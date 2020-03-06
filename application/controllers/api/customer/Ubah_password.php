<?php
use Restserver\Libraries\REST_Controller;
header('Access-Control-Allow-Origin: *');
date_default_timezone_set('Asia/Jakarta');
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH.'libraries/Format.php';

class Ubah_password extends REST_Controller {

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
          $msg = array('success'=>0,'message'=>'Token tidak ditemukan',"data"=>array());
        }else{
          $data = array();
          $pw_lama = $this->post("password_lama");
          $pw_baru = $this->post("password_baru");
          $cpw_baru = $this->post('cpassword_baru');
          $cek_pw = $this->mymodel->getbywhere('customer',"password='".md5($pw_lama)."' and token=",$token,'row');
          if (!empty($cek_pw)) {
            if ($pw_baru == $cpw_baru) {
              $data = array(
              "password" => md5($cpw_baru)
              );
              $up = $this->mymodel->update('customer',$data,'token',$token);
              $msg = array('success'=>1,'message'=>'Berhasil Ubah Password',"data"=>$data);
            }else{
              $msg = array('success'=>0,'message'=>'Password dan Konfirmasi Password Baru Tidak Lama');
            }
          }else{
            $msg = array('success'=>0,'message'=>'Password Lama Salah',"data"=>array());
          }

        }

        $this->response($msg);
  }


}
