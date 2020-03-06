

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-2 text-gray-800"> <?php echo $title_page; ?> </h1>
          <p class="mb-4 hidden">&nbsp;</p>
          <div class="download_menu">
            <form action="<?php echo site_url('admin/exportexcel/'); ?>" method="POST">
              <div class="row form-group">
                <div class="col-md-3">
                  <label for="clabel">Dari Periode</label>
                  <input type="date" class="form-control" name="periode_awal" />
                </div>
                <div class=" col-md-3">
                  <label for="clabel">Sampai Periode</label>
                  <input type="date" class="form-control" name="periode_akhir" />
                </div>
              </div>
              <div class="row form-group">
                <div class="col">
                  <input type="submit" id="btn_exports" name="btn_bi" class="btn btn_download" value="Laporan BI" />
                  <input type="submit" id="btn_exports" name="btn_sipesat" class="btn btn_download" value="Laporan Sipesat" />
                  <input type="submit" id="btn_exports" name="btn_ppatk" class="btn btn_download" value="Laporan PPATK" />
                  <input type="submit" id="btn_exports" name="btn_ptd" class="btn btn_download" value="Penilaian Resiko PTD" />
                
                </div>
              </div>
            </form>
          </div>

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

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-body">
              <div class="table-responsive">
                <table id="data" class="table table-striped table-bordered" style="width:100%;">
                  <thead class="thead-light">
                    <tr>
                      <th>Nomor</th>
                      <th>Kode Booking</th>
                      <th>Nama Tujuan</th>
                      <th>No Rek Tujuan</th>
                      <th>Nama bank</th>
                      <th>Telepon</th>
                      <th>Alamat</th>
                      <th>Kota / Kabupaten</th>
                      <th>Provinsi</th>
                      <th>Sumber Dana</th>
                      <th>Untuk Keperluan</th>
                      <th>Total Kiriman</th>
                      <th>Biaya Remitansi</th>
                      <th>Total Bayar</th>
                      <th>Kurs Rupiah</th>
                      <th>Biaya Admin</th>
                      <th>Total Terima</th>
                      <th>Transaksi</th>
                      <th>Tanggal</th>
                    </tr>
                  </thead>
                  
              </table>
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
  <script >
  $(document).ready(function(){
    
       var dataTable = $('#data').DataTable({
         "dom": 'Blfrtip',
         "buttons": {
             dom: {
               button: {
                 tag: 'button',
                 className: ''
               }
             },
             buttons: [{
               extend: 'excel',
               className: 'btn btn-sm btn-info',
               titleAttr: 'Excel export.',
               text: 'Download Excel',
               exportOptions: {
                    columns: 'th:not(:last-child)'
              }
             },{
               extend: 'csv',
               className: 'btn btn-sm btn-info',
               titleAttr: 'CSV export.',
               text: 'Download CSV',
               exportOptions: {
                    columns: 'th:not(:last-child)'
              }
             },{
               extend: 'print',
               className: 'btn btn-sm btn-info',
               titleAttr: 'PDF export.',
               text: 'Print Table',
               exportOptions: {
                    columns: 'th:not(:last-child)'
              }
             }]
           },
            "scrollX":true,
            "processing":true,
            "serverSide":true,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "order":[],
            "ajax":{
                 url:"<?php echo base_url() .'admin/histori_kirim/alldata/'; ?>",
                 type:"POST"
            },
            "columnDefs":[
                 {
                      "targets":[0],
                      "orderable":false,
                 },
            ],
            "initComplete": function(settings, json) {
                $(".current").addClass("btn btn-primary");
              $(".current").removeClass("paginate_button");
            },
            "fnDrawCallback": function( oSettings ) {

                  $(".current").addClass("btn btn-primary");
                $(".current").removeClass("paginate_button");
            }
       });

  });
$(function(){
    $( "#btn_export" ).click(function(event)
        {
            event.preventDefault();

        var date= $("#date").val();  

        $.ajax(
        {
            type: "post",
            url: "<?php echo base_url(); ?>admin/exportexcel/",
            data:{
                'periode':date,
                'btn_bi':"BI"
            },
            //dataType: 'JSON',
            success:function(data)
            {
              //window.location.replace(data);
                console.log(data);
            }
        });
    });
});
  </script>


      