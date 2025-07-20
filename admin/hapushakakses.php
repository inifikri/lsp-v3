<?php
session_start();
include "../config/koneksi.php";
include "../classes/class.phpmailer.php";

$sqlidentitas="SELECT * FROM `identitas`";
$identitas=$conn->query($sqlidentitas);
$iden=$identitas->fetch_assoc();
$urldomain=$iden['url_domain'];
$ceka=$conn->query("SELECT * FROM `users_modul` WHERE `id_session`='$_POST[id_session]' AND `id_modul`='$_POST[id_modul]'");
$jumceka=$ceka->num_rows;
if ($jumceka>0){
	$conn->query("DELETE FROM `users_modul` WHERE `id_session`='$_POST[id_session]' AND `id_modul`='$_POST[id_modul]'");
	header("location:media.php?module=moduluser&uid=".$_POST['id_session']);
}
?>