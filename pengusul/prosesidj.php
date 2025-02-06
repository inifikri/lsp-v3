<?php
ini_set('display_errors',1); 
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['namauser'])){
header("Location:index.php");
}	

include "../config/koneksi.php";
include "../config/library.php";
include "../config/fungsi_indotgl.php";


$sqlasesi="SELECT * FROM `asesi_asesmen` ORDER BY `id` ASC";
$asesi=$conn->query($sqlasesi);
$no=1;
echo "<table>";
while ($as=$asesi->fetch_assoc()){
	$sqlinput="SELECT * FROM `jadwal_asesmen` WHERE `tgl_asesmen`='$as[tgl_asesmen]' AND `id_asesor`='$as[id_asesor]' AND `id_skemakkni`='$as[id_skemakkni]'";
	$input=$conn->query($sqlinput);
	$as2=$input->fetch_assoc();
	
	echo "<tr><td>$no $as[id_asesi]</td><td>$as2[id]</td><td>$as2[id_skemakkni] $as2[id_asesor] $as2[tgl_asesmen]</td></tr>";
	$sqlinputdata="UPDATE `asesi_asesmen` SET `id_jadwal`='$as2[id]' WHERE `id_skemakkni`='$as2[id_skemakkni]' AND `id_asesor`='$as2[id_asesor]' AND `tgl_asesmen`='$as2[tgl_asesmen]'";
	$conn->query($sqlinputdata);
	$no++;
}
echo "</table>";
?>