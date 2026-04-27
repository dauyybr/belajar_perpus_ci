<?php

namespace App\Controllers;

use App\Models\M_Admin;

class Admin extends BaseController
{
    public function login()
    {
        return view('Backend/Login/login');
    }

    public function dashboard()
    {
        // PERBAIKAN: Menyamakan key session dengan yang dibuat saat login
        // Di sini kita mengecek apakah session 'logged_in' bernilai true
        if (session()->get('logged_in') != TRUE) {
            session()->setFlashdata('error', 'Silakan login terlebih dahulu!');
            return redirect()->to(base_url('admin/login-admin'));
        } else {
            echo view('Backend/Template/header');
            echo view('Backend/Template/sidebar');
            echo view('Backend/Login/dashboard_admin');
            echo view('Backend/Template/footer');
        }
    }

    public function autentikasi()
    {
        $modelAdmin = new M_Admin; 
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $dataUser = $modelAdmin->getDataAdmin(['username_admin' => $username, 'is_delete_admin' => '0'])->getRowArray();

        if (!$dataUser) {
            session()->setFlashdata('error', 'Username Tidak Ditemukan!');
            return redirect()->back();
        } else {
            $passwordUser = $dataUser['password_admin'];

            // Memverifikasi password menggunakan password_verify
            $verifikasiPassword = password_verify($password, $passwordUser);
            
            if (!$verifikasiPassword) {
                session()->setFlashdata('error', 'Password Tidak Sesuai!');
                return redirect()->back();
            } else {
                // PERBAIKAN: Menyimpan data session dengan key yang konsisten
                $sessionData = [
                    'id_admin'       => $dataUser['id_admin'],
                    'username_admin' => $dataUser['username_admin'],
                    'logged_in'      => TRUE // Key ini yang akan dicek di dashboard
                ];
                session()->set($sessionData);

                return redirect()->to(base_url('admin/dashboard-admin'));
            }
        }
    }
    
    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('admin/login-admin'));
    }
}