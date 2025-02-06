<?php
//ini_set('display_errors',1); 
//error_reporting(E_ALL);
ob_start();
include 'fpdf-easytable-master/fpdf.php';
include 'fpdf-easytable-master/exfpdf.php';
include 'fpdf-easytable-master/easyTable.php';
include "../config/koneksi.php";
include "../config/library.php";
include "../config/fungsi_indotgl.php";

$sqlasesi="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_GET[ida]'";
$asesi=$conn->query($sqlasesi);
$as=$asesi->fetch_assoc();
$sqljadwal="SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
$jadwal=$conn->query($sqljadwal);
$jdq=$jadwal->fetch_assoc();
$tgl_cetak = tgl_indo($jdq['tgl_asesmen']);

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


$pdf=new exFPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',10);
$pdf->AddFont('FontUTF8','','Arimo-Regular.php'); 
$pdf->AddFont('FontUTF8','B','Arimo-Bold.php');
$pdf->AddFont('FontUTF8','I','Arimo-Italic.php');
$pdf->AddFont('FontUTF8','BI','Arimo-BoldItalic.php');

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
//$pdf->Cell(0, 5, '', '0', 1, 'C');
$pdf->Ln();
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

$write=new easyTable($pdf, 1, 'width:180;  font-style:B; font-size:12;font-family:arial;');
$write->easyCell('FR.IA.11. CEKLIS MENINJAU INSTRUMEN ASESSMEN', 'align:L;');
$write->printRow();
$write->endTable(5);

$write=new easyTable($pdf, '{50, 20, 5, 105}', 'width:180; align:C; font-style:B; font-family:arial; font-size:12');
$write->easyCell('Skema Sertifikasi (KKNI/Okupasi/Klaster)', 'align:L; rowspan:2; border:LTBR');
$write->easyCell('Judul', 'align:L; font-size:10; border:LTBR');
$write->easyCell(':', 'align:C; font-size:10; border:LTBR');
$write->easyCell($skemakkni, 'align:L; font-size:10; border:LTBR');
$write->printRow();
$write->easyCell('Nomor', 'align:L; font-size:10; border:LTBR');
$write->easyCell(':', 'align:C; font-size:10; border:LTBR');
$write->easyCell($sq['kode_skema'], 'align:L; font-size:10; border:LTBR');
$write->printRow();
$write->easyCell('TUK', 'align:L; font-size:10; colspan:2; border:LTBR');
$write->easyCell(':', 'align:C; font-size:10; border:LTBR');
$sqlgetjenistuk="SELECT * FROM `tuk_jenis` WHERE `id`='$tq[jenis_tuk]'";
$getjenistuk=$conn->query($sqlgetjenistuk);
$jnstuk=$getjenistuk->fetch_assoc();
$write->easyCell($jnstuk['jenis_tuk'], 'align:L; font-size:10; border:LTBR');
$write->printRow();

$noasr=1;
$getasesor=$conn->query("SELECT * FROM `jadwal_asesor` WHERE `id_jadwal`='$_GET[idj]'");
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
	$noregasesor=$asr['no_induk'];
	$namaasesor=$noasr.'. '.$namaasesor;
	$noregasesor=$noasr.'. '.$noregasesor;

	$noasr++;

}

$write->easyCell('Nama Asesor', 'align:L; font-size:10; colspan:2; border:LTBR');
$write->easyCell(':', 'align:C; font-size:10; border:LTBR');
$write->easyCell($namaasesor, 'align:L; font-size:10; border:LTBR');
$write->printRow();
$write->easyCell('Nama Asesi', 'align:L; font-size:10; colspan:2; border:LTBR');
$write->easyCell(':', 'align:C; font-size:10; border:LTBR');
$write->easyCell($as['nama'], 'align:L; font-size:10; border:LTBR');
$write->printRow();
$write->easyCell('Tanggal', 'align:L; font-size:10; colspan:2; border:LTBR');
$write->easyCell(':', 'align:C; font-size:10; border:LTBR');
$write->easyCell($tgl_cetak, 'align:L; font-size:10; border:LTBR');
$write->printRow();
$write->endTable(5);

$write=new easyTable($pdf, '{180}', 'width:180; align:C; font-family:arial; font-size:12');
$write->easyCell('PANDUAN BAGI ASESOR', 'align:L; border:LTBR; bgcolor:#C6C6C6; font-style:B;');
$write->printRow();
$write->endTable(0);
$write=new easyTable($pdf, '{10,170}', 'width:180; align:C; font-family:arial; font-size:12');
$write->easyCell(chr(149), 'align:C; border:L;');
$write->easyCell('Isilah tabel ini sesuai dengan informasi sesuai pertanyaan/pernyataan dalam table dibawah ini.', 'align:L; border:R;');
$write->printRow();
$write->easyCell(chr(149), 'align:C; border:L;');
$write->easyCell('Beri tanda centang (V) pada hasil penilaian MUK berdasarkan tinjauan anda dengan jastifikasi professional anda.', 'align:L; border:R;');
$write->printRow();
$write->easyCell(chr(149), 'align:C; border:LB;');
$write->easyCell('Berikan komentar dengan jastifikasi profesional anda.', 'align:L; border:RB;');
$write->printRow();
$write->endTable(5);

$write=new easyTable($pdf, '{30, 30, 5, 115}', 'width:180; align:C; font-style:B; font-family:arial; font-size:12');
$sqlgetunitkompetensi="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sq[id]'";
$getunitkompetensi=$conn->query($sqlgetunitkompetensi);
$jumunitnya0=$getunitkompetensi->num_rows;
$jumunitnya=$jumunitnya0*2;
$noku=1;
//while unitkompetensi ==================================================================
while ($unk=$getunitkompetensi->fetch_assoc()){
	if ($noku==1){
		$write->easyCell('Unit Kompetensi', 'align:C; rowspan:'.$jumunitnya.'; border:LTBR');
		$write->easyCell('Kode Unit', 'align:L; font-size:12; border:LTBR');
		$write->easyCell(':', 'align:C; font-size:12; border:LTBR');
		$write->easyCell($unk['kode_unit'], 'align:L; font-size:12; border:LTBR');
		$write->printRow();
		$write->easyCell('Judul Unit', 'align:L; font-size:12; border:LTBR');
		$write->easyCell(':', 'align:C; font-size:12; border:LTBR');
		$write->easyCell($unk['judul'], 'align:L; font-size:12; border:LTBR');
		$write->printRow();
	}else{
		$write->easyCell('Kode Unit', 'align:L; font-size:12; border:LTBR');
		$write->easyCell(':', 'align:C; font-size:12; border:LTBR');
		$write->easyCell($unk['kode_unit'], 'align:L; font-size:12; border:LTBR');
		$write->printRow();
		$write->easyCell('Judul Unit', 'align:L; font-size:12; border:LTBR');
		$write->easyCell(':', 'align:C; font-size:12; border:LTBR');
		$write->easyCell($unk['judul'], 'align:L; font-size:12; border:LTBR');
		$write->printRow();
	}
	$noku++;
}
//end while unitkompetensi =============================================
$write->endTable(5);

$write=new easyTable($pdf, '{10,70,15,15,70}', 'width:180; align:C; font-family:arial; font-size:12');
$write->easyCell('Kegiatan Asesmen', 'align:C; valign:M; font-size:12; font-style:B; colspan:2; border:LTBR');
$write->easyCell('Ya/Tidak', 'align:C; valign:M; font-size:12; font-style:B; colspan:2; border:LTBR');
$write->easyCell('Komentar', 'align:C; valign:M; font-size:12; font-style:B; border:LTBR');
$write->printRow();
$write->rowStyle('min-height:15');
$write->easyCell(chr(149), 'align:C; valign:T; font-size:11; font-style:B; border:LTB');
$write->easyCell('Instruksi perangkat asesmen dan kondisi asesmen diidentifikasi dengan jelas', 'align:L; valign:T; font-size:12; border:TBR');
$sqlgetjwb1="SELECT * FROM `asesmen_ia11` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='1'";
$getjwb1=$conn->query($sqlgetjwb1);
$jwb1=$getjwb1->fetch_assoc();
switch($jwb1['jawaban']){
	case "Ya":
		$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell($jwb1['komentar'], 'align:L; valign:T; font-size:11; font-style:B; border:LTBR');
	break;
	case "Tidak":
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell($jwb1['komentar'], 'align:L; valign:T; font-size:11; font-style:B; border:LTBR');
	break;
	default:
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell('', 'align:C; valign:M; font-size:11; font-style:B; border:LTBR');
	break;
}
$write->printRow();
$write->rowStyle('min-height:15');
$write->easyCell(chr(149), 'align:C; valign:T; font-size:11; font-style:B; border:LTB');
$write->easyCell('Informasi tertulis dituliskan secara tepat', 'align:L; valign:T; font-size:12; border:TBR');
$sqlgetjwb2="SELECT * FROM `asesmen_ia11` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='2'";
$getjwb2=$conn->query($sqlgetjwb2);
$jwb2=$getjwb2->fetch_assoc();
switch($jwb2['jawaban']){
	case "Ya":
		$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell($jwb2['komentar'], 'align:L; valign:T; font-size:11; font-style:B; border:LTBR');
	break;
	case "Tidak":
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell($jwb2['komentar'], 'align:L; valign:T; font-size:11; font-style:B; border:LTBR');
	break;
	default:
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell('', 'align:C; valign:M; font-size:11; font-style:B; border:LTBR');
	break;
}
$write->printRow();
$write->rowStyle('min-height:15');
$write->easyCell(chr(149), 'align:C; valign:T; font-size:11; font-style:B; border:LTB');
$write->easyCell('Kegiatan asesmen membahas persyaratan bukti untuk kompetensi atau kompetensi yang diases', 'align:L; valign:T; font-size:12; border:TBR');
$sqlgetjwb3="SELECT * FROM `asesmen_ia11` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='3'";
$getjwb3=$conn->query($sqlgetjwb3);
$jwb3=$getjwb3->fetch_assoc();
switch($jwb3['jawaban']){
	case "Ya":
		$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell($jwb3['komentar'], 'align:L; valign:T; font-size:11; font-style:B; border:LTBR');
	break;
	case "Tidak":
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell($jwb3['komentar'], 'align:L; valign:T; font-size:11; font-style:B; border:LTBR');
	break;
	default:
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell('', 'align:C; valign:M; font-size:11; font-style:B; border:LTBR');
	break;
}
$write->printRow();
$write->rowStyle('min-height:15');
$write->easyCell(chr(149), 'align:C; valign:T; font-size:11; font-style:B; border:LTB');
$write->easyCell('Tingkat kesulitan bahasa, literasi, dan berhitung sesuai dengan tingkat unit kompetensi yang dinilai.', 'align:L; valign:T; font-size:12; border:TBR');
$sqlgetjwb4="SELECT * FROM `asesmen_ia11` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='4'";
$getjwb4=$conn->query($sqlgetjwb4);
$jwb4=$getjwb4->fetch_assoc();
switch($jwb4['jawaban']){
	case "Ya":
		$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell($jwb4['komentar'], 'align:L; valign:T; font-size:11; font-style:B; border:LTBR');
	break;
	case "Tidak":
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell($jwb4['komentar'], 'align:L; valign:T; font-size:11; font-style:B; border:LTBR');
	break;
	default:
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell('', 'align:C; valign:M; font-size:11; font-style:B; border:LTBR');
	break;
}
$write->printRow();
$write->rowStyle('min-height:15');
$write->easyCell(chr(149), 'align:C; valign:T; font-size:11; font-style:B; border:LTB');
$write->easyCell('Tingkat kesulitan kegiatan sesuai dengan kompetensi atau kompetensi yang diases.', 'align:L; valign:T; font-size:12; border:TBR');
$sqlgetjwb5="SELECT * FROM `asesmen_ia11` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='5'";
$getjwb5=$conn->query($sqlgetjwb5);
$jwb5=$getjwb5->fetch_assoc();
switch($jwb5['jawaban']){
	case "Ya":
		$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell($jwb5['komentar'], 'align:L; valign:T; font-size:11; font-style:B; border:LTBR');
	break;
	case "Tidak":
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell($jwb5['komentar'], 'align:L; valign:T; font-size:11; font-style:B; border:LTBR');
	break;
	default:
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell('', 'align:C; valign:M; font-size:11; font-style:B; border:LTBR');
	break;
}
$write->printRow();
$write->rowStyle('min-height:15');
$write->easyCell(chr(149), 'align:C; valign:T; font-size:11; font-style:B; border:LTB');
$write->easyCell('Contoh, benchmark dan / atau ceklis asesmen tersedia untuk digunakan dalam pengambilan keputusan asesmen.', 'align:L; valign:T; font-size:12; border:TBR');
$sqlgetjwb6="SELECT * FROM `asesmen_ia11` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='6'";
$getjwb6=$conn->query($sqlgetjwb6);
$jwb6=$getjwb6->fetch_assoc();
switch($jwb6['jawaban']){
	case "Ya":
		$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell($jwb6['komentar'], 'align:L; valign:T; font-size:11; font-style:B; border:LTBR');
	break;
	case "Tidak":
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell($jwb6['komentar'], 'align:L; valign:T; font-size:11; font-style:B; border:LTBR');
	break;
	default:
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell('', 'align:C; valign:M; font-size:11; font-style:B; border:LTBR');
	break;
}
$write->printRow();
$write->rowStyle('min-height:15');
$write->easyCell(chr(149), 'align:C; valign:T; font-size:11; font-style:B; border:LTB');
$write->easyCell('Diperlukan modifikasi (seperti yang diidentifikasi dalam Komentar)', 'align:L; valign:T; font-size:12; border:TBR');
$sqlgetjwb7="SELECT * FROM `asesmen_ia11` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='7'";
$getjwb7=$conn->query($sqlgetjwb7);
$jwb7=$getjwb7->fetch_assoc();
switch($jwb7['jawaban']){
	case "Ya":
		$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell($jwb7['komentar'], 'align:L; valign:T; font-size:11; font-style:B; border:LTBR');
	break;
	case "Tidak":
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell($jwb7['komentar'], 'align:L; valign:T; font-size:11; font-style:B; border:LTBR');
	break;
	default:
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell('', 'align:C; valign:M; font-size:11; font-style:B; border:LTBR');
	break;
}
$write->printRow();
$write->rowStyle('min-height:15');
$write->easyCell(chr(149), 'align:C; valign:T; font-size:11; font-style:B; border:LTB');
$write->easyCell('Tugas asesmen siap digunakan:', 'align:L; valign:T; font-size:12; border:TBR');
$sqlgetjwb8="SELECT * FROM `asesmen_ia11` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='8'";
$getjwb8=$conn->query($sqlgetjwb8);
$jwb8=$getjwb8->fetch_assoc();
switch($jwb8['jawaban']){
	case "Ya":
		$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell($jwb8['komentar'], 'align:L; valign:T; font-size:11; font-style:B; border:LTBR');
	break;
	case "Tidak":
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell($jwb8['komentar'], 'align:L; valign:T; font-size:11; font-style:B; border:LTBR');
	break;
	default:
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
		$write->easyCell('', 'align:C; valign:M; font-size:11; font-style:B; border:LTBR');
	break;
}
$write->printRow();
$write->endTable(5);
$write->rowStyle('min-height:10');

$sqlgetkomentar="SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
$getkomentar=$conn->query($sqlgetkomentar);
$gkom=$getkomentar->fetch_assoc();

$write=new easyTable($pdf, '{60, 40, 80}', 'width:180; align:C; font-style:B; font-family:arial; font-size:12');
$write->easyCell('Nama Peninjau', 'align:L; valign:T; border:LTR');
$write->easyCell('Tanggal Tanda Tangan Peninjau', 'align:L; valign:T; border:LTR');
$write->easyCell('Komentar', 'align:L; valign:T; border:LTR');
$write->printRow();
$peninjau=$gkom['peninjau_ia11'];
if (empty($peninjau)){
	$write->rowStyle('min-height:40');
}
$sqlasesorp="SELECT * FROM `asesor` WHERE `id`='$peninjau'";
$asesorp=$conn->query($sqlasesorp);
$asrp=$asesorp->fetch_assoc();
if (!empty($asrp['gelar_depan'])){
	if (!empty($asrp['gelar_blk'])){
		$namaasesorp=$asrp['gelar_depan']." ".$asrp['nama'].", ".$asrp['gelar_blk'];
	}else{
		$namaasesorp=$asrp['gelar_depan']." ".$asrp['nama'];
	}
}else{
	if (!empty($asrp['gelar_blk'])){
		$namaasesorp=$asrp['nama'].", ".$asrp['gelar_blk'];
	}else{
		$namaasesorp=$asrp['nama'];
	}
}

$write->easyCell($namaasesorp, 'align:L; valign:T; border:LBR');
// tandatangan asesor
$sqlidentitas="SELECT * FROM `identitas`";
$identitas=$conn->query($sqlidentitas);
$iden=$identitas->fetch_assoc();
$urltandatanganas=$iden['url_domain']."/asesor/media.php?module=form-fr-ia-11&amp;ida=".$as['no_pendaftaran']."&amp;idj=".$jdq['id'];
$sqlcekttdasesiapl01as="SELECT * FROM `logdigisign` WHERE `nama_dokumen`='FR.IA.11. CEKLIS MENINJAU INSTRUMEN ASESMEN' AND `penandatangan`='$asrp[nama]' AND `url_ditandatangani`='$urltandatanganas' ORDER BY `id` DESC";
$cekttdasesiapl01as=$conn->query($sqlcekttdasesiapl01as);
$jumttdasesias=$cekttdasesiapl01as->num_rows;
$ttdasas=$cekttdasesiapl01as->fetch_assoc();
if ($jumttdasesias>0){
	$write->easyCell(tgl_indo($ttdasas['waktu']), 'img:'.$ttdasas['file'].', h20; align:C; valign:T; font-size:10; font-style:B; border:LBR;');
}else{
	$write->easyCell('', 'align:L; valign:T; font-size:10; font-style:B; border:LBR;');
}
//$write->easyCell('', 'align:L; valign:T; border:LBR');
if (!empty($gkom['komentar_ia11'])){
	$write->easyCell($gkom['komentar_ia11'], 'align:L; valign:T; border:LBR');
}else{
	$write->easyCell('', 'align:L; valign:T; border:LBR');
}
$write->printRow();
$write->easyCell('Diadopsi dari templat yang disediakan di Departemen Pendidikan dan Pelatihan, Australia. Merancang alat asesmen untuk hasil yang berkualitas di VET. 2008', 'align:L; font-style:I; valign:T; colspan:3');
$write->printRow();
$write->endTable(5);


$pdf->AliasNbPages();

//output file pdf
$fileoutputnya="FR-IA-11-".$skemakkni."-".$_GET['idj']."-".$_GET['ida'].".pdf";
$pdf->Output($fileoutputnya,'I');
 
ob_end_flush();
?>