<?php
ini_set('display_errors',1); 
error_reporting(E_ALL);
header('Content-type: application/pdf');

session_start();
if (!isset($_SESSION['namauser'])){
header("Location:index.php");
}	

include "../config/koneksi.php";
include "../config/library.php";
include "../config/fungsi_indotgl.php";


$sqlasesi="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_GET[ida]'";
$asesi=$conn->query($sqlasesi);
$as=$asesi->fetch_assoc();
$sqljadwal="SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
$jadwal=$conn->query($sqljadwal);
$jdq=$jadwal->fetch_assoc();
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



//mengambil data dari tabel
$sqlasesmenasesi="SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`='$jdq[id]' ORDER BY `id_asesi` ASC";
$asesmenasesi=$conn->query($sqlasesmenasesi);
$data = array();
while ($row = $asesmenasesi->fetch_assoc()) {
    array_push($data, $row);
}
$sqlasesmenasesi2="SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`='$jdq[id]' ORDER BY `id_asesi` ASC";
$asesmenasesi2=$conn->query($sqlasesmenasesi2);
$assm = $asesmenasesi2->fetch_assoc();
$sqladmin="SELECT * FROM `users` WHERE `username`='$assm[id_admin]' AND `blokir`='N'";
$admin=$conn->query($sqladmin);
$adm=$admin->fetch_assoc();

$sqlasesor="SELECT * FROM `asesor` WHERE `id`='$jdq[id_asesor]'";
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
//$fotowisudawan = "../../foto_wisuda/".$row3[foto];
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

//memanggil fpdf
require_once ("fpdf/fpdf.php");

$pdf = new FPDF('P','mm',array(210,297), false, 'ISO-8859-15',array(3, 20, 3, 0));
$pdf->AddPage();
//Cetak Barcode
$getkodeverifikasi=$as['no_pendaftaran'].$jdq['id'].date("Y-m-d")."FORM-APL-01";
$kodeverifikasi=md5($getkodeverifikasi);
$kodever=substr($kodeverifikasi,-8);
$pdf->Code39(10,280,$kodever,1,7);
// riwayat cetak
$alamatip=$_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
$sqllogcetak="INSERT INTO `logcetak`(`kodeverifikasi`, `id_jadwal`, `id_asesi`, `form`, `ip`) VALUES ('$kodever','$jdq[id]','$as[no_pendaftaran]','FORM-APL-01', '$alamatip')";
$logcetak=$conn->query($sqllogcetak);

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
//$nomorform='Nomor : '.$noijazah.'/IKIPVET.H4/F/'.$bulanromawi.'/'.$tahunskpi;
//tampilan Form
$id_wilayah=trim($wil1['nm_wil']);
$id_wilayah2=trim($wil2['nm_wil']).", ".trim($wil3['nm_wil']);
$namalsp=strtoupper($lq['nama']);
$alamatlsp=$lq['alamat']." ".$lq['kelurahan']." ".$id_wilayah;
$alamatlsp2=$id_wilayah2." Kodepos : ".$lq['kodepos'];
$telpemail="Telp./Fax.: ".$lq['telepon']." / ".$lq['fax']." Email : ".$lq['email'].", ".$lq['website'];
$tampilperiode="Periode ".$jdq['periode']." Tahun ".$jdq['tahun']." Gelombang ".$jdq['gelombang'];
$nomorlisensi="Nomor Lisensi : ".$lq['no_lisensi'];
//Disable automatic page break
//$pdf->SetAutoPageBreak(false);
// Foto atau Logo
$pdf->Image('../images/lspbatik.jpg',15,15,25,25);
$pdf->Image('../images/logo-bnsp.jpg',170,15,25,25);

//tampilan Judul Laporan
$pdf->SetFont('Arial','B','12'); //Font Arial, Tebal/Bold, ukuran font 11
$pdf->Cell(0, 3, '', '0', 1, 'C');
$pdf->Cell(0, 5, '', '0', 1, 'C');

$pdf->SetWidths(array(25,10,120,10,25));
$pdf->RowContentNoBorderC(array('','',$namalsp,'',''));
$pdf->SetFont('Arial','B','10'); //Font Arial, Tebal/Bold, ukuran font 16
$pdf->SetWidths(array(25,10,120,10,25));
$pdf->RowContentNoBorderC(array('','',$nomorlisensi,'',''));
$pdf->SetFont('Arial','','8'); //Font Arial, Tebal/Bold, ukuran font 16
$pdf->SetWidths(array(25,10,120,10,25));
$pdf->RowContentNoBorderC(array('','',$alamatlsp,'',''));
$pdf->SetWidths(array(25,10,120,10,25));
$pdf->RowContentNoBorderC(array('','',$alamatlsp2,'',''));
$pdf->SetWidths(array(25,10,120,10,25));
$pdf->RowContentNoBorderC(array('','',$telpemail,'',''));

//$pdf->Cell(0, 5, '', '0', 1, 'C');
$pdf->Ln();

//Headernya Identitas
$pdf->SetFillColor(255, 255, 255); //warna dalam kolom header
$pdf->SetTextColor(0); //warna tulisan putih
$pdf->SetDrawColor(0, 0, 0); //warna border R, G, B
$pdf->SetFont('Arial','B','12');
$pdf->Cell(190,6,'FR-APL-01. FORMULIR PERMOHONAN SERTIFIKASI KOMPETENSI', '', 0,'L');
$pdf->Ln();
/* $skemaid="SKEMA SERTIFIKASI : KLASTER ".strtoupper($skemakkni);
$pdf->SetFont('Arial','B','10');
$pdf->Cell(190,6,$skemaid, '', 0,'L');
$pdf->Ln(); */
$pdf->Cell(190,10,'Bagian 1 :  Rincian Data Pemohon Sertifikasi', '', 0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','','10');
$pdf->Cell(190,10,'Pada bagian ini, cantumkan data pribadi, data pendidikan formal serta data pekerjaan anda pada saat ini.', '', 0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','B','10');
$pdf->Cell(10,6,'a.', '', 0,'L');
$pdf->Cell(180,6,'Data Pribadi', '', 0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','','10');
$pdf->Cell(10,6,'', '', 0,'L');
$pdf->Cell(50,6,'Nama Lengkap', '', 0,'L');
$pdf->Cell(5,6,':', '', 0,'L');
$pdf->Cell(125,6,$as['nama'], 'B', 0,'L');
$pdf->Ln();
$pdf->Cell(10,6,'', '', 0,'L');
$pdf->Cell(50,6,'Tempat/ Tgl. Lahir', '', 0,'L');
$pdf->Cell(5,6,':', '', 0,'L');
$ttl=ucwords(strtolower($as['tmp_lahir'])).", ".tgl_indo($as['tgl_lahir']);
$pdf->Cell(125,6,$ttl, 'B', 0,'L');
$pdf->Ln();
$pdf->Cell(10,6,'', '', 0,'L');
$pdf->Cell(50,6,'Jenis Kelamin', '', 0,'L');
$pdf->Cell(5,6,':', '', 0,'L');
$pdf->SetFont('Arial','','10');
switch ($as['jenis_kelamin']){
	case "L":
		$jeniskelamin="Laki-laki";
	break;
	case "P":
		$jeniskelamin="Perempuan";
	break;
	default:
		$jeniskelamin="Laki-laki / Perempuan*";
	break;
}
$pdf->Cell(125,6,$jeniskelamin, 'B', 0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','','10');
$pdf->Cell(10,6,'', '', 0,'L');
$pdf->Cell(50,6,'Kebangsaan', '', 0,'L');
$pdf->Cell(5,6,':', '', 0,'L');
$kebangsaan=ucwords(strtolower($as['kebangsaan']));
$pdf->Cell(125,6,$kebangsaan, 'B', 0,'L');
$pdf->Ln();
$pdf->Cell(10,6,'', '', 0,'L');
$pdf->Cell(50,6,'Alamat Rumah', '', 0,'L');
$pdf->Cell(5,6,':', '', 0,'L');
$alamat=$as['alamat']." RT ".$as['RT']." RW ".$as['RW']." Kel./Desa ".$as['kelurahan'];
$pdf->Cell(125,6,$alamat, 'B', 0,'L');
$pdf->Ln();
$pdf->Cell(10,6,'', '', 0,'L');
$pdf->Cell(50,6,'', '', 0,'L');
$pdf->Cell(5,6,'', '', 0,'L');
$sqlwil1c="SELECT * FROM `data_wilayah` WHERE `id_wil`='$as[kecamatan]'";
$wilayah1c=$conn->query($sqlwil1c);
$wil1c=$wilayah1c->fetch_assoc();
$sqlwil2c="SELECT * FROM `data_wilayah` WHERE `id_wil`='$as[kota]'";
$wilayah2c=$conn->query($sqlwil2c);
$wil2c=$wilayah2c->fetch_assoc();
$sqlwil3c="SELECT * FROM `data_wilayah` WHERE `id_wil`='$as[propinsi]'";
$wilayah3c=$conn->query($sqlwil3c);
$wil3c=$wilayah3c->fetch_assoc();
$alamat2=trim($wil1c['nm_wil']).", ".trim($wil2c['nm_wil']).", ".trim($wil3c['nm_wil'])." Kodepos ".$as['kodepos'];
$pdf->Cell(125,6,$alamat2, 'B', 0,'L');
$pdf->Ln();
$pdf->Cell(10,6,'', '', 0,'L');
$pdf->Cell(50,6,'No. Telp./ Email', '', 0,'L');
$pdf->Cell(5,6,':', '', 0,'L');
$pdf->SetFont('Arial','','8');
$pdf->Cell(15,6,'Rumah', 'B', 0,'L');
$pdf->Cell(45,6,':', 'B', 0,'L');
$pdf->Cell(15,6,'Kantor', 'B', 0,'L');
$pdf->Cell(50,6,':', 'B', 0,'L');
$pdf->Ln();
$pdf->Cell(10,6,'', '', 0,'L');
$pdf->Cell(50,6,'', '', 0,'L');
$pdf->Cell(5,6,'', '', 0,'L');
$pdf->Cell(15,6,'HP', 'B', 0,'L');
$hp=": ".$as['nohp'];
$pdf->SetFont('Arial','','10');
$pdf->Cell(45,6,$hp, 'B', 0,'L');
$pdf->SetFont('Arial','','8');
$pdf->Cell(15,6,'Email', 'B', 0,'L');
$email=": ".$as['email'];
$pdf->SetFont('Arial','','10');
$pdf->Cell(50,6,$email, 'B', 0,'L');
$pdf->Ln();
$pdf->Cell(10,6,'', '', 0,'L');
$pdf->Cell(50,6,'Pendidikan terakhir', '', 0,'L');
$pdf->Cell(5,6,':', '', 0,'L');
$sqlpendidikan="SELECT * FROM `pendidikan` WHERE `id`='$as[pendidikan]'";
$pendidikan=$conn->query($sqlpendidikan);
$pdd=$pendidikan->fetch_assoc();
$pendidikannyaa=$pdd['jenjang_pendidikan'];
$pdf->Cell(125,6,$pendidikannyaa, 'B', 0,'L');
$pdf->Ln();
$pdf->Cell(190,4,'', '', 0,'L');
$pdf->Ln();

$pdf->SetFont('Arial','B','10');
$pdf->Cell(10,6,'b.', '', 0,'L');
$pdf->Cell(180,6,'Data Pekerjaan Sekarang', '', 0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','','10');
$pdf->Cell(10,6,'', '', 0,'L');
$pdf->Cell(50,6,'Nama lembaga/ perusahaan', '', 0,'L');
$pdf->Cell(5,6,':', '', 0,'L');
$pdf->Cell(125,6,$as['nama_kantor'], 'B', 0,'L');
$pdf->Ln();
$pdf->Cell(10,6,'', '', 0,'L');
$pdf->Cell(50,6,'Jabatan', '', 0,'L');
$pdf->Cell(5,6,':', '', 0,'L');
$pdf->Cell(125,6,$as['pekerjaan'], 'B', 0,'L');
$pdf->Ln();
$pdf->Cell(10,6,'', '', 0,'L');
$pdf->Cell(50,6,'Alamat', '', 0,'L');
$pdf->Cell(5,6,':', '', 0,'L');
$pdf->SetFont('Arial','','8');
$pdf->Cell(125,6,$as['alamat_kantor'], 'B', 0,'L');
$pdf->Ln();
$pdf->Cell(10,6,'', '', 0,'L');
$pdf->SetFont('Arial','','10');
$pdf->Cell(50,6,'No. Telp./ Fax./ Email', '', 0,'L');
$pdf->Cell(5,6,':', '', 0,'L');
$pdf->SetFont('Arial','','8');
$pdf->Cell(15,6,'Telp.', 'B', 0,'L');
$pdf->Cell(5,6,':', 'B', 0,'L');
$pdf->Cell(40,6,$as['telp_kantor'], 'B', 0,'L');
$pdf->Cell(15,6,'Fax.', 'B', 0,'L');
$pdf->Cell(5,6,':', 'B', 0,'L');
$pdf->Cell(45,6,$as['fax_kantor'], 'B', 0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','','8');
$pdf->Cell(10,6,'', '', 0,'L');
$pdf->Cell(50,6,'', '', 0,'L');
$pdf->Cell(5,6,'', '', 0,'L');
$pdf->SetFont('Arial','','8');
$pdf->Cell(15,6,'Email', 'B', 0,'L');
$pdf->Cell(5,6,':', 'B', 0,'L');
$pdf->Cell(105,6,$as['email_kantor'], 'B', 0,'L');

$pdf->Cell(190,5,'', '', 0,'L');
$pdf->Ln();

//===========BAGIAN 2====================

$pdf->SetFont('Arial','B','10');
$pdf->Cell(190,10,'Bagian 2 :  Data Sertifikasi', '', 0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','','10');
$keterangan1="Tuliskan Judul dan Nomor Skema Sertifikasi, Tujuan Asesmen  serta Daftar Unit Kompetensi sesuai kemasan pada skema sertifikasi yang anda ajukan untuk mendapatkan pengakuan sesuai dengan latar belakang pendidikan, pelatihan serta pengalaman kerja yang Anda miliki.";
$pdf->SetWidths(array(190));
$pdf->RowContentNoBorder(array($keterangan1));
$pdf->Cell(190,4,'',0,'C');
$pdf->Ln();
$pdf->SetFont('Arial','B','10');
$pdf->Cell(35,6,'Skema Sertifikasi/', 'LT', 0,'L');
$pdf->Cell(15,6,'Judul', 'LTB', 0,'L');
$pdf->Cell(5,6,':', 'LTB', 0,'L');
$pdf->Cell(135,6,$sq['judul'], 'LTBR', 0,'L');
$pdf->Ln();
$pdf->Cell(35,6,'Klaster Asesmen', 'LB', 0,'L');
$pdf->Cell(15,6,'Nomor', 'LB', 0,'L');
$pdf->Cell(5,6,':', 'LTB', 0,'L');
$pdf->Cell(135,6,$sq['kode_skema'], 'LBR', 0,'L');
$pdf->Ln();
$pdf->Cell(50,6,'Tujuan Asesmen', 'LB', 0,'L');
$pdf->Cell(5,6,':', 'LTB', 0,'L');
$pdf->Cell(45,6,'[     ] Sertifikasi', 'LBR', 0,'L');
$pdf->Cell(45,6,'[     ] Sertifikasi Ulang', 'LBR', 0,'L');
$pdf->Cell(45,6,'', 'LBR', 0,'L');
$pdf->Ln();
//Cetak nomor halaman
$pdf->AliasNbPages();

// Bagian Rekomendasi
$pdf->AddPage();
$pdf->SetFont('Arial','B','10');
$pdf->Cell(190,6,'Daftar Unit Kompetensi :', '', 0,'L');
$pdf->Ln();

$pdf->Cell(190,4,'',0,'C');
$pdf->Ln();
$kettable1="Jenis Standar (Standar Khusus/Standar Internasional/SKKNI)";
$kali2=strlen($kettable1)/15;
$kalinya2=ceil($kali2);
$hspace2=4*$kalinya2;
$pdf->SetFont('Arial','B','10');
$pdf->Cell(10,$hspace2,'NO.', 'LTBR', 0,'C');
$pdf->Cell(40,$hspace2,'KODE UNIT', 'TBR', 0,'C');
$pdf->Cell(100,$hspace2,'JUDUL UNIT', 'TBR', 0,'C');
$pdf->MultiCell(0, 4, $kettable1, 'TBR','C', 0, 0, '', '', true, 0, false, true, 4, 'LR');
$pdf->Cell(190,0.5,'','TB',0,'C');
$pdf->Ln();
$sqlunitkompetensi="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sq[id]' ORDER BY `kode_unit` ASC";
$unitkompetensi=$conn->query($sqlunitkompetensi);
$nounk=1;
$pdf->SetFont('Arial','','10');
while ($unk=$unitkompetensi->fetch_assoc()){
	$pdf->SetWidths(array(10,40,100,40));
	$nounkt="  ".$nounk.".";
	$pdf->RowContent(array($nounkt,$unk['kode_unit'],$unk['judul'],$unk['jenis']));
	$nounk++;
}
$pdf->Cell(190,6,'',0,'C');
$pdf->Ln();
$pdf->SetFont('Arial','B','10');
$pdf->Cell(190,6,'Bagian 3 :  Bukti Kelengkapan Pemohon', '', 0,'L');
$pdf->Ln();
$pdf->Cell(5,6,'a.', '', 0,'L');
$pdf->Cell(185,6,'Bukti kelengkapan persyaratan dasar pemohon :', '', 0,'L');
$pdf->Ln();
$pdf->Cell(10,2,'', 'LT', 0,'C');
$pdf->Cell(110,2,'', 'LT', 0,'C');
$pdf->Cell(40,2,'', 'LT', 0,'C');
$pdf->Cell(30,2,'', 'LTR', 0,'C');
$pdf->Ln();
$pdf->Cell(10,2,'', 'L', 0,'C');
$pdf->Cell(110,2,'', 'L', 0,'C');
$pdf->Cell(40,2,'Ada *)', 'L', 0,'C');
$pdf->Cell(30,2,'', 'LR', 0,'C');
$pdf->Ln();
$pdf->Cell(10,2,'', 'L', 0,'C');
$pdf->Cell(110,2,'', 'L', 0,'C');
$pdf->Cell(40,2,'', 'LB', 0,'C');
$pdf->Cell(30,2,'', 'LR', 0,'C');
$pdf->Ln();
$pdf->Cell(10,2,'', 'L', 0,'C');
$pdf->Cell(110,2,'', 'L', 0,'C');
$pdf->Cell(20,2,'', 'L', 0,'C');
$pdf->Cell(20,2,'', 'L', 0,'C');
$pdf->Cell(30,2,'', 'LR', 0,'C');
$pdf->Ln();
$pdf->Cell(10,2,'No.', 'L', 0,'C');
$pdf->Cell(110,2,'Bukti Persyaratan', 'L', 0,'C');
$pdf->Cell(20,2,'', 'L', 0,'C');
$pdf->Cell(20,2,'Tidak', 'L', 0,'C');
$pdf->Cell(30,2,'Tidak Ada*)', 'LR', 0,'C');
$pdf->Ln();
$pdf->Cell(10,1,'', 'L', 0,'C');
$pdf->Cell(110,1,'', 'L', 0,'C');
$pdf->Cell(20,1,'memenuhi', 'L', 0,'C');
$pdf->Cell(20,1,'', 'L', 0,'C');
$pdf->Cell(30,1,'', 'LR', 0,'C');
$pdf->Ln();
$pdf->Cell(10,2,'', 'L', 0,'C');
$pdf->Cell(110,2,'', 'L', 0,'C');
$pdf->Cell(20,2,'', 'L', 0,'C');
$pdf->Cell(20,2,'memenuhi', 'L', 0,'C');
$pdf->Cell(30,2,'', 'LR', 0,'C');
$pdf->Ln();
$pdf->Cell(10,2,'', 'L', 0,'C');
$pdf->Cell(110,2,'', 'L', 0,'C');
$pdf->Cell(20,2,'syarat', 'L', 0,'C');
$pdf->Cell(20,2,'', 'L', 0,'C');
$pdf->Cell(30,2,'', 'LR', 0,'C');
$pdf->Ln();
$pdf->Cell(10,2,'', 'L', 0,'C');
$pdf->Cell(110,2,'', 'L', 0,'C');
$pdf->Cell(20,2,'', 'L', 0,'C');
$pdf->Cell(20,2,'syarat', 'L', 0,'C');
$pdf->Cell(30,2,'', 'LR', 0,'C');
$pdf->Ln();
$pdf->Cell(10,2,'', 'LB', 0,'C');
$pdf->Cell(110,2,'', 'LB', 0,'C');
$pdf->Cell(20,2,'', 'LB', 0,'C');
$pdf->Cell(20,2,'', 'LB', 0,'C');
$pdf->Cell(30,2,'', 'LBR', 0,'C');
$pdf->Ln();
$pdf->Cell(190,0.5,'','TB',0,'C');
$pdf->Ln();
$pdf->SetFont('Arial','','10');
$sqlgetskemapersyaratan="SELECT * FROM `skema_persyaratan` WHERE `id_skemakkni`='$sq[id]' ORDER BY `id` ASC";
$getskemapersyaratan=$conn->query($sqlgetskemapersyaratan);
$nosksy=1;
while ($sksy=$getskemapersyaratan->fetch_assoc()){
	$pdf->SetWidths(array(10,110,20,20,30));
	$nosksyt="  ".$nosksy.".";
	$pdf->RowContent(array($nosksyt,$sksy['persyaratan'],'','',''));
	$nosksy++;
}
$pdf->Cell(190,6,'',0,'C');
$pdf->Ln();
$pdf->SetFont('Arial','B','10');
$pdf->Cell(5,6,'b.', '', 0,'L');
$pdf->Cell(185,6,'Bukti kompetensi yang relevan :', '', 0,'L');
$pdf->Ln();

$pdf->Cell(10,2,'', 'LT', 0,'C');
$pdf->Cell(140,2,'', 'LT', 0,'C');
$pdf->Cell(40,2,'', 'LTR', 0,'C');
$pdf->Ln();
$pdf->Cell(10,2,'', 'L', 0,'C');
$pdf->Cell(140,2,'', 'L', 0,'C');
$pdf->Cell(40,2,'Lampiran Bukti*)', 'LR', 0,'C');
$pdf->Ln();
$pdf->Cell(10,2,'', 'L', 0,'C');
$pdf->Cell(140,2,'', 'L', 0,'C');
$pdf->Cell(40,2,'', 'LBR', 0,'C');
$pdf->Ln();
$pdf->Cell(10,2,'No.', 'L', 0,'C');
$pdf->Cell(140,2,'Rincian Bukti Pendidikan/Pelatihan, Pengalaman Kerja,', 'L', 0,'C');
$pdf->Cell(20,2,'', 'L', 0,'C');
$pdf->Cell(20,2,'', 'LR', 0,'C');
$pdf->Ln();
$pdf->Cell(10,2,'', 'L', 0,'C');
$pdf->Cell(140,2,'', 'L', 0,'C');
$pdf->Cell(20,2,'', 'L', 0,'C');
$pdf->Cell(20,2,'Tidak', 'LR', 0,'C');
$pdf->Ln();
$pdf->Cell(10,2,'', 'L', 0,'C');
$pdf->Cell(140,2,'Pengalaman Hidup', 'L', 0,'C');
$pdf->Cell(20,2,'Ada', 'L', 0,'C');
$pdf->Cell(20,2,'', 'LR', 0,'C');
$pdf->Ln();
$pdf->Cell(10,2,'', 'L', 0,'C');
$pdf->Cell(140,2,'', 'L', 0,'C');
$pdf->Cell(20,2,'', 'L', 0,'C');
$pdf->Cell(20,2,'Ada', 'LR', 0,'C');
$pdf->Ln();
$pdf->Cell(10,2,'', 'LB', 0,'C');
$pdf->Cell(140,2,'', 'LB', 0,'C');
$pdf->Cell(20,2,'', 'LB', 0,'C');
$pdf->Cell(20,2,'', 'LBR', 0,'C');
$pdf->Ln();
$pdf->Cell(190,0.5,'','TB',0,'C');
$pdf->Ln();

$sqlgetasesodoc="SELECT * FROM `asesi_doc` WHERE `id_asesi`='$as[no_pendaftaran]' AND `id_skemakkni`='$sq[id]'";
$getasesodoc=$conn->query($sqlgetasesodoc);
$jumasesidoc=$getasesodoc->num_rows;
$noasesidoc=1;
$pdf->SetFont('Arial','','10');
if ($jumasesidoc==0){
	while ($noasesidoc<=5){
		$noasesidoct="  ".$noasesidoc.".";
		$pdf->Cell(10,10,$noasesidoct, 'LB', 0,'C');
		$pdf->Cell(140,10,'', 'LB', 0,'C');
		$pdf->Cell(20,10,'', 'LB', 0,'C');
		$pdf->Cell(20,10,'', 'LBR', 0,'C');
		$pdf->Ln();
		$noasesidoc++;
	}
}else{
	while ($asdoc=$getasesodoc->fetch_assoc()){
		$pdf->SetWidths(array(10,140,20,20));
		$noasesidoc=" ".$noasesidoc.".";
		$tampildoc=$asdoc['nama_doc']." Tahun ".$asdoc['tahun_doc']." No. ".$asdoc['nomor_doc']." Tanggal ".tgl_indo($asdoc['tgl_doc']);
		$pdf->RowContent(array($noasesidoc,$tampildoc,'',''));
		$noasesidoc++;

	}
}
$pdf->SetFont('Arial','I','8');
$pdf->Cell(190,4,'*) Diisi oleh LSP','',0,'L');
$pdf->Ln();

//Cetak nomor halaman
$pdf->AliasNbPages();

// Bagian Rekomendasi
$pdf->AddPage();
$pdf->SetFont('Arial','B','10');
$pdf->Cell(95,5,'Rekomendasi (diisi oleh LSP):', 'TL', 0,'L');
$pdf->Cell(95,5,'Pemohon : ', 'TBLR', 0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','','10');
$pdf->Cell(95,5,'Berdasarkan ketentuan persyaratan dasar pemohon,', 'L', 0,'L');
$pdf->Cell(30,5,'Nama', 'BL', 0,'L');
$pdf->SetFont('Arial','B','10');
$pdf->Cell(65,5,$as['nama'], 'BLR', 0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','','10');
$pdf->Cell(95,5,'Pemohon :', 'L', 0,'L');
$pdf->Cell(30,5,'Tandatangan/', 'TL', 0,'L');
$pdf->Cell(65,5,'', 'LR', 0,'C');
$pdf->Ln();
$pdf->SetFont('Arial','B','10');
$pdf->Cell(45,5,'Diterima/ Tidak  diterima*)', 'L', 0,'L');
$pdf->SetFont('Arial','','10');
$pdf->Cell(50,5,'sebagai peserta  sertifikasi', '', 0,'L');
$pdf->Cell(30,5,'Tanggal', 'L', 0,'L');
$pdf->Cell(65,5,'', 'LR', 0,'C');
$pdf->Ln();
$pdf->SetFont('Arial','','8');
$pdf->Cell(95,20,'*) coret yang tidak sesuai', 'LB', 0,'L');
$pdf->Cell(30,20,'', 'BL', 0,'L');
$pdf->Cell(65,20,'', 'BLR', 0,'C');
$pdf->Ln();
$pdf->SetFont('Arial','B','10');
$pdf->Cell(95,5,'Catatan :', 'TL', 0,'L');
$pdf->Cell(95,5,'Admin LSP : ', 'TBLR', 0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','','10');
$pdf->Cell(95,5,'', 'L', 0,'L');
$pdf->Cell(30,5,'Nama', 'BL', 0,'L');
$pdf->SetFont('Arial','B','10');
$pdf->Cell(65,5,$adm['nama_lengkap'], 'BLR', 0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','','10');
$pdf->Cell(95,5,'', 'L', 0,'L');
$pdf->Cell(30,5,'NIK LSP', 'BL', 0,'L');
$pdf->Cell(65,5,$adm['no_induk'], 'BLR', 0,'L');
$pdf->Ln();
$pdf->Cell(95,5,'', 'L', 0,'L');
$pdf->Cell(30,5,'Tandatangan/', 'L', 0,'L');
$pdf->Cell(65,5,'', 'LR', 0,'C');
$pdf->Ln();
$pdf->Cell(95,5,'', 'L', 0,'L');
$pdf->Cell(30,5,'Tanggal', 'L', 0,'L');
$pdf->Cell(65,5,'', 'LR', 0,'C');
$pdf->Ln();
$pdf->Cell(95,20,'', 'LB', 0,'L');
$pdf->Cell(30,20,'', 'BL', 0,'L');
$pdf->Cell(65,20,'', 'BLR', 0,'C');
$pdf->Ln();

/* $pdf->Cell(190,150,'', '', 0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','B','10');
$pdf->Cell(95,5,'Ditetapkan oleh : Manajer Sertifikasi', 'LTB', 0,'L');
$pdf->Cell(95,5,'Disyahkan oleh : Direktur', 'LTBR', 0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','','8');
$pdf->Cell(20,5,'Nama', 'BL', 0,'L');
$pdf->Cell(75,5,$lq['penanggungjawab'], 'BLR', 0,'L');
$pdf->Cell(20,5,'Nama', 'BL', 0,'L');
$pdf->Cell(75,5,$lq['direktur'], 'BLR', 0,'L');
$pdf->Ln();
$pdf->Cell(20,5,'Tandatangan/', 'L', 0,'L');
$pdf->Cell(75,5,'', 'LR', 0,'C');
$pdf->Cell(20,5,'Tandatangan/', 'L', 0,'L');
$pdf->Cell(75,5,'', 'LR', 0,'C');
$pdf->Ln();
$pdf->Cell(20,5,'Tanggal', 'L', 0,'L');
$pdf->Cell(75,5,'', 'LR', 0,'C');
$pdf->Cell(20,5,'Tanggal', 'L', 0,'L');
$pdf->Cell(75,5,'', 'LR', 0,'C');
$pdf->Ln();
$pdf->Cell(20,5,'', 'BL', 0,'L');
$pdf->Cell(75,5,'', 'BLR', 0,'C');
$pdf->Cell(20,5,'', 'BL', 0,'L');
$pdf->Cell(75,5,'', 'BLR', 0,'C');
$pdf->Ln(); */

$pdf->AliasNbPages();

//output file pdf
$fileoutputnya="FORM-APL-01-".$skemakkni."-".$as['nama'].".pdf";
$pdf->Output($fileoutputnya,'D');

?>