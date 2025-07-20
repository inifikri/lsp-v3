<?php
/*******************************************
    Export Excel dengan PHPExcel
 
    Dibuat oleh : Danni Moring
    pemrograman : PHP
******************************************/

//session_start();

include "../config/koneksi.php";
include "PHPExcel/PHPExcel.php";

date_default_timezone_set("Asia/Jakarta");

$excelku = new PHPExcel();

// Set properties
$excelku->getProperties()->setCreator("Silsp.online")
                         ->setLastModifiedBy("Dhega Febiharsa");

// Set lebar kolom
$excelku->getActiveSheet()->getColumnDimension('A')->setWidth(5);
$excelku->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$excelku->getActiveSheet()->getColumnDimension('C')->setWidth(15);
$excelku->getActiveSheet()->getColumnDimension('D')->setWidth(15);
$excelku->getActiveSheet()->getColumnDimension('E')->setWidth(15);
$excelku->getActiveSheet()->getColumnDimension('F')->setWidth(15);
$excelku->getActiveSheet()->getColumnDimension('G')->setWidth(15);
$excelku->getActiveSheet()->getColumnDimension('H')->setWidth(15);
$excelku->getActiveSheet()->getColumnDimension('I')->setWidth(15);
$excelku->getActiveSheet()->getColumnDimension('J')->setWidth(15);
$excelku->getActiveSheet()->getColumnDimension('K')->setWidth(15);
$excelku->getActiveSheet()->getColumnDimension('L')->setWidth(15);
$excelku->getActiveSheet()->getColumnDimension('M')->setWidth(15);
$excelku->getActiveSheet()->getColumnDimension('N')->setWidth(15);
$excelku->getActiveSheet()->getColumnDimension('O')->setWidth(15);
$excelku->getActiveSheet()->getColumnDimension('P')->setWidth(15);
$excelku->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
$excelku->getActiveSheet()->getColumnDimension('R')->setWidth(15);
$excelku->getActiveSheet()->getColumnDimension('S')->setWidth(15);

// Mergecell, menyatukan beberapa kolom
//$excelku->getActiveSheet()->mergeCells('A1 : D1');
//$excelku->getActiveSheet()->mergeCells('A2 : D2');

// Buat Kolom judul tabel
$SI = $excelku->setActiveSheetIndex(0);
$SI->setCellValue('A1', 'No'); //Kolom No
$SI->setCellValue('B1', 'NAMA ASESI'); //Kolom Nama Asesi
$SI->setCellValue('C1', 'NIK'); //Kolom No. KTP
$SI->setCellValue('D1', 'TEMPAT LAHIR'); //Kolom Tempat Lahir
$SI->setCellValue('E1', 'TANGGAL LAHIR (dd/mm/yyyy)'); //Kolom Tanggal Lahir
$SI->setCellValue('F1', 'JENIS KELAMIN (L/P)'); //Kolom Jenis Kelamin
$SI->setCellValue('G1', 'TEMPAT TINGGAL'); //Kolom Tempat Tinggal
$SI->setCellValue('H1', 'KODE KOTA'); //Kolom Kode Kota
$SI->setCellValue('I1', 'KODE PROVINSI'); //Kolom Kode Provinsi
$SI->setCellValue('J1', 'TELP'); //Kolom Telp
$SI->setCellValue('K1', 'EMAIL'); //Kolom Email
$SI->setCellValue('L1', 'KODE PENDIDIKAN'); //Kolom Kode Pendidikan
$SI->setCellValue('M1', 'KODE PEKERJAAN'); //Kolom Pekerjaan
$SI->setCellValue('N1', 'KODE JADWAL'); //Kolom Kode Jadwal
$SI->setCellValue('O1', 'TANGGAL UJI (hh/bb/yyyy)'); //Kolom Tanggal Uji
$SI->setCellValue('P1', 'NOMOR REGISTRASI ASESOR'); //Kolom Nomor Registrasi Asesor
$SI->setCellValue('Q1', 'KODE SUMBER ANGGARAN'); //Kolom Sumber Anggaran
$SI->setCellValue('R1', 'KODE KEMENTERIAN'); //Kolom Kode Kementerian
$SI->setCellValue('S1', 'K/BK'); //Kolom K/BK

//Mengeset Syle nya
$headerStylenya = new PHPExcel_Style();
$bodyStylenya   = new PHPExcel_Style();

/*$headerStylenya->applyFromArray(
	array('fill' 	=> array(
		  'type'    => PHPExcel_Style_Fill::FILL_SOLID,
		  'color'   => array('argb' => 'FFEEEEEE')),
		  'borders' => array('bottom'=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
						'left'	    => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'top'	    => array('style' => PHPExcel_Style_Border::BORDER_THIN)
		  )
	));
	
$bodyStylenya->applyFromArray(
	array('fill' 	=> array(
		  'type'	=> PHPExcel_Style_Fill::FILL_SOLID,
		  'color'	=> array('argb' => 'FFFFFFFF')),
		  'borders' => array(
						'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
						'left'	    => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'top'	    => array('style' => PHPExcel_Style_Border::BORDER_THIN)
		  )
    ));

//Menggunakan HeaderStylenya
$excelku->getActiveSheet()->setSharedStyle($headerStylenya, "A1 : S1"); */

// Mengambil data dari tabel
$strsql	= "SELECT * from `asesi_asesmen` WHERE `id_jadwal`='$_GET[idj]'";
$res    = $conn->query($strsql);
$baris  = 2; //Ini untuk dimulai baris datanya, karena di baris 3 itu digunakan untuk header tabel
$no     = 1;
while ($row = $res->fetch_assoc()) {
	$sqlgetdataasesi="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$row[id_asesi]'";
	$getdataasesi=$conn->query($sqlgetdataasesi);
	$dtas=$getdataasesi->fetch_assoc();
	$sqlgetdataskema="SELECT * FROM `skema_kkni` WHERE `id`='$row[id_skemakkni]'";
	$getdataskema=$conn->query($sqlgetdataskema);
	$dtskm=$getdataskema->fetch_assoc();

	$sqlgetdatajadwal="SELECT * FROM `jadwal_asesmen` WHERE `id`='$row[id_jadwal]'";
	$getdatajadwal=$conn->query($sqlgetdatajadwal);
	$dtjadwal=$getdatajadwal->fetch_assoc();

	$sqlgetdatatuk="SELECT * FROM `tuk` WHERE `id`='$dtjadwal[tempat_asesmen]'";
	$getdatatuk=$conn->query($sqlgetdatatuk);
	$dttuk=$getdatatuk->fetch_assoc();
	
	$asesornya1='';

	$getasesor=$conn->query("SELECT * FROM `jadwal_asesor` WHERE `id_jadwal`='$_GET[idj]'");
	$jumasesor=$getasesor->num_rows;
	if ($jumasesor>1){
		while ($gas=$getasesor->fetch_assoc()){
			$sqlasesor="SELECT * FROM `asesor` WHERE `id`='$gas[id_asesor]'";
			$asesor=$conn->query($sqlasesor);
			$asr=$asesor->fetch_assoc();
			$namaasesor=$asr['no_induk'];
			$asesornya=$namaasesor.", ";
			$asesornya1=$asesornya1.$asesornya;
		}
	}else{
		$gas=$getasesor->fetch_assoc();
		$sqlasesor="SELECT * FROM `asesor` WHERE `id`='$gas[id_asesor]'";
		$asesor=$conn->query($sqlasesor);
		$asr=$asesor->fetch_assoc();
		$namaasesor=$asr['no_induk'];
		$asesornya1=$namaasesor;
	}
	$noktpnya=$dtas['no_ktp'];
	$tanggallahirnya=substr($dtas['tgl_lahir'],8,2)."/".substr($dtas['tgl_lahir'],5,2)."/".substr($dtas['tgl_lahir'],0,4);
	$tanggalujinya=substr($row['tgl_asesmen'],8,2)."/".substr($row['tgl_asesmen'],5,2)."/".substr($row['tgl_asesmen'],0,4);

	$SI->setCellValue("A".$baris,$no++); //mengisi data untuk nomor urut
	$SI->setCellValue("B".$baris,$dtas['nama']); //mengisi data untuk nama
	$SI->setCellValueExplicit("C".$baris,$noktpnya,PHPExcel_Cell_DataType::TYPE_STRING); //mengisi data untuk no ktp/nik
	$SI->setCellValue("D".$baris,$dtas['tmp_lahir']); //mengisi data untuk tempat lahir
	$SI->setCellValue("E".$baris,$tanggallahirnya); //mengisi data untuk tanggal lahir
	$SI->setCellValue("F".$baris,$dtas['jenis_kelamin']); //mengisi data untuk jenis kelamin
	$SI->setCellValue("G".$baris,$dtas['alamat']); //mengisi data untuk alamat
	$SI->setCellValue("H".$baris,$dtas['kota']); //mengisi data untuk kota
	$SI->setCellValue("I".$baris,$dtas['propinsi']); //mengisi data untuk propinsi
	$SI->setCellValue("J".$baris,$dtas['nohp']); //mengisi data untuk no HP
	$SI->setCellValue("K".$baris,$dtas['email']); //mengisi data untuk email
	$sqlgetpdd="SELECT * FROM `pendidikan` WHERE `id`='$dtas[pendidikan]'";
	$getpdd=$conn->query($sqlgetpdd);
	$gpdd=$getpdd->fetch_assoc();
	$SI->setCellValue("L".$baris,$gpdd['kodependidikan_bnsp']); //mengisi data untuk pendidikan
	$SI->setCellValue("M".$baris,$dtas['pekerjaan']); //mengisi data untuk pekerjaan
	$SI->setCellValue("N".$baris,$dtjadwal['kodejadwal_bnsp']); //mengisi data untuk kode jadwal BNSP
	$SI->setCellValue("O".$baris,$tanggalujinya); //mengisi data untuk tanggal asesmen
	$SI->setCellValue("P".$baris,$asesornya1); //mengisi data untuk asesor
	$SI->setCellValue("Q".$baris,$dtjadwal['sumber_anggaran']); //mengisi data untuk sumber anggaran
	$SI->setCellValue("R".$baris,$dtjadwal['pemberi_anggaran']); //mengisi data untuk pemberi anggaran
	$SI->setCellValue("S".$baris,$row['status_asesmen']); //mengisi data untuk status asesmen
	$baris++; //looping untuk barisnya
}
//Membuat garis di body tabel (isi data)
//$excelku->getActiveSheet()->setSharedStyle($bodyStylenya, "A4 : D$baris");

//Memberi nama sheet
$excelku->getActiveSheet()->setTitle('Data Asesmen');

$excelku->setActiveSheetIndex(0);

// untuk excel 2007 atau yang berekstensi .xlsx
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename=data-asesmen.xlsx');
header('Cache-Control: max-age=0');
 
$objWriter = PHPExcel_IOFactory::createWriter($excelku, 'Excel2007');
ob_end_clean();
$objWriter->save('php://output');
unset($excelku);
?>