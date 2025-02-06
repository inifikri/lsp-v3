<?php
ini_set('display_errors',1); 
error_reporting(E_ALL);
ob_start();
include 'fpdf-easytable-master/fpdf.php';
include 'fpdf-easytable-master/exfpdf.php';
include 'fpdf-easytable-master/easyTable.php';
include "../config/koneksi.php";
include "../config/library.php";
include "../config/fungsi_indotgl.php";
$sqlidentitas="SELECT * FROM `identitas`";
$identitas=$conn->query($sqlidentitas);
$iden=$identitas->fetch_assoc();
$sqlgetasesi="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_GET[ida]'";
$getasesi=$conn->query($sqlgetasesi);
$as=$getasesi->fetch_assoc();
$sqljadwal="SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
$jadwal=$conn->query($sqljadwal);
$jdq=$jadwal->fetch_assoc();
$tambah=date('Y-m-d', strtotime('+6 days', strtotime($jdq['tgl_asesmen'])));
$ttdsertifikatnya=date('Y-m-d H:i:s');
$tgl_cetak = tgl_indo($tambah);
$sqllsp="SELECT * FROM `lsp`";
$lsp=$conn->query($sqllsp);
$lq=$lsp->fetch_assoc();
$namalsp0=str_replace("LSP ","",$lq['nama']);
$namalsp=str_replace("Lembaga Sertifikasi Profesi ","",$namalsp0);
$sqlskema="SELECT * FROM `skema_kkni` WHERE `id`='$jdq[id_skemakkni]'";
$skema=$conn->query($sqlskema);
$sq=$skema->fetch_assoc();
$skemakkni=$sq['judul'];
$skemakknieng=$sq['judul_eng'];
$sqlwil1="SELECT * FROM `data_wilayah` WHERE `id_wil`='$lq[id_wilayah]'";
$wilayah1=$conn->query($sqlwil1);
$wil1=$wilayah1->fetch_assoc();
$sqlwil2="SELECT * FROM `data_wilayah` WHERE `id_wil`='$wil1[id_induk_wilayah]'";
$wilayah2=$conn->query($sqlwil2);
$wil2=$wilayah2->fetch_assoc();
$sqlwil3="SELECT * FROM `data_wilayah` WHERE `id_wil`='$wil2[id_induk_wilayah]'";
$wilayah3=$conn->query($sqlwil3);
$wil3=$wilayah3->fetch_assoc();
$pdf=new exFPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',10);
$pdf->AddFont('FontUTF8','','Arimo-Regular.php'); 
$pdf->AddFont('FontUTF8','B','Arimo-Bold.php');
$pdf->AddFont('FontUTF8','I','Arimo-Italic.php');
$pdf->AddFont('FontUTF8','BI','Arimo-BoldItalic.php');
//$bgsertifikat="e-sertifikat-blangko-front.jpg";
//$pdf->Image($bgsertifikat,0,0,215,297.5);
$namakota0=trim($wil2['nm_wil']);
$namakota=ucwords(strtolower($namakota0));
switch ($namakota){
	default:
		$namakotax=$namakota;
	break;
	case "Kota Adm. Jakarta Utara":
		$namakotax="Jakarta";
	break;
	case "Kota Adm. Jakarta Selatan":
		$namakotax="Jakarta";
	break;
	case "Kota Adm. Jakarta Timur":
		$namakotax="Jakarta";
	break;
	case "Kota Adm. Jakarta Barat":
		$namakotax="Jakarta";
	break;
	case "Kota Adm. Jakarta Pusat":
		$namakotax="Jakarta";
	break;
}
$tglcetakkota = ucwords(strtolower($namakotax)).", ".$tgl_cetak;
$tglLulusEng0=date_create($tambah);
$tglLulusEng=date_format($tglLulusEng0,"jS F Y");
$tglcetakkota2 = ucwords(strtolower($namakotax)).", ".$tglLulusEng;
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
$kbli = $sq['kbli'];
$kbji = $sq['kbji'];
$jenjangkkni=$sq['jenjang'];
$tahunpermohonan=substr($jdq['tgl_asesmen'],0,4);
// get data nomor register sertifikat keseluruhan selama LSP berdiri
$sqlgetnoregsert0="SELECT * FROM `skillpassport`";
$getnoregsert0=$conn->query($sqlgetnoregsert0);
$jumcert=$getnoregsert0->num_rows;
if ($jumcert>0){
	// cek apakah sudah ada sertifikatnya
	$sqlgetnoregsert0a="SELECT * FROM `skillpassport` WHERE `id_asesi`='$as[no_pendaftaran]' AND `id_jadwal`='$jdq[id]'";
	$getnoregsert0a=$conn->query($sqlgetnoregsert0a);
	$jumcertas=$getnoregsert0a->num_rows;
	$nrcertxx=$getnoregsert0a->fetch_assoc();
	if ($jumcertas>0){
		$nourutsertifikat=$nrcertxx['no_register'];
		$nourutdilsp=$nrcertxx['no_regpertahun'];
	}else{
		$sqlgetnoregsert="SELECT * FROM `skillpassport` ORDER BY `no_register` DESC LIMIT 1";
		$getnoregsert=$conn->query($sqlgetnoregsert);
		$nrcert=$getnoregsert->fetch_assoc();
		$nourutsertifikat0=$nrcert['no_register']+1;
		$nourutsertifikat00=strlen($nourutsertifikat0);
		switch ($nourutsertifikat00){
			case 1:
				$nourutsertifikat="0000000".$nourutsertifikat0;
			break;
			case 2:
				$nourutsertifikat="000000".$nourutsertifikat0;
			break;
			case 3:
				$nourutsertifikat="00000".$nourutsertifikat0;
			break;
			case 4:
				$nourutsertifikat="0000".$nourutsertifikat0;
			break;
			case 5:
				$nourutsertifikat="000".$nourutsertifikat0;
			break;
			case 6:
				$nourutsertifikat="00".$nourutsertifikat0;
			break;
			case 7:
				$nourutsertifikat="0".$nourutsertifikat0;
			break;
			default:
				$nourutsertifikat=$nourutsertifikat0;
			break;
		}
		// get data nomor register sertifikat keseluruhan per tahun
		$sqlgetnoregsert20="SELECT * FROM `skillpassport` WHERE `tahun`='$tahunpermohonan'";
		$getnoregsert20=$conn->query($sqlgetnoregsert20);
		$jumcert2=$getnoregsert20->num_rows;
		if ($jumcert2>0){
			$sqlgetnoregsert2="SELECT * FROM `skillpassport` WHERE `tahun`='$tahunpermohonan' ORDER BY `no_regpertahun` DESC LIMIT 1";
			$getnoregsert2=$conn->query($sqlgetnoregsert2);
			$nrcert2=$getnoregsert2->fetch_assoc();
			$nourutdilsp0=$nrcert2['no_regpertahun']+1;
			$nourutdilsp00=strlen($nourutdilsp0);
			switch ($nourutdilsp00){
				case 1:
					$nourutdilsp="0000".$nourutdilsp0;
				break;
				case 2:
					$nourutdilsp="000".$nourutdilsp0;
				break;
				case 3:
					$nourutdilsp="00".$nourutdilsp0;
				break;
				case 4:
					$nourutdilsp="0".$nourutdilsp0;
				break;
				default:
					$nourutdilsp=$nourutdilsp0;
				break;
			}
		}else{
			// 5 digit nomor registernya sesuai ketentuan PUPR
			$nourutdilsp="00001";
		}
	}
}else{
	// 8 digit nomor registernya sesuai ketentuan PUPR
	$nourutsertifikat="00000001";
	// 5 digit nomor registernya sesuai ketentuan PUPR
	$nourutdilsp="00001";
}
$nomorsertifikat=$kbli." ".$kbji." ".$jenjangkkni." ".$nourutsertifikat." ".$tahunpermohonan;
$nolisensilsp0=explode("-",$lq['no_lisensi']);
$nolisensilsp=@$nolisensilsp0[2];
//$nourutdilsp="00000";
$noregtkk=""; // dapat dari portal perizinan PUPR
$kodesektor=$sq['kode_sektor'];
$nomorregistrasixx=$kodesektor." ".$nolisensilsp." ".$nourutdilsp." ".$tahunpermohonan;
$nomorregistrasitampil="No. Reg. ".$nomorregistrasixx;
// membuat digital signature pimppinan LSP
$alamatip=$_SERVER['REMOTE_ADDR'];
$url =  "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
$iddokumen=md5($url);
$escaped_url = htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
$namadokumensertifikat="SKILL PASSPORT LSP NO. ".$nomorsertifikat." an.".$as['nama'];
$sqlinputdigisign="INSERT INTO `logdigisign`(`id_dokumen`, `url_ditandatangani`, `nama_dokumen`, `penandatangan`, `ip`, `waktu`) VALUES ('$iddokumen','$escaped_url','$namadokumensertifikat','$lq[direktur]','$alamatip', '$ttdsertifikatnya')";
$conn->query($sqlinputdigisign);
$pdf->SetMargins(0,0,0);
$write=new easyTable($pdf, '{200}', 'width:200; align:C; font-family:arial;');
$write->rowStyle('min-height:10');
//$write->easyCell($nomor_blanko, 'font-size:18; colspan:2; font-style:B; align:L; valign:B;');
$write->easyCell('', 'font-size:18; colspan:2; font-style:B; align:L; valign:B;');
$write->printRow();
$write->endTable(50);
$jabatankerja=$sq['judul'];
$workposition=$sq['judul_eng'];
$write=new easyTable($pdf, '{98,4,98}', 'width:200; align:C; font-family:arial;');
$write->rowStyle('min-height:10');
$write->easyCell('Nomor Skill Passport', 'font-size:14; font-style:B; align:R;');
$write->easyCell('/', 'font-size:14; font-style:B; align:C;');
$write->easyCell('Skill Passport Number', 'font-size:13.5; font-style:I,B; align:L;');
$write->printRow();
$write->easyCell($nomorsertifikat, 'line-height:0.75; font-size:13.5; font-style:B; align:C; valign:B; colspan:3;');
$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell('Dengan ini menyatakan bahwa,', 'line-height:0.75; font-size:13.5; font-style:N; align:C; valign:B; colspan:3;');
$write->printRow();
$write->easyCell('This is to certify that,', 'line-height:0.75; font-size:13.5; font-style:I; align:C; valign:T; colspan:3;');
$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell($as['nama'], 'line-height:0.75; font-size:15; font-style:B; align:C; valign:M; colspan:3;');
$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell($nomorregistrasitampil, 'line-height:0.75; font-size:13.5; font-style:B; align:C; valign:M; colspan:3;');
$write->printRow();
$write->easyCell('Telah Kompeten pada bidang:', 'line-height:0.75; font-size:13.5; font-style:B; align:C; valign:M; colspan:3;');
$write->printRow();
$write->easyCell('Is competent in the area of:', 'line-height:0.75; font-size:13.5; font-style:I; align:C; valign:T; colspan:3;');
$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell($sq['areakerja'], 'line-height:0.75; font-size:15; font-style:B; align:C; valign:B; colspan:3;');
$write->printRow();
$write->easyCell($sq['areakerja_eng'], 'line-height:0.75; font-size:15; font-style:BI; align:C; valign:T; colspan:3;');
$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell('Dengan Kualifikasi / Kompetensi:', 'line-height:0.75; font-size:13.5; font-style:N; align:C; valign:B; colspan:3;');
$write->printRow();
$write->easyCell('With Qualification / Competency:', 'line-height:0.75; font-size:13.5; font-style:I; align:C; valign:T; colspan:3;');
$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell($jabatankerja, 'line-height:0.75; font-size:16.5; font-style:B; align:C; valign:B; colspan:3;');
$write->printRow();
$write->easyCell($workposition, 'line-height:0.75; font-size:16.5; font-style:BI; align:C; valign:T; colspan:3;');
$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell('Skill Passport ini berlaku untuk 3 (tiga) tahun', 'line-height:0.75; font-size:12; font-style:N; align:C; valign:B; colspan:3;');
$write->printRow();
$write->easyCell('This skill passport is valid for 3 (three) years', 'line-height:0.75; font-size:12; font-style:I; align:C; valign:T; colspan:3;');
$write->printRow();
$sqlinputdigisign="SELECT * FROM `logdigisign` WHERE `id_dokumen`='$iddokumen' AND `nama_dokumen`= '$namadokumensertifikat' AND `penandatangan`='$lq[direktur]' ORDER BY `id` DESC";
$inputdigisign=$conn->query($sqlinputdigisign);
$dgsn=$inputdigisign->fetch_assoc();
//memanggil library QR Code
require_once("../phpqrcode/qrlib.php");
$qrcodetext="http://".$iden['url_domain']."/signed.php?id=".$dgsn['id'];
//create a QR Code and save it as a png image file named generateqr.png
QRcode::png($qrcodetext,"../foto_tandatangan/generateqr.png");
$write->rowStyle('min-height:10');
$write->easyCell('Atas nama Badan Nasional Sertifikasi Profesi', 'line-height:0.75; font-size:13.5; font-style:N; align:C; valign:B; colspan:3;');
$write->printRow();
$write->easyCell('On Behalf of Indonesia Professional Certification Authority', 'line-height:0.75; font-size:13.5; font-style:I; align:C; valign:T; colspan:3;');
$write->printRow();
$write->rowStyle('min-height:10');
$namalsplen=strlen($namalsp);
if ($namalsplen>25){
	$write->easyCell('Lembaga Sertifikasi Profesi '.$namalsp, 'line-height:0.75; font-size:12; font-style:B; align:C; valign:B; colspan:3;');
	$write->printRow();
	$write->easyCell($namalsp.' Professional Certification Agency', 'line-height:0.75; font-size:12; font-style:BI; align:C; valign:T; colspan:3;');
	$write->printRow();
}else{
	$write->easyCell('Lembaga Sertifikasi Profesi '.$namalsp, 'line-height:0.75; font-size:13.5; font-style:B; align:C; valign:B; colspan:3;');
	$write->printRow();
	$write->easyCell($namalsp.' Professional Certification Agency', 'line-height:0.75; font-size:13.5; font-style:BI; align:C; valign:T; colspan:3;');
	$write->printRow();	
}
$write->endTable(5);
$write=new easyTable($pdf, '{60,80,60}', 'width:200; align:C; font-family:arial;');
$write->rowStyle('min-height:24');
$write->easyCell('', 'img:../foto_tandatangan/generateqr.png, h20; font-size:12; font-style:B; align:R; valign:M;');
$write->easyCell('', 'font-size:12; font-style:B; align:C; valign:M;');
$write->easyCell('', 'font-size:12; font-style:B; align:C; valign:M;');
$write->printRow();
$write->easyCell($lq['direktur'], 'line-height:0.75; font-size:12; font-style:B; align:C; valign:B; colspan:3;');
$write->printRow();
if ($lq['nama_jabatanpimpinan']=="Ketua"){
	$write->easyCell('Ketua LSP', 'line-height:0.75; font-size:12; font-style:B; align:C; valign:B; colspan:3;');
	$write->printRow();
	$write->easyCell('Chairman PCA', 'line-height:0.5; font-size:12; font-style:I; align:C; valign:T; colspan:3;');
	$write->printRow();
}else{
	$write->easyCell('Direktur LSP', 'line-height:0.75; font-size:12; font-style:B; align:C; valign:B; colspan:3;');
	$write->printRow();
	$write->easyCell('Director PCA', 'line-height:0.5; font-size:12; font-style:I; align:C; valign:T; colspan:3;');
	$write->printRow();
}
$write->endTable(5);
$pdf->AliasNbPages();
// ============================= halaman kedua sertifikat ==================================
$pdf->AddPage();
/* $bgsertifikat2="e-sertifikat-blangko-back.png";
$pdf->Image($bgsertifikat2,0,0,215,297.5);
$pdf->SetMargins(10,10,10,0); */
// daftar unit kompetensinya =======================================================
// tampilkan unit kompetensinya
$sqlgetunitkompetensi="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sq[id]' ORDER BY `id` ASC";
$getunitkompetensi=$conn->query($sqlgetunitkompetensi);
$nounk=1;
$jumunit=$getunitkompetensi->num_rows;
//
$write=new easyTable($pdf, '{200}', 'width:200; align:C; font-family:arial;');
$write->rowStyle('min-height:10');
$write->easyCell('', 'font-size:12; font-style:B; align:C; valign:M; colspan:3;');
$write->printRow();
$write->endTable(5);
if ($jumunit>15){
	$write=new easyTable($pdf, '{200}', 'width:200; align:C; font-family:arial;');
	$write->rowStyle('min-height:20');
	$write->easyCell('', 'img:../images/logolsp.jpg, h20; font-size:12; font-style:B; align:C; valign:M;');
	$write->printRow();
	$write->easyCell('Daftar Unit Kompetensi:', 'line-height:0.75; font-size:12; font-style:BI; align:C; valign:B;');
	$write->printRow();
	$write->easyCell('List of Unit(s) of Competency:', 'line-height:0.75; font-size:10; font-style:BI; align:C; valign:T;');
	$write->printRow();
	$write->endTable(3);
}else{
	$write=new easyTable($pdf, '{200}', 'width:200; align:C; font-family:arial;');
	$write->rowStyle('min-height:30');
	$write->easyCell('', 'img:../images/logolsp.jpg, h30; font-size:12; font-style:B; align:C; valign:M;');
	$write->printRow();
	$write->rowStyle('min-height:10');
	$write->easyCell('Daftar Unit Kompetensi:', 'line-height:0.75; font-size:14; font-style:BI; align:C; valign:B;');
	$write->printRow();
	$write->easyCell('List of Unit(s) of Competency:', 'line-height:0.75; font-size:12; font-style:BI; align:C; valign:T;');
	$write->printRow();
	$write->endTable(3);
}
if ($jumunit>10){
	if ($jumunit>15){
		$write=new easyTable($pdf, '{7,23,65,7,23,65}', 'width:180; align:C; font-family:arial;');
		$write->easyCell('No.', 'font-size:6; font-style:B; align:C; valign:M; border:LTR; rowspan:2;');
		$write->easyCell('Kode Unit Kompetensi', 'line-height:0.6; font-size:5; font-style:B; align:C; valign:B; border:LTR;');
		$write->easyCell('Judul Unit Kompetensi', 'line-height:0.6; font-size:6; font-style:B; align:C; valign:B; border:LTR;');
		$write->easyCell('No.', 'font-size:6; font-style:B; align:C; valign:M; border:LTR; rowspan:2;');
		$write->easyCell('Kode Unit Kompetensi', 'line-height:0.6; font-size:5; font-style:B; align:C; valign:B; border:LTR;');
		$write->easyCell('Judul Unit Kompetensi', 'line-height:0.6; font-size:6; font-style:B; align:C; valign:B; border:LTR;');
		$write->printRow();
		$write->easyCell('Code of Competency Unit', 'line-height:0.6; font-size:4; font-style:BI; align:C; valign:T; border:LBR;');
		$write->easyCell('Title of Competency Unit', 'line-height:0.6; font-size:4; font-style:BI; align:C; valign:T; border:LBR;');
		$write->easyCell('Code of Competency Unit', 'line-height:0.6; font-size:4; font-style:BI; align:C; valign:T; border:LBR;');
		$write->easyCell('Title of Competency Unit', 'line-height:0.6; font-size:4; font-style:BI; align:C; valign:T; border:LBR;');
		$write->printRow();
		while ($unk=$getunitkompetensi->fetch_assoc()){
			$write->easyCell($nounk.'.', 'font-size:7; font-style:B; align:C; valign:M; border:LTBR; rowspan:2;');
			$write->easyCell($unk['kode_unit'], 'line-height:0.6; font-size:6; font-style:B; align:C; valign:M; border:LTBR; rowspan:2;');
			$write->easyCell($unk['judul'], 'line-height:0.6; font-size:7; font-style:B; align:L; valign:M; border:LTR;');
			$write->easyCell($nounk.'.', 'font-size:7; font-style:B; align:C; valign:M; border:LTBR; rowspan:2;');
			$write->easyCell($unk['kode_unit'], 'line-height:0.6; font-size:6; font-style:B; align:C; valign:M; border:LTBR; rowspan:2;');
			$write->easyCell($unk['judul'], 'line-height:0.6; font-size:7; font-style:B; align:L; valign:M; border:LTR;');
			$write->printRow();
			$write->easyCell($unk['judul_eng'], 'line-height:0.6; font-size:6; font-style:I; align:L; valign:T; border:LBR;');
			$write->easyCell($unk['judul_eng'], 'line-height:0.6; font-size:6; font-style:I; align:L; valign:T; border:LBR;');
			$write->printRow();
			$nounk++;
		}
		$write->endTable(3);		
	}else{
		$write=new easyTable($pdf, '{10,45,125}', 'width:180; align:C; font-family:arial;');
		$write->easyCell('No.', 'font-size:9; font-style:B; align:C; valign:M; border:LTR; rowspan:2;');
		$write->easyCell('Kode Unit Kompetensi', 'font-size:9; font-style:B; align:C; valign:B; border:LTR;');
		$write->easyCell('Judul Unit Kompetensi', 'font-size:9; font-style:B; align:C; valign:B; border:LTR;');
		$write->printRow();
		$write->easyCell('Code of Competency Unit', 'font-size:9; font-style:BI; align:C; valign:T; border:LBR;');
		$write->easyCell('Title of Competency Unit', 'font-size:9; font-style:BI; align:C; valign:T; border:LBR;');
		$write->printRow();
		while ($unk=$getunitkompetensi->fetch_assoc()){
			$write->easyCell($nounk.'.', 'font-size:8; font-style:B; align:C; valign:M; border:LTBR; rowspan:2;');
			$write->easyCell($unk['kode_unit'], 'font-size:8; font-style:B; align:C; valign:M; border:LTBR; rowspan:2;');
			$write->easyCell($unk['judul'], 'line-height:0.6; font-size:8; font-style:B; align:L; valign:M; border:LTR;');
			$write->printRow();
			$write->easyCell($unk['judul_eng'], 'line-height:0.6; font-size:7; font-style:I; align:L; valign:T; border:LBR;');
			$write->printRow();
			$nounk++;
		}
		$write->endTable(3);
	}
}else{
	$write=new easyTable($pdf, '{10,55,115}', 'width:180; align:C; font-family:arial;');
	$write->easyCell('No.', 'font-size:10; font-style:B; align:C; valign:M; border:LTR; rowspan:2;');
	$write->easyCell('Kode Unit Kompetensi', 'font-size:10; font-style:B; align:C; valign:B; border:LTR;');
	$write->easyCell('Judul Unit Kompetensi', 'font-size:10; font-style:B; align:C; valign:B; border:LTR;');
	$write->printRow();
	$write->easyCell('Code of Competency Unit', 'font-size:9; font-style:BI; align:C; valign:T; border:LBR;');
	$write->easyCell('Title of Competency Unit', 'font-size:9; font-style:BI; align:C; valign:T; border:LBR;');
	$write->printRow();
	while ($unk=$getunitkompetensi->fetch_assoc()){
		$write->easyCell($nounk.'.', 'font-size:9; font-style:B; align:C; valign:M; border:LTBR; rowspan:2;');
		$write->easyCell($unk['kode_unit'], 'font-size:9; font-style:B; align:C; valign:M; border:LTBR; rowspan:2;');
		$write->easyCell($unk['judul'], 'line-height:0.8; font-size:9; font-style:B; align:L; valign:M; border:LTR;');
		$write->printRow();
		$write->easyCell($unk['judul_eng'], 'line-height:0.8; font-size:7; font-style:I; align:L; valign:T; border:LBR;');
		$write->printRow();
		$nounk++;
	}	
	$write->endTable(20);
}
//===================================================================================
$ditandatangani="Ditetapkan di ".$tglcetakkota;
$ditandatangani2="Enacted in ".$tglcetakkota2;
if ($jumunit>15){
	$write=new easyTable($pdf, '{40,60,80}', 'width:180; align:C; font-family:arial;');
	//$write->easyCell('', 'font-size:10.5; align:C; valign:T;');
	$write->easyCell($ditandatangani, 'line-height:0.75; font-size:9; align:R; valign:T; colspan:3;');
	$write->printRow();
	//$write->easyCell('', 'font-size:10.5; align:C; valign:T;');
	$write->easyCell($ditandatangani2, 'line-height:0.75; font-size:9; font-style:I; align:R; valign:T; colspan:3;');
	$write->printRow();
	$write->easyCell('Atas Nama Badan Nasional Sertifikasi Profesi', 'line-height:0.75; font-size:9; align:R; valign:T; colspan:3;');
	$write->printRow();
	$write->easyCell('On Behalf of Indonesian Professional Certification Authority', 'line-height:0.75; font-size:8; font-style:I; align:R; valign:T; colspan:3;');
	$write->printRow();
	$write->easyCell('', 'img:../foto_asesi/'.$as['foto'].',w30,h40; font-size:10.5; align:L; valign:T; rowspan:5;');
	if ($namalsplen>25){
		$write->easyCell('Lembaga Sertifikasi Profesi '.$namalsp, 'line-height:0.75; font-size:9; font-style:B; align:R; valign:B; colspan:2;');
		$write->printRow();
		$write->easyCell($namalsp.' Professional Certification Agency', 'line-height:0.75; font-size:8; font-style:I; align:R; valign:T; colspan:2;');
		$write->printRow();
	}else{
		$write->easyCell('Lembaga Sertifikasi Profesi '.$namalsp, 'line-height:0.75; font-size:9; font-style:B; align:R; valign:B; colspan:2;');
		$write->printRow();
		$write->easyCell($namalsp.' Professional Certification Agency', 'line-height:0.75; font-size:8; font-style:I; align:R; valign:T; colspan:2;');
		$write->printRow();	
	}
	$write->rowStyle('min-height:28');
	$write->easyCell($as['nama'], 'font-size:9; font-style:BU; align:C; valign:B;');
	$write->easyCell($lq['manajer_sertifikasi'], 'font-size:9; font-style:BU; align:C; valign:B;');
	//$write->easyCell('', 'font-size:12; font-style:B; align:C; valign:T; rowspan:2;');
	$write->printRow();
	$write->easyCell('Tanda tangan Pemilik', 'line-height:0.75; font-size:9; font-style:B; align:C; valign:T;');
	$write->easyCell('Manajer Sertifikasi', 'line-height:0.75; font-size:9; font-style:B; align:C; valign:T;');
	$write->printRow();
	$write->easyCell('(Signature of Holder)', 'line-height:0.75; font-size:8; font-style:I; align:C; valign:T;');
	$write->easyCell('(Certification Manager)', 'line-height:0.75; font-size:8; font-style:I; align:C; valign:T;');
	$write->printRow();
	$write->endTable(8);
}else{
	$write=new easyTable($pdf, '{40,60,80}', 'width:180; align:C; font-family:arial;');
	//$write->easyCell('', 'font-size:10.5; align:C; valign:T;');
	$write->easyCell($ditandatangani, 'line-height:0.75; font-size:10.5; align:R; valign:T; colspan:3;');
	$write->printRow();
	//$write->easyCell('', 'font-size:10.5; align:C; valign:T;');
	$write->easyCell($ditandatangani2, 'line-height:0.75; font-size:10.5; font-style:I; align:R; valign:T; colspan:3;');
	$write->printRow();
	$write->easyCell('Atas Nama Badan Nasional Sertifikasi Profesi', 'line-height:0.75; font-size:10.5; align:R; valign:T; colspan:3;');
	$write->printRow();
	$write->easyCell('On Behalf of Indonesian Professional Certification Authority', 'line-height:0.75; font-size:10.5; font-style:I; align:R; valign:T; colspan:3;');
	$write->printRow();
	$write->easyCell('', 'img:../foto_asesi/'.$as['foto'].',w30,h40; font-size:10.5; align:L; valign:T; rowspan:5;');
	if ($namalsplen>25){
		$write->easyCell('Lembaga Sertifikasi Profesi '.$namalsp, 'line-height:0.75; font-size:10.5; font-style:B; align:R; valign:B; colspan:2;');
		$write->printRow();
		$write->easyCell($namalsp.' Professional Certification Agency', 'line-height:0.75; font-size:10.5; font-style:I; align:R; valign:T; colspan:2;');
		$write->printRow();
	}else{
		$write->easyCell('Lembaga Sertifikasi Profesi '.$namalsp, 'line-height:0.75; font-size:10.5; font-style:B; align:R; valign:B; colspan:2;');
		$write->printRow();
		$write->easyCell($namalsp.' Professional Certification Agency', 'line-height:0.75; font-size:10.5; font-style:I; align:R; valign:T; colspan:2;');
		$write->printRow();	
	}
	$write->rowStyle('min-height:28');
	$write->easyCell($as['nama'], 'font-size:10.5; font-style:BU; align:C; valign:B;');
	$write->easyCell($lq['manajer_sertifikasi'], 'font-size:12; font-style:BU; align:C; valign:B;');
	//$write->easyCell('', 'font-size:12; font-style:B; align:C; valign:T; rowspan:2;');
	$write->printRow();
	$write->easyCell('Tanda tangan Pemilik', 'line-height:0.75; font-size:10.5; font-style:B; align:C; valign:T;');
	$write->easyCell('Manajer Sertifikasi', 'line-height:0.75; font-size:10.5; font-style:B; align:C; valign:T;');
	$write->printRow();
	$write->easyCell('(Signature of Holder)', 'line-height:0.75; font-size:10.5; font-style:I; align:C; valign:T;');
	$write->easyCell('(Certification Manager)', 'line-height:0.75; font-size:10.5; font-style:I; align:C; valign:T;');
	$write->printRow();
	$write->endTable(8);
}
$pdf->AliasNbPages();
// Pencatatan kembali ke tabel sertifikat
$no_registrasisertifikat=$nomorregistrasixx;
$tgl_terbit=date("Y-m-d");
$tambah2=date('Y-m-d', strtotime('+3 years', strtotime($tgl_terbit)));
$tgl_expired=$tambah2;
$sqlgetnoregsert3="SELECT * FROM `skillpassport` WHERE `id_asesi`='$as[no_pendaftaran]' AND `id_jadwal`='$jdq[id]' AND `id_skemakkni`='$sq[id]' AND `no_register`='$nourutsertifikat' AND `no_regpertahun`='$nourutdilsp' AND `no_sertifikat`='$nomorsertifikat' AND `tahun`='$tahunpermohonan'";
$getnoregsert3=$conn->query($sqlgetnoregsert3);
//$nrcert3=$getnoregsert3->fetch_assoc();
$jumcert3=$getnoregsert3->num_rows;
if ($jumcert3>0){
	$sqlinputcert="UPDATE `skillpassport` SET `no_register`='$nourutsertifikat', `no_regpertahun`='$nourutdilsp', `no_sertifikat`='$nomorsertifikat', `no_registrasisertifikat`='$no_registrasisertifikat',  `tgl_terbit`='$tgl_terbit', `tahun`='$tahunpermohonan', `masa_berlaku`='$tgl_expired' WHERE `id_asesi`='$as[no_pendaftaran]' AND `id_jadwal`='$jdq[id]' AND `id_skemakkni`='$sq[id]'";
	$conn->query($sqlinputcert);
	//echo $sqlinputcert;
}else{
	$sqlinputcert="INSERT INTO `skillpassport`(`id_asesi`, `id_jadwal`, `id_skemakkni`, `tgl_diajukan`, `no_pengajuan`, `no_register`, `no_regpertahun`, `no_sertifikat`, `no_registrasisertifikat`, `tgl_terbit`, `tahun`, `masa_berlaku`) VALUES ('$as[no_pendaftaran]', '$jdq[id]', '$sq[id]', '$tgl_terbit', '', '$nourutsertifikat', '$nourutdilsp', '$nomorsertifikat', '$no_registrasisertifikat', '$tgl_terbit', '$tahunpermohonan', '$tgl_expired')";
	$conn->query($sqlinputcert);
	//echo $sqlinputcert;
}
// =================================================
//output file pdf
$fileoutputnya="skillpassport-".$as['nama']."-".$_GET['ida'].".pdf";
$pdf->Output($fileoutputnya,'D');
ob_end_flush();
?>