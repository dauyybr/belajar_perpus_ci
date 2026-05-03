<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="#"><span class="glyphicon glyphicon-home"></span></a></li>
            <li>Master Data Admin</li>
            <li class="active">Edit Data Admin</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3>Edit Admin</h3>
                    <hr />
                    <form action="<?= base_url('admin/update-admin');?>" method="post">
                        <input type="hidden" name="id_admin" value="<?= $data_user['id_admin']; ?>">
                        
                        <div class="form-group col-md-6">
                            <label>Nama Admin</label>
                            <input type="text" class="form-control" name="nama" value="<?= $data_user['nama_admin']; ?>" required>
                        </div>
                        <div style="clear:both;"></div>

                        <div class="form-group col-md-6">
                            <label>Username Admin</label>
                            <input type="text" class="form-control" name="username" value="<?= $data_user['username_admin']; ?>" required>
                        </div>
                        <div style="clear:both;"></div>

                        <div class="form-group col-md-6">
                            <label>Akses Level</label>
                            <select class="form-control" name="level" required>
                                <option value="2" <?= ($data_user['akses_level'] == '2') ? 'selected' : ''; ?>>Kepala Perpustakaan</option>
                                <option value="3" <?= ($data_user['akses_level'] == '3') ? 'selected' : ''; ?>>Admin Perpustakaan</option>
                            </select>
                        </div>
                        <div style="clear:both;"></div>

                        <div class="form-group col-md-6">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="<?= base_url('admin/master-data-admin'); ?>" class="btn btn-danger">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>