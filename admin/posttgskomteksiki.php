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

	// Pencatatan Penugasan Komite Teknis
	$sqlasesituk="SELECT * FROM `komite` WHERE `aktif`='Y' ORDER BY `nama` ASC";
	$asesituk=$conn->query($sqlasesituk);
	$noastuk=1;
	$nama_komite_teknisx="";
	while ($astuk=$asesituk->fetch_assoc()){
		// cek apakah komite adalah asesor
		$sqlgetasesor="SELECT * FROM `jadwal_asesor` WHERE `id_jadwal`='$_GET[idj]' ORDER BY `id` ASC";
		$getasesor=$conn->query($sqlgetasesor);
		while($gasx=$getasesor->fetch_assoc()){
			$sqlceknamaasesor="SELECT * FROM `asesor` WHERE `id`='$gasx[id_asesor]'";
			$ceknamaasesor=$conn->query($sqlceknamaasesor);
			$asrx=$ceknamaasesor->fetch_assoc();
			if ($asrx['no_ktp']!=$astuk['no_ktp']){
				$makaasesor=0;
			}else{
				$makaasesor=1;
			}
		}
		if ($makaasesor==0){
			$sqlasesor="SELECT * FROM `komite` WHERE `id`='$astuk[id]'";
			$asesor=$conn->query($sqlasesor);
			$asr=$asesor->fetch_assoc();
			if (!empty($asr['gelar_depan'])){
				if (!empty($asr['gelar_blk'])){
					$namaasesor=$asr['gelar_depan']." ".$asr['nama'].", ".$asr['gelar_blk'];
				}else{
					$namaasesor=$asr['gelar_depan']." ".$asr['nama'];
				}
			}else{
				if (!empty($asr['gelar_blk'])){
					$namaasesor=$asr['nama'].", ".$asr['gelar_blk'];
				}else{
					$namaasesor=$asr['nama'];
				}
			}
			if ($noastuk==1 && $noastuk>0){
				$nama_komite_teknisx=$namaasesor;
				$jabatan_komite_teknisx=$astuk['jabatan_komite'];
			}else{
				$nama_komite_teknisx=$nama_komite_teknisx.",".$namaasesor;
				$jabatan_komite_teknisx=$jabatan_komite_teknisx.",".$astuk['jabatan_komite'];
			}
			$noastuk++;
		}
		
	}
	//echo $nama_komite_teknisx."<br>";
	//echo $jabatan_komite_teknisx."<br>";
	$nama_komite_teknis=$nama_komite_teknisx;
	$jabatan_komite_teknis=$jabatan_komite_teknisx;
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

	$hasil_penetapan=$rekomendasi;
	$catatanx=$catatan;
	$tgl_surat_tugas=$jdw['tgl_surattugaskomtek'];
	$no_surat_tugas=$jdw['no_surattugaskomtek'];
	$tglsuratkomite=date('Y-m-d', strtotime('+1 days', strtotime($jdw['tgl_asesmen'])));
	$tgl_penetapan=$tglsuratkomite;
	$url_surat_tugas='https://'.$iden['url_domain'].'/foto_surat/'.$jdw['file_surattugaskomtek'];
	$url_ba_penetapan='https://'.$iden['url_domain'].'/foto_surat/'.$jdw['file_bakomite'];


	/* echo $hasil_penetapan."<br>";
	echo $catatanx."<br>";
	echo $tgl_surat_tugas."<br>";
	echo $no_surat_tugas."<br>";
	echo $tglsuratkomite."<br>";
	echo $tgl_penetapan."<br>";
	echo $url_surat_tugas."<br>";
	echo $url_ba_penetapan."<br>---"; */

	$curlkomtek = curl_init();
	$jsonDatakomtek = array(
		'nama_komite_teknis' => $nama_komite_teknis,
		'jabatan_komite_teknis' => $jabatan_komite_teknis,
		'hasil_penetapan' => $hasil_penetapan,
		'catatan' => $catatanx,
		'tgl_surat_tugas' => $tgl_surat_tugas,
		'no_surat_tugas' => $no_surat_tugas,
		'tgl_penetapan' => $tgl_penetapan,
		'url_surat_tugas' => $url_surat_tugas,
		'url_ba_penetapan' => $url_ba_penetapan
	);
	$jsonDataEncodedakomtek = json_encode($jsonDatakomtek);
	$urlpencatatanskkpuprakomtek="https://siki.pu.go.id/siki-api/v1/komtek-lsp-penugasan/".$pnas['id_asesi'];
	curl_setopt_array($curlkomtek, array(
	  CURLOPT_URL => $urlpencatatanskkpuprakomtek,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'POST',
	  CURLOPT_POSTFIELDS => $jsonDataEncodedakomtek,
	  CURLOPT_HTTPHEADER => array(
		'Content-Type: application/json',
		'token: '.$tokennya
	  ),
	));
	$responselpjkkomtek = curl_exec($curlkomtek);
	curl_close($curlkomtek);
	//echo $responselpjkkomtek;
	$deca = json_decode($responselpjkkomtek, true);
	if ($deca["status"]=='errors'){
		echo "<script>alert('".$deca["message"]."'); window.location='media.php?module=pesertaasesmen&idj=$_GET[idj]';</script>";
	}else{
		echo "<script>alert('Penugasan Asesor dan Komite Teknis Berhasil Dikirim ke LPJK'); window.location='media.php?module=pesertaasesmen&idj=$_GET[idj]';</script>";
	}
	//================================================================================================================
}

?>