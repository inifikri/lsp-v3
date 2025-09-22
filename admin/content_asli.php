<?php
include "config/koneksi.php";
include "config/library.php";
include "config/fungsi_indotgl.php";
include "config/fungsi_combobox.php";
include "config/class_paging.php";
include "config/fungsi_rupiah.php";
include "classes/class.phpmailer.php";
//include "config/fungsi_thumb.php";
ini_set('display_errors', 0);
//error_reporting(E_ALL);
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
	$sqlasesi = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_SESSION[namauser]'";
	$getasesi = $conn->query($sqlasesi);
	$as = $getasesi->fetch_assoc();
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
	<h3>Selamat Datang $as[nama]</h3>
	<p>Terimakasih telah bergabung menjadi Asesi dengan Lembaga Sertifikasi Profesi $iden[nama_lsp]. Untuk mengikuti ujian sertifikasi profesi keahlian, Prosedur yang harus dilalui adalah sebagai berikut.</p>
      <!-- Info boxes -->
      <div class='row'>
        <div class='col-lg-3 col-xs-12'>
          <!-- small box -->
          <div class='small-box bg-aqua'>
            <div class='inner'>
              <h3>1</h3>
              <p>Pilih Skema</p>
            </div>
            <div class='icon'>
              <i class='glyphicon glyphicon-th'></i>
            </div>
            <a href='?module=skema' class='small-box-footer'>Selengkapnya <i class='fa fa-arrow-circle-right'></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class='col-lg-3 col-xs-12'>
          <!-- small box -->
          <div class='small-box bg-teal'>
            <div class='inner'>
              <h3>2<sup style='font-size: 20px'></sup></h3>
              <p>Unggah Dokumen Persyaratan</p>
            </div>
            <div class='icon'>
              <i class='glyphicon glyphicon-open-file'></i>
            </div>
            <a href='?module=unggahsyarat' class='small-box-footer'>Selengkapnya <i class='fa fa-arrow-circle-right'></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class='col-lg-3 col-xs-12'>
          <!-- small box -->
          <div class='small-box bg-yellow'>
            <div class='inner'>
              <h3>3</h3>
              <p>Pembayaran Biaya</p>
            </div>
            <div class='icon'>
              <i class='glyphicon glyphicon-usd'></i>
            </div>
            <a href='?module=biaya' class='small-box-footer'>Selengkapnya <i class='fa fa-arrow-circle-right'></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class='col-lg-3 col-xs-12'>
          <!-- small box -->
          <div class='small-box bg-orange'>
            <div class='inner'>
              <h3>4</h3>
              <p>Konfirmasi Pembayaran</p>
            </div>
            <div class='icon'>
              <i class='ion ion-pie-graph'></i>
            </div>
            <a href='?module=konfpay' class='small-box-footer'>Selengkapnya <i class='fa fa-arrow-circle-right'></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->
<div class='row'>
        <div class='col-lg-3 col-xs-12'>
          <!-- small box -->
          <div class='small-box bg-red'>
            <div class='inner'>
              <h3>5</h3>
              <p>Tunggu SMS Notifikasi</p>
            </div>
            <div class='icon'>
              <i class='glyphicon glyphicon-phone'></i>
            </div>
            <a href='?module=sms' class='small-box-footer'>Selengkapnya <i class='fa fa-arrow-circle-right'></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class='col-lg-3 col-xs-12'>
          <!-- small box -->
          <div class='small-box bg-purple'>
            <div class='inner'>
              <h3>6<sup style='font-size: 20px'></sup></h3>
              <p>Lihat Jadwal</p>
            </div>
            <div class='icon'>
              <i class='glyphicon glyphicon-calendar'></i>
            </div>
            <a href='?module=jadwal' class='small-box-footer'>Selengkapnya <i class='fa fa-arrow-circle-right'></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class='col-lg-3 col-xs-12'>
          <!-- small box -->
          <div class='small-box bg-gray'>
            <div class='inner'>
              <h3>7</h3>
              <p>Ujian Sertifikasi</p>
            </div>
            <div class='icon'>
              <i class='glyphicon glyphicon-edit'></i>
            </div>
            <a href='?module=asesmen' class='small-box-footer'>Selengkapnya <i class='fa fa-arrow-circle-right'></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class='col-lg-3 col-xs-12'>
          <!-- small box -->
          <div class='small-box bg-green'>
            <div class='inner'>
              <h3>8</h3>
              <p>Terbit Sertifikat Kompetensi</p>
            </div>
            <div class='icon'>
              <i class='glyphicon glyphicon-check'></i>
            </div>
            <a href='?module=sertifikat' class='small-box-footer'>Selengkapnya <i class='fa fa-arrow-circle-right'></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->
            </div>
            <!-- ./box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
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
}
// Bagian Skema LSP ==========================================================================================================
elseif ($_GET['module'] == 'skema') {
	if (isset($_REQUEST['daftarskema'])) {
		$kode_skema = $_POST['kodeskema'];
		$judul = $_POST['namaskema'];
		$id_skkni = $_POST['skknilsp'];
		$cekdu = "SELECT * FROM `skema_kkni` WHERE `kode_skema`='$kode_skema' AND `id_skkni`='$id_skkni'";
		$result = $conn->query($cekdu);
		if ($result->num_rows == 0) {
			$conn->query("INSERT INTO `skema_kkni`(`kode_skema`, `judul`, `id_skkni`) VALUES ('$kode_skema', '$judul', '$id_skkni')");
			echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Input Data Sukses</h4>
			Anda Telah Berhasil Input Data <b>Skema $nama</b></div>";
		} else {
			echo "<script>alert('Maaf Skema dengan Kode tersebut Sudah Ada');</script>";
		}
	}
	if (isset($_REQUEST['hapusskema'])) {
		$cekdu = "SELECT * FROM `skema_kkni` WHERE `id`='$_POST[iddellsp]'";
		$result = $conn->query($cekdu);
		if ($result->num_rows != 0) { 
			$conn->query("DELETE FROM `skema_kkni` WHERE `id`='$_POST[iddellsp]'");
			echo "<div class='alert alert-danger alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Hapus Data Sukses</h4>
			Anda Telah Berhasil Menghapus Data <b>Skema Sertifikasi</b></div>";
		} else {
			echo "<script>alert('Maaf Skema dengan Kode tersebut Tidak Ditemukan');</script>";
		}
	}
	echo "
    <!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
        Skema Sertifikasi Profesi
        <small>Input Data</small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li class='active'>Data Skema Sertifikasi Profesi</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class='content'>
      <div class='row'>
        <div class='col-xs-12'>
          <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Data Skema Sertifikasi Profesi Tersedia</h3>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>
			<div style='overflow-x:auto;'>
				<table id='example1' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Kode Skema</th><th>Deskripsi Skema Sertifikasi</th><th>Biaya</th></tr></thead>
					<tbody>";
	$no = 1;
	$sqllsp = "SELECT * FROM `skema_kkni` WHERE `aktif`='Y' ORDER BY `id` ASC, `kode_skema` ASC";
	$lsp = $conn->query($sqllsp);
	while ($pm = $lsp->fetch_assoc()) {
		echo "<tr class=gradeX><td>$no</td><td><b>$pm[kode_skema]</b></td><td>$pm[judul]";
		//$sqlgetskkni=$conn->query("SELECT * FROM `skkni` WHERE `id`='$pm[id_skkni]'");
		//$ns=$sqlgetskkni->fetch_assoc();
		//echo "<td>$ns[nama]</td>";
		$sqlgetsyarat = "SELECT * FROM `skema_persyaratan` WHERE `id_skemakkni`='$pm[id]'";
		$getsyarat = $conn->query($sqlgetsyarat);
		$numsyarat = $getsyarat->num_rows;
		if ($numsyarat > 0) {
			//	echo "<br><a class='btn btn-default btn-xs' disabled>Lihat Detail</a>";
			//}else{
			echo "<br><b>( $numsyarat ) Persyaratan</b>";
		}
		$sqlgetsyaratu = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$pm[id]'";
		$getsyaratu = $conn->query($sqlgetsyaratu);
		$numsyaratu = $getsyaratu->num_rows;
		if ($numsyaratu > 0) {
			//	echo "<br><a class='btn btn-default btn-xs' disabled>Lihat Detail</a>";
			//}else{
			echo "<br><b>( $numsyaratu ) Unit Kompetensi</b><br><a href='?module=syarat&id=$pm[id]&ida=$_SESSION[namauser]' class='btn btn-success btn-xs'>Lihat Detail</a>";
		}
		echo "</td><td>";
		$sqlbiaya = "SELECT SUM(`nominal`) AS `jumnominal` FROM `biaya_sertifikasi` WHERE `id_skemakkni`='$pm[id]'";
		$biayanya = $conn->query($sqlbiaya);
		while ($bys = $biayanya->fetch_assoc()) {
			$tampilbiaya = "Rp. " . number_format($bys['jumnominal'], 0, ",", ".");
			echo "<b>$tampilbiaya</b>";
		}
		echo "</td></tr>";
		$no++;
	}
	echo "</tbody></table>
			</div>
	</div>
	</div>
    </section>";
}
// Bagian Input Syarat Skema LSP ==========================================================================================================
elseif ($_GET['module'] == 'syarat') {
	// Update @FHM 24 Juli 2023 : Cek data asesmen
	$cekasesiasesmen = $conn->query("SELECT * FROM asesi_asesmen WHERE id_asesi='$_SESSION[namauser]' AND id_skemakkni='$_GET[id]'")->fetch_assoc();

	// UPDATE @FHM-PPM 31 JULY 2023 : Query manggil data skema persyaratan
	$sqllsp = "SELECT * FROM `skema_persyaratan` WHERE `id_skemakkni`='$_GET[id]' ORDER BY `id` ASC";
	$lsp = $conn->query($sqllsp);
	$jumpsdev = $lsp->num_rows;

	// UPDATE @FHM-PPM 31 JULY 2023 : Query status dokumen asesi yang ditolak
	$qstatusdoktolak = "SELECT * FROM `asesi_doc` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_skemakkni`='$_GET[id]' AND `status`= 'R' ORDER BY `id` DESC";
	$dokasesi = $conn->query($qstatusdoktolak);
	$statusdoktolak = $dokasesi->num_rows;

	// UPDATE @FHM-PPM 31 JULY 2023 : Query manggil data dokumen asesi
	$sqlasesidoc = "SELECT * FROM `asesi_doc` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_skemakkni`='$_GET[id]' AND `status` ORDER BY `id` DESC";
	$asesidoc = $conn->query($sqlasesidoc);
	$jumpm = $asesidoc->num_rows;

	$sqlasesi = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_SESSION[namauser]'";
	$getasesi = $conn->query($sqlasesi);
	$as = $getasesi->fetch_assoc();
	$max_upload = (int)(ini_get('upload_max_filesize'));
	$max_post = (int)(ini_get('post_max_size'));
	$memory_limit = (int)(ini_get('memory_limit'));
	$upload_mb = min($max_upload, $max_post, $memory_limit);
	function uploadDoc($file)
	{
		//$file_max_weight = 20000000; //Ukuran maksimal yg dibolehkan( 20 Mb)
		$ok_ext = array('jpg', 'png', 'gif', 'jpeg', 'JPG', 'PNG', 'GIF', 'JPEG', 'pdf', 'PDF'); // ekstensi yang diijinkan
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
	if (isset($_REQUEST['tambahdocasesi'])) {
		$file = $_FILES['file'];
		// Apabila ada file yang diupload
		if (empty($_FILES['file']['tmp_name']) || !is_uploaded_file($_FILES['file']['tmp_name'])) {
			$query = "INSERT INTO `asesi_doc`(`id_asesi`, `id_skemakkni`, `skema_persyaratan`, `nama_doc`, `tahun_doc`, `nomor_doc`, `tgl_doc`) VALUES ('$_SESSION[namauser]','$_GET[id]','$_POST[kategori]','$_POST[nama_dokumen]','$_POST[tahun_dokumen]','$_POST[nomor_dokumen]','$_POST[tgl_dokumen]')";
		} else {
			$alamatfile = uploadDoc($file);
			$query = "INSERT INTO `asesi_doc`(`id_asesi`, `id_skemakkni`, `skema_persyaratan`, `nama_doc`, `tahun_doc`, `nomor_doc`, `tgl_doc`, `file`) VALUES ('$_SESSION[namauser]','$_GET[id]','$_POST[kategori]','$_POST[nama_dokumen]','$_POST[tahun_dokumen]','$_POST[nomor_dokumen]','$_POST[tgl_dokumen]','$alamatfile')";
		}
		$querycek = "SELECT * FROM `asesi_doc` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_skemakkni`='$_GET[id]' AND `skema_persyaratan`='$_POST[kategori]' AND `nama_doc`='$_POST[nama_dokumen]' AND `tahun_doc`='$_POST[tahun_dokumen]' AND `nomor_doc`='$_POST[nomor_dokumen]' AND `tgl_doc`='$_POST[tgl_dokumen]'";
		$resultc = $conn->query($querycek);
		$row_cnt = $resultc->num_rows;
		$querycekr = "SELECT * FROM `asesi_doc` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_skemakkni`='$_GET[id]' AND `skema_persyaratan`='$_POST[kategori]' AND `nama_doc`='$_POST[nama_dokumen]' AND `tahun_doc`='$_POST[tahun_dokumen]' AND `nomor_doc`='$_POST[nomor_dokumen]' AND `tgl_doc`='$_POST[tgl_dokumen]' AND `status`='R'";
		$resultcr = $conn->query($querycekr);
		$row_cntr = $resultcr->num_rows;
		if ($row_cnt == 0) {
			$conn->query($query);
			//header('location:../../media.php?module=syarat&id=$_GET[id]');
			echo "<div class='alert alert-success alert-dismissible'>
		<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
		<h4><i class='icon fa fa-check'></i> Tambah Data Sukses</h4>
		Anda Telah Berhasil Mengunggah Data <b>Persyaratan Sertifikasi</b></div>";
		} else {
			if ($row_cnt > 0) {
				$conn->query($query);
				//header('location:../../media.php?module=syarat&id=$_GET[id]');
				echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Perbaikan Data Dokumen Sukses</h4>
			Anda Telah Berhasil Mengunggah Data Perbaikan/Revisi Dokumen <b>Persyaratan Sertifikasi</b></div>";
			} else {
				echo "<script>alert('Maaf Data Tersebut Sudah Ada'); window.location = '../../media.php?module=syarat&id=$_GET[id]&ida=$_SESSION[namauser]'</script>";
			}
		}
	}
	if (isset($_REQUEST['addfromlib'])) {
		$alamatfile = $_POST['file'];
		$query = "INSERT INTO `asesi_doc`(`id_asesi`, `id_skemakkni`, `skema_persyaratan`, `nama_doc`, `tahun_doc`, `nomor_doc`, `tgl_doc`, `file`) VALUES ('$_SESSION[namauser]','$_GET[id]','$_POST[kategori]','$_POST[nama_dokumen]','$_POST[tahun_dokumen]','$_POST[nomor_dokumen]','$_POST[tgl_dokumen]','$alamatfile')";
		$querycek = "SELECT * FROM `asesi_doc` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_skemakkni`='$_GET[id]' AND `skema_persyaratan`='$_POST[kategori]' AND `nama_doc`='$_POST[nama_dokumen]' AND `tahun_doc`='$_POST[tahun_dokumen]' AND `nomor_doc`='$_POST[nomor_dokumen]' AND `tgl_doc`='$_POST[tgl_dokumen]'";
		$resultc = $conn->query($querycek);
		$row_cnt = $resultc->num_rows;
		if ($row_cnt == 0) {
			$conn->query($query);
			//header('location:../../media.php?module=syarat&id=$_GET[id]');
			echo "<div class='alert alert-success alert-dismissible'>
		<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
		<h4><i class='icon fa fa-check'></i> Tambah Data Sukses</h4>
		Anda Telah Berhasil Mengunggah Data <b>Persyaratan Sertifikasi</b></div>";
		} else {
			echo "<script>alert('Maaf Data Tersebut Sudah Ada'); window.location = '../../media.php?module=syarat&id=$_GET[id]&ida=$_SESSION[namauser]'</script>";
		}
	}
	if (isset($_REQUEST['hapusdocasesi'])) {
		$cekdu = "SELECT * FROM `asesi_doc` WHERE `id`='$_POST[iddeldocasesi]'";
		$result = $conn->query($cekdu);
		if ($result->num_rows != 0) {
			$conn->query("DELETE FROM `asesi_doc` WHERE `id`='$_POST[iddeldocasesi]'");
			echo "<div class='alert alert-danger alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Hapus Data Sukses</h4>
			Anda Telah Berhasil Menghapus Data <b>Persyaratan Sertifikasi</b></div>";
		} else {
			echo "<script>alert('Maaf Persyaratan Skema dengan tersebut Tidak Ditemukan');</script>";
		}
	}
	if (isset($_REQUEST['daftarasesmen'])) {
		$tgl_daftar = date("Y-m-d");
		if ($_POST['tujuan_sertifikasi'] == "Lainnya") {
			$querydas = "INSERT INTO `asesi_asesmen`(`tujuan_sertifikasi`, `tujuan_lainnya`, `id_asesi`, `id_skemakkni`, `tgl_daftar`,`biaya`) VALUES ('$_POST[tujuan_sertifikasi]', '$_POST[tujuan_lainnya]', '$_SESSION[namauser]','$_GET[id]','$tgl_daftar','$_POST[biaya_asesmen]')";
		} else {
			$querydas = "INSERT INTO `asesi_asesmen`(`tujuan_sertifikasi`, `id_asesi`, `id_skemakkni`, `tgl_daftar`,`biaya`) VALUES ('$_POST[tujuan_sertifikasi]', '$_SESSION[namauser]','$_GET[id]','$tgl_daftar','$_POST[biaya_asesmen]')";
		}
		$querycek = "SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_skemakkni`='$_GET[id]' AND `status_asesmen`!='K'";
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
			$sqlidentitas = "SELECT * FROM `identitas`";
			$identitas = $conn->query($sqlidentitas);
			$iden = $identitas->fetch_assoc();
			// Kirim email dalam format HTML ke Pendaftar
			// cek LSP Konstruksi
			$sqlceklspkonstruksi = "SELECT * FROM `user_pupr`";
			$ceklspkonsturksi = $conn->query($sqlceklspkonstruksi);
			$jumlspkonstruksi = $ceklspkonsturksi->num_rows;
			if ($jumlspkonstruksi > 0) {
				/* $pesan="Terimakasih telah melakukan pendaftaran Asesmen Skema $gs[kode_skema] - $gs[judul]<br /><br />  
				ID Asesi: $_SESSION[namauser] <br />
				Nama: $namanya <br />
				Nomor Handphone: $as[nohp] <br />
				Skema: $gs[kode_skema] - $gs[judul]<br />
				Biaya: $_POST[biaya_asesmen]<br />
				<br /><br />Silahkan tunggu email berikutnya berisi informasi <b>PERSETUJUAN<b> dari Admin LSP beserta <b>INVOICE (TAGIHAN)</b> dan lakukan pembayaran sejumlah Rp. $_POST[biaya_asesmen] setelah Anda menerima persetujuan dan Jadwal Asesmen. Pembayaran dapat dilakukan secara tunai atau melalui transfer ke salah satu rekening berikut:<br /><br />"; */
				$pesan = "Terimakasih telah melakukan pendaftaran Asesmen Skema $gs[kode_skema] - $gs[judul]<br /><br />  
				ID Asesi: $_SESSION[namauser] <br />
				Nama: $namanya <br />
				Nomor Handphone: $as[nohp] <br />
				Skema: $gs[kode_skema] - $gs[judul]<br />
				<br /><br />Silahkan tunggu email berikutnya berisi informasi <b>PERSETUJUAN<b> dari Admin LSP beserta <b>INVOICE (TAGIHAN)</b> dan lakukan pembayaran setelah Anda menerima persetujuan dan Jadwal Asesmen. Pembayaran dapat dilakukan secara tunai atau melalui transfer ke salah satu rekening berikut:<br /><br />";
				$sqlgetbank = "SELECT * FROM `rekeningbayar` WHERE `metode`!='Tunai' AND `aktif`='Y'";
				$getbank = $conn->query($sqlgetbank);
				while ($rek = $getbank->fetch_assoc()) {
					$pesan .= "Rekening $rek[bank] No. $rek[norek]<br>an. $rek[atasnama]<br>";
				}
				$subjek = "Terimakasih telah melakukan Pendaftaran Asesmen di $iden[nama_lsp]";
				$dari = "From: noreply@" . $urldomain . "\r\n";
				$dari .= "Content-type: text/html\r\n";
			} else {
				$pesan = "Terimakasih telah melakukan pendaftaran Asesmen Skema $gs[kode_skema] - $gs[judul]<br /><br />  
				ID Asesi: $_SESSION[namauser] <br />
				Nama: $namanya <br />
				Nomor Handphone: $as[nohp] <br />
				Skema: $gs[kode_skema] - $gs[judul]<br />
				Biaya: $_POST[biaya_asesmen]<br />
				<br /><br />Silahkan lakukan pembayaran sejumlah Rp. $_POST[biaya_asesmen]. Pembayaran dapat dilakukan secara tunai atau melalui transfer ke salah satu rekening berikut:<br /><br />";
				$sqlgetbank = "SELECT * FROM `rekeningbayar` WHERE `metode`!='Tunai' AND `aktif`='Y'";
				$getbank = $conn->query($sqlgetbank);
				while ($rek = $getbank->fetch_assoc()) {
					$pesan .= "Rekening $rek[bank] No. $rek[norek]<br>an. $rek[atasnama]<br>";
				}
				$pesan .= "Selanjutnya lakukan konfirmasi pembayaran di laman asesi<br>";
				$subjek = "Terimakasih telah melakukan Pendaftaran Asesmen di $iden[nama_lsp]";
				$dari = "From: noreply@" . $urldomain . "\r\n";
				$dari .= "Content-type: text/html\r\n";
			}
			// Kirim email ke member
			$sqlgetsmtp = "SELECT * FROM `smtp` WHERE `aktif`='Y'";
			$getsmtp = $conn->query($sqlgetsmtp);
			$gsmtp = $getsmtp->fetch_assoc();
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
			$isisms = "Yth. $namanya Pendaftaran Asesmen Skema $gs[kode_skema]-$gs[judul] berhasil, dengan biaya asesmen Rp. $_POST[biaya_asesmen]. Silahkan cek email Anda";
			if (strlen($no_hp) > 8) {
				$sqloutbox = "INSERT INTO `outbox` (`DestinationNumber`, `TextDecoded`, `Coding`,`SenderID`,`CreatorID`) VALUES ('$no_hp','$isisms','Default_No_Compression','MyPhone1','MyPhone1')";
				$outbox = $conn->query($sqloutbox);
			}
			//============================================================================
			$mod = count($_POST['ukom']);
			$modul = $_POST['ukom'];
			// clear data asesmen_unitkompetensi
			$conn->query("DELETE FROM `asesmen_unitkompetensi` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_skemakkni`='$_GET[id]'");
			for ($i = 0; $i < $mod; $i++) {
				$conn->query("INSERT INTO `asesmen_unitkompetensi`(`id_asesi`, `id_skemakkni`, `id_unitkompetensi`) VALUES ('$_SESSION[namauser]', '$_GET[id]', '$modul[$i]')");
			}
			//=====================update data usia=========================================================
			$sqlkalkulasiusia = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_SESSION[namauser]'";
			$kalkulasiusia = $conn->query($sqlkalkulasiusia);
			$kus = $kalkulasiusia->fetch_assoc();
			$tgl_lahir = $kus['tgl_lahir'];
			// ubah ke format Ke Date Time
			$lahir = new DateTime($tgl_lahir);
			$hari_ini = new DateTime();
			$diff = $hari_ini->diff($lahir);
			$usia = $diff->y;
			$sqlupdateusia = "UPDATE `asesi` SET `usia`='$usia' WHERE `no_pendaftaran`='$_SESSION[namauser]'";
			$updateusia = $conn->query($sqlupdateusia);
			//---------------------------------------------------		
			$folderPath = "foto_tandatangan/";
			if (empty($_POST['signed'])) {
				echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Pendaftaran Asesmen Sukses</h4>
			Anda Telah Berhasil mendaftarkan diri pada skema ini<b></b></div>";
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
				$sqlinputdigisign = "INSERT INTO `logdigisign`(`id_dokumen`, `id_asesi`, `id_skema`, `url_ditandatangani`, `nama_dokumen`, `penandatangan`, `file`, `ip`) VALUES ('$iddokumen','$_SESSION[namauser]','$_GET[id]'	,'$escaped_url','FR-APL-01. FORMULIR PERMOHONAN SERTIFIKASI KOMPETENSI','$rowAgen[nama]','$file','$alamatip')";
				$conn->query($sqlinputdigisign);
				echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Pendaftaran Asesmen Sukses</h4>
			Anda Telah Berhasil mendaftarkan diri pada skema ini<b></b><br><b>Tanda Tangan Sukses Diupload</b></div>";
			}
		} else {
			echo "<script>alert('Maaf Anda sudah terdaftar pada skema ini'); window.location = '../../media.php?module=syarat&id=$_GET[id]&ida=$_SESSION[namauser]'</script>";
		}
	}
	$sqlgetskema = "SELECT * FROM `skema_kkni` WHERE `id`='$_GET[id]'";
	$getskema = $conn->query($sqlgetskema);
	$gs = $getskema->fetch_assoc();
	echo "
    <!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
        Persyaratan Uji Kompetensi
        <small>Input Data</small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li><a href='media.php?module=skema'><i class='fa fa fa-edit'></i> Skema Sertifikasi</a></li>
        <li class='active'>Data Persyaratan</li>
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
			<h2>$gs[judul] <b>($gs[kode_skema])</b></h2>
			<div style='overflow-x:auto;'>
			<table id='example' class='table table-bordered table-striped'>
				<thead><tr><th>No</th><th>Persyaratan</th></tr></thead>
				<tbody>";
	$no = 1;
	$sqllsp = "SELECT * FROM `skema_persyaratan` WHERE `id_skemakkni`='$gs[id]' ORDER BY `id` ASC";
	$lsp = $conn->query($sqllsp);
	$jumpsdev = $lsp->num_rows;
	while ($pspm = $lsp->fetch_assoc()) {
		echo "<tr class=gradeX><td>$no</td>";
		echo "</td><td>$pspm[persyaratan]</td></tr>";
		/*echo "<td><form name='frm' method='POST' role='form' enctype='multipart/form-data'>
						<input type='hidden' name='iddelsy' value='$pm[id]'><input type='submit' name='hapussy' class='btn btn-danger btn-xs' onclick='return confirmation()' title='Hapus' value='Hapus'></form></td></tr>"; */
		$no++;
	}
	echo "</tbody>
			</table></div>
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
			<div style='overflow-x:auto;'>
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
			</table></div>
		</div>
	  </div>
	</div>";
	if ($pupr > 0) {
		echo "<div class='panel box box-success'>
		  <div class='box-header with-border'>
			<h4 class='box-title'>
			  <a data-toggle='collapse' data-parent='#accordion' href='#collapseThree'>
				Daftar Unit Kompetensi sesuai kemasan Skema
			  </a>
			</h4>
		  </div>
		  <div id='collapseThree' class='panel-collapse collapse in'>
			<div class='box-body'>
				<div style='overflow-x:auto;'>
					<table id='example' class='table table-bordered table-striped'>
						<thead><tr><th>No</th><th>Kode Unit</th><th>Judul Unit</th><th>Jenis Standar<br />(Standar Khusus/ Standar Internasional/ SKKNI)</th></tr></thead>
						<tbody>";
		$no = 1;
		$sqllsp = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$gs[id]' ORDER BY `id` ASC";
		$lsp = $conn->query($sqllsp);
		while ($pm = $lsp->fetch_assoc()) {
			echo "<tr class=gradeX><td>$no</td>";
			echo "</td><td><b>$pm[kode_unit]</b></td><td>$pm[judul]</td><td>";
			$sqlgetskkni = "SELECT * FROM `skkni` WHERE `id`='$pm[id_skkni]'";
			$getskkni = $conn->query($sqlgetskkni);
			$skkx = $getskkni->fetch_assoc();
			echo $skkx['no_skkni'];
			echo "</td></tr>";
			$no++;
		}
		echo "</tbody>
					</table>
				</div>
			</div>
		  </div>
		</div>";
		echo "<div class='panel box box-success'>
		  <div class='box-header with-border'>
			<h4 class='box-title'>
			  <a data-toggle='collapse' data-parent='#accordion' href='#collapseFour'>
				Pendaftaran Uji Kompetensi Skema $gs[judul]
			  </a>
			</h4>
		  </div>
		  <div id='collapseFour' class='panel-collapse collapse in'>";
	} else {
		echo "<div class='panel box box-success'>
		  <div class='box-header with-border'>
			<h4 class='box-title'>
			  <a data-toggle='collapse' data-parent='#accordion' href='#collapseThree'>
				Pendaftaran Uji Kompetensi Skema $gs[judul]
			  </a>
			</h4>
		  </div>
		  <div id='collapseThree' class='panel-collapse collapse in'>";
	}
	echo "<div class='box-body'>";
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
	if ($as['tgl_lahir'] != NULL) {
		$usia = getAge($as['tgl_lahir']);
	} else {
		$usia = 0;
	}
	if ($usia < 101) {
		$syaratusia = "<font color='green'><b>Anda telah memenuhi Persyaratan Usia</b></font>";
	} else {
		$syaratusia = "<font color='red'><b>Maaf, Anda tidak memenuhi Persyaratan Usia</b></font>";
	}
	$sqlpendidikan = "SELECT * FROM `pendidikan` WHERE `id`='$as[pendidikan]'";
	$pendidikan = $conn->query($sqlpendidikan);
	$pdas = $pendidikan->fetch_assoc();
	if ($as['pendidikan'] > 1) {
		$syaratpend = "<font color='green'><b>Anda telah memenuhi Persyaratan Pendidikan</b></font>";
	} else {
		$syaratpend = "<font color='red'><b>Maaf, Anda tidak memenuhi Persyaratan Pendidikan</b></font>";
	}
	echo "<p>Untuk mengikuti uji kompetensi pada skema ini, silahkan lengkapi persyaratan dan dokumen berikut:<br>
			Usia Anda adalah <b>$usia tahun</b>, $syaratusia<br>
			Pendidikan terakhir Anda adalah <b>$pdas[jenjang_pendidikan]</b>, $syaratpend<br></p>";
	$sqlceksywajib = "SELECT * FROM `asesi_persyaratanpokok` WHERE `wajib`='Y'";
	$ceksywajib = $conn->query($sqlceksywajib);
	$jumsyw = $ceksywajib->num_rows;
	$pointsy = 0;
	$notesy = "";
	while ($csyw = $ceksywajib->fetch_assoc()) {
		$sywajib = $csyw['shortcode'];
		if (!empty($as[$sywajib])) {
			$pointsy++;
		} else {
			$notesy = $csyw['persyaratan'] . ", " . $notesy;
		}
	}
	/* if(empty($as['foto']) or empty($as['ktp']) || empty($as['ijazah'])){
				$sydokpokok="kosong";
				echo "<div class='alert alert-warning alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-warning'></i> Maaf, Anda belum mengunggah Dokumen Pokok !</h4>
				Silahkan unggah dokumen pokok Anda (Foto, KTP, dan Ijazah Terakhir) melalui menu <b><a href='media.php?module=unggahfile'>Profil Anda</a></b></div>";
			}else{
				$sydokpokok="lengkap";
			} */
	if ($pointsy == $jumsyw) {
		$sydokpokok = "lengkap";
	} else {
		$sydokpokok = "kosong";
		echo "<div class='alert alert-warning alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-warning'></i> Maaf, Anda belum mengunggah Dokumen Pokok !</h4>
				Silahkan unggah dokumen pokok Anda ($notesy) melalui menu <b><a href='media.php?module=unggahfile'>Profil Anda</a></b></div>";
	}
	if ($pupr > 0) {
		echo "<p>Pendaftaran Uji Kompetensi dapat Anda lakukan melalui portal Perizinan PUPR<br /><a href='https://perizinan.pu.go.id/portal' class='btn btn-success btn-xs' target='_blank'>Daftar SKK Skema $gs[judul]</a></p>";
	} else {
		if ($usia < 101 && $as['pendidikan'] > 1 && $sydokpokok != 'kosong') {
			echo "<h4>Input Persyaratan Uji Kompetensi</h4>
					<span>Masukkan data persyaratan/pengalaman/portfolio Anda, beserta informasi yang dibutuhkan, kemudian klik <b><a class='btn btn-primary btn-xs'>Tambahkan</a></b></span> 
					<div class='row'>
						<div class='box-body'>
						  <div class='col-md-12'>
							<form role='form' method='POST' enctype='multipart/form-data'>
							<div class='form-group'>
								<label>Jenis Persyaratan</label>
								<select name='kategori' class='form-control' id='kategori'>
								<option value=''>--Pilih Kategori Persyaratan--</option>";
			$sqlselectkat = "SELECT * FROM `skema_persyaratan` WHERE `id_skemakkni`='$_GET[id]' ORDER BY `id` ASC";
			$tampilkat = $conn->query($sqlselectkat);
			while ($rr = $tampilkat->fetch_assoc()) {
				$sqlcekpersyaratan = "SELECT * FROM `asesi_doc` WHERE `id_asesi`='$as[no_pendaftaran]' AND `id_skemakkni`='$_GET[id]' AND `skema_persyaratan`='$rr[id]' AND `status`!='R' ORDER BY `id` DESC";
				$asesidocps = $conn->query($sqlcekpersyaratan);
				$jumpsx = $asesidocps->num_rows;
				$sqlcekpersyaratanp = "SELECT * FROM `asesi_doc` WHERE `id_asesi`='$as[no_pendaftaran]' AND `id_skemakkni`='$_GET[id]' AND `skema_persyaratan`='$rr[id]' AND `status`='R' ORDER BY `id` DESC";
				$asesidocpsp = $conn->query($sqlcekpersyaratanp);
				$jumpsxp = $asesidocpsp->num_rows;
				if ($jumpsx == 0) {
					if ($jumpsxp > 0) {
						echo "<option value='$rr[id]'>$rr[persyaratan] (perbaikan/revisi)</option>";
					} else {
						echo "<option value='$rr[id]'>$rr[persyaratan]</option>";
					}
				} else {
					echo "";
				}
			}
			echo "</select>";
			echo "</div>
						  </div>
						  <div class='col-md-6'>
							<div class='form-group'>
								<label>Nama Dokumen</label>
								<input type='text' name='nama_dokumen' class='form-control' placeholder='Nama Pengalaman' required>
							</div>
						  </div>
						  <div class='col-md-6'>
							<div class='form-group'>
								<label>Nomor Dokumen/SK</label>
								<input type='text' name='nomor_dokumen' class='form-control' placeholder='Nomor Dokumen' required>
							</div>
						  </div>
						  <div class='col-md-3'>
							<div class='form-group'>
								<label>Tahun Dokumen/SK</label>
								<select name='tahun_dokumen' class='form-control'>";
			$tahunskr = date("Y");
			$tahunnya = intval(trim(substr($as['tgl_lahir'], 0, 4))) + 10;
			while ($tahunnya <= $tahunskr) {
				echo "<option value='$tahunnya'>$tahunnya</option>";
				$tahunnya = $tahunnya + 1;
			}
			echo "</select>
							</div>
						  </div>
						  <div class='col-md-3'>
							<div class='form-group'>
								<label>Tanggal Dokumen/SK</label>
								<input type='date' name='tgl_dokumen' class='form-control' required>
							</div>
						  </div>
						  <div class='col-md-3'>
							<div class='form-group'>
								<label>File Pendukung</label>
								<label for='fileID'>
								<input type='file' name='file' id='fileID' accept='.pdf, image/*' onchange='readURL(this);'>
								<span>File pdf/jpg/png, maks. $upload_mb Mb</span>
							</div>
						  </div>
						  <div class='col-md-3'>
							<div class='form-group'>
							<button type='submit' class='btn btn-primary' name='tambahdocasesi'>Tambahkan</button>
							</form>
							</div>
						  </div>
						  <div class='col-md-12'>
							<div class='form-group'>
							<a href='#myModalLib" . $as['no_pendaftaran'] . "' class='btn btn-primary' data-toggle='modal' data-id='" . $as['id'] . "'>Tambahkan dokumen dari <em>Library</em> (pustaka anda)</a>
							</div>
						  </div>
					<script>
						$(function(){
									$(document).on('click','.edit-record',function(e){
										e.preventDefault();
										$('#myModal" . $as['no_pendaftaran'] . "').modal('show');
									});
							});
					</script>
					<!-- Modal -->
						<div class='modal fade' id='myModalLib" . $as['no_pendaftaran'] . "' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
							<div class='modal-dialog'>
								<div class='modal-content'>
									<div class='modal-header'>
										<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Tutup</span></button>
										<h4 class='modal-title' id='myModalLabel'><em>Library</em> (Pustaka) Dokumen " . $as['no_pendaftaran'] . "</h4>
									</div>
									<div class='modal-body'>
										<div style='overflow-x:auto; overflow-y:auto;'>
										<table id='example1' class='table table-bordered table-striped'>
											<thead><tr><th>Dokumen</th><th>Aksi</th></tr></thead>
											<tbody>";
			$sqlasesidocx = "SELECT * FROM `asesi_doc` WHERE `id_asesi`='$as[no_pendaftaran]' ORDER BY `id` DESC";
			$asesidocx = $conn->query($sqlasesidocx);
			while ($pmx = $asesidocx->fetch_assoc()) {
				echo "<tr><td width='50%'>Nama Dokumen : $pmx[nama_doc]<br>No. Dokumen : <b><a href='#myModalM" . $pmx['id'] . "' data-toggle='modal' data-id='" . $pmx['id'] . "'>$pmx[nomor_doc]</a></b><br>Tanggal Dok.: <b>" . tgl_indo($pmx['tgl_doc']) . "</b></td>
													<td width='50%'>
														<form role='form' method='POST' enctype='multipart/form-data'>
														<input type='hidden' name='nama_dokumen' value='$pmx[nama_doc]'>
														<input type='hidden' name='nomor_dokumen' value='$pmx[nomor_doc]'>
														<input type='hidden' name='tgl_dokumen' value='$pmx[tgl_doc]'>
														<input type='hidden' name='tahun_dokumen' value='$pmx[tahun_doc]'>
														<input type='hidden' name='file' value='$pmx[file]'>
														<div class='col-md-12'>
														<label>Jenis Persyaratan</label>";
				$sqlselectkat = "SELECT * FROM `skema_persyaratan` WHERE `id_skemakkni`='$_GET[id]' ORDER BY `id` ASC";
				$tampilkat = $conn->query($sqlselectkat);
				while ($rr = $tampilkat->fetch_assoc()) {
					echo "<br><input type='radio' name='kategori' id='options$rr[id]' value='$rr[id]' required>
																 $rr[persyaratan] &nbsp;&nbsp;&nbsp;";
				}
				echo "</div><div class='col-md-12'>
														<button type='submit' name='addfromlib' class='btn btn-primary btn-block'>Gunakan</button></div>
														</form>
													</td></tr>";
				echo "<script>
														$(function(){
																	$(document).on('click','.edit-record',function(e){
																		e.preventDefault();
																		$('#myModalM" . $pmx['id'] . "').modal('show');
																	});
															});
													</script>
													<!-- ModalM -->
														<div class='modal fade' id='myModalM" . $pmx['id'] . "' tabindex='-1' role='dialog' aria-labelledby='myModalMLabel' aria-hidden='true'>
															<div class='modal-dialog'>
																<div style='overflow-y:auto;'>
																<div class='modal-content'>
																	<div class='modal-header'>
																		<h4 class='modal-title' id='myModalMLabel'>Library Dokumen Porfolio " . $pmx['nama_doc'] . "</h4>
																	</div>
																	<div class='modal-body'><embed src='foto_asesi/$pmx[file]' width='100%' height='500px'/>
																	</div>
																	<div class='modal-footer'>
																		<em><font color='red'>klik di area gelap untuk menutup</font></em>
																	</div>
																</div>
																</div>
															   </div><!-- End Overflow-->
														</div>
													<!-- ModalM End-->";
			}
			echo "</tbody>
										</table>
										</div>
									</div>
									<div class='modal-footer'>
										<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>
									</div>
								</div>
								</div>
						</div>
						<!-- Modal End -->
						</div>
					</div>
					<h1>FR-APL-01. FORMULIR PERMOHONAN SERTIFIKASI KOMPETENSI</h1>
					<h2>Bagian 1: Rincian Data Pemohon Sertifikasi</h2>
					<h3>a. Data Pribadi</h3>
					<table id='example1' class='table table-bordered table-striped'>
						<tr><td width='25%'>Nama Lengkap</td><td>";
			$namanya = strtoupper($as['nama']);
			echo "$namanya</td></tr>
						<tr><td>Tempat/ Tgl. Lahir</td><td>";
			$ttl = ucwords(strtolower($as['tmp_lahir'])) . ", " . tgl_indo($as['tgl_lahir']);
			echo "$ttl</td></tr>
						<tr><td>Jenis Kelamin</td><td>";
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
			echo "$jeniskelamin</td></tr>
						<tr><td>Kebangsaan</td><td>";
			$kebangsaan = ucwords(strtolower($as['kebangsaan']));
			echo "$kebangsaan</td></tr>
						<tr><td>Alamat Rumah</td><td>";
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
			echo "$alamattampil</td></tr>
						<tr><td>No. Telp/ Email</td><td>Rumah : $as[nohp]<br />Kantor : $as[telp_kantor]<br />HP : $as[nohp]<br />Email : $as[email]</td></tr>
						<tr><td>Pendidikan Terakhir</td><td>";
			$sqlpendidikan = "SELECT * FROM `pendidikan` WHERE `id`='$as[pendidikan]'";
			$pendidikan = $conn->query($sqlpendidikan);
			$pdd = $pendidikan->fetch_assoc();
			$pendidikannyaa = $pdd['jenjang_pendidikan'];
			echo "$pendidikannyaa</td></tr>
					</table>
					<h3>b. Data Pekerjaan Sekarang</h3>
					<table id='example1' class='table table-bordered table-striped'>
						<tr><td width='25%'>Nama lembaga/ perusahaan</td><td>";
			$namakantornya = strtoupper($as['nama_kantor']);
			echo "$namakantornya</td></tr>
						<tr><td>Jabatan</td><td>";
			$sqlgetpekerjaan = "SELECT * FROM `pekerjaan` WHERE `id`='$as[pekerjaan]'";
			$getpekerjaan = $conn->query($sqlgetpekerjaan);
			$askrj = $getpekerjaan->fetch_assoc();
			$jabatannya = strtoupper($askrj['pekerjaan']);
			echo "$jabatannya</td></tr>
						<tr><td>Alamat</td><td>";
			echo "$as[alamat_kantor]</td></tr>
						<tr><td>No. Telp./ Fax./ Email</td><td>";
			echo "Telp.: $as[telp_kantor]<br />Fax. : $as[fax_kantor]<br />Email : $as[email_kantor]</td></tr>
					</table>
					<h2>Bagian 2: Data Sertifikasi</h2>
					<table id='example1' class='table table-bordered table-striped'>";
			$querycek2 = "SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_skemakkni`='$_GET[id]' AND `status_asesmen`!='K'";
			$resultc2 = $conn->query($querycek2);
			$gstas = $resultc2->fetch_assoc();
			echo "<form role='form' method='POST' enctype='multipart/form-data'>
						<tr><td rowspan='2'>Skema Sertifikasi<br />(KKNI/Okupasi/Klaster)</td><td>Judul</td><td>$gs[judul]</td></tr>
						<tr><td>Nomor</td><td>$gs[kode_skema]</td></tr>
						<tr><td colspan='2'>Tujuan Asesmen</td><td>
												<input type='radio' required='required' name='tujuan_sertifikasi' id='tujuansertifikasi1' value='Sertifikasi'";
			if ($gstas['tujuan_sertifikasi'] == "Sertifikasi") {
				echo " checked";
			} else {
				echo " checked";
			}
			echo ">
												Sertifikasi<br>
												<input type='radio' required='required' name='tujuan_sertifikasi' id='tujuansertifikasi2' value='Sertifikasi Ulang'";
			if ($gstas['tujuan_sertifikasi'] == "Sertifikasi Ulang") {
				echo " checked";
			} else {
				echo "";
			}
			echo ">
												Sertifikasi Ulang<br>
												<input type='radio' required='required' name='tujuan_sertifikasi' id='tujuansertifikasi3' value='Pengakuan Kompetensi Terkini (PKT)'";
			if ($gstas['tujuan_sertifikasi'] == "Pengakuan Kompetensi Terkini (PKT)") {
				echo " checked";
			} else {
				echo "";
			}
			echo ">
												Pengakuan Kompetensi Terkini (PKT)<br>
												<input type='radio' required='required' name='tujuan_sertifikasi' id='tujuansertifikasi4' value='Rekognisi Pembelajaran Lampau'";
			if ($gstas['tujuan_sertifikasi'] == "Rekognisi Pembelajaran Lampau") {
				echo " checked";
			} else {
				echo "";
			}
			echo ">
												Rekognisi Pembelajaran Lampau<br>
												<input type='radio' required='required' name='tujuan_sertifikasi' id='tujuansertifikasi5' value='Lainnya'";
			if ($gstas['tujuan_sertifikasi'] == "Lainnya") {
				echo " checked";
			} else {
				echo "";
			}
			echo ">
												Lainnya : <input type='text' name='tujuan_lainnya' class='form-control' value='$gstas[tujuan_lainnya]'/>
					</td></tr>
					</table>
					<h3>Daftar Unit Kompetensi sesuai kemasan:</h3>
					<div style='overflow-x:auto;'>
					<table id='example' class='table table-bordered table-striped'>
						<thead><tr><th>No</th><th>Kode Unit</th><th>Judul Unit</th><th>Jenis Standar<br />(Standar Khusus/ Standar Internasional/ SKKNI)</th></tr></thead>
						<tbody>";
			$no = 1;
			$sqllsp = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$gs[id]' ORDER BY `id` ASC";
			$lsp = $conn->query($sqllsp);
			while ($pm = $lsp->fetch_assoc()) {
				echo "<tr class=gradeX><td>$no</td>";
				echo "</td><td><b>$pm[kode_unit]</b></td><td>$pm[judul]";
				/* $sqllspe0="SELECT * FROM `elemen_kompetensi` WHERE `id_unitkompetensi`='$pm[id]' ORDER BY `id` ASC";
							$lspe0=$conn->query($sqllspe0);
							$numel=$lspe0->num_rows;
							if ($numel>0){
								echo "<br><b>Elemen Kompetensi:</b>";
								$sqllspe="SELECT * FROM `elemen_kompetensi` WHERE `id_unitkompetensi`='$pm[id]' ORDER BY `id` ASC";
								$lspe=$conn->query($sqllspe);
								$noel=1;
								while ($pme=$lspe->fetch_assoc()){
									echo "<br>$noel. $pme[elemen_kompetensi]";
									$noel++;
								}
							} */
				echo "&nbsp;<input name='ukom[]' type='checkbox' value='$pm[id]' ";
				$sqlgetukom = "SELECT * FROM `asesmen_unitkompetensi` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_skemakkni`='$_GET[id]' AND `id_unitkompetensi`='$pm[id]'";
				$getukom = $conn->query($sqlgetukom);
				$ukomnya = $getukom->num_rows;
				//if ($ukomnya>0){
				echo "checked";
				//}
				echo " required/></td><td>";
				$sqlgetskkni = "SELECT * FROM `skkni` WHERE `id`='$pm[id_skkni]'";
				$getskkni = $conn->query($sqlgetskkni);
				$skkx = $getskkni->fetch_assoc();
				echo $skkx['no_skkni'];
				echo "</td></tr>";
				$no++;
			}
			echo "</tbody>
					</table></div>
					<h2>Bagian 3: Bukti Kelengkapan Pemohon</h2>
					<h3>Bukti Persyaratan Dasar Pemohon</h3>
					<div class='row'>
					<div class='box-body'>
					<h3>Data Dokumen Persyaratan</h3>
					<div style='overflow-x:auto;'>
					<table id='table-example' class='table table-bordered table-striped'>
					<thead><tr><th>No.</th><th>Persyaratan<th>File Pendukung</th><th>Status</th></tr></thead>
					<tbody>";
			$no = 1;
			$sqlasesidoc = "SELECT * FROM `asesi_doc` WHERE `id_asesi`='$as[no_pendaftaran]' AND `id_skemakkni`='$_GET[id]' AND `status` != 'R' ORDER BY `id` DESC";
			$asesidoc = $conn->query($sqlasesidoc);
			$jumpm = $asesidoc->num_rows;
			while ($pm = $asesidoc->fetch_assoc()) {
				switch ($pm['status']) {
					default:
						$statusnya = "<font color='blue'><b>Menunggu<br />Persetujuan</b></font>";
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
				if ($cekasesiasesmen['status'] == 'P') {
				}else{
					if ($pm['status'] == 'P') {
						// echo "<br />
						// 	<input type='hidden' name='iddeldocasesi' value='$pm[id]'>
						// 	<input type='submit' name='hapusdocasesi' class='btn btn-danger btn-xs' onclick='return confirmation()' title='Hapus' value='Hapus'>";
						
						// UPDATE @FHM-PPM 28 JULY 2023 : Perubahan fungsi hapus persyaratan dokumen form-apl-01
						echo "<br />
							<button type='submit' name='hapusdocasesi' class='btn btn-danger btn-xs' onclick='return confirmation()' title='Hapus' value='$pm[id]'>Hapus</button";
					}
				}
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
									</div>
									<div class='modal-body'><embed src='foto_asesi/$pm[file]' width='100%' height=600px'/>
									</div>
									<div class='modal-footer'>
										<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>
									</div>
								</div>
								</div>
						</div>";
			}
			echo "</tbody></table></div>";
			if ($jumpm <> $jumpsdev) {
				echo "<br><h2><font color='red'>Proses pendaftaran dapat dilakukan bila Anda telah mengunggah SEMUA DOKUMEN PERSYARATAN SKEMA.</font></h2>";
			}
			echo "<input type='hidden' name='biaya_asesmen' value='$totbiaya'>";
			// cek tandatangan digital
			$url =  "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
			$iddokumen = md5($url);
			$sqlcektandatangan = "SELECT * FROM `logdigisign` WHERE `id_dokumen`='$iddokumen' AND `penandatangan`='$namanya' ORDER BY `waktu` DESC";
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
			if(empty($cekasesiasesmen)){
?>
			<div class="col-md-12">
				<label class="" for="">Tanda Tangan:</label>
				<br />
				<div id="sig"></div>
				<br />
				<button id="clear">Hapus Tanda Tangan</button>
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
		<?php
			echo "<div align='left' class='col-md-6 col-sm-6 col-xs-6'>
			<a class='btn btn-danger' id=reset-validate-form href='?module=home'>Batal</a>
			</div>
			<div align='right' class='col-md-6 col-sm-6 col-xs-6'>";
			if ($jumpm == $jumpsdev) {
				echo "<input type='submit' class='btn btn-success' name='daftarasesmen' value='Proses Pendaftaran'>";
			} else {
				echo "<input type='submit' class='btn btn-success' name='daftarasesmen' title='Tombol aktif bila semua persyaratan telah diunggah' value='Proses Pendaftaran' disabled>";
			}
			echo "</div>
			</form>";
		}
		}
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
// Bagian Input Syarat Skema LSP ==========================================================================================================
elseif ($_GET['module'] == 'updatesyarat') {
	$sqlasesi = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_SESSION[namauser]'";
	$getasesi = $conn->query($sqlasesi);
	$as = $getasesi->fetch_assoc();
	$max_upload = (int)(ini_get('upload_max_filesize'));
	$max_post = (int)(ini_get('post_max_size'));
	$memory_limit = (int)(ini_get('memory_limit'));
	$upload_mb = min($max_upload, $max_post, $memory_limit);
	function uploadDoc($file)
	{
		//$file_max_weight = 20000000; //Ukuran maksimal yg dibolehkan( 20 Mb)
		$ok_ext = array('jpg', 'png', 'gif', 'jpeg', 'JPG', 'PNG', 'GIF', 'JPEG', 'pdf', 'PDF'); // ekstensi yang diijinkan
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
	if (isset($_REQUEST['tambahdocasesi'])) {
		$file = $_FILES['file'];
		// Apabila ada file yang diupload
		if (empty($_FILES['file']['tmp_name']) || !is_uploaded_file($_FILES['file']['tmp_name'])) {
			$query = "INSERT INTO `asesi_doc`(`id_asesi`, `id_skemakkni`, `skema_persyaratan`, `nama_doc`, `tahun_doc`, `nomor_doc`, `tgl_doc`) VALUES ('$_SESSION[namauser]','$_GET[id]','$_POST[kategori]','$_POST[nama_dokumen]','$_POST[tahun_dokumen]','$_POST[nomor_dokumen]','$_POST[tgl_dokumen]')";
		} else {
			$alamatfile = uploadDoc($file);
			$query = "INSERT INTO `asesi_doc`(`id_asesi`, `id_skemakkni`, `skema_persyaratan`, `nama_doc`, `tahun_doc`, `nomor_doc`, `tgl_doc`, `file`) VALUES ('$_SESSION[namauser]','$_GET[id]','$_POST[kategori]','$_POST[nama_dokumen]','$_POST[tahun_dokumen]','$_POST[nomor_dokumen]','$_POST[tgl_dokumen]','$alamatfile')";
		}
		$querycek = "SELECT * FROM `asesi_doc` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_skemakkni`='$_GET[id]' AND `skema_persyaratan`='$_POST[kategori]' AND `nama_doc`='$_POST[nama_dokumen]' AND `tahun_doc`='$_POST[tahun_dokumen]' AND `nomor_doc`='$_POST[nomor_dokumen]' AND `tgl_doc`='$_POST[tgl_dokumen]'";
		$resultc = $conn->query($querycek);
		$row_cnt = $resultc->num_rows;
		if ($row_cnt == 0) {
			$conn->query($query);
			//header('location:../../media.php?module=syarat&id=$_GET[id]');
			echo "<div class='alert alert-success alert-dismissible'>
		<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
		<h4><i class='icon fa fa-check'></i> Tambah Data Sukses</h4>
		Anda Telah Berhasil Mengunggah Data <b>Persyaratan Sertifikasi</b></div>";
		} else {
			echo "<script>alert('Maaf Data Tersebut Sudah Ada'); window.location = '../../media.php?module=syarat&id=$_GET[id]'</script>";
		}
	}
	if (isset($_REQUEST['addfromlib'])) {
		$alamatfile = $_POST['file'];
		$query = "INSERT INTO `asesi_doc`(`id_asesi`, `id_skemakkni`, `skema_persyaratan`, `nama_doc`, `tahun_doc`, `nomor_doc`, `tgl_doc`, `file`) VALUES ('$_SESSION[namauser]','$_GET[id]','$_POST[kategori]','$_POST[nama_dokumen]','$_POST[tahun_dokumen]','$_POST[nomor_dokumen]','$_POST[tgl_dokumen]','$alamatfile')";
		$querycek = "SELECT * FROM `asesi_doc` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_skemakkni`='$_GET[id]' AND `skema_persyaratan`='$_POST[kategori]' AND `nama_doc`='$_POST[nama_dokumen]' AND `tahun_doc`='$_POST[tahun_dokumen]' AND `nomor_doc`='$_POST[nomor_dokumen]' AND `tgl_doc`='$_POST[tgl_dokumen]'";
		$resultc = $conn->query($querycek);
		$row_cnt = $resultc->num_rows;
		if ($row_cnt == 0) {
			$conn->query($query);
			//header('location:../../media.php?module=syarat&id=$_GET[id]');
			echo "<div class='alert alert-success alert-dismissible'>
		<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
		<h4><i class='icon fa fa-check'></i> Tambah Data Sukses</h4>
		Anda Telah Berhasil Mengunggah Data <b>Persyaratan Sertifikasi</b></div>";
		} else {
			echo "<script>alert('Maaf Data Tersebut Sudah Ada'); window.location = '../../media.php?module=syarat&id=$_GET[id]'</script>";
		}
	}
	if (isset($_REQUEST['daftarasesmen'])) {
		$tgl_daftar = date("Y-m-d");
		if ($_POST['tujuan_sertifikasi'] == "Lainnya") {
			$querydas = "INSERT INTO `asesi_asesmen`(`tujuan_sertifikasi`, `tujuan_lainnya`, `id_asesi`, `id_skemakkni`, `tgl_daftar`,`biaya`) VALUES ('$_POST[tujuan_sertifikasi]', '$_POST[tujuan_lainnya]', '$_SESSION[namauser]','$_GET[id]','$tgl_daftar','$_POST[biaya_asesmen]')";
		} else {
			$querydas = "INSERT INTO `asesi_asesmen`(`tujuan_sertifikasi`, `id_asesi`, `id_skemakkni`, `tgl_daftar`,`biaya`) VALUES ('$_POST[tujuan_sertifikasi]', '$_SESSION[namauser]','$_GET[id]','$tgl_daftar','$_POST[biaya_asesmen]')";
		}
		if (!empty($_POST['nama_kantor'])) {
			$sqlupdatekantor = "UPDATE `asesi` SET `jabatan`='$_POST[jabatan]', `nama_kantor`='$_POST[nama_kantor]', `alamat_kantor`='$_POST[alamat_kantor]', `telp_kantor`='$_POST[telp_kantor]', `fax_kantor`='$_POST[fax_kantor]', `email_kantor`='$_POST[email_kantor]' WHERE `no_pendaftaran`='$_SESSION[namauser]'";
			$conn->query($sqlupdatekantor);
		}
		$querycek = "SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_skemakkni`='$_GET[id]' AND `status_asesmen`!='K'";
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
			// cek LSP Konstruksi
			$sqlceklspkonstruksi = "SELECT * FROM `user_pupr`";
			$ceklspkonsturksi = $conn->query($sqlceklspkonstruksi);
			$jumlspkonstruksi = $ceklspkonsturksi->num_rows;
			if ($jumlspkonstruksi > 0) {
				/* $pesan="Terimakasih telah melakukan pendaftaran Asesmen Skema $gs[kode_skema] - $gs[judul]<br /><br />  
				ID Asesi: $_SESSION[namauser] <br />
				Nama: $namanya <br />
				Nomor Handphone: $as[nohp] <br />
				Skema: $gs[kode_skema] - $gs[judul]<br />
				Biaya: $_POST[biaya_asesmen]<br />
				<br /><br />Silahkan tunggu email berikutnya berisi informasi <b>PERSETUJUAN<b> dari Admin LSP beserta <b>INVOICE (TAGIHAN)</b> dan lakukan pembayaran sejumlah Rp. $_POST[biaya_asesmen] setelah Anda menerima persetujuan dan Jadwal Asesmen. Pembayaran dapat dilakukan secara tunai atau melalui transfer ke salah satu rekening berikut:<br /><br />"; */
				$pesan = "Terimakasih telah melakukan pendaftaran Asesmen Skema $gs[kode_skema] - $gs[judul]<br /><br />  
				ID Asesi: $_SESSION[namauser] <br />
				Nama: $namanya <br />
				Nomor Handphone: $as[nohp] <br />
				Skema: $gs[kode_skema] - $gs[judul]<br />
				<br /><br />Silahkan tunggu email berikutnya berisi informasi <b>PERSETUJUAN<b> dari Admin LSP beserta <b>INVOICE (TAGIHAN)</b> dan lakukan pembayaran setelah Anda menerima persetujuan dan Jadwal Asesmen. Pembayaran dapat dilakukan secara tunai atau melalui transfer ke salah satu rekening berikut:<br /><br />";
				$sqlgetbank = "SELECT * FROM `rekeningbayar` WHERE `metode`!='Tunai' AND `aktif`='Y'";
				$getbank = $conn->query($sqlgetbank);
				while ($rek = $getbank->fetch_assoc()) {
					$pesan .= "Rekening $rek[bank] No. $rek[norek]<br>an. $rek[atasnama]<br>";
				}
				$subjek = "Terimakasih telah melakukan Pendaftaran Asesmen di $iden[nama_lsp]";
				$dari = "From: noreply@" . $urldomain . "\r\n";
				$dari .= "Content-type: text/html\r\n";
			} else {
				$pesan = "Terimakasih telah melakukan pendaftaran Asesmen Skema $gs[kode_skema] - $gs[judul]<br /><br />  
				ID Asesi: $_SESSION[namauser] <br />
				Nama: $namanya <br />
				Nomor Handphone: $as[nohp] <br />
				Skema: $gs[kode_skema] - $gs[judul]<br />
				Biaya: $_POST[biaya_asesmen]<br />
				<br /><br />Silahkan lakukan pembayaran sejumlah Rp. $_POST[biaya_asesmen]. Pembayaran dapat dilakukan secara tunai atau melalui transfer ke salah satu rekening berikut:<br /><br />";
				$sqlgetbank = "SELECT * FROM `rekeningbayar` WHERE `metode`!='Tunai' AND `aktif`='Y'";
				$getbank = $conn->query($sqlgetbank);
				while ($rek = $getbank->fetch_assoc()) {
					$pesan .= "Rekening $rek[bank] No. $rek[norek]<br>an. $rek[atasnama]<br>";
				}
				$pesan .= "Selanjutnya lakukan konfirmasi pembayaran di laman asesi<br>";
				$subjek = "Terimakasih telah melakukan Pendaftaran Asesmen di $iden[nama_lsp]";
				$dari = "From: noreply@" . $urldomain . "\r\n";
				$dari .= "Content-type: text/html\r\n";
			}
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
			//SMS Pendaftar
			if ($pupr > 0) {
				$isisms = "Yth. $namanya Pendaftaran Asesmen Skema $gs[kode_skema]-$gs[judul] berhasil, silahkan lakukan pembayaran biaya asesmen sesuai yang dikirimkan ke email Anda.";
			} else {
				$isisms = "Yth. $namanya Pendaftaran Asesmen Skema $gs[kode_skema]-$gs[judul] berhasil, silahkan lakukan pembayaran biaya asesmen Rp. $_POST[biaya_asesmen] sesuai yang dikirimkan ke email Anda";
			}
			if (strlen($no_hp) > 8) {
				$sqloutbox = "INSERT INTO `outbox` (`DestinationNumber`, `TextDecoded`, `Coding`,`SenderID`,`CreatorID`) VALUES ('$no_hp','$isisms','Default_No_Compression','MyPhone1','MyPhone1')";
				$outbox = $conn->query($sqloutbox);
			}
			//============================================================================
			$mod = count($_POST['ukom']);
			$modul = $_POST['ukom'];
			// clear data asesmen_unitkompetensi
			$conn->query("DELETE FROM `asesmen_unitkompetensi` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_skemakkni`='$_GET[id]'");
			for ($i = 0; $i < $mod; $i++) {
				$conn->query("INSERT INTO `asesmen_unitkompetensi`(`id_asesi`, `id_skemakkni`, `id_unitkompetensi`) VALUES ('$_SESSION[namauser]', '$_GET[id]', '$modul[$i]')");
			}
			//=====================update data usia=========================================================
			$sqlkalkulasiusia = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_SESSION[namauser]'";
			$kalkulasiusia = $conn->query($sqlkalkulasiusia);
			$kus = $kalkulasiusia->fetch_assoc();
			$tgl_lahir = $kus['tgl_lahir'];
			// ubah ke format Ke Date Time
			$lahir = new DateTime($tgl_lahir);
			$hari_ini = new DateTime();
			$diff = $hari_ini->diff($lahir);
			$usia = $diff->y;
			$sqlupdateusia = "UPDATE `asesi` SET `usia`='$usia' WHERE `no_pendaftaran`='$_SESSION[namauser]'";
			$updateusia = $conn->query($sqlupdateusia);
			//---------------------------------------------------		
			$folderPath = "foto_tandatangan/";
			if (empty($_POST['signed'])) {
				echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Pendaftaran Asesmen Sukses</h4>
			Anda Telah Berhasil mendaftarkan diri pada skema ini<b></b></div>";
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
				$sqlinputdigisign = "INSERT INTO `logdigisign`(`id_dokumen`, `id_asesi`, `id_skema`, `url_ditandatangani`, `nama_dokumen`, `penandatangan`, `file`, `ip`) VALUES ('$iddokumen','$_SESSION[namauser]','$_GET[id]'	,'$escaped_url','FR-APL-01. FORMULIR PERMOHONAN SERTIFIKASI KOMPETENSI','$rowAgen[nama]','$file','$alamatip')";
				$conn->query($sqlinputdigisign);
				echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Pendaftaran Asesmen Sukses</h4>
			Anda Telah Berhasil mendaftarkan diri pada skema ini<b></b><br><b>Tanda Tangan Sukses Diupload</b></div>";
			}
		} else {
			//$conn->query($querydas);
			//Notifikasi Email dan SMS====================================================
			$sqlgetskema = "SELECT * FROM `skema_kkni` WHERE `id`='$_GET[id]'";
			$getskema = $conn->query($sqlgetskema);
			$gs = $getskema->fetch_assoc();
			$email = $as['email'];
			$namanya = $as['nama'];
			$no_hp = $as['nohp'];
			// Kirim email dalam format HTML ke Pendaftar
			if ($pupr > 0) {
				$pesan = "Terimakasih telah melakukan pemutakhiran data pendaftaran Asesmen Skema $gs[kode_skema] - $gs[judul]<br /><br />  
				ID Asesi: $_SESSION[namauser] <br />
				Nama: $namanya <br />
				Nomor Handphone: $as[nohp] <br />
				Skema: $gs[kode_skema] - $gs[judul]<br />
				<br /><br />Silahkan lakukan pembayaran melalui transfer ke salah satu rekening berikut:<br /><br />";
			} else {
				$pesan = "Terimakasih telah melakukan pemutakhiran data pendaftaran Asesmen Skema $gs[kode_skema] - $gs[judul]<br /><br />  
				ID Asesi: $_SESSION[namauser] <br />
				Nama: $namanya <br />
				Nomor Handphone: $as[nohp] <br />
				Skema: $gs[kode_skema] - $gs[judul]<br />
				Biaya: $_POST[biaya_asesmen]<br />
				<br /><br />Silahkan lakukan pembayaran sejumlah Rp. $_POST[biaya_asesmen] secara tunai atau melalui transfer ke salah satu rekening berikut:<br /><br />";
			}
			$sqlgetbank = "SELECT * FROM `rekeningbayar` WHERE `metode`!='Tunai' AND `aktif`='Y'";
			$getbank = $conn->query($sqlgetbank);
			while ($rek = $getbank->fetch_assoc()) {
				$pesan .= "Rekening $rek[bank] No. $rek[norek]<br>an. $rek[atasnama]<br>";
			}
			$subjek = "Terimakasih telah melakukan pemutakhiran data Pendaftaran Asesmen di $iden[nama_lsp]";
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
			//SMS Pendaftar
			if ($pupr > 0) {
				$isisms = "Yth. $namanya Pendaftaran Asesmen Skema $gs[kode_skema]-$gs[judul] berhasil, silahkan lakukan pembayaran biaya asesmen sesuai yang dikirimkan ke email Anda.";
			} else {
				$isisms = "Yth. $namanya Pendaftaran Asesmen Skema $gs[kode_skema]-$gs[judul] berhasil, silahkan lakukan pembayaran biaya asesmen Rp. $_POST[biaya_asesmen] sesuai yang dikirimkan ke email Anda";
			}
			if (strlen($no_hp) > 8) {
				$sqloutbox = "INSERT INTO `outbox` (`DestinationNumber`, `TextDecoded`, `Coding`,`SenderID`,`CreatorID`) VALUES ('$no_hp','$isisms','Default_No_Compression','MyPhone1','MyPhone1')";
				$outbox = $conn->query($sqloutbox);
			}
			//============================================================================
			$mod = count($_POST['ukom']);
			$modul = $_POST['ukom'];
			// clear data asesmen_unitkompetensi
			$conn->query("DELETE FROM `asesmen_unitkompetensi` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_skemakkni`='$_GET[id]'");
			for ($i = 0; $i < $mod; $i++) {
				$conn->query("INSERT INTO `asesmen_unitkompetensi`(`id_asesi`, `id_skemakkni`, `id_unitkompetensi`) VALUES ('$_SESSION[namauser]', '$_GET[id]', '$modul[$i]')");
			}
			//=====================update data usia=========================================================
			$sqlkalkulasiusia = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_SESSION[namauser]'";
			$kalkulasiusia = $conn->query($sqlkalkulasiusia);
			$kus = $kalkulasiusia->fetch_assoc();
			$tgl_lahir = $kus['tgl_lahir'];
			// ubah ke format Ke Date Time
			$lahir = new DateTime($tgl_lahir);
			$hari_ini = new DateTime();
			$diff = $hari_ini->diff($lahir);
			$usia = $diff->y;
			$sqlupdateusia = "UPDATE `asesi` SET `usia`='$usia' WHERE `no_pendaftaran`='$_SESSION[namauser]'";
			$updateusia = $conn->query($sqlupdateusia);
			//---------------------------------------------------		
			$folderPath = "foto_tandatangan/";
			if (empty($_POST['signed'])) {
				echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Pendaftaran Asesmen Sukses</h4>
			Anda Telah Berhasil mendaftarkan diri pada skema ini<b></b></div>";
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
				$sqlinputdigisign = "INSERT INTO `logdigisign`(`id_dokumen`, `id_asesi`, `id_skema`, `url_ditandatangani`, `nama_dokumen`, `penandatangan`, `file`, `ip`) VALUES ('$iddokumen','$_SESSION[namauser]','$_GET[id]'	,'$escaped_url','FR-APL-01. FORMULIR PERMOHONAN SERTIFIKASI KOMPETENSI','$rowAgen[nama]','$file','$alamatip')";
				$conn->query($sqlinputdigisign);
				echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Pendaftaran Asesmen Sukses</h4>
			Anda Telah Berhasil mendaftarkan diri pada skema ini<b></b><br><b>Tanda Tangan Sukses Diupload</b></div>";
			}
			echo "<script>alert('Anda telah memperbarui data pendaftaran uji kompetensi pada skema ini'); window.location = '../../media.php?module=updatesyarat&id=$_GET[id]&ida=$_SESSION[namauser]'</script>";
		}
	}
	$sqlgetskema = "SELECT * FROM `skema_kkni` WHERE `id`='$_GET[id]'";
	$getskema = $conn->query($sqlgetskema);
	$gs = $getskema->fetch_assoc();
	echo "
    <!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
        Persyaratan Uji Kompetensi
        <small>Input Data</small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li><a href='media.php?module=skema'><i class='fa fa fa-edit'></i> Skema Sertifikasi</a></li>
        <li class='active'>Data Persyaratan</li>
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
			<h2>$gs[judul] <b>($gs[kode_skema])</b></h2>
			<div style='overflow-x:auto;'>
			<table id='example' class='table table-bordered table-striped'>
				<thead><tr><th>No</th><th>Persyaratan</th></tr></thead>
				<tbody>";
	$no = 1;
	$sqllsp = "SELECT * FROM `skema_persyaratan` WHERE `id_skemakkni`='$gs[id]' ORDER BY `id` ASC";
	$lsp = $conn->query($sqllsp);
	$jumpsdev = $lsp->num_rows;
	while ($pspm = $lsp->fetch_assoc()) {
		echo "<tr class=gradeX><td>$no</td>";
		echo "</td><td>$pspm[persyaratan]</td></tr>";
		/*echo "<td><form name='frm' method='POST' role='form' enctype='multipart/form-data'>
						<input type='hidden' name='iddelsy' value='$pm[id]'><input type='submit' name='hapussy' class='btn btn-danger btn-xs' onclick='return confirmation()' title='Hapus' value='Hapus'></form></td></tr>"; */
		$no++;
	}
	echo "</tbody>
			</table></div>
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
			<div style='overflow-x:auto;'>
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
			</table></div>";
	if ($pupr > 0) {
		echo "<font color='red'>Catatan : BIAYA TERSEBUT <b>BELUM TERMASUK BIAYA OPERASIONAL</b> DI MASING-MASING WILAYAH/PENYELENGGARA/TEMPAT UJI KOMPETENSI</font>";
	}
	echo "</div>
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
				$dif = $dif;
			};
		};
		return $dif;
	}
	if ($as['tgl_lahir'] != NULL) {
		$usia = getAge($as['tgl_lahir']);
	} else {
		$usia = 0;
	}
	if ($usia < 101) {
		$syaratusia = "<font color='green'><b>Anda telah memenuhi Persyaratan Usia</b></font>";
	} else {
		$syaratusia = "<font color='red'><b>Maaf, Anda tidak memenuhi Persyaratan Usia</b></font>";
	}
	$sqlpendidikan = "SELECT * FROM `pendidikan` WHERE `id`='$as[pendidikan]'";
	$pendidikan = $conn->query($sqlpendidikan);
	$pdas = $pendidikan->fetch_assoc();
	if ($as['pendidikan'] > 1) {
		$syaratpend = "<font color='green'><b>Anda telah memenuhi Persyaratan Pendidikan</b></font>";
	} else {
		$syaratpend = "<font color='red'><b>Maaf, Anda tidak memenuhi Persyaratan Pendidikan</b></font>";
	}
	echo "<p>Untuk mengikuti uji kompetensi pada skema ini, silahkan lengkapi persyaratan dan dokumen berikut:<br>
			Usia Anda adalah <b>$usia tahun</b>, $syaratusia<br>
			Pendidikan terakhir Anda adalah <b>$pdas[jenjang_pendidikan]</b>, $syaratpend<br></p>";
	if (empty($as['foto']) or empty($as['ktp']) || empty($as['ijazah'])) {
		$sydokpokok = "kosong";
		echo "<div class='alert alert-warning alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-warning'></i> Maaf, Anda belum mengunggah Dokumen Pokok !</h4>
				Silahkan unggah dokumen pokok Anda (Foto, KTP, dan Ijazah Terakhir) melalui menu <b><a href='media.php?module=unggahfile'>Profil Anda</a></b></div>";
	} else {
		$sydokpokok = "lengkap";
	}
	if ($usia < 101 && $as['pendidikan'] > 1 && $sydokpokok != 'kosong') {
		echo "<h4>Input Persyaratan Uji Kompetensi</h4>
				<span>Masukkan data persyaratan/pengalaman/portfolio Anda, beserta informasi yang dibutuhkan, kemudian klik <b><a class='btn btn-primary btn-xs'>Tambahkan</a></b></span> 
				<div class='row'>
					<div class='box-body'>
					  <div class='col-md-12'>
						<form role='form' method='POST' enctype='multipart/form-data'>
						<div class='form-group'>
							<label>Jenis Persyaratan</label>
							<select name='kategori' class='form-control' id='kategori'>
							<option value=''>--Pilih Kategori Persyaratan--</option>";
		$sqlselectkat = "SELECT * FROM `skema_persyaratan` WHERE `id_skemakkni`='$_GET[id]' ORDER BY `id` ASC";
		$tampilkat = $conn->query($sqlselectkat);
		while ($rr = $tampilkat->fetch_assoc()) {
			$sqlcekpersyaratan = "SELECT * FROM `asesi_doc` WHERE `id_asesi`='$as[no_pendaftaran]' AND `id_skemakkni`='$_GET[id]' AND `skema_persyaratan`='$rr[id]' AND `status`!='R' ORDER BY `id` DESC";
			$asesidocps = $conn->query($sqlcekpersyaratan);
			$jumpsx = $asesidocps->num_rows;
			$sqlcekpersyaratanp = "SELECT * FROM `asesi_doc` WHERE `id_asesi`='$as[no_pendaftaran]' AND `id_skemakkni`='$_GET[id]' AND `skema_persyaratan`='$rr[id]' AND `status`='R' ORDER BY `id` DESC";
			$asesidocpsp = $conn->query($sqlcekpersyaratanp);
			$jumpsxp = $asesidocpsp->num_rows;
			if ($jumpsx == 0) {
				if ($jumpsxp > 0) {
					echo "<option value='$rr[id]'>$rr[persyaratan] (perbaikan/revisi)</option>";
				} else {
					echo "<option value='$rr[id]'>$rr[persyaratan]</option>";
				}
			} else {
				echo "";
			}
		}
		echo "</select>";
		echo "</div>
					  </div>
					  <div class='col-md-6'>
						<div class='form-group'>
							<label>Nama Dokumen</label>
							<input type='text' name='nama_dokumen' class='form-control' placeholder='Nama Pengalaman' required>
						</div>
					  </div>
					  <div class='col-md-6'>
						<div class='form-group'>
							<label>Nomor Dokumen/SK</label>
							<input type='text' name='nomor_dokumen' class='form-control' placeholder='Nomor Dokumen' required>
						</div>
					  </div>
					  <div class='col-md-3'>
						<div class='form-group'>
							<label>Tahun Dokumen/SK</label>
							<select name='tahun_dokumen' class='form-control'>";
		$tahunskr = date("Y");
		$tahunnya = intval(trim(substr($as['tgl_lahir'], 0, 4))) + 10;
		while ($tahunnya <= $tahunskr) {
			echo "<option value='$tahunnya'>$tahunnya</option>";
			$tahunnya = $tahunnya + 1;
		}
		echo "</select>
						</div>
					  </div>
					  <div class='col-md-3'>
						<div class='form-group'>
							<label>Tanggal Dokumen/SK</label>
							<input type='date' name='tgl_dokumen' class='form-control' required>
						</div>
					  </div>
					  <div class='col-md-3'>
						<div class='form-group'>
							<label>File Pendukung</label>
							<label for='fileID'>
							<input type='file' name='file' id='fileID' accept='.pdf, image/*' onchange='readURL(this);'>
							<span>File pdf/jpg/png, maks. $upload_mb Mb</span>
						</div>
					  </div>
					  <div class='col-md-3'>
						<div class='form-group'>
						<button type='submit' class='btn btn-primary' name='tambahdocasesi'>Tambahkan</button>
						</form>
						</div>
					  </div>
					  <div class='col-md-12'>
						<div class='form-group'>
						<a href='#myModalLib" . $as['no_pendaftaran'] . "' class='btn btn-primary' data-toggle='modal' data-id='" . $as['id'] . "'>Tambahkan dokumen dari <em>Library</em> (pustaka anda)</a>
						</div>
					  </div>
				<script>
					$(function(){
								$(document).on('click','.edit-record',function(e){
									e.preventDefault();
									$('#myModal" . $as['no_pendaftaran'] . "').modal('show');
								});
						});
				</script>
				<!-- Modal -->
					<div class='modal fade' id='myModalLib" . $as['no_pendaftaran'] . "' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
						<div class='modal-dialog'>
							<div class='modal-content'>
								<div class='modal-header'>
									<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Tutup</span></button>
									<h4 class='modal-title' id='myModalLabel'><em>Library</em> (Pustaka) Dokumen " . $as['no_pendaftaran'] . "</h4>
								</div>
								<div class='modal-body'>
									<div style='overflow-x:auto; overflow-y:auto;'>
									<table id='example1' class='table table-bordered table-striped'>
										<thead><tr><th>Dokumen</th><th>Aksi</th></tr></thead>
										<tbody>";
		$sqlasesidocx = "SELECT * FROM `asesi_doc` WHERE `id_asesi`='$as[no_pendaftaran]' ORDER BY `id` DESC";
		$asesidocx = $conn->query($sqlasesidocx);
		while ($pmx = $asesidocx->fetch_assoc()) {
			echo "<tr><td width='50%'>Nama Dokumen : $pmx[nama_doc]<br>No. Dokumen : <b><a href='#myModalM" . $pmx['id'] . "' data-toggle='modal' data-id='" . $pmx['id'] . "'>$pmx[nomor_doc]</a></b><br>Tanggal Dok.: <b>" . tgl_indo($pmx['tgl_doc']) . "</b></td>
												<td width='50%'>
													<form role='form' method='POST' enctype='multipart/form-data'>
													<input type='hidden' name='nama_dokumen' value='$pmx[nama_doc]'>
													<input type='hidden' name='nomor_dokumen' value='$pmx[nomor_doc]'>
													<input type='hidden' name='tgl_dokumen' value='$pmx[tgl_doc]'>
													<input type='hidden' name='tahun_dokumen' value='$pmx[tahun_doc]'>
													<input type='hidden' name='file' value='$pmx[file]'>
													<div class='col-md-12'>
													<label>Jenis Persyaratan</label>";
			$sqlselectkat = "SELECT * FROM `skema_persyaratan` WHERE `id_skemakkni`='$_GET[id]' ORDER BY `id` ASC";
			$tampilkat = $conn->query($sqlselectkat);
			while ($rr = $tampilkat->fetch_assoc()) {
				echo "<br><input type='radio' name='kategori' id='options$rr[id]' value='$rr[id]' required>
															 $rr[persyaratan] &nbsp;&nbsp;&nbsp;";
			}
			echo "</div><div class='col-md-12'>
													<button type='submit' name='addfromlib' class='btn btn-primary btn-block'>Gunakan</button></div>
													</form>
												</td></tr>";
			echo "<script>
													$(function(){
																$(document).on('click','.edit-record',function(e){
																	e.preventDefault();
																	$('#myModalM" . $pmx['id'] . "').modal('show');
																});
														});
												</script>
												<!-- ModalM -->
													<div class='modal fade' id='myModalM" . $pmx['id'] . "' tabindex='-1' role='dialog' aria-labelledby='myModalMLabel' aria-hidden='true'>
														<div class='modal-dialog'>
															<div style='overflow-y:auto;'>
															<div class='modal-content'>
																<div class='modal-header'>
																	<h4 class='modal-title' id='myModalMLabel'>Library Dokumen Porfolio " . $pmx['nama_doc'] . "</h4>
																</div>";
			if (substr($pmx['file'], 0, 4) == "http") {
				echo "<div class='modal-body'><embed src='$pmx[file]' width='100%' height='500px'/>";
			} else {
				echo "<div class='modal-body'><embed src='foto_asesi/$pmx[file]' width='100%' height='500px'/>";
			}
			echo "</div>
																<div class='modal-footer'>
																	<em><font color='red'>klik di area gelap untuk menutup</font></em>
																</div>
															</div>
															</div>
														   </div><!-- End Overflow-->
													</div>
												<!-- ModalM End-->";
		}
		echo "</tbody>
									</table>
									</div>
								</div>
								<div class='modal-footer'>
									<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>
								</div>
							</div>
							</div>
					</div>
					<!-- Modal End -->
					</div>
				</div>
				<h1>FR-APL-01. FORMULIR PERMOHONAN SERTIFIKASI KOMPETENSI-xx</h1>
				<h2>Bagian 1: Rincian Data Pemohon Sertifikasi</h2>
				<h3>a. Data Pribadi</h3>
				<table id='example1' class='table table-bordered table-striped'>
					<tr><td width='25%'>Nama Lengkap</td><td>";
		$namanya = strtoupper($as['nama']);
		echo "$namanya</td></tr>
					<tr><td>Tempat/ Tgl. Lahir</td><td>";
		$ttl = ucwords(strtolower($as['tmp_lahir'])) . ", " . tgl_indo($as['tgl_lahir']);
		echo "$ttl</td></tr>
					<tr><td>Jenis Kelamin</td><td>";
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
		echo "$jeniskelamin</td></tr>
					<tr><td>Kebangsaan</td><td>";
		$kebangsaan = ucwords(strtolower($as['kebangsaan']));
		echo "$kebangsaan</td></tr>
					<tr><td>Alamat Rumah</td><td>";
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
		echo "$alamattampil</td></tr>
					<tr><td>No. Telp/ Email</td><td>Rumah : $as[nohp]<br />Kantor : $as[telp_kantor]<br />HP : $as[nohp]<br />Email : $as[email]</td></tr>
					<tr><td>Pendidikan Terakhir</td><td>";
		$sqlpendidikan = "SELECT * FROM `pendidikan` WHERE `id`='$as[pendidikan]'";
		$pendidikan = $conn->query($sqlpendidikan);
		$pdd = $pendidikan->fetch_assoc();
		$pendidikannyaa = $pdd['jenjang_pendidikan'];
		echo "$pendidikannyaa</td></tr>
				</table>
				<h3>b. Data Pekerjaan Sekarang</h3>
				<table id='example1' class='table table-bordered table-striped'>
					<tr><td width='25%'>Nama lembaga/ perusahaan</td><td>
					<form role='form' method='POST' enctype='multipart/form-data'>";
		$namakantornya = strtoupper($as['nama_kantor']);
		echo "<input type='text' name='nama_kantor' value='$namakantornya' class='form-control'></td></tr>
					<tr><td>Jabatan</td><td>";
		$sqlgetpekerjaan = "SELECT * FROM `pekerjaan` WHERE `id`='$as[pekerjaan]'";
		$getpekerjaan = $conn->query($sqlgetpekerjaan);
		$askrj = $getpekerjaan->fetch_assoc();
		$jabatannya = strtoupper($askrj['pekerjaan']);
		echo "<input type='text' name='jabatan' value='$as[jabatan]' class='form-control'></td></tr>
					<tr><td>Alamat</td><td>";
		echo "<input type='text' name='alamat_kantor' value='$as[alamat_kantor]' class='form-control'></td></tr>
					<tr><td>No. Telp./ Fax./ Email</td><td>";
		echo "Telp.: <input type='text' name='telp_kantor' value='$as[telp_kantor]' class='form-control'><br />Fax. : <input type='text' name='fax_kantor' value='$as[fax_kantor]' class='form-control'><br />Email : <input type='text' name='email_kantor' value='$as[email_kantor]' class='form-control'></td></tr>
				</table>
				<h2>Bagian 2: Data Sertifikasi</h2>
				<table id='example1' class='table table-bordered table-striped'>";
		$querycek2 = "SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_skemakkni`='$_GET[id]' AND `status_asesmen`!='K'";
		$resultc2 = $conn->query($querycek2);
		$gstas = $resultc2->fetch_assoc();
		echo "
					<tr><td rowspan='2'>Skema Sertifikasi<br />(KKNI/Okupasi/Klaster)</td><td>Judul</td><td>$gs[judul]</td></tr>
					<tr><td>Nomor</td><td>$gs[kode_skema]</td></tr>
					<tr><td colspan='2'>Tujuan Asesmen</td><td>
											<input type='radio' required='required' name='tujuan_sertifikasi' id='tujuansertifikasi1' value='Sertifikasi'";
		if ($gstas['tujuan_sertifikasi'] == "Sertifikasi") {
			echo " checked";
		} else {
			echo " checked";
		}
		echo ">
											Sertifikasi<br>
											<input type='radio' required='required' name='tujuan_sertifikasi' id='tujuansertifikasi2' value='Sertifikasi Ulang'";
		if ($gstas['tujuan_sertifikasi'] == "Sertifikasi Ulang") {
			echo " checked";
		} else {
			echo "";
		}
		echo ">
											Sertifikasi Ulang<br>
											<input type='radio' required='required' name='tujuan_sertifikasi' id='tujuansertifikasi3' value='Pengakuan Kompetensi Terkini (PKT)'";
		if ($gstas['tujuan_sertifikasi'] == "Pengakuan Kompetensi Terkini (PKT)") {
			echo " checked";
		} else {
			echo "";
		}
		echo ">
											Pengakuan Kompetensi Terkini (PKT)<br>
											<input type='radio' required='required' name='tujuan_sertifikasi' id='tujuansertifikasi4' value='Rekognisi Pembelajaran Lampau'";
		if ($gstas['tujuan_sertifikasi'] == "Rekognisi Pembelajaran Lampau") {
			echo " checked";
		} else {
			echo "";
		}
		echo ">
											Rekognisi Pembelajaran Lampau<br>
											<input type='radio' required='required' name='tujuan_sertifikasi' id='tujuansertifikasi5' value='Lainnya'";
		if ($gstas['tujuan_sertifikasi'] == "Lainnya") {
			echo " checked";
		} else {
			echo "";
		}
		echo ">
											Lainnya : <input type='text' name='tujuan_lainnya' class='form-control' value='$gstas[tujuan_lainnya]'/>
				</td></tr>
				</table>
				<h3>Daftar Unit Kompetensi sesuai kemasan:</h3>
				<div style='overflow-x:auto;'>
				<table id='example' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Kode Unit</th><th>Judul Unit</th><th>Jenis Standar<br />(Standar Khusus/ Standar Internasional/ SKKNI)</th></tr></thead>
					<tbody>";
		$no = 1;
		$sqllsp = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$gs[id]' ORDER BY `id` ASC";
		$lsp = $conn->query($sqllsp);
		while ($pmun = $lsp->fetch_assoc()) {
			echo "<tr class=gradeX><td>$no</td>";
			echo "</td><td><b>$pmun[kode_unit]</b></td><td>$pmun[judul]";
			/* $sqllspe0="SELECT * FROM `elemen_kompetensi` WHERE `id_unitkompetensi`='$pmun[id]' ORDER BY `id` ASC";
						$lspe0=$conn->query($sqllspe0);
						$numel=$lspe0->num_rows;
						if ($numel>0){
							echo "<br><b>Elemen Kompetensi:</b>";
							$sqllspe="SELECT * FROM `elemen_kompetensi` WHERE `id_unitkompetensi`='$pmun[id]' ORDER BY `id` ASC";
							$lspe=$conn->query($sqllspe);
							$noel=1;
							while ($pme=$lspe->fetch_assoc()){
								echo "<br>$noel. $pme[elemen_kompetensi]";
								$noel++;
							}
						} */
			echo "&nbsp;<input name='ukom[]' type='checkbox' value='$pmun[id]' ";
			$sqlgetukom = "SELECT * FROM `asesmen_unitkompetensi` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_skemakkni`='$_GET[id]' AND `id_unitkompetensi`='$pmun[id]'";
			$getukom = $conn->query($sqlgetukom);
			$ukomnya = $getukom->num_rows;
			//if ($ukomnya>0){
			echo "checked";
			//}
			echo " required/></td><td>";
			$sqlgetskkni = "SELECT * FROM `skkni` WHERE `id`='$pmun[id_skkni]'";
			$getskkni = $conn->query($sqlgetskkni);
			$skkx = $getskkni->fetch_assoc();
			echo $skkx['no_skkni'];
			echo "</td></tr>";
			$no++;
		}
		echo "</tbody>
				</table></div>
				<h2>Bagian 3: Bukti Kelengkapan Pemohon</h2>
				<h3>Bukti Persyaratan Dasar Pemohon</h3>
				<div class='row'>
				<div class='box-body'>
				<h3>Data Dokumen Persyaratan</h3>
				<div style='overflow-x:auto;'>
				<table id='table-example' class='table table-bordered table-striped'>
				<thead><tr><th>No.</th><th>Persyaratan<th>File Pendukung</th><th>Status</th></tr></thead>
				<tbody>";
		$no = 1;
		$sqlasesidoc = "SELECT * FROM `asesi_doc` WHERE `id_asesi`='$as[no_pendaftaran]' AND `id_skemakkni`='$_GET[id]' AND `status` != 'R' ORDER BY `id` DESC";
		$asesidoc = $conn->query($sqlasesidoc);
		$jumpm = $asesidoc->num_rows;
		while ($pm = $asesidoc->fetch_assoc()) {
			$hapusdocasesi = 'hapusdocasesi' . $pm['id'];
			$iddeldocasesi = 'iddeldocasesi' . $pm['id'];
			if (isset($_REQUEST[$hapusdocasesi])) {
				$cekdu = "SELECT * FROM `asesi_doc` WHERE `id`='" . $_POST[$iddeldocasesi] . "'";
				$result = $conn->query($cekdu);
				if ($result->num_rows > 0) {
					$sqlhapus = "DELETE FROM `asesi_doc` WHERE `id`='" . $_POST[$iddeldocasesi] . "'";
					$conn->query($sqlhapus);
					echo "<div class='alert alert-danger alert-dismissible'>
								<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
								<h4><i class='icon fa fa-check'></i> Hapus Data Sukses</h4>
								Anda Telah Berhasil Menghapus Data <b>Persyaratan Sertifikasi</b></div>";
					echo "<script>alert('Anda telah berhasil menghapus dokumen persyaratan uji kompetensi pada skema ini'); window.location = '../../media.php?module=updatesyarat&id=$_GET[id]&ida=$_SESSION[namauser]'</script>";
				} else {
					echo "<script>alert('Maaf Persyaratan Skema dengan tersebut Tidak Ditemukan');</script>";
				}
			}
			switch ($pm['status']) {
				default:
					$statusnya = "<font color='blue'><b>Menunggu<br />Persetujuan</b></font>";
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
			if ($pm['status'] == 'P') {
				echo "<br />
						<input type='hidden' name='iddeldocasesi" . $pm['id'], "' value='$pm[id]'>
						<input type='submit' name='hapusdocasesi" . $pm['id'] . "' class='btn btn-danger btn-xs' onclick='return confirmation()' title='Hapus' value='Hapus'>";
			}
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
								</div>";
			if (substr($pm['file'], 0, 4) == "http") {
				if (substr($pm['file'], -3) == "pdf") {
					echo "<div class='modal-body'><embed src='$pm[file]' width='100%' height='600px'/>";
				} else {
					echo "<div class='modal-body'><embed src='$pm[file]' width='100%'/>";
				}
			} else {
				if (substr($pm['file'], -3) == "pdf") {
					echo "<div class='modal-body'><embed src='foto_asesi/$pm[file]' width='100%' height='600px'/>";
				} else {
					echo "<div class='modal-body'><embed src='foto_asesi/$pm[file]' width='100%' height='600px'/>";
				}
			}
			echo "</div>
								<div class='modal-footer'>
									<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>
								</div>
							</div>
						</div>
					</div>";
		}
		echo "</tbody></table></div>";
		if ($jumpm <> $jumpsdev) {
			echo "<br><h2><font color='red'>Proses pendaftaran dapat dilakukan bila Anda telah mengunggah SEMUA DOKUMEN PERSYARATAN SKEMA.</font></h2>";
		}
		echo "<input type='hidden' name='biaya_asesmen' value='$totbiaya'>";
		// cek tandatangan digital
		$url =  "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
		$iddokumen = md5($url);
		$sqlcektandatangan = "SELECT * FROM `logdigisign` WHERE `id_dokumen`='$iddokumen' AND `penandatangan`='$namanya' ORDER BY `waktu` DESC";
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
		?>
		<div class="col-md-12">
			<label class="" for="">Tanda Tangan:</label>
			<br />
			<div id="sig"></div>
			<br />
			<button id="clear">Hapus Tanda Tangan</button>
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
<?php
		echo "<div align='left' class='col-md-6 col-sm-6 col-xs-6'>
					<a class='btn btn-danger' id=reset-validate-form href='?module=home'>Batal</a>
				</div>
				<div align='right' class='col-md-6 col-sm-6 col-xs-6'>";
		if ($jumpm == $jumpsdev) {
			echo "<input type='submit' class='btn btn-success' name='daftarasesmen' value='Proses Pendaftaran'>";
		} else {
			echo "<input type='submit' class='btn btn-success' name='daftarasesmen' title='Tombol aktif bila semua persyaratan telah diunggah' value='Proses Pendaftaran' disabled>";
		}
		echo "</div>
				</form>";
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
// Bagian Profil Asesi
elseif ($_GET['module'] == 'profil') {
	$sqllogin = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_SESSION[namauser]'";
	$login = $conn->query($sqllogin);
	$ketemu = $login->num_rows;
	$rowAgen = $login->fetch_assoc();
	echo "<!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
	  Profil Asesi
        <small></small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li class='active'>Profil Asesi</li>
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
		if (substr($rowAgen['foto'], 0, 4) == "http") {
			echo "<img class='profile-user-img img-responsive img-circle' src='$rowAgen[foto]' alt='User profile picture' style='width:128px; height:128px;'>";
		} else {
			echo "<img class='profile-user-img img-responsive img-circle' src='foto_asesi/$rowAgen[foto]' alt='User profile picture' style='width:128px; height:128px;'>";
		}
	}
	echo "<h3 class='profile-username text-center'>$rowAgen[nama]</h3>
              <p class='text-muted text-center'>$rowAgen[no_pendaftaran]</p>
			  <br>
			  <a href='?module=editprofil' class='btn btn-block btn-primary'>Ubah Profil</a>
			  <br>
			  <a href='?module=unggahfile' class='btn btn-block btn-primary'>Unggah Dokumen</a>
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
              <strong><h3 class='box-title'>Detail Data</h3></strong>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>
			<br>
              <strong class='col-md-3'>Nomor Registrasi (Pendaftaran)</strong>
               <span class='col-md-9 col-sm-12 col-xs-12'><b>$rowAgen[no_pendaftaran]</b></span>
			   <br><br>
              <strong class='col-md-3'>Nomor KTP</strong>
               <span class='col-md-9 col-sm-12 col-xs-12'>$rowAgen[no_ktp]</span>
			   <br><br>
              <strong class='col-md-3'>Nama Lengkap</strong>
               <span class='col-md-9 col-sm-12 col-xs-12'>$rowAgen[nama]</span>
			   <br><br>
              <strong class='col-md-3'>Tempat, Tanggal Lahir</strong>";
	$date = date_create($rowAgen['tgl_lahir']);
	$tanggal_lahir = date_format($date, 'd F Y');
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
               <span class='col-md-9 col-sm-12 col-xs-12'>$rowAgen[nohp]</span>
			   <br><br>
              <strong class='col-md-3'>Email</strong>
               <span class='col-md-9 col-sm-12 col-xs-12'><a href='mailto:$rowAgen[email]'>$rowAgen[email]</a></span>
			   <br><br>
              <strong class='col-md-3'>Pendidikan Terakhir</strong>
               <span class='col-md-9 col-sm-12 col-xs-12'>";
	$sqlpendidikan = "SELECT * FROM `pendidikan` WHERE `id`='$rowAgen[pendidikan]'";
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
// Bagian Ubah Profil
elseif ($_GET['module'] == 'editprofil') {
	$sqllogin = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_SESSION[namauser]'";
	$login = $conn->query($sqllogin);
	$ketemu = $login->num_rows;
	$rowAgen = $login->fetch_assoc();
	echo "<!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
	  Ubah Data Profil
        <small></small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li><a href='media.php?module=profil'>Profil Asesi</a></li>
        <li class='active'>Edit</li>
      </ol>
    </section>";
	function uploadFoto($file)
	{
		//$file_max_weight = 20097152; //Ukuran maksimal yg dibolehkan(2Mb)
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
		$nama = mysqli_escape_string($conn, addslashes(strip_tags(htmlspecialchars(strtoupper(str_replace("'", "`", $_POST['nama'])), ENT_QUOTES))));
		$no_ktp = mysqli_escape_string($conn, addslashes(strip_tags(htmlspecialchars($_POST['no_ktp'], ENT_QUOTES))));
		$tmp_lahir = mysqli_escape_string($conn, addslashes(strip_tags(htmlspecialchars($_POST['tmp_lahir'], ENT_QUOTES))));
		$alamat = mysqli_escape_string($conn, addslashes(strip_tags(htmlspecialchars(str_replace("'", "`", $_POST['alamat']), ENT_QUOTES))));
		$rt = mysqli_escape_string($conn, addslashes(strip_tags(htmlspecialchars($_POST['rt'], ENT_QUOTES))));
		$rw = mysqli_escape_string($conn, addslashes(strip_tags(htmlspecialchars($_POST['rw'], ENT_QUOTES))));
		$kelurahan = mysqli_escape_string($conn, addslashes(strip_tags(htmlspecialchars($_POST['kelurahan'], ENT_QUOTES))));
		$kecamatan = mysqli_escape_string($conn, addslashes(strip_tags(htmlspecialchars($_POST['kecamatan'], ENT_QUOTES))));
		$kota = $_POST['kota'];
		$propinsi = $_POST['propinsi'];
		$kodepos = mysqli_escape_string($conn, addslashes(strip_tags(htmlspecialchars($_POST['kodepos'], ENT_QUOTES))));
		$no_hp = mysqli_escape_string($conn, addslashes(strip_tags(htmlspecialchars($_POST['no_hp'], ENT_QUOTES))));
		$email = mysqli_escape_string($conn, addslashes(strip_tags(htmlspecialchars($_POST['email'], ENT_QUOTES))));
		$pendidikan_terakhir = $_POST['pendidikan_terakhir'];
		$prodi = mysqli_escape_string($conn, addslashes(strip_tags(htmlspecialchars($_POST['prodi'], ENT_QUOTES))));
		$tahun_lulus = mysqli_escape_string($conn, addslashes(strip_tags(htmlspecialchars($_POST['tahun_lulus'], ENT_QUOTES))));
		$jenis_kelamin = $_POST['jenis_kelamin'];
		$kebangsaan = $_POST['kebangsaan'];
		$lembaga_pendidikan = mysqli_escape_string($conn, addslashes(strip_tags(htmlspecialchars(str_replace("'", "`", $_POST['lembaga_pendidikan']), ENT_QUOTES))));
		$jabatan = mysqli_escape_string($conn, addslashes(strip_tags(htmlspecialchars($_POST['jabatan'], ENT_QUOTES))));
		$nama_kantor = mysqli_escape_string($conn, addslashes(strip_tags(htmlspecialchars(str_replace("'", "`", $_POST['nama_kantor']), ENT_QUOTES))));
		$alamat_kantor = mysqli_escape_string($conn, addslashes(strip_tags(htmlspecialchars(str_replace("'", "`", $_POST['alamat_kantor']), ENT_QUOTES))));
		$telp_kantor = mysqli_escape_string($conn, addslashes(strip_tags(htmlspecialchars($_POST['telp_kantor'], ENT_QUOTES))));
		$fax_kantor = mysqli_escape_string($conn, addslashes(strip_tags(htmlspecialchars($_POST['fax_kantor'], ENT_QUOTES))));
		$email_kantor = mysqli_escape_string($conn, addslashes(strip_tags(htmlspecialchars($_POST['email_kantor'], ENT_QUOTES))));
		$file = $_FILES['file'];
		if (empty($_FILES['file']['tmp_name']) || !is_uploaded_file($_FILES['file']['tmp_name'])) {
			$alamatfile = $rowAgen['foto'];
		} else {
			unlink('foto_asesi/' . $rowAgen['foto']);
			$alamatfile = uploadFoto($file);
		}
		$query = "UPDATE `asesi` SET `nama`='$nama', `tmp_lahir`='$tmp_lahir',`tgl_lahir`='$_POST[tgl_lahir]',`email`='$email',`nohp`='$no_hp',`no_ktp`='$no_ktp',`alamat`='$alamat',`RT`='$rt',`RW`='$rw',`kelurahan`='$kelurahan',`kecamatan`='$kecamatan',`kota`='$kota',`propinsi`='$propinsi',`kodepos`='$kodepos',`pendidikan`='$pendidikan_terakhir',`lembaga_pendidikan`='$lembaga_pendidikan',`prodi`='$prodi',`tahun_lulus`='$tahun_lulus',`jenis_kelamin`='$jenis_kelamin',`kebangsaan`='$kebangsaan',`foto`='$alamatfile', `pekerjaan`='$_POST[pekerjaan]',`jabatan`='$jabatan',`nama_kantor`='$nama_kantor',`alamat_kantor`='$alamat_kantor',`telp_kantor`='$telp_kantor',`fax_kantor`='$fax_kantor',`email_kantor`='$email_kantor' WHERE `no_pendaftaran` = '$_SESSION[namauser]'";
		if ($conn->query($query) == TRUE) {
			echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Ubah Data Sukses</h4>
			Anda Telah Berhasil Mengunggah Data <b>Profil Anda</b></div>";
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
			<h4><i class='icon fa fa-info'></i> Lengkapi Data Profil Anda</h4>
			Silahkan lengkapi data <b>Profil Anda</b> untuk dapat melanjutkan</div>";
	}
	echo "<div class='row'>
		    <div class='col-md-6'>			  
				<div class='col-md-12'>			  
					<div class='form-group'>
						<label for='fileID'>";
	if (empty($rowAgen['foto'])) {
		echo "<img class='profile-user-img img-responsive img-circle' src='images/default.jpg' alt='User profile picture' style='width:150px; height:150px;'>";
	} else {
		if (substr($rowAgen['foto'], 0, 4) == "http") {
			echo "<img class='profile-user-img img-responsive img-circle' src='$rowAgen[foto]' alt='User profile picture' style='width:150px; height:150px;'>";
		} else {
			echo "<img class='profile-user-img img-responsive img-circle' src='foto_asesi/$rowAgen[foto]' alt='User profile picture' style='width:150px; height:150px;'>";
		}
	}
	echo "</label>
						<input type='file' name='file' id='fileID' accept='image/*' onchange='readURL(this);'>
						<p class='help-block'>Ukuran foto maksimal 2 MB.</p>
					</div>
				</div>
				<div class='col-md-6'>  
					<div class='form-group'>
						<label>Nama</label>
						<input required type='text'  name='nama' value='$rowAgen[nama]' class='form-control'>
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
				<div class='col-md-6'>
					<div class='form-group'>
						<label>Pendidikan Terakhir</label>
						<select required class='form-control' name='pendidikan_terakhir'>";
	$sqlpendidikan = "SELECT * FROM `pendidikan` ORDER BY `id` ASC";
	$pendidikan = $conn->query($sqlpendidikan);
	while ($pdd = $pendidikan->fetch_assoc()) {
		echo "<option value='$pdd[id]'";
		if ($pdd['id'] == $rowAgen['pendidikan']) {
			echo "selected";
		}
		echo ">$pdd[jenjang_pendidikan]</option>";
	}
	echo "</select>
					</div>
				</div>
				<div class='col-md-12'>  
					<div class='form-group'>
						<label>Universitas/ Lembaga Pendidikan</label>
						<input required type='text'  name='lembaga_pendidikan' value='$rowAgen[lembaga_pendidikan]' class='form-control'>
					</div>
				</div>
				<div class='col-md-9'>  
					<div class='form-group'>
						<label>Jurusan/Program Studi</label>
						<input required type='text'  name='prodi' value='$rowAgen[prodi]' class='form-control'>
					</div>
				</div>
				<div class='col-md-3'>  
					<div class='form-group'>
						<label>Tahun Lulus</label>
						<input required type='text'  name='tahun_lulus' value='$rowAgen[tahun_lulus]' class='form-control'>
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
						<select required class='form-control' name='kebangsaan'>";
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
				<div class='col-md-6'>
					<div class='form-group'>
						<label>Nomor HP</label>
						<input required type='text' name='no_hp' value='$rowAgen[nohp]' class='form-control' maxlength='14'>
					</div>
				</div>
				<div class='col-md-6'>			
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
				<div class='col-md-3'>
					<div class='form-group'>
						<label>RT</label>
						<input required type='text' name='rt' value='$rowAgen[RT]' class='form-control'>
					</div>
				</div>
				<div class='col-md-3'>
					<div class='form-group'>
						<label>RW</label>
						<input required type='text' name='rw' value='$rowAgen[RW]'class='form-control'>
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
						<select name='propinsi' class='form-control' id='propinsi'>";
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
						<label>Pekerjaan/ Pengalaman Magang</label>
						<select  name='pekerjaan' class='form-control'>";
	$sqlgetpekerjaan = "SELECT * FROM `pekerjaan` ORDER BY `id` ASC";
	$getpekerjaan = $conn->query($sqlgetpekerjaan);
	while ($krj = $getpekerjaan->fetch_assoc()) {
		echo "<option value='$krj[id]' ";
		if ($krj['id'] == $rowAgen['pekerjaan']) {
			echo "selected";
		}
		echo ">$krj[pekerjaan]</option>";
	}
	echo "</select>
					</div>
				</div>
				<div class='col-md-12'>			
					<div class='form-group'>
						<label>Jabatan / Posisi saat magang</label>
						<input required type='text'  name='jabatan' value='$rowAgen[jabatan]' class='form-control'>
						</select>
					</div>
				</div>
				<div class='col-md-12'>			
					<div class='form-group'>
						<label>Nama Perusahaan</label>
						<input required type='text'  name='nama_kantor' value='$rowAgen[nama_kantor]' class='form-control'>
						</select>
					</div>
				</div>
				<div class='col-md-12'>			
					<div class='form-group'>
						<label>Alamat Perusahaan/Alamat Magang</label>
						<input required type='text'  name='alamat_kantor' value='$rowAgen[alamat_kantor]' class='form-control'>
						</select>
					</div>
				</div>
				<div class='col-md-6'>			
					<div class='form-group'>
						<label>Telp. Perusahaan/Magang</label>
						<input required type='text'  name='telp_kantor' value='$rowAgen[telp_kantor]' class='form-control'>
						</select>
					</div>
				</div>
				<div class='col-md-6'>			
					<div class='form-group'>
						<label>Fax. Perusahaan/Magang</label>
						<input required type='text'  name='fax_kantor' value='$rowAgen[fax_kantor]' class='form-control'>
						</select>
					</div>
				</div>
				<div class='col-md-12'>			
					<div class='form-group'>
						<label>Email Perusahaan/Magang</label>
						<input required type='text'  name='email_kantor' value='$rowAgen[email_kantor]' class='form-control'>
						</select>
					</div>
				</div>
              </div>
              <!-- /.box-body -->
	</div>
	<!-- /.row -->
              <div class='box-footer'>
                <center><button type='submit' class='btn btn-primary form-control' name='simpan'>Simpan</button></center>
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
// Bagian Konfirmasi Pembayaran
elseif ($_GET['module'] == 'konfirmbayar') {
	//include "modul/mod_password/password.php";
}
// Bagian Password
elseif ($_GET['module'] == 'password') {
	$sqlasesi = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_SESSION[namauser]'";
	$asesi = $conn->query($sqlasesi);
	$r = $asesi->fetch_array();
	if (isset($_REQUEST['ubahpassword'])) {
		$pass_lama = md5($_POST['pass_lama']);
		$pass_baru = md5($_POST['pass_baru']);
		if (empty($_POST['pass_baru']) or empty($_POST['pass_lama']) or empty($_POST['pass_ulangi'])) {
			echo "<div class='alert alert-danger alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Ubah Password Gagal</h4>
			Maaf Ubah Password Gagal!. <b>Anda harus mengisikan semua data pada form Ganti Password.</b></div>";
		} else {
			if (strlen($_POST['pass_baru']) < 8) {
				echo "<div class='alert alert-danger alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Ubah Password Gagal</h4>
				Maaf Ubah Password Gagal!. <b>Password minimal 8 karakter</b></div>";
			} else {
				// Apabila password lama cocok dengan password admin di database
				if ($pass_lama == $_POST['password']) {
					// Pastikan bahwa password baru yang dimasukkan sebanyak dua kali sudah cocok
					if ($_POST['pass_baru'] == $_POST['pass_ulangi']) {
						$query = "UPDATE `asesi` SET `password` = '$pass_baru' WHERE `no_pendaftaran`='$_SESSION[namauser]'";
						if ($conn->query($query) == TRUE) {
							echo "<div class='alert alert-success alert-dismissible'>
							<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
							<h4><i class='icon fa fa-check'></i> Ubah Password Sukses</h4>
							Ganti Kata Sandi (Password) Berhasil<b></b></div>";
							//die("<script>location.href = 'media.php?module=password'</script>");
						} else {
							echo "Error: " . $query . "<br>" . $conn->error;
						}
					} else {
						echo "<div class='alert alert-danger alert-dismissible'>
						<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
						<h4><i class='icon fa fa-warning'></i> Ubah Password Gagal</h4>
						Maaf Ubah Password Gagal!. <b>Password baru yang Anda masukkan sebanyak dua kali belum cocok.</b></div>";
					}
				} else {
					echo "<div class='alert alert-danger alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-warning'></i> Ubah Password Gagal</h4>
					Maaf Ubah Password Gagal!. <b>Anda salah memasukkan Password Lama Anda.</b></div>";
				}
			}
		}
	}
	echo "<!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
	  Ubah Kata Sandi (Password)
        <small></small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li class='active'>Ubah Password</li>
      </ol>
    </section>";
	echo "<!-- Main content -->
    <section class='content'>
      <div class='row'>
        <div class='col-xs-12'>
          <div class='box'>
            <div class='box-body'>
              <!-- form start -->
		<form role='form' method='POST' enctype='multipart/form-data'>
          	<input type='hidden' name='no_pendaftaran' value='$r[no_pendaftaran]'>
		  <input type='hidden' name='password' value='$r[password]'>
		  <div class='col-md-5 col-sm-5 col-xs-12'>
			<strong>Masukkan Password Lama (sebelumnya)</strong>
		  </div>
		  <div class='col-md-7 col-sm-7 col-xs-12'>
			<div class='form-group'>				
				<input class='form-control' type=text name='pass_lama'>
			</div>
		  </div>
		  <div class='col-md-5 col-sm-5 col-xs-12'>
			<strong>Masukkan Password Baru</strong>
		  </div>
		  <div class='col-md-7 col-sm-7 col-xs-12'>
			<div class='form-group'>				
				<input class='form-control' type=text name='pass_baru'>
				<span class='help-block'>minimal 8 karakter</span>
			</div>
		  </div>
		  <div class='col-md-5 col-sm-5 col-xs-12'>
			<strong>Masukkan Lagi Password Baru</strong>
		  </div>
		  <div class='col-md-7 col-sm-7 col-xs-12'>
			<div class='form-group'>				
				<input class='form-control' type=text name='pass_ulangi'>
				<span class='help-block'>minimal 8 karakter</span>
			</div>
		  </div>
		  <div align='left' class='col-md-6 col-sm-6 col-xs-6'>
				<a class='btn btn-danger' id=reset-validate-form href='?module=home'>Batal</a>
		  </div>
		  <div align='right' class='col-md-6 col-sm-6 col-xs-6'>
				<input class='btn btn-success' type=submit class='tombol' value='Ubah Password' name='ubahpassword'>
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
// Bagian Konfirmasi Pembayaran
elseif ($_GET['module'] == 'konfpay') {
	$sqlasesi = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_SESSION[namauser]'";
	$asesi = $conn->query($sqlasesi);
	$r = $asesi->fetch_array();
	$max_upload = (int)(ini_get('upload_max_filesize'));
	$max_post = (int)(ini_get('post_max_size'));
	$memory_limit = (int)(ini_get('memory_limit'));
	$upload_mb = min($max_upload, $max_post, $memory_limit);
	function uploadDoc($file)
	{
		//$file_max_weight = 20000000; //Ukuran maksimal yg dibolehkan( 20 Mb)
		$ok_ext = array('jpg', 'png', 'gif', 'jpeg', 'JPG', 'PNG', 'GIF', 'JPEG', 'pdf', 'PDF'); // ekstensi yang diijinkan
		$destination = "foto_asesibayar/"; // tempat buat upload
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
			//	echo "File terlalu besar.";
			//endif;
			else :
				echo "Tipe file ini tidak diperbolehkan.";
			endif;
		}
		return $alamatfile;
	}
	if (isset($_REQUEST['konfirmbayar'])) {
		$sqlskemaasesi2 = "SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$_SESSION[namauser]' AND `id`='$_POST[skema]'";
		$skemaasesi2 = $conn->query($sqlskemaasesi2);
		$ska2 = $skemaasesi2->fetch_assoc();
		$sqlcekbayar = "SELECT * FROM `asesi_pembayaran` WHERE `id_asesmen`='$_POST[skema]' AND `id_asesi`='$_SESSION[namauser]' AND `id_skemakkni`='$ska2[id_skemakkni]' AND `metode_bayar`='$_POST[pembayaran]' AND `jalur_bayar`='$_POST[jalurpembayaran]' AND `tujuan_rek`='$_POST[rekening]' AND `nominal`='$_POST[nominal]' AND `tgl_bayar`='$_POST[tgl_bayar]' AND `jam_bayar`='$_POST[jam_bayar]'";
		$cekbayar = $conn->query($sqlcekbayar);
		if ($cekbayar->num_rows == 0) {
			$file = $_FILES['file'];
			if (!empty($_FILES['file']['tmp_name']) || !is_uploaded_file($_FILES['file']['tmp_name'])) {
				$alamatfile = uploadDoc($file);
				$sqlinputbayar = "INSERT INTO `asesi_pembayaran`(`id_asesmen`,`id_asesi`, `id_skemakkni`, `metode_bayar`, `jalur_bayar`, `tujuan_rek`, `nominal`, `tgl_bayar`, `jam_bayar`, `file`) VALUES ('$_POST[skema]','$_SESSION[namauser]','$ska2[id_skemakkni]','$_POST[pembayaran]','$_POST[jalurpembayaran]','$_POST[rekening]','$_POST[nominal]','$_POST[tgl_bayar]','$_POST[jam_bayar]', '$alamatfile')";
				$inputbayar = $conn->query($sqlinputbayar);
			} else {
				$sqlinputbayar = "INSERT INTO `asesi_pembayaran`(`id_asesmen`,`id_asesi`, `id_skemakkni`, `metode_bayar`, `jalur_bayar`, `tujuan_rek`, `nominal`, `tgl_bayar`, `jam_bayar`) VALUES ('$_POST[skema]','$_SESSION[namauser]','$ska2[id_skemakkni]','$_POST[pembayaran]','$_POST[jalurpembayaran]','$_POST[rekening]','$_POST[nominal]','$_POST[tgl_bayar]','$_POST[jam_bayar]')";
				$inputbayar = $conn->query($sqlinputbayar);
			}
			$sqlupdatebayar = "UPDATE `asesi_asesmen` SET `biaya_asesmen`='K' WHERE `id`='$_POST[skema]'";
			$updatebayar = $conn->query($sqlupdatebayar);
			echo "<div class='alert alert-success alert-dismissible'>
		<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
		<h4><i class='icon fa fa-check'></i> Konfirmasi Pembayaran Berhasil</h4>
		Terimakasih, Anda telah berhasil <b>melakukan Konfirmasi Pembayaran.</b></div>";
		} else {
			echo "<div class='alert alert-danger alert-dismissible'>
		<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
		<h4><i class='icon fa fa-warning'></i> Konfirmasi Gagal</h4>
		Maaf <b>Data telah Anda konfirmasi sebelumnya</b></div>";
		}
	}
	if (isset($_REQUEST['hapuskonfpay'])) {
		$cekdu = "SELECT * FROM `asesi_pembayaran` WHERE `id`='$_POST[iddelkonfpay]'";
		$result = $conn->query($cekdu);
		$cnfbyr = $result->fetch_assoc();
		if ($result->num_rows != 0) {
			unlink("foto_asesibayar/" . $cnfbyr['file']);
			$sqlupdatebayar = "UPDATE `asesi_asesmen` SET `biaya_asesmen`='P' WHERE `id`='$cnfbyr[id_asesmen]'";
			$updatebayar = $conn->query($sqlupdatebayar);
			$conn->query("DELETE FROM `asesi_pembayaran` WHERE `id`='$_POST[iddelkonfpay]'");
			echo "<div class='alert alert-danger alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Hapus Data Sukses</h4>
			Anda Telah Berhasil Menghapus Data <b>Konfirmasi Pembayaran</b></div>";
			die("<script>location.href = '?module=konfpay'</script>");
		} else {
			echo "<script>alert('Maaf Data Konfirmasi Pembayaran dengan ID tersebut Tidak Ditemukan');</script>";
		}
	}
	echo "<!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
	  Konfirmasi Pembayaran Biaya Asesmen
        <small></small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li class='active'>Konfirmasi Pembayaran</li>
      </ol>
    </section>";
	echo "<!-- Main content -->
    <section class='content'>
      <div class='row'>
        <div class='col-xs-12'>
          <div class='box'>
            <div class='box-body'>";
	$sqlcekasesmen = "SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$_SESSION[namauser]'";
	$cekasesmen = $conn->query($sqlcekasesmen);
	$getasesmen = $cekasesmen->num_rows;
	if ($getasesmen == 0) {
		echo "<div class='alert alert-warning alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-warning'></i> Maaf, Anda belum melakukan Pendaftaran Skema Asesmen !</h4>
			Anda dapat melakukan pendaftaran melalui menu <b><a href='media.php?module=skema'>Lihat Skema Sertifikasi</a></b></div>";
	} else {
		$sqlcekasesmenx = "SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$_SESSION[namauser]' AND `biaya_asesmen`='P'";
		$cekasesmenx = $conn->query($sqlcekasesmenx);
		$getasesmenx = $cekasesmenx->num_rows;
		if ($getasesmenx == 0) {
			echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Semua pembayaran telah Anda konfirmasi</h4>
			Terimakasih, Anda telah <b>melakukan Konfirmasi Pembayaran.</b></div>";
			echo "<table id='table-example' class='table table-bordered table-striped'>
			<thead>
			<tr>
				<th>No.</th>
				<th>Data Pembayaran</th>
			</tr>
			</thead>
			<tbody>";
			$no = 1;
			$sqlskema = "SELECT * FROM `asesi_pembayaran` WHERE `id_asesi`='$_SESSION[namauser]'";
			$skema = $conn->query($sqlskema);
			while ($skm = $skema->fetch_assoc()) {
				if ($skm['status'] == 'P') {
					$stbayar = "<span class='text-blue'>Menunggu Validasi<span>";
				} else {
					$stbayar = "<span class='text-green'>Telah divalidasi</span>";
				}
				echo "<tr>
					<td>$no</td>
					<td>Waktu Pembayaran : <b>$skm[tgl_bayar] - $skm[jam_bayar]</b><br>Metode Pembayaran : <b>$skm[metode_bayar] - $skm[jalur_bayar]</b><br>Nominal : <b>$skm[nominal]</b><br>Status : <b>$stbayar</b>";
				if ($skm['file'] != "NULL") {
					echo " <a href='#myModalfoto' class='btn btn-primary btn-xs' data-toggle='modal' data-id='foto'><span class='glyphicon glyphicon-zoom-in' aria-hidden='true' title='Lihat Dokumen Bukti Pembayaran'></span></a>";
				}
				echo "&nbsp;<form role='form' method='POST' enctype='multipart/form-data'>
					<input type='hidden' name='iddelkonfpay' value='$skm[id]'>
					<input type='submit' name='hapuskonfpay' class='btn btn-danger btn-xs' onclick='return confirmation()' title='Hapus' value='Hapus'></form>";
				echo "<script>
				$(function(){
							$(document).on('click','.edit-record',function(e){
								e.preventDefault();
								$('#myModalfoto').modal('show');
							});
					});
				</script>
				<!-- Modal -->
				<div class='modal fade' id='myModalfoto' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
					<div class='modal-dialog'>
						<div class='modal-content'>
							<div class='modal-header'>
								<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Tutup</span></button>
								<h4 class='modal-title' id='myModalLabel'>Dokumen Bukti Pembayaran</h4>
							</div>
							<div class='modal-body'><embed src='foto_asesibayar/$skm[file]' width='100%' height='500px'/>
							</div>
							<div class='modal-footer'>
								<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>
							</div>
						</div>
						</div>
				</div>";
				echo "</td>
					</tr>";
				$no++;
			}
			echo "</tbody>
			</table>";
		} else {
			echo "<!-- form start -->
		<form role='form' method='POST' enctype='multipart/form-data'>
          	<input type='hidden' name='no_pendaftaran' value='$r[no_pendaftaran]'>
		  <div class='col-md-5 col-sm-5 col-xs-12'>
			<strong>Skema Sertifikasi yang Anda ikuti</strong>
		  </div>
		  <div class='col-md-7 col-sm-7 col-xs-12'>
			<div class='form-group'>				
				<select name='skema' class='form-control' required>
					<option>-- Pilih Skema --</option>";
			$sqlskemaasesi = "SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$_SESSION[namauser]'";
			$skemaasesi = $conn->query($sqlskemaasesi);
			while ($ska = $skemaasesi->fetch_assoc()) {
				$sqlskema = "SELECT * FROM `skema_kkni` WHERE `id`='$ska[id_skemakkni]'";
				$skema = $conn->query($sqlskema);
				$skm = $skema->fetch_assoc();
				echo "<option value='$ska[id]'>$skm[kode_skema] - $skm[judul]</option>";
			}
			echo "</select>
			</div>
		  </div>
		  <div class='col-md-5 col-sm-5 col-xs-12'>
			<strong>Metode Pembayaran</strong>
		  </div>
		  <div class='col-md-7 col-sm-7 col-xs-12'>
			<div class='form-group'>				
				<select name='pembayaran' id='pembayaran' class='form-control' required>
					<option>-- Pilih Metode Pembayaran --</option>
					<option value='Tunai'>Tunai</option>
					<option value='Transfer'>Transfer Rekening</option>
				</select>
			</div>
		  </div>
		  <div class='col-md-5 col-sm-5 col-xs-12'>
			<strong>Jalur Pembayaran</strong>
		  </div>
		  <div class='col-md-7 col-sm-7 col-xs-12'>
			<div class='form-group'>				
				<select name='jalurpembayaran' id='jalurpembayaran' class='form-control' required>
					<option></option>
				</select>
			</div>
		  </div>
		  <div class='col-md-5 col-sm-5 col-xs-12'>
			<strong>Tujuan Transfer</strong>
		  </div>
		  <div class='col-md-7 col-sm-7 col-xs-12'>
			<div class='form-group'>				
				<select name='rekening' id='rekening' class='form-control' required>
					<option></option>
				</select>
			</div>
		  </div>
		  <div class='col-md-5 col-sm-5 col-xs-12'>
			<strong>Nominal Pembayaran/Transfer</strong>
		  </div>
		  <div class='col-md-7 col-sm-7 col-xs-12'>
			<div class='form-group'>				
				<input class='form-control' type='text' name='nominal' id='nominal' required>
				<span class='help-block'>ketik hanya angka, misal: 750000</span>
			</div>
		  </div>
		  <div class='col-md-5 col-sm-5 col-xs-12'>
			<strong>Waktu Pembayaran</strong>
		  </div>
		  <div class='col-md-4 col-sm-4 col-xs-12'>
			<div class='form-group'>				
				<input class='form-control' type='date' name='tgl_bayar' required>
				<span class='help-block'>Tanggal Pembayaran/Transfer</span>
			</div>
		  </div>
		  <div class='col-md-3 col-sm-3 col-xs-12'>
			<div class='form-group'>				
				<input class='form-control' type='time' name='jam_bayar'>
				<span class='help-block'>Jam Pembayaran/Transfer</span>
			</div>
		  </div>
		  <div class='col-md-5 col-sm-5 col-xs-12'>
			<strong>Bukti Pembayaran</strong>
		  </div>
		  <div class='col-md-7 col-sm-7 col-xs-12'>
			<div class='form-group'>
				<label for='fileID'>
				<input type='file' name='file' id='fileID' accept='image/*, .pdf' onchange='readURL(this);'>
				<span class='help-block'>File pdf/jpg/png, maks. $upload_mb Mb</span>
			</div>
		  </div>
		  <div align='left' class='col-md-6 col-sm-6 col-xs-6'>
				<a class='btn btn-danger' id=reset-validate-form href='?module=home'>Batal</a>
		  </div>
		  <div align='right' class='col-md-6 col-sm-6 col-xs-6'>
				<input class='btn btn-success' type=submit class='tombol' value='Konfirmasi' name='konfirmbayar'>
		  </div>
		  </form>";
		}
	}
	echo "</div>
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
// Bagian Asesmen yang Diikuti
elseif ($_GET['module'] == 'asesmen') {
	$sqlasesi = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_SESSION[namauser]'";
	$asesi = $conn->query($sqlasesi);
	$r = $asesi->fetch_array();
	if (isset($_REQUEST['batalkanasesmen'])) {
		echo "<div class='alert alert-danger alert-dismissible'>
		<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
		<h4><i class='icon fa fa-warning'></i> Konfirmasi Gagal</h4>
		Maaf <b>Data telah Anda konfirmasi sebelumnya</b></div>";
	}
	echo "<!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
	  Skema Asesmen yang Anda Ikuti
        <small></small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li class='active'>Asesmen Saya</li>
      </ol>
    </section>";
	echo "<!-- Main content -->
    <section class='content'>
      <div class='row'>
        <div class='col-xs-12'>
          <div class='box'>
            <div class='box-body'>";
	$sqlcekasesmen = "SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$_SESSION[namauser]'";
	$cekasesmen = $conn->query($sqlcekasesmen);
	$getasesmen = $cekasesmen->num_rows;
	if ($getasesmen == 0) {
		echo "<div class='alert alert-warning alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-warning'></i> Maaf, Anda belum melakukan Pendaftaran Skema Asesmen !</h4>
			Anda dapat melakukan pendaftaran melalui menu <b><a href='media.php?module=skema'>Lihat Skema Sertifikasi</a></b></div>";
	} else {
		echo "<div style='overflow-x:auto;'>
			<table id='table-example' class='table table-bordered table-striped'>
			<thead>
				<tr>
					<th>No.</th>
					<th>Skema</th>
					<th>Aksi</th>
				</tr>
			</thead>
			<tbody>";
		$no = 1;
		// cek LSP Konstruksi
		$sqlceklspkonstruksi = "SELECT * FROM `user_pupr`";
		$ceklspkonsturksi = $conn->query($sqlceklspkonstruksi);
		$jumlspkonstruksi = $ceklspkonsturksi->num_rows;
		while ($asas = $cekasesmen->fetch_assoc()) {
			$sqlskema = "SELECT * FROM `skema_kkni` WHERE `id`='$asas[id_skemakkni]'";
			$skema = $conn->query($sqlskema);
			$skm = $skema->fetch_assoc();
			switch ($asas['status']) {
				case "P":
					switch ($asas['biaya_asesmen']) {
						case "P":
							$pesannya = "<span class='text-orange'><b>Menunggu Pembayaran</b></span>";
							if ($jumlspkonstruksi > 0) {
								$aksinya = "";
							} else {
								$aksinya = "<a class='btn btn-primary form-control' href='media.php?module=konfpay'>Konfirmasi Pembayaran</a>";
							}
							break;
						case "K":
							$pesannya = "<span class='text-green'><b>Telah dibayar</b></span>, menunggu <span class='text-orange'><b>Validasi Pembayaran</b></span>";
							$aksinya = "";
							break;
						case "L":
							$pesannya = "<span class='text-blue'><b>Menunggu Persetujuan</b></span>";
							$aksinya = "";
							break;
					}
					break;
				case "A":
					switch ($asas['status_asesmen']) {
						case "P":
							if ($jumlspkonstruksi > 0) {
								$pesannya = "<span class='text-green'><b>Diterima dan dijadwalkan</b></span><br/><span class='text-orange'><b>Menunggu Pembayaran</b></span>";
								$aksinya = "<a class='btn btn-primary form-control' href='media.php?module=konfpay'>Konfirmasi Pembayaran</a><br/>
								<a class='btn btn-primary form-control' href='media.php?module=jadwal&idj=$asas[id_jadwal]'>Lihat Jadwal</a>";
							} else {
								$pesannya = "<span class='text-green'><b>Diterima dan dijadwalkan</b></span>";
								$aksinya = "<a class='btn btn-primary form-control' href='media.php?module=jadwal&idj=$asas[id_jadwal]'>Lihat Jadwal</a>";
							}
							break;
						case "K":
							$pesannya = "<span class='text-black'><b>Selesai</b>, dan dinyatakan</span> <span class='text-green'><b>KOMPETEN</b></span>";
							$aksinya = "<a class='btn btn-success form-control' href='media.php?module=sertifikat'>Lihat Data Sertifikat</a>";
							break;
						case "BK":
							$pesannya = "<span class='text-black'><b>Selesai</b>, dan dinyatakan</span> <span class='text-red'><b>BELUM KOMPETEN</b></span>";
							$aksinya = "<a class='btn btn-warning form-control' href='media.php?module=banding&idass=$asas[id]&ida=$_SESSION[namauser]&idj=$asas[id_jadwal]'>Ajukan Banding</a>";
							break;
						case "TL":
							$pesannya = "<span class='text-black'><b>Selesai</b>, dan dinyatakan</span> <span class='text-red'><b>BELUM KOMPETEN DAN PERLU TINDAK LANJUT</b></span>";
							$aksinya = "<a class='btn btn-default form-control' href='media.php?module=pelatihan'>Ajukan Pelatihan</a>";
							break;
					}
					break;
				case "R":
					$pesannya = "<span class='text-red'><b>Ditolak</b></span>";
					$aksinya = "";
					break;
			}
			// cek kelengkapan persyaratan
			$sqlceksyarat = "SELECT * FROM `skema_persyaratan` WHERE `id_skemakkni`='$asas[id_skemakkni]'";
			$ceksyarat = $conn->query($sqlceksyarat);
			$syaratkurang = "";
			while ($syx = $ceksyarat->fetch_assoc()) {
				$sqlceksylengkap = "SELECT * FROM `asesi_doc` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_skemakkni`='$asas[id_skemakkni]' AND `skema_persyaratan`='$syx[id]'";
				$ceksylengkap = $conn->query($sqlceksylengkap);
				$sylengkap = $ceksylengkap->num_rows;
				if ($sylengkap > 0) {
					$syaratkurang = "";
				} else {
					$syaratkurang = "<br><font color='red'>Kurang dokumen " . $syx['persyaratan'] . "</font>";
				}
			}
			echo "<tr>
					<td>$no</td>
					<td>$skm[kode_skema] - $skm[judul]<br>Status : $pesannya $syaratkurang</td>
					<td>$aksinya<br><a href='media.php?module=updatesyarat&id=$asas[id_skemakkni]&ida=$_SESSION[namauser]' class='btn btn-success btn-block'>Lengkapi Persyaratan Pendaftaran APL-01</a>
					</td>
				</tr>";
			$no++;
		}
		echo "</tbody>
			</table></div>";
	}
	echo "</div>
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
// Bagian Unggah Persyaratan
elseif ($_GET['module'] == 'unggahsyarat') {
	$sqlasesi = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_SESSION[namauser]'";
	$asesi = $conn->query($sqlasesi);
	$r = $asesi->fetch_array();
	echo "<!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
	  Unggah Dokumen Persyaratan Pokok Asesi
        <small></small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li class='active'>Unggah Dokumen</li>
      </ol>
    </section>";
	echo "<!-- Main content -->
    <section class='content'>
      <div class='row'>
        <div class='col-xs-12'>
          <div class='box'>";
	// get setting persyaratan
	/* $pesyaratanpk="";
			$sqlgetpersyaratanpokok="SELECT * FROM `asesi_persyaratanpokok` WHERE `aktif`='Y' AND `wajib`='Y' ORDER BY `id` ASC";
			$getpersyaratanpokok=$conn->query($sqlgetpersyaratanpokok);
			$jumperspokok=$getpersyaratanpokok->num_rows;
			$indeksprs=1;
			$skortot=0;
			while ($pspk=$getpersyaratanpokok->fetch_assoc()){
				if ($indeksprs==$jumperspokok){
					$pesyaratanpk=$persyaratanpk." dan ".$pspk['persyaratan'];
				}else{
					if ($indeksprs>1){
						$pesyaratanpk=$persyaratanpk.", ".$pspk['persyaratan'];
					}else{
						$pesyaratanpk=$pspk['persyaratan'];
					}
				}
				$indeksprs++;
				switch ($pspk['shortcode']){
					case "foto":
						if (empty($r['foto']){
							$skor=1;
						}else{
							$skor=0;
						}
					break;	
					case "ktp":
						if (empty($r['ktp']){
							$skor=1;
						}else{
							$skor=0;
						}
					break;	
					case "kk":
						if (empty($r['kk']){
							$skor=1;
						}else{
							$skor=0;
						}
					break;
					case "ijazah":
						if (empty($r['ijazah']){
							$skor=1;
						}else{
							$skor=0;
						}
					break;
					case "transkrip":
						if (empty($r['transkrip']){
							$skor=1;
						}else{
							$skor=0;
						}
					break;
					case "suket":
						if (empty($r['suket']){
							$skor=1;
						}else{
							$skor=0;
						}
					break;
					case "cv":
						if (empty($r['cv']){
							$skor=1;
						}else{
							$skor=0;
						}
					break;
					default:
						$skor=0;
					break;
				}
				$skortot=$skortot+$skor;
			} */
	if ($skortot > 0) {
		echo "<div class='alert alert-warning alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-warning'></i> Maaf, Anda belum mengunggah Dokumen Pokok dengan lengkap!</h4>
				Silahkan unggah dokumen pokok Anda ($pesyaratanpk) melalui menu <b><a href='media.php?module=unggahfile'>Profil Anda</a></b></div>";
	}
	echo "<div class='box-header'>
              <h3 class='box-title'>Skema Sertifikasi Profesi yang Anda ikuti</h3>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>
				<table id='example1' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Skema Sertifikasi</th><th>Persyaratan</th><th>Biaya</th></tr></thead>
					<tbody>";
	$no = 1;
	$sqlasesiikut = "SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$_SESSION[namauser]'";
	$asesiikut = $conn->query($sqlasesiikut);
	while ($pm0 = $asesiikut->fetch_assoc()) {
		$sqllsp = "SELECT * FROM `skema_kkni` WHERE `id`='$pm0[id_skemakkni]'";
		$lsp = $conn->query($sqllsp);
		$pm = $lsp->fetch_assoc();
		echo "<tr class=gradeX><td>$no</td><td><b>$pm[kode_skema]</b><br>$pm[judul]</td>";
		$sqlgetsyarat = "SELECT * FROM `asesi_doc` WHERE `id_skemakkni`='$pm[id]' AND `id_asesi`='$_SESSION[namauser]'";
		$getsyarat = $conn->query($sqlgetsyarat);
		$numsyarat = $getsyarat->num_rows;
		if ($numsyarat == 0) {
			echo "<td><a href='?module=syarat&id=$pm[id]' class='btn btn-primary btn-xs'>Unggah Dokumen</a></td>";
		} else {
			echo "<td><b>( $numsyarat ) Dokumen terunggah</b><br><a href='?module=syarat&id=$pm[id]' class='btn btn-success btn-xs'>Lengkapi/Tambah Dokumen</a></td>";
		}
		echo "<td>";
		$sqlbiaya = "SELECT SUM(`nominal`) AS `jumnominal` FROM `biaya_sertifikasi` WHERE `id_skemakkni`='$pm[id]'";
		$biayanya = $conn->query($sqlbiaya);
		while ($bys = $biayanya->fetch_assoc()) {
			$tampilbiaya = "Rp. " . number_format($bys['jumnominal'], 0, ",", ".");
			echo "<b>$tampilbiaya</b>";
		}
		echo "</td></tr>";
		$no++;
	}
	echo "</tbody></table>
			</div>
		  </div>
	</section>
    <!-- /.content -->";
}
// Bagian Biaya
elseif ($_GET['module'] == 'biaya') {
	$sqlasesi = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_SESSION[namauser]'";
	$asesi = $conn->query($sqlasesi);
	$r = $asesi->fetch_array();
	echo "<!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
	  Biaya Asesmen
        <small></small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li class='active'>Biaya</li>
      </ol>
    </section>";
	echo "<!-- Main content -->
    <section class='content'>
      <div class='row'>
        <div class='col-xs-12'>
          <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Biaya Skema Sertifikasi Profesi yang Anda ikuti</h3>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>
				<table id='example1' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Skema Sertifikasi</th><th>Biaya</th></tr></thead>
					<tbody>";
	$no = 1;
	$sqlasesiikut = "SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$_SESSION[namauser]'";
	$asesiikut = $conn->query($sqlasesiikut);
	while ($pm0 = $asesiikut->fetch_assoc()) {
		$sqllsp = "SELECT * FROM `skema_kkni` WHERE `id`='$pm0[id_skemakkni]'";
		$lsp = $conn->query($sqllsp);
		$pm = $lsp->fetch_assoc();
		echo "<tr class=gradeX><td>$no</td><td><b>$pm[kode_skema]</b><br>$pm[judul]</td>";
		echo "<td>";
		$sqlbiaya = "SELECT SUM(`nominal`) AS `jumnominal` FROM `biaya_sertifikasi` WHERE `id_skemakkni`='$pm[id]'";
		$biayanya = $conn->query($sqlbiaya);
		while ($bys = $biayanya->fetch_assoc()) {
			$tampilbiaya = "Rp. " . number_format($bys['jumnominal'], 0, ",", ".");
			echo "<b>$tampilbiaya</b>";
		}
		echo "</td></tr>";
		$no++;
	}
	echo "</tbody></table>
	   </div>
	</div>
	<div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Pembayaran Biaya Skema Sertifikasi Profesi</h3>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>
		<p>Pembayaran biaya asesmen sertifikasi dapat dilakukan secara tunai maupun transfer, dengan beberapa cara sebagai berikut:</p>";
	$sqlcarabayar = "SELECT DISTINCT `metode` FROM `rekeningbayar` ORDER BY `metode` ASC";
	$carabayar = $conn->query($sqlcarabayar);
	while ($cb = $carabayar->fetch_assoc()) {
		echo "<h3>$cb[metode]</h3>
			<div class='row'>";
		$sqlcarabayar2 = "SELECT * FROM `rekeningbayar` WHERE `metode`='$cb[metode]'";
		$carabayar2 = $conn->query($sqlcarabayar2);
		$no = 1;
		while ($cb2 = $carabayar2->fetch_assoc()) {
			if ($cb2['metode'] != 'Tunai') {
				echo "<div class='col-md-4'>
					<div class='box box-widget widget-user-2'>
					<div class='widget-user-header bg-gray'>
              				<div class='widget-user-image'>
                				<img class='img-circle' src='images/$cb2[logo]' alt='cb2[logo]'>
              				</div>
              				<!-- /.widget-user-image -->
              				<h3 class='widget-user-username'>Transfer Rekening</h3>
              				<h5 class='widget-user-desc'>$cb2[bank]</h5>
            				</div>
					<div class='box-footer no-padding'>
						<ul class='nav nav-stacked'>
						<li><a>Nomor Rekening <span class='pull-right'>$cb2[norek]</span></a></li>
						<li><a>Atas Nama <span class='pull-right'>$cb2[atasnama]</span></a></li>
						</ul>
            				</div>
					</div>
					</div>";
				$no++;
			} else {
				echo "<div class='col-md-12'>
					<div class='box box-widget widget-user-2'>
					<div class='widget-user-header bg-orange'>
              				<h3 class='widget-user-username'>$cb2[norek]</h3>
              				<h5 class='widget-user-desc'>Melalui $cb2[atasnama]</h5>
            				</div>
					</div>
					</div>";
			}
		}
		echo "</div>";
	}
	echo "</div>
	</div>
    </section>
    <!-- /.content -->";
}
// Bagian Sertifikat
elseif ($_GET['module'] == 'sertifikat') {
	$sqlasesi = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_SESSION[namauser]'";
	$asesi = $conn->query($sqlasesi);
	$r = $asesi->fetch_array();
	echo "<!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
	  Sertifikat Kompetensi
        <small></small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li class='active'>Sertifikat</li>
      </ol>
    </section>";
	echo "<!-- Main content -->
    <section class='content'>
      <div class='row'>
        <div class='col-xs-12'>
          <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Data Sertifikat Profesi Anda</h3>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>
				<table id='example1' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Skema Sertifikasi</th><th>Nomor Sertifikat</th></tr></thead>
					<tbody>";
	$no = 1;
	$sqlasesiikut = "SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$_SESSION[namauser]'";
	$asesiikut = $conn->query($sqlasesiikut);
	while ($pm0 = $asesiikut->fetch_assoc()) {
		$sqllsp = "SELECT * FROM `skema_kkni` WHERE `id`='$pm0[id_skemakkni]'";
		$lsp = $conn->query($sqllsp);
		$pm = $lsp->fetch_assoc();
		//$sqlcert="SELECT * FROM `sertifikat` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_asesmen`='$pm0[id]'";
		//$cert=$conn->query($sqlcert);
		//$cr=$cert->fetch_assoc();
		$tglsertifikat = tgl_indo($pm0['masa_berlaku']);
		if ($pm0['no_serisertifikat'] == '') {
			$sertifikat = "<span class='text-red'>Belum ada data</span>";
		} else {
			$sertifikat = "<span class='text-green'>No. Seri : $pm0[no_serisertifikat]<br>No. Lisensi : $pm0[no_lisensi]</span>";
		}
		echo "<tr class=gradeX><td>$no</td><td><b>$pm[kode_skema]</b><br>$pm[judul]</td>";
		echo "<td>";
		echo "<b>$sertifikat<br>Masa Berlaku : $tglsertifikat</b><br><a href='foto_asesicert/$pm0[foto_sertifikat]' class='btn btn-success' target='_blank' title='Unduh Sertifikat Kompetensi No. $pm0[no_serisertifikat]'>Unduh Sertifikat</a>";
		echo "</td></tr>";
		$no++;
	}
	echo "</tbody></table>
	   </div>
	</div>
	<div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Informasi Sertifikat Kompetensi Profesi</h3>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>
		<p>Sertifikat Kompetensi dikeluarkan oleh Badan Nasional Sertifikasi Profesi (BNSP), dimana proses dilakukan oleh BNSP Pusat. Data sertifikat di atas adalah data permohonan sertifikat yang telah dikirimkan ke BNSP Pusat.</p>";
	echo "</div>
	</div>
    </section>
    <!-- /.content -->";
}
// Bagian Jadwal
elseif ($_GET['module'] == 'jadwal') {
	$sqlasesi = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_SESSION[namauser]'";
	$asesi = $conn->query($sqlasesi);
	$r = $asesi->fetch_array();
	echo "<!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
	  Jadwal Asesmen Anda
        <small></small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li class='active'>Jadwal Asesmen</li>
      </ol>
    </section>";
	echo "<!-- Main content -->
    <section class='content'>
      <div class='row'>
        <div class='col-xs-12'>
          <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Jadwal Asesmen Sertifikasi Profesi</h3>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>
			<div style='overflow-x:auto;'>
				<table id='example1' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Skema Sertifikasi, Asesor, Waktu dan Tempat</th><th>Aksi</th></tr></thead>
					<tbody>";
	$no = 1;
	if (!empty($_GET['idj'])) {
		$sqlasesiikut = "SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_jadwal`='$_GET[idj]'";
	} else {
		$sqlasesiikut = "SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_jadwal`!=''";
	}
	$asesiikut = $conn->query($sqlasesiikut);
	while ($pm0 = $asesiikut->fetch_assoc()) {
		$sqllsp = "SELECT * FROM `skema_kkni` WHERE `id`='$pm0[id_skemakkni]'";
		$lsp = $conn->query($sqllsp);
		$pm = $lsp->fetch_assoc();
		$sqllsp3 = "SELECT * FROM `jadwal_asesmen` WHERE `id`='$pm0[id_jadwal]'";
		$lsp3 = $conn->query($sqllsp3);
		$pm3 = $lsp3->fetch_assoc();
		$sqllsp4 = "SELECT * FROM `tuk` WHERE `id`='$pm3[tempat_asesmen]'";
		$lsp4 = $conn->query($sqllsp4);
		$pm4 = $lsp4->fetch_assoc();
		$sqlwil1 = "SELECT * FROM `data_wilayah` WHERE `id_wil`='$pm4[id_wilayah]'";
		$wilayah1 = $conn->query($sqlwil1);
		$wil1 = $wilayah1->fetch_assoc();
		$sqlwil2 = "SELECT * FROM `data_wilayah` WHERE `id_wil`='$wil1[id_induk_wilayah]'";
		$wilayah2 = $conn->query($sqlwil2);
		$wil2 = $wilayah2->fetch_assoc();
		$sqlwil3 = "SELECT * FROM `data_wilayah` WHERE `id_wil`='$wil2[id_induk_wilayah]'";
		$wilayah3 = $conn->query($sqlwil3);
		$wil3 = $wilayah3->fetch_assoc();
		$noasr = 1;
		$getasesor = $conn->query("SELECT * FROM `jadwal_asesor` WHERE `id_jadwal`='$pm3[id]'");
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
			//echo "<b>$noasr. $namaasesor</b><br>";
			$noasr++;
		}
		echo "<tr class=gradeX><td>$no</td><td>Skema : <b>$pm[judul]</b><br>Asesor : <b>$namaasesor</b><br>Tanggal : <b>" . tgl_indo($pm3['tgl_asesmen']) . "</b> Pukul <b>$pm3[jam_asesmen]</b><br>Tempat :<br><b>$pm4[nama]<br>$pm4[alamat]<br>$pm4[kelurahan] $wil1[nm_wil] $wil2[nm_wil] $wil3[nm_wil]</b></td>
						<td width='30%'><a href='asesor/form-apl-01.php?ida=$_SESSION[namauser]&idj=$pm0[id_jadwal]' class='btn btn-primary btn-xs btn-block'>Unduh FORM-APL-01</a><br>
						<a href='?module=portfolio&ida=$_SESSION[namauser]&idj=$pm0[id_jadwal]' class='btn btn-primary btn-xs btn-block'>Unggah Portfolio</a><br>";
		$sqlcekjawaban = "SELECT * FROM `asesi_apl02` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_skemakkni`='$pm3[id_skemakkni]' AND `id_jadwal`='$pm0[id_jadwal]'";
		$cekjawaban = $conn->query($sqlcekjawaban);
		$jjw = $cekjawaban->num_rows;
		$cekjenisapl02 = "SELECT `apl02` FROM `skema_kkni` WHERE `id`='$pm3[id_skemakkni]'";
		$jenisapl02 = $conn->query($cekjenisapl02);
		$jnsapl02 = $jenisapl02->fetch_assoc();
		if ($jnsapl02['apl02'] == 'elemen') {
			if ($jjw == 0) {
				echo "<a href='?module=form-apl-02-el&ida=$_SESSION[namauser]&idj=$pm0[id_jadwal]' class='btn btn-primary btn-xs btn-block'>Input Asesmen Mandiri (FORM-APL-02)</a>";
			} else {
				echo "<a href='?module=form-apl-02-el&ida=$_SESSION[namauser]&idj=$pm0[id_jadwal]' class='btn btn-success btn-xs btn-block'>Telah Asesmen Mandiri (FORM-APL-02)</a><br>";
				echo "<a href='asesor/form-apl-02-el.php?ida=$_SESSION[namauser]&idj=$pm0[id_jadwal]' class='btn btn-success btn-xs btn-block'>Unduh Form Asesmen Mandiri (FORM-APL-02)</a><br>";
			}
			echo "<a href='?module=form-fr-ak-01&ida=$_SESSION[namauser]&idj=$pm0[id_jadwal]' class='btn btn-success btn-xs btn-block'>Input Persetujuan Asesmen (FORM-AK-01)</a><br>";
			echo "<a href='?module=form-fr-ak-02&ida=$_SESSION[namauser]&idj=$pm0[id_jadwal]' class='btn btn-success btn-xs btn-block'>Input Persetujuan Rekaman Asesmen (FORM-AK-02)</a><br>";
			echo "<a href='asesor/form-fr-ia-02.php?ida=$_SESSION[namauser]&idj=$pm0[id_jadwal]' class='btn btn-success btn-xs btn-block'>Unduh Tugas Praktik Demonstrasi (FORM-IA-02)</a><br>";
			echo "<a href='?module=form-ia-03&ida=$_SESSION[namauser]&idj=$pm0[id_jadwal]' class='btn btn-success btn-xs btn-block'>Buka Pertanyaan Pendukung Observasi (FORM-IA-03)</a><br>";
			echo "<a href='?module=form-ia-05&ida=$_SESSION[namauser]&idj=$pm0[id_jadwal]' class='btn btn-success btn-xs btn-block'>Buka Tes Tulis Pilihan Ganda (FORM-IA-05)</a><br>";
			echo "<a href='?module=form-ia-06&ida=$_SESSION[namauser]&idj=$pm0[id_jadwal]' class='btn btn-success btn-xs btn-block'>Buka Tes Tulis Esai (FORM-IA-06)</a><br>";
		} else {
			if ($jjw == 0) {
				echo "<a href='?module=form-apl-02&ida=$_SESSION[namauser]&idj=$pm0[id_jadwal]' class='btn btn-primary btn-xs btn-block'>Input Asesmen Mandiri (FORM-APL-02)</a>";
			} else {
				echo "<a href='?module=form-apl-02&ida=$_SESSION[namauser]&idj=$pm0[id_jadwal]' class='btn btn-success btn-xs btn-block'>Telah Asesmen Mandiri (FORM-APL-02)</a><br>";
				echo "<a href='asesor/form-apl-02.php?ida=$_SESSION[namauser]&idj=$pm0[id_jadwal]' class='btn btn-success btn-xs btn-block'>Unduh Form Asesmen Mandiri (FORM-APL-02)</a><br>";
			}
			echo "<a href='?module=form-fr-ak-01&ida=$_SESSION[namauser]&idj=$pm0[id_jadwal]' class='btn btn-success btn-xs btn-block'>Input Persetujuan Asesmen (FORM-AK-01)</a><br>";
			echo "<a href='?module=form-fr-ak-02&ida=$_SESSION[namauser]&idj=$pm0[id_jadwal]' class='btn btn-success btn-xs btn-block'>Input Persetujuan Rekaman Asesmen (FORM-AK-02)</a><br>";
			echo "<a href='asesor/form-fr-ia-02.php?ida=$_SESSION[namauser]&idj=$pm0[id_jadwal]' class='btn btn-success btn-xs btn-block'>Unduh Tugas Praktik Demonstrasi (FORM-IA-02)</a><br>";
			echo "<a href='?module=form-ia-03&ida=$_SESSION[namauser]&idj=$pm0[id_jadwal]' class='btn btn-success btn-xs btn-block'>Buka Pertanyaan Pendukung Observasi (FORM-IA-03)</a><br>";
			echo "<a href='?module=form-ia-05&ida=$_SESSION[namauser]&idj=$pm0[id_jadwal]' class='btn btn-success btn-xs btn-block'>Buka Tes Tulis Pilihan Ganda (FORM-IA-05)</a><br>";
			echo "<a href='?module=form-ia-06&ida=$_SESSION[namauser]&idj=$pm0[id_jadwal]' class='btn btn-success btn-xs btn-block'>Buka Tes Tulis Esai (FORM-IA-06)</a><br>";
		}
		switch ($pm0['status_asesmen']) {
			case "K":
				echo "<a href='?module=form-fr-ak-03&ida=$_SESSION[namauser]&idj=$pm0[id_jadwal]' class='btn btn-success btn-xs btn-block'>Input Umpan Balik dan Catatan Asesmen (FORM-AK-03)</a><br>";
				break;
			case "BK":
				echo "<a href='?module=form-fr-ak-03&ida=$_SESSION[namauser]&idj=$pm0[id_jadwal]' class='btn btn-success btn-xs btn-block'>Input Umpan Balik dan Catatan Asesmen (FORM-AK-03)</a><br>";
				break;
			default:
				echo "<a href='?module=form-fr-ak-03&ida=$_SESSION[namauser]&idj=$pm0[id_jadwal]' class='btn btn-success btn-xs btn-block'>Input Umpan Balik dan Catatan Asesmen (FORM-AK-03)</a><br>";
				break;
		}
		if ($pm0['status_asesmen'] == 'BK') {
			echo "<a href='?module=banding&idass=$pm0[id]&ida=$_SESSION[namauser]&idj=$pm0[id_jadwal]' class='btn btn-success btn-xs btn-block'>Ajukan Banding Asesmen (FORM-AK-04)</a><br>";
			echo "<a href='asesor/form-fr-ak-04.php?idass=$pm0[id]&ida=$_SESSION[namauser]&idj=$pm0[id_jadwal]' class='btn btn-warning btn-xs btn-block'>Unduh Form Banding Asesmen (FORM-AK-04)</a>";
		}
		echo "</td></tr>";
		$no++;
	}
	echo "</tbody></table>
			</div>
		     </div>
		  </div>
		</div>
            </div>
	</section>
    <!-- /.content -->";
}
// Bagian Banding Asesmen ================================================================================================================
elseif ($_GET['module'] == 'banding') {
	$sqljadwaltuk = "SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
	$jadwaltuk = $conn->query($sqljadwaltuk);
	$jd = $jadwaltuk->fetch_assoc();
	$tanggalasesmen = tgl_indo($jd['tgl_asesmen']);
	$sqllsp = "SELECT * FROM `skema_kkni` WHERE `id`='$jd[id_skemakkni]'";
	$lsp = $conn->query($sqllsp);
	$pm = $lsp->fetch_assoc();
	if (isset($_REQUEST['simpan'])) {
		$sqlcekgetak01 = "SELECT * FROM `asesmen_ak04` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_jadwal`='$_GET[idj]'";
		$cekgetak01 = $conn->query($sqlcekgetak01);
		$cekak01 = $cekgetak01->num_rows;
		$tglsekarang = date("Y-m-d");
		if ($cekak01 > 0) {
			$folderPath = "foto_tandatangan/";
			if (empty($_POST['signed'])) {
				echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Permohonan Banding Anda berhasil disimpan</h4>
				Terimakasih, Anda telah melakukan <b>Permohonan Banding Asesmen atas hasil asesmen Anda pada Skema $pm[judul].</b><br>
				<a class='btn btn-warning form-control' href='?module=jadwal'>Kembali</a></div>";
				$sqlinputak01 = "UPDATE `asesmen_ak04` SET `pertanyaan1`='$_POST[pertanyaan1]',`pertanyaan2`='$_POST[pertanyaan2]',`pertanyaan3`='$_POST[pertanyaan3]',`alasan_banding`='$_POST[alasan_banding]' WHERE `id_asesi`='$_SESSION[namauser]' AND `id_skemakkni`='$jd[id_skemakkni]' AND `id_jadwal`='$_GET[idj]'";
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
				$sqlinputdigisign = "INSERT INTO `logdigisign`(`id_dokumen`, `id_asesi`, `id_skema`, `url_ditandatangani`, `nama_dokumen`, `penandatangan`, `file`, `ip`) VALUES ('$iddokumen','$_SESSION[namauser]','$_GET[id]'	,'$escaped_url','FR-APL-01. FORMULIR PERMOHONAN SERTIFIKASI KOMPETENSI','$rowAgen[nama]','$file','$alamatip')";
				$conn->query($sqlinputdigisign);
				echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Permohonan Banding Anda berhasil disimpan</h4>
				Terimakasih, Anda telah melakukan <b>Permohonan Banding Asesmen atas hasil asesmen Anda pada Skema $pm[judul] dan tanda tangan Anda telah ditambahkan</b><br>
				<a class='btn btn-warning form-control' href='?module=jadwal'>Kembali</a></div>";
				$sqlinputak01 = "UPDATE `asesmen_ak04` SET `pertanyaan1`='$_POST[pertanyaan1]',`pertanyaan2`='$_POST[pertanyaan2]',`pertanyaan3`='$_POST[pertanyaan3]',`alasan_banding`='$_POST[alasan_banding]' WHERE `id_asesi`='$_SESSION[namauser]' AND `id_skemakkni`='$jd[id_skemakkni]' AND `id_jadwal`='$_GET[idj]'";
				$conn->query($sqlinputak01);
			}
		} else {
			$folderPath = "foto_tandatangan/";
			if (empty($_POST['signed'])) {
				echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Permohonan Banding Anda berhasil disimpan</h4>
				Terimakasih, Anda telah melakukan <b>Permohonan Banding Asesmen atas hasil asesmen Anda pada Skema $pm[judul].</b><br>
				<a class='btn btn-warning form-control' href='?module=jadwal'>Kembali</a></div>";
				$sqlinputak01 = "INSERT INTO `asesmen_ak04`(`id_asesi`, `id_skemakkni`, `id_jadwal`, `pertanyaan1`, `pertanyaan2`, `pertanyaan3`, `alasan_banding`, `waktu`) VALUES ('$_SESSION[namauser]','$jd[id_skemakkni]','$_GET[idj]','$_POST[pertanyaan1]','$_POST[pertanyaan2]','$_POST[pertanyaan3]','$_POST[alasan_banding]','$tglsekarang')";
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
				$sqlinputdigisign = "INSERT INTO `logdigisign`(`id_dokumen`, `id_asesi`, `id_skema`, `url_ditandatangani`, `nama_dokumen`, `penandatangan`, `file`, `ip`) VALUES ('$iddokumen','$_SESSION[namauser]','$_GET[id]'	,'$escaped_url','FR-APL-01. FORMULIR PERMOHONAN SERTIFIKASI KOMPETENSI','$rowAgen[nama]','$file','$alamatip')";
				$conn->query($sqlinputdigisign);
				echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Permohonan Banding Anda berhasil disimpan</h4>
				Terimakasih, Anda telah melakukan <b>Permohonan Banding Asesmen atas hasil asesmen Anda pada Skema $pm[judul] dan tanda tangan Anda telah ditambahkan</b><br>
				<a class='btn btn-warning form-control' href='?module=jadwal'>Kembali</a></div>";
				if (isset($_POST['pertanyaan1'])) {
					$pertanyaan1 = $_POST['pertanyaan1'];
				} else {
					$pertanyaan1 = '0';
				}
				if (isset($_POST['pertanyaan2'])) {
					$pertanyaan2 = $_POST['pertanyaan2'];
				} else {
					$pertanyaan2 = '0';
				}
				if (isset($_POST['pertanyaan3'])) {
					$pertanyaan3 = $_POST['pertanyaan3'];
				} else {
					$pertanyaan3 = '0';
				}
				if (isset($_POST['alasan_banding'])) {
					$alasan_banding = $_POST['alasan_banding'];
				} else {
					$alasan_banding = '';
				}
				$sqlinputak01 = "INSERT INTO `asesmen_ak04`(`id_asesi`, `id_skemakkni`, `id_jadwal`, `pertanyaan1`, `pertanyaan2`, `pertanyaan3`, `alasan_banding`, `waktu`) VALUES ('$_SESSION[namauser]','$jd[id_skemakkni]','$_GET[idj]','$pertanyaan1','$pertanyaan2','$pertanyaan3','$alasan_banding','$tglsekarang')";
				$conn->query($sqlinputak01);
			}
		}
	}
	$sqlcekbanding = "SELECT * FROM `asesmen_ak04` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_jadwal`='$_GET[idj]'";
	$cekbanding = $conn->query($sqlcekbanding);
	$cbd = $cekbanding->fetch_assoc();
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
	$sqllogin = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_SESSION[namauser]'";
	$login = $conn->query($sqllogin);
	$rowAgen = $login->fetch_assoc();
	$sqlgetjadwal = "SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
	$getjadwal = $conn->query($sqlgetjadwal);
	$jd = $getjadwal->fetch_assoc();
	echo "<!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
        Formulir Banding Hasil Asesmen
        <small>Input Data</small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li class='active'>Banding Hasil Asesmen</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class='content'>
      <div class='row'>
        <div class='col-xs-12'>
          <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>FR.AK.04. BANDING ASESMEN</h3>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>
			<div style='overflow-x:auto;'>
			<form role='form' method='POST' enctype='multipart/form-data'>
				<table id='example1' class='table table-bordered table-striped'>
					<tbody>";
	$no = 1;
	echo "<tr><td>Nama Asesi</td><td colspan='4'>$rowAgen[nama]</td>";
	echo "<tr><td>Nama Asesor</td><td colspan='4'>$namaasesor</td>";
	echo "<tr><td>Tanggal Asesmen</td><td colspan='4'>$tanggalasesmen</td>";
	echo "<tr><td colspan='4'><b>Jawablah dengan Ya atau Tidak pertanyaan-pertanyaan berikut ini :</b></td>";
	echo "<tr><td colspan='3'>Apakah Proses Banding telah dijelaskan kepada Anda?</td><td colspan='2'>
					<label>
						<input type='radio' name='pertanyaan1' id='pertanyaan1_1' value='1'";
	if ($cbd['pertanyaan1'] == '1') {
		echo " checked";
	} else {
		echo "";
	}
	echo ">YA &nbsp;&nbsp;&nbsp;
					</label>
					<label>
						<input type='radio' name='pertanyaan1' id='pertanyaan1_2' value='0'";
	if ($cbd['pertanyaan1'] == '0') {
		echo " checked";
	} else {
		echo "";
	}
	echo ">TIDAK
					</label>
					</td>";
	echo "<tr><td colspan='3'>Apakah Anda telah mendiskusikan Banding dengan Asesor?</td><td colspan='2'>
					<label>
						<input type='radio' name='pertanyaan2' id='pertanyaan2_1' value='1'";
	if ($cbd['pertanyaan2'] == '1') {
		echo " checked";
	} else {
		echo "";
	}
	echo ">YA &nbsp;&nbsp;&nbsp;
					</label>
					<label>
						<input type='radio' name='pertanyaan2' id='pertanyaan2_2' value='0'";
	if ($cbd['pertanyaan2'] == '0') {
		echo " checked";
	} else {
		echo "";
	}
	echo ">TIDAK
					</label>
					</td>";
	echo "<tr><td colspan='3'>Apakah Anda mau melibatkan 'orang lain' membantu Anda dalam Proses Banding?</td><td colspan='2'>
					<label>
						<input type='radio' name='pertanyaan3' id='pertanyaan3_1' value='1'";
	if ($cbd['pertanyaan3'] == '1') {
		echo " checked";
	} else {
		echo "";
	}
	echo ">YA &nbsp;&nbsp;&nbsp;
					</label>
					<label>
						<input type='radio' name='pertanyaan3' id='pertanyaan3_2' value='0'";
	if ($cbd['pertanyaan3'] == '0') {
		echo " checked";
	} else {
		echo "";
	}
	echo ">TIDAK
					</label>
					</td>";
	echo "<tr><td colspan='5'>Banding ini diajukan atas Keputusan Asesmen yang dibuat terhadap Skema Sertifikasi (Kualifikasi/ Klaster/ Okupasi) berikut :</td>";
	echo "<tr><td>Skema Sertifikasi</td><td colspan='4'>: $pm[judul]</td>";
	echo "<tr><td>No. Skema Sertifikasi</td><td colspan='4'>: $pm[kode_skema]</td>";
	echo "<tr><td colspan='5'>Banding ini diajukan atas alasan sebagai berikut :<br/>
					<textarea name='alasan_banding' class='form-control' placeholder='isi alasan Anda mengajukan banding di sini'>";
	if (!empty($cbd['alasan_banding'])) {
		echo "$cbd[alasan_banding]";
	} else {
		echo "";
	}
	echo "</textarea></td>";
	echo "<tr><td colspan='5'>Anda mempunyai hak mengajukan banding jika Anda menilai proses asesmen tidak sesuai SOP dan tidak memenuhi Prinsip Asesmen.</td>";
	echo "</tbody></table>
			</div>";
	// cek tandatangan digital
	$url = "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
	$iddokumen = md5($url);
	$sqlcektandatangan = "SELECT * FROM `logdigisign` WHERE `id_dokumen`='$iddokumen' ORDER BY `id` DESC LIMIT 1";
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
					<div class='col-md-4 col-sm-12 col-xs-12'>
							<a class='btn btn-danger form-control' id=reset-validate-form href='?module=jadwal'>Kembali</a>
					</div>
					<div class='col-md-4 col-sm-12 col-xs-12'>
							<a href='asesor/form-fr-ak-04.php?idass=$_GET[idass]&ida=$_SESSION[namauser]&idj=$_GET[idj]' class='btn btn-primary form-control'>Unduh Formulir</a>
					</div>
					<div class='col-md-4 col-sm-12 col-xs-12'>
							<button type='submit' class='btn btn-success form-control' name='simpan'>Simpan Jawaban</button>
					</div>
				</div>
			</form>";
	echo "</div>
		  </div>
		</div>
	  </div>
	</section>";
}
// Bagian SMS Notifikasi
elseif ($_GET['module'] == 'sms') {
	$sqlasesi = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_SESSION[namauser]'";
	$asesi = $conn->query($sqlasesi);
	$r = $asesi->fetch_array();
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
              <h3 class='box-title'>Notifikasi SMS yang dikirim ke Anda</h3>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>
			<div style='overflow-x:auto;'>
				<table id='example1' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Waktu Pengiriman</th><th>Isi Pesan</th><th>Status</th></tr></thead>
					<tbody>";
	$no = 1;
	$sqlasesiikut = "SELECT * FROM `sentitems` WHERE `DestinationNumber`='$r[nohp]' ORDER BY `SendingDateTime` DESC";
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
	$sqlasesiikut2 = "SELECT * FROM `outbox` WHERE `DestinationNumber`='$r[nohp]' ORDER BY `SendingDateTime` DESC";
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
// Bagian Unggah Dokumen
elseif ($_GET['module'] == 'unggahfile') {
	$sqlasesi = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_SESSION[namauser]'";
	$getasesi = $conn->query($sqlasesi);
	$as = $getasesi->fetch_assoc();
	$sqlasesidoc = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$as[no_pendaftaran]'";
	$asesidoc = $conn->query($sqlasesidoc);
	$pm = $asesidoc->fetch_assoc();
	$sqlpendidikan = "SELECT * FROM `pendidikan` WHERE `id`='$pm[pendidikan]'";
	$pendidikan = $conn->query($sqlpendidikan);
	$pdd = $pendidikan->fetch_assoc();
	$max_upload = (int)(ini_get('upload_max_filesize'));
	$max_post = (int)(ini_get('post_max_size'));
	$memory_limit = (int)(ini_get('memory_limit'));
	$upload_mb = min($max_upload, $max_post, $memory_limit);
	function uploadDoc2($file)
	{
		//direktori file
		$vdir_upload = "foto_asesi/";
		$vfile_upload = $vdir_upload . $fupload_name;
		$tipe_file   = $_FILES['file']['type'];
		//Simpan gambar dalam ukuran sebenarnya
		move_uploaded_file($_FILES["file"]["tmp_name"], $vfile_upload);
	}
	function uploadDoc($file)
	{
		//$file_max_weight = 20000000; //Ukuran maksimal yg dibolehkan( 20 Mb)
		$ok_ext = array('jpg', 'png', 'gif', 'jpeg', 'JPG', 'PNG', 'GIF', 'JPEG', 'pdf', 'PDF'); // ekstensi yang diijinkan
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
				//if( $file_weight <= $file_max_weight ):
				$fileNewName = $time_stamp . md5($file_name_no_ext[0] . microtime()) . "." . $file_extension;
				$alamatfile = $fileNewName;
				if (move_uploaded_file($file['tmp_name'], $destination . $fileNewName)) :
				//echo" File uploaded !";
				else :
				//echo "can't upload file.";
				endif;
			//else:
			//	echo "File terlalu besar.";
			//endif;
			else :
				echo "Tipe file ini tidak diperbolehkan.";
			endif;
		}
		return $alamatfile;
	}
	if (isset($_REQUEST['tambahdocasesi'])) {
		$file = $_FILES['file'];
		switch ($_POST['kategori']) {
			case "foto":
				$ketdata = "Pas Foto";
				// Apabila ada file yang diupload
				if (!empty($_FILES['file']['tmp_name']) || !is_uploaded_file($_FILES['file']['tmp_name'])) {
					$alamatfile = uploadDoc($file);
					$query = "UPDATE `asesi` SET `foto`='$alamatfile' WHERE `no_pendaftaran`='$as[no_pendaftaran]'";
				}
				$conn->query($query);
				echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Tambah Data Sukses</h4>
			Anda Telah Berhasil Mengunggah Dokumen <b>$ketdata</b></div>";
				die("<script>location.href = '?module=unggahfile'</script>");
				break;
			case "ktp":
				$ketdata = "KTP No. $pm[no_ktp]";
				// Apabila ada file yang diupload
				if (!empty($_FILES['file']['tmp_name']) || !is_uploaded_file($_FILES['file']['tmp_name'])) {
					$alamatfile = uploadDoc($file);
					$query = "UPDATE `asesi` SET `ktp`='$alamatfile' WHERE `no_pendaftaran`='$as[no_pendaftaran]'";
				}
				$conn->query($query);
				echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Tambah Data Sukses</h4>
			Anda Telah Berhasil Mengunggah Dokumen <b>$ketdata</b></div>";
				die("<script>location.href = '?module=unggahfile'</script>");
				break;
			case "ijazah":
				$ketdata = "Ijazah ($pdd[jenjang_pendidikan] $pm[prodi])";
				// Apabila ada file yang diupload
				if (!empty($_FILES['file']['tmp_name']) || !is_uploaded_file($_FILES['file']['tmp_name'])) {
					$alamatfile = uploadDoc($file);
					$query = "UPDATE `asesi` SET `ijazah`='$alamatfile' WHERE `no_pendaftaran`='$as[no_pendaftaran]'";
				}
				$conn->query($query);
				echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Tambah Data Sukses</h4>
			Anda Telah Berhasil Mengunggah Dokumen <b>$ketdata</b></div>";
				die("<script>location.href = '?module=unggahfile'</script>");
				break;
			case "transkrip":
				$ketdata = "Transkrip Nilai/Daftar Nilai";
				// Apabila ada file yang diupload
				if (!empty($_FILES['file']['tmp_name']) || !is_uploaded_file($_FILES['file']['tmp_name'])) {
					$alamatfile = uploadDoc($file);
					$query = "UPDATE `asesi` SET `transkrip`='$alamatfile' WHERE `no_pendaftaran`='$as[no_pendaftaran]'";
				}
				$conn->query($query);
				echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Tambah Data Sukses</h4>
			Anda Telah Berhasil Mengunggah Dokumen <b>$ketdata</b></div>";
				die("<script>location.href = '?module=unggahfile'</script>");
				break;
			case "kk":
				$ketdata = "Kartu Keluarga";
				// Apabila ada file yang diupload
				if (!empty($_FILES['file']['tmp_name']) || !is_uploaded_file($_FILES['file']['tmp_name'])) {
					$alamatfile = uploadDoc($file);
					$query = "UPDATE `asesi` SET `kk`='$alamatfile' WHERE `no_pendaftaran`='$as[no_pendaftaran]'";
				}
				$conn->query($query);
				echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Tambah Data Sukses</h4>
			Anda Telah Berhasil Mengunggah Dokumen <b>$ketdata</b></div>";
				die("<script>location.href = '?module=unggahfile'</script>");
				break;
			case "suket":
				$ketdata = "Surat Keterangan Kerja/Magang dari $pm[nama_kantor]";
				// Apabila ada file yang diupload
				if (!empty($_FILES['file']['tmp_name']) || !is_uploaded_file($_FILES['file']['tmp_name'])) {
					$alamatfile = uploadDoc($file);
					$query = "UPDATE `asesi` SET `suket`='$alamatfile' WHERE `no_pendaftaran`='$as[no_pendaftaran]'";
				}
				$conn->query($query);
				echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Tambah Data Sukses</h4>
			Anda Telah Berhasil Mengunggah Dokumen <b>$ketdata</b></div>";
				die("<script>location.href = '?module=unggahfile'</script>");
				break;
			case "cv":
				$ketdata = "Riwayat Hidup/ Curriculum Vitae (CV)";
				// Apabila ada file yang diupload
				if (!empty($_FILES['file']['tmp_name']) || !is_uploaded_file($_FILES['file']['tmp_name'])) {
					$alamatfile = uploadDoc($file);
					$query = "UPDATE `asesi` SET `cv`='$alamatfile' WHERE `no_pendaftaran`='$as[no_pendaftaran]'";
				}
				$conn->query($query);
				echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Tambah Data Sukses</h4>
			Anda Telah Berhasil Mengunggah Dokumen <b>$ketdata</b></div>";
				die("<script>location.href = '?module=unggahfile'</script>");
				break;
			case "sertifikat":
				$ketdata = "Sertifikat Pelatihan";
				// Apabila ada file yang diupload
				if (!empty($_FILES['file']['tmp_name']) || !is_uploaded_file($_FILES['file']['tmp_name'])) {
					$alamatfile = uploadDoc($file);
					$query = "UPDATE `asesi` SET `sertifikat`='$alamatfile' WHERE `no_pendaftaran`='$as[no_pendaftaran]'";
				}
				$conn->query($query);
				echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Tambah Data Sukses</h4>
			Anda Telah Berhasil Mengunggah Dokumen <b>$ketdata</b></div>";
				die("<script>location.href = '?module=unggahfile'</script>");
				break;
		}
	}
	if (isset($_REQUEST['hapusdocasesi'])) {
		$cekdu = "SELECT * FROM `asesi` WHERE `" . $_POST['iddeldocasesi'] . "`='$_POST[iddeldocasesifile]' AND `no_pendaftaran`='$as[no_pendaftaran]'";
		$result = $conn->query($cekdu);
		if ($result->num_rows != 0) {
			unlink("foto_asesi/" . $_POST['iddeldocasesifile']);
			$conn->query("UPDATE `asesi` SET `" . $_POST['iddeldocasesi'] . "`='' WHERE `no_pendaftaran`='$as[no_pendaftaran]'");
			echo "<div class='alert alert-danger alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Hapus Data Sukses</h4>
			Anda Telah Berhasil Menghapus Data <b>Persyaratan</b></div>";
			die("<script>location.href = '?module=unggahfile'</script>");
		} else {
			echo "<script>alert('Maaf Persyaratan dengan tersebut Tidak Ditemukan');</script>";
		}
	}
	echo "<!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
        Data Pokok Asesi
        <small>Input Data</small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li class='active'>Unggah Data Pokok Asesi</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class='content'>
      <div class='row'>
        <div class='col-xs-12'>
		<div class='box-body'>";
	echo "<h3>Input Dokumen Pokok Asesi</h3>
				<span>Masukkan data pokok Anda, kemudian klik <b><a class='btn btn-primary btn-xs'>Tambahkan</a></b></span> 
			<form role='form' method='POST' enctype='multipart/form-data'>
			<div class='row'>
				<div class='box-body'>
				  <div class='col-md-6'>
					<div class='form-group'>
						<label>Jenis Dokumen Persyaratan</label>
						<select name='kategori' class='form-control' id='kategori'>";
	$sqlgetpersyaratanpokok = "SELECT * FROM `asesi_persyaratanpokok` WHERE `aktif`='Y' ORDER BY `id` ASC";
	$getpersyaratanpokok = $conn->query($sqlgetpersyaratanpokok);
	while ($pspk = $getpersyaratanpokok->fetch_assoc()) {
		echo "<option value='$pspk[shortcode]'>$pspk[persyaratan] ";
		if ($pspk['wajib'] == 'Y') {
			echo "(wajib)";
		} else {
			echo "(tambahan)";
		}
		echo "</option>";
	}
	echo "</select>";
	echo "</div>
				  </div>
				  <div class='col-md-3'>
					<div class='form-group'>
						<label>File Pendukung</label>
						<label for='fileID'>
						<input type='file' name='file' id='fileID' accept='image/*, .pdf' onchange='readURL(this);'>
						<span>File pdf/jpg/png, maks. $upload_mb Mb</span>
					</div>
				  </div>
				  <div class='col-md-3'>
					<div class='form-group'>
					<button type='submit' class='btn btn-primary' name='tambahdocasesi'>Tambahkan</button>
					</div>
				  </div>
				</div>
				</div>
			</form>
			<div class='row'>
		    <div class='box-body'>
			<h3>Data Dokumen Persyaratan</h3>
			<div style='overflow-x:auto;'>
			<table id='table-example' class='table table-bordered table-striped'>
			<thead><tr><th>No.</th><th>Persyaratan<th>File Pendukung</th><th>Status</th></tr></thead>
			<tbody>";
	// -------------------------------------data dokumen foto -----------------------------------
	$sqlgetpersyaratanpokok = "SELECT * FROM `asesi_persyaratanpokok` WHERE `aktif`='Y' ORDER BY `id` ASC";
	$getpersyaratanpokok = $conn->query($sqlgetpersyaratanpokok);
	$nodoc = 1;
	while ($pspk = $getpersyaratanpokok->fetch_assoc()) {
		$shorcodenya = $pspk['shortcode'];
		echo "<tr><td>$nodoc</td><td><b>Dokumen $pspk[persyaratan]</b> (";
		if ($pspk['wajib'] == 'Y') {
			echo "wajib";
		} else {
			echo "tambahan";
		}
		echo ")<br>Dokumen : <b><a href='#myModal" . $shorcodenya . "' data-toggle='modal' data-id='" . $shorcodenya . "'>$pm[$shorcodenya]</a></b></td><td>";
		if (!empty($pm[$shorcodenya])) {
			echo "<a class='btn btn-success btn-xs'><span class='glyphicon glyphicon-ok' aria-hidden='true' title='Dokumen Berhasil Diunggah'></span></a>";
			echo "&nbsp;<a href='#myModal" . $shorcodenya . "' class='btn btn-primary btn-xs' data-toggle='modal' data-id='$shorcodenya'><span class='glyphicon glyphicon-zoom-in' aria-hidden='true' title='Lihat/Unduh Dokumen'></span></a>";
		} else {
			if ($pspk['wajib'] == 'Y') {
				echo "<span class='text-red'>Tidak ada dokumen</span>";
			} else {
				echo "<span class='text-blue'>Tidak wajib</span>";
			}
		}
		echo "</td><td>";
		if (!empty($pm[$shorcodenya])) {
			echo "<br /><form role='form' method='POST' enctype='multipart/form-data'>
					<input type='hidden' name='iddeldocasesi' value='$shorcodenya'>
					<input type='hidden' name='iddeldocasesifile' value='" . $pm[$shorcodenya] . "'>
					<input type='submit' name='hapusdocasesi' class='btn btn-danger btn-xs' onclick='return confirmation()' title='Hapus' value='Hapus'></form>";
			echo "</td></tr>";
			echo "<script>
					$(function(){
								$(document).on('click','.edit-record',function(e){
									e.preventDefault();
									$('#myModal" . $shorcodenya . "').modal('show');
								});
						});
					</script>
					<!-- Modal -->
					<div class='modal fade' id='myModal" . $shorcodenya . "' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
						<div class='modal-dialog'>
							<div class='modal-content'>
								<div class='modal-header'>
									<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Tutup</span></button>
									<h4 class='modal-title' id='myModalLabel'>Dokumen $shorcodenya</h4>
								</div>";
			if (substr($pm[$shorcodenya], 0, 4) == "http") {
				echo "<div class='modal-body'><embed src='" . $pm[$shorcodenya] . "' width='100%' height='500px'/>";
			} else {
				echo "<div class='modal-body'><embed src='foto_asesi/" . $pm[$shorcodenya] . "' width='100%' height='500px'/>";
			}
			echo "</div>
								<div class='modal-footer'>
									<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>
								</div>
							</div>
							</div>
					</div>";
		}
		$nodoc++;
	}
	echo "</tbody></table></div>";
	echo "</div>
		</div>
	  </div>
	</div>
	  </div><!--col-->
		</div><!--row-->
		</section>";
}
// Bagian Unggah Dokumen
elseif ($_GET['module'] == 'unggahfile2') {
	$sqlasesi = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_SESSION[namauser]'";
	$asesi = $conn->query($sqlasesi);
	$r = $asesi->fetch_array();
	echo "<!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
	  Dokumen Pokok Asesi
        <small></small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li class='active'>Unggah Dokumen Pendukung</li>
      </ol>
    </section>";
	if (isset($_REQUEST['simpan'])) {
		function uploadFoto($file)
		{
			//$file_max_weight = 20097152; //Ukuran maksimal yg dibolehkan(20Mb)
			$ok_ext = array('jpg', 'png', 'gif', 'jpeg', 'JPG', 'PNG', 'GIF', 'JPEG'); // ekstensi yang diijinkan
			$destination = 'foto_asesi/'; // tempat buat upload
			$filename = explode(".", $file["name"]);
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
					$fileNewName = $time_stamp . md5($file_name_no_ext[0] . microtime()) . '.' . $file_extension;
					$alamatfile = $fileNewName;
					if (move_uploaded_file($file['tmp_name'], $destination . $fileNewName)) :
					//echo" File uploaded !";
					else :
					//echo "can't upload file.";
					endif;
				//else:
				//echo "File terlalu besar.";
				//endif;
				else :
				//echo "Ekstensi file tidak sesuai.";
				endif;
			}
			return $alamatfile;
		}
		function uploadFotoktp($filektp)
		{
			$file_max_weight = 20097152; //Ukuran maksimal yg dibolehkan(20Mb)
			$ok_ext = array('jpg', 'png', 'gif', 'jpeg', 'JPG', 'PNG', 'GIF', 'JPEG'); // ekstensi yang diijinkan
			$destination = 'foto_asesi/'; // tempat buat upload
			$filenamektp = explode(".", $filektp["name"]);
			$file_name = $filektp['name'];
			$file_name_no_ext = isset($filenamektp[0]) ? $filenamektp[0] : null;
			$file_extension = $filenamektp[count($filenamektp) - 1];
			$file_weight = $filektp['size'];
			$file_type = $filektp['type'];
			// Jika tidak ada error
			if ($filektp['error'] == 0) {
				$dateNow = date_create();
				$time_stamp = date_format($dateNow, 'U');
				if (in_array($file_extension, $ok_ext)) :
					if ($file_weight <= $file_max_weight) :
						$fileNewName = $time_stamp . md5($file_name_no_ext[0] . microtime()) . '.' . $file_extension;
						$alamatfilektp = $fileNewName;
						if (move_uploaded_file($filektp['tmp_name'], $destination . $fileNewName)) :
						//echo" File uploaded !";
						else :
						//echo "can't upload file.";
						endif;
					else :
					//echo "File terlalu besar.";
					endif;
				else :
				//echo "Ekstensi file tidak sesuai.";
				endif;
			}
			return $alamatfilektp;
		}
		function uploadFotoijazahs1($files1)
		{
			$file_max_weight = 20097152; //Ukuran maksimal yg dibolehkan(20Mb)
			$ok_ext = array('jpg', 'png', 'gif', 'jpeg', 'JPG', 'PNG', 'GIF', 'JPEG'); // ekstensi yang diijinkan
			$destination = 'foto_asesi/'; // tempat buat upload
			$filenames1 = explode(".", $files1["name"]);
			$file_name = $files1['name'];
			$file_name_no_ext = isset($filenames1[0]) ? $filenames1[0] : null;
			$file_extension = $filenames1[count($filenames1) - 1];
			$file_weight = $files1['size'];
			$file_type = $files1['type'];
			// Jika tidak ada error
			if ($files1['error'] == 0) {
				$dateNow = date_create();
				$time_stamp = date_format($dateNow, 'U');
				if (in_array($file_extension, $ok_ext)) :
					if ($file_weight <= $file_max_weight) :
						$fileNewName = $time_stamp . md5($file_name_no_ext[0] . microtime()) . '.' . $file_extension;
						$alamatfiles1 = $fileNewName;
						if (move_uploaded_file($files1['tmp_name'], $destination . $fileNewName)) :
						//echo" File uploaded !";
						else :
						//echo "can't upload file.";
						endif;
					else :
					//echo "File terlalu besar.";
					endif;
				else :
				//echo "Ekstensi file tidak sesuai.";
				endif;
			}
			return $alamatfiles1;
		}
		function uploadFototranskrip1($filetranskrip1)
		{
			$file_max_weight = 20097152; //Ukuran maksimal yg dibolehkan(20Mb)
			$ok_ext = array('jpg', 'png', 'gif', 'jpeg', 'JPG', 'PNG', 'GIF', 'JPEG'); // ekstensi yang diijinkan
			$destination = 'foto_asesi/'; // tempat buat upload
			$filenames1 = explode(".", $filetranskrip1["name"]);
			$file_name = $filetranskrip1['name'];
			$file_name_no_ext = isset($filenames1[0]) ? $filenames1[0] : null;
			$file_extension = $filenames1[count($filenames1) - 1];
			$file_weight = $filetranskrip1['size'];
			$file_type = $filetranskrip1['type'];
			// Jika tidak ada error
			if ($filetranskrip1['error'] == 0) {
				$dateNow = date_create();
				$time_stamp = date_format($dateNow, 'U');
				if (in_array($file_extension, $ok_ext)) :
					if ($file_weight <= $file_max_weight) :
						$fileNewName = $time_stamp . md5($file_name_no_ext[0] . microtime()) . '.' . $file_extension;
						$alamatfiletranskrip1 = $fileNewName;
						if (move_uploaded_file($filetranskrip1['tmp_name'], $destination . $fileNewName)) :
						//echo" File uploaded !";
						else :
						//echo "can't upload file.";
						endif;
					else :
					//echo "File terlalu besar.";
					endif;
				else :
				//echo "Ekstensi file tidak sesuai.";
				endif;
			}
			return $alamatfiletranskrip1;
		}
		function uploadFotokk($filekk)
		{
			$file_max_weight = 20097152; //Ukuran maksimal yg dibolehkan(20Mb)
			$ok_ext = array('jpg', 'png', 'gif', 'jpeg', 'JPG', 'PNG', 'GIF', 'JPEG'); // ekstensi yang diijinkan
			$destination = 'foto_asesi/'; // tempat buat upload
			$filenamekk = explode(".", $filekk["name"]);
			$file_name = $filekk['name'];
			$file_name_no_ext = isset($filenamekk[0]) ? $filenamekk[0] : null;
			$file_extension = $filenamekk[count($filenamekk) - 1];
			$file_weight = $filekk['size'];
			$file_type = $filekk['type'];
			// Jika tidak ada error
			if ($filekk['error'] == 0) {
				$dateNow = date_create();
				$time_stamp = date_format($dateNow, 'U');
				if (in_array($file_extension, $ok_ext)) :
					if ($file_weight <= $file_max_weight) :
						$fileNewName = $time_stamp . md5($file_name_no_ext[0] . microtime()) . '.' . $file_extension;
						$alamatfilekk = $fileNewName;
						if (move_uploaded_file($filekk['tmp_name'], $destination . $fileNewName)) :
						//echo" File uploaded !";
						else :
						//echo "can't upload file.";
						endif;
					else :
					//echo "File terlalu besar.";
					endif;
				else :
				//echo "Ekstensi file tidak sesuai.";
				endif;
			}
			return $alamatfilekk;
		}
		$file = $_FILES['file'];
		if (empty($_FILES['file']['tmp_name']) || !is_uploaded_file($_FILES['file']['tmp_name'])) {
			$alamatfile = $r["foto"];
		} else {
			unlink("foto_asesi/" . $r["foto"]);
			$alamatfile = uploadFoto($file);
		}
		// Unggah File KTP
		$filektp = $_FILES['filektp'];
		if (empty($_FILES['filektp']['tmp_name']) || !is_uploaded_file($_FILES['filektp']['tmp_name'])) {
			$alamatfilektp = $r["ktp"];
		} else {
			unlink("foto_asesi/" . $r["ktp"]);
			$alamatfilektp = uploadFotoktp($filektp);
		}
		// Unggah File Ijazah S1
		$files1 = $_FILES['fileijazahs1'];
		if (empty($_FILES['fileijazahs1']['tmp_name']) || !is_uploaded_file($_FILES['fileijazahs1']['tmp_name'])) {
			$alamatfiles1 = $r["ijazah"];
		} else {
			unlink("foto_asesi/" . $r["ijazah"]);
			$alamatfiles1 = uploadFotoijazahs1($files1);
		}
		// Unggah File Transkrip S1
		$files1 = $_FILES['filetranskrip1'];
		if (empty($_FILES['filetranskrip1']['tmp_name']) || !is_uploaded_file($_FILES['filetranskrip1']['tmp_name'])) {
			$alamatfiletranskrip1 = $r["transkrip"];
		} else {
			unlink("foto_asesi/" . $r["transkrip"]);
			$alamatfiletranskrip1 = uploadFototranskrip1($files1);
		}
		// Unggah File Kartu Keluarga
		$filekk = $_FILES['filekk'];
		if (empty($_FILES['filekk']['tmp_name']) || !is_uploaded_file($_FILES['filekk']['tmp_name'])) {
			$alamatfilekk = $r["kk"];
		} else {
			unlink("foto_asesi/" . $r["kk"]);
			$alamatfilekk = uploadFotokk($filekk);
		}
		//--------------------------------------------------
		$query = "UPDATE `asesi` set 
				`foto`='$alamatfile',
				`ktp`='$alamatfilektp',
				`kk`='$alamatfilekk',
				`ijazah`='$alamatfiles1',
				`transkrip`='$alamatfiletranskrip1' where `no_pendaftaran`='$_SESSION[namauser]'";
		if ($conn->query($query) == TRUE) {
			die("<script>location.href = '?module=unggahfile'</script>");
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
              <div class='box-body'>
				<div class='col-md-6'>			  
					<div class='form-group'>
						<label for='fileID'>
						<label>Pas Foto 3x4</label><br /><img src='foto_asesi/$r[foto]' style='width:200px; height:300px; margin-bottom:10px;' id='fotoProfil'></label>
						<input type='file' name='file' id='fileID' accept='image/*' onchange='readURL(this);'>
						<p class='help-block'>Ukuran foto maksimal 2 MB.</p>
					</div>
					<div class='form-group'>
						<label for='fileIDktp'>
						<label>Scan KTP</label><br /><img src='foto_asesi/$r[ktp]' style='width:250px; height:150px; margin-bottom:10px;' id='fotoktp'></label>
						<input type='file' name='filektp' id='fileIDktp' accept='image/*' onchange='readURL(this);'>
						<p class='help-block'>Ukuran foto maksimal 2 MB.</p>
					</div>
					<div class='form-group'>
						<label for='fileIDkk'>
						<label>Scan Kartu Keluarga</label><br /><img src='foto_asesi/$r[kk]' style='width:250px; height:150px; margin-bottom:10px;' id='fotokk'></label>
						<input type='file' name='filekk' id='fileIDkk' accept='image/*' onchange='readURL(this);'>
						<p class='help-block'>Ukuran foto maksimal 2 MB.</p>
					</div>
				</div>
				<div class='col-md-6'>				
					<div class='form-group'>
						<label for='fileIDijazahs1'>
						<label>Scan Ijazah Terakhir</label><br /><img src='foto_asesi/$r[ijazah]' style='width:250px; height:150px; margin-bottom:10px;' id='fotoIjazahs1'></label>
						<input type='file' name='fileijazahs1' id='fileIDijazahs1' accept='image/*' onchange='readURL(this);'>
						<p class='help-block'>Ukuran foto maksimal 2 MB.</p>
					</div>
					<div class='form-group'>
						<label for='fileIDtranskrip1'>
						<label>Scan Transkrip Nilai</label><br /><img src='foto_asesi/$r[transkrip]' style='width:250px; margin-bottom:10px;' id='fototranskrip1'></label>
						<input type='file' name='filetranskrip1' id='fileIDtranskrip1' accept='image/*' onchange='readURL(this);'>
						<p class='help-block'>Ukuran foto maksimal 2 MB.</p>
					</div>
				</div>
              </div>
              <!-- /.box-body -->
              <div class='box-footer'>
                <center><button type='submit' class='btn btn-primary' style='width:250px;' name='simpan'>Simpan</button></center>
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
// Bagian Input Asesmen Mandiri FORM-APL-02 Sistem Per Elemen
elseif ($_GET['module'] == 'form-apl-02-el') {
	$sqllogin = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_SESSION[namauser]'";
	$login = $conn->query($sqllogin);
	$ketemu = $login->num_rows;
	$rowAgen = $login->fetch_assoc();
	$sqlgetjadwal = "SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
	$getjadwal = $conn->query($sqlgetjadwal);
	$jd = $getjadwal->fetch_assoc();
	$sqlgetskema = "SELECT * FROM `skema_kkni` WHERE `id`='$jd[id_skemakkni]'";
	$getskema = $conn->query($sqlgetskema);
	$sk = $getskema->fetch_assoc();
	if (isset($_REQUEST['simpandata'])) {
		$sqlgetunitkompetensi2 = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
		$getunitkompetensi2 = $conn->query($sqlgetunitkompetensi2);
		while ($unk2 = $getunitkompetensi2->fetch_assoc()) {
			$sqlcekukom = "SELECT * FROM `asesmen_unitkompetensi` WHERE `id_skemakkni`='$sk[id]' AND `id_asesi`='$_SESSION[namauser]' AND `id_unitkompetensi`='$unk2[id]'";
			$cekukom = $conn->query($sqlcekukom);
			$ukom = $cekukom->num_rows;
			if ($ukom > 0) {
				$sqlgetelemen2 = "SELECT * FROM `elemen_kompetensi` WHERE `id_unitkompetensi`='$unk2[id]' ORDER BY `id` ASC";
				$getelemen2 = $conn->query($sqlgetelemen2);
				while ($el2 = $getelemen2->fetch_assoc()) {
					$id_jawaban = 'optionsRadios' . $el2['id'];
					$sqlcekjawaban = "SELECT * FROM `asesi_apl02` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_elemen`='$el2[id]' AND `id_skemakkni`='$sk[id]' AND `id_jadwal`='$_GET[idj]'";
					$cekjawaban = $conn->query($sqlcekjawaban); //var_dump($sqlcekjawaban);exit;
					$jjw = $cekjawaban->num_rows;
					$idjawaban = isset($_POST[$id_jawaban]) ? $_POST[$id_jawaban] : '0';
					if ($jjw > 0) {
						$sqlinputjawaban = "UPDATE `asesi_apl02` SET `jawaban`='$idjawaban' WHERE `id_asesi`='$_SESSION[namauser]' AND `id_elemen`='$el2[id]' AND `id_skemakkni`='$sk[id]' AND `id_jadwal`='$_GET[idj]'";
						$conn->query($sqlinputjawaban);
					} else {
						$sqlinputjawaban = "INSERT INTO `asesi_apl02`(`id_asesi`, `id_elemen`, `id_skemakkni`, `id_jadwal`, `jawaban`) VALUES ('$_SESSION[namauser]','$el2[id]','$sk[id]','$_GET[idj]',$idjawaban)";
						$conn->query($sqlinputjawaban);
					}
				}
			}
		}
		$folderPath = "foto_tandatangan/";
		if (empty($_POST['signed'])) {
			echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Jawaban berhasil disimpan</h4>
			Terimakasih, Anda telah melakukan <b>Asesmen Mandiri untuk Skema $sk[judul].</b></div>";
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
			$sqlinputdigisign = "INSERT INTO `logdigisign`(`id_dokumen`, `id_asesi`, `id_skema`, `url_ditandatangani`, `nama_dokumen`, `penandatangan`, `file`, `ip`) VALUES ('$iddokumen','$_SESSION[namauser]','$_GET[id]'	,'$escaped_url','FR-APL-01. FORMULIR PERMOHONAN SERTIFIKASI KOMPETENSI','$rowAgen[nama]','$file','$alamatip')";
			$conn->query($sqlinputdigisign);
			echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Jawaban berhasil disimpan</h4>
			Terimakasih, Anda telah melakukan <b>Asesmen Mandiri untuk Skema $sk[judul].</b><br><b>Tanda Tangan Sukses Diupload</b></div>";
		}
	}
	if (isset($_REQUEST['hapusdocasesi'])) {
		$cekdu = "SELECT * FROM `asesi_apl02doc` WHERE `id`='$_POST[iddeldocasesi]'";
		$result = $conn->query($cekdu);
		$getr = $result->fetch_assoc();
		if ($result->num_rows != 0) {
			$conn->query("DELETE FROM `asesi_apl02doc` WHERE `id`='$_POST[iddeldocasesi]'");
			//unlink("foto_apl02/".$getr['file']);
			echo "<div class='alert alert-danger alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Hapus Data Sukses</h4>
				Anda Telah Berhasil Menghapus Data <b>Bukti Kompetensi</b></div>";
		} else {
			echo "<script>alert('Maaf Dokumen Bukti tersebut Tidak Ditemukan');</script>";
		}
	}
	echo "<!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
	  Asesmen Mandiri (FORM-APL-02)
        <small>Skema $sk[judul]</small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li class='active'>Asesmen Mandiri</li>
      </ol>
    </section>";
	function uploadBukti($file)
	{
		$file_max_weight = 20097152; //Ukuran maksimal yg dibolehkan(2Mb)
		$ok_ext = array('jpg', 'png', 'gif', 'jpeg', 'JPG', 'PNG', 'GIF', 'JPEG', 'pdf', 'PDF'); // ekstensi yang diijinkan
		$destination = "foto_apl02/"; // tempat buat upload
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
	echo "<!-- Main content -->
    <section class='content'>
      <div class='row'>
        <div class='col-xs-12'>
          <div class='box'>
            <div class='box-body'><!-- /.start box-body main-->
              <!-- form start -->
              <form role='form' method='POST' enctype='multipart/form-data'>
				<div class='box-body box-success'>
				<h2>Formulir Asesmen Mandiri</h2>
				<p>Pada bagian ini, anda diminta untuk menilai diri sendiri terhadap unit (unit-unit) kompetensi yang akan di-ases.</p>
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
		$sqlcekukom = "SELECT * FROM `asesmen_unitkompetensi` WHERE `id_skemakkni`='$sk[id]' AND `id_asesi`='$_SESSION[namauser]' AND `id_unitkompetensi`='$unk[id]'";
		$cekukom = $conn->query($sqlcekukom);
		$ukom = $cekukom->num_rows;
		if ($ukom > 0) {
			//echo"<div class='box box-solid'><!-- start box unit kompetensi-->";
			echo "<div class='box-header with-border'>
								<h3 class='box-title text-green'><b>Unit Kompetensi : $no. $unk[judul]</b></h3>
							</div>
							<div class='box-body'><!-- start box elemen kompetensi-->";
			$no2 = 1;
			$sqlgetelemen = "SELECT * FROM `elemen_kompetensi` WHERE `id_unitkompetensi`='$unk[id]' ORDER BY `id` ASC";
			$getelemen = $conn->query($sqlgetelemen);
			while ($el = $getelemen->fetch_assoc()) {
				$no3 = 1;
				echo "<h4 class='text-blue'>Elemen Kompetensi : <b>$no.$no2. $el[elemen_kompetensi]</b></h4>";
				echo "<div class='form-group'>
											<label>Kriteria Unjuk Kerja :</label>";
				$sqlgetkriteria = "SELECT * FROM `kriteria_unjukkerja` WHERE `id_elemenkompetensi`='$el[id]' ORDER BY `id` ASC";
				$getkriteria = $conn->query($sqlgetkriteria);
				while ($kr = $getkriteria->fetch_assoc()) {
					echo "<p>$no.$no2.$no3. $kr[kriteria]</p>";
					$no3++;
				}
				echo "</div>";
				$sqlcekjawaban0 = "SELECT * FROM `asesi_apl02` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_elemen`='$el[id]' AND `id_skemakkni`='$sk[id]' AND `id_jadwal`='$_GET[idj]'";
				$cekjawaban0 = $conn->query($sqlcekjawaban0);
				$jjw0 = $cekjawaban0->fetch_assoc();
				$numjjw0 = $cekjawaban0->num_rows;
				if ($numjjw0 == 0) {
					echo "<label>
														<input type='radio' name='optionsRadios$el[id]' id='options1$el[id]' value='1'>
														 Kompeten &nbsp;&nbsp;&nbsp;
													</label>
													<label>
														<input type='radio' name='optionsRadios$el[id]' id='options2$el[id]' value='0'>
														 Belum Kompeten
													</label>";
				} else {
					if ($jjw0['jawaban'] == 1) {
						echo "<label>
													<input type='radio' name='optionsRadios$el[id]' id='options1$el[id]' value='1' checked>
													 Kompeten &nbsp;&nbsp;&nbsp;
												</label>";
					} else {
						echo "<label>
													<input type='radio' name='optionsRadios$el[id]' id='options1$el[id]' value='1'>
													 Kompeten &nbsp;&nbsp;&nbsp;
												</label>";
					}
					if ($jjw0['jawaban'] == 0) {
						echo "<label>
													<input type='radio' name='optionsRadios$el[id]' id='options2$el[id]' value='0' checked>
													 Belum Kompeten
												</label>";
					} else {
						echo "<label>
													<input type='radio' name='optionsRadios$el[id]' id='options2$el[id]' value='0'>
													 Belum Kompeten
												</label>";
					}
				}
				//upload bukti
				$kritvar = $el['id'];
				$id_kriteriax = "id_kriteria" . $kritvar;
				$id_elemenkompetensix = "id_elemenkompetensi" . $kritvar;
				$id_skemakknix = "id_skemakkni" . $kritvar;
				$unggahbuktix = "unggahbukti" . $kritvar;
				$filex = "file" . $kritvar;
				if (isset($_REQUEST[$unggahbuktix])) {
					$file = $_FILES[$filex];
					// Apabila ada file yang diupload
					if (empty($_FILES[$filex]['tmp_name']) || !is_uploaded_file($_FILES[$filex]['tmp_name'])) {
						$query = "INSERT INTO `asesi_apl02doc`(`id_asesi`, `id_kriteria`, `id_elemenkompetensi`, `id_skemakkni`, `id_jadwal`) VALUES ('$_SESSION[namauser]','$_POST[$id_kriteriax]','$_POST[$id_elemenkompetensix]','$_POST[$id_skemakknix]','$_GET[idj]')";
					} else {
						$alamatfile = uploadBukti($file);
						$query = "INSERT INTO `asesi_apl02doc`(`id_asesi`, `id_kriteria`, `id_elemenkompetensi`, `id_skemakkni`, `id_jadwal`, `file`) VALUES ('$_SESSION[namauser]','$_POST[$id_kriteriax]','$_POST[$id_elemenkompetensix]','$_POST[$id_skemakknix]','$_GET[idj]','$alamatfile')";
					}
					$conn->query($query);
					/* echo "<div class='alert alert-success alert-dismissible'>
											<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
											<h4><i class='icon fa fa-check'></i> Tambah Data Sukses</h4>
											Anda Telah Berhasil Mengunggah Bukti <b>Kriteria Unjuk Kerja $el[elemen_kompetensi]</b></div>"; */
					//header('location:../../media.php?module=form-apl-02-el&ida=$_SESSION[namauser]&idj=$_GET[idj]');
					echo "<script>alert('Anda Telah Berhasil Mengunggah Bukti Kriteria Unjuk Kerja $el[elemen_kompetensi]'); window.location = '../../media.php?module=form-apl-02-el&ida=$_SESSION[namauser]&idj=$_GET[idj]';</script>";
					// ==========================================simpan kompeten atau belum kompeten=============================
					$id_jawaban = 'optionsRadios' . $el['id'];
					$sqlcekjawaban = "SELECT * FROM `asesi_apl02` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_elemen`='$el[id]' AND `id_skemakkni`='$sk[id]'";
					$cekjawaban = $conn->query($sqlcekjawaban);
					$jjw = $cekjawaban->num_rows;
					if ($jjw > 0) {
						if (isset($_POST[$id_jawaban])) {
							$sqlinputjawaban = "UPDATE `asesi_apl02` SET `jawaban`='$_POST[$id_jawaban]' WHERE `id_asesi`='$_SESSION[namauser]' AND `id_elemen`='$el[id]' AND `id_skemakkni`='$sk[id]' AND `id_jadwal`='$_GET[idj]'";
							$conn->query($sqlinputjawaban);
						} else {
							$sqlinputjawaban = "UPDATE `asesi_apl02` SET `jawaban`=NULL WHERE `id_asesi`='$_SESSION[namauser]' AND `id_elemen`='$el[id]' AND `id_skemakkni`='$sk[id]' AND `id_jadwal`='$_GET[idj]'";
							$conn->query($sqlinputjawaban);
						}
					} else {
						if (isset($_POST[$id_jawaban])) {
							$sqlinputjawaban = "INSERT INTO `asesi_apl02`(`id_asesi`, `id_elemen`, `id_skemakkni`, `id_jadwal`, `jawaban`) VALUES ('$_SESSION[namauser]','$el[id]','$sk[id]','$_GET[idj]','$_POST[$id_jawaban]')";
							$conn->query($sqlinputjawaban);
						} else {
							$sqlinputjawaban = "INSERT INTO `asesi_apl02`(`id_asesi`, `id_elemen`, `id_skemakkni`, `id_jadwal`, `jawaban`) VALUES ('$_SESSION[namauser]','$el[id]','$sk[id]','$_GET[idj]',NULL)";
							$conn->query($sqlinputjawaban);
						}
					}
					//===========================================================================================================
				}
				echo "<br><label>Bukti unjuk kerja elemen:</label><br>";
				$getfilebukti = "SELECT * FROM `asesi_apl02doc` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_elemenkompetensi`='$el[id]' AND `id_skemakkni`='$sk[id]' AND `id_jadwal`='$_GET[idj]'";
				$filebukti = $conn->query($getfilebukti);
				$nodokumen = 1;
				while ($fbk = $filebukti->fetch_assoc()) {
					echo "<form role='form' method='POST' enctype='multipart/form-data'>Dokumen Elemen $no.$no2.-($nodokumen)&nbsp;<a href='#myModal" . $fbk['id'] . "' class='btn btn-success btn-xs' data-toggle='modal' data-id='" . $fbk['id'] . "'><span class='glyphicon glyphicon-zoom-in' aria-hidden='true' title='Lihat/Unduh Dokumen'></span></a>
											<input type='hidden' name='iddeldocasesi' value='$fbk[id]'>
											<input type='submit' name='hapusdocasesi' class='btn btn-danger btn-xs' onclick='return confirmation()' title='Hapus' value='Hapus'></form><br>";
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
															<div class='modal-body'><embed src='foto_apl02/$fbk[file]' width='100%' height='500px'/>
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
				echo "<input type='hidden' name='id_asesi$el[id]' value='$_SESSION[namauser]'>
										<input type='hidden' name='id_kriteria$el[id]' value='$el[id]'>
										<input type='hidden' name='id_skemakkni$el[id]' value='$sk[id]'>
										<input type='hidden' name='id_elemenkompetensi$el[id]' value='$el[id]'>
										<input type='hidden' name='id_jadwal$el[id]' value='$_GET[idj]'>
										<input type='file' name='file$el[id]' id='file$el[id]' accept='.pdf, image/*' onchange='readURL(this);'>
										<button type='submit' class='btn btn-success btn-xs' name='unggahbukti$el[id]'>Unggah/ Tambah Bukti</button><br>atau<br>
										<a href='#myModalLib" . $el['id'] . "' class='btn btn-primary btn-xs' data-toggle='modal' data-id='" . $el['id'] . "'>Tambahkan dokumen dari <em>Library</em> (pustaka anda)</a>";
				// add bukti from library
				$kritvar = $el['id'];
				$addfromlibel = "addfromlib" . $kritvar;
				if (isset($_REQUEST[$addfromlibel])) {
					$id_kriteriax = "id_elemenkompetensi" . $kritvar;
					$id_elemenkompetensix = "id_elemenkompetensi" . $kritvar;
					$id_skemakknix = "id_skemakkni" . $kritvar;
					$filex = "file" . $kritvar;
					$query = "INSERT INTO `asesi_apl02doc`(`id_asesi`, `id_kriteria`, `id_elemenkompetensi`, `id_skemakkni`, `id_jadwal`, `file`) VALUES ('$_SESSION[namauser]','$_POST[$id_kriteriax]','$_POST[$id_elemenkompetensix]','$_POST[$id_skemakknix]','$_GET[idj]','$_POST[$filex]')";
					$conn->query($query);
					/* echo "<div class='alert alert-success alert-dismissible'>
											<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
											<h4><i class='icon fa fa-check'></i> Tambah Data dari <em>Library</em> Sukses</h4>
											Anda Telah Berhasil Menambahkan Data <b>Bukti Kompetensi dari <em>Library</em></b></div>"; */
					echo "<script>alert('Anda Telah Berhasil Menambahkan Bukti Kriteria Unjuk Kerja $el[elemen_kompetensi] dari Library'); window.location = '../../media.php?module=form-apl-02-el&ida=$_SESSION[namauser]&idj=$_GET[idj]';</script>";
				}
				// mengambil dari library bukti unjuk kerja
				echo "<script>
											$(function(){
														$(document).on('click','.edit-record',function(e){
															e.preventDefault();
															$('#myModalLib" . $el['id'] . "').modal('show');
														});
												});
										</script>
										<!-- Modal -->
											<div class='modal fade' id='myModalLib" . $el['id'] . "' tabindex='-1' role='dialog' aria-labelledby='myModalLibLabel' aria-hidden='true'>
												<div class='modal-dialog'>
													<div class='modal-content'>
														<div class='modal-header'>
															<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Tutup</span></button>
															<h4 class='modal-title' id='myModalLibLabel'><em>Library</em> (Pustaka) Dokumen " . $rowAgen['no_pendaftaran'] . " Unit $unk[judul]</h4>
														</div>
														<div class='modal-body'>";
				echo "<div style='overflow-x:auto; overflow-y:auto;'>";
				echo "<table id='example3' class='table table-bordered table-striped'>
																<thead><tr><th>Dokumen</th></tr></thead>
																<tbody>";
				$sqlasesidocx = "SELECT DISTINCT `file` FROM `asesi_apl02doc` WHERE `id_asesi`='$rowAgen[no_pendaftaran]'";
				$asesidocx = $conn->query($sqlasesidocx);
				while ($pmx0 = $asesidocx->fetch_assoc()) {
					$sqlasesidocxy = "SELECT * FROM `asesi_apl02doc` WHERE `file`='$pmx0[file]' ORDER BY `id` DESC";
					$asesidocxy = $conn->query($sqlasesidocxy);
					$pmx = $asesidocxy->fetch_assoc();
					echo "<tr>
																			<td>
																				<form role='form' method='POST' enctype='multipart/form-data'>
																				<div class='col-md-4'>
																				Dokumen : <embed src='foto_apl02/$pmx[file]' width='100%' height='500px'/><br>Tanggal Diunggah: <b>" . tgl_indo($pmx['waktu']) . "</b>
																				</div>
																				<div class='col-md-8'>
																					<input type='hidden' name='id_asesi$el[id]' value='$_SESSION[namauser]'>
																					<input type='hidden' name='id_skemakkni$el[id]' value='$sk[id]'>
																					<input type='hidden' name='id_jadwal$el[id]' value='$_GET[idj]'>
																					<input type='hidden' name='file$el[id]' value='$pmx[file]'>
																					<label>Pilih Elemen Kompetensi</label>
																					<div class='input-group input-group-sm'>
																						<select name='id_elemenkompetensi$el[id]' class='form-control'>";
					$sqlgetelemenx = "SELECT * FROM `elemen_kompetensi` WHERE `id_unitkompetensi`='$unk[id]' ORDER BY `id` ASC";
					$getelemenx = $conn->query($sqlgetelemenx);
					while ($elx = $getelemenx->fetch_assoc()) {
						echo "<option value='$elx[id]'>$elx[elemen_kompetensi]</option>";
					}
					echo "</select>
																						<span class='input-group-btn'>
																							<button type='submit' name='addfromlib$el[id]' class='btn btn-primary btn-xs btn-block'>Gunakan</button>
																						</span>
																					</div>
																				</div>
																				</form>
																			</td>
																		</tr>";
					/*echo "<script>
																			$(function(){
																						$(document).on('click','.edit-record',function(e){
																							e.preventDefault();
																							$('#myModalM".$pmx['id']."').modal('show');
																						});
																				});
																		</script>
																		<!-- ModalM -->
																			<div class='modal fade' id='myModalM".$pmx['id']."' tabindex='-1' role='dialog' aria-labelledby='myModalMLabel' aria-hidden='true'>
																				<div class='modal-dialog'>
																					<div style='overflow-y:auto;'>
																						<div class='modal-content'>
																							<div class='modal-header'>
																								<h4 class='modal-title' id='myModalMLabel'>Library Dokumen Porfolio ".$pmx['file']."</h4>
																							</div>
																							<div class='modal-body'>
																								<embed src='foto_apl02/$pmx[file]' width='100%' height='500px'/>
																							</div>
																							<div class='modal-footer'>
																								<em><font color='red'>klik di area gelap untuk menutup</font></em>
																							</div>
																						</div>
																					</div><!-- End Overflow-->
																				</div>
																			</div>
																		<!-- ModalM End-->"; */
				}
				echo "</tbody>
															</table>";
				echo "</div><!-- End overflow Table -->
														  <font color='red'><b>PERINGATAN :</b><br>Bila Anda menghapus data pada <em>library</em> akan menghapus data portfolio seluruh asesmen anda yang bersangkutan</font>";
				echo "</div>
														<div class='modal-footer'>
															<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>
														</div>
													</div>
												</div>
											</div>
										<!-- Modal End -->";
				//echo "</div>";
				$no2++;
			}
			echo "</div><!-- /.end box elemen kompetensi-->";
			//echo "<div><!-- /.end box-body unit kompetensi-->";
			$no++;
		} else {
			echo "<div class='box-body'><font color='red'>Maaf Anda belum memilih Unit Kompetensi saat mendaftar ke Skema</font></div>";
		}
	}
	echo "<div class='box-footer'>";
	// cek tandatangan digital
	$url =  "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
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
	echo "<div align='left' class='col-md-6 col-sm-6 col-xs-6'>
						<a class='btn btn-danger' id=reset-validate-form href='?module=jadwal'>Kembali</a>
					</div>
					<div align='right' class='col-md-6 col-sm-6 col-xs-6'>
						<!--<a href='admin/form-apl-02.php?ida=$_SESSION[namauser]&idj=$_GET[idj]' class='btn btn-primary'>Unduh Form Jawaban</a>-->
						<button type='submit' class='btn btn-success' name='simpandata'>Simpan Jawaban</button>
					</div>
				</div><!-- /.box-footer --></form>			
            </div>
            <!-- /.end box-body main-->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->";
}
// Bagian Input Asesmen Mandiri FORM-APL-02
elseif ($_GET['module'] == 'form-apl-02') {
	$sqllogin = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_SESSION[namauser]'";
	$login = $conn->query($sqllogin);
	$ketemu = $login->num_rows;
	$rowAgen = $login->fetch_assoc();
	$sqlgetjadwal = "SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
	$getjadwal = $conn->query($sqlgetjadwal);
	$jd = $getjadwal->fetch_assoc();
	$sqlgetskema = "SELECT * FROM `skema_kkni` WHERE `id`='$jd[id_skemakkni]'";
	$getskema = $conn->query($sqlgetskema);
	$sk = $getskema->fetch_assoc();
	if (isset($_REQUEST['simpandata'])) {
		$sqlgetunitkompetensi2 = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
		$getunitkompetensi2 = $conn->query($sqlgetunitkompetensi2);
		while ($unk2 = $getunitkompetensi2->fetch_assoc()) {
			$sqlcekukom = "SELECT * FROM `asesmen_unitkompetensi` WHERE `id_skemakkni`='$sk[id]' AND `id_asesi`='$_SESSION[namauser]' AND `id_unitkompetensi`='$unk2[id]'";
			$cekukom = $conn->query($sqlcekukom);
			$ukom = $cekukom->num_rows;
			if ($ukom > 0) {
				$sqlgetelemen2 = "SELECT * FROM `elemen_kompetensi` WHERE `id_unitkompetensi`='$unk2[id]' ORDER BY `id` ASC";
				$getelemen2 = $conn->query($sqlgetelemen2);
				while ($el2 = $getelemen2->fetch_assoc()) {
					$sqlgetkriteria2 = "SELECT * FROM `kriteria_unjukkerja` WHERE `id_elemenkompetensi`='$el2[id]' ORDER BY `id` ASC";
					$getkriteria2 = $conn->query($sqlgetkriteria2);
					while ($kr2 = $getkriteria2->fetch_assoc()) {
						$id_jawaban = 'optionsRadios' . $kr2['id'];
						$sqlcekjawaban = "SELECT * FROM `asesi_apl02` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_kriteria`='$kr2[id]' AND `id_skemakkni`='$sk[id]'";
						$cekjawaban = $conn->query($sqlcekjawaban);
						$jjw = $cekjawaban->num_rows;
						if ($jjw == 0) {
							$sqlinputjawaban = "INSERT INTO `asesi_apl02`(`id_asesi`, `id_kriteria`, `id_skemakkni`, `id_jadwal`, `jawaban`) VALUES ('$_SESSION[namauser]','$kr2[id]','$sk[id]','$_GET[idj]','$_POST[$id_jawaban]')";
							$conn->query($sqlinputjawaban);
						} else {
							$sqlinputjawaban = "UPDATE `asesi_apl02` SET `jawaban`='$_POST[$id_jawaban]' WHERE `id_asesi`='$_SESSION[namauser]' AND `id_kriteria`='$kr2[id]' AND `id_skemakkni`='$sk[id]' AND `id_jadwal`='$_GET[idj]'";
							$conn->query($sqlinputjawaban);
						}
					}
				}
			}
		}
		$folderPath = "foto_tandatangan/";
		if (empty($_POST['signed'])) {
			echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Jawaban berhasil disimpan</h4>
			Terimakasih, Anda telah melakukan <b>Asesmen Mandiri untuk Skema $sk[judul].</b></div>";
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
			$sqlinputdigisign = "INSERT INTO `logdigisign`(`id_dokumen`, `id_asesi`, `id_skema`, `url_ditandatangani`, `nama_dokumen`, `penandatangan`, `file`, `ip`) VALUES ('$iddokumen','$_SESSION[namauser]','$_GET[id]'	,'$escaped_url','FR-APL-01. FORMULIR PERMOHONAN SERTIFIKASI KOMPETENSI','$rowAgen[nama]','$file','$alamatip')";
			$conn->query($sqlinputdigisign);
			echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Jawaban berhasil disimpan</h4>
			Terimakasih, Anda telah melakukan <b>Asesmen Mandiri untuk Skema $sk[judul].</b><br><b>Tanda Tangan Sukses Diupload</b></div>";
		}
	}
	if (isset($_REQUEST['hapusdocasesi'])) {
		$cekdu = "SELECT * FROM `asesi_apl02doc` WHERE `id`='$_POST[iddeldocasesi]'";
		$result = $conn->query($cekdu);
		$getr = $result->fetch_assoc();
		if ($result->num_rows != 0) {
			$conn->query("DELETE FROM `asesi_apl02doc` WHERE `id`='$_POST[iddeldocasesi]'");
			unlink("foto_apl02/" . $getr['file']);
			echo "<div class='alert alert-danger alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Hapus Data Sukses</h4>
			Anda Telah Berhasil Menghapus Data <b>Bukti Kompetensi</b></div>";
		} else {
			echo "<script>alert('Maaf Dokumen Bukti tersebut Tidak Ditemukan');</script>";
		}
	}
	echo "<!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
	  Asesmen Mandiri (FORM-APL-02)
        <small>Skema $sk[judul]</small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li class='active'>Asesmen Mandiri</li>
      </ol>
    </section>";
	function uploadBukti($file)
	{
		$file_max_weight = 20097152; //Ukuran maksimal yg dibolehkan(2Mb)
		$ok_ext = array('jpg', 'png', 'gif', 'jpeg', 'JPG', 'PNG', 'GIF', 'JPEG', 'pdf', 'PDF'); // ekstensi yang diijinkan
		$destination = "foto_apl02/"; // tempat buat upload
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
	echo "<!-- Main content -->
    <section class='content'>
      <div class='row'>
        <div class='col-xs-12'>
          <div class='box'>
            <div class='box-body'>
              <!-- form start -->
            	<div class='box-body'>
		<h2>Formulir Asesmen Mandiri</h2>
            <form role='form' method='POST' enctype='multipart/form-data'>
		<p>Pada bagian ini, anda diminta untuk menilai diri sendiri terhadap unit (unit-unit) kompetensi yang akan di-ases.</p>
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
		$sqlcekukom = "SELECT * FROM `asesmen_unitkompetensi` WHERE `id_skemakkni`='$sk[id]' AND `id_asesi`='$_SESSION[namauser]' AND `id_unitkompetensi`='$unk[id]'";
		$cekukom = $conn->query($sqlcekukom);
		$ukom = $cekukom->num_rows;
		if ($ukom > 0) {
			echo "<div class='box box-solid'>";
			echo "<div class='box-header with-border'>
					<h3 class='box-title text-green'><b>Unit Kompetensi : $no. $unk[judul]</b></h3>
				</div>
				<div class='box-body'>";
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
					$sqlcekjawaban0 = "SELECT * FROM `asesi_apl02` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_kriteria`='$kr[id]' AND `id_skemakkni`='$sk[id]'";
					$cekjawaban0 = $conn->query($sqlcekjawaban0);
					$jjw0 = $cekjawaban0->fetch_assoc();
					$numjjw0 = $cekjawaban0->num_rows;
					if ($numjjw0 == 0) {
						echo "<p>$no.$no2.$no3. $kr[kriteria]</p>";
						echo "<label>
											<input type='radio' name='optionsRadios$kr[id]' id='options1$kr[id]' value='1'>
											 Kompeten &nbsp;&nbsp;&nbsp;
										</label>
										<label>
											<input type='radio' name='optionsRadios$kr[id]' id='options2$kr[id]' value='0'>
											 Belum Kompeten
										</label>";
					} else {
						echo "<p>$no.$no2.$no3. $kr[kriteria]</p>";
						if ($jjw0['jawaban'] == 1) {
							echo "<label>
												<input type='radio' name='optionsRadios$kr[id]' id='options1$kr[id]' value='1' checked>
												 Kompeten &nbsp;&nbsp;&nbsp;
											</label>";
						} else {
							echo "<label>
												<input type='radio' name='optionsRadios$kr[id]' id='options1$kr[id]' value='1'>
												 Kompeten &nbsp;&nbsp;&nbsp;
											</label>";
						}
						if ($jjw0['jawaban'] == 0) {
							echo "<label>
												<input type='radio' name='optionsRadios$kr[id]' id='options2$kr[id]' value='0' checked>
												 Belum Kompeten
											</label>";
						} else {
							echo "<label>
												<input type='radio' name='optionsRadios$kr[id]' id='options2$kr[id]' value='0'>
												 Belum Kompeten
											</label>";
						}
					}
					//upload bukti
					$kritvar = $kr['id'];
					$id_kriteriax = "id_kriteria" . $kritvar;
					$id_elemenkompetensix = "id_elemenkompetensi" . $kritvar;
					$id_skemakknix = "id_skemakkni" . $kritvar;
					$unggahbuktix = "unggahbukti" . $kritvar;
					$filex = "file" . $kritvar;
					if (isset($_REQUEST[$unggahbuktix])) {
						$file = $_FILES[$filex];
						// Apabila ada file yang diupload
						if (empty($_FILES[$filex]['tmp_name']) || !is_uploaded_file($_FILES[$filex]['tmp_name'])) {
							$query = "INSERT INTO `asesi_apl02doc`(`id_asesi`, `id_kriteria`, `id_elemenkompetensi`, `id_skemakkni`, `id_jadwal`) VALUES ('$_SESSION[namauser]','$_POST[$id_kriteriax]','$_POST[$id_elemenkompetensix]','$_POST[$id_skemakknix]','$_GET[idj]')";
						} else {
							$alamatfile = uploadBukti($file);
							$query = "INSERT INTO `asesi_apl02doc`(`id_asesi`, `id_kriteria`, `id_elemenkompetensi`, `id_skemakkni`, `id_jadwal`, `file`) VALUES ('$_SESSION[namauser]','$_POST[$id_kriteriax]','$_POST[$id_elemenkompetensix]','$_POST[$id_skemakknix]','$_GET[idj]','$alamatfile')";
						}
						/*$querycek = "SELECT * FROM `asesi_apl02doc` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_jadwal`='$_GET[idj]' AND `id_skemakkni`='$_POST[$id_skemakknix]' AND `id_elemenkompetensi`='$_POST[$id_elemenkompetensix]'";
										$resultc=$conn->query($querycek);
										$row_cnt = $resultc->num_rows;
										if ($row_cnt==0){*/
						$conn->query($query);
						//header('location:../../media.php?module=syarat&id=$_GET[id]');
						echo "<div class='alert alert-success alert-dismissible'>
											<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
											<h4><i class='icon fa fa-check'></i> Tambah Data Sukses</h4>
											Anda Telah Berhasil Mengunggah Bukti <b>Kriteria Unjuk Kerja $kr[kriteria]</b></div>";
						/*}else{
											echo "<script>alert('Maaf Data Tersebut Sudah Ada'); window.location = '../../media.php?module=form-apl-02&ida=$_GET[ida]&idj=$_GET[idj]'</script>";
										}*/
					}
					echo "<br><label>Bukti unjuk kerja:</label><br>";
					$getfilebukti = "SELECT * FROM `asesi_apl02doc` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_kriteria`='$kr[id]' AND `id_elemenkompetensi`='$el[id]' AND `id_skemakkni`='$sk[id]' AND `id_jadwal`='$_GET[idj]'";
					$filebukti = $conn->query($getfilebukti);
					$nodokumen = 1;
					while ($fbk = $filebukti->fetch_assoc()) {
						//$fbk[file];
						echo "<form role='form' method='POST' enctype='multipart/form-data'>Dokumen $no.$no2.$no3.$nodokumen&nbsp;<a href='#myModal" . $fbk['id'] . "' class='btn btn-success btn-xs' data-toggle='modal' data-id='" . $fbk['id'] . "'><span class='glyphicon glyphicon-zoom-in' aria-hidden='true' title='Lihat/Unduh Dokumen'></span></a>
													<input type='hidden' name='iddeldocasesi' value='$fbk[id]'>
													<input type='submit' name='hapusdocasesi' class='btn btn-danger btn-xs' onclick='return confirmation()' title='Hapus' value='Hapus'></form><br>";
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
																	<div class='modal-body'><embed src='foto_apl02/$fbk[file]' width='100%' height='500px'/>
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
					echo "<input type='hidden' name='id_asesi$kr[id]' value='$_SESSION[namauser]'>
													<input type='hidden' name='id_kriteria$kr[id]' value='$kr[id]'>
													<input type='hidden' name='id_skemakkni$kr[id]' value='$sk[id]'>
													<input type='hidden' name='id_elemenkompetensi$kr[id]' value='$el[id]'>
													<input type='hidden' name='id_jadwal$kr[id]' value='$_GET[idj]'>
													<input type='file' name='file$kr[id]' id='file$kr[id]' accept='.pdf, image/*' onchange='readURL(this);'>
													<button type='submit' class='btn btn-success btn-xs' name='unggahbukti$kr[id]'>Unggah/ Tambah Bukti</button><a href='#myModalLib" . $rowAgen['no_pendaftaran'] . "' class='btn btn-primary' data-toggle='modal' data-id='" . $rowAgen['id'] . "'>Tambahkan dokumen dari <em>Library</em> (pustaka anda)</a>";
					// mengambil dari library bukti unjuk kerja
					echo "<script>
													$(function(){
																$(document).on('click','.edit-record',function(e){
																	e.preventDefault();
																	$('#myModalLib" . $rowAgen['no_pendaftaran'] . "').modal('show');
																});
														});
												</script>
												<!-- Modal -->
													<div class='modal fade' id='myModalLib" . $rowAgen['no_pendaftaran'] . "' tabindex='-1' role='dialog' aria-labelledby='myModalLibLabel' aria-hidden='true'>
														<div class='modal-dialog'>
															<div class='modal-content'>
																<div style='overflow-y:auto;'>
																<div class='modal-header'>
																	<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Tutup</span></button>
																	<h4 class='modal-title' id='myModalLibLabel'><em>Library</em> (Pustaka) Dokumen " . $rowAgen['no_pendaftaran'] . "</h4>
																</div>
																<div class='modal-body'>
																	<div style='overflow-x:auto; overflow-y:auto;'>
																	<table id='example1' class='table table-bordered table-striped'>
																		<thead><tr><th>Dokumen</th><th>Aksi</th></tr></thead>
																		<tbody>";
					$sqlasesidocx = "SELECT DISTINCT `file` FROM `asesi_apl02doc` WHERE `id_asesi`='$rowAgen[no_pendaftaran]'";
					$asesidocx = $conn->query($sqlasesidocx);
					while ($pmx0 = $asesidocx->fetch_assoc()) {
						$sqlasesidocxy = "SELECT * FROM `asesi_apl02doc` WHERE `file`='$pmx0[file]' ORDER BY `id` DESC";
						$asesidocxy = $conn->query($sqlasesidocxy);
						$pmx = $asesidocxy->fetch_assoc();
						echo "<tr><td width='50%'>Nama Dokumen : $pmx[nama_doc]<br>Nomor Dokumen : <b><a href='#myModalM" . $pmx['id'] . "' data-toggle='modal' data-id='" . $pmx['id'] . "'>$pmx[nomor_doc]</a></b><br>Tanggal Dok.: <b>" . tgl_indo($pmx['tgl_doc']) . "</b></td>
																				<td width='50%'>
																				<form role='form' method='POST' enctype='multipart/form-data'>
																					<input type='hidden' name='id_asesi$kr[id]' value='$_SESSION[namauser]'>
																					<input type='hidden' name='id_kriteria$kr[id]' value='$kr[id]'>
																					<input type='hidden' name='id_skemakkni$kr[id]' value='$sk[id]'>
																					<input type='hidden' name='id_elemenkompetensi$kr[id]' value='$el[id]'>
																					<input type='hidden' name='id_jadwal$kr[id]' value='$_GET[idj]'>
																					<input type='hidden' name='file' value='$pmx[file]'>
																				<div class='col-md-6'>
																					<button type='submit' name='addfromlib' class='btn btn-primary btn-xs btn-block'>Gunakan</button></div>
																				</form>
																				<div class='col-md-6'><form role='form' method='POST' enctype='multipart/form-data'>
																				<input type='hidden' name='iddellibasesi' value='$pmx[id]'>
																				<input type='hidden' name='file' value='$pmx[file]'>
																				<input type='submit' name='hapuslibasesi' class='btn btn-danger btn-xs btn-block' onclick='return confirmation()' title='Hapus' value='Hapus'></form></div></td></tr>";
						echo "<script>
																					$(function(){
																								$(document).on('click','.edit-record',function(e){
																									e.preventDefault();
																									$('#myModalM" . $pmx['id'] . "').modal('show');
																								});
																						});
																				</script>
																				<!-- ModalM -->
																					<div class='modal fade' id='myModalM" . $pmx['id'] . "' tabindex='-1' role='dialog' aria-labelledby='myModalMLabel' aria-hidden='true'>
																						<div class='modal-dialog'>
																							<div style='overflow-y:auto;'>
																							<div class='modal-content'>
																								<div class='modal-header'>
																									<h4 class='modal-title' id='myModalMLabel'>Library Dokumen Porfolio " . $pmx['nama_doc'] . "</h4>
																								</div>
																								<div class='modal-body'><embed src='foto_apl02/$pmx[file]' width='100%' height='500px'/>
																								</div>
																								<div class='modal-footer'>
																									<em><font color='red'>klik di area gelap untuk menutup</font></em>
																								</div>
																							</div>
																							</div>
																						   </div><!-- End Overflow-->
																					</div>
																				<!-- ModalM End-->";
					}
					echo "</tbody>
																	</table>
																	</div>
																	<font color='red'><b>PERINGATAN :</b><br>Bila Anda menghapus data pada <em>library</em> akan menghapus data portfolio seluruh asesmen anda yang bersangkutan</font>
																</div>
																<div class='modal-footer'>
																	<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>
																</div>
															   </div><!-- End Overflow-->
															</div>
															</div>
													</div>
												<!-- Modal End -->";
					$no3++;
				}
				echo "</div>";
				$no2++;
			}
			echo "<div><!-- /.box-body-->
			</div></div><!-- /.box box-solid-->";
			$no++;
		}
	}
	echo "</div>
	      <div class='box-footer'>";
	// cek tandatangan digital
	$url =  "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
	$iddokumen = md5($url);
	$sqlcektandatangan = "SELECT * FROM `logdigisign` WHERE `id_dokumen`='$iddokumen'";
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
	echo "<div align='left' class='col-md-6 col-sm-6 col-xs-6'>
				<a class='btn btn-danger' id=reset-validate-form href='?module=jadwal'>Kembali</a>
		</div>
		<div align='right' class='col-md-6 col-sm-6 col-xs-6'>
				<!--<a href='admin/form-apl-02.php?ida=$_SESSION[namauser]&idj=$_GET[idj]' class='btn btn-primary'>Unduh Form Jawaban</a>-->
				<button type='submit' class='btn btn-success' name='simpandata'>Simpan Jawaban</button>
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
// Bagian Input Portfolio ==========================================================================================================
elseif ($_GET['module'] == 'portfolio') {
	$sqlasesi = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_SESSION[namauser]'";
	$getasesi = $conn->query($sqlasesi);
	$as = $getasesi->fetch_assoc();
	$max_upload = (int)(ini_get('upload_max_filesize'));
	$max_post = (int)(ini_get('post_max_size'));
	$memory_limit = (int)(ini_get('memory_limit'));
	$upload_mb = min($max_upload, $max_post, $memory_limit);
	function uploadDoc($file)
	{
		//$file_max_weight = 20000000; //Ukuran maksimal yg dibolehkan( 20 Mb)
		$ok_ext = array('jpg', 'png', 'gif', 'jpeg', 'JPG', 'PNG', 'GIF', 'JPEG', 'pdf', 'PDF'); // ekstensi yang diijinkan
		$destination = "foto_portfolio/"; // tempat buat upload
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
	if (isset($_REQUEST['tambahdocasesi'])) {
		$file = $_FILES['file'];
		// Apabila ada file yang diupload
		if (empty($_FILES['file']['tmp_name']) || !is_uploaded_file($_FILES['file']['tmp_name'])) {
			$query = "INSERT INTO `asesi_portfolio`(`id_asesi`, `id_jadwal`, `id_skemakkni`, `jenis_portfolio`, `peran_portfolio`, `nama_doc`, `tahun_doc`, `nomor_doc`, `tgl_doc`) VALUES ('$_SESSION[namauser]','$_GET[idj]','$_POST[id_skemakkni]','$_POST[jenis_portfolio]','$_POST[peran_portfolio]','$_POST[nama_dokumen]','$_POST[tahun_dokumen]','$_POST[nomor_dokumen]','$_POST[tgl_dokumen]')";
		} else {
			$alamatfile = uploadDoc($file);
			$query = "INSERT INTO `asesi_portfolio`(`id_asesi`, `id_jadwal`, `id_skemakkni`, `jenis_portfolio`, `peran_portfolio`,  `nama_doc`, `tahun_doc`, `nomor_doc`, `tgl_doc`, `file`) VALUES ('$_SESSION[namauser]','$_GET[idj]','$_POST[id_skemakkni]','$_POST[jenis_portfolio]','$_POST[peran_portfolio]','$_POST[nama_dokumen]','$_POST[tahun_dokumen]','$_POST[nomor_dokumen]','$_POST[tgl_dokumen]','$alamatfile')";
			$query2 = "INSERT INTO `asesi_portfoliolib`(`id_asesi`, `id_jadwal`, `id_skemakkni`, `jenis_portfolio`, `peran_portfolio`,  `nama_doc`, `tahun_doc`, `nomor_doc`, `tgl_doc`, `file`) VALUES ('$_SESSION[namauser]','$_GET[idj]','$_POST[id_skemakkni]','$_POST[jenis_portfolio]','$_POST[peran_portfolio]','$_POST[nama_dokumen]','$_POST[tahun_dokumen]','$_POST[nomor_dokumen]','$_POST[tgl_dokumen]','$alamatfile')";
		}
		$querycek = "SELECT * FROM `asesi_portfolio` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_jadwal`='$_GET[idj]' AND `id_skemakkni`='$_POST[id_skemakkni]' AND `nama_doc`='$_POST[nama_dokumen]' AND `tahun_doc`='$_POST[tahun_dokumen]' AND `nomor_doc`='$_POST[nomor_dokumen]' AND `tgl_doc`='$_POST[tgl_dokumen]'";
		$resultc = $conn->query($querycek);
		$row_cnt = $resultc->num_rows;
		if ($row_cnt == 0) {
			$conn->query($query);
			$conn->query($query2);
			//header('location:../../media.php?module=syarat&id=$_GET[id]');
			echo "<div class='alert alert-success alert-dismissible'>
		<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
		<h4><i class='icon fa fa-check'></i> Tambah Data Sukses</h4>
		Anda Telah Berhasil Mengunggah Data <b>Portfolio</b></div>";
		} else {
			echo "<script>alert('Maaf Data Tersebut Sudah Ada'); window.location = '../../media.php?module=portfolio&ida=$_GET[ida]&idj=$_GET[idj]'</script>";
		}
	}
	if (isset($_REQUEST['addfromlib'])) {
		$alamatfile = $_POST['file'];
		$query = "INSERT INTO `asesi_portfolio`(`id_asesi`, `id_jadwal`, `id_skemakkni`, `jenis_portfolio`, `peran_portfolio`,  `nama_doc`, `tahun_doc`, `nomor_doc`, `tgl_doc`, `file`) VALUES ('$_SESSION[namauser]','$_GET[idj]','$_POST[id_skemakkni]','$_POST[jenis_portfolio]','$_POST[peran_portfolio]','$_POST[nama_dokumen]','$_POST[tahun_dokumen]','$_POST[nomor_dokumen]','$_POST[tgl_dokumen]','$alamatfile')";
		$querycek = "SELECT * FROM `asesi_portfolio` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_jadwal`='$_GET[idj]' AND `id_skemakkni`='$_POST[id_skemakkni]' AND `nama_doc`='$_POST[nama_dokumen]' AND `tahun_doc`='$_POST[tahun_dokumen]' AND `nomor_doc`='$_POST[nomor_dokumen]' AND `tgl_doc`='$_POST[tgl_dokumen]'";
		$resultc = $conn->query($querycek);
		$row_cnt = $resultc->num_rows;
		//if ($row_cnt==0){
		$conn->query($query);
		//header('location:../../media.php?module=syarat&id=$_GET[id]');
		echo "<div class='alert alert-success alert-dismissible'>
		<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
		<h4><i class='icon fa fa-check'></i> Tambah Data Sukses</h4>
		Anda Telah Berhasil Mengunggah Data <b>Persyaratan Sertifikasi</b></div>";
		//}else{
		//	echo "<script>alert('Maaf Data Tersebut Sudah Ada'); window.location = '../../media.php?module=portfolio&ida=$_GET[ida]&idj=$_GET[idj]'</script>";
		//}
	}
	if (isset($_REQUEST['hapusdocasesi'])) {
		$cekdu = "SELECT * FROM `asesi_portfolio` WHERE `id`='$_POST[iddeldocasesi]'";
		$result = $conn->query($cekdu);
		$getr = $result->fetch_assoc();
		if ($result->num_rows != 0) {
			$conn->query("DELETE FROM `asesi_portfolio` WHERE `id`='$_POST[iddeldocasesi]'");
			unlink("foto_portfolio/" . $getr['file']);
			echo "<div class='alert alert-danger alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Hapus Data Sukses</h4>
			Anda Telah Berhasil Menghapus Data <b>Portfolio</b></div>";
		} else {
			echo "<script>alert('Maaf Dokumen Portfolio tersebut Tidak Ditemukan');</script>";
		}
	}
	if (isset($_REQUEST['hapuslibasesi'])) {
		$cekdu = "SELECT * FROM `asesi_portfoliolib` WHERE `id`='$_POST[iddellibasesi]' OR `file`='$_POST[file]'";
		$result = $conn->query($cekdu);
		$getr = $result->fetch_assoc();
		if ($result->num_rows != 0) {
			$conn->query("DELETE FROM `asesi_portfoliolib` WHERE `id`='$_POST[iddellibasesi]' OR `file`='$_POST[file]'");
			$conn->query("DELETE FROM `asesi_portfolio` WHERE `id`='$_POST[iddellibasesi]' OR `file`='$_POST[file]'");
			unlink("foto_portfolio/" . $getr['file']);
			echo "<div class='alert alert-danger alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Hapus Data Sukses</h4>
			Anda Telah Berhasil Menghapus Data <b>Portfolio</b></div>";
		} else {
			echo "<script>alert('Maaf Persyaratan Skema dengan tersebut Tidak Ditemukan');</script>";
		}
	}
	$sqlgetjadwal = "SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
	$getjadwal = $conn->query($sqlgetjadwal);
	$jdw = $getjadwal->fetch_assoc();
	$sqlgetskema = "SELECT * FROM `skema_kkni` WHERE `id`='$jdw[id_skemakkni]'";
	$getskema = $conn->query($sqlgetskema);
	$gs = $getskema->fetch_assoc();
	echo "
    <!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
        Persyaratan Portfolio Uji Kompetensi
        <small>Input Data</small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li><a href='media.php?module=jadwal'><i class='fa fa fa-calendar'></i> Jadwal Asesmen</a></li>
        <li class='active'>Data Persyaratan Portfolio</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class='content'>
      <div class='row'>
        <div class='col-xs-12'>
          <div class='box-group' id='accordion'>
	<!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
	<div class='panel box box-success'>
	  <div class='box-header with-border'>
		<h4 class='box-title'>
		  <a data-toggle='collapse' data-parent='#accordion' href='#collapseZero'>
			Dokumen Portfolio Uji Kompetensi Skema $gs[judul]
		  </a>
		</h4>
	  </div>
	  <div id='collapseThree' class='panel-collapse collapse in'>
		<div class='box-body'>";
	echo "<p>Untuk mengikuti uji kompetensi pada skema ini, silahkan lengkapi persyaratan dan dokumen portfolio berikut:</p>";
	echo "<h3>Input Portfolio Persyaratan Uji Kompetensi</h3>
				<span>Masukkan data pengalaman/portfolio Anda, beserta informasi yang dibutuhkan, kemudian klik <b><a class='btn btn-primary btn-xs'>Tambahkan</a></b></span> 
			<form role='form' method='POST' enctype='multipart/form-data'>
			<input type='hidden' name='id_skemakkni' value='$gs[id]'>
			<div class='row'>
				<div class='box-body'>
				  <div class='col-md-9'>
					<div class='form-group'>
						<label>Jenis Pengalaman/ Portfolio</label>
						<select name='jenis_portfolio' class='form-control' id='jenis_portfolio'>
							<option>-- Pilih Jenis Portfolio --</option>
							<option value='Karya Ilmiah'>Karya Ilmiah/ Penelitian/ Karya Tulis</option>
							<option value='Pelatihan'>Diklat/ Bimtek/ Seminar/ Workshop/ Lokakarya/ Kursus</option>
							<option value='Pengalaman Kerja'>Pengalaman Kerja/ Magang</option>";
	echo "</select>";
	echo "</div>
				  </div>
				  <div class='col-md-3'>
					<div class='form-group'>
						<label>Peran dalam Pengalaman</label>
						<select name='peran_portfolio' class='form-control' id='peran_portfolio'>
							<option value=''></option>";
	echo "</select>";
	echo "</div>
				  </div>
				  <div class='col-md-6'>
					<div class='form-group'>
						<label>Nama Dokumen</label>
						<input type='text' name='nama_dokumen' class='form-control' placeholder='Nama Pengalaman' required>
					</div>
				  </div>
				  <div class='col-md-6'>
					<div class='form-group'>
						<label>Nomor Dokumen/SK</label>
						<input type='text' name='nomor_dokumen' class='form-control' placeholder='Nomor Dokumen' required>
					</div>
				  </div>
				  <div class='col-md-3'>
					<div class='form-group'>
						<label>Tahun Dokumen/SK</label>
						<select name='tahun_dokumen' class='form-control'>";
	$tahunskr = date("Y");
	$tahunnya = intval(trim(substr($as['tgl_lahir'], 0, 4))) + 5;
	while ($tahunnya <= $tahunskr) {
		echo "<option value='$tahunnya'>$tahunnya</option>";
		$tahunnya = $tahunnya + 1;
	}
	echo "</select>
					</div>
				  </div>
				  <div class='col-md-3'>
					<div class='form-group'>
						<label>Tanggal Dokumen/SK</label>
						<input type='date' name='tgl_dokumen' class='form-control' required>
					</div>
				  </div>
				  <div class='col-md-3'>
					<div class='form-group'>
						<label>File Pendukung</label>
						<label for='fileID'>
						<input type='file' name='file' id='fileID' accept='.pdf, image/*' onchange='readURL(this);'>
						<span>File pdf/jpg/png, maks. $upload_mb Mb</span>
					</div>
				  </div>
				  <div class='col-md-3'>
					<div class='form-group'>
					<button type='submit' class='btn btn-primary' name='tambahdocasesi'>Tambahkan</button>
					</div>
				  </div>
			</form>
				  <div class='col-md-12'>
					<div class='form-group'>
					<a href='#myModalLib" . $as['no_pendaftaran'] . "' class='btn btn-primary' data-toggle='modal' data-id='" . $as['id'] . "'>Tambahkan dokumen dari <em>Library</em> (pustaka anda)</a>
					</div>
				  </div>
			<script>
				$(function(){
							$(document).on('click','.edit-record',function(e){
								e.preventDefault();
								$('#myModalLib" . $as['no_pendaftaran'] . "').modal('show');
							});
					});
			</script>
			<!-- Modal -->
				<div class='modal fade' id='myModalLib" . $as['no_pendaftaran'] . "' tabindex='-1' role='dialog' aria-labelledby='myModalLibLabel' aria-hidden='true'>
					<div class='modal-dialog'>
						<div class='modal-content'>
						    <div style='overflow-y:auto;'>
							<div class='modal-header'>
								<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Tutup</span></button>
								<h4 class='modal-title' id='myModalLibLabel'><em>Library</em> (Pustaka) Dokumen " . $as['no_pendaftaran'] . "</h4>
							</div>
							<div class='modal-body'>
								<div style='overflow-x:auto; overflow-y:auto;'>
								<table id='example1' class='table table-bordered table-striped'>
									<thead><tr><th>Dokumen</th><th>Aksi</th></tr></thead>
									<tbody>";
	$sqlasesidocx = "SELECT DISTINCT `file` FROM `asesi_portfoliolib` WHERE `id_asesi`='$as[no_pendaftaran]'";
	$asesidocx = $conn->query($sqlasesidocx);
	while ($pmx0 = $asesidocx->fetch_assoc()) {
		$sqlasesidocxy = "SELECT * FROM `asesi_portfoliolib` WHERE `file`='$pmx0[file]' ORDER BY `id` DESC";
		$asesidocxy = $conn->query($sqlasesidocxy);
		$pmx = $asesidocxy->fetch_assoc();
		echo "<tr><td width='50%'>Nama Dokumen : $pmx[nama_doc]<br>Nomor Dokumen : <b><a href='#myModalM" . $pmx['id'] . "' data-toggle='modal' data-id='" . $pmx['id'] . "'>$pmx[nomor_doc]</a></b><br>Tanggal Dok.: <b>" . tgl_indo($pmx['tgl_doc']) . "</b></td>
											<td width='50%'>
											<form role='form' method='POST' enctype='multipart/form-data'>
												<input type='hidden' name='id_skemakkni' value='$gs[id]'>
												<input type='hidden' name='nama_dokumen' value='$pmx[nama_doc]'>
												<input type='hidden' name='nomor_dokumen' value='$pmx[nomor_doc]'>
												<input type='hidden' name='tgl_dokumen' value='$pmx[tgl_doc]'>
												<input type='hidden' name='tahun_dokumen' value='$pmx[tahun_doc]'>
												<input type='hidden' name='jenis_portfolio' value='$pmx[jenis_portfolio]'>
												<input type='hidden' name='peran_portfolio' value='$pmx[peran_portfolio]'>
												<input type='hidden' name='file' value='$pmx[file]'>
											<div class='col-md-6'>
												<button type='submit' name='addfromlib' class='btn btn-primary btn-xs btn-block'>Gunakan</button></div>
											</form>
											<div class='col-md-6'><form role='form' method='POST' enctype='multipart/form-data'>
											<input type='hidden' name='iddellibasesi' value='$pmx[id]'>
											<input type='hidden' name='file' value='$pmx[file]'>
											<input type='submit' name='hapuslibasesi' class='btn btn-danger btn-xs btn-block' onclick='return confirmation()' title='Hapus' value='Hapus'></form></div></td></tr>";
		echo "<script>
												$(function(){
															$(document).on('click','.edit-record',function(e){
																e.preventDefault();
																$('#myModalM" . $pmx['id'] . "').modal('show');
															});
													});
											</script>
											<!-- ModalM -->
												<div class='modal fade' id='myModalM" . $pmx['id'] . "' tabindex='-1' role='dialog' aria-labelledby='myModalMLabel' aria-hidden='true'>
													<div class='modal-dialog'>
													    <div style='overflow-y:auto;'>
														<div class='modal-content'>
															<div class='modal-header'>
																<h4 class='modal-title' id='myModalMLabel'>Library Dokumen Porfolio " . $pmx['nama_doc'] . "</h4>
															</div>
															<div class='modal-body'><embed src='foto_portfolio/$pmx[file]' width='100%' height='500px'/>
															</div>
															<div class='modal-footer'>
																<em><font color='red'>klik di area gelap untuk menutup</font></em>
															</div>
														</div>
														</div>
													   </div><!-- End Overflow-->
												</div>
											<!-- ModalM End-->";
	}
	echo "</tbody>
								</table>
								</div>
								<font color='red'><b>PERINGATAN :</b><br>Bila Anda menghapus data pada <em>library</em> akan menghapus data portfolio seluruh asesmen anda yang bersangkutan</font>
							</div>
							<div class='modal-footer'>
								<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>
							</div>
						   </div><!-- End Overflow-->
						</div>
						</div>
				</div>
			<!-- Modal End -->
				</div>
				</div>
			<div class='row'>
		    <div class='box-body'>
			<h3>Data Dokumen Portfolio Persyaratan</h3>
			<div style='overflow-x:auto;'>
			<table id='table-example' class='table table-bordered table-striped'>
			<thead><tr><th>No.</th><th>Portfolio<th>File Pendukung</th><th>Status</th></tr></thead>
			<tbody>";
	$no = 1;
	$sqlasesidoc = "SELECT * FROM `asesi_portfolio` WHERE `id_asesi`='$as[no_pendaftaran]' AND `id_jadwal`='$_GET[idj]' ORDER BY `id` DESC";
	$asesidoc = $conn->query($sqlasesidoc);
	$jumpm = $asesidoc->num_rows;
	while ($pm = $asesidoc->fetch_assoc()) {
		switch ($pm['status']) {
			default:
				$statusnya = "<font color='blue'><b>Menunggu<br />Persetujuan</b></font>";
				break;
			case "A":
				$statusnya = "<font color='green'><b>Disetujui</b></font>";
				break;
			case "R":
				$statusnya = "<font color='red'><b>Ditolak</b></font>";
				break;
		}
		echo "<tr class=gradeX><td>$no</td><td><b>$pm[nama_doc]</b><br>No. Dokumen : <b><a href='#myModal" . $pm['id'] . "' data-toggle='modal' data-id='" . $pm['id'] . "'>$pm[nomor_doc]</a></b><br>Tanggal Dok.: <b>" . tgl_indo($pm['tgl_doc']) . "</b></td><td>";
		if (!empty($pm['file'])) {
			echo "<a class='btn btn-success btn-xs'><span class='glyphicon glyphicon-ok' aria-hidden='true' title='Dokumen Berhasil Diunggah'></span></a>";
			echo "&nbsp;<a href='#myModal" . $pm['id'] . "' class='btn btn-primary btn-xs' data-toggle='modal' data-id='" . $pm['id'] . "'><span class='glyphicon glyphicon-zoom-in' aria-hidden='true' title='Lihat/Unduh Dokumen'></span></a>";
		} else {
			echo "<span class='text-red'>Tidak ada dokumen</span>";
		}
		echo "</td><td>$statusnya";
		if ($pm['status'] == 'P') {
			echo "<br /><form role='form' method='POST' enctype='multipart/form-data'>
				<input type='hidden' name='iddeldocasesi' value='$pm[id]'>
				<input type='submit' name='hapusdocasesi' class='btn btn-danger btn-xs' onclick='return confirmation()' title='Hapus' value='Hapus'></form>";
		}
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
							</div>
							<div class='modal-body'><embed src='foto_portfolio/$pm[file]' width='100%' height='500px'/>
							</div>
							<div class='modal-footer'>
								<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>
							</div>
						</div>
						</div>
				</div>";
	}
	echo "</tbody></table></div>";
	if ($jumpm == 0) {
		echo "<br><p><font color='red'>Proses asesmen dapat dilakukan bila Anda telah mengunggah dokumen persyaratan portfolio.</font></p>";
	}
	echo "<form role='form' method='POST' enctype='multipart/form-data'>
			<div align='left' class='col-md-6 col-sm-6 col-xs-6'>
				<a class='btn btn-default' id=reset-validate-form href='?module=jadwal'>Kembali</a>
			</div>
			<div align='right' class='col-md-6 col-sm-6 col-xs-6'>";
	echo "</div>
			</form>";
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
// Bagian Input Hasil Asesmen FORM-FR.AK.01
elseif ($_GET['module'] == 'form-fr-ak-01') {
	$sqllogin = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_SESSION[namauser]'";
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
	  Input FR.AK.01. FORMULIR PERSETUJUAN ASESMEN DAN KERAHASIAAN
        <small>Skema $sk[judul]</small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li class='active'>FR.AK.01</li>
      </ol>
    </section>";
	if (isset($_REQUEST['simpan'])) {
		$sqlcekgetak01 = "SELECT * FROM `asesmen_ak01` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_jadwal`='$_GET[idj]'";
		$cekgetak01 = $conn->query($sqlcekgetak01);
		$cekak01 = $cekgetak01->num_rows;
		$tglsekarang = date("Y-m-d");
		if ($cekak01 > 0) {
			$folderPath = "foto_tandatangan/";
			if (empty($_POST['signed'])) {
				echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Persetujuan Anda berhasil disimpan</h4>
				Terimakasih, Anda telah melakukan <b>Persetujuan Asesmen dan Kerahasiaan untuk Skema $sk[judul].</b><br>
				<a class='btn btn-warning form-control' href='?module=jadwal'>Kembali</a></div>";
				$sqlinputak01 = "UPDATE `asesmen_ak01` SET `persetujuan_asesi`='$_POST[persetujuan]',`tanggal_asesittd`='$tglsekarang' WHERE `id_asesi`='$_SESSION[namauser]' AND `id_skemakkni`='$jd[id_skemakkni]' AND `id_jadwal`='$_GET[idj]'";
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
				$sqlinputdigisign = "INSERT INTO `logdigisign`(`id_dokumen`, `url_ditandatangani`, `nama_dokumen`, `penandatangan`, `file`, `ip`) VALUES ('$iddokumen','$escaped_url','FR.AK.01. FORMULIR PERSETUJUAN ASESMEN DAN KERAHASIAAN','$_SESSION[namalengkap]','$file','$alamatip')";
				$conn->query($sqlinputdigisign);
				echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Persetujuan Anda berhasil disimpan</h4>
				Terimakasih, Anda telah melakukan <b>Persetujuan Asesmen dan Kerahasiaan untuk Skema $sk[judul], dan tanda tangan telah ditambahkan</b><br>
				<a class='btn btn-warning form-control' href='?module=jadwal'>Kembali</a></div>";
				$sqlinputak01 = "UPDATE `asesmen_ak01` SET `persetujuan_asesi`='$_POST[persetujuan]',`tanggal_asesittd`='$tglsekarang' WHERE `id_asesi`='$_SESSION[namauser]' AND `id_skemakkni`='$jd[id_skemakkni]' AND `id_jadwal`='$_GET[idj]'";
				$conn->query($sqlinputak01);
			}
		} else {
			$folderPath = "foto_tandatangan/";
			if (empty($_POST['signed'])) {
				echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Persetujuan Anda berhasil disimpan</h4>
				Terimakasih, Anda telah melakukan <b>Persetujuan Asesmen dan Kerahasiaan untuk Skema $sk[judul].</b><br>
				<a class='btn btn-warning form-control' href='?module=jadwal'>Kembali</a></div>";
				$sqlinputak01 = "INSERT INTO `asesmen_ak01`(`id_asesi`, `id_skemakkni`, `id_jadwal`, `VP`, `CL`, `DPT`, `DPL`, `PW`, `persetujuan_asesi`, `tanggal_asesittd`) VALUES ('$_SESSION[namauser]','$jd[id_skemakkni]','$_GET[idj]','$_POST[checkboxVP]','$_POST[checkboxCL]','$_POST[checkboxDPT]','$_POST[checkboxDPL]','$_POST[checkboxPW]','$_POST[persetujuan]','$tglsekarang')";
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
				$sqlinputdigisign = "INSERT INTO `logdigisign`(`id_dokumen`, `id_asesi`, `id_skema`, `url_ditandatangani`, `nama_dokumen`, `penandatangan`, `file`, `ip`) VALUES ('$iddokumen','$_SESSION[namauser]','$_GET[id]'	,'$escaped_url','FR-APL-01. FORMULIR PERMOHONAN SERTIFIKASI KOMPETENSI','$rowAgen[nama]','$file','$alamatip')";
				$conn->query($sqlinputdigisign);
				echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Persetujuan Anda berhasil disimpan</h4>
				Terimakasih, Anda telah melakukan <b>Persetujuan Asesmen dan Kerahasiaan untuk Skema $sk[judul], dan tanda tangan telah ditambahkan</b><br>
				<a class='btn btn-warning form-control' href='?module=jadwal'>Kembali</a></div>";
				if (isset($_POST['checkboxVP'])) {
					$checkboxVP = $_POST['checkboxVP'];
				} else {
					$checkboxVP = '0';
				}
				if (isset($_POST['checkboxCL'])) {
					$checkboxCL = $_POST['checkboxCL'];
				} else {
					$checkboxCL = '0';
				}
				if (isset($_POST['checkboxDPT'])) {
					$checkboxDPT = $_POST['checkboxDPT'];
				} else {
					$checkboxDPT = '0';
				}
				if (isset($_POST['checkboxDPL'])) {
					$checkboxDPL = $_POST['checkboxDPL'];
				} else {
					$checkboxDPL = '0';
				}
				if (isset($_POST['checkboxPW'])) {
					$checkboxPW = $_POST['checkboxPW'];
				} else {
					$checkboxPW = '0';
				}
				$sqlinputak01 = "INSERT INTO `asesmen_ak01`(`id_asesi`, `id_skemakkni`, `id_jadwal`, `VP`, `CL`, `DPT`, `DPL`, `PW`, `persetujuan_asesi`, `tanggal_asesittd`) VALUES ('$_SESSION[namauser]','$jd[id_skemakkni]','$_GET[idj]','$checkboxVP','$checkboxCL','$checkboxDPT','$checkboxDPL','$checkboxPW','$_POST[persetujuan]','$tglsekarang')";
				$conn->query($sqlinputak01);
			}
		}
	}
	$sqlgetak01 = "SELECT * FROM `asesmen_ak01` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_jadwal`='$_GET[idj]'";
	$getak01 = $conn->query($sqlgetak01);
	$jjw = $getak01->fetch_assoc();
	$tgl_sekarang = date("Y-m-d");
	//if ($tgl_sekarang>=$jd['tgl_asesmen']){
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
	echo " disabled> TL : Verifikasi Portfolio";
	echo "</td><td>";
	echo "<input type='checkbox' class='flat-red' name='checkboxCL' id='optionsCL' value='1'";
	if ($jjw['CL'] == "1") {
		echo "checked";
	} else {
		echo "";
	}
	echo " disabled> L : Observasi Langsung";
	echo "</td>
							</tr>
							<tr><td colspan='2'>";
	echo "<input type='checkbox' class='flat-red' name='checkboxDPT' id='optionsDPT' value='1'";
	if ($jjw['DPT'] == "1") {
		echo "checked";
	} else {
		echo "";
	}
	echo " disabled> T : Hasil Tes Tulis";
	echo "</td>
							</tr>
							<tr><td colspan='2'>";
	echo "<input type='checkbox' class='flat-red' name='checkboxDPL' id='optionsDPL' value='1'";
	if ($jjw['DPL'] == "1") {
		echo "checked";
	} else {
		echo "";
	}
	echo " disabled> L : Hasil Tes Lisan";
	echo "</td>
							</tr>
							<tr><td colspan='2'>";
	echo "<input type='checkbox' class='flat-red' name='checkboxPW' id='optionsPW' value='1'";
	if ($jjw['PW'] == "1") {
		echo "checked";
	} else {
		echo "";
	}
	echo " disabled> L : Hasil Wawancara";
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
	$tgl_cetak = tgl_indo($tanggal);
	$jadwal = $dayList[$day] . ", " . $tgl_cetak;
	$jamasesmen = "Pukul " . $jd['jam_asesmen'] . " - ";
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
						<p><b>Asesi :</b><br />
						<input type='checkbox' class='flat-red' name='persetujuan' id='optionsPersetujuan' value='Y'";
	if ($jjw['persetujuan_asesi'] == "Y") {
		echo "checked";
	} else {
		echo "";
	}
	echo " required> Saya setuju mengikuti asesmen dengan pemahaman bahwa informasi yang dikumpulkan hanya digunakan untuk pengembangan profesional dan hanya dapat diakses oleh orang tertentu saja.
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
						<div class='col-md-4 col-sm-12 col-xs-12'>
								<a class='btn btn-danger form-control' id=reset-validate-form href='?module=jadwal'>Kembali</a>
						</div>
						<div class='col-md-4 col-sm-12 col-xs-12'>
								<a href='asesor/form-fr-ak-01.php?ida=$_SESSION[namauser]&idj=$_GET[idj]' class='btn btn-primary form-control'>Unduh Formulir</a>
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
	/* }else{
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
	} */
}
// Bagian Input FORMULIR REKAMAN ASESMEN KOMPETENSI FORM-FR.AK.02
elseif ($_GET['module'] == 'form-fr-ak-02') {
	$sqllogin = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_SESSION[namauser]'";
	$login = $conn->query($sqllogin);
	$ketemu = $login->num_rows;
	$rowAgen = $login->fetch_assoc();
	$sqlgetjadwal = "SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
	$getjadwal = $conn->query($sqlgetjadwal);
	$jd = $getjadwal->fetch_assoc();
	$sqlgetskema = "SELECT * FROM `skema_kkni` WHERE `id`='$jd[id_skemakkni]'";
	$getskema = $conn->query($sqlgetskema);
	$sk = $getskema->fetch_assoc();
	/* $sqlgetasesordata="SELECT * FROM `asesor` WHERE `no_ktp`='$_SESSION[namauser]'";
$getasesordata=$conn->query($sqlgetasesordata);
$asr=$getasesordata->fetch_assoc(); */
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
		$folderPath = "foto_tandatangan/";
		if (empty($_POST['signed'])) {
			/* //unit kompetensi
			$sqlgetunitkompetensi0="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
			$getunitkompetensi0=$conn->query($sqlgetunitkompetensi0);
			//while unitkompetensi ==================================================================
			while ($unk0=$getunitkompetensi0->fetch_assoc()){
				$sqlcekgetak01b="SELECT * FROM `asesmen_ak02` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_unitkompetensi`='$unk0[id]'";
				$cekgetak01b=$conn->query($sqlcekgetak01b);
				$jumak02unit=$cekgetak01b->num_rows;
				$varpostCL='checkboxCL'.$unk0['id'];
				if (isset($_POST[$varpostCL])){
					$postCL=$_POST[$varpostCL];
				}else{
					$postCL='0';
				}
				$varpostVP='checkboxVP'.$unk0['id'];
				if (isset($_POST[$varpostVP])){
					$postVP=$_POST[$varpostVP];
				}else{
					$postVP='0';
				}
				$varpostPW='checkboxPW'.$unk0['id'];
				if (isset($_POST[$varpostPW])){
					$postPW=$_POST[$varpostPW];
				}else{
					$postPW='0';
				}
				$varpostDPL='checkboxDPL'.$unk0['id'];
				if (isset($_POST[$varpostDPL])){
					$postDPL=$_POST[$varpostDPL];
				}else{
					$postDPL='0';
				}
				$varpostDPT='checkboxDPT'.$unk0['id'];
				if (isset($_POST[$varpostDPT])){
					$postDPT=$_POST[$varpostDPT];
				}else{
					$postDPT='0';
				}
				$varpostCUP='checkboxCUP'.$unk0['id'];
				if (isset($_POST[$varpostCUP])){
					$postCUP=$_POST[$varpostCUP];
				}else{
					$postCUP='0';
				}
				$varpostLainnya='checkboxLainnya'.$unk0['id'];
				if (isset($_POST[$varpostLainnya])){
					$postLainnya=$_POST[$varpostLainnya];
				}else{
					$postLainnya='0';
				}
				if ($jumak02unit>0){
					// update data
					$sqlinputak01="UPDATE `asesmen_ak02` SET `CL`='$postCL', `VP`='$postVP', `PW`='$postPW', `DPL`='$postDPL', `DPT`='$postDPT', `CUP`='$postCUP', `Lainnya`='$postLainnya',`tanggal`='$tglsekarang' WHERE `id_asesi`='$_GET[ida]' AND `id_unitkompetensi`='$unk0[id]' AND `id_jadwal`='$_GET[idj]'";
					$conn->query($sqlinputak01);
				}else{
					// input data
					$sqlinputak01="INSERT INTO `asesmen_ak02`(`id_asesi`, `id_unitkompetensi`, `id_jadwal`, `CL`, `VP`, `PW`, `DPL`, `DPT`, `CUP`, `Lainnya`, `tanggal`) VALUES ('$_GET[ida]', '$unk0[id]', '$_GET[idj]', '$postCL', '$postVP', '$postPW', '$postDPL', '$postDPT', '$postCUP', '$postLainnya', '$tglsekarang')";
					$conn->query($sqlinputak01);
				}
			}
			$sqlinputak02="UPDATE `asesi_asesmen` SET `umpan_balik`='".$_POST['umpan_balik']."', `tindak_lanjut_ak02`='".$_POST['tindaklanjut']."' WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]'";
			$conn->query($sqlinputak02); */
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
			$sqlinputdigisign = "INSERT INTO `logdigisign`(`id_dokumen`, `id_asesi`, `id_skema`, `url_ditandatangani`, `nama_dokumen`, `penandatangan`, `file`, `ip`) VALUES ('$iddokumen','$_SESSION[namauser]','$_GET[id]'	,'$escaped_url','FR-APL-01. FORMULIR PERMOHONAN SERTIFIKASI KOMPETENSI','$rowAgen[nama]','$file','$alamatip')";
			$conn->query($sqlinputdigisign);
			/* //unit kompetensi
			$sqlgetunitkompetensi0="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
			$getunitkompetensi0=$conn->query($sqlgetunitkompetensi0);
			//while unitkompetensi ==================================================================
			while ($unk0=$getunitkompetensi0->fetch_assoc()){
				$sqlcekgetak01b="SELECT * FROM `asesmen_ak02` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_jadwal`='$_GET[idj]' AND `id_unitkompetensi`='$unk0[id]'";
				$cekgetak01b=$conn->query($sqlcekgetak01b);
				$jumak02unit=$cekgetak01b->num_rows;
				$varpostCL='checkboxCL'.$unk0['id'];
				if (isset($_POST[$varpostCL])){
					$postCL=$_POST[$varpostCL];
				}else{
					$postCL='0';
				}
				$varpostVP='checkboxVP'.$unk0['id'];
				if (isset($_POST[$varpostVP])){
					$postVP=$_POST[$varpostVP];
				}else{
					$postVP='0';
				}
				$varpostPW='checkboxPW'.$unk0['id'];
				if (isset($_POST[$varpostPW])){
					$postPW=$_POST[$varpostPW];
				}else{
					$postPW='0';
				}
				$varpostDPL='checkboxDPL'.$unk0['id'];
				if (isset($_POST[$varpostDPL])){
					$postDPL=$_POST[$varpostDPL];
				}else{
					$postDPL='0';
				}
				$varpostDPT='checkboxDPT'.$unk0['id'];
				if (isset($_POST[$varpostDPT])){
					$postDPT=$_POST[$varpostDPT];
				}else{
					$postDPT='0';
				}
				$varpostCUP='checkboxCUP'.$unk0['id'];
				if (isset($_POST[$varpostCUP])){
					$postCUP=$_POST[$varpostCUP];
				}else{
					$postCUP='0';
				}
				$varpostLainnya='checkboxLainnya'.$unk0['id'];
				if (isset($_POST[$varpostLainnya])){
					$postLainnya=$_POST[$varpostLainnya];
				}else{
					$postLainnya='0';
				}
				if ($jumak02unit>0){
					// update data
					$sqlinputak01="UPDATE `asesmen_ak02` SET `CL`='$postCL', `VP`='$postVP', `PW`='$postPW', `DPL`='$postDPL', `DPT`='$postDPT', `CUP`='$postCUP', `Lainnya`='$postLainnya',`tanggal`='$tglsekarang' WHERE `id_asesi`='$_SESSION[namauser]' AND `id_unitkompetensi`='$unk0[id]' AND `id_jadwal`='$_GET[idj]'";
					$conn->query($sqlinputak01);
				}else{
					// input data
					$sqlinputak01="INSERT INTO `asesmen_ak02`(`id_asesi`, `id_unitkompetensi`, `id_jadwal`, `CL`, `VP`, `PW`, `DPL`, `DPT`, `CUP`, `Lainnya`, `tanggal`) VALUES ('$_SESSION[namauser]', '$unk0[id]', '$_GET[idj]', '$postCL', '$postVP', '$postPW', '$postDPL', '$postDPT', '$postCUP', '$postLainnya', '$tglsekarang')";
					$conn->query($sqlinputak01);
				}
			}
			$sqlinputak02="UPDATE `asesi_asesmen` SET `umpan_balik`='$_POST[umpan_balik]', `tindak_lanjut_ak02`='$_POST[tindaklanjut]' WHERE `id_asesi`='$_SESSION[namauser]' AND `id_jadwal`='$_GET[idj]'";
			$conn->query($sqlinputak02); */
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
		echo " disabled></td>";
		echo "<td><input type='checkbox' class='flat-red' name='checkboxVP" . $unk2['id'] . "' id='optionsVP" . $unk2['id'] . "' value='1'";
		if ($jjw['VP'] == "1") {
			echo "checked";
		} else {
			echo "";
		}
		echo " disabled></td>";
		echo "<td><input type='checkbox' class='flat-red' name='checkboxPW" . $unk2['id'] . "' id='optionsPW" . $unk2['id'] . "' value='1'";
		if ($jjw['PW'] == "1") {
			echo "checked";
		} else {
			echo "";
		}
		echo " disabled></td>";
		echo "<td><input type='checkbox' class='flat-red' name='checkboxDPL" . $unk2['id'] . "' id='optionsDPL" . $unk2['id'] . "' value='1'";
		if ($jjw['DPL'] == "1") {
			echo "checked";
		} else {
			echo "";
		}
		echo " disabled></td>";
		echo "<td><input type='checkbox' class='flat-red' name='checkboxDPT" . $unk2['id'] . "' id='optionsDPT" . $unk2['id'] . "' value='1'";
		if ($jjw['DPT'] == "1") {
			echo "checked";
		} else {
			echo "";
		}
		echo " disabled></td>";
		echo "<td><input type='checkbox' class='flat-red' name='checkboxCUP" . $unk2['id'] . "' id='optionsCUP" . $unk2['id'] . "' value='1'";
		if ($jjw['CUP'] == "1") {
			echo "checked";
		} else {
			echo "";
		}
		echo " disabled></td>";
		echo "<td><input type='checkbox' class='flat-red' name='checkboxLainnya" . $unk2['id'] . "' id='optionsLainnya" . $unk2['id'] . "' value='1'";
		if ($jjw['Lainnya'] == "1") {
			echo "checked";
		} else {
			echo "";
		}
		echo " disabled></td>";
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
						(Masukkan pekerjaan tambahan dan asesmen yang diperlukan untuk mencapai kompetensi)</td><td colspan='2'><textarea name='tindaklanjut' class='form-control' disabled>$getk[tindak_lanjut_ak02]</textarea></td></tr>
						<tr><td width='25%'>Komentar/ Observasi oleh asesor</td><td colspan='2'><textarea name='umpan_balik' class='form-control' disabled>$getk[umpan_balik]</textarea></td></tr>
					</table>
					<p><b>LAMPIRAN DOKUMEN:</b><br>
					<ol>";
	echo "<li><a href='asesor/form-apl-01.php?ida=$getk[id_asesi]&idj=$_GET[idj]' target='_blank'>Dokumen APL 01 Peserta</a></li>";
	$sqlgetapl02 = "SELECT  * FROM `asesi_apl02` WHERE `id_asesi`='$getk[id_asesi]' AND `id_jadwal`='$_GET[idj]'";
	$getapl02 = $conn->query($sqlgetapl02);
	$jumapl02 = $getapl02->num_rows;
	if ($jumapl02 > 0) {
		echo "<li><a href='asesor/form-apl-02.php?ida=$getk[id_asesi]&idj=$_GET[idj]' target='_blank'>Dokumen APL 02 Peserta</a></li>";
	} else {
		echo "<li>Dokumen APL 02 Peserta</li>";
	}
	echo "<li><a href='asesor/portfolio-asesi.php?ida=$getk[id_asesi]&idj=$_GET[idj]' target='_blank'>Bukti-bukti berkualitas Peserta</a></li>";
	$sqlgetia11 = "SELECT * FROM `asesmen_ia11` WHERE `id_asesi`='$getk[id_asesi]' AND `id_jadwal`='$_GET[idj]'";
	$getia11 = $conn->query($sqlgetia11);
	$jumia11 = $getia11->num_rows;
	if ($jumia11 > 0) {
		echo "<li><a href='asesor/form-fr-ia-11.php?ida=$getk[id_asesi]&idj=$_GET[idj]' target='_blank'>Tinjauan proses asesmen</a></li>";
	} else {
		echo "<li>Tinjauan proses asesmen</li>";
	}
	echo "</ol></p>
					<p><b>Asesi :</b>
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
					<div class='col-md-4 col-sm-12 col-xs-12'>
							<a class='btn btn-danger form-control' id=reset-validate-form href='?module=pesertaasesmen&idj=$_GET[idj]'>Kembali</a>
					</div>
					<div class='col-md-4 col-sm-12 col-xs-12'>
							<a href='asesor/form-fr-ak-02.php?ida=$_SESSION[namauser]&idj=$_GET[idj]' class='btn btn-primary form-control'>Unduh Formulir</a>
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
// Bagian Input Umpan Balik dan Catatan Asesmen FORM-FR.AK.03
elseif ($_GET['module'] == 'form-fr-ak-03') {
	$sqllogin = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_SESSION[namauser]'";
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
	  Input FR.AK.03. FORMULIR UMPAN BALIK DAN CATATAN ASESMEN
        <small>Skema $sk[judul]</small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li class='active'>FR.AK.03</li>
      </ol>
    </section>";
	if (isset($_REQUEST['simpan'])) {
		$sqlcekgetak01 = "SELECT * FROM `asesmen_ak03` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_jadwal`='$_GET[idj]'";
		$cekgetak01 = $conn->query($sqlcekgetak01);
		$cekak01 = $cekgetak01->num_rows;
		$tglsekarang = date("Y-m-d");
		if ($cekak01 > 0) {
			$folderPath = "foto_tandatangan/";
			if (empty($_POST['signed'])) {
				echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Umpan Balik dan Catatan Asesmen berhasil disimpan</h4>
				Terimakasih, Anda telah memberikan <b>Umpan Balik dan Catatan Asesmen untuk Skema $sk[judul].</b><br>
				<a class='btn btn-warning form-control' href='?module=jadwal'>Kembali</a></div>";
				$sqlinputak01 = "UPDATE `asesmen_ak03` SET `K1`='$_POST[radioK1]', `catatanK1`='$_POST[catatanK1]', `K2`='$_POST[radioK2]', `catatanK2`='$_POST[catatanK2]', `K3`='$_POST[radioK3]', `catatanK3`='$_POST[catatanK3]', `K4`='$_POST[radioK4]', `catatanK4`='$_POST[catatanK4]', `K5`='$_POST[radioK5]', `catatanK5`='$_POST[catatanK5]', `K6`='$_POST[radioK6]', `catatanK6`='$_POST[catatanK6]', `K7`='$_POST[radioK7]', `catatanK7`='$_POST[catatanK7]', `K8`='$_POST[radioK8]', `catatanK8`='$_POST[catatanK8]', `K9`='$_POST[radioK9]', `catatanK9`='$_POST[catatanK9]', `K10`='$_POST[radioK10]', `catatanK10`='$_POST[catatanK10]', `catatanL`='$_POST[catatanL]', `tanggal`='$tglsekarang', `tanggal_asesittd`='$tglsekarang' WHERE `id_asesi`='$_SESSION[namauser]' AND `id_jadwal`='$_GET[idj]'";
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
				$sqlinputdigisign = "INSERT INTO `logdigisign`(`id_dokumen`, `id_asesi`, `id_skema`, `url_ditandatangani`, `nama_dokumen`, `penandatangan`, `file`, `ip`) VALUES ('$iddokumen','$_SESSION[namauser]','$_GET[id]'	,'$escaped_url','FR-APL-01. FORMULIR PERMOHONAN SERTIFIKASI KOMPETENSI','$rowAgen[nama]','$file','$alamatip')";
				$conn->query($sqlinputdigisign);
				echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Umpan Balik dan Catatan Asesmen berhasil disimpan</h4>
				Terimakasih, Anda telah melakukan <b>Umpan Balik dan Catatan Asesmen untuk Skema $sk[judul], dan tanda tangan telah ditambahkan</b><br>
				<a class='btn btn-warning form-control' href='?module=jadwal'>Kembali</a></div>";
				$sqlinputak01 = "UPDATE `asesmen_ak03` SET `K1`='$_POST[radioK1]', `catatanK1`='$_POST[catatanK1]', `K2`='$_POST[radioK2]', `catatanK2`='$_POST[catatanK2]', `K3`='$_POST[radioK3]', `catatanK3`='$_POST[catatanK3]', `K4`='$_POST[radioK4]', `catatanK4`='$_POST[catatanK4]', `K5`='$_POST[radioK5]', `catatanK5`='$_POST[catatanK5]', `K6`='$_POST[radioK6]', `catatanK6`='$_POST[catatanK6]', `K7`='$_POST[radioK7]', `catatanK7`='$_POST[catatanK7]', `K8`='$_POST[radioK8]', `catatanK8`='$_POST[catatanK8]', `K9`='$_POST[radioK9]', `catatanK9`='$_POST[catatanK9]', `K10`='$_POST[radioK10]', `catatanK10`='$_POST[catatanK10]', `catatanL`='$_POST[catatanL]', `tanggal`='$tglsekarang', `tanggal_asesittd`='$tglsekarang' WHERE `id_asesi`='$_SESSION[namauser]' AND `id_jadwal`='$_GET[idj]'";
				$conn->query($sqlinputak01);
			}
		} else {
			$folderPath = "foto_tandatangan/";
			if (empty($_POST['signed'])) {
				echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Umpan Balik dan Catatan Asesmen berhasil disimpan</h4>
				Terimakasih, Anda telah melakukan <b>Umpan Balik dan Catatan Asesmen untuk Skema $sk[judul].</b><br>
				<a class='btn btn-warning form-control' href='?module=jadwal'>Kembali</a></div>";
				$sqlinputak01 = "INSERT INTO `asesmen_ak03`(`id_asesi`, `id_skemakkni`, `id_jadwal`, `K1`, `catatanK1`, `K2`, `catatanK2`, `K3`, `catatanK3`, `K4`, `catatanK4`, `K5`, `catatanK5`, `K6`, `catatanK6`, `K7`, `catatanK7`, `K8`, `catatanK8`, `K9`, `catatanK9`, `K10`, `catatanK10`, `catatanL`, `tanggal`, `tanggal_asesittd`) VALUES ('$_SESSION[namauser]', '$jd[id_skemakkni]', '$_GET[idj]', '$_POST[radioK1]', '$_POST[catatanK1]', '$_POST[radioK2]', '$_POST[catatanK2]', '$_POST[radioK3]', '$_POST[catatanK3]', '$_POST[radioK4]', '$_POST[catatanK4]', '$_POST[radioK5]', '$_POST[catatanK5]', '$_POST[radioK6]', '$_POST[catatanK6]', '$_POST[radioK7]', '$_POST[catatanK7]', '$_POST[radioK8]', '$_POST[catatanK8]', '$_POST[radioK9]', '$_POST[catatanK9]', '$_POST[radioK10]', '$_POST[catatanK10]', '$_POST[catatanL]', '$tglsekarang', '$tglsekarang')";
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
				$sqlinputdigisign = "INSERT INTO `logdigisign`(`id_dokumen`, `id_asesi`, `id_skema`, `url_ditandatangani`, `nama_dokumen`, `penandatangan`, `file`, `ip`) VALUES ('$iddokumen','$_SESSION[namauser]','$_GET[id]'	,'$escaped_url','FR-APL-01. FORMULIR PERMOHONAN SERTIFIKASI KOMPETENSI','$rowAgen[nama]','$file','$alamatip')";
				$conn->query($sqlinputdigisign);
				echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Umpan Balik dan Catatan Asesmen berhasil disimpan</h4>
				Terimakasih, Anda telah melakukan <b>Umpan Balik dan Catatan Asesmen untuk Skema $sk[judul], dan tanda tangan telah ditambahkan</b><br>
				<a class='btn btn-warning form-control' href='?module=jadwal'>Kembali</a></div>";
				$sqlinputak01 = "INSERT INTO `asesmen_ak03`(`id_asesi`, `id_skemakkni`, `id_jadwal`, `K1`, `catatanK1`, `K2`, `catatanK2`, `K3`, `catatanK3`, `K4`, `catatanK4`, `K5`, `catatanK5`, `K6`, `catatanK6`, `K7`, `catatanK7`, `K8`, `catatanK8`, `K9`, `catatanK9`, `K10`, `catatanK10`, `catatanL`, `tanggal`, `tanggal_asesittd`) VALUES ('$_SESSION[namauser]', '$jd[id_skemakkni]', '$_GET[idj]', '$_POST[radioK1]', '$_POST[catatanK1]', '$_POST[radioK2]', '$_POST[catatanK2]', '$_POST[radioK3]', '$_POST[catatanK3]', '$_POST[radioK4]', '$_POST[catatanK4]', '$_POST[radioK5]', '$_POST[catatanK5]', '$_POST[radioK6]', '$_POST[catatanK6]', '$_POST[radioK7]', '$_POST[catatanK7]', '$_POST[radioK8]', '$_POST[catatanK8]', '$_POST[radioK9]', '$_POST[catatanK9]', '$_POST[radioK10]', '$_POST[catatanK10]', '$_POST[catatanL]', '$tglsekarang', '$tglsekarang')";
				$conn->query($sqlinputak01);
			}
		}
	}
	$sqlgetak01 = "SELECT * FROM `asesmen_ak03` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_jadwal`='$_GET[idj]'";
	$getak01 = $conn->query($sqlgetak01);
	$jjw = $getak01->fetch_assoc();
	$jumjjwx = $getak01->num_rows;
	echo "<!-- Main content -->
    <section class='content'>
      <div class='row'>
        <div class='col-xs-12'>
          <div class='box'>
            <div class='box-body'>
              <!-- form start -->
            	<div class='box-body'>
					<h2>FR.AK.03. UMPAN BALIK DAN CATATAN ASESMEN</h2>
					<form role='form' method='POST' enctype='multipart/form-data'>
					<table id='example9' class='table table-bordered table-striped'>
						<tr><td width='25%'>Nama Asesi</td><td>$rowAgen[nama]</td><td width='25%'>Hari/ Tanggal</td><td>";
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
	$tgl_cetak = tgl_indo($tanggal);
	$jadwal = $dayList[$day] . ", " . $tgl_cetak;
	echo "$jadwal</td></tr>
						<tr><td width='25%'>Nama Asesor</td><td>$namaasesor</td><td width='25%'>Waktu</td><td>$jd[jam_asesmen]</td></tr>
					</table>
					<table id='example9' class='table table-bordered table-striped'>
						<tr><td colspan='3'>Umpan balik dari Asesi (diisi oleh Asesi setelah pengambilan keputusan) :</td>
						</tr>
						<tr><th>Komponen</th><th width='10%'>Hasil</th><th>Catatan/ Komentar Asesi</th>
						</tr>
						<tr><td>Saya mendapatkan penjelasan yang cukup memadai mengenai proses asesmen/ uji kompetensi</td><td><div class='radio'>";
	echo "<input type='radio' class='flat-red' name='radioK1' id='optionsRadio1K1' value='1'";
	if ($jumjjwx > 0) {
		if ($jjw['K1'] == "1") {
			echo "checked";
		} else {
			echo "";
		}
	}
	echo "> Ya<br />";
	echo "<input type='radio' class='flat-red' name='radioK1' id='optionsRadio2K1' value='0'";
	if ($jumjjwx > 0) {
		if ($jjw['K1'] == "0") {
			echo "checked";
		} else {
			echo "";
		}
	}
	echo "> Tidak";
	echo "</div></td><td><textarea name='catatanK1' class='form-control'>";
	if ($jumjjwx > 0) {
		echo "$jjw[catatanK1]";
	}
	echo "</textarea></td>
						</tr>
						<tr><td>Saya diberikan kesempatan untuk mempelajari standar kompetensi yang akan diujikan dan menilai diri sendiri terhadap pencapaiannya</td><td><div class='radio'>";
	echo "<input type='radio' class='flat-red' name='radioK2' id='optionsRadio1K2' value='1'";
	if ($jumjjwx > 0) {
		if ($jjw['K2'] == "1") {
			echo "checked";
		} else {
			echo "";
		}
	}
	echo "> Ya<br />";
	echo "<input type='radio' class='flat-red' name='radioK2' id='optionsRadio2K2' value='0'";
	if ($jumjjwx > 0) {
		if ($jjw['K2'] == "0") {
			echo "checked";
		} else {
			echo "";
		}
	}
	echo "> Tidak";
	echo "</div></td><td><textarea name='catatanK2' class='form-control'>";
	if ($jumjjwx > 0) {
		echo "$jjw[catatanK2]";
	}
	echo "</textarea></td>
						</tr>
						<tr><td>Asesor memberikan kesempatan untuk mendiskusikan/menegosiasikan metoda, instrumen dan sumber asesmen serta jadwal asesmen</td><td><div class='radio'>";
	echo "<input type='radio' class='flat-red' name='radioK3' id='optionsRadio1K3' value='1'";
	if ($jumjjwx > 0) {
		if ($jjw['K3'] == "1") {
			echo "checked";
		} else {
			echo "";
		}
	}
	echo "> Ya<br />";
	echo "<input type='radio' class='flat-red' name='radioK3' id='optionsRadio2K3' value='0'";
	if ($jumjjwx > 0) {
		if ($jjw['K3'] == "0") {
			echo "checked";
		} else {
			echo "";
		}
	}
	echo "> Tidak";
	echo "</div></td><td><textarea name='catatanK3' class='form-control'>";
	if ($jumjjwx > 0) {
		echo "$jjw[catatanK3]";
	}
	echo "</textarea></td>
						</tr>
						<tr><td>Asesor berusaha menggali seluruh bukti pendukung yang sesuai dengan latar belakang pelatihan dan pengalaman yang saya miliki</td><td><div class='radio'>";
	echo "<input type='radio' class='flat-red' name='radioK4' id='optionsRadio1K4' value='1'";
	if ($jumjjwx > 0) {
		if ($jjw['K4'] == "1") {
			echo "checked";
		} else {
			echo "";
		}
	}
	echo "> Ya<br />";
	echo "<input type='radio' class='flat-red' name='radioK4' id='optionsRadio2K4' value='0'";
	if ($jumjjwx > 0) {
		if ($jjw['K4'] == "0") {
			echo "checked";
		} else {
			echo "";
		}
	}
	echo "> Tidak";
	echo "</div></td><td><textarea name='catatanK4' class='form-control'>";
	if ($jumjjwx > 0) {
		echo "$jjw[catatanK4]";
	}
	echo "</textarea></td>
						</tr>
						<tr><td>Saya sepenuhnya diberikan kesempatan untuk mendemonstrasikan kompetensi yang saya miliki selama asesmen</td><td><div class='radio'>";
	echo "<input type='radio' class='flat-red' name='radioK5' id='optionsRadio1K5' value='1'";
	if ($jumjjwx > 0) {
		if ($jjw['K5'] == "1") {
			echo "checked";
		} else {
			echo "";
		}
	}
	echo "> Ya<br />";
	echo "<input type='radio' class='flat-red' name='radioK5' id='optionsRadio2K5' value='0'";
	if ($jumjjwx > 0) {
		if ($jjw['K5'] == "0") {
			echo "checked";
		} else {
			echo "";
		}
	}
	echo "> Tidak";
	echo "</div></td><td><textarea name='catatanK5' class='form-control'>";
	if ($jumjjwx > 0) {
		echo "$jjw[catatanK5]";
	}
	echo "</textarea></td>
						</tr>
						<tr><td>Saya mendapatkan penjelasan yang memadai mengenai keputusan asesmen</td><td><div class='radio'>";
	echo "<input type='radio' class='flat-red' name='radioK6' id='optionsRadio1K6' value='1'";
	if ($jumjjwx > 0) {
		if ($jjw['K6'] == "1") {
			echo "checked";
		} else {
			echo "";
		}
	}
	echo "> Ya<br />";
	echo "<input type='radio' class='flat-red' name='radioK6' id='optionsRadio2K6' value='0'";
	if ($jumjjwx > 0) {
		if ($jjw['K6'] == "0") {
			echo "checked";
		} else {
			echo "";
		}
	}
	echo "> Tidak";
	echo "</div></td><td><textarea name='catatanK6' class='form-control'>";
	if ($jumjjwx > 0) {
		echo "$jjw[catatanK6]";
	}
	echo "</textarea></td>
						</tr>
						<tr><td>Asesor memberikan umpan balik yang mendukung setelah asesmen serta tindak lanjutnya</td><td><div class='radio'>";
	echo "<input type='radio' class='flat-red' name='radioK7' id='optionsRadio1K7' value='1'";
	if ($jumjjwx > 0) {
		if ($jjw['K7'] == "1") {
			echo "checked";
		} else {
			echo "";
		}
	}
	echo "> Ya<br />";
	echo "<input type='radio' class='flat-red' name='radioK7' id='optionsRadio2K7' value='0'";
	if ($jumjjwx > 0) {
		if ($jjw['K7'] == "0") {
			echo "checked";
		} else {
			echo "";
		}
	}
	echo "> Tidak";
	echo "</div></td><td><textarea name='catatanK7' class='form-control'>";
	if ($jumjjwx > 0) {
		echo "$jjw[catatanK7]";
	}
	echo "</textarea></td>
						</tr>
						<tr><td>Asesor bersama saya mempelajari semua dokumen asesmen serta menandatanganinya</td><td><div class='radio'>";
	echo "<input type='radio' class='flat-red' name='radioK8' id='optionsRadio1K8' value='1'";
	if ($jumjjwx > 0) {
		if ($jjw['K8'] == "1") {
			echo "checked";
		} else {
			echo "";
		}
	}
	echo "> Ya<br />";
	echo "<input type='radio' class='flat-red' name='radioK8' id='optionsRadio2K8' value='0'";
	if ($jumjjwx > 0) {
		if ($jjw['K8'] == "0") {
			echo "checked";
		} else {
			echo "";
		}
	}
	echo "> Tidak";
	echo "</div></td><td><textarea name='catatanK8' class='form-control'>";
	if ($jumjjwx > 0) {
		echo "$jjw[catatanK8]";
	}
	echo "</textarea></td>
						</tr>
						<tr><td>Saya mendapatkan jaminan kerahasiaan hasil asesmen serta penjelasan penanganan dokumen asesmen</td><td><div class='radio'>";
	echo "<input type='radio' class='flat-red' name='radioK9' id='optionsRadio1K9' value='1'";
	if ($jumjjwx > 0) {
		if ($jjw['K9'] == "1") {
			echo "checked";
		} else {
			echo "";
		}
	}
	echo "> Ya<br />";
	echo "<input type='radio' class='flat-red' name='radioK9' id='optionsRadio2K9' value='0'";
	if ($jumjjwx > 0) {
		if ($jjw['K9'] == "0") {
			echo "checked";
		} else {
			echo "";
		}
	}
	echo "> Tidak";
	echo "</div></td><td><textarea name='catatanK9' class='form-control'>";
	if ($jumjjwx > 0) {
		echo "$jjw[catatanK9]";
	}
	echo "</textarea></td>
						</tr>
						<tr><td>Asesor menggunakan keterampilan komunikasi yang efektif selama asesmen</td><td><div class='radio'>";
	echo "<input type='radio' class='flat-red' name='radioK10' id='optionsRadio1K10' value='1'";
	if ($jumjjwx > 0) {
		if ($jjw['K10'] == "1") {
			echo "checked";
		} else {
			echo "";
		}
	}
	echo "> Ya<br />";
	echo "<input type='radio' class='flat-red' name='radioK10' id='optionsRadio2K10' value='0'";
	if ($jumjjwx > 0) {
		if ($jjw['K10'] == "0") {
			echo "checked";
		} else {
			echo "";
		}
	}
	echo "> Tidak";
	echo "</div></td><td><textarea name='catatanK10' class='form-control'>";
	if ($jumjjwx > 0) {
		echo "$jjw[catatanK10]";
	}
	echo "</textarea></td>
						</tr>						
					</table>
					<p><b>Catatan/ Komentar lainnya (apabila ada) :</b><br />
					<textarea name='catatanL' class='form-control'>";
	if ($jumjjwx > 0) {
		echo "$jjw[catatanL]";
	}
	echo "</textarea>
					<p><b>Asesi :</b>
					</p>";
	// cek tandatangan digital
	/* $url = "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
				$iddokumen=md5($url);
				$sqlcektandatangan="SELECT * FROM `logdigisign` WHERE `id_dokumen`='$iddokumen' ORDER BY `id` DESC";
				$cektandatangan=$conn->query($sqlcektandatangan);
				$jumttd=$cektandatangan->num_rows;
				$ttdx=$cektandatangan->fetch_assoc();
				if ($jumttd>0){
					echo "<div class='col-md-12'>
								<label class='' for=''>Persetujuan/ Tanda Tangan yang telah Anda berikan:</label>
								<br/>
								<img src='$ttdx[file]' width='400px'/>
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
				</script></div>"; */
	echo "<div class='box-footer bg-black'>
					<div class='col-md-4 col-sm-12 col-xs-12'>
							<a class='btn btn-danger form-control' id=reset-validate-form href='?module=jadwal'>Kembali</a>
					</div>
					<div class='col-md-4 col-sm-12 col-xs-12'>
							<a href='asesor/form-fr-ak-03.php?ida=$_SESSION[namauser]&idj=$_GET[idj]' class='btn btn-primary form-control'>Unduh Formulir</a>
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
// Bagian Input Hasil Asesmen FORM-FR.IA.03
elseif ($_GET['module'] == 'form-ia-03') {
	$sqllogin = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_SESSION[namauser]'";
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
		  Input FR.IA.03
			<small>Skema $sk[judul]</small>
		  </h1>
		  <ol class='breadcrumb'>
			<li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
			<li class='active'>FR.IA.03</li>
		  </ol>
		</section>";
	if (isset($_REQUEST['simpan'])) {
		$sqlcekgetak01 = "SELECT * FROM `asesmen_ia03` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_jadwal`='$_GET[idj]'";
		$cekgetak01 = $conn->query($sqlcekgetak01);
		$cekak01 = $cekgetak01->num_rows;
		$tglsekarang = date("Y-m-d");
		if ($cekak01 > 0) {
			$folderPath = "foto_tandatangan/";
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
						$sqlcekjawaban2 = "SELECT * FROM `asesmen_ia03` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='$gpp2[id]'";
						$cekjawaban2 = $conn->query($sqlcekjawaban2);
						$jjw2 = $cekjawaban2->fetch_assoc();
						$sqlinputak01 = "UPDATE `asesmen_ia03` SET `tanggapan`='" . $_POST[$posttanggapan] . "' WHERE `id_asesi`='$_SESSION[namauser]' AND `id_skemakkni`='$jd[id_skemakkni]' AND `id_jadwal`='$_GET[idj]'";
						$conn->query($sqlinputak01);
						//}
					}
				}
				$sqlinputak01b = "UPDATE `asesi_asesmen` SET `umpan_balik_ia03`='$_POST[umpan_balik_ia03]' WHERE `id_asesi`='$_SESSION[namauser]' AND `id_jadwal`='$_GET[idj]'";
				$conn->query($sqlinputak01b);
				echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Tanggapan Pertanyaan Pendukung Observasi oleh Asesi berhasil disimpan</h4>
				Terimakasih, Anda telah melakukan input <b>Tanggapan Pertanyaan Pendukung Observasi untuk Skema $sk[judul].</b><br>
				<a class='btn btn-warning form-control' href='?module=jadwal'>Kembali</a></div>";
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
				$sqlinputdigisign = "INSERT INTO `logdigisign`(`id_dokumen`, `id_asesi`, `id_skema`, `url_ditandatangani`, `nama_dokumen`, `penandatangan`, `file`, `ip`) VALUES ('$iddokumen','$_SESSION[namauser]','$_GET[id]'	,'$escaped_url','FR-APL-01. FORMULIR PERMOHONAN SERTIFIKASI KOMPETENSI','$rowAgen[nama]','$file','$alamatip')";
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
						$sqlcekjawaban2 = "SELECT * FROM `asesmen_ia03` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='$gpp2[id]'";
						$cekjawaban2 = $conn->query($sqlcekjawaban2);
						$jjw2 = $cekjawaban2->fetch_assoc();
						$sqlinputak01 = "UPDATE `asesmen_ia03` SET `tanggapan`='" . $_POST[$posttanggapan] . "' WHERE `id_asesi`='$_SESSION[namauser]' AND `id_skemakkni`='$jd[id_skemakkni]' AND `id_jadwal`='$_GET[idj]'";
						$conn->query($sqlinputak01);
						//}
					}
				}
				$sqlinputak01b = "UPDATE `asesi_asesmen` SET `umpan_balik_ia03`='$_POST[umpan_balik_ia03]' WHERE `id_asesi`='$_SESSION[namauser]' AND `id_jadwal`='$_GET[idj]'";
				$conn->query($sqlinputak01b);
				echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Tanggapan Pertanyaan Pendukung Observasi oleh Asesi berhasil disimpan</h4>
				Terimakasih, Anda telah melakukan input <b>Tanggapan Pertanyaan Pendukung Observasi untuk Skema $sk[judul], dan tanda tangan telah ditambahkan</b><br>
				<a class='btn btn-warning form-control' href='?module=jadwal'>Kembali</a></div>";
			}
		} else {
			$folderPath = "foto_tandatangan/";
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
						$sqlcekjawaban2 = "SELECT * FROM `asesmen_ia03` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='$gpp2[id]'";
						$cekjawaban2 = $conn->query($sqlcekjawaban2);
						$jjw2 = $cekjawaban2->fetch_assoc();
						if (!empty($_POST[$posttanggapan])) {
							$posttanggapannya = $_POST[$posttanggapan];
						} else {
							$posttanggapannya = "";
						}
						$sqlinputak01 = "INSERT INTO `asesmen_ia03`(`id_asesi`, `id_skemakkni`, `id_jadwal`, `id_unitkompetensi`, `id_pertanyaan`, `tanggapan`) VALUES ('$_SESSION[namauser]','$sk[id]','$_GET[idj]','$unkb2[id]','$gpp2[id]','" . $posttanggapannya . "')";
						$conn->query($sqlinputak01);
						//}
					}
				}
				$sqlinputak01b = "UPDATE `asesi_asesmen` SET `umpan_balik_ia03`='$_POST[umpan_balik_ia03]' WHERE `id_asesi`='$_SESSION[namauser]' AND `id_jadwal`='$_GET[idj]'";
				$conn->query($sqlinputak01b);
				echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Tanggapan Pertanyaan Pendukung Observasi oleh Asesi berhasil disimpan</h4>
				Terimakasih, Anda telah melakukan input <b>Tanggapan Pertanyaan Pendukung Observasi untuk Skema $sk[judul].</b><br>
				<a class='btn btn-warning form-control' href='?module=jadwal'>Kembali</a></div>";
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
				$sqlinputdigisign = "INSERT INTO `logdigisign`(`id_dokumen`, `id_asesi`, `id_skema`, `url_ditandatangani`, `nama_dokumen`, `penandatangan`, `file`, `ip`) VALUES ('$iddokumen','$_SESSION[namauser]','$_GET[id]'	,'$escaped_url','FR-APL-01. FORMULIR PERMOHONAN SERTIFIKASI KOMPETENSI','$rowAgen[nama]','$file','$alamatip')";
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
						$sqlcekjawaban2 = "SELECT * FROM `asesmen_ia03` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='$gpp2[id]'";
						$cekjawaban2 = $conn->query($sqlcekjawaban2);
						$jjw2 = $cekjawaban2->fetch_assoc();
						if (!empty($_POST[$posttanggapan])) {
							$posttanggapannya = $_POST[$posttanggapan];
						} else {
							$posttanggapannya = "";
						}
						$sqlinputak01 = "INSERT INTO `asesmen_ia03`(`id_asesi`, `id_skemakkni`, `id_jadwal`, `id_unitkompetensi`, `id_pertanyaan`, `tanggapan`) VALUES ('$_SESSION[namauser]','$sk[id]','$_GET[idj]','$unkb2[id]','$gpp2[id]','" . $posttanggapannya . "')";
						$conn->query($sqlinputak01);
						//}
					}
				}
				$sqlinputak01b = "UPDATE `asesi_asesmen` SET `umpan_balik_ia03`='$_POST[umpan_balik_ia03]' WHERE `id_asesi`='$_SESSION[namauser]' AND `id_jadwal`='$_GET[idj]'";
				$conn->query($sqlinputak01b);
				echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Tanggapan Pertanyaan Pendukung Observasi oleh Asesi berhasil disimpan</h4>
				Terimakasih, Anda telah melakukan untuk <b>Tanggapan Pertanyaan Pendukung Observasi untuk Skema $sk[judul], dan tanda tangan telah ditambahkan</b><br>
				<a class='btn btn-warning form-control' href='?module=jadwal'>Kembali</a></div>";
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
						<h2>FR.IA.03. PERTANYAAN UNTUK MENDUKUNG OBSERVASI</h2>
						<form role='form' method='POST' enctype='multipart/form-data'>
						<table id='example9' class='table table-bordered table-striped'>
							<tr><td rowspan='2' width='25%'>Skema Sertifikasi (KKNI/Okupasi/Klaster)</td><td>Judul :</td><td>$sk[judul]</td></tr>
							<tr><td width='25%'>Nomor :</td><td>$sk[kode_skema]</td></tr>
							<tr><td width='25%'>TUK</td><td colspan='2'>$tukjen[jenis_tuk]</td></tr>
							<tr><td width='25%'>Nama Asesor</td><td colspan='2'>$namaasesor</td></tr>
							<tr><td width='25%'>Nama Asesi</td><td colspan='2'>$_SESSION[namalengkap]</td></tr>
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
				if (!empty($gpp['pertanyaan'])) {
					$sqlcekjawaban = "SELECT * FROM `asesmen_ia03` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='$gpp[id]'";
					$cekjawaban = $conn->query($sqlcekjawaban);
					$jjw = $cekjawaban->fetch_assoc();
					echo "<tr><td>$nopp.</td><td>" . $gpp['pertanyaan'] . "</td><td rowspan='2'>
									<input type='radio' name='rekomendasi$gpp[id]' id='rekomendasi$gpp[id]1' value='K' ";
					if ($jjw['rekomendasi'] == 'K') {
						echo " checked";
					} else {
						echo "";
					}
					echo " disabled>&nbsp;Kompeten<br>
									<input type='radio' name='rekomendasi$gpp[id]' id='rekomendasi$gpp[id]2' value='BK' ";
					if ($jjw['rekomendasi'] == 'BK') {
						echo " checked";
					} else {
						echo "";
					}
					echo " disabled>&nbsp;Belum Kompeten</td></tr>
									<tr><td colspan='2'>Tanggapan:<br>
									<textarea class='form-control' name='tanggapan$gpp[id]'>";
					if (!empty($jjw['tanggapan'])) {
						echo $jjw['tanggapan'];
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
		$sqlgetkeputusan = "SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_jadwal`='$_GET[idj]'";
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
										<img src='$ttdx[file]' width='400px'/>
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
									<a class='btn btn-danger form-control' id=reset-validate-form href='?module=jadwal'>Kembali</a>
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
// Bagian Soal Tertulis Pilihan Ganda FORM-IA-05
elseif ($_GET['module'] == 'form-ia-05') {
	$sqllogin = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_SESSION[namauser]'";
	$login = $conn->query($sqllogin);
	$ketemu = $login->num_rows;
	$rowAgen = $login->fetch_assoc();
	$sqlgetjadwal = "SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
	$getjadwal = $conn->query($sqlgetjadwal);
	$jd = $getjadwal->fetch_assoc();
	$sqlgetskema = "SELECT * FROM `skema_kkni` WHERE `id`='$jd[id_skemakkni]'";
	$getskema = $conn->query($sqlgetskema);
	$sk = $getskema->fetch_assoc();
	if (isset($_REQUEST['simpandata'])) {
		// cek jumlah percobaan (attempt) menjawab soal
		/* $sqlcekcobajawaban="SELECT DISTINCT `percobaan` FROM `asesmen_ia05` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_skemakkni`='$sk[id]' AND `id_jadwal`='$_GET[idj]'";
		$cekcobajawaban=$conn->query($sqlcekcobajawaban);
		$jcobajwb=$cekcobajawaban->num_rows;
		if ($jcobajwb>0){
			$percobaanjawab=$jcobajwb+1;
		}else{
			$percobaanjawab=1;
		} */
		// proses input data jawaban
		$sqlgetsoal = "SELECT * FROM `skema_pertanyaantulispg` WHERE `id_skemakkni`='$sk[id]' ORDER BY `no_urut` ASC";
		$getsoal = $conn->query($sqlgetsoal);
		$no = 1;
		while ($unk0 = $getsoal->fetch_assoc()) {
			$varpertanyaan = $unk0['id'];
			$varjawaban = "optionsRadios" . $unk0['id'];
			if (isset($_POST[$varjawaban])) {
				if ($_POST[$varjawaban] == $unk0['kunci_jawaban']) {
					$skorjawaban = 1;
				} else {
					$skorjawaban = 0;
				}
			} else {
				$skorjawaban = 0;
			}
			// cek jawaban
			//$sqlcekjawaban0="SELECT * FROM `asesmen_ia05` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_skemakkni`='$sk[id]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='$unk[id]' AND `percobaan`='$percobaanjawab'";
			$sqlcekjawaban0 = "SELECT * FROM `asesmen_ia05` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_skemakkni`='$sk[id]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='$unk0[id]'";
			$cekjawaban0 = $conn->query($sqlcekjawaban0);
			$cjwb0 = $cekjawaban0->fetch_assoc();
			$jcjwb0 = $cekjawaban0->num_rows;
			if ($jcjwb0 > 0) {
				if (isset($_POST[$varjawaban])) {
					$sqljawabania05 = "UPDATE `asesmen_ia05` SET `jawaban`='$_POST[$varjawaban]',`skor`='$skorjawaban' WHERE `id`='$cjwb0[id]'";
				} else {
					$sqljawabania05 = "UPDATE `asesmen_ia05` SET `skor`='$skorjawaban' WHERE `id`='$cjwb0[id]'";
				}
				$conn->query($sqljawabania05);
			} else {
				if (isset($_POST[$varjawaban])) {
					//$sqljawabania05="INSERT INTO `asesmen_ia05`(`id_asesi`, `id_skemakkni`, `id_jadwal`, `id_unitkompetensi`, `no_urut`, `id_pertanyaan`, `percobaan`, `jawaban`, `skor`) VALUES ('$_SESSION[namauser]','$sk[id]','$_GET[idj]','$unk[id_unitkompetensi]','$unk[no_urut]','$varpertanyaan','$percobaanjawab','$_POST[$varjawaban]','$skorjawaban')";
					$sqljawabania05 = "INSERT INTO `asesmen_ia05`(`id_asesi`, `id_skemakkni`, `id_jadwal`, `id_unitkompetensi`, `no_urut`, `id_pertanyaan`, `jawaban`, `skor`) VALUES ('$_SESSION[namauser]','$sk[id]','$_GET[idj]','$unk0[id_unitkompetensi]','$unk0[no_urut]','$varpertanyaan','$_POST[$varjawaban]','$skorjawaban')";
				} else {
					//$sqljawabania05="INSERT INTO `asesmen_ia05`(`id_asesi`, `id_skemakkni`, `id_jadwal`, `id_unitkompetensi`, `no_urut`, `id_pertanyaan`, `percobaan`, `jawaban`, `skor`) VALUES ('$_SESSION[namauser]','$sk[id]','$_GET[idj]','$unk[id_unitkompetensi]','$unk[no_urut]','$varpertanyaan','$percobaanjawab',NULL,'$skorjawaban')";
					$sqljawabania05 = "INSERT INTO `asesmen_ia05`(`id_asesi`, `id_skemakkni`, `id_jadwal`, `id_unitkompetensi`, `no_urut`, `id_pertanyaan`, `jawaban`, `skor`) VALUES ('$_SESSION[namauser]','$sk[id]','$_GET[idj]','$unk0[id_unitkompetensi]','$unk0[no_urut]','$varpertanyaan',NULL,'$skorjawaban')";
				}
				$conn->query($sqljawabania05);
			}
		}
		$folderPath = "foto_tandatangan/";
		if (empty($_POST['signed'])) {
			echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Jawaban berhasil disimpan</h4>
			Terimakasih, Anda telah melakukan <b>Tes Tulis Pilihan Ganda untuk Skema $sk[judul].</b></div>";
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
			$sqlinputdigisign = "INSERT INTO `logdigisign`(`id_dokumen`, `id_asesi`, `id_skema`, `url_ditandatangani`, `nama_dokumen`, `penandatangan`, `file`, `ip`) VALUES ('$iddokumen','$_SESSION[namauser]','$_GET[id]'	,'$escaped_url','FR-APL-01. FORMULIR PERMOHONAN SERTIFIKASI KOMPETENSI','$rowAgen[nama]','$file','$alamatip')";
			$conn->query($sqlinputdigisign);
			echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Jawaban berhasil disimpan</h4>
			Terimakasih, Anda telah melakukan <b>Tes Tulis Pilihan Ganda untuk Skema $sk[judul].</b><br><b>Tanda Tangan Sukses Diupload</b></div>";
		}
	}
	echo "<!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
	  Tes Tertulis Pilihan Ganda (FR.IA.05)
        <small>Skema $sk[judul]</small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li class='active'>Tes Tertulis Pilihan Ganda</li>
      </ol>
    </section>";
	// cek jumlah percobaan (attempt) menjawab soal
	/* $sqlcekcobajawaban2="SELECT DISTINCT `percobaan` FROM `asesmen_ia05` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_skemakkni`='$sk[id]' AND `id_jadwal`='$_GET[idj]'";
	$cekcobajawaban2=$conn->query($sqlcekcobajawaban2);
	$jcobajwb2=$cekcobajawaban2->num_rows;
	if ($jcobajwb2>0){
		$percobaanjawab2=$jcobajwb2+1;
	}else{
		$percobaanjawab2=1;
	} */
	// cek triger asesor
	$sqlgettrigersoal = "SELECT * FROM `asesi_aksessoal` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_skemakkni`='$sk[id]' AND `id_jadwal`='$_GET[idj]' AND `jenis_soal`='FR.IA.05' AND `status`='1'";
	$gettrigersoal = $conn->query($sqlgettrigersoal);
	$jumtriger = $gettrigersoal->num_rows;
	if ($jumtriger > 0) {
		echo "<!-- Main content -->
		<section class='content'>
		  <div class='row'>
			<div class='col-xs-12'>
			  <div class='box'>
				<div class='box-body'><!-- /.start box-body main-->
				  <!-- form start -->
				  <form role='form' method='POST' enctype='multipart/form-data'>
					<div class='box-body box-success'>
					<h2>Petunjuk Soal Tes Tertulis Pilihan Ganda</h2>
					<p>
						<ol>
							<li>Asesor memberikan penjelasan tentang proses tes lisan kepada peserta</li>
							<li>Asesor memberikan pertanyaan kepada peserta sesuai DPL yang telah disiapkan</li>
							<li>Peserta menjawab sesuai dengan pertanyaan dari asesor</li>
							<li>Asesor mencatat secara ringkas dan akurat jawaban peserta</li>
							<li>Asesor menilai jawaban peserta sesuai dengan kunci jawaban</li>
							<li>Asesor menentukan hasil tes lisan kompeten atau belum kompeten</li>
						</ol>
					</p>";
		// ambil 20 soal acak dari bank soal
		if ($pupr > 0) {
			$sqlgetsoal = "SELECT * FROM `skema_pertanyaantulispg` WHERE `id_skemakkni`='$sk[id]' ORDER BY `no_urut` ASC LIMIT 20";
		} else {
			$sqlgetsoal = "SELECT * FROM `skema_pertanyaantulispg` WHERE `id_skemakkni`='$sk[id]' ORDER BY `no_urut` ASC";
		}
		$getsoal = $conn->query($sqlgetsoal);
		$no = 1;
		// hitung skor
		//$percobaanjawab2min=$percobaanjawab2-1;
		$sqlgetskor = "SELECT SUM(`skor`) AS `skortotal` FROM `asesmen_ia05` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_skemakkni`='$sk[id]' AND `id_jadwal`='$_GET[idj]'";
		$getskor = $conn->query($sqlgetskor);
		$gskor = $getskor->fetch_assoc();
		$jumsoal = $getsoal->num_rows;
		$gradenya = ($gskor['skortotal'] / $jumsoal) * 100;
		if ($jcobajwb2 > 0 && $gradenya > 69.99) {
			/* echo "<div class='alert alert-success alert-dismissible'>
						<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
						<h4><i class='icon fa fa-check'></i> ANDA TELAH MENGERJAKAN SOAL INI $jcobajwb2 KALI DENGAN SKOR TERAKHIR = <b>$gradenya</b></h4>"; */
			echo "<div class='alert alert-success alert-dismissible'>
						<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
						<h4><i class='icon fa fa-check'></i> TERIMAKASIH ANDA TELAH MENGERJAKAN SOAL <b>PILIHAN GANDA</b></h4>";
			/* while ($jprcdata=$cekcobajawaban2->fetch_assoc()){
							$sqlgetskor2="SELECT SUM(`skor`) AS `skortotal` FROM `asesmen_ia05` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_skemakkni`='$sk[id]' AND `id_jadwal`='$_GET[idj]'";
							$getskor2=$conn->query($sqlgetskor2);
							$gskor2=$getskor2->fetch_assoc();
							$jcobajwb2=($gskor2['skortotal']/$jumsoal)*100;
							//echo "Tes ke $jprcdata[percobaan], skor yang Anda peroleh = $jcobajwb2<br>";
							echo "Tes ke $jprcdata[percobaan]<br>";
						} */
			echo "</div>";
		} else {
			//if ($percobaanjawab2>1){
			/* echo "<div class='alert alert-success alert-dismissible'>
							<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
							<h4><i class='icon fa fa-check'></i> ANDA TELAH MENGERJAKAN SOAL INI $jcobajwb2 KALI DENGAN SKOR = <b>$gradenya</b></h4>
							Bila Anda ingin mengubah jawaban dan melakukan <b>Tes Tulis Ulang (Remidi) Pilihan Ganda untuk Skema $sk[judul].</b><br><b>Silahkan kerjakan kembali soal ini dan klik tombol <b>Simpan Jawaban</b><br>Jawaban Anda akan disimpan sebagai jawaban berbeda dari sebelumnya.</div>";
							echo "<h2>Soal Remidi</h2>"; */
			/* echo "<div class='alert alert-success alert-dismissible'>
							<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
							<h4><i class='icon fa fa-check'></i> TERIMAKASIH ANDA TELAH MENGERJAKAN SOAL <b>PILIHAN GANDA</b></h4>
							Bila Anda ingin mengubah jawaban dan melakukan <b>Tes Tulis Ulang Pilihan Ganda untuk Skema $sk[judul].</b><br><b>Silahkan kerjakan kembali soal ini dan klik tombol <b>Simpan Jawaban</b><br>Jawaban Anda akan disimpan sebagai jawaban berbeda dari sebelumnya.</div>"; */
			//echo "<h2>Soal Remidi</h2>";
			//echo "<h2>Soal</h2>";
			//}else{
			echo "<h2>Soal</h2>";
			//}
			echo "<ol>";
			$sqlgetsoal2 = "SELECT * FROM `skema_pertanyaantulispg` WHERE `id_skemakkni`='$sk[id]' ORDER BY `no_urut` ASC";
			$getsoal2 = $conn->query($sqlgetsoal2);
			while ($unk = $getsoal->fetch_assoc()) {
				// create bank soal di asesi
				//$sqlbanksoalia05="INSERT INTO `asesmen_ia05`(`id_asesi`, `id_skemakkni`, `id_jadwal`, `id_unitkompetensi`, `no_urut`, `id_pertanyaan`) VALUES ('$_SESSION[namauser]','$sk[id]','$_GET[idj]','$unk[id_unitkompetensi]','$unk[no_urut]','$unk[id]')";
				//$conn->query($sqlbanksoalia05);
				// cek jawaban
				$sqlcekjawaban = "SELECT * FROM `asesmen_ia05` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_skemakkni`='$sk[id]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='$unk[id]' ORDER BY `id` DESC";
				$cekjawaban = $conn->query($sqlcekjawaban);
				$cjwb = $cekjawaban->fetch_assoc();
				$jcjwb = $cekjawaban->num_rows;
				echo "<div class='box-body'><li>";
				if (!empty($unk['fotosoal1'])) {
					echo "<img src='foto_soal/$unk[fotosoal1]' width='100%'/><br/>";
				}
				echo "$unk[pertanyaan]";
				if (!empty($unk['fotosoal2'])) {
					echo "<br/><img src='foto_soal/$unk[fotosoal2]' width='100%'/>";
				}
				echo "</li><ul>
							<div class='form-group'>";
				if ($jcjwb > 0) {
					echo "<div class='radio'>
									<input type='radio' name='optionsRadios$unk[id]' id='options1$unk[id]' value='a'";
					if ($cjwb['jawaban'] == 'a') {
						echo " checked";
					}
					echo " required='required'>
									 $unk[pilihan_a]</div>";
					echo "<div class='radio'>
									<input type='radio' name='optionsRadios$unk[id]' id='options2$unk[id]' value='b'";
					if ($cjwb['jawaban'] == 'b') {
						echo " checked";
					}
					echo ">
									 $unk[pilihan_b]</div>";
					echo "<div class='radio'>
									<input type='radio' name='optionsRadios$unk[id]' id='options3$unk[id]' value='c'";
					if ($cjwb['jawaban'] == 'c') {
						echo " checked";
					}
					echo ">
									 $unk[pilihan_c]</div>";
					if (!empty($unk['pilihan_d'])) {
						echo "<div class='radio'>
										<input type='radio' name='optionsRadios$unk[id]' id='options4$unk[id]' value='d'";
						if ($cjwb['jawaban'] == 'd') {
							echo " checked";
						}
						echo ">
										 $unk[pilihan_d]</div>";
					}
					if (!empty($unk['pilihan_e'])) {
						echo "<div class='radio'>
										<input type='radio' name='optionsRadios$unk[id]' id='options5$unk[id]' value='e'";
						if ($cjwb['jawaban'] == 'e') {
							echo " checked";
						}
						echo ">
										 $unk[pilihan_e]</div>";
					}
				} else {
					echo "<div class='radio'>
									<input type='radio' name='optionsRadios$unk[id]' id='options1$unk[id]' value='a' required='required'>
									 $unk[pilihan_a]</div>
								<div class='radio'>
									<input type='radio' name='optionsRadios$unk[id]' id='options2$unk[id]' value='b'>
									 $unk[pilihan_b]</div>
								<div class='radio'>
									<input type='radio' name='optionsRadios$unk[id]' id='options3$unk[id]' value='c'>
									 $unk[pilihan_c]</div>";
					if (!empty($unk['pilihan_d'])) {
						echo "<div class='radio'>
										<input type='radio' name='optionsRadios$unk[id]' id='options4$unk[id]' value='d'>
										 $unk[pilihan_d]</div>";
					}
					if (!empty($unk['pilihan_e'])) {
						echo "<div class='radio'>
										<input type='radio' name='optionsRadios$unk[id]' id='options5$unk[id]' value='e'>
										 $unk[pilihan_e]</div>";
					}
				}
				echo "</div></ul>
							</div><!-- /.end box-body soal-->
							<br>";
			}
			echo "</ol>";
			echo "<div class='box-footer'>";
			// cek tandatangan digital
			$url =  "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
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
			echo "<div align='left' class='col-md-6 col-sm-6 col-xs-6'>
								<a class='btn btn-danger' id=reset-validate-form href='?module=jadwal'>Kembali</a>
							</div>
							<div align='right' class='col-md-6 col-sm-6 col-xs-6'>
								<!--<a href='admin/form-apl-02.php?ida=$_SESSION[namauser]&idj=$_GET[idj]' class='btn btn-primary'>Unduh Form Jawaban</a>-->
								<button type='submit' class='btn btn-success' name='simpandata'>Simpan Jawaban</button>
							</div>
						</div><!-- /.box-footer -->
						</form>";
		}
		echo "</div>
				<!-- /.end box-body main-->
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
				<div class='box-body'><!-- /.start box-body main-->
					<h2 class='text-red'>Maaf Soal ini belum bisa Anda akses, silahkan hubungi Asesor Anda untuk membuka soal ini</h2>
				</div>
				<!-- /.end box-body main-->
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
// Bagian Input Hasil Asesmen FORM-FR.IA.06
elseif ($_GET['module'] == 'form-ia-06') {
	$sqllogin = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_SESSION[namauser]'";
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
		$sqlcekgetak01 = "SELECT * FROM `asesmen_ia06` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_jadwal`='$_GET[idj]'";
		$cekgetak01 = $conn->query($sqlcekgetak01);
		$cekak01 = $cekgetak01->num_rows;
		$tglsekarang = date("Y-m-d");
		if ($cekak01 > 0) {
			$folderPath = "foto_tandatangan/";
			if (empty($_POST['signed'])) {
				// input jawaban pendukung observasi
				$sqlgetunitkompetensib2 = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
				$getunitkompetensib2 = $conn->query($sqlgetunitkompetensib2);
				while ($unkb2 = $getunitkompetensib2->fetch_assoc()) {
					$sqlgetpertanyaan2 = "SELECT * FROM `skema_pertanyaanesai` WHERE `id_unitkompetensi`='$unkb2[id]' ORDER BY `id` ASC";
					$getpertanyaan2 = $conn->query($sqlgetpertanyaan2);
					while ($gpp2 = $getpertanyaan2->fetch_assoc()) {
						//if (!empty($gpp2['pertanyaan'])){
						$postjawaban = 'jawaban' . $gpp2['id'];
						$sqlcekjawaban2 = "SELECT * FROM `asesmen_ia06` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='$gpp2[id]'";
						$cekjawaban2 = $conn->query($sqlcekjawaban2);
						$jjw2 = $cekjawaban2->fetch_assoc();
						$sqlinputak01 = "UPDATE `asesmen_ia06` SET `jawaban`='" . $_POST[$postjawaban] . "' WHERE `id_asesi`='$_SESSION[namauser]' AND `id_skemakkni`='$jd[id_skemakkni]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='$gpp2[id]'";
						$conn->query($sqlinputak01);
						//}
					}
				}
				//$sqlinputak01b="UPDATE `asesi_asesmen` SET `umpan_balik_ia06`='$_POST[umpan_balik_ia06]' WHERE `id_asesi`='$_SESSION[namauser]' AND `id_jadwal`='$_GET[idj]'";
				//$conn->query($sqlinputak01b);
				echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Jawaban Pertanyaan Tertulis Esai oleh Asesi berhasil disimpan</h4>
				Terimakasih, Anda telah melakukan input <b>Jawaban Pertanyaan Tertulis Esai untuk Skema $sk[judul].</b><br>
				<a class='btn btn-warning form-control' href='?module=jadwal'>Kembali</a></div>";
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
				$sqlinputdigisign = "INSERT INTO `logdigisign`(`id_dokumen`, `id_asesi`, `id_skema`, `url_ditandatangani`, `nama_dokumen`, `penandatangan`, `file`, `ip`) VALUES ('$iddokumen','$_SESSION[namauser]','$_GET[id]'	,'$escaped_url','FR-APL-01. FORMULIR PERMOHONAN SERTIFIKASI KOMPETENSI','$rowAgen[nama]','$file','$alamatip')";
				$conn->query($sqlinputdigisign);
				// input jawaban pendukung observasi
				$sqlgetunitkompetensib2 = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
				$getunitkompetensib2 = $conn->query($sqlgetunitkompetensib2);
				while ($unkb2 = $getunitkompetensib2->fetch_assoc()) {
					$sqlgetpertanyaan2 = "SELECT * FROM `skema_pertanyaanesai` WHERE `id_unitkompetensi`='$unkb2[id]' ORDER BY `id` ASC";
					$getpertanyaan2 = $conn->query($sqlgetpertanyaan2);
					while ($gpp2 = $getpertanyaan2->fetch_assoc()) {
						//if (!empty($gpp2['pertanyaan'])){
						$postjawaban = 'jawaban' . $gpp2['id'];
						$sqlcekjawaban2 = "SELECT * FROM `asesmen_ia06` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='$gpp2[id]'";
						$cekjawaban2 = $conn->query($sqlcekjawaban2);
						$jjw2 = $cekjawaban2->fetch_assoc();
						$sqlinputak01 = "UPDATE `asesmen_ia06` SET `jawaban`='" . $_POST[$postjawaban] . "' WHERE `id_asesi`='$_SESSION[namauser]' AND `id_skemakkni`='$jd[id_skemakkni]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='$gpp2[id]'";
						$conn->query($sqlinputak01);
						//}
					}
				}
				//$sqlinputak01b="UPDATE `asesi_asesmen` SET `umpan_balik_ia06`='$_POST[umpan_balik_ia06]' WHERE `id_asesi`='$_SESSION[namauser]' AND `id_jadwal`='$_GET[idj]'";
				//$conn->query($sqlinputak01b);
				echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Jawaban Pertanyaan Tertulis Esai oleh Asesi berhasil disimpan</h4>
				Terimakasih, Anda telah melakukan input <b>Jawaban Pertanyaan Tertulis Esai untuk Skema $sk[judul], dan tanda tangan telah ditambahkan</b><br>
				<a class='btn btn-warning form-control' href='?module=jadwal'>Kembali</a></div>";
			}
		} else {
			$folderPath = "foto_tandatangan/";
			if (empty($_POST['signed'])) {
				// input jawaban pendukung observasi
				$sqlgetunitkompetensib2 = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
				$getunitkompetensib2 = $conn->query($sqlgetunitkompetensib2);
				while ($unkb2 = $getunitkompetensib2->fetch_assoc()) {
					$sqlgetpertanyaan2 = "SELECT * FROM `skema_pertanyaanesai` WHERE `id_unitkompetensi`='$unkb2[id]' ORDER BY `id` ASC";
					$getpertanyaan2 = $conn->query($sqlgetpertanyaan2);
					while ($gpp2 = $getpertanyaan2->fetch_assoc()) {
						//if (!empty($gpp2['pertanyaan'])){
						$postjawaban = 'jawaban' . $gpp2['id'];
						$sqlcekjawaban2 = "SELECT * FROM `asesmen_ia06` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='$gpp2[id]'";
						$cekjawaban2 = $conn->query($sqlcekjawaban2);
						$jjw2 = $cekjawaban2->fetch_assoc();
						if (isset($_POST[$postjawaban])) {
							$postjawabannya = $_POST[$postjawaban];
						} else {
							$postjawabannya = "";
						}
						$sqlinputak01 = "INSERT INTO `asesmen_ia06`(`id_asesi`, `id_skemakkni`, `id_jadwal`, `id_unitkompetensi`, `id_pertanyaan`, `jawaban`) VALUES ('$_SESSION[namauser]','$sk[id]','$_GET[idj]','$unkb2[id]','$gpp2[id]','" . $postjawabannya . "')";
						$conn->query($sqlinputak01);
						//}
					}
				}
				//$sqlinputak01b="UPDATE `asesi_asesmen` SET `umpan_balik_ia06`='$_POST[umpan_balik_ia06]' WHERE `id_asesi`='$_SESSION[namauser]' AND `id_jadwal`='$_GET[idj]'";
				//$conn->query($sqlinputak01b);
				echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Jawaban Pertanyaan Tertulis Esai oleh Asesi berhasil disimpan</h4>
				Terimakasih, Anda telah melakukan input <b>Jawaban Pertanyaan Tertulis Esai untuk Skema $sk[judul].</b><br>
				<a class='btn btn-warning form-control' href='?module=jadwal'>Kembali</a></div>";
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
				$sqlinputdigisign = "INSERT INTO `logdigisign`(`id_dokumen`, `id_asesi`, `id_skema`, `url_ditandatangani`, `nama_dokumen`, `penandatangan`, `file`, `ip`) VALUES ('$iddokumen','$_SESSION[namauser]','$_GET[id]'	,'$escaped_url','FR-APL-01. FORMULIR PERMOHONAN SERTIFIKASI KOMPETENSI','$rowAgen[nama]','$file','$alamatip')";
				$conn->query($sqlinputdigisign);
				// input jawaban pendukung observasi
				$sqlgetunitkompetensib2 = "SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
				$getunitkompetensib2 = $conn->query($sqlgetunitkompetensib2);
				while ($unkb2 = $getunitkompetensib2->fetch_assoc()) {
					$sqlgetpertanyaan2 = "SELECT * FROM `skema_pertanyaanesai` WHERE `id_unitkompetensi`='$unkb2[id]' ORDER BY `id` ASC";
					$getpertanyaan2 = $conn->query($sqlgetpertanyaan2);
					while ($gpp2 = $getpertanyaan2->fetch_assoc()) {
						//if (!empty($gpp2['pertanyaan'])){
						$postjawaban = 'jawaban' . $gpp2['id'];
						$sqlcekjawaban2 = "SELECT * FROM `asesmen_ia06` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='$gpp2[id]'";
						$cekjawaban2 = $conn->query($sqlcekjawaban2);
						$jjw2 = $cekjawaban2->fetch_assoc();
						if (isset($_POST[$postjawaban])) {
							$postjawabannya = $_POST[$postjawaban];
						} else {
							$postjawabannya = "";
						}
						$sqlinputak01 = "INSERT INTO `asesmen_ia06`(`id_asesi`, `id_skemakkni`, `id_jadwal`, `id_unitkompetensi`, `id_pertanyaan`, `jawaban`) VALUES ('$_SESSION[namauser]','$sk[id]','$_GET[idj]','$unkb2[id]','$gpp2[id]','" . $postjawabannya . "')";
						$conn->query($sqlinputak01);
						//}
					}
				}
				//$sqlinputak01b="UPDATE `asesi_asesmen` SET `umpan_balik_ia06`='$_POST[umpan_balik_ia06]' WHERE `id_asesi`='$_SESSION[namauser]' AND `id_jadwal`='$_GET[idj]'";
				//$conn->query($sqlinputak01b);
				echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Jawaban Pertanyaan Tertulis Esai oleh Asesi berhasil disimpan</h4>
				Terimakasih, Anda telah melakukan untuk <b>Jawaban Pertanyaan Tertulis Esai untuk Skema $sk[judul], dan tanda tangan telah ditambahkan</b><br>
				<a class='btn btn-warning form-control' href='?module=jadwal'>Kembali</a></div>";
			}
		}
	}
	$tanggalasesmen = tgl_indo($jd['tgl_asesmen']);
	// cek triger asesor
	$sqlgettrigersoal = "SELECT * FROM `asesi_aksessoal` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_skemakkni`='$sk[id]' AND `id_jadwal`='$_GET[idj]' AND `jenis_soal`='FR.IA.06' AND `status`='1'";
	$gettrigersoal = $conn->query($sqlgettrigersoal);
	$jumtriger = $gettrigersoal->num_rows;
	if ($jumtriger > 0) {
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
							<tr><td width='25%'>Nama Asesi</td><td colspan='2'>$_SESSION[namalengkap]</td></tr>
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
					$sqlcekjawaban = "SELECT * FROM `asesmen_ia06` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_jadwal`='$_GET[idj]' AND `id_pertanyaan`='$gpp[id]'";
					$cekjawaban = $conn->query($sqlcekjawaban);
					$jjw = $cekjawaban->fetch_assoc();
					echo "<tr><td>$nopp.</td><td>" . $gpp['pertanyaan'] . "</td><td rowspan='2'>
									<input type='radio' name='rekomendasi$gpp[id]' id='rekomendasi$gpp[id]1' value='K' ";
					if ($jjw['rekomendasi'] == 'K') {
						echo " checked";
					} else {
						echo "";
					}
					echo " disabled>&nbsp;Kompeten<br>
									<input type='radio' name='rekomendasi$gpp[id]' id='rekomendasi$gpp[id]2' value='BK' ";
					if ($jjw['rekomendasi'] == 'BK') {
						echo " checked";
					} else {
						echo "";
					}
					echo " disabled>&nbsp;Belum Kompeten</td></tr>
									<tr><td colspan='2'>Jawaban:<br>
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
		/* $sqlgetkeputusan="SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$_SESSION[namauser]' AND `id_jadwal`='$_GET[idj]'";
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
										<img src='$ttdx[file]' width='400px'/>
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
									<a class='btn btn-danger form-control' id=reset-validate-form href='?module=jadwal'>Kembali</a>
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
	} else {
		echo "<!-- Main content -->
		<section class='content'>
		  <div class='row'>
			<div class='col-xs-12'>
			  <div class='box'>
				<div class='box-body'><!-- /.start box-body main-->
					<h2 class='text-red'>Maaf Soal ini belum bisa Anda akses, silahkan hubungi Asesor Anda untuk membuka soal ini</h2>
				</div>
				<!-- /.end box-body main-->
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
// Apabila modul tidak ditemukan
else {
	echo "<p><b>MODUL BELUM ADA ATAU BELUM LENGKAP</b></p>";
}
?>