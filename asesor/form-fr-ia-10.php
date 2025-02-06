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

$sqlgetunitkompetensi="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sq[id]'";
$getunitkompetensi=$conn->query($sqlgetunitkompetensi);
$noku=1;
//while unitkompetensi ==================================================================
while ($unk=$getunitkompetensi->fetch_assoc()){

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
	$write->easyCell('FR.IA.10. KLARIFIKASI BUKTI PIHAK KETIGA', 'align:L;');
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

	$write=new easyTable($pdf, '{190}', 'width:190; align:L; font-family:arial; font-size:12');
	$write->easyCell('PANDUAN BAGI ASESOR', 'align:L; border:LTBR; bgcolor:#C6C6C6; font-style:B;');
	$write->printRow();
	$write->endTable(0);
	$write=new easyTable($pdf, '{190}', 'width:190; align:L; font-family:arial; font-size:12');
	$write->easyCell('Lengkapi formulir ini sesuai dengan pertanyaan/pernyataan dalam tabel ini secara seksama.', 'align:L; border:LRB;');
	$write->printRow();
	$write->endTable(5);

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

	$write=new easyTable($pdf, '{190}', 'width:190; align:L; font-family:arial; font-size:12');
	$write->rowStyle('min-height:15');
	$write->easyCell('Nama Pengawas/penyelia/atasan/orang lain di perusahaan :', 'align:L; valign:T; font-size:12; border:LTBR');
	$write->printRow();
	$write->rowStyle('min-height:15');
	$write->easyCell('Tempat kerja :', 'align:L; valign:T; font-size:12; border:LTBR');
	$write->printRow();
	$write->rowStyle('min-height:15');
	$write->easyCell('Alamat :', 'align:L; valign:T; font-size:12; border:LTBR');
	$write->printRow();
	$write->easyCell('Telepon :', 'align:L; valign:T; font-size:12; border:LTBR');
	$write->printRow();
	$write->endTable(5);

	$write=new easyTable($pdf, '{10,150,15,15}', 'width:190; align:L; font-family:arial; font-size:12');
	$write->easyCell('Pertanyaan', 'align:C; valign:M; font-size:12; colspan:2; border:LTB');
	$write->easyCell('Ya', 'align:C; valign:M; font-size:12; border:LTB');
	$write->easyCell('Tdk', 'align:C; valign:M; font-size:12; border:LTBR');
	$write->printRow();
	$write->easyCell('-', 'align:C; valign:T; font-size:12; border:LTB');
	$write->easyCell('Apakah asesi bekerja dengan mempertimbangkan Kesehatan, Keamanan dan Keselamatan Kerja?', 'align:L; valign:T; font-size:12; border:TBR');
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
	$write->printRow();
	$write->easyCell('-', 'align:C; valign:T; font-size:12; border:LTB');
	$write->easyCell('Apakah asesi berinteraksi dengan harmonis didalam kelompoknya?', 'align:L; valign:T; font-size:12; border:TBR');
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
	$write->printRow();
	$write->easyCell('-', 'align:C; valign:T; font-size:12; border:LTB');
	$write->easyCell('Apakah asesi dapat mengelola tugas-tugas secara bersamaan?', 'align:L; valign:T; font-size:12; border:TBR');
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
	$write->printRow();
	$write->easyCell('-', 'align:C; valign:T; font-size:12; border:LTB');
	$write->easyCell('Apakah asesi dapat dengan cepat beradaptasi dengan peralatan dan lingkungan yang baru?', 'align:L; valign:T; font-size:12; border:TBR');
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
	$write->printRow();
	$write->easyCell('-', 'align:C; valign:T; font-size:12; border:LTB');
	$write->easyCell('Apakah asesi dapat merespon dengan cepat masalah-masalah yang ada di tempat kerjanya?', 'align:L; valign:T; font-size:12; border:TBR');
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
	$write->printRow();
	$write->easyCell('-', 'align:C; valign:T; font-size:12; border:LTB');
	$write->easyCell('Apakah Anda bersedia dihubungi jika verifikasi lebih lanjut dari pernyataan ini diperlukan?', 'align:L; valign:T; font-size:12; border:TBR');
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:TRB;');
	$write->printRow();
	$write->endTable(5);


	$write=new easyTable($pdf, '{70,120}', 'width:190; align:L; font-family:arial; font-size:12');
	$write->rowStyle('min-height:20');
	$write->easyCell('Apa hubungan Anda dengan asesi?', 'align:L; valign:T; font-size:12; font-style:B; border:LTB');
	$write->easyCell('', 'align:L; valign:T; font-size:12; border:LTBR');
	$write->printRow();
	$write->rowStyle('min-height:20');
	$write->easyCell('Berapa lama Anda bekerja dengan asesi?', 'align:L; valign:T; font-size:12; font-style:B; border:LTB');
	$write->easyCell('', 'align:L; valign:T; font-size:12; border:LTBR');
	$write->printRow();
	$write->rowStyle('min-height:20');
	$write->easyCell('Seberapa dekat Anda bekerja dengan asesi di area yang dinilai?', 'align:L; valign:T; font-size:12; font-style:B; border:LTB');
	$write->easyCell('', 'align:L; valign:T; font-size:12; border:LTBR');
	$write->printRow();
	$write->rowStyle('min-height:20');
	$write->easyCell('Apa pengalaman teknis dan / atau kualifikasi Anda di bidang yang dinilai? (termasuk asesmen atau kualifikasi pelatihan)', 'align:L; valign:T; font-size:12; font-style:B; border:LTB');
	$write->easyCell('', 'align:L; valign:T; font-size:12; border:LTBR');
	$write->printRow();
	$write->endTable(5);

	$write=new easyTable($pdf, '{120,70}', 'width:190; align:L; font-family:arial; font-size:12');
	$write->rowStyle('min-height:20');
	$write->easyCell('Secara keseluruhan, apakah Anda yakin asesi melakukan sesuai standar yang diminta oleh unit kompetensi secara konsisten?', 'align:L; valign:T; font-size:12; font-style:B; border:LTB');
	$write->easyCell('', 'align:L; valign:T; font-size:12; border:LTBR');
	$write->printRow();
	$write->rowStyle('min-height:20');
	$write->easyCell('Identifikasi kebutuhan pelatihan lebih lanjut untuk asesi:', 'align:L; valign:T; font-size:12; colspan:2; font-style:B; border:LTBR');
	$write->printRow();
	$write->rowStyle('min-height:30');
	$write->easyCell('Ada komentar lain:', 'align:L; valign:T; font-size:12; colspan:2; font-style:B; border:LTBR');
	$write->printRow();
	$write->rowStyle('min-height:30');
	$write->easyCell('Tanda tangan Asesor:', 'align:L; valign:T; font-size:12; font-style:B; border:LTB');
	$write->easyCell('Tanggal:', 'align:C; valign:T; font-size:12; font-style:B; border:LTBR');
	$write->printRow();
	$write->easyCell('Diadopsi dari templat yang disediakan di Departemen Pendidikan dan Pelatihan, Australia. Merancang alat asesmen untuk hasil yang berkualitas di VET. 2008', 'align:L; valign:T; font-size:12; colspan:2; font-style:I;');
	$write->printRow();
	$write->endTable(5);

}

//end while unitkompetensi =============================================


$pdf->AliasNbPages();

//output file pdf
$fileoutputnya="FR-IA-10-".$skemakkni."-".$_GET['idj']."-".$_GET['ida'].".pdf";
$pdf->Output($fileoutputnya,'I');
 
ob_end_flush();
?>