<?php

date_default_timezone_set('Asia/Jakarta');
defined('BASEPATH') OR exit('No direct script access allowed');
// Load library phpspreadsheet
require('./excel/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style;
// End load library phpspreadsheet

class Histori_kirim extends CI_Controller {
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
  public function index($val = "")
  {
    $this->is_login();
    $msg = $this->session->flashdata('msg');
    $this->session->set_flashdata('msg',$msg);
    $data['title_page'] = "Histori Pengiriman Uang Customer";
    $data['nama_lengkap'] = $this->mymodel->getbywhere('admin','username',$this->session->userdata('admin'),"row")->nama_lengkap;
    $data['err_msg'] =  $this->session->flashdata('msg');
    $data['success_msg'] =  $this->session->flashdata('success_msg');
    $this->load->view('admin/header', $data);
    $this->load->view('admin/histori_kirim');
    $this->load->view('admin/footer');
  }
  public function alldata()
  {
    $this->load->model(array('Histori_kirim_datatable'));
    $fetch_data = $this->Histori_kirim_datatable->make_datatables();
           $data = array();
           $nomor=1;
           foreach($fetch_data as $value)
           {
            $sub_array = array();
            $sub_array[] = $nomor;
            $sub_array[] = $value->kode_booking;
            $sub_array[] = $value->nama_tujuan;
            $sub_array[] = $value->norek_tujuan;
            $sub_array[] = $this->mymodel->getbywhere('bank','id_bank',$value->id_bank,'row')->nama_bank;
            $sub_array[] = $value->notelp_tujuan;
            $sub_array[] = $value->alamat_tujuan;
            $sub_array[] = $this->mymodel->getbywhere('kabupaten','id',$value->id_kabupaten,'row')->name;
            $sub_array[] = $this->mymodel->getbywhere('provinsi','id',$value->id_provinsi,'row')->name;
            $sub_array[] = $value->sumber_dana;
            $sub_array[] = $value->keperluan;
            $sub_array[] = $value->total_kirim;
            $sub_array[] = $value->biaya_remitansi;
            $sub_array[] = $value->total_bayar;
            $sub_array[] = $value->total_idr;
            $sub_array[] = $value->biaya_admin;
            $sub_array[] = $value->total_diterima;
            $sub_array[] = $value->jenis_transaksi;
            $convert = date("d m Y N",strtotime($value->created_at));
            $convert = explode(" ", $convert);
            $tgl = $convert[0];
            $bln = $this->get_bulan($convert[1]);
            $thn = $convert[2];
            $hari = $this->get_hari($convert[3]);
            $sub_array[] = $tgl." ".$bln." ".$thn;

                $data[] = $sub_array;
                $nomor++;
           }
           $output = array(
                "draw"                    =>    intval($_POST["draw"]) ,
                "recordsTotal"          =>      $this->Histori_kirim_datatable->get_all_data(),
                "recordsFiltered"     =>     $this->Histori_kirim_datatable->get_filtered_data(),
                "data"                    =>     $data
           );
           echo json_encode($output);
  }

    // Export ke excel
  
  
  public function is_login()
  {
    $about_img = $this->session->userdata('admin');
    if ($about_img=="") {
      redirect('admin/login/');
    }
  }
}
?>
