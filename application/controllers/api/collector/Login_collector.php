<?php
use Restserver\Libraries\REST_Controller;
header('Access-Control-Allow-Origin: *');
date_default_timezone_set('Asia/Jakarta');
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH.'libraries/Format.php';

class Login_collector extends REST_Controller {

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

        $username = $this->post('username');
        $password = md5($this->post('password'));
        if ($username != "" && $password !="") {
          $ceklogin = $this->mymodel->getbywhere("collector","username='".$username."' and password=",$password,'row');
          
          if (empty($ceklogin)) {
            $msg = array('success'=>0,'message'=>'Username atau Password Salah',"token"=>"", "nama_lengkap"=>"");
          }else{
            $msg = array('success'=>1,'message'=>'Login Berhasil',"token"=>$ceklogin->token, "nama_lengkap"=>$ceklogin->nama_lengkap);
          }
        }else{
          $msg = array('success'=>0,'message'=>'Username atau Password tidak boleh kosong',"token"=>"", "nama_lengkap"=>"");
        }

        $this->response($msg);
  }


}
