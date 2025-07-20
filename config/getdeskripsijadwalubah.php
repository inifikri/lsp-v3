<?php
 
ini_set('display_errors',0);
require_once "koneksi.php";
require_once "fungsi_indotgl.php";

$idjadwal = $_GET['jadwalasesmenubah'];
$sqlgetdesc = "SELECT * FROM `jadwal_asesmen` WHERE `id`='$idjadwal'";
$getdesc = $conn->query($sqlgetdesc);
$desc=$getdesc->fetch_assoc();
$tglasesmen=tgl_indo($desc[tgl_asesmen]);
$sqltempatasesmen="SELECT * FROM `tuk` WHERE `id`='$desc[tempat_asesmen]'";
$tempatasesmen=$conn->query($sqltempatasesmen);
$tmpt=$tempatasesmen->fetch_assoc();
$hariini=date("Y-m-d");
if ($desc['tgl_asesmen']<$hariini){
	echo "<p><b>PERHATIAN!<br>Jadwal yang Anda pilih telah berlalu (telah lampau), apakah Anda yakin memilih jadwal ini?</b></p><p class='text-red'";
}else{
	echo "<p class='text-green'";
}
echo" id='deskripsijadwalubah'>Gelombang $desc[gelombang] $desc[periode] $desc[tahun]<br>Tanggal $tglasesmen Pukul $desc[jam_asesmen] di $tmpt[nama]<br>Asesor :<br>";
$sqlgetdesc2 = "SELECT * FROM `jadwal_asesor` WHERE `id_jadwal`='$idjadwal'";
$getdesc2 = $conn->query($sqlgetdesc2);
$noass=1;
while ($desc2=$getdesc2->fetch_assoc()){

	$sqlasesor="SELECT * FROM `asesor` WHERE `id`='$desc2[id_asesor]'";
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
	echo $noass.". ".$namaasesor."<br>";
	$noass++;
}
echo "</p>";
?>