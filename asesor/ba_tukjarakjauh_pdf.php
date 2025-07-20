<?php
ob_start();
include 'fpdf-easytable-master/fpdf.php';
include 'fpdf-easytable-master/exfpdf.php';
include 'fpdf-easytable-master/easyTable.php';
include "../config/koneksi.php";
include "../config/library.php";
include "../config/fungsi_indotgl.php";

$sqlasesi = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_GET[ida]'";
$asesi = $conn->query($sqlasesi);
$as01 = $asesi->fetch_assoc();
$sqlasesi = "SELECT * FROM `asesi_tukjarakjauh` WHERE `jadwal_id`='$_GET[idj]' AND asesi_id='$_GET[ida]'";
$asesi = $conn->query($sqlasesi);
$as = $asesi->fetch_assoc();
$sqljadwal = "SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
$jadwal = $conn->query($sqljadwal);
$jdq = $jadwal->fetch_assoc();
$tgl_cetak = tgl_indo($jdq['tgl_asesmen']);

$sqltuk = "SELECT * FROM `tuk` WHERE `id`='$jdq[tempat_asesmen]'";
$tuk = $conn->query($sqltuk);
$tq = $tuk->fetch_assoc();
$sqllsp = "SELECT * FROM `lsp` WHERE `id`='$tq[lsp_induk]'";
$lsp = $conn->query($sqllsp);
$lq = $lsp->fetch_assoc();
$sqlskema = "SELECT * FROM `skema_kkni` WHERE `id`='$jdq[id_skemakkni]'";
$skema = $conn->query($sqlskema);
$sq = $skema->fetch_assoc();
$skemakkni = $sq['judul'];

$sqlwil1 = "SELECT * FROM `data_wilayah` WHERE `id_wil`='$lq[id_wilayah]'";
$wilayah1 = $conn->query($sqlwil1);
$wil1 = $wilayah1->fetch_assoc();
$sqlwil2 = "SELECT * FROM `data_wilayah` WHERE `id_wil`='$wil1[id_induk_wilayah]'";
$wilayah2 = $conn->query($sqlwil2);
$wil2 = $wilayah2->fetch_assoc();
$sqlwil3 = "SELECT * FROM `data_wilayah` WHERE `id_wil`='$wil2[id_induk_wilayah]'";
$wilayah3 = $conn->query($sqlwil3);
$wil3 = $wilayah3->fetch_assoc();

$sqlwil1b = "SELECT * FROM `data_wilayah` WHERE `id_wil`='$tq[id_wilayah]'";
$wilayah1b = $conn->query($sqlwil1b);
$wil1b = $wilayah1b->fetch_assoc();
$sqlwil2b = "SELECT * FROM `data_wilayah` WHERE `id_wil`='$wil1b[id_induk_wilayah]'";
$wilayah2b = $conn->query($sqlwil2b);
$wil2b = $wilayah2b->fetch_assoc();
$sqlwil3b = "SELECT * FROM `data_wilayah` WHERE `id_wil`='$wil2b[id_induk_wilayah]'";
$wilayah3b = $conn->query($sqlwil3b);
$wil3b = $wilayah3b->fetch_assoc();



$pdf = new exFPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);
$pdf->AddFont('FontUTF8', '', 'Arimo-Regular.php');
$pdf->AddFont('FontUTF8', 'B', 'Arimo-Bold.php');
$pdf->AddFont('FontUTF8', 'I', 'Arimo-Italic.php');
$pdf->AddFont('FontUTF8', 'BI', 'Arimo-BoldItalic.php');

// kop LSP ======================================================

//tampilan Form
$id_wilayah = trim($wil1['nm_wil']);
$id_wilayah2 = trim($wil2['nm_wil']) . ", " . trim($wil3['nm_wil']);
$namalsp = strtoupper($lq['nama']);
$alamatlsp = $lq['alamat'] . " " . $lq['kelurahan'] . " " . $id_wilayah;
$alamatlsp2 = $id_wilayah2 . " Kodepos : " . $lq['kodepos'];
if ($lq['fax'] == "" or $lq['fax'] == "-") {
	$telpemail = "Telp.: " . $lq['telepon'] . " Email : " . $lq['email'] . ", " . $lq['website'];
} else {
	$telpemail = "Telp./Fax.: " . $lq['telepon'] . " / " . $lq['fax'] . " Email : " . $lq['email'] . ", " . $lq['website'];
}
$tampilperiode = "Periode " . $jdq['periode'] . " Tahun " . $jdq['tahun'] . " Gelombang " . $jdq['gelombang'];
$nomorlisensi = "Nomor Lisensi : " . $lq['no_lisensi'];

$alamatlsptampil = $alamatlsp . " " . $alamatlsp2;
//$pdf->Cell(0, 5, '', '0', 1, 'C');
$pdf->Ln();
$write = new easyTable($pdf, '{30, 130, 30}', 'width:190; align:L; font-style:B; font-family:arial;');
$write->easyCell('', 'img:../images/logolsp.jpg, w25, h25; align:C; rowspan:3');
$write->easyCell($namalsp, 'align:C; font-size:14;');
$write->easyCell('', 'img:../images/logo-bnsp.jpg, w25, h25;align:C; rowspan:3');
$write->printRow();
$write->easyCell($nomorlisensi, 'align:C; font-size:10;');
$write->printRow();
$write->easyCell($alamatlsptampil . '
' . $telpemail, 'align:C; font-size:8;');
$write->printRow();
$write->endTable(5);


//===============================================================

$write = new easyTable($pdf, 1, 'width:190;  font-size:12;font-family:arial;');
$write->easyCell('CEKLIST VERIFIKASI TEMPAT UJI KOMPETENSI (TUK) JARAK JAUH', 'font-style:B,U; align:C;');
$write->printRow();
$write->endTable(5);
$namakota = trim($wil2b['nm_wil']);
$tglcetakkota = ucwords(strtolower($namakota)) . ", " . $tgl_cetak;
$tanggal = $as['tgl_verifikasi'];
$tgl_verifikasi = tgl_indo($as['tgl_verifikasi']);
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
$tglpelaksanaan = $dayList[$day] . "/ " . $tgl_verifikasi;


$write = new easyTable($pdf, '{70, 5, 115}', 'width:190; align:L; font-size:10;font-family:arial;');
$write->easyCell('Nama TUK');
$write->easyCell(':');
$write->easyCell($tq['nama']);
$write->printRow();
$write->easyCell('Hari/Tanggal');
$write->easyCell(':');
$write->easyCell($tglpelaksanaan);
$write->printRow();
$write->easyCell('Metode Asesmen');
$write->easyCell(':');
$write->easyCell('Observasi/Demonstrasi/Praktek/Tes Tulis/Wawancara');
$write->printRow();
$write->easyCell('Skema');
$write->easyCell(':');
$write->easyCell($skemakkni);
$write->printRow();
$write->endTable(0);

$write = new easyTable($pdf, '{10, 40, 30, 50, 50}', 'width:190; align:L; font-size:10;font-family:arial;');
$write->easyCell('No.', 'align:C; rowspan:2; bgcolor:#C6C6C6; font-style:B; border:LTB');
$write->easyCell('Perlengkapan', 'align:C; rowspan:2; bgcolor:#C6C6C6; font-style:B; border:LTB');
// $write->easyCell('Spesifikasi', 'align:C; rowspan:2; bgcolor:#C6C6C6; font-style:B; border:LTB');
// $write->easyCell('Jumlah', 'align:C; rowspan:2; bgcolor:#C6C6C6; font-style:B; border:LTB');
$write->easyCell('Kondisi', 'align:C; colspan:2; bgcolor:#C6C6C6; font-style:B; border:LTB');
$write->easyCell('Catatan', 'align:C; rowspan:2; bgcolor:#C6C6C6; font-style:B; border:LTB');
$write->printRow();
$write->easyCell('Sesuai', 'align:C; bgcolor:#C6C6C6; font-style:B; border:LTB');
$write->easyCell('Tidak Sesuai', 'align:C; bgcolor:#C6C6C6; font-style:B; border:LTB');
$write->printRow();

$sqlgetpersyaratantuk = "SELECT * FROM `persyaratan_tukjarakjauh` ORDER BY `id` ASC";
$getpersyaratantuk = $conn->query($sqlgetpersyaratantuk);
$nostuk = 1;
while ($stuk = $getpersyaratantuk->fetch_assoc()) {
	$write->easyCell($nostuk . '.', 'align:R; border:LTB');
	$write->easyCell($stuk['persyaratan'], 'align:L; border:LTB');
	// remove html character =============================
	// $string1 = $stuk['spesifikasi'];
	// $string1 = str_replace("<p>", "", $string1);
	// $string1 = str_replace("<ol>", "", $string1);
	// $string1 = str_replace("<li>", "", $string1);
	// $string1 = str_replace("<ul>", "", $string1);
	// $string1 = str_replace("</p>", "", $string1);
	// $string1 = str_replace("</ol>", "", $string1);
	// $string1 = str_replace("</li>", "", $string1);
	// $string1 = str_replace("</ul>", "", $string1);
	// $string1 = str_replace("&nbsp", " ", $string1);
	// $string1 = str_replace("&", "", $string1);
	// $string1 = str_replace(">", "", $string1);
	// $string1 = str_replace("<", "", $string1);
	// $string1 = str_replace("&agrave;", "", $string1);
	// $string1 = str_replace("&euml;", "", $string1);
	// $string1 = str_replace("\"", "", $string1);
	// $string1 = str_replace("&lt;br /&gt;", "", $string1);
	// $string1 = str_replace("&eacute;", "", $string1);
	// $string1 = str_replace("�", "", $string1);
	//==================================================
	// $write->easyCell($string1, 'align:L; border:LTB');
	$sqlcekceklist = "SELECT * FROM `tukjarakjauh_doc` WHERE `asesi_id`='$as[asesi_id]' AND `skema_id`='$as[skema_id]' AND `syarattuk_id`='$stuk[id]'";
	$cekceklist = $conn->query($sqlcekceklist);
	$clx = $cekceklist->fetch_assoc();
	if ($clx['status'] == 'A') {
		$write->easyCell('YA', 'align:C; border:LTB');
		$write->easyCell('', 'align:C; border:LTB');
	} elseif ($clx['status'] == 'R') {
		$write->easyCell('', 'align:C; border:LTB');
		$write->easyCell('YA', 'align:C; border:LTB');
	} else {
		$write->easyCell('', 'align:C; border:LTB');
		$write->easyCell('', 'align:C; border:LTB');
	}
	$write->easyCell($clx['catatan_asesor'], 'align:L; border:LTBR');
	$write->printRow();
	$nostuk++;
}
$write->endTable(0);

$write = new easyTable($pdf, '{90,90}', 'width:180; align:C; font-size:10;font-family:arial;');
$sqlasesitukx = "SELECT * FROM `asesor_verifikatortuk` WHERE `id_jadwal`='$_GET[idj]' AND `keputusanverifikasi`!='P' ORDER BY `waktu` DESC";
$asesitukx = $conn->query($sqlasesitukx);
$astukx = $asesitukx->fetch_assoc();
switch ($astukx['keputusanverifikasi']) {
	case "Y":
		$keputusan = "Sesuai persyaratan teknis Tempat Uji Kompetensi (TUK)";
		break;
	case "N":
		$keputusan = "Tidak Sesuai persyaratan teknis Tempat Uji Kompetensi (TUK)";
		break;
	default:
		$keputusan = "Sesuai/Tidak Sesuai persyaratan teknis Tempat Uji Kompetensi (TUK)";
		break;
}
$write->easyCell('Keputusan Verifikasi : ' . $keputusan, 'align:L; valign:T; font-style:B; colspan:2;');
$write->printRow();
$write->easyCell('');
$write->easyCell($tglcetakkota, 'align:L; valign:T;');
$write->printRow();
$write->endTable(0);

$write = new easyTable($pdf, '{5,85,5,85}', 'width:180; align:C; font-size:10;font-family:arial;');
$write->easyCell('Tim Verifikator', 'align:L; valign:T; colspan:2');
$write->easyCell('Tanda Tangan', 'align:L; valign:T; colspan:2');
$write->printRow();
// // tandatangan asesi
// $sqlidentitas = "SELECT * FROM `identitas`";
// $identitas = $conn->query($sqlidentitas);
// $iden = $identitas->fetch_assoc();
// $urltandatangan = $iden['url_domain'] . "/media.php?module=form-ia-03&amp;ida=" . $as01['no_pendaftaran'] . "&amp;idj=" . $jdq['id'];
// $sqlcekttdasesiapl01 = "SELECT * FROM `logdigisign` WHERE id_asesi='$_GET[ida]' AND id_skema='$sq[id]' AND `nama_dokumen`='FR.IA.03. PERTANYAAN UNTUK MENDUKUNG OBSERVASI' AND `penandatangan`='$as01[nama]' ORDER BY `id` DESC";
// $cekttdasesiapl01 = $conn->query($sqlcekttdasesiapl01);
// $jumttdasesi = $cekttdasesiapl01->num_rows;
// $ttdas = $cekttdasesiapl01->fetch_assoc();
// if ($jumttdasesi > 0) {
// 	$write->easyCell('', 'img:../' . $ttdas['file'] . ', h20; align:C; valign:T; font-size:10; font-style:B; border:BR;');
// } else {
// 	$write->easyCell('', 'align:L; valign:T; font-size:10; font-style:B; border:BR;');
// }
$sqlasesituk = "SELECT * FROM `asesor_verifikatortuk` WHERE `id_jadwal`='$_GET[idj]'";
$asesituk = $conn->query($sqlasesituk);
$noastuk = 1;
while ($astuk = $asesituk->fetch_assoc()) {
	$sqlasesor = "SELECT * FROM `asesor` WHERE `id`='$astuk[id_asesor]'";
	$asesor = $conn->query($sqlasesor);
	$asr = $asesor->fetch_assoc();
	if (!empty($asr['gelar_depan'])) {
		if (!empty($asr['gelar_blk'])) {
			$namaasesor = $asr['gelar_depan'] . " " . $asr['nama'] . ", " . $asr['gelar_blk'];
		} else {
			$namaasesor = $asr['gelar_depan'] . " " . $asr['nama'];
		}
	} else {
		if (!empty($asr['gelar_blk'])) {
			$namaasesor = $asr['nama'] . ", " . $asr['gelar_blk'];
		} else {
			$namaasesor = $asr['nama'];
		}
	}
	// tandatangan asesor
	$sqlidentitas = "SELECT * FROM `identitas`";
	$identitas = $conn->query($sqlidentitas);
	$iden = $identitas->fetch_assoc();
	$urltandatanganas = $iden['url_domain'] . "/asesor/media.php?module=inputceklist&amp;idj=" . $jdq['id'];
	$sqlcekttdasesiapl01as = "SELECT * FROM `logdigisign` WHERE `nama_dokumen`='FR-TUK.FORMULIR PERMOHONAN TUK JARAK JAUH' AND `penandatangan`='$asr[nama]' ORDER BY `id` DESC";
	$cekttdasesiapl01as = $conn->query($sqlcekttdasesiapl01as);
	$jumttdasesias = $cekttdasesiapl01as->num_rows;
	$ttdasas = $cekttdasesiapl01as->fetch_assoc();
	if ($jumttdasesias > 0) {
		$write->rowStyle('min-height:20');
		$write->easyCell($noastuk . '.', 'align:L; valign:B;');
		$write->easyCell($namaasesor, 'align:L; valign:B;');
		$write->easyCell($noastuk . '.', 'align:L; valign:B;');
		// $write->easyCell($namaasesor . '.', 'align:L; valign:B;');
		$write->easyCell('', 'img:' . $ttdasas['file'] . ', h20; align:C; valign:T; font-size:10; font-style:B;');
		$write->printRow();
		$noastuk++;
	} else {
		$write->rowStyle('min-height:15');
		$write->easyCell($noastuk . '.', 'align:L; valign:B;');
		$write->easyCell($namaasesor, 'align:L; valign:B;');
		$write->easyCell($noastuk . '.', 'align:L; valign:B;');
		$write->easyCell('.....................................................', 'align:L; valign:B;');
		$write->printRow();
		$noastuk++;
	}
}
$write->endTable(5);
$pdf->AliasNbPages();

//output file pdf
$fileoutputnya = "Checklist-Verifikasi-TUK-oleh-asesor-" . $skemakkni . "-" . $_GET['idj'] . ".pdf";
$pdf->Output($fileoutputnya, 'I');

ob_end_flush();
