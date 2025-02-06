<?php
$getpassword=$conn->query("SELECT * FROM `asesor` WHERE `no_ktp`='$_SESSION[namauser]'");
$r=$getpassword->fetch_assoc();
	echo "<!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
        Ubah Kata Sandi (Password) Asesor
        <small>Ubah Data</small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li class='active'>Ubah Kata Sandi/Password</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class='content'>
      <div class='row'>
        <div class='col-xs-12'>
          <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Ubah Kata Sandi/Password Asesor dengan No. KTP/NIK $_SESSION[namauser]</h3>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>
          <form method='POST' action='modul/mod_password/aksi_password.php'>
          	<input type='hidden' name='username' value='$r[no_ktp]'>
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
				<input class='form-control' type='password' name='pass_baru'> minimal 8 karakter
			</div>
		  </div>
          
		  <div class='col-md-5 col-sm-5 col-xs-12'>
			<strong>Masukkan Lagi Password Baru</strong>
		  </div>
		  
		  <div class='col-md-7 col-sm-7 col-xs-12'>
			<div class='form-group'>				
				<input class='form-control' type='password' name='pass_ulangi'> minimal 8 karakter
			</div>
		  </div>
		  
		  <div class='col-md-12 col-sm-12 col-xs-12'>
			<input class='btn btn-primary' type=submit class='tombol' value='Proses'>&nbsp;&nbsp;&nbsp;
			<input class='btn btn-primary' type=button class='tombol' value='Batalkan' onclick=self.history.back()>
		  </div>
		  </form>
		  </div>
		  </div>
		</div>
	  </div>
	</section>
  </div>";
?>
