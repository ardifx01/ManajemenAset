<?php

namespace App\Models;

use CodeIgniter\Model;

class LaporanModel extends Model
{
    protected $table = 'laporan';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'kendaraan_id',
        'user_id',
        'jenis_laporan',
        'tanggal_kejadian',
        'lokasi_kejadian',
        'keterangan',
        'bukti_foto',
        'status',
        'tindak_lanjut',
        'ditindaklanjuti_oleh',
        'tanggal_tindak_lanjut'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'kendaraan_id' => 'required|numeric',
        'user_id' => 'required|numeric',
        'jenis_laporan' => 'required|in_list[Laporan Insiden,Laporan Kerusakan]',
        'tanggal_kejadian' => 'required|valid_date',
        'lokasi_kejadian' => 'required|min_length[3]|max_length[255]',
        'keterangan' => 'required|min_length[10]',
        'bukti_foto' => 'permit_empty|max_size[bukti_foto,2048]|mime_in[bukti_foto,image/jpg,image/jpeg,image/png]',
        'status' => 'required|in_list[pending,proses,selesai,ditolak]',
    ];

    protected $validationMessages = [
        'kendaraan_id' => [
            'required' => 'Kendaraan harus dipilih',
            'numeric' => 'ID Kendaraan tidak valid'
        ],
        'jenis_laporan' => [
            'required' => 'Jenis laporan harus dipilih',
            'in_list' => 'Jenis laporan tidak valid'
        ],
        'tanggal_kejadian' => [
            'required' => 'Tanggal kejadian harus diisi',
            'valid_date' => 'Format tanggal tidak valid'
        ],
        'lokasi_kejadian' => [
            'required' => 'Lokasi kejadian harus diisi',
            'min_length' => 'Lokasi kejadian terlalu pendek (minimal 3 karakter)',
            'max_length' => 'Lokasi kejadian terlalu panjang (maksimal 255 karakter)'
        ],
        'keterangan' => [
            'required' => 'Keterangan harus diisi',
            'min_length' => 'Keterangan terlalu pendek (minimal 10 karakter)'
        ],
        'bukti_foto' => [
            'max_size' => 'Ukuran file terlalu besar (maksimal 2MB)',
            'mime_in' => 'Format file tidak didukung (gunakan JPG atau PNG)'
        ],
        'status' => [
            'required' => 'Status harus diisi',
            'in_list' => 'Status tidak valid'
        ]
    ];

    protected $beforeInsert = ['setUserID'];
    protected $beforeUpdate = ['setUpdatedAt'];

    protected function setUserID(array $data)
    {
        if (!isset($data['data']['user_id'])) {
            $data['data']['user_id'] = user_id();
        }
        return $data;
    }

    protected function setUpdatedAt(array $data)
    {
        $data['data']['updated_at'] = date('Y-m-d H:i:s');
        return $data;
    }

    public function getLaporanWithDetails($id = null)
    {
        $builder = $this->db->table($this->table)
            ->select('
                laporan.*,
                assets.merk as kendaraan_merk,
                assets.no_polisi,
                users.username as pelapor,
                petugas.username as petugas
            ')
            ->join('assets', 'assets.id = laporan.kendaraan_id')
            ->join('users', 'users.id = laporan.user_id')
            ->join('users as petugas', 'petugas.id = laporan.ditindaklanjuti_oleh', 'left')
            ->where('laporan.deleted_at IS NULL');

        if ($id !== null) {
            $builder->where('laporan.id', $id);
            return $builder->get()->getRowArray();
        }

        return $builder->get()->getResultArray();
    }
    public function getLaporanByStatus($status)
    {
        return $this->where('status', $status)
            ->findAll();
    }
    public function getLaporanByKendaraan($kendaraan_id)
    {
        return $this->where('kendaraan_id', $kendaraan_id)
            ->findAll();
    }
    public function getLaporanByUser($user_id)
    {
        return $this->where('user_id', $user_id)
            ->findAll();
    }
    public function getLaporanPending()
    {
        return $this->where('status', 'pending')
            ->findAll();
    }
    public function getTotalLaporanByJenis($jenis)
    {
        return $this->where('jenis_laporan', $jenis)
            ->countAllResults();
    }
    public function updateStatus($id, $status, $petugas_id = null)
    {
        $data = ['status' => $status];

        if ($petugas_id) {
            $data['ditindaklanjuti_oleh'] = $petugas_id;
            $data['tanggal_tindak_lanjut'] = date('Y-m-d H:i:s');
        }

        return $this->update($id, $data);
    }
    public function getDashboardStats()
    {
        return [
            'total_laporan' => $this->countAllResults(),
            'pending' => $this->where('status', 'pending')->countAllResults(),
            'proses' => $this->where('status', 'proses')->countAllResults(),
            'selesai' => $this->where('status', 'selesai')->countAllResults(),
            'insiden' => $this->where('jenis_laporan', 'Laporan Insiden')->countAllResults(),
            'kerusakan' => $this->where('jenis_laporan', 'Laporan Kerusakan')->countAllResults(),
        ];
    }
}