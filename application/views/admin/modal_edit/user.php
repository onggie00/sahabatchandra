<form enctype="multipart/form-data" class="cmxform" method="post" action="<?php echo site_url('admin/user/updatedata') ?>">
  <input type="hidden" name="data_id" id="data_id" value="<?php echo $data->id_customer; ?>">
  <fieldset>
    <div class="form-group">
      <label for="nama">Nama Lengkap </label>
      <input class="form-control " type="text" name="nama_lengkap" value="<?php echo $data->nama_lengkap; ?>">
    </div>
    <div class="form-group row">
      <div class="col-md-6">
        <label for="cphone">Telepon </label>
        <input class="form-control" type="text" name="notelp" value="<?php echo $data->notelp; ?>">
      </div>
      <div class="col-md-6">
        <label for="cuser">Email </label>
        <input class="form-control" type="email" name="email" value="<?php echo $data->email; ?>">
      </div>
    </div>
    <div class="form-group row">
      <div class="col-md-6">
        <label for="cphone">Tempat Lahir </label>
        <input class="form-control" type="text" name="tempat_lahir" value="<?php echo $data->tempat_lahir; ?>">
      </div>
      <div class="col-md-6">
        <label for="cuser">Tanggal Lahir </label>
        <input class="form-control" type="date" name="tanggal_lahir" value="<?php echo $data->tanggal_lahir; ?>">
      </div>
    </div>
    <div class="form-group row">
      <div class="col-md-6">
        <label for="cphone">Longitude </label>
        <input class="form-control" type="text" name="longitude" value="<?php echo $data->longitude; ?>">
      </div>
      <div class="col-md-6">
        <label for="cpass">Latitude </label>
        <input class="form-control" type="text" name="latitude" value="<?php echo $data->latitude; ?>">
      </div>
    </div>
    <div class="form-group row">
      <div class="col-md-6">
        <label for="cphone">Pekerjaan </label>
        <input class="form-control" type="text" name="pekerjaan" value="<?php echo $data->pekerjaan; ?>">
      </div>
      <div class="col-md-6">
        <label for="cimg">Foto Identitas (KTP) </label>
      <input class="form-control " name="img" type="file">
      </div>
      <div class="col-md-6">
        <label for="cimg">Negara </label>
        <input class="form-control " name="negara" type="text" value="<?php echo $data->negara; ?>">
      </div>
      <div class="col-md-6">
        <label for="cphone">Username </label>
        <input class="form-control" type="text" name="username" disabled value="<?php echo $data->username; ?>">
      </div>
    </div>
    <div class="form-group">
      <label for="cemail">Alamat </label>
      <textarea class="form-control" name="alamat" style="resize:none;"><?php echo $data->alamat; ?></textarea>
    </div>

    <div class="form-group text-right">
      <input class="btn btn-default" type="button" value="Batal" data-dismiss="modal">
        <input class="btn btn-primary" type="submit" value="Ubah">
    </div>
  </fieldset>
</form>
