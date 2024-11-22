<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\PinjamModel;
use App\Models\KembaliModel;
use App\Models\AsetModel;

class Riwayat extends Controller
{
    protected $pinjamModel;
    protected $kembaliModel;
    protected $asetModel;

    public function __construct()
    {
        $this->pinjamModel = new PinjamModel();
        $this->kembaliModel = new KembaliModel();
        $this->asetModel = new AsetModel();
    }

    public function index()
    {
        $peminjaman_history = $this->pinjamModel->select('
                pinjam.*, 
                assets.merk, 
                assets.no_polisi
            ')
            ->join('assets', 'assets.id = pinjam.kendaraan_id')
            ->where([
                'pinjam.status !=' => 'pending'
            ])
            ->orderBy('pinjam.created_at', 'DESC')
            ->find();

        $pengembalian_history = $this->kembaliModel->select('
                kembali.*, 
                assets.merk, 
                assets.no_polisi
            ')
            ->join('assets', 'assets.id = kembali.kendaraan_id')
            ->where([
                'kembali.status !=' => 'pending'
            ])
            ->orderBy('kembali.created_at', 'DESC')
            ->find();

        foreach ($peminjaman_history as &$pinjam) {
            if (!empty($pinjam['tanggal_pinjam'])) {
                $pinjam['tanggal_pinjam'] = date('Y-m-d', strtotime($pinjam['tanggal_pinjam']));
            }
            if (!empty($pinjam['tanggal_kembali'])) {
                $pinjam['tanggal_kembali'] = date('Y-m-d', strtotime($pinjam['tanggal_kembali']));
            }

            switch ($pinjam['status']) {
                case 'disetujui':
                    $pinjam['status_label'] = 'Disetujui';
                    $pinjam['status_class'] = 'success';
                    break;
                case 'ditolak':
                    $pinjam['status_label'] = 'Ditolak';
                    $pinjam['status_class'] = 'danger';
                    break;
                default:
                    $pinjam['status_label'] = 'Pending';
                    $pinjam['status_class'] = 'warning';
            }
        }

        foreach ($pengembalian_history as &$kembali) {
            if (!empty($kembali['tanggal_pinjam'])) {
                $kembali['tanggal_pinjam'] = date('Y-m-d', strtotime($kembali['tanggal_pinjam']));
            }
            if (!empty($kembali['tanggal_kembali'])) {
                $kembali['tanggal_kembali'] = date('Y-m-d', strtotime($kembali['tanggal_kembali']));
            }

            switch ($kembali['status']) {
                case 'disetujui':
                    $kembali['status_label'] = 'Disetujui';
                    $kembali['status_class'] = 'success';
                    break;
                case 'ditolak':
                    $kembali['status_label'] = 'Ditolak';
                    $kembali['status_class'] = 'danger';
                    break;
                default:
                    $kembali['status_label'] = 'Pending';
                    $kembali['status_class'] = 'warning';
            }
        }

        $data = [
            'title' => 'Riwayat Peminjaman & Pengembalian',
            'peminjaman_history' => $peminjaman_history,
            'pengembalian_history' => $pengembalian_history
        ];

        return view('admin/riwayat', $data);
    }

    public function detail($type, $id)
    {
        if ($type === 'peminjaman') {
            $detail = $this->pinjamModel->select('
                    pinjam.*, 
                    assets.merk, 
                    assets.no_polisi
                ')
                ->join('assets', 'assets.id = pinjam.kendaraan_id')
                ->find($id);
        } else {
            $detail = $this->kembaliModel->select('
                    kembali.*, 
                    assets.merk, 
                    assets.no_polisi
                ')
                ->join('assets', 'assets.id = kembali.kendaraan_id')
                ->find($id);
        }

        if (!$detail) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $detail
        ]);
    }
}