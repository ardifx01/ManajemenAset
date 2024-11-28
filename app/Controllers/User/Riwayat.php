<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\PinjamModel;
use App\Models\KembaliModel;

class Riwayat extends BaseController
{
    public function index()
    {
        $pinjamModel = new PinjamModel();
        $kembaliModel = new KembaliModel();
        $userId = user_id();

        try {
            $data['peminjaman'] = $pinjamModel->select('pinjam.*, assets.merk, assets.no_polisi')
                ->join('assets', 'assets.id = pinjam.kendaraan_id')
                ->where('pinjam.user_id', $userId)
                ->orderBy('pinjam.created_at', 'DESC')
                ->findAll();

            $data['pengembalian'] = $kembaliModel->select('kembali.*, assets.merk, assets.no_polisi')
                ->join('assets', 'assets.id = kembali.kendaraan_id')
                ->where('kembali.user_id', $userId)
                ->orderBy('kembali.created_at', 'DESC')
                ->findAll();

            foreach ($data['peminjaman'] as &$pinjam) {
                $pengembalian = $kembaliModel->where([
                    'kendaraan_id' => $pinjam['kendaraan_id'],
                    'user_id' => $userId
                ])->first();

                if ($pinjam['deleted_at'] !== null && !empty($pengembalian)) {
                    $pinjam['status_lengkap'] = [
                        'label' => 'Selesai',
                        'class' => 'bg-success',
                        'icon' => 'bi-check-circle'
                    ];
                } else {
                    $pinjam['status_lengkap'] = $this->getStatusLengkap($pinjam);
                }
            }

            foreach ($data['pengembalian'] as &$kembali) {
                $kembali['status_lengkap'] = $this->getStatusLengkap($kembali);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error in Riwayat::index: ' . $e->getMessage());
            $data['peminjaman'] = [];
            $data['pengembalian'] = [];
        }

        return view('user/riwayat', $data);
    }

    private function getStatusLengkap($data)
    {
        $status = [
            'label' => ucfirst($data['status']),
            'class' => 'bg-secondary',
            'icon' => 'bi-clock'
        ];

        switch ($data['status']) {
            case 'pending':
                $status['class'] = 'bg-warning';
                $status['icon'] = 'bi-clock';
                break;
            case 'disetujui':
                $status['class'] = 'bg-success';
                $status['icon'] = 'bi-check-circle';
                break;
            case 'ditolak':
                $status['class'] = 'bg-danger';
                $status['icon'] = 'bi-x-circle';
                break;
            case 'selesai':
                $status['label'] = 'Selesai';
                $status['class'] = 'bg-info';
                $status['icon'] = 'bi-check-circle-fill';
                break;
        }

        if ($data['deleted_at'] && $data['status'] === 'pending') {
            $status['label'] = 'Dibatalkan';
            $status['class'] = 'bg-secondary';
            $status['icon'] = 'bi-dash-circle';
        }

        return $status;
    }

    public function getDetail($type, $id)
    {
        try {
            $userId = user_id();

            if ($type === 'peminjaman') {
                $model = new PinjamModel();
                $data = $model->select('pinjam.*, assets.merk, assets.no_polisi, assets.status_pinjam')
                    ->join('assets', 'assets.id = pinjam.kendaraan_id')
                    ->where('pinjam.id', $id)
                    ->where('pinjam.user_id', $userId)
                    ->first();
            } else {
                $model = new KembaliModel();
                $data = $model->select('kembali.*, assets.merk, assets.no_polisi, assets.status_pinjam')
                    ->join('assets', 'assets.id = kembali.kendaraan_id')
                    ->where('kembali.id', $id)
                    ->where('kembali.user_id', $userId)
                    ->first();
            }

            if (!$data) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }

            $statusInfo = $this->getStatusLengkap($data);

            $html = $this->buildDetailHtml($data, $type, $statusInfo);

            return $this->response->setJSON([
                'success' => true,
                'html' => $html
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error in Riwayat::getDetail: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data'
            ]);
        }
    }

    private function buildDetailHtml($data, $type, $statusInfo)
    {
        $html = "
        <div class='mb-3'>
            <strong>Kendaraan:</strong><br>
            {$data['merk']} ({$data['no_polisi']})
        </div>";

        if ($type === 'peminjaman') {
            $html .= "
            <div class='mb-3'>
                <strong>Tujuan:</strong><br>
                {$data['urusan_kedinasan']}
            </div>";
        }

        $html .= "
        <div class='mb-3'>
            <strong>Tanggal Pengajuan:</strong><br>
            " . date('d/m/Y H:i', strtotime($data['created_at'])) . "
        </div>
        <div class='mb-3'>
            <strong>Status:</strong><br>
            <span class='badge {$statusInfo['class']}'>
                <i class='bi {$statusInfo['icon']}'></i> {$statusInfo['label']}
            </span>
        </div>";

        if ($data['status'] === 'ditolak' && !empty($data['keterangan'])) {
            $html .= "
            <div class='mb-3'>
                <strong>Alasan Penolakan:</strong><br>
                <div class='alert alert-danger'>
                    {$data['keterangan']}
                </div>
            </div>";
        }

        $html .= $this->buildDocumentsSection($data, $type);

        return $html;
    }

    private function buildDocumentsSection($data, $type)
    {
        $html = "<div class='mb-3'><strong>Dokumen:</strong><br>";

        if ($type === 'peminjaman') {
            $documents = [
                'surat_permohonan' => 'Surat Permohonan',
                'surat_jalan_admin' => 'Surat Jalan'
                // 'surat_pemakaian' => 'Surat Pemakaian',
                // 'berita_acara_penyerahan' => 'Berita Acara Penyerahan'
            ];
        } else {
            $documents = [
                'surat_pengembalian' => 'Surat Pengembalian',
                'berita_acara_pengembalian' => 'Berita Acara Pengembalian'
            ];
        }

        foreach ($documents as $field => $label) {
            if (!empty($data[$field])) {
                $html .= "
                <a href='" . base_url('uploads/documents/' . $data[$field]) . "' 
                   target='_blank' 
                   class='btn btn-sm btn-outline-primary mb-1 me-1'>
                    <i class='bi bi-file-earmark-pdf'></i> {$label}
                </a>";
            }
        }

        $html .= "</div>";
        return $html;
    }
}