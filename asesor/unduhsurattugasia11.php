<?php
ob_start();
include 'fpdf-easytable-master/fpdf.php';
include 'fpdf-easytable-master/exfpdf.php';
include 'fpdf-easytable-master/easyTable.php';
include "../config/koneksi.php";
include "../config/library.php";
include "../config/fungsi_indotgl.php";

$sqlasesi="SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`='$_GET[idj]'";
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

$write=new easyTable($pdf, 1, 'width:190;  font-size:12;font-family:arial;');
$write->easyCell('SURAT PERINTAH TUGAS', 'font-style:B,U; align:C;');
$write->printRow();
$nomorsurattugas="No. ".$jdq['no_surattugasia11'];
$write->easyCell($nomorsurattugas, 'align:C;');
$write->printRow();
$write->endTable(5);
$namakota=trim($wil2b['nm_wil']);
$tglcetakkota = ucwords(strtolower($namakota)).", ".$tgl_cetak;
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


$write=new easyTable($pdf, 1, 'width:190;  font-size:10;font-family:arial;');
$write->easyCell('DIPERINTAHKAN:', 'font-style:B; align:C;');
$write->printRow();
$write->endTable(0);

$write=new easyTable($pdf, '{70, 5, 115}', 'width:190; align:L; font-size:10;font-family:arial;');
$write->easyCell('Kepada');
$write->easyCell(':');
$write->easyCell('');
$write->printRow();

$sqlasesituk="SELECT DISTINCT `peninjau_ia11` FROM `asesi_asesmen` WHERE `id_jadwal`='$_GET[idj]' ORDER BY `peninjau_ia11` ASC";
$asesituk=$conn->query($sqlasesituk);
$noastuk=1;
while ($astuk=$asesituk->fetch_assoc()){

	$sqlasesor="SELECT * FROM `asesor` WHERE `id`='$astuk[peninjau_ia11]'";
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
	if ($noastuk==1){
		$write->easyCell('Nama');
		$write->easyCell(':');
		$write->easyCell($noastuk.". ".$namaasesor." (".$asr['no_induk'].")", 'font-style:B;');
	}else{
		$write->easyCell('');
		$write->easyCell('');
		$write->easyCell($noastuk.". ".$namaasesor." (".$asr['no_induk'].")", 'font-style:B;');
	}
	$write->printRow();
	$noastuk++;
}
$write->endTable(0);

$write=new easyTable($pdf, '{70, 5, 5, 110}', 'width:190; align:L; font-size:10;font-family:arial;');
$write->easyCell('Tugas');
$write->easyCell(':');
$write->easyCell('1.');
$write->easyCell('Meninjau Instrumen Asesmen (FR.IA.11)');
$write->printRow();
$write->endTable(0);

$write=new easyTable($pdf, '{70, 5, 5, 40, 5, 65}', 'width:190; align:L; font-size:10;font-family:arial;');
$write->easyCell('');
$write->easyCell('');
$write->easyCell('2.');
$write->easyCell('Skema Kompetensi');
$write->easyCell(':');
$write->easyCell($skemakkni);
$write->printRow();

$write->easyCell('');
$write->easyCell('');
$write->easyCell('3.');
$write->easyCell('Tempat Uji Kompetensi');
$write->easyCell(':');

$sqlgetkotaprov="SELECT * FROM `data_wilayah` WHERE `id_wil`='$tq[id_wilayah]'";
$kec0=$conn->query($sqlgetkotaprov);
$kec=$kec0->fetch_assoc();
$kotaprov=trim($kec['nm_wil']);

$sqlgetkotaprov2="SELECT * FROM `data_wilayah` WHERE `id_wil`='$kec[id_induk_wilayah]'";
$kec20=$conn->query($sqlgetkotaprov2);
$kec2=$kec20->fetch_assoc();
$kotaprov2=trim($kec2['nm_wil']);

$sqlgetkotaprov3="SELECT * FROM `data_wilayah` WHERE `id_wil`='$kec2[id_induk_wilayah]'";
$kec30=$conn->query($sqlgetkotaprov3);
$kec3=$kec30->fetch_assoc();
$kotaprov3=trim($kec3['nm_wil']);

$keckotaprov=$kotaprov." ".$kotaprov2." ".$kotaprov3;

$tempatpelaksanaan=$tq['nama'].", ".$tq['alamat']." ".$tq['kelurahan']." ".ucwords(strtolower($keckotaprov));
$write->easyCell($tempatpelaksanaan);
$write->printRow();
$write->easyCell('');
$write->easyCell('');
$write->easyCell('4.');
$write->easyCell('Hari/ Tanggal');
$write->easyCell(':');
$tglpelaksanaan=$dayList[$day]."/ ".$tgl_cetak;
$write->easyCell($tglpelaksanaan);
$write->printRow();
$write->easyCell('');
$write->easyCell('');
$write->easyCell('5.');
$write->easyCell('Waktu');
$write->easyCell(':');
$pklpelaksanaan="Pukul ".$jdq['jam_asesmen']." s/d Selesai";
$write->easyCell($pklpelaksanaan);
$write->printRow();
$write->easyCell('');
$write->easyCell('');
$write->easyCell('6.');
$write->easyCell('Peserta');
$write->easyCell(':');
$sqlasesmenasesi0="SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`='$jdq[id]' ORDER BY `id_asesi` ASC";
$asesmenasesi0=$conn->query($sqlasesmenasesi0);
$jumlahasesi=$asesmenasesi0->num_rows;
$write->easyCell($jumlahasesi.' orang (Terlampir)');
$write->printRow();
$write->endTable(0);

$write=new easyTable($pdf, '{70, 5, 5, 110}', 'width:190; align:L; font-size:10;font-family:arial;');
$write->easyCell('');
$write->easyCell('');
$write->easyCell('7.');
$write->easyCell('Membuat laporan setelah selesai melaksankan tugas ditujukan kepada Ketua LSP dan paling lambat 5 (lima) hari kerja setelah selesai melaksanakan tugas.');
$write->printRow();
$write->easyCell('');
$write->easyCell('');
$write->easyCell('8.');
$write->easyCell('Melaksanakan perintah ini dengan sebaik-baiknya dan penuh tanggung jawab');
$write->printRow();
$write->endTable(10);

/*$write=new easyTable($pdf, '{10, 60, 30, 50, 40}', 'width:190; align:L; font-size:10;font-family:arial;');
$write->easyCell('No.','align:C; bgcolor:#C6C6C6; font-style:B; border:LTB');
$write->easyCell('Nama Asesi/Peserta','align:C; bgcolor:#C6C6C6; font-style:B; border:LTB');
$write->easyCell('Hasil Asesmen*)','align:C; bgcolor:#C6C6C6; font-style:B; border:LTB');
$write->easyCell('Rekomendasi Tindak Lanjut','align:C; bgcolor:#C6C6C6; font-style:B; border:LTB');
$write->easyCell('Keterangan','align:C; bgcolor:#C6C6C6; font-style:B; border:LTBR');
$write->printRow();

$sqlasesmenasesi0="SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`='$jdq[id]' ORDER BY `id_asesi` ASC";
$asesmenasesi0=$conn->query($sqlasesmenasesi0);
$nops=1;
while ($asdj=$asesmenasesi0->fetch_assoc()){
	$sqlasesi0="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$asdj[id_asesi]'";
	$asesi0=$conn->query($sqlasesi0);
	$dtm0=$asesi0->fetch_assoc();

	$namaasesi=$dtm0['nama'];
	$organisasi=$dtm0['nama_kantor'];
	$write->easyCell($nops,'align:R; border:LTB');
	$write->easyCell($namaasesi,'align:L; border:LTB');
	$write->easyCell('','align:L; border:LTB');
	$write->easyCell('','align:L; border:LTB');
	$write->easyCell('','align:L; border:LTBR');
	$write->printRow();

	$nops++;
}
$write->easyCell('Keterangan : *)  Diisi dengan K (Kompeten) atau BK (Belum Kompeten)','align:L; colspan:5;');
$write->printRow();

$write->endTable(0);

$write=new easyTable($pdf, '{190}', 'width:190; align:L; font-size:10;font-family:arial;');
$write->easyCell('Demikian berita acara ini dibuat dengan sebenarnya, untuk digunakan sebagaimana mestinya.');
$write->printRow();
$write->endTable(5); */
$namakota0=trim($wil2['nm_wil']);
$namakota=ucwords(strtolower($namakota0));
switch ($namakota){
	default:
		$namakotax=$namakota;
	break;
	case "Kota Adm. Jakarta Utara":
		$namakotax="Jakarta";
	break;
	case "Kota Adm. Jakarta Selatan":
		$namakotax="Jakarta";
	break;
	case "Kota Adm. Jakarta Timur":
		$namakotax="Jakarta";
	break;
	case "Kota Adm. Jakarta Barat":
		$namakotax="Jakarta";
	break;
	case "Kota Adm. Jakarta Pusat":
		$namakotax="Jakarta";
	break;
}
if(!empty($jdq['tgl_surattugasia11'])){
	$tglsknya=tgl_indo($jdq['tgl_surattugasia11']);
}else{
	$haminsatu=date('Y-m-d', strtotime('-1 day', strtotime($jdq['tgl_asesmen'])));
	$tglsknya=tgl_indo($haminsatu);
}
$tglcetakkota = ucwords(strtolower($namakotax)).", ".$tglsknya;


$write=new easyTable($pdf, 1, 'width:80; align:R; font-size:12;font-family:arial;');
$write->easyCell($tglcetakkota, 'align:C;');
$write->printRow();
$write->endTable(0);
$write=new easyTable($pdf, '{80}', 'width:180; align:R; font-size:11; font-family:arial;');
$write->easyCell($namalsp, 'align:C;');
$write->printRow();
$write->rowStyle('min-height:15');
$write->easyCell('', 'img:../images/ttd-stempel-pimpinan.png, h25; align:C;');
$write->printRow();
$write->easyCell($lq['direktur'], 'align:C; font-style:BU;');
$write->printRow();
$write->easyCell($lq['nama_jabatanpimpinan'], 'align:C;');
$write->printRow();
$write->endTable(5);

$write=new easyTable($pdf, '{70, 5, 115}', 'width:190; align:L; font-size:8;font-family:arial;');
$write->easyCell('Tembusan:');
$write->easyCell('');
$write->easyCell('');
$write->printRow();
$write->endTable(0);

$write=new easyTable($pdf, '{5, 70, 115}', 'width:190; align:L; font-size:8;font-family:arial;');
$write->easyCell('1.');
$write->easyCell('Manajer Sertifikasi LSP');
$write->easyCell('');
$write->printRow();
$write->easyCell('2.');
$write->easyCell('Manajer Administrasi & Keuangan LSP');
$write->easyCell('');
$write->printRow();
$write->easyCell('3.');
$write->easyCell('Arsip');
$write->easyCell('');
$write->printRow();
$write->endTable(0);

$pdf->AliasNbPages();

//output file pdf
$fileoutputnya="surat-tugas-meninjau-instrumen-asesmen-oleh-asesor-".$skemakkni."-".$_GET['idj'].".pdf";
$pdf->Output($fileoutputnya,'D');
 
ob_end_flush();
?>