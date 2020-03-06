<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');
// Load library phpspreadsheet
require('./excel/vendor/autoload.php');
require_once('sms/api_sms_class_reguler_json.php');
ob_start();
define( 'API_ACCESS_KEY', 'AAAA54uLV8E:APA91bGtYTAJDz-Ku9DwEy9HfY4AFh3X6mCBf2n8hc7dTpK_ar5nc4QDT-sFXLBTALZVDOZE00cf3iRu3_2qGns7oPuW9kqi4qFAX9Di4Jvs_uvsLU_wgAkaIN87VI4HUGVFnhTQKfj5' );

class broadcast extends CI_Controller {
  public function __construct()
  {
    parent::__construct();
  }
  public function index()
  {
    $this->is_login();
    redirect('admin/broadcast/data');
  }
  public function is_login()
  {
    $broadcast = $this->session->userdata('admin');
    if ($broadcast=="") {
      redirect('admin/login/signin');
    }
  }
  
  public function alldata(){
    $this->load->model(array('User_aktif_datatable'));
    $fetch_data = $this->User_aktif_datatable->make_datatables();
    $data = array();
           foreach($fetch_data as $value)
           {
                $sub_array = array();
                $sub_array[] = $value->nama_depan;
                $sub_array[] = $value->nama_belakang;
                $sub_array[] = $value->nomer_kode;
                $data[] = $sub_array;
           }
           $output = array(
                "draw"                    =>    intval($_POST["draw"]) ,
                "recordsTotal"          =>      $this->User_aktif_datatable->get_all_data(),
                "recordsFiltered"     =>     $this->User_aktif_datatable->get_filtered_data(),
                "data"                    =>     $data
           );
           echo json_encode($output);
  }

  public function insert_data()
  {
    $data_broadcast  = array(
       "title" => $_REQUEST['title'],
       "description" => $_REQUEST['description'],
       "id_user"=> $_REQUEST['id_user'],
       "created_at" => date("Y-m-d H:i:s")
    );
    $in = $this->mymodel->insert('broadcast',$data_broadcast);
      if ($in) {
        $this->session->set_flashdata('msg','Broadcast Terkirm');
        $pesan = $_REQUEST['title'].'%0A'.$_REQUEST['description'];
        $user = $this->mymodel->getall('user');

        $this->notif_user($_REQUEST['title'],$_REQUEST['description']);
      }
      else {
        $this->session->set_flashdata('msg',"Broadcast gagal kirim");
      }
    
    redirect('admin/broadcast/data');
  }

  public function notif_user($title="",$description = "")
  {
    $date = date("Y-m-d H:i:s");
    $date_1 = date('Y-m-d H:i:s', strtotime('-1 hour'));
//    echo $this->send_user($title,$description,'fYENwNhXebo:APA91bF7PIj_P4LKxWhiPev0rMa2KZkJrBvZUqvmDBeQRZUjN1vSAgkg7E0QrZeJgnHUfTpFG1ZvISIeJlIFLjE9f-r2iuALQpmbfdoxjvGyfvUM20K2Vi902po4gIkH6LEObbOBgFB9','3348','Onggie','Danny','1650')."<br>";
//    echo "<hr>";
    foreach ($this->mymodel->getall('user') as $key => $value) {
      $cek_nomer = $this->mymodel->getbywhere('barcode','code',$value->barcode,'row')->nomer_kode;
      if ($value->id_fcm!='') { 
        if ($cek_nomer != "") {
          echo $this->send_user($title,$description,$value->id_fcm,$value->id_user,$value->nama_depan,$value->nama_belakang,$cek_nomer)."<br>";
          echo "<hr>";
        }
        else{
          $cek_nomer = 0;
        }
        
      }
    }

  }
  public function send_user($title,$desc,$id_fcm,$id_user,$nama_depan,$nama_belakang,$nomer)
  {
    $Msg = array(
      'body' => $desc,
      'title' => $title
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
      
    }
    else if($berhasil=='0'){
      
    }
    echo $result . "\n\n";
  }

  public function delete()
  {
    $in = $this->mymodel->getbywhere('broadcast','id_broadcast',$_REQUEST['id_data'],'row');
    if ($in->img!="custom.png") {
      unlink('./assets/img/broadcast/'.$in->img);
    }
    $in = $this->mymodel->delete('broadcast','id_broadcast',$_REQUEST['id_data']);
    $this->session->set_flashdata('msg','Data Berhasil Dihapus');
    if ($in) {
      echo "success";
        $this->session->set_flashdata('msg','Data Berhasil Dihapus');
    }else {
      echo $this->db->last_query();
    }
    redirect('admin/broadcast/data');
  }
  

  public function style_pagination()
  {
    $config['full_tag_open'] = '<nav><ul class="pagination" style="margin-top:0px">';
    $config['full_tag_close'] = '</ul></nav>';
    $config['first_link'] = '<span class="color-blue"><b>
    First</b></span>';
    $config['first_tag_open'] = '<li>';
    $config['first_tag_close'] = '</li>';
    $config['last_link'] ='<span class="color-blue"><b>
    Last</b></span>';
    $config['last_tag_open'] = '<li>';
    $config['last_tag_close'] = '</li>';
    $config['next_link'] = '<span class="color-blue"><b>
    Next</b></span>';
    $config['next_tag_open'] = '<li class="color-blue">';
    $config['next_tag_close'] = '</li>';
    $config['prev_link'] = '<span class="color-blue"><b>
    Prev</b></span>';
    $config['prev_tag_open'] = '<li>';
    $config['prev_tag_close'] = '</li>';
    $config['cur_tag_open'] = '<li class="active"><a class="background-blue">';
    $config['cur_tag_close'] = '</a></li>';
    $config['num_tag_open'] = '<li>';
    $config['num_tag_close'] = '</li>';
    $this->pagination->initialize($config);
  }
  public function data($start=0)
  {
    $this->is_login();
    $end=10;
    $datahd['tittle'] = 'Data broadcast';
    $datahd['menu'] = 18;
    $data['dataku'] = "";
    $this->load->view('admin/header',$datahd);
    $this->load->view('admin/data_broadcast',$data);
  }

  function pagination($page)
  {
   header('Access-Control-Allow-Origin: *');
   $end=10;
   $this->load->library("pagination");
   $config = array();
   $config["base_url"] = "#";
   $config["per_page"] = $end;
   $config["uri_segment"] = 4;
   $config["use_page_numbers"] = TRUE;
   $config['total_rows'] = count($this->mymodel->getall('broadcast'));
   $config["num_links"] = 1;
   $this->style_pagination();
   $this->pagination->initialize($config);
   $start = ($page - 1) * $config["per_page"];
   $data['page'] = $start;
   $data['dataku'] = $this->mymodel->getalllimitdesc('broadcast',$start,$end,'id_broadcast');
   $output= array();
   $output['data_admin'] = $this->load->view('admin/table_data/table_broadcast',$data,true);
   $output['pagination_link'] = $this->pagination->create_links();
   echo json_encode($output);
  }
}
