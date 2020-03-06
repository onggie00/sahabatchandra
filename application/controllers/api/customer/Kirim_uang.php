<?php
use Restserver\Libraries\REST_Controller;
header('Access-Control-Allow-Origin: *');
date_default_timezone_set('Asia/Jakarta');
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH.'libraries/Format.php';

class Kirim_uang extends REST_Controller {

  function __construct($config = 'rest') {
      parent::__construct($config);
  }

  function index_post() {
      $token = "";
      $headers=array();
      foreach (getallheaders() as $name => $value) {
          $headers[$name] = $value;
      }
      if(isset($headers['token']))
        $token =  $headers['token'];
        $cek_token = $this->mymodel->getbywhere("customer",'token',$token,'row');

        if (empty($cek_token)) {
          $msg = array('success'=>0,'message'=>'Token tidak ditemukan',"kode_booking"=>"","kode_qr"=>"");
        }else{
          $data = array();

          $nama_tujuan = $this->post("nama_tujuan");
          $norek_tujuan = $this->post("norek");
          $id_bank = $this->post("id_bank");
          $notelp_tujuan = $this->post('notelp');
          $alamat_tujuan = $this->post('alamat');
          $id_provinsi = $this->post('id_provinsi');
          $id_kabupaten = $this->post('id_kabupaten');
          $kiriman_uang = $this->post("total_kirim");
          $biaya_remitansi = $this->mymodel->getlast('biaya','id_biaya')->biaya_remitansi;
          $total_bayar = $this->post('total_bayar');
          $total_idr = $this->post('total_idr');
          $biaya_admin = $this->mymodel->getlast('biaya','id_biaya')->biaya_admin;
          $total_diterima = $this->post('total_diterima');
          $sumber_dana = $this->post('sumber_dana');
          $keperluan = $this->post('keperluan');
          $length = 5;
          $kode_booking =  substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1,$length);

          $get_batas = $this->mymodel->getlast('biaya','id_biaya')->batas_kirim_uang;
          if ($kiriman_uang > $get_batas) {
            $msg = array('success'=>0,'message'=>"Nominal Transaksi melebihi Batas User","kode_booking"=>"","kode_qr"=>"");
          }
          else{
//cek total transaksi customer
          //$get_total_perbulan = $this->mymodel->withquery("select sum(total_bayar) as total_bayar, extract(MONTH from created_at) as m from trans_customer where id_customer = '".$cek_token->id_customer."' and jenis_transaksi='Bayar Kepada' group by m order by m DESC",'row')->total_bayar;
              $get_total_perbulan  =0;
          //cek batas transaksi
          $get_batas_perbulan = $this->mymodel->getlast('biaya','id_biaya')->batas_perbulan;
          if ($get_total_perbulan+$total_bayar >=$get_batas_perbulan) {
            $msg = array('success'=>0,'message'=>"Kuota kirim uang dengan Aplikasi untuk bulan ini sudah melebihi HKD ".number_format($get_batas_perbulan,0,'','.').", silakan hubungi agen terdekat","kode_booking"=>"","kode_qr"=>"");
          }
          else{
//Membuat QR Code
          $this->load->library('ciqrcode'); //pemanggilan library QR CODE
          $config['cacheable']    = true; //boolean, the default is true
          $config['cachedir']     = '/assets/image/trans_customer/'; //string, the default is application/cache/
          $config['errorlog']     = '/assets/logs/'; //string, the default is application/logs/
          $config['imagedir']     = '/assets/image/trans_customer/'; //direktori penyimpanan qr code
          $config['quality']      = true; //boolean, the default is true
          $config['size']         = '1024'; //interger, the default is 1024
          $config['black']        = array(224,255,255); // array, default is array(255,255,255)
          $config['white']        = array(70,130,180); // array, default is array(0,0,0)
          $this->ciqrcode->initialize($config);
   
          $image_name=md5($kode_booking).'.png'; //buat name dari qr code sesuai dengan nim
   
          $params['data'] = $kode_booking; //data yang akan di jadikan QR CODE
          $params['level'] = 'H'; //H=High
          $params['size'] = 10;
          $params['savename'] = FCPATH.$config['imagedir'].$image_name; //simpan image QR CODE ke folder assets/images/
          $create = $this->ciqrcode->generate($params); // fungsi untuk generate QR CODE

          //print_r($create);
          $data = array(
            "id_customer" => $cek_token->id_customer,
            "id_bank" => $id_bank,
            "nama_tujuan" => $nama_tujuan,
            "norek_tujuan" => $norek_tujuan,
            "notelp_tujuan" => $notelp_tujuan,
            "alamat_tujuan" => $alamat_tujuan,
            "id_provinsi" => $id_provinsi,
            "id_kabupaten" => $id_kabupaten,
            "sumber_dana" => $sumber_dana,
            "keperluan" => $keperluan,
            "jenis_transaksi" => "Kirim Uang",
            "total_kirim" => $kiriman_uang,
            "biaya_remitansi" => $biaya_remitansi,
            "total_bayar" => $total_bayar,
            "total_idr" => $total_idr,
            "biaya_admin" => $biaya_admin,
            "total_diterima" => $total_diterima,
            "kode_booking" => $kode_booking,
            "kode_qr" => $image_name,
            "created_at" => date("Y-m-d H:i:s")
            );
          //check empty beberapa
          foreach ($data as $key => $value) {
            if (empty($value)) {
              $data = array();
              $msg = array('success'=>0,'message'=>"Field ".$key." tidak boleh kosong","kode_booking"=>"","kode_qr"=>"");
            }
          }
          //check keseluruhan empty
          if (!empty($data)) {
            $in = $this->mymodel->insert('trans_customer',$data);
            $msg = array('success'=>1,'message'=>'Berhasil Tambah Data Transaksi',"kode_booking"=>$kode_booking,"kode_qr"=>base_url('assets/image/trans_customer/'.$image_name));
          }
        }
      }
    }
    $this->response($msg);
  }

}
