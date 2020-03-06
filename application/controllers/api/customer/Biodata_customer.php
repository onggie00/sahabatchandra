<?php
use Restserver\Libraries\REST_Controller;
header('Access-Control-Allow-Origin: *');
date_default_timezone_set('Asia/Jakarta');
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH.'libraries/Format.php';

class Biodata_customer extends REST_Controller {

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
        $cektoken =  $this->mymodel->getbywhere('customer','token',$token,'row');
        if (empty($cektoken)) {
          $msg = array('status' => 0, 'message'=>'Token tidak ditemukan',"data"=>array());
        }
        else{
          $id_customer = $cektoken->id_customer;
          $nama_lengkap = $this->post('nama_lengkap');
          $tempat_lahir = $this->post('tempat_lahir');
          $tanggal_lahir = $this->post('tanggal_lahir');
          $notelp = $this->post('notelp');
          $pekerjaan = $this->post('pekerjaan');
          $alamat = $this->post('alamat');
          $negara = $this->post('negara');
          $id_number = $this->post('id_number');
          $data = array();
          if (!empty($_FILES['img']['name'])) {
              $uploaddir = './assets/image/customer/';
              $img = explode('.', $_FILES['img']['name']);
              $extension = end($img);
              $file_name =  md5(date('y-m-d h:i:s').$_FILES['img']['name']).".".$extension;
              $uploadfile = $uploaddir.$file_name;
              $status = 0;
              if (move_uploaded_file($_FILES['img']['tmp_name'], $uploadfile)) {
              $data = array(
                "img_file" => $file_name,
                "nama_lengkap" => $nama_lengkap,
                "tempat_lahir" => $tempat_lahir,
                "tanggal_lahir" => $tanggal_lahir,
                "notelp" => $notelp,
                "pekerjaan" => $pekerjaan,
                "alamat" => $alamat,
                "negara" => $negara,
                "id_number" => $id_number
              );
                if (!empty($data)) {
                  $up = $this->mymodel->update("customer",$data,"token",$token);
                  $msg = array('success'=>1,'message'=>'Unggah Data Diri Berhasil',"data"=>$data);
                }else{
                  $msg = array('success'=>0,'message'=>'Data tidak valid','data'=>new \stdClass());
                }
              }else{
                $msg = array('success'=>0,'message'=>'Upload Foto Gagal','data'=>new \stdClass());
              }
            }else{
              $msg = array('success'=>0,'message'=>'File tidak boleh kosong ','data'=>new \stdClass());
            }
        }

        $this->response($msg);
  }


}
