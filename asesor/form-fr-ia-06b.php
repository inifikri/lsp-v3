<?php
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

$write=new easyTable($pdf, 1, 'width:190;  font-style:B; font-size:12;font-family:arial;');
$write->easyCell('FR.IA.06.B. LEMBAR JAWABAN PERTANYAAN TERTULIS ESAI', 'align:L;');
$write->printRow();
$write->endTable(5);

$write=new easyTable($pdf, '{50, 20, 5, 115}', 'width:190; align:L; font-style:B; font-family:arial; font-size:12');
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

$sqlgetunitkompetensi="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sq[id]'";
$getunitkompetensi=$conn->query($sqlgetunitkompetensi);
$jumunitnya=$getunitkompetensi->num_rows;
$jumrowspan=2*$jumunitnya;
//while unitkompetensi ==================================================================
$write=new easyTable($pdf, '{40, 30, 5, 115}', 'width:190; align:L; font-style:B; font-family:arial; font-size:10');
$write->easyCell('Unit Kompetensi', 'align:L; rowspan:'.$jumrowspan.'; border:LTBR');
while ($unk=$getunitkompetensi->fetch_assoc()){
		$write->easyCell('Kode Unit', 'align:L; font-size:10; border:LTBR');
		$write->easyCell(':', 'align:C; font-size:10; border:LTBR');
		$write->easyCell($unk['kode_unit'], 'align:L; font-size:10; border:LTBR');
		$write->printRow();
		$write->easyCell('Judul Unit', 'align:L; font-size:10; border:LTBR');
		$write->easyCell(':', 'align:C; font-size:10; border:LTBR');
		$write->easyCell($unk['judul'], 'align:L; font-size:10; border:LTBR');
		$write->printRow();
}
$write->endTable(5);

$write=new easyTable($pdf, '{10,150,15,15}', 'width:190; align:L; font-family:arial; font-size:12');
$write->easyCell('Jawaban:', 'align:C; valign:M; font-size:12; font-style:B; colspan:2; rowspan:2; bgcolor:#C6C6C6; border:LTBR');
$write->easyCell('Rekomendasi', 'align:C; valign:M; font-size:12; font-style:B; colspan:2; bgcolor:#C6C6C6; border:LTBR');
$write->printRow();
$write->easyCell('K', 'align:C; valign:M; font-size:12; font-style:B; bgcolor:#C6C6C6; border:LTBR');
$write->easyCell('BK', 'align:C; valign:M; font-size:12; font-style:B; bgcolor:#C6C6C6; border:LTBR');
$write->printRow();

$sqlgetunitkompetensib="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sq[id]'";
$getunitkompetensib=$conn->query($sqlgetunitkompetensib);
$noku=1;
$nopp=1;
while ($unkb=$getunitkompetensib->fetch_assoc()){

	$sqlgetpertanyaan="SELECT * FROM `skema_pertanyaanesai` WHERE `id_unitkompetensi`='$unkb[id]' ORDER BY `id` ASC";
	$getpertanyaan=$conn->query($sqlgetpertanyaan);
	$jumpertanyaan=$getpertanyaan->num_rows;

	while ($gpp=$getpertanyaan->fetch_assoc()){
		if (!empty($gpp['pertanyaan'])){
			$write->rowStyle('min-height:40');
			$write->easyCell($nopp.'.', 'align:L; valign:T; font-size:11; font-style:B; border:LTB');
			$sqlcekjawaban="SELECT * FROM `asesmen_ia06` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='$gpp[id]'";
			$cekjawaban=$conn->query($sqlcekjawaban);
			$jwbx=$cekjawaban->fetch_assoc();
			if (!empty($jwbx['jawaban'])){
				$write->easyCell($jwbx['jawaban'], 'align:L; valign:T; font-size:11; font-style:B; border:TRB');
			}else{
				$write->easyCell('', 'align:L; valign:T; font-size:11; font-style:B; border:TRB');
			}
			switch ($jwbx['rekomendasi']){
				default:
					$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTRB;');
					$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTRB;');
				break;
				case "K":
					$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTRB;');
					$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTRB;');
				break;
				case "BK":
					$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTRB;');
					$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:T; border:LTRB;');
				break;
			}
			$write->printRow();
			$nopp++;
		}
	}
	
}
$write->endTable(5);
//end while unitkompetensi =============================================

$write=new easyTable($pdf, '{30,80,80}', 'width:190; align:L; font-family:arial; font-size:12');
$write->easyCell('Nama:', 'align:L; valign:M; border:LTRB; rowspan:2');
$write->easyCell('Asesi:', 'align:L; valign:T;  border:TR;');
$write->easyCell('Asesor:', 'align:L; valign:T;  border:TR;');
$write->printRow();
$write->easyCell($as['nama'], 'align:L; border:LBR; font-style:B;');
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
	$namaasesor=$noasr.'. '.$namaasesor;
	$noasr++;

}
$write->easyCell($namaasesor, 'align:L; border:LBR; font-style:B;');
$write->printRow();

$sqlidentitas="SELECT * FROM `identitas`";
$identitas=$conn->query($sqlidentitas);
$iden=$identitas->fetch_assoc();

// tandatangan Asesi
$namaasesi=$as['nama'];
$urltandatangan=$iden['url_domain']."/media.php?module=form-ia-06&amp;ida=".$_GET['ida']."&amp;idj=".$_GET['idj'];
$sqlcekttdasesiapl01="SELECT * FROM `logdigisign` WHERE `nama_dokumen`='FR.IA.06. PERTANYAAN TERTULIS ESAI' AND `penandatangan`='$namaasesi' AND `url_ditandatangani`='$urltandatangan' ORDER BY `id` DESC";
$cekttdasesiapl01=$conn->query($sqlcekttdasesiapl01);
$jumttdasesi=$cekttdasesiapl01->num_rows;
$ttdas=$cekttdasesiapl01->fetch_assoc();

// tandatangan Asesor LSP
$urltandatanganadmin=$iden['url_domain']."/asesor/media.php?module=form-fr-ia-06&amp;ida=".$_GET['ida']."&amp;idj=".$_GET['idj'];
$sqlcekttdadminapl01="SELECT * FROM `logdigisign` WHERE `nama_dokumen`='FR.IA.06. PERTANYAAN TERTULIS ESAI' AND `penandatangan`='$asr[nama]' AND `url_ditandatangani`='$urltandatanganadmin' ORDER BY `id` DESC";
$cekttdadminapl01=$conn->query($sqlcekttdadminapl01);
$jumttdadmin=$cekttdadminapl01->num_rows;
$ttdad=$cekttdadminapl01->fetch_assoc();

//$tglttdasesi=tgl_indo($jjw['tanggal_asesittd']);
if ($jumttdasesi>0){
	$write->easyCell('Tanda Tangan dan Tanggal', 'align:L; border:LTBR; rowspan:2');
	$write->easyCell($tgl_cetak, 'align:L; border:TR; font-style:TB;');
	if ($jumttdadmin>0){
		$write->easyCell($tgl_cetak, 'align:L; border:TR;');
	}else{
		$write->easyCell('', 'align:L; border:TR;');
	}
	$write->printRow();
	$write->easyCell('', 'img:../'.$ttdas['file'].', h20; align:C; valign:T; font-size:10; font-style:B; border:BR;');
	if ($jumttdadmin>0){
		$write->easyCell('', 'img:'.$ttdad['file'].', h20; align:C; valign:T; font-size:10; font-style:B; border:BR;');
	}else{
		$write->easyCell('', 'align:L; border:BR;');
	}
	$write->printRow();
}else{
	$write->rowStyle('min-height:30');
	$write->easyCell('Tanda Tangan dan Tanggal', 'align:L; border:LTBR;');
	$write->easyCell($tgl_cetak, 'align:L; border:TBR; font-style:TB;');
	$write->easyCell('', 'align:L; border:TBR;');
	$write->printRow();
}

$write->endTable(0);
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
$fileoutputnya="FR-IA-06B-".$skemakkni."-".$_GET['idj']."-".$_GET['ida'].".pdf";
$pdf->Output($fileoutputnya,'I');
 
ob_end_flush();
?>