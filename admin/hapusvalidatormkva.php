<?php
session_start();
include "../config/koneksi.php";
include "../config/library.php";
include "../config/fungsi_indotgl.php";
include "../config/fungsi_combobox.php";
include "../config/class_paging.php";
//include "../config/fungsi_rupiah.php";
include "../classes/class.phpmailer.php";

$idadel=$_POST['idadel'];
$idjdel=$_POST['idjdel'];
$sqlidentitas="SELECT * FROM `identitas`";
$identitas=$conn->query($sqlidentitas);
$iden=$identitas->fetch_assoc();
$urldomain=$iden['url_domain'];

$ceka=$conn->query("SELECT * FROM `jadwal_asesmen` WHERE `asesor_mkva1`='$idadel' AND `id`='$idjdel' OR `asesor_mkva2`='$idadel' AND `id`='$idjdel'");
$asmk=$ceka->fetch_assoc();
$jumceka=$ceka->num_rows;
if ($jumceka!=0){
    if ($asmk['asesor_mkva1']==$idadel){
		// SMS Asesor
		$sqlgethpasesor="SELECT * FROM `asesor` WHERE `id`='$idadel'";
		$gethpasesor=$conn->query($sqlgethpasesor);
		$hpasr=$gethpasesor->fetch_assoc();
		$email=$hpasr['email'];
		$namanya=$hpasr['nama'];
		$no_hp=$hpasr['nohp'];

		$isismsasr="Yth. ".$hpasr['nama'].", Tugas Memberikan Kontribusi dalam Validasi Asesmen (MKVA) pada ".$_POST['tgl_verifikasidel']." DIBATALKAN, info lengkap lihat di http://".$urldomain."/asesor";
		if (strlen($hpasr['no_hp'])>8){
			$sqloutbox="INSERT INTO `outbox` (`DestinationNumber`, `TextDecoded`, `Coding`,`SenderID`,`CreatorID`) VALUES ('$hpasr[no_hp]','$isismsasr','Default_No_Compression','MyPhone1','MyPhone1')";
			$outbox=$conn->query($sqloutbox);
		}
		// Email Asesor
		$sqlgetskema="SELECT * FROM `skema_kkni` WHERE `id`='$_POST[skemakknidel]'";
		$getskema=$conn->query($sqlgetskema);
		$gs=$getskema->fetch_assoc();

		$sqltuk2="SELECT * FROM `tuk` WHERE `id`='$_POST[tukdel]'";
		$tuk2=$conn->query($sqltuk2);
		$tt2=$tuk2->fetch_assoc();
		$tempatasesmen=$tt2['nama']." ".$tt2['alamat'];
		$tglasesmen=$_POST['tgl_asesmendel'];
		// Kirim email dalam format HTML ke Pendaftar
		    $pesan ="Yth. $namanya, penjadwalan Anda Memberikan Kontribusi dalam Validasi Asesmen (MKVA) pada Asesmen Skema $gs[kode_skema] - $gs[judul]<br /><br />  
		            Tanggal: $tglasesmen <br />
		            Tempat: $tempatasesmen <br />
			    Skema: $gs[kode_skema] - $gs[judul]<br />
		    		<br />DINYATAKAN DIBATALKAN<br />Untuk info selengkapnya silahkan lihat di laman Dashboard Anda di http://$urldomain/asesor.<br /><br />";
		    
			    $subjek="Penjadwalan Anda Memberikan Kontribusi dalam Validasi Asesmen (MKVA) pada $tglasesmen DINYATAKAN DIBATALKAN";
			    $dari = "From: noreply@".$urldomain."\r\n";
			    $dari .= "Content-type: text/html\r\n";
    // Kirim email ke member
	$sqlgetsmtp="SELECT * FROM `smtp` WHERE `aktif`='Y'";
	$getsmtp=$conn->query($sqlgetsmtp);
	$gsmtp=$getsmtp->fetch_assoc();

	$sqlidentitas="SELECT * FROM `identitas`";
	$identitas=$conn->query($sqlidentitas);
	$iden=$identitas->fetch_assoc();

	date_default_timezone_set("Asia/Jakarta");
	$mail = new PHPMailer; 
	$mail->IsSMTP();
	$mail->SMTPSecure = $gsmtp['protokol']; 
	$mail->Host = $gsmtp['host']; //host masing2 provider email
	$mail->SMTPDebug = 0;
	$mail->Port = $gsmtp['port'];
	$mail->SMTPAuth = true;
	$mail->Username = $gsmtp['username']; //user email
	$mail->Password = $gsmtp['password']; //password email 
	//Set who the message is to be sent from
	$mail->setFrom("$gsmtp[username]",$iden['nama_lsp']);
	//Set an alternative reply-to address
	$mail->addReplyTo("$gsmtp[username]",$iden['nama_lsp']);
	$mail->Subject = $subjek; //subyek email
	$mail->AddAddress($email,$namanya);  //tujuan email
	$mail->MsgHTML($pesan);
	if ($mail->Send()){
	    header("location:media.php?module=validatormkva&idj=".$_POST['idjdel']);
	}else{
		echo "Notifikasi Email Gagal Terkirim, periksa pengaturan di menu Manajemen, sub menu Pengaturan SMTP";
	}

    	//mail($email,$subjek,$pesan,$dari);

	$conn->query("UPDATE `jadwal_asesmen` SET `asesor_mkva1`=NULL WHERE `id`='$idjdel'");
	header("location:media.php?module=validatormkva&idj=".$_POST['idjdel']);
    }else{
		// SMS Asesor
		$sqlgethpasesor="SELECT * FROM `asesor` WHERE `id`='$idadel'";
		$gethpasesor=$conn->query($sqlgethpasesor);
		$hpasr=$gethpasesor->fetch_assoc();
		$email=$hpasr['email'];
		$namanya=$hpasr['nama'];
		$no_hp=$hpasr['nohp'];

		$isismsasr="Yth. ".$hpasr['nama'].", Tugas Memberikan Kontribusi dalam Validasi Asesmen (MKVA) pada ".$_POST['tgl_asesmendel']." DIBATALKAN, info lengkap lihat di http://".$urldomain."/asesor";
		if (strlen($hpasr['no_hp'])>8){
			$sqloutbox="INSERT INTO `outbox` (`DestinationNumber`, `TextDecoded`, `Coding`,`SenderID`,`CreatorID`) VALUES ('$hpasr[no_hp]','$isismsasr','Default_No_Compression','MyPhone1','MyPhone1')";
			$outbox=$conn->query($sqloutbox);
		}
		// Email Asesor
		$sqlgetskema="SELECT * FROM `skema_kkni` WHERE `id`='$_POST[skemakknidel]'";
		$getskema=$conn->query($sqlgetskema);
		$gs=$getskema->fetch_assoc();

		$sqltuk2="SELECT * FROM `tuk` WHERE `id`='$_POST[tukdel]'";
		$tuk2=$conn->query($sqltuk2);
		$tt2=$tuk2->fetch_assoc();
		$tempatasesmen=$tt2['nama']." ".$tt2['alamat'];
		$tglasesmen=$_POST['tgl_asesmendel'];
		// Kirim email dalam format HTML ke Pendaftar
		    $pesan ="Yth. $namanya, penjadwalan Anda Memberikan Kontribusi dalam Validasi Asesmen (MKVA) pada Asesmen Skema $gs[kode_skema] - $gs[judul]<br /><br />  
		            Tanggal: $tglasesmen <br />
		            Tempat: $tempatasesmen <br />
			    Skema: $gs[kode_skema] - $gs[judul]<br />
		    		<br />DINYATAKAN DIBATALKAN<br />Untuk info selengkapnya silahkan lihat di laman Dashboard Anda di http://$urldomain/asesor.<br /><br />";
		    
			    $subjek="Penjadwalan Anda Memberikan Kontribusi dalam Validasi Asesmen (MKVA) pada $tglasesmen DINYATAKAN DIBATALKAN";
			    $dari = "From: noreply@".$urldomain."\r\n";
			    $dari .= "Content-type: text/html\r\n";
    // Kirim email ke member
	$sqlgetsmtp="SELECT * FROM `smtp` WHERE `aktif`='Y'";
	$getsmtp=$conn->query($sqlgetsmtp);
	$gsmtp=$getsmtp->fetch_assoc();

	$sqlidentitas="SELECT * FROM `identitas`";
	$identitas=$conn->query($sqlidentitas);
	$iden=$identitas->fetch_assoc();

	date_default_timezone_set("Asia/Jakarta");
	$mail = new PHPMailer; 
	$mail->IsSMTP();
	$mail->SMTPSecure = $gsmtp['protokol']; 
	$mail->Host = $gsmtp['host']; //host masing2 provider email
	$mail->SMTPDebug = 0;
	$mail->Port = $gsmtp['port'];
	$mail->SMTPAuth = true;
	$mail->Username = $gsmtp['username']; //user email
	$mail->Password = $gsmtp['password']; //password email 
	//Set who the message is to be sent from
	$mail->setFrom("$gsmtp[username]",$iden['nama_lsp']);
	//Set an alternative reply-to address
	$mail->addReplyTo("$gsmtp[username]",$iden['nama_lsp']);
	$mail->Subject = $subjek; //subyek email
	$mail->AddAddress($email,$namanya);  //tujuan email
	$mail->MsgHTML($pesan);
	if ($mail->Send()){
	    header("location:media.php?module=validatormkva&idj=".$_POST['idjdel']);
	}else{
		echo "Notifikasi Email Gagal Terkirim, periksa pengaturan di menu Manajemen, sub menu Pengaturan SMTP";
	}

    	//mail($email,$subjek,$pesan,$dari);

	$conn->query("UPDATE `jadwal_asesmen` SET `asesor_mkva2`=NULL WHERE `id`='$idjdel'");
	header("location:media.php?module=validatormkva&idj=".$_POST['idjdel']);

    }
}
?>