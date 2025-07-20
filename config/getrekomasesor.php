<?php
 
ini_set('display_errors',0);
require_once "koneksi.php";

$asesorwil=$_GET['asesorwil'];
$wiltukx = $_GET['tuk'];
$sqlgetidwiltuk="SELECT * FROM `tuk` WHERE `id`='$wiltukx'";
$getidwiltuk=$conn->query($sqlgetidwiltuk);
$wiltuk=$getidwiltuk->fetch_assoc();

$sqlkec="SELECT * FROM `data_wilayah` WHERE `id_wil`='$wiltuk[id_wilayah]'";
$kec = $conn->query($sqlkec);
$k = $kec->fetch_assoc();

$sqlkota="SELECT * FROM `data_wilayah` WHERE `id_wil`='$k[id_induk_wilayah]'";
$kota = $conn->query($sqlkota);
$k2 = $kota->fetch_assoc();

$sqlprop="SELECT * FROM `data_wilayah` WHERE `id_wil`='$k2[id_induk_wilayah]'";
$prop = $conn->query($sqlprop);
$k3 = $prop->fetch_assoc();

if ($asesorwil=="1"){
   $sqlasesor="SELECT * FROM `asesor` WHERE `propinsi`='$k3[id_wil]' ORDER BY `nama` ASC";
   $asesor=$conn->query($sqlasesor);
   $jumasesor=$asesor->num_rows;
   if ($jumasesor>0){
	$sqlasesor2="SELECT * FROM `asesor` WHERE `propinsi`='$k3[id_wil]' ORDER BY `nama` ASC";
	$asesor2=$conn->query($sqlasesor2);
	echo "<option>--Pilih Asesor ".trim($k3['nm_wil'])."--</option>";
	while ($asr=$asesor2->fetch_assoc()){
		if (!empty($asr['gelar_depan'])){
			if (!empty($asr['gelar_blk'])){
				$namaasesor=$asr['gelar_depan']." ".$asr['nama'].", ".$asr['gelar_blk'];
			}else{
				$namaasesor=$asr['gelar_depan']." ".$asr['nama'];
			}
		}else{
			if (!empty($asr['gelar_blk'])){
				$namaasesor=$asr['nama'].", ".$asr['gelar_blk'];
			}else{
				$namaasesor=$asr['nama'];
			}
		}
		echo"<option value='$asr[id]'>$namaasesor</option>";
	}

   }else{
	$sqlasesor3="SELECT * FROM `asesor` ORDER BY `nama` ASC";
	$asesor3=$conn->query($sqlasesor3);

	echo "<option>--Pilih Asesor Non Wilayah--</option>";
	while ($asr=$asesor3->fetch_assoc()){
		if (!empty($asr['gelar_depan'])){
			if (!empty($asr['gelar_blk'])){
				$namaasesor=$asr['gelar_depan']." ".$asr['nama'].", ".$asr['gelar_blk'];
			}else{
				$namaasesor=$asr['gelar_depan']." ".$asr['nama'];
			}
		}else{
			if (!empty($asr['gelar_blk'])){
				$namaasesor=$asr['nama'].", ".$asr['gelar_blk'];
			}else{
				$namaasesor=$asr['nama'];
			}
		}
		echo"<option value='$asr[id]'>$namaasesor</option>";
	}

   }
}else{
	$sqlasesor4="SELECT * FROM `asesor` ORDER BY `nama` ASC";
	$asesor4=$conn->query($sqlasesor4);

	echo "<option>--Pilih Asesor Non Wilayah--</option>";
	while ($asr2=$asesor4->fetch_assoc()){
		if (!empty($asr2['gelar_depan'])){
			if (!empty($asr2['gelar_blk'])){
				$namaasesor=$asr2['gelar_depan']." ".$asr2['nama'].", ".$asr2['gelar_blk'];
			}else{
				$namaasesor=$asr2['gelar_depan']." ".$asr2['nama'];
			}
		}else{
			if (!empty($asr2['gelar_blk'])){
				$namaasesor=$asr2['nama'].", ".$asr2['gelar_blk'];
			}else{
				$namaasesor=$asr2['nama'];
			}
		}
		echo"<option value='$asr2[id]'>$namaasesor</option>";
	}

}

?>