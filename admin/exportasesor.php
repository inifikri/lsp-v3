<?php

session_start();

include "../config/koneksi.php";
include "../config/fungsi_indotgl.php";
 
$title = "DATA ASESOR LSP";
$content_header = "<table><tr><th>No.</th><th>No. register</th><th>Nama Asesor</th><th>No. HP</th><th>Email</th><th>Tempat Lahir</th><th>Tanggal Lahir</th><th>Alamat</th><th>Kelurahan</th><th>Kecamatan</th><th>Kota</th><th>Kode Pos</th><th>No. Sertifikat</th><th>Masa Berlaku</th></tr>";
$content_footer = "</table>";
$content_dalam = "";
 
 
$sql = "SELECT * FROM `asesor` ORDER BY `nama` ASC";
$q   = $conn->query($sql);
$no=1;
while($r=$q->fetch_assoc()){
$sql2 = "SELECT * FROM `data_wilayah` WHERE `id_wil`='$r[kecamatan]'";
$q2   = $conn->query($sql2);
$kec=$q2->fetch_assoc();
$sql3 = "SELECT * FROM `data_wilayah` WHERE `id_wil`='$r[kota]'";
$q3   = $conn->query($sql3);
$kota=$q3->fetch_assoc();

$data = "<tr><td>".$no."</td><td>".$r['no_induk']."</td><td>".$r['nama']."</td><td>".$r['no_hp']."&nbsp;</td><td>".$r['email']."</td><td>".$r['tmp_lahir']."</td><td>".tgl_indo($r['tgl_lahir'])."</td><td>".$r['alamat']."</td><td>".$r['kelurahan']."</td><td>".trim($kec['nm_wil'])."</td><td>".trim($kota['nm_wil'])."</td><td>".$r['kodepos']."</td><td>".$r['no_lisensi']."</td><td>".tgl_indo($r['masaberlaku_lisensi'])."</td></tr>";
$content_dalam = $content_dalam ."\n". $data;
$no++;
}
 
$content_sheet = $title . "\n" . $content_header . "\n" . $content_dalam . "\n" . $content_footer;
$tglnya=date("d-m-Y"); 
 
 
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=data-asesor-sd-tanggal-$tglnya.xls");
header("Pragma: no-cache");
header("Expires: 0");
print $content_sheet;
?>