<?php

namespace App\Controllers;

class Laporan extends BaseController
{
    public function index()
    {
        return view('admin/laporan/index');
    }

    public function pemeliharaanRutin()
    {
        return view('admin/laporan/pemeliharaan-rutin');
    }

    public function kerusakan()
    {
        return view('admin/laporan/kerusakan');
    }

    public function riwayatPemeliharaan()
    {
        return view('admin/laporan/riwayat-pemeliharaan');
    }

    public function kepatuhan()
    {
        return view('admin/laporan/kepatuhan');
    }

    public function insiden()
    {
        return view('admin/laporan/insiden');
    }

    public function penertiban()
    {
        return view('admin/laporan/penertiban');
    }

    public function statistikAset()
    {
        return view('admin/laporan/statistik-aset');
    }

    public function analisis()
    {
        return view('admin/laporan/analisis');
    }
}