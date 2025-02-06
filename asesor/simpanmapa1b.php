<?php
//error_reporting(E_ALL);
session_start();
include "../config/koneksi.php";
		/* $sqlgetunit2="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$_POST[id_skema]' ORDER BY `id` ASC";
		$getunit2=$conn->query($sqlgetunit2);
		$noun2=1;
		$id_skemakkni=$_POST['id_skema'];

		while ($gtu2=$getunit2->fetch_assoc()){
			$sqlgetkatmuk2="SELECT * FROM `elemen_kompetensi` WHERE `id_unitkompetensi`='$gtu2[id]' ORDER BY `id` ASC";
			$getkatmuk2=$conn->query($sqlgetkatmuk2);
			$noel2=1;
			while ($gmuk2=$getkatmuk2->fetch_assoc()){
				$sqlgetkuk2="SELECT * FROM `kriteria_unjukkerja` WHERE `id_elemenkompetensi`='$gmuk2[id]' ORDER BY `id` ASC";
				$getkuk2=$conn->query($sqlgetkuk2);
				$nokuk2=1;
				while ($gkuk2=$getkuk2->fetch_assoc()){
					$sqlgetmapa12="SELECT * FROM `skema_mapa1b` WHERE `id_skemakkni`='$_POST[id_skema]' AND `id_unitkompetensi`='$gtu2[id]' AND `id_elemenkompetensi`='$gmuk2[id]' AND `id_kriteria`='$gkuk2[id]' ORDER BY `id` ASC";
					$getmapa12=$conn->query($sqlgetmapa12);
					$gtmapa12=$getmapa12->fetch_assoc();
					$jumgtmapa12=$getmapa12->num_rows;

					$id_unitkompetensi=$gtu2['id'];
					$id_elemenkompetensi=$gmuk2['id'];
					$id_kriteria=$gkuk2['id'];
					$varketbukti="bukti_".$noun2.$noel2.$nokuk2;
					$ket_bukti=$_POST[$varketbukti];
					$varbuktiL="L_".$noun2.$noel2.$nokuk2;
					$bukti_L=$_POST[$varbuktiL];
					$varbuktiTL="TL_".$noun2.$noel2.$nokuk2;
					$bukti_TL=$_POST[$varbuktiTL];
					$varbuktiT="T_".$noun2.$noel2.$nokuk2;
					$bukti_T=$_POST[$varbuktiT];
					$varmetode1="met1_".$noun2.$noel2.$nokuk2;
					$metode1=$_POST[$varmetode1];
					$varmetode2="met2_".$noun2.$noel2.$nokuk2;
					$metode2=$_POST[$varmetode2];
					$varmetode3="met3_".$noun2.$noel2.$nokuk2;
					$metode3=$_POST[$varmetode3];
					$varmetode4="met4_".$noun2.$noel2.$nokuk2;
					$metode4=$_POST[$varmetode4];
					$varmetode5="met5_".$noun2.$noel2.$nokuk2;
					$metode5=$_POST[$varmetode5];
					$varmetode6="met6_".$noun2.$noel2.$nokuk2;
					$metode6=$_POST[$varmetode6];

					if ($jumgtmapa12==0){
						$sqlskemamapa1="INSERT INTO `skema_mapa1b`(`id_skemakkni`, `id_unitkompetensi`, `id_elemenkompetensi`, `id_kriteria`, `ket_bukti`, `bukti_L`, `bukti_TL`, `bukti_T`, `metode1`, `metode2`, `metode3`, `metode4`, `metode5`, `metode6`) VALUES ('$id_skemakkni', '$id_unitkompetensi', '$id_elemenkompetensi', '$id_kriteria', '$ket_bukti', '$bukti_L', '$bukti_TL', '$bukti_T', '$metode1', '$metode2', '$metode3', '$metode4', '$metode5', '$metode6')";
						$conn->query($sqlskemamapa1);
					}else{
						$sqlskemamapa1="UPDATE `skema_mapa1b` SET
							`id_skemakkni`='$id_skemakkni', 
							`id_unitkompetensi`='$id_unitkompetensi', 
							`id_elemenkompetensi`='$id_elemenkompetensi', 
							`id_kriteria`='$id_kriteria', 
							`ket_bukti`='$ket_bukti', 
							`bukti_L`='$bukti_L', 
							`bukti_TL`='$bukti_TL', 
							`bukti_T`='$bukti_T', 
							`metode1`='$metode1', 
							`metode2`='$metode2', 
							`metode3`='$metode3', 
							`metode4`='$metode4', 
							`metode5`='$metode5', 
							`metode6`='$metode6' 
							WHERE `id`='$gtmapa12[id]'";
						$conn->query($sqlskemamapa1);
					}
					$nokuk2++;
				}
				$noel2++;
			}
			$noun2++;
		} */
  		echo "<script>alert('Bagian 2 MAPA-01 Telah Tersimpan/Terupdate'); window.location = 'media.php?module=mapa1c&idsk=$_POST[id_skema]'</script>";

?>