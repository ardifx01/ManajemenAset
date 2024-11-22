<?php

namespace App\Controllers;

class User extends BaseController
{
    public function homepage()
    {
        return view('user/homepage');
    }
    public function riwayat()
    {
        return view('user/riwayat');
    }
}