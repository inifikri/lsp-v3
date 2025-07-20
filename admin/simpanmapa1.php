<?php
//error_reporting(E_ALL);
session_start();
include "../config/koneksi.php";
		$sqlgetunit2="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$_POST[id_skema]' ORDER BY `id` ASC";
		//echo "Simpan $sqlgetunit2<br>";
		$getunit2=$conn->query($sqlgetunit2);
		$noun2=1;
//echo "konfirm1 $_POST[konf1]<br/>konfirm1_ket $_POST[nama_konf1]<br/>konfirm2 $_POST[konf2]<br/>konfirm2_ket $_POST[nama_konf2]<br/>konfirm3 $_POST[konf3]<br/>konfirm3_ket $_POST[nama_konf3]<br/>konfirm4 $_POST[konf4]<br/>konfirm4_ket $_POST[nama_konf4]<br/>";
					$id_skemakkni=$_POST['id_skema'];
					$pendekatan=$_POST['kandidat'];
					$pendekatan_ket=$_POST['kandidatket1'];
					$tujuan=$_POST['tujuanasesmen'];
					$tujuanket=$_POST['tujuanket'];
					$konteks_a=$_POST['lingkungan'];
					$konteks_b=$_POST['peluang'];
					$konteks_c1=$_POST['hubungan1'];
					$konteks_c2=$_POST['hubungan2'];
					$konteks_c3=$_POST['hubungan3'];
					$konteks_d=$_POST['siapa'];
					$konfirmasi1=$_POST['konfirmasi1'];
					$konfirmasi2=$_POST['konfirmasi2'];
					$konfirmasi3=$_POST['konfirmasi3'];
					$konfirmasi4=$_POST['konfirmasi4'];
					$konfirmasi4_ket=$_POST['konfirmasiket'];
					$toluk1=$_POST['tolokukur1'];
					$toluk1_ket=$_POST['tolokukur1ket'];
					$toluk2=$_POST['tolokukur2'];
					$toluk2_ket=$_POST['tolokukur2ket'];
					$toluk3=$_POST['tolokukur3'];
					$toluk3_ket=$_POST['tolokukur3ket'];
					$toluk4=$_POST['tolokukur4'];
					$toluk4_ket=$_POST['tolokukur4ket'];
					$toluk5=$_POST['tolokukur5'];
					$toluk5_ket=$_POST['tolokukur5ket'];
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
					$konfirm1=$_POST['konf1'];
					$konfirm1_ket=$_POST['nama_konf1'];
					$konfirm2=$_POST['konf2'];
					$konfirm2_ket=$_POST['nama_konf2'];
					$konfirm3=$_POST['konf3'];
					$konfirm3_ket=$_POST['nama_konf3'];
					$konfirm4=$_POST['konf4'];
					$konfirm4_ket=$_POST['nama_konf4'];

		while ($gtu2=$getunit2->fetch_assoc()){
			$sqlgetkatmuk2="SELECT * FROM `elemen_kompetensi` WHERE `id_unitkompetensi`='$gtu2[id]' ORDER BY `id` ASC";
			$getkatmuk2=$conn->query($sqlgetkatmuk2);
			$noel2=1;
			while ($gmuk2=$getkatmuk2->fetch_assoc()){
				$sqlgetkuk2="SELECT * FROM `kriteria_unjukkerja` WHERE `id_elemenkompetensi`='$gmuk2[id]' ORDER BY `id` ASC";
				$getkuk2=$conn->query($sqlgetkuk2);
				$nokuk2=1;
				while ($gkuk2=$getkuk2->fetch_assoc()){
					$sqlgetmapa12="SELECT * FROM `skema_mapa1` WHERE `id_skemakkni`='$_POST[id_skema]' AND `id_unitkompetensi`='$gtu2[id]' AND `id_elemenkompetensi`='$gmuk2[id]' AND `id_kriteria`='$gkuk2[id]' ORDER BY `id` ASC";
					$getmapa12=$conn->query($sqlgetmapa12);
					$gtmapa12=$getmapa12->fetch_assoc();
					$jumgtmapa12=$getmapa12->num_rows;
					//echo "$sqlgetmapa12<br>$jumgtmapa12";

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
						$sqlskemamapa1="INSERT INTO `skema_mapa1`(`id_skemakkni`, `id_unitkompetensi`, `id_elemenkompetensi`, `id_kriteria`, `pendekatan`, `pendekatan_ket`, `tujuan`, `tujuanket`, `konteks_a`, `konteks_b`, `konteks_c1`, `konteks_c2`, `konteks_c3`, `konteks_d`, `konfirmasi1`, `konfirmasi2`, `konfirmasi3`, `konfirmasi4`, `konfirmasi4_ket`, `toluk1`, `toluk1_ket`, `toluk2`, `toluk2_ket`, `toluk3`, `toluk3_ket`, `toluk4`, `toluk4_ket`, `toluk5`, `toluk5_ket`, `ket_bukti`, `bukti_L`, `bukti_TL`, `bukti_T`, `metode1`, `metode2`, `metode3`, `metode4`, `metode5`, `metode6`, `modkon1a`, `modkon1a_ket`, `modkon1b`, `modkon1b_ket`, `modkon2`, `modkon2_ket`, `modkon3`, `modkon3_ket`, `modkon4`, `modkon4_ket`, `konfirm1`, `konfirm1_ket`, `konfirm2`, `konfirm2_ket`, `konfirm3`, `konfirm3_ket`, `konfirm4`, `konfirm4_ket`) VALUES ('$id_skemakkni', '$id_unitkompetensi', '$id_elemenkompetensi', '$id_kriteria', '$pendekatan', '$pendekatan_ket', '$tujuan', '$tujuanket', '$konteks_a', '$konteks_b', '$konteks_c1', '$konteks_c2', '$konteks_c3', '$konteks_d', '$konfirmasi1', '$konfirmasi2', '$konfirmasi3', '$konfirmasi4', '$konfirmasi4_ket', '$toluk1', '$toluk1_ket', '$toluk2', '$toluk2_ket', '$toluk3', '$toluk3_ket', '$toluk4', '$toluk4_ket', '$toluk5', '$toluk5_ket', '$ket_bukti', '$bukti_L', '$bukti_TL', '$bukti_T', '$metode1', '$metode2', '$metode3', '$metode4', '$metode5', '$metode6', '$modkon1a', '$modkon1a_ket', '$modkon1b', '$modkon1b_ket', '$modkon2', '$modkon2_ket', '$modkon3', '$modkon3_ket', '$modkon4', '$modkon4_ket', '$konfirm1', '$konfirm1_ket', '$konfirm2', '$konfirm2_ket', '$konfirm3', '$konfirm3_ket', '$konfirm4', '$konfirm4_ket')";
						$conn->query($sqlskemamapa1);
						//echo "$sqlskemamapa1<br>";

					}else{
						$sqlskemamapa1="UPDATE `skema_mapa1` SET
							`id_skemakkni`='$id_skemakkni', 
							`id_unitkompetensi`='$id_unitkompetensi', 
							`id_elemenkompetensi`='$id_elemenkompetensi', 
							`id_kriteria`='$id_kriteria', 
							`pendekatan`='$pendekatan', 
							`pendekatan_ket`='$pendekatan_ket', 
							`tujuan`='$tujuan', 
							`tujuanket`='$tujuanket', 
							`konteks_a`='$konteks_a', 
							`konteks_b`='$konteks_b', 
							`konteks_c1`='$konteks_c1', 
							`konteks_c2`='$konteks_c2', 
							`konteks_c3`='$konteks_c3', 
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
							`toluk5_ket`='$toluk5_ket', 
							`ket_bukti`='$ket_bukti', 
							`bukti_L`='$bukti_L', 
							`bukti_TL`='$bukti_TL', 
							`bukti_T`='$bukti_T', 
							`metode1`='$metode1', 
							`metode2`='$metode2', 
							`metode3`='$metode3', 
							`metode4`='$metode4', 
							`metode5`='$metode5', 
							`metode6`='$metode6', 
							WHERE `id`='$gtmapa12[id]'";
						$conn->query($sqlskemamapa1);
						$sqlskemamapa1_2="UPDATE `skema_mapa1` SET
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
							WHERE `id`='$gtmapa12[id]'";
						$conn->query($sqlskemamapa1_2);

						//echo "$noun2.$noel2.$nokuk2--$sqlskemamapa1---$_POST[konf1]<br>";

					}
					$nokuk2++;
				}
				$noel2++;
			}
			$noun2++;
		}
		/* echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> FR.MAPA.01 MERENCANAKAN AKTIVITAS DAN PROSES ASESMEN berhasil DISIMPAN</h4>
		</div>"; */
  		echo "<script>alert('MAPA-01 Telah Tersimpan/Terupdate'); window.location = 'media.php?module=skema'</script>";

?>