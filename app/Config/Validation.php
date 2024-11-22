<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\StrictRules\CreditCardRules;
use CodeIgniter\Validation\StrictRules\FileRules;
use CodeIgniter\Validation\StrictRules\FormatRules;
use CodeIgniter\Validation\StrictRules\Rules;
use Myth\Auth\Authentication\Passwords\ValidationRules;

class Validation extends BaseConfig
{
    // --------------------------------------------------------------------
    // Setup
    // --------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var list<string>
     */
    public array $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
        ValidationRules::class,
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public array $templates = [
        'list' => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    // --------------------------------------------------------------------
    // Rules
    // --------------------------------------------------------------------
    public $formTambahAset = [
        'gambar_mobil' => [
            'rules' => 'uploaded[gambar_mobil]|mime_in[gambar_mobil,image/jpg,image/jpeg,image/png]|max_size[gambar_mobil,2048]',
            'errors' => [
                'uploaded' => 'Gambar mobil harus diupload',
                'mime_in' => 'Format file harus JPG atau PNG',
                'max_size' => 'Ukuran file maksimal 2MB'
            ]
        ]
    ];
    public $formPengembalian = [
        'surat_pengembalian' => [
            'rules' => 'uploaded[surat_pengembalian]|mime_in[surat_pengembalian,application/pdf]|max_size[surat_pengembalian,2048]',
            'errors' => [
                'uploaded' => 'Surat Pengembalian harus diupload',
                'mime_in' => 'Format file harus PDF',
                'max_size' => 'Ukuran file maksimal 2MB'
            ]
        ],
        'berita_acara_pengembalian' => [
            'rules' => 'uploaded[berita_acara_pengembalian]|mime_in[berita_acara_pengembalian,application/pdf]|max_size[berita_acara_pengembalian,2048]',
            'errors' => [
                'uploaded' => 'Berita Acara Pengembalian harus diupload',
                'mime_in' => 'Format file harus PDF',
                'max_size' => 'Ukuran file maksimal 2MB'
            ]
        ]
    ];
    public $formPeminjaman = [
        'surat_jalan' => [
            'rules' => 'uploaded[surat_jalan]|mime_in[surat_jalan,application/pdf]|max_size[surat_jalan,2048]',
            'errors' => [
                'uploaded' => 'Surat jalan harus diupload',
                'mime_in' => 'Format file harus PDF',
                'max_size' => 'Ukuran file maksimal 2MB'
            ]
        ],
        'surat_pemakaian' => [
            'rules' => 'uploaded[surat_pemakaian]|mime_in[surat_pemakaian,application/pdf]|max_size[surat_pemakaian,2048]',
            'errors' => [
                'uploaded' => 'Surat Pemakaian harus diupload',
                'mime_in' => 'Format file harus PDF',
                'max_size' => 'Ukuran file maksimal 2MB'
            ]
        ],
        'berita_acara_penyerahan' => [
            'rules' => 'uploaded[berita_acara_penyerahan]|mime_in[berita_acara_penyerahan,application/pdf]|max_size[berita_acara_penyerahan,2048]',
            'errors' => [
                'uploaded' => 'Berita Acara Penyerahan harus diupload',
                'mime_in' => 'Format file harus PDF',
                'max_size' => 'Ukuran file maksimal 2MB'
            ]
        ]
    ];
}
