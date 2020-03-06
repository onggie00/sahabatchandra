<?php
use Restserver\Libraries\REST_Controller;
header('Access-Control-Allow-Origin: *');
date_default_timezone_set('Asia/Jakarta');
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH.'libraries/Format.php';

class Lihat_kode_booking extends REST_Controller {

  function __construct($config = 'rest') {
      parent::__construct($config);
  }


  function index_get() {
      $token = "";
      $headers=array();
      foreach (getallheaders() as $name => $value) {
          $headers[$name] = $value;
      }
      if(isset($headers['token']))
        $token =  $headers['token'];
        $cek_token = $this->mymodel->getbywhere("customer",'token',$token,'row');

        if (empty($cek_token)) {
          $msg = array('success'=>0,'message'=>'Token tidak ditemukan',"data"=>new \stdClass());
        }else{
          $id_transaksi = $this->get('id_transaksi');
          if (empty($id_transaksi)) {
            $msg = array('success'=>0,'message'=>'ID Transaksi tidak ditemukan',"data"=>new \stdClass());
          }else{
            $data->kode_booking = $this->mymodel->getbywhere('trans_customer',"id_trans_customer=",$id_transaksi,"row")->kode_booking;
            $data->kode_qr = $this->mymodel->getbywhere('trans_customer',"id_trans_customer=",$id_transaksi,"row")->kode_qr;

            $data->kode_qr = base_url('assets/image/trans_customer/'.$data->kode_qr);
            foreach ($data as $key => $value) {
              if (empty($value)) {
                $data = array();
              }
            }
            if (!empty($data)) {
              $msg = array('success'=>1,'message'=>'Berhasil Ambil Data',"data"=>$data);
            }else{
              $msg = array('success'=>0,'message'=>'Data tidak ditemukan',"data"=>new \stdClass());
            }
          }
        }

        $this->response($msg);
  }


}
