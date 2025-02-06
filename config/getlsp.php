<?php
 
ini_set('display_errors',0);
require_once "koneksi.php";
 
$lsp = $_GET['lsp'];
$sqlget = "SELECT * FROM `skkni`";
$getdata = $conn->query($sqlget);
echo "<option value=''>-- Pilih SKKNI --</option>";
while($k=$getdata->fetch_assoc()){
echo "<option value='$k[id]'>$k[nama]</option>";
}

?>