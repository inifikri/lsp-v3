<?php
//error_reporting(E_ALL);
session_start();
include "../config/koneksi.php";
$id_skemakkni=$_POST['id_skema'];
$modkon1a=$_POST['31a'];
$modkon1a_ket=$_POST['31a_ket'];
$modkon1b=$_POST['31b'];
$modkon1b_ket=$_POST['31b_ket'];
$modkon2=$_POST['32'];
$modkon2_ket=$_POST['32_ket'];
$modkon3=$_POST['33'];
$modkon3_ket=$_POST['33_ket'];
$modkon4=$_POST['34'];
$modkon4_ket=$_POST['34_ket'];
if (isset($_POST['konf1'])){
	$konfirm1=$_POST['konf1'];
}else{
	$konfirm1="";
}
$konfirm1_ket=$_POST['nama_konf1'];
if (isset($_POST['konf2'])){
	$konfirm2=$_POST['konf2'];
}else{
	$konfirm2="";
}
$konfirm2_ket=$_POST['nama_konf2'];
if (isset($_POST['konf3'])){
	$konfirm3=$_POST['konf3'];
}else{
	$konfirm3="";
}
$konfirm3_ket=$_POST['nama_konf3'];
if (isset($_POST['konf4'])){
	$konfirm4=$_POST['konf4'];
}else{
	$konfirm4="";
}
$konfirm4_ket=$_POST['nama_konf4'];
					$sqlgetmapa12="SELECT * FROM `skema_mapa1a` WHERE `id_skemakkni`='$_POST[id_skema]' AND `profil_kandidat`='$profilkandidat' ORDER BY `id` ASC";
					$getmapa12=$conn->query($sqlgetmapa12);
					$gtmapa12=$getmapa12->fetch_assoc();
					$jumgtmapa12=$getmapa12->num_rows;

					if ($jumgtmapa12==0){
						$sqlskemamapa1="INSERT INTO `skema_mapa1a`(`id_skemakkni`, `profil_kandidat`, `modkon1a`, `modkon1a_ket`, `modkon1b`, `modkon1b_ket`, `modkon2`, `modkon2_ket`, `modkon3`, `modkon3_ket`, `modkon4`, `modkon4_ket`, `konfirm1`, `konfirm1_ket`, `konfirm2`, `konfirm2_ket`, `konfirm3`, `konfirm3_ket`, `konfirm4`, `konfirm4_ket`) VALUES ('$id_skemakkni', '$profilkandidat', '$modkon1a', '$modkon1a_ket', '$modkon1b', '$modkon1b_ket', '$modkon2', '$modkon2_ket', '$modkon3', '$modkon3_ket', '$modkon4', '$modkon4_ket', '$konfirm1', '$konfirm1_ket', '$konfirm2', '$konfirm2_ket', '$konfirm3', '$konfirm3_ket', '$konfirm4', '$konfirm4_ket')";
						$conn->query($sqlskemamapa1);

					}else{
						$sqlskemamapa1="UPDATE `skema_mapa1a` SET
							`modkon1a`='$modkon1a', 
							`modkon1a_ket`='$modkon1a_ket', 
							`modkon1b`='$modkon1b', 
							`modkon1b_ket`='$modkon1b_ket', 
							`modkon2`='$modkon2', 
							`modkon2_ket`='$modkon2_ket', 
							`modkon3`='$modkon3', 
							`modkon3_ket`='$modkon3_ket', 
							`modkon4`='$modkon4', 
							`modkon4_ket`='$modkon4_ket', 
							`konfirm1`='$konfirm1', 
							`konfirm1_ket`='$konfirm1_ket', 
							`konfirm2`='$konfirm2', 
							`konfirm2_ket`='$konfirm2_ket', 
							`konfirm3`='$konfirm3', 
							`konfirm3_ket`='$konfirm3_ket', 
							`konfirm4`='$konfirm4', 
							`konfirm4_ket`='$konfirm4_ket' 
							WHERE `id_skemakkni`='$id_skemakkni' AND `profil_kandidat`='$profilkandidat'";
						$conn->query($sqlskemamapa1);
					}
  		echo "<script>alert('Bagian 3 MAPA-01 Telah Tersimpan/Terupdate'); window.location = 'media.php?module=skema'</script>";

?>