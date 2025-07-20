<?php
	include "../config/koneksi.php";
	include "../config/library.php";
	include "../config/fungsi_indotgl.php";
	
	$sqlidentitas="SELECT * FROM `identitas`";
	$identitas=$conn->query($sqlidentitas);
	$iden=$identitas->fetch_assoc();
	$urldomain=$iden['url_domain'];
	
	// cek apakah LSP Kontruksi
	$sqlakunpupr="SELECT * FROM `user_pupr`";
	$akunpupr=$conn->query($sqlakunpupr);
	$pupr=$akunpupr->num_rows;

	ini_set('post_max_size', '20M');
	ini_set('upload_max_filesize', '20M');

	function uploadDocCert($filecert){
	//$file_max_weight = 20000000; //Ukuran maksimal yg dibolehkan( 20 Mb)
	$ok_ext = array('jpg','png','gif','jpeg','JPG','PNG','GIF','JPEG','pdf','PDF'); // ekstensi yang diijinkan
	$destination = "../foto_asesicert/"; // tempat buat upload
	$filename = explode(".", $filecert['name']); 
	$file_name = $filecert['name'];
	$file_name_no_ext = isset($filename[0]) ? $filename[0] : null;
	$file_extension = $filename[count($filename)-1];
	$file_weight = $filecert['size'];
	$file_type = $filecert['type'];

	// Jika tidak ada error
	if( $filecert['error'] == 0 ){					
		$dateNow = date_create();
		$time_stamp = date_format($dateNow, 'U');
			if(in_array($file_extension, $ok_ext)):
				//if( $file_weight <= $file_max_weight ):
					$fileNewName = $time_stamp.md5( $file_name_no_ext[0].microtime() ).".".$file_extension;
					$namafilesertifikat=$fileNewName;
					if( move_uploaded_file($filecert['tmp_name'], $destination.$fileNewName) ):
						//echo" File uploaded !";
					else:
						//echo "can't upload file.";
					endif;
				//else:
					//echo "File too heavy.";
				//endif;
			else:
				echo "File type is not supported.";
			endif;
			}	
		return $namafilesertifikat;
	}

	$post0=$_POST['idpost'];
	$post1="no_serisertifikat".$post0;
	$post2="no_lisensi".$post0;
	$post3="masa_berlaku".$post0;
	$post4="file".$post0;
	$filecert = $_FILES[$post4];

	$namafilesertifikat= uploadDocCert($filecert);

	$sqlupdatecert="UPDATE `asesi_asesmen` SET `no_serisertifikat`='$_POST[$post1]',`no_lisensi`='$_POST[$post2]',`masa_berlaku`='$_POST[$post3]',`foto_sertifikat`='$namafilesertifikat' WHERE `id`='$post0'";
	$conn->query($sqlupdatecert);
	
	if ($pupr>0){
		// authorisasi sistem BNSP
		$sqlgetatoken2="SELECT * FROM `user_bnsp`";
		$gettoken2=$conn->query($sqlgetatoken2);
		$tokenx=$gettoken2->fetch_assoc();
		$tokenuserx=$tokenx['username'];
		$tokenkeyx=$tokenx['password'];

		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://konstruksi.bnsp.go.id/api/v1/auth',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_HTTPHEADER => array(
			'x-bnsp-user: '.$tokenuserx.'',
			'x-bnsp-key: '.$tokenkeyx.'',
			'Content-Type: application/json',
			'Content-Length: 0',
			'Connection: keep-alive',
			'Accept-Encoding: gzip, deflate, br',
			'User-Agent: PostmanRuntime/7.29.0'
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		//echo $response;

		$datanya=json_decode($response, true);
		$thepesan=$datanya["message"];
		$thedata=$datanya["data"]["token"];
		//======================================================
		$getdataasesmen="SELECT * FROM `asesi_asesmen` WHERE `id`='$post0'";
		$dataasesmen=$conn->query($getdataasesmen);
		$dtasm=$dataasesmen->fetch_assoc();
		$sqljadwaltuk="SELECT * FROM `jadwal_asesmen` WHERE `id`='$dtasm[id_jadwal]'";
		$jadwaltuk=$conn->query($sqljadwaltuk);
		$jdw=$jadwaltuk->fetch_assoc();
		$sqlgetasesi="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$dtasm[id_asesi]'";
		$getasesi=$conn->query($sqlgetasesi);
		$as=$getasesi->fetch_assoc();
		$sqlceksertifikat="SELECT * FROM `sertifikat` WHERE `id_asesi`='$dtasm[id_asesi]' AND `id_jadwal`='$dtasm[id_jadwal]'";
		$ceksertifikat=$conn->query($sqlceksertifikat);
		$certx=$ceksertifikat->fetch_assoc();
		
		$jadwal_id=$jdw['id_jadwalbnsp'];
		$nik_peserta=$as['no_ktp'];
		$nomor_sertifikat=$certx['no_sertifikat'];
		$nomor_reg=$certx['no_register'];
		$nomor_reg_lpjk=$certx['no_registrasisertifikat'];
		$link_sertifikat="https://".$urldomain."/foto_asesicert/".$dtasm['foto_sertifikat'];
		$tgl_srtf=$certx['tgl_terbit'];
		$tgl_srtf_end=$certx['masa_berlaku'];
		
		/* echo $jadwal_id."<br />";
		echo $nik_peserta."<br />";
		echo $nomor_sertifikat."<br />";
		echo $nomor_reg."<br />";
		echo $nomor_reg_lpjk."<br />";
		echo $link_sertifikat."<br />";
		echo $tgl_srtf."<br />";
		echo $tgl_srtf_end."<br />";
		echo $thedata."<br />"; */

		$datas=array(
			'jadwal_id' => $jadwal_id,
			'nik_peserta' => $nik_peserta,
			'nomor_sertifikat' => $nomor_sertifikat,
			'nomor_reg' => $nomor_reg,
			'nomor_reg_lpjk' => $nomor_reg_lpjk,
			'link_sertifikat' => $link_sertifikat,
			'tgl_srtf' => $tgl_srtf,
			'tgl_srtf_end' => $tgl_srtf_end
		);
		$payload=json_encode($datas, true);

		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://konstruksi.bnsp.go.id/api/v1/jadwal/peserta/sertifikat',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS => array(
			'jadwal_id' => $jadwal_id,
			'nik_peserta' => $nik_peserta,
			'nomor_sertifikat' => $nomor_sertifikat,
			'nomor_reg' => $nomor_reg,
			'nomor_reg_lpjk' => $nomor_reg_lpjk,
			'link_sertifikat' => $link_sertifikat,
			'tgl_srtf' => $tgl_srtf,
			'tgl_srtf_end' => $tgl_srtf_end
		  ),
		  CURLOPT_HTTPHEADER => array(
			'x-authorization: '.$thedata.''
		  ),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		//echo $response;

		// pencatatan final di LPJK
		
		$sqlgetatoken="SELECT * FROM `user_pupr` WHERE `token` IS NOT NULL ORDER BY `id` ASC LIMIT 1";
		$gettoken=$conn->query($sqlgetatoken);
		$tokenx=$gettoken->fetch_assoc();
		$tokennya=$tokenx['token'];
		if ($dtasm['fresh_graduate']=='Y'){
			$urlnyalpjk="https://siki.pu.go.id/siki-api/v1/izin-final-skk-fg/".$as['no_pendaftaran'];
		}else{
			$urlnyalpjk="https://siki.pu.go.id/siki-api/v1/izin-final-skk/".$as['no_pendaftaran'];
		}
		$curl2 = curl_init();

		curl_setopt_array($curl2, array(
		  CURLOPT_URL => $urlnyalpjk,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS =>'{
			"file_lampiran":{
				"link_e_sertifikat":"'.$link_sertifikat.'"
			}
		}',
		  CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json',
			'token: '.$tokennya
		  ),
		));

		$response2 = curl_exec($curl2);

		curl_close($curl2);
		//echo $response2;
		echo "<script>alert('Pencatatan Final Sertifikat BNSP : ".$response.", LPJK : ".$response2.", silahkan cek di laman Jadwal BNSP dan Portal Perizinan'); window.location='media.php?module=asesik';</script>";

	}else{
		$sqlupdatecert2="UPDATE `sertifikat` SET `no_blangko`='$_POST[$post1]' WHERE `no_sertifikat`='$_POST[$post2]'";
		$conn->query($sqlupdatecert2);
		echo "<script>alert('Ungah Arsip Sertifikat Berhasil'); window.location='media.php?module=asesik';</script>";
	}							
?>