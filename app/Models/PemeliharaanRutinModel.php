<?php

namespace App\Models;

use CodeIgniter\Model;

class PemeliharaanRutinModel extends Model
{
    protected $table = 'pemeliharaan_rutin';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useAutoIncrement = true;
    protected $useSoftDeletes = true;
    protected $useTimestamps = true;
    const STATUS_PENDING = 'Pending';
    const STATUS_SELESAI = 'Selesai';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'kendaraan_id' => 'required',
        'jenis_pemeliharaan' => 'required|in_list[Service Rutin,Ganti Oli,Tune Up]',
        'tanggal_terjadwal' => 'required|valid_date',
        'status' => 'required|in_list[Pending,Selesai]'
    ];

    protected $validationMessages = [
        'kendaraan_id' => [
            'required' => 'Kendaraan harus dipilih'
        ],
        'jenis_pemeliharaan' => [
            'required' => 'Jenis pemeliharaan harus dipilih',
            'in_list' => 'Jenis pemeliharaan tidak valid'
        ],
        'tanggal_terjadwal' => [
            'required' => 'Tanggal terjadwal harus diisi',
            'valid_date' => 'Format tanggal tidak valid'
        ],
        'status' => [
            'required' => 'Status harus dipilih',
            'in_list' => 'Status harus Pending atau Selesai'
        ]
    ];
    protected $allowedFields = [
        'kendaraan_id',
        'jenis_pemeliharaan',
        'tanggal_terjadwal',
        'status',
        'bengkel',
        'biaya',
        'keterangan',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    public function getValidStatuses()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_SELESAI => 'Selesai'
        ];
    }
    public function getValidJenisPemeliharaan()
    {
        return [
            'Service Rutin' => 'Service Rutin',
            'Ganti Oli' => 'Ganti Oli',
            'Tune Up' => 'Tune Up'
        ];
    }
}