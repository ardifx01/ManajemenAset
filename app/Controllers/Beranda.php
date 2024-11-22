<?php

namespace App\Controllers;

class Beranda extends BaseController
{
    public function __construct()
    {
        helper('auth');
    }
    public function index()
    {
        return view('home/beranda');
    }
}