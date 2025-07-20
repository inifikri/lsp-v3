<?php
	session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Laman Reset Password | SiLSP</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.5 -->
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="../plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
   Reset Password Login Asesor
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
  <br>
	<?php	
	include "../config/koneksi.php";
	if( isset( $_REQUEST['reset'] )){
		$npm=$_POST['npm'];
		$pertanyaan=$_POST['pertanyaan'];
		$jawaban=$_POST['jawaban'];
		$kodeaman=$_POST['kode'];
		$kodeaman2=$_SESSION['digit'];
		$generatepassword=rand(100000,999999);
		$password=md5($generatepassword);	
		
		// awal if
		if ($kodeaman!=$kodeaman2){
			echo "<center><font color='red' size='5'>Maaf Kode Keamanan yang Anda masukkan salah<br>Silahkan ulangi lagi</font></center>";
		}else{
			$periksa=$conn->query("SELECT * FROM `asesor` WHERE `no_induk`='$npm' AND `no_induk`!=''") ;
			switch ($pertanyaan){
				default :
					echo "<center><font color='red' size='5'>Maaf Anda belum memilih pilihan pertanyaan keamanan</font></center>";
				break;
				case 'nomorktp':
					$ds1 = $periksa->fetch_assoc();
					$smsnya = "Yth. $ds1[nama], Kata sandi baru Anda adalah $generatepassword Silahkan ganti password Anda di Laman http://".$_SERVER['HTTP_HOST']."/asesor";

					if ($jawaban==$ds1['no_ktp']){
						echo "<center>Yth. <b>$ds1[nama]</b>, Kata sandi baru Anda adalah:<br /><font color='red' size='5'><b>$generatepassword</b></font><br />Silahkan ganti password Anda di Laman Asesor<br><a href='index.php' class='btn btn-primary btn-block btn-flat'>Masuk Laman Asesor</a></center>";
						$sql2 = "UPDATE `asesor` SET `password`='$password' WHERE `no_induk`='$npm' AND `no_induk`!=''";
						$result2 = $conn->query($sql2);

						$sql3 = "INSERT INTO `outbox` (`DestinationNumber`, `TextDecoded`, `Coding`, `SenderID`, `CreatorID`) VALUES ('$ds1[no_hp]', '$smsnya', 'Default_No_Compression', 'MyPhone1', 'MyPhone1')";
						$result3 = $conn->query($sql3);
					}else{
						echo "<center><font color='red' size='5'>Maaf Jawaban Anda Salah</font></center>";
					}
				break;
				case 'tahunlahir':
					$ds1 = $periksa->fetch_assoc();
					$smsnya = "Yth. $ds[nama], Kata sandi baru Anda adalah $generatepassword Silahkan ganti password Anda di Laman http://".$_SERVER['HTTP_HOST']."/asesor";

					if ($jawaban==$ds1['tgl_lahir']){
						echo "<center>Yth. <b>$ds1[nama]</b>, Kata sandi baru Anda adalah:<br /><font color='red' size='5'><b>$generatepassword</b></font><br />Silahkan ganti password Anda di Laman Asesor<br><a href='index.php' class='btn btn-primary btn-block btn-flat'>Masuk Laman Asesor</a></center>";
						$sql2 = "UPDATE `asesor` SET `password`='$password' WHERE `no_induk`='$npm' AND `no_induk`!=''";
						$result2 = $conn->query($sql2);
						$sql3 = "INSERT INTO `outbox` (`DestinationNumber`, `TextDecoded`, `Coding`, `SenderID`, `CreatorID`) VALUES ('$ds1[no_hp]', '$smsnya', 'Default_No_Compression', 'MyPhone1', 'MyPhone1')";
						$result3 = $conn->query($sql3);
					}else{
						echo "<center><font color='red' size='5'>Maaf Jawaban Anda Salah</font></center>";
					}
				break;
			} 

		}
			//akhir if
	}
	?>

    <form method="post">
      <div class="form-group has-feedback">
<?php
$kodekeamanan=rand(1000,9999);
$dsn=$conn->query("SELECT `id`, `nama`, `gelar_depan`, `gelar_blk` FROM `asesor` ORDER BY `nama` ASC");
?>       </div>
      <div class="form-group has-feedback">
        <input required type="text" name="npm" class="form-control" placeholder="No. Induk Asesor">
      </div>
<div class="form-group has-feedback">
        <p>Pertanyaan Keamanan : <b><select name='pertanyaan' class="form-control">
		<option>-- Pilih Pertanyaan Keamanan --</option>
		<option value='nomorktp'>Berapa nomor KTP Anda?</option>
		<option value='tahunlahir'>Tanggal lahir Anda (TTTT-BB-HH)?</option>
	</select></b></p><input type="hidden" name="kodegen" value="<?php echo"$kodekeamanan"; ?>">
      </div>
	<div class="form-group has-feedback">
        <input required type="text" name="jawaban" class="form-control" placeholder="Jawaban Anda">
      </div>
	<div class="form-group has-feedback">
        <p>Kode Keamanan : <font color='blue'><b><?php echo "<img src='captcha.php' width='120' height='30' border='1' alt='CAPTCHA'>"; ?></b></font></p><input required type="hidden" name="kodegen" value="<?php echo"$kodekeamanan"; ?>">
      </div>
	<div class="form-group has-feedback">
        <input required type="text" name="kode" class="form-control" placeholder="Kode Keamanan">
      </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <!--<label>
              <input type="checkbox"> Remember Me
            </label>-->
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" name="reset" class="btn btn-primary btn-block btn-flat" title="Setel Ulang Kata Sandi">Reset</button>
        </div>
        <!-- /.col -->
      </div>
    </form>
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.1.4 -->
<script src="../plugins/jQuery/jQuery-2.1.4.min.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="../bootstrap/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="../plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>
