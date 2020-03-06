<?php
use Restserver\Libraries\REST_Controller;
header('Access-Control-Allow-Origin: *');
date_default_timezone_set('Asia/Jakarta');
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH.'libraries/Format.php';

class Agen_terdekat extends REST_Controller {

  function __construct($config = 'rest') {
      parent::__construct($config);
  }
  public function get_bulan($bulan){
    $bln = "";
    if ($bulan == 1) {
      $bln = "Januari";
    }else if ($bulan == 2) {
      $bln = "Februari";
    }else if ($bulan == 3) {
      $bln = "Maret";
    }else if ($bulan == 4) {
      $bln = "April";
    }else if ($bulan == 5) {
      $bln = "Mei";
    }else if ($bulan == 6) {
      $bln = "Juni";
    }else if ($bulan == 7) {
      $bln = "Juli";
    }else if ($bulan == 8) {
      $bln = "Agustus";
    }else if ($bulan == 9) {
      $bln = "September";
    }else if ($bulan == 10) {
      $bln = "Oktober";
    }else if ($bulan == 11) {
      $bln = "November";
    }else if ($bulan == 12) {
      $bln = "Desember";
    }
    return $bln;
  }
  public function get_hari($h){
    $hari = "";
    if ($h == 1) {
      $hari = "Senin";
    }else if ($h == 2) {
      $hari = "Selasa";
    }else if ($h == 3) {
      $hari = "Rabu";
    }else if ($h == 4) {
      $hari = "Kamis";
    }else if ($h == 5) {
      $hari = "Jumat";
    }else if ($h == 6) {
      $hari = "Sabtu";
    }else if ($h == 7) {
      $hari = "Minggu";
    }
    return $hari;
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
          $msg = array('success'=>0,'message'=>'Token tidak ditemukan',"data"=>array());
        }else{
        $radius = $this->get('radius');
        if (empty($radius)) {
          $radius = 14;
        }
        $customer = $this->mymodel->getbywhere("customer",'token',$token,'row');
            $data = $this->mymodel->withquery("select *, ( 6371 * acos( cos( radians(".$customer->latitude.") ) * cos( radians( latitude ) ) * 
cos( radians( longitude ) - radians(".$customer->longitude.") ) + sin( radians(".$customer->latitude.") ) * 
sin( radians( latitude ) ) ) ) AS jarak FROM  agen HAVING
jarak < ".$radius." ORDER BY jarak LIMIT 50","result");
            
            if (!empty($data)) {
              $msg = array('status' => 1, 'message'=>'Berhasil ambil data' ,'data'=>$data);
            }else {
              $msg = array('status' => 0, 'message'=>'Data tidak ditemukan' ,'data'=>array());
            }
        }

        $this->response($msg);
  }


}
