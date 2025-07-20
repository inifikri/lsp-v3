<?php
require('fpdf-easytable-master/fpdf.php');
require('fpdf-easytable-master/exfpdf.php');
require('fpdf-easytable-master/easyTable.php');

$pdf = new exFPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',10);
$pdf->AddFont('FontUTF8','','Arimo-Regular.php'); 
$pdf->AddFont('FontUTF8','B','Arimo-Bold.php');

// === HEADER LSP ===
$write = new easyTable($pdf, '{30, 130, 30}', 'width:190; align:L; font-style:B; font-family:arial;');
$write->easyCell('', 'img:../images/logolsp.jpg, w25, h14; align:C; rowspan:3');
$write->easyCell('LEMBAGA SERTIFIKASI PROFESI (LSP) CONTOH', 'align:C; font-size:14;');
$write->easyCell('', 'img:../images/logo-bnsp.jpg, w25, h14;align:C; rowspan:3');
$write->printRow();
$write->easyCell('Nomor Lisensi : 1234/LSP/BNSP/2024', 'align:C; font-size:10;');
$write->printRow();
$write->easyCell('Jl. Contoh Alamat No.1, Kota Contoh, Indonesia. Telp. 012-3456789 Email: info@example.com', 'align:C; font-size:8;');
$write->printRow();
$write->endTable(5);

// === JUDUL FORM ===
$write = new easyTable($pdf, 1, 'width:180; align:C; font-style:B; font-size:12; font-family:arial;');
$write->easyCell('FR.AK.07. CEKLIS PENYESUAIAN YANG WAJAR DAN BERALASAN', 'align:L;');
$write->printRow();
$write->endTable(3);

// === INFORMASI ASESI ===
$write = new easyTable($pdf, '{50, 5, 125}', 'width:180; align:L; font-size:10; font-family:arial;');
$data_asesi = [
  'Skema Sertifikasi' => 'KKNI/Okupasi/Klaster',
  'Judul' => 'MANAJER PENGEMBANGAN PRODUK BARU',
  'Nomor' => '001/SKEMA-LSP-PPM/I/2017',
  'TUK' => 'Sewaktu / Tempat Kerja / Mandiri',
  'Nama Asesi' => 'Ahmad Surya Putra',
  'Nama Asesor' => 'Budi Santosa, S.Kom',
  'Tanggal' => '19 Juli 2025'
];
$keys = array_keys($data_asesi);
foreach ($keys as $i => $label) {
    $border = 'LR';
    if ($i === 0) $border = 'LTR';
    if ($i === count($keys) - 1) $border = 'LRB';
    $write->easyCell($label, "border:$border");
    $write->easyCell(':', "border:$border");
    $write->easyCell($data_asesi[$label], "border:$border");
    $write->printRow();
}
$write->endTable(5);

// === POTENSI ASESI ===
$write = new easyTable($pdf, 1, 'width:180; align:L; font-style:B; font-size:11;');
$write->easyCell('Potensi Asesi', 'align:L;');
$write->printRow();
$write->endTable(1);

$write = new easyTable($pdf, '{5, 175}', 'width:180; font-size:10; align:L;');
$potensi = [
    'Hasil pelatihan berbasis standar kompetensi',
    'Hasil pelatihan non-kompetensi',
    'Pekerja dari industri sesuai standar kompetensi',
    'Pekerja industri non-standar',
    'Pelatihan / belajar mandiri'
];
foreach ($potensi as $p) {
    $write->easyCell('☐', '');
    $write->easyCell($p);
    $write->printRow();
}
$write->endTable(5);

// === TABEL PENYESUAIAN ===
$write = new easyTable($pdf, '{10, 90, 20, 20, 40}', 'width:180; align:C; font-size:9;');
$write->easyCell('No', 'border:1; align:C; bgcolor:#ccc');
$write->easyCell('Persyaratan', 'border:1; bgcolor:#ccc');
$write->easyCell('Ya', 'border:1; align:C; bgcolor:#ccc');
$write->easyCell('Tidak', 'border:1; align:C; bgcolor:#ccc');
$write->easyCell('Keterangan', 'border:1; bgcolor:#ccc');
$write->printRow();

$dummy_penyesuaian = [
    "Keterbatasan bahasa/literasi",
    "Butuh pendamping baca/tulis",
    "Gunakan teknologi adaptif",
    "Fleksibel karena keletihan",
    "Butuh braille/audio",
    "Penyesuaian tempat asesmen",
    "Pertimbangan usia/gender",
    "Pertimbangan budaya/agama"
];

for ($i=0; $i<count($dummy_penyesuaian); $i++) {
    $write->easyCell($i+1, 'border:1; align:C;');
    $write->easyCell($dummy_penyesuaian[$i], 'border:1;');
    $write->easyCell('☑', 'border:1; align:C;'); // Dummy: Ya
    $write->easyCell('', 'border:1;');
    $write->easyCell('-', 'border:1;');
    $write->printRow();
}
$write->endTable(5);

// === HASIL PENYESUAIAN ===
$write = new easyTable($pdf, 1, 'width:180; font-size:10; align:L;');
$write->easyCell('Hasil Penyesuaian yang disepakati menggunakan:', 'align:L; font-style:B;');
$write->printRow();
$write->easyCell('1) Acuan Pembanding Asesmen: Standar Kompetensi SKKNI', '');
$write->printRow();
$write->easyCell('2) Metode Asesmen: Observasi, Tanya Jawab', '');
$write->printRow();
$write->easyCell('3) Instrumen Asesmen: FR.AK.02, FR.AK.03', '');
$write->printRow();
$write->endTable(5);

// === TANDA TANGAN DAN QR ===
$write = new easyTable($pdf, '{40, 50, 40, 50}', 'width:180; align:L; font-size:10;');
$write->easyCell('Tanda Tangan Asesi', '');
$write->easyCell(': Ahmad Surya Putra', '');
$write->easyCell('Tanggal', '');
$write->easyCell(': 19 Juli 2025', '');
$write->printRow();

$write->easyCell('Tanda Tangan Asesor', '');
$write->easyCell(': Budi Santosa, S.Kom', '');
$write->easyCell('Tanggal', '');
$write->easyCell(': 19 Juli 2025', '');
$write->printRow();
$write->endTable(3);

// === QR Code ===
$write = new easyTable($pdf, 1, 'width:180; align:C;');
$write->easyCell('', 'img:../foto_tandatangan/generateqr.png, h25; align:C;');
$write->printRow();
$write->endTable(0);

$pdf->Output('FR-AK-07-PENYESUAIAN.pdf','I');
?>
