<?php
$tahunskr=date("Y");
include "config/koneksi.php";

$sqlidentitas="SELECT * FROM `identitas`";
$identitas=$conn->query($sqlidentitas);
$iden=$identitas->fetch_assoc();

echo "<!DOCTYPE html>
<html>
<head>
  <meta charset='utf-8'>
  <meta http-equiv='X-UA-Compatible' content='IE=edge'>
  <title>$iden[nama_lsp] | Sistem Informasi Persuratan</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
  <!-- Bootstrap 3.3.6 -->
  <link rel='stylesheet' href='bootstrap/css/bootstrap.min.css'>
  <!-- Font Awesome -->
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css'>
  <!-- Ionicons -->
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css'>
  <!-- Theme style -->
  <link rel='stylesheet' href='dist/css/AdminLTE.min.css'>
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel='stylesheet' href='dist/css/skins/_all-skins.min.css'>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src='https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js'></script>
  <script src='https://oss.maxcdn.com/respond/1.4.2/respond.min.js'></script>
  <![endif]-->
</head>
<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
<body class='hold-transition skin-blue layout-top-nav'>
<div class='wrapper'>
  <!-- Full Width Column -->
  <div class='content-wrapper'>
    <div class='container'>
      <!-- Content Header (Page header) -->
      <section class='content-header'>
        <h1>
          $iden[nama_lsp]
          <small>Sistem Informasi Persuratan</small>
        </h1>
        <ol class='breadcrumb'>
          <li><a href='http://$iden[url_domain]'><i class='fa fa-dashboard'></i> Home</a></li>
          <li class='active'>Persuratan</li>
        </ol>
      </section>

      <!-- Main content -->
      <section class='content'>
        <div class='callout callout-success'>
          <h4>Selamat Datang</h4>

          <p>Selamat datang di sistem informasi persuratan elektronik (E-Surat). Sistem ini dipergunakan untuk memberikan layanan persuratan secara digital/ elektronik.</p>
        </div>
        <div class='callout callout-warning'>
          <h4>Perhatian!</h4>

          <p>Dokumen yang terdistribusi dalam sistem persuratan elektronik ini merupakan dokumen yang dikeluarkan maupun dokumen yang masuk secara resmi dan tercatat. Sistem ini dipergunakan secara terbatas. Penyalahgunaan data maupun informasi di dalam sistem persuratan ini akan diproses sesuai ketentuan dan peraturan perundangan yang berlaku. Penggunaan tandatangan digital (digital signature) dalam sistem persuratan ini sesuai dengan Undang- undang Nomor 11 Tahun 2008 tentang Informasi dan Teknologi Elektronik.
	  Berdasarkan Pasal 5 Ayat (1) Undang-Undang No. 11 Tahun 2008, informasi Elektronik dan/atau Dokumen Elektronik dan/atau hasil cetaknya merupakan <b>alat bukti hukum yang sah</b>, hal ini merupakan perluasan dari alat bukti yang sah sesuai dengan Hukum Acara yang berlaku di Indonesia.</p>
        </div>
        <div class='box box-default'>
          <div class='box-header with-border'>";
	if (!empty($_GET['id']) && !empty($_GET['idp'])){
		if (is_numeric($_GET['idp'])){
			$getpenerima="SELECT * FROM `surat_penerima` WHERE `id`='$_GET[idp]'";
			$penerima=$conn->query($getpenerima);
			$pnm=$penerima->fetch_assoc();
		}
		echo "<h3 class='box-title'>Surat Anda</h3>
          </div>
          <div class='box-body'>
		<label>Nama Penerima</label>
		<p>$pnm[nama]</p>
		<label>Surat</label>";
		if (is_numeric($_GET['id'])){
			$getsurat="SELECT * FROM `surat_item` WHERE `id`='$_GET[id]'";
			$suratpenerima=$conn->query($getsurat);
			$srt=$suratpenerima->fetch_assoc();
		}
		echo "<embed src='foto_surat/$srt[file]' width='100%' height='800px'/><br><a href='foto_surat/$srt[file]' target='_blank' class='btn btn-primary btn-block'>Unduh Surat</a>";
		$waktubaca=date("Y-m-d H:i:s");
		$sqlupdate="UPDATE `surat_penerima` SET `dibaca`='Y', `waktu_baca`='$waktubaca' WHERE `id`='$_GET[idp]' AND `id_surat`='$_GET[id]'";
		$conn->query($sqlupdate);
	}else{
		echo "<h3 class='box-title'>Surat Tidak Ditemukan</h3>
          </div>
          <div class='box-body'>";

	}
	
          echo "</div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </section>
      <!-- /.content -->
    </div>
    <!-- /.container -->
  </div>
  <!-- /.content-wrapper -->
  <footer class='main-footer'>
    <div class='container'>
    </div>
    <!-- /.container -->
  </footer>
</div>
<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
<script src='plugins/jQuery/jquery-2.2.3.min.js'></script>
<!-- Bootstrap 3.3.6 -->
<script src='bootstrap/js/bootstrap.min.js'></script>
<!-- SlimScroll -->
<script src='plugins/slimScroll/jquery.slimscroll.min.js'></script>
<!-- FastClick -->
<script src='plugins/fastclick/fastclick.js'></script>
<!-- AdminLTE App -->
<script src='dist/js/app.min.js'></script>
<!-- AdminLTE for demo purposes -->
<script src='dist/js/demo.js'></script>
</body>
</html>";
?>
