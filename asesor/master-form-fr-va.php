<?php
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

/* $sqlwil1b="SELECT * FROM `data_wilayah` WHERE `id_wil`='$tq[id_wilayah]'";
$wilayah1b=$conn->query($sqlwil1b);
$wil1b=$wilayah1b->fetch_assoc();
$sqlwil2b="SELECT * FROM `data_wilayah` WHERE `id_wil`='$wil1b[id_induk_wilayah]'";
$wilayah2b=$conn->query($sqlwil2b);
$wil2b=$wilayah2b->fetch_assoc();
$sqlwil3b="SELECT * FROM `data_wilayah` WHERE `id_wil`='$wil2b[id_induk_wilayah]'";
$wilayah3b=$conn->query($sqlwil3b);
$wil3b=$wilayah3b->fetch_assoc(); */


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


$write=new easyTable($pdf, '{30, 70, 30, 60}', 'width:190; align:L; font-style:B; font-family:arial; font-size:11');
$write->rowStyle('min-height:10');
$write->easyCell('Tim Validasi', 'align:L; rowspan:2; border:LTBR');
$write->easyCell('1. ', 'align:L; font-size:11; border:LTBR');
$write->easyCell('Hari/ Tanggal', 'align:L; font-size:11; border:LTBR');
/* $daynya=date("l",$jdq['tgl_asesmen']);
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
} */

$write->easyCell('', 'align:L; font-size:11; border:LTBR');
$write->printRow();
$write->rowStyle('min-height:10');

$write->easyCell('2. ', 'align:L; font-size:11; border:LTBR');
$write->easyCell('Tempat', 'align:L; font-size:11; border:LTBR');
$write->easyCell('', 'align:L; font-size:11; border:LTBR');
$write->printRow();
$write->endTable(0);
$write=new easyTable($pdf, '{30, 5, 7, 38, 7, 40, 7, 56}', 'width:190; align:L; font-style:B; font-family:arial; font-size:11');
$write->rowStyle('min-height:10');
$write->easyCell('Periode', 'align:L; valign:M; border:LTBR');
$write->easyCell(':', 'align:C; valign:M; font-size:11; border:LTB');
/* $sqlcekmkva="SELECT * FROM `mkva` WHERE `id_jadwal`='$_GET[idj]'";
$cekmkva=$conn->query($sqlcekmkva);
$cmkv=$cekmkva->fetch_assoc(); */

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');
	$write->easyCell('Sebelum Asesmen', 'align:L; valign:M; font-size:11; border:TB');
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');
	$write->easyCell('Pada Saat Asesmen', 'align:L; valign:M; font-size:11; border:TB');
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');
	$write->easyCell('Setelah Asesmen', 'align:L; valign:M; font-size:11; border:TBR');

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

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');

$write->easyCell('Bagian dari proses penjaminan mutu organisasi', 'align:L; border:TBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');

$write->easyCell('Internal organisasi', 'align:L; border:TBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');

$write->easyCell('Panel  asesmen', 'align:L; border:TBR;');
$write->printRow();
$write->rowStyle('min-height:10');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');

$write->easyCell('Mengantisipasi risiko', 'align:L; border:TBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');

$write->easyCell('Eksternal organisasi', 'align:L; border:TBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');

$write->easyCell('Pertemuan moderasi', 'align:L; border:TBR;');
$write->printRow();
$write->rowStyle('min-height:10');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');

$write->easyCell('Memenuhi persyaratan BNSP', 'align:L; border:TBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');

$write->easyCell('Proses lisensi/re lisensi', 'align:L; border:TBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');

$write->easyCell('Mengkaji perangkat asesmen', 'align:L; border:TBR;');
$write->printRow();
$write->rowStyle('min-height:10');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');

$write->easyCell('Memastikan kesesuaian bukti-bukti', 'align:L; border:TBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');

$write->easyCell('Dengan kolega asesor', 'align:L; border:TBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');

$write->easyCell('Acuan pembanding', 'align:L; border:TBR;');
$write->printRow();
$write->rowStyle('min-height:10');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');

$write->easyCell('Meningkatkan kualitas asesmen', 'align:L; border:TBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');

$write->easyCell('Kolega dari organisasi pelatihan atau asesmen', 'align:L; border:TBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');

$write->easyCell('Pengujian lapangan dan uji coba perangkat asesmen', 'align:L; border:TBR;');
$write->printRow();
$write->rowStyle('min-height:10');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');

$write->easyCell('Mengevaluasi kualitas perangkat asesmen', 'align:L; border:TBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');


	$write->easyCell('…………………………', 'align:L; valign:B; border:TBR;');


	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');

$write->easyCell('Umpan balik dari klien', 'align:L; border:TBR;');
$write->printRow();
$write->rowStyle('min-height:10');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');


	$write->easyCell('………………………………', 'align:L; valign:B; border:TBR;');

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

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; rowspan:3; border:TBR;');

$write->easyCell('Asesor kompetensi (wajib)', 'align:L; valign:T; rowspan:3; border:TBR;');

	$write->easyCell('1.', 'align:L; colspan:2; border:TBR;');


	$write->easyCell('', 'align:L; colspan:2; border:TBR;');

$write->printRow();
$write->rowStyle('min-height:10');

	$write->easyCell('2.', 'align:L; colspan:2; border:TBR;');


	$write->easyCell('', 'align:L; colspan:2; border:TBR;');

$write->printRow();
$write->rowStyle('min-height:10');

	$write->easyCell('3.', 'align:L; colspan:2; border:TBR;');


	$write->easyCell('', 'align:L; colspan:2; border:TBR;');

$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell('', 'align:L; border:LR; font-style:B;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TBR;');

$write->easyCell('Lead Asesor', 'align:L; border:TBR;');

	$write->easyCell('', 'align:L;colspan:2;  border:TBR;');


	$write->easyCell('', 'align:L;colspan:2;  border:TBR;');

$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell('', 'align:L; border:LR; font-style:B;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TBR;');

$write->easyCell('Manager, supervisor', 'align:L; border:TBR;');

	$write->easyCell('', 'align:L;colspan:2;  border:TBR;');


	$write->easyCell('', 'align:L;colspan:2;  border:TBR;');

$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell('', 'align:L; border:LR; font-style:B;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TBR;');

$write->easyCell('Tenaga ahli di bidangnya', 'align:L; border:TBR;');

	$write->easyCell('', 'align:L;colspan:2;  border:TBR;');


	$write->easyCell('', 'align:L;colspan:2;  border:TBR;');

$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell('', 'align:L; border:LR; font-style:B;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TBR;');

$write->easyCell('Koord. Pelatihan', 'align:L; border:TBR;');

	$write->easyCell('', 'align:L;colspan:2;  border:TBR;');


	$write->easyCell('', 'align:L;colspan:2;  border:TBR;');

$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell('', 'align:L; border:LR; font-style:B;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TBR;');

$write->easyCell('Anggota asosiasi industry/profesi', 'align:L; border:TBR;');

	$write->easyCell('', 'align:L;colspan:2;  border:TBR;');


	$write->easyCell('', 'align:L;colspan:2;  border:TBR;');

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

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');

$write->easyCell('Standar kompetensi', 'align:L; border:TR; font-style:B;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');

$write->easyCell('Skema sertifikasi', 'align:L; border:TR; font-style:B;');
$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell('', 'align:L; border:LR; font-style:B;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');

$write->easyCell('SOP/IK', 'align:L; border:TR; font-style:B;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');

$write->easyCell('SKKNI/SK3/SKI', 'align:L; border:TR; font-style:B;');
$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell('', 'align:L; border:LR; font-style:B;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');

$write->easyCell('Manual Instruction/book', 'align:L; border:TR; font-style:B;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');

$write->easyCell('Perangkat asesmen', 'align:L; border:TR; font-style:B;');
$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell('', 'align:L; border:LR; font-style:B;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');

$write->easyCell('Standar Kinerja', 'align:L; border:TR; font-style:B;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');

$write->easyCell('Peraturan/Pedoman', 'align:L; border:TR; font-style:B;');
$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell('', 'align:L; border:LRB; font-style:B;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');


	$write->easyCell('………………………………….', 'align:L; valign:B; border:TRB; font-style:B;');

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

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');

$write->easyCell('PRO AKTIF', 'align:L; border:TBR; font-style:B;');
$write->printRow();
$write->rowStyle('min-height:10');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');

$write->easyCell('ACTIVE LISTENING', 'align:L; border:TBR; font-style:B;');
$write->printRow();
$write->rowStyle('min-height:10');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');


	$write->easyCell('', 'align:L; border:TBR; font-style:B;');

$write->printRow();
$write->rowStyle('min-height:10');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TB;');


	$write->easyCell('', 'align:L; border:TBR; font-style:B;');

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

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');


	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');


	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');


	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');


	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');


	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');


	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');


	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell('2.', 'align:C; valign:T; border:LTBR;');
$write->easyCell('Rencana asesmen', 'align:L; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');


	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');


	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');


	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell('3.', 'align:C; valign:T; border:LTBR;');
$write->easyCell('Interpretasi standar kompetensi', 'align:L; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell('4.', 'align:C; valign:T; border:LTBR;');
$write->easyCell('Interpretasi acuan pembanding lainnya', 'align:L; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell('5.', 'align:C; valign:T; border:LTBR;');
$write->easyCell('Penyeleksian dan penerapan metode asesmen', 'align:L; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell('6.', 'align:C; valign:T; border:LTBR;');
$write->easyCell('Penyeleksian dan penerapan perangkat asesmen', 'align:L; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell('7.', 'align:C; valign:T; border:LTBR;');
$write->easyCell('Bukti-bukti yang dikumpulkan', 'align:L; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell('8.', 'align:C; valign:T; border:LTBR;');
$write->easyCell('Proses pengambilan keputusan', 'align:L; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');

$write->printRow();
$write->endTable(5);

$pdf->AddPage();

$write=new easyTable($pdf, '{10,10,85,85}', 'width:190; align:L; font-family:arial; font-size:12');
$write->easyCell('3.', 'align:C; border:LTBR; bgcolor:#C6C6C6; font-style:B; font-size:12');
$write->easyCell('Memberikan kontribusi untuk hasil asesmen', 'align:L; border:LTBR; colspan:3; bgcolor:#C6C6C6; font-style:B;');
$write->printRow();

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


$write=new easyTable($pdf, '{10,10,80,40,50}', 'width:190; align:L; font-family:arial; font-size:12');
$write->rowStyle('min-height:10');

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


$pdf->AliasNbPages();

//output file pdf
$fileoutputnya="Master-FR-VA-".$skemakkni.".pdf";
$pdf->Output($fileoutputnya,'D');
 
ob_end_flush();
?>