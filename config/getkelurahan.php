<?php
 
ini_set('display_errors',0);
require_once "koneksi.php";
 
$kecamatan = $_GET['kecamatan'];
$sqlkel = "SELECT * FROM `data_wilayah` WHERE `id_induk_wilayah`='$kecamatan'";
$kelurahan = $conn->query($sqlkel);
echo "<option>-- Pilih Kelurahan --</option>";
while($kl = $kelurahan->fetch_assoc()){
echo "<option value='$kl[id_wil]'>$kl[nm_wil]</option>";
}

?>