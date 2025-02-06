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
	$jadwal_id=$jdw['id_jadwalbnsp'];

	// get nomor permohonan blangko BNSP
	$curl2 = curl_init();

	curl_setopt_array($curl2, [
	  CURLOPT_URL => "https://konstruksi.bnsp.go.id/api/v1/jadwal/blanko/nomor-permohonan?jadwal_id=".$jadwal_id,
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

	$response2 = curl_exec($curl2);
	$err2 = curl_error($curl2);

	curl_close($curl2);

	/* if ($err2) {
	  echo "cURL Error #:" . $err2;
	} else {
	  echo $response2;
	} */
	
	$datanya2=json_decode($response2, true);
	 
	/*Dynamically generating rows & columns*/
	$nomorpermohonan=$datanya2["data"];
				

	$sqlgetnosurat="SELECT * FROM `surat_item` WHERE `no_surat`='$jdw[no_permohonanblangko]' ORDER BY `tgl_surat` DESC LIMIT 1";
	$getnosurat=$conn->query($sqlgetnosurat);
	$gns=$getnosurat->fetch_assoc();
	//$nomorpermohonan=$jdw['no_permohonanblangko'];
	$tanggalpermohonan=$gns['tgl_surat'];
	$file_permohonan="https://".$urldomain."/foto_surat/".$jdw['file_permohonanblangko'];
	$sqlgetnosurat2="SELECT * FROM `surat_item` WHERE `no_surat`='$jdw[no_permohonanblangko]' ORDER BY `tgl_surat` DESC LIMIT 1";
	$getnosurat2=$conn->query($sqlgetnosurat2);
	$gns2=$getnosurat2->fetch_assoc();
	$nomorberitaacara=$jdw['no_bakomite'];
	$tanggalberitaacara=$gns['tgl_surat'];
	$file_beritaacara="https://".$urldomain."/foto_surat/".$jdw['file_bakomite'];
	$sqlgetnosurat3="SELECT * FROM `surat_item` WHERE `no_surat`='$jdw[no_permohonanblangko]' ORDER BY `tgl_surat` DESC LIMIT 1";
	$getnosurat3=$conn->query($sqlgetnosurat3);
	$gns3=$getnosurat3->fetch_assoc();
	$nomorsk=$jdw['no_skkeputusan'];
	$tanggalsk=$gns3['tgl_surat'];
	$file_sk="https://".$urldomain."/foto_surat/".$jdw['file_skkeputusan'];
	
	$datas=array(
		'jadwal_id' => $jadwal_id,
		'form_id' => 1,
		'nama' => 'Nomor Permohonan',
		'nomor' => $nomorpermohonan,
		'tanggal' => $tanggalpermohonan,
		'file_dokumen' => $file_permohonan
	);
	$payload=json_encode($datas, true);
	$datas2=array(
		'jadwal_id' => $jadwal_id,
		'form_id' => 2,
		'nama' => 'Berita Acara Pleno Komite Teknis',
		'nomor' => $nomorberitaacara,
		'tanggal' => $tanggalberitaacara,
		'file_dokumen' => $file_beritaacara
	);
	$payload2=json_encode($datas2, true);
	$datas3=array(
		'jadwal_id' => $jadwal_id,
		'form_id' => 3,
		'nama' => 'SK Hasil Sertifikasi Kompetensi',
		'nomor' => $nomorsk,
		'tanggal' => $tanggalsk,
		'file_dokumen' => $file_sk
	);
	$payload3=json_encode($datas3, true);
	$payloadsss="[$payload,$payload2,$payload3]";
	//echo $payloadsss;
	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => 'https://konstruksi.bnsp.go.id/api/v1/jadwal/blanko',
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'POST',
	  CURLOPT_POSTFIELDS => $payloadsss,
	  CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json',
			'x-authorization: '.$thedata
	  ),
	));

	$response5 = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	/* if ($err) {
	  echo "cURL Error #:" . $err;
	} else {
	  echo "<br />".$response5;
	} */
	$datanya=json_decode($response5, true);
	if ($datanya["code"]=="OK"){
		$pesannya=$datanya["message"];
	}else{
		$pesannya=$datanya["message"];
	}

	//=============================================
	
echo "<script>alert('".$response2." ".$response5." Permohonan Blangko di Sistem BNSP : ".$pesannya.", silahkan cek di laman Jadwal BNSP'); window.location='media.php?module=jadwalasesmen';</script>";

?>