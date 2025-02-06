<?php
 
ini_set('display_errors',0);
require_once "koneksi.php";
 
$propinsi = $_GET['propinsi'];
$sqlkota="SELECT * FROM `data_wilayah` WHERE `id_induk_wilayah`='$propinsi'";
$kota = $conn->query($sqlkota);
echo "<option>-- Pilih Kabupaten/Kotanya --</option>";
while($k = $kota->fetch_assoc()){
echo "<option value='$k[id_wil]'>$k[nm_wil]</option>";
}

?>