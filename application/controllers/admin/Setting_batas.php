<?php

date_default_timezone_set('Asia/Jakarta');
defined('BASEPATH') OR exit('No direct script access allowed');


class Setting_batas extends CI_Controller {

  public function index($val = "")
  {
    $this->is_login();
    $msg = $this->session->flashdata('msg');
    $this->session->set_flashdata('msg',$msg);
    $data['title_page'] = "Pengaturan Batas Pengiriman";
    $data['nama_lengkap'] = $this->mymodel->getbywhere('admin','username',$this->session->userdata('admin'),"row")->nama_lengkap;
    $data['err_msg'] =  $this->session->flashdata('msg');
    $data['success_msg'] =  $this->session->flashdata('success_msg');
    $this->load->view('admin/header', $data);
    $this->load->view('admin/setting_batas');
    $this->load->view('admin/footer');
  }

  public function updatedata()
  {

  if(isset($_REQUEST['btn_simpan'])){
      $data = array(
      "batas_terima_uang" => $_REQUEST['batas_uang']
      );
      $this->mymodel->update("biaya",$data,'id_biaya',$_REQUEST['data_id']);
      $this->session->set_flashdata('success_msg','Data Berhasil Diubah');
  }
    //echo $this->db->last_query();
   redirect('admin/setting_batas/');
  }

  public function getdataedit()
  {
    $id = $_REQUEST['id'];
    $data["data"] = $this->mymodel->getbywhere('biaya','id_biaya',$id,"row");
    $this->load->view('admin/modal_edit/setting_batas',$data);
  }
  public function bulanan(){
    $bulanan = $this->input->post('bulanan');

    $data = array(
      "batas_perbulan" => $bulanan
      );
    $get_last = $this->mymodel->getlast('biaya','id_biaya');
    $up = $this->mymodel->update('biaya',$data,"id_biaya",$get_last->id_biaya);
    if ($up) {
      $this->session->set_flashdata('success_msg','Data Berhasil Diubah');
    }else{
      $this->session->set_flashdata('msg','Gagal Ubah Data');
    }
    redirect('admin/setting_batas/');
  }

  public function transaksi(){
    $transaksi = $this->input->post('transaksi');

    $data = array(
      "batas_kirim_uang" => $transaksi
      );
    $get_last = $this->mymodel->getlast('biaya','id_biaya');
    $up = $this->mymodel->update('biaya',$data,"id_biaya",$get_last->id_biaya);
    if ($up) {
      $this->session->set_flashdata('success_msg','Data Berhasil Diubah');
    }else{
      $this->session->set_flashdata('msg','Gagal Ubah Data');
    }
    redirect('admin/setting_batas/');
  }

  public function deposit(){
    $deposit = $this->input->post('deposit');

    $data = array(
      "deposit" => $deposit
      );
    $get_last = $this->mymodel->getlast('biaya','id_biaya');
    $up = $this->mymodel->update('biaya',$data,"id_biaya",$get_last->id_biaya);
    if ($up) {
      $this->session->set_flashdata('success_msg','Data Berhasil Diubah');
    }else{
      $this->session->set_flashdata('msg','Gagal Ubah Data');
    }
    redirect('admin/setting_batas/');
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
