<?php

date_default_timezone_set('Asia/Jakarta');
defined('BASEPATH') OR exit('No direct script access allowed');


class Dashboard extends CI_Controller {
	public function index()
  {
    $this->is_login();

		

		$data['title_page'] = "Dashboard Administrator";
    $data['nama_lengkap'] = $this->mymodel->getbywhere('admin','username',$this->session->userdata('admin'),"row")->nama_lengkap;
    //Check previleges
    //$this->load->view('admin/header',$data);
		$this->load->view('admin/dashboard');
		//$this->load->view('admin/footer');
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
