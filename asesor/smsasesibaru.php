<?php
include "../config/koneksi.php";
include "../config/library.php";
include "../config/fungsi_indotgl.php";
include "../config/fungsi_combobox.php";
include "../config/class_paging.php";
include "../config/fungsi_rupiah.php";


		$post0=$_POST['idpost'];
		$post1="nohp".$post0;
		$post2="pesan".$post0;

		if (strlen($_POST[$post1])>8){
			$sqloutbox="INSERT INTO `outbox` (`DestinationNumber`, `TextDecoded`, `Coding`,`SenderID`,`CreatorID`) VALUES ('$_POST[$post1]','$_POST[$post2]','Default_No_Compression','MyPhone1','MyPhone1')";
			$outbox=$conn->query($sqloutbox);
    		}
		//header('location:media.php?module=asesibaru');

							
?>