<?php

namespace App\Models;

use CodeIgniter\Model;

class PinjamModel extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_DISETUJUI = 'disetujui';
    const STATUS_DITOLAK = 'ditolak';
    const STATUS_SELESAI = 'selesai';

    protected $table = 'pinjam';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'user_id',
        'kode_barang',
        'kendaraan_id',
        'nama_penanggung_jawab',
        'nip_nrp',
        'pangkat_golongan',
        'jabatan',
        'unit_organisasi',
        'surat_jalan',
        'surat_pemakaian',
        'berita_acara_penyerahan',
        'pengemudi',
        'no_hp',
        'tanggal_pinjam',
        'tanggal_kembali',
        'urusan_kedinasan',
        'status',
        'keterangan',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'user_id' => 'required',
        'kode_barang' => 'required',
        'kendaraan_id' => 'required'
        // 'status' => 'in_list[pending,disetujui,ditolak]'
    ];
    public function getPeminjamanHistory($userId = null)
    {
        $builder = $this->select('pinjam.*, assets.merk, assets.no_polisi')
            ->join('assets', 'assets.id = pinjam.kendaraan_id');

        if ($userId) {
            $builder->where('pinjam.user_id', $userId);
        }

        return $builder->orderBy('pinjam.created_at', 'DESC')
            ->findAll();
    }

    public function getPendingPeminjaman()
    {
        return $this->select('pinjam.*, assets.merk, assets.no_polisi')
            ->join('assets', 'assets.id = pinjam.kendaraan_id')
            ->where('pinjam.status', self::STATUS_PENDING)
            ->where('pinjam.deleted_at', null)
            ->orderBy('pinjam.created_at', 'DESC')
            ->findAll();
    }

    public function getActivePeminjaman($kendaraanId)
    {
        return $this->where('kendaraan_id', $kendaraanId)
            ->whereIn('status', [self::STATUS_DISETUJUI, self::STATUS_PENDING])
            ->where('deleted_at', null)
            ->first();
    }

    public function getFullHistory($kendaraanId = null)
    {
        $builder = $this->select('
                pinjam.*, 
                assets.merk, 
                assets.no_polisi,
                assets.status_pinjam
            ')
            ->join('assets', 'assets.id = pinjam.kendaraan_id');

        if ($kendaraanId) {
            $builder->where('pinjam.kendaraan_id', $kendaraanId);
        }

        return $builder->orderBy('pinjam.created_at', 'DESC')
            ->findAll();
    }
}