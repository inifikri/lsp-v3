<script>
function confirmdelete(delUrl) {
   if (confirm("Anda yakin ingin menghapus?")) {
      document.location = delUrl;
   }
}
</script>

<?php

//cek hak akses user
$cek=user_akses($_GET[module],$_SESSION[sessid]);
if($cek==1 OR $_SESSION[leveluser]=='admin'){

$base_url = $_SERVER['HTTP_HOST'];


$aksi="modul/mod_skema/aksi_skema.php";
switch($_GET[act]){
  // Tampil skema Kami
  default:
      
if( isset( $_REQUEST['tambahkan'] ))
	{
	$kode_skema=$_POST[kodeskema];
	$judul=$_POST[namaskema];
	$id_skkni=$_POST[skknilsp];
	$cekdu="SELECT * FROM `skema_kkni` WHERE `kode_skema`='$kode_skema' AND `id_skkni`='$id_skkni'";
	$result = $conn->query($cekdu);
	if ($result->num_rows == 0){
		$conn->query("INSERT INTO `skema_kkni`(`kode_skema`, `judul`, `id_skkni`) VALUES ('$kode_skema', '$judul', '$id_skkni')");
        echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Input Data Sukses</h4>
			Anda Telah Berhasil Input Data <b>Skema $nama</b></div>";

	}else{
		echo "<script>alert('Maaf Skema dengan Kode tersebut Sudah Ada');</script>";
	}
}
if( isset( $_REQUEST['hapuslsp'] ))
	{
	$cekdu="SELECT * FROM `skema_kkni` WHERE `id`='$_POST[iddellsp]'";
	$result = $conn->query($cekdu);
	if ($result->num_rows != 0){
		$conn->query("DELETE FROM `skema_kkni` WHERE `id`='$_POST[iddellsp]'");
        echo "<div class='alert alert-danger alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Hapus Data Sukses</h4>
			Anda Telah Berhasil Menghapus Data <b>Skema Sertifikasi</b></div>";

	}else{
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
              <h3 class='box-title'>Data Skema Sertifikasi Profesi</h3>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>
			
				<table id='example1' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Kode Skema</th><th>Nama Skema Sertifikasi</th><th>SKKNI</th><th>Aksi</th></tr></thead>
					<tbody>";
					$no=1;
					$sqllsp="SELECT * FROM `skema_kkni` ORDER BY `kode_skema` ASC";
					$lsp=$conn->query($sqllsp);
					while ($pm=$lsp->fetch_assoc()){
						$masa_berlaku=tgl_indo($pm[masa_berlaku]);
						echo "<tr class=gradeX><td>$no</td><td><b>$pm[kode_skema]</b></td><td>$pm[judul]</td>";
						$sqlgetskkni=$conn->query("SELECT * FROM `skkni` WHERE `id`='$pm[id_skkni]'");
						$ns=$sqlgetskkni->fetch_assoc();
						echo "</td><td>$ns[nama]</td>";
					    echo "<td><form name='frm' method='POST' role='form' enctype='multipart/form-data'>
							<input type='hidden' name='iddellsp' value='$pm[id]'><input type='submit' name='hapuslsp' class='btn btn-danger btn-xs' title='Hapus' value='Hapus'></form></td></tr>";
						$no++;
					}
				echo "</tbody></table>
			
			</div>
		  </div>
		  <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Input Data Skema Sertifikasi Profesi (LSP)</h3>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>
			
				<form name='frm' method='POST' role='form' enctype='multipart/form-data'>
					<div class='col-md-6'>
						<div class='form-group'>
							<label>Kode Skema</label>
							<input type='text' name='kodeskema' class='form-control' required>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
							<label>Nama Skema Profesi</label>
							<input type='text' name='namaskema' class='form-control' required>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
								<label>SKKNI LSP</label>
								<select name='skknilsp' class='form-control'>";
								$sqlskkni="SELECT * FROM `skkni` ORDER BY `nama` ASC";
								$skkni=$conn->query($sqlskkni);
								while ($sk=$skkni->fetch_assoc()){
									echo"<option value='$sk[id]'>$sk[nama]</option>";
								}
								echo"</select>
						</div>
					</div>
					<div class='col-md-12'>
						<div class='form-group'>
							<input type='submit'  name='tambahkan' class='btn btn-primary' value='Tambahkan'>
						</div>
					</div>
				</form>
			
			</div>
		  </div>
          
		</div>
	  </div>
	</section>";

    break;

   }
   }

?>
