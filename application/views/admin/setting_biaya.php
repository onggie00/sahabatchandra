

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800"> <?php echo $title_page; ?> </h1>
          <p class="mb-4 hidden">&nbsp;</p>

        
        <?php if (isset($err_msg)): ?>
          <br/>
          <div class="alert alert-danger alert-dismissible fade show rounded mb-0" role="alert">
              <span><i class="iconsminds-danger"></i></span> <?php echo $err_msg; ?>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
        <?php endif; ?>
        <?php if (isset($success_msg)): ?>
                <br>
                <div class="alert alert-success alert-dismissible fade show rounded mb-0" role="alert">
                    <span><i class="iconsminds-yes"></i></span> <?php echo $success_msg; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
              <?php endif; ?>
        <br/>
<div class="container bg-white p-4">
  <div class="row justify-content-around">
    <div class="col">
      <h2 class="p-3 bg-light"><?php echo "HKD ".number_format($this->mymodel->getlast('biaya','id_biaya')->biaya_admin,0,'','.'); ?></h2>
    </div>
    <div class="col">
      <h2 class="p-3 bg-light"><?php echo "HKD ".number_format($this->mymodel->getlast('biaya','id_biaya')->biaya_remitansi,0,'','.'); ?></h2>
    </div>
  </div>
  <div class="row">
    <div class="col">
      <form action="<?php echo site_url('admin/setting_biaya/administrasi'); ?>" method="post">
        <div class="md-form">
          <label for="form1">Biaya Administrasi</label>
          <input type="number" id="form1" min="0" name="administrasi" class="form-control form-control-lg p-2">
          <button type="submit" class="btn btn-danger mt-3">Simpan</button>
        </div>
      </form>
    </div>
  </div>
  <div class="row mt-4">
    <div class="col">
      <form action="<?php echo site_url('admin/setting_biaya/remitansi'); ?>" method="post">
        <div class="md-form">
          <label for="form1">Biaya Remitansi</label>
          <input type="number" id="form1" min="0" name="remitansi" class="form-control form-control-lg p-2">
          <button type="submit" class="btn btn-danger mt-3">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>


        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- main-panel ends -->
        <!--<script type="text/javascript" src="<?php echo base_url('resource_admin/'); ?>vendor\jquery\jquery.min.js"></script>-->
        
  <!-- Custom js for this page-->  




      