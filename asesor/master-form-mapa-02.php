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

$as=$asesi->fetch_assoc();

$sqljadwal="SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";

$jadwal=$conn->query($sqljadwal);

$jdq=$jadwal->fetch_assoc();

$tgl_cetak = tgl_indo($jdq['tgl_asesmen']);



$sqltuk="SELECT * FROM `tuk` WHERE `id`='$jdq[tempat_asesmen]'";

$tuk=$conn->query($sqltuk);

$tq=$tuk->fetch_assoc();*/

$sqllsp="SELECT * FROM `lsp` ORDER BY `id` ASC LIMIT 1";

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



/* $sqlwil1b="SELECT * FROM `data_wilayah` WHERE `id_wil`='$tq[id_wilayah]'";

$wilayah1b=$conn->query($sqlwil1b);

$wil1b=$wilayah1b->fetch_assoc();

$sqlwil2b="SELECT * FROM `data_wilayah` WHERE `id_wil`='$wil1b[id_induk_wilayah]'";

$wilayah2b=$conn->query($sqlwil2b);

$wil2b=$wilayah2b->fetch_assoc();

$sqlwil3b="SELECT * FROM `data_wilayah` WHERE `id_wil`='$wil2b[id_induk_wilayah]'";

$wilayah3b=$conn->query($sqlwil3b);

$wil3b=$wilayah3b->fetch_assoc(); */





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

//$tampilperiode="Periode ".$jdq['periode']." Tahun ".$jdq['tahun']." Gelombang ".$jdq['gelombang'];

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

$write->easyCell('FR.MAPA.02- PETA INSTRUMEN ASESSMEN HASIL PENDEKATAN ASESMEN DAN PERENCANAAN ASESMEN*', 'align:L;');

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



$sqlgetunitkompetensi="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sq[id]'";

$getunitkompetensi=$conn->query($sqlgetunitkompetensi);

$noku=1;

//while unitkompetensi ==================================================================

while ($unk=$getunitkompetensi->fetch_assoc()){



	$write=new easyTable($pdf, '{50, 20, 5, 115}', 'width:190; align:L; font-style:B; font-family:arial; font-size:12');

	$write->easyCell('Unit Kompetensi', 'align:L; rowspan:2; border:LTBR');

	$write->easyCell('Kode Unit', 'align:L; font-size:10; border:LTBR');

	$write->easyCell(':', 'align:C; font-size:10; border:LTBR');

	$write->easyCell($unk['kode_unit'], 'align:L; font-size:10; border:LTBR');

	$write->printRow();

	$write->easyCell('Judul Unit', 'align:L; font-size:10; border:LTBR');

	$write->easyCell(':', 'align:C; font-size:10; border:LTBR');

	$write->easyCell($unk['judul'], 'align:L; font-size:10; border:LTBR');

	$write->printRow();

	$write->endTable(5);



	$write=new easyTable($pdf, '{10, 130, 10, 10, 10, 10, 10}', 'width:190; align:L; font-family:arial; font-size:12');

	$write->easyCell('No.', 'align:C; rowspan:2; border:LTBR; font-style:B');

	$write->easyCell('MUK', 'align:C; rowspan:2; border:LTBR; font-style:B');

	$write->easyCell('Potensi Asesi**', 'align:C; colspan:5; border:LTBR; font-style:B');

	$write->printRow();

	$write->easyCell('1', 'align:C; border:LTBR; font-style:B');

	$write->easyCell('2', 'align:C; border:LTBR; font-style:B');

	$write->easyCell('3', 'align:C; border:LTBR; font-style:B');

	$write->easyCell('4', 'align:C; border:LTBR; font-style:B');

	$write->easyCell('5', 'align:C; border:LTBR; font-style:B');

	$write->printRow();

	$sqlgetmuk="SELECT * FROM `muk` WHERE `aktif`='Y' ORDER BY `id` ASC";

	$getmuk=$conn->query($sqlgetmuk);

	$nomuk=1;

	while ($gmuk=$getmuk->fetch_assoc()){

		$sqlgetmukx="SELECT * FROM `skema_mapa2` WHERE `id_skema`='$_GET[idsk]' AND `id_unitkompetensi`='$unk[id]' AND `id_muk`='$gmuk[id]'";

		$getmukx=$conn->query($sqlgetmukx);

		$gtmukx=$getmukx->fetch_assoc();

		

		$write->easyCell($nomuk, 'align:C; border:LTBR; font-size:11');

		$write->easyCell($gmuk['judul'], 'align:L; border:LTBR; font-size:11');

		if ($gtmukx['kandidat1']=="1"){

			$write->easyCell('', 'img:../images/checked.jpg, w4, h4; align:C; valign:M; border:TRB;');

		}else{

			$write->easyCell('', 'img:../images/unchecked.jpg, w4, h4; align:C; valign:M; border:TRB;');

		}

		if ($gtmukx['kandidat2']=="1"){

			$write->easyCell('', 'img:../images/checked.jpg, w4, h4; align:C; valign:M; border:TRB;');

		}else{

			$write->easyCell('', 'img:../images/unchecked.jpg, w4, h4; align:C; valign:M; border:TRB;');

		}

		if ($gtmukx['kandidat3']=="1"){

			$write->easyCell('', 'img:../images/checked.jpg, w4, h4; align:C; valign:M; border:TRB;');

		}else{

			$write->easyCell('', 'img:../images/unchecked.jpg, w4, h4; align:C; valign:M; border:TRB;');

		}

		if ($gtmukx['kandidat4']=="1"){

			$write->easyCell('', 'img:../images/checked.jpg, w4, h4; align:C; valign:M; border:TRB;');

		}else{

			$write->easyCell('', 'img:../images/unchecked.jpg, w4, h4; align:C; valign:M; border:TRB;');

		}

		if ($gtmukx['kandidat5']=="1"){

			$write->easyCell('', 'img:../images/checked.jpg, w4, h4; align:C; valign:M; border:TRB;');

		}else{

			$write->easyCell('', 'img:../images/unchecked.jpg, w4, h4; align:C; valign:M; border:TRB;');

		}



		$write->printRow();

		$nomuk++;



	}

	$write->endTable(5);



	//enc while unitkompetensi ============================================

	// garis penutup tabel ============

	/*$write=new easyTable($pdf, '{190}', 'width:190; align:L; font-family:arial; font-size:12');

	$write->easyCell('', 'align:C; border:T;');

	$write->printRow();

	$write->endTable(5);*/

	//=================================

}



//end while unitkompetensi =============================================



$write=new easyTable($pdf, '{5,185}', 'width:190; align:L; font-family:arial; font-size:10');

$write->easyCell('*) diisi berdasarkan hasil penentuan pendekatan asesmen dan perencanaan asesmen', 'align:L; colspan:2;');

$write->printRow();

$write->easyCell('**) Keterangan:', 'align:L; colspan:2;');

$write->printRow();

$sqlgetkatkandidat="SELECT * FROM `kategori_kandidat` ORDER BY `kode` ASC";

$getkatkandidat=$conn->query($sqlgetkatkandidat);

$nogkkan=1;

while ($gkkan=$getkatkandidat->fetch_assoc()){

	$write->easyCell($nogkkan.'.', 'align:L;');

	$write->easyCell($gkkan['deskripsi'], 'align:L;');

	$write->printRow();

	$nogkkan++;

}



$write->endTable(0);



$pdf->AliasNbPages();



//output file pdf

$fileoutputnya="Master-FR-MAPA-02-".$skemakkni.".pdf";

$pdf->Output($fileoutputnya,'D');

 

ob_end_flush();

?>