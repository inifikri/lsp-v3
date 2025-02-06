<?php
include "config/koneksi.php";
include "classes/class.phpmailer.php";
/*function antiinjection($data){
  $filter_sql = mysql_real_escape_string(stripslashes(strip_tags(htmlspecialchars($data,ENT_QUOTES))));
  return $filter_sql;
}*/
$sqlidentitas="SELECT * FROM `identitas`";
$identitas=$conn->query($sqlidentitas);
$iden=$identitas->fetch_assoc();
$urldomain=$iden['url_domain'];

$namanya = strtoupper(str_replace("'","`",$_POST['nama']));
$no_hp = $_POST['no_hp'];
$email = $_POST['email'];

$namanyainput=mysqli_escape_string($conn,addslashes(strip_tags(htmlspecialchars($namanya,ENT_QUOTES))));
$no_hpinput=mysqli_escape_string($conn,addslashes(strip_tags(htmlspecialchars($no_hp,ENT_QUOTES))));
$emailinput=mysqli_escape_string($conn,addslashes(strip_tags(htmlspecialchars($email,ENT_QUOTES))));
$alamatinput=mysqli_escape_string($conn,addslashes(strip_tags(htmlspecialchars($_POST['alamat'],ENT_QUOTES))));
$ktpinput=mysqli_escape_string($conn,addslashes(strip_tags(htmlspecialchars($_POST['no_ktp'],ENT_QUOTES))));
$nama_kantorinput=mysqli_escape_string($conn,addslashes(strip_tags(htmlspecialchars($_POST['nama_kantor'],ENT_QUOTES))));
$email_kantorinput=mysqli_escape_string($conn,addslashes(strip_tags(htmlspecialchars($_POST['email_kantor'],ENT_QUOTES))));
$telp_kantorinput=mysqli_escape_string($conn,addslashes(strip_tags(htmlspecialchars($_POST['telp_kantor'],ENT_QUOTES))));
$fax_kantorinput=mysqli_escape_string($conn,addslashes(strip_tags(htmlspecialchars($_POST['fax_kantor'],ENT_QUOTES))));

$sqlcek="SELECT `no_ktp` FROM `pengusul` WHERE `no_ktp`='$ktpinput' AND `nama_kantor`='$nama_kantorinput'";
$cekktp=$conn->query($sqlcek);
$ktp=$cekktp->num_rows;

if ($ktp==0){
    // Simpan data pendaftaran calon mahasiswa
    $tgldaftar=date("Y-m-d");
    $digitthn=date("Y");
    $digitbln=date("m");
    $digittgl=date("d");
    $genpass=rand(100000,999999);
    $pass1=md5($genpass);
    $pass2=substr($pass1,-6);
    $password=md5($pass2);
    $digitnohp=substr($ktpinput,-6);
    $gennopendaf=$digitthn.$digitbln.$digittgl.$digitnohp;
    //2017080512345
    $ip = $_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
    $sql="INSERT INTO `pengusul`(`no_pendaftaran`,
	`password`,
	`nama`,
	`email`,
	`nohp`,
	`no_ktp`,
	`nama_kantor`,
	`jenis_kantor`,
	`email_kantor`,
	`telp_kantor`,
	`fax_kantor`,
	`alamat_kantor`,
	`kelurahan_kantor`,
	`kecamatan_kantor`,
	`kota_kantor`,
	`propinsi_kantor`,
	`tgl_daftar`) VALUES ('$gennopendaf',
	'$password',
	'$namanyainput',
	'$emailinput',
	'$no_hpinput',
	'$ktpinput',
	'$nama_kantorinput',
	'$_POST[jenis_kantor]',
	'$email_kantorinput',
	'$telp_kantorinput',
	'$fax_kantorinput',
	'$alamatinput',
	'$_POST[kelurahan]',
	'$_POST[kecamatan]',
	'$_POST[kota]',
	'$_POST[propinsi]',
	'$tgldaftar')";
	$conn->query($sql);
    $getbank=$conn->query("SELECT * FROM `rekeningbayar` WHERE `aktif`='Y'");
    // Kirim email dalam format HTML ke Pendaftar
    $pesan ="Terimakasih telah melakukan pendaftaran Akun Pengusul Peserta Uji Kompetensi Keahlian di LSP<br /><br />  
            Nomor Pendaftaran: $gennopendaf <br />
            Nama: $namanyainput <br />
            Institusi: $nama_kantorinput <br />
            Nomor Handphone: $no_hpinput <br />
            Kata Sandi (Password): $pass2 <br />
    		<br /><br />Silahkan lakukan masuk/login ke http://".$urldomain."/pengusul, dan isi data-data yang diperlukan.";
    $subjek="Terimakasih telah melakukan Pendaftaran di SILSP";
    $dari = "From: noreply@".$urldomain."\r\n";
    $dari .= "Content-type: text/html\r\n";
    // Kirim email ke member
	$sqlgetsmtp="SELECT * FROM `smtp` WHERE `aktif`='Y'";
	$getsmtp=$conn->query($sqlgetsmtp);
	$gsmtp=$getsmtp->fetch_assoc();

	$sqlidentitas="SELECT * FROM `identitas`";
	$identitas=$conn->query($sqlidentitas);
	$iden=$identitas->fetch_assoc();

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
	$mail->setFrom("$gsmtp[username]",$iden['nama_lsp']);
	//Set an alternative reply-to address
	$mail->addReplyTo("$gsmtp[username]",$iden['nama_lsp']);
	$mail->Subject = $subjek; //subyek email
	$mail->AddAddress($emailinput,$namanyainput);  //tujuan email
	$mail->MsgHTML($pesan);
	if ($mail->Send()){
		echo "";
	}else{
		echo "Notifikasi Email Gagal Terkirim, periksa pengaturan di menu Manajemen, sub menu Pengaturan SMTP";
	}

    	//mail($email,$subjek,$pesan,$dari);
    
    //SMS Pendaftar
    
    $isisms="Yth. $namanyainput Pendaftaran Pengusul berhasil, No. Pendaftaran Anda adalah $gennopendaf, Password : $pass2 Silahkan masuk/Login ke http://".$urldomain."/pengusul";
    if (strlen($no_hp)>8){
        $sqloutbox="INSERT INTO `outbox` (`DestinationNumber`, `TextDecoded`, `Coding`,`SenderID`,`CreatorID`) VALUES ('$no_hpinput','$isisms','Default_No_Compression','MyPhone1','MyPhone1')";
	$outbox=$conn->query($sqloutbox);
    }
    // Tampilkan bukti pendaftaran==============
    $tgl_daftar=date("d-m-Y");
    echo"<!DOCTYPE html>
    <html>
    <head>
      <meta charset='utf-8'>
      <meta http-equiv='X-UA-Compatible' content='IE=edge'>
      <title>Bukti Pendaftaran Akun Pengusul Peserta di Sistem Informasi Lembaga Sertifikasi Profesi</title>
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
    <body class='hold-transition skin-blue sidebar-mini'>
    
    
        <!-- Content Header (Page header) -->
        <section class='content-header'>
          <h1>
            Bukti Pendaftaran
            <small>No. $gennopendaf</small>
          </h1>
          <ol class='breadcrumb'>
            <li><a href='index.php'><i class='fa fa-dashboard'></i> Home</a></li>
            <li class='active'>Bukti Pendaftaran</li>
          </ol>
        </section>
    
        <div class='pad margin no-print'>
          <div class='callout callout-success' style='margin-bottom: 0!important;'>
            <h4><i class='fa fa-check-square-o'></i> Pendaftaran Berhasil</h4>
            Nomor Pendaftaran Anda : <h4>$gennopendaf</h4>
    		Catat Nomor Pendaftaran Anda atau cetak bukti pendaftaran, dengan tombol Cetak pada bagian bawah laman.
          </div>
        </div>
    
        <!-- Main content -->
        <section class='invoice'>
          <!-- title row -->
          <div class='row'>
            <div class='col-xs-12'>
              <h2 class='page-header'>
                <i class='fa fa-globe'></i> Sistem Informasi LSP
                <small class='pull-right'>Tanggal Pendaftaran: $tgl_daftar</small>
              </h2>
            </div>
            <!-- /.col -->
          </div>
          <!-- info row -->
          <div class='row invoice-info'>
            <div class='col-sm-4 invoice-col'>
              Identitas Pendaftar
              <address>
                <strong>$namanyainput</strong><br>
                Institusi : $nama_kantorinput<br>
                No. Pendaftaran : $gennopendaf<br>
                Kata Sandi (Password) : <b>$pass2</b><br>
                No. HP : $no_hpinput<br>
                Email : $emailinput
              </address>
            </div>
          </div>
          <!-- /.row -->";
            if ($getprod[biaya]!='0'){
          echo"<div class='row'>
            <!-- accepted payments column -->
            <div class='col-xs-6'>
              <!--<p class='lead'>Pembayaran Biaya Pendaftaran:</p>-->";
              while ($rek=$getbank->fetch_assoc()){
                    echo"<img src='images/$rek[logo]' alt='$rek[bank]' height='20px'><br>Rekening $rek[bank] No. $rek[norek]<br>an. $rek[atasnama]";
              }
    
    
            echo"</div>
            <!-- /.col -->
            <div class='col-xs-6'>
              <!--<p class='lead'>Pembayaran hingga 2/22/2014</p>-->
    
              <div class='table-responsive'>
                <table class='table'>
                  <tr>
                    <!--<th style='width:50%'>Biaya Pendaftaran:</th>
                    <td>$getprod[biaya]</td>-->
                  </tr>
                 </table>
              </div>
            </div>
            <div class='col-xs-12'>
    		  <p class='text-muted well well-sm no-shadow' style='margin-top: 10px;'>
                Lengkapi data Anda di laman pengusul di <strong>http://".$urldomain."/pengusul</strong>
              </p>
    		</div>
            <!-- /.col -->
          </div>
          <!-- /.row -->";
            }
          echo"<!-- this row will not appear when printing -->
          <div class='row no-print'>
            <div class='col-xs-12'>
                <form action='pendaftaran-pengusul-print.php' method='post'>
                <input type='hidden' name='nopendaf' value='$gennopendaf'>
                <input type='hidden' name='namanya' value='$namanyainput'>
                <input type='hidden' name='institusi' value='$nama_kantorinput'>
                <input type='hidden' name='no_hp' value='$no_hpinput'>
                <input type='hidden' name='email' value='$emailinput'>
                <input type='hidden' name='password' value='$pass2'>
                <input type='hidden' name='tgl_daftar' value='$tgl_daftar'>
                <input type='hidden' name='biaya' value='$getprod[biaya]'>

                <input type='submit' class='btn btn-default' value='Cetak'>
                <a href='http://".$urldomain."/pengusul' target='_blank' class='btn btn-default'><i class='fa fa-sign-in'></i> Masuk</a>

                </form>
              <!--<button type='button' class='btn btn-primary pull-right' style='margin-right: 5px;'>
                <i class='fa fa-download'></i> Unduh PDF
              </button>-->
            </div>
          </div>
        </section>
        <!-- /.content -->
        <div class='clearfix'></div>
    
    
    <!-- jQuery 2.2.3 -->
    <script src='plugins/jQuery/jquery-2.2.3.min.js'></script>
    <!-- Bootstrap 3.3.6 -->
    <script src='bootstrap/js/bootstrap.min.js'></script>
    <!-- FastClick -->
    <script src='plugins/fastclick/fastclick.js'></script>
    <!-- AdminLTE App -->
    <script src='dist/js/app.min.js'></script>
    <!-- AdminLTE for demo purposes -->
    <script src='dist/js/demo.js'></script>
    </body>
    </html>";

    //==========================================
    //echo "<script>alert('Pendaftaran Anda berhasil, Nomor Pendaftaran Anda adalah $gennopendaf, Silahkan masuk/Login'); window.location = '/pengusul'</script>";

    
}else{
    echo "<script>alert('Maaf Pendaftaran Anda Gagal, Nomor Induk Kependudukan (Nomor KTP) atau Institusi telah terdaftar sebelumnya'); window.location = 'index.php'</script>";

}
?>
