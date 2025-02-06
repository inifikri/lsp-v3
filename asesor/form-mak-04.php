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
$getkodeverifikasi=$as['no_pendaftaran'].$jdq['id'].date("Y-m-d")."FORM-APL-02";
$kodeverifikasi=md5($getkodeverifikasi);
$kodever=substr($kodeverifikasi,-8);
$pdf->Code39(10,280,$kodever,1,7);
// riwayat cetak
$alamatip=$_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
$sqllogcetak="INSERT INTO `logcetak`(`kodeverifikasi`, `id_jadwal`, `id_asesi`, `form`, `ip`) VALUES ('$kodever','$jdq[id]','$as[no_pendaftaran]','FORM-APL-02', '$alamatip')";
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
$pdf->Cell(190,6,'FR-MAK-04. KEPUTUSAN DAN UMPAN BALIK ASESMEN', '', 0,'L');
$pdf->Ln();
$pdf->Cell(190,5,'', '', 0,'L');
$pdf->Ln();
$skemaid=strtoupper($skemakkni);
//$pdf->SetFont('Arial','B','10');
//$pdf->Cell(190,10,$skemaid, '', 0,'L');
//$pdf->Ln();
$pdf->SetFont('Arial','','10');
$pdf->Cell(50,5,'Skema Sertifikasi', '', 0,'L');
$pdf->Cell(5,5,':', '', 0,'C');
$pdf->SetFont('Arial','B','10');
$pdf->Cell(135,5,$skemaid, '', 0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','','10');
$pdf->Cell(50,5,'Nomor Skema Sertifikasi', '', 0,'L');
$pdf->Cell(5,5,':', '', 0,'C');
$pdf->SetFont('Arial','B','10');
$pdf->Cell(135,5,$sq['kode_skema'], '', 0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','B','10');
$pdf->Cell(190,5,'', '', 0,'L');
$pdf->Ln();

$sqljenistuknya="SELECT * FROM `tuk_jenis` WHERE `id`='$tq[jenis_tuk]'";
$jenistuknya=$conn->query($sqljenistuknya);
$tuknya=$jenistuknya->fetch_assoc();
$jenisnyatuk=$tuknya['jenis_tuk'];

$pdf->SetFont('Arial','','10');
$pdf->SetWidths(array(30,5,60,30,5,60));
$tglas=tgl_indo($jdq['tgl_asesmen']);
$tglas2=$tglas." Pukul ".$jdq['jam_asesmen'];
$pdf->RowContent(array('Nama Peserta',':',$as['nama'],'Tanggal/ Waktu',':',$tglas2));
$pdf->RowContent(array('Nama Asesor',':',$namaasesor,'Tempat',':',$tq['nama']));

$pdf->SetFont('Arial','','10');

$pdf->SetFont('Arial','B','10');
$pdf->Cell(190,5,'Penjelasan untuk Asesor:', '', 0,'L');
$pdf->Ln();

$pdf->Cell(190,3,'', '', 0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','','10');
$ket1="Asesor mengorganisasikan pelaksanaan asesmen berdasarkan metoda dan instrumen/sumber-sumber asesmen seperti yang tercantum dalam perencanaan asesmen.";
$pdf->SetWidths(array(5,185));
$pdf->RowContentNoBorderJ(array('1.',$ket1));
$ket2="Asesor melaksanakan kegiatan pengumpulan bukti serta mendokumentasikan seluruh bukti pendukung yang dapat ditunjukkan oleh Asesi sesuai dengan kriteria unjuk kerja yang dipersyaratkan.";
$pdf->SetWidths(array(5,185));
$pdf->RowContentNoBorderJ(array('2.',$ket2));
$ket3="Asesor membuat keputusan apakah Asesi sudah Kompeten (K), Belum kompeten (BK) atau Asesmen Lanjut (AL), untuk setiap kriteria unjuk kerja berdasarkan bukti-bukti.";
$pdf->SetWidths(array(5,185));
$pdf->RowContentNoBorderJ(array('3.',$ket3));
$ket4="Asesor memberikan umpan balik kepada Asesi mengenai pencapaian unjuk kerja dan Asesi juga diminta untuk memberikan umpan balik terhadap proses asesmen yang dilaksanakan (kuesioner).";
$pdf->SetWidths(array(5,185));
$pdf->RowContentNoBorderJ(array('4.',$ket4));
$ket5="Asesor dan Asesi bersama-sama menandatangani pelaksanaan asesmen.";
$pdf->SetWidths(array(5,185));
$pdf->RowContentNoBorderJ(array('5.',$ket5));
$ket6="Beri tanda ( v ) pada kolom yang dipilih dengan simbul (*).";
$pdf->SetWidths(array(5,185));
$pdf->RowContentNoBorderJ(array('6.',$ket6));

$pdf->Cell(190,5,'', '', 0,'R');
$pdf->Ln();
$pdf->SetFont('Arial','B','10');
$pdf->Cell(190,5,'PENCAPAIAN KOMPETENSI:', '', 0,'L');
$pdf->Ln();

$pdf->SetFont('Arial','','10');

$sqlunitkompetensi2="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sq[id]' ORDER BY `kode_unit` ASC";
$unitkompetensi2=$conn->query($sqlunitkompetensi2);
$nounk2=1;
while ($unk2=$unitkompetensi2->fetch_assoc()){

	if ($nounk2=='1'){
		$pdf->Cell(40,5,'Kode Unit Kompetensi','LT','T');
		$pdf->Cell(5,5,':','T','LT');
		$pdf->Cell(5,5,$nounk2,'T','T');
		$pdf->MultiCell(140,5,$unk2['kode_unit'],'TR','L');

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
		$pdf->Cell(40,$h,'Judul Unit Kompetensi','LT','T');
		$pdf->Cell(5,$h,':','T','LT');
		$pdf->Cell(5,$h,$nounk,'T','T');
		$pdf->MultiCell(140,5,$unk['judul'],'TR','L');
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
$pdf->Cell(190,5,'', 'T', 'L');
$pdf->Ln();

// Cell(width, height, text, border, end line, [align])
$pdf->SetFont('Arial','B','9');

$pdf->AddPage();

//normal row height=5
$pdf->setFillColor(198,198,198);
$pdf->Cell(35,1,'','LT',0,'C',TRUE); //vertical merged cell, height=2x row height=2x5=10
$pdf->Cell(60,1,'','LT',0,'C',TRUE); //vertically merges cell
$pdf->Cell(75,1,'','LT',0,'C',TRUE); //normal height, but occupy 4 columns (horizontally merged)
$pdf->Cell(20,1,'','LTR',0,'C',TRUE); //vertically merged cell
//$pdf->Cell(0,1,'',0,1); //dummy line ending, height=5(normal row height) width=0 should be invisible
$pdf->Ln();
$pdf->Cell(35,3,'','L',0,'C',TRUE); //vertical merged cell, height=2x row height=2x5=10
$pdf->Cell(60,3,'','L',0,'C',TRUE); //vertically merges cell
$pdf->Cell(75,3,'Bukti � Bukti (beri tanda v bila)','L',0,'C',TRUE); //normal height, but occupy 4 columns (horizontally merged)
$pdf->Cell(20,3,'','LR',0,'C',TRUE); //vertically merged cell
//$pdf->Cell(0,3,'',0,1); //dummy line ending, height=5(normal row height) width=0 should be invisible
$pdf->Ln();
$pdf->Cell(35,3,'','L',0,'C',TRUE); //vertical merged cell, height=2x row height=2x5=10
$pdf->Cell(60,3,'','L',0,'C',TRUE); //vertically merges cell
$pdf->Cell(75,3,'tersedia dan X bila tidak)','L',0,'C',TRUE); //normal height, but occupy 4 columns (horizontally merged)
$pdf->Cell(20,3,'Keputusan','LR',0,'C',TRUE); //vertically merged cell
$pdf->Ln();
$pdf->Cell(35,3,'Elemen','L',0,'C',TRUE); //vertical merged cell, height=2x row height=2x5=10
$pdf->Cell(60,3,'Kriteria Unjuk Kerja','L',0,'C',TRUE); //vertically merges cell
$pdf->Cell(75,3,'tersedia/tidak mencukupi)','L',0,'C',TRUE); //normal height, but occupy 4 columns (horizontally merged)
$pdf->Cell(20,3,'','LR',0,'C',TRUE); //vertically merged cell
$pdf->Ln();

//space line(row)
$pdf->Cell(35,1,'','L',0,'C',TRUE); //dummy cell to align next cell, should be invisible
$pdf->Cell(60,1,'','L',0,'C',TRUE);
$pdf->Cell(25,1,'','LT',0,'C',TRUE);
$pdf->Cell(25,1,'','LT',0,'C',TRUE);
$pdf->Cell(25,1,'','LT',0,'C',TRUE);
$pdf->Cell(5,1,'','LT',0,'C',TRUE);
$pdf->Cell(5,1,'','LT',0,'C',TRUE);
$pdf->Cell(5,1,'','LT',0,'C',TRUE);
$pdf->Cell(5,1,'','LTR',0,'C',TRUE);
$pdf->Ln();

//second line(row)
$pdf->Cell(35,3,'Kompetensi','L',0,'C',TRUE); //dummy cell to align next cell, should be invisible
$pdf->Cell(60,3,'','L',0,'C',TRUE);
$pdf->Cell(25,3,'Bukti','L',0,'C',TRUE);
$pdf->Cell(25,3,'Bukti','L',0,'C',TRUE);
$pdf->Cell(25,3,'Bukti','L',0,'C',TRUE);
$pdf->Cell(5,3,'','L',0,'C',TRUE);
$pdf->Cell(5,3,'','L',0,'C',TRUE);
$pdf->Cell(5,3,'','L',0,'C',TRUE);
$pdf->Cell(5,3,'','LR',0,'C',TRUE);
$pdf->Ln();

//3rd line(row)
$pdf->Cell(35,3,'','L',0,'C',TRUE); //dummy cell to align next cell, should be invisible
$pdf->Cell(60,3,'','L',0,'C',TRUE);
$pdf->Cell(25,3,'Langsung','L',0,'C',TRUE);
$pdf->Cell(25,3,'Tidak','L',0,'C',TRUE);
$pdf->Cell(25,3,'Tambahan','L',0,'C',TRUE);
$pdf->Cell(5,3,'K','L',0,'C',TRUE);
$pdf->Cell(5,3,'BK','L',0,'C',TRUE);
$pdf->Cell(5,3,'','L',0,'C',TRUE);
$pdf->Cell(5,3,'PL','LR',0,'C',TRUE);
$pdf->Ln();

//4th line(row)
$pdf->Cell(35,3,'','L',0,'C',TRUE); //dummy cell to align next cell, should be invisible
$pdf->Cell(60,3,'','L',0,'C',TRUE);
$pdf->Cell(25,3,'','L',0,'C',TRUE);
$pdf->Cell(25,3,'Langsung','L',0,'C',TRUE);
$pdf->Cell(25,3,'','L',0,'C',TRUE);
$pdf->Cell(5,3,'','L',0,'C',TRUE);
$pdf->Cell(5,3,'','L',0,'C',TRUE);
$pdf->Cell(5,3,'','L',0,'C',TRUE);
$pdf->Cell(5,3,'','LR',0,'C',TRUE);
$pdf->Ln();
//4th line(row)
$pdf->Cell(35,1,'','LB',0,'C',TRUE); //dummy cell to align next cell, should be invisible
$pdf->Cell(60,1,'','LB',0,'C',TRUE);
$pdf->Cell(25,1,'','LB',0,'C',TRUE);
$pdf->Cell(25,1,'','LB',0,'C',TRUE);
$pdf->Cell(25,1,'','LB',0,'C',TRUE);
$pdf->Cell(5,1,'','LB',0,'C',TRUE);
$pdf->Cell(5,1,'','LB',0,'C',TRUE);
$pdf->Cell(5,1,'','LB',0,'C',TRUE);
$pdf->Cell(5,1,'','LBR',0,'C',TRUE);
$pdf->Ln();

$pdf->SetFont('Arial','','9');

// mulai dengan isi tablenya
$sqlgetunitkompetensi="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sq[id]'";
$getunitkompetensi=$conn->query($sqlgetunitkompetensi);
$noku=1;
while ($unk=$getunitkompetensi->fetch_assoc()){

	$sqlcekjawaban1="SELECT * FROM `asesi_mak02` WHERE `id_jadwal`='$_GET[idj]' AND `id_asesi`='$_GET[ida]' AND `id_skema`='$sq[id]' AND `id_unitkompetensi`='$unk[id]'";
	$cekjawaban1=$conn->query($sqlcekjawaban1);
	$jjw1=$cekjawaban1->fetch_assoc();

	$kali0=strlen($unk['judul'])/76;
	$kalinya0=ceil($kali0);
	$hspace0=5*$kalinya0;
	$notampil=$noku.".";
	
	//Calculate the height of the row
	$baris=strlen($unk['judul'])/75;
	$baris2=ceil($baris);
	$h=5*$baris2;
	$pdf->Cell(40,$h,$unk['kode_unit'],'LT','L');
	$pdf->Cell(5,$h,':','T','L');
	$pdf->MultiCell(145,5,$unk['judul'],'TR','L');

	
	//$pdf->Cell(10,5,$notampil, 'LT', 0,'C');
	//$pdf->Cell(85,5,$unk['kode_unit'], 'LT', 0,'L');
	//$pdf->Cell(40,5,'Hasil rekaman observasi', 'LT', 0,'L');
	switch ($jjw1['bukti1-1']){
		case "L":
			$buktiL="L ";
		break;
		case "TL":
			$buktiTL="TL ";
		break;
		case "T":
			$buktiT="T";
		break;

		default:
			$buktiL="";
			$buktiTL="";
			$buktiT="";
		break;
	}
	switch ($jjw1['bukti1-2']){
		case "L":
			$buktiL="L ";
		break;
		case "TL":
			$buktiTL="TL ";
		break;
		case "T":
			$buktiT="T";
		break;

		default:
			$buktiL="";
			$buktiTL="";
			$buktiT="";
		break;
	}
	switch ($jjw1['bukti1-3']){
		case "L":
			$buktiL="L ";
		break;
		case "TL":
			$buktiTL="TL ";
		break;
		case "T":
			$buktiT="T";
		break;

		default:
			$buktiL="";
			$buktiTL="";
			$buktiT="";
		break;
	}
	$bukti1=$buktiL.$buktiTL.$buktiT;
	//$pdf->Cell(15,5,$bukti1, 'LT', 0,'L');

	/*switch ($jjw1['pencapaian1']){
		case "Y":
			$pdf->Cell(10,5,'V', 'LT', 0,'C');
			$pdf->Cell(10,5,'', 'LT', 0,'C');
		break;
		case "N":
			$pdf->Cell(10,5,'', 'LT', 0,'C');
			$pdf->Cell(10,5,'V', 'LT', 0,'C');
		break;
		default:
			$pdf->Cell(10,5,'', 'LT', 0,'C');
			$pdf->Cell(10,5,'', 'LT', 0,'C');
		break;
	}
	switch ($jjw1['keputusan1']){
		case "K":
			$pdf->Cell(10,5,'V', 'LT', 0,'C');
			$pdf->Cell(10,5,'', 'LTR', 0,'C');
		break;
		case "BK":
			$pdf->Cell(10,5,'', 'LT', 0,'C');
			$pdf->Cell(10,5,'V', 'LTR', 0,'C');
		break;
		case "PL":
			$pdf->Cell(10,5,'', 'LT', 0,'C');
			$pdf->Cell(10,5,'', 'LTR', 0,'C');
		break;
		default:
			$pdf->Cell(10,5,'', 'LT', 0,'C');
			$pdf->Cell(10,5,'', 'LTR', 0,'C');
		break;

	}*/
	//$pdf->Ln();
	//$pdf->Cell(10,5,'', 'L', 0,'C');
	//$pdf->MultiCell(85,5,$unk['judul'], 'L', 'L');
	//$pdf->MultiCell(40,5,'Hasil rekaman tes lisan', 'LT', 'L');

	switch ($jjw1['bukti2-1']){
		case "L":
			$buktiL="L ";
		break;
		case "TL":
			$buktiTL="TL ";
		break;
		case "T":
			$buktiT="T";
		break;

		default:
			$buktiL="";
			$buktiTL="";
			$buktiT="";
		break;
	}
	switch ($jjw1['bukti2-2']){
		case "L":
			$buktiL="L ";
		break;
		case "TL":
			$buktiTL="TL ";
		break;
		case "T":
			$buktiT="T";
		break;

		default:
			$buktiL="";
			$buktiTL="";
			$buktiT="";
		break;
	}
	switch ($jjw1['bukti2-3']){
		case "L":
			$buktiL="L ";
		break;
		case "TL":
			$buktiTL="TL ";
		break;
		case "T":
			$buktiT="T";
		break;

		default:
			$buktiL="";
			$buktiTL="";
			$buktiT="";
		break;
	}
	$bukti1=$buktiL.$buktiTL.$buktiT;
	//$pdf->Cell(15,5,$bukti1, 'LT', 0,'L');

	/*switch ($jjw1['pencapaian2']){
		case "Y":
			$pdf->Cell(10,5,'V', 'LT', 0,'C');
			$pdf->Cell(10,5,'', 'LT', 0,'C');
		break;
		case "N":
			$pdf->Cell(10,5,'', 'LT', 0,'C');
			$pdf->Cell(10,5,'V', 'LT', 0,'C');
		break;
		default:
			$pdf->Cell(10,5,'', 'LT', 0,'C');
			$pdf->Cell(10,5,'', 'LT', 0,'C');
		break;
	}
	switch ($jjw1['keputusan2']){
		case "K":
			$pdf->Cell(10,5,'V', 'LT', 0,'C');
			$pdf->Cell(10,5,'', 'LTR', 0,'C');
		break;
		case "BK":
			$pdf->Cell(10,5,'', 'LT', 0,'C');
			$pdf->Cell(10,5,'V', 'LTR', 0,'C');
		break;
		case "PL":
			$pdf->Cell(10,5,'', 'LT', 0,'C');
			$pdf->Cell(10,5,'', 'LTR', 0,'C');
		break;
		default:
			$pdf->Cell(10,5,'', 'LT', 0,'C');
			$pdf->Cell(10,5,'', 'LTR', 0,'C');
		break;

	}*/
	//$pdf->Ln();
	//$pdf->Cell(10,5,'', 'L', 0,'C');
	//$pdf->Cell(85,5,'', 'L', 0,'C');
	//$pdf->MultiCell(40,5,'Hasil rekaman tes tulis', 'LT', 'L');

	switch ($jjw1['bukti3-1']){
		case "L":
			$buktiL="L ";
		break;
		case "TL":
			$buktiTL="TL ";
		break;
		case "T":
			$buktiT="T";
		break;

		default:
			$buktiL="";
			$buktiTL="";
			$buktiT="";
		break;
	}
	switch ($jjw1['bukti3-2']){
		case "L":
			$buktiL="L ";
		break;
		case "TL":
			$buktiTL="TL ";
		break;
		case "T":
			$buktiT="T";
		break;

		default:
			$buktiL="";
			$buktiTL="";
			$buktiT="";
		break;
	}
	switch ($jjw1['bukti3-3']){
		case "L":
			$buktiL="L ";
		break;
		case "TL":
			$buktiTL="TL ";
		break;
		case "T":
			$buktiT="T";
		break;

		default:
			$buktiL="";
			$buktiTL="";
			$buktiT="";
		break;
	}
	$bukti1=$buktiL.$buktiTL.$buktiT;
	//$pdf->Cell(15,5,$bukti1, 'LT', 0,'L');

	/*switch ($jjw1['pencapaian3']){
		case "Y":
			$pdf->Cell(10,5,'V', 'LT', 0,'C');
			$pdf->Cell(10,5,'', 'LT', 0,'C');
		break;
		case "N":
			$pdf->Cell(10,5,'', 'LT', 0,'C');
			$pdf->Cell(10,5,'V', 'LT', 0,'C');
		break;
		default:
			$pdf->Cell(10,5,'', 'LT', 0,'C');
			$pdf->Cell(10,5,'', 'LT', 0,'C');
		break;
	}
	switch ($jjw1['keputusan3']){
		case "K":
			$pdf->Cell(10,5,'V', 'LT', 0,'C');
			$pdf->Cell(10,5,'', 'LTR', 0,'C');
		break;
		case "BK":
			$pdf->Cell(10,5,'', 'LT', 0,'C');
			$pdf->Cell(10,5,'V', 'LTR', 0,'C');
		break;
		case "PL":
			$pdf->Cell(10,5,'', 'LT', 0,'C');
			$pdf->Cell(10,5,'', 'LTR', 0,'C');
		break;
		default:
			$pdf->Cell(10,5,'', 'LT', 0,'C');
			$pdf->Cell(10,5,'', 'LTR', 0,'C');
		break;

	}*/
	$pdf->Ln();

	//$pdf->SetWidths(array(15,60,60,15,10,10,10,10));
	//$judulkodeunit=$unk['kode_unit']." ".$unk['judul'];
	//$pdf->RowContent(array($notampil,$judulkodeunit,$buktibuktiunit,$jenisbukti,'','','',''));
	$noku++;
	
	// elemen kompetensi
	/*$sqlgetelemen="SELECT * FROM `elemen_kompetensi` WHERE `id_unitkompetensi`='$unk[id]' ORDER BY `id` ASC";
	$getelemen=$conn->query($sqlgetelemen);
	$no=1;
	while ($el=$getelemen->fetch_assoc()){
		$pdf->SetWidths(array(40,5,145));
		$elemenkompetensi=$no.". ".$el['elemen_kompetensi'];
		$pdf->SetFont('Arial','B','9');
		$pdf->RowContent(array('Elemen Kompetensi',' :',$elemenkompetensi));
		// kriteria unjuk kerja
		$sqlgetkriteria="SELECT * FROM `kriteria_unjukkerja` WHERE `id_elemenkompetensi`='$el[id]' ORDER BY `id` ASC";
		$getkriteria=$conn->query($sqlgetkriteria);
		$nokr=1;
		//header row
		$pdf->SetFont('Arial','B','8');
		$pdf->Cell(15,1,'', 'LT', 0,'C');
		$pdf->Cell(100,1,'', 'LT', 0,'C');
		$pdf->Cell(20,1,'', 'LT', 0,'C');
		$pdf->Cell(35,1,'', 'LT', 0,'C');
		$pdf->Cell(20,1,'', 'LTR', 0,'C');
		$pdf->Ln();
		$pdf->Cell(15,3,'', 'L', 0,'C');
		$pdf->Cell(100,3,'', 'L', 0,'C');
		$pdf->Cell(20,3,'', 'L', 0,'C');
		$pdf->Cell(35,3,'', 'L', 0,'C');
		$pdf->Cell(20,3,'Bukti-bukti', 'LR', 0,'C');
		$pdf->Ln();
		$pdf->Cell(15,3,'Nomor', 'L', 0,'C');
		$pdf->Cell(100,3,'Daftar Pertanyaan', 'L', 0,'C');
		$pdf->Cell(20,3,'Penilaian', 'L', 0,'C');
		$pdf->Cell(35,3,'Bukti-kukti', 'L', 0,'C');
		$pdf->Cell(20,3,'Pendukung', 'LR', 0,'C');
		$pdf->Ln();
		$pdf->Cell(15,4,'ELemen', 'L', 0,'C');
		$pdf->Cell(100,4,'(Asesmen Mandiri/Self Assessment)', 'L', 0,'C');
		$pdf->Cell(20,4,'', 'L', 0,'C');
		$pdf->Cell(35,4,'Kompetensi', 'L', 0,'C');
		$pdf->Cell(20,4,'', 'LBR', 0,'C');
		$pdf->Ln();
		$pdf->Cell(15,4,'', 'LB', 0,'C');
		$pdf->Cell(100,4,'', 'LB', 0,'C');
		$pdf->Cell(10,4,'K', 'LTB', 0,'C');
		$pdf->Cell(10,4,'BK', 'LTB', 0,'C');
		$pdf->Cell(35,4,'', 'LB', 0,'C');
		$pdf->Cell(5,4,'V', 'LRB', 0,'C');
		$pdf->Cell(5,4,'A', 'LRB', 0,'C');
		$pdf->Cell(5,4,'T', 'LRB', 0,'C');
		$pdf->Cell(5,4,'M', 'LRB', 0,'C');
		$pdf->Ln();
		$pdf->SetFont('Arial','','9');
		$jumkr=$getkriteria->num_rows;
		$posisikr=$jumkr/2;
		$poskr=round($posisikr,0,PHP_ROUND_HALF_UP);
		$poskrplus=$poskr+1;
		while($kr=$getkriteria->fetch_assoc()){
			$kali=strlen($kr['kriteria'])/63;
			$kalinya=ceil($kali);
			$hspace=5*$kalinya;
			$pdf->SetWidths(array(15,100,10,10,35,5,5,5,5));
			$nokriteria="      ".$nokr.".";
			$kriteria=$kr['kriteria'];
			//if ($nokr==$poskr){
			//	$pdf->SetFont('Arial','B','8');
			//	$pdf->Cell(30,$hspace,'Kriteria Unjuk Kerja', 'LR', 0,'C');
			//}else{
				//if ($nokr==$poskrplus){
				//	$pdf->Cell(30,$hspace,'Unjuk Kerja', 'LR', 0,'C');
				//}else{
			//		$pdf->Cell(30,$hspace,'', 'LR', 0,'C');
				//}
			//}
			$pdf->SetFont('Arial','','9');
			$getnilai="SELECT * FROM `asesi_apl02` WHERE `id_asesi`='$as[no_pendaftaran]' AND `id_kriteria`='$kr[id]' AND `id_skemakkni`='$sq[id]'";
			$nilai=$conn->query($getnilai);
			$nil=$nilai->fetch_assoc();
			switch ($nil['jawaban']){
				case "1":
					if ($nil['verifikasi_asesor1']=='V'){
						$verifikasi_asesor1=" v";
					}else{
						$verifikasi_asesor1="";
					}
					if ($nil['verifikasi_asesor2']=='A'){
						$verifikasi_asesor2=" v";
					}else{
						$verifikasi_asesor2="";
					}
					if ($nil['verifikasi_asesor3']=='T'){
						$verifikasi_asesor3=" v";
					}else{
						$verifikasi_asesor3="";
					}
					if ($nil['verifikasi_asesor4']=='M'){
						$verifikasi_asesor4=" v";
					}else{
						$verifikasi_asesor4="";
					}
					$pdf->RowContent(array($nokriteria,$kriteria,'    v','',$nil['keterangan_bukti'],$verifikasi_asesor1,$verifikasi_asesor2,$verifikasi_asesor3,$verifikasi_asesor4));
				break;
				case "0":
					$pdf->RowContent(array($nokriteria,$kriteria,'','    v','','','','',''));
				break;

				default:
					$pdf->RowContent(array($nokriteria,$kriteria,'','','','','','',''));
			}
			$nokr++;

		}
		$no++;
		$pdf->Cell(190,5,'', '', 0,'R');
		$pdf->Ln();
	}
	$pdf->Ln(); */

}
$pdf->SetFont('Arial','B','9');
$pdf->Cell(190,8,'Umpan balik terhadap pencapaian unjuk kerja :', 'LTR', 0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','','9');

$sqlcekjawaban2="SELECT * FROM `asesi_mak02` WHERE `id_jadwal`='$_GET[idj]' AND `id_asesi`='$_GET[ida]' AND `id_skema`='$sq[id]' AND `pencapaian1`='N' OR `id_jadwal`='$_GET[idj]' AND `id_asesi`='$_GET[ida]' AND `id_skema`='$sq[id]' AND `pencapaian2`='N' OR `id_jadwal`='$_GET[idj]' AND `id_asesi`='$_GET[ida]' AND `id_skema`='$sq[id]' AND `pencapaian3`='N'";
$cekjawaban2=$conn->query($sqlcekjawaban2);
$jumjjw2=$cekjawaban2->num_rows;

$sqlcekjawaban3="SELECT * FROM `asesi_mak02` WHERE `id_jadwal`='$_GET[idj]' AND `id_asesi`='$_GET[ida]' AND `id_skema`='$sq[id]' AND `pencapaian1`='Y' AND `pencapaian2`='Y' AND `pencapaian3`='Y'";
$cekjawaban3=$conn->query($sqlcekjawaban3);
$jumjjw3=$cekjawaban3->num_rows;

if ($jumjjw2==0){

	if ($jumjjw3==0){
		$pdf->Cell(190,8,'  [      ] Seluruh Elemen Kompetensi/Kriteria Unjuk Kerja (KUK) yang diujikan telah tercapai', 'LR', 0,'L');
		$pdf->Ln();
		$pdf->Cell(190,8,'  [      ] Terdapat Elemen Kompetensi/Kriteria Unjuk Kerja (KUK) yang diujikan belum tercapai', 'LRB', 0,'L');
		$pdf->Ln();
	}else{
		$pdf->Cell(190,8,'  [  V  ] Seluruh Elemen Kompetensi/Kriteria Unjuk Kerja (KUK) yang diujikan telah tercapai', 'LR', 0,'L');
		$pdf->Ln();
		$pdf->Cell(190,8,'  [      ] Terdapat Elemen Kompetensi/Kriteria Unjuk Kerja (KUK) yang diujikan belum tercapai', 'LRB', 0,'L');
		$pdf->Ln();
	}
}else{
	$pdf->Cell(190,8,'  [      ] Seluruh Elemen Kompetensi/Kriteria Unjuk Kerja (KUK) yang diujikan telah tercapai', 'LR', 0,'L');
	$pdf->Ln();
	$pdf->Cell(190,8,'  [  V  ] Terdapat Elemen Kompetensi/Kriteria Unjuk Kerja (KUK) yang diujikan belum tercapai', 'LRB', 0,'L');
	$pdf->Ln();
}
$pdf->Cell(190,5,'', 'T', 0,'L');
$pdf->Ln();

$pdf->AddPage();
$pdf->SetFont('Arial','B','9');
$pdf->Cell(190,8,'Identifikasi kesenjangan pencapaian unjuk kerja :', 'LTR', 0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','','9');
if ($jumjjw2==0){
	if ($jumjjw3==0){
		$pdf->Cell(190,8,'  [      ] Tidak ada kesenjangan', 'LR', 0,'L');
		$pdf->Ln();
		$pdf->Cell(190,8,'  [      ] Ditemukan kesenjangan pencapaian, sebagai berikut pada :', 'LR', 0,'L');
		$pdf->Ln();
	}else{
		$pdf->Cell(190,8,'  [  V  ] Tidak ada kesenjangan', 'LR', 0,'L');
		$pdf->Ln();
		$pdf->Cell(190,8,'  [      ] Ditemukan kesenjangan pencapaian, sebagai berikut pada :', 'LR', 0,'L');
		$pdf->Ln();
	}
}else{
	$pdf->Cell(190,8,'  [      ] Tidak ada kesenjangan', 'LR', 0,'L');
	$pdf->Ln();
	$pdf->Cell(190,8,'  [  V  ] Ditemukan kesenjangan pencapaian, sebagai berikut pada :', 'LR', 0,'L');
	$pdf->Ln();
}
$pdf->Cell(190,5,'           Kode dan Judul Unit Kompetensi:', 'LR', 0,'L');
$pdf->Ln();
// cek kesenjangan unit kompetensi
$pdf->SetFont('Arial','B','9');
$sqlcekjawaban4="SELECT * FROM `asesi_mak02` WHERE `id_jadwal`='$_GET[idj]' AND `id_asesi`='$_GET[ida]' AND `id_skema`='$sq[id]' AND `pencapaian1`='N' OR `id_jadwal`='$_GET[idj]' AND `id_asesi`='$_GET[ida]' AND `id_skema`='$sq[id]' AND `pencapaian2`='N' OR `id_jadwal`='$_GET[idj]' AND `id_asesi`='$_GET[ida]' AND `id_skema`='$sq[id]' AND `pencapaian3`='N'";
$cekjawaban4=$conn->query($sqlcekjawaban4);
$nokux=1;
while ($jumjjw4=$cekjawaban4->fetch_assoc()){
	$sqlgetunitkompetensix="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sq[id]' AND `id`='$jumjjw4[id_unitkompetensi]'";
	$getunitkompetensix=$conn->query($sqlgetunitkompetensix);
	$unkx=$getunitkompetensix->fetch_assoc();
	$kodedanjudulunit="           ".$nokux.". ".$unkx['kode_unit']." - ".$unkx['judul'];
	$pdf->Cell(190,5,$kodedanjudulunit, 'LR', 0,'L');
	$pdf->Ln();
	$nokux++;
}
$pdf->SetFont('Arial','','9');
$pdf->Cell(190,5,'           Elemen/ Kriteria Unjuk Kerja', 'LR', 0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','B','8');

// cek kesenjangan elemen/kriteria
	$getnilai2="SELECT * FROM `asesi_apl02` WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$sq[id]' AND `id_jadwal`='$_GET[idj]' AND `pencapaian`='N'";
	$nilai2=$conn->query($getnilai2);
	$nokrit=1;
	while($nil2=$nilai2->fetch_assoc()){
		$getnilai2a="SELECT * FROM `kriteria_unjukkerja` WHERE `id`='$nil2[id_kriteria]'";
		$nilai2a=$conn->query($getnilai2a);
		$nil2a=$nilai2a->fetch_assoc();
		$kriteriatampil=str_replace("Anda dapat ","",$nil2a['kriteria']);
		$kriteriatampil2=str_replace("Apakah ","",$kriteriatampil);
		$kriteriatampil3=str_replace("?","",$kriteriatampil2);
		$kriteriatampil4=$nokrit.". ".$kriteriatampil3;
		$pdf->Cell(10,5,'', 'L', 0,'L');
		$pdf->Cell(180,5,$kriteriatampil4, 'R', 0,'L');
		$pdf->Ln();
		$nokrit++;

	}

$pdf->Cell(190,5,'', 'LR', 0,'L');
$pdf->Ln();

$pdf->SetFont('Arial','B','9');
$pdf->Cell(190,8,'Saran tindak lanjut hasil asesmen :', 'LTR', 0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','','9');
$pdf->Cell(190,8,'  [      ] Agar memelihara kompetensi yang telah dicapai', 'LR', 0,'L');
$pdf->Ln();
$pdf->Cell(190,8,'  [      ] Perlu dilakukan asesmen ulang pada :', 'LR', 0,'L');
$pdf->Ln();
$pdf->Cell(190,5,'           Kode dan Judul Unit Kompetensi:', 'LR', 0,'L');
$pdf->Ln();
// cek asesmen ulang unit kompetensi
$sqlgetunitkompetensi2="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sq[id]'";
$getunitkompetensi2=$conn->query($sqlgetunitkompetensi2);
$noku2=1;
while ($unk2=$getunitkompetensi2->fetch_assoc()){
	$nokutampil2="           ".$noku2.". ...............................................................................................................................................................";
	$pdf->Cell(190,5,$nokutampil2, 'LR', 0,'L');
	$pdf->Ln();
	$noku2++;
}
$pdf->Cell(190,5,'', 'LR', 0,'L');
$pdf->Ln();

$pdf->Cell(190,5,'', 'T', 0,'L');
$pdf->Ln();


$pdf->SetFont('Arial','I','8');
/* $pdf->Cell(190,3,'Catatan : *) apabila tersedia dalam standar kompetensi', '', 0,'L');
$pdf->Ln();
$pdf->Cell(190,3,'Keterangan : V = Valid, A = Autentik, T = Terkini, M = Memadai', '', 0,'L');
$pdf->Ln(); */

//Cetak nomor halaman
$pdf->AliasNbPages();

//===========BAGIAN 2====================


// Bagian Rekomendasi
$pdf->SetFont('Arial','B','10');
$pdf->Cell(95,5,'Rekomendasi Asesor :', 'TL', 0,'L');
$pdf->Cell(95,5,'Asesor', 'TBLR', 0,'C');
$pdf->Ln();
$pdf->SetFont('Arial','','9');
$pdf->Cell(95,5,'Peserta direkomendasikan', 'L', 0,'L');
$pdf->SetFont('Arial','','9');
$pdf->Cell(30,5,'Nama', 'BL', 0,'L');
$pdf->SetFont('Arial','B','9');
$pdf->Cell(65,5,$namaasesor, 'BLR', 0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','B','10');
$pdf->Cell(95,5,'Kompeten/ Belum Kompeten *)', 'L', 0,'L');
$pdf->SetFont('Arial','','9');
$pdf->Cell(30,5,'No. Reg.', 'BL', 0,'L');
$pdf->SetFont('Arial','B','9');
$pdf->Cell(65,5,$asr['no_induk'], 'BLR', 0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','','10');
$pdf->Cell(10,5,'pada', 'L', 0,'L');
$pdf->SetFont('Arial','B','10');
$pdf->Cell(85,5,'skema sertifikasi/ Klaster Asesmen *)', '', 0,'L');
$pdf->SetFont('Arial','','10');
$pdf->Cell(30,5,'Tandatangan/', 'TL', 0,'L');
$pdf->Cell(65,5,'', 'LR', 0,'C');
$pdf->Ln();
$pdf->SetFont('Arial','B','10');
$pdf->Cell(95,5,'yang diujikan', 'L', 0,'L');
$pdf->SetFont('Arial','','9');
$pdf->Cell(30,5,'Tanggal', 'L', 0,'L');
$pdf->Cell(65,5,'', 'LR', 0,'C');
$pdf->Ln();
$pdf->SetFont('Arial','','9');
$pdf->Cell(95,5,'', 'L', 0,'L');
$pdf->Cell(30,5,'', 'L', 0,'L');
$pdf->Cell(65,5,'', 'LR', 0,'C');
$pdf->Ln();
$pdf->Cell(95,10,'', 'L', 0,'L');
$pdf->Cell(30,10,'', 'L', 0,'L');
$pdf->Cell(65,10,'', 'LR', 0,'C');
$pdf->Ln();
$pdf->SetFont('Arial','B','10');
$pdf->Cell(95,5,'', 'L', 0,'L');
$pdf->Cell(95,5,'Peserta', 'TBLR', 0,'C');
$pdf->Ln();
$pdf->SetFont('Arial','','9');
$pdf->Cell(95,5,'', 'L', 0,'L');
$pdf->Cell(30,5,'Nama', 'BL', 0,'L');
$pdf->SetFont('Arial','B','9');
$pdf->Cell(65,5,$as['nama'], 'BLR', 0,'L');
$pdf->Ln();
$pdf->Cell(95,5,'', 'L', 0,'L');
$pdf->SetFont('Arial','','9');
$pdf->Cell(30,5,'No. Reg.', 'BL', 0,'L');
$pdf->SetFont('Arial','B','9');
$pdf->Cell(65,5,$as['no_pendaftaran'], 'BLR', 0,'L');
$pdf->Ln();
$pdf->Cell(95,5,'', 'L', 0,'L');
$pdf->SetFont('Arial','','9');
$pdf->Cell(30,5,'Tandatangan/', 'L', 0,'L');
$pdf->Cell(65,5,'', 'LR', 0,'C');
$pdf->Ln();
$pdf->Cell(95,5,'', 'L', 0,'L');
$pdf->Cell(30,5,'Tanggal', 'L', 0,'L');
$pdf->Cell(65,5,'', 'LR', 0,'C');
$pdf->Ln();
$pdf->Cell(95,15,'', 'LB', 0,'L');
$pdf->Cell(30,15,'', 'BL', 0,'L');
$pdf->Cell(65,15,'', 'BLR', 0,'C');
$pdf->Ln();
$pdf->SetFont('Arial','I','8');
$pdf->Cell(190,5,'*) Coret yang tidak perlu', '', 0,'L');
/* $pdf->Ln();
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
$pdf->Code39(10,280,$kodever,1,7);

$pdf->AliasNbPages();

//output file pdf
$fileoutputnya="FORM-MAK-04-".$skemakkni."-".$as['nama'].".pdf";
$pdf->Output($fileoutputnya,'I');

?>