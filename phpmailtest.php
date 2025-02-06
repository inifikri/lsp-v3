<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "classes/class.phpmailer.php";
include "config/koneksi.php";
date_default_timezone_set("Asia/Jakarta");
$mail = new PHPMailer; 
$mail->IsSMTP();
//$mail->SMTPSecure = 'ssl'; 
$mail->SMTPSecure = 'tls'; 
$mail->Host = $_POST['host']; //host masing2 provider email
$mail->SMTPDebug = 2;
//$mail->Port = 465;
$mail->Port = 587;
$mail->SMTPAuth = true;
$mail->Username = $_POST['username']; //user email
$mail->Password = $_POST['password']; //password email 
//Set who the message is to be sent from
$mail->setFrom("$_POST[username]","Sistem Informasi LSP");
//Set an alternative reply-to address
$mail->addReplyTo("$_POST[username]","Pengembang Aplikasi Sistem Informasi LSP");
$mail->Subject = "Testing Email Sistem Informasi LSP Berhasil"; //subyek email
$mail->AddAddress($_POST['tujuan'],$_POST['nama']);  //tujuan email
$mail->MsgHTML("Bila email ini diterima, maka Sistem Informasi LSP telah berhasil mengirim email dengan pengaturan atau konfigurasi SMTP yang dilakukan.");
if($mail->Send()) echo "Message has been sent";
else echo "Failed to sending message";
echo "<br><br><a href='admin/media.php?module=smtp'>Kembali</a>";
//mail($email,$subjek,$pesan,$dari);
//echo $pesan; 
?>