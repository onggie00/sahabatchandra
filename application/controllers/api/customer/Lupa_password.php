<?php
use Restserver\Libraries\REST_Controller;
header('Access-Control-Allow-Origin: *');
date_default_timezone_set('Asia/Jakarta');
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH.'libraries/Format.php';
require APPPATH.'controllers/api/phpmailer/PHPMailerAutoload.php';
ob_start();

class Lupa_password extends REST_Controller {

  function __construct($config = 'rest') {
      parent::__construct($config);
  }

  function index_post() {
      $email = $this->post('email');
       if (isset($email)) {
         $cekemail = $this->mymodel->getbywhere('customer','email',$email,'row');
         if (isset($cekemail)) {
           $newpass = rand( 100000, 1000000);
           $dt = array('password' => md5($newpass) );
           $this->mymodel->update('customer',$dt,'token',$cekemail->token);
           //$this->send_email_file('',urlencode($email),$newpass);
           $this->send_email_file("",$cekemail->email,$newpass);
           $msg = array('status' => 1, 'message'=>'Password Berhasil di reset' );
         }else {
           $msg = array('status' => 0, 'message'=>'Email Tidak Terdaftar' );
         }
       }else {
         $msg = array('status' => 0, 'message'=>'Email tidak boleh kosong' );
       }
      $this->response($msg);

  }
  public function send_email_file($file="",$to='',$data)
    {
      $to = urldecode($to);
      $mail = new PHPMailer;
      // Konfigurasi SMTP
      $mail->isSMTP();
      $mail->SMTPDebug =0;
      // $mail->Host = 'mail.namagz.com';
      $mail->Host = 'smtp.gmail.com';
      $mail->SMTPOptions = array(
         'ssl' => array(
           'verify_peer' => false,
           'verify_peer_name' => false,
           'allow_self_signed' => true
          )
      );
      $mail->SMTPAuth = true;
      // $mail->Username = 'syauqi@namagz.com';
      // $mail->Password = 'koroko11';
      $mail->Username = 'dev.sahabatchandra@gmail.com';
      $mail->Password = 'bayuganteng2312';
      $mail->SMTPSecure = 'ssl';
      $mail->Port = 465;

      $mail->addReplyTo('no-reply@sahabatchandra.com', 'Sahabat Chandra');
      $mail->setFrom('no-reply@sahabatchandra.com', 'Sahabat Chandra');

      // Menambahkan penerima
      $mail->addAddress($to);

      // Menambahkan beberapa penerima


      // Subjek email
      $mail->Subject = '[No Reply] Reset Password Sahabat Chandra';

      // Mengatur format email ke HTML
      $mail->isHTML(true);

      // Konten/isi
       $data_['msg'] = "Selamat Password anda berhasil di reset, Password baru anda berada dibawah ini.";
       $data_['code'] = "$data";
       $data_['title'] = "Lupa Password";
       $mailContent = $this->load->view('email_lupas',$data_,true);
      $mail->Body = $mailContent;
      // Menambahakn lampiran

      // Kirim email
      if(!$mail->send()){
          echo 'Pesan tidak dapat dikirim.';
          echo 'Mailer Error: ' . $mail->ErrorInfo;
      }else{
          //echo 'Pesan telah terkirim ';
      }
    }


}
