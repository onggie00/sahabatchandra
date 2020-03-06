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

class Exportexcel extends CI_Controller {
  function __construct() {
    parent::__construct();
    $this->load->library('Fpdf_gen');
  }
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
    //bulan lalu   
    //$datas = $this->mymodel->withquery("select * from trans_customer where year(created_at) = year(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(created_at) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)",'result');
    
    $periode_awal = date("Y-m-d",strtotime($this->input->post('periode_awal')));
    $periode_akhir = date("Y-m-d",strtotime($this->input->post('periode_akhir')));
    $periode_awal_lengkap = date("d F Y",strtotime($this->input->post('periode_awal')));
    $periode_akhir_lengkap = date("d F Y",strtotime($this->input->post('periode_akhir')));
    if (!empty($this->input->post('btn_bi'))) {
      //bulan ini
      //$datas = $this->mymodel->withquery("select * from trans_customer where jenis_transaksi ='Kirim Uang' and year(created_at) = ".$periode_tahun." AND MONTH(created_at) = ".$periode_bulan,'result');
      //$datas = $this->mymodel->withquery("select * from trans_customer where jenis_transaksi ='Kirim Uang' and year(created_at) = year(CURRENT_DATE) AND MONTH(created_at) = MONTH(CURRENT_DATE)",'result');
      $datas = $this->mymodel->withquery("select * from trans_customer where jenis_transaksi ='Kirim Uang' and (date(created_at) >='".$periode_awal."' and date(created_at) <='".$periode_akhir."') ",'result');
      //echo $this->db->last_query();
      $title="Laporan BI";


      // // Create new Spreadsheet object
      $spreadsheet = new Spreadsheet();
      //
      // // Set document properties
      $spreadsheet->getProperties()->setCreator('Admin SC')
      ->setLastModifiedBy('Admin SC')
      ->setTitle($title)
      ->setSubject('Laporan BI SC')
      ->setDescription('This Document is Auto Generated with Sahabat Chandra Admin System.')
      ->setKeywords('office Excel openxml php')
      ->setCategory('Monthly Report');
      //
      $spreadsheet->setActiveSheetIndex(0)
      ->setCellValue('D1', 'Lampiran Surat No. : 01/APIKKO/XI/2019')
      ->setCellValue('G1', 'Tanggal : '.date("d F Y"))
      ;
      
      $spreadsheet->setActiveSheetIndex(0)
      ->setCellValue('A3', 'Transaksi Penerimaan Uang dalam Wilayah Indonesia')
      ->setCellValue('A4', 'Nama Penyelenggara')
      ->setCellValue('B4', ': PT.APIKKO')
      ->setCellValue('A5', 'Bulan Pelaporan')
      ->setCellValue('B5', ": ".$periode_awal_lengkap." - ".$periode_akhir_lengkap)
      ;
      //       // Add some data
      $spreadsheet->setActiveSheetIndex(0)
      ->setCellValue('A10', 'NO')
      ->setCellValue('B10', 'NEGARA ASAL PENGIRIMAN')
      ->setCellValue('C10', 'KOTA/KABUPATEN TUJUAN PENGIRIMAN')
      ->setCellValue('D10', 'NAMA PENERIMA')
      ->setCellValue('E10', 'NAMA PENGIRIM')
      ->setCellValue('F10', 'FREKUENSI PENGIRIMAN')
      ->setCellValue('G10', 'TOTAL NILAI PENERIMAAN')
      ;
      foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
 
            $spreadsheet->setActiveSheetIndex($spreadsheet->getIndex($worksheet));
 
            $sheet = $spreadsheet->getActiveSheet();
            $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            foreach ($cellIterator as $cell) {
                $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
            }
        }
      // // Miscellaneous glyphs, UTF-8
      $i=11;$no=1; 
      foreach($datas as $data) {
      
      $kabupaten = $this->mymodel->getbywhere('kabupaten',"id",$data->id_kabupaten,'row')->name;
      //$provinsi = $this->mymodel->getbywhere('provinsi',"id",$data->id_provinsi,'row')->name;
      $kabupaten_bi = $this->mymodel->withquery("select * from kabupaten_bi where kabupaten_bi LIKE '%".$kabupaten."%'",'row')->kabupaten_bi;
      $pengirim = $this->mymodel->getbywhere("customer",'id_customer',$data->id_customer,'row')->nama_lengkap;
      $spreadsheet->setActiveSheetIndex(0)
      ->setCellValue('A'.$i, $no)
      ->setCellValue('B'.$i, "HK-Hong Kong")
      ->setCellValue('C'.$i, $kabupaten_bi)
      ->setCellValue('D'.$i, $data->nama_tujuan)
      ->setCellValue('E'.$i, $pengirim)
      ->setCellValue('F'.$i, "1")
      ->setCellValue('G'.$i, number_format($data->total_diterima,0,"","."))      
      ;
      $i++;$no++;
      }

      // // Rename worksheet
       $spreadsheet->getActiveSheet()->setTitle('Laporan BI ');
      //
      // // Set active sheet index to the first sheet, so Excel opens this as the first sheet
      $spreadsheet->setActiveSheetIndex(0);
      //
      // // Redirect output to a client’s web browser (Xlsx)
      $judul_file = "LAPORAN BI ".$periode_awal_lengkap." - ".$periode_akhir_lengkap;
      
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment;filename="'.$judul_file.'.xlsx"');
      header('Cache-Control: max-age=0');
      // // If you're serving to IE 9, then the following may be needed
      // header('Cache-Control: max-age=1');
      //
      
      // // If you're serving to IE over SSL, then the following may be needed
      header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
      header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
      header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
      header('Pragma: public'); // HTTP/1.0
      ob_end_clean();
      //
      $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
      $writer->save('php://output');
       exit;
      //die();

    }
    else if (!empty($this->input->post('btn_sipesat'))) {
      //bulan ini
      $datas = $this->mymodel->withquery("select * from trans_customer where jenis_transaksi ='Kirim Uang' and (date(created_at) >='".$periode_awal."' and date(created_at) <='".$periode_akhir."') ",'result');
      //$datas = $this->mymodel->withquery("select * from trans_customer where jenis_transaksi ='Kirim Uang' and year(created_at) = ".$periode_tahun." AND MONTH(created_at) = ".$periode_bulan,'result');
      //$datas = $this->mymodel->withquery("select * from trans_customer where jenis_transaksi ='Kirim Uang' and year(created_at) = year(CURRENT_DATE) AND MONTH(created_at) = MONTH(CURRENT_DATE)",'result');
      //echo $this->db->last_query();
      $title="Laporan SIPESAT ";
      $subtitle = "Transaksi Penerimaan Uang dalam Wilayah Indonesia";
      $nama_p = "Nama Penyelenggara : PT. APIKKO ";

      // // Create new Spreadsheet object
      $spreadsheet = new Spreadsheet();
      //
      // // Set document properties
      $spreadsheet->getProperties()->setCreator('Admin SC')
      ->setLastModifiedBy('Admin SC')
      ->setTitle($title)
      ->setSubject('Laporan SIPESAT')
      ->setDescription('This Document is Auto Generated with Sahabat Chandra Admin System.')
      ->setKeywords('office Excel openxml php')
      ->setCategory('Monthly Report');
      //

      $spreadsheet->setActiveSheetIndex(0)->mergeCells('A1:I1');
      $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal('center');
      $spreadsheet->setActiveSheetIndex(0)
      ->setCellValue('A1', 'PT APIKKO')
      ;
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('A2:I2');
      $spreadsheet->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal('center');
      $spreadsheet->setActiveSheetIndex(0)
      ->setCellValue('A2', 'SISTEM INFORMASI PENGGUNA JASA TERPADU (SIPESAT)')
      ;
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('A3:I3');
      $spreadsheet->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal('center');
      $spreadsheet->setActiveSheetIndex(0)
      ->setCellValue('A3', 'PENGIRIM')
      ;
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('A4:I4');
      $spreadsheet->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal('center');
      $spreadsheet->setActiveSheetIndex(0)
      ->setCellValue('A4', 'PERIODE : '.date("d-M-Y",strtotime($this->input->post('periode_awal')))." - ".date("d-M-Y",strtotime($this->input->post('periode_akhir'))))
      ;

      //       // Add some data
      $spreadsheet->setActiveSheetIndex(0)
      ->setCellValue('A6', 'ID-PJK')
      ->setCellValue('B6', 'KODE NASABAH')
      ->setCellValue('C6', 'NAMA NASABAH')
      ->setCellValue('D6', 'TEMPAT LAHIR')
      ->setCellValue('E6', 'TGL LAHIR')
      ->setCellValue('F6', 'ALAMAT')
      ->setCellValue('G6', 'NIK/NOMOR KTP')
      ->setCellValue('H6', 'NOMOR ID')
      ->setCellValue('I6', 'NOMOR CIF')
      ->setCellValue('J6', 'NOMOR NPWP')
      ;
      foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
 
            $spreadsheet->setActiveSheetIndex($spreadsheet->getIndex($worksheet));
 
            $sheet = $spreadsheet->getActiveSheet();
            $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            foreach ($cellIterator as $cell) {
                $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
            }
        }
      // // Miscellaneous glyphs, UTF-8
      $i=7;$no=1; 
      foreach($datas as $data) {
      
      $kabupaten = $this->mymodel->getbywhere('kabupaten',"id",$data->id_kabupaten,'row')->name;
      $provinsi = $this->mymodel->getbywhere('provinsi',"id",$data->id_provinsi,'row')->name;
      $pengirim = $this->mymodel->getbywhere("customer",'id_customer',$data->id_customer,'row');
      $spreadsheet->setActiveSheetIndex(0)
      ->setCellValue('A'.$i, $pengirim->id_customer)
      ->setCellValue('B'.$i, $pengirim->id_customer)
      ->setCellValue('C'.$i, $pengirim->nama_lengkap)
      ->setCellValue('D'.$i, $pengirim->tempat_lahir)
      ->setCellValue('E'.$i, date("d-m-Y",strtotime($pengirim->tanggal_lahir)))
      ->setCellValue('F'.$i, $pengirim->alamat)
      ->setCellValue('G'.$i, base_url("assets/image/customer/".$pengirim->img_file))
      ->setCellValue('H'.$i, $pengirim->id_number)
      ->setCellValue('I'.$i, $pengirim->id_customer)
      ->setCellValue('J'.$i, " ")
      ;
      $i++;$no++;
      }

      // // Rename worksheet
      $spreadsheet->getActiveSheet()->setTitle('Laporan SIPESAT ');
      $judul_file = "LAPORAN SIPESAT ".$periode_awal_lengkap." - ".$periode_akhir_lengkap;
      //
      // // Set active sheet index to the first sheet, so Excel opens this as the first sheet
      $spreadsheet->setActiveSheetIndex(0);
      //
      // // Redirect output to a client’s web browser (Xlsx)
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment;filename="'.$judul_file.'.xlsx"');
      header('Cache-Control: max-age=0');
      // // If you're serving to IE 9, then the following may be needed
      // header('Cache-Control: max-age=1');
      //
      // // If you're serving to IE over SSL, then the following may be needed
      header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
      header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
      header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
      header('Pragma: public'); // HTTP/1.0
      ob_end_clean();
      //
      $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
      $writer->save('php://output');
      // exit;
    }
    else if ($this->input->post('btn_ppatk')) {
      //bulan ini
      $datas = $this->mymodel->withquery("select * from trans_customer where jenis_transaksi ='Kirim Uang' and (date(created_at) >='".$periode_awal."' and date(created_at) <='".$periode_akhir."') ",'result');
      //$datas = $this->mymodel->withquery("select * from trans_customer where jenis_transaksi ='Kirim Uang' and year(created_at) = ".$periode_tahun." AND MONTH(created_at) = ".$periode_bulan,'result');
      //$datas = $this->mymodel->withquery("select * from trans_customer where jenis_transaksi ='Kirim Uang' and year(created_at) = year(CURRENT_DATE) AND MONTH(created_at) = MONTH(CURRENT_DATE)",'result');
      //echo $this->db->last_query();
      $title="Laporan SIPESAT ";
      $subtitle = "Transaksi Penerimaan Uang dalam Wilayah Indonesia";
      $nama_p = "Nama Penyelenggara : PT. APIKKO ";

      // // Create new Spreadsheet object
      $spreadsheet = new Spreadsheet();
      //
      // // Set document properties
      $spreadsheet->getProperties()->setCreator('Admin SC')
      ->setLastModifiedBy('Admin SC')
      ->setTitle($title)
      ->setSubject('Laporan PPATK')
      ->setDescription('This Document is Auto Generated with Sahabat Chandra Admin System.')
      ->setKeywords('office Excel openxml php')
      ->setCategory('Monthly Report');
      //

      $spreadsheet->setActiveSheetIndex(0)->mergeCells('A1:A4');
      $spreadsheet->getActiveSheet()->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('99CCFF');
      $spreadsheet->setActiveSheetIndex(0)
      ->setCellValue('A1', 'Local ID*')
      ;
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('B1:C2');
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('B3:B4');
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('C3:C4');
      $spreadsheet->getActiveSheet()->getStyle('B1:C3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('CC99FF');
      $spreadsheet->setActiveSheetIndex(0)
      ->setCellValue('B1', 'Umum')
      ->setCellValue('B3', 'No. LTKL Koreksi')
      ->setCellValue('C3', 'Jenis Laporan*')
      ;
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('D1:D4');
      $spreadsheet->getActiveSheet()->getStyle('D1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFCC00');
      $spreadsheet->setActiveSheetIndex(0)
      ->setCellValue('D1', 'PJK bertindak sebagai*')
      ;
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('E1:Y1');
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('E2:Q2');
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('E3:E4');
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('F3:F4');
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('G3:G4');
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('H3:H4');
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('I3:I4');
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('J3:J4');
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('K3:K4');
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('L3:L4');
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('R2:Y2');
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('M3:N3');
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('O3:Q3');
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('X3:Y3');
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('R3:R4');
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('S3:S4');
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('T3:T4');
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('U3:U4');
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('V3:V4');
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('W3:W4');

      $spreadsheet->setActiveSheetIndex(0)->mergeCells('Z1:BB1');
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('Z2:AR2');
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('AS2:BB2');
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('Z3:Z4');
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('AA3:AA4');
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('AB3:AB4');
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('AC3:AC4');
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('AD3:AD4');
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('AE3:AI3');
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('AJ3:AN3');
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('AP3:AR3');
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('AO3:AO4');
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('AS3:AS4');
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('AT3:AT4');
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('AU3:AU4');
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('AV3:AV4');
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('AW3:BA3');
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('BB3:BB4');

      $spreadsheet->setActiveSheetIndex(0)->mergeCells('BC1:BK1');
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('BC2:BC4');
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('BD2:BD4');
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('BE2:BE4');
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('BF2:BF4');
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('BG2:BG4');
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('BH2:BH4');
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('BI2:BI4');
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('BJ2:BJ4');
      $spreadsheet->setActiveSheetIndex(0)->mergeCells('BK2:BK4');

      $spreadsheet->getActiveSheet()->getStyle('E1:Y4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('00FF00');
      $spreadsheet->getActiveSheet()->getStyle('Z1:BB3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF8080');
      $spreadsheet->getActiveSheet()->getStyle('AE4:BA4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF8080');
      $spreadsheet->getActiveSheet()->getStyle('BC1:BK2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('99CCFF');
      $spreadsheet->setActiveSheetIndex(0)
      ->setCellValue('E1', 'Identitas Pengirim (Ordering Customer) ')
      ->setCellValue('E2', 'Perorangan ')
      ->setCellValue('R2', 'Korporasi ')
      ->setCellValue('E3', 'No Rek ')
      ->setCellValue('F3', 'Nama Bank ')
      ->setCellValue('G3', 'Nama Lengkap* ')
      ->setCellValue('H3', 'Tanggal Lahir ')
      ->setCellValue('I3', 'Negara Bagian/Kota ')
      ->setCellValue('J3', 'Negara* ')
      ->setCellValue('K3', 'Negara Lainnya ')
      ->setCellValue('L3', 'Apakah sumber dana tunai?* ')
      ->setCellValue('M3', 'Apabila sumber dana tunai ')
      ->setCellValue('M4', 'Alamat ')
      ->setCellValue('N4', 'No Telp ')
      ->setCellValue('O3', 'Bukti Identitas ')
      ->setCellValue('O4', 'Jenis ')
      ->setCellValue('P4', 'Identitas Lainnya ')
      ->setCellValue('Q4', 'No Identitas ')
      ->setCellValue('R3', 'No Rek ')
      ->setCellValue('S3', 'Nama Bank ')
      ->setCellValue('T3', 'Nama Korporasi* ')
      ->setCellValue('U3', 'Negara* ')
      ->setCellValue('V3', 'Negara Lainnya ')
      ->setCellValue('W3', 'Apakah sumber dana tunai?* ')
      ->setCellValue('X4', 'Alamat Korporasi ')
      ->setCellValue('Y4', 'No Telp ')
      ->setCellValue('Z1', 'Identitas Penerima (Beneficiary Customer)  ')
      ->setCellValue('Z2', 'Perorangan ')
      ->setCellValue('AS2', 'Korporasi ')
      ->setCellValue('Z3', 'Kode Rahasia Transaksi ')
      ->setCellValue('AA3', 'No Rek ')
      ->setCellValue('AB3', 'Nama Bank ')
      ->setCellValue('AC3', 'Nama Lengkap* ')
      ->setCellValue('AD3', 'Tanggal Lahir ')
      ->setCellValue('AE3', 'Alamat Domisili ')
      ->setCellValue('AJ3', 'Alamat Sesuai Bukti Identitas* ')
      ->setCellValue('AE4', 'Alamat ')
      ->setCellValue('AF4', 'Provinsi ')
      ->setCellValue('AG4', 'Provinsi Lainnya ')
      ->setCellValue('AH4', 'Kabupaten/Kota ')
      ->setCellValue('AI4', 'Kabupaten/Kota Lainnya ')
      ->setCellValue('AJ4', 'Alamat* ')
      ->setCellValue('AK4', 'Provinsi* ')
      ->setCellValue('AL4', 'Provinsi Lainnya ')
      ->setCellValue('AM4', 'Kabupaten/kota* ')
      ->setCellValue('AN4', 'Kabupaten/kota Lainnya ')
      ->setCellValue('AO3', 'No Telp ')
      ->setCellValue('AP4', 'Jenis* ')
      ->setCellValue('AQ4', 'Identitas Lainnya ')
      ->setCellValue('AR4', 'No Identitas* ')
      ->setCellValue('AS3', 'Kode Rahasia Transaksi ')
      ->setCellValue('AT3', 'No Rek ')
      ->setCellValue('AU3', 'Nama Bank ')
      ->setCellValue('AV3', 'Nama Korporasi ')
      ->setCellValue('AW3', 'Alamat Lengkap Korporasi* ')
      ->setCellValue('AW4', 'Alamat ')
      ->setCellValue('AX4', 'Provinsi* ')
      ->setCellValue('AY4', 'Provinsi Lainnya ')
      ->setCellValue('AZ4', 'Kabupaten/Kota ')
      ->setCellValue('BA4', 'Kabupaten/Kota Lainnya ')
      ->setCellValue('BB3', 'No Telp ')
      ->setCellValue('BC1', 'Transaksi ')
      ->setCellValue('BC2', 'Tanggal Transaksi* ')
      ->setCellValue('BD2', 'Tanggal efektif penyelesaian transfer LN* ')
      ->setCellValue('BE2', 'Mata Uang asal yang diinstruksikan ')
      ->setCellValue('BF2', 'Nilai uang asal yang diinstruksikan ')
      ->setCellValue('BG2', 'Kurs Nilai Tukar ')
      ->setCellValue('BH2', 'Mata Uang yang diterima* ')
      ->setCellValue('BI2', 'Nilai Uang yang diterima dalam Rupiah* ')
      ->setCellValue('BJ2', 'Tujuan transaksi ')
      ->setCellValue('BK2', 'Sumber penggunaan dana ')
      ;
//Setting border
      //$spreadsheet->getActiveSheet()->getStyle('B3')->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
      //$spreadsheet->getActiveSheet()->getStyle('E3:Y4')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
      //$spreadsheet->getActiveSheet()->getStyle('E3:Y4')->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
      //$spreadsheet->getActiveSheet()->getStyle('E3:Y4')->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);

      foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
 
            $spreadsheet->setActiveSheetIndex($spreadsheet->getIndex($worksheet));
 
            $sheet = $spreadsheet->getActiveSheet();
    //Set header outer borders
    $styleArray = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'right' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                'left' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
    $sheet->getStyle('A1:'. 
    $sheet->getHighestColumn().$sheet->getHighestRow())->applyFromArray($styleArray);

            $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            foreach ($cellIterator as $cell) {
                $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
                //$sheet->getStyle($cell->getColumn())->applyFromArray($styleArray);
            }
        }
      // // Miscellaneous glyphs, UTF-8
      $i=5;$no=1; 
      foreach($datas as $data) {
      
      //$kabupaten = $this->mymodel->getbywhere('kabupaten',"id",$data->id_kabupaten,'row')->name;
      //$provinsi = $this->mymodel->getbywhere('provinsi',"id",$data->id_provinsi,'row')->name;
      $pengirim = $this->mymodel->getbywhere("customer",'id_customer',$data->id_customer,'row');
      $bank = $this->mymodel->getbywhere('bank',"id_bank",$data->id_bank,'row');
      $cek_trf;
      if (!empty($this->mymodel->getbywhere("trans_agen",'id_trans_customer',$data->id_trans_customer,'row'))) {
        $cek_trf = $this->mymodel->getbywhere("trans_agen",'id_trans_customer',$data->id_trans_customer,'row');
      }
      $angka;
      if ($no<10) {
        $angka = "000".$no;
      }else if($no<100){
        $angka = "00".$no;
      }else if($no<1000){
        $angka = "0".$no;
      }
      $spreadsheet->setActiveSheetIndex(0)
      ->setCellValue('A'.$i, "APIKKO".date("ymd",strtotime($data->created_at)).$angka)
      ->setCellValue('B'.$i, "")
      ->setCellValue('C'.$i, "1")
      ->setCellValue('D'.$i, "1")
      ->setCellValue('G'.$i, $pengirim->nama_lengkap)
      ->setCellValue('H'.$i, date("d/m/Y",strtotime($pengirim->tanggal_lahir)))
      ->setCellValue('I'.$i, "HONG KONG")
      ->setCellValue('J'.$i, "40")
      ->setCellValue('L'.$i, "2")
      ->setCellValue('M'.$i, $pengirim->alamat)
      ->setCellValue('N'.$i, $pengirim->notelp)
      ->setCellValue('O'.$i, "1")
      ->setCellValue('Q'.$i, base_url("assets/image/customer/".$pengirim->img_file))
      ->setCellValue('AA'.$i, $data->norek_tujuan)
      ->setCellValue('AB'.$i, $bank->nama_bank)
      ->setCellValue('AC'.$i, $data->nama_tujuan)
      ->setCellValue('AE'.$i, $data->alamat_tujuan)
      ->setCellValue('AF'.$i, $data->id_provinsi)
      ->setCellValue('AH'.$i, $data->id_kabupaten)
      ->setCellValue('AJ'.$i, $data->alamat_tujuan)
      ->setCellValue('AK'.$i, $data->id_provinsi)
      ->setCellValue('AM'.$i, $data->id_kabupaten)
      ->setCellValue('AO'.$i, $data->notelp_tujuan)
      ->setCellValue('AP'.$i, "0")
      ->setCellValue('AQ'.$i, "Tidak Ada")
      ->setCellValue('AR'.$i, "NA")
      ->setCellValue('AT'.$i, "NA")
      ->setCellValue('AU'.$i, "Tidak Ada")
      ->setCellValue('AV'.$i, "A01")
      ->setCellValue('AX'.$i, "99")
      ->setCellValue('AZ'.$i, "9999")
      ->setCellValue('BC'.$i, date('d/m/Y',strtotime($data->created_at)))
      ->setCellValue('BE'.$i, "10")
      ->setCellValue('BF'.$i, "3600")
      ->setCellValue('BG'.$i, "1")
      ->setCellValue('BH'.$i, "10")
      ->setCellValue('BI'.$i, $data->total_diterima)
      ->setCellValue('BJ'.$i, $data->keperluan)
      ->setCellValue('BK'.$i, $data->sumber_dana)
      ;
      $i++;$no++;
      }

      // // Rename worksheet
      $spreadsheet->getActiveSheet()->setTitle('Laporan PPATK ');
      $judul_file = "LAPORAN PPATK ".$periode_awal_lengkap." - ".$periode_akhir_lengkap;
      //
      // // Set active sheet index to the first sheet, so Excel opens this as the first sheet
      $spreadsheet->setActiveSheetIndex(0);
      //
      // // Redirect output to a client’s web browser (Xlsx)
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment;filename="'.$judul_file.'.xlsx"');
      header('Cache-Control: max-age=0');
      // // If you're serving to IE 9, then the following may be needed
      // header('Cache-Control: max-age=1');
      //
      // // If you're serving to IE over SSL, then the following may be needed
      header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
      header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
      header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
      header('Pragma: public'); // HTTP/1.0
      ob_end_clean();
      //
      $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
      $writer->save('php://output');
      // exit;
    }
    else if ($this->input->post('btn_ptd')) {
      $datas = $this->mymodel->withquery("select * from trans_customer where jenis_transaksi ='Kirim Uang' and (date(created_at) >='".$periode_awal."' and date(created_at) <='".$periode_akhir."') ",'result');
      $hitung_kirim = $this->mymodel->withquery("select count(*) as total from trans_customer where jenis_transaksi ='Kirim Uang' and (date(created_at) >='".$periode_awal."' and date(created_at) <='".$periode_akhir."') ",'row');
      $hitung_penerimaan = $this->mymodel->withquery("select sum(total_setor) as total from trans_agen where jenis_transaksi ='Penerimaan Uang' and (date(created_at) >='".$periode_awal."' and date(created_at) <='".$periode_akhir."') ",'row');
        $pdf = new fpdf_helper('P','mm','A4');
        // membuat halaman baru
        $pdf->AliasNbPages();
        $pdf->AddPage();
        //header
        //$img_file = base_url()."assets/image/logo-black.png";
        // Arial bold 15
        // Logo
        //$pdf->Image($img_file,10,15,30);
        $pdf->SetFont('Arial','',12);
        // Move to the right
        $pdf->Ln(10);
        $pdf->Cell(0,5,'Penilaian Resiko PTD PT Apikko',0,1,'C');
        // Line break
        $pdf->Ln(1);
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(0,3,'Periode : '.date("d M Y",strtotime($this->input->post('periode_awal')))." - ".date("d M Y",strtotime($this->input->post('periode_akhir'))),0,1,'C');
        $pdf->Ln(2);

        //Line(padding-left,miring,ukuran garis, miring)
        $pdf->Line(10, 40, 210-10, 40);

        //Judul
        $pdf->Ln(10);

        //Data
        $pdf->Ln(5);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(50,5,'Total Frekuensi Pengiriman ',0,0);
        $pdf->Cell(10,5,':',0,0);
        $pdf->Cell(30,5,$hitung_kirim->total,0,0);
        $pdf->Cell(60,5,'Total Nilai Transaksi Penerimaan ',0,0);
        $pdf->Cell(10,5,':',0,0);
        $pdf->Cell(50,5,$hitung_penerimaan->total,0,1);

        $pdf->Ln(10);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(50,5,'Total Pengguna Jasa ',0,0);
        $pdf->Cell(10,5,':',0,1);

          $get_swasta = $this->mymodel->withquery("select count(*) as pegawai_swasta from customer where pekerjaan ='Pegawai Swasta' ",'row');
          $get_pengusaha = $this->mymodel->withquery("select count(*) as pengusaha from customer where pekerjaan ='Pengusaha' ",'row');
          $get_korporasi = $this->mymodel->withquery("select count(*) as korporasi from customer where pekerjaan ='Korporasi' ",'row');
          $get_irt = $this->mymodel->withquery("select count(*) as irt from customer where pekerjaan ='Ibu Rumah Tangga' ",'row');
          $get_yayasan = $this->mymodel->withquery("select count(*) as yayasan from customer where pekerjaan ='Yayasan' ",'row');
          $get_tki = $this->mymodel->withquery("select count(*) as tki from customer where pekerjaan ='TKI' ",'row');
          $get_lain = $this->mymodel->withquery("select count(*) as lain from customer where not pekerjaan = 'Pegawai Swasta' || pekerjaan = 'Pengusaha' || pekerjaan = 'Korporasi' || pekerjaan = 'Ibu Rumah Tangga' || pekerjaan = 'Yayasan' || pekerjaan = 'TKI' ",'row');
        
        $pdf->Ln(2);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(50,5,"Pegawai Swasta",0,0);
        $pdf->Cell(10,5,':',0,0);
        $pdf->Cell(30,5,$get_swasta->pegawai_swasta,0,1);
        $pdf->Ln(2);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(50,5,"Pengusaha",0,0);
        $pdf->Cell(10,5,':',0,0);
        $pdf->Cell(30,5,$get_pengusaha->pengusaha,0,1);
        $pdf->Ln(2);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(50,5,"Korporasi",0,0);
        $pdf->Cell(10,5,':',0,0);
        $pdf->Cell(30,5,$get_korporasi->korporasi,0,1);
        $pdf->Ln(2);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(50,5,"Ibu Rumah Tangga",0,0);
        $pdf->Cell(10,5,':',0,0);
        $pdf->Cell(30,5,$get_irt->irt,0,1);
        $pdf->Ln(2);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(50,5,"Yayasan",0,0);
        $pdf->Cell(10,5,':',0,0);
        $pdf->Cell(30,5,$get_yayasan->yayasan,0,1);
        $pdf->Ln(2);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(50,5,"TKI",0,0);
        $pdf->Cell(10,5,':',0,0);
        $pdf->Cell(30,5,$get_tki->tki,0,1);
        $pdf->Ln(2);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(50,5,"Lainnya",0,0);
        $pdf->Cell(10,5,':',0,0);
        $pdf->Cell(30,5,$get_lain->lain,0,1);

        $pdf->Ln(10);
        $get_wni = $this->mymodel->withquery("select count(*) as wni from customer where negara='Indonesia' ",'row');
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(50,5,"Total WNI",0,0);
        $pdf->Cell(10,5,':',0,0);
        $pdf->Cell(30,5,$get_wni->wni,0,1);
        
        $pdf->Output();
        ob_end_flush();  
    }
    //echo "test";
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
