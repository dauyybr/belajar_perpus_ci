<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }

    public function belajar_segment()
    {
        $uri = service('uri');
        $parameter1 = $uri->getSegment(3);
        $parameter2 = $uri->getSegment(4);
        $parameter3 = $uri->getSegment(5);

        $data['p1'] = $parameter1;
        $data['p2'] = $parameter2;
        $data['p3'] = $parameter3;
        
        return view('segment_view', $data);
    }
    public function tugas()
 {
    $data = [
        'mhs' => [
            ['nama' => 'Supardi', 'nilai' => 85],
            ['nama' => 'jokowi', 'nilai' => 75],
            ['nama' => 'sahroni', 'nilai' => 67],
            ['nama' => 'taufiq', 'nilai' => 40],
            ['nama' => 'haikal', 'nilai' => 69]
        ]
    ];

    return view('foreach', $data);
 }
    public function logout()
    
    {
    session()->remove('ses_id');
    session()->remove('ses_user');
    session()->remove('ses_level');
    session()->setFlashdata('info','Anda telah keluar dari sistem!');
    ?>
    <script>
        document.location = "<?= base_url('admin/login-admin');?>";
    </script>
    <?php
}
}
