<?php
 
ini_set('display_errors',0);
require_once "koneksi.php";
 
$jalurbayar = $_GET['jalurpembayaran'];
$sqljalur="SELECT * FROM `rekeningbayar_jalur` WHERE `jalur`='$jalurbayar'";
$jalur=$conn->query($sqljalur);
$jlr=$jalur->fetch_assoc();
$sqljalur2="SELECT * FROM `rekeningbayar` WHERE `metode`='$jlr[metode]'";
$jalur2=$conn->query($sqljalur2);
echo "<option>-- Pilih Rekening Pembayaran --</option>";
while($k = $jalur2->fetch_assoc()){
echo "<option value='$k[id]'>$k[bank] $k[norek] $k[atasnama]</option>";
}

?>