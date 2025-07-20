<?php
ini_set('display_errors', 0);
include "config/koneksi.php";

$sqllogin="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_SESSION[namauser]'";
$login=$conn->query($sqllogin);
$ketemu=$login->num_rows;
$rowAgen=$login->fetch_assoc();
if ($rowAgen['tgl_lahir']!='0000-00-00'){
	echo "<li class='treeview'>
			<a href='?module=home'>
				<i class='fa fa-home'></i> <span>Home</span>
			</a>
		</li>";
	echo "<li class='treeview'>
			<a href='?module=profil'>
				<i class='fa fa-user'></i> <span>Profil Anda</span>
			</a>
		  </li>";
}else{
	echo "<li class='treeview'>
			<a href='?module=editprofil'>
				<i class='fa fa-user'></i> <span>Lengkapi Profil</span>
			</a>
		  </li>";
}
if ($rowAgen['tgl_lahir']!='0000-00-00'){

	echo "<li class='treeview'>
			<a href='?module=skema'>
				<i class='fa fa-edit'></i> <span>Lihat Skema Sertifikasi</span>
			</a>
		  </li>";

	echo "<li class='treeview'>
			<a href='?module=asesmen'>
				<i class='fa fa-file-text-o'></i> <span>Asesmen Anda</span>
			</a>
		</li> ";
	echo "<li class='treeview'>";
          	// <a href='?module=konfpay'>
            // 		<i class='fa fa-money'></i> <span>Konfirmasi Pembayaran</span>
            // 		<!--<span class='pull-right-container'>
            //   		<i class='fa fa-angle-left pull-right'></i>
            // 		</span>-->
          	// </a>
          	echo "<!--<ul class='treeview-menu'>
            		<li><a href='?module=rekening'><i class='fa fa-circle-o'></i> Rekening Bank</a></li>
            		<li><a href='?module=biayauji'><i class='fa fa-circle-o'></i> Biaya</a></li>
          	</ul>-->
        	</li>";
}
	echo "<li class='treeview'>
			<a href='?module=password'>
				<i class='fa fa-unlock-alt'></i> <span>Ubah Sandi (Password)</span>
			</a>
		</li> ";



?>
