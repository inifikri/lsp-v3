<?php

include 'fpdf-easytable-master/fpdf.php';
include 'fpdf-easytable-master/exfpdf.php';
include 'fpdf-easytable-master/easyTable.php';
include "../config/koneksi.php";
include "../config/library.php";
include "../config/fungsi_indotgl.php";





ini_set('display_errors', 0);
error_reporting(E_ALL);
ob_start();


$sqlasesi="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_GET[ida]'";
$asesi=$conn->query($sqlasesi);
$as=$asesi->fetch_assoc();
$sqljadwal = "SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
$jadwal = $conn->query($sqljadwal);
$jdq = $jadwal->fetch_assoc();
$tgl_cetak = tgl_indo($jdq['tgl_asesmen']);
$sqltuk = "SELECT * FROM `tuk` WHERE `id`='$jdq[tempat_asesmen]'";
$tuk = $conn->query($sqltuk);
$tq = $tuk->fetch_assoc();
$sqllsp = "SELECT * FROM `lsp` ORDER BY `id` ASC LIMIT 1";
$lsp = $conn->query($sqllsp);
$lq = $lsp->fetch_assoc();
$sqlskema = "SELECT * FROM `skema_kkni` WHERE `id`='$jdq[id_skemakkni]'";
$skema = $conn->query($sqlskema);
$sq = $skema->fetch_assoc();
$skemakkni = $sq['judul'];
$sqlwil1 = "SELECT * FROM `data_wilayah` WHERE `id_wil`='$lq[id_wilayah]'";
$wilayah1 = $conn->query($sqlwil1);
$wil1 = $wilayah1->fetch_assoc();
$sqlwil2 = "SELECT * FROM `data_wilayah` WHERE `id_wil`='$wil1[id_induk_wilayah]'";
$wilayah2 = $conn->query($sqlwil2);
$wil2 = $wilayah2->fetch_assoc();
$sqlwil3 = "SELECT * FROM `data_wilayah` WHERE `id_wil`='$wil2[id_induk_wilayah]'";
$wilayah3 = $conn->query($sqlwil3);
$wil3 = $wilayah3->fetch_assoc();
$sqlwil1b = "SELECT * FROM `data_wilayah` WHERE `id_wil`='$tq[id_wilayah]'";
$wilayah1b = $conn->query($sqlwil1b);
$wil1b = $wilayah1b->fetch_assoc();
$sqlwil2b = "SELECT * FROM `data_wilayah` WHERE `id_wil`='$wil1b[id_induk_wilayah]'";
$wilayah2b = $conn->query($sqlwil2b);
$wil2b = $wilayah2b->fetch_assoc();
$sqlwil3b = "SELECT * FROM `data_wilayah` WHERE `id_wil`='$wil2b[id_induk_wilayah]'";
$wilayah3b = $conn->query($sqlwil3b);
$wil3b = $wilayah3b->fetch_assoc();


//Data Asesmen
$sqlgetkeputusan="SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
$getkeputusan=$conn->query($sqlgetkeputusan);
$getk=$getkeputusan->fetch_assoc();


$pdf = new exFPDF('P', 'mm', 'A4'); 
$left=10; $top=12; $right=10; $bottom=12;
$pdf->SetMargins($left,$top,$right);
$pdf->SetAutoPageBreak(true, $bottom);
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);
$pdf->AddFont('FontUTF8', '', 'Arimo-Regular.php');
$pdf->AddFont('FontUTF8', 'B', 'Arimo-Bold.php');
$pdf->AddFont('FontUTF8', 'I', 'Arimo-Italic.php');
$pdf->AddFont('FontUTF8', 'BI', 'Arimo-BoldItalic.php');
// kop LSP ======================================================
//tampilan Form
$id_wilayah = trim($wil1['nm_wil']);
$id_wilayah2 = trim($wil2['nm_wil']) . ", " . trim($wil3['nm_wil']);
$namalsp = strtoupper($lq['nama']);
$alamatlsp = $lq['alamat'] . " " . $lq['kelurahan'] . " " . $id_wilayah;
$alamatlsp2 = $id_wilayah2 . " Kodepos : " . $lq['kodepos'];
$telpemail = "Telp./Fax.: " . $lq['telepon'] . " / " . $lq['fax'] . " Email : " . $lq['email'] . ", " . $lq['website'];
$tampilperiode = "Periode " . $jdq['periode'] . " Tahun " . $jdq['tahun'] . " Gelombang " . $jdq['gelombang'];
$nomorlisensi = "Nomor Lisensi : " . $lq['no_lisensi'];
$alamatlsptampil = $alamatlsp . " " . $alamatlsp2 . " " . $telpemail;
//$pdf->Cell(0, 5, '', '0', 1, 'C');
$pdf->Ln();
$write = new easyTable($pdf, '{30, 130, 30}', 'width:190; align:L; font-style:B; font-family:arial;');
$write->easyCell('', 'img:../images/logolsp.jpg, w25, h14; align:C; rowspan:3');
$write->easyCell($namalsp, 'align:C; font-size:14;');
$write->easyCell('', 'img:../images/logo-bnsp.jpg, w25, h14;align:C; rowspan:3');
$write->printRow();
$write->easyCell($nomorlisensi, 'align:C; font-size:10;');
$write->printRow();
$write->easyCell($alamatlsptampil, 'align:C; font-size:10;');
$write->printRow();
$write->endTable(5);
//===============================================================
$write = new easyTable($pdf, 1, 'width:190;  font-style:B; font-size:10;font-family:arial;');
$write->easyCell('FR.AK.07 - CEKLIS PENYESUAIAN YANG WAJAR DAN BERALASAN', 'align:L;');
$write->printRow();
$write->endTable(5);
$write = new easyTable($pdf, '{50, 20, 5, 115}', 'width:190; align:L; font-family:arial; font-size:10');
$write->easyCell('Skema Sertifikasi (KKNI/Okupasi/Klaster)', 'align:L; rowspan:2; border:LTBR');
$write->easyCell('Judul', 'align:L; border:LTBR');
$write->easyCell(':', 'align:C; border:LTBR');
$write->easyCell($skemakkni, 'align:L;font-style:B; border:LTBR');
$write->printRow();
$write->easyCell('Nomor', 'align:L; border:LTBR');
$write->easyCell(':', 'align:C; border:LTBR');
$write->easyCell($sq['kode_skema'], 'align:L;font-style:B; border:LTBR');
$write->printRow();
$write->easyCell('TUK', 'align:L; colspan:2; border:LTBR');
$write->easyCell(':', 'align:C; border:LTBR');
$sqlgetjenistuk = "SELECT * FROM `tuk_jenis` WHERE `id`='$tq[jenis_tuk]'";
$getjenistuk = $conn->query($sqlgetjenistuk);
$jnstuk = $getjenistuk->fetch_assoc();
$write->easyCell($jnstuk['jenis_tuk'], 'align:L; border:LTBR');
$write->printRow();
$noasr = 1;
$getasesor = $conn->query("SELECT * FROM `jadwal_asesor` WHERE `id_jadwal`='$_GET[idj]'");
while ($gas = $getasesor->fetch_assoc()) {
  $sqlasesor = "SELECT * FROM `asesor` WHERE `id`='$gas[id_asesor]'";
  $asesor = $conn->query($sqlasesor);
  $asr = $asesor->fetch_assoc();
  if (!empty($asr['gelar_depan'])) {
    if (!empty($asr['gelar_blk'])) {
      $namaasesor = $asr['gelar_depan'] . " " . $asr['nama'] . ", " . $asr['gelar_blk'];
    } else {
      $namaasesor = $asr['gelar_depan'] . " " . $asr['nama'];
    }
  } else {
    if (!empty($asr['gelar_blk'])) {
      $namaasesor = $asr['nama'] . ", " . $asr['gelar_blk'];
    } else {
      $namaasesor = $asr['nama'];
    }
  }
  $noregasesor = $asr['no_induk'];
  $namaasesor = $noasr . '. ' . $namaasesor;
  $noregasesor = $noasr . '. ' . $noregasesor;
  $noasr++;
}
$write->easyCell('Nama Asesor', 'align:L; colspan:2; border:LTBR');
$write->easyCell(':', 'align:C; font-size:10; border:LTBR');
$write->easyCell($namaasesor, 'align:L; border:LTBR');
$write->printRow();
$write->easyCell('Nama Asesi', 'align:L; colspan:2; border:LTBR');
$write->easyCell(':', 'align:C; font-size:10; border:LTBR');
$write->easyCell($as['nama'], 'align:L; border:LTBR');
$write->printRow();
$write->easyCell('Tanggal', 'align:L; colspan:2; border:LTBR');
$write->easyCell(':', 'align:C; border:LTBR');
$write->easyCell($tgl_cetak, 'align:L; border:LTBR');
$write->printRow();
$write->endTable(5);


$sqlgetak07="SELECT * FROM `asesmen_ak07` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_skemakkni`='$jdq[id_skemakkni]'";
$getak07=$conn->query($sqlgetak07);
$gak07=$getak07->fetch_assoc();

// var_dump($gak07);


$write = new easyTable($pdf, '{10,180}', 'width:190; align:L; font-family:arial; font-size:10');

$write->easyCell('Panduan Bagi Asesor ', 'align:L; border:LTR; colspan:2; font-style:B;');
$write->printRow();

// Bullet 1
$write->easyCell(chr(149), 'align:L; border:L;');
$write->easyCell('Formulir ini dapat digunakan (sebelum pra asesmen, saat pelaksanaan pra asesmen, setelah pra asesmen)* jika ada asesi yang mempunyai keterbatasan sesuai karakteristik yang dimilikinya sehingga 
diperlukan penyesuaian yang wajar dan beralasan, jika rencana asesmen dan perangkat asesmen tidak 
sesuai dengan acuan pembanding, potensi asesi dan konteks asesi, jika asesi merasa keletihan, sakit, 
serta jika kondisi alam, listrik padam,..........', 'align:L; border:R;');
$write->printRow();

// Bullet 2
$write->easyCell(chr(149), 'align:L; border:L;');
$write->easyCell('Coretlah pada tanda * yang tidak sesuai.', 'align:L; border:R;');
$write->printRow();



// Bullet3
$write->easyCell(chr(149), 'align:L; border:L;');
$write->easyCell('Berilah tanda ceklis pada kotak pada kolom potensi asesi ', 'align:L; border:R;');
$write->printRow();

// Bullet 3
$write->easyCell(chr(149), 'align:L; border:LB;');
$write->easyCell('Berilah tanda ceklis Ya atau Tidak pada tanda ** sesuai pilihan, jika jawaban Ya selanjutanya pada kolom keterangan 
berilah tanda ceklis di kotak yang tersedia, pilihan boleh  lebih dari satu. ', 'align:L; border:RB;');
$write->printRow();

$write->endTable(5);



//=================================
$write = new easyTable(
  $pdf,
  '{60,15,115}',
  'width:190; align:L; font-family:arial; font-size:10'
);

$write->rowStyle('min-height:15');

// Kolom pertama "Potensi Asesi" sekali saja, span ke 5 baris
$write->easyCell(
  'Potensi Asesi',
  'rowspan:5; align:L; valign:M; border:LTBR;'
);

// --- Baris 1
if ($gak07['PA1'] == "1") {
    $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:TBR;');
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:TBR;');
}
$write->easyCell(
    'Hasil pelatihan dan / atau pendidikan, dimana Kurikulum dan fasilitas praktek mampu telusur terhadap standar kompetensi',
    'align:L; valign:M; border:TBR;'
);
$write->printRow();


// --- Baris 2
if ($gak07['PA2'] == "1") {
    $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:TBR;');
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:TBR;');
}
$write->easyCell('Hasil pelatihan dan / atau pendidikan, dimana kurikulum belum praktek mampu telusur terhadap standar kompetensi berbasis kompetensi.', 'align:L; valign:M; border:TBR;');
$write->printRow();

// --- Baris 3
if ($gak07['PA3'] == "1") {
    $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:TBR;');
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:TBR;');
}
$write->easyCell('Pekerja berpengalaman, dimana berasal dari industri/tempat kerja yang dalam operasionalnya mampu telusur dengan standar kompetensi', 'align:L; valign:M; border:TBR;');
$write->printRow();

// --- Baris 4
if ($gak07['PA4'] == "1") {
    $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:TBR;');
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:TBR;');
}
$write->easyCell('Pekerja berpengalaman, dimana berasal dari industri/tempat kerja yang dalam operasionalnya belum berbasis kompetensi.', 'align:L; valign:M; border:TBR;');
$write->printRow();

// --- Baris 5
if ($gak07['PA5'] == "1") {
    $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:TBR;');
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:TBR;');
}
$write->easyCell('Pelatihan / belajar mandiri atau otodidak.', 'align:L; valign:M; border:TBR;');
$write->printRow();

$write->endTable(5);



//=================================
$write = new easyTable(
  $pdf,
  '{10,60,20,20,10,70}', // No | Aspek | Ya | Tidak | Ceklis | Keterangan
  'width:190; align:L; font-family:arial; font-size:10'
);

// ===== HEADER =====
$write->easyCell('No.', 'align:C; valign:M; border:LTR; rowspan:2;');
$write->easyCell(
  'Mengidentifikasi Persyaratan Modifikasi dan Kontekstualisasi (karakteristik asesi) :',
  'align:C; valign:M; border:LTR; rowspan:2;'
);
$write->easyCell('Diperlukan penyesuaian**', 'align:C; valign:M; border:LTR; colspan:2;');
$write->easyCell('Keterangan', 'align:C; valign:M; border:LTR; colspan:2; rowspan:2;');
$write->printRow();

$write->easyCell('Ya',    'align:C; valign:M; border:LTR; font-size:10;');
$write->easyCell('Tidak', 'align:C; valign:M; border:LTR; font-size:10;');
$write->printRow();

// $jumlahIsi = 5;

// Baris pertama (No. + Aspek + Ya/Tidak rowspan 6)
$write->easyCell('1.', 'align:C; valign:M; border:LTR; rowspan:6;');
$write->easyCell('Keterbatasan asesi terhadap persyaratan bahasa, literasi, numerasi.', 'align:L; valign:M; border:LTR; rowspan:6;');

// YA
if ($gak07['KA1'] == "1") {
    $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTR; rowspan:6;');
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTR; rowspan:6;');
}

// TIDAK
if ($gak07['KA1'] == "0") {
    $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTR; rowspan:6;');
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTR; rowspan:6;');
}


if ($gak07['KET1_1'] == "1") {
    $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTR;');
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTR;');
}
$write->easyCell(
  'Memerlukan dukungan pembaca, penerjemah, pelayan, penulis untuk merekam jawaban asesi.',
  'align:L; valign:T; border:LTR;'
);
$write->printRow();


if ($gak07['KET1_2'] == "1") {
    $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTR;');
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTR;');
}
$write->easyCell(
  'Melakukan asesmen verbal (gunakan pertanyaan lisan/pertanyaan wawancara) dengan dilengkapi gambar diagram dan bentuk visual.',
  'align:L; valign:T; border:LTR;'
);
$write->printRow();


if ($gak07['KET1_3'] == "1") {
    $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTR;');
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTR;');
}
$write->easyCell('Menggunakan Hasil produksi', 'align:L; valign:T; border:LTR;');
$write->printRow();


if ($gak07['KET1_4'] == "1") {
    $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTR;');
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTR;');
}
$write->easyCell('Menggunakan Ceklis observasi/demonstrasi', 'align:L; valign:T; border:LTR;');
$write->printRow();


if ($gak07['KET1_5'] == "1") {
    $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTR;');
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTR;');
}
$write->easyCell('Menggunakan pertanyaan lisan dengan dilengkapi gambar diagram dan bentuk-bentuk visual.', 'align:L; valign:T; border:LTRB;');
$write->printRow();


if ($gak07['KET1_6'] == "1") {
    $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTR;');
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTR;');
}
$write->easyCell('...................................................', 'align:L; valign:T; border:LBR;');
$write->printRow();

// ===== Baris kedua
$write->easyCell('2.', 'align:C; valign:M; border:LTBR; rowspan:3;');
$write->easyCell('Penyediaan dukungan pembaca, penerjemah, pelayan, penulis.', 'align:L; valign:M; border:LTBR; rowspan:3;');


// YA
if ($gak07['KA2'] == "1") {
   $write->easyCell(
  '',
  'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTBR; rowspan:3;'
);
} else {
   $write->easyCell(
  '',
  'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTBR; rowspan:3;'
);
}

// TIDAK
if ($gak07['KA2'] == "0") {
  $write->easyCell(
  '',
  'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTBR; rowspan:3;'
);
} else {
 $write->easyCell(
  '',
  'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTBR; rowspan:3;'
);
}


if ($gak07['KET2_1'] == "1") {
    $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTR;');
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTR;');
}
$write->easyCell(
  'Menggunakan pertanyaan lisan dengan dilengkapi gambar diagram dan bentuk-bentuk visual.',
  'align:L; valign:T; border:LTRB;'
);
$write->printRow();


if ($gak07['KET2_2'] == "1") {
    $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTR;');
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTR;');
}
$write->easyCell(
  'Menggunakan pertanyaan wawancara dengan dilengkapi gambar diagram dan bentuk-bentuk visual.',
  'align:L; valign:T; border:LTRB;'
);
$write->printRow();


if ($gak07['KET2_3'] == "1") {
    $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTR;');
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTR;');
}
$write->easyCell('...................................................', 'align:L; valign:T; border:LBR;');
$write->printRow();

/* =========================
   BARIS KETIGA
   ========================= */
$write->easyCell('3.', 'align:C; valign:M; border:LTBR; rowspan:8;');
$write->easyCell('Penggunaan teknologi adaptif atau peralatan khusus. (Tidak dapat menggunakan teknologi adaptif, misal: mengoperasikan komputer dan printer, peralatan digital dsb).', 'align:L; valign:M; border:LTBR; rowspan:8;');

// YA
if ($gak07['KA3'] == "1") {
   $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTBR; rowspan:8;'); // YA
} else {
   $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTBR; rowspan:8;'); // YA
}

// TIDAK
if ($gak07['KA3'] == "0") {
 $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTBR; rowspan:8;'); // YA
} else {
$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTBR; rowspan:8;'); // YA
}

if ($gak07['KET3_1'] == "1") {
    $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTR;');
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTR;');
}
$write->easyCell('Ceklis observasi/demonstrasi Demonstrasi.', 'align:L; valign:T; border:LTRB;'); $write->printRow();
if ($gak07['KET3_2'] == "1") {
    $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTR;');
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTR;');
}
$write->easyCell('Pertanyaan lisan', 'align:L; valign:T; border:LTRB;'); $write->printRow();
if ($gak07['KET3_3'] == "1") {
    $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTR;');
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTR;');
}
$write->easyCell('Pertanyaan tertulis.', 'align:L; valign:T; border:LTRB;'); $write->printRow();
if ($gak07['KET3_4'] == "1") {
    $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTR;');
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTR;');
}
$write->easyCell('Pertanyaan wawancara.', 'align:L; valign:T; border:LTRB;'); $write->printRow();
if ($gak07['KET3_5'] == "1") {
    $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTR;');
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTR;');
}
$write->easyCell('Daftar instruksi terstruktur.', 'align:L; valign:T; border:LTRB;'); $write->printRow();
if ($gak07['KET3_6'] == "1") {
    $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTR;');
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTR;');
}
$write->easyCell('Ceklis verifikasi portofolio.', 'align:L; valign:T; border:LTRB;'); $write->printRow();
if ($gak07['KET3_7'] == "1") {
    $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTR;');
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTR;');
}
$write->easyCell('Menggunakan dukungan operator komputer.', 'align:L; valign:T; border:LTRB;'); $write->printRow();
if ($gak07['KET3_8'] == "1") {
    $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTR;');
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTR;');
}
$write->easyCell('...................................', 'align:L; valign:T; border:LBR;');  $write->printRow();

/* =========================
   BARIS KEEMPAT
   ========================= */
$write->easyCell('4.', 'align:C; valign:M; border:LTBR; rowspan:6;');
$write->easyCell('Pelaksanaan asesmen secara fleksibel karena alasan keletihan atau keperluan pengobatan.', 'align:L; valign:M; border:LTBR; rowspan:6;');


// YA
if ($gak07['KA4'] == "1") {
   $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTBR; rowspan:6;'); // YA
} else {
$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTBR; rowspan:6;');
}

// TIDAK
if ($gak07['KA4'] == "0") {
$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTBR; rowspan:6;');
} else {
$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTBR; rowspan:6;');
}

if ($gak07['KET4_1'] == "1") {
    $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTR;');
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTR;');
}
$write->easyCell('Menggunakan juru tulis.', 'align:L; valign:T; border:LTRB;'); $write->printRow();
if ($gak07['KET4_2'] == "1") {
    $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTR;');
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTR;');
}
$write->easyCell('Menggunakan kameramen perekam vidio/atau audio.', 'align:L; valign:T; border:LTRB;'); $write->printRow();
if ($gak07['KET4_3'] == "1") {
    $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTR;');
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTR;');
}
$write->easyCell('Memperbolehkan periode waktu yang lebih panjang untuk menyelesaikan tugas pekerjaan dalam asesmen.', 'align:L; valign:T; border:LTRB;'); $write->printRow();
if ($gak07['KET4_4'] == "1") {
    $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTR;');
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTR;');
}
$write->easyCell('Melakukan tugas pekerjaan dalam asesmen dengan waktu lebih pendek.', 'align:L; valign:T; border:LTRB;'); $write->printRow();
if ($gak07['KET4_5'] == "1") {
    $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTR;');
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTR;');
}
$write->easyCell('Menggunakan instruksi-instruksi spesifik pada proyek yang dapat dilakukan pada berbagai tingkatan.', 'align:L; valign:T; border:LTRB;'); $write->printRow();
if ($gak07['KET4_6'] == "1") {
    $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTR;');
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTR;');
}
$write->easyCell('.....................................', 'align:L; valign:T; border:LBR;');  $write->printRow();

/* =========================
   BARIS KELIMA
   ========================= */
$write->easyCell('5.', 'align:C; valign:M; border:LTBR; rowspan:3;');
$write->easyCell('Penyediaan peralatan asesmen berupa brailie, audio/video-tape.', 'align:L; valign:M; border:LTBR; rowspan:3;');


// YA
if ($gak07['KA5'] == "1") {
   $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTBR; rowspan:3;'); // YA
} else {
$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTBR; rowspan:3;');
}

// TIDAK
if ($gak07['KA5'] == "0") {
$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTBR; rowspan:3;');
} else {
$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTBR; rowspan:3;');
}


if ($gak07['KET5_1'] == "1") {
    $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTR;');
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTR;');
}
$write->easyCell('Menggunakan pertanyaann lisan.', 'align:L; valign:T; border:LTRB;'); $write->printRow();
if ($gak07['KET5_2'] == "1") {
    $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTR;');
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTR;');
}
$write->easyCell('Menggunakan pertanyaan wawancara.', 'align:L; valign:T; border:LTRB;'); $write->printRow();
if ($gak07['KET5_3'] == "1") {
    $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LBR;');
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LBR;');
}
$write->easyCell('......................................', 'align:L; valign:T; border:LBR;');  $write->printRow();

/* =========================
   BARIS KEENAM
   ========================= */
$write->easyCell('6.', 'align:C; valign:M; border:LTBR; rowspan:7;');
$write->easyCell('Penyesuaian tempat fisik/lingkungan asesmen', 'align:L; valign:M; border:LTBR; rowspan:7;');

// YA
if ($gak07['KA6'] == "1") {
$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTBR; rowspan:7;');
} else {
$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTBR; rowspan:7;');
}

// TIDAK
if ($gak07['KA6'] == "0") {
$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTBR; rowspan:7;');
} else {
$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTBR; rowspan:7;');
}

if ($gak07['KET6_1'] == "1") {
    $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTR;');
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTR;');
}
$write->easyCell('Pertanyaan lisan.', 'align:L; valign:T; border:LTRB;'); $write->printRow();
if ($gak07['KET6_2'] == "1") {
    $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTR;');
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTR;');
}$write->easyCell('Pertanyaan tulis.', 'align:L; valign:T; border:LTRB;'); $write->printRow();
if ($gak07['KET6_3'] == "1") {
    $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTR;');
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTR;');
}
$write->easyCell('Pertanyaan wawancara.', 'align:L; valign:T; border:LTRB;'); $write->printRow();
if ($gak07['KET6_4'] == "1") {
    $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTR;');
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTR;');
}
$write->easyCell('Ceklis Verifikasi portofolio', 'align:L; valign:T; border:LTRB;'); $write->printRow();
if ($gak07['KET6_5'] == "1") {
    $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTR;');
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTR;');
}
$write->easyCell('Ceklis reviu produk.', 'align:L; valign:T; border:LTRB;'); $write->printRow();
if ($gak07['KET6_6'] == "1") {
    $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTR;');
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTR;');
}
$write->easyCell('Daftar instruksi terstruktur.', 'align:L; valign:T; border:LTRB;'); $write->printRow();
if ($gak07['KET6_7'] == "1") {
    $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTR;');
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTR;');
}
$write->easyCell('...................', 'align:L; valign:T; border:LBR;');  $write->printRow();

/* =========================
   BARIS KETUJUH
   ========================= */
$write->easyCell('7.', 'align:C; valign:M; border:LTBR; rowspan:5;');
$write->easyCell('Pertimbangan umur/usia lanjut/gender asesi. (Adanya perbedaan usia dengan asesor yang lebih muda).', 'align:L; valign:M; border:LTBR; rowspan:5;');


// YA
if ($gak07['KA7'] == "1") {
$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTBR; rowspan:5;');
} else {
$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTBR; rowspan:5;');
}

// TIDAK
if ($gak07['KA7'] == "0") {
$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTBR; rowspan:5;');
} else {
$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTBR; rowspan:5;');
}

if ($gak07['KET7_1'] == "1") {
    $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTR;');
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTR;');
}
$write->easyCell('Menggunakan studi kasus/daftar instruksi terstruktur.', 'align:L; valign:T; border:LTRB;'); $write->printRow();
if ($gak07['KET7_2'] == "1") {
    $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTR;');
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTR;');
}
$write->easyCell('Menggunakan instrumen asesmen dengan huruf normal jangan terlalu kecil.', 'align:L; valign:T; border:LTRB;'); $write->printRow();
if ($gak07['KET7_3'] == "1") {
    $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTR;');
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTR;');
}
$write->easyCell('Menggunakan asesor dengan jenis kelamin yang sama dengan asesi.', 'align:L; valign:T; border:LTRB;'); $write->printRow();
if ($gak07['KET7_4'] == "1") {
    $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTR;');
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTR;');
}
$write->easyCell('Menggunakan instrumen asesmen yang sama walaupun berbeda jenis kelamin (tidak boleh memberi tanda tambahan pada instrumen asesmen yang digunakan dengan tujuan untuk membedakan jenis kelamin).', 'align:L; valign:T; border:LTRB;'); $write->printRow();
if ($gak07['KET7_5'] == "1") {
    $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTR;');
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTR;');
}
$write->easyCell('.........................', 'align:L; valign:T; border:LBR;');  $write->printRow();

/* =========================
   BARIS KEDELAPAN (4 KETERANGAN)
   ========================= */
$write->easyCell('8.', 'align:C; valign:M; border:LTBR; rowspan:4;');
$write->easyCell('Pertimbangan budaya/tradisi/agama.', 'align:L; valign:M; border:LTBR; rowspan:4;');


// YA
if ($gak07['KA8'] == "1") {
$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTBR; rowspan:4;');
} else {
$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTBR; rowspan:4;');
}

// TIDAK
if ($gak07['KA8'] == "0") {
$write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTBR; rowspan:4;');
} else {
$write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTBR; rowspan:4;');
}



if ($gak07['KET8_1'] == "1") {
    $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTR;');
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTR;');
}
$write->easyCell('Menggunakan studi kasus daftar instruksi terstruktur', 'align:L; valign:T; border:LTRB;'); $write->printRow();
if ($gak07['KET8_2'] == "1") {
    $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTR;');
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTR;');
}
$write->easyCell('Menggunakan asesor tanpa pertimbangan budaya/tradisi/agama.', 'align:L; valign:T; border:LTRB;'); $write->printRow();

if ($gak07['KET8_3'] == "1") {
   $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LTRB;'); 
} else {
    $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LTRB;'); 
}
$write->easyCell('Menggunakan instrumen asesmen yang sama walaupun berbeda budaya/tradisi/agama.', 'align:L; valign:T; border:LTRB;'); $write->printRow();


if ($gak07['KET8_4'] == "1") {
   $write->easyCell('', 'img:../images/checked.jpg, w5, h5; align:C; valign:M; border:LBR;');  
} else {
 $write->easyCell('', 'img:../images/unchecked.jpg, w5, h5; align:C; valign:M; border:LBR;');  
}

$write->easyCell('................................', 'align:L; valign:T; border:LBR;');  $write->printRow();

$write->endTable(5);




$write = new easyTable($pdf, '{190}', 'width:190; align:L; font-family:arial; font-size:10;');
$write->rowStyle('min-height:6; paddingY:1.5;');

// Judul (tebal), tanpa garis bawah di dalam kotak
$write->easyCell('Hasil Penyesuaian yang wajar dan berasalan disepakati menggunakan : ', 'align:L; valign:M; font-style:B; border:LTR;');
$write->printRow();

$isi = 
  '1. Acuan Pembanding Asesmen: ' . ($gak07['acuan'] ?? '') . "\n" .
  '2. Metode Asesmen: ' . ($gak07['metode'] ?? '') . "\n" .
  '3. Instrumen Asesmen: ' . ($gak07['instrumen'] ?? '');

$write->easyCell($isi, 'align:L; valign:T; border:LBR;');
$write->printRow();

$write->endTable(5);




$write = new easyTable($pdf, '{100,30,60}', 'width:190; align:L font-family:arial; font-size:10');
$write->easyCell('Nama Asesor:
 ', 'align:L; valign:T; border:LTR; font-size:10; font-style:B; rowspan:4');
// $write->easyCell('Tanggal dan Tanda Tangan Asesor:', 'align:L; font-size:10; font-style:B; border:LTBR; colspan:2');
$write->printRow();
$write->easyCell('Nama', 'align:L; font-size:10; border:LTBR;');
$write->easyCell($namaasesor, 'align:L; font-size:10; border:LTBR;');
$write->printRow();
// $write->easyCell('No. Reg', 'align:L; font-size:10; border:LTBR;');
// $write->easyCell($noregasesor, 'align:L; font-size:10; border:LTBR;');
// $write->printRow();
$write->easyCell('Tanda Tangan/
Tanggal', 'align:L; font-size:10; border:LTR;');
// tandatangan asesor
$sqlidentitas = "SELECT * FROM `identitas`";
$identitas = $conn->query($sqlidentitas);
$iden = $identitas->fetch_assoc();
$urltandatanganas = $iden['url_domain'] . "/asesor/media.php?module=form-fr-ak-07&amp;idj=" . $jdq['id'];
$sqlcekttdasesorak07as = "SELECT * FROM `logdigisign` WHERE id_skema='$jdq[id_skemakkni]' AND `nama_dokumen`='FR.AK.07.CEKLIS PENYESUAIAN YANG WAJAR DAN BERALASAN' AND `penandatangan`='$asr[nama]' AND id_jadwal='$_GET[idj]' ORDER BY `id` DESC";
$cekttdasesorak07 = $conn->query($sqlcekttdasesorak07as);
$jumttdasesor = $cekttdasesorak07->num_rows;
$ttdasesor = $cekttdasesorak07->fetch_assoc();
if ($jumttdasesor > 0) {
  $write->easyCell('', 'img:' . $ttdasesor['file'] . ', h20; align:C; valign:T; font-size:10; font-style:B; border:R;');
} else {
  $write->easyCell('', 'align:L; valign:T; font-size:10; font-style:B; border:R;');
}
$write->printRow();
$tglttdnya = tgl_indo($ttdasesor['waktu']);
$write->easyCell('', 'align:L; font-size:10; border:LBR;');
$write->easyCell($tglttdnya, 'align:C; font-size:10; border:LBR;');
$write->easyCell('', 'align:L; font-size:10; border:LBR;');
$write->printRow();
$write->endTable(0);

$write = new easyTable($pdf, '{100,30,60}', 'width:190; align:L font-family:arial; font-size:10');
$write->easyCell('Nama Asesi:
 ', 'align:L; valign:T; border:LTBR; font-size:10; font-style:B; rowspan:4');
// $write->easyCell('Tanggal dan Tanda Tangan Asesi:', 'align:L; font-size:10; font-style:B; border:LTBR; colspan:2');
$write->printRow();
$write->easyCell('Nama', 'align:L; font-size:10; border:LTBR;');
$write->easyCell($as['nama'], 'align:L; font-size:10; border:LTBR;');
$write->printRow();

$write->easyCell('Tanda Tangan/
Tanggal', 'align:L; font-size:10; border:LTR;');
// tandatangan asesi
$sqlidentitas = "SELECT * FROM `identitas`";
$identitas = $conn->query($sqlidentitas);
$iden = $identitas->fetch_assoc();
$urltandatanganas = $iden['url_domain'] . "/asesor/media.php?module=form-fr-ak-07&amp;idj=" . $jdq['id'];
$sqlcekttdasesiak07as = "SELECT * FROM `logdigisign` WHERE id_skema='$jdq[id_skemakkni]' AND id_asesi='$as[no_pendaftaran]' AND `penandatangan`='$as[nama]' AND id_jadwal='$_GET[idj]' AND nama_dokumen='FR.AK.07.CEKLIS PENYESUAIAN YANG WAJAR DAN BERALASAN' ORDER BY `waktu` DESC";
$cekttdasesiak07as = $conn->query($sqlcekttdasesiak07as);
$jumttdasesias = $cekttdasesiak07as->num_rows;
$ttdasas = $cekttdasesiak07as->fetch_assoc();
// var_dump($ttdasas['file']);


if ($jumttdasesias > 0) {
  $write->easyCell('', 'img:../' . $ttdasas['file'] . ', h20; align:C; valign:T; font-size:10; font-style:B; border:R;');
} else {
  $write->easyCell('', 'align:L; valign:T; font-size:10; font-style:B; border:R;');
}
$write->printRow();
$tglttdnya = tgl_indo($ttdasas['waktu']);
$write->easyCell('', 'align:L; font-size:10; border:LBR;');
$write->easyCell($tglttdnya, 'align:C; font-size:10; border:LBR;');
$write->easyCell('', 'align:L; font-size:10; border:LBR;');
$write->printRow();
$write->endTable(5);
//memanggil library QR Code
require_once("../phpqrcode/qrlib.php");
//$qrcodetext="http://".$iden['url_domain']."/signed.php?id=$ttdas[id]";
$qrcodetext2 = "http://" . $iden['url_domain'] . "/signed.php?id=$ttdasas[id]";
//create a QR Code and save it as a png image file named generateqr.png
//QRcode::png($qrcodetext,"../foto_tandatangan/generateqr.png");
QRcode::png($qrcodetext2, "../foto_tandatangan/generateqr2.png");
//this is the second method
$write = new easyTable($pdf, '{95,95}', 'width:190; align:L; font-family:arial; font-size:12');
//if (!empty($ttdas['id'])){
//	$write->easyCell('', 'img:../foto_tandatangan/generateqr.png, h20; align:L; valign:T; font-size:10; font-style:B;');
//}else{
$write->easyCell('', 'align:L; valign:T; font-size:10; font-style:B;');
//}
if (!empty($ttdasas['id'])) {
  $write->easyCell('', 'img:../foto_tandatangan/generateqr2.png, h20; align:R; valign:T; font-size:10; font-style:B;');
} else {
  $write->easyCell('', 'align:R; valign:T; font-size:10; font-style:B;');
}
$write->printRow();
$write->endTable(5);
$pdf->AliasNbPages();
//output file pdf
$fileoutputnya = "FR-AK-07-" . $skemakkni . "-" . $_GET['idj'] . ".pdf";
$pdf->Output($fileoutputnya, 'I');
