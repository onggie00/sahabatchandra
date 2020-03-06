<?php

date_default_timezone_set('Asia/Jakarta');
defined('BASEPATH') OR exit('No direct script access allowed');


class Setting_biaya extends CI_Controller {

  public function index($val = "")
  {
    $this->is_login();
    $msg = $this->session->flashdata('msg');
    $this->session->set_flashdata('msg',$msg);
    $data['title_page'] = "Pengaturan Biaya";
    $data['nama_lengkap'] = $this->mymodel->getbywhere('admin','username',$this->session->userdata('admin'),"row")->nama_lengkap;
    $data['err_msg'] =  $this->session->flashdata('msg');
    $data['success_msg'] =  $this->session->flashdata('success_msg');
    $this->load->view('admin/header', $data);
    $this->load->view('admin/setting_biaya');
    $this->load->view('admin/footer');
  }

  public function administrasi(){
    $administrasi = $this->input->post('administrasi');

    $data = array(
      "biaya_admin" => $administrasi
      );
    $get_last = $this->mymodel->getlast('biaya','id_biaya');
    $up = $this->mymodel->update('biaya',$data,"id_biaya",$get_last->id_biaya);
    if ($up) {
      $this->session->set_flashdata('success_msg','Data Berhasil Diubah');
    }else{
      $this->session->set_flashdata('msg','Gagal Ubah Data');
    }
    redirect('admin/setting_biaya/');
  }

  public function remitansi(){
    $remitansi = $this->input->post('remitansi');

    $data = array(
      "biaya_remitansi" => $remitansi
      );
    $get_last = $this->mymodel->getlast('biaya','id_biaya');
    $up = $this->mymodel->update('biaya',$data,"id_biaya",$get_last->id_biaya);
    if ($up) {
      $this->session->set_flashdata('success_msg','Data Berhasil Diubah');
    }else{
      $this->session->set_flashdata('msg','Gagal Ubah Data');
    }
    redirect('admin/setting_biaya/');
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
