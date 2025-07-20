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

$write=new easyTable($pdf, 1, 'width:190; align:L; font-style:B; font-size:12;font-family:arial;');
$write->easyCell('FR-MMA- MERENCANAKAN DAN MENGORGANISASIKAN ASESMEN');
$write->printRow();
$write->endTable(5);

$write=new easyTable($pdf, '{30, 5, 70, 20, 5, 60}', 'width:190; align:L; font-style:B; font-size:10;font-family:arial;');
$write->easyCell('Judul Skema Sertifikasi');
$write->easyCell(':');
$write->easyCell($skemakkni);
$write->easyCell('Tanggal');
$write->easyCell(':');
$write->easyCell($tgl_cetak);
$write->printRow();
$write->easyCell('No. Skema');
$write->easyCell(':');
$write->easyCell($sq['kode_skema']);
$write->easyCell('TUK');
$write->easyCell(':');
$write->easyCell($tq['nama']);
$write->printRow();
$write->easyCell('LSP');
$write->easyCell(':');
$write->easyCell($lq['nama'], 'colspan:4');
$write->printRow();
$write->easyCell('TIM/Nama Asesor');
$write->easyCell(':');
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
		$asesornya=$noasr.". ".$namaasesor.", ";
		$asesornya1=$asesornya1.$asesornya;
		$noasr++;
	}

$write->easyCell($asesornya1, 'colspan:4');
$write->printRow();

$write->endTable(5);


$write=new easyTable($pdf, '{10, 30, 30, 30, 30, 30, 30}', 'width:190; align:L; border-color:#000000; font-style:B; font-size:10;font-family:arial;');
$write->easyCell('1.', 'bgcolor:#C6C6C6; border:LTB');
$write->easyCell('Menentukan pendekatan asesmen', 'bgcolor:#C6C6C6; colspan:6; border:TBR');
$write->printRow();
$write->endTable(0);

$write=new easyTable($pdf, '{10, 30, 5, 25, 30, 30, 30, 30}', 'width:190; align:L; border-color:#000000; font-size:10;font-family:arial;');
$write->easyCell('1.1', 'valign:T; rowspan:3; border:LTBR');
$write->easyCell('Nama Peserta (kelompok Homogen)', 'border:LTBR');
$write->easyCell(':', 'border:LTB');
$write->easyCell($as['nama'], 'colspan:5; border:TBR');
$write->printRow();
$write->easyCell('Tujuan Asesmen', 'border:LTBR');
$write->easyCell(':', 'border:LTB');
$write->easyCell('[_] Sertifikasi', 'border:TB');
$write->easyCell('[_] RCC', 'border:TB');
$write->easyCell('[_] RPL', 'border:TB');
$write->easyCell('[_] Pencapaian proses pembelajaran', 'border:TB');
$write->easyCell('[_] lainnya', 'border:TBR');
$write->printRow();
$write->easyCell('Konteks Asesmen', 'border:LTBR');
$write->easyCell(':', 'border:LTB');
$write->easyCell('TUK simulasi/tempat kerja*    dengan karakteristik   produk/sistem/tempat kerja*', 'colspan:5; border:TBR');
$write->printRow();
$write->endTable(0);

$write=new easyTable($pdf, '{10, 30, 5, 45, 50, 50}', 'width:190; align:L; border-color:#000000; font-size:10;font-family:arial;');
$write->easyCell('1.2', 'border:LTBR');
$write->easyCell('Pendekatan/ Jalur Asesmen', 'border:LTBR');
$write->easyCell(':', 'border:LTB');
$write->easyCell('[_] Mengikuti proses kerja ditempat kerja', 'border:TB');
$write->easyCell('[_] Proses pembelajaran (Sumatif dan formatif)', 'border:TB');
$write->easyCell('[_] Hasil akhir proses pelatihan', 'border:TBR');
$write->printRow();
$write->endTable(0);

$write=new easyTable($pdf, '{10, 30, 5, 145}', 'width:190; align:L; border-color:#000000; font-size:10;font-family:arial;');
$write->easyCell('1.3', 'border:LTBR');
$write->easyCell('Strategi Asesmen', 'border:LTBR');
$write->easyCell(':', 'border:LTB');
$write->easyCell('Mengikuti*:
[_] Benchmark asesmen (unit kompetensi)
[_] RPL arrangements
[_] Metode dan alat asesmen,
[_] Pengorganisasian asesmen,
[_] Aturan paket kualifikasi,
[_] Persyaratan khusus,
[_] Mekanisme jaminan mutu', 'border:TBR');
$write->printRow();
$write->endTable(0);

$write=new easyTable($pdf, '{10, 30, 5, 35, 5, 105}', 'width:190; align:L; border-color:#000000; font-size:10;font-family:arial;');
$write->easyCell('1.4', 'border:LTBR; rowspan:6; valign:T');
$write->easyCell('Acuan pembanding/ Benchmark

Pilih yang  sesuai', 'border:LTBR; rowspan:6; valign:T');
$write->easyCell(':', 'border:LTB; rowspan:6; valign:T');
$write->easyCell('[_] SKKNI', 'border:T');
$write->easyCell(':');
$write->easyCell('Standar Kompetensi Khusus Ahli Cagar Budaya', 'border:TR');
$write->printRow();

$write->easyCell('[_] Standar Produk');
$write->easyCell(':');
$write->easyCell('(tuliskan)', 'border:R');
$write->printRow();

$write->easyCell('[_] Standar Sistem');
$write->easyCell(':');
$write->easyCell('(tuliskan)', 'border:R');
$write->printRow();

$write->easyCell('[_] Regulasi Teknis');
$write->easyCell(':');
$write->easyCell('Undang-Undang No. 11 Tahun 2010 tentang Cagar Budaya', 'border:R');
$write->printRow();

$write->easyCell('[_] SOP');
$write->easyCell(':');
$write->easyCell('SOP Sertifikasi Profesi', 'border:R');
$write->printRow();

$write->easyCell('[_] Dll. ...................................................', 'border:BR; colspan:3');
$write->printRow();
$write->endTable(5);

$write=new easyTable($pdf, '{10, 30, 30, 30, 30, 30, 30}', 'width:190; align:L; border-color:#000000; font-style:B; font-size:10;font-family:arial;');
$write->easyCell('2.', 'bgcolor:#C6C6C6; border:LTB');
$write->easyCell('Mempersiapkan Rencana Asesmen (pada bagian ini kalau lebih dari 2 unit sisipkan sesuai dengan jumlah unit kompetensi)', 'bgcolor:#C6C6C6; colspan:6; border:TBR');
$write->printRow();
$write->endTable(0);

$write=new easyTable($pdf, '{35, 5, 5, 145}', 'width:190; align:L; border-color:#000000; font-size:10;font-family:arial;');

// mulai dengan isi tablenya
$sqlgetunitkompetensi="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sq[id]'";
$getunitkompetensi=$conn->query($sqlgetunitkompetensi);
$noku=1;
$jumunk=$getunitkompetensi->num_rows;
while ($unk=$getunitkompetensi->fetch_assoc()){
	$noku2=$noku.". ";
	if ($noku==1){
		$write->easyCell('Kode Unit', 'border:LTR');
		$write->easyCell(':', 'border:LT');
		$write->easyCell($noku2, 'border:T');
		$write->easyCell($unk['kode_unit'], 'border:TR');
	}else{
		if ($noku!=$jumunk){
			$write->easyCell('', 'border:LR');
			$write->easyCell('', 'border:L');
			$write->easyCell($noku2);
			$write->easyCell($unk['kode_unit'], 'border:R');
		}else{
			$write->easyCell('', 'border:LBR');
			$write->easyCell('', 'border:LB');
			$write->easyCell($noku2, 'border:B');
			$write->easyCell($unk['kode_unit'], 'border:BR');
		}
	}
	$write->printRow();
	$noku++;
}
$write->endTable(0);

$write=new easyTable($pdf, '{35, 5, 5, 145}', 'width:190; align:L; border-color:#000000; font-size:10;font-family:arial; split-row:true;');
// mulai dengan isi tablenya judul unit
$sqlgetunitkompetensi2="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sq[id]'";
$getunitkompetensi2=$conn->query($sqlgetunitkompetensi2);
$nokuj=1;
$jumunkj=$getunitkompetensi2->num_rows;
while ($unk2=$getunitkompetensi2->fetch_assoc()){
	$nokuj2=$nokuj.". ";
	if ($nokuj==1){
		$write->easyCell('Judul Unit', 'border:LTR');
		$write->easyCell(':', 'border:LT');
		$write->easyCell($nokuj2, 'border:T');
		$write->easyCell($unk2['judul'], 'border:TR');
	}else{
		if ($nokuj!=$jumunkj){
			$write->easyCell('', 'border:LR');
			$write->easyCell('', 'border:L');
			$write->easyCell($nokuj2);
			$write->easyCell($unk2['judul'], 'border:R');
		}else{
			$write->easyCell('', 'border:LBR');
			$write->easyCell('', 'border:LB');
			$write->easyCell($nokuj2, 'border:B');
			$write->easyCell($unk2['judul'], 'border:BR');
		}
	}
	$write->printRow();
	$nokuj++;
}
$write->endTable(5);

// mulai dengan isi tablenya judul unit
$sqlgetunitkompetensi3="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sq[id]'";
$getunitkompetensi3=$conn->query($sqlgetunitkompetensi3);
$noku3=1;
$jumunk3=$getunitkompetensi3->num_rows;
while ($unk3=$getunitkompetensi3->fetch_assoc()){
	$nokuj3="No. ".$noku3;
	$write=new easyTable($pdf, '{15, 25, 150}', 'width:190; align:L; border-color:#000000; split-row:true; font-size:10;font-family:arial;');
	$write->easyCell($nokuj3, 'border:LTBR; rowspan:2; valign:T');
	$write->easyCell('Kode Unit :', 'border:LTBR');
	$write->easyCell($unk3['kode_unit'], 'border:LTBR');
	$write->printRow();
	$write->easyCell('Judul Unit :', 'border:LTBR');
	$write->easyCell($unk3['judul'], 'border:LTBR');
	$write->printRow();
	$write->endTable(0);

	// get elemen kompetensi
	$sqlgetelemen="SELECT * FROM `elemen_kompetensi` WHERE `id_unitkompetensi`='$unk3[id]' ORDER BY `id` ASC";
	$getelemen=$conn->query($sqlgetelemen);
	$noel=1;
	while ($el=$getelemen->fetch_assoc()){
		$elemenkom="Elemen ".$noel;
		$write=new easyTable($pdf, '{20,170}', 'width:190; align:L; border-color:#000000; font-size:10; split-row:false; font-family:arial;');
		$write->easyCell($elemenkom, 'border:LTB; valign:T');
		$write->easyCell($el['elemen_kompetensi'], 'border:TBR; valign:T');
		$write->printRow();
		$write->endTable(0);
		// isi tiap elemen
		$write=new easyTable($pdf, '{40,49,8,8,8,10,10,10,10,10,10,10,10}', 'width:190; align:L; border-color:#000000; font-family:arial;');
		$write->easyCell('KRITERIA UNJUK KERJA', 'border:LTBR; align:C; valign:M; font-size:10; rowspan:4');
		$write->easyCell('BUKTI-BUKTI', 'border:LTBR; align:C; valign:M; font-size:10; rowspan:4');
		$write->easyCell('JENIS BUKTI', 'border:LTBR; align:C; valign:M; font-size:10; rowspan:3; colspan:3');
		$write->easyCell('PERANGKAT ASESMEN', 'border:LTBR; align:C; valign:M; font-size:10; colspan:8');
		$write->printRow();
		$write->easyCell('CLO : Ceklis Observasi , CLP : Ceklis Portofolio, VPK: Verifikasi Pihak Ketiga, DPL: Daftar Pertanyaan Lisan, DPT *) : Daftar Pertanyaan Tertulis, SK : Studi Kasus, PW: Pertanyaan Wawancara', 'border:LTBR; align:C; valign:M; font-size:8; colspan:8');
		$write->printRow();
		$write->easyCell('METODE', 'border:LTBR; align:C; valign:M; colspan:8');
		$write->printRow();
		$write->easyCell('L', 'align:C; valign:M; border:LTBR');
		$write->easyCell('TL', 'align:C; valign:M; border:LTBR');
		$write->easyCell('T', 'align:C; valign:M; border:LTBR');
		$write->easyCell('', 'align:C; valign:M; border:LTBR; img:mma1.jpg, h15;');
		$write->easyCell('', 'align:C; valign:M; border:LTBR; img:mma2.jpg, h15;');
		$write->easyCell('', 'align:C; valign:M; border:LTBR; img:mma3.jpg, h15;');
		$write->easyCell('', 'align:C; valign:M; border:LTBR; img:mma4.jpg, h15;');
		$write->easyCell('', 'align:C; valign:M; border:LTBR; img:mma5.jpg, h15;');
		$write->easyCell('', 'align:C; valign:M; border:LTBR; img:mma6.jpg, h15;');
		$write->easyCell('', 'align:C; valign:M; border:LTBR; img:mma7.jpg, h15;');
		$write->easyCell('', 'align:C; valign:M; border:LTBR; img:mma8.jpg, h15;');
		$write->printRow();
		$write->endTable(0);
		
		// kriteria unjuk kerja
		$sqlgetkriteria="SELECT * FROM `kriteria_unjukkerja` WHERE `id_elemenkompetensi`='$el[id]' ORDER BY `id` ASC";
		$getkriteria=$conn->query($sqlgetkriteria);
		$nokr=1;
		while($kr=$getkriteria->fetch_assoc()){
			$write=new easyTable($pdf, '{8,32,49,8,8,8,10,10,10,10,10,10,10,10}', 'width:190; align:L; border-color:#000000; font-size:10;font-family:arial;');
			$nokrt=$noel.".".$nokr;
			$write->easyCell($nokrt, 'border:LTB; align:L; valign:T; rowspan:2');
			$write->easyCell($kr['kriteria'], 'border:TBR; align:L; valign:T; rowspan:2');
			$krtam1="Hasil Verifikasi Bukti Portofolio: 
Bukti No: ……….
Berkaitan dengan ".$kr['kriteria'];
			$write->easyCell($krtam1, 'border:LTBR; align:L; valign:T');
			$write->easyCell('', 'align:C; border:LTBR');
			$write->easyCell('', 'align:C; border:LTBR');
			$write->easyCell('', 'align:C; border:LTBR');
			$write->easyCell('', 'align:C; border:LTBR');
			$write->easyCell('', 'align:C; border:LTBR');
			$write->easyCell('', 'align:C; border:LTBR');
			$write->easyCell('', 'align:C; border:LTBR');
			$write->easyCell('', 'align:C; border:LTBR');
			$write->easyCell('', 'align:C; border:LTBR');
			$write->easyCell('', 'align:C; border:LTBR');
			$write->easyCell('', 'align:C; border:LTBR');
			$write->printRow();

			$krtam2="Hasil Tes Tertulis tentang ".$kr['kriteria'];
			$write->easyCell($krtam2, 'border:LTBR; align:L; valign:T');
			$write->easyCell('', 'align:C; border:LTBR');
			$write->easyCell('', 'align:C; border:LTBR');
			$write->easyCell('', 'align:C; border:LTBR');
			$write->easyCell('', 'align:C; border:LTBR');
			$write->easyCell('', 'align:C; border:LTBR');
			$write->easyCell('', 'align:C; border:LTBR');
			$write->easyCell('', 'align:C; border:LTBR');
			$write->easyCell('', 'align:C; border:LTBR');
			$write->easyCell('', 'align:C; border:LTBR');
			$write->easyCell('', 'align:C; border:LTBR');
			$write->easyCell('', 'align:C; border:LTBR');
			$write->printRow();
			$write->endTable(0);
			// increament no kriteria ujuk kerja
			$nokr++;
		}

		// increament urutan elemen kompetensi
		$noel++;	
	}
	// increament urutan
	$noku3++;
}
$write=new easyTable($pdf, '{190}', 'width:190; align:L; font-size:10;font-family:arial;');
$write->easyCell('', 'align:L');
$write->printRow();
$write->endTable(5);

$write=new easyTable($pdf, '{5, 40, 5, 140}', 'width:190; align:L; border-color:#000000; font-size:10;font-family:arial;');
$write->easyCell('Sumber Daya Phisik/Material : Sarana/pra sarana yang dibutuhkan untuk masing-masing metode', 'align:L; border:LTR; colspan:4');
$write->printRow();
$write->easyCell('•', 'align:C; border:L');
$write->easyCell('Observasi Demonstrasi', 'align:L');
$write->easyCell(':', 'align:C');
$write->easyCell('', 'align:L; border:R');
$write->printRow();
$write->easyCell('•', 'align:C; border:L');
$write->easyCell('Tes Lisan', 'align:L');
$write->easyCell(':', 'align:C');
$write->easyCell('', 'align:L; border:R');
$write->printRow();
$write->easyCell('•', 'align:C; border:L');
$write->easyCell('Verifikasi Porto Folio', 'align:L');
$write->easyCell(':', 'align:C');
$write->easyCell('', 'align:L; border:R');
$write->printRow();
$write->easyCell('•', 'align:C; border:LB');
$write->easyCell('Dll.', 'align:L; border:B');
$write->easyCell(':', 'align:C; border:B');
$write->easyCell('', 'align:L; border:BR');
$write->printRow();
$write->endTable(0);

$write=new easyTable($pdf, '{190}', 'width:190; align:L; font-size:10;font-family:arial;');
$write->easyCell('Catatan : *) L = Buklti langsung,  TL = Bukti tidak langsung,  T = Bukti tambahan', 'align:L');
$write->printRow();
$write->endTable(5);

$write=new easyTable($pdf, '{70,60,60}', 'width:190; align:L; font-size:10;font-family:arial;');
$write->easyCell('Pemenuhan terhadap seluruh bagian unit standar kompetensi : 
(bila tersedia)', 'align:L; border:LTBR; rowspan:2');
$write->easyCell('Batasan Variabel', 'align:C; border:LTBR');
$write->easyCell('Panduan Asesmen', 'align:C; border:LTBR');
$write->printRow();
$write->easyCell('[_] Ya', 'align:C; border:LTBR');
$write->easyCell('[_] Ya', 'align:C; border:LTBR');
$write->printRow();
$write->endTable(0);

$write=new easyTable($pdf, '{190}', 'width:190; align:L; font-size:10;font-family:arial;');
$write->easyCell('Peran dan tanggung jawab Tim/Personil terkait: *) Khusus persetujuan Peserta dapat dilakukan pada saat Pra Asesmen dengan menandatangani FR-MAK.03 : Formulir persetujuan asesmen.', 'align:L; border:LTBR');
$write->printRow();
$write->endTable(0);

$write=new easyTable($pdf, '{45,45,5,50,45}', 'width:190; align:L; font-size:10;font-family:arial;');
$write->easyCell('Nama', 'align:C; valign:M; border:LTBR');
$write->easyCell('Jabatan/ Pekerjaan', 'align:C; valign:M; border:LTBR');
$write->easyCell('Peran dan tanggung jawab dalam asesmen', 'align:C; valign:M; border:LTBR; colspan:2');
$write->easyCell('Paraf/ tanggal', 'align:C; valign:M; border:LTBR');
$write->printRow();
$write->easyCell('', 'align:L; valign:T; border:LTBR; rowspan:5');
$write->easyCell('Asesor', 'align:L; valign:T; border:LTBR; rowspan:5');
$write->easyCell('-', 'align:R; border:LT');
$write->easyCell('Merencanakan asesmen,', 'align:L; border:TR');
$write->easyCell('', 'align:L; border:LTBR; rowspan:5');
$write->printRow();

$write->easyCell('-', 'align:R; border:L');
$write->easyCell('mengembangkan perangkat asesmen,', 'align:L; border:R');
$write->printRow();

$write->easyCell('-', 'align:R; border:L');
$write->easyCell('mengorganisasikan asesmen,', 'align:L; border:R');
$write->printRow();

$write->easyCell('-', 'align:R; border:L');
$write->easyCell('melaksanakan asesmen (Menetapkan dan memelihara lingkungan asesmen, Mengumpulkan Bukti, Mereview bukti, membuat keputusan asesmen, menyampaikan keputusan asesmen, memberikan umpan balik kepada peserta, meminta umpan balik dari  peserta),', 'align:L; border:R');
$write->printRow();

$write->easyCell('-', 'align:R; border:LB');
$write->easyCell('meninjau proses asesmen.', 'align:L; border:BR');
$write->printRow();

// berikutnya
$write->easyCell('', 'align:L; valign:T; border:LTBR; rowspan:2');
$write->easyCell('Manager Sertifikasi', 'align:L; valign:T; border:LTBR; rowspan:2');
$write->easyCell('-', 'align:R; border:LT');
$write->easyCell('Memastikan materi uji kompetensi siap digunakan', 'align:L; border:TR');
$write->easyCell('', 'align:L; border:LTBR; rowspan:2');
$write->printRow();

$write->easyCell('-', 'align:R; border:LB');
$write->easyCell('Memastikan seluruh personil yang terlibat memahami tugas dan fungsinya masing-masing', 'align:L; border:BR');
$write->printRow();

// berikutnya
$write->easyCell('', 'align:L; valign:T; border:LTBR; rowspan:4');
$write->easyCell('Kepala TUK', 'align:L; valign:T; border:LTBR; rowspan:4');
$write->easyCell('-', 'align:R; border:LT');
$write->easyCell('Mendukung proses administrasi dan dokumentasi asesmen', 'align:L; border:TR');
$write->easyCell('', 'align:L; border:LTBR; rowspan:4');
$write->printRow();

$write->easyCell('-', 'align:R; border:L');
$write->easyCell('Memastikan alat, bahan, dan peralatan uji kompetensi sudah siap di area tempat', 'align:L; border:R');
$write->printRow();

$write->easyCell('-', 'align:R; border:L');
$write->easyCell('Memberi arahan kepada peserta dalam rangka persiapan asesmen', 'align:L; border:R');
$write->printRow();

$write->easyCell('-', 'align:R; border:LB');
$write->easyCell('Menyiapkan konsumsi, akomodasi, dan transportasi', 'align:L; border:BR');
$write->printRow();
$write->endTable(0);

$write=new easyTable($pdf, '{45,45,100}', 'width:190; align:L; font-size:10;font-family:arial;');
$write->easyCell('Jangka dan periode waktu asesmen', 'align:L; valign:T; border:LTBR; rowspan:3');
$write->easyCell('•	Tanggal Asesmen', 'align:L; border:LT');
$write->easyCell(':', 'align:L; border:TR');
$write->printRow();
$write->easyCell('•	Durasi per metode', 'align:L; border:L');
$write->easyCell(':', 'align:L; border:R');
$write->printRow();
$write->easyCell('1. Observasi Demonstrasi	: …..  menit (Pk ..... s/d Pk ..........)
	2. Tes Lisan 		: …… menit (Pk..... s/d Pk ..... )
    3. Dll
', 'align:L; border:LBR; colspan:3');
$write->printRow();
$alamatlokasi=$tq['nama']." ".$tq['alamat'];
$write->easyCell('Lokasi asesmen', 'align:L; valign:T; border:LTBR');
$write->easyCell($alamatlokasi, 'align:L; border:LTBR; colspan:2');
$write->printRow();
$write->endTable(5);

$write=new easyTable($pdf, '{10, 45, 45, 90}', 'width:190; align:L; border-color:#000000;  font-size:10;font-family:arial;');
$write->easyCell('3.', 'bgcolor:#C6C6C6;font-style:B; border:LTB');
$write->easyCell('Kontekstualisasi dan meninjau rencana asesmen :', 'bgcolor:#C6C6C6; colspan:3; font-style:B; border:TBR');
$write->printRow();
$write->easyCell('3.1', 'border:LTB');
$write->easyCell('Karakteristik Peserta :', 'colspan:2; border:TBR');
$write->easyCell('Penyesuaian kebutuhan spesifik asesi:', 'border:TBR');
$write->printRow();

$write->rowStyle('min-height:10');
$write->easyCell('', 'border:LTB');
$write->easyCell('', 'colspan:2; border:TBR');
$write->easyCell('', 'border:TBR');
$write->printRow();

$write->easyCell('3.2', 'border:LTB; valign:T; rowspan:2');
$write->easyCell('Kontekstualisasi standar kompetensi :
(untuk mengakomodasi persyaratan spesifik industri, pada batasan variabel dan pedoman bukti)', 'border:TBR; valign:T; rowspan:2');
$write->rowStyle('min-height:20');
$write->easyCell('Pada batasan variabel :', 'colspan:2; border:TBR');
$write->printRow();
$write->rowStyle('min-height:20');
$write->easyCell('Pada panduan penilaian :', 'colspan:2; border:LTBR');
$write->printRow();

$write->endTable(0);

$write=new easyTable($pdf, '{10, 120, 60}', 'width:190; align:L; border-color:#000000;  font-size:10;font-family:arial;');
$write->easyCell('3.3', 'font-style:B; border:LTB');
$write->easyCell('Memeriksa metoda dan perangkat asesmen yang dipilih (sesuai/tidak sesuai) dengan skema sertifikasi', 'font-style:B; colspan:2; border:TBR');
$write->printRow();
$write->easyCell('Bila diperlukan penyesuaian Metode dan perangkat asesmen
dipertimbangkan terhadap :', 'align:C; border:LTB; colspan:2');
$write->easyCell('Catatan
(Tuliskan bila ada penyesuaian)', 'align:C; border:LTBR');
$write->printRow();
$write->easyCell('', 'border:LTB');
$write->easyCell('1.  Berbagai kontekstualisasi Standar Kompetensi', 'align:L; border:TB');
$write->easyCell('', 'border:LTBR');
$write->printRow();
$write->easyCell('', 'border:LTB');
$write->easyCell('2.  Penyesuaian yang beralasan', 'align:L; border:TB');
$write->easyCell('', 'border:LTBR');
$write->printRow();
$write->easyCell('', 'border:LTB');
$write->easyCell('3.  Kegiatan asesmen terintegrasi', 'align:L; border:TB');
$write->easyCell('', 'border:LTBR');
$write->printRow();
$write->easyCell('', 'border:LTB');
$write->easyCell('4.  Kapasitas untuk mendukung RPL', 'align:L; border:TB');
$write->easyCell('', 'border:LTBR');
$write->printRow();
$write->easyCell('3.4', 'font-style:B; border:LTB');
$write->easyCell('Meninjau Perangkat asesmen yang disesuaikan terhadap spesifikasi standar kompetensi  (Ya/Tidak)', 'font-style:B; border:TBR');
$write->easyCell('Catatan
(Tuliskan bila ada penyesuaian)', 'align:C; border:LTBR');
$write->printRow();
$write->rowStyle('min-height:40');
$write->easyCell('', 'border:LTB');
$write->easyCell('', 'border:TBR');
$write->easyCell('', 'align:C; border:LTBR');
$write->printRow();
$write->endTable(5);

$write=new easyTable($pdf, '{10, 45, 75, 30, 30}', 'width:190; align:L; border-color:#000000;  font-size:10;font-family:arial;');
$write->easyCell('4.', 'bgcolor:#C6C6C6;font-style:B; border:LTB');
$write->easyCell('Mengorganisasikan asesmen :', 'bgcolor:#C6C6C6; colspan:4; font-style:B; border:TBR');
$write->printRow();
$write->easyCell('4.1', 'valign:T; border:LTB; rowspan:4');
$write->easyCell('Pengaturan sumber daya asesmen', 'valign:T; rowspan:4; border:TBR');
$write->easyCell('Bahan dan Sumberdaya Fisik', 'font-style:B; align:C; border:LTB');
$write->easyCell('Status', 'font-style:B; align:C; border:LTB');
$write->easyCell('Ket.', 'font-style:B; align:C; border:LTBR');
$write->printRow();
$write->easyCell('•	Tempat Asesmen kompetensi', 'border:LTB');
$write->easyCell('Disediakan oleh Kepala TUK', 'border:LTB');
$write->easyCell('15 menit sebelum asesmen telah siap', 'border:LTBR');
$write->printRow();
$write->easyCell('•	Kelengkapan tempat asesmen  (penerangan, AC, in out) meja, kursi dan ATK sudah dipastikan tersedia di ruangan', 'border:LTB');
$write->easyCell('Diperiksa ketersediaan dan kelengkapannya oleh Teknisi TUK', 'border:LTB');
$write->easyCell('15 menit sebelum asesmen telah siap', 'border:LTBR');
$write->printRow();
$write->easyCell('•	Alat  dan bahan yang digunakan', 'border:LTB');
$write->easyCell('Diperiksa ketersediaan dan kelengkapannya oleh Teknisi TUK', 'border:LTB');
$write->easyCell('15 menit sebelum asesmen telah siap', 'border:LTBR');
$write->printRow();
$write->easyCell('4.2', 'border:LTB');
$write->easyCell('Pengaturan dukungan spesialis', 'border:TBR');
$write->easyCell('', 'colspan:3; border:TBR');
$write->printRow();
$write->endTable(0);

$write=new easyTable($pdf, '{10, 45, 30, 105}', 'width:190; align:L; border-color:#000000;  font-size:10;font-family:arial;');
$write->easyCell('4.3', 'valign:T; border:LTB; rowspan:4');
$write->easyCell('Pengorganisasian personil yang terlibat', 'valign:T; rowspan:4; border:TBR');
$write->easyCell('Personil :', 'align:C; border:LTB');
$write->easyCell('Tugas dan Tanggung Jawab', 'align:C; border:LTBR');
$write->printRow();
$write->easyCell('•	 Asesor', 'valign:T; border:TBR');
$write->easyCell('-	Memeriksa Kesiapan dokumen/berkas Asesmen
-	Memeriksa kesiapan sumber daya asesmen yg dibutuhkan
-	Memberikan arahan kepada peserta asesmen
-	Melakukan & mengawasi proses asesmen
-	Mengumpulkan & memerikasa kelengkapan berkas/dokumen asesmen', 'align:L; border:LTBR');
$write->printRow();
$write->easyCell('•	 Peserta', 'valign:T; border:TBR');
$write->easyCell('-	Peserta ditempatkan/dikumpulkan ditempat yg telah disediakan
-	Peserta diminta mengisi & menandatangani daftar hadir
-	Peserta menerima penjelasan & pengarahan mengenai pelaksanaan asesmen, termasuk tata tertib asesmen yg berlaku
-	Peserta mengikuti jadwal asesmen yg sudah ditetapkan', 'align:L; border:LTBR');
$write->printRow();
$write->easyCell('•	 Panitia', 'valign:T; border:TBR');
$write->easyCell('-	Menyiapkan ruangan ruangan/fasilitas asesmen
-	Menyiapkan berkas/form asesmen
-	Menyiapkan peralatan tulis yg dibutuhkan
-	Menyiapkan daftar hadir & memeriksa kehadiran peserta
-	Memerika, mengumpulkan & mendokumentasikan berkas asesmen
-	Menyiapkan konsumsi, akomodasi & transportasi Asesor & peserta', 'align:L; border:LTBR');
$write->printRow();
$write->easyCell('4.4', 'border:LTB');
$write->easyCell('Strategi Komunikasi (pilih yang sesuai)', 'colspan:3; border:TBR');
$write->printRow();
$write->easyCell('[__] •	Wawancara, baik secara berhadapan maupun melalui telepon', 'colspan:4; border:LBR');
$write->printRow();
$write->easyCell('[__] •	Email, memo, korespondensi', 'colspan:4; border:LBR');
$write->printRow();
$write->easyCell('[__] •	Rapat', 'colspan:4; border:LTBR');
$write->printRow();
$write->easyCell('[__] •	Video Conference/Pembelajaran Berbasis Elektronik', 'colspan:4; border:LBR');
$write->printRow();
$write->easyCell('[__] •	Fokus Group', 'colspan:4; border:LBR');
$write->printRow();
$write->rowStyle('min-height:20');
$write->easyCell('4.5', 'border:LTB');
$write->easyCell('Penyimpanan Rekaman Asesmen dan Pelaporan', 'border:TB');
$namalspnya="Seluruh rekaman proses asesmen dilaporkan kepada Manager Sertifikasi/Kepala LSP dan disimpan di LSP  ".$lq['nama'];
$write->easyCell($namalspnya, 'colspan:2; border:LTBR');
$write->printRow();

$write->endTable(5);

$pdf->AddPage();
$write=new easyTable($pdf, '{70, 70, 50}', 'width:190; align:L; border-color:#000000;  font-size:10;font-family:arial;');
$write->easyCell('Konfirmasi dengan pihak yang relevan :', 'valign:T; font-style:B; border:LTBR; colspan:3');
$write->printRow();
$write->easyCell('Nama', 'align:C; border:LTB');
$write->easyCell('Jabatan', 'align:C; border:LTB');
$write->easyCell('Paraf/ Tanggal', 'align:C; border:LTBR');
$write->printRow();
$write->easyCell('', 'align:C; border:LTB');
$write->easyCell('Manager Sertifikasi LSP', 'align:L; border:LTB');
$write->easyCell('', 'align:C; border:LTBR');
$write->printRow();
$write->easyCell('', 'align:C; border:LTB');
$write->easyCell('Manager Administrasi/Mutu', 'align:L; border:LTB');
$write->easyCell('', 'align:C; border:LTBR');
$write->printRow();
$write->easyCell('', 'align:C; border:LTB');
$write->easyCell('Kepala LSP', 'align:L; border:LTB');
$write->easyCell('', 'align:C; border:LTBR');
$write->printRow();
$write->endTable(0);

$write=new easyTable($pdf, '{90, 50, 50}', 'width:190; align:L; border-color:#000000;  font-size:10;font-family:arial;');
$write->easyCell('Konfirmasi dengan pihak yang relevan :', 'valign:T; font-style:B; border:LTB; rowspan:3');
$write->easyCell('Nama Asesor :', 'valign:T; font-style:B; border:LTB');
$write->easyCell($namaasesor, 'valign:T; font-style:B; border:LTBR');
$write->printRow();
$write->easyCell('No. Reg. :', 'valign:T; font-style:B; border:LTB');
$write->easyCell($asr['no_induk'], 'valign:T; font-style:B; border:LTBR');
$write->printRow();
$write->rowStyle('min-height:20');
$write->easyCell('Tanda tangan/ Tanggal :', 'valign:M; font-style:B; border:LTB');
$write->easyCell('', 'valign:T; font-style:B; border:LTBR');
$write->printRow();
$write->easyCell('Diverifikasi oleh Manajemen Sertifikasi', 'valign:T; font-style:B; border:LTB; rowspan:3');
$write->easyCell('Nama :', 'valign:T; font-style:B; border:LTB');
$write->easyCell('', 'valign:T; font-style:B; border:LTBR');
$write->printRow();
$write->easyCell('Jabatan :', 'valign:T; font-style:B; border:LTB');
$write->easyCell('', 'valign:T; font-style:B; border:LTBR');
$write->printRow();
$write->rowStyle('min-height:20');
$write->easyCell('Tanda tangan/ Tanggal :', 'valign:M; font-style:B; border:LTB');
$write->easyCell('', 'valign:T; font-style:B; border:LTBR');
$write->printRow();

$write->endTable(0);

$pdf->AliasNbPages();

//output file pdf
$fileoutputnya="form-mma-".$skemakkni."-".$as['no_pendaftaran'].".pdf";
$pdf->Output($fileoutputnya,'D');
 

?>