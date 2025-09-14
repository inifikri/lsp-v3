<?php
// 1. Load TCPDF (sesuaikan path jika perlu)
require_once('tcpdf/tcpdf.php');

// 2. Inisialisasi dokumen
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetMargins(10, 15, 10);
$pdf->SetAutoPageBreak(true, 15);
$pdf->SetFont('helvetica', '', 10);

// -----------------------------------------------------------------------------
// Halaman 1
// -----------------------------------------------------------------------------
$pdf->AddPage();

// Logo LSP‑PPM (sesuaikan nama/file)
$pdf->Image('logo_lspppm.png', 15, 10, 30);

// Judul utama
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 8, 'FR.AK.07 – CEKLIST PENYESUAIAN YANG WAJAR DAN BERALASAN', 0, 1, 'C');
$pdf->Ln(4);
$pdf->SetFont('helvetica', '', 10);

// Tabel metadata
$tbl  = '<table cellspacing="0" cellpadding="4" border="1">';
$tbl .= '<tr>
           <td width="30%">Skema Sertifikasi<br>(KKNI/Okupasi/Klaster)</td>
           <td width="20%">Judul :</td>
           <td width="50%">MANAJER PENGEMBANGAN PRODUK BARU<br>(New Product Development Manager)</td>
         </tr>';
$tbl .= '<tr>
           <td></td>
           <td>Nomor :</td>
           <td>001/SKEMA‑LSP‑PPM/I/2017</td>
         </tr>';
$tbl .= '<tr>
           <td>TUK :</td>
           <td colspan="2">Sewaktu/Tempat Kerja/Mandiri*</td>
         </tr>';
$tbl .= '<tr>
           <td>Nama Asesor :</td>
           <td colspan="2"></td>
         </tr>';
$tbl .= '<tr>
           <td>Nama Asesi :</td>
           <td colspan="2"></td>
         </tr>';
$tbl .= '<tr>
           <td>Tanggal :</td>
           <td colspan="2"></td>
         </tr>';
$tbl .= '</table>';
$pdf->writeHTML($tbl, true, false, false, false, '');

// Panduan Asesor
$pdf->Ln(4);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(0, 6, 'PANDUAN BAGI ASESOR', 0, 1);
$pdf->SetFont('helvetica', '', 10);
$pdf->writeHTML('
<ul>
  <li>Form ini digunakan jika diperlukan penyesuaian wajar sebelum/saat/after pra‑asesmen.</li>
  <li>Coretlah tanda * yang tidak sesuai.</li>
  <li>Berilah tanda √ pada kotak untuk potensi asesi.</li>
  <li>Berilah tanda √ Ya/Tidak dan isi kolom keterangan jika Ya.</li>
</ul>
', true, false, false, false);

// Potensi Asesi
$pdf->Ln(2);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(0, 6, 'POTENSI ASESI', 0, 1);
$pdf->SetFont('helvetica', '', 10);

$tbl2  = '<table cellspacing="0" cellpadding="4" border="1">';
$potensi = [
  'Hasil pelatihan/pendidikan sesuai standar kompetensi.',
  'Hasil pelatihan/pendidikan belum berbasis kompetensi.',
  'Pekerja berpengalaman, operasional sesuai standar.',
  'Pekerja berpengalaman, operasional belum berbasis kompetensi.',
  'Belajar mandiri atau otodidak.'
];
foreach ($potensi as $item) {
    $tbl2 .= '<tr>
               <td width="5%">&#9633;</td>
               <td width="95%">'.$item.'</td>
              </tr>';
}
$tbl2 .= '</table>';
$pdf->writeHTML($tbl2, true, false, false, false, '');

// -----------------------------------------------------------------------------
// Halaman 2
// -----------------------------------------------------------------------------
$pdf->AddPage();

// Tabel checklist modifikasi (1–6)
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(0, 6, 'MODIFIKASI & KONTEKSTUALISASI (1–6)', 0, 1);
$pdf->SetFont('helvetica', '', 10);

$headers = '<tr>
             <th width="5%">No</th>
             <th width="40%">Karakteristik Asesi</th>
             <th width="10%">Ya</th>
             <th width="10%">Tidak</th>
             <th width="35%">Keterangan</th>
           </tr>';
$rows = '';
$descs = [
  1 => 'Keterbatasan bahasa, literasi, numerasi.',
  2 => 'Penyediaan dukungan pembaca/penerjemah.',
  3 => 'Penggunaan teknologi adaptif/peralatan khusus.',
  4 => 'Fleksibilitas pelaksanaan asesmen (keletihan/medis).',
  5 => 'Peralatan khusus (Braille, audio/video tape).',
  6 => 'Penyesuaian tempat fisik/lingkungan.'
];
for ($i = 1; $i <= 6; $i++) {
    $rows .= '<tr>
                <td align="center">'.$i.'</td>
                <td>'.$descs[$i].'</td>
                <td align="center">&#9633;</td>
                <td align="center">&#9633;</td>
                <td>&nbsp;</td>
              </tr>';
}
$tbl3 = '<table cellspacing="0" cellpadding="4" border="1">'.$headers.$rows.'</table>';
$pdf->writeHTML($tbl3, true, false, false, false, '');

// -----------------------------------------------------------------------------
// Halaman 3
// -----------------------------------------------------------------------------
$pdf->AddPage();

// Tabel checklist modifikasi (7–8)
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(0, 6, 'MODIFIKASI & KONTEKSTUALISASI (7–8)', 0, 1);
$pdf->SetFont('helvetica', '', 10);

$headers3 = '<tr>
               <th width="5%">No</th>
               <th width="40%">Karakteristik Asesi</th>
               <th width="10%">Ya</th>
               <th width="10%">Tidak</th>
               <th width="35%">Keterangan</th>
             </tr>';
$rows3 = '';
$descs3 = [
  7 => 'Pertimbangan umur/usia lanjut/gender.',
  8 => 'Pertimbangan budaya/tradisi/agama.'
];
foreach ($descs3 as $i => $d) {
    $rows3 .= '<tr>
                 <td align="center">'.$i.'</td>
                 <td>'.$d.'</td>
                 <td align="center">&#9633;</td>
                 <td align="center">&#9633;</td>
                 <td>&nbsp;</td>
               </tr>';
}
$tbl4 = '<table cellspacing="0" cellpadding="4" border="1">'.$headers3.$rows3.'</table>';
$pdf->writeHTML($tbl4, true, false, false, false, '');

// Hasil kesepakatan
$pdf->Ln(4);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(0, 6, 'HASIL PENYESUAIAN DISEPAKATI MENGGUNAKAN:', 0, 1);
$pdf->SetFont('helvetica', '', 10);
$pdf->writeHTML('
<ol>
  <li>Acuan Pembanding Asesmen</li>
  <li>Metode Asesmen</li>
  <li>Instrumen Asesmen</li>
</ol>', true, false, false, false, '');

// Tanda tangan
$pdf->Ln(4);
$sign = '
<table cellspacing="0" cellpadding="4" border="0">
  <tr>
    <td width="50%">Nama Asesor :</td>
    <td width="50%">Tanggal & TTD Asesor :</td>
  </tr>
  <tr>
    <td height="20"></td>
    <td></td>
  </tr>
  <tr>
    <td>Nama Asesi :</td>
    <td>Tanggal & TTD Asesi :</td>
  </tr>
  <tr>
    <td height="20"></td>
    <td></td>
  </tr>
</table>';
$pdf->writeHTML($sign, true, false, false, false, '');

// 4. Output ke browser
$pdf->Output('FR_AK_07_Replika.pdf', 'I');
