<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="#"><span class="glyphicon glyphicon-home"></span></a></li>
            <li>Master Data Buku</li>
            <li class="active">Edit Data Buku</li>
        </ol>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3>Edit Buku</h3>
                    <hr />
                    <form action="<?= base_url('admin/update-buku'); ?>" method="post" enctype="multipart/form-data">
                        
                        <div class="form-group col-md-12">
                            <label>Judul Buku</label>
                            <input type="text" class="form-control" name="judul_buku" value="<?= $data_buku['judul_buku']; ?>" required="required">
                        </div>

                        <div class="form-group col-md-12">
                            <label>Pengarang</label>
                            <input type="text" class="form-control" name="pengarang" value="<?= $data_buku['pengarang']; ?>" required="required">
                        </div>

                        <div class="form-group col-md-12">
                            <label>Penerbit</label>
                            <input type="text" class="form-control" name="penerbit" value="<?= $data_buku['penerbit']; ?>" required="required">
                        </div>

                        <div class="form-group col-md-12">
                            <label>Tahun</label>
                            <input type="text" class="form-control" name="tahun" value="<?= $data_buku['tahun']; ?>" onkeypress="return goodchars(event, '0123456789', this)" required="required" maxlength="4">
                        </div>

                        <div class="form-group col-md-12">
                            <label>Jumlah Eksemplar</label>
                            <input type="text" class="form-control" name="jumlah_eksemplar" value="<?= $data_buku['jumlah_eksemplar']; ?>" onkeypress="return goodchars(event, '0123456789', this)" required="required">
                        </div>

                        <div class="form-group col-md-12">
                            <label>Kategori Buku</label>
                            <select class="form-control" name="kategori_buku" required="required">
                                <?php foreach($data_kategori as $kat) { ?>
                                    <option value="<?= $kat['id_kategori']; ?>" <?= ($data_buku['id_kategori'] == $kat['id_kategori']) ? 'selected' : ''; ?>><?= $kat['nama_kategori']; ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group col-md-12">
                            <label>Keterangan</label>
                            <textarea class="form-control" name="keterangan" rows="3" required="required"><?= $data_buku['keterangan']; ?></textarea>
                        </div>

                        <div class="form-group col-md-12">
                            <label>Rak</label>
                            <select class="form-control" name="rak" required="required">
                                <?php foreach($data_rak as $r) { ?>
                                    <option value="<?= $r['id_rak']; ?>" <?= ($data_buku['id_rak'] == $r['id_rak']) ? 'selected' : ''; ?>><?= $r['nama_rak']; ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group col-md-12">
                            <label>Cover Buku Lama</label><br>
                            <img src="<?= base_url('Assets/Cover_buku/'.$data_buku['cover_buku']); ?>" width="200px"><br><br>
                            <label>Ganti Cover Buku Baru</label>
                            <input type="file" name="cover_buku">
                            <small>Format: jpg, jpeg, png. Maks: 1 MB. (Kosongkan jika tidak ingin ganti cover)</small>
                        </div>

                        <div class="form-group col-md-12">
                            <label>E-Book Lama</label><br>
                            <iframe src="<?= base_url('Assets/E-book/'.$data_buku['e_book']); ?>" width="100%" height="300px"></iframe><br><br>
                            <label>Ganti E-Book Baru</label>
                            <input type="file" name="e_book">
                            <small>Format: pdf. Maks: 10 MB. (Kosongkan jika tidak ingin ganti e-book)</small>
                        </div>

                        <div class="form-group col-md-12">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="<?= base_url('admin/master-data-buku'); ?>" class="btn btn-danger">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>