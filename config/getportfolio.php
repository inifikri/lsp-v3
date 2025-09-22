<?php
 
$wiltuk = $_GET['jenis_portfolio'];

if ($wiltuk=="Pelatihan"){
	echo"<option value='narasumber'>Narasumber</option>
	<option value='pengajar'>Pengajar</option>
	<option value='moderator'>Moderator</option>
	<option value='panitia'>Panitia</option>
	<option value='peserta'>Peserta</option>";
}elseif($wiltuk=="Pengalaman Kerja"){
	echo"<option value='kerja'>Pengalaman Kerja</option>
	<option value='proyek'>Pengalaman Proyek</option>
	<option value='magang'>Pengalaman Magang</option>";
}else{
	echo"<option value='peneliti'>Peneliti</option>
	<option value='penulis'>Penulis</option>";
}
?>