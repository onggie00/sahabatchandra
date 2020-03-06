<?php
use Restserver\Libraries\REST_Controller;
header('Access-Control-Allow-Origin: *');
date_default_timezone_set('Asia/Jakarta');
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH.'libraries/Format.php';

class Get_kurs extends REST_Controller {

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

          $data = number_format($this->mymodel->getlast('biaya','id_biaya')->kurs_idr,0,'','.');

          if (!empty($data)) {
            $msg = array('success'=>1,'message'=>'Berhasil Ambil Data',"data"=>$data);
          }else{
            $msg = array('success'=>0,'message'=>'Data tidak ditemukan',"data"=>array());
          }


        $this->response($msg);
  }


}
