<?php
//ini_set('display_errors',1); 
//error_reporting(E_ALL);
ob_start();
include 'fpdf-easytable-master/fpdf.php';
include 'fpdf-easytable-master/exfpdf.php';
include 'fpdf-easytable-master/easyTable.php';
include "../config/koneksi.php";
include "../config/library.php";
include "../config/fungsi_indotgl.php";
$sqlidentitas="SELECT * FROM `identitas`";
$identitas=$conn->query($sqlidentitas);
$iden=$identitas->fetch_assoc();
$sqlgetasesi="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_GET[ida]'";
$getasesi=$conn->query($sqlgetasesi);
$as=$getasesi->fetch_assoc();
$sqljadwal="SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
$jadwal=$conn->query($sqljadwal);
$jdq=$jadwal->fetch_assoc();
$tambah=date('Y-m-d', strtotime('+6 days', strtotime($jdq['tgl_asesmen'])));
//$ttdsertifikatnya=date('Y-m-d H:i:s', strtotime('+6 days', strtotime($jdq['tgl_asesmen'])));
$ttdsertifikatnya=date('Y-m-d H:i:s');
$hariinitanggal=date('Y-m-d');
if ($tambah>$hariinitanggal){
	$tambahx=$hariinitanggal;
}else{
	$tambahx=$hariinitanggal;
}
$tgl_cetak = tgl_indo($hariinitanggal);
$sqllsp="SELECT * FROM `lsp`";
$lsp=$conn->query($sqllsp);
$lq=$lsp->fetch_assoc();
$namalsp0=str_replace("LSP ","",$lq['nama']);
$namalsp=str_replace("Lembaga Sertifikasi Profesi ","",$namalsp0);
$sqlskema="SELECT * FROM `skema_kkni` WHERE `id`='$jdq[id_skemakkni]'";
$skema=$conn->query($sqlskema);
$sq=$skema->fetch_assoc();
$skemakkni=$sq['judul'];
$skemakknieng=$sq['judul_eng'];
$sqlwil1="SELECT * FROM `data_wilayah` WHERE `id_wil`='$lq[id_wilayah]'";
$wilayah1=$conn->query($sqlwil1);
$wil1=$wilayah1->fetch_assoc();
$sqlwil2="SELECT * FROM `data_wilayah` WHERE `id_wil`='$wil1[id_induk_wilayah]'";
$wilayah2=$conn->query($sqlwil2);
$wil2=$wilayah2->fetch_assoc();
$sqlwil3="SELECT * FROM `data_wilayah` WHERE `id_wil`='$wil2[id_induk_wilayah]'";
$wilayah3=$conn->query($sqlwil3);
$wil3=$wilayah3->fetch_assoc();
$pdf=new exFPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',10);
$pdf->AddFont('FontUTF8','','Arimo-Regular.php'); 
$pdf->AddFont('FontUTF8','B','Arimo-Bold.php');
$pdf->AddFont('FontUTF8','I','Arimo-Italic.php');
$pdf->AddFont('FontUTF8','BI','Arimo-BoldItalic.php');
$bgsertifikat="e-sertifikat-blangko-front.jpg";
$pdf->Image($bgsertifikat,0,0,215,297.5);
$namakota0=trim($wil2['nm_wil']);
$namakota=ucwords(strtolower($namakota0));
switch ($namakota){
	default:
		$namakotax=$namakota;
	break;
	case "Kota Adm. Jakarta Utara":
		$namakotax="Jakarta";
	break;
	case "Kota Adm. Jakarta Selatan":
		$namakotax="Jakarta";
	break;
	case "Kota Adm. Jakarta Timur":
		$namakotax="Jakarta";
	break;
	case "Kota Adm. Jakarta Barat":
		$namakotax="Jakarta";
	break;
	case "Kota Adm. Jakarta Pusat":
		$namakotax="Jakarta";
	break;
}
$tglcetakkota = ucwords(strtolower($namakotax)).", ".$tgl_cetak;
$tglLulusEng0=date_create($tambahx);
$tglLulusEng=date_format($tglLulusEng0,"jS F Y");
$tglcetakkota2 = ucwords(strtolower($namakotax)).", ".$tglLulusEng;
$tanggal = $jdq['tgl_asesmen'];
$day = date('D', strtotime($tanggal));
$dayList = array(
	'Sun' => 'Minggu',
	'Mon' => 'Senin',
	'Tue' => 'Selasa',
	'Wed' => 'Rabu',
	'Thu' => 'Kamis',
	'Fri' => 'Jumat',
	'Sat' => 'Sabtu'
);
// Ambil data Portal PUPR
$sqlgetatoken="SELECT * FROM `user_pupr` WHERE `token` IS NOT NULL ORDER BY `id` ASC LIMIT 1";
$gettoken=$conn->query($sqlgetatoken);
$tokenx=$gettoken->fetch_assoc();
$tokennya=$tokenx['token'];
//===============================================================================
// Login PUPR
$curl = curl_init();
$urlnya="https://siki.pu.go.id/siki-api/v1/permohonan-skk/".$as['no_pendaftaran'];
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
for($o = 0; $o < sizeof($data["klasifikasi_kualifikasi"]); $o++)
{
	$subklasifikasinya=$data["klasifikasi_kualifikasi"][$o]["subklasifikasi"];
	$kualifikasinya=$data["klasifikasi_kualifikasi"][$o]["kualifikasi"];
	$JabatanKerja=$data["klasifikasi_kualifikasi"][$o]["jabatan_kerja"];
	$Jenjang=$data["klasifikasi_kualifikasi"][$o]["jenjang"];
	$Klasifikasi=$data["klasifikasi_kualifikasi"][$o]["klasifikasi"];
}
// get blangko BNSP
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
// get blangko BNSP
$curl = curl_init();
curl_setopt_array($curl, [
  CURLOPT_URL => "https://konstruksi.bnsp.go.id/api/v1/jadwal/blanko?jadwal_id=".$jdq['id_jadwalbnsp'],
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
for($i = 0; $i < sizeof($datanya["data"]); $i++)
{
	if ($datanya["data"][$i]["nik"]==$as['no_ktp'] && $datanya["data"][$i]["nomor_blanko"]<>null){
		$idjadwal=$datanya["data"][$i]["jadwal_id"];
		$idasesi=$datanya["data"][$i]["id_asesi"];
		//$datanya["data"][$i]["nama"];
		$nik=$datanya["data"][$i]["nik"];
		$nomor_blanko=$datanya["data"][$i]["nomor_blanko"];
		$noblangko=$datanya["data"][$i]["nomor_blanko"];
		$idskema=$datanya["data"][$i]["skema_id"];
	}
}
/* $curl = curl_init();
curl_setopt_array($curl, [
  CURLOPT_URL => "https://konstruksi.bnsp.go.id/api/v1/blanko/by_nik?nik=".$as['no_ktp'],
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
if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo "<br/>BNSP DATA BLANGKO :<br/>".$response."<hr/>";
} */
//$datanya=json_decode($response, true);
/*Dynamically generating rows & columns*/
//for($i = 0; $i < sizeof($datanya["data"]); $i++)
//{
// ambil data KBLI dan KBJI
$sqlgetkbji="SELECT * FROM `kbli_kbji` WHERE `kode_lpjk`='$JabatanKerja'";
$getkbji=$conn->query($sqlgetkbji);
$kbji=$getkbji->fetch_assoc();
	//$nomor_blanko = $datanya["data"]["nomor_blanko"];
	//$id_lsp = $datanya["data"]["id_lsp"];
	$kbli = $kbji['kbli'];
	$kbji = $kbji['kbji'];
//}
/* echo "<br />Nomor Blangko : ".$nomor_blanko;
echo "<br />ID LSP : ".$id_lsp;
echo "<br />KBLI : ".$kbli;
echo "<br />KBJI : ".$kbji; */
// get nomor permohonan blangko BNSP
/* $curl2 = curl_init();
curl_setopt_array($curl2, [
  CURLOPT_URL => "https://konstruksi.bnsp.go.id/api/v1/jadwal/blanko/nomor-permohonan?jadwal_id=".$jdq['id_jadwalbnsp'],
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
if ($err2) {
  echo "cURL Error #:" . $err2;
} else {
  echo "<br/>BNSP DATA BLANGKO NOMOR PERMOHONAN :<br/>".$response2;
}
$datanya2=json_decode($response2, true); */
/*Dynamically generating rows & columns*/
//for($i = 0; $i < sizeof($datanya["data"]); $i++)
//{
	//$idjadwal=$datanya["data"]["id_jadwal"];
	//$idasesi=$datanya["data"]["id"];
	//$temp .= "<td>" . $datanya["data"][$i]["nama"] . "</td>";
	//$nik=$datanya["data"]["nik"];
	//$noblangko=$datanya["data"]["nomor_blanko"];
	//$idskema=$datanya["data"]["skema_asesi"]["id"];
//}
// =====================================================================
/* echo "<br />ID Jadwal : ".$idjadwal;
echo "<br />ID Asesi : ".$idasesi;
echo "<br />NIK : ".$nik;
echo "<br />NO. Blangko : ".$noblangko;
echo "<br />ID Skema : ".$idskema; */
$jenjangkkni=$Jenjang;
//$tahunpermohonan=substr($jdq['tgl_asesmen'],0,4);
$tahunpermohonan=date("Y");
// get data nomor register sertifikat keseluruhan selama LSP berdiri
$sqlgetnoregsert0="SELECT * FROM `sertifikat`";
$getnoregsert0=$conn->query($sqlgetnoregsert0);
$jumcert=$getnoregsert0->num_rows;
if ($jumcert>0){
	// cek apakah sudah ada sertifikatnya
	$sqlgetnoregsert0a="SELECT * FROM `sertifikat` WHERE `id_asesi`='$as[no_pendaftaran]' AND `id_jadwal`='$jdq[id]' AND `no_blangko` IS NOT NULL";
	$getnoregsert0a=$conn->query($sqlgetnoregsert0a);
	$jumcertas=$getnoregsert0a->num_rows;
	$nrcertxx=$getnoregsert0a->fetch_assoc();
	if ($jumcertas>0){
		$nourutsertifikat=$nrcertxx['no_register'];
		$nourutdilsp=$nrcertxx['no_regpertahun'];
	}else{
		$sqlgetnoregsert="SELECT * FROM `sertifikat` ORDER BY `no_register` DESC LIMIT 1";
		$getnoregsert=$conn->query($sqlgetnoregsert);
		$nrcert=$getnoregsert->fetch_assoc();
		$nourutsertifikat0=$nrcert['no_register']+1;
		$nourutsertifikat00=strlen($nourutsertifikat0);
		switch ($nourutsertifikat00){
			case 1:
				$nourutsertifikat="0000000".$nourutsertifikat0;
			break;
			case 2:
				$nourutsertifikat="000000".$nourutsertifikat0;
			break;
			case 3:
				$nourutsertifikat="00000".$nourutsertifikat0;
			break;
			case 4:
				$nourutsertifikat="0000".$nourutsertifikat0;
			break;
			case 5:
				$nourutsertifikat="000".$nourutsertifikat0;
			break;
			case 6:
				$nourutsertifikat="00".$nourutsertifikat0;
			break;
			case 7:
				$nourutsertifikat="0".$nourutsertifikat0;
			break;
			default:
				$nourutsertifikat=$nourutsertifikat0;
			break;
		}
		// get data nomor register sertifikat keseluruhan per tahun
		$sqlgetnoregsert20="SELECT * FROM `sertifikat` WHERE `tahun`='$tahunpermohonan'";
		$getnoregsert20=$conn->query($sqlgetnoregsert20);
		$jumcert2=$getnoregsert20->num_rows;
		if ($jumcert2>0){
			$sqlgetnoregsert2="SELECT * FROM `sertifikat` WHERE `tahun`='$tahunpermohonan' ORDER BY `no_regpertahun` DESC LIMIT 1";
			$getnoregsert2=$conn->query($sqlgetnoregsert2);
			$nrcert2=$getnoregsert2->fetch_assoc();
			$nourutdilsp0=$nrcert2['no_regpertahun']+1;
			$nourutdilsp00=strlen($nourutdilsp0);
			switch ($nourutdilsp00){
				case 1:
					$nourutdilsp="0000".$nourutdilsp0;
				break;
				case 2:
					$nourutdilsp="000".$nourutdilsp0;
				break;
				case 3:
					$nourutdilsp="00".$nourutdilsp0;
				break;
				case 4:
					$nourutdilsp="0".$nourutdilsp0;
				break;
				default:
					$nourutdilsp=$nourutdilsp0;
				break;
			}
		}else{
			// 5 digit nomor registernya sesuai ketentuan PUPR
			$nourutdilsp="00001";
		}
	}
}else{
	// 8 digit nomor registernya sesuai ketentuan PUPR
	$nourutsertifikat="00000001";
	// 5 digit nomor registernya sesuai ketentuan PUPR
	$nourutdilsp="00001";
}
$nomorsertifikat=$kbli." ".$kbji." ".$jenjangkkni." ".$nourutsertifikat." ".$tahunpermohonan;
/* echo "<br />No. Serrtifikat : ".$nomorsertifikat;
 */
$nolisensilsp0=explode("-",$lq['no_lisensi']);
$nolisensilsp=@$nolisensilsp0[2];
//$nourutdilsp="00000";
$noregtkk=""; // dapat dari portal perizinan PUPR
$kodeklasifikasi=substr($subklasifikasinya,0,2);
$kodesubklasifikasi=substr($subklasifikasinya,2,2);
// Pencatatan SKK
$nomorregistrasixx="F ".$nolisensilsp." ".$nourutdilsp." ".$tahunpermohonan;
$curlx = curl_init();
$jsonData = array(
	'nomor_registrasi_lsp' => $nomorregistrasixx,
	'nomor_sertifikasi_lsp' => $nomorsertifikat,
	'nomor_blangko_bnsp' => $noblangko
);
if (!empty($noblangko)){
	$jsonDataEncoded = json_encode($jsonData);
	$urlpencatatanskkpupr="https://siki.pu.go.id/siki-api/v1/pencatatan-skk/".$as['no_pendaftaran'];
	curl_setopt_array($curlx, array(
	  CURLOPT_URL => $urlpencatatanskkpupr,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'POST',
	  CURLOPT_POSTFIELDS => $jsonDataEncoded,
	  CURLOPT_HTTPHEADER => array(
		'Content-Type: application/json',
		'token: '.$tokennya
	  ),
	));
	$responselpjk = curl_exec($curlx);
	curl_close($curlx);
	//echo $responselpjk;
	$dec = json_decode($responselpjk, true);
	if ($dec["status"]=='errors'){
		echo $dec["message"];
	}
	//echo $dec["status"];
	$nomorregistrasi=$dec["nomor_registrasi"];
	$qrnomorregistrasi=$dec["qr"];
	//echo $dec["message"];
	//echo $qrnomorregistrasi;
	/* for($idx = 0; $idx < count($dec); $idx++){
		$obj = (array)$dec[$idx];
		echo "<br />".$obj[1];
		echo "<br />".$obj[2];
		echo "<br />".$obj[3];
		echo "<br />".$obj[4];
	} */
	//$nomorregistrasi="No. Reg. F ".$nolisensilsp." ".$nourutdilsp." ".$tahunpermohonan." ".$noregtkk;
	$nomorregistrasitampil="No. Reg. ".$nomorregistrasi;
	/* echo "<br />No. Reg. Serrtifikat : ".$nomorregistrasi;
	echo "<hr />"; */
	// membuat digital signature pimppinan LSP
	$alamatip=$_SERVER['REMOTE_ADDR'];
	$url =  "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
	$iddokumen=md5($url);
	$escaped_url = htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
	$namadokumensertifikat="SERTIFIKAT KOMPETENSI BNSP NO. ".$nomorsertifikat." an.".$as['nama']." NO. BLANGKO ".$nomor_blanko;
	$sqlinputdigisign="INSERT INTO `logdigisign`(`id_dokumen`, `url_ditandatangani`, `nama_dokumen`, `penandatangan`, `ip`, `waktu`) VALUES ('$iddokumen','$escaped_url','$namadokumensertifikat','$lq[direktur]','$alamatip', '$ttdsertifikatnya')";
	$conn->query($sqlinputdigisign);
	$pdf->SetMargins(0,0,0);
	$write=new easyTable($pdf, '{200}', 'width:200; align:C; font-family:arial;');
	$write->rowStyle('min-height:10');
	$write->easyCell($nomor_blanko, 'font-size:18; colspan:2; font-style:B; align:L; valign:B;');
	$write->printRow();
	$write->endTable(50);
	// ambil data klasifikasi, sub klasifikasi dan kualifikasi dari jabatan kerja
	//===============================================================================
	$curl = curl_init();
	curl_setopt_array($curl, array(
	  CURLOPT_URL => 'https://siki.pu.go.id/siki-api/v2/jabatan-kerja',
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
	for($i = 0; $i < sizeof($data["data"]); $i++)
	{
		if ($data["data"][$i]["id_jabatan_kerja"]==$sq['kode_skema']){
			$klasifikasi=$data["data"][$i]["klasifikasi"];
			$klasifikasi_eng=$data["data"][$i]["klasifikasi_en"];
			$subklasifikasi=$data["data"][$i]["subklasifikasi"];
			$subklasifikasi_eng=$data["data"][$i]["subklasifikasi_en"];
			$kualifikasi=$data["data"][$i]["kualifikasi"];
			$kualifikasi_eng=$data["data"][$i]["kualifikasi_en"];
			$jabatankerja=$data["data"][$i]["jabatan_kerja"];
			$workposition=$data["data"][$i]["work_position"];
		}
	}
	$write=new easyTable($pdf, '{98,4,98}', 'width:200; align:C; font-family:arial;');
	$write->rowStyle('min-height:10');
	$write->easyCell('Nomor Sertifikat', 'font-size:14; font-style:B; align:R;');
	$write->easyCell('/', 'font-size:14; font-style:B; align:C;');
	$write->easyCell('Certificate Number', 'font-size:13.5; font-style:I,B; align:L;');
	$write->printRow();
	$write->easyCell($nomorsertifikat, 'line-height:0.75; font-size:13.5; font-style:B; align:C; valign:B; colspan:3;');
	$write->printRow();
	$write->rowStyle('min-height:10');
	$write->easyCell('Dengan ini menyatakan bahwa,', 'line-height:0.75; font-size:13.5; font-style:N; align:C; valign:B; colspan:3;');
	$write->printRow();
	$write->easyCell('This is to certify that,', 'line-height:0.75; font-size:13.5; font-style:I; align:C; valign:T; colspan:3;');
	$write->printRow();
	$write->rowStyle('min-height:10');
	$write->easyCell($as['nama'], 'line-height:0.75; font-size:15; font-style:B; align:C; valign:M; colspan:3;');
	$write->printRow();
	$write->rowStyle('min-height:10');
	$write->easyCell($nomorregistrasitampil, 'line-height:0.75; font-size:13.5; font-style:B; align:C; valign:M; colspan:3;');
	$write->printRow();
	$write->easyCell('Telah Kompeten pada bidang:', 'line-height:0.75; font-size:13.5; font-style:B; align:C; valign:M; colspan:3;');
	$write->printRow();
	$write->easyCell('Is competent in the area of:', 'line-height:0.75; font-size:13.5; font-style:I; align:C; valign:T; colspan:3;');
	$write->printRow();
	$write->rowStyle('min-height:10');
	$write->easyCell('Jasa Konstruksi', 'line-height:0.75; font-size:15; font-style:B; align:C; valign:B; colspan:3;');
	$write->printRow();
	$write->easyCell('Construction Services', 'line-height:0.75; font-size:15; font-style:BI; align:C; valign:T; colspan:3;');
	$write->printRow();
	$write->rowStyle('min-height:10');
	$write->easyCell('Dengan Kualifikasi / Kompetensi:', 'line-height:0.75; font-size:13.5; font-style:N; align:C; valign:B; colspan:3;');
	$write->printRow();
	$write->easyCell('With Qualification / Competency:', 'line-height:0.75; font-size:13.5; font-style:I; align:C; valign:T; colspan:3;');
	$write->printRow();
	$namajabatankerja=strlen($jabatankerja);
	if ($namajabatankerja>45){
		$write->rowStyle('min-height:10');
		$write->easyCell($jabatankerja, 'line-height:0.75; font-size:14; font-style:B; align:C; valign:B; colspan:3;');
		$write->printRow();
		$write->easyCell($workposition, 'line-height:0.75; font-size:14; font-style:BI; align:C; valign:T; colspan:3;');
		$write->printRow();
	}else{
		$write->rowStyle('min-height:10');
		$write->easyCell($jabatankerja, 'line-height:0.75; font-size:16.5; font-style:B; align:C; valign:B; colspan:3;');
		$write->printRow();
		$write->easyCell($workposition, 'line-height:0.75; font-size:16.5; font-style:BI; align:C; valign:T; colspan:3;');
		$write->printRow();
	}
	$sqlgetpupr="SELECT * FROM `user_pupr` WHERE `username` IS NOT NULL";
	$getpupr=$conn->query($sqlgetpupr);
	$jumpupr=$getpupr->num_rows;
	if ($jumpupr>0){
		$write->rowStyle('min-height:10');
		$write->easyCell('Sertifikat ini berlaku untuk 5 (lima) tahun', 'line-height:0.75; font-size:12; font-style:N; align:C; valign:B; colspan:3;');
		$write->printRow();
		$write->easyCell('This certificate is valid for 5 (five) years', 'line-height:0.75; font-size:12; font-style:I; align:C; valign:T; colspan:3;');
		$write->printRow();
	}else{
		$write->rowStyle('min-height:10');
		$write->easyCell('Sertifikat ini berlaku untuk 3 (tiga) tahun', 'line-height:0.75; font-size:12; font-style:N; align:C; valign:B; colspan:3;');
		$write->printRow();
		$write->easyCell('This certificate is valid for 3 (three) years', 'line-height:0.75; font-size:12; font-style:I; align:C; valign:T; colspan:3;');
		$write->printRow();
	}
	$sqlinputdigisign="SELECT * FROM `logdigisign` WHERE `id_dokumen`='$iddokumen' AND `nama_dokumen`= '$namadokumensertifikat' AND `penandatangan`='$lq[direktur]' ORDER BY `id` DESC";
	$inputdigisign=$conn->query($sqlinputdigisign);
	$dgsn=$inputdigisign->fetch_assoc();
	//memanggil library QR Code
	require_once("../phpqrcode/qrlib.php");
	$qrcodetext="http://".$iden['url_domain']."/signed.php?id=".$dgsn['id'];
	//create a QR Code and save it as a png image file named generateqr.png
	QRcode::png($qrcodetext,"../foto_tandatangan/generateqr.png");
	$write->rowStyle('min-height:10');
	$write->easyCell('Atas nama Badan Nasional Sertifikasi Profesi', 'line-height:0.75; font-size:13.5; font-style:N; align:C; valign:B; colspan:3;');
	$write->printRow();
	$write->easyCell('On Behalf of Indonesia Professional Certification Authority', 'line-height:0.75; font-size:13.5; font-style:I; align:C; valign:T; colspan:3;');
	$write->printRow();
	$namalsplen=strlen($namalsp);
	if ($namalsplen>25){
		$write->rowStyle('min-height:11');
		$write->easyCell('Lembaga Sertifikasi Profesi '.$namalsp, 'line-height:0.75; font-size:12; font-style:B; align:C; valign:B; colspan:3;');
		$write->printRow();
		$write->easyCell($namalsp.' Professional Certification Agency', 'line-height:0.75; font-size:11; font-style:BI; align:C; valign:T; colspan:3;');
		$write->printRow();
	}else{
		$write->rowStyle('min-height:10');
		$write->easyCell('Lembaga Sertifikasi Profesi '.$namalsp, 'line-height:0.75; font-size:13.5; font-style:B; align:C; valign:B; colspan:3;');
		$write->printRow();
		$write->easyCell($namalsp.' Professional Certification Agency', 'line-height:0.75; font-size:13.5; font-style:BI; align:C; valign:T; colspan:3;');
		$write->printRow();	
	}
	$write->rowStyle('min-height:28');
	$write->easyCell('', 'img:../foto_tandatangan/generateqr.png, h20; font-size:12; font-style:B; align:C; valign:M; colspan:3;');
	$write->printRow();
	$write->easyCell($lq['direktur'], 'line-height:0.75; font-size:12; font-style:B; align:C; valign:B; colspan:3;');
	$write->printRow();
	if ($lq['nama_jabatanpimpinan']=="Ketua"){
		$write->easyCell('Ketua LSP', 'line-height:0.75; font-size:12; font-style:B; align:C; valign:B; colspan:3;');
		$write->printRow();
		$write->easyCell('Chairman PCA', 'line-height:0.5; font-size:12; font-style:I; align:C; valign:T; colspan:3;');
		$write->printRow();
	}else{
		$write->easyCell('Direktur LSP', 'line-height:0.75; font-size:12; font-style:B; align:C; valign:B; colspan:3;');
		$write->printRow();
		$write->easyCell('Director PCA', 'line-height:0.5; font-size:12; font-style:I; align:C; valign:T; colspan:3;');
		$write->printRow();
	}
	$write->endTable(5);
	$pdf->AliasNbPages();
	// ============================= halaman kedua sertifikat ==================================
	$pdf->AddPage();
	$bgsertifikat2="e-sertifikat-blangko-back.png";
	$pdf->Image($bgsertifikat2,0,0,215,297.5);
	$pdf->SetMargins(10,10,10,0);
	$write=new easyTable($pdf, '{200}', 'width:200; align:C; font-family:arial;');
	$write->rowStyle('min-height:10');
	$write->easyCell('', 'font-size:12; font-style:B; align:C; valign:M; colspan:3;');
	$write->printRow();
	$write->endTable(5);
	$write=new easyTable($pdf, '{200}', 'width:200; align:C; font-family:arial;');
	$write->rowStyle('min-height:30');
	$write->easyCell('', 'img:e-sertifikat-logo-pupr.png, h20; font-size:12; font-style:B; align:C; valign:M;');
	$write->printRow();
	$write->easyCell('LEMBAGA PENGEMBANGAN', 'line-height:0.75; font-size:15; font-style:B; align:C; valign:M;');
	$write->printRow();
	$write->easyCell('JASA KONSTRUKSI', 'line-height:0.75; font-size:15; font-style:B; align:C; valign:M;');
	$write->printRow();
	$write->easyCell('CONSTRUCTION SERVICES', 'line-height:0.75; font-size:15; font-style:BI; align:C; valign:M;');
	$write->printRow();
	$write->easyCell('DEVELOPMENT BOARD', 'line-height:0.75; font-size:15; font-style:BI; align:C; valign:M;');
	$write->printRow();
	$write->rowStyle('min-height:10');
	$write->easyCell('Daftar Unit Kompetensi:', 'line-height:0.75; font-size:14; font-style:BI; align:C; valign:B;');
	$write->printRow();
	$write->easyCell('List of Unit(s) of Competency:', 'line-height:0.75; font-size:12; font-style:BI; align:C; valign:T;');
	$write->printRow();
	$write->endTable(3);
	$write=new easyTable($pdf, '{50,5,125}', 'width:180; align:C; font-family:arial;');
	$write->easyCell('Klasifikasi', 'font-size:12; align:L; valign:T;');
	$write->easyCell(':', 'font-size:12; align:C; valign:T;');
	$write->easyCell($klasifikasi, 'font-size:12; align:L; valign:T;');
	$write->printRow();
	$write->easyCell('Classification', 'font-size:12; font-style:I; align:L; valign:T;');
	$write->easyCell(':', 'font-size:12; font-style:I; align:C; valign:T;');
	$write->easyCell($klasifikasi_eng, 'font-size:12; font-style:I; align:L; valign:T;');
	$write->printRow();
	$write->endTable(3);
	$write=new easyTable($pdf, '{50,5,125}', 'width:180; align:C; font-family:arial;');
	$write->easyCell('Subklasifikasi', 'font-size:12; align:L; valign:T;');
	$write->easyCell(':', 'font-size:12; align:C; valign:T;');
	$write->easyCell($subklasifikasi, 'font-size:12; align:L; valign:T;');
	$write->printRow();
	$write->easyCell('Subclassification', 'font-size:12; font-style:I; align:L; valign:T;');
	$write->easyCell(':', 'font-size:12; font-style:I; align:C; valign:T;');
	$write->easyCell($subklasifikasi_eng, 'font-size:12; font-style:I; align:L; valign:T;');
	$write->printRow();
	$write->endTable(3);
	$write=new easyTable($pdf, '{50,5,125}', 'width:180; align:C; font-family:arial;');
	$write->easyCell('Kualifikasi', 'font-size:12; align:L; valign:T;');
	$write->easyCell(':', 'font-size:12; align:C; valign:T;');
	$write->easyCell($kualifikasi, 'font-size:12; align:L; valign:T;');
	$write->printRow();
	$write->easyCell('Qualification', 'font-size:12; font-style:I; align:L; valign:T;');
	$write->easyCell(':', 'font-size:12; font-style:I; align:C; valign:T;');
	$write->easyCell($kualifikasi_eng, 'font-size:12; font-style:I; align:L; valign:T;');
	$write->printRow();
	$write->endTable(3);
	switch ($jenjangkkni){
		case "1":
			$jenjangtampil="1 (Satu)";
			$jenjangtampileng="1 (One)";
		break;
		case "2":
			$jenjangtampil="2 (Dua)";
			$jenjangtampileng="2 (Two)";
		break;
		case "3":
			$jenjangtampil="3 (Tiga)";
			$jenjangtampileng="3 (Three)";
		break;
		case "4":
			$jenjangtampil="4 (Empat)";
			$jenjangtampileng="4 (Four)";
		break;
		case "5":
			$jenjangtampil="5 (Lima)";
			$jenjangtampileng="5 (Five)";
		break;
		case "6":
			$jenjangtampil="6 (Enam)";
			$jenjangtampileng="6 (Six)";
		break;
		case "7":
			$jenjangtampil="7 (Tujuh)";
			$jenjangtampileng="7 (Seven)";
		break;
		case "8":
			$jenjangtampil="8 (Delapan)";
			$jenjangtampileng="8 (Eight)";
		break;
		case "9":
			$jenjangtampil="9 (Sembilan)";
			$jenjangtampileng="9 (Nine)";
		break;
	}
	$write=new easyTable($pdf, '{50,5,125}', 'width:180; align:C; font-family:arial;');
	$write->easyCell('Jenjang', 'font-size:12; align:L; valign:T;');
	$write->easyCell(':', 'font-size:12; align:C; valign:T;');
	$write->easyCell($jenjangtampil, 'font-size:12; align:L; valign:T;');
	$write->printRow();
	$write->easyCell('Level', 'font-size:12; font-style:I; align:L; valign:T;');
	$write->easyCell(':', 'font-size:12; font-style:I; align:C; valign:T;');
	$write->easyCell($jenjangtampileng, 'font-size:12; font-style:I; align:L; valign:T;');
	$write->printRow();
	$write->endTable(3);
	$write=new easyTable($pdf, '{50,5,125}', 'width:180; align:C; font-family:arial;');
	$write->easyCell('Okupasi', 'font-size:12; align:L; valign:T;');
	$write->easyCell(':', 'font-size:12; align:C; valign:T;');
	$write->easyCell($jabatankerja, 'font-size:12; align:L; valign:T;');
	$write->printRow();
	$write->easyCell('Occupation', 'font-size:12; font-style:I; align:L; valign:T;');
	$write->easyCell(':', 'font-size:12; font-style:I; align:C; valign:T;');
	$write->easyCell($workposition, 'font-size:12; font-style:I; align:L; valign:T;');
	$write->printRow();
	$write->endTable(8);
	$ditandatangani="Ditetapkan di ".$tglcetakkota;
	$ditandatangani2="Enacted in ".$tglcetakkota2;
	// Download foto dari Portal PUPR
	// Login PUPR
	$curl = curl_init();
	$urlnya="https://siki.pu.go.id/siki-api/v1/permohonan-skk/".$as['no_pendaftaran'];
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
	/*Fetching JSON file content using php file_get_contents method*/
	$data = json_decode($response, true);
	for($i = 0; $i < sizeof($data["personal"]); $i++)
	{
		$PasFoto=$data["personal"][$i]["pas_foto"];
	}
	$url =$PasFoto;
	// Initialize the cURL session
	$ch = curl_init($url);
	// Initialize directory name where
	// file will be save
	$dir = '../foto_asesi/';
	// Use basename() function to return
	// the base name of file
	$file_name = basename($url);
	// Save file into file location
	$save_file_loc = $dir . $file_name;
	// Open file
	$fp = fopen($save_file_loc, 'wb');
	// It set an option for a cURL transfer
	curl_setopt($ch, CURLOPT_FILE, $fp);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	// Perform a cURL session
	curl_exec($ch);
	// Closes a cURL session and frees all resources
	curl_close($ch);
	// Close file
	fclose($fp);
	//memanggil library QR Code
	require_once("../phpqrcode/qrlib.php");
	$qrcodetext2="lpjk://";
	//create a QR Code and save it as a png image file named generateqr.png
	QRcode::png($qrcodetext2,"../foto_tandatangan/generateqr2.png");
	// image base64
	// Image from data stream ('PHP rules')
	/* $imgLogo = base64_decode($img_base64_encoded);
	$this->setImageScale(7);
	$this->Image('@'.$imgLogo); */
	$img = $qrnomorregistrasi;
	$img = str_replace('data:image/png;base64,', '', $img);
	$img = str_replace(' ', '+', $img);
	$data = base64_decode($img);
	$file = "../foto_tandatangan/" . uniqid() . '.png';
	$success = file_put_contents($file, $data);
	$write=new easyTable($pdf, '{60,120}', 'width:180; align:C; font-family:arial;');
	$write->easyCell('', 'font-size:10.5; align:C; valign:T;');
	$write->easyCell($ditandatangani, 'line-height:0.75; font-size:10.5; align:C; valign:T;');
	$write->printRow();
	$write->easyCell('', 'font-size:10.5; align:C; valign:T;');
	$write->easyCell($ditandatangani2, 'line-height:0.75; font-size:10.5; font-style:I; align:C; valign:T;');
	$write->printRow();
	$write->easyCell('', 'img:../foto_asesi/'.$file_name.',w30,h40; font-size:10.5; align:C; valign:T;');
	$write->rowStyle('min-height:28');
	//$write->easyCell('', 'img:../foto_tandatangan/generateqr2.png, h50; font-size:12; font-style:B; align:C; valign:T; rowspan:2;');
	$write->easyCell('', 'img:'. $file .', h50; font-size:12; font-style:B; align:C; valign:T; rowspan:2;');
	$write->printRow();
	$write->easyCell($as['nama'], 'font-size:10.5; align:C; valign:T;');
	$write->printRow();
	$write->endTable(8);
	$write=new easyTable($pdf, '{17,163}', 'width:180; align:C; font-family:arial;');
	$write->easyCell('Keterangan ', 'line-height:0.75; font-size:8; font-style:N; align:L; valign:T;');
	$write->easyCell('/ Remarks :', 'line-height:0.75; font-size:8; font-style:I; align:L; valign:T;');
	$write->printRow();
	$write->endTable(0);
	$write=new easyTable($pdf, '{5,175}', 'width:180; align:C; font-family:arial;');
	$write->easyCell('1.', 'line-height:0.75; font-size:8; font-style:N; align:L; valign:T;');
	$write->easyCell('Sertifikat ini sah berlaku setelah tercatat yang dibuktikan dengan nomor registrasi Sertifikat Kompetensi Kerja Konstruksi. /', 'line-height:0.75; font-size:9; font-style:N; align:L; valign:T;');
	$write->printRow();
	$write->easyCell('', 'line-height:0.75; font-size:8; font-style:N; align:C; valign:T;');
	$write->easyCell('This certificate is valid upon being registered as evidenced by registration number of Certificate of Competency of Contruction Works.', 'line-height:0.75; font-size:9; font-style:I; align:L; valign:T;');
	$write->printRow();
	$write->easyCell('2.', 'line-height:0.75; font-size:8; font-style:N; align:L; valign:T;');
	$write->easyCell('QR Code dan Data yang tertera dalam sertifikat ini dapat diverifikasi melalui sistem informasi jasa konstruksi terintegrasi. /', 'line-height:0.75; font-size:9; font-style:N; align:L; valign:T;');
	$write->printRow();
	$write->easyCell('', 'line-height:0.75; font-size:8; font-style:N; align:C; valign:T;');
	$write->easyCell('QR Code and Data contained herein may be verified through an integrated information system of construction service.', 'line-height:0.75; font-size:9; font-style:I; align:L; valign:T;');
	$write->printRow();
	$write->endTable(0);
	$pdf->AliasNbPages();
	// Pencatatan kembali ke tabel sertifikat
	$no_registrasisertifikat=$nomorregistrasi;
	$tgl_terbit=date("Y-m-d");
	$tambah2=date('Y-m-d', strtotime('+5 years', strtotime($tgl_terbit)));
	$tgl_expired=$tambah2;
	$sqlgetnoregsert3="SELECT * FROM `sertifikat` WHERE `id_asesi`='$as[no_pendaftaran]' AND `id_jadwal`='$jdq[id]' AND `id_skemakkni`='$sq[id]' AND `no_register`='$nourutsertifikat' AND `no_regpertahun`='$nourutdilsp' AND `no_blangko`='$nomor_blanko' AND `no_sertifikat`='$nomorsertifikat' AND `tahun`='$tahunpermohonan'";
	$getnoregsert3=$conn->query($sqlgetnoregsert3);
	//$nrcert3=$getnoregsert3->fetch_assoc();
	$jumcert3=$getnoregsert3->num_rows;
	if ($jumcert3>0){
		$sqlinputcert="UPDATE `sertifikat` SET `no_register`='$nourutsertifikat', `no_regpertahun`='$nourutdilsp', `no_blangko`='$nomor_blanko', `no_sertifikat`='$nomorsertifikat', `no_registrasisertifikat`='$no_registrasisertifikat',  `tgl_terbit`='$tgl_terbit', `tahun`='$tahunpermohonan', `masa_berlaku`='$tgl_expired' WHERE `id_asesi`='$as[no_pendaftaran]' AND `id_jadwal`='$jdq[id]' AND `id_skemakkni`='$sq[id]'";
		$conn->query($sqlinputcert);
		//echo $sqlinputcert;
	}else{
		$sqlinputcert="INSERT INTO `sertifikat`(`id_asesi`, `id_jadwal`, `id_skemakkni`, `tgl_diajukan`, `no_pengajuan`, `no_register`, `no_regpertahun`, `no_blangko`, `no_sertifikat`, `no_registrasisertifikat`, `tgl_terbit`, `tahun`, `masa_berlaku`) VALUES ('$as[no_pendaftaran]', '$jdq[id]', '$sq[id]', '', '', '$nourutsertifikat', '$nourutdilsp', '$nomor_blanko', '$nomorsertifikat', '$no_registrasisertifikat', '$tgl_terbit', '$tahunpermohonan', '$tgl_expired')";
		$conn->query($sqlinputcert);
		//echo $sqlinputcert;
	}
	// =================================================
	//output file pdf
	$fileoutputnya="e-sertifikat-".$as['nama']."-".$_GET['ida'].".pdf";
	$pdf->Output($fileoutputnya,'D');
}else{
	echo "Nomor Blangko dari BNSP Belum Tersedia";
}
ob_end_flush();
?>