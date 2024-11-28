<?php

namespace App\Models;

use CodeIgniter\Model;

class AsetModel extends Model
{
    protected $useSoftDeletes = true;
    protected $table = 'assets';
    protected $primaryKey = 'id';
    protected $deletedField = 'deleted_at';
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $allowedFields = [
        'user_id',
        'kategori_id',
        'no_sk_psp',
        'kode_barang',
        'merk',
        'tahun_pembuatan',
        'kapasitas',
        'gambar_mobil',
        'no_polisi',
        'no_bpkb',
        'no_stnk',
        'no_rangka',
        'kondisi',
        'status_pinjam',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    const STATUS_TERSEDIA = 'Tersedia';
    const STATUS_DIPINJAM = 'Dipinjam';
    const STATUS_VERIFIKASI = 'Dalam Verifikasi';
    const STATUS_VERIFIKASI_PENGEMBALIAN = 'Dalam Verifikasi Pengembalian';

    public function isAvailable($kendaraanId)
    {
        $result = $this->select('status_pinjam')
            ->where('id', $kendaraanId)
            ->where('deleted_at', null)
            ->first();

        return $result && $result['status_pinjam'] === self::STATUS_TERSEDIA;
    }
    public function updateStatus($id, $status)
    {
        $data = ['status_pinjam' => $status];

        if (!$this->find($id)) {
            return false;
        }

        $builder = $this->db->table($this->table);
        return $builder->where('id', $id)
            ->set($data)
            ->update();
    }
}