<?php
ob_start();
include 'fpdf-easytable-master/fpdf.php';
include 'fpdf-easytable-master/exfpdf.php';
include 'fpdf-easytable-master/easyTable.php';
include "../config/koneksi.php";
include "../config/library.php";
include "../config/fungsi_indotgl.php";

$sqllsp="SELECT * FROM `lsp`";
$lsp=$conn->query($sqllsp);
$lq=$lsp->fetch_assoc();
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
$write->easyCell('FR.AK.05 - LAPORAN ASESMEN', 'align:L;');
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

$write->easyCell('Mandiri/Sewaktu/Tempat Kerja', 'align:L; font-size:10; border:LTBR');
$write->printRow();

$write->easyCell('Nama Asesor', 'align:L; font-size:10; colspan:2; border:LTBR');
$write->easyCell(':', 'align:C; font-size:10; border:LTBR');
$write->easyCell('', 'align:L; font-size:10; border:LTBR');
$write->printRow();
$write->endTable(5);
$write=new easyTable($pdf, '{50, 20, 5, 115}', 'width:190; align:L; font-style:B; font-family:arial; font-size:12');
$sqlgetunitkompetensi="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sq[id]'";
$getunitkompetensi=$conn->query($sqlgetunitkompetensi);
$jumunitnya0=$getunitkompetensi->num_rows;
$jumunitnya=$jumunitnya0*2;//kode dan judul unit adalah 2 baris
$noku=1;
//while unitkompetensi ==================================================================
while ($unk=$getunitkompetensi->fetch_assoc()){
	if ($noku==1){
		$write->easyCell('Unit Kompetensi', 'align:L; rowspan:'.$jumunitnya.'; border:LTBR');
		$write->easyCell('Kode Unit', 'align:L; font-size:10; border:LTBR');
		$write->easyCell(':', 'align:C; font-size:10; border:LTBR');
		$write->easyCell($unk['kode_unit'], 'align:L; font-size:10; border:LTBR');
		$write->printRow();
		$write->easyCell('Judul Unit', 'align:L; font-size:10; border:LTBR');
		$write->easyCell(':', 'align:C; font-size:10; border:LTBR');
		$write->easyCell($unk['judul'], 'align:L; font-size:10; border:LTBR');
		$write->printRow();
	}else{
		$write->easyCell('Kode Unit', 'align:L; font-size:10; border:LTBR');
		$write->easyCell(':', 'align:C; font-size:10; border:LTBR');
		$write->easyCell($unk['kode_unit'], 'align:L; font-size:10; border:LTBR');
		$write->printRow();
		$write->easyCell('Judul Unit', 'align:L; font-size:10; border:LTBR');
		$write->easyCell(':', 'align:C; font-size:10; border:LTBR');
		$write->easyCell($unk['judul'], 'align:L; font-size:10; border:LTBR');
		$write->printRow();
	}
	$noku++;
}
//end while unitkompetensi =============================================
$write->endTable(5);
$write=new easyTable($pdf, '{10,75,15,15,75}', 'width:190; align:L; font-family:arial; font-size:12');
$write->easyCell('No.', 'align:C; border:LT; font-style:B; rowspan:2');
$write->easyCell('Nama Asesi', 'align:C; border:LTR; font-style:B; rowspan:2');
$write->easyCell('Rekomendasi', 'align:C; border:TR; font-style:B; colspan:2');
$write->easyCell('Keterangan**', 'align:C; valign:M; rowspan:2; font-style:B; border:TR;');
$write->printRow();
$write->easyCell('K', 'align:C; font-style:B; border:TR;');
$write->easyCell('BK', 'align:C; font-style:B; border:TR;');
$write->printRow();
$sqlgetasesi="SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`='$jdq[id]' ORDER BY `id_asesi` ASC";
$getasesi=$conn->query($sqlgetasesi);	
$noas=1;
$i=1;
for ($i<11){
	$write->rowStyle('min-height:15');
	$write->easyCell($noas.'.', 'align:L; border:LTB; font-style:B;');

	$write->easyCell('', 'align:L; border:LTBR; font-style:B;');

			$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; border:TBR;');
			$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; border:TBR;');
			$write->easyCell($gtas['umpan_balik'], 'align:L; valign:T; border:TBR;');
			$write->printRow();

	}
	$noas++;
	$i++;
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

	$write=new easyTable($pdf, '{90,100}', 'width:190; align:L font-family:arial; font-size:12');
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

$write=new easyTable($pdf, '{100,30,60}', 'width:190; align:L font-family:arial; font-size:12');
$write->easyCell('Catatan:
 ', 'align:L; valign:T; border:LTR; font-size:12; font-style:B; rowspan:4');
$write->easyCell('Asesor:', 'align:L; font-size:12; font-style:B; border:LTBR; colspan:2');
$write->printRow();
$write->easyCell('Nama', 'align:L; font-size:12; border:LTBR;');
$write->easyCell('', 'align:L; font-size:12; border:LTBR;');
$write->printRow();
$write->easyCell('No. Reg', 'align:L; font-size:12; border:LTBR;');
$write->easyCell('', 'align:L; font-size:12; border:LTBR;');
$write->printRow();
$write->easyCell('Tanda Tangan/
Tanggal', 'align:L; font-size:12; border:LTR;');

	$write->easyCell('', 'align:L; valign:T; font-size:10; font-style:B; border:R;');

$write->printRow();

$write->easyCell('', 'align:L; font-size:12; border:LBR;');
$write->easyCell('', 'align:L; font-size:12; border:LBR;');
$write->easyCell('', 'align:C; font-size:12; border:LBR;');
$write->printRow();
$write->endTable(5);

$pdf->AliasNbPages();
//output file pdf
$fileoutputnya="Master-FR-AK-05-".$skemakkni."-".$_GET['idj'].".pdf";
$pdf->Output($fileoutputnya,'D');
ob_end_flush();
?>