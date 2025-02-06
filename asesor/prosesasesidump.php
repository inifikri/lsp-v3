<?php
ini_set('display_errors',1); 
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['namauser'])){
header("Location:index.php");
}	

include "../config/koneksi.php";
include "../config/library.php";
include "../config/fungsi_indotgl.php";


$sqlasesi="SELECT * FROM `asesi_dump2` ORDER BY `tgl_daftar` ASC, `nama` ASC";
$asesi=$conn->query($sqlasesi);
$no=1;
echo "<table>";
while ($as=$asesi->fetch_assoc()){
	$sqlinput="SELECT * FROM `data_wilayah` WHERE `id_induk_wilayah` LIKE '$as[kota]%' AND `nm_wil` LIKE '%$as[kecamatan]%'";
	$input=$conn->query($sqlinput);
	$as2=$input->fetch_assoc();
	if ($as2['id_wil']!=""){
		$idkecamatan=$as2['id_wil'];
		$idkecamatan2=$as2['id_wil'];
	}else{
		$idkecamatan=$as['kecamatan'];
		$idkecamatan2="<font color='red'>Tidak Ditemukan Kecamatan</font>";
	}
	echo "<tr><td>$no $as[nama]</td><td>$as[no_ktp]</td><td>$idkecamatan2</td></tr>";
	$sqlinputdata="INSERT INTO `asesi`(`no_pendaftaran`, `password`, `nama`, `tmp_lahir`, `tgl_lahir`, `usia`, `email`, `nohp`, `no_ktp`, `alamat`, `RT`, `RW`, `kelurahan`, `kecamatan`, `kota`, `propinsi`, `kodepos`, `pendidikan`, `agama`, `prodi`, `tahun_lulus`, `kebangsaan`, `jenis_kelamin`, `foto`, `ktp`, `kk`, `ijazah`, `transkrip`, `suket`, `pekerjaan`, `jabatan`, `nama_kantor`, `alamat_kantor`, `telp_kantor`, `fax_kantor`, `email_kantor`, `tgl_daftar`, `blokir`, `verifikasi`) VALUES ('$as[no_pendaftaran]', '$as[password]', '$as[nama]', '$as[tmp_lahir]', '$as[tgl_lahir]', '$as[usia]', '$as[email]', '$as[nohp]', '$as[no_ktp]', '$as[alamat]', '$as[RT]', '$as[RW]', '$as[kelurahan]', '$idkecamatan', '$as[kota]', '$as[propinsi]', '$as[kodepos]', '$as[pendidikan]', '$as[agama]', '$as[prodi]', '$as[tahun_lulus]', '$as[kebangsaan]', '$as[jenis_kelamin]', '$as[foto]', '$as[ktp]', '$as[kk]', '$as[ijazah]', '$as[transkrip]', '$as[suket]', '$as[pekerjaan]', '$as[jabatan]', '$as[nama_kantor]', '$as[alamat_kantor]', '$as[telp_kantor]', '$as[fax_kantor]', '$as[email_kantor]', '$as[tgl_daftar]', '$as[blokir]', '$as[verifikasi]')";
	$conn->query($sqlinputdata);
	$no++;
}
echo "</table>";
?>