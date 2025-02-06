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
$sqljadwaltuk="SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
$jadwaltuk=$conn->query($sqljadwaltuk);
$jdw=$jadwaltuk->fetch_assoc();
$sqlskema="SELECT * FROM `skema_kkni` WHERE `id`='$jdw[id_skemakkni]'";
$skema=$conn->query($sqlskema);
$sq=$skema->fetch_assoc();
$sqltuk="SELECT * FROM `tuk` WHERE `id`='$jdw[tempat_asesmen]'";
$tuk=$conn->query($sqltuk);
$tt=$tuk->fetch_assoc();
// Ambil data Portal PUPR
$sqlgetatoken="SELECT * FROM `user_pupr` WHERE `token` IS NOT NULL ORDER BY `id` ASC LIMIT 1";
$gettoken=$conn->query($sqlgetatoken);
$tokenx=$gettoken->fetch_assoc();
$tokennya=$tokenx['token'];
$sqlgetasesipeserta="SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`='$jdw[id]' AND `id_asesi`='$_GET[ida]'";
$getasesipeserta=$conn->query($sqlgetasesipeserta);
while ($pnas=$getasesipeserta->fetch_assoc()){
	$getasesor=$conn->query("SELECT * FROM `jadwal_asesor` WHERE `id_jadwal`='$_GET[idj]' ORDER BY `id` DESC LIMIT 1");
	$gas=$getasesor->fetch_assoc();
	$sqlasesor="SELECT * FROM `asesor` WHERE `id`='$gas[id_asesor]'";
	$asesor=$conn->query($sqlasesor);
	$asr=$asesor->fetch_assoc();
	$pesantampil="";
	// Penugasan Asesor SKK
	// mendapatkan asesor penguji
	$sqlgetasesorpenguji="SELECT * FROM `jadwal_asesor` WHERE `id_jadwal`='$_GET[idj]' ORDER BY `id` ASC";
	$getasesorpenguji=$conn->query($sqlgetasesorpenguji);
	$idasesorbertugas="";
	while($aspj=$getasesorpenguji->fetch_assoc()){
		//mendapatkan MET Asesor
		$sqlgetmetasesor="SELECT * FROM `asesor` WHERE `id`='$aspj[id_asesor]'";
		$getmetasesor=$conn->query($sqlgetmetasesor);
		$aspn=$getmetasesor->fetch_assoc();
		$jumasrtgs=$getmetasesor->num_rows;
		$METasesor=$aspn['no_induk'];
		if ($jumasrtgs>1){
			if ($idasesorbertugas==""){
				$idasesorbertugas=$METasesor;
			}else{
				$idasesorbertugas=$idasesorbertugas.",".$METasesor;
			}
		}else{
			$idasesorbertugas=$METasesor;
		}
	}
	$id_asesor=$idasesorbertugas;
	// get rekomendasi
	$sqlgethasilasesmen="SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$pnas[id_asesi]' AND `id_jadwal`='$_GET[idj]'";
	$gethasilasesmen=$conn->query($sqlgethasilasesmen);
	$hslasm=$gethasilasesmen->fetch_assoc();
	$rekomendasi=$hslasm['status_asesmen'];
	if ($rekomendasi=='BK'){
		$catatan=$hslasm['umpan_balik'];
	}else{
		$catatan='';
	}
	$tgl_surat_tugas=$jdw['tgl_asesmen'];
	$no_surat_tugas=$jdw['no_surattugas'];
	// get data TUK dari master TUK SIKI
	$sqlgetkodetuk="SELECT * FROM `tuk` WHERE `id`='$jdw[tempat_asesmen]'";
	$getkodetuk=$conn->query($sqlgetkodetuk);
	$gtukx=$getkodetuk->fetch_assoc();
	//===============================================================================
	$curl = curl_init();
	$urlnya="https://siki.pu.go.id/siki-api/v2/tuk";
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
	$response = curl_exec($curl);
	curl_close($curl);
	//echo $response;
	/*Fetching JSON file content using php file_get_contents method*/
	$data = json_decode($response, true);
	/*Initializing temp variable to design table dynamically*/
	//$temp = "<div style='overflow-x:auto;'><table id='example2' class='table table-bordered table-striped'>";
	//$temp .= "<thead><tr><th>NO.</th><th>ID TUK</th><th>ID LSP</th><th>KODE TUK</th><th>JENIS TUK</th><th>NAMA</th><th>ALAMAT</th><tr></thead><tbody>";
	/*Dynamically generating rows & columns*/
	//$nourut=1;
	for($i = 0; $i < sizeof($data["data"]); $i++)
	{
		//echo $data["data"][$i]["id_lsp"]."---";
		//echo $iden['id_lsppupr']."<br/>";
		if ($data["data"][$i]["id_lsp"]==$iden['id_lsppupr']){
			//echo $data["data"][$i]["kode_tuk"]."---";
			//echo $gtukx['kode_tuk']."<br>";
			if (trim($data["data"][$i]["kode_tuk"])==trim($gtukx['kode_tuk'])){
				//echo $data["data"][$i]["id"];
				$kode_tukx=$data["data"][$i]["kode_tuk"];
				$nama_tukx=$data["data"][$i]["nama_tuk"];
			}
		}
		//$nourut++;
	}
	$kode_tuk=$kode_tukx;
	$nama_tuk=$nama_tukx;
	// get tanggal asesmen
	$sqljadwaltukx="SELECT * FROM `jadwal_asesmen` WHERE `id`='$hslasm[id_jadwal]'";
	$jadwaltukx=$conn->query($sqljadwaltukx);
	$jdwx=$jadwaltukx->fetch_assoc();
	$tgl_uji=$hslasm['tgl_asesmen'];
	$tgl_selesai_uji=$jdwx['tgl_asesmen_akhir'];
	//=====================================================================================================================
	// get metode uji dari AK 01
	$sqlgetmetodeuji="SELECT * FROM `asesmen_ak01` WHERE `id_asesi`='$pnas[id_asesi]' AND `id_jadwal`='$_GET[idj]'";
	$getmetodeuji=$conn->query($sqlgetmetodeuji);
	$gmuj=$getmetodeuji->fetch_assoc();
	if ($gmuj['VP']=="1"){
		$metodeujinya="portofolio";
		if($gmuj['DPT']=="1"){
			$uji_tulis=1;
		}else{
			$uji_tulis=0;
		}
		if($gmuj['CL']=="1"){
			$uji_praktek_atau_observasi_lapangan=1;
		}else{
			$uji_praktek_atau_observasi_lapangan=0;
		}
		if($gmuj['DPL']=="1"){
			$uji_lisan=1;
		}else{
			$uji_lisan=0;
		}
		$wawancara=1;
		
	}else{
		$metodeujinya="observasi";
		$uji_praktek_atau_observasi_lapangan=1;
		$uji_tulis=1;
		$uji_lisan=1;
		$wawancara=0;
	}
	$metode_uji=$metodeujinya;
	// get pelaksanaan uji dari jadwal asesmen
	$penyelenggaraan_uji=$jdw['pelaksanaan_uji'];
	// get URL surat tugas asesor
	$url_surat_tugas='https://'.$iden['url_domain'].'/foto_surat/'.$jdw['file_surattugas'];
	// get URL surat keputusan asesmen
	$url_surat_rekomendasi_akhir='https://'.$iden['url_domain'].'/foto_surat/'.$jdw['file_skkeputusan'];;
	$curlxa = curl_init();
	$jsonDataa = array(
		'id_asesor' => $id_asesor,
		'rekomendasi' => $rekomendasi,
		'catatan' => $catatan,
		'tgl_surat_tugas' => $tgl_surat_tugas,
		'no_surat_tugas' => $no_surat_tugas,
		'kode_tuk' => $kode_tuk,
		'nama_tuk' => $nama_tuk,
		'tgl_uji' => $tgl_uji,
		'tgl_selesai_uji' => $tgl_selesai_uji,
		'metode_uji' => $metode_uji,
		'uji_praktek_atau_observasi_lapangan' => $uji_praktek_atau_observasi_lapangan,
		'uji_tulis' => $uji_tulis,
		'uji_lisan' => $uji_lisan,
		'wawancara' => $wawancara,
		'penyelenggaraan_uji' => $penyelenggaraan_uji,
		'url_surat_tugas' => $url_surat_tugas,
		'url_surat_rekomendasi_akhir' => $url_surat_rekomendasi_akhir
	);
	$jsonDataEncodeda = json_encode($jsonDataa);
	$urlpencatatanskkpupra="https://siki.pu.go.id/siki-api/v2/asesor-lsp-penugasan/".$pnas['id_asesi'];
	curl_setopt_array($curlxa, array(
	  CURLOPT_URL => $urlpencatatanskkpupra,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'POST',
	  CURLOPT_POSTFIELDS => $jsonDataEncodeda,
	  CURLOPT_HTTPHEADER => array(
		'Content-Type: application/json',
		'token: '.$tokennya
	  ),
	));
	$responselpjka = curl_exec($curlxa);
	curl_close($curlxa);
	//echo $responselpjka;
	$deca = json_decode($responselpjka, true);
	//if ($deca["status"]=='errors'){
	//	echo "<script>alert('".$deca["message"]."'); window.location='media.php?module=pesertaasesmen&idj=$_GET[idj]';</script>";
	//}else{
		echo "<script>alert('".$responselpjka." Penugasan Asesor Berhasil Dikirim ke LPJK'); window.location='media.php?module=pesertaasesmen&idj=$_GET[idj]';</script>";
	//}
	//echo $jsonDataEncodeda;
	//echo $responselpjka;
	//================================================================================================================
}
?>