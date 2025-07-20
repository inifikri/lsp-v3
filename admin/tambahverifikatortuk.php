<?php
session_start();
include "../config/koneksi.php";
include "../config/library.php";
include "../config/fungsi_indotgl.php";
include "../config/fungsi_combobox.php";
include "../config/class_paging.php";
//include "../config/fungsi_rupiah.php";
include "../classes/class.phpmailer.php";

$ida=$_POST['ida'];
$idj=$_POST['idj'];
$sqlidentitas="SELECT * FROM `identitas`";
$identitas=$conn->query($sqlidentitas);
$iden=$identitas->fetch_assoc();
$urldomain=$iden['url_domain'];
$ceka=$conn->query("SELECT * FROM `asesor_verifikatortuk` WHERE `id_asesor`='$ida' AND `id_jadwal`='$idj'");
$jumceka=$ceka->num_rows;
if ($jumceka==0){
	$conn->query("INSERT INTO `asesor_verifikatortuk`(`id_asesor`, `id_jadwal`, `id_skemakkni`, `tgl_verifikasi`) VALUES ('$ida', '$idj', '$_POST[skemakkni]', '$_POST[tgl_verifikasi]')");
		// SMS Asesor
		$sqlgethpasesor="SELECT * FROM `asesor` WHERE `id`='$ida'";
		$gethpasesor=$conn->query($sqlgethpasesor);
		$hpasr=$gethpasesor->fetch_assoc();
		$email=$hpasr['email'];
		$namanya=$hpasr['nama'];
		$no_hp=$hpasr['nohp'];

		$isismsasr="Yth. ".$hpasr['nama'].", Anda dijawal untuk Tugas Verifikasi TUK pada ".$_POST['tgl_verifikasi'].", info lengkap lihat di http://".$urldomain."/asesor";
		if (strlen($hpasr['no_hp'])>8){
			$sqloutbox="INSERT INTO `outbox` (`DestinationNumber`, `TextDecoded`, `Coding`,`SenderID`,`CreatorID`) VALUES ('$hpasr[no_hp]','$isismsasr','Default_No_Compression','MyPhone1','MyPhone1')";
			$outbox=$conn->query($sqloutbox);
		}
		// Email Asesor
		$sqlgetskema="SELECT * FROM `skema_kkni` WHERE `id`='$_POST[skemakkni]'";
		$getskema=$conn->query($sqlgetskema);
		$gs=$getskema->fetch_assoc();

		$sqltuk2="SELECT * FROM `tuk` WHERE `id`='$_POST[tuk]'";
		$tuk2=$conn->query($sqltuk2);
		$tt2=$tuk2->fetch_assoc();
		$tempatasesmen=$tt2['nama']." ".$tt2['alamat'];
		$tglasesmen=$_POST['tgl_verifikasi'];
		// Kirim email dalam format HTML ke Pendaftar
		    $pesan ="Yth. $namanya, Anda dijadwalkan sebagai Verifikator Tempat Uji Kompetensi (TUK) pada Asesmen Skema $gs[kode_skema] - $gs[judul]<br /><br />  
		            Tanggal: $tglasesmen <br />
		            Tempat (TUK): $tempatasesmen <br />
			    Skema: $gs[kode_skema] - $gs[judul]<br />
		    		<br /><br />Untuk info selengkapnya silahkan lihat jadwal verifikasi TUK di laman Dashboard Anda di http://$urldomain/asesor.<br /><br />";
		    
			    $subjek="Anda dijadwal sebagai Verifikator TUK pada $tglasesmen";
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
	    header("location:media.php?module=verifikatortuk&idj=".$_POST['idj']);
	}else{
		echo "Notifikasi Email Gagal Terkirim, periksa pengaturan di menu Manajemen, sub menu Pengaturan SMTP";
	}

    	//mail($email,$subjek,$pesan,$dari);

	header("location:media.php?module=verifikatortuk&idj=".$_POST['idj']);
}
?>