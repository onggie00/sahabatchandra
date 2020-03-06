<?php
use Restserver\Libraries\REST_Controller;
header('Access-Control-Allow-Origin: *');
date_default_timezone_set('Asia/Jakarta');
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH.'libraries/Format.php';

class Simpan_lokasi extends REST_Controller {

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

        if (empty($cek_token)) {
          $msg = array('success'=>0,'message'=>'Token tidak ditemukan');
        }else{
          $data = array();

          $data = array(
            "longitude" => $this->post('longitude'),
            "latitude" => $this->post('latitude')
            );
          if (!empty($data)) {
            $up = $this->mymodel->update('agen',$data,'token',$token);
              $msg = array('success'=>1,'message'=>'Berhasil Update Data',"data"=>$data);
          }else{
            $msg = array('success'=>0,'message'=>'Gagal Simpan Lokasi',"data"=>array());
          }
        }

        $this->response($msg);
  }


}
