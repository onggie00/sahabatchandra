<?php
use Restserver\Libraries\REST_Controller;
header('Access-Control-Allow-Origin: *');
date_default_timezone_set('Asia/Jakarta');
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH.'libraries/Format.php';

class Setor_uang extends REST_Controller {

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
        $cek_token = $this->mymodel->getbywhere("agen",'token',$token,'row');
        $total_setor = $this->post('total_setor');

        if (empty($cek_token)) {
          $msg = array('success'=>0,'message'=>'Token tidak ditemukan',"data" => new \stdClass(),"kode_booking"=>"","kode_qr"=>"");
        }else if(empty($total_setor)){
          $msg = array('success'=>0,'message'=>'Total Setoran tidak boleh kosong',"data" => new \stdClass(),"kode_booking"=>"","kode_qr"=>"");
        }
        else{
          $data = array();
          //$id_collector = $this->post("id_collector");
          $jenis_transaksi = "Setor Uang";
          $length = 5;
          $kode_booking =  substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1,$length);

//Membuat QR Code
          $this->load->library('ciqrcode'); //pemanggilan library QR CODE
          $config['cacheable']    = true; //boolean, the default is true
          $config['cachedir']     = './assets/image/trans_agen/'; //string, the default is application/cache/
          $config['errorlog']     = './assets/'; //string, the default is application/logs/
          $config['imagedir']     = './assets/image/trans_agen/'; //direktori penyimpanan qr code
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
          $this->ciqrcode->generate($params); // fungsi untuk generate QR CODE

          $data = array(
            "id_agen" => $cek_token->id_agen,
            "jenis_transaksi" => $jenis_transaksi,
            "total_setor" => $total_setor,
            "kode_booking" => $kode_booking,
            "kode_qr" => $image_name,
            "created_at" => date("Y-m-d H:i:s")
            );
          if (!empty($data)) {
            $in = $this->mymodel->insert('trans_agen',$data);
            $msg = array('success'=>1,'message'=>'Berhasil Tambah Data Transaksi',"data" => $data,"kode_booking"=>$kode_booking,"kode_qr"=>base_url('assets/image/trans_agen/'.$image_name));
          }
        }

        $this->response($msg);
  }


}
