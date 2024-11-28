<?php

namespace App\Models;

use CodeIgniter\Model;

class KembaliModel extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_DISETUJUI = 'disetujui';
    const STATUS_DITOLAK = 'ditolak';

    protected $table = 'kembali';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'user_id',
        'kode_barang',
        'nama_penanggung_jawab',
        'nip_nrp',
        'pangkat_golongan',
        'jabatan',
        'unit_organisasi',
        'surat_pengembalian',
        'berita_acara_pengembalian',
        'kendaraan_id',
        'pinjam_id',
        'no_hp',
        'tanggal_pinjam',
        'tanggal_kembali',
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
        'kendaraan_id' => 'required',
        'nama_penanggung_jawab' => 'required',
        'nip_nrp' => 'required',
        'pangkat_golongan' => 'required',
        'jabatan' => 'required',
        'unit_organisasi' => 'required',
        'no_hp' => 'required',
        'tanggal_pinjam' => 'required',
        'tanggal_kembali' => 'required',
        'pinjam_id' => 'required'
    ];
    public function getPengembalianHistory($userId = null)
    {
        $builder = $this->select('
                kembali.*, 
                assets.merk, 
                assets.no_polisi,
                pinjam.urusan_kedinasan, 
                pinjam.status as status_pinjam
            ')
            ->join('assets', 'assets.id = kembali.kendaraan_id')
            ->join('pinjam', 'pinjam.id = kembali.pinjam_id', 'left');

        if ($userId) {
            $builder->where('kembali.user_id', $userId);
        }
        return $builder->where('kembali.deleted_at', null)
            ->orderBy('kembali.created_at', 'DESC')
            ->findAll();
    }

    public function getPendingPengembalian()
    {
        return $this->select('
                kembali.*, 
                assets.merk, 
                assets.no_polisi,
                pinjam.urusan_kedinasan
            ')
            ->join('assets', 'assets.id = kembali.kendaraan_id')
            ->join('pinjam', 'pinjam.id = kembali.pinjam_id', 'left')
            ->where('kembali.status', self::STATUS_PENDING)
            ->where('kembali.deleted_at', null)
            ->orderBy('kembali.created_at', 'DESC')
            ->findAll();
    }

    public function getFullHistory($kendaraanId = null)
    {
        $builder = $this->select('
                kembali.*, 
                assets.merk, 
                assets.no_polisi,
                assets.status_pinjam,
                pinjam.urusan_kedinasan,
                pinjam.status as status_pinjam
            ')
            ->join('assets', 'assets.id = kembali.kendaraan_id')
            ->join('pinjam', 'pinjam.kendaraan_id = kembali.kendaraan_id', 'left');

        if ($kendaraanId) {
            $builder->where('kembali.kendaraan_id', $kendaraanId);
        }

        return $builder->orderBy('kembali.created_at', 'DESC')
            ->findAll();
    }
}