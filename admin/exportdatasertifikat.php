<?php
include "../config/koneksi.php";
include "PHPExcel/PHPExcel.php";
 
$objPHPExcel    =   new PHPExcel();

$result         =   $conn->query("SELECT * FROM `sertifikat` WHERE `no_blangko` IS NOT NULL ORDER BY `id_skemakkni` ASC, `id` ASC") or die(mysql_error());
 
$objPHPExcel->setActiveSheetIndex(0);
 
$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'No.');
$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'No. Pendaftaran');
$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'NIK');
$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Nama');
$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'No. Blangko');
$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'No. Sertifikat');
$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'No. Register');
$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Tanggal Terbit');
$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Masa Berlaku');
$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Skema/Jabker');

$objPHPExcel->getActiveSheet()->getStyle("A1:J1")->getFont()->setBold(true);
 
$rowCount   =   2;
$nomor = 1;
while($row  =   $result->fetch_assoc()){

	// cek apakah sudah daftar asesmen

	$sqlgetasesmen="SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$row[id_asesi]'";
	$getasesmen=$conn->query($sqlgetasesmen);
	$jumasesmen=$getasesmen->num_rows;
	$sqlgetasesmen2="SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$row[id_asesi]' AND `id_jadwal`='$row[id_jadwal]";
	$getasesmen2=$conn->query($sqlgetasesmen2);
	$jumasesmen2=$getasesmen2->num_rows;
	$sqlgetasesi="SELECT `nama`, `no_pendaftaran`, `no_ktp` FROM `asesi` WHERE `no_pendaftaran`='$row[id_asesi]'";
	$getasesi=$conn->query($sqlgetasesi);
	$ass=$getasesi->fetch_assoc();
	$sqlgetskema="SELECT `id`,`kode_skema`,`judul` FROM `skema_kkni` WHERE `id`='$row[id_skemakkni]'";
	$getskema=$conn->query($sqlgetskema);
	$skm=$getskema->fetch_assoc();
	//if ($jumasesmen==0 OR $jumasesmen2>0){
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, mb_strtoupper($nomor,'UTF-8'));
		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, mb_strtoupper($row['id_asesi'],'UTF-8'));
		$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, mb_strtoupper("'".$ass['no_ktp'],'UTF-8'));
		$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, mb_strtoupper($ass['nama'],'UTF-8'));
		$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, mb_strtoupper($row['no_blangko'],'UTF-8'));
		$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, mb_strtoupper($row['no_sertifikat'],'UTF-8'));
		$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, mb_strtoupper($row['no_registrasisertifikat'],'UTF-8'));
		$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, mb_strtoupper($row['tgl_terbit'],'UTF-8'));
		$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, mb_strtoupper($row['masa_berlaku'],'UTF-8'));
		$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, mb_strtoupper($skm['kode_skema']."-".$skm['judul'],'UTF-8'));
		$rowCount++;
		$nomor++;
	//}
}
 
 
$objWriter  =   new PHPExcel_Writer_Excel2007($objPHPExcel);
 
 
header('Content-Type: application/vnd.ms-excel'); //mime type
header('Content-Disposition: attachment;filename="Data-Pemegang-Sertifikat.xlsx"'); //tell browser what's the file name
header('Cache-Control: max-age=0'); //no cache
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');  
ob_end_clean();
$objWriter->save('php://output');
?>