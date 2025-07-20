<?php
include "../config/koneksi.php";

 

	echo "<li class='treeview'>
			<a href='?module=home'>
				<i class='fa fa-home'></i> <span>Home</span>
			</a>
		</li>";
	$sqlcekidentitas="SELECT * FROM `identitas`";
	$cekidentitas=$conn->query($sqlcekidentitas);
	$jumiden=$cekidentitas->num_rows;
	if ($jumiden>0){
	echo "<li class='treeview'>
			<a href='?module=tuk'>
				<i class='fa fa-university'></i> <span>Tempat Uji Kompetensi</span>
			</a>
		  </li>";
	/*echo "<li class='treeview'>
			<a href='?module=asesi'>
				<i class='fa fa-users'></i> <span>Asesi</span>
			</a>
		  </li>";*/
	echo "<li class='treeview'>
          	<a href='#'>
            		<i class='fa fa-users'></i> <span>Asesi</span>
            		<span class='pull-right-container'>
              		<i class='fa fa-angle-left pull-right'></i>
            		</span>
          	</a>
          	<ul class='treeview-menu'>
            		<li><a href='?module=asesithak'><i class='fa fa-circle-o'></i> Tahun Angkatan</a></li>
            		<li><a href='?module=asesiprov'><i class='fa fa-circle-o'></i> Provinsi Asal</a></li>
            		<li><a href='?module=asesikab'><i class='fa fa-circle-o'></i> Kota/ Kabupaten Asal</a></li>
            		<li><a href='?module=asesik'><i class='fa fa-circle-o'></i> Kompeten</a></li>
            		<li><a href='?module=asesibk'><i class='fa fa-circle-o'></i> Belum Kompeten</a></li>
            		<li><a href='?module=asesibv'><i class='fa fa-circle-o'></i> Belum Verifikasi</a></li>
            		<li><a href='?module=asesiv'><i class='fa fa-circle-o'></i> Terverifikasi</a></li>
            		<li><a href='?module=asesiban'><i class='fa fa-circle-o'></i> Diblokir</a></li>
          	</ul>
        	</li>";

	echo "<li class='treeview'>
			<a href='?module=asesibaru'>
				<i class='fa fa-users'></i> <span>Calon Asesi (Baru)</span>
			</a>
		  </li>";
	echo "<li class='treeview'>
			<a href='?module=jadwalasesmen'>
				<i class='fa fa-calendar'></i> <span>Jadwal Uji Kompetensi</span>
			</a>
		  </li>";

	echo "<li class='treeview'>
			<a href='?module=skema'>
				<i class='fa fa-edit'></i> <span>Skema Sertifikasi</span>
			</a>
		  </li>";

	echo "<li class='treeview'>
			<a href='?module=unitkompetensi'>
				<i class='fa fa-file-text-o'></i> <span>Unit Kompetensi</span>
			</a>
		</li> ";
	echo "<li class='treeview'>
          	<a href='#'>
            		<i class='fa fa-folder'></i> <span>Biaya</span>
            		<span class='pull-right-container'>
              		<i class='fa fa-angle-left pull-right'></i>
            		</span>
          	</a>
          	<ul class='treeview-menu'>
            		<li><a href='?module=rekening'><i class='fa fa-circle-o'></i> Rekening Bank</a></li>
            		<li><a href='?module=biayauji'><i class='fa fa-circle-o'></i> Biaya</a></li>
          	</ul>
        	</li>";
	echo "<li class='treeview'>
          	<a href='#'>
            		<i class='fa fa-envelope'></i> <span>SMS Notifikasi</span>
            		<span class='pull-right-container'>
              		<i class='fa fa-angle-left pull-right'></i>
            		</span>
          	</a>
          	<ul class='treeview-menu'>
            		<li><a href='?module=pesanmasuk'><i class='fa fa-circle-o'></i> Pesan Masuk</a></li>
          	</ul>
        	</li>";

	}
	echo "<li class='treeview'>
			<a href='?module=password'>
				<i class='fa fa-unlock-alt'></i> <span>Ubah Sandi (Password)</span>
			</a>
		</li> ";



?>
