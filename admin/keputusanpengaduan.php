<?php
session_start();
include "../config/koneksi.php";
	
// cek akses asesi ke soal
$sqlgetaksessoal="SELECT * FROM `pengaduan` WHERE `id`='$_POST[id_pengaduan]'";
$getaksessoal=$conn->query($sqlgetaksessoal);
$gaso=$getaksessoal->fetch_assoc();
$jgaso=$getaksessoal->num_rows;
$keputusan='keputusanSLS';
if (isset($_REQUEST[$keputusan])){
	if ($jgaso>0){
		$sqlupdateakses="UPDATE `pengaduan` SET `status`='Selesai'";
		$conn->query($sqlupdateakses);
	}
	echo "<script>alert('Anda memutuskan pengaduan : Anda nyatakan SELESAI'); window.location = 'media.php?module=pengaduan'</script>";
}
$keputusan2='keputusanTL';
if (isset($_REQUEST[$keputusan2])){
	if ($jgaso>0){
		$sqlupdateakses="UPDATE `pengaduan` SET `status`='Tindak Lanjut'";
		$conn->query($sqlupdateakses);
	}
	echo "<script>alert('Anda memutuskan pengaduan : Anda nyatakan Ditindak Lanjuti'); window.location = 'media.php?module=pengaduan'</script>";
}
$keputusan3='keputusanR';
if (isset($_REQUEST[$keputusan3])){
	if ($jgaso>0){
		$sqlupdateakses="UPDATE `pengaduan` SET `status`='Masuk'";
		$conn->query($sqlupdateakses);
	}
	echo "<script>alert('Anda memutuskan pengaduan : Anda nyatakan Masuk'); window.location = 'media.php?module=pengaduan'</script>";
}

?>