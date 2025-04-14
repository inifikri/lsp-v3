<?php
ob_start();
include 'fpdf-easytable-master/fpdf.php';
include 'fpdf-easytable-master/exfpdf.php';
include 'fpdf-easytable-master/easyTable.php';
include "../config/koneksi.php";
include "../config/library.php";
include "../config/fungsi_indotgl.php";

ini_set('display_errors',0); 

error_reporting(E_ALL);

$sqlasesi="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_GET[ida]'";
$asesi=$conn->query($sqlasesi);
$as=$asesi->fetch_assoc();
$sqljadwal="SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
$jadwal=$conn->query($sqljadwal);
$jdq=$jadwal->fetch_assoc();
$tgl_cetak = tgl_indo($jdq['tgl_asesmen']);

// QUERY GET DATA ASESOR
$queryasesor = "SELECT * FROM asesor WHERE no_ktp='$_GET[asesor]'";
$qasesor = $conn->query($queryasesor);
$asesor = $qasesor->fetch_assoc();

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

// GET ASESI ASESMEN
$getfria04B=$conn->query("SELECT * FROM asesi_asesmen a WHERE a.id_asesi='$_GET[ida]' AND a.id_jadwal='$_GET[idj]'");
$gtas =$getfria04B->fetch_assoc();

// GET TANDA TANGAN ASESOR
$sqlcektandatangan = "SELECT * FROM `logdigisign` WHERE id_skema='$jdq[id_skemakkni]' AND id_asesi='$asesor[no_ktp]' AND `penandatangan`='$asesor[nama]' AND nama_dokumen='FR.IA.04B. PENILAIAN PROYEK SINGKAT ATAU KEGIATAN TERSTRUKTUR LAINNYA' ORDER BY `waktu` DESC";
$cektandatangan = $conn->query($sqlcektandatangan);
$jumttd = $cektandatangan->num_rows;
$ttdx = $cektandatangan->fetch_assoc();

// GET TANDA TANGAN ASESI
$sqlcektandatanganasesi = "SELECT * FROM `logdigisign` WHERE `id_jadwal`='$_GET[idj]' AND id_skema='$jdq[id_skemakkni]' AND id_asesi='$_GET[ida]' AND `penandatangan`='$as[nama]' AND nama_dokumen='FR.IA.04B. PENILAIAN PROYEK SINGKAT ATAU KEGIATAN TERSTRUKTUR LAINNYA' ORDER BY `waktu` DESC";
$cektandatanganasesi = $conn->query($sqlcektandatanganasesi);
$jumttdasesi = $cektandatanganasesi->num_rows;
$ttdxasesi = $cektandatanganasesi->fetch_assoc();

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
$write->easyCell('', 'img:../images/logolsp.jpg, w25, h14; align:C; rowspan:3');
$write->easyCell($namalsp, 'align:C; font-size:14;');
$write->easyCell('', 'img:../images/logobnsp.jpg, w25, h14;align:C; rowspan:3');
$write->printRow();
$write->easyCell($nomorlisensi, 'align:C; font-size:10;');
$write->printRow();
$write->easyCell($alamatlsptampil, 'align:C; font-size:8;');
$write->printRow();
$write->endTable(5);


//===============================================================

$write=new easyTable($pdf, 1, 'width:190;  font-style:B; font-size:12;font-family:arial;');
$write->easyCell('FR.IA.04B. PENILAIAN PROYEK SINGKAT ATAU KEGIATAN TERSTRUKTUR LAINNYA', 'align:L;');
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
$write=new easyTable($pdf, '{10,180}', 'width:190; align:L; font-family:arial; font-size:12');
$write->easyCell(chr(149), 'align:C; border:L;');
$write->easyCell('Lakukan penilaian pencapaian hasil proyek singkat atau kegiatan terstruktur lainnya  melalui presentasi.', 'align:L; border:R;');
$write->printRow();
$write->easyCell(chr(149), 'align:C; border:L;');
$write->easyCell('Penilaian dilakukan sesuai dengan  FR IA 04A. DIT.  Daftar Instruksi Terstruktur (Penjelasan Proyek Singkat/ Kegiatan Terstruktur Lainnya)', 'align:L; border:R;');
$write->printRow();
$write->easyCell(chr(149), 'align:C; border:L;');
$write->easyCell('Pertanyaan disampaikan oleh asesor setelah  asesi melakukan presentasi proyek singkat/ kegiatan terstruktur lainnya.', 'align:L; border:R;');
$write->printRow();
$write->easyCell(chr(149), 'align:C; border:L;');
$write->easyCell('Pertanyaan dapat dikembangkan oleh asesor berdasarkan dokumen presentasi dan atau hasil presentasi .', 'align:L; border:R;');
$write->printRow();
$write->easyCell(chr(149), 'align:C; border:L;');
$write->easyCell('Isilah kolom lingkup penyajian proyek atau kegiatan terstruktur lainnya sesuai sektor/ sub-sektor/ profesi.', 'align:L; border:R;');
$write->printRow();
$write->easyCell(chr(149), 'align:C; border:LB;');
$write->easyCell('Berikan keputusan pencapaian berdasarkan kesimpulan jawaban asesi.', 'align:L; border:RB;');
$write->printRow();
$write->endTable(5);

// QUERY MEMANGGIL DATA LINGKUP KEGIATAN
// $sqllingkupkegiatan="SELECT * FROM `lingkupkegiatan_formIA04B` WHERE `id_skemakkni`='$sq[id]'";
$sqllingkupkegiatan="SELECT *,a.id as idpertanyaan FROM `skema_pertanyaania04B` a LEFT JOIN lingkupkegiatan_formIA04B b ON b.id=a.id_lingkupkegiatan WHERE b.id_skemakkni=$sq[id] ORDER BY b.id ASC";
$getlingkupkegiatan=$conn->query($sqllingkupkegiatan);

$write=new easyTable($pdf, '{130,60,40,30,30}', 'width:190; align:L; font-family:arial; font-size:12');
$write->easyCell('Aspek Penilaian', 'align:L; rowspan:2;colspan:3; border:LTBR; font-style:B;');
$write->easyCell('Pencapaian', 'align:L; colspan:2; border:TRB; font-style:B;');
$write->printRow();
$write->easyCell('Ya', 'align:L;rowspan:2; border:LTBR; font-style:B;');
$write->easyCell('Tidak', 'align:L;rowspan:2; border:LTBR; font-style:B;');
$write->printRow();
$write->easyCell('Lingkup Penyajian proyek atau kegiatan terstruktur lainnya', 'align:L; border:LTBR; font-style:B;');
$write->easyCell('Daftar Pertanyaan', 'align:L; border:LTBR; font-style:B;');
$write->easyCell('Kesesuaian dengan standar kompetensi kerja  (unit/elemen/KUK)', 'align:L; border:LTBR; font-style:B;');
$write->printRow();
while ($lk=$getlingkupkegiatan->fetch_assoc()){
	$getfria04B=$conn->query("SELECT * FROM asesmen_ia04B a WHERE a.id_skemakkni=$sq[id] AND a.id_asesi='$_GET[ida]' AND a.id_jadwal='$_GET[idj]' AND a.id_pertanyaan='$lk[id]'");
	$gfr=$getfria04B->fetch_assoc();
	$getpertanyaanIA04B =$conn->query("SELECT * FROM skema_pertanyaania04B WHERE id_lingkupkegiatan='$lk[id]'");
	$gc=$getfria04B->fetch_assoc();

	$write->easyCell($lk['no_urutan'].'. '.$lk['lingkupkegiatan'], 'align:L; border:LTBR;');
	$write->easyCell($lk['pertanyaan'].'  Tanggapan :'.$gfr['tanggapan'], 'align:L; border:LTBR;');
	$write->easyCell($gfr['standar_kompetensikerja'], 'align:L; border:LTBR;');
	switch ($gfr['pencapaian']){
		default:
			$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; border:LTBR;');
			$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; border:LTBR;');
		break;
		case "Ya":
			$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; border:LTBR;');
			$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; border:LTBR;');
		break;
		case "Tidak":
			$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; border:LTBR;');
			$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; border:LTBR;');
		break;
	}
	$write->printRow();
}
$write->endTable(5);

// REKOMENDASI ASESOR
$write=new easyTable($pdf, '{50,10,130}', 'width:190; align:L; font-family:arial; font-size:12');
$write->easyCell('Rekomendasi Asesor:', 'valign:T; border:LTR;');
$write->easyCell("Asesi telah memenuhi/belum memenuhi pencapaian seluruh kriteria unjuk kerja, direkomendasikan:\n", 'valign:T; align:L; border:LTR; colspan:2;');
$write->printRow();
$write->easyCell('', 'border:LR;');
if($gtas['rekomendasi_IA04B'] == 'K'){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:L; border:L;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:L; border:L;');
}
$write->easyCell('Kompeten', 'align:L; border:R;');
$write->printRow();
$write->easyCell('', 'border:LR;');
if($gtas['rekomendasi_IA04B'] == 'BK'){
	$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:L; border:L;');
}else{
	$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:L; border:L;');
}
$write->easyCell('Belum Kompeten', 'align:L; border:R;');
$write->printRow();
$write->endTable(0);
$write = new easyTable($pdf, '{190}', 'border:1;font-size:12');
$write->rowStyle('font-size:10; font-style:B;');
$write->easyCell('Asesi:', 'align:L; border:LTRB;font-size:12');
$write->printRow();
$write->endTable(0);
$write = new easyTable($pdf, '{50,140}', 'border:1;font-size:12');
$write->easyCell('Nama', 'border:LTRB;');
$write->easyCell($as['nama'], 'border:LTRB;');
$write->printRow();
$write->easyCell('Tanda tangan / Tanggal', 'border:LTRB;');
$write->easyCell(tgl_indo($ttdxasesi['waktu']),'img:../'.$ttdxasesi['file'].',h20,border:LTRB;');
$write->printRow();
$write->endTable(0);

// Asesor
$write = new easyTable($pdf, '{190}', 'border:1;font-size:12');
$write->easyCell('Asesor:', 'align:L; border:LTRB;font-style:B');
$write->printRow();
$write->endTable(0);

$write = new easyTable($pdf, '{50,140}', 'border:1;font-size:12');
$write->easyCell('Nama', 'border:LTRB;');
$write->easyCell($namaasesor, 'border:LTRB;');
$write->printRow();
$write->easyCell('No. Reg', 'border:LTRB;font-size:12');
$write->easyCell($noregasesor, 'border:LTRB;');
$write->printRow();
$write->easyCell('Tanda tangan / Tanggal', 'border:LTRB;');
$write->easyCell(tgl_indo($ttdx['waktu']),'img:'.$ttdx['file'].',h20,border:LTRB;');
$write->printRow();

$write->easyCell('Tanda tangan/Tanggal', 'rowspan:2; border:LTRB;font-size:12');
$write->easyCell(':', 'rowspan:2; border:LTRB;');
$write->printRow();
$write->endTable(5);

$write=new easyTable($pdf, '{200}', 'width:190; align:L; font-family:arial; font-size:12');
$write->easyCell('PENYUSUN DAN VALIDATOR', 'align:L; font-style:B;');
$write->printRow();
$write->endTable(0);

// GET QUERY JADWAL ASESOR PENYUSUN
$queryjadwalpenyusun=$conn->query("SELECT * FROM jadwal_penyusun WHERE id_jadwal='$_GET[idj]'");
$countjadwalpenyusun=$queryjadwalpenyusun->num_rows;

// GET QUERY JADWAL VERIFIKATOR FORM
$queryverifikatorform=$conn->query("SELECT * FROM asesor_verifikatorform WHERE id_jadwal='$_GET[idj]'");
$countverifikatorform=$queryverifikatorform->num_rows;

$write=new easyTable($pdf, '{40,20,60,60,100}', 'width:190; align:L; font-family:arial; font-size:12');
$write->easyCell('STATUS', 'align:L; border:LTBR; font-style:B;');
$write->easyCell('NO', 'align:L; border:LTBR; font-style:B;');
$write->easyCell('NAMA', 'align:L; border:LTBR; font-style:B;');
$write->easyCell('NOMOR MET', 'align:L; border:LTBR; font-style:B;');
$write->easyCell('TANDA TANGAN DAN TANGGAL', 'align:L; border:LTBR; font-style:B;');
$write->printRow();
$write->easyCell('PENYUSUN','rowspan:'.$countjadwalpenyusun.'; align:L; border:LTBR;');
while($jps = $queryjadwalpenyusun->fetch_assoc()){
	$queryasesor=$conn->query("SELECT * FROM asesor WHERE id=$jps[id_asesor]");
	$fetchasesor=$queryasesor->fetch_assoc();
	$write->easyCell('1', 'align:L; border:LTBR;');
	$write->easyCell($fetchasesor['nama'], 'align:L; border:LTBR;');
	$write->easyCell($fetchasesor['no_induk'], 'align:L; border:LTBR;');
	$write->easyCell('', 'align:L; border:LTBR;');
	$write->printRow();
}	
$write->easyCell('VERIFIKATOR','rowspan:'.$countverifikatorform.'; align:L; border:LTBR;');
while($jvf = $queryverifikatorform->fetch_assoc()){
	$queryasesor=$conn->query("SELECT * FROM asesor WHERE no_induk='$jvf[id_verifikatorform]'");
	$fetchasesor=$queryasesor->fetch_assoc();
	$write->easyCell('1', 'align:L; border:LTBR;');
	$write->easyCell($fetchasesor['nama'], 'align:L; border:LTBR;');
	$write->easyCell($fetchasesor['no_induk'], 'align:L; border:LTBR;');
	$write->easyCell('', 'align:L; border:LTBR;');
	$write->printRow();
}

$pdf->AliasNbPages();
$write->endTable(0);

//output file pdf
$fileoutputnya="FR-IA-04B-".$skemakkni."-".$_GET['idj']."-".$_GET['ida'].".pdf";
$pdf->Output($fileoutputnya,'I');
 
ob_end_flush();
?>