<?php
ini_set('display_errors',0); 
/*error_reporting(error_reporting() & ~E_NOTICE);
header('Content-type: application/pdf');
session_start();
if (!isset($_SESSION['namauser'])){
header("Location:index.php");
}*/
ob_start();
include "../config/koneksi.php";
include "../config/library.php";
include "../config/fungsi_indotgl.php";
include 'fpdf-easytable-master/fpdf.php';
include 'fpdf-easytable-master/exfpdf.php';
include 'fpdf-easytable-master/easyTable.php';
$ida = isset($_GET[ida]) ? $_GET[ida] : '';
$sqlasesi="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$ida'";
$asesi=$conn->query($sqlasesi);
$as=$asesi->fetch_assoc();
$sqljadwal="SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
$jadwal=$conn->query($sqljadwal);
$jdq=$jadwal->fetch_assoc();
$sqltuk="SELECT * FROM `tuk` WHERE `id`='$jdq[tempat_asesmen]'";
$tuk=$conn->query($sqltuk);
$tq=$tuk->fetch_assoc(); 
$sqllsp="SELECT * FROM `lsp`";
$lsp=$conn->query($sqllsp);
$lq=$lsp->fetch_assoc();
$sqlskema="SELECT * FROM `skema_kkni` WHERE `id`='$_GET[idsk]'";
$skema=$conn->query($sqlskema);
$sq=$skema->fetch_assoc();
$skemakkni=$sq['judul'];

/* $sqlasesmenasesi2="SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$jdq[id]' ORDER BY `id_asesi` ASC";
$asesmenasesi2=$conn->query($sqlasesmenasesi2);
$assm = $asesmenasesi2->fetch_assoc();
$sqladmin="SELECT * FROM `users` WHERE `username`='$assm[id_admin]' AND `blokir`='N'";
$admin=$conn->query($sqladmin);
$adm=$admin->fetch_assoc(); */
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
$namamanajer=$tq['penanggungjawab'];
$fakultas = "ASESOR";
$tgl_cetak = tgl_indo($jdq['tgl_asesmen']);
$tglcetak = trim($wil2b['nm_wil']).", ".$tgl_cetak;
$tanggal = $jdq['tgl_asesmen'];
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
$jamasesmen = "Pukul ".$jdq['jam_asesmen']." - ";
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
$pdf=new exFPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',10);
$pdf->AddFont('FontUTF8','','Arimo-Regular.php'); 
$pdf->AddFont('FontUTF8','B','Arimo-Bold.php');
$pdf->AddFont('FontUTF8','I','Arimo-Italic.php');
$pdf->AddFont('FontUTF8','BI','Arimo-BoldItalic.php');
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
//Cetak Barcode
/* $getkodeverifikasi=$as['no_pendaftaran'].$jdq['id'].date("Y-m-d")."FORM-APL-01";
$kodeverifikasi=md5($getkodeverifikasi);
$kodever=substr($kodeverifikasi,-8); */
//$pdf->Code39(10,280,$kodever,1,7);
// riwayat cetak
/* $alamatip=$_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
$sqllogcetak="INSERT INTO `logcetak`(`kodeverifikasi`, `id_jadwal`, `id_asesi`, `form`, `ip`) VALUES ('$kodever','$jdq[id]','$as[no_pendaftaran]','FORM-APL-01', '$alamatip')";
$logcetak=$conn->query($sqllogcetak); */
$bulanasesmen=substr($jdq['tgl_asesmen'],5,2);
switch ($bulanasesmen){
default:
	$bulanromawi='';
break;
case "01":
	$bulanromawi='I';
break;
case "02":
	$bulanromawi='II';
break;
case "03":
	$bulanromawi='III';
break;
case "04":
	$bulanromawi='IV';
break;
case "05":
	$bulanromawi='V';
break;
case "06":
	$bulanromawi='VI';
break;
case "07":
	$bulanromawi='VII';
break;
case "08":
	$bulanromawi='VIII';
break;
case "09":
	$bulanromawi='IX';
break;
case "10":
	$bulanromawi='X';
break;
case "11":
	$bulanromawi='XI';
break;
case "12":
	$bulanromawi='XII';
break;
}
$write=new easyTable($pdf, 1, 'width:180;  font-size:12;font-family:arial;');
$write->easyCell('FR-APL-01. FORMULIR PERMOHONAN SERTIFIKASI KOMPETENSI', 'align:L; font-style:B;');
$write->printRow();
$write->easyCell('Bagian 1: Rincian Data Pemohon Sertifikasi', 'align:L; font-style:B;');
$write->printRow();
$write->easyCell('Pada bagian ini, cantumkan data pribadi, data pendidikan formal serta data pekerjaan anda pada saat ini.', 'align:L; font-size:10;');
$write->printRow();
$write->endTable(5);
$write=new easyTable($pdf, '{10, 40, 5, 15, 5, 40, 15, 5, 45}', 'width:180; font-family:arial; font-size:10');
$write->easyCell('a.', 'align:L; font-style:B;');
$write->easyCell('Data Pribadi', 'align:L; font-style:B; colspan:8;');
$write->printRow();
$write->easyCell('', 'align:L;');
$write->easyCell('Nama Lengkap', 'align:L;');
$write->easyCell(':', 'align:C;');
$write->easyCell('', 'align:L; colspan:6; border:B;');
$write->printRow();
$write->easyCell('', 'align:L;');
$write->easyCell('Tempat/ Tgl. Lahir', 'align:L;');
$write->easyCell(':', 'align:C;');
$write->easyCell('', 'align:L; colspan:6; border:B;');
$write->printRow();
$write->easyCell('', 'align:L;');
$write->easyCell('Jenis Kelamin', 'align:L;');
$write->easyCell(':', 'align:C;');

$jeniskelamin="Laki-laki / Perempuan*";

$write->easyCell(strtoupper($jeniskelamin), 'align:L; colspan:6; border:B;');
$write->printRow();
$write->easyCell('', 'align:L;');
$write->easyCell('Kebangsaan', 'align:L;');
$write->easyCell(':', 'align:C;');
$write->easyCell('', 'align:L; colspan:6; border:B;');
$write->printRow();
$write->easyCell('', 'align:L;');
$write->easyCell('Alamat Rumah', 'align:L;');
$write->easyCell(':', 'align:C;');

$write->easyCell('', 'align:L; colspan:6; border:B;');
$write->printRow();
$write->easyCell('', 'align:L;');
$write->easyCell('No. Telp./ Email', 'align:L;');
$write->easyCell(':', 'align:C;');
$write->easyCell('Rumah', 'align:L;');
$write->easyCell(':', 'align:C;');
$write->easyCell('', 'align:L; border:B;');
$write->easyCell('Kantor', 'align:L;');
$write->easyCell(':', 'align:C;');
$write->easyCell('', 'align:L; border:B;');
$write->printRow();
$write->easyCell('', 'align:L;');
$write->easyCell('', 'align:L;');
$write->easyCell('', 'align:C;');
$write->easyCell('HP', 'align:L;');
$write->easyCell(':', 'align:C;');
$write->easyCell('', 'align:L; border:B;');
$write->easyCell('Email', 'align:L;');
$write->easyCell(':', 'align:C;');
$write->easyCell('', 'align:L; border:B;');
$write->printRow();
$write->easyCell('', 'align:L;');
$write->easyCell('Pendidikan Terakhir', 'align:L;');
$write->easyCell(':', 'align:C;');

$write->easyCell('', 'align:L; colspan:6; border:B;');
$write->printRow();
$write->endTable(5);
$write=new easyTable($pdf, '{10, 40, 5, 15, 5, 40, 15, 5, 45}', 'width:180; font-family:arial; font-size:10');
$write->easyCell('b.', 'align:L; font-style:B;');
$write->easyCell('Data Pekerjaan Sekarang', 'align:L; font-style:B; colspan:8;');
$write->printRow();
$write->easyCell('', 'align:L;');
$write->easyCell('Nama lembaga/ perusahaan', 'align:L;');
$write->easyCell(':', 'align:C;');
$write->easyCell('', 'align:L; colspan:6; border:B;');
$write->printRow();
$write->easyCell('', 'align:L;');
$write->easyCell('Jabatan', 'align:L;');
$write->easyCell(':', 'align:C;');

$write->easyCell('', 'align:L; colspan:6; border:B;');
$write->printRow();
$write->easyCell('', 'align:L;');
$write->easyCell('Alamat', 'align:L;');
$write->easyCell(':', 'align:C;');
$write->easyCell('', 'align:L; colspan:6; border:B;');
$write->printRow();
$write->easyCell('', 'align:L;');
$write->easyCell('No. Telp./ Fax./ Email', 'align:L;');
$write->easyCell(':', 'align:C;');
$write->easyCell('Telp.', 'align:L;');
$write->easyCell(':', 'align:C;');
$write->easyCell('', 'align:L; border:B;');
$write->easyCell('Fax.', 'align:L;');
$write->easyCell(':', 'align:C;');
$write->easyCell('', 'align:L; border:B;');
$write->printRow();
$write->easyCell('', 'align:L;');
$write->easyCell('', 'align:L;');
$write->easyCell('', 'align:C;');
$write->easyCell('Email', 'align:L;');
$write->easyCell(':', 'align:C;');
$write->easyCell('', 'align:L; border:B; colspan:4;');
$write->printRow();
$write->endTable(5);
// Bagian 2
$write=new easyTable($pdf, 1, 'width:180;  font-size:12;font-family:arial;');
$write->easyCell('Bagian 2: Data Sertifikasi', 'align:L; font-style:B;');
$write->printRow();
$write->easyCell('Tuliskan Judul dan Nomor Skema Sertifikasi yang anda ajukan berikut Daftar Unit Kompetensi sesuai kemasan pada skema sertifikasi untuk mendapatkan pengakuan sesuai dengan latar belakang pendidikan, pelatihan serta pengalaman kerja yang anda miliki.', 'align:L; font-size:10;');
$write->printRow();
$write->endTable(5);
$write=new easyTable($pdf, '{40, 15, 5, 10, 110}', 'width:180;  font-size:12;font-family:arial;');
$write->easyCell('Skema Sertifikasi  
(KKNI/Okupasi/Klaster)', 'align:L; font-size:10; rowspan:2; border:LTBR;');
$write->easyCell('Judul', 'align:L; font-size:10; border:LTBR;');
$write->easyCell(':', 'align:C; font-size:10; border:LTBR;');
$write->easyCell($sq['judul'], 'align:L; font-size:10; colspan:2; border:LTBR;');
$write->printRow();
$write->easyCell('Nomor', 'align:L; font-size:10; border:LTBR;');
$write->easyCell(':', 'align:C; font-size:10; border:LTBR;');
$write->easyCell($sq['kode_skema'], 'align:L; font-size:10; colspan:2; border:LTBR;');
$write->printRow();

$write->easyCell('Tujuan Asesmen', 'align:L; valign:T; font-size:10; colspan:2; rowspan:5; border:LTBR;');
$write->easyCell(':', 'align:C; font-size:10;; border:LTR;');
$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; font-size:10; border:LTB;');
$write->easyCell('Sertifikasi', 'align:L; font-size:10; border:TBR;');
$write->printRow();
$write->easyCell('', 'align:C; font-size:10; border:LR;');
$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; font-size:10; border:LTB;');
$write->easyCell('Sertifikasi Ulang', 'align:L; font-size:10; border:TBR;');
$write->printRow();
$write->easyCell('', 'align:C; font-size:10; border:LR;');
$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; font-size:10; border:LTB;');
$write->easyCell('Pengakuan Kompetensi Terkini (PKT)', 'align:L; font-size:10; border:TBR;');
$write->printRow();
$write->easyCell('', 'align:C; font-size:10; border:LR;');
$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; font-size:10; border:LTB;');
$write->easyCell('Rekognisi Pembelajaran Lampau', 'align:L; font-size:10; border:TBR;');
$write->printRow();
$write->easyCell('', 'align:C; font-size:10; border:LBR;');
$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; font-size:10; border:LTB;');
$write->easyCell('Lainnya :', 'align:L; font-size:10; border:TBR;');
$write->printRow();

$write->endTable(5);
$pdf->AliasNbPages();
$pdf->AddPage();
$write=new easyTable($pdf, '{10, 35, 95, 40}', 'width:180;  font-size:12;font-family:arial;');
$write->easyCell('Daftar Unit Kompetensi sesuai kemasan:', 'align:L; font-size:10; font-style:B; colspan:4');
$write->printRow();
$write->easyCell('No.', 'align:C; valign:M; font-size:10; font-style:B; border:LTBR; bgcolor:#DFDFDF;');
$write->easyCell('Kode Unit', 'align:C; valign:M; font-size:10; font-style:B; border:LTBR; bgcolor:#DFDFDF;');
$write->easyCell('Judul Unit', 'align:C; valign:M; font-size:10; font-style:B; border:LTBR; bgcolor:#DFDFDF;');
$write->easyCell('Jenis Standar (Standar 
Khusus/Standar
Internasional/SKKNI)', 'align:C; valign:M; font-size:10; font-style:B; border:LTBR; bgcolor:#DFDFDF;');
$write->printRow();
$sqlunitkompetensi="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sq[id]' ORDER BY `kode_unit` ASC";
$unitkompetensi=$conn->query($sqlunitkompetensi);
$nounk=1;
while ($unk=$unitkompetensi->fetch_assoc()){
	
	$nounkt=$nounk.".";
	$getskkninya="SELECT * FROM `skkni` WHERE `id`='$unk[id_skkni]'";
	$skkninya=$conn->query($getskkninya);
	$skkx=$skkninya->fetch_assoc();
	$write->easyCell($nounkt, 'align:C; valign:T; font-size:10; border:LTBR;');
	$write->easyCell($unk['kode_unit'], 'align:L; valign:T; font-size:10; border:LTBR;');
	$write->easyCell($unk['judul'], 'align:L; valign:T; font-size:10; border:LTBR;');
	$write->easyCell($skkx['no_skkni'], 'align:L; valign:T; font-size:10; border:LTBR;');
	$write->printRow();
	$nounk++;
	
}
$write->endTable(5);
// Bagian 3
$write=new easyTable($pdf, '{10, 95, 25, 25, 25}', 'width:180;  font-size:12;font-family:arial;');
$write->easyCell('Bagian 3: Bukti Kelengkapan Pemohon', 'align:L; valign:T; font-size:12; font-style:B; colspan:5');
$write->printRow();
$write->easyCell('Bukti Persyaratan Dasar Pemohon', 'align:L; valign:T; font-size:12; font-style:B; colspan:5');
$write->printRow();
$write->easyCell('No.', 'align:C; valign:M; font-size:10; font-style:B; border:LTBR; bgcolor:#DFDFDF; rowspan:2');
$write->easyCell('Bukti Persyaratan Dasar', 'align:C; valign:M; font-size:10; font-style:B; border:LTBR; bgcolor:#DFDFDF; rowspan:2');
$write->easyCell('Ada', 'align:C; valign:M; font-size:10; font-style:B; border:LTBR; bgcolor:#DFDFDF; colspan:2');
$write->easyCell('Tidak Ada', 'align:C; valign:M; font-size:10; font-style:B; border:LTBR; bgcolor:#DFDFDF; rowspan:2');
$write->printRow();
$write->easyCell('Memenuhi 
Syarat', 'align:C; valign:M; font-size:10; font-style:B; border:LTBR; bgcolor:#DFDFDF;');
$write->easyCell('Tidak
Memenuhi 
Syarat', 'align:C; valign:M; font-size:10; font-style:B; border:LTBR; bgcolor:#DFDFDF;');
$write->printRow();
$sqlgetskemapersyaratan="SELECT * FROM `skema_persyaratan` WHERE `id_skemakkni`='$sq[id]' ORDER BY `id` ASC";
$getskemapersyaratan=$conn->query($sqlgetskemapersyaratan);
$nosksy=1;
while ($sksy=$getskemapersyaratan->fetch_assoc()){
	$nosksyt=$nosksy.".";
	$write->easyCell($nosksyt, 'align:C; valign:T; font-size:10; border:LTBR;');
	$write->easyCell($sksy['persyaratan'], 'align:L; valign:T; font-size:10; border:LTBR;');
	
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; font-size:10; border:LTBR;');
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; font-size:10; border:LTBR;');
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; font-size:10; border:LTBR;');
	
	$write->printRow();
	$nosksy++;
}
$write->endTable(5);
// Bagian Rekomendasi
$write=new easyTable($pdf, '{95, 30, 55}', 'width:180;  font-size:12;font-family:arial;');
$write->easyCell('Rekomendasi (diisi oleh LSP):', 'align:L; valign:T; font-size:10; font-style:B; border:LTR;');
$write->easyCell('Pemohon/ Kandidat :', 'align:L; valign:T; font-size:10; font-style:B; border:LTR; colspan:2');
$write->printRow();
$write->easyCell('Berdasarkan ketentuan persyaratan dasar, maka pemohon:', 'align:L; valign:T; font-size:10; border:LR;');
$write->easyCell('Nama', 'align:L; valign:T; font-size:10; border:LTBR;');
$write->easyCell('', 'align:L; valign:T; font-size:10; font-style:B; border:LTBR;');
$write->printRow();

$write->easyCell('Diterima/ Tidak diterima *)', 'align:L; valign:T; font-size:10; font-style:B; border:LR;');

$write->easyCell('Tanda tangan/
Tanggal', 'align:L; valign:T; font-size:10; border:LTBR; rowspan:4');
// tandatangan asesi
$sqlidentitas="SELECT * FROM `identitas`";
$identitas=$conn->query($sqlidentitas);
$iden=$identitas->fetch_assoc();

$write->easyCell('', 'align:L; valign:T; font-size:10; font-style:B; border:LR; rowspan:3');

$write->printRow();
$write->easyCell('sebagai peserta  sertifikasi', 'align:L; valign:T; font-size:10; border:LR;');
$write->printRow();
$write->easyCell('* coret yang tidak sesuai', 'align:L; valign:T; font-size:10; border:LR;');

$write->printRow();
$write->easyCell('', 'align:L; valign:T; font-size:10; border:LBR;');
$write->easyCell('', 'align:L; valign:B; font-size:10; font-style:B; border:LBR;');
$write->printRow();
$write->easyCell('Catatan:', 'align:L; valign:T; font-size:10; font-style:B; border:LR;');
$write->easyCell('Admin LSP :', 'align:L; valign:T; font-size:10; font-style:B; border:LBR; colspan:2');
$write->printRow();
$write->easyCell('', 'align:L; valign:T; font-size:10; font-style:B; border:LBR; rowspan:4');
$write->easyCell('Nama', 'align:L; valign:T; font-size:10; border:LBR;');

$write->easyCell('', 'align:L; valign:T; font-size:10; font-style:B; border:LBR;');
$write->printRow();
$write->easyCell('No. Reg', 'align:L; valign:T; font-size:10; border:LBR;');
$write->easyCell('', 'align:L; valign:T; font-size:10; font-style:B; border:LBR;');
$write->printRow();
$write->easyCell('Tanda tangan/ 
Tanggal', 'align:L; valign:T; font-size:10; border:LBR; rowspan:2');

$write->easyCell('', 'align:L; valign:T; font-size:10; font-style:B; border:LR;');

$write->printRow();
$write->easyCell('', 'align:L; valign:T; font-size:10; font-style:B; border:LBR;');
$write->printRow();
$write->endTable(5);
$pdf->AliasNbPages();
//output file pdf
$fileoutputnya="Master-FORM-APL-01-".$skemakkni.".pdf";
$pdf->Output($fileoutputnya,'D');
ob_end_flush();
?>