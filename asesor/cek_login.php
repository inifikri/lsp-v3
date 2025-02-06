<?php
include "../config/koneksi.php";
$username = mysqli_escape_string($conn,addslashes(htmlspecialchars($_POST['username'],ENT_QUOTES)));
$pass     = md5(mysqli_escape_string($conn,addslashes(strip_tags(htmlspecialchars($_POST['password'],ENT_QUOTES)))));

// pastikan username dan password adalah berupa huruf atau angka.
if (!ctype_alnum($username) && !empty($username) OR !ctype_alnum($pass) && !empty($pass)){
  echo "Sekarang loginnya tidak bisa di injeksi lho. $username $pass".!ctype_alnum($username);
}
else{
$sqllogin="SELECT * FROM `asesor` WHERE `no_ktp`='$username' AND `password`='$pass' AND `aktif`='Y' OR `no_hp`='$username' AND `password`='$pass' AND `aktif`='Y'";
$login=$conn->query($sqllogin);
$ketemu=$login->num_rows;
$r=$login->fetch_assoc();

// Apabila username dan password ditemukan
if ($ketemu > 0){
  session_start();
	$timeout = 60; // setting timeout dalam menit
	$logout = "asesor/index.php"; // redirect halaman logout

	$timeout = $timeout * 60; // menit ke detik
	if(isset($_SESSION['start_session'])){
		$elapsed_time = time()-$_SESSION['start_session'];
		if($elapsed_time >= $timeout){
			session_destroy();
			echo "<script type='text/javascript'>alert('Sesi telah berakhir');window.location='$logout'</script>";
		}
	}

	$_SESSION['start_session']=time();

  $_SESSION['namauser']     = $r['no_ktp'];
  $_SESSION['namalengkap']  = $r['nama'];
  $_SESSION['passuser']     = $r['password'];
  $_SESSION['foto']    	    = $r['foto'];


// Simpan log login asesor

$tgljam=date("Y-m-d h:i:s");
$ip = $_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
//mysql_query("INSERT INTO `loguser`(`username`, `waktu`, `IP`, `from`,`inorout`) VALUES ('$r[username]','$tgljam','$ip','http://batik.silsp.online/asesor','1')");





  header('location:media.php?module=home');
}
else{

$sqllogin2="SELECT * FROM `asesor` WHERE `no_ktp`='$_POST[username]'";
$login2=$conn->query($sqllogin2);
//$ketemu=$login->num_rows;
$rf=$login2->fetch_assoc();
   echo "
  <!DOCTYPE html>
<html>
<head>
  <meta charset='utf-8'>
  <meta http-equiv='X-UA-Compatible' content='IE=edge'>
  <title>Dashboard | Lockscreen</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
  <!-- Bootstrap 3.3.6 -->
  <link rel='stylesheet' href='../bootstrap/css/bootstrap.min.css'>
  <!-- Font Awesome -->
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css'>
  <!-- Ionicons -->
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css'>
  <!-- Theme style -->
  <link rel='stylesheet' href='../dist/css/AdminLTE.min.css'>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src='https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js'></script>
  <script src='https://oss.maxcdn.com/respond/1.4.2/respond.min.js'></script>
  <![endif]-->
</head>
<body class='hold-transition lockscreen'>
<!-- Automatic element centering -->
<div class='lockscreen-wrapper'>
  <div class='lockscreen-logo'>
    <a href='index.php'><b>Dashboard</b>Asesor</a>
  </div>
  <!-- User name -->
  <div class='lockscreen-name'>$_POST[username]</div>

  <!-- START LOCK SCREEN ITEM -->
  <div class='lockscreen-item'>
    <!-- lockscreen image -->
    <div class='lockscreen-image'>
      <img src='../foto_asesor/$rf[foto]' alt='User Image'>
    </div>
    <!-- /.lockscreen-image -->

    <!-- lockscreen credentials (contains the form) -->
    <form class='lockscreen-credentials' action='cek_login.php' method='post'>
      <div class='input-group'>
        <input type='password' name='password' class='form-control' placeholder='password'>
        <input type='hidden' name='username' class='form-control' value='$_POST[username]'>

        <div class='input-group-btn'>
          <button type='submit' class='btn'><i class='fa fa-arrow-right text-muted'></i></button>
        </div>
      </div>
    </form>
    <!-- /.lockscreen credentials -->

  </div>
  <!-- /.lockscreen-item -->
  <div class='help-block text-center'>
    Masukkan password Anda yang sesuai untuk masuk ke Dashboard
  </div>
  <div class='text-center'>
    <a href='index.php'>atau Log in sebagai pengguna yang berbeda</a>
  </div>
  <div class='lockscreen-footer text-center'>
    Copyright &copy; 2018<br>
    All rights reserved
  </div>
</div>
<!-- /.center -->

<!-- jQuery 2.2.3 -->
<script src='../plugins/jQuery/jquery-2.2.3.min.js'></script>
<!-- Bootstrap 3.3.6 -->
<script src='../bootstrap/js/bootstrap.min.js'></script>
</body>
</html>
";

}
}
?>
