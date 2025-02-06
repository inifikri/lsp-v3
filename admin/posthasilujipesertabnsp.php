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

$sqljadwaltuk="SELECT * FROM `jadwal_asesmen` WHERE `id`='$_POST[id_jadwal]'";
$jadwaltuk=$conn->query($sqljadwaltuk);
$jdw=$jadwaltuk->fetch_assoc();
$sqlskema="SELECT * FROM `skema_kkni` WHERE `id`='$jdw[id_skemakkni]'";
$skema=$conn->query($sqlskema);
$sq=$skema->fetch_assoc();
$sqltuk="SELECT * FROM `tuk` WHERE `id`='$jdw[tempat_asesmen]'";
$tuk=$conn->query($sqltuk);
$tt=$tuk->fetch_assoc();
$sqlgetasesipeserta="SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`='$jdw[id]'";
$getasesipeserta=$conn->query($sqlgetasesipeserta);

$getasesor=$conn->query("SELECT * FROM `jadwal_asesor` WHERE `id_jadwal`='$_POST[id_jadwal]' ORDER BY `id` DESC LIMIT 1");
$gas=$getasesor->fetch_assoc();
$sqlasesor="SELECT * FROM `asesor` WHERE `id`='$gas[id_asesor]'";
$asesor=$conn->query($sqlasesor);
$asr=$asesor->fetch_assoc();
$pesantampil="";
while ($pst=$getasesipeserta->fetch_assoc()){
	$sqlgetasesi="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$pst[id_asesi]'";
	$getasesi=$conn->query($sqlgetasesi);
	$as=$getasesi->fetch_assoc();
	// authorisasi sistem BNSP

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
		// get skema LSP

		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://konstruksi.bnsp.go.id/api/v1/skema/all?klasifikasi_id=".$klasifikasi_id."&subklasifikasi_id=".$subklasifikasi_id,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json',
			'Content-Length: 0',
			'Connection: keep-alive',
			'Accept-Encoding: gzip, deflate, br',
			'User-Agent: PostmanRuntime/7.29.0',
			'x-authorization: '.$thedata
		  ),
		));

		$response3 = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);
		//echo "<br />".$response3;
		/* if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		} */
		$datanya3=json_decode($response3, true);
		 
		/*Dynamically generating rows & columns*/
		for($i = 0; $i < sizeof($datanya3["data"]); $i++)
		{
			if ($datanya3["data"][$i]["kode_lpjk"]==$sq['kode_skema']){
				$jabker_id=$datanya3["data"][$i]["id"];
			}
		}
		
		$jabkerint=(int)$jabker_id;
		$tuk_id=(int)$tt['id_tuk_bnsp'];

	}
	// create peserta dalam jadwal ================
	$sqlgetatoken="SELECT * FROM `user_pupr` WHERE `token` IS NOT NULL ORDER BY `id` ASC LIMIT 1";
	$gettoken=$conn->query($sqlgetatoken);
	$tokenx=$gettoken->fetch_assoc();
	$tokennya=$tokenx['token'];
	//===============================================================================
	// Login PUPR
	$curl = curl_init();
	$urlnya="https://siki.pu.go.id/siki-api/v1/permohonan-skk/".$pst['id_asesi'];
	curl_setopt_array($curl, array(
	  CURLOPT_URL => $urlnya,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'GET',
	  CURLOPT_HTTPHEADER => array(
		'Content-Type: application/json',
		'token: '.$tokennya
	  ),
	));

	$response4 = curl_exec($curl);

	curl_close($curl);
	//echo "<br />".$response4;
	 
	/*Fetching JSON file content using php file_get_contents method*/
	$data = json_decode($response4, true);
	 
	 
	/*Dynamically generating rows & columns*/
	for($i = 0; $i < sizeof($data["personal"]); $i++)
	{
		$nik=$data["personal"][$i]["nik"];
		$nama=$data["personal"][$i]["nama"];
		$tempat_lahir=$data["personal"][$i]["tempat_lahir"];
		$tanggal_lahir=$data["personal"][$i]["tanggal_lahir"];
		$jenis_kelamin=$data["personal"][$i]["jenis_kelamin"];
		$alamat=$data["personal"][$i]["alamat"];
		$kota_id=$data["personal"][$i]["kabupaten"];
		$provinsi_id=$data["personal"][$i]["propinsi"];
		$negara_id=$data["personal"][$i]["negara"];
		$telepon=$data["personal"][$i]["telepon"];
		$email=$data["personal"][$i]["email"];
		$file_foto=$data["personal"][$i]["pas_foto"];
		$file_ktp=$data["personal"][$i]["ktp"];
	
		for($j = 0; $j < sizeof($data["pendidikan"]); $j++)
		{
			$jenjang_id=$data["pendidikan"][$j]["jenjang"];
			$prodi=$data["pendidikan"][$j]["program_studi"];
			$no_ijasah=$data["pendidikan"][$j]["no_ijazah"];
			$tahun_lulus=$data["pendidikan"][$j]["tahun_lulus"];
			$kota_sekolah=$data["pendidikan"][$j]["kabupaten"];
			$prov_sekolah=$data["pendidikan"][$j]["propinsi"];
			$negara_sekolah=$data["pendidikan"][$j]["negara"];
			$nama_sekolah=$data["pendidikan"][$j]["nama_sekolah_perguruan_tinggi"];
			$file_ijazah=$data["pendidikan"][$j]["scan_ijazah_legalisir"];

		}
		for($o = 0; $o < sizeof($data["klasifikasi_kualifikasi"]); $o++)
		{
			$jenjang_idx=$data["klasifikasi_kualifikasi"][$o]["jenjang"];
		}
	}
					
	$jadwal_id=$jdw['id_jadwalbnsp'];
	
	switch ($pst['status_asesmen']){
		case "K":
			$kompeten="1";
		break;
		default:
			$kompeten="2";
		break;
	}
	$datas=array(
		'jadwal_id' => $jadwal_id,
		'nik_peserta' => $nik,
		'kompeten' => $kompeten
	);
	$payload=json_encode($datas, true);

	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => 'https://konstruksi.bnsp.go.id/api/v1/jadwal/peserta/hasil-uji',
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'POST',
	  CURLOPT_POSTFIELDS => $payload,
	  CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json',
			'x-authorization: '.$thedata
	  ),
	));

	$response5 = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
	  echo "cURL Error #:" . $err;
	} else {
	  echo "<br />".$response5;
	}
	$datanya=json_decode($response5, true);
	if ($datanya["code"]=="OK"){
		$pesannya=$datanya["message"].$datanya["data"]["id_peserta"]."<br/>";
		$pesantampil=$pesantampil.$pesannya;
	}else{
		$pesannya=$datanya["message"];
	}
	//=============================================
}
echo "<script>alert('Penambahan Hasil Uji Peserta pada Jadwal di Sistem BNSP : ".$pesantampil.", silahkan cek di laman Jadwal BNSP'); window.location='media.php?module=jadwalasesmen';</script>";

?>