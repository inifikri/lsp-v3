<?php
 
ini_set('display_errors',0);
require_once "koneksi.php";
 
$kategori = $_GET['jenisrpl'];
$getkat = mysql_query("SELECT * FROM `data_porfoliorpl` WHERE `id_induk_porfolio`='$kategori'");

$row_cntcr = mysql_num_rows($getkat);
if ($row_cntcr>0){
	while($k = mysql_fetch_array($getkat)){
	echo "<option value='$k[id]'>$k[nm_porfolio]</option>";
	}
}else{
	echo "<option value='0'>-- Pilih Jenis Pengalaman --</option>";
}

?>