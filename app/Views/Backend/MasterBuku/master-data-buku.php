<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="#"><span class="glyphicon glyphicon-home"></span></a></li>
            <li>Master Data Buku</li>
            <li class="active">Data Buku</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3>Data Buku</h3>
                    <hr />
                    <a href="<?= base_url('admin/input-buku'); ?>">
                        <button type="button" class="btn btn-primary pull-right"><span class="glyphicon glyphicon-plus"></span> Tambah Data Buku</button>
                    </a>
                    <br /><br /><br />
                    
                    <div class="table-responsive">
                    <table data-toggle="table" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-search="true" data-select-item-name="toolbar1" data-pagination="true" data-sort-name="name" data-sort-order="desc">
                        <thead>
                            <tr>
                                <th data-sortable="true">No</th>
                                <th data-sortable="true">Cover Buku</th>
                                <th data-sortable="true">Judul Buku</th>
                                <th data-sortable="true">Pengarang</th>
                                <th data-sortable="true">Penerbit</th>
                                <th data-sortable="true">Tahun</th>
                                <th data-sortable="true">Jumlah Eksemplar</th>
                                <th data-sortable="true">Kategori Buku</th>
                                <th data-sortable="true">Keterangan</th>
                                <th data-sortable="true">Rak</th>
                                <th data-sortable="true">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 0; foreach($dataBuku as $data) { ?>
                            <tr>
                                <td><?= $no=$no+1; ?></td>
                                <td><img src="<?= base_url('Assets/Cover_buku/'.$data['cover_buku']); ?>" width="80px" alt="Cover"></td>
                                <td><?= $data['judul_buku']; ?></td>
                                <td><?= $data['pengarang']; ?></td>
                                <td><?= $data['penerbit']; ?></td>
                                <td><?= $data['tahun']; ?></td>
                                <td><?= $data['jumlah_eksemplar']; ?></td>
                                <td><?= $data['nama_kategori']; ?></td>
                                <td><?= $data['keterangan']; ?></td>
                                <td><?= $data['nama_rak']; ?></td>
                                <td>
                                    <?php if(session()->get('ses_level') == '1') { ?>
                                        <a href="<?= base_url('admin/edit-buku/'.sha1($data['id_buku'])); ?>" class="btn btn-info btn-sm">Edit</a>
                                        <a href="javascript:void(0);" onclick="doDelete('<?= sha1($data['id_buku']); ?>')" class="btn btn-danger btn-sm">Hapus</a>
                                    <?php } else { echo "-"; } ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function doDelete(idDelete) {
        swal({
            title: "Hapus Data Buku?",
            text: "Data ini akan terhapus secara permanen!!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((ok) => {
            if (ok) {
                window.location.href = '<?= base_url('admin/hapus-buku'); ?>/' + idDelete;
            }
        });
    }
</script>