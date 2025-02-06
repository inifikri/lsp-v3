<?php
include "../../../config/koneksi.php";

$rsql=$conn->query("SELECT * FROM `users` WHERE `username`='$_POST[username]'");
$r=$rsql->fetch_assoc();
$pass_lama=md5($_POST[pass_lama]);
$pass_baru=md5($_POST[pass_baru]);

if (empty($_POST[pass_baru]) OR empty($_POST[pass_lama]) OR empty($_POST[pass_ulangi])){
  echo "<p align=center>Anda harus mengisikan semua data pada form Ganti Password.<br />"; 
  echo "<a href=javascript:history.go(-1)><b>Ulangi Lagi</b></a></p>";
}
else{ 
if (strlen($_POST[pass_baru])<8){
	  echo "<p align=center>Maaf Ubah Password Gagal!. Password minimal 8 karakter<br />"; 
  echo "<a href=javascript:history.go(-1)><b>Ulangi Lagi</b></a></p>";
}else{
// Apabila password lama cocok dengan password admin di database
if ($pass_lama==$_POST[password]){
  // Pastikan bahwa password baru yang dimasukkan sebanyak dua kali sudah cocok
  if ($_POST[pass_baru]==$_POST[pass_ulangi]){
    $conn->query("UPDATE `users` SET `password` = '$pass_baru' WHERE `username`='$_POST[username]'");
	echo "<script>alert('Ganti Kata Sandi (Password) Berhasil, silahkan masuk (login) kembali'); window.location = '../../index.php'</script>";
    //header('location:../../media.php?module=home');
  }
  else{
    echo "<p align=center>Password baru yang Anda masukkan sebanyak dua kali belum cocok.<br />"; 
    echo "<a href=javascript:history.go(-1)><b>Ulangi Lagi</b></a></p>";  
  }
}
else{
  
  echo "<p align=center>Anda salah memasukkan Password Lama Anda.<br />"; 
  echo "<a href=javascript:history.go(-1)><b>Ulangi Lagi</b></a></p>";
  
}
}
}
?>
