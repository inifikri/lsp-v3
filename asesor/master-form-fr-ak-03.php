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
$write->easyCell('FR.AK.03. UMPAN BALIK DAN CATATAN ASESMEN', 'align:L;');
$write->printRow();
$write->endTable(5);

$write=new easyTable($pdf, '{30, 5, 70, 30, 5, 60}', 'width:190; align:L; font-style:B; font-family:arial; font-size:12');
$write->easyCell('Nama Asesi', 'align:L; valign:M; font-size:12;');
$write->easyCell(':', 'align:C; valign:M; font-size:12;');
$write->easyCell('', 'align:L;  valign:M; font-size:12;');
$write->easyCell('Hari / Tanggal', 'align:L; valign:M; font-size:12;');
$write->easyCell(':', 'align:C; valign:M; font-size:12;');
/* $tanggal = $jdq['tgl_asesmen'];
$day = date('D', strtotime($tanggal));
$dayList = array(
	'Sun' => 'Minggu',
	'Mon' => 'Senin',
	'Tue' => 'Selasa',
	'Wed' => 'Rabu',
	'Thu' => 'Kamis',
	'Fri' => 'Jumat',
	'Sat' => 'Sabtu'
);
$jadwal = $dayList[$day].", ".$tgl_cetak; */

$write->easyCell('', 'align:L; valign:M; font-size:12;');
$write->printRow();

$write->easyCell('Nama Asesor', 'align:L; valign:M; font-size:12;');
$write->easyCell(':', 'align:C; valign:M; font-size:12;');
$write->easyCell('', 'align:L; valign:M; font-size:12;');
$write->easyCell('Waktu', 'align:L; valign:M; font-size:12;');
$write->easyCell(':', 'align:C; valign:M; font-size:12;');
$write->easyCell('', 'align:L; valign:M; font-size:12;');
$write->printRow();

$write->endTable(5);

/* $sqlgetak01="SELECT * FROM `asesmen_ak03` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
$getak01=$conn->query($sqlgetak01);
$jjw=$getak01->fetch_assoc(); */

$write=new easyTable($pdf, '{110, 15, 15, 50}', 'width:190; align:L; font-family:arial; font-size:12');
$write->easyCell('Umpan balik dari Asesi (diisi oleh Asesi setelah pengambilan keputusan) :', 'align:L;  valign:M; colspan:4; font-size:11;');
$write->printRow();
$write->easyCell('Komponen', 'align:C; valign:M; rowspan:2; font-style:B; font-size:11; border:LTBR;');
$write->easyCell('Hasil', 'align:C; valign:M; colspan:2; font-style:B; font-size:11; border:LTBR;');
$write->easyCell('Catatan/Komentar Asesi', 'align:C;  valign:M; rowspan:2; font-style:B; font-size:11; border:LTBR;');
$write->printRow();
$write->easyCell('Ya', 'align:C; valign:M; font-style:B; font-size:11; border:LTBR;');
$write->easyCell('Tidak', 'align:C;  valign:M; font-style:B; font-size:11; border:LTBR;');
$write->printRow();
$write->rowStyle('min-height:15');
$write->easyCell('Saya mendapatkan penjelasan yang cukup memadai mengenai proses asesmen/uji kompetensi', 'align:L; valign:T; font-size:11; border:LTBR;');

		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');

$write->easyCell('', 'align:L;  valign:T; font-size:11; border:LTBR;');
$write->printRow();
$write->rowStyle('min-height:15');
$write->easyCell('Saya diberikan kesempatan untuk mempelajari standar kompetensi yang akan diujikan dan menilai diri sendiri terhadap pencapaiannya', 'align:L; valign:T; font-size:11; border:LTBR;');

		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');

$write->easyCell('', 'align:L;  valign:T; font-size:11; border:LTBR;');
$write->printRow();
$write->rowStyle('min-height:15');
$write->easyCell('Asesor memberikan kesempatan untuk mendiskusikan/menegosiasikan metoda, instrumen dan sumber asesmen serta jadwal asesmen', 'align:L; valign:T; font-size:11; border:LTBR;');

		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');

$write->easyCell('', 'align:L;  valign:T; font-size:11; border:LTBR;');
$write->printRow();
$write->rowStyle('min-height:15');
$write->easyCell('Asesor berusaha menggali seluruh bukti pendukung yang sesuai dengan latar belakang pelatihan dan pengalaman yang saya miliki', 'align:L; valign:T; font-size:11; border:LTBR;');

		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');

$write->easyCell('', 'align:L;  valign:T; font-size:11; border:LTBR;');
$write->printRow();
$write->rowStyle('min-height:15');
$write->easyCell('Saya sepenuhnya diberikan kesempatan untuk mendemonstrasikan kompetensi yang saya miliki selama asesmen', 'align:L; valign:T; font-size:11; border:LTBR;');

		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');

$write->easyCell('', 'align:L;  valign:T; font-size:11; border:LTBR;');
$write->printRow();
$write->rowStyle('min-height:15');
$write->easyCell('Saya mendapatkan penjelasan yang memadai mengenai keputusan asesmen', 'align:L; valign:T; font-size:11; border:LTBR;');

		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');

$write->easyCell('', 'align:L;  valign:T; font-size:11; border:LTBR;');
$write->printRow();
$write->rowStyle('min-height:15');
$write->easyCell('Asesor memberikan umpan balik yang mendukung setelah asesmen serta tindak lanjutnya', 'align:L; valign:T; font-size:11; border:LTBR;');

		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');

$write->easyCell('', 'align:L;  valign:T; font-size:11; border:LTBR;');
$write->printRow();
$write->rowStyle('min-height:15');
$write->easyCell('Asesor bersama saya mempelajari semua dokumen asesmen serta menandatanganinya', 'align:L; valign:T; font-size:11; border:LTBR;');

		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');

$write->easyCell('', 'align:L;  valign:T; font-size:11; border:LTBR;');
$write->printRow();
$write->rowStyle('min-height:15');
$write->easyCell('Saya mendapatkan jaminan kerahasiaan hasil asesmen serta penjelasan penanganan dokumen asesmen', 'align:L; valign:T; font-size:11; border:LTBR;');

		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');

$write->easyCell('', 'align:L;  valign:T; font-size:11; border:LTBR;');
$write->printRow();
$write->rowStyle('min-height:15');
$write->easyCell('Asesor menggunakan keterampilan komunikasi yang efektif selama asesmen', 'align:L; valign:T; font-size:11; border:LTBR;');

		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');

$write->easyCell('', 'align:L;  valign:T; font-size:11; border:LTBR;');
$write->printRow();
$write->rowStyle('min-height:20');
$write->easyCell('Catatan/komentar lainnya (apabila ada) :
', 'align:L;  valign:T; colspan:4; font-size:11; border:LTBR;');
$write->printRow();
$write->endTable(5);

$pdf->AliasNbPages();

//output file pdf
$fileoutputnya="Master-FR-AK-03-".$skemakkni.".pdf";
$pdf->Output($fileoutputnya,'D');
 
ob_end_flush();
?>