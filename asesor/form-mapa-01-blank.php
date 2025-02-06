<?php
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
$write->easyCell('FR.MAPA.01 - MERENCANAKAN AKTIVITAS DAN PROSES ASESMEN', 'align:L;');
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

$write=new easyTable($pdf, '{10,30,10,20,10,55,10,45}', 'width:190; align:L font-family:arial; font-size:12');
$write->easyCell('1.', 'align:L; border:LTB; font-style:B; bgcolor:#C6C6C6;');
$write->easyCell('Menentukan Pendekatan Asesmen', 'align:L; font-size:10; font-style:B; border:TBR; colspan:7; bgcolor:#C6C6C6;');
$write->printRow();
$write->easyCell('1.1.', 'align:L; font-size:10; border:LTR');
$write->easyCell('Kandidat', 'align:L; font-size:10; border:LTR');
$write->easyCell('', 'img:../images/unchecked.jpg, w4, h4; align:C; valign:M; border:T;');
$write->easyCell('Hasil pelatihan dan / atau pendidikan:', 'align:L; font-size:10; border:TR; colspan:5;');
$write->printRow();
$write->easyCell('', 'align:L; font-size:10; border:LR');
$write->easyCell('', 'align:L; font-size:10; border:LR');
$write->easyCell('', 'img:../images/unchecked.jpg, w4, h4; align:C; valign:M; border:L;');
$write->easyCell('Pekerja berpengalaman', 'align:L; font-size:10; border:R; colspan:5;');
$write->printRow();
$write->easyCell('', 'align:L; font-size:10; border:LR');
$write->easyCell('', 'align:L; font-size:10; border:LBR');
$write->easyCell('', 'img:../images/unchecked.jpg, w4, h4; align:C; valign:M; border:LB;');
$write->easyCell('Pelatihan / belajar mandiri', 'align:L; font-size:10; border:RB; colspan:5;');
$write->printRow();
$write->easyCell('', 'align:L; font-size:10; border:LR');
$write->easyCell('Tujuan Asesmen', 'align:L; font-size:10; border:LTR');
$write->easyCell('', 'img:../images/unchecked.jpg, w4, h4; align:C; valign:M; border:T;');
$write->easyCell('Sertifikasi', 'align:L; font-size:10; border:TR; colspan:5;');
$write->printRow();
$write->easyCell('', 'align:L; font-size:10; border:LR');
$write->easyCell('', 'align:L; font-size:10; border:LR');
$write->easyCell('', 'img:../images/unchecked.jpg, w4, h4; align:C; valign:M; border:L;');
$write->easyCell('Sertifikasi Ulang', 'align:L; font-size:10; border:R; colspan:5;');
$write->printRow();
$write->easyCell('', 'align:L; font-size:10; border:LR');
$write->easyCell('', 'align:L; font-size:10; border:LR');
$write->easyCell('', 'img:../images/unchecked.jpg, w4, h4; align:C; valign:M; border:L;');
$write->easyCell('Pengakuan Kompetensi Terkini (PKT)', 'align:L; font-size:10; border:R; colspan:5;');
$write->printRow();
$write->easyCell('', 'align:L; font-size:10; border:LR');
$write->easyCell('', 'align:L; font-size:10; border:LR');
$write->easyCell('', 'img:../images/unchecked.jpg, w4, h4; align:C; valign:M; border:L;');
$write->easyCell('Rekognisi Pembelajaran Lampau', 'align:L; font-size:10; border:R; colspan:5;');
$write->printRow();
$write->easyCell('', 'align:L; font-size:10; border:LR');
$write->easyCell('', 'align:L; font-size:10; border:LBR');
$write->easyCell('', 'img:../images/unchecked.jpg, w4, h4; align:C; valign:M; border:LB;');
$write->easyCell('Lainnya', 'align:L; font-size:10; border:RB; colspan:5;');
$write->printRow();

$write->easyCell('', 'align:L; font-size:10; border:LR; rowspan:8;');
$write->easyCell('Konteks Asesmen:', 'align:L; font-size:10; border:LTR; rowspan:8;');

$write->easyCell('Lingkungan', 'align:L; font-size:10; border:LTR; colspan:2;');
$write->easyCell('', 'img:../images/unchecked.jpg, w4, h4; align:C; valign:M; border:T;');
$write->easyCell('Tempat kerja nyata', 'align:L; valign:M; font-size:10; border:TR;');
$write->easyCell('', 'img:../images/unchecked.jpg, w4, h4; align:C; valign:M; border:T;');
$write->easyCell('Tempat kerja simulasi', 'align:L; valign:M; font-size:10; border:TR;');
$write->printRow();
$write->easyCell('Peluang untuk mengumpulkan bukti dalam sejumlah situasi', 'align:L; font-size:10; border:LTR; colspan:2;');
$write->easyCell('', 'img:../images/unchecked.jpg, w4, h4; align:C; valign:M; border:T;');
$write->easyCell('Tersedia', 'align:L; valign:M; font-size:10; border:TR;');
$write->easyCell('', 'img:../images/unchecked.jpg, w4, h4; align:C; valign:M; border:T;');
$write->easyCell('Terbatas', 'align:L; valign:M; font-size:10; border:TR;');
$write->printRow();
$write->easyCell('Hubungan antara standar kompetensi dan:', 'align:L; font-size:10; border:LTR; colspan:2; rowspan:3');
$write->easyCell('', 'img:../images/unchecked.jpg, w4, h4; align:C; valign:M; border:T;');
$write->easyCell('Bukti untuk mendukung asesmen / RPL:', 'align:L; valign:M; font-size:10; border:T; colspan:2');
$write->easyCell('', 'img:../images/faceicon.jpg, w20, h4;align:L; valign:M; font-size:10; border:TR;');
$write->printRow();
$write->easyCell('', 'img:../images/unchecked.jpg, w4, h4; align:C; valign:M; border:T;');
$write->easyCell('Aktivitas kerja di tempat kerja Asesi:', 'align:L; valign:M; font-size:10; border:T; colspan:2');
$write->easyCell('', 'img:../images/faceicon.jpg, w20, h4;align:L; valign:M; font-size:10; border:TR;');
$write->printRow();
$write->easyCell('', 'img:../images/unchecked.jpg, w4, h4; align:C; valign:M; border:T;');
$write->easyCell('Kegiatan Pembelajaran:', 'align:L; valign:M; font-size:10; border:T; colspan:2');
$write->easyCell('', 'img:../images/faceicon.jpg, w20, h4;align:L; valign:M; font-size:10; border:TR;');
$write->printRow();
$write->easyCell('Siapa yang melakukan asesmen / RPL', 'align:L; font-size:10; border:LTR; colspan:2; rowspan:3');
$write->easyCell('', 'img:../images/unchecked.jpg, w4, h4; align:C; valign:M; border:T;');
$write->easyCell('Lembaga Sertifikasi', 'align:L; valign:M; font-size:10; border:TR; colspan:3');
$write->printRow();
$write->easyCell('', 'img:../images/unchecked.jpg, w4, h4; align:C; valign:M; border:T;');
$write->easyCell('Organisasi Pelatihan', 'align:L; valign:M; font-size:10; border:TR; colspan:3');
$write->printRow();
$write->easyCell('', 'img:../images/unchecked.jpg, w4, h4; align:C; valign:M; border:T;');
$write->easyCell('Asesor Perusahaan', 'align:L; valign:M; font-size:10; border:TR; colspan:3');
$write->printRow();

$write->easyCell('', 'align:L; font-size:10; border:LR; rowspan:4;');
$write->easyCell('Konfirmasi dengan orang yang relevan', 'align:L; font-size:10; border:LTR; rowspan:4;');
$write->easyCell('', 'img:../images/unchecked.jpg, w4, h4; align:C; valign:M; border:T;');
$write->easyCell('Manajer sertifikasi LSP', 'align:L; valign:M; font-size:10; border:TR; colspan:5');
$write->printRow();
$write->easyCell('', 'img:../images/unchecked.jpg, w4, h4; align:C; valign:M; border:T;');
$write->easyCell('Master Asesor / Master Trainer / Asesor Utama Kompetensi', 'align:L; valign:M; font-size:10; border:TR; colspan:5');
$write->printRow();
$write->easyCell('', 'img:../images/unchecked.jpg, w4, h4; align:C; valign:M; border:T;');
$write->easyCell('Manajer Pelatihan Lembaga Training terakreditasi / Lembaga Training terdaftar', 'align:L; valign:M; font-size:10; border:TR; colspan:5');
$write->printRow();
$write->easyCell('', 'img:../images/unchecked.jpg, w4, h4; align:C; valign:M; border:T;');
$write->easyCell('Lainnya:', 'align:L; valign:M; font-size:10; border:TR; colspan:5');
$write->printRow();

$write->easyCell('1.2.', 'align:L; valign:T; font-size:10; border:LTRB; rowspan:5;');
$write->easyCell('Tolok Ukur Asesmen', 'align:L; valign:T; font-size:10; border:LTRB; rowspan:5;');
$write->easyCell('', 'img:../images/unchecked.jpg, w4, h4; align:C; valign:M; border:T;');
$write->easyCell('Standar Kompetensi: SKKNI', 'align:L; valign:M; font-size:10; border:TR; colspan:5');
$write->printRow();
$write->easyCell('', 'img:../images/unchecked.jpg, w4, h4; align:C; valign:M; border:T;');
$write->easyCell('Kriteria asesmen dari kurikulum pelatihan', 'align:L; valign:M; font-size:10; border:TR; colspan:5');
$write->printRow();
$write->easyCell('', 'img:../images/unchecked.jpg, w4, h4; align:C; valign:M; border:T;');
$write->easyCell('Spesifikasi kinerja suatu perusahaan atau industri:', 'align:L; valign:M; font-size:10; border:TR; colspan:5');
$write->printRow();
$write->easyCell('', 'img:../images/unchecked.jpg, w4, h4; align:C; valign:M; border:T;');
$write->easyCell('Spesifikasi Produk:', 'align:L; valign:M; font-size:10; border:TR; colspan:5');
$write->printRow();
$write->easyCell('', 'img:../images/unchecked.jpg, w4, h4; align:C; valign:M; border:TB;');
$write->easyCell('Pedoman khusus:', 'align:L; valign:M; font-size:10; border:TRB; colspan:5');
$write->printRow();

$write->endTable(5);

$write=new easyTable($pdf, '{10,30,10,20,10,55,10,45}', 'width:190; align:L font-family:arial; font-size:12');
$write->easyCell('2.', 'align:L; border:LTB; font-style:B; bgcolor:#C6C6C6;');
$write->easyCell('Mempersiapkan Rencana Asesmen', 'align:L; font-size:10; font-style:B; border:TBR; colspan:7; bgcolor:#C6C6C6;');
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

	$write=new easyTable($pdf, '{10, 30, 36, 8, 8, 8, 15, 15, 15, 15, 15, 15}', 'width:190; align:L; font-family:arial; font-size:12');
	$write->easyCell('Kriteria Unjuk Kerja', 'align:C; valign:M; border:LTBR; font-style:B; rowspan:2; colspan:2');
	$write->easyCell('Bukti-Bukti
(Kinerja, Produk, Portofolio, dan / atau Hafalan) diidentifikasi berdasarkan Kriteria Unjuk Kerja dan Pendekatan Asesmen', 'align:C; rowspan:2; border:LTBR; font-style:B');
	$write->easyCell('Jenis Bukti', 'align:C; valign:M; colspan:3; border:LTBR; font-style:B');
	$write->easyCell('Metode dan Perangkat Asesmen
CL (Ceklis Observasi/ Lembar Periksa), DIT (Daftar Instruksi Terstruktur), DPL (Daftar Pertanyaan Lisan), DPT (Daftar Pertanyaan Tertulis), VP (Verifikasi Portofolio), CUP (Ceklis Ulasan Produk), PW (Pertanyaan Wawancara)', 'align:C; valign:M; colspan:6; border:LTBR; font-style:B');
	$write->printRow();
	$write->easyCell('L', 'align:C; valign:M; border:LTBR; font-style:B');
	$write->easyCell('TL', 'align:C; valign:M; border:LTBR; font-style:B');
	$write->easyCell('T', 'align:C; valign:M; border:LTBR; font-style:B');
	$write->easyCell('', 'img:../images/mapa01-1.jpg, w15, h30; align:C; valign:M; border:LTBR; font-style:B');
	$write->easyCell('', 'img:../images/mapa01-2.jpg, w15, h30; align:C; valign:M; border:LTBR; font-style:B');
	$write->easyCell('', 'img:../images/mapa01-3.jpg, w15, h30; align:C; valign:M; border:LTBR; font-style:B');
	$write->easyCell('', 'img:../images/mapa01-4.jpg, w15, h30; align:C; valign:M; border:LTBR; font-style:B');
	$write->easyCell('', 'img:../images/mapa01-5.jpg, w15, h30; align:C; valign:M; border:LTBR; font-style:B');
	$write->easyCell('', 'img:../images/mapa01-6.jpg, w8, h30; align:C; valign:M; border:LTBR; font-style:B');
	$write->printRow();

	$sqlgetel="SELECT * FROM `elemen_kompetensi` WHERE `id_unitkompetensi`='$unk[id]' ORDER BY `id` ASC";
	$getel=$conn->query($sqlgetel);
	$noel=1;
	while ($gel=$getel->fetch_assoc()){
		$elemennya="Elemen: ".$noel.". ".$gel['elemen_kompetensi'];
		$write->easyCell($elemennya, 'align:L; border:LTBR; font-style:B; colspan:12');
		$write->printRow();
		$sqlgetkuk="SELECT * FROM `kriteria_unjukkerja` WHERE `id_elemenkompetensi`='$gel[id]' ORDER BY `id` ASC";
		$getkuk=$conn->query($sqlgetkuk);
		$nokuk=1;
		while ($kuk=$getkuk->fetch_assoc()){
			$write->easyCell($noel.'.'.$nokuk.'.', 'align:C; border:LTBR; font-size:10');
			$write->easyCell($kuk['kriteria'], 'align:L; border:LTBR; font-size:10');
			$write->easyCell('', 'align:C; border:LTBR; font-style:B');
			//
			$write->easyCell('', 'align:C; border:LTBR; font-style:B');
			$write->easyCell('', 'align:C; border:LTBR; font-style:B');
			$write->easyCell('', 'align:C; border:LTBR; font-style:B');
			//
			$write->easyCell('', 'align:C; border:LTBR; font-style:B');
			$write->easyCell('', 'align:C; border:LTBR; font-style:B');
			$write->easyCell('', 'align:C; border:LTBR; font-style:B');
			$write->easyCell('', 'align:C; border:LTBR; font-style:B');
			$write->easyCell('', 'align:C; border:LTBR; font-style:B');
			$write->easyCell('', 'align:C; border:LTBR; font-style:B');
			$write->printRow();
			$nokuk++;
		}
		$noel++;

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

$write=new easyTable($pdf, '{10,7,73,100}', 'width:190; align:L font-family:arial; font-size:12');
$write->easyCell('3.', 'align:L; border:LTB; font-style:B; bgcolor:#C6C6C6;');
$write->easyCell('Mengidentifikasi Persyaratan Modifikasi dan Kontekstualisasi:', 'align:L; font-size:10; font-style:B; border:TBR; colspan:3; bgcolor:#C6C6C6;');
$write->printRow();
$write->easyCell('3.1.', 'align:L; border:LTB; font-size:12;');
$write->easyCell('a.', 'align:L; border:TB; font-size:12;');
$write->easyCell('Karakteristik Kandidat:', 'align:L; font-size:12; border:TBR;');
$write->easyCell('Ada / tidak ada* karakteristik khusus Kandidat
Jika Ada, tuliskan', 'align:L; font-size:12; border:TBR;');
$write->printRow();
$write->easyCell('', 'align:L; border:LTB; font-size:12;');
$write->easyCell('b.', 'align:L; border:TB; font-size:12;');
$write->easyCell('Kebutuhan kontekstualisasi terkait tempat kerja:', 'align:L; font-size:12; border:TBR;');
$write->easyCell('Ada / tidak ada* karakteristik khusus Kandidat
Jika Ada, tuliskan', 'align:L; font-size:12; border:TBR;');
$write->printRow();
$write->easyCell('3.2.', 'align:L; border:LTB; font-size:12;');
$write->easyCell('Saran yang diberikan oleh paket pelatihan atau pengembang pelatihan', 'align:L; font-size:12; border:TBR; colspan:2');
$write->easyCell('Ada / tidak ada* karakteristik khusus Kandidat
Jika Ada, tuliskan', 'align:L; font-size:12; border:TBR;');
$write->printRow();
$write->easyCell('3.3.', 'align:L; border:LTB; font-size:12;');
$write->easyCell('Penyesuaian perangkat asesmen terkait kebutuhan kontekstualisasi', 'align:L; font-size:12; border:TBR; colspan:2');
$write->easyCell('Ada / tidak ada* karakteristik khusus Kandidat
Jika Ada, tuliskan', 'align:L; font-size:12; border:TBR;');
$write->printRow();
$write->easyCell('3.4.', 'align:L; border:LTB; font-size:12;');
$write->easyCell('Peluang untuk kegiatan asesmen terintegrasi dan mencatat setiap perubahan yang diperlukan untuk alat asesmen', 'align:L; font-size:12; border:TBR; colspan:2');
$write->easyCell('Ada / tidak ada* karakteristik khusus Kandidat
Jika Ada, tuliskan', 'align:L; font-size:12; border:TBR;');
$write->printRow();
$write->easyCell('*Coret yang tidak perlu', 'align:L; font-size:8; colspan:4');
$write->printRow();

$write->endTable(5);


$write=new easyTable($pdf, '{10,140,50}', 'width:190; align:L font-family:arial; font-size:12');
$write->easyCell('Konfirmasi dengan orang yang relevan', 'align:L; font-style:B; colspan:3');
$write->printRow();
$write->easyCell('Orang yang Relevan', 'align:C; border:LTB; font-size:12; font-style:B; bgcolor:#C6C6C6; colspan:2');
$write->easyCell('Tandatangan', 'align:C; font-size:12; font-style:B; border:LTBR; bgcolor:#C6C6C6;');
$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell('', 'img:../images/unchecked.jpg, w4, h4; align:C; valign:M; border:LT;');
$write->easyCell('Manajer sertifikasi LSP', 'align:L; valign:M; font-size:12; border:TBR;');
$write->easyCell('', 'align:L; valign:M; font-size:12; font-style:B; border:TBR;');
$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell('', 'img:../images/unchecked.jpg, w4, h4; align:C; valign:M; border:LT;');
$write->easyCell('Master Asesor / Master Trainer / Lead Asesor/ Asesor Utama Kompetensi', 'align:L; valign:M; font-size:12; border:TBR;');
$write->easyCell('', 'align:L; valign:M; font-size:12; font-style:B; border:TBR;');
$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell('', 'img:../images/unchecked.jpg, w4, h4; align:C; valign:M; border:LT;');
$write->easyCell('Manajer pelatihan Lembaga Training terakreditasi / Lembaga Training terdaftar', 'align:L; valign:M; font-size:12; border:TBR;');
$write->easyCell('', 'align:L; valign:M; font-size:12; font-style:B; border:TBR;');
$write->printRow();
$write->rowStyle('min-height:10');
$write->easyCell('', 'img:../images/unchecked.jpg, w4, h4; align:C; valign:M; border:LTB;');
$write->easyCell('Lainnya:', 'align:L; valign:M; font-size:12; border:TBR;');
$write->easyCell('', 'align:L; valign:M; font-size:12; font-style:B; border:TBR;');
$write->printRow();

$write->endTable(5);
$write=new easyTable($pdf, '{70,70,50}', 'width:190; align:L font-family:arial; font-size:12');
$write->easyCell('Penyusun dan Validator', 'align:L; font-style:B; colspan:3');
$write->printRow();
$write->easyCell('Nama', 'align:C; border:LTB; font-size:12; font-style:B; bgcolor:#C6C6C6;');
$write->easyCell('Jabatan', 'align:C; border:LTB; font-size:12; font-style:B; bgcolor:#C6C6C6;');
$write->easyCell('Tanggal dan Tandatangan', 'align:C; font-size:12; font-style:B; border:LTBR; bgcolor:#C6C6C6;');
$write->printRow();
$write->rowStyle('min-height:20');
$write->easyCell('', 'align:L; valign:M; border:LTR;');
$write->easyCell('Penyusun', 'align:L; valign:M; font-size:12; border:TBR;');
$write->easyCell('', 'align:L; valign:M; font-size:12; font-style:B; border:TBR;');
$write->printRow();
$write->rowStyle('min-height:20');
$write->easyCell('', 'align:L; valign:M; border:LTRB;');
$write->easyCell('Validator', 'align:L; valign:M; font-size:12; border:TBR;');
$write->easyCell('', 'align:L; valign:M; font-size:12; font-style:B; border:TBR;');
$write->printRow();

$write->endTable(5);

$pdf->AliasNbPages();

//output file pdf
$fileoutputnya="FR-MAPA-01-".$skemakkni.".pdf";
$pdf->Output($fileoutputnya,'I');
 

?>