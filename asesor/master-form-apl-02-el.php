<?php
ini_set('display_errors',0); 
//ob_start();
include 'fpdf-easytable-master/fpdf.php';
include 'fpdf-easytable-master/exfpdf.php';
include 'fpdf-easytable-master/easyTable.php';
include "../config/koneksi.php";
include "../config/library.php";
include "../config/fungsi_indotgl.php";


/* $sqlasesi="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_GET[ida]'";
$asesi=$conn->query($sqlasesi);
$as=$asesi->fetch_assoc();
$sqljadwal="SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
$jadwal=$conn->query($sqljadwal);
$jdq=$jadwal->fetch_assoc();
$tgl_cetak = tgl_indo($jdq['tgl_asesmen']);

$sqltuk="SELECT * FROM `tuk` WHERE `id`='$jdq[tempat_asesmen]'";
$tuk=$conn->query($sqltuk);
$tq=$tuk->fetch_assoc(); */
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
$write->easyCell('FR.APL.02. ASESMEN MANDIRI', 'align:L;');
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
$write->endTable(5);
$write=new easyTable($pdf, '{190}', 'width:190; align:L; font-family:arial; font-size:12');
$write->easyCell('Panduan Asesmen Mandiri', 'align:L; border:LTBR; bgcolor:#C6C6C6; font-style:B;');
$write->printRow();
$write->easyCell('Instruksi:', 'align:L; border:LR; font-style:B;');
$write->printRow();
$write->endTable(0);
$write=new easyTable($pdf, '{10,180}', 'width:190; align:L; font-family:arial; font-size:12');
$write->easyCell(chr(149), 'align:C; border:L;');
$write->easyCell('Baca setiap pertanyaan di kolom sebelah kiri', 'align:L; border:R;');
$write->printRow();
$write->easyCell(chr(149), 'align:C; border:L;');
$write->easyCell('Beri tanda centang (V) pada kotak jika Anda yakin dapat melakukan tugas yang dijelaskan', 'align:L; border:R;');
$write->printRow();
$write->easyCell(chr(149), 'align:C; border:LB;');
$write->easyCell('Isi kolom di sebelah kanan dengan mendaftar bukti yang Anda miliki untuk menunjukkan bahwa Anda melakukan tugas-tugas ini.', 'align:L; border:RB;');
$write->printRow();
$write->endTable(5);

$sqlgetunitkompetensi="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sq[id]' ORDER BY `id` ASC";
$getunitkompetensi=$conn->query($sqlgetunitkompetensi);
$noku=1;
//while unitkompetensi ==================================================================
while ($unk=$getunitkompetensi->fetch_assoc()){

	$write=new easyTable($pdf, '{30,160}', 'width:190; align:L; font-family:arial; font-size:12');
	$write->easyCell('Unit Kompetensi :', 'align:L; border:LTBR; font-style:B; bgcolor:#DFDFDF;');
	$write->easyCell($unk['kode_unit'].' - '.$unk['judul'], 'align:L; border:TRB; font-style:B; bgcolor:#DFDFDF;');
	$write->printRow();
	$write->endTable(0);
	$write=new easyTable($pdf, '{120,10,10,50}', 'width:190; align:L; font-family:arial; font-size:12');
	$write->easyCell('Dapatkah Saya .......................?', 'align:L; border:LTBR; font-style:B;');
	$write->easyCell('K', 'align:C; border:TRB; font-style:B;');
	$write->easyCell('BK', 'align:C; border:TRB; font-style:B;');
	$write->easyCell('Bukti yang relevan', 'align:C; border:TRB; font-style:B;');
	$write->printRow();
	$write->endTable(0);
	
	//while elemen
	$sqlgetelemen="SELECT * FROM `elemen_kompetensi` WHERE `id_unitkompetensi`='$unk[id]' ORDER BY `id` ASC";
	$getelemen=$conn->query($sqlgetelemen);
	$noel=1;
	while ($el=$getelemen->fetch_assoc()){
		// hitung rowspan berdasarkan KUK elemen
		$sqlgetkriteria="SELECT * FROM `kriteria_unjukkerja` WHERE `id_elemenkompetensi`='$el[id]' ORDER BY `id` ASC";
		$getkriteria=$conn->query($sqlgetkriteria);
		$jumkukelemen0=$getkriteria->num_rows;
		$jumkukelemen=$jumkukelemen0+2;// 2 = judul elemen dan heading kriteria
		$write=new easyTable($pdf, '{10,10,100,10,10,50}', 'width:190; align:L; font-family:arial; font-size:12');
		$elemen=$el['elemen_kompetensi'];
		$write->easyCell($noel.'.', 'align:L; border:LT; font-style:B;');
		$write->easyCell('Elemen :'.$elemen, 'align:L; border:LTR; font-style:B; colspan:2;');

		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; border:TR;');
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; border:TR;');

		$write->easyCell('', 'align:L; valign:T; rowspan:'.$jumkukelemen.'; border:TR;');
		$write->printRow();
		//judul kriteria
		$write->easyCell('', 'align:L; border:LR; font-style:B;');
		$write->easyCell('Kriteria Unjuk Kerja :', 'align:L; border:LR; font-style:B; colspan:2; font-size:10');
		$write->easyCell('', 'align:C; border:R;');
		$write->easyCell('', 'align:C; border:R;');
		//$write->easyCell('', 'align:C; border:R;');
		$write->printRow();
		//$write->endTable(0);
		
		//while kriteria ============================

		$nokrit=1;
		while($kr=$getkriteria->fetch_assoc()){
			//$write=new easyTable($pdf, '{10,10,100,10,10,50}', 'width:190; align:L; font-family:arial; font-size:10');
			$write->easyCell('', 'align:C; border:LR;');
			$write->easyCell($noel.'.'.$nokrit.'.', 'align:R; font-size:10');
			$write->easyCell($kr['kriteria'], 'align:L; border:R; font-size:10');
			$write->easyCell('', 'align:C; border:R;');
			$write->easyCell('', 'align:C; border:R;');
			//$write->easyCell('', 'align:C; border:R;');
			$write->printRow();
			//$write->endTable(0);
			$nokrit++;
		}
		$write->endTable(0);
		//end while kriteria =========================
		$noel++;
	}
	//end while elemen ==================================
	//enc while unitkompetensi ============================================
	// garis penutup tabel ============
	$write=new easyTable($pdf, '{190}', 'width:190; align:L; font-family:arial; font-size:12');
	$write->easyCell('', 'align:C; border:T;');
	$write->printRow();
	$write->endTable(0);
	//=================================
}

//end while unitkompetensi =============================================

$write=new easyTable($pdf, '{62,62,66}', 'width:190; align:L; font-family:arial; font-size:12');
$write->easyCell('Nama Asesi:', 'align:L; border:LTR;');
$write->easyCell('Tanggal:', 'align:L; border:TR;');
$write->easyCell('Tanda Tangan Asesi:', 'align:L; border:TR;');
$write->printRow();
$write->rowStyle('min-height:20');
$write->easyCell('', 'align:L; border:LBR; font-style:B;');
$write->easyCell('', 'align:L; border:BR; font-style:B;');

// tandatangan asesi
$sqlidentitas="SELECT * FROM `identitas`";
$identitas=$conn->query($sqlidentitas);
$iden=$identitas->fetch_assoc();

$write->easyCell('', 'align:L; valign:T; font-size:10; font-style:B; border:BR;');


$write->easyCell('', 'align:L; border:BR;');
$write->printRow();
$write->endTable(0);

$write=new easyTable($pdf, '{190}', 'width:190; align:L; font-family:arial; font-size:12');
$write->easyCell('Ditinjau oleh Asesor:', 'align:L; border:LTBR; bgcolor:#C6C6C6; font-style:B;');
$write->printRow();
$write->endTable(0);

$write=new easyTable($pdf, '{62,62,66}', 'width:190; align:L; font-family:arial; font-size:12');
$write->easyCell('Nama Asesor:', 'align:L; border:LTR; font-style:B;');
$write->easyCell('Rekomendasi:', 'align:L; border:TR; font-style:B;');
$write->easyCell('Tanda Tangan dan Tanggal:', 'align:L; border:TR; font-style:B;');
$write->printRow();
$write->rowStyle('min-height:20');
$write->easyCell('', 'align:L; border:LBR; font-style:B;');

$write->easyCell('Asesmen dapat dilanjutkan/ tidak dapat dilanjutkan', 'align:L; border:BR;');

$write->easyCell('', 'align:L; valign:T; font-size:10; font-style:B; border:BR;');

$write->printRow();
$write->endTable(0);

$pdf->AliasNbPages();

//output file pdf
$fileoutputnya="Master-FR-APL-02-".$skemakkni.".pdf";
$pdf->Output($fileoutputnya,'D');
 
ob_end_flush();
?>