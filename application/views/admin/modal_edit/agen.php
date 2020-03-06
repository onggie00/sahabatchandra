<form enctype="multipart/form-data" class="cmxform" method="post" action="<?php echo site_url('admin/agen/updatedata') ?>">
  <input type="hidden" name="data_id" id="data_id" value="<?php echo $data->id_agen; ?>">
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
        <label for="cphone">Username </label>
        <input class="form-control" type="text" name="username" disabled value="<?php echo $data->username; ?>">
      </div>
      <div class="col-md-6">
        <label for="cphone">Total Uang </label>
        <input class="form-control" type="number" min="0" max="6500" name="total_uang" value="<?php echo $data->total_uang; ?>">
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
