<?php
ini_set('display_errors',1); 
error_reporting(E_ALL);

session_start();
include "../config/koneksi.php";

$sqlidentitas="SELECT * FROM `identitas`";
$identitas=$conn->query($sqlidentitas);
$iden=$identitas->fetch_assoc();
$urldomain=$iden['url_domain'];
// get data dari BNSP
$sqlgetatoken="SELECT * FROM `user_bnsp` WHERE `token` IS NOT NULL";
$gettoken=$conn->query($sqlgetatoken);
$tokenxjum=$gettoken->num_rows;
$sqlgetatoken2="SELECT * FROM `user_bnsp`";
$gettoken2=$conn->query($sqlgetatoken2);
$tokenx=$gettoken2->fetch_assoc();
$tokenuserx=$tokenx['username'];
$tokenkeyx=$tokenx['password'];
$sqlskema="SELECT * FROM `skema_kkni` WHERE `id`='$_POST[id_skemakkni]'";
$skema=$conn->query($sqlskema);
$sq=$skema->fetch_assoc();
$sqltuk="SELECT * FROM `tuk` WHERE `id`='$_POST[tempat_asesmen]'";
$tuk=$conn->query($sqltuk);
$tt=$tuk->fetch_assoc();

if ($tokenxjum>0){
	$tokennya=$tokenx['token'];
	echo "Token Tersedia : $tokenx[token]";
}else{
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

	/* echo "<br>";
	echo $response; */

	$datanya=json_decode($response, true);
	$thepesan=$datanya["message"];
	$thedata=$datanya["data"]["token"];
	//echo "<br/>Token : ".$thedata;
	//echo "<br/>Expired : ".$datanya["data"]["expire_date"]."<br />";

	// mendapatkan data jabatan kerja

	$curl = curl_init();

	curl_setopt_array($curl, [
	  CURLOPT_URL => "https://konstruksi.bnsp.go.id/api/v1/master/jabatan",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "GET",
	  CURLOPT_HTTPHEADER => [
		"x-authorization: ".$thedata
	  ],
	]);

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);
	//echo $response;
	/* if ($err) {
	  echo "cURL Error #:" . $err;
	} else {
	  echo $response;
	} */
	$datanya=json_decode($response, true);
	 
	/*Dynamically generating rows & columns*/
	for($i = 0; $i < sizeof($datanya["data"]); $i++)
	{
		if ($datanya["data"][$i]["kode"]==$sq['kode_skema']){
			$klasifikasi_id=$datanya["data"][$i]["id_klas"];
			$subklasifikasi_id=$datanya["data"][$i]["id_sub_klas"];
			$jabker_id=$datanya["data"][$i]["data_id_jabatan_kerja"];
		}
	}

	/*Printing temp variable which holds table*/

	if (empty($tt['id_tuk_bnsp'])){
		echo "<script>alert('Maaf TUK Belum sesuai dengan data Sistem BNSP, silahkan cek di laman TUK'); window.location='media.php?module=jadwalasesmen';</script>";
	}else{
		$jenisjadwal=(int)$_POST['jenis_jadwal'];
		$jenisanggaran=(int)$_POST['jenis_anggaran'];
		$namajadwal=$_POST['nama_jadwal'];
		$tanggalmulai=$_POST['tgl_mulai'];
		$tanggalselesai=$_POST['tgl_selesai'];
		$keterangannya=$_POST['keterangan'];
		// get skema LSP

		$curl = curl_init();

		curl_setopt_array($curl, [
		  CURLOPT_URL => "https://konstruksi.bnsp.go.id/api/v1/skema/all?klasifikasi_id=".$klasifikasi_id."&subklasifikasi_id=".$subklasifikasi_id,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => [
			"x-authorization: ".$thedata
		  ],
		]);

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);
		//echo $response;
		/* if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		} */
		$datanya=json_decode($response, true);
		 
		/*Dynamically generating rows & columns*/
		for($i = 0; $i < sizeof($datanya["data"]); $i++)
		{
			if (!empty($datanya["data"][$i]["kode_lpjk"])){
				if ($datanya["data"][$i]["kode_lpjk"]==$sq['kode_skema']){
					$jabker_id=$datanya["data"][$i]["id"];
				}
			}else{
				if ($datanya["data"][$i]["kode"]==$sq['kodeskema_bnsp']){
					$jabker_id=$datanya["data"][$i]["id"];
				}
			}
		}
		
		$jabkerint=(int)$jabker_id;
		$tuk_id=(int)$tt['id_tuk_bnsp'];
		// cek tampilkan datanya

		$datas=array(
			'tuk_id' => $tuk_id,
			'jenis_jadwal' => $jenisjadwal,
			'jenis_anggaran' => $jenisanggaran,
			'nama_jadwal' => $namajadwal,
			'klasifikasi_id' => $klasifikasi_id,
			'subklasifikasi_id' => $subklasifikasi_id,
			'tanggal_mulai' => $tanggalmulai,
			'tanggal_selesai' => $tanggalselesai,
			'keterangan' => $keterangannya,
			'jabker_id' => [$jabkerint]
			);
		$payload=json_encode($datas, true);
		//echo $payload;
		$curl = curl_init();

		curl_setopt_array($curl, [
		  CURLOPT_URL => "https://konstruksi.bnsp.go.id/api/v1/jadwal",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => $payload,
		  CURLOPT_HTTPHEADER => [
			'Content-Type: application/json',
			'x-authorization: '.$thedata
		  ],
		]);

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		/* if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		} */

		$datanya=json_decode($response, true);
		$pesannya=$datanya["message"];
		$datajdwnya=$datanya["data"];
		$id_jadwal=$datanya["data"]["id_jadwal"];
		$kode_jadwal=$datanya["data"]["kode_jadwal"];
		// update data jadwal
		if (!empty($kode_jadwal)){
			$sqlupdatekodejdw="UPDATE `jadwal_asesmen` SET `kodejadwal_bnsp`='$kode_jadwal', `id_jadwalbnsp`='$id_jadwal' WHERE `id`='$_POST[id_jadwal]'";
			$conn->query($sqlupdatekodejdw);
			echo "<script>alert('Pembuatan Jadwal di Sistem BNSP : ".$pesannya." dengan kode : ".$kode_jadwal.", silahkan cek di laman Jadwal BNSP'); window.location='media.php?module=jadwalasesmen';</script>";
		}else{
			echo "<script>alert('Pembuatan Jadwal di Sistem BNSP : ".$pesannya.", silahkan cek di laman Jadwal BNSP'); window.location='media.php?module=jadwalasesmen';</script>";
		}
		//echo $pesannya."<br/>ID Jadwal : ".$id_jadwal;
		//echo "<br/>Kode Jadwal : ".$kode_jadwal;
		//header("location:media.php?module=jadwalasesmen");
	}
}
?>