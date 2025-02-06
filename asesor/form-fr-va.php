<?php
ob_start();
include 'fpdf-easytable-master/fpdf.php';
include 'fpdf-easytable-master/exfpdf.php';
include 'fpdf-easytable-master/easyTable.php';
include "../config/koneksi.php";
include "../config/library.php";
include "../config/fungsi_indotgl.php";

/* $sqlasesi="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_GET[ida]'";
$asesi=$conn->query($sqlasesi);
$as=$asesi->fetch_assoc(); */
$sqljadwal="SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
$jadwal=$conn->query($sqljadwal);
$jdq=$jadwal->fetch_assoc();
$tgl_cetak = tgl_indo($jdq['tgl_asesmen']);

$sqltuk="SELECT * FROM `tuk` WHERE `id`='$jdq[tempat_asesmen]'";
$tuk=$conn->query($sqltuk);
$tq=$tuk->fetch_assoc();
$sqllsp="SELECT * FROM `lsp` WHERE `id`='$tq[lsp_induk]'";
$lsp=$conn->query($sqllsp);
$lq=$lsp->fetch_assoc();
$sqlskema="SELECT * FROM `skema_kkni` WHERE `id`='$jdq[id_skemakkni]'";
$skema=$conn->query($sqlskema);
$sq=$skema->fetch_assoc();
$skemakkni=$sq['judul'];

$sqlwil1="SELECT * FROM `data_wilayah` WHERE `id_wil`='$lq[id_wilayah]'";
$wilayah1=$conn->query($sqlwil1);
$wil1=$wilayah1->fetch_assoc();
$sqlwil2="SELECT * FROM `data_wilayah` WHERE `id_wil`='$wil1[id_induk_wilayah]'";
$wilayah2=$conn->query($sqlwil2);
$wil2=$wilayah2->fetch_assoc();
$sqlwil3="SELECT * FROM `data_wilayah` WHERE `id_wil`='$wil2[id_induk_wilayah]'";
$wilayah3=$conn->query($sqlwil3);
$wil3=$wilayah3->fetch_assoc();

$sqlwil1b="SELECT * FROM `data_wilayah` WHERE `id_wil`='$tq[id_wilayah]'";
$wilayah1b=$conn->query($sqlwil1b);
$wil1b=$wilayah1b->fetch_assoc();
$sqlwil2b="SELECT * FROM `data_wilayah` WHERE `id_wil`='$wil1b[id_induk_wilayah]'";
$wilayah2b=$conn->query($sqlwil2b);
$wil2b=$wilayah2b->fetch_assoc();
$sqlwil3b="SELECT * FROM `data_wilayah` WHERE `id_wil`='$wil2b[id_induk_wilayah]'";
$wilayah3b=$conn->query($sqlwil3b);
$wil3b=$wilayah3b->fetch_assoc();


$pdf=new exFPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',10);
$pdf->AddFont('FontUTF8','','Arimo-Regular.php'); 
$pdf->AddFont('FontUTF8','B','Arimo-Bold.php');
$pdf->AddFont('FontUTF8','I','Arimo-Italic.php');
$pdf->AddFont('FontUTF8','BI','Arimo-BoldItalic.php');

// kop LSP ======================================================

//tampilan Form
$id_wilayah=trim($wil1['nm_wil']);
$id_wilayah2=trim($wil2['nm_wil']).", ".trim($wil3['nm_wil']);
$namalsp=strtoupper($lq['nama']);
$alamatlsp=$lq['alamat']." ".$lq['kelurahan']." ".$id_wilayah;
$alamatlsp2=$id_wilayah2." Kodepos : ".$lq['kodepos'];
$telpemail="Telp./Fax.: ".$lq['telepon']." / ".$lq['fax']." Email : ".$lq['email'].", ".$lq['website'];
$tampilperiode="Periode ".$jdq['periode']." Tahun ".$jdq['tahun']." Gelombang ".$jdq['gelombang'];
$nomorlisensi="Nomor Lisensi : ".$lq['no_lisensi'];

$alamatlsptampil=$alamatlsp." ".$alamatlsp2." ".$telpemail;
//$pdf->Cell(0, 5, '', '0', 1, 'C');
$pdf->Ln();
$write=new easyTable($pdf, '{30, 130, 30}', 'width:190; align:L; font-style:B; font-family:arial;');
$write->easyCell('', 'img:../images/logolsp.jpg, w25, h25; align:C; rowspan:3');
$write->easyCell($namalsp, 'align:C; font-size:14;');
$write->easyCell('', 'img:../images/logo-bnsp.jpg, w25, h25;align:C; rowspan:3');
$write->printRow();
$write->easyCell($nomorlisensi, 'align:C; font-size:10;');
$write->printRow();
$write->easyCell($alamatlsptampil, 'align:C; font-size:8;');
$write->printRow();
$write->endTable(5);


//===============================================================

$write=new easyTable($pdf, 1, 'width:190;  font-style:B; font-size:12;font-family:arial;');
$write->easyCell('FR.VA. MEMBERIKAN KONTRIBUSI DALAM VALIDASI ASESMEN', 'align:L;');
$write->printRow();
$write->endTable(5);
	$noasr=1;
				$sqlgetvalidator="SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
				$getvalidator=$conn->query($sqlgetvalidator);
				$mkv=$getvalidator->fetch_assoc();
				
				$sqlasesor="SELECT * FROM `asesor` WHERE `id`='$mkv[asesor_mkva1]'";
				$asesor=$conn->query($sqlasesor);
				$asr=$asesor->fetch_assoc();
				if (!empty($asr['gelar_depan'])){
					if (!empty($asr['gelar_blk'])){
						$namaasesor=$asr['gelar_depan']." ".$asr['nama'].", ".$asr['gelar_blk'];
					}else{
						$namaasesor=$asr['gelar_depan']." ".$asr['nama'];
					}
				}else{
					if (!empty($asr['gelar_blk'])){
						$namaasesor=$asr['nama'].", ".$asr['gelar_blk'];
					}else{
						$namaasesor=$asr['nama'];
					}
				}

$write=new easyTable($pdf, '{30, 70, 30, 60}', 'width:190; align:L; font-style:B; font-family:arial; font-size:11');
$write->rowStyle('min-height:10');
$write->easyCell('Tim Validasi', 'align:L; rowspan:2; border:LTBR');
$write->easyCell('1. '.$namaasesor, 'align:L; font-size:11; border:LTBR');
$write->easyCell('Hari/ Tanggal', 'align:L; font-size:11; border:LTBR');
$dateasesmen = strtotime($jdq['tgl_asesmen']);
$daynya=date("l",$dateasesmen);
switch ($daynya){
	case "Monday":
		$harinya="Senin";
	break;
	case "Tuesday":
		$harinya="Selasa";
	break;
	case "Wednesday":
		$harinya="Rabu";
	break;
	case "Thursday":
		$harinya="Kamis";
	break;
	case "Friday":
		$harinya="Jumat";
	break;
	case "Saturday":
		$harinya="Sabtu";
	break;
	case "Sunday":
		$harinya="Minggu";
	break;
}

$write->easyCell($harinya.', '.$tgl_cetak, 'align:L; font-size:11; border:LTBR');
$write->printRow();
$write->rowStyle('min-height:10');
				$sqlasesor2="SELECT * FROM `asesor` WHERE `id`='$mkv[asesor_mkva2]'";
				$asesor2=$conn->query($sqlasesor2);
				$asr2=$asesor2->fetch_assoc();
				if (!empty($asr2['gelar_depan'])){
					if (!empty($asr2['gelar_blk'])){
						$namaasesor2=$asr2['gelar_depan']." ".$asr2['nama'].", ".$asr2['gelar_blk'];
					}else{
						$namaasesor2=$asr2['gelar_depan']." ".$asr2['nama'];
					}
				}else{
					if (!empty($asr['gelar_blk'])){
						$namaasesor2=$asr2['nama'].", ".$asr2['gelar_blk'];
					}else{
						$namaasesor2=$asr2['nama'];
					}
				}
$write->easyCell('2. '.$namaasesor2, 'align:L; font-size:11; border:LTBR');
$write->easyCell('Tempat', 'align:L; font-size:11; border:LTBR');
$write->easyCell($tq['nama'], 'align:L; font-size:11; border:LTBR');
$write->printRow();
$write->endTable(0);
$write=new easyTable($pdf, '{30, 5, 7, 38, 7, 40, 7, 56}', 'width:190; align:L; font-style:B; font-family:arial; font-size:11');
$write->rowStyle('min-height:10');
$write->easyCell('Periode', 'align:L; valign:M; border:LTBR');
$write->easyCell(':', 'align:C; valign:M; font-size:11; border:LTB');
$sqlcekmkva="SELECT * FROM `mkva` WHERE `id_jadwal`='$_GET[idj]'";
$cekmkva=$conn->query($sqlcekmkva);
$cmkv=$cekmkva->fetch_assoc();
switch ($cmkv['periode']){
case "1":
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TB;');
	$write->easyCell('Sebelum Asesmen', 'align:L; valign:M; font-size:11; border:TB');
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');
	$write->easyCell('Pada Saat Asesmen', 'align:L; valign:M; font-size:11; border:TB');
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');
	$write->easyCell('Setelah Asesmen', 'align:L; valign:M; font-size:11; border:TBR');
break;
case "2":
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');
	$write->easyCell('Sebelum Asesmen', 'align:L; valign:M; font-size:11; border:TB');
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TB;');
	$write->easyCell('Pada Saat Asesmen', 'align:L; valign:M; font-size:11; border:TB');
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');
	$write->easyCell('Setelah Asesmen', 'align:L; valign:M; font-size:11; border:TBR');
break;
case "3":
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');
	$write->easyCell('Sebelum Asesmen', 'align:L; valign:M; font-size:11; border:TB');
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');
	$write->easyCell('Pada Saat Asesmen', 'align:L; valign:M; font-size:11; border:TB');
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TB;');
	$write->easyCell('Setelah Asesmen', 'align:L; valign:M; font-size:11; border:TBR');
break;
default:
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');
	$write->easyCell('Sebelum Asesmen', 'align:L; valign:M; font-size:11; border:TB');
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');
	$write->easyCell('Pada Saat Asesmen', 'align:L; valign:M; font-size:11; border:TB');
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');
	$write->easyCell('Setelah Asesmen', 'align:L; valign:M; font-size:11; border:TBR');
break;
}
$write->printRow();
$write->endTable(0);
$write=new easyTable($pdf, '{30, 5, 155}', 'width:190; align:L; font-family:arial; font-size:12');
$write->rowStyle('min-height:10');
$write->easyCell('Nama Skema', 'align:L; border:LTBR');
$write->easyCell(':', 'align:C; font-size:11; border:LTB');
$write->easyCell($skemakkni, 'align:L; font-style:B; font-size:11; border:TBR');
$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell('Nomor Skema', 'align:L; font-size:11; border:LTBR');
$write->easyCell(':', 'align:C; font-size:11; border:LTB');
$write->easyCell($sq['kode_skema'], 'align:L; font-size:11; border:TBR');
$write->printRow();
$write->endTable(5);


$write=new easyTable($pdf, '{10,7,62,7,50,7,47}', 'width:190; align:L; font-family:arial; font-size:12');
$write->easyCell('1', 'align:L; border:LTBR; bgcolor:#C6C6C6; font-style:B;');
$write->easyCell('Menyiapkan proses validasi', 'align:L; colspan:6; border:LTBR; bgcolor:#C6C6C6; font-style:B;');
$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell('', 'align:L; border:LTBR; rowspan:8; font-style:B;');
$write->easyCell('Tujuan dan fokus validasi', 'align:L; colspan:2; border:LTBR; font-style:B;');
$write->easyCell('Konteks validasi', 'align:L; colspan:2; border:LTBR; font-style:B;');
$write->easyCell('Pendekatan validasi', 'align:L; colspan:2; border:LTBR; font-style:B;');
$write->printRow();
$write->rowStyle('min-height:10');
if ($cmkv['tujuan_1']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TB;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');
}
$write->easyCell('Bagian dari proses penjaminan mutu organisasi', 'align:L; border:TBR;');
if ($cmkv['konteks_1']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TB;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');
}
$write->easyCell('Internal organisasi', 'align:L; border:TBR;');
if ($cmkv['pendekatan_1']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TB;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');
}
$write->easyCell('Panel  asesmen', 'align:L; border:TBR;');
$write->printRow();
$write->rowStyle('min-height:10');
if ($cmkv['tujuan_2']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TB;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');
}
$write->easyCell('Mengantisipasi risiko', 'align:L; border:TBR;');
if ($cmkv['konteks_2']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TB;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');
}
$write->easyCell('Eksternal organisasi', 'align:L; border:TBR;');
if ($cmkv['pendekatan_2']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TB;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');
}
$write->easyCell('Pertemuan moderasi', 'align:L; border:TBR;');
$write->printRow();
$write->rowStyle('min-height:10');
if ($cmkv['tujuan_3']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TB;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');
}
$write->easyCell('Memenuhi persyaratan BNSP', 'align:L; border:TBR;');
if ($cmkv['konteks_3']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TB;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');
}
$write->easyCell('Proses lisensi/re lisensi', 'align:L; border:TBR;');
if ($cmkv['pendekatan_3']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TB;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');
}
$write->easyCell('Mengkaji perangkat asesmen', 'align:L; border:TBR;');
$write->printRow();
$write->rowStyle('min-height:10');
if ($cmkv['tujuan_4']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TB;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');
}
$write->easyCell('Memastikan kesesuaian bukti-bukti', 'align:L; border:TBR;');
if ($cmkv['konteks_4']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TB;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');
}
$write->easyCell('Dengan kolega asesor', 'align:L; border:TBR;');
if ($cmkv['pendekatan_4']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TB;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');
}
$write->easyCell('Acuan pembanding', 'align:L; border:TBR;');
$write->printRow();
$write->rowStyle('min-height:10');
if ($cmkv['tujuan_5']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TB;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');
}
$write->easyCell('Meningkatkan kualitas asesmen', 'align:L; border:TBR;');
if ($cmkv['konteks_5']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TB;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');
}
$write->easyCell('Kolega dari organisasi pelatihan atau asesmen', 'align:L; border:TBR;');
if ($cmkv['pendekatan_5']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TB;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');
}
$write->easyCell('Pengujian lapangan dan uji coba perangkat asesmen', 'align:L; border:TBR;');
$write->printRow();
$write->rowStyle('min-height:10');
if ($cmkv['tujuan_6']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TB;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');
}
$write->easyCell('Mengevaluasi kualitas perangkat asesmen', 'align:L; border:TBR;');
if ($cmkv['konteks_6']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TB;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');
}
if (!empty($cmkv['konteks_6b'])){
	$write->easyCell($cmkv['konteks_6b'], 'align:L; valign:B; border:TBR;');
}else{
	$write->easyCell('����������', 'align:L; valign:B; border:TBR;');
}
if ($cmkv['pendekatan_6']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TB;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');
}
$write->easyCell('Umpan balik dari klien', 'align:L; border:TBR;');
$write->printRow();
$write->rowStyle('min-height:10');
if ($cmkv['tujuan_7']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TB;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');
}
if (!empty($cmkv['tujuan_7b'])){
	$write->easyCell($cmkv['tujuan_7b'], 'align:L; valign:B; border:TBR;');
}else{
	$write->easyCell('������������', 'align:L; valign:B; border:TBR;');
}
$write->easyCell('', 'align:L; border:TB;');
$write->easyCell('', 'align:L; border:TBR;');
$write->easyCell('', 'align:L; border:TB;');
$write->easyCell('', 'align:L; border:TBR;');
$write->printRow();
$write->endTable(0);

$write=new easyTable($pdf, '{10,7,62,7,50,7,47}', 'width:190; align:L; font-family:arial; font-size:12');
$write->easyCell('', 'align:L; border:LTR; font-style:B;');
$write->easyCell('Orang yang relevan', 'align:L; colspan:2; border:LTBR; font-style:B;');
$write->easyCell('Nama', 'align:L; colspan:2; border:LTBR; font-style:B;');
$write->easyCell('Hasil konfirmasi/diskusi tujuan, fokus & konteks', 'align:L; colspan:2; border:LTBR; font-style:B;');
$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell('', 'align:L; border:LR; rowspan:3; font-style:B;');
if ($cmkv['askom']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; rowspan:3; border:TBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; rowspan:3; border:TBR;');
}
$write->easyCell('Asesor kompetensi (wajib)', 'align:L; valign:T; rowspan:3; border:TBR;');
if(!empty($cmkv['orel_1'])){
	$write->easyCell('1. '.$cmkv['orel_1'], 'align:L; colspan:2; border:TBR;');
}else{
	$write->easyCell('1.', 'align:L; colspan:2; border:TBR;');
}
if(!empty($cmkv['konfirmorel_1'])){
	$write->easyCell($cmkv['konfirmorel_1'], 'align:L; colspan:2; border:TBR;');
}else{
	$write->easyCell('', 'align:L; colspan:2; border:TBR;');
}
$write->printRow();
$write->rowStyle('min-height:10');
if(!empty($cmkv['orel_2'])){
	$write->easyCell('2. '.$cmkv['orel_2'], 'align:L; colspan:2; border:TBR;');
}else{
	$write->easyCell('2.', 'align:L; colspan:2; border:TBR;');
}
if(!empty($cmkv['konfirmorel_2'])){
	$write->easyCell($cmkv['konfirmorel_2'], 'align:L; colspan:2; border:TBR;');
}else{
	$write->easyCell('', 'align:L; colspan:2; border:TBR;');
}
$write->printRow();
$write->rowStyle('min-height:10');
if(!empty($cmkv['orel_3'])){
	$write->easyCell('3. '.$cmkv['orel_3'], 'align:L; colspan:2; border:TBR;');
}else{
	$write->easyCell('3.', 'align:L; colspan:2; border:TBR;');
}
if(!empty($cmkv['konfirmorel_3'])){
	$write->easyCell($cmkv['konfirmorel_3'], 'align:L; colspan:2; border:TBR;');
}else{
	$write->easyCell('', 'align:L; colspan:2; border:TBR;');
}
$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell('', 'align:L; border:LR; font-style:B;');
if ($cmkv['leadasesor']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TBR;');
}
$write->easyCell('Lead Asesor', 'align:L; border:TBR;');
if(!empty($cmkv['nama_leadasesor'])){
	$write->easyCell($cmkv['nama_leadasesor'], 'align:L;colspan:2;  border:TBR;');
}else{
	$write->easyCell('', 'align:L;colspan:2;  border:TBR;');
}
if(!empty($cmkv['konfirmleadasesor'])){
	$write->easyCell($cmkv['konfirmleadasesor'], 'align:L;colspan:2;  border:TBR;');
}else{
	$write->easyCell('', 'align:L;colspan:2;  border:TBR;');
}
$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell('', 'align:L; border:LR; font-style:B;');
if ($cmkv['manspv']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TBR;');
}
$write->easyCell('Manager, supervisor', 'align:L; border:TBR;');
if(!empty($cmkv['nama_manspv'])){
	$write->easyCell($cmkv['nama_manspv'], 'align:L;colspan:2;  border:TBR;');
}else{
	$write->easyCell('', 'align:L;colspan:2;  border:TBR;');
}
if(!empty($cmkv['konfirmmanspv'])){
	$write->easyCell($cmkv['konfirmmanspv'], 'align:L;colspan:2;  border:TBR;');
}else{
	$write->easyCell('', 'align:L;colspan:2;  border:TBR;');
}
$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell('', 'align:L; border:LR; font-style:B;');
if ($cmkv['tenagaahli']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TBR;');
}
$write->easyCell('Tenaga ahli di bidangnya', 'align:L; border:TBR;');
if(!empty($cmkv['nama_tenagaahli'])){
	$write->easyCell($cmkv['nama_tenagaahli'], 'align:L;colspan:2;  border:TBR;');
}else{
	$write->easyCell('', 'align:L;colspan:2;  border:TBR;');
}
if(!empty($cmkv['konfirmtenagaahli'])){
	$write->easyCell($cmkv['konfirmtenagaahli'], 'align:L;colspan:2;  border:TBR;');
}else{
	$write->easyCell('', 'align:L;colspan:2;  border:TBR;');
}
$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell('', 'align:L; border:LR; font-style:B;');
if ($cmkv['koordtraining']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TBR;');
}
$write->easyCell('Koord. Pelatihan', 'align:L; border:TBR;');
if(!empty($cmkv['nama_koordtraining'])){
	$write->easyCell($cmkv['nama_koordtraining'], 'align:L;colspan:2;  border:TBR;');
}else{
	$write->easyCell('', 'align:L;colspan:2;  border:TBR;');
}
if(!empty($cmkv['konfirmkoordtraining'])){
	$write->easyCell($cmkv['konfirmkoordtraining'], 'align:L;colspan:2;  border:TBR;');
}else{
	$write->easyCell('', 'align:L;colspan:2;  border:TBR;');
}
$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell('', 'align:L; border:LR; font-style:B;');
if ($cmkv['asosiasi']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TBR;');
}
$write->easyCell('Anggota asosiasi industry/profesi', 'align:L; border:TBR;');
if(!empty($cmkv['nama_asosiasi'])){
	$write->easyCell($cmkv['nama_asosiasi'], 'align:L;colspan:2;  border:TBR;');
}else{
	$write->easyCell('', 'align:L;colspan:2;  border:TBR;');
}
if(!empty($cmkv['konfirmasosiasi'])){
	$write->easyCell($cmkv['konfirmasosiasi'], 'align:L;colspan:2;  border:TBR;');
}else{
	$write->easyCell('', 'align:L;colspan:2;  border:TBR;');
}
$write->printRow();
$write->endTable(0);

$write=new easyTable($pdf, '{10,7,83,7,83}', 'width:190; align:L; font-family:arial; font-size:12');
$write->rowStyle('min-height:10');
$write->easyCell('', 'align:L; border:LR; font-style:B;');
$write->easyCell('Acuan Pembanding :', 'align:L; colspan:2; border:LR; font-style:B;');
$write->easyCell('Dokumen terkait dan bahan-bahan :', 'align:L; colspan:2; border:LR; font-style:B;');
$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell('', 'align:L; border:LR; font-style:B;');
if ($cmkv['stdkom']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TB;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');
}
$write->easyCell('Standar kompetensi', 'align:L; border:TR; font-style:B;');
if ($cmkv['skema']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TB;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');
}
$write->easyCell('Skema sertifikasi', 'align:L; border:TR; font-style:B;');
$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell('', 'align:L; border:LR; font-style:B;');
if ($cmkv['sop']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TB;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');
}
$write->easyCell('SOP/IK', 'align:L; border:TR; font-style:B;');
if ($cmkv['skk']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TB;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');
}
$write->easyCell('SKKNI/SK3/SKI', 'align:L; border:TR; font-style:B;');
$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell('', 'align:L; border:LR; font-style:B;');
if ($cmkv['manualbook']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TB;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');
}
$write->easyCell('Manual Instruction/book', 'align:L; border:TR; font-style:B;');
if ($cmkv['perangkat']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TB;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');
}
$write->easyCell('Perangkat asesmen', 'align:L; border:TR; font-style:B;');
$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell('', 'align:L; border:LR; font-style:B;');
if ($cmkv['stdkinerja']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TB;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');
}
$write->easyCell('Standar Kinerja', 'align:L; border:TR; font-style:B;');
if ($cmkv['peraturan']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TB;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');
}
$write->easyCell('Peraturan/Pedoman', 'align:L; border:TR; font-style:B;');
$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell('', 'align:L; border:LRB; font-style:B;');
if ($cmkv['lainnya']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TB;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');
}
if (!empty($cmkv['nama_lainnya'])){
	$write->easyCell($cmkv['nama_lainnya'], 'align:L; valign:B; border:TRB; font-style:B;');
}else{
	$write->easyCell('�������������.', 'align:L; valign:B; border:TRB; font-style:B;');
}
$write->easyCell('', 'align:L; colspan:2; border:LTRB; font-style:B;');
$write->printRow();
$write->endTable(5);

$write=new easyTable($pdf, '{10,85,10,85}', 'width:190; align:L; font-family:arial; font-size:12');
$write->easyCell('2', 'align:L; border:LTBR; bgcolor:#C6C6C6; font-style:B;');
$write->easyCell('Memberikan kontribusi dalam proses validasi', 'align:L; colspan:3; border:LTBR; bgcolor:#C6C6C6; font-style:B;');
$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell('', 'align:L; rowspan:4; border:LTBR; font-style:B;');
$write->easyCell('Keterampilan komunikasi yang digunakan dalam kegiatan validasi :', 'align:L; valign:T; rowspan:4; border:LTBR;');
if ($cmkv['proaktif']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TB;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');
}
$write->easyCell('PRO AKTIF', 'align:L; border:TBR; font-style:B;');
$write->printRow();
$write->rowStyle('min-height:10');
if ($cmkv['activelistening']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TB;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');
}
$write->easyCell('ACTIVE LISTENING', 'align:L; border:TBR; font-style:B;');
$write->printRow();
$write->rowStyle('min-height:10');
if ($cmkv['keterampilan1']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TB;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');
}
if (!empty($cmkv['nama_ketlainnya1'])){
	$write->easyCell($cmkv['nama_ketlainnya1'], 'align:L; border:TBR; font-style:B;');
}else{
	$write->easyCell('', 'align:L; border:TBR; font-style:B;');
}
$write->printRow();
$write->rowStyle('min-height:10');
if ($cmkv['keterampilan2']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TB;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');
}
if (!empty($cmkv['nama_ketlainnya2'])){
	$write->easyCell($cmkv['nama_ketlainnya2'], 'align:L; border:TBR; font-style:B;');
}else{
	$write->easyCell('', 'align:L; border:TBR; font-style:B;');
}
$write->printRow();
$write->endTable(5);

$write=new easyTable($pdf, '{10,100,10,10,10,10,10,10,10,10}', 'width:190; align:L; font-family:arial; font-size:12');
$write->easyCell('No.', 'align:C; border:LTBR; rowspan:3; font-style:B; font-size:12');
$write->easyCell('Aspek-Aspek Dalam Kegiatan Validasi
(Meninjau, Membandingkan, Mengevaluasi)', 'align:C; border:LTBR; rowspan:3; font-style:B;');
$write->easyCell('Pemenuhan Terhadap :', 'align:C; colspan:8; border:LTBR; font-style:B;');
$write->printRow();
$write->easyCell('Aturan Bukti', 'align:C; colspan:4; border:LTBR; font-style:B;');
$write->easyCell('Prinsip Asesmen', 'align:C; colspan:4; border:LTBR; font-style:B;');
$write->printRow();
$write->easyCell('V', 'align:C; border:LTBR; font-style:B;');
$write->easyCell('A', 'align:C; border:LTBR; font-style:B;');
$write->easyCell('T', 'align:C; border:LTBR; font-style:B;');
$write->easyCell('M', 'align:C; border:LTBR; font-style:B;');
$write->easyCell('V', 'align:C; border:LTBR; font-style:B;');
$write->easyCell('R', 'align:C; border:LTBR; font-style:B;');
$write->easyCell('F', 'align:C; border:LTBR; font-style:B;');
$write->easyCell('F', 'align:C; border:LTBR; font-style:B;');
$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell('1.', 'align:C; valign:T; border:LTBR;');
$write->easyCell('Proses asesmen', 'align:L; valign:T; border:LTBR;');
if ($cmkv['ab1v']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['ab1a']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['ab1t']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['ab1m']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['pa1v']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['pa1r']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['pa1f']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['pa1f2']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell('2.', 'align:C; valign:T; border:LTBR;');
$write->easyCell('Rencana asesmen', 'align:L; valign:T; border:LTBR;');
if ($cmkv['ab2v']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['ab2a']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['ab2t']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['ab2m']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['pa2v']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['pa2r']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['pa2f']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['pa2f2']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell('3.', 'align:C; valign:T; border:LTBR;');
$write->easyCell('Interpretasi standar kompetensi', 'align:L; valign:T; border:LTBR;');
if ($cmkv['ab3v']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['ab3a']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['ab3t']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['ab3m']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['pa3v']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['pa3r']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['pa3f']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['pa3f2']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell('4.', 'align:C; valign:T; border:LTBR;');
$write->easyCell('Interpretasi acuan pembanding lainnya', 'align:L; valign:T; border:LTBR;');
if ($cmkv['ab4v']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['ab4a']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['ab4t']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['ab4m']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['pa4v']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['pa4r']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['pa4f']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['pa4f2']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell('5.', 'align:C; valign:T; border:LTBR;');
$write->easyCell('Penyeleksian dan penerapan metode asesmen', 'align:L; valign:T; border:LTBR;');
if ($cmkv['ab5v']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['ab5a']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['ab5t']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['ab5m']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['pa5v']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['pa5r']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['pa5f']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['pa5f2']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell('6.', 'align:C; valign:T; border:LTBR;');
$write->easyCell('Penyeleksian dan penerapan perangkat asesmen', 'align:L; valign:T; border:LTBR;');
if ($cmkv['ab6v']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['ab6a']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['ab6t']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['ab6m']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['pa6v']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['pa6r']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['pa6f']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['pa6f2']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell('7.', 'align:C; valign:T; border:LTBR;');
$write->easyCell('Bukti-bukti yang dikumpulkan', 'align:L; valign:T; border:LTBR;');
if ($cmkv['ab7v']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['ab7a']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['ab7t']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['ab7m']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['pa7v']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['pa7r']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['pa7f']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['pa7f2']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell('8.', 'align:C; valign:T; border:LTBR;');
$write->easyCell('Proses pengambilan keputusan', 'align:L; valign:T; border:LTBR;');
if ($cmkv['ab8v']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['ab8a']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['ab8t']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['ab8m']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['pa8v']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['pa8r']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['pa8f']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
if ($cmkv['pa8f2']==1){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
}
$write->printRow();
$write->endTable(5);

$pdf->AddPage();

$write=new easyTable($pdf, '{10,10,85,85}', 'width:190; align:L; font-family:arial; font-size:12');
$write->easyCell('3.', 'align:C; border:LTBR; bgcolor:#C6C6C6; font-style:B; font-size:12');
$write->easyCell('Memberikan kontribusi untuk hasil asesmen', 'align:L; border:LTBR; colspan:3; bgcolor:#C6C6C6; font-style:B;');
$write->printRow();
$sqlgetmkvatemuan="SELECT * FROM `mkva_temuan` WHERE `id_jadwal`='$_GET[idj]'";
$getmkvatemuan=$conn->query($sqlgetmkvatemuan);
$jumtemuanmkva=$getmkvatemuan->num_rows;
$jumtemuanmkvaspan=$jumtemuanmkva+1;
if ($jumtemuanmkva>0){
	$write->easyCell('', 'align:C; valign:T; rowspan:'.$jumtemuanmkvaspan.'; border:LTBR;');
	$write->easyCell('Temuan-temuan validasi :', 'align:L; valign:T; colspan:2; border:LTBR;');
	$write->easyCell('Rekomendasi-rekomendasi untuk meningkatkan praktek asesmen', 'align:L; valign:T; border:LTBR;');
	$write->printRow();

	$notm=1;
	while ($mkvtm=$getmkvatemuan->fetch_assoc()){
		$write->rowStyle('min-height:20');
		$write->easyCell($notm.'.', 'align:C; valign:T; border:LTBR;');
		$write->easyCell($mkvtm['temuan'], 'align:L; valign:T; border:LTBR;');
		$write->easyCell($mkvtm['rekomendasi'], 'align:L; valign:T; border:LTBR;');
		$write->printRow();
		$notm++;
	}
	$write->endTable(0);
}else{
	$write->easyCell('', 'align:C; valign:T; rowspan:5; border:LTBR;');
	$write->easyCell('Temuan-temuan validasi :', 'align:L; valign:T; colspan:2; border:LTBR;');
	$write->easyCell('Rekomendasi-rekomendasi untuk meningkatkan praktek asesmen', 'align:L; valign:T; border:LTBR;');
	$write->printRow();
	$write->rowStyle('min-height:20');
	$write->easyCell('1.', 'align:C; valign:T; border:LTBR;');
	$write->easyCell('', 'align:L; valign:T; border:LTBR;');
	$write->easyCell('', 'align:L; valign:T; border:LTBR;');
	$write->printRow();
	$write->rowStyle('min-height:20');
	$write->easyCell('2.', 'align:C; valign:T; border:LTBR;');
	$write->easyCell('', 'align:L; valign:T; border:LTBR;');
	$write->easyCell('', 'align:L; valign:T; border:LTBR;');
	$write->printRow();
	$write->rowStyle('min-height:20');
	$write->easyCell('3.', 'align:C; valign:T; border:LTBR;');
	$write->easyCell('', 'align:L; valign:T; border:LTBR;');
	$write->easyCell('', 'align:L; valign:T; border:LTBR;');
	$write->printRow();
	$write->rowStyle('min-height:20');
	$write->easyCell('4.', 'align:C; valign:T; border:LTBR;');
	$write->easyCell('', 'align:L; valign:T; border:LTBR;');
	$write->easyCell('', 'align:L; valign:T; border:LTBR;');
	$write->printRow();
	$write->endTable(0);
}

$write=new easyTable($pdf, '{10,10,80,40,50}', 'width:190; align:L; font-family:arial; font-size:12');
$write->rowStyle('min-height:10');
$sqlgetmkvatemuan2="SELECT * FROM `mkva_perbaikan` WHERE `id_jadwal`='$_GET[idj]'";
$getmkvatemuan2=$conn->query($sqlgetmkvatemuan2);
$jumtemuanmkva2=$getmkvatemuan2->num_rows;
$jumtemuanmkva2span=$jumtemuanmkva2+2;
if ($jumtemuanmkva2>0){
	$write->easyCell('', 'align:C; valign:T; rowspan:'.$jumtemuanmkva2span.'; border:LTBR;');
	$write->easyCell('Rencana Implementasi  perubahan/perbaikan pelaksanaan asesmen :', 'align:L; valign:M; colspan:4; font-style:B; border:LTBR;');
	$write->printRow();
	$write->rowStyle('min-height:10');
	$write->easyCell('No.', 'align:C; valign:M; border:LTBR;');
	$write->easyCell('Kegiatan Perbaikan sesuai
	Rekomendasi', 'align:C; valign:M; border:LTBR;');
	$write->easyCell('Waktu Penyelesaian', 'align:C; valign:M; border:LTBR;');
	$write->easyCell('Penanggungjawab', 'align:C; valign:M; border:LTBR;');
	$write->printRow();
	$notm2=1;
	while ($mkvtm2=$getmkvatemuan2->fetch_assoc()){
		$write->rowStyle('min-height:20');
		$write->easyCell($notm2.'.', 'align:C; valign:T; border:LTBR;');
		$write->easyCell($mkvtm2['perbaikan'], 'align:L; valign:T; border:LTBR;');
		$write->easyCell($mkvtm2['penyelesaian'], 'align:L; valign:T; border:LTBR;');
		$write->easyCell($mkvtm2['penanggungjawab'], 'align:L; valign:T; border:LTBR;');
		$write->printRow();
		$notm2++;
	}
	$write->endTable(5);
}else{
	$write->easyCell('', 'align:C; valign:T; rowspan:6; border:LTBR;');
	$write->easyCell('Rencana Implementasi  perubahan/perbaikan pelaksanaan asesmen :', 'align:L; valign:M; colspan:4; font-style:B; border:LTBR;');
	$write->printRow();
	$write->rowStyle('min-height:10');
	$write->easyCell('No.', 'align:C; valign:M; border:LTBR;');
	$write->easyCell('Kegiatan Perbaikan sesuai
	Rekomendasi', 'align:C; valign:M; border:LTBR;');
	$write->easyCell('Waktu Penyelesaian', 'align:C; valign:M; border:LTBR;');
	$write->easyCell('Penanggungjawab', 'align:C; valign:M; border:LTBR;');
	$write->printRow();
	$write->rowStyle('min-height:20');
	$write->easyCell('1.', 'align:C; valign:T; border:LTBR;');
	$write->easyCell('', 'align:L; valign:T; border:LTBR;');
	$write->easyCell('', 'align:L; valign:T; border:LTBR;');
	$write->easyCell('', 'align:L; valign:T; border:LTBR;');
	$write->printRow();
	$write->rowStyle('min-height:20');
	$write->easyCell('2.', 'align:C; valign:T; border:LTBR;');
	$write->easyCell('', 'align:L; valign:T; border:LTBR;');
	$write->easyCell('', 'align:L; valign:T; border:LTBR;');
	$write->easyCell('', 'align:L; valign:T; border:LTBR;');
	$write->printRow();
	$write->rowStyle('min-height:20');
	$write->easyCell('3.', 'align:C; valign:T; border:LTBR;');
	$write->easyCell('', 'align:L; valign:T; border:LTBR;');
	$write->easyCell('', 'align:L; valign:T; border:LTBR;');
	$write->easyCell('', 'align:L; valign:T; border:LTBR;');
	$write->printRow();
	$write->rowStyle('min-height:20');
	$write->easyCell('4.', 'align:C; valign:T; border:LTBR;');
	$write->easyCell('', 'align:L; valign:T; border:LTBR;');
	$write->easyCell('', 'align:L; valign:T; border:LTBR;');
	$write->easyCell('', 'align:L; valign:T; border:LTBR;');
	$write->printRow();
	$write->endTable(5);
}

$pdf->AliasNbPages();

//output file pdf
$fileoutputnya="FR-VA-".$skemakkni."-".$_GET['idj'].".pdf";
$pdf->Output($fileoutputnya,'I');
 
ob_end_flush();
?>