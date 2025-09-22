<?php
session_start();
include "../config/koneksi.php";
	
// cek akses asesi ke soal
$sqlgetaksessoal="SELECT * FROM `asesi_asesmen` WHERE `id_skemakkni`='$_POST[id_skemakkni]' AND `id_asesi`='$_POST[id_asesi]' AND `id_jadwal`='$_POST[id_jadwal]'";
$getaksessoal=$conn->query($sqlgetaksessoal);
$gaso=$getaksessoal->fetch_assoc();
$jgaso=$getaksessoal->num_rows;
$bukaakses='keputusanK';
if (isset($_REQUEST[$bukaakses])){
	if ($jgaso>0){
		$sqlupdateakses="UPDATE `asesi_asesmen` SET `status_asesmen`='K', `keputusan_asesor`='R' WHERE `id_skemakkni`='$_POST[id_skemakkni]' AND `id_asesi`='$_POST[id_asesi]' AND `id_jadwal`='$_POST[id_jadwal]'";
		$conn->query($sqlupdateakses);
	}
	echo "<script>alert('Anda memutuskan bahwa asesi : ($_POST[nama_asesi]) Anda nyatakan KOMPETEN'); window.location = 'media.php?module=pesertaasesmen&idj=$_POST[id_jadwal]'</script>";
}
$tutupakses='keputusanBK';
if (isset($_REQUEST[$tutupakses])){
	$sqlupdateakses="UPDATE `asesi_asesmen` SET `status_asesmen`='BK', `keputusan_asesor`='NR' WHERE `id_skemakkni`='$_POST[id_skemakkni]' AND `id_asesi`='$_POST[id_asesi]' AND `id_jadwal`='$_POST[id_jadwal]'";
	$conn->query($sqlupdateakses);
	echo "<script>alert('Anda memutuskan bahwa asesi : ($_POST[nama_asesi]) Anda nyatakan BELUM KOMPETEN'); window.location = 'media.php?module=pesertaasesmen&idj=$_POST[id_jadwal]'</script>";
}
$tindaklanjutakses='keputusanTL';
if (isset($_REQUEST[$tindaklanjutakses])){
	$sqlupdateakses="UPDATE `asesi_asesmen` SET `status_asesmen`='TL', `keputusan_asesor`='NR' WHERE `id_skemakkni`='$_POST[id_skemakkni]' AND `id_asesi`='$_POST[id_asesi]' AND `id_jadwal`='$_POST[id_jadwal]'";
	$conn->query($sqlupdateakses);
	echo "<script>alert('Anda memutuskan bahwa asesi : ($_POST[nama_asesi]) Anda nyatakan BELUM KOMPETEN dan UJI ULANG/ TINDAK LANJUT'); window.location = 'media.php?module=pesertaasesmen&idj=$_POST[id_jadwal]'</script>";
}
$resetakses='keputusanP';
if (isset($_REQUEST[$resetakses])){
	$sqlupdateakses="UPDATE `asesi_asesmen` SET `status_asesmen`='P', `keputusan_asesor`=NULL WHERE `id_skemakkni`='$_POST[id_skemakkni]' AND `id_asesi`='$_POST[id_asesi]' AND `id_jadwal`='$_POST[id_jadwal]'";
	$conn->query($sqlupdateakses);
	echo "<script>alert('Anda melakukan pengaturan ulang (reset) keputusan asesmen terhadap asesi : ($_POST[nama_asesi])'); window.location = 'media.php?module=pesertaasesmen&idj=$_POST[id_jadwal]'</script>";
}
	
?>