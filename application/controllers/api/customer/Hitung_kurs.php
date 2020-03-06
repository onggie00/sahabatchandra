<?php
use Restserver\Libraries\REST_Controller;
header('Access-Control-Allow-Origin: *');
date_default_timezone_set('Asia/Jakarta');
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH.'libraries/Format.php';

class Hitung_kurs extends REST_Controller {

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
          $msg = array('success'=>0,'message'=>'Token tidak ditemukan',"data"=>new \stdClass());
        }else{
          $data = array();
          $uang_hkd = $this->post("uang");
          if (empty($uang_hkd)) {
            $msg = array('success'=>0,'message'=>'Total Uang Harus Di Isi',"data"=>new \stdClass());
          }else{
            $hkd_remitansi = $uang_hkd + $this->mymodel->getlast('biaya','id_biaya')->biaya_remitansi;
            $hkd_idr = $uang_hkd * $this->mymodel->getlast('biaya','id_biaya')->kurs_idr;
            $idr_admin = $this->mymodel->getlast('biaya','id_biaya')->biaya_admin;
            $total_diterima = $hkd_idr - $idr_admin;

            $data = array(
              "uang_hkd" => "$ ".number_format($uang_hkd,0,"","."),
              "biaya_remitansi" => "$ ".number_format($this->mymodel->getlast('biaya','id_biaya')->biaya_remitansi,0,"","."),
              "hkd_remitansi" => "$ ".number_format($hkd_remitansi,0,"","."),
              "hkd_idr" => "Rp. ".number_format($hkd_idr,0,"","."),
              "idr_admin" => "Rp. ".number_format($idr_admin,0,"","."),
              "total_diterima" => "Rp. ".number_format($total_diterima,0,"",".")
              );
            if (!empty($data)) {
              $msg = array('success'=>1,'message'=>'Berhasil Ambil Data',"data"=>$data);
            }else{
              $msg = array('success'=>0,'message'=>'Gagal Hitung Kurs',"data"=>new \stdClass());
            }
          }

        }

        $this->response($msg);
  }


}
