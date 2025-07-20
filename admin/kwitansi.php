<?php
ob_start();
include '../asesor/fpdf-easytable-master/fpdf.php';
include '../asesor/fpdf-easytable-master/exfpdf.php';
include '../asesor/fpdf-easytable-master/easyTable.php';
include "../config/koneksi.php";
include "../config/library.php";
include "../config/fungsi_indotgl.php";

$sqlasesi="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_GET[ida]'";
$asesi=$conn->query($sqlasesi);
$as=$asesi->fetch_assoc();
$sqlgetasesmen="SELECT * FROM `asesi_asesmen` WHERE `id`='$_GET[idas]'";
$getasesmen=$conn->query($sqlgetasesmen);
$asas=$getasesmen->fetch_assoc();
$sqljadwal="SELECT * FROM `jadwal_asesmen` WHERE `id`='$asas[id_jadwal]'";
$jadwal=$conn->query($sqljadwal);
$jdq=$jadwal->fetch_assoc();
$tglsekarang=date("Y-m-d");
$tgl_cetak = tgl_indo($tglsekarang);

$sqltuk="SELECT * FROM `tuk` WHERE `id`='$jdq[tempat_asesmen]'";
$tuk=$conn->query($sqltuk);
$tq=$tuk->fetch_assoc();
$sqlgetlsp="SELECT * FROM `lsp`";
$glsp=$conn->query($sqlgetlsp);
$lq=$glsp->fetch_assoc();
$sqlskema="SELECT * FROM `skema_kkni` WHERE `id`='$_GET[idsk]'";
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

$write=new easyTable($pdf, 1, 'width:190;  font-style:B; font-size:12;font-family:arial;');
$write->easyCell('KWITANSI PEMBAYARAN', 'align:C; font-size:16;');
$write->printRow();
$write->endTable(5);


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

$write=new easyTable($pdf, '{190}', 'width:190; align:L; font-size:10;font-family:arial;');
$keterangan1="Telah terima pembayaran dari :";
$write->easyCell($keterangan1);
$write->printRow();
$keterangan2=$as['nama']." (No. KTP/NIK : ".$as['no_ktp'].")";
$write->easyCell($keterangan2, 'font-style:B; font-size:12;');
$write->printRow();
$write->endTable(0);


$write=new easyTable($pdf, '{10, 40, 50, 50, 30}', 'width:190; align:C; font-size:10;font-family:arial;');
$sqlasesmenasesi0="SELECT * FROM `asesi_pembayaran` WHERE `id_asesi`='$as[no_pendaftaran]' AND `id_skemakkni`='$_GET[idsk]' AND `status`='V' ORDER BY `tgl_bayar` ASC";
$asesmenasesi0=$conn->query($sqlasesmenasesi0);
$nops=1;
$totalnominal=0;
$write->easyCell('No.','align:R; border:LTB');
$write->easyCell('Tanggal Transaksi','align:L; border:LTB');
$write->easyCell('Metode Pembayaran','align:L; border:LTB');
$write->easyCell('Jalur Pembayaran/Channel','align:L; border:LTB');
$write->easyCell('Nominal','align:L; border:LTBR');
$write->printRow();
while ($asdj=$asesmenasesi0->fetch_assoc()){
	$write->easyCell($nops,'align:R; border:LTB');
	$write->easyCell($asdj['tgl_bayar']." ".$asdj['jam_bayar'],'align:L; border:LTB');
	$write->easyCell($asdj['metode_bayar'],'align:L; border:LTB');
	$write->easyCell($asdj['jalur_bayar'],'align:L; border:LTB');
	$nominalshow="Rp. ".number_format($asdj['nominal'],2,",",".");
	$write->easyCell($nominalshow,'align:R; border:LTBR');
	$write->printRow();
	$totalnominal=$totalnominal+$asdj['nominal'];
	$nops++;
}
$write->printRow();
$write->easyCell('TOTAL','align:R; border:LTB; colspan:4; font-style:B;');
$nominalshow2="Rp. ".number_format($totalnominal,2,",",".");
$write->easyCell($nominalshow2,'align:R; border:LTBR; font-style:B;');
$write->printRow();
$write->endTable(0);

// fungsi terbilang =========================

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
	
// ==========================================
$write=new easyTable($pdf, '{190}', 'width:190; align:L; font-size:10;font-family:arial;');
$keteranganbayar2="# ".terbilang($totalnominal)." rupiah #";
$write->easyCell('Terbilang :');
$write->printRow();
$write->easyCell(strtoupper($keteranganbayar2), 'font-style:B;');
$write->printRow();
$write->endTable(0);

$write=new easyTable($pdf, '{190}', 'width:190; align:L; font-size:10;font-family:arial;');
$keteranganbayar="Biaya Uji Kompetensi di ".$namalsp." pada skema ".$skemakkni;
$write->easyCell('Untuk pembayaran :');
$write->printRow();
$write->easyCell($keteranganbayar, 'font-style:B;');
$write->printRow();
$write->endTable(5);

$tglcetakkota = trim($wil2['nm_wil']).", ".$tgl_cetak;
$write=new easyTable($pdf, '{95,95}', 'width:190; align:L; font-size:10;font-family:arial;');
$write->easyCell('');
$write->easyCell($tglcetakkota);
$write->printRow();
$write->easyCell('Direktur,');
$write->easyCell('Bendahara,');
$write->printRow();
$write->rowStyle('min-height:20');
$write->easyCell($lq['direktur'], 'valign:B; font-style:B;');
$write->easyCell('...................................................', 'valign:B; font-style:B;');
$write->printRow();
$write->endTable(0);

$pdf->AliasNbPages();

//output file pdf
$fileoutputnya="kwitansi-".$skemakkni."-".$as['nama']."-".$_GET['ida'].".pdf";
$pdf->Output($fileoutputnya,'I');
ob_end_flush();
?>