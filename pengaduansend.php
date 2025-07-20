<?php
include "classes/class.phpmailer.php";
include "config/koneksi.php";
session_start();
$kodeaman=$_POST['kode'];
$kodeaman2=$_SESSION['digit'];

if ($kodeaman<>$kodeaman2){
	echo "<center>Maaf Kode Keamanan yang Anda masukkan Salah, Silahkan coba <a href='pengaduan.php' class='btn btn-primary btn-block btn-flat'>Kembali</a></center>";
}else{

$sqlidentitas="SELECT * FROM `identitas`";
$identitas=$conn->query($sqlidentitas);
$iden=$identitas->fetch_assoc();
$urldomain=$iden['url_domain'];

$namanya = strtoupper(str_replace("'","`",$_POST['nama']));
$no_hp = $_POST['no_hp'];
$email = $_POST['email'];
$responden = $_POST['jenis_responden'];
$aduan = $_POST['aduan'];
$namanyainput=mysqli_escape_string($conn,addslashes(strip_tags(htmlspecialchars($namanya,ENT_QUOTES))));
$no_hpinput=mysqli_escape_string($conn,addslashes(strip_tags(htmlspecialchars($no_hp,ENT_QUOTES))));
$emailinput=mysqli_escape_string($conn,addslashes(strip_tags(htmlspecialchars($email,ENT_QUOTES))));
$respondeninput=mysqli_escape_string($conn,addslashes(strip_tags(htmlspecialchars($responden,ENT_QUOTES))));
$aduaninput=mysqli_escape_string($conn,addslashes(strip_tags(htmlspecialchars($aduan,ENT_QUOTES))));

    // Simpan data 
    $tgl_daftar2=date("d-m-Y");
    $tgl_daftar=date("Y-m-d");
    $digitthn=date("Y");
    $digitbln=date("m");
    $digittgl=date("d");
    $genpass=rand(100000,999999);
    $pass1=md5($genpass);
    $pass2=substr($no_hp,-3);
    $password=md5($pass2);
    $digitnohp=date('Ymd');
    //$gennopendaf=$digitthn.$digitbln.$digittgl.$digitnohp;
    $gennopendaf=$digitnohp.'-'.$pass2;
    //2017080512345
    $ip = $_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
    $sql="INSERT INTO `pengaduan`(`no_tiket`,
	`nama`,
	`email`,
	`nohp`,
	`aduan`,
	`status`,
	`tgl_aduan`,
	`jenis_responden`) VALUES ('$gennopendaf',
	'$namanyainput',
	'$emailinput',
	'$no_hpinput',
	'$aduaninput',
	'masuk',
	'$tgl_daftar',
	'$_POST[jenis_responden]')";
	$conn->query($sql);
    // Kirim email dalam format HTML ke Pendaftar
    $pesan ="Terimakasih telah melakukan pengaduan atas keetidaksesuaian di $iden[nama_lsp]<br /><br />  
            Nomor Tiket: $gennopendaf <br />
            Nama: $namanyainput <br />
            Nomor Handphone: $no_hpinput <br />
            Aduan Anda: $aduaninput <br />";
    $subjek="Terimakasih telah melakukan pengaduan di $iden[nama_lsp]";
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
    
    $isisms="Yth. $namanyainput Pengaduan Anda berhasil, No. Tiket Anda adalah $gennopendaf";
    if (strlen($no_hp)>8){
        $sqloutbox="INSERT INTO `outbox` (`DestinationNumber`, `TextDecoded`, `Coding`,`SenderID`,`CreatorID`) VALUES ('$no_hpinput','$isisms','Default_No_Compression','MyPhone1','MyPhone1')";
	$outbox=$conn->query($sqloutbox);
    }
    // Tampilkan bukti pendaftaran==============
    echo"<!DOCTYPE html>
    <html>
    <head>
      <meta charset='utf-8'>
      <meta http-equiv='X-UA-Compatible' content='IE=edge'>
      <title>Bukti Aduan Sistem Informasi Lembaga Sertifikasi Kompetensi</title>
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
            Bukti Pengaduan
            <small>No. $gennopendaf</small>
          </h1>
          <ol class='breadcrumb'>
            <li><a href='index.php'><i class='fa fa-dashboard'></i> Home</a></li>
            <li class='active'>Bukti Pengaduan</li>
          </ol>
        </section>
    
        <div class='pad margin no-print'>
          <div class='callout callout-success' style='margin-bottom: 0!important;'>
            <h4><i class='fa fa-check-square-o'></i> Pengaduan Berhasil</h4>
            Nomor Pengaduan Anda : <h4>$gennopendaf</h4>
    		Catat Nomor Pengaduan Anda atau cetak bukti pengaduan, dengan tombol Cetak pada bagian bawah laman.
          </div>
        </div>
    
        <!-- Main content -->
        <section class='invoice'>
          <!-- title row -->
          <div class='row'>
            <div class='col-xs-12'>
              <h2 class='page-header'>
                <i class='fa fa-globe'></i> Sistem Informasi LSK
                <small class='pull-right'>Tanggal Aduan: $tgl_daftar2</small>
              </h2>
            </div>
            <!-- /.col -->
          </div>
          <!-- info row -->
          <div class='row invoice-info'>
            <div class='col-sm-4 invoice-col'>
              Identitas Pendaftar
              <address>
                <strong>$namanya</strong><br>
                No. Aduan : $gennopendaf<br>
                No. HP : $no_hpinput<br>
                Email : $emailinput<br>
		Aduan : $aduaninput
              </address>
            </div>
          </div>
          <!-- /.row -->";
/*             if ($getprod['biaya']!='0'){
          echo"<div class='row'>
            <!-- accepted payments column -->
            <div class='col-xs-6'>
              <!--<p class='lead'>Pembayaran Biaya Pendaftaran:</p>-->";
              while ($rek=$getbank->fetch_assoc()){
                    echo"<img src='images/$rek[logo]' alt='$rek[bank]' height='20px'><br>Rekening $rek[bank] No. $rek[norek]<br>an. $rek[atasnama]";
              } */
    
    
            echo"</div>
            <!-- /.col -->
            <div class='col-xs-6'>
              <!--<p class='lead'>Pembayaran hingga 2/22/2014</p>-->
    
              <div class='table-responsive'>
                <table class='table'>
                  <tr>

                  </tr>
                 </table>
              </div>
            </div>
            <div class='col-xs-12'>
    		  <p class='text-muted well well-sm no-shadow' style='margin-top: 10px;'>Pengaduan Anda akan diproses sesuai sengan SOP pelayanan pengaduan Lembaga Sertifikasi Kompetensi yang berlaku.
              </p>
    		</div>
            <!-- /.col -->
          </div>
          <!-- /.row -->";
           // }
          echo"<!-- this row will not appear when printing -->
          <div class='row no-print'>
            <div class='col-xs-12'>
                <form action='pengaduan-print.php' method='post'>
                <input type='hidden' name='nopendaf' value='$gennopendaf'>
                <input type='hidden' name='namanya' value='$namanyainput'>
                <input type='hidden' name='no_hp' value='$no_hpinput'>
                <input type='hidden' name='email' value='$emailinput'>
                <input type='hidden' name='aduan' value='$aduaninput'>
                <input type='hidden' name='tgl_daftar' value='$tgl_daftar'>

                <input type='submit' class='btn btn-default' value='Cetak'>
		<a href='index.php' class='btn btn-default'>Kembali</a>

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
    //echo "<script>alert('Pendaftaran Anda berhasil, Nomor Pendaftaran Anda adalah $gennopendaf, Silahkan masuk/Login'); window.location = '/asesi'</script>";

    
}//end cek captcha
?>
