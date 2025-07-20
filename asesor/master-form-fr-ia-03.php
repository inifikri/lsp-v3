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
	$write->easyCell('FR.IA.03. PERTANYAAN UNTUK MENDUKUNG OBSERVASI', 'align:L;');
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
	$write->easyCell('Formulir ini diisi pada saat asesor akan melakukan asesmen dengan metode observasi demonstrasi', 'align:L; border:R;');
	$write->printRow();
	$write->easyCell(chr(149), 'align:C; border:L;');
	$write->easyCell('Pertanyaan dibuat dengan tujuan untuk menggali, dapat berisi pertanyaan yang berkaitan dengan dimensi kompetensi, batasan variabel dan aspek kritis.', 'align:L; border:R;');
	$write->printRow();
	$write->easyCell(chr(149), 'align:C; border:LB;');
	$write->easyCell('Tanggapan asesi dapat ditulis pada kolom tanggapan, atau dapat langsung mengisi respon dengan ya atau tidak.', 'align:L; border:RB;');
	$write->printRow();
	$write->endTable(5);

$sqlgetunitkompetensi="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sq[id]'";
$getunitkompetensi=$conn->query($sqlgetunitkompetensi);
$noku=1;
//while unitkompetensi ==================================================================
$write=new easyTable($pdf, '{40, 30, 5, 115}', 'width:190; align:L; font-style:B; font-family:arial; font-size:10');
while ($unk=$getunitkompetensi->fetch_assoc()){
	$write->easyCell('Unit Kompetensi', 'align:L; rowspan:2; border:LTBR');
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
$write->easyCell('Pertanyaan', 'align:C; valign:M; font-size:12; font-style:B; colspan:2; rowspan:2; border:LTBR');
$write->easyCell('Rekomendasi', 'align:C; valign:M; font-size:12; font-style:B; colspan:2; border:LTBR');
$write->printRow();
$write->easyCell('K', 'align:C; valign:M; font-size:12; font-style:B; border:LTBR');
$write->easyCell('BK', 'align:C; valign:M; font-size:12; font-style:B; border:LTBR');
$write->printRow();

$sqlgetunitkompetensib="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sq[id]'";
$getunitkompetensib=$conn->query($sqlgetunitkompetensib);
$nopp=1;
while ($unkb=$getunitkompetensib->fetch_assoc()){
	$sqlgetpertanyaan="SELECT * FROM `skema_pertanyaanpendukung` WHERE `id_unitkompetensi`='$unkb[id]' ORDER BY `id` ASC";
	$getpertanyaan=$conn->query($sqlgetpertanyaan);
	while ($gpp=$getpertanyaan->fetch_assoc()){
		if (!empty($gpp['pertanyaan'])){
			/* $sqlcekjawaban="SELECT * FROM `asesmen_ia03` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='$gpp[id]'";
			$cekjawaban=$conn->query($sqlcekjawaban);
			$jjw=$cekjawaban->fetch_assoc(); */
			
			$write->easyCell($nopp.'.', 'align:C; valign:T; font-size:11; font-style:B; bgcolor:#C6C6C6; border:LTBR');
			$write->easyCell($gpp['pertanyaan'], 'align:L; valign:T; font-size:12; border:TBR');
			
					$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
					$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRBR;');
					$write->printRow();

			$write->rowStyle('min-height:20');
			$write->easyCell('Tanggapan:
', 'align:L; valign:T; font-size:11; font-style:B; colspan:2; border:LTBR');
			$write->easyCell('', 'align:C; valign:T; border:TRB;');
			$write->easyCell('', 'align:C; valign:T; border:TRBR;');
			$write->printRow();
			$nopp++;
		}
	}

}
$write->endTable(0);

//end while unitkompetensi =============================================

	$write=new easyTable($pdf, '{10,150,15,15}', 'width:190; align:L; font-family:arial; font-size:12');
	$write->rowStyle('min-height:20');

	/* $sqlgetkeputusan="SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
	$getkeputusan=$conn->query($sqlgetkeputusan);
	$getk=$getkeputusan->fetch_assoc(); */
	
	$write->easyCell('Umpan balik asesi:
', 'align:L; valign:T; font-size:11; font-style:B; colspan:4; border:LTBR');
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
	//$write->rowStyle('min-height:30');
	$write->easyCell('Tanda Tangan dan Tanggal', 'align:L; border:LTBR; rowspan:2');
	$write->easyCell('', 'align:L; border:TR; font-style:T;');
	$write->easyCell('', 'align:L; border:TR; font-style:T;');
	$write->printRow();

// tandatangan asesi
$sqlidentitas="SELECT * FROM `identitas`";
$identitas=$conn->query($sqlidentitas);
$iden=$identitas->fetch_assoc();

	$write->easyCell('', 'align:L; valign:T; font-size:10; font-style:B; border:BR;');

// tandatangan asesor

	$write->easyCell('', 'align:L; valign:T; font-size:10; font-style:B; border:BR;');

$write->printRow();
$write->easyCell('*) Bila diperlukan', 'align:L; colspan:3');
$write->printRow();
$write->easyCell('Diadopsi dari templat yang disediakan di Departemen Pendidikan dan Pelatihan, Australia. Merancang alat asesmen untuk hasil yang berkualitas di VET. 2008', 'align:L; font-style:I; valign:T; colspan:3');
$write->printRow();
$write->endTable(5);


$pdf->AliasNbPages();

//output file pdf
$fileoutputnya="Master-FR-IA-03-".$skemakkni.".pdf";
$pdf->Output($fileoutputnya,'D');
 
ob_end_flush();
?>