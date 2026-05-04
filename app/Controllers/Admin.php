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

    public function master_data_anggota() 
    {
        if (session()->get('ses_id') == "") return redirect()->to(base_url('admin/login-admin'));
        $modelAnggota = new \App\Models\M_Anggota();
        $data['data_anggota'] = $modelAnggota->getDataAnggota(['is_delete_anggota' => '0'])->getResultArray();
        
        echo view('Backend/Template/header', $data);
        echo view('Backend/Template/sidebar', $data);
        echo view('Backend/MasterAnggota/master-data-anggota', $data);
        echo view('Backend/Template/footer', $data);
    }

    public function input_data_anggota() 
    {
        if (session()->get('ses_id') == "") return redirect()->to(base_url('admin/login-admin'));
        echo view('Backend/Template/header');
        echo view('Backend/Template/sidebar');
        echo view('Backend/MasterAnggota/input-anggota');
        echo view('Backend/Template/footer');
    }

    public function simpan_data_anggota() 
    {
        if (session()->get('ses_id') == "") return redirect()->to(base_url('admin/login-admin'));
        $modelAnggota = new \App\Models\M_Anggota();
        
        $nama = $this->request->getPost('nama');
        $jk = $this->request->getPost('jenis_kelamin');
        $tlp = $this->request->getPost('no_tlp');
        $alamat = $this->request->getPost('alamat');
        $email = $this->request->getPost('email');

        $hasil = $modelAnggota->autoNumber()->getRowArray();
        if (!$hasil) {
            $id = "AGT001";
        } else {
            $kode = $hasil['id_anggota'];
            $noUrut = (int) substr($kode, -3);
            $noUrut++;
            $id = "AGT" . sprintf("%03s", $noUrut);
        }

        $dataSimpan = [
            'id_anggota'        => $id,
            'nama_anggota'      => $nama,
            'jenis_kelamin'     => $jk,
            'no_tlp'            => $tlp,
            'alamat'            => $alamat,
            'email'             => $email,
            'password_anggota'  => password_hash('pass_anggota', PASSWORD_DEFAULT),
            'is_delete_anggota' => '0',
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s')
        ];

        $modelAnggota->saveDataAnggota($dataSimpan);
        session()->setFlashdata('success', 'Data Anggota Berhasil Ditambahkan!');
        return redirect()->to(base_url('admin/master-data-anggota'));
    }

    public function edit_data_anggota($idEdit) 
    {
        if (session()->get('ses_id') == "") return redirect()->to(base_url('admin/login-admin'));
        $modelAnggota = new \App\Models\M_Anggota();
        
        $dataAnggota = $modelAnggota->getDataAnggota(['sha1(id_anggota)' => $idEdit])->getRowArray();
        session()->set(['idUpdateAgt' => $dataAnggota['id_anggota']]);
        $data['data_anggota'] = $dataAnggota;
        
        echo view('Backend/Template/header', $data);
        echo view('Backend/Template/sidebar', $data);
        echo view('Backend/MasterAnggota/edit-anggota', $data);
        echo view('Backend/Template/footer', $data);
    }

    public function update_data_anggota() 
    {
        if (session()->get('ses_id') == "") return redirect()->to(base_url('admin/login-admin'));
        $modelAnggota = new \App\Models\M_Anggota();
        $idUpdate = session()->get('idUpdateAgt');

        $dataUpdate = [
            'nama_anggota'  => $this->request->getPost('nama'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'no_tlp'        => $this->request->getPost('no_tlp'),
            'alamat'        => $this->request->getPost('alamat'),
            'email'         => $this->request->getPost('email'),
            'updated_at'    => date("Y-m-d H:i:s")
        ];
        
        $modelAnggota->updateDataAnggota($dataUpdate, ['id_anggota' => $idUpdate]);
        session()->remove('idUpdateAgt');
        session()->setFlashdata('success', 'Data Anggota Berhasil Diperbaharui!');
        return redirect()->to(base_url('admin/master-data-anggota'));
    }

    public function hapus_data_anggota($idHapus) 
    {
        if (session()->get('ses_id') == "") return redirect()->to(base_url('admin/login-admin'));
        $modelAnggota = new \App\Models\M_Anggota();
        $dataUpdate = [
            'is_delete_anggota' => '1',
            'updated_at'        => date("Y-m-d H:i:s")
        ];
        $modelAnggota->updateDataAnggota($dataUpdate, ['sha1(id_anggota)' => $idHapus]);
        session()->setFlashdata('success', 'Data Anggota Berhasil Dihapus!');
        return redirect()->to(base_url('admin/master-data-anggota'));
    }

    public function master_data_rak() 
    {
        if (session()->get('ses_id') == "") return redirect()->to(base_url('admin/login-admin'));
        $modelRak = new \App\Models\M_Rak();
        $data['data_rak'] = $modelRak->getDataRak(['is_delete_rak' => '0'])->getResultArray();
        
        echo view('Backend/Template/header', $data);
        echo view('Backend/Template/sidebar', $data);
        echo view('Backend/MasterRak/master-data-rak', $data);
        echo view('Backend/Template/footer', $data);
    }

    public function input_data_rak() 
    {
        if (session()->get('ses_id') == "") return redirect()->to(base_url('admin/login-admin'));
        echo view('Backend/Template/header');
        echo view('Backend/Template/sidebar');
        echo view('Backend/MasterRak/input-rak');
        echo view('Backend/Template/footer');
    }

    public function simpan_data_rak() 
    {
        if (session()->get('ses_id') == "") return redirect()->to(base_url('admin/login-admin'));
        $modelRak = new \App\Models\M_Rak();
        $nama = $this->request->getPost('nama_rak');

        $hasil = $modelRak->autoNumber()->getRowArray();
        if (!$hasil) {
            $id = "RAK001";
        } else {
            $kode = $hasil['id_rak'];
            $noUrut = (int) substr($kode, -3);
            $noUrut++;
            $id = "RAK" . sprintf("%03s", $noUrut);
        }

        $dataSimpan = [
            'id_rak'        => $id,
            'nama_rak'      => $nama,
            'is_delete_rak' => '0',
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s')
        ];

        $modelRak->saveDataRak($dataSimpan);
        session()->setFlashdata('success', 'Data Rak Berhasil Ditambahkan!');
        return redirect()->to(base_url('admin/master-data-rak'));
    }

    public function edit_data_rak($idEdit) 
    {
        if (session()->get('ses_id') == "") return redirect()->to(base_url('admin/login-admin'));
        $modelRak = new \App\Models\M_Rak();
        
        $dataRak = $modelRak->getDataRak(['sha1(id_rak)' => $idEdit])->getRowArray();
        session()->set(['idUpdateRak' => $dataRak['id_rak']]);
        $data['data_rak'] = $dataRak;
        
        echo view('Backend/Template/header', $data);
        echo view('Backend/Template/sidebar', $data);
        echo view('Backend/MasterRak/edit-rak', $data);
        echo view('Backend/Template/footer', $data);
    }

    public function update_data_rak() 
    {
        if (session()->get('ses_id') == "") return redirect()->to(base_url('admin/login-admin'));
        $modelRak = new \App\Models\M_Rak();
        $idUpdate = session()->get('idUpdateRak');

        $dataUpdate = [
            'nama_rak'   => $this->request->getPost('nama_rak'),
            'updated_at' => date("Y-m-d H:i:s")
        ];
        
        $modelRak->updateDataRak($dataUpdate, ['id_rak' => $idUpdate]);
        session()->remove('idUpdateRak');
        session()->setFlashdata('success', 'Data Rak Berhasil Diperbaharui!');
        return redirect()->to(base_url('admin/master-data-rak'));
    }

    public function hapus_data_rak($idHapus) 
    {
        if (session()->get('ses_id') == "") return redirect()->to(base_url('admin/login-admin'));
        $modelRak = new \App\Models\M_Rak();
        $dataUpdate = [
            'is_delete_rak' => '1',
            'updated_at'    => date("Y-m-d H:i:s")
        ];
        $modelRak->updateDataRak($dataUpdate, ['sha1(id_rak)' => $idHapus]);
        session()->setFlashdata('success', 'Data Rak Berhasil Dihapus!');
        return redirect()->to(base_url('admin/master-data-rak'));
    }

    public function master_data_kategori() 
    {
        if (session()->get('ses_id') == "") return redirect()->to(base_url('admin/login-admin'));
        $modelKategori = new \App\Models\M_Kategori();
        $data['data_kategori'] = $modelKategori->getDataKategori(['is_delete_kategori' => '0'])->getResultArray();
        
        echo view('Backend/Template/header', $data);
        echo view('Backend/Template/sidebar', $data);
        echo view('Backend/MasterKategori/master-data-kategori', $data);
        echo view('Backend/Template/footer', $data);
    }

    public function input_data_kategori() 
    {
        if (session()->get('ses_id') == "") return redirect()->to(base_url('admin/login-admin'));
        echo view('Backend/Template/header');
        echo view('Backend/Template/sidebar');
        echo view('Backend/MasterKategori/input-kategori');
        echo view('Backend/Template/footer');
    }

    public function simpan_data_kategori() 
    {
        if (session()->get('ses_id') == "") return redirect()->to(base_url('admin/login-admin'));
        $modelKategori = new \App\Models\M_Kategori();
        $nama = $this->request->getPost('nama_kategori');

        $hasil = $modelKategori->autoNumber()->getRowArray();
        if (!$hasil) {
            $id = "KTG001";
        } else {
            $kode = $hasil['id_kategori'];
            $noUrut = (int) substr($kode, -3);
            $noUrut++;
            $id = "KTG" . sprintf("%03s", $noUrut);
        }

        $dataSimpan = [
            'id_kategori'        => $id,
            'nama_kategori'      => $nama,
            'is_delete_kategori' => '0',
            'created_at'         => date('Y-m-d H:i:s'),
            'updated_at'         => date('Y-m-d H:i:s')
        ];

        $modelKategori->saveDataKategori($dataSimpan);
        session()->setFlashdata('success', 'Data Kategori Berhasil Ditambahkan!');
        return redirect()->to(base_url('admin/master-data-kategori'));
    }

    public function edit_data_kategori($idEdit) 
    {
        if (session()->get('ses_id') == "") return redirect()->to(base_url('admin/login-admin'));
        $modelKategori = new \App\Models\M_Kategori();
        
        $dataKategori = $modelKategori->getDataKategori(['sha1(id_kategori)' => $idEdit])->getRowArray();
        session()->set(['idUpdateKtg' => $dataKategori['id_kategori']]);
        $data['data_kategori'] = $dataKategori;
        
        echo view('Backend/Template/header', $data);
        echo view('Backend/Template/sidebar', $data);
        echo view('Backend/MasterKategori/edit-kategori', $data);
        echo view('Backend/Template/footer', $data);
    }

    public function update_data_kategori() 
    {
        if (session()->get('ses_id') == "") return redirect()->to(base_url('admin/login-admin'));
        $modelKategori = new \App\Models\M_Kategori();
        $idUpdate = session()->get('idUpdateKtg');

        $dataUpdate = [
            'nama_kategori' => $this->request->getPost('nama_kategori'),
            'updated_at'    => date("Y-m-d H:i:s")
        ];
        
        $modelKategori->updateDataKategori($dataUpdate, ['id_kategori' => $idUpdate]);
        session()->remove('idUpdateKtg');
        session()->setFlashdata('success', 'Data Kategori Berhasil Diperbaharui!');
        return redirect()->to(base_url('admin/master-data-kategori'));
    }

    public function hapus_data_kategori($idHapus) 
    {
        if (session()->get('ses_id') == "") return redirect()->to(base_url('admin/login-admin'));
        $modelKategori = new \App\Models\M_Kategori();
        $dataUpdate = [
            'is_delete_kategori' => '1',
            'updated_at'         => date("Y-m-d H:i:s")
        ];
        $modelKategori->updateDataKategori($dataUpdate, ['sha1(id_kategori)' => $idHapus]);
        session()->setFlashdata('success', 'Data Kategori Berhasil Dihapus!');
        return redirect()->to(base_url('admin/master-data-kategori'));
    }

    public function master_buku()
    {
        if (session()->get('ses_id') == "") return redirect()->to(base_url('admin/login-admin'));
        $modelBuku = new \App\Models\M_Buku();
        
        $dataBuku = $modelBuku->getDataBukuJoin(['tbl_buku.is_delete_buku' => '0'])->getResultArray();
        
        $uri = service('uri');
        $data['page'] = $uri->getSegment(2);
        $data['web_title'] = "Master Data Buku";
        $data['dataBuku'] = $dataBuku;
        
        echo view('Backend/Template/header', $data);
        echo view('Backend/Template/sidebar', $data);
        echo view('Backend/MasterBuku/master-data-buku', $data);
        echo view('Backend/Template/footer', $data);
    }

    public function input_buku()
    {
        if (session()->get('ses_id') == "") return redirect()->to(base_url('admin/login-admin'));
        $modelKategori = new \App\Models\M_Kategori();
        $modelRak = new \App\Models\M_Rak();
        
        $uri = service('uri');
        $data['page'] = $uri->getSegment(2);
        $data['web_title'] = "Input Data Buku";
        $data['data_kategori'] = $modelKategori->getDataKategori(['is_delete_kategori' => '0'])->getResultArray();
        $data['data_rak'] = $modelRak->getDataRak(['is_delete_rak' => '0'])->getResultArray();
        
        echo view('Backend/Template/header', $data);
        echo view('Backend/Template/sidebar', $data);
        echo view('Backend/MasterBuku/input-buku', $data);
        echo view('Backend/Template/footer', $data);
    }

    public function simpan_buku()
    {
        if (session()->get('ses_id') == "") return redirect()->to(base_url('admin/login-admin'));
        $modelBuku = new \App\Models\M_Buku();
        
        $judulBuku = $this->request->getPost('judul_buku');
        $pengarang = $this->request->getPost('pengarang');
        $penerbit = $this->request->getPost('penerbit');
        $tahun = $this->request->getPost('tahun');
        $jumlahEksemplar = $this->request->getPost('jumlah_eksemplar');
        $kategoriBuku = $this->request->getPost('kategori_buku');
        $keterangan = $this->request->getPost('keterangan');
        $rak = $this->request->getPost('rak');

        if (!$this->validate([
            'cover_buku' => 'uploaded[cover_buku]|max_size[cover_buku,1024]|ext_in[cover_buku,jpg,jpeg,png]'
        ])) {
            session()->setFlashdata('error', "Format file gambar tidak valid atau lebih dari 1 MB");
            return redirect()->to('/admin/input-buku')->withInput();
        }

        if (!$this->validate([
            'e_book' => 'uploaded[e_book]|max_size[e_book,10240]|ext_in[e_book,pdf]'
        ])) {
            session()->setFlashdata('error', "Format file PDF tidak valid atau lebih dari 10 MB");
            return redirect()->to('/admin/input-buku')->withInput();
        }

        $coverBuku = $this->request->getFile('cover_buku');
        $ext1 = $coverBuku->getClientExtension();
        $namaFile1 = "Cover-Buku-".date("ymdHis").".".$ext1;
        $coverBuku->move('Assets/Cover_buku', $namaFile1);

        $eBook = $this->request->getFile('e_book');
        $ext2 = $eBook->getClientExtension();
        $namaFile2 = "E-Book-".date("ymdHis").".".$ext2;
        $eBook->move('Assets/E-book', $namaFile2);

        $hasil = $modelBuku->autoNumber()->getRowArray();
        if (!$hasil) {
            $id = "BKU001";
        } else {
            $kode = $hasil['id_buku'];
            $noUrut = (int) substr($kode, -3);
            $noUrut++;
            $id = "BKU" . sprintf("%03s", $noUrut);
        }

        $dataSimpan = [
            'id_buku' => $id,
            'judul_buku' => ucwords($judulBuku),
            'pengarang' => ucwords($pengarang),
            'penerbit' => ucwords($penerbit),
            'tahun' => $tahun,
            'jumlah_eksemplar' => $jumlahEksemplar,
            'id_kategori' => $kategoriBuku,
            'keterangan' => $keterangan,
            'id_rak' => $rak,
            'cover_buku' => $namaFile1,
            'e_book' => $namaFile2,
            'is_delete_buku' => '0',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $modelBuku->saveDataBuku($dataSimpan);
        session()->setFlashdata('success', 'Data Buku Berhasil Disimpan!');
        return redirect()->to(base_url('admin/master-data-buku'));
    }

    public function hapus_buku($idHapus)
    {
        if (session()->get('ses_id') == "") return redirect()->to(base_url('admin/login-admin'));
        $modelBuku = new \App\Models\M_Buku();
        
        $dataHapus = $modelBuku->getDataBuku(['sha1(id_buku)' => $idHapus])->getRowArray();
        
        if(file_exists('Assets/Cover_buku/'.$dataHapus['cover_buku']) && $dataHapus['cover_buku']) {
            unlink('Assets/Cover_buku/'.$dataHapus['cover_buku']); 
        }
        if(file_exists('Assets/E-book/'.$dataHapus['e_book']) && $dataHapus['e_book']) {
            unlink('Assets/E-book/'.$dataHapus['e_book']); 
        }
        
        $modelBuku->updateDataBuku(['is_delete_buku' => '1'], ['sha1(id_buku)' => $idHapus]);
        session()->setFlashdata('success', 'Data Buku Berhasil Dihapus!');
        return redirect()->to(base_url('admin/master-data-buku'));
    }

    // Jawaban Tugas Mandiri: Edit dan Update Buku
    public function edit_buku($idEdit) 
    {
        if (session()->get('ses_id') == "") return redirect()->to(base_url('admin/login-admin'));
        $modelBuku = new \App\Models\M_Buku();
        $modelKategori = new \App\Models\M_Kategori();
        $modelRak = new \App\Models\M_Rak();
        
        $dataBuku = $modelBuku->getDataBuku(['sha1(id_buku)' => $idEdit])->getRowArray();
        session()->set(['idUpdateBuku' => $dataBuku['id_buku']]);
        
        $data['web_title'] = "Edit Data Buku";
        $data['data_buku'] = $dataBuku;
        $data['data_kategori'] = $modelKategori->getDataKategori(['is_delete_kategori' => '0'])->getResultArray();
        $data['data_rak'] = $modelRak->getDataRak(['is_delete_rak' => '0'])->getResultArray();
        
        echo view('Backend/Template/header', $data);
        echo view('Backend/Template/sidebar', $data);
        echo view('Backend/MasterBuku/edit-buku', $data);
        echo view('Backend/Template/footer', $data);
    }

    public function update_buku() 
    {
        if (session()->get('ses_id') == "") return redirect()->to(base_url('admin/login-admin'));
        $modelBuku = new \App\Models\M_Buku();
        $idUpdate = session()->get('idUpdateBuku');
        $dataLama = $modelBuku->getDataBuku(['id_buku' => $idUpdate])->getRowArray();

        $namaCover = $dataLama['cover_buku'];
        $coverBuku = $this->request->getFile('cover_buku');
        if ($coverBuku->isValid() && !$coverBuku->hasMoved()) {
            if (!$this->validate(['cover_buku' => 'max_size[cover_buku,1024]|ext_in[cover_buku,jpg,jpeg,png]'])) {
                session()->setFlashdata('error', "Format cover tidak valid!");
                return redirect()->to('/admin/edit-buku/'.sha1($idUpdate))->withInput();
            }
            if(file_exists('Assets/Cover_buku/'.$dataLama['cover_buku']) && $dataLama['cover_buku']) {
                unlink('Assets/Cover_buku/'.$dataLama['cover_buku']);
            }
            $ext1 = $coverBuku->getClientExtension();
            $namaCover = "Cover-Buku-".date("ymdHis").".".$ext1;
            $coverBuku->move('Assets/Cover_buku', $namaCover);
        }

        $namaEbook = $dataLama['e_book'];
        $eBook = $this->request->getFile('e_book');
        if ($eBook->isValid() && !$eBook->hasMoved()) {
            if (!$this->validate(['e_book' => 'max_size[e_book,10240]|ext_in[e_book,pdf]'])) {
                session()->setFlashdata('error', "Format PDF tidak valid!");
                return redirect()->to('/admin/edit-buku/'.sha1($idUpdate))->withInput();
            }
            if(file_exists('Assets/E-book/'.$dataLama['e_book']) && $dataLama['e_book']) {
                unlink('Assets/E-book/'.$dataLama['e_book']);
            }
            $ext2 = $eBook->getClientExtension();
            $namaEbook = "E-Book-".date("ymdHis").".".$ext2;
            $eBook->move('Assets/E-book', $namaEbook);
        }

        $dataUpdate = [
            'judul_buku'       => ucwords($this->request->getPost('judul_buku')),
            'pengarang'        => ucwords($this->request->getPost('pengarang')),
            'penerbit'         => ucwords($this->request->getPost('penerbit')),
            'tahun'            => $this->request->getPost('tahun'),
            'jumlah_eksemplar' => $this->request->getPost('jumlah_eksemplar'),
            'id_kategori'      => $this->request->getPost('kategori_buku'),
            'keterangan'       => $this->request->getPost('keterangan'),
            'id_rak'           => $this->request->getPost('rak'),
            'cover_buku'       => $namaCover,
            'e_book'           => $namaEbook,
            'updated_at'       => date('Y-m-d H:i:s')
        ];

        $modelBuku->updateDataBuku($dataUpdate, ['id_buku' => $idUpdate]);
        session()->remove('idUpdateBuku');
        session()->setFlashdata('success', 'Data Buku Berhasil Diperbaharui!');
        return redirect()->to(base_url('admin/master-data-buku'));
    }
}