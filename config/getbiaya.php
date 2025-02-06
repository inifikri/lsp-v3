<?php
 
ini_set('display_errors',0);
require_once "koneksi.php";
 
$skkni = $_GET['skemakkni'];
$sqlget1 = "SELECT * FROM `skema_kkni` WHERE `id`='$skkni'";
$getdata1 = $conn->query($sqlget1);
$k1=$getdata1->fetch_assoc();
$sqlget2 = "SELECT * FROM `skkni` WHERE `id`='$k1[id_skkni]'";
$getdata2 = $conn->query($sqlget2);
$k2=$getdata2->fetch_assoc();

$sqlget = "SELECT * FROM `biaya_jenis`";
$getdata = $conn->query($sqlget);
echo "<option value=''>-- Pilih Jenis Biaya --</option>";
while($k=$getdata->fetch_assoc()){
echo "<option value='$k[id]'>$k[jenis_biaya]</option>";
}

?>