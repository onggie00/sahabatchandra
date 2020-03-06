<?php
use Restserver\Libraries\REST_Controller;
header('Access-Control-Allow-Origin: *');
date_default_timezone_set('Asia/Jakarta');
defined('BASEPATH') OR exit('No direct script access allowed');
define( 'API_ACCESS_KEY', 'AAAA54uLV8E:APA91bGtYTAJDz-Ku9DwEy9HfY4AFh3X6mCBf2n8hc7dTpK_ar5nc4QDT-sFXLBTALZVDOZE00cf3iRu3_2qGns7oPuW9kqi4qFAX9Di4Jvs_uvsLU_wgAkaIN87VI4HUGVFnhTQKfj5' );

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH.'libraries/Format.php';

class Terima_setor extends REST_Controller {

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
        $kode_booking = $this->post('kode_booking');
        
        if (!empty($token) && !empty($kode_booking)) {
          $ceklogin = $this->mymodel->getbywhere("collector","token",$token,'row');
          $get_trans = $this->mymodel->getbywhere('trans_agen','kode_booking',$kode_booking,'row');
            if (empty($get_trans)) {
              $msg = array('success'=>0,'message'=>'Kode Booking tidak ditemukan',"data"=>new \stdClass());
            }
            else{
              if ($get_trans->status_transaksi==1) {
                $msg = array('success'=>0,'message'=>'Kode Booking sudah tidak berlaku karena Setoran Telah Dilakukan Sebelumnya',"data"=>new \stdClass());
              }
              else if ($get_trans->status_transaksi==0) {
                $data = array(
                  "status_transaksi" => "1",
                  "id_collector" => $ceklogin->id_collector
                  );
                $up1 = $this->mymodel->update('trans_agen',$data,'kode_booking',$kode_booking);

                $get_agen = $this->mymodel->getbywhere('agen','id_agen',$get_trans->id_agen,'row');
                $uang = $get_agen->total_uang - $get_trans->total_setor;
                $data2 = array(
                  "total_uang" => $uang
                  );
                $up = $this->mymodel->update('agen',$data2,'id_agen',$get_trans->id_agen);
                $data2['id_trans_agen'] = $get_trans->id_trans_agen;
                if ($up) {
                  $title = "Konfirmasi Transaksi";
                  $desc = "Agen melakukan Setor Uang kepada Collector ".$ceklogin->nama_lengkap." sebesar ".number_format($get_trans->total_setor,0,"",".");
                  $id_fcm = $this->mymodel->getbywhere('agen','id_agen',$get_trans->id_agen,'row')->fcm_id;

                  $konfirmasi_customer = $this->send_user($title,$desc,$id_fcm);

                  $data_pesan = array(
                  "id_agen" => $get_agen->id_agen,
                  "judul_pesan" => $title,
                  "isi_pesan" => $desc,
                  "created_at" => date("Y-m-d H:i:s")
                  );
                $simpan_pesan = $this->mymodel->insert('pesan',$data_pesan);
                  $msg = array('success'=>1,'message'=>'Berhasil Update Data',"data"=>$data2);
                }else{
                  $msg = array('success'=>0,'message'=>'Gagal Update data',"data"=>new \stdClass());
                }
              }
            }
        }else if (empty($kode_booking)) {
          $msg = array('success'=>0,'message'=>'Kode Booking Kosong',"data"=>new \stdClass());
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
