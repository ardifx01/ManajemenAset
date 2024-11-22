<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\PinjamModel;
use App\Models\KembaliModel;
use App\Models\AsetModel;
use App\Models\PemeliharaanRutinModel;
use CodeIgniter\I18n\Time;

class Dashboard extends Controller
{
    protected $pinjamModel;
    protected $kembaliModel;
    protected $asetModel;
    protected $pemeliharaanModel;

    public function __construct()
    {
        $this->pinjamModel = new PinjamModel();
        $this->kembaliModel = new KembaliModel();
        $this->asetModel = new AsetModel();
        $this->pemeliharaanModel = new PemeliharaanRutinModel();
    }

    public function index()
    {
        $peminjaman_pending = $this->pinjamModel->select('pinjam.*, assets.merk')
            ->join('assets', 'assets.id = pinjam.kendaraan_id')
            ->where('pinjam.status', PinjamModel::STATUS_PENDING)
            ->where('pinjam.deleted_at', null)
            ->findAll();

        $pengembalian_pending = $this->kembaliModel->select('kembali.*, assets.merk')
            ->join('assets', 'assets.id = kembali.kendaraan_id')
            ->where('kembali.status', KembaliModel::STATUS_PENDING)
            ->where('kembali.deleted_at', null)
            ->findAll();

        $total_kendaraan = $this->asetModel->where('deleted_at', null)->countAllResults();
        $kendaraan_tersedia = $this->asetModel->where('status_pinjam', 'Tersedia')
            ->where('deleted_at', null)
            ->countAllResults();
        $peminjaman_aktif = $this->asetModel->where('status_pinjam', 'Dipinjam')
            ->where('deleted_at', null)
            ->countAllResults();

        $status_kendaraan = [
            'tersedia' => $kendaraan_tersedia,
            'dipinjam' => $peminjaman_aktif,
            'maintenance' => $this->asetModel->where('kondisi !=', 'Baik')
                ->where('deleted_at', null)
                ->countAllResults()
        ];

        $statistik_bulanan = $this->getStatistikBulanan();
        $kendaraan_maintenance = $this->getKendaraanMaintenance();
        $peminjaman_terbaru = $this->getPeminjamanTerbaru();
        $quick_info = $this->getQuickInfo();

        $data = [
            'peminjaman_pending' => $peminjaman_pending,
            'pengembalian_pending' => $pengembalian_pending,
            'total_kendaraan' => $total_kendaraan,
            'kendaraan_tersedia' => $kendaraan_tersedia,
            'peminjaman_aktif' => $peminjaman_aktif,
            'status_kendaraan' => $status_kendaraan,
            'statistik_bulanan' => $statistik_bulanan,
            'kendaraan_maintenance' => $kendaraan_maintenance,
            'peminjaman_terbaru' => $peminjaman_terbaru,
            'quick_info' => $quick_info
        ];

        return view('admin/index', $data);
    }

    private function getStatistikBulanan()
    {
        $year = date('Y');
        $statistics = [];

        for ($month = 1; $month <= 12; $month++) {
            $startDate = "{$year}-{$month}-01";
            $endDate = date('Y-m-t', strtotime($startDate));

            $count = $this->pinjamModel->where('status', 'disetujui')
                ->where('created_at >=', $startDate)
                ->where('created_at <=', $endDate)
                ->countAllResults();

            $statistics[] = $count;
        }

        return $statistics;
    }

    private function getKendaraanMaintenance()
    {
        return $this->asetModel->select('assets.*, pemeliharaan_rutin.tanggal_terjadwal, pemeliharaan_rutin.jenis_pemeliharaan')
            ->join('pemeliharaan_rutin', 'pemeliharaan_rutin.kendaraan_id = assets.id', 'left')
            ->where('pemeliharaan_rutin.status', PemeliharaanRutinModel::STATUS_PENDING)
            ->where('assets.deleted_at', null)
            ->where('pemeliharaan_rutin.deleted_at', null)
            ->orWhere('assets.kondisi', 'Rusak Ringan')
            ->orWhere('assets.kondisi', 'Rusak Berat')
            ->findAll();
    }

    private function getPeminjamanTerbaru()
    {
        return $this->pinjamModel->select('pinjam.*, assets.merk, assets.no_polisi')
            ->join('assets', 'assets.id = pinjam.kendaraan_id')
            ->where('pinjam.deleted_at', null)
            ->orderBy('pinjam.created_at', 'DESC')
            ->limit(5)
            ->findAll();
    }

    private function getQuickInfo()
    {
        $now = Time::now();

        $peminjaman_hari_ini = $this->pinjamModel
            ->where('DATE(created_at)', $now->toDateString())
            ->countAllResults();

        $kendaraan_overdue = $this->pinjamModel
            ->where('tanggal_kembali <', $now->toDateString())
            ->where('status', 'disetujui')
            ->where('deleted_at', null)
            ->countAllResults();

        $maintenance_mendatang = $this->pemeliharaanModel
            ->where('status', PemeliharaanRutinModel::STATUS_PENDING)
            ->where('tanggal_terjadwal >', $now->toDateString())
            ->where('deleted_at', null)
            ->countAllResults();

        return [
            'peminjaman_hari_ini' => $peminjaman_hari_ini,
            'kendaraan_overdue' => $kendaraan_overdue,
            'maintenance_mendatang' => $maintenance_mendatang
        ];
    }

    public function getStatistikAPI()
    {
        $statistik = $this->getStatistikBulanan();
        return $this->response->setJSON(['data' => $statistik]);
    }

    public function getStatusKendaraanAPI()
    {
        $status = $this->asetModel->select('status_pinjam, COUNT(*) as total')
            ->where('deleted_at', null)
            ->groupBy('status_pinjam')
            ->findAll();

        return $this->response->setJSON(['data' => $status]);
    }

    public function getMaintenanceKendaraanAPI()
    {
        $kendaraan = $this->getKendaraanMaintenance();
        return $this->response->setJSON(['data' => $kendaraan]);
    }

    public function getPeminjamanTerbaruAPI()
    {
        $peminjaman = $this->getPeminjamanTerbaru();
        return $this->response->setJSON(['data' => $peminjaman]);
    }
}