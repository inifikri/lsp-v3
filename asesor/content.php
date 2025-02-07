<?php
	//ini_set('display_errors',1); 
	//error_reporting(E_ALL);
	include "../config/koneksi.php";
	include "../config/library.php";
	include "../config/fungsi_indotgl.php";
	include "../config/fungsi_combobox.php";
	include "../config/class_paging.php";
	include "../config/fungsi_rupiah.php";
	include "../classes/class.phpmailer.php";
	ini_set('display_errors', 0);
	// UPDATE @FHM-PPM 28 JULY 2023 : PENAMBAHAN FUNGSI base_url()
	if (!function_exists('base_url')) {
		function base_url($atRoot = FALSE, $atCore = FALSE, $parse = FALSE)
		{
			if (isset($_SERVER['HTTP_HOST'])) {
				$http = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
				$hostname = $_SERVER['HTTP_HOST'];
				$dir = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
				$core = preg_split('@/@', str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath(dirname(__FILE__))), NULL, PREG_SPLIT_NO_EMPTY);
				$core = $core[0];
				$tmplt = $atRoot ? ($atCore ? "%s://%s/%s/" : "%s://%s/") : ($atCore ? "%s://%s/%s/" : "%s://%s%s");
				$end = $atRoot ? ($atCore ? $core : $hostname) : ($atCore ? $core : $dir);
				$base_url = sprintf($tmplt, $http, $hostname, $end);
			} else $base_url = 'http://localhost/';
			if ($parse) {
				$base_url = parse_url($base_url);
				if (isset($base_url['path'])) if (
					$base_url['path'] == '/'
				) $base_url['path'] = '';
			}
			return $base_url;
		}
	}
	$base_url = base_url();

	$sqlidentitas = "SELECT * FROM `identitas`";
	$identitas = $conn->query($sqlidentitas);
	$iden = $identitas->fetch_assoc();
	$urldomain = $iden['url_domain'];
	// cek apakah LSP Kontruksi
	$sqlakunpupr = "SELECT * FROM `user_pupr`";
	$akunpupr = $conn->query($sqlakunpupr);
	$pupr = $akunpupr->num_rows;
	// Bagian Home
	if ($_GET['module'] == 'home') {
		if (!empty($_SESSION['namauser'])) {
			$sqljumasesor0 = "SELECT * FROM `asesor` WHERE `no_ktp`='$_SESSION[namauser]'";
			$qjumasesor0 = $conn->query($sqljumasesor0);
			$asr0 = $qjumasesor0->fetch_assoc();
			$tahun = date("Y");
			//$jumtuk=0;
			//Jumlah Skema Sertifikasi
			$sqljumskema = "SELECT DISTINCT `id_skemakkni` FROM `asesi_apl02` WHERE `id_asesor`='$asr0[id]'";
			$qjumskema = $conn->query($sqljumskema);
			$jumskema = $qjumskema->num_rows;
			//Jumlah TUK Sertifikasi
			$sqljumtuk = "SELECT DISTINCT `id_jadwal` FROM `jadwal_asesor` WHERE `id_asesor`='$asr0[id]'";
			$qjumtuk = $conn->query($sqljumtuk);
			$jumtuk = $qjumtuk->num_rows;
			//Jumlah Portofolio Asesor Sertifikasi
			$sqljumasesor = "SELECT DISTINCT `id_asesi` FROM `asesi_apl02` WHERE `id_asesor`='$asr0[id]'";
			$qjumasesor = $conn->query($sqljumasesor);
			$jumasesor = $qjumasesor->num_rows;
			//Jumlah Asesor Sertifikasi
			$sqljumasesi = "SELECT DISTINCT `id_asesi` FROM `asesi_apl02` WHERE `id_asesor`='$asr0[id]' AND `waktu` LIKE '$tahun%'";
			$qjumasesi = $conn->query($sqljumasesi);
			$jumasesi = $qjumasesi->num_rows;
			$tampilperiode = date("m-Y");
			$tahun = date("Y");
			echo "<!-- Content Header (Page header) -->
			<section class='content-header'>
				<h1>
				Dashboard
				<small>Sistem Informasi Lembaga Sertifikasi Profesi</small>
				</h1>
				<ol class='breadcrumb'>
				<li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
				<li class='active'>Dashboard</li>
				</ol>
			</section>
			<!-- Main content -->
			<section class='content'>
			<!-- Small boxes (Stat box) -->
				<div class='row'>
				<div class='col-lg-3 col-xs-6'>
					<!-- small box -->
					<div class='small-box bg-aqua'>
					<div class='inner'>
						<h3>$jumtuk</h3>
						<p>Jadwal Asesmen Anda</p>
					</div>
					<div class='icon'>
						<i class='fa fa-calendar-check-o'></i>
					</div>
					</div>
				</div>
				<!-- ./col -->
				<div class='col-lg-3 col-xs-6'>
					<!-- small box -->
					<div class='small-box bg-blue'>
					<div class='inner'>
						<h3>$jumskema</h3>
						<p>Skema Asesmen Anda</p>
					</div>
					<div class='icon'>
						<i class='fa fa-users'></i>
					</div>
					</div>
				</div>
				<!-- ./col -->
				<div class='col-lg-3 col-xs-6'>
					<!-- small box -->
					<div class='small-box bg-green'>
					<div class='inner'>
						<h3>$jumasesor</h3>
						<p>Portofolio Asesmen Anda</p>
					</div>
					<div class='icon'>
						<i class='fa fa-user'></i>
					</div>
					</div>
				</div>
				<!-- ./col -->
				<div class='col-lg-3 col-xs-6'>
					<!-- small box -->
					<div class='small-box bg-yellow'>
					<div class='inner'>
						<h3>$jumasesi</h3>
						<p>Asesi Anda tahun $tahun</p>
					</div>
					<div class='icon'>
						<i class='fa fa-user-plus'></i>
					</div>
					</div>
				</div>
				<!-- ./col -->
				</div>
				<!-- /.row -->
			<div class='row'>
				<div class='col-md-6'>
					<!-- AREA CHART -->
					<div class='box box-primary'>
					<div class='box-header with-border'>
						<h3 class='box-title'>Pendaftar dan Kandidat Tahun $tahun</h3>
						<div class='box-tools pull-right'>
						<button type='button' class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i>
						</button>
						<button type='button' class='btn btn-box-tool' data-widget='remove'><i class='fa fa-times'></i></button>
						</div>
					</div>
					<div class='box-body'>
						<div class='chart'>
						<canvas id='line-chart' width='800' height='450'></canvas>
						</div>
					</div>
					<!-- /.box-body -->
					</div>
					<!-- /.box -->
				<!-- DONUT CHART -->
					<div class='box box-warning'>
					<div class='box-header with-border'>
						<h3 class='box-title'>Jenis Kelamin Asesi</h3>
						<div class='box-tools pull-right'>
						<button type='button' class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i>
						</button>
						<button type='button' class='btn btn-box-tool' data-widget='remove'><i class='fa fa-times'></i></button>
						</div>
					</div>
					<div class='box-body'>
						<div class='chart'>
						<canvas id='pie-chart' width='800' height='450'></canvas>
						</div>
					</div>
					<!-- /.box-body -->
					</div>
					<!-- /.box -->
				</div>
				<!-- /.col (LEFT) -->
				<div class='col-md-6'>
					<!-- BAR CHART -->
					<div class='box box-success'>
					<div class='box-header with-border'>
						<h3 class='box-title'>Kelompok Usia Asesi</h3>
						<div class='box-tools pull-right'>
						<button type='button' class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i>
						</button>
						<button type='button' class='btn btn-box-tool' data-widget='remove'><i class='fa fa-times'></i></button>
						</div>
					</div>
					<div class='box-body'>
						<div class='chart'>
						<canvas id='bar-chart' width='800' height='450'></canvas>
						</div>
					</div>
					<!-- /.box-body -->
					</div>
					<!-- /.box -->
					<div class='box box-info'>
					<div class='box-header with-border'>
						<h3 class='box-title'>Kemajuan Proses Administratif</h3>
						<div class='box-tools pull-right'>
						<button type='button' class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i>
						</button>
						<button type='button' class='btn btn-box-tool' data-widget='remove'><i class='fa fa-times'></i></button>
						</div>
					</div>
					<div class='box-body'>
							<p class='text-center'>
							<strong>Prosentase Proses Asesmen</strong>
							</p>";
			$sqlnumkona = "SELECT DISTINCT `id_asesi` FROM `asesi_doc`";
			$numkona0 = $conn->query($sqlnumkona);
			$numkona = $numkona0->num_rows;
			$sqlnumkon2a = "SELECT * FROM `asesi`";
			$numkon2a0 = $conn->query($sqlnumkon2a);
			$numkon2a = $numkon2a0->num_rows;
			$persentasea = round(($numkona / $numkon2a) * 100);
			echo "<div class='progress-group'>
							<span class='progress-text'>Data Asesi telah melengkapi berkas</span>
							<span class='progress-number'><b>$persentasea %</b> ($numkona/$numkon2a*)</span>
							<div class='progress sm'>
								<div class='progress-bar progress-bar-aqua' style='width: $persentasea%'></div>
							</div>
							</div>";
			$sqlnumkon = "SELECT * FROM `asesi_asesmen` WHERE `tgl_asesmen`!='0000-00-00'";
			$numkon0 = $conn->query($sqlnumkon);
			$numkon = $numkon0->num_rows;
			$sqlnumkon2 = "SELECT * FROM `asesi_asesmen`";
			$numkon20 = $conn->query($sqlnumkon2);
			$numkon2 = $numkon20->num_rows;
			$persentase = round(($numkon / $numkon2) * 100);
			echo "<div class='progress-group'>
							<span class='progress-text'>Data asesmen diproses (terjadwal)</span>
							<span class='progress-number'><b>$persentase %</b> ($numkon/$numkon2)</span>
							<div class='progress sm'>
								<div class='progress-bar progress-bar-blue' style='width: $persentase%'></div>
							</div>
							</div>
							<p class='text-center'>
							<strong>Prosentase Hasil Asesmen</strong>
							</p>";
			//if($_SESSION['leveluser']=='admin'){
			$sqlgetkompeten2 = "SELECT * FROM `asesi_asesmen` WHERE `status_asesmen`='K'";
			$getkompeten2 = $conn->query($sqlgetkompeten2);
			$numkonb = $getkompeten2->num_rows;
			$sqlgetkompeten = "SELECT * FROM `asesi_asesmen`";
			$getkompeten = $conn->query($sqlgetkompeten);
			$numkon2b = $getkompeten->num_rows;
			//$numkonb=mysql_num_rows(mysql_query("SELECT DISTINCT `NO_TEST` FROM `logexport`"));
			//$numkon2b=mysql_num_rows(mysql_query("SELECT DISTINCT `NIM` FROM `konversimktemp`"));
			//}else{
			//$numkonb=mysql_num_rows(mysql_query("SELECT DISTINCT `NO_TEST` FROM `logexport` WHERE `posisi`!='admin' AND `kodept`='$_SESSION[kodept]' AND `kodeprodi`='$_SESSION[kodeprodi]'"));
			//$numkon2b=mysql_num_rows(mysql_query("SELECT DISTINCT `NIM` FROM `konversimktemp` WHERE `kode_pt`='$_SESSION[kodept]'"));
			//}
			$persentaseb = round(($numkonb / $numkon2b) * 100);
			echo "<div class='progress-group'>
							<span class='progress-text'>Data Asesmen dinyatakan Kompeten</span>
							<span class='progress-number'><b>$persentaseb %</b> ($numkonb/$numkon2b)</span>
							<div class='progress sm'>
								<div class='progress-bar progress-bar-green' style='width: $persentaseb%'></div>
							</div>
							</div>";
			//if($_SESSION['leveluser']=='admin'){
			$sqlgetkompeten3 = "SELECT * FROM `asesi_asesmen` WHERE `status_asesmen`='BK'";
			$getkompeten3 = $conn->query($sqlgetkompeten3);
			$numkonc = $getkompeten3->num_rows;
			$sqlgetkompeten4 = "SELECT * FROM `asesi_asesmen`";
			$getkompeten4 = $conn->query($sqlgetkompeten4);
			$numkon2c = $getkompeten4->num_rows;
			//$numkonc=mysql_num_rows(mysql_query("SELECT DISTINCT `NO_TEST` FROM `logcetak`"));
			//$numkon2c=mysql_num_rows(mysql_query("SELECT DISTINCT `NIM` FROM `konversimktemp`"));
			//}else{
			//$numkonc=mysql_num_rows(mysql_query("SELECT DISTINCT `NO_TEST` FROM `logcetak` WHERE `posisi`!='admin' AND `kodept`='$_SESSION[kodept]' AND `kodeprodi`='$_SESSION[kodeprodi]'"));
			//$numkon2c=mysql_num_rows(mysql_query("SELECT DISTINCT `NIM` FROM `konversimktemp` WHERE `kode_pt`='$_SESSION[kodept]'"));
			//}
			$persentasec = round(($numkonc / $numkon2c) * 100);
			echo "<div class='progress-group'>
							<span class='progress-text'>Data Asesmen dinyatakan Belum Kompeten</span>
							<span class='progress-number'><b>$persentasec %</b> ($numkonc/$numkon2c)</span>
							<div class='progress sm'>
								<div class='progress-bar progress-bar-red' style='width: $persentasec%'></div>
							</div>
							</div>
					<p class='text-muted'>*) Data Asesi < Data asesmen, karena dimungkinkan asesi ikut lebih dari 1 skema</p>
					</div>
					<!-- /.box-body -->
					</div>
					<!-- /.box -->
				</div>
				<!-- /.col (RIGHT) -->
				</div>
				<!-- /.row -->
					<!-- /.box-footer -->
					</div>
					<!-- /.box -->
				</div>
				<!-- /.col -->
				</div>
				<!-- /.row -->
			</section>
			<!-- /.content -->";
			//=============================hitung untuk grafik=======================================
			$periode01 = $tahun . "01";
			$periode02 = $tahun . "02";
			$periode03 = $tahun . "03";
			$periode04 = $tahun . "04";
			$periode05 = $tahun . "05";
			$periode06 = $tahun . "06";
			$periode07 = $tahun . "07";
			$periode08 = $tahun . "08";
			$periode09 = $tahun . "09";
			$periode10 = $tahun . "10";
			$periode11 = $tahun . "11";
			$periode12 = $tahun . "12";
			$periode01b = $tahun . "-01";
			$periode02b = $tahun . "-02";
			$periode03b = $tahun . "-03";
			$periode04b = $tahun . "-04";
			$periode05b = $tahun . "-05";
			$periode06b = $tahun . "-06";
			$periode07b = $tahun . "-07";
			$periode08b = $tahun . "-08";
			$periode09b = $tahun . "-09";
			$periode10b = $tahun . "-10";
			$periode11b = $tahun . "-11";
			$periode12b = $tahun . "-12";
			$sqljumkandidat01 = "SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode01%'";
			$sqljumkandidat02 = "SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode02%'";
			$sqljumkandidat03 = "SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode03%'";
			$sqljumkandidat04 = "SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode04%'";
			$sqljumkandidat05 = "SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode05%'";
			$sqljumkandidat06 = "SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode06%'";
			$sqljumkandidat07 = "SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode07%'";
			$sqljumkandidat08 = "SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode08%'";
			$sqljumkandidat09 = "SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode09%'";
			$sqljumkandidat10 = "SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode10%'";
			$sqljumkandidat11 = "SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode11%'";
			$sqljumkandidat12 = "SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode12%'";
			// data terverifikasi
			$sqljumkandidat01b = "SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode01%' AND `verifikasi`='V'";
			$sqljumkandidat02b = "SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode02%' AND `verifikasi`='V'";
			$sqljumkandidat03b = "SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode03%' AND `verifikasi`='V'";
			$sqljumkandidat04b = "SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode04%' AND `verifikasi`='V'";
			$sqljumkandidat05b = "SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode05%' AND `verifikasi`='V'";
			$sqljumkandidat06b = "SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode06%' AND `verifikasi`='V'";
			$sqljumkandidat07b = "SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode07%' AND `verifikasi`='V'";
			$sqljumkandidat08b = "SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode08%' AND `verifikasi`='V'";
			$sqljumkandidat09b = "SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode09%' AND `verifikasi`='V'";
			$sqljumkandidat10b = "SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode10%' AND `verifikasi`='V'";
			$sqljumkandidat11b = "SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode11%' AND `verifikasi`='V'";
			$sqljumkandidat12b = "SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode12%' AND `verifikasi`='V'";
			// data terjadwal
			$sqljumterjadwal01b = "SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`!='' AND `tgl_asesmen` LIKE '$periode01b%'";
			$sqljumterjadwal02b = "SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`!='' AND `tgl_asesmen` LIKE '$periode02b%'";
			$sqljumterjadwal03b = "SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`!='' AND `tgl_asesmen` LIKE '$periode03b%'";
			$sqljumterjadwal04b = "SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`!='' AND `tgl_asesmen` LIKE '$periode04b%'";
			$sqljumterjadwal05b = "SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`!='' AND `tgl_asesmen` LIKE '$periode05b%'";
			$sqljumterjadwal06b = "SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`!='' AND `tgl_asesmen` LIKE '$periode06b%'";
			$sqljumterjadwal07b = "SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`!='' AND `tgl_asesmen` LIKE '$periode07b%'";
			$sqljumterjadwal08b = "SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`!='' AND `tgl_asesmen` LIKE '$periode08b%'";
			$sqljumterjadwal09b = "SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`!='' AND `tgl_asesmen` LIKE '$periode09b%'";
			$sqljumterjadwal10b = "SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`!='' AND `tgl_asesmen` LIKE '$periode10b%'";
			$sqljumterjadwal11b = "SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`!='' AND `tgl_asesmen` LIKE '$periode11b%'";
			$sqljumterjadwal12b = "SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`!='' AND `tgl_asesmen` LIKE '$periode12b%'";
			$jumkandidat01 = $conn->query($sqljumkandidat01);
			$kandidat01 = $jumkandidat01->num_rows;
			$jumkandidat02 = $conn->query($sqljumkandidat02);
			$kandidat02 = $jumkandidat02->num_rows;
			$jumkandidat03 = $conn->query($sqljumkandidat03);
			$kandidat03 = $jumkandidat03->num_rows;
			$jumkandidat04 = $conn->query($sqljumkandidat04);
			$kandidat04 = $jumkandidat04->num_rows;
			$jumkandidat05 = $conn->query($sqljumkandidat05);
			$kandidat05 = $jumkandidat05->num_rows;
			$jumkandidat06 = $conn->query($sqljumkandidat06);
			$kandidat06 = $jumkandidat06->num_rows;
			$jumkandidat07 = $conn->query($sqljumkandidat07);
			$kandidat07 = $jumkandidat07->num_rows;
			$jumkandidat08 = $conn->query($sqljumkandidat08);
			$kandidat08 = $jumkandidat08->num_rows;
			$jumkandidat09 = $conn->query($sqljumkandidat09);
			$kandidat09 = $jumkandidat09->num_rows;
			$jumkandidat10 = $conn->query($sqljumkandidat10);
			$kandidat10 = $jumkandidat10->num_rows;
			$jumkandidat11 = $conn->query($sqljumkandidat11);
			$kandidat11 = $jumkandidat11->num_rows;
			$jumkandidat12 = $conn->query($sqljumkandidat12);
			$kandidat12 = $jumkandidat12->num_rows;
			// Terverifikasi
			$jumkandidat01b = $conn->query($sqljumkandidat01b);
			$kandidat01b = $jumkandidat01b->num_rows;
			$jumkandidat02b = $conn->query($sqljumkandidat02b);
			$kandidat02b = $jumkandidat02b->num_rows;
			$jumkandidat03b = $conn->query($sqljumkandidat03b);
			$kandidat03b = $jumkandidat03b->num_rows;
			$jumkandidat04b = $conn->query($sqljumkandidat04b);
			$kandidat04b = $jumkandidat04b->num_rows;
			$jumkandidat05b = $conn->query($sqljumkandidat05b);
			$kandidat05b = $jumkandidat05b->num_rows;
			$jumkandidat06b = $conn->query($sqljumkandidat06b);
			$kandidat06b = $jumkandidat06b->num_rows;
			$jumkandidat07b = $conn->query($sqljumkandidat07b);
			$kandidat07b = $jumkandidat07b->num_rows;
			$jumkandidat08b = $conn->query($sqljumkandidat08b);
			$kandidat08b = $jumkandidat08b->num_rows;
			$jumkandidat09b = $conn->query($sqljumkandidat09b);
			$kandidat09b = $jumkandidat09b->num_rows;
			$jumkandidat10b = $conn->query($sqljumkandidat10b);
			$kandidat10b = $jumkandidat10b->num_rows;
			$jumkandidat11b = $conn->query($sqljumkandidat11b);
			$kandidat11b = $jumkandidat11b->num_rows;
			$jumkandidat12b = $conn->query($sqljumkandidat12b);
			$kandidat12b = $jumkandidat12b->num_rows;
			// Terjadwal
			$jumterjadwal01b = $conn->query($sqljumterjadwal01b);
			$terjadwal01b = $jumterjadwal01b->num_rows;
			$jumterjadwal02b = $conn->query($sqljumterjadwal02b);
			$terjadwal02b = $jumterjadwal02b->num_rows;
			$jumterjadwal03b = $conn->query($sqljumterjadwal03b);
			$terjadwal03b = $jumterjadwal03b->num_rows;
			$jumterjadwal04b = $conn->query($sqljumterjadwal04b);
			$terjadwal04b = $jumterjadwal04b->num_rows;
			$jumterjadwal05b = $conn->query($sqljumterjadwal05b);
			$terjadwal05b = $jumterjadwal05b->num_rows;
			$jumterjadwal06b = $conn->query($sqljumterjadwal06b);
			$terjadwal06b = $jumterjadwal06b->num_rows;
			$jumterjadwal07b = $conn->query($sqljumterjadwal07b);
			$terjadwal07b = $jumterjadwal07b->num_rows;
			$jumterjadwal08b = $conn->query($sqljumterjadwal08b);
			$terjadwal08b = $jumterjadwal08b->num_rows;
			$jumterjadwal09b = $conn->query($sqljumterjadwal09b);
			$terjadwal09b = $jumterjadwal09b->num_rows;
			$jumterjadwal10b = $conn->query($sqljumterjadwal10b);
			$terjadwal10b = $jumterjadwal10b->num_rows;
			$jumterjadwal11b = $conn->query($sqljumterjadwal11b);
			$terjadwal11b = $jumterjadwal11b->num_rows;
			$jumterjadwal12b = $conn->query($sqljumterjadwal12b);
			$terjadwal12b = $jumterjadwal12b->num_rows;
			//=====================data jenis kelamin================================================
			$sqljeniskelaminl = "SELECT * FROM `asesi` WHERE `jenis_kelamin`='L'";
			$jeniskelaminl = $conn->query($sqljeniskelaminl);
			$jumlaki = $jeniskelaminl->num_rows;
			$sqljeniskelaminp = "SELECT * FROM `asesi` WHERE `jenis_kelamin`='P'";
			$jeniskelaminp = $conn->query($sqljeniskelaminp);
			$jumperempuan = $jeniskelaminp->num_rows;
			$sqljeniskelaminu = "SELECT * FROM `asesi` WHERE `jenis_kelamin`=''";
			$jeniskelaminu = $conn->query($sqljeniskelaminu);
			$jumnotlp = $jeniskelaminu->num_rows;
			//=====================data usia=========================================================
			$sqlkalkulasiusia = "SELECT `no_pendaftaran`,`tgl_lahir` FROM `asesi`";
			$kalkulasiusia = $conn->query($sqlkalkulasiusia);
			while ($kus = $kalkulasiusia->fetch_assoc()) {
				$tgl_lahir = $kus['tgl_lahir'];
				// ubah ke format Ke Date Time
				$lahir = new DateTime($tgl_lahir);
				$hari_ini = new DateTime();
				$diff = $hari_ini->diff($lahir);
				$usia = $diff->y;
				$sqlupdateusia = "UPDATE `asesi` SET `usia`='$usia' WHERE `no_pendaftaran`='$kus[no_pendaftaran]'";
				$updateusia = $conn->query($sqlupdateusia);
			}
			//---------------------------------------------------
			$sqlgetusia1 = "SELECT `no_pendaftaran`, `usia` FROM `asesi` WHERE `usia` BETWEEN 15.5 AND 21.5";
			$getusia1 = $conn->query($sqlgetusia1);
			$jumusia1 = $getusia1->num_rows;
			$sqlgetusia2 = "SELECT `no_pendaftaran`, `usia` FROM `asesi` WHERE `usia` BETWEEN 21.5 AND 26.5";
			$getusia2 = $conn->query($sqlgetusia2);
			$jumusia2 = $getusia2->num_rows;
			$sqlgetusia3 = "SELECT `no_pendaftaran`, `usia` FROM `asesi` WHERE `usia` BETWEEN 26.5 AND 32.5";
			$getusia3 = $conn->query($sqlgetusia3);
			$jumusia3 = $getusia3->num_rows;
			$sqlgetusia4 = "SELECT `no_pendaftaran`, `usia` FROM `asesi` WHERE `usia` BETWEEN 32.5 AND 38.5";
			$getusia4 = $conn->query($sqlgetusia4);
			$jumusia4 = $getusia4->num_rows;
			$sqlgetusia5 = "SELECT `no_pendaftaran`, `usia` FROM `asesi` WHERE `usia` BETWEEN 38.5 AND 44.5";
			$getusia5 = $conn->query($sqlgetusia5);
			$jumusia5 = $getusia5->num_rows;
			$sqlgetusia6 = "SELECT `no_pendaftaran`, `usia` FROM `asesi` WHERE `usia` BETWEEN 44.5 AND 50.5";
			$getusia6 = $conn->query($sqlgetusia6);
			$jumusia6 = $getusia6->num_rows;
			$sqlgetusia7 = "SELECT `no_pendaftaran`, `usia` FROM `asesi` WHERE `usia` BETWEEN 50.5 AND 56.5";
			$getusia7 = $conn->query($sqlgetusia7);
			$jumusia7 = $getusia7->num_rows;
			$sqlgetusia8 = "SELECT `no_pendaftaran`, `usia` FROM `asesi` WHERE `usia` BETWEEN 56.5 AND 62.5";
			$getusia8 = $conn->query($sqlgetusia8);
			$jumusia8 = $getusia8->num_rows;
			$sqlgetusia9 = "SELECT `no_pendaftaran`, `usia` FROM `asesi` WHERE `usia` BETWEEN 62.5 AND 68.5";
			$getusia9 = $conn->query($sqlgetusia9);
			$jumusia9 = $getusia9->num_rows;
			$sqlgetusia10 = "SELECT `no_pendaftaran`, `usia` FROM `asesi` WHERE `usia` BETWEEN 68.5 AND 74.5";
			$getusia10 = $conn->query($sqlgetusia10);
			$jumusia10 = $getusia10->num_rows;
			$sqlgetusia11 = "SELECT `no_pendaftaran`, `usia` FROM `asesi` WHERE `usia` > 74.5";
			$getusia11 = $conn->query($sqlgetusia11);
			$jumusia11 = $getusia11->num_rows;
			//=======================================================================================
		}
	?>
		<!-- jQuery 2.2.3 -->
		<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
		<!-- ChartJS 1.0.1 -->
		<script src="../../plugins/chartjs/Chart2.min.js"></script>
		<!-- Page script -->
		<script>
			// ===================================================================================
			// Perhatikan urutan sekuensial harus sesuai dengan bagian kode htmlnya
			// dan jumlah chart yang ditampilkan harus sesuai dengan javascriptnya,
			// untuk chart dengan data beda getELemenById juga harus beda
			// kalau tidak sama chart tidak akan tampil
			// ===================================================================================
			new Chart(document.getElementById("line-chart"), {
				type: 'line',
				data: {
					labels: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Ags", "Sep", "Okt", "Nov", "Des"],
					datasets: [{
						data: [<?php echo "$kandidat01"; ?>, <?php echo "$kandidat02"; ?>, <?php echo "$kandidat03"; ?>, <?php echo "$kandidat04"; ?>, <?php echo "$kandidat05"; ?>, <?php echo "$kandidat06"; ?>, <?php echo "$kandidat07"; ?>, <?php echo "$kandidat08"; ?>, <?php echo "$kandidat09"; ?>, <?php echo "$kandidat10"; ?>, <?php echo "$kandidat11"; ?>, <?php echo "$kandidat12"; ?>],
						label: "Pendaftar",
						borderColor: "#3e95cd",
						fill: false
					}, {
						data: [<?php echo "$kandidat01b"; ?>, <?php echo "$kandidat02b"; ?>, <?php echo "$kandidat03b"; ?>, <?php echo "$kandidat04b"; ?>, <?php echo "$kandidat05b"; ?>, <?php echo "$kandidat06b"; ?>, <?php echo "$kandidat07b"; ?>, <?php echo "$kandidat08b"; ?>, <?php echo "$kandidat09b"; ?>, <?php echo "$kandidat10b"; ?>, <?php echo "$kandidat11b"; ?>, <?php echo "$kandidat12b"; ?>],
						label: "Terverifikasi",
						borderColor: "#8e5ea2",
						fill: false
					}, {
						data: [<?php echo "$terjadwal01b"; ?>, <?php echo "$terjadwal02b"; ?>, <?php echo "$terjadwal03b"; ?>, <?php echo "$terjadwal04b"; ?>, <?php echo "$terjadwal05b"; ?>, <?php echo "$terjadwal06b"; ?>, <?php echo "$terjadwal07b"; ?>, <?php echo "$terjadwal08b"; ?>, <?php echo "$terjadwal09b"; ?>, <?php echo "$terjadwal10b"; ?>, <?php echo "$terjadwal11b"; ?>, <?php echo "$terjadwal12b"; ?>],
						label: "Terjadwal",
						borderColor: "#3cba9f",
						fill: false
					}, {
						data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
						label: "Tersertifikasi",
						borderColor: "#e8c3b9",
						fill: false
					}]
				},
				options: {
					title: {
						display: true,
						text: 'Data Asesi Tahun <?php echo date("Y"); ?>'
					}
				}
			});
			new Chart(document.getElementById("bar-chart"), {
				type: 'bar',
				data: {
					labels: ["< 25 Tahun", "26 - 35 Tahun", "36 - 45 Tahun", "> 45 Tahun"],
					datasets: [{
						backgroundColor: ["#3e95cd", "#8e5ea2", "#3cba9f", "#c45850"],
						data: [<?php echo $jumusia1; ?>, <?php echo $jumusia2; ?>, <?php echo $jumusia3; ?>, <?php echo $jumusia4; ?>],
						label: "Jumlah Asesi"
					}]
				},
				options: {
					legend: {
						display: false
					},
					title: {
						display: true,
						text: 'Jumlah Asesi Berdasarkan Kelompok Usia'
					}
				}
			});
			new Chart(document.getElementById("pie-chart"), {
				type: 'pie',
				data: {
					labels: ["Laki-Laki", "Perempuan", "Tidak Diketahui"],
					datasets: [{
						label: "Jumlah Asesi",
						backgroundColor: ["#3e95cd", "#8e5ea2", "#3cba9f"],
						data: [<?php echo $jumlaki; ?>, <?php echo $jumperempuan; ?>, <?php echo $jumnotlp; ?>]
					}]
				},
				options: {
					title: {
						display: true,
						text: 'Jumlah Asesi Berdasarkan Jenis Kelamin'
					}
				}
			});
		</script>
	<?php
	}
	// Bagian Password User ======================================================================================================
	elseif ($_GET['module'] == 'password') {
		if (!empty($_SESSION['namauser'])) {
			include "modul/mod_password/password.php";
		}
	}
	// Bagian Profil Asesi
	elseif ($_GET['module'] == 'profil') {
		$sqllogin = "SELECT * FROM `asesor` WHERE `no_ktp`='$_SESSION[namauser]'";
		$login = $conn->query($sqllogin);
		$ketemu = $login->num_rows;
		$rowAgen = $login->fetch_assoc();
		if (!empty($rowAgen['gelar_depan'])) {
			if (!empty($rowAgen['gelar_blk'])) {
				$namaasesor = $rowAgen['gelar_depan'] . " " . $rowAgen['nama'] . ", " . $rowAgen['gelar_blk'];
			} else {
				$namaasesor = $rowAgen['gelar_depan'] . " " . $rowAgen['nama'];
			}
		} else {
			if (!empty($rowAgen['gelar_blk'])) {
				$namaasesor = $rowAgen['nama'] . ", " . $rowAgen['gelar_blk'];
			} else {
				$namaasesor = $rowAgen['nama'];
			}
		}
		echo "<!-- Content Header (Page header) -->
			<section class='content-header'>
				<h1>
			Profil Asesor
					<small></small>
				</h1>
				<ol class='breadcrumb'>
					<li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
					<li class='active'>Profil Asesor</li>
				</ol>
			</section>
			<!-- Main content -->
			<section class='content'>
				<div class='row'>
					<div class='col-md-3'>
						<!-- Profile Image -->
						<div class='box box-primary'>
							<div class='box-body box-profile'>";
		if (empty($rowAgen['foto'])) {
			echo "<img class='profile-user-img img-responsive img-circle' src='images/default.jpg' alt='User profile picture' style='width:128px; height:128px;'>";
		} else {
			echo "<img class='profile-user-img img-responsive img-circle' src='../foto_asesor/$rowAgen[foto]' alt='User profile picture' style='width:128px; height:128px;'>";
		}
		echo "<h3 class='profile-username text-center'>$namaasesor</h3>
								<p class='text-muted text-center'>$rowAgen[no_ktp]</p>
					<br>
					<a href='?module=updateasesor' class='btn btn-block btn-primary'>Ubah Profil</a>
					<br>
					<!--<a href='?module=unggahfile' class='btn btn-block btn-primary'>Unggah Dokumen</a>-->
							</div>
							<!-- /.box-body -->
						</div>
						<!-- /.box -->
					</div>
					<!-- /.col -->
					<div class='col-md-9'>		
			<!-- About Me Box -->
						<div class='box box-primary'>
							<div class='box-header with-border'>
								<strong><h3 class='box-title'>Detail Data Asesor</h3></strong>
							</div>
							<!-- /.box-header -->
							<div class='box-body'>
				<br>
								<strong class='col-md-3'>Nomor Induk Asesor</strong>
								<span class='col-md-9 col-sm-12 col-xs-12'><b>$rowAgen[no_induk]</b></span>
					<br><br>
								<strong class='col-md-3'>Nomor Lisensi</strong>
								<span class='col-md-9 col-sm-12 col-xs-12'><b>$rowAgen[no_lisensi]</b></span>
					<br><br>
								<strong class='col-md-3'>Masa Berlaku Lisensi</strong>
								<span class='col-md-9 col-sm-12 col-xs-12'><b>";
		echo tgl_indo($rowAgen['masaberlaku_lisensi']);
		echo "</b></span>
					<br><br>
								<strong class='col-md-3'>Nomor KTP</strong>
								<span class='col-md-9 col-sm-12 col-xs-12'>$rowAgen[no_ktp]</span>
					<br><br>
								<strong class='col-md-3'>Nama Lengkap dan Gelar</strong>
								<span class='col-md-9 col-sm-12 col-xs-12'>$namaasesor</span>
					<br><br>
								<strong class='col-md-3'>Tempat, Tanggal Lahir</strong>";
		$date = tgl_indo($rowAgen['tgl_lahir']);
		$tanggal_lahir = $date;
		echo "<span class='col-md-9 col-sm-12 col-xs-12'>$rowAgen[tmp_lahir], $tanggal_lahir</span>
					<br><br>
								<strong class='col-md-3'>Alamat</strong>
								<span class='col-md-9 col-sm-12 col-xs-12'>";
		$sqlwil1 = "SELECT * FROM `data_wilayah` WHERE `id_wil`='$rowAgen[kecamatan]'";
		$wilayah1 = $conn->query($sqlwil1);
		$wil1 = $wilayah1->fetch_assoc();
		$sqlwil2 = "SELECT * FROM `data_wilayah` WHERE `id_wil`='$rowAgen[kota]'";
		$wilayah2 = $conn->query($sqlwil2);
		$wil2 = $wilayah2->fetch_assoc();
		$sqlwil3 = "SELECT * FROM `data_wilayah` WHERE `id_wil`='$rowAgen[propinsi]'";
		$wilayah3 = $conn->query($sqlwil3);
		$wil3 = $wilayah3->fetch_assoc();
		echo $rowAgen['alamat'] . " RT " . $rowAgen['RT'] . ", RW " . $rowAgen['RW'] . ", " . $wil1['nm_wil'] . ", " . $wil2['nm_wil'] . ", " . $wil3['nm_wil'] . " " . $rowAgen['kodepos'];
		echo "</span><br><br>
														<strong class='col-md-3'>Nomor HP</strong>
								<span class='col-md-9 col-sm-12 col-xs-12'>$rowAgen[no_hp]</span>
					<br><br>
								<strong class='col-md-3'>Email</strong>
								<span class='col-md-9 col-sm-12 col-xs-12'><a href='mailto:$rowAgen[email]'>$rowAgen[email]</a></span>
					<br><br>
								<strong class='col-md-3'>Pendidikan Terakhir</strong>
								<span class='col-md-9 col-sm-12 col-xs-12'>";
		$sqlpendidikan = "SELECT * FROM `pendidikan` WHERE `id`='$rowAgen[pendidikan_terakhir]'";
		$pendidikan = $conn->query($sqlpendidikan);
		$jpen = $pendidikan->fetch_assoc();
		echo "$jpen[jenjang_pendidikan]</span>
					<br><br>
								<hr>
							</div>
							<!-- /.box-body -->
						</div>
						<!-- /.box -->
					</div>
					<!-- /.col -->
				</div>
				<!-- /.row -->
			</section>
			<!-- /.content -->";
	}
	// Bagian Asesi LSP ==========================================================================================================
	elseif ($_GET['module'] == 'asesi') {
		if ($_SESSION['leveluser'] == 'admin' || $_SESSION['leveluser'] == 'user') {
			if (isset($_REQUEST['plotasesor'])) {
			}
			if (isset($_REQUEST['blokirasesi'])) {
			}
			echo "
			<!-- Content Header (Page header) -->
			<section class='content-header'>
				<h1>
					Asesi Sertifikasi Profesi
					<small>Data Asesi</small>
				</h1>
				<ol class='breadcrumb'>
					<li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
					<li class='active'>Data Asesi Sertifikasi Profesi</li>
				</ol>
			</section>
			<!-- Main content -->
			<section class='content'>
				<div class='row'>
					<div class='col-xs-12'>
						<div class='box'>
							<div class='box-header'>
								<h3 class='box-title'>Data Asesi Sertifikasi Profesi</h3>
							</div>
							<!-- /.box-header -->
							<div class='box-body'>
					<table id='example1' class='table table-bordered table-striped'>
						<thead><tr><th>No</th><th>Identitas Asesi</th><th>Asesmen Skema Sertifikasi</th><th>Aksi</th></tr></thead>
						<tbody>";
			$no = 1;
			$sqllsp = "SELECT * FROM `asesi` ORDER BY `nama` ASC";
			$lsp = $conn->query($sqllsp);
			while ($pm = $lsp->fetch_assoc()) {
				$sqlgetskkni = $conn->query("SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$pm[no_pendaftaran]' ORDER BY `id` DESC");
				$ikutasesmen = $sqlgetskkni->num_rows;
				echo "<tr class=gradeX><td>$no</td><td>
								<div class='box box-widget widget-user-2'>
								<!-- Add the bg color to the header using any of the bg-* classes -->
								<div class='widget-user-header bg-aqua'>
									<div class='widget-user-image'>";
				if ($pm['foto'] == '') {
					echo "<img class='profile-user-img img-responsive img-circle' src='../images/default.jpg' alt='User Avatar'>";
				} else {
					echo "<img class='profile-user-img img-responsive img-circle' src='../foto_asesi/$pm[foto]' alt='User Avatar'>";
				}
				if (empty($pm['foto']) or empty($pm['ktp']) || empty($pm['kk']) || empty($pm['ijazah']) || empty($pm['transkrip'])) {
					if (empty($pm['foto'])) {
						$kelengkapan1 = "Foto ";
					}
					if (empty($pm['ktp'])) {
						$kelengkapan2 = "KTP ";
					}
					if (empty($pm['kk'])) {
						$kelengkapan3 = "KK ";
					}
					if (empty($pm['ijazah'])) {
						$kelengkapan4 = "Ijazah ";
					}
					if (empty($pm['transkrip'])) {
						$kelengkapan5 = "Transkrip";
					}
					$kelengkapan = "Kurang " . $kelengkapan1 . $kelengkapan2 . $kelengkapan3 . $kelengkapan4 . $kelengkapan5;
				} else {
					$kelengkapan = "Lengkap";
				}
				$sqlpendidikan = "SELECT * FROM `pendidikan` WHERE `id`='$pm[pendidikan]'";
				$pendidikan = $conn->query($sqlpendidikan);
				$pe = $pendidikan->fetch_assoc();
				echo "</div>
									<!-- /.widget-user-image -->
									<h3 class='widget-user-username'>$pm[nama]</h3>
									<h5 class='widget-user-desc'>No. Pendaftaran : $pm[no_pendaftaran]</h5>
									<h5 class='widget-user-desc'>Pendidikan Terakhir : $pe[jenjang_pendidikan]</h5>
								</div>
								<div class='box-footer'>
									<ul class='nav nav-stacked'>
									<li>Asesmen diikuti <span class='pull-right badge'>$ikutasesmen Skema</span></li>
									<li>Dokumen Pokok <span class='pull-right badge'>$kelengkapan</span></li>
									</ul>
								</div>
								</div>
								<!-- /.widget-user --></td>";
				echo "</td><td>";
				while ($ns = $sqlgetskkni->fetch_assoc()) {
					$sqlgetskkni2 = $conn->query("SELECT * FROM `skema_kkni` WHERE `id`='$ns[id_skemakkni]'");
					$ns2 = $sqlgetskkni2->fetch_assoc();
					$sqlgetskkni3 = $conn->query("SELECT * FROM `asesi_doc` WHERE `id_asesi`='$pm[no_pendaftaran]' AND `id_skemakkni`='$ns[id_skemakkni]'");
					$ns3 = $sqlgetskkni3->num_rows;
					echo "<b>$ns2[judul]</b> ($ns2[kode_skema])<br>";
					if ($ns3 == 0) {
						echo "Persyaratan : <span class='text-red'><b>Belum Melengkapi</b></span><br>";
					} else {
						echo "Persyaratan : <span class='text-green'><b>Telah mengunggah $ns3 Dokumen</b></span>
									<a href='?module=syarat&id=$ns[id_skemakkni]&ida=$pm[no_pendaftaran]' class='btn btn-info btn-xs' title='Lakukan Verifikasi/Persetujuan Dokumen yang diunggah Asesi'>Verifikasi</a><br>";
					}
					if ($ns['id_asesor'] == '0') {
						echo "Ploting Asesor : <span class='text-red'><b>Belum</b></span><br>";
					} else {
						$tglasesmen = tgl_indo($ns['tgl_asesmen']);
						echo "Ploting Asesor : <span class='text-green'><b>Sudah</b></span><br>";
						echo "Tanggal Asesmen : <span class='text-green'><b>$tglasesmen</b></span><br>";
					}
				}
				echo "</td>";
				echo "<td><form name='frm' method='POST' role='form' enctype='multipart/form-data'>
								<input type='hidden' name='iddellsp' value='$pm[id]'><input type='submit' name='hapuslsp' class='btn btn-danger btn-xs' title='Blokir' value='Blokir'></form>
								</td></tr>";
				$no++;
			}
			echo "</tbody></table>
				</div>
				</div>
			</div>
			</div>
		</section>";
		}
	}
	// Bagian Asesor LSP ==========================================================================================================
	elseif ($_GET['module'] == 'asesor') {
		if (!empty($_SESSION['namauser'])) {
			if (isset($_REQUEST['hapusasesor'])) {
				$cekdu = "SELECT * FROM `asesor` WHERE `id`='$_POST[iddelasesor]'";
				$result = $conn->query($cekdu);
				if ($result->num_rows != 0) {
					$conn->query("DELETE FROM `asesor` WHERE `id`='$_POST[iddelasesor]'");
					echo "<div class='alert alert-danger alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Hapus Data Sukses</h4>
				Anda Telah Berhasil Menghapus Data <b>Asesor Kompetensi Sertifikasi</b></div>";
				} else {
					echo "<script>alert('Maaf Asesor tersebut Tidak Ditemukan');</script>";
				}
			}
			echo "
			<!-- Content Header (Page header) -->
			<section class='content-header'>
				<h1>
					Asesor Sertifikasi Profesi
					<small>Data Asesor</small>
				</h1>
				<ol class='breadcrumb'>
					<li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
					<li class='active'>Data Asesor Sertifikasi Profesi</li>
				</ol>
			</section>
			<!-- Main content -->
			<section class='content'>
				<div class='row'>
					<div class='col-xs-12'>
						<div class='box'>
							<div class='box-header'>
								<h3 class='box-title'>Data Asesor Sertifikasi Profesi</h3>
							</div>
							<!-- /.box-header -->
							<div class='box-body'>
					<table id='example1' class='table table-bordered table-striped'>
						<thead><tr><th>No</th><th>Identitas Asesor</th><th>Kompetensi Skema Sertifikasi</th><th>Aksi</th></tr></thead>
						<tbody>";
			$no = 1;
			$sqllsp = "SELECT * FROM `asesor` ORDER BY `nama` ASC";
			$lsp = $conn->query($sqllsp);
			while ($pm = $lsp->fetch_assoc()) {
				$tahunskr = date("Y");
				$sqlgetskkni = $conn->query("SELECT * FROM `jadwal_asesmen` WHERE `id_asesor`='$pm[id]'");
				$ikutasesmen = $sqlgetskkni->num_rows;
				$sqlgetskkni2 = $conn->query("SELECT * FROM `jadwal_asesmen` WHERE `id_asesor`='$pm[id]' AND `tgl_asesmen` LIKE '$tahunskr%'");
				$ikutasesmen2 = $sqlgetskkni2->num_rows;
				if (!empty($pm['gelar_depan'])) {
					if (!empty($pm['gelar_blk'])) {
						$namaasesor = $pm['gelar_depan'] . " " . $pm['nama'] . ", " . $pm['gelar_blk'];
					} else {
						$namaasesor = $pm['gelar_depan'] . " " . $pm['nama'];
					}
				} else {
					if (!empty($pm['gelar_blk'])) {
						$namaasesor = $pm['nama'] . ", " . $pm['gelar_blk'];
					} else {
						$namaasesor = $pm['nama'];
					}
				}
				$masaberlaku = $pm['masaberlaku_lisensi'];
				$masaberlakuasesor = tgl_indo($masaberlaku);
				$hariini = date("Y-m-d");
				$dt1 = strtotime($hariini);
				$dt2 = strtotime($masaberlaku);
				$diff = $dt2 - $dt1;
				//$diff = abs($dt2-$dt1);
				$telat = $diff / 86400; // 86400 detik sehari
				$days_between = $telat;
				$days_between2 = abs($telat);
				echo "<tr class=gradeX><td>$no</td><td>
								<div class='box box-widget widget-user-2'>
								<!-- Add the bg color to the header using any of the bg-* classes -->";
				if ($days_between < 180) {
					if ($days_between < 0) {
						echo "<div class='widget-user-header bg-red'>";
					} else {
						echo "<div class='widget-user-header bg-yellow'>";
					}
				} else {
					echo "<div class='widget-user-header bg-green'>";
				}
				echo "<div class='widget-user-image'>";
				if ($pm['foto'] == '') {
					echo "<img class='profile-user-img img-responsive img-circle' src='../images/default.jpg' alt='User Avatar'>";
				} else {
					echo "<img class='profile-user-img img-responsive img-circle' src='../foto_asesor/$pm[foto]' alt='User Avatar'>";
				}
				if (empty($pm['foto']) or empty($pm['ktp']) || empty($pm['kk']) || empty($pm['ijazah']) || empty($pm['transkrip'])) {
					if (empty($pm['foto'])) {
						$kelengkapan1 = "Foto ";
					}
					if (empty($pm['ktp'])) {
						$kelengkapan2 = "KTP ";
					}
					if (empty($pm['kk'])) {
						$kelengkapan3 = "KK ";
					}
					if (empty($pm['ijazah'])) {
						$kelengkapan4 = "Ijazah ";
					}
					if (empty($pm['transkrip'])) {
						$kelengkapan5 = "Transkrip";
					}
					$kelengkapan = "Kurang " . $kelengkapan1 . $kelengkapan2 . $kelengkapan3 . $kelengkapan4 . $kelengkapan5;
				} else {
					$kelengkapan = "Lengkap";
				}
				$sqlpendidikan = "SELECT * FROM `pendidikan` WHERE `id`='$pm[pendidikan_terakhir]'";
				$pendidikan = $conn->query($sqlpendidikan);
				$pe = $pendidikan->fetch_assoc();
				echo "</div>
								<!-- /.widget-user-image -->
								<h3 class='widget-user-username'>$namaasesor</h3>
								<h5 class='widget-user-desc'>No. Register : $pm[no_induk]</h5>";
				echo "</div>
								<div class='box-footer'>
									<ul class='nav nav-stacked'>";
				if ($days_between < 180) {
					if ($days_between < 0) {
						echo "<li>Masa Berlaku Lisensi<span class='pull-right badge'>$masaberlakuasesor (Kadaluarsa $days_between2 hari)</span></li>";
					} else {
						echo "<li>Masa Berlaku Lisensi<span class='pull-right badge'>$masaberlakuasesor (Tersisa $days_between hari)</span></li>";
					}
				} else {
					echo "<li>Masa Berlaku Lisensi<span class='pull-right badge'>$masaberlakuasesor (Tersisa $days_between hari)</span></li>";
				}
				echo "<li>Total Portfolio Asesmen<span class='pull-right badge'>$ikutasesmen Skema</span></li>
									<li>Jumlah Portfolio Asesmen Tahun $tahunskr<span class='pull-right badge'>$ikutasesmen2 Skema</span></li>
									<li>Dokumen Pokok <span class='pull-right badge'>$kelengkapan</span></li>
									</ul>
								</div>
								</div>
								<!-- /.widget-user --></td>";
				echo "</td><td>";
				while ($ns = $sqlgetskkni->fetch_assoc()) {
					$sqlgetskkni2 = $conn->query("SELECT * FROM `skema_kkni` WHERE `id`='$ns[id_skemakkni]'");
					$ns2 = $sqlgetskkni2->fetch_assoc();
					echo "<b>$ns2[judul]</b> ($ns2[kode_skema])<br>";
				}
				echo "</td><td>";
				if ($ikutasesmen == 0) {
					echo "<form name='frm' method='POST' role='form' enctype='multipart/form-data'>
								<input type='hidden' name='iddelasesor' value='$pm[id]'><input type='submit' name='hapusasesor' class='btn btn-danger btn-xs' title='Hapus' value='Hapus'></form>
								</td>";
				} else {
					echo "<a href='?module=updateasesor&id=$pm[id]' class='btn btn-primary btn-xs'>Perbarui</a>";
				}
				echo "</tr>";
				$no++;
			}
			echo "</tbody></table><br><a href='?module=tambahasesor' class='btn btn-primary'>Tambah Asesor Baru</a>			
				</div>
				</div>
			</div>
			</div>
		</section>";
		}
	}
	// Bagian Update Asesor LSP ==========================================================================================================
	elseif ($_GET['module'] == 'updateasesor') {
		if (!empty($_SESSION['namauser'])) {
			$sqllogin = "SELECT * FROM `asesor` WHERE `no_ktp`='$_SESSION[namauser]'";
			$login = $conn->query($sqllogin);
			$ketemu = $login->num_rows;
			$rowAgen = $login->fetch_assoc();
			echo "<!-- Content Header (Page header) -->
			<section class='content-header'>
				<h1>
			Ubah Profil
					<small></small>
				</h1>
				<ol class='breadcrumb'>
					<li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
					<li><a href='media.php?module=asesor'>Profil Asesor</a></li>
					<li class='active'>Ubah</li>
				</ol>
			</section>";
			function uploadFoto($file)
			{
				//$file_max_weight = 20097152; //Ukuran maksimal yg dibolehkan(2Mb)
				$ok_ext = array('jpg', 'png', 'gif', 'jpeg', 'JPG', 'PNG', 'GIF', 'JPEG'); // ekstensi yang diijinkan
				$destination = "../foto_asesor/"; // tempat buat upload
				$filename = explode(".", $file['name']);
				$file_name = $file['name'];
				$file_name_no_ext = isset($filename[0]) ? $filename[0] : null;
				$file_extension = $filename[count($filename) - 1];
				$file_weight = $file['size'];
				$file_type = $file['type'];
				// Jika tidak ada error
				if ($file['error'] == 0) {
					$dateNow = date_create();
					$time_stamp = date_format($dateNow, 'U');
					if (in_array($file_extension, $ok_ext)) :
						//if( $file_weight <= $file_max_weight ):
						$fileNewName = $time_stamp . md5($file_name_no_ext[0] . microtime()) . "." . $file_extension;
						$alamatfile = $fileNewName;
						if (move_uploaded_file($file['tmp_name'], $destination . $fileNewName)) :
						//echo" File uploaded !";
						else :
						//echo "can't upload file.";
						endif;
					//else:
					//echo "File too heavy.";
					//endif;
					else :
					//echo "File type is not supported.";
					endif;
				}
				return $alamatfile;
			}
			if (isset($_REQUEST['simpan'])) {
				$file = $_FILES['file'];
				if (empty($_FILES['file']['tmp_name']) || !is_uploaded_file($_FILES['file']['tmp_name'])) {
					$alamatfile = $rowAgen['foto'];
				} else {
					unlink('../foto_asesor/' . $rowAgen['foto']);
					$alamatfile = uploadFoto($file);
				}
				$query = "UPDATE `asesor` SET `nama`='$_POST[nama]',`gelar_depan`='$_POST[gelar_depan]',`gelar_blk`='$_POST[gelar_blk]',`jenis_kelamin`='$_POST[jenis_kelamin]',`tmp_lahir`='$_POST[tmp_lahir]',`tgl_lahir`='$_POST[tgl_lahir]',`foto`='$alamatfile',`email`='$_POST[email]',`no_hp`='$_POST[no_hp]',`no_induk`='$_POST[no_induk]',`no_ktp`='$_POST[no_ktp]',`pendidikan_terakhir`='$_POST[pendidikan_terakhir]',`tahun_lulus`='$_POST[tahun_lulus]',`bid_keahlian`='$_POST[bid_keahlian]',`kebangsaan`='$_POST[kebangsaan]',`alamat`='$_POST[alamat]',`RT`='$_POST[RT]',`RW`='$_POST[RW]',`kelurahan`='$_POST[kelurahan]',`kecamatan`='$_POST[kecamatan]',`kota`='$_POST[kota]',`propinsi`='$_POST[propinsi]',`kodepos`='$_POST[kodepos]',`institusi_asal`='$_POST[institusi_asal]',`telp_kantor`='$_POST[telp_kantor]',`fax_kantor`='$_POST[fax_kantor]',`email_kantor`='$_POST[email_kantor]',`no_lisensi`='$_POST[no_lisensi]',`masaberlaku_lisensi`='$_POST[masaberlaku_lisensi]',`aktif`='$_POST[aktif]' WHERE `id`='$_POST[id_asesor]'";
				if ($conn->query($query) == TRUE) {
					echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Ubah Data Sukses</h4>
				Anda Telah Berhasil Mengubah Data <b>Profil Asesor</b></div>";
					//echo('<script>location.href = 'Location: editdata.php?type=$type&id=$id&edit=sukses';</script>');
					die("<script>location.href = 'media.php?module=profil'</script>");
				} else {
					echo "Error: " . $query . "<br>" . $conn->error;
				}
			}
			echo "<!-- Main content -->
			<section class='content'>
				<div class='row'>
					<div class='col-xs-12'>
						<div class='box'>
							<div class='box-body'>
								<!-- form start -->
							<form role='form' method='POST' enctype='multipart/form-data'>
								<div class='box-body'>";
			if ($rowAgen['tgl_lahir'] == '0000-00-00') {
				echo "<div class='alert alert-info alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-info'></i> Lengkapi Data Profil Asesor</h4>
				Silahkan lengkapi data <b>Profil Asesor</b> untuk dapat melanjutkan</div>";
			}
			echo "<div class='row'>
					<div class='col-md-6'>			  
					<div class='col-md-12'>			  
						<div class='form-group'>
							<label for='fileID'>";
			if (empty($rowAgen['foto'])) {
				echo "<img class='profile-user-img img-responsive img-circle' src='images/default.jpg' alt='User profile picture' style='width:150px; height:150px;'>";
			} else {
				echo "<img class='profile-user-img img-responsive img-circle' src='../foto_asesor/$rowAgen[foto]' alt='User profile picture' style='width:150px; height:150px;'>";
			}
			echo "</label>
							<input type='file' name='file' id='fileID' accept='image/*' onchange='readURL(this);'>
							<p class='help-block'>Ukuran foto maksimal 2 MB.</p>
							<input type='hidden'  name='id_asesor' value='$rowAgen[id]' class='form-control'>
						</div>
					</div>
					<div class='col-md-3'>  
						<div class='form-group'>
							<label>Gelar depan</label>
							<input type='text'  name='gelar_depan' value='$rowAgen[gelar_depan]' class='form-control'>
						</div>
					</div>
					<div class='col-md-6'>  
						<div class='form-group'>
							<label>Nama Lengkap</label>
							<input required type='text'  name='nama' value='$rowAgen[nama]' class='form-control'>
						</div>
					</div>
					<div class='col-md-3'>  
						<div class='form-group'>
							<label>Gelar belakang</label>
							<input type='text'  name='gelar_blk' value='$rowAgen[gelar_blk]' class='form-control'>
						</div>
					</div>
					<div class='col-md-6'>  
						<div class='form-group'>
							<label>Nomor KTP</label>
							<input required type='text'  name='no_ktp' value='$rowAgen[no_ktp]' class='form-control'>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
							<label>Jenis Kelamin</label>
							<select required class='form-control' name='jenis_kelamin'>";
			echo "<option value='L'";
			if ($rowAgen['jenis_kelamin'] == 'L') {
				echo "selected";
			}
			echo ">Laki-laki</option>";
			echo "<option value='P'";
			if ($rowAgen['jenis_kelamin'] == 'P') {
				echo "selected";
			}
			echo ">Perempuan</option>";
			echo "</select>
						</div>
					</div>
					<div class='col-md-9'>
						<div class='form-group'>
							<label>Pendidikan Terakhir</label>
							<select required class='form-control' name='pendidikan_terakhir'>";
			$sqlpendidikan = "SELECT * FROM `pendidikan` ORDER BY `id` ASC";
			$pendidikan = $conn->query($sqlpendidikan);
			while ($pdd = $pendidikan->fetch_assoc()) {
				echo "<option value='$pdd[id]'";
				if ($pdd['id'] == $rowAgen['pendidikan_terakhir']) {
					echo "selected";
				}
				echo ">$pdd[jenjang_pendidikan]</option>";
			}
			echo "</select>
						</div>
					</div>
					<div class='col-md-3'>  
						<div class='form-group'>
							<label>Tahun Lulus</label>
							<input required type='text'  name='tahun_lulus' value='$rowAgen[tahun_lulus]' class='form-control'>
						</div>
					</div>
					<div class='col-md-12'>  
						<div class='form-group'>
							<label>Bidang Keahlian</label>
							<input required type='text'  name='bid_keahlian' value='$rowAgen[bid_keahlian]' class='form-control'>
						</div>
					</div>
					<div class='col-md-6'>			  
						<div class='form-group'>
							<label>Tempat Lahir</label>
							<input required type='text'  name='tmp_lahir' value='$rowAgen[tmp_lahir]' class='form-control'>
							</select>
						</div>
					</div>
					<div class='col-md-6'>			  
						<div class='form-group'>
							<label>Tanggal Lahir</label>
							<input required type='date'  name='tgl_lahir' value='$rowAgen[tgl_lahir]' class='form-control'>
							</select>
						</div>
					</div>
					<div class='col-md-12'>
						<div class='form-group'>
							<label>Kebangsaan</label>
							<select required class='form-control' name='kebangsaan'>
								<option>Pilih</option>";
			$sqlkebangsaan = "SELECT * FROM `kebangsaan` ORDER BY `negara` ASC";
			$kebangsaan = $conn->query($sqlkebangsaan);
			while ($bgs = $kebangsaan->fetch_assoc()) {
				echo "<option value='$bgs[negara]'";
				if ($bgs['negara'] == $rowAgen['kebangsaan']) {
					echo "selected";
				}
				echo ">$bgs[negara]</option>";
			}
			echo "</select>
						</div>
					</div>
					<div class='col-md-12'>
						<div class='form-group'>
							<label>Nomor HP</label>
							<input required type='text' name='no_hp' value='$rowAgen[no_hp]' class='form-control' maxlength='14'>
						</div>
					</div>
					<div class='col-md-12'>			
						<div class='form-group'>
							<label>E-mail</label>
							<input required type='text' name='email' value='$rowAgen[email]' class='form-control'>
						</div>				
					</div>
			</div>
			<div class='col-md-6'>			  
					<div class='col-md-12'>				
						<div class='form-group'>
							<label>Alamat</label>
							<input required type='text'  name='alamat' value='$rowAgen[alamat]' class='form-control'>
							</select>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
							<label>RT</label>
							<input required type='text' name='RT' value='$rowAgen[RT]' class='form-control'>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
							<label>RW</label>
							<input required type='text' name='RW' value='$rowAgen[RW]'class='form-control'>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
							<label>Kelurahan</label>
							<input required type='text' name='kelurahan' value='$rowAgen[kelurahan]' class='form-control'>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
							<label>Provinsi</label>
							<div class='form-group'>
							<select name='propinsi' class='form-control' id='propinsi'>
								<option>Pilih</pilih>";
			$sqlpropinsi = "SELECT * FROM  `data_wilayah` WHERE  id_level_wil='1' AND id_induk_wilayah!='NULL' ORDER BY id_wil ASC";
			$propinsi = $conn->query($sqlpropinsi);
			while ($prop = $propinsi->fetch_assoc()) {
				echo "<option value='$prop[id_wil]'";
				if ($prop['id_wil'] == $rowAgen['propinsi']) {
					echo "selected";
				}
				echo ">$prop[nm_wil]</option>";
			}
			echo "</select>";
			echo "</div>
						</div>
					</div>
					<div class='col-md-6'>				
						<div class='form-group'>
							<label>Kota</label>
							<select name='kota' class='form-control' id='kota'>";
			$sqlkota = "SELECT * FROM  `data_wilayah` WHERE  id_wil='$rowAgen[kota]'";
			$kota = $conn->query($sqlkota);
			$nk = $kota->fetch_assoc();
			echo "<option value='$rowAgen[kota]'>$nk[nm_wil]</option>";
			echo "</select>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
							<label>Kecamatan</label>
							<select name='kecamatan' class='form-control' id='kecamatan'>";
			$sqlkecamatan = "SELECT * FROM  `data_wilayah` WHERE  id_wil='$rowAgen[kecamatan]'";
			$kec = $conn->query($sqlkecamatan);
			$kc = $kec->fetch_assoc();
			echo "<option value='$rowAgen[kecamatan]'>$kc[nm_wil]</option>";
			echo "</select>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
							<label>Kode Pos</label>
							<input type='text' name='kodepos' value='$rowAgen[kodepos]' class='form-control'>
						</div>
					</div>
					<div class='col-md-12'>			
						<div class='form-group'>
							<label>Nomor Register Asesor</label>
							<input required type='text'  name='no_induk' value='$rowAgen[no_induk]' class='form-control'>
						</div>
					</div>
					<div class='col-md-12'>			
						<div class='form-group'>
							<label>Nomor Lisensi Asesor</label>
							<input required type='text'  name='no_lisensi' value='$rowAgen[no_lisensi]' class='form-control'>
						</div>
					</div>
					<div class='col-md-12'>			
						<div class='form-group'>
							<label>Masa Berlaku hingga</label>
							<input required type='date'  name='masaberlaku_lisensi' value='$rowAgen[masaberlaku_lisensi]' class='form-control'>
						</div>
					</div>
					<div class='col-md-12'>			
						<div class='form-group'>
							<label>Institusi Asal</label>
							<input required type='text'  name='institusi_asal' value='$rowAgen[institusi_asal]' class='form-control'>
						</div>
					</div>
					<div class='col-md-6'>			
						<div class='form-group'>
							<label>Telp. Institusi Asal</label>
							<input required type='text'  name='telp_kantor' value='$rowAgen[telp_kantor]' class='form-control'>
						</div>
					</div>
					<div class='col-md-6'>			
						<div class='form-group'>
							<label>Fax. Institusi Asal</label>
							<input required type='text'  name='fax_kantor' value='$rowAgen[fax_kantor]' class='form-control'>
						</div>
					</div>
					<div class='col-md-9'>			
						<div class='form-group'>
							<label>Email Institusi Asal</label>
							<input required type='text'  name='email_kantor' value='$rowAgen[email_kantor]' class='form-control'>
						</div>
					</div>
					<div class='col-md-3'>			
						<div class='form-group'>
							<label>Status</label>
							<select  name='aktif' class='form-control'>";
			echo "<option value='Y'";
			if ($rowAgen['aktif' == 'Y']) {
				echo "selected";
			}
			echo ">Aktif</option>";
			echo "<option value='N'";
			if ($rowAgen['aktif' == 'N']) {
				echo "selected";
			}
			echo ">Non Aktif</option>";
			echo "</select>
						</div>
					</div>
								</div>
								<!-- /.box-body -->
		</div>
		<!-- /.row -->
								<div class='box-footer'>
				<div align='left' class='col-md-6 col-sm-6 col-xs-6'>
					<a class='btn btn-default' id=reset-validate-form href='?module=profil'>Kembali</a>
				</div>
				<div align='right' class='col-md-6 col-sm-6 col-xs-6'>
					<button type='submit' class='btn btn-primary' name='simpan'>Simpan</button>
				</div>
								</div>
							</form>			
							</div>
							<!-- /.box-body -->
						</div>
						<!-- /.box -->
					</div>
					<!-- /.col -->
				</div>
				<!-- /.row -->
			</section>
			<!-- /.content -->";
		}
	}
	// Bagian Input Dokumen Persyaratan Asesi ==========================================================================================================
	elseif ($_GET['module'] == 'syarat') {
		$sqlasesi = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_GET[ida]'";
		$getasesi = $conn->query($sqlasesi);
		$as = $getasesi->fetch_assoc();
		if (isset($_REQUEST['setujuidoc'])) {
			$cekdu = "SELECT * FROM `asesi_doc` WHERE `id`='$_POST[id_doc]'";
			$result = $conn->query($cekdu);
			if ($result->num_rows != 0) {
				$conn->query("UPDATE `asesi_doc` SET `status`='A' WHERE `id`='$_POST[id_doc]'");
				echo "<div class='alert alert-sukses alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Persetujuan Dokumen Sukses</h4>
				Anda Telah Berhasil Menyetujui <b>Dokumen Persyaratan Sertifikasi</b></div>";
			} else {
				echo "<script>alert('Maaf Dokumen Persyaratan tersebut Tidak Ditemukan');</script>";
			}
		}
		if (isset($_REQUEST['tolakdoc'])) {
			$cekdu = "SELECT * FROM `asesi_doc` WHERE `id`='$_POST[id_doc]'";
			$result = $conn->query($cekdu);
			if ($result->num_rows != 0) {
				$conn->query("UPDATE `asesi_doc` SET `status`='R' WHERE `id`='$_POST[id_doc]'");
				echo "<div class='alert alert-danger alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-times'></i> Penolakan Dokumen Sukses</h4>
				Anda Telah Berhasil Menolak <b>Dokumen Persyaratan Sertifikasi</b></div>";
			} else {
				echo "<script>alert('Maaf Dokumen Persyaratan tersebut Tidak Ditemukan');</script>";
			}
		}
		if (isset($_REQUEST['setujuiasesmen'])) {
			$tgl_daftar = date("Y-m-d");
			$sqljadwal = "SELECT * FROM `jadwal_asesmen` WHERE `id`='$_POST[jadwalasesmen]'";
			$jadwal = $conn->query($sqljadwal);
			$jdq = $jadwal->fetch_assoc();
			$querydas = "UPDATE `asesi_asesmen` SET `status`='A', `id_jadwal`='$_POST[jadwalasesmen]', `id_asesor`='$jdq[id_asesor]', `tgl_asesmen`='$jdq[tgl_asesmen]' WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$_GET[id]'";
			$querycek = "SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$_GET[id]' AND `status`='A'";
			$resultc = $conn->query($querycek);
			$row_cnt = $resultc->num_rows;
			if ($row_cnt == 0) {
				$conn->query($querydas);
				//Notifikasi Email dan SMS====================================================
				$sqlgetskema = "SELECT * FROM `skema_kkni` WHERE `id`='$_GET[id]'";
				$getskema = $conn->query($sqlgetskema);
				$gs = $getskema->fetch_assoc();
				$email = $as['email'];
				$namanya = $as['nama'];
				$no_hp = $as['nohp'];
				// Kirim email dalam format HTML ke Pendaftar
				$pesan = "Pendaftaran Asesmen Skema $gs[kode_skema] - $gs[judul]<br /><br />  
									ID Asesi: $as[no_pendaftaran] <br />
									Nama: $namanya <br />
									Nomor Handphone: $as[nohp] <br />
						Skema: $gs[kode_skema] - $gs[judul]<br />
							<br /><br />Telah dinyatakan disetujui. Silahkan lihat jadwal asesmen di laman Dashboard Anda.<br /><br />";
				$subjek = "Pendaftaran Asesmen di SILSP Telah disetujui";
				$dari = "From: febiharsa@students.unnes.ac.id\r\n";
				$dari .= "Content-type: text/html\r\n";
				// Kirim email ke member
				$sqlgetsmtp = "SELECT * FROM `smtp` WHERE `aktif`='Y'";
				$getsmtp = $conn->query($sqlgetsmtp);
				$gsmtp = $getsmtp->fetch_assoc();
				$sqlidentitas = "SELECT * FROM `identitas`";
				$identitas = $conn->query($sqlidentitas);
				$iden = $identitas->fetch_assoc();
				date_default_timezone_set("Asia/Jakarta");
				$mail = new PHPMailer;
				$mail->IsSMTP();
				$mail->SMTPSecure = $gsmtp['protokol'];
				$mail->Host = $gsmtp['host']; //host masing2 provider email
				$mail->SMTPDebug = 0;
				$mail->Port = $gsmtp['port'];
				$mail->SMTPAuth = true;
				$mail->Username = $gsmtp['username']; //user email
				$mail->Password = $gsmtp['password']; //password email 
				//Set who the message is to be sent from
				$mail->setFrom("$gsmtp[username]", $iden['nama_lsp']);
				//Set an alternative reply-to address
				$mail->addReplyTo("$gsmtp[username]", $iden['nama_lsp']);
				$mail->Subject = $subjek; //subyek email
				$mail->AddAddress($email, $namanya);  //tujuan email
				$mail->MsgHTML($pesan);
				if ($mail->Send()) {
					echo "";
				} else {
					echo "Notifikasi Email Gagal Terkirim, periksa pengaturan di menu Manajemen, sub menu Pengaturan SMTP";
				}
				//mail($email,$subjek,$pesan,$dari);
				//SMS Pendaftar
				$isisms = "Yth. $namanya Pendaftaran Asesmen Skema $gs[kode_skema]-$gs[judul] Anda telah disetujui, lihat jadwal di laman Dashboar Anda";
				if (strlen($no_hp) > 8) {
					$sqloutbox = "INSERT INTO `outbox` (`DestinationNumber`, `TextDecoded`, `Coding`,`SenderID`,`CreatorID`) VALUES ('$no_hp','$isisms','Default_No_Compression','MyPhone1','MyPhone1')";
					$outbox = $conn->query($sqloutbox);
				}
				//============================================================================
				echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Persetujuan Asesmen Sukses</h4>
			Anda Telah Berhasil menyetujui permohonan asesmen Asesi pada skema ini<b></b></div>";
			} else {
				echo "<script>alert('Maaf Asesi sudah disetujui pada skema ini'); window.location = '?module=syarat&id=$_GET[id]&ida=$_GET[ida]'</script>";
			}
		}
		if (isset($_REQUEST['tolakasesmen'])) {
			$tgl_daftar = date("Y-m-d");
			$querydas = "UPDATE `asesi_asesmen` SET `status`='R' WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$_GET[id]'";
			$querycek = "SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$_GET[id]' AND `status`='R'";
			$resultc = $conn->query($querycek);
			$row_cnt = $resultc->num_rows;
			if ($row_cnt == 0) {
				$conn->query($querydas);
				//Notifikasi Email dan SMS====================================================
				$sqlgetskema = "SELECT * FROM `skema_kkni` WHERE `id`='$_GET[id]'";
				$getskema = $conn->query($sqlgetskema);
				$gs = $getskema->fetch_assoc();
				$email = $as['email'];
				$namanya = $as['nama'];
				$no_hp = $as['nohp'];
				// Kirim email dalam format HTML ke Pendaftar
				$pesan = "Pendaftaran Asesmen Skema $gs[kode_skema] - $gs[judul]<br /><br />  
									ID Asesi: $as[no_pendaftaran] <br />
									Nama: $namanya <br />
									Nomor Handphone: $as[nohp] <br />
						Skema: $gs[kode_skema] - $gs[judul]<br />
							<br /><br />Telah dinyatakan DITOLAK. Silahkan lihat info selengkapnya di laman Dashboard Anda.<br /><br />";
				$subjek = "Pendaftaran Asesmen di SILSP ditolak";
				$dari = "From: febiharsa@students.unnes.ac.id\r\n";
				$dari .= "Content-type: text/html\r\n";
				// Kirim email ke member
				$sqlgetsmtp = "SELECT * FROM `smtp` WHERE `aktif`='Y'";
				$getsmtp = $conn->query($sqlgetsmtp);
				$gsmtp = $getsmtp->fetch_assoc();
				$sqlidentitas = "SELECT * FROM `identitas`";
				$identitas = $conn->query($sqlidentitas);
				$iden = $identitas->fetch_assoc();
				date_default_timezone_set("Asia/Jakarta");
				$mail = new PHPMailer;
				$mail->IsSMTP();
				$mail->SMTPSecure = $gsmtp['protokol'];
				$mail->Host = $gsmtp['host']; //host masing2 provider email
				$mail->SMTPDebug = 0;
				$mail->Port = $gsmtp['port'];
				$mail->SMTPAuth = true;
				$mail->Username = $gsmtp['username']; //user email
				$mail->Password = $gsmtp['password']; //password email 
				//Set who the message is to be sent from
				$mail->setFrom("$gsmtp[username]", $iden['nama_lsp']);
				//Set an alternative reply-to address
				$mail->addReplyTo("$gsmtp[username]", $iden['nama_lsp']);
				$mail->Subject = $subjek; //subyek email
				$mail->AddAddress($email, $namanya);  //tujuan email
				$mail->MsgHTML($pesan);
				if ($mail->Send()) {
					echo "";
				} else {
					echo "Notifikasi Email Gagal Terkirim, periksa pengaturan di menu Manajemen, sub menu Pengaturan SMTP";
				}
				//mail($email,$subjek,$pesan,$dari);
				//SMS Pendaftar
				$isisms = "Yth. $namanya, Maaf Pendaftaran Asesmen Skema $gs[kode_skema]-$gs[judul] Anda telah DITOLAK, lihat info di laman Dashboar Anda";
				if (strlen($no_hp) > 8) {
					$sqloutbox = "INSERT INTO `outbox` (`DestinationNumber`, `TextDecoded`, `Coding`,`SenderID`,`CreatorID`) VALUES ('$no_hp','$isisms','Default_No_Compression','MyPhone1','MyPhone1')";
					$outbox = $conn->query($sqloutbox);
				}
				//============================================================================
				echo "<div class='alert alert-danger alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Permohonan Asesmen Berhasil Ditolak</h4>
			Anda Telah Berhasil menolak permohonan asesmen Asesi pada skema ini<b></b></div>";
			} else {
				echo "<script>alert('Maaf Asesi sudah ditolak pada skema ini'); window.location = '?module=syarat&id=$_GET[id]&ida=$_GET[ida]'</script>";
			}
		}
		$sqlgetskema = "SELECT * FROM `skema_kkni` WHERE `id`='$_GET[id]'";
		$getskema = $conn->query($sqlgetskema);
		$gs = $getskema->fetch_assoc();
		echo "
			<!-- Content Header (Page header) -->
			<section class='content-header'>
				<h1>
					Dokumen Persyaratan Uji Kompetensi Asesi
					<small>Input Data</small>
				</h1>
				<ol class='breadcrumb'>
					<li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
					<li class='active'>Data Dokumen Persyaratan</li>
				</ol>
			</section>
			<!-- Main content -->
			<section class='content'>
				<div class='row'>
					<div class='col-xs-12'>
						<div class='box-group' id='accordion'>
		<!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
		<div class='panel box box-primary'>
			<div class='box-header with-border'>
			<h4 class='box-title'>
				<a data-toggle='collapse' data-parent='#accordion' href='#collapseOne'>
				Persyaratan Umum Skema Sertifikasi Profesi
				</a>
			</h4>
			</div>
			<div id='collapseOne' class='panel-collapse collapse in'>
			<div class='box-body'>
				<h2><b>$gs[kode_skema]</b>- $gs[judul]</h2>
				<table id='example' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Persyaratan</th></tr></thead>
					<tbody>";
		$no = 1;
		$sqllsp = "SELECT * FROM `skema_persyaratan` WHERE `id_skemakkni`='$gs[id]' ORDER BY `id` ASC";
		$lsp = $conn->query($sqllsp);
		while ($pm = $lsp->fetch_assoc()) {
			echo "<tr class=gradeX><td>$no</td>";
			echo "</td><td>$pm[persyaratan]</td></tr>";
			/*echo "<td><form name='frm' method='POST' role='form' enctype='multipart/form-data'>
							<input type='hidden' name='iddelsy' value='$pm[id]'><input type='submit' name='hapussy' class='btn btn-danger btn-xs' title='Hapus' value='Hapus'></form></td></tr>"; */
			$no++;
		}
		echo "</tbody>
				</table>
			</div>
			</div>
		</div>
		<div class='panel box box-default'>
			<div class='box-header with-border'>
			<h4 class='box-title'>
				<a data-toggle='collapse' data-parent='#accordion' href='#collapseTwo'>
				Persyaratan Biaya
				</a>
			</h4>
			</div>
			<div id='collapseTwo' class='panel-collapse collapse in'>
			<div class='box-body'>
				<table id='example' class='table table-bordered table-striped'>
				<thead><tr><th>Jenis Biaya</th><th>Nominal</th></tr></thead>
					<tbody>";
		$sqlbiaya = "SELECT * FROM `biaya_sertifikasi` WHERE `id_skemakkni`='$gs[id]'";
		$biayanya = $conn->query($sqlbiaya);
		$totbiaya = 0;
		while ($bys = $biayanya->fetch_assoc()) {
			$tampilbiaya = "Rp. " . number_format($bys['nominal'], 0, ",", ".");
			$sqljenisbi = "SELECT * FROM `biaya_jenis` WHERE `id`='$bys[jenis_biaya]'";
			$jenisbi = $conn->query($sqljenisbi);
			$jnb = $jenisbi->fetch_assoc();
			echo "<tr><td>$jnb[jenis_biaya]</td><td>$tampilbiaya</td></tr>";
			$totbiaya = $totbiaya + $bys['nominal'];
		}
		$totbiayatampil = "Rp. " . number_format($totbiaya, 0, ",", ".");
		echo "</tbody>
					<tfoot><tr><th>Total Biaya</th><th>$totbiayatampil</th></tr></tfoot>
				</table>
			</div>
			</div>
		</div>
		<div class='panel box box-success'>
			<div class='box-header with-border'>
			<h4 class='box-title'>
				<a data-toggle='collapse' data-parent='#accordion' href='#collapseThree'>
				Pendaftaran Uji Kompetensi Skema $gs[judul]
				</a>
			</h4>
			</div>
			<div id='collapseThree' class='panel-collapse collapse in'>
			<div class='box-body'>";
		function getAge($date)
		{ // Y-m-d format
			$now = explode("-", date('Y-m-d'));
			$dob = explode("-", $date);
			$dif = $now[0] - $dob[0];
			if ($dob[1] > $now[1]) { // birthday month has not hit this year
				$dif -= 1;
			} elseif ($dob[1] == $now[1]) { // birthday month is this month, check day
				if ($dob[2] > $now[2]) {
					$dif -= 1;
				} elseif ($dob[2] == $now[2]) { // Happy Birthday!
					$dif = $dif . " Happy Birthday!";
				};
			};
			return $dif;
		}
		$usia = getAge($as['tgl_lahir']);
		if ($usia < 61) {
			$syaratusia = "<font color='green'><b>Calon Asesi telah memenuhi Persyaratan Usia</b></font>";
		} else {
			$syaratusia = "<font color='red'><b>Maaf, Calon Asesi tidak memenuhi Persyaratan Usia</b></font>";
		}
		$sqlpendidikan = "SELECT * FROM `pendidikan` WHERE `id`='$as[pendidikan]'";
		$pendidikan = $conn->query($sqlpendidikan);
		$pdas = $pendidikan->fetch_assoc();
		if ($as['pendidikan'] > 1) {
			$syaratpend = "<font color='green'><b>Calon Asesi telah memenuhi Persyaratan Pendidikan</b></font>";
		} else {
			$syaratpend = "<font color='red'><b>Maaf, Calon Asesi tidak memenuhi Persyaratan Pendidikan</b></font>";
		}
		echo "<p>Asesi bernama <b>$as[nama]</b>, Nomor Pendaftaran <b>$as[no_pendaftaran]</b><br>
				Usia Calon Asesi adalah <b>$usia tahun</b>, $syaratusia<br>
				Pendidikan terakhir Calon Asesi adalah <b>$pdas[jenjang_pendidikan]</b>, $syaratpend<br></p>";
		$querycekasesmen = "SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$_GET[id]' AND `status`='R'";
		$cekasesmen = $conn->query($querycekasesmen);
		$asesmen = $cekasesmen->num_rows;
		if ($asesmen <> 0) {
			echo "<div class='alert alert-danger alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-warning'></i> Permohonan Asesmen Ditolak</h4>
					Permohonan asesmen Asesi pada skema ini dinyatakan ditolak<b></b></div>";
		}
		$querycekasesmen1 = "SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$_GET[id]' AND `status`='A'";
		$cekasesmen1 = $conn->query($querycekasesmen1);
		$asesmen1 = $cekasesmen1->num_rows;
		if ($asesmen1 <> 0) {
			echo "<div class='alert alert-success alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Permohonan Asesmen Disetujui</h4>
					Permohonan asesmen Asesi pada skema ini dinyatakan disetujui<b></b></div>";
		}
		if ($usia < 61 && $as['pendidikan'] > 1) {
			echo "<div class='row'>
						<div class='box-body'>
				<h3>Data Dokumen Persyaratan Asesi</h3>
				<table id='table-example' class='table table-bordered table-striped'>
				<thead><tr><th>No.</th><th>Persyaratan<th>File Pendukung</th><th>Status</th></tr></thead>
				<tbody>";
			$no = 1;
			$sqlasesidoc = "SELECT * FROM `asesi_doc` WHERE `id_asesi`='$as[no_pendaftaran]' AND `id_skemakkni`='$_GET[id]' ORDER BY `id` DESC";
			$asesidoc = $conn->query($sqlasesidoc);
			while ($pm = $asesidoc->fetch_assoc()) {
				switch ($pm['status']) {
					default:
						$statusnya = "<form role='form' method='POST' enctype='multipart/form-data'>
						<input type='hidden' name='id_doc' value='$pm[id]'>
							<button type='submit' class='btn btn-success btn-xs' name='setujuidoc'>Setujui</button>
							<button type='submit' class='btn btn-danger btn-xs' name='tolakdoc'>Tolak</button>
						</form>";
						break;
					case "A":
						$statusnya = "<font color='green'><b>Disetujui</b></font>";
						break;
					case "R":
						$statusnya = "<font color='red'><b>Ditolak</b></font>";
						break;
				}
				$portfolioskpi = $conn->query("SELECT * FROM `skema_persyaratan` WHERE `id`='$pm[skema_persyaratan]'");
				$prt = $portfolioskpi->fetch_assoc();
				echo "<tr class=gradeX><td>$no</td><td><b>$prt[persyaratan]</b><br>$pm[nama_doc]<br>No. Dokumen : <b><a href='#myModal" . $pm['id'] . "' data-toggle='modal' data-id='" . $pm['id'] . "'>$pm[nomor_doc]</a></b><br>Tanggal Dok.: <b>" . tgl_indo($pm['tgl_doc']) . "</b></td><td>";
				if (!empty($pm['file'])) {
					echo "<a class='btn btn-success btn-xs'><span class='glyphicon glyphicon-ok' aria-hidden='true' title='Dokumen Berhasil Diunggah'></span></a>";
					echo "&nbsp;<a href='#myModal" . $pm['id'] . "' class='btn btn-primary btn-xs' data-toggle='modal' data-id='" . $pm['id'] . "'><span class='glyphicon glyphicon-zoom-in' aria-hidden='true' title='Lihat/Unduh Dokumen'></span></a>";
				} else {
					echo "<span class='text-red'>Tidak ada dokumen</span>";
				}
				echo "</td><td>$statusnya";
				echo "</td></tr>";
				$no++;
				echo "<script>
					$(function(){
								$(document).on('click','.edit-record',function(e){
									e.preventDefault();
									$('#myModal" . $pm['id'] . "').modal('show');
								});
						});
				</script>
				<!-- Modal -->
					<div class='modal fade' id='myModal" . $pm['id'] . "' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
						<div class='modal-dialog'>
							<div class='modal-content'>
								<div class='modal-header'>
									<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Tutup</span></button>
									<h4 class='modal-title' id='myModalLabel'>Dokumen Porfolio " . $pm['nama_doc'] . "</h4>
									<h5 class='modal-title' id='myModalLabel'>Dokumen Porfolio " . $as['nama'] . " No. Pendaftaran " . $as['no_pendaftaran'] . "</h5>
								</div>
								<div class='modal-body'><img src='../foto_asesi/$pm[file]' width='100%'/>
								</div>
								<div class='modal-footer'>";
				if ($pm['status'] == 'P') {
					echo "<form role='form' method='POST' enctype='multipart/form-data'>
										<input type='hidden' name='id_doc' value='$pm[id]'>
										<button type='submit' class='btn btn-success' name='setujuidoc'>Setujui</button>
										<button type='submit' class='btn btn-danger' name='tolakdoc'>Tolak</button>
									</form>";
				}
				echo "<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>
								</div>
							</div>
							</div>
					</div>";
			}
			echo "</tbody></table><br />
				<form role='form' method='POST' enctype='multipart/form-data'>
				<input type='hidden' name='biaya_asesmen' value='$totbiaya'>
				<div align='left' class='col-md-6 col-sm-6 col-xs-6'>
					<a class='btn btn-default' id=reset-validate-form href='?module=asesi'>Kembali</a>
				</div>
				<div align='right' class='col-md-6 col-sm-6 col-xs-6'>";
			$querycekasesmen2 = "SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$_GET[id]' AND `status`='P'";
			$cekasesmen2 = $conn->query($querycekasesmen2);
			$asesmen2 = $cekasesmen2->num_rows;
			if ($asesmen2 <> 0) {
				echo "<a href='#myModalAs' data-toggle='modal' data-id='" . $as['no_pendaftaran'] . "' class='btn btn-success' name='setujuiasesmen'>Setujui Pendaftaran</a>";
				echo "<button type='submit' class='btn btn-danger' name='tolakasesmen'>Tolak Pendaftaran</button>";
			}
			echo "</div>
				</form>";
			// =============================================================================
			echo "		<!-- modal -->
			<div class='modal fade' id='myModalAs' tabindex='-1' role='dialog'>
				<div class='modal-dialog'>
					<div class='modal-content'>
						<div class='modal-header'>
									<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Tutup</span></button>
									<h4 class='modal-title' id='myModalLabelAs'>Persetujuan Pendaftaran Asesmen</h4>
									<h5 class='modal-title' id='myModalLabelAs'>" . $gs['kode_skema'] . " - " . $gs['judul'] . "</h5>
									<h5 class='modal-title' id='myModalLabelAs'>Asesi " . $as['nama'] . " No. Pendaftaran " . $as['no_pendaftaran'] . "</h5>					</div> 
						<div class='modal-body'>     
							<form role='form' method='POST' enctype='multipart/form-data'>";
			$sqlcekjadwalasesmen = "SELECT * FROM `jadwal_asesmen` WHERE `id_skemakkni`='$gs[id]' AND `tgl_asesmen` >= '$today'";
			$cekjadwalasesmen = $conn->query($sqlcekjadwalasesmen);
			$jumjadwal = $cekjadwalasesmen->num_rows;
			if ($jumjadwal == 0) {
				echo "<label class='text-red'>Belum Ada Jadwal Asesmen untuk Skema ini/ Jadwal Belum Diinput</label>";
			} else {
				echo "<label>Pilih Jadwal Asesmen</label>
											<select name='jadwalasesmen' id='jadwalasesmen' class='form-control' required>
												<option>-- Pilih Jadwal Asesmen --</option>";
				$today = date("Y-m-d");
				$sqljadwalasesmen = "SELECT * FROM `jadwal_asesmen` WHERE `id_skemakkni`='$gs[id]' AND `tgl_asesmen` >= '$today'";
				$jadwalasesmen = $conn->query($sqljadwalasesmen);
				while ($jdw = $jadwalasesmen->fetch_assoc()) {
					$tanggalassesmen = tgl_indo($jdw['tgl_asesmen']);
					echo "<option value='$jdw[id]'>$jdw[tahun] $jdw[periode] Gelombang $jdw[gelombang] ($tanggalassesmen)</option>";
				}
				echo "</select>
											<label>Deskripsi Jadwal</label>
											<p class='text-red' id='deskripsijadwal'>Pilih jadwal terlebih dahulu</p>";
			}
			echo "</div>
						<div class='modal-footer'>";
			if ($jumjadwal != 0) {
				echo "<button type='submit' class='btn btn-success' name='setujuiasesmen'>Setujui Pendaftaran</button>";
			}
			echo "</form>
							<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>
						</div> 
					</div>
				</div>
			</div>
			<!-- //modal --> ";
			// =============================================================================
		}
		echo "</div>
			</div>
			</div>
		</div>
	</div>
	<!--accordion-->
			</div><!--col-->
			</div><!--row-->
			</section>";
	}
	// Bagian Jadwal TUK ================================================================================================================
	elseif ($_GET['module'] == 'jadwalasesmen') {
		if (!empty($_SESSION['namauser'])) {
			$sqllogin = "SELECT * FROM `asesor` WHERE `no_ktp`='$_SESSION[namauser]'";
			$login = $conn->query($sqllogin);
			$ketemu = $login->num_rows;
			$rowAgen = $login->fetch_assoc();
			if (isset($_REQUEST['tambahjadwal'])) {
				$cekdu = "SELECT * FROM `jadwal_asesmen` WHERE `tahun`='$_POST[tahun]' AND `periode`='$_POST[periode]' AND `gelombang`='$_POST[gelombang]'";
				$result = $conn->query($cekdu);
				if ($result->num_rows == 0) {
					$conn->query("INSERT INTO `jadwal_asesmen`(`tahun`, `periode`, `gelombang`, `tgl_asesmen`, `jam_asesmen`, `tempat_asesmen`, `id_asesor`, `kapasitas`, `id_skemakkni`) VALUES ('$_POST[tahun]','$_POST[periode]','$_POST[gelombang]','$_POST[tgl_asesmen]','$_POST[jam_asesmen]','$_POST[tuk]','$_POST[asesor]','$_POST[kapasitas]','$_POST[skemakkni]')");
					echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Input Data Jadwal Sukses</h4>
				Anda Telah Berhasil Input Data <b>Jadwal Asesmen TUK</b></div>";
				} else {
					echo "<script>alert('Maaf Jadwal TUK Tersebut Sudah Ada');</script>";
				}
			}
			if (isset($_REQUEST['hapusjadwal'])) {
				$cekdu = "SELECT * FROM `jadwal_asesmen` WHERE `id`='$_POST[iddeljadwal]'";
				$result = $conn->query($cekdu);
				if ($result->num_rows != 0) {
					$conn->query("DELETE FROM `jadwal_asesmen` WHERE `id`='$_POST[iddeljadwal]'");
					echo "<div class='alert alert-danger alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Hapus Data Sukses</h4>
				Anda Telah Berhasil Menghapus Data <b>Jadwal Asesmen TUK</b></div>";
				} else {
					echo "<script>alert('Maaf Jadwal Asesmen TUK Tersebut Tidak Ditemukan');</script>";
				}
			}
			echo "
			<!-- Content Header (Page header) -->
			<section class='content-header'>
				<h1>
					Jadwal Uji Kompetensi (Jadwal TUK)
					<small>Tugas Menguji</small>
				</h1>
				<ol class='breadcrumb'>
					<li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
					<li class='active'>Jadwal Asesor</li>
				</ol>
			</section>
			<!-- Main content -->
			<section class='content'>
				<div class='row'>
					<div class='col-xs-12'>
						<div class='box'>
							<div class='box-header'>
								<h3 class='box-title'>Data Jadwal Uji Kompetensi Lembaga Sertifikasi Profesi dimana Anda sebagai Asesor</h3>
							</div>
							<!-- /.box-header -->
							<div class='box-body'>
				<div style='overflow-x:auto;'>
					<table id='example1' class='table table-bordered table-striped'>
						<thead><tr><th>No</th><th>Aksi</th><th>Jadwal Asesmen</th></tr></thead>
						<tbody>";
			$no = 1;
			$sqljadwaltukx = "SELECT * FROM `jadwal_asesor` WHERE `id_asesor`='$rowAgen[id]'";
			$jadwaltukx = $conn->query($sqljadwaltukx);
			while ($pmx = $jadwaltukx->fetch_assoc()) {
				$sqljadwaltuk = "SELECT * FROM `jadwal_asesmen` WHERE `id`='$pmx[id_jadwal]'";
				$jadwaltuk = $conn->query($sqljadwaltuk);
				$pm = $jadwaltuk->fetch_assoc();
				/* $masa_berlaku=tgl_indo($pm['masa_berlaku']);
							$sqltukjenis1="SELECT * FROM `tuk_jenis` WHERE `id`='$pm[jenis_tuk]'";
							$jenistuk=$conn->query($sqltukjenis1);
							$jt=$jenistuk->fetch_assoc();
							$sqllspinduk="SELECT * FROM `lsp` WHERE `id`='$pm[lsp_induk]'";
							$lspinduk=$conn->query($sqllspinduk);
							$li=$lspinduk->fetch_assoc(); */
				$sqltuk = "SELECT * FROM `tuk` WHERE `id`='$pm[tempat_asesmen]'";
				$tuk = $conn->query($sqltuk);
				$tt = $tuk->fetch_assoc();
				$tglasesmen1 = tgl_indo($pm['tgl_asesmen']);
				$tglasesmen2 = tgl_indo($pm['tgl_asesmen_akhir']);
				if ($pm['tgl_asesmen'] == $pm['tgl_asesmen_akhir']) {
					$tglasesmen = $tglasesmen1;
				} else {
					$tglasesmen = $tglasesmen1 . " sd. " . $tglasesmen2;
				}
				if (empty($pm['nama_kegiatan'])) {
					$namakegiatan = $pm['periode'] . " " . $pm['tahun'] . " Gelombang " . $pm['gelombang'];
				} else {
					$namakegiatan = $pm['nama_kegiatan'];
				}
				$pesertaasesmen = $conn->query("SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`='$pm[id]'");
				$jumps = $pesertaasesmen->num_rows;
				echo "<tr><td width='10%'>$no</td><td>";
				if ($jumps == 0) {
					echo "Belum ada peserta";
				} else {
					echo "<div class='btn-group'>
								<button type='button' class='btn btn-success'>Aksi</button>
								<button type='button' class='btn btn-success dropdown-toggle' data-toggle='dropdown'>
									<span class='caret'></span>
																<span class='sr-only'>Toggle Dropdown</span>
															</button>
															<ul class='dropdown-menu' role='menu'>
																<li><a href='?module=pesertaasesmen&idj=$pm[id]' title='Lihat Peserta Asesmen'>Lihat Peserta</a></li>
																<li><a href='daftarhadir.php?idj=$pm[id]' title='Unduh Berita Acara dan Daftar Hadir Peserta'>Berita Acara & Daftar Hadir</a></li>
																<li class='divider'>Input MAPA 1</li>
													<li><a href='media.php?module=mapa1a&kand=1&idsk=$pm[id_skemakkni]' title='Input/Update MAPA 1 Kandidat 1'>Input/Update MAPA 1 Kandidat 1</a></li>
																<li><a href='media.php?module=mapa1a&kand=2&idsk=$pm[id_skemakkni]' title='Input/Update MAPA 1 Kandidat 2'>Input/Update MAPA 1 Kandidat 2</a></li>
																<li><a href='media.php?module=mapa1a&kand=3&idsk=$pm[id_skemakkni]' title='Input/Update MAPA 1 Kandidat 3'>Input/Update MAPA 1 Kandidat 3</a></li>
																<li><a href='media.php?module=mapa1a&kand=4&idsk=$pm[id_skemakkni]' title='Input/Update MAPA 1 Kandidat 4'>Input/Update MAPA 1 Kandidat 4</a></li>
																<li><a href='media.php?module=mapa1a&kand=5&idsk=$pm[id_skemakkni]' title='Input/Update MAPA 1 Kandidat 5'>Input/Update MAPA 1 Kandidat 5</a></li>
																<li class='divider'>Unduh MAPA 1</li>
																<li><a href='form-mapa-01.php?idsk=$pm[id_skemakkni]&kand=1' title='Unduh MAPA 01-MERENCANAKAN AKTIVITAS DAN PROSES ASESMEN' target='_blank'>Unduh Form MAPA-01 Kandidat 1</a></li>
																<li><a href='form-mapa-01.php?idsk=$pm[id_skemakkni]&kand=2' title='Unduh MAPA 01-MERENCANAKAN AKTIVITAS DAN PROSES ASESMEN' target='_blank'>Unduh Form MAPA-01 Kandidat 2</a></li>
																<li><a href='form-mapa-01.php?idsk=$pm[id_skemakkni]&kand=3' title='Unduh MAPA 01-MERENCANAKAN AKTIVITAS DAN PROSES ASESMEN' target='_blank'>Unduh Form MAPA-01 Kandidat 3</a></li>
																<li><a href='form-mapa-01.php?idsk=$pm[id_skemakkni]&kand=4' title='Unduh MAPA 01-MERENCANAKAN AKTIVITAS DAN PROSES ASESMEN' target='_blank'>Unduh Form MAPA-01 Kandidat 4</a></li>
																<li><a href='form-mapa-01.php?idsk=$pm[id_skemakkni]&kand=5' title='Unduh MAPA 01-MERENCANAKAN AKTIVITAS DAN PROSES ASESMEN' target='_blank'>Unduh Form MAPA-01 Kandidat 5</a></li>
																<li class='divider'></li>
																<li><a href='media.php?module=mapa2&idsk=$pm[id_skemakkni]' title='Input MAPA 02-PETA INSTRUMEN ASESMEN HASIL PENDEKATAN ASESMEN DAN PERENCANAAN ASESMEN'>Input/Update Form MAPA-02</a></li>
																<li><a href='form-mapa-02.php?idsk=$pm[id_skemakkni]' title='Unduh MAPA 02-PETA INSTRUMEN ASESMEN HASIL PENDEKATAN ASESMEN DAN PERENCANAAN ASESMEN' target='_blank'>Unduh Form MAPA-02</a></li>
																<li><a href='media.php?module=form-fr-ak-05&idj=$pm[id]' title='Input/Update AK 05-LAPORAN ASESMEN'>Input/Update Form AK-05</a></li>
																<li><a href='form-ak-05.php?idj=$pm[id]' title='Unduh AK 05-LAPORAN ASESMEN' target='_blank'>Unduh Form AK-05</a></li>
																<li><a href='media.php?module=form-fr-ak-06&idj=$pm[id]' title='Input/Update AK 06-MENINJAU PROSES ASESMEN'>Input/Update Form AK-06</a></li>
																<li><a href='form-ak-06.php?idj=$pm[id]' title='Unduh AK 06-MENINJAU PROSES ASESMEN' target='_blank'>Unduh Form AK-06</a></li>
															</ul>
													</div>";
				}
				/*if ($jumps==0){
										echo "<td><a href='?module=pesertaasesmen&idj=$pm[id]' class='btn btn-info btn-xs btn-block' title='Lihat Peserta Asesmen'>Belum Ada Peserta</a>";
							}else{
								echo "<td><a href='?module=pesertaasesmen&idj=$pm[id]' class='btn btn-info btn-xs btn-block' title='Lihat Peserta Asesmen'>Peserta</a>
									<br><a href='daftarhadir.php?idj=$pm[id]' class='btn btn-primary btn-xs btn-block' title='Unduh Berita Acara dan Daftar Hadir Peserta'>Berita Acara & Daftar Hadir</a>";
							}*/
				$no++;
				echo "</td><td width='60%'><b>$namakegiatan</b><br>Tanggal : <b>$tglasesmen</b> Pukul : <b>$pm[jam_asesmen]</b></br>Tempat :<br><b>$tt[nama]</b><br>$tt[alamat] $tt[kelurahan]";
				$sqlskemakkni = "SELECT * FROM `skema_kkni` WHERE `id`='$pm[id_skemakkni]'";
				$skemakkni = $conn->query($sqlskemakkni);
				$skm = $skemakkni->fetch_assoc();
				echo "<br>Skema :<br><b>$skm[kode_skema]-$skm[judul]</b><br>";
				$namaskkni = $conn->query("SELECT * FROM `skkni` WHERE `id`='$skm[id_skkni]'");
				$nsk = $namaskkni->fetch_assoc();
				echo "$nsk[nama]<br>Maksimal Peserta : <b>$pm[kapasitas] Asesi</b><br>Peserta Terjadwal : <b>$jumps Asesi</b><br>Asesor :<br>";
				$noasr = 1;
				$getasesor = $conn->query("SELECT * FROM `jadwal_asesor` WHERE `id_jadwal`='$pm[id]'");
				while ($gas = $getasesor->fetch_assoc()) {
					$sqlasesor = "SELECT * FROM `asesor` WHERE `id`='$gas[id_asesor]'";
					$asesor = $conn->query($sqlasesor);
					$asr = $asesor->fetch_assoc();
					if (!empty($asr['gelar_depan'])) {
						if (!empty($asr['gelar_blk'])) {
							$namaasesor = $asr['gelar_depan'] . " " . $asr['nama'] . ", " . $asr['gelar_blk'];
						} else {
							$namaasesor = $asr['gelar_depan'] . " " . $asr['nama'];
						}
					} else {
						if (!empty($asr['gelar_blk'])) {
							$namaasesor = $asr['nama'] . ", " . $asr['gelar_blk'];
						} else {
							$namaasesor = $asr['nama'];
						}
					}
					if (!empty($pm['file_surattugas'])) {
						echo "<a href='../foto_surat/$pm[file_surattugas]' class='btn btn-primary btn-xs' target='_blank'>Unduh Arsip Surat Tugas</a>&nbsp;<b>$noasr. $namaasesor</b><br>";
					} else {
						echo "<b>$noasr. $namaasesor</b><br>";
					}
					$noasr++;
				}
				echo "</td></tr>";
			}
			echo "</tbody>
					</table>
				</div>
				</div>
				</div>
			</div>
		</section>";
		}
	}
	// Bagian Jadwal Meninjau Instrumen Asesmen ================================================================================================================
	elseif ($_GET['module'] == 'peninjauasesmen') {
		if (!empty($_SESSION['namauser'])) {
			$sqllogin = "SELECT * FROM `asesor` WHERE `no_ktp`='$_SESSION[namauser]'";
			$login = $conn->query($sqllogin);
			$ketemu = $login->num_rows;
			$rowAgen = $login->fetch_assoc();
			echo "<!-- Content Header (Page header) -->
			<section class='content-header'>
				<h1>
					Jadwal Uji Kompetensi
					<small>Tugas Meninjau Instrumen Asesmen (FR.IA.11)</small>
				</h1>
				<ol class='breadcrumb'>
					<li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
					<li class='active'>Jadwal Asesor</li>
				</ol>
			</section>
			<!-- Main content -->
			<section class='content'>
				<div class='row'>
					<div class='col-xs-12'>
						<div class='box'>
							<div class='box-header'>
								<h3 class='box-title'>Data Jadwal Uji Kompetensi Lembaga Sertifikasi Profesi dimana Anda sebagai Asesor Peninjau Instrumen Asesmen (FR.IA.11)</h3>
							</div>
							<!-- /.box-header -->
							<div class='box-body'>
				<div style='overflow-x:auto;'>
					<table id='example1' class='table table-bordered table-striped'>
						<thead><tr><th>No</th><th>Aksi</th><th>Jadwal Asesmen</th></tr></thead>
						<tbody>";
			$no = 1;
			$sqljadwaltukx = "SELECT DISTINCT `id_jadwal` FROM `asesi_asesmen` WHERE `peninjau_ia11`='$rowAgen[id]' AND `id_jadwal` IS NOT NULL";
			$jadwaltukx = $conn->query($sqljadwaltukx);
			while ($pmx = $jadwaltukx->fetch_assoc()) {
				$sqljadwaltuk = "SELECT * FROM `jadwal_asesmen` WHERE `id`='$pmx[id_jadwal]'";
				$jadwaltuk = $conn->query($sqljadwaltuk);
				$pm = $jadwaltuk->fetch_assoc();
				/* $masa_berlaku=tgl_indo($pm['masa_berlaku']);
							$sqltukjenis1="SELECT * FROM `tuk_jenis` WHERE `id`='$pm[jenis_tuk]'";
							$jenistuk=$conn->query($sqltukjenis1);
							$jt=$jenistuk->fetch_assoc();
							$sqllspinduk="SELECT * FROM `lsp` WHERE `id`='$pm[lsp_induk]'";
							$lspinduk=$conn->query($sqllspinduk);
							$li=$lspinduk->fetch_assoc(); */
				$sqltuk = "SELECT * FROM `tuk` WHERE `id`='$pm[tempat_asesmen]'";
				$tuk = $conn->query($sqltuk);
				$tt = $tuk->fetch_assoc();
				$tglasesmen = tgl_indo($pm['tgl_asesmen']);
				if (empty($pm['nama_kegiatan'])) {
					$namakegiatan = $pm['periode'] . " " . $pm['tahun'] . " Gelombang " . $pm['gelombang'];
				} else {
					$namakegiatan = $pm['nama_kegiatan'];
				}
				$pesertaasesmen = $conn->query("SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`='$pm[id]'");
				$jumps = $pesertaasesmen->num_rows;
				echo "<tr><td width='10%'>$no</td><td>";
				if ($jumps == 0) {
					echo "Belum ada peserta";
				} else {
					echo "<div class='btn-group'>
								<button type='button' class='btn btn-success'>Aksi</button>
								<button type='button' class='btn btn-success dropdown-toggle' data-toggle='dropdown'>
									<span class='caret'></span>
																<span class='sr-only'>Toggle Dropdown</span>
															</button>
															<ul class='dropdown-menu' role='menu'>
																<li><a href='?module=tinjauia11&idj=$pm[id]' title='Tinjau Asesmen'>Lihat Peserta</a></li>
															</ul>
								</div>";
				}
				/*if ($jumps==0){
										echo "<td><a href='?module=pesertaasesmen&idj=$pm[id]' class='btn btn-info btn-xs btn-block' title='Lihat Peserta Asesmen'>Belum Ada Peserta</a>";
							}else{
								echo "<td><a href='?module=pesertaasesmen&idj=$pm[id]' class='btn btn-info btn-xs btn-block' title='Lihat Peserta Asesmen'>Peserta</a>
									<br><a href='daftarhadir.php?idj=$pm[id]' class='btn btn-primary btn-xs btn-block' title='Unduh Berita Acara dan Daftar Hadir Peserta'>Berita Acara & Daftar Hadir</a>";
							}*/
				$no++;
				echo "</td><td width='60%'><b>$namakegiatan</b><br>Tanggal : <b>$tglasesmen</b> Pukul : <b>$pm[jam_asesmen]</b></br>Tempat :<br><b>$tt[nama]</b><br>$tt[alamat] $tt[kelurahan]";
				$sqlskemakkni = "SELECT * FROM `skema_kkni` WHERE `id`='$pm[id_skemakkni]'";
				$skemakkni = $conn->query($sqlskemakkni);
				$skm = $skemakkni->fetch_assoc();
				echo "<br>Skema :<br><b>$skm[kode_skema]-$skm[judul]</b><br>";
				$namaskkni = $conn->query("SELECT * FROM `skkni` WHERE `id`='$skm[id_skkni]'");
				$nsk = $namaskkni->fetch_assoc();
				echo "$nsk[nama]<br>Maksimal Peserta : <b>$pm[kapasitas] Asesi</b><br>Peserta Terjadwal : <b>$jumps Asesi</b><br>Asesor :<br>";
				$noasr = 1;
				$getasesor = $conn->query("SELECT * FROM `jadwal_asesor` WHERE `id_jadwal`='$pm[id]'");
				while ($gas = $getasesor->fetch_assoc()) {
					$sqlasesor = "SELECT * FROM `asesor` WHERE `id`='$gas[id_asesor]'";
					$asesor = $conn->query($sqlasesor);
					$asr = $asesor->fetch_assoc();
					if (!empty($asr['gelar_depan'])) {
						if (!empty($asr['gelar_blk'])) {
							$namaasesor = $asr['gelar_depan'] . " " . $asr['nama'] . ", " . $asr['gelar_blk'];
						} else {
							$namaasesor = $asr['gelar_depan'] . " " . $asr['nama'];
						}
					} else {
						if (!empty($asr['gelar_blk'])) {
							$namaasesor = $asr['nama'] . ", " . $asr['gelar_blk'];
						} else {
							$namaasesor = $asr['nama'];
						}
					}
					echo "<b>$noasr. $namaasesor</b><br>";
					$noasr++;
				}
				echo "</td></tr>";
			}
			echo "</tbody>
					</table>
				</div>
				</div>
				</div>
			</div>
		</section>";
		}
	}
	// Bagian Jadwal Meninjau Instrumen Asesmen ================================================================================================================
	elseif ($_GET['module'] == 'penugasanmkva') {
		if (!empty($_SESSION['namauser'])) {
			$sqllogin = "SELECT * FROM `asesor` WHERE `no_ktp`='$_SESSION[namauser]'";
			$login = $conn->query($sqllogin);
			$ketemu = $login->num_rows;
			$rowAgen = $login->fetch_assoc();
			echo "<!-- Content Header (Page header) -->
			<section class='content-header'>
				<h1>
					Jadwal Uji Kompetensi
					<small>Tugas Memberikan Kontribusi dalam Validasi Asesmen (FR.VA)</small>
				</h1>
				<ol class='breadcrumb'>
					<li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
					<li class='active'>Jadwal Asesor</li>
				</ol>
			</section>
			<!-- Main content -->
			<section class='content'>
				<div class='row'>
					<div class='col-xs-12'>
						<div class='box'>
							<div class='box-header'>
								<h3 class='box-title'>Data Jadwal Uji Kompetensi Lembaga Sertifikasi Profesi dimana Anda Memberikan Kontribusi dalam Validasi Asesmen (FR.VA)</h3>
							</div>
							<!-- /.box-header -->
							<div class='box-body'>
				<div style='overflow-x:auto;'>
					<table id='example1' class='table table-bordered table-striped'>
						<thead><tr><th>No</th><th>Aksi</th><th>Jadwal Asesmen</th></tr></thead>
						<tbody>";
			$no = 1;
			$sqljadwaltukx = "SELECT DISTINCT `id` FROM `jadwal_asesmen` WHERE `asesor_mkva1`='$rowAgen[id]' OR `asesor_mkva2`='$rowAgen[id]'";
			$jadwaltukx = $conn->query($sqljadwaltukx);
			while ($pmx = $jadwaltukx->fetch_assoc()) {
				$sqljadwaltuk = "SELECT * FROM `jadwal_asesmen` WHERE `id`='$pmx[id]'";
				$jadwaltuk = $conn->query($sqljadwaltuk);
				$pm = $jadwaltuk->fetch_assoc();
				/* $masa_berlaku=tgl_indo($pm['masa_berlaku']);
							$sqltukjenis1="SELECT * FROM `tuk_jenis` WHERE `id`='$pm[jenis_tuk]'";
							$jenistuk=$conn->query($sqltukjenis1);
							$jt=$jenistuk->fetch_assoc();
							$sqllspinduk="SELECT * FROM `lsp` WHERE `id`='$pm[lsp_induk]'";
							$lspinduk=$conn->query($sqllspinduk);
							$li=$lspinduk->fetch_assoc(); */
				$sqltuk = "SELECT * FROM `tuk` WHERE `id`='$pm[tempat_asesmen]'";
				$tuk = $conn->query($sqltuk);
				$tt = $tuk->fetch_assoc();
				$tglasesmen = tgl_indo($pm['tgl_asesmen']);
				if (empty($pm['nama_kegiatan'])) {
					$namakegiatan = $pm['periode'] . " " . $pm['tahun'] . " Gelombang " . $pm['gelombang'];
				} else {
					$namakegiatan = $pm['nama_kegiatan'];
				}
				$pesertaasesmen = $conn->query("SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`='$pm[id]'");
				$jumps = $pesertaasesmen->num_rows;
				echo "<tr><td width='10%'>$no</td><td>";
				if ($jumps == 0) {
					echo "Belum ada peserta";
				} else {
					echo "<div class='btn-group'>
								<button type='button' class='btn btn-success'>Aksi</button>
								<button type='button' class='btn btn-success dropdown-toggle' data-toggle='dropdown'>
									<span class='caret'></span>
																<span class='sr-only'>Toggle Dropdown</span>
															</button>
															<ul class='dropdown-menu' role='menu'>
																<li><a href='?module=form-fr-va&idj=$pm[id]' title='Memberikan Kontribusi dalam Validasi Asesmen'>Input Form FR.VA (MKVA)</a></li>
															</ul>
								</div>";
				}
				/*if ($jumps==0){
										echo "<td><a href='?module=pesertaasesmen&idj=$pm[id]' class='btn btn-info btn-xs btn-block' title='Lihat Peserta Asesmen'>Belum Ada Peserta</a>";
							}else{
								echo "<td><a href='?module=pesertaasesmen&idj=$pm[id]' class='btn btn-info btn-xs btn-block' title='Lihat Peserta Asesmen'>Peserta</a>
									<br><a href='daftarhadir.php?idj=$pm[id]' class='btn btn-primary btn-xs btn-block' title='Unduh Berita Acara dan Daftar Hadir Peserta'>Berita Acara & Daftar Hadir</a>";
							}*/
				$no++;
				echo "</td><td width='60%'><b>$namakegiatan</b><br>Tanggal : <b>$tglasesmen</b> Pukul : <b>$pm[jam_asesmen]</b></br>Tempat :<br><b>$tt[nama]</b><br>$tt[alamat] $tt[kelurahan]";
				$sqlskemakkni = "SELECT * FROM `skema_kkni` WHERE `id`='$pm[id_skemakkni]'";
				$skemakkni = $conn->query($sqlskemakkni);
				$skm = $skemakkni->fetch_assoc();
				echo "<br>Skema :<br><b>$skm[kode_skema]-$skm[judul]</b><br>";
				$namaskkni = $conn->query("SELECT * FROM `skkni` WHERE `id`='$skm[id_skkni]'");
				$nsk = $namaskkni->fetch_assoc();
				echo "$nsk[nama]<br>Maksimal Peserta : <b>$pm[kapasitas] Asesi</b><br>Peserta Terjadwal : <b>$jumps Asesi</b><br>Asesor :<br>";
				$noasr = 1;
				$getasesor = $conn->query("SELECT * FROM `jadwal_asesor` WHERE `id_jadwal`='$pm[id]'");
				while ($gas = $getasesor->fetch_assoc()) {
					$sqlasesor = "SELECT * FROM `asesor` WHERE `id`='$gas[id_asesor]'";
					$asesor = $conn->query($sqlasesor);
					$asr = $asesor->fetch_assoc();
					if (!empty($asr['gelar_depan'])) {
						if (!empty($asr['gelar_blk'])) {
							$namaasesor = $asr['gelar_depan'] . " " . $asr['nama'] . ", " . $asr['gelar_blk'];
						} else {
							$namaasesor = $asr['gelar_depan'] . " " . $asr['nama'];
						}
					} else {
						if (!empty($asr['gelar_blk'])) {
							$namaasesor = $asr['nama'] . ", " . $asr['gelar_blk'];
						} else {
							$namaasesor = $asr['nama'];
						}
					}
					echo "<b>$noasr. $namaasesor</b><br>";
					$noasr++;
				}
				echo "</td></tr>";
			}
			echo "</tbody>
					</table>
				</div>
				</div>
				</div>
			</div>
		</section>";
		}
	}
	// Bagian Peserta Asesmen ================================================================================================================
	elseif ($_GET['module'] == 'tinjauia11') {
		if (!empty($_SESSION['namauser'])) {
			$sqlidentitas = "SELECT * FROM `identitas`";
			$identitas = $conn->query($sqlidentitas);
			$iden = $identitas->fetch_assoc();
			if (isset($_REQUEST['telepon'])) {
				echo "<script>window.location.href = 'tel:$_POST[nohpasesi]'</script>";
			}
			if (isset($_REQUEST['whatsapp'])) {
				$nohptujuan = $_POST['nohpasesi'];
				$first_letter = substr($nohptujuan[0], 0, 1);
				$rest = substr($nohptujuan, 1);
				$mes2 = str_replace('0', '62', $first_letter);
				$nowatujuan = $mes2 . $rest;
				echo "<script>window.location.href = 'https://api.whatsapp.com/send?phone=$nowatujuan&text=Yth.%20$_POST[namaasesi].%20Saya%20Asesor%20$iden[nama_lsp].%20Apakah%20Anda%20berkenan%20kami%20hubungi%20untuk%20tindak%20lanjut%20pendaftaran%20skema%20asesmen%20uji%20komptensi%20Anda?'</script>";
			}
			echo "
			<!-- Content Header (Page header) -->
			<section class='content-header'>
				<h1>
					Peserta (Asesi) Uji Kompetensi
					<small>Tinjau Instrumen Asesmen Peserta Asesmen</small>
				</h1>
				<ol class='breadcrumb'>
					<li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
					<li class='active'>Peserta/Calon Asesi Uji Kompetensi Sertifikasi Profesi</li>
				</ol>
			</section>
			<!-- Main content -->
			<section class='content'>
				<div class='row'>
					<div class='col-xs-12'>
						<div class='box'>
							<div class='box-header'>
								<h3 class='box-title'>Data Peserta Uji Kompetensi Lembaga Sertifikasi Profesi</h3>
							</div>
							<!-- /.box-header -->
							<div class='box-body'>
				<div style='overflow-x:auto;'>
					<table id='example1' class='table table-bordered table-striped'>
						<thead><tr><th>No</th><th>Aksi</th><th>Identitas Peserta/Asesi</th></tr></thead>
						<tbody>";
			$no = 1;
			$sqljadwaltuk = "SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`='$_GET[idj]'";
			$jadwaltuk = $conn->query($sqljadwaltuk);
			while ($pm = $jadwaltuk->fetch_assoc()) {
				$sqlasesi = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$pm[id_asesi]'";
				$asesi = $conn->query($sqlasesi);
				$as = $asesi->fetch_assoc();
				echo "<tr class=gradeX><td>$no</td><td>";
				echo "<div class='btn-group'>
								<button type='button' class='btn btn-success'>Aksi</button>
								<button type='button' class='btn btn-success dropdown-toggle' data-toggle='dropdown'>
									<span class='caret'></span>
																<span class='sr-only'>Toggle Dropdown</span>
															</button>
															<ul class='dropdown-menu' role='menu'>
																<li><a href='?module=form-fr-ia-11&ida=$pm[id_asesi]&idj=$_GET[idj]' title='CEKLIS MENINJAU INSTRUMEN ASESSMEN'>Input Formulir FR-IA-11</a></li>
																<li><a href='form-fr-ia-11.php?ida=$pm[id_asesi]&idj=$_GET[idj]' title='CEKLIS MENINJAU INSTRUMEN ASESSMEN' target='_blank'>Unduh Formulir FR-IA-11</a></li>
															</ul>
													</div>";
				$cekstatusAPL02 = "SELECT `id_asesi` FROM `asesi_apl02` WHERE `id_asesi`='$pm[id_asesi]' AND `id_jadwal`='$_GET[idj]'";
				$statusapl02 = $conn->query($cekstatusAPL02);
				$jumapl02 = $statusapl02->num_rows;
				$cekstatusasesmen = "SELECT `id_asesi`,`status_asesmen` FROM `asesi_asesmen` WHERE `id_asesi`='$pm[id_asesi]' AND `id_jadwal`='$_GET[idj]' AND `keputusan_asesor`!='NULL'";
				$statusasesmen = $conn->query($cekstatusasesmen);
				$gstass = $statusasesmen->fetch_assoc();
				$jumasesmen = $statusasesmen->num_rows;
				echo "</td><td><b>$as[nama]</b><br>No. Pendaftaran : $pm[id_asesi]<br>No. HP : $as[nohp]<br>";
				if (!empty($as['nohp'])) {
					echo "<form name='frm' method='POST' role='form' enctype='multipart/form-data'><a href='#myModalSMS" . $as['no_pendaftaran'] . "' data-toggle='modal' data-id='" . $as['no_pendaftaran'] . "' class='btn btn-xs btn-primary' title='Kirim pesan SMS ke $as[nama] ($as[nohp])'><i class='fa fa-envelope'></i> Kirim SMS</a>
									<input type='hidden' name='nohpasesi' value='$as[nohp]'>
									<input type='hidden' name='namaasesi' value='$as[nama]'>
									<button type='submit' name='telepon' class='btn btn-info btn-xs' title='Hubungi $as[nama] ($as[nohp]) melalui telepon'><i class='fa fa-phone-square'></i> $as[nohp]</button>
											<button type='submit' name='whatsapp' class='btn btn-success btn-xs' title='Kirim pesan WhatsApp ke $as[nama] ($as[nohp])'><i class='fa fa-whatsapp'></i> $as[nohp]</button></form>";
					echo "<script>
									$(function(){
												$(document).on('click','.edit-record',function(e){
													e.preventDefault();
													$('#myModalSMS" . $as['no_pendaftaran'] . "').modal('show');
												});
										});
								</script>
								<!-- Modal -->
									<form role='form' action='smsasesibaru.php' method='POST' enctype='multipart/form-data'>
									<div class='modal fade' id='myModalSMS" . $as['no_pendaftaran'] . "' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
										<div class='modal-dialog'>
											<div class='modal-content'>
												<div class='modal-header'>
													<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Tutup</span></button>
													<h4 class='modal-title' id='myModalLabel'>Kirim SMS ke $as[nama] (" . $as['nohp'] . ")</h4>
												</div>
												<div class='modal-body'>
													<input type='hidden' class='form-control' name='idpost' value='$as[no_pendaftaran]'/>
													<input type='hidden' class='form-control' name='nohp" . $as['no_pendaftaran'] . "' value='$as[nohp]'/>
													<div class='input-group'>
														<span class='input-group-addon'><i class='fa fa-envelope'></i></span>
														<textarea class='form-control' placeholder='Isi SMS'  name='pesan" . $as['no_pendaftaran'] . "' rows='2' cols='80' maxlength='160'>Yth. $as[nama], </textarea>
													</div>
												</div>
												<div class='modal-footer'>
													<div align='left' class='col-md-6 col-sm-6 col-xs-6'>
														<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>
													</div>
													<div align='right' class='col-md-6 col-sm-6 col-xs-6'>
														<button type='submit' class='btn btn-success' name='smsasesibaru'>Kirim SMS</button>
													</div>
												</div>
											</div>
										</div>
									</div>
									</form>
								<!-- Modal -->";
				}
				if ($jumapl02 > 0) {
					echo "<br><font color='red'>Telah mengisi Asesmen Mandiri (APL-02)</font>";
				}
				if ($jumasesmen > 0) {
					echo "<br><font color='green'><b>Keputusan Asesmen telah dibuat dan dinyatakan</b></font> : ";
					switch ($gstass['status_asesmen']) {
						case "K":
							echo "<font color='green'><b>KOMPETEN</b></font>";
							break;
						case "BK":
							echo "<font color='red'><b>BELUM KOMPETEN</b></font>";
							break;
						case "TL":
							echo "<b>UJI ULANG/ TINDAK LANJUT</b>";
							break;
						default:
							echo "<b>BELUM ADA KEPUTUSAN</b>";
							break;
					}
				}
				echo "</td>
							</tr>";
				$no++;
			}
			echo "</tbody></table>
				</div>
				</div>
				</div>
				<div class='box'>
							<div class='box-header'>
								<h3 class='box-title'>Info Jadwal Asesmen</h3>
							</div>
							<!-- /.box-header -->
							<div class='box-body'>";
			$sqljadwaltuk2 = "SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
			$jadwaltuk2 = $conn->query($sqljadwaltuk2);
			$jdt = $jadwaltuk2->fetch_assoc();
			$sqltuk = "SELECT * FROM `tuk` WHERE `id`='$jdt[tempat_asesmen]'";
			$tuk = $conn->query($sqltuk);
			$tt = $tuk->fetch_assoc();
			$masa_berlaku = tgl_indo($tt['masa_berlaku']);
			$sqltukjenis1 = "SELECT * FROM `tuk_jenis` WHERE `id`='$tt[jenis_tuk]'";
			$jenistuk = $conn->query($sqltukjenis1);
			$jt = $jenistuk->fetch_assoc();
			$tglasesmen = tgl_indo($jdt['tgl_asesmen']);
			$sqlskemakkni = "SELECT * FROM `skema_kkni` WHERE `id`='$jdt[id_skemakkni]'";
			$skemakkni = $conn->query($sqlskemakkni);
			$skm = $skemakkni->fetch_assoc();
			echo "<table id='example1' class='table table-bordered table-striped'>
				<tbody><tr><td>Skema</td><td><b>$skm[kode_skema]-$skm[judul]</b><br>";
			$namaskkni = $conn->query("SELECT * FROM `skkni` WHERE `id`='$skm[id_skkni]'");
			$nsk = $namaskkni->fetch_assoc();
			$pesertaasesmen = $conn->query("SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`='$jdt[id]'");
			$jumps = $pesertaasesmen->num_rows;
			echo "$nsk[nama]</td></tr>
				<tr><td>Periode</td><td><b>$jdt[periode] $jdt[tahun]</td></tr>
				<tr><td>Gelombang</td><td><b>$jdt[gelombang]</b></td></tr>
				<tr><td>Tanggal</td><td><b>$tglasesmen</b></td></tr>
				<tr><td>Pukul</td><td><b>$jdt[jam_asesmen]</b></td></tr>
				<tr><td>Tempat</td><td><b>$tt[nama]</b><br>$tt[alamat] $tt[kelurahan]</td></tr>
				<tr><td>Maksimal Peserta</td><td><b>$jdt[kapasitas] Asesi</b></td></tr>
				<tr><td>Peserta Terjadwal</td><td><b>$jumps Asesi</b></td></tr>
				<tr><td>Asesor</td><td>";
			$noasr = 1;
			$getasesor = $conn->query("SELECT * FROM `jadwal_asesor` WHERE `id_jadwal`='$jdt[id]'");
			while ($gas = $getasesor->fetch_assoc()) {
				$sqlasesor = "SELECT * FROM `asesor` WHERE `id`='$gas[id_asesor]'";
				$asesor = $conn->query($sqlasesor);
				$asr = $asesor->fetch_assoc();
				if (!empty($asr['gelar_depan'])) {
					if (!empty($asr['gelar_blk'])) {
						$namaasesor = $asr['gelar_depan'] . " " . $asr['nama'] . ", " . $asr['gelar_blk'];
					} else {
						$namaasesor = $asr['gelar_depan'] . " " . $asr['nama'];
					}
				} else {
					if (!empty($asr['gelar_blk'])) {
						$namaasesor = $asr['nama'] . ", " . $asr['gelar_blk'];
					} else {
						$namaasesor = $asr['nama'];
					}
				}
				echo "<b>$noasr. $namaasesor</b><br>";
				$noasr++;
			}
			echo "</td></tr></tbody></table><br>
				<div align='left' class='col-md-9 col-sm-6 col-xs-6'>
					<a href='daftarhadir.php?idj=$_GET[idj]' class='btn btn-primary'>Unduh Daftar Hadir</a>&nbsp;";
			if (empty($jdt['dok_standarkompetensi'])) {
				echo "<a href='#' class='btn btn-default'>Dok. Standar Kompetensi Belum Tersedia</a>";
			} else {
				echo "<a href='../foto_dokskkni/$jdt[dok_standarkompetensi]' class='btn btn-primary' target='_blank'>Unduh Standar Kompetensi</a>";
			}
			echo "<a href='media.php?module=form-fr-ak-05&idj=$_GET[idj]' class='btn btn-success' title='Input/Update AK 05-LAPORAN ASESMEN'>Input/Update Laporan Asesmen</a>";
			echo "</div>
				<div align='right' class='col-md-3 col-sm-6 col-xs-6'>";
			echo "<a href='?module=jadwalasesmen' class='btn btn-success'>Lihat Jadwal Lainnya</a>";
			echo "</div>
				</div>
				</div>
			</div>
			</div>
		</section>";
		}
	}
	// Bagian Jadwal TUK ================================================================================================================
	elseif ($_GET['module'] == 'verifikasituk') {
		if (!empty($_SESSION['namauser'])) {
			$sqllogin = "SELECT * FROM `asesor` WHERE `no_ktp`='$_SESSION[namauser]'";
			$login = $conn->query($sqllogin);
			$ketemu = $login->num_rows;
			$rowAgen = $login->fetch_assoc();
			if (isset($_REQUEST['tambahjadwal'])) {
				$cekdu = "SELECT * FROM `jadwal_asesmen` WHERE `tahun`='$_POST[tahun]' AND `periode`='$_POST[periode]' AND `gelombang`='$_POST[gelombang]'";
				$result = $conn->query($cekdu);
				if ($result->num_rows == 0) {
					$conn->query("INSERT INTO `jadwal_asesmen`(`tahun`, `periode`, `gelombang`, `tgl_asesmen`, `jam_asesmen`, `tempat_asesmen`, `id_asesor`, `kapasitas`, `id_skemakkni`) VALUES ('$_POST[tahun]','$_POST[periode]','$_POST[gelombang]','$_POST[tgl_asesmen]','$_POST[jam_asesmen]','$_POST[tuk]','$_POST[asesor]','$_POST[kapasitas]','$_POST[skemakkni]')");
					echo "<div class='alert alert-success alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Input Data Jadwal Sukses</h4>
					Anda Telah Berhasil Input Data <b>Jadwal Asesmen TUK</b></div>";
				} else {
					echo "<script>alert('Maaf Jadwal TUK Tersebut Sudah Ada');</script>";
				}
			}
			if (isset($_REQUEST['hapusjadwal'])) {
				$cekdu = "SELECT * FROM `jadwal_asesmen` WHERE `id`='$_POST[iddeljadwal]'";
				$result = $conn->query($cekdu);
				if ($result->num_rows != 0) {
					$conn->query("DELETE FROM `jadwal_asesmen` WHERE `id`='$_POST[iddeljadwal]'");
					echo "<div class='alert alert-danger alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Hapus Data Sukses</h4>
					Anda Telah Berhasil Menghapus Data <b>Jadwal Asesmen TUK</b></div>";
				} else {
					echo "<script>alert('Maaf Jadwal Asesmen TUK Tersebut Tidak Ditemukan');</script>";
				}
			}
			echo "
			<!-- Content Header (Page header) -->
			<section class='content-header'>
				<h1>
					Jadwal Uji Kompetensi (Jadwal TUK)
					<small>Tugas Menguji</small>
				</h1>
				<ol class='breadcrumb'>
					<li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
					<li class='active'>Jadwal Verifikasi TUK</li>
				</ol>
			</section>
			<!-- Main content -->
			<section class='content'>
				<div class='row'>
					<div class='col-xs-12'>
						<div class='box'>
							<div class='box-header'>
								<h3 class='box-title'>Data Jadwal Verifikasi Tempat Uji Kompetensi (TUK) Lembaga Sertifikasi Profesi dimana Anda sebagai Verifikator</h3>
							</div>
							<!-- /.box-header -->
							<div class='box-body'>
				<div style='overflow-x:auto;'>
					<table id='example1' class='table table-bordered table-striped'>
						<thead><tr><th>No</th><th>Aksi</th><th>Jadwal Verifikasi TUK</th></tr></thead>
						<tbody>";
			$no = 1;
			$sqljadwaltukx = "SELECT * FROM `asesor_verifikatortuk` WHERE `id_asesor`='$rowAgen[id]' ORDER BY `tgl_verifikasi` DESC";
			$jadwaltukx = $conn->query($sqljadwaltukx);
			while ($pmx = $jadwaltukx->fetch_assoc()) {
				$sqljadwaltuk = "SELECT * FROM `jadwal_asesmen` WHERE `id`='$pmx[id_jadwal]'";
				$jadwaltuk = $conn->query($sqljadwaltuk);
				$pm = $jadwaltuk->fetch_assoc();
				/* $masa_berlaku=tgl_indo($pm['masa_berlaku']);
							$sqltukjenis1="SELECT * FROM `tuk_jenis` WHERE `id`='$pm[jenis_tuk]'";
							$jenistuk=$conn->query($sqltukjenis1);
							$jt=$jenistuk->fetch_assoc();
							$sqllspinduk="SELECT * FROM `lsp` WHERE `id`='$pm[lsp_induk]'";
							$lspinduk=$conn->query($sqllspinduk);
							$li=$lspinduk->fetch_assoc(); */
				$sqltuk = "SELECT * FROM `tuk` WHERE `id`='$pm[tempat_asesmen]'";
				$tuk = $conn->query($sqltuk);
				$tt = $tuk->fetch_assoc();
				$tglasesmen0 = tgl_indo($pm['tgl_asesmen']);
				$tglasesmen = tgl_indo($pmx['tgl_verifikasi']);
				if (empty($pm['nama_kegiatan'])) {
					$namakegiatan = $pm['periode'] . " " . $pm['tahun'] . " Gelombang " . $pm['gelombang'];
				} else {
					$namakegiatan = $pm['nama_kegiatan'];
				}
				$pesertaasesmen = $conn->query("SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`='$pm[id]'");
				$jumps = $pesertaasesmen->num_rows;
				echo "<tr><td width='10%'>$no</td><td>";
				if ($jumps == 0) {
					echo "Belum ada peserta";
				} else {
					echo "<div class='btn-group'>
								<button type='button' class='btn btn-success'>Aksi</button>
								<button type='button' class='btn btn-success dropdown-toggle' data-toggle='dropdown'>
									<span class='caret'></span>
																<span class='sr-only'>Toggle Dropdown</span>
															</button>
															<ul class='dropdown-menu' role='menu'>
																<li><a href='?module=inputceklist&idj=$pm[id]' title='Input Ceklis Verifikasi TUK'>Input Ceklis Verifikasi TUK</a></li>
																<li><a href='unduhceklis.php?idj=$pm[id]' title='Unduh Berita Acara dan Ceklis Verifikasi TUK'>Berita Acara & Ceklis Verifikasi TUK</a></li>
																<li><a href='unduhsurattugasvertuk.php?idj=$pm[id]' title='Unduh Surat Tugas Verifikasi TUK'>Surat Tugas Verifikasi TUK</a></li>
															</ul>
													</div>";
				}
				/*if ($jumps==0){
										echo "<td><a href='?module=pesertaasesmen&idj=$pm[id]' class='btn btn-info btn-xs btn-block' title='Lihat Peserta Asesmen'>Belum Ada Peserta</a>";
							}else{
								echo "<td><a href='?module=pesertaasesmen&idj=$pm[id]' class='btn btn-info btn-xs btn-block' title='Lihat Peserta Asesmen'>Peserta</a>
									<br><a href='daftarhadir.php?idj=$pm[id]' class='btn btn-primary btn-xs btn-block' title='Unduh Berita Acara dan Daftar Hadir Peserta'>Berita Acara & Daftar Hadir</a>";
							}*/
				$no++;
				echo "</td><td width='60%'><b>$namakegiatan</b><br>Tanggal Uji Kompetensi: <b>$tglasesmen0</b> Pukul : <b>$pm[jam_asesmen]</b><br>Tanggal Verifikasi: <b>$tglasesmen</b></br>Tempat :<br><b>$tt[nama]</b><br>$tt[alamat] $tt[kelurahan]";
				$sqlskemakkni = "SELECT * FROM `skema_kkni` WHERE `id`='$pm[id_skemakkni]'";
				$skemakkni = $conn->query($sqlskemakkni);
				$skm = $skemakkni->fetch_assoc();
				echo "<br>Skema :<br><b>$skm[kode_skema]-$skm[judul]</b><br>";
				$namaskkni = $conn->query("SELECT * FROM `skkni` WHERE `id`='$skm[id_skkni]'");
				$nsk = $namaskkni->fetch_assoc();
				echo "$nsk[nama]<br>Maksimal Peserta : <b>$pm[kapasitas] Asesi</b><br>Peserta Terjadwal : <b>$jumps Asesi</b><br>Asesor :<br>";
				$noasr = 1;
				$getasesor = $conn->query("SELECT * FROM `jadwal_asesor` WHERE `id_jadwal`='$pm[id]'");
				while ($gas = $getasesor->fetch_assoc()) {
					$sqlasesor = "SELECT * FROM `asesor` WHERE `id`='$gas[id_asesor]'";
					$asesor = $conn->query($sqlasesor);
					$asr = $asesor->fetch_assoc();
					if (!empty($asr['gelar_depan'])) {
						if (!empty($asr['gelar_blk'])) {
							$namaasesor = $asr['gelar_depan'] . " " . $asr['nama'] . ", " . $asr['gelar_blk'];
						} else {
							$namaasesor = $asr['gelar_depan'] . " " . $asr['nama'];
						}
					} else {
						if (!empty($asr['gelar_blk'])) {
							$namaasesor = $asr['nama'] . ", " . $asr['gelar_blk'];
						} else {
							$namaasesor = $asr['nama'];
						}
					}
					//if (!empty($pm['file_surattugas'])){
					//	echo "<a href='../foto_surat/$pm[file_surattugas]' class='btn btn-primary btn-xs' target='_blank'>Unduh Arsip Surat Tugas</a>&nbsp;<b>$noasr. $namaasesor</b><br>";
					//}else{
					echo "<b>$noasr. $namaasesor</b><br>";
					//}
					$noasr++;
				}
				echo "Verifikator TUK :<br>";
				$noasr2 = 1;
				$getasesor2 = $conn->query("SELECT * FROM `asesor_verifikatortuk` WHERE `id_jadwal`='$pm[id]'");
				while ($gas2 = $getasesor2->fetch_assoc()) {
					$sqlasesor2 = "SELECT * FROM `asesor` WHERE `id`='$gas2[id_asesor]'";
					$asesor2 = $conn->query($sqlasesor2);
					$asr2 = $asesor2->fetch_assoc();
					if (!empty($asr2['gelar_depan'])) {
						if (!empty($asr2['gelar_blk'])) {
							$namaasesor2 = $asr2['gelar_depan'] . " " . $asr2['nama'] . ", " . $asr2['gelar_blk'];
						} else {
							$namaasesor2 = $asr2['gelar_depan'] . " " . $asr2['nama'];
						}
					} else {
						if (!empty($asr2['gelar_blk'])) {
							$namaasesor2 = $asr2['nama'] . ", " . $asr2['gelar_blk'];
						} else {
							$namaasesor2 = $asr2['nama'];
						}
					}
					echo "<b>$noasr2. $namaasesor2</b><br>";
					$sqlcekkeputusan = "SELECT * FROM `asesor_verifikatortuk` WHERE `id_asesor`='$gas2[id_asesor]' AND `id_jadwal`='$pm[id]'";
					$cekkeputusan = $conn->query($sqlcekkeputusan);
					$kpva = $cekkeputusan->fetch_assoc();
					switch ($kpva['keputusanverifikasi']) {
						case "Y":
							echo "&nbsp;Keputusan Verifikasi : <span class='text-green'><b>Sesuai Persyaratan Skema</b></span><br>";
							break;
						case "N":
							echo "&nbsp;Keputusan Verifikasi : <span class='text-red'><b>Tidak Sesuai Persyaratan Skema</b></span><br>";
							break;
						default:
							echo "&nbsp;<span class='text-red'>Belum Dilaksanakan Verifikasi</span></b></span><br>";
							break;
					}
					$noasr2++;
				}
				echo "</td></tr>";
			}
			echo "</tbody>
					</table>
				</div>
				</div>
				</div>
			</div>
		</section>";
		}
	} elseif ($_GET['module'] == 'verifikasitukjarakjauh') {
		if (isset($_REQUEST['hapusasesi'])) {
			$sqlgetasesidata = "SELECT * FROM `asesi` WHERE `id`='$_POST[idhapus]'";
			$getasesidata = $conn->query($sqlgetasesidata);
			$dtas = $getasesidata->fetch_assoc();
			$asesidata = $getasesidata->num_rows;
			if ($asesidata > 0) {
				// hapus data relevan
				// dokumen asesi
				$sqlgetdocasesi = "SELECT * FROM `tukjarakjauh_doc` WHERE `asesi_id`='$dtas[no_pendaftaran]'";
				$getdocasesi = $conn->query($sqlgetdocasesi);
				while ($docas = $getdocasesi->fetch_assoc()) {
					unlink('../foto_asesi/' . $docas['file']);
					$sqlhapusdatadocasesi = "DELETE FROM `tukjarakjauh_doc` WHERE `id`='$docas[id]'";
					$conn->query($sqlhapusdatadocasesi);
					echo "<div class='alert alert-danger alert-dismissible'>
						<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
						<h4><i class='icon fa fa-check'></i> Hapus Data Dokumen Asesi Sukses</h4>
						Anda Telah Berhasil Menghapus <b>Dokumen Pendukung Asesi dengan ID $docas[id]</b></div>";
				}
				// pendaftaran ke skema asesmen
				$sqlhapusdata1 = "DELETE FROM `asesi_tukjarakjauh` WHERE `asesi_id`='$dtas[no_pendaftaran]'";
				$conn->query($sqlhapusdata1);
				echo "<div class='alert alert-danger alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Hapus Data Pendaftaran Skema Asesi Sukses</h4>
					Anda Telah Berhasil Menghapus <b>Data Pendaftaran Asesi pada Skema Kompetensi</b></div>";
				// hapus data asesi
				$sqlblokir = "DELETE FROM `asesi` WHERE `id`='$_POST[idhapus]'";
				$conn->query($sqlblokir);
				echo "<div class='alert alert-danger alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Hapus Data Asesi Sukses</h4>
					Anda Telah Berhasil Menghapus <b>Data Asesi dan Data-Data Pendukungnya</b></div>";
			} else {
				echo "<script>alert('Maaf Asesi tersebut Tidak Ditemukan');</script>";
			}
		}
		if (isset($_REQUEST['hapusskemaasesi'])) {
			$sqlgetasesiasesmendata = "SELECT * FROM `asesi_tukjarakjauh` WHERE `id`='$_POST[idhapusskema]'";
			$getasesiasesmendata = $conn->query($sqlgetasesiasesmendata);
			$dtas0 = $getasesiasesmendata->fetch_assoc();
			$sqlgetasesidata = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_POST[idasesidel]'";
			$getasesidata = $conn->query($sqlgetasesidata);
			$dtas = $getasesidata->fetch_assoc();
			$asesiasesmendata = $getasesiasesmendata->num_rows;
			if ($asesiasesmendata > 0) {
				// hapus data relevan
				// dokumen asesi
				$sqlgetdocasesi = "SELECT * FROM `tukjarakjauh_doc` WHERE `asesi_id`='$dtas[no_pendaftaran]' AND `skema_id`='$_POST[idskemadel]'";
				$getdocasesi = $conn->query($sqlgetdocasesi);
				while ($docas = $getdocasesi->fetch_assoc()) {
					if (substr($docas['file'], 0, 4) != "http") {
						unlink('../foto_tukjarakjauh/' . $docas['file']);
					}
					$sqlhapusdatadocasesi = "DELETE FROM `tukjarakjauh_doc` WHERE `id`='$docas[id]' AND `skema_id`='$_POST[idskemadel]'";
					$conn->query($sqlhapusdatadocasesi);
					echo "<div class='alert alert-danger alert-dismissible'>
						<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
						<h4><i class='icon fa fa-check'></i> Hapus Data Dokumen Asesi Sukses</h4>
						Anda Telah Berhasil Menghapus <b>Dokumen Pendukung Asesi dengan ID $docas[id]</b></div>";
				}
				// pendaftaran ke skema asesmen
				$sqlhapusdata1 = "DELETE FROM `asesi_tukjarakjauh` WHERE `id`='$_POST[idhapusskema]' AND `asesi_id`='$dtas[no_pendaftaran]' AND `skema_id`='$_POST[idskemadel]'";
				$conn->query($sqlhapusdata1);
				echo "<div class='alert alert-danger alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Hapus Data Pendaftaran Skema Asesi Sukses</h4>
					Anda Telah Berhasil Menghapus <b>Data Pendaftaran Asesi pada Skema Kompetensi</b></div>";
			} else {
				echo "<script>alert('Maaf Pendaftaran Skema Asesi tersebut Tidak Ditemukan');</script>";
			}
		}
		if (isset($_REQUEST['telepon'])) {
			echo "<script>window.location.href = 'tel:$_POST[nohpasesi]'</script>";
		}
		if (isset($_REQUEST['whatsapp'])) {
			$nohptujuan = $_POST['nohpasesi'];
			$first_letter = substr($nohptujuan[0], 0, 1);
			$rest = substr($nohptujuan, 1);
			$mes2 = str_replace('0', '62', $first_letter);
			$nowatujuan = $mes2 . $rest;
			echo "<script>window.location.href = 'https://api.whatsapp.com/send?phone=$nowatujuan&text=Yth.%20$_POST[namaasesi].%20Saya%20Admin%20$iden[nama_lsp].%20Apakah%20Anda%20berkenan%20kami%20hubungi%20untuk%20tindak%20lanjut%20pendaftaran%20asesmen%20baru%20Anda?'</script>";
		}

		$tahunini = date("Y");
		echo "
			<!-- Content Header (Page header) -->
			<section class='content-header'>
				<h1>
					Asesi Sertifikasi Profesi
					<small>Data Asesi</small>
				</h1>
				<ol class='breadcrumb'>
					<li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
					<li class='active'>Data pemohon TUK Jarak Jauh</li>
				</ol>
			</section>
			<!-- Main content -->
			<section class='content'>
				<div class='row'>
					<div class='col-xs-12'>
						<div class='box'>
							<div class='box-header'>
								<h3 class='box-title'>Data Pemohon TUK Jarak Jauh</h3>
							</div>
							<!-- /.box-header -->
							<div class='box-body'>
					<!-- <div id='loading' class='col-xs-12 overlay'>
						<i class='fa fa-refresh fa-spin'></i>
					</div> -->
					<div style='overflow-x:auto;'>
						<table id='example5' class='table table-bordered table-striped'>
						<thead><tr><th>No</th><th>Identitas Asesi</th><th>Permohonan TUK Jarak Jauh</th><th>Aksi</th></tr></thead>
						<tbody>";
		$no = 1;
		$sqllsp = "SELECT `asesi_id` FROM `asesi_tukjarakjauh` WHERE `jadwal_id` IS NOT NULL AND `tgl_verifikasi` LIKE '$tahunini%' ORDER BY `tgl_verifikasi` DESC";
		$lsp = $conn->query($sqllsp);
		while ($pm0x = $lsp->fetch_assoc()) {
			$sqlgetasesinya = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$pm0x[asesi_id]'";
			$getasesinya = $conn->query($sqlgetasesinya);
			$pm = $getasesinya->fetch_assoc();
			$sqlcekterjadwal = "SELECT * FROM `asesi_tukjarakjauh` WHERE `asesi_id`='$pm[no_pendaftaran]' AND `jadwal_id` IS NOT NULL";
			$cekterjadwal = $conn->query($sqlcekterjadwal);
			$terjadwal = $cekterjadwal->num_rows;
			if ($terjadwal > 0) {
				$sqlgetskkni = $conn->query("SELECT * FROM `asesi_tukjarakjauh` WHERE `asesi_id`='$pm[no_pendaftaran]' ORDER BY `id` DESC");
				$ikutasesmen = $sqlgetskkni->num_rows;
				$sqlgetskkni2 = $conn->query("SELECT `id`,`asesi_id`,`status` FROM `asesi_tukjarakjauh` WHERE `asesi_id`='$pm[no_pendaftaran]' AND `status`='A' ORDER BY `id` DESC");
				$ikutasesmen2 = $sqlgetskkni2->num_rows;
				$sqlgetskkni3 = $conn->query("SELECT `id`,`asesi_id`,`status` FROM `asesi_tukjarakjauh` WHERE `asesi_id`='$pm[no_pendaftaran]' AND `status`='R' ORDER BY `id` DESC");
				$ikutasesmen3 = $sqlgetskkni3->num_rows;
				$sqlgetskkni4 = $conn->query("SELECT `id`,`asesi_id`,`status` FROM `asesi_tukjarakjauh` WHERE `asesi_id`='$pm[no_pendaftaran]' AND `status`='P' ORDER BY `id` DESC");
				$ikutasesmen4 = $sqlgetskkni4->num_rows;
				echo "<tr class=gradeX><td>$no</td><td>
									<div class='box box-widget widget-user-2'>
									<!-- Add the bg color to the header using any of the bg-* classes -->
									<a href='media.php?module=syaratprofil&ida=$pm[no_pendaftaran]' title='Lihat Profil $pm[nama]'><div class='widget-user-header bg-aqua'>
										<!--<div class='widget-user-image'>-->";
				/* if ($pm['foto']==''){
											echo "<img class='profile-user-img img-responsive img-circle' src='../images/default.jpg' alt='User Avatar'>";
										}else{
											echo "<img class='profile-user-img img-responsive img-circle' src='../foto_asesi/$pm[foto]' alt='User Avatar'>";
										} */
				// cek data mirip
				$cekdatamirip = "SELECT `nama` FROM `asesi` WHERE `nama` = '$pm[nama]'";
				$datamirip = $conn->query($cekdatamirip);
				$jumdatmir = $datamirip->num_rows;
				$cekdatamiripktp = "SELECT `no_ktp` FROM `asesi` WHERE `no_ktp` = '$pm[no_ktp]'";
				$datamiripktp = $conn->query($cekdatamiripktp);
				$jumdatmirktp = $datamiripktp->num_rows;
				$cekdatamiriptl = "SELECT `tgl_lahir` FROM `asesi` WHERE `tgl_lahir` = '$pm[tgl_lahir]'";
				$datamiriptl = $conn->query($cekdatamiriptl);
				$jumdatmirtl = $datamiriptl->num_rows;
				$sqlpendidikan = "SELECT * FROM `pendidikan` WHERE `id`='$pm[pendidikan]'";
				$pendidikan = $conn->query($sqlpendidikan);
				$pe = $pendidikan->fetch_assoc();
				echo "<!--</div>-->
										<!-- /.widget-user-image -->
										<h3 class='widget-user-username'>$pm[nama]</h3>
										<h5 class='widget-user-desc'>No. Pendaftaran : $pm[no_pendaftaran]</h5>
										<h5 class='widget-user-desc'>NIK/ No. KTP : $pm[no_ktp]</h5>
										<h5 class='widget-user-desc'>Pendidikan Terakhir : $pe[jenjang_pendidikan]</h5>
										<h5 class='widget-user-desc'>Preferensi Wilayah Uji : ";
				$sqlgetwil = "SELECT `id_wil`, `nm_wil` FROM `data_wilayah` WHERE `id_wil`='$pm[wil_ujikom]'";
				$getwil = $conn->query($sqlgetwil);
				$wilx = $getwil->fetch_assoc();
				$wilayahnya = trim($wilx['nm_wil']);
				echo "$wilayahnya</h5>
									</div></a>
									<div class='box-footer'>
										<ul class='nav nav-stacked'>
										<li>Asesmen diikuti <span class='pull-right badge'>$ikutasesmen Skema</span></li>
										<ul><li>Asesmen disetujui <span class='pull-right badge'>$ikutasesmen2 Skema</span></li>
										<li>Asesmen ditolak <span class='pull-right badge'>$ikutasesmen3 Skema</span></li>
										<li>Asesmen menunggu keputusan <span class='pull-right badge'>$ikutasesmen4 Skema</span></li></ul>
										<li>Dokumen Persyaratan Pokok</li><ul>";
				$sqlsyaratpokok = "SELECT * FROM `asesi_persyaratanpokok` WHERE `wajib`='Y'";
				$syaratpokok = $conn->query($sqlsyaratpokok);
				while ($syp = $syaratpokok->fetch_assoc()) {
					$shortcode = $syp['shortcode'];
					echo "<li>$syp[persyaratan] ";
					if (!empty($pm[$shortcode])) {
						echo "<span class='pull-right badge green'>Ada";
					} else {
						echo "<span class='pull-right badge red'>Belum Ada";
					}
					echo "</span></li>";
				}
				echo "</ul>";
				if ($jumdatmir > 1 || $jumdatmirktp > 1) {
					echo "<li class='text-red'>Data Nama Mirip/Ganda <span class='pull-right badge'>$jumdatmir</span></li>";
					echo "<li class='text-red'>Data No. KTP Mirip/Ganda <span class='pull-right badge'>$jumdatmirktp</span></li>";
					echo "<li class='text-red'>Tanggal Daftar <span class='pull-right badge'>$pm[waktu]</span></li>";
				} else {
					echo "";
				}
				echo "</ul>
									</div>
									</div>
									<!-- /.widget-user --></td>";
				echo "</td><td>";
				while ($ns = $sqlgetskkni->fetch_assoc()) {
					$sqlgetskkni2 = $conn->query("SELECT * FROM `skema_kkni` WHERE `id`='$ns[skema_id]'");
					$ns2 = $sqlgetskkni2->fetch_assoc();
					$sqlgetskkni3 = $conn->query("SELECT * FROM `tukjarakjauh_doc` WHERE `asesi_id`='$ns[asesi_id]' AND `skema_id`='$ns[skema_id]' AND `jadwal_id`='$ns[jadwal_id]'");
					$ns3 = $sqlgetskkni3->num_rows;
					echo "<b>$ns2[judul]</b> ($ns2[kode_skema])<br><ul>";
					if ($ns3 == 0) {
						echo "<li>Persyaratan : <span class='text-red'><b>Belum Melengkapi</b></span></li>";
					} else {
						echo "<li>Persyaratan : <span class='text-green'><b>Telah mengunggah $ns3 Dokumen</b></span>
										<a href='?module=veriftukjarakjauh&idas=$ns[id]&id=$ns[skema_id]&ida=$pm[no_pendaftaran]&idj=$ns[jadwal_id]' class='btn btn-info btn-xs' target='_blank' title='Lakukan Verifikasi/Persetujuan Dokumen yang diunggah Asesi'>Verifikasi</a></li>";
					}
					switch ($ns['status']) {
						case "A":
							echo "<li>Status Pendaftaran Asesmen : <span class='text-green'><b>Disetujui</b></span></li>";
							break;
						case "R":
							echo "<li>Status Pendaftaran Asesmen : <span class='text-red'><b>Ditolak</b></span></li>";
							break;
						default:
							echo "<li>Status Pendaftaran Asesmen : <span class='text-blue'><b>Dalam proses</b></span></li>";
							break;
					}
					$sqlcekasesor = "SELECT `id_jadwal` FROM `jadwal_asesor` WHERE `id_jadwal`='$ns[jadwal_id]'";
					$cekasesor = $conn->query($sqlcekasesor);
					$jumasas = $cekasesor->num_rows;
					if ($jumasas == 0) {
						echo "<li>Ploting Asesor : <span class='text-red'><b>Belum</b></span></li>";
					} else {
						$tglasesmen = tgl_indo($ns['tgl_verifikasi']);
						echo "<li>Ploting Asesor : <span class='text-green'><b>Sudah</b></span></li>";
						echo "<li>Tanggal Asesmen : <span class='text-green'><b>$tglasesmen</b></span>&nbsp;<a href='?module=syarat&idas=$ns[id]&id=$ns[skema_id]&ida=$pm[no_pendaftaran]' class='btn btn-primary btn-xs' title='Ubah jadwal asesmen Asesi'>Ubah Jadwal</a>&nbsp;<a href='?module=pesertaasesmen&idj=$ns[jadwal_id]' class='btn btn-primary btn-xs'><span class='glyphicon glyphicon-zoom-in' aria-hidden='true' title='Lihat Jadwal'></span></a></li>";
					}
					echo "</ul>";
					echo "<form name='frm' method='POST' role='form' enctype='multipart/form-data'><input type='hidden' name='idhapusskema' value='$ns[id]'><input type='hidden' name='idasesidel' value='$pm[no_pendaftaran]'><input type='hidden' name='idskemadel' value='$ns[skema_id]'><button type='submit' name='hapusskemaasesi' onclick='return confirmation()' class='btn btn-danger btn-xs' title='Hapus Pendaftaran $pm[nama] pada Skema ini'><i class='fa fa-trash'></i> Hapus Pendaftaran Skema</button></form>";
				}
				echo "</td>";
				echo "<td>
									<form name='frm' method='POST' role='form' enctype='multipart/form-data'>
									<input type='hidden' name='idblokir' value='$pm[id]'><button type='submit' name='blokir' class='btn btn-warning btn-xs btn-block' title='Blokir akses $pm[nama]'><i class='fa fa-ban'></i> Blokir</button></form>
										<form name='frm' method='POST' role='form' enctype='multipart/form-data'>
									<input type='hidden' name='idhapus' value='$pm[id]'><button type='submit' name='hapusasesi' onclick='return confirmation()' class='btn btn-danger btn-xs btn-block' title='Hapus $pm[nama]'><i class='fa fa-trash'></i> Hapus</button></form>";
				if (!empty($pm['nohp'])) {
					echo "<a href='#myModalSMS" . $pm['id'] . "' data-toggle='modal' data-id='" . $pm['id'] . "' class='btn btn-xs btn-primary btn-block' title='Kirim pesan SMS ke $pm[nama] ($pm[nohp])'><i class='fa fa-envelope'></i> Kirim SMS</a>
										<form name='frm' method='POST' role='form' enctype='multipart/form-data'><input type='hidden' name='nohpasesi' value='$pm[nohp]'>
										<input type='hidden' name='namaasesi' value='$pm[nama]'>
										<button type='submit' name='telepon' class='btn btn-info btn-xs btn-block'><i class='fa fa-phone-square'></i> $pm[nohp]</button>
											<button type='submit' name='whatsapp' class='btn btn-success btn-xs btn-block'><i class='fa fa-whatsapp'></i> $pm[nohp]</button></form>";
					echo "<script>
										$(function(){
													$(document).on('click','.edit-record',function(e){
														e.preventDefault();
														$('#myModalSMS" . $pm['id'] . "').modal('show');
													});
											});
									</script>
									<!-- Modal -->
										<form role='form' action='smsasesibaru.php' method='POST' enctype='multipart/form-data'>
										<div class='modal fade' id='myModalSMS" . $pm['id'] . "' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
											<div class='modal-dialog'>
												<div class='modal-content'>
													<div class='modal-header'>
														<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Tutup</span></button>
														<h4 class='modal-title' id='myModalLabel'>Kirim SMS ke $pm[nama] (" . $pm['nohp'] . ")</h4>
													</div>
													<div class='modal-body'>
														<input type='hidden' class='form-control' name='idpost' value='$pm[id]'/>
														<input type='hidden' class='form-control' name='nohp" . $pm['id'] . "' value='$pm[nohp]'/>
														<div class='input-group'>
															<span class='input-group-addon'><i class='fa fa-envelope'></i></span>
															<textarea class='form-control' placeholder='Isi SMS'  name='pesan" . $pm['id'] . "' rows='2' cols='80' maxlength='160'>Yth. $pm[nama], </textarea>
														</div>
													</div>
													<div class='modal-footer'>
														<div align='left' class='col-md-6 col-sm-6 col-xs-6'>
															<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>
														</div>
														<div align='right' class='col-md-6 col-sm-6 col-xs-6'>
															<button type='submit' class='btn btn-success' name='smsasesibaru'>Kirim SMS</button>
														</div>
													</div>
												</div>
											</div>
										</div>
										</form>
									<!-- Modal -->";
				}
				echo "</td></tr>";
				$no++;
			}
		}
		echo "</tbody></table>
					</div>
				</div>
				</div>
			</div>
			</div>
		</section>";
	} elseif ($_GET['module'] == 'veriftukjarakjauh') {
		// QUERY TUK JARAK JAUH
		$qtukjarakjauh = "SELECT * FROM asesi_tukjarakjauh WHERE asesi_id='$_GET[ida]' AND skema_id='$_GET[id]' AND jadwal_id='$_GET[idj]'";
		$gettukjarakjauh = $conn->query($qtukjarakjauh);
		$tukjarakjauh = $gettukjarakjauh->fetch_assoc();

		$sqlasesi = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_GET[ida]'";
		$getasesi = $conn->query($sqlasesi);
		$as = $getasesi->fetch_assoc();
		$sqlgetatoken = "SELECT * FROM `user_pupr` WHERE `token` IS NOT NULL ORDER BY `id` ASC LIMIT 1";
		$gettoken = $conn->query($sqlgetatoken);
		$tokenx = $gettoken->fetch_assoc();
		$tokennya = $tokenx['token'];
		if (isset($_REQUEST['setujuidoc'])) {
			$cekdu = "SELECT * FROM `tukjarakjauh_doc` WHERE `id`='$_POST[id_doc]'";
			$result = $conn->query($cekdu);
			$asd = $result->fetch_assoc();
			$sqlgetasesi = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$asd[asesi_id]'";
			$getasesi = $conn->query($sqlgetasesi);
			$asi = $getasesi->fetch_assoc();
			//Kirim Email ke Asesi-----------------------------------
			$email = $asi['email'];
			$namanya = $asi['nama'];
			$no_hp = $asi['nohp'];
			// Kirim email dalam format HTML ke Pendaftar
			$pesan = "Dokumen Anda telah Disetujui<br /><br />  
									ID Asesi: $asi[no_pendaftaran] <br />
									Nama: $namanya <br />
									Nomor Handphone: $asi[nohp] <br />
						Dokumen: $asd[nama_doc]<br />
							<br /><br />Telah dinyatakan disetujui. Silahkan lihat di laman Dashboard Anda.<br /><br />";
			$subjek = "Selamat, Dokumen Asesi di SILSP Telah disetujui";
			$dari = "From: noreply@" . $urldomain . "\r\n";
			$dari .= "Content-type: text/html\r\n";
			// Kirim email ke member
			$sqlgetsmtp = "SELECT * FROM `smtp` WHERE `aktif`='Y'";
			$getsmtp = $conn->query($sqlgetsmtp);
			$gsmtp = $getsmtp->fetch_assoc();
			$sqlidentitas = "SELECT * FROM `identitas`";
			$identitas = $conn->query($sqlidentitas);
			$iden = $identitas->fetch_assoc();
			date_default_timezone_set("Asia/Jakarta");
			$mail = new PHPMailer;
			$mail->IsSMTP();
			$mail->SMTPSecure = $gsmtp['protokol'];
			$mail->Host = $gsmtp['host']; //host masing2 provider email
			$mail->SMTPDebug = 0;
			$mail->Port = $gsmtp['port'];
			$mail->SMTPAuth = true;
			$mail->Username = $gsmtp['username']; //user email
			$mail->Password = $gsmtp['password']; //password email 
			//Set who the message is to be sent from
			$mail->setFrom("$gsmtp[username]", $iden['nama_lsp']);
			//Set an alternative reply-to address
			$mail->addReplyTo("$gsmtp[username]", $iden['nama_lsp']);
			$mail->Subject = $subjek; //subyek email
			$mail->AddAddress($email, $namanya);  //tujuan email
			$mail->MsgHTML($pesan);
			if ($mail->Send()) {
				echo "";
			} else {
				echo "Notifikasi Email Gagal Terkirim, periksa pengaturan di menu Manajemen, sub menu Pengaturan SMTP";
			}
			//mail($email,$subjek,$pesan,$dari);
			//SMS Pendaftar
			$isisms = "Yth. $namanya Dokumen $asd[nama_doc] Anda telah disetujui, lihat info di laman http://" . $urldomain . ".";
			if (strlen($no_hp) > 8) {
				$sqloutbox = "INSERT INTO `outbox` (`DestinationNumber`, `TextDecoded`, `Coding`,`SenderID`,`CreatorID`) VALUES ('$no_hp','$isisms','Default_No_Compression','MyPhone1','MyPhone1')";
				$outbox = $conn->query($sqloutbox);
			}
			//-----------------------------------------------------
			if ($result->num_rows != 0) {
				$conn->query("UPDATE `tukjarakjauh_doc` SET `status`='A', `catatan_asesor`='$_POST[catatan_asesor]' WHERE `id`='$_POST[id_doc]'");
				echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Persetujuan Dokumen Sukses</h4>
				Anda Telah Berhasil Menyetujui <b>Dokumen Persyaratan Sertifikasi</b></div>";
			} else {
				echo "<script>alert('Maaf Dokumen Persyaratan tersebut Tidak Ditemukan');</script>";
			}
		}
		if (isset($_REQUEST['tolakdoc'])) {
			$cekdu = "SELECT * FROM `tukjarakjauh_doc` WHERE `id`='$_POST[id_doc]'";
			$result = $conn->query($cekdu);
			$asd = $result->fetch_assoc();
			$sqlgetasesi = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$asd[asesi_id]'";
			$getasesi = $conn->query($sqlgetasesi);
			$asi = $getasesi->fetch_assoc();
			//Kirim Email ke Asesi-----------------------------------
			$email = $asi['email'];
			$namanya = $asi['nama'];
			$no_hp = $asi['nohp'];
			// Kirim email dalam format HTML ke Pendaftar
			$pesan = "Dokumen Anda Ditolak<br /><br />  
									ID Asesi: $asi[no_pendaftaran] <br />
									Nama: $namanya <br />
									Nomor Handphone: $asi[nohp] <br />
						Dokumen: $asd[nama_doc]<br />
							<br /><br />Telah dinyatakan ditolak. Silahkan lihat di laman Dashboard Anda.<br /><br />";
			$subjek = "Maaf, Dokumen Asesi di SILSP ditolak";
			$dari = "From: noreply@" . $urldomain . "\r\n";
			$dari .= "Content-type: text/html\r\n";
			// Kirim email ke member
			$sqlgetsmtp = "SELECT * FROM `smtp` WHERE `aktif`='Y'";
			$getsmtp = $conn->query($sqlgetsmtp);
			$gsmtp = $getsmtp->fetch_assoc();
			$sqlidentitas = "SELECT * FROM `identitas`";
			$identitas = $conn->query($sqlidentitas);
			$iden = $identitas->fetch_assoc();
			date_default_timezone_set("Asia/Jakarta");
			$mail = new PHPMailer;
			$mail->IsSMTP();
			$mail->SMTPSecure = $gsmtp['protokol'];
			$mail->Host = $gsmtp['host']; //host masing2 provider email
			$mail->SMTPDebug = 0;
			$mail->Port = $gsmtp['port'];
			$mail->SMTPAuth = true;
			$mail->Username = $gsmtp['username']; //user email
			$mail->Password = $gsmtp['password']; //password email 
			//Set who the message is to be sent from
			$mail->setFrom("$gsmtp[username]", $iden['nama_lsp']);
			//Set an alternative reply-to address
			$mail->addReplyTo("$gsmtp[username]", $iden['nama_lsp']);
			$mail->Subject = $subjek; //subyek email
			$mail->AddAddress($email, $namanya);  //tujuan email
			$mail->MsgHTML($pesan);
			if ($mail->Send()) {
				echo "";
			} else {
				echo "Notifikasi Email Gagal Terkirim, periksa pengaturan di menu Manajemen, sub menu Pengaturan SMTP";
			}
			//mail($email,$subjek,$pesan,$dari);
			//SMS Pendaftar
			$isisms = "Yth. $namanya Dokumen $asd[nama_doc] Anda telah ditolak, lihat info di laman http://" . $urldomain . ".";
			if (strlen($no_hp) > 8) {
				$sqloutbox = "INSERT INTO `outbox` (`DestinationNumber`, `TextDecoded`, `Coding`,`SenderID`,`CreatorID`) VALUES ('$no_hp','$isisms','Default_No_Compression','MyPhone1','MyPhone1')";
				$outbox = $conn->query($sqloutbox);
			}
			//-----------------------------------------------------
			if ($result->num_rows != 0) {
				$conn->query("UPDATE `tukjarakjauh_doc` SET `status`='R',`catatan_asesor`='$_POST[catatan_asesor]' WHERE `id`='$_POST[id_doc]'");
				echo "<div class='alert alert-danger alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-times'></i> Penolakan Dokumen Sukses</h4>
				Anda Telah Berhasil Menolak <b>Dokumen Persyaratan Sertifikasi</b></div>";
				if ($pupr > 0) {
					// kirim status ke siki.pu.go.id ======================================
					$urlnyaxxx = 'https://siki.pu.go.id/siki-api/v1/permohonan-skk/' . $_GET['idpmh'];
					$curlxxx = curl_init();
					curl_setopt_array($curlxxx, array(
						CURLOPT_URL => $urlnyaxxx,
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_ENCODING => '',
						CURLOPT_MAXREDIRS => 10,
						CURLOPT_TIMEOUT => 0,
						CURLOPT_FOLLOWLOCATION => true,
						CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
						CURLOPT_CUSTOMREQUEST => 'POST',
						CURLOPT_POSTFIELDS => array('kd_status' => '11', 'keterangan' => 'Dokumen Tidak Lengkap'),
						CURLOPT_HTTPHEADER => array(
							'token: ' . $tokennya
						),
					));
					$responsexxx = curl_exec($curlxxx);
					curl_close($curlxxx);
					// =================================================================
				}
			} else {
				echo "<script>alert('Maaf Dokumen Persyaratan tersebut Tidak Ditemukan');</script>";
			}
		}
		if (isset($_REQUEST['setujuiasesmen'])) {
			$tgl_daftar = date("Y-m-d");
			$querydas = "UPDATE `asesi_tukjarakjauh` SET `status`='A', `asesor_id`='$_SESSION[namauser]', `tgl_verifikasi`='$tgl_daftar' WHERE `id`='$_GET[idas]' AND `asesi_id`='$_GET[ida]' AND `skema_id`='$_GET[id]' AND `jadwal_id`='$_GET[idj]'";
			$querycek = "SELECT * FROM `asesi_tukjarakjauh` WHERE `id`='$_GET[idas]' AND `asesi_id`='$_GET[ida]' AND `skema_id`='$_GET[id]' AND `jadwal_id`='$_GET[idj]' AND `status`='A'";
			$resultc = $conn->query($querycek);
			$row_cnt = $resultc->num_rows;
			// digital signature process =====================================================
			$folderPath = "../foto_tandatangan/";
			if (empty($_POST['signed'])) {
				echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Persetujuan Asesmen Sukses</h4>
				Anda Telah Berhasil menyetujui permohonan asesmen Asesi pada skema ini<b></b></div>";
			} else {
				$image_parts = explode(";base64,", $_POST['signed']);
				$image_type_aux = explode("image/", $image_parts[0]);
				$image_type = $image_type_aux[1];
				$image_base64 = base64_decode($image_parts[1]);
				$file = $folderPath . uniqid() . '.' . $image_type;
				file_put_contents($file, $image_base64);
				$url =  "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
				$iddokumen = md5($url);
				$escaped_url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
				$alamatip = $_SERVER['REMOTE_ADDR'];
				$sqlinputdigisign = "INSERT INTO `logdigisign`(`id_dokumen`,`id_asesi`, `id_skema`, `url_ditandatangani`, `nama_dokumen`, `penandatangan`, `file`, `ip`) VALUES ('$iddokumen','$_SESSION[namauser]','$_GET[id]','$escaped_url','FR-TUK.FORMULIR PERMOHONAN TUK JARAK JAUH','$_SESSION[namalengkap]','$file','$alamatip')";
				$conn->query($sqlinputdigisign);
			}
			// end digital signature process =================================================
			if ($row_cnt == 0) {
				$conn->query($querydas);
				//Notifikasi Email dan SMS====================================================
				$sqlgetskema = "SELECT * FROM `skema_kkni` WHERE `id`='$_GET[id]'";
				$getskema = $conn->query($sqlgetskema);
				$gs = $getskema->fetch_assoc();
				$email = $as['email'];
				$namanya = $as['nama'];
				$no_hp = $as['nohp'];
				// Kirim email dalam format HTML ke Pendaftar
				$pesan = "Pendaftaran Asesmen Skema $gs[kode_skema] - $gs[judul]<br /><br />  
									ID Asesi: $as[no_pendaftaran] <br />
									Nama: $namanya <br />
									Nomor Handphone: $as[nohp] <br />
						Skema: $gs[kode_skema] - $gs[judul]<br />
							<br /><br />Telah dinyatakan disetujui. Silahkan lihat jadwal asesmen di laman Dashboard Anda.<br /><br />";
				$subjek = "Pendaftaran Asesmen di SILSP Telah disetujui";
				$dari = "From: noreply@" . $urldomain . "\r\n";
				$dari .= "Content-type: text/html\r\n";
				// Kirim email ke member
				$sqlgetsmtp = "SELECT * FROM `smtp` WHERE `aktif`='Y'";
				$getsmtp = $conn->query($sqlgetsmtp);
				$gsmtp = $getsmtp->fetch_assoc();
				$sqlidentitas = "SELECT * FROM `identitas`";
				$identitas = $conn->query($sqlidentitas);
				$iden = $identitas->fetch_assoc();
				date_default_timezone_set("Asia/Jakarta");
				$mail = new PHPMailer;
				$mail->IsSMTP();
				$mail->SMTPSecure = $gsmtp['protokol'];
				$mail->Host = $gsmtp['host']; //host masing2 provider email
				$mail->SMTPDebug = 0;
				$mail->Port = $gsmtp['port'];
				$mail->SMTPAuth = true;
				$mail->Username = $gsmtp['username']; //user email
				$mail->Password = $gsmtp['password']; //password email 
				//Set who the message is to be sent from
				$mail->setFrom("$gsmtp[username]", $iden['nama_lsp']);
				//Set an alternative reply-to address
				$mail->addReplyTo("$gsmtp[username]", $iden['nama_lsp']);
				$mail->Subject = $subjek; //subyek email
				$mail->AddAddress($email, $namanya);  //tujuan email
				$mail->MsgHTML($pesan);
				if ($mail->Send()) {
					echo "";
				} else {
					echo "Notifikasi Email Gagal Terkirim, periksa pengaturan di menu Manajemen, sub menu Pengaturan SMTP";
				}
				//mail($email,$subjek,$pesan,$dari);
				//SMS Pendaftar
				$isisms = "Yth. $namanya Pendaftaran Asesmen Skema $gs[kode_skema]-$gs[judul] Anda telah disetujui, lihat jadwal di laman http://" . $urldomain . ".";
				if (strlen($no_hp) > 8) {
					$sqloutbox = "INSERT INTO `outbox` (`DestinationNumber`, `TextDecoded`, `Coding`,`SenderID`,`CreatorID`) VALUES ('$no_hp','$isisms','Default_No_Compression','MyPhone1','MyPhone1')";
					$outbox = $conn->query($sqloutbox);
				}
				//============================================================================
				echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Persetujuan Asesmen Sukses</h4>
			Anda Telah Berhasil menyetujui permohonan asesmen Asesi pada skema ini<b></b></div>";
			} else {
				echo "<script>alert('Maaf Asesi sudah disetujui pada skema ini'); window.location = '?module=syarat&id=$_GET[id]&ida=$_GET[ida]'</script>";
			}
			if ($pupr > 0) {
				// kirim status ke siki.pu.go.id ======================================
				$urlnyaxxx = 'https://siki.pu.go.id/siki-api/v1/permohonan-skk/' . $_GET['idpmh'];
				$curlxxx = curl_init();
				curl_setopt_array($curlxxx, array(
					CURLOPT_URL => $urlnyaxxx,
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => '',
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 0,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => 'POST',
					CURLOPT_POSTFIELDS => array('kd_status' => '10', 'keterangan' => 'Dokumen Lengkap'),
					CURLOPT_HTTPHEADER => array(
						'token: ' . $tokennya
					),
				));
				$responsexxx = curl_exec($curlxxx);
				curl_close($curlxxx);
				// =================================================================
			}
		}
		if (isset($_REQUEST['tolakasesmen'])) {
			$tgl_daftar = date("Y-m-d");
			$querydas = "UPDATE `asesi_tukjarakjauh` SET `status`='R' WHERE `id`='$_GET[idas]' AND `asesi_id`='$_GET[ida]' AND `skema_id`='$_GET[id]' AND `jadwal_id`='$_GET[idj]'";
			$querycek = "SELECT * FROM `asesi_tukjarakjauh` WHERE `id`='$_GET[idas]' AND `asesi_id`='$_GET[ida]' AND `skema_id`='$_GET[id]' AND `jadwal_id`='$_GET[idj]' AND `status`='R'";
			$resultc = $conn->query($querycek);
			$row_cnt = $resultc->num_rows;
			$folderPath = "../foto_tandatangan/";
			if (empty($_POST['signed'])) {
				echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Persetujuan Asesmen Sukses</h4>
				Anda Telah Berhasil menyetujui permohonan asesmen Asesi pada skema ini<b></b></div>";
			} else {
				$image_parts = explode(";base64,", $_POST['signed']);
				$image_type_aux = explode("image/", $image_parts[0]);
				$image_type = $image_type_aux[1];
				$image_base64 = base64_decode($image_parts[1]);
				$file = $folderPath . uniqid() . '.' . $image_type;
				file_put_contents($file, $image_base64);
				$url =  "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
				$iddokumen = md5($url);
				$escaped_url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
				$alamatip = $_SERVER['REMOTE_ADDR'];
				$sqlinputdigisign = "INSERT INTO `logdigisign`(`id_dokumen`,`id_asesi`, `id_skema`, `url_ditandatangani`, `nama_dokumen`, `penandatangan`, `file`, `ip`) VALUES ('$iddokumen','$_SESSION[namauser]','$_GET[id]','$escaped_url','FR-TUK.FORMULIR PERMOHONAN TUK JARAK JAUH','$_SESSION[namalengkap]','$file','$alamatip')";
				$conn->query($sqlinputdigisign);
			}
			if ($row_cnt == 0) {
				$conn->query($querydas);
				//Notifikasi Email dan SMS====================================================
				$sqlgetskema = "SELECT * FROM `skema_kkni` WHERE `id`='$_GET[id]'";
				$getskema = $conn->query($sqlgetskema);
				$gs = $getskema->fetch_assoc();
				$email = $as['email'];
				$namanya = $as['nama'];
				$no_hp = $as['nohp'];
				// Kirim email dalam format HTML ke Pendaftar
				$pesan = "Pendaftaran Asesmen Skema $gs[kode_skema] - $gs[judul]<br /><br />  
									ID Asesi: $as[no_pendaftaran] <br />
									Nama: $namanya <br />
									Nomor Handphone: $as[nohp] <br />
						Skema: $gs[kode_skema] - $gs[judul]<br />
							<br /><br />Telah dinyatakan DITOLAK. Silahkan lihat info selengkapnya di laman " . $urldomain . ".<br /><br />";
				$subjek = "Pendaftaran Asesmen di SILSP ditolak";
				$dari = "From: noreply@" . $urldomain . "\r\n";
				$dari .= "Content-type: text/html\r\n";
				// Kirim email ke member
				$sqlgetsmtp = "SELECT * FROM `smtp` WHERE `aktif`='Y'";
				$getsmtp = $conn->query($sqlgetsmtp);
				$gsmtp = $getsmtp->fetch_assoc();
				$sqlidentitas = "SELECT * FROM `identitas`";
				$identitas = $conn->query($sqlidentitas);
				$iden = $identitas->fetch_assoc();
				date_default_timezone_set("Asia/Jakarta");
				$mail = new PHPMailer;
				$mail->IsSMTP();
				$mail->SMTPSecure = $gsmtp['protokol'];
				$mail->Host = $gsmtp['host']; //host masing2 provider email
				$mail->SMTPDebug = 0;
				$mail->Port = $gsmtp['port'];
				$mail->SMTPAuth = true;
				$mail->Username = $gsmtp['username']; //user email
				$mail->Password = $gsmtp['password']; //password email 
				//Set who the message is to be sent from
				$mail->setFrom("$gsmtp[username]", $iden['nama_lsp']);
				//Set an alternative reply-to address
				$mail->addReplyTo("$gsmtp[username]", $iden['nama_lsp']);
				$mail->Subject = $subjek; //subyek email
				$mail->AddAddress($email, $namanya);  //tujuan email
				$mail->MsgHTML($pesan);
				if ($mail->Send()) {
					echo "";
				} else {
					echo "Notifikasi Email Gagal Terkirim, periksa pengaturan di menu Manajemen, sub menu Pengaturan SMTP";
				}
				//mail($email,$subjek,$pesan,$dari);
				//SMS Pendaftar
				$isisms = "Yth. $namanya, Maaf Pendaftaran Asesmen Skema $gs[kode_skema]-$gs[judul] Anda telah DITOLAK, lihat info di laman http://" . $urldomain . ".";
				if (strlen($no_hp) > 8) {
					$sqloutbox = "INSERT INTO `outbox` (`DestinationNumber`, `TextDecoded`, `Coding`,`SenderID`,`CreatorID`) VALUES ('$no_hp','$isisms','Default_No_Compression','MyPhone1','MyPhone1')";
					$outbox = $conn->query($sqloutbox);
				}
				//============================================================================
				echo "<div class='alert alert-danger alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Permohonan Asesmen Berhasil Ditolak</h4>
			Anda Telah Berhasil menolak permohonan asesmen Asesi pada skema ini<b></b></div>";
				if ($pupr > 0) {
					// kirim status ke siki.pu.go.id ======================================
					$urlnyaxxx = 'https://siki.pu.go.id/siki-api/v1/permohonan-skk/' . $_GET['idpmh'];
					$curlxxx = curl_init();
					curl_setopt_array($curlxxx, array(
						CURLOPT_URL => $urlnyaxxx,
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_ENCODING => '',
						CURLOPT_MAXREDIRS => 10,
						CURLOPT_TIMEOUT => 0,
						CURLOPT_FOLLOWLOCATION => true,
						CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
						CURLOPT_CUSTOMREQUEST => 'POST',
						CURLOPT_POSTFIELDS => array('kd_status' => '90', 'keterangan' => 'Permohonan Ditolak'),
						CURLOPT_HTTPHEADER => array(
							'token: ' . $tokennya
						),
					));
					$responsexxx = curl_exec($curlxxx);
					curl_close($curlxxx);
					// =================================================================
				}
			} else {
				echo "<script>alert('Maaf Asesi sudah ditolak pada skema ini'); window.location = '?module=syarat&id=$_GET[id]&ida=$_GET[ida]'</script>";
			}
		}
		if (isset($_REQUEST['ubahjadwalasesmen'])) {
			$tgl_daftar = date("Y-m-d");
			$sqljadwal = "SELECT * FROM `jadwal_asesmen` WHERE `id`='$_POST[jadwalasesmenubah]'";
			$jadwal = $conn->query($sqljadwal);
			$jdq = $jadwal->fetch_assoc();
			$querydas = "UPDATE `asesi_asesmen` SET `status`='A', `id_jadwal`='$_POST[jadwalasesmenubah]', `tgl_asesmen`='$jdq[tgl_asesmen]' WHERE `id`='$_GET[idas]' AND `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$_GET[id]'";
			$querycek = "SELECT * FROM `asesi_asesmen` WHERE `id`='$_GET[idas]' AND `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$_GET[id]' AND `status`='A'";
			$resultc = $conn->query($querycek);
			$row_cnt = $resultc->num_rows;
			if ($row_cnt > 0) {
				$conn->query($querydas);
				// sesuaikan id jadwal di database apl 02
				$querydasapl02 = "UPDATE `asesi_apl02` SET `id_jadwal`='$_POST[jadwalasesmenubah]' WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$_GET[id]'";
				$conn->query($querydasapl02);
				// digital signature process =====================================================
				$folderPath = "../foto_tandatangan/";
				if (empty($_POST['signed'])) {
					echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Persetujuan Asesmen Sukses</h4>
				Anda Telah Berhasil menyetujui permohonan asesmen Asesi pada skema ini<b></b></div>";
				} else {
					$image_parts = explode(";base64,", $_POST['signed']);
					$image_type_aux = explode("image/", $image_parts[0]);
					$image_type = $image_type_aux[1];
					$image_base64 = base64_decode($image_parts[1]);
					$file = $folderPath . uniqid() . '.' . $image_type;
					file_put_contents($file, $image_base64);
					$url =  "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
					$iddokumen = md5($url);
					$escaped_url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
					$alamatip = $_SERVER['REMOTE_ADDR'];
					$sqlinputdigisign = "INSERT INTO `logdigisign`(`id_dokumen`,`id_asesi`,`id_skema`, `url_ditandatangani`, `nama_dokumen`, `penandatangan`, `file`, `ip`) VALUES ('$iddokumen','$_SESSION[namauser]','$_GET[id]','$escaped_url','FR-TUK.FORMULIR PERMOHONAN TUK JARAK JAUH','$_SESSION[namalengkap]','$file','$alamatip')";
					$conn->query($sqlinputdigisign);
				}
				// end digital signature process =================================================
				//Notifikasi Email dan SMS====================================================
				$sqlgetskema = "SELECT * FROM `skema_kkni` WHERE `id`='$_GET[id]'";
				$getskema = $conn->query($sqlgetskema);
				$gs = $getskema->fetch_assoc();
				$email = $as['email'];
				$namanya = $as['nama'];
				$no_hp = $as['nohp'];
				// Kirim email dalam format HTML ke Pendaftar
				$pesan = "Perubahan Jadwal Asesmen Skema $gs[kode_skema] - $gs[judul]<br /><br />  
									ID Asesi: $as[no_pendaftaran] <br />
									Nama: $namanya <br />
									Nomor Handphone: $as[nohp] <br />
						Skema: $gs[kode_skema] - $gs[judul]<br />
							<br /><br />Telah dinyatakan diubah. Silahkan lihat jadwal asesmen di laman Dashboard Anda.<br /><br />";
				$subjek = "Perubahan Jadwal Asesmen di SILSP Telah disetujui";
				$dari = "From: noreply@" . $urldomain . "\r\n";
				$dari .= "Content-type: text/html\r\n";
				// Kirim email ke member
				$sqlgetsmtp = "SELECT * FROM `smtp` WHERE `aktif`='Y'";
				$getsmtp = $conn->query($sqlgetsmtp);
				$gsmtp = $getsmtp->fetch_assoc();
				$sqlidentitas = "SELECT * FROM `identitas`";
				$identitas = $conn->query($sqlidentitas);
				$iden = $identitas->fetch_assoc();
				date_default_timezone_set("Asia/Jakarta");
				$mail = new PHPMailer;
				$mail->IsSMTP();
				$mail->SMTPSecure = $gsmtp['protokol'];
				$mail->Host = $gsmtp['host']; //host masing2 provider email
				$mail->SMTPDebug = 0;
				$mail->Port = $gsmtp['port'];
				$mail->SMTPAuth = true;
				$mail->Username = $gsmtp['username']; //user email
				$mail->Password = $gsmtp['password']; //password email 
				//Set who the message is to be sent from
				$mail->setFrom("$gsmtp[username]", $iden['nama_lsp']);
				//Set an alternative reply-to address
				$mail->addReplyTo("$gsmtp[username]", $iden['nama_lsp']);
				$mail->Subject = $subjek; //subyek email
				$mail->AddAddress($email, $namanya);  //tujuan email
				$mail->MsgHTML($pesan);
				if ($mail->Send()) {
					echo "";
				} else {
					echo "Notifikasi Email Gagal Terkirim, periksa pengaturan di menu Manajemen, sub menu Pengaturan SMTP";
				}
				//mail($email,$subjek,$pesan,$dari);
				//SMS Pendaftar
				$isisms = "Yth. $namanya Jadwal Asesmen Skema $gs[kode_skema]-$gs[judul] Anda telah diubah, lihat jadwal di laman http://" . $urldomain . ".";
				if (strlen($no_hp) > 8) {
					$sqloutbox = "INSERT INTO `outbox` (`DestinationNumber`, `TextDecoded`, `Coding`,`SenderID`,`CreatorID`) VALUES ('$no_hp','$isisms','Default_No_Compression','MyPhone1','MyPhone1')";
					$outbox = $conn->query($sqloutbox);
				}
				//============================================================================
				echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Perubahan Jadwal Asesmen Sukses</h4>
			Anda Telah Berhasil mengubah jadwal asesmen Asesi pada skema ini<b></b></div>";
			} else {
				echo "<script>alert('Maaf Asesi sudah disetujui pada skema ini'); window.location = '?module=syarat&id=$_GET[id]&ida=$_GET[ida]'</script>";
			}
		}
		$sqlgetskema = "SELECT * FROM `skema_kkni` WHERE `id`='$_GET[id]'";
		$getskema = $conn->query($sqlgetskema);
		$gs = $getskema->fetch_assoc();
	?>
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1>
				Dokumen Persyaratan Permohonan TUK Jarak Jauh
				<small>Input Data</small>
			</h1>
			<ol class="breadcrumb">
				<li><a href="media.php?module=home"><i class="fa fa-dashboard"></i> Home</a></li>
				<li class="active">Data Dokumen Persyaratan</li>
			</ol>
		</section>
		<!-- Main content -->
		<section class="content">
			<div class="row">
				<div class="col-xs-12">
					<div class="box-group" id="accordion"> <?php echo "
		<div class='panel box box-success'>
			<div class='box-header with-border'>
			<h4 class='box-title'>
				<a data-toggle='collapse' data-parent='#accordion' href='#collapseThree'>
				Pendaftaran TUK Jarak Jauh Skema $gs[judul]
				</a>
			</h4>
			</div>
			<div id='collapseThree' class='panel-collapse collapse in'>
			<div class='box-body'>";
																									function getAge($date)
																									{ // Y-m-d format
																										$now = explode("-", date('Y-m-d'));
																										$dob = explode("-", $date);
																										$dif = $now[0] - $dob[0];
																										if ($dob[1] > $now[1]) { // birthday month has not hit this year
																											$dif -= 1;
																										} elseif ($dob[1] == $now[1]) { // birthday month is this month, check day
																											if ($dob[2] > $now[2]) {
																												$dif -= 1;
																											} elseif ($dob[2] == $now[2]) { // Happy Birthday!
																												$dif = $dif;
																											};
																										};
																										return $dif;
																									}
																									$usia = getAge($as['tgl_lahir']);
																									if ($usia < 91) {
																										$syaratusia = "<font color='green'><b>Calon Asesi telah memenuhi Persyaratan Usia</b></font>";
																									} else {
																										$syaratusia = "<font color='red'><b>Maaf, Calon Asesi tidak memenuhi Persyaratan Usia</b></font>";
																									}
																									$sqlpendidikan = "SELECT * FROM `pendidikan` WHERE `id`='$as[pendidikan]'";
																									$pendidikan = $conn->query($sqlpendidikan);
																									$pdas = $pendidikan->fetch_assoc();
																									if ($as['pendidikan'] > 1) {
																										$syaratpend = "<font color='green'><b>Calon Asesi telah memenuhi Persyaratan Pendidikan</b></font>";
																									} else {
																										$syaratpend = "<font color='red'><b>Maaf, Calon Asesi tidak memenuhi Persyaratan Pendidikan</b></font>";
																									}
																									echo "<p>Asesi bernama <b>$as[nama]</b>, Nomor Pendaftaran <b>$as[no_pendaftaran]</b><br>
				Usia Calon Asesi adalah <b>$usia tahun</b>, $syaratusia<br>
				Pendidikan terakhir Calon Asesi adalah <b>$pdas[jenjang_pendidikan]</b>, $syaratpend<br></p>";
																									$querycekasesmen = "SELECT * FROM `asesi_tukjarakjauh` WHERE `id`='$_GET[idas]' AND `asesi_id`='$_GET[ida]' AND `skema_id`='$_GET[id]' AND `jadwal_id`='$_GET[idj]' AND `status`='R'";
																									$cekasesmen = $conn->query($querycekasesmen);
																									$asesmen = $cekasesmen->num_rows;
																									if ($asesmen <> 0) {
																										echo "<div class='alert alert-danger alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-warning'></i> Permohonan Asesmen Ditolak</h4>
					Permohonan asesmen Asesi pada skema ini dinyatakan ditolak<b></b></div>";
																									}
																									$querycekasesmen1 = "SELECT * FROM `asesi_tukjarakjauh` WHERE `id`='$_GET[idas]' AND `asesi_id`='$_GET[ida]' AND `skema_id`='$_GET[id]' AND `jadwal_id`='$_GET[idj]' AND `status`='A'";
																									$cekasesmen1 = $conn->query($querycekasesmen1);
																									$asesmen1 = $cekasesmen1->num_rows;
																									if ($asesmen1 <> 0) {
																										echo "<div class='alert alert-success alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Permohonan TUK Jarak Jauh Sudah Disetujui</h4>
					Permohonan TUK Jarak Jauh Asesi pada skema ini dinyatakan disetujui<b></b></div>";
																									}
																									if ($usia < 91 && $as['pendidikan'] > 1) {
																										echo "
						<form role='form' method='POST' enctype='multipart/form-data'>
												<div class='row'>
						<div class='box-body'>
						<h1>FR.PERMOHONAN TUK JARAK JAUH</h1>
						<h2>Bagian 1: Rincian Data Pemohon TUK Jarak Jauh</h2>
						<h3>a. Data Pribadi</h3>
						<div class='row'>
							<strong class='col-md-6 col-sm-12 col-xs-12'>Nama Lengkap</strong><span class='col-md-6 col-sm-12 col-xs-12'>";
																										$namanya = strtoupper($as['nama']);
																										echo "$namanya</span>
							<strong class='col-md-6 col-sm-12 col-xs-12'>Tempat/ Tgl. Lahir</strong><span class='col-md-6 col-sm-12 col-xs-12'>";
																										$ttl = ucwords(strtolower($as['tmp_lahir'])) . ", " . tgl_indo($as['tgl_lahir']);
																										echo "$ttl</span>
							<strong class='col-md-6 col-sm-12 col-xs-12'>Jenis Kelamin</strong><span class='col-md-6 col-sm-12 col-xs-12'>";
																										switch ($as['jenis_kelamin']) {
																											case "L":
																												$jeniskelamin = "Laki-laki";
																												break;
																											case "P":
																												$jeniskelamin = "Perempuan";
																												break;
																											default:
																												$jeniskelamin = "Laki-laki / Perempuan*";
																												break;
																										}
																										echo "$jeniskelamin</span>
							<strong class='col-md-6 col-sm-12 col-xs-12'>Kebangsaan</strong><span class='col-md-6 col-sm-12 col-xs-12'>";
																										$kebangsaan = ucwords(strtolower($as['kebangsaan']));
																										echo "$kebangsaan</span>
							<strong class='col-md-6 col-sm-12 col-xs-12'>Alamat Rumah</strong><span class='col-md-6 col-sm-12 col-xs-12'>";
																										$alamat = $as['alamat'] . " RT " . $as['RT'] . " RW " . $as['RW'] . " Kel./Desa " . $as['kelurahan'];
																										$sqlwil1c = "SELECT * FROM `data_wilayah` WHERE `id_wil`='$as[kecamatan]'";
																										$wilayah1c = $conn->query($sqlwil1c);
																										$wil1c = $wilayah1c->fetch_assoc();
																										$sqlwil2c = "SELECT * FROM `data_wilayah` WHERE `id_wil`='$as[kota]'";
																										$wilayah2c = $conn->query($sqlwil2c);
																										$wil2c = $wilayah2c->fetch_assoc();
																										$sqlwil3c = "SELECT * FROM `data_wilayah` WHERE `id_wil`='$as[propinsi]'";
																										$wilayah3c = $conn->query($sqlwil3c);
																										$wil3c = $wilayah3c->fetch_assoc();
																										$alamat2 = trim($wil1c['nm_wil']) . ", " . trim($wil2c['nm_wil']) . ", " . trim($wil3c['nm_wil']) . " Kodepos " . $as['kodepos'];
																										$alamattampil = strtoupper($alamat) . " " . strtoupper($alamat2);
																										echo "$alamattampil</span>
							<strong class='col-md-6 col-sm-12 col-xs-12'>No. Telp/ Email</strong><span class='col-md-6 col-sm-12 col-xs-12'>Rumah : $as[nohp]<br />Kantor : $as[telp_kantor]<br />HP : $as[nohp]<br />Email : $as[email]</span>
							<strong class='col-md-6 col-sm-12 col-xs-12'>Pendidikan Terakhir</strong><span class='col-md-6 col-sm-12 col-xs-12'>";
																										$sqlpendidikan = "SELECT * FROM `pendidikan` WHERE `id`='$as[pendidikan]'";
																										$pendidikan = $conn->query($sqlpendidikan);
																										$pdd = $pendidikan->fetch_assoc();
																										$pendidikannyaa = $pdd['jenjang_pendidikan'];
																										echo "$pendidikannyaa</span>
						</div>
						<h3>b. Data Pekerjaan Sekarang</h3>
						<div class='row'>
							<strong class='col-md-6 col-sm-12 col-xs-12'>Nama lembaga/ perusahaan</strong><span class='col-md-6 col-sm-12 col-xs-12'>";
																										$namakantornya = strtoupper($as['nama_kantor']);
																										echo "$namakantornya</span>
							<strong class='col-md-6 col-sm-12 col-xs-12'>Jabatan</strong><span class='col-md-6 col-sm-12 col-xs-12'>";
																										$sqlgetpekerjaan = "SELECT * FROM `pekerjaan` WHERE `id`='$as[pekerjaan]'";
																										$getpekerjaan = $conn->query($sqlgetpekerjaan);
																										$askrj = $getpekerjaan->fetch_assoc();
																										$jabatannya = strtoupper($askrj['pekerjaan']);
																										echo "$jabatannya</span>
							<strong class='col-md-6 col-sm-12 col-xs-12'>Alamat</strong><span class='col-md-6 col-sm-12 col-xs-12'>";
																										echo "$as[alamat_kantor]</span>
							<strong class='col-md-6 col-sm-12 col-xs-12'>No. Telp./ Fax./ Email</strong><span class='col-md-6 col-sm-12 col-xs-12'>";
																										echo "Telp.: $as[telp_kantor]<br />Fax. : $as[fax_kantor]<br />Email : $as[email_kantor]</span>
						</div>
						<h2>Bagian 2: Data dan Bukti Kelengkapan Pemohon</h2><br>
						<table TUKe id='table-example' class='table table-bordered table-striped'>
					<thead>
						<tr>
							<th>No.</th>
							<th>Alamat TUK</th>
							<th>Kepemilikan TUK</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>1.</td>
							<td>$tukjarakjauh[alamat_tuk]</td>
							<td>$tukjarakjauh[kepemilikan_tuk]</td>
						</tr>
					</tbody>
				</table><br>
						<h3>Bukti Pemenuhan Persyaratan TUK Jarak Jauh</h3>
						<div style='overflow-x:auto;'>
						<table id='table-example' class='table table-bordered table-striped'>
						<thead><tr><th>No.</th><th>Persyaratan<th>File Pendukung</th><th>Status</th><th>Catatan Asesor</th></tr></thead>
						<tbody>";
																										$no = 1;
																										$sqlasesidoc = "SELECT * FROM `tukjarakjauh_doc` WHERE `asesi_id`='$as[no_pendaftaran]' AND `skema_id`='$_GET[id]' AND `jadwal_id`='$_GET[idj]' ORDER BY `id` DESC";
																										$asesidoc = $conn->query($sqlasesidoc);
																										while ($pm = $asesidoc->fetch_assoc()) {
																											switch ($pm['status']) {
																												default:
																													/* $statusnya="
									<input type='hidden' name='id_doc' value='$pm[id]'>
										<button type='submit' class='btn btn-success btn-xs' name='setujuidoc'>Setujui</button>
										<button type='submit' onclick='return confirmationtolak()' class='btn btn-danger btn-xs' name='tolakdoc'>Tolak</button>
									"; */
																													$statusnya = "<font color='blue'><b>Menunggu Persetujuan</b></font>";
																													break;
																												case "A":
																													$statusnya = "<font color='green'><b>Disetujui</b></font>";
																													break;
																												case "R":
																													$statusnya = "<font color='red'><b>Ditolak</b></font>";
																													break;
																											}
																											// cek apakah sudah direvisi atau tidakwajibkan
																											$sqlcekrevdoc = "SELECT * FROM `tukjarakjauh_doc` WHERE `asesi_id`='$as[no_pendaftaran]' AND `skema_id`='$_GET[id]' AND `jadwal_id`='$_GET[idj]'";
																											$cekrevdoc = $conn->query($sqlcekrevdoc);
																											$jumrevdoc = $cekrevdoc->num_rows;
																											//=======================================================
																											if ($jumrevdoc > 0) {
																												$portfolioskpi = $conn->query("SELECT * FROM `persyaratan_tukjarakjauh` WHERE `id`='$pm[syarattuk_id]'");
																												$prt = $portfolioskpi->fetch_assoc();
																												echo "<tr class=gradeX><td>$no</td><td><b>$prt[persyaratan]</b><br>Nama Doc : <b><a href='#myModal" . $pm['id'] . "' data-toggle='modal' data-id='" . $pm['id'] . "'>$pm[nama_doc]</a></b><br>Tanggal Dok.: <b>" . tgl_indo($pm['tgl_doc']) . "</b></td><td>";
																												if (!empty($pm['file'])) {
																													echo "<a class='btn btn-success btn-xs'><span class='glyphicon glyphicon-ok' aria-hidden='true' title='Dokumen Berhasil Diunggah'></span></a>";
																													echo "&nbsp;<a href='#myModal" . $pm['id'] . "' class='btn btn-primary btn-xs' data-toggle='modal' data-id='" . $pm['id'] . "'><span class='glyphicon glyphicon-zoom-in' aria-hidden='true' title='Lihat/Unduh Dokumen'></span></a>";
																												} else {
																													echo "<span class='text-red'>Tidak ada dokumen</span>";
																												}
																												echo "</td><td>$statusnya";
																												echo "</td><td>$pm[catatan_asesor]</td></tr>";
																												$no++;
																											}
																											echo "<script>
								$(function(){
											$(document).on('click','.edit-record',function(e){
												e.preventDefault();
												$('#myModal" . $pm['id'] . "').modal('show');
											});
									});
							</script>
							<!-- Modal -->
							<div class='modal fade' id='myModal" . $pm['id'] . "' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
								<div class='modal-dialog'>
									<div class='modal-content'>
										<div class='modal-header'>
											<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Tutup</span></button>
											<h4 class='modal-title' id='myModalLabel'>Dokumen Porfolio " . $pm['nama_doc'] . "</h4>
											<h5 class='modal-title' id='myModalLabel'>Dokumen Porfolio " . $as['nama'] . " No. Pendaftaran " . $as['no_pendaftaran'] . "</h5>
										</div>";
																											if (substr($pm['file'], 0, 4) == "http") {
																												if (substr($pm['file'], -3) == "pdf") {
																													echo "<div class='modal-body'><embed src='$pm[file]' width='100%' height='600px'/>";
																												} else {
																													echo "<div class='modal-body'><embed src='$pm[file]' width='100%'/>";
																												}
																												echo "</div>
											<div class='modal-footer'>";
																												if ($pm['status'] == 'P') {
																													echo "<form role='form' method='POST' enctype='multipart/form-data'>";
																													echo "<input type='hidden' name='id_doc' value='$pm[id]'>
													<button type='submit' class='btn btn-success' name='setujuidoc'>Setujui</button>
													<button type='submit' onclick='return confirmationtolak()' class='btn btn-danger' name='tolakdoc'>Tolak</button>";
																													echo "</form>";
																												}
																												echo "<a class='btn btn-default' href='$pm[file]' download>Unduh</a><button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>
											</div>";
																											} else {
																												if (substr($pm['file'], -3) == "pdf") {
																													echo "<div class='modal-body'><embed src='../foto_tukjarakjauh/$pm[file]' width='100%' height='600px'/>";
																												} else {
																													echo "<div class='modal-body'><embed src='../foto_tukjarakjauh/$pm[file]' width='100%'/>";
																												}
																												echo "</div>
											<div class='modal-footer'>";
																												if ($pm['status'] == 'P') {
																													echo "<form role='form' method='POST' enctype='multipart/form-data'>
															<div class='form-group'><label>Catatan/Keterangan</label><input type='text' class='form-control' name='catatan_asesor' placeholder='Jika Tidak Ada Catatan/Keterangan Cukup Diisi - saja' required></div><br>";
																													echo "<input type='hidden' name='id_doc' value='$pm[id]'>
													<button type='submit' class='btn btn-success' name='setujuidoc'>Setujui</button>
													<button type='submit' onclick='return confirmationtolak()' class='btn btn-danger' name='tolakdoc'>Tolak</button>";
																													echo "</form>";
																												}
																												echo "<a class='btn btn-default' href='../foto_tukjarakjauh/$pm[file]' download>Unduh</a><button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>
											</div>";
																											}
																											echo "</div>
									</div>
							</div>";
																										}
																										echo "</tbody></table>
						<br />
						<h3>Asesor LSP</h3>
						<table id='table-example' class='table table-bordered table-striped'>
							<tr><td>Nama</td><td>$_SESSION[namalengkap]</td></tr>";
																										$sqlgetadminlsp = "SELECT * FROM `users` WHERE `username`='$_SESSION[namauser]'";
																										$getadminlsp = $conn->query($sqlgetadminlsp);
																										$admlsp = $getadminlsp->fetch_assoc();
																										echo "<tr><td>No. Reg.</td><td>$admlsp[no_induk]</td></tr>
						</table>
						</div>";
																										// cek tandatangan digital
																										$querycekasesmen2 = "SELECT * FROM `tukjarakjauh_doc` WHERE `id`='$_GET[idas]' AND `asesi_id`='$_GET[ida]' AND `skema_id`='$_GET[id]' AND `jadwal_id`='$_GET[idj]'";
																										$cekasesmen2 = $conn->query($querycekasesmen2)->fetch_assoc();
																										// UPDATE @FHM-Pusti 12 Jul 2023 : Query Panggil Persyaratan Skema
																										$qsyaratskema = $conn->query("SELECT * FROM persyaratan_tukjarakjauh a WHERE a.id_skemakkni='$_GET[id]'");
																										// while($ss = $qsyaratskema->fetch_assoc()){
																										// 	echo $ss['id'];
																										// 	echo $ss['persyaratan'];
																										// }
																										// END
																										//cek dokumen apakah lengkap
																										$sqlasesidoc = "SELECT * FROM `tukjarakjauh_doc` WHERE `asesi_id`='$_GET[ida]' AND `skema_id`='$_GET[id]' AND `jadwal_id`='$_GET[idj]' AND  `status`!='R'";
																										$asesidoc = $conn->query($sqlasesidoc);
																										$jumpm = $asesidoc->num_rows;
																										$sqlasesidoca = "SELECT * FROM `tukjarakjauh_doc` WHERE `asesi_id`='$_GET[ida]' AND `skema_id`='$_GET[id]' AND `jadwal_id`='$_GET[idj]' AND `status`='A'";
																										$asesidoca = $conn->query($sqlasesidoca);
																										$jumpma = $asesidoca->num_rows;
																										// UPDATE @FHM-PPM : Hitung	Jumlah Skema Persyaratan
																										$qskemapersyaratan = $conn->query("SELECT * FROM persyaratan_tukjarakjauh")->num_rows;
																										// UPDATE @FHM-PPM : Cek status asesi asesmen
																										$qasesiasesmen = $conn->query("SELECT * FROM asesi_tukjarakjauh WHERE `asesi_id`='$_GET[ida]' AND `skema_id`='$_GET[id]' AND `jadwal_id`='$_GET[idj]'")->fetch_assoc();
																										$url = "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
																										$iddokumen = md5($url);
																										$sqlcektandatangan = "SELECT * FROM `logdigisign` WHERE id_skema='$_GET[id]' AND nama_dokumen='FR-TUK.FORMULIR PERMOHONAN TUK JARAK JAUH' AND penandatangan='$_SESSION[namalengkap]' ORDER BY `id` DESC";
																										$cektandatangan = $conn->query($sqlcektandatangan);
																										$jumttd = $cektandatangan->num_rows;
																										$ttdx = $cektandatangan->fetch_assoc();
																										if ($jumttd > 0) {
																											echo "<label class='' for=''>Persetujuan/ Tanda Tangan yang telah Anda berikan:</label>
								<br/>
								<img src='$ttdx[file]' width='400px'/>
								<br/>
								<a href='ba_tukjarakjauh_pdf.php?idas=$_GET[idas]&ida=$_GET[ida]&id=$_GET[id]&idj=$tukjarakjauh[jadwal_id]' class='btn btn-primary' title='Unduh Berita Acara dan Ceklis Verifikasi TUK' target='_blank'>Berita Acara & Ceklis Verifikasi TUK</a>
								<a href='unduhsurattugasvertuk.php?idas=$_GET[idas]&ida=$_GET[ida]&id=$_GET[id]&idj=$tukjarakjauh[jadwal_id]' class='btn btn-success' title='Unduh Surat Tugas Verifikasi TUK' target='_blank'>Surat Tugas Verifikasi TUK</a>";
																										}

																										if ($qasesiasesmen['status'] == 'A' || $qasesiasesmen['status'] == 'R') {
																									?>

							<?php } else { ?>
								<div class="col-md-12">
									<label class="" for="">Tanda Tangan Verifikator:</label>
									<br />
									<div id="sig"></div>
									<br />
									<button id="clear" class="btn btn-default btn-xs">Hapus Tanda Tangan</button>
									<textarea id="signature64" name="signed" style="display: none"></textarea>
								</div>
								<script type="text/javascript">
									var sig = $('#sig').signature({
										syncField: '#signature64',
										syncFormat: 'PNG',
										color: '#58009F'
									});
									$('#clear').click(function(e) {
										e.preventDefault();
										sig.signature('clear');
										$("#signature64").val('');
									});
								</script>
							<?php } ?>

							<?php
																										echo "<div align='left' class='col-md-6 col-sm-6 col-xs-6'>
							<a class='btn btn-default' id=reset-validate-form href='?module=asesibaru'>Kembali</a>
						</div>
						<div align='right' class='col-md-6 col-sm-6 col-xs-6'>";
																										if ($qasesiasesmen['status'] == 'P') {
																											if ($jumpma == $qskemapersyaratan) { // Jika dokumen sudah disetujui semua maka akan tampil button disetujui dan ditolak
																												echo "<p>Berdasarkan ketentuan persyaratan dasar, maka pemohon:</p>";
																												echo "<button type='submit' class='btn btn-success' name='setujuiasesmen'>Sesuai</>";
																												echo "<button type='submit' onclick='return confirmationtolak()' class='btn btn-danger' name='tolakasesmen'>Tidak Sesuai</button>";
																											} else {
																												echo "<p>Berdasarkan ketentuan persyaratan dasar, maka pemohon:</p>";
																												echo "<a href='#' data-toggle='modal' data-id='" . $as['no_pendaftaran'] . "' class='btn btn-default' name='setujuiasesmendisable' title='Tombol akan aktif bila semua dokumen telah disetujui' disabled>Sesusai</a>";
																												echo "<button type='submit' onclick='#' class='btn btn-default' name='tolakasesmendisable' title='Tombol akan aktif bila semua dokumen telah disetujui' disabled>Tidak Sesuai</button>";
																											}
																										}
																										echo "</div>";
																										echo "</form>";
																									}
																									echo "</div>
			</div>
			</div>
		</div>
	</div>
	<!--accordion-->
			</div><!--col-->
			</div><!--row-->
		</section>";
																								}
																								// Bagian Input Ceklist Verifikasi TUK Skema LSP ==========================================================================================================
																								elseif ($_GET['module'] == 'inputceklist') {
																									if (!empty($_SESSION['namauser'])) {
																										$sqljadwaltuk = "SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
																										$jadwaltuk = $conn->query($sqljadwaltuk);
																										$pm0 = $jadwaltuk->fetch_assoc();
																										$sqlgetskema = "SELECT * FROM `skema_kkni` WHERE `id`='$pm0[id_skemakkni]'";
																										$getskema = $conn->query($sqlgetskema);
																										$gs = $getskema->fetch_assoc();
																										$sqllogin = "SELECT * FROM `asesor` WHERE `no_ktp`='$_SESSION[namauser]'";
																										$login = $conn->query($sqllogin);
																										$ketemu = $login->num_rows;
																										$rowAgen = $login->fetch_assoc();
																										if (isset($_REQUEST['simpanverifikasi'])) {
																											$sqlgetpersyaratantuk = "SELECT * FROM `skema_persyaratantuk` WHERE `id_skemakkni`='$pm0[id_skemakkni]' ORDER BY `id` ASC";
																											$getpersyaratantuk = $conn->query($sqlgetpersyaratantuk);
																											while ($unk2 = $getpersyaratantuk->fetch_assoc()) {
																												$id_asesor = 'asesor_' . $unk2['id'];
																												$id_perlengkapan = 'perlengkapan_' . $unk2['id'];
																												$id_skemakkni = 'skema_' . $unk2['id'];
																												$id_jadwal = 'jadwal_' . $unk2['id'];
																												$jumlah = 'jumlah_' . $unk2['id'];
																												$baik = 'baik_' . $unk2['id'];
																												$rusak = 'rusak_' . $unk2['id'];
																												$keterangan = 'keterangan_' . $unk2['id'];
																												$postasesor = $_POST[$id_asesor];
																												$postjadwal = $_POST[$id_jadwal];
																												$postskema = $_POST[$id_skemakkni];
																												$postperlengkapan = $_POST[$id_perlengkapan];
																												$postjumlah = $_POST[$jumlah];
																												$postbaik = $_POST[$baik];
																												$postrusak = $_POST[$rusak];
																												$postketerangan = $_POST[$keterangan];
																												$sqlcekjawaban = "SELECT * FROM `skema_ceklisvertuk` WHERE `id_asesor`='$postasesor' AND `id_jadwal`='$postjadwal' AND `id_skemakkni`='$postskema' AND `id_perlengkapan`='$postperlengkapan'";
																												$cekjawaban = $conn->query($sqlcekjawaban);
																												$jjw = $cekjawaban->num_rows;
																												if ($jjw == 0) {
																													$sqlinputjawaban = "INSERT INTO `skema_ceklisvertuk`(`id_asesor`, `id_jadwal`, `id_skemakkni`, `id_perlengkapan`, `jumlah`, `baik`, `rusak`, `keterangan`) VALUES ('$postasesor','$postjadwal','$postskema','$postperlengkapan','$postjumlah','$postbaik','$postrusak','$postketerangan')";
																													$conn->query($sqlinputjawaban);
																												} else {
																													$sqlinputjawaban = "UPDATE `skema_ceklisvertuk` SET `jumlah`='$postjumlah',`baik`='$postbaik',`rusak`='$postrusak',`keterangan`='$postketerangan' WHERE `id_asesor`='$postasesor' AND `id_jadwal`='$postjadwal' AND `id_perlengkapan`='$postperlengkapan'";
																													$conn->query($sqlinputjawaban);
																												}
																												//echo $sqlcekjawaban."<br>";
																												//echo $sqlinputjawaban."<br>";
																											}
																											// query keputusan verifikasi TUK oleh asesor
																											$sqlkeputusan = "UPDATE `asesor_verifikatortuk` SET `keputusanverifikasi`='$_POST[keputusanverifikasi]' WHERE `id_asesor`='$rowAgen[id]' AND `id_jadwal`='$_GET[idj]'";
																											$conn->query($sqlkeputusan);
																											// upload tandatangan
																											$folderPath = "../foto_tandatangan/";
																											if (empty($_POST['signed'])) {
																												echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Verifikasi TUK berhasil disimpan</h4>
				Terimakasih, Anda telah melakukan <b>Verifikasi TUK untuk Skema $sk[judul].</b><br>
				<a class='btn btn-warning form-control' href='?module=verifikasituk&idj=$_GET[idj]'>Kembali</a></div>";
																											} else {
																												$image_parts = explode(";base64,", $_POST['signed']);
																												$image_type_aux = explode("image/", $image_parts[0]);
																												$image_type = $image_type_aux[1];
																												$image_base64 = base64_decode($image_parts[1]);
																												$file = $folderPath . uniqid() . '.' . $image_type;
																												file_put_contents($file, $image_base64);
																												$url =  "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
																												$iddokumen = md5($url);
																												$escaped_url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
																												$alamatip = $_SERVER['REMOTE_ADDR'];
																												$sqlinputdigisign = "INSERT INTO `logdigisign`(`id_dokumen`, id_skema, id_asesi, `url_ditandatangani`, `nama_dokumen`, `penandatangan`, `file`, `ip`) VALUES ('$iddokumen','$pm0[id_skemakkni]','$_SESSION[namauser]','$escaped_url','FORM CEKLIST VERIFIKASI TUK','$_SESSION[namalengkap]','$file','$alamatip')";
																												$conn->query($sqlinputdigisign);
																												echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Verifikasi TUK berhasil disimpan</h4>
				Terimakasih, Anda telah melakukan <b>Verifikasi TUK untuk Skema $sk[judul].</b><br>
				<a class='btn btn-warning form-control' href='?module=verifikasituk&idj=$_GET[idj]'>Kembali</a></div>";
																											}
																											//echo $sqlgetpersyaratantuk."<br>";
																										}
																										echo "<!-- Content Header (Page header) -->
		<section class='content-header'>
			<h1>
			Ceklist Verifikasi Tempat Uji Kompetensi
			<small>Input Data</small>
			</h1>
			<ol class='breadcrumb'>
			<li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
			<li><a href='media.php?module=verifikasituk'><i class='fa fa-calendar-check-o'></i> Jadwal Verifikasi TUK</a></li>
			<li class='active'>Ceklis Verifikasi Persyaratan TUK</li>
			</ol>
		</section>
		<!-- Main content -->
		<section class='content'>
			<div class='row'>
			<div class='col-xs-12'>
				<div class='box'>
				<div class='box-header'>
					<h3 class='box-title'>Verifikasi Persyaratan Tempat Uji Kompetensi (TUK) untuk Skema Sertifikasi Profesi</h3>
					<h2><b>$gs[kode_skema]</b>- $gs[judul]</h2>
				</div>
				<!-- /.box-header -->
				<form name='frm' method='POST' role='form' enctype='multipart/form-data'>
				<div class='box-body'>
					<div style='overflow-x:auto;'>
					<table id='example' class='table table-bordered table-striped'>
						<thead><tr><th>No</th><th>Perlengkapan (Sarana dan Prasarana) & Spesifikasi</th><th>Jumlah/ Kondisi/ Keterangan</th></tr></thead>
						<tbody>
						<input type='hidden' name='id_skemakkni' value='$gs[id]'>";
																										$no = 1;
																										$sqllsp = "SELECT * FROM `skema_persyaratantuk` WHERE `id_skemakkni`='$gs[id]' ORDER BY `id` ASC";
																										$lsp = $conn->query($sqllsp);
																										while ($pm = $lsp->fetch_assoc()) {
																											$sqlcekjawaban2 = "SELECT * FROM `skema_ceklisvertuk` WHERE `id_asesor`='$rowAgen[id]' AND `id_jadwal`='$_GET[idj]' AND `id_perlengkapan`='$pm[id]'";
																											$cekjawaban2 = $conn->query($sqlcekjawaban2);
																											$jumjw = $cekjawaban2->num_rows;
																											$vjw = $cekjawaban2->fetch_assoc();
																											echo "<tr><td>$no</td>";
																											echo "<td>$pm[perlengkapan]<br>";
																											echo "<b>Spesifikasi :</b><br>$pm[spesifikasi]</td>";
																											echo "<td><input type='hidden' name='asesor_$pm[id]' value='$rowAgen[id]'>
								<input type='hidden' name='jadwal_$pm[id]' value='$_GET[idj]'>
								<input type='hidden' name='skema_$pm[id]' value='$gs[id]'>
								<input type='hidden' name='perlengkapan_$pm[id]' value='$pm[id]'>
								Jumlah Total:<br><input type='text' class='form-control' name='jumlah_$pm[id]'";
																											if ($jumjw > 0) {
																												echo " value='$vjw[jumlah]'";
																											} else {
																												echo "";
																											}
																											echo " required><br>
								<b>Kondisi :</b><br>Jumlah Baik :<br><input type='text' class='form-control' name='baik_$pm[id]'";
																											if ($jumjw > 0) {
																												echo " value='$vjw[baik]'";
																											} else {
																												echo "";
																											}
																											echo " required><br>
								Jumlah Rusak :<br><input type='text' class='form-control' name='rusak_$pm[id]'";
																											if ($jumjw > 0) {
																												echo " value='$vjw[rusak]'";
																											} else {
																												echo "";
																											}
																											echo " required><br>
								Keterangan : <br><textarea name='keterangan_$pm[id]' class='form-control' placeholder='keterangan'>";
																											if ($jumjw > 0) {
																												echo "$vjw[keterangan]";
																											} else {
																												echo "";
																											}
																											echo "</textarea></td></tr>";
																											$no++;
																										}
																										echo "</tbody></table>
					</div>";
																										$sqlcekkeputusan = "SELECT * FROM `asesor_verifikatortuk` WHERE `id_asesor`='$rowAgen[id]' AND `id_jadwal`='$_GET[idj]'";
																										$cekkeputusan = $conn->query($sqlcekkeputusan);
																										$kpva = $cekkeputusan->fetch_assoc();
																										switch ($kpva['keputusanverifikasi']) {
																											case "Y":
																												echo "<div class='col-md-12'><b>Keputusan Verifikasi:</b><br>
							<input type='radio' name='keputusanverifikasi' id='kepurtusanverifikasi1' value='Y' required='required' checked>&nbsp;Sesuai<br>
							<input type='radio' name='keputusanverifikasi' id='kepurtusanverifikasi2' value='N' required='required'>&nbsp;Tidak Sesuai<br>
							persyaratan teknis Tempat Uji Kompetensi (TUK) Skema $gs[judul]</div>";
																												break;
																											case "N":
																												echo "<div class='col-md-12'><b>Keputusan Verifikasi:</b><br>
							<input type='radio' name='keputusanverifikasi' id='kepurtusanverifikasi1' value='Y' required='required'>&nbsp;Sesuai<br>
							<input type='radio' name='keputusanverifikasi' id='kepurtusanverifikasi2' value='N' required='required' checked>&nbsp;Tidak Sesuai<br>
							persyaratan teknis Tempat Uji Kompetensi (TUK) Skema $gs[judul]</div>";
																												break;
																											default:
																												echo "<div class='col-md-12'><b>Keputusan Verifikasi:</b><br>
							<input type='radio' name='keputusanverifikasi' id='kepurtusanverifikasi1' value='Y' required='required'>&nbsp;Sesuai<br>
							<input type='radio' name='keputusanverifikasi' id='kepurtusanverifikasi2' value='N' required='required'>&nbsp;Tidak Sesuai<br>
							persyaratan teknis Tempat Uji Kompetensi (TUK) Skema $gs[judul]</div>";
																												break;
																										}
																										// cek tandatangan digital
																										$url = "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
																										$iddokumen = md5($url);
																										$sqlcektandatangan = "SELECT * FROM `logdigisign` WHERE `id_dokumen`='$iddokumen' AND `penandatangan`='$_SESSION[namalengkap]' ORDER BY `id` DESC";
																										
																										$cektandatangan = $conn->query($sqlcektandatangan);
																										$jumttd = $cektandatangan->num_rows;
																										$ttdx = $cektandatangan->fetch_assoc();
																										if ($jumttd > 0) {
																											echo "<div class='col-md-12'>
									<label class='' for=''>Persetujuan/ Tanda Tangan yang telah Anda berikan:</label>
									<br/>
									<img src='../$ttdx[file]' width='400px'/>
									<br/>
								</div>";
																										}
																										echo "<div class='col-md-12'>
						<label class='' for=''>Tanda Tangan Verifikator :</label>
						<br/>
						<div id='sig' ></div>
						<br/>
						<button id='clear'>Hapus Tanda Tangan</button>
						<textarea id='signature64' name='signed' style='display: none'></textarea>
					</div>
					<script type='text/javascript'>
						var sig = $('#sig').signature({syncField: '#signature64', syncFormat: 'PNG', color: '#58009F'});
						$('#clear').click(function(e) {
							e.preventDefault();
							sig.signature('clear');
							$('#signature64').val('');
						});
					</script>";
																										echo "</div>
				<div class='box-footer'>
					<div align='left' class='col-md-6 col-sm-6 col-xs-6'>";
																										echo "<input type='submit' name='simpanverifikasi' value='Simpan' class='btn btn-success'>";
																										echo "</div>
					<div align='right' class='col-md-6 col-sm-6 col-xs-6'>";
																										echo "<a href='?module=verifikasituk' class='btn btn-primary'>Lihat Jadwal Verifikasi Lainnya</a>";
																										echo "</div>
				</div>
				</form>
			</div>
			</div>
		</section>";
																									}
																								}
																								// Bagian Peserta Asesmen ================================================================================================================
																								elseif ($_GET['module'] == 'pesertaasesmen') {
																									if (!empty($_SESSION['namauser'])) {
																										$sqlidentitas = "SELECT * FROM `identitas`";
																										$identitas = $conn->query($sqlidentitas);
																										$iden = $identitas->fetch_assoc();
																										if (isset($_REQUEST['telepon'])) {
																											echo "<script>window.location.href = 'tel:$_POST[nohpasesi]'</script>";
																										}
																										if (isset($_REQUEST['whatsapp'])) {
																											$nohptujuan = $_POST['nohpasesi'];
																											$first_letter = substr($nohptujuan[0], 0, 1);
																											$rest = substr($nohptujuan, 1);
																											$mes2 = str_replace('0', '62', $first_letter);
																											$nowatujuan = $mes2 . $rest;
																											echo "<script>window.location.href = 'https://api.whatsapp.com/send?phone=$nowatujuan&text=Yth.%20$_POST[namaasesi].%20Saya%20Asesor%20$iden[nama_lsp].%20Apakah%20Anda%20berkenan%20kami%20hubungi%20untuk%20tindak%20lanjut%20pendaftaran%20skema%20asesmen%20uji%20komptensi%20Anda?'</script>";
																										}
																										echo "
			<!-- Content Header (Page header) -->
			<section class='content-header'>
				<h1>
					Peserta (Asesi) Uji Kompetensi
					<small>Data Peserta Asesmen</small>
				</h1>
				<ol class='breadcrumb'>
					<li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
					<li class='active'>Peserta/Calon Asesi Uji Kompetensi Sertifikasi Profesi</li>
				</ol>
			</section>
			<!-- Main content -->
			<section class='content'>
				<div class='row'>
					<div class='col-xs-12'>
						<div class='box'>
							<div class='box-header'>
								<h3 class='box-title'>Data Peserta Uji Kompetensi Lembaga Sertifikasi Profesi</h3>
							</div>
							<!-- /.box-header -->
							<div class='box-body'>
				<div style='overflow-x:auto;'>
					<table id='example1' class='table table-bordered table-striped'>
						<thead><tr><th>No</th><th>Aksi</th><th>Identitas Peserta/Asesi</th></tr></thead>
						<tbody>";
																										$no = 1;
																										$sqljadwaltuk = "SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`='$_GET[idj]'";
																										$jadwaltuk = $conn->query($sqljadwaltuk);
																										while ($pm = $jadwaltuk->fetch_assoc()) {
																											$sqlasesi = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$pm[id_asesi]'";
																											$asesi = $conn->query($sqlasesi);
																											$as = $asesi->fetch_assoc();
																											echo "<tr class=gradeX><td>$no</td><td>";
																											echo "<div class='btn-group'>
								<button type='button' class='btn btn-success'>Aksi</button>
								<button type='button' class='btn btn-success dropdown-toggle' data-toggle='dropdown'>
									<span class='caret'></span>
																<span class='sr-only'>Toggle Dropdown</span>
															</button>
															<ul class='dropdown-menu' role='menu'>
																<li><a href='form-checklist.php?ida=$pm[id_asesi]&idj=$_GET[idj]' title='FORMULIR CHECKLIST'>Unduh Checklist Form</a></li>
																<!--<li><a href='form-mak01.php?ida=$pm[id_asesi]&idj=$_GET[idj]' title='FORMULIR CHECKLIST MENGASES KOMPETENSI'>Unduh FR-MAK-01</a></li>-->
																<li><a href='form-apl-01.php?ida=$pm[id_asesi]&idj=$_GET[idj]'>Unduh APL-01</a></li>
																<li><a href='portfolio-asesi.php?ida=$pm[id_asesi]&idj=$_GET[idj]'>Unduh Portfolio Asesi</a></li>
																<li>";
																											$sqlcekapl02 = "SELECT * FROM `asesi_apl02` WHERE `verifikasi_asesor1` !='' AND `id_asesi`='$pm[id_asesi]' AND `id_skemakkni`='$pm[id_skemakkni]' OR `verifikasi_asesor2` !='' AND `id_asesi`='$pm[id_asesi]' AND `id_skemakkni`='$pm[id_skemakkni]' OR `verifikasi_asesor3` !='' AND `id_asesi`='$pm[id_asesi]' AND `id_skemakkni`='$pm[id_skemakkni]' OR `verifikasi_asesor4` !='' AND `id_asesi`='$pm[id_asesi]' AND `id_skemakkni`='$pm[id_skemakkni]'";
																											$cekapl02 = $conn->query($sqlcekapl02);
																											$jumcekapl02 = $cekapl02->num_rows;
																											$cekjenisapl02 = "SELECT `id`, `apl02` FROM `skema_kkni` WHERE `id`='$pm[id_skemakkni]'";
																											$jenisapl02 = $conn->query($cekjenisapl02);
																											$jnsapl02 = $jenisapl02->fetch_assoc();
																											if ($jnsapl02['apl02'] == 'elemen') {
																												if ($jumcekapl02 == 0) {
																													echo "<li><a href='?module=form-apl-02-el&ida=$pm[id_asesi]&idj=$_GET[idj]'>Input FORM-APL-02</a></li>
													<li><a href='form-apl-02-el.php?ida=$pm[id_asesi]&idj=$_GET[idj]' target='_blank'>Unduh Formulir APL-02</a></li>";
																												} else {
																													echo "<li><a href='?module=form-apl-02-el&ida=$pm[id_asesi]&idj=$_GET[idj]'>Update APL-02 Terverifikasi $jumcekapl02 item</a></li>
													<li><a href='form-apl-02-el.php?ida=$pm[id_asesi]&idj=$_GET[idj]' target='_blank'>Unduh Formulir APL-02</a></li>";
																												}
																											} else {
																												if ($jumcekapl02 == 0) {
																													echo "<li><a href='?module=form-apl-02&ida=$pm[id_asesi]&idj=$_GET[idj]'>Input FORM-APL-02</a></li>
													<li><a href='form-apl-02.php?ida=$pm[id_asesi]&idj=$_GET[idj]' target='_blank'>Unduh Formulir APL-02</a></li>";
																												} else {
																													echo "<li><a href='?module=form-apl-02&ida=$pm[id_asesi]&idj=$_GET[idj]'>Update APL-02 Terverifikasi $jumcekapl02 item</a></li>
													<li><a href='form-apl-02.php?ida=$pm[id_asesi]&idj=$_GET[idj]' target='_blank'>Unduh Formulir APL-02</a></li>";
																												}
																											}
																											echo "<li><a href='form-mapa-01.php?idsk=$pm[id_skemakkni]&kand=1' title='FORMULIR MERENCANAKAN AKTIVITAS DAN PROSES ASESMEN untuk Kandidat Tipe 1' target='_blank'>Unduh FORM-MAPA-01 Kandidat 1</a></li>
																<li><a href='form-mapa-01.php?idsk=$pm[id_skemakkni]&kand=2' title='FORMULIR MERENCANAKAN AKTIVITAS DAN PROSES ASESMEN untuk Kandidat Tipe 2' target='_blank'>Unduh FORM-MAPA-01 Kandidat 2</a></li>
																<li><a href='form-mapa-01.php?idsk=$pm[id_skemakkni]&kand=3' title='FORMULIR MERENCANAKAN AKTIVITAS DAN PROSES ASESMEN untuk Kandidat Tipe 3' target='_blank'>Unduh FORM-MAPA-01 Kandidat 3</a></li>
																<li><a href='form-mapa-01.php?idsk=$pm[id_skemakkni]&kand=4' title='FORMULIR MERENCANAKAN AKTIVITAS DAN PROSES ASESMEN untuk Kandidat Tipe 4' target='_blank'>Unduh FORM-MAPA-01 Kandidat 4</a></li>
																<li><a href='form-mapa-01.php?idsk=$pm[id_skemakkni]&kand=5' title='FORMULIR MERENCANAKAN AKTIVITAS DAN PROSES ASESMEN untuk Kandidat Tipe 5' target='_blank'>Unduh FORM-MAPA-01 Kandidat 5</a></li>
																<li><a href='form-mapa-02.php?idsk=$pm[id_skemakkni]' title='FORMULIR PETA MUK DARI HASIL PENDEKATAN ASESMEN DAN PERENCANAAN ASESMEN' target='_blank'>Unduh FORM-MAPA-02</a></li>
																<li><a href='?module=form-fr-ak-01&ida=$pm[id_asesi]&idj=$_GET[idj]' title='INPUT FORMULIR PERSETUJUAN ASESMEN DAN KERAHASIAAN'>Input Formulir FR-AK-01</a></li>
																<li><a href='form-fr-ak-01.php?ida=$pm[id_asesi]&idj=$_GET[idj]' title='FORMULIR PERSETUJUAN ASESMEN DAN KERAHASIAAN' target='_blank'>Unduh Formulir FR-AK-01</a></li>
																<li><a href='?module=form-fr-ak-02&ida=$pm[id_asesi]&idj=$_GET[idj]' title='INPUT FORMULIR REKAMAN ASESMEN KOMPETENSI' target='_blank'>Input Formulir FR-AK-02</a></li>
															<li><a href='form-fr-ak-02.php?ida=$pm[id_asesi]&idj=$_GET[idj]' title='FORMULIR REKAMAN ASESMEN KOMPETENSI' target='_blank'>Unduh Formulir FR-AK-02</a></li>
																<li><a href='form-fr-ak-03.php?ida=$pm[id_asesi]&idj=$_GET[idj]' title='FORMULIR UMPAN BALIK DAN CATATAN ASESMEN' target='_blank'>Unduh Formulir FR-AK-03</a></li>
																<li><a href='form-fr-ak-04.php?idass=$pm[id]&ida=$pm[id_asesi]&idj=$_GET[idj]' title='FORMULIR BANDING ASESMEN' target='_blank'>Unduh Formulir FR-AK-04</a></li>
																<li><a href='?module=form-fr-ia-01&ida=$pm[id_asesi]&idj=$_GET[idj]' title='INPUT CEKLIS OBSERVASI AKTIVITAS DI TEMPAT KERJA ATAU TEMPAT KERJA SIMULASI'>Input Formulir FR-IA-01</a></li>
																<li><a href='form-fr-ia-01.php?ida=$pm[id_asesi]&idj=$_GET[idj]' title='FORMULIR CEKLIS OBSERVASI AKTIVITAS DI TEMPAT KERJA ATAU TEMPAT KERJA SIMULASI' target='_blank'>Unduh Formulir FR-IA-01</a></li>
																<li><a href='form-fr-ia-02.php?ida=$pm[id_asesi]&idj=$_GET[idj]' title='TUGAS PRAKTIK DEMONSTRASI' target='_blank'>Unduh Formulir FR-IA-02</a></li>
																<li><a href='?module=form-fr-ia-04A&ida=$pm[id_asesi]&idj=$_GET[idj]' title='INPUT DIT – DAFTAR INSTRUKSI TERSTRUKTUR (PENJELASAN PROYEK SINGKAT/ KEGIATAN TERSTRUKTUR LAINNYA*)'>Input Formulir FR-IA-04A</a></li>
																<li><a href='form-fr-ia-04A.php?ida=$pm[id_asesi]&idj=$_GET[idj]' title='DIT – DAFTAR INSTRUKSI TERSTRUKTUR (PENJELASAN PROYEK SINGKAT/ KEGIATAN TERSTRUKTUR LAINNYA*)' target='_blank'>Unduh Formulir FR-IA-04A</a></li>
																<li><a href='?module=form-fr-ia-04B&ida=$pm[id_asesi]&idj=$_GET[idj]' title='INPUT FORMULIR PENILAIAN PROYEK SINGKAT ATAU KEGIATAN TERSTRUKTUR LAINNYA'>Input Formulir FR-IA-04B</a></li>
																<li><a href='form-fr-ia-04B.php?ida=$pm[id_asesi]&idj=$_GET[idj]' title='PENILAIAN PROYEK SINGKAT ATAU KEGIATAN TERSTRUKTUR LAINNYA' target='_blank'>Unduh Formulir FR-IA-04B</a></li>
																<li><a href='form-fr-ia-05.php?ida=$pm[id_asesi]&idj=$_GET[idj]' title='PERTANYAAN TERTULIS PILIHAN GANDA' target='_blank'>Unduh Formulir FR-IA-05</a></li>
																<li><a href='form-fr-ia-05b.php?ida=$pm[id_asesi]&idj=$_GET[idj]' title='JAWABAN PERTANYAAN TERTULIS PILIHAN GANDA' target='_blank'>Unduh Jawaban Asesi FR-IA-05</a></li>
																<li><a href='form-fr-ia-06.php?ida=$pm[id_asesi]&idj=$_GET[idj]' title='PERTANYAAN TERTULIS ESAI' target='_blank'>Unduh Formulir FR-IA-06</a></li>
																<li><a href='form-fr-ia-06a.php?ida=$pm[id_asesi]&idj=$_GET[idj]' title='LEMBAR KUNCI JAWABAN PERTANYAAN TERTULIS ESAI' target='_blank'>Unduh Formulir FR-IA-06.A</a></li>
																<li><a href='?module=form-fr-ia-06&ida=$pm[id_asesi]&idj=$_GET[idj]' title='KOREKSI JAWABAN PERTANYAAN TERTULIS ESAI'>Input Penilaian Formulir FR-IA-06.B</a></li>
																<li><a href='form-fr-ia-06b.php?ida=$pm[id_asesi]&idj=$_GET[idj]' title='LEMBAR JAWABAN PERTANYAAN TERTULIS ESAI' target='_blank'>Unduh Formulir FR-IA-06.B</a></li>
																<li><a href='?module=form-fr-ia-07&ida=$pm[id_asesi]&idj=$_GET[idj]' title='INPUT JAWABAN PERTANYAAN LISAN ASESI'>Input Penilaian Formulir FR-IA-07</a></li>
																<li><a href='form-fr-ia-07.php?ida=$pm[id_asesi]&idj=$_GET[idj]' title='PERTANYAAN LISAN' target='_blank'>Unduh Formulir FR-IA-07</a></li>
																<li><a href='?module=form-fr-ia-08&ida=$pm[id_asesi]&idj=$_GET[idj]' title='CEKLIS VERIFIKASI PORTOFOLIO'>Input Formulir FR-IA-08</a></li>
																<li><a href='form-fr-ia-08.php?ida=$pm[id_asesi]&idj=$_GET[idj]' title='CEKLIS VERIFIKASI PORTOFOLIO' target='_blank'>Unduh Formulir FR-IA-08</a></li>
																<li><a href='?module=form-fr-ia-09&ida=$pm[id_asesi]&idj=$_GET[idj]' title='PERTANYAAN WAWANCARA'>Input Formulir FR-IA-09</a></li>
																<li><a href='form-fr-ia-10.php?ida=$pm[id_asesi]&idj=$_GET[idj]' title='KLARIFIKASI BUKTI PIHAK KETIGA' target='_blank'>Unduh Formulir FR-IA-10</a></li>
																<li><a href='?module=peninjauasesmen' title='CEKLIS MENINJAU INSTRUMEN ASESSMEN'>Input Formulir FR-IA-11</a></li>
																<li><a href='form-fr-ia-11.php?ida=$pm[id_asesi]&idj=$_GET[idj]' title='CEKLIS MENINJAU INSTRUMEN ASESSMEN' target='_blank'>Unduh Formulir FR-IA-11</a></li>
																<li><a href='form-fr-va.php?ida=$pm[id_asesi]&idj=$_GET[idj]' title='MEMBERIKAN KONTRIBUSI DALAM VALIDASI ASESMEN' target='_blank'>Unduh Formulir FR-VA</a></li>
															</ul>
													</div>";
																											/*echo "<a href='form-checklist.php?ida=$pm[id_asesi]&idj=$_GET[idj]' class='btn btn-primary btn-xs btn-block' title='FORMULIR CHECKLIST'>Unduh Checklist Form</a><br>";
							echo "<a href='form-mak01.php?ida=$pm[id_asesi]&idj=$_GET[idj]' class='btn btn-primary btn-xs btn-block' title='FORMULIR CHECKLIST MENGASES KOMPETENSI'><span class='badge bg-green'>1</span>Unduh FR-MAK-01</a><br>";
							echo "<a href='form-apl-01.php?ida=$pm[id_asesi]&idj=$_GET[idj]' class='btn btn-primary btn-xs btn-block btn-social'><i>2</i>Unduh Formulir APL-01</a><br>";
							$sqlcekapl02="SELECT * FROM `asesi_apl02` WHERE `verifikasi_asesor`!='' AND `id_asesi`='$pm[id_asesi]' AND `id_skemakkni`='$pm[id_skemakkni]'";
							$cekapl02=$conn->query($sqlcekapl02);
							$jumcekapl02=$cekapl02->num_rows;
							if ($jumcekapl02==0){
								echo "<a href='?module=form-apl-02&ida=$pm[id_asesi]&idj=$_GET[idj]' class='btn btn-primary btn-xs btn-block'>Penilaian Pra-Asesmen (FORM-APL-02)</a><br>";
							}else{
								echo "<a href='?module=form-apl-02&ida=$pm[id_asesi]&idj=$_GET[idj]' class='btn btn-success btn-xs btn-block'>FORM-APL-02 Terverifikasi $jumcekapl02 item</a><br>";
							}
							echo "<a href='form-apl-02.php?ida=$pm[id_asesi]&idj=$_GET[idj]' class='btn btn-primary btn-xs btn-block' title='FORMULIR ASESMEN MANDIRI'>Unduh FORM-APL-02</a><br>
							<a href='form-mak-01.php?ida=$pm[id_asesi]&idj=$_GET[idj]' class='btn btn-primary btn-xs btn-block' title='FORMULIR PERSETUJUAN ASESMEN DAN KERAHASIAAN'>Unduh FORM-MAK-03</a><br>";
							echo "<a href='?module=form-mak-02&ida=$pm[id_asesi]&idj=$_GET[idj]' class='btn btn-success btn-xs btn-block'>Input Hasil Asesmen (FORM-MAK-02)</a>";*/
																											$cekstatusAPL02 = "SELECT `id_asesi` FROM `asesi_apl02` WHERE `id_asesi`='$pm[id_asesi]' AND `id_jadwal`='$_GET[idj]'";
																											$statusapl02 = $conn->query($cekstatusAPL02);
																											$jumapl02 = $statusapl02->num_rows;
																											$cekstatusasesmen = "SELECT `id_asesi`,`status_asesmen` FROM `asesi_asesmen` WHERE `id_asesi`='$pm[id_asesi]' AND `id_jadwal`='$_GET[idj]' AND `keputusan_asesor`!='NULL'";
																											$statusasesmen = $conn->query($cekstatusasesmen);
																											$gstass = $statusasesmen->fetch_assoc();
																											$jumasesmen = $statusasesmen->num_rows;
																											echo "</td><td><b>$as[nama]</b><br>No. Pendaftaran : $pm[id_asesi]<br>No. HP : $as[nohp]<br>";
																											if (!empty($as['nohp'])) {
																												echo "<form name='frm' method='POST' role='form' enctype='multipart/form-data'><a href='#myModalSMS" . $as['no_pendaftaran'] . "' data-toggle='modal' data-id='" . $as['no_pendaftaran'] . "' class='btn btn-xs btn-primary' title='Kirim pesan SMS ke $as[nama] ($as[nohp])'><i class='fa fa-envelope'></i> Kirim SMS</a>
									<input type='hidden' name='nohpasesi' value='$as[nohp]'>
									<input type='hidden' name='namaasesi' value='$as[nama]'>
									<button type='submit' name='telepon' class='btn btn-info btn-xs' title='Hubungi $as[nama] ($as[nohp]) melalui telepon'><i class='fa fa-phone-square'></i> $as[nohp]</button>
											<button type='submit' name='whatsapp' class='btn btn-success btn-xs' title='Kirim pesan WhatsApp ke $as[nama] ($as[nohp])'><i class='fa fa-whatsapp'></i> $as[nohp]</button></form>";
																												echo "<script>
									$(function(){
												$(document).on('click','.edit-record',function(e){
													e.preventDefault();
													$('#myModalSMS" . $as['no_pendaftaran'] . "').modal('show');
												});
										});
								</script>
								<!-- Modal -->
									<form role='form' action='smsasesibaru.php' method='POST' enctype='multipart/form-data'>
									<div class='modal fade' id='myModalSMS" . $as['no_pendaftaran'] . "' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
										<div class='modal-dialog'>
											<div class='modal-content'>
												<div class='modal-header'>
													<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Tutup</span></button>
													<h4 class='modal-title' id='myModalLabel'>Kirim SMS ke $as[nama] (" . $as['nohp'] . ")</h4>
												</div>
												<div class='modal-body'>
													<input type='hidden' class='form-control' name='idpost' value='$as[no_pendaftaran]'/>
													<input type='hidden' class='form-control' name='nohp" . $as['no_pendaftaran'] . "' value='$as[nohp]'/>
													<div class='input-group'>
														<span class='input-group-addon'><i class='fa fa-envelope'></i></span>
														<textarea class='form-control' placeholder='Isi SMS'  name='pesan" . $as['no_pendaftaran'] . "' rows='2' cols='80' maxlength='160'>Yth. $as[nama], </textarea>
													</div>
												</div>
												<div class='modal-footer'>
													<div align='left' class='col-md-6 col-sm-6 col-xs-6'>
														<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>
													</div>
													<div align='right' class='col-md-6 col-sm-6 col-xs-6'>
														<button type='submit' class='btn btn-success' name='smsasesibaru'>Kirim SMS</button>
													</div>
												</div>
											</div>
										</div>
									</div>
									</form>
								<!-- Modal -->";
																											}
																											// input dokumentasi foto asesmen
																											echo "<a href='#myModalFotoAsesmen" . $as['no_pendaftaran'] . "' data-toggle='modal' data-id='" . $as['no_pendaftaran'] . "' class='btn btn-xs btn-primary' title='Unggah Foto Bukti Asesmen $as[nama] ($as[no_pendaftaran])'><i class='fa fa-camera'></i> Foto Bukti Asesmen</a>";
																											echo "<script>
								$(function(){
											$(document).on('click','.edit-record',function(e){
												e.preventDefault();
												$('#myModalFotoAsesmen" . $as['no_pendaftaran'] . "').modal('show');
											});
									});
							</script>
							<!-- Modal -->
								<form role='form' action='uploadfotoasesmen.php' method='POST' enctype='multipart/form-data'>
								<div class='modal fade' id='myModalFotoAsesmen" . $as['no_pendaftaran'] . "' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
									<div class='modal-dialog'>
										<div class='modal-content'>
											<div class='modal-header'>
												<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Tutup</span></button>
												<h4 class='modal-title' id='myModalLabel'>Unggah Dokumentasi Foto Bukti Proses Asesmen $as[nama] (" . $as['no_pendaftaran'] . ")</h4>
											</div>
											<div class='modal-body'>
												<input type='hidden' class='form-control' name='id_asesi' value='$as[no_pendaftaran]'/>
												<input type='hidden' class='form-control' name='id_jadwal' value='$_GET[idj]'/>
												<input type='hidden' class='form-control' name='id_skemakkni' value='$pm[id_skemakkni]'/>
												<div class='input-group'>";
																											if (!empty($pm['foto_buktiasesmen'])) {
																												echo "<img src='../foto_buktiasesmen/$pm[foto_buktiasesmen]' width='500px'/>
														<input type='file' name='file' id='fileID' accept='image/*' onchange='readURL(this);'></span>
														<p class='help-block'>Silahkan gunakan kamera HP/Laptop Anda</p>";
																											} else {
																												echo "<span class='input-group-addon'><i class='fa fa-camera'></i>
														<label for='fileID'>";
																												echo "</label>
														<input type='file' name='file' id='fileID' accept='image/*' onchange='readURL(this);'></span>
														<p class='help-block'>Silahkan gunakan kamera HP/Laptop Anda</p>";
																											}
																											echo "</div>
											</div>
											<div class='modal-footer'>
												<div align='left' class='col-md-6 col-sm-6 col-xs-6'>
													<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>
												</div>
												<div align='right' class='col-md-6 col-sm-6 col-xs-6'>
													<button type='submit' class='btn btn-success' name='uploadfotoasesmen" . $as['no_pendaftaran'] . "'>Upload Foto Bukti Asesmen</button>
												</div>
											</div>
										</div>
									</div>
								</div>
								</form>
							<!-- Modal -->";
																											//===================================================================
																											if ($jumapl02 > 0) {
																												echo "<br><font color='red'>Telah mengisi Asesmen Mandiri (APL-02)</font>";
																											}
																											if ($jumasesmen > 0) {
																												echo "<br><font color='green'><b>Keputusan Asesmen telah dibuat dan dinyatakan</b></font> : ";
																												switch ($gstass['status_asesmen']) {
																													case "K":
																														echo "<font color='green'><b>KOMPETEN</b></font>";
																														break;
																													case "BK":
																														echo "<font color='red'><b>BELUM KOMPETEN</b></font>";
																														break;
																													case "TL":
																														echo "<b>UJI ULANG/ TINDAK LANJUT</b>";
																														break;
																													default:
																														echo "<b>BELUM ADA KEPUTUSAN</b>";
																														break;
																												}
																											}
																											// cek apakah sudah mengerjakan soal ia 05
																											$sqlcekstatussoalia05 = "SELECT `id_asesi` FROM `asesmen_ia05` WHERE `id_asesi`='$pm[id_asesi]'";
																											$cekstatussoalia05 = $conn->query($sqlcekstatussoalia05);
																											$statussoalia05 = $cekstatussoalia05->num_rows;
																											$hitungskor = "SELECT SUM(`skor`) AS `TotSkor` FROM `asesmen_ia05` WHERE `id_asesi`='$pm[id_asesi]' AND `id_skemakkni`='$pm[id_skemakkni]' AND `id_jadwal`='$_GET[idj]'";
																											$skortotal = $conn->query($hitungskor);
																											$skorx = $skortotal->fetch_assoc();
																											if ($statussoalia05 > 0) {
																												// hitung skor IA 05
																												$sqlgetunitkompetensib = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$pm[id_skemakkni]'";
																												$getunitkompetensib = $conn->query($sqlgetunitkompetensib);

																												$noku = 1;
																												$nopp = 1;
																												$skortotal = 0;

																												while ($unkb = $getunitkompetensib->fetch_assoc()) {
																													$sqlgetpertanyaan = "SELECT * FROM `skema_pertanyaantulispg` WHERE `id_unitkompetensi`='$unkb[id]' ORDER BY `id` ASC";
																													//$sqlgetpertanyaan="SELECT * FROM `asesmen_ia05` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_unitkompetensi`='$unkb[id]' AND `jawaban` IS NOT NULL ORDER BY `id_pertanyaan` ASC";
																													$getpertanyaan = $conn->query($sqlgetpertanyaan);
																													$jumpertanyaan = $getpertanyaan->num_rows;

																													while ($gpp = $getpertanyaan->fetch_assoc()) {
																														$sqlgetpertanyaanxx = "SELECT * FROM `asesmen_ia05` WHERE `id_asesi`='$pm[id_asesi]' AND `id_jadwal`='$_GET[idj]' AND `id_unitkompetensi`='$unkb[id]' AND `jawaban` IS NOT NULL ORDER BY `id_pertanyaan` ASC";
																														$getpertanyaanxx = $conn->query($sqlgetpertanyaanxx);
																														$gppxx = $getpertanyaanxx->fetch_assoc();
																														if (!empty($gppxx['id_pertanyaan'])) {
																															//$sqlambiljawaban="SELECT * FROM `asesmen_ia05` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_unitkompetensi`='$unkb[id]' AND `id_pertanyaan`='$gpp[id]'";
																															$sqlambiljawaban = "SELECT * FROM `asesmen_ia05` WHERE `id_asesi`='$pm[id_asesi]' AND `id_jadwal`='$_GET[idj]' AND `id_unitkompetensi`='$unkb[id]' AND `id_pertanyaan`='$gpp[id]' ORDER BY `waktu` DESC LIMIT 1";
																															$ambiljawaban = $conn->query($sqlambiljawaban);
																															$jwbnx = $ambiljawaban->fetch_assoc();
																															$sqlgetpertanyaanx = "SELECT * FROM `skema_pertanyaantulispg` WHERE `id`='$gpp[id]'";
																															$getpertanyaanx = $conn->query($sqlgetpertanyaanx);
																															$gppx = $getpertanyaanx->fetch_assoc();
																															$skortotal = $skortotal + $jwbnx['skor'];
																															$nopp++;
																														}
																													}
																												}
																												// GRADE NILAI
																												$jumskorfull = $nopp - 1;
																												if ($skortotal > 0) {
																													$gradenilai = ($skortotal / $jumskorfull) * 100;
																												} else {
																													$gradenilai = 0;
																												}
																												$nilaigrade = round($gradenilai, 2);
																												echo "<br/><font color='green'><b>Telah mengerjakan soal pilihan ganda dengan nilai :</b></font> <font color='red' size='8'><b>$nilaigrade</b></font><br>";
																											} else {
																												echo "</br><font color='red'><b>Belum mengerjakan soal pilihan ganda</b></font><br>";
																											}
																											// cek akses asesi ke soal
																											$sqlgetaksessoal = "SELECT * FROM `asesi_aksessoal` WHERE `id_skemakkni`='$pm[id_skemakkni]' AND `id_asesi`='$pm[id_asesi]' AND `id_jadwal`='$_GET[idj]' AND `jenis_soal`='FR.IA.05'";
																											$getaksessoal = $conn->query($sqlgetaksessoal);
																											$gaso = $getaksessoal->fetch_assoc();
																											$jgaso = $getaksessoal->num_rows;
																											if ($jgaso > 0) {
																												switch ($gaso['status']) {
																													case "1":
																														echo "<form role='form' action='aksessoal.php' method='POST' enctype='multipart/form-data'>
										<input type='hidden' name='nama_asesi' value='$as[nama]'>
										<input type='hidden' name='id_asesi' value='$pm[id_asesi]'>
										<input type='hidden' name='id_jadwal' value='$_GET[idj]'>
										<input type='hidden' name='id_skemakkni' value='$pm[id_skemakkni]'>
										<input type='hidden' name='jenis_soal' value='FR.IA.05'>
										<input type='submit' class='btn btn-danger btn-xs' name='tutupaksessoal' value='Tutup Akses Soal Pilihan Ganda'>
										</form>";
																														break;
																													case "0":
																														echo "<form role='form' action='aksessoal.php' method='POST' enctype='multipart/form-data'>
										<input type='hidden' name='nama_asesi' value='$as[nama]'>
										<input type='hidden' name='id_asesi' value='$pm[id_asesi]'>
										<input type='hidden' name='id_jadwal' value='$_GET[idj]'>
										<input type='hidden' name='id_skemakkni' value='$pm[id_skemakkni]'>
										<input type='hidden' name='jenis_soal' value='FR.IA.05'>
										<input type='submit' class='btn btn-success btn-xs' name='bukaaksessoal' value='Buka Akses Soal Pilihan Ganda'>
										</form>";
																														break;
																													case "2":
																														echo "<form role='form' action='aksessoal.php' method='POST' enctype='multipart/form-data'>
										<input type='hidden' name='nama_asesi' value='$as[nama]'>
										<input type='hidden' name='id_asesi' value='$pm[id_asesi]'>
										<input type='hidden' name='id_jadwal' value='$_GET[idj]'>
										<input type='hidden' name='id_skemakkni' value='$pm[id_skemakkni]'>
										<input type='hidden' name='jenis_soal' value='FR.IA.05'>
										<input type='submit' class='btn btn-danger btn-xs' name='perbaikan' value='Buka Akses Perbaikan Soal Pilihan Ganda'>
										</form>";
																														break;
																												}
																											} else {
																												echo "<form role='form' action='aksessoal.php' method='POST' enctype='multipart/form-data'>
								<input type='hidden' name='nama_asesi' value='$as[nama]'>
								<input type='hidden' name='id_asesi' value='$pm[id_asesi]'>
								<input type='hidden' name='id_jadwal' value='$_GET[idj]'>
								<input type='hidden' name='id_skemakkni' value='$pm[id_skemakkni]'>
								<input type='hidden' name='jenis_soal' value='FR.IA.05'>
								<input type='submit' class='btn btn-success btn-xs' name='bukaaksessoal' value='Buka Akses Soal Pilihan Ganda'>
								</form>";
																											}
																											// cek apakah sudah mengerjakan soal ia 06
																											$sqlcekstatussoalia06 = "SELECT `id_asesi` FROM `asesmen_ia06` WHERE `id_asesi`='$pm[id_asesi]'";
																											$cekstatussoalia06 = $conn->query($sqlcekstatussoalia06);
																											$statussoalia06 = $cekstatussoalia06->num_rows;
																											if ($statussoalia06 > 0) {
																												echo "<font color='green'><b>Telah mengerjakan soal esai</b></font><br>";
																											} else {
																												echo "<font color='red'><b>Belum mengerjakan soal esai</b></font><br>";
																											}
																											// cek akses asesi ke soal esai
																											$sqlgetaksessoal2 = "SELECT * FROM `asesi_aksessoal` WHERE `id_skemakkni`='$pm[id_skemakkni]' AND `id_asesi`='$pm[id_asesi]' AND `id_jadwal`='$_GET[idj]' AND `jenis_soal`='FR.IA.06'";
																											$getaksessoal2 = $conn->query($sqlgetaksessoal2);
																											$gaso2 = $getaksessoal2->fetch_assoc();
																											$jgaso2 = $getaksessoal2->num_rows;
																											if ($jgaso2 > 0) {
																												switch ($gaso2['status']) {
																													case "1":
																														echo "<form role='form' action='aksessoal.php' method='POST' enctype='multipart/form-data'>
										<input type='hidden' name='nama_asesi' value='$as[nama]'>
										<input type='hidden' name='id_asesi' value='$pm[id_asesi]'>
										<input type='hidden' name='id_jadwal' value='$_GET[idj]'>
										<input type='hidden' name='id_skemakkni' value='$pm[id_skemakkni]'>
										<input type='hidden' name='jenis_soal' value='FR.IA.06'>
										<input type='submit' class='btn btn-danger btn-xs' name='tutupaksessoal' value='Tutup Akses Soal Esai'>
										</form>";
																														break;
																													case "0":
																														echo "<form role='form' action='aksessoal.php' method='POST' enctype='multipart/form-data'>
										<input type='hidden' name='nama_asesi' value='$as[nama]'>
										<input type='hidden' name='id_asesi' value='$pm[id_asesi]'>
										<input type='hidden' name='id_jadwal' value='$_GET[idj]'>
										<input type='hidden' name='id_skemakkni' value='$pm[id_skemakkni]'>
										<input type='hidden' name='jenis_soal' value='FR.IA.06'>
										<input type='submit' class='btn btn-success btn-xs' name='bukaaksessoal' value='Buka Akses Soal Esai'>
										</form>";
																														break;
																												}
																											} else {
																												echo "<form role='form' action='aksessoal.php' method='POST' enctype='multipart/form-data'>
								<input type='hidden' name='nama_asesi' value='$as[nama]'>
								<input type='hidden' name='id_asesi' value='$pm[id_asesi]'>
								<input type='hidden' name='id_jadwal' value='$_GET[idj]'>
								<input type='hidden' name='id_skemakkni' value='$pm[id_skemakkni]'>
								<input type='hidden' name='jenis_soal' value='FR.IA.06'>
								<input type='submit' class='btn btn-success btn-xs' name='bukaaksessoal' value='Buka Akses Soal Esai'>
								</form>";
																											}
																											// keputusan rekomendasi asesor
																											if ($jumasesmen > 0) {
																												echo "<form role='form' action='keputusanasesor.php' method='POST' enctype='multipart/form-data'>
								<input type='hidden' name='nama_asesi' value='$as[nama]'>
								<input type='hidden' name='id_asesi' value='$pm[id_asesi]'>
								<input type='hidden' name='id_jadwal' value='$_GET[idj]'>
								<input type='hidden' name='id_skemakkni' value='$pm[id_skemakkni]'>
								<div class='btn-group'>
								<button type='button' class='btn btn-primary btn-xs'>Keputusan (Rekomendasi)</button>
								<button type='button' class='btn btn-primary btn-xs dropdown-toggle' data-toggle='dropdown'>
									<span class='caret'></span>
																<span class='sr-only'>Toggle Dropdown</span>
															</button>
															<ul class='dropdown-menu' role='menu'>
										<li><input type='submit' name='keputusanK' class='btn btn-success btn-flat btn-block' value='Rekomendasikan Kompeten (K)'></li>
										<li><input type='submit' name='keputusanBK' class='btn btn-danger btn-flat btn-block' value='Rekomensasikan Belum Kompeten (BK)'></li>
										<li><input type='submit' name='keputusanTL' class='btn btn-warning btn-flat btn-block' value='Rekomensasikan Uji Ulang/ Tindak Lanjut (TL)'></li>
										<li><input type='submit' name='keputusanP' class='btn btn-default btn-flat btn-block' value='Reset Keputusan (Atur Ulang)'></li>
										</ul>
								</div>
								</form>";
																											} else {
																												echo "<form role='form' action='keputusanasesor.php' method='POST' enctype='multipart/form-data'>
								<input type='hidden' name='nama_asesi' value='$as[nama]'>
								<input type='hidden' name='id_asesi' value='$pm[id_asesi]'>
								<input type='hidden' name='id_jadwal' value='$_GET[idj]'>
								<input type='hidden' name='id_skemakkni' value='$pm[id_skemakkni]'>
								<div class='btn-group'>
								<button type='button' class='btn btn-primary btn-xs'>Keputusan (Rekomendasi)</button>
								<button type='button' class='btn btn-primary btn-xs dropdown-toggle' data-toggle='dropdown'>
									<span class='caret'></span>
																<span class='sr-only'>Toggle Dropdown</span>
															</button>
															<ul class='dropdown-menu' role='menu'>
										<li><input type='submit' name='keputusanK' class='btn btn-success btn-flat btn-block' value='Rekomendasikan Kompeten (K)'></li>
										<li><input type='submit' name='keputusanBK' class='btn btn-danger btn-flat btn-block' value='Rekomensasikan Belum Kompeten (BK)'></li>
										<li><input type='submit' name='keputusanTL' class='btn btn-warning btn-flat btn-block' value='Rekomensasikan Uji Ulang/ Tindak Lanjut (TL)'></li>
										<li><input type='submit' name='keputusanP' class='btn btn-default btn-flat btn-block' value='Reset Keputusan (Atur Ulang)'></li>
										</ul>
								</div>
								</form>";
																											}
																											echo "</td>
							</tr>";
																											$no++;
																										}
																										echo "</tbody></table>
				</div>
				</div>
				</div>
				<div class='box'>
							<div class='box-header'>
								<h3 class='box-title'>Info Jadwal Asesmen</h3>
							</div>
							<!-- /.box-header -->
							<div class='box-body'>";
																										$sqljadwaltuk2 = "SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
																										$jadwaltuk2 = $conn->query($sqljadwaltuk2);
																										$jdt = $jadwaltuk2->fetch_assoc();
																										$sqltuk = "SELECT * FROM `tuk` WHERE `id`='$jdt[tempat_asesmen]'";
																										$tuk = $conn->query($sqltuk);
																										$tt = $tuk->fetch_assoc();
																										$masa_berlaku = tgl_indo($tt['masa_berlaku']);
																										$sqltukjenis1 = "SELECT * FROM `tuk_jenis` WHERE `id`='$tt[jenis_tuk]'";
																										$jenistuk = $conn->query($sqltukjenis1);
																										$jt = $jenistuk->fetch_assoc();
																										$tglasesmen = tgl_indo($jdt['tgl_asesmen']);
																										$sqlskemakkni = "SELECT * FROM `skema_kkni` WHERE `id`='$jdt[id_skemakkni]'";
																										$skemakkni = $conn->query($sqlskemakkni);
																										$skm = $skemakkni->fetch_assoc();
																										echo "<table id='example1' class='table table-bordered table-striped'>
				<tbody><tr><td>Skema</td><td><b>$skm[kode_skema]-$skm[judul]</b><br>";
																										$namaskkni = $conn->query("SELECT * FROM `skkni` WHERE `id`='$skm[id_skkni]'");
																										$nsk = $namaskkni->fetch_assoc();
																										$pesertaasesmen = $conn->query("SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`='$jdt[id]'");
																										$jumps = $pesertaasesmen->num_rows;
																										echo "$nsk[nama]</td></tr>
				<tr><td>Periode</td><td><b>$jdt[periode] $jdt[tahun]</td></tr>
				<tr><td>Gelombang</td><td><b>$jdt[gelombang]</b></td></tr>
				<tr><td>Tanggal</td><td><b>$tglasesmen</b></td></tr>
				<tr><td>Pukul</td><td><b>$jdt[jam_asesmen]</b></td></tr>
				<tr><td>Tempat</td><td><b>$tt[nama]</b><br>$tt[alamat] $tt[kelurahan]</td></tr>
				<tr><td>Maksimal Peserta</td><td><b>$jdt[kapasitas] Asesi</b></td></tr>
				<tr><td>Peserta Terjadwal</td><td><b>$jumps Asesi</b></td></tr>
				<tr><td>Asesor</td><td>";
																										$noasr = 1;
																										$getasesor = $conn->query("SELECT * FROM `jadwal_asesor` WHERE `id_jadwal`='$jdt[id]'");
																										while ($gas = $getasesor->fetch_assoc()) {
																											$sqlasesor = "SELECT * FROM `asesor` WHERE `id`='$gas[id_asesor]'";
																											$asesor = $conn->query($sqlasesor);
																											$asr = $asesor->fetch_assoc();
																											if (!empty($asr['gelar_depan'])) {
																												if (!empty($asr['gelar_blk'])) {
																													$namaasesor = $asr['gelar_depan'] . " " . $asr['nama'] . ", " . $asr['gelar_blk'];
																												} else {
																													$namaasesor = $asr['gelar_depan'] . " " . $asr['nama'];
																												}
																											} else {
																												if (!empty($asr['gelar_blk'])) {
																													$namaasesor = $asr['nama'] . ", " . $asr['gelar_blk'];
																												} else {
																													$namaasesor = $asr['nama'];
																												}
																											}
																											echo "<b>$noasr. $namaasesor</b><br>";
																											$noasr++;
																										}
																										echo "</td></tr></tbody></table><br>
				<div align='left' class='col-md-9 col-sm-6 col-xs-6'>
					<a href='daftarhadir.php?idj=$_GET[idj]' class='btn btn-primary'>Unduh Daftar Hadir</a>&nbsp;";
																										if (empty($jdt['dok_standarkompetensi'])) {
																											echo "<a href='#' class='btn btn-default'>Dok. Standar Kompetensi Belum Tersedia</a>";
																										} else {
																											echo "<a href='../foto_dokskkni/$jdt[dok_standarkompetensi]' class='btn btn-primary' target='_blank'>Unduh Standar Kompetensi</a>";
																										}
																										echo "<a href='media.php?module=form-fr-ak-05&idj=$_GET[idj]' class='btn btn-success' title='Input/Update AK 05-LAPORAN ASESMEN'>Input/Update Laporan Asesmen</a>";
																										echo "</div>
				<div align='right' class='col-md-3 col-sm-6 col-xs-6'>";
																										echo "<a href='?module=jadwalasesmen' class='btn btn-success'>Lihat Jadwal Lainnya</a>";
																										echo "</div>
				</div>
				</div>
			</div>
			</div>
		</section>";
																									}
																								}
																								// Bagian SMS Notifikasi
																								elseif ($_GET['module'] == 'pesanmasuk') {
																									if (!empty($_SESSION['namauser'])) {
																										$sqlgetasesordata = "SELECT * FROM `asesor` WHERE `no_ktp`='$_SESSION[namauser]'";
																										$getasesordata = $conn->query($sqlgetasesordata);
																										$asr = $getasesordata->fetch_assoc();
																										echo "<!-- Content Header (Page header) -->
			<section class='content-header'>
				<h1>
			SMS Notifikasi
					<small></small>
				</h1>
				<ol class='breadcrumb'>
					<li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
					<li class='active'>SMS</li>
				</ol>
			</section>";
																										echo "<!-- Main content -->
			<section class='content'>
				<div class='row'>
					<div class='col-xs-12'>
						<div class='box'>
							<div class='box-header'>
								<h3 class='box-title'>Notifikasi SMS yang dikirim Sistem SMS Gateway</h3>
							</div>
							<!-- /.box-header -->
							<div class='box-body'>
				<div style='overflow-x:auto;'>
					<table id='example1' class='table table-bordered table-striped'>
						<thead><tr><th>No</th><th>Waktu Pengiriman</th><th>Isi Pesan</th><th>Status</th></tr></thead>
						<tbody>";
																										$no = 1;
																										$sqlasesiikut = "SELECT * FROM `sentitems` WHERE `DestinationNUmber`='$asr[no_hp]' ORDER BY `SendingDateTime` DESC";
																										$asesiikut = $conn->query($sqlasesiikut);
																										while ($pm0 = $asesiikut->fetch_assoc()) {
																											switch ($pm0['Status']) {
																												case 'SendingOK':
																													$statuskirim = "<span class='btn-success btn-flat btn-xs'>Terkirim</span>";
																													break;
																												case 'SendingOKNoReport':
																													$statuskirim = "<span class='btn-success btn-flat btn-xs'>Terkirim</span>";
																													break;
																												case 'SendingError':
																													$statuskirim = "<span class='btn-danger btn-flat btn-xs'>Gagal Terkirim</span>";
																													break;
																												case 'DeliveryOK':
																													$statuskirim = "<span class='btn-success btn-flat btn-xs'>Terkirim</span>";
																													break;
																												case 'DeliveryFailed':
																													$statuskirim = "<span class='btn-danger btn-flat btn-xs'>Gagal Terkirim</span>";
																													break;
																												case 'DeliveryPending':
																													$statuskirim = "<span class='btn-warning btn-flat btn-xs'>Tertahan</span>";
																													break;
																												case 'DeliveryUnknown':
																													$statuskirim = "<span class='btn-info btn-flat btn-xs'>Status tidak diketahui</span>";
																													break;
																												case 'Error':
																													$statuskirim = "<span class='btn-danger btn-flat btn-xs'>Terjadi kesalahan sistem</span>";
																													break;
																											}
																											echo "<tr class=gradeX><td>$no</td><td><b>$pm0[SendingDateTime]</b></td><td>$pm0[TextDecoded]</td><td>$statuskirim</td></tr>";
																											$no++;
																										}
																										$sqlasesiikut2 = "SELECT * FROM `outbox` WHERE `DestinationNUmber`='$asr[no_hp]' ORDER BY `SendingDateTime` DESC";
																										$asesiikut2 = $conn->query($sqlasesiikut2);
																										while ($pm02 = $asesiikut2->fetch_assoc()) {
																											$statuskirim = "<span class='btn-danger btn-flat btn-xs'>menunggu</span>";
																											echo "<tr class=gradeX><td>$no</td><td><b>$pm02[SendingDateTime]</b></td><td>$pm02[TextDecoded]</td><td>$statuskirim</td></tr>";
																											$no++;
																										}
																										echo "</tbody></table>
				</div>
				</div>
				</div>
		</section>
			<!-- /.content -->";
																									}
																								}
																								// Bagian Input Asesmen Mandiri FORM-APL-02 Asesi Sistem Elemen
																								elseif ($_GET['module'] == 'form-apl-02-el') {
																									$sqllogin = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_GET[ida]'";
																									$login = $conn->query($sqllogin);
																									$ketemu = $login->num_rows;
																									$rowAgen = $login->fetch_assoc();
																									$sqlgetjadwal = "SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
																									$getjadwal = $conn->query($sqlgetjadwal);
																									$jd = $getjadwal->fetch_assoc();
																									$sqlgetskema = "SELECT * FROM `skema_kkni` WHERE `id`='$jd[id_skemakkni]'";
																									$getskema = $conn->query($sqlgetskema);
																									$sk = $getskema->fetch_assoc();
																									$sqlgetasesordata = "SELECT * FROM `asesor` WHERE `no_ktp`='$_SESSION[namauser]'";
																									$getasesordata = $conn->query($sqlgetasesordata);
																									$asr = $getasesordata->fetch_assoc();

																									// UPDATE @FHM=PUSTI 2 AGUSTUS 2023 : Cek Data Asesi Asesmen
																									$cekasesiasesmen = $conn->query("SELECT * FROM asesi_asesmen WHERE id_asesi='$_GET[ida]' AND id_skemakkni='$jd[id_skemakkni]' AND id_jadwal='$_GET[idj]'")->fetch_assoc();

																									echo "<!-- Content Header (Page header) -->
			<section class='content-header'>
				<h1>
			Asesmen Mandiri (FORM-APL-02)
					<small>Skema $sk[judul]</small>
				</h1>
				<ol class='breadcrumb'>
					<li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
					<li class='active'>Penilaian Asesmen Mandiri Asesi</li>
				</ol>
			</section>";
																									function uploadFoto($file)
																									{
																										$file_max_weight = 20097152; //Ukuran maksimal yg dibolehkan(2Mb)
																										$ok_ext = array('jpg', 'png', 'gif', 'jpeg', 'JPG', 'PNG', 'GIF', 'JPEG'); // ekstensi yang diijinkan
																										$destination = "foto_asesi/"; // tempat buat upload
																										$filename = explode(".", $file['name']);
																										$file_name = $file['name'];
																										$file_name_no_ext = isset($filename[0]) ? $filename[0] : null;
																										$file_extension = $filename[count($filename) - 1];
																										$file_weight = $file['size'];
																										$file_type = $file['type'];
																										// Jika tidak ada error
																										if ($file['error'] == 0) {
																											$dateNow = date_create();
																											$time_stamp = date_format($dateNow, 'U');
																											if (in_array($file_extension, $ok_ext)) :
																												if ($file_weight <= $file_max_weight) :
																													$fileNewName = $time_stamp . md5($file_name_no_ext[0] . microtime()) . "." . $file_extension;
																													$alamatfile = $fileNewName;
																													if (move_uploaded_file($file['tmp_name'], $destination . $fileNewName)) :
																													//echo" File uploaded !";
																													else :
																													//echo "can't upload file.";
																													endif;
																												else :
																												//echo "File too heavy.";
																												endif;
																											else :
																											//echo "File type is not supported.";
																											endif;
																										}
																										return $alamatfile;
																									}
																									if (isset($_REQUEST['simpan'])) {
																										$sqlgetunitkompetensi2 = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
																										$getunitkompetensi2 = $conn->query($sqlgetunitkompetensi2);
																										while ($unk2 = $getunitkompetensi2->fetch_assoc()) {
																											$sqlcekukom = "SELECT * FROM `asesmen_unitkompetensi` WHERE `id_skemakkni`='$sk[id]' AND `id_asesi`='$_GET[ida]' AND `id_unitkompetensi`='$unk2[id]'";
																											$cekukom = $conn->query($sqlcekukom);
																											$ukom = $cekukom->num_rows;
																											if ($ukom > 0) {
																												$sqlgetelemen2 = "SELECT * FROM `elemen_kompetensi` WHERE `id_unitkompetensi`='$unk2[id]' ORDER BY `id` ASC";
																												$getelemen2 = $conn->query($sqlgetelemen2);
																												while ($el2 = $getelemen2->fetch_assoc()) {
																													$sqlgetkriteria2 = "SELECT * FROM `kriteria_unjukkerja` WHERE `id_elemenkompetensi`='$el2[id]' ORDER BY `id` ASC";
																													$getkriteria2 = $conn->query($sqlgetkriteria2);
																													$id_jawaban0 = 'bukti' . $el2['id'];
																													$id_jawaban = 'checkbox1' . $el2['id'];
																													$id_jawabanb = 'checkbox2' . $el2['id'];
																													$id_jawabanc = 'checkbox3' . $el2['id'];
																													$id_jawaband = 'checkbox4' . $el2['id'];
																													$sqlcekjawaban = "SELECT * FROM `asesi_apl02` WHERE `id_asesi`='$_GET[ida]' AND `id_elemen`='$el2[id]' AND `id_skemakkni`='$sk[id]' AND `id_jadwal`='$_GET[idj]'";
																													$cekjawaban = $conn->query($sqlcekjawaban);
																													$jjw = $cekjawaban->num_rows;
																													if ($jjw == 0) {
																														if (isset($_POST[$id_jawaban])) {
																															$sqlinputjawaban = "INSERT INTO `asesi_apl02`(`id_asesi`, `id_elemen`, `id_skemakkni`, `jawaban`) VALUES ('$_GET[ida]','$el2[id]','$sk[id]','" . $_POST[$id_jawaban] . "')";
																															$conn->query($sqlinputjawaban);
																														} else {
																															$sqlinputjawaban = "INSERT INTO `asesi_apl02`(`id_asesi`, `id_elemen`, `id_skemakkni`, `jawaban`) VALUES ('$_GET[ida]','$el2[id]','$sk[id]',NULL')";
																															$conn->query($sqlinputjawaban);
																														}
																													} else {
																														if (isset($_POST[$id_jawaban0])) {
																															$sqlinputjawaban = "UPDATE `asesi_apl02` SET `keterangan_bukti`='" . $_POST[$id_jawaban0] . "',`id_asesor`='$asr[id]' WHERE `id_asesi`='$_GET[ida]' AND `id_elemen`='$el2[id]' AND `id_skemakkni`='$sk[id]' AND `id_jadwal`='$_GET[idj]'";
																															$conn->query($sqlinputjawaban);
																														} else {
																															$sqlinputjawaban = "UPDATE `asesi_apl02` SET `keterangan_bukti`='',`id_asesor`='$asr[id]' WHERE `id_asesi`='$_GET[ida]' AND `id_elemen`='$el2[id]' AND `id_skemakkni`='$sk[id]' AND `id_jadwal`='$_GET[idj]'";
																															$conn->query($sqlinputjawaban);
																														}
																													}
																												}
																											}
																										}
																										$folderPath = "../foto_tandatangan/";
																										if (empty($_POST['signed'])) {
																											echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Penilaian Asesor berhasil disimpan</h4>
				Terimakasih, Anda telah melakukan <b>Penilaian Asesmen Mandiri untuk Skema $sk[judul].</b><br>
				<a class='btn btn-warning form-control' href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a></div>
				<script>window.location = '" . $base_url . "media.php?module=form-apl-02-el&ida=$_GET[ida]&idj=$_GET[idj]'</script>";
																										} else {
																											$image_parts = explode(";base64,", $_POST['signed']);
																											$image_type_aux = explode("image/", $image_parts[0]);
																											$image_type = $image_type_aux[1];
																											$image_base64 = base64_decode($image_parts[1]);
																											$file = $folderPath . uniqid() . '.' . $image_type;
																											file_put_contents($file, $image_base64);
																											$url =  "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
																											$iddokumen = md5($url);
																											$escaped_url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
																											$alamatip = $_SERVER['REMOTE_ADDR'];
																											$sqlinputdigisign = "INSERT INTO `logdigisign`(`id_dokumen`, id_skema, id_asesi, `url_ditandatangani`, `nama_dokumen`, `penandatangan`, `file`, `ip`) VALUES ('$iddokumen','$sk[id]', '$_SESSION[namauser]','$escaped_url','FR.APL.02. ASESMEN MANDIRI','$_SESSION[namalengkap]','$file','$alamatip')";
																											$conn->query($sqlinputdigisign);
																											echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Penilaian Asesor berhasil disimpan</h4>
				Terimakasih, Anda telah melakukan <b>Penilaian Asesmen Mandiri untuk Skema $sk[judul].</b><br>
				<a class='btn btn-warning form-control' href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a></div>
				<script>window.location = '" . $base_url . "media.php?module=form-apl-02-el&ida=$_GET[ida]&idj=$_GET[idj]'</script>";
																										}
																										$sqlupdateasesmenasesi = "UPDATE `asesi_asesmen` SET `status_apl02`='$_POST[status_apl02]' WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																										$conn->query($sqlupdateasesmenasesi);
																										$sqlidentitas = "SELECT * FROM `identitas`";
																										$identitas = $conn->query($sqlidentitas);
																										$iden = $identitas->fetch_assoc();
																										$sqlbiaya = "SELECT * FROM `biaya_sertifikasi` WHERE `id_skemakkni`='$sk[id]'";
																										$biayanya = $conn->query($sqlbiaya);
																										$totbiaya = 0;
																										while ($bys = $biayanya->fetch_assoc()) {
																											$sqljenisbi = "SELECT * FROM `biaya_jenis` WHERE `id`='$bys[jenis_biaya]'";
																											$jenisbi = $conn->query($sqljenisbi);
																											$jnb = $jenisbi->fetch_assoc();
																											$totbiaya = $totbiaya + $bys['nominal'];
																											$tampilbiaya = "Rp. " . number_format($totbiaya, 0, ",", ".");
																										}
																										// Kirim email dalam format HTML ke Pendaftar
																										// cek LSP Konstruksi
																										$sqlceklspkonstruksi = "SELECT * FROM `user_pupr`";
																										$ceklspkonsturksi = $conn->query($sqlceklspkonstruksi);
																										$jumlspkonstruksi = $ceklspkonsturksi->num_rows;
																										if ($jumlspkonstruksi > 0) {
																											$pesan = "Asesmen Mandiri (APL-02) Anda pada Skema $sk[kode_skema] - $sk[judul]<br />Dinyatakan telah diverifikasi oleh Asesor.<br />  
					ID Asesi: $_GET[ida] <br />
					Nama: $rowAgen[nama] <br />
					Nomor Handphone: $rowAgen[nohp] <br />
					Skema: $sk[kode_skema] - $sk[judul]<br />
					Biaya: $tampilbiaya<br />
					<br /><br />Silahkan lakukan pembayaran berdasarkan <b>INVOICE (TAGIHAN)</b> dan lakukan pembayaran sejumlah $tampilbiaya. Pembayaran dapat dilakukan secara tunai atau melalui transfer ke salah satu rekening berikut:<br /><br />";
																											$sqlgetbank = "SELECT * FROM `rekeningbayar` WHERE `metode`!='Tunai' AND `aktif`='Y'";
																											$getbank = $conn->query($sqlgetbank);
																											while ($rek = $getbank->fetch_assoc()) {
																												$pesan .= "Rekening $rek[bank] No. $rek[norek]<br>an. $rek[atasnama]<br>";
																											}
																											$subjek = "Asesmen Mandiri (APL-02) Anda di $iden[nama_lsp] telah diverifikasi oleh Asesor";
																											$dari = "From: noreply@" . $urldomain . "\r\n";
																											$dari .= "Content-type: text/html\r\n";
																											// Kirim email ke member
																											$sqlgetsmtp = "SELECT * FROM `smtp` WHERE `aktif`='Y'";
																											$getsmtp = $conn->query($sqlgetsmtp);
																											$gsmtp = $getsmtp->fetch_assoc();
																											$email = $rowAgen['email'];
																											$namanya = $rowAgen['nama'];
																											$no_hp = $rowAgen['nohp'];
																											date_default_timezone_set("Asia/Jakarta");
																											$mail = new PHPMailer;
																											$mail->IsSMTP();
																											$mail->SMTPSecure = $gsmtp['protokol'];
																											$mail->Host = $gsmtp['host']; //host masing2 provider email
																											$mail->SMTPDebug = 0;
																											$mail->Port = $gsmtp['port'];
																											$mail->SMTPAuth = true;
																											$mail->Username = $gsmtp['username']; //user email
																											$mail->Password = $gsmtp['password']; //password email 
																											//Set who the message is to be sent from
																											$mail->setFrom("$gsmtp[username]", $iden['nama_lsp']);
																											//Set an alternative reply-to address
																											$mail->addReplyTo("$gsmtp[username]", $iden['nama_lsp']);
																											$mail->Subject = $subjek; //subyek email
																											$mail->AddAddress($email, $namanya);  //tujuan email
																											$mail->MsgHTML($pesan);
																											if ($mail->Send()) {
																												echo "";
																											} else {
																												echo "Notifikasi Email Gagal Terkirim, periksa pengaturan di menu Manajemen, sub menu Pengaturan SMTP";
																											}
																											//SMS Pendaftar
																											$isisms = "Yth. $namanya Asesmen Mandiri pada Skema $sk[kode_skema]-$sk[judul] diverifikasi, dengan biaya asesmen $tampilbiaya. Silahkan cek email Anda";
																											if (strlen($no_hp) > 8) {
																												$sqloutbox = "INSERT INTO `outbox` (`DestinationNumber`, `TextDecoded`, `Coding`,`SenderID`,`CreatorID`) VALUES ('$no_hp','$isisms','Default_No_Compression','MyPhone1','MyPhone1')";
																												$outbox = $conn->query($sqloutbox);
																											}
																										}
																										// kirim status asesmen apl 02 ========================
																										switch ($_POST['status_apl02']) {
																											case "R":
																												$pesan = "Asesmen Mandiri (APL-02) Anda pada Skema $sk[kode_skema] - $sk[judul]<br />Dinyatakan telah diverifikasi oleh Asesor dan dinyatakan TIDAK DAPAT DILANJUTKAN.<br />  
					ID Asesi: $_GET[ida] <br />
					Nama: $rowAgen[nama] <br />
					Nomor Handphone: $rowAgen[nohp] <br />
					Skema: $sk[kode_skema] - $sk[judul]<br />
					Status: TIDAK DAPAT DILANJUTKAN<br />";
																												$subjek = "Asesmen Mandiri (APL-02) Anda di $iden[nama_lsp] telah diverifikasi oleh Asesor dan dinyatakan TIDAK DAPAT DILANJUTKAN";
																												$dari = "From: noreply@" . $urldomain . "\r\n";
																												$dari .= "Content-type: text/html\r\n";
																												// Kirim email ke member
																												$sqlgetsmtp = "SELECT * FROM `smtp` WHERE `aktif`='Y'";
																												$getsmtp = $conn->query($sqlgetsmtp);
																												$gsmtp = $getsmtp->fetch_assoc();
																												$email = $rowAgen['email'];
																												$namanya = $rowAgen['nama'];
																												$no_hp = $rowAgen['nohp'];
																												date_default_timezone_set("Asia/Jakarta");
																												$mail = new PHPMailer;
																												$mail->IsSMTP();
																												$mail->SMTPSecure = $gsmtp['protokol'];
																												$mail->Host = $gsmtp['host']; //host masing2 provider email
																												$mail->SMTPDebug = 0;
																												$mail->Port = $gsmtp['port'];
																												$mail->SMTPAuth = true;
																												$mail->Username = $gsmtp['username']; //user email
																												$mail->Password = $gsmtp['password']; //password email 
																												//Set who the message is to be sent from
																												$mail->setFrom("$gsmtp[username]", $iden['nama_lsp']);
																												//Set an alternative reply-to address
																												$mail->addReplyTo("$gsmtp[username]", $iden['nama_lsp']);
																												$mail->Subject = $subjek; //subyek email
																												$mail->AddAddress($email, $namanya);  //tujuan email
																												$mail->MsgHTML($pesan);
																												if ($mail->Send()) {
																													echo "";
																												} else {
																													echo "Notifikasi Email Gagal Terkirim, periksa pengaturan di menu Manajemen, sub menu Pengaturan SMTP";
																												}
																												//SMS Pendaftar
																												$isisms = "Yth. $namanya Asesmen Mandiri pada Skema $sk[kode_skema]-$sk[judul] diverifikasi, DINYATAKAN TIDAK DAPAT DILANJUTKAN. Silahkan cek email Anda";
																												if (strlen($no_hp) > 8) {
																													$sqloutbox = "INSERT INTO `outbox` (`DestinationNumber`, `TextDecoded`, `Coding`,`SenderID`,`CreatorID`) VALUES ('$no_hp','$isisms','Default_No_Compression','MyPhone1','MyPhone1')";
																													$outbox = $conn->query($sqloutbox);
																												}
																												break;
																											case "A":
																												$pesan = "Asesmen Mandiri (APL-02) Anda pada Skema $sk[kode_skema] - $sk[judul]<br />Dinyatakan telah diverifikasi oleh Asesor dan dinyatakan DAPAT DILANJUTKAN.<br />  
					ID Asesi: $_GET[ida] <br />
					Nama: $rowAgen[nama] <br />
					Nomor Handphone: $rowAgen[nohp] <br />
					Skema: $sk[kode_skema] - $sk[judul]<br />
					Status: DAPAT DILANJUTKAN<br />";
																												$subjek = "Asesmen Mandiri (APL-02) Anda di $iden[nama_lsp] telah diverifikasi oleh Asesor dan dinyatakan DAPAT DILANJUTKAN";
																												$dari = "From: noreply@" . $urldomain . "\r\n";
																												$dari .= "Content-type: text/html\r\n";
																												// Kirim email ke member
																												$sqlgetsmtp = "SELECT * FROM `smtp` WHERE `aktif`='Y'";
																												$getsmtp = $conn->query($sqlgetsmtp);
																												$gsmtp = $getsmtp->fetch_assoc();
																												$email = $rowAgen['email'];
																												$namanya = $rowAgen['nama'];
																												$no_hp = $rowAgen['nohp'];
																												date_default_timezone_set("Asia/Jakarta");
																												$mail = new PHPMailer;
																												$mail->IsSMTP();
																												$mail->SMTPSecure = $gsmtp['protokol'];
																												$mail->Host = $gsmtp['host']; //host masing2 provider email
																												$mail->SMTPDebug = 0;
																												$mail->Port = $gsmtp['port'];
																												$mail->SMTPAuth = true;
																												$mail->Username = $gsmtp['username']; //user email
																												$mail->Password = $gsmtp['password']; //password email 
																												//Set who the message is to be sent from
																												$mail->setFrom("$gsmtp[username]", $iden['nama_lsp']);
																												//Set an alternative reply-to address
																												$mail->addReplyTo("$gsmtp[username]", $iden['nama_lsp']);
																												$mail->Subject = $subjek; //subyek email
																												$mail->AddAddress($email, $namanya);  //tujuan email
																												$mail->MsgHTML($pesan);
																												if ($mail->Send()) {
																													echo "";
																												} else {
																													echo "Notifikasi Email Gagal Terkirim, periksa pengaturan di menu Manajemen, sub menu Pengaturan SMTP";
																												}
																												//SMS Pendaftar
																												$isisms = "Yth. $namanya Asesmen Mandiri pada Skema $sk[kode_skema]-$sk[judul] diverifikasi, DINYATAKAN DAPAT DILANJUTKAN. Silahkan cek email Anda";
																												if (strlen($no_hp) > 8) {
																													$sqloutbox = "INSERT INTO `outbox` (`DestinationNumber`, `TextDecoded`, `Coding`,`SenderID`,`CreatorID`) VALUES ('$no_hp','$isisms','Default_No_Compression','MyPhone1','MyPhone1')";
																													$outbox = $conn->query($sqloutbox);
																												}
																												break;
																											default:
																												break;
																										}
																										//============================================================================
																									}
																									echo "<!-- Main content -->
			<section class='content'>
				<div class='row'>
					<div class='col-xs-12'>
						<div class='box'>
							<div class='box-body'>
								<!-- form start -->
								<div class='box-body'>
			<h2>Formulir Penilaian Asesmen Mandiri Asesi Oleh Asesor</h2>
			<h2 class='text-green'>Nama Asesi : $rowAgen[nama]</h2>
							<form role='form' method='POST' enctype='multipart/form-data'>
			<p>Pada bagian ini, anda diminta untuk melakukan penilaian atas asesmen mandiri unit (unit-unit) kompetensi yang akan di-ases.</p>
			<p>
				<ol>
					<li>Pelajari seluruh standar <b>Kriteria Unjuk Kerja  (KUK)</b>, batasan variabel, panduan penilaian dan aspek kritis serta yakinkan bahwa anda sudah benar-benar memahami seluruh isinya.</li>
					<li>Laksanakan penilaian mandiri dengan mempelajari dan menilai kemampuan yang anda miliki secara obyektif terhadap seluruh daftar pertanyaan yang ada, serta tentukan apakah sudah <b>kompeten (K)</b> atau <b>belum kompeten (BK)</b>.</li>
					<li>Siapkan bukti-bukti yang anda anggap relevan terhadap unit kompetensi, serta &quot;<em>matching</em>&quot;-kan setiap bukti yang ada terhadap setiap elemen/KUK, konteks variable, pengetahuan dan keterampilan yang dipersyaratkan serta aspek kritis</li>
					<li>Asesor dan asesi menandatangani form Asesmen Mandiri</li>
				</ol>
			</p>";
																									$sqlgetunitkompetensi = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
																									$getunitkompetensi = $conn->query($sqlgetunitkompetensi);
																									$no = 1;
																									while ($unk = $getunitkompetensi->fetch_assoc()) {
																										$sqlcekukom = "SELECT * FROM `asesmen_unitkompetensi` WHERE `id_skemakkni`='$sk[id]' AND `id_asesi`='$_GET[ida]' AND `id_unitkompetensi`='$unk[id]'";
																										$cekukom = $conn->query($sqlcekukom);
																										$ukom = $cekukom->num_rows;
																										if ($ukom > 0) {
																											echo "<div class='box box-solid'>
							<div class='col-md-12'>";	  //satu
																											echo "<div class='box-header with-border'>
								<h3 class='box-title text-green'><b>Unit Kompetensi : $no. $unk[judul]</b></h3>
							</div>
							<div class='box-body'>
								<div class='col-md-12'>"; //satu-satu
																											$no2 = 1;
																											$sqlgetelemen = "SELECT * FROM `elemen_kompetensi` WHERE `id_unitkompetensi`='$unk[id]' ORDER BY `id` ASC";
																											$getelemen = $conn->query($sqlgetelemen);
																											while ($el = $getelemen->fetch_assoc()) {
																												$no3 = 1;
																												echo "<div class='col-md-12 bg-green'>
											<label>Elemen Kompetensi : $no.$no2. $el[elemen_kompetensi]</label>
										</div>";
																												echo "<div class='col-md-12'>
											<div class='form-group'>
											<label>Kriteria Unjuk Kerja :</label>";
																												$sqlgetkriteria = "SELECT * FROM `kriteria_unjukkerja` WHERE `id_elemenkompetensi`='$el[id]' ORDER BY `id` ASC";
																												$getkriteria = $conn->query($sqlgetkriteria);
																												while ($kr = $getkriteria->fetch_assoc()) {
																													/* 									
												if ($numjjw0==0){
													$kriteriatampil=str_replace("Anda",$rowAgen['nama'],$kr[kriteria]);
													echo "<div class='col-md-12'><p>$no.$no2.$no3. $kriteriatampil</p></div>";
													echo "<div class='col-md-12'>
														<span class='text-red'><b>Belum menjawab</b></span>
													</div>";
												}else{ */
																													$kriteriatampil = str_replace("Anda", $rowAgen['nama'], $kr['kriteria']);
																													echo "<div class='col-md-12'>
														<p>$no.$no2.$no3. $kriteriatampil</p>
													</div>";
																													//}
																													$no3++;
																												}
																												$sqlcekjawaban0 = "SELECT * FROM `asesi_apl02` WHERE `id_asesi`='$_GET[ida]' AND `id_elemen`='$el[id]' AND `id_skemakkni`='$sk[id]' AND `id_jadwal`='$_GET[idj]'";
																												$cekjawaban0 = $conn->query($sqlcekjawaban0);
																												$jjw0 = $cekjawaban0->fetch_assoc();
																												$numjjw0 = $cekjawaban0->num_rows;
																												switch ($jjw0['jawaban']) {
																													case "1":
																														echo "<div class='col-md-2'>
														<a class='btn btn-success btn-xs'><span class='glyphicon glyphicon-ok' aria-hidden='true' title='Kompeten'></span></a>&nbsp;&nbsp;Kompeten &nbsp;&nbsp;&nbsp;
													</div>
													<div class='col-md-4'>
														<div class='form-group'>
															<label>Bukti unjuk kerja:</label><br>";
																														$getfilebukti = "SELECT * FROM `asesi_apl02doc` WHERE `id_asesi`='$_GET[ida]' AND `id_elemenkompetensi`='$el[id]' AND `id_skemakkni`='$sk[id]' AND `id_jadwal`='$_GET[idj]'";
																														$filebukti = $conn->query($getfilebukti);
																														$nodokumen = 1;
																														while ($fbk = $filebukti->fetch_assoc()) {
																															echo "Dokumen $no.$no2.-($nodokumen)&nbsp;<a href='#myModal" . $fbk['id'] . "' class='btn btn-success btn-xs' data-toggle='modal' data-id='" . $fbk['id'] . "'><span class='glyphicon glyphicon-zoom-in' aria-hidden='true' title='Lihat/Unduh Dokumen'></span></a><br>";
																															echo "<script>
																	$(function(){
																				$(document).on('click','.edit-record',function(e){
																					e.preventDefault();
																					$('#myModal" . $fbk['id'] . "').modal('show');
																				});
																		});
																</script>
																<!-- Modal -->
																	<div class='modal fade' id='myModal" . $fbk['id'] . "' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
																		<div class='modal-dialog'>
																			<div class='modal-content'>
																				<div class='modal-header'>
																					<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Tutup</span></button>
																					<h4 class='modal-title text-green' id='myModalLabel'>Dokumen Bukti " . $kriteriatampil . "</h4>
																				</div>
																				<div class='modal-body'><embed src='../foto_apl02/$fbk[file]' width='100%' height='700px'/>
																				</div>
																				<div class='modal-footer'>
																					<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>
																				</div>
																			</div>
																		</div>
																	</div>
																<!-- Modal End -->";
																															$nodokumen++;
																														}
																														echo "<label>
																Catatan Bukti-bukti Kompetensi :
															</label><br>";
																														if ($cekasesiasesmen['status_apl02'] == 'A' || $cekasesiasesmen['status_apl02'] == 'R') {
																															echo "<textarea name='bukti$el[id]' id='bukti$el[id]' class='form-control' placeholder='isikan bukti-bukti kompetensi' disabled>$jjw0[keterangan_bukti]</textarea>";
																														} else {
																															echo "<textarea name='bukti$el[id]' id='bukti$el[id]' class='form-control' placeholder='isikan bukti-bukti kompetensi' required>$jjw0[keterangan_bukti]</textarea>";
																														}
																														echo "</div><!-- /.form-group-->
													</div><!-- /.col-->";
																														break;
																													case "0":
																														echo "<div class='col-md-12'>
														<a class='btn btn-danger btn-xs'><span class='fa fa-times' aria-hidden='true' title='Belum Kompeten'></span></a>&nbsp;&nbsp;Belum Kompeten &nbsp;&nbsp;&nbsp;
													</div>";
																														break;
																													default:
																														echo "<div class='col-md-12'>
														<b><font color='red'>Belum Menjawab</font></b>
													</div>";
																														break;
																												}
																												echo "</div><!-- /.form-group-->
										</div><!-- /.col-->";
																												$no2++;
																											}
																											echo "</div>
							</div><!-- /.box-body-->
						</div></div><!-- /.box box-solid-->"; //satu
																											$no++;
																										}
																									}
																									$sqlgetasesmen = "SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																									$getasesmen = $conn->query($sqlgetasesmen);
																									$getassm = $getasesmen->fetch_assoc();
																									if ($cekasesiasesmen['status_apl02'] == 'A' || $cekasesiasesmen['status_apl02'] == 'R') {
																										echo "<div class='col-md-12'>
					<h3>Rekomendasi :</h3>
						<input type='radio' name='status_apl02' id='rekomendasiapl021' value='A'";
																										if ($getassm['status_apl02'] == "A") {
																											echo " checked";
																										} else {
																											echo "";
																										}
																										echo " disabled>
						Asesmen dapat dilanjutkan<br>
						<input type='radio' name='status_apl02' id='rekomendasiapl022' value='R'";
																										if ($getassm['status_apl02'] == "R") {
																											echo " checked";
																										} else {
																											echo "";
																										}
																										echo " disabled>
						Asesmen tidak dapat dilanjutkan<br>
					</div>";
																									} else {
																										echo "<div class='col-md-12'>
					<h3>Rekomendasi :</h3>
						<input type='radio' required='required' name='status_apl02' id='rekomendasiapl021' value='A'";
																										if ($getassm['status_apl02'] == "A") {
																											echo " checked";
																										} else {
																											echo "";
																										}
																										echo ">
						Asesmen dapat dilanjutkan<br>
						<input type='radio' required='required' name='status_apl02' id='rekomendasiapl022' value='R'";
																										if ($getassm['status_apl02'] == "R") {
																											echo " checked";
																										} else {
																											echo "";
																										}
																										echo ">
						Asesmen tidak dapat dilanjutkan<br>
					</div>";
																									}
																									echo "</div>";
																									// cek tandatangan digital
																									$url = "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
																									$iddokumen = md5($url);
																									$sqlcektandatangan = "SELECT * FROM `logdigisign` WHERE id_dokumen='$iddokumen' AND id_skema='$jd[id_skemakkni]' AND nama_dokumen='FR.APL.02. ASESMEN MANDIRI' ORDER BY `id` DESC";
																									$cektandatangan = $conn->query($sqlcektandatangan);
																									$jumttd = $cektandatangan->num_rows;
																									$ttdx = $cektandatangan->fetch_assoc();
																									if ($jumttd > 0) {
																										echo "<div class='col-md-12'>
										<label class='' for=''>Persetujuan/ Tanda Tangan yang telah Anda berikan:</label>
										<br/>
										<img src='$ttdx[file]' width='400px'/>
										<br/>
							</div>";
																									}

																									if ($cekasesiasesmen['status_apl02'] == 'A' || $cekasesiasesmen['status_apl02'] == 'R') {
																										echo "<div class='box-footer'>
									<div class='col-md-4 col-sm-12 col-xs-12'>
					<a class='btn btn-danger form-control' id=reset-validate-form href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a>
			</div>
			<div class='col-md-4 col-sm-12 col-xs-12'>
					<a href='form-apl-02-el.php?ida=$_GET[ida]&idj=$_GET[idj]' class='btn btn-primary form-control' target='_blank'>Unduh Form Jawaban</a>
			</div>";
																									} else {
																										echo "<div class='col-md-12'>
							<label class='' for=''>Tanda Tangan:</label>
							<br/>
							<div id='sig' ></div>
							<br/>
							<button id='clear'>Hapus Tanda Tangan</button>
							<textarea id='signature64' name='signed' style='display: none'></textarea>
					</div>
			<script type='text/javascript'>
				var sig = $('#sig').signature({syncField: '#signature64', syncFormat: 'PNG', color: '#58009F'});
				$('#clear').click(function(e) {
					e.preventDefault();
					sig.signature('clear');
					$('#signature64').val('');
				});
			</script>";
																										echo "<div class='box-footer'>
									<div class='col-md-4 col-sm-12 col-xs-12'>
					<a class='btn btn-danger form-control' id=reset-validate-form href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a>
			</div>
			<div class='col-md-4 col-sm-12 col-xs-12'>
					<a href='form-apl-02-el.php?ida=$_GET[ida]&idj=$_GET[idj]' class='btn btn-primary form-control' target='_blank'>Unduh Form Jawaban</a>
			</div>
			<div class='col-md-4 col-sm-12 col-xs-12'>
					<button type='submit' class='btn btn-success form-control' name='simpan'>Simpan Jawaban</button>
			</div>";
																									}
																									echo "</div>
							</form>			
							</div>
							<!-- /.box-body -->
						</div>
						<!-- /.box -->
					</div>
					<!-- /.col -->
				</div>
				<!-- /.row -->
			</section>
			<!-- /.content -->";
																								}
																								// Bagian Input Asesmen Mandiri FORM-APL-02 Asesi
																								elseif ($_GET['module'] == 'form-apl-02') {
																									$sqllogin = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_GET[ida]'";
																									$login = $conn->query($sqllogin);
																									$ketemu = $login->num_rows;
																									$rowAgen = $login->fetch_assoc();
																									$sqlgetjadwal = "SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
																									$getjadwal = $conn->query($sqlgetjadwal);
																									$jd = $getjadwal->fetch_assoc();
																									$sqlgetskema = "SELECT * FROM `skema_kkni` WHERE `id`='$jd[id_skemakkni]'";
																									$getskema = $conn->query($sqlgetskema);
																									$sk = $getskema->fetch_assoc();
																									$sqlgetasesordata = "SELECT * FROM `asesor` WHERE `no_ktp`='$_SESSION[namauser]'";
																									$getasesordata = $conn->query($sqlgetasesordata);
																									$asr = $getasesordata->fetch_assoc();
																									echo "<!-- Content Header (Page header) -->
			<section class='content-header'>
				<h1>
			Asesmen Mandiri (FORM-APL-02)
					<small>Skema $sk[judul]</small>
				</h1>
				<ol class='breadcrumb'>
					<li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
					<li class='active'>Penilaian Asesmen Mandiri Asesi</li>
				</ol>
			</section>";
																									function uploadFoto($file)
																									{
																										$file_max_weight = 20097152; //Ukuran maksimal yg dibolehkan(2Mb)
																										$ok_ext = array('jpg', 'png', 'gif', 'jpeg', 'JPG', 'PNG', 'GIF', 'JPEG'); // ekstensi yang diijinkan
																										$destination = "foto_asesi/"; // tempat buat upload
																										$filename = explode(".", $file['name']);
																										$file_name = $file['name'];
																										$file_name_no_ext = isset($filename[0]) ? $filename[0] : null;
																										$file_extension = $filename[count($filename) - 1];
																										$file_weight = $file['size'];
																										$file_type = $file['type'];
																										// Jika tidak ada error
																										if ($file['error'] == 0) {
																											$dateNow = date_create();
																											$time_stamp = date_format($dateNow, 'U');
																											if (in_array($file_extension, $ok_ext)) :
																												if ($file_weight <= $file_max_weight) :
																													$fileNewName = $time_stamp . md5($file_name_no_ext[0] . microtime()) . "." . $file_extension;
																													$alamatfile = $fileNewName;
																													if (move_uploaded_file($file['tmp_name'], $destination . $fileNewName)) :
																													//echo" File uploaded !";
																													else :
																													//echo "can't upload file.";
																													endif;
																												else :
																												//echo "File too heavy.";
																												endif;
																											else :
																											//echo "File type is not supported.";
																											endif;
																										}
																										return $alamatfile;
																									}
																									if (isset($_REQUEST['simpan'])) {
																										$sqlgetunitkompetensi2 = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
																										$getunitkompetensi2 = $conn->query($sqlgetunitkompetensi2);
																										while ($unk2 = $getunitkompetensi2->fetch_assoc()) {
																											$sqlcekukom = "SELECT * FROM `asesmen_unitkompetensi` WHERE `id_skemakkni`='$sk[id]' AND `id_asesi`='$_GET[ida]' AND `id_unitkompetensi`='$unk2[id]'";
																											$cekukom = $conn->query($sqlcekukom);
																											$ukom = $cekukom->num_rows;
																											if ($ukom > 0) {
																												$sqlgetelemen2 = "SELECT * FROM `elemen_kompetensi` WHERE `id_unitkompetensi`='$unk2[id]' ORDER BY `id` ASC";
																												$getelemen2 = $conn->query($sqlgetelemen2);
																												while ($el2 = $getelemen2->fetch_assoc()) {
																													$sqlgetkriteria2 = "SELECT * FROM `kriteria_unjukkerja` WHERE `id_elemenkompetensi`='$el2[id]' ORDER BY `id` ASC";
																													$getkriteria2 = $conn->query($sqlgetkriteria2);
																													while ($kr2 = $getkriteria2->fetch_assoc()) {
																														$id_jawaban0 = 'bukti' . $kr2['id'];
																														$id_jawaban = 'checkbox1' . $kr2['id'];
																														$id_jawabanb = 'checkbox2' . $kr2['id'];
																														$id_jawabanc = 'checkbox3' . $kr2['id'];
																														$id_jawaband = 'checkbox4' . $kr2['id'];
																														$sqlcekjawaban = "SELECT * FROM `asesi_apl02` WHERE `id_asesi`='$_GET[ida]' AND `id_kriteria`='$kr2[id]' AND `id_skemakkni`='$sk[id]' AND `id_jadwal`='$_GET[idj]'";
																														$cekjawaban = $conn->query($sqlcekjawaban);
																														$jjw = $cekjawaban->num_rows;
																														if ($jjw == 0) {
																															$sqlinputjawaban = "INSERT INTO `asesi_apl02`(`id_asesi`, `id_kriteria`, `id_skemakkni`, `jawaban`, ``, ``) VALUES ('$_GET[ida]','$kr2[id]','$sk[id]','$_POST[$id_jawaban]','$_POST[$id_jawabanb]','$_POST[$id_jawabanc]')";
																															$conn->query($sqlinputjawaban);
																														} else {
																															$sqlinputjawaban = "UPDATE `asesi_apl02` SET `keterangan_bukti`='$_POST[$id_jawaban0]',`verifikasi_asesor1`='$_POST[$id_jawaban]',`verifikasi_asesor2`='$_POST[$id_jawabanb]',`verifikasi_asesor3`='$_POST[$id_jawabanc]',`verifikasi_asesor4`='$_POST[$id_jawaband]',`id_asesor`='$asr[id]' WHERE `id_asesi`='$_GET[ida]' AND `id_kriteria`='$kr2[id]' AND `id_skemakkni`='$sk[id]' AND `id_jadwal`='$_GET[idj]'";
																															$conn->query($sqlinputjawaban);
																														}
																													}
																												}
																											}
																										}
																										$folderPath = "../foto_tandatangan/";
																										if (empty($_POST['signed'])) {
																											echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Penilaian Asesor berhasil disimpan</h4>
				Terimakasih, Anda telah melakukan <b>Penilaian Asesmen Mandiri untuk Skema $sk[judul].</b><br>
				<a class='btn btn-warning form-control' href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a></div>";
																										} else {
																											$image_parts = explode(";base64,", $_POST['signed']);
																											$image_type_aux = explode("image/", $image_parts[0]);
																											$image_type = $image_type_aux[1];
																											$image_base64 = base64_decode($image_parts[1]);
																											$file = $folderPath . uniqid() . '.' . $image_type;
																											file_put_contents($file, $image_base64);
																											$url =  "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
																											$iddokumen = md5($url);
																											$escaped_url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
																											$alamatip = $_SERVER['REMOTE_ADDR'];
																											$sqlinputdigisign = "INSERT INTO `logdigisign`(`id_dokumen`, id_skema, id_asesi, `url_ditandatangani`, `nama_dokumen`, `penandatangan`, `file`, `ip`) VALUES ('$iddokumen','$sk[id]','$_SESSION[namauser]','$escaped_url','FR-APL-01. FORMULIR PERMOHONAN SERTIFIKASI KOMPETENSI','$rowAgen[nama]','$file','$alamatip')";
																											$conn->query($sqlinputdigisign);
																											echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Penilaian Asesor berhasil disimpan</h4>
				Terimakasih, Anda telah melakukan <b>Penilaian Asesmen Mandiri untuk Skema $sk[judul].</b><br>
				<a class='btn btn-warning form-control' href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a></div>";
																										}
																										$sqlupdateasesmenasesi = "UPDATE `asesi_asesmen` SET `status_apl02`='$_POST[status_apl02]' WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																										$conn->query($sqlupdateasesmenasesi);
																									}
																									echo "<!-- Main content -->
			<section class='content'>
				<div class='row'>
					<div class='col-xs-12'>
						<div class='box'>
							<div class='box-body'>
								<!-- form start -->
								<div class='box-body'>
			<h2>Formulir Penilaian Asesmen Mandiri Asesi Oleh Asesor</h2>
			<h2 class='text-green'>Nama Asesi : $rowAgen[nama]</h2>
							<form role='form' method='POST' enctype='multipart/form-data'>
			<p>Pada bagian ini, anda diminta untuk melakukan penilaian atas asesmen mandiri unit (unit-unit) kompetensi yang akan di-ases.</p>
			<p>
				<ol>
					<li>Pelajari seluruh standar <b>Kriteria Unjuk Kerja  (KUK)</b>, batasan variabel, panduan penilaian dan aspek kritis serta yakinkan bahwa anda sudah benar-benar memahami seluruh isinya.</li>
					<li>Laksanakan penilaian mandiri dengan mempelajari dan menilai kemampuan yang anda miliki secara obyektif terhadap seluruh daftar pertanyaan yang ada, serta tentukan apakah sudah <b>kompeten (K)</b> atau <b>belum kompeten (BK)</b>.</li>
					<li>Siapkan bukti-bukti yang anda anggap relevan terhadap unit kompetensi, serta &quot;<em>matching</em>&quot;-kan setiap bukti yang ada terhadap setiap elemen/KUK, konteks variable, pengetahuan dan keterampilan yang dipersyaratkan serta aspek kritis</li>
					<li>Asesor dan asesi menandatangani form Asesmen Mandiri</li>
				</ol>
			</p>";
																									$sqlgetunitkompetensi = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
																									$getunitkompetensi = $conn->query($sqlgetunitkompetensi);
																									$no = 1;
																									while ($unk = $getunitkompetensi->fetch_assoc()) {
																										$sqlcekukom = "SELECT * FROM `asesmen_unitkompetensi` WHERE `id_skemakkni`='$sk[id]' AND `id_asesi`='$_GET[ida]' AND `id_unitkompetensi`='$unk[id]'";
																										$cekukom = $conn->query($sqlcekukom);
																										$ukom = $cekukom->num_rows;
																										if ($ukom > 0) {
																											echo "<div class='box box-solid'>
						<div class='col-md-12'>";	  //satu
																											echo "<div class='box-header with-border'>
							<h3 class='box-title text-green'><b>Unit Kompetensi : $no. $unk[judul]</b></h3>
						</div>
						<div class='box-body'>
							<div class='col-md-12'>"; //satu-satu
																											$no2 = 1;
																											$sqlgetelemen = "SELECT * FROM `elemen_kompetensi` WHERE `id_unitkompetensi`='$unk[id]' ORDER BY `id` ASC";
																											$getelemen = $conn->query($sqlgetelemen);
																											while ($el = $getelemen->fetch_assoc()) {
																												$no3 = 1;
																												echo "<div class='col-md-12 bg-green'><label>Elemen Kompetensi : $no.$no2. $el[elemen_kompetensi]</label></div>";
																												echo "<div class='col-md-12'>
										<div class='form-group'>
										<label>Kriteria Unjuk Kerja :</label>";
																												$sqlgetkriteria = "SELECT * FROM `kriteria_unjukkerja` WHERE `id_elemenkompetensi`='$el[id]' ORDER BY `id` ASC";
																												$getkriteria = $conn->query($sqlgetkriteria);
																												while ($kr = $getkriteria->fetch_assoc()) {
																													$sqlcekjawaban0 = "SELECT * FROM `asesi_apl02` WHERE `id_asesi`='$_GET[ida]' AND `id_kriteria`='$kr[id]' AND `id_skemakkni`='$sk[id]'";
																													$cekjawaban0 = $conn->query($sqlcekjawaban0);
																													$jjw0 = $cekjawaban0->fetch_assoc();
																													$numjjw0 = $cekjawaban0->num_rows;
																													if ($numjjw0 == 0) {
																														$kriteriatampil = str_replace("Anda", $rowAgen['nama'], $kr[kriteria]);
																														echo "<div class='col-md-12'><p>$no.$no2.$no3. $kriteriatampil</p></div>
												<div class='col-md-12'>
													<span class='text-red'><b>Belum menjawab</b></span>
												</div>";
																													} else {
																														$kriteriatampil = str_replace("Anda", $rowAgen['nama'], $kr[kriteria]);
																														echo "<div class='col-md-12'><p>$no.$no2.$no3. $kriteriatampil</p></div>";
																														switch ($jjw0['jawaban']) {
																															case "1":
																																echo "<div class='col-md-2'>
														<a class='btn btn-success btn-xs'><span class='glyphicon glyphicon-ok' aria-hidden='true' title='Kompeten'></span></a>&nbsp;&nbsp;Kompeten &nbsp;&nbsp;&nbsp;
													</div>
													<div class='col-md-4'>
														<div class='form-group'><label>Bukti unjuk kerja:</label><br>";
																																$getfilebukti = "SELECT * FROM `asesi_apl02doc` WHERE `id_asesi`='$_GET[ida]' AND `id_kriteria`='$kr[id]' AND `id_elemenkompetensi`='$el[id]' AND `id_skemakkni`='$sk[id]' AND `id_jadwal`='$_GET[idj]'";
																																$filebukti = $conn->query($getfilebukti);
																																$nodokumen = 1;
																																while ($fbk = $filebukti->fetch_assoc()) {
																																	//$fbk[file];
																																	echo "Dokumen $no.$no2.$no3.$nodokumen&nbsp;<a href='#myModal" . $fbk['id'] . "' class='btn btn-success btn-xs' data-toggle='modal' data-id='" . $fbk['id'] . "'><span class='glyphicon glyphicon-zoom-in' aria-hidden='true' title='Lihat/Unduh Dokumen'></span></a><br>";
																																	echo "<script>
																$(function(){
																			$(document).on('click','.edit-record',function(e){
																				e.preventDefault();
																				$('#myModal" . $fbk['id'] . "').modal('show');
																			});
																	});
															</script>
															<!-- Modal -->
																<div class='modal fade' id='myModal" . $fbk['id'] . "' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
																	<div class='modal-dialog'>
																		<div class='modal-content'>
																			<div class='modal-header'>
																				<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Tutup</span></button>
																				<h4 class='modal-title text-green' id='myModalLabel'>Dokumen Bukti " . $kriteriatampil . "</h4>
																			</div>
																			<div class='modal-body'><embed src='../foto_apl02/$fbk[file]' width='100%' height='100%'/>
																			</div>
																			<div class='modal-footer'>
																				<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>
																			</div>
																		</div>
																	</div>
																</div>
															<!-- Modal End -->";
																																	$nodokumen++;
																																}
																																echo "<label>
														Catatan Bukti-bukti Kompetensi :
														</label><br>";
																																echo "<textarea name='bukti$kr[id]' id='bukti$kr[id]' class='form-control' placeholder='isikan bukti-bukti kompetensi'>$jjw0[keterangan_bukti]</textarea>";
																																echo "</div>
													</div>";
																																break;
																															case "0":
																																echo "<div class='col-md-12'>
														<a class='btn btn-danger btn-xs'><span class='glyphicon glyphicon-times' aria-hidden='true' title='Belum Kompeten'></span></a>&nbsp;&nbsp;Belum Kompeten &nbsp;&nbsp;&nbsp;
													</div>";
																																break;
																															default:
																																echo "<div class='col-md-12'>
														<b>Belum Menjawab</b>
													</div>";
																																break;
																														}
																													}
																													$no3++;
																												}
																												echo "</div>
									</div>";
																												$no2++;
																											}
																											echo "</div>
						</div><!-- /.box-body-->
					</div></div><!-- /.box box-solid-->"; //satu
																											$no++;
																										}
																									}
																									$sqlgetasesmen = "SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																									$getasesmen = $conn->query($sqlgetasesmen);
																									$getassm = $getasesmen->fetch_assoc();
																									echo "<div class='col-md-12'>
				<h3>Rekomendasi :</h3>
					<input type='radio' required='required' name='status_apl02' id='rekomendasiapl021' value='A'";
																									if ($getassm['status_apl02'] == "A") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">
					Asesmen dapat dilanjutkan<br>
					<input type='radio' required='required' name='status_apl02' id='rekomendasiapl022' value='R'";
																									if ($getassm['status_apl02'] == "R") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">
					Asesmen tidak dapat dilanjutkan<br>
			</div>";
																									echo "</div>";
																									// cek tandatangan digital
																									$url = "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
																									$iddokumen = md5($url);
																									$sqlcektandatangan = "SELECT * FROM `logdigisign` WHERE `id_dokumen`='$iddokumen' ORDER BY `id` DESC";
																									$cektandatangan = $conn->query($sqlcektandatangan);
																									$jumttd = $cektandatangan->num_rows;
																									$ttdx = $cektandatangan->fetch_assoc();
																									if ($jumttd > 0) {
																										echo "<div class='col-md-12'>
										<label class='' for=''>Persetujuan/ Tanda Tangan yang telah Anda berikan:</label>
										<br/>
										<img src='../$ttdx[file]' width='400px'/>
										<br/>
							</div>";
																									}
																									echo "<div class='col-md-12'>
							<label class='' for=''>Tanda Tangan:</label>
							<br/>
							<div id='sig' ></div>
							<br/>
							<button id='clear'>Hapus Tanda Tangan</button>
							<textarea id='signature64' name='signed' style='display: none'></textarea>
					</div>
			<script type='text/javascript'>
				var sig = $('#sig').signature({syncField: '#signature64', syncFormat: 'PNG', color: '#58009F'});
				$('#clear').click(function(e) {
					e.preventDefault();
					sig.signature('clear');
					$('#signature64').val('');
				});
			</script>";
																									echo "<div class='box-footer'>
									<div class='col-md-4 col-sm-12 col-xs-12'>
					<a class='btn btn-danger form-control' id=reset-validate-form href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a>
			</div>
			<div class='col-md-4 col-sm-12 col-xs-12'>
					<a href='form-apl-02.php?ida=$_GET[ida]&idj=$_GET[idj]' class='btn btn-primary form-control' target='_blank'>Unduh Form Jawaban</a>
			</div>
			<div class='col-md-4 col-sm-12 col-xs-12'>
					<button type='submit' class='btn btn-success form-control' name='simpan'>Simpan Jawaban</button>
			</div>
								</div>
							</form>			
							</div>
							<!-- /.box-body -->
						</div>
						<!-- /.box -->
					</div>
					<!-- /.col -->
				</div>
				<!-- /.row -->
			</section>
			<!-- /.content -->";
																								}
																								// Bagian Input Hasil Asesmen FORM-ASC-01 Asesor
																								elseif ($_GET['module'] == 'form-asc-01') {
																									$sqllogin = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_GET[ida]'";
																									$login = $conn->query($sqllogin);
																									$ketemu = $login->num_rows;
																									$rowAgen = $login->fetch_assoc();
																									$sqlgetjadwal = "SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
																									$getjadwal = $conn->query($sqlgetjadwal);
																									$jd = $getjadwal->fetch_assoc();
																									$sqlgetskema = "SELECT * FROM `skema_kkni` WHERE `id`='$jd[id_skemakkni]'";
																									$getskema = $conn->query($sqlgetskema);
																									$sk = $getskema->fetch_assoc();
																									$sqlgetasesordata = "SELECT * FROM `asesor` WHERE `no_ktp`='$_SESSION[namauser]'";
																									$getasesordata = $conn->query($sqlgetasesordata);
																									$asr = $getasesordata->fetch_assoc();
																									echo "<!-- Content Header (Page header) -->
			<section class='content-header'>
				<h1>
			Hasil Penilaian Asesmen (FORM-ASC-01)
					<small>Skema $sk[judul]</small>
				</h1>
				<ol class='breadcrumb'>
					<li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
					<li class='active'>Penilaian Asesmen Asesi oleh Asesor</li>
				</ol>
			</section>";
																									function uploadFoto($file)
																									{
																										$file_max_weight = 20097152; //Ukuran maksimal yg dibolehkan(2Mb)
																										$ok_ext = array('jpg', 'png', 'gif', 'jpeg', 'JPG', 'PNG', 'GIF', 'JPEG'); // ekstensi yang diijinkan
																										$destination = "foto_asesi/"; // tempat buat upload
																										$filename = explode(".", $file['name']);
																										$file_name = $file['name'];
																										$file_name_no_ext = isset($filename[0]) ? $filename[0] : null;
																										$file_extension = $filename[count($filename) - 1];
																										$file_weight = $file['size'];
																										$file_type = $file['type'];
																										// Jika tidak ada error
																										if ($file['error'] == 0) {
																											$dateNow = date_create();
																											$time_stamp = date_format($dateNow, 'U');
																											if (in_array($file_extension, $ok_ext)) :
																												if ($file_weight <= $file_max_weight) :
																													$fileNewName = $time_stamp . md5($file_name_no_ext[0] . microtime()) . "." . $file_extension;
																													$alamatfile = $fileNewName;
																													if (move_uploaded_file($file['tmp_name'], $destination . $fileNewName)) :
																													//echo" File uploaded !";
																													else :
																													//echo "can't upload file.";
																													endif;
																												else :
																												//echo "File too heavy.";
																												endif;
																											else :
																											//echo "File type is not supported.";
																											endif;
																										}
																										return $alamatfile;
																									}
																									if (isset($_REQUEST['simpan'])) {
																										$sqlgetunitkompetensi2 = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
																										$getunitkompetensi2 = $conn->query($sqlgetunitkompetensi2);
																										while ($unk2 = $getunitkompetensi2->fetch_assoc()) {
																											$sqlgetelemen2 = "SELECT * FROM `elemen_kompetensi` WHERE `id_unitkompetensi`='$unk2[id]' ORDER BY `id` ASC";
																											$getelemen2 = $conn->query($sqlgetelemen2);
																											while ($el2 = $getelemen2->fetch_assoc()) {
																												$sqlgetkriteria2 = "SELECT * FROM `kriteria_unjukkerja` WHERE `id_elemenkompetensi`='$el2[id]' ORDER BY `id` ASC";
																												$getkriteria2 = $conn->query($sqlgetkriteria2);
																												while ($kr2 = $getkriteria2->fetch_assoc()) {
																													$id_jawaban0 = 'bukti' . $kr2['id'];
																													$id_jawaban = 'checkbox1' . $kr2['id'];
																													$id_jawabanb = 'checkbox2' . $kr2['id'];
																													$id_jawabanc = 'checkbox3' . $kr2['id'];
																													$id_jawaband = 'checkbox4' . $kr2['id'];
																													$id_jawabane = 'checkboxa' . $kr2['id'];
																													$id_jawabanf = 'checkboxb' . $kr2['id'];
																													$id_jawabang = 'checkboxc' . $kr2['id'];
																													$id_jawabanh = 'optionsRadiosc' . $kr2['id'];
																													$sqlcekjawaban = "SELECT * FROM `asesi_apl02` WHERE `id_asesi`='$_GET[ida]' AND `id_kriteria`='$kr2[id]' AND `id_skemakkni`='$sk[id]'";
																													$cekjawaban = $conn->query($sqlcekjawaban);
																													$jjw = $cekjawaban->num_rows;
																													if ($jjw == 0) {
																														$sqlinputjawaban = "INSERT INTO `asesi_apl02`(`id_asesi`, `id_kriteria`, `id_skemakkni`, `jawaban`, ``, ``) VALUES ('$_GET[ida]','$kr2[id]','$sk[id]','$_POST[$id_jawaban]','$_POST[$id_jawabanb]','$_POST[$id_jawabanc]')";
																														$conn->query($sqlinputjawaban);
																													} else {
																														$sqlinputjawaban = "UPDATE `asesi_apl02` SET `keterangan_bukti`='$_POST[$id_jawaban0]',`verifikasi_asesor1`='$_POST[$id_jawaban]',`verifikasi_asesor2`='$_POST[$id_jawabanb]',`verifikasi_asesor3`='$_POST[$id_jawabanc]',`verifikasi_asesor4`='$_POST[$id_jawaband]',`bukti1`='$_POST[$id_jawabane]',`bukti2`='$_POST[$id_jawabanf]',`bukti3`='$_POST[$id_jawabang]',`keputusan`='$_POST[$id_jawabanh]',`id_asesor`='$asr[id]' WHERE `id_asesi`='$_GET[ida]' AND `id_kriteria`='$kr2[id]' AND `id_skemakkni`='$sk[id]'";
																														$conn->query($sqlinputjawaban);
																													}
																												}
																											}
																										}
																										$sqlhitunghasilK = "SELECT * FROM `asesi_apl02` WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$sk[id]' AND `keputusan`='K'";
																										$sqlhitunghasilBK = "SELECT * FROM `asesi_apl02` WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$sk[id]' AND `keputusan`='BK'";
																										$sqlhitunghasilPL = "SELECT * FROM `asesi_apl02` WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$sk[id]' AND `keputusan`='PL'";
																										$hitunghasilK = $conn->query($sqlhitunghasilK);
																										$hitunghasilBK = $conn->query($sqlhitunghasilBK);
																										$hitunghasilPL = $conn->query($sqlhitunghasilPL);
																										$hasilK = $hitunghasilK->num_rows;
																										$hasilBK = $hitunghasilBK->num_rows;
																										$hasilPL = $hitunghasilPL->num_rows;
																										$jumhasil = $hasilK + $hasilBK + $hasilPL;
																										$persentase = ($hasilK / $jumhasil) * 100;
																										$persenminimal = 100;
																										$persenbelumkompeten = 70;
																										if ($_POST['keputusanfinal'] == 'R') {
																											$sqlkeputusanfinal = "UPDATE `asesi_asesmen` SET `status_asesmen`='K',`keputusan_asesor`='$_POST[keputusanfinal]' WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																										} else {
																											if ($persentase > $persenminimal) {
																												$sqlkeputusanfinal = "UPDATE `asesi_asesmen` SET `status_asesmen`='K',`keputusan_asesor`='R' WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																											} elseif ($persentase < $persenbelumkompeten) {
																												$sqlkeputusanfinal = "UPDATE `asesi_asesmen` SET `status_asesmen`='BK',`keputusan_asesor`='NR' WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																											} else {
																												$sqlkeputusanfinal = "UPDATE `asesi_asesmen` SET `status_asesmen`='TL',`keputusan_asesor`='R' WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																											}
																										}
																										$conn->query($sqlkeputusanfinal);
																										if ($persentase > $persenminimal) {
																											echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Penilaian Asesor berhasil disimpan</h4>
				Terimakasih, Anda telah melakukan <b>Penilaian Asesmen untuk Skema $sk[judul].</b><br>
				Asesi dinyatakan <b>$persentase % KOMPETEN</b></div>";
																										} else {
																											echo "<div class='alert alert-warning alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-warning'></i> Penilaian Asesor berhasil disimpan</h4>
				Terimakasih, Anda telah melakukan <b>Penilaian Asesmen untuk Skema $sk[judul].</b><br>
				Asesi dinyatakan $persentase % KOMPETEN <b>(BELUM KOMPETEN)</b></div>";
																										}
																									}
																									echo "<!-- Main content -->
			<section class='content'>
				<div class='row'>
					<div class='col-xs-12'>
						<div class='box'>
							<div class='box-body'>
								<!-- form start -->
								<div class='box-body'>
			<h2>Formulir Penilaian Asesmen Asesi Oleh Asesor</h2>
			<h2 class='text-green'>Nama Asesi : $rowAgen[nama]</h2>
							<form role='form' method='POST' enctype='multipart/form-data'>
			<p><b>Penjelasan untuk Asesor</b></p>
			<p>
				<ol>
					<li>Asesor mengorganisasikan pelaksanaan asesmen berdasarkan metoda dan instrumen/sumber-sumber asesmen seperti yang tercantum dalam perencanaan asesmen.</li>
					<li>Asesor melaksanakan kegiatan pengumpulan bukti serta mendokumentasikan seluruh bukti pendukung yang dapat ditunjukkan oleh Asesi sesuai dengan kriteria unjuk kerja yang dipersyaratkan.</li>
					<li>Asesor membuat keputusan apakah Asesi sudah <b>Kompeten (K)</b>,  <b>Belum kompeten (BK)</b> atau <b>Asesmen Lanjut (PL)</b>, untuk setiap kriteria unjuk kerja berdasarkan bukti-bukti.</li>
					<li>Asesor memberikan umpan balik kepada Asesi mengenai pencapaian unjuk kerja dan Asesi juga diminta untuk memberikan umpan balik terhadap proses asesmen yang dilaksanakan (kuesioner).</li>
					<li>Asesor dan Asesi bersama-sama menandatangani pelaksanaan asesmen.</li>
					<li>Beri tanda ( <input type='checkbox' checked> ) pada opsi/pilihan sesuai kolom yang dipilih.</li>
				</ol>
			</p>";
																									$sqlgetunitkompetensi = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
																									$getunitkompetensi = $conn->query($sqlgetunitkompetensi);
																									$no = 1;
																									while ($unk = $getunitkompetensi->fetch_assoc()) {
																										echo "<div class='box box-solid'>";
																										echo "<div class='box-header with-border'>
						<h3 class='box-title text-green'><b>Unit Kompetensi : $no. $unk[judul]</b></h3>
					</div>
					<div class='box-body'><div class='col-md-12'>";
																										$no2 = 1;
																										$sqlgetelemen = "SELECT * FROM `elemen_kompetensi` WHERE `id_unitkompetensi`='$unk[id]' ORDER BY `id` ASC";
																										$getelemen = $conn->query($sqlgetelemen);
																										while ($el = $getelemen->fetch_assoc()) {
																											$no3 = 1;
																											echo "<label>Elemen Kompetensi : $no.$no2. $el[elemen_kompetensi]</label>";
																											echo "<div class='form-group'>
									<label>Kriteria Unjuk Kerja :</label>";
																											$sqlgetkriteria = "SELECT * FROM `kriteria_unjukkerja` WHERE `id_elemenkompetensi`='$el[id]' ORDER BY `id` ASC";
																											$getkriteria = $conn->query($sqlgetkriteria);
																											while ($kr = $getkriteria->fetch_assoc()) {
																												$sqlcekjawaban0 = "SELECT * FROM `asesi_apl02` WHERE `id_asesi`='$_GET[ida]' AND `id_kriteria`='$kr[id]' AND `id_skemakkni`='$sk[id]'";
																												$cekjawaban0 = $conn->query($sqlcekjawaban0);
																												$jjw0 = $cekjawaban0->fetch_assoc();
																												$numjjw0 = $cekjawaban0->num_rows;
																												if ($numjjw0 == 0) {
																													$kriteriatampil = str_replace("Anda", $rowAgen['nama'], $kr[kriteria]);
																													$kriteriatampil2 = str_replace("Apakah ", "", $kriteriatampil);
																													$kriteriatampil3 = str_replace("?", "", $kriteriatampil2);
																													echo "<div class='col-md-12'><p>$no.$no2.$no3. $kriteriatampil3</p></div>
											<div class='col-md-12'>
												<span class='text-red'><b>Belum menjawab</b></span>
											</div>";
																												} else {
																													$kriteriatampil = str_replace("Anda", $rowAgen['nama'], $kr[kriteria]);
																													$kriteriatampil2 = str_replace("Apakah ", "", $kriteriatampil);
																													$kriteriatampil3 = str_replace("?", "", $kriteriatampil2);
																													echo "<div class='col-md-12'><p>$no.$no2.$no3. $kriteriatampil3</p></div>";
																													if ($jjw0['jawaban'] == 1) {
																														echo "<div class='col-md-12'><div class='col-md-2'>
													<a class='btn btn-success btn-xs'><span class='glyphicon glyphicon-ok' aria-hidden='true' title='Ya'></span></a>&nbsp;&nbsp;Ya &nbsp;&nbsp;&nbsp;
												</div>
												<div class='col-md-4'>
													<div class='form-group'><label>
													Bukti-bukti Kompetensi :
													</label><br>";
																														echo "<textarea name='bukti$kr[id]' id='bukti$kr[id]' class='form-control' placeholder='isikan bukti-bukti kompetensi'>$jjw0[keterangan_bukti]</textarea>";
																														echo "</div>
												</div>
												<div class='col-md-4'>
													<div class='form-group'><label>
													Penilaian Asesor :
													</label><br>
													<label>";
																														echo "<input type='checkbox' class='flat-red' name='checkbox1$kr[id]' id='options1$kr[id]' value='V'";
																														if ($jjw0['verifikasi_asesor1'] == "V") {
																															echo "checked";
																														}
																														echo ">";
																														echo " Valid &nbsp;&nbsp;&nbsp;
													</label>
													<label>";
																														echo "<input type='checkbox' class='flat-red' name='checkbox2$kr[id]' id='options2$kr[id]' value='A'";
																														if ($jjw0['verifikasi_asesor2'] == "A") {
																															echo "checked";
																														}
																														echo ">";
																														echo " Autentik &nbsp;&nbsp;&nbsp;
													</label>
													<label>";
																														echo "<input type='checkbox' class='flat-red' name='checkbox3$kr[id]' id='options3$kr[id]' value='T'";
																														if ($jjw0['verifikasi_asesor3'] == "T") {
																															echo "checked";
																														}
																														echo ">";
																														echo " Terkini &nbsp;&nbsp;&nbsp;
													</label>
													<label>";
																														echo "<input type='checkbox' class='flat-red' name='checkbox4$kr[id]' id='options4$kr[id]' value='M'";
																														if ($jjw0['verifikasi_asesor4'] == "M") {
																															echo "checked";
																														}
																														echo ">";
																														echo " Memadai &nbsp;&nbsp;&nbsp;
													</label></div>
												</div></div>
												<div class='col-md-12'><div class='col-md-6'>
													<div class='col-md-12 bg-gray'><div class='form-group'><label>
													Bukti-bukti :
													</label><br>
													<label>";
																														echo "<input type='checkbox' class='flat-red' name='checkboxa$kr[id]' id='options1b$kr[id]' value='L'";
																														if ($jjw0['bukti1'] == "L") {
																															echo "checked";
																														}
																														echo ">";
																														echo " Bukti Langsung &nbsp;&nbsp;&nbsp;
													</label>
													<label>";
																														echo "<input type='checkbox' class='flat-red' name='checkboxb$kr[id]' id='options2b$kr[id]' value='TL'";
																														if ($jjw0['bukti2'] == "TL") {
																															echo "checked";
																														}
																														echo ">";
																														echo " Bukti Tidak Langsung &nbsp;&nbsp;&nbsp;
													</label>
													<label>";
																														echo "<input type='checkbox' class='flat-red' name='checkboxc$kr[id]' id='options3b$kr[id]' value='T'";
																														if ($jjw0['bukti3'] == "T") {
																															echo "checked";
																														}
																														echo ">";
																														echo " Bukti Tambahan
													</label>
													</div></div>
												</div>
												<div class='col-md-6'>
													<div class='col-md-12 bg-green'><div class='form-group'><label>
													Keputusan :
													</label><br>
													<label>";
																														echo "<input type='radio' class='minimal' name='optionsRadiosc$kr[id]' id='options1c$kr[id]' value='K'";
																														if ($jjw0['keputusan'] == "K") {
																															echo "checked";
																														}
																														echo ">";
																														echo " Kompeten &nbsp;&nbsp;&nbsp;
													</label>
													<label>";
																														echo "<input type='radio' class='minimal' name='optionsRadiosc$kr[id]' id='options2c$kr[id]' value='BK'";
																														if ($jjw0['keputusan'] == "BK") {
																															echo "checked";
																														}
																														echo ">";
																														echo " Belum Kompeten &nbsp;&nbsp;&nbsp;
													</label>
													<label>";
																														echo "<input type='radio' class='minimal' name='optionsRadiosc$kr[id]' id='options3c$kr[id]' value='PL'";
																														if ($jjw0['keputusan'] == "PL") {
																															echo "checked";
																														}
																														echo ">";
																														echo " Asesmen Lanjut
													</label>
													</div></div>
												</div></div>";
																													}
																													if ($jjw0['jawaban'] == 0) {
																														echo "<div class='col-md-12'>
													<a class='btn btn-danger btn-xs'><span class='glyphicon glyphicon-times' aria-hidden='true' title='Tidak'></span></a>&nbsp;&nbsp;Tidak &nbsp;&nbsp;&nbsp;
												</div>";
																													}
																												}
																												$no3++;
																											}
																											echo "</div>";
																											$no2++;
																										}
																										echo "<div></div><!-- /.box-body-->
				</div></div><!-- /.box box-solid-->";
																										$no++;
																									}
																									echo "</div>";
																									$sqlgetkeputusan = "SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																									$getkeputusan = $conn->query($sqlgetkeputusan);
																									$getk = $getkeputusan->fetch_assoc();
																									echo "<div class='box-footer bg-black'>
			<div class='col-md-12'><label class='text-yellow'>
							Asesi telah diberikan umpan balik/masukan dan diinformasikan hasil asesmen/uji kompetensi serta penjelasan terhadap keputusan yang dibuat. Berdasarkan hasil asesmen tersebut, peserta : 
						</label><br>
						<label>";
																									echo "<input type='radio' class='minimal' name='keputusanfinal' id='keputusanfinal1' value='R'";
																									if ($getk['keputusan_asesor'] == "R") {
																										echo "checked";
																									}
																									echo ">";
																									echo " Direkomendasikan &nbsp;&nbsp;&nbsp;
						</label>
						<label>";
																									echo "<input type='radio' class='minimal' name='keputusanfinal' id='keputusanfinal2' value='NR'";
																									if ($getk['keputusan_asesor'] == "NR") {
																										echo "checked";
																									}
																									echo ">";
																									echo " Tidak Direkomendasikan &nbsp;&nbsp;&nbsp;
						</label><br>
						<label class='text-yellow'>Untuk mendapatkan pengakuan terhadap unit kompetensi yang diujikan.</label>
			</div>
									<div class='col-md-4 col-sm-12 col-xs-12'>
					<a class='btn btn-danger form-control' id=reset-validate-form href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a>
			</div>
			<div class='col-md-4 col-sm-12 col-xs-12'>
					<a href='form-asc-01.php?ida=$_GET[ida]&idj=$_GET[idj]' class='btn btn-primary form-control'>Unduh Form Hasil</a>
			</div>
			<div class='col-md-4 col-sm-12 col-xs-12'>
					<button type='submit' class='btn btn-success form-control' name='simpan'>Simpan Jawaban</button>
			</div>
								</div>
							</form>			
							</div>
							<!-- /.box-body -->
						</div>
						<!-- /.box -->
					</div>
					<!-- /.col -->
				</div>
				<!-- /.row -->
			</section>
			<!-- /.content -->";
																								}
																								// Bagian Input Hasil Asesmen FORM-MAK-02 Asesor
																								elseif ($_GET['module'] == 'form-mak-04') {
																									$sqllogin = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_GET[ida]'";
																									$login = $conn->query($sqllogin);
																									$ketemu = $login->num_rows;
																									$rowAgen = $login->fetch_assoc();
																									$sqlgetjadwal = "SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
																									$getjadwal = $conn->query($sqlgetjadwal);
																									$jd = $getjadwal->fetch_assoc();
																									$sqlgetskema = "SELECT * FROM `skema_kkni` WHERE `id`='$jd[id_skemakkni]'";
																									$getskema = $conn->query($sqlgetskema);
																									$sk = $getskema->fetch_assoc();
																									$sqlgetasesordata = "SELECT * FROM `asesor` WHERE `no_ktp`='$_SESSION[namauser]'";
																									$getasesordata = $conn->query($sqlgetasesordata);
																									$asr = $getasesordata->fetch_assoc();
																									echo "<!-- Content Header (Page header) -->
			<section class='content-header'>
				<h1>
			Input FORM-MAK-04
					<small>Skema $sk[judul]</small>
				</h1>
				<ol class='breadcrumb'>
					<li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
					<li class='active'>Penilaian Asesmen Asesi oleh Asesor</li>
				</ol>
			</section>";
																									function uploadFoto($file)
																									{
																										$file_max_weight = 20097152; //Ukuran maksimal yg dibolehkan(2Mb)
																										$ok_ext = array('jpg', 'png', 'gif', 'jpeg', 'JPG', 'PNG', 'GIF', 'JPEG'); // ekstensi yang diijinkan
																										$destination = "foto_asesi/"; // tempat buat upload
																										$filename = explode(".", $file['name']);
																										$file_name = $file['name'];
																										$file_name_no_ext = isset($filename[0]) ? $filename[0] : null;
																										$file_extension = $filename[count($filename) - 1];
																										$file_weight = $file['size'];
																										$file_type = $file['type'];
																										// Jika tidak ada error
																										if ($file['error'] == 0) {
																											$dateNow = date_create();
																											$time_stamp = date_format($dateNow, 'U');
																											if (in_array($file_extension, $ok_ext)) :
																												if ($file_weight <= $file_max_weight) :
																													$fileNewName = $time_stamp . md5($file_name_no_ext[0] . microtime()) . "." . $file_extension;
																													$alamatfile = $fileNewName;
																													if (move_uploaded_file($file['tmp_name'], $destination . $fileNewName)) :
																													//echo" File uploaded !";
																													else :
																													//echo "can't upload file.";
																													endif;
																												else :
																												//echo "File too heavy.";
																												endif;
																											else :
																											//echo "File type is not supported.";
																											endif;
																										}
																										return $alamatfile;
																									}
																									if (isset($_REQUEST['simpan'])) {
																										$sqlgetunitkompetensi2 = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
																										$getunitkompetensi2 = $conn->query($sqlgetunitkompetensi2);
																										while ($unk2 = $getunitkompetensi2->fetch_assoc()) {
																											$sqlcekjawaban01 = "SELECT * FROM `asesi_mak02` WHERE `id_jadwal`='$_GET[idj]' AND `id_asesi`='$_GET[ida]' AND `id_skema`='$sk[id]' AND `id_unitkompetensi`='$unk2[id]'";
																											$cekjawaban01 = $conn->query($sqlcekjawaban01);
																											$jjw01 = $cekjawaban01->num_rows;
																											$id_jawabane = 'checkboxa1' . $jd['id'] . "-" . $jd['id_skemakkni'] . "-" . $unk2['id'] . "-" . $_GET['ida'];
																											$id_jawabanf = 'checkboxb1' . $jd['id'] . "-" . $jd['id_skemakkni'] . "-" . $unk2['id'] . "-" . $_GET['ida'];
																											$id_jawabang = 'checkboxc1' . $jd['id'] . "-" . $jd['id_skemakkni'] . "-" . $unk2['id'] . "-" . $_GET['ida'];
																											$id_jawabanh = 'checkboxa2' . $jd['id'] . "-" . $jd['id_skemakkni'] . "-" . $unk2['id'] . "-" . $_GET['ida'];
																											$id_jawabani = 'checkboxb2' . $jd['id'] . "-" . $jd['id_skemakkni'] . "-" . $unk2['id'] . "-" . $_GET['ida'];
																											$id_jawabanj = 'checkboxc2' . $jd['id'] . "-" . $jd['id_skemakkni'] . "-" . $unk2['id'] . "-" . $_GET['ida'];
																											$id_jawabank = 'checkboxa3' . $jd['id'] . "-" . $jd['id_skemakkni'] . "-" . $unk2['id'] . "-" . $_GET['ida'];
																											$id_jawabanl = 'checkboxb3' . $jd['id'] . "-" . $jd['id_skemakkni'] . "-" . $unk2['id'] . "-" . $_GET['ida'];
																											$id_jawabanm = 'checkboxc3' . $jd['id'] . "-" . $jd['id_skemakkni'] . "-" . $unk2['id'] . "-" . $_GET['ida'];
																											$id_jawabann1 = 'keputusan1' . $jd['id'] . "-" . $jd['id_skemakkni'] . "-" . $unk2['id'] . "-" . $_GET['ida'];
																											$id_jawabano1 = 'pencapaian1' . $jd['id'] . "-" . $jd['id_skemakkni'] . "-" . $unk2['id'] . "-" . $_GET['ida'];
																											$id_jawabann2 = 'keputusan2' . $jd['id'] . "-" . $jd['id_skemakkni'] . "-" . $unk2['id'] . "-" . $_GET['ida'];
																											$id_jawabano2 = 'pencapaian2' . $jd['id'] . "-" . $jd['id_skemakkni'] . "-" . $unk2['id'] . "-" . $_GET['ida'];
																											$id_jawabann3 = 'keputusan3' . $jd['id'] . "-" . $jd['id_skemakkni'] . "-" . $unk2['id'] . "-" . $_GET['ida'];
																											$id_jawabano3 = 'pencapaian3' . $jd['id'] . "-" . $jd['id_skemakkni'] . "-" . $unk2['id'] . "-" . $_GET['ida'];
																											if ($jjw01 == 0) {
																												$sqlinputjawaban01 = "INSERT INTO `asesi_mak02`(`id_jadwal`, `id_asesi`, `id_skema`, `id_unitkompetensi`, `bukti1-1`, `bukti1-2`, `bukti1-3`, `bukti2-1`, `bukti2-2`, `bukti2-3`, `bukti3-1`, `bukti3-2`, `bukti3-3`, `pencapaian1`,`pencapaian2`, `pencapaian3`, `keputusan1`, `keputusan2`, `keputusan3`, `id_asesor`) VALUES ('$_GET[idj]','$_GET[ida]','$sk[id]','$unk2[id]','$_POST[$id_jawabane]','$_POST[$id_jawabanf]','$_POST[$id_jawabang]','$_POST[$id_jawabanh]','$_POST[$id_jawabani]','$_POST[$id_jawabanj]','$_POST[$id_jawabank]','$_POST[$id_jawabanl]','$_POST[$id_jawabanm]','$_POST[$id_jawabano1]','$_POST[$id_jawabano2]','$_POST[$id_jawabano3]','$_POST[$id_jawabann1]','$_POST[$id_jawabann2]','$_POST[$id_jawabann3]','$asr[id]')";
																												$conn->query($sqlinputjawaban01);
																											} else {
																												$sqlinputjawaban01 = "UPDATE `asesi_mak02` SET `bukti1-1`='$_POST[$id_jawabane]',`bukti1-2`='$_POST[$id_jawabanf]',`bukti1-3`='$_POST[$id_jawabang]',`bukti2-1`='$_POST[$id_jawabanh]',`bukti2-2`='$_POST[$id_jawabani]',`bukti2-3`='$_POST[$id_jawabanj]',`bukti3-1`='$_POST[$id_jawabank]',`bukti3-2`='$_POST[$id_jawabanl]',`bukti3-3`='$_POST[$id_jawabanm]',`pencapaian1`='$_POST[$id_jawabano1]',`pencapaian2`='$_POST[$id_jawabano2]',`pencapaian3`='$_POST[$id_jawabano3]',`keputusan1`='$_POST[$id_jawabann1]',`keputusan2`='$_POST[$id_jawabann2]',`keputusan3`='$_POST[$id_jawabann3]',`id_asesor`='$asr[id]' WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_skema`='$sk[id]' AND `id_unitkompetensi`='$unk2[id]'";
																												$conn->query($sqlinputjawaban01);
																											}
																											$sqlgetelemen2 = "SELECT * FROM `elemen_kompetensi` WHERE `id_unitkompetensi`='$unk2[id]' ORDER BY `id` ASC";
																											$getelemen2 = $conn->query($sqlgetelemen2);
																											while ($el2 = $getelemen2->fetch_assoc()) {
																												$sqlgetkriteria2 = "SELECT * FROM `kriteria_unjukkerja` WHERE `id_elemenkompetensi`='$el2[id]' ORDER BY `id` ASC";
																												$getkriteria2 = $conn->query($sqlgetkriteria2);
																												while ($kr2 = $getkriteria2->fetch_assoc()) {
																													$id_jawaban0 = 'bukti' . $kr2['id'];
																													$id_jawaban = 'checkbox1' . $kr2['id'];
																													$id_jawabanb = 'checkbox2' . $kr2['id'];
																													$id_jawabanc = 'checkbox3' . $kr2['id'];
																													$id_jawaband = 'checkbox4' . $kr2['id'];
																													$id_jawabanp = 'pencapaian' . $kr2['id'];
																													$sqlcekjawaban = "SELECT * FROM `asesi_apl02` WHERE `id_asesi`='$_GET[ida]' AND `id_kriteria`='$kr2[id]' AND `id_skemakkni`='$sk[id]' AND `id_jadwal`='$_GET[idj]'";
																													$cekjawaban = $conn->query($sqlcekjawaban);
																													$jjw = $cekjawaban->num_rows;
																													if ($jjw == 0) {
																														$sqlinputjawaban = "INSERT INTO `asesi_apl02`(`id_asesi`, `id_kriteria`, `id_skemakkni`, `jawaban`, ``, ``) VALUES ('$_GET[ida]','$kr2[id]','$sk[id]','$_POST[$id_jawaban]','$_POST[$id_jawabanb]','$_POST[$id_jawabanc]')";
																														$conn->query($sqlinputjawaban);
																													} else {
																														$sqlinputjawaban = "UPDATE `asesi_apl02` SET `keterangan_bukti`='$_POST[$id_jawaban0]',`verifikasi_asesor1`='$_POST[$id_jawaban]',`verifikasi_asesor2`='$_POST[$id_jawabanb]',`verifikasi_asesor3`='$_POST[$id_jawabanc]',`verifikasi_asesor4`='$_POST[$id_jawaband]',`bukti1-1`='$_POST[$id_jawabane]',`bukti1-2`='$_POST[$id_jawabanf]',`bukti1-3`='$_POST[$id_jawabang]',`bukti2-1`='$_POST[$id_jawabanh]',`bukti2-2`='$_POST[$id_jawabani]',`bukti2-3`='$_POST[$id_jawabanj]',`bukti3-1`='$_POST[$id_jawabank]',`bukti3-2`='$_POST[$id_jawabanl]',`bukti3-3`='$_POST[$id_jawabanm]',`keputusan`='$_POST[$id_jawabann]',`pencapaian`='$_POST[$id_jawabanp]',`id_asesor`='$asr[id]' WHERE `id_asesi`='$_GET[ida]' AND `id_kriteria`='$kr2[id]' AND `id_skemakkni`='$sk[id]' AND `id_jadwal`='$_GET[idj]'";
																														$conn->query($sqlinputjawaban);
																													}
																												}
																											}
																										}
																										$sqlhitunghasilK1 = "SELECT * FROM `asesi_mak02` WHERE `id_asesi`='$_GET[ida]' AND `id_skema`='$sk[id]' AND `keputusan1`='K'";
																										$sqlhitunghasilBK1 = "SELECT * FROM `asesi_mak02` WHERE `id_asesi`='$_GET[ida]' AND `id_skema`='$sk[id]' AND `keputusan1`='BK'";
																										$sqlhitunghasilPL1 = "SELECT * FROM `asesi_mak02` WHERE `id_asesi`='$_GET[ida]' AND `id_skema`='$sk[id]' AND `keputusan1`='PL'";
																										$sqlhitunghasilK2 = "SELECT * FROM `asesi_mak02` WHERE `id_asesi`='$_GET[ida]' AND `id_skema`='$sk[id]' AND `keputusan2`='K'";
																										$sqlhitunghasilBK2 = "SELECT * FROM `asesi_mak02` WHERE `id_asesi`='$_GET[ida]' AND `id_skema`='$sk[id]' AND `keputusan2`='BK'";
																										$sqlhitunghasilPL2 = "SELECT * FROM `asesi_mak02` WHERE `id_asesi`='$_GET[ida]' AND `id_skema`='$sk[id]' AND `keputusan2`='PL'";
																										$sqlhitunghasilK3 = "SELECT * FROM `asesi_mak02` WHERE `id_asesi`='$_GET[ida]' AND `id_skema`='$sk[id]' AND `keputusan3`='K'";
																										$sqlhitunghasilBK3 = "SELECT * FROM `asesi_mak02` WHERE `id_asesi`='$_GET[ida]' AND `id_skema`='$sk[id]' AND `keputusan3`='BK'";
																										$sqlhitunghasilPL3 = "SELECT * FROM `asesi_mak02` WHERE `id_asesi`='$_GET[ida]' AND `id_skema`='$sk[id]' AND `keputusan3`='PL'";
																										$hitunghasilK1 = $conn->query($sqlhitunghasilK1);
																										$hitunghasilBK1 = $conn->query($sqlhitunghasilBK1);
																										$hitunghasilPL1 = $conn->query($sqlhitunghasilPL1);
																										$hitunghasilK2 = $conn->query($sqlhitunghasilK2);
																										$hitunghasilBK2 = $conn->query($sqlhitunghasilBK2);
																										$hitunghasilPL2 = $conn->query($sqlhitunghasilPL2);
																										$hitunghasilK3 = $conn->query($sqlhitunghasilK3);
																										$hitunghasilBK3 = $conn->query($sqlhitunghasilBK3);
																										$hitunghasilPL3 = $conn->query($sqlhitunghasilPL3);
																										$hasilK1 = $hitunghasilK1->num_rows;
																										$hasilBK1 = $hitunghasilBK1->num_rows;
																										$hasilPL1 = $hitunghasilPL1->num_rows;
																										$hasilK2 = $hitunghasilK2->num_rows;
																										$hasilBK2 = $hitunghasilBK2->num_rows;
																										$hasilPL2 = $hitunghasilPL2->num_rows;
																										$hasilK3 = $hitunghasilK3->num_rows;
																										$hasilBK3 = $hitunghasilBK3->num_rows;
																										$hasilPL3 = $hitunghasilPL3->num_rows;
																										$hasilK = $hasilK1 + $hasilK2 + $hasilK3;
																										$hasilBK = $hasilBK1 + $hasilBK2 + $hasilBK3;
																										$hasilPL = $hasilPL1 + $hasilPL2 + $hasilPL3;
																										$jumhasil = $hasilK + $hasilBK + $hasilPL;
																										$persentase = ($hasilK / $jumhasil) * 100;
																										$persenminimal = 99.99;
																										$persenbelumkompeten = 70;
																										if ($_POST['keputusanfinal'] == 'R') {
																											$sqlkeputusanfinal = "UPDATE `asesi_asesmen` SET `status_asesmen`='K',`keputusan_asesor`='$_POST[keputusanfinal]' WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																										} else {
																											if ($persentase > $persenminimal) {
																												$sqlkeputusanfinal = "UPDATE `asesi_asesmen` SET `status_asesmen`='K',`keputusan_asesor`='R' WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																											} elseif ($persentase < $persenbelumkompeten) {
																												$sqlkeputusanfinal = "UPDATE `asesi_asesmen` SET `status_asesmen`='BK',`keputusan_asesor`='NR' WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																											} else {
																												$sqlkeputusanfinal = "UPDATE `asesi_asesmen` SET `status_asesmen`='TL',`keputusan_asesor`='R' WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																											}
																										}
																										$conn->query($sqlkeputusanfinal);
																										if ($persentase > $persenminimal) {
																											echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Penilaian Asesor berhasil disimpan</h4>
				Terimakasih, Anda telah melakukan <b>Penilaian Asesmen untuk Skema $sk[judul].</b><br>
				Asesi dinyatakan <b>$persentase % KOMPETEN</b><br>
				<a class='btn btn-warning form-control' href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a></div>";
																										} else {
																											echo "<div class='alert alert-warning alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-warning'></i> Penilaian Asesor berhasil disimpan</h4>
				Terimakasih, Anda telah melakukan <b>Penilaian Asesmen untuk Skema $sk[judul].</b><br>
				Asesi dinyatakan $persentase % KOMPETEN <b>(BELUM KOMPETEN)</b><br>
				<a class='btn btn-danger form-control' href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a></div>";
																										}
																									}
																									echo "<!-- Main content -->
			<section class='content'>
				<div class='row'>
					<div class='col-xs-12'>
						<div class='box'>
							<div class='box-body'>
								<!-- form start -->
								<div class='box-body'>
			<h2>Formulir Hasil Keputusan Asesmen dan Umpan Balik</h2>
			<h2 class='text-green'>Nama Asesi : $rowAgen[nama]</h2>
							<form role='form' method='POST' enctype='multipart/form-data'>
			<p><b>Penjelasan untuk Asesor</b></p>
			<p>
				<ol>
					<li>Asesor mengorganisasikan pelaksanaan asesmen berdasarkan metoda dan instrumen/sumber-sumber asesmen seperti yang tercantum dalam perencanaan asesmen.</li>
					<li>Asesor melaksanakan kegiatan pengumpulan bukti serta mendokumentasikan seluruh bukti pendukung yang dapat ditunjukkan oleh Asesi sesuai dengan kriteria unjuk kerja yang dipersyaratkan.</li>
					<li>Asesor membuat keputusan apakah Asesi sudah <b>Kompeten (K)</b>,  <b>Belum kompeten (BK)</b> atau <b>Asesmen Lanjut (PL)</b>, untuk setiap kriteria unjuk kerja berdasarkan bukti-bukti.</li>
					<li>Asesor memberikan umpan balik kepada Asesi mengenai pencapaian unjuk kerja dan Asesi juga diminta untuk memberikan umpan balik terhadap proses asesmen yang dilaksanakan (kuesioner).</li>
					<li>Asesor dan Asesi bersama-sama menandatangani pelaksanaan asesmen.</li>
					<li>Beri tanda ( <input type='checkbox' checked> ) pada opsi/pilihan sesuai kolom yang dipilih.</li>
				</ol>
			</p>";
																									$sqlgetunitkompetensi = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
																									$getunitkompetensi = $conn->query($sqlgetunitkompetensi);
																									$no = 1;
																									while ($unk = $getunitkompetensi->fetch_assoc()) {
																										$sqlcekukom = "SELECT * FROM `asesmen_unitkompetensi` WHERE `id_skemakkni`='$sk[id]' AND `id_asesi`='$_GET[ida]' AND `id_unitkompetensi`='$unk[id]'";
																										$cekukom = $conn->query($sqlcekukom);
																										$ukom = $cekukom->num_rows;
																										if ($ukom > 0) {
																											echo "<div class='box box-solid'>";
																											echo "<div class='box-header with-border'>
						<h3 class='box-title text-green'><b>Unit Kompetensi : $no. $unk[judul]</b></h3>
					</div>
					<div class='box-body'><div class='col-md-12'>";
																											$no2 = 1;
																											$sqlgetelemen = "SELECT * FROM `elemen_kompetensi` WHERE `id_unitkompetensi`='$unk[id]' ORDER BY `id` ASC";
																											$getelemen = $conn->query($sqlgetelemen);
																											while ($el = $getelemen->fetch_assoc()) {
																												$no3 = 1;
																												echo "<label>Elemen Kompetensi : $no.$no2. $el[elemen_kompetensi]</label>";
																												echo "<div class='form-group'>
									<label>Kriteria Unjuk Kerja :</label>";
																												$sqlgetkriteria = "SELECT * FROM `kriteria_unjukkerja` WHERE `id_elemenkompetensi`='$el[id]' ORDER BY `id` ASC";
																												$getkriteria = $conn->query($sqlgetkriteria);
																												while ($kr = $getkriteria->fetch_assoc()) {
																													$sqlcekjawaban0 = "SELECT * FROM `asesi_apl02` WHERE `id_asesi`='$_GET[ida]' AND `id_kriteria`='$kr[id]' AND `id_skemakkni`='$sk[id]'";
																													$cekjawaban0 = $conn->query($sqlcekjawaban0);
																													$jjw0 = $cekjawaban0->fetch_assoc();
																													$numjjw0 = $cekjawaban0->num_rows;
																													if ($numjjw0 == 0) {
																														$kriteriatampil = str_replace("Anda", $rowAgen['nama'], $kr[kriteria]);
																														$kriteriatampil2 = str_replace("Apakah ", "", $kriteriatampil);
																														$kriteriatampil3 = str_replace("?", "", $kriteriatampil2);
																														echo "<div class='col-md-12'><p>$no.$no2.$no3. $kriteriatampil3</p></div>
											<div class='col-md-12'>
												<span class='text-red'><b>Belum menjawab</b></span>
											</div>";
																													} else {
																														$kriteriatampil = str_replace("Anda", $rowAgen['nama'], $kr[kriteria]);
																														$kriteriatampil2 = str_replace("Apakah ", "", $kriteriatampil);
																														$kriteriatampil3 = str_replace("?", "", $kriteriatampil2);
																														echo "<div class='col-md-12'><p>$no.$no2.$no3. $kriteriatampil3</p></div>";
																														if ($jjw0['jawaban'] == 1) {
																															echo "<div class='col-md-12'>
												<div class='col-md-2'>
													<a class='btn btn-success btn-xs'><span class='glyphicon glyphicon-ok' aria-hidden='true' title='Ya'></span></a>&nbsp;&nbsp;Ya &nbsp;&nbsp;&nbsp;
												</div>
												<div class='col-md-4'>
													<div class='form-group'><label>
													Bukti-bukti Kompetensi :
													</label><br>";
																															$getfilebukti = "SELECT * FROM `asesi_apl02doc` WHERE `id_asesi`='$_GET[ida]' AND `id_kriteria`='$kr[id]' AND `id_elemenkompetensi`='$el[id]' AND `id_skemakkni`='$sk[id]' AND `id_jadwal`='$_GET[idj]'";
																															$filebukti = $conn->query($getfilebukti);
																															$nodokumen = 1;
																															while ($fbk = $filebukti->fetch_assoc()) {
																																//$fbk[file];
																																echo "Dokumen $no.$no2.$no3.$nodokumen&nbsp;<a href='#myModal" . $fbk['id'] . "' class='btn btn-success btn-xs' data-toggle='modal' data-id='" . $fbk['id'] . "'><span class='glyphicon glyphicon-zoom-in' aria-hidden='true' title='Lihat/Unduh Dokumen'></span></a><br>";
																																echo "<script>
															$(function(){
																		$(document).on('click','.edit-record',function(e){
																			e.preventDefault();
																			$('#myModal" . $fbk['id'] . "').modal('show');
																		});
																});
														</script>
														<!-- Modal -->
															<div class='modal fade' id='myModal" . $fbk['id'] . "' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
																<div class='modal-dialog'>
																	<div class='modal-content'>
																		<div class='modal-header'>
																			<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Tutup</span></button>
																			<h4 class='modal-title' id='myModalLabel'>Dokumen Bukti " . $kr['kriteria'] . "</h4>
																		</div>
																		<div class='modal-body'><embed src='../foto_apl02/$fbk[file]' width='100%' height='100%'/>
																		</div>
																		<div class='modal-footer'>
																			<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>
																		</div>
																	</div>
																</div>
															</div>
														<!-- Modal End -->";
																																$nodokumen++;
																															}
																															echo "<br><textarea name='bukti$kr[id]' id='bukti$kr[id]' class='form-control' placeholder='isikan bukti-bukti kompetensi'>$jjw0[keterangan_bukti]</textarea>";
																															echo "</div>
												</div>
												<div class='col-md-3'>
													<div class='form-group'><label>
													Penilaian Asesor :
													</label><br>
													<label>";
																															echo "<input type='checkbox' class='flat-red' name='checkbox1$kr[id]' id='options1$kr[id]' value='V'";
																															if ($jjw0['verifikasi_asesor1'] == "V") {
																																echo "checked";
																															}
																															echo ">";
																															echo " Valid &nbsp;&nbsp;&nbsp;
													</label>
													<label>";
																															echo "<input type='checkbox' class='flat-red' name='checkbox2$kr[id]' id='options2$kr[id]' value='A'";
																															if ($jjw0['verifikasi_asesor2'] == "A") {
																																echo "checked";
																															}
																															echo ">";
																															echo " Autentik &nbsp;&nbsp;&nbsp;
													</label>
													<label>";
																															echo "<input type='checkbox' class='flat-red' name='checkbox3$kr[id]' id='options3$kr[id]' value='T'";
																															if ($jjw0['verifikasi_asesor3'] == "T") {
																																echo "checked";
																															}
																															echo ">";
																															echo " Terkini &nbsp;&nbsp;&nbsp;
													</label>
													<label>";
																															echo "<input type='checkbox' class='flat-red' name='checkbox4$kr[id]' id='options4$kr[id]' value='M'";
																															if ($jjw0['verifikasi_asesor4'] == "M") {
																																echo "checked";
																															}
																															echo ">";
																															echo " Memadai &nbsp;&nbsp;&nbsp;
													</label></div>
												</div>";
																															switch ($jjw0['pencapaian']) {
																																case "Y":
																																	echo "<div class='col-md-3 bg-green'>";
																																	break;
																																case "N":
																																	echo "<div class='col-md-3 bg-red'>";
																																	break;
																																default:
																																	echo "<div class='col-md-3'>";
																																	break;
																															}
																															echo "<div class='form-group'><label>
													Pencapaian :
													</label><br>
													<label>";
																															echo "<input type='radio' class='minimal' name='pencapaian$kr[id]' id='options1c$kr[id]' value='Y'";
																															if ($jjw0['pencapaian'] == "Y") {
																																echo "checked";
																															}
																															echo ">";
																															echo " Ya &nbsp;&nbsp;&nbsp;
													</label>
													<label>";
																															echo "<input type='radio' class='minimal' name='pencapaian$kr[id]' id='options2c1$kr[id]' value='N'";
																															if ($jjw0['pencapaian'] == "N") {
																																echo "checked";
																															}
																															echo ">";
																															echo " Tidak &nbsp;&nbsp;&nbsp;
													</label>
													</div>
												</div></div>";
																														}
																														if ($jjw0['jawaban'] == 0) {
																															echo "<div class='col-md-12'>
													<a class='btn btn-danger btn-xs'><span class='glyphicon glyphicon-times' aria-hidden='true' title='Tidak'></span></a>&nbsp;&nbsp;Tidak &nbsp;&nbsp;&nbsp;
												</div>";
																														}
																													}
																													$no3++;
																												}
																												echo "</div>";
																												$no2++;
																											}
																											$sqlcekjawaban1 = "SELECT * FROM `asesi_mak02` WHERE `id_jadwal`='$_GET[idj]' AND `id_asesi`='$_GET[ida]' AND `id_skema`='$sk[id]' AND `id_unitkompetensi`='$unk[id]'";
																											$cekjawaban1 = $conn->query($sqlcekjawaban1);
																											$jjw1 = $cekjawaban1->fetch_assoc();
																											echo "<div class='col-md-12'>
													<div class='col-md-12 bg-navy'><div class='form-group'><label>
													Bukti-bukti <b>Hasil rekaman observasi</b>:
													</label><br>
													<label>";
																											echo "<input type='checkbox' class='flat-red' name='checkboxa1$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' id='options1b$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' value='L'";
																											if ($jjw1['bukti1-1'] == "L") {
																												echo "checked";
																											}
																											echo ">";
																											echo " Bukti Langsung &nbsp;&nbsp;&nbsp;
													</label>
													<label>";
																											echo "<input type='checkbox' class='flat-red' name='checkboxb1$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' id='options2b$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' value='TL'";
																											if ($jjw1['bukti1-2'] == "TL") {
																												echo "checked";
																											}
																											echo ">";
																											echo " Bukti Tidak Langsung &nbsp;&nbsp;&nbsp;
													</label>
													<label>";
																											echo "<input type='checkbox' class='flat-red' name='checkboxc1$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' id='options3b$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' value='T'";
																											if ($jjw1['bukti1-3'] == "T") {
																												echo "checked";
																											}
																											echo ">";
																											echo " Bukti Tambahan
													</label>
													</div></div>
												</div>
												<div class='col-md-12'>
													<div class='col-md-12 bg-navy'><div class='form-group'><label>
													Pencapaian :
													</label><br>
													<label>";
																											echo "<input type='radio' class='minimal' name='pencapaian1$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' id='options1c1$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' value='Y'";
																											if ($jjw1['pencapaian1'] == "Y") {
																												echo "checked";
																											}
																											echo ">";
																											echo " Ya &nbsp;&nbsp;&nbsp;
													</label>
													<label>";
																											echo "<input type='radio' class='minimal' name='pencapaian1$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' id='options2c1$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' value='N'";
																											if ($jjw1['pencapaian1'] == "N") {
																												echo "checked";
																											}
																											echo ">";
																											echo " Tidak &nbsp;&nbsp;&nbsp;
													</label>
													</div></div>
												</div>			
												<div class='col-md-12'>
													<div class='col-md-12 bg-navy'><div class='form-group'><label>
													Keputusan :
													</label><br>
													<label>";
																											echo "<input type='radio' class='minimal' name='keputusan1$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' id='options1c1$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' value='K'";
																											if ($jjw1['keputusan1'] == "K") {
																												echo "checked";
																											}
																											echo ">";
																											echo " Kompeten &nbsp;&nbsp;&nbsp;
													</label>
													<label>";
																											echo "<input type='radio' class='minimal' name='keputusan1$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' id='options2c1$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' value='BK'";
																											if ($jjw1['keputusan1'] == "BK") {
																												echo "checked";
																											}
																											echo ">";
																											echo " Belum Kompeten &nbsp;&nbsp;&nbsp;
													</label>
													<label>";
																											echo "<input type='radio' class='minimal' name='keputusan1$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' id='options3c1$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' value='PL'";
																											if ($jjw1['keputusan1'] == "PL") {
																												echo "checked";
																											}
																											echo ">";
																											echo " Asesmen Lanjut
													</label>
													</div></div>
												</div>
												<div class='col-md-12'>
													<div class='col-md-12 bg-aqua'><div class='form-group'><label>
													Bukti-bukti <b>Hasil rekaman tes lisan</b>:
													</label><br>
													<label>";
																											echo "<input type='checkbox' class='flat-red' name='checkboxa2$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' id='options1b$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' value='L'";
																											if ($jjw1['bukti2-1'] == "L") {
																												echo "checked";
																											}
																											echo ">";
																											echo " Bukti Langsung &nbsp;&nbsp;&nbsp;
													</label>
													<label>";
																											echo "<input type='checkbox' class='flat-red' name='checkboxb2$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' id='options2b$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' value='TL'";
																											if ($jjw1['bukti2-2'] == "TL") {
																												echo "checked";
																											}
																											echo ">";
																											echo " Bukti Tidak Langsung &nbsp;&nbsp;&nbsp;
													</label>
													<label>";
																											echo "<input type='checkbox' class='flat-red' name='checkboxc2$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' id='options3b$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' value='T'";
																											if ($jjw1['bukti2-3'] == "T") {
																												echo "checked";
																											}
																											echo ">";
																											echo " Bukti Tambahan
													</label>
													</div></div>
												</div>
												<div class='col-md-12'>
													<div class='col-md-12 bg-aqua'><div class='form-group'><label>
													Pencapaian :
													</label><br>
													<label>";
																											echo "<input type='radio' class='minimal' name='pencapaian2$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' id='options1c2$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' value='Y'";
																											if ($jjw1['pencapaian2'] == "Y") {
																												echo "checked";
																											}
																											echo ">";
																											echo " Ya &nbsp;&nbsp;&nbsp;
													</label>
													<label>";
																											echo "<input type='radio' class='minimal' name='pencapaian2$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' id='options2c2$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' value='N'";
																											if ($jjw1['pencapaian2'] == "N") {
																												echo "checked";
																											}
																											echo ">";
																											echo " Tidak &nbsp;&nbsp;&nbsp;
													</label>
													</div></div>
												</div>			
												<div class='col-md-12'>
													<div class='col-md-12 bg-aqua'><div class='form-group'><label>
													Keputusan :
													</label><br>
													<label>";
																											echo "<input type='radio' class='minimal' name='keputusan2$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' id='options1c2$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' value='K'";
																											if ($jjw1['keputusan2'] == "K") {
																												echo "checked";
																											}
																											echo ">";
																											echo " Kompeten &nbsp;&nbsp;&nbsp;
													</label>
													<label>";
																											echo "<input type='radio' class='minimal' name='keputusan2$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' id='options2c2$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' value='BK'";
																											if ($jjw1['keputusan2'] == "BK") {
																												echo "checked";
																											}
																											echo ">";
																											echo " Belum Kompeten &nbsp;&nbsp;&nbsp;
													</label>
													<label>";
																											echo "<input type='radio' class='minimal' name='keputusan2$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' id='options3c2$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' value='PL'";
																											if ($jjw1['keputusan2'] == "PL") {
																												echo "checked";
																											}
																											echo ">";
																											echo " Asesmen Lanjut
													</label>
													</div></div>
												</div>
												<div class='col-md-12'>
													<div class='col-md-12 bg-blue'><div class='form-group'><label>
													Bukti-bukti <b>Hasil rekaman tes tertulis</b>:
													</label><br>
													<label>";
																											echo "<input type='checkbox' class='flat-red' name='checkboxa3$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' id='options1b$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' value='L'";
																											if ($jjw1['bukti3-1'] == "L") {
																												echo "checked";
																											}
																											echo ">";
																											echo " Bukti Langsung &nbsp;&nbsp;&nbsp;
													</label>
													<label>";
																											echo "<input type='checkbox' class='flat-red' name='checkboxb3$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' id='options2b$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' value='TL'";
																											if ($jjw1['bukti3-2'] == "TL") {
																												echo "checked";
																											}
																											echo ">";
																											echo " Bukti Tidak Langsung &nbsp;&nbsp;&nbsp;
													</label>
													<label>";
																											echo "<input type='checkbox' class='flat-red' name='checkboxc3$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' id='options3b$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' value='T'";
																											if ($jjw1['bukti3-3'] == "T") {
																												echo "checked";
																											}
																											echo ">";
																											echo " Bukti Tambahan
													</label>
													</div></div>
												</div>
												<div class='col-md-12'>
													<div class='col-md-12 bg-blue'><div class='form-group'><label>
													Pencapaian :
													</label><br>
													<label>";
																											echo "<input type='radio' class='minimal' name='pencapaian3$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' id='options1c3$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' value='Y'";
																											if ($jjw1['pencapaian3'] == "Y") {
																												echo "checked";
																											}
																											echo ">";
																											echo " Ya &nbsp;&nbsp;&nbsp;
													</label>
													<label>";
																											echo "<input type='radio' class='minimal' name='pencapaian3$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' id='options2c3$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' value='N'";
																											if ($jjw1['pencapaian3'] == "N") {
																												echo "checked";
																											}
																											echo ">";
																											echo " Tidak &nbsp;&nbsp;&nbsp;
													</label>
													</div></div>
												</div>			
												<div class='col-md-12'>
													<div class='col-md-12 bg-blue'><div class='form-group'><label>
													Keputusan :
													</label><br>
													<label>";
																											echo "<input type='radio' class='minimal' name='keputusan3$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' id='options1c3$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' value='K'";
																											if ($jjw1['keputusan3'] == "K") {
																												echo "checked";
																											}
																											echo ">";
																											echo " Kompeten &nbsp;&nbsp;&nbsp;
													</label>
													<label>";
																											echo "<input type='radio' class='minimal' name='keputusan3$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' id='options2c3$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' value='BK'";
																											if ($jjw1['keputusan3'] == "BK") {
																												echo "checked";
																											}
																											echo ">";
																											echo " Belum Kompeten &nbsp;&nbsp;&nbsp;
													</label>
													<label>";
																											echo "<input type='radio' class='minimal' name='keputusan3$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' id='options3c3$jd[id]-$jd[id_skemakkni]-$unk[id]-$_GET[ida]' value='PL'";
																											if ($jjw1['keputusan3'] == "PL") {
																												echo "checked";
																											}
																											echo ">";
																											echo " Asesmen Lanjut
													</label>
													</div></div>
												</div>";
																											echo "<div></div><!-- /.box-body-->
				</div></div><!-- /.box box-solid-->";
																											$no++;
																										}
																									}
																									echo "</div>";
																									$sqlgetkeputusan = "SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																									$getkeputusan = $conn->query($sqlgetkeputusan);
																									$getk = $getkeputusan->fetch_assoc();
																									echo "<div class='box-footer bg-black'>
			<div class='col-md-12'><label class='text-yellow'>
							Asesi telah diberikan umpan balik/masukan dan diinformasikan hasil asesmen/uji kompetensi serta penjelasan terhadap keputusan yang dibuat. Berdasarkan hasil asesmen tersebut, peserta : 
						</label><br>
						<label>";
																									echo "<input type='radio' class='minimal' name='keputusanfinal' id='keputusanfinal1' value='R'";
																									if ($getk['keputusan_asesor'] == "R") {
																										echo "checked";
																									}
																									echo ">";
																									echo " Direkomendasikan &nbsp;&nbsp;&nbsp;
						</label>
						<label>";
																									echo "<input type='radio' class='minimal' name='keputusanfinal' id='keputusanfinal2' value='NR'";
																									if ($getk['keputusan_asesor'] == "NR") {
																										echo "checked";
																									}
																									echo ">";
																									echo " Tidak Direkomendasikan &nbsp;&nbsp;&nbsp;
						</label><br>
						<label class='text-yellow'>Untuk mendapatkan pengakuan terhadap unit kompetensi yang diujikan.</label>
			</div>
									<div class='col-md-4 col-sm-12 col-xs-12'>
					<a class='btn btn-danger form-control' id=reset-validate-form href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a>
			</div>
			<div class='col-md-4 col-sm-12 col-xs-12'>
					<a href='form-mak-04.php?ida=$_GET[ida]&idj=$_GET[idj]' class='btn btn-primary form-control'>Unduh Form Hasil</a>
			</div>
			<div class='col-md-4 col-sm-12 col-xs-12'>
					<button type='submit' class='btn btn-success form-control' name='simpan'>Simpan Jawaban</button>
			</div>
								</div>
							</form>			
							</div>
							<!-- /.box-body -->
						</div>
						<!-- /.box -->
					</div>
					<!-- /.col -->
				</div>
				<!-- /.row -->
			</section>
			<!-- /.content -->";
																								}
																								//===============================================================================
																								// Bagian Input Hasil Asesmen FORM-FR.AK.01 Asesor
																								elseif ($_GET['module'] == 'form-fr-ak-01') {
																									$sqllogin = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_GET[ida]'";
																									$login = $conn->query($sqllogin);
																									$ketemu = $login->num_rows;
																									$rowAgen = $login->fetch_assoc();
																									$sqlgetjadwal = "SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
																									$getjadwal = $conn->query($sqlgetjadwal);
																									$jd = $getjadwal->fetch_assoc();
																									$sqlgetskema = "SELECT * FROM `skema_kkni` WHERE `id`='$jd[id_skemakkni]'";
																									$getskema = $conn->query($sqlgetskema);
																									$sk = $getskema->fetch_assoc();
																									$sqlgetasesordata = "SELECT * FROM `asesor` WHERE `no_ktp`='$_SESSION[namauser]'";
																									$getasesordata = $conn->query($sqlgetasesordata);
																									$asr = $getasesordata->fetch_assoc();
																									$sqltuk = "SELECT * FROM `tuk` WHERE `id`='$jd[tempat_asesmen]'";
																									$tuk = $conn->query($sqltuk);
																									$tq = $tuk->fetch_assoc();
																									$sqltukjenis = "SELECT `jenis_tuk` FROM `tuk_jenis` WHERE `id`='$tq[jenis_tuk]'";
																									$tukjenis = $conn->query($sqltukjenis);
																									$tukjen = $tukjenis->fetch_assoc();
																									$noasr = 1;
																									$getasesor = $conn->query("SELECT * FROM `jadwal_asesor` WHERE `id_jadwal`='$_GET[idj]'");
																									while ($gas = $getasesor->fetch_assoc()) {
																										$sqlasesor = "SELECT * FROM `asesor` WHERE `id`='$gas[id_asesor]'";
																										$asesor = $conn->query($sqlasesor);
																										$asr = $asesor->fetch_assoc();
																										if (!empty($asr['gelar_depan'])) {
																											if (!empty($asr['gelar_blk'])) {
																												$namaasesor = $asr['gelar_depan'] . " " . $asr['nama'] . ", " . $asr['gelar_blk'];
																											} else {
																												$namaasesor = $asr['gelar_depan'] . " " . $asr['nama'];
																											}
																										} else {
																											if (!empty($asr['gelar_blk'])) {
																												$namaasesor = $asr['nama'] . ", " . $asr['gelar_blk'];
																											} else {
																												$namaasesor = $asr['nama'];
																											}
																										}
																										$noregasesor = $asr['no_induk'];
																										$namaasesor = $noasr . '. ' . $namaasesor;
																										$noregasesor = $noasr . '. ' . $noregasesor;
																										$noasr++;
																									}
																									echo "<!-- Content Header (Page header) -->
			<section class='content-header'>
				<h1>
			Input FR.AK.01. FORMULIR PERSETUJUAN ASESMEN DAN KERAHASIAAN
					<small>Skema $sk[judul]</small>
				</h1>
				<ol class='breadcrumb'>
					<li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
					<li class='active'>FR.AK.01</li>
				</ol>
			</section>";
																									if (isset($_REQUEST['simpan'])) {
																										$sqlcekgetak01 = "SELECT * FROM `asesmen_ak01` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																										$cekgetak01 = $conn->query($sqlcekgetak01);
																										$cekak01 = $cekgetak01->num_rows;
																										$tglsekarang = date("Y-m-d");
																										if ($cekak01 > 0) {
																											$folderPath = "../foto_tandatangan/";
																											if (empty($_POST['signed'])) {
																												echo "<div class='alert alert-success alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Persetujuan Asesor berhasil disimpan</h4>
					Terimakasih, Anda telah melakukan <b>Persetujuan Asesmen dan Kerahasiaan untuk Skema $sk[judul].</b><br>
					<a class='btn btn-warning form-control' href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a></div>
					<script>alert('Data berhasil disimpan'); window.location = '" . $base_url . "media.php?module=form-fr-ak-01&ida=$_GET[ida]&idj=$_GET[idj]'</script>";
																												if (isset($_POST['checkboxVP'])) {
																													$postVP = $_POST['checkboxVP'];
																												} else {
																													$postVP = "0";
																												}
																												if (isset($_POST['checkboxCL'])) {
																													$postCL = $_POST['checkboxCL'];
																												} else {
																													$postCL = "0";
																												}
																												if (isset($_POST['checkboxDPT'])) {
																													$postDPT = $_POST['checkboxDPT'];
																												} else {
																													$postDPT = "0";
																												}
																												if (isset($_POST['checkboxDPL'])) {
																													$postDPL = $_POST['checkboxDPL'];
																												} else {
																													$postDPL = "0";
																												}
																												if (isset($_POST['checkboxPW'])) {
																													$postPW = $_POST['checkboxPW'];
																												} else {
																													$postPW = "0";
																												}
																												$sqlinputak01 = "UPDATE `asesmen_ak01` SET `VP`='$postVP',`CL`='$postCL', `DPT`='$postDPT', `DPL`='$postDPL', `PW`='$postPW', `persetujuan`='" . $_POST['persetujuan'] . "',`tanggal`='$tglsekarang' WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$jd[id_skemakkni]' AND `id_jadwal`='$_GET[idj]'";
																												$conn->query($sqlinputak01);
																											} else {
																												$image_parts = explode(";base64,", $_POST['signed']);
																												$image_type_aux = explode("image/", $image_parts[0]);
																												$image_type = $image_type_aux[1];
																												$image_base64 = base64_decode($image_parts[1]);
																												$file = $folderPath . uniqid() . '.' . $image_type;
																												file_put_contents($file, $image_base64);
																												$url =  "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
																												$iddokumen = md5($url);
																												$escaped_url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
																												$alamatip = $_SERVER['REMOTE_ADDR'];
																												$sqlinputdigisign = "INSERT INTO `logdigisign`(`id_dokumen`, id_skema, id_asesi, `url_ditandatangani`, `nama_dokumen`, `penandatangan`, `file`, `ip`) VALUES ('$iddokumen','$sk[id]','$_SESSION[namauser]','$escaped_url','FR.AK.01.FORMULIR PERSETUJUAN ASESMEN DAN KERAHASIAAN','$_SESSION[namalengkap]','$file','$alamatip')";
																												$conn->query($sqlinputdigisign);
																												echo "<div class='alert alert-success alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Persetujuan Asesor berhasil disimpan</h4>
					Terimakasih, Anda telah melakukan <b>Persetujuan Asesmen dan Kerahasiaan untuk Skema $sk[judul], dan tanda tangan telah ditambahkan</b><br>
					<a class='btn btn-warning form-control' href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a></div>
					<script>alert('Data berhasil disimpan'); window.location = '" . $base_url . "media.php?module=form-fr-ak-01&ida=$_GET[ida]&idj=$_GET[idj]'</script>";
																												if (isset($_POST['checkboxVP'])) {
																													$postVP = $_POST['checkboxVP'];
																												} else {
																													$postVP = "0";
																												}
																												if (isset($_POST['checkboxCL'])) {
																													$postCL = $_POST['checkboxCL'];
																												} else {
																													$postCL = "0";
																												}
																												if (isset($_POST['checkboxDPT'])) {
																													$postDPT = $_POST['checkboxDPT'];
																												} else {
																													$postDPT = "0";
																												}
																												if (isset($_POST['checkboxDPL'])) {
																													$postDPL = $_POST['checkboxDPL'];
																												} else {
																													$postDPL = "0";
																												}
																												if (isset($_POST['checkboxPW'])) {
																													$postPW = $_POST['checkboxPW'];
																												} else {
																													$postPW = "0";
																												}
																												$sqlinputak01 = "UPDATE `asesmen_ak01` SET `VP`='$postVP',`CL`='$postCL', `DPT`='$postDPT', `DPL`='$postDPL', `PW`='$postPW', `persetujuan`='" . $_POST['persetujuan'] . "',`tanggal`='$tglsekarang' WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$jd[id_skemakkni]' AND `id_jadwal`='$_GET[idj]'";
																												$conn->query($sqlinputak01);
																											}
																										} else {
																											$folderPath = "../foto_tandatangan/";
																											if (empty($_POST['signed'])) {
																												echo "<div class='alert alert-success alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Persetujuan Asesor berhasil disimpan</h4>
					Terimakasih, Anda telah melakukan <b>Persetujuan Asesmen dan Kerahasiaan untuk Skema $sk[judul].</b><br>
					<a class='btn btn-warning form-control' href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a></div>";
																												$sqlinputak01 = "INSERT INTO `asesmen_ak01`(`id_asesi`, `id_skemakkni`, `id_jadwal`, `VP`, `CL`, `DPT`, `DPL`, `PW`, `persetujuan`, `tanggal`) VALUES ('$_GET[ida]','$jd[id_skemakkni]','$_GET[idj]','$postVP','$postCL','$postDPT','$postDPL','$postPW','$_POST[persetujuan]','$tglsekarang')";
																												$conn->query($sqlinputak01);
																											} else {
																												$image_parts = explode(";base64,", $_POST['signed']);
																												$image_type_aux = explode("image/", $image_parts[0]);
																												$image_type = $image_type_aux[1];
																												$image_base64 = base64_decode($image_parts[1]);
																												$file = $folderPath . uniqid() . '.' . $image_type;
																												file_put_contents($file, $image_base64);
																												$url =  "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
																												$iddokumen = md5($url);
																												$escaped_url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
																												$alamatip = $_SERVER['REMOTE_ADDR'];
																												$sqlinputdigisign = "INSERT INTO `logdigisign`(`id_dokumen`, id_skema, id_asesi, `url_ditandatangani`, `nama_dokumen`, `penandatangan`, `file`, `ip`) VALUES ('$iddokumen','$sk[id]','$_SESSION[namauser]','$escaped_url','FR.AK.01.FORMULIR PERSETUJUAN ASESMEN DAN KERAHASIAAN','$_SESSION[namalengkap]','$file','$alamatip')";
																												$conn->query($sqlinputdigisign);
																												echo "<div class='alert alert-success alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Persetujuan Asesor berhasil disimpan</h4>
					Terimakasih, Anda telah melakukan <b>Persetujuan Asesmen dan Kerahasiaan untuk Skema $sk[judul], dan tanda tangan telah ditambahkan</b><br>
					<a class='btn btn-warning form-control' href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a></div>";
																												if (isset($_POST['checkboxVP'])) {
																													$postVP = $_POST['checkboxVP'];
																												} else {
																													$postVP = "0";
																												}
																												if (isset($_POST['checkboxCL'])) {
																													$postCL = $_POST['checkboxCL'];
																												} else {
																													$postCL = "0";
																												}
																												if (isset($_POST['checkboxDPT'])) {
																													$postDPT = $_POST['checkboxDPT'];
																												} else {
																													$postDPT = "0";
																												}
																												if (isset($_POST['checkboxDPL'])) {
																													$postDPL = $_POST['checkboxDPL'];
																												} else {
																													$postDPL = "0";
																												}
																												if (isset($_POST['checkboxPW'])) {
																													$postPW = $_POST['checkboxPW'];
																												} else {
																													$postPW = "0";
																												}
																												$sqlinputak01 = "INSERT INTO `asesmen_ak01`(`id_asesi`, `id_skemakkni`, `id_jadwal`, `VP`, `CL`, `DPT`, `DPL`, `PW`, `persetujuan`, `tanggal`) VALUES ('$_GET[ida]','$jd[id_skemakkni]','$_GET[idj]','$postVP','$postCL','$postDPT','$postDPL','$postPW','$_POST[persetujuan]','$tglsekarang')";
																												$conn->query($sqlinputak01);
																											}
																										}
																									}
																									if (isset($_REQUEST['ubahjawaban'])) {
																										$sqlcekgetak01 = "SELECT * FROM `asesmen_ak01` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																										$cekgetak01 = $conn->query($sqlcekgetak01);
																										$cekak01 = $cekgetak01->num_rows;
																										// UPDATE @FHM-PUSTI 7 AGUSTUS 2023 : Cek Query Logdigisign Asesor
																										$ceklogdigisign = "SELECT * FROM logdigisign WHERE id_asesi='$_SESSION[namauser]' AND id_skema='$jd[id_skemakkni]' AND nama_dokumen='FR.AK.01.FORMULIR PERSETUJUAN ASESMEN DAN KERAHASIAAN' AND penandatangan='$asr[nama]'";
																										$connlogdigisign = $conn->query($ceklogdigisign);
																										$qlogdigisign = $connlogdigisign->fetch_assoc();

																										// UPDATE @FHM-PUSTI 7 AGUSTUS 2023 : Cek Query Logdigisign Asesi
																										$ceklogdigisignasesi = "SELECT * FROM logdigisign WHERE id_asesi='$_GET[ida]' AND id_skema='$jd[id_skemakkni]' AND nama_dokumen='FR.AK.01. FORMULIR PERSETUJUAN ASESMEN DAN KERAHASIAAN' AND penandatangan='$rowAgen[nama]'";
																										$connlogdigisignasesi = $conn->query($ceklogdigisignasesi);
																										$qlogdigisignasesi = $connlogdigisignasesi->fetch_assoc();
																										$tglsekarang = date("Y-m-d");
																										$folderPath = "../foto_tandatangan/";
																										if (empty($_POST['signed'])) {
																											echo "<div class='alert alert-success alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Persetujuan Asesor berhasil diubah</h4>
					<a class='btn btn-warning form-control' href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a></div>
					<script>alert('Data berhasil diubah'); window.location = '" . $base_url . "media.php?module=form-fr-ak-01&ida=$_GET[ida]&idj=$_GET[idj]'</script>";
																											if (isset($_POST['checkboxVP'])) {
																												$postVP = $_POST['checkboxVP'];
																											} else {
																												$postVP = "0";
																											}
																											if (isset($_POST['checkboxCL'])) {
																												$postCL = $_POST['checkboxCL'];
																											} else {
																												$postCL = "0";
																											}
																											if (isset($_POST['checkboxDPT'])) {
																												$postDPT = $_POST['checkboxDPT'];
																											} else {
																												$postDPT = "0";
																											}
																											if (isset($_POST['checkboxDPL'])) {
																												$postDPL = $_POST['checkboxDPL'];
																											} else {
																												$postDPL = "0";
																											}
																											if (isset($_POST['checkboxPW'])) {
																												$postPW = $_POST['checkboxPW'];
																											} else {
																												$postPW = "0";
																											}
																											$sqlinputak01 = "UPDATE `asesmen_ak01` SET `VP`='$postVP',`CL`='$postCL', `DPT`='$postDPT', `DPL`='$postDPL', `PW`='$postPW', `persetujuan`='" . $_POST['persetujuan'] . "',`tanggal`='$tglsekarang', `tanggal_asesittd`=NULL, `persetujuan_asesi`=NULL WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$jd[id_skemakkni]' AND `id_jadwal`='$_GET[idj]'";
																											$conn->query($sqlinputak01);
																										} else {
																											// UPDATE @FHM-PUSTI 7 AGUSTUS 2023 : Hapus data logdigisign Asesi
																											$deletelogdigisignasesi = "DELETE FROM `logdigisign` WHERE id_asesi='$_GET[ida]' AND id_skema='$jd[id_skemakkni]' AND nama_dokumen='FR.AK.01.FORMULIR PERSETUJUAN ASESMEN DAN KERAHASIAAN' AND penandatangan='$rowAgen[nama]'";
																											$conn->query($deletelogdigisignasesi);
																											// UPDATE @FHM-PUSTI 7 AGUSTUS 2023 : Hapus file ttd asesi
																											unlink($qlogdigisignasesi['file']);
																											// UPDATE @FHM-PUSTI 7 AGUSTUS 2023 : Hapus file ttd asesor
																											unlink($qlogdigisign['file']);
																											$image_parts = explode(";base64,", $_POST['signed']);
																											$image_type_aux = explode("image/", $image_parts[0]);
																											$image_type = $image_type_aux[1];
																											$image_base64 = base64_decode($image_parts[1]);
																											$file = $folderPath . uniqid() . '.' . $image_type;
																											file_put_contents($file, $image_base64);
																											$url =  "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
																											$iddokumen = md5($url);
																											$escaped_url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
																											$alamatip = $_SERVER['REMOTE_ADDR'];
																											$updatelogdigisign = "UPDATE `logdigisign` SET `file` = '$file' WHERE `logdigisign`.`id` ='$qlogdigisign[id]'";
																											$conn->query($updatelogdigisign);
																											echo "<div class='alert alert-success alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Persetujuan Asesor berhasil disimpan</h4>
					Terimakasih, Anda telah melakukan <b>Persetujuan Asesmen dan Kerahasiaan untuk Skema $sk[judul], dan tanda tangan telah ditambahkan</b><br>
					<a class='btn btn-warning form-control' href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a></div>
					<script>alert('Data berhasil diubah'); window.location = '" . $base_url . "media.php?module=form-fr-ak-01&ida=$_GET[ida]&idj=$_GET[idj]'</script>";
																											if (isset($_POST['checkboxVP'])) {
																												$postVP = $_POST['checkboxVP'];
																											} else {
																												$postVP = "0";
																											}
																											if (isset($_POST['checkboxCL'])) {
																												$postCL = $_POST['checkboxCL'];
																											} else {
																												$postCL = "0";
																											}
																											if (isset($_POST['checkboxDPT'])) {
																												$postDPT = $_POST['checkboxDPT'];
																											} else {
																												$postDPT = "0";
																											}
																											if (isset($_POST['checkboxDPL'])) {
																												$postDPL = $_POST['checkboxDPL'];
																											} else {
																												$postDPL = "0";
																											}
																											if (isset($_POST['checkboxPW'])) {
																												$postPW = $_POST['checkboxPW'];
																											} else {
																												$postPW = "0";
																											}
																											$sqlinputak01 = "UPDATE `asesmen_ak01` SET `VP`='$postVP',`CL`='$postCL', `DPT`='$postDPT', `DPL`='$postDPL', `PW`='$postPW', `persetujuan`='" . $_POST['persetujuan'] . "',`tanggal`='$tglsekarang', `tanggal_asesittd`=NULL, `persetujuan_asesi`=NULL WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$jd[id_skemakkni]' AND `id_jadwal`='$_GET[idj]'";
																											$conn->query($sqlinputak01);
																										}
																									}
																									$sqlgetak01 = "SELECT * FROM `asesmen_ak01` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																									$getak01 = $conn->query($sqlgetak01);
																									$jjw = $getak01->fetch_assoc();
																									echo "<!-- Main content -->
			<section class='content'>
				<div class='row'>
					<div class='col-xs-12'>
						<div class='box'>
							<div class='box-body'>
								<!-- form start -->
								<div class='box-body'>
						<h2>FR.AK.01. FORMULIR PERSETUJUAN ASESMEN DAN KERAHASIAAN</h2>
						<form role='form' method='POST' enctype='multipart/form-data'>
						<table id='example9' class='table table-bordered table-striped'>
							<tr><td colspan='3'>Persetujuan Asesmen ini untuk menjamin bahwa Asesi telah diberi arahan secara rinci tentang perencanaan dan proses asesmen</td></tr>
							<tr><td rowspan='2' width='25%'>Skema Sertifikasi (KKNI/Okupasi/Klaster)</td><td>Judul :</td><td>$sk[judul]</td></tr>
							<tr><td width='25%'>Nomor :</td><td>$sk[kode_skema]</td></tr>
							<tr><td width='25%'>TUK</td><td colspan='2'>$tukjen[jenis_tuk]</td></tr>
							<tr><td width='25%'>Nama Asesor</td><td colspan='2'>$namaasesor</td></tr>
							<tr><td width='25%'>Nama Asesi</td><td colspan='2'>$rowAgen[nama]</td></tr>
						</table>
						<table id='example9' class='table table-bordered table-striped'>
							<tr><td rowspan='4' width='25%'>Bukti yang akan dikumpulkan</td><td>";
																									echo "<input type='checkbox' class='flat-red' name='checkboxVP' id='optionsVP' value='1'";
																									if ($jjw['VP'] == "1") {
																										echo "checked";
																									} else {
																										echo "";
																									}
																									echo "> TL : Verifikasi Portfolio";
																									echo "</td><td>";
																									echo "<input type='checkbox' class='flat-red' name='checkboxCL' id='optionsCL' value='1'";
																									if ($jjw['CL'] == "1") {
																										echo "checked";
																									} else {
																										echo "";
																									}
																									echo "> L : Observasi Langsung";
																									echo "</td>
							</tr>
							<tr><td colspan='2'>";
																									echo "<input type='checkbox' class='flat-red' name='checkboxDPT' id='optionsDPT' value='1'";
																									if ($jjw['DPT'] == "1") {
																										echo "checked";
																									} else {
																										echo "";
																									}
																									echo "> T : Hasil Tes Tulis";
																									echo "</td>
							</tr>
							<tr><td colspan='2'>";
																									echo "<input type='checkbox' class='flat-red' name='checkboxDPL' id='optionsDPL' value='1'";
																									if ($jjw['DPL'] == "1") {
																										echo "checked";
																									} else {
																										echo "";
																									}
																									echo "> L : Hasil Tes Lisan";
																									echo "</td>
							</tr>
							<tr><td colspan='2'>";
																									echo "<input type='checkbox' class='flat-red' name='checkboxPW' id='optionsPW' value='1'";
																									if ($jjw['PW'] == "1") {
																										echo "checked";
																									} else {
																										echo "";
																									}
																									echo "> L : Hasil Wawancara";
																									echo "</td>
							</tr>
						</table>
						<table id='example9' class='table table-bordered table-striped'>
							<tr><td rowspan='3' width='25%'>Pelaksanaan asesmen disepakati pada:</td><td>";
																									$tanggal = $jd['tgl_asesmen'];
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
																									$jadwal = $dayList[$day] . ", " . tgl_indo($tanggal);
																									$jamasesmen = "Pukul " . $jd['jam_asesmen'];
																									echo "Hari/ Tanggal";
																									echo "</td><td>";
																									echo "$jadwal";
																									echo "</td>
							</tr>
							<tr><td>";
																									echo "Waktu";
																									echo "</td><td>$jamasesmen</td>
							</tr>
							<tr><td>";
																									echo "TUK";
																									echo "</td><td>$tq[nama]</td>
							</tr>
						</table>
						<p><b>Asesor :</b><br />
						<input type='checkbox' class='flat-red' name='persetujuan' id='optionsPersetujuan' value='Y'";
																									if ($jjw['persetujuan'] == "Y") {
																										echo "checked";
																									} else {
																										echo "";
																									}
																									echo " required> Menyatakan tidak akan membuka hasil pekerjaan yang saya peroleh karena penugasan saya sebagai Asesor dalam pekerjaan Asesmen kepada siapapun atau organisasi apapun selain kepada pihak yang berwenang sehubungan dengan kewajiban saya sebagai Asesor yang ditugaskan oleh LSP.
						</p>";
																									// cek tandatangan digital
																									$url = "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
																									$iddokumen = md5($url);
																									$sqlcektandatangan = "SELECT * FROM `logdigisign` WHERE id_asesi='$asr[no_ktp]' AND id_skema='$jd[id_skemakkni]' AND nama_dokumen='FR.AK.01.FORMULIR PERSETUJUAN ASESMEN DAN KERAHASIAAN' AND penandatangan='$asr[nama]'";
																									// $sqlcektandatangan="SELECT * FROM `logdigisign` WHERE `id_dokumen`='$iddokumen' ORDER BY `id` DESC";
																									$cektandatangan = $conn->query($sqlcektandatangan);
																									$jumttd = $cektandatangan->num_rows;
																									$ttdx = $cektandatangan->fetch_assoc();
																									if ($jumttd > 0) {
																										echo "<div class='col-md-12'>
									<label class='' for=''>Persetujuan/ Tanda Tangan yang telah Anda berikan:</label>
									<br/>
									<img src='$ttdx[file]' width='400px'/>
									<br/>
								</div>";
																									} else {
																										echo "<div class='col-md-12'>
							<label class='' for=''>Tanda Tangan:</label>
							<br/>
							<div id='sig' ></div>
							<br/>
							<button id='clear'>Hapus Tanda Tangan</button>
							<textarea id='signature64' name='signed' style='display: none'></textarea>
						</div>
						<script type='text/javascript'>
							var sig = $('#sig').signature({syncField: '#signature64', syncFormat: 'PNG', color: '#58009F'});
							$('#clear').click(function(e) {
								e.preventDefault();
								sig.signature('clear');
								$('#signature64').val('');
							});
						</script></div>";
																									}
																									echo "<div class='box-footer'>
						<div class='col-md-4 col-sm-12 col-xs-12'>
								<a class='btn btn-danger form-control' id=reset-validate-form href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a>
						</div>
						<div class='col-md-4 col-sm-12 col-xs-12'>
								<a href='form-fr-ak-01.php?ida=$_GET[ida]&idj=$_GET[idj]' class='btn btn-primary form-control' target='_blank'>Unduh Formulir</a>
						</div>";
																									if (!empty($jjw)) {
																										echo "<div class='col-md-4 col-sm-12 col-xs-12'>
								<button type='submit' class='btn btn-success form-control' onclick='return confirmationupdate()' name='ubahjawaban'>Ubah Jawaban</button>
						</div>";
																									} else {
																										echo "<div class='col-md-4 col-sm-12 col-xs-12'>
								<button type='submit' class='btn btn-success form-control' name='simpan'>Simpan Jawaban</button>
						</div>";
																									}

																									echo "</div>
				</form>			
							</div>
							<!-- /.box-body -->
						</div>
						<!-- /.box -->
					</div>
					<!-- /.col -->
				</div>
				<!-- /.row -->
			</section>
			<!-- /.content -->";
																								}
																								// Bagian Input Hasil Asesmen FORM-FR.AK.02 Asesor
																								elseif ($_GET['module'] == 'form-fr-ak-02') {
																									$sqllogin = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_GET[ida]'";
																									$login = $conn->query($sqllogin);
																									$ketemu = $login->num_rows;
																									$rowAgen = $login->fetch_assoc();
																									$sqlgetjadwal = "SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
																									$getjadwal = $conn->query($sqlgetjadwal);
																									$jd = $getjadwal->fetch_assoc();
																									$sqlgetskema = "SELECT * FROM `skema_kkni` WHERE `id`='$jd[id_skemakkni]'";
																									$getskema = $conn->query($sqlgetskema);
																									$sk = $getskema->fetch_assoc();
																									$sqlgetasesordata = "SELECT * FROM `asesor` WHERE `no_ktp`='$_SESSION[namauser]'";
																									$getasesordata = $conn->query($sqlgetasesordata);
																									$asr = $getasesordata->fetch_assoc();
																									$sqltuk = "SELECT * FROM `tuk` WHERE `id`='$jd[tempat_asesmen]'";
																									$tuk = $conn->query($sqltuk);
																									$tq = $tuk->fetch_assoc();
																									$sqltukjenis = "SELECT `jenis_tuk` FROM `tuk_jenis` WHERE `id`='$tq[jenis_tuk]'";
																									$tukjenis = $conn->query($sqltukjenis);
																									$tukjen = $tukjenis->fetch_assoc();
																									$noasr = 1;
																									$getasesor = $conn->query("SELECT * FROM `jadwal_asesor` WHERE `id_jadwal`='$_GET[idj]'");
																									while ($gas = $getasesor->fetch_assoc()) {
																										$sqlasesor = "SELECT * FROM `asesor` WHERE `id`='$gas[id_asesor]'";
																										$asesor = $conn->query($sqlasesor);
																										$asr = $asesor->fetch_assoc();
																										if (!empty($asr['gelar_depan'])) {
																											if (!empty($asr['gelar_blk'])) {
																												$namaasesor = $asr['gelar_depan'] . " " . $asr['nama'] . ", " . $asr['gelar_blk'];
																											} else {
																												$namaasesor = $asr['gelar_depan'] . " " . $asr['nama'];
																											}
																										} else {
																											if (!empty($asr['gelar_blk'])) {
																												$namaasesor = $asr['nama'] . ", " . $asr['gelar_blk'];
																											} else {
																												$namaasesor = $asr['nama'];
																											}
																										}
																										$noregasesor = $asr['no_induk'];
																										$namaasesor = $noasr . '. ' . $namaasesor;
																										$noregasesor = $noasr . '. ' . $noregasesor;
																										$noasr++;
																									}
																									echo "<!-- Content Header (Page header) -->
			<section class='content-header'>
				<h1>
			Input FR.AK.02. FORMULIR REKAMAN ASESMEN KOMPETENSI
					<small>Skema $sk[judul]</small>
				</h1>
				<ol class='breadcrumb'>
					<li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
					<li class='active'>FR.AK.02</li>
				</ol>
			</section>";
																									if (isset($_REQUEST['simpan'])) {
																										$tglsekarang = date("Y-m-d");
																										$folderPath = "../foto_tandatangan/";
																										if (empty($_POST['signed'])) {
																											//unit kompetensi
																											$sqlgetunitkompetensi0 = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
																											$getunitkompetensi0 = $conn->query($sqlgetunitkompetensi0);
																											//while unitkompetensi ==================================================================
																											while ($unk0 = $getunitkompetensi0->fetch_assoc()) {
																												$sqlcekgetak01b = "SELECT * FROM `asesmen_ak02` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_unitkompetensi`='$unk0[id]'";
																												$cekgetak01b = $conn->query($sqlcekgetak01b);
																												$jumak02unit = $cekgetak01b->num_rows;
																												$varpostCL = 'checkboxCL' . $unk0['id'];
																												if (isset($_POST[$varpostCL])) {
																													$postCL = $_POST[$varpostCL];
																												} else {
																													$postCL = '0';
																												}
																												$varpostVP = 'checkboxVP' . $unk0['id'];
																												if (isset($_POST[$varpostVP])) {
																													$postVP = $_POST[$varpostVP];
																												} else {
																													$postVP = '0';
																												}
																												$varpostPW = 'checkboxPW' . $unk0['id'];
																												if (isset($_POST[$varpostPW])) {
																													$postPW = $_POST[$varpostPW];
																												} else {
																													$postPW = '0';
																												}
																												$varpostDPL = 'checkboxDPL' . $unk0['id'];
																												if (isset($_POST[$varpostDPL])) {
																													$postDPL = $_POST[$varpostDPL];
																												} else {
																													$postDPL = '0';
																												}
																												$varpostDPT = 'checkboxDPT' . $unk0['id'];
																												if (isset($_POST[$varpostDPT])) {
																													$postDPT = $_POST[$varpostDPT];
																												} else {
																													$postDPT = '0';
																												}
																												$varpostCUP = 'checkboxCUP' . $unk0['id'];
																												if (isset($_POST[$varpostCUP])) {
																													$postCUP = $_POST[$varpostCUP];
																												} else {
																													$postCUP = '0';
																												}
																												$varpostLainnya = 'checkboxLainnya' . $unk0['id'];
																												if (isset($_POST[$varpostLainnya])) {
																													$postLainnya = $_POST[$varpostLainnya];
																												} else {
																													$postLainnya = '0';
																												}
																												if ($jumak02unit > 0) {
																													// update data
																													$sqlinputak01 = "UPDATE `asesmen_ak02` SET `CL`='$postCL', `VP`='$postVP', `PW`='$postPW', `DPL`='$postDPL', `DPT`='$postDPT', `CUP`='$postCUP', `Lainnya`='$postLainnya',`tanggal`='$tglsekarang' WHERE `id_asesi`='$_GET[ida]' AND `id_unitkompetensi`='$unk0[id]' AND `id_jadwal`='$_GET[idj]'";
																													$conn->query($sqlinputak01);
																												} else {
																													// input data
																													$sqlinputak01 = "INSERT INTO `asesmen_ak02`(`id_asesi`, `id_unitkompetensi`, `id_jadwal`, `CL`, `VP`, `PW`, `DPL`, `DPT`, `CUP`, `Lainnya`, `tanggal`) VALUES ('$_GET[ida]', '$unk0[id]', '$_GET[idj]', '$postCL', '$postVP', '$postPW', '$postDPL', '$postDPT', '$postCUP', '$postLainnya', '$tglsekarang')";
																													$conn->query($sqlinputak01);
																												}
																											}
																											$sqlinputak02 = "UPDATE `asesi_asesmen` SET `umpan_balik`='$_POST[umpan_balik]', `tindak_lanjut_ak02`='$_POST[tindaklanjut]' WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																											$conn->query($sqlinputak02);
																											echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Formulir REKAMAN ASESMEN KOMPETENSI berhasil disimpan</h4>
				Terimakasih, Anda telah mengisi <b>Formulir REKAMAN ASESMEN KOMPETENSI untuk Skema $sk[judul].</b><br>
				<a class='btn btn-warning form-control' href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a></div>";
																										} else {
																											$image_parts = explode(";base64,", $_POST['signed']);
																											$image_type_aux = explode("image/", $image_parts[0]);
																											$image_type = $image_type_aux[1];
																											$image_base64 = base64_decode($image_parts[1]);
																											$file = $folderPath . uniqid() . '.' . $image_type;
																											file_put_contents($file, $image_base64);
																											$url =  "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
																											$iddokumen = md5($url);
																											$escaped_url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
																											$alamatip = $_SERVER['REMOTE_ADDR'];
																											$sqlinputdigisign = "INSERT INTO `logdigisign`(`id_dokumen`, id_skema, id_asesi, `url_ditandatangani`, `nama_dokumen`, `penandatangan`, `file`, `ip`) VALUES ('$iddokumen','$sk[id]','$_SESSION[namauser]','$escaped_url','FR.AK.02. FORMULIR REKAMAN ASESMEN KOMPETENSI','$_SESSION[namalengkap]','$file','$alamatip')";
																											$conn->query($sqlinputdigisign);
																											//unit kompetensi
																											$sqlgetunitkompetensi0 = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
																											$getunitkompetensi0 = $conn->query($sqlgetunitkompetensi0);
																											//while unitkompetensi ==================================================================
																											while ($unk0 = $getunitkompetensi0->fetch_assoc()) {
																												$sqlcekgetak01b = "SELECT * FROM `asesmen_ak02` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_unitkompetensi`='$unk0[id]'";
																												$cekgetak01b = $conn->query($sqlcekgetak01b);
																												$jumak02unit = $cekgetak01b->num_rows;
																												$varpostCL = 'checkboxCL' . $unk0['id'];
																												if (isset($_POST[$varpostCL])) {
																													$postCL = $_POST[$varpostCL];
																												} else {
																													$postCL = '0';
																												}
																												$varpostVP = 'checkboxVP' . $unk0['id'];
																												if (isset($_POST[$varpostVP])) {
																													$postVP = $_POST[$varpostVP];
																												} else {
																													$postVP = '0';
																												}
																												$varpostPW = 'checkboxPW' . $unk0['id'];
																												if (isset($_POST[$varpostPW])) {
																													$postPW = $_POST[$varpostPW];
																												} else {
																													$postPW = '0';
																												}
																												$varpostDPL = 'checkboxDPL' . $unk0['id'];
																												if (isset($_POST[$varpostDPL])) {
																													$postDPL = $_POST[$varpostDPL];
																												} else {
																													$postDPL = '0';
																												}
																												$varpostDPT = 'checkboxDPT' . $unk0['id'];
																												if (isset($_POST[$varpostDPT])) {
																													$postDPT = $_POST[$varpostDPT];
																												} else {
																													$postDPT = '0';
																												}
																												$varpostCUP = 'checkboxCUP' . $unk0['id'];
																												if (isset($_POST[$varpostCUP])) {
																													$postCUP = $_POST[$varpostCUP];
																												} else {
																													$postCUP = '0';
																												}
																												$varpostLainnya = 'checkboxLainnya' . $unk0['id'];
																												if (isset($_POST[$varpostLainnya])) {
																													$postLainnya = $_POST[$varpostLainnya];
																												} else {
																													$postLainnya = '0';
																												}
																												if ($jumak02unit > 0) {
																													// update data
																													$sqlinputak01 = "UPDATE `asesmen_ak02` SET `CL`='$postCL', `VP`='$postVP', `PW`='$postPW', `DPL`='$postDPL', `DPT`='$postDPT', `CUP`='$postCUP', `Lainnya`='$postLainnya',`tanggal`='$tglsekarang' WHERE `id_asesi`='$_GET[ida]' AND `id_unitkompetensi`='$unk0[id]' AND `id_jadwal`='$_GET[idj]'";
																													$conn->query($sqlinputak01);
																												} else {
																													// input data
																													$sqlinputak01 = "INSERT INTO `asesmen_ak02`(`id_asesi`, `id_unitkompetensi`, `id_jadwal`, `CL`, `VP`, `PW`, `DPL`, `DPT`, `CUP`, `Lainnya`, `tanggal`) VALUES ('$_GET[ida]', '$unk0[id]', '$_GET[idj]', '$postCL', '$postVP', '$postPW', '$postDPL', '$postDPT', '$postCUP', '$postLainnya', '$tglsekarang')";
																													$conn->query($sqlinputak01);
																												}
																											}
																											$sqlinputak02 = "UPDATE `asesi_asesmen` SET `umpan_balik`='$_POST[umpan_balik]', `tindak_lanjut_ak02`='$_POST[tindaklanjut]' WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																											$conn->query($sqlinputak02);
																											echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Formulir REKAMAN ASESMEN KOMPETENSI berhasil disimpan</h4>
				Terimakasih, Anda telah mengisi <b>Formulir REKAMAN ASESMEN KOMPETENSI untuk Skema $sk[judul].</b><br>
				<a class='btn btn-warning form-control' href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a></div>";
																										}
																									}

																									echo "<!-- Main content -->
			<section class='content'>
				<div class='row'>
					<div class='col-xs-12'>
						<div class='box'>
							<div class='box-body'>
								<!-- form start -->
								<div class='box-body'>
						<h2>FR.AK.02. FORMULIR REKAMAN ASESMEN KOMPETENSI</h2>
						<form role='form' method='POST' enctype='multipart/form-data'>
						<table id='example9' class='table table-bordered table-striped'>
							<tr><td width='25%'>Nama Asesi</td><td colspan='2'>$rowAgen[nama]</td></tr>
							<tr><td width='25%'>Nama Asesor</td><td colspan='2'>$namaasesor</td></tr>
							<tr><td width='25%'>Skema Sertifikasi (bila tersedia)</td><td colspan='2'>$sk[judul]</td></tr>
						</table>
						<table id='example9' class='table table-bordered table-striped'>";
																									//Data Asesmen
																									$sqlgetkeputusan = "SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																									$getkeputusan = $conn->query($sqlgetkeputusan);
																									$getk = $getkeputusan->fetch_assoc();
																									//unit kompetensi
																									$sqlgetunitkompetensi = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
																									$getunitkompetensi = $conn->query($sqlgetunitkompetensi);
																									$jumunitnya0 = $getunitkompetensi->num_rows;
																									$jumunitnya = $jumunitnya0 * 2; //kode dan judul unit adalah 2 baris
																									$noku = 1;
																									//while unitkompetensi ==================================================================
																									while ($unk = $getunitkompetensi->fetch_assoc()) {
																										if ($noku == 1) {
																											echo "<tr><td rowspan='" . $jumunitnya . "' valign='middle'>Unit Kompetensi</td><td>Kode Unit</td><td>" . $unk['kode_unit'] . "</td></tr>";
																											echo "<tr><td>Judul Unit</td><td>" . $unk['judul'] . "</td></tr>";
																										} else {
																											echo "<tr><td>Kode Unit</td><td>" . $unk['kode_unit'] . "</td></tr>";
																											echo "<tr><td>Judul Unit</td><td>" . $unk['judul'] . "</td></tr>";
																										}
																										$noku++;
																									}
																									//end while unitkompetensi =============================================
																									$tanggalasesmen = tgl_indo($jd['tgl_asesmen']);
																									$tanggalasesmenakhir = tgl_indo($jd['tgl_asesmen_akhir']);
																									echo "</table>
						<table id='example9' class='table table-bordered table-striped'>
							<tr><td width='25%'>Tanggal Mulainya</td><td colspan='2'>$tanggalasesmen</td></tr>
							<tr><td width='25%'>Tanggal Selesainya</td><td colspan='2'>$tanggalasesmenakhir</td></tr>
						</table>
						<h5>Beri tanda centang (V) di kolom yang sesuai untuk mencerminkan bukti yang diperoleh untuk menentukan Kompetensi asesi untuk setiap Unit Kompetensi.</h5>
						<table id='example9' class='table table-bordered table-striped'>
							<th>Unit Kompetensi</th><th>Observasi<br>demonstrasi</th><th>Portofolio</th><th>Pernyataan<br>Pihak<br>Ketiga<br>Pertanyaan<br>Wawancara</th><th>Pertanyaan<br>lisan</th><th>Pertanyaan<br>tertulis</th><th>Proyek<br>kerja</th><th>Lainnya</th>";
																									$sqlgetunitkompetensi2 = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
																									$getunitkompetensi2 = $conn->query($sqlgetunitkompetensi2);
																									//while unitkompetensi ==================================================================
																									while ($unk2 = $getunitkompetensi2->fetch_assoc()) {
																										$sqlgetak01 = "SELECT * FROM `asesmen_ak02` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_unitkompetensi`='$unk2[id]'";
																										$getak01 = $conn->query($sqlgetak01);
																										$jjw = $getak01->fetch_assoc();
																										echo "<tr><td>$unk2[judul]</td>";
																										echo "<td><input type='checkbox' class='flat-red' name='checkboxCL" . $unk2['id'] . "' id='optionsCL" . $unk2['id'] . "' value='1'";
																										if ($jjw['CL'] == "1") {
																											echo "checked";
																										} else {
																											echo "";
																										}
																										echo "></td>";
																										echo "<td><input type='checkbox' class='flat-red' name='checkboxVP" . $unk2['id'] . "' id='optionsVP" . $unk2['id'] . "' value='1'";
																										if ($jjw['VP'] == "1") {
																											echo "checked";
																										} else {
																											echo "";
																										}
																										echo "></td>";
																										echo "<td><input type='checkbox' class='flat-red' name='checkboxPW" . $unk2['id'] . "' id='optionsPW" . $unk2['id'] . "' value='1'";
																										if ($jjw['PW'] == "1") {
																											echo "checked";
																										} else {
																											echo "";
																										}
																										echo "></td>";
																										echo "<td><input type='checkbox' class='flat-red' name='checkboxDPL" . $unk2['id'] . "' id='optionsDPL" . $unk2['id'] . "' value='1'";
																										if ($jjw['DPL'] == "1") {
																											echo "checked";
																										} else {
																											echo "";
																										}
																										echo "></td>";
																										echo "<td><input type='checkbox' class='flat-red' name='checkboxDPT" . $unk2['id'] . "' id='optionsDPT" . $unk2['id'] . "' value='1'";
																										if ($jjw['DPT'] == "1") {
																											echo "checked";
																										} else {
																											echo "";
																										}
																										echo "></td>";
																										echo "<td><input type='checkbox' class='flat-red' name='checkboxCUP" . $unk2['id'] . "' id='optionsCUP" . $unk2['id'] . "' value='1'";
																										if ($jjw['CUP'] == "1") {
																											echo "checked";
																										} else {
																											echo "";
																										}
																										echo "></td>";
																										echo "<td><input type='checkbox' class='flat-red' name='checkboxLainnya" . $unk2['id'] . "' id='optionsLainnya" . $unk2['id'] . "' value='1'";
																										if ($jjw['Lainnya'] == "1") {
																											echo "checked";
																										} else {
																											echo "";
																										}
																										echo "></td>";
																										echo "</tr>";
																									}
																									echo "</table>
						<table id='example9' class='table table-bordered table-striped'>
							<tr><td width='25%'>Rekomendasi hasil asesmen</td><td colspan='2'>";
																									switch ($getk['keputusan_asesor']) {
																										default:
																											echo "Kompeten / Belum kompeten";
																											break;
																										case "R":
																											echo "Kompeten";
																											break;
																										case "NR":
																											echo "Belum kompeten";
																											break;
																									}
																									echo "</td></tr>
							<tr><td width='25%'><b>Tindak lanjut yang dibutuhkan</b><br>
							(Masukkan pekerjaan tambahan dan asesmen yang diperlukan untuk mencapai kompetensi)</td><td colspan='2'><textarea name='tindaklanjut' class='form-control'>$getk[tindak_lanjut_ak02]</textarea></td></tr>
							<tr><td width='25%'>Komentar/ Observasi oleh asesor</td><td colspan='2'><textarea name='umpan_balik' class='form-control'>$getk[umpan_balik]</textarea></td></tr>
						</table>
						<p><b>LAMPIRAN DOKUMEN:</b><br>
						<ol>";
																									echo "<li><a href='form-apl-01.php?ida=$getk[id_asesi]&idj=$_GET[idj]' target='_blank'>Dokumen APL 01 Peserta</a></li>";
																									$sqlgetapl02 = "SELECT  * FROM `asesi_apl02` WHERE `id_asesi`='$getk[id_asesi]' AND `id_jadwal`='$_GET[idj]'";
																									$getapl02 = $conn->query($sqlgetapl02);
																									$jumapl02 = $getapl02->num_rows;
																									if ($jumapl02 > 0) {
																										echo "<li><a href='form-apl-02.php?ida=$getk[id_asesi]&idj=$_GET[idj]' target='_blank'>Dokumen APL 02 Peserta</a></li>";
																									} else {
																										echo "<li>Dokumen APL 02 Peserta</li>";
																									}
																									echo "<li><a href='portfolio-asesi.php?ida=$getk[id_asesi]&idj=$_GET[idj]' target='_blank'>Bukti-bukti berkualitas Peserta</a></li>";
																									$sqlgetia11 = "SELECT * FROM `asesmen_ia11` WHERE `id_asesi`='$getk[id_asesi]' AND `id_jadwal`='$_GET[idj]'";
																									$getia11 = $conn->query($sqlgetia11);
																									$jumia11 = $getia11->num_rows;
																									if ($jumia11 > 0) {
																										echo "<li><a href='form-fr-ia-11.php?ida=$getk[id_asesi]&idj=$_GET[idj]' target='_blank'>Tinjauan proses asesmen</a></li>";
																									} else {
																										echo "<li>Tinjauan proses asesmen</li>";
																									}
																									echo "</ol></p>
						<p><b>Asesor :</b>
						</p>";
																									// cek tandatangan digital
																									$url = "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
																									$iddokumen = md5($url);
																									$sqlcektandatangan = "SELECT * FROM `logdigisign` WHERE `id_dokumen`='$iddokumen' ORDER BY `id` DESC";
																									$cektandatangan = $conn->query($sqlcektandatangan);
																									$jumttd = $cektandatangan->num_rows;
																									$ttdx = $cektandatangan->fetch_assoc();
																									if ($jumttd > 0) {
																										echo "<div class='col-md-12'>
									<label class='' for=''>Persetujuan/ Tanda Tangan yang telah Anda berikan:</label>
									<br/>
									<img src='$ttdx[file]' width='400px'/>
									<br/>
								</div>";
																									} else {
																										echo "<div class='col-md-12'>
							<label class='' for=''>Tanda Tangan:</label>
							<br/>
							<div id='sig' ></div>
							<br/>
							<button id='clear'>Hapus Tanda Tangan</button>
							<textarea id='signature64' name='signed' style='display: none'></textarea>
						</div>
						<script type='text/javascript'>
							var sig = $('#sig').signature({syncField: '#signature64', syncFormat: 'PNG', color: '#58009F'});
							$('#clear').click(function(e) {
								e.preventDefault();
								sig.signature('clear');
								$('#signature64').val('');
							});
						</script></div>";
																									}
																									echo "<div class='box-footer'>
						<div class='col-md-4 col-sm-12 col-xs-12'>
								<a class='btn btn-danger form-control' id=reset-validate-form href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a>
						</div>
						<div class='col-md-4 col-sm-12 col-xs-12'>
								<a href='form-fr-ak-02.php?ida=$_GET[ida]&idj=$_GET[idj]' class='btn btn-primary form-control' target='_blank'>Unduh Formulir</a>
						</div>
						<div class='col-md-4 col-sm-12 col-xs-12'>
								<button type='submit' class='btn btn-success form-control' name='simpan'>Simpan Jawaban</button>
						</div>
					</div>
				</form>			
							</div>
							<!-- /.box-body -->
						</div>
						<!-- /.box -->
					</div>
					<!-- /.col -->
				</div>
				<!-- /.row -->
			</section>
			<!-- /.content -->";
																								}
																								// Bagian Input Hasil Asesmen FORM-FR.AK.05 Asesor
																								elseif ($_GET['module'] == 'form-fr-ak-05') {
																									$sqlgetjadwal = "SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
																									$getjadwal = $conn->query($sqlgetjadwal);
																									$jd = $getjadwal->fetch_assoc();
																									$sqlgetskema = "SELECT * FROM `skema_kkni` WHERE `id`='$jd[id_skemakkni]'";
																									$getskema = $conn->query($sqlgetskema);
																									$sk = $getskema->fetch_assoc();
																									$sqlgetasesordata = "SELECT * FROM `asesor` WHERE `no_ktp`='$_SESSION[namauser]'";
																									$getasesordata = $conn->query($sqlgetasesordata);
																									$asr = $getasesordata->fetch_assoc();
																									$sqltuk = "SELECT * FROM `tuk` WHERE `id`='$jd[tempat_asesmen]'";
																									$tuk = $conn->query($sqltuk);
																									$tq = $tuk->fetch_assoc();
																									$sqltukjenis = "SELECT `jenis_tuk` FROM `tuk_jenis` WHERE `id`='$tq[jenis_tuk]'";
																									$tukjenis = $conn->query($sqltukjenis);
																									$tukjen = $tukjenis->fetch_assoc();
																									$noasr = 1;
																									$getasesor = $conn->query("SELECT * FROM `jadwal_asesor` WHERE `id_jadwal`='$_GET[idj]'");
																									while ($gas = $getasesor->fetch_assoc()) {
																										$sqlasesor = "SELECT * FROM `asesor` WHERE `id`='$gas[id_asesor]'";
																										$asesor = $conn->query($sqlasesor);
																										$asr = $asesor->fetch_assoc();
																										if (!empty($asr['gelar_depan'])) {
																											if (!empty($asr['gelar_blk'])) {
																												$namaasesor = $asr['gelar_depan'] . " " . $asr['nama'] . ", " . $asr['gelar_blk'];
																											} else {
																												$namaasesor = $asr['gelar_depan'] . " " . $asr['nama'];
																											}
																										} else {
																											if (!empty($asr['gelar_blk'])) {
																												$namaasesor = $asr['nama'] . ", " . $asr['gelar_blk'];
																											} else {
																												$namaasesor = $asr['nama'];
																											}
																										}
																										$noregasesor = $asr['no_induk'];
																										$namaasesor = $noasr . '. ' . $namaasesor;
																										$noregasesor = $noasr . '. ' . $noregasesor;
																										$noasr++;
																									}
																									echo "<!-- Content Header (Page header) -->
			<section class='content-header'>
				<h1>
			Input Laporan Asesmen
					<small>Skema $sk[judul]</small>
				</h1>
				<ol class='breadcrumb'>
					<li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
					<li class='active'>FR.AK.05</li>
				</ol>
			</section>";
																									if (isset($_REQUEST['simpan'])) {
																										$sqlcekgetak01 = "SELECT * FROM `asesmen_ak05` WHERE `id_asesor`='$asr[id]' AND `id_jadwal`='$_GET[idj]'";
																										$cekgetak01 = $conn->query($sqlcekgetak01);
																										$cekak01 = $cekgetak01->num_rows;
																										$tglsekarang = date("Y-m-d");
																										if ($cekak01 > 0) {
																											$folderPath = "../foto_tandatangan/";
																											if (empty($_POST['signed'])) {
																												echo "<div class='alert alert-success alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Laporan Asesmen berhasil disimpan</h4>
					Terimakasih, Anda telah melakukan input <b>Laporan Asesmen untuk Skema $sk[judul].</b><br>
					<a class='btn btn-warning form-control' href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a></div>";
																												$sqlinputak01 = "UPDATE `asesmen_ak05` SET `aspek`='$_POST[aspek]',`catatan_penolakan`='$_POST[catatan_penolakan]',`saran_perbaikan`='$_POST[saran_perbaikan]',`catatan`='$_POST[catatan]',`tanggal`='$tglsekarang' WHERE `id_asesor`='$asr[id]' AND `id_skemakkni`='$jd[id_skemakkni]' AND `id_jadwal`='$_GET[idj]'";
																												$conn->query($sqlinputak01);
																											} else {
																												$image_parts = explode(";base64,", $_POST['signed']);
																												$image_type_aux = explode("image/", $image_parts[0]);
																												$image_type = $image_type_aux[1];
																												$image_base64 = base64_decode($image_parts[1]);
																												$file = $folderPath . uniqid() . '.' . $image_type;
																												file_put_contents($file, $image_base64);
																												$url =  "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
																												$iddokumen = md5($url);
																												$escaped_url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
																												$alamatip = $_SERVER['REMOTE_ADDR'];
								
																												$sqlinputdigisign = "INSERT INTO `logdigisign`(`id_dokumen`, id_skema, id_asesi, `url_ditandatangani`, `nama_dokumen`, `penandatangan`, `file`, `ip`, `id_jadwal`) VALUES ('$iddokumen','$jd[id_skemakkni]','$_SESSION[namauser]','$escaped_url','FR.AK.05. LAPORAN ASESMEN','$_SESSION[namalengkap]','$file','$alamatip','$_GET[idj]')";
																												$conn->query($sqlinputdigisign);
																												echo "<div class='alert alert-success alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Laporan Asesmen berhasil disimpan</h4>
					Terimakasih, Anda telah melakukan <b>Laporan Asesmen untuk Skema $sk[judul], dan tanda tangan telah ditambahkan</b><br>
					<a class='btn btn-warning form-control' href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a></div>";
																												$sqlinputak01 = "UPDATE `asesmen_ak05` SET `aspek`='$_POST[aspek]',`catatan_penolakan`='$_POST[catatan_penolakan]',`saran_perbaikan`='$_POST[saran_perbaikan]',`catatan`='$_POST[catatan]',`tanggal`='$tglsekarang' WHERE `id_asesor`='$asr[id]' AND `id_skemakkni`='$jd[id_skemakkni]' AND `id_jadwal`='$_GET[idj]'";
																												$conn->query($sqlinputak01);
																											}
																										} else {
																											$folderPath = "../foto_tandatangan/";
																											if (empty($_POST['signed'])) {
																												echo "<div class='alert alert-success alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Laporan Asesmen berhasil disimpan</h4>
					Terimakasih, Anda telah melakukan <b>Laporan Asesmen untuk Skema $sk[judul].</b><br>
					<a class='btn btn-warning form-control' href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a></div>";
																												$sqlinputak01 = "INSERT INTO `asesmen_ak05`(`id_asesor`, `id_skemakkni`, `id_jadwal`, `aspek`,`catatan_penolakan`,`saran_perbaikan`,`catatan`,`tanggal`) VALUES ('$asr[id]','$jd[id_skemakkni]','$_GET[idj]','$_POST[aspek]','$_POST[catatan_penolakan]','$_POST[saran_perbaikan]','$_POST[catatan]','$tglsekarang')";
																												$conn->query($sqlinputak01);
																											} else {
																												$image_parts = explode(";base64,", $_POST['signed']);
																												$image_type_aux = explode("image/", $image_parts[0]);
																												$image_type = $image_type_aux[1];
																												$image_base64 = base64_decode($image_parts[1]);
																												$file = $folderPath . uniqid() . '.' . $image_type;
																												file_put_contents($file, $image_base64);
																												$url =  "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
																												$iddokumen = md5($url);
																												$escaped_url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
																												$alamatip = $_SERVER['REMOTE_ADDR'];
																												$sqlinputdigisign = "INSERT INTO `logdigisign`(`id_dokumen`, id_skema, id_asesi`url_ditandatangani`, `nama_dokumen`, `penandatangan`, `file`, `ip`) VALUES ('$iddokumen','$jd[id_skemakkni]','$_SESSION[namauser]','$escaped_url','FR.AK.05. LAPORAN ASESMEN','$_SESSION[namalengkap]','$file','$alamatip')";
																												$conn->query($sqlinputdigisign);
																												echo "<div class='alert alert-success alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Laporan Asesmen berhasil disimpan</h4>
					Terimakasih, Anda telah melakukan <b>Laporan Asesmen untuk Skema $sk[judul], dan tanda tangan telah ditambahkan</b><br>
					<a class='btn btn-warning form-control' href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a></div>";
																												$sqlinputak01 = "INSERT INTO `asesmen_ak05`(`id_asesor`, `id_skemakkni`, `id_jadwal`, `aspek`,`catatan_penolakan`,`saran_perbaikan`,`catatan`,`tanggal`) VALUES ('$asr[id]','$jd[id_skemakkni]','$_GET[idj]','$_POST[aspek]','$_POST[catatan_penolakan]','$_POST[saran_perbaikan]','$_POST[catatan]','$tglsekarang')";
																												$conn->query($sqlinputak01);
																											}
																										}
																									}
																									$sqlgetak01 = "SELECT * FROM `asesmen_ak05` WHERE `id_asesor`='$asr[id]' AND `id_jadwal`='$_GET[idj]'";
																									$getak01 = $conn->query($sqlgetak01);
																									$jjw = $getak01->fetch_assoc();
																									echo "<!-- Main content -->
			<section class='content'>
				<div class='row'>
					<div class='col-xs-12'>
						<div class='box'>
							<div class='box-body'>
								<!-- form start -->
								<div class='box-body'>
						<h2>FR.AK.05. LAPORAN ASESMEN</h2>
						<form role='form' method='POST' enctype='multipart/form-data'>
						<table id='example9' class='table table-bordered table-striped'>
							<tr><td rowspan='2' width='25%'>Skema Sertifikasi (KKNI/Okupasi/Klaster)</td><td>Judul :</td><td>$sk[judul]</td></tr>
							<tr><td width='25%'>Nomor :</td><td>$sk[kode_skema]</td></tr>
							<tr><td width='25%'>TUK</td><td colspan='2'>$tukjen[jenis_tuk]</td></tr>
							<tr><td width='25%'>Nama Asesor</td><td colspan='2'>$namaasesor</td></tr>
						</table>";
						// <table id='example9' class='table table-bordered table-striped'>
						// 																			$sqlgetunitkompetensi = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
						// 																			$getunitkompetensi = $conn->query($sqlgetunitkompetensi);
						// 																			$jumunitnya0 = $getunitkompetensi->num_rows;
						// 																			$jumunitnya = $jumunitnya0 * 2; //kode dan judul unit adalah 2 baris
						// 																			$noku = 1;
						// 																			//while unitkompetensi ==================================================================
						// 																			while ($unk = $getunitkompetensi->fetch_assoc()) {
						// 																				if ($noku == 1) {
						// 																					echo "<tr><td rowspan='" . $jumunitnya . "' valign='middle'>Unit Kompetensi</td><td>Kode Unit</td><td>" . $unk['kode_unit'] . "</td></tr>";
						// 																					echo "<tr><td>Judul Unit</td><td>" . $unk['judul'] . "</td></tr>";
						// 																				} else {
						// 																					echo "<tr><td>Kode Unit</td><td>" . $unk['kode_unit'] . "</td></tr>";
						// 																					echo "<tr><td>Judul Unit</td><td>" . $unk['judul'] . "</td></tr>";
						// 																				}
						// 																				$noku++;
						// 																			}
						// 																			//end while unitkompetensi =============================================
						// 																			echo "</table>
						echo "<table id='example9' class='table table-bordered table-striped'>
							<thead><tr><th rowspan='2'>No.</th><th rowspan='2'>Nama Asesi</th><th colspan='2'>Rekomendasi</th><th rowspan='2'>Keterangan</th></tr>
							<tr><th>K</th><th>BK</th></tr></thead>
							<tbody>";
																									$noasi = 1;
																									$sqlasesiasesmen = "SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`='$_GET[idj]' ORDER BY `id_asesi` ASC";
																									$asesiasesmenx = $conn->query($sqlasesiasesmen);
																									while ($aas = $asesiasesmenx->fetch_assoc()) {
																										$sqlasesi = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$aas[id_asesi]'";
																										$asesi = $conn->query($sqlasesi);
																										$as = $asesi->fetch_assoc();
																										$getcatatanbukti = "SELECT * FROM `asesi_apl02` WHERE `id_asesi`='$aas[id_asesi]' AND `id_jadwal`='$_GET[idj]'";
																										$catatanbukti = $conn->query($getcatatanbukti);
																										$cbkt = $catatanbukti->fetch_assoc();
																										echo "<tr><td>$noasi</td>";
																										echo "<td>$as[nama]</td>";
																										switch ($aas['keputusan_asesor']) {
																											default:
																												echo "<td><img src='../images/unchecked.jpg' width='25px'/></td>";
																												echo "<td><img src='../images/unchecked.jpg' width='25px'/></td>";
																												echo "<td>" . $aas['umpan_balik'] . "</td>";
																												break;
																											case "R":
																												echo "<td><img src='../images/checked.jpg' width='25px'/></td>";
																												echo "<td><img src='../images/unchecked.jpg' width='25px'/></td>";
																												echo "<td>" . $aas['umpan_balik'] . "</td>";
																												break;
																											case "NR":
																												echo "<td><img src='../images/unchecked.jpg' width='25px'/></td>";
																												echo "<td><img src='../images/checked.jpg' width='25px'/></td>";
																												// mendapatkan data BK pada penilaian asesor
																												$sqlgetdatabkasesi = "SELECT * FROM `asesi_apl02` WHERE `id_asesi`='$aas[id_asesi]' AND `id_jadwal`='$_GET[idj]' AND `keputusan`='BK'";
																												$getdatabkasesi = $conn->query($sqlgetdatabkasesi);
																												$jumbk = $getdatabkasesi->num_rows;
																												$nobknya = 1;
																												$databk = "";
																												while ($getbk = $getdatabkasesi->fetch_assoc()) {
																													$sqlgetelemen = "SELECT * FROM `elemen_kompetensi` WHERE `id`='$getbk[id_elemen]'";
																													$getelemen = $conn->query($sqlgetelemen);
																													$elemen = $getelemen->fetch_assoc();
																													$ketbk = "(" . $nobknya . ") belum kompeten " . $elemen['elemen_kompetensi'];
																													$databk = $databk . $ketbk . "; ";
																													$nobknya++;
																												}
																												$keteranganbk = $aas['umpan_balik'] . ": " . $databk;
																												echo "<td>" . $keteranganbk . "</td>";
																												break;
																										}
																										echo "</tr>";
																										$noasi++;
																									}
																									echo "</tbody>
						</table>
						<div class='col-md-12'>
						<label><b>Aspek Negatif dan Positif dalam Asesemen :</b></label><br />
						<textarea class='form-control' name='aspek'>";
																									if (!empty($jjw['aspek'])) {
																										echo $jjw['aspek'];
																									} else {
																										echo "";
																									}
																									echo "</textarea>
						</div>
						<div class='col-md-12'>
						<label><b>Pencatatan Penolakan Hasil Asesmen :</b></label><br />
						<textarea class='form-control' name='catatan_penolakan'>";
																									if (!empty($jjw['catatan_penolakan'])) {
																										echo $jjw['catatan_penolakan'];
																									} else {
																										echo "";
																									}
																									echo "</textarea>
						</div>
						<div class='col-md-12'>
						<label><b>Saran Perbaikan (Asesor/Personil Terkait) :</b></label><br />
						<textarea class='form-control' name='saran_perbaikan'>";
																									if (!empty($jjw['saran_perbaikan'])) {
																										echo $jjw['saran_perbaikan'];
																									} else {
																										echo "";
																									}
																									echo "</textarea>
						</div>
						<div class='col-md-12'>
						<label><b>Catatan: :</b></label><br />
						<textarea class='form-control' name='catatan'>";
																									if (!empty($jjw['catatan'])) {
																										echo $jjw['catatan'];
																									} else {
																										echo "";
																									}
																									echo "</textarea>
						</div>";
																									// cek tandatangan digital
																									$url = "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
																									$iddokumen = md5($url);
																									// $idj = "SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'"; 
																									// var_dump($idj);
																									$sqlcektandatangan = "SELECT * FROM `logdigisign` WHERE `id_skema`='$jd[id_skemakkni]' AND `id_asesi`='$_SESSION[namauser]' AND nama_dokumen='FR.AK.05. LAPORAN ASESMEN' ORDER BY `id` DESC";
																									$cektandatangan = $conn->query($sqlcektandatangan);
																									$jumttd = $cektandatangan->num_rows;
																									$ttdx = $cektandatangan->fetch_assoc();
																									if ($jumttd > 0) {
																										echo "<div class='col-md-12'>
									<label class='' for=''>Persetujuan/ Tanda Tangan yang telah Anda berikan:</label>
									<br/>
									<img src='$ttdx[file]' width='400px'/>
									<br/>
								</div>";
																									} else {
																										echo "<div class='col-md-12'>
							<label class='' for=''>Tanda Tangan:</label>
							<br/>
							<div id='sig' ></div>
							<br/>
							<button id='clear'>Hapus Tanda Tangan</button>
							<textarea id='signature64' name='signed' style='display: none'></textarea>
						</div>
						<script type='text/javascript'>
							var sig = $('#sig').signature({syncField: '#signature64', syncFormat: 'PNG', color: '#58009F'});
							$('#clear').click(function(e) {
								e.preventDefault();
								sig.signature('clear');
								$('#signature64').val('');
							});
						</script></div>";
																									}
																									echo "<div class='box-footer'>
						<div class='col-md-4 col-sm-12 col-xs-12'>
								<a class='btn btn-danger form-control' id=reset-validate-form href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a>
						</div>
						<div class='col-md-4 col-sm-12 col-xs-12'>
								<a href='form-ak-05.php?&idj=$_GET[idj]' class='btn btn-primary form-control'>Unduh Formulir</a>
						</div>
						<div class='col-md-4 col-sm-12 col-xs-12'>
								<button type='submit' class='btn btn-success form-control' name='simpan'>Simpan Jawaban</button>
						</div>
					</div>
				</form>			
							</div>
							<!-- /.box-body -->
						</div>
						<!-- /.box -->
					</div>
					<!-- /.col -->
				</div>
				<!-- /.row -->
			</section>
			<!-- /.content -->";
																								}
																								// Bagian Input Hasil Asesmen FORM-FR.AK.06 Asesor
																								elseif ($_GET['module'] == 'form-fr-ak-06') {
																									$sqlgetjadwal = "SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
																									$getjadwal = $conn->query($sqlgetjadwal);
																									$jd = $getjadwal->fetch_assoc();
																									$sqlgetskema = "SELECT * FROM `skema_kkni` WHERE `id`='$jd[id_skemakkni]'";
																									$getskema = $conn->query($sqlgetskema);
																									$sk = $getskema->fetch_assoc();
																									$sqlgetasesordata = "SELECT * FROM `asesor` WHERE `no_ktp`='$_SESSION[namauser]'";
																									$getasesordata = $conn->query($sqlgetasesordata);
																									$asr = $getasesordata->fetch_assoc();
																									$sqltuk = "SELECT * FROM `tuk` WHERE `id`='$jd[tempat_asesmen]'";
																									$tuk = $conn->query($sqltuk);
																									$tq = $tuk->fetch_assoc();
																									$sqltukjenis = "SELECT `jenis_tuk` FROM `tuk_jenis` WHERE `id`='$tq[jenis_tuk]'";
																									$tukjenis = $conn->query($sqltukjenis);
																									$tukjen = $tukjenis->fetch_assoc();
																									$noasr = 1;
																									$getasesor = $conn->query("SELECT * FROM `jadwal_asesor` WHERE `id_jadwal`='$_GET[idj]'");
																									while ($gas = $getasesor->fetch_assoc()) {
																										$sqlasesor = "SELECT * FROM `asesor` WHERE `id`='$gas[id_asesor]'";
																										$asesor = $conn->query($sqlasesor);
																										$asr = $asesor->fetch_assoc();
																										if (!empty($asr['gelar_depan'])) {
																											if (!empty($asr['gelar_blk'])) {
																												$namaasesor = $asr['gelar_depan'] . " " . $asr['nama'] . ", " . $asr['gelar_blk'];
																											} else {
																												$namaasesor = $asr['gelar_depan'] . " " . $asr['nama'];
																											}
																										} else {
																											if (!empty($asr['gelar_blk'])) {
																												$namaasesor = $asr['nama'] . ", " . $asr['gelar_blk'];
																											} else {
																												$namaasesor = $asr['nama'];
																											}
																										}
																										$noregasesor = $asr['no_induk'];
																										$namaasesor = $noasr . '. ' . $namaasesor;
																										$noregasesor = $noasr . '. ' . $noregasesor;
																										$noasr++;
																									}
																									echo "<!-- Content Header (Page header) -->
			<section class='content-header'>
				<h1>
			Input Laporan Meninjau Proses Asesmen
					<small>Skema $sk[judul]</small>
				</h1>
				<ol class='breadcrumb'>
					<li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
					<li class='active'>FR.AK.06</li>
				</ol>
			</section>";
																									if (isset($_REQUEST['simpan'])) {
																										$sqlcekgetak01 = "SELECT * FROM `asesmen_ak06` WHERE `id_asesor`='$asr[id]' AND `id_jadwal`='$_GET[idj]'";
																										$cekgetak01 = $conn->query($sqlcekgetak01);
																										$cekak01 = $cekgetak01->num_rows;
																										$tglsekarang = date("Y-m-d");
																										if (isset($_POST['rencana_v'])) {
																											$rencana_v = $_POST['rencana_v'];
																										} else {
																											$rencana_v = '0';
																										}
																										if (isset($_POST['rencana_r'])) {
																											$rencana_r = $_POST['rencana_r'];
																										} else {
																											$rencana_r = '0';
																										}
																										if (isset($_POST['rencana_f'])) {
																											$rencana_f = $_POST['rencana_f'];
																										} else {
																											$rencana_f = '0';
																										}
																										if (isset($_POST['rencana_a'])) {
																											$rencana_a = $_POST['rencana_a'];
																										} else {
																											$rencana_a = '0';
																										}
																										if (isset($_POST['persiapan_v'])) {
																											$persiapan_v = $_POST['persiapan_v'];
																										} else {
																											$persiapan_v = '0';
																										}
																										if (isset($_POST['persiapan_r'])) {
																											$persiapan_r = $_POST['persiapan_r'];
																										} else {
																											$persiapan_r = '0';
																										}
																										if (isset($_POST['persiapan_f'])) {
																											$persiapan_f = $_POST['persiapan_f'];
																										} else {
																											$persiapan_f = '0';
																										}
																										if (isset($_POST['persiapan_a'])) {
																											$persiapan_a = $_POST['persiapan_a'];
																										} else {
																											$persiapan_a = '0';
																										}
																										if (isset($_POST['implementasi_v'])) {
																											$implementasi_v = $_POST['implementasi_v'];
																										} else {
																											$implementasi_v = '0';
																										}
																										if (isset($_POST['implementasi_r'])) {
																											$implementasi_r = $_POST['implementasi_r'];
																										} else {
																											$implementasi_r = '0';
																										}
																										if (isset($_POST['implementasi_f'])) {
																											$implementasi_f = $_POST['implementasi_f'];
																										} else {
																											$implementasi_f = '0';
																										}
																										if (isset($_POST['implementasi_a'])) {
																											$implementasi_a = $_POST['implementasi_a'];
																										} else {
																											$implementasi_a = '0';
																										}
																										if (isset($_POST['keputusan_v'])) {
																											$keputusan_v = $_POST['keputusan_v'];
																										} else {
																											$keputusan_v = '0';
																										}
																										if (isset($_POST['keputusan_r'])) {
																											$keputusan_r = $_POST['keputusan_r'];
																										} else {
																											$keputusan_r = '0';
																										}
																										if (isset($_POST['keputusan_a'])) {
																											$keputusan_a = $_POST['keputusan_a'];
																										} else {
																											$keputusan_a = '0';
																										}
																										if (isset($_POST['umpanbalik_v'])) {
																											$umpanbalik_v = $_POST['umpanbalik_v'];
																										} else {
																											$umpanbalik_v = '0';
																										}
																										if (isset($_POST['umpanbalik_r'])) {
																											$umpanbalik_r = $_POST['umpanbalik_r'];
																										} else {
																											$umpanbalik_r = '0';
																										}
																										if (isset($_POST['umpanbalik_a'])) {
																											$umpanbalik_a = $_POST['umpanbalik_a'];
																										} else {
																											$umpanbalik_a = '0';
																										}
																										if (isset($_POST['rekomdimensi1'])) {
																											$rekomdimensi1 = $_POST['rekomdimensi1'];
																										} else {
																											$rekomdimensi1 = '0';
																										}
																										if (isset($_POST['rekomdimensi2'])) {
																											$rekomdimensi2 = $_POST['rekomdimensi2'];
																										} else {
																											$rekomdimensi2 = '0';
																										}
																										if (isset($_POST['rekomdimensi2a'])) {
																											$rekomdimensi2a = $_POST['rekomdimensi2a'];
																										} else {
																											$rekomdimensi2a = '0';
																										}
																										if (isset($_POST['rekomdimensi2b'])) {
																											$rekomdimensi2b = $_POST['rekomdimensi2b'];
																										} else {
																											$rekomdimensi2b = '0';
																										}
																										if (isset($_POST['rekomdimensi2c'])) {
																											$rekomdimensi2c = $_POST['rekomdimensi2c'];
																										} else {
																											$rekomdimensi2c = '0';
																										}
																										if ($cekak01 > 0) {
																											$folderPath = "../foto_tandatangan/";
																											if (empty($_POST['signed'])) {
																												echo "<div class='alert alert-success alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Laporan Meninjau Proses Asesmen berhasil disimpan</h4>
					Terimakasih, Anda telah melakukan input <b>Laporan Meninjau Proses Asesmen untuk Skema $sk[judul].</b><br>
					<a class='btn btn-warning form-control' href='?module=jadwalasesmen'>Kembali</a></div>";
																												$sqlinputak01 = "UPDATE `asesmen_ak06` SET `rencana_v`='$rencana_v',`rencana_r`='$rencana_r',`rencana_f`='$rencana_f',`rencana_a`='$rencana_a',`persiapan_v`='$persiapan_v',`persiapan_r`='$persiapan_r',`persiapan_f`='$persiapan_f',`persiapan_a`='$persiapan_a',`implementasi_v`='$implementasi_v',`implementasi_r`='$implementasi_r',`implementasi_f`='$implementasi_f',`implementasi_a`='$implementasi_a',`keputusan_v`='$keputusan_v',`keputusan_r`='$keputusan_r',`keputusan_a`='$keputusan_a',`umpanbalik_v`='$umpanbalik_v',`umpanbalik_r`='$umpanbalik_r',`umpanbalik_a`='$umpanbalik_a',`rekomendasi`='$_POST[rekomendasi]',`ts`='$_POST[ts]',`tms`='$_POST[tms]',`cms`='$_POST[cms]',`jres`='$_POST[jres]',`trs`='$_POST[trs]',`rekomdimensi1`='$rekomdimensi1',`rekomdimensi2`='$rekomdimensi2',`rekomdimensi2a`='$rekomdimensi2a',`rekomdimensi2b`='$rekomdimensi2b',`rekomdimensi2c`='$rekomdimensi2c',`aspek`='$_POST[aspek]',`catatan_penolakan`='$_POST[catatan_penolakan]',`saran_perbaikan`='$_POST[saran_perbaikan]',`catatan`='$_POST[catatan]',`tanggal`='$tglsekarang' WHERE `id_asesor`='$asr[id]' AND `id_skemakkni`='$jd[id_skemakkni]' AND `id_jadwal`='$_GET[idj]'";
																												$conn->query($sqlinputak01);
																											} else {
																												$image_parts = explode(";base64,", $_POST['signed']);
																												$image_type_aux = explode("image/", $image_parts[0]);
																												$image_type = $image_type_aux[1];
																												$image_base64 = base64_decode($image_parts[1]);
																												$file = $folderPath . uniqid() . '.' . $image_type;
																												file_put_contents($file, $image_base64);
																												$url =  "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
																												$iddokumen = md5($url);
																												$escaped_url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
																												$alamatip = $_SERVER['REMOTE_ADDR'];
																												$sqlinputdigisign = "INSERT INTO `logdigisign`(`id_dokumen`, id_skema, id_asesi `url_ditandatangani`, `nama_dokumen`, `penandatangan`, `file`, `ip`, `id_jadwal`) VALUES ('$iddokumen','$sk[id]','$_SESSION[namauser]','$escaped_url','FR.AK.06. MENINJAU PROSES ASESMEN','$_SESSION[namalengkap]','$file','$alamatip','$_GET[idj]')";
																												// var_dump($sqlinputdigisign);
																												// die;
																												$conn->query($sqlinputdigisign);
																												echo "<div class='alert alert-success alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Laporan Meninjau Proses Asesmen berhasil disimpan</h4>
					Terimakasih, Anda telah melakukan <b>Laporan Meninjau Proses Asesmen untuk Skema $sk[judul], dan tanda tangan telah ditambahkan</b><br>
					<a class='btn btn-warning form-control' href='?module=jadwalasesmen'>Kembali</a></div>";
																												$sqlinputak01 = "UPDATE `asesmen_ak06` SET `rencana_v`='$rencana_v',`rencana_r`='$rencana_r',`rencana_f`='$rencana_f',`rencana_a`='$rencana_a',`persiapan_v`='$persiapan_v',`persiapan_r`='$persiapan_r',`persiapan_f`='$persiapan_f',`persiapan_a`='$persiapan_a',`implementasi_v`='$implementasi_v',`implementasi_r`='$implementasi_r',`implementasi_f`='$implementasi_f',`implementasi_a`='$implementasi_a',`keputusan_v`='$keputusan_v',`keputusan_r`='$keputusan_r',`keputusan_a`='$keputusan_a',`umpanbalik_v`='$umpanbalik_v',`umpanbalik_r`='$umpanbalik_r',`umpanbalik_a`='$umpanbalik_a',`rekomendasi`='$_POST[rekomendasi]',`ts`='$_POST[ts]',`tms`='$_POST[tms]',`cms`='$_POST[cms]',`jres`='$_POST[jres]',`trs`='$_POST[trs]',`rekomdimensi1`='$rekomdimensi1',`rekomdimensi2`='$rekomdimensi2',`rekomdimensi2a`='$rekomdimensi2a',`rekomdimensi2b`='$rekomdimensi2b',`rekomdimensi2c`='$rekomdimensi2c',`aspek`='$_POST[aspek]',`catatan_penolakan`='$_POST[catatan_penolakan]',`saran_perbaikan`='$_POST[saran_perbaikan]',`catatan`='$_POST[catatan]',`tanggal`='$tglsekarang' WHERE `id_asesor`='$asr[id]' AND `id_skemakkni`='$jd[id_skemakkni]' AND `id_jadwal`='$_GET[idj]'";
																												$conn->query($sqlinputak01);
																											}
																										} else {
																											$folderPath = "../foto_tandatangan/";
																											if (empty($_POST['signed'])) {
																												echo "<div class='alert alert-success alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Laporan Meninjau Proses Asesmen berhasil disimpan</h4>
					Terimakasih, Anda telah melakukan <b>Laporan Meninjau Proses Asesmen untuk Skema $sk[judul].</b><br>
					<a class='btn btn-warning form-control' href='?module=jadwalasesmen'>Kembali</a></div>";
																												$sqlinputak01 = "INSERT INTO `asesmen_ak06`(`id_asesor`, `id_skemakkni`, `id_jadwal`, `rencana_v`, `rencana_r`, `rencana_f`, `rencana_a`, `persiapan_v`, `persiapan_r`, `persiapan_f`, `persiapan_a`, `implementasi_v`, `implementasi_r`, `implementasi_f`, `implementasi_a`, `keputusan_v`, `keputusan_r`, `keputusan_a`, `umpanbalik_v`, `umpanbalik_r`, `umpanbalik_a`, `rekomendasi`, `ts`, `tms`, `cms`, `jres`, `trs`, `rekomdimensi1`, `rekomdimensi2`, `rekomdimensi2a`, `rekomdimensi2b`, `rekomdimensi2c`, `aspek`,`catatan_penolakan`,`saran_perbaikan`,`catatan`,`tanggal`) VALUES ('$asr[id]','$jd[id_skemakkni]','$_GET[idj]','$rencana_v', '$rencana_r', '$rencana_f', '$rencana_a', '$persiapan_v', '$persiapan_r', '$persiapan_f', '$persiapan_a', '$implementasi_v', '$implementasi_r', '$implementasi_f', '$implementasi_a', '$keputusan_v', '$keputusan_r', '$keputusan_a', '$umpanbalik_v', '$umpanbalik_r', '$umpanbalik_a', '$_POST[rekomendasi]', '$_POST[ts]', '$_POST[tms]', '$_POST[cms]', '$_POST[jres]', '$_POST[trs]', '$rekomdimensi1', '$rekomdimensi2', '$rekomdimensi2a', '$rekomdimensi2b', '$rekomdimensi2c', '$_POST[aspek]','$_POST[catatan_penolakan]','$_POST[saran_perbaikan]','$_POST[catatan]','$tglsekarang')";
																												$conn->query($sqlinputak01);
																											} else {
																												$image_parts = explode(";base64,", $_POST['signed']);
																												$image_type_aux = explode("image/", $image_parts[0]);
																												$image_type = $image_type_aux[1];
																												$image_base64 = base64_decode($image_parts[1]);
																												$file = $folderPath . uniqid() . '.' . $image_type;
																												file_put_contents($file, $image_base64);
																												$url =  "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
																												$iddokumen = md5($url);
																												$escaped_url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
																												$alamatip = $_SERVER['REMOTE_ADDR'];
																												$sqlinputdigisign = "INSERT INTO `logdigisign`(`id_dokumen`, id_skema, id_asesi,`url_ditandatangani`, `nama_dokumen`, `penandatangan`, `file`, `ip`, `id_jadwal`) VALUES ('$iddokumen','$sk[id]','$_SESSION[namauser]','$escaped_url','FR.AK.06. MENINJAU PROSES ASESMEN','$_SESSION[namalengkap]','$file','$alamatip','$_GET[idj]')";
																												$conn->query($sqlinputdigisign);
																												echo "<div class='alert alert-success alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Laporan Meninjau Proses Asesmen berhasil disimpan</h4>
					Terimakasih, Anda telah melakukan <b>Laporan Meninjau Proses Asesmen untuk Skema $sk[judul], dan tanda tangan telah ditambahkan</b><br>
					<a class='btn btn-warning form-control' href='?module=jadwalasesmen'>Kembali</a></div>";
																												$sqlinputak01 = "INSERT INTO `asesmen_ak06`(`id_asesor`, `id_skemakkni`, `id_jadwal`, `rencana_v`, `rencana_r`, `rencana_f`, `rencana_a`, `persiapan_v`, `persiapan_r`, `persiapan_f`, `persiapan_a`, `implementasi_v`, `implementasi_r`, `implementasi_f`, `implementasi_a`, `keputusan_v`, `keputusan_r`, `keputusan_a`, `umpanbalik_v`, `umpanbalik_r`, `umpanbalik_a`, `rekomendasi`, `ts`, `tms`, `cms`, `jres`, `trs`, `rekomdimensi1`, `rekomdimensi2`, `rekomdimensi2a`, `rekomdimensi2b`, `rekomdimensi2c`, `aspek`,`catatan_penolakan`,`saran_perbaikan`,`catatan`,`tanggal`) VALUES ('$asr[id]','$jd[id_skemakkni]','$_GET[idj]','$rencana_v', '$rencana_r', '$rencana_f', '$rencana_a', '$persiapan_v', '$persiapan_r', '$persiapan_f', '$persiapan_a', '$implementasi_v', '$implementasi_r', '$implementasi_f', '$implementasi_a', '$keputusan_v', '$keputusan_r', '$keputusan_a', '$umpanbalik_v', '$umpanbalik_r', '$umpanbalik_a', '$_POST[rekomendasi]', '$_POST[ts]', '$_POST[tms]', '$_POST[cms]', '$_POST[jres]', '$_POST[trs]', '$rekomdimensi1', '$rekomdimensi2', '$rekomdimensi2a', '$rekomdimensi2b', '$rekomdimensi2c', '$_POST[aspek]','$_POST[catatan_penolakan]','$_POST[saran_perbaikan]','$_POST[catatan]','$tglsekarang')";
																												$conn->query($sqlinputak01);
																											}
																										}
																									}
																									$sqlgetak01 = "SELECT * FROM `asesmen_ak06` WHERE `id_asesor`='$asr[id]' AND `id_jadwal`='$_GET[idj]'";
																									$getak01 = $conn->query($sqlgetak01);
																									$jjw = $getak01->fetch_assoc();
																									echo "<!-- Main content -->
			<section class='content'>
				<div class='row'>
					<div class='col-xs-12'>
						<div class='box'>
							<div class='box-body'>
								<!-- form start -->
								<div class='box-body'>
						<h2>FR.AK.06. MENINJAU PROSES ASESMEN</h2>
						<form role='form' method='POST' enctype='multipart/form-data'>
						<table id='example9' class='table table-bordered table-striped'>
							<tr><td rowspan='2' width='25%'>Skema Sertifikasi (KKNI/Okupasi/Klaster)</td><td>Judul :</td><td>$sk[judul]</td></tr>
							<tr><td width='25%'>Nomor :</td><td>$sk[kode_skema]</td></tr>
							<tr><td width='25%'>TUK</td><td colspan='2'>$tukjen[jenis_tuk]</td></tr>
							<tr><td width='25%'>Nama Asesor</td><td colspan='2'>$namaasesor</td></tr>
						</table>";
						// <table id='example9' class='table table-bordered table-striped'>
						// 																			$sqlgetunitkompetensi = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
						// 																			$getunitkompetensi = $conn->query($sqlgetunitkompetensi);
						// 																			$jumunitnya0 = $getunitkompetensi->num_rows;
						// 																			$jumunitnya = $jumunitnya0 * 2; //kode dan judul unit adalah 2 baris
						// 																			$noku = 1;
						// 																			//while unitkompetensi ==================================================================
						// 																			while ($unk = $getunitkompetensi->fetch_assoc()) {
						// 																				if ($noku == 1) {
						// 																					echo "<tr><td rowspan='" . $jumunitnya . "' valign='middle'>Unit Kompetensi</td><td>Kode Unit</td><td>" . $unk['kode_unit'] . "</td></tr>";
						// 																					echo "<tr><td>Judul Unit</td><td>" . $unk['judul'] . "</td></tr>";
						// 																				} else {
						// 																					echo "<tr><td>Kode Unit</td><td>" . $unk['kode_unit'] . "</td></tr>";
						// 																					echo "<tr><td>Judul Unit</td><td>" . $unk['judul'] . "</td></tr>";
						// 																				}
						// 																				$noku++;
						// 																			}
						// 																			//end while unitkompetensi =============================================
						// 																			echo "</table>
						echo "<table id='example9' class='table table-bordered table-striped'>
							<thead><tr><th>Penjelasan :</th></tr></thead>
							<tbody><tr><td><ol>
								<li>Peninjauan seharusnya dilakukan oleh asesor yang mensupervisi implementasi asesmen.</li>
								<li>Jika tinjauan dilakukan oleh asesor lain, tinjauan akan dilakukan setelah seluruh proses implementasi asesmen telah selesai.</li>
								<li>Peninjauan dapat dilakukan secara terpadu dalam skema sertifikasi dan / atau peserta kelompok yang homogen.</li>
							</ol></td></tr></tbody>
						</table>
						<table id='example9' class='table table-bordered table-striped'>
							<thead>
								<tr><th rowspan='2'>Aspek yang ditinjau</th><th colspan='4'>Kesesuaian dengan prinsip asesmen</th></tr>
								<tr><th>Validitas</th><th>Reliabel</th><th>Fleksibel</th><th>Adil</th></tr>
							</thead>
							<tbody>
								<tr><td colspan='5'>Prosedur Asesmen:</td></tr>
								<tr>
									<td>Rencana asesmen</td>
									<td><input type='checkbox' class='flat-red' name='rencana_v' id='rencana_v' value='1'";
																									if ($jjw['rencana_v'] == "1") {
																										echo " checked";
																									}
																									echo "></td>
									<td><input type='checkbox' class='flat-red' name='rencana_r' id='rencana_r' value='1'";
																									if ($jjw['rencana_r'] == "1") {
																										echo " checked";
																									}
																									echo "></td>
									<td><input type='checkbox' class='flat-red' name='rencana_f' id='rencana_f' value='1'";
																									if ($jjw['rencana_f'] == "1") {
																										echo " checked";
																									}
																									echo "></td>
									<td><input type='checkbox' class='flat-red' name='rencana_a' id='rencana_a' value='1'";
																									if ($jjw['rencana_a'] == "1") {
																										echo " checked";
																									}
																									echo "></td>
								</tr>
								<tr>
									<td>Persiapan asesmen</td>
									<td><input type='checkbox' class='flat-red' name='persiapan_v' id='persiapan_v' value='1'";
																									if ($jjw['persiapan_v'] == "1") {
																										echo " checked";
																									}
																									echo "></td>
									<td><input type='checkbox' class='flat-red' name='persiapan_r' id='persiapan_r' value='1'";
																									if ($jjw['persiapan_r'] == "1") {
																										echo " checked";
																									}
																									echo "></td>
									<td><input type='checkbox' class='flat-red' name='persiapan_f' id='persiapan_f' value='1'";
																									if ($jjw['persiapan_f'] == "1") {
																										echo " checked";
																									}
																									echo "></td>
									<td><input type='checkbox' class='flat-red' name='persiapan_a' id='persiapan_a' value='1'";
																									if ($jjw['persiapan_a'] == "1") {
																										echo " checked";
																									}
																									echo "></td>
								</tr>
								<tr>
									<td>Implementasi asesmen</td>
									<td><input type='checkbox' class='flat-red' name='implementasi_v' id='implementasi_v' value='1'";
																									if ($jjw['implementasi_v'] == "1") {
																										echo " checked";
																									}
																									echo "></td>
									<td><input type='checkbox' class='flat-red' name='implementasi_r' id='implementasi_r' value='1'";
																									if ($jjw['implementasi_r'] == "1") {
																										echo " checked";
																									}
																									echo "></td>
									<td><input type='checkbox' class='flat-red' name='implementasi_f' id='implementasi_f' value='1'";
																									if ($jjw['implementasi_f'] == "1") {
																										echo " checked";
																									}
																									echo "></td>
									<td><input type='checkbox' class='flat-red' name='implementasi_a' id='implementasi_a' value='1'";
																									if ($jjw['implementasi_a'] == "1") {
																										echo " checked";
																									}
																									echo "></td>
								</tr>
								<tr>
									<td>Keputusan asesmen</td>
									<td><input type='checkbox' class='flat-red' name='keputusan_v' id='keputusan_v' value='1'";
																									if ($jjw['keputusan_v'] == "1") {
																										echo " checked";
																									}
																									echo "></td>
									<td><input type='checkbox' class='flat-red' name='keputusan_r' id='keputusan_r' value='1'";
																									if ($jjw['keputusan_r'] == "1") {
																										echo " checked";
																									}
																									echo "></td>
									<td></td>
									<td><input type='checkbox' class='flat-red' name='keputusan_a' id='keputusan_a' value='1'";
																									if ($jjw['keputusan_a'] == "1") {
																										echo " checked";
																									}
																									echo "></td>
								</tr>
								<tr>
									<td>Umpan balik asesmen</td>
									<td><input type='checkbox' class='flat-red' name='umpanbalik_v' id='umpanbalik_v' value='1'";
																									if ($jjw['umpanbalik_v'] == "1") {
																										echo " checked";
																									}
																									echo "></td>
									<td><input type='checkbox' class='flat-red' name='umpanbalik_r' id='umpanbalik_r' value='1'";
																									if ($jjw['umpanbalik_r'] == "1") {
																										echo " checked";
																									}
																									echo "></td>
									<td></td>
									<td><input type='checkbox' class='flat-red' name='umpanbalik_a' id='umpanbalik_a' value='1'";
																									if ($jjw['umpanbalik_a'] == "1") {
																										echo " checked";
																									}
																									echo "></td>
								</tr>
							</tbody>
						</table>
						<div class='col-md-12'>
						<label><b>Rekomendasi untuk peningkatan</b></label><br />
						<textarea class='form-control' name='rekomendasi'>";
																									if (!empty($jjw['rekomendasi'])) {
																										echo $jjw['rekomendasi'];
																									} else {
																										echo "";
																									}
																									echo "</textarea>
						</div>
						<table id='example9' class='table table-bordered table-striped'>
							<thead>
								<tr><th rowspan='2'>Aspek yang ditinjau</th><th colspan='5'>Pemenuhan dimensi kompetensi</th></tr>
								<tr><th><em>Task Skills</em></th><th><em>Task Management Skills</em></th><th><em>Contingency Management Skills</em></th><th><em>Job Role/ Environment Skills</em></th><th><em>Transfer Skills</em></th></tr>
							</thead>
							<tbody>
								<tr>
									<td>
										<b>Konsistensi keputusan asesmen</b><br/>Bukti dari berbagai asesmen diperiksa untuk konsistensi dimensi kompetensi
									</td>
									<td>
										<textarea class='form-control' name='ts'>";
																									if (!empty($jjw['ts'])) {
																										echo $jjw['ts'];
																									} else {
																										echo "";
																									}
																									echo "</textarea>
									</td>
									<td>
										<textarea class='form-control' name='tms'>";
																									if (!empty($jjw['tms'])) {
																										echo $jjw['tms'];
																									} else {
																										echo "";
																									}
																									echo "</textarea>
									</td>
									<td>
										<textarea class='form-control' name='cms'>";
																									if (!empty($jjw['cms'])) {
																										echo $jjw['cms'];
																									} else {
																										echo "";
																									}
																									echo "</textarea>
									</td>
									<td>
										<textarea class='form-control' name='jres'>";
																									if (!empty($jjw['jres'])) {
																										echo $jjw['jres'];
																									} else {
																										echo "";
																									}
																									echo "</textarea>
									</td>
									<td>
										<textarea class='form-control' name='trs'>";
																									if (!empty($jjw['trs'])) {
																										echo $jjw['trs'];
																									} else {
																										echo "";
																									}
																									echo "</textarea>
									</td>
								</tr>
							</tbody>
						</table>
						<div class='col-md-12'>
						<label><b>Rekomendasi untuk peningkatan</b></label><br />
						<input type='checkbox' class='flat-red' name='rekomdimensi1' id='rekomdimensi1' value='1'";
																									if ($jjw['rekomdimensi1'] == "1") {
																										echo " checked";
																									}
																									echo ">&nbsp;Seluruh dimensi kompetensi sudah tergali pada perangkat asesmen unit kompetensi yang diases<br/>
						<input type='checkbox' class='flat-red' name='rekomdimensi2' id='rekomdimensi2' value='1'";
																									if ($jjw['rekomdimensi2'] == "1") {
																										echo " checked";
																									}
																									echo ">&nbsp;Dimensi kompetensi : <input type='checkbox' class='flat-red' name='rekomdimensi2a' id='rekomdimensi2a' value='1'";
																									if ($jjw['rekomdimensi2a'] == "1") {
																										echo " checked";
																									}
																									echo "> TMS / <input type='checkbox' class='flat-red' name='rekomdimensi2b' id='rekomdimensi2b' value='1'";
																									if ($jjw['rekomdimensi2b'] == "1") {
																										echo " checked";
																									}
																									echo "> CMS / <input type='checkbox' class='flat-red' name='rekomdimensi2c' id='rekomdimensi2c' value='1'";
																									if ($jjw['rekomdimensi2c'] == "1") {
																										echo " checked";
																									}
																									echo "> JRES belum tergali pada perangkat asesmen unit kompetensi yang diases, sehingga harus diperbaiki untuk pelaksanaan selanjutnya.<br/>
						</div>
						<div class='col-md-12'>
						<label><b>Aspek Negatif dan Positif dalam Asesemen :</b></label><br />
						<textarea class='form-control' name='aspek'>";
																									if (!empty($jjw['aspek'])) {
																										echo $jjw['aspek'];
																									} else {
																										echo "";
																									}
																									echo "</textarea>
						</div>
						<div class='col-md-12'>
						<label><b>Pencatatan Penolakan Hasil Asesmen :</b></label><br />
						<textarea class='form-control' name='catatan_penolakan'>";
																									if (!empty($jjw['catatan_penolakan'])) {
																										echo $jjw['catatan_penolakan'];
																									} else {
																										echo "";
																									}
																									echo "</textarea>
						</div>
						<div class='col-md-12'>
						<label><b>Saran Perbaikan (Asesor/Personil Terkait) :</b></label><br />
						<textarea class='form-control' name='saran_perbaikan'>";
																									if (!empty($jjw['saran_perbaikan'])) {
																										echo $jjw['saran_perbaikan'];
																									} else {
																										echo "";
																									}
																									echo "</textarea>
						</div>
						<div class='col-md-12'>
						<label><b>Catatan: :</b></label><br />
						<textarea class='form-control' name='catatan'>";
																									if (!empty($jjw['catatan'])) {
																										echo $jjw['catatan'];
																									} else {
																										echo "";
																									}
																									echo "</textarea>
						</div>";
																									// cek tandatangan digital
																									$url = "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
																									$iddokumen = md5($url);
																									$sqlcektandatangan = "SELECT * FROM `logdigisign` WHERE `id_dokumen`='$iddokumen' AND `penandatangan`='$asr[nama]' ORDER BY `id` DESC";
																									$cektandatangan = $conn->query($sqlcektandatangan);
																									$jumttd = $cektandatangan->num_rows;
																									$ttdx = $cektandatangan->fetch_assoc();
																									if ($jumttd > 0) {
																										echo "<div class='col-md-12'>
									<label class='' for=''>Persetujuan/ Tanda Tangan yang telah Anda berikan:</label>
									<br/>
									<img src='$ttdx[file]' width='400px'/>
									<br/>
								</div>";
																									} else {
																										echo "<div class='col-md-12'>
							<label class='' for=''>Tanda Tangan:</label>
							<br/>
							<div id='sig' ></div>
							<br/>
							<button id='clear'>Hapus Tanda Tangan</button>
							<textarea id='signature64' name='signed' style='display: none'></textarea>
						</div>
						<script type='text/javascript'>
							var sig = $('#sig').signature({syncField: '#signature64', syncFormat: 'PNG', color: '#58009F'});
							$('#clear').click(function(e) {
								e.preventDefault();
								sig.signature('clear');
								$('#signature64').val('');
							});
						</script></div>";
																									}
																									echo "<div class='box-footer'>
						<div class='col-md-4 col-sm-12 col-xs-12'>
								<a class='btn btn-danger form-control' id=reset-validate-form href='?module=jadwalasesmen'>Kembali</a>
						</div>
						<div class='col-md-4 col-sm-12 col-xs-12'>
								<a href='form-ak-06.php?&idj=$_GET[idj]' class='btn btn-primary form-control'>Unduh Formulir</a>
						</div>
						<div class='col-md-4 col-sm-12 col-xs-12'>
								<button type='submit' class='btn btn-success form-control' name='simpan'>Simpan Jawaban</button>
						</div>
					</div>
				</form>			
							</div>
							<!-- /.box-body -->
						</div>
						<!-- /.box -->
					</div>
					<!-- /.col -->
				</div>
				<!-- /.row -->
			</section>
			<!-- /.content -->";
																								}
																								// Bagian Input Hasil Asesmen FORM-FR.IA.01 Asesor
																								elseif ($_GET['module'] == 'form-fr-ia-01') {
																									$sqllogin = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_GET[ida]'";
																									$login = $conn->query($sqllogin);
																									$ketemu = $login->num_rows;
																									$rowAgen = $login->fetch_assoc();
																									$sqlgetjadwal = "SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
																									$getjadwal = $conn->query($sqlgetjadwal);
																									$jd = $getjadwal->fetch_assoc();
																									$sqlgetskema = "SELECT * FROM `skema_kkni` WHERE `id`='$jd[id_skemakkni]'";
																									$getskema = $conn->query($sqlgetskema);
																									$sk = $getskema->fetch_assoc();
																									$sqlgetasesordata = "SELECT * FROM `asesor` WHERE `no_ktp`='$_SESSION[namauser]'";
																									$getasesordata = $conn->query($sqlgetasesordata);
																									$asr = $getasesordata->fetch_assoc();
																									echo "<!-- Content Header (Page header) -->
			<section class='content-header'>
				<h1>
			Input Formulir FR.IA.01
					<small>Skema $sk[judul]</small>
				</h1>
				<ol class='breadcrumb'>
					<li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
					<li class='active'>Ceklis Observasi di Tempat Kerja atau Tempat Kerja Simulasi</li>
				</ol>
			</section>";
																									function uploadFoto($file)
																									{
																										$file_max_weight = 20097152; //Ukuran maksimal yg dibolehkan(2Mb)
																										$ok_ext = array('jpg', 'png', 'gif', 'jpeg', 'JPG', 'PNG', 'GIF', 'JPEG'); // ekstensi yang diijinkan
																										$destination = "foto_asesi/"; // tempat buat upload
																										$filename = explode(".", $file['name']);
																										$file_name = $file['name'];
																										$file_name_no_ext = isset($filename[0]) ? $filename[0] : null;
																										$file_extension = $filename[count($filename) - 1];
																										$file_weight = $file['size'];
																										$file_type = $file['type'];
																										// Jika tidak ada error
																										if ($file['error'] == 0) {
																											$dateNow = date_create();
																											$time_stamp = date_format($dateNow, 'U');
																											if (in_array($file_extension, $ok_ext)) :
																												if ($file_weight <= $file_max_weight) :
																													$fileNewName = $time_stamp . md5($file_name_no_ext[0] . microtime()) . "." . $file_extension;
																													$alamatfile = $fileNewName;
																													if (move_uploaded_file($file['tmp_name'], $destination . $fileNewName)) :
																													//echo" File uploaded !";
																													else :
																													//echo "can't upload file.";
																													endif;
																												else :
																												//echo "File too heavy.";
																												endif;
																											else :
																											//echo "File type is not supported.";
																											endif;
																										}
																										return $alamatfile;
																									}
																									if (isset($_REQUEST['simpan'])) {
																										$sqlgetunitkompetensi2 = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
																										$getunitkompetensi2 = $conn->query($sqlgetunitkompetensi2);
																										while ($unk2 = $getunitkompetensi2->fetch_assoc()) {
																											/* $sqlcekjawaban01="SELECT * FROM `asesi_mak02` WHERE `id_jadwal`='$_GET[idj]' AND `id_asesi`='$_GET[ida]' AND `id_skema`='$sk[id]' AND `id_unitkompetensi`='$unk2[id]'";
						$cekjawaban01=$conn->query($sqlcekjawaban01);
						$jjw01=$cekjawaban01->num_rows;
						$id_jawabane='checkboxa1'.$jd['id']."-".$jd['id_skemakkni']."-".$unk2['id']."-".$_GET['ida'];
						$id_jawabanf='checkboxb1'.$jd['id']."-".$jd['id_skemakkni']."-".$unk2['id']."-".$_GET['ida'];
						$id_jawabang='checkboxc1'.$jd['id']."-".$jd['id_skemakkni']."-".$unk2['id']."-".$_GET['ida'];
						$id_jawabanh='checkboxa2'.$jd['id']."-".$jd['id_skemakkni']."-".$unk2['id']."-".$_GET['ida'];
						$id_jawabani='checkboxb2'.$jd['id']."-".$jd['id_skemakkni']."-".$unk2['id']."-".$_GET['ida'];
						$id_jawabanj='checkboxc2'.$jd['id']."-".$jd['id_skemakkni']."-".$unk2['id']."-".$_GET['ida'];
						$id_jawabank='checkboxa3'.$jd['id']."-".$jd['id_skemakkni']."-".$unk2['id']."-".$_GET['ida'];
						$id_jawabanl='checkboxb3'.$jd['id']."-".$jd['id_skemakkni']."-".$unk2['id']."-".$_GET['ida'];
						$id_jawabanm='checkboxc3'.$jd['id']."-".$jd['id_skemakkni']."-".$unk2['id']."-".$_GET['ida'];
						$id_jawabann1='keputusan1'.$jd['id']."-".$jd['id_skemakkni']."-".$unk2['id']."-".$_GET['ida'];
						$id_jawabano1='pencapaian1'.$jd['id']."-".$jd['id_skemakkni']."-".$unk2['id']."-".$_GET['ida'];
						$id_jawabann2='keputusan2'.$jd['id']."-".$jd['id_skemakkni']."-".$unk2['id']."-".$_GET['ida'];
						$id_jawabano2='pencapaian2'.$jd['id']."-".$jd['id_skemakkni']."-".$unk2['id']."-".$_GET['ida'];
						$id_jawabann3='keputusan3'.$jd['id']."-".$jd['id_skemakkni']."-".$unk2['id']."-".$_GET['ida'];
						$id_jawabano3='pencapaian3'.$jd['id']."-".$jd['id_skemakkni']."-".$unk2['id']."-".$_GET['ida'];
						if ($jjw01==0){
							$sqlinputjawaban01="INSERT INTO `asesi_mak02`(`id_jadwal`, `id_asesi`, `id_skema`, `id_unitkompetensi`, `bukti1-1`, `bukti1-2`, `bukti1-3`, `bukti2-1`, `bukti2-2`, `bukti2-3`, `bukti3-1`, `bukti3-2`, `bukti3-3`, `pencapaian1`,`pencapaian2`, `pencapaian3`, `keputusan1`, `keputusan2`, `keputusan3`, `id_asesor`) VALUES ('$_GET[idj]','$_GET[ida]','$sk[id]','$unk2[id]','$_POST[$id_jawabane]','$_POST[$id_jawabanf]','$_POST[$id_jawabang]','$_POST[$id_jawabanh]','$_POST[$id_jawabani]','$_POST[$id_jawabanj]','$_POST[$id_jawabank]','$_POST[$id_jawabanl]','$_POST[$id_jawabanm]','$_POST[$id_jawabano1]','$_POST[$id_jawabano2]','$_POST[$id_jawabano3]','$_POST[$id_jawabann1]','$_POST[$id_jawabann2]','$_POST[$id_jawabann3]','$asr[id]')";
							$conn->query($sqlinputjawaban01);
						}else{
							$sqlinputjawaban01="UPDATE `asesi_mak02` SET `bukti1-1`='$_POST[$id_jawabane]',`bukti1-2`='$_POST[$id_jawabanf]',`bukti1-3`='$_POST[$id_jawabang]',`bukti2-1`='$_POST[$id_jawabanh]',`bukti2-2`='$_POST[$id_jawabani]',`bukti2-3`='$_POST[$id_jawabanj]',`bukti3-1`='$_POST[$id_jawabank]',`bukti3-2`='$_POST[$id_jawabanl]',`bukti3-3`='$_POST[$id_jawabanm]',`pencapaian1`='$_POST[$id_jawabano1]',`pencapaian2`='$_POST[$id_jawabano2]',`pencapaian3`='$_POST[$id_jawabano3]',`keputusan1`='$_POST[$id_jawabann1]',`keputusan2`='$_POST[$id_jawabann2]',`keputusan3`='$_POST[$id_jawabann3]',`id_asesor`='$asr[id]' WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_skema`='$sk[id]' AND `id_unitkompetensi`='$unk2[id]'";
							$conn->query($sqlinputjawaban01);
						} */
																											$sqlgetelemen2 = "SELECT * FROM `elemen_kompetensi` WHERE `id_unitkompetensi`='$unk2[id]' ORDER BY `id` ASC";
																											$getelemen2 = $conn->query($sqlgetelemen2);
																											while ($el2 = $getelemen2->fetch_assoc()) {
																												$id_jawaban0 = 'bukti' . $el2['id'];
																												$id_jawaban = 'checkbox1' . $el2['id'];
																												$id_jawabanb = 'checkbox2' . $el2['id'];
																												$id_jawabanc = 'checkbox3' . $el2['id'];
																												$id_jawaband = 'checkbox4' . $el2['id'];
																												$id_jawabanp = 'pencapaian' . $el2['id'];
																												$id_jawabanpl = 'penilaian_lanjut' . $el2['id'];
																												$sqlcekjawaban = "SELECT * FROM `asesi_apl02` WHERE `id_asesi`='$_GET[ida]' AND `id_elemen`='$el2[id]' AND `id_skemakkni`='$sk[id]' AND `id_jadwal`='$_GET[idj]'";
																												$cekjawaban = $conn->query($sqlcekjawaban);
																												$jjw = $cekjawaban->num_rows;
																												if ($jjw > 0) {
																													$sqlinputjawaban = "UPDATE `asesi_apl02` SET `benchmark`='$_POST[$id_jawaban0]',`verifikasi_asesor1`='$_POST[$id_jawaban]',`verifikasi_asesor2`='$_POST[$id_jawabanb]',`verifikasi_asesor3`='$_POST[$id_jawabanc]',`verifikasi_asesor4`='$_POST[$id_jawaband]',`keputusan`='$_POST[$id_jawabanp]',`pencapaian`='$_POST[$id_jawabanp]',`penilaian_lanjut`='$_POST[$id_jawabanpl]',`id_asesor`='$asr[id]' WHERE `id_asesi`='$_GET[ida]' AND `id_elemen`='$el2[id]' AND `id_skemakkni`='$sk[id]' AND `id_jadwal`='$_GET[idj]'";
																													$conn->query($sqlinputjawaban);
																												}
																											}
																										}
																										// hitung jumlah kompeten
																										$getkeputusanasesmen1 = "SELECT * FROM `asesi_apl02` WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$sk[id]' AND `id_jadwal`='$_GET[idj]'";
																										$keputusanasesmen1 = $conn->query($getkeputusanasesmen1);
																										$jumapl02 = $keputusanasesmen1->num_rows;
																										$getkeputusanasesmen2 = "SELECT * FROM `asesi_apl02` WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$sk[id]' AND `id_jadwal`='$_GET[idj]' AND `keputusan`='K'";
																										$keputusanasesmen2 = $conn->query($getkeputusanasesmen2);
																										$jumapl02K = $keputusanasesmen2->num_rows;
																										$persentase = ($jumapl02K / $jumapl02) * 100;
																										/* if ($persentase>99.9){
							$sqlkeputusanfinal="UPDATE `asesi_asesmen` SET `status_asesmen`='K',`keputusan_asesor`='R', `umpan_balik`='$_POST[umpan_balik]' WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
						}else{
							if ($persentase < 1){
								$sqlkeputusanfinal="UPDATE `asesi_asesmen` SET `status_asesmen`='BK',`keputusan_asesor`='NR', `umpan_balik`='$_POST[umpan_balik]' WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
							}else{
								$sqlkeputusanfinal="UPDATE `asesi_asesmen` SET `status_asesmen`='TL',`keputusan_asesor`='NR', `umpan_balik`='$_POST[umpan_balik]' WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
							}
						}
						$conn->query($sqlkeputusanfinal); */
																										/* if ($persentase > 99.9){
				echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Penilaian Asesor berhasil disimpan</h4>
				Terimakasih, Anda telah melakukan <b>Penilaian Asesmen untuk Skema $sk[judul].</b><br>
				Asesi dinyatakan <b>$persentase % Jumlah Elemen Kompetensinya KOMPETEN</b><br>
				<a class='btn btn-warning form-control' href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a></div>";
			}else{
				echo "<div class='alert alert-warning alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-warning'></i> Penilaian Asesor berhasil disimpan</h4>
				Terimakasih, Anda telah melakukan <b>Penilaian Asesmen untuk Skema $sk[judul].</b><br>
				Asesi dinyatakan hanya $persentase % Jumlah Elemen Kompetensinya KOMPETEN <b>(BELUM KOMPETEN)</b><br>
				<a class='btn btn-danger form-control' href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a></div>";
			} */
																										// keputusan tidak otomatis by sistem================
																										$folderPath = "../foto_tandatangan/";
																										if (empty($_POST['signed'])) {
																											echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Penilaian Asesor berhasil disimpan</h4>
				Terimakasih, Anda telah melakukan <b>Penilaian Asesmen untuk Skema $sk[judul].</b><br>
				Asesi dinyatakan <b>$persentase % Jumlah Elemen Kompetensinya KOMPETEN</b><br>
				<a class='btn btn-warning form-control' href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a></div>";
																										} else {
																											$image_parts = explode(";base64,", $_POST['signed']);
																											$image_type_aux = explode("image/", $image_parts[0]);
																											$image_type = $image_type_aux[1];
																											$image_base64 = base64_decode($image_parts[1]);
																											$file = $folderPath . uniqid() . '.' . $image_type;
																											file_put_contents($file, $image_base64);
																											$url =  "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
																											$iddokumen = md5($url);
																											$escaped_url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
																											$alamatip = $_SERVER['REMOTE_ADDR'];
																											$sqlinputdigisign = "INSERT INTO `logdigisign`(`id_dokumen`, id_skema, id_asesi, `url_ditandatangani`, `nama_dokumen`, `penandatangan`, `file`, `ip`) VALUES ('$iddokumen','$sk[id]','$_SESSION[namauser]','$escaped_url','FR.IA.01. CEKLIS OBSERVASI AKTIVITAS DI TEMPAT KERJA ATAU TEMPAT KERJA SIMULASI','$_SESSION[namalengkap]','$file','$alamatip')";
																											$conn->query($sqlinputdigisign);
																											echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Penilaian Asesor berhasil disimpan</h4>
				Terimakasih, Anda telah melakukan <b>Penilaian Asesmen untuk Skema $sk[judul].</b><br>
				Asesi dinyatakan <b>$persentase % Jumlah Elemen Kompetensinya KOMPETEN</b><br>
				<a class='btn btn-warning form-control' href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a></div>";
																										}
																									}
																									echo "<!-- Main content -->
			<section class='content'>
				<div class='row'>
					<div class='col-xs-12'>
						<div class='box'>
							<div class='box-body'>
								<!-- form start -->
								<div class='box-body'>
						<h2>FR.IA.01 - CEKLIS OBSERVASI AKTIVITAS DI TEMPAT KERJA ATAU TEMPAT KERJA SIMULASI</h2>
						<h2 class='text-green'>Nama Asesi : $rowAgen[nama] ($rowAgen[no_ktp])</h2>
							<form role='form' method='POST' enctype='multipart/form-data'>
						<p><b>Panduan Bagi Asesor</b></p>
						<p>
							<ol>
								<li>Lengkapi nama unit kompetensi, elemen, dan kriteria unjuk kerja sesuai kolom dalam tabel.</li>
								<li>Istilah Acuan Pembanding  dengan SOP/spesifikasi produk dari industri/organisasi dari tempat kerja atau simulasi tempat kerja.</li>
								<li>Beri tanda centang <input type='checkbox' checked> pada kolom <b>K</b> jika Anda yakin asesi dapat melakukan/ mendemonstrasikan tugas sesuai KUK, atau centang <input type='checkbox' checked> pada kolom <b>BK</b> bila sebaliknya.</li>
								<li>Penilaian Lanjut diisi bila hasil belum dapat disimpulkan, untuk itu gunakan metode lain sehingga keputusan dapat dibuat.</li>
							</ol>
						</p>";
																									$sqlgetunitkompetensi = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
																									$getunitkompetensi = $conn->query($sqlgetunitkompetensi);
																									$no = 1;
																									while ($unk = $getunitkompetensi->fetch_assoc()) {
																										$sqlcekukom = "SELECT * FROM `asesmen_unitkompetensi` WHERE `id_skemakkni`='$sk[id]' AND `id_asesi`='$_GET[ida]' AND `id_unitkompetensi`='$unk[id]'";
																										$cekukom = $conn->query($sqlcekukom);
																										$ukom = $cekukom->num_rows;
																										if ($ukom > 0) {
																											echo "<div class='box box-solid'>";
																											echo "<div class='box-header with-border'>
											<h3 class='box-title text-green'><b>Unit Kompetensi : $no. $unk[judul]</b></h3>
										</div>
										<div class='box-body'><div class='col-md-12'>";
																											$no2 = 1;
																											$sqlgetelemen = "SELECT * FROM `elemen_kompetensi` WHERE `id_unitkompetensi`='$unk[id]' ORDER BY `id` ASC";
																											$getelemen = $conn->query($sqlgetelemen);
																											while ($el = $getelemen->fetch_assoc()) {
																												$no3 = 1;
																												echo "<label>Elemen Kompetensi : $no.$no2. $el[elemen_kompetensi]</label>";
																												echo "<div class='form-group'>
														<label>Kriteria Unjuk Kerja :</label>";
																												$sqlgetkriteria = "SELECT * FROM `kriteria_unjukkerja` WHERE `id_elemenkompetensi`='$el[id]' ORDER BY `id` ASC";
																												$getkriteria = $conn->query($sqlgetkriteria);
																												while ($kr = $getkriteria->fetch_assoc()) {
																													$kriteriatampil = str_replace("Anda", $rowAgen['nama'], $kr['kriteria']);
																													$kriteriatampil2 = str_replace("Apakah ", "", $kriteriatampil);
																													$kriteriatampil3 = str_replace("?", "", $kriteriatampil2);
																													echo "<div class='col-md-12'><p>$no.$no2.$no3. $kriteriatampil3</p></div>";
																													$no3++;
																												}
																												echo "</div>";
																												$sqlcekjawaban0 = "SELECT * FROM `asesi_apl02` WHERE `id_asesi`='$_GET[ida]' AND `id_elemen`='$el[id]' AND `id_skemakkni`='$sk[id]' AND `id_jadwal`='$_GET[idj]'";
																												$cekjawaban0 = $conn->query($sqlcekjawaban0);
																												$jjw0 = $cekjawaban0->fetch_assoc();
																												$numjjw0 = $cekjawaban0->num_rows;
																												if ($numjjw0 == 0) {
																													echo "<div class='col-md-12'>
															<span class='text-red'><b>Belum menjawab</b></span>
														</div>";
																												} else {
																													if ($jjw0['jawaban'] == 1) {
																														echo "<div class='col-md-12'>
																	<div class='col-md-2'>
																		<label>
																		Penilaian Mandiri Asesi :
																		</label><br><a class='btn btn-success btn-xs'><span class='glyphicon glyphicon-ok' aria-hidden='true' title='Kompeten'></span></a>&nbsp;&nbsp;Kompeten &nbsp;&nbsp;&nbsp;
																	</div>
																	<div class='col-md-4'>
																		<div class='form-group'><label>
																		Benchmark (SOP / spesifikasi produk industri) :
																		</label><br>";
																														$getfilebukti = "SELECT * FROM `asesi_apl02doc` WHERE `id_asesi`='$_GET[ida]' AND `id_elemenkompetensi`='$el[id]' AND `id_skemakkni`='$sk[id]' AND `id_jadwal`='$_GET[idj]'";
																														$filebukti = $conn->query($getfilebukti);
																														$nodokumen = 1;
																														while ($fbk = $filebukti->fetch_assoc()) {
																															//$fbk[file];
																															echo "Dokumen $no.$no2.$no3.$nodokumen&nbsp;<a href='#myModal" . $fbk['id'] . "' class='btn btn-success btn-xs' data-toggle='modal' data-id='" . $fbk['id'] . "'><span class='glyphicon glyphicon-zoom-in' aria-hidden='true' title='Lihat/Unduh Dokumen'></span></a><br>";
																															echo "<script>
																				$(function(){
																							$(document).on('click','.edit-record',function(e){
																								e.preventDefault();
																								$('#myModal" . $fbk['id'] . "').modal('show');
																							});
																					});
																			</script>
																			<!-- Modal -->
																				<div class='modal fade' id='myModal" . $fbk['id'] . "' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
																					<div class='modal-dialog'>
																						<div class='modal-content'>
																							<div class='modal-header'>
																								<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Tutup</span></button>
																								<h4 class='modal-title' id='myModalLabel'>Dokumen Bukti " . $el['elemen_kompetensi'] . "</h4>
																							</div>
																							<div class='modal-body'><embed src='../foto_apl02/$fbk[file]' width='100%' height='100%'/>
																							</div>
																							<div class='modal-footer'>
																								<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>
																							</div>
																						</div>
																					</div>
																				</div>
																			<!-- Modal End -->";
																															$nodokumen++;
																														}
																														echo "<br><textarea name='bukti$el[id]' id='bukti$el[id]' class='form-control' placeholder='isikan Benchmark (SOP / spesifikasi produk industri)' required>$jjw0[benchmark]</textarea>";
																														echo "</div>
																	</div>
																	<div class='col-md-3'>
																		<div class='form-group'><label>
																		Penilaian Asesor :
																		</label><br>
																		<label>";
																														echo "<input type='checkbox' class='flat-red' name='checkbox1$el[id]' id='options1$el[id]' value='V'";
																														if ($jjw0['verifikasi_asesor1'] == "V") {
																															echo "checked";
																														}
																														echo ">";
																														echo " Valid &nbsp;&nbsp;&nbsp;
																		</label>
																		<label>";
																														echo "<input type='checkbox' class='flat-red' name='checkbox2$el[id]' id='options2$el[id]' value='A'";
																														if ($jjw0['verifikasi_asesor2'] == "A") {
																															echo "checked";
																														}
																														echo ">";
																														echo " Autentik &nbsp;&nbsp;&nbsp;
																		</label>
																		<label>";
																														echo "<input type='checkbox' class='flat-red' name='checkbox3$el[id]' id='options3$el[id]' value='T'";
																														if ($jjw0['verifikasi_asesor3'] == "T") {
																															echo "checked";
																														}
																														echo ">";
																														echo " Terkini &nbsp;&nbsp;&nbsp;
																		</label>
																		<label>";
																														echo "<input type='checkbox' class='flat-red' name='checkbox4$el[id]' id='options4$el[id]' value='M'";
																														if ($jjw0['verifikasi_asesor4'] == "M") {
																															echo "checked";
																														}
																														echo ">";
																														echo " Memadai &nbsp;&nbsp;&nbsp;
																		</label></div>
																	</div>";
																														switch ($jjw0['pencapaian']) {
																															case "K":
																																echo "<div class='col-md-3 bg-green'>";
																																break;
																															case "BK":
																																echo "<div class='col-md-3 bg-red'>";
																																break;
																															default:
																																echo "<div class='col-md-3'>";
																																break;
																														}
																														echo "<div class='form-group'><label>
																		Rekomendasi :
																		</label><br>
																		<label>";
																														echo "<input type='radio' class='minimal' name='pencapaian$el[id]' id='options1c$el[id]' value='K'";
																														if ($jjw0['pencapaian'] == "K") {
																															echo "checked";
																														}
																														echo ">";
																														echo " Kompeten (K)&nbsp;&nbsp;&nbsp;
																		</label>
																		<label>";
																														echo "<input type='radio' class='minimal' name='pencapaian$el[id]' id='options2c1$el[id]' value='BK'";
																														if ($jjw0['pencapaian'] == "BK") {
																															echo "checked";
																														}
																														echo ">";
																														echo " Belum Kompeten (BK)&nbsp;&nbsp;&nbsp;
																		</label>
																		</div>
																	</div>
																	<div class='col-md-12'>
																		<div class='form-group'><label>
																		Penilaian Lanjut :
																		</label><br>";
																														echo "<br><textarea name='penilaian_lanjut$el[id]' id='penilaian_lanjut$el[id]' class='form-control' placeholder='isikan penilaian lanjut alasan kenapa belum kompeten (bila belum kompeten)' required>$jjw0[penilaian_lanjut]</textarea>";
																														echo "</div>
																	</div></div>";
																													}
																													if ($jjw0['jawaban'] == 0) {
																														echo "<div class='col-md-12'>
																	<div class='col-md-2'>
																		<label>
																		Penilaian Mandiri Asesi :
																		</label><br><a class='btn btn-danger btn-xs'><span class='glyphicon glyphicon-remove' aria-hidden='true' title='Belum Kompeten'></span></a>&nbsp;&nbsp;Belum Kompeten &nbsp;&nbsp;&nbsp;
																	</div>
																	<div class='col-md-4'>
																		<div class='form-group'><label>
																		Benchmark (SOP / spesifikasi produk industri) :
																		</label><br>";
																														$getfilebukti = "SELECT * FROM `asesi_apl02doc` WHERE `id_asesi`='$_GET[ida]' AND `id_elemenkompetensi`='$el[id]' AND `id_skemakkni`='$sk[id]' AND `id_jadwal`='$_GET[idj]'";
																														$filebukti = $conn->query($getfilebukti);
																														$nodokumen = 1;
																														while ($fbk = $filebukti->fetch_assoc()) {
																															//$fbk[file];
																															echo "Dokumen $no.$no2.$no3.$nodokumen&nbsp;<a href='#myModal" . $fbk['id'] . "' class='btn btn-success btn-xs' data-toggle='modal' data-id='" . $fbk['id'] . "'><span class='glyphicon glyphicon-zoom-in' aria-hidden='true' title='Lihat/Unduh Dokumen'></span></a><br>";
																															echo "<script>
																				$(function(){
																							$(document).on('click','.edit-record',function(e){
																								e.preventDefault();
																								$('#myModal" . $fbk['id'] . "').modal('show');
																							});
																					});
																			</script>
																			<!-- Modal -->
																				<div class='modal fade' id='myModal" . $fbk['id'] . "' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
																					<div class='modal-dialog'>
																						<div class='modal-content'>
																							<div class='modal-header'>
																								<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Tutup</span></button>
																								<h4 class='modal-title' id='myModalLabel'>Dokumen Bukti " . $el['elemen_kompetensi'] . "</h4>
																							</div>
																							<div class='modal-body'><embed src='../foto_apl02/$fbk[file]' width='100%' height='100%'/>
																							</div>
																							<div class='modal-footer'>
																								<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>
																							</div>
																						</div>
																					</div>
																				</div>
																			<!-- Modal End -->";
																															$nodokumen++;
																														}
																														echo "<br><textarea name='bukti$el[id]' id='bukti$el[id]' class='form-control' placeholder='isikan Benchmark (SOP / spesifikasi produk industri)' required>$jjw0[benchmark]</textarea>";
																														echo "</div>
																	</div>
																	<div class='col-md-3'>
																		<div class='form-group'><label>
																		Penilaian Asesor :
																		</label><br>
																		<label>";
																														echo "<input type='checkbox' class='flat-red' name='checkbox1$el[id]' id='options1$el[id]' value='V'";
																														if ($jjw0['verifikasi_asesor1'] == "V") {
																															echo "checked";
																														}
																														echo ">";
																														echo " Valid &nbsp;&nbsp;&nbsp;
																		</label>
																		<label>";
																														echo "<input type='checkbox' class='flat-red' name='checkbox2$el[id]' id='options2$el[id]' value='A'";
																														if ($jjw0['verifikasi_asesor2'] == "A") {
																															echo "checked";
																														}
																														echo ">";
																														echo " Autentik &nbsp;&nbsp;&nbsp;
																		</label>
																		<label>";
																														echo "<input type='checkbox' class='flat-red' name='checkbox3$el[id]' id='options3$el[id]' value='T'";
																														if ($jjw0['verifikasi_asesor3'] == "T") {
																															echo "checked";
																														}
																														echo ">";
																														echo " Terkini &nbsp;&nbsp;&nbsp;
																		</label>
																		<label>";
																														echo "<input type='checkbox' class='flat-red' name='checkbox4$el[id]' id='options4$el[id]' value='M'";
																														if ($jjw0['verifikasi_asesor4'] == "M") {
																															echo "checked";
																														}
																														echo ">";
																														echo " Memadai &nbsp;&nbsp;&nbsp;
																		</label></div>
																	</div>";
																														switch ($jjw0['pencapaian']) {
																															case "K":
																																echo "<div class='col-md-3 bg-green'>";
																																break;
																															case "BK":
																																echo "<div class='col-md-3 bg-red'>";
																																break;
																															default:
																																echo "<div class='col-md-3'>";
																																break;
																														}
																														echo "<div class='form-group'><label>
																		Rekomendasi :
																		</label><br>
																		<label>";
																														echo "<input type='radio' class='minimal' name='pencapaian$el[id]' id='options1c$el[id]' value='K'";
																														if ($jjw0['pencapaian'] == "K") {
																															echo "checked";
																														}
																														echo ">";
																														echo " Kompeten (K)&nbsp;&nbsp;&nbsp;
																		</label>
																		<label>";
																														echo "<input type='radio' class='minimal' name='pencapaian$el[id]' id='options2c1$el[id]' value='BK'";
																														if ($jjw0['pencapaian'] == "BK") {
																															echo "checked";
																														}
																														echo ">";
																														echo " Belum Kompeten (BK)&nbsp;&nbsp;&nbsp;
																		</label>
																		</div>
																	</div>
																	<div class='col-md-12'>
																		<div class='form-group'><label>
																		Penilaian Lanjut :
																		</label><br>";
																														echo "<br><textarea name='penilaian_lanjut$el[id]' id='penilaian_lanjut$el[id]' class='form-control' placeholder='isikan penilaian lanjut alasan kenapa belum kompeten (bila belum kompeten)' required>$jjw0[penilaian_lanjut]</textarea>";
																														echo "</div>
																	</div></div>";
																													}
																												}
																												$no2++;
																											}
																											echo "<div></div><!-- /.box-body-->
									</div></div><!-- /.box box-solid-->";
																											$no++;
																										}
																									}
																									echo "</div>";
																									$sqlgetkeputusan = "SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																									$getkeputusan = $conn->query($sqlgetkeputusan);
																									$getk = $getkeputusan->fetch_assoc();
																									echo "<div class='box-footer'>
					<div class='col-md-12'><label class='text-yellow'>
									Umpan Balik untuk Asesi : 
								</label><br>
								<textarea name='umpan_balik' id='umpan_balik' class='form-control' placeholder='isikan umpan balik untuk asesi'>$getk[umpan_balik]</textarea><br>
					</div>";
																									// cek tandatangan digital
																									$url = "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
																									$iddokumen = md5($url);
																									$sqlcektandatangan = "SELECT * FROM `logdigisign` WHERE `penandatangan`='$_SESSION[namalengkap]' AND `id_asesi`='$_SESSION[namauser]' AND `id_skema`='$sk[id]' AND nama_dokumen='FR.AK.06. MENINJAU PROSES ASESMEN' ORDER BY `id` DESC";
																									$cektandatangan = $conn->query($sqlcektandatangan);
																									$jumttd = $cektandatangan->num_rows;
																									$ttdx = $cektandatangan->fetch_assoc();
																									if ($jumttd > 0) {
																										echo "<div class='col-md-12'>
									<label class='' for=''>Persetujuan/ Tanda Tangan yang telah Anda berikan:</label>
									<br/>
									<img src='../$ttdx[file]' width='400px'/>
									<br/>
								</div>";
																									} else {
																										echo "<div class='col-md-12'>
							<label class='' for=''>Tanda Tangan:</label>
							<br/>
							<div id='sig' ></div>
							<br/>
							<button id='clear'>Hapus Tanda Tangan</button>
							<textarea id='signature64' name='signed' style='display: none'></textarea>
						</div>
						<script type='text/javascript'>
							var sig = $('#sig').signature({syncField: '#signature64', syncFormat: 'PNG', color: '#58009F'});
							$('#clear').click(function(e) {
								e.preventDefault();
								sig.signature('clear');
								$('#signature64').val('');
							});
						</script></div>";
																									}
																									echo "<div class='col-md-4 col-sm-12 col-xs-12'>
							<a class='btn btn-danger form-control' id=reset-validate-form href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a>
					</div>
					<div class='col-md-4 col-sm-12 col-xs-12'>
							<a href='form-fr-ia-01.php?ida=$_GET[ida]&idj=$_GET[idj]' class='btn btn-primary form-control' target='_blank'>Unduh Form Hasil</a>
					</div>
					<div class='col-md-4 col-sm-12 col-xs-12'>
							<button type='submit' class='btn btn-success form-control' name='simpan'>Simpan Jawaban</button>
					</div>
								</div>
							</form>			
							</div>
							<!-- /.box-body -->
						</div>
						<!-- /.box -->
					</div>
					<!-- /.col -->
				</div>
				<!-- /.row -->
			</section>
			<!-- /.content -->";
																								}
																								// Bagian Input Hasil Asesmen FORM-FR.IA.03 Asesor
																								elseif ($_GET['module'] == 'form-fr-ia-03') {
																									$sqllogin = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_GET[ida]'";
																									$login = $conn->query($sqllogin);
																									$ketemu = $login->num_rows;
																									$rowAgen = $login->fetch_assoc();
																									// QUERY GET DATA ASESOR
																									$queryasesor = "SELECT * FROM asesor WHERE no_ktp='$_SESSION[namauser]'";
																									$qasesor = $conn->query($queryasesor);
																									$asesor = $qasesor->fetch_assoc();

																									$sqlgetjadwal = "SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
																									$getjadwal = $conn->query($sqlgetjadwal);
																									$jd = $getjadwal->fetch_assoc();
																									$sqlgetskema = "SELECT * FROM `skema_kkni` WHERE `id`='$jd[id_skemakkni]'";
																									$getskema = $conn->query($sqlgetskema);
																									$sk = $getskema->fetch_assoc();
																									$sqlgetasesordata = "SELECT * FROM `asesor` WHERE `no_ktp`='$_SESSION[namauser]'";
																									$getasesordata = $conn->query($sqlgetasesordata);
																									$asr = $getasesordata->fetch_assoc();
																									$sqltuk = "SELECT * FROM `tuk` WHERE `id`='$jd[tempat_asesmen]'";
																									$tuk = $conn->query($sqltuk);
																									$tq = $tuk->fetch_assoc();
																									$sqltukjenis = "SELECT `jenis_tuk` FROM `tuk_jenis` WHERE `id`='$tq[jenis_tuk]'";
																									$tukjenis = $conn->query($sqltukjenis);
																									$tukjen = $tukjenis->fetch_assoc();
																									$noasr = 1;
																									$getasesor = $conn->query("SELECT * FROM `jadwal_asesor` WHERE `id_jadwal`='$_GET[idj]'");

																									$sqlcektandatangan = "SELECT * FROM `logdigisign` WHERE id_skema='$jd[id_skemakkni]' AND id_asesi='$_SESSION[namauser]' AND `penandatangan`='$asesor[nama]' AND nama_dokumen='FR.IA.03. PERTANYAAN UNTUK MENDUKUNG OBSERVASI' ORDER BY `waktu` DESC";
																									$cektandatangan = $conn->query($sqlcektandatangan);
																									$jumttd = $cektandatangan->num_rows;
																									$ttdx = $cektandatangan->fetch_assoc();

																									while ($gas = $getasesor->fetch_assoc()) {
																										$sqlasesor = "SELECT * FROM `asesor` WHERE `id`='$gas[id_asesor]'";
																										$asesor = $conn->query($sqlasesor);
																										$asr = $asesor->fetch_assoc();
																										if (!empty($asr['gelar_depan'])) {
																											if (!empty($asr['gelar_blk'])) {
																												$namaasesor = $asr['gelar_depan'] . " " . $asr['nama'] . ", " . $asr['gelar_blk'];
																											} else {
																												$namaasesor = $asr['gelar_depan'] . " " . $asr['nama'];
																											}
																										} else {
																											if (!empty($asr['gelar_blk'])) {
																												$namaasesor = $asr['nama'] . ", " . $asr['gelar_blk'];
																											} else {
																												$namaasesor = $asr['nama'];
																											}
																										}
																										$noregasesor = $asr['no_induk'];
																										$namaasesor = $noasr . '. ' . $namaasesor;
																										$noregasesor = $noasr . '. ' . $noregasesor;
																										$noasr++;
																									}
																									echo "<!-- Content Header (Page header) -->
			<section class='content-header'>
				<h1>
				Input FR.IA.03
				<small>Skema $sk[judul]</small>
				</h1>
				<ol class='breadcrumb'>
				<li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
				<li class='active'>FR.IA.03</li>
				</ol>
			</section>";
																									if (isset($_REQUEST['simpan'])) {
																										$sqlcekgetak01 = "SELECT * FROM `asesmen_ia03` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																										$cekgetak01 = $conn->query($sqlcekgetak01);
																										$cekak01 = $cekgetak01->num_rows;
																										$tglsekarang = date("Y-m-d");
																										if ($cekak01 > 0) {
																											$folderPath = "../foto_tandatangan/";
																											if (empty($_POST['signed'])) {
																												// input tanggapan pendukung observasi
																												$sqlgetunitkompetensib2 = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
																												$getunitkompetensib2 = $conn->query($sqlgetunitkompetensib2);
																												while ($unkb2 = $getunitkompetensib2->fetch_assoc()) {
																													$sqlgetpertanyaan2 = "SELECT * FROM `skema_pertanyaanpendukung` WHERE `id_unitkompetensi`='$unkb2[id]' ORDER BY `id` ASC";
																													$getpertanyaan2 = $conn->query($sqlgetpertanyaan2);
																													while ($gpp2 = $getpertanyaan2->fetch_assoc()) {
																														//if (!empty($gpp2['pertanyaan'])){
																														$posttanggapan = 'tanggapan' . $gpp2['id'];
																														$postrekomendasi = 'rekomendasi' . $gpp2['id'];
																														$sqlcekjawaban2 = "SELECT * FROM `asesmen_ia03` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='$gpp2[id]'";
																														$cekjawaban2 = $conn->query($sqlcekjawaban2);
																														$jjw2 = $cekjawaban2->fetch_assoc();
																														$sqlinputak01 = "UPDATE `asesmen_ia03` SET `tanggapan`='" . $_POST[$posttanggapan] . "', `rekomendasi`='" . $_POST[$postrekomendasi] . "' WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$jd[id_skemakkni]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='$gpp2[id]'";
																														die;
																														$conn->query($sqlinputak01);
																														//}
																													}
																												}
																												$sqlinputak01b = "UPDATE `asesmen_ia03` SET `tanggapan`='" . $_POST[$posttanggapan] . "', `rekomendasi`='" . $_POST[$postrekomendasi] . "' WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$jd[id_skemakkni]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='$gpp2[id]'";
																												$conn->query($sqlinputak01b);
																												echo "<div class='alert alert-success alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Tanggapan Pertanyaan Pendukung Observasi oleh Asesor berhasil disimpan</h4>
					Terimakasih, Anda telah melakukan input <b>Tanggapan Pertanyaan Pendukung Observasi untuk Skema $sk[judul].</b><br>
					<a class='btn btn-warning form-control' href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a></div>"; ?>
									<script>
										window.location = "<?php echo $base_url ?>media.php?module=form-fr-ia-03&ida=<?php echo $_GET['ida'] ?>&idj=<?php echo $_GET['idj'] ?>"
									</script>
								<?php
																											} else {
																												$image_parts = explode(";base64,", $_POST['signed']);
																												$image_type_aux = explode("image/", $image_parts[0]);
																												$image_type = $image_type_aux[1];
																												$image_base64 = base64_decode($image_parts[1]);
																												$file = $folderPath . uniqid() . '.' . $image_type;
																												file_put_contents($file, $image_base64);
																												$url =  "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
																												$iddokumen = md5($url);
																												$escaped_url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
																												$alamatip = $_SERVER['REMOTE_ADDR'];
																												$sqlinputdigisign = "INSERT INTO `logdigisign`(`id_dokumen`, id_skema, id_asesi, `url_ditandatangani`, `nama_dokumen`, `penandatangan`, `file`, `ip`) VALUES ('$iddokumen','$sk[id]','$_SESSION[namauser]','$escaped_url','FR.IA.03. PERTANYAAN UNTUK MENDUKUNG OBSERVASI','$_SESSION[namalengkap]','$file','$alamatip')";
																												$conn->query($sqlinputdigisign);
																												// input tanggapan pendukung observasi
																												$sqlgetunitkompetensib2 = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
																												$getunitkompetensib2 = $conn->query($sqlgetunitkompetensib2);
																												while ($unkb2 = $getunitkompetensib2->fetch_assoc()) {
																													$sqlgetpertanyaan2 = "SELECT * FROM `skema_pertanyaanpendukung` WHERE `id_unitkompetensi`='$unkb2[id]' ORDER BY `id` ASC";
																													$getpertanyaan2 = $conn->query($sqlgetpertanyaan2);
																													while ($gpp2 = $getpertanyaan2->fetch_assoc()) {
																														//if (!empty($gpp2['pertanyaan'])){
																														$posttanggapan = 'tanggapan' . $gpp2['id'];
																														$postrekomendasi = 'rekomendasi' . $gpp2['id'];
																														$sqlcekjawaban2 = "SELECT * FROM `asesmen_ia03` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='$gpp2[id]'";
																														$cekjawaban2 = $conn->query($sqlcekjawaban2);
																														$jjw2 = $cekjawaban2->fetch_assoc();
																														$sqlinputak01 = "UPDATE `asesmen_ia03` SET `tanggapan`='" . $_POST[$posttanggapan] . "', `rekomendasi`='" . $_POST[$postrekomendasi] . "' WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$jd[id_skemakkni]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='$gpp2[id]'";
																														$conn->query($sqlinputak01);
																														//}
																													}
																												}
																												$sqlinputak01b = "UPDATE `asesi_asesmen` SET `umpan_balik_ia03`='$_POST[umpan_balik_ia03]' WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																												$conn->query($sqlinputak01b);
																												echo "<div class='alert alert-success alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Tanggapan Pertanyaan Pendukung Observasi oleh Asesor berhasil disimpan</h4>
					Terimakasih, Anda telah melakukan input <b>Tanggapan Pertanyaan Pendukung Observasi untuk Skema $sk[judul], dan tanda tangan telah ditambahkan</b><br>
					<a class='btn btn-warning form-control' href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a></div>"; ?>
									<script>
										window.location = "<?php echo $base_url ?>media.php?module=form-fr-ia-03&ida=<?php echo $_GET['ida'] ?>&idj=<?php echo $_GET['idj'] ?>"
									</script>
								<?php
																											}
																										} else {
																											$folderPath = "../foto_tandatangan/";
																											if (empty($_POST['signed'])) {
																												// input tanggapan pendukung observasi
																												$sqlgetunitkompetensib2 = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
																												$getunitkompetensib2 = $conn->query($sqlgetunitkompetensib2);
																												while ($unkb2 = $getunitkompetensib2->fetch_assoc()) {
																													$sqlgetpertanyaan2 = "SELECT * FROM `skema_pertanyaanpendukung` WHERE `id_unitkompetensi`='$unkb2[id]' ORDER BY `id` ASC";
																													$getpertanyaan2 = $conn->query($sqlgetpertanyaan2);
																													while ($gpp2 = $getpertanyaan2->fetch_assoc()) {
																														//if (!empty($gpp2['pertanyaan'])){
																														$posttanggapan = 'tanggapan' . $gpp2['id'];
																														$postrekomendasi = 'rekomendasi' . $gpp2['id'];
																														if (empty($_POST[$posttanggapan])) {
																															$posttanggapanx = "";
																														} else {
																															$posttanggapanx = $_POST[$posttanggapan];
																														}
																														if (empty($_POST[$postrekomendasi])) {
																															$postrekomendasix = "";
																														} else {
																															$postrekomendasix = $_POST[$postrekomendasi];
																														}
																														$sqlcekjawaban2 = "SELECT * FROM `asesmen_ia03` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='$gpp2[id]'";
																														$cekjawaban2 = $conn->query($sqlcekjawaban2);
																														$jjw2 = $cekjawaban2->fetch_assoc();
																														$sqlinputak01 = "INSERT INTO `asesmen_ia03`(`id_asesi`, `id_skemakkni`, `id_jadwal`, `id_unitkompetensi`, `id_pertanyaan`, `tanggapan`, `rekomendasi`) VALUES ('$_GET[ida]','$sk[id]','$_GET[idj]','$unkb2[id]','$gpp2[id]','" . $posttanggapanx . "','" . $postrekomendasix . "')";
																														$conn->query($sqlinputak01);
																														//}
																													}
																												}
																												$sqlinputak01b = "UPDATE `asesi_asesmen` SET `umpan_balik_ia03`='$_POST[umpan_balik_ia03]' WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																												$conn->query($sqlinputak01b);
																												echo "<div class='alert alert-success alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Tanggapan Pertanyaan Pendukung Observasi oleh Asesor berhasil disimpan</h4>
					Terimakasih, Anda telah melakukan input <b>Tanggapan Pertanyaan Pendukung Observasi untuk Skema $sk[judul].</b><br>
					<a class='btn btn-warning form-control' href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a></div>"; ?>
									<script>
										window.location = "<?php echo $base_url ?>media.php?module=form-fr-ia-03&ida=<?php echo $_GET['ida'] ?>&idj=<?php echo $_GET['idj'] ?>"
									</script>
								<?php
																											} else {
																												$image_parts = explode(";base64,", $_POST['signed']);
																												$image_type_aux = explode("image/", $image_parts[0]);
																												$image_type = $image_type_aux[1];
																												$image_base64 = base64_decode($image_parts[1]);
																												$file = $folderPath . uniqid() . '.' . $image_type;
																												file_put_contents($file, $image_base64);
																												$url =  "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
																												$iddokumen = md5($url);
																												$escaped_url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
																												$alamatip = $_SERVER['REMOTE_ADDR'];
																												$sqlinputdigisign = "INSERT INTO `logdigisign`(`id_dokumen`, id_skema, id_asesi, `url_ditandatangani`, `nama_dokumen`, `penandatangan`, `file`, `ip`) VALUES ('$iddokumen','$sk[id]','$_SESSION[namauser]','$escaped_url','FR.IA.03. PERTANYAAN UNTUK MENDUKUNG OBSERVASI','$_SESSION[namalengkap]','$file','$alamatip')";
																												$conn->query($sqlinputdigisign);
																												// input tanggapan pendukung observasi
																												$sqlgetunitkompetensib2 = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
																												$getunitkompetensib2 = $conn->query($sqlgetunitkompetensib2);
																												while ($unkb2 = $getunitkompetensib2->fetch_assoc()) {
																													$sqlgetpertanyaan2 = "SELECT * FROM `skema_pertanyaanpendukung` WHERE `id_unitkompetensi`='$unkb2[id]' ORDER BY `id` ASC";
																													$getpertanyaan2 = $conn->query($sqlgetpertanyaan2);
																													while ($gpp2 = $getpertanyaan2->fetch_assoc()) {
																														//if (!empty($gpp2['pertanyaan'])){
																														$posttanggapan = 'tanggapan' . $gpp2['id'];
																														$postrekomendasi = 'rekomendasi' . $gpp2['id'];
																														if (empty($_POST[$posttanggapan])) {
																															$posttanggapanx = "";
																														} else {
																															$posttanggapanx = $_POST[$posttanggapan];
																														}
																														if (empty($_POST[$postrekomendasi])) {
																															$postrekomendasix = "";
																														} else {
																															$postrekomendasix = $_POST[$postrekomendasi];
																														}
																														$sqlcekjawaban2 = "SELECT * FROM `asesmen_ia03` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='$gpp2[id]'";
																														$cekjawaban2 = $conn->query($sqlcekjawaban2);
																														$jjw2 = $cekjawaban2->fetch_assoc();
																														$sqlinputak01 = "INSERT INTO `asesmen_ia03`(`id_asesi`, `id_skemakkni`, `id_jadwal`, `id_unitkompetensi`, `id_pertanyaan`, `tanggapan`, `rekomendasi`) VALUES ('$_GET[ida]','$sk[id]','$_GET[idj]','$unkb2[id]','$gpp2[id]','" . $posttanggapanx . "','" . $postrekomendasix . "')";
																														$conn->query($sqlinputak01);
																														//}
																													}
																												}
																												$sqlinputak01b = "UPDATE `asesi_asesmen` SET `umpan_balik_ia03`='$_POST[umpan_balik_ia03]' WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																												$conn->query($sqlinputak01b);
																												echo "<div class='alert alert-success alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Tanggapan Pertanyaan Pendukung Observasi oleh Asesor berhasil disimpan</h4>
					Terimakasih, Anda telah melakukan untuk <b>Tanggapan Pertanyaan Pendukung Observasi untuk Skema $sk[judul], dan tanda tangan telah ditambahkan</b><br>
					<a class='btn btn-warning form-control' href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a></div>"; ?>
									<script>
										window.location = "<?php echo $base_url ?>media.php?module=form-fr-ia-03&ida=<?php echo $_GET['ida'] ?>&idj=<?php echo $_GET['idj'] ?>"
									</script>
					<?php
																											}
																										}
																									}
																									$tanggalasesmen = tgl_indo($jd['tgl_asesmen']);
																									echo "<!-- Main content -->
			<section class='content'>
				<div class='row'>
					<div class='col-xs-12'>
						<div class='box'>
							<div class='box-body'>
								<!-- form start -->
								<div class='box-body'>
						<h2>FR.IA.03. PERTANYAAN UNTUK MENDUKUNG OBSERVASI</h2>
						<form role='form' method='POST' enctype='multipart/form-data'>
						<table id='example9' class='table table-bordered table-striped'>
							<tr><td rowspan='2' width='25%'>Skema Sertifikasi (KKNI/Okupasi/Klaster)</td><td>Judul :</td><td>$sk[judul]</td></tr>
							<tr><td width='25%'>Nomor :</td><td>$sk[kode_skema]</td></tr>
							<tr><td width='25%'>TUK</td><td colspan='2'>$tukjen[jenis_tuk]</td></tr>
							<tr><td width='25%'>Nama Asesor</td><td colspan='2'>$namaasesor</td></tr>
							<tr><td width='25%'>Nama Asesi</td><td colspan='2'>$rowAgen[nama]</td></tr>
							<tr><td width='25%'>Tanggal</td><td colspan='2'>$tanggalasesmen</td></tr>
						</table>
						<table id='example9' class='table table-bordered table-striped'>
							<tr><th>PANDUAN BAGI ASESOR</th></tr>
							<tr><td><ul>
							<li>Formulir ini diisi pada saat asesor akan melakukan asesmen dengan metode observasi demonstrasi</li>
							<li>Pertanyaan dibuat dengan tujuan untuk menggali, dapat berisi pertanyaan yang berkaitan dengan dimensi kompetensi, batasan variabel dan aspek kritis.</li>
							<li>Tanggapan asesi dapat ditulis pada kolom tanggapan, atau dapat langsung mengisi respon dengan ya atau tidak</li>
							</ul></td></tr>
						</table>
						<table id='example9' class='table table-bordered table-striped'>";
																									$sqlgetunitkompetensi = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
																									$getunitkompetensi = $conn->query($sqlgetunitkompetensi);
																									$jumunitnya0 = $getunitkompetensi->num_rows;
																									$jumunitnya = $jumunitnya0 * 2; //kode dan judul unit adalah 2 baris
																									$noku = 1;
																									//while unitkompetensi ==================================================================
																									while ($unk = $getunitkompetensi->fetch_assoc()) {
																										if ($noku == 1) {
																											echo "<tr><td rowspan='" . $jumunitnya . "' valign='middle'>Unit Kompetensi</td><td>Kode Unit</td><td>" . $unk['kode_unit'] . "</td></tr>";
																											echo "<tr><td>Judul Unit</td><td>" . $unk['judul'] . "</td></tr>";
																										} else {
																											echo "<tr><td>Kode Unit</td><td>" . $unk['kode_unit'] . "</td></tr>";
																											echo "<tr><td>Judul Unit</td><td>" . $unk['judul'] . "</td></tr>";
																										}
																										$noku++;
																									}
																									//end while unitkompetensi =============================================
																									echo "</table>
						<table id='example9' class='table table-bordered table-striped'>
						<thead><tr><th colspan='2'>Pertanyaan</th><th>Rekomendasi</th></tr></thead>
						<tbody>";
																									// pertanyaan pendukung observasi
																									$sqlgetunitkompetensib = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
																									$getunitkompetensib = $conn->query($sqlgetunitkompetensib);
																									$nopp = 1;
																									while ($unkb = $getunitkompetensib->fetch_assoc()) {
																										$sqlgetpertanyaan = "SELECT * FROM `skema_pertanyaanpendukung` WHERE `id_unitkompetensi`='$unkb[id]' ORDER BY `id` ASC";
																										$getpertanyaan = $conn->query($sqlgetpertanyaan);
																										while ($gpp = $getpertanyaan->fetch_assoc()) {
																											//if (!empty($gpp['pertanyaan'])){
																											$sqlcekjawaban = "SELECT * FROM `asesmen_ia03` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='$gpp[id]'";
																											$cekjawaban = $conn->query($sqlcekjawaban);
																											$jjw = $cekjawaban->fetch_assoc();
																											echo "<tr><td>$nopp.</td><td>" . $gpp['pertanyaan'] . "</td><td rowspan='2'>
									<input type='radio' name='rekomendasi$gpp[id]' id='rekomendasi$gpp[id]1' value='K' required='required'";
																											if ($jjw['rekomendasi'] == 'K') {
																												echo " checked";
																											} else {
																												echo "";
																											}
																											echo ">&nbsp;Kompeten<br>
									<input type='radio' name='rekomendasi$gpp[id]' id='rekomendasi$gpp[id]2' value='BK' required='required'";
																											if ($jjw['rekomendasi'] == 'BK') {
																												echo " checked";
																											} else {
																												echo "";
																											}
																											echo ">&nbsp;Belum Kompeten</td></tr>
									<tr><td colspan='2'>Tanggapan:<br>";
																											echo "<textarea class='form-control' name='tanggapan$gpp[id]'>";
																											if (!empty($jjw['tanggapan'])) {
																												echo $jjw['tanggapan'];
																											} else {
																												echo "";
																											}
																											echo "</textarea>";
																											echo "</td></tr>";
																											$nopp++;
																											//}
																										}
																									}
																									echo "</tbody></table>";
																									$sqlgetkeputusan = "SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																									$getkeputusan = $conn->query($sqlgetkeputusan);
																									$getk = $getkeputusan->fetch_assoc();
																									echo "<div class='col-md-12'>
						<label><b>Umpan Balik Asesi :</b></label><br />
						<textarea class='form-control' name='umpan_balik_ia03'>";
																									if (!empty($getk['umpan_balik_ia03'])) {
																										echo $getk['umpan_balik_ia03'];
																									} else {
																										echo "";
																									}
																									echo "</textarea>
						</div>";

																									if ($jumttd > 0) {
																										echo "<div class='col-md-12'>
										<label class='' for=''>Persetujuan/ Tanda Tangan yang telah Anda berikan:</label>
										<br/>
										<img src='$ttdx[file]' width='400px'/>
										<br/>
									</div>";
																									} else {
																										echo "<div class='col-md-12'>
								<label class='' for=''>Tanda Tangan:</label>
								<br/>
								<div id='sig' ></div>
								<br/>
								<button id='clear'>Hapus Tanda Tangan</button>
								<textarea id='signature64' name='signed' style='display: none'></textarea>
							</div>
							<script type='text/javascript'>
								var sig = $('#sig').signature({syncField: '#signature64', syncFormat: 'PNG', color: '#58009F'});
								$('#clear').click(function(e) {
									e.preventDefault();
									sig.signature('clear');
									$('#signature64').val('');
								});
							</script></div>";
																									}
																									echo "<div class='box-footer'>
							<div class='col-md-4 col-sm-12 col-xs-12'>
									<a class='btn btn-danger form-control' id=reset-validate-form href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a>
							</div>
							<div class='col-md-4 col-sm-12 col-xs-12'>
									<a href='form-fr-ia-03.php?ida=$_GET[ida]&idj=$_GET[idj]' class='btn btn-primary form-control' target='_blank'>Unduh Formulir</a>
							</div>";
																									if ($jumttd == 0) {
																										echo "<div class='col-md-4 col-sm-12 col-xs-12'>
										<button type='submit' class='btn btn-success form-control' name='simpan'>Simpan Jawaban</button>
								</div>";
																									} else {
																										echo "<div class='col-md-4 col-sm-12 col-xs-12'>
										<button type='submit' class='btn btn-success form-control' name='simpan'>Ubah Jawaban</button>
								</div>";
																									}
																									echo "</div>
					</form>			
							</div>
							<!-- /.box-body -->
						</div>
						<!-- /.box -->
					</div>
					<!-- /.col -->
				</div>
				<!-- /.row -->
			</section>
			<!-- /.content -->";
		}
		// Bagian Input Hasil Asesmen FORM-FR.IA.04A
elseif ($_GET['module'] == 'form-fr-ia-04A') {
	$sqllogin = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_GET[ida]'";
	$login = $conn->query($sqllogin);
	$ketemu = $login->num_rows;
	$rowAgen = $login->fetch_assoc();
	$sqlgetjadwal = "SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
	$getjadwal = $conn->query($sqlgetjadwal);
	$jd = $getjadwal->fetch_assoc();
	$sqlgetskema = "SELECT * FROM `skema_kkni` WHERE `id`='$jd[id_skemakkni]'";
	$getskema = $conn->query($sqlgetskema);
	$sk = $getskema->fetch_assoc();
	$sqlgetasesordata = "SELECT * FROM `asesor` WHERE `no_ktp`='$_SESSION[namauser]'";
	$getasesordata = $conn->query($sqlgetasesordata);
	$asr = $getasesordata->fetch_assoc();
	$sqltuk = "SELECT * FROM `tuk` WHERE `id`='$jd[tempat_asesmen]'";
	$tuk = $conn->query($sqltuk);
	$tq = $tuk->fetch_assoc();
	$sqltukjenis = "SELECT `jenis_tuk` FROM `tuk_jenis` WHERE `id`='$tq[jenis_tuk]'";
	$tukjenis = $conn->query($sqltukjenis);
	$tukjen = $tukjenis->fetch_assoc();
	$noasr = 1;
	$getasesor = $conn->query("SELECT * FROM `jadwal_asesor` WHERE `id_jadwal`='$_GET[idj]'");
	$sqlcektandatangan = "SELECT * FROM `logdigisign` WHERE id_skema='$jd[id_skemakkni]' AND id_asesi='$_SESSION[namauser]' AND `penandatangan`='$asr[nama]' AND nama_dokumen='FR.IA.04A. DIT - DAFTAR INSTRUKSI TERSTRUKTUR (PENJELASAN PROYEK SINGKAT/ KEGIATAN TERSTRUKTUR LAINNYA' ORDER BY `waktu` DESC";
	$sqlcekgetak01 = "SELECT * FROM `asesmen_ia04A` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_jadwal`='$_GET[idj]' AND id_skemakkni=$jd[id_skemakkni]";
	$cekgetak01 = $conn->query($sqlcekgetak01);
	$ai04a=$cekgetak01->fetch_assoc();
	// UPDATE @FHM-PUSTI 1 AGUSTUS 2023
	$cektandatangan = $conn->query($sqlcektandatangan);
	$jumttd = $cektandatangan->num_rows;
	$ttdx = $cektandatangan->fetch_assoc();
	$sqlcektandatanganasesi = $conn->query("SELECT * FROM `logdigisign` WHERE id_skema='$jd[id_skemakkni]' AND id_asesi='$_GET[ida]' AND `penandatangan`='$rowAgen[nama]' AND nama_dokumen='FR.IA.04A. DIT - DAFTAR INSTRUKSI TERSTRUKTUR (PENJELASAN PROYEK SINGKAT/ KEGIATAN TERSTRUKTUR LAINNYA' ORDER BY `waktu` DESC");
	$ttdasesi = $sqlcektandatanganasesi->fetch_assoc();
	// var_dump("SELECT * FROM `logdigisign` WHERE id_skema='$jd[id_skemakkni]' AND id_asesi='$_GET[ida]' AND id_jadwal='$_GET[idj]' AND `penandatangan`='$rowAgen[nama]' AND nama_dokumen='FR.IA.04A. DIT - DAFTAR INSTRUKSI TERSTRUKTUR (PENJELASAN PROYEK SINGKAT/ KEGIATAN TERSTRUKTUR LAINNYA' ORDER BY `waktu` DESC");
	while ($gas = $getasesor->fetch_assoc()) {
		$sqlasesor = "SELECT * FROM `asesor` WHERE `id`='$gas[id_asesor]'";
		$asesor = $conn->query($sqlasesor);
		$asr = $asesor->fetch_assoc();
		if (!empty($asr['gelar_depan'])) {
			if (!empty($asr['gelar_blk'])) {
				$namaasesor = $asr['gelar_depan'] . " " . $asr['nama'] . ", " . $asr['gelar_blk'];
			} else {
				$namaasesor = $asr['gelar_depan'] . " " . $asr['nama'];
			}
		} else {
			if (!empty($asr['gelar_blk'])) {
				$namaasesor = $asr['nama'] . ", " . $asr['gelar_blk'];
			} else {
				$namaasesor = $asr['nama'];
			}
		}
		$noregasesor = $asr['no_induk'];
		$namaasesor = $noasr . '. ' . $namaasesor;
		$noregasesor = $noasr . '. ' . $noregasesor;
		$noasr++;
	}
	echo "<!-- Content Header (Page header) -->
		<section class='content-header'>
		  <h1>
		  Input FR.IA.04A
			<small>Skema $sk[judul]</small>
		  </h1>
		  <ol class='breadcrumb'>
			<li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
			<li class='active'>FR.IA.04A</li>
		  </ol>
		</section>";
	if (isset($_REQUEST['simpan'])) {
		$getumpanbalik=$conn->query("SELECT * FROM asesmen_ia04A a LEFT JOIN content_ia04A b ON b.id=a.id_pertanyaan WHERE a.id_skemakkni='$jd[id_skemakkni]' AND a.id_pertanyaan='$cta[id]' AND a.id_jadwal='$_GET[idj]' AND a.id_asesi='$_GET[ida]'");
		$cekak01 = $getumpanbalik->num_rows;
		$tglsekarang = date("Y-m-d");
		if ($cekak01 > 0) {
			$folderPath = "foto_tandatangan/";
			if (empty($_POST['signed'])) {
				// input tanggapan pendukung observasi
				$getcontentia04a = $conn->query("SELECT * FROM content_ia04A WHERE `id_skemakkni`='$sk[id]'");
				while ($unkb2 = $getunitkompetensib2->fetch_assoc()) {
						$postumpanbalik = 'umpan_balik' . $cfr04a['id'];
						$sqlinputak01 = "UPDATE `asesmen_ia04A` SET `umpan_balik`='" . $_POST[$postumpanbalik] . "' WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$jd[id_skemakkni]' AND `id_jadwal`='$_GET[idj]'";
						$conn->query($sqlinputak01);
				}
				echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Tanggapan Pertanyaan Pendukung Observasi oleh Asesi berhasil disimpan</h4>
				Terimakasih, Anda telah melakukan input <b>Tanggapan Pertanyaan Pendukung Observasi untuk Skema $sk[judul].</b><br>
				<a class='btn btn-warning form-control' href='?module=jadwal'>Kembali</a></div>
				<script>window.location = '$base_url/media.php?module=form-ia-04A&ida=$_GET[ida]&idj=$_GET[idj]'</script>";
			} else {
				$image_parts = explode(";base64,", $_POST['signed']);
				$image_type_aux = explode("image/", $image_parts[0]);
				$image_type = $image_type_aux[1];
				$image_base64 = base64_decode($image_parts[1]);
				$file = $folderPath . uniqid() . '.' . $image_type;
				file_put_contents($file, $image_base64);
				$url =  "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
				$iddokumen = md5($url);
				$escaped_url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
				$alamatip = $_SERVER['REMOTE_ADDR'];
				$sqlinputdigisign = "INSERT INTO `logdigisign`(`id_dokumen`, `id_asesi`, `id_skema`, `url_ditandatangani`, `nama_dokumen`, `penandatangan`, `file`, `ip`,`id_jadwal`) VALUES ('$iddokumen','$_SESSION[namauser]','$jd[id_skemakkni]','$escaped_url','FR.IA.04A. DIT - DAFTAR INSTRUKSI TERSTRUKTUR (PENJELASAN PROYEK SINGKAT/ KEGIATAN TERSTRUKTUR LAINNYA','$asr[nama]','$file','$alamatip','$_GET[idj]')";
				$conn->query($sqlinputdigisign);
				// input tanggapan pendukung observasi
				$getcontentia04a = $conn->query("SELECT * FROM content_ia04A WHERE `id_skemakkni`='$sk[id]'");
				while ($unkb2 = $getunitkompetensib2->fetch_assoc()) {
						$postumpanbalik = 'umpan_balik' . $cfr04a['id'];
						$sqlinputak01 = "UPDATE `asesmen_ia04A` SET `umpan_balik`='" . $_POST[$postumpanbalik] . "' WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$jd[id_skemakkni]' AND `id_jadwal`='$_GET[idj]'";
						$conn->query($sqlinputak01);
				}
				echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Tanggapan Pertanyaan Pendukung Observasi oleh Asesi berhasil disimpan</h4>
				Terimakasih, Anda telah melakukan input <b>Tanggapan Pertanyaan Pendukung Observasi untuk Skema $sk[judul], dan tanda tangan telah ditambahkan</b><br>
				<a class='btn btn-warning form-control' href='?module=jadwal'>Kembali</a></div>
				<script>window.location = '$base_url/media.php?module=form-fr-ia-04A&ida=$_GET[ida]&idj=$_GET[idj]'</script>";
			}
		} else {
			$folderPath = "../foto_tandatangan/";
			if (empty($_POST['signed'])) {
				// input tanggapan pendukung observasi
				$getcontentia04a = $conn->query("SELECT * FROM content_ia04A WHERE `id_skemakkni`='$sk[id]'");
				while ($cfr04a = $getcontentia04a->fetch_assoc()) {
						$postumpanbalik = 'umpan_balik' . $cfr04a['id'];
						if (!empty($_POST[$postumpanbalik])) {
							$postumpanbalik1 = $_POST[$postumpanbalik];
						} else {
							$postumpanbalik1 = "";
						}
						$inputia04A = "INSERT INTO `asesmen_ia04A`(`id_asesi`, `id_jadwal`, `id_pertanyaan`, `id_skemakkni`, `umpan_balik`) VALUES ('$_SESSION[namauser]','$_GET[idj]','$cfr04a[id]','$sk[id]','" . $postumpanbalik1 . "')";
						$conn->query($inputia04A);
						//}
					}
				echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Tanggapan Pertanyaan Pendukung Observasi oleh Asesi berhasil disimpan</h4>
				Terimakasih, Anda telah melakukan input <b>Tanggapan Pertanyaan Pendukung Observasi untuk Skema $sk[judul].</b><br>
				<a class='btn btn-warning form-control' href='?module=jadwal'>Kembali</a></div>
				<script>window.location = '$base_url/media.php?module=form-fr-ia-04A&ida=$_GET[ida]&idj=$_GET[idj]'</script>";
			} else {
				$image_parts = explode(";base64,", $_POST['signed']);
				$image_type_aux = explode("image/", $image_parts[0]);
				$image_type = $image_type_aux[1];
				$image_base64 = base64_decode($image_parts[1]);
				$file = $folderPath . uniqid() . '.' . $image_type;
				file_put_contents($file, $image_base64);
				$url =  "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
				$iddokumen = md5($url);
				$escaped_url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
				$alamatip = $_SERVER['REMOTE_ADDR'];
				$sqlinputdigisign = "INSERT INTO `logdigisign`(`id_dokumen`, `id_asesi`, `id_skema`, `url_ditandatangani`, `nama_dokumen`, `penandatangan`, `file`, `ip`, `id_jadwal`) VALUES ('$iddokumen','$_SESSION[namauser]','$jd[id_skemakkni]','$escaped_url','FR.IA.04A. DIT - DAFTAR INSTRUKSI TERSTRUKTUR (PENJELASAN PROYEK SINGKAT/ KEGIATAN TERSTRUKTUR LAINNYA','$asr[nama]','$file','$alamatip','$_GET[idj]')";
				$conn->query($sqlinputdigisign);
				// input tanggapan pendukung observasi
				$getcontentia04a = $conn->query("SELECT * FROM content_ia04A WHERE `id_skemakkni`='$sk[id]'");
				while ($cfr04a = $getcontentia04a->fetch_assoc()) {
					$postumpanbalik = 'umpan_balik' . $cfr04a['id'];
						if (!empty($_POST[$postumpanbalik])) {
							$postumpanbalik1 = $_POST[$postumpanbalik];
						} else {
							$postumpanbalik1 = "";
						}
						$inputia04A = "INSERT INTO `asesmen_ia04A`(`id_asesi`, `id_jadwal`, `id_pertanyaan`, `id_skemakkni`, `umpan_balik`) VALUES ('$_GET[ida]','$_GET[idj]','$cfr04a[id]','$sk[id]','" . $postumpanbalik1 . "')";
						$conn->query($inputia04A);
					//}
				}
				echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Tanggapan Pertanyaan Pendukung Observasi oleh Asesi berhasil disimpan</h4>
				Terimakasih, Anda telah melakukan untuk <b>Tanggapan Pertanyaan Pendukung Observasi untuk Skema $sk[judul], dan tanda tangan telah ditambahkan</b><br>
				<a class='btn btn-warning form-control' href='?module=jadwal'>Kembali</a></div>
				<script>window.location = 'media.php?module=form-fr-ia-04A&ida=$_GET[ida]&idj=$_GET[idj]'</script>";
			}
		}
	}
	$tanggalasesmen = tgl_indo($jd['tgl_asesmen']);
	$tgl_sekarang = date("Y-m-d");
	if ($tgl_sekarang >= $jd['tgl_asesmen']) {
		echo "<!-- Main content -->
		<section class='content'>
		  <div class='row'>
			<div class='col-xs-12'>
			  <div class='box'>
				<div class='box-body'>
				  <!-- form start -->
					<div class='box-body'>
						<h2>FR.IA.04A. DIT-Daftar Instruksi Terstruktur (Penjelasan Proyek Singkat/Kegiatan Terstruktur Lainnya)</h2>
						<form role='form' method='POST' enctype='multipart/form-data'>
						<table id='example9' class='table table-bordered table-striped'>
							<tr><td rowspan='2' width='25%'>Skema Sertifikasi (KKNI/Okupasi/Klaster)</td><td>Judul :</td><td>$sk[judul]</td></tr>
							<tr><td width='25%'>Nomor :</td><td>$sk[kode_skema]</td></tr>
							<tr><td width='25%'>TUK</td><td colspan='2'>$tukjen[jenis_tuk]</td></tr>
							<tr><td width='25%'>Nama Asesor</td><td colspan='2'>$namaasesor</td></tr>
							<tr><td width='25%'>Nama Asesi</td><td colspan='2'>$rowAgen[nama]</td></tr>
							<tr><td width='25%'>Tanggal</td><td colspan='2'>$tanggalasesmen</td></tr>
						</table>
						<table id='example9' class='table table-bordered table-striped'>
							<tr><th>PANDUAN BAGI ASESOR</th></tr>
							<tr><td><ul>
							<li>Tentukan proyek singkat atau kegiatan terstruktur lainnya yang harus dipersiapkan dan dipresentasikan oleh asesi.</li>
							<li>Proyek singkat atau kegiatan terstruktur lainnya dibuat untuk keseluruhan unit kompetensi
dalam Skema Sertifikasi atau untuk masing-masing kelompok pekerjaan.</li>
							<li>Kumpulkan hasil proyek singkat atau kegiatan terstruktur lainnya sesuai dengan hasil
keluaran yang telah ditetapkan.</li>
							</ul></td></tr>
						</table>
						";
		$sqlgetunitkompetensi = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$jd[id_skemakkni]'";
		$getunitkompetensi = $conn->query($sqlgetunitkompetensi);
		$jumunitnya0 = $getunitkompetensi->num_rows;
		$jumunitnya = $jumunitnya0 * 2; //kode dan judul unit adalah 2 baris
		$noku = 1;

		$contentasesmenIA04=$conn->query("SELECT * FROM content_ia04A WHERE `id_skemakkni`='$jd[id_skemakkni]'");
		// $contentasesmenIA04=$conn->query("SELECT * FROM asesmen_ia04A a LEFT JOIN content_ia04A b ON b.id=a.id_pertanyaan WHERE a.id_skemakkni='$jd[id_skemakkni]'");
		//while unitkompetensi ==================================================================
		while ($unk = $getunitkompetensi->fetch_assoc()) {
			// if ($noku == 1) {
			// 	echo "<tr><td rowspan='" . $jumunitnya . "' valign='middle'>Unit Kompetensi</td><td>Kode Unit</td><td>" . $unk['kode_unit'] . "</td></tr>";
			// 	echo "<tr><td>Judul Unit</td><td>" . $unk['judul'] . "</td></tr>";
			// } else {
			// 	echo "<tr><td>Kode Unit</td><td>" . $unk['kode_unit'] . "</td></tr>";
			// 	echo "<tr><td>Judul Unit</td><td>" . $unk['judul'] . "</td></tr>";
			// }
			// $noku++;
		}
		while ($cta = $contentasesmenIA04->fetch_assoc()) {
			$getumpanbalik=$conn->query("SELECT * FROM asesmen_ia04A a LEFT JOIN content_ia04A b ON b.id=a.id_pertanyaan WHERE a.id_skemakkni='$jd[id_skemakkni]' AND a.id_pertanyaan='$cta[id]' AND a.id_jadwal='$_GET[idj]' AND a.id_asesi='$_GET[ida]'");
			$unit_kompetensi=explode(',',$cta['kode_unit']);
			$rowspan=1+count($unit_kompetensi);
		?>
		<table id='example9' class='table table-bordered table-striped'>
		<tr>
			<td rowspan="<?php echo $rowspan ?>" valign="middle"><?= $cta['kelompok']?> </td>
			<td><b>No.</b></td>
			<td><b>Kode Unit</b></td>
			<td><b>Judul Unit</b></td>
		</tr>
		<tr>
			<?php 
			$nounitkom=1;
				for($i=0;$i < count($unit_kompetensi);++$i){
					$unit_kompetensi01=$conn->query("SELECT * FROM unit_kompetensi WHERE kode_unit='$unit_kompetensi[$i]' AND `id_skemakkni`=3");
					
					while($uk01 = $unit_kompetensi01->fetch_assoc()){
			?>
				<td><?= $nounitkom++; ?></td>
				<td><?= $uk01['kode_unit']?></td>
				<td><?= $uk01['judul']?></td>
		</tr>
		<?php 
					}
			}?>
		<tr>
			<td><?= $cta['content']?></td>
			<td colspan="3"><?= $cta['content1']?></td>
		</tr>
		<?php
			if($cta['content2']){
		?>
		<tr>
			<td><?= $cta['content2']?></td>
			<td colspan="3"><?= $cta['content3']?></td>
		</tr>
		<?php }
		if($getumpanbalik->num_rows == 0){
			echo "<tr>
			<td colspan='4'>
				<b>Umpan balik untuk Asesi :</b>
					<input class='form form-control' name='umpan_balik$cta[id]' placeholder='Umpan Balik Untuk Asesi' ></input>
			</td>
		</tr>";
		}else{
		while($gu = $getumpanbalik->fetch_assoc()){
		?>
		<tr>
			<td colspan="4">
					<b>Umpan balik untuk Asesi :</b>
					<input class="form form-control" name="umpan_balik<?=$cta['id']?>" placeholder="Umpan Balik Untuk Asesi" value="<?=$gu['umpan_balik']?>" ></input>
			</td>
		</tr>
		<?php
		}
		}
		// $nounit++;
		}
		//end while unitkompetensi =============================================
		echo "</table>";
		?>
		<table id='example9' class='table table-bordered table-striped'>
			<tr>
				<td>Tanda Tangan Asesi</td>
				<td>Tanda Tangan Asesor</td>
				<td>Nama dan Tanda Tangan Supervisor(Jika Ada)</td>
			</tr>
			<tr>
			<?php
				$url = "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
				$iddokumen = md5($url);
				if ($jumttd > 0) {
			?>
				<td><img src="../<?= $ttdasesi['file'] ?>" width="400px"/></td>
				<td>
					<img src="<?= $ttdx['file'] ?>" width="400px"/>
					</td>
				<td></td>
			</tr>
			</table>
					<?php
					} else {
					?>
			<td></td>
			</tr>
			</table>
			<div class='col-md-12'>
				<label class='' for=''>Tanda Tangan:</label>
				<br/>
				<div id='sig' ></div>
				<br/>
				<button id='clear'>Hapus Tanda Tangan</button>
				<textarea id='signature64' name='signed' style='display: none'></textarea>
			</div>
			<script type='text/javascript'>
				var sig = $('#sig').signature({syncField: '#signature64', syncFormat: 'PNG', color: '#58009F'});
				$('#clear').click(function(e) {
					e.preventDefault();
					sig.signature('clear');
					$('#signature64').val('');
				});
			</script></div>
		<?php
					}
		// $sqlcektandatangan = "SELECT * FROM `logdigisign` WHERE `id_dokumen`='$iddokumen' AND `penandatangan`='$_SESSION[namalengkap]' ORDER BY `id` DESC";
		echo "<div class='box-footer'>
							<div class='col-md-6 col-sm-12 col-xs-12'>
									<a class='btn btn-danger form-control' id=reset-validate-form href='?module=jadwal'>Kembali</a>
							</div>";
		if ($jumttd == 0) {
			echo "<div class='col-md-6 col-sm-12 col-xs-12'>
										<button type='submit' class='btn btn-success form-control' name='simpan'>Simpan</button>
								</div>";
		}
		echo "</div>
					</form>			
				</div>
				<!-- /.box-body -->
			  </div>
			  <!-- /.box -->
			</div>
			<!-- /.col -->
		  </div>
		  <!-- /.row -->
		</section>
		<!-- /.content -->";
	} else {
		echo "<!-- Main content -->
		<section class='content'>
		  <div class='row'>
			<div class='col-xs-12'>
			  <div class='box'>
				<div class='box-body'>
				<h2><font color='red'>Maaf, formulir ini belum bisa Anda akses sebelum tanggal Asesmen</font></h2>
				</div>
				<!-- /.box-body -->
			  </div>
			  <!-- /.box -->
			</div>
			<!-- /.col -->
		  </div>
		  <!-- /.row -->
		</section>
		<!-- /.content -->";
	}
}
		// Bagian Input Hasil Asesmen FORM-FR.IA.04B Asesor
		elseif ($_GET['module'] == 'form-fr-ia-04B') {
			$sqllogin = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_GET[ida]'";
			$login = $conn->query($sqllogin);
			$ketemu = $login->num_rows;
			$rowAgen = $login->fetch_assoc();
			// QUERY GET DATA ASESOR
			$queryasesor = "SELECT * FROM asesor WHERE no_ktp='$_SESSION[namauser]'";
			$qasesor = $conn->query($queryasesor);
			$asesor = $qasesor->fetch_assoc();

			$sqlgetjadwal = "SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
			$getjadwal = $conn->query($sqlgetjadwal);
			$jd = $getjadwal->fetch_assoc();
			$sqlgetskema = "SELECT * FROM `skema_kkni` WHERE `id`='$jd[id_skemakkni]'";
			$getskema = $conn->query($sqlgetskema);
			$sk = $getskema->fetch_assoc();
			$sqlgetasesordata = "SELECT * FROM `asesor` WHERE `no_ktp`='$_SESSION[namauser]'";
			$getasesordata = $conn->query($sqlgetasesordata);
			$asr = $getasesordata->fetch_assoc();
			$sqltuk = "SELECT * FROM `tuk` WHERE `id`='$jd[tempat_asesmen]'";
			$tuk = $conn->query($sqltuk);
			$tq = $tuk->fetch_assoc();
			$sqltukjenis = "SELECT `jenis_tuk` FROM `tuk_jenis` WHERE `id`='$tq[jenis_tuk]'";
			$tukjenis = $conn->query($sqltukjenis);
			$tukjen = $tukjenis->fetch_assoc();
			$noasr = 1;
			$getasesor = $conn->query("SELECT * FROM `jadwal_asesor` WHERE `id_jadwal`='$_GET[idj]'");

			$sqlcektandatangan = "SELECT * FROM `logdigisign` WHERE id_skema='$jd[id_skemakkni]' AND id_asesi='$_SESSION[namauser]' AND `penandatangan`='$asesor[nama]' AND nama_dokumen='FR.IA.04B. PENILAIAN PROYEK SINGKAT ATAU KEGIATAN TERSTRUKTUR LAINNYA' ORDER BY `waktu` DESC";
			$cektandatangan = $conn->query($sqlcektandatangan);
			$jumttd = $cektandatangan->num_rows;
			$ttdx = $cektandatangan->fetch_assoc();

			// QUERY GET DATA ASESI ASESMEN
			$sqlasesiasesmen =$conn->query("SELECT * FROM asesi_asesmen a WHERE a.id_skemakkni='$jd[id_skemakkni]' AND a.id_asesi='$_GET[ida]' AND a.id_jadwal='$_GET[idj]'");
			$gtas =$sqlasesiasesmen->fetch_assoc();
			// GET DATA ASESMEN FORM-FR-IA04B

			while ($gas = $getasesor->fetch_assoc()) {
				$sqlasesor = "SELECT * FROM `asesor` WHERE `id`='$gas[id_asesor]'";
				$asesor = $conn->query($sqlasesor);
				$asr = $asesor->fetch_assoc();
				if (!empty($asr['gelar_depan'])) {
					if (!empty($asr['gelar_blk'])) {
						$namaasesor = $asr['gelar_depan'] . " " . $asr['nama'] . ", " . $asr['gelar_blk'];
					} else {
						$namaasesor = $asr['gelar_depan'] . " " . $asr['nama'];
					}
				} else {
					if (!empty($asr['gelar_blk'])) {
						$namaasesor = $asr['nama'] . ", " . $asr['gelar_blk'];
					} else {
						$namaasesor = $asr['nama'];
					}
				}
				$noregasesor = $asr['no_induk'];
				$namaasesor = $noasr . '. ' . $namaasesor;
				$noregasesor = $noasr . '. ' . $noregasesor;
				$noasr++;
			}
			echo "<!-- Content Header (Page header) -->
			<section class='content-header'>
				<h1>
				Input FR.IA.04B
				<small>Skema $sk[judul]</small>
				</h1>
				<ol class='breadcrumb'>
				<li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
				<li class='active'>FR.IA.04A</li>
				</ol>
			</section>";
		if (isset($_REQUEST['simpan'])) {
			$sqlcekgetakia04B = "SELECT * FROM `asesmen_ia04B` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
			$cekgetia04B = $conn->query($sqlcekgetakia04B);
			$cekia04B = $cekgetia04B->num_rows;
			$tglsekarang = date("Y-m-d");
			if ($cekia04B > 0) {
				$folderPath = "../foto_tandatangan/";
				if (empty($_POST['signed'])) {
					// input tanggapan pendukung observasi
						$sqlgetpertanyaan04B = "SELECT * FROM `skema_pertanyaania04B` AS a LEFT JOIN lingkupkegiatan_formia04B AS b WHERE `b.id_skemakkni`=$_GET[ida] ORDER BY `a.id` ASC";
						$getpertanyaan04B = $conn->query($sqlgetpertanyaan04B);
						while ($gpp04B = $getpertanyaan04B->fetch_assoc()) {
							//if (!empty($gpp2['pertanyaan'])){
							$posttanggapan = 'tanggapan' . $gpp04B['id'];
							$postrekomendasi = 'rekomendasi' . $gpp04B['id'];
							$sqlinputia04b = "UPDATE `asesmen_ia04B` SET `tanggapan`='" . $_POST[$posttanggapan] . "', `rekomendasi`='" . $_POST[$postrekomendasi] . "' WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$jd[id_skemakkni]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='$gpp04B[id]'";
							$conn->query($sqlinputia04b);
							//}
						}
					$sqlinputia04b = "UPDATE `asesmen_ia04B` SET `tanggapan`='" . $_POST[$posttanggapan] . "', `rekomendasi`='" . $_POST[$postrekomendasi] . "' WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$jd[id_skemakkni]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='$gpp04B[id]'";
					$conn->query($sqlinputia04b);
					echo "<div class='alert alert-success alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Tanggapan Pertanyaan Pendukung Observasi oleh Asesor berhasil disimpan</h4>
					Terimakasih, Anda telah melakukan input <b>Tanggapan Pertanyaan Pendukung Observasi untuk Skema $sk[judul].</b><br>
					<a class='btn btn-warning form-control' href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a></div>"; ?>
									<script>
										window.location = "<?php echo $base_url ?>media.php?module=form-fr-ia-04B&ida=<?php echo $_GET['ida'] ?>&idj=<?php echo $_GET['idj'] ?>"
									</script>
								<?php
				} else {
					$image_parts = explode(";base64,", $_POST['signed']);
					$image_type_aux = explode("image/", $image_parts[0]);
					$image_type = $image_type_aux[1];
					$image_base64 = base64_decode($image_parts[1]);
					$file = $folderPath . uniqid() . '.' . $image_type;
					file_put_contents($file, $image_base64);
					$url =  "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
					$iddokumen = md5($url);
					$escaped_url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
					$alamatip = $_SERVER['REMOTE_ADDR'];
					$sqlinputdigisign = "INSERT INTO `logdigisign`(`id_dokumen`, id_skema, id_asesi, `url_ditandatangani`, `nama_dokumen`, `penandatangan`, `file`, `ip`) VALUES ('$iddokumen','$sk[id]','$_SESSION[namauser]','$escaped_url','FR.IA.04B. PERTANYAAN UNTUK MENDUKUNG OBSERVASI','$_SESSION[namalengkap]','$file','$alamatip')";
					$conn->query($sqlinputdigisign);
					// input tanggapan pendukung observasi
					$sqlgetpertanyaan04B = "SELECT *,a.id as idpertanyaan FROM `skema_pertanyaania04b` a LEFT JOIN lingkupkegiatan_formIA04B b ON b.id=a.id_lingkupkegiatan WHERE b.id_skemakkni=$sk[id] ORDER BY b.id ASC";
						$getpertanyaan04B = $conn->query($sqlgetpertanyaan04B);
						while ($gpp04B = $getpertanyaan04B->fetch_assoc()) {
							//if (!empty($gpp2['pertanyaan'])){
							$posttanggapan = 'tanggapan' . $gpp04B['idpertanyaan'];
							$postrekomendasi = 'rekomendasi' . $gpp04B['idpertanyaan'];
							$sqlinputia04b = "UPDATE `asesmen_ia04B` SET `tanggapan`='" . $_POST[$posttanggapan] . "', `rekomendasi`='" . $_POST[$postrekomendasi] . "' WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$jd[id_skemakkni]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='$gpp04B[id]'";
							$conn->query($sqlinputia04b);
							//}
						}
					$sqlinputak01b = "UPDATE `asesi_asesmen` SET `umpan_balik_ia03`='$_POST[umpan_balik_ia03]' WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
					$conn->query($sqlinputak01b);
					echo "<div class='alert alert-success alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Tanggapan Pertanyaan Pendukung Observasi oleh Asesor berhasil disimpan</h4>
					Terimakasih, Anda telah melakukan input <b>Tanggapan Pertanyaan Pendukung Observasi untuk Skema $sk[judul], dan tanda tangan telah ditambahkan</b><br>
					<a class='btn btn-warning form-control' href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a></div>"; ?>
									<script>
										window.location = "<?php echo $base_url ?>media.php?module=form-fr-ia-04B&ida=<?php echo $_GET['ida'] ?>&idj=<?php echo $_GET['idj'] ?>"
									</script>
								<?php
				}
			} else {
				$folderPath = "../foto_tandatangan/";
				if (empty($_POST['signed'])) {
					// input tanggapan pendukung observasi
						$sqlgetpertanyaan04B = "SELECT *,a.id as idpertanyaan FROM `skema_pertanyaania04b` a LEFT JOIN lingkupkegiatan_formIA04B b ON b.id=a.id_lingkupkegiatan WHERE b.id_skemakkni=$sk[id] ORDER BY b.id ASC";
						$getpertanyaan04B = $conn->query($sqlgetpertanyaan04B);
						while ($gpp04B = $getpertanyaan04B->fetch_assoc()) {
							//if (!empty($gpp2['pertanyaan'])){
							$posttanggapan = 'tanggapan' . $gpp04B['idpertanyaan'];
							$postkompetensikerja = 'status_kompetensikerja' . $gpp04B['idpertanyaan'];
							$postpencapaian = 'pencapaian' . $gpp04B['idpertanyaan'];
							if (empty($_POST[$posttanggapan])) {
								$posttanggapanx = "";
							} else {
								$posttanggapanx = $_POST[$posttanggapan];
							}
							if (empty($_POST[$postkompetensikerja])) {
								$postkompetensikerjax = "";
							} else {
								$postkompetensikerjax = $_POST[$postkompetensikerja];
							}
							$sqlinputia04B = "INSERT INTO `asesmen_ia04B`(`id_asesi`, `id_jadwal`, `id_pertanyaan`,`tanggapan`, `standar_kompetensikerja`, `id_skemakkni`, `pencapaian`) VALUES ('$_GET[ida]','$_GET[idj]','$gpp04B[idpertanyaan]','" . $posttanggapanx . "','" . $postkompetensikerja . "','$sk[id]','" . $postpencapaian . "')";
							$conn->query($sqlinputia04B);
							//}
						}
						$sqlinputak01b = "UPDATE `asesi_asesmen` SET `rekomendasi_IA04B`='$_POST[rekomendasi]' WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
						
						$conn->query($sqlinputak01b);
					echo "<div class='alert alert-success alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Tanggapan Pertanyaan Pendukung Observasi oleh Asesor berhasil disimpan</h4>
					Terimakasih, Anda telah melakukan input <b>Tanggapan Pertanyaan Pendukung Observasi untuk Skema $sk[judul].</b><br>
					<a class='btn btn-warning form-control' href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a></div>"; ?>
									<script>
										window.location = "<?php echo $base_url ?>media.php?module=form-fr-ia-04B&ida=<?php echo $_GET['ida'] ?>&idj=<?php echo $_GET['idj'] ?>"
									</script>
								<?php
				} else {
					$image_parts = explode(";base64,", $_POST['signed']);
					$image_type_aux = explode("image/", $image_parts[0]);
					$image_type = $image_type_aux[1];
					$image_base64 = base64_decode($image_parts[1]);
					$file = $folderPath . uniqid() . '.' . $image_type;
					file_put_contents($file, $image_base64);
					$url =  "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
					$iddokumen = md5($url);
					$escaped_url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
					$alamatip = $_SERVER['REMOTE_ADDR'];
					$sqlinputdigisign = "INSERT INTO `logdigisign`(`id_dokumen`, id_skema, id_asesi, `url_ditandatangani`, `nama_dokumen`, `penandatangan`, `file`, `ip`) VALUES ('$iddokumen','$sk[id]','$_SESSION[namauser]','$escaped_url','FR.IA.04B. PENILAIAN PROYEK SINGKAT ATAU KEGIATAN TERSTRUKTUR LAINNYA','$_SESSION[namalengkap]','$file','$alamatip')";
					$conn->query($sqlinputdigisign);
					// input tanggapan pendukung observasi
						$sqlgetpertanyaan04B = "SELECT *,a.id as idpertanyaan FROM `skema_pertanyaania04b` a LEFT JOIN lingkupkegiatan_formIA04B b ON b.id=a.id_lingkupkegiatan WHERE b.id_skemakkni=$sk[id] ORDER BY b.no_urutan ASC";
						$getpertanyaan04B = $conn->query($sqlgetpertanyaan04B);
						while ($gpp04B = $getpertanyaan04B->fetch_assoc()) {
							$posttanggapan = 'tanggapan' . $gpp04B['idpertanyaan'];
							$postkompetensikerja = 'standar_kompetensikerja' . $gpp04B['idpertanyaan'];
							$postpencapaian = 'pencapaian' . $gpp04B['idpertanyaan'];

							$sqlinputia04B = "INSERT INTO `asesmen_ia04B`(`id_asesi`, `id_jadwal`, `id_pertanyaan`,`tanggapan`, `standar_kompetensikerja`, `id_skemakkni`, `pencapaian`) VALUES ('$_GET[ida]','$_GET[idj]','$gpp04B[idpertanyaan]','" . $_POST[$posttanggapan] . "','" . $_POST[$postkompetensikerja] . "','$sk[id]','" . $_POST[$postpencapaian] . "')";
							$conn->query($sqlinputia04B);
						}
					$sqlinputak01b = "UPDATE `asesi_asesmen` SET `rekomendasi_IA04B`='$_POST[rekomendasi]' WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
					$conn->query($sqlinputak01b);	
					echo "<div class='alert alert-success alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Tanggapan Pertanyaan Pendukung Observasi oleh Asesor berhasil disimpan</h4>
					Terimakasih, Anda telah melakukan untuk <b>Tanggapan Pertanyaan Pendukung Observasi untuk Skema $sk[judul], dan tanda tangan telah ditambahkan</b><br>
					<a class='btn btn-warning form-control' href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a></div>"; ?>
									<script>
										window.location = "<?php echo $base_url ?>media.php?module=form-fr-ia-04B&ida=<?php echo $_GET['ida'] ?>&idj=<?php echo $_GET['idj'] ?>"
									</script>
					<?php
					}
				}
			}
			$tanggalasesmen = tgl_indo($jd['tgl_asesmen']);
			echo "<!-- Main content -->
			<section class='content'>
				<div class='row'>
					<div class='col-xs-12'>
						<div class='box'>
							<div class='box-body'>
								<!-- form start -->
								<div class='box-body'>
						<h2>FR.IA.04B. PENILAIAN PROYEK SINGKAT ATAU KEGIATAN TERSTRUKTUR LAINNYA</h2>
						<form role='form' method='POST' enctype='multipart/form-data'>
						<table id='example9' class='table table-bordered table-striped'>
							<tr><td rowspan='2' width='25%'>Skema Sertifikasi (KKNI/Okupasi/Klaster)</td><td>Judul :</td><td>$sk[judul]</td></tr>
							<tr><td width='25%'>Nomor :</td><td>$sk[kode_skema]</td></tr>
							<tr><td width='25%'>TUK</td><td colspan='2'>$tukjen[jenis_tuk]</td></tr>
							<tr><td width='25%'>Nama Asesor</td><td colspan='2'>$namaasesor</td></tr>
							<tr><td width='25%'>Nama Asesi</td><td colspan='2'>$rowAgen[nama]</td></tr>
							<tr><td width='25%'>Tanggal</td><td colspan='2'>$tanggalasesmen</td></tr>
						</table>
						<table id='example9' class='table table-bordered table-striped'>
							<tr><th>PANDUAN BAGI ASESOR</th></tr>
							<tr><td><ul>
							<li>Lakukan penilaian pencapaian hasil proyek singkat atau kegiatan terstruktur lainnya melalui presentasi.</li>
							<li>Penilaian dilakukan sesuai dengan <b>FR IA 04A. DIT. Daftar Instruksi Terstruktur (Penjelasan
Proyek Singkat/ Kegiatan Terstruktur Lainnya)</b></li>
							<li>Pertanyaan disampaikan oleh asesor setelah asesi melakukan presentasi proyek singkat/
kegiatan terstruktur lainnya.</li>
							<li>Pertanyaan dapat dikembangkan oleh asesor berdasarkan dokumen presentasi dan atau hasil
presentasi</li>
							<li>Pertanyaan yang disampaikan untuk pemenuhan pencapaian 5 dimensi kompetensi.</li>
							<li>Isilah kolom lingkup penyajian proyek atau kegiatan terstruktur lainnya sesuai sektor/ sub-sektor/ profesi.</li>
							<li>Berikan keputusan pencapaian berdasarkan kesimpulan jawaban asesi.</li>
							</ul></td></tr>
						</table>
						<table id='example9' class='table table-bordered table-striped'>
							<thead>	
								<tr>
									<td rowspan='2' colspan='4' align='center'><b>Aspek Penilaian</b></td>
									<td colspan='2'><b>Pencapaian</b></td>
								</tr>
								<tr>
									<td rowspan='2'><b>Ya</b></td>
									<td rowspan='2'><b>Tidak</b></td>
								</tr>
								<tr>
									<td><b>No.</b></td>
									<td><b>Lingkup Penyajian proyek atau kegiatan terstruktur lainnya</b></td>
									<td><b>Lingkup Penyajian proyek atau kegiatan terstruktur lainnya</b></td>
									<td><b>Kesesuaian dengan standar kompetensi kerja (unit/elemen/KUK)</b></td>
								</tr>
							</thead>
						<tbody>";
						$noglk=1;
						$countfria04B=$conn->query("SELECT * FROM asesmen_ia04B a
								WHERE a.id_skemakkni=$jd[id_skemakkni] AND a.id_asesi='$_GET[ida]' AND a.id_jadwal='$_GET[idj]' ORDER BY a.id ASC")->num_rows;
							$getpertanyaanIA04B =$conn->query("SELECT *,a.id as idpertanyaan FROM `skema_pertanyaania04b` a LEFT JOIN lingkupkegiatan_formIA04B b ON b.id=a.id_lingkupkegiatan WHERE b.id_skemakkni=$sk[id] ORDER BY b.id ASC ");
							
							while ($gp = $getpertanyaanIA04B->fetch_assoc()){
								if($countfria04B > 0){
									$getfria04B=$conn->query("SELECT * FROM asesmen_ia04B a
								WHERE a.id_skemakkni=$jd[id_skemakkni] AND a.id_asesi='$_GET[ida]' AND a.id_jadwal='$_GET[idj]' AND a.id_pertanyaan='$gp[idpertanyaan]'");
								$gfr=$getfria04B->fetch_assoc();
									// while($gfr = $getfria04B->fetch_assoc()){
									echo "<tr>
											<td>$noglk</td>
											<td>$gp[lingkupkegiatan]</td>
											<td>
												$gp[pertanyaan]
												<input class='form form-control' name='tanggapan$gp[idpertanyaan]' placeholder='Tanggapan' value='$gfr[tanggapan]'></input>
											</td>
											<td><textarea name='standar_kompetensikerja$gp[idpertanyaan]' class='form form-control'>$gfr[standar_kompetensikerja]</textarea></td>"; ?>
											<td>
												<div class="form-check">
													<input class="form-check-input" type="radio" name="pencapaian<?= $gp['idpertanyaan'] ?>" id="flexRadioDefault<?= $gp['idpertanyaan']?>" value="Ya" <?php if($gfr['pencapaian'] == 'Ya'){ echo "checked"; }?>>
												</div>
											</td>
											<td>
												<div class='form-check'>
													<input class="form-check-input" type="radio" name="pencapaian<?= $gp['idpertanyaan'] ?>" id="flexRadioDefault<?= $gp['idpertanyaan'] ?>" value="Tidak" <?php if($gfr['pencapaian'] == 'Tidak'){ echo "checked"; }?>>
												</div>
											</td>
									<?php
										echo "</tr>";
									// }
								}else{
									echo "<tr>
									<td>$noglk</td>
									<td>$gp[lingkupkegiatan]</td>
									<td>
										$gp[pertanyaan]
										<input class='form form-control' name='tanggapan$gp[idpertanyaan]' placeholder='Tanggapan'></input>
									</td>
									<td><textarea name='standar_kompetensikerja$gp[idpertanyaan]' class='form form-control'></textarea></td>
									<td>
										<div class='form-check'>
											<input class='form-check-input' type='radio' name='pencapaian$gp[idpertanyaan]' id='flexRadioDefault$gp[idpertanyaan]' value='Ya'>
										</div>
									</td>
									<td>
										<div class='form-check'>
											<input class='form-check-input' type='radio' name='pencapaian$gp[idpertanyaan]' id='flexRadioDefault$gp[idpertanyaan]' value='Tidak'>
										</div>
										</td>
									</tr>";
								}
								$noglk++;
							}
						echo "</tbody></table>
						<table id='example9' class='table table-bordered table-striped'>
							<tr>
								<td>Rekomendasi Asesor</td>
								<td colspan='2'>Asesi telah memenuhi/belum memenuhi pencapaian seluruh kriteria unjuk
									kerja, direkomendasikan:<br>";
						?>
									<div class="form-check">
										<input class="form-check-input" type="radio" name="rekomendasi" value="K" id="flexRadioDefault1" <?php if($gtas['rekomendasi_IA04B'] == 'K'){ echo 'checked'; }?>>
										<label class="form-check-label" for="flexRadioDefault1">
											Kompeten
										</label>
									</div>		
									<div class="form-check">
										<input class="form-check-input" type="radio" name="rekomendasi" value="BK" id="flexRadioDefault2" <?php if($gtas['rekomendasi_IA04B'] == 'BK'){ echo 'checked'; }?>>
										<label class="form-check-label" for="flexRadioDefault1">
											Belum Kompeten
										</label>
									</div>
						<?php		
								echo "</td>
							</tr>
							<tr>
								<td colspan='3'><b>Asesi :</b> </td>
							</tr>
							<tr>
								<td>Nama </td>
								<td>:</td>
								<td>$rowAgen[nama]</td>
							</tr>
							<tr>
								<td>Tanda Tangan/Tanggal </td>
								<td>:</td>
								<td></td>
							</tr>
							<tr>
								<td colspan='3'><b>Asesor :</b> </td>
							</tr>
							<tr>
								<td>Nama </td>
								<td>:</td>
								<td>$namaasesor</td>
							</tr>
							<tr>
								<td>No. Reg </td>
								<td>:</td>
								<td>$noregasesor</td>
							</tr>
							<tr>
								<td>Tanda Tangan/Tanggal </td>
								<td>:</td>";
							$sqlgetkeputusan = "SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
							$getkeputusan = $conn->query($sqlgetkeputusan);
							$getk = $getkeputusan->fetch_assoc();
							if ($jumttd > 0) {
							echo	"<td><img src='$ttdx[file]' width='400px'/></td>
							</tr>
						</table>";
					} else {
						echo "
						<td></td>
						</tr>
						</table>
						<div class='col-md-12'>
								<label class='' for=''>Tanda Tangan:</label>
								<br/>
								<div id='sig' ></div>
								<br/>
								<button id='clear'>Hapus Tanda Tangan</button>
								<textarea id='signature64' name='signed' style='display: none'></textarea>
							</div>
							<script type='text/javascript'>
								var sig = $('#sig').signature({syncField: '#signature64', syncFormat: 'PNG', color: '#58009F'});
								$('#clear').click(function(e) {
									e.preventDefault();
									sig.signature('clear');
									$('#signature64').val('');
								});
							</script></div>";
																									}
																									echo "<div class='box-footer'>
							<div class='col-md-4 col-sm-12 col-xs-12'>
									<a class='btn btn-danger form-control' id=reset-validate-form href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a>
							</div>
							<div class='col-md-4 col-sm-12 col-xs-12'>
									<a href='form-fr-ia-03.php?ida=$_GET[ida]&idj=$_GET[idj]' class='btn btn-primary form-control' target='_blank'>Unduh Formulir</a>
							</div>";
																									if ($jumttd == 0) {
																										echo "<div class='col-md-4 col-sm-12 col-xs-12'>
										<button type='submit' class='btn btn-success form-control' name='simpan'>Simpan Jawaban</button>
								</div>";
																									} else {
																										echo "<div class='col-md-4 col-sm-12 col-xs-12'>
										<button type='submit' class='btn btn-success form-control' name='simpan'>Ubah Jawaban</button>
								</div>";
																									}
																									echo "</div>
					</form>			
							</div>
							<!-- /.box-body -->
						</div>
						<!-- /.box -->
					</div>
					<!-- /.col -->
				</div>
				<!-- /.row -->
			</section>
			<!-- /.content -->";
		}
		// Bagian Input Hasil Asesmen FORM-FR.IA.06 Asesor
		elseif ($_GET['module'] == 'form-fr-ia-06') {
			$sqllogin = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_GET[ida]'";
			$login = $conn->query($sqllogin);
			$ketemu = $login->num_rows;
			$rowAgen = $login->fetch_assoc();
			$sqlgetjadwal = "SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
			$getjadwal = $conn->query($sqlgetjadwal);
			$jd = $getjadwal->fetch_assoc();
			$sqlgetskema = "SELECT * FROM `skema_kkni` WHERE `id`='$jd[id_skemakkni]'";
			$getskema = $conn->query($sqlgetskema);
			$sk = $getskema->fetch_assoc();
			$sqltuk = "SELECT * FROM `tuk` WHERE `id`='$jd[tempat_asesmen]'";
			$tuk = $conn->query($sqltuk);
			$tq = $tuk->fetch_assoc();
			$sqltukjenis = "SELECT `jenis_tuk` FROM `tuk_jenis` WHERE `id`='$tq[jenis_tuk]'";
			$tukjenis = $conn->query($sqltukjenis);
			$tukjen = $tukjenis->fetch_assoc();
			$noasr = 1;
			$getasesor = $conn->query("SELECT * FROM `jadwal_asesor` WHERE `id_jadwal`='$_GET[idj]'");
			while ($gas = $getasesor->fetch_assoc()) {
				$sqlasesor = "SELECT * FROM `asesor` WHERE `id`='$gas[id_asesor]'";
				$asesor = $conn->query($sqlasesor);
				$asr = $asesor->fetch_assoc();
				if (!empty($asr['gelar_depan'])) {
					if (!empty($asr['gelar_blk'])) {
						$namaasesor = $asr['gelar_depan'] . " " . $asr['nama'] . ", " . $asr['gelar_blk'];
					} else {
						$namaasesor = $asr['gelar_depan'] . " " . $asr['nama'];
					}
				} else {
					if (!empty($asr['gelar_blk'])) {
						$namaasesor = $asr['nama'] . ", " . $asr['gelar_blk'];
					} else {
						$namaasesor = $asr['nama'];
					}
				}
				$noregasesor = $asr['no_induk'];
				$namaasesor = $noasr . '. ' . $namaasesor;
				$noregasesor = $noasr . '. ' . $noregasesor;
				$noasr++;
			}
			echo "<!-- Content Header (Page header) -->
			<section class='content-header'>
				<h1>
				Input FR.IA.06
				<small>Skema $sk[judul]</small>
				</h1>
				<ol class='breadcrumb'>
				<li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
				<li class='active'>FR.IA.06</li>
				</ol>
			</section>";
																									if (isset($_REQUEST['simpan'])) {
																										$sqlcekgetak01 = "SELECT * FROM `asesmen_ia06` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																										$cekgetak01 = $conn->query($sqlcekgetak01);
																										$cekak01 = $cekgetak01->num_rows;
																										$tglsekarang = date("Y-m-d");
																										if ($cekak01 > 0) {
																											$folderPath = "../foto_tandatangan/";
																											if (empty($_POST['signed'])) {
																												// input jawaban pendukung observasi
																												$sqlgetunitkompetensib2 = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
																												$getunitkompetensib2 = $conn->query($sqlgetunitkompetensib2);
																												while ($unkb2 = $getunitkompetensib2->fetch_assoc()) {
																													$sqlgetpertanyaan2 = "SELECT * FROM `skema_pertanyaanesai` WHERE `id_unitkompetensi`='$unkb2[id]' ORDER BY `id` ASC";
																													$getpertanyaan2 = $conn->query($sqlgetpertanyaan2);
																													while ($gpp2 = $getpertanyaan2->fetch_assoc()) {
																														//if (!empty($gpp2['pertanyaan'])){
																														$postjawaban = 'rekomendasi' . $gpp2['id'];
																														$sqlcekjawaban2 = "SELECT * FROM `asesmen_ia06` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='$gpp2[id]'";
																														$cekjawaban2 = $conn->query($sqlcekjawaban2);
																														$jjw2 = $cekjawaban2->fetch_assoc();
																														$sqlinputak01 = "UPDATE `asesmen_ia06` SET `rekomendasi`='" . $_POST[$postjawaban] . "' WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$jd[id_skemakkni]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='$gpp2[id]'";
																														$conn->query($sqlinputak01);
																														//}
																													}
																												}
																												//$sqlinputak01b="UPDATE `asesi_asesmen` SET `umpan_balik_ia06`='$_POST[umpan_balik_ia06]' WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																												//$conn->query($sqlinputak01b);
																												echo "<div class='alert alert-success alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Keputusan/ Penilaian atas Jawaban Pertanyaan Tertulis Esai oleh Asesi berhasil disimpan</h4>
					Terimakasih, Anda telah melakukan penilaian atas <b>Jawaban Pertanyaan Tertulis Esai untuk Skema $sk[judul].</b><br>
					<a class='btn btn-warning form-control' href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a></div>";
																											} else {
																												$image_parts = explode(";base64,", $_POST['signed']);
																												$image_type_aux = explode("image/", $image_parts[0]);
																												$image_type = $image_type_aux[1];
																												$image_base64 = base64_decode($image_parts[1]);
																												$file = $folderPath . uniqid() . '.' . $image_type;
																												file_put_contents($file, $image_base64);
																												$url =  "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
																												$iddokumen = md5($url);
																												$escaped_url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
																												$alamatip = $_SERVER['REMOTE_ADDR'];
																												$sqlinputdigisign = "INSERT INTO `logdigisign`(`id_dokumen`, id_skema, id_asesi, `url_ditandatangani`, `nama_dokumen`, `penandatangan`, `file`, `ip`) VALUES ('$iddokumen','$sk[id]','$_SESSION[namauser]','$escaped_url','FR.IA.06. PERTANYAAN TERTULIS ESAI','$_SESSION[namalengkap]','$file','$alamatip')";
																												$conn->query($sqlinputdigisign);
																												// input jawaban pendukung observasi
																												$sqlgetunitkompetensib2 = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
																												$getunitkompetensib2 = $conn->query($sqlgetunitkompetensib2);
																												while ($unkb2 = $getunitkompetensib2->fetch_assoc()) {
																													$sqlgetpertanyaan2 = "SELECT * FROM `skema_pertanyaanesai` WHERE `id_unitkompetensi`='$unkb2[id]' ORDER BY `id` ASC";
																													$getpertanyaan2 = $conn->query($sqlgetpertanyaan2);
																													while ($gpp2 = $getpertanyaan2->fetch_assoc()) {
																														//if (!empty($gpp2['pertanyaan'])){
																														$postjawaban = 'rekomendasi' . $gpp2['id'];
																														$sqlcekjawaban2 = "SELECT * FROM `asesmen_ia06` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='$gpp2[id]'";
																														$cekjawaban2 = $conn->query($sqlcekjawaban2);
																														$jjw2 = $cekjawaban2->fetch_assoc();
																														$sqlinputak01 = "UPDATE `asesmen_ia06` SET `rekomendasi`='" . $_POST[$postjawaban] . "' WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$jd[id_skemakkni]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='$gpp2[id]'";
																														$conn->query($sqlinputak01);
																														//}
																													}
																												}
																												//$sqlinputak01b="UPDATE `asesi_asesmen` SET `umpan_balik_ia06`='$_POST[umpan_balik_ia06]' WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																												//$conn->query($sqlinputak01b);
																												echo "<div class='alert alert-success alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Keputusan/ Penilaian atas Jawaban Pertanyaan Tertulis Esai oleh Asesi berhasil disimpan</h4>
					Terimakasih, Anda telah melakukan penilaaian atas <b>Jawaban Pertanyaan Tertulis Esai untuk Skema $sk[judul], dan tanda tangan telah ditambahkan</b><br>
					<a class='btn btn-warning form-control' href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a></div>";
																											}
																										}
																									}
																									$tanggalasesmen = tgl_indo($jd['tgl_asesmen']);
																									echo "<!-- Main content -->
		<section class='content'>
			<div class='row'>
			<div class='col-xs-12'>
				<div class='box'>
				<div class='box-body'>
					<!-- form start -->
					<div class='box-body'>
						<h2>FR.IA.06. PERTANYAAN TERTULIS ESAI</h2>
						<form role='form' method='POST' enctype='multipart/form-data'>
						<table id='example9' class='table table-bordered table-striped'>
							<tr><td rowspan='2' width='25%'>Skema Sertifikasi (KKNI/Okupasi/Klaster)</td><td>Judul :</td><td>$sk[judul]</td></tr>
							<tr><td width='25%'>Nomor :</td><td>$sk[kode_skema]</td></tr>
							<tr><td width='25%'>TUK</td><td colspan='2'>$tukjen[jenis_tuk]</td></tr>
							<tr><td width='25%'>Nama Asesor</td><td colspan='2'>$namaasesor</td></tr>
							<tr><td width='25%'>Nama Asesi</td><td colspan='2'>$rowAgen[nama]</td></tr>
							<tr><td width='25%'>Tanggal</td><td colspan='2'>$tanggalasesmen</td></tr>
						</table>
						<table id='example9' class='table table-bordered table-striped'>";
																									$sqlgetunitkompetensi = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
																									$getunitkompetensi = $conn->query($sqlgetunitkompetensi);
																									$jumunitnya0 = $getunitkompetensi->num_rows;
																									$jumunitnya = $jumunitnya0 * 2; //kode dan judul unit adalah 2 baris
																									$noku = 1;
																									//while unitkompetensi ==================================================================
																									while ($unk = $getunitkompetensi->fetch_assoc()) {
																										if ($noku == 1) {
																											echo "<tr><td rowspan='" . $jumunitnya . "' valign='middle'>Unit Kompetensi</td><td>Kode Unit</td><td>" . $unk['kode_unit'] . "</td></tr>";
																											echo "<tr><td>Judul Unit</td><td>" . $unk['judul'] . "</td></tr>";
																										} else {
																											echo "<tr><td>Kode Unit</td><td>" . $unk['kode_unit'] . "</td></tr>";
																											echo "<tr><td>Judul Unit</td><td>" . $unk['judul'] . "</td></tr>";
																										}
																										$noku++;
																									}
																									//end while unitkompetensi =============================================
																									echo "</table>
						<table id='example9' class='table table-bordered table-striped'>
						<thead><tr><th colspan='2'>Pertanyaan</th><th>Rekomendasi</th></tr></thead>
						<tbody>";
																									// pertanyaan pendukung observasi
																									$sqlgetunitkompetensib = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
																									$getunitkompetensib = $conn->query($sqlgetunitkompetensib);
																									$nopp = 1;
																									while ($unkb = $getunitkompetensib->fetch_assoc()) {
																										$sqlgetpertanyaan = "SELECT * FROM `skema_pertanyaanesai` WHERE `id_unitkompetensi`='$unkb[id]' ORDER BY `id` ASC";
																										$getpertanyaan = $conn->query($sqlgetpertanyaan);
																										while ($gpp = $getpertanyaan->fetch_assoc()) {
																											if (!empty($gpp['pertanyaan'])) {
																												$sqlcekjawaban = "SELECT * FROM `asesmen_ia06` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='$gpp[id]'";
																												$cekjawaban = $conn->query($sqlcekjawaban);
																												$jjw = $cekjawaban->fetch_assoc();
																												echo "<tr><td>$nopp.</td><td>" . $gpp['pertanyaan'] . "</td><td rowspan='2'>
									<input type='radio' name='rekomendasi$gpp[id]' id='rekomendasi$gpp[id]1' value='K' ";
																												if ($jjw['rekomendasi'] == 'K') {
																													echo " checked";
																												} else {
																													echo "";
																												}
																												echo ">&nbsp;Kompeten<br>
									<input type='radio' name='rekomendasi$gpp[id]' id='rekomendasi$gpp[id]2' value='BK' ";
																												if ($jjw['rekomendasi'] == 'BK') {
																													echo " checked";
																												} else {
																													echo "";
																												}
																												echo ">&nbsp;Belum Kompeten</td></tr>
									<tr><td colspan='2'>Jawaban:<br>
									<textarea class='form-control' name='jawaban$gpp[id]' disabled>";
																												if (!empty($jjw['jawaban'])) {
																													echo $jjw['jawaban'];
																												} else {
																													echo "";
																												}
																												echo "</textarea>
									</td></tr>";
																												$nopp++;
																											}
																										}
																									}
																									echo "</tbody></table>";
																									/* $sqlgetkeputusan="SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
						$getkeputusan=$conn->query($sqlgetkeputusan);
						$getk=$getkeputusan->fetch_assoc();
						echo "<div class='col-md-12'>
						<label><b>Umpan Balik Asesi :</b></label><br />
						<textarea class='form-control' name='umpan_balik_ia06'>";
						if (!empty($getk['umpan_balik_ia06'])){echo $getk['umpan_balik_ia06'];}else{echo "";}
						echo "</textarea>
						</div>"; */
																									// cek tandatangan digital
																									$url = "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
																									$iddokumen = md5($url);
																									$sqlcektandatangan = "SELECT * FROM `logdigisign` WHERE `id_dokumen`='$iddokumen' AND `penandatangan`='$_SESSION[namalengkap]' ORDER BY `id` DESC";
																									$cektandatangan = $conn->query($sqlcektandatangan);
																									$jumttd = $cektandatangan->num_rows;
																									$ttdx = $cektandatangan->fetch_assoc();
																									if ($jumttd > 0) {
																										echo "<div class='col-md-12'>
										<label class='' for=''>Persetujuan/ Tanda Tangan yang telah Anda berikan:</label>
										<br/>
										<img src='../$ttdx[file]' width='400px'/>
										<br/>
									</div>";
																									}
																									echo "<div class='col-md-12'>
							<label class='' for=''>Tanda Tangan:</label>
							<br/>
							<div id='sig' ></div>
							<br/>
							<button id='clear'>Hapus Tanda Tangan</button>
							<textarea id='signature64' name='signed' style='display: none'></textarea>
						</div>
						<script type='text/javascript'>
							var sig = $('#sig').signature({syncField: '#signature64', syncFormat: 'PNG', color: '#58009F'});
							$('#clear').click(function(e) {
								e.preventDefault();
								sig.signature('clear');
								$('#signature64').val('');
							});
						</script></div>";
																									echo "<div class='box-footer bg-black'>
							<div class='col-md-6 col-sm-12 col-xs-12'>
									<a class='btn btn-danger form-control' id=reset-validate-form href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a>
							</div>
							<div class='col-md-6 col-sm-12 col-xs-12'>
									<button type='submit' class='btn btn-success form-control' name='simpan'>Simpan Jawaban</button>
							</div>
						</div>
					</form>			
				</div>
				<!-- /.box-body -->
				</div>
				<!-- /.box -->
			</div>
			<!-- /.col -->
			</div>
			<!-- /.row -->
		</section>
		<!-- /.content -->";
																								}
																								// Bagian Input Hasil Asesmen FORM-FR.IA.07 Asesor
																								elseif ($_GET['module'] == 'form-fr-ia-07') {
																									$sqllogin = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_GET[ida]'";
																									$login = $conn->query($sqllogin);
																									$ketemu = $login->num_rows;
																									$rowAgen = $login->fetch_assoc();
																									$sqlgetjadwal = "SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
																									$getjadwal = $conn->query($sqlgetjadwal);
																									$jd = $getjadwal->fetch_assoc();
																									$sqlgetskema = "SELECT * FROM `skema_kkni` WHERE `id`='$jd[id_skemakkni]'";
																									$getskema = $conn->query($sqlgetskema);
																									$sk = $getskema->fetch_assoc();
																									$sqltuk = "SELECT * FROM `tuk` WHERE `id`='$jd[tempat_asesmen]'";
																									$tuk = $conn->query($sqltuk);
																									$tq = $tuk->fetch_assoc();
																									$sqltukjenis = "SELECT `jenis_tuk` FROM `tuk_jenis` WHERE `id`='$tq[jenis_tuk]'";
																									$tukjenis = $conn->query($sqltukjenis);
																									$tukjen = $tukjenis->fetch_assoc();
																									$noasr = 1;
																									$getasesor = $conn->query("SELECT * FROM `jadwal_asesor` WHERE `id_jadwal`='$_GET[idj]'");
																									while ($gas = $getasesor->fetch_assoc()) {
																										$sqlasesor = "SELECT * FROM `asesor` WHERE `id`='$gas[id_asesor]'";
																										$asesor = $conn->query($sqlasesor);
																										$asr = $asesor->fetch_assoc();
																										if (!empty($asr['gelar_depan'])) {
																											if (!empty($asr['gelar_blk'])) {
																												$namaasesor = $asr['gelar_depan'] . " " . $asr['nama'] . ", " . $asr['gelar_blk'];
																											} else {
																												$namaasesor = $asr['gelar_depan'] . " " . $asr['nama'];
																											}
																										} else {
																											if (!empty($asr['gelar_blk'])) {
																												$namaasesor = $asr['nama'] . ", " . $asr['gelar_blk'];
																											} else {
																												$namaasesor = $asr['nama'];
																											}
																										}
																										$noregasesor = $asr['no_induk'];
																										$namaasesor = $noasr . '. ' . $namaasesor;
																										$noregasesor = $noasr . '. ' . $noregasesor;
																										$noasr++;
																									}
																									echo "<!-- Content Header (Page header) -->
			<section class='content-header'>
				<h1>
				Input FR.IA.07
				<small>Skema $sk[judul]</small>
				</h1>
				<ol class='breadcrumb'>
				<li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
				<li class='active'>FR.IA.07</li>
				</ol>
			</section>";
																									if (isset($_REQUEST['simpan'])) {
																										$sqlcekgetak01 = "SELECT * FROM `asesmen_ia07` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																										$cekgetak01 = $conn->query($sqlcekgetak01);
																										$cekak01 = $cekgetak01->num_rows;
																										$tglsekarang = date("Y-m-d");
																										//if ($cekak01>0){
																										$folderPath = "../foto_tandatangan/";
																										if (empty($_POST['signed'])) {
																											// input jawaban pendukung observasi
																											$sqlgetunitkompetensib2 = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
																											$getunitkompetensib2 = $conn->query($sqlgetunitkompetensib2);
																											while ($unkb2 = $getunitkompetensib2->fetch_assoc()) {
																												$sqlgetpertanyaan2 = "SELECT * FROM `skema_pertanyaanlisan` WHERE `id_unitkompetensi`='$unkb2[id]' ORDER BY `id` ASC";
																												$getpertanyaan2 = $conn->query($sqlgetpertanyaan2);
																												while ($gpp2 = $getpertanyaan2->fetch_assoc()) {
																													//if (!empty($gpp2['pertanyaan'])){
																													$postjawaban = 'jawaban' . $gpp2['id'];
																													$postrekomendasi = 'rekomendasi' . $gpp2['id'];
																													if (empty($_POST[$postjawaban])) {
																														$postjawabanx = "";
																													} else {
																														$postjawabanx = $_POST[$postjawaban];
																													}
																													if (empty($_POST[$postrekomendasi])) {
																														$postrekomendasix = "";
																													} else {
																														$postrekomendasix = $_POST[$postrekomendasi];
																													}
																													$sqlcekjawaban2 = "SELECT * FROM `asesmen_ia07` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='$gpp2[id]'";
																													$cekjawaban2 = $conn->query($sqlcekjawaban2);
																													$jjw2 = $cekjawaban2->fetch_assoc();
																													$jumjjw2 = $cekjawaban2->num_rows;
																													if ($jumjjw2 > 0) {
																														$sqlinputak01 = "UPDATE `asesmen_ia07` SET `rekomendasi`='" . $postjawabanx . "' WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$jd[id_skemakkni]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='$gpp2[id]'";
																														$conn->query($sqlinputak01);
																													} else {
																														$sqlinputak01 = "INSERT INTO `asesmen_ia07`(`id_asesi`, `id_skemakkni`, `id_jadwal`, `id_pertanyaan`,  `jawaban`, `rekomendasi`) VALUES ('$_GET[ida]', '$jd[id_skemakkni]', '$_GET[idj]', '$gpp2[id]', '" . $postjawabanx . "', '" . $postrekomendasix . "')";
																														$conn->query($sqlinputak01);
																													}
																													//}
																												}
																											}
																											//$sqlinputak01b="UPDATE `asesi_asesmen` SET `umpan_balik_ia06`='$_POST[umpan_balik_ia06]' WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																											//$conn->query($sqlinputak01b);
																											echo "<div class='alert alert-success alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Keputusan/ Penilaian atas Jawaban Pertanyaan Lisan oleh Asesi berhasil disimpan</h4>
					Terimakasih, Anda telah melakukan penilaian atas <b>Jawaban Pertanyaan Lisan untuk Skema $sk[judul].</b><br>
					<a class='btn btn-warning form-control' href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a></div>";
																										} else {
																											$image_parts = explode(";base64,", $_POST['signed']);
																											$image_type_aux = explode("image/", $image_parts[0]);
																											$image_type = $image_type_aux[1];
																											$image_base64 = base64_decode($image_parts[1]);
																											$file = $folderPath . uniqid() . '.' . $image_type;
																											file_put_contents($file, $image_base64);
																											$url =  "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
																											$iddokumen = md5($url);
																											$escaped_url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
																											$alamatip = $_SERVER['REMOTE_ADDR'];
																											$sqlinputdigisign = "INSERT INTO `logdigisign`(`id_dokumen`, id_skema, id_asesi, `url_ditandatangani`, `nama_dokumen`, `penandatangan`, `file`, `ip`) VALUES ('$iddokumen','$sk[id]','$_SESSION[namauser]','$escaped_url','FR.IA.07. PERTANYAAN LISAN','$_SESSION[namalengkap]','$file','$alamatip')";
																											$conn->query($sqlinputdigisign);
																											// input jawaban pendukung observasi
																											$sqlgetunitkompetensib2 = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
																											$getunitkompetensib2 = $conn->query($sqlgetunitkompetensib2);
																											while ($unkb2 = $getunitkompetensib2->fetch_assoc()) {
																												$sqlgetpertanyaan2 = "SELECT * FROM `skema_pertanyaanlisan` WHERE `id_unitkompetensi`='$unkb2[id]' ORDER BY `id` ASC";
																												$getpertanyaan2 = $conn->query($sqlgetpertanyaan2);
																												while ($gpp2 = $getpertanyaan2->fetch_assoc()) {
																													//if (!empty($gpp2['pertanyaan'])){
																													$postjawaban = 'jawaban' . $gpp2['id'];
																													$postrekomendasi = 'rekomendasi' . $gpp2['id'];
																													if (empty($_POST[$postjawaban])) {
																														$postjawabanx = "";
																													} else {
																														$postjawabanx = $_POST[$postjawaban];
																													}
																													if (empty($_POST[$postrekomendasi])) {
																														$postrekomendasix = "";
																													} else {
																														$postrekomendasix = $_POST[$postrekomendasi];
																													}
																													$sqlcekjawaban2 = "SELECT * FROM `asesmen_ia07` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='$gpp2[id]'";
																													$cekjawaban2 = $conn->query($sqlcekjawaban2);
																													$jjw2 = $cekjawaban2->fetch_assoc();
																													$jumjjw2 = $cekjawaban2->num_rows;
																													if ($jumjjw2 > 0) {
																														$sqlinputak01 = "UPDATE `asesmen_ia07` SET `rekomendasi`='" . $postjawabanx . "' WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$jd[id_skemakkni]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='$gpp2[id]'";
																														$conn->query($sqlinputak01);
																													} else {
																														$sqlinputak01 = "INSERT INTO `asesmen_ia07`(`id_asesi`, `id_skemakkni`, `id_jadwal`, `id_pertanyaan`,  `jawaban`, `rekomendasi`) VALUES ('$_GET[ida]', '$jd[id_skemakkni]', '$_GET[idj]', '$gpp2[id]', '" . $postjawabanx . "', '" . $postrekomendasix . "')";
																														$conn->query($sqlinputak01);
																													}
																													//}
																												}
																											}
																											//$sqlinputak01b="UPDATE `asesi_asesmen` SET `umpan_balik_ia06`='$_POST[umpan_balik_ia06]' WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																											//$conn->query($sqlinputak01b);
																											echo "<div class='alert alert-success alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Keputusan/ Penilaian atas Jawaban Pertanyaan Lisan oleh Asesi berhasil disimpan</h4>
					Terimakasih, Anda telah melakukan penilaaian atas <b>Jawaban Pertanyaan Lisan untuk Skema $sk[judul], dan tanda tangan telah ditambahkan</b><br>
					<a class='btn btn-warning form-control' href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a></div>";
																										}
																										//}
																									}
																									$tanggalasesmen = tgl_indo($jd['tgl_asesmen']);
																									echo "<!-- Main content -->
		<section class='content'>
			<div class='row'>
			<div class='col-xs-12'>
				<div class='box'>
				<div class='box-body'>
					<!-- form start -->
					<div class='box-body'>
						<h2>FR.IA.07. PERTANYAAN LISAN</h2>
						<form role='form' method='POST' enctype='multipart/form-data'>
						<table id='example9' class='table table-bordered table-striped'>
							<tr><td rowspan='2' width='25%'>Skema Sertifikasi (KKNI/Okupasi/Klaster)</td><td>Judul :</td><td>$sk[judul]</td></tr>
							<tr><td width='25%'>Nomor :</td><td>$sk[kode_skema]</td></tr>
							<tr><td width='25%'>TUK</td><td colspan='2'>$tukjen[jenis_tuk]</td></tr>
							<tr><td width='25%'>Nama Asesor</td><td colspan='2'>$namaasesor</td></tr>
							<tr><td width='25%'>Nama Asesi</td><td colspan='2'>$rowAgen[nama]</td></tr>
							<tr><td width='25%'>Tanggal</td><td colspan='2'>$tanggalasesmen</td></tr>
						</table>
						<table id='example9' class='table table-bordered table-striped'>
							<tr><th>PANDUAN BAGI ASESOR</th></tr>
							<tr><td><ul>
							<li>Pertanyaan lisan merupakan jenis bukti tambahan untuk mendukung bukti-bukti yang sudah ada.</li>
							<li>Buatlah pertanyaan lisan yang dapat mencakupi penguatan informasi berdasarkan KUK, batasan variabel, pengetahuan dan ketrampilan esensial, sikap dan aspek kritis.</li>
							<li>Perkiraan jawaban dapat dibuat pada lembar lain.</li>
							<li>Tanggapan/penilaian dapat diisi dengan centang (v) pada kolom K (kompeten) atau BK (belum kompeten). Dibutuhkan jastifikasi profesional asesor untuk memutuskan hal ini.</li>
							</ul></td></tr>
						</table>
						<table id='example9' class='table table-bordered table-striped'>";
																									$sqlgetunitkompetensi = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
																									$getunitkompetensi = $conn->query($sqlgetunitkompetensi);
																									$jumunitnya0 = $getunitkompetensi->num_rows;
																									$jumunitnya = $jumunitnya0 * 2; //kode dan judul unit adalah 2 baris
																									$noku = 1;
																									//while unitkompetensi ==================================================================
																									while ($unk = $getunitkompetensi->fetch_assoc()) {
																										if ($noku == 1) {
																											echo "<tr><td rowspan='" . $jumunitnya . "' valign='middle'>Unit Kompetensi</td><td>Kode Unit</td><td>" . $unk['kode_unit'] . "</td></tr>";
																											echo "<tr><td>Judul Unit</td><td>" . $unk['judul'] . "</td></tr>";
																										} else {
																											echo "<tr><td>Kode Unit</td><td>" . $unk['kode_unit'] . "</td></tr>";
																											echo "<tr><td>Judul Unit</td><td>" . $unk['judul'] . "</td></tr>";
																										}
																										$noku++;
																									}
																									//end while unitkompetensi =============================================
																									echo "</table>
						<table id='example9' class='table table-bordered table-striped'>";
																									echo "<tr><td><b>Instruksi:</b></td><td>
							<ol>
								<li>Ajukan pertanyaan kepada Asesi dari daftar terlampir untuk mengonfirmasi pengetahuan, sebagaimana diperlukan.</li>
								<li>Tempatkan centang di kotak untuk mencerminkan prestasi Asesi (Kompeten 'K' atau Belum Kompeten 'BK').</li>
								<li>Tulis jawaban Asesi secara singkat di tempat yang disediakan untuk setiap pertanyaan.</li>
							</ol></td></tr>";
																									echo "</table>
						<table id='example9' class='table table-bordered table-striped'>
						<thead><tr><th>Pertanyaan</th><th>Rekomendasi</th></tr></thead>
						<tbody>";
																									// pertanyaan pendukung observasi
																									$sqlgetunitkompetensib = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
																									$getunitkompetensib = $conn->query($sqlgetunitkompetensib);
																									$nopp = 1;
																									while ($unkb = $getunitkompetensib->fetch_assoc()) {
																										$sqlgetpertanyaan = "SELECT * FROM `skema_pertanyaanlisan` WHERE `id_unitkompetensi`='$unkb[id]' ORDER BY `id` ASC";
																										$getpertanyaan = $conn->query($sqlgetpertanyaan);
																										while ($gpp = $getpertanyaan->fetch_assoc()) {
																											if (!empty($gpp['pertanyaan'])) {
																												$sqlcekjawaban = "SELECT * FROM `asesmen_ia07` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='$gpp[id]'";
																												$cekjawaban = $conn->query($sqlcekjawaban);
																												$jjw = $cekjawaban->fetch_assoc();
																												echo "<tr><td><b>Pertanyaan:</b><br/>" . $gpp['pertanyaan'] . "</td><td rowspan='3'>
									<input type='radio' name='rekomendasi$gpp[id]' id='rekomendasi$gpp[id]1' value='K' ";
																												if ($jjw['rekomendasi'] == 'K') {
																													echo " checked";
																												} else {
																													echo "";
																												}
																												echo ">&nbsp;Kompeten<br>
									<input type='radio' name='rekomendasi$gpp[id]' id='rekomendasi$gpp[id]2' value='BK' ";
																												if ($jjw['rekomendasi'] == 'BK') {
																													echo " checked";
																												} else {
																													echo "";
																												}
																												echo ">&nbsp;Belum Kompeten</td></tr>
									<tr><td><b>Kunci Jawaban:</b><br/>
									<p>" . $gpp['kunci_jawaban'] . "</p>
									</td></tr>
									<tr><td><b>Jawaban:</b><br/>
									<textarea class='form-control' name='jawaban$gpp[id]'>";
																												if (!empty($jjw['jawaban'])) {
																													echo $jjw['jawaban'];
																												} else {
																													echo "";
																												}
																												echo "</textarea>
									</td></tr>";
																												$nopp++;
																											}
																										}
																									}
																									echo "</tbody></table>";
																									/* $sqlgetkeputusan="SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
						$getkeputusan=$conn->query($sqlgetkeputusan);
						$getk=$getkeputusan->fetch_assoc();
						echo "<div class='col-md-12'>
						<label><b>Umpan Balik Asesi :</b></label><br />
						<textarea class='form-control' name='umpan_balik_ia06'>";
						if (!empty($getk['umpan_balik_ia06'])){echo $getk['umpan_balik_ia06'];}else{echo "";}
						echo "</textarea>
						</div>"; */
																									// cek tandatangan digital
																									$url = "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
																									$iddokumen = md5($url);
																									$sqlcektandatangan = "SELECT * FROM `logdigisign` WHERE `id_dokumen`='$iddokumen' AND `penandatangan`='$_SESSION[namalengkap]' ORDER BY `id` DESC";
																									$cektandatangan = $conn->query($sqlcektandatangan);
																									$jumttd = $cektandatangan->num_rows;
																									$ttdx = $cektandatangan->fetch_assoc();
																									if ($jumttd > 0) {
																										echo "<div class='col-md-12'>
										<label class='' for=''>Persetujuan/ Tanda Tangan yang telah Anda berikan:</label>
										<br/>
										<img src='../$ttdx[file]' width='400px'/>
										<br/>
									</div>";
																									}
																									echo "<div class='col-md-12'>
							<label class='' for=''>Tanda Tangan:</label>
							<br/>
							<div id='sig' ></div>
							<br/>
							<button id='clear'>Hapus Tanda Tangan</button>
							<textarea id='signature64' name='signed' style='display: none'></textarea>
						</div>
						<script type='text/javascript'>
							var sig = $('#sig').signature({syncField: '#signature64', syncFormat: 'PNG', color: '#58009F'});
							$('#clear').click(function(e) {
								e.preventDefault();
								sig.signature('clear');
								$('#signature64').val('');
							});
						</script></div>";
																									echo "<div class='box-footer bg-black'>
							<div class='col-md-6 col-sm-12 col-xs-12'>
									<a class='btn btn-danger form-control' id=reset-validate-form href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a>
							</div>
							<div class='col-md-6 col-sm-12 col-xs-12'>
									<button type='submit' class='btn btn-success form-control' name='simpan'>Simpan Jawaban</button>
							</div>
						</div>
					</form>			
				</div>
				<!-- /.box-body -->
				</div>
				<!-- /.box -->
			</div>
			<!-- /.col -->
			</div>
			<!-- /.row -->
		</section>
		<!-- /.content -->";
																								}
																								// Bagian Input Hasil Asesmen FORM-FR.IA.08 Asesor
																								elseif ($_GET['module'] == 'form-fr-ia-08') {
																									$sqllogin = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_GET[ida]'";
																									$login = $conn->query($sqllogin);
																									$ketemu = $login->num_rows;
																									$rowAgen = $login->fetch_assoc();
																									$sqlgetjadwal = "SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
																									$getjadwal = $conn->query($sqlgetjadwal);
																									$jd = $getjadwal->fetch_assoc();
																									$sqlgetskema = "SELECT * FROM `skema_kkni` WHERE `id`='$jd[id_skemakkni]'";
																									$getskema = $conn->query($sqlgetskema);
																									$sk = $getskema->fetch_assoc();
																									$sqltuk = "SELECT * FROM `tuk` WHERE `id`='$jd[tempat_asesmen]'";
																									$tuk = $conn->query($sqltuk);
																									$tq = $tuk->fetch_assoc();
																									$sqltukjenis = "SELECT `jenis_tuk` FROM `tuk_jenis` WHERE `id`='$tq[jenis_tuk]'";
																									$tukjenis = $conn->query($sqltukjenis);
																									$tukjen = $tukjenis->fetch_assoc();
																									$noasr = 1;
																									$getasesor = $conn->query("SELECT * FROM `jadwal_asesor` WHERE `id_jadwal`='$_GET[idj]'");
																									while ($gas = $getasesor->fetch_assoc()) {
																										$sqlasesor = "SELECT * FROM `asesor` WHERE `id`='$gas[id_asesor]'";
																										$asesor = $conn->query($sqlasesor);
																										$asr = $asesor->fetch_assoc();
																										if (!empty($asr['gelar_depan'])) {
																											if (!empty($asr['gelar_blk'])) {
																												$namaasesor = $asr['gelar_depan'] . " " . $asr['nama'] . ", " . $asr['gelar_blk'];
																											} else {
																												$namaasesor = $asr['gelar_depan'] . " " . $asr['nama'];
																											}
																										} else {
																											if (!empty($asr['gelar_blk'])) {
																												$namaasesor = $asr['nama'] . ", " . $asr['gelar_blk'];
																											} else {
																												$namaasesor = $asr['nama'];
																											}
																										}
																										$noregasesor = $asr['no_induk'];
																										$namaasesor = $noasr . '. ' . $namaasesor;
																										$noregasesor = $noasr . '. ' . $noregasesor;
																										$noasr++;
																									}
																									echo "<!-- Content Header (Page header) -->
			<section class='content-header'>
				<h1>
				Input FR.IA.08
				<small>Skema $sk[judul]</small>
				</h1>
				<ol class='breadcrumb'>
				<li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
				<li class='active'>FR.IA.08</li>
				</ol>
			</section>";
																									if (isset($_REQUEST['simpan'])) {
																										$sqlcekgetak01 = "SELECT * FROM `asesmen_ia08` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																										$cekgetak01 = $conn->query($sqlcekgetak01);
																										$cekak01 = $cekgetak01->num_rows;
																										$tglsekarang = date("Y-m-d");
																										if ($cekak01 > 0) {
																											$folderPath = "../foto_tandatangan/";
																											if (empty($_POST['signed'])) {
																												// input jawaban ceklist portfolio
																												//$sqlgetunitkompetensib2="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
																												//$getunitkompetensib2=$conn->query($sqlgetunitkompetensib2);
																												//while ($unkb2=$getunitkompetensib2->fetch_assoc()){
																												$sqlgetpertanyaan2 = "SELECT * FROM `asesi_portfolio` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' OR `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$sk[id]' ORDER BY `id` ASC";
																												$getpertanyaan2 = $conn->query($sqlgetpertanyaan2);
																												while ($gpp2 = $getpertanyaan2->fetch_assoc()) {
																													//if (!empty($gpp2['pertanyaan'])){
																													$postjawabanV = 'V' . $gpp2['id'];
																													$postjawabanA = 'A' . $gpp2['id'];
																													$postjawabanT = 'T' . $gpp2['id'];
																													$postjawabanM = 'M' . $gpp2['id'];
																													$postelemen = 'elemen_' . $gpp2['id'];
																													$postpertanyaan = 'pertanyaan_' . $gpp2['id'];
																													if (empty($_POST[$postjawabanV])) {
																														$postjawabanVx = "0";
																													} else {
																														$postjawabanVx = $_POST[$postjawabanV];
																													}
																													if (empty($_POST[$postjawabanA])) {
																														$postjawabanAx = "0";
																													} else {
																														$postjawabanAx = $_POST[$postjawabanA];
																													}
																													if (empty($_POST[$postjawabanT])) {
																														$postjawabanTx = "0";
																													} else {
																														$postjawabanTx = $_POST[$postjawabanT];
																													}

																													if (empty($_POST[$postjawabanM])) {
																														$postjawabanMx = "0";
																													} else {
																														$postjawabanMx = $_POST[$postjawabanM];
																													}

																													if (empty($_POST[$postelemen])) {
																														$postelemenx = "0";
																													} else {
																														$postelemenx = $_POST[$postelemen];
																													}

																													if (empty($_POST[$postpertanyaan])) {
																														$postpertanyaanx = "0";
																													} else {
																														$postpertanyaanx = $_POST[$postpertanyaan];
																													}
																													if (empty($_POST[$postpertanyaan])) {
																														$postpertanyaanx = "";
																													} else {
																														$postpertanyaanx = $_POST[$postpertanyaan];
																													}

																													$sqlcekjawaban2 = "SELECT * FROM `asesmen_ia08` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_portfolio`='$gpp2[id]'";
																													$cekjawaban2 = $conn->query($sqlcekjawaban2);
																													$jjw2 = $cekjawaban2->fetch_assoc();
																													$jumjjw = $cekjawaban2->num_rows;
																													if ($jumjjw > 0) {
																														$sqlinputak01 = "UPDATE `asesmen_ia08` SET `V`='" . $postjawabanVx . "', `A`='" . $postjawabanAx . "', `T`='" . $postjawabanTx . "', `M`='" . $postjawabanMx . "', `id_elemen`='" . $postelemenx . "', `pertanyaan`='" . $postpertanyaanx . "' WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$jd[id_skemakkni]' AND `id_jadwal`='$_GET[idj]' AND `id_portfolio`='$gpp2[id]'";
																														$conn->query($sqlinputak01);
																													} else {
																														$sqlinputak01 = "INSERT INTO `asesmen_ia08`(`id_asesi`, `id_skemakkni`, `id_jadwal`, `id_portfolio`, `V`, `A`, `T`, `M`, `id_elemen`, `pertanyaan`) VALUES ('$_GET[ida]', '$jd[id_skemakkni]', '$_GET[idj]', '$gpp2[id]', '" . $postjawabanVx . "', '" . $postjawabanAx . "', '" . $postjawabanTx . "', '" . $postjawabanMx . "', '" . $postelemenx . "', '" . $postpertanyaanx . "')";
																														$conn->query($sqlinputak01);
																													}
																													//}
																												}
																												//}
																												//$sqlinputak01b="UPDATE `asesi_asesmen` SET `umpan_balik_ia08`='$_POST[umpan_balik_ia08]' WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																												//$conn->query($sqlinputak01b);
																												echo "<div class='alert alert-success alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Keputusan/ Penilaian CEKLIS VERIFIKASI PORTOFOLIO oleh Asesi berhasil disimpan</h4>
					Terimakasih, Anda telah melakukan <b>CEKLIS VERIFIKASI PORTOFOLIO untuk Skema $sk[judul].</b><br>
					<a class='btn btn-warning form-control' href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a></div>";
																											} else {
																												$image_parts = explode(";base64,", $_POST['signed']);
																												$image_type_aux = explode("image/", $image_parts[0]);
																												$image_type = $image_type_aux[1];
																												$image_base64 = base64_decode($image_parts[1]);
																												$file = $folderPath . uniqid() . '.' . $image_type;
																												file_put_contents($file, $image_base64);
																												$url =  "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
																												$iddokumen = md5($url);
																												$escaped_url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
																												$alamatip = $_SERVER['REMOTE_ADDR'];
																												$sqlinputdigisign = "INSERT INTO `logdigisign`(`id_dokumen`, id_skema, id_asesi, `url_ditandatangani`, `nama_dokumen`, `penandatangan`, `file`, `ip`) VALUES ('$iddokumen','$sk[id]','$_SESSION[namauser]','$escaped_url','FR.IA.08. CEKLIS VERIFIKASI PORTOFOLIO','$_SESSION[namalengkap]','$file','$alamatip')";
																												$conn->query($sqlinputdigisign);
																												// input jawaban ceklist portfolio
																												//$sqlgetunitkompetensib2="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
																												//$getunitkompetensib2=$conn->query($sqlgetunitkompetensib2);
																												//while ($unkb2=$getunitkompetensib2->fetch_assoc()){
																												$sqlgetpertanyaan2 = "SELECT * FROM `asesi_portfolio` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' OR `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$sk[id]' ORDER BY `id` ASC";
																												$getpertanyaan2 = $conn->query($sqlgetpertanyaan2);
																												while ($gpp2 = $getpertanyaan2->fetch_assoc()) {
																													//if (!empty($gpp2['pertanyaan'])){
																													$postjawabanV = 'V' . $gpp2['id'];
																													$postjawabanA = 'A' . $gpp2['id'];
																													$postjawabanT = 'T' . $gpp2['id'];
																													$postjawabanM = 'M' . $gpp2['id'];
																													$postelemen = 'elemen_' . $gpp2['id'];
																													$postpertanyaan = 'pertanyaan_' . $gpp2['id'];
																													if (empty($_POST[$postjawabanV])) {
																														$postjawabanVx = "0";
																													} else {
																														$postjawabanVx = $_POST[$postjawabanV];
																													}
																													if (empty($_POST[$postjawabanA])) {
																														$postjawabanAx = "0";
																													} else {
																														$postjawabanAx = $_POST[$postjawabanA];
																													}
																													if (empty($_POST[$postjawabanT])) {
																														$postjawabanTx = "0";
																													} else {
																														$postjawabanTx = $_POST[$postjawabanT];
																													}

																													if (empty($_POST[$postjawabanM])) {
																														$postjawabanMx = "0";
																													} else {
																														$postjawabanMx = $_POST[$postjawabanM];
																													}

																													if (empty($_POST[$postelemen])) {
																														$postelemenx = "0";
																													} else {
																														$postelemenx = $_POST[$postelemen];
																													}

																													if (empty($_POST[$postpertanyaan])) {
																														$postpertanyaanx = "0";
																													} else {
																														$postpertanyaanx = $_POST[$postpertanyaan];
																													}
																													if (empty($_POST[$postpertanyaan])) {
																														$postpertanyaanx = "";
																													} else {
																														$postpertanyaanx = $_POST[$postpertanyaan];
																													}

																													$sqlcekjawaban2 = "SELECT * FROM `asesmen_ia08` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_portfolio`='$gpp2[id]'";
																													$cekjawaban2 = $conn->query($sqlcekjawaban2);
																													$jjw2 = $cekjawaban2->fetch_assoc();
																													$jumjjw = $cekjawaban2->num_rows;
																													if ($jumjjw > 0) {
																														$sqlinputak01 = "UPDATE `asesmen_ia08` SET `V`='" . $postjawabanVx . "', `A`='" . $postjawabanAx . "', `T`='" . $postjawabanTx . "', `M`='" . $postjawabanMx . "', `id_elemen`='" . $postelemenx . "', `pertanyaan`='" . $postpertanyaanx . "' WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$jd[id_skemakkni]' AND `id_jadwal`='$_GET[idj]' AND `id_portfolio`='$gpp2[id]'";
																														$conn->query($sqlinputak01);
																													} else {
																														$sqlinputak01 = "INSERT INTO `asesmen_ia08`(`id_asesi`, `id_skemakkni`, `id_jadwal`, `id_portfolio`, `V`, `A`, `T`, `M`, `id_elemen`, `pertanyaan`) VALUES ('$_GET[ida]', '$jd[id_skemakkni]', '$_GET[idj]', '$gpp2[id]', '" . $postjawabanVx . "', '" . $postjawabanAx . "', '" . $postjawabanTx . "', '" . $postjawabanMx . "', '" . $postelemenx . "', '" . $postpertanyaanx . "')";
																														$conn->query($sqlinputak01);
																													}
																													//}
																												}
																												//}
																												//$sqlinputak01b="UPDATE `asesi_asesmen` SET `umpan_balik_ia08`='$_POST[umpan_balik_ia08]' WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																												//$conn->query($sqlinputak01b);
																												echo "<div class='alert alert-success alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Keputusan/ Penilaian CEKLIS VERIFIKASI PORTOFOLIO oleh Asesi berhasil disimpan dan ditandatangani</h4>
					Terimakasih, Anda telah melakukan <b>CEKLIS VERIFIKASI PORTOFOLIO untuk Skema $sk[judul], dan tanda tangan telah ditambahkan</b><br>
					<a class='btn btn-warning form-control' href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a></div>";
																											}
																										} else {
																											$folderPath = "../foto_tandatangan/";
																											if (empty($_POST['signed'])) {
																												// input jawaban ceklist portfolio
																												//$sqlgetunitkompetensib2="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
																												//$getunitkompetensib2=$conn->query($sqlgetunitkompetensib2);
																												//while ($unkb2=$getunitkompetensib2->fetch_assoc()){
																												$sqlgetpertanyaan2 = "SELECT * FROM `asesi_portfolio` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' OR `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$sk[id]' ORDER BY `id` ASC";
																												$getpertanyaan2 = $conn->query($sqlgetpertanyaan2);
																												while ($gpp2 = $getpertanyaan2->fetch_assoc()) {
																													//if (!empty($gpp2['pertanyaan'])){
																													$postjawabanV = 'V' . $gpp2['id'];
																													$postjawabanA = 'A' . $gpp2['id'];
																													$postjawabanT = 'T' . $gpp2['id'];
																													$postjawabanM = 'M' . $gpp2['id'];
																													$postelemen = 'elemen_' . $gpp2['id'];
																													$postpertanyaan = 'pertanyaan_' . $gpp2['id'];
																													if (empty($_POST[$postjawabanV])) {
																														$postjawabanVx = "0";
																													} else {
																														$postjawabanVx = $_POST[$postjawabanV];
																													}
																													if (empty($_POST[$postjawabanA])) {
																														$postjawabanAx = "0";
																													} else {
																														$postjawabanAx = $_POST[$postjawabanA];
																													}
																													if (empty($_POST[$postjawabanT])) {
																														$postjawabanTx = "0";
																													} else {
																														$postjawabanTx = $_POST[$postjawabanT];
																													}

																													if (empty($_POST[$postjawabanM])) {
																														$postjawabanMx = "0";
																													} else {
																														$postjawabanMx = $_POST[$postjawabanM];
																													}

																													if (empty($_POST[$postelemen])) {
																														$postelemenx = "0";
																													} else {
																														$postelemenx = $_POST[$postelemen];
																													}

																													if (empty($_POST[$postpertanyaan])) {
																														$postpertanyaanx = "0";
																													} else {
																														$postpertanyaanx = $_POST[$postpertanyaan];
																													}
																													if (empty($_POST[$postpertanyaan])) {
																														$postpertanyaanx = "";
																													} else {
																														$postpertanyaanx = $_POST[$postpertanyaan];
																													}

																													$sqlcekjawaban2 = "SELECT * FROM `asesmen_ia08` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_portfolio`='$gpp2[id]'";
																													$cekjawaban2 = $conn->query($sqlcekjawaban2);
																													$jjw2 = $cekjawaban2->fetch_assoc();
																													$jumjjw = $cekjawaban2->num_rows;
																													if ($jumjjw > 0) {
																														$sqlinputak01 = "UPDATE `asesmen_ia08` SET `V`='" . $postjawabanVx . "', `A`='" . $postjawabanAx . "', `T`='" . $postjawabanTx . "', `M`='" . $postjawabanMx . "', `id_elemen`='" . $postelemenx . "', `pertanyaan`='" . $postpertanyaanx . "' WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$jd[id_skemakkni]' AND `id_jadwal`='$_GET[idj]' AND `id_portfolio`='$gpp2[id]'";
																														$conn->query($sqlinputak01);
																													} else {
																														$sqlinputak01 = "INSERT INTO `asesmen_ia08`(`id_asesi`, `id_skemakkni`, `id_jadwal`, `id_portfolio`, `V`, `A`, `T`, `M`, `id_elemen`, `pertanyaan`) VALUES ('$_GET[ida]', '$jd[id_skemakkni]', '$_GET[idj]', '$gpp2[id]', '" . $postjawabanVx . "', '" . $postjawabanAx . "', '" . $postjawabanTx . "', '" . $postjawabanMx . "', '" . $postelemenx . "', '" . $postpertanyaanx . "')";
																														$conn->query($sqlinputak01);
																													}
																													//}
																												}
																												//}
																												//$sqlinputak01b="UPDATE `asesi_asesmen` SET `umpan_balik_ia08`='$_POST[umpan_balik_ia08]' WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																												//$conn->query($sqlinputak01b);
																												echo "<div class='alert alert-success alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Keputusan/ Penilaian CEKLIS VERIFIKASI PORTOFOLIO oleh Asesi berhasil disimpan</h4>
					Terimakasih, Anda telah melakukan <b>CEKLIS VERIFIKASI PORTOFOLIO untuk Skema $sk[judul].</b><br>
					<a class='btn btn-warning form-control' href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a></div>";
																											} else {
																												$image_parts = explode(";base64,", $_POST['signed']);
																												$image_type_aux = explode("image/", $image_parts[0]);
																												$image_type = $image_type_aux[1];
																												$image_base64 = base64_decode($image_parts[1]);
																												$file = $folderPath . uniqid() . '.' . $image_type;
																												file_put_contents($file, $image_base64);
																												$url =  "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
																												$iddokumen = md5($url);
																												$escaped_url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
																												$alamatip = $_SERVER['REMOTE_ADDR'];
																												$sqlinputdigisign = "INSERT INTO `logdigisign`(`id_dokumen`, id_skema, id_asesi, `url_ditandatangani`, `nama_dokumen`, `penandatangan`, `file`, `ip`) VALUES ('$iddokumen','$sk[id]','$_SESSION[namauser]','$escaped_url','FR.IA.08. CEKLIS VERIFIKASI PORTOFOLIO','$_SESSION[namalengkap]','$file','$alamatip')";
																												$conn->query($sqlinputdigisign);
																												// input jawaban ceklist portfolio
																												//$sqlgetunitkompetensib2="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
																												//$getunitkompetensib2=$conn->query($sqlgetunitkompetensib2);
																												//while ($unkb2=$getunitkompetensib2->fetch_assoc()){
																												$sqlgetpertanyaan2 = "SELECT * FROM `asesi_portfolio` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' OR `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$sk[id]' ORDER BY `id` ASC";
																												$getpertanyaan2 = $conn->query($sqlgetpertanyaan2);
																												while ($gpp2 = $getpertanyaan2->fetch_assoc()) {
																													//if (!empty($gpp2['pertanyaan'])){
																													$postjawabanV = 'V' . $gpp2['id'];
																													$postjawabanA = 'A' . $gpp2['id'];
																													$postjawabanT = 'T' . $gpp2['id'];
																													$postjawabanM = 'M' . $gpp2['id'];
																													$postelemen = 'elemen_' . $gpp2['id'];
																													$postpertanyaan = 'pertanyaan_' . $gpp2['id'];
																													if (empty($_POST[$postjawabanV])) {
																														$postjawabanVx = "0";
																													} else {
																														$postjawabanVx = $_POST[$postjawabanV];
																													}
																													if (empty($_POST[$postjawabanA])) {
																														$postjawabanAx = "0";
																													} else {
																														$postjawabanAx = $_POST[$postjawabanA];
																													}
																													if (empty($_POST[$postjawabanT])) {
																														$postjawabanTx = "0";
																													} else {
																														$postjawabanTx = $_POST[$postjawabanT];
																													}

																													if (empty($_POST[$postjawabanM])) {
																														$postjawabanMx = "0";
																													} else {
																														$postjawabanMx = $_POST[$postjawabanM];
																													}

																													if (empty($_POST[$postelemen])) {
																														$postelemenx = "0";
																													} else {
																														$postelemenx = $_POST[$postelemen];
																													}

																													if (empty($_POST[$postpertanyaan])) {
																														$postpertanyaanx = "0";
																													} else {
																														$postpertanyaanx = $_POST[$postpertanyaan];
																													}
																													if (empty($_POST[$postpertanyaan])) {
																														$postpertanyaanx = "";
																													} else {
																														$postpertanyaanx = $_POST[$postpertanyaan];
																													}

																													$sqlcekjawaban2 = "SELECT * FROM `asesmen_ia08` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_portfolio`='$gpp2[id]'";
																													$cekjawaban2 = $conn->query($sqlcekjawaban2);
																													$jjw2 = $cekjawaban2->fetch_assoc();
																													$jumjjw = $cekjawaban2->num_rows;
																													if ($jumjjw > 0) {
																														$sqlinputak01 = "UPDATE `asesmen_ia08` SET `V`='" . $postjawabanVx . "', `A`='" . $postjawabanAx . "', `T`='" . $postjawabanTx . "', `M`='" . $postjawabanMx . "', `id_elemen`='" . $postelemenx . "', `pertanyaan`='" . $postpertanyaanx . "' WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$jd[id_skemakkni]' AND `id_jadwal`='$_GET[idj]' AND `id_portfolio`='$gpp2[id]'";
																														$conn->query($sqlinputak01);
																													} else {
																														$sqlinputak01 = "INSERT INTO `asesmen_ia08`(`id_asesi`, `id_skemakkni`, `id_jadwal`, `id_portfolio`, `V`, `A`, `T`, `M`, `id_elemen`, `pertanyaan`) VALUES ('$_GET[ida]', '$jd[id_skemakkni]', '$_GET[idj]', '$gpp2[id]', '" . $postjawabanVx . "', '" . $postjawabanAx . "', '" . $postjawabanTx . "', '" . $postjawabanMx . "', '" . $postelemenx . "', '" . $postpertanyaanx . "')";
																														$conn->query($sqlinputak01);
																													}
																													//}
																												}
																												//}
																												//$sqlinputak01b="UPDATE `asesi_asesmen` SET `umpan_balik_ia08`='$_POST[umpan_balik_ia08]' WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																												//$conn->query($sqlinputak01b);
																												echo "<div class='alert alert-success alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Keputusan/ Penilaian CEKLIS VERIFIKASI PORTOFOLIO oleh Asesi berhasil disimpan dan ditandatangani</h4>
					Terimakasih, Anda telah melakukan <b>CEKLIS VERIFIKASI PORTOFOLIO untuk Skema $sk[judul], dan tanda tangan telah ditambahkan</b><br>
					<a class='btn btn-warning form-control' href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a></div>";
																											}
																										}
																										// input data ia08 asesor
																										$sqlcekgetak01a = "SELECT * FROM `asesmen_ia08asesor` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_asesor`='$_SESSION[namauser]'";
																										$cekgetak01a = $conn->query($sqlcekgetak01a);
																										$cekak01a = $cekgetak01a->num_rows;
																										if ($cekak01a > 0) {
																											$sqlinputia08asesor = "UPDATE `asesmen_ia08asesor` SET `id_asesi`='$_GET[ida]',`id_jadwal`='$_GET[idj]',`id_asesor`='$_SESSION[namauser]',`buktitambahan`='$_POST[buktitambahan]',`contohbukti`='$_POST[contohbukti]',`rekomendasi`='$_POST[rekomendasi]',`unitbkrekom`='$_POST[unitbkrekom]',`elemenbkrekom`='$_POST[elemenbkrekom]',`kukbkrekom`='$_POST[kukbkrekom]' WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_asesor`='$_SESSION[namauser]'";
																											$conn->query($sqlinputia08asesor);
																										} else {
																											$sqlinputia08asesor = "INSERT INTO `asesmen_ia08asesor`(`id_asesi`, `id_jadwal`, `id_asesor`, `buktitambahan`, `contohbukti`, `rekomendasi`, `unitbkrekom`, `elemenbkrekom`, `kukbkrekom`) VALUES ('$_GET[ida]','$_GET[idj]','$_SESSION[namauser]','$_POST[buktitambahan]','$_POST[contohbukti]','$_POST[rekomendasi]','$_POST[unitbkrekom]','$_POST[elemenbkrekom]','$_POST[kukbkrekom]')";
																											$conn->query($sqlinputia08asesor);
																										}
																									}
																									$tanggalasesmen = tgl_indo($jd['tgl_asesmen']);
																									echo "<!-- Main content -->
		<section class='content'>
			<div class='row'>
			<div class='col-xs-12'>
				<div class='box'>
				<div class='box-body'>
					<!-- form start -->
					<div class='box-body'>
						<h2>FR.IA.08. CEKLIS VERIFIKASI PORTOFOLIO</h2>
						<form role='form' method='POST' enctype='multipart/form-data'>
						<table id='example9' class='table table-bordered table-striped'>
							<tr><td rowspan='2' width='25%'>Skema Sertifikasi (KKNI/Okupasi/Klaster)</td><td>Judul :</td><td>$sk[judul]</td></tr>
							<tr><td width='25%'>Nomor :</td><td>$sk[kode_skema]</td></tr>
							<tr><td width='25%'>TUK</td><td colspan='2'>$tukjen[jenis_tuk]</td></tr>
							<tr><td width='25%'>Nama Asesor</td><td colspan='2'>$namaasesor</td></tr>
							<tr><td width='25%'>Nama Asesi</td><td colspan='2'>$rowAgen[nama]</td></tr>
							<tr><td width='25%'>Tanggal</td><td colspan='2'>$tanggalasesmen</td></tr>
						</table>
						<table id='example9' class='table table-bordered table-striped'>";
																									echo "<tr><td align='left'><b>PANDUAN BAGI ASESOR</b></td></tr>
						<tr><td align='left'><ul>
						<li>Isilah tabel ini sesuai dengan informasi sesuai pertanyaan/pernyataan dalam tabel dibawah ini.</li>
						<li>Beri tanda centang (&#10003;) pada hasil penilaian portfolio berdasarkan aturan bukti.</li>
						</ul></td></tr>";
																									echo "</table>
						<table id='example9' class='table table-bordered table-striped'>";
																									$sqlgetunitkompetensi = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
																									$getunitkompetensi = $conn->query($sqlgetunitkompetensi);
																									$jumunitnya0 = $getunitkompetensi->num_rows;
																									$jumunitnya = $jumunitnya0 * 2; //kode dan judul unit adalah 2 baris
																									$noku = 1;
																									//while unitkompetensi ==================================================================
																									while ($unk = $getunitkompetensi->fetch_assoc()) {
																										if ($noku == 1) {
																											echo "<tr><td rowspan='" . $jumunitnya . "' valign='middle'>Unit Kompetensi</td><td>Kode Unit</td><td>" . $unk['kode_unit'] . "</td></tr>";
																											echo "<tr><td>Judul Unit</td><td>" . $unk['judul'] . "</td></tr>";
																										} else {
																											echo "<tr><td>Kode Unit</td><td>" . $unk['kode_unit'] . "</td></tr>";
																											echo "<tr><td>Judul Unit</td><td>" . $unk['judul'] . "</td></tr>";
																										}
																										$noku++;
																									}
																									//end while unitkompetensi =============================================
																									echo "</table>
						<table id='example9' class='table table-bordered table-striped'>
						<thead>
						<tr><th rowspan='3' colspan='2' align='center' valign='middle'>Dokumen Portfolio</th><th colspan='8'>Aturan Bukti</th></tr>
						<tr><th colspan='2' align='center' valign='middle'>Valid</th><th colspan='2' align='center' valign='middle'>Asli</th><th colspan='2' align='center' valign='middle'>Terkini</th><th colspan='2' align='center' valign='middle'>Memadai</th></tr>
						<tr><th align='center' valign='middle'>Ya</th><th align='center' valign='middle'>Tidak</th><th align='center' valign='middle'>Ya</th><th align='center' valign='middle'>Tidak</th><th align='center' valign='middle'>Ya</th><th align='center' valign='middle'>Tidak</th><th align='center' valign='middle'>Ya</th><th align='center' valign='middle'>Tidak</th></tr>
						</thead>
						<tbody>";
																									// dokumen portfolio
																									$sqlgetunitkompetensib = "SELECT * FROM `asesi_portfolio` WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$sk[id]' ORDER BY `id` ASC";
																									$getunitkompetensib = $conn->query($sqlgetunitkompetensib);
																									$nopp = 1;
																									while ($unkb = $getunitkompetensib->fetch_assoc()) {
																										$sqlcekjawaban = "SELECT * FROM `asesmen_ia08` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_portfolio`='$unkb[id]'";
																										$cekjawaban = $conn->query($sqlcekjawaban);
																										$jjw = $cekjawaban->fetch_assoc();
																										echo "<tr><td>$nopp</td><td>" . $unkb['jenis_portfolio'] . "<br>" . $unkb['nama_doc'] . "<br>" . $unkb['peran_portfolio'] . "<br>Tanggal Dokumen: " . tgl_indo($unkb['tgl_doc']) . "<br><a href='";
																										if (substr($unkb['file'], 0, 4) == 'http') {
																											echo $unkb['file'];
																										} else {
																											echo "../foto_portfolio/" . $unkb['file'];
																										}
																										echo "' target='_blank' class='btn btn-primary btn-xs'>Lihat Dokumen</a></td>
							<td><input type='radio' name='V" . $unkb['id'] . "' id='V" . $unkb['id'] . "_1' value='1' ";
																										if ($jjw['V'] == '1') {
																											echo " checked";
																										} else {
																											echo "";
																										}
																										echo ">&nbsp;</td>
							<td><input type='radio' name='V" . $unkb['id'] . "' id='V" . $unkb['id'] . "_2' value='0' ";
																										if ($jjw['V'] == '0') {
																											echo " checked";
																										} else {
																											echo "";
																										}
																										echo ">&nbsp;</td>
							<td><input type='radio' name='A" . $unkb['id'] . "' id='A" . $unkb['id'] . "_1' value='1' ";
																										if ($jjw['A'] == '1') {
																											echo " checked";
																										} else {
																											echo "";
																										}
																										echo ">&nbsp;</td>
							<td><input type='radio' name='A" . $unkb['id'] . "' id='A" . $unkb['id'] . "_2' value='0' ";
																										if ($jjw['A'] == '0') {
																											echo " checked";
																										} else {
																											echo "";
																										}
																										echo ">&nbsp;</td>
							<td><input type='radio' name='T" . $unkb['id'] . "' id='T" . $unkb['id'] . "_1' value='1' ";
																										if ($jjw['T'] == '1') {
																											echo " checked";
																										} else {
																											echo "";
																										}
																										echo ">&nbsp;</td>
							<td><input type='radio' name='T" . $unkb['id'] . "' id='T" . $unkb['id'] . "_2' value='0' ";
																										if ($jjw['T'] == '0') {
																											echo " checked";
																										} else {
																											echo "";
																										}
																										echo ">&nbsp;</td>
							<td><input type='radio' name='M" . $unkb['id'] . "' id='M" . $unkb['id'] . "_1' value='1' ";
																										if ($jjw['M'] == '1') {
																											echo " checked";
																										} else {
																											echo "";
																										}
																										echo ">&nbsp;</td>
							<td><input type='radio' name='M" . $unkb['id'] . "' id='M" . $unkb['id'] . "_2' value='0' ";
																										if ($jjw['M'] == '0') {
																											echo " checked";
																										} else {
																											echo "";
																										}
																										echo ">&nbsp;</td>
							</tr>";
																										$nopp++;
																									}
																									echo "</tbody></table>";
																									echo "<table id='example9' class='table table-bordered table-striped'>
						<thead>
						<tr><th colspan='3' align='left' valign='middle'>Sebagai tindak lanjut dari hasil verifikasi bukti, substansi materi di bawah ini (no elemen yg di cek list) harus diklarifikasi selama wawancara:</th></tr>
						<tr><th>Cek List</th><th>No. Elemen</th><th>Materi/substansi Wawancara (KUK)</th></tr></thead>
						<tbody>";
																									$sqlgetunitkompetensib2 = "SELECT * FROM `asesi_portfolio` WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$sk[id]' ORDER BY `id` ASC";
																									$getunitkompetensib2 = $conn->query($sqlgetunitkompetensib2);
																									$nopp2 = 1;
																									while ($unkb2 = $getunitkompetensib2->fetch_assoc()) {
																										echo "<tr><td>$nopp2</td><td><select name='elemen_" . $unkb2['id'] . "' id='elemen_" . $unkb2['id'] . "' class='form-control'>";
																										$sqlgetpertanyaanwawancara = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
																										$getpertanyaanwawancara = $conn->query($sqlgetpertanyaanwawancara);
																										$nount = 1;
																										$sqlcekjawabanx = "SELECT * FROM `asesmen_ia08` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_portfolio`='$unkb2[id]'";
																										$cekjawabanx = $conn->query($sqlcekjawabanx);
																										$jjwx = $cekjawabanx->fetch_assoc();
																										echo "<option>-- pilih unit/elemen --</option>";
																										while ($getel = $getpertanyaanwawancara->fetch_assoc()) {
																											echo "<option value='$getel[id]'";
																											if ($jjwx['id_elemen'] == $getel['id']) {
																												echo " selected";
																											} else {
																												echo "";
																											}
																											echo "><b>" . $nount . ". $getel[kode_unit] - $getel[judul]</b></option>";
																											$sqlgetpertanyaanwawancara2 = "SELECT * FROM `elemen_kompetensi` WHERE `id_unitkompetensi`='$getel[id]'";
																											$getpertanyaanwawancara2 = $conn->query($sqlgetpertanyaanwawancara2);
																											$nountel = 1;
																											while ($getel2 = $getpertanyaanwawancara2->fetch_assoc()) {
																												echo "<option value='e-" . $getel2['id'] . "'";
																												if ($jjwx['id_elemen'] == "e-" . $getel2['id']) {
																													echo " selected";
																												} else {
																													echo "";
																												}
																												echo ">--" . $nount . "." . $nountel . ". $getel2[elemen_kompetensi]</option>";
																												$nountel++;
																											}
																											$nount++;
																										}
																										echo "</select></td><td><textarea name='pertanyaan_" . $unkb2['id'] . "' id='pertanyaan_" . $unkb2['id'] . "' class='form-control'>";
																										if (!empty($jjwx['pertanyaan'])) {
																											echo $jjwx['pertanyaan'];
																										} else {
																											echo "";
																										}
																										echo "</textarea></td></tr>";
																										$nopp2++;
																									}
																									echo "</tbody></table>";
																									$sqlcekjawabanr = "SELECT * FROM `asesmen_ia08asesor` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_asesor`='$_SESSION[namauser]'";
																									$cekjawabanr = $conn->query($sqlcekjawabanr);
																									$jjwr = $cekjawabanr->fetch_assoc();
																									echo "<table id='example9' class='table table-bordered table-striped'>
						<tr><td colspan='2'>Bukti tambahan diperlukan pada unit / elemen kompetensi sebagai berikut:<br>
						<textarea name='buktitambahan' class='form-control'>";
																									if (!empty($jjwr['buktitambahan'])) {
																										echo $jjwr['buktitambahan'];
																									}
																									echo "</textarea><br>
						Contoh :<br>
						<textarea name='contohbukti' class='form-control'>";
																									if (!empty($jjwr['contohbukti'])) {
																										echo $jjwr['contohbukti'];
																									}
																									echo "</textarea></td>
						</tr>
						<tr><td rowspan='2'>Rekomendasi Asesor</td><td><input type='radio' name='rekomendasi' id='rekomendasi1' value='K' ";
																									if ($jjwr['rekomendasi'] == 'K') {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;Asesi telah memenuhi pencapaian seluruh kriteria unjuk kerja, direkomendasikan <b>KOMPETEN</b></td></td></tr>
						<tr><td><input type='radio' name='rekomendasi' id='rekomendasi2' value='BK' ";
																									if ($jjwr['rekomendasi'] == 'BK') {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;Asesi belum memenuhi pencapaian seluruh kriteria unjuk kerja, direkomendasikan uji demonstrasi pada:<br>
						<label>Unit</label><input type='text' name='unitbkrekom' class='form-control'";
																									if (!empty($jjwr['unitbkrekom'])) {
																										echo " value='" . $jjwr['unitbkrekom'] . "'";
																									}
																									echo "/><br>
						<label>Elemen</label><input type='text' name='elemenbkrekom' class='form-control'";
																									if (!empty($jjwr['elemenbkrekom'])) {
																										echo " value='" . $jjwr['elemenbkrekom'] . "'";
																									}
																									echo "/><br>
						<label>KUK</label><input type='text' name='kukbkrekom' class='form-control'";
																									if (!empty($jjwr['kukbkrekom'])) {
																										echo " value='" . $jjwr['kukbkrekom'] . "'";
																									}
																									echo "/><br></td></tr>
						</table>";
																									// cek tandatangan digital
																									$url = "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
																									$iddokumen = md5($url);
																									$sqlcektandatangan = "SELECT * FROM `logdigisign` WHERE `id_dokumen`='$iddokumen' AND `penandatangan`='$_SESSION[namalengkap]' ORDER BY `id` DESC";
																									$cektandatangan = $conn->query($sqlcektandatangan);
																									$jumttd = $cektandatangan->num_rows;
																									$ttdx = $cektandatangan->fetch_assoc();
																									if ($jumttd > 0) {
																										echo "<div class='col-md-12'>
										<label class='' for=''>Persetujuan/ Tanda Tangan yang telah Anda berikan:</label>
										<br/>
										<img src='../$ttdx[file]' width='400px'/>
										<br/>
									</div>";
																									}
																									echo "<div class='col-md-12'>
							<label class='' for=''>Tanda Tangan:</label>
							<br/>
							<div id='sig' ></div>
							<br/>
							<button id='clear'>Hapus Tanda Tangan</button>
							<textarea id='signature64' name='signed' style='display: none'></textarea>
						</div>
						<script type='text/javascript'>
							var sig = $('#sig').signature({syncField: '#signature64', syncFormat: 'PNG', color: '#58009F'});
							$('#clear').click(function(e) {
								e.preventDefault();
								sig.signature('clear');
								$('#signature64').val('');
							});
						</script></div>";
																									echo "<div class='box-footer bg-black'>
							<div class='col-md-6 col-sm-12 col-xs-12'>
									<a class='btn btn-danger form-control' id=reset-validate-form href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a>
							</div>
							<div class='col-md-6 col-sm-12 col-xs-12'>
									<button type='submit' class='btn btn-success form-control' name='simpan'>Simpan Jawaban</button>
							</div>
						</div>
					</form>			
				</div>
				<!-- /.box-body -->
				</div>
				<!-- /.box -->
			</div>
			<!-- /.col -->
			</div>
			<!-- /.row -->
		</section>
		<!-- /.content -->";
																								}
																								// Bagian Input Hasil Asesmen FORM-FR.IA.09 Asesor
																								elseif ($_GET['module'] == 'form-fr-ia-09') {
																									$sqllogin = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_GET[ida]'";
																									$login = $conn->query($sqllogin);
																									$ketemu = $login->num_rows;
																									$rowAgen = $login->fetch_assoc();
																									$sqlgetjadwal = "SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
																									$getjadwal = $conn->query($sqlgetjadwal);
																									$jd = $getjadwal->fetch_assoc();
																									$sqlgetskema = "SELECT * FROM `skema_kkni` WHERE `id`='$jd[id_skemakkni]'";
																									$getskema = $conn->query($sqlgetskema);
																									$sk = $getskema->fetch_assoc();
																									$sqltuk = "SELECT * FROM `tuk` WHERE `id`='$jd[tempat_asesmen]'";
																									$tuk = $conn->query($sqltuk);
																									$tq = $tuk->fetch_assoc();
																									$sqltukjenis = "SELECT `jenis_tuk` FROM `tuk_jenis` WHERE `id`='$tq[jenis_tuk]'";
																									$tukjenis = $conn->query($sqltukjenis);
																									$tukjen = $tukjenis->fetch_assoc();
																									$noasr = 1;
																									$getasesor = $conn->query("SELECT * FROM `jadwal_asesor` WHERE `id_jadwal`='$_GET[idj]'");
																									while ($gas = $getasesor->fetch_assoc()) {
																										$sqlasesor = "SELECT * FROM `asesor` WHERE `id`='$gas[id_asesor]'";
																										$asesor = $conn->query($sqlasesor);
																										$asr = $asesor->fetch_assoc();
																										if (!empty($asr['gelar_depan'])) {
																											if (!empty($asr['gelar_blk'])) {
																												$namaasesor = $asr['gelar_depan'] . " " . $asr['nama'] . ", " . $asr['gelar_blk'];
																											} else {
																												$namaasesor = $asr['gelar_depan'] . " " . $asr['nama'];
																											}
																										} else {
																											if (!empty($asr['gelar_blk'])) {
																												$namaasesor = $asr['nama'] . ", " . $asr['gelar_blk'];
																											} else {
																												$namaasesor = $asr['nama'];
																											}
																										}
																										$noregasesor = $asr['no_induk'];
																										$namaasesor = $noasr . '. ' . $namaasesor;
																										$noregasesor = $noasr . '. ' . $noregasesor;
																										$noasr++;
																									}
																									echo "<!-- Content Header (Page header) -->
			<section class='content-header'>
				<h1>
				Input FR.IA.09
				<small>Skema $sk[judul]</small>
				</h1>
				<ol class='breadcrumb'>
				<li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
				<li class='active'>FR.IA.09</li>
				</ol>
			</section>";
																									if (isset($_REQUEST['simpan'])) {
																										$sqlcekgetak01 = "SELECT * FROM `asesmen_ia09` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																										$cekgetak01 = $conn->query($sqlcekgetak01);
																										$cekak01 = $cekgetak01->num_rows;
																										$tglsekarang = date("Y-m-d");
																										if ($cekak01 > 0) {
																											$folderPath = "../foto_tandatangan/";
																											if (empty($_POST['signed'])) {
																												// input jawaban ceklist portfolio
																												//$sqlgetunitkompetensib2="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
																												//$getunitkompetensib2=$conn->query($sqlgetunitkompetensib2);
																												//while ($unkb2=$getunitkompetensib2->fetch_assoc()){
																												$sqlgetpertanyaan2 = "SELECT * FROM `asesi_portfolio` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' OR `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$sk[id]' ORDER BY `id` ASC";
																												$getpertanyaan2 = $conn->query($sqlgetpertanyaan2);
																												while ($gpp2 = $getpertanyaan2->fetch_assoc()) {
																													//if (!empty($gpp2['pertanyaan'])){
																													$postjawaban = 'jawaban_' . $gpp2['id'];
																													$postrekomendasibukti = 'rekomendasibukti_' . $gpp2['id'];
																													$sqlcekjawaban2 = "SELECT * FROM `asesmen_ia09` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_portfolio`='$gpp2[id]'";
																													$cekjawaban2 = $conn->query($sqlcekjawaban2);
																													$jjw2 = $cekjawaban2->fetch_assoc();
																													if (isset($_POST[$postjawaban])) {
																														$POSTpostjawaban = $_POST[$postjawaban];
																													} else {
																														$POSTpostjawaban = "";
																													}
																													if (isset($_POST[$postrekomendasibukti])) {
																														$POSTpostrekomendasibukti = $_POST[$postrekomendasibukti];
																													} else {
																														$POSTpostrekomendasibukti = "";
																													}
																													$sqlinputak01 = "UPDATE `asesmen_ia09` SET `jawaban`='" . $POSTpostjawaban . "', `rekomendasi`='" . $POSTpostrekomendasibukti . "' WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_asesor`='$_SESSION[namauser]' AND `id_portfolio`='$gpp2[id]'";
																													$conn->query($sqlinputak01);
																													//}
																												}
																												//}
																												//$sqlinputak01b="UPDATE `asesi_asesmen` SET `umpan_balik_ia09`='$_POST[umpan_balik_ia09]' WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																												//$conn->query($sqlinputak01b);
																												echo "<div class='alert alert-success alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Keputusan/ Penilaian PERTANYAAN WAWANCARA oleh Asesi berhasil disimpan</h4>
					Terimakasih, Anda telah melakukan <b>PERTANYAAN WAWANCARA untuk Skema $sk[judul].</b><br>
					<a class='btn btn-warning form-control' href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a></div>";
																											} else {
																												$image_parts = explode(";base64,", $_POST['signed']);
																												$image_type_aux = explode("image/", $image_parts[0]);
																												$image_type = $image_type_aux[1];
																												$image_base64 = base64_decode($image_parts[1]);
																												$file = $folderPath . uniqid() . '.' . $image_type;
																												file_put_contents($file, $image_base64);
																												$url =  "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
																												$iddokumen = md5($url);
																												$escaped_url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
																												$alamatip = $_SERVER['REMOTE_ADDR'];
																												$sqlinputdigisign = "INSERT INTO `logdigisign`(`id_dokumen`, id_skema, id_asesi, `url_ditandatangani`, `nama_dokumen`, `penandatangan`, `file`, `ip`) VALUES ('$iddokumen','$sk[id]','$_SESSION[namauser]','$escaped_url','FR.IA.09. PERTANYAAN WAWANCARA','$_SESSION[namalengkap]','$file','$alamatip')";
																												$conn->query($sqlinputdigisign);
																												// input jawaban ceklist portfolio
																												//$sqlgetunitkompetensib2="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
																												//$getunitkompetensib2=$conn->query($sqlgetunitkompetensib2);
																												//while ($unkb2=$getunitkompetensib2->fetch_assoc()){
																												$sqlgetpertanyaan2 = "SELECT * FROM `asesi_portfolio` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' OR `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$sk[id]' ORDER BY `id` ASC";
																												$getpertanyaan2 = $conn->query($sqlgetpertanyaan2);
																												while ($gpp2 = $getpertanyaan2->fetch_assoc()) {
																													//if (!empty($gpp2['pertanyaan'])){
																													$postjawaban = 'jawaban_' . $gpp2['id'];
																													$postrekomendasibukti = 'rekomendasibukti_' . $gpp2['id'];
																													$sqlcekjawaban2 = "SELECT * FROM `asesmen_ia09` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_portfolio`='$gpp2[id]'";
																													$cekjawaban2 = $conn->query($sqlcekjawaban2);
																													$jjw2 = $cekjawaban2->fetch_assoc();
																													if (isset($_POST[$postjawaban])) {
																														$POSTpostjawaban = $_POST[$postjawaban];
																													} else {
																														$POSTpostjawaban = "";
																													}
																													if (isset($_POST[$postrekomendasibukti])) {
																														$POSTpostrekomendasibukti = $_POST[$postrekomendasibukti];
																													} else {
																														$POSTpostrekomendasibukti = "";
																													}
																													$sqlinputak01 = "UPDATE `asesmen_ia09` SET `jawaban`='" . $POSTpostjawaban . "', `rekomendasi`='" . $POSTpostrekomendasibukti . "' WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_asesor`='$_SESSION[namauser]' AND `id_portfolio`='$gpp2[id]'";
																													$conn->query($sqlinputak01);
																													//}
																												}
																												//}
																												//$sqlinputak01b="UPDATE `asesi_asesmen` SET `umpan_balik_ia09`='$_POST[umpan_balik_ia09]' WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																												//$conn->query($sqlinputak01b);
																												echo "<div class='alert alert-success alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Keputusan/ Penilaian PERTANYAAN WAWANCARA oleh Asesi berhasil disimpan dan ditandatangani</h4>
					Terimakasih, Anda telah melakukan <b>PERTANYAAN WAWANCARA untuk Skema $sk[judul], dan tanda tangan telah ditambahkan</b><br>
					<a class='btn btn-warning form-control' href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a></div>";
																											}
																										} else {
																											$folderPath = "../foto_tandatangan/";
																											if (empty($_POST['signed'])) {
																												// input jawaban ceklist portfolio
																												//$sqlgetunitkompetensib2="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
																												//$getunitkompetensib2=$conn->query($sqlgetunitkompetensib2);
																												//while ($unkb2=$getunitkompetensib2->fetch_assoc()){
																												$sqlgetpertanyaan2 = "SELECT * FROM `asesi_portfolio` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' OR `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$sk[id]' ORDER BY `id` ASC";
																												$getpertanyaan2 = $conn->query($sqlgetpertanyaan2);
																												while ($gpp2 = $getpertanyaan2->fetch_assoc()) {
																													//if (!empty($gpp2['pertanyaan'])){
																													$postjawaban = 'jawaban_' . $gpp2['id'];
																													$postrekomendasibukti = 'rekomendasibukti_' . $gpp2['id'];
																													$sqlcekjawaban2 = "SELECT * FROM `asesmen_ia09` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_portfolio`='$gpp2[id]'";
																													$cekjawaban2 = $conn->query($sqlcekjawaban2);
																													$jjw2 = $cekjawaban2->fetch_assoc();
																													if (isset($_POST[$postjawaban])) {
																														$POSTpostjawaban = $_POST[$postjawaban];
																													} else {
																														$POSTpostjawaban = "";
																													}
																													if (isset($_POST[$postrekomendasibukti])) {
																														$POSTpostrekomendasibukti = $_POST[$postrekomendasibukti];
																													} else {
																														$POSTpostrekomendasibukti = "";
																													}
																													$sqlinputak01 = "INSERT INTO `asesmen_ia09`(`id_asesi`, `id_jadwal`, `id_asesor`, `id_portfolio`, `jawaban`, `rekomendasi`) VALUES ('$_GET[ida]','$_GET[idj]','$_SESSION[namauser]','$gpp2[id]','" . $POSTpostjawaban . "','" . $POSTpostrekomendasibukti . "')";
																													$conn->query($sqlinputak01);
																													//}
																												}
																												//}
																												//$sqlinputak01b="UPDATE `asesi_asesmen` SET `umpan_balik_ia09`='$_POST[umpan_balik_ia09]' WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																												//$conn->query($sqlinputak01b);
																												echo "<div class='alert alert-success alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Keputusan/ Penilaian PERTANYAAN WAWANCARA oleh Asesi berhasil disimpan</h4>
					Terimakasih, Anda telah melakukan <b>PERTANYAAN WAWANCARA untuk Skema $sk[judul].</b><br>
					<a class='btn btn-warning form-control' href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a></div>";
																											} else {
																												$image_parts = explode(";base64,", $_POST['signed']);
																												$image_type_aux = explode("image/", $image_parts[0]);
																												$image_type = $image_type_aux[1];
																												$image_base64 = base64_decode($image_parts[1]);
																												$file = $folderPath . uniqid() . '.' . $image_type;
																												file_put_contents($file, $image_base64);
																												$url =  "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
																												$iddokumen = md5($url);
																												$escaped_url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
																												$alamatip = $_SERVER['REMOTE_ADDR'];
																												$sqlinputdigisign = "INSERT INTO `logdigisign`(`id_dokumen`, id_skema, id_asesi, `url_ditandatangani`, `nama_dokumen`, `penandatangan`, `file`, `ip`) VALUES ('$iddokumen','$sk[id]','$_SESSION[namauser]','$escaped_url','FR.IA.09. PERTANYAAN WAWANCARA','$_SESSION[namalengkap]','$file','$alamatip')";
																												$conn->query($sqlinputdigisign);
																												// input jawaban ceklist portfolio
																												//$sqlgetunitkompetensib2="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
																												//$getunitkompetensib2=$conn->query($sqlgetunitkompetensib2);
																												//while ($unkb2=$getunitkompetensib2->fetch_assoc()){
																												$sqlgetpertanyaan2 = "SELECT * FROM `asesi_portfolio` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' OR `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$sk[id]' ORDER BY `id` ASC";
																												$getpertanyaan2 = $conn->query($sqlgetpertanyaan2);
																												while ($gpp2 = $getpertanyaan2->fetch_assoc()) {
																													//if (!empty($gpp2['pertanyaan'])){
																													$postjawaban = 'jawaban_' . $gpp2['id'];
																													$postrekomendasibukti = 'rekomendasibukti_' . $gpp2['id'];
																													$sqlcekjawaban2 = "SELECT * FROM `asesmen_ia09` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_portfolio`='$gpp2[id]'";
																													$cekjawaban2 = $conn->query($sqlcekjawaban2);
																													$jjw2 = $cekjawaban2->fetch_assoc();
																													if (isset($_POST[$postjawaban])) {
																														$POSTpostjawaban = $_POST[$postjawaban];
																													} else {
																														$POSTpostjawaban = "";
																													}
																													if (isset($_POST[$postrekomendasibukti])) {
																														$POSTpostrekomendasibukti = $_POST[$postrekomendasibukti];
																													} else {
																														$POSTpostrekomendasibukti = "";
																													}
																													$sqlinputak01 = "INSERT INTO `asesmen_ia09`(`id_asesi`, `id_jadwal`, `id_asesor`, `id_portfolio`, `jawaban`, `rekomendasi`) VALUES ('$_GET[ida]','$_GET[idj]','$_SESSION[namauser]','$gpp2[id]','" . $POSTpostjawaban . "','" . $POSTpostrekomendasibukti . "')";
																													$conn->query($sqlinputak01);
																													//}
																												}
																												//}
																												//$sqlinputak01b="UPDATE `asesi_asesmen` SET `umpan_balik_ia09`='$_POST[umpan_balik_ia09]' WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																												//$conn->query($sqlinputak01b);
																												echo "<div class='alert alert-success alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Keputusan/ Penilaian PERTANYAAN WAWANCARA oleh Asesi berhasil disimpan dan ditandatangani</h4>
					Terimakasih, Anda telah melakukan <b>PERTANYAAN WAWANCARA untuk Skema $sk[judul], dan tanda tangan telah ditambahkan</b><br>
					<a class='btn btn-warning form-control' href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a></div>";
																											}
																										}
																									}
																									$tanggalasesmen = tgl_indo($jd['tgl_asesmen']);
																									echo "<!-- Main content -->
		<section class='content'>
			<div class='row'>
			<div class='col-xs-12'>
				<div class='box'>
				<div class='box-body'>
					<!-- form start -->
					<div class='box-body'>
						<h2>FR.IA.09. PERTANYAAN WAWANCARA</h2>
						<form role='form' method='POST' enctype='multipart/form-data'>
						<table id='example9' class='table table-bordered table-striped'>
							<tr><td rowspan='2' width='25%'>Skema Sertifikasi (KKNI/Okupasi/Klaster)</td><td>Judul :</td><td>$sk[judul]</td></tr>
							<tr><td width='25%'>Nomor :</td><td>$sk[kode_skema]</td></tr>
							<tr><td width='25%'>TUK</td><td colspan='2'>$tukjen[jenis_tuk]</td></tr>
							<tr><td width='25%'>Nama Asesor</td><td colspan='2'>$namaasesor</td></tr>
							<tr><td width='25%'>Nama Asesi</td><td colspan='2'>$rowAgen[nama]</td></tr>
							<tr><td width='25%'>Tanggal</td><td colspan='2'>$tanggalasesmen</td></tr>
						</table>";
																									echo "<table id='example9' class='table table-bordered table-striped'>";
																									$sqlgetunitkompetensi = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
																									$getunitkompetensi = $conn->query($sqlgetunitkompetensi);
																									$jumunitnya0 = $getunitkompetensi->num_rows;
																									$jumunitnya = $jumunitnya0 * 2; //kode dan judul unit adalah 2 baris
																									$noku = 1;
																									//while unitkompetensi ==================================================================
																									while ($unk = $getunitkompetensi->fetch_assoc()) {
																										if ($noku == 1) {
																											echo "<tr><td rowspan='" . $jumunitnya . "' valign='middle'>Unit Kompetensi</td><td>Kode Unit</td><td>" . $unk['kode_unit'] . "</td></tr>";
																											echo "<tr><td>Judul Unit</td><td>" . $unk['judul'] . "</td></tr>";
																										} else {
																											echo "<tr><td>Kode Unit</td><td>" . $unk['kode_unit'] . "</td></tr>";
																											echo "<tr><td>Judul Unit</td><td>" . $unk['judul'] . "</td></tr>";
																										}
																										$noku++;
																									}
																									//end while unitkompetensi =============================================
																									echo "</table>
						<h3>Setiap pertanyaan harus terkait dengan Elemen</h3>
						<h4>Tuliskan bukti-bukti yang terdapat pada Ceklis Verifikasi Portofolio yang memerlukan wawancara</h4>
						<table id='example9' class='table table-bordered table-striped'>
						<thead>
						<tr><th>No.</th><th>Bukti-Bukti Kompetensi</th></tr>
						</thead>
						<tbody>";
																									// dokumen portfolio
																									$sqlgetunitkompetensib = "SELECT * FROM `asesi_portfolio` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' OR `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$sk[id]' ORDER BY `id` ASC";
																									$getunitkompetensib = $conn->query($sqlgetunitkompetensib);
																									$nopp = 1;
																									while ($unkb = $getunitkompetensib->fetch_assoc()) {
																										echo "<tr><td>$nopp</td><td>" . $unkb['jenis_portfolio'] . "<br>" . $unkb['nama_doc'] . "<br>" . $unkb['peran_portfolio'] . "<br>Tanggal Dokumen: " . tgl_indo($unkb['tgl_doc']) . "<br><a href='";
																										if (substr($unkb['file'], 0, 4) == 'http') {
																											echo $unkb['file'];
																										} else {
																											echo "../foto_portfolio/" . $unkb['file'];
																										}
																										echo "' target='_blank' class='btn btn-primary btn-xs'>Lihat Dokumen</a></td>
							</tr>";
																										$nopp++;
																									}
																									echo "</tbody></table>";
																									echo "<table id='example9' class='table table-bordered table-striped'>
						<thead>
						<tr><th colspan='2' rowspan='2' align='center' valign='middle'>Daftar Pertanyaan Wawancara</th><th rowspan='2'>Kesimpulan Jawaban Asesi</th><th colspan='2'>Rekomendasi</th></tr>
						<tr><th>K</th><th>BK</th></tr></thead>
						<tbody>";
																									$sqlgetunitkompetensib2 = "SELECT * FROM `asesi_portfolio` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' OR `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$sk[id]' ORDER BY `id` ASC";
																									$getunitkompetensib2 = $conn->query($sqlgetunitkompetensib2);
																									$nopp2 = 1;
																									while ($unkb2 = $getunitkompetensib2->fetch_assoc()) {
																										$sqlcekjawaban = "SELECT * FROM `asesmen_ia08` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_portfolio`='$unkb2[id]'";
																										$cekjawaban = $conn->query($sqlcekjawaban);
																										$jjw = $cekjawaban->fetch_assoc();
																										$sqlcekjawabanx = "SELECT * FROM `asesmen_ia09` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_portfolio`='$unkb2[id]'";
																										$cekjawabanx = $conn->query($sqlcekjawabanx);
																										$jumia09 = $cekjawabanx->num_rows;
																										if ($jumia09) {
																											$jjwx = $cekjawabanx->fetch_assoc();
																											echo "<tr><td>$nopp2</td><td>Sesuai dengan bukti : $nopp2<br>$jjw[pertanyaan]</td><td><textarea name='jawaban_" . $unkb2['id'] . "' id='jawaban_" . $unkb2['id'] . "' class='form-control'>";
																											if (!empty($jjwx['jawaban'])) {
																												echo $jjwx['jawaban'];
																											} else {
																												echo "";
																											}
																											echo "</textarea></td>
								<td><input type='radio' name='rekomendasibukti_" . $unkb2['id'] . "' id='rekomendasibukti" . $unkb2['id'] . "_1' value='K' ";
																											if ($jjwx['rekomendasi'] == 'K') {
																												echo " checked";
																											} else {
																												echo "";
																											}
																											echo ">&nbsp;</td>
								<td><input type='radio' name='rekomendasibukti_" . $unkb2['id'] . "' id='rekomendasibukti" . $unkb2['id'] . "_2' value='BK' ";
																											if ($jjwx['rekomendasi'] == 'BK') {
																												echo " checked";
																											} else {
																												echo "";
																											}
																											echo ">&nbsp;</td>
								</tr>";
																										} else {
																											echo "<tr><td>$nopp2</td><td>Sesuai dengan bukti : $nopp2<br>$jjw[pertanyaan]</td><td><textarea name='jawaban_" . $unkb2['id'] . "' id='jawaban_" . $unkb2['id'] . "' class='form-control'></textarea></td>
								<td><input type='radio' name='rekomendasibukti_" . $unkb2['id'] . "' id='rekomendasibukti" . $unkb2['id'] . "_1' value='K'>&nbsp;</td>
								<td><input type='radio' name='rekomendasibukti_" . $unkb2['id'] . "' id='rekomendasibukti" . $unkb2['id'] . "_2' value='BK'>&nbsp;</td>
								</tr>";
																										}
																										$nopp2++;
																									}
																									echo "</tbody></table>";
																									// cek tandatangan digital
																									$url = "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
																									$iddokumen = md5($url);
																									$sqlcektandatangan = "SELECT * FROM `logdigisign` WHERE `id_dokumen`='$iddokumen' AND `penandatangan`='$_SESSION[namalengkap]' ORDER BY `id` DESC";
																									$cektandatangan = $conn->query($sqlcektandatangan);
																									$jumttd = $cektandatangan->num_rows;
																									$ttdx = $cektandatangan->fetch_assoc();
																									if ($jumttd > 0) {
																										echo "<div class='col-md-12'>
										<label class='' for=''>Persetujuan/ Tanda Tangan yang telah Anda berikan:</label>
										<br/>
										<img src='../$ttdx[file]' width='400px'/>
										<br/>
									</div>";
																									}
																									echo "<div class='col-md-12'>
							<label class='' for=''>Tanda Tangan:</label>
							<br/>
							<div id='sig' ></div>
							<br/>
							<button id='clear'>Hapus Tanda Tangan</button>
							<textarea id='signature64' name='signed' style='display: none'></textarea>
						</div>
						<script type='text/javascript'>
							var sig = $('#sig').signature({syncField: '#signature64', syncFormat: 'PNG', color: '#58009F'});
							$('#clear').click(function(e) {
								e.preventDefault();
								sig.signature('clear');
								$('#signature64').val('');
							});
						</script></div>";
																									echo "<div class='box-footer bg-black'>
							<div class='col-md-6 col-sm-12 col-xs-12'>
									<a class='btn btn-danger form-control' id=reset-validate-form href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a>
							</div>
							<div class='col-md-6 col-sm-12 col-xs-12'>
									<button type='submit' class='btn btn-success form-control' name='simpan'>Simpan Jawaban</button>
							</div>
						</div>
					</form>			
				</div>
				<!-- /.box-body -->
				</div>
				<!-- /.box -->
			</div>
			<!-- /.col -->
			</div>
			<!-- /.row -->
		</section>
		<!-- /.content -->";
																								}
																								// Bagian Input Hasil Asesmen FORM-FR.IA.11 Asesor
																								elseif ($_GET['module'] == 'form-fr-ia-11') {
																									$sqllogin = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_GET[ida]'";
																									$login = $conn->query($sqllogin);
																									$ketemu = $login->num_rows;
																									$rowAgen = $login->fetch_assoc();
																									$sqlgetjadwal = "SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
																									$getjadwal = $conn->query($sqlgetjadwal);
																									$jd = $getjadwal->fetch_assoc();
																									$sqlgetskema = "SELECT * FROM `skema_kkni` WHERE `id`='$jd[id_skemakkni]'";
																									$getskema = $conn->query($sqlgetskema);
																									$sk = $getskema->fetch_assoc();
																									$sqlgetasesordata = "SELECT * FROM `asesor` WHERE `no_ktp`='$_SESSION[namauser]'";
																									$getasesordata = $conn->query($sqlgetasesordata);
																									$asr = $getasesordata->fetch_assoc();
																									$sqltuk = "SELECT * FROM `tuk` WHERE `id`='$jd[tempat_asesmen]'";
																									$tuk = $conn->query($sqltuk);
																									$tq = $tuk->fetch_assoc();
																									$sqltukjenis = "SELECT `jenis_tuk` FROM `tuk_jenis` WHERE `id`='$tq[jenis_tuk]'";
																									$tukjenis = $conn->query($sqltukjenis);
																									$tukjen = $tukjenis->fetch_assoc();
																									$noasr = 1;
																									$getasesor = $conn->query("SELECT * FROM `jadwal_asesor` WHERE `id_jadwal`='$_GET[idj]'");
																									while ($gas = $getasesor->fetch_assoc()) {
																										$sqlasesor = "SELECT * FROM `asesor` WHERE `id`='$gas[id_asesor]'";
																										$asesor = $conn->query($sqlasesor);
																										$asr = $asesor->fetch_assoc();
																										if (!empty($asr['gelar_depan'])) {
																											if (!empty($asr['gelar_blk'])) {
																												$namaasesor = $asr['gelar_depan'] . " " . $asr['nama'] . ", " . $asr['gelar_blk'];
																											} else {
																												$namaasesor = $asr['gelar_depan'] . " " . $asr['nama'];
																											}
																										} else {
																											if (!empty($asr['gelar_blk'])) {
																												$namaasesor = $asr['nama'] . ", " . $asr['gelar_blk'];
																											} else {
																												$namaasesor = $asr['nama'];
																											}
																										}
																										$noregasesor = $asr['no_induk'];
																										$namaasesor = $noasr . '. ' . $namaasesor;
																										$noregasesor = $noasr . '. ' . $noregasesor;
																										$noasr++;
																									}
																									echo "<!-- Content Header (Page header) -->
			<section class='content-header'>
				<h1>
				Input FR.IA.11
				<small>Skema $sk[judul]</small>
				</h1>
				<ol class='breadcrumb'>
				<li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
				<li class='active'>FR.IA.11</li>
				</ol>
			</section>";
																									if (isset($_REQUEST['simpan'])) {
																										$sqlcekgetak01 = "SELECT * FROM `asesmen_ia11` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																										$cekgetak01 = $conn->query($sqlcekgetak01);
																										$cekak01 = $cekgetak01->num_rows;
																										$tglsekarang = date("Y-m-d");
																										if ($cekak01 > 0) {
																											$folderPath = "../foto_tandatangan/";
																											if (empty($_POST['signed'])) {
																												// input ceklis meninjau instrumen asesmen
																												/* $sqlgetunitkompetensib2="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
					$getunitkompetensib2=$conn->query($sqlgetunitkompetensib2);
					while ($unkb2=$getunitkompetensib2->fetch_assoc()){ */
																												$sqlgetpertanyaan2 = "SELECT * FROM `skema_meninjauasesmen` ORDER BY `id` ASC";
																												$getpertanyaan2 = $conn->query($sqlgetpertanyaan2);
																												while ($gpp2 = $getpertanyaan2->fetch_assoc()) {
																													//if (!empty($gpp2['pertanyaan'])){
																													$posttanggapan = 'tanggapan' . $gpp2['id'];
																													$postjawaban = 'jawaban' . $gpp2['id'];
																													$sqlcekjawaban2 = "SELECT * FROM `asesmen_ia11` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='$gpp2[id]'";
																													$cekjawaban2 = $conn->query($sqlcekjawaban2);
																													$jjw2 = $cekjawaban2->fetch_assoc();
																													$sqlinputak01 = "UPDATE `asesmen_ia11` SET `tanggapan`='" . $_POST[$posttanggapan] . "', `jawaban`='" . $_POST[$postjawaban] . "' WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$jd[id_skemakkni]' AND `id_jadwal`='$_GET[idj]'";
																													$conn->query($sqlinputak01);
																													//}
																												}
																												//}
																												$sqlinputak01b = "UPDATE `asesi_asesmen` SET `komentar_ia11`='$_POST[komentar_ia11]' WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																												$conn->query($sqlinputak01b);
																												echo "<div class='alert alert-success alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Ceklis Meninjau Instrumen Asesmen oleh Asesor berhasil disimpan</h4>
					Terimakasih, Anda telah melakukan input <b>Ceklis Meninjau Instrumen Asesmen untuk Skema $sk[judul].</b><br>
					<a class='btn btn-warning form-control' href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a></div>";
																											} else {
																												$image_parts = explode(";base64,", $_POST['signed']);
																												$image_type_aux = explode("image/", $image_parts[0]);
																												$image_type = $image_type_aux[1];
																												$image_base64 = base64_decode($image_parts[1]);
																												$file = $folderPath . uniqid() . '.' . $image_type;
																												file_put_contents($file, $image_base64);
																												$url =  "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
																												$iddokumen = md5($url);
																												$escaped_url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
																												$alamatip = $_SERVER['REMOTE_ADDR'];
																												$sqlinputdigisign = "INSERT INTO `logdigisign`(`id_dokumen`, id_skema, id_asesi, `url_ditandatangani`, `nama_dokumen`, `penandatangan`, `file`, `ip`) VALUES ('$iddokumen','$sk[id]','$_SESSION[namauser]','$escaped_url','FR.IA.09. PERTANYAAN WAWANCARA','$_SESSION[namalengkap]','$file','$alamatip')";
																												$conn->query($sqlinputdigisign);
																												// input ceklis meninjau instrumen asesmen
																												/* $sqlgetunitkompetensib2="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
					$getunitkompetensib2=$conn->query($sqlgetunitkompetensib2);
					while ($unkb2=$getunitkompetensib2->fetch_assoc()){ */
																												$sqlgetpertanyaan2 = "SELECT * FROM `skema_meninjauasesmen` ORDER BY `id` ASC";
																												$getpertanyaan2 = $conn->query($sqlgetpertanyaan2);
																												while ($gpp2 = $getpertanyaan2->fetch_assoc()) {
																													//if (!empty($gpp2['pertanyaan'])){
																													$postkomentar = 'komentar' . $gpp2['id'];
																													$postjawaban = 'jawaban' . $gpp2['id'];
																													$sqlcekjawaban2 = "SELECT * FROM `asesmen_ia11` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='$gpp2[id]'";
																													$cekjawaban2 = $conn->query($sqlcekjawaban2);
																													$jjw2 = $cekjawaban2->fetch_assoc();
																													$sqlinputak01 = "UPDATE `asesmen_ia11` SET `komentar`='" . $_POST[$postkomentar] . "', `jawaban`='" . $_POST[$postjawaban] . "' WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$jd[id_skemakkni]' AND `id_jadwal`='$_GET[idj]'";
																													$conn->query($sqlinputak01);
																													//}
																												}
																												//}
																												$sqlinputak01b = "UPDATE `asesi_asesmen` SET `komentar_ia11`='$_POST[komentar_ia11]' WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																												$conn->query($sqlinputak01b);
																												echo "<div class='alert alert-success alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Ceklis Meninjau Instrumen Asesmen oleh Asesor berhasil disimpan</h4>
					Terimakasih, Anda telah melakukan input <b>Ceklis Meninjau Instrumen Asesmen untuk Skema $sk[judul], dan tanda tangan telah ditambahkan</b><br>
					<a class='btn btn-warning form-control' href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a></div>";
																											}
																										} else {
																											$folderPath = "../foto_tandatangan/";
																											if (empty($_POST['signed'])) {
																												// input ceklis meninjau instrumen asesmen
																												/* $sqlgetunitkompetensib2="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
					$getunitkompetensib2=$conn->query($sqlgetunitkompetensib2);
					while ($unkb2=$getunitkompetensib2->fetch_assoc()){ */
																												$sqlgetpertanyaan2 = "SELECT * FROM `skema_meninjauasesmen` ORDER BY `id` ASC";
																												$getpertanyaan2 = $conn->query($sqlgetpertanyaan2);
																												while ($gpp2 = $getpertanyaan2->fetch_assoc()) {
																													//if (!empty($gpp2['pertanyaan'])){
																													$postkomentar = 'komentar' . $gpp2['id'];
																													$postjawaban = 'jawaban' . $gpp2['id'];
																													$sqlcekjawaban2 = "SELECT * FROM `asesmen_ia11` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='$gpp2[id]'";
																													$cekjawaban2 = $conn->query($sqlcekjawaban2);
																													$jjw2 = $cekjawaban2->fetch_assoc();
																													$sqlinputak01 = "INSERT INTO `asesmen_ia11`(`id_asesi`, `id_skemakkni`, `id_jadwal`, `id_unitkompetensi`, `id_pertanyaan`, `komentar`, `jawaban`) VALUES ('$_GET[ida]','$sk[id]','$_GET[idj]','$unkb2[id]','$gpp2[id]','" . $_POST[$postkomentar] . "','" . $_POST[$postjawaban] . "')";
																													$conn->query($sqlinputak01);
																													//}
																												}
																												//}
																												$sqlinputak01b = "UPDATE `asesi_asesmen` SET `komentar_ia11`='$_POST[komentar_ia11]' WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																												$conn->query($sqlinputak01b);
																												echo "<div class='alert alert-success alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Ceklis Meninjau Instrumen Asesmen oleh Asesor berhasil disimpan</h4>
					Terimakasih, Anda telah melakukan input <b>Ceklis Meninjau Instrumen Asesmen untuk Skema $sk[judul].</b><br>
					<a class='btn btn-warning form-control' href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a></div>";
																											} else {
																												$image_parts = explode(";base64,", $_POST['signed']);
																												$image_type_aux = explode("image/", $image_parts[0]);
																												$image_type = $image_type_aux[1];
																												$image_base64 = base64_decode($image_parts[1]);
																												$file = $folderPath . uniqid() . '.' . $image_type;
																												file_put_contents($file, $image_base64);
																												$url =  "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
																												$iddokumen = md5($url);
																												$escaped_url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
																												$alamatip = $_SERVER['REMOTE_ADDR'];
																												$sqlinputdigisign = "INSERT INTO `logdigisign`(`id_dokumen`, id_skema, id_asesi, `url_ditandatangani`, `nama_dokumen`, `penandatangan`, `file`, `ip`) VALUES ('$iddokumen','$sk[id]','$_SESSION[namauser]','$escaped_url','FR.IA.09. PERTANYAAN WAWANCARA','$_SESSION[namalengkap]','$file','$alamatip')";
																												$conn->query($sqlinputdigisign);
																												// input ceklis meninjau instrumen asesmen
																												/* $sqlgetunitkompetensib2="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
					$getunitkompetensib2=$conn->query($sqlgetunitkompetensib2);
					while ($unkb2=$getunitkompetensib2->fetch_assoc()){ */
																												$sqlgetpertanyaan2 = "SELECT * FROM `skema_meninjauasesmen` ORDER BY `id` ASC";
																												$getpertanyaan2 = $conn->query($sqlgetpertanyaan2);
																												while ($gpp2 = $getpertanyaan2->fetch_assoc()) {
																													//if (!empty($gpp2['pertanyaan'])){
																													$postkomentar = 'komentar' . $gpp2['id'];
																													$postjawaban = 'jawaban' . $gpp2['id'];
																													$sqlcekjawaban2 = "SELECT * FROM `asesmen_ia11` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='$gpp2[id]'";
																													$cekjawaban2 = $conn->query($sqlcekjawaban2);
																													$jjw2 = $cekjawaban2->fetch_assoc();
																													$sqlinputak01 = "INSERT INTO `asesmen_ia11`(`id_asesi`, `id_skemakkni`, `id_jadwal`, `id_unitkompetensi`, `id_pertanyaan`, `komentar`, `jawaban`) VALUES ('$_GET[ida]','$sk[id]','$_GET[idj]','$unkb2[id]','$gpp2[id]','" . $_POST[$postkomentar] . "','" . $_POST[$postjawaban] . "')";
																													$conn->query($sqlinputak01);
																													//}
																												}
																												//}
																												$sqlinputak01b = "UPDATE `asesi_asesmen` SET `komentar_ia11`='$_POST[komentar_ia11]' WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																												$conn->query($sqlinputak01b);
																												echo "<div class='alert alert-success alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Ceklis Meninjau Instrumen Asesmen oleh Asesor berhasil disimpan</h4>
					Terimakasih, Anda telah melakukan untuk <b>Ceklis Meninjau Instrumen Asesmen untuk Skema $sk[judul], dan tanda tangan telah ditambahkan</b><br>
					<a class='btn btn-warning form-control' href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a></div>";
																											}
																										}
																									}
																									$tanggalasesmen = tgl_indo($jd['tgl_asesmen']);
																									echo "<!-- Main content -->
			<section class='content'>
				<div class='row'>
					<div class='col-xs-12'>
						<div class='box'>
							<div class='box-body'>
								<!-- form start -->
								<div class='box-body'>
						<h2>FR.IA.11. CEKLIS MENINJAU INSTRUMEN ASESMEN</h2>
						<form role='form' method='POST' enctype='multipart/form-data'>
						<table id='example9' class='table table-bordered table-striped'>
							<tr><td rowspan='2' width='25%'>Skema Sertifikasi (KKNI/Okupasi/Klaster)</td><td>Judul :</td><td>$sk[judul]</td></tr>
							<tr><td width='25%'>Nomor :</td><td>$sk[kode_skema]</td></tr>
							<tr><td width='25%'>TUK</td><td colspan='2'>$tukjen[jenis_tuk]</td></tr>
							<tr><td width='25%'>Nama Asesor</td><td colspan='2'>$namaasesor</td></tr>
							<tr><td width='25%'>Nama Asesi</td><td colspan='2'>$rowAgen[nama]</td></tr>
							<tr><td width='25%'>Tanggal</td><td colspan='2'>$tanggalasesmen</td></tr>
						</table>
						<table id='example9' class='table table-bordered table-striped'>
							<tr><th>PANDUAN BAGI ASESOR</th></tr>
							<tr><td><ul>
							<li>Isilah tabel ini sesuai dengan informasi sesuai pertanyaan/pernyataan dalam tabel dibawah ini.</li>
							<li>Beri tanda centang (V) pada hasil penilaian MUK berdasarkan tinjauan anda dengan jastifikasi professional anda.</li>
							<li>Berikan komentar dengan jastifikasi profesional anda.</li>
							</ul></td></tr>
						</table>
						<table id='example9' class='table table-bordered table-striped'>";
																									$sqlgetunitkompetensi = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
																									$getunitkompetensi = $conn->query($sqlgetunitkompetensi);
																									$jumunitnya0 = $getunitkompetensi->num_rows;
																									$jumunitnya = $jumunitnya0 * 2; //kode dan judul unit adalah 2 baris
																									$noku = 1;
																									//while unitkompetensi ==================================================================
																									while ($unk = $getunitkompetensi->fetch_assoc()) {
																										if ($noku == 1) {
																											echo "<tr><td rowspan='" . $jumunitnya . "' valign='middle'>Unit Kompetensi</td><td>Kode Unit</td><td>" . $unk['kode_unit'] . "</td></tr>";
																											echo "<tr><td>Judul Unit</td><td>" . $unk['judul'] . "</td></tr>";
																										} else {
																											echo "<tr><td>Kode Unit</td><td>" . $unk['kode_unit'] . "</td></tr>";
																											echo "<tr><td>Judul Unit</td><td>" . $unk['judul'] . "</td></tr>";
																										}
																										$noku++;
																									}
																									//end while unitkompetensi =============================================
																									echo "</table>
						<table id='example9' class='table table-bordered table-striped'>
						<thead><tr><th>Kegiatan Asesmen</th><th>Ya/Tidak</th><th>Komentar</th></tr></thead>
						<tbody>";
																									// pertanyaan pendukung observasi
																									/* $sqlgetunitkompetensib="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
						$getunitkompetensib=$conn->query($sqlgetunitkompetensib);
						$nopp=1;
						while ($unkb=$getunitkompetensib->fetch_assoc()){ */
																									$sqlgetpertanyaan = "SELECT * FROM `skema_meninjauasesmen` ORDER BY `id` ASC";
																									$getpertanyaan = $conn->query($sqlgetpertanyaan);
																									while ($gpp = $getpertanyaan->fetch_assoc()) {
																										if (!empty($gpp['pertanyaan'])) {
																											$sqlcekjawaban = "SELECT * FROM `asesmen_ia11` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='$gpp[id]'";
																											$cekjawaban = $conn->query($sqlcekjawaban);
																											$jjw = $cekjawaban->fetch_assoc();
																											echo "<tr><td width='40%'><ul><li>" . $gpp['pertanyaan'] . "</li></td><td>
									<input type='radio' name='jawaban$gpp[id]' id='jawaban$gpp[id]1' value='Ya' required='required'";
																											if ($jjw['jawaban'] == 'Ya') {
																												echo " checked";
																											} else {
																												echo "";
																											}
																											echo ">&nbsp;Ya<br>
									<input type='radio' name='jawaban$gpp[id]' id='jawaban$gpp[id]2' value='Tidak' required='required'";
																											if ($jjw['jawaban'] == 'Tidak') {
																												echo " checked";
																											} else {
																												echo "";
																											}
																											echo ">&nbsp;Tidak</td>
									<td><textarea class='form-control' name='komentar$gpp[id]'>";
																											if (!empty($jjw['komentar'])) {
																												echo $jjw['komentar'];
																											} else {
																												echo "";
																											}
																											echo "</textarea>
									</td></tr>";
																											$nopp++;
																										}
																									}
																									//}
																									echo "</tbody></table>";
																									$sqlgetkeputusan = "SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
																									$getkeputusan = $conn->query($sqlgetkeputusan);
																									$getk = $getkeputusan->fetch_assoc();
																									echo "<div class='col-md-12'>
						<label><b>Komentar :</b></label><br />
						<textarea class='form-control' name='komentar_ia11'>";
																									if (!empty($getk['komentar_ia11'])) {
																										echo $getk['komentar_ia11'];
																									} else {
																										echo "";
																									}
																									echo "</textarea>
						</div>";
																									// cek tandatangan digital
																									$url = "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
																									$iddokumen = md5($url);
																									$sqlcektandatangan = "SELECT * FROM `logdigisign` WHERE `id_dokumen`='$iddokumen' AND `penandatangan`='$_SESSION[namalengkap]' ORDER BY `id` DESC";
																									$cektandatangan = $conn->query($sqlcektandatangan);
																									$jumttd = $cektandatangan->num_rows;
																									$ttdx = $cektandatangan->fetch_assoc();
																									if ($jumttd > 0) {
																										echo "<div class='col-md-12'>
										<label class='' for=''>Persetujuan/ Tanda Tangan yang telah Anda berikan:</label>
										<br/>
										<img src='../$ttdx[file]' width='400px'/>
										<br/>
									</div>";
																									}
																									echo "<div class='col-md-12'>
							<label class='' for=''>Tanda Tangan:</label>
							<br/>
							<div id='sig' ></div>
							<br/>
							<button id='clear'>Hapus Tanda Tangan</button>
							<textarea id='signature64' name='signed' style='display: none'></textarea>
						</div>
						<script type='text/javascript'>
							var sig = $('#sig').signature({syncField: '#signature64', syncFormat: 'PNG', color: '#58009F'});
							$('#clear').click(function(e) {
								e.preventDefault();
								sig.signature('clear');
								$('#signature64').val('');
							});
						</script></div>Diadopsi dari templat yang disediakan di Departemen Pendidikan dan Pelatihan, Australia. Merancang alat asesmen untuk hasil yang berkualitas di VET. 2008";
																									echo "<div class='box-footer bg-black'>
							<div class='col-md-4 col-sm-12 col-xs-12'>
									<a class='btn btn-danger form-control' id=reset-validate-form href='?module=tinjauia11&idj=$_GET[idj]'>Kembali</a>
							</div>
							<div class='col-md-4 col-sm-12 col-xs-12'>
									<a href='form-fr-ia-11.php?ida=$_GET[ida]&idj=$_GET[idj]' class='btn btn-primary form-control'>Unduh Formulir</a>
							</div>
							<div class='col-md-4 col-sm-12 col-xs-12'>
									<button type='submit' class='btn btn-success form-control' name='simpan'>Simpan Jawaban</button>
							</div>
						</div>
					</form>			
							</div>
							<!-- /.box-body -->
						</div>
						<!-- /.box -->
					</div>
					<!-- /.col -->
				</div>
				<!-- /.row -->
			</section>
			<!-- /.content -->";
																								}
																								// Bagian Input Hasil Asesmen FORM-FR.VA Asesor
																								elseif ($_GET['module'] == 'form-fr-va') {
																									$sqlgetjadwal = "SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
																									$getjadwal = $conn->query($sqlgetjadwal);
																									$jd = $getjadwal->fetch_assoc();
																									$sqlgetskema = "SELECT * FROM `skema_kkni` WHERE `id`='$jd[id_skemakkni]'";
																									$getskema = $conn->query($sqlgetskema);
																									$sk = $getskema->fetch_assoc();
																									$sqlgetasesordata = "SELECT * FROM `asesor` WHERE `no_ktp`='$_SESSION[namauser]'";
																									$getasesordata = $conn->query($sqlgetasesordata);
																									$asr = $getasesordata->fetch_assoc();
																									$sqltuk = "SELECT * FROM `tuk` WHERE `id`='$jd[tempat_asesmen]'";
																									$tuk = $conn->query($sqltuk);
																									$tq = $tuk->fetch_assoc();
																									$sqltukjenis = "SELECT `jenis_tuk` FROM `tuk_jenis` WHERE `id`='$tq[jenis_tuk]'";
																									$tukjenis = $conn->query($sqltukjenis);
																									$tukjen = $tukjenis->fetch_assoc();
																									echo "<!-- Content Header (Page header) -->
			<section class='content-header'>
				<h1>
				Input FR.VA
				<small>Skema $sk[judul]</small>
				</h1>
				<ol class='breadcrumb'>
				<li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
				<li class='active'>FR.VA</li>
				</ol>
			</section>";
																									$tanggalasesmen = tgl_indo($jd['tgl_asesmen']);
																									$daynya = date("l", $jdq['tgl_asesmen']);
																									switch ($daynya) {
																										case "Monday":
																											$harinya = "Senin";
																											break;
																										case "Tuesday":
																											$harinya = "Selasa";
																											break;
																										case "Wednesday":
																											$harinya = "Rabu";
																											break;
																										case "Thursday":
																											$harinya = "Kamis";
																											break;
																										case "Friday":
																											$harinya = "Jumat";
																											break;
																										case "Saturday":
																											$harinya = "Sabtu";
																											break;
																										case "Sunday":
																											$harinya = "Minggu";
																											break;
																									}
																									echo "<!-- Main content -->
			<section class='content'>
				<div class='row'>
					<div class='col-xs-12'>
						<div class='box'>
							<div class='box-header'>
								<h3 class='box-title'>FR.VA MEMBERIKAN KONTRIBUSI DALAM VALIDASI ASESMEN (MKVA)</h3>
							</div><!-- /.box-header -->
				<form name='frm' action='simpanmkva.php' method='POST' role='form' enctype='multipart/form-data'>
				<div class='box-body'>";
																									$sqlgetmkvadata = "SELECT * FROM `mkva` WHERE `id_jadwal`='$_GET[idj]'";
																									$getmkvadata = $conn->query($sqlgetmkvadata);
																									$smapa1 = $getmkvadata->fetch_assoc();
																									echo "<table id='example9' class='table table-bordered table-striped'>
					<input type='hidden' name='id_jadwal' value='$_GET[idj]'/>
					<input type='hidden' name='id_skema' value='$sk[id]'/>
					<thead>
					<tr><td rowspan='2'><b>Tim Validasi</b></td>
					<td>";
																									$sqlgetvalidator = "SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
																									$getvalidator = $conn->query($sqlgetvalidator);
																									$mkv = $getvalidator->fetch_assoc();
																									$sqlasesor = "SELECT * FROM `asesor` WHERE `id`='$mkv[asesor_mkva1]'";
																									$asesor = $conn->query($sqlasesor);
																									$asr = $asesor->fetch_assoc();
																									if (!empty($asr['gelar_depan'])) {
																										if (!empty($asr['gelar_blk'])) {
																											$namaasesor = $asr['gelar_depan'] . " " . $asr['nama'] . ", " . $asr['gelar_blk'];
																										} else {
																											$namaasesor = $asr['gelar_depan'] . " " . $asr['nama'];
																										}
																									} else {
																										if (!empty($asr['gelar_blk'])) {
																											$namaasesor = $asr['nama'] . ", " . $asr['gelar_blk'];
																										} else {
																											$namaasesor = $asr['nama'];
																										}
																									}
																									echo "1. $namaasesor</td>";
																									echo "<td>Hari/Tanggal</td><td>$harinya, $tanggalasesmen</td></tr>";
																									$sqlasesor2 = "SELECT * FROM `asesor` WHERE `id`='$mkv[asesor_mkva2]'";
																									$asesor2 = $conn->query($sqlasesor2);
																									$asr2 = $asesor2->fetch_assoc();
																									if (!empty($asr2['gelar_depan'])) {
																										if (!empty($asr2['gelar_blk'])) {
																											$namaasesor2 = $asr2['gelar_depan'] . " " . $asr2['nama'] . ", " . $asr2['gelar_blk'];
																										} else {
																											$namaasesor2 = $asr2['gelar_depan'] . " " . $asr2['nama'];
																										}
																									} else {
																										if (!empty($asr['gelar_blk'])) {
																											$namaasesor2 = $asr2['nama'] . ", " . $asr2['gelar_blk'];
																										} else {
																											$namaasesor2 = $asr2['nama'];
																										}
																									}
																									echo "<tr><td>2. $namaasesor2</td>";
																									echo "<td>Tempat</td><td>$tq[nama]</td></tr>";
																									echo "<tr><td><b>Periode</b></td><td colspan='3'><input type='radio' id='periode1' name='periode' value='1'";
																									if ($smapa1['periode'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Sebelum Asesmen</label>
							<br/><input type='radio' id='periode2' name='periode' value='2'";
																									if ($smapa1['periode'] == "2") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Pada Saat Asesmen</label>
							<br/><input type='radio' id='periode3' name='periode' value='3'";
																									if ($smapa1['periode'] == "3") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Setelah Asesmen</label></td></tr>
					<tr><td><b>Nama Skema</b></td><td colspan='3'>$sk[judul]</td></tr>
					<tr><td><b>Nomor Skema</b></td><td colspan='3'>$sk[kode_skema]</td></tr></thead>
					</table>";
																									echo "<div style='overflow-x:auto;'>
					<table id='example9' class='table table-bordered table-striped'>
						<thead>
							<tr bgcolor='#D8D8D8'><th><b>1.</b></th><th colspan='3'><b>Menyiapkan proses validasi</b></th></tr>
						</thead>
						<tbody>
							<tr><td rowspan='8'></td><td><b>Tujuan dan fokus validasi</b></td><td><b>Konteks validasi</b></td><td><b>Pendekatan validasi</b></td></tr>
							<tr>
								<td><div class='checkbox'><label><input type='checkbox' id='tujuan_1' name='tujuan_1' value='1'";
																									if ($smapa1['tujuan_1'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">Bagian dari proses penjaminan mutu organisasi</label></div></td>
								<td><div class='checkbox'><label><input type='checkbox' id='konteks_1' name='konteks_1' value='1'";
																									if ($smapa1['konteks_1'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">Internal organisasi</label></div></td>
								<td><div class='checkbox'><label><input type='checkbox' id='pendekatan_1' name='pendekatan_1' value='1'";
																									if ($smapa1['pendekatan_1'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">Panel asesmen</label></div></td>
							</tr>
							<tr>
								<td><div class='checkbox'><label><input type='checkbox' id='tujuan_2' name='tujuan_2' value='1'";
																									if ($smapa1['tujuan_2'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">Mengantisipasi risiko</label></div></td>
								<td><div class='checkbox'><label><input type='checkbox' id='konteks_2' name='konteks_2' value='1'";
																									if ($smapa1['konteks_2'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">Eksternal organisasi</label></div></td>
								<td><div class='checkbox'><label><input type='checkbox' id='pendekatan_2' name='pendekatan_2' value='1'";
																									if ($smapa1['pendekatan_2'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">Pertemuan moderasi</label></div></td>
							</tr>
							<tr>
								<td><div class='checkbox'><label><input type='checkbox' id='tujuan_3' name='tujuan_3' value='1'";
																									if ($smapa1['tujuan_3'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">Memenuhi persyaratan BNSP</label></div></td>
								<td><div class='checkbox'><label><input type='checkbox' id='konteks_3' name='konteks_3' value='1'";
																									if ($smapa1['konteks_3'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">Proses lisensi/re lisensi</label></div></td>
								<td><div class='checkbox'><label><input type='checkbox' id='pendekatan_3' name='pendekatan_3' value='1'";
																									if ($smapa1['pendekatan_3'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">Mengkaji perangkat asesmen</label></div></td>
							</tr>
							<tr>
								<td><div class='checkbox'><label><input type='checkbox' id='tujuan_4' name='tujuan_4' value='1'";
																									if ($smapa1['tujuan_4'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">Memastikan kesesuaian bukti-bukti</label></div></td>
								<td><div class='checkbox'><label><input type='checkbox' id='konteks_4' name='konteks_4' value='1'";
																									if ($smapa1['konteks_4'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">Dengan kolega asesor</label></div></td>
								<td><div class='checkbox'><label><input type='checkbox' id='pendekatan_4' name='pendekatan_4' value='1'";
																									if ($smapa1['pendekatan_4'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">Acuan pembanding</label></div></td>
							</tr>
							<tr>
								<td><div class='checkbox'><label><input type='checkbox' id='tujuan_5' name='tujuan_5' value='1'";
																									if ($smapa1['tujuan_5'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">Meningkatkan kualitas asesmen</label></div></td>
								<td><div class='checkbox'><label><input type='checkbox' id='konteks_5' name='konteks_5' value='1'";
																									if ($smapa1['konteks_5'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">Kolega dari organisasi pelatihan atau asesmen</label></div></td>
								<td><div class='checkbox'><label><input type='checkbox' id='pendekatan_5' name='pendekatan_5' value='1'";
																									if ($smapa1['pendekatan_5'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">Pengujian lapangan dan uji coba perangkat asesmen</label></div></td>
							</tr>
							<tr>
								<td><div class='checkbox'><label><input type='checkbox' id='tujuan_6' name='tujuan_6' value='1'";
																									if ($smapa1['tujuan_6'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">Mengevaluasi kualitas perangkat asesmen</label></div></td>
								<td><div class='checkbox'><label><input type='checkbox' id='konteks_6' name='konteks_6' value='1'";
																									if ($smapa1['konteks_6'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "><input type='text' name='konteks_6b' class='form-control' ";
																									if (!empty($smapa1['konteks_6b'])) {
																										echo "value='" . $smapa1['konteks_6b'] . "'";
																									}
																									echo "></label></div></td>
								<td><div class='checkbox'><label><input type='checkbox' id='pendekatan_6' name='pendekatan_6' value='1'";
																									if ($smapa1['pendekatan_6'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">Umpan balik dari klien</label></div></td>
							</tr>
							<tr>
								<td><div class='checkbox'><label><input type='checkbox' id='tujuan_7' name='tujuan_7' value='1'";
																									if ($smapa1['tujuan_7'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "><input type='text' name='tujuan_7b' class='form-control' ";
																									if (!empty($smapa1['tujuan_7b'])) {
																										echo "value='" . $smapa1['tujuan_7b'] . "'";
																									}
																									echo "></label></div></td>
								<td></td>
								<td></td>
							</tr>
							<tr><td rowspan='15'></td><td><b>Orang yang relevan</b></td><td><b>Nama</b></td><td><b>Hasil konfirmasi/diskusi tujuan, fokus & konteks</b></td></tr>
							<tr><td rowspan='3'><div class='checkbox'><label><input type='checkbox' id='askom' name='askom' value='1'";
																									if ($smapa1['askom'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">Asesor kompetensi (wajib)</label></div></td><td><div class='input-group'><span class='input-group-addon'>1.</span><input type='text' name='orel_1' class='form-control' ";
																									if (!empty($smapa1['orel_1'])) {
																										echo "value='" . $smapa1['orel_1'] . "'";
																									}
																									echo "></div></td><td><textarea name='konfirmorel_1' class='form-control'>";
																									if (!empty($smapa1['konfirmorel_1'])) {
																										echo $smapa1['konfirmorel_1'];
																									}
																									echo "</textarea></td></tr>
							<tr><td><div class='input-group'><span class='input-group-addon'>2.</span><input type='text' name='orel_2' class='form-control' ";
																									if (!empty($smapa1['orel_2'])) {
																										echo "value='" . $smapa1['orel_2'] . "'";
																									}
																									echo "></div></td><td><textarea name='konfirmorel_2' class='form-control'>";
																									if (!empty($smapa1['konfirmorel_2'])) {
																										echo $smapa1['konfirmorel_2'];
																									}
																									echo "</textarea></td></tr>
							<tr><td><div class='input-group'><span class='input-group-addon'>3.</span><input type='text' name='orel_3' class='form-control' ";
																									if (!empty($smapa1['orel_3'])) {
																										echo "value='" . $smapa1['orel_3'] . "'";
																									}
																									echo "></div></td><td><textarea name='konfirmorel_3' class='form-control'>";
																									if (!empty($smapa1['konfirmorel_3'])) {
																										echo $smapa1['konfirmorel_3'];
																									}
																									echo "</textarea></td></tr>
							<tr><td><div class='checkbox'><label><input type='checkbox' id='leadasesor' name='leadasesor' value='1'";
																									if ($smapa1['leadasesor'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">Lead Asesor</label></div></td><td><input type='text' name='nama_leadasesor' class='form-control' ";
																									if (!empty($smapa1['nama_leadasesor'])) {
																										echo "value='" . $smapa1['nama_leadasesor'] . "'";
																									}
																									echo "></td><td><textarea name='konfirmleadaseesor' class='form-control'>";
																									if (!empty($smapa1['konfirmleadaseesor'])) {
																										echo $smapa1['konfirmleadaseesor'];
																									}
																									echo "</textarea></td></tr>
							<tr><td><div class='checkbox'><label><input type='checkbox' id='manspv' name='manspv' value='1'";
																									if ($smapa1['manspv'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">Manajer, supervisor</label></div></td><td><input type='text' name='nama_manspv' class='form-control' ";
																									if (!empty($smapa1['nama_manspv'])) {
																										echo "value='" . $smapa1['nama_manspv'] . "'";
																									}
																									echo "></td><td><textarea name='konfirmmanspv' class='form-control'>";
																									if (!empty($smapa1['konfirmmanspv'])) {
																										echo $smapa1['konfirmmanspv'];
																									}
																									echo "</textarea></td></tr>
							<tr><td><div class='checkbox'><label><input type='checkbox' id='tenagaahli' name='tenagaahli' value='1'";
																									if ($smapa1['tenagaahli'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">Tenaga ahli di bidangnya</label></div></td><td><input type='text' name='nama_tenagaahli' class='form-control' ";
																									if (!empty($smapa1['nama_tenagaahli'])) {
																										echo "value='" . $smapa1['nama_tenagaahli'] . "'";
																									}
																									echo "></td><td><textarea name='konfirmtenagaahli' class='form-control'>";
																									if (!empty($smapa1['konfirmtenagaahli'])) {
																										echo $smapa1['konfirmtenagaahli'];
																									}
																									echo "</textarea></td></tr>
							<tr><td><div class='checkbox'><label><input type='checkbox' id='koordtraining' name='koordtraining' value='1'";
																									if ($smapa1['koordtraining'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">Koord. Pelatihan</label></div></td><td><input type='text' name='nama_koordtraining' class='form-control' ";
																									if (!empty($smapa1['nama_koordtraining'])) {
																										echo "value='" . $smapa1['nama_koordtraining'] . "'";
																									}
																									echo "></td><td><textarea name='konfirmkoordtraining' class='form-control'>";
																									if (!empty($smapa1['konfirmkoordtraining'])) {
																										echo $smapa1['konfirmkoordtraining'];
																									}
																									echo "</textarea></td></tr>
							<tr><td><div class='checkbox'><label><input type='checkbox' id='asosiasi' name='asosiasi' value='1'";
																									if ($smapa1['asosiasi'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">Anggota asosiasi industri/profesi</label></div></td><td><input type='text' name='nama_asosiasi' class='form-control' ";
																									if (!empty($smapa1['nama_asosiasi'])) {
																										echo "value='" . $smapa1['nama_asosiasi'] . "'";
																									}
																									echo "></td><td><textarea name='konfirmasosiasi' class='form-control'>";
																									if (!empty($smapa1['konfirmasosiasi'])) {
																										echo $smapa1['konfirmasosiasi'];
																									}
																									echo "</textarea></td></tr>
							<tr><td><b>Acuan Pembanding:</b></td><td colspan='2'><b>Dokumen terkait dan bahan-bahan</b></td></tr>
							<tr><td><div class='checkbox'><label><input type='checkbox' id='stdkom' name='stdkom' value='1'";
																									if ($smapa1['stdkom'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">Standar Kompetensi</label></div></td><td colspan='2'><div class='checkbox'><label><input type='checkbox' id='skema' name='skema' value='1'";
																									if ($smapa1['skema'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">Skema sertifikasi</label></div></td></tr>
							<tr><td><div class='checkbox'><label><input type='checkbox' id='sop' name='sop' value='1'";
																									if ($smapa1['sop'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">SOP/IK</label></div></td><td colspan='2'><div class='checkbox'><label><input type='checkbox' id='skk' name='skk' value='1'";
																									if ($smapa1['skk'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">SKKNI/SK3/SKI</label></div></td></tr>
							<tr><td><div class='checkbox'><label><input type='checkbox' id='manualbook' name='manualbook' value='1'";
																									if ($smapa1['manualbook'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">Manual Instruction/book</label></div></td><td colspan='2'><div class='checkbox'><label><input type='checkbox' id='perangkat' name='perangkat' value='1'";
																									if ($smapa1['perangkat'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">Perangkat asesmen</label></div></td></tr>
							<tr><td><div class='checkbox'><label><input type='checkbox' id='stdkinerja' name='stdkinerja' value='1'";
																									if ($smapa1['stdkinerja'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">Standar Kinerja</label></div></td><td colspan='2'><div class='checkbox'><label><input type='checkbox' id='peraturan' name='peraturan' value='1'";
																									if ($smapa1['peraturan'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">Peraturan/Pedoman</label></div></td></tr>
							<tr><td><div class='checkbox'><label><input type='checkbox' id='lainnya' name='lainnya' value='1'";
																									if ($smapa1['lainnya'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "><input type='text' name='nama_lainnya' class='form-control' ";
																									if (!empty($smapa1['nama_lainnya'])) {
																										echo "value='" . $smapa1['nama_lainnya'] . "'";
																									}
																									echo "></label></div></td><td colspan='2'></td></tr>
						</tbody>
					</table>
					<table id='example9' class='table table-bordered table-striped'>
						<thead>
							<tr bgcolor='#D8D8D8'><th><b>2.</b></th><th colspan='2'><b>Memberikan kontribusi dalam proses validasi</b></th></tr>
						</thead>
						<tbody>
							<tr><td rowspan='4'></td><td rowspan='4'>Keterampilan komunikasi yang digunakan dalam kegiatan validasi :</td><td><div class='checkbox'><label><input type='checkbox' id='proaktif' name='proaktif' value='1'";
																									if ($smapa1['proaktif'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">PRO AKTIF</label></div></td></tr>
							<tr><td><div class='checkbox'><label><input type='checkbox' id='activelistening' name='activelistening' value='1'";
																									if ($smapa1['activelistening'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">ACTIVE LISTENING</label></div></td></tr>
							<tr><td><div class='checkbox'><label><input type='checkbox' id='keterampilan1' name='keterampilan1' value='1'";
																									if ($smapa1['keterampilan1'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "><input type='text' name='nama_ketlainnya1' class='form-control' ";
																									if (!empty($smapa1['nama_ketlainnya1'])) {
																										echo "value='" . $smapa1['nama_ketlainnya1'] . "'";
																									}
																									echo "></label></div></td></tr>
							<tr><td><div class='checkbox'><label><input type='checkbox' id='keterampilan2' name='keterampilan2' value='1'";
																									if ($smapa1['keterampilan2'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "><input type='text' name='nama_ketlainnya2' class='form-control' ";
																									if (!empty($smapa1['nama_ketlainnya2'])) {
																										echo "value='" . $smapa1['nama_ketlainnya2'] . "'";
																									}
																									echo "></label></div></td></tr>
						</tbody>
					</table>
					<table id='example9' class='table table-bordered table-striped'>
						<thead>
							<tr><th rowspan='3'><b>No.</b></th><th rowspan='3'><b>Aspek-Aspek Dalam Kegiatan Validasi<br/>(Meninjau, Membandingkan, Mengevaluasi)</b></th><th colspan='8'>Pemenuhan terhadap:</th></tr>
							<tr><th colspan='4'>Aturan Bukti</th><th colspan='4'>Prinsip Asesmen</th></tr>
							<tr><th>V</th><th>A</th><th>T</th><th>M</th><th>V</th><th>R</th><th>F</th><th>F</th></tr>
						</thead>
						<tbody>
							<tr><td>1.</td><td>Proses Asesmen</td><td><input type='checkbox' id='ab1v' name='ab1v' value='1'";
																									if ($smapa1['ab1v'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='ab1a' name='ab1a' value='1'";
																									if ($smapa1['ab1a'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='ab1t' name='ab1t' value='1'";
																									if ($smapa1['ab1t'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='ab1m' name='ab1m' value='1'";
																									if ($smapa1['ab1m'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='pa1v' name='pa1v' value='1'";
																									if ($smapa1['pa1v'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='pa1r' name='pa1r' value='1'";
																									if ($smapa1['pa1r'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='pa1f' name='pa1f' value='1'";
																									if ($smapa1['pa1f'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='pa1f2' name='pa1f2' value='1'";
																									if ($smapa1['pa1f2'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td></tr>
							<tr><td>2.</td><td>Rencana asesmen</td><td><input type='checkbox' id='ab2v' name='ab2v' value='1'";
																									if ($smapa1['ab2v'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='ab2a' name='ab2a' value='1'";
																									if ($smapa1['ab2a'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='ab2t' name='ab2t' value='1'";
																									if ($smapa1['ab2t'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='ab2m' name='ab2m' value='1'";
																									if ($smapa1['ab2m'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='pa2v' name='pa2v' value='1'";
																									if ($smapa1['pa2v'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='pa2r' name='pa2r' value='1'";
																									if ($smapa1['pa2r'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='pa2f' name='pa2f' value='1'";
																									if ($smapa1['pa2f'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='pa2f2' name='pa2f2' value='1'";
																									if ($smapa1['pa2f2'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td></tr>
							<tr><td>3.</td><td>Interpretasi standar kompetensi</td><td><input type='checkbox' id='ab3v' name='ab3v' value='1'";
																									if ($smapa1['ab3v'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='ab3a' name='ab3a' value='1'";
																									if ($smapa1['ab3a'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='ab3t' name='ab3t' value='1'";
																									if ($smapa1['ab3t'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='ab3m' name='ab3m' value='1'";
																									if ($smapa1['ab3m'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='pa3v' name='pa3v' value='1'";
																									if ($smapa1['pa3v'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='pa3r' name='pa3r' value='1'";
																									if ($smapa1['pa3r'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='pa3f' name='pa3f' value='1'";
																									if ($smapa1['pa3f'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='pa3f2' name='pa3f2' value='1'";
																									if ($smapa1['pa3f2'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td></tr>
							<tr><td>4.</td><td>Interpretasi acuan pembanding lainnya</td><td><input type='checkbox' id='ab4v' name='ab4v' value='1'";
																									if ($smapa1['ab4v'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='ab4a' name='ab4a' value='1'";
																									if ($smapa1['ab4a'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='ab4t' name='ab4t' value='1'";
																									if ($smapa1['ab4t'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='ab4m' name='ab4m' value='1'";
																									if ($smapa1['ab4m'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='pa4v' name='pa4v' value='1'";
																									if ($smapa1['pa4v'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='pa4r' name='pa4r' value='1'";
																									if ($smapa1['pa4r'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='pa4f' name='pa4f' value='1'";
																									if ($smapa1['pa4f'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='pa4f2' name='pa4f2' value='1'";
																									if ($smapa1['pa4f2'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td></tr>
							<tr><td>5.</td><td>Penyeleksian dan penerapan metode asesmen</td><td><input type='checkbox' id='ab5v' name='ab5v' value='1'";
																									if ($smapa1['ab5v'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='ab5a' name='ab5a' value='1'";
																									if ($smapa1['ab5a'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='ab5t' name='ab5t' value='1'";
																									if ($smapa1['ab5t'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='ab5m' name='ab5m' value='1'";
																									if ($smapa1['ab5m'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='pa5v' name='pa5v' value='1'";
																									if ($smapa1['pa5v'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='pa5r' name='pa5r' value='1'";
																									if ($smapa1['pa5r'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='pa5f' name='pa5f' value='1'";
																									if ($smapa1['pa5f'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='pa5f2' name='pa5f2' value='1'";
																									if ($smapa1['pa5f2'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td></tr>
							<tr><td>6.</td><td>Penyeleksian dan penerapan perangkat asesmen</td><td><input type='checkbox' id='ab6v' name='ab6v' value='1'";
																									if ($smapa1['ab6v'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='ab6a' name='ab6a' value='1'";
																									if ($smapa1['ab6a'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='ab6t' name='ab6t' value='1'";
																									if ($smapa1['ab6t'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='ab6m' name='ab6m' value='1'";
																									if ($smapa1['ab6m'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='pa6v' name='pa6v' value='1'";
																									if ($smapa1['pa6v'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='pa6r' name='pa6r' value='1'";
																									if ($smapa1['pa6r'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='pa6f' name='pa6f' value='1'";
																									if ($smapa1['pa6f'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='pa6f2' name='pa6f2' value='1'";
																									if ($smapa1['pa6f2'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td></tr>
							<tr><td>7.</td><td>Bukti-bukti yang dikumpulkan</td><td><input type='checkbox' id='ab7v' name='ab7v' value='1'";
																									if ($smapa1['ab7v'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='ab7a' name='ab7a' value='1'";
																									if ($smapa1['ab7a'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='ab7t' name='ab7t' value='1'";
																									if ($smapa1['ab7t'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='ab7m' name='ab7m' value='1'";
																									if ($smapa1['ab7m'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='pa7v' name='pa7v' value='1'";
																									if ($smapa1['pa7v'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='pa7r' name='pa7r' value='1'";
																									if ($smapa1['pa7r'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='pa7f' name='pa7f' value='1'";
																									if ($smapa1['pa7f'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='pa7f2' name='pa7f2' value='1'";
																									if ($smapa1['pa7f2'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td></tr>
							<tr><td>8.</td><td>Proses pengambilan keputusan</td><td><input type='checkbox' id='ab8v' name='ab8v' value='1'";
																									if ($smapa1['ab8v'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='ab8a' name='ab8a' value='1'";
																									if ($smapa1['ab8a'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='ab8t' name='ab8t' value='1'";
																									if ($smapa1['ab8t'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='ab8m' name='ab8m' value='1'";
																									if ($smapa1['ab8m'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='pa8v' name='pa8v' value='1'";
																									if ($smapa1['pa8v'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='pa8r' name='pa8r' value='1'";
																									if ($smapa1['pa8r'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='pa8f' name='pa8f' value='1'";
																									if ($smapa1['pa8f'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td><td><input type='checkbox' id='pa8f2' name='pa8f2' value='1'";
																									if ($smapa1['pa8f2'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "></td></tr>
						</tbody>
					</table>
					</div><!--overflow-->
				</div><!--box body-->
				<div class='box-footer'>
					<div class='col-md-4 col-sm-12 col-xs-12'>
						<a class='btn btn-danger form-control' id=reset-validate-form href='?module=penugasanmkva'>Kembali</a>
					</div>
					<div class='col-md-4 col-sm-12 col-xs-12'>
						<a href='../asesor/form-fr-va.php?&idj=$_GET[idj]' class='btn btn-primary form-control'>Unduh Form FR.VA</a>
					</div>
					<div class='col-md-4 col-sm-12 col-xs-12'>
						<input type='submit' class='btn btn-success form-control' name='simpan' value='Simpan & Lanjut Berikutnya'/>
					</div>
				</div><!--box footer-->
				</form>
				</div><!--box-->
					</div>
					<!-- /.col -->
				</div>
				<!-- /.row -->
			</section>
			<!-- /.content -->";
																								}
																								// Bagian Input Hasil Asesmen FORM-FR.VA Asesor Bagian 2
																								elseif ($_GET['module'] == 'form-fr-va2') {
																									$sqlgetjadwal = "SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
																									$getjadwal = $conn->query($sqlgetjadwal);
																									$jd = $getjadwal->fetch_assoc();
																									$sqlgetskema = "SELECT * FROM `skema_kkni` WHERE `id`='$jd[id_skemakkni]'";
																									$getskema = $conn->query($sqlgetskema);
																									$sk = $getskema->fetch_assoc();
																									$sqlgetasesordata = "SELECT * FROM `asesor` WHERE `no_ktp`='$_SESSION[namauser]'";
																									$getasesordata = $conn->query($sqlgetasesordata);
																									$asr = $getasesordata->fetch_assoc();
																									$sqltuk = "SELECT * FROM `tuk` WHERE `id`='$jd[tempat_asesmen]'";
																									$tuk = $conn->query($sqltuk);
																									$tq = $tuk->fetch_assoc();
																									$sqltukjenis = "SELECT `jenis_tuk` FROM `tuk_jenis` WHERE `id`='$tq[jenis_tuk]'";
																									$tukjenis = $conn->query($sqltukjenis);
																									$tukjen = $tukjenis->fetch_assoc();
																									echo "<!-- Content Header (Page header) -->
			<section class='content-header'>
				<h1>
				Input FR.VA
				<small>Skema $sk[judul]</small>
				</h1>
				<ol class='breadcrumb'>
				<li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
				<li class='active'>FR.VA</li>
				</ol>
			</section>";
																									$tanggalasesmen = tgl_indo($jd['tgl_asesmen']);
																									$daynya = date("l", $jdq['tgl_asesmen']);
																									switch ($daynya) {
																										case "Monday":
																											$harinya = "Senin";
																											break;
																										case "Tuesday":
																											$harinya = "Selasa";
																											break;
																										case "Wednesday":
																											$harinya = "Rabu";
																											break;
																										case "Thursday":
																											$harinya = "Kamis";
																											break;
																										case "Friday":
																											$harinya = "Jumat";
																											break;
																										case "Saturday":
																											$harinya = "Sabtu";
																											break;
																										case "Sunday":
																											$harinya = "Minggu";
																											break;
																									}
																									if (isset($_POST['tambahtemuan'])) {
																										$sqlcektemuan = "SELECT * FROM `mkva_temuan` WHERE `id_jadwal`='$_POST[id_jadwal]' AND `temuan`='$_POST[temuan]' AND `rekomendasi`='$_POST[rekomendasi]'";
																										$cektemuan = $conn->query($sqlcektemuan);
																										$jumtemuan = $cektemuan->num_rows;
																										if ($jumtemuan > 0) {
																											echo "<script>alert('Maaf Temuan Tersebut Sudah Ada');</script>";
																										} else {
																											$sqlinserttemuan = "INSERT INTO `mkva_temuan`(`id_jadwal`, `temuan`, `rekomendasi`) VALUES ('$_POST[id_jadwal]', '$_POST[temuan]', '$_POST[rekomendasi]')";
																											$conn->query($sqlinserttemuan);
																											echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Temuan berhasil disimpan</h4>
				Terimakasih, Anda telah menambahkan informasi <b>Temuan MKVA untuk Skema $sk[judul].</b></div>";
																										}
																									}
																									if (isset($_POST['hapustemuan'])) {
																										$sqlcektemuan = "SELECT * FROM `mkva_temuan` WHERE `id`='$_POST[iddeltemuan]'";
																										$cektemuan = $conn->query($sqlcektemuan);
																										$jumtemuan = $cektemuan->num_rows;
																										if ($jumtemuan == 0) {
																											echo "<script>alert('Maaf Temuan Tersebut Tidak Ada');</script>";
																										} else {
																											$sqlinserttemuan = "DELETE FROM `mkva_temuan` WHERE `id`='$_POST[iddeltemuan]'";
																											$conn->query($sqlinserttemuan);
																											echo "<div class='alert alert-danger alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Temuan berhasil dihapus</h4>
				Terimakasih, Anda telah menghapus informasi <b>Temuan MKVA untuk Skema $sk[judul].</b></div>";
																										}
																									}
																									if (isset($_POST['tambahperbaikan'])) {
																										$sqlcekperbaikan = "SELECT * FROM `mkva_perbaikan` WHERE `id_jadwal`='$_POST[id_jadwal]' AND `perbaikan`='$_POST[perbaikan]' AND `penyelesaian`='$_POST[penyelesaian]' AND `penanggungjawab`='$_POST[penanggungjawab]'";
																										$cekperbaikan = $conn->query($sqlcekperbaikan);
																										$jumperbaikan = $cekperbaikan->num_rows;
																										if ($jumperbaikan > 0) {
																											echo "<script>alert('Maaf Perbaikan Tersebut Sudah Ada');</script>";
																										} else {
																											$sqlinsertperbaikan = "INSERT INTO `mkva_perbaikan`(`id_jadwal`, `perbaikan`, `penyelesaian`, `penanggungjawab`) VALUES ('$_POST[id_jadwal]', '$_POST[perbaikan]', '$_POST[penyelesaian]', '$_POST[penanggungjawab]')";
																											$conn->query($sqlinsertperbaikan);
																											echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Informasi Perbaikan berhasil disimpan</h4>
				Terimakasih, Anda telah menambahkan informasi <b>Perbaikan atas Temuan MKVA untuk Skema $sk[judul].</b></div>";
																										}
																									}
																									if (isset($_POST['hapusperbaikan'])) {
																										$sqlcekperbaikan = "SELECT * FROM `mkva_perbaikan` WHERE `id`='$_POST[iddelperbaikan]'";
																										$cekperbaikan = $conn->query($sqlcekperbaikan);
																										$jumperbaikan = $cekperbaikan->num_rows;
																										if ($jumperbaikan == 0) {
																											echo "<script>alert('Maaf Data Perbaikan Tersebut Tidak Ada');</script>";
																										} else {
																											$sqlinsertperbaikan = "DELETE FROM `mkva_perbaikan` WHERE `id`='$_POST[iddelperbaikan]'";
																											$conn->query($sqlinsertperbaikan);
																											echo "<div class='alert alert-danger alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Informasi Perbaikan berhasil dihapus</h4>
				Terimakasih, Anda telah menghapus informasi <b>Perbaikan atas Temuan MKVA untuk Skema $sk[judul].</b></div>";
																										}
																									}
																									echo "<!-- Main content -->
			<section class='content'>
				<div class='row'>
					<div class='col-xs-12'>
						<div class='box'>
							<div class='box-header'>
								<h3 class='box-title'>FR.VA MEMBERIKAN KONTRIBUSI DALAM VALIDASI ASESMEN (MKVA) (Bagian 2)</h3>
							</div><!-- /.box-header -->
				<form name='frm' method='POST' role='form' enctype='multipart/form-data'>
				<div class='box-body'>";
																									$sqlgetmkvadata = "SELECT * FROM `mkva` WHERE `id_jadwal`='$_GET[idj]'";
																									$getmkvadata = $conn->query($sqlgetmkvadata);
																									$smapa1 = $getmkvadata->fetch_assoc();
																									echo "<table id='example9' class='table table-bordered table-striped'>
					<input type='hidden' name='id_jadwal' value='$_GET[idj]'/>
					<input type='hidden' name='id_skema' value='$sk[id]'/>
					<thead>
					<tr><td rowspan='2'><b>Tim Validasi</b></td>
					<td>";
																									$sqlgetvalidator = "SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
																									$getvalidator = $conn->query($sqlgetvalidator);
																									$mkv = $getvalidator->fetch_assoc();
																									$sqlasesor = "SELECT * FROM `asesor` WHERE `id`='$mkv[asesor_mkva1]'";
																									$asesor = $conn->query($sqlasesor);
																									$asr = $asesor->fetch_assoc();
																									if (!empty($asr['gelar_depan'])) {
																										if (!empty($asr['gelar_blk'])) {
																											$namaasesor = $asr['gelar_depan'] . " " . $asr['nama'] . ", " . $asr['gelar_blk'];
																										} else {
																											$namaasesor = $asr['gelar_depan'] . " " . $asr['nama'];
																										}
																									} else {
																										if (!empty($asr['gelar_blk'])) {
																											$namaasesor = $asr['nama'] . ", " . $asr['gelar_blk'];
																										} else {
																											$namaasesor = $asr['nama'];
																										}
																									}
																									echo "1. $namaasesor</td>";
																									echo "<td>Hari/Tanggal</td><td>$harinya, $tanggalasesmen</td></tr>";
																									$sqlasesor2 = "SELECT * FROM `asesor` WHERE `id`='$mkv[asesor_mkva2]'";
																									$asesor2 = $conn->query($sqlasesor2);
																									$asr2 = $asesor2->fetch_assoc();
																									if (!empty($asr2['gelar_depan'])) {
																										if (!empty($asr2['gelar_blk'])) {
																											$namaasesor2 = $asr2['gelar_depan'] . " " . $asr2['nama'] . ", " . $asr2['gelar_blk'];
																										} else {
																											$namaasesor2 = $asr2['gelar_depan'] . " " . $asr2['nama'];
																										}
																									} else {
																										if (!empty($asr['gelar_blk'])) {
																											$namaasesor2 = $asr2['nama'] . ", " . $asr2['gelar_blk'];
																										} else {
																											$namaasesor2 = $asr2['nama'];
																										}
																									}
																									echo "<tr><td>2. $namaasesor2</td>";
																									echo "<td>Tempat</td><td>$tq[nama]</td></tr>";
																									echo "<tr><td><b>Periode</b></td><td colspan='3'><input type='radio' id='periode1' name='periode' value='1'";
																									if ($smapa1['periode'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Sebelum Asesmen</label>
							<br/><input type='radio' id='periode2' name='periode' value='2'";
																									if ($smapa1['periode'] == "2") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Pada Saat Asesmen</label>
							<br/><input type='radio' id='periode3' name='periode' value='3'";
																									if ($smapa1['periode'] == "3") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Setelah Asesmen</label></td></tr>
					<tr><td><b>Nama Skema</b></td><td colspan='3'>$sk[judul]</td></tr>
					<tr><td><b>Nomor Skema</b></td><td colspan='3'>$sk[kode_skema]</td></tr></thead>
					</table>";
																									echo "<div style='overflow-x:auto;'>
					<table id='example9' class='table table-bordered table-striped'>
						<thead>
							<tr bgcolor='#D8D8D8'><th><b>3.</b></th><th colspan='3'><b>Memberikan kontribusi untuk hasil asesmen</b></th></tr>
							<tr><th><b></b></th><th>Temuan-temuan validasi :</th><th colspan='2'>Rekomendasi-rekomendasi untuk meningkatkan praktek asesmen</th></tr>";
																									$nomkv = 1;
																									$sqlgettemuanmkva = "SELECT * FROM `mkva_temuan` WHERE `id_jadwal`='$_GET[idj]'";
																									$gettemuanmkva = $conn->query($sqlgettemuanmkva);
																									while ($tmkv = $gettemuanmkva->fetch_assoc()) {
																										echo "<tr><td>$nomkv</td><td>$tmkv[temuan]</td><td>$tmkv[rekomendasi]</td><td>
								<input type='hidden' name='iddeltemuan' value='$tmkv[id]'><input type='submit' name='hapustemuan' class='btn btn-danger btn-xs' title='Hapus' value='Hapus'></form></td></tr>";
																										$nomkv++;
																									}
																									echo "<tr><th><b></b></th><th><textarea name='temuan' class='form-control'></textarea></th><th><textarea name='rekomendasi' class='form-control'></textarea></td><td><input type='submit' name='tambahtemuan' class='btn btn-primary' value='Tambah'></th></tr>
						</thead>
					</table>
					<table id='example9' class='table table-bordered table-striped'>
						<thead>
							<tr bgcolor='#D8D8D8'><th><b></b></th><th colspan='4'><b>Rencana Implementasi perubahan/perbaikan pelaksanaan asesmen :</b></th></tr>
							<tr><th><b>No.</b></th><th>Kegiatan Perbaikan sesuai Rekomendasi</th><th>Waktu Penyelesaian</th><th colspan='2'>Penanggungjawab</th></tr>";
																									$nomkv2 = 1;
																									$sqlgettemuanmkva2 = "SELECT * FROM `mkva_perbaikan` WHERE `id_jadwal`='$_GET[idj]'";
																									$gettemuanmkva2 = $conn->query($sqlgettemuanmkva2);
																									while ($tmkv2 = $gettemuanmkva2->fetch_assoc()) {
																										echo "<tr><td>$nomkv2</td><td>$tmkv2[perbaikan]</td><td>$tmkv2[penyelesaian]</td><td>$tmkv2[penanggungjawab]</td><td><form name='frm' method='POST' role='form' enctype='multipart/form-data'>
								<input type='hidden' name='iddelperbaikan' value='$tmkv2[id]'><input type='submit' name='hapusperbaikan' class='btn btn-danger btn-xs' title='Hapus' value='Hapus'></form></td></tr>";
																										$nomkv2++;
																									}
																									echo "<tr><th><b></b></th><th><textarea name='perbaikan' class='form-control'></textarea></th><th><textarea name='penyelesaian' class='form-control'></textarea></th><th><textarea name='penanggungjawab' class='form-control'></textarea></th><th><input type='submit' name='tambahperbaikan' class='btn btn-primary' value='Tambah'></th></tr>
						</thead>
					</table>
					</div><!--overflow-->
				</div><!--box body-->
				<div class='box-footer'>
					<div class='col-md-4 col-sm-12 col-xs-12'>
						<a class='btn btn-danger form-control' id=reset-validate-form href='?module=form-fr-va&idj=$_GET[idj]'>Kembali</a>
					</div>
					<div class='col-md-4 col-sm-12 col-xs-12'>
						<a href='../asesor/form-fr-va.php?&idj=$_GET[idj]' class='btn btn-primary form-control'>Unduh Form FR.VA</a>
					</div>
					<div class='col-md-4 col-sm-12 col-xs-12'>
						<a class='btn btn-success form-control' id=reset-validate-form href='?module=penugasanmkva'>Selesai</a>
					</div>
				</div><!--box footer-->
				</form>
				</div><!--box-->
					</div>
					<!-- /.col -->
				</div>
				<!-- /.row -->
			</section>
			<!-- /.content -->";
																								}
																								// Bagian MAPA 1 LSP ==========================================================================================================
																								elseif ($_GET['module'] == 'mapa1') {
																									if (isset($_REQUEST['simpan'])) {
																										$sqlgetunit2 = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$_GET[idsk]' ORDER BY `id` ASC";
																										$getunit2 = $conn->query($sqlgetunit2);
																										$noun2 = 1;
																										while ($gtu2 = $getunit2->fetch_assoc()) {
																											$sqlgetkatmuk2 = "SELECT * FROM `elemen_kompetensi` WHERE `id_unitkompetensi`='$gtu2[id]' ORDER BY `id` ASC";
																											$getkatmuk2 = $conn->query($sqlgetkatmuk2);
																											$noel2 = 1;
																											while ($gmuk2 = $getkatmuk2->fetch_assoc()) {
																												$sqlgetkuk2 = "SELECT * FROM `kriteria_unjukkerja` WHERE `id_elemenkompetensi`='$gmuk2[id]' ORDER BY `id` ASC";
																												$getkuk2 = $conn->query($sqlgetkuk2);
																												$nokuk2 = 1;
																												while ($gkuk2 = $getkuk2->fetch_assoc()) {
																													$sqlgetmapa12 = "SELECT * FROM `skema_mapa1` WHERE `id_skemakkni`='$_POST[id_skema]' AND `id_unitkompetensi`='$gtu2[id]' AND `id_elemenkompetensi`='$gmuk2[id]' AND `id_kriteria`='$gkuk2[id]'";
																													$getmapa12 = $conn->query($sqlgetmapa12);
																													$gtmapa12 = $getmapa12->fetch_assoc();
																													$jumgtmapa12 = $getmapa12->num_rows;
																													$id_skemakkni = $_POST['id_skema'];
																													$id_unitkompetensi = $gtu2['id'];
																													$id_elemenkompetensi = $gmuk2['id'];
																													$id_kriteria = $gkuk2['id'];
																													$pendekatan = $_POST['kandidat'];
																													$pendekatan_ket = $_POST['kandidatket1'];
																													$tujuan = $_POST['tujuanasesmen'];
																													$tujuanket = $_POST['tujuanket'];
																													$konteks_a = $_POST['lingkungan'];
																													$konteks_b = $_POST['peluang'];
																													$konteks_c1 = $_POST['hubungan1'];
																													$konteks_c2 = $_POST['hubungan2'];
																													$konteks_c3 = $_POST['hubungan3'];
																													$konteks_d = $_POST['siapa'];
																													$konfirmasi1 = $_POST['konfirmasi1'];
																													$konfirmasi2 = $_POST['konfirmasi2'];
																													$konfirmasi3 = $_POST['konfirmasi3'];
																													$konfirmasi4 = $_POST['konfirmasi4'];
																													$konfirmasi4_ket = $_POST['konfirmasiket'];
																													$toluk1 = $_POST['tolokukur1'];
																													$toluk1_ket = $_POST['tolokukur1ket'];
																													$toluk2 = $_POST['tolokukur2'];
																													$toluk2_ket = $_POST['tolokukur2ket'];
																													$toluk3 = $_POST['tolokukur3'];
																													$toluk3_ket = $_POST['tolokukur3ket'];
																													$toluk4 = $_POST['tolokukur4'];
																													$toluk4_ket = $_POST['tolokukur4ket'];
																													$toluk5 = $_POST['tolokukur5'];
																													$toluk5_ket = $_POST['tolokukur5ket'];
																													$varketbukti = "bukti_" . $noun2 . $noel2 . $nokuk2;
																													$ket_bukti = $_POST[$varketbukti];
																													$varbuktiL = "L_" . $noun2 . $noel2 . $nokuk2;
																													$bukti_L = $_POST[$varbuktiL];
																													$varbuktiTL = "TL_" . $noun2 . $noel2 . $nokuk2;
																													$bukti_TL = $_POST[$varbuktiTL];
																													$varbuktiT = "T_" . $noun2 . $noel2 . $nokuk2;
																													$bukti_T = $_POST[$varbuktiT];
																													$varmetode1 = "met1_" . $noun2 . $noel2 . $nokuk2;
																													$metode1 = $_POST[$varmetode1];
																													$varmetode2 = "met2_" . $noun2 . $noel2 . $nokuk2;
																													$metode2 = $_POST[$varmetode2];
																													$varmetode3 = "met3_" . $noun2 . $noel2 . $nokuk2;
																													$metode3 = $_POST[$varmetode3];
																													$varmetode4 = "met4_" . $noun2 . $noel2 . $nokuk2;
																													$metode4 = $_POST[$varmetode4];
																													$varmetode5 = "met5_" . $noun2 . $noel2 . $nokuk2;
																													$metode5 = $_POST[$varmetode5];
																													$varmetode6 = "met6_" . $noun2 . $noel2 . $nokuk2;
																													$metode6 = $_POST[$varmetode6];
																													$modkon1a = $_POST['31a'];
																													$modkon1a_ket = $_POST['31a_ket'];
																													$modkon1b = $_POST['31b'];
																													$modkon1b_ket = $_POST['31b_ket'];
																													$modkon2 = $_POST['32'];
																													$modkon2_ket = $_POST['32_ket'];
																													$modkon3 = $_POST['33'];
																													$modkon3_ket = $_POST['33_ket'];
																													$modkon4 = $_POST['34'];
																													$modkon4_ket = $_POST['34_ket'];
																													$konfirm1 = $_POST['konf1'];
																													$konfirm1_ket = $_POST['nama_konf1'];
																													$konfirm2 = $_POST['konf2'];
																													$konfirm2_ket = $_POST['nama_konf2'];
																													$konfirm3 = $_POST['konf3'];
																													$konfirm3_ket = $_POST['nama_konf3'];
																													$konfirm4 = $_POST['konf4'];
																													$konfirm4_ket = $_POST['nama_konf4'];
																													if ($jumgtmapa12 == 0) {
																														$sqlskemamapa1 = "INSERT INTO `skema_mapa1`(`id_skemakkni`, `id_unitkompetensi`, `id_elemenkompetensi`, `id_kriteria`, `pendekatan`, `pendekatan_ket`, `tujuan`, `tujuanket`, `konteks_a`, `konteks_b`, `konteks_c1`, `konteks_c2`, `konteks_c3`, `konteks_d`, `konfirmasi1`, `konfirmasi2`, `konfirmasi3`, `konfirmasi4`, `konfirmasi4_ket`, `toluk1`, `toluk1_ket`, `toluk2`, `toluk2_ket`, `toluk3`, `toluk3_ket`, `toluk4`, `toluk4_ket`, `toluk5`, `toluk5_ket`, `ket_bukti`, `bukti_L`, `bukti_TL`, `bukti_T`, `metode1`, `metode2`, `metode3`, `metode4`, `metode5`, `metode6`, `modkon1a`, `modkon1a_ket`, `modkon1b`, `modkon1b_ket`, `modkon2`, `modkon2_ket`, `modkon3`, `modkon3_ket`, `modkon4`, `modkon4_ket`, `konfirm1`, `konfirm1_ket`, `konfirm2`, `konfirm2_ket`, `konfirm3`, `konfirm3_ket`, `konfirm4`, `konfirm4_ket`) VALUES ('$id_skemakkni', '$id_unitkompetensi', '$id_elemenkompetensi', '$id_kriteria', '$pendekatan', '$pendekatan_ket', '$tujuan', '$tujuanket', '$konteks_a', '$konteks_b', '$konteks_c1', '$konteks_c2', '$konteks_c3', '$konteks_d', '$konfirmasi1', '$konfirmasi2', '$konfirmasi3', '$konfirmasi4', '$konfirmasi4_ket', '$toluk1', '$toluk1_ket', '$toluk2', '$toluk2_ket', '$toluk3', '$toluk3_ket', '$toluk4', '$toluk4_ket', '$toluk5', '$toluk5_ket', '$ket_bukti', '$bukti_L', '$bukti_TL', '$bukti_T', '$metode1', '$metode2', '$metode3', '$metode4', '$metode5', '$metode6', '$modkon1a', '$modkon1a_ket', '$modkon1b', '$modkon1b_ket', '$modkon2', '$modkon2_ket', '$modkon3', '$modkon3_ket', '$modkon4', '$modkon4_ket', '$konfirm1', '$konfirm1_ket', '$konfirm2', '$konfirm2_ket', '$konfirm3', '$konfirm3_ket', '$konfirm4', '$konfirm4_ket')";
																														$conn->query($sqlskemamapa1);
																														//echo "$sqlskemamapa1<br>";
																													} else {
																														$sqlskemamapa1 = "UPDATE `skema_mapa1` SET
							`id_skemakkni`='$id_skemakkni', 
							`id_unitkompetensi`='$id_unitkompetensi', 
							`id_elemenkompetensi`='$id_elemenkompetensi', 
							`id_kriteria`='$id_kriteria', 
							`pendekatan`='$pendekatan', 
							`pendekatan_ket`='$pendekatan_ket', 
							`tujuan`='$tujuan', 
							`tujuanket`='$tujuanket', 
							`konteks_a`='$konteks_a', 
							`konteks_b`='$konteks_b', 
							`konteks_c1`='$konteks_c1', 
							`konteks_c2`='$konteks_c2', 
							`konteks_c3`='$konteks_c3', 
							`konteks_d`='$konteks_d', 
							`konfirmasi1`='$konfirmasi1', 
							`konfirmasi2`='$konfirmasi2', 
							`konfirmasi3`='$konfirmasi3', 
							`konfirmasi4`='$konfirmasi4', 
							`konfirmasi4_ket`='$konfirmasi4_ket', 
							`toluk1`='$toluk1', 
							`toluk1_ket`='$toluk1_ket', 
							`toluk2`='$toluk2', 
							`toluk2_ket`='$toluk2_ket', 
							`toluk3`='$toluk3', 
							`toluk3_ket`='$toluk3_ket', 
							`toluk4`='$toluk4', 
							`toluk4_ket`='$toluk4_ket', 
							`toluk5`='$toluk5', 
							`toluk5_ket`='$toluk5_ket', 
							`ket_bukti`='$ket_bukti', 
							`bukti_L`='$bukti_L', 
							`bukti_TL`='$bukti_TL', 
							`bukti_T`='$bukti_T', 
							`metode1`='$metode1', 
							`metode2`='$metode2', 
							`metode3`='$metode3', 
							`metode4`='$metode4', 
							`metode5`='$metode5', 
							`metode6`='$metode6', 
							`modkon1a`='$modkon1a', 
							`modkon1a_ket`='$modkon1a_ket', 
							`modkon1b`='$modkon1b', 
							`modkon1b_ket`='$modkon1b_ket', 
							`modkon2`='$modkon2', 
							`modkon2_ket`='$modkon2_ket', 
							`modkon3`='$modkon3', 
							`modkon3_ket`='$modkon3_ket', 
							`modkon4`='$modkon4', 
							`modkon4_ket`='$modkon4_ket', 
							`konfirm1`='$konfirm1', 
							`konfirm1_ket`='$konfirm1_ket', 
							`konfirm2`='$konfirm2', 
							`konfirm2_ket`='$konfirm2_ket', 
							`konfirm3`='$konfirm3', 
							`konfirm3_ket`='$konfirm3_ket', 
							`konfirm4`='$konfirm4', 
							`konfirm4_ket`='$konfirm4_ket' 
							WHERE `id`='$gtmapa12[id]'";
																														$conn->query($sqlskemamapa1);
																														//echo "$sqlskemamapa1<br>";
																													}
																													$nokuk2++;
																												}
																												$noel2++;
																											}
																											$noun2++;
																										}
																										echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> FR.MAPA.01 MERENCANAKAN AKTIVITAS DAN PROSES ASESMEN berhasil DISIMPAN</h4>
		</div>";
																									}
																									echo "
			<!-- Content Header (Page header) -->
			<section class='content-header'>
				<h1>
					Perencanaan Instrumen Skema Sertifikasi Profesi
					<small>Set Up Data</small>
				</h1>
				<ol class='breadcrumb'>
					<li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
					<li class='active'>Data Instrumen Skema Sertifikasi Profesi</li>
				</ol>
			</section>
			<!-- Main content -->
			<section class='content'>
				<div class='row'>
					<div class='col-xs-12'>
						<div class='box'>
							<div class='box-header'>
								<h3 class='box-title'>FR.MAPA.01 MERENCANAKAN AKTIVITAS DAN PROSES ASESMEN</h3>
							</div>
							<!-- /.box-header -->
							<div class='box-body'>";
																									$sqlgetskemanya = "SELECT * FROM `skema_kkni` WHERE `id`='$_GET[idsk]'";
																									$getskemanya = $conn->query($sqlgetskemanya);
																									$gskx = $getskemanya->fetch_assoc();
																									$getskemamapa1content = "SELECT * FROM `skema_mapa1` WHERE `id_skemakkni`='$_GET[idsk]'";
																									$skemamapa1content = $conn->query($getskemamapa1content);
																									$smapa1 = $skemamapa1content->fetch_assoc();
																									echo "<form name='frm' method='POST' role='form' enctype='multipart/form-data'>
				<table id='example9' class='table table-bordered table-striped'><input type='hidden' name='id_skema' value='$_GET[idsk]'/>
				<thead><tr><th rowspan='2'><b>Skema Sertifikasi</b></th><th><b>Judul Skema</b></th><th>$gskx[judul]</th></tr>
				<tr><th><b>Nomor</b></th><th>$gskx[kode_skema]</th></tr></thead>
				</table>
				<div style='overflow-x:auto;'>
				<table id='example9' class='table table-bordered table-striped'>
				<thead><tr bgcolor='#D8D8D8'><th><b>1.</b></th><th colspan='3'><b>Menentukan Pendekatan Asesmen</b></th></tr></thead>
				<tbody>
				<tr><td rowspan='7'><b>1.1.</b></td><td>Kandidat</td><td colspan='2'>
					<input type='radio' id='kandidat1' name='kandidat' value='1'";
																									if ($smapa1['pendekatan'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Hasil pelatihan dan / atau pendidikan:</label>&nbsp;<input type='text' name='kandidatket1' value='$smapa1[pendekatan_ket]' class='form-control' placeholder='nama lembaga pelatihan/pendidikan'/>
					<br/><input type='radio' id='kandidat2' name='kandidat' value='2'";
																									if ($smapa1['pendekatan'] == "2") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Pekerja berpengalaman&nbsp;</label>
					<br/><input type='radio' id='kandidat3' name='kandidat' value='3'";
																									if ($smapa1['pendekatan'] == "3") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Pelatihan / belajar mandiri&nbsp;</label>
				</td></tr>
				<tr><td>Tujuan Asesmen</td><td colspan='2'>
					<input type='radio' id='tujuanassesmen1' name='tujuanasesmen' value='1'";
																									if ($smapa1['tujuan'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Sertifikasi</label>
					<br/><input type='radio' id='tujuanassesmen2' name='tujuanasesmen' value='2'";
																									if ($smapa1['tujuan'] == "2") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Sertifikasi Ulang</label>
					<br/><input type='radio' id='tujuanassesmen3' name='tujuanasesmen' value='3'";
																									if ($smapa1['tujuan'] == "3") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Pengakuan Kompetensi Terkini (PKT)</label>
					<br/><input type='radio' id='tujuanassesmen4' name='tujuanasesmen' value='4'";
																									if ($smapa1['tujuan'] == "4") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Rekognisi Pembelajaran Lampau</label>
					<br/><input type='radio' id='tujuanassesmen5' name='tujuanasesmen' value='5'";
																									if ($smapa1['tujuan'] == "5") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Lainnya</label>&nbsp;<input type='text' name='tujuanket' value='$smapa1[tujuanket]' class='form-control'/>
				</td></tr>
				<tr><td rowspan='4'>Konteks Asesmen</td><td>Lingkungan</td><td>
					<input type='radio' id='lingkungan1' name='lingkungan' value='1'";
																									if ($smapa1['konteks_a'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Tempat kerja nyata</label>
					<br/><input type='radio' id='lingkungan2' name='lingkungan' value='2'";
																									if ($smapa1['konteks_a'] == "2") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Tempat kerja simulasi</label>
				</td></tr>
				<tr><td>Peluang untuk mengumpulkan bukti dalam sejumlah situasi</td><td>
					<input type='radio' id='peluang1' name='peluang' value='1'";
																									if ($smapa1['konteks_b'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Tersedia</label>
					<br/><input type='radio' id='peluang2' name='peluang' value='2'";
																									if ($smapa1['konteks_b'] == "2") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Terbatas</label>
				</td></tr>
				<tr><td>Hubungan antara standar kompetensi dan:</td><td>
					<input type='checkbox' id='hubungan1' name='hubungan1' value='1'";
																									if ($smapa1['konteks_c1'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Bukti untuk mendukung asesmen / RPL: </label>
					<br/><input type='checkbox' id='hubungan2' name='hubungan2' value='2'";
																									if ($smapa1['konteks_c2'] == "2") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Aktivitas kerja di tempat kerja Asesi: </label>
					<br/><input type='checkbox' id='hubungan3' name='hubungan3' value='3'";
																									if ($smapa1['konteks_c3'] == "3") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Kegiatan Pembelajaran: </label>
				</td></tr>
				<tr><td>Siapa yang melakukan asesmen / RPL</td><td><input type='radio' id='siapa1' name='siapa' value='1'";
																									if ($smapa1['konteks_d'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Lembaga Sertifikasi</label>
					<br/><input type='radio' id='siapa2' name='siapa' value='2'";
																									if ($smapa1['konteks_d'] == "2") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Organisasi Pelatihan</label>
					<br/><input type='radio' id='siapa3' name='siapa' value='3'";
																									if ($smapa1['konteks_d'] == "3") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Asesor Perusahaan</label>
				</td></tr>
				<tr><td>Konfirmasi dengan orang yang relevan</td><td colspan='2'>
					<input type='checkbox' id='konfirmasi1' name='konfirmasi1' value='1'";
																									if ($smapa1['konfirmasi1'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Manajer sertifikasi LSP</label>
					<br/><input type='checkbox' id='konfirmasi2' name='konfirmasi2' value='2'";
																									if ($smapa1['konfirmasi2'] == "2") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Master Asesor / Master Trainer / Asesor Utama Kompetensi</label>
					<br/><input type='checkbox' id='konfirmasi3' name='konfirmasi3' value='3'";
																									if ($smapa1['konfirmasi3'] == "3") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Manajer Pelatihan Lembaga Training terakreditasi / Lembaga Training terdaftar</label>
					<br/><input type='checkbox' id='konfirmasi4' name='konfirmasi4' value='4'";
																									if ($smapa1['konfirmasi4'] == "4") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Lainnya:</label>&nbsp;<input type='text' name='konfirmasiket' value='$smapa1[konfirmasi4_ket]' class='form-control'/>
				</td></tr>
				<tr><td>1.2.</td><td>Tolok Ukur Asesmen</td><td colspan='2'>
					<input type='checkbox' id='tolokukur1' name='tolokukur1' value='1'";
																									if ($smapa1['toluk1'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Standar Kompetensi: SKKNI</label>&nbsp;<input type='text' name='tolokukur1ket' value='$smapa1[toluk1_ket]' class='form-control' placeholder='bila ada, isikan nomor SKKNI/SKK/SI'/>
					<br/><input type='checkbox' id='tolokukur2' name='tolokukur2' value='2'";
																									if ($smapa1['toluk2'] == "2") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Kriteria asesmen dari kurikulum pelatihan</label>&nbsp;<input type='text' name='tolokukur2ket' value='$smapa1[toluk2_ket]' class='form-control' placeholder='bila ada, isikan acuan/judul kurikulum pelatihan'/>
					<br/><input type='checkbox' id='tolokukur3' name='tolokukur3' value='3'";
																									if ($smapa1['toluk3'] == "3") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Spesifikasi kinerja suatu perusahaan atau industri:</label>&nbsp;<input type='text' name='tolokukur3ket' value='$smapa1[toluk3_ket]' class='form-control' placeholder='bila ada, isikan acuan spesifikasi kinerja/perusahaan'/>
					<br/><input type='checkbox' id='tolokukur4' name='tolokukur4' value='4'";
																									if ($smapa1['toluk4'] == "4") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Spesifikasi Produk:</label>&nbsp;<input type='text' name='tolokukur4ket' value='$smapa1[toluk4_ket]' class='form-control' placeholder='bila ada, isikan acuan spesifikasi produk'/>
					<br/><input type='checkbox' id='tolokukur5' name='tolokukur5' value='5'";
																									if ($smapa1['toluk5'] == "5") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Pedoman khusus:</label>&nbsp;<input type='text' name='tolokukur5ket' value='$smapa1[toluk5_ket]' class='form-control' placeholder='bila ada, isikan acuan nomor/judul pedoman khusus'/>
				</td></tr>
				</tbody>
				</div>
				<div style='overflow-x:auto;'>
				<table id='example9' class='table table-bordered table-striped'>
					<thead><tr bgcolor='#D8D8D8'><th><b>2.</b></th><th><b>Mempersiapkan Rencana Asesmen </b></th></tr></thead>
				</table>
				</div>";
																									$sqlgetunit = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$_GET[idsk]' ORDER BY `id` ASC";
																									$getunit = $conn->query($sqlgetunit);
																									$noun = 1;
																									while ($gtu = $getunit->fetch_assoc()) {
																										echo "<hr><div style='overflow-x:auto;'>
							<table id='example9' class='table table-bordered table-striped'>
							<input type='hidden' name='id_skema' value='$_GET[idsk]'/>
							<input type='hidden' name='id_unitkompetensi$gtu[id]' value='$gtu[id]'/>
							<thead><tr><th rowspan='2'><b>Unit Kompetensi</b></th><th><b>Kode Unit</b></th><th>$gtu[kode_unit]</th></tr>
							<tr><th><b>Judul Unit</b></th><th>$gtu[judul]</th></tr></thead></table>
							<table id='example9' class='table table-bordered table-striped'>
							<thead bgcolor='#FFFFFF'><tr><th rowspan='2'>Kriteria Unjuk Kerja</th><th rowspan='2'>Bukti-Bukti<br/>(Kinerja, Produk, Portofolio, dan / atau Hafalan) diidentifikasi berdasarkan Kriteria Unjuk Kerja dan Pendekatan Asesmen</th><th colspan='3'>Jenis Bukti</th><th colspan='6'>Metode dan Perangkat Asesmen<br/>CL (Ceklis Observasi/ Lembar Periksa), DIT (Daftar Instruksi Terstruktur), DPL (Daftar Pertanyaan Lisan), DPT (Daftar Pertanyaan Tertulis), VP (Verifikasi Portofolio), CUP (Ceklis Ulasan Produk), PW (Pertanyaan Wawancara)</th></tr>
							<tr><th><b>L</b></th><th><b>TL</b></th><th><b>T</b></th><th width='10%'><img src='../images/mapa01-1.jpg' height='150'/></th><th width='10%'><img src='../images/mapa01-2.jpg' height='150'/></th><th width='10%'><img src='../images/mapa01-3.jpg' height='150'/></th><th width='10%'><img src='../images/mapa01-4.jpg' height='150'/></th><th width='10%'><img src='../images/mapa01-5.jpg' height='150'/></th><th width='10%'><img src='../images/mapa01-6.jpg' height='150'/></th></tr>";
																										echo "</thead><tbody>";
																										$sqlgetelemen = "SELECT * FROM `elemen_kompetensi` WHERE `id_unitkompetensi`='$gtu[id]' ORDER BY `id` ASC";
																										$getelemen = $conn->query($sqlgetelemen);
																										$noel = 1;
																										while ($gel = $getelemen->fetch_assoc()) {
																											$elemennya = "Elemen " . $noel . ". " . $gel['elemen_kompetensi'];
																											echo "<tr><td colspan='12'><b>$elemennya</b></td></tr>";
																											$sqlgetkuk = "SELECT * FROM `kriteria_unjukkerja` WHERE `id_elemenkompetensi`='$gel[id]' ORDER BY `id` ASC";
																											$getkuk = $conn->query($sqlgetkuk);
																											$nokuk = 1;
																											while ($gkuk = $getkuk->fetch_assoc()) {
																												$sqlgetskemamapa1b = "SELECT * FROM `skema_mapa1` WHERE `id_skemakkni`='$_GET[idsk]' AND `id_unitkompetensi`='$gtu[id]' AND `id_elemenkompetensi`='$gel[id]' AND `id_kriteria`='$gkuk[id]'";
																												$getskemamapa1b = $conn->query($sqlgetskemamapa1b);
																												$smapa1b = $getskemamapa1b->fetch_assoc();
																												echo "<tr><td>$gkuk[kriteria]</td>
										<td><textarea name='bukti_$noun$noel$nokuk' class='form-control'>$smapa1b[ket_bukti]</textarea></td>
										<td><input type='checkbox' name='L_$noun$noel$nokuk' value='L'";
																												if ($smapa1b['bukti_L'] == "L") {
																													echo " checked";
																												} else {
																													echo "";
																												}
																												echo "></td>
										<td><input type='checkbox' name='TL_$noun$noel$nokuk' value='TL'";
																												if ($smapa1b['bukti_TL'] == "TL") {
																													echo " checked";
																												} else {
																													echo "";
																												}
																												echo "></td>
										<td><input type='checkbox' name='T_$noun$noel$nokuk' value='T'";
																												if ($smapa1b['bukti_T'] == "T") {
																													echo " checked";
																												} else {
																													echo "";
																												}
																												echo "></td>
										<td><select name='met1_$noun$noel$nokuk' class='form-control'>
											<option value=''>-</option>
											<option value='CL'";
																												if ($smapa1b['metode1'] == "CL") {
																													echo " selected";
																												} else {
																													echo "";
																												}
																												echo ">CL (Ceklis Observasi/ Lembar Periksa)</option>
											<option value='DIT'";
																												if ($smapa1b['metode1'] == "DIT") {
																													echo " selected";
																												} else {
																													echo "";
																												}
																												echo ">DIT (Daftar Instruksi Terstruktur)</option>
											<option value='DPL'";
																												if ($smapa1b['metode1'] == "DPL") {
																													echo " selected";
																												} else {
																													echo "";
																												}
																												echo ">DPL (Daftar Pertanyaan Lisan)</option>
											<option value='DPT'";
																												if ($smapa1b['metode1'] == "DPT") {
																													echo " selected";
																												} else {
																													echo "";
																												}
																												echo ">DPT (Daftar Pertanyaan Tertulis)</option>
											<option value='VP'";
																												if ($smapa1b['metode1'] == "VP") {
																													echo " selected";
																												} else {
																													echo "";
																												}
																												echo ">VP (Verifikasi Portofolio)</option>
											<option value='CUP'";
																												if ($smapa1b['metode1'] == "CUP") {
																													echo " selected";
																												} else {
																													echo "";
																												}
																												echo ">CUP (Ceklis Ulasan Produk)</option>
											<option value='PW'";
																												if ($smapa1b['metode1'] == "PW") {
																													echo " selected";
																												} else {
																													echo "";
																												}
																												echo ">PW (Pertanyaan Wawancara)</option>
										</select></td>
										<td><select name='met2_$noun$noel$nokuk' class='form-control'>
											<option value=''>-</option>
											<option value='CL'";
																												if ($smapa1b['metode2'] == "CL") {
																													echo " selected";
																												} else {
																													echo "";
																												}
																												echo ">CL (Ceklis Observasi/ Lembar Periksa)</option>
											<option value='DIT'";
																												if ($smapa1b['metode2'] == "DIT") {
																													echo " selected";
																												} else {
																													echo "";
																												}
																												echo ">DIT (Daftar Instruksi Terstruktur)</option>
											<option value='DPL'";
																												if ($smapa1b['metode2'] == "DPL") {
																													echo " selected";
																												} else {
																													echo "";
																												}
																												echo ">DPL (Daftar Pertanyaan Lisan)</option>
											<option value='DPT'";
																												if ($smapa1b['metode2'] == "DPT") {
																													echo " selected";
																												} else {
																													echo "";
																												}
																												echo ">DPT (Daftar Pertanyaan Tertulis)</option>
											<option value='VP'";
																												if ($smapa1b['metode2'] == "VP") {
																													echo " selected";
																												} else {
																													echo "";
																												}
																												echo ">VP (Verifikasi Portofolio)</option>
											<option value='CUP'";
																												if ($smapa1b['metode2'] == "CUP") {
																													echo " selected";
																												} else {
																													echo "";
																												}
																												echo ">CUP (Ceklis Ulasan Produk)</option>
											<option value='PW'";
																												if ($smapa1b['metode2'] == "PW") {
																													echo " selected";
																												} else {
																													echo "";
																												}
																												echo ">PW (Pertanyaan Wawancara)</option>
										</select></td>
										<td><select name='met3_$noun$noel$nokuk' class='form-control'>
											<option value=''>-</option>
											<option value='CL'";
																												if ($smapa1b['metode3'] == "CL") {
																													echo " selected";
																												} else {
																													echo "";
																												}
																												echo ">CL (Ceklis Observasi/ Lembar Periksa)</option>
											<option value='DIT'";
																												if ($smapa1b['metode3'] == "DIT") {
																													echo " selected";
																												} else {
																													echo "";
																												}
																												echo ">DIT (Daftar Instruksi Terstruktur)</option>
											<option value='DPL'";
																												if ($smapa1b['metode3'] == "DPL") {
																													echo " selected";
																												} else {
																													echo "";
																												}
																												echo ">DPL (Daftar Pertanyaan Lisan)</option>
											<option value='DPT'";
																												if ($smapa1b['metode3'] == "DPT") {
																													echo " selected";
																												} else {
																													echo "";
																												}
																												echo ">DPT (Daftar Pertanyaan Tertulis)</option>
											<option value='VP'";
																												if ($smapa1b['metode3'] == "VP") {
																													echo " selected";
																												} else {
																													echo "";
																												}
																												echo ">VP (Verifikasi Portofolio)</option>
											<option value='CUP'";
																												if ($smapa1b['metode3'] == "CUP") {
																													echo " selected";
																												} else {
																													echo "";
																												}
																												echo ">CUP (Ceklis Ulasan Produk)</option>
											<option value='PW'";
																												if ($smapa1b['metode3'] == "PW") {
																													echo " selected";
																												} else {
																													echo "";
																												}
																												echo ">PW (Pertanyaan Wawancara)</option>
										</select></td>
										<td><select name='met4_$noun$noel$nokuk' class='form-control'>
											<option value=''>-</option>
											<option value='CL'";
																												if ($smapa1b['metode4'] == "CL") {
																													echo " selected";
																												} else {
																													echo "";
																												}
																												echo ">CL (Ceklis Observasi/ Lembar Periksa)</option>
											<option value='DIT'";
																												if ($smapa1b['metode4'] == "DIT") {
																													echo " selected";
																												} else {
																													echo "";
																												}
																												echo ">DIT (Daftar Instruksi Terstruktur)</option>
											<option value='DPL'";
																												if ($smapa1b['metode4'] == "DPL") {
																													echo " selected";
																												} else {
																													echo "";
																												}
																												echo ">DPL (Daftar Pertanyaan Lisan)</option>
											<option value='DPT'";
																												if ($smapa1b['metode4'] == "DPT") {
																													echo " selected";
																												} else {
																													echo "";
																												}
																												echo ">DPT (Daftar Pertanyaan Tertulis)</option>
											<option value='VP'";
																												if ($smapa1b['metode4'] == "VP") {
																													echo " selected";
																												} else {
																													echo "";
																												}
																												echo ">VP (Verifikasi Portofolio)</option>
											<option value='CUP'";
																												if ($smapa1b['metode4'] == "CUP") {
																													echo " selected";
																												} else {
																													echo "";
																												}
																												echo ">CUP (Ceklis Ulasan Produk)</option>
											<option value='PW'";
																												if ($smapa1b['metode4'] == "PW") {
																													echo " selected";
																												} else {
																													echo "";
																												}
																												echo ">PW (Pertanyaan Wawancara)</option>
										</select></td>
										<td><select name='met5_$noun$noel$nokuk' class='form-control'>
											<option value=''>-</option>
											<option value='CL'";
																												if ($smapa1b['metode5'] == "CL") {
																													echo " selected";
																												} else {
																													echo "";
																												}
																												echo ">CL (Ceklis Observasi/ Lembar Periksa)</option>
											<option value='DIT'";
																												if ($smapa1b['metode5'] == "DIT") {
																													echo " selected";
																												} else {
																													echo "";
																												}
																												echo ">DIT (Daftar Instruksi Terstruktur)</option>
											<option value='DPL'";
																												if ($smapa1b['metode5'] == "DPL") {
																													echo " selected";
																												} else {
																													echo "";
																												}
																												echo ">DPL (Daftar Pertanyaan Lisan)</option>
											<option value='DPT'";
																												if ($smapa1b['metode5'] == "DPT") {
																													echo " selected";
																												} else {
																													echo "";
																												}
																												echo ">DPT (Daftar Pertanyaan Tertulis)</option>
											<option value='VP'";
																												if ($smapa1b['metode5'] == "VP") {
																													echo " selected";
																												} else {
																													echo "";
																												}
																												echo ">VP (Verifikasi Portofolio)</option>
											<option value='CUP'";
																												if ($smapa1b['metode5'] == "CUP") {
																													echo " selected";
																												} else {
																													echo "";
																												}
																												echo ">CUP (Ceklis Ulasan Produk)</option>
											<option value='PW'";
																												if ($smapa1b['metode5'] == "PW") {
																													echo " selected";
																												} else {
																													echo "";
																												}
																												echo ">PW (Pertanyaan Wawancara)</option>
										</select></td>
										<td><select name='met6_$noun$noel$nokuk' class='form-control'>
											<option value=''>-</option>
											<option value='CL'";
																												if ($smapa1b['metode6'] == "CL") {
																													echo " selected";
																												} else {
																													echo "";
																												}
																												echo ">CL (Ceklis Observasi/ Lembar Periksa)</option>
											<option value='DIT'";
																												if ($smapa1b['metode6'] == "DIT") {
																													echo " selected";
																												} else {
																													echo "";
																												}
																												echo ">DIT (Daftar Instruksi Terstruktur)</option>
											<option value='DPL'";
																												if ($smapa1b['metode6'] == "DPL") {
																													echo " selected";
																												} else {
																													echo "";
																												}
																												echo ">DPL (Daftar Pertanyaan Lisan)</option>
											<option value='DPT'";
																												if ($smapa1b['metode6'] == "DPT") {
																													echo " selected";
																												} else {
																													echo "";
																												}
																												echo ">DPT (Daftar Pertanyaan Tertulis)</option>
											<option value='VP'";
																												if ($smapa1b['metode6'] == "VP") {
																													echo " selected";
																												} else {
																													echo "";
																												}
																												echo ">VP (Verifikasi Portofolio)</option>
											<option value='CUP'";
																												if ($smapa1b['metode6'] == "CUP") {
																													echo " selected";
																												} else {
																													echo "";
																												}
																												echo ">CUP (Ceklis Ulasan Produk)</option>
											<option value='PW'";
																												if ($smapa1b['metode6'] == "PW") {
																													echo " selected";
																												} else {
																													echo "";
																												}
																												echo ">PW (Pertanyaan Wawancara)</option>
										</select></td>
									</tr>";
																												$nokuk++;
																											}
																											$noel++;
																										}
																										echo "</tbody></table>
						</div>";
																										$noun++;
																									}
																									echo "<div style='overflow-x:auto;'>
					<table id='example9' class='table table-bordered table-striped'>
					<thead><tr bgcolor='#D8D8D8'><th><b>3.</b></th><th colspan='2'><b>Mengidentifikasi Persyaratan Modifikasi dan Kontekstualisasi:</b></th></tr></thead>
					<tbody>
						<tr><td>3.1</td><td>a. Karakteristik Kandidat:</td><td><input type='radio' id='31a1' name='31a' value='1'";
																									if ($smapa1['modkon1a'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "><label>Ada</label>&nbsp;&nbsp;&nbsp;<input type='radio' id='31a2' name='31a' value='0'";
																									if ($smapa1['modkon1a'] == "0") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "><label>Tidak Ada</label>&nbsp;karakteristik khusus Kandidat<br>Jika Ada, tuliskan <input type='text' name='31a_ket' class='form-control' value='$smapa1[modkon1a_ket]'/></td></tr>
						<tr><td></td><td>b. Kebutuhan kontekstualisasi terkait tempat kerja:</td><td><input type='radio' id='31b1' name='31b' value='1'";
																									if ($smapa1['modkon1b'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "><label>Ada</label>&nbsp;&nbsp;&nbsp;<input type='radio' id='31b2' name='31b' value='0'";
																									if ($smapa1['modkon1b'] == "0") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "><label>Tidak Ada</label>&nbsp;kebutuhan kontekstualisasi<br>Jika Ada, tuliskan <input type='text' name='31b_ket' class='form-control' value='$smapa1[modkon1b_ket]'/></td></tr>
						<tr><td>3.2</td><td>Saran yang diberikan oleh paket pelatihan atau pengembang pelatihan</td><td><input type='radio' id='321' name='32' value='1'";
																									if ($smapa1['modkon2'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "><label>Ada</label>&nbsp;&nbsp;&nbsp;<input type='radio' id='322' name='32' value='0'";
																									if ($smapa1['modkon2'] == "0") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "><label>Tidak Ada</label>&nbsp;saran<br>Jika Ada, tuliskan <input type='text' name='32_ket' class='form-control' value='$smapa1[modkon2_ket]'/></td></tr>
						<tr><td>3.3</td><td>Penyesuaian perangkat asesmen terkait kebutuhan kontekstualisasi</td><td><input type='radio' id='331' name='33' value='1'";
																									if ($smapa1['modkon3'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "><label>Ada</label>&nbsp;&nbsp;&nbsp;<input type='radio' id='332' name='33' value='0'";
																									if ($smapa1['modkon3'] == "0") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "><label>Tidak Ada</label>&nbsp;penyesuaian perangkat<br>Jika Ada, tuliskan <input type='text' name='33_ket' class='form-control' value='$smapa1[modkon3_ket]'/></td></tr>
						<tr><td>3.4</td><td>Peluang untuk kegiatan asesmen terintegrasi dan mencatat setiap perubahan yang diperlukan untuk alat asesmen</td><td><input type='radio' id='341' name='34' value='1'";
																									if ($smapa1['modkon4'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "><label>Ada</label>&nbsp;&nbsp;&nbsp;<input type='radio' id='342' name='34' value='0'";
																									if ($smapa1['modkon4'] == "0") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "><label>Tidak Ada</label>&nbsp;peluang<br>Jika Ada, tuliskan <input type='text' name='34_ket' class='form-control' value='$smapa1[modkon4_ket]'/></td></tr>
					</tbody>
				</table></div>";
																									echo "<div style='overflow-x:auto;'>
					<table id='example9' class='table table-bordered table-striped'>
					<thead><tr bgcolor='#D8D8D8'><th colspan='2'><b>Konfirmasi dengan orang yang relevan</b></th></tr></thead>
					<tbody>
						<tr><td><input type='checkbox' name='konf1' value='1'";
																									if ($smapa1['konfirm1'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Manajer sertifikasi LSP</label></td><td><input type='text' name='nama_konf1' class='form-control' placeholder='nama personil' value='$smapa1[konfirm1_ket]'/></td></tr>
						<tr><td><input type='checkbox' name='konf2' value='1'";
																									if ($smapa1['konfirm2'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Master Asesor / Master Trainer / Lead Asesor/ Asesor Utama Kompetensi</label></td><td><input type='text' name='nama_konf2' class='form-control' placeholder='nama personil' value='$smapa1[konfirm2_ket]'/></td></tr>
						<tr><td><input type='checkbox' name='konf3' value='1'";
																									if ($smapa1['konfirm3'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Manajer pelatihan Lembaga Training terakreditasi / Lembaga Training terdaftar</label></td><td><input type='text' name='nama_konf3' class='form-control' placeholder='nama personil' value='$smapa1[konfirm3_ket]'/></td></tr>
						<tr><td><input type='checkbox' name='konf4' value='1'";
																									if ($smapa1['konfirm4'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Lainnya:</label></td><td><input type='text' name='nama_konf4' class='form-control' placeholder='nama personil' value='$smapa1[konfirm4_ket]'/></td></tr>
					</tbody>
				</table>";
																									echo "</div>
				</div>
				<div class='box-footer'>
											<div class='col-md-4 col-sm-12 col-xs-12'>
						<a class='btn btn-danger form-control' id=reset-validate-form href='?module=jadwalasesmen'>Kembali</a>
					</div>
					<div class='col-md-4 col-sm-12 col-xs-12'>
						<a href='../asesor/form-mapa-01.php?idsk=$_GET[idsk]' class='btn btn-primary form-control' target='_blank'>Unduh Form MAPA-01</a>
					</div>
					<div class='col-md-4 col-sm-12 col-xs-12'>
						<button type='submit' class='btn btn-success form-control' name='simpan'>Simpan Jawaban</button>
					</div>
				</div>
								</form>
			</div>
			</div>
		</section>";
																								}
																								// Bagian 1 MAPA 1 LSP ==========================================================================================================
																								elseif ($_GET['module'] == 'mapa1a') {
																									echo "
			<!-- Content Header (Page header) -->
			<section class='content-header'>
				<h1>
					Perencanaan Instrumen Skema Sertifikasi Profesi
					<small>Set Up Data</small>
				</h1>
				<ol class='breadcrumb'>
					<li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
					<li><a href='media.php?module=jadwalasesmen'></i> Jadwal Asesmen</a></li>
					<li class='active'>Data Instrumen Skema Sertifikasi Profesi</li>
				</ol>
			</section>
			<!-- Main content -->
			<section class='content'>
				<div class='row'>
					<div class='col-xs-12'>
						<div class='box'>
							<div class='box-header'>
								<h3 class='box-title'>FR.MAPA.01 MERENCANAKAN AKTIVITAS DAN PROSES ASESMEN (Bagian 1 dari 3)</h3>
							</div><!-- /.box-header -->
				<form name='frm' action='simpanmapa1a.php' method='POST' role='form' enctype='multipart/form-data'>
				<div class='box-body'>";
																									$sqlgetskemanya = "SELECT * FROM `skema_kkni` WHERE `id`='$_GET[idsk]'";
																									$getskemanya = $conn->query($sqlgetskemanya);
																									$gskx = $getskemanya->fetch_assoc();
																									if (!empty($_GET['kand'])) {
																										$getskemamapa1content = "SELECT * FROM `skema_mapa1a` WHERE `id_skemakkni`='$_GET[idsk]' AND `profil_kandidat`='$_GET[kand]'";
																									} else {
																										$getskemamapa1content = "SELECT * FROM `skema_mapa1a` WHERE `id_skemakkni`='$_GET[idsk]'";
																									}
																									$skemamapa1content = $conn->query($getskemamapa1content);
																									$smapa1 = $skemamapa1content->fetch_assoc();
																									echo "<table id='example9' class='table table-bordered table-striped'>
					<input type='hidden' name='id_skema' value='$_GET[idsk]'/>
					<thead><tr><th rowspan='2'><b>Skema Sertifikasi</b></th><th><b>Judul Skema</b></th><th>$gskx[judul]</th></tr>
					<tr><th><b>Nomor</b></th><th>$gskx[kode_skema]</th></tr></thead>
					<tr><td><b>Profil Kandidat</b></td><td colspan='2'><select name='profil_kandidat' class='form-control'>";
																									if (!empty($_GET['kand'])) {
																										$sqlgetkandidat = "SELECT * FROM `kategori_kandidat` WHERE `kode`='$_GET[kand]' ORDER BY `kode`";
																									} else {
																										$sqlgetkandidat = "SELECT * FROM `kategori_kandidat` ORDER BY `kode`";
																									}
																									$getkandidat = $conn->query($sqlgetkandidat);
																									while ($kand = $getkandidat->fetch_assoc()) {
																										echo "<option value='$kand[kode]'>$kand[deskripsi]</option>";
																									}
																									echo "</select></td></tr>
					</table>
					<div style='overflow-x:auto;'>
					<table id='example9' class='table table-bordered table-striped'>
					<thead><tr bgcolor='#D8D8D8'><th><b>1.</b></th><th colspan='3'><b>Menentukan Pendekatan Asesmen</b></th></tr></thead>
					<tbody>
						<tr><td rowspan='7'><b>1.1.</b></td><td>Kandidat</td><td colspan='2'>
							<input type='radio' id='kandidat1' name='kandidat' value='1'";
																									if ($smapa1['pendekatan'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Hasil pelatihan dan / atau pendidikan:</label>&nbsp;<input type='text' name='kandidatket1' value='$smapa1[pendekatan_ket]' class='form-control' placeholder='nama lembaga pelatihan/pendidikan'/>
							<br/><input type='radio' id='kandidat2' name='kandidat' value='2'";
																									if ($smapa1['pendekatan'] == "2") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Pekerja berpengalaman&nbsp;</label>
							<br/><input type='radio' id='kandidat3' name='kandidat' value='3'";
																									if ($smapa1['pendekatan'] == "3") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Pelatihan / belajar mandiri&nbsp;</label>
						</td></tr>
						<tr><td>Tujuan Asesmen</td><td colspan='2'>
							<input type='radio' id='tujuanassesmen1' name='tujuanasesmen' value='1'";
																									if ($smapa1['tujuan'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Sertifikasi</label>
							<br/><input type='radio' id='tujuanassesmen2' name='tujuanasesmen' value='2'";
																									if ($smapa1['tujuan'] == "2") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Sertifikasi Ulang</label>
							<br/><input type='radio' id='tujuanassesmen3' name='tujuanasesmen' value='3'";
																									if ($smapa1['tujuan'] == "3") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Pengakuan Kompetensi Terkini (PKT)</label>
							<br/><input type='radio' id='tujuanassesmen4' name='tujuanasesmen' value='4'";
																									if ($smapa1['tujuan'] == "4") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Rekognisi Pembelajaran Lampau</label>
							<br/><input type='radio' id='tujuanassesmen5' name='tujuanasesmen' value='5'";
																									if ($smapa1['tujuan'] == "5") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Lainnya</label>&nbsp;<input type='text' name='tujuanket' value='$smapa1[tujuanket]' class='form-control'/>
						</td></tr>
						<tr><td rowspan='4'>Konteks Asesmen</td><td>Lingkungan</td><td>
							<input type='radio' id='lingkungan1' name='lingkungan' value='1'";
																									if ($smapa1['konteks_a'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Tempat kerja nyata</label>
							<br/><input type='radio' id='lingkungan2' name='lingkungan' value='2'";
																									if ($smapa1['konteks_a'] == "2") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Tempat kerja simulasi</label>
						</td></tr>
						<tr><td>Peluang untuk mengumpulkan bukti dalam sejumlah situasi</td><td>
							<input type='radio' id='peluang1' name='peluang' value='1'";
																									if ($smapa1['konteks_b'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Tersedia</label>
							<br/><input type='radio' id='peluang2' name='peluang' value='2'";
																									if ($smapa1['konteks_b'] == "2") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Terbatas</label>
						</td></tr>
						<tr><td>Hubungan antara standar kompetensi dan:</td><td>
							<input type='checkbox' id='hubungan1' name='hubungan1' value='1'";
																									if ($smapa1['konteks_c1'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Bukti untuk mendukung asesmen / RPL: </label><input type='radio' id='hubungan1a' name='hubungan1-1' value='1'";
																									if ($smapa1['konteks_c1-1'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<img src='../images/faceicon-like.jpg' width='18px'/>&nbsp;&nbsp;&nbsp;<input type='radio' id='hubungan1b' name='hubungan1-1' value='2'";
																									if ($smapa1['konteks_c1-1'] == "2") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<img src='../images/faceicon-neutral.jpg' width='18px'/>&nbsp;&nbsp;&nbsp;<input type='radio' id='hubungan1c' name='hubungan1-1' value='3'";
																									if ($smapa1['konteks_c1-1'] == "3") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<img src='../images/faceicon-dislike.jpg' width='18px'/>
							<br/><input type='checkbox' id='hubungan2' name='hubungan2' value='2'";
																									if ($smapa1['konteks_c2'] == "2") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Aktivitas kerja di tempat kerja Asesi: </label><input type='radio' id='hubungan2a' name='hubungan2-1' value='1'";
																									if ($smapa1['konteks_c2-1'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<img src='../images/faceicon-like.jpg' width='18px'/>&nbsp;&nbsp;&nbsp;<input type='radio' id='hubungan2b' name='hubungan2-1' value='2'";
																									if ($smapa1['konteks_c2-1'] == "2") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<img src='../images/faceicon-neutral.jpg' width='18px'/>&nbsp;&nbsp;&nbsp;<input type='radio' id='hubungan2c' name='hubungan2-1' value='3'";
																									if ($smapa1['konteks_c2-1'] == "3") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<img src='../images/faceicon-dislike.jpg' width='18px'/>
							<br/><input type='checkbox' id='hubungan3' name='hubungan3' value='3'";
																									if ($smapa1['konteks_c3'] == "3") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Kegiatan Pembelajaran: </label><input type='radio' id='hubungan3a' name='hubungan3-1' value='1'";
																									if ($smapa1['konteks_c3-1'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<img src='../images/faceicon-like.jpg' width='18px'/>&nbsp;&nbsp;&nbsp;<input type='radio' id='hubungan3b' name='hubungan3-1' value='2'";
																									if ($smapa1['konteks_c3-1'] == "2") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<img src='../images/faceicon-neutral.jpg' width='18px'/>&nbsp;&nbsp;&nbsp;<input type='radio' id='hubungan3c' name='hubungan3-1' value='3'";
																									if ($smapa1['konteks_c3-1'] == "3") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<img src='../images/faceicon-dislike.jpg' width='18px'/>
						</td></tr>
						<tr><td>Siapa yang melakukan asesmen / RPL</td><td><input type='radio' id='siapa1' name='siapa' value='1'";
																									if ($smapa1['konteks_d'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Lembaga Sertifikasi</label>
							<br/><input type='radio' id='siapa2' name='siapa' value='2'";
																									if ($smapa1['konteks_d'] == "2") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Organisasi Pelatihan</label>
							<br/><input type='radio' id='siapa3' name='siapa' value='3'";
																									if ($smapa1['konteks_d'] == "3") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Asesor Perusahaan</label>
						</td></tr>
						<tr><td>Konfirmasi dengan orang yang relevan</td><td colspan='2'>
							<input type='checkbox' id='konfirmasi1' name='konfirmasi1' value='1'";
																									if ($smapa1['konfirmasi1'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Manajer sertifikasi LSP</label>
							<br/><input type='checkbox' id='konfirmasi2' name='konfirmasi2' value='2'";
																									if ($smapa1['konfirmasi2'] == "2") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Master Asesor / Master Trainer / Asesor Utama Kompetensi</label>
							<br/><input type='checkbox' id='konfirmasi3' name='konfirmasi3' value='3'";
																									if ($smapa1['konfirmasi3'] == "3") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Manajer Pelatihan Lembaga Training terakreditasi / Lembaga Training terdaftar</label>
							<br/><input type='checkbox' id='konfirmasi4' name='konfirmasi4' value='4'";
																									if ($smapa1['konfirmasi4'] == "4") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Lainnya:</label>&nbsp;<input type='text' name='konfirmasiket' value='$smapa1[konfirmasi4_ket]' class='form-control'/>
						</td></tr>
						<tr><td>1.2.</td><td>Tolok Ukur Asesmen</td><td colspan='2'>
							<input type='checkbox' id='tolokukur1' name='tolokukur1' value='1'";
																									if ($smapa1['toluk1'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Standar Kompetensi: SKKNI</label>&nbsp;<input type='text' name='tolokukur1ket' value='$smapa1[toluk1_ket]' class='form-control' placeholder='bila ada, isikan nomor SKKNI/SKK/SI'/>
							<br/><input type='checkbox' id='tolokukur2' name='tolokukur2' value='2'";
																									if ($smapa1['toluk2'] == "2") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Kriteria asesmen dari kurikulum pelatihan</label>&nbsp;<input type='text' name='tolokukur2ket' value='$smapa1[toluk2_ket]' class='form-control' placeholder='bila ada, isikan acuan/judul kurikulum pelatihan'/>
							<br/><input type='checkbox' id='tolokukur3' name='tolokukur3' value='3'";
																									if ($smapa1['toluk3'] == "3") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Spesifikasi kinerja suatu perusahaan atau industri:</label>&nbsp;<input type='text' name='tolokukur3ket' value='$smapa1[toluk3_ket]' class='form-control' placeholder='bila ada, isikan acuan spesifikasi kinerja/perusahaan'/>
							<br/><input type='checkbox' id='tolokukur4' name='tolokukur4' value='4'";
																									if ($smapa1['toluk4'] == "4") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Spesifikasi Produk:</label>&nbsp;<input type='text' name='tolokukur4ket' value='$smapa1[toluk4_ket]' class='form-control' placeholder='bila ada, isikan acuan spesifikasi produk'/>
							<br/><input type='checkbox' id='tolokukur5' name='tolokukur5' value='5'";
																									if ($smapa1['toluk5'] == "5") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label>Pedoman khusus:</label>&nbsp;<input type='text' name='tolokukur5ket' value='$smapa1[toluk5_ket]' class='form-control' placeholder='bila ada, isikan acuan nomor/judul pedoman khusus'/>
						</td></tr>
					</tbody>
					</table>
				</div><!--box body-->
				<div class='box-footer'>
					<div class='col-md-4 col-sm-12 col-xs-12'>
						<a class='btn btn-danger form-control' id=reset-validate-form href='?module=jadwalasesmen'>Kembali</a>
					</div>
					<div class='col-md-4 col-sm-12 col-xs-12'>
						<a href='../asesor/form-mapa-01.php?kand=$_GET[kand]&idsk=$_GET[idsk]' class='btn btn-primary form-control' target='_blank'>Unduh Form MAPA-01</a>
					</div>
					<div class='col-md-4 col-sm-12 col-xs-12'>
						<input type='submit' class='btn btn-success form-control' name='simpan' value='Simpan & Lanjut Berikutnya'/>
					</div>
				</div><!--box footer-->
				</form>
				</div><!--box-->
			</div><!--col-->
			</div><!--row-->
		</section>";
																								}
		// Bagian 2 MAPA 1 LSP ==========================================================================================================
		elseif ($_GET['module'] == 'mapa1b') {
			if (isset($_REQUEST['simpanbagian'])) {
				// $_GET[idsk]
				$sqlgetkelunit = "SELECT * FROM kelompok_unitkompetensi WHERE `id_skema`=$_GET[idsk] ORDER BY `id` ASC";
				$getkelunit = $conn->query($sqlgetkelunit);
				$noun1 = 1;
				while($gkkeluk = $getkelunit->fetch_assoc()){
					$sqlgetunitkompetensi = "SELECT * FROM `unit_kompetensi` WHERE `id_kelompok`=$gkkeluk[id] ORDER BY `id` ASC";
					$getunitkompetensi = $conn->query($sqlgetunitkompetensi);
					$noun2 = 1;
					$id_skemakkni = $_GET['idsk'];
					while ($gkuk2 = $getunitkompetensi->fetch_assoc()) {
						$sqlgetmapa12 = "SELECT * FROM `skema_mapa1b` WHERE `id_skemakkni`=$_GET[idsk] AND `profil_kandidat`='$_POST[profil_kandidat]' AND `id_unitkompetensi`='$gkuk2[id]' ORDER BY `id` ASC";
						$getmapa12 = $conn->query($sqlgetmapa12);
						$gtmapa12 = $getmapa12->fetch_assoc();
						$jumgtmapa12 = $getmapa12->num_rows;
						$profilkandidat = $_POST['profil_kandidat'];
						$id_unitkompetensi = $gkuk2['id'];
						$id_kriteria = $gkuk2['id'];
						$varketbukti = "bukti_" . $noun1.$noun2;
						if (isset($_POST[$varketbukti])) {
							$ket_bukti = $_POST[$varketbukti];
						} else {
							$ket_bukti = "-";
						}
						$varbuktiL = "L_" .$noun1. $noun2;
						if (isset($_POST[$varbuktiL])) {
							$bukti_L = $_POST[$varbuktiL];
						} else {
							$bukti_L = "-";
						}
						$varbuktiTL = "TL_" .$noun1. $noun2;
						if (isset($_POST[$varbuktiTL])) {
							$bukti_TL = $_POST[$varbuktiTL];
						} else {
							$bukti_TL = "";
						}
						$varbuktiT = "T_" .$noun1. $noun2;
						if (isset($_POST[$varbuktiT])) {
							$bukti_T = $_POST[$varbuktiT];
						} else {
							$bukti_T = "-";
						}
						$varmetode1 = "met1_" .$noun1. $noun2;
						if (isset($_POST[$varmetode1])) {
							$metode1 = $_POST[$varmetode1];
						} else {
							$metode1 = "-";
						}
						$varmetode2 = "met2_" .$noun1. $noun2;
						if (isset($_POST[$varmetode2])) {
							$metode2 = $_POST[$varmetode2];
						} else {
							$metode2 = "-";
						}
						$varmetode3 = "met3_" .$noun1. $noun2;
						//$metode3=$_POST[$varmetode3];
						$metode3 = "-";
						$varmetode4 = "met4_" .$noun1. $noun2;
						if (isset($_POST[$varmetode4])) {
							$metode4 = $_POST[$varmetode4];
						} else {
							$metode4 = "-";
						}
						$varmetode5 = "met5_" .$noun1. $noun2;
						if (isset($_POST[$varmetode5])) {
							$metode5 = $_POST[$varmetode5];
						} else {
							$metode5 = "-";
						}
						$varmetode6 = "met6_" .$noun1. $noun2;
						if (isset($_POST[$varmetode6])) {
							$metode6 = $_POST[$varmetode6];
						} else {
							$metode6 = "-";
						}
						$varketbukti2 = "bukti2_" .$noun1. $noun2;
						if (isset($_POST[$varketbukti2])) {
							$ket_bukti2 = $_POST[$varketbukti2];
						} else {
							$ket_bukti2 = "-";
						}
						$varbuktiL2 = "L2_" .$noun1. $noun2;
						if (isset($_POST[$varbuktiL2])) {
							$bukti_L2 = $_POST[$varbuktiL2];
						} else {
							$bukti_L2 = "-";
						}
						$varbuktiTL2 = "TL2_" .$noun1. $noun2;
						if (isset($_POST[$varbuktiTL2])) {
							$bukti_TL2 = $_POST[$varbuktiTL2];
						} else {
							$bukti_TL2 = "-";
						}
						$varbuktiT2 = "T2_" .$noun1. $noun2;
						if (isset($_POST[$varbuktiT2])) {
							$bukti_T2 = $_POST[$varbuktiT2];
						} else {
							$bukti_T2 = "-";
						}
						$varmetode12 = "met12_" .$noun1. $noun2;
						//$metode12=$_POST[$varmetode12];
						$metode12 = "-";
						$varmetode22 = "met22_" .$noun1. $noun2;
						//$metode22=$_POST[$varmetode22];
						$metode22 = "-";
						$varmetode32 = "met32_" .$noun1. $noun2;
						if (isset($_POST[$varmetode32])) {
							$metode32 = $_POST[$varmetode32];
						} else {
							$metode32 = "-";
						}
						$varmetode42 = "met42_" .$noun1. $noun2;
						//$metode42=$_POST[$varmetode42];
						$metode42 = "-";
						$varmetode52 = "met52_" .$noun1. $noun2;
						//$metode52=$_POST[$varmetode52];
						$metode52 = "-";
						$varmetode62 = "met62_" .$noun1. $noun2;
						//$metode62=$_POST[$varmetode62];
						$metode62 = "-";
						if ($jumgtmapa12 == 0) {
							$sqlskemamapa1 = "INSERT INTO `skema_mapa1b`(`id_skemakkni`, `profil_kandidat`, `id_unitkompetensi`, `ket_bukti`, `ket_bukti2`, `bukti_L`, `bukti_TL`, `bukti_T`, `bukti_L2`, `bukti_TL2`, `bukti_T2`, `metode1`, `metode2`, `metode3`, `metode4`, `metode5`, `metode6`, `metode1t`, `metode2t`, `metode3t`, `metode4t`, `metode5t`, `metode6t`) VALUES ('$id_skemakkni', '$profilkandidat', '$id_unitkompetensi', '$ket_bukti', '$ket_bukti2', '$bukti_L', '$bukti_TL', '$bukti_T', '$bukti_L2', '$bukti_TL2', '$bukti_T2', '$metode1', '$metode2', '$metode3', '$metode4', '$metode5', '$metode6', '$metode12', '$metode22', '$metode32', '$metode42', '$metode52', '$metode62')";
							$conn->query($sqlskemamapa1);
						} else {
							$sqlskemamapa1 = "UPDATE `skema_mapa1b` SET
						`id_skemakkni`='$id_skemakkni', 
						`profil_kandidat`='$profilkandidat', 
						`id_unitkompetensi`='$id_unitkompetensi',
						`ket_bukti`='$ket_bukti', 
						`ket_bukti2`='$ket_bukti2', 
						`bukti_L`='$bukti_L', 
						`bukti_TL`='$bukti_TL', 
						`bukti_T`='$bukti_T', 
						`bukti_L2`='$bukti_L2', 
						`bukti_TL2`='$bukti_TL2', 
						`bukti_T2`='$bukti_T2', 
						`metode1`='$metode1', 
						`metode2`='$metode2', 
						`metode3`='$metode3', 
						`metode4`='$metode4', 
						`metode5`='$metode5', 
						`metode6`='$metode6', 
						`metode1t`='$metode12', 
						`metode2t`='$metode22', 
						`metode3t`='$metode32', 
						`metode4t`='$metode42', 
						`metode5t`='$metode52', 
						`metode6t`='$metode62' 
						WHERE `id`='$gtmapa12[id]'";
						$conn->query($sqlskemamapa1);
							}
							$noun2++;
						}
					$noun1++;
				}
							echo "<div class='alert alert-success alert-dismissible'>
							<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
							<h4><i class='icon fa fa-check'></i> DATA BERHASIL DISIMPAN</h4>
							</div>";
							}
							echo "
							<!-- Content Header (Page header) -->
							<section class='content-header'>
								<h1>
									Perencanaan Instrumen Skema Sertifikasi Profesi
									<small>Set Up Data</small>
								</h1>
								<ol class='breadcrumb'>
									<li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
									<li><a href='media.php?module=jadwalasesmen'></i> Jadwal Asesmen</a></li>
									<li class='active'>Data Instrumen Skema Sertifikasi Profesi</li>
								</ol>
							</section>
							<!-- Main content -->
							<section class='content'>
								<div class='row'>
									<div class='col-xs-12'>
										<div class='box'>
											<div class='box-header'>
												<h3 class='box-title'>FR.MAPA.01 MERENCANAKAN AKTIVITAS DAN PROSES ASESMEN (Bagian 2 dari 3)</h3>
											</div>
											<!-- /.box-header -->
											<div class='box-body'>
								<form name='frm' method='POST' role='form' enctype='multipart/form-data'>";
								$sqlgetskemanya = "SELECT * FROM `skema_kkni` WHERE `id`='$_GET[idsk]'";
								$getskemanya = $conn->query($sqlgetskemanya);
								$gskx = $getskemanya->fetch_assoc();
								if (!empty($_GET['kand'])) {
									$getskemamapa1content = "SELECT * FROM `skema_mapa1b` WHERE `id_skemakkni`='$_GET[idsk]' AND `profil_kandidat`='$_GET[kand]'";
								} else {
									$getskemamapa1content = "SELECT * FROM `skema_mapa1b` WHERE `id_skemakkni`='$_GET[idsk]'";
								}
								$skemamapa1content = $conn->query($getskemamapa1content);
								$smapa1 = $skemamapa1content->fetch_assoc();
								echo "<table id='example9' class='table table-bordered table-striped'>
								<input type='hidden' name='id_skema' value='$_GET[idsk]'/>
								<thead><tr><th rowspan='2'><b>Skema Sertifikasi</b></th><th><b>Judul Skema</b></th><th>$gskx[judul]</th></tr>
								<tr><th><b>Nomor</b></th><th>$gskx[kode_skema]</th></tr></thead>
								<tr><td><b>Profil Kandidat</b></td><td colspan='2'><select name='profil_kandidat' class='form-control'>";
								if (!empty($_GET['kand'])) {
									$sqlgetkandidat = "SELECT * FROM `kategori_kandidat` WHERE `kode`='$_GET[kand]' ORDER BY `kode`";
								} else {
									$sqlgetkandidat = "SELECT * FROM `kategori_kandidat` ORDER BY `kode`";
								}
								$getkandidat = $conn->query($sqlgetkandidat);
								while ($kand = $getkandidat->fetch_assoc()) {
									echo "<option value='$kand[kode]'>$kand[deskripsi]</option>";
								}
								echo "</select></td></tr>
								</table>
								<div style='overflow-x:auto;'>
								<table id='example9' class='table table-bordered table-striped'>
									<thead><tr bgcolor='#D8D8D8'><th><b>2.</b></th><th><b>Mempersiapkan Rencana Asesmen </b></th></tr></thead>
								</table>
								</div>";
								$sqlgetkelunit2 = "SELECT * FROM kelompok_unitkompetensi WHERE `id_skema`=$_GET[idsk] ORDER BY `id` ASC";
								$getkelunit2 = $conn->query($sqlgetkelunit2);
								$noun = 1;
								while ($gtu = $getkelunit2->fetch_assoc()) {
									$sqlunitkompetensikel="SELECT * FROM unit_kompetensi WHERE `id_kelompok`=$gtu[id] ORDER BY `id` ASC";
									$getunitkompetensikel=$conn->query($sqlunitkompetensikel);
									$countunitkompetensikel=$getunitkompetensikel->num_rows+2;
									echo "<h3><b>".$noun.". $gtu[nama_kelompok]</b></h2>
									<table id='example9' class='table table-bordered table-striped'>
										<input type='hidden' name='id_unitkompetensi$gtu[id]' value='$gtu[id]'/>
										<thead>
											<tr>
												<th style='text-align: center;padding: 100px 0;' rowspan='$countunitkompetensikel'><b>$gtu[nama_kelompok]</b></th>
											</tr>
											<tr>
												<th><b>No.</b></th>
												<th><b>Kode Unit</b></th>
												<th><b>Judul Unit</b></th>
											</tr>";
											$noukk=1;
											while($getukk = $getunitkompetensikel->fetch_assoc()){
											echo "<tr>
												<th>".$noukk++.". </th>
												<th>$getukk[kode_unit]</th>
												<th>$getukk[judul]</th>
											</tr>";
											}
										echo "</thead>
									</table>
									<table id='example9' class='table table-bordered table-striped'>
										<thead bgcolor='#FFFFFF'>
											<tr>
												<th rowspan='2'>Unit Kompetensi</th>
												<th rowspan='2'>Bukti-Bukti<br/>(Kinerja, Produk, Portofolio, dan / atau Hafalan) diidentifikasi berdasarkan Kriteria Unjuk Kerja dan Pendekatan Asesmen</th>
												<th colspan='3'>Jenis Bukti</th><th colspan='6'>Metode dan Perangkat Asesmen<br/>CL (Ceklis Observasi/ Lembar Periksa), DIT (Daftar Instruksi Terstruktur), DPL (Daftar Pertanyaan Lisan), DPT (Daftar Pertanyaan Tertulis), VP (Verifikasi Portofolio), CUP (Ceklis Ulasan Produk), PW (Pertanyaan Wawancara)</th>
											</tr>
											<tr>
												<th><b>L</b></th>
												<th><b>TL</b></th>
												<th><b>T</b></th>
												<th width='10%'><img src='../images/mapa01-1.jpg' height='150'/></th>
												<th width='10%'><img src='../images/mapa01-2.jpg' height='150'/></th>
												<th width='10%'><img src='../images/mapa01-3.jpg' height='150'/></th>
												<th width='10%'><img src='../images/mapa01-4.jpg' height='150'/></th>
												<th width='10%'><img src='../images/mapa01-5.jpg' height='150'/></th>
												<th width='10%'><img src='../images/mapa01-6.jpg' height='150'/></th>
											</tr>
									</thead><tbody>";
									// GET DATA UNIT KOMPETENSI 02
									$sqlunitkompetensikel2="SELECT * FROM unit_kompetensi WHERE `id_kelompok`=$gtu[id] ORDER BY `id` ASC";
									$getunitkompetensikel2=$conn->query($sqlunitkompetensikel2);
									$countunitkompetensikel2=$getunitkompetensikel2->num_rows+2;
									$noukk2=1;
									$noukk3=1;
									while($gukk2 = $getunitkompetensikel2->fetch_assoc()){
										if (!empty($_GET['kand'])) {
											$sqlgetskemamapa1b = "SELECT * FROM `skema_mapa1b` WHERE `id_skemakkni`='$_GET[idsk]' AND `profil_kandidat`='$_GET[kand]' AND `id_unitkompetensi`='$gukk2[id]' ORDER BY `id` ASC";
										} else {
											$sqlgetskemamapa1b = "SELECT * FROM `skema_mapa1b` WHERE `id_skemakkni`='$_GET[idsk]' AND `id_unitkompetensi`='$gukk2[id]' ORDER BY `id` ASC";
										}
										$getskemamapa1b = $conn->query($sqlgetskemamapa1b);
										$smapa1b = $getskemamapa1b->fetch_assoc();
										echo "<tr><td>".$noukk2.". $gukk2[judul]</td>
											<td><textarea name='bukti_$noun$noukk3' class='form-control'>$smapa1b[ket_bukti]</textarea></td>
											<td><input type='checkbox' name='L_$noun$noukk3' value='L'";
												if ($smapa1b['bukti_L'] == "L") {
													echo " checked";
												} else {
													echo "";
												}
												echo "></td>
												<td><input type='checkbox' name='TL_$noun$noukk3' value='TL'";
													if ($smapa1b['bukti_TL'] == "TL") {
														echo " checked";
													} else {
														echo "";
													}
													echo "></td>
												<td><input type='checkbox' name='T_$noun$noukk3' value='T'";
													if ($smapa1b['bukti_T'] == "T") {
														echo " checked";
													} else {
														echo "";
													}
													echo "></td>
												<td><select name='met1_$noun$noukk3' class='form-control'>
												<option value='-'>-</option>
												<option value='CL'";
													if ($smapa1b['metode1'] == "CL") {
														echo " selected";
													} else {
														echo "";
													}
													echo ">CL (Ceklis Observasi/ Lembar Periksa)</option>
												<option value='DIT'";
													if ($smapa1b['metode1'] == "DIT") {
														echo " selected";
													} else {
														echo "";
													}
													echo ">DIT (Daftar Instruksi Terstruktur)</option>
												<option value='DPL'";
													if ($smapa1b['metode1'] == "DPL") {
														echo " selected";
													} else {
														echo "";
													}
													echo ">DPL (Daftar Pertanyaan Lisan)</option>
												<option value='DPT'";
													if ($smapa1b['metode1'] == "DPT") {
														echo " selected";
													} else {
														echo "";
													}
													echo ">DPT (Daftar Pertanyaan Tertulis)</option>
												<option value='VP'";
													if ($smapa1b['metode1'] == "VP") {
														echo " selected";
													} else {
														echo "";
													}
													echo ">VP (Verifikasi Portofolio)</option>
												<option value='CUP'";
													if ($smapa1b['metode1'] == "CUP") {
														echo " selected";
													} else {
														echo "";
													}
													echo ">CUP (Ceklis Ulasan Produk)</option>
												<option value='PW'";
													if ($smapa1b['metode1'] == "PW") {
														echo " selected";
													} else {
														echo "";
													}
													echo ">PW (Pertanyaan Wawancara)</option>
													</select></td>
												<td><select name='met2_$noun$noukk3' class='form-control'>
													<option value='-'>-</option>
													<option value='CL'";
														if ($smapa1b['metode2'] == "CL") {
															echo " selected";
														} else {
															echo "";
														}
														echo ">CL (Ceklis Observasi/ Lembar Periksa)</option>
													<option value='DIT'";
														if ($smapa1b['metode2'] == "DIT") {
															echo " selected";
														} else {
															echo "";
														}
														echo ">DIT (Daftar Instruksi Terstruktur)</option>
													<option value='DPL'";
														if ($smapa1b['metode2'] == "DPL") {
															echo " selected";
														} else {
															echo "";
														}
														echo ">DPL (Daftar Pertanyaan Lisan)</option>
													<option value='DPT'";
														if ($smapa1b['metode2'] == "DPT") {
															echo " selected";
														} else {
															echo "";
														}
														echo ">DPT (Daftar Pertanyaan Tertulis)</option>
													<option value='VP'";
														if ($smapa1b['metode2'] == "VP") {
															echo " selected";
														} else {
															echo "";
														}
														echo ">VP (Verifikasi Portofolio)</option>
													<option value='CUP'";
														if ($smapa1b['metode2'] == "CUP") {
															echo " selected";
														} else {
															echo "";
														}
														echo ">CUP (Ceklis Ulasan Produk)</option>
													<option value='PW'";
														if ($smapa1b['metode2'] == "PW") {
															echo " selected";
														} else {
															echo "";
														}
														echo ">PW (Pertanyaan Wawancara)</option>
												</select></td>
												<td><select name='met3_$noun$noukk3' class='form-control' disabled>
													<option value='-'>-</option>
													<option value='CL'";
														if ($smapa1b['metode3'] == "CL") {
															echo " selected";
														} else {
															echo "";
														}
														echo ">CL (Ceklis Observasi/ Lembar Periksa)</option>
													<option value='DIT'";
														if ($smapa1b['metode3'] == "DIT") {
															echo " selected";
														} else {
															echo "";
														}
														echo ">DIT (Daftar Instruksi Terstruktur)</option>
													<option value='DPL'";
														if ($smapa1b['metode3'] == "DPL") {
															echo " selected";
														} else {
															echo "";
														}
														echo ">DPL (Daftar Pertanyaan Lisan)</option>
													<option value='DPT'";
														if ($smapa1b['metode3'] == "DPT") {
															echo " selected";
														} else {
															echo "";
														}
														echo ">DPT (Daftar Pertanyaan Tertulis)</option>
													<option value='VP'";
														if ($smapa1b['metode3'] == "VP") {
															echo " selected";
														} else {
															echo "";
														}
														echo ">VP (Verifikasi Portofolio)</option>
													<option value='CUP'";
														if ($smapa1b['metode3'] == "CUP") {
															echo " selected";
														} else {
															echo "";
														}
														echo ">CUP (Ceklis Ulasan Produk)</option>
													<option value='PW'";
														if ($smapa1b['metode3'] == "PW") {
															echo " selected";
														} else {
															echo "";
														}
														echo ">PW (Pertanyaan Wawancara)</option>
												</select></td>
												<td><select name='met4_$noun$noukk3' class='form-control'>
													<option value='-'>-</option>
													<option value='CL'";
														if ($smapa1b['metode4'] == "CL") {
															echo " selected";
														} else {
															echo "";
														}
														echo ">CL (Ceklis Observasi/ Lembar Periksa)</option>
													<option value='DIT'";
														if ($smapa1b['metode4'] == "DIT") {
															echo " selected";
														} else {
															echo "";
														}
														echo ">DIT (Daftar Instruksi Terstruktur)</option>
													<option value='DPL'";
														if ($smapa1b['metode4'] == "DPL") {
															echo " selected";
														} else {
															echo "";
														}
														echo ">DPL (Daftar Pertanyaan Lisan)</option>
													<option value='DPT'";
														if ($smapa1b['metode4'] == "DPT") {
															echo " selected";
														} else {
															echo "";
														}
														echo ">DPT (Daftar Pertanyaan Tertulis)</option>
													<option value='VP'";
														if ($smapa1b['metode4'] == "VP") {
															echo " selected";
														} else {
															echo "";
														}
														echo ">VP (Verifikasi Portofolio)</option>
													<option value='CUP'";
														if ($smapa1b['metode4'] == "CUP") {
															echo " selected";
														} else {
															echo "";
														}
														echo ">CUP (Ceklis Ulasan Produk)</option>
													<option value='PW'";
														if ($smapa1b['metode4'] == "PW") {
															echo " selected";
														} else {
															echo "";
														}
														echo ">PW (Pertanyaan Wawancara)</option>
													</select></td>
												<td><select name='met5_$noun$noukk3' class='form-control'>
												<option value='-'>-</option>
												<option value='CL'";
													if ($smapa1b['metode5'] == "CL") {
														echo " selected";
													} else {
														echo "";
													}
													echo ">CL (Ceklis Observasi/ Lembar Periksa)</option>
												<option value='DIT'";
													if ($smapa1b['metode5'] == "DIT") {
														echo " selected";
													} else {
														echo "";
													}
													echo ">DIT (Daftar Instruksi Terstruktur)</option>
												<option value='DPL'";
													if ($smapa1b['metode5'] == "DPL") {
														echo " selected";
													} else {
														echo "";
													}
													echo ">DPL (Daftar Pertanyaan Lisan)</option>
												<option value='DPT'";
													if ($smapa1b['metode5'] == "DPT") {
														echo " selected";
													} else {
														echo "";
													}
													echo ">DPT (Daftar Pertanyaan Tertulis)</option>
												<option value='VP'";
													if ($smapa1b['metode5'] == "VP") {
														echo " selected";
													} else {
														echo "";
													}
													echo ">VP (Verifikasi Portofolio)</option>
												<option value='CUP'";
													if ($smapa1b['metode5'] == "CUP") {
														echo " selected";
													} else {
														echo "";
													}
													echo ">CUP (Ceklis Ulasan Produk)</option>
												<option value='PW'";
													if ($smapa1b['metode5'] == "PW") {
														echo " selected";
													} else {
														echo "";
													}
													echo ">PW (Pertanyaan Wawancara)</option>
												</select></td>
												<td><select name='met6_$noun$noukk3' class='form-control'>
												<option value='-'>-</option>
												<option value='CL'";
													if ($smapa1b['metode6'] == "CL") {
														echo " selected";
													} else {
														echo "";
													}
													echo ">CL (Ceklis Observasi/ Lembar Periksa)</option>
												<option value='DIT'";
													if ($smapa1b['metode6'] == "DIT") {
														echo " selected";
													} else {
														echo "";
													}
													echo ">DIT (Daftar Instruksi Terstruktur)</option>
												<option value='DPL'";
													if ($smapa1b['metode6'] == "DPL") {
														echo " selected";
													} else {
														echo "";
													}
													echo ">DPL (Daftar Pertanyaan Lisan)</option>
												<option value='DPT'";
													if ($smapa1b['metode6'] == "DPT") {
														echo " selected";
													} else {
														echo "";
													}
													echo ">DPT (Daftar Pertanyaan Tertulis)</option>
												<option value='VP'";
													if ($smapa1b['metode6'] == "VP") {
														echo " selected";
													} else {
														echo "";
													}
													echo ">VP (Verifikasi Portofolio)</option>
												<option value='CUP'";
													if ($smapa1b['metode6'] == "CUP") {
														echo " selected";
													} else {
														echo "";
													}
													echo ">CUP (Ceklis Ulasan Produk)</option>
												<option value='PW'";
													if ($smapa1b['metode6'] == "PW") {
														echo " selected";
													} else {
														echo "";
													}
													echo ">PW (Pertanyaan Wawancara)</option>
												</select></td>
												</tr>";
												$noukk3++;
												$noukk2++;
										}
										
										echo "
										</tbody>
										</table>";
										$noun++;
									}
									echo "<div class='box-footer'>
											<div class='col-md-3 col-sm-12 col-xs-12'>
												<a class='btn btn-danger form-control' id=reset-validate-form href='?module=jadwalasesmen'>Kembali</a>
											</div>
											<div class='col-md-3 col-sm-12 col-xs-12'>
												<a href='../asesor/form-mapa-01.php?kand=$_GET[kand]&idsk=$_GET[idsk]' class='btn btn-primary form-control' target='_blank'>Unduh Form MAPA-01</a>
											</div>
											<div class='col-md-3 col-sm-12 col-xs-12'>
												<input type='submit' class='btn btn-success form-control' name='simpanbagian' value='Simpan Unit ini & Lanjut Pengisian'/>
											</div>
											<div class='col-md-3 col-sm-12 col-xs-12'>
												<input type='submit' formaction='simpanmapa1b.php' class='btn btn-info form-control' name='simpan' value='Lanjut Pengisian Berikutnya'/>
											</div>
										</div>
													</form>
								</div>
								</div>
							</section>";
						}
																								// Bagian 3 MAPA 1 LSP ==========================================================================================================
																								elseif ($_GET['module'] == 'mapa1c') {
																									echo "<!-- Content Header (Page header) -->
			<section class='content-header'>
				<h1>
					Perencanaan Instrumen Skema Sertifikasi Profesi
					<small>Set Up Data</small>
				</h1>
				<ol class='breadcrumb'>
					<li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
					<li><a href='media.php?module=jadwalasesmen'></i> Jadwal Asesmen</a></li>
					<li class='active'>Data Instrumen Skema Sertifikasi Profesi</li>
				</ol>
			</section>
			<!-- Main content -->
			<section class='content'>
				<div class='row'>
					<div class='col-xs-12'>
						<div class='box'>
							<div class='box-header'>
								<h3 class='box-title'>FR.MAPA.01 MERENCANAKAN AKTIVITAS DAN PROSES ASESMEN (Bagian 3 dari 3)</h3>
							</div>
							<!-- /.box-header -->
							<div class='box-body'>
				<form name='frm' action='simpanmapa1c.php' method='POST' role='form' enctype='multipart/form-data'>";
																									$sqlgetskemanya = "SELECT * FROM `skema_kkni` WHERE `id`='$_GET[idsk]'";
																									$getskemanya = $conn->query($sqlgetskemanya);
																									$gskx = $getskemanya->fetch_assoc();
																									if (!empty($_GET['kand'])) {
																										$getskemamapa1content = "SELECT * FROM `skema_mapa1a` WHERE `id_skemakkni`='$_GET[idsk]' AND `profil_kandidat`='$_GET[kand]'";
																									} else {
																										$getskemamapa1content = "SELECT * FROM `skema_mapa1a` WHERE `id_skemakkni`='$_GET[idsk]'";
																									}
																									$skemamapa1content = $conn->query($getskemamapa1content);
																									$smapa1 = $skemamapa1content->fetch_assoc();
																									echo "<table id='example9' class='table table-bordered table-striped'>
				<input type='hidden' name='id_skema' value='$_GET[idsk]'/>
				<thead><tr><th rowspan='2'><b>Skema Sertifikasi</b></th><th><b>Judul Skema</b></th><th>$gskx[judul]</th></tr>
				<tr><th><b>Nomor</b></th><th>$gskx[kode_skema]</th></tr></thead>
				<tr><td><b>Profil Kandidat</b></td><td colspan='2'><select name='profil_kandidat' class='form-control'>";
																									if (!empty($_GET['kand'])) {
																										$sqlgetkandidat = "SELECT * FROM `kategori_kandidat` WHERE `kode`='$_GET[kand]' ORDER BY `kode`";
																									} else {
																										$sqlgetkandidat = "SELECT * FROM `kategori_kandidat` ORDER BY `kode`";
																									}
																									$getkandidat = $conn->query($sqlgetkandidat);
																									while ($kand = $getkandidat->fetch_assoc()) {
																										echo "<option value='$kand[kode]'>$kand[deskripsi]</option>";
																									}
																									echo "</select></td></tr>
				</table>
				<div style='overflow-x:auto;'>
					<table id='example9' class='table table-bordered table-striped'>
					<thead><tr bgcolor='#D8D8D8'><th><b>3.</b></th><th colspan='2'><b>Mengidentifikasi Persyaratan Modifikasi dan Kontekstualisasi:</b></th></tr></thead>
					<tbody>
						<tr><td>3.1</td><td>a. Karakteristik Kandidat:</td><td><input type='radio' id='31a1' name='31a' value='1'";
																									if ($smapa1['modkon1a'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "><label>Ada</label>&nbsp;&nbsp;&nbsp;<input type='radio' id='31a2' name='31a' value='0'";
																									if ($smapa1['modkon1a'] == "0") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "><label>Tidak Ada</label>&nbsp;karakteristik khusus Kandidat<br>Jika Ada, tuliskan <input type='text' name='31a_ket' class='form-control' value='$smapa1[modkon1a_ket]'/></td></tr>
						<tr><td></td><td>b. Kebutuhan kontekstualisasi terkait tempat kerja:</td><td><input type='radio' id='31b1' name='31b' value='1'";
																									if ($smapa1['modkon1b'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "><label>Ada</label>&nbsp;&nbsp;&nbsp;<input type='radio' id='31b2' name='31b' value='0'";
																									if ($smapa1['modkon1b'] == "0") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "><label>Tidak Ada</label>&nbsp;kebutuhan kontekstualisasi<br>Jika Ada, tuliskan <input type='text' name='31b_ket' class='form-control' value='$smapa1[modkon1b_ket]'/></td></tr>
						<tr><td>3.2</td><td>Saran yang diberikan oleh paket pelatihan atau pengembang pelatihan</td><td><input type='radio' id='321' name='32' value='1'";
																									if ($smapa1['modkon2'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "><label>Ada</label>&nbsp;&nbsp;&nbsp;<input type='radio' id='322' name='32' value='0'";
																									if ($smapa1['modkon2'] == "0") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "><label>Tidak Ada</label>&nbsp;saran<br>Jika Ada, tuliskan <input type='text' name='32_ket' class='form-control' value='$smapa1[modkon2_ket]'/></td></tr>
						<tr><td>3.3</td><td>Penyesuaian perangkat asesmen terkait kebutuhan kontekstualisasi</td><td><input type='radio' id='331' name='33' value='1'";
																									if ($smapa1['modkon3'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "><label>Ada</label>&nbsp;&nbsp;&nbsp;<input type='radio' id='332' name='33' value='0'";
																									if ($smapa1['modkon3'] == "0") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "><label>Tidak Ada</label>&nbsp;penyesuaian perangkat<br>Jika Ada, tuliskan <input type='text' name='33_ket' class='form-control' value='$smapa1[modkon3_ket]'/></td></tr>
						<tr><td>3.4</td><td>Peluang untuk kegiatan asesmen terintegrasi dan mencatat setiap perubahan yang diperlukan untuk alat asesmen</td><td><input type='radio' id='341' name='34' value='1'";
																									if ($smapa1['modkon4'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "><label>Ada</label>&nbsp;&nbsp;&nbsp;<input type='radio' id='342' name='34' value='0'";
																									if ($smapa1['modkon4'] == "0") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo "><label>Tidak Ada</label>&nbsp;peluang<br>Jika Ada, tuliskan <input type='text' name='34_ket' class='form-control' value='$smapa1[modkon4_ket]'/></td></tr>
					</tbody>
				</table></div>";
																									echo "<div style='overflow-x:auto;'>
					<table id='example9' class='table table-bordered table-striped'>
					<thead><tr bgcolor='#D8D8D8'><th colspan='2'><b>Konfirmasi dengan orang yang relevan</b></th></tr></thead>
					<tbody>
						<tr><td><input type='checkbox' id='konf1' name='konf1' value='1'";
																									if ($smapa1['konfirm1'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label id='konf1'>Manajer sertifikasi LSP</label></td><td><input type='text' name='nama_konf1' class='form-control' placeholder='nama personil' value='$smapa1[konfirm1_ket]'/></td></tr>
						<tr><td><input type='checkbox' id='konf2' name='konf2' value='1'";
																									if ($smapa1['konfirm2'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label id='konf2'>Master Asesor / Master Trainer / Lead Asesor/ Asesor Utama Kompetensi</label></td><td><input type='text' name='nama_konf2' class='form-control' placeholder='nama personil' value='$smapa1[konfirm2_ket]'/></td></tr>
						<tr><td><input type='checkbox' id='konf3' name='konf3' value='1'";
																									if ($smapa1['konfirm3'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label id='konf3'>Manajer pelatihan Lembaga Training terakreditasi / Lembaga Training terdaftar</label></td><td><input type='text' name='nama_konf3' class='form-control' placeholder='nama personil' value='$smapa1[konfirm3_ket]'/></td></tr>
						<tr><td><input type='checkbox' id='konf4' name='konf4' value='1'";
																									if ($smapa1['konfirm4'] == "1") {
																										echo " checked";
																									} else {
																										echo "";
																									}
																									echo ">&nbsp;<label id='konf4'>Lainnya:</label></td><td><input type='text' name='nama_konf4' class='form-control' placeholder='nama personil' value='$smapa1[konfirm4_ket]'/></td></tr>
					</tbody>
				</table>";
																									echo "</div>
				</div>
				<div class='box-footer'>
											<div class='col-md-4 col-sm-12 col-xs-12'>
						<a class='btn btn-danger form-control' id=reset-validate-form href='?module=jadwalasesmen'>Kembali</a>
					</div>
					<div class='col-md-4 col-sm-12 col-xs-12'>
						<a href='../asesor/form-mapa-01.php?kand=$_GET[kand]&idsk=$_GET[idsk]' class='btn btn-primary form-control' target='_blank'>Unduh Form MAPA-01</a>
					</div>
					<div class='col-md-4 col-sm-12 col-xs-12'>
						<input type='submit' class='btn btn-success form-control' name='simpan' value='Simpan Jawaban'/>
					</div>
				</div>
								</form>
			</div>
			</div>
		</section>";
	}
	// Bagian MAPA 2 LSP ==========================================================================================================
	elseif ($_GET['module'] == 'mapa2') {
		if (isset($_REQUEST['simpan'])) {
			$sqlgetkelunit2 = "SELECT * FROM `kelompok_unitkompetensi` WHERE `id_skema`='$_POST[id_skema]' ORDER BY `id` ASC";
			$getkelunit2 = $conn->query($sqlgetkelunit2);
			while($gku1 = $getkelunit2->fetch_assoc()){
				$sqlgetunit2 = "SELECT * FROM `unit_kompetensi` WHERE `id_kelompok`='$gku1[id]' ORDER BY `id` ASC";
				$getunit2 = $conn->query($sqlgetunit2);
				while ($gtu2 = $getunit2->fetch_assoc()) {
					$sqlgetkatmuk2 = "SELECT * FROM `muk` ORDER BY `id` ASC";
					$getkatmuk2 = $conn->query($sqlgetkatmuk2);
					while ($gmuk2 = $getkatmuk2->fetch_assoc()) {
						$sqlgetmuk2 = "SELECT * FROM `skema_mapa2` WHERE `id_skema`='$_POST[id_skema]' AND `id_unitkompetensi`='$gtu2[id]' AND `id_muk`='$gmuk2[id]'";
						$getmuk2 = $conn->query($sqlgetmuk2);
						$gtmuk2 = $getmuk2->fetch_assoc();
						$jumgtmuk2 = $getmuk2->num_rows;
						$mukkandidat1 = "muk" . $gtu2['id'] . $gmuk2['id'] . "_1";
						if (empty($_POST[$mukkandidat1])) {
							$kandidat1 = '0';
						} else {
							$kandidat1 = $_POST[$mukkandidat1];
						}
						$mukkandidat2 = "muk" . $gtu2['id'] . $gmuk2['id'] . "_2";
						if (empty($_POST[$mukkandidat2])) {
							$kandidat2 = '0';
						} else {
							$kandidat2 = $_POST[$mukkandidat2];
						}
						$mukkandidat3 = "muk" . $gtu2['id'] . $gmuk2['id'] . "_3";
						if (empty($_POST[$mukkandidat3])) {
							$kandidat3 = '0';
						} else {
							$kandidat3 = $_POST[$mukkandidat3];
						}
						$mukkandidat4 = "muk" . $gtu2['id'] . $gmuk2['id'] . "_4";
						if (empty($_POST[$mukkandidat4])) {
							$kandidat4 = '0';
						} else {
							$kandidat4 = $_POST[$mukkandidat4];
						}
						$mukkandidat5 = "muk" . $gtu2['id'] . $gmuk2['id'] . "_5";
						if (empty($_POST[$mukkandidat5])) {
							$kandidat5 = '0';
						} else {
							$kandidat5 = $_POST[$mukkandidat5];
						}
						$idmuknya0 = "id_muk" . $gtu2['id'] . $gmuk2['id'];
						$idmuknya = $_POST[$idmuknya0];
						if ($jumgtmuk2 == 0) {
							$sqlskemamapa2 = "INSERT INTO `skema_mapa2`(`id_skema`, `id_unitkompetensi`, `id_muk`, `kandidat1`, `kandidat2`, `kandidat3`, `kandidat4`, `kandidat5`) VALUES ('$_POST[id_skema]','$gtu2[id]','$idmuknya','$kandidat1','$kandidat2','$kandidat3','$kandidat4','$kandidat5')";
							$conn->query($sqlskemamapa2);
						} else {
							$sqlskemamapa2 = "UPDATE `skema_mapa2` SET `kandidat1`='$kandidat1',`kandidat2`='$kandidat2',`kandidat3`='$kandidat3',`kandidat4`='$kandidat4',`kandidat5`='$kandidat5' WHERE `id_skema`='$_POST[id_skema]' AND `id_unitkompetensi`='$gtu2[id]' AND `id_muk`='$idmuknya'";
							$conn->query($sqlskemamapa2);
						}
					}
				}
			}
			echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> FR.MAPA.02 PETA INSTRUMEN ASESSMEN HASIL PENDEKATAN ASESMEN DAN PERENCANAAN ASESMEN berhasil DISIMPAN</h4>
		</div>";
			}
			echo "
			<!-- Content Header (Page header) -->
			<section class='content-header'>
				<h1>
					Perencanaan Instrumen Skema Sertifikasi Profesi
					<small>Set Up Data</small>
				</h1>
				<ol class='breadcrumb'>
					<li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
					<li class='active'>Data Instrumen Skema Sertifikasi Profesi</li>
				</ol>
			</section>
			<!-- Main content -->
			<section class='content'>
				<div class='row'>
					<div class='col-xs-12'>
						<div class='box'>
							<div class='box-header'>
								<h3 class='box-title'>FR.MAPA.02 PETA INSTRUMEN ASESSMEN HASIL PENDEKATAN ASESMEN DAN PERENCANAAN ASESMEN</h3>
							</div>
							<!-- /.box-header -->
							<div class='box-body'>";
				$sqlgetskemanya = "SELECT * FROM `skema_kkni` WHERE `id`='$_GET[idsk]'";
				$getskemanya = $conn->query($sqlgetskemanya);
				$gskx = $getskemanya->fetch_assoc();
				echo "<form name='frm' method='POST' role='form' enctype='multipart/form-data'>
				<table id='example9' class='table table-bordered table-striped'>
				<thead><tr><th rowspan='2'><b>Skema Sertifikasi</b></th><th><b>Judul Skema</b></th><th>$gskx[judul]</th></tr>
				<tr><th><b>Nomor</b></th><th>$gskx[kode_skema]</th></tr></thead>
				<div style='overflow-x:auto;'>";

				$sqlgetkelunit = "SELECT * FROM `kelompok_unitkompetensi` WHERE `id_skema`='$_GET[idsk]' ORDER BY `id` ASC";
				$getkelunit = $conn->query($sqlgetkelunit);

				while ($gtku = $getkelunit->fetch_assoc()) {
					$sqlgetunit = "SELECT * FROM `unit_kompetensi` WHERE `id_kelompok`='$gtku[id]' ORDER BY `id` ASC";
					$getunit = $conn->query($sqlgetunit);
					$getunit1 = $conn->query($sqlgetunit);
					$countunit = $getunit->num_rows+1;
					// $arrunit=$getunit->fetch_assoc();
					while($gtu1 = $getunit1->fetch_assoc()){
					echo "<hr><table id='example9' class='table table-bordered table-striped'>
							<input type='hidden' name='id_skema' value='$_GET[idsk]'/>
							<thead>
								<tr>
									<th rowspan='$countunit'><b>$gtku[nama_kelompok]</b></th>
									<th><b>No.</b></th>
									<th><b>Kode Unit</b></th>
									<th><b>Judul Unit</b></th>
								</tr>";
								$noku=1;
								while($gtu = $getunit->fetch_assoc()){
									echo "<tr>
									<th><b>".$noku++."</b></th>
									<th>$gtu[kode_unit]</th>
									<th>$gtu[judul]</th>
									</tr>";
								}
								echo "</thead>
							</table>";
							echo "<table id='example9' class='table table-bordered table-striped'>
							<thead><tr><th rowspan='2'>No.</th><th rowspan='2'>MUK</th><th colspan='5'>Potensi Asesi**</th></tr>
							<tr><th>1</th><th>2</th><th>3</th><th>4</th><th>5</th>";
								echo "</tr></thead><tbody>";
								$sqlgetkatmuk = "SELECT * FROM `muk` ORDER BY `id` ASC";
								$getkatmuk = $conn->query($sqlgetkatmuk);
								while ($gmuk = $getkatmuk->fetch_assoc()) {
									$sqlgetmuk = "SELECT * FROM `skema_mapa2` WHERE `id_skema`='$_GET[idsk]' AND `id_unitkompetensi`='$gtu1[id]' AND `id_muk`='$gmuk[id]'";
									$getmuk = $conn->query($sqlgetmuk);
									$gtmuk = $getmuk->fetch_assoc();
									echo "<tr><td>$gmuk[id]<input type='hidden' name='id_muk$gtu1[id]$gmuk[id]' value='$gmuk[id]'/></td><td>$gmuk[judul] ID MUK : $gtu1[id]$gmuk[id] MUK : $gtu1[id]$gmuk[id]_1</td>
								<td><input type='checkbox' name='muk$gtu1[id]$gmuk[id]_1' id='options1$gtu1[id]$gmuk[id]' value='1'";
									if ($gtmuk['kandidat1'] == "1") {
										echo " checked";
									}
									echo "></td>
								<td><input type='checkbox' name='muk$gtu1[id]$gmuk[id]_2' id='options2$gtu1[id]$gmuk[id]' value='1'";
									if ($gtmuk['kandidat2'] == "1") {
										echo " checked";
									}
									echo "></td>
								<td><input type='checkbox' name='muk$gtu1[id]$gmuk[id]_3' id='options3$gtu1[id]$gmuk[id]' value='1'";
									if ($gtmuk['kandidat3'] == "1") {
										echo " checked";
									}
									echo "></td>
								<td><input type='checkbox' name='muk$gtu1[id]$gmuk[id]_4' id='options4$gtu1[id]$gmuk[id]' value='1'";
									if ($gtmuk['kandidat4'] == "1") {
										echo " checked";
									}
									echo "></td>
								<td><input type='checkbox' name='muk$gtu1[id]$gmuk[id]_5' id='options5$gtu1[id]$gmuk[id]' value='1'";
									if ($gtmuk['kandidat5'] == "1") {
										echo " checked";
									}
									echo "></td></tr>";
							}
								echo "</tbody></table>";
								$sqlgetkatkandidat = "SELECT * FROM `kategori_kandidat` ORDER BY `kode` ASC";
								$getkatkandidat = $conn->query($sqlgetkatkandidat);
								// 0
								echo "</ol>";
							}
							}
							echo "</div>
				</div>
				<div class='box-footer'>
											<div class='col-md-4 col-sm-12 col-xs-12'>
						<a class='btn btn-danger form-control' id=reset-validate-form href='?module=jadwalasesmen'>Kembali</a>
					</div>
					<div class='col-md-4 col-sm-12 col-xs-12'>
						<a href='../asesor/form-mapa-02.php?idsk=$_GET[idsk]' target='_blank' class='btn btn-primary form-control' target='_blank'>Unduh Form MAPA-02</a>
					</div> 
					<div class='col-md-4 col-sm-12 col-xs-12'>
						<button type='submit' class='btn btn-success form-control' name='simpan'>Simpan Jawaban</button>
					</div>
				</div>
								</form>
			</div>
			</div>
		</section>";
																								}
																								// Apabila modul tidak ditemukan =============================================================================================
																								else {
																									echo "<p><b>MODUL BELUM ADA ATAU BELUM LENGKAP</b></p>";
																								}
					?>
					<!-- CK Editor -->
					<script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>
					<!-- Bootstrap WYSIHTML5 -->
					<script src="../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
					<script>
						$(function() {
							// Replace the <textarea id="editor1"> with a CKEditor
							// instance, using default configuration.
							CKEDITOR.replace('editor1');
							//bootstrap WYSIHTML5 - text editor
							$(".textarea").wysihtml5();
						});
					</script>