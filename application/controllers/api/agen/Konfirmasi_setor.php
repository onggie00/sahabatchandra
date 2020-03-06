<?php
use Restserver\Libraries\REST_Controller;
header('Access-Control-Allow-Origin: *');
date_default_timezone_set('Asia/Jakarta');
defined('BASEPATH') OR exit('No direct script access allowed');
define( 'API_ACCESS_KEY', 'AAAA54uLV8E:APA91bGtYTAJDz-Ku9DwEy9HfY4AFh3X6mCBf2n8hc7dTpK_ar5nc4QDT-sFXLBTALZVDOZE00cf3iRu3_2qGns7oPuW9kqi4qFAX9Di4Jvs_uvsLU_wgAkaIN87VI4HUGVFnhTQKfj5' );

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH.'libraries/Format.php';

class Konfirmasi_setor extends REST_Controller {

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
        $cektoken =  $this->mymodel->getbywhere('agen','token',$token,'row');
        $id_trans_agen = $this->post('id_trans_agen');
        if (empty($cektoken)) {
          $msg = array('status' => 0, 'message'=>'Token tidak ditemukan',"data"=>new \stdClass());
        }else if (empty($id_trans_agen)) {
          $msg = array('status' => 0, 'message'=>'ID Transaksi Agen kosong',"data"=>new \stdClass());
        }
        else{
          $get_kode = $this->mymodel->getbywhere('trans_agen','id_trans_agen',$id_trans_agen,'row');
          if (empty($get_kode)) {
            $msg = array('status' => 0, 'message'=>'ID Transaksi tidak ditemukan',"data"=>new \stdClass());
          }else{
            $data = array(
              "status_transaksi" => 1
              );

              $title = "Agen Telah Konfirmasi";
              $desc = "Agen ".$get_customer->nama_lengkap." dengan kode transaksi ".$get_kode->kode_booking." telah mengkonfirmasi setoran uang.");
              $id_fcm = $this->mymodel->getbywhere('collector','id_collector',$get_kode->id_collector,'row')->fcm_id;
              if (!empty($id_fcm)) {
                $konfirmasi_collector = $this->send_user($title,$desc,$id_fcm);
              }

            $up = $this->mymodel->update('trans_agen',$data,'kode_booking',$get_kode->kode_booking);
            if ($up) {
              $msg = array('success'=>1,'message'=>'Berhasil Konfirmasi Transaksi','data'=>$data);
            }else{
              $msg = array('success'=>0,'message'=>'ID Transaksi Agen telah dikonfirmasi sebelumnya',"data"=>new \stdClass());
            }
          }

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
