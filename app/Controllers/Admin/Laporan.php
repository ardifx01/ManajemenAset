<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\LaporanModel;
use App\Models\AsetModel;

class Laporan extends BaseController
{
    protected $laporanModel;
    protected $asetModel;
    protected $session;

    public function __construct()
    {
        $this->laporanModel = new LaporanModel();
        $this->asetModel = new AsetModel();
        $this->session = session();
    }

    public function index()
    {
        return view('admin/laporan/index');
    }

    public function tambah()
    {
        try {
            $rules = [
                'kendaraan_id' => 'required|numeric',
                'jenis_laporan' => 'required|in_list[Laporan Insiden,Laporan Kerusakan]',
                'tanggal_kejadian' => 'required|valid_date',
                'lokasi_kejadian' => 'required|min_length[3]',
                'keterangan' => 'required|min_length[10]',
                'bukti_foto' => 'uploaded[bukti_foto]|max_size[bukti_foto,2048]|mime_in[bukti_foto,image/jpg,image/jpeg,image/png]'
            ];

            if (!$this->validate($rules)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $this->validator->getErrors()
                ]);
            }

            $bukti_foto = $this->request->getFile('bukti_foto');
            $fileName = '';

            if ($bukti_foto->isValid() && !$bukti_foto->hasMoved()) {
                $fileName = $bukti_foto->getRandomName();
                $bukti_foto->move(ROOTPATH . 'public/uploads/laporan', $fileName);
            }

            $data = [
                'kendaraan_id' => $this->request->getPost('kendaraan_id'),
                'user_id' => user_id(),
                'jenis_laporan' => $this->request->getPost('jenis_laporan'),
                'tanggal_kejadian' => $this->request->getPost('tanggal_kejadian'),
                'lokasi_kejadian' => $this->request->getPost('lokasi_kejadian'),
                'keterangan' => $this->request->getPost('keterangan'),
                'bukti_foto' => $fileName,
                'tindak_lanjut' => $this->request->getPost('tindak_lanjut'),
                'status' => 'pending'
            ];

            $this->laporanModel->insert($data);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Laporan berhasil ditambahkan'
            ]);

        } catch (\Exception $e) {
            log_message('error', $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan laporan'
            ]);
        }
    }

    public function getLaporan($id = null)
    {
        try {
            if ($id !== null) {
                $laporan = $this->laporanModel->getLaporanWithDetails($id);
                if (!$laporan) {
                    throw new \Exception('Laporan tidak ditemukan');
                }
                return $this->response->setJSON($laporan);
            }

            $laporan = $this->laporanModel->getLaporanWithDetails();
            return $this->response->setJSON($laporan);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ])->setStatusCode(404);
        }
    }

    public function update($id)
    {
        try {
            $rules = [
                'status' => 'required|in_list[pending,proses,selesai,ditolak]',
                'tindak_lanjut' => 'required_if[status,selesai]'
            ];

            if (!$this->validate($rules)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => $this->validator->getErrors()
                ]);
            }

            $data = [
                'status' => $this->request->getPost('status'),
                'tindak_lanjut' => $this->request->getPost('tindak_lanjut'),
                'ditindaklanjuti_oleh' => user_id(),
                'tanggal_tindak_lanjut' => date('Y-m-d H:i:s')
            ];

            $this->laporanModel->update($id, $data);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Status laporan berhasil diupdate'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengupdate laporan'
            ]);
        }
    }

    public function delete($id)
    {
        try {
            $laporan = $this->laporanModel->find($id);
            if (!$laporan) {
                throw new \Exception('Laporan tidak ditemukan');
            }

            if (!empty($laporan['bukti_foto'])) {
                $filePath = ROOTPATH . 'public/uploads/laporan/' . $laporan['bukti_foto'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            $this->laporanModel->delete($id);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Laporan berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getStatistik()
    {
        try {
            $stats = $this->laporanModel->getDashboardStats();
            return $this->response->setJSON([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal memuat statistik'
            ]);
        }
    }
}