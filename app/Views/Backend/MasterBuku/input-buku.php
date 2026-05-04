<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="#"><span class="glyphicon glyphicon-home"></span></a></li>
            <li>Master Data Buku</li>
            <li class="active">Input Data Buku</li>
        </ol>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3>Input Buku</h3>
                    <hr />
                    <form action="<?= base_url('admin/simpan-buku'); ?>" method="post" enctype="multipart/form-data">
                        
                        <div class="form-group col-md-12">
                            <label>Judul Buku</label>
                            <input type="text" class="form-control" name="judul_buku" placeholder="Masukkan Judul Buku" required="required">
                        </div>

                        <div class="form-group col-md-12">
                            <label>Pengarang</label>
                            <input type="text" class="form-control" name="pengarang" placeholder="Masukkan Nama Pengarang" required="required">
                        </div>

                        <div class="form-group col-md-12">
                            <label>Penerbit</label>
                            <input type="text" class="form-control" name="penerbit" placeholder="Masukkan Nama Penerbit" required="required">
                        </div>

                        <div class="form-group col-md-12">
                            <label>Tahun</label>
                            <input type="text" class="form-control" name="tahun" onkeypress="return goodchars(event, '0123456789', this)" placeholder="Masukkan Tahun" required="required" maxlength="4">
                        </div>

                        <div class="form-group col-md-12">
                            <label>Jumlah Eksemplar</label>
                            <input type="text" class="form-control" name="jumlah_eksemplar" onkeypress="return goodchars(event, '0123456789', this)" placeholder="Masukkan Jumlah Eksemplar" required="required">
                        </div>

                        <div class="form-group col-md-12">
                            <label>Kategori Buku</label>
                            <select class="form-control" name="kategori_buku" required="required">
                                <option value="">-- Pilih Kategori Buku --</option>
                                <?php foreach($data_kategori as $kat) { ?>
                                    <option value="<?= $kat['id_kategori']; ?>"><?= $kat['nama_kategori']; ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group col-md-12">
                            <label>Keterangan</label>
                            <textarea class="form-control" name="keterangan" rows="3" placeholder="Masukkan Keterangan" required="required"></textarea>
                        </div>

                        <div class="form-group col-md-12">
                            <label>Rak</label>
                            <select class="form-control" name="rak" required="required">
                                <option value="">-- Pilih Rak --</option>
                                <?php foreach($data_rak as $r) { ?>
                                    <option value="<?= $r['id_rak']; ?>"><?= $r['nama_rak']; ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group col-md-12">
                            <label>Cover Buku</label>
                            <input type="file" name="cover_buku" required="required">
                            <small>Format file yang diizinkan : jpg, jpeg, png. Maksimal ukuran 1 MB</small>
                        </div>

                        <div class="form-group col-md-12">
                            <label>E-Book</label>
                            <input type="file" name="e_book" required="required">
                            <small>Format file yang diizinkan : pdf. Maksimal ukuran 10 MB</small>
                        </div>

                        <div class="form-group col-md-12">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <button type="reset" class="btn btn-danger">Batal</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>