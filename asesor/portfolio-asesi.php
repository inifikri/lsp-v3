<?php
/*ini_set('display_errors',1); 
error_reporting(E_ALL);
header('Content-type: application/pdf');

session_start();
if (!isset($_SESSION['namauser'])){
header("Location:index.php");
}*/

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
$pdf->Image('../images/logolsp.jpg',15,15,25,25);
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
$pdf->Cell(190,6,'PORTOFOLIO CALON PESERTA SERTIFIKASI', '', 0,'L');
$pdf->Ln();
$skemaid=strtoupper($skemakkni);
$pdf->SetFont('Arial','B','10');
$pdf->Cell(190,6,$skemaid, '', 0,'L');
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
$pdf->SetFont('Arial','B','10');
$pdf->Cell(10,6,'b.', '', 0,'L');
$pdf->Cell(180,6,'Data Pendidikan', '', 0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','','10');
$pdf->Cell(10,6,'', '', 0,'L');
$pdf->Cell(50,6,'Nama Universitas/ Lembaga', '', 0,'L');
$pdf->Cell(5,6,':', '', 0,'L');
$sqlpendidikan="SELECT * FROM `pendidikan` WHERE `id`='$as[pendidikan]'";
$pendidikan=$conn->query($sqlpendidikan);
$pdd=$pendidikan->fetch_assoc();
$pendidikannyaa=$as['prodi']." (".$pdd['jenjang_pendidikan'].")";
$pdf->Cell(125,6,$as['lembaga_pendidikan'], 'B', 0,'L');
$pdf->Ln();
$pdf->Cell(10,6,'', '', 0,'L');
$pdf->Cell(50,6,'Jurusan/ Program', '', 0,'L');
$pdf->Cell(5,6,':', '', 0,'L');
$pdf->Cell(125,6,$pendidikannyaa, 'B', 0,'L');
$pdf->Ln();
$pdf->Cell(10,6,'', '', 0,'L');
$pdf->Cell(50,6,'Tahun Lulus', '', 0,'L');
$pdf->Cell(5,6,':', '', 0,'L');
$pdf->Cell(125,6,$as['tahun_lulus'], 'B', 0,'L');
$pdf->Ln();


$pdf->Cell(190,4,'', '', 0,'L');
$pdf->Ln();

$pdf->SetFont('Arial','B','10');
$pdf->Cell(10,6,'c.', '', 0,'L');
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
$pdf->Cell(125,6,$as['jabatan'], 'B', 0,'L');
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
$pdf->Ln();
$pdf->Cell(190,5,'', '', 0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','B','10');
$pdf->SetWidths(array(10,180));
$pdf->RowContentNoBorder(array('d.','Pengalaman Penelitian/Tulisan terkait (lampirkan halaman depan yang mencantumkan nama dan judul penelitian/tulisan)'));
$pdf->Ln();
$pdf->SetFont('Arial','B','10');
$pdf->SetWidths(array(10,100,20,60));
$pdf->RowContent(array('No.','Nama Penelitian/Judul Tulisan','Tahun','Kategori Kegiatan (Penelitian/Tulisan)'));
$pdf->SetWidths(array(10,100,20,60));
$pdf->SetFont('Arial','','10');
$sqlgetportfolio="SELECT * FROM `asesi_portfolio` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `jenis_portfolio`='Karya Ilmiah' ORDER BY `tgl_doc` DESC";
$getportfolio=$conn->query($sqlgetportfolio);
$nopenelitian=1;
while ($gpf=$getportfolio->fetch_assoc()){
	$pdf->RowContent(array($nopenelitian,$gpf['nama_doc'],$gpf['tahun_doc'],$gpf['peran_portfolio']));
	$pdf->Ln();
	$nopenelitian++;
}
$pdf->Cell(190,5,'', '', 0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','B','10');
$pdf->SetWidths(array(10,180));
$pdf->RowContentNoBorder(array('e.','Kegiatan Diklat/Bimtek/Seminar/Workshop/Lokakarya/kursus (Setifikat dilampirkan)'));
$pdf->Ln();
$pdf->SetFont('Arial','B','10');
$pdf->SetWidths(array(10,60,20,60,40));
$pdf->RowContent(array('No.','Nama Kegiatan','Tahun','Kategori Kegiatan (Diklat/Bimtek/Seminar/Workshop/Lokakarya/Kursus)','Peran dalam kegiatan (narasumber/pengajar/moderator/panitia)'));
$pdf->SetFont('Arial','','10');
$pdf->SetWidths(array(10,60,20,60,40));
$sqlgetportfolio2="SELECT * FROM `asesi_portfolio` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `jenis_portfolio`='Pelatihan' ORDER BY `tgl_doc` DESC";
$getportfolio2=$conn->query($sqlgetportfolio2);
$nopenelitian2=1;
while ($gpf2=$getportfolio2->fetch_assoc()){
	$pdf->RowContent(array($nopenelitian,$gpf2['nama_doc'],$gpf2['tahun_doc'],$gpf2['jenis_portfolio'],$gpf2['peran_portfolio']));
	$pdf->Ln();
	$nopenelitian2++;
}

$pdf->AliasNbPages();

//output file pdf
$fileoutputnya="Portfolio-".$skemakkni."-".$as['nama'].".pdf";
$pdf->Output($fileoutputnya,'D');

?>