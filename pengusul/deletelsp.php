<?php
session_start();
include "../config/koneksi.php";
$id=$_POST['id'];
$conn->query("DELETE FROM `lsp` WHERE `id`='$id'");
?>