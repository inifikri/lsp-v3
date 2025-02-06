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
}

/* $sqlasesor="SELECT * FROM `asesor` WHERE `id`='$jdq[id_asesor]'";
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
} */

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
$pdf->Cell(190,6,'FR-APL-02. ASESMEN MANDIRI', '', 0,'L');
$pdf->Ln();
$pdf->Cell(190,5,'', '', 0,'L');
$pdf->Ln();$skemaid=strtoupper($skemakkni);
//$pdf->SetFont('Arial','B','10');
//$pdf->Cell(190,10,$skemaid, '', 0,'L');
//$pdf->Ln();
$pdf->SetFont('Arial','','10');
$pdf->Cell(35,5,'Skema Sertifikasi/', 'LT', 0,'L');
$pdf->Cell(15,5,'Judul', 'LT', 0,'L');
$pdf->Cell(5,5,':', 'LT', 0,'C');
$pdf->SetFont('Arial','B','10');
$pdf->Cell(135,5,$skemaid, 'LTR', 0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','','10');
$pdf->Cell(35,5,'Klaster Asesmen', 'LB', 0,'L');
$pdf->Cell(15,5,'Nomor', 'LTB', 0,'L');
$pdf->Cell(5,5,':', 'LTB', 0,'C');
$pdf->SetFont('Arial','B','10');
$pdf->Cell(135,5,$sq['kode_skema'], 'LTBR', 0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','','10');
$pdf->Cell(50,5,'TUK', 'LT', 0,'L');
$pdf->Cell(5,5,':', 'LT', 0,'C');
$pdf->SetFont('Arial','B','10');
$pdf->Cell(135,5,'Sewaktu/ Tempat Kerja/ Mandiri*', 'LTR', 0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','','10');
$pdf->Cell(50,5,'Nama Asesor', 'LT', 0,'L');
$pdf->Cell(5,5,':', 'LT', 0,'C');
$pdf->SetFont('Arial','B','10');
$pdf->Cell(135,5,$namaasesor, 'LTR', 0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','','10');
$pdf->Cell(50,5,'Nama Peserta', 'LT', 0,'L');
$pdf->Cell(5,5,':', 'LT', 0,'C');
$pdf->SetFont('Arial','B','10');
$pdf->Cell(135,5,$as['nama'], 'LTR', 0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','','10');
$pdf->Cell(50,5,'Tanggal', 'LTB', 0,'L');
$pdf->Cell(5,5,':', 'LTB', 0,'C');
$tglas=tgl_indo($jdq['tgl_asesmen']);
$pdf->SetFont('Arial','B','10');
$pdf->Cell(135,5,$tglas, 'LTBR', 0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','I','8');
$pdf->Cell(190,5,'*Coret yang tidak perlu', '', 0,'L');
$pdf->Ln();
$pdf->Cell(190,3,'', '', 0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','B','9');
$pdf->Cell(190,5,'Peserta diminta untuk:', '', 0,'L');
$pdf->Ln();
$ket1="Mempelajari Kriteria Unjuk Kerja  (KUK), Batasan Variabel, Panduan Penilaian, dan Aspek Kritis seluruh Unit Kompetensi yang diminta untuk di Ases.";
$pdf->SetWidths(array(5,185));
$pdf->RowContentNoBorderJ(array('1.',$ket1));
$ket2="Melaksanakan Penilaian Mandiri secara obyektif atas sejumlah pertanyaan yang diajukan, bilamana Anda menilai diri sudah kompeten atas pertanyaan tersebut, tuliskan tanda v pada kolom (K), dan bilamana Anda menilai diri belum kompeten tuliskan tanda v pada kolom (BK).";
$pdf->SetWidths(array(5,185));
$pdf->RowContentNoBorderJ(array('2.',$ket2));
$ket3="Mengisi bukti-bukti kompetensi yang relevan atas sejumlah pertanyaan yang dinyatakan Kompeten (bila ada).";
$pdf->SetWidths(array(5,185));
$pdf->RowContentNoBorderJ(array('3.',$ket3));
$ket4="Menandatangani form Asesmen Mandiri.";
$pdf->SetWidths(array(5,185));
$pdf->RowContentNoBorderJ(array('4.',$ket4));

$pdf->Cell(190,5,'', '', 0,'R');
$pdf->Ln();

// mulai dengan isi tablenya
$sqlgetunitkompetensi="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sq[id]'";
$getunitkompetensi=$conn->query($sqlgetunitkompetensi);
$noku=1;
while ($unk=$getunitkompetensi->fetch_assoc()){
	$sqlcekukom="SELECT * FROM `asesmen_unitkompetensi` WHERE `id_skemakkni`='$sq[id]' AND `id_asesi`='$_GET[ida]' AND `id_unitkompetensi`='$unk[id]'";
	$cekukom=$conn->query($sqlcekukom);
	$ukom=$cekukom->num_rows;
	if ($ukom>0){

	$kali0=strlen($unk['judul'])/76;
	$kalinya0=ceil($kali0);
	$hspace0=5*$kalinya0;
	$pdf->SetFont('Arial','','9');
	$pdf->Cell(40,1,'', 'LT', 0,'L');
	$pdf->Cell(20,1,'', 'LT', 0,'L');
	$pdf->Cell(5,1,'', 'LT', 0,'L');
	$pdf->Cell(125,1,'', 'LTR', 0,'L');
	$pdf->Ln();
	$pdf->Cell(40,5,'', 'L', 0,'L');
	$pdf->Cell(20,5,'Kode Unit', 'L', 0,'L');
	$pdf->Cell(5,5,':', 'L', 0,'C');
	$pdf->SetFont('Arial','B','9');
	$pdf->Cell(125,5,$unk['kode_unit'], 'LR', 0,'L');
	$pdf->Ln();
	$pdf->SetFont('Arial','','9');
	$unitkomno="Unit Kompetensi No. ".$noku;
	$pdf->Cell(40,1,$unitkomno, 'L', 0,'C');
	$pdf->Cell(20,1,'', 'LB', 0,'L');
	$pdf->Cell(5,1,'', 'LB', 0,'L');
	$pdf->Cell(125,1,'', 'LBR', 0,'L');
	$pdf->Ln();
	$pdf->Cell(40,1,'', 'L', 0,'L');
	$pdf->Cell(20,1,'', 'L', 0,'L');
	$pdf->Cell(5,1,'', 'L', 0,'L');
	$pdf->Cell(125,1,'', 'LR', 0,'L');
	$pdf->Ln();
	$pdf->Cell(40,$hspace0,'', 'LB', 0,'L');
	$pdf->Cell(20,$hspace0,'Judul Unit', 'LB', 0,'L');
	$pdf->Cell(5,$hspace0,':', 'LB', 0,'C');
	$pdf->SetFont('Arial','B','9');
	$pdf->MultiCell(125, 5, $unk['judul'], 'LRB','J', 0, 0, '', '', true, 0, false, true, 4, 'LR');
	//$pdf->Cell(50,5,$unk['judul'], '', 0,'L');
	$pdf->Ln();
	$noku++;
	
	// elemen kompetensi
	$sqlgetelemen="SELECT * FROM `elemen_kompetensi` WHERE `id_unitkompetensi`='$unk[id]' ORDER BY `id` ASC";
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
	$pdf->Ln();
	
	}
}
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
$pdf->Cell(95,5,'Peserta', 'TBLR', 0,'C');
$pdf->Ln();
$pdf->SetFont('Arial','B','9');
$pdf->Cell(95,5,'1. Asesmen dapat/tidak dapat*) dilanjutkan', 'L', 0,'L');
$pdf->SetFont('Arial','','9');
$pdf->Cell(30,5,'Nama', 'BL', 0,'L');
$pdf->SetFont('Arial','B','9');
$pdf->Cell(65,5,$as['nama'], 'BLR', 0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','B','9');
$pdf->Cell(95,5,'2. Proses Asesmen dilanjutkan melalui:', 'L', 0,'L');
$pdf->SetFont('Arial','','9');
$pdf->Cell(30,5,'Tandatangan/', 'TL', 0,'L');
$pdf->Cell(65,5,'', 'LR', 0,'C');
$pdf->Ln();
$pdf->SetFont('Arial','B','9');
$pdf->Cell(95,5,'     [    ] Asesmen Portofolio', 'L', 0,'L');
$pdf->SetFont('Arial','','9');
$pdf->Cell(30,5,'Tanggal', 'L', 0,'L');
$pdf->Cell(65,5,'', 'LR', 0,'C');
$pdf->Ln();
$pdf->SetFont('Arial','B','9');
$pdf->Cell(95,5,'     [    ] Uji Kompetensi', 'L', 0,'L');
$pdf->Cell(30,5,'', 'L', 0,'L');
$pdf->Cell(65,5,'', 'LR', 0,'C');
$pdf->Ln();
$pdf->Cell(95,10,'', 'LB', 0,'L');
$pdf->Cell(30,10,'', 'BL', 0,'L');
$pdf->Cell(65,10,'', 'BLR', 0,'C');
$pdf->Ln();
$pdf->SetFont('Arial','B','10');
$pdf->Cell(95,5,'Catatan :', 'TL', 0,'L');
$pdf->Cell(95,5,'Asesor', 'TBLR', 0,'C');
$pdf->Ln();
$pdf->SetFont('Arial','','9');
$pdf->Cell(95,5,'', 'L', 0,'L');
$pdf->Cell(30,5,'Nama', 'BL', 0,'L');
$pdf->SetFont('Arial','B','9');
$pdf->Cell(65,5,$namaasesor, 'BLR', 0,'L');
$pdf->Ln();
$pdf->Cell(95,5,'', 'L', 0,'L');
$pdf->SetFont('Arial','','9');
$pdf->Cell(30,5,'No. Reg.', 'BL', 0,'L');
$pdf->SetFont('Arial','B','9');
$pdf->Cell(65,5,$asr['no_induk'], 'BLR', 0,'L');
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
$fileoutputnya="FORM-APL-02-".$skemakkni."-".$as['nama'].".pdf";
$pdf->Output($fileoutputnya,'D');

?>