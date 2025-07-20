<?php
 
ini_set('display_errors',0);
require_once "koneksi.php";
 
$kota = $_GET['kota'];
$sqlkec = "SELECT * FROM data_wilayah WHERE id_induk_wilayah='$kota'";
$kecamatan = $conn->query($sqlkec);
echo "<option>-- Pilih Kecamatan --</option>";
while($kc = $kecamatan->fetch_assoc()){
echo "<option value='$kc[id_wil]'>$kc[nm_wil]</option>";
}

?>