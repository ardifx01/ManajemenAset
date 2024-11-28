<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\AsetModel;
use App\Models\PinjamModel;
use App\Models\KembaliModel;

class Homepage extends BaseController
{
    public function index()
    {
        $asetModel = new AsetModel();
        $pinjamModel = new PinjamModel();
        $kembaliModel = new KembaliModel();

        $assets = $asetModel->where('deleted_at IS NULL')
            ->orderBy('id', 'ASC')
            ->findAll();

        $latestPinjam = $pinjamModel->select('kendaraan_id, MAX(created_at) as latest_created_at')
            ->where('deleted_at IS NULL')
            ->groupBy('kendaraan_id')
            ->findAll();

        $latestKembali = $kembaliModel->select('kendaraan_id, MAX(created_at) as latest_created_at')
            ->where('deleted_at IS NULL')
            ->groupBy('kendaraan_id')
            ->findAll();

        $latestPinjamMap = [];
        foreach ($latestPinjam as $pinjam) {
            $pinjamDetail = $pinjamModel->where([
                'kendaraan_id' => $pinjam['kendaraan_id'],
                'created_at' => $pinjam['latest_created_at'],
                'deleted_at IS NULL' => null
            ])->first();

            if ($pinjamDetail) {
                $latestPinjamMap[$pinjam['kendaraan_id']] = $pinjamDetail;
            }
        }

        $latestKembaliMap = [];
        foreach ($latestKembali as $kembali) {
            $kembaliDetail = $kembaliModel->where([
                'kendaraan_id' => $kembali['kendaraan_id'],
                'created_at' => $kembali['latest_created_at'],
                'deleted_at IS NULL' => null
            ])->first();

            if ($kembaliDetail) {
                $latestKembaliMap[$kembali['kendaraan_id']] = $kembaliDetail;
            }
        }

        foreach ($assets as &$asset) {
            $pinjamData = $latestPinjamMap[$asset['id']] ?? null;
            $kembaliData = $latestKembaliMap[$asset['id']] ?? null;

            // Inisialisasi field baru
            $asset['surat_permohonan'] = null;
            $asset['surat_jalan_admin'] = null;
            $asset['tanggal_kembali'] = null;

            if ($pinjamData && $kembaliData) {
                $pinjamTime = strtotime($pinjamData['created_at']);
                $kembaliTime = strtotime($kembaliData['created_at']);

                if ($kembaliTime > $pinjamTime) {
                    switch ($kembaliData['status']) {
                        case 'pending':
                            $asset['status_pinjam'] = 'Dalam Verifikasi';
                            break;
                        case 'disetujui':
                            $asset['status_pinjam'] = 'Tersedia';
                            break;
                        case 'ditolak':
                            $asset['status_pinjam'] = 'Dipinjam';
                            $asset['surat_permohonan'] = $pinjamData['surat_permohonan'];
                            $asset['surat_jalan_admin'] = $pinjamData['surat_jalan_admin'];
                            $asset['tanggal_kembali'] = $pinjamData['tanggal_kembali'];
                            break;
                    }
                } else {
                    $this->setPinjamStatus($asset, $pinjamData);
                }
            } elseif ($pinjamData) {
                $this->setPinjamStatus($asset, $pinjamData);
            } elseif ($kembaliData) {
                switch ($kembaliData['status']) {
                    case 'pending':
                        $asset['status_pinjam'] = 'Dalam Verifikasi';
                        break;
                    case 'disetujui':
                        $asset['status_pinjam'] = 'Tersedia';
                        break;
                    default:
                        $asset['status_pinjam'] = 'Tersedia';
                }
            } else {
                $asset['status_pinjam'] = 'Tersedia';
            }
        }

        return view('user/homepage', ['aset' => $assets]);
    }
    private function setPinjamStatus(&$asset, $pinjamData)
    {
        switch ($pinjamData['status']) {
            case 'pending':
                $asset['status_pinjam'] = 'Dalam Verifikasi';
                break;
            case 'disetujui':
                $asset['status_pinjam'] = 'Dipinjam';
                $asset['surat_permohonan'] = $pinjamData['surat_permohonan'];
                $asset['surat_jalan_admin'] = $pinjamData['surat_jalan_admin'];
                $asset['tanggal_kembali'] = $pinjamData['tanggal_kembali'];
                break;
            default:
                $asset['status_pinjam'] = 'Tersedia';
        }
    }
}