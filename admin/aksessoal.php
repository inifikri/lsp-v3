<?php
session_start();
include "../config/koneksi.php";

// cek akses asesi ke soal
$sqlgetaksessoal="SELECT * FROM `asesi_aksessoal` WHERE `id_skemakkni`='$_POST[id_skemakkni]' AND `id_jadwal`='$_POST[id_jadwal]' AND `jenis_soal`='$_POST[jenis_soal]'";
$getaksessoal=$conn->query($sqlgetaksessoal);
$gaso=$getaksessoal->fetch_assoc();
$jgaso=$getaksessoal->num_rows;
// GET VALUE INPUT
$nama_asesi    = $_POST['nama_asesi'];
$id_asesi      = $_POST['id_asesi']; 

$bukaakses='bukaaksessoal';
if (isset($_REQUEST[$bukaakses])){
	for ($i = 0; $i < count($id_asesi); $i++) {
		$nama = $nama_asesi[$i];
		$idasesi = $id_asesi[$i];
		if ($jgaso>0){
			$sqlupdateakses="UPDATE `asesi_aksessoal` SET `status`='1' WHERE `id_skemakkni`='$_POST[id_skemakkni]' AND `id_asesi`='$idasesi' AND `id_jadwal`='$_POST[id_jadwal]' AND `jenis_soal`='$_POST[jenis_soal]'";
			$conn->query($sqlupdateakses);
		}else{
			$sqlupdateakses="INSERT INTO `asesi_aksessoal`(`id_asesi`, `id_skemakkni`, `id_jadwal`, `jenis_soal`, `status`) VALUES ($idasesi,'$_POST[id_skemakkni]','$_POST[id_jadwal]','$_POST[jenis_soal]','1')";
			$conn->query($sqlupdateakses);
		}
	}
	
	echo "<script>alert('Akses Soal telah dibuka'); window.location = 'media.php?module=jadwalasesmen'</script>";
}
$tutupakses='tutupaksessoal';
if (isset($_REQUEST[$tutupakses])){
	for ($i = 0; $i < count($id_asesi); $i++) {
		$nama = $nama_asesi[$i];
		$idasesi = $id_asesi[$i];
		
		$sqlupdateakses="UPDATE `asesi_aksessoal` SET `status`='0' WHERE `id_skemakkni`='$_POST[id_skemakkni]' AND `id_asesi`='$idasesi' AND `id_jadwal`='$_POST[id_jadwal]' AND `jenis_soal`='$_POST[jenis_soal]'";
		$conn->query($sqlupdateakses);
	}
	echo "<script>alert('Akses Soal telah ditutup'); window.location = 'media.php?module=jadwalasesmen'</script>";
}
$perbaikanakses='perbaikan';
if (isset($_REQUEST[$perbaikanakses])){
	for ($i = 0; $i < count($id_asesi); $i++) {
		$nama = $nama_asesi[$i];
		$idasesi = $id_asesi[$i];

		$querylogdigisign="SELECT * FROM logdigisign WHERE id_asesi='$idasesi' AND id_skema='$_POST[id_skemakkni]' AND nama_dokumen='FR.AI.05.TES TERTULIS PILIHAN GANDA'";
		$qlogdigisign=$conn->query($querylogdigisign);
		$logdigisign=$qlogdigisign->fetch_assoc();
		unlink($logdigisign['file']);
		$hapuslogdigisign="DELETE FROM logdigisign WHERE id_asesi='$idasesi' AND id_jadwal='$_POST[id_jadwal]' AND id_skema='$_POST[id_skemakkni]' AND nama_dokumen='FR.AI.05.TES TERTULIS PILIHAN GANDA'";
		$conn->query($hapuslogdigisign);
		// Status 3 > Perbaikan
		$sqlupdateakses="UPDATE `asesi_aksessoal` SET `status`='3' WHERE `id_asesi`='$idasesi0' AND id_jadwal='$_POST[id_jadwal]'";
		$conn->query($sqlupdateakses);
	}
		echo "<script>alert('Akses Soal telah dibuka untuk perbaikan'); window.location = 'media.php?module=jadwalasesmen'</script>";
}
	
?>