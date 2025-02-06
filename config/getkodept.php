<?php
 
ini_set('display_errors',0);
require_once "koneksi.php";
 
$kategori = $_GET['kodept'];
$getkat = mysql_query("SELECT * FROM `kodeprodi` WHERE `kode_pt`='$kategori' AND `aktif`='Y'");
echo "<option value=''>-- Pilih Program Studi --</option>";
while($k = mysql_fetch_array($getkat)){
echo "<option value='$k[kode_prodi]'>$k[nama_jurusan]</option>";
}

?>