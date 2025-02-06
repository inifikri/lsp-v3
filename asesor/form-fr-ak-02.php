<?php
ob_start();
include 'fpdf-easytable-master/fpdf.php';
include 'fpdf-easytable-master/exfpdf.php';
include 'fpdf-easytable-master/easyTable.php';
include "../config/koneksi.php";
include "../config/library.php";
include "../config/fungsi_indotgl.php";
$sqlidentitas="SELECT * FROM `identitas`";
$identitas=$conn->query($sqlidentitas);
$iden=$identitas->fetch_assoc();
$sqlasesi="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_GET[ida]'";
$asesi=$conn->query($sqlasesi);
$as=$asesi->fetch_assoc();
$sqljadwal="SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
$jadwal=$conn->query($sqljadwal);
$jdq=$jadwal->fetch_assoc();
$tgl_cetak = tgl_indo($jdq['tgl_asesmen']);
$tgl_cetak2 = tgl_indo($jdq['tgl_asesmen_akhir']);

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

//Data Asesmen
$sqlgetkeputusan="SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
$getkeputusan=$conn->query($sqlgetkeputusan);
$getk=$getkeputusan->fetch_assoc();

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
$write->easyCell('FR.AK.02. FORMULIR REKAMAN ASESMEN KOMPETENSI', 'align:L;');
$write->printRow();
$write->endTable(5);

$write=new easyTable($pdf, '{50, 40,5,5, 100}', 'width:190; align:L; font-family:arial; font-size:12');

	$noasr=1;
	$getasesor=$conn->query("SELECT * FROM `jadwal_asesor` WHERE `id_jadwal`='$_GET[idj]' ORDER BY `id` LIMIT 1");
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
		$namaasesor=$namaasesor;
		$noregasesor=$noregasesor;

		$noasr++;

	}

$write->easyCell('Skema Sertifikasi (KKNI/Okupasi/Klaster)', 'align:L;rowspan:2; font-size:12; border:LTBR');
$write->easyCell('Judul', 'align:L; font-size:12; border:LTBR');
$write->easyCell(':', 'align:L; font-size:12; border:LTBR');
$write->easyCell($skemakkni, 'align:L; font-style:B;font-size:12; colspan:2; border:LTBR');
$write->printRow();
$write->easyCell('Nomor', 'align:L; font-size:12; border:LTBR');
$write->easyCell(':', 'align:L; font-size:12; border:LTBR');
$write->easyCell($sq['kode_skema'], 'align:L;font-style:B; font-size:12; colspan:2; border:LTBR');
$write->printRow();
$write->easyCell('TUK', 'align:L;colspan:2; border:LTBR');
$write->easyCell(':', 'align:L; border:LTBR');
$write->easyCell('TUK', 'align:L; border:LTBR;colspan:2;');
$write->printRow();
$write->easyCell('Nama Asesi', 'align:L;colspan:2; border:LTBR');
$write->easyCell(':', 'align:L; border:LTBR');
$write->easyCell($as['nama'], 'align:L; border:LTBR;colspan:2;');
$write->printRow();
$write->easyCell('Nama Asesor', 'align:L;colspan:2; border:LTBR');
$write->easyCell(':', 'align:L; border:LTBR');
$write->easyCell($namaasesor, 'align:L; border:LTBR;colspan:2;');
$write->printRow();
$sqlgetunitkompetensi0="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sq[id]'";
$getunitkompetensi0=$conn->query($sqlgetunitkompetensi0);
$jumunitnya=$getunitkompetensi0->num_rows;
$noku0=1;
//while unitkompetensi ==================================================================
// while ($unk0=$getunitkompetensi0->fetch_assoc()){
// 	if ($noku0==1){
// 		$write->easyCell('Unit Kompetensi', 'align:L; valign:T; rowspan:'.$jumunitnya.'; border:LTBR');
// 		$write->easyCell($unk0['kode_unit'], 'align:L; font-size:12; border:LTBR');
// 		$write->easyCell($unk0['judul'], 'align:L; font-size:12; border:LTBR');
// 		$write->printRow();
// 	}else{
// 		$write->easyCell($unk0['kode_unit'], 'align:L; font-size:12; border:LTBR');
// 		$write->easyCell($unk0['judul'], 'align:L; font-size:12; border:LTBR');
// 		$write->printRow();

// 	}
// 	$noku0++;
// }
$write->easyCell('Tanggal Asesmen', 'align:L;rowspan:2; font-size:12; border:LTBR');
$write->easyCell('Mulai', 'align:L; font-size:12; border:LTBR');
$write->easyCell(':', 'align:L; font-size:12; border:LTBR');
$write->easyCell($tgl_cetak, 'align:L;font-size:12; colspan:2; border:LTBR');
$write->printRow();
$write->easyCell('Selesai', 'align:L; font-size:12; border:LTBR');
$write->easyCell(':', 'align:L; font-size:12; border:LTBR');
$write->easyCell($tgl_cetak2, 'align:L; font-size:12; colspan:2; border:LTBR');
$write->printRow();
$write->endTable(5);

$write=new easyTable($pdf, '{190}', 'width:190; align:L; font-style:B; font-family:arial; font-size:12');
$write->easyCell('Beri tanda centang (V) di kolom yang sesuai untuk mencerminkan bukti yang diperoleh untuk menentukan Kompetensi asesi untuk setiap Unit Kompetensi.', 'align:L; font-size:12;');
$write->printRow();
$write->endTable(5);

$write=new easyTable($pdf, '{35,23,22,22,22,22,22,22}', 'width:190; align:L; font-family:arial; font-size:12');
$write->easyCell('Unit Kompetensi', 'align:C; valign:M;  bgcolor:#C6C6C6; font-style:B; font-size:10; border:LTBR');
$write->easyCell('Observasi demonstrasi', 'align:C; valign:M; bgcolor:#C6C6C6; font-style:B; font-size:10; border:LTBR');
$write->easyCell('Portofolio', 'align:C; valign:M; bgcolor:#C6C6C6; font-style:B; font-size:10; border:LTBR');
$write->easyCell('Pernyataan Pihak Ketiga Pertanyaan Wawancara', 'align:C; valign:M; bgcolor:#C6C6C6; font-style:B; font-size:10; border:LTBR');
$write->easyCell('Pertanyaan lisan', 'align:C; valign:M; bgcolor:#C6C6C6; font-style:B; font-size:10; border:LTBR');
$write->easyCell('Pertanyaan tertulis', 'align:C; valign:M; bgcolor:#C6C6C6; font-style:B; font-size:10; border:LTBR');
$write->easyCell('Proyek kerja', 'align:C; valign:M; bgcolor:#C6C6C6; font-style:B; font-size:10; border:LTBR');
$write->easyCell('Lainnya', 'align:C; valign:M; bgcolor:#C6C6C6; font-style:B; font-size:10; border:LTBR');
$write->printRow();
$sqlgetunitkompetensi="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sq[id]'";
$getunitkompetensi=$conn->query($sqlgetunitkompetensi);
//while unitkompetensi ==================================================================
while ($unk=$getunitkompetensi->fetch_assoc()){
	$write->easyCell($unk['judul'], 'align:L; valign:T; font-size:10; border:LTBR');
	// mendapatkan mapa 1
	$sqlgetmapa1b="SELECT * FROM `asesmen_ak02` WHERE `id_asesi`='$_GET[ida]' AND `id_unitkompetensi`='$unk[id]' AND `id_jadwal`='$_GET[idj]'";
	$getmapa1b=$conn->query($sqlgetmapa1b);
	$gmapa1=$getmapa1b->fetch_assoc();
	if ($gmapa1['CL']=="1"){
		$write->easyCell('', 'img:../images/checkedonly.jpg, w5, h5; align:C; valign:M; font-size:10; border:LTBR');
	}else{
		$write->easyCell('', 'align:C; font-size:10; border:LTBR');
	}
	if ($gmapa1['VP']=="1"){
		$write->easyCell('', 'img:../images/checkedonly.jpg, w5, h5; align:C; valign:M; font-size:10; border:LTBR');
	}else{
		$write->easyCell('', 'align:C; font-size:10; border:LTBR');
	}
	if ($gmapa1['PW']=="1"){
		$write->easyCell('', 'img:../images/checkedonly.jpg, w5, h5; align:C; valign:M; font-size:10; border:LTBR');
	}else{
		$write->easyCell('', 'align:C; font-size:10; border:LTBR');
	}
	if ($gmapa1['DPL']=="1"){
		$write->easyCell('', 'img:../images/checkedonly.jpg, w5, h5; align:C; valign:M; font-size:10; border:LTBR');
	}else{
		$write->easyCell('', 'align:C; font-size:10; border:LTBR');
	}
	if ($gmapa1['DPT']=="1"){
		$write->easyCell('', 'img:../images/checkedonly.jpg, w5, h5; align:C; valign:M; font-size:10; border:LTBR');
	}else{
		$write->easyCell('', 'align:C; font-size:10; border:LTBR');
	}
	if ($gmapa1['CUP']=="1"){
		$write->easyCell('', 'img:../images/checkedonly.jpg, w5, h5; align:C; valign:M; font-size:10; border:LTBR');
	}else{
		$write->easyCell('', 'align:C; font-size:10; border:LTBR');
	}
	if ($gmapa1['Lainnya']=="1"){
		$write->easyCell('', 'img:../images/checkedonly.jpg, w5, h5; align:C; valign:M; font-size:10; border:LTBR');
	}else{
		$write->easyCell('', 'align:C; font-size:10; border:LTBR');
	}
	$write->printRow();
}
$write->endTable(0);

$write=new easyTable($pdf, '{50,140}', 'width:190; align:L; font-family:arial; font-size:12');
$write->easyCell('Rekomendasi hasil asesmen', 'align:L; valign:T; font-style:B; font-size:12; border:LTBR');
switch($getk['keputusan_asesor']){
	default:
		$write->easyCell('Kompeten / Belum kompeten', 'align:L; valign:T; font-size:12; border:LTBR');
	break;
	case "R":
		$write->easyCell('Kompeten', 'align:L; valign:T; font-size:12; font-style:B; border:LTBR');
	break;
	case "NR":
		$write->easyCell('Belum kompeten', 'align:L; valign:T; font-size:12; font-style:B; border:LTBR');
	break;

}
$write->printRow();
if (!empty($getk['tindak_lanjut_ak02'])){
	$write->easyCell('Tindak lanjut yang dibutuhkan', 'align:L; valign:T; font-style:B; font-size:12; border:LTR');
	$write->easyCell($getk['tindak_lanjut_ak02'], 'align:L; rowspan:2; valign:T; font-style:B; font-size:12; border:LTBR');
	$write->printRow();
	$write->easyCell('(Masukkan pekerjaan tambahan dan asesmen yang diperlukan untuk mencapai kompetensi) ', 'align:L; valign:T; font-size:12; border:LBR');
	$write->printRow();
	$write->easyCell('Komentar/ Observasi oleh asesor', 'align:L; valign:T; font-style:B; font-size:12; border:LTBR');
	$write->easyCell($getk['umpan_balik'], 'align:L; valign:T; font-style:B; font-size:12; border:LTBR');
	$write->printRow();
}else{
	$write->easyCell('Tindak lanjut yang dibutuhkan', 'align:L; valign:T; font-style:B; font-size:12; border:LTR');
	$write->easyCell('', 'align:L; rowspan:2; valign:T; font-style:B; font-size:12; border:LTBR');
	$write->printRow();
	$write->easyCell('(Masukkan pekerjaan tambahan dan asesmen yang diperlukan untuk mencapai kompetensi) ', 'align:L; valign:T; font-size:12; border:LBR');
	$write->printRow();
	$write->easyCell('Komentar/ Observasi oleh asesor', 'align:L; valign:T; font-style:B; font-size:12; border:LTBR');
	$write->easyCell($getk['umpan_balik'], 'align:L; valign:T; font-style:B; font-size:12; border:LTBR');
	$write->printRow();
}
$write->endTable(0);
$write=new easyTable($pdf, '{40,5,60,90}', 'width:190; align:L; font-family:arial; font-size:12');
// tandatangan Asesi
$urltandatangan=$iden['url_domain']."/media.php?module=form-fr-ak-02&amp;ida=".$_GET['ida']."&amp;idj=".$_GET['idj'];
$sqlcekttdasesiapl01="SELECT * FROM `logdigisign` WHERE `nama_dokumen`='FR.AK.02. FORMULIR REKAMAN ASESMEN KOMPETENSI' AND `penandatangan`='$as[nama]' ORDER BY `id` DESC";
$cekttdasesiapl01=$conn->query($sqlcekttdasesiapl01);
$jumttdasesi=$cekttdasesiapl01->num_rows;
$ttdas=$cekttdasesiapl01->fetch_assoc();
$tglttdasesi=tgl_indo(substr($ttdas['waktu'],0,10));
if ($jumttdasesi>0){
	// $write->rowStyle('min-height:20');
	$write->easyCell('Asesi :', 'align:L; valign:T;colspan:4;font-style:B; font-size:12; border:LTBR');
	$write->printRow();
	$write->easyCell('Nama', 'align:L; valign:T; font-size:12; border:LTBR');
	$write->easyCell(':', 'align:L; valign:T; font-size:12; border:LTBR');
	$write->easyCell($as['nama'], 'align:L;colspan:4; valign:T; font-size:12; border:LTBR');
	$write->printRow();
	$write->easyCell('Tanda Tangan dan Tanggal', 'align:L; valign:T; font-size:12; border:LTBR');
	$write->easyCell(':', 'align:L; valign:T; font-size:12; border:LTBR');
	$write->easyCell($tglttdasesi, 'img:../'.$ttdas['file'].', h20; align:C;colspan:3; valign:T; font-size:10; border:LTBR;');
	$write->printRow();
}else{
	// $write->rowStyle('min-height:20');
	$write->easyCell('Asesi :', 'align:L; valign:T;colspan:4;font-style:B; font-size:12; border:LTBR');
	$write->printRow();
	$write->easyCell('Nama', 'align:L; valign:T; font-size:12; border:LTBR');
	$write->easyCell(':', 'align:L; valign:T; font-size:12; border:LTBR');
	$write->easyCell('', 'align:L;colspan:4; valign:T; font-size:12; border:LTBR');
	$write->printRow();
	$write->easyCell('Tanda Tangan dan Tanggal', 'align:L; valign:T; font-size:12; border:LTBR');
	$write->easyCell(':', 'align:L; valign:T; font-size:12; border:LTBR');
	$write->easyCell('', 'align:L;colspan:4; valign:T; font-size:12; border:LTBR');
	$write->printRow();
}
// tandatangan Asesor LSP
$urltandatanganadmin=$iden['url_domain']."/asesor/media.php?module=form-fr-ak-02&amp;ida=".$_GET['ida']."&amp;idj=".$_GET['idj'];
$sqlcekttdadminapl01="SELECT * FROM `logdigisign` WHERE `nama_dokumen`='FR.AK.02. FORMULIR REKAMAN ASESMEN KOMPETENSI' AND `penandatangan`='$asr[nama]' ORDER BY `id` DESC";
$cekttdadminapl01=$conn->query($sqlcekttdadminapl01);
$jumttdadmin=$cekttdadminapl01->num_rows;
$ttdad=$cekttdadminapl01->fetch_assoc();
$tglttdasesor=tgl_indo(substr($ttdad['waktu'],0,10));
if ($jumttdadmin>0){
	// $write->rowStyle('min-height:20');
	$write->easyCell('Asesor :', 'align:L; valign:T;colspan:4;font-style:B; font-size:12; border:LTBR');
	$write->printRow();
	$write->easyCell('Nama', 'align:L; valign:T; font-size:12; border:LTBR');
	$write->easyCell(':', 'align:L; valign:T; font-size:12; border:LTBR');
	$write->easyCell($namaasesor, 'align:L;colspan:4; valign:T; font-size:12; border:LTBR');
	$write->printRow();
	$write->easyCell('No. Reg', 'align:L; valign:T; font-size:12; border:LTBR');
	$write->easyCell(':', 'align:L; valign:T; font-size:12; border:LTBR');
	$write->easyCell($noregasesor, 'align:L;colspan:4; valign:T; font-size:12; border:LTBR');
	$write->printRow();
	$write->easyCell('Tanda Tangan dan Tanggal', 'align:L; valign:T; font-size:12; border:LTBR');
	$write->easyCell(':', 'align:L; valign:T; font-size:12; border:LTBR');
	$write->easyCell($tglttdasesor, 'img:'.$ttdad['file'].', h20; align:C;colspan:3; valign:T; font-size:10; border:LTBR;');
	$write->printRow();
}else{
	$write->rowStyle('min-height:20');
	$write->easyCell('Tanda tangan Asesor:', 'align:L; valign:T; font-style:B; font-size:12; border:LTBR');
	$write->easyCell('', 'align:L; valign:T; font-style:B; font-size:12; border:LTBR');
	$write->easyCell('Tanggal:', 'align:L; valign:T; font-style:B; font-size:12; border:LTBR');
	$write->easyCell('', 'align:L; valign:T; font-style:B; font-size:12; border:LTBR');
	$write->printRow();
}
$write->endTable(5);

$write=new easyTable($pdf, '{10,180}', 'width:190; align:L; font-family:arial; font-size:12');
$write->easyCell('LAMPIRAN DOKUMEN:', 'align:L; valign:T; font-size:12; colspan:2;');
$write->printRow();
$write->easyCell('1.', 'align:L; valign:T; font-size:12;');
$write->easyCell('Dokumen APL 01 Peserta', 'align:L; valign:T; font-size:12;');
$write->printRow();
$write->easyCell('2.', 'align:L; valign:T; font-size:12;');
$write->easyCell('Dokumen APL 02 Peserta', 'align:L; valign:T; font-size:12;');
$write->printRow();
$write->easyCell('3.', 'align:L; valign:T; font-size:12;');
$write->easyCell('Bukti-bukti berkualitas Peserta', 'align:L; valign:T; font-size:12;');
$write->printRow();
$write->easyCell('4.', 'align:L; valign:T; font-size:12;');
$write->easyCell('Tinjauan proses asesmen.', 'align:L; valign:T; font-size:12;');
$write->printRow();
$write->endTable(5);
//memanggil library QR Code
require_once("../phpqrcode/qrlib.php");

$qrcodetext="http://".$iden['url_domain']."/signed.php?id=$ttdas[id]";
$qrcodetext2="http://".$iden['url_domain']."/signed.php?id=$ttdad[id]";

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
$fileoutputnya="FR-AK-02-".$skemakkni."-".$_GET['idj']."-".$_GET['ida'].".pdf";
$pdf->Output($fileoutputnya,'I');
 
ob_end_flush();
?>