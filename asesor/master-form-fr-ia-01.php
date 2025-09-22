<?php
ini_set('display_errors',0); 
//error_reporting(E_ALL);

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
$write->easyCell('FR.IA.01. CEKLIS OBSERVASI AKTIVITAS DI TEMPAT KERJA ATAU TEMPAT KERJA SIMULASI', 'align:L;');
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
$write->easyCell('Nama Asesi', 'align:L; font-size:10; colspan:2; border:LTBR');
$write->easyCell(':', 'align:C; font-size:10; border:LTBR');
$write->easyCell('', 'align:L; font-size:10; border:LTBR');
$write->printRow();
$write->easyCell('Tanggal', 'align:L; font-size:10; colspan:2; border:LTBR');
$write->easyCell(':', 'align:C; font-size:10; border:LTBR');
$write->easyCell('', 'align:L; font-size:10; border:LTBR');
$write->printRow();

$write->endTable(5);

$write=new easyTable($pdf, '{190}', 'width:190; align:L; font-family:arial; font-size:12');
$write->easyCell('PANDUAN BAGI ASESOR', 'align:L; border:LTBR; bgcolor:#C6C6C6; font-style:B;');
$write->printRow();
$write->endTable(0);
$write=new easyTable($pdf, '{10,180}', 'width:190; align:L; font-family:arial; font-size:12');
$write->easyCell(chr(149), 'align:C; border:L;');
$write->easyCell('Lengkapi nama unit kompetensi, elemen, dan kriteria unjuk kerja sesuai kolom dalam tabel.', 'align:L; border:R;');
$write->printRow();
$write->easyCell(chr(149), 'align:C; border:L;');
$write->easyCell('Istilah Acuan Pembanding  dengan SOP/spesifikasi produk dari industri/organisasi dari tempat kerja atau simulasi tempat kerja.', 'align:L; border:R;');
$write->printRow();
$write->easyCell(chr(149), 'align:C; border:L;');
$write->easyCell('Beri tanda centang (V) pada kolom K jika Anda yakin asesi dapat melakukan/ mendemonstrasikan tugas sesuai KUK, atau centang (V) pada kolom BK bila sebaliknya.', 'align:L; border:R;');
$write->printRow();
$write->easyCell(chr(149), 'align:C; border:LB;');
$write->easyCell('Penilaian Lanjut diisi bila hasil belum dapat disimpulkan, untuk itu gunakan metode lain sehingga keputusan dapat dibuat.', 'align:L; border:RB;');
$write->printRow();
$write->endTable(5);

$sqlgetunitkompetensi="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sq[id]' ORDER BY `id` ASC";
$getunitkompetensi=$conn->query($sqlgetunitkompetensi);
$noku=1;
//while unitkompetensi ==================================================================
while ($unk=$getunitkompetensi->fetch_assoc()){
	$write=new easyTable($pdf, '{40, 30, 5, 115}', 'width:190; align:L; font-style:B; font-family:arial; font-size:12');
	$write->easyCell('Unit Kompetensi', 'align:L; rowspan:2; border:LTBR');
	$write->easyCell('Kode Unit', 'align:L; font-size:12; border:LTBR');
	$write->easyCell(':', 'align:C; font-size:12; border:LTBR');
	$write->easyCell($unk['kode_unit'], 'align:L; font-size:12; border:LTBR');
	$write->printRow();
	$write->easyCell('Judul Unit', 'align:L; font-size:12; border:LTBR');
	$write->easyCell(':', 'align:C; font-size:12; border:LTBR');
	$write->easyCell($unk['judul'], 'align:L; font-size:12; border:LTBR');
	$write->printRow();
	$write->endTable(5);

	$write=new easyTable($pdf, '{10,40,50,45,15,15,35}', 'width:190; align:L; font-family:arial; font-size:12');
	$write->easyCell('No.', 'align:C; valign:M; font-size:11; font-style:B; rowspan:2; bgcolor:#C6C6C6; border:LTBR');
	$write->easyCell('Elemen', 'align:C; valign:M; rowspan:2; font-size:11; font-style:B; bgcolor:#C6C6C6; border:LTBR');
	$write->easyCell('Kriteria Unjuk Kerja*', 'align:C; valign:M; rowspan:2; font-size:11; font-style:B; bgcolor:#C6C6C6; border:LTBR');
	$write->easyCell('Benchmark (SOP / spesifikasi produk industri)', 'align:C; valign:M; rowspan:2; font-style:B; font-size:11; bgcolor:#C6C6C6; border:LTBR');
	$write->easyCell('Rekomendasi', 'align:C; valign:M; colspan:2; font-size:10; font-style:B; bgcolor:#C6C6C6; border:LTBR');
	$write->easyCell('Penilaian Lanjut', 'align:C; valign:M; rowspan:2; font-size:11; font-style:B; bgcolor:#C6C6C6; border:LTBR');
	$write->printRow();
	$write->easyCell('K', 'align:C; valign:M; font-size:11; font-style:B; bgcolor:#C6C6C6; border:LTBR');
	$write->easyCell('BK', 'align:C; valign:M; font-size:11; font-style:B; bgcolor:#C6C6C6; border:LTBR');
	$write->printRow();

	$write->endTable(0);

	//while elemen
	$sqlgetelemen="SELECT * FROM `elemen_kompetensi` WHERE `id_unitkompetensi`='$unk[id]' ORDER BY `id` ASC";
	$getelemen=$conn->query($sqlgetelemen);
	$noel=1;
	while ($el=$getelemen->fetch_assoc()){
		$write=new easyTable($pdf, '{10,40,10,40,45,15,15,35}', 'width:190; align:L; font-family:arial; font-size:12');
		$elemen=$el['elemen_kompetensi'];
		$nokrit=1;
		$sqlgetkriteria="SELECT * FROM `kriteria_unjukkerja` WHERE `id_elemenkompetensi`='$el[id]' ORDER BY `id` ASC";
		$getkriteria=$conn->query($sqlgetkriteria);
		// hitung rowspan berdasarkan KUK elemen
		$jumkukelemen0=$getkriteria->num_rows;
		//while kriteria ============================
		/* $getcatatanbukti="SELECT * FROM `asesi_apl02` WHERE `id_asesi`='$as[no_pendaftaran]' AND `id_elemen`='$el[id]'";
		$catatanbukti=$conn->query($getcatatanbukti);
		$cbkt=$catatanbukti->fetch_assoc(); */
		while($kr=$getkriteria->fetch_assoc()){
			if ($nokrit==1){
				$write->easyCell($noel.'.', 'align:C; valign:T; font-style:B; border:LTB; rowspan:'.$jumkukelemen0.'; font-size:9;');
				$write->easyCell($elemen, 'align:L; valign:T; font-style:B; border:LTRB; rowspan:'.$jumkukelemen0.'; font-size:9;');
				$nokrnya=$noel.'.'.$nokrit.'.';
				$write->easyCell($nokrnya, 'align:L; valign:T; border:T; font-size:9;');
				$write->easyCell($kr['kriteria'], 'align:L; valign:T; border:TR; font-size:9;');
				$write->easyCell('', 'align:L; valign:T; border:TRB; rowspan:'.$jumkukelemen0.'; font-size:9;');
				
					$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; rowspan:'.$jumkukelemen0.'; border:TRB;');
					$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; rowspan:'.$jumkukelemen0.'; border:TRB;');
				
				$write->easyCell('', 'align:L; valign:T; border:TRB; rowspan:'.$jumkukelemen0.'; font-size:9;');
				$write->printRow();
			}else{
				$nokrnya=$noel.'.'.$nokrit.'.';
				if ($nokrit==$jumkukelemen0){
					$write->easyCell($nokrnya, 'align:L; valign:T; border:B; font-size:9;');
					$write->easyCell($kr['kriteria'], 'align:L; valign:T; border:RB; font-size:9;');
				}else{
					$write->easyCell($nokrnya, 'align:L; valign:T; font-size:9;');
					$write->easyCell($kr['kriteria'], 'align:L; valign:T; border:R; font-size:9;');
				}
				$write->printRow();
			}
			$nokrit++;
			//end while kriteria =========================
		}
		$noel++;
		$write->endTable(0);
		//end while elemen ==================================

	}
	// garis penutup tabel ============
	$write=new easyTable($pdf, '{190}', 'width:190; align:L; font-family:arial; font-size:12');
	$write->easyCell('', 'align:C; border:T;');
	$write->printRow();
	//=================================
	$write->endTable(5);
	//enc while unitkompetensi ============================================
}

//end while unitkompetensi =============================================

//Umpan Balik Asesmen
/* $sqlgetkeputusan="SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
$getkeputusan=$conn->query($sqlgetkeputusan);
$getk=$getkeputusan->fetch_assoc(); */

$write=new easyTable($pdf, '{50,140}', 'width:190; align:L; font-family:arial; font-size:12');
$write->rowStyle('min-height:20');
$write->easyCell('Umpan Balik untuk Asesi:', 'align:L; border:LTRB; font-style:B; bgcolor:#C6C6C6;');
$write->easyCell('', 'align:L; valign:T; border:TRB;');
$write->printRow();
$write->endTable(5);

$write=new easyTable($pdf, '{30,80,80}', 'width:190; align:L; font-family:arial; font-size:12');
$write->easyCell('Nama:', 'align:L; valign:M; border:LTRB; rowspan:2');
$write->easyCell('Asesi:', 'align:L; valign:T;  border:TR;');
$write->easyCell('Asesor:', 'align:L; valign:T;  border:TR;');
$write->printRow();
$write->easyCell('', 'align:L; border:LBR; font-style:B;');

$write->easyCell('', 'align:L; border:LBR; font-style:B;');
$write->printRow();

$sqlidentitas="SELECT * FROM `identitas`";
$identitas=$conn->query($sqlidentitas);
$iden=$identitas->fetch_assoc();


	$write->easyCell('Tanda Tangan dan Tanggal', 'align:L; border:LTBR; rowspan:2');
	$write->easyCell('', 'align:L; border:TR; font-style:TB;');

	$write->rowStyle('min-height:30');
	$write->easyCell('', 'align:L; border:TR;');
	$write->printRow();
	$write->easyCell('', 'align:L; border:BR;');

	$write->easyCell('', 'align:L; border:BR;');

	$write->printRow();


$write->endTable(0);

$pdf->AliasNbPages();

//output file pdf
$fileoutputnya="Master-FR-IA-01-".$skemakkni.".pdf";
$pdf->Output($fileoutputnya,'D');
 
ob_end_flush();
?>