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

$write=new easyTable($pdf, 1, 'width:180;  font-style:B; font-size:12;font-family:arial;');
$write->easyCell('FR.IA.08. CEKLIS VERIFIKASI PORTOFOLIO', 'align:L;');
$write->printRow();
$write->endTable(5);

$write=new easyTable($pdf, '{50, 20, 5, 105}', 'width:180; align:C; font-style:B; font-family:arial; font-size:12');
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

$write=new easyTable($pdf, '{180}', 'width:180; align:C; font-family:arial; font-size:12');
$write->easyCell('PANDUAN BAGI ASESOR', 'align:L; border:LTBR; bgcolor:#C6C6C6; font-style:B;');
$write->printRow();
$write->endTable(0);
$write=new easyTable($pdf, '{10,170}', 'width:180; align:C; font-family:arial; font-size:12');
$write->easyCell(chr(149), 'align:C; border:LT;');
$write->easyCell('Isilah tabel ini sesuai dengan informasi sesuai pertanyaan/pernyataan dalam tabel dibawah ini.', 'align:L; border:TR;');
$write->printRow();
$write->easyCell(chr(149), 'align:C; border:LB;');
$write->easyCell('Beri tanda centang (v) pada hasil penilaian portfolio berdasarkan aturan bukti.', 'align:L; border:RB;');
$write->printRow();
$write->endTable(5);

$sqlgetunitkompetensi="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sq[id]'";
$getunitkompetensi=$conn->query($sqlgetunitkompetensi);
//while unitkompetensi ==================================================================
$write=new easyTable($pdf, '{40, 30, 5, 105}', 'width:180; align:C; font-style:B; font-family:arial; font-size:10');
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

$write=new easyTable($pdf, '{10, 50, 15, 15, 15, 15, 15, 15, 15, 15}', 'width:180; align:C; font-family:arial; font-size:12');
$write->easyCell('Dokumen portofolio:', 'align:C; valign:M; rowspan:3; colspan:2; font-style:B; border:LTBR');
$write->easyCell('Aturan Bukti', 'align:C; valign:T; font-size:12; colspan:8; font-style:B; bgcolor:#C6C6C6; border:LTBR');
$write->printRow();
$write->easyCell('Valid', 'align:C; valign:T; colspan:2; font-size:12; font-style:B; bgcolor:#C6C6C6; border:LTBR');
$write->easyCell('Asli', 'align:C; valign:T; colspan:2; font-size:12; font-style:B; bgcolor:#C6C6C6; border:LTBR');
$write->easyCell('Terkini', 'align:C; valign:T; colspan:2; font-size:12; font-style:B; bgcolor:#C6C6C6; border:LTBR');
$write->easyCell('Memadai', 'align:C; valign:T; colspan:2; font-size:12; font-style:B; bgcolor:#C6C6C6; border:LTBR');
$write->printRow();
$write->easyCell('Ya', 'align:C; valign:T; font-size:12; font-style:B; border:LTBR');
$write->easyCell('Tidak', 'align:C; valign:T; font-size:12; font-style:B; border:LTBR');
$write->easyCell('Ya', 'align:C; valign:T; font-size:12; font-style:B; border:LTBR');
$write->easyCell('Tidak', 'align:C; valign:T; font-size:12; font-style:B; border:LTBR');
$write->easyCell('Ya', 'align:C; valign:T; font-size:12; font-style:B; border:LTBR');
$write->easyCell('Tidak', 'align:C; valign:T; font-size:12; font-style:B; border:LTBR');
$write->easyCell('Ya', 'align:C; valign:T; font-size:12; font-style:B; border:LTBR');
$write->easyCell('Tidak', 'align:C; valign:T; font-size:12; font-style:B; border:LTBR');
$write->printRow();
// portofolio
$sqlgetportofolio="SELECT * FROM `asesi_portfolio` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_skemakkni`='$sq[id]' ORDER BY `tgl_doc` DESC";
$getportofolio=$conn->query($sqlgetportofolio);
$noprtas=1;
$jumportofolio=$getportofolio->num_rows;
if ($jumportofolio>0){
	while ($prtas=$getportofolio->fetch_assoc()){
		$write->easyCell($noprtas.'.', 'align:R; valign:T; font-size:12; font-style:B; border:LTB');
		$write->easyCell(ucwords($prtas['peran_portfolio']).' '.$prtas['nama_doc'], 'align:C; valign:T; font-size:12; font-style:B; border:TBR');
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
		$write->printRow();
		$noprtas++;
	}
}else{
	$ii=1;
	while ($ii<4){
		$write->easyCell($ii.'.', 'align:R; valign:T; font-size:12; font-style:B; border:LTB');
		$write->easyCell('', 'align:C; valign:T; font-size:12; font-style:B; border:TBR');
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
		$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTBR;');
		$write->printRow();
		$ii++;
	}
}
$write->endTable(5);

$write=new easyTable($pdf, '{30,30,120}', 'width:180; align:C; font-family:arial; font-size:12');
$write->easyCell('Sebagai tindak lanjut dari hasil verifikasi bukti, substansi materi di bawah ini (no elemen yg di cek list) harus diklarifikasi selama wawancara:', 'align:L; valign:T; font-size:12; font-style:B; colspan:3; border:LTBR');
$write->printRow();
$write->easyCell('Cek List', 'align:C; valign:M; font-size:12; font-style:B; border:LTBR');
$write->easyCell('Elemen', 'align:C; valign:M; font-size:12; font-style:B; border:LTBR');
$write->easyCell('Materi/substansi wawancara (KUK)', 'align:C; valign:M; font-size:12; font-style:B; border:LTBR');
$write->printRow();
$i=1;
while ($i<6){
	$write->rowStyle('min-height:10');
	$write->easyCell('', 'align:C; valign:M; font-size:12; font-style:B; border:LTBR');
	$write->easyCell('', 'align:C; valign:M; font-size:12; font-style:B; border:LTBR');
	$write->easyCell('', 'align:C; valign:M; font-size:12; font-style:B; border:LTBR');
	$write->printRow();
	$i++;
}
$write->easyCell('Bukti tambahan diperlukan pada unit / elemen kompetensi sebagai berikut:', 'align:L; valign:T; colspan:3; font-size:12; font-style:B; border:LTR');
$write->printRow();
$write->rowStyle('min-height:30');
$write->easyCell('Contoh :', 'align:L; valign:T; font-size:12; font-style:B; border:LB');
$write->easyCell('', 'align:C; valign:M; colspan:2; font-size:12; font-style:B; border:BR');
$write->printRow();
$write->endTable(0);
$write=new easyTable($pdf, '{50,10,120}', 'width:180; align:C; font-family:arial; font-size:12');
$write->easyCell('Rekomendasi Asesor:', 'align:L; valign:T; font-size:12; rowspan:5; font-style:B; bgcolor:#C6C6C6; border:LTBR');
$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LTB;');
$write->easyCell('Asesi telah memenuhi pencapaian seluruh kriteria unjuk kerja, direkomendasikan KOMPETEN', 'align:L; valign:T; font-size:12; font-style:B; border:TBR');
$write->printRow();
$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:T; border:LT;');
$write->easyCell('Asesi belum memenuhi pencapaian seluruh kriteria unjuk kerja, direkomendasikan uji demonstrasi pada:', 'align:L; valign:T; font-size:12; font-style:B; border:TR');
$write->printRow();
$write->easyCell('', 'align:L; valign:T; font-size:12; font-style:B; border:L');
$write->easyCell('Unit ', 'align:L; valign:T; font-size:12; font-style:B; border:R');
$write->printRow();
$write->easyCell('', 'align:L; valign:T; font-size:12; font-style:B; border:L');
$write->easyCell('Elemen ', 'align:L; valign:T; font-size:12; font-style:B; border:R');
$write->printRow();
$write->easyCell('', 'align:L; valign:T; font-size:12; font-style:B; border:LB');
$write->easyCell('KUK ', 'align:L; valign:T; font-size:12; font-style:B; border:BR');
$write->printRow();
$write->endTable(5);


$write=new easyTable($pdf, '{30,75,75}', 'width:180; align:C; font-family:arial; font-size:12');
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
$write->rowStyle('min-height:30');
$write->easyCell('Tanda Tangan dan Tanggal', 'align:L; border:LTBR;');
$write->easyCell($tgl_cetak, 'align:L; border:TBR; font-style:TB;');
$write->easyCell('', 'align:L; border:TBR;');
$write->printRow();
$write->endTable(0);


$pdf->AliasNbPages();

//output file pdf
$fileoutputnya="FR-IA-08-".$skemakkni."-".$_GET['idj']."-".$_GET['ida'].".pdf";
$pdf->Output($fileoutputnya,'I');
 
ob_end_flush();
?>