<?php
//error_reporting(E_ALL);
session_start();
include "../config/koneksi.php";
					$id_skemakkni=$_POST['id_skema'];
					$profilkandidat=$_POST['profil_kandidat'];
					$pendekatan=$_POST['kandidat'];
					$pendekatan_ket=$_POST['kandidatket1'];
					$tujuan=$_POST['tujuanasesmen'];
					$tujuanket=$_POST['tujuanket'];
					$konteks_a=$_POST['lingkungan'];
					$konteks_b=$_POST['peluang'];
					if (isset($_POST['hubungan1'])){
						$konteks_c1=$_POST['hubungan1'];
					}else{
						$konteks_c1="";
					}
					if (isset($_POST['hubungan2'])){
						$konteks_c2=$_POST['hubungan2'];
					}else{
						$konteks_c2="";
					}
					if (isset($_POST['hubungan3'])){
						$konteks_c3=$_POST['hubungan3'];
					}else{
						$konteks_c3="";
					}
					if (isset($_POST['hubungan1-1'])){
						$konteks_c11=$_POST['hubungan1-1'];
					}else{
						$konteks_c11="";
					}
					if (isset($_POST['hubungan2-1'])){
						$konteks_c21=$_POST['hubungan2-1'];
					}else{
						$konteks_c21="";
					}
					if (isset($_POST['hubungan3-1'])){
						$konteks_c31=$_POST['hubungan3-1'];
					}else{
						$konteks_c31="";
					}
					$konteks_d=$_POST['siapa'];
					if (isset($_POST['konfirmasi1'])){
						$konfirmasi1=$_POST['konfirmasi1'];
					}else{
						$konfirmasi1="";
					}
					if (isset($_POST['konfirmasi2'])){
						$konfirmasi2=$_POST['konfirmasi2'];
					}else{
						$konfirmasi2="";
					}
					if (isset($_POST['konfirmasi3'])){
						$konfirmasi3=$_POST['konfirmasi3'];
					}else{
						$konfirmasi3="";
					}
					if (isset($_POST['konfirmasi4'])){
						$konfirmasi4=$_POST['konfirmasi4'];
					}else{
						$konfirmasi4="";
					}
					$konfirmasi4_ket=$_POST['konfirmasiket'];
					if (isset($_POST['tolokukur1'])){
						$toluk1=$_POST['tolokukur1'];
					}else{
						$toluk1="";
					}
					$toluk1_ket=$_POST['tolokukur1ket'];
					if (isset($_POST['tolokukur2'])){
						$toluk2=$_POST['tolokukur2'];
					}else{
						$toluk2="";
					}
					$toluk2_ket=$_POST['tolokukur2ket'];
					if (isset($_POST['tolokukur3'])){
						$toluk3=$_POST['tolokukur3'];
					}else{
						$toluk3="";
					}
					$toluk3_ket=$_POST['tolokukur3ket'];
					if (isset($_POST['tolokukur4'])){
						$toluk4=$_POST['tolokukur4'];
					}else{
						$toluk4="";
					}
					$toluk4_ket=$_POST['tolokukur4ket'];
					if (isset($_POST['tolokukur5'])){
						$toluk5=$_POST['tolokukur5'];
					}else{
						$toluk5="";
					}
					$toluk5_ket=$_POST['tolokukur5ket'];

					$sqlgetmapa12="SELECT * FROM `skema_mapa1a` WHERE `id_skemakkni`='$_POST[id_skema]' AND `profil_kandidat`='$profilkandidat' ORDER BY `id` ASC";
					$getmapa12=$conn->query($sqlgetmapa12);
					$gtmapa12=$getmapa12->fetch_assoc();
					$jumgtmapa12=$getmapa12->num_rows;


					if ($jumgtmapa12==0){
						$sqlskemamapa1="INSERT INTO `skema_mapa1a`(`id_skemakkni`, `profil_kandidat`, `pendekatan`, `pendekatan_ket`, `tujuan`, `tujuanket`, `konteks_a`, `konteks_b`, `konteks_c1`, `konteks_c1-1`, `konteks_c2`, `konteks_c2-1`, `konteks_c3`, `konteks_c3-1`, `konteks_d`, `konfirmasi1`, `konfirmasi2`, `konfirmasi3`, `konfirmasi4`, `konfirmasi4_ket`, `toluk1`, `toluk1_ket`, `toluk2`, `toluk2_ket`, `toluk3`, `toluk3_ket`, `toluk4`, `toluk4_ket`, `toluk5`, `toluk5_ket`) VALUES ('$id_skemakkni', '$profilkandidat', '$pendekatan', '$pendekatan_ket', '$tujuan', '$tujuanket', '$konteks_a', '$konteks_b', '$konteks_c1', '$konteks_c11', '$konteks_c2', '$konteks_c21', '$konteks_c3', '$konteks_c31', '$konteks_d', '$konfirmasi1', '$konfirmasi2', '$konfirmasi3', '$konfirmasi4', '$konfirmasi4_ket', '$toluk1', '$toluk1_ket', '$toluk2', '$toluk2_ket', '$toluk3', '$toluk3_ket', '$toluk4', '$toluk4_ket', '$toluk5', '$toluk5_ket')";
						$conn->query($sqlskemamapa1);

					}else{
						$sqlskemamapa1="UPDATE `skema_mapa1a` SET
							`id_skemakkni`='$id_skemakkni', 
							`pendekatan`='$pendekatan', 
							`pendekatan_ket`='$pendekatan_ket', 
							`tujuan`='$tujuan', 
							`tujuanket`='$tujuanket', 
							`konteks_a`='$konteks_a', 
							`konteks_b`='$konteks_b', 
							`konteks_c1`='$konteks_c1', 
							`konteks_c1-1`='$konteks_c11', 
							`konteks_c2`='$konteks_c2', 
							`konteks_c2-1`='$konteks_c21', 
							`konteks_c3`='$konteks_c3', 
							`konteks_c3-1`='$konteks_c31', 
							`konteks_d`='$konteks_d', 
							`konfirmasi1`='$konfirmasi1', 
							`konfirmasi2`='$konfirmasi2', 
							`konfirmasi3`='$konfirmasi3', 
							`konfirmasi4`='$konfirmasi4', 
							`konfirmasi4_ket`='$konfirmasi4_ket', 
							`toluk1`='$toluk1', 
							`toluk1_ket`='$toluk1_ket', 
							`toluk2`='$toluk2', 
							`toluk2_ket`='$toluk2_ket', 
							`toluk3`='$toluk3', 
							`toluk3_ket`='$toluk3_ket', 
							`toluk4`='$toluk4', 
							`toluk4_ket`='$toluk4_ket', 
							`toluk5`='$toluk5', 
							`toluk5_ket`='$toluk5_ket' 
							WHERE `id_skemakkni`='$id_skemakkni' AND `profil_kandidat`='$profilkandidat'";
						$conn->query($sqlskemamapa1);

					}
		
  		echo "<script>alert('Bagian 1 MAPA-01 Telah Tersimpan/Terupdate'); window.location = 'media.php?module=mapa1b&kand=$profilkandidat&idsk=$id_skemakkni'</script>";

?>