<?php

date_default_timezone_set('Asia/Jakarta');
defined('BASEPATH') OR exit('No direct script access allowed');


class Histori_terima_collector extends CI_Controller {
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
    $data['title_page'] = "Histori Penerimaan Uang Collector";
    $data['nama_lengkap'] = $this->mymodel->getbywhere('admin','username',$this->session->userdata('admin'),"row")->nama_lengkap;
    $data['err_msg'] =  $this->session->flashdata('msg');
    $data['success_msg'] =  $this->session->flashdata('success_msg');
    $this->load->view('admin/header', $data);
    $this->load->view('admin/histori_terima_collector');
    $this->load->view('admin/footer');
  }
  public function alldata()
  {
    $this->load->model(array('Histori_terima_collector_datatable'));
    $fetch_data = $this->Histori_terima_collector_datatable->make_datatables();
           $data = array();
           $nomor=1;
           foreach($fetch_data as $value)
           {
            $sub_array = array();
            $sub_array[] = $nomor;
            $sub_array[] = $value->kode_booking;
            $sub_array[] = $this->mymodel->getbywhere('agen','id_agen',$value->id_agen,'row')->nama_lengkap;
            $sub_array[] = $this->mymodel->getbywhere('collector','id_collector',$value->id_collector,'row')->nama_lengkap;
            $sub_array[] = "Diterima Oleh Collector";

                $data[] = $sub_array;
                $nomor++;
           }
           $output = array(
                "draw"                    =>    intval($_POST["draw"]) ,
                "recordsTotal"          =>      $this->Histori_terima_collector_datatable->get_all_data(),
                "recordsFiltered"     =>     $this->Histori_terima_collector_datatable->get_filtered_data(),
                "data"                    =>     $data
           );
           echo json_encode($output);
  }
  
  public function is_login()
  {
    $about_img = $this->session->userdata('admin');
    if ($about_img=="") {
      redirect('admin/login/');
    }
  }
}
?>
