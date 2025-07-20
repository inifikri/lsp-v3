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

$noreg_asesor=$asr['no_induk'];
$urlgetasesor="https://konstruksi.bnsp.go.id/api/v1/asesor?no_reg=".$noreg_asesor."&klasifikasi=".$klasifikasi_id."&subklasifikasi=".$subklasifikasi_id;
$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL => $urlgetasesor,
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

/* if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
} */

$datanya=json_decode($response, true);
	 
$idasesornya=$datanya["data"]["id"];
			
$jadwal_id=$jdw['id_jadwalbnsp'];
$asesor_id=$idasesornya;

//$file_surattugas=$_POST['url_surattugas'];
/* echo "<br/>ID Jadwal : ".$jadwal_id."<br/>";
echo "<br/>ID Asesor : ".$asesor_id."<br/>";
echo "<br/>URL SPT Asesor : ".$file_surattugas."<br/>"; */

// konfimasi jadwal dulu =========================================

$datas0=array(
	'jadwal_id' => $jadwal_id
);
$payload0=json_encode($datas0, true);

$curl0 = curl_init();

curl_setopt_array($curl0, array(
  CURLOPT_URL => 'https://konstruksi.bnsp.go.id/api/v1/jadwal/cetak',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => $payload0,
  CURLOPT_HTTPHEADER => array(
		'Content-Type: application/json',
		'x-authorization: '.$thedata
  ),
));

$response50 = curl_exec($curl0);
$err0 = curl_error($curl0);

curl_close($curl0);

/* if ($err0) {
  echo "cURL Error #:" . $err0;
} else {
  echo "<br />".$response50;
} */

// =============================================================================

$datanya=json_decode($response50, true);
if ($datanya["code"]=="OK"){
	$pesannya=$datanya["message"];
	$pesantampil=$pesantampil.$pesannya;
}else{
	$pesannya=$datanya["message"];
}
//=============================================
echo "<script>alert('Laporan Cetak Blangko Sertifikat berdasarkan Jadwal di Sistem BNSP : ".$pesantampil.", silahkan cek di laman Jadwal BNSP'); window.location='media.php?module=jadwalasesmen';</script>";

?>