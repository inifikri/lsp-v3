<?php
session_start();
include "../config/koneksi.php";
	
// cek akses asesi ke soal
$sqlgetaksessoal="SELECT * FROM `komite_keputusan` WHERE `id_skemakkni`='$_POST[id_skemakkni]' AND `id_asesi`='$_POST[id_asesi]' AND `id_jadwal`='$_POST[id_jadwal]' AND `id_komite`='$_POST[id_komite]'";
$getaksessoal=$conn->query($sqlgetaksessoal);
$gaso=$getaksessoal->fetch_assoc();
$jgaso=$getaksessoal->num_rows;

$bukaakses='keputusanK';
if (isset($_REQUEST[$bukaakses])){
	if ($jgaso>0){
		$sqlupdateakses="UPDATE `komite_keputusan` SET `keputusan`='K' WHERE `id_skemakkni`='$_POST[id_skemakkni]' AND `id_asesi`='$_POST[id_asesi]' AND `id_jadwal`='$_POST[id_jadwal]' AND `id_komite`='$_POST[id_komite]'";
		$conn->query($sqlupdateakses);
	}else{
		$sqlinsertdata="INSERT INTO `komite_keputusan`(`id_skemakkni`, `id_asesi`, `id_jadwal`, `id_komite`, `keputusan`) VALUES ('$_POST[id_skemakkni]', '$_POST[id_asesi]', '$_POST[id_jadwal]', '$_POST[id_komite]', 'K')";
		$conn->query($sqlinsertdata);
	}
	echo "<script>alert('Anda memutuskan bahwa asesi : ($_POST[nama_asesi]) Anda nyatakan KOMPETEN'); window.location = 'media.php?module=pesertaasesmen&idj=$_POST[id_jadwal]'</script>";
}
$tutupakses='keputusanBK';
if (isset($_REQUEST[$tutupakses])){
	if ($jgaso>0){
		$sqlupdateakses="UPDATE `komite_keputusan` SET `keputusan`='BK' WHERE `id_skemakkni`='$_POST[id_skemakkni]' AND `id_asesi`='$_POST[id_asesi]' AND `id_jadwal`='$_POST[id_jadwal]' AND `id_komite`='$_POST[id_komite]'";
		$conn->query($sqlupdateakses);
	}else{
		$sqlinsertdata="INSERT INTO `komite_keputusan`(`id_skemakkni`, `id_asesi`, `id_jadwal`, `id_komite`, `keputusan`) VALUES ('$_POST[id_skemakkni]', '$_POST[id_asesi]', '$_POST[id_jadwal]', '$_POST[id_komite]', 'BK')";
		$conn->query($sqlinsertdata);
	}
	echo "<script>alert('Anda memutuskan bahwa asesi : ($_POST[nama_asesi]) Anda nyatakan BELUM KOMPETEN'); window.location = 'media.php?module=pesertaasesmen&idj=$_POST[id_jadwal]'</script>";
}
$tindaklanjutakses='keputusanTL';
if (isset($_REQUEST[$tindaklanjutakses])){
	if ($jgaso>0){
		$sqlupdateakses="UPDATE `komite_keputusan` SET `keputusan`='TL' WHERE `id_skemakkni`='$_POST[id_skemakkni]' AND `id_asesi`='$_POST[id_asesi]' AND `id_jadwal`='$_POST[id_jadwal]' AND `id_komite`='$_POST[id_komite]'";
		$conn->query($sqlupdateakses);
	}else{
		$sqlinsertdata="INSERT INTO `komite_keputusan`(`id_skemakkni`, `id_asesi`, `id_jadwal`, `id_komite`, `keputusan`) VALUES ('$_POST[id_skemakkni]', '$_POST[id_asesi]', '$_POST[id_jadwal]', '$_POST[id_komite]', 'TL')";
		$conn->query($sqlinsertdata);
	}
	echo "<script>alert('Anda memutuskan bahwa asesi : ($_POST[nama_asesi]) Anda nyatakan BELUM KOMPETEN dan UJI ULANG/ TINDAK LANJUT'); window.location = 'media.php?module=pesertaasesmen&idj=$_POST[id_jadwal]'</script>";
}
$resetakses='keputusanP';
if (isset($_REQUEST[$resetakses])){
	if ($jgaso>0){
		$sqlupdateakses="UPDATE `komite_keputusan` SET `keputusan`='P' WHERE `id_skemakkni`='$_POST[id_skemakkni]' AND `id_asesi`='$_POST[id_asesi]' AND `id_jadwal`='$_POST[id_jadwal]' AND `id_komite`='$_POST[id_komite]'";
		$conn->query($sqlupdateakses);
	}else{
		$sqlinsertdata="INSERT INTO `komite_keputusan`(`id_skemakkni`, `id_asesi`, `id_jadwal`, `id_komite`, `keputusan`) VALUES ('$_POST[id_skemakkni]', '$_POST[id_asesi]', '$_POST[id_jadwal]', '$_POST[id_komite]', 'P')";
		$conn->query($sqlinsertdata);
	}
	echo "<script>alert('Anda melakukan pengaturan ulang (reset) keputusan asesmen terhadap asesi : ($_POST[nama_asesi])'); window.location = 'media.php?module=pesertaasesmen&idj=$_POST[id_jadwal]'</script>";
}
	
?>