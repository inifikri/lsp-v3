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
$noasr=1;
$namaasesor="";
$getasesor=$conn->query("SELECT * FROM `jadwal_asesor` WHERE `id_jadwal`='$jdq[id]'");
while ($gas=$getasesor->fetch_assoc()){
	$sqlasesor="SELECT * FROM `asesor` WHERE `id`='$gas[id_asesor]'";
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
	if ($noasr>1){
		$namaasesor=$namaasesor.", ".$namaasesor;
	}else{
		$namaasesor=$namaasesor;
	}
	$noasr++;
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
$getkodeverifikasi=$as['no_pendaftaran'].$jdq['id'].date("Y-m-d")."FORM-CHECKLIST";
$kodeverifikasi=md5($getkodeverifikasi);
$kodever=substr($kodeverifikasi,-8);
$pdf->Code39(10,280,$kodever,1,7);
// riwayat cetak
$alamatip=$_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
$sqllogcetak="INSERT INTO `logcetak`(`kodeverifikasi`, `id_jadwal`, `id_asesi`, `form`, `ip`) VALUES ('$kodever','$jdq[id]','$as[no_pendaftaran]','FORM-CHECKLIST', '$alamatip')";
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
$pdf->Cell(190,5,'CEKLIS KELENGKAPAN DOKUMEN ASESMEN', '', 0,'C');
$pdf->Ln();
$pdf->SetFont('Arial','I','10');
$pdf->Cell(190,5,'*(Pilih dan sesuaikan dengan dokumen yang dibuat, coret yang tidak sesuai)', '', 0,'C');
$pdf->Ln();

$pdf->SetFont('Arial','B','10');
$pdf->Cell(10,2,'', 'LT', 0,'C');
$pdf->Cell(100,2,'', 'LT', 0,'C');
$pdf->Cell(40,2,'', 'LT', 0,'C');
$pdf->Cell(40,2,'', 'LTR', 0,'C');
$pdf->Ln();
$pdf->Cell(10,2,'', 'L', 0,'C');
$pdf->Cell(100,2,'', 'L', 0,'C');
$pdf->Cell(40,2,'STATUS', 'L', 0,'C');
$pdf->Cell(40,2,'', 'LR', 0,'C');
$pdf->Ln();
$pdf->Cell(10,2,'', 'L', 0,'C');
$pdf->Cell(100,2,'', 'L', 0,'C');
$pdf->Cell(40,2,'', 'LB', 0,'C');
$pdf->Cell(40,2,'', 'LR', 0,'C');
$pdf->Ln();
$pdf->Cell(10,2,'', 'L', 0,'C');
$pdf->Cell(100,2,'', 'L', 0,'C');
$pdf->Cell(20,2,'', 'L', 0,'C');
$pdf->Cell(20,2,'', 'L', 0,'C');
$pdf->Cell(40,2,'', 'LR', 0,'C');
$pdf->Ln();
$pdf->Cell(10,2,'NO.', 'L', 0,'C');
$pdf->Cell(100,2,'JENIS DOKUMEN', 'L', 0,'C');
$pdf->Cell(20,2,'', 'L', 0,'C');
$pdf->Cell(20,2,'BELUM', 'L', 0,'C');
$pdf->Cell(40,2,'KETERANGAN', 'LR', 0,'C');
$pdf->Ln();
$pdf->Cell(10,1,'', 'L', 0,'C');
$pdf->Cell(100,1,'', 'L', 0,'C');
$pdf->Cell(20,1,'', 'L', 0,'C');
$pdf->Cell(20,1,'', 'L', 0,'C');
$pdf->Cell(40,1,'', 'LR', 0,'C');
$pdf->Ln();
$pdf->Cell(10,2,'', 'L', 0,'C');
$pdf->Cell(100,2,'', 'L', 0,'C');
$pdf->Cell(20,2,'ADA', 'L', 0,'C');
$pdf->Cell(20,2,'', 'L', 0,'C');
$pdf->Cell(40,2,'', 'LR', 0,'C');
$pdf->Ln();
$pdf->Cell(10,3,'', 'L', 0,'C');
$pdf->Cell(100,3,'', 'L', 0,'C');
$pdf->Cell(20,3,'', 'L', 0,'C');
$pdf->Cell(20,3,'ADA', 'L', 0,'C');
$pdf->Cell(40,3,'', 'LR', 0,'C');
$pdf->Ln();
$pdf->Cell(10,1,'', 'LB', 0,'C');
$pdf->Cell(100,1,'', 'LB', 0,'C');
$pdf->Cell(20,1,'', 'LB', 0,'C');
$pdf->Cell(20,1,'', 'LB', 0,'C');
$pdf->Cell(40,1,'', 'LBR', 0,'C');
$pdf->Ln();
$pdf->Cell(190,0.5,'','TB',0,'C');
$pdf->Ln();

$pdf->SetFont('Arial','B','10');
$pdf->SetWidths(array(10,180));
$pdf->RowContent(array('  A.','PELAKSANAAN ASESMEN'));
$pdf->SetFont('Arial','','10');
$pdf->SetWidths(array(10,100,20,20,40));
$pdf->RowContent(array('  1.','FR.APL.01 Permohonan Sertifikasi Kompetensi','','',''));
$pdf->RowContent(array('  2.','FR.APL.02 Asesmen Mandiri','','',''));
$pdf->RowContent(array('  3.','Portofolio Asesi','','',''));
$pdf->RowContent(array('  4.','FR.MAPA.01 Merencanakan Aktivitas dan Proses Asesmen','','',''));
$pdf->RowContent(array('  5.','Skema Sertifikasi & Standar Kompetensi','','',''));
$pdf->RowContent(array('  6.','FR.MAPA.02 Peta MUK dari Hasil Pendekatan Asesmen dan Perencanaan Asesmen','','',''));
$pdf->RowContent(array('  7.','FR.AK.01 Formulir Persetujuan Asesmen & Kerahasiaan','','',''));
$pdf->RowContent(array('  8.','FR.AK.04 Formulir Banding','','',''));
$pdf->RowContent(array('  9.','FR.IA.01 Ceklis Observasi Aktivitas di Tempat Kerja atau Tempat Kerja Simulasi','','',''));
$pdf->RowContent(array(' 10.','FR.IA.02 Tugas Praktik Demonstrasi','','',''));
$pdf->RowContent(array(' 11.','FR.IA.03 Pertanyaan untuk Mendukung Observasi','','',''));
$pdf->RowContent(array(' 12.','FR.IA.04 Penjelasan Singkat Proyek Terkait Pekerjaan / Kegiatan Terstruktur Lainnya','','',''));
$pdf->RowContent(array(' 13.','FR.IA.05 Pertanyaan Tertulis Pilihan Ganda + Kunci Jawaban dan Lembar Jawab','','',''));
$pdf->RowContent(array(' 14.','FR.IA.06 Pertanyaan Tertulis Pilihan Esai + Kunci Jawaban dan Lembar Jawab','','',''));
$pdf->RowContent(array(' 15.','FR.IA.07 Pertanyaan Lisan','','',''));
$pdf->RowContent(array(' 16.','FR.IA.08 Ceklis Verifikasi Portofolio','','',''));
$pdf->RowContent(array(' 17.','FR.IA.09 Pertanyaan Wawancara','','',''));
$pdf->RowContent(array(' 18.','FR.IA.10 Klarifikasi Bukti Pihak Ketiga','','',''));
$pdf->RowContent(array(' 19.','FR.IA.11 Ceklis meninjau Materi Uji Kompetensi','','',''));

$pdf->SetFont('Arial','B','10');
$pdf->SetWidths(array(10,180));
$pdf->RowContent(array('  B.','KEPUTUSAN'));
$pdf->SetFont('Arial','','10');
$pdf->SetWidths(array(10,100,20,20,40));
$pdf->RowContent(array(' 20.','FR.AK.02 Formulir Rekaman Asesmen Kompetensi','','',''));
$pdf->RowContent(array(' 21.','FR.AK.03 Umpan Balik dan Catatan Asesmen','','',''));
$pdf->SetFont('Arial','B','10');
$pdf->SetWidths(array(10,180));
$pdf->RowContent(array('  C.','LAPORAN & MENINJAU ASESMEN'));
$pdf->SetFont('Arial','','10');
$pdf->SetWidths(array(10,100,20,20,40));
$pdf->RowContent(array(' 22.','FR.AK.05 Laporan Asesmen','','',''));
$pdf->RowContent(array(' 23.','FR.AK.06 Meninjau Proses Asesmen','','',''));
$pdf->SetFont('Arial','B','10');
$pdf->SetWidths(array(10,180));
$pdf->RowContent(array('  D.','VALIDASI'));
$pdf->SetFont('Arial','','10');
$pdf->SetWidths(array(10,100,20,20,40));
$pdf->RowContent(array(' 24.','FR.VA Memberikan Kontribusi dalam Validasi Asesmen','','',''));

//Tandatangan Peserta dan Asesor
$pdf->Cell(190,0.5,'', 'TB', 0,'C');
$pdf->Ln();
$pdf->SetFont('Arial','B','10');
$pdf->Cell(95,4,'Peserta :', 'LT', 0,'L');
$pdf->Cell(95,4,'Asesor :', 'LTR', 0,'L');
$pdf->Ln();

$pdf->SetFont('Arial','','10');
$pdf->Cell(20,4,'Nama', 'LT', 0,'L');
$pdf->Cell(5,4,':', 'T', 0,'C');
$pdf->SetFont('Arial','B','10');
$pdf->Cell(70,4,$as['nama'], 'T', 0,'L');
$pdf->SetFont('Arial','','10');
$pdf->Cell(20,4,'Nama', 'LT', 0,'L');
$pdf->Cell(5,4,':', 'T', 0,'C');
$pdf->SetFont('Arial','B','10');
$pdf->Cell(70,4,$namaasesor, 'TR', 0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','','10');
$pdf->Cell(20,17,'Paraf', 'LTB', 0,'L');
$pdf->Cell(5,17,':', 'TB', 0,'C');
$pdf->Cell(70,17,'', 'TB', 0,'L');
$pdf->Cell(20,17,'Paraf', 'LTB', 0,'L');
$pdf->Cell(5,17,':', 'TB', 0,'C');
$pdf->Cell(70,17,'', 'TBR', 0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','','8');
$pdf->SetWidths(array(190));
$pdf->RowContentNoBorder(array('CATATAN : Asesor baru menandatangani dokumen ini apabila dokumen telah diperbaiki dan tidak ada perbaikan lagi'));
// nomor halaman
$pdf->AliasNbPages();

//output file pdf
$fileoutputnya="CEKLIS-".$skemakkni."-".$as['nama'].".pdf";
$pdf->Output($fileoutputnya,'D');

?>