<?php
 
ini_set('display_errors',0);
require_once "koneksi.php";
 
$jalurbayar = $_GET['pembayaran'];
$sqljalur="SELECT * FROM `rekeningbayar_jalur` WHERE `metode`='$jalurbayar'";
$jalur=$conn->query($sqljalur);
echo "<option>-- Pilih Jalur Pembayaran --</option>";
while($k = $jalur->fetch_assoc()){
echo "<option value='$k[jalur]'>$k[jalur]</option>";
}

?>