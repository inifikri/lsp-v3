<?php
 
ini_set('display_errors',0);
require_once "koneksi.php";
 
$wiltuk = $_GET['tuk'];

$sqlgetidwiltuk="SELECT * FROM `tuk` WHERE `id`='$wiltuk'";
$getidwiltuk=$conn->query($sqlgetidwiltuk);
$wiltuk=$getidwiltuk->fetch_assoc();

$sqlkec="SELECT * FROM `data_wilayah` WHERE `id_wil`='$wiltuk[id_wilayah]'";
$kec = $conn->query($sqlkec);
$k = $kec->fetch_assoc();

$sqlkota="SELECT * FROM `data_wilayah` WHERE `id_wil`='$k[id_induk_wilayah]'";
$kota = $conn->query($sqlkota);
$k2 = $kota->fetch_assoc();

$sqlprop="SELECT * FROM `data_wilayah` WHERE `id_wil`='$k2[id_induk_wilayah]'";
$prop = $conn->query($sqlprop);
$k3 = $prop->fetch_assoc();

echo"<option>-- Pilih Wilayah Tugas --</option>";
echo"<option value='1'>Wilayah</option>";
echo"<option value='0'>Non Wilayah</option>";

?>