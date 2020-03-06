

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
<div class="container bg-white p-3">
  <!-- Collapse buttons -->
  <div class="row justify-content-around text-center">
    <div class="col">
      <h2 class="p-3 bg-light"><?php echo "HKD ".number_format($this->mymodel->getlast('biaya','id_biaya')->batas_perbulan,0,'','.'); ?></h2>
      <a class=" link-setting" data-toggle="collapse" href="#batas1" aria-expanded="false"
      aria-controls="batas1">BATAS PENGIRIMAN BULANAN USER</a>
    </div>
    <div class="col">
      <h2 class="p-3 bg-light"><?php echo "HKD ".number_format($this->mymodel->getlast('biaya','id_biaya')->batas_kirim_uang,0,'','.'); ?></h2>
      <a class="link-setting" data-toggle="collapse" href="#batas2" aria-expanded="false"
        aria-controls="batas2">BATAS NOMINAL SEKALI PENGIRIMAN</a>
    </div>
    <div class="col">
      <h2 class="p-3 bg-light"><?php echo "HKD ".number_format($this->mymodel->getlast('biaya','id_biaya')->deposit,0,'','.'); ?></h2>
      <a class="link-setting" data-toggle="collapse" href="#batas3" aria-expanded="false"
        aria-controls="batas3">BATAS MAKSIMUM DEPOSIT AGEN</a>    
    </div>
  </div>
  <!--/ Collapse buttons -->

  <hr class="sidebar-divider my-3">

  <!-- Collapsible content -->
  <div class="row">
    <div class="col">
      <div class="collapse multi-collapse" id="batas1">
        <div class="card card-body">
          <form action="<?php echo site_url('admin/setting_batas/bulanan'); ?>" method="post">
            <div class="md-form">
              <label for="form1">Masukkan Nominal untuk Batas Bulanan User</label>
              <input type="number" id="form1" min="0" name="bulanan" class="form-control form-control-lg p-2">
              <button type="submit" class="btn btn-danger mt-3">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col">
      <div class="collapse multi-collapse" id="batas2">
        <div class="card card-body">
          <form action="<?php echo site_url('admin/setting_batas/transaksi'); ?>" method="post">
            <div class="md-form">
              <label for="form1">Masukkan Nominal untuk Batas Setiap Transaksi User</label>
              <input type="number" id="form1" min="0" name="transaksi" class="form-control form-control-lg p-2">
              <button type="submit" class="btn btn-danger mt-3">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>  
  </div>
  <div class="row">
    <div class="col">
      <div class="collapse multi-collapse" id="batas3">
        <div class="card card-body">
          <form action="<?php echo site_url('admin/setting_batas/deposit'); ?>" method="post">
            <div class="md-form">
              <label for="form1">Masukkan Nominal untuk Batas Deposit Agen</label>
              <input type="number" id="form1" min="0" name="deposit" class="form-control form-control-lg p-2">
              <button type="submit" class="btn btn-danger mt-3">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>  
  </div>


  <!--/ Collapsible content -->
</div>


        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- main-panel ends -->
        <!--<script type="text/javascript" src="<?php echo base_url('resource_admin/'); ?>vendor\jquery\jquery.min.js"></script>-->
        
  <!-- Custom js for this page-->  




      