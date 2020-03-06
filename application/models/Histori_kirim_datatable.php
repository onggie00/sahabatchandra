<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class Histori_kirim_datatable extends CI_Model
{
      var $table = "trans_customer";
      var $select_column = array("*");
      var $order_column = array('created_at','biaya');
      function make_query($term=''){ //term is value of $_REQUEST['search']['value']
          $column = array('tc.*','b.nama_bank','p.name','k.name');
          $this->db->select('tc.*','b.nama_bank','p.name','k.name');
          $this->db->from('trans_customer as tc');
          $this->db->join('bank as b', 'tc.id_bank = b.id_bank','left');
          $this->db->join('provinsi as p', 'tc.id_provinsi = p.id','left');
          $this->db->join('kabupaten as k', 'tc.id_kabupaten = k.id','left');
          $this->db->where('tc.jenis_transaksi', "Kirim Uang");
          //group start for where + like together
          $this->db->group_start();
          $this->db->like('tc.kode_booking', $term);
          $this->db->or_like('tc.nama_tujuan', $term);
          $this->db->or_like('b.nama_bank', $term);
          $this->db->or_like('tc.notelp_tujuan', $term);
          $this->db->or_like('tc.alamat_tujuan', $term);
          $this->db->or_like('k.name', $term);
          $this->db->or_like('p.name', $term);
          $this->db->or_like('tc.sumber_dana', $term);
          $this->db->or_like('tc.keperluan', $term);
          $this->db->or_like('tc.total_kirim', $term);
          $this->db->or_like('tc.biaya_remitansi', $term);
          $this->db->or_like('tc.total_bayar', $term);
          $this->db->or_like('tc.total_idr', $term);
          $this->db->or_like('tc.biaya_admin', $term);
          $this->db->or_like('tc.total_diterima', $term);
          $this->db->or_like('tc.jenis_transaksi', $term);
          $this->db->or_like('tc.created_at', $term);

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
