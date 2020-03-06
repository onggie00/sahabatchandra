<?php
use Restserver\Libraries\REST_Controller;
header('Access-Control-Allow-Origin: *');
date_default_timezone_set('Asia/Jakarta');
defined('BASEPATH') OR exit('No direct script access allowed');
define( 'API_ACCESS_KEY', 'AAAA54uLV8E:APA91bGtYTAJDz-Ku9DwEy9HfY4AFh3X6mCBf2n8hc7dTpK_ar5nc4QDT-sFXLBTALZVDOZE00cf3iRu3_2qGns7oPuW9kqi4qFAX9Di4Jvs_uvsLU_wgAkaIN87VI4HUGVFnhTQKfj5' );

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH.'libraries/Format.php';

class Terima_uang extends REST_Controller {

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
        $id_trans_customer = $this->post('id_trans_customer');
        
        if (!empty($token) && !empty($id_trans_customer)) {
          $ceklogin = $this->mymodel->getbywhere("agen","token",$token,'row');
          //cek batas transaksi
          $get_batas = $this->mymodel->getlast('biaya','id_biaya')->deposit;
          $get_id_trans = $this->mymodel->getbywhere('trans_customer','id_trans_customer',$id_trans_customer,'row');
          if (empty($get_id_trans)) {
            $msg = array('success'=>0,'message'=>'ID Transaksi Tidak ditemukan',"data"=>new \stdClass());
          }else{
            if ($ceklogin->total_uang+$get_id_trans->total_bayar > $get_batas) {
              $msg = array('success'=>0,'message'=>'Agen Telah Mencapai Batas Deposit. Tidak dapat melanjutkan transaksi',"data"=>new \stdClass());
            }else{
              $data = array(
                "id_trans_customer" => $id_trans_customer,
                "id_agen" => $ceklogin->id_agen,
                "jenis_transaksi" => "Penerimaan Uang",
                "status_transaksi" => "1",
                "total_setor" => $get_id_trans->total_bayar,
                "created_at" => date("Y-m-d H:i:s")
                );

              if (!empty($data)) {
                $in = $this->mymodel->insert("trans_agen",$data);
                if ($in) {
                  $data['total_setor'] = "HKD ".number_format($get_id_trans->total_bayar,0,"",".");
                  $data2 = array(
                    "id_customer" => $get_id_trans->id_customer,
                    "id_agen" => $ceklogin->id_agen,
                    "id_bank" => $get_id_trans->id_bank,
                    "nama_tujuan" => $get_id_trans->nama_tujuan,
                    "norek_tujuan" => $get_id_trans->norek_tujuan,
                    "notelp_tujuan" => $get_id_trans->notelp_tujuan,
                    "alamat_tujuan" => $get_id_trans->alamat_tujuan,
                    "id_provinsi" => $get_id_trans->id_provinsi,
                    "id_kabupaten" => $get_id_trans->id_kabupaten,
                    "sumber_dana" => $get_id_trans->sumber_dana,
                    "keperluan" => $get_id_trans->keperluan,
                    "jenis_transaksi" => "Bayar Kepada",
                    "total_kirim" => $get_id_trans->total_kirim,
                    "biaya_remitansi" => $get_id_trans->biaya_remitansi,
                    "total_bayar" => $get_id_trans->total_bayar,
                    "total_idr" => $get_id_trans->total_idr,
                    "biaya_admin" => $get_id_trans->biaya_admin,
                    "total_diterima" => $get_id_trans->total_diterima,
                    "kode_booking" => $get_id_trans->kode_booking,
                    "kode_qr" => $get_id_trans->kode_qr,
                    "created_at" => date("Y-m-d H:i:s")
                    );
                  $in2 = $this->mymodel->insert("trans_customer",$data2);
                  $uang = $ceklogin->total_uang+$get_id_trans->total_bayar;
                  $data3 = array(
                    "total_uang" => $uang,
                    );
                  $update = $this->mymodel->update('agen',$data3,"id_agen",$ceklogin->id_agen);
                }
                $title = "Konfirmasi Transaksi";
                $desc = "Anda melakukan pembayaran kepada Agen ".$ceklogin->nama_lengkap." sebesar ".number_format($get_id_trans->total_bayar,0,"",".");
                $id_fcm = $this->mymodel->getbywhere('customer','id_customer',$get_id_trans->id_customer,'row')->fcm_id;
                if (!empty($id_fcm)) {
                $konfirmasi_customer = $this->send_user($title,$desc,$id_fcm);

                $data_pesan = array(
                  "id_customer" => $get_id_trans->id_customer,
                  "id_agen" => $ceklogin->id_agen,
                  "id_trans_customer" => $id_trans_customer,
                  "judul_pesan" => $title,
                  "isi_pesan" => $desc,
                  "created_at" => date("Y-m-d H:i:s")
                  );
                $simpan_pesan = $this->mymodel->insert('pesan',$data_pesan);
                $get_last = $this->mymodel->getlast("trans_customer",'id_trans_customer');
                $msg = array('success'=>1,'message'=>'Berhasil Tambah Data',"data"=>$get_last);
                }
              }else{
                $msg = array('success'=>0,'message'=>'Data tidak valid',"data"=>new \stdClass());
              }
            }
          }
        }else if(empty($id_trans_customer)){
          $msg = array('success'=>0,'message'=>'ID Transaksi tidak boleh kosong',"data"=>new \stdClass());
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
