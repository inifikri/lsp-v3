<?php
/*
-- Source Code from My Notes Code (www.mynotescode.com)
-- 
-- Follow Us on Social Media
-- Facebook : http://facebook.com/mynotescode/
-- Twitter  : http://twitter.com/code_notes
-- Google+  : http://plus.google.com/118319575543333993544
--
-- Terimakasih telah mengunjungi blog kami.
-- Jangan lupa untuk Like dan Share catatan-catatan yang ada di blog kami.
*/

// Load file koneksi.php
include "../config/koneksi.php";

$sqlidentitas="SELECT * FROM `identitas`";
$identitas=$conn->query($sqlidentitas);
$iden=$identitas->fetch_assoc();
$urldomain=$iden['url_domain'];

if(isset($_POST['import'])){ // Jika user mengklik tombol Import
	$nama_file_baru = 'data.xlsx';
	
	// Load librari PHPExcel nya
	require_once 'PHPExcel/PHPExcel.php';
	
	$excelreader = new PHPExcel_Reader_Excel2007();
	$loadexcel = $excelreader->load('tmp/'.$nama_file_baru); // Load file excel yang tadi diupload ke folder tmp
	$sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);
	
	
	$numrow = 1;
	foreach($sheet as $row){
		// Ambil data pada excel sesuai Kolom
		$no_ktp = $row['A']; // Ambil data NIS
		$nama = $row['B']; // Ambil data nama
		$jenis_kelamin = $row['C']; // Ambil data jenis kelamin
		$tempat_lahir = $row['D']; // Ambil data
		$tanggal_lahir = $row['E']; // Ambil data
		$telepon = $row['F']; // Ambil data
		$email = $row['G']; // Ambil data
		$alamat = $row['H']; // Ambil data
		$rt = $row['I']; // Ambil data
		$rw = $row['J']; // Ambil data
		$kelurahan = $row['K']; // Ambil data
		$kecamatan = $row['L']; // Ambil data
		$kota = $row['M']; // Ambil data
		$propinsi = $row['N']; // Ambil data
		$kodepos = $row['O']; // Ambil data
		$tgldaftar = $row['P']; // Ambil data

		$digitthn=date("Y");
		$digitbln=date("m");
		$digittgl=date("d");
		$genpass=rand(100000,999999);
		$pass1=md5($genpass);
		$pass2=substr($pass1,-6);
		$password=md5($pass2);
		$digitnohp=substr($no_ktp,-6);
		//$gennopendaf=$digitthn.$digitbln.$digittgl.$digitnohp;
		$gennopendaf=$no_ktp;

		// Cek jika semua data tidak diisi
		if(empty($no_ktp) && empty($nama) && empty($jenis_kelamin) && empty($tempat_lahir) && empty($tanggal_lahir) && empty($telepon) && empty($email) && empty($alamat) && empty($rt) && empty($rw) && empty($kelurahan) && empty($kecamatan) && empty($kota) && empty($propinsi) && empty($kodepos))
			continue; // Lewat data pada baris ini (masuk ke looping selanjutnya / baris selanjutnya)
		$sqlcekdata="SELECT * FROM `asesi` WHERE `no_ktp`='$no_ktp'";
		$cekdata=$conn->query($sqlcekdata);
		$numrow=$cekdata->num_rows;

		if($numrow == 0 && $nama!= 'Nama' && $no_ktp!='No. KTP'){
			// query Insert
			$query = "INSERT INTO `asesi`(`no_pendaftaran`, `password`, `nama`, `tmp_lahir`, `tgl_lahir`, `email`, `nohp`, `no_ktp`, `alamat`, `RT`, `RW`, `kelurahan`, `kecamatan`, `kota`, `propinsi`, `kodepos`, `jenis_kelamin`,`tgl_daftar`,`id_pengusul`) VALUES ('$gennopendaf', '$password', '$nama', '$tempat_lahir', '$tanggal_lahir', '$email', '$telepon', '$no_ktp', '$alamat', '$rt', '$rw', '$kelurahan', '$kecamatan', '$kota', '$propinsi', '$kodepos', '$jenis_kelamin','$tgldaftar','$_SESSION[namauser]')";
			$conn->query($query);

				// Kirim email dalam format HTML ke Pendaftar
				$pesan ="Terimakasih telah melakukan pendaftaran di LSP<br /><br />  
						Nomor Pendaftaran: $gennopendaf <br />
						Nama: $namanya <br />
						Nomor Handphone: $no_hp <br />
						Kata Sandi (Password): $pass2 <br />
						<br /><br />Silahkan lakukan masuk/login ke http://".$urldomain."/asesi, dan isi data-data yang diperlukan.";
				$subjek="Terimakasih telah melakukan Pendaftaran di SILSP";
				$dari = "From: noreply@".$urldomain."\r\n";
				$dari .= "Content-type: text/html\r\n";
				// Kirim email ke member
				mail($email,$subjek,$pesan,$dari);
				
				//SMS Pendaftar
				
				$isisms="Yth. $namanya Pendaftaran Anda berhasil, No. Pendaftaran Anda adalah $gennopendaf, Password : $pass2 Silahkan masuk/Login ke http://".$urldomain."/asesi";
				if (strlen($no_hp)>8){
					$sqloutbox="INSERT INTO `outbox` (`DestinationNumber`, `TextDecoded`, `Coding`,`SenderID`,`CreatorID`) VALUES ('$no_hp','$isisms','Default_No_Compression','MyPhone1','MyPhone1')";
					$outbox=$conn->query($sqloutbox);
				}

		}
	}
	
}

header('location: media.php?module=asesi'); // Redirect ke halaman awal
?>
