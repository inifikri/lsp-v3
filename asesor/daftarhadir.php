<?php
ob_start();
include 'fpdf-easytable-master/fpdf.php';
include 'fpdf-easytable-master/exfpdf.php';
include 'fpdf-easytable-master/easyTable.php';
include "../config/koneksi.php";
include "../config/library.php";
include "../config/fungsi_indotgl.php";
$sqlidentitas="SELECT * FROM `identitas`";
$identitas=$conn->query($sqlidentitas);
$iden=$identitas->fetch_assoc();
/* $sqlasesi="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_GET[ida]'";
$asesi=$conn->query($sqlasesi);
$as=$asesi->fetch_assoc(); */
$sqljadwal="SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
$jadwal=$conn->query($sqljadwal);
$jdq=$jadwal->fetch_assoc();
$tgl_cetak = tgl_indo($jdq['tgl_asesmen_akhir']);
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
/* $sqlasesor="SELECT * FROM `asesor` WHERE `no_ktp`='$_SESSION[namauser]'";
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
} */
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
$tanggal = $jdq['tgl_asesmen_akhir'];
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
$keterangan1="Pada hari ini, Hari/Tanggal : ".$dayList[$day]."/ ".$tgl_cetak.",   Waktu : Pkl ".$jdq['jam_asesmen'];
$write->easyCell($keterangan1);
$write->printRow();
$keterangan2="bertempat di : ".$tq['nama']." Ruang .............................................................................................................................................., telah dilaksanakan proses asesmen terhadap peserta asesmen  sebagai berikut :";
$write->easyCell($keterangan2);
$write->printRow();
$write->endTable(0);
// fungsi terbilang =========================
function penyebut($nilai) {
	$nilai = abs($nilai);
	$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
	$temp = "";
	if ($nilai < 12) {
		$temp = " ". $huruf[$nilai];
	} else if ($nilai <20) {
		$temp = penyebut($nilai - 10). " belas";
	} else if ($nilai < 100) {
		$temp = penyebut($nilai/10)." puluh". penyebut($nilai % 10);
	} else if ($nilai < 200) {
		$temp = " seratus" . penyebut($nilai - 100);
	} else if ($nilai < 1000) {
		$temp = penyebut($nilai/100) . " ratus" . penyebut($nilai % 100);
	} else if ($nilai < 2000) {
		$temp = " seribu" . penyebut($nilai - 1000);
	} else if ($nilai < 1000000) {
		$temp = penyebut($nilai/1000) . " ribu" . penyebut($nilai % 1000);
	} else if ($nilai < 1000000000) {
		$temp = penyebut($nilai/1000000) . " juta" . penyebut($nilai % 1000000);
	} else if ($nilai < 1000000000000) {
		$temp = penyebut($nilai/1000000000) . " milyar" . penyebut(fmod($nilai,1000000000));
	} else if ($nilai < 1000000000000000) {
		$temp = penyebut($nilai/1000000000000) . " trilyun" . penyebut(fmod($nilai,1000000000000));
	}     
	return $temp;
}
function terbilang($nilai) {
	if($nilai<0) {
		$hasil = "minus ". trim(penyebut($nilai));
	} else {
		$hasil = trim(penyebut($nilai));
	}     		
	return $hasil;
}
// ==========================================
$write=new easyTable($pdf, '{70, 10, 110}', 'width:190; align:L; font-size:10;font-family:arial;');
$write->easyCell('Sektor/sub sektor/ bidang profesi');
$write->easyCell(':');
$write->easyCell($skemakkni);
$write->printRow();
$write->easyCell('Jumlah Asesi /Peserta yang mengikuti');
$write->easyCell(':');
$sqlasesmenasesix="SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`='$jdq[id]'";
$asesmenasesix=$conn->query($sqlasesmenasesix);
$jumasesix=$asesmenasesix->num_rows;
if ($jumasesix>0){
	$terbilangjumlahx=terbilang($jumasesix);
	$jumlahasesinya=$jumasesix."  (".$terbilangjumlahx.")  orang";
	$write->easyCell($jumlahasesinya);
}else{
	$write->easyCell('......  (.........)  orang');
}
$write->printRow();
$write->easyCell('Jumlah Asesi/Peserta  yang dinyatakan Kompeten');
$write->easyCell(':');
$sqlasesmenasesixK="SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`='$jdq[id]' AND `status_asesmen`='K'";
$asesmenasesixK=$conn->query($sqlasesmenasesixK);
$jumasesixK=$asesmenasesixK->num_rows;
if ($jumasesixK>0){
	$terbilangjumlahxK=terbilang($jumasesixK);
	$jumlahasesinyaK=$jumasesixK."  (".$terbilangjumlahxK.")  orang";
	$write->easyCell($jumlahasesinyaK);
}else{
	$write->easyCell('......  (.........)  orang');
}
$write->printRow();
$write->easyCell('Jumlah Asesi/Peserta  yang dinyatakan Belum Kompeten');
$write->easyCell(':');
$sqlasesmenasesixBK="SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`='$jdq[id]' AND `status_asesmen`='BK'";
$asesmenasesixBK=$conn->query($sqlasesmenasesixBK);
$jumasesixBK=$asesmenasesixBK->num_rows;
if ($jumasesixBK>0){
	$terbilangjumlahxBK=terbilang($jumasesixBK);
	$jumlahasesinyaBK=$jumasesixBK."  (".$terbilangjumlahxBK.")  orang";
	$write->easyCell($jumlahasesinyaBK);
}else{
	$write->easyCell('......  (.........)  orang');
}
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
	if ($asdj['status_asesmen']=='BK'){
		$write->easyCell('BELUM KOMPETEN','align:L; border:LTB');
		$sqlgetdatabkasesi="SELECT * FROM `asesi_apl02` WHERE `id_asesi`='$asdj[id_asesi]' AND `id_jadwal`='$jdq[id]' AND `keputusan`='BK'";
		$getdatabkasesi=$conn->query($sqlgetdatabkasesi);
		$jumbk=$getdatabkasesi->num_rows;
		$nobknya=1;
		$databk="";
		while ($getbk=$getdatabkasesi->fetch_assoc()){
			$sqlgetelemen="SELECT * FROM `elemen_kompetensi` WHERE `id`='$getbk[id_elemen]'";
			$getelemen=$conn->query($sqlgetelemen);
			$elemen=$getelemen->fetch_assoc();
			$ketbk="(".$nobknya.") belum kompeten ".$elemen['elemen_kompetensi'];
			$databk=$databk.$ketbk."; ";
			$nobknya++;
		}
	}elseif($asdj['status_asesmen']=='K'){
		$write->easyCell('KOMPETEN','align:L; border:LTB');
	}else{
		$write->easyCell('','align:L; border:LTB');
	}
	$keteranganbk=$asdj['umpan_balik'].": ".$databk;
	if ($asdj['status_asesmen']=='BK'){
		$write->easyCell($keteranganbk,'align:L; border:LTB');
	}else{
		$write->easyCell('','align:L; border:LTB');
	}
	$write->easyCell($asdj['umpan_balik_ia03'],'align:L; border:LTBR');
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
		
		// ttd asesor
		$sqlcekttdadminapl01="SELECT * FROM `logdigisign` WHERE `nama_dokumen`='FR.AK.01. FORMULIR PERSETUJUAN ASESMEN DAN KERAHASIAAN' AND `penandatangan`='$asr[nama]' ORDER BY `id` DESC";
		$cekttdadminapl01=$conn->query($sqlcekttdadminapl01);
		$jumttdadmin=$cekttdadminapl01->num_rows;
		$ttdad=$cekttdadminapl01->fetch_assoc();
		if ($jumttdadmin>0){
			$write->easyCell('Tanda tangan :','valign:T;');
			if ($noasr==1){
				$write->easyCell('Tanda tangan :','valign:T;');
			}
			$write->printRow();
			$write->easyCell('', 'img:'.$ttdad['file'].', h20; align:C; valign:T; font-size:10; font-style:B;');
		}else{
			$write->rowStyle('min-height:15');
			$write->easyCell('Tanda tangan :','valign:T;');
			if ($noasr==1){
				$write->rowStyle('min-height:15');
				$write->easyCell('Tanda tangan :','valign:T;');
			}
			$write->printRow();
			$write->easyCell('...........................................', 'align:C; valign:T; font-size:10; font-style:B;');
		}
		//$write->easyCell('...........................................');
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
$write=new easyTable($pdf, '{10, 40, 40, 60, 40, 40, 47}', 'width:277; align:L; border-color:#000000; font-size:9;font-family:arial;');
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
	$write->easyCell($alamatasesi, 'border:LTB; font-size:7');
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
	// tandatangan Asesi
	$urltandatangan=$iden['url_domain']."/media.php?module=form-fr-ak-01&amp;ida=".$dt['id_asesi']."&amp;idj=".$jdq['id'];
	$sqlcekttdasesiapl01="SELECT * FROM `logdigisign` WHERE `nama_dokumen`='FR.AK.01. FORMULIR PERSETUJUAN ASESMEN DAN KERAHASIAAN' AND `penandatangan`='".$dtm['nama']."' AND `url_ditandatangani`='$urltandatangan' ORDER BY `id` DESC";
	$cekttdasesiapl01=$conn->query($sqlcekttdasesiapl01);
	$jumttdasesi=$cekttdasesiapl01->num_rows;
	$ttdas=$cekttdasesiapl01->fetch_assoc();
	$urltandatangan2=$iden['url_domain']."/media.php?module=form-apl-02-el&amp;ida=".$dt['id_asesi']."&amp;idj=".$jdq['id'];
	$sqlcekttdasesiapl012="SELECT * FROM `logdigisign` WHERE `nama_dokumen`='FR.APL.02. ASESMEN MANDIRI' AND `penandatangan`='".$dtm['nama']."' AND `url_ditandatangani`='$urltandatangan' ORDER BY `id` DESC";
	$cekttdasesiapl012=$conn->query($sqlcekttdasesiapl012);
	$jumttdasesi2=$cekttdasesiapl012->num_rows;
	$ttdas2=$cekttdasesiapl012->fetch_assoc();
	if ($jumttdasesi>0){
		$write->easyCell($nogangen, 'img:../'.$ttdas['file'].', h20; align:L; valign:T; border:LTBR');
	}else{
		if ($jumttdasesi2>0){
			$write->easyCell($nogangen, 'img:../'.$ttdas2['file'].', h20; align:L; valign:T; border:LTBR');
		}else{
			$write->easyCell($nogangen, 'border:LTBR');
		}
	}
	$write->printRow();
	$nomnya++;
}
$write->endTable(10);
$write=new easyTable($pdf, '{90,90,97}', 'width:190; align:L; font-size:10;font-family:arial;');
$write->printRow();
$noasr0=1;
$getasesor0=$conn->query("SELECT * FROM `jadwal_asesor` WHERE `id_jadwal`='$_GET[idj]'");
while ($gas0=$getasesor0->fetch_assoc()){
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
	//$urltandatanganadmin=$iden['url_domain']."/asesor/media.php?module=form-fr-ak-01&amp;ida=".$dt['id_asesi']."&amp;idj=".$jdq['id'];
	$sqlcekttdadminapl01="SELECT * FROM `logdigisign` WHERE `nama_dokumen`='FR.AK.01. FORMULIR PERSETUJUAN ASESMEN DAN KERAHASIAAN' AND `penandatangan`='$asr[nama]' ORDER BY `id` DESC";
	$cekttdadminapl01=$conn->query($sqlcekttdadminapl01);
	$jumttdadmin=$cekttdadminapl01->num_rows;
	$ttdad=$cekttdadminapl01->fetch_assoc();
	if ($jumttdadmin>0){
		$write->easyCell('', 'img:'.$ttdad['file'].', h20; align:C; valign:T; font-size:10; font-style:B;');
	}else{
		$write->rowStyle('min-height:30');
		$write->easyCell('', 'align:C; valign:T; font-size:10; font-style:B;');
	}
	$noasr++;
}
$write->printRow();
$getasesor1=$conn->query("SELECT * FROM `jadwal_asesor` WHERE `id_jadwal`='$_GET[idj]'");
while ($gas1=$getasesor1->fetch_assoc()){
	$asesornya="( ".$namaasesor." )";
	//$asesornya1=$asesornya1.$asesornya;
	$noregasr="No. Reg ".$asr['no_induk'];
	$write->easyCell($asesornya,'align:C;font-style:B');
}
$write->printRow();
$write->endTable(5);
$pdf->AliasNbPages();
//output file pdf
$fileoutputnya="daftar-hadir-berita-acara-".$skemakkni."-".$_GET['idj'].".pdf";
$pdf->Output($fileoutputnya,'D');
ob_end_flush();
?>