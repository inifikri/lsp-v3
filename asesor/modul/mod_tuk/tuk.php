<script>
function confirmdelete(delUrl) {
   if (confirm("Anda yakin ingin menghapus?")) {
      document.location = delUrl;
   }
}
</script>

<?php
include "../../../config/koneksi.php";

//cek hak akses user
$cek=user_akses($_GET['module'],$_SESSION['password']);
if($cek==1 OR $_SESSION['leveluser']=='admin'){

$base_url = $_SERVER['HTTP_HOST'];


$aksi="modul/mod_tuk/aksi_tuk.php";
switch($_GET['act']){
  // Tampil tuk Kami
  default:
      
if( isset( $_REQUEST['tambahkan'] ))
	{
	$kode_tuk=$_POST['kodetuk'];
	$nama=$_POST['namatuk'];
	$penanggungjawab=$_POST['penjabtuk'];
	$jenis_tuk=$_POST['jenistuk'];
	$institusi_induk=$_POST['institusi_induk'];
	$alamat=$_POST['alamattuk'];
	$id_wilayah=$_POST['kecamatan'];
	$kodepos=$_POST['kodepos'];
	$telepon=$_POST['telepon'];
	$email=$_POST['email'];
	$fax=$_POST['fax'];
	$tgl_pendirian=$_POST['tgl_pendirian'];
	$no_lisensi=$_POST['no_lisensi'];
	$masa_berlaku=$_POST['masa_berlaku'];
	$id_skkni=$_POST['skknituk'];
	$cekdu="SELECT * FROM `tuk` WHERE `kode_tuk`='$kodetuk'";
	$result = $conn->query($cekdu);
	if ($result->num_rows == 0){
		$conn->query("INSERT INTO `tuk`(`kode_tuk`, `nama`, `penanggungjawab`, `jenis_tuk`, `institusi_induk`, `alamat`, `id_wilayah`, `kodepos`, `telepon`, `email`, `fax`, `tgl_pendirian`, `no_lisensi`, `masa_berlaku`, `id_skkni`) VALUES ('$kode_tuk', '$nama', '$penanggungjawab', '$jenis_tuk', '$institusi_induk', '$alamat', '$id_wilayah', '$kodepos', '$telepon', '$email', '$fax', '$tgl_pendirian', '$no_lisensi', '$masa_berlaku', '$id_skkni')");
        echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Input Data Sukses</h4>
			Anda Telah Berhasil Input Data <b>TUK $nama</b></div>";

	}else{
		echo "<script>alert('Maaf TUK dengan Kode Tersebut Sudah Ada');</script>";
	}
}
if( isset( $_REQUEST['hapustuk'] ))
	{
	$cekdu="SELECT * FROM `tuk` WHERE `id`='$_POST[iddeltuk]'";
	$result = $conn->query($cekdu);
	if ($result->num_rows != 0){
		$conn->query("DELETE FROM `tuk` WHERE `id`='$_POST[iddeltuk]'");
        echo "<div class='alert alert-danger alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Hapus Data Sukses</h4>
			Anda Telah Berhasil Menghapus Data <b>TUK</b></div>";

	}else{
		echo "<script>alert('Maaf TUK dengan ID Tersebut Tidak Ditemukan');</script>";
	}
}
    echo "
    <!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
        Tempat Uji Kompetensi (TUK)
        <small>Input Data</small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li class='active'>Data Lembaga Sertifikasi Profesi</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class='content'>
      <div class='row'>
        <div class='col-xs-12'>
          <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Data Tempat Uji Kompetensi Lembaga Sertifikasi Profesi</h3>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>
			
				<table id='example1' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Nama TUK</th><th>LSP Induk</th><th>No. Lisensi dan SKKNI</th><th>Penanggungjawab</th><th>Aksi</th></tr></thead>
					<tbody>";
					$no=1;
					$sqltuk="SELECT * FROM `tuk` ORDER BY `id` ASC";
					$tuk=$conn->query($sqltuk);
					while ($pm=$tuk->fetch_assoc()){
						$masa_berlaku=tgl_indo($pm[masa_berlaku]);
						echo "<tr class=gradeX><td>$no</td><td><b>$pm[nama]</b></td><td>$pm[jenis_tuk]</td>";
						$tglsekarang=date("Y-m-d");
						if ($pm[masa_berlaku]>=$tglsekarang){
							echo "<td><font color='green'><b>$pm[no_lisensi]</b> (Berlaku sd. <b>$masa_berlaku</b>)</font><br />";
						}else{
							echo "<td><font color='red'><b>$pm[no_lisensi]</b> (Telah Berakhir pada <b>$masa_berlaku</b>)</font><br />";
						}
						$namaskkni=$conn->query("SELECT * FROM `skkni` WHERE `id`='$pm[id_skkni]'");
						$nsk=$namaskkni->fetch_assoc();
						echo "$nsk[nama]";
						echo "</td><td>$pm[penanggungjawab]</td>";
					    echo "<td><form name='frm' method='POST' role='form' enctype='multipart/form-data'>
							<input type='hidden' name='iddeltuk' value='$pm[id]'><input type='submit' name='hapustuk' class='btn btn-danger btn-xs' title='Hapus' value='Hapus'></form></td></tr>";
						$no++;
					}
				echo "</tbody></table>
			
			</div>
		  </div>
		  <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Input Data Tempat Uji Kompetensi (TUK)</h3>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>
			
				<form name='frm' method='POST' role='form' enctype='multipart/form-data'>
					<div class='col-md-6'>
						<div class='form-group'>
							<label>Kode TUK</label>
							<input type='text' name='kodetuk' class='form-control'>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
							<label>Nama TUK</label>
							<input type='text' name='namatuk' class='form-control' required>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
							<label>Penanggungjawab TUK</label>
							<input type='text' name='penjabtuk' class='form-control' required>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
								<label>LSP Induk TUK</label>
								<select name='induktuk' class='form-control'>";
								$sqltukjenis="SELECT * FROM `lsp` ORDER BY `nama` ASC";
								$indukkat=$conn->query($sqltukjenis);
								while ($ik=$indukkat->fetch_assoc()){
									echo"<option value='$ik[id]'>$ik[nama] ($ik[jenis_lsp])</option>";
								}
								echo"</select>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
								<label>SKKNI TUK</label>
								<select name='skknituk' class='form-control'>";
								$sqlskkni="SELECT * FROM `skkni` ORDER BY `nama` ASC";
								$skkni=$conn->query($sqlskkni);
								while ($sk=$skkni->fetch_assoc()){
									echo"<option value='$sk[id]'>$sk[nama]</option>";
								}
								echo"</select>
						</div>
					</div>
					<div class='col-md-3'>
						<div class='form-group'>
							<label>Telepon</label>
							<input type='text' name='telepon' class='form-control'>
						</div>
					</div>
					<div class='col-md-3'>
						<div class='form-group'>
							<label>Faximile</label>
							<input type='text' name='fax' class='form-control'>
						</div>
					</div>
					<div class='col-md-12'>
						<div class='form-group'>
							<label>Alamat TUK</label>
							<input type='text' name='alamattuk' class='form-control'>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
							<label>Provinsi</label>
							<select name='propinsi' class='form-control' id='propinsi'>";
							$sql="SELECT * FROM  `data_wilayah` WHERE  id_level_wil='1' AND id_induk_wilayah!='NULL' ORDER BY id_wil ASC";
							$tampil=$conn->query($sql);
							while($rr=$tampil->fetch_assoc()){
								echo "<option value='$rr[id_wil]'>$rr[nm_wil]</option>";
							}
							echo"</select>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
							<label>Kota/Kabupaten</label>
							<select name='kota' class='form-control' id='kota'>";
							echo"<option value=''></option>";
							echo"</select>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
							<label>Kecamatan</label>
							<select name='kecamatan' class='form-control' id='kecamatan'>";
							echo"<option value=''></option>";
							echo"</select>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
							<label>Desa/Kelurahan</label>
							<input type='text' name='kelurahan' class='form-control'>
						</div>
					</div>
					<div class='col-md-3'>
						<div class='form-group'>
							<label>Nomor Lisensi/Ijin/SK</label>
							<input type='text' name='no_lisensi' class='form-control'>
						</div>
					</div>
					<div class='col-md-3'>
						<div class='form-group'>
							<label>Masa Berlaku Lisensi/Ijin/SK</label>
							<input type='date' name='masa_berlaku' class='form-control'>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
							<label>Institusi Induk</label>
							<input type='text' name='institusi_induk' class='form-control'>
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
