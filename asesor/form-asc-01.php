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
$pdf->Cell(190,6,'FR-ASC-01. PELAKSANAAN ASESMEN DAN REKOMENDASI', '', 0,'L');
$pdf->Ln();
$skemaid="SKEMA SERTIFIKASI : KLASTER ".strtoupper($skemakkni);
$pdf->SetFont('Arial','B','10');
$pdf->Cell(190,10,$skemaid, '', 0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','B','8');
$pdf->Cell(25,5,'Nama Asesi', '', 0,'L');
$pdf->Cell(5,5,':', '', 0,'L');
$pdf->Cell(55,5,$as['nama'], 'B', 0,'L');
$pdf->Cell(5,5,'', '', 0,'L');
$pdf->Cell(30,5,'Tanggal/ Waktu', '', 0,'L');
$pdf->Cell(5,5,':', '', 0,'L');
$tglas=tgl_indo($jdq['tgl_asesmen'])." / Pukul ".$jdq['jam_asesmen'];
$pdf->Cell(60,5,$tglas, 'B', 0,'L');
$pdf->Ln();
$pdf->Cell(25,5,'Nama Asesor', '', 0,'L');
$pdf->Cell(5,5,':', '', 0,'L');
$pdf->Cell(55,5,$namaasesor, 'B', 0,'L');
$pdf->Cell(5,5,'', '', 0,'L');
$pdf->Cell(30,5,'Tempat', '', 0,'L');
$pdf->Cell(5,5,':', '', 0,'L');
$pdf->Cell(60,5,$tq['nama'], 'B', 0,'L');
$pdf->Ln();
$pdf->Cell(190,5,'', '', 0,'L');
$pdf->Ln();
$pdf->SetFont('Arial','B','10');
$keterangan1="Penjelasan untuk Asesor";
$kali=strlen($keterangan1)/100;
$kalinya=ceil($kali);
$hspace=4*$kalinya;
//$pdf->Cell(10,$hspace,'', 'LR', 0,'R');
//$pdf->Cell(5,$hspace,'', 'L', 0,'R');
// Format
//$pdf->MultiCell(0, 4, $keterangan1, 'R','J', 0, 0, '', '', true, 0, false, true, 4, 'LR');

$pdf->MultiCell(0, 4, $keterangan1, '','J', 0, 0, '', '', true, 0, false, true, 4, 'LR');
$pdf->Ln();
$pdf->SetFont('Arial','','9');

$ket1="Asesor mengorganisasikan pelaksanaan asesmen berdasarkan metoda dan instrumen/sumber-sumber asesmen seperti yang tercantum dalam perencanaan asesmen.";
$pdf->SetWidths(array(5,185));
$pdf->RowContentNoBorder(array('1.',$ket1));
$ket2="Asesor melaksanakan kegiatan pengumpulan bukti serta mendokumentasikan seluruh bukti pendukung yang dapat ditunjukkan oleh Asesi sesuai dengan kriteria unjuk kerja yang dipersyaratkan.";
$pdf->SetWidths(array(5,185));
$pdf->RowContentNoBorder(array('2.',$ket2));
$ket3="Asesor membuat keputusan apakah Asesi sudah Kompeten (K), Belum kompeten (BK) atau Asesmen Lanjut (PL), untuk setiap kriteria unjuk kerja berdasarkan bukti-bukti.";
$pdf->SetWidths(array(5,185));
$pdf->RowContentNoBorder(array('3.',$ket3));
$ket4="Asesor memberikan umpan balik kepada Asesi mengenai pencapaian unjuk kerja dan Asesi juga diminta untuk memberikan umpan balik terhadap proses asesmen yang dilaksanakan (kuesioner).";
$pdf->SetWidths(array(5,185));
$pdf->RowContentNoBorder(array('4.',$ket4));
$ket5="Asesor dan Asesi bersama-sama menandatangani pelaksanaan asesmen.";
$pdf->SetWidths(array(5,185));
$pdf->RowContentNoBorder(array('5.',$ket5));
$ket6="Beri tanda ( v ) pada kolom yang dipilih dengan simbul (*).";
$pdf->SetWidths(array(5,185));
$pdf->RowContentNoBorder(array('6.',$ket6));

$pdf->Cell(190,5,'', '', 0,'R');
$pdf->Ln();

// mulai dengan isi tablenya
$sqlgetunitkompetensi="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sq[id]'";
$getunitkompetensi=$conn->query($sqlgetunitkompetensi);
while ($unk=$getunitkompetensi->fetch_assoc()){
$pdf->Cell(190,0.5,'', 'T', 0,'R');
$pdf->Ln();
		$pdf->SetFont('Arial','B','9');
	$pdf->SetWidths(array(40,150));
	$unitkompetensi=$unk['kode_unit'];
	$pdf->RowContent(array('Kode Unit Kompetensi',$unitkompetensi));
	$pdf->SetWidths(array(40,150));
	$unitkompetensi2=$unk['judul'];
	$pdf->RowContent(array('Judul Unit Kompetensi',$unitkompetensi2));
$pdf->Cell(190,2,'', 'T', 0,'R');
$pdf->Ln();

	// elemen kompetensi
	$sqlgetelemen="SELECT * FROM `elemen_kompetensi` WHERE `id_unitkompetensi`='$unk[id]' ORDER BY `id` ASC";
	$getelemen=$conn->query($sqlgetelemen);
	$no=1;
	while ($el=$getelemen->fetch_assoc()){
		$pdf->SetFont('Arial','B','9');
			$pdf->SetWidths(array(40,150));
			$pdf->RowContent(array('Elemen Kompetensi',$el['elemen_kompetensi']));

		//header row
		//$pdf->Cell(30,1,'', 'LT', 0,'C');
		$pdf->Cell(100,1,'', 'LT', 0,'C');
		$pdf->Cell(60,1,'', 'LT', 0,'C');
		$pdf->Cell(30,1,'', 'LTR', 0,'C');
		$pdf->Ln();
		//$pdf->Cell(30,3,'', 'L', 0,'C');
		$pdf->Cell(100,3,'', 'L', 0,'C');
		$pdf->Cell(60,3,'Bukti-Bukti*', 'L', 0,'C');
		$pdf->Cell(30,3,'Penilaian*', 'LR', 0,'C');
		$pdf->Ln();
		//$pdf->Cell(30,1,'Elemen', 'L', 0,'C');
		$pdf->Cell(100,1,'', 'L', 0,'C');
		$pdf->Cell(60,1,'', 'L', 0,'C');
		$pdf->Cell(30,1,'', 'LR', 0,'C');
		$pdf->Ln();
		//$pdf->Cell(30,1,'', 'L', 0,'C');
		$pdf->Cell(100,1,'Kriteria Unjuk Kerja', 'L', 0,'C');
		$pdf->Cell(20,1,'', 'LT', 0,'C');
		$pdf->Cell(20,1,'', 'LT', 0,'C');
		$pdf->Cell(20,1,'', 'LRT', 0,'C');
		$pdf->Cell(10,1,'', 'LRT', 0,'C');
		$pdf->Cell(10,1,'', 'LRT', 0,'C');
		$pdf->Cell(10,1,'', 'LRT', 0,'C');
		$pdf->Ln();
		//$pdf->Cell(30,3,'Kompetensi', 'L', 0,'C');
		$pdf->Cell(100,3,'', 'L', 0,'C');
		$pdf->Cell(20,3,'Bukti', 'L', 0,'C');
		$pdf->Cell(20,3,'Bukti Tidak', 'L', 0,'C');
		$pdf->Cell(20,3,'Bukti', 'LR', 0,'C');
		$pdf->Cell(10,3,'K', 'LR', 0,'C');
		$pdf->Cell(10,3,'BK', 'LR', 0,'C');
		$pdf->Cell(10,3,'PL', 'LR', 0,'C');
		$pdf->Ln();
		//$pdf->Cell(30,2,'', 'L', 0,'C');
		$pdf->Cell(100,2,'', 'L', 0,'C');
		$pdf->Cell(20,2,'Langsung', 'L', 0,'C');
		$pdf->Cell(20,2,'Langsung', 'L', 0,'C');
		$pdf->Cell(20,2,'Tambahan', 'LR', 0,'C');
		$pdf->Cell(10,2,'', 'LR', 0,'C');
		$pdf->Cell(10,2,'', 'LR', 0,'C');
		$pdf->Cell(10,2,'', 'LR', 0,'C');
		$pdf->Ln();
		//$pdf->Cell(30,1,'', 'LB', 0,'C');
		$pdf->Cell(100,1,'', 'LB', 0,'C');
		$pdf->Cell(20,1,'', 'LB', 0,'C');
		$pdf->Cell(20,1,'', 'LB', 0,'C');
		$pdf->Cell(20,1,'', 'LRB', 0,'C');
		$pdf->Cell(10,1,'', 'LRB', 0,'C');
		$pdf->Cell(10,1,'', 'LRB', 0,'C');
		$pdf->Cell(10,1,'', 'LRB', 0,'C');
		$pdf->Ln();
		//$pdf->SetWidths(array(190));
		//$elemenkompetensi="Elemen Kompetensi : ".$no.". ".$el['elemen_kompetensi'];
		//$pdf->RowContent(array($elemenkompetensi));
		// kriteria unjuk kerja
		$sqlgetkriteria="SELECT * FROM `kriteria_unjukkerja` WHERE `id_elemenkompetensi`='$el[id]' ORDER BY `id` ASC";
		$getkriteria=$conn->query($sqlgetkriteria);
		$nokr=1;
		
		$pdf->SetFont('Arial','','9');
		$jumkr=$getkriteria->num_rows;
		$posisikr=$jumkr/2;
		$poskr=round($posisikr,0,PHP_ROUND_HALF_UP);
		$poskrplus=$poskr+1;
		while($kr=$getkriteria->fetch_assoc()){
			$kriteriatampil=$kr['kriteria'];
			$kriteriatampil2=str_replace("Apakah ","",$kriteriatampil);
			$kriteriatampil3=str_replace("Anda ","",$kriteriatampil2);
			$kriteria=str_replace("?","",$kriteriatampil3);
			$kali=strlen($kriteria)/38;
			$kalinya=ceil($kali);
			$hspace=5*$kalinya;


			$pdf->SetWidths(array(8,92,20,20,20,10,10,10));
			$nokriteria=$no.".".$nokr.".";


			//if ($nokr==$poskr){
				//$pdf->SetFont('Arial','B','8');
				//$pdf->Cell(30,$hspace,$el['elemen_kompetensi'], 'LR', 0,'C');
				//$pdf->MultiCell(30,4,$el['elemen_kompetensi'], 0, 'J', false);
			//}else{
				//if ($nokr==$poskrplus){
				//	$pdf->Cell(30,$hspace,'Unjuk Kerja', 'LR', 0,'C');
				//}else{
				//	$pdf->Cell(30,$hspace,'', 'LR', 0,'C');
				//}
			//}
			$pdf->SetFont('Arial','','9');
			$getnilai="SELECT * FROM `asesi_apl02` WHERE `id_asesi`='$as[no_pendaftaran]' AND `id_kriteria`='$kr[id]' AND `id_skemakkni`='$sq[id]'";
			$nilai=$conn->query($getnilai);
			$nil=$nilai->fetch_assoc();
			
			$nokr++;

					if ($nil['bukti1']=='L'){
						$bukti1="          v";
					}else{
						$bukti1="";
					}
					if ($nil['bukti2']=='TL'){
						$bukti2="          v";
					}else{
						$bukti2="";
					}
					if ($nil['bukti3']=='T'){
						$bukti3="          v";
					}else{
						$bukti3="";
					}
					switch ($nil['keputusan']){
					case "K":
						$keputusan1="    v";
						$keputusan2="";
						$keputusan3="";
					break;
					case "BK":
						$keputusan1="";
						$keputusan2="    v";
						$keputusan3="";
					break;
					case "PL":
						$keputusan1="";
						$keputusan2="";
						$keputusan3="    v";
					break;
					default:
						$keputusan1="";
						$keputusan2="";
						$keputusan3="";
					break;
					}


			$pdf->RowContent(array($nokriteria,$kriteria,$bukti1,$bukti2,$bukti3,$keputusan1,$keputusan2,$keputusan3));

		}

	$pdf->Cell(190,0,'', '', 0,'L');
	$pdf->Ln();

		$no++;
	}
	$pdf->Cell(190,10,'', '', 0,'L');
	$pdf->Ln();

}
$pdf->SetFont('Arial','I','8');
$pdf->Cell(190,3,'Keterangan : K = Kompeten, BK = Belum Kompeten, PL = Asesmen Lanjut', '', 0,'L');
$pdf->Ln();

$pdf->Ln();
//Cetak nomor halaman
$pdf->AliasNbPages();

//===========BAGIAN 2====================

$pdf->AddPage();

// Bagian Rekomendasi
$pdf->SetFont('Arial','','10');
$pdf->SetWidths(array(100,90));

$sqlasesmenasesik="SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`='$jdq[id]' AND `id_asesi`='$as[no_pendaftaran]'";
$asesmenasesik=$conn->query($sqlasesmenasesik);
$rowkep = $asesmenasesik->fetch_assoc();
switch ($rowkep['keputusan_asesor']){
case "R":
$rekomendasi='Asesi telah diberikan umpan balik/masukan dan diinformasikan hasil asesmen/uji kompetensi serta penjelasan terhadap keputusan yang dibuat. Berdasarkan hasil asesmen tersebut, peserta :
DIREKOMENDASIKAN
Untuk mendapatkan pengakuan terhadap unit kompetensi yang diujikan.';

break;
case "NR":
$rekomendasi='Asesi telah diberikan umpan balik/masukan dan diinformasikan hasil asesmen/uji kompetensi serta penjelasan terhadap keputusan yang dibuat. Berdasarkan hasil asesmen tersebut, peserta :
TIDAK DIREKOMENDASIKAN
Untuk mendapatkan pengakuan terhadap unit kompetensi yang diujikan.';

break;
default:
$rekomendasi='Asesi telah diberikan umpan balik/masukan dan diinformasikan hasil asesmen/uji kompetensi serta penjelasan terhadap keputusan yang dibuat. Berdasarkan hasil asesmen tersebut, peserta :
Direkomendasikan/ Tidak direkomendasikan *)
Untuk mendapatkan pengakuan terhadap unit kompetensi yang diujikan.';
break;
}
$rekomendasiremark='Nama Asesor
';
$pdf->SetFont('Arial','','10');
$rekomendasiremark.=$namaasesor;
$rekomendasiremark.='
No. Reg.: '.$asr['no_induk'];
$rekomendasiremark.='
Tanda Tangan:


_________________  Tgl._____________';
$pdf->RowContent(array($rekomendasi,$rekomendasiremark));

$rekomendasix='Saya mengkonfirmasikan bahwa peserta telah melaksanakan asesmen pada unit kompetensi ini dan saya menyatakan
[     ] Setuju          [     ] Tidak Setuju';
$rekomendasiremarkx='Nama Tenaga Ahli/ Subject Specialist :
';
$pdf->SetFont('Arial','','10');
$rekomendasiremarkx.='_______________________________';
$rekomendasiremarkx.='
Tanda Tangan:


_________________  Tgl._____________';
$pdf->RowContent(array($rekomendasix,$rekomendasiremarkx));

$rekomendasib='Saya telah mendapatkan umpan balik/ masukan terhadap bukti yang telah saya berikan serta informasi mengenai hasil asesmen dan penjelasan untuk keputusan yang dibuat';
$rekomendasiremarkb='Nama Asesi
';
$pdf->SetFont('Arial','','10');
$rekomendasiremarkb.=$as['nama'];
$rekomendasiremarkb.='
Tanda Tangan:


_________________  Tgl._____________';
$pdf->RowContent(array($rekomendasib,$rekomendasiremarkb));


$pdf->Code39(10,280,$kodever,1,7);

$pdf->AliasNbPages();

//output file pdf
$fileoutputnya="form-asc-01-Terverifikasi-".$skemakkni."-".$as['no_pendaftaran'].".pdf";
$pdf->Output($fileoutputnya,'I');

?>