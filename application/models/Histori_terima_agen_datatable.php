<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class Histori_terima_agen_datatable extends CI_Model
{
      var $table = "trans_agen";
      var $select_column = array("*");
      var $order_column = array('created_at','biaya');
      function make_query($term=''){ //term is value of $_REQUEST['search']['value']
          $column = array('tc.*','c.nama_lengkap','a.nama_lengkap');
          $this->db->select('tc.*','c.nama_lengkap','a.nama_lengkap');
          $this->db->from('trans_customer as tc');
          $this->db->join('customer as c', 'c.id_customer = tc.id_customer','left');
          $this->db->join('agen as a', 'a.id_agen = tc.id_agen','left');
          $this->db->where('tc.jenis_transaksi', "Bayar Kepada");
          //group start for where + like together
          $this->db->group_start();
          $this->db->or_like('c.nama_lengkap', $term);
          $this->db->or_like('a.nama_lengkap', $term);
          $this->db->or_like('tc.kode_booking', $term);
          $this->db->group_end();
          if(isset($_REQUEST['order'])) // here order processing
          {
             $this->db->order_by($column[$_REQUEST['order']['0']['column']], $_REQUEST['order']['0']['dir']);
          } 
          else if(isset($this->order))
          {
             $order = $this->order;
             $this->db->order_by(key($order), $order[key($order)]);
          }else{
             $this->db->order_by("tc.id_trans_customer", "DESC");
          }
      }
      function make_datatables(){
        $term = $_REQUEST['search']['value'];   
        $this->make_query($term);
        if($_REQUEST['length'] != -1)
        $this->db->limit($_REQUEST['length'], $_REQUEST['start']);
        $query = $this->db->get();
        return $query->result();
      }
      function get_filtered_data(){
        $term = $_REQUEST['search']['value']; 
        $this->make_query($term);
        $query = $this->db->get();
        return $query->num_rows(); 
      }
      function get_all_data()
      {
        $this->db->from($this->table);
        return $this->db->count_all_results();
      }
  }

?>
