<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AsetModel;
use App\Models\PemeliharaanRutinModel;
use Config\Email;
use TCPDF;

class PemeliharaanRutin extends BaseController
{
    protected $pemeliharaanModel;
    protected $asetModel;
    protected $email;

    public function __construct()
    {
        $this->pemeliharaanModel = new PemeliharaanRutinModel();
        $this->asetModel = new AsetModel();
        $this->email = \Config\Services::email();
    }

    public function index()
    {
        return view('admin/laporan/pemeliharaan-rutin');
    }

    public function exportExcel()
    {
        try {
            $builder = $this->pemeliharaanModel->builder();
            $builder->select('assets.merk, assets.no_polisi, pemeliharaan_rutin.*');
            $builder->join('assets', 'assets.id = pemeliharaan_rutin.kendaraan_id');
            $builder->where('pemeliharaan_rutin.deleted_at IS NULL');

            if ($this->request->getGet('kendaraan_id')) {
                $builder->where('kendaraan_id', $this->request->getGet('kendaraan_id'));
            }
            if ($this->request->getGet('jenis')) {
                $builder->where('jenis_pemeliharaan', $this->request->getGet('jenis'));
            }
            if ($this->request->getGet('status')) {
                $builder->where('status', $this->request->getGet('status'));
            }

            $data = $builder->get()->getResultArray();

            $output = fopen('php://temp', 'w');

            fputcsv($output, [
                'No',
                'Kendaraan',
                'Jenis Pemeliharaan',
                'Tanggal Terjadwal',
                'Status',
                'Bengkel',
                'Biaya',
                'Keterangan'
            ]);

            foreach ($data as $index => $row) {
                fputcsv($output, [
                    $index + 1,
                    $row['merk'] . ' - ' . $row['no_polisi'],
                    $row['jenis_pemeliharaan'],
                    date('d/m/Y', strtotime($row['tanggal_terjadwal'])),
                    $row['status'],
                    $row['bengkel'],
                    number_format($row['biaya'], 0, ',', '.'),
                    $row['keterangan']
                ]);
            }

            rewind($output);

            $csv_content = stream_get_contents($output);
            fclose($output);

            return $this->response
                ->setHeader('Content-Type', 'text/csv')
                ->setHeader('Content-Disposition', 'attachment; filename="Jadwal_Pemeliharaan_' . date('Y-m-d_His') . '.csv"')
                ->setBody($csv_content);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal mengexport data: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function exportPDF()
    {
        try {
            $builder = $this->pemeliharaanModel->builder();
            $builder->select('assets.merk, assets.no_polisi, pemeliharaan_rutin.*');
            $builder->join('assets', 'assets.id = pemeliharaan_rutin.kendaraan_id');
            $builder->where('pemeliharaan_rutin.deleted_at IS NULL');

            if ($this->request->getGet('kendaraan_id')) {
                $builder->where('kendaraan_id', $this->request->getGet('kendaraan_id'));
            }
            if ($this->request->getGet('jenis')) {
                $builder->where('jenis_pemeliharaan', $this->request->getGet('jenis'));
            }
            if ($this->request->getGet('status')) {
                $builder->where('status', $this->request->getGet('status'));
            }

            $data = $builder->get()->getResultArray();

            $viewData = [
                'title' => 'Jadwal Pemeliharaan Kendaraan',
                'date' => date('d/m/Y H:i:s'),
                'data' => $data
            ];

            $html = view('admin/laporan/pdf/print_pemeliharaan', $viewData);

            return $this->response
                ->setHeader('Content-Type', 'text/html')
                ->setBody($html);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal mengexport data: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function getKendaraan()
    {
        try {
            $kendaraan = $this->asetModel
                ->select('id, merk, no_polisi, kode_barang')
                ->where('deleted_at IS NULL')
                ->orderBy('merk', 'ASC')
                ->findAll();

            if (empty($kendaraan)) {
                return $this->response->setJSON([])
                    ->setStatusCode(200);
            }

            return $this->response->setJSON($kendaraan)
                ->setStatusCode(200);
        } catch (\Exception $e) {
            log_message('error', 'Error in getKendaraan: ' . $e->getMessage());
            return $this->response->setJSON([
                'error' => true,
                'message' => 'Gagal mengambil data kendaraan: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function getPemeliharaan()
    {
        try {
            $builder = $this->pemeliharaanModel->builder();
            $builder->select('pemeliharaan_rutin.*, assets.merk, assets.no_polisi');
            $builder->join('assets', 'assets.id = pemeliharaan_rutin.kendaraan_id');
            $builder->where('pemeliharaan_rutin.deleted_at IS NULL');

            if ($this->request->getGet('kendaraan_id')) {
                $builder->where('kendaraan_id', $this->request->getGet('kendaraan_id'));
            }
            if ($this->request->getGet('jenis')) {
                $builder->where('jenis_pemeliharaan', $this->request->getGet('jenis'));
            }
            if ($this->request->getGet('status')) {
                $builder->where('status', $this->request->getGet('status'));
            }

            $builder->orderBy('pemeliharaan_rutin.created_at', 'DESC');

            $data = $builder->get()->getResultArray();

            return $this->response->setJSON($data);

        } catch (\Exception $e) {
            log_message('error', 'Error in getPemeliharaan: ' . $e->getMessage());
            return $this->response->setJSON([
                'error' => true,
                'message' => 'Gagal mengambil data pemeliharaan: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function tambahJadwal()
    {
        try {
            $kendaraanId = $this->request->getPost('kendaraan_id');
            $jenisPemeliharaan = $this->request->getPost('jenis_pemeliharaan');
            $tanggalTerjadwal = $this->request->getPost('tanggal_terjadwal');
            $bengkel = $this->request->getPost('bengkel');
            $biaya = $this->request->getPost('biaya');
            $keterangan = $this->request->getPost('keterangan');

            $aset = $this->asetModel->find($kendaraanId);
            if (!$aset) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Data aset tidak ditemukan'
                ]);
            }

            $jadwalService = [
                'kendaraan_id' => $kendaraanId,
                'jenis_pemeliharaan' => $jenisPemeliharaan,
                'tanggal_terjadwal' => $tanggalTerjadwal,
                'bengkel' => $bengkel,
                'biaya' => $biaya,
                'keterangan' => $keterangan,
                'status' => 'Pending',
                'created_at' => date('Y-m-d H:i:s')
            ];

            $this->pemeliharaanModel->insert($jadwalService);

            $this->scheduleEmailNotification($aset, $jadwalService);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Jadwal pemeliharaan berhasil dibuat'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal membuat jadwal: ' . $e->getMessage()
            ]);
        }
    }

    protected function scheduleEmailNotification($aset, $jadwal)
    {
        try {
            $startTime = time();
            log_message('info', 'Mulai proses pengiriman email untuk kendaraan: ' . $aset['no_polisi']);

            $emailConfig = new Email();

            $this->email->initialize([
                'protocol' => $emailConfig->protocol,
                'SMTPHost' => $emailConfig->SMTPHost,
                'SMTPUser' => $emailConfig->SMTPUser,
                'SMTPPass' => $emailConfig->SMTPPass,
                'SMTPPort' => $emailConfig->SMTPPort,
                'SMTPTimeout' => $emailConfig->SMTPTimeout,
                'SMTPCrypto' => $emailConfig->SMTPCrypto,
                'mailType' => $emailConfig->mailType,
                'charset' => $emailConfig->charset,
                'validate' => $emailConfig->validate,
                'priority' => $emailConfig->priority
            ]);

            $this->email->setFrom($emailConfig->SMTPUser, 'Sistem Manajemen Kendaraan');
            $this->email->setTo($emailConfig->SMTPUser);
            $this->email->setSubject('Pemberitahuan Jadwal Pemeliharaan Kendaraan');

            $message = "
            <p>Segera Lakukan Pemeliharaan Rutin</P>
            <br>
            Detail Kendaraan:
            <br>
            Merk         : {$aset['merk']}
            <br>
            No Polisi    : {$aset['no_polisi']}
            <br>
            Kode Barang  : {$aset['kode_barang']}
            <br>
            <br>
            Detail Pemeliharaan:
            <br>
            Jenis        : {$jadwal['jenis_pemeliharaan']}
            <br>
            Terjadwal    : " . date('d F Y', strtotime($jadwal['tanggal_terjadwal'])) . "
            <br>
            Bengkel      : {$jadwal['bengkel']}
            <br>
            Estimasi Biaya: Rp " . number_format($jadwal['biaya'], 0, ',', '.') . "
            <br>
            Keterangan   : {$jadwal['keterangan']}
            <br>
            <br>
            Mohon segera dijadwalkan pemeliharaannya.
            <br>
            <br>
            Email ini dikirim secara otomatis oleh sistem.
            Waktu Pengiriman : " . date('Y-m-d H:i:s', time()) . "
            ";

            $this->email->setMessage($message);

            if (!$this->email->send(true)) {
                throw new \Exception('Gagal mengirim email: ' . $this->email->printDebugger(['headers']));
            }

            log_message('info', 'Email berhasil dikirim untuk kendaraan: ' . $aset['no_polisi']);

            return true;

        } catch (\Exception $e) {
            log_message('error', 'Gagal mengirim email untuk kendaraan ' . $aset['no_polisi'] .
                ': ' . $e->getMessage());
            return false;
        }
    }
    public function getJadwalById($id)
    {
        try {
            $jadwal = $this->pemeliharaanModel->builder()
                ->select('pemeliharaan_rutin.*, assets.merk, assets.no_polisi')
                ->join('assets', 'assets.id = pemeliharaan_rutin.kendaraan_id')
                ->where('pemeliharaan_rutin.id', $id)
                ->get()
                ->getRowArray();

            if (!$jadwal) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Data jadwal tidak ditemukan'
                ])->setStatusCode(404);
            }

            return $this->response->setJSON($jadwal);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }
    public function update($id)
    {
        try {
            $data = [
                'jenis_pemeliharaan' => $this->request->getPost('jenis_pemeliharaan'),
                'tanggal_terjadwal' => $this->request->getPost('tanggal_terjadwal'),
                'status' => $this->request->getPost('status'),
                'bengkel' => $this->request->getPost('bengkel'),
                'biaya' => $this->request->getPost('biaya'),
                'keterangan' => $this->request->getPost('keterangan'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            log_message('debug', 'Update data: ' . json_encode($data));
            log_message('debug', 'Update ID: ' . $id);

            if (empty($data['jenis_pemeliharaan']) || empty($data['tanggal_terjadwal']) || empty($data['status'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Semua field wajib harus diisi'
                ]);
            }

            $this->pemeliharaanModel->update($id, $data);

            if ($data['status'] === 'Selesai') {
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data jadwal berhasil diupdate'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error updating maintenance: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal mengupdate data: ' . $e->getMessage()
            ]);
        }
    }
    public function delete($id)
    {
        try {
            $this->pemeliharaanModel->delete($id);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Jadwal pemeliharaan berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'error' => 'Gagal menghapus data: ' . $e->getMessage()
            ]);
        }
    }
}