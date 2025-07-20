<?php
include "config/koneksi.php";
$sqlidentitas="SELECT * FROM `identitas`";
$identitas=$conn->query($sqlidentitas);
$iden=$identitas->fetch_assoc();
$urldomain=$iden['url_domain'];

echo "<!DOCTYPE html>
<html>
<head>
  <meta charset='utf-8'>
  <meta http-equiv='X-UA-Compatible' content='IE=edge'>
  <title>SILSP | Bukti Pendaftaran</title>
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

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src='https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js'></script>
  <script src='https://oss.maxcdn.com/respond/1.4.2/respond.min.js'></script>
  <![endif]-->
</head>
<body onload='window.print();'>
<div class='wrapper'>
  <!-- Main content -->
  <section class='invoice'>
          <!-- title row -->
          <div class='row'>
            <div class='col-xs-12'>
              <h2 class='page-header'>
                <i class='fa fa-globe'></i> Sistem Informasi $iden[nama_lsp]
                <small class='pull-right'>Tanggal Pendaftaran: $_POST[tgl_daftar]</small>
              </h2>
            </div>
            <!-- /.col -->
          </div>
          <!-- info row -->
          <div class='row invoice-info'>
            <div class='col-xs-9'>
              Identitas Pendaftar
              <address>
                <strong>$_POST[namanya]</strong><br>
                No. Pendaftaran :<br><strong>$_POST[nopendaf]</strong><br>
                Kata Sandi (Passsowrd) :<br><strong>$_POST[password]</strong><br>
                No. HP :<br><strong>$_POST[no_hp]</strong><br>
                Email :<br><strong>$_POST[email]</strong>
              </address>
            </div>
          </div>
          <!-- /.row -->";
		  /*if ($_POST['biaya']!='0'){
          echo"<div class='row'>
            <!-- accepted payments column -->
            <div class='col-xs-6'>
              <!--<p class='lead'>Pembayaran Biaya Pendaftaran:</p>-->";
              $getbank=$conn->query("SELECT * FROM `rekeningbayar` WHERE `aktif`='Y'");

              while ($rek=$getbank->fetch_assoc()){
                    echo"<img src='images/$rek[logo]' alt='$rek[bank]' height='20px'><br>Rekening $rek[bank] No. $rek[norek]<br>an. $rek[atasnama]";
          } */
    
    
            echo"</div>
            <!-- /.col -->
            <div class='col-xs-6'>
              <div class='table-responsive'>
                <table class='table'>
                  <tr>

                  </tr>
                 </table>
              </div>
            </div>
            <div class='col-xs-12'>
    		  <p class='text-muted well well-sm no-shadow' style='margin-top: 10px;'>
                Lengkapi data Anda di laman Asesi di <strong>http://".$urldomain."/asesi</strong>
              </p>
    		</div>
            <!-- /.col -->
        </div>
        <!-- /.row -->";
        //}
  echo"</section>
  <!-- /.content -->
</div>
<!-- ./wrapper -->
</body>
</html>
";
?>