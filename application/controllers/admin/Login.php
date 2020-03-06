<?php

date_default_timezone_set('Asia/Jakarta');
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function index()
	{
		 $user = $this->session->userdata('admin');
 	    if ($user!="") {
	      redirect('admin/user/');
	    }
  			$data['err_msg'] = 	$this->session->flashdata('msg');
  	 	  $this->load->view('admin/login',$data);
	}
	public function do_login()
  {
    $date = date('Y-m-d H:i:s');
    $cek = $this->admin->checkusername($_REQUEST['username']);
    if ($cek!="") {
      $login = $this->admin->do_login($_REQUEST['username'],$_REQUEST['password']);
      if ($login!="") {
        $this->session->set_userdata('admin',$_REQUEST['username']);
        redirect('admin/login');
      }
      else {
        echo "err2";
        $this->session->set_flashdata('msg','Password Salah');
        redirect('admin/login');
      }
    }else {
      echo "err1";
      $this->session->set_flashdata('msg','Username Tidak Ditemukan');
      redirect('admin/login');
    }
  }

  public function logout($value='')
  {
    $this->session->unset_userdata('admin');
    redirect('admin/login');
  }
}
?>
