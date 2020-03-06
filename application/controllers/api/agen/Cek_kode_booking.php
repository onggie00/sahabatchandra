<?php
use Restserver\Libraries\REST_Controller;
header('Access-Control-Allow-Origin: *');
date_default_timezone_set('Asia/Jakarta');
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH.'libraries/Format.php';

class Cek_kode_booking extends REST_Controller {

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
          $ceklogin = $this->mymodel->getbywhere("agen","token",$token,'row');
          $data = $this->mymodel->getbywhere('trans_customer','kode_booking',$kode_booking,'row');
          if (empty($data)) {
            $msg = array('success'=>0,'message'=>'Kode Booking tidak ditemukan',"data"=>new \stdClass());
          }else{
            $convert = date("d m Y N",strtotime($data->created_at));
            $convert = explode(" ", $convert);
            $tgl = $convert[0];
            $bln = $this->get_bulan($convert[1]);
            $thn = $convert[2];
            $hari = $this->get_hari($convert[3]);
            $data->created_at = $tgl." ".$bln." ".$thn." ".date("H:i",strtotime($data->created_at));
            $data->kode_qr = base_url('assets/image/trans_customer/'.$data->kode_qr);
            $data->total_kirim = "HKD ".number_format($data->total_kirim,0,"",".");
            $data->biaya_remitansi = "HKD ".number_format($data->biaya_remitansi,0,"",".");
            $data->total_bayar = "HKD ".number_format($data->total_bayar,0,"",".");
            $data->total_idr = "IDR ".number_format($data->total_idr,0,"",".");
            $data->biaya_admin = "IDR ".number_format($data->biaya_admin,0,"",".");
            $data->total_diterima = "IDR ".number_format($data->total_diterima,0,"",".");
            $data->nama_bank = $this->mymodel->getbywhere('bank',"id_bank",$data->id_bank,'row')->nama_bank;
            $data->provinsi = $this->mymodel->getbywhere('provinsi',"id",$data->id_provinsi,"row")->name;
            $data->kabupaten = $this->mymodel->getbywhere('kabupaten','id',$data->id_kabupaten,'row')->name;

            if (!empty($data)) {
              $msg = array('success'=>1,'message'=>'Berhasil Ambil Data',"data"=>$data);
            }else{
              $msg = array('success'=>0,'message'=>'Data tidak ditemukan',"data"=>new \stdClass());
            }
          }
        }else if(empty($kode_booking)){
          $msg = array('success'=>0,'message'=>'Kode Booking tidak boleh kosong',"data"=>new \stdClass());
        }
        else{
          $msg = array('success'=>0,'message'=>'Token tidak ditemukan',"data"=>new \stdClass());
        }

        $this->response($msg);
  }


}
