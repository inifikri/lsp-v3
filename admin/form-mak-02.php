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
$getkodeverifikasi=$as['no_pendaftaran'].$jdq['id'].date("Y-m-d")."FORM-MAK-03";
$kodeverifikasi=md5($getkodeverifikasi);
$kodever=substr($kodeverifikasi,-8);
$pdf->Code39(10,280,$kodever,1,7);
// riwayat cetak
$alamatip=$_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
$sqllogcetak="INSERT INTO `logcetak`(`kodeverifikasi`, `id_jadwal`, `id_asesi`, `form`, `ip`) VALUES ('$kodever','$jdq[id]','$as[no_pendaftaran]','FORM-MAK-03', '$alamatip')";
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
$pdf->Cell(190,6,'FR-MAK-02. FORMULIR BANDING ASESMEN', '', 0,'L');
$pdf->Ln();
//$pdf->SetWidths(array(190));
//$keterangan1="Persetujuan Asesmen ini untuk menjamin bahwa peserta telah diberi arahan secara rinci tentang proses asesmen";
//$pdf->RowContent(array($keterangan1));
$namaasesi=ucwords(strtolower($as['nama']));
//$pdf->Cell(190,10,'Persetujuan Asesmen ini untuk menjamin bahwa peserta telah diberi arahan secara rinci tentang proses asesmen', 'LTRB', 0,'L');
$pdf->SetFont('Arial','','10');
$pdf->Cell(50,6,'Nama Peserta', 'LTB', 0,'L');
$pdf->SetFont('Arial','B','10');
$pdf->Cell(140,6,$namaasesi, 'LTBR', 0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','','10');
$pdf->Cell(50,6,'Nama Asesor', 'LB', 0,'L');
$pdf->SetFont('Arial','B','10');
$pdf->Cell(140,6,$namaasesor, 'LBR', 0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','','10');
$pdf->Cell(50,6,'Tanggal Asesmen', 'LB', 0,'L');
$pdf->SetFont('Arial','B','10');
$pdf->Cell(140,6,$tgl_cetak, 'LBR', 0,'L');
$pdf->Ln();

$pdf->SetFont('Arial','','10');
$pdf->setFillColor(198,198,198);
$pdf->Cell(150,8,'Jawablah dengan Ya atau Tidak pertanyaan-pertanyaan berikut ini :', 'LB', 0,'L',TRUE);
$pdf->Cell(20,8,'YA', 'LB', 0,'C',TRUE);
$pdf->Cell(20,8,'TIDAK', 'LBR', 0,'C',TRUE);
$pdf->Ln();

$pdf->SetFont('Arial','','10');
$pdf->setFillColor(255,255,255);
$pdf->SetWidths(array(150,20,20));
$pdf->RowContent(array('Apakah Proses Banding telah dijelaskan kepada Anda?','',''));
$pdf->RowContent(array('Apakah Anda telah mendiskusikan Banding dengan Asesor?','',''));
$pdf->RowContent(array('Apakah Anda mau melibatkan “orang lain” membantu Anda dalam Proses Banding?','',''));
$pdf->Cell(190,6,'Banding ini diajukan atas Keputusan Asesmen yang dibuat terhadap Unit Kompetensi berikut :', 'LR', 0,'L');
$pdf->Ln();

$sqlunitkompetensi2="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sq[id]' ORDER BY `kode_unit` ASC";
$unitkompetensi2=$conn->query($sqlunitkompetensi2);
$nounk2=1;
while ($unk2=$unitkompetensi2->fetch_assoc()){

	if ($nounk2=='1'){
		$pdf->Cell(40,5,'Kode Unit Kompetensi','L','T');
		$pdf->Cell(5,5,':','','LT');
		$pdf->Cell(5,5,$nounk2,'','T');
		$pdf->MultiCell(140,5,$unk2['kode_unit'],'R','L');

		//$pdf->SetWidths(array(50,140));
		//$pdf->RowContentNoBorderTB(array('Kode Unit Kompetensi',$unk2['kode_unit']));

	}else{
		$pdf->Cell(40,5,'','L','L');
		$pdf->Cell(5,5,'','','LT');
		$pdf->Cell(5,5,$nounk2,'','T');
		$pdf->MultiCell(140,5,$unk2['kode_unit'],'R','L');

		//$pdf->SetWidths(array(50,140));
		//$pdf->RowContentNoBorderTB(array('',$unk2['kode_unit']));

	}
	//$pdf->Ln();
	$nounk2++;
}

$sqlunitkompetensi="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sq[id]' ORDER BY `kode_unit` ASC";
$unitkompetensi=$conn->query($sqlunitkompetensi);
$nounk=1;
$pdf->SetFont('Arial','','10');
while ($unk=$unitkompetensi->fetch_assoc()){
	if ($nounk=='1'){
		//Calculate the height of the row
		$baris=strlen($unk['judul'])/75;
		$baris2=ceil($baris);
		$h=5*$baris2;
		$pdf->Cell(40,$h,'Judul Unit Kompetensi','L','T');
		$pdf->Cell(5,$h,':','','LT');
		$pdf->Cell(5,$h,$nounk,'','T');
		$pdf->MultiCell(140,5,$unk['judul'],'R','L');
		//$pdf->Ln();
		//$pdf->SetWidths(array(50,140));
		//$pdf->RowContentNoBorderTB(array('Judul Unit Kompetensi', $unk['judul']));
	}else{
		//Calculate the height of the row
		$baris=strlen($unk['judul'])/75;
		$baris2=ceil($baris);
		$h=5*$baris2;
		$pdf->Cell(40,$h,'','L','L');
		$pdf->Cell(5,$h,'','','LT');
		$pdf->Cell(5,$h,$nounk,'','T');
		$pdf->MultiCell(140,5,$unk['judul'],'R','L');
		//$pdf->Ln();
		//$pdf->SetWidths(array(50,140));
		//$pdf->RowContentNoBorderTB(array('', $unk['judul']));
	}
	//$pdf->Ln();
	$nounk++;
}

/*
// Cell(width, height, text, border, end line, [align])

//normal row height=5

$pdf->cell(20,10,'ID',1,0); //vertical merged cell, height=2x row height=2x5=10
$pdf->cell(50,10,'Nama',1,0); //vertically merges cell
$pdf->cell(100,5,'Skor',1,0); //normal height, but occupy 4 columns (horizontally merged)
$pdf->cell(20,10,'Passing',1,0); //vertically merged cell
$pdf->cell(0,5,'',0,1); //dummy line ending, height=5(normal row height) width=0 should be invisible

//second line(row)
$pdf->cell(20,5,'',0,0); //dummy cell to align next cell, should be invisible
$pdf->cell(25,5,'Q1',1,0);
$pdf->cell(25,5,'Q1',1,0);
$pdf->cell(25,5,'Q1',1,0);
$pdf->cell(25,5,'Q1',1,0);

//data rows
$pdf->cell(20,5,' ',1,0);
$pdf->cell(50,5,' ',1,0);
$pdf->cell(25,5,' ',1,0);
$pdf->cell(25,5,' ',1,0);
$pdf->cell(25,5,' ',1,0);
$pdf->cell(25,5,' ',1,0);
$pdf->cell(25,5,' ',1,0);
//.....*/


$pdf->Cell(190,7,'Banding ini diajukan atas alasan sebagai berikut :', 'LTR', 0,'L');
$pdf->Ln();
$pdf->Cell(190,25,'', 'LBR', 0,'L');
$pdf->Ln();

$pdf->SetWidths(array(190));
$pdf->RowContent(array('Anda mempunyai hak mengajukan banding jika Anda mendapatkan hasil yang Tidak Sah dan/atau Proses Tidak Sah atau Tidak Adil.'));

$pdf->SetFont('Arial','','10');
$pdf->Cell(190,14,'', 'LTR', 0,'L');
$pdf->Ln();
$pdf->Cell(35,7,'Tandatangan Peserta', 'L', 0,'L');
$pdf->Cell(5,7,':', '', 0,'C');
$pdf->SetFont('Arial','B','10');
$pdf->Cell(70,7,$namaasesi, '', 0,'L');
$pdf->SetFont('Arial','','10');
$pdf->Cell(15,7,'Tanggal', '', 0,'L');
$pdf->Cell(5,7,':', '', 0,'C');
$pdf->SetFont('Arial','B','10');
$pdf->Cell(60,7,'', 'R', 0,'L');
$pdf->Ln();
$pdf->Cell(190,7,'', 'LBR', 0,'L');
$pdf->Ln();


//Cetak nomor halaman
$pdf->AliasNbPages();
$kode3=$_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
// riwayat cetak
//mysql_query("INSERT INTO `logcdhujian`(`IP`, `kodethak`, `kodekelas`, `total_peserta`, `prodi`, `kodedosen`, `remark`) VALUES ('$kode3','$tampilperiode','$row2[kodekelas]','$jmsql','$progdinya','$row2[kodedosen]','Dicetak BAAK')");


//output file pdf
$fileoutputnya="form-mak-02-".$skemakkni."-".$as['no_pendaftaran'].".pdf";
$pdf->Output($fileoutputnya,'D');

?>