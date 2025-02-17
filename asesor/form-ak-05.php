<?php

ob_start();

include 'fpdf-easytable-master/fpdf.php';

include 'fpdf-easytable-master/exfpdf.php';

include 'fpdf-easytable-master/easyTable.php';

include "../config/koneksi.php";

include "../config/library.php";

include "../config/fungsi_indotgl.php";



/*$sqlasesi="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_GET[ida]'";

$asesi=$conn->query($sqlasesi);

$as=$asesi->fetch_assoc();*/

$sqljadwal = "SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";

$jadwal = $conn->query($sqljadwal);

$jdq = $jadwal->fetch_assoc();

$tgl_cetak = tgl_indo($jdq['tgl_asesmen']);



$sqltuk = "SELECT * FROM `tuk` WHERE `id`='$jdq[tempat_asesmen]'";

$tuk = $conn->query($sqltuk);

$tq = $tuk->fetch_assoc();

$sqllsp = "SELECT * FROM `lsp` ORDER BY `id` ASC LIMIT 1";

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

$noasr = 1;

$getasesor = $conn->query("SELECT * FROM `jadwal_asesor` WHERE `id_jadwal`='$_GET[idj]'");

while ($gas = $getasesor->fetch_assoc()) {

	$sqlasesor = "SELECT * FROM `asesor` WHERE `id`='$gas[id_asesor]'";

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

	$noregasesor = $asr['no_induk'];

	$namaasesor =$namaasesor;

	$noregasesor =$noregasesor;



	$noasr++;
}




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

$telpemail = "Telp./Fax.: " . $lq['telepon'] . " / " . $lq['fax'] . " Email : " . $lq['email'] . ", " . $lq['website'];

$tampilperiode = "Periode " . $jdq['periode'] . " Tahun " . $jdq['tahun'] . " Gelombang " . $jdq['gelombang'];

$nomorlisensi = "Nomor Lisensi : " . $lq['no_lisensi'];



$alamatlsptampil = $alamatlsp . " " . $alamatlsp2 . " " . $telpemail;

//$pdf->Cell(0, 5, '', '0', 1, 'C');

$pdf->Ln();

$write = new easyTable($pdf, '{30, 130, 30}', 'width:190; align:L; font-style:B; font-family:arial;');

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



$write = new easyTable($pdf, 1, 'width:190;  font-style:B; font-size:12;font-family:arial;');

$write->easyCell('FR.AK.05 - LAPORAN ASESMEN', 'align:L;');

$write->printRow();

$write->endTable(5);

$sqlgetjenistuk = "SELECT * FROM `tuk_jenis` WHERE `id`='$tq[jenis_tuk]'";

$getjenistuk = $conn->query($sqlgetjenistuk);

$jnstuk = $getjenistuk->fetch_assoc();


$write = new easyTable($pdf, '{50, 40, 5,20, 115}', 'width:190; align:L; font-family:arial; font-size:12');

$write->easyCell('Skema Sertifikasi (KKNI/Okupasi/Klaster)', 'align:L;rowspan:2; border:LTBR');
$write->easyCell('Judul', 'align:L; font-size:12; border:LTBR');
$write->easyCell(':', 'align:L; font-size:12; border:LTBR');
$write->easyCell($skemakkni, 'align:L; font-style:B; colspan:2; border:LTBR');
$write->printRow();
$write->easyCell('Nomor', 'align:L; font-size:12; border:LTBR');
$write->easyCell(':', 'align:L; font-size:12; border:LTBR');
$write->easyCell($sq['kode_skema'], 'align:L;font-style:B; colspan:2; border:LTBR');
$write->printRow();
$write->easyCell('TUK', 'align:L;colspan:2; border:LTBR');
$write->easyCell(':', 'align:L; border:LTBR');
$write->easyCell($jnstuk['jenis_tuk'], 'align:L;colspan:2; border:LTBR');
$write->printRow();
$write->easyCell('Nama Asesor', 'align:L;colspan:2; border:LTBR');
$write->easyCell(':', 'align:L; border:LTBR');
$write->easyCell($namaasesor, 'align:L; border:LTBR;colspan:2;');
$write->printRow();
$write->easyCell('Tanggal', 'align:L;colspan:2; border:LTBR');
$write->easyCell(':', 'align:L; border:LTBR');
$write->easyCell($tgl_cetak, 'align:L; border:LTBR;colspan:2;');
$write->printRow();



$write->endTable(5);



$write = new easyTable($pdf, '{50, 20, 5, 115}', 'width:190; align:L; font-style:B; font-family:arial; font-size:12');



$sqlgetunitkompetensi = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sq[id]'";

$getunitkompetensi = $conn->query($sqlgetunitkompetensi);

$jumunitnya0 = $getunitkompetensi->num_rows;

$jumunitnya = $jumunitnya0 * 2; //kode dan judul unit adalah 2 baris

$noku = 1;

//while unitkompetensi ==================================================================

// while ($unk=$getunitkompetensi->fetch_assoc()){

// 	if ($noku==1){

// 		$write->easyCell('Unit Kompetensi', 'align:L; rowspan:2; border:LTR');

// 		$write->easyCell('Kode Unit', 'align:L; font-size:10; border:LTBR');

// 		$write->easyCell(':', 'align:C; font-size:10; border:LTBR');

// 		$write->easyCell($unk['kode_unit'], 'align:L; font-size:10; border:LTBR');

// 		$write->printRow();

// 		$write->easyCell('Judul Unit', 'align:L; font-size:10; border:LTBR');

// 		$write->easyCell(':', 'align:C; font-size:10; border:LTBR');

// 		$write->easyCell($unk['judul'], 'align:L; font-size:10; border:LTBR');

// 		$write->printRow();

// 	}else{
// 		if ($noku==$jumunitnya0){
// 			$write->easyCell('', 'align:L; rowspan:2; border:LBR');
// 		}else{
// 			$write->easyCell('', 'align:L; rowspan:2; border:LR');
// 		}
// 		$write->easyCell('Kode Unit', 'align:L; font-size:10; border:LTBR');

// 		$write->easyCell(':', 'align:C; font-size:10; border:LTBR');

// 		$write->easyCell($unk['kode_unit'], 'align:L; font-size:10; border:LTBR');

// 		$write->printRow();

// 		$write->easyCell('Judul Unit', 'align:L; font-size:10; border:LTBR');

// 		$write->easyCell(':', 'align:C; font-size:10; border:LTBR');

// 		$write->easyCell($unk['judul'], 'align:L; font-size:10; border:LTBR');

// 		$write->printRow();

// 	}

// 	$noku++;

// }

//end while unitkompetensi =============================================

$write->endTable(5);



$write = new easyTable($pdf, '{10,75,15,15,75}', 'width:190; align:L; font-family:arial; font-size:12');

$write->easyCell('No.', 'align:C; border:LT; font-style:B; rowspan:2');

$write->easyCell('Nama Asesi', 'align:C; border:LTR; font-style:B; rowspan:2');

$write->easyCell('Rekomendasi', 'align:C; border:TR; font-style:B; colspan:2');

$write->easyCell('Keterangan**', 'align:C; valign:M; rowspan:2; font-style:B; border:TR;');

$write->printRow();

$write->easyCell('K', 'align:C; font-style:B; border:TR;');

$write->easyCell('BK', 'align:C; font-style:B; border:TR;');

$write->printRow();



$sqlgetasesi = "SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`='$jdq[id]' ORDER BY `id_asesi` ASC";

$getasesi = $conn->query($sqlgetasesi);

$noas = 1;

while ($gtas = $getasesi->fetch_assoc()) {

	$write->rowStyle('min-height:15');

	$write->easyCell($noas . '.', 'align:L; border:LTB; font-style:B;');

	$sqlasesi = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$gtas[id_asesi]'";

	$asesi = $conn->query($sqlasesi);

	$as = $asesi->fetch_assoc();

	$write->easyCell($as['nama'], 'align:L; border:LTBR; font-style:B;');

	$getcatatanbukti = "SELECT * FROM `asesi_apl02` WHERE `id_asesi`='$gtas[id_asesi]' AND `id_jadwal`='$jdq[id]'";

	$catatanbukti = $conn->query($getcatatanbukti);

	$cbkt = $catatanbukti->fetch_assoc();

	switch ($gtas['keputusan_asesor']) {

		default:

			$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; border:TBR;');

			$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; border:TBR;');

			$write->easyCell($gtas['umpan_balik'], 'align:L; valign:T; border:TBR;');

			$write->printRow();

			break;

		case "R":

			$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; border:TBR;');

			$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; border:TBR;');

			$write->easyCell($gtas['umpan_balik'], 'align:L; valign:T; border:TBR;');

			$write->printRow();

			break;

		case "NR":

			$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; border:TBR;');

			$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; border:TBR;');

			// mendapatkan data BK pada penilaian asesor

			$sqlgetdatabkasesi = "SELECT * FROM `asesi_apl02` WHERE `id_asesi`='$gtas[id_asesi]' AND `id_jadwal`='$jdq[id]' AND `keputusan`='BK'";

			$getdatabkasesi = $conn->query($sqlgetdatabkasesi);

			$jumbk = $getdatabkasesi->num_rows;

			$nobknya = 1;

			$databk = "";

			while ($getbk = $getdatabkasesi->fetch_assoc()) {

				$sqlgetelemen = "SELECT * FROM `elemen_kompetensi` WHERE `id`='$getbk[id_elemen]'";

				$getelemen = $conn->query($sqlgetelemen);

				$elemen = $getelemen->fetch_assoc();

				$ketbk = "(" . $nobknya . ") belum kompeten " . $elemen['elemen_kompetensi'];

				$databk = $databk . $ketbk . "; ";

				$nobknya++;
			}

			$keteranganbk = $gtas['umpan_balik'] . ": " . $databk;

			$write->easyCell($keteranganbk, 'align:L; valign:T; border:TBR;');

			$write->printRow();

			break;
	}



	$noas++;
}

$write->easyCell('** tuliskan Kode dan Judul Unit Kompetensi yang dinyatakan BK bila mengases satu skema', 'font-size:10; align:L; colspan:5');

$write->printRow();

$write->endTable(5);

//enc while unitkompetensi ============================================

// garis penutup tabel ============

/*$write=new easyTable($pdf, '{190}', 'width:190; align:L; font-family:arial; font-size:12');

$write->easyCell('', 'align:C; border:T;');

$write->printRow();

$write->endTable(5);*/

//=================================

$sqlgetak01b = "SELECT * FROM `asesmen_ak05` WHERE `id_asesor`='$asr[id]' AND `id_jadwal`='$_GET[idj]'";

$getak01b = $conn->query($sqlgetak01b);

$jjwb = $getak01b->fetch_assoc();

$jumjjwb = $getak01b->num_rows;



if ($jumjjwb > 0) {

	$write = new easyTable($pdf, '{90,100}', 'width:190; align:L font-family:arial; font-size:12');

	$write->rowStyle('min-height:15');

	$write->easyCell('Aspek Negatif dan Positif dalam Asesemen', 'align:L; valign:M; font-size:12; border:LTR;');

	$write->easyCell($jjwb['aspek'], 'align:L; valign:M; font-size:12; border:LTR;');

	$write->printRow();

	$write->rowStyle('min-height:15');

	$write->easyCell('Pencatatan Penolakan Hasil Asesmen', 'align:L; valign:M; font-size:12; border:LTR;');

	$write->easyCell($jjwb['catatan_penolakan'], 'align:L; valign:M; font-size:12; border:LTR;');

	$write->printRow();

	$write->rowStyle('min-height:15');

	$write->easyCell('Saran Perbaikan :

	(Asesor/Personil Terkait)', 'align:L; valign:M; font-size:12; border:LTBR;');

	$write->easyCell($jjwb['saran_perbaikan'], 'align:L; valign:M; font-size:12; border:LTBR;');

	$write->printRow();

	$write->endTable(5);
} else {

	$write = new easyTable($pdf, '{90,100}', 'width:190; align:L font-family:arial; font-size:12');

	$write->rowStyle('min-height:15');

	$write->easyCell('Aspek Negatif dan Positif dalam Asesemen', 'align:L; valign:M; font-size:12; border:LTR;');

	$write->easyCell('', 'align:L; valign:M; font-size:12; border:LTR;');

	$write->printRow();

	$write->rowStyle('min-height:15');

	$write->easyCell('Pencatatan Penolakan Hasil Asesmen', 'align:L; valign:M; font-size:12; border:LTR;');

	$write->easyCell('', 'align:L; valign:M; font-size:12; border:LTR;');

	$write->printRow();

	$write->rowStyle('min-height:15');

	$write->easyCell('Saran Perbaikan :

	(Asesor/Personil Terkait)', 'align:L; valign:M; font-size:12; border:LTBR;');

	$write->easyCell('', 'align:L; valign:M; font-size:12; border:LTBR;');

	$write->printRow();

	$write->endTable(5);
}



$write = new easyTable($pdf, '{100,30,60}', 'width:190; align:L font-family:arial; font-size:12');

$write->easyCell('Catatan:

 ' . $jjwb['catatan'], 'align:L; valign:T; border:LTR; font-size:12; font-style:B; rowspan:4');

$write->easyCell('Asesor:', 'align:L; font-size:12; font-style:B; border:LTBR; colspan:2');

$write->printRow();

$write->easyCell('Nama', 'align:L; font-size:12; border:LTBR;');

$write->easyCell($namaasesor, 'align:L; font-size:12; border:LTBR;');

$write->printRow();

$write->easyCell('No. Reg', 'align:L; font-size:12; border:LTBR;');

$write->easyCell($noregasesor, 'align:L; font-size:12; border:LTBR;');

$write->printRow();

$write->easyCell('Tanda Tangan/

Tanggal', 'align:L; font-size:12; border:LTR;');

// tandatangan asesor

$sqlidentitas = "SELECT * FROM `identitas`";

$identitas = $conn->query($sqlidentitas);

$iden = $identitas->fetch_assoc();

$urltandatanganas = $iden['url_domain'] . "/asesor/media.php?module=form-fr-ak-05&amp;idj=" . $jdq['id'];

// $sqlcekttdasesiapl01as = "SELECT * FROM `logdigisign` WHERE `nama_dokumen`='FR.AK.05. LAPORAN ASESMEN' AND `penandatangan`='$asr[nama]' AND `url_ditandatangani`='$urltandatanganas' ORDER BY `id` DESC";
$sqlcekttdasesiapl01as = "SELECT * FROM `logdigisign` WHERE `nama_dokumen`='FR.AK.05. LAPORAN ASESMEN' AND `penandatangan`='$asr[nama]' AND `id_jadwal`='$_GET[idj]' ORDER BY `id` DESC";

$cekttdasesiapl01as = $conn->query($sqlcekttdasesiapl01as);

$jumttdasesias = $cekttdasesiapl01as->num_rows;

$ttdasas = $cekttdasesiapl01as->fetch_assoc();

if ($jumttdasesias > 0) {

	$write->easyCell('', 'img:' . $ttdasas['file'] . ', h20; align:C; valign:T; font-size:10; font-style:B; border:R;');
} else {

	$write->easyCell('', 'align:L; valign:T; font-size:10; font-style:B; border:R;');
}

$write->printRow();

$tglttdnya = tgl_indo($jdq['tgl_asesmen_akhir']);

$write->easyCell('', 'align:L; font-size:12; border:LBR;');

$write->easyCell('', 'align:L; font-size:12; border:LBR;');

$write->easyCell($tglttdnya, 'align:C; font-size:12; border:LBR;');

$write->printRow();

$write->endTable(5);

//memanggil library QR Code

require_once("../phpqrcode/qrlib.php");



//$qrcodetext="http://".$iden['url_domain']."/signed.php?id=$ttdas[id]";

$qrcodetext2 = "http://" . $iden['url_domain'] . "/signed.php?id=$ttdasas[id]";



//create a QR Code and save it as a png image file named generateqr.png

//QRcode::png($qrcodetext,"../foto_tandatangan/generateqr.png");

QRcode::png($qrcodetext2, "../foto_tandatangan/generateqr2.png");



//this is the second method

$write = new easyTable($pdf, '{95,95}', 'width:190; align:L; font-family:arial; font-size:12');

//if (!empty($ttdas['id'])){

//	$write->easyCell('', 'img:../foto_tandatangan/generateqr.png, h20; align:L; valign:T; font-size:10; font-style:B;');

//}else{

$write->easyCell('', 'align:L; valign:T; font-size:10; font-style:B;');

//}

if (!empty($ttdasas['id'])) {

	$write->easyCell('', 'img:../foto_tandatangan/generateqr2.png, h20; align:R; valign:T; font-size:10; font-style:B;');
} else {

	$write->easyCell('', 'align:R; valign:T; font-size:10; font-style:B;');
}

$write->printRow();

$write->endTable(0);



$pdf->AliasNbPages();



//output file pdf

$fileoutputnya = "FR-AK-05-" . $skemakkni . "-" . $_GET['idj'] . ".pdf";

$pdf->Output($fileoutputnya, 'I');



ob_end_flush();
