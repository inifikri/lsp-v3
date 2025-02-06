<?php
include "../config/koneksi.php";
include "../config/library.php";
include "../config/fungsi_indotgl.php";
include "../config/fungsi_combobox.php";
include "../config/class_paging.php";
include "../config/fungsi_rupiah.php";


$post0=$_POST['idpost'];
$filecert = $_POST['namadok'];
unlink('../foto_dokskkni/'.$filecert);

$sqlupdatecert="UPDATE `jadwal_asesmen` SET `dok_standarkompetensi`='' WHERE `id`='$post0'";
$conn->query($sqlupdatecert);
								
header('location:media.php?module=jadwalasesmen');

							
?>