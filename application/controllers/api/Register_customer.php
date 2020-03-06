<?php
use Restserver\Libraries\REST_Controller;
header('Access-Control-Allow-Origin: *');
date_default_timezone_set('Asia/Jakarta');
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'libraries/REST_Controller.php';
require APPPATH.'libraries/Format.php';
class Register_customer extends REST_Controller {

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

        //$mem = $this->mymodel->getbywhere('user','token',$token,"row");
        $username = $this->post('username');
        $password = $this->post('password');
        $cpassword = $this->post('cpassword');
        $email = $this->post("email");
        $cek_username = $this->mymodel->getbywhere("customer","username",$username,'row');
        $cek_email = $this->mymodel->getbywhere('customer','email',$email,'row');
        if (!empty($cek_username)) {
          $msg = array('status' => 0, 'message'=>'Username sudah terdaftar',"token"=>"");
        }else{
          if (!empty($cek_email)) {
            $msg = array('status' => 0, 'message'=>'Email sudah terdaftar',"token"=>"");
          }else{
              if(!empty($this->post('username')) || !empty($this->post('password'))
                       || !empty($this->post('cpassword')) ){

                   $msg = array('status' => 0, 'message'=>'Form tidak boleh kosong',"token"=>"");
             }else{
                 if ($password == $cpassword) {
                   $token = md5($username." ".date("Y-m-d H:i:s"));
                   $data = array(
                     "username" => $username,
                     "password" => md5($password),
                     "email" => $email,
                     "token" => $token,
                     "created_at" => date("Y-m-d H:i:s")
                   );
                   if (!empty($data)) {
                     $this->mymodel->insert('customer',$data);
                     $msg = array('status' => 1, 'message'=>'Berhasil Register Customer',"token"=>$token);
                   }else {
                     $msg = array('status' => 0, 'message'=>'Data tidak valid',"token"=>"");
                   }
                 }else{
                     $msg = array('status' => 0, 'message'=>'Password dan Konfirmasi Password tidak sama',"token"=>"");
                 }
             }
            
          }
        }

        $this->response($msg);
  }


}
