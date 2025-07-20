<?php
//error_reporting(E_ALL);
session_start();
include "../config/koneksi.php";

$sqlcekdatamkva="SELECT * FROM `mkva` WHERE `id_jadwal`='$_POST[id_jadwal]'";
$cekdatamkva=$conn->query($sqlcekdatamkva);
$jumceknya=$cekdatamkva->num_rows;
if (!empty($_POST['ab1v'])){$ab1v='1';}else{$ab1v='0';}
if (!empty($_POST['ab1a'])){$ab1a='1';}else{$ab1a='0';}
if (!empty($_POST['ab1t'])){$ab1t='1';}else{$ab1t='0';}
if (!empty($_POST['ab1m'])){$ab1m='1';}else{$ab1m='0';}
if (!empty($_POST['pa1v'])){$pa1v='1';}else{$pa1v='0';}
if (!empty($_POST['pa1r'])){$pa1r='1';}else{$pa1r='0';}
if (!empty($_POST['pa1f'])){$pa1f='1';}else{$pa1f='0';}
if (!empty($_POST['pa1f2'])){$pa1f2='1';}else{$pa1f2='0';}
if (!empty($_POST['ab2v'])){$ab2v='1';}else{$ab2v='0';}
if (!empty($_POST['ab2a'])){$ab2a='1';}else{$ab2a='0';}
if (!empty($_POST['ab2t'])){$ab2t='1';}else{$ab2t='0';}
if (!empty($_POST['ab2m'])){$ab2m='1';}else{$ab2m='0';}
if (!empty($_POST['pa2v'])){$pa2v='1';}else{$pa2v='0';}
if (!empty($_POST['pa2r'])){$pa2r='1';}else{$pa2r='0';}
if (!empty($_POST['pa2f'])){$pa2f='1';}else{$pa2f='0';}
if (!empty($_POST['pa2f2'])){$pa2f2='1';}else{$pa2f2='0';}
if (!empty($_POST['ab3v'])){$ab3v='1';}else{$ab3v='0';}
if (!empty($_POST['ab3a'])){$ab3a='1';}else{$ab3a='0';}
if (!empty($_POST['ab3t'])){$ab3t='1';}else{$ab3t='0';}
if (!empty($_POST['ab3m'])){$ab3m='1';}else{$ab3m='0';}
if (!empty($_POST['pa3v'])){$pa3v='1';}else{$pa3v='0';}
if (!empty($_POST['pa3r'])){$pa3r='1';}else{$pa3r='0';}
if (!empty($_POST['pa3f'])){$pa3f='1';}else{$pa3f='0';}
if (!empty($_POST['pa3f2'])){$pa3f2='1';}else{$pa3f2='0';}
if (!empty($_POST['ab4v'])){$ab4v='1';}else{$ab4v='0';}
if (!empty($_POST['ab4a'])){$ab4a='1';}else{$ab4a='0';}
if (!empty($_POST['ab4t'])){$ab4t='1';}else{$ab4t='0';}
if (!empty($_POST['ab4m'])){$ab4m='1';}else{$ab4m='0';}
if (!empty($_POST['pa4v'])){$pa4v='1';}else{$pa4v='0';}
if (!empty($_POST['pa4r'])){$pa4r='1';}else{$pa4r='0';}
if (!empty($_POST['pa4f'])){$pa4f='1';}else{$pa4f='0';}
if (!empty($_POST['pa4f2'])){$pa4f2='1';}else{$pa4f2='0';}
if (!empty($_POST['ab5v'])){$ab5v='1';}else{$ab5v='0';}
if (!empty($_POST['ab5a'])){$ab5a='1';}else{$ab5a='0';}
if (!empty($_POST['ab5t'])){$ab5t='1';}else{$ab5t='0';}
if (!empty($_POST['ab5m'])){$ab5m='1';}else{$ab5m='0';}
if (!empty($_POST['pa5v'])){$pa5v='1';}else{$pa5v='0';}
if (!empty($_POST['pa5r'])){$pa5r='1';}else{$pa5r='0';}
if (!empty($_POST['pa5f'])){$pa5f='1';}else{$pa5f='0';}
if (!empty($_POST['pa5f2'])){$pa5f2='1';}else{$pa5f2='0';}
if (!empty($_POST['ab6v'])){$ab6v='1';}else{$ab6v='0';}
if (!empty($_POST['ab6a'])){$ab6a='1';}else{$ab6a='0';}
if (!empty($_POST['ab6t'])){$ab6t='1';}else{$ab6t='0';}
if (!empty($_POST['ab6m'])){$ab6m='1';}else{$ab6m='0';}
if (!empty($_POST['pa6v'])){$pa6v='1';}else{$pa6v='0';}
if (!empty($_POST['pa6r'])){$pa6r='1';}else{$pa6r='0';}
if (!empty($_POST['pa6f'])){$pa6f='1';}else{$pa6f='0';}
if (!empty($_POST['pa6f2'])){$pa6f2='1';}else{$pa6f2='0';}
if (!empty($_POST['ab7v'])){$ab7v='1';}else{$ab7v='0';}
if (!empty($_POST['ab7a'])){$ab7a='1';}else{$ab7a='0';}
if (!empty($_POST['ab7t'])){$ab7t='1';}else{$ab7t='0';}
if (!empty($_POST['ab7m'])){$ab7m='1';}else{$ab7m='0';}
if (!empty($_POST['pa7v'])){$pa7v='1';}else{$pa7v='0';}
if (!empty($_POST['pa7r'])){$pa7r='1';}else{$pa7r='0';}
if (!empty($_POST['pa7f'])){$pa7f='1';}else{$pa7f='0';}
if (!empty($_POST['pa7f2'])){$pa7f2='1';}else{$pa7f2='0';}
if (!empty($_POST['ab8v'])){$ab8v='1';}else{$ab8v='0';}
if (!empty($_POST['ab8a'])){$ab8a='1';}else{$ab8a='0';}
if (!empty($_POST['ab8t'])){$ab8t='1';}else{$ab8t='0';}
if (!empty($_POST['ab8m'])){$ab8m='1';}else{$ab8m='0';}
if (!empty($_POST['pa8v'])){$pa8v='1';}else{$pa8v='0';}
if (!empty($_POST['pa8r'])){$pa8r='1';}else{$pa8r='0';}
if (!empty($_POST['pa8f'])){$pa8f='1';}else{$pa8f='0';}
if (!empty($_POST['pa8f2'])){$pa8f2='1';}else{$pa8f2='0';}

if ($jumceknya>0){
	// update data MKVA
	$sqlupdatemkva="UPDATE `mkva` SET `periode`='$_POST[periode]',`tujuan_1`='$_POST[tujuan_1]',`tujuan_2`='$_POST[tujuan_2]',`tujuan_3`='$_POST[tujuan_3]',`tujuan_4`='$_POST[tujuan_4]',`tujuan_5`='$_POST[tujuan_5]',`tujuan_6`='$_POST[tujuan_6]',`tujuan_7`='$_POST[tujuan_7]',`tujuan_7b`='$_POST[tujuan_7b]',`konteks_1`='$_POST[konteks_1]',`konteks_2`='$_POST[konteks_2]',`konteks_3`='$_POST[konteks_3]',`konteks_4`='$_POST[konteks_4]',`konteks_5`='$_POST[konteks_5]',`konteks_6`='$_POST[konteks_6]',`konteks_6b`='$_POST[konteks_6b]',`pendekatan_1`='$_POST[pendekatan_1]',`pendekatan_2`='$_POST[pendekatan_2]',`pendekatan_3`='$_POST[pendekatan_3]',`pendekatan_4`='$_POST[pendekatan_4]',`pendekatan_5`='$_POST[pendekatan_5]',`pendekatan_6`='$_POST[pendekatan_6]',`askom`='$_POST[askom]',`orel_1`='$_POST[orel_1]',`konfirmorel_1`='$_POST[konfirmorel_1]',`orel_2`='$_POST[orel_2]',`konfirmorel_2`='$_POST[konfirmorel_2]',`orel_3`='$_POST[orel_3]',`konfirmorel_3`='$_POST[konfirmorel_3]',`leadasesor`='$_POST[leadasesor]',`nama_leadasesor`='$_POST[nama_leadasesor]',`konfirmleadaseesor`='$_POST[konfirmleadaseesor]',`manspv`='$_POST[manspv]',`nama_manspv`='$_POST[nama_manspv]',`konfirmmanspv`='$_POST[konfirmmanspv]',`tenagaahli`='$_POST[tenagaahli]',`nama_tenagaahli`='$_POST[nama_tenagaahli]',`konfirmtenagaahli`='$_POST[konfirmtenagaahli]',`koordtraining`='$_POST[koordtraining]',`nama_koordtraining`='$_POST[nama_koordtraining]',`konfirmkoordtraining`='$_POST[konfirmkoordtraining]',`asosiasi`='$_POST[asosiasi]',`nama_asosiasi`='$_POST[nama_asosiasi]',`konfirmasosiasi`='$_POST[konfirmasosiasi]',`stdkom`='$_POST[stdkom]',`sop`='$_POST[sop]',`manualbook`='$_POST[manualbook]',`stdkinerja`='$_POST[stdkinerja]',`lainnya`='$_POST[lainnya]',`nama_lainnya`='$_POST[nama_lainnya]',`skema`='$_POST[skema]',`skk`='$_POST[skk]',`perangkat`='$_POST[perangkat]',`peraturan`='$_POST[peraturan]',`proaktif`='$_POST[proaktif]',`activelistening`='$_POST[activelistening]',`keterampilan1`='$_POST[keterampilan1]',`nama_ketlainnya1`='$_POST[nama_ketlainnya1]',`keterampilan2`='$_POST[keterampilan2]',`nama_ketlainnya2`='$_POST[nama_ketlainnya2]',`ab1v`='$ab1v',`ab1a`='$ab1a',`ab1t`='$ab1t',`ab1m`='$ab1m',`pa1v`='$pa1v',`pa1r`='$pa1r',`pa1f`='$pa1f',`pa1f2`='$pa1f2',`ab2v`='$ab2v',`ab2a`='$ab2a',`ab2t`='$ab2t',`ab2m`='$ab2m',`pa2v`='$pa2v',`pa2r`='$pa2r',`pa2f`='$pa2f',`pa2f2`='$pa2f2',`ab3v`='$ab3v',`ab3a`='$ab3a',`ab3t`='$ab3t',`ab3m`='$ab3m',`pa3v`='$pa3v',`pa3r`='$pa3r',`pa3f`='$pa3f',`pa3f2`='$pa3f2',`ab4v`='$ab4v',`ab4a`='$ab4a',`ab4t`='$ab4t',`ab4m`='$ab4m',`pa4v`='$pa4v',`pa4r`='$pa4r',`pa4f`='$pa4f',`pa4f2`='$pa4f2',`ab5v`='$ab5v',`ab5a`='$ab5a',`ab5t`='$ab5t',`ab5m`='$ab5m',`pa5v`='$pa5v',`pa5r`='$pa5r',`pa5f`='$pa5f',`pa5f2`='$pa5f2',`ab6v`='$ab6v',`ab6a`='$ab6a',`ab6t`='$ab6t',`ab6m`='$ab6m',`pa6v`='$pa6v',`pa6r`='$pa6r',`pa6f`='$pa6f',`pa6f2`='$pa6f2',`ab7v`='$ab7v',`ab7a`='$ab7a',`ab7t`='$ab7t',`ab7m`='$ab7m',`pa7v`='$pa7v',`pa7r`='$pa7r',`pa7f`='$pa7f',`pa7f2`='$pa7f2',`ab8v`='$ab8v',`ab8a`='$ab8a',`ab8t`='$ab8t',`ab8m`='$ab8m',`pa8v`='$pa8v',`pa8r`='$pa8r',`pa8f`='$pa8f',`pa8f2`='$pa8f2' WHERE `id_jadwal`='$_POST[id_jadwal]'";
	$conn->query($sqlupdatemkva);
}else{
	// insert data MKVA
	$sqlinsertmkva="INSERT INTO `mkva`(`id_jadwal`, `periode`, `tujuan_1`, `tujuan_2`, `tujuan_3`, `tujuan_4`, `tujuan_5`, `tujuan_6`, `tujuan_7`, `tujuan_7b`, `konteks_1`, `konteks_2`, `konteks_3`, `konteks_4`, `konteks_5`, `konteks_6`, `konteks_6b`, `pendekatan_1`, `pendekatan_2`, `pendekatan_3`, `pendekatan_4`, `pendekatan_5`, `pendekatan_6`, `askom`, `orel_1`, `konfirmorel_1`, `orel_2`, `konfirmorel_2`, `orel_3`, `konfirmorel_3`, `leadasesor`, `nama_leadasesor`, `konfirmleadaseesor`, `manspv`, `nama_manspv`, `konfirmmanspv`, `tenagaahli`, `nama_tenagaahli`, `konfirmtenagaahli`, `koordtraining`, `nama_koordtraining`, `konfirmkoordtraining`, `asosiasi`, `nama_asosiasi`, `konfirmasosiasi`, `stdkom`, `sop`, `manualbook`, `stdkinerja`, `lainnya`, `nama_lainnya`, `skema`, `skk`, `perangkat`, `peraturan`, `proaktif`, `activelistening`, `keterampilan1`, `nama_ketlainnya1`, `keterampilan2`, `nama_ketlainnya2`, `ab1v`, `ab1a`, `ab1t`, `ab1m`, `pa1v`, `pa1r`, `pa1f`, `pa1f2`, `ab2v`, `ab2a`, `ab2t`, `ab2m`, `pa2v`, `pa2r`, `pa2f`, `pa2f2`, `ab3v`, `ab3a`, `ab3t`, `ab3m`, `pa3v`, `pa3r`, `pa3f`, `pa3f2`, `ab4v`, `ab4a`, `ab4t`, `ab4m`, `pa4v`, `pa4r`, `pa4f`, `pa4f2`, `ab5v`, `ab5a`, `ab5t`, `ab5m`, `pa5v`, `pa5r`, `pa5f`, `pa5f2`, `ab6v`, `ab6a`, `ab6t`, `ab6m`, `pa6v`, `pa6r`, `pa6f`, `pa6f2`, `ab7v`, `ab7a`, `ab7t`, `ab7m`, `pa7v`, `pa7r`, `pa7f`, `pa7f2`, `ab8v`, `ab8a`, `ab8t`, `ab8m`, `pa8v`, `pa8r`, `pa8f`, `pa8f2`) VALUES ('$_POST[id_jadwal]', '$_POST[periode]', '$_POST[tujuan_1]', '$_POST[tujuan_2]', '$_POST[tujuan_3]', '$_POST[tujuan_4]', '$_POST[tujuan_5]', '$_POST[tujuan_6]', '$_POST[tujuan_7]', '$_POST[tujuan_7b]', '$_POST[konteks_1]', '$_POST[konteks_2]', '$_POST[konteks_3]', '$_POST[konteks_4]', '$_POST[konteks_5]', '$_POST[konteks_6]', '$_POST[konteks_6b]', '$_POST[pendekatan_1]', '$_POST[pendekatan_2]', '$_POST[pendekatan_3]', '$_POST[pendekatan_4]', '$_POST[pendekatan_5]', '$_POST[pendekatan_6]', '$_POST[askom]', '$_POST[orel_1]', '$_POST[konfirmorel_1]', '$_POST[orel_2]', '$_POST[konfirmorel_2]', '$_POST[orel_3]', '$_POST[konfirmorel_3]', '$_POST[leadasesor]', '$_POST[nama_leadasesor]', '$_POST[konfirmleadaseesor]', '$_POST[manspv]', '$_POST[nama_manspv]', '$_POST[konfirmmanspv]', '$_POST[tenagaahli]', '$_POST[nama_tenagaahli]', '$_POST[konfirmtenagaahli]', '$_POST[koordtraining]', '$_POST[nama_koordtraining]', '$_POST[konfirmkoordtraining]', '$_POST[asosiasi]', '$_POST[nama_asosiasi]', '$_POST[konfirmasosiasi]', '$_POST[stdkom]', '$_POST[sop]', '$_POST[manualbook]', '$_POST[stdkinerja]', '$_POST[lainnya]', '$_POST[nama_lainnya]', '$_POST[skema]', '$_POST[skk]', '$_POST[perangkat]', '$_POST[peraturan]', '$_POST[proaktif]', '$_POST[activelistening]', '$_POST[keterampilan1]', '$_POST[nama_ketlainnya1]', '$_POST[keterampilan2]', '$_POST[nama_ketlainnya2]', '$ab1v', '$ab1a', '$ab1t', '$ab1m', '$pa1v', '$pa1r', '$pa1f', '$pa1f2', '$ab2v', '$ab2a', '$ab2t', '$ab2m', '$pa2v', '$pa2r', '$pa2f', '$pa2f2', '$ab3v', '$ab3a', '$ab3t', '$ab3m', '$pa3v', '$pa3r', '$pa3f', '$pa3f2', '$ab4v', '$ab4a', '$ab4t', '$ab4m', '$pa4v', '$pa4r', '$pa4f', '$pa4f2', '$ab5v', '$ab5a', '$ab5t', '$ab5m', '$pa5v', '$pa5r', '$pa5f', '$pa5f2', '$ab6v', '$ab6a', '$ab6t', '$ab6m', '$pa6v', '$pa6r', '$pa6f', '$pa6f2', '$ab7v', '$ab7a', '$ab7t', '$ab7m', '$pa7v', '$pa7r', '$pa7f', '$pa7f2', '$ab8v', '$ab8a', '$ab8t', '$ab8m', '$pa8v', '$pa8r', '$pa8f', '$pa8f2')";
	$conn->query($sqlinsertmkva);
}
echo "<script>alert('Bagian 1 MKVA Telah Tersimpan/Terupdate'); window.location = 'media.php?module=form-fr-va2&idj=$_POST[id_jadwal]'</script>";

?>