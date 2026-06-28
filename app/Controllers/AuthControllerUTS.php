<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\UserModel; 

class AuthController extends BaseController
{
    protected $userModel;

    function __construct()
    {
    helper('form');
        $this->userModel = new UserModel();
    }

    public function login()
    {
    if ($this->request->getPost()) {
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $dataUser = $this->userModel ->where(['username' => $username])->first();
        
        $dataUser = [
            'Yuniar' => [
                'username' => 'Yuniar Kurniawan', 
                'foto'     => 'profile-img2.jpeg', 
                'password' => '202cb962ac59075b964b07152d234b70', // passw 123
                'nim'      => 'A11.2020.12557', 
                'email'    => '111202012557@mhs.dinus.ac.id', 
                'phone'    => '6285776766938',
                'role'     => 'admin'
            ],
            'april' => [
                'username' => 'Kevin', 
                'foto'     => 'profile-img.jpg', 
                'password' => '202cb962ac59075b964b07152d234b70', // passw 123
                'nim'      => 'A11.2025.99999', 
                'email'    => '111202599999@mhs.dinus.ac.id', 
                'phone'    => '6287823741865',
                'role'     => 'tamu'
            ]
        ];


        if (isset($dataUser[$username])) {
            if (md5($password) == $dataUser[$username]['password']) {
                session()->set([
                    'username' => $dataUser[$username]['username'],
                    'foto' => $dataUser[$username]['foto'],
                    'nim' => $dataUser[$username]['nim'],
                    'email' => $dataUser[$username]['email'],
                    'phone' => $dataUser[$username]['phone'],
                    'role' => $dataUser[$username]['role'],
                    'login_time'   => date('Y-m-d H:i:s'),
                    'isLoggedIn' => TRUE
                ]);

                return redirect()->to(base_url('/'));
            } else {
                session()->setFlashdata('failed', 'Username & Password Salah');
                return redirect()->back();
            }
        } else {
            session()->setFlashdata('failed', 'Username Tidak Ditemukan');
            return redirect()->back();
        }
    } else {
        return view('v_login');
    }
    }

    public function logout()
    {
    session()->destroy();
    return redirect()->to('login');
    }

}
