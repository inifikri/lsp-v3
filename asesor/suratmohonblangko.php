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

function penyebut($nilai) {
	$nilai = abs($nilai);
	$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
	$temp = "";
	if ($nilai < 12) {
		$temp = " ". $huruf[$nilai];
	} else if ($nilai <20) {
		$temp = penyebut($nilai - 10). " belas";
	} else if ($nilai < 100) {
		$temp = penyebut($nilai/10)." puluh". penyebut($nilai % 10);
	} else if ($nilai < 200) {
		$temp = " seratus" . penyebut($nilai - 100);
	} else if ($nilai < 1000) {
		$temp = penyebut($nilai/100) . " ratus" . penyebut($nilai % 100);
	} else if ($nilai < 2000) {
		$temp = " seribu" . penyebut($nilai - 1000);
	} else if ($nilai < 1000000) {
		$temp = penyebut($nilai/1000) . " ribu" . penyebut($nilai % 1000);
	} else if ($nilai < 1000000000) {
		$temp = penyebut($nilai/1000000) . " juta" . penyebut($nilai % 1000000);
	} else if ($nilai < 1000000000000) {
		$temp = penyebut($nilai/1000000000) . " milyar" . penyebut(fmod($nilai,1000000000));
	} else if ($nilai < 1000000000000000) {
		$temp = penyebut($nilai/1000000000000) . " trilyun" . penyebut(fmod($nilai,1000000000000));
	}     
	return $temp;
}

function terbilang($nilai) {
	if($nilai<0) {
		$hasil = "minus ". trim(penyebut($nilai));
	} else {
		$hasil = trim(penyebut($nilai));
	}     		
	return $hasil;
}
$sqlidentitas="SELECT * FROM `identitas`";
$identitas=$conn->query($sqlidentitas);
$iden=$identitas->fetch_assoc();

$nomorsuratkeputusan=$_POST['no_surattugas'];
$sqljadwal="SELECT * FROM `jadwal_asesmen` WHERE `id`='$_POST[idj]'";
$jadwal=$conn->query($sqljadwal);
$jdq=$jadwal->fetch_assoc();
$tgl_cetak = tgl_indo($jdq['tgl_asesmen']);
$tgl_cetak_akhir = tgl_indo($jdq['tgl_asesmen_akhir']);

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
$ketualspnya=strtoupper($lq['nama_jabatanpimpinan'])." ".$namalsp;
$write=new easyTable($pdf, '{30,5,145}', 'width:180; align:C; font-size:12; font-family:arial;');
$write->easyCell('Nomor', 'align:L;');
$write->easyCell(':', 'align:L;');
$write->easyCell($nomorsuratkeputusan, 'align:L;');
$write->printRow();
$write->easyCell('Perihal', 'align:L;');
$write->easyCell(':', 'align:L;');
$write->easyCell('Permohonan Blangko Sertifikat Kompetensi', 'align:L;');
$write->printRow();
$write->easyCell('Lampiran', 'align:L;');
$write->easyCell(':', 'align:L;');
$write->easyCell('1 Bendel', 'align:L;');
$write->printRow();
$write->endTable(5);

$write=new easyTable($pdf, 1, 'width:180; align:C; font-style:B; font-size:12;font-family:arial;');
$write->easyCell('Kepada :', 'align:L;');
$write->printRow();
$write->easyCell('Yth. Ketua Badan Nasional Sertifikasi Profesi (BNSP)', 'align:L;');
$write->printRow();
$write->easyCell('         di Tempat', 'align:L;');
$write->printRow();
$write->endTable(5);

$sqlgetplenotgl="SELECT * FROM `komite_keputusan` WHERE `id_jadwal`='$_POST[idj]' ORDER BY `waktu` DESC LIMIT 1";
$getplenotgl=$conn->query($sqlgetplenotgl);
$tglpln=$getplenotgl->fetch_assoc();
$thn_plenonya=substr($tglpln['waktu'],0,4);
$tgl_plenonya=substr($tglpln['waktu'],8,2);
$bln_plenonya=substr($tglpln['waktu'],5,2);
switch ($bln_plenonya){
	case "01":
		$bulanplenonya="Januari";
	break;
	case "02":
		$bulanplenonya="Februari";
	break;
	case "03":
		$bulanplenonya="Maret";
	break;
	case "04":
		$bulanplenonya="April";
	break;
	case "05":
		$bulanplenonya="Mei";
	break;
	case "06":
		$bulanplenonya="Juni";
	break;
	case "07":
		$bulanplenonya="Juli";
	break;
	case "08":
		$bulanplenonya="Agustus";
	break;
	case "09":
		$bulanplenonya="September";
	break;
	case "10":
		$bulanplenonya="Oktober";
	break;
	case "11":
		$bulanplenonya="November";
	break;
	case "12":
		$bulanplenonya="Desember";
	break;
}
$sqlcekasesi="SELECT DISTINCT `id_jadwal` FROM `asesi_asesmen` WHERE `id_jadwal`='$_POST[idj]'";
$cekasesi=$conn->query($sqlcekasesi);
$sqlcekasesi2="SELECT DISTINCT `id_asesi` FROM `asesi_asesmen` WHERE `id_jadwal`='$_POST[idj]'";
$cekasesi2=$conn->query($sqlcekasesi2);
$jumasesi=$cekasesi2->num_rows;
// hitung yang dinyatakan kompeten
$sqlgetplenok="SELECT DISTINCT `id_asesi` FROM `komite_keputusan` WHERE `id_jadwal`='$_POST[idj]' AND `keputusan`='K'";
$getplenok=$conn->query($sqlgetplenok);
$plenok=$getplenok->num_rows;
// hitung yang dinyatakan kompeten
$sqlgetplenobk="SELECT DISTINCT `id_asesi` FROM `komite_keputusan` WHERE `id_jadwal`='$_POST[idj]' AND `keputusan`='BK'";
$getplenobk=$conn->query($sqlgetplenobk);
$plenobk=$getplenobk->num_rows;

if ($plenobk>0){
	$redbk=$plenobk." (".terbilang($plenobk).")";
}else{
	$redbk="tidak ada";
}
$tglberitaacara0=$thn_plenonya."-".$bln_plenonya."-".$tgl_plenonya;
$tglberitaacara=tgl_indo($thn_plenonya."-".$bln_plenonya."-".$tgl_plenonya);

$write=new easyTable($pdf, '{180}', 'width:180; align:C; font-size:12; font-family:arial;');
$write->easyCell('Dengan Hormat,', 'align:L;');
$write->printRow();
$write->easyCell('          Bersama dengan surat ini, kami '.$namalsp.' melaporkan hasil uji kompetensi dan mengajukan blangko sertifikat kompetensi sebagai berikut:', 'align:L;');
$write->printRow();
$write->endTable(5);
$write=new easyTable($pdf, '{10,70,25,25,25,25}', 'width:180; align:C; font-size:10; font-style:B; font-family:arial;');
$write->easyCell('No.', 'align:C; font-style:B; font-size:10; border:LTBR; rowspan:2;');
$write->easyCell('Skema', 'align:C; font-style:B; font-size:10; border:LTBR; rowspan:2;');
$write->easyCell('Jumlah Asesi', 'align:C; font-style:B; font-size:10; border:LTBR; rowspan:2;');
$write->easyCell('Hasil Uji Kompetensi', 'align:C; font-style:B; font-size:10; border:LTBR; colspan:2;');
$write->easyCell('Jumlah Permohonan Blangko', 'align:C; font-style:B; font-size:10; border:LTBR; rowspan:2;');
$write->printRow();
$write->easyCell('Kompeten', 'align:C; font-style:B; font-size:10; border:LTBR;');
$write->easyCell('Belum Kompeten', 'align:C; font-style:B; font-size:10; border:LTBR;');
$write->printRow();
$nox=1;
while ($assc=$cekasesi->fetch_assoc()){
	/* $sqlgetasesi="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$assc[id_asesi]'";
	$getasesi=$conn->query($sqlgetasesi);
	$as=$getasesi->fetch_assoc(); */
	$write->easyCell($nox.'.', 'align:C; font-style:B; font-size:10; border:LTBR;');
	$write->easyCell($skemakkni, 'align:C; font-style:B; font-size:10; border:LTBR;');
	// hitung keputusan komite
	/* $sqlhitungk="SELECT * FROM `komite_keputusan` WHERE `id_asesi`='$assc[id_asesi]' AND `keputusan`='K'";
	$hitungk=$conn->query($sqlhitungk);
	$hitk=$hitungk->num_rows;
	$sqlhitungbk="SELECT * FROM `komite_keputusan` WHERE `id_asesi`='$assc[id_asesi]' AND `keputusan`='BK'";
	$hitungbk=$conn->query($sqlhitungbk);
	$hitbk=$hitungbk->num_rows; */
	$write->easyCell($jumasesi, 'align:C; font-style:B; font-size:10; border:LTBR;');
	$write->easyCell($plenok, 'align:C; font-style:B; font-size:10; border:LTBR;');
	$write->easyCell($plenobk, 'align:C; font-style:B; font-size:10; border:LTBR;');
	$write->easyCell($plenok, 'align:C; font-style:B; font-size:10; border:LTBR;');
	$write->printRow();
	$nox++;
}
$write->endTable(5);
$write=new easyTable($pdf, '{180}', 'width:180; align:C; font-size:12; font-family:arial;');
$write->easyCell('          Adapun formulir laporan asesmen dari masing-masing asesor kami sertakan bersama dengan surat ini. Demikian permohonan ini dibuat untuk dijadikan pertimbangan dan periksa. Atas perhatiannya kami sampaikan terimakasih.', 'align:L;');
$write->printRow();
$write->endTable(5);
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

$tanggalsk=date('Y-m-d', strtotime('+1 days', strtotime($tglberitaacara0)));

$tglsknya=tgl_indo($tanggalsk);

$tglcetakkota = ucwords(strtolower($namakotax)).", ".$tglsknya;


$write=new easyTable($pdf, 1, 'width:80; align:R; font-size:12;font-family:arial;');
$write->easyCell($tglcetakkota, 'align:C;');
$write->printRow();
$write->endTable(0);
$write=new easyTable($pdf, '{80}', 'width:180; align:R; font-size:12; font-family:arial;');
$write->easyCell($namalsp, 'align:C;');
$write->printRow();
$write->rowStyle('min-height:15');
$write->easyCell('', 'img:../images/ttd-stempel-pimpinan.png, h25; align:C;');
$write->printRow();
$write->easyCell($lq['direktur'], 'align:C; font-style:BU;');
$write->printRow();
$write->easyCell($lq['nama_jabatanpimpinan'], 'align:C;');
$write->printRow();
$write->endTable(5);
$pdf->AliasNbPages();

//output file pdf
$fileoutputnya="Surat-Permohonan-Blangko-".$tglsknya."-".$_POST['no_surattugas'].".pdf";
$pdf->Output($fileoutputnya,'D');
 
ob_end_flush();
?>