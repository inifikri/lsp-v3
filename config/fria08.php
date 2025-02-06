<?php
require_once "koneksi.php";

$wiltuk = $_GET['elemen_ia08'];
$cekel=substr($wiltuk,0,2);
$siap=explode("-",$wiltuk);
if ($cekel=="e-"){
	$sqlgetunitnya="SELECT * FROM `elemen_kompetensi` WHERE `id`='$siap[1]'";
	$getunitnya=$conn->query($sqlgetunitnya);
	$gunt=$getunitnya->fetch_assoc();
	$sqlgetpertanyaan08="SELECT * FROM `skema_pertanyaanwawancara` WHERE `id_unitkompetensi`='$gunt[id_unitkompetensi]'";
	$getpertanyaan08=$conn->query($sqlgetpertanyaan08);
	$jumpert=$getpertanyaan08->num_rows;
	if ($jumpert>0){
		echo "<p id='pertanyaan08'><ol>";
		while ($prtny=$getpertanyaan08->fetch_assoc()){
			echo "<li>$prtny[pertanyaan]</li>";
		}
		echo "</ol></p>";
	}else{
		echo "<p id='pertanyaan08'><font color='red'>Pertanyaan belum dibuat</font></p>";
	}
}else{
	$sqlgetpertanyaan08="SELECT * FROM `skema_pertanyaanwawancara` WHERE `id_unitkompetensi`='$wiltuk'";
	$getpertanyaan08=$conn->query($sqlgetpertanyaan08);
	echo "<p id='pertanyaan08'><ol>";
	$jumpert=$getpertanyaan08->num_rows;
	if ($jumpert>0){
		echo "<p id='pertanyaan08'><ol>";
		while ($prtny=$getpertanyaan08->fetch_assoc()){
			echo "<li>$prtny[pertanyaan]</li>";
		}
		echo "</ol></p>";
	}else{
		echo "<p id='pertanyaan08'><font color='red'>Pertanyaan belum dibuat</font></p>";
	}
}
?>