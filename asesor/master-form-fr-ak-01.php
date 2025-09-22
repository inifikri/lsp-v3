<?php
ini_set('display_errors',0); 
//ob_start();
include 'fpdf-easytable-master/fpdf.php';
include 'fpdf-easytable-master/exfpdf.php';
include 'fpdf-easytable-master/easyTable.php';
include "../config/koneksi.php";
include "../config/library.php";
include "../config/fungsi_indotgl.php";

/* $sqlasesi="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_GET[ida]'";
$asesi=$conn->query($sqlasesi);
$as=$asesi->fetch_assoc();
$namaasesi=ucwords(strtolower($as['nama']));

$sqljadwal="SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
$jadwal=$conn->query($sqljadwal);
$jdq=$jadwal->fetch_assoc();
$tgl_cetak = tgl_indo($jdq['tgl_asesmen']);

$sqltuk="SELECT * FROM `tuk` WHERE `id`='$jdq[tempat_asesmen]'";
$tuk=$conn->query($sqltuk);
$tq=$tuk->fetch_assoc(); */
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
if ($lq['fax']=="" OR $lq['fax']=="-"){
	$telpemail="Telp.: ".$lq['telepon']." Email : ".$lq['email'].", ".$lq['website'];
}else{
	$telpemail="Telp./Fax.: ".$lq['telepon']." / ".$lq['fax']." Email : ".$lq['email'].", ".$lq['website'];
}
$tampilperiode="Periode ".$jdq['periode']." Tahun ".$jdq['tahun']." Gelombang ".$jdq['gelombang'];
$nomorlisensi="Nomor Lisensi : ".$lq['no_lisensi'];

$alamatlsptampil=$alamatlsp." ".$alamatlsp2;
//$pdf->Cell(0, 5, '', '0', 1, 'C');
$pdf->Ln();
$write=new easyTable($pdf, '{30, 130, 30}', 'width:190; align:L; font-style:B; font-family:arial;');
$write->easyCell('', 'img:../images/logolsp.jpg, w25, h25; align:C; rowspan:3');
$write->easyCell($namalsp, 'align:C; font-size:14;');
$write->easyCell('', 'img:../images/logo-bnsp.jpg, w25, h25;align:C; rowspan:3');
$write->printRow();
$write->easyCell($nomorlisensi, 'align:C; font-size:10;');
$write->printRow();
$write->easyCell($alamatlsptampil.'
'.$telpemail, 'align:C; font-size:8;');
$write->printRow();
$write->endTable(5);


/* //===============================================================
$sqlgetak01="SELECT * FROM `asesmen_ak01` WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$jdq[id_skemakkni]' AND `id_jadwal`='$_GET[idj]'";
$getak01=$conn->query($sqlgetak01);
$jjw=$getak01->fetch_assoc(); */


$write=new easyTable($pdf, 1, 'width:180; align:C; font-style:B; font-size:12;font-family:arial;');
$write->easyCell('FR.AK.01. FORMULIR PERSETUJUAN ASESMEN DAN KERAHASIAAN', 'align:L;');
$write->printRow();
$write->endTable(5);

$write=new easyTable($pdf, '{60, 30, 90}', 'width:180; align:C; font-family:arial; font-size:10');
$write->easyCell('Persetujuan Asesmen ini untuk menjamin bahwa Asesi telah diberi arahan secara rinci tentang perencanaan dan proses asesmen', 'align:L; valign:M; font-size:11; colspan:3; border:LTBR;');
$write->printRow();
$write->easyCell('Skema Sertifikasi
(KKNI/Okupasi/Klaster)', 'align:L; valign:M; font-size:11; rowspan:2; border:LTR;');
$write->easyCell('Judul :', 'align:L; valign:M; font-size:11; border:LTBR;');
$write->easyCell($skemakkni, 'align:L; valign:M; font-size:11; font-style:B; border:LTBR;');
$write->printRow();
$write->easyCell('Nomor :', 'align:L; valign:M; font-size:11; border:LTBR;');
$write->easyCell($sq['kode_skema'], 'align:L; valign:M; font-size:11; font-style:B; border:LTBR;');
$write->printRow();
$write->easyCell('TUK', 'align:L; valign:M; font-size:11; border:LTBR;');
$sqltukjenis="SELECT `jenis_tuk` FROM `tuk_jenis` WHERE `id`='$tq[jenis_tuk]'";
$tukjenis=$conn->query($sqltukjenis);
$tukjen=$tukjenis->fetch_assoc();
$write->easyCell($tukjen['jenis_tuk'], 'align:L; valign:M; font-size:11; border:LTBR; colspan:2');
$write->printRow();

$write->easyCell('Nama Asesor', 'align:L; valign:M; font-size:11; border:LTBR;');
$write->easyCell('', 'align:L; valign:M; font-size:11; font-style:B; border:LTBR; colspan:2');
$write->printRow();
$write->easyCell('Nama Asesi', 'align:L; valign:M; font-size:11; border:LTBR;');
$write->easyCell('', 'align:L; valign:M; font-size:11; font-style:B; border:LTBR; colspan:2');
$write->printRow();
$write->endTable(0);
$write=new easyTable($pdf, '{60, 10, 50, 10, 50}', 'width:180; align:C; font-family:arial; font-size:10');
$write->easyCell('Bukti yang akan dikumpulkan', 'align:L; valign:M; font-size:11; border:LTBR; rowspan:4');
$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; border:LTB;');
$write->easyCell('TL : Verifikasi Portfolio', 'align:L; valign:M; font-size:11; border:TBR;');
$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; border:LTB;');
$write->easyCell('L : Observasi Langsung', 'align:L; valign:M; font-size:11; border:TBR;');
$write->printRow();
$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; border:LT;');
$write->easyCell('T : Hasil Tes Tulis', 'align:L; valign:M; font-size:11; border:TR; colspan:3;');
$write->printRow();
$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; border:L;');
$write->easyCell('L : Hasil Tes Lisan', 'align:L; valign:M; font-size:11; border:R; colspan:3;');
$write->printRow();
$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; border:L;');
$write->easyCell('L : Hasil Wawancara', 'align:L; valign:M; font-size:11; border:BR; colspan:3;');
$write->printRow();
$write->endTable(0);
$write=new easyTable($pdf, '{60, 40, 5, 75}', 'width:180; align:C; font-family:arial; font-size:10');
$write->easyCell('Pelaksanaan asesmen disepakati pada:', 'align:L; valign:M; font-size:11; border:LTBR; rowspan:3');
$write->easyCell('Hari/ Tanggal', 'align:L; valign:M; font-size:11; border:LTB;');
$write->easyCell(':', 'align:L; valign:M; font-size:11; border:TB; ');

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

$jadwal = $dayList[$day].", ".$tgl_cetak;
$jamasesmen = "Pukul ".$jdq['jam_asesmen']." - "; */

$write->easyCell('', 'align:L; valign:M; font-size:11; font-style:B; border:TBR; ');
$write->printRow();
$write->easyCell('Waktu', 'align:L; valign:M; font-size:11; border:LTB;');
$write->easyCell(':', 'align:L; valign:M; font-size:11; border:TB; ');
$write->easyCell('', 'align:L; valign:M; font-style:B; font-size:11; border:TBR; ');
$write->printRow();
$write->easyCell('TUK', 'align:L; valign:M; font-size:11; border:LTB;');
$write->easyCell(':', 'align:L; valign:M; font-size:11; border:TB; ');
$write->easyCell('', 'align:L; valign:M; font-style:B;font-size:11; border:TBR; ');
$write->printRow();
$write->endTable(0);



$write=new easyTable($pdf, '{180}', 'width:180; align:C; font-family:arial; font-size:10');
$write->easyCell('Asesor :', 'align:L;  valign:M; font-style:B; font-size:11; border:LTR;');
$write->printRow();
$write->easyCell('Menyatakan tidak akan membuka hasil pekerjaan yang saya peroleh karena penugasan saya sebagai Asesor dalam pekerjaan Asesmen kepada siapapun atau organisasi apapun selain kepada pihak yang berwenang sehubungan dengan kewajiban saya sebagai Asesor yang ditugaskan oleh LSP.', 'align:L;  valign:M; font-style:I; font-size:11; border:LRB;');
$write->printRow();
$write->easyCell('Asesi :', 'align:L;  valign:M; font-style:B; font-size:11; border:LTR;');
$write->printRow();
$write->easyCell('Saya setuju mengikuti asesmen dengan pemahaman bahwa informasi yang dikumpulkan hanya digunakan untuk pengembangan profesional dan hanya dapat diakses oleh orang tertentu saja.', 'align:L;  valign:M; font-style:I; font-size:11; border:LRB;');
$write->printRow();
$write->endTable(0);
$write=new easyTable($pdf, '{40,5,70,20,45}', 'width:180; align:C; font-family:arial; font-size:10');

$sqlidentitas="SELECT * FROM `identitas`";
$identitas=$conn->query($sqlidentitas);
$iden=$identitas->fetch_assoc();
// tandatangan Asesor LSP

	$write->rowStyle('min-height:25');
	$write->easyCell('Tandatangan Asesor', 'align:L; valign:B; font-size:11; border:LT;');
	$write->easyCell(':', 'align:L; valign:B; font-size:11; border:T;');
	$write->easyCell('', 'align:L; valign:B; font-size:11; border:T;');
	$write->easyCell('Tanggal :', 'align:L; valign:B; font-size:11; border:T;');
	$write->easyCell('.......................................', 'align:L; valign:B; font-size:11; border:TR;');
	$write->printRow();

// tandatangan Asesi

	$write->rowStyle('min-height:25');
	$write->easyCell('Tandatangan Asesi', 'align:L; valign:B; font-size:11; border:LB;');
	$write->easyCell(':', 'align:L; valign:B; font-size:11; border:B;');
	$write->easyCell('', 'align:L; valign:B; font-size:11; border:B;');
	$write->easyCell('Tanggal :', 'align:L; valign:B; font-size:11; border:B;');
	$write->easyCell('.......................................', 'align:L; valign:B; font-size:11; border:BR;');
	$write->printRow();


$write->endTable(5);


$pdf->AliasNbPages();

//output file pdf
$fileoutputnya="Master-FR-AK-01-".$skemakkni.".pdf";
$pdf->Output($fileoutputnya,'D');
 
ob_end_flush();
?>