<?php
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

$sqlasesor="SELECT * FROM `asesor` WHERE `no_ktp`='$_SESSION[namauser]'";
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
$write->easyCell('BERITA ACARA ASESMEN KOMPETENSI', 'align:C;');
$write->printRow();
$write->endTable(5);

$tglcetakkota = trim($wil2b['nm_wil']).", ".$tgl_cetak;
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

$write=new easyTable($pdf, '{190}', 'width:190; align:L; font-size:10;font-family:arial;');
$keterangan1="Pada hari ini, Hari/Tanggal : ".$dayList[$day]."/ ".$tgl_cetak.",   Waktu : Pkl ".$jdq['jam_asesmen']." s/d Selesai";
$write->easyCell($keterangan1);
$write->printRow();
$keterangan2="bertempat di : ".$tq['nama']." Ruang .............................................................................................................................................., telah dilaksanakan proses asesmen terhadap peserta asesmen  sebagai berikut :";
$write->easyCell($keterangan2);
$write->printRow();
$write->endTable(0);

$write=new easyTable($pdf, '{70, 10, 110}', 'width:190; align:L; font-size:10;font-family:arial;');
$write->easyCell('Sektor/sub sektor/ bidang profesi');
$write->easyCell(':');
$write->easyCell($skemakkni);
$write->printRow();
$write->easyCell('Jumlah Asesi /Peserta yang mengikuti');
$write->easyCell(':');
$write->easyCell('......  (.........)  orang');
$write->printRow();
$write->easyCell('Jumlah Asesi/Peserta  yang dinyatakan Kompeten');
$write->easyCell(':');
$write->easyCell('......  (.........)  orang');
$write->printRow();
$write->easyCell('Jumlah Asesi/Peserta  yang dinyatakan Belum Kompeten');
$write->easyCell(':');
$write->easyCell('......  (.........)  orang');
$write->printRow();
$write->easyCell('dengan perincian sebagai berikut:','colspan:3');
$write->printRow();

$write->endTable(0);
$write=new easyTable($pdf, '{10, 60, 30, 50, 40}', 'width:190; align:L; font-size:10;font-family:arial;');
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
$write->endTable(5);

$write=new easyTable($pdf, '{190}', 'width:190; align:L; font-size:10;font-family:arial;');
$write->easyCell($tglcetakkota);
$write->printRow();
$write->endTable(5);

$write=new easyTable($pdf, '{95,95}', 'width:190; align:L; font-size:10;font-family:arial;');
$write->easyCell('Asesor Kompetensi :');
$write->easyCell('Penanggungjawab kegiatan :');
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
		$asesornya="Nama : ".$namaasesor;
		//$asesornya1=$asesornya1.$asesornya;
		$noregasr="No. Reg ".$asr['no_induk'];
		$write->easyCell($asesornya);
		if ($noasr==1){
			$write->easyCell('Nama :');
		}
		$write->printRow();
		$write->easyCell($noregasr);
		if ($noasr==1){
			$write->easyCell('Jabatan :');
		}
		$write->printRow();
		$write->rowStyle('min-height:15');
		$write->easyCell('Tanda tangan :','valign:T;');
		if ($noasr==1){
			$write->rowStyle('min-height:15');
			$write->easyCell('Tanda tangan :','valign:T;');
		}

		$write->printRow();
		$write->easyCell('...........................................');
		if ($noasr==1){
			$write->easyCell('...........................................');
		}

		$write->printRow();
		$noasr++;

	}

$write->endTable(5);


$pdf->AddPage('L');
$write=new easyTable($pdf, 1, 'width:190;  font-style:B; font-size:12;font-family:arial;');
$write->easyCell('DAFTAR HADIR', 'align:C;');
$write->printRow();
$write->endTable(5);

$write=new easyTable($pdf, '{35, 5, 237}', 'width:277; align:L; font-size:10;font-family:arial;');
$haritanggalnya=$dayList[$day].", ".$tgl_cetak;
$keterangan2b=$tq['nama']." Ruang ...................................................................";
$write->easyCell('Hari/ tanggal','font-style:B');
$write->easyCell(':','font-style:B');
$write->easyCell($haritanggalnya,'font-style:B');
$write->printRow();
$write->easyCell('Tempat','font-style:B');
$write->easyCell(':','font-style:B');
$write->easyCell($keterangan2b,'font-style:B');
$write->printRow();
$write->endTable(0);


$write=new easyTable($pdf, '{10, 40, 40, 60, 40, 40, 47}', 'width:277; align:L; border-color:#000000; font-size:10;font-family:arial;');
$write->easyCell('No.', 'align:C; bgcolor:#C6C6C6; font-style:B; border:LTB');
$write->easyCell('Nama Peserta', 'align:C; bgcolor:#C6C6C6; font-style:B; border:LTB');
$write->easyCell('Institusi/ Perusahaan', 'align:C; bgcolor:#C6C6C6; font-style:B; border:LTB');
$write->easyCell('Alamat', 'align:C; bgcolor:#C6C6C6; font-style:B; border:LTB');
$write->easyCell('Pekerjaan', 'align:C; bgcolor:#C6C6C6; font-style:B; border:LTB');
$write->easyCell('No. Telp/ HP', 'align:C; bgcolor:#C6C6C6; font-style:B; border:LTB');
$write->easyCell('Tanda Tangan', 'align:C; bgcolor:#C6C6C6; font-style:B; border:LTBR');
$write->printRow();

$nomnya=1;
$sqlasesmenasesi="SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`='$jdq[id]' ORDER BY `id_asesi` ASC";
$asesmenasesi=$conn->query($sqlasesmenasesi);
while ($dt=$asesmenasesi->fetch_assoc()){
	$sqlasesi="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$dt[id_asesi]'";
	$asesi=$conn->query($sqlasesi);
	$dtm=$asesi->fetch_assoc();
	$nomnya2=$nomnya.".";
	$alamatasesi=$dtm['alamat']." RT ".$dtm['RT']." RW ".$dtm['RW']." ".$dtm['kelurahan']." ".trim($wil1['nm_wil']).", ".trim($wil2['nm_wil'])." ".trim($wil3['nm_wil']);
	$write->easyCell($nomnya2, 'border:LTB');
	$write->easyCell($dtm['nama'], 'border:LTB');
	$write->easyCell($dtm['nama_kantor'], 'border:LTB');
	$write->easyCell($alamatasesi, 'border:LTB');
	$sqlgetkerjaan="SELECT * FROM `pekerjaan` WHERE `id`='$dtm[pekerjaan]'";
	$getkerjaan=$conn->query($sqlgetkerjaan);
	$krjn=$getkerjaan->fetch_assoc();
	$write->easyCell($krjn['pekerjaan'], 'border:LTB');
	$write->easyCell($dtm['nohp'], 'border:LTB');
	if($nomnya % 2 == 0){
		$nogangen=$nomnya.".";
	}else{
		$nogangen="      ".$nomnya.".";

	}
	$write->easyCell($nogangen, 'border:LTBR');
	$write->printRow();
	$nomnya++;
}
$write->endTable(10);
$write=new easyTable($pdf, '{90,90,97}', 'width:190; align:L; font-size:10;font-family:arial;');
$write->printRow();
$noasr0=1;
$getasesor0=$conn->query("SELECT * FROM `jadwal_asesor` WHERE `id_jadwal`='$_GET[idj]'");
while ($gas0=$getasesor0->fetch_assoc()){

	$write->rowStyle('min-height:30');
	$asesorkompetensi="Asesor Kompetensi ".$noasr0." :";
	$write->easyCell($asesorkompetensi,'valign:T; align:C');
	$noasr0++;
}
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
	$asesornya="( ".$namaasesor." )";
	//$asesornya1=$asesornya1.$asesornya;
	$noregasr="No. Reg ".$asr['no_induk'];
	$write->easyCell($asesornya,'align:C;font-style:B');
	$noasr++;

}
$write->printRow();
$write->endTable(5);

$pdf->AliasNbPages();

//output file pdf
$fileoutputnya="daftar-hadir-berita-acara-".$skemakkni."-".$_GET['idj'].".pdf";
$pdf->Output($fileoutputnya,'D');
 

?>