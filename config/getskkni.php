<?php
 
ini_set('display_errors',0);
require_once "koneksi.php";
 
$skkni = $_GET['skkni'];
$sqlget = "SELECT * FROM `skema_kkni` WHERE `aktif`='Y'";
$getdata = $conn->query($sqlget);
echo "<option value=''>-- Pilih Skema KKNI --</option>";
while($k=$getdata->fetch_assoc()){
echo "<option value='$k[id]'>$k[judul]</option>";
}

?>