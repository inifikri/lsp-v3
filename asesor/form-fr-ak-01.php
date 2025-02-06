<?php
ob_start();
include 'fpdf-easytable-master/fpdf.php';
include 'fpdf-easytable-master/exfpdf.php';
include 'fpdf-easytable-master/easyTable.php';
include "../config/koneksi.php";
include "../config/library.php";
include "../config/fungsi_indotgl.php";

// start a session 
session_start();

// UPDATE @FHM-PUSTI 7 AGUSTUS 2023 : Get data asesor dari tabel jadwal asesor
$getjadwalasesor=$conn->query("SELECT * FROM jadwal_asesor WHERE id_jadwal='$_GET[idj]'")->fetch_assoc();
$getdataasesor=$conn->query("SELECT * FROM asesor WHERE id=$getjadwalasesor[id_asesor]")->fetch_assoc();

$sqlasesor = "SELECT * FROM `asesor` WHERE `no_ktp`='$_SESSION[namauser]'";
$asesor = $conn->query($sqlasesor);
$asr = $asesor->fetch_assoc();

$sqlasesi="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_GET[ida]'";
$asesi=$conn->query($sqlasesi);
$as=$asesi->fetch_assoc();
$namaasesi=ucwords(strtolower($as['nama']));

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
if ($lq['fax']=="" OR $lq['fax']=="-"){
	$telpemail="Telp.: ".$lq['telepon']." Email : ".$lq['email'].", ".$lq['website'];
}else{
	$telpemail="Telp./Fax.: ".$lq['telepon']." / ".$lq['fax']." Email : ".$lq['email'].", ".$lq['website'];
}
$tampilperiode="Periode ".$jdq['periode']." Tahun ".$jdq['tahun']." Gelombang ".$jdq['gelombang'];
$nomorlisensi="Nomor Lisensi : ".$lq['no_lisensi'];

$alamatlsptampil=$alamatlsp." ".$alamatlsp2;
//$pdf->Cell(0, 5, '', '0', 1, 'C');
$pdf->Ln();
$write=new easyTable($pdf, '{30, 130, 30}', 'width:190; align:L; font-style:B; font-family:arial;');
$write->easyCell('', 'img:../images/logolsp.jpg, w25, h25; align:C; rowspan:3');
$write->easyCell($namalsp, 'align:C; font-size:14;');
$write->easyCell('', 'img:../images/logo-bnsp.jpg, w25, h25;align:C; rowspan:3');
$write->printRow();
$write->easyCell($nomorlisensi, 'align:C; font-size:10;');
$write->printRow();
$write->easyCell($alamatlsptampil.'
'.$telpemail, 'align:C; font-size:8;');
$write->printRow();
$write->endTable(5);


//===============================================================
$sqlgetak01="SELECT * FROM `asesmen_ak01` WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$jdq[id_skemakkni]' AND `id_jadwal`='$_GET[idj]'";
$getak01=$conn->query($sqlgetak01);
$jjw=$getak01->fetch_assoc();


$write=new easyTable($pdf, 1, 'width:180; align:C; font-style:B; font-size:12;font-family:arial;');
$write->easyCell('FR.AK.01. FORMULIR PERSETUJUAN ASESMEN DAN KERAHASIAAN', 'align:L;');
$write->printRow();
$write->endTable(5);

$write=new easyTable($pdf, '{60, 30, 90}', 'width:180; align:C; font-family:arial; font-size:10');
$write->easyCell('Persetujuan Asesmen ini untuk menjamin bahwa Asesi telah diberi arahan secara rinci tentang perencanaan dan proses asesmen', 'align:L; valign:M; font-size:11; colspan:3; border:LTBR;');
$write->printRow();
$write->easyCell('Skema Sertifikasi
(KKNI/Okupasi/Klaster)', 'align:L; valign:M; font-size:11; rowspan:2; border:LTR;');
$write->easyCell('Judul :', 'align:L; valign:M; font-size:11; border:LTBR;');
$write->easyCell($skemakkni, 'align:L; valign:M; font-size:11; font-style:B; border:LTBR;');
$write->printRow();
$write->easyCell('Nomor :', 'align:L; valign:M; font-size:11; border:LTBR;');
$write->easyCell($sq['kode_skema'], 'align:L; valign:M; font-size:11; font-style:B; border:LTBR;');
$write->printRow();
$write->easyCell('TUK', 'align:L; valign:M; font-size:11; border:LTBR;');
$sqltukjenis="SELECT `jenis_tuk` FROM `tuk_jenis` WHERE `id`='$tq[jenis_tuk]'";
$tukjenis=$conn->query($sqltukjenis);
$tukjen=$tukjenis->fetch_assoc();
$write->easyCell($tukjen['jenis_tuk'], 'align:L; valign:M; font-size:11; border:LTBR; colspan:2');
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


$write->easyCell('Nama Asesor', 'align:L; valign:M; font-size:11; border:LTBR;');
$write->easyCell($namaasesor, 'align:L; valign:M; font-size:11; font-style:B; border:LTBR; colspan:2');
$write->printRow();
$write->easyCell('Nama Asesi', 'align:L; valign:M; font-size:11; border:LTBR;');
$write->easyCell($namaasesi, 'align:L; valign:M; font-size:11; font-style:B; border:LTBR; colspan:2');
$write->printRow();
$write->endTable(0);
$write=new easyTable($pdf, '{60, 10, 50, 10, 50}', 'width:180; align:C; font-family:arial; font-size:10');
$write->easyCell('Bukti yang akan dikumpulkan', 'align:L; valign:M; font-size:11; border:LTBR; rowspan:4');
if ($jjw['VP']=='1'){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; border:LTB;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; border:LTB;');

}
$write->easyCell('TL : Verifikasi Portfolio', 'align:L; valign:M; font-size:11; border:TBR;');
if ($jjw['CL']=='1'){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; border:LTB;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; border:LTB;');
}
$write->easyCell('L : Observasi Langsung', 'align:L; valign:M; font-size:11; border:TBR;');
$write->printRow();
if ($jjw['DPT']=='1'){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; border:LT;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; border:LT;');
}
$write->easyCell('T : Hasil Tes Tulis', 'align:L; valign:M; font-size:11; border:TR; colspan:3;');
$write->printRow();
if ($jjw['DPL']=='1'){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; border:L;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; border:L;');
}
$write->easyCell('L : Hasil Tes Lisan', 'align:L; valign:M; font-size:11; border:R; colspan:3;');
$write->printRow();
if ($jjw['PW']=='1'){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; border:L;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; border:L;');
}
$write->easyCell('L : Hasil Wawancara', 'align:L; valign:M; font-size:11; border:BR; colspan:3;');
$write->printRow();
$write->endTable(0);
$write=new easyTable($pdf, '{60, 40, 5, 75}', 'width:180; align:C; font-family:arial; font-size:10');
$write->easyCell('Pelaksanaan asesmen disepakati pada:', 'align:L; valign:M; font-size:11; border:LTBR; rowspan:3');
$write->easyCell('Hari/ Tanggal', 'align:L; valign:M; font-size:11; border:LTB;');
$write->easyCell(':', 'align:L; valign:M; font-size:11; border:TB; ');

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

$write->easyCell($jadwal, 'align:L; valign:M; font-size:11; font-style:B; border:TBR; ');
$write->printRow();
$write->easyCell('Waktu', 'align:L; valign:M; font-size:11; border:LTB;');
$write->easyCell(':', 'align:L; valign:M; font-size:11; border:TB; ');
$write->easyCell($jdq['jam_asesmen'], 'align:L; valign:M; font-style:B; font-size:11; border:TBR; ');
$write->printRow();
$write->easyCell('TUK', 'align:L; valign:M; font-size:11; border:LTB;');
$write->easyCell(':', 'align:L; valign:M; font-size:11; border:TB; ');
$write->easyCell($tq['nama'], 'align:L; valign:M; font-style:B;font-size:11; border:TBR; ');
$write->printRow();
$write->endTable(0);



$write=new easyTable($pdf, '{180}', 'width:180; align:C; font-family:arial; font-size:10');
$write->easyCell('Asesor :', 'align:L;  valign:M; font-style:B; font-size:11; border:LTR;');
$write->printRow();
$write->easyCell('Menyatakan tidak akan membuka hasil pekerjaan yang saya peroleh karena penugasan saya sebagai Asesor dalam pekerjaan Asesmen kepada siapapun atau organisasi apapun selain kepada pihak yang berwenang sehubungan dengan kewajiban saya sebagai Asesor yang ditugaskan oleh LSP.', 'align:L;  valign:M; font-style:I; font-size:11; border:LRB;');
$write->printRow();
$write->easyCell('Asesi :', 'align:L;  valign:M; font-style:B; font-size:11; border:LTR;');
$write->printRow();
$write->easyCell('Saya setuju mengikuti asesmen dengan pemahaman bahwa informasi yang dikumpulkan hanya digunakan untuk pengembangan profesional dan hanya dapat diakses oleh orang tertentu saja.', 'align:L;  valign:M; font-style:I; font-size:11; border:LRB;');
$write->printRow();
$write->endTable(0);
$write=new easyTable($pdf, '{40,5,70,20,45}', 'width:180; align:C; font-family:arial; font-size:10');

$sqlidentitas="SELECT * FROM `identitas`";
$identitas=$conn->query($sqlidentitas);
$iden=$identitas->fetch_assoc();
// tandatangan Asesor LSP
$urltandatanganadmin=$iden['url_domain']."/asesor/media.php?module=form-fr-ak-01&ida=".$_GET['ida']."&idj=".$_GET['idj'];
$sqlcekttdadminapl01="SELECT * FROM `logdigisign` WHERE id_asesi='$getdataasesor[no_ktp]' AND id_skema='$jdq[id_skemakkni]' AND `nama_dokumen`='FR.AK.01.FORMULIR PERSETUJUAN ASESMEN DAN KERAHASIAAN' AND `penandatangan`='$asr[nama]' ORDER BY `id` DESC";
$cekttdadminapl01=$conn->query($sqlcekttdadminapl01);
$jumttdadmin=$cekttdadminapl01->num_rows;
$ttdad=$cekttdadminapl01->fetch_assoc();
$tglttdasesor=tgl_indo($jjw['tanggal']);
if ($jumttdadmin>0){
	$write->easyCell('', 'align:L; valign:B; font-size:11; border:LT;');
	$write->easyCell('', 'align:L; valign:B; font-size:11; border:T;');
	$write->easyCell('', 'align:L; valign:B; font-size:11; border:T;');
	$write->easyCell('', 'align:L; valign:B; font-size:11; border:T;');
	$write->easyCell('', 'img:'.$ttdad['file'].', h20; align:C; valign:T; font-size:10; font-style:B; border:R;');
	$write->printRow();
	$write->easyCell('Tandatangan Asesor', 'align:L; valign:B; font-size:11; border:L;');
	$write->easyCell(':', 'align:L; valign:B; font-size:11;');
	$write->easyCell($namaasesor, 'align:L; valign:B; font-size:11;');
	$write->easyCell('Tanggal :', 'align:L; valign:B; font-size:11;');
	$write->easyCell($tglttdasesor, 'align:L; valign:B; font-size:11; border:R;');
	$write->printRow();
}else{
	$write->rowStyle('min-height:25');
	$write->easyCell('Tandatangan Asesor', 'align:L; valign:B; font-size:11; border:LT;');
	$write->easyCell(':', 'align:L; valign:B; font-size:11; border:T;');
	$write->easyCell($namaasesor, 'align:L; valign:B; font-size:11; border:T;');
	$write->easyCell('Tanggal :', 'align:L; valign:B; font-size:11; border:T;');
	$write->easyCell('.......................................', 'align:L; valign:B; font-size:11; border:TR;');
	$write->printRow();
}
// tandatangan Asesi
$urltandatangan=$iden['url_domain']."/media.php?module=form-fr-ak-01&ida=".$_GET['ida']."&idj=".$_GET['idj'];
$sqlcekttdasesiapl01="SELECT * FROM `logdigisign` WHERE id_asesi='$_GET[ida]' AND id_skema='$jdq[id_skemakkni]' AND `nama_dokumen`='FR.AK.01.FORMULIR PERSETUJUAN ASESMEN DAN KERAHASIAAN' AND `penandatangan`='$namaasesi' ORDER BY `id` DESC";
$cekttdasesiapl01=$conn->query($sqlcekttdasesiapl01);
$jumttdasesi=$cekttdasesiapl01->num_rows;
$ttdas=$cekttdasesiapl01->fetch_assoc();
$tglttdasesi=tgl_indo($jjw['tanggal_asesittd']);
if ($jumttdasesi>0){
	$write->easyCell('', 'align:L; valign:B; font-size:11; border:L;');
	$write->easyCell('', 'align:L; valign:B; font-size:11;');
	$write->easyCell('', 'align:L; valign:B; font-size:11;');
	$write->easyCell('', 'align:L; valign:B; font-size:11;');
	$write->easyCell('', 'img:../'.$ttdas['file'].', h20; align:C; valign:T; font-size:10; font-style:B; border:R;');
	$write->printRow();
	$write->easyCell('Tandatangan Asesi', 'align:L; valign:B; font-size:11; border:LB;');
	$write->easyCell(':', 'align:L; valign:B; font-size:11; border:B;');
	$write->easyCell($namaasesi, 'align:L; valign:B; font-size:11; border:B;');
	$write->easyCell('Tanggal :', 'align:L; valign:B; font-size:11; border:B;');
	$write->easyCell($tglttdasesi, 'align:L; valign:B; font-size:11; border:BR;');
	$write->printRow();
}else{
	$write->rowStyle('min-height:25');
	$write->easyCell('Tandatangan Asesi', 'align:L; valign:B; font-size:11; border:LB;');
	$write->easyCell(':', 'align:L; valign:B; font-size:11; border:B;');
	$write->easyCell($namaasesi, 'align:L; valign:B; font-size:11; border:B;');
	$write->easyCell('Tanggal :', 'align:L; valign:B; font-size:11; border:B;');
	$write->easyCell('.......................................', 'align:L; valign:B; font-size:11; border:BR;');
	$write->printRow();
}

$write->endTable(5);
//memanggil library QR Code
require_once("../phpqrcode/qrlib.php");
$ttdasid =isset($ttdas['id'])?$ttdas['id']:'';
$ttdadid = isset($ttdad['id'])?$ttdad['id']:'';
$qrcodetext="http://".$iden['url_domain']."/signed.php?id=$ttdasid";
$qrcodetext2="http://".$iden['url_domain']."/signed.php?id=$ttdadid";

//create a QR Code and save it as a png image file named generateqr.png
QRcode::png($qrcodetext,"../foto_tandatangan/generateqr.png");
QRcode::png($qrcodetext2,"../foto_tandatangan/generateqr2.png");

//this is the second method
$write=new easyTable($pdf, '{95,95}', 'width:190; align:L; font-family:arial; font-size:12');
if (!empty($ttdas['id'])){
	$write->easyCell('', 'img:../foto_tandatangan/generateqr.png, h20; align:L; valign:T; font-size:10; font-style:B;');
}else{
	$write->easyCell('', 'align:L; valign:T; font-size:10; font-style:B;');
}
if (!empty($ttdad['id'])){
	$write->easyCell('', 'img:../foto_tandatangan/generateqr2.png, h20; align:R; valign:T; font-size:10; font-style:B;');
}else{
	$write->easyCell('', 'align:R; valign:T; font-size:10; font-style:B;');
}
$write->printRow();
$write->endTable(0);

$pdf->AliasNbPages();

//output file pdf
$fileoutputnya="FR-AK-01-".$skemakkni."-".$_GET['idj']."-".$_GET['ida'].".pdf";
$pdf->Output($fileoutputnya,'I');
 
ob_end_flush();
?>