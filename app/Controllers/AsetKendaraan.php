<?php

namespace App\Controllers;

use App\Models\AsetModel;
use App\Models\KembaliModel;
use App\Models\PinjamModel;

class AsetKendaraan extends BaseController
{
    private function getUserData($userId)
    {
        $userModel = new \Myth\Auth\Models\UserModel();
        return $userModel->find($userId);
    }
    private function check_file_with_virustotal($file)
    {
        if ($file->getMimeType() !== 'application/pdf') {
            return true;
        }

        $api_key = '964f15a6e58be968be71f229b33c52b56a9ba2ccfd8969df075e2700dc584d4a';
        $api_url_scan = 'https://www.virustotal.com/vtapi/v2/file/scan';
        $api_url_report = 'https://www.virustotal.com/vtapi/v2/file/report';

        if ($file->getSize() > 32 * 1024 * 1024) {
            return true;
        }

        try {
            $post = array(
                'apikey' => $api_key,
                'file' => new \CURLFile($file->getTempName(), 'application/pdf', $file->getName())
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $api_url_scan);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

            $scan_response = curl_exec($ch);

            if (curl_errno($ch)) {
                log_message('error', 'Curl error: ' . curl_error($ch));
                return true;
            }

            curl_close($ch);

            $scan_result = json_decode($scan_response, true);

            if (!isset($scan_result['scan_id'])) {
                log_message('error', 'Invalid scan response: ' . json_encode($scan_result));
                return true;
            }

            sleep(5);

            $post = array(
                'apikey' => $api_key,
                'resource' => $scan_result['scan_id']
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $api_url_report);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

            $report_response = curl_exec($ch);
            curl_close($ch);

            $report_result = json_decode($report_response, true);

            if (!isset($report_result['response_code']) || $report_result['response_code'] === 0) {
                log_message('warning', 'File belum pernah di-scan sebelumnya');
                return false;
            }

            return isset($report_result['positives']) && $report_result['positives'] > 0;

        } catch (\Exception $e) {
            log_message('error', 'Error checking file: ' . $e->getMessage());
            return true;
        }
    }
    public function edit($id)
    {
        $model = new AsetModel();
        $aset = $model->find($id);

        if (!$aset) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Data aset tidak ditemukan'
            ]);
        }

        if (!in_groups('admin')) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Unauthorized Access'
            ]);
        }

        $data = [];
        $fields = [
            'kategori_id',
            'no_sk_psp',
            'kode_barang',
            'merk',
            'tahun_pembuatan',
            'kapasitas',
            'no_polisi',
            'no_bpkb',
            'no_stnk',
            'no_rangka',
            'kondisi'
        ];

        foreach ($fields as $field) {
            $value = $this->request->getPost($field);
            if ($value !== null && $value !== '') {
                $data[$field] = $value;
            }
        }

        $data['updated_at'] = date('Y-m-d H:i:s');

        $gambar_mobil = $this->request->getFile('gambar_mobil');
        if ($gambar_mobil && $gambar_mobil->isValid()) {
            if ($gambar_mobil->getSize() > 5 * 1024 * 1024) {
                return $this->response->setJSON([
                    'success' => false,
                    'error' => 'Ukuran file gambar mobil tidak boleh lebih dari 5MB'
                ]);
            }

            if (!empty($aset['gambar_mobil'])) {
                $oldImagePath = ROOTPATH . 'public/uploads/images/' . $aset['gambar_mobil'];
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $newName = $gambar_mobil->getRandomName();
            if ($gambar_mobil->move(ROOTPATH . 'public/uploads/images', $newName)) {
                $data['gambar_mobil'] = $newName;
            }
        }

        if (empty($data)) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Tidak ada data yang diubah'
            ]);
        }

        try {
            $model->update($id, $data);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error updating asset: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Gagal memperbarui data: ' . $e->getMessage()
            ]);
        }
    }
    public function getAsetById($id)
    {
        try {
            if (!in_groups('admin')) {
                return $this->response->setJSON([
                    'success' => false,
                    'error' => 'Unauthorized Access'
                ]);
            }

            $model = new AsetModel();
            $aset = $model->find($id);

            if ($aset) {
                unset($aset['deleted_at']);

                return $this->response->setJSON([
                    'success' => true,
                    'data' => $aset
                ]);
            }

            return $this->response->setJSON([
                'success' => false,
                'error' => 'Data tidak ditemukan'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error in getAsetById: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Terjadi kesalahan saat mengambil data: ' . $e->getMessage()
            ]);
        }
    }
    public function delete($id)
    {
        $model = new AsetModel();

        try {
            $model->delete($id);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Gagal menghapus data: ' . $e->getMessage()
            ]);
        }
    }
    protected $email;

    public function __construct()
    {
        helper(['auth', 'email']);
        $this->email = \Config\Services::email();

        $config = config('Email');
        $this->email->initialize($config);
    }
    public function getKendaraan()
    {
        $model = new AsetModel();
        $kendaraan = $model->findAll();
        return $this->response->setJSON($kendaraan);
    }
    public function getKendaraanDipinjam()
    {
        $model = new PinjamModel();
        $asetModel = new AsetModel();

        try {
            $pinjaman = $model->where('deleted_at', null)->findAll();
            log_message('debug', 'Data Pinjaman: ' . json_encode($pinjaman));

            if (empty($pinjaman)) {
                log_message('debug', 'Tidak ada data peminjaman aktif');
                return $this->response->setJSON([]);
            }

            $kendaraanIds = array_map('strval', array_column($pinjaman, 'kendaraan_id'));
            log_message('debug', 'ID Kendaraan: ' . json_encode($kendaraanIds));

            $builder = $asetModel->builder();
            $builder->select('assets.*, pinjam.tanggal_pinjam, pinjam.tanggal_kembali');
            $builder->join('pinjam', 'CAST(pinjam.kendaraan_id AS VARCHAR) = CAST(assets.id AS VARCHAR)', 'inner');
            $builder->whereIn('assets.id', $kendaraanIds);
            $builder->where('pinjam.deleted_at IS NULL');

            $kendaraan = $builder->get()->getResult();
            log_message('debug', 'Hasil Query: ' . json_encode($kendaraan));

            return $this->response->setJSON($kendaraan);
        } catch (\Exception $e) {
            log_message('error', 'Error in getKendaraanDipinjam: ' . $e->getMessage());
            return $this->response->setJSON(['error' => $e->getMessage()]);
        }
    }
    public function getPeminjamanData($kendaraanId)
    {
        $model = new PinjamModel();
        $asetModel = new AsetModel();

        $data = $model->where([
            'kendaraan_id' => $kendaraanId,
            'deleted_at' => null
        ])->first();

        if (!$data) {
            return $this->response->setJSON(['error' => 'Data peminjaman tidak ditemukan']);
        }

        if ($data['user_id'] != user_id()) {
            return $this->response->setJSON([
                'error' => 'Anda tidak memiliki akses untuk mengembalikan kendaraan ini'
            ]);
        }

        $data['tanggal_pinjam'] = date('Y-m-d', strtotime($data['tanggal_pinjam']));

        $kendaraan = $asetModel->find($kendaraanId);
        if ($kendaraan) {
            $data['merk'] = $kendaraan['merk'];
            $data['no_polisi'] = $kendaraan['no_polisi'];
        }

        return $this->response->setJSON($data);
    }
    public function tambah()
    {
        $model = new AsetModel();

        $userId = user_id();
        $kategori_id = $this->request->getPost('kategori_id');
        $no_sk_psp = $this->request->getPost('no_sk_psp');
        $kode_barang = $this->request->getPost('kode_barang');
        $merk = $this->request->getPost('merk');
        $tahun_pembuatan = $this->request->getPost('tahun_pembuatan');
        $kapasitas = $this->request->getPost('kapasitas');
        $no_polisi = $this->request->getPost('no_polisi');
        $no_bpkb = $this->request->getPost('no_bpkb');
        $no_stnk = $this->request->getPost('no_stnk');
        $no_rangka = $this->request->getPost('no_rangka');
        $kondisi = $this->request->getPost('kondisi');
        $gambar_mobil = $this->request->getFile('gambar_mobil');

        if ($gambar_mobil->getSize() > 5 * 1024 * 1024) {
            return $this->response->setJSON(['error' => 'Ukuran file gambar mobil tidak boleh lebih dari 5MB']);
        }

        $newName = '';
        if ($gambar_mobil->isValid() && !$gambar_mobil->hasMoved()) {
            $newName = $gambar_mobil->getRandomName();
            $gambar_mobil->move(ROOTPATH . 'public/uploads/images', $newName);
        }

        $data = [
            'user_id' => $userId,
            'kategori_id' => $kategori_id,
            'no_sk_psp' => $no_sk_psp,
            'kode_barang' => $kode_barang,
            'merk' => $merk,
            'tahun_pembuatan' => $tahun_pembuatan,
            'kapasitas' => $kapasitas,
            'no_polisi' => $no_polisi,
            'no_bpkb' => $no_bpkb,
            'no_stnk' => $no_stnk,
            'no_rangka' => $no_rangka,
            'kondisi' => $kondisi,
            'created_at' => date('Y-m-d H:i:s'),
            'gambar_mobil' => $newName,
        ];

        try {
            $model->insert($data);
            return $this->response->setJSON(['success' => true, 'message' => 'Data berhasil disimpan']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['error' => 'Gagal menyimpan data: ' . $e->getMessage()]);
        }
    }
    public function pinjam()
    {
        $model = new PinjamModel();
        $asetModel = new AsetModel();
        $db = db_connect();

        $userId = user_id();
        $nama_penanggung_jawab = $this->request->getPost('nama_penanggung_jawab');
        $nip_nrp = $this->request->getPost('nip_nrp');
        $pangkat_golongan = $this->request->getPost('pangkat_golongan');
        $jabatan = $this->request->getPost('jabatan');
        $unit_organisasi = $this->request->getPost('unit_organisasi');
        $kendaraan_id = $this->request->getPost('kendaraan_id');
        $pengemudi = $this->request->getPost('pengemudi');
        $no_hp = $this->request->getPost('no_hp');
        $tanggal_pinjam = $this->request->getPost('tanggal_pinjam');
        $tanggal_kembali = $this->request->getPost('tanggal_kembali');
        $urusan_kedinasan = $this->request->getPost('urusan_kedinasan');
        $surat_permohonan = $this->request->getFile('surat_permohonan');

        if (!$surat_permohonan->isValid() || $surat_permohonan->getError() !== 0) {
            return $this->response->setJSON(['error' => 'Surat Permohonan tidak valid: ' . $surat_permohonan->getErrorString()]);
        }

        if ($this->check_file_with_virustotal($surat_permohonan)) {
            return $this->response->setJSON([
                'error' => 'File Surat Permohonan terdeteksi tidak aman'
            ]);
        }

        $suratPermohonanName = $surat_permohonan->getRandomName();

        try {
            $surat_permohonan->move(ROOTPATH . 'public/uploads/documents', $suratPermohonanName);
        } catch (\Exception $e) {
            return $this->response->setJSON(['error' => 'Gagal mengupload file: ' . $e->getMessage()]);
        }

        $validationRules = [
            'nama_penanggung_jawab' => 'required',
            'nip_nrp' => 'required',
            'pangkat_golongan' => 'required',
            'jabatan' => 'required',
            'unit_organisasi' => 'required',
            'kendaraan_id' => 'required',
            'pengemudi' => 'required',
            'no_hp' => 'required',
            'tanggal_pinjam' => 'required',
            'tanggal_kembali' => 'required',
            'urusan_kedinasan' => 'required'
        ];

        foreach ($validationRules as $field => $rule) {
            $value = $this->request->getPost($field);
            if (empty($value)) {
                @unlink(ROOTPATH . 'public/uploads/documents/' . $suratPermohonanName);
                return $this->response->setJSON([
                    'error' => ucwords(str_replace('_', ' ', $field)) . ' harus diisi.'
                ]);
            }
        }

        $asset = $asetModel->find($kendaraan_id);
        if (!$asset) {
            @unlink(ROOTPATH . 'public/uploads/documents/' . $suratPermohonanName);
            return $this->response->setJSON([
                'error' => 'Kendaraan tidak ditemukan dalam database.'
            ]);
        }

        $existingPinjam = $model->where([
            'kendaraan_id' => $kendaraan_id,
            'status' => 'disetujui',
            'is_returned' => false,
            'deleted_at' => null
        ])->first();

        if ($existingPinjam) {
            @unlink(ROOTPATH . 'public/uploads/documents/' . $suratPermohonanName);
            return $this->response->setJSON([
                'error' => 'Kendaraan ini sedang dipinjam.'
            ]);
        }

        $pendingPinjam = $model->where([
            'kendaraan_id' => $kendaraan_id,
            'status' => 'pending',
            'deleted_at' => null
        ])->first();

        if ($pendingPinjam) {
            @unlink(ROOTPATH . 'public/uploads/documents/' . $suratPermohonanName);
            return $this->response->setJSON([
                'error' => 'Kendaraan ini sedang dalam proses verifikasi peminjaman.'
            ]);
        }

        $db->transStart();

        try {
            $data = [
                'user_id' => $userId,
                'nama_penanggung_jawab' => $nama_penanggung_jawab,
                'nip_nrp' => $nip_nrp,
                'pangkat_golongan' => $pangkat_golongan,
                'jabatan' => $jabatan,
                'unit_organisasi' => $unit_organisasi,
                'kendaraan_id' => $kendaraan_id,
                'pengemudi' => $pengemudi,
                'no_hp' => $no_hp,
                'tanggal_pinjam' => $tanggal_pinjam,
                'tanggal_kembali' => $tanggal_kembali,
                'urusan_kedinasan' => $urusan_kedinasan,
                'kode_barang' => $asset['kode_barang'],
                'surat_permohonan' => $suratPermohonanName,
                'surat_jalan_admin' => null,
                'status' => PinjamModel::STATUS_PENDING,
                'is_returned' => false,
                'keterangan' => null,
                'created_at' => date('Y-m-d H:i:s')
            ];

            $model->insert($data);

            $asetModel->update($kendaraan_id, [
                'status_pinjam' => 'Dalam Verifikasi'
            ]);

            $db->transComplete();

            if ($db->transStatus() === false) {
                @unlink(ROOTPATH . 'public/uploads/documents/' . $suratPermohonanName);
                return $this->response->setJSON([
                    'error' => 'Gagal menyimpan data: Terjadi kesalahan pada transaksi database'
                ]);
            }

            $userData = user()->toArray();
            $data['user_email'] = $userData['email'];
            sendPeminjamanNotification($data, 'new');

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data peminjaman berhasil disimpan'
            ]);

        } catch (\Exception $e) {
            $db->transRollback();
            @unlink(ROOTPATH . 'public/uploads/documents/' . $suratPermohonanName);
            log_message('error', 'Error in loan process: ' . $e->getMessage());
            return $this->response->setJSON([
                'error' => 'Gagal menyimpan data: ' . $e->getMessage()
            ]);
        }
    }
    public function kembali()
    {
        $model = new KembaliModel();
        $pinjamModel = new PinjamModel();
        $asetModel = new AsetModel();
        $db = db_connect();

        $userId = user_id();
        $nama_penanggung_jawab = $this->request->getPost('nama_penanggung_jawab');
        $nip_nrp = $this->request->getPost('nip_nrp');
        $pangkat_golongan = $this->request->getPost('pangkat_golongan');
        $jabatan = $this->request->getPost('jabatan');
        $unit_organisasi = $this->request->getPost('unit_organisasi');
        $kendaraan_id = $this->request->getPost('kendaraan_id') ?? $this->request->getPost('kendaraan_id_hidden');
        $no_hp = $this->request->getPost('no_hp');
        $tanggal_pinjam = $this->request->getPost('tanggal_pinjam');
        $tanggal_kembali = $this->request->getPost('tanggal_kembali');
        $suratPengembalian = $this->request->getFile('surat_pengembalian');
        $beritaAcara = $this->request->getFile('berita_acara_pengembalian');

        if (empty($kendaraan_id)) {
            return $this->response->setJSON([
                'error' => 'Data kendaraan tidak valid'
            ]);
        }

        $asset = $asetModel->find($kendaraan_id);
        if (!$asset) {
            return $this->response->setJSON(['error' => 'Kendaraan tidak ditemukan dalam database.']);
        }

        $pinjam = $pinjamModel->where([
            'kendaraan_id' => $kendaraan_id,
            'status' => 'disetujui',
            'is_returned' => false,
            'deleted_at' => null
        ])->first();

        if (!$pinjam) {
            return $this->response->setJSON(['error' => 'Tidak ada peminjaman aktif untuk kendaraan ini']);
        }

        if ($pinjam['user_id'] !== $userId) {
            return $this->response->setJSON([
                'error' => 'Anda tidak memiliki akses untuk mengembalikan kendaraan ini'
            ]);
        }

        $suratPengembalianName = null;
        if ($suratPengembalian && $suratPengembalian->isValid()) {
            if ($this->check_file_with_virustotal($suratPengembalian)) {
                return $this->response->setJSON(['error' => 'File Surat Pengembalian terdeteksi tidak aman']);
            }
            $suratPengembalianName = $suratPengembalian->getRandomName();
            try {
                $suratPengembalian->move(ROOTPATH . 'public/uploads/documents', $suratPengembalianName);
            } catch (\Exception $e) {
                return $this->response->setJSON(['error' => 'Gagal mengupload surat pengembalian: ' . $e->getMessage()]);
            }
        }

        $beritaAcaraName = null;
        if ($beritaAcara && $beritaAcara->isValid()) {
            if ($this->check_file_with_virustotal($beritaAcara)) {
                if ($suratPengembalianName) {
                    @unlink(ROOTPATH . 'public/uploads/documents/' . $suratPengembalianName);
                }
                return $this->response->setJSON(['error' => 'File Berita Acara terdeteksi tidak aman']);
            }
            $beritaAcaraName = $beritaAcara->getRandomName();
            try {
                $beritaAcara->move(ROOTPATH . 'public/uploads/documents', $beritaAcaraName);
            } catch (\Exception $e) {
                if ($suratPengembalianName) {
                    @unlink(ROOTPATH . 'public/uploads/documents/' . $suratPengembalianName);
                }
                return $this->response->setJSON(['error' => 'Gagal mengupload berita acara: ' . $e->getMessage()]);
            }
        }

        $requiredFields = [
            'nama_penanggung_jawab' => $nama_penanggung_jawab,
            'nip_nrp' => $nip_nrp,
            'pangkat_golongan' => $pangkat_golongan,
            'jabatan' => $jabatan,
            'unit_organisasi' => $unit_organisasi,
            'no_hp' => $no_hp,
            'tanggal_pinjam' => $tanggal_pinjam,
            'tanggal_kembali' => $tanggal_kembali
        ];

        foreach ($requiredFields as $field => $value) {
            if (empty($value)) {
                $this->cleanupFiles($suratPengembalianName, $beritaAcaraName);
                return $this->response->setJSON(['error' => ucwords(str_replace('_', ' ', $field)) . ' harus diisi.']);
            }
        }

        try {
            $db->transStart();
            $data = [
                'user_id' => $userId,
                'nama_penanggung_jawab' => $nama_penanggung_jawab,
                'nip_nrp' => $nip_nrp,
                'pangkat_golongan' => $pangkat_golongan,
                'jabatan' => $jabatan,
                'unit_organisasi' => $unit_organisasi,
                'kendaraan_id' => $kendaraan_id,
                'pinjam_id' => $pinjam['id'],
                'no_hp' => $no_hp,
                'tanggal_pinjam' => $tanggal_pinjam,
                'tanggal_kembali' => $tanggal_kembali,
                'kode_barang' => $asset['kode_barang'],
                'status' => KembaliModel::STATUS_PENDING,
                'keterangan' => null,
                'created_at' => date('Y-m-d H:i:s')
            ];

            if ($suratPengembalianName) {
                $data['surat_pengembalian'] = $suratPengembalianName;
            }
            if ($beritaAcaraName) {
                $data['berita_acara_pengembalian'] = $beritaAcaraName;
            }

            $result = $model->insert($data);

            if (!$result) {
                throw new \Exception('Gagal menyimpan data pengembalian');
            }

            $pinjamModel->update($pinjam['id'], ['is_returned' => true]);

            $asetModel->update($kendaraan_id, [
                'status_pinjam' => 'Dalam Verifikasi Pengembalian'
            ]);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Terjadi kesalahan pada transaksi database');
            }

            $userData = user()->toArray();
            $data['user_email'] = $userData['email'];
            sendPengembalianNotification($data, 'new');

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data pengembalian berhasil disimpan'
            ]);

        } catch (\Exception $e) {
            $db->transRollback();
            $this->cleanupFiles($suratPengembalianName, $beritaAcaraName);
            log_message('error', 'Error in return process: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());

            return $this->response->setJSON([
                'error' => 'Gagal menyimpan data: ' . $e->getMessage()
            ]);
        }
    }

    private function cleanupFiles($suratPengembalian = null, $beritaAcara = null)
    {
        if ($suratPengembalian) {
            @unlink(ROOTPATH . 'public/uploads/documents/' . $suratPengembalian);
        }
        if ($beritaAcara) {
            @unlink(ROOTPATH . 'public/uploads/documents/' . $beritaAcara);
        }
    }
    public function verifikasiPeminjaman()
    {
        if (!in_groups('admin')) {
            return $this->response->setJSON(['error' => 'Unauthorized Access']);
        }

        $pinjamId = $this->request->getPost('pinjam_id');
        $status = $this->request->getPost('status');
        $keterangan = $this->request->getPost('keterangan');
        $surat_jalan_admin = $this->request->getFile('surat_jalan_admin');

        $model = new PinjamModel();
        $asetModel = new AsetModel();
        $db = db_connect();

        if (!in_array($status, [PinjamModel::STATUS_DISETUJUI, PinjamModel::STATUS_DITOLAK])) {
            return $this->response->setJSON(['error' => 'Status tidak valid']);
        }

        $pinjam = $model->find($pinjamId);
        if (!$pinjam) {
            return $this->response->setJSON(['error' => 'Data peminjaman tidak ditemukan']);
        }

        if ($status === 'disetujui') {
            if (!$surat_jalan_admin || !$surat_jalan_admin->isValid()) {
                return $this->response->setJSON(['error' => 'Surat Jalan harus diunggah untuk menyetujui peminjaman']);
            }

            if ($this->check_file_with_virustotal($surat_jalan_admin)) {
                return $this->response->setJSON(['error' => 'File Surat Jalan terdeteksi tidak aman']);
            }
        }

        $db->transStart();

        try {
            $updateData = [
                'status' => $status,
                'keterangan' => $keterangan
            ];

            if ($status === 'disetujui') {
                $suratJalanName = $surat_jalan_admin->getRandomName();
                try {
                    if ($surat_jalan_admin->move(ROOTPATH . 'public/uploads/documents', $suratJalanName)) {
                        $updateData['surat_jalan_admin'] = $suratJalanName;
                    }
                } catch (\Exception $e) {
                    return $this->response->setJSON(['error' => 'Gagal mengupload surat jalan: ' . $e->getMessage()]);
                }
            }

            $model->update($pinjamId, $updateData);

            $statusAset = $status === 'disetujui' ? 'Dipinjam' : 'Tersedia';
            $asetModel->update($pinjam['kendaraan_id'], ['status_pinjam' => $statusAset]);

            $db->transComplete();

            if ($db->transStatus() === false) {
                if (isset($suratJalanName)) {
                    @unlink(ROOTPATH . 'public/uploads/documents/' . $suratJalanName);
                }
                return $this->response->setJSON(['error' => 'Terjadi kesalahan pada transaksi database']);
            }

            $userData = $this->getUserData($pinjam['user_id']);
            $notifData = array_merge($pinjam, [
                'user_email' => $userData->email,
                'status' => $status,
                'keterangan' => $keterangan
            ]);
            sendPeminjamanNotification($notifData, 'verified');

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Verifikasi peminjaman berhasil'
            ]);

        } catch (\Exception $e) {
            $db->transRollback();
            if (isset($suratJalanName)) {
                @unlink(ROOTPATH . 'public/uploads/documents/' . $suratJalanName);
            }
            log_message('error', 'Error in verification: ' . $e->getMessage());
            return $this->response->setJSON(['error' => $e->getMessage()]);
        }
    }
    public function verifikasiPengembalian()
    {
        if (!in_groups('admin')) {
            return $this->response->setJSON(['error' => 'Unauthorized Access']);
        }

        $kembaliId = $this->request->getPost('kembali_id');
        $status = $this->request->getPost('status');
        $keterangan = $this->request->getPost('keterangan');

        $model = new KembaliModel();
        $pinjamModel = new PinjamModel();
        $asetModel = new AsetModel();
        $db = db_connect();

        if (!in_array($status, [KembaliModel::STATUS_DISETUJUI, KembaliModel::STATUS_DITOLAK])) {
            return $this->response->setJSON(['error' => 'Status tidak valid']);
        }

        $kembali = $model->find($kembaliId);
        if (!$kembali) {
            return $this->response->setJSON(['error' => 'Data pengembalian tidak ditemukan']);
        }

        $pinjam = $pinjamModel->where([
            'kendaraan_id' => $kembali['kendaraan_id'],
            'status' => 'disetujui',
            'is_returned' => true,
            'deleted_at' => null
        ])->first();

        if (!$pinjam) {
            return $this->response->setJSON(['error' => 'Data peminjaman terkait tidak ditemukan']);
        }

        $db->transStart();

        try {
            $model->update($kembaliId, [
                'status' => $status,
                'keterangan' => $keterangan
            ]);

            if ($status === 'disetujui') {
                $asetModel->update($kembali['kendaraan_id'], [
                    'status_pinjam' => 'Tersedia'
                ]);

                $pinjamModel->update($pinjam['id'], [
                    'status' => 'selesai',
                    'is_returned' => true
                ]);

            } else if ($status === 'ditolak') {
                $asetModel->update($kembali['kendaraan_id'], [
                    'status_pinjam' => 'Dipinjam'
                ]);

                $pinjamModel->update($pinjam['id'], [
                    'is_returned' => false
                ]);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->response->setJSON(['error' => 'Terjadi kesalahan pada transaksi database']);
            }

            $userData = $this->getUserData($kembali['user_id']);
            $notifData = array_merge($kembali, [
                'user_email' => $userData->email,
                'status' => $status,
                'keterangan' => $keterangan
            ]);
            sendPengembalianNotification($notifData, 'verified');

            $message = $status === 'disetujui'
                ? 'Pengembalian kendaraan berhasil disetujui'
                : 'Pengembalian kendaraan ditolak. Status dikembalikan ke Dipinjam';

            return $this->response->setJSON([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Error in verification: ' . $e->getMessage());
            return $this->response->setJSON(['error' => $e->getMessage()]);
        }
    }
}