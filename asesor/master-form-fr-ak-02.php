<?php
ini_set('display_errors',0);
ob_start();
include 'fpdf-easytable-master/fpdf.php';
include 'fpdf-easytable-master/exfpdf.php';
include 'fpdf-easytable-master/easyTable.php';
include "../config/koneksi.php";
include "../config/library.php";
include "../config/fungsi_indotgl.php";

$sqllsp="SELECT * FROM `lsp`";
$lsp=$conn->query($sqllsp);
$lq=$lsp->fetch_assoc();
$sqlskema="SELECT * FROM `skema_kkni` WHERE `id`='$_GET[idsk]'";
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
$write->easyCell('FR.AK.02. FORMULIR REKAMAN ASESMEN KOMPETENSI', 'align:L;');
$write->printRow();
$write->endTable(5);

$write=new easyTable($pdf, '{50, 40, 100}', 'width:190; align:L; font-style:B; font-family:arial; font-size:12');

$write->easyCell('Nama Asesi', 'align:L; font-size:12; border:LTBR');
$write->easyCell('', 'align:L; font-size:12; colspan:2; border:LTBR');
$write->printRow();
$write->easyCell('Nama Asesor', 'align:L; font-size:12; border:LTBR');
$write->easyCell('', 'align:L; font-size:12; colspan:2; border:LTBR');
$write->printRow();
$write->easyCell('Skema Sertifikasi (bila tersedia)', 'align:L; border:LTBR');
$write->easyCell($skemakkni, 'align:L; font-size:12; colspan:2; border:LTBR');
$write->printRow();
$sqlgetunitkompetensi0="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sq[id]'";
$getunitkompetensi0=$conn->query($sqlgetunitkompetensi0);
$jumunitnya=$getunitkompetensi0->num_rows;
$noku0=1;
//while unitkompetensi ==================================================================
while ($unk0=$getunitkompetensi0->fetch_assoc()){
	if ($noku0==1){
		$write->easyCell('Unit Kompetensi', 'align:L; valign:T; border:LTR');
		$write->easyCell($unk0['kode_unit'], 'align:L; font-size:12; border:LTBR');
		$write->easyCell($unk0['judul'], 'align:L; font-size:12; border:LTBR');
		$write->printRow();
	}else{
		$write->easyCell('', 'align:L; valign:T; border:LR');
		$write->easyCell($unk0['kode_unit'], 'align:L; font-size:12; border:LTBR');
		$write->easyCell($unk0['judul'], 'align:L; font-size:12; border:LTBR');
		$write->printRow();

	}
	$noku0++;
}
$write->easyCell('Tanggal mulainya asesmen', 'align:L; font-size:12; border:LTBR');
$write->easyCell($tgl_cetak, 'align:L; font-size:12; colspan:2; border:LTBR');
$write->printRow();
$write->easyCell('Tanggal selesainya asesmen', 'align:L; font-size:12; border:LTBR');
$write->easyCell('', 'align:L; font-size:12; colspan:2; border:LTBR');
$write->printRow();
$write->endTable(5);

$write=new easyTable($pdf, '{190}', 'width:190; align:L; font-style:B; font-family:arial; font-size:12');
$write->easyCell('Beri tanda centang (V) di kolom yang sesuai untuk mencerminkan bukti yang diperoleh untuk menentukan Kompetensi asesi untuk setiap Unit Kompetensi.', 'align:L; font-size:12;');
$write->printRow();
$write->endTable(5);

$write=new easyTable($pdf, '{35,23,22,22,22,22,22,22}', 'width:190; align:L; font-family:arial; font-size:12');
$write->easyCell('Unit Kompetensi', 'align:C; valign:M;  bgcolor:#C6C6C6; font-style:B; font-size:10; border:LTBR');
$write->easyCell('Observasi demonstrasi', 'align:C; valign:M; bgcolor:#C6C6C6; font-style:B; font-size:10; border:LTBR');
$write->easyCell('Portofolio', 'align:C; valign:M; bgcolor:#C6C6C6; font-style:B; font-size:10; border:LTBR');
$write->easyCell('Pernyataan Pihak Ketiga Pertanyaan Wawancara', 'align:C; valign:M; bgcolor:#C6C6C6; font-style:B; font-size:10; border:LTBR');
$write->easyCell('Pertanyaan lisan', 'align:C; valign:M; bgcolor:#C6C6C6; font-style:B; font-size:10; border:LTBR');
$write->easyCell('Pertanyaan tertulis', 'align:C; valign:M; bgcolor:#C6C6C6; font-style:B; font-size:10; border:LTBR');
$write->easyCell('Proyek kerja', 'align:C; valign:M; bgcolor:#C6C6C6; font-style:B; font-size:10; border:LTBR');
$write->easyCell('Lainnya', 'align:C; valign:M; bgcolor:#C6C6C6; font-style:B; font-size:10; border:LTBR');
$write->printRow();
$sqlgetunitkompetensi="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sq[id]'";
$getunitkompetensi=$conn->query($sqlgetunitkompetensi);
//while unitkompetensi ==================================================================
while ($unk=$getunitkompetensi->fetch_assoc()){
	$unk[id] = 0;
	$write->easyCell($unk['judul'], 'align:L; valign:T; font-size:10; border:LTBR');
	// mendapatkan mapa 1
	$sqlgetmapa1b="SELECT * FROM `skema_mapa1b` WHERE `id_unitkompetensi`='$unk[id]' ORDER BY `metode1` DESC";
	$getmapa1b=$conn->query($sqlgetmapa1b);
	$gmapa1=$getmapa1b->fetch_assoc();
	if ($gmapa1['metode1']=="CL"){
		$write->easyCell('', 'img:../images/checkedonly.jpg, w5, h5; align:C; valign:M; font-size:10; border:LTBR');
	}else{
		$write->easyCell('', 'align:C; font-size:10; border:LTBR');
	}
	$sqlgetmapa1b2="SELECT * FROM `skema_mapa1b` WHERE `id_unitkompetensi`='$unk[id]' ORDER BY `metode4` DESC";
	$getmapa1b2=$conn->query($sqlgetmapa1b2);
	$gmapa12=$getmapa1b2->fetch_assoc();
	if ($gmapa12['metode4']=="VP"){
		$write->easyCell('', 'img:../images/checkedonly.jpg, w5, h5; align:C; valign:M; font-size:10; border:LTBR');
	}else{
		$write->easyCell('', 'align:C; font-size:10; border:LTBR');
	}
	$sqlgetmapa1b3="SELECT * FROM `skema_mapa1b` WHERE `id_unitkompetensi`='$unk[id]' ORDER BY `metode3t` DESC";
	$getmapa1b3=$conn->query($sqlgetmapa1b3);
	$gmapa13=$getmapa1b3->fetch_assoc();
	if ($gmapa13['metode3t']=="PW"){
		$write->easyCell('', 'img:../images/checkedonly.jpg, w5, h5; align:C; valign:M; font-size:10; border:LTBR');
	}else{
		$write->easyCell('', 'align:C; font-size:10; border:LTBR');
	}
	if ($gmapa13['metode3t']=="DPL"){
		$write->easyCell('', 'img:../images/checkedonly.jpg, w5, h5; align:C; valign:M; font-size:10; border:LTBR');
	}else{
		$write->easyCell('', 'align:C; font-size:10; border:LTBR');
	}
	if ($gmapa13['metode3t']=="DPT"){
		$write->easyCell('', 'img:../images/checkedonly.jpg, w5, h5; align:C; valign:M; font-size:10; border:LTBR');
	}else{
		$write->easyCell('', 'align:C; font-size:10; border:LTBR');
	}
	$sqlgetmapa1b4="SELECT * FROM `skema_mapa1b` WHERE `id_unitkompetensi`='$unk[id]' ORDER BY `metode2` DESC";
	$getmapa1b4=$conn->query($sqlgetmapa1b4);
	$gmapa14=$getmapa1b4->fetch_assoc();
	if ($gmapa14['metode2']=="DIT"){
		$write->easyCell('', 'img:../images/checkedonly.jpg, w5, h5; align:C; valign:M; font-size:10; border:LTBR');
	}else{
		$write->easyCell('', 'align:C; font-size:10; border:LTBR');
	}
	$sqlgetmapa1b5="SELECT * FROM `skema_mapa1b` WHERE `id_unitkompetensi`='$unk[id]' ORDER BY `metode6` DESC";
	$getmapa1b5=$conn->query($sqlgetmapa1b5);
	$gmapa15=$getmapa1b5->fetch_assoc();
	if ($gmapa15['metode6']=="CUP"){
		$write->easyCell('', 'img:../images/checkedonly.jpg, w5, h5; align:C; valign:M; font-size:10; border:LTBR');
	}else{
		$write->easyCell('', 'align:C; font-size:10; border:LTBR');
	}
	$write->printRow();
}
$write->endTable(0);

$write=new easyTable($pdf, '{50,140}', 'width:190; align:L; font-family:arial; font-size:12');
$write->easyCell('Rekomendasi hasil asesmen', 'align:L; valign:T; font-style:B; font-size:12; border:LTBR');
$write->easyCell('Kompeten / Belum kompeten', 'align:L; valign:T; font-size:12; border:LTBR');
$write->printRow();
$write->easyCell('Tindak lanjut yang dibutuhkan', 'align:L; valign:T; font-style:B; font-size:12; border:LTR');
$write->easyCell('', 'align:L; valign:T; font-style:B; font-size:12; border:LTR');
$write->printRow();
$write->easyCell('(Masukkan pekerjaan tambahan dan asesmen yang diperlukan untuk mencapai kompetensi) ', 'align:L; valign:T; font-size:12; border:LBR');
$write->easyCell('', 'align:L; valign:T; font-style:B; font-size:12; border:LBR');
$write->printRow();
$write->easyCell('Komentar/ Observasi oleh asesor', 'align:L; valign:T; font-style:B; font-size:12; border:LTBR');
$write->easyCell('', 'align:L; valign:T; font-style:B; font-size:12; border:LTBR');
$write->printRow();
$write->endTable(0);

$write=new easyTable($pdf, '{40,60,40,50}', 'width:190; align:L; font-family:arial; font-size:12');
$write->rowStyle('min-height:20');
$write->easyCell('Tanda tangan Asesi:', 'align:L; valign:T; font-style:B; font-size:12; border:LTBR');
$write->easyCell('', 'align:L; valign:T; font-style:B; font-size:12; border:LTBR');
$write->easyCell('Tanggal:', 'align:L; valign:T; font-style:B; font-size:12; border:LTBR');
$write->easyCell('', 'align:L; valign:T; font-style:B; font-size:12; border:LTBR');
$write->printRow();
$write->rowStyle('min-height:20');
$write->easyCell('Tanda tangan Asesor:', 'align:L; valign:T; font-style:B; font-size:12; border:LTBR');
$write->easyCell('', 'align:L; valign:T; font-style:B; font-size:12; border:LTBR');
$write->easyCell('Tanggal:', 'align:L; valign:T; font-style:B; font-size:12; border:LTBR');
$write->easyCell('', 'align:L; valign:T; font-style:B; font-size:12; border:LTBR');
$write->printRow();
$write->endTable(5);

$write=new easyTable($pdf, '{10,180}', 'width:190; align:L; font-family:arial; font-size:12');
$write->easyCell('LAMPIRAN DOKUMEN:', 'align:L; valign:T; font-size:12; colspan:2;');
$write->printRow();
$write->easyCell('1.', 'align:L; valign:T; font-size:12;');
$write->easyCell('Dokumen APL 01 Peserta', 'align:L; valign:T; font-size:12;');
$write->printRow();
$write->easyCell('2.', 'align:L; valign:T; font-size:12;');
$write->easyCell('Dokumen APL 02 Peserta', 'align:L; valign:T; font-size:12;');
$write->printRow();
$write->easyCell('3.', 'align:L; valign:T; font-size:12;');
$write->easyCell('Bukti-bukti berkualitas Peserta', 'align:L; valign:T; font-size:12;');
$write->printRow();
$write->easyCell('4.', 'align:L; valign:T; font-size:12;');
$write->easyCell('Tinjauan proses asesmen.', 'align:L; valign:T; font-size:12;');
$write->printRow();
$write->endTable(5);

$pdf->AliasNbPages();

//output file pdf
$fileoutputnya="Master-FR-AK-02-".$skemakkni.".pdf";
$pdf->Output($fileoutputnya,'D');
 
ob_end_flush();
?>