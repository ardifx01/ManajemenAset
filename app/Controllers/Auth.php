<?php

namespace App\Controllers;

use Myth\Auth\Config\Auth as AuthConfig;
use Myth\Auth\Models\UserModel;

class Auth extends BaseController
{
    protected $auth;

    public function __construct()
    {
        $this->auth = service('authentication');
    }

    public function login()
    {
        $config = config(AuthConfig::class);
        return view('auth/login', ['config' => $config]);
    }

    public function register()
    {
        return view('auth/register');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }

    public function forgot()
    {
        return view('auth/forgot');
    }

    public function checkEmailForgot()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON([
                'success' => false,
                'message' => 'Direct access not allowed'
            ]);
        }

        if (!$this->validateRequest(['email' => 'required'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request'
            ]);
        }

        $email = $this->request->getPost('email');
        $users = new UserModel();

        $validation = \Config\Services::validation();
        $validation->setRules([
            'email' => [
                'label' => 'Email',
                'rules' => 'required|valid_email|regex_match[/@pu\.go\.id$/]',
                'errors' => [
                    'required' => 'Email harus diisi',
                    'valid_email' => 'Alamat email tidak valid',
                    'regex_match' => 'Email harus menggunakan domain @pu.go.id'
                ]
            ]
        ]);

        if (!$validation->run(['email' => $email])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $validation->getError('email')
            ]);
        }

        $exists = $users->where('email', $email)->first();

        return $this->response->setJSON([
            'success' => true,
            'exists' => $exists ? true : false,
            'message' => $exists ? 'Email ditemukan' : 'Email tidak terdaftar'
        ]);
    }

    /**
     * Endpoint buat checkUsername pake AJAX
     */
    public function checkUsername()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON([
                'success' => false,
                'message' => 'Direct access not allowed'
            ]);
        }

        if (!$this->validateRequest(['username' => 'required'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request'
            ]);
        }

        $username = $this->request->getPost('username');
        $users = new UserModel();

        $validation = \Config\Services::validation();
        $validation->setRules([
            'username' => [
                'label' => 'Username',
                'rules' => 'required|alpha_numeric_space|min_length[4]|max_length[30]',
                'errors' => [
                    'required' => 'Username harus diisi.',
                    'alpha_numeric_space' => 'Username hanya boleh berisi huruf, angka, dan spasi.',
                    'min_length' => 'Username harus memiliki minimal 4 karakter.',
                    'max_length' => 'Username tidak boleh lebih dari 30 karakter.'
                ]
            ]
        ]);

        if (!$validation->run(['username' => $username])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $validation->getError('username')
            ]);
        }

        $exists = $users->where('username', $username)->first();

        return $this->response->setJSON([
            'success' => true,
            'available' => !$exists,
            'message' => $exists ? 'Username sudah digunakan.' : 'Username tersedia.'
        ]);
    }

    /**
     * Endpoint buat checkEmail pake AJAX
     */
    public function checkEmail()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON([
                'success' => false,
                'message' => 'Direct access not allowed'
            ]);
        }

        if (!$this->validateRequest(['email' => 'required'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request'
            ]);
        }

        $email = $this->request->getPost('email');
        $users = new UserModel();

        $validation = \Config\Services::validation();
        $validation->setRules([
            'email' => [
                'label' => 'Email',
                'rules' => 'required|valid_email|regex_match[/@pu\.go\.id$/]',
                'errors' => [
                    'required' => 'Email harus diisi.',
                    'valid_email' => 'Alamat email tidak valid.',
                    'regex_match' => 'Email harus menggunakan domain @pu.go.id.'
                ]
            ]
        ]);

        if (!$validation->run(['email' => $email])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $validation->getError('email')
            ]);
        }

        $exists = $users->where('email', $email)->first();

        return $this->response->setJSON([
            'success' => true,
            'available' => !$exists,
            'message' => $exists ? 'Email sudah terdaftar.' : 'Email tersedia.'
        ]);
    }

    /**
     * Helper method buat validasi request
     */
    protected function validateRequest($rules)
    {
        return $this->validate($rules);
    }
}