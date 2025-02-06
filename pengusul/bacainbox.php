<?php
include "../config/koneksi.php";
include "../config/library.php";
include "../config/fungsi_indotgl.php";
include "../config/fungsi_combobox.php";
include "../config/class_paging.php";
include "../config/fungsi_rupiah.php";


		$post0=$_POST['idpostinbox'];

		$sqlinbox="UPDATE `inbox` SET `Processed`='true' WHERE `ID`='$post0'";
		$inbox=$conn->query($sqlinbox);
		header('location:media.php?module=inbox');

							
?>