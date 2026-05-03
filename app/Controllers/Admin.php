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
        // Pengecekan gembok session
        if (session()->get('ses_id') == "") {
            session()->setFlashdata('error', 'Silakan login terlebih dahulu!');
            return redirect()->to(base_url('admin/login-admin'));
        } else {
            // Minta CodeIgniter untuk ngambil segmen URL
            $uri = service('uri');
            $pages = $uri->getSegment(2); 
            
            $data['pages'] = $pages;

            // URUTAN INI SANGAT PENTING! Jangan sampai terbalik atau tertinggal
            echo view('Backend/Template/header', $data);
            echo view('Backend/Template/sidebar', $data);
            echo view('Backend/Login/dashboard_admin', $data);
            echo view('Backend/Template/footer', $data);
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
                // PERBAIKAN: Menyamakan key session dengan yang diminta oleh halaman lain
                $sessionData = [
                    'ses_id'    => $dataUser['id_admin'],
                    'ses_user'  => $dataUser['username_admin'],
                    'ses_level' => $dataUser['akses_level'], // Mengambil data level dari database
                    'logged_in' => TRUE 
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

    public function input_data_admin()
    {
        if(session()->get('ses_id')=="" or session()->get('ses_user')=="" or session()->get('ses_level')==""){
            session()->setFlashdata('error','silakan login terlebih dahulu!');
            ?>
            <script>
                document.location = "<?= base_url('admin/login-admin');?>";
            </script>
            <?php
        }
        else{
            echo view('Backend/Template/header');
            echo view('Backend/Template/sidebar');
            echo view('Backend/MasterAdmin/input-admin');
            echo view('Backend/Template/footer');
        }
    }

    public function simpan_data_admin()
    {
        if(session()->get('ses_id')=="" or session()->get('ses_user')=="" or session()->get('ses_level')==""){
            session()->setFlashdata('error','Silakan login terlebih dahulu!');
            ?>
            <script>
                document.location = "<?= base_url('admin/login-admin');?>";
            </script>
            <?php
        }
        else{
            $modelAdmin = new M_Admin; // inisiasi
            
            $nama = $this->request->getPost('nama');
            $username = $this->request->getPost('username');
            $level = $this->request->getPost('level');

            $cekUname = $modelAdmin->getDataAdmin(['username_admin' => $username])->getNumRows();
            if($cekUname > 0){
                session()->setFlashdata('error','Username sudah digunakan!!');
                ?>
                <script>
                    history.go(-1);
                </script>
                <?php
            }
            else
                {
                $hasil = $modelAdmin->autoNumber()->getRowArray();
                if(!$hasil){
                    $id = "ADM001";
                }
                else{
                    $kode = $hasil['id_admin'];
                    $noUrut = (int) substr($kode, -3);
                    $noUrut++;
                    $id = "ADM".sprintf("%03s", $noUrut);
                }

                $dataSimpan = [
                    'id_admin' => $id,
                    'nama_admin' => $nama,
                    'username_admin' => $username,
                    'password_admin' => password_hash('pass_admin', PASSWORD_DEFAULT),
                    'akses_level' => $level,
                    'is_delete_admin' => '0',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                $modelAdmin->saveDataAdmin($dataSimpan);
                session()->setFlashdata('success', 'Data Admin Berhasil Ditambahkan!!');
                ?>
                <script>
                    document.location = "<?= base_url('admin/master-data-admin');?>";
                </script>
                <?php
            }
        }
    }

    public function master_data_admin()
    {
    if(session()->get('ses_id')=="" or session()->get('ses_user')=="" or session()->get('ses_level')==""){
        session()->setFlashdata('error','silakan login terlebih dahulu!');
        ?>
        <script>
            document.location = "<?= base_url('admin/login-admin');?>";
        </script>
        <?php
    }
    else{
        $modelAdmin = new M_Admin; // inisiasi

        $uri = service('uri');
        $pages = $uri->getSegment(2);
        $dataUser = $modelAdmin->getDataAdmin(['is_delete_admin' => '0', 'akses_level !=' => '1'])->getResultArray();

        $data['pages'] = $pages;
        $data['data_user'] = $dataUser;

        echo view('Backend/Template/header', $data);
        echo view('Backend/Template/sidebar', $data);
        echo view('Backend/MasterAdmin/master-data-admin', $data);
        echo view('Backend/Template/footer', $data);
    }
    }

    public function edit_data_admin($id)
    {
        if (session()->get('ses_id') == "") {
            session()->setFlashdata('error', 'Silakan login terlebih dahulu!');
            return redirect()->to(base_url('admin/login-admin'));
        } else {
            $modelAdmin = new M_Admin;
            // Kita cari data menggunakan sha1 karena di link view-nya kamu pakai sha1()
            $dataUser = $modelAdmin->getDataAdmin(['sha1(id_admin)' => $id])->getRowArray();

            $uri = service('uri');
            $pages = $uri->getSegment(2);

            $data['pages'] = $pages;
            $data['data_user'] = $dataUser;

            echo view('Backend/Template/header', $data);
            echo view('Backend/Template/sidebar', $data);
            echo view('Backend/MasterAdmin/edit-admin', $data);
            echo view('Backend/Template/footer', $data);
        }
    }

    public function update_data_admin()
    {
        if (session()->get('ses_id') == "") {
            session()->setFlashdata('error', 'Silakan login terlebih dahulu!');
            return redirect()->to(base_url('admin/login-admin'));
        } else {
            $modelAdmin = new M_Admin;
            $id = $this->request->getPost('id_admin');
            
            $dataUpdate = [
                'nama_admin'      => $this->request->getPost('nama'),
                'username_admin'  => $this->request->getPost('username'),
                'akses_level'     => $this->request->getPost('level'),
                'updated_at'      => date('Y-m-d H:i:s')
            ];

            $modelAdmin->updateDataAdmin($dataUpdate, ['id_admin' => $id]);
            session()->setFlashdata('success', 'Data Admin Berhasil Diperbarui!!');
            return redirect()->to(base_url('admin/master-data-admin'));
        }
    }

    public function hapus_data_admin($id)
    {
        if (session()->get('ses_id') == "") {
            session()->setFlashdata('error', 'Silakan login terlebih dahulu!');
            return redirect()->to(base_url('admin/login-admin'));
        } else {
            $modelAdmin = new \App\Models\M_Admin();
            
            // Siapkan data untuk mengubah status menjadi terhapus (Soft Delete)
            $dataUpdate = [
                'is_delete_admin' => '1',
                'updated_at'      => date('Y-m-d H:i:s')
            ];

            // Panggil fungsi update dari model
            // Perhatikan urutan parameternya: ($data, $where)
            $modelAdmin->updateDataAdmin($dataUpdate, ['sha1(id_admin)' => $id]);

            session()->setFlashdata('success', 'Data Admin Berhasil Dihapus!!');
            return redirect()->to(base_url('admin/master-data-admin'));
        }
    }   
    
}