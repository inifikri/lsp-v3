<?php
include "../config/koneksi.php";
include "PHPExcel/PHPExcel.php";
 
$objPHPExcel    =   new PHPExcel();

$result         =   $conn->query("SELECT * FROM `asesi` ORDER BY `nama` ASC") or die(mysql_error());
 
$objPHPExcel->setActiveSheetIndex(0);
 
$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'No.');
$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'No. Pendaftaran');
$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'NIK');
$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Nama');
$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Tempat Lahir');
$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Tanggal Lahir');
$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Alamat');
$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Kota/Kabupaten');
$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Provinsi');
$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'No. HP');

$objPHPExcel->getActiveSheet()->getStyle("A1:J1")->getFont()->setBold(true);
 
$rowCount   =   2;
$nomor = 1;
while($row  =   $result->fetch_assoc()){

	// cek apakah sudah daftar asesmen

	$sqlgetasesmen="SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$row[no_pendaftaran]'";
	$getasesmen=$conn->query($sqlgetasesmen);
	$jumasesmen=$getasesmen->num_rows;
	$sqlgetasesmen2="SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$row[no_pendaftaran]' AND `id_jadwal` IS NULL";
	$getasesmen2=$conn->query($sqlgetasesmen2);
	$jumasesmen2=$getasesmen2->num_rows;

	if ($jumasesmen==0 OR $jumasesmen2>0){
		$sqlgetwil="SELECT * FROM `data_wilayah` WHERE `id_wil`='$row[kelurahan]'";
		$getwil=$conn->query($sqlgetwil);
		$wil=$getwil->fetch_assoc();
		$kelurahan=trim($wil['nm_wil']);
		$sqlgetwil2="SELECT * FROM `data_wilayah` WHERE `id_wil`='$row[kecamatan]'";
		$getwil2=$conn->query($sqlgetwil2);
		$wil2=$getwil2->fetch_assoc();
		$kecamatan=trim($wil2['nm_wil']);
		$sqlgetwil3="SELECT * FROM `data_wilayah` WHERE `id_wil`='$row[kota]'";
		$getwil3=$conn->query($sqlgetwil3);
		$wil3=$getwil3->fetch_assoc();
		$kota=trim($wil3['nm_wil']);
		$sqlgetwil4="SELECT * FROM `data_wilayah` WHERE `id_wil`='$row[propinsi]'";
		$getwil4=$conn->query($sqlgetwil4);
		$wil4=$getwil4->fetch_assoc();
		$provinsi=trim($wil4['nm_wil']);
		$alamat=$row['alamat']." RT ".$row['RT']." RW ".$row['RW'].", ".$kelurahan.", ".$kecamatan.", ".$kota.", ".$provinsi.", Kodepos ".$row['kodepos'];

		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, mb_strtoupper($nomor,'UTF-8'));
		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, mb_strtoupper($row['no_pendaftaran'],'UTF-8'));
		$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, mb_strtoupper($row['no_ktp'],'UTF-8'));
		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, mb_strtoupper($row['nama'],'UTF-8'));
		$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, mb_strtoupper($row['tmp_lahir'],'UTF-8'));
		$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, mb_strtoupper($row['tgl_lahir'],'UTF-8'));
		$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, mb_strtoupper($alamat,'UTF-8'));
		$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, mb_strtoupper($kota,'UTF-8'));
		$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, mb_strtoupper($provinsi,'UTF-8'));
		$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, mb_strtoupper($row['nohp'],'UTF-8'));
		$rowCount++;
		$nomor++;
	}
}
 
 
$objWriter  =   new PHPExcel_Writer_Excel2007($objPHPExcel);
 
 
header('Content-Type: application/vnd.ms-excel'); //mime type
header('Content-Disposition: attachment;filename="Data-Asesi-Belum-Terjadwal.xlsx"'); //tell browser what's the file name
header('Cache-Control: max-age=0'); //no cache
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');  
ob_end_clean();
$objWriter->save('php://output');
?>