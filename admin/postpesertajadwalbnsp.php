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
		if (trim($datanya["data"][$i]["kode"])==trim($sq['kode_skema'])){
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
			if (trim($datanya3["data"][$i]["kode_lpjk"])==trim($sq['kode_skema'])){
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
	if ($pst['fresh_graduate']=='Y'){
		$urlnya="https://siki.pu.go.id/siki-api/v1/permohonan-skk-fg/".$pst['id_asesi'];
	}else{
		$urlnya="https://siki.pu.go.id/siki-api/v1/permohonan-skk/".$pst['id_asesi'];
	}
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
	 
	/*Initializing temp variable to design table dynamically*/
	/* $temp = "<table id='example2' class='table table-bordered table-striped'>"; */
	 
	/*Dynamically generating rows & columns*/
	for($i = 0; $i < sizeof($data["personal"]); $i++)
	{
		/* $temp .= "<tr><td>NIK</td><td>" . $data["personal"][$i]["nik"] . "</td><tr>";
		$temp .= "<tr><td>Nama Lengkap</td><td>" . $data["personal"][$i]["nama"] . "</td><tr>";
		$temp .= "<tr><td>Tempat Lahir</td><td>" . $data["personal"][$i]["tempat_lahir"] . "</td><tr>";
		$temp .= "<tr><td>Tanggal Lahir</td><td>" . $data["personal"][$i]["tanggal_lahir"] . "</td><tr>";
		$temp .= "<tr><td>Alamat Email</td><td>" . $data["personal"][$i]["email"] . "</td><tr>";
		$temp .= "<tr><td>Jenis Kelamin</td><td>" . $data["personal"][$i]["jenis_kelamin"] . "</td><tr>";
		$temp .= "<tr><td>Alamat</td><td>" . $data["personal"][$i]["alamat"] . "</td><tr>";
		$temp .= "<tr><td>Negara</td><td>" . $data["personal"][$i]["negara"] . "</td><tr>";
		$temp .= "<tr><td>Provinsi</td><td>" . $data["personal"][$i]["propinsi"] . "</td><tr>";
		$temp .= "<tr><td>Kabupaten/Kota</td><td>" . $data["personal"][$i]["kabupaten"] . "</td><tr>";
		$temp .= "<tr><td>Kodepos</td><td>" . $data["personal"][$i]["kodepos"] . "</td><tr>";
		$temp .= "<tr><td>KTP</td><td><embed src='" . $data["personal"][$i]["ktp"] . "' width='100%' height='400px'/></td><tr>";
		$temp .= "<tr><td>Surat Pernyataan Kebenaran Data</td><td><embed src='" . $data["personal"][$i]["surat_pernyataan_kebenaran_data"] . "' width='100%' height='400px'/></td><tr>";
		$temp .= "<tr><td>File NPWP</td><td><embed src='" . $data["personal"][$i]["file_npwp"] . "' width='100%' height='400px'/></td><tr>";
		$temp .= "<tr><td>Pas Foto</td><td><img src='" . $data["personal"][$i]["pas_foto"] . "' width='100px'/></td><tr>";
		$temp .= "<tr><td>Pendidikan</td><td>";  */

		$nik=$data["personal"][$i]["nik"];
		$nama=$data["personal"][$i]["nama"];
		$tempat_lahir=$data["personal"][$i]["tempat_lahir"];
		$tanggal_lahir=$data["personal"][$i]["tanggal_lahir"];
		$jenis_kelamin=$data["personal"][$i]["jenis_kelamin"];
		$alamat=$data["personal"][$i]["alamat"];
		// mapping kode untuk Kota Pangandaran penyesuaian perbedaan kode di LPJK dan BNSP
		if ($data["personal"][$i]["kabupaten"]=="3218"){
			$kota_id="3299";
		}else{
			$kota_id=$data["personal"][$i]["kabupaten"];
		}
		$provinsi_id=$data["personal"][$i]["propinsi"];
		$negara_id=$data["personal"][$i]["negara"];
		$telepon=$data["personal"][$i]["telepon"];
		$email=$data["personal"][$i]["email"];
		$file_foto=$data["personal"][$i]["pas_foto"];
		$file_ktp=$data["personal"][$i]["ktp"];
	
		for($j = 0; $j < sizeof($data["pendidikan"]); $j++)
		{
			/* $temp .= "<label>ID</label> : " . $data["pendidikan"][$j]["id"] . "<br>";
			$temp .= "<label>Updated</label> : " . $data["pendidikan"][$j]["updated"] . "<br>";
			$temp .= "<label>Created</label> : " . $data["pendidikan"][$j]["created"] . "<br>";
			$temp .= "<label>Creator</label> : " . $data["pendidikan"][$j]["creator"] . "<br>";
			$temp .= "<label>Data ID</label> : " . $data["pendidikan"][$j]["data_id"] . "<br>";
			$temp .= "<label>Nama Sekolah/ Perguruan Tinggi</label> : " . $data["pendidikan"][$j]["nama_sekolah_perguruan_tinggi"] . "<br>";
			$temp .= "<label>Program Studi</label> : " . $data["pendidikan"][$j]["program_studi"] . "<br>";
			$temp .= "<label>No. Ijazah</label> : " . $data["pendidikan"][$j]["no_ijazah"] . "<br>";
			$temp .= "<label>Tahun Lulus</label> : " . $data["pendidikan"][$j]["tahun_lulus"] . "<br>";
			$temp .= "<label>Jenjang</label> : " . $data["pendidikan"][$j]["jenjang"] . "<br>";
			$temp .= "<label>Alamat Sekolah/ PT</label> : " . $data["pendidikan"][$j]["alamat"] . "<br>";
			$temp .= "<label>Negara</label> : " . $data["pendidikan"][$j]["negara"] . "<br>";
			$temp .= "<label>Provinsi</label> : " . $data["pendidikan"][$j]["propinsi"] . "<br>";
			$temp .= "<label>Kabupaten/Kota</label> : " . $data["pendidikan"][$j]["kabupaten"] . "<br>";
			$temp .= "<label>Scan Ijazah/ Legalisir Ijazah</label> : <embed src='" . $data["pendidikan"][$j]["scan_ijazah_legalisir"] . "' width='100%' height='400px'/><br>";
			$temp .= "<label>Scan Surat Keterangan</label> : <embed src='" . $data["pendidikan"][$j]["scan_surat_keterangan"] . "' width='100%' height='400px'/><br>"; */
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
			/* $temp .= "<label>ID</label> : " . $data["klasifikasi_kualifikasi"][$o]["id"] . "<br>";
			$temp .= "<label>Updated</label> : " . $data["klasifikasi_kualifikasi"][$o]["updated"] . "<br>";
			$temp .= "<label>Created</label> : " . $data["klasifikasi_kualifikasi"][$o]["created"] . "<br>";
			$temp .= "<label>Creator</label> : " . $data["klasifikasi_kualifikasi"][$o]["creator"] . "<br>";
			$temp .= "<label>Data ID</label> : " . $data["klasifikasi_kualifikasi"][$o]["data_id"] . "<br>";
			$temp .= "<label>LSP</label> : " . $data["klasifikasi_kualifikasi"][$o]["lsp"] . "<br>";
			$temp .= "<label>Sub Klasifikasi</label> : " . $data["klasifikasi_kualifikasi"][$o]["subklasifikasi"] . "<br>";
			$temp .= "<label>Kualifikasi</label> : " . $data["klasifikasi_kualifikasi"][$o]["kualifikasi"] . "<br>";
			$temp .= "<label>Jabatan Kerja</label> : " . $data["klasifikasi_kualifikasi"][$o]["jabatan_kerja"] . "<br>";
			$temp .= "<label>Jenjang</label> : " . $data["klasifikasi_kualifikasi"][$o]["jenjang"] . "<br>";
			$temp .= "<label>Asosiasi</label> : " . $data["klasifikasi_kualifikasi"][$o]["asosiasi"] . "<br>";
			$temp .= "<label>KTA Asosiasi</label> : " . $data["klasifikasi_kualifikasi"][$o]["kta"] . "<br>";
			$temp .= "<label>Tempat Uji Kompetensi</label> : " . $data["klasifikasi_kualifikasi"][$o]["tuk"] . "<br>";
			$temp .= "<label>Jenis Permohonan</label> : " . $data["klasifikasi_kualifikasi"][$o]["jenis_permohonan"] . "<br>";
			$temp .= "<label>Berita Acara Verifikasi</label> : " . $data["klasifikasi_kualifikasi"][$o]["berita_acara_vv"] . "<br>";
			$temp .= "<label>Surat Permohonan</label> : <embed src='" . $data["klasifikasi_kualifikasi"][$o]["surat_permohonan"] . "' width='100%' height='400px'/><br>";
			$temp .= "<label>Surat Pengantar Permohonan dari Asosiasi</label> : <embed src='" . $data["klasifikasi_kualifikasi"][$o]["surat_pengantar_pemohonan_asosiasi"] . "' width='100%' height='400px'/><br>";
			$temp .= "<label>Asesmen Mandiri APL-02</label> : " . $data["klasifikasi_kualifikasi"][$o]["self_asesmen_apl"] . "<br>";
			$temp .= "<label>Klasifikasi</label> : " . $data["klasifikasi_kualifikasi"][$o]["klasifikasi"] . "<br>"; */
			$jenjang_idx=$data["klasifikasi_kualifikasi"][$o]["jenjang"];
		}
	}
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
		 
	/*Dynamically generating rows & columns*/
	/* for($i = 0; $i < sizeof($datanya["data"]); $i++)
	{
		$temp .= "<tr>";
		$temp .= "<td>" . $datanya["data"][$i]["id"] . "</td>";
		$temp .= "<td>" . $datanya["data"][$i]["id_klas"] . "</td>";
		$temp .= "<td>" . $datanya["data"][$i]["klas"] . "</td>";
		$temp .= "<td>";
		$temp .= "<a href='?module=bnspsubklasifikasi&idklas=";
		$temp .= $datanya["data"][$i]["id_klas"];
		$temp .= "' class='btn btn-primary btn-xs'>Detail</a>";
		$temp .= "</td>";
		$temp .= "</tr>";
	} */
	$idasesornya=$datanya["data"]["id"];
				
	$jadwal_id=$jdw['id_jadwalbnsp'];
	$tuk_id=$tt['id_tuk_bnsp'];
	$asesor_id=$idasesornya;
	$nib="";
	$tanggal_ijazah=$as['tgl_ijazah'];
	$tujuansertifikasi=$pst['tujuan_sertifikasi'];
	switch($tujuansertifikasi){
		case "Sertifikasi":
			// status BNSP baru
			$jenispermohonan="1";
		break;
		case "Sertifikasi Ulang":
			// status BNSP perpanjangan/sertifikasi ulang
			$jenispermohonan="2";
		break;
		case "Pengakuan Kompetensi Terkini (PKT)":
			// status BNSP baru
			$jenispermohonan="1";
		break;
		case "Rekognisi Pembelajaran Lampau":
			// status BNSP baru
			$jenispermohonan="1";
		break;
		default:
			// status BNSP perubahan
			$jenispermohonan="3";
		break;
	}
	$jenis_mohon=$jenispermohonan;
	$skema_id=$jabkerint;
	$keterangan="";
	$pekerjaan=$as['pekerjaan'];
	$instansi_pekerjaan=$as['nama_kantor'];
	$jabatan_pekerjaan=$as['jabatan'];
	$file_nib="";
	if ($jenis_kelamin=="Pria"){
		$jenis_kelaminx="1";
	}else{
		$jenis_kelaminx="2";
	}
	if ($negara_id=="ID"){
		$negara_idx="1";
	}else{
		$negara_idx="2";
	}
	if ($negara_sekolah=="ID"){
		$negara_sekolahx="1";
	}else{
		$negara_sekolahx="2";
	}
	/* $sqlgetpendidiknbnsp="SELECT * FROM `pendidikan` WHERE `kodependidikan_pupr`='$jenjang_id'";
	$getpendidikanbnsp=$conn->query($sqlgetpendidiknbnsp);
	$pddbnsp=$getpendidikanbnsp->fetch_assoc();
	$jenjang_idx=$pddbnsp['kodependidikan_bnsp']; */
	//echo "<br/>ID Asesor : ".$asesor_id."<br/>";
	$datas=array(
		'jadwal_id' => $jadwal_id,
		'tuk_id' => $tuk_id,
		'asesor_id' => $asesor_id,
		'nik' => $nik,
		'nib' => $nib,
		'nama' => $nama,
		'tempat_lahir' => $tempat_lahir,
		'tanggal_lahir' => $tanggal_lahir,
		'jenis_kelamin' => $jenis_kelaminx,
		'alamat' => $alamat,
		'kota_id' => $kota_id,
		'provinsi_id' => $provinsi_id,
		'negara_id' => $negara_idx,
		'telepon' => $telepon,
		'email' => $email,
		'jenis_mohon' => $jenis_mohon,
		'skema_id' => $skema_id,
		'keterangan' => $keterangan,
		'jenjang_id' => $jenjang_idx,
		'prodi' => $prodi,
		'no_ijasah' => $no_ijasah,
		'tanggal_ijazah' => $tanggal_ijazah,
		'tahun_lulus' => $tahun_lulus,
		'kota_sekolah' => $kota_sekolah,
		'prov_sekolah' => $prov_sekolah,
		'negara_sekolah' => $negara_sekolahx,
		'nama_sekolah' => $nama_sekolah,
		'pekerjaan' => $pekerjaan,
		'instansi_pekerjaan' => $instansi_pekerjaan,
		'jabatan_pekerjaan' => $jabatan_pekerjaan,
		'file_foto' => $file_foto,
		'file_ktp' => $file_ktp,
		'file_nib' => $file_nib,
		'file_ijazah' => $file_ijazah
	);
	$payload=json_encode($datas, true);

	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => 'https://konstruksi.bnsp.go.id/api/v1/jadwal/peserta',
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

	/* if ($err) {
	  echo "cURL Error #:" . $err;
	} else {
	  echo "<br />".$response5;
	} */
	$datanya=json_decode($response5, true);
	$pesannya=$datanya["message"].$datanya["data"]["id_peserta"]."<br/>";
	$pesantampil=$pesantampil.$pesannya;
	//=============================================
}
echo "<script>alert('Penambahan Peserta pada Jadwal di Sistem BNSP : ".$pesantampil.", silahkan cek di laman Jadwal BNSP'); window.location='media.php?module=jadwalasesmen';</script>";

?>