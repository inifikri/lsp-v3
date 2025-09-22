<?php
 
ini_set('display_errors',0);
require_once "koneksi.php";
 
$skkni = $_GET['skkni'];
$sqlget = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$skkni'";
$getdata = $conn->query($sqlget);
$no=1;
echo "<b>Unit Kompetensi :</b><br>";
while($k=$getdata->fetch_assoc()){
	echo "$no. $k[judul]<br>";
	$no++;
}

?>