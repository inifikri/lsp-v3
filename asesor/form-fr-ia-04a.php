<?php
ob_start();
require_once('tcpdf/tcpdf.php');
include "../config/koneksi.php";
include "../config/library.php";
include "../config/fungsi_indotgl.php";

ini_set('display_errors', 0);
error_reporting(E_ALL);


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


$pdf = new TCPDF();
// $pdf->SetAutoPageBreak(TRUE, 10);
$pdf->AddPage();
$pdf->SetFont('Helvetica', '', 10);
// $pdf->setPrintHeader(true);


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



$pdf->Ln();

$html = '
<table border="0" cellpadding="2" cellspacing="0" style="width:190mm; font-family: Arial;">
    <tr>
        <td rowspan="3" style="width:30mm; text-align:center;">
            <img src="../images/logolsp.jpg" width="25" height="25">
        </td>
        <td style="width:130mm; text-align:center; font-size:14px; font-weight:bold;">' . $namalsp . '</td>
        <td rowspan="3" style="width:30mm; text-align:center;">
            <img src="../images/logo-bnsp.jpg" width="25" height="25">
        </td>
    </tr>
    <tr>
        <td style="text-align:center; font-size:10px;">' . $nomorlisensi . '</td>
    </tr>
    <tr>
        <td style="text-align:center; font-size:8px;">' . $alamatlsptampil . '</td>
    </tr>
</table>
';
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Ln(5);


$html = '
<table border="0" cellpadding="3" cellspacing="0" style="width:190mm; font-family: Arial; font-size:12px; font-weight:bold;">
    <tr>
        <td style="text-align:left;">FR.IA.04A. DIT - DAFTAR INSTRUKSI TERSTRUKTUR (PENJELASAN PROYEK SINGKAT/ KEGIATAN TERSTRUKTUR LAINNYA*)</td>
    </tr>
</table>
';
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Ln(5);


$sqlgetjenistuk="SELECT * FROM `tuk_jenis` WHERE `id`='$tq[jenis_tuk]'";
$getjenistuk=$conn->query($sqlgetjenistuk);
$jnstuk=$getjenistuk->fetch_assoc();

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


$html = '
<table border="1" cellpadding="3" cellspacing="0" style="width:190mm; font-family: Arial; font-size:12px;">
    <tr>
        <td rowspan="2" style="width:50mm; font-weight:bold;">Skema Sertifikasi (KKNI/Okupasi/Klaster)</td>
        <td style="width:20mm;">Judul</td>
        <td style="width:5mm;">:</td>
        <td style="width:115mm;">' . $skemakkni . '</td>
    </tr>
    <tr>
        <td>Nomor</td>
        <td>:</td>
        <td>' . $sq['kode_skema'] . '</td>
    </tr>
    <tr>
        <td colspan="2">TUK</td>
        <td>:</td>
        <td>' . $jnstuk['jenis_tuk'] . '</td>
    </tr>
    <tr>
        <td colspan="2">Nama Asesor</td>
        <td>:</td>
        <td>' . $namaasesor . '</td>
    </tr>
    <tr>
        <td colspan="2">Nama Asesi</td>
        <td>:</td>
        <td>' . $as['nama'] . '</td>
    </tr>
    <tr>
        <td colspan="2">Tanggal</td>
        <td>:</td>
        <td>' . $tgl_cetak . '</td>
    </tr>
</table>
';
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Ln(5);


$html = '
<table border="1" cellpadding="3" cellspacing="0" style="width:190mm; font-family: Arial; font-size:12px;">
    <tr style="background-color:#C6C6C6;">
        <td style="font-weight:bold;">PANDUAN BAGI ASESOR</td>
    </tr>
</table>
<table border="1" cellpadding="3" cellspacing="0" style="width:190mm; font-family: Arial; font-size:12px;">
    <tr>
        <td style="width:10mm; text-align:center;">•</td>
        <td style="width:180mm;">Tentukan proyek singkat atau kegiatan terstruktur lainnya yang harus dipersiapkan dan dipresentasikan oleh asesi.</td>
    </tr>
    <tr>
        <td style="text-align:center;">•</td>
        <td>Proyek singkat atau kegiatan terstruktur lainnya dibuat untuk keseluruhan unit kompetensi dalam Skema Sertifikasi atau untuk masing-masing kelompok pekerjaan.</td>
    </tr>
    <tr>
        <td style="text-align:center;">•</td>
        <td>Kumpulkan hasil proyek singkat atau kegiatan terstruktur lainnya sesuai dengan hasil keluaran yang telah ditetapkan.</td>
    </tr>
</table>
';
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Ln(5);

$contentasesmenIA04 = $conn->query("SELECT * FROM content_ia04A WHERE `id_skemakkni`='$jdq[id_skemakkni]'");

while ($cta = $contentasesmenIA04->fetch_assoc()) {
    $unit_kompetensi = explode(',', $cta['kode_unit']);
    $rowspan = 1 + count($unit_kompetensi);
    $kelompok = strip_tags($cta['kelompok']);
    $content = strip_tags($cta['content']);
    $content1 = strip_tags($cta['content1']);

   
    $html = '
    <table border="1" cellpadding="3" cellspacing="0" style="width:190mm; font-family: Arial; font-size:12px;">
        <tr>
            <td rowspan="' . $rowspan . '" style="width:40mm; text-align:center; font-weight:bold;">' . $kelompok . '</td>
            <td style="width:20mm; font-weight:bold;">No. </td>
            <td style="width:40mm; font-weight:bold;">Kode Unit</td>
            <td style="width:90mm; font-weight:bold;">Judul Unit</td>
        </tr>';

    $no = 1;
    for ($i = 0; $i < count($unit_kompetensi); ++$i) {
        $unit_kompetensi01 = $conn->query("SELECT * FROM unit_kompetensi WHERE kode_unit='$unit_kompetensi[$i]' AND `id_skemakkni`='$jdq[id_skemakkni]'");
        while ($uk01 = $unit_kompetensi01->fetch_assoc()) {
            $html .= '
            <tr>
                <td>' . $no++ . '</td>
                <td>' . $uk01['kode_unit'] . '</td>
                <td>' . $uk01['judul'] . '</td>
            </tr>';
        }
    }

    $html .= '</table>';
    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Ln(2);

  
    $html = '
    <table border="1" cellpadding="3" cellspacing="0" style="width:190mm; font-family: Arial; font-size:12px;">
        <tr>
            <td style="width:90mm;">' . $content . '</td>
            <td style="width:100mm;">' . $content1 . '</td>
        </tr>
        <tr>
            <td colspan="2" style="font-weight:bold;">Umpan Balik :</td>
        </tr>
    </table>';
    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Ln(5);
}


// Output file PDF
$fileoutputnya = "FR-IA-04A-" . $skemakkni . "-" . $idj . "-" . $ida . ".pdf";
$pdf->Output($fileoutputnya, 'I');

ob_end_flush();
?>
