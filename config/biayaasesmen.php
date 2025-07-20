<?php
 
ini_set('display_errors',0);
require_once "koneksi.php";
 
$jalurbayar = $_GET['skema'];
$sqljalur="SELECT * FROM `asesi_asesmen` WHERE `id_skemakkni`='$jalurbayar'";
$jalur=$conn->query($sqljalur);
$nom=$jalur->fetch_assoc();
echo "<input class='form-control' type='text' name='nominal' id='nominal' value='100' required>";
?>