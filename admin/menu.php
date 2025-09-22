<?php
error_reporting(0);
include "../config/koneksi.php";
$sessionidlogin=md5($_SESSION['namauser']);
echo "<li class='treeview'>
		<a href='?module=home'>
			<i class='fa fa-home'></i> <span>Home</span>
		</a>
	</li>";
$sqlcekidentitas="SELECT * FROM `identitas`";
$cekidentitas=$conn->query($sqlcekidentitas);
$jumiden=$cekidentitas->num_rows;
// cek LSP Konstruksi
$sqlceklspkonstruksi="SELECT * FROM `user_pupr`";
$ceklspkonsturksi=$conn->query($sqlceklspkonstruksi);
$jumlspkonstruksi=$ceklspkonsturksi->num_rows;
if ($jumiden==0){
	echo "<li class='treeview'>
			<a href='?module=setup'>
				<i class='fa fa-cogs'></i> <span>Pengaturan Awal</span>
			</a>
		  </li>";
}else{
	// Cek Hak Akses Modul
	$urlmodul="?module=pengaduan";
	$sqlcekmodulnya="SELECT * FROM `modul` WHERE `link`='$urlmodul'";
	$cekmodulnya=$conn->query($sqlcekmodulnya);
	$cmdl=$cekmodulnya->fetch_assoc();
	$sqlcekhakakses="SELECT * FROM `users_modul` WHERE `id_session`='$sessionidlogin' AND `id_modul`='$cmdl[id_modul]'";
	$cekhakakses=$conn->query($sqlcekhakakses);
	$berhakkah=$cekhakakses->num_rows;
	if ($_SESSION['leveluser']=='admin'|| $berhakkah>0){
		echo "<li class='treeview'>
				<a href='?module=pengaduan'>
					<i class='fa fa-users'></i> <span>Layanan Pengaduan</span>
				</a>
			  </li>";
	}
	// Cek Hak Akses Modul
	$urlmodul="?module=laporan";
	$sqlcekmodulnya="SELECT * FROM `modul` WHERE `link`='$urlmodul'";
	$cekmodulnya=$conn->query($sqlcekmodulnya);
	$cmdl=$cekmodulnya->fetch_assoc();
	$sqlcekhakakses="SELECT * FROM `users_modul` WHERE `id_session`='$sessionidlogin' AND `id_modul`='$cmdl[id_modul]'";
	$cekhakakses=$conn->query($sqlcekhakakses);
	$berhakkah=$cekhakakses->num_rows;
	if ($_SESSION['leveluser']=='admin'|| $berhakkah>0){
		echo "<li class='treeview'>
				<a href='#'>
						<i class='fa fa-book'></i> <span>Laporan Sertifikasi</span>
						<span class='pull-right-container'>
						<i class='fa fa-angle-left pull-right'></i>
						</span>
				</a>
				<ul class='treeview-menu'>
						<li><a href='?module=laporan'><i class='fa fa-circle-o'></i> Laporan Umum</a></li>
						<li><a href='?module=lapbulanan'><i class='fa fa-circle-o'></i> Laporan Bulanan</a></li>
						<li><a href='?module=lapbulananly'><i class='fa fa-circle-o'></i> Laporan Tahun Lalu</a></li>
						<li><a href='?module=lapkinasesor'><i class='fa fa-circle-o'></i> Laporan Kinerja Asesor</a></li>
						<li><a href='?module=lapcert'><i class='fa fa-circle-o'></i> Lap. Pemegang Sertifikat</a></li>
				</ul>
				</li>";
	}
	// Cek Hak Akses Modul
	$urlmodul="?module=lsp";
	$sqlcekmodulnya="SELECT * FROM `modul` WHERE `link`='$urlmodul'";
	$cekmodulnya=$conn->query($sqlcekmodulnya);
	$cmdl=$cekmodulnya->fetch_assoc();
	$sqlcekhakakses="SELECT * FROM `users_modul` WHERE `id_session`='$sessionidlogin' AND `id_modul`='$cmdl[id_modul]'";
	$cekhakakses=$conn->query($sqlcekhakakses);
	$berhakkah=$cekhakakses->num_rows;
	if ($_SESSION['leveluser']=='admin'|| $berhakkah>0){
		echo "<li class='treeview'>
				<a href='?module=lsp'>
					<i class='fa fa-graduation-cap'></i> <span>Lembaga Sertifikasi Profesi</span>
				</a>
			  </li>";
	}
	// Cek Hak Akses Modul
	$urlmodul="?module=docmutu";
	$sqlcekmodulnya="SELECT * FROM `modul` WHERE `link`='$urlmodul'";
	$cekmodulnya=$conn->query($sqlcekmodulnya);
	$cmdl=$cekmodulnya->fetch_assoc();
	$sqlcekhakakses="SELECT * FROM `users_modul` WHERE `id_session`='$sessionidlogin' AND `id_modul`='$cmdl[id_modul]'";
	$cekhakakses=$conn->query($sqlcekhakakses);
	$berhakkah=$cekhakakses->num_rows;
	if ($_SESSION['leveluser']=='admin'|| $berhakkah>0){
		echo "<li class='treeview'>
				<a href='?module=docmutu'>
					<i class='fa fa-check-square-o'></i> <span>Dokumen Mutu</span>
				</a>
			  </li>";
	}
	// Cek Hak Akses Modul
	$urlmodul="?module=skkni";
	$sqlcekmodulnya="SELECT * FROM `modul` WHERE `link`='$urlmodul'";
	$cekmodulnya=$conn->query($sqlcekmodulnya);
	$cmdl=$cekmodulnya->fetch_assoc();
	$sqlcekhakakses="SELECT * FROM `users_modul` WHERE `id_session`='$sessionidlogin' AND `id_modul`='$cmdl[id_modul]'";
	$cekhakakses=$conn->query($sqlcekhakakses);
	$berhakkah=$cekhakakses->num_rows;
	if ($_SESSION['leveluser']=='admin'|| $berhakkah>0){
		echo "<li class='treeview'>
				<a href='?module=skkni'>
					<i class='fa fa-legal'></i> <span>Standar Kompetensi</span>
				</a>
			  </li>";
	}
	// Cek Hak Akses Modul
	$urlmodul="?module=skema";
	$sqlcekmodulnya="SELECT * FROM `modul` WHERE `link`='$urlmodul'";
	$cekmodulnya=$conn->query($sqlcekmodulnya);
	$cmdl=$cekmodulnya->fetch_assoc();
	$sqlcekhakakses="SELECT * FROM `users_modul` WHERE `id_session`='$sessionidlogin' AND `id_modul`='$cmdl[id_modul]'";
	$cekhakakses=$conn->query($sqlcekhakakses);
	$berhakkah=$cekhakakses->num_rows;
	if ($_SESSION['leveluser']=='admin'|| $berhakkah>0){
		echo "<li class='treeview'>
				<a href='?module=skema'>
					<i class='fa fa-edit'></i> <span>Skema Sertifikasi</span>
				</a>
			  </li>";
	}
	// Cek Hak Akses Modul
	/*$urlmodul="?module=unitkompetensi";
	$sqlcekmodulnya="SELECT * FROM `modul` WHERE `link`='$urlmodul'";
	$cekmodulnya=$conn->query($sqlcekmodulnya);
	$cmdl=$cekmodulnya->fetch_assoc();
	$sqlcekhakakses="SELECT * FROM `users_modul` WHERE `id_session`='$sessionidlogin' AND `id_modul`='$cmdl[id_modul]'";
	$cekhakakses=$conn->query($sqlcekhakakses);
	$berhakkah=$cekhakakses->num_rows;
	if ($_SESSION['leveluser']=='admin'|| $berhakkah>0){
		echo "<li class='treeview'>
				<a href='?module=unitkompetensi'>
					<i class='fa fa-file-text-o'></i> <span>Unit Kompetensi</span>
				</a>
			</li> ";
	}*/
	// Cek Hak Akses Modul
	$urlmodul="?module=rekening";
	$sqlcekmodulnya="SELECT * FROM `modul` WHERE `link`='$urlmodul'";
	$cekmodulnya=$conn->query($sqlcekmodulnya);
	$cmdl=$cekmodulnya->fetch_assoc();
	$sqlcekhakakses="SELECT * FROM `users_modul` WHERE `id_session`='$sessionidlogin' AND `id_modul`='$cmdl[id_modul]'";
	$cekhakakses=$conn->query($sqlcekhakakses);
	$berhakkah=$cekhakakses->num_rows;
	if ($_SESSION['leveluser']=='admin'|| $berhakkah>0){
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
	}
	// Cek Hak Akses Modul
	$urlmodul="?module=jadwalasesmen";
	$sqlcekmodulnya="SELECT * FROM `modul` WHERE `link`='$urlmodul'";
	$cekmodulnya=$conn->query($sqlcekmodulnya);
	$cmdl=$cekmodulnya->fetch_assoc();
	$sqlcekhakakses="SELECT * FROM `users_modul` WHERE `id_session`='$sessionidlogin' AND `id_modul`='$cmdl[id_modul]'";
	$cekhakakses=$conn->query($sqlcekhakakses);
	$berhakkah=$cekhakakses->num_rows;
	if ($_SESSION['leveluser']=='admin'|| $berhakkah>0){
		echo "<li class='treeview'>
				<a href='#'>
						<i class='fa fa-calendar'></i> <span>Event dan Jadwal</span>
						<span class='pull-right-container'>
						<i class='fa fa-angle-left pull-right'></i>
						</span>
				</a>
				<ul class='treeview-menu'>
						<li><a href='?module=carijadwal'><i class='fa fa-circle-o'></i> Cari Jadwal</a></li>
						<li><a href='?module=event'><i class='fa fa-circle-o'></i> Event Uji Kompetensi</a></li>
						<li><a href='?module=jadwalasesmen'><i class='fa fa-circle-o'></i> Jadwal Uji Kompetensi</a></li>
						<li><a href='?module=arsipjadwalasesmen'><i class='fa fa-circle-o'></i> Arsip Jadwal Uji Kompetensi</a></li>
				</ul>
				</li>";
	}
	// Cek Hak Akses Modul
	$urlmodul="?module=tuk";
	$sqlcekmodulnya="SELECT * FROM `modul` WHERE `link`='$urlmodul'";
	$cekmodulnya=$conn->query($sqlcekmodulnya);
	$cmdl=$cekmodulnya->fetch_assoc();
	$sqlcekhakakses="SELECT * FROM `users_modul` WHERE `id_session`='$sessionidlogin' AND `id_modul`='$cmdl[id_modul]'";
	$cekhakakses=$conn->query($sqlcekhakakses);
	$berhakkah=$cekhakakses->num_rows;
	if ($_SESSION['leveluser']=='admin'|| $berhakkah>0){
		echo "<li class='treeview'>
				<a href='?module=tuk'>
					<i class='fa fa-university'></i> <span>Tempat Uji Kompetensi</span>
				</a>
			  </li>";
	}
	// Cek Hak Akses Modul
	$urlmodul="?module=asesi";
	$sqlcekmodulnya="SELECT * FROM `modul` WHERE `link`='$urlmodul'";
	$cekmodulnya=$conn->query($sqlcekmodulnya);
	$cmdl=$cekmodulnya->fetch_assoc();
	$sqlcekhakakses="SELECT * FROM `users_modul` WHERE `id_session`='$sessionidlogin' AND `id_modul`='$cmdl[id_modul]'";
	$cekhakakses=$conn->query($sqlcekhakakses);
	$berhakkah=$cekhakakses->num_rows;
	if ($_SESSION['leveluser']=='admin'|| $berhakkah>0){
		echo "<li class='treeview'>
				<a href='#'>
						<i class='fa fa-users'></i> <span>Asesi</span>
						<span class='pull-right-container'>
						<i class='fa fa-angle-left pull-right'></i>
						</span>
				</a>
				<ul class='treeview-menu'>
						<li><a href='?module=cariasesi'><i class='fa fa-circle-o'></i> Cari Asesi/Calon Asesi</a></li>
						<li><a href='?module=asesibv'><i class='fa fa-circle-o'></i> Belum Terjadwal</a></li>
						<li><a href='?module=asesiv'><i class='fa fa-circle-o'></i> Terjadwal</a></li>
						<li><a href='?module=asesibaru'><i class='fa fa-circle-o'></i> Tahun Ini</a></li>
						<li><a href='?module=asesitahunlalu'><i class='fa fa-circle-o'></i> Tahun Lalu</a></li>
						<li><a href='?module=asesithak'><i class='fa fa-circle-o'></i> Per Tahun Angkatan</a></li>
						<li><a href='?module=asesiprov'><i class='fa fa-circle-o'></i> Per Provinsi Asal</a></li>
						<li><a href='?module=asesikab'><i class='fa fa-circle-o'></i> Per Kota/Kab. Asal</a></li>
						<li><a href='?module=asesibypengusul'><i class='fa fa-circle-o'></i> Berdasarkan Pengusul</a></li>
						<li><a href='?module=asesik'><i class='fa fa-circle-o'></i> Kompeten</a></li>
						<li><a href='?module=asesiknocert'><i class='fa fa-circle-o'></i> Kompeten (Belum Sertifikat)</a></li>
						<li><a href='?module=asesikarsip'><i class='fa fa-circle-o'></i> Kompeten (Arsip)</a></li>
						<li><a href='?module=asesibk'><i class='fa fa-circle-o'></i> Belum Kompeten</a></li>
						<li><a href='?module=asesiban'><i class='fa fa-circle-o'></i> Diblokir</a></li>
						<li><a href='?module=importasesilama'><i class='fa fa-circle-o'></i> Impor Asesi (Terdahulu)</a></li>
				</ul>
				</li>";
	}
	// Cek Hak Akses Modul
	/* $urlmodul="?module=asesibaru";
	$sqlcekmodulnya="SELECT * FROM `modul` WHERE `link`='$urlmodul'";
	$cekmodulnya=$conn->query($sqlcekmodulnya);
	$cmdl=$cekmodulnya->fetch_assoc();
	$sqlcekhakakses="SELECT * FROM `users_modul` WHERE `id_session`='$sessionidlogin' AND `id_modul`='$cmdl[id_modul]'";
	$cekhakakses=$conn->query($sqlcekhakakses);
	$berhakkah=$cekhakakses->num_rows;
	if ($_SESSION['leveluser']=='admin'|| $berhakkah>0){
		echo "<li class='treeview'>
				<a href='?module=asesibaru'>
					<i class='fa fa-users'></i> <span>Calon Asesi (Baru)</span>
				</a>
			  </li>";
	} */
	// Cek Hak Akses Modul
	$urlmodul="?module=asesor";
	$sqlcekmodulnya="SELECT * FROM `modul` WHERE `link`='$urlmodul'";
	$cekmodulnya=$conn->query($sqlcekmodulnya);
	$cmdl=$cekmodulnya->fetch_assoc();
	$sqlcekhakakses="SELECT * FROM `users_modul` WHERE `id_session`='$sessionidlogin' AND `id_modul`='$cmdl[id_modul]'";
	$cekhakakses=$conn->query($sqlcekhakakses);
	$berhakkah=$cekhakakses->num_rows;
	if ($_SESSION['leveluser']=='admin'|| $berhakkah>0){
		echo "<li class='treeview'>
				<a href='?module=asesor'>
					<i class='fa fa-users'></i> <span>Asesor</span>
				</a>
			  </li>";
	}
	// Cek Hak Akses Modul
	$urlmodul="?module=komite";
	$sqlcekmodulnya="SELECT * FROM `modul` WHERE `link`='$urlmodul'";
	$cekmodulnya=$conn->query($sqlcekmodulnya);
	$cmdl=$cekmodulnya->fetch_assoc();
	$sqlcekhakakses="SELECT * FROM `users_modul` WHERE `id_session`='$sessionidlogin' AND `id_modul`='$cmdl[id_modul]'";
	$cekhakakses=$conn->query($sqlcekhakakses);
	$berhakkah=$cekhakakses->num_rows;
	if ($_SESSION['leveluser']=='admin'|| $berhakkah>0){
		echo "<li class='treeview'>
				<a href='?module=komite'>
					<i class='fa fa-gavel'></i> <span>Komite Teknis</span>
				</a>
			  </li>";
	}
	// Cek Hak Akses Modul
	$urlmodul="?module=inbox";
	$sqlcekmodulnya="SELECT * FROM `modul` WHERE `link`='$urlmodul'";
	$cekmodulnya=$conn->query($sqlcekmodulnya);
	$cmdl=$cekmodulnya->fetch_assoc();
	$sqlcekhakakses="SELECT * FROM `users_modul` WHERE `id_session`='$sessionidlogin' AND `id_modul`='$cmdl[id_modul]'";
	$cekhakakses=$conn->query($sqlcekhakakses);
	$berhakkah=$cekhakakses->num_rows;
	if ($_SESSION['leveluser']=='admin'|| $berhakkah>0){
		echo "<li class='treeview'>
				<a href='#'>
						<i class='fa fa-envelope'></i> <span>SMS Notifikasi</span>
						<span class='pull-right-container'>
						<i class='fa fa-angle-left pull-right'></i>
						</span>
				</a>
				<ul class='treeview-menu'>
						<li><a href='?module=inbox'><i class='fa fa-circle-o'></i> Pesan Masuk</a></li>
						<li><a href='?module=sent'><i class='fa fa-circle-o'></i> Pesan Keluar</a></li>
				</ul>
				</li>";
	}
	// Cek Hak Akses Modul
	$urlmodul="?module=konten";
	$sqlcekmodulnya="SELECT * FROM `modul` WHERE `link`='$urlmodul'";
	$cekmodulnya=$conn->query($sqlcekmodulnya);
	$cmdl=$cekmodulnya->fetch_assoc();
	$sqlcekhakakses="SELECT * FROM `users_modul` WHERE `id_session`='$sessionidlogin' AND `id_modul`='$cmdl[id_modul]'";
	$cekhakakses=$conn->query($sqlcekhakakses);
	$berhakkah=$cekhakakses->num_rows;
	if ($_SESSION['leveluser']=='admin'|| $berhakkah>0){
		echo "<li class='treeview'>
				<a href='?module=konten'>
					<i class='fa fa-object-group'></i> <span>Konten Frontpage Website</span>
				</a>
			</li> ";
	}
	// Cek Hak Akses Modul
	$urlmodul="?module=users";
	$sqlcekmodulnya="SELECT * FROM `modul` WHERE `link`='$urlmodul'";
	$cekmodulnya=$conn->query($sqlcekmodulnya);
	$cmdl=$cekmodulnya->fetch_assoc();
	$sqlcekhakakses="SELECT * FROM `users_modul` WHERE `id_session`='$sessionidlogin' AND `id_modul`='$cmdl[id_modul]'";
	$cekhakakses=$conn->query($sqlcekhakakses);
	$berhakkah=$cekhakakses->num_rows;
	if ($_SESSION['leveluser']=='admin'|| $berhakkah>0){
		echo "<li class='treeview'>
				<a href='#'>
						<i class='fa fa-gears'></i> <span>Manajemen</span>
						<span class='pull-right-container'>
						<i class='fa fa-angle-left pull-right'></i>
						</span>
				</a>
				<ul class='treeview-menu'>
						<li><a href='?module=menu'><i class='fa fa-circle-o'></i> Menu FrontPage</a></li>
						<li><a href='?module=users'><i class='fa fa-circle-o'></i> Pengguna</a></li>
						<li><a href='?module=pengusul'><i class='fa fa-circle-o'></i> Pengusul</a></li>
						<li><a href='?module=smtp'><i class='fa fa-circle-o'></i> Pengaturan SMTP</a></li>
						<li><a href='?module=devsyarat'><i class='fa fa-circle-o'></i> Pengaturan Dok. Asesi</a></li>
						<li><a href='?module=akunbnsp'><i class='fa fa-circle-o'></i> Akun Sistem BNSP</a></li>";
						if ($jumlspkonstruksi>0){
							echo "<li><a href='?module=akunpupr'><i class='fa fa-circle-o'></i> Akun Portal PUPR</a></li>";
						}
				echo "</ul>
			</li>";
	}
	// Cek Hak Akses Modul
	$urlmodul="?module=inlapkeu";
	$sqlcekmodulnya="SELECT * FROM `modul` WHERE `link`='$urlmodul'";
	$cekmodulnya=$conn->query($sqlcekmodulnya);
	$cmdl=$cekmodulnya->fetch_assoc();
	$sqlcekhakakses="SELECT * FROM `users_modul` WHERE `id_session`='$sessionidlogin' AND `id_modul`='$cmdl[id_modul]'";
	$cekhakakses=$conn->query($sqlcekhakakses);
	$berhakkah=$cekhakakses->num_rows;
	if ($_SESSION['leveluser']=='admin'|| $berhakkah>0){
		echo "<li class='treeview'>
				<a href='#'>
						<i class='fa fa-money'></i> <span>Keuangan</span>
						<span class='pull-right-container'>
						<i class='fa fa-angle-left pull-right'></i>
						</span>
				</a>
				<ul class='treeview-menu'>
						<li><a href='?module=inlapkeu'><i class='fa fa-circle-o'></i> Input Transaksi Keuangan</a></li>
						<!--<li><a href='?module=neraca'><i class='fa fa-circle-o'></i> Laporan Neraca</a></li>
						<li><a href='?module=rugilaba'><i class='fa fa-circle-o'></i> Laporan Rugi Laba</a></li>-->
				</ul>
			</li>";
	}
	// Cek Hak Akses Modul
	$urlmodul="?module=persuratan";
	$sqlcekmodulnya="SELECT * FROM `modul` WHERE `link`='$urlmodul'";
	$cekmodulnya=$conn->query($sqlcekmodulnya);
	$cmdl=$cekmodulnya->fetch_assoc();
	$sqlcekhakakses="SELECT * FROM `users_modul` WHERE `id_session`='$sessionidlogin' AND `id_modul`='$cmdl[id_modul]'";
	$cekhakakses=$conn->query($sqlcekhakakses);
	$berhakkah=$cekhakakses->num_rows;
	if ($_SESSION['leveluser']=='admin'|| $berhakkah>0){
		echo "<li class='treeview'>
				<a href='#'>
						<i class='fa fa-paper-plane'></i> <span>Persuratan</span>
						<span class='pull-right-container'>
						<i class='fa fa-angle-left pull-right'></i>
						</span>
				</a>
				<ul class='treeview-menu'>
						<li><a href='?module=persuratan'><i class='fa fa-circle-o'></i> Data Persuratan</a></li>";
				$sqljenissurat="SELECT * FROM `surat_jenis` ORDER BY `id`";
				$jenissurat=$conn->query($sqljenissurat);
				echo "<li>
					<a href='?module=arsip'>
					<i class='fa fa-circle-o'></i> <span>Arsip Persuratan</span>
					</a>
				</li> ";
				while ($jns=$jenissurat->fetch_assoc()){
					echo "<li>
						<a href='?module=arsip&ids=$jns[id]'>
						<i class='fa fa-circle-o'></i> <span>$jns[jenis_surat]</span>
						</a>
					</li> ";
				}
				echo "</ul>
			</li>";
	}
      if ($jumlspkonstruksi>0){
	// Cek Hak Akses Modul
	$urlmodul="?module=permohonanskk";
	$sqlcekmodulnya="SELECT * FROM `modul` WHERE `link`='$urlmodul'";
	$cekmodulnya=$conn->query($sqlcekmodulnya);
	$cmdl=$cekmodulnya->fetch_assoc();
	$sqlcekhakakses="SELECT * FROM `users_modul` WHERE `id_session`='$sessionidlogin' AND `id_modul`='$cmdl[id_modul]'";
	$cekhakakses=$conn->query($sqlcekhakakses);
	$berhakkah=$cekhakakses->num_rows;
	if ($_SESSION['leveluser']=='admin'|| $berhakkah>0){
		echo "<li class='treeview'>
				<a href='#'>
					<i class='fa fa-object-group'></i> <span>Permohonan SKK PUPR</span>
					<span class='pull-right-container'>
					<i class='fa fa-angle-left pull-right'></i>
					</span>
				</a>
				<ul class='treeview-menu'>
					<li><a href='#'>
					<i class='fa fa-circle-o'></i> <span>Master Data</span>
					<span class='pull-right-container'>
					<i class='fa fa-angle-left pull-right'></i>
					</span>
					</a>
						<ul class='treeview-menu'>
						<li><a href='?module=getasesor'><i class='fa fa-circle-o'></i> Daftar Asesor</a></li>
						<li><a href='?module=gettuk'><i class='fa fa-circle-o'></i> Daftar TUK</a></li>
						<li><a href='?module=getmetode'><i class='fa fa-circle-o'></i> Daftar Metode Uji</a></li>
						<li><a href='?module=getpenyelenggaraan'><i class='fa fa-circle-o'></i> Daftar Penyelenggaraan Uji</a></li>
						<li><a href='?module=statusskk'><i class='fa fa-circle-o'></i> Daftar Status</a></li>
						<li><a href='?module=jabatankerjaskk'><i class='fa fa-circle-o'></i> Daftar Jabatan Kerja</a></li>
						</ul>
					</li>
					<li><a href='?module=permohonanskk'><i class='fa fa-circle-o'></i> Daftar Pemohon</a></li>
					<li><a href='?module=caripemohonskk'><i class='fa fa-circle-o'></i> Cari Pemohon SKK</a></li>
					<li><a href='?module=permohonanskkfg'><i class='fa fa-circle-o'></i> Daftar Pemohon FG</a></li>
					<li><a href='?module=caripemohonskkfg'><i class='fa fa-circle-o'></i> Cari Pemohon SKK FG</a></li>
					</ul>
			</li> ";
	}
	// Cek Hak Akses Modul
	$urlmodul="?module=bnspjadwal";
	$sqlcekmodulnya="SELECT * FROM `modul` WHERE `link`='$urlmodul'";
	$cekmodulnya=$conn->query($sqlcekmodulnya);
	$cmdl=$cekmodulnya->fetch_assoc();
	$sqlcekhakakses="SELECT * FROM `users_modul` WHERE `id_session`='$sessionidlogin' AND `id_modul`='$cmdl[id_modul]'";
	$cekhakakses=$conn->query($sqlcekhakakses);
	$berhakkah=$cekhakakses->num_rows;
	if ($_SESSION['leveluser']=='admin'|| $berhakkah>0){
		echo "<li class='treeview'>
				<a href='#'>
						<i class='fa fa-check-square-o'></i> <span>Data Sistem BNSP</span>
						<span class='pull-right-container'>
						<i class='fa fa-angle-left pull-right'></i>
						</span>
				</a>
				<ul class='treeview-menu'>
						<li><a href='?module=bnspklasifikasi'><i class='fa fa-circle-o'></i> Master Data Klasifikasi</a></li>
						<li><a href='?module=bnspjadwal'><i class='fa fa-circle-o'></i> Data Jadwal</a></li>
						<li><a href='?module=bnsptuk'><i class='fa fa-circle-o'></i> Data TUK</a></li>
				</ul>
			</li>";
	}
     }
}
echo "<li class='treeview'>
		<a href='?module=password'>
			<i class='fa fa-unlock-alt'></i> <span>Ubah Sandi (Password)</span>
		</a>
	</li> ";
?>