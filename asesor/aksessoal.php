<?php
session_start();
include "../config/koneksi.php";
	
// cek akses asesi ke soal
$sqlgetaksessoal="SELECT * FROM `asesi_aksessoal` WHERE `id_skemakkni`='$_POST[id_skemakkni]' AND `id_asesi`='$_POST[id_asesi]' AND `id_jadwal`='$_POST[id_jadwal]' AND `jenis_soal`='$_POST[jenis_soal]'";
$getaksessoal=$conn->query($sqlgetaksessoal);
$gaso=$getaksessoal->fetch_assoc();
$jgaso=$getaksessoal->num_rows;

// UPDATE @FHM-PUSTI 8 AGUSTUS 2023 : Cek data Logdigisign
$querylogdigisign="SELECT * FROM logdigisign WHERE id_asesi='$_POST[id_asesi]' AND id_skema='$_POST[id_skemakkni]' AND nama_dokumen='FR.AI.05.TES TERTULIS PILIHAN GANDA'";
$qlogdigisign=$conn->query($querylogdigisign);
$logdigisign=$qlogdigisign->fetch_assoc();

$bukaakses='bukaaksessoal';
if (isset($_REQUEST[$bukaakses])){
	if ($jgaso>0){
		$sqlupdateakses="UPDATE `asesi_aksessoal` SET `status`='1' WHERE `id_skemakkni`='$_POST[id_skemakkni]' AND `id_asesi`='$_POST[id_asesi]' AND `id_jadwal`='$_POST[id_jadwal]' AND `jenis_soal`='$_POST[jenis_soal]'";
		$conn->query($sqlupdateakses);
	}else{
		$sqlupdateakses="INSERT INTO `asesi_aksessoal`(`id_asesi`, `id_skemakkni`, `id_jadwal`, `jenis_soal`, `status`) VALUES ('$_POST[id_asesi]','$_POST[id_skemakkni]','$_POST[id_jadwal]','$_POST[jenis_soal]','1')";
		$conn->query($sqlupdateakses);
	}
	echo "<script>alert('Akses Soal untuk Asesi ini ($_POST[nama_asesi]) telah dibuka'); window.location = 'media.php?module=pesertaasesmen&idj=$_POST[id_jadwal]'</script>";
}
$tutupakses='tutupaksessoal';
if (isset($_REQUEST[$tutupakses])){
	$sqlupdateakses="UPDATE `asesi_aksessoal` SET `status`='0' WHERE `id_skemakkni`='$_POST[id_skemakkni]' AND `id_asesi`='$_POST[id_asesi]' AND `id_jadwal`='$_POST[id_jadwal]' AND `jenis_soal`='$_POST[jenis_soal]'";
	$conn->query($sqlupdateakses);
	echo "<script>alert('Akses Soal untuk Asesi ini ($_POST[nama_asesi]) telah ditutup'); window.location = 'media.php?module=pesertaasesmen&idj=$_POST[id_jadwal]'</script>";
}

$tandatangan='tandatangan';
if (isset($_REQUEST[$tandatangan])){
	// $sqlupdateakses="UPDATE `asesi_aksessoal` SET `status`='0' WHERE `id_skemakkni`='$_POST[id_skemakkni]' AND `id_asesi`='$_POST[id_asesi]' AND `id_jadwal`='$_POST[id_jadwal]' AND `jenis_soal`='$_POST[jenis_soal]'";
	// $conn->query($sqlupdateakses);

$folderPath = "../foto_tandatangan/";
$image_parts = explode(";base64,", $_POST['signed']);
$image_type_aux = explode("image/", $image_parts[0]);
$image_type = $image_type_aux[1];
$image_base64 = base64_decode($image_parts[1]);
$file_name = uniqid() . '.' . $image_type;
$file = $folderPath . $file_name;
file_put_contents($file, $image_base64);

// Baru ambil data lainnya
$iddokumen = $_POST['id_dokumen'];
$id_skema = $_POST['id_skemakkni'];
$id_asesi = $_POST['id_asesittd'];
$url_ditandatangani = htmlspecialchars($_POST['url_ditandatangani'], ENT_QUOTES, 'UTF-8');
$nama_dokumen = $_POST['nama_dokumen'];
$penandatangan = $_POST['penandatangan'];
$ip = $_SERVER['REMOTE_ADDR'];
$id_jadwal = $_POST['id_jadwal'];

// Insert log
$sql = "INSERT INTO logdigisign 
        (id_dokumen, id_skema, id_asesi, url_ditandatangani, nama_dokumen, penandatangan, file, ip, id_jadwal) 
        VALUES 
        ('$iddokumen', '$id_skema', '$id_asesi', '$url_ditandatangani', '$nama_dokumen', '$penandatangan', '$file', '$ip', '$id_jadwal')";
$conn->query($sql);



	echo "<script>alert('Tanda Tangan Berhasil'); window.location = 'media.php?module=pesertaasesmen&idj=$_POST[id_jadwal]'</script>";
}



$perbaikanakses='perbaikan';
if (isset($_REQUEST[$perbaikanakses])){
	unlink($logdigisign['file']);
	$hapuslogdigisign="DELETE FROM logdigisign WHERE id_asesi='$_POST[id_asesi]' AND id_jadwal='$_POST[id_jadwal]' AND id_skema='$_POST[id_skemakkni]' AND nama_dokumen='FR.AI.05.TES TERTULIS PILIHAN GANDA'";
	$conn->query($hapuslogdigisign);
	// Status 3 > Perbaikan
	$sqlupdateakses="UPDATE `asesi_aksessoal` SET `status`='3' WHERE `id`='$_POST[id]'";
	$conn->query($sqlupdateakses);
	echo "<script>alert('Akses Soal untuk Asesi ini ($_POST[nama_asesi]) telah dibuka untuk perbaikan'); window.location = 'media.php?module=pesertaasesmen&idj=$_POST[id_jadwal]'</script>";
}
	
?>