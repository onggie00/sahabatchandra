<?php
use Restserver\Libraries\REST_Controller;
header('Access-Control-Allow-Origin: *');
date_default_timezone_set('Asia/Jakarta');
defined('BASEPATH') OR exit('No direct script access allowed');
define( 'API_ACCESS_KEY', 'AAAA54uLV8E:APA91bGtYTAJDz-Ku9DwEy9HfY4AFh3X6mCBf2n8hc7dTpK_ar5nc4QDT-sFXLBTALZVDOZE00cf3iRu3_2qGns7oPuW9kqi4qFAX9Di4Jvs_uvsLU_wgAkaIN87VI4HUGVFnhTQKfj5' );

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH.'libraries/Format.php';

class Cek_rekening_bank extends REST_Controller {

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

        //$rekening = $this->post('rekening');
        $id_trans_customer = $this->post('id_trans_customer');

        $ceklogin = $this->mymodel->getbywhere("agen","token",$token,'row');
        if (!empty($ceklogin) && !empty($id_trans_customer)) {
          //$data = $this->mymodel->getbywhere('trans_customer','kode_booking',$kode_booking,'row');
          //$data->nama_bank = $this->mymodel->getbywhere('bank',"id_bank",$data->id_bank,'row')->nama_bank;
          $get_data = $this->mymodel->getbywhere('trans_customer','id_trans_customer',$id_trans_customer,'row');
          
          if (empty($get_data)) {
            $msg = array('success'=>0,'message'=>'ID Transaksi tidak ditemukan',"data"=>new \stdClass());
          }else{
            $title = "Notifikasi Transaksi";
            $desc = "Nomor rekening dari Transaksi Pengiriman Uang dengan kode booking ".$get_data->kode_booking." Valid";
            $desc2 = "Nomor rekening dari Transaksi Pengiriman Uang dengan kode booking ".$get_data->kode_booking." Tidak Valid";
            $id_fcm = $this->mymodel->getbywhere('customer','id_customer',$get_data->id_customer,'row')->fcm_id;

            if (!empty($id_fcm)) {
              $konfirmasi_customer = $this->send_user($title,$desc,$id_fcm);
              $data_pesan = array(
                "id_customer" => $get_data->id_customer,
                "id_agen" => $ceklogin->id_agen,
                "id_trans_customer" => $id_trans_customer,
                "judul_pesan" => $title,
                "isi_pesan" => $desc,
                "created_at" => date("Y-m-d H:i:s")
                );
              $simpan_pesan = $this->mymodel->insert('pesan',$data_pesan);
              $msg = array('success'=>1,'message'=>'Cek Rekening Berhasil',"data"=>$data_pesan);
            }else{
              $msg = array('success'=>0,'message'=>'Data Customer tidak ditemukan',"data"=>new \stdClass());
            }
          }
        }else if (empty($id_trans_customer)) {
          $msg = array('success'=>0,'message'=>'ID Transaksi Kosong',"data"=>new \stdClass());
        }
        else{
          $msg = array('success'=>0,'message'=>'Token tidak ditemukan',"data"=>new \stdClass());
        }

        $this->response($msg);
  }

  public function send_user($title,$desc,$id_fcm)
  {
    $Msg = array(
      'body' => $desc,
      'title' => $title,
      'click_action'=>"FLUTTER_NOTIFICATION_CLICK"
    );
    $fcmFields = array(
      'to' => $id_fcm,
      'notification' => $Msg
      // 'data'=>$data
    );
    $headers = array(
      'Authorization: key=' . API_ACCESS_KEY,
      'Content-Type: application/json'
    );
    $ch = curl_init();
    curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
    curl_setopt( $ch,CURLOPT_POST, true );
    curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
    curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fcmFields ) );
    $result = curl_exec($ch );
    curl_close( $ch );
    //print_r($result);
    $cek_respon = explode(',',$result);
    $berhasil = substr($cek_respon[1],strpos($cek_respon[1],':')+1);
    if ($berhasil=='1') {
      return true;
    }
    else if($berhasil=='0'){
      return false;
    }
    echo $result . "\n\n";
  }


}
