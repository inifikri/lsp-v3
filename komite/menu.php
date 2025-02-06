<?php
include "../config/koneksi.php";

 

	echo "<li class='treeview'>
			<a href='?module=home'>
				<i class='fa fa-home'></i> <span>Home</span>
			</a>
		</li>";
	echo "<li class='treeview'>
			<a href='?module=jadwalasesmen'>
				<i class='fa fa-calendar'></i> <span>Jadwal Uji Kompetensi</span>
			</a>
		  </li>";
	echo "<li class='treeview'>
			<a href='?module=pesanmasuk'>
				<i class='fa fa-envelope'></i> <span>SMS Notifikasi</span>
			</a>
		  </li>";
	echo "<li class='treeview'>
			<a href='?module=password'>
				<i class='fa fa-unlock-alt'></i> <span>Ubah Sandi (Password)</span>
			</a>
		</li> ";



?>
