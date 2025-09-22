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
			<a href='?module=verifikasituk'>
				<i class='fa fa-calendar-check-o'></i> <span>Jadwal Verifikasi TUK</span>
			</a>
		  </li>";
echo "<li class='treeview'>
			<a href='?module=verifikasitukjarakjauh'>
				<i class='fa fa-calendar-check-o'></i> <span>Verifikasi TUK Jarak Jauh</span>
			</a>
		  </li>";
echo "<li class='treeview'>
			<a href='?module=peninjauasesmen'>
				<i class='fa fa-calendar-check-o'></i> <span>Jadwal Meninjau Instrumen</span>
			</a>
		  </li>";
echo "<li class='treeview'>
			<a href='?module=penugasanmkva'>
				<i class='fa fa-calendar-check-o'></i> <span>Jadwal MKVA</span>
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
