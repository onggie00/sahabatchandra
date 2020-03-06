<?php
use Restserver\Libraries\REST_Controller;
header('Access-Control-Allow-Origin: *');
date_default_timezone_set('Asia/Jakarta');
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH.'libraries/Format.php';

class Update_fcm extends REST_Controller {

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
          $msg = array('success'=>0,'message'=>'Token tidak ditemukan');
        }
        else{
          $data = array();
          $id_fcm = $this->post("fcm_id");
          $data = array(
            "fcm_id" => $id_fcm
            );
          if (!empty($data)) {
            $up = $this->mymodel->update('customer',$data,'token',$token);
            if ($up) {
              $msg = array('success'=>1,'message'=>'Berhasil Ubah data',"data" => $data);  
            }else{
            $msg = array('success'=>0,'message'=>'Gagal Ubah Data',"data"=>array());
            }
          }else{
            $msg = array('success'=>0,'message'=>'Data tidak boleh kosong',"data"=>array());
          }
        }
        $this->response($msg);
  }


}
