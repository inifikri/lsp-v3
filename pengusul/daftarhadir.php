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
$sqlskkni="SELECT * FROM `skkni` WHERE `id`='$sq[id_skkni]'";
$skkni=$conn->query($sqlskkni);
$sk=$skkni->fetch_assoc();

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

$header1a = array(
	array("label"=>"Hari/ Tanggal", "length"=>25, "align"=>"L"),
	array("label"=>": $jadwal", "length"=>160, "align"=>"L"),
	array("label"=>"Waktu", "length"=>20, "align"=>"L"),
	array("label"=>": $jamasesmen", "length"=>60, "align"=>"L")

);
$header1b = array(
	array("label"=>"Tempat", "length"=>25, "align"=>"L"),
	array("label"=>": $tq[nama]", "length"=>160, "align"=>"L"),
	array("label"=>"Ruang", "length"=>20, "align"=>"L"),
	array("label"=>": ", "length"=>60, "align"=>"L")

);
$header1c = array(
	array("label"=>"Skema", "length"=>25, "align"=>"L"),
	array("label"=>": $skemakkni", "length"=>160, "align"=>"L"),
	array("label"=>"Asesor", "length"=>20, "align"=>"L"),
	array("label"=>": $namaasesor", "length"=>60, "align"=>"L")

);


 
//memanggil fpdf
require_once ("fpdf/fpdf.php");

$pdf = new FPDF('P','mm',array(210,297), false, 'ISO-8859-15',array(3, 5, 3, 0));
$pdf->AddPage();
//Cetak Barcode
$kode1=date("Ymdhis");
$kode2=$_GET['idj'];
$kode3=$_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
$kodeproses=$kode1.$kode2.$kode3;
$kodecetak=substr(md5($kodeproses),0,8);
$kodecetakdb=strtoupper($kodecetak);
$id_wilayah=trim($wil1['nm_wil']);
$id_wilayah2=trim($wil2['nm_wil']).", ".trim($wil3['nm_wil']);
$namalsp=strtoupper($lq['nama']);
$alamatlsp=$lq['alamat']." ".$lq['kelurahan']." ".$id_wilayah;
$alamatlsp2=$id_wilayah2." Kodepos : ".$lq['kodepos'];
$telpemail="Telp./Fax.: ".$lq['telepon']." / ".$lq['fax']." Email : ".$lq['email'].", ".$lq['website'];
if (empty($jdq['nama_kegiatan'])){
	$tampilperiode="Periode ".$jdq['periode']." Tahun ".$jdq['tahun']." Gelombang ".$jdq['gelombang'];
}else{
	$tampilperiode=strtoupper($jdq['nama_kegiatan']);
}
$nomorlisensi="Nomor Lisensi : ".$lq['no_lisensi'];

$pdf->Code39(10,280,$kodecetak,1,8);
// Foto atau Logo
$pdf->Image('../images/lspbatik.jpg',15,15,25,25);
$pdf->Image('../images/logo-bnsp.jpg',170,15,25,25);

//tampilan Judul Laporan
$pdf->SetFont('Arial','B','8'); //Font Arial, Tebal/Bold, ukuran font 11
$pdf->Cell(0, 3, '', '0', 1, 'C');
$pdf->Cell(0, 5, '', '0', 1, 'C');
$pdf->SetFont('Arial','B','8'); //Font Arial, Tebal/Bold, ukuran font 11
$pdf->Cell(0, 5, 'BADAN NASIONAL SERTIFIKASI PROFESI', '0', 1, 'C');
$pdf->SetFont('Arial','B','12'); //Font Arial, Tebal/Bold, ukuran font 16
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
$pdf->Ln();

$pdf->SetFont('Arial','B','10'); //Font Arial, Tebal/Bold, ukuran font 9
$pdf->Cell(0, 5, 'BERITA ACARA ASESMEN / UJI KOMPETENSI', '0', 1, 'C');
$pdf->SetFont('Arial','B','10'); //Font Arial, Tebal/Bold, ukuran font 10
$pdf->Cell(0,5, $tampilperiode, '0', 1, 'C'); 
$pdf->Cell(0,5, '', '0', 1, 'C'); 
$pdf->SetFont('Arial','','10'); //Font Arial, Tebal/Bold, ukuran font 10
$keterangan="Pada hari ini ........................ Tanggal ......... (                                 ) Bulan ......... (                                 ) Tahun ".date("Y");
$pdf->Cell(0,7, $keterangan, '0', 1, 'L'); 
$sqlwiltuk="SELECT * FROM `data_wilayah` WHERE `id_wil`='$tq[id_wilayah]'";
$wilayahtuk=$conn->query($sqlwiltuk);
$wilt=$wilayahtuk->fetch_assoc();
$sqlwiltuk2="SELECT * FROM `data_wilayah` WHERE `id_wil`='$wilt[id_induk_wilayah]'";
$wilayahtuk2=$conn->query($sqlwiltuk2);
$wilt2=$wilayahtuk2->fetch_assoc();
$dilaksanakan="bertempat di ".$tq['nama']."; (lokasi) ".$tq['alamat']." ".$tq['kelurahan']." ".trim($wilt['nm_wil']).", ".trim($wilt2['nm_wil']);
//$pdf->Cell(0,7, $dilaksanakan, '0', 1, 'L'); 
$dilaksanakan2=$dilaksanakan." telah dilakukan Uji Kompetensi Skema ".$skemakkni." yang diikuti sebanyak ......... peserta dengan penjelasan sebagai berikut: Asesor:";
$pdf->SetWidths(array(190)); 
$pdf->RowContentNoBorder(array($dilaksanakan2));

$pdf->Cell(5,7, '1.', '', 0,'L'); 
$pdf->SetFont('Arial','B','10'); //Font Arial, Tebal/Bold, ukuran font 10
$pdf->Cell(60,7, $namaasesor, '', 0,'L');
$pdf->SetFont('Arial','','10'); //Font Arial, Tebal/Bold, ukuran font 10
$pdf->Cell(30,7,'No. Reg. Sertifikat:', '', 0,'C');
$pdf->SetFont('Arial','B','10'); //Font Arial, Tebal/Bold, ukuran font 10
$pdf->Cell(40,7,$asr['no_lisensi'], '', 0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','','10'); //Font Arial, Tebal/Bold, ukuran font 10
$pdf->Cell(5,7, '2.', '', 0,'L'); 
$pdf->Cell(60,7, '..........................................................', '', 0,'L'); 
$pdf->Cell(30,7,'No. Reg. Sertifikat:', '', 0,'C');
$pdf->Cell(40,7,'..............................................', '', 0,'L');
$pdf->Ln();

// Headernya Tabel
$pdf->Cell(190,0.5,'', 'T', 0,'C');
$pdf->Ln();
$pdf->Cell(8,5,'', 'LT', 0,'C');
$pdf->Cell(60,5,'', 'LT', 0,'C');
$pdf->Cell(92,5,'', 'LT', 0,'C');
$pdf->Cell(30,5,'REKOMENDASI', 'LTBR', 0,'C');
$pdf->Ln();
$pdf->Cell(8,1,'NO.', 'L', 0,'C');
$pdf->Cell(60,1,'NAMA ASESI', 'L', 0,'C');
$pdf->Cell(92,1,'ORGANISASI', 'L', 0,'C');
$pdf->Cell(15,1,'', 'L', 0,'C');
$pdf->Cell(15,1,'', 'LR', 0,'C');
$pdf->Ln();
$pdf->Cell(8,5,'', 'LB', 0,'C');
$pdf->Cell(60,5,'', 'LB', 0,'C');
$pdf->Cell(92,5,'', 'LB', 0,'C');
$pdf->Cell(15,5,'K', 'LB', 0,'C');
$pdf->Cell(15,5,'BK', 'LBR', 0,'C');
$pdf->Ln();
$pdf->Cell(190,0.5,'', 'TB', 0,'C');
$pdf->Ln();
$sqlasesmenasesi0="SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`='$jdq[id]' ORDER BY `id_asesi` ASC";
$asesmenasesi0=$conn->query($sqlasesmenasesi0);
$nops=1;
while ($asdj=$asesmenasesi0->fetch_assoc()){
	$sqlasesi0="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$asdj[id_asesi]'";
	$asesi0=$conn->query($sqlasesi0);
	$dtm0=$asesi0->fetch_assoc();

	$namaasesi=$dtm0['nama'];
	$organisasi=$dtm0['nama_kantor'];
	$pdf->SetWidths(array(8,60,92,15,15)); 
	$pdf->RowContent(array($nops,$namaasesi,$organisasi,'',''));
	$nops++;
}
$pdf->Cell(190,0.5,'', 'TB', 0,'C');
$pdf->Ln();
$pdf->Cell(160,5,'Jumlah', 'LB', 0,'C');
$pdf->Cell(15,5,'', 'LB', 0,'C');
$pdf->Cell(15,5,'', 'LBR', 0,'C');
$pdf->Ln();


$pdf->Cell(190,5,'', '', 0,'C');
$pdf->Ln();
$dilaksanakan3="Demikian berita acara Asesmen/ Uji Kompetensi dibuat untuk sebagai pengambil keputusan oleh tim Asesor ".$namalsp;
$pdf->SetWidths(array(190)); 
$pdf->RowContentNoBorder(array($dilaksanakan3));
$ttdasesornya="......................................, ................................................ ".date("Y");
$pdf->Cell(190,5,$ttdasesornya, '', 0,'R');
$pdf->Ln();
$pdf->Cell(65,7, '', '', 0,'L'); 
$pdf->SetFont('Arial','B','10'); //Font Arial, Tebal/Bold, ukuran font 10
$pdf->Cell(65,7, 'Asesor Kompetensi', '', 0,'L'); 
$pdf->SetFont('Arial','','10'); //Font Arial, Tebal/Bold, ukuran font 10
$pdf->Ln();
$pdf->Cell(65,10, '', '', 0,'L'); 
$pdf->Cell(5,10, '1.', '', 0,'L'); 
$pdf->SetFont('Arial','B','10'); //Font Arial, Tebal/Bold, ukuran font 10
$pdf->Cell(60,10, $namaasesor, '', 0,'L');
$pdf->SetFont('Arial','','10'); //Font Arial, Tebal/Bold, ukuran font 10
$pdf->Cell(5,10,'1.', '', 0,'C');
$pdf->SetFont('Arial','','10'); //Font Arial, Tebal/Bold, ukuran font 10
$pdf->Cell(40,10,'..............................................(ttd)', '', 0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','','10'); //Font Arial, Tebal/Bold, ukuran font 10
$pdf->Cell(65,10, '', '', 0,'L'); 
$pdf->Cell(5,10, '2.', '', 0,'L'); 
$pdf->Cell(60,10, '..........................................................', '', 0,'L'); 
$pdf->Cell(5,10,'2.', '', 0,'C');
$pdf->Cell(40,10,'..............................................(ttd)', '', 0,'L');
$pdf->Ln();
$pdf->Cell(190,5,'', '', 0,'C');
$pdf->Ln();

$pdf->AddPage('L');
$pdf->Code39(10,280,$kodecetak,1,8);
//mysql_query("INSERT INTO `logcpresensi`(`kodever`, `kodekelas`, `dosen`, `kodemk`, `ipserver`, `timestmp`) VALUES ('$kodecetakdb','$kode2','$dosennya','$kodemk','$kode3','$kode1')");

//$cetakbarcode=$pdf->Code39(80,40,$kodekelasnya,1,10);
//$pdf->Write(2, $cetakbarcode);


//Disable automatic page break
//$pdf->SetAutoPageBreak(false);
// Foto atau Logo
$pdf->Image('../images/lspbatik.jpg',15,15,25,25);
$pdf->Image('../images/logo-bnsp.jpg',250,15,25,25);

//tampilan Judul Laporan
$pdf->SetFont('Arial','B','8'); //Font Arial, Tebal/Bold, ukuran font 11
$pdf->Cell(0, 3, '', '0', 1, 'C');
$pdf->Cell(0, 5, '', '0', 1, 'C');
$pdf->SetFont('Arial','B','12'); //Font Arial, Tebal/Bold, ukuran font 11
$pdf->Cell(0, 5, $namalsp, '0', 1, 'C');
$pdf->SetFont('Arial','B','8'); //Font Arial, Tebal/Bold, ukuran font 16
$pdf->Cell(0,5, $nomorlisensi, '0', 1, 'C');
$pdf->SetFont('Arial','','8'); //Font Arial, Tebal/Bold, ukuran font 16
$pdf->Cell(0,3, $alamatlsp, '0', 1, 'C');
$pdf->Cell(0,3, $alamatlsp2, '0', 1, 'C');
$pdf->Cell(0, 3, $telpemail, '0', 1, 'C');
$pdf->Ln();

$pdf->SetFont('Arial','B','10'); //Font Arial, Tebal/Bold, ukuran font 9
$pdf->Cell(0, 5, 'DAFTAR HADIR ASESMEN KOMPETENSI', '0', 1, 'C');
$pdf->SetFont('Arial','B','10'); //Font Arial, Tebal/Bold, ukuran font 11
$pdf->Cell(0,5, $tampilperiode, '0', 1, 'C'); 
$pdf->Cell(0,5, '', '0', 1, 'C'); 

//Header1a Table
$pdf->SetFont('Arial','B','9');
$pdf->SetFillColor(255, 255, 255); //warna dalam kolom header
$pdf->SetTextColor(0); //warna tulisan putih
$pdf->SetDrawColor(0, 0, 0); //warna border R, G, B
foreach ($header1a as $kolom1a) {
    $pdf->Cell($kolom1a['length'], 4, $kolom1a['label'], 0, '0', $kolom1a['align'], true);
}
$pdf->Ln();

//Header1b Table
$pdf->SetFont('Arial','B','9');
$pdf->SetFillColor(255, 255, 255); //warna dalam kolom header
$pdf->SetTextColor(0); //warna tulisan putih
$pdf->SetDrawColor(0, 0, 0); //warna border R, G, B
foreach ($header1b as $kolom1b) {
    $pdf->Cell($kolom1b['length'], 4, $kolom1b['label'], 0, '0', $kolom1b['align'], true);
}
$pdf->Ln();

//Header1c Table
$pdf->SetFont('Arial','B','9');
$pdf->SetFillColor(255, 255, 255); //warna dalam kolom header
$pdf->SetTextColor(0); //warna tulisan putih
$pdf->SetDrawColor(0, 0, 0); //warna border R, G, B
foreach ($header1c as $kolom1c) {
    $pdf->Cell($kolom1c['length'], 4, $kolom1c['label'], 0, '0', $kolom1c['align'], true);
}
$pdf->Ln();

//Header1d Table
$pdf->SetFont('Arial','B','8');
$pdf->SetFillColor(255, 255, 255); //warna dalam kolom header
$pdf->SetTextColor(0); //warna tulisan putih
$pdf->SetDrawColor(0, 0, 0); //warna border R, G, B

$pdf->Ln();
$pdf->Cell(277,0.5,' ', '', 0,'C');

$pdf->Ln();

$pdf->Cell(277,0.5,' ', 'T', 0,'C');
$pdf->Ln();
$pdf->SetFont('Arial','','8');

//Headernya Table
$pdf->SetFillColor(255, 255, 255); //warna dalam kolom header
$pdf->SetTextColor(0); //warna tulisan putih
$pdf->SetDrawColor(0, 0, 0); //warna border R, G, B
$pdf->SetFont('Arial','B','8');
//===========================================BLOK HEADER TABLE======================================
//Headernya1
$pdf->Cell(8,8,'NO.', 'LT', 0,'C');
$pdf->Cell(60,8,'NAMA', 'LT', 0,'C');
$pdf->Cell(62,8,'ALAMAT', 'LT', 0,'C');
$pdf->Cell(30,8,'NO. HP', 'LT', 0,'C');
$pdf->Cell(40,8,'EMAIL', 'LT', 0,'C');
$pdf->Cell(10,8,'USIA', 'LT', 0,'C');
$pdf->Cell(37,8,'PENDIDIKAN TERAKHIR', 'LT', 0,'C');
$pdf->Cell(30,8,'TANDA TANGAN', 'LTR', 0,'C');
$pdf->Ln();

$pdf->Cell(277,0.5,' ', 'BT', 0,'C');
$pdf->Ln();
//===================================================AKHIR BLOK HEADER TABLE================================================ 
//menampilkan data table
$pdf->SetFillColor(255, 255, 255); //warna dalam kolom data
$pdf->SetTextColor(0); //warna tulisan hitam
$pdf->SetFont('Arial','','8');
$fill=false;
$nomnya=1;
$sqlasesmenasesi="SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`='$jdq[id]' ORDER BY `id_asesi` ASC";
$asesmenasesi=$conn->query($sqlasesmenasesi);

//Add first page
//$pdf->AddPage();
//set initial y axis position per page
$y_axis_initial = 15;
$row_height = 5;
$y_axis = $y_axis_initial + $row_height;
//initialize counter
$i = 1;
//Set maximum rows per page
$max = 12;
while ($dt=$asesmenasesi->fetch_assoc()){
	$sqlasesi="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$dt[id_asesi]'";
	$asesi=$conn->query($sqlasesi);
	$dtm=$asesi->fetch_assoc();
    //If the current row is the last one, create new page and print column title
		//$i++;
    if ($i > $max)
    {
        $pdf->AddPage();
		//Set $i variable to 0 (first row)
        	$i = 1;
		$max = 18;
        //print column titles for the current page
		$pdf->SetFont('Arial','B','9');
		$pdf->Cell(0, 5, '', '0', 1, 'C');

		//===========================================BLOK HEADER TABLE======================================
		//Headernya1
		
		//Headernya1
		$pdf->Cell(8,8,'NO.', 'LT', 0,'C');
		$pdf->Cell(60,8,'NAMA', 'LT', 0,'C');
		$pdf->Cell(62,8,'ALAMAT', 'LT', 0,'C');
		$pdf->Cell(30,8,'NO. HP', 'LT', 0,'C');
		$pdf->Cell(40,8,'EMAIL', 'LT', 0,'C');
		$pdf->Cell(10,8,'USIA', 'LT', 0,'C');
		$pdf->Cell(37,8,'PENDIDIKAN TERAKHIR', 'LT', 0,'C');
		$pdf->Cell(30,8,'TANDA TANGAN', 'LTR', 0,'C');
		$pdf->Ln();

		$pdf->Cell(277,0.5,' ', 'BT', 0,'C');
		$pdf->Ln();
//===================================================AKHIR BLOK HEADER TABLE================================================ 
       
        	
    }


		$pdf->SetFont('Arial','','8');
		$pdf->SetWidths(array(8,60,62,30,40,10,37,30));
		$sqlwil1="SELECT * FROM `data_wilayah` WHERE `id_wil`='$dtm[kecamatan]'";
		$wilayah1=$conn->query($sqlwil1);
		$wil1=$wilayah1->fetch_assoc();
		$sqlwil2="SELECT * FROM `data_wilayah` WHERE `id_wil`='$dtm[kota]'";
		$wilayah2=$conn->query($sqlwil2);
		$wil2=$wilayah2->fetch_assoc();
		$sqlwil3="SELECT * FROM `data_wilayah` WHERE `id_wil`='$dtm[propinsi]'";
		$wilayah3=$conn->query($sqlwil3);
		$wil3=$wilayah3->fetch_assoc();

		$alamatasesi=$dtm['alamat']." RT ".$dtm['RT']." RW ".$dtm['RW']." ".$dtm['kelurahan']." ".trim($wil1['nm_wil']).", ".trim($wil2['nm_wil'])." ".trim($wil3['nm_wil']);
		$sqlpendidikan="SELECT * FROM `pendidikan` WHERE `id`='$dtm[pendidikan]'";
		$pendidikan=$conn->query($sqlpendidikan);
		$jpen=$pendidikan->fetch_assoc();
		$pdf->RowContent(array($nomnya,$dtm['nama'],$alamatasesi,$dtm['nohp'],$dtm['email'],$dtm['usia'],$jpen['jenjang_pendidikan'],''));
		//$pdf->Cell(8,8,$nomnya, 'LBT', 0,'C');
		//$pdf->Cell(92,8,$dtm['nama'], 'LBT', 0,'L');
		//$pdf->Cell(50,8,$dt['id_asesi'], 'LBT', 0,'C');
		//$pdf->Cell(40,8,'', 'LRBT', 0,'C');
		$nomnya++;

    //Go to next row
    $y_axis = $y_axis_initial + $row_height;
    $i++;

}
//Jumlah Hadir
$pdf->SetFont('Arial','B','10');
$pdf->Cell(277,0.5,' ', 'BT', 0,'C');
$pdf->Ln();

//Paraf TUK
$pdf->Cell(247,8,'Jumlah Hadir', 'LB', 0,'R');
$pdf->Cell(30,8,'', 'LBR', 0,'C');
$pdf->Ln();

$pdf->Cell(247,8,'Jumlah Tidak Hadir', 'LB', 0,'R');
$pdf->Cell(30,8,'', 'LBR', 0,'C');
$pdf->Ln();

$pdf->Cell(277,1,'',0,'L');

$pdf->Ln();
$pdf->SetFont('Arial','B','10');

$pdf->Cell(95,3,'Catatan',0,'L');

$pdf->Ln();
$pdf->SetFont('Arial','','9');

$pdf->Cell(95,4,'- Bila Nama Asesi tidak tertera dalam daftar, harap lapor ke Admin TUK/LSP',0,'L');
$pdf->Ln();
$pdf->Cell(95,4,'- Asesi yang TIDAK MENDAFTAR ONLINE PADA SKEMA tidak tertera dalam daftar',0,'L');
$pdf->Ln();
$pdf->Cell(190,10,'',0,'C');
$pdf->Ln();

// pengesahan dan tandatangan
$tglinput0=date('Y-m-d');
$tglinput=tgl_indo($tglinput0);
$pdf->SetFont('Arial','','10');
$pdf->Cell(15,3,' ', '', 0,'C');
$pdf->Cell(30,3,'Mengetahui,', '', 0,'C');
$pdf->Cell(3,3,' ', '', 0,'C');
$pdf->Cell(150,3,'', '', 0,'C');
$pdf->Cell(80,3,$tglcetak, '', 0,'C');
$pdf->Ln();

$pdf->Cell(15,5,' ', '', 0,'C');
$pdf->Cell(30,5,'Manajer TUK', '', 0,'C');
$pdf->Cell(3,5,' ', '', 0,'C');
$pdf->Cell(150,5,'', '', 0,'C');
$pdf->Cell(80,5,'Asesor,', '', 0,'C');
$pdf->Ln();

$pdf->Cell(15,5,' ', '', 0,'C');
$pdf->Cell(30,5,'', '', 0,'L');
$pdf->Cell(3,5,'', '', 0,'C');
$pdf->SetFont('Arial','B','10');
$pdf->Cell(150,5,'', '', 0,'L');
$pdf->SetFont('Arial','','10');
$pdf->Cell(80,5,'', '', 0,'L');
$pdf->Ln();

$pdf->Cell(15,5,' ', '', 0,'C');
$pdf->Cell(30,5,'', '', 0,'L');
$pdf->Cell(3,5,'', '', 0,'C');
$pdf->Cell(150,5,'', '', 0,'L');
$pdf->Cell(80,5,'', '', 0,'C');
$pdf->Ln();

$pdf->Cell(15,5,' ', '', 0,'C');
$pdf->Cell(30,5,'', '', 0,'L');
$pdf->Cell(3,5,'', '', 0,'C');
$pdf->Cell(150,5,'', '', 0,'L');
$pdf->Cell(80,5,'', '', 0,'C');
$pdf->Ln();

$pdf->Cell(15,3,' ', '', 0,'C');
$pdf->Cell(30,3,'', '', 0,'L');
$pdf->Cell(3,3,'', '', 0,'C');
$pdf->Cell(150,3,'', '', 0,'L');
$pdf->Cell(80,3,'', '', 0,'C');
$pdf->Ln();

$pdf->SetFont('Arial','B','10');
$pdf->Cell(15,5,' ', '', 0,'C');
$pdf->Cell(30,5,$namamanajer, '', 0,'C');
$pdf->Cell(3,5,'', '', 0,'C');
$pdf->Cell(150,5,'', '', 0,'C');
$pdf->Cell(80,4,$namaasesor, '', 0,'C');
$pdf->Ln();

$pdf->AliasNbPages();



//output file pdf
$fileoutputnya="daftar-hadir-".$skemakkni."-".$tampilperiode.".pdf";
$pdf->Output($fileoutputnya,'D');


?>