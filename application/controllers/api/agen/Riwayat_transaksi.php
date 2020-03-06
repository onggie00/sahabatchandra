<?php
use Restserver\Libraries\REST_Controller;
header('Access-Control-Allow-Origin: *');
date_default_timezone_set('Asia/Jakarta');
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH.'libraries/Format.php';

class Riwayat_transaksi extends REST_Controller {

  function __construct($config = 'rest') {
      parent::__construct($config);
  }
  public function get_bulan($bulan){
    $bln = "";
    if ($bulan == 1) {
      $bln = "Januari";
    }else if ($bulan == 2) {
      $bln = "Februari";
    }else if ($bulan == 3) {
      $bln = "Maret";
    }else if ($bulan == 4) {
      $bln = "April";
    }else if ($bulan == 5) {
      $bln = "Mei";
    }else if ($bulan == 6) {
      $bln = "Juni";
    }else if ($bulan == 7) {
      $bln = "Juli";
    }else if ($bulan == 8) {
      $bln = "Agustus";
    }else if ($bulan == 9) {
      $bln = "September";
    }else if ($bulan == 10) {
      $bln = "Oktober";
    }else if ($bulan == 11) {
      $bln = "November";
    }else if ($bulan == 12) {
      $bln = "Desember";
    }
    return $bln;
  }
  public function get_hari($h){
    $hari = "";
    if ($h == 1) {
      $hari = "Senin";
    }else if ($h == 2) {
      $hari = "Selasa";
    }else if ($h == 3) {
      $hari = "Rabu";
    }else if ($h == 4) {
      $hari = "Kamis";
    }else if ($h == 5) {
      $hari = "Jumat";
    }else if ($h == 6) {
      $hari = "Sabtu";
    }else if ($h == 7) {
      $hari = "Minggu";
    }
    return $hari;
  }

  function index_get() {
      $token = "";
      $headers=array();
      foreach (getallheaders() as $name => $value) {
          $headers[$name] = $value;
      }
      if(isset($headers['token']))
        $token =  $headers['token'];
        $cek_token = $this->mymodel->getbywhere("agen",'token',$token,'row');

        if (empty($cek_token)) {
          $msg = array('success'=>0,'message'=>'Token tidak ditemukan',"data"=>array());
        }else{
          $data = $this->mymodel->getbywhere('trans_agen',"id_agen",$cek_token->id_agen,"result");
          foreach ($data as $key => $value) {
            $convert = date("d m Y N",strtotime($value->created_at));
            $convert = explode(" ", $convert);
            $tgl = $convert[0];
            $bln = $this->get_bulan($convert[1]);
            $thn = $convert[2];
            $hari = $this->get_hari($convert[3]);
            $value->created_at = $tgl." ".$bln." ".$thn." ".date("H:i",strtotime($value->created_at));
            if ($value->id_trans_customer == 0) {
              $value->kode_qr = base_url('assets/image/trans_agen/'.$value->kode_qr);
                if($value->id_collector != 0){
                    $value->nama_pengirim = $this->mymodel->getbywhere('collector','id_collector',$value->id_collector,'row')->nama_lengkap;
                }else{

                    $value->nama_pengirim = "";
                }
            }else{
              $get_trans_customer = $this->mymodel->getbywhere('trans_customer','id_trans_customer',$value->id_trans_customer,'row');
                $value->kode_booking =$get_trans_customer->kode_booking;
              $value->nama_pengirim = $this->mymodel->getbywhere('customer','id_customer',$get_trans_customer->id_customer,'row')->nama_lengkap;
            }
            $value->total_setor = "HKD ".number_format($value->total_setor,0,"",".");
          }

          if (!empty($data)) {
            $msg = array('success'=>1,'message'=>'Berhasil Ambil Data',"data"=>$data);
          }else{
            $msg = array('success'=>0,'message'=>'Data tidak ditemukan',"data"=>array());
          }
        }

        $this->response($msg);
  }


}
