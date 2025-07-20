<?php
include "../config/koneksi.php";
include "../config/library.php";
include "../config/fungsi_indotgl.php";
include "../config/fungsi_combobox.php";
include "../config/class_paging.php";
include "../config/fungsi_rupiah.php";
include "../classes/class.phpmailer.php";

$sqlidentitas="SELECT * FROM `identitas`";
$identitas=$conn->query($sqlidentitas);
$iden=$identitas->fetch_assoc();
$urldomain=$iden['url_domain'];

// Bagian Home
if ($_GET['module']=='home'){
	if ($_SESSION['leveluser']=='pengusul'|| $_SESSION['leveluser']=='user'){
	$jumtuk=0;
	//Jumlah Skema Sertifikasi
	$sqljumskema="SELECT * FROM `skema_kkni`";
	$qjumskema=$conn->query($sqljumskema);
	$jumskema=$qjumskema->num_rows;
	//Jumlah TUK Sertifikasi
	$sqljumtuk="SELECT * FROM `tuk`";
	$qjumtuk=$conn->query($sqljumtuk);
	$jumtuk=$qjumtuk->num_rows;
	//Jumlah Asesor Sertifikasi
	$sqljumasesor="SELECT * FROM `asesor`";
	$qjumasesor=$conn->query($sqljumasesor);
	$jumasesor=$qjumasesor->num_rows;
	//Jumlah Asesor Sertifikasi
	$sqljumasesi="SELECT * FROM `asesi` WHERE `id_pengusul`='$_SESSION[namauser]'";
	$qjumasesi=$conn->query($sqljumasesi);
	$jumasesi=$qjumasesi->num_rows;

	$sqljumjadwal="SELECT * FROM `jadwal_asesmen`";
	$qjumjadwal=$conn->query($sqljumjadwal);
	$jumjadwal=$qjumjadwal->num_rows;

	while($event=$qjumjadwal->fetch_assoc()){
		$id_event=$event['nama_kegiatan']."-".$event['tgl_asesmen']."-".$event['tempat_asesmen'];
		$sqlevent="UPDATE `jadwal_asesmen` SET `id_event`='$id_event' WHERE `id`='$event[id]'";
		$conn->query($sqlevent);
	}

	$sqljumevent="SELECT DISTINCT `id_event` FROM `jadwal_asesmen`";
	$qjumevent=$conn->query($sqljumevent);
	$jumevent=$qjumevent->num_rows;

	$tampilperiode=date("m-Y");
	$tahun=date("Y");

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


<!-- Small boxes (Stat box) -->
      <div class='row'>
        <div class='col-lg-3 col-xs-6'>
          <!-- small box -->
          <div class='small-box bg-aqua'>
            <div class='inner'>
              <h3>$jumtuk</h3>

              <p>T.U.K. Tersedia</p>
            </div>
            <div class='icon'>
              <i class='fa fa-building'></i>
            </div>
            <a href='?module=tuk' class='small-box-footer'>Lihat data <i class='fa fa-arrow-circle-right'></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class='col-lg-3 col-xs-6'>
          <!-- small box -->
          <div class='small-box bg-blue'>
            <div class='inner'>
              <h3>$jumskema</h3>

              <p>Skema Sertifikasi Tersedia</p>
            </div>
            <div class='icon'>
              <i class='fa fa-users'></i>
            </div>
            <a href='?module=skema' class='small-box-footer'>Lihat data <i class='fa fa-arrow-circle-right'></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class='col-lg-3 col-xs-6'>
          <!-- small box -->
          <div class='small-box bg-yellow'>
            <div class='inner'>
              <h3>$jumasesi</h3>

              <p>Asesi/ Calon Asesi Anda Usulkan</p>
            </div>
            <div class='icon'>
              <i class='fa fa-user-plus'></i>
            </div>
            <a href='?module=asesi' class='small-box-footer'>Lihat data <i class='fa fa-arrow-circle-right'></i></a>
          </div>
        </div>
        <!-- ./col -->
	<div class='col-lg-3 col-xs-6'>
          <!-- small box -->
          <div class='small-box bg-maroon'>
            <div class='inner'>
              <h3>$jumjadwal</h3>

              <p>Jadwal Asesmen Tersedia</p>
            </div>
            <div class='icon'>
              <i class='fa fa-calendar-check-o'></i>
            </div>
            <a href='?module=jadwalasesmen' class='small-box-footer'>Lihat data <i class='fa fa-arrow-circle-right'></i></a>
          </div>
        </div>
        <!-- ./col -->


      </div>
      <!-- /.row -->

<div class='row'>
        <div class='col-md-6'>
          <!-- AREA CHART -->
          <div class='box box-primary'>
            <div class='box-header with-border'>
              <h3 class='box-title'>Asesi yang Diusulkan Tahun $tahun</h3>

              <div class='box-tools pull-right'>
                <button type='button' class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i>
                </button>
                <button type='button' class='btn btn-box-tool' data-widget='remove'><i class='fa fa-times'></i></button>
              </div>
            </div>
            <div class='box-body'>
              <div class='chart'>
<canvas id='line-chart' width='800' height='450'></canvas>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->


	  <!-- DONUT CHART -->
          <div class='box box-warning'>
            <div class='box-header with-border'>
              <h3 class='box-title'>Jenis Kelamin Asesi yang Diusulkan</h3>

              <div class='box-tools pull-right'>
                <button type='button' class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i>
                </button>
                <button type='button' class='btn btn-box-tool' data-widget='remove'><i class='fa fa-times'></i></button>
              </div>
            </div>
            <div class='box-body'>
              <div class='chart'>
<canvas id='pie-chart' width='800' height='450'></canvas>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

        </div>
        <!-- /.col (LEFT) -->
        <div class='col-md-6'>
          <div class='box box-info'>
            <div class='box-header with-border'>
              <h3 class='box-title'>Kemajuan Proses Administratif</h3>

              <div class='box-tools pull-right'>
                <button type='button' class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i>
                </button>
                <button type='button' class='btn btn-box-tool' data-widget='remove'><i class='fa fa-times'></i></button>
              </div>
            </div>
            <div class='box-body'>

            	  <p class='text-center'>
                    <strong>Prosentase Proses Asesmen</strong>
                  </p>";
		  	$sqlnumkona="SELECT DISTINCT `id_asesi` FROM `asesi_doc` AND `id_pengusul`='$_SESSION[namauser]'";
			$numkona0=$conn->query($sqlnumkona);
			$numkona=$numkona0->num_rows;
			$sqlnumkon2a="SELECT * FROM `asesi` AND `id_pengusul`='$_SESSION[namauser]'";
			$numkon2a0=$conn->query($sqlnumkon2a);
		  	$numkon2a=$numkon2a0->num_rows;
		  	
		  	if ($numkon2a!=0){
		  	    $persentasea=round(($numkona/$numkon2a)*100);
		  	}else{
		  	    $persentasea=0;
		  	}
                  echo"<div class='progress-group'>
                    <span class='progress-text'>Data Asesi telah melengkapi berkas</span>
                    <span class='progress-number'><b>$persentasea %</b> ($numkona/$numkon2a*)</span>

                    <div class='progress sm'>
                      <div class='progress-bar progress-bar-aqua' style='width: $persentasea%'></div>
                    </div>
                  </div>";
		  	$sqlnumkon="SELECT * FROM `asesi_asesmen` WHERE `tgl_asesmen`!='0000-00-00' AND `id_pengusul`='$_SESSION[namauser]'";
			$numkon0=$conn->query($sqlnumkon);
			$numkon=$numkon0->num_rows;
			$sqlnumkon2="SELECT * FROM `asesi_asesmen` AND `id_pengusul`='$_SESSION[namauser]'";
			$numkon20=$conn->query($sqlnumkon2);
		  	$numkon2=$numkon20->num_rows;
		  	
		        if ($numkon2!=0){
		            $persentase=round(($numkon/$numkon2)*100);
		        }else{
		            $persentase=0;
		        }
                  echo"<div class='progress-group'>
                    <span class='progress-text'>Data asesmen diproses (terjadwal)</span>
                    <span class='progress-number'><b>$persentase %</b> ($numkon/$numkon2)</span>

                    <div class='progress sm'>
                      <div class='progress-bar progress-bar-blue' style='width: $persentase%'></div>
                    </div>
                  </div>

            	  <p class='text-center'>
                    <strong>Prosentase Hasil Asesmen</strong>
                  </p>";
			//if($_SESSION['leveluser']=='pengusul'){
				$sqlgetkompeten2="SELECT * FROM `asesi_asesmen` WHERE `status_asesmen`='K' AND `id_pengusul`='$_SESSION[namauser]'";
				$getkompeten2=$conn->query($sqlgetkompeten2);
				$numkonb=$getkompeten2->num_rows;

				$sqlgetkompeten="SELECT * FROM `asesi_asesmen` AND `id_pengusul`='$_SESSION[namauser]'";
				$getkompeten=$conn->query($sqlgetkompeten);
				$numkon2b=$getkompeten->num_rows;


		           //$numkonb=mysql_num_rows(mysql_query("SELECT DISTINCT `NO_TEST` FROM `logexport`"));
		           //$numkon2b=mysql_num_rows(mysql_query("SELECT DISTINCT `NIM` FROM `konversimktemp`"));
			//}else{
		           //$numkonb=mysql_num_rows(mysql_query("SELECT DISTINCT `NO_TEST` FROM `logexport` WHERE `posisi`!='admin' AND `kodept`='$_SESSION[kodept]' AND `kodeprodi`='$_SESSION[kodeprodi]'"));
		           //$numkon2b=mysql_num_rows(mysql_query("SELECT DISTINCT `NIM` FROM `konversimktemp` WHERE `kode_pt`='$_SESSION[kodept]'"));
			//}
		           if ($numkon2b!=0){
		               $persentaseb=round(($numkonb/$numkon2b)*100);
		           }else{
		               $persentaseb=0;
		           }
                  echo"<div class='progress-group'>
                    <span class='progress-text'>Data Asesmen dinyatakan Kompeten</span>
                    <span class='progress-number'><b>".$persentaseb." %</b> (".$numkonb."/".$numkon2b.")</span>

                    <div class='progress sm'>
                      <div class='progress-bar progress-bar-green' style='width: ".$persentaseb."%'></div>
                    </div>
                  </div>";
			//if($_SESSION['leveluser']=='pengusul'){
				$sqlgetkompeten3="SELECT * FROM `asesi_asesmen` WHERE `status_asesmen`='BK' AND `id_pengusul`='$_SESSION[namauser]'";
				$getkompeten3=$conn->query($sqlgetkompeten3);
				$numkonc=$getkompeten3->num_rows;

				$sqlgetkompeten4="SELECT * FROM `asesi_asesmen` AND `id_pengusul`='$_SESSION[namauser]'";
				$getkompeten4=$conn->query($sqlgetkompeten4);
				$numkon2c=$getkompeten4->num_rows;

		           //$numkonc=mysql_num_rows(mysql_query("SELECT DISTINCT `NO_TEST` FROM `logcetak`"));
		           //$numkon2c=mysql_num_rows(mysql_query("SELECT DISTINCT `NIM` FROM `konversimktemp`"));
			//}else{
		           //$numkonc=mysql_num_rows(mysql_query("SELECT DISTINCT `NO_TEST` FROM `logcetak` WHERE `posisi`!='admin' AND `kodept`='$_SESSION[kodept]' AND `kodeprodi`='$_SESSION[kodeprodi]'"));
		           //$numkon2c=mysql_num_rows(mysql_query("SELECT DISTINCT `NIM` FROM `konversimktemp` WHERE `kode_pt`='$_SESSION[kodept]'"));
			//}
		           if ($numkon2c!=0){
		               $persentasec=round(($numkonc/$numkon2c)*100);
		           }else{
		               $persentasec=0;
		           }
                  echo"<div class='progress-group'>
                    <span class='progress-text'>Data Asesmen dinyatakan Belum Kompeten</span>
                    <span class='progress-number'><b>$persentasec %</b> ($numkonc/$numkon2c)</span>

                    <div class='progress sm'>
                      <div class='progress-bar progress-bar-red' style='width: $persentasec%'></div>
                    </div>
                  </div>
		  <p class='text-muted'>*) Data Asesi < Data asesmen, karena dimungkinkan asesi ikut lebih dari 1 skema</p>
            </div>
            <!-- /.box-body -->

          </div>
          <!-- /.box -->

          <!-- BAR CHART -->
          <div class='box box-success'>
            <div class='box-header with-border'>
              <h3 class='box-title'>Kelompok Usia Asesi yang Diusulkan</h3>

              <div class='box-tools pull-right'>
                <button type='button' class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i>
                </button>
                <button type='button' class='btn btn-box-tool' data-widget='remove'><i class='fa fa-times'></i></button>
              </div>
            </div>
            <div class='box-body'>
              <div class='chart'>
<canvas id='bar-chart' width='800' height='450'></canvas>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

        </div>
        <!-- /.col (RIGHT) -->
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


//=============================hitung untuk grafik=======================================


$periode01=$tahun."01";
$periode02=$tahun."02";
$periode03=$tahun."03";
$periode04=$tahun."04";
$periode05=$tahun."05";
$periode06=$tahun."06";
$periode07=$tahun."07";
$periode08=$tahun."08";
$periode09=$tahun."09";
$periode10=$tahun."10";
$periode11=$tahun."11";
$periode12=$tahun."12";

$periode01b=$tahun."-01";
$periode02b=$tahun."-02";
$periode03b=$tahun."-03";
$periode04b=$tahun."-04";
$periode05b=$tahun."-05";
$periode06b=$tahun."-06";
$periode07b=$tahun."-07";
$periode08b=$tahun."-08";
$periode09b=$tahun."-09";
$periode10b=$tahun."-10";
$periode11b=$tahun."-11";
$periode12b=$tahun."-12";


	$sqljumkandidat01="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode01%' AND `id_pengusul`='$_SESSION[namauser]'";
	$sqljumkandidat02="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode02%' AND `id_pengusul`='$_SESSION[namauser]'";
	$sqljumkandidat03="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode03%' AND `id_pengusul`='$_SESSION[namauser]'";
	$sqljumkandidat04="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode04%' AND `id_pengusul`='$_SESSION[namauser]'";
	$sqljumkandidat05="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode05%' AND `id_pengusul`='$_SESSION[namauser]'";
	$sqljumkandidat06="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode06%' AND `id_pengusul`='$_SESSION[namauser]'";
	$sqljumkandidat07="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode07%' AND `id_pengusul`='$_SESSION[namauser]'";
	$sqljumkandidat08="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode08%' AND `id_pengusul`='$_SESSION[namauser]'";
	$sqljumkandidat09="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode09%' AND `id_pengusul`='$_SESSION[namauser]'";
	$sqljumkandidat10="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode10%' AND `id_pengusul`='$_SESSION[namauser]'";
	$sqljumkandidat11="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode11%' AND `id_pengusul`='$_SESSION[namauser]'";
	$sqljumkandidat12="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode12%' AND `id_pengusul`='$_SESSION[namauser]'";

	// data terverifikasi

	$sqljumkandidat01b="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode01%' AND `verifikasi`='V' AND `id_pengusul`='$_SESSION[namauser]'";
	$sqljumkandidat02b="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode02%' AND `verifikasi`='V' AND `id_pengusul`='$_SESSION[namauser]'";
	$sqljumkandidat03b="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode03%' AND `verifikasi`='V' AND `id_pengusul`='$_SESSION[namauser]'";
	$sqljumkandidat04b="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode04%' AND `verifikasi`='V' AND `id_pengusul`='$_SESSION[namauser]'";
	$sqljumkandidat05b="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode05%' AND `verifikasi`='V' AND `id_pengusul`='$_SESSION[namauser]'";
	$sqljumkandidat06b="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode06%' AND `verifikasi`='V' AND `id_pengusul`='$_SESSION[namauser]'";
	$sqljumkandidat07b="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode07%' AND `verifikasi`='V' AND `id_pengusul`='$_SESSION[namauser]'";
	$sqljumkandidat08b="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode08%' AND `verifikasi`='V' AND `id_pengusul`='$_SESSION[namauser]'";
	$sqljumkandidat09b="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode09%' AND `verifikasi`='V' AND `id_pengusul`='$_SESSION[namauser]'";
	$sqljumkandidat10b="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode10%' AND `verifikasi`='V' AND `id_pengusul`='$_SESSION[namauser]'";
	$sqljumkandidat11b="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode11%' AND `verifikasi`='V' AND `id_pengusul`='$_SESSION[namauser]'";
	$sqljumkandidat12b="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode12%' AND `verifikasi`='V' AND `id_pengusul`='$_SESSION[namauser]'";

	// data terjadwal

	$sqljumterjadwal01b="SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`!='' AND `tgl_asesmen` LIKE '$periode01b%' AND `id_pengusul`='$_SESSION[namauser]'";
	$sqljumterjadwal02b="SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`!='' AND `tgl_asesmen` LIKE '$periode02b%' AND `id_pengusul`='$_SESSION[namauser]'";
	$sqljumterjadwal03b="SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`!='' AND `tgl_asesmen` LIKE '$periode03b%' AND `id_pengusul`='$_SESSION[namauser]'";
	$sqljumterjadwal04b="SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`!='' AND `tgl_asesmen` LIKE '$periode04b%' AND `id_pengusul`='$_SESSION[namauser]'";
	$sqljumterjadwal05b="SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`!='' AND `tgl_asesmen` LIKE '$periode05b%' AND `id_pengusul`='$_SESSION[namauser]'";
	$sqljumterjadwal06b="SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`!='' AND `tgl_asesmen` LIKE '$periode06b%' AND `id_pengusul`='$_SESSION[namauser]'";
	$sqljumterjadwal07b="SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`!='' AND `tgl_asesmen` LIKE '$periode07b%' AND `id_pengusul`='$_SESSION[namauser]'";
	$sqljumterjadwal08b="SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`!='' AND `tgl_asesmen` LIKE '$periode08b%' AND `id_pengusul`='$_SESSION[namauser]'";
	$sqljumterjadwal09b="SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`!='' AND `tgl_asesmen` LIKE '$periode09b%' AND `id_pengusul`='$_SESSION[namauser]'";
	$sqljumterjadwal10b="SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`!='' AND `tgl_asesmen` LIKE '$periode10b%' AND `id_pengusul`='$_SESSION[namauser]'";
	$sqljumterjadwal11b="SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`!='' AND `tgl_asesmen` LIKE '$periode11b%' AND `id_pengusul`='$_SESSION[namauser]'";
	$sqljumterjadwal12b="SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`!='' AND `tgl_asesmen` LIKE '$periode12b%' AND `id_pengusul`='$_SESSION[namauser]'";



$jumkandidat01=$conn->query($sqljumkandidat01);
$kandidat01=$jumkandidat01->num_rows;

$jumkandidat02=$conn->query($sqljumkandidat02);
$kandidat02=$jumkandidat02->num_rows;

$jumkandidat03=$conn->query($sqljumkandidat03);
$kandidat03=$jumkandidat03->num_rows;

$jumkandidat04=$conn->query($sqljumkandidat04);
$kandidat04=$jumkandidat04->num_rows;

$jumkandidat05=$conn->query($sqljumkandidat05);
$kandidat05=$jumkandidat05->num_rows;

$jumkandidat06=$conn->query($sqljumkandidat06);
$kandidat06=$jumkandidat06->num_rows;

$jumkandidat07=$conn->query($sqljumkandidat07);
$kandidat07=$jumkandidat07->num_rows;

$jumkandidat08=$conn->query($sqljumkandidat08);
$kandidat08=$jumkandidat08->num_rows;

$jumkandidat09=$conn->query($sqljumkandidat09);
$kandidat09=$jumkandidat09->num_rows;

$jumkandidat10=$conn->query($sqljumkandidat10);
$kandidat10=$jumkandidat10->num_rows;

$jumkandidat11=$conn->query($sqljumkandidat11);
$kandidat11=$jumkandidat11->num_rows;

$jumkandidat12=$conn->query($sqljumkandidat12);
$kandidat12=$jumkandidat12->num_rows;


// Terverifikasi
$jumkandidat01b=$conn->query($sqljumkandidat01b);
$kandidat01b=$jumkandidat01b->num_rows;

$jumkandidat02b=$conn->query($sqljumkandidat02b);
$kandidat02b=$jumkandidat02b->num_rows;

$jumkandidat03b=$conn->query($sqljumkandidat03b);
$kandidat03b=$jumkandidat03b->num_rows;

$jumkandidat04b=$conn->query($sqljumkandidat04b);
$kandidat04b=$jumkandidat04b->num_rows;

$jumkandidat05b=$conn->query($sqljumkandidat05b);
$kandidat05b=$jumkandidat05b->num_rows;

$jumkandidat06b=$conn->query($sqljumkandidat06b);
$kandidat06b=$jumkandidat06b->num_rows;

$jumkandidat07b=$conn->query($sqljumkandidat07b);
$kandidat07b=$jumkandidat07b->num_rows;

$jumkandidat08b=$conn->query($sqljumkandidat08b);
$kandidat08b=$jumkandidat08b->num_rows;

$jumkandidat09b=$conn->query($sqljumkandidat09b);
$kandidat09b=$jumkandidat09b->num_rows;

$jumkandidat10b=$conn->query($sqljumkandidat10b);
$kandidat10b=$jumkandidat10b->num_rows;

$jumkandidat11b=$conn->query($sqljumkandidat11b);
$kandidat11b=$jumkandidat11b->num_rows;

$jumkandidat12b=$conn->query($sqljumkandidat12b);
$kandidat12b=$jumkandidat12b->num_rows;


// Terjadwal
$jumterjadwal01b=$conn->query($sqljumterjadwal01b);
$terjadwal01b=$jumterjadwal01b->num_rows;

$jumterjadwal02b=$conn->query($sqljumterjadwal02b);
$terjadwal02b=$jumterjadwal02b->num_rows;

$jumterjadwal03b=$conn->query($sqljumterjadwal03b);
$terjadwal03b=$jumterjadwal03b->num_rows;

$jumterjadwal04b=$conn->query($sqljumterjadwal04b);
$terjadwal04b=$jumterjadwal04b->num_rows;

$jumterjadwal05b=$conn->query($sqljumterjadwal05b);
$terjadwal05b=$jumterjadwal05b->num_rows;

$jumterjadwal06b=$conn->query($sqljumterjadwal06b);
$terjadwal06b=$jumterjadwal06b->num_rows;

$jumterjadwal07b=$conn->query($sqljumterjadwal07b);
$terjadwal07b=$jumterjadwal07b->num_rows;

$jumterjadwal08b=$conn->query($sqljumterjadwal08b);
$terjadwal08b=$jumterjadwal08b->num_rows;

$jumterjadwal09b=$conn->query($sqljumterjadwal09b);
$terjadwal09b=$jumterjadwal09b->num_rows;

$jumterjadwal10b=$conn->query($sqljumterjadwal10b);
$terjadwal10b=$jumterjadwal10b->num_rows;

$jumterjadwal11b=$conn->query($sqljumterjadwal11b);
$terjadwal11b=$jumterjadwal11b->num_rows;

$jumterjadwal12b=$conn->query($sqljumterjadwal12b);
$terjadwal12b=$jumterjadwal12b->num_rows;

//=====================data jenis kelamin================================================

$sqljeniskelaminl="SELECT * FROM `asesi` WHERE `jenis_kelamin`='L' AND `id_pengusul`='$_SESSION[namauser]'";
$jeniskelaminl=$conn->query($sqljeniskelaminl);
$jumlaki=$jeniskelaminl->num_rows;
$sqljeniskelaminp="SELECT * FROM `asesi` WHERE `jenis_kelamin`='P' AND `id_pengusul`='$_SESSION[namauser]'";
$jeniskelaminp=$conn->query($sqljeniskelaminp);
$jumperempuan=$jeniskelaminp->num_rows;
$sqljeniskelaminu="SELECT * FROM `asesi` WHERE `jenis_kelamin`='' AND `id_pengusul`='$_SESSION[namauser]'";
$jeniskelaminu=$conn->query($sqljeniskelaminu);
$jumnotlp=$jeniskelaminu->num_rows;


//=====================data jenis kelamin Asesor ========================================

$sqljeniskelaminlasr="SELECT * FROM `asesor` WHERE `jenis_kelamin`='L'";
$jeniskelaminlasr=$conn->query($sqljeniskelaminlasr);
$jumlakiasr=$jeniskelaminlasr->num_rows;
$sqljeniskelaminpasr="SELECT * FROM `asesor` WHERE `jenis_kelamin`='P'";
$jeniskelaminpasr=$conn->query($sqljeniskelaminpasr);
$jumperempuanasr=$jeniskelaminpasr->num_rows;
$sqljeniskelaminuasr="SELECT * FROM `asesor` WHERE `jenis_kelamin`=''";
$jeniskelaminuasr=$conn->query($sqljeniskelaminuasr);
$jumnotlpasr=$jeniskelaminuasr->num_rows;


//=====================data usia=========================================================
function getAge($date) { // Y-m-d format
	$now = explode("-", date('Y-m-d'));
	$dob = explode("-", $date);
	$dif = $now[0] - $dob[0];
	if ($dob[1] > $now[1]) { // birthday month has not hit this year
		$dif -= 1;
	}
	elseif ($dob[1] == $now[1]) { // birthday month is this month, check day
	if ($dob[2] > $now[2]) {
		$dif -= 1;
	}
	elseif ($dob[2] == $now[2]) { // Happy Birthday!
		$dif = $dif." Happy Birthday!";
	};
	};
	return $dif;
}
$sqlkalkulasiusia="SELECT * FROM `asesi`";
$kalkulasiusia=$conn->query($sqlkalkulasiusia);
while ($kus=$kalkulasiusia->fetch_assoc()){
	$usia=getAge($kus['tgl_lahir']);
	$sqlupdateusia="UPDATE `asesi` SET `usia`='$usia' WHERE `no_pendaftaran`='$kus[no_pendaftaran]'";
	$updateusia=$conn->query($sqlupdateusia);
}

//---------------------------------------------------
$sqlgetusia1="SELECT * FROM `asesi` WHERE `usia` BETWEEN 15.5 AND 21.5 AND `id_pengusul`='$_SESSION[namauser]'";
$getusia1=$conn->query($sqlgetusia1);
$jumusia1=$getusia1->num_rows;
$sqlgetusia2="SELECT * FROM `asesi` WHERE `usia` BETWEEN 21.5 AND 26.5 AND `id_pengusul`='$_SESSION[namauser]'";
$getusia2=$conn->query($sqlgetusia2);
$jumusia2=$getusia2->num_rows;
$sqlgetusia3="SELECT * FROM `asesi` WHERE `usia` BETWEEN 26.5 AND 32.5 AND `id_pengusul`='$_SESSION[namauser]'";
$getusia3=$conn->query($sqlgetusia3);
$jumusia3=$getusia3->num_rows;
$sqlgetusia4="SELECT * FROM `asesi` WHERE `usia` BETWEEN 32.5 AND 38.5 AND `id_pengusul`='$_SESSION[namauser]'";
$getusia4=$conn->query($sqlgetusia4);
$jumusia4=$getusia4->num_rows;
$sqlgetusia5="SELECT * FROM `asesi` WHERE `usia` BETWEEN 38.5 AND 44.5 AND `id_pengusul`='$_SESSION[namauser]'";
$getusia5=$conn->query($sqlgetusia5);
$jumusia5=$getusia5->num_rows;
$sqlgetusia6="SELECT * FROM `asesi` WHERE `usia` BETWEEN 44.5 AND 50.5 AND `id_pengusul`='$_SESSION[namauser]'";
$getusia6=$conn->query($sqlgetusia6);
$jumusia6=$getusia6->num_rows;
$sqlgetusia7="SELECT * FROM `asesi` WHERE `usia` BETWEEN 50.5 AND 56.5 AND `id_pengusul`='$_SESSION[namauser]'";
$getusia7=$conn->query($sqlgetusia7);
$jumusia7=$getusia7->num_rows;
$sqlgetusia8="SELECT * FROM `asesi` WHERE `usia` BETWEEN 56.5 AND 62.5 AND `id_pengusul`='$_SESSION[namauser]'";
$getusia8=$conn->query($sqlgetusia8);
$jumusia8=$getusia8->num_rows;
$sqlgetusia9="SELECT * FROM `asesi` WHERE `usia` BETWEEN 62.5 AND 68.5 AND `id_pengusul`='$_SESSION[namauser]'";
$getusia9=$conn->query($sqlgetusia9);
$jumusia9=$getusia9->num_rows;
$sqlgetusia10="SELECT * FROM `asesi` WHERE `usia` BETWEEN 68.5 AND 74.5 AND `id_pengusul`='$_SESSION[namauser]'";
$getusia10=$conn->query($sqlgetusia10);
$jumusia10=$getusia10->num_rows;
$sqlgetusia11="SELECT * FROM `asesi` WHERE `usia` > 74.5 AND `id_pengusul`='$_SESSION[namauser]'";
$getusia11=$conn->query($sqlgetusia11);
$jumusia11=$getusia11->num_rows;
//=======================================================================================

//===========================usia asesor=================================================
$sqlkalkulasiusiaasr="SELECT * FROM `asesor`";
$kalkulasiusiaasr=$conn->query($sqlkalkulasiusiaasr);
while ($kusasr=$kalkulasiusiaasr->fetch_assoc()){
	$usiaasr=getAge($kusasr['tgl_lahir']);
	$sqlupdateusiaasr="UPDATE `asesor` SET `usia`='$usiaasr' WHERE `id`='$kusasr[id]'";
	$updateusiaasr=$conn->query($sqlupdateusiaasr);
}

//---------------------------------------------------
$sqlgetusia1asr="SELECT * FROM `asesor` WHERE `usia` BETWEEN 22.5 AND 39.5";
$getusia1asr=$conn->query($sqlgetusia1asr);
$jumusia1asr=$getusia1asr->num_rows;
$sqlgetusia2asr="SELECT * FROM `asesor` WHERE `usia` BETWEEN 39.5 AND 36.5";
$getusia2asr=$conn->query($sqlgetusia2asr);
$jumusia2asr=$getusia2asr->num_rows;
$sqlgetusia3asr="SELECT * FROM `asesor` WHERE `usia` BETWEEN 36.5 AND 43.5";
$getusia3asr=$conn->query($sqlgetusia3asr);
$jumusia3asr=$getusia3asr->num_rows;
$sqlgetusia4asr="SELECT * FROM `asesor` WHERE `usia` BETWEEN 43.5 AND 50.5";
$getusia4asr=$conn->query($sqlgetusia4asr);
$jumusia4asr=$getusia4asr->num_rows;
$sqlgetusia5asr="SELECT * FROM `asesor` WHERE `usia` BETWEEN 50.5 AND 57.5";
$getusia5asr=$conn->query($sqlgetusia5asr);
$jumusia5asr=$getusia5asr->num_rows;
$sqlgetusia6asr="SELECT * FROM `asesor` WHERE `usia` > 57.5";
$getusia6asr=$conn->query($sqlgetusia6asr);
$jumusia6asr=$getusia6asr->num_rows;
//=======================================================================================

  }
}

// Bagian User ===============================================================================================================
elseif ($_GET['module']=='user'){
  if ($_SESSION['leveluser']=='pengusul' OR $_SESSION[leveluser]=='user'){
    include "modul/mod_users/users.php";
  }
}

// Bagian Password User ======================================================================================================
elseif ($_GET['module']=='password'){
  if ($_SESSION['leveluser']=='pengusul' OR $_SESSION[leveluser]=='user'){
    include "modul/mod_password/password.php";
  }
}

// Bagian Modul ==============================================================================================================
elseif ($_GET['module']=='modul'){
   if ($_SESSION['leveluser']=='pengusul' OR $_SESSION['leveluser']=='user'){
    include "modul/mod_modul/modul.php";
  }
}

// Bagian TUK ================================================================================================================
elseif ($_GET['module']=='tuk'){
  if ($_SESSION['leveluser']=='pengusul'|| $_SESSION['leveluser']=='user'){
    echo "
    <!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
        Tempat Uji Kompetensi (TUK)
        <small>Data</small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li class='active'>Tempat Uji Kompetensi (TUK) Lembaga Sertifikasi Profesi</li>
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
			<div style='overflow-x:auto;'>
				<table id='example1' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Nama TUK</th><th>LSP Induk</th><th>No. Lisensi dan SKKNI</th><th>Penanggungjawab</th></tr></thead>
					<tbody>";
					$no=1;
					$sqltuk="SELECT * FROM `tuk` ORDER BY `id` ASC";
					$tuk=$conn->query($sqltuk);
					while ($pm=$tuk->fetch_assoc()){
						$masa_berlaku=tgl_indo($pm[masa_berlaku]);
						$sqltukjenis1="SELECT * FROM `tuk_jenis` WHERE `id`='$pm[jenis_tuk]'";
						$jenistuk=$conn->query($sqltukjenis1);
						$jt=$jenistuk->fetch_assoc();
						$sqllspinduk="SELECT * FROM `lsp` WHERE `id`='$pm[lsp_induk]'";
						$lspinduk=$conn->query($sqllspinduk);
						$li=$lspinduk->fetch_assoc();
						$sqlcekterjadwal="SELECT * FROM `jadwal_asesmen` WHERE `tempat_asesmen`='$pm[id]'";
						$cekterjadwal=$conn->query($sqlcekterjadwal);
						$jumjadwal=$cekterjadwal->num_rows;
						echo "<tr class=gradeX><td>$no</td><td>$pm[kode_tuk]<br><b>$pm[nama]</b><br>$jt[jenis_tuk]<br>Terjadwal $jumjadwal Asesmen</td><td>$li[nama] ($li[jenis_lsp])</td>";
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
					    	echo "</tr>";
						$no++;
					}
				echo "</tbody></table>
			</div>
			</div>
		  </div>
		</div>
	  </div>
	</section>";
	}
}

// Bagian Skema LSP ==========================================================================================================
elseif ($_GET['module']=='skema'){
  if ($_SESSION['leveluser']=='pengusul'|| $_SESSION['leveluser']=='user'){
    echo "
    <!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
        Skema Sertifikasi Profesi
        <small>Data</small>
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
			<div style='overflow-x:auto;'>
				<table id='example1' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Kode Skema</th><th>Nama Skema Sertifikasi</th><th>SKK/SKKNI</th><th>Persyaratan</th><th>Aksi</th></tr></thead>
					<tbody>";
					$no=1;
					$sqllsp="SELECT * FROM `skema_kkni` ORDER BY `id` ASC, `kode_skema` ASC";
					$lsp=$conn->query($sqllsp);
					while ($pm=$lsp->fetch_assoc()){
						echo "<tr class=gradeX><td>$no</td><td><b>$pm[kode_skema]</b></td><td>$pm[judul]</td>";
						$sqlgetskkni=$conn->query("SELECT * FROM `skkni` WHERE `id`='$pm[id_skkni]'");
						$ns=$sqlgetskkni->fetch_assoc();
						echo "</td><td>$ns[nama]</td>";
						$sqlgetsyarat="SELECT * FROM `skema_persyaratan` WHERE `id_skemakkni`='$pm[id]'";
						$getsyarat=$conn->query($sqlgetsyarat);
						$numsyarat=$getsyarat->num_rows;
						if ($numsyarat==0){
							echo "<td><a href='?module=insyarat&id=$pm[id]' class='btn btn-primary btn-xs'>Belum Tersesia Persyaratan</a></td>";
						}else{
							echo "<td><b>( $numsyarat ) Persyaratan</b><br><a href='?module=insyarat&id=$pm[id]' class='btn btn-success btn-xs'>Lihat Persyaratan</a></td>";
						}
						echo "<td>";
						echo "</td></tr>";
						$no++;
					}
				echo "</tbody></table>
			</div>
			</div>
		  </div>
		</div>
	  </div>
	</section>";
  }
}

// Bagian Unit Kompetensi LSP ==========================================================================================================
elseif ($_GET['module']=='unitkompetensi'){
  if ($_SESSION['leveluser']=='pengusul'|| $_SESSION['leveluser']=='user'){
    echo "
    <!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
        Unit Kompetensi Sertifikasi Profesi
        <small>Data</small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li class='active'>Unit Kompetensi Sertifikasi</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class='content'>
      <div class='row'>
        <div class='col-xs-12'>
          <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Data Unit Kompetensi Sertifikasi Profesi</h3>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>
			<div style='overflow-x:auto;'>
				<table id='example1' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Unit Kompetensi</th><th>Skema KKNI Sertifikasi</th><th>Standar</th></tr></thead>
					<tbody>";
					$no=1;
					$sqllsp="SELECT * FROM `unit_kompetensi` ORDER BY `kode_unit` ASC";
					$lsp=$conn->query($sqllsp);
					while ($pm=$lsp->fetch_assoc()){
						echo "<tr class=gradeX><td>$no</td><td><b>$pm[kode_unit]</b><br>$pm[judul]<br>";
						$sqlelemenkompetensi="SELECT * FROM `elemen_kompetensi` WHERE `id_unitkompetensi`='$pm[id]'";
						$elemenkompetensi=$conn->query($sqlelemenkompetensi);
						$jumelemen=$elemenkompetensi->num_rows;
						if ($jumelemen==0){
							echo "<a href='?module=elemenkompetensi&iduk=$pm[id]' class='btn btn-primary btn-xs'>Input Elemen Kompetensi</a>";
						}else{
							echo "<a href='?module=elemenkompetensi&iduk=$pm[id]' class='btn btn-success btn-xs'>$jumelemen Elemen Kompetensi</a>";
							$jumlahkriteria=0;
							while ($el=$elemenkompetensi->fetch_assoc()){
								$sqlgetkriteria="SELECT * FROM `kriteria_unjukkerja` WHERE `id_elemenkompetensi`='$el[id]'";
								$getkriteria=$conn->query($sqlgetkriteria);
								$jumkrit=$getkriteria->num_rows;
								$jumlahkriteria=$jumlahkriteria+$jumkrit;
							}
							if ($jumlahkriteria==0){
								echo "<a href='?module=elemenkompetensi&iduk=$pm[id]' class='btn btn-danger btn-xs'>Kriteria Unjuk Kerja belum diinput</a></td>";

							}else{
								echo "<a href='?module=elemenkompetensi&iduk=$pm[id]' class='btn btn-success btn-xs'>$jumlahkriteria Kriteria Unjuk Kerja</a></td>";
							}

						}
						$sqlgetskkni=$conn->query("SELECT * FROM `skema_kkni` WHERE `id`='$pm[id_skemakkni]'");
						$ns=$sqlgetskkni->fetch_assoc();
						echo "</td><td><b>$ns[kode_skema]</b><br>$ns[judul]</td>";
						$sqlgetskkni2=$conn->query("SELECT * FROM `skkni` WHERE `id`='$ns[id_skkni]'");
						$ns2=$sqlgetskkni2->fetch_assoc();

					    	echo "<td>$ns2[nama]<br>($pm[jenis])";
						echo "</td></tr>";
						$no++;
					}
				echo "</tbody></table>
			</div>
			</div>
		  </div>
          
		</div>
	  </div>
	</section>";
  }
}

// Bagian Elemen Kompetensi LSP ==========================================================================================================
elseif ($_GET['module']=='elemenkompetensi'){
  if ($_SESSION['leveluser']=='pengusul'|| $_SESSION['leveluser']=='user'){

$sqlunitkompetensi="SELECT * FROM `unit_kompetensi` WHERE `id`='$_GET[iduk]'";
$unitkompetensi=$conn->query($sqlunitkompetensi);
$unk=$unitkompetensi->fetch_assoc();
$sqlskema="SELECT * FROM `skema_kkni` WHERE `id`='$unk[id_skemakkni]'";
$skema=$conn->query($sqlskema);
$sk=$skema->fetch_assoc();
    echo "
    <!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
        Elemen Kompetensi Sertifikasi Profesi
        <small>Data</small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li><a href='media.php?module=unitkompetensi'><i class='fa fa-cube'></i> Unit Kompetensi Sertifikasi</a></li>
        <li class='active'>Elemen Kompetensi</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class='content'>
      <div class='row'>
        <div class='col-xs-12'>
          <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Data Elemen Kompetensi <b>$unk[judul]</b></h3><br>
              <h4 class='box-title'>Skema Kompetensi <b>".ucwords(strtolower($sk['judul']))."</b></h4>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>
			
				<table id='example1' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Nama Elemen</th></tr></thead>
					<tbody>";
					$no=1;
					$sqllsp="SELECT * FROM `elemen_kompetensi` WHERE `id_unitkompetensi`='$_GET[iduk]' ORDER BY `id` ASC";
					$lsp=$conn->query($sqllsp);
					while ($pm=$lsp->fetch_assoc()){
						echo "<tr class=gradeX><td>$no</td><td><b>$pm[elemen_kompetensi]</b><br>";
						$sqlelemenkompetensi="SELECT * FROM `kriteria_unjukkerja` WHERE `id_elemenkompetensi`='$pm[id]'";
						$elemenkompetensi=$conn->query($sqlelemenkompetensi);
						$jumelemen=$elemenkompetensi->num_rows;
						if ($jumelemen==0){
							echo "<a href='?module=kriteriaelemen&ide=$pm[id]' class='btn btn-primary btn-xs'>Input Kriteria Unjuk Kerja Elemen Kompetensi</a></td>";
						}else{
							echo "<a href='?module=kriteriaelemen&ide=$pm[id]' class='btn btn-success btn-xs'>$jumelemen Kriteria Unjuk Kerja Elemen Kompetensi</a></td>";
						}

						echo "</td></tr>";
						$no++;
					}
				echo "</tbody></table>
			
			</div>
		  </div>
          
		</div>
	  </div>
	</section>";
  }
}

// Bagian Kriteria Elemen Kompetensi LSP ==========================================================================================================
elseif ($_GET['module']=='kriteriaelemen'){
  if ($_SESSION['leveluser']=='pengusul'|| $_SESSION['leveluser']=='user'){
$sqlelemenkompetensi="SELECT * FROM `elemen_kompetensi` WHERE `id`='$_GET[ide]'";
$elemenkompetensi=$conn->query($sqlelemenkompetensi);
$elm=$elemenkompetensi->fetch_assoc();
$sqlunitkompetensi="SELECT * FROM `unit_kompetensi` WHERE `id`='$elm[id_unitkompetensi]'";
$unitkompetensi=$conn->query($sqlunitkompetensi);
$unk=$unitkompetensi->fetch_assoc();
$sqlskema="SELECT * FROM `skema_kkni` WHERE `id`='$unk[id_skemakkni]'";
$skema=$conn->query($sqlskema);
$sk=$skema->fetch_assoc();
    echo "
    <!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
        Kriteria Elemen Kompetensi Sertifikasi
        <small>Input Data</small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li><a href='media.php?module=unitkompetensi'><i class='fa fa-cube'></i> Unit Kompetensi Sertifikasi</a></li>
        <li><a href='media.php?module=elemenkompetensi&iduk=$elm[id_unitkompetensi]'><i class='fa fa-cubes'></i> Elemen Kompetensi</a></li>
        <li class='active'>Kriteria Unjuk Kerja</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class='content'>
      <div class='row'>
        <div class='col-xs-12'>
          <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Kriteria Unjuk Kerja Elemen Kompetensi <b>$elm[elemen_kompetensi]</b></h3><br>
              <h4 class='box-title'>Elemen Kompetensi <b>$unk[judul]</b></h4><br>
              <h4 class='box-title'>Skema Kompetensi <b>".ucwords(strtolower($sk['judul']))."</b></h4>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>
			
				<table id='example1' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Kriteria Unjuk Kerja</th></tr></thead>
					<tbody>";
					$no=1;
					$sqllsp="SELECT * FROM `kriteria_unjukkerja` WHERE `id_elemenkompetensi`='$_GET[ide]' ORDER BY `id` ASC";
					$lsp=$conn->query($sqllsp);
					while ($pm=$lsp->fetch_assoc()){
						echo "<tr class=gradeX><td>$no</td><td><b>$pm[kriteria]</b></td></tr>";
						$no++;
					}
				echo "</tbody></table>
			
			</div>
		  </div>
          
		</div>
	  </div>
	</section>";
  }
}

// Bagian Rekening Bank LSP ==========================================================================================================
elseif ($_GET['module']=='rekening'){
  if ($_SESSION['leveluser']=='pengusul'|| $_SESSION['leveluser']=='user'){
    echo "
    <!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
        Rekening Bank Pembayaran Sertifikasi Profesi
        <small>Data</small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li class='active'>Data Rekening Bank Sertifikasi Profesi</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class='content'>
      <div class='row'>
        <div class='col-xs-12'>
          <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Data Rekening Bank Pembayaran Uji Kompetensi Sertifikasi Profesi</h3>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>
			
				<table id='example1' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Rekening Bank</th><th>Nama LSP</th></tr></thead>
					<tbody>";
					$no=1;
					$sqlrekbayar="SELECT * FROM `rekeningbayar` ORDER BY `bank` ASC, `norek` ASC";
					$rek=$conn->query($sqlrekbayar);
					while ($pm=$rek->fetch_assoc()){
						$sqllsp="SELECT * FROM `lsp` WHERE `id`='$pm[kode_lsp]'";
						$lsp=$conn->query($sqllsp);
						$ls=$lsp->fetch_assoc();
					    echo "<tr class=gradeX><td>$no</td><td><b>$pm[bank]</b><br>$pm[norek]<br>$pm[atasnama]</td><td>$ls[nama]</td></tr>";
						$no++;
					}
				echo "</tbody></table>
			
			</div>
		  </div>
          
		</div>
	  </div>
	</section>";
  }
}

// Bagian Biaya Uji Kompetensi LSP ==========================================================================================================
elseif ($_GET['module']=='biayauji'){
  if ($_SESSION['leveluser']=='pengusul'|| $_SESSION['leveluser']=='user'){
    echo "
    <!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
        Biaya Uji Kompetensi Sertifikasi Profesi
        <small>Data</small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li class='active'>Data Biaya Uji Kompetensi Sertifikasi Profesi</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class='content'>
      <div class='row'>
        <div class='col-xs-12'>
          <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Data Biaya Uji Kompetensi Sertifikasi Profesi</h3>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>
			
				<table id='example1' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Skema KKNI Sertifikasi</th><th>Jenis Biaya</th><th>Nominal Biaya</th></tr></thead>
					<tbody>";
					$no=1;
					$sqllsp="SELECT * FROM `biaya_sertifikasi` ORDER BY `id_lsp` ASC, `id_skemakkni` ASC, `id` ASC";
					$lsp=$conn->query($sqllsp);
					while ($pm=$lsp->fetch_assoc()){
						$sqlbiaya1="SELECT * FROM `lsp` WHERE `id`='$pm[id_lsp]'";
						$biaya1=$conn->query($sqlbiaya1);
						$bs1=$biaya1->fetch_assoc();
						$sqlbiaya2="SELECT * FROM `skkni` WHERE `id`='$pm[id_skkni]'";
						$biaya2=$conn->query($sqlbiaya2);
						$bs2=$biaya2->fetch_assoc();
						$sqlgetskkni=$conn->query("SELECT * FROM `skema_kkni` WHERE `id`='$pm[id_skemakkni]'");
						$ns=$sqlgetskkni->fetch_assoc();
						$sqlgetskkni2=$conn->query("SELECT * FROM `biaya_jenis` WHERE `id`='$pm[jenis_biaya]'");
						$ns2=$sqlgetskkni2->fetch_assoc();
						$nominaltampil="Rp. ".number_format($pm['nominal'],0,",",".");
					    echo "<tr class=gradeX><td>$no</td><td><b>$bs1[nama]</b><h6 class='text-blue'>$bs2[nama]</h6>$ns[judul] ($ns[kode_skema])</td><td>$ns2[jenis_biaya]</td><td>$nominaltampil</td></tr>";
						$no++;
					}
				echo "</tbody></table>
			
			</div>
		  </div>
          
		</div>
	  </div>
	</section>";
  }
}

// Bagian Input Syarat Skema LSP ==========================================================================================================
elseif ($_GET['module']=='insyarat'){
  if ($_SESSION['leveluser']=='pengusul'|| $_SESSION['leveluser']=='user'){
	$sqlgetskema="SELECT * FROM `skema_kkni` WHERE `id`='$_GET[id]'";
	$getskema=$conn->query($sqlgetskema);
	$gs=$getskema->fetch_assoc();
	echo "
    <!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
        Persyaratan Uji Kompetensi
        <small>Data</small>
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
          <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Persyaratan Skema Sertifikasi Profesi</h3>
		<h2><b>$gs[kode_skema]</b>- $gs[judul]</h2>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>
			
				<table id='example1' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Persyaratan</th></tr></thead>
					<tbody>";
					$no=1;
					$sqllsp="SELECT * FROM `skema_persyaratan` WHERE `id_skemakkni`='$gs[id]' ORDER BY `id` ASC";
					$lsp=$conn->query($sqllsp);
					while ($pm=$lsp->fetch_assoc()){
						echo "<tr class=gradeX><td>$no</td>";
						echo "</td><td>$pm[persyaratan]</td></tr>";
						$no++;
					}
				echo "</tbody></table>
			
			</div>
		  </div>
          
		</div>
	  </div>
	</section>";
  }
}

// Bagian Asesi LSP ==========================================================================================================
elseif ($_GET['module']=='asesi'){
  if ($_SESSION['leveluser']=='pengusul'|| $_SESSION['leveluser']=='user'){
    if( isset( $_REQUEST['plotasesor'] ))
	{

	}
    if( isset( $_REQUEST['blokir'] ))
	{
		$sqlblokir="UPDATE `asesi` SET `blokir`='Y' WHERE `id`='$_POST[idblokir]'";
		$conn->query($sqlblokir);
	}
    if( isset( $_REQUEST['bukablokir'] ))
	{
		$sqlblokir="UPDATE `asesi` SET `blokir`='N' WHERE `id`='$_POST[idblokir]'";
		$conn->query($sqlblokir);
	}
    if( isset( $_REQUEST['hapusasesi'] ))
	{
		$sqlblokir="DELETE FROM `asesi` WHERE `id`='$_POST[idhapus]'";
		$conn->query($sqlblokir);
	}
    echo "


    <!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
        Asesi Sertifikasi Profesi
        <small>Data Asesi</small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li class='active'>Data Asesi Sertifikasi Profesi</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class='content'>
      <div class='row'>
        <div class='col-xs-12'>
          <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Data Asesi Sertifikasi Profesi</h3>
			<!-- Buat sebuah tombol Cancel untuk kemabli ke halaman awal / view data -->
			<a href='media.php?module=tambahasesi' class='btn btn-primary pull-right'>Daftarkan Asesi</a>&nbsp;
			<a href='media.php?module=importasesilama' class='btn btn-success pull-right'>&nbsp;
				<span class='fa fa-upload'></span> Import Data Asesi Lama
			</a>

            </div>
            <!-- /.box-header -->
            <div class='box-body'>
   <div id='loading' class='col-xs-12 overlay'>
              <i class='fa fa-refresh fa-spin'></i>
   </div>

		  <!-- Custom Tabs -->
          <div class='nav-tabs-custom'>
            <ul class='nav nav-tabs bg-gray'>
              <li class='active'><a href='#ANGKATAN' data-toggle='tab'>Tahun Angkatan</a></li>
              <li><a href='#WILAYAH' data-toggle='tab'>Provinsi Asesi</a></li>
              <li><a href='#WILAYAH-KOTA' data-toggle='tab'>Kota/ Kabupaten Asesi</a></li>
              <li><a href='#KOMPETEN' data-toggle='tab'>Kompeten</a></li>
              <li><a href='#BELUM-KOMPETEN' data-toggle='tab'>Belum Kompeten</a></li>
              <li><a href='#BELUM-VERIFIKASI' data-toggle='tab'>Belum Terverifikasi</a></li>
              <li><a href='#TERVERIFIKASI' data-toggle='tab'>Terverifikasi</a></li>
              <li><a href='#DIBLOKIR' data-toggle='tab'>Diblokir</a></li>
            </ul>
            <div class='tab-content'>
              <div class='tab-pane active' id='ANGKATAN'>
					<div style='overflow-x:auto;'>
					<table id='example7' class='table table-bordered table-striped'>
						<thead><tr><th>No</th><th>Tahun Angkatan</th><th>Daftar</th><th>Asesmen</th><th>Kompeten</th><th>Belum Kompeten</th><th>Tindak Lanjut</th><th>Sedang Proses</th></tr></thead>
						<tbody>";
						$no=1;
						$sqlwilasesi0="SELECT * FROM `asesi` ORDER BY `id` ASC";
						$wilasesi0=$conn->query($sqlwilasesi0);
						while ($was0=$wilasesi0->fetch_assoc()){
							/*if ($was0['tgl_daftar']=='0000-00-00'){
								$angkatan=substr($was0['no_pendaftaran'],0,4);
								$thndaftarnya=substr($was0['no_pendaftaran'],0,4);
								$blndaftarnya=substr($was0['no_pendaftaran'],4,2);
								$tgldaftarnya=substr($was0['no_pendaftaran'],6,2);
								$tgl_daftarx=$thndaftarnya."-".$blndaftarnya."-".$tgldaftarnya;
							}else{*/
								$angkatan=substr($was0['waktu'],0,4);
								$thndaftarnya=substr($was0['waktu'],0,4);
								$blndaftarnya=substr($was0['waktu'],4,2);
								$tgldaftarnya=substr($was0['waktu'],6,2);
								$tgl_daftarx=$thndaftarnya."-".$blndaftarnya."-".$tgldaftarnya;
							//}
							//$conn->query("UPDATE `asesi` SET `tgl_daftar`='$tgl_daftarx',`angkatan`='$angkatan' WHERE `id`='$was0[id]'");
							$conn->query("UPDATE `asesi` SET `angkatan`='$angkatan' WHERE `id`='$was0[id]'");
						}
						$sqlwilasesi="SELECT DISTINCT `angkatan` FROM `asesi` WHERE `id_pengusul`='$_SESSION[namauser]' ORDER BY `angkatan` DESC";
						$wilasesi=$conn->query($sqlwilasesi);
						while ($was=$wilasesi->fetch_assoc()){
							$sqljumaswil="SELECT `no_pendaftaran`,`angkatan` FROM `asesi` WHERE `angkatan`='$was[angkatan]' AND `id_pengusul`='$_SESSION[namauser]'";
							$jumaswil=$conn->query($sqljumaswil);
							$jumas=$jumaswil->num_rows;

							$sqlasesmen="SELECT DISTINCT `id_asesi` FROM `asesi_asesmen` WHERE `tgl_asesmen` LIKE '$was[angkatan]%'";
							$asesmen=$conn->query($sqlasesmen);
							$jumasesmen=0;
							while ($juma1=$asesmen->fetch_assoc()){
								$sqlcekpengusul="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$juma1[id_asesi]' AND `id_pengusul`='$_SESSION[namauser]'";
								$cekpengusul=$conn->query($sqlcekpengusul);
								$pengus1=$cekpengusul->num_rows;
								if ($pengus1>0){
									$jumasesmen=$jumasesmen+1;
								}else{
									$jumasesmen=$jumasesmen;
								}
							}

							$sqlasesmen2="SELECT DISTINCT `id_asesi` FROM `asesi_asesmen` WHERE `tgl_asesmen` LIKE '$was[angkatan]%' AND `status_asesmen`='K'";
							$asesmen2=$conn->query($sqlasesmen2);
							$jumasesmen2=0;
							
							while ($juma2=$asesmen2->fetch_assoc()){
								$sqlcekpengusul2="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$juma2[id_asesi]' AND `id_pengusul`='$_SESSION[namauser]'";
								$cekpengusul2=$conn->query($sqlcekpengusul2);
								$pengus2=$cekpengusul2->num_rows;
								if ($pengus2>0){
									$jumasesmen2=$jumasesmen2+1;
								}else{
									$jumasesmen2=$jumasesmen2;
								}
							}
							
							$sqlasesmen3="SELECT DISTINCT `id_asesi` FROM `asesi_asesmen` WHERE `tgl_asesmen` LIKE '$was[angkatan]%' AND `status_asesmen`='BK'";
							$asesmen3=$conn->query($sqlasesmen3);
							$jumasesmen3=0;
							
							while ($juma3=$asesmen3->fetch_assoc()){
								$sqlcekpengusul3="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$juma3[id_asesi]' AND `id_pengusul`='$_SESSION[namauser]'";
								$cekpengusul3=$conn->query($sqlcekpengusul3);
								$pengus3=$cekpengusul3->num_rows;
								if ($pengus3>0){
									$jumasesmen3=$jumasesmen3+1;
								}else{
									$jumasesmen3=$jumasesmen3;
								}
							}
							
							$sqlasesmen4="SELECT DISTINCT `id_asesi` FROM `asesi_asesmen` WHERE `tgl_asesmen` LIKE '$was[angkatan]%' AND `status_asesmen`='P'";
							$asesmen4=$conn->query($sqlasesmen4);
							$jumasesmen4=0;
							
							while ($juma4=$asesmen4->fetch_assoc()){
								$sqlcekpengusul4="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$juma4[id_asesi]' AND `id_pengusul`='$_SESSION[namauser]'";
								$cekpengusul4=$conn->query($sqlcekpengusul4);
								$pengus4=$cekpengusul4->num_rows;
								if ($pengus4>0){
									$jumasesmen4=$jumasesmen4+1;
								}else{
									$jumasesmen4=$jumasesmen4;
								}
							}
							
							$sqlasesmen5="SELECT DISTINCT `id_asesi` FROM `asesi_asesmen` WHERE `tgl_asesmen` LIKE '$was[angkatan]%' AND `status_asesmen`='TL'";
							$asesmen5=$conn->query($sqlasesmen5);
							$jumasesmen5=0;
							
							while ($juma5=$asesmen5->fetch_assoc()){
								$sqlcekpengusul5="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$juma5[id_asesi]' AND `id_pengusul`='$_SESSION[namauser]'";
								$cekpengusul5=$conn->query($sqlcekpengusul5);
								$pengus5=$cekpengusul5->num_rows;
								if ($pengus5>0){
									$jumasesmen5=$jumasesmen5+1;
								}else{
									$jumasesmen5=$jumasesmen5;
								}
							}

							if ($jumasesmen>$jumas){
								$ketjumas="<font color='red'>".$jumasesmen." (tidak sinkron)</font> <a href='media.php?module=asesmen' class='btn btn-primary btn-xs'><span class='glyphicon glyphicon-zoom-in' aria-hidden='true' title='Lihat Data'></span></a>";
							}else{
								$ketjumas=$jumasesmen;
							}
							echo "<tr class=gradeX><td>$no</td><td>$was[angkatan]</td><td>$jumas</td><td>$ketjumas</td><td>$jumasesmen2</td><td>$jumasesmen3</td><td>$jumasesmen5</td><td>$jumasesmen4</td></tr>";
							$no++;
						}
					echo "</tbody></table>
					</div>
              </div>
              <!-- /.tab-pane -->

              <div class='tab-pane' id='WILAYAH'>
			<div style='overflow-x:auto;'>
				<table id='example7' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Wilayah Provinsi</th><th>Jumlah Asesi</th><th>Aksi</th></tr></thead>
					<tbody>";
					$no=1;
					$sqlwilasesi="SELECT DISTINCT `propinsi` FROM `asesi` WHERE `id_pengusul`='$_SESSION[namauser]' ORDER BY `propinsi` ASC";
					$wilasesi=$conn->query($sqlwilasesi);
					while ($was=$wilasesi->fetch_assoc()){
						$sqljumaswil="SELECT * FROM `asesi` WHERE `propinsi`='$was[propinsi]' AND `id_pengusul`='$_SESSION[namauser]'";
						$jumaswil=$conn->query($sqljumaswil);
						$jumas=$jumaswil->num_rows;
						$sqldatawil="SELECT * FROM `data_wilayah` WHERE `id_wil`='$was[propinsi]'";
						$datawil=$conn->query($sqldatawil);
						$nmwil=$datawil->fetch_assoc();
						$namawilayah=trim($nmwil['nm_wil']);
						echo "<tr class=gradeX><td>$no</td><td>$namawilayah</td><td>$jumas</td><td><a href='?module=asesibypropinsi&wil=$was[propinsi]' class='btn btn-primary btn-xs'>Detail</a></td></tr>";
						$no++;
					}
				echo "</tbody></table>
			</div>
              </div>
              <!-- /.tab-pane -->
              <div class='tab-pane' id='WILAYAH-KOTA'>
			<div style='overflow-x:auto;'>
				<table id='example8' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Wilayah Kota/Kabupaten</th><th>Jumlah Asesi</th><th>Aksi</th></tr></thead>
					<tbody>";
					$no2=1;
					$sqlwilasesi2="SELECT DISTINCT `kota` FROM `asesi` WHERE `id_pengusul`='$_SESSION[namauser]' ORDER BY `kota` ASC";
					$wilasesi2=$conn->query($sqlwilasesi2);
					while ($was2=$wilasesi2->fetch_assoc()){
						$sqljumaswil2="SELECT * FROM `asesi` WHERE `kota`='$was2[kota]' AND `id_pengusul`='$_SESSION[namauser]'";
						$jumaswil2=$conn->query($sqljumaswil2);
						$jumas2=$jumaswil2->num_rows;
						$sqldatawil2="SELECT * FROM `data_wilayah` WHERE `id_wil`='$was2[kota]'";
						$datawil2=$conn->query($sqldatawil2);
						$nmwil2=$datawil2->fetch_assoc();
						$namawilayah2=trim($nmwil2['nm_wil']);
						echo "<tr class=gradeX><td>$no2</td><td>$namawilayah2</td><td>$jumas2</td><td></td></tr>";
						$no2++;
					}
				echo "</tbody></table>
			</div>
              </div>
              <!-- /.tab-pane -->
              <div class='tab-pane' id='KOMPETEN'>
				<div style='overflow-x:auto;'>
				<table id='example5' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Identitas Asesi</th><th>Asesmen Skema Sertifikasi</th><th>Aksi</th></tr></thead>
					<tbody>";
					$no=1;
					$sqllsp="SELECT DISTINCT `id_asesi` FROM `asesi_asesmen` WHERE `status_asesmen`='K' ORDER BY `id_asesi` DESC";
					$lsp=$conn->query($sqllsp);
					while ($pm=$lsp->fetch_assoc()){
						$sqlgetskkni=$conn->query("SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$pm[id_asesi]' AND `status_asesmen`='K' ORDER BY `id` DESC");
						$ikutasesmen=$sqlgetskkni->num_rows;
						$sqlgetasesi="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$pm[id_asesi]' AND `id_pengusul`='$_SESSION[namauser]'";
						$getasesi=$conn->query($sqlgetasesi);
						while ($pma=$getasesi->fetch_assoc()){
							echo "<tr class=gradeX><td>$no</td><td>
							  <div class='box box-widget widget-user-2'>
								<!-- Add the bg color to the header using any of the bg-* classes -->
								<div class='widget-user-header bg-green'>
								  <div class='widget-user-image'>";
									if ($pma['foto']==''){
										echo "<img class='profile-user-img img-responsive img-circle' src='../images/default.jpg' alt='User Avatar'>";
									}else{
										echo "<img class='profile-user-img img-responsive img-circle' src='../foto_asesi/$pma[foto]' alt='User Avatar'>";
									}
									
								if(empty($pma['foto']) or empty($pma['ktp']) || empty($pma['kk']) || empty($pma['ijazah']) || empty($pma['transkrip'])){
									if (empty($pma['foto'])){
										$kelengkapan1="Foto ";
									}
									if(empty($pma['ktp'])){
										$kelengkapan2="KTP ";
									}
									if(empty($pma['kk'])){
										$kelengkapan3="KK ";
									}
									if(empty($pma['ijazah'])){
										$kelengkapan4="Ijazah ";
									}
									if(empty($pma['transkrip'])){
										$kelengkapan5="Transkrip";
									}
									$kelengkapan="Kurang ".$kelengkapan1.$kelengkapan2.$kelengkapan3.$kelengkapan4.$kelengkapan5;
									
								}else{
									$kelengkapan="Lengkap";

								}
								$sqlpendidikan="SELECT * FROM `pendidikan` WHERE `id`='$pma[pendidikan]'";
								$pendidikan=$conn->query($sqlpendidikan);
								$pe=$pendidikan->fetch_assoc();
								  echo "</div>
								  <!-- /.widget-user-image -->
								  <h3 class='widget-user-username'>$pma[nama]</h3>
								  <h5 class='widget-user-desc'>No. Pendaftaran : $pma[no_pendaftaran]</h5>
								  <h5 class='widget-user-desc'>Pendidikan Terakhir : $pe[jenjang_pendidikan]</h5>
								</div>
								<div class='box-footer'>
								  <ul class='nav nav-stacked'>
									<li>Asesmen diikuti <span class='pull-right badge'>$ikutasesmen Skema</span></li>
									<li>Dokumen Pokok <span class='pull-right badge'>$kelengkapan</span></li>

								  </ul>
								</div>
							  </div>
							  <!-- /.widget-user --></td>";
							
				
							echo "</td><td>";
							
							while ($ns=$sqlgetskkni->fetch_assoc()){
								$sqlgetskkni2=$conn->query("SELECT * FROM `skema_kkni` WHERE `id`='$ns[id_skemakkni]'");
								$ns2=$sqlgetskkni2->fetch_assoc();
								$sqlgetskkni3=$conn->query("SELECT * FROM `asesi_doc` WHERE `id_asesi`='$pma[no_pendaftaran]' AND `id_skemakkni`='$ns[id_skemakkni]'");
								$ns3=$sqlgetskkni3->num_rows;

								echo "<b>$ns2[judul]</b> ($ns2[kode_skema])<br>";
								if ($ns3==0){
									echo "Persyaratan : <span class='text-red'><b>Belum Melengkapi</b></span><br>";
								}else{
									echo "Persyaratan : <span class='text-green'><b>Telah mengunggah $ns3 Dokumen</b></span><br>";
								}
							
								if ($ns['id_asesor']=='0'){
									echo "Ploting Asesor : <span class='text-red'><b>Belum</b></span><br>";

								}else{
									$tglasesmen=tgl_indo($ns['tgl_asesmen']);
									echo "Ploting Asesor : <span class='text-green'><b>Sudah</b></span><br>";
									echo "Tanggal Asesmen : <span class='text-green'><b>$tglasesmen</b></span><br>";

								}
								if ($ns['no_lisensi']=='' && $ns['no_serisertifikat']==''){
									echo "Sertifikat : <span class='text-red'><b>Belum Dicetak</b></span>&nbsp;";
									//echo "<a href='#myModalK".$ns['id']."' class='btn btn-primary btn-xs' data-toggle='modal' data-id='".$ns['id']."' title='Perbarui dan unggah scan dokumen sertifikat Asesi'>Perbarui dan Unggah Sertifikat</a><br>";

								}else{
									echo "No. Sertifikat : <span class='text-green'><b>$ns[no_lisensi]</b></span><br>";
									echo "No. Seri Sertifikat : <span class='text-green'><b>$ns[no_serisertifikat]</b></span> ";
									echo "Dokumen Sertifikat: <a href='#myModalCertK".$ns['id']."' class='btn btn-primary btn-xs' data-toggle='modal' data-id='".$ns['id']."' title='Lihat scan dokumen sertifikat Asesi'>Lihat Sertifikat</a><br>";

								}
								


								echo "<script>
									$(function(){
												$(document).on('click','.edit-record',function(e){
													e.preventDefault();
													$('#myModalK".$ns['id']."').modal('show');
												});
										});
								</script>
								<!-- Modal -->
									<div class='modal fade' id='myModalK".$ns['id']."' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
										<div class='modal-dialog'>
											<div class='modal-content'>
												<div class='modal-header'>
													<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Tutup</span></button>
													<h4 class='modal-title' id='myModalLabel'>Dokumen Sertifikat ".$ns['no_lisensi']."</h4>
												</div>
												<form role='form' method='POST' action='uploadcert.php' enctype='multipart/form-data'>
												<div class='modal-body'>
														<div class='col-md-12'>
															<div class='form-group'>
																<label>Nomor Seri Sertifikat</label><br>
																<input type='text' class='form-control' name='no_serisertifikat".$ns['id']."'/>
																<input type='hidden' class='form-control' name='idpost' value='$ns[id]'/>
															</div>
														</div>
														<div class='col-md-12'>
															<div class='form-group'>
																<label>Nomor Sertifikat/ Lisensi</label><br>
																<input type='text' class='form-control' name='no_lisensi".$ns['id']."'/>
															</div>
														</div>
														<div class='col-md-12'>
															<div class='form-group'>
																<label>Masa Berlaku</label><br>
																<input type='date' class='form-control' name='masa_berlaku".$ns['id']."'/>
															</div>
														</div>
														<div class='col-md-12'>
															<div class='form-group'>
																<label>Unggah Sertifikat</label>
																<input type='file' name='file".$ns['id']."'/><br>
															</div>
														</div>

												</div>
												<div class='modal-footer'>
													<div align='left' class='col-md-6 col-sm-6 col-xs-6'>
														<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>
													</div>
													<div align='right' class='col-md-6 col-sm-6 col-xs-6'>
														<button type='submit' class='btn btn-success' name='simpancert'>Simpan</button>
													</div>
													
												</div>
												</form>

											</div>
											</div>
									</div>
								<!-- Modal -->";

								echo "<script>
									$(function(){
												$(document).on('click','.edit-record',function(e){
													e.preventDefault();
													$('#myModalCertK".$ns['id']."').modal('show');
												});
										});
								</script>
								<!-- Modal -->
									<div class='modal fade' id='myModalCertK".$ns['id']."' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
										<div class='modal-dialog'>
											<div class='modal-content'>
												<div class='modal-header'>
													<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Tutup</span></button>
													<h4 class='modal-title' id='myModalLabel'>Dokumen Sertifikat ".$ns['no_lisensi']."</h4>
												</div>
												<div class='modal-body'><embed src='../foto_asesicert/$ns[foto_sertifikat]' width='100%'/>
												</div>
												<div class='modal-footer'>
													<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>
												</div>
											</div>
											</div>
									</div>
								<!-- Modal -->";


							}
							echo "</td>";
							echo "<td><form name='frm' method='POST' role='form' enctype='multipart/form-data'>
								<input type='hidden' name='idblokir' value='$pma[id]'><input type='submit' name='blokir' class='btn btn-warning btn-xs btn-block' title='Blokir akses $pma[nama]' value='Blokir'></form>
								</td></tr>";
							$no++;
						}
					}
				echo "</tbody></table>
				</div>
              </div>
              <!-- /.tab-pane -->
              <div class='tab-pane' id='BELUM-KOMPETEN'>
				<div style='overflow-x:auto;'>
				<table id='example6' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Identitas Asesi</th><th>Asesmen Skema Sertifikasi</th><th>Aksi</th></tr></thead>
					<tbody>";
					$no=1;
					$sqllsp="SELECT DISTINCT `id_asesi` FROM `asesi_asesmen` WHERE `status_asesmen`='BK' ORDER BY `id_asesi` DESC";
					$lsp=$conn->query($sqllsp);
					while ($pm=$lsp->fetch_assoc()){
						$sqlgetskkni=$conn->query("SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$pm[id_asesi]' AND `status_asesmen`='BK' ORDER BY `id` DESC");
						$ikutasesmen=$sqlgetskkni->num_rows;
						$sqlgetasesi="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$pm[id_asesi]' AND `id_pengusul`='$_SESSION[namauser]'";
						$getasesi=$conn->query($sqlgetasesi);
						while ($pma=$getasesi->fetch_assoc()){
							echo "<tr class=gradeX><td>$no</td><td>
							  <div class='box box-widget widget-user-2'>
								<!-- Add the bg color to the header using any of the bg-* classes -->
								<div class='widget-user-header bg-orange'>
								  <div class='widget-user-image'>";
									if ($pma['foto']==''){
										echo "<img class='profile-user-img img-responsive img-circle' src='../images/default.jpg' alt='User Avatar'>";
									}else{
										echo "<img class='profile-user-img img-responsive img-circle' src='../foto_asesi/$pma[foto]' alt='User Avatar'>";
									}
									
								if(empty($pma['foto']) or empty($pma['ktp']) || empty($pma['kk']) || empty($pma['ijazah']) || empty($pma['transkrip'])){
									if (empty($pma['foto'])){
										$kelengkapan1="Foto ";
									}
									if(empty($pma['ktp'])){
										$kelengkapan2="KTP ";
									}
									if(empty($pma['kk'])){
										$kelengkapan3="KK ";
									}
									if(empty($pma['ijazah'])){
										$kelengkapan4="Ijazah ";
									}
									if(empty($pma['transkrip'])){
										$kelengkapan5="Transkrip";
									}
									$kelengkapan="Kurang ".$kelengkapan1.$kelengkapan2.$kelengkapan3.$kelengkapan4.$kelengkapan5;
									
								}else{
									$kelengkapan="Lengkap";

								}
								$sqlpendidikan="SELECT * FROM `pendidikan` WHERE `id`='$pma[pendidikan]'";
								$pendidikan=$conn->query($sqlpendidikan);
								$pe=$pendidikan->fetch_assoc();
								  echo "</div>
								  <!-- /.widget-user-image -->
								  <h3 class='widget-user-username'>$pma[nama]</h3>
								  <h5 class='widget-user-desc'>No. Pendaftaran : $pma[no_pendaftaran]</h5>
								  <h5 class='widget-user-desc'>Pendidikan Terakhir : $pe[jenjang_pendidikan]</h5>
								</div>
								<div class='box-footer'>
								  <ul class='nav nav-stacked'>
									<li>Asesmen diikuti <span class='pull-right badge'>$ikutasesmen Skema</span></li>
									<li>Dokumen Pokok <span class='pull-right badge'>$kelengkapan</span></li>

								  </ul>
								</div>
							  </div>
							  <!-- /.widget-user --></td>";
							
				
							echo "</td><td>";
							
							while ($ns=$sqlgetskkni->fetch_assoc()){
								$sqlgetskkni2=$conn->query("SELECT * FROM `skema_kkni` WHERE `id`='$ns[id_skemakkni]'");
								$ns2=$sqlgetskkni2->fetch_assoc();
								$sqlgetskkni3=$conn->query("SELECT * FROM `asesi_doc` WHERE `id_asesi`='$pma[no_pendaftaran]' AND `id_skemakkni`='$ns[id_skemakkni]'");
								$ns3=$sqlgetskkni3->num_rows;

								echo "<b>$ns2[judul]</b> ($ns2[kode_skema])<br>";
								if ($ns3==0){
									echo "Persyaratan : <span class='text-red'><b>Belum Melengkapi</b></span><br>";
								}else{
									echo "Persyaratan : <span class='text-green'><b>Telah mengunggah $ns3 Dokumen</b></span>
									<a href='?module=syarat&id=$ns[id_skemakkni]&ida=$pma[no_pendaftaran]' class='btn btn-info btn-xs' title='Lakukan Verifikasi/Persetujuan Dokumen yang diunggah Asesi'>Verifikasi</a><br>";
								}
							
								if ($ns['id_asesor']=='0'){
									echo "Ploting Asesor : <span class='text-red'><b>Belum</b></span><br>";

								}else{
									$tglasesmen=tgl_indo($ns['tgl_asesmen']);
									echo "Ploting Asesor : <span class='text-green'><b>Sudah</b></span><br>";
									echo "Tanggal Asesmen : <span class='text-green'><b>$tglasesmen</b></span><br>";

								}
							}
							echo "</td>";
							echo "<td><form name='frm' method='POST' role='form' enctype='multipart/form-data'>
								<input type='hidden' name='idblokir' value='$pma[id]'><input type='submit' name='blokir' class='btn btn-warning btn-xs btn-block' title='Blokir akses $pma[nama]' value='Blokir'></form>
								</td></tr>";
							$no++;
						}
					}
				echo "</tbody></table>
				</div>
              </div>
              <!-- /.tab-pane -->
              <div class='tab-pane' id='BELUM-VERIFIKASI'>
				<div style='overflow-x:auto;'>
				<table id='example7' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Identitas Asesi</th><th>Asesmen Skema Sertifikasi</th><th>Aksi</th></tr></thead>
					<tbody>";
					$no=1;
					$sqllsp="SELECT * FROM `asesi` WHERE `verifikasi`='P' AND `blokir`='N' AND `id_pengusul`='$_SESSION[namauser]' ORDER BY `nama` ASC";
					$lsp=$conn->query($sqllsp);
					while ($pm=$lsp->fetch_assoc()){
						$sqlcekstatusasesmen="SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$pm[no_pendaftaran]' AND `status`='P'";
						$cekstatusasesmen=$conn->query($sqlcekstatusasesmen);
						while ($csa=$cekstatusasesmen->fetch_assoc()){
							$sqlgetskkni=$conn->query("SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$csa[id_asesi]' ORDER BY `id` DESC");
							$ikutasesmen=$sqlgetskkni->num_rows;
							echo "<tr class=gradeX><td>$no</td><td>
							  <div class='box box-widget widget-user-2'>
								<!-- Add the bg color to the header using any of the bg-* classes -->
								<div class='widget-user-header bg-aqua'>
								  <div class='widget-user-image'>";
									if ($pm['foto']==''){
										echo "<img class='profile-user-img img-responsive img-circle' src='../images/default.jpg' alt='User Avatar'>";
									}else{
										echo "<img class='profile-user-img img-responsive img-circle' src='../foto_asesi/$pm[foto]' alt='User Avatar'>";
									}
									
								if(empty($pm['foto']) or empty($pm['ktp']) || empty($pm['kk']) || empty($pm['ijazah']) || empty($pm['transkrip'])){
									if (empty($pm['foto'])){
										$kelengkapan1="Foto ";
									}
									if(empty($pm['ktp'])){
										$kelengkapan2="KTP ";
									}
									if(empty($pm['kk'])){
										$kelengkapan3="KK ";
									}
									if(empty($pm['ijazah'])){
										$kelengkapan4="Ijazah ";
									}
									if(empty($pm['transkrip'])){
										$kelengkapan5="Transkrip";
									}
									$kelengkapan="Kurang ".$kelengkapan1.$kelengkapan2.$kelengkapan3.$kelengkapan4.$kelengkapan5;
									
								}else{
									$kelengkapan="Lengkap";

								}
								$sqlpendidikan="SELECT * FROM `pendidikan` WHERE `id`='$pm[pendidikan]'";
								$pendidikan=$conn->query($sqlpendidikan);
								$pe=$pendidikan->fetch_assoc();
								  echo "</div>
								  <!-- /.widget-user-image -->
								  <h3 class='widget-user-username'>$pm[nama]</h3>
								  <h5 class='widget-user-desc'>No. Pendaftaran : $pm[no_pendaftaran]</h5>
								  <h5 class='widget-user-desc'>Pendidikan Terakhir : $pe[jenjang_pendidikan]</h5>
								</div>
								<div class='box-footer'>
								  <ul class='nav nav-stacked'>
									<li>Asesmen diikuti <span class='pull-right badge'>$ikutasesmen Skema</span></li>
									<li>Dokumen Pokok <span class='pull-right badge'>$kelengkapan</span></li>

								  </ul>
								</div>
							  </div>
							  <!-- /.widget-user --></td>";
							
				
							echo "</td><td>";
							
							while ($ns=$sqlgetskkni->fetch_assoc()){
								$sqlgetskkni2=$conn->query("SELECT * FROM `skema_kkni` WHERE `id`='$ns[id_skemakkni]'");
								$ns2=$sqlgetskkni2->fetch_assoc();
								$sqlgetskkni3=$conn->query("SELECT * FROM `asesi_doc` WHERE `id_asesi`='$pm[no_pendaftaran]' AND `id_skemakkni`='$ns[id_skemakkni]'");
								$ns3=$sqlgetskkni3->num_rows;

								echo "<b>$ns2[judul]</b> ($ns2[kode_skema])<br>";
								if ($ns3==0){
									echo "Persyaratan : <span class='text-red'><b>Belum Melengkapi</b></span><br>";
								}else{
									echo "Persyaratan : <span class='text-green'><b>Telah mengunggah $ns3 Dokumen</b></span>
									<a href='?module=syarat&id=$ns[id_skemakkni]&ida=$pm[no_pendaftaran]' class='btn btn-info btn-xs' title='Lakukan Verifikasi/Persetujuan Dokumen yang diunggah Asesi'>Verifikasi</a><br>";
								}
							
								if ($ns['id_asesor']=='0'){
									echo "Ploting Asesor : <span class='text-red'><b>Belum</b></span><br>";

								}else{
									$tglasesmen=tgl_indo($ns['tgl_asesmen']);
									echo "Ploting Asesor : <span class='text-green'><b>Sudah</b></span><br>";
									echo "Tanggal Asesmen : <span class='text-green'><b>$tglasesmen</b></span><br>";

								}

							}
							echo "</td>";
							echo "<td><form name='frm' method='POST' role='form' enctype='multipart/form-data'>
								<input type='hidden' name='idblokir' value='$pm[id]'><input type='submit' name='blokir' class='btn btn-warning btn-xs btn-block' title='Blokir akses $pm[nama]' value='Blokir'></form>
								  <form name='frm' method='POST' role='form' enctype='multipart/form-data'>
								<input type='hidden' name='idhapus' value='$pm[id]'><input type='submit' name='hapusasesi' class='btn btn-danger btn-xs btn-block' title='Hapus $pm[nama]' value='Hapus'></form>

								</td></tr>";
							$no++;
						}
					}
				echo "</tbody></table>
			</div>
              </div>
              <!-- /.tab-pane -->
              <div class='tab-pane' id='TERVERIFIKASI'>
				<div style='overflow-x:auto;'>
				<table id='example3' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Identitas Asesi</th><th>Asesmen Skema Sertifikasi</th><th>Aksi</th></tr></thead>
					<tbody>";
					$no=1;
					$sqllsp="SELECT * FROM `asesi` WHERE `blokir`='N' AND `id_pengusul`='$_SESSION[namauser]' ORDER BY `nama` ASC";
					$lsp=$conn->query($sqllsp);
					while ($pm=$lsp->fetch_assoc()){
						$sqlcekstatusasesmen="SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$pm[no_pendaftaran]' AND `status`!='P'";
						$cekstatusasesmen=$conn->query($sqlcekstatusasesmen);
						while ($csa=$cekstatusasesmen->fetch_assoc()){
							$sqlgetskkni=$conn->query("SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$csa[id_asesi]' ORDER BY `id` DESC");
							$ikutasesmen=$sqlgetskkni->num_rows;
							echo "<tr class=gradeX><td>$no</td><td>
							  <div class='box box-widget widget-user-2'>
								<!-- Add the bg color to the header using any of the bg-* classes -->
								<div class='widget-user-header bg-green'>
								  <div class='widget-user-image'>";
									if ($pm['foto']==''){
										echo "<img class='profile-user-img img-responsive img-circle' src='../images/default.jpg' alt='User Avatar'>";
									}else{
										echo "<img class='profile-user-img img-responsive img-circle' src='../foto_asesi/$pm[foto]' alt='User Avatar'>";
									}
									
								if(empty($pm['foto']) or empty($pm['ktp']) || empty($pm['kk']) || empty($pm['ijazah']) || empty($pm['transkrip'])){
									if (empty($pm['foto'])){
										$kelengkapan1="Foto ";
									}
									if(empty($pm['ktp'])){
										$kelengkapan2="KTP ";
									}
									if(empty($pm['kk'])){
										$kelengkapan3="KK ";
									}
									if(empty($pm['ijazah'])){
										$kelengkapan4="Ijazah ";
									}
									if(empty($pm['transkrip'])){
										$kelengkapan5="Transkrip";
									}
									$kelengkapan="Kurang ".$kelengkapan1.$kelengkapan2.$kelengkapan3.$kelengkapan4.$kelengkapan5;
									
								}else{
									$kelengkapan="Lengkap";

								}
								$sqlpendidikan="SELECT * FROM `pendidikan` WHERE `id`='$pm[pendidikan]'";
								$pendidikan=$conn->query($sqlpendidikan);
								$pe=$pendidikan->fetch_assoc();
								  echo "</div>
								  <!-- /.widget-user-image -->
								  <h3 class='widget-user-username'>$pm[nama]</h3>
								  <h5 class='widget-user-desc'>No. Pendaftaran : $pm[no_pendaftaran]</h5>
								  <h5 class='widget-user-desc'>Pendidikan Terakhir : $pe[jenjang_pendidikan]</h5>
								</div>
								<div class='box-footer'>
								  <ul class='nav nav-stacked'>
									<li>Asesmen diikuti <span class='pull-right badge'>$ikutasesmen Skema</span></li>
									<li>Dokumen Pokok <span class='pull-right badge'>$kelengkapan</span></li>

								  </ul>
								</div>
							  </div>
							  <!-- /.widget-user --></td>";
							
				
							echo "</td><td>";
							
							while ($ns=$sqlgetskkni->fetch_assoc()){
								$sqlgetskkni2=$conn->query("SELECT * FROM `skema_kkni` WHERE `id`='$ns[id_skemakkni]'");
								$ns2=$sqlgetskkni2->fetch_assoc();
								$sqlgetskkni3=$conn->query("SELECT * FROM `asesi_doc` WHERE `id_asesi`='$pm[no_pendaftaran]' AND `id_skemakkni`='$ns[id_skemakkni]'");
								$ns3=$sqlgetskkni3->num_rows;

								echo "<b>$ns2[judul]</b> ($ns2[kode_skema])<br>";
								if ($ns3==0){
									echo "Persyaratan : <span class='text-red'><b>Belum Melengkapi</b></span><br>";
								}else{
									echo "Persyaratan : <span class='text-green'><b>Telah mengunggah $ns3 Dokumen</b></span>
									<a href='?module=syarat&id=$ns[id_skemakkni]&ida=$pm[no_pendaftaran]' class='btn btn-info btn-xs' title='Lakukan Verifikasi/Persetujuan Dokumen yang diunggah Asesi'>Verifikasi</a><br>";
								}
							
								if ($ns['id_asesor']=='0'){
									echo "Ploting Asesor : <span class='text-red'><b>Belum</b></span><br>";

								}else{
									$tglasesmen=tgl_indo($ns['tgl_asesmen']);
									echo "Ploting Asesor : <span class='text-green'><b>Sudah</b></span><br>";
									echo "Tanggal Asesmen : <span class='text-green'><b>$tglasesmen</b></span><br>";

								}
							}
							echo "</td>";
							echo "<td><form name='frm' method='POST' role='form' enctype='multipart/form-data'>
								<input type='hidden' name='idblokir' value='$pm[id]'><input type='submit' name='blokir' class='btn btn-warning btn-xs btn-block' title='Blokir akses $pm[nama]' value='Blokir'></form>
								</td></tr>";
							$no++;
						}
					}
				echo "</tbody></table>
				</div>
              </div>
              <!-- /.tab-pane -->
              <div class='tab-pane' id='DIBLOKIR'>
			<div style='overflow-x:auto;'>
				<table id='example4' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Identitas Asesi</th><th>Asesmen Skema Sertifikasi</th><th>Aksi</th></tr></thead>
					<tbody>";
					$no=1;
					$sqllsp="SELECT * FROM `asesi` WHERE `blokir`='Y' AND `id_pengusul`='$_SESSION[namauser]' ORDER BY `nama` ASC";
					$lsp=$conn->query($sqllsp);
					while ($pm=$lsp->fetch_assoc()){
						$sqlgetskkni=$conn->query("SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$pm[no_pendaftaran]' ORDER BY `id` DESC");
						$ikutasesmen=$sqlgetskkni->num_rows;
						echo "<tr class=gradeX><td>$no</td><td>
						  <div class='box box-widget widget-user-2'>
							<!-- Add the bg color to the header using any of the bg-* classes -->
							<div class='widget-user-header bg-red'>
							  <div class='widget-user-image'>";
								if ($pm['foto']==''){
									echo "<img class='profile-user-img img-responsive img-circle' src='../images/default.jpg' alt='User Avatar'>";
								}else{
									echo "<img class='profile-user-img img-responsive img-circle' src='../foto_asesi/$pm[foto]' alt='User Avatar'>";
								}
								
							if(empty($pm['foto']) or empty($pm['ktp']) || empty($pm['kk']) || empty($pm['ijazah']) || empty($pm['transkrip'])){
								if (empty($pm['foto'])){
									$kelengkapan1="Foto ";
								}
								if(empty($pm['ktp'])){
									$kelengkapan2="KTP ";
								}
								if(empty($pm['kk'])){
									$kelengkapan3="KK ";
								}
								if(empty($pm['ijazah'])){
									$kelengkapan4="Ijazah ";
								}
								if(empty($pm['transkrip'])){
									$kelengkapan5="Transkrip";
								}
								$kelengkapan="Kurang ".$kelengkapan1.$kelengkapan2.$kelengkapan3.$kelengkapan4.$kelengkapan5;
								
							}else{
								$kelengkapan="Lengkap";

							}
							$sqlpendidikan="SELECT * FROM `pendidikan` WHERE `id`='$pm[pendidikan]'";
							$pendidikan=$conn->query($sqlpendidikan);
							$pe=$pendidikan->fetch_assoc();
							  echo "</div>
							  <!-- /.widget-user-image -->
							  <h3 class='widget-user-username'>$pm[nama]</h3>
							  <h5 class='widget-user-desc'>No. Pendaftaran : $pm[no_pendaftaran]</h5>
							  <h5 class='widget-user-desc'>Pendidikan Terakhir : $pe[jenjang_pendidikan]</h5>
							</div>
							<div class='box-footer'>
							  <ul class='nav nav-stacked'>
								<li>Asesmen diikuti <span class='pull-right badge'>$ikutasesmen Skema</span></li>
								<li>Dokumen Pokok <span class='pull-right badge'>$kelengkapan</span></li>

							  </ul>
							</div>
						  </div>
						  <!-- /.widget-user --></td>";
						
			
						echo "</td><td>";
						
						while ($ns=$sqlgetskkni->fetch_assoc()){
							$sqlgetskkni2=$conn->query("SELECT * FROM `skema_kkni` WHERE `id`='$ns[id_skemakkni]'");
							$ns2=$sqlgetskkni2->fetch_assoc();
							$sqlgetskkni3=$conn->query("SELECT * FROM `asesi_doc` WHERE `id_asesi`='$pm[no_pendaftaran]' AND `id_skemakkni`='$ns[id_skemakkni]'");
							$ns3=$sqlgetskkni3->num_rows;

							echo "<b>$ns2[judul]</b> ($ns2[kode_skema])<br>";
							if ($ns3==0){
								echo "Persyaratan : <span class='text-red'><b>Belum Melengkapi</b></span><br>";
							}else{
								echo "Persyaratan : <span class='text-green'><b>Telah mengunggah $ns3 Dokumen</b></span>
								<a href='?module=syarat&id=$ns[id_skemakkni]&ida=$pm[no_pendaftaran]' class='btn btn-info btn-xs' title='Lakukan Verifikasi/Persetujuan Dokumen yang diunggah Asesi'>Verifikasi</a><br>";
							}
						
							if ($ns['id_asesor']=='0'){
								echo "Ploting Asesor : <span class='text-red'><b>Belum</b></span><br>";

							}else{
								$tglasesmen=tgl_indo($ns['tgl_asesmen']);
								echo "Ploting Asesor : <span class='text-green'><b>Sudah</b></span><br>";
								echo "Tanggal Asesmen : <span class='text-green'><b>$tglasesmen</b></span><br>";

							}
						}
						echo "</td>";
					    echo "<td><form name='frm' method='POST' role='form' enctype='multipart/form-data'>
							<input type='hidden' name='idblokir' value='$pm[id]'><input type='submit' name='bukablokir' class='btn btn-success btn-xs btn-block' title='Buka Blokir $pm[nama]' value='Buka Blokir'></form>
							</td></tr>";
						$no++;
					}
				echo "</tbody></table>
			</div>
              </div>
              <!-- /.tab-pane -->

            </div>
            <!-- /.tab-content -->
          </div>
          <!-- nav-tabs-custom -->			
			</div>
		  </div>
          
		</div>
	  </div>
	</section>";
  }
}

// Bagian Asesi Pendaftar Baru ==========================================================================================================
elseif ($_GET['module']=='asesibaru'){
  if ($_SESSION['leveluser']=='pengusul'|| $_SESSION['leveluser']=='user'){
    if( isset( $_REQUEST['plotasesor'] ))
	{

	}
    if( isset( $_REQUEST['blokir'] ))
	{
		$sqlblokir="UPDATE `asesi` SET `blokir`='Y' WHERE `id`='$_POST[idblokir]'";
		$conn->query($sqlblokir);
	}
    if( isset( $_REQUEST['bukablokir'] ))
	{
		$sqlblokir="UPDATE `asesi` SET `blokir`='N' WHERE `id`='$_POST[idblokir]'";
		$conn->query($sqlblokir);
	}
    if( isset( $_REQUEST['hapusasesi'] ))
	{
		$sqlblokir="DELETE FROM `asesi` WHERE `id`='$_POST[idhapus]'";
		$conn->query($sqlblokir);
	}
    $sqlgetpengusul="SELECT * FROM `pengusul` WHERE `no_pendaftaran`='$_SESSION[namauser]'";
    $getpengusul=$conn->query($sqlgetpengusul);
    $idp=$getpengusul->fetch_assoc();
    echo "

   <div id='loading' class='col-xs-12 overlay'>
              <i class='fa fa-refresh fa-spin'></i>
   </div>

    <!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
        Calon Asesi Sertifikasi Profesi (Pendaftar Baru)
        <small>Data Calon Asesi Baru</small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li class='active'>Data Calon Asesi Sertifikasi Profesi</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class='content'>
      <div class='row'>
        <div class='col-xs-12'>
          <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Data Calon Asesi Sertifikasi Profesi</h3>
			<!-- Buat sebuah tombol Cancel untuk kembali ke halaman awal / view data -->
			<a href='#myModal' class='btn btn-primary pull-right' data-toggle='modal' data-id='modalurl'><span class='fa fa-link' aria-hidden='true' title='Lihat URL Pendaftaran untuk Asesi Baru'></span> URL Pendaftaran Asesi</a>
			<a href='media.php?module=tambahasesi' class='btn btn-primary pull-right'>Daftarkan Asesi</a>&nbsp;
			<a href='media.php?module=importasesi' class='btn btn-success pull-right'>&nbsp;
				<span class='fa fa-upload'></span> Import Data Asesi
			</a>
			<script>
				$(function(){
							$(document).on('click','.edit-record',function(e){
								e.preventDefault();
								$('#myModal').modal('show');
							});
					});
			</script>
			<!-- Modal -->
				<div class='modal fade' id='myModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
					<div class='modal-dialog'>
						<div class='modal-content'>
							<div class='modal-header'>
								<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Tutup</span></button>
								<h4 class='modal-title' id='myModalLabel'>URL untuk Pendaftaran Asesi Pengusul</h4>
							</div>
							<div class='modal-body'>
								<div class='col-md-12'>
									<label>URL Dokumen</label>
									<div class='input-group input-group-sm'>
										<input type='text' class='form-control' value='http://$iden[url_domain]/diusulkan.php?idp=$idp[id]' id='URLdoc'>
										<span class='input-group-btn'>
										  <button type='button' class='btn btn-primary btn-flat' onclick='myURLFunction()'>Salin</button>
										</span>
									</div>
								</div>
							</div>
							<div class='modal-footer'>
								<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>
							</div>
						</div>
						</div>
				</div>

            </div>
            <!-- /.box-header -->
            <div class='box-body'>
		  <!-- Custom Tabs -->
          <div class='nav-tabs-custom'>
            <ul class='nav nav-tabs bg-gray'>
              <li class='active'><a href='#BELUM-VERIFIKASI' data-toggle='tab'>Calon Asesi Belum Terverifikasi (Pendaftar Baru)</a></li>
            </ul>
            <div class='tab-content'>
              <div class='tab-pane active' id='BELUM-VERIFIKASI'>
			<div style='overflow-x:auto;'>
				<table id='example1' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Identitas Asesi</th><th>Asesmen Skema Sertifikasi</th><th>Aksi</th></tr></thead>
					<tbody>";
					$no=1;
					$sqllsp="SELECT * FROM `asesi` WHERE `verifikasi`='P' AND `blokir`='N' AND `id_pengusul`='$_SESSION[namauser]' ORDER BY `nama` ASC";
					$lsp=$conn->query($sqllsp);
					while ($pm=$lsp->fetch_assoc()){
						$sqlgetskkni=$conn->query("SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$pm[no_pendaftaran]' AND `id_pengusul`='$_SESSION[namauser]' ORDER BY `id` DESC");
						$ikutasesmen=$sqlgetskkni->num_rows;
						echo "<tr class=gradeX><td>$no</td><td>
						  <div class='box box-widget widget-user-2'>
							<!-- Add the bg color to the header using any of the bg-* classes -->
							<div class='widget-user-header bg-aqua'>
							  <div class='widget-user-image'>";
								if ($pm['foto']==''){
									echo "<img class='profile-user-img img-responsive img-circle' src='../images/default.jpg' alt='User Avatar'>";
								}else{
									echo "<img class='profile-user-img img-responsive img-circle' src='../foto_asesi/$pm[foto]' alt='User Avatar'>";
								}
								
							if(empty($pm['foto']) or empty($pm['ktp']) || empty($pm['kk']) || empty($pm['ijazah']) || empty($pm['transkrip'])){
								if (empty($pm['foto'])){
									$kelengkapan1="Foto ";
								}
								if(empty($pm['ktp'])){
									$kelengkapan2="KTP ";
								}
								if(empty($pm['kk'])){
									$kelengkapan3="KK ";
								}
								if(empty($pm['ijazah'])){
									$kelengkapan4="Ijazah ";
								}
								if(empty($pm['transkrip'])){
									$kelengkapan5="Transkrip";
								}
								$kelengkapan="Kurang ".$kelengkapan1.$kelengkapan2.$kelengkapan3.$kelengkapan4.$kelengkapan5;
								
							}else{
								$kelengkapan="Lengkap";

							}
							$sqlpendidikan="SELECT * FROM `pendidikan` WHERE `id`='$pm[pendidikan]'";
							$pendidikan=$conn->query($sqlpendidikan);
							$pe=$pendidikan->fetch_assoc();
							  echo "</div>
							  <!-- /.widget-user-image -->
							  <h3 class='widget-user-username'>$pm[nama]</h3>
							  <h5 class='widget-user-desc'>No. Pendaftaran : $pm[no_pendaftaran]</h5>
							  <h5 class='widget-user-desc'>Pendidikan Terakhir : $pe[jenjang_pendidikan]</h5>
							</div>
							<div class='box-footer'>
							  <ul class='nav nav-stacked'>
								<li>Asesmen diikuti <span class='pull-right badge'>$ikutasesmen Skema</span></li>
								<li>Dokumen Pokok <span class='pull-right badge'>$kelengkapan</span></li>
								<a href='?module=unggahfile&ida=$pm[no_pendaftaran]' class='btn btn-primary btn-xs'>Unggah/ Tambah Dokumen</a>

							  </ul>
							</div>
						  </div>
						  <!-- /.widget-user --></td>";
						
			
						echo "</td><td>";
						
						while ($ns=$sqlgetskkni->fetch_assoc()){
							$sqlgetskkni2=$conn->query("SELECT * FROM `skema_kkni` WHERE `id`='$ns[id_skemakkni]'");
							$ns2=$sqlgetskkni2->fetch_assoc();
							$sqlgetskkni3=$conn->query("SELECT * FROM `asesi_doc` WHERE `id_asesi`='$pm[no_pendaftaran]' AND `id_skemakkni`='$ns[id_skemakkni]'");
							$ns3=$sqlgetskkni3->num_rows;

							echo "<b>$ns2[judul]</b> ($ns2[kode_skema])<br>";
							if ($ns3==0){
								echo "Persyaratan : <span class='text-red'><b>Belum Melengkapi</b></span><br>";
							}else{
								echo "Persyaratan : <span class='text-green'><b>Telah mengunggah $ns3 Dokumen</b></span>
								<a href='?module=syarat&id=$ns[id_skemakkni]&ida=$pm[no_pendaftaran]' class='btn btn-info btn-xs' title='Lakukan Verifikasi/Persetujuan Dokumen yang diunggah Asesi'>Verifikasi</a><br>";
							}
						
							if ($ns['id_asesor']=='0'){
								echo "Ploting Asesor : <span class='text-red'><b>Belum</b></span><br>";

							}else{
								$tglasesmen=tgl_indo($ns['tgl_asesmen']);
								echo "Ploting Asesor : <span class='text-green'><b>Sudah</b></span><br>";
								echo "Tanggal Asesmen : <span class='text-green'><b>$tglasesmen</b></span><br>";

							}

						}
						echo "</td>";
					    echo "<td>
						      	<form name='frm' method='POST' role='form' enctype='multipart/form-data'>
							<input type='hidden' name='idhapus' value='$pm[id]'><input type='submit' name='hapusasesi' class='btn btn-danger btn-xs btn-block' title='Hapus $pm[nama]' value='Hapus'></form>";
							if (!empty($pm['nohp'])){
								echo "<a href='#myModalSMS".$pm['id']."' data-toggle='modal' data-id='".$pm['id']."' class='btn btn-xs btn-primary btn-block' title='Kirim pesan SMS ke $pm[nama] ($pm[nohp])'>Kirim SMS</a>";


							echo "<script>
								$(function(){
											$(document).on('click','.edit-record',function(e){
												e.preventDefault();
												$('#myModalSMS".$pm['id']."').modal('show');
											});
									});
							</script>
							<!-- Modal -->
								<form role='form' action='smsasesibaru.php' method='POST' enctype='multipart/form-data'>
								<div class='modal fade' id='myModalSMS".$pm['id']."' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
									<div class='modal-dialog'>
										<div class='modal-content'>
											<div class='modal-header'>
												<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Tutup</span></button>
												<h4 class='modal-title' id='myModalLabel'>Kirim SMS ke $pm[nama] (".$pm['nohp'].")</h4>
											</div>
											<div class='modal-body'>
												<input type='hidden' class='form-control' name='idpost' value='$pm[id]'/>
												<input type='hidden' class='form-control' name='nohp".$pm['id']."' value='$pm[nohp]'/>

												<div class='input-group'>
													<span class='input-group-addon'><i class='fa fa-envelope'></i></span>
													<textarea class='form-control' placeholder='Isi SMS'  name='pesan".$pm['id']."' rows='2' cols='80' maxlength='160'>Yth. $pm[nama], </textarea>
												</div>

											</div>
											<div class='modal-footer'>
												<div align='left' class='col-md-6 col-sm-6 col-xs-6'>
													<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>
												</div>
												<div align='right' class='col-md-6 col-sm-6 col-xs-6'>
													<button type='submit' class='btn btn-success' name='smsasesibaru'>Kirim SMS</button>
												</div>
											</div>
										</div>
									</div>
								</div>
								</form>

							<!-- Modal -->";

							}
							echo "</td></tr>";
						$no++;
					}
				echo "</tbody></table>
			</div>
              </div>
              <!-- /.tab-pane -->

            </div>
            <!-- /.tab-content -->
          </div>
          <!-- nav-tabs-custom -->	
		<script>
		function myURLFunction() {
		  /* Get the text field */
		  var copyText = document.getElementById('URLdoc');

		  /* Select the text field */
		  copyText.select();
		  copyText.setSelectionRange(0, 99999); /* For mobile devices */

		  /* Copy the text inside the text field */
		  document.execCommand('copy');

		  /* Alert the copied text */
		  alert('URL berhasil disalin: ' + copyText.value);
		}
		</script>		
			</div>
		  </div>
          
		</div>
	  </div>
	</section>";
  }
}

// Bagian Asesi LSP ==========================================================================================================
elseif ($_GET['module']=='asesithak'){
	if ($_SESSION['leveluser']=='pengusul'|| $_SESSION['leveluser']=='user'){
		if( isset( $_REQUEST['plotasesor'] ))
		{

		}
		if( isset( $_REQUEST['blokir'] ))
		{
			$sqlblokir="UPDATE `asesi` SET `blokir`='Y' WHERE `id`='$_POST[idblokir]'";
			$conn->query($sqlblokir);
		}
		if( isset( $_REQUEST['bukablokir'] ))
		{
			$sqlblokir="UPDATE `asesi` SET `blokir`='N' WHERE `id`='$_POST[idblokir]'";
			$conn->query($sqlblokir);
		}
		if( isset( $_REQUEST['hapusasesi'] ))
		{
			$sqlgetasesidata="SELECT * FROM `asesi` WHERE `id`='$_POST[idhapus]'";
			$getasesidata=$conn->query($sqlgetasesidata);
			$dtas=$getasesidata->fetch_assoc();
			$asesidata=$getasesidata->num_rows;
			if ($asesidata>0){
				// hapus data relevan
				// dokumen asesi
				$sqlgetdocasesi="SELECT * FROM `asesi_doc` WHERE `id_asesi`='$dtas[no_pendaftaran]'";
				$getdocasesi=$conn->query($sqlgetdocasesi);
				while ($docas=$getdocasesi->fetch_assoc()){
					unlink('../foto_asesi/'.$docas['file']);
					$sqlhapusdatadocasesi="DELETE FROM `asesi_doc` WHERE `id`='$docas[id]'";
					$conn->query($sqlhapusdatadocasesi);
						echo "<div class='alert alert-danger alert-dismissible'>
						<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
						<h4><i class='icon fa fa-check'></i> Hapus Data Dokumen Asesi Sukses</h4>
						Anda Telah Berhasil Menghapus <b>Dokumen Pendukung Asesi dengan ID $docas[id]</b></div>";

				}
				// data konfirmasi pembayaran
				$sqlhapusdatakonfirmbayar="DELETE FROM `asesi_pembayaran` WHERE `id_asesi`='$dtas[no_pendaftaran]'";
				$conn->query($sqlhapusdatakonfirmbayar);
					echo "<div class='alert alert-danger alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Hapus Data Konfirmasi Pembayaran Asesi Sukses</h4>
					Anda Telah Berhasil Menghapus <b>Data Konfirmasi Pembayaran Asesi</b></div>";

				// pendaftaran ke skema asesmen
				$sqlhapusdata1="DELETE FROM `asesi_asesmen` WHERE `id_asesi`='$dtas[no_pendaftaran]'";
				$conn->query($sqlhapusdata1);
					echo "<div class='alert alert-danger alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Hapus Data Pendaftaran Skema Asesi Sukses</h4>
					Anda Telah Berhasil Menghapus <b>Data Pendaftaran Asesi pada Skema Kompetensi</b></div>";

				// hapus data asesi
				$sqlblokir="DELETE FROM `asesi` WHERE `id`='$_POST[idhapus]'";
				$conn->query($sqlblokir);
					echo "<div class='alert alert-danger alert-dismissible'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<h4><i class='icon fa fa-check'></i> Hapus Data Asesi Sukses</h4>
					Anda Telah Berhasil Menghapus <b>Data Asesi dan Data-Data Pendukungnya</b></div>";

			}else{
				echo "<script>alert('Maaf Asesi tersebut Tidak Ditemukan');</script>";
			}
		}
		echo "


		<!-- Content Header (Page header) -->
		<section class='content-header'>
		  <h1>
			Asesi Sertifikasi Profesi
			<small>Data Asesi</small>
		  </h1>
		  <ol class='breadcrumb'>
			<li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
			<li class='active'>Data Asesi Sertifikasi Profesi</li>
		  </ol>
		</section>

		<!-- Main content -->
		<section class='content'>
		  <div class='row'>
			<div class='col-xs-12'>
			  <div class='box'>
				<div class='box-header'>
				  <h3 class='box-title'>Data Asesi Sertifikasi Profesi berdasarkan Tahun Angkatan</h3>
				<!-- Buat sebuah tombol Cancel untuk kemabli ke halaman awal / view data -->
				<a href='media.php?module=importasesilama' class='btn btn-success pull-right'>
					<span class='fa fa-upload'></span> Import Data Asesi Lama
				</a>

				</div>
				<!-- /.box-header -->
				<div class='box-body'>
					<div id='loading' class='col-xs-12 overlay'>
						<i class='fa fa-refresh fa-spin'></i>
					</div>
					<div style='overflow-x:auto;'>
					<table id='example7' class='table table-bordered table-striped'>
						<thead><tr><th>No</th><th>Tahun Angkatan</th><th>Daftar</th><th>Asesmen</th><th>Kompeten</th><th>Belum Kompeten</th><th>Tindak Lanjut</th><th>Sedang Proses</th></tr></thead>
						<tbody>";
						$no=1;
						$sqlwilasesi0="SELECT * FROM `asesi` ORDER BY `id` ASC";
						$wilasesi0=$conn->query($sqlwilasesi0);
						while ($was0=$wilasesi0->fetch_assoc()){
							/*if ($was0['tgl_daftar']=='0000-00-00'){
								$angkatan=substr($was0['no_pendaftaran'],0,4);
								$thndaftarnya=substr($was0['no_pendaftaran'],0,4);
								$blndaftarnya=substr($was0['no_pendaftaran'],4,2);
								$tgldaftarnya=substr($was0['no_pendaftaran'],6,2);
								$tgl_daftarx=$thndaftarnya."-".$blndaftarnya."-".$tgldaftarnya;
							}else{*/
								$angkatan=substr($was0['waktu'],0,4);
								$thndaftarnya=substr($was0['waktu'],0,4);
								$blndaftarnya=substr($was0['waktu'],4,2);
								$tgldaftarnya=substr($was0['waktu'],6,2);
								$tgl_daftarx=$thndaftarnya."-".$blndaftarnya."-".$tgldaftarnya;
							//}
							//$conn->query("UPDATE `asesi` SET `tgl_daftar`='$tgl_daftarx',`angkatan`='$angkatan' WHERE `id`='$was0[id]'");
							$conn->query("UPDATE `asesi` SET `angkatan`='$angkatan' WHERE `id`='$was0[id]'");
						}
						$sqlwilasesi="SELECT DISTINCT `angkatan` FROM `asesi` WHERE `id_pengusul`='$_SESSION[namauser]' ORDER BY `angkatan` DESC";
						$wilasesi=$conn->query($sqlwilasesi);
						while ($was=$wilasesi->fetch_assoc()){
							$sqljumaswil="SELECT `no_pendaftaran`,`angkatan` FROM `asesi` WHERE `angkatan`='$was[angkatan]' AND `id_pengusul`='$_SESSION[namauser]'";
							$jumaswil=$conn->query($sqljumaswil);
							$jumas=$jumaswil->num_rows;

							$sqlasesmen="SELECT DISTINCT `id_asesi` FROM `asesi_asesmen` WHERE `tgl_asesmen` LIKE '$was[angkatan]%'";
							$asesmen=$conn->query($sqlasesmen);
							$jumasesmen=0;
							while ($juma1=$asesmen->fetch_assoc()){
								$sqlcekpengusul="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$juma1[id_asesi]' AND `id_pengusul`='$_SESSION[namauser]'";
								$cekpengusul=$conn->query($sqlcekpengusul);
								$pengus1=$cekpengusul->num_rows;
								if ($pengus1>0){
									$jumasesmen=$jumasesmen+1;
								}else{
									$jumasesmen=$jumasesmen;
								}
							}

							$sqlasesmen2="SELECT DISTINCT `id_asesi` FROM `asesi_asesmen` WHERE `tgl_asesmen` LIKE '$was[angkatan]%' AND `status_asesmen`='K'";
							$asesmen2=$conn->query($sqlasesmen2);
							$jumasesmen2=0;
							
							while ($juma2=$asesmen2->fetch_assoc()){
								$sqlcekpengusul2="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$juma2[id_asesi]' AND `id_pengusul`='$_SESSION[namauser]'";
								$cekpengusul2=$conn->query($sqlcekpengusul2);
								$pengus2=$cekpengusul2->num_rows;
								if ($pengus2>0){
									$jumasesmen2=$jumasesmen2+1;
								}else{
									$jumasesmen2=$jumasesmen2;
								}
							}
							
							$sqlasesmen3="SELECT DISTINCT `id_asesi` FROM `asesi_asesmen` WHERE `tgl_asesmen` LIKE '$was[angkatan]%' AND `status_asesmen`='BK'";
							$asesmen3=$conn->query($sqlasesmen3);
							$jumasesmen3=0;
							
							while ($juma3=$asesmen3->fetch_assoc()){
								$sqlcekpengusul3="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$juma3[id_asesi]' AND `id_pengusul`='$_SESSION[namauser]'";
								$cekpengusul3=$conn->query($sqlcekpengusul3);
								$pengus3=$cekpengusul3->num_rows;
								if ($pengus3>0){
									$jumasesmen3=$jumasesmen3+1;
								}else{
									$jumasesmen3=$jumasesmen3;
								}
							}
							
							$sqlasesmen4="SELECT DISTINCT `id_asesi` FROM `asesi_asesmen` WHERE `tgl_asesmen` LIKE '$was[angkatan]%' AND `status_asesmen`='P'";
							$asesmen4=$conn->query($sqlasesmen4);
							$jumasesmen4=0;
							
							while ($juma4=$asesmen4->fetch_assoc()){
								$sqlcekpengusul4="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$juma4[id_asesi]' AND `id_pengusul`='$_SESSION[namauser]'";
								$cekpengusul4=$conn->query($sqlcekpengusul4);
								$pengus4=$cekpengusul4->num_rows;
								if ($pengus4>0){
									$jumasesmen4=$jumasesmen4+1;
								}else{
									$jumasesmen4=$jumasesmen4;
								}
							}
							
							$sqlasesmen5="SELECT DISTINCT `id_asesi` FROM `asesi_asesmen` WHERE `tgl_asesmen` LIKE '$was[angkatan]%' AND `status_asesmen`='TL'";
							$asesmen5=$conn->query($sqlasesmen5);
							$jumasesmen5=0;
							
							while ($juma5=$asesmen5->fetch_assoc()){
								$sqlcekpengusul5="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$juma5[id_asesi]' AND `id_pengusul`='$_SESSION[namauser]'";
								$cekpengusul5=$conn->query($sqlcekpengusul5);
								$pengus5=$cekpengusul5->num_rows;
								if ($pengus5>0){
									$jumasesmen5=$jumasesmen5+1;
								}else{
									$jumasesmen5=$jumasesmen5;
								}
							}

							if ($jumasesmen>$jumas){
								$ketjumas="<font color='red'>".$jumasesmen." (tidak sinkron)</font> <a href='media.php?module=asesmen' class='btn btn-primary btn-xs'><span class='glyphicon glyphicon-zoom-in' aria-hidden='true' title='Lihat Data'></span></a>";
							}else{
								$ketjumas=$jumasesmen;
							}
							echo "<tr class=gradeX><td>$no</td><td>$was[angkatan]</td><td>$jumas</td><td>$ketjumas</td><td>$jumasesmen2</td><td>$jumasesmen3</td><td>$jumasesmen5</td><td>$jumasesmen4</td></tr>";
							$no++;
						}
					echo "</tbody></table>
					</div>
					
				</div>
			  </div>
			  
			</div>
		  </div>
		</section>";
	}
}

// Bagian Asesi LSP ==========================================================================================================
elseif ($_GET['module']=='asesiprov'){
  if ($_SESSION['leveluser']=='pengusul'|| $_SESSION['leveluser']=='user'){
    if( isset( $_REQUEST['plotasesor'] ))
	{

	}
    if( isset( $_REQUEST['blokir'] ))
	{
		$sqlblokir="UPDATE `asesi` SET `blokir`='Y' WHERE `id`='$_POST[idblokir]'";
		$conn->query($sqlblokir);
	}
    if( isset( $_REQUEST['bukablokir'] ))
	{
		$sqlblokir="UPDATE `asesi` SET `blokir`='N' WHERE `id`='$_POST[idblokir]'";
		$conn->query($sqlblokir);
	}
    if( isset( $_REQUEST['hapusasesi'] ))
	{
		$sqlblokir="DELETE FROM `asesi` WHERE `id`='$_POST[idhapus]'";
		$conn->query($sqlblokir);
	}
    echo "


    <!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
        Asesi Sertifikasi Profesi
        <small>Data Asesi</small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li class='active'>Data Asesi Sertifikasi Profesi</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class='content'>
      <div class='row'>
        <div class='col-xs-12'>
          <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Data Asesi Sertifikasi Profesi berdasarkan Provinsi Asal</h3>
			<!-- Buat sebuah tombol Cancel untuk kemabli ke halaman awal / view data -->
			<a href='media.php?module=importasesilama' class='btn btn-success pull-right'>
				<span class='fa fa-upload'></span> Import Data Asesi Lama
			</a>

            </div>
            <!-- /.box-header -->
            <div class='box-body'>
				<div id='loading' class='col-xs-12 overlay'>
					<i class='fa fa-refresh fa-spin'></i>
				</div>
				<div style='overflow-x:auto;'>
				<table id='example7' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Wilayah Provinsi</th><th>Jumlah Asesi</th><th>Aksi</th></tr></thead>
					<tbody>";
					$no=1;
					$sqlwilasesi="SELECT DISTINCT `propinsi` FROM `asesi` WHERE `id_pengusul`='$_SESSION[namauser]' ORDER BY `propinsi` ASC";
					$wilasesi=$conn->query($sqlwilasesi);
					while ($was=$wilasesi->fetch_assoc()){
						$sqljumaswil="SELECT * FROM `asesi` WHERE `propinsi`='$was[propinsi]' AND `id_pengusul`='$_SESSION[namauser]'";
						$jumaswil=$conn->query($sqljumaswil);
						$jumas=$jumaswil->num_rows;
						$sqldatawil="SELECT * FROM `data_wilayah` WHERE `id_wil`='$was[propinsi]'";
						$datawil=$conn->query($sqldatawil);
						$nmwil=$datawil->fetch_assoc();
						$namawilayah=trim($nmwil['nm_wil']);
						echo "<tr class=gradeX><td>$no</td><td>$namawilayah</td><td>$jumas</td><td><a href='?module=asesibypropinsi&wil=$was[propinsi]' class='btn btn-primary btn-xs'>Detail</a></td></tr>";
						$no++;
					}
				echo "</tbody></table>
				</div>
				
			</div>
		  </div>
          
		</div>
	  </div>
	</section>";
  }
}

// Bagian Asesi LSP ==========================================================================================================
elseif ($_GET['module']=='asesikab'){
  if ($_SESSION['leveluser']=='pengusul'|| $_SESSION['leveluser']=='user'){
    if( isset( $_REQUEST['plotasesor'] ))
	{

	}
    if( isset( $_REQUEST['blokir'] ))
	{
		$sqlblokir="UPDATE `asesi` SET `blokir`='Y' WHERE `id`='$_POST[idblokir]'";
		$conn->query($sqlblokir);
	}
    if( isset( $_REQUEST['bukablokir'] ))
	{
		$sqlblokir="UPDATE `asesi` SET `blokir`='N' WHERE `id`='$_POST[idblokir]'";
		$conn->query($sqlblokir);
	}
    if( isset( $_REQUEST['hapusasesi'] ))
	{
		$sqlblokir="DELETE FROM `asesi` WHERE `id`='$_POST[idhapus]'";
		$conn->query($sqlblokir);
	}
    echo "


    <!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
        Asesi Sertifikasi Profesi
        <small>Data Asesi</small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li class='active'>Data Asesi Sertifikasi Profesi</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class='content'>
      <div class='row'>
        <div class='col-xs-12'>
          <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Data Asesi Sertifikasi Profesi berdasarkan Kota/ Kabupaten Asal</h3>
			<!-- Buat sebuah tombol Cancel untuk kemabli ke halaman awal / view data -->
			<a href='media.php?module=importasesilama' class='btn btn-success pull-right'>
				<span class='fa fa-upload'></span> Import Data Asesi Lama
			</a>

            </div>
            <!-- /.box-header -->
            <div class='box-body'>
				<div id='loading' class='col-xs-12 overlay'>
					<i class='fa fa-refresh fa-spin'></i>
				</div>
				<div style='overflow-x:auto;'>
				<table id='example8' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Wilayah Kota/Kabupaten</th><th>Jumlah Asesi</th><th>Aksi</th></tr></thead>
					<tbody>";
					$no2=1;
					$sqlwilasesi2="SELECT DISTINCT `kota` FROM `asesi` WHERE `id_pengusul`='$_SESSION[namauser]' ORDER BY `kota` ASC";
					$wilasesi2=$conn->query($sqlwilasesi2);
					while ($was2=$wilasesi2->fetch_assoc()){
						$sqljumaswil2="SELECT * FROM `asesi` WHERE `kota`='$was2[kota]' AND `id_pengusul`='$_SESSION[namauser]'";
						$jumaswil2=$conn->query($sqljumaswil2);
						$jumas2=$jumaswil2->num_rows;
						$sqldatawil2="SELECT * FROM `data_wilayah` WHERE `id_wil`='$was2[kota]'";
						$datawil2=$conn->query($sqldatawil2);
						$nmwil2=$datawil2->fetch_assoc();
						$namawilayah2=trim($nmwil2['nm_wil']);
						echo "<tr class=gradeX><td>$no2</td><td>$namawilayah2</td><td>$jumas2</td><td></td></tr>";
						$no2++;
					}
				echo "</tbody></table>
				</div>
				
			</div>
		  </div>
          
		</div>
	  </div>
	</section>";
  }
}

// Bagian Asesi LSP ==========================================================================================================
elseif ($_GET['module']=='asesik'){
  if ($_SESSION['leveluser']=='pengusul'|| $_SESSION['leveluser']=='user'){
    if( isset( $_REQUEST['plotasesor'] ))
	{

	}
    if( isset( $_REQUEST['blokir'] ))
	{
		$sqlblokir="UPDATE `asesi` SET `blokir`='Y' WHERE `id`='$_POST[idblokir]'";
		$conn->query($sqlblokir);
	}
    if( isset( $_REQUEST['bukablokir'] ))
	{
		$sqlblokir="UPDATE `asesi` SET `blokir`='N' WHERE `id`='$_POST[idblokir]'";
		$conn->query($sqlblokir);
	}
    if( isset( $_REQUEST['hapusasesi'] ))
	{
		$sqlblokir="DELETE FROM `asesi` WHERE `id`='$_POST[idhapus]'";
		$conn->query($sqlblokir);
	}
    echo "


    <!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
        Asesi Sertifikasi Profesi
        <small>Data Asesi</small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li class='active'>Data Asesi Sertifikasi Profesi Kompeten</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class='content'>
      <div class='row'>
        <div class='col-xs-12'>
          <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Data Asesi Sertifikasi Profesi</h3>
			<!-- Buat sebuah tombol Cancel untuk kemabli ke halaman awal / view data -->
			<a href='media.php?module=importasesilama' class='btn btn-success pull-right'>
				<span class='fa fa-upload'></span> Import Data Asesi Lama
			</a>

            </div>
            <!-- /.box-header -->
            <div class='box-body'>
				<div id='loading' class='col-xs-12 overlay'>
					<h5><center><em>Sedang melakukan proses data</em></center></h5><i class='fa fa-refresh fa-spin'></i>
				</div>
				<div style='overflow-x:auto;'>
				<table id='example5' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Identitas Asesi</th><th>Asesmen Skema Sertifikasi</th><th>Aksi</th></tr></thead>
					<tbody>";
					$no=1;
					$sqllsp="SELECT DISTINCT `id_asesi` FROM `asesi_asesmen` WHERE `status_asesmen`='K' ORDER BY `id_asesi` DESC";
					$lsp=$conn->query($sqllsp);
					while ($pm=$lsp->fetch_assoc()){
						$sqlgetskkni=$conn->query("SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$pm[id_asesi]' AND `status_asesmen`='K' ORDER BY `id` DESC");
						$ikutasesmen=$sqlgetskkni->num_rows;
						$sqlgetasesi="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$pm[id_asesi]' AND `id_pengusul`='$_SESSION[namauser]'";
						$getasesi=$conn->query($sqlgetasesi);
						while ($pma=$getasesi->fetch_assoc()){
							echo "<tr class=gradeX><td>$no</td><td>
							  <div class='box box-widget widget-user-2'>
								<!-- Add the bg color to the header using any of the bg-* classes -->
								<div class='widget-user-header bg-green'>
								  <div class='widget-user-image'>";
									if ($pma['foto']==''){
										echo "<img class='profile-user-img img-responsive img-circle' src='../images/default.jpg' alt='User Avatar'>";
									}else{
										echo "<img class='profile-user-img img-responsive img-circle' src='../foto_asesi/$pma[foto]' alt='User Avatar'>";
									}
									
								if(empty($pma['foto']) or empty($pma['ktp']) || empty($pma['kk']) || empty($pma['ijazah']) || empty($pma['transkrip'])){
									if (empty($pma['foto'])){
										$kelengkapan1="Foto ";
									}
									if(empty($pma['ktp'])){
										$kelengkapan2="KTP ";
									}
									if(empty($pma['kk'])){
										$kelengkapan3="KK ";
									}
									if(empty($pma['ijazah'])){
										$kelengkapan4="Ijazah ";
									}
									if(empty($pma['transkrip'])){
										$kelengkapan5="Transkrip";
									}
									$kelengkapan="Kurang ".$kelengkapan1.$kelengkapan2.$kelengkapan3.$kelengkapan4.$kelengkapan5;
									
								}else{
									$kelengkapan="Lengkap";

								}
								$sqlpendidikan="SELECT * FROM `pendidikan` WHERE `id`='$pma[pendidikan]'";
								$pendidikan=$conn->query($sqlpendidikan);
								$pe=$pendidikan->fetch_assoc();
								  echo "</div>
								  <!-- /.widget-user-image -->
								  <h3 class='widget-user-username'>$pma[nama]</h3>
								  <h5 class='widget-user-desc'>No. Pendaftaran : $pma[no_pendaftaran]</h5>
								  <h5 class='widget-user-desc'>Pendidikan Terakhir : $pe[jenjang_pendidikan]</h5>
								</div>
								<div class='box-footer'>
								  <ul class='nav nav-stacked'>
									<li>Asesmen diikuti <span class='pull-right badge'>$ikutasesmen Skema</span></li>
									<li>Dokumen Pokok <span class='pull-right badge'>$kelengkapan</span></li>

								  </ul>
								</div>
							  </div>
							  <!-- /.widget-user --></td>";
							
				
							echo "</td><td>";
							
							while ($ns=$sqlgetskkni->fetch_assoc()){
								$sqlgetskkni2=$conn->query("SELECT * FROM `skema_kkni` WHERE `id`='$ns[id_skemakkni]'");
								$ns2=$sqlgetskkni2->fetch_assoc();
								$sqlgetskkni3=$conn->query("SELECT * FROM `asesi_doc` WHERE `id_asesi`='$pma[no_pendaftaran]' AND `id_skemakkni`='$ns[id_skemakkni]'");
								$ns3=$sqlgetskkni3->num_rows;

								echo "<b>$ns2[judul]</b> ($ns2[kode_skema])<br>";
								if ($ns3==0){
									echo "Persyaratan : <span class='text-red'><b>Belum Melengkapi</b></span><br>";
								}else{
									echo "Persyaratan : <span class='text-green'><b>Telah mengunggah $ns3 Dokumen</b></span><br>";
								}
							
								if ($ns['id_asesor']=='0'){
									echo "Ploting Asesor : <span class='text-red'><b>Belum</b></span><br>";

								}else{
									$tglasesmen=tgl_indo($ns['tgl_asesmen']);
									echo "Ploting Asesor : <span class='text-green'><b>Sudah</b></span><br>";
									echo "Tanggal Asesmen : <span class='text-green'><b>$tglasesmen</b></span><br>";

								}
								if ($ns['no_lisensi']=='' && $ns['no_serisertifikat']==''){
									echo "Sertifikat : <span class='text-red'><b>Belum Dicetak</b></span>&nbsp;";
									//echo "<a href='#myModalK".$ns['id']."' class='btn btn-primary btn-xs' data-toggle='modal' data-id='".$ns['id']."' title='Perbarui dan unggah scan dokumen sertifikat Asesi'>Perbarui dan Unggah Sertifikat</a><br>";

								}else{
									echo "No. Sertifikat : <span class='text-green'><b>$ns[no_lisensi]</b></span><br>";
									echo "No. Seri Sertifikat : <span class='text-green'><b>$ns[no_serisertifikat]</b></span> ";
									echo "Dokumen Sertifikat: <a href='#myModalCertK".$ns['id']."' class='btn btn-primary btn-xs' data-toggle='modal' data-id='".$ns['id']."' title='Lihat scan dokumen sertifikat Asesi'>Lihat Sertifikat</a><br>";

								}
								


								echo "<script>
									$(function(){
												$(document).on('click','.edit-record',function(e){
													e.preventDefault();
													$('#myModalK".$ns['id']."').modal('show');
												});
										});
								</script>
								<!-- Modal -->
									<div class='modal fade' id='myModalK".$ns['id']."' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
										<div class='modal-dialog'>
											<div class='modal-content'>
												<div class='modal-header'>
													<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Tutup</span></button>
													<h4 class='modal-title' id='myModalLabel'>Dokumen Sertifikat ".$ns['no_lisensi']."</h4>
												</div>
												<form role='form' method='POST' action='uploadcert.php' enctype='multipart/form-data'>
												<div class='modal-body'>
														<div class='col-md-12'>
															<div class='form-group'>
																<label>Nomor Seri Sertifikat</label><br>
																<input type='text' class='form-control' name='no_serisertifikat".$ns['id']."'/>
																<input type='hidden' class='form-control' name='idpost' value='$ns[id]'/>
															</div>
														</div>
														<div class='col-md-12'>
															<div class='form-group'>
																<label>Nomor Sertifikat/ Lisensi</label><br>
																<input type='text' class='form-control' name='no_lisensi".$ns['id']."'/>
															</div>
														</div>
														<div class='col-md-12'>
															<div class='form-group'>
																<label>Masa Berlaku</label><br>
																<input type='date' class='form-control' name='masa_berlaku".$ns['id']."'/>
															</div>
														</div>
														<div class='col-md-12'>
															<div class='form-group'>
																<label>Unggah Sertifikat</label>
																<input type='file' name='file".$ns['id']."'/><br>
															</div>
														</div>

												</div>
												<div class='modal-footer'>
													<div align='left' class='col-md-6 col-sm-6 col-xs-6'>
														<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>
													</div>
													<div align='right' class='col-md-6 col-sm-6 col-xs-6'>
														<button type='submit' class='btn btn-success' name='simpancert'>Simpan</button>
													</div>
													
												</div>
												</form>

											</div>
											</div>
									</div>
								<!-- Modal -->";

								echo "<script>
									$(function(){
												$(document).on('click','.edit-record',function(e){
													e.preventDefault();
													$('#myModalCertK".$ns['id']."').modal('show');
												});
										});
								</script>
								<!-- Modal -->
									<div class='modal fade' id='myModalCertK".$ns['id']."' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
										<div class='modal-dialog'>
											<div class='modal-content'>
												<div class='modal-header'>
													<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Tutup</span></button>
													<h4 class='modal-title' id='myModalLabel'>Dokumen Sertifikat ".$ns['no_lisensi']."</h4>
												</div>
												<div class='modal-body'><embed src='../foto_asesicert/$ns[foto_sertifikat]' width='100%'/>
												</div>
												<div class='modal-footer'>
													<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>
												</div>
											</div>
											</div>
									</div>
								<!-- Modal -->";


							}
							echo "</td>";
							echo "<td><form name='frm' method='POST' role='form' enctype='multipart/form-data'>
								<input type='hidden' name='idblokir' value='$pma[id]'><input type='submit' name='blokir' class='btn btn-warning btn-xs btn-block' title='Blokir akses $pma[nama]' value='Blokir'></form>
								</td></tr>";
							$no++;
						}
					}
				echo "</tbody></table>
				</div>
				
			</div>
		  </div>
          
		</div>
	  </div>
	</section>";
  }
}

// Bagian Asesi LSP ==========================================================================================================
elseif ($_GET['module']=='asesibk'){
  if ($_SESSION['leveluser']=='pengusul'|| $_SESSION['leveluser']=='user'){
    if( isset( $_REQUEST['plotasesor'] ))
	{

	}
    if( isset( $_REQUEST['blokir'] ))
	{
		$sqlblokir="UPDATE `asesi` SET `blokir`='Y' WHERE `id`='$_POST[idblokir]'";
		$conn->query($sqlblokir);
	}
    if( isset( $_REQUEST['bukablokir'] ))
	{
		$sqlblokir="UPDATE `asesi` SET `blokir`='N' WHERE `id`='$_POST[idblokir]'";
		$conn->query($sqlblokir);
	}
    if( isset( $_REQUEST['hapusasesi'] ))
	{
		$sqlblokir="DELETE FROM `asesi` WHERE `id`='$_POST[idhapus]'";
		$conn->query($sqlblokir);
	}
    echo "


    <!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
        Asesi Sertifikasi Profesi
        <small>Data Asesi</small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li class='active'>Data Asesi Sertifikasi Profesi</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class='content'>
      <div class='row'>
        <div class='col-xs-12'>
          <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Data Asesi Sertifikasi Profesi Belum Kompeten</h3>
			<!-- Buat sebuah tombol Cancel untuk kemabli ke halaman awal / view data -->
			<a href='media.php?module=importasesilama' class='btn btn-success pull-right'>
				<span class='fa fa-upload'></span> Import Data Asesi Lama
			</a>

            </div>
            <!-- /.box-header -->
            <div class='box-body'>
				<div id='loading' class='col-xs-12 overlay'>
					<h5><center><em>Sedang melakukan proses data</em></center></h5><i class='fa fa-refresh fa-spin'></i>
				</div>
				<div style='overflow-x:auto;'>
				<table id='example6' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Identitas Asesi</th><th>Asesmen Skema Sertifikasi</th><th>Aksi</th></tr></thead>
					<tbody>";
					$no=1;
					$sqllsp="SELECT DISTINCT `id_asesi` FROM `asesi_asesmen` WHERE `status_asesmen`='BK' ORDER BY `id_asesi` DESC";
					$lsp=$conn->query($sqllsp);
					while ($pm=$lsp->fetch_assoc()){
						$sqlgetskkni=$conn->query("SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$pm[id_asesi]' AND `status_asesmen`='BK' ORDER BY `id` DESC");
						$ikutasesmen=$sqlgetskkni->num_rows;
						$sqlgetasesi="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$pm[id_asesi]' AND `id_pengusul`='$_SESSION[namauser]'";
						$getasesi=$conn->query($sqlgetasesi);
						while ($pma=$getasesi->fetch_assoc()){
							echo "<tr class=gradeX><td>$no</td><td>
							  <div class='box box-widget widget-user-2'>
								<!-- Add the bg color to the header using any of the bg-* classes -->
								<div class='widget-user-header bg-orange'>
								  <div class='widget-user-image'>";
									if ($pma['foto']==''){
										echo "<img class='profile-user-img img-responsive img-circle' src='../images/default.jpg' alt='User Avatar'>";
									}else{
										echo "<img class='profile-user-img img-responsive img-circle' src='../foto_asesi/$pma[foto]' alt='User Avatar'>";
									}
									
								if(empty($pma['foto']) or empty($pma['ktp']) || empty($pma['kk']) || empty($pma['ijazah']) || empty($pma['transkrip'])){
									if (empty($pma['foto'])){
										$kelengkapan1="Foto ";
									}
									if(empty($pma['ktp'])){
										$kelengkapan2="KTP ";
									}
									if(empty($pma['kk'])){
										$kelengkapan3="KK ";
									}
									if(empty($pma['ijazah'])){
										$kelengkapan4="Ijazah ";
									}
									if(empty($pma['transkrip'])){
										$kelengkapan5="Transkrip";
									}
									$kelengkapan="Kurang ".$kelengkapan1.$kelengkapan2.$kelengkapan3.$kelengkapan4.$kelengkapan5;
									
								}else{
									$kelengkapan="Lengkap";

								}
								$sqlpendidikan="SELECT * FROM `pendidikan` WHERE `id`='$pma[pendidikan]'";
								$pendidikan=$conn->query($sqlpendidikan);
								$pe=$pendidikan->fetch_assoc();
								  echo "</div>
								  <!-- /.widget-user-image -->
								  <h3 class='widget-user-username'>$pma[nama]</h3>
								  <h5 class='widget-user-desc'>No. Pendaftaran : $pma[no_pendaftaran]</h5>
								  <h5 class='widget-user-desc'>Pendidikan Terakhir : $pe[jenjang_pendidikan]</h5>
								</div>
								<div class='box-footer'>
								  <ul class='nav nav-stacked'>
									<li>Asesmen diikuti <span class='pull-right badge'>$ikutasesmen Skema</span></li>
									<li>Dokumen Pokok <span class='pull-right badge'>$kelengkapan</span></li>

								  </ul>
								</div>
							  </div>
							  <!-- /.widget-user --></td>";
							
				
							echo "</td><td>";
							
							while ($ns=$sqlgetskkni->fetch_assoc()){
								$sqlgetskkni2=$conn->query("SELECT * FROM `skema_kkni` WHERE `id`='$ns[id_skemakkni]'");
								$ns2=$sqlgetskkni2->fetch_assoc();
								$sqlgetskkni3=$conn->query("SELECT * FROM `asesi_doc` WHERE `id_asesi`='$pma[no_pendaftaran]' AND `id_skemakkni`='$ns[id_skemakkni]'");
								$ns3=$sqlgetskkni3->num_rows;

								echo "<b>$ns2[judul]</b> ($ns2[kode_skema])<br>";
								if ($ns3==0){
									echo "Persyaratan : <span class='text-red'><b>Belum Melengkapi</b></span><br>";
								}else{
									echo "Persyaratan : <span class='text-green'><b>Telah mengunggah $ns3 Dokumen</b></span>
									<a href='?module=syarat&id=$ns[id_skemakkni]&ida=$pma[no_pendaftaran]' class='btn btn-info btn-xs' title='Lakukan Verifikasi/Persetujuan Dokumen yang diunggah Asesi'>Verifikasi</a><br>";
								}
							
								if ($ns['id_asesor']=='0'){
									echo "Ploting Asesor : <span class='text-red'><b>Belum</b></span><br>";

								}else{
									$tglasesmen=tgl_indo($ns['tgl_asesmen']);
									echo "Ploting Asesor : <span class='text-green'><b>Sudah</b></span><br>";
									echo "Tanggal Asesmen : <span class='text-green'><b>$tglasesmen</b></span><br>";

								}
							}
							echo "</td>";
							echo "<td><form name='frm' method='POST' role='form' enctype='multipart/form-data'>
								<input type='hidden' name='idblokir' value='$pma[id]'><input type='submit' name='blokir' class='btn btn-warning btn-xs btn-block' title='Blokir akses $pma[nama]' value='Blokir'></form>
								</td></tr>";
							$no++;
						}
					}
				echo "</tbody></table>
				</div>
				
			</div>
		  </div>
          
		</div>
	  </div>
	</section>";
  }
}

// Bagian Asesi LSP ==========================================================================================================
elseif ($_GET['module']=='asesibv'){
  if ($_SESSION['leveluser']=='pengusul'|| $_SESSION['leveluser']=='user'){
    if( isset( $_REQUEST['plotasesor'] ))
	{

	}
    if( isset( $_REQUEST['blokir'] ))
	{
		$sqlblokir="UPDATE `asesi` SET `blokir`='Y' WHERE `id`='$_POST[idblokir]'";
		$conn->query($sqlblokir);
	}
    if( isset( $_REQUEST['bukablokir'] ))
	{
		$sqlblokir="UPDATE `asesi` SET `blokir`='N' WHERE `id`='$_POST[idblokir]'";
		$conn->query($sqlblokir);
	}
    if( isset( $_REQUEST['hapusasesi'] ))
	{
		$sqlblokir="DELETE FROM `asesi` WHERE `id`='$_POST[idhapus]'";
		$conn->query($sqlblokir);
	}
    echo "


    <!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
        Asesi Sertifikasi Profesi
        <small>Data Asesi</small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li class='active'>Data Asesi Sertifikasi Profesi</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class='content'>
      <div class='row'>
        <div class='col-xs-12'>
          <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Data Asesi Sertifikasi Profesi Belum Verifikasi</h3>
			<!-- Buat sebuah tombol Cancel untuk kemabli ke halaman awal / view data -->
			<a href='media.php?module=importasesilama' class='btn btn-success pull-right'>
				<span class='fa fa-upload'></span> Import Data Asesi Lama
			</a>

            </div>
            <!-- /.box-header -->
            <div class='box-body'>
				<div id='loading' class='col-xs-12 overlay'>
					<h5><center><em>Sedang melakukan proses data</em></center></h5><i class='fa fa-refresh fa-spin'></i>
				</div>
				<div style='overflow-x:auto;'>
				<table id='example7' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Identitas Asesi</th><th>Asesmen Skema Sertifikasi</th><th>Aksi</th></tr></thead>
					<tbody>";
					$no=1;
					$sqllsp="SELECT * FROM `asesi` WHERE `verifikasi`='P' AND `blokir`='N' AND `id_pengusul`='$_SESSION[namauser]' ORDER BY `nama` ASC";
					$lsp=$conn->query($sqllsp);
					while ($pm=$lsp->fetch_assoc()){
						$sqlcekstatusasesmen="SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$pm[no_pendaftaran]' AND `status`='P'";
						$cekstatusasesmen=$conn->query($sqlcekstatusasesmen);
						while ($csa=$cekstatusasesmen->fetch_assoc()){
							$sqlgetskkni=$conn->query("SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$csa[id_asesi]' ORDER BY `id` DESC");
							$ikutasesmen=$sqlgetskkni->num_rows;
							echo "<tr class=gradeX><td>$no</td><td>
							  <div class='box box-widget widget-user-2'>
								<!-- Add the bg color to the header using any of the bg-* classes -->
								<div class='widget-user-header bg-aqua'>
								  <div class='widget-user-image'>";
									if ($pm['foto']==''){
										echo "<img class='profile-user-img img-responsive img-circle' src='../images/default.jpg' alt='User Avatar'>";
									}else{
										echo "<img class='profile-user-img img-responsive img-circle' src='../foto_asesi/$pm[foto]' alt='User Avatar'>";
									}
									
								if(empty($pm['foto']) or empty($pm['ktp']) || empty($pm['kk']) || empty($pm['ijazah']) || empty($pm['transkrip'])){
									if (empty($pm['foto'])){
										$kelengkapan1="Foto ";
									}
									if(empty($pm['ktp'])){
										$kelengkapan2="KTP ";
									}
									if(empty($pm['kk'])){
										$kelengkapan3="KK ";
									}
									if(empty($pm['ijazah'])){
										$kelengkapan4="Ijazah ";
									}
									if(empty($pm['transkrip'])){
										$kelengkapan5="Transkrip";
									}
									$kelengkapan="Kurang ".$kelengkapan1.$kelengkapan2.$kelengkapan3.$kelengkapan4.$kelengkapan5;
									
								}else{
									$kelengkapan="Lengkap";

								}
								$sqlpendidikan="SELECT * FROM `pendidikan` WHERE `id`='$pm[pendidikan]'";
								$pendidikan=$conn->query($sqlpendidikan);
								$pe=$pendidikan->fetch_assoc();
								  echo "</div>
								  <!-- /.widget-user-image -->
								  <h3 class='widget-user-username'>$pm[nama]</h3>
								  <h5 class='widget-user-desc'>No. Pendaftaran : $pm[no_pendaftaran]</h5>
								  <h5 class='widget-user-desc'>Pendidikan Terakhir : $pe[jenjang_pendidikan]</h5>
								</div>
								<div class='box-footer'>
								  <ul class='nav nav-stacked'>
									<li>Asesmen diikuti <span class='pull-right badge'>$ikutasesmen Skema</span></li>
									<li>Dokumen Pokok <span class='pull-right badge'>$kelengkapan</span></li>

								  </ul>
								</div>
							  </div>
							  <!-- /.widget-user --></td>";
							
				
							echo "</td><td>";
							
							while ($ns=$sqlgetskkni->fetch_assoc()){
								$sqlgetskkni2=$conn->query("SELECT * FROM `skema_kkni` WHERE `id`='$ns[id_skemakkni]'");
								$ns2=$sqlgetskkni2->fetch_assoc();
								$sqlgetskkni3=$conn->query("SELECT * FROM `asesi_doc` WHERE `id_asesi`='$pm[no_pendaftaran]' AND `id_skemakkni`='$ns[id_skemakkni]'");
								$ns3=$sqlgetskkni3->num_rows;

								echo "<b>$ns2[judul]</b> ($ns2[kode_skema])<br>";
								if ($ns3==0){
									echo "Persyaratan : <span class='text-red'><b>Belum Melengkapi</b></span><br>";
								}else{
									echo "Persyaratan : <span class='text-green'><b>Telah mengunggah $ns3 Dokumen</b></span>
									<a href='?module=syarat&id=$ns[id_skemakkni]&ida=$pm[no_pendaftaran]' class='btn btn-info btn-xs' title='Lakukan Verifikasi/Persetujuan Dokumen yang diunggah Asesi'>Verifikasi</a><br>";
								}
							
								if ($ns['id_asesor']=='0'){
									echo "Ploting Asesor : <span class='text-red'><b>Belum</b></span><br>";

								}else{
									$tglasesmen=tgl_indo($ns['tgl_asesmen']);
									echo "Ploting Asesor : <span class='text-green'><b>Sudah</b></span><br>";
									echo "Tanggal Asesmen : <span class='text-green'><b>$tglasesmen</b></span><br>";

								}

							}
							echo "</td>";
							echo "<td><form name='frm' method='POST' role='form' enctype='multipart/form-data'>
								<input type='hidden' name='idblokir' value='$pm[id]'><input type='submit' name='blokir' class='btn btn-warning btn-xs btn-block' title='Blokir akses $pm[nama]' value='Blokir'></form>
								  <form name='frm' method='POST' role='form' enctype='multipart/form-data'>
								<input type='hidden' name='idhapus' value='$pm[id]'><input type='submit' name='hapusasesi' class='btn btn-danger btn-xs btn-block' title='Hapus $pm[nama]' value='Hapus'></form>

								</td></tr>";
							$no++;
						}
					}
				echo "</tbody></table>
			</div>
				
			</div>
		  </div>
          
		</div>
	  </div>
	</section>";
  }
}

// Bagian Asesi LSP ==========================================================================================================
elseif ($_GET['module']=='asesiv'){
  if ($_SESSION['leveluser']=='pengusul'|| $_SESSION['leveluser']=='user'){
    if( isset( $_REQUEST['plotasesor'] ))
	{

	}
    if( isset( $_REQUEST['blokir'] ))
	{
		$sqlblokir="UPDATE `asesi` SET `blokir`='Y' WHERE `id`='$_POST[idblokir]'";
		$conn->query($sqlblokir);
	}
    if( isset( $_REQUEST['bukablokir'] ))
	{
		$sqlblokir="UPDATE `asesi` SET `blokir`='N' WHERE `id`='$_POST[idblokir]'";
		$conn->query($sqlblokir);
	}
    if( isset( $_REQUEST['hapusasesi'] ))
	{
		$sqlblokir="DELETE FROM `asesi` WHERE `id`='$_POST[idhapus]'";
		$conn->query($sqlblokir);
	}
    echo "


    <!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
        Asesi Sertifikasi Profesi
        <small>Data Asesi</small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li class='active'>Data Asesi Sertifikasi Profesi</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class='content'>
      <div class='row'>
        <div class='col-xs-12'>
          <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Data Asesi Sertifikasi Profesi Terverifikasi</h3>
			<!-- Buat sebuah tombol Cancel untuk kemabli ke halaman awal / view data -->
			<a href='media.php?module=importasesilama' class='btn btn-success pull-right'>
				<span class='fa fa-upload'></span> Import Data Asesi Lama
			</a>

            </div>
            <!-- /.box-header -->
            <div class='box-body'>
				<div id='loading' class='col-xs-12 overlay'>
					<i class='fa fa-refresh fa-spin'></i>
				</div>
				<div style='overflow-x:auto;'>
				<table id='example3' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Identitas Asesi</th><th>Asesmen Skema Sertifikasi</th><th>Aksi</th></tr></thead>
					<tbody>";
					$no=1;
					$sqllsp="SELECT * FROM `asesi` WHERE `blokir`='N' AND `id_pengusul`='$_SESSION[namauser]' ORDER BY `nama` ASC";
					$lsp=$conn->query($sqllsp);
					while ($pm=$lsp->fetch_assoc()){
						$sqlcekstatusasesmen="SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$pm[no_pendaftaran]' AND `status`!='P'";
						$cekstatusasesmen=$conn->query($sqlcekstatusasesmen);
						while ($csa=$cekstatusasesmen->fetch_assoc()){
							$sqlgetskkni=$conn->query("SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$csa[id_asesi]' ORDER BY `id` DESC");
							$ikutasesmen=$sqlgetskkni->num_rows;
							echo "<tr class=gradeX><td>$no</td><td>
							  <div class='box box-widget widget-user-2'>
								<!-- Add the bg color to the header using any of the bg-* classes -->
								<div class='widget-user-header bg-green'>
								  <div class='widget-user-image'>";
									if ($pm['foto']==''){
										echo "<img class='profile-user-img img-responsive img-circle' src='../images/default.jpg' alt='User Avatar'>";
									}else{
										echo "<img class='profile-user-img img-responsive img-circle' src='../foto_asesi/$pm[foto]' alt='User Avatar'>";
									}
									
								if(empty($pm['foto']) or empty($pm['ktp']) || empty($pm['kk']) || empty($pm['ijazah']) || empty($pm['transkrip'])){
									if (empty($pm['foto'])){
										$kelengkapan1="Foto ";
									}
									if(empty($pm['ktp'])){
										$kelengkapan2="KTP ";
									}
									if(empty($pm['kk'])){
										$kelengkapan3="KK ";
									}
									if(empty($pm['ijazah'])){
										$kelengkapan4="Ijazah ";
									}
									if(empty($pm['transkrip'])){
										$kelengkapan5="Transkrip";
									}
									$kelengkapan="Kurang ".$kelengkapan1.$kelengkapan2.$kelengkapan3.$kelengkapan4.$kelengkapan5;
									
								}else{
									$kelengkapan="Lengkap";

								}
								$sqlpendidikan="SELECT * FROM `pendidikan` WHERE `id`='$pm[pendidikan]'";
								$pendidikan=$conn->query($sqlpendidikan);
								$pe=$pendidikan->fetch_assoc();
								  echo "</div>
								  <!-- /.widget-user-image -->
								  <h3 class='widget-user-username'>$pm[nama]</h3>
								  <h5 class='widget-user-desc'>No. Pendaftaran : $pm[no_pendaftaran]</h5>
								  <h5 class='widget-user-desc'>Pendidikan Terakhir : $pe[jenjang_pendidikan]</h5>
								</div>
								<div class='box-footer'>
								  <ul class='nav nav-stacked'>
									<li>Asesmen diikuti <span class='pull-right badge'>$ikutasesmen Skema</span></li>
									<li>Dokumen Pokok <span class='pull-right badge'>$kelengkapan</span></li>

								  </ul>
								</div>
							  </div>
							  <!-- /.widget-user --></td>";
							
				
							echo "</td><td>";
							
							while ($ns=$sqlgetskkni->fetch_assoc()){
								$sqlgetskkni2=$conn->query("SELECT * FROM `skema_kkni` WHERE `id`='$ns[id_skemakkni]'");
								$ns2=$sqlgetskkni2->fetch_assoc();
								$sqlgetskkni3=$conn->query("SELECT * FROM `asesi_doc` WHERE `id_asesi`='$pm[no_pendaftaran]' AND `id_skemakkni`='$ns[id_skemakkni]'");
								$ns3=$sqlgetskkni3->num_rows;

								echo "<b>$ns2[judul]</b> ($ns2[kode_skema])<br>";
								if ($ns3==0){
									echo "Persyaratan : <span class='text-red'><b>Belum Melengkapi</b></span><br>";
								}else{
									echo "Persyaratan : <span class='text-green'><b>Telah mengunggah $ns3 Dokumen</b></span>
									<a href='?module=syarat&id=$ns[id_skemakkni]&ida=$pm[no_pendaftaran]' class='btn btn-info btn-xs' title='Lakukan Verifikasi/Persetujuan Dokumen yang diunggah Asesi'>Verifikasi</a><br>";
								}
							
								if ($ns['id_asesor']=='0'){
									echo "Ploting Asesor : <span class='text-red'><b>Belum</b></span><br>";

								}else{
									$tglasesmen=tgl_indo($ns['tgl_asesmen']);
									echo "Ploting Asesor : <span class='text-green'><b>Sudah</b></span><br>";
									echo "Tanggal Asesmen : <span class='text-green'><b>$tglasesmen</b></span><br>";

								}
							}
							echo "</td>";
							echo "<td><form name='frm' method='POST' role='form' enctype='multipart/form-data'>
								<input type='hidden' name='idblokir' value='$pm[id]'><input type='submit' name='blokir' class='btn btn-warning btn-xs btn-block' title='Blokir akses $pm[nama]' value='Blokir'></form>
								</td></tr>";
							$no++;
						}
					}
				echo "</tbody></table>
				</div>
				
			</div>
		  </div>
          
		</div>
	  </div>
	</section>";
  }
}

// Bagian Asesi LSP ==========================================================================================================
elseif ($_GET['module']=='asesiban'){
  if ($_SESSION['leveluser']=='pengusul'|| $_SESSION['leveluser']=='user'){
    if( isset( $_REQUEST['plotasesor'] ))
	{

	}
    if( isset( $_REQUEST['blokir'] ))
	{
		$sqlblokir="UPDATE `asesi` SET `blokir`='Y' WHERE `id`='$_POST[idblokir]'";
		$conn->query($sqlblokir);
	}
    if( isset( $_REQUEST['bukablokir'] ))
	{
		$sqlblokir="UPDATE `asesi` SET `blokir`='N' WHERE `id`='$_POST[idblokir]'";
		$conn->query($sqlblokir);
	}
    if( isset( $_REQUEST['hapusasesi'] ))
	{
		$sqlblokir="DELETE FROM `asesi` WHERE `id`='$_POST[idhapus]'";
		$conn->query($sqlblokir);
	}
    echo "


    <!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
        Asesi Sertifikasi Profesi
        <small>Data Asesi</small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li class='active'>Data Asesi Sertifikasi Profesi</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class='content'>
      <div class='row'>
        <div class='col-xs-12'>
          <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Data Asesi Sertifikasi Profesi Diblokir</h3>
			<!-- Buat sebuah tombol Cancel untuk kemabli ke halaman awal / view data -->
			<a href='media.php?module=importasesilama' class='btn btn-success pull-right'>
				<span class='fa fa-upload'></span> Import Data Asesi Lama
			</a>

            </div>
            <!-- /.box-header -->
            <div class='box-body'>
				<div id='loading' class='col-xs-12 overlay'>
					<i class='fa fa-refresh fa-spin'></i>
				</div>
				<div style='overflow-x:auto;'>
				<table id='example4' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Identitas Asesi</th><th>Asesmen Skema Sertifikasi</th><th>Aksi</th></tr></thead>
					<tbody>";
					$no=1;
					$sqllsp="SELECT * FROM `asesi` WHERE `blokir`='Y' AND `id_pengusul`='$_SESSION[namauser]' ORDER BY `nama` ASC";
					$lsp=$conn->query($sqllsp);
					while ($pm=$lsp->fetch_assoc()){
						$sqlgetskkni=$conn->query("SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$pm[no_pendaftaran]' AND `id_pengusul`='$_SESSION[namauser]' ORDER BY `id` DESC");
						$ikutasesmen=$sqlgetskkni->num_rows;
						echo "<tr class=gradeX><td>$no</td><td>
						  <div class='box box-widget widget-user-2'>
							<!-- Add the bg color to the header using any of the bg-* classes -->
							<div class='widget-user-header bg-red'>
							  <div class='widget-user-image'>";
								if ($pm['foto']==''){
									echo "<img class='profile-user-img img-responsive img-circle' src='../images/default.jpg' alt='User Avatar'>";
								}else{
									echo "<img class='profile-user-img img-responsive img-circle' src='../foto_asesi/$pm[foto]' alt='User Avatar'>";
								}
								
							if(empty($pm['foto']) or empty($pm['ktp']) || empty($pm['kk']) || empty($pm['ijazah']) || empty($pm['transkrip'])){
								if (empty($pm['foto'])){
									$kelengkapan1="Foto ";
								}
								if(empty($pm['ktp'])){
									$kelengkapan2="KTP ";
								}
								if(empty($pm['kk'])){
									$kelengkapan3="KK ";
								}
								if(empty($pm['ijazah'])){
									$kelengkapan4="Ijazah ";
								}
								if(empty($pm['transkrip'])){
									$kelengkapan5="Transkrip";
								}
								$kelengkapan="Kurang ".$kelengkapan1.$kelengkapan2.$kelengkapan3.$kelengkapan4.$kelengkapan5;
								
							}else{
								$kelengkapan="Lengkap";

							}
							$sqlpendidikan="SELECT * FROM `pendidikan` WHERE `id`='$pm[pendidikan]'";
							$pendidikan=$conn->query($sqlpendidikan);
							$pe=$pendidikan->fetch_assoc();
							  echo "</div>
							  <!-- /.widget-user-image -->
							  <h3 class='widget-user-username'>$pm[nama]</h3>
							  <h5 class='widget-user-desc'>No. Pendaftaran : $pm[no_pendaftaran]</h5>
							  <h5 class='widget-user-desc'>Pendidikan Terakhir : $pe[jenjang_pendidikan]</h5>
							</div>
							<div class='box-footer'>
							  <ul class='nav nav-stacked'>
								<li>Asesmen diikuti <span class='pull-right badge'>$ikutasesmen Skema</span></li>
								<li>Dokumen Pokok <span class='pull-right badge'>$kelengkapan</span></li>

							  </ul>
							</div>
						  </div>
						  <!-- /.widget-user --></td>";
						
			
						echo "</td><td>";
						
						while ($ns=$sqlgetskkni->fetch_assoc()){
							$sqlgetskkni2=$conn->query("SELECT * FROM `skema_kkni` WHERE `id`='$ns[id_skemakkni]'");
							$ns2=$sqlgetskkni2->fetch_assoc();
							$sqlgetskkni3=$conn->query("SELECT * FROM `asesi_doc` WHERE `id_asesi`='$pm[no_pendaftaran]' AND `id_skemakkni`='$ns[id_skemakkni]'");
							$ns3=$sqlgetskkni3->num_rows;

							echo "<b>$ns2[judul]</b> ($ns2[kode_skema])<br>";
							if ($ns3==0){
								echo "Persyaratan : <span class='text-red'><b>Belum Melengkapi</b></span><br>";
							}else{
								echo "Persyaratan : <span class='text-green'><b>Telah mengunggah $ns3 Dokumen</b></span>
								<a href='?module=syarat&id=$ns[id_skemakkni]&ida=$pm[no_pendaftaran]' class='btn btn-info btn-xs' title='Lakukan Verifikasi/Persetujuan Dokumen yang diunggah Asesi'>Verifikasi</a><br>";
							}
						
							if ($ns['id_asesor']=='0'){
								echo "Ploting Asesor : <span class='text-red'><b>Belum</b></span><br>";

							}else{
								$tglasesmen=tgl_indo($ns['tgl_asesmen']);
								echo "Ploting Asesor : <span class='text-green'><b>Sudah</b></span><br>";
								echo "Tanggal Asesmen : <span class='text-green'><b>$tglasesmen</b></span><br>";

							}
						}
						echo "</td>";
					    echo "<td><form name='frm' method='POST' role='form' enctype='multipart/form-data'>
							<input type='hidden' name='idblokir' value='$pm[id]'><input type='submit' name='bukablokir' class='btn btn-success btn-xs btn-block' title='Buka Blokir $pm[nama]' value='Buka Blokir'></form>
							</td></tr>";
						$no++;
					}
				echo "</tbody></table>
				</div>
				
			</div>
		  </div>
          
		</div>
	  </div>
	</section>";
  }
}

// Bagian Asesmen LSP Basis Data ==========================================================================================================
elseif ($_GET['module']=='asesmen'){
  if ($_SESSION['leveluser']=='pengusul'|| $_SESSION['leveluser']=='user'){
    if( isset( $_REQUEST['plotasesor'] ))
	{

	}
    if( isset( $_REQUEST['blokir'] ))
	{
		$sqlblokir="UPDATE `asesi` SET `blokir`='Y' WHERE `id`='$_POST[idblokir]'";
		$conn->query($sqlblokir);
	}
    if( isset( $_REQUEST['bukablokir'] ))
	{
		$sqlblokir="UPDATE `asesi` SET `blokir`='N' WHERE `id`='$_POST[idblokir]'";
		$conn->query($sqlblokir);
	}
    if( isset( $_REQUEST['hapusasesi'] ))
	{
		$sqlblokir="DELETE FROM `asesi` WHERE `id`='$_POST[idhapus]'";
		$conn->query($sqlblokir);
	}
    echo "


    <!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
        Asesi Sertifikasi Profesi
        <small>Data Asesesmen</small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li class='active'>Data Asesmen Sertifikasi Profesi</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class='content'>
      <div class='row'>
        <div class='col-xs-12'>
          <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Data Asesmen Sertifikasi Profesi</h3>

            </div>
            <!-- /.box-header -->
            <div class='box-body'>
				<div id='loading' class='col-xs-12 overlay'>
					<i class='fa fa-refresh fa-spin'></i>
				</div>
				<div style='overflow-x:auto;'>
				<table id='example7' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>ID Asesi</th><th>Skema</th><th>Status</th><th>Keterangan</th><th>Aksi</th></tr></thead>
					<tbody>";
					$no=1;
					$sqlwilasesi0="SELECT * FROM `asesi_asesmen` ORDER BY `id_asesi` ASC, `id` DESC";
					$wilasesi0=$conn->query($sqlwilasesi0);
					while ($was0=$wilasesi0->fetch_assoc()){

						$sqlcekdataas="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$was0[id_asesi]'";
						$cekdataas=$conn->query($sqlcekdataas);
						$dataas=$cekdataas->num_rows;
						if ($dataas==0){
							$ket="<font color='red'>Tidak ada data pada data asesi</font>";
							$ketaksi="<a href='?module=tambahasesi&idp=$was0[id_asesi]' class='btn btn-primary btn-xs'>Tambahkan</a>";
						}else{
							$ket="Valid (Data sinkron dengan data Asesi)";
							$ketaksi="";
						}
						$sqlgetskkni2=$conn->query("SELECT * FROM `skema_kkni` WHERE `id`='$was0[id_skemakkni]'");
						$ns2=$sqlgetskkni2->fetch_assoc();
						echo "<tr class=gradeX><td>$no</td><td>$was0[id_asesi]</td><td>$ns2[judul]</td><td>$was0[status_asesmen]</td><td>$ket</td><td>$ketaksi</td></tr>";
						$no++;
					}
				echo "</tbody></table>
				</div>
				
			</div>
		  </div>
          
		</div>
	  </div>
	</section>";
  }
}

// Bagian Asesor LSP ==========================================================================================================
elseif ($_GET['module']=='asesor'){
  if ($_SESSION['leveluser']=='pengusul'|| $_SESSION['leveluser']=='user'){
	$tanggalini=date("Y-m-d");
	$tanggalkadaluarsa=date('Y-m-d', strtotime($tanggalini. ' + 180 days'));
    echo "
    <!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
        Asesor Sertifikasi Profesi
        <small>Data Asesor</small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li class='active'>Data Asesor Sertifikasi Profesi</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class='content'>
      <div class='row'>
        <div class='col-xs-12'>
          <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Data Asesor Sertifikasi Profesi</h3>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>
			<div style='overflow-x:auto;'>
				<table id='example1' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Identitas Asesor</th><th>Portofolio Asesmen Skema Sertifikasi</th></tr></thead>
					<tbody>";
					$no=1;
					$sqllsp="SELECT * FROM `asesor` ORDER BY `nama` ASC";
					$lsp=$conn->query($sqllsp);
					while ($pm=$lsp->fetch_assoc()){
						$tahunskr=date("Y");
						$sqlgetskkni=$conn->query("SELECT * FROM `jadwal_asesmen` WHERE `id_asesor`='$pm[id]'");
						$ikutasesmen=$sqlgetskkni->num_rows;
						$sqlgetskkni2=$conn->query("SELECT * FROM `jadwal_asesmen` WHERE `id_asesor`='$pm[id]' AND `tgl_asesmen` LIKE '$tahunskr%'");
						$ikutasesmen2=$sqlgetskkni2->num_rows;
						if (!empty($pm['gelar_depan'])){
							if (!empty($pm['gelar_blk'])){
								$namaasesor=$pm['gelar_depan']." ".$pm['nama'].", ".$pm['gelar_blk'];
							}else{
								$namaasesor=$pm['gelar_depan']." ".$pm['nama'];
							}
						}else{
							if (!empty($pm['gelar_blk'])){
								$namaasesor=$pm['nama'].", ".$pm['gelar_blk'];
							}else{
								$namaasesor=$pm['nama'];
							}
						}
						$masaberlaku=$pm['masaberlaku_lisensi'];
						$masaberlakuasesor=tgl_indo($masaberlaku);
						$hariini=date("Y-m-d");
						$dt1 = strtotime($hariini);
						$dt2 = strtotime($masaberlaku);
						$diff = $dt2-$dt1;
						//$diff = abs($dt2-$dt1);
						$telat = $diff/86400; // 86400 detik sehari
						$days_between = $telat;
						$days_between2 = abs($telat);

						echo "<tr class=gradeX><td>$no</td><td><b>$namaasesor</b>
							<br>No. Register : $pm[no_induk]";
						echo "</td><td>";
						$sqlgetskkniasesor=$conn->query("SELECT DISTINCT `id_skemakkni` FROM `jadwal_asesmen` WHERE `id_asesor`='$pm[id]'");
						while ($ns=$sqlgetskkniasesor->fetch_assoc()){
							$sqlgetskkni2=$conn->query("SELECT * FROM `skema_kkni` WHERE `id`='$ns[id_skemakkni]'");
							$ns2=$sqlgetskkni2->fetch_assoc();
							echo "<b>$ns2[judul]</b> ($ns2[kode_skema])<br>";
						}
						echo "</td>";
						echo "</tr>";
						$no++;
					}
				echo "</tbody></table>
			</div>

	    </div>
	  </div>
        </div>
      </div>
    </section>";
  }
}

// Bagian Tambah Asesi LSP ==========================================================================================================
elseif ($_GET['module']=='tambahasesi'){
  if ($_SESSION['leveluser']=='pengusul'|| $_SESSION['leveluser']=='user'){


$sqlidentitas="SELECT * FROM `identitas`";
$identitas=$conn->query($sqlidentitas);
$iden=$identitas->fetch_assoc();
$urldomain=$iden['url_domain'];

echo "<!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
	  Tambah Asesi
        <small></small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li><a href='media.php?module=asesor'>Asesi</a></li>
        <li class='active'>Tambah</li>
      </ol>
    </section>";
	function uploadFoto($file){
		//$file_max_weight = 20097152; //Ukuran maksimal yg dibolehkan(2Mb)
		$ok_ext = array('jpg','png','gif','jpeg','JPG','PNG','GIF','JPEG'); // ekstensi yang diijinkan
		$destination = "../foto_asesi/"; // tempat buat upload
		$filename = explode(".", $file['name']); 
		$file_name = $file['name'];
		$file_name_no_ext = isset($filename[0]) ? $filename[0] : null;
		$file_extension = $filename[count($filename)-1];
		$file_weight = $file['size'];
		$file_type = $file['type'];

		// Jika tidak ada error
		if( $file['error'] == 0 ){					
			$dateNow = date_create();
			$time_stamp = date_format($dateNow, 'U');
				if( in_array($file_extension, $ok_ext)):
					//if( $file_weight <= $file_max_weight ):
						$fileNewName = $time_stamp.md5( $file_name_no_ext[0].microtime() ).".".$file_extension;
						$alamatfile=$fileNewName;
						if( move_uploaded_file($file['tmp_name'], $destination.$fileNewName) ):
							//echo" File uploaded !";
						else:
							//echo "can't upload file.";
						endif;
					//else:
						//echo "File too heavy.";
					//endif;
				else:
					//echo "File type is not supported.";
				endif;
				}	
		return $alamatfile;
		}

	if( isset( $_REQUEST['simpan'] ))
	{				
		
		$file = $_FILES['file'];
				
		if(empty($_FILES['file']['tmp_name']) || !is_uploaded_file($_FILES['file']['tmp_name']))
		{
			$alamatfile=$rowAgen['foto'];
					
		}else {
			unlink('../foto_asesi/'.$rowAgen['foto']);
			$alamatfile = uploadFoto($file);
		}
		$sqlcek="SELECT `no_ktp` FROM `asesi` WHERE `no_ktp`='$_POST[no_ktp]'";
		$cekktp=$conn->query($sqlcek);
		$ktp=$cekktp->num_rows;

		if ($ktp==0){
			// Simpan data pendaftaran calon mahasiswa
    			$tgl_daftar=date("Y-m-d");
			$digitthn=date("Y");
			$digitbln=date("m");
			$digittgl=date("d");
			$genpass=rand(100000,999999);
			$pass1=md5($genpass);
			$pass2=substr($pass1,-6);
			$password=md5($pass2);
			$digitnohp=substr($_POST['no_ktp'],-6);
			$gennopendaf=$digitthn.$digitbln.$digittgl.$digitnohp;
			//2017080512345
			$ip = $_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
			$sql="INSERT INTO `asesi`(`no_pendaftaran`,
			`password`,
			`nama`,
			`tmp_lahir`,
			`tgl_lahir`,
			`email`,
			`nohp`,
			`no_ktp`,
			`alamat`,
			`RT`,
			`RW`,
			`kelurahan`,
			`kecamatan`,
			`kota`,
			`propinsi`,
			`jenis_kelamin`,
			`foto`,
			`tgl_daftar`,
			`id_pengusul`) VALUES ('$gennopendaf',
			'$password',
			'$_POST[nama]',
			'$_POST[tmp_lahir]',
			'$_POST[tgl_lahir]',
			'$_POST[email]',
			'$_POST[no_hp]',
			'$_POST[no_ktp]',
			'$_POST[alamat]',
			'$_POST[RT]',
			'$_POST[RW]',
			'$_POST[kelurahan]',
			'$_POST[kecamatan]',
			'$_POST[kota]',
			'$_POST[propinsi]',
			'$_POST[jenis_kelamin]',
			'$alamatfile',
			'$tgl_daftar',
			'$_SESSION[namauser]')";
			$conn->query($sql);
			//$getpt=mysql_fetch_assoc(mysql_query("SELECT * FROM `kodept` WHERE `kode_pt`='$_POST[kodept]'"));
			//$getprod=mysql_fetch_assoc(mysql_query("SELECT * FROM `kodeprodi` WHERE `kode_pt`='$_POST[kodept]' AND `kode_prodi`='$_POST[prodipt]'"));
			$getbank=$conn->query("SELECT * FROM `rekeningbayar` WHERE `aktif`='Y'");
			// Kirim email dalam format HTML ke Pendaftar
			$pesan ="Anda telah didaftarkan sebagai peserta Uji Kompetensi Keahlian di $urldomain<br /><br />  
					Nomor Pendaftaran: $gennopendaf <br />
					Nama: $_POST[nama] <br />
					Nomor Handphone: $_POST[no_hp] <br />
					Kata Sandi (Password): $pass2 <br />
					<br /><br />Silahkan lakukan masuk/login ke http://".$urldomain."/asesi, dan isi data-data yang diperlukan.";
			$subjek="Anda telah didaftarkan di SILSP $urldomain";
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
	$mail->AddAddress($email,$namanya);  //tujuan email
	$mail->MsgHTML($pesan);
	if ($mail->Send()){
		echo "";
	}else{
		echo "Notifikasi Email Gagal Terkirim, periksa pengaturan di menu Manajemen, sub menu Pengaturan SMTP";
	}

    	//mail($email,$subjek,$pesan,$dari);
			
			//SMS Pendaftar
			
			$isisms="Yth. $_POST[nama] Anda didaftarkan sebagai peserta uji kompetensi dengan No. Pendaftaran $gennopendaf, Password : $pass2 masuk/Login ke http://".$urldomain."/asesi";
			if (strlen($_POST['no_hp'])>8){
				$sqloutbox="INSERT INTO `outbox` (`DestinationNumber`, `TextDecoded`, `Coding`,`SenderID`,`CreatorID`) VALUES ('$_POST[no_hp]','$isisms','Default_No_Compression','MyPhone1','MyPhone1')";
				$outbox=$conn->query($sqloutbox);
			}

			echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Tambah Data Sukses</h4>
			Anda Telah Berhasil Menambah Data <b>Asesi</b></div>";
			//echo('<script>location.href = 'Location: editdata.php?type=$type&id=$id&edit=sukses';</script>');
			die("<script>location.href = 'media.php?module=asesibaru'</script>");
		}else{
			echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-times'></i> Tambah Data Gagal</h4>
			Data telah ditambahkan sebelumnya</div>";
			die("<script>location.href = 'media.php?module=asesibaru'</script>");


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
		if ($rowAgen['tgl_lahir']=='0000-00-00'){
			echo "<div class='alert alert-info alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-info'></i> Lengkapi Data Profil Asesi</h4>
			Silahkan lengkapi data <b>Profil Asesi</b> untuk dapat menambahkan Asesi</div>";

		}
		echo"<div class='row'>
		    <div class='col-md-6'>			  
				<div class='col-md-12'>			  
					
					<div class='form-group'>
						<label for='fileID'>
						Foto Asesi
						</label>
						<input type='file' name='file' id='fileID' accept='image/*' onchange='readURL(this);'>
						<p class='help-block'>Ukuran foto maksimal 2 MB.</p>
					</div>
				</div>";

				echo "<div class='col-md-12'>  
					<div class='form-group'>
						<label>Nama Lengkap</label>
						<input required type='text'  name='nama' class='form-control'>
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
							if ($rowAgen['jenis_kelamin']=='L'){
								echo "selected";
							}
							echo ">Laki-laki</option>";					
							echo "<option value='P'";
							if ($rowAgen['jenis_kelamin']=='P'){
								echo "selected";
							}
							echo ">Perempuan</option>";
						echo "</select>
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

		</div>
		<div class='col-md-6'>			  
				<div class='col-md-6'>
					<div class='form-group'>
						<label>Nomor HP</label>
						<input required type='text' name='no_hp' value='$rowAgen[no_hp]' class='form-control' maxlength='14'>
					</div>
				</div>
				<div class='col-md-6'>			
					<div class='form-group'>
						<label>E-mail</label>
						<input required type='text' name='email' value='$rowAgen[email]' class='form-control'>
					</div>				
									
					
				</div>
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
						<input required type='text' name='RT' value='$rowAgen[RT]' class='form-control'>
					</div>
				</div>
				<div class='col-md-3'>
					<div class='form-group'>
						<label>RW</label>
						<input required type='text' name='RW' value='$rowAgen[RW]'class='form-control'>
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
						<select name='propinsi' class='form-control' id='propinsi'>
							<option>Pilih</pilih>";
						$sqlpropinsi="SELECT * FROM  `data_wilayah` WHERE  id_level_wil='1' AND id_induk_wilayah!='NULL' ORDER BY id_wil ASC";
						$propinsi=$conn->query($sqlpropinsi);
   						while($prop=$propinsi->fetch_assoc()){
   							echo "<option value='$prop[id_wil]'";
							if ($prop['id_wil']==$rowAgen['propinsi']){
								echo "selected";
							}
							echo ">$prop[nm_wil]</option>";
						}
						echo"</select>";
					echo"</div>
					</div>
				</div>
				<div class='col-md-6'>				
					<div class='form-group'>
						<label>Kota</label>
						<select name='kota' class='form-control' id='kota'>";
						$sqlkota="SELECT * FROM  `data_wilayah` WHERE  id_wil='$rowAgen[kota]'";
						$kota=$conn->query($sqlkota);
						$nk=$kota->fetch_assoc();
						echo"<option value='$rowAgen[kota]'>$nk[nm_wil]</option>";
						echo"</select>
					</div>
				</div>
				<div class='col-md-6'>
					<div class='form-group'>
						<label>Kecamatan</label>
						<select name='kecamatan' class='form-control' id='kecamatan'>";
						$sqlkecamatan="SELECT * FROM  `data_wilayah` WHERE  id_wil='$rowAgen[kecamatan]'";
						$kec=$conn->query($sqlkecamatan);
						$kc=$kec->fetch_assoc();
						echo"<option value='$rowAgen[kecamatan]'>$kc[nm_wil]</option>";
						echo"</select>
					</div>
				</div>
				<div class='col-md-6'>
					<div class='form-group'>
						<label>Kode Pos</label>
						<input type='text' name='kodepos' value='$rowAgen[kodepos]' class='form-control'>
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
}

// Bagian Tambah Asesor LSP ==========================================================================================================
elseif ($_GET['module']=='tambahasesor'){
  if ($_SESSION['leveluser']=='pengusul'|| $_SESSION['leveluser']=='user'){




echo "<!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
	  Tambah Asesor
        <small></small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li><a href='media.php?module=asesor'>Asesor</a></li>
        <li class='active'>Tambah</li>
      </ol>
    </section>";
	function uploadFoto($file){
		//$file_max_weight = 20097152; //Ukuran maksimal yg dibolehkan(2Mb)
		$ok_ext = array('jpg','png','gif','jpeg','JPG','PNG','GIF','JPEG'); // ekstensi yang diijinkan
		$destination = "../foto_asesor/"; // tempat buat upload
		$filename = explode(".", $file['name']); 
		$file_name = $file['name'];
		$file_name_no_ext = isset($filename[0]) ? $filename[0] : null;
		$file_extension = $filename[count($filename)-1];
		$file_weight = $file['size'];
		$file_type = $file['type'];

		// Jika tidak ada error
		if( $file['error'] == 0 ){					
			$dateNow = date_create();
			$time_stamp = date_format($dateNow, 'U');
				if( in_array($file_extension, $ok_ext)):
					//if( $file_weight <= $file_max_weight ):
						$fileNewName = $time_stamp.md5( $file_name_no_ext[0].microtime() ).".".$file_extension;
						$alamatfile=$fileNewName;
						if( move_uploaded_file($file['tmp_name'], $destination.$fileNewName) ):
							//echo" File uploaded !";
						else:
							//echo "can't upload file.";
						endif;
					//else:
						//echo "File too heavy.";
					//endif;
				else:
					//echo "File type is not supported.";
				endif;
				}	
		return $alamatfile;
		}

	if( isset( $_REQUEST['simpan'] ))
	{				
		
		$file = $_FILES['file'];
				
		if(empty($_FILES['file']['tmp_name']) || !is_uploaded_file($_FILES['file']['tmp_name']))
		{
			$alamatfile=$rowAgen['foto'];
					
		}else {
			unlink('../foto_asesor/'.$rowAgen['foto']);
			$alamatfile = uploadFoto($file);
		}
		$sqllogin="SELECT * FROM `asesor` WHERE `nama`='$_POST[nama]' AND `gelar_depan`='$_POST[gelar_depan]' AND `gelar_blk`='$_POST[gelar_blk]' AND `jenis_kelamin`='$_POST[jenis_kelamin]' AND `tmp_lahir`='$_POST[tmp_lahir]' AND `tgl_lahir`='$_POST[tgl_lahir]'";
		$login=$conn->query($sqllogin);
		$ketemu=$login->num_rows;
		if ($ketemu==0){
    			$genpass=rand(100000,999999);
    			$pass1=md5($genpass);
    			$pass2=substr($pass1,-6);
    			$password=md5($pass2);

			// Kirim email dalam format HTML ke Pendaftar
			$email=$_POST['email'];
    			$pesan ="Anda terdaftar sebagai asesor di LSP<br /><br />  
            			Nomor Induk : $_POST[no_induk] <br />
            			Nama: $_POST[nama] <br />
            			Nomor Handphone: $_POST[no_hp] <br />
            			Kata Sandi (Password): $pass2 <br />
    				<br /><br />Silahkan lakukan masuk/login ke http://".$urldomain."/asesor, dan lengkapi data-data yang diperlukan.";
    			$subjek="Anda didaftarakan sebagai asesor di SILSP";
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
	$mail->AddAddress($email,$namanya);  //tujuan email
	$mail->MsgHTML($pesan);
	if ($mail->Send()){
		echo "";
	}else{
		echo "Notifikasi Email Gagal Terkirim, periksa pengaturan di menu Manajemen, sub menu Pengaturan SMTP";
	}

    	//mail($email,$subjek,$pesan,$dari);
    
    			//SMS Pendaftar
    
    			$isisms="Yth. $_POST[nama] Anda didaftarkan sebagai asesor LSP, dengan No. Register : $_POST[no_induk] Password : $pass2 Silahkan masuk/Login ke http://".$urldomain."/asesor";
    			if (strlen($_POST['no_hp'])>8){
    			    $sqloutbox="INSERT INTO `outbox` (`DestinationNumber`, `TextDecoded`, `Coding`,`SenderID`,`CreatorID`) VALUES ('$_POST[no_hp]','$isisms','Default_No_Compression','MyPhone1','MyPhone1')";
			    $outbox=$conn->query($sqloutbox);
    			}


			$query = "INSERT INTO `asesor`(`password`, `nama`, `gelar_depan`, `gelar_blk`, `jenis_kelamin`, `tmp_lahir`, `tgl_lahir`, `foto`, `email`, `no_hp`, `no_induk`, `no_ktp`, `pendidikan_terakhir`, `tahun_lulus`, `bid_keahlian`, `kebangsaan`, `alamat`, `RT`, `RW`, `kelurahan`, `kecamatan`, `kota`, `propinsi`, `kodepos`, `institusi_asal`, `telp_kantor`, `fax_kantor`, `email_kantor`, `no_lisensi`, `masaberlaku_lisensi`, `aktif`) VALUES ('$password', '$_POST[nama]','$_POST[gelar_depan]','$_POST[gelar_blk]','$_POST[jenis_kelamin]','$_POST[tmp_lahir]','$_POST[tgl_lahir]','$alamatfile','$_POST[email]','$_POST[no_hp]','$_POST[no_induk]','$_POST[no_ktp]','$_POST[pendidikan_terakhir]','$_POST[tahun_lulus]','$_POST[bid_keahlian]','$_POST[kebangsaan]','$_POST[alamat]','$_POST[RT]','$_POST[RW]','$_POST[kelurahan]','$_POST[kecamatan]','$_POST[kota]','$_POST[propinsi]','$_POST[kodepos]','$_POST[institusi_asal]','$_POST[telp_kantor]','$_POST[fax_kantor]','$_POST[email_kantor]','$_POST[no_lisensi]','$_POST[masaberlaku_lisensi]','$_POST[aktif]')";
			if ($conn->query($query) == TRUE) {
					
				echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Tambah Data Sukses</h4>
				Anda Telah Berhasil Menambah Data <b>Asesor</b></div>";
			//echo('<script>location.href = 'Location: editdata.php?type=$type&id=$id&edit=sukses';</script>');
				die("<script>location.href = 'media.php?module=asesor'</script>");
			} else {
				echo "Error: " . $query . "<br>" . $conn->error;
			}
		}else{
			echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-times'></i> Tambah Data Gagal</h4>
			Data telah ditambahkan sebelumnya</div>";
			die("<script>location.href = 'media.php?module=asesor'</script>");


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
		if ($rowAgen['tgl_lahir']=='0000-00-00'){
			echo "<div class='alert alert-info alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-info'></i> Lengkapi Data Profil Asesor</h4>
			Silahkan lengkapi data <b>Profil Asesor</b> untuk dapat menambahkan Asesor</div>";

		}
		echo"<div class='row'>
		    <div class='col-md-6'>			  
				<div class='col-md-12'>			  
					
					<div class='form-group'>
						<label for='fileID'>
						Foto Asesor
						</label>
						<input type='file' name='file' id='fileID' accept='image/*' onchange='readURL(this);'>
						<p class='help-block'>Ukuran foto maksimal 2 MB.</p>
					</div>
				</div>
				<div class='col-md-3'>  
					<div class='form-group'>
						<label>Gelar depan</label>
						<input type='text'  name='gelar_depan' class='form-control'>
					</div>
				</div>
				<div class='col-md-6'>  
					<div class='form-group'>
						<label>Nama Lengkap</label>
						<input required type='text'  name='nama' class='form-control'>
					</div>
				</div>
				<div class='col-md-3'>  
					<div class='form-group'>
						<label>Gelar belakang</label>
						<input type='text'  name='gelar_blk' value='$rowAgen[gelar_blk]' class='form-control'>
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
							if ($rowAgen['jenis_kelamin']=='L'){
								echo "selected";
							}
							echo ">Laki-laki</option>";					
							echo "<option value='P'";
							if ($rowAgen['jenis_kelamin']=='P'){
								echo "selected";
							}
							echo ">Perempuan</option>";
						echo "</select>
					</div>
				</div>
				<div class='col-md-9'>
					<div class='form-group'>
						<label>Pendidikan Terakhir</label>
						<select required class='form-control' name='pendidikan_terakhir'>";
						$sqlpendidikan="SELECT * FROM `pendidikan` ORDER BY `id` ASC";
						$pendidikan=$conn->query($sqlpendidikan);
						while ($pdd=$pendidikan->fetch_assoc()){
							
							echo "<option value='$pdd[id]'";
							if ($pdd['id']==$rowAgen['pendidikan_terakhir']){
								echo "selected";
							}
							echo ">$pdd[jenjang_pendidikan]</option>";					
						}
						echo "</select>
					</div>
				</div>
				<div class='col-md-3'>  
					<div class='form-group'>
						<label>Tahun Lulus</label>
						<input required type='text'  name='tahun_lulus' value='$rowAgen[tahun_lulus]' class='form-control'>
					</div>
				</div>
				<div class='col-md-12'>  
					<div class='form-group'>
						<label>Bidang Keahlian</label>
						<input required type='text'  name='bid_keahlian' value='$rowAgen[bid_keahlian]' class='form-control'>
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
						<select required class='form-control' name='kebangsaan'>
							<option>Pilih</option>";
						$sqlkebangsaan="SELECT * FROM `kebangsaan` ORDER BY `negara` ASC";
						$kebangsaan=$conn->query($sqlkebangsaan);
						while ($bgs=$kebangsaan->fetch_assoc()){
							
							echo "<option value='$bgs[negara]'";
							if ($bgs['negara']==$rowAgen['kebangsaan']){
								echo "selected";
							}
							echo ">$bgs[negara]</option>";					
						}
						echo "</select>
					</div>
				</div>
				<div class='col-md-12'>
					<div class='form-group'>
						<label>Nomor HP</label>
						<input required type='text' name='no_hp' value='$rowAgen[no_hp]' class='form-control' maxlength='14'>
					</div>
				</div>
				<div class='col-md-12'>			
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
				<div class='col-md-6'>
					<div class='form-group'>
						<label>RT</label>
						<input required type='text' name='RT' value='$rowAgen[RT]' class='form-control'>
					</div>
				</div>
				<div class='col-md-6'>
					<div class='form-group'>
						<label>RW</label>
						<input required type='text' name='RW' value='$rowAgen[RW]'class='form-control'>
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
						<select name='propinsi' class='form-control' id='propinsi'>
							<option>Pilih</pilih>";
						$sqlpropinsi="SELECT * FROM  `data_wilayah` WHERE  id_level_wil='1' AND id_induk_wilayah!='NULL' ORDER BY id_wil ASC";
						$propinsi=$conn->query($sqlpropinsi);
   						while($prop=$propinsi->fetch_assoc()){
   							echo "<option value='$prop[id_wil]'";
							if ($prop['id_wil']==$rowAgen['propinsi']){
								echo "selected";
							}
							echo ">$prop[nm_wil]</option>";
						}
						echo"</select>";
					echo"</div>
					</div>
				</div>
				<div class='col-md-6'>				
					<div class='form-group'>
						<label>Kota</label>
						<select name='kota' class='form-control' id='kota'>";
						$sqlkota="SELECT * FROM  `data_wilayah` WHERE  id_wil='$rowAgen[kota]'";
						$kota=$conn->query($sqlkota);
						$nk=$kota->fetch_assoc();
						echo"<option value='$rowAgen[kota]'>$nk[nm_wil]</option>";
						echo"</select>
					</div>
				</div>
				<div class='col-md-6'>
					<div class='form-group'>
						<label>Kecamatan</label>
						<select name='kecamatan' class='form-control' id='kecamatan'>";
						$sqlkecamatan="SELECT * FROM  `data_wilayah` WHERE  id_wil='$rowAgen[kecamatan]'";
						$kec=$conn->query($sqlkecamatan);
						$kc=$kec->fetch_assoc();
						echo"<option value='$rowAgen[kecamatan]'>$kc[nm_wil]</option>";
						echo"</select>
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
						<label>Nomor Register Asesor</label>
						<input required type='text'  name='no_induk' value='$rowAgen[no_induk]' class='form-control'>
					</div>
				</div>
				<div class='col-md-12'>			
					<div class='form-group'>
						<label>Nomor Lisensi Asesor</label>
						<input required type='text'  name='no_lisensi' value='$rowAgen[no_lisensi]' class='form-control'>
					</div>
				</div>
				<div class='col-md-12'>			
					<div class='form-group'>
						<label>Masa Berlaku hingga</label>
						<input required type='date'  name='masaberlaku_lisensi' value='$rowAgen[masaberlaku_lisensi]' class='form-control'>
					</div>
				</div>
				<div class='col-md-12'>			
					<div class='form-group'>
						<label>Institusi Asal</label>
						<input required type='text'  name='institusi_asal' value='$rowAgen[institusi_asal]' class='form-control'>
					</div>
				</div>
				<div class='col-md-6'>			
					<div class='form-group'>
						<label>Telp. Institusi Asal</label>
						<input required type='text'  name='telp_kantor' value='$rowAgen[telp_kantor]' class='form-control'>
					</div>
				</div>
				<div class='col-md-6'>			
					<div class='form-group'>
						<label>Fax. Institusi Asal</label>
						<input required type='text'  name='fax_kantor' value='$rowAgen[fax_kantor]' class='form-control'>
					</div>
				</div>

				<div class='col-md-9'>			
					<div class='form-group'>
						<label>Email Institusi Asal</label>
						<input required type='text'  name='email_kantor' value='$rowAgen[email_kantor]' class='form-control'>
					</div>
				</div>
				<div class='col-md-3'>			
					<div class='form-group'>
						<label>Status</label>
						<select  name='aktif' class='form-control'>";
							echo "<option value='Y'";
							if ($rowAgen['aktif'=='Y']){
								echo "selected";
							}
							echo ">Aktif</option>";
							echo "<option value='N'";
							if ($rowAgen['aktif'=='N']){
								echo "selected";
							}
							echo ">Non Aktif</option>";
						echo "</select>
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
}

// Bagian Input Dokumen Persyaratan Asesi ==========================================================================================================
elseif ($_GET['module']=='syarat'){
$sqlasesi="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_GET[ida]'";
$getasesi=$conn->query($sqlasesi);
$as=$getasesi->fetch_assoc();

if( isset( $_REQUEST['setujuidoc'] )){
	$cekdu="SELECT * FROM `asesi_doc` WHERE `id`='$_POST[id_doc]'";
	$result = $conn->query($cekdu);
	$asd=$result->fetch_assoc();
	$sqlgetasesi="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$asd[id_asesi]'";
	$getasesi=$conn->query($sqlgetasesi);
	$asi=$getasesi->fetch_assoc();
	//Kirim Email ke Asesi-----------------------------------
		$email=$asi['email'];
		$namanya=$asi['nama'];
		$no_hp=$asi['nohp'];
		// Kirim email dalam format HTML ke Pendaftar
		    $pesan ="Dokumen Anda telah Disetujui<br /><br />  
		            ID Asesi: $asi[no_pendaftaran] <br />
		            Nama: $namanya <br />
		            Nomor Handphone: $asi[nohp] <br />
			    Dokumen: $asd[nama_doc] - $asd[nomor_doc]<br />
		    		<br /><br />Telah dinyatakan disetujui. Silahkan lihat di laman Dashboard Anda.<br /><br />";
		    
			    $subjek="Selamat, Dokumen Asesi di SILSP Telah disetujui";
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
	$mail->AddAddress($email,$namanya);  //tujuan email
	$mail->MsgHTML($pesan);
	if ($mail->Send()){
		echo "";
	}else{
		echo "Notifikasi Email Gagal Terkirim, periksa pengaturan di menu Manajemen, sub menu Pengaturan SMTP";
	}

    	//mail($email,$subjek,$pesan,$dari);
    
		//SMS Pendaftar
    		$isisms="Yth. $namanya Dokumen $asd[nama_doc] Anda telah disetujui, lihat info di laman http://".$urldomain.".";
		if (strlen($no_hp)>8){
			$sqloutbox="INSERT INTO `outbox` (`DestinationNumber`, `TextDecoded`, `Coding`,`SenderID`,`CreatorID`) VALUES ('$no_hp','$isisms','Default_No_Compression','MyPhone1','MyPhone1')";
			$outbox=$conn->query($sqloutbox);
    		}
	//-----------------------------------------------------
	if ($result->num_rows != 0){
		$conn->query("UPDATE `asesi_doc` SET `status`='A' WHERE `id`='$_POST[id_doc]'");
        echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Persetujuan Dokumen Sukses</h4>
			Anda Telah Berhasil Menyetujui <b>Dokumen Persyaratan Sertifikasi</b></div>";

	}else{
		echo "<script>alert('Maaf Dokumen Persyaratan tersebut Tidak Ditemukan');</script>";
	}
}

if( isset( $_REQUEST['tolakdoc'] )){
	$cekdu="SELECT * FROM `asesi_doc` WHERE `id`='$_POST[id_doc]'";
	$result = $conn->query($cekdu);
	$asd=$result->fetch_assoc();
	$sqlgetasesi="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$asd[id_asesi]'";
	$getasesi=$conn->query($sqlgetasesi);
	$asi=$getasesi->fetch_assoc();
	//Kirim Email ke Asesi-----------------------------------
		$email=$asi['email'];
		$namanya=$asi['nama'];
		$no_hp=$asi['nohp'];
		// Kirim email dalam format HTML ke Pendaftar
		    $pesan ="Dokumen Anda Ditolak<br /><br />  
		            ID Asesi: $asi[no_pendaftaran] <br />
		            Nama: $namanya <br />
		            Nomor Handphone: $asi[nohp] <br />
			    Dokumen: $asd[nama_doc] - $asd[nomor_doc]<br />
		    		<br /><br />Telah dinyatakan ditolak. Silahkan lihat di laman Dashboard Anda.<br /><br />";
		    
			    $subjek="Maaf, Dokumen Asesi di SILSP ditolak";
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
	$mail->AddAddress($email,$namanya);  //tujuan email
	$mail->MsgHTML($pesan);
	if ($mail->Send()){
		echo "";
	}else{
		echo "Notifikasi Email Gagal Terkirim, periksa pengaturan di menu Manajemen, sub menu Pengaturan SMTP";
	}

    	//mail($email,$subjek,$pesan,$dari);
    
		//SMS Pendaftar
    		$isisms="Yth. $namanya Dokumen $asd[nama_doc] Anda telah ditolak, lihat info di laman http://".$urldomain.".";
		if (strlen($no_hp)>8){
			$sqloutbox="INSERT INTO `outbox` (`DestinationNumber`, `TextDecoded`, `Coding`,`SenderID`,`CreatorID`) VALUES ('$no_hp','$isisms','Default_No_Compression','MyPhone1','MyPhone1')";
			$outbox=$conn->query($sqloutbox);
    		}
	//-----------------------------------------------------

	if ($result->num_rows != 0){
		$conn->query("UPDATE `asesi_doc` SET `status`='R' WHERE `id`='$_POST[id_doc]'");
        echo "<div class='alert alert-danger alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-times'></i> Penolakan Dokumen Sukses</h4>
			Anda Telah Berhasil Menolak <b>Dokumen Persyaratan Sertifikasi</b></div>";

	}else{
		echo "<script>alert('Maaf Dokumen Persyaratan tersebut Tidak Ditemukan');</script>";
	}
}

if( isset( $_REQUEST['validasipembayaran'] )){
	$cekdub="SELECT * FROM `asesi_pembayaran` WHERE `id`='$_POST[id_bayar]'";
	$resultb = $conn->query($cekdub);
	$asd=$resultb->fetch_assoc();
	$sqlgetasesi="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$asd[id_asesi]'";
	$getasesi=$conn->query($sqlgetasesi);
	$asi=$getasesi->fetch_assoc();
	//Kirim Email ke Asesi-----------------------------------
		$email=$asi['email'];
		$namanya=$asi['nama'];
		$no_hp=$asi['nohp'];
		// Kirim email dalam format HTML ke Pendaftar
		    $pesan ="Pembayaran biaya Asesmen Anda telah dinyatakan Valid<br /><br />  
		            ID Asesi: $asi[no_pendaftaran] <br />
		            Nama: $namanya <br />
		            Nomor Handphone: $asi[nohp] <br />
			    Pembayaran: $asd[metode_bayar] - $asd[jalur_bayar] $asd[tgl_bayar] $asd[jam_bayar]<br />
		    		<br /><br />Telah dinyatakan valid. Silahkan lihat di laman Dashboard Anda.<br /><br />";
		    
			    $subjek="Selamat, Pembayaran Asesmen Telah Divalidasi";
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
	$mail->AddAddress($email,$namanya);  //tujuan email
	$mail->MsgHTML($pesan);
	if ($mail->Send()){
		echo "";
	}else{
		echo "Notifikasi Email Gagal Terkirim, periksa pengaturan di menu Manajemen, sub menu Pengaturan SMTP";
	}

    	//mail($email,$subjek,$pesan,$dari);
    
		//SMS Pendaftar
    		$isisms="Yth. $namanya Pembayaran Asesmen Anda telah divalidasi, lihat info di laman http://".$urldomain.".";
		if (strlen($no_hp)>8){
			$sqloutbox="INSERT INTO `outbox` (`DestinationNumber`, `TextDecoded`, `Coding`,`SenderID`,`CreatorID`) VALUES ('$no_hp','$isisms','Default_No_Compression','MyPhone1','MyPhone1')";
			$outbox=$conn->query($sqloutbox);
    		}
	//-----------------------------------------------------

	if ($resultb->num_rows != 0){
		$conn->query("UPDATE `asesi_pembayaran` SET `status`='V' WHERE `id`='$_POST[id_bayar]'");
		$conn->query("UPDATE `asesi_asesmen` SET `biaya_asesmen`='L' WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$_GET[id]'");
        echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Validasi sukses</h4>
			Pembayaran telah Anda nyatakan Valid</div>";

	}else{
		echo "<script>alert('Maaf Data Pembayaran tersebut Tidak Ditemukan');</script>";
	}
}


if( isset( $_REQUEST['setujuiasesmen'] )){
	$tgl_daftar=date("Y-m-d");
	$sqljadwal="SELECT * FROM `jadwal_asesmen` WHERE `id`='$_POST[jadwalasesmen]'";
	$jadwal=$conn->query($sqljadwal);
	$jdq=$jadwal->fetch_assoc();
	$querydas = "UPDATE `asesi_asesmen` SET `status`='A', `id_jadwal`='$_POST[jadwalasesmen]', `id_asesor`='$jdq[id_asesor]', `tgl_asesmen`='$jdq[tgl_asesmen]' WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$_GET[id]'";
	$querycek = "SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$_GET[id]' AND `status`='A'";
	$resultc=$conn->query($querycek);
	$row_cnt = $resultc->num_rows;
	if ($row_cnt==0){
		$conn->query($querydas);
		//Notifikasi Email dan SMS====================================================
		$sqlgetskema="SELECT * FROM `skema_kkni` WHERE `id`='$_GET[id]'";
		$getskema=$conn->query($sqlgetskema);
		$gs=$getskema->fetch_assoc();
		
		
		$email=$as['email'];
		$namanya=$as['nama'];
		$no_hp=$as['nohp'];
		// Kirim email dalam format HTML ke Pendaftar
		    $pesan ="Pendaftaran Asesmen Skema $gs[kode_skema] - $gs[judul]<br /><br />  
		            ID Asesi: $as[no_pendaftaran] <br />
		            Nama: $namanya <br />
		            Nomor Handphone: $as[nohp] <br />
			    Skema: $gs[kode_skema] - $gs[judul]<br />
		    		<br /><br />Telah dinyatakan disetujui. Silahkan lihat jadwal asesmen di laman Dashboard Anda.<br /><br />";
		    
			    $subjek="Pendaftaran Asesmen di SILSP Telah disetujui";
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
	$mail->AddAddress($email,$namanya);  //tujuan email
	$mail->MsgHTML($pesan);
	if ($mail->Send()){
		echo "";
	}else{
		echo "Notifikasi Email Gagal Terkirim, periksa pengaturan di menu Manajemen, sub menu Pengaturan SMTP";
	}

    	//mail($email,$subjek,$pesan,$dari);
    
		//SMS Pendaftar
    		$isisms="Yth. $namanya Pendaftaran Asesmen Skema $gs[kode_skema]-$gs[judul] Anda telah disetujui, lihat jadwal di laman http://".$urldomain.".";
		if (strlen($no_hp)>8){
			$sqloutbox="INSERT INTO `outbox` (`DestinationNumber`, `TextDecoded`, `Coding`,`SenderID`,`CreatorID`) VALUES ('$no_hp','$isisms','Default_No_Compression','MyPhone1','MyPhone1')";
			$outbox=$conn->query($sqloutbox);
    		}
		//============================================================================
		echo "<div class='alert alert-success alert-dismissible'>
		<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
		<h4><i class='icon fa fa-check'></i> Persetujuan Asesmen Sukses</h4>
		Anda Telah Berhasil menyetujui permohonan asesmen Asesi pada skema ini<b></b></div>";
	}else{
		echo "<script>alert('Maaf Asesi sudah disetujui pada skema ini'); window.location = '?module=syarat&id=$_GET[id]&ida=$_GET[ida]'</script>";
	}


}

if( isset( $_REQUEST['tolakasesmen'] )){
	$tgl_daftar=date("Y-m-d");
	$querydas = "UPDATE `asesi_asesmen` SET `status`='R' WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$_GET[id]'";
	$querycek = "SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$_GET[id]' AND `status`='R'";
	$resultc=$conn->query($querycek);
	$row_cnt = $resultc->num_rows;
	if ($row_cnt==0){
		$conn->query($querydas);
		//Notifikasi Email dan SMS====================================================
		$sqlgetskema="SELECT * FROM `skema_kkni` WHERE `id`='$_GET[id]'";
		$getskema=$conn->query($sqlgetskema);
		$gs=$getskema->fetch_assoc();
		
		
		$email=$as['email'];
		$namanya=$as['nama'];
		$no_hp=$as['nohp'];
		// Kirim email dalam format HTML ke Pendaftar
		    $pesan ="Pendaftaran Asesmen Skema $gs[kode_skema] - $gs[judul]<br /><br />  
		            ID Asesi: $as[no_pendaftaran] <br />
		            Nama: $namanya <br />
		            Nomor Handphone: $as[nohp] <br />
			    Skema: $gs[kode_skema] - $gs[judul]<br />
		    		<br /><br />Telah dinyatakan DITOLAK. Silahkan lihat info selengkapnya di laman ".$urldomain.".<br /><br />";
		    
			    $subjek="Pendaftaran Asesmen di SILSP ditolak";
			    $dari = "From: febiharsa@students.unnes.ac.id\r\n";
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
	$mail->AddAddress($email,$namanya);  //tujuan email
	$mail->MsgHTML($pesan);
	if ($mail->Send()){
		echo "";
	}else{
		echo "Notifikasi Email Gagal Terkirim, periksa pengaturan di menu Manajemen, sub menu Pengaturan SMTP";
	}

    	//mail($email,$subjek,$pesan,$dari);
    
		//SMS Pendaftar
    		$isisms="Yth. $namanya, Maaf Pendaftaran Asesmen Skema $gs[kode_skema]-$gs[judul] Anda telah DITOLAK, lihat info di laman http://".$urldomain.".";
		if (strlen($no_hp)>8){
			$sqloutbox="INSERT INTO `outbox` (`DestinationNumber`, `TextDecoded`, `Coding`,`SenderID`,`CreatorID`) VALUES ('$no_hp','$isisms','Default_No_Compression','MyPhone1','MyPhone1')";
			$outbox=$conn->query($sqloutbox);
    		}
		//============================================================================
		echo "<div class='alert alert-danger alert-dismissible'>
		<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
		<h4><i class='icon fa fa-check'></i> Permohonan Asesmen Berhasil Ditolak</h4>
		Anda Telah Berhasil menolak permohonan asesmen Asesi pada skema ini<b></b></div>";
	}else{
		echo "<script>alert('Maaf Asesi sudah ditolak pada skema ini'); window.location = '?module=syarat&id=$_GET[id]&ida=$_GET[ida]'</script>";
	}


}


	$sqlgetskema="SELECT * FROM `skema_kkni` WHERE `id`='$_GET[id]'";
	$getskema=$conn->query($sqlgetskema);
	$gs=$getskema->fetch_assoc();
	echo "
    <!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
        Dokumen Persyaratan Uji Kompetensi Asesi
        <small>Input Data</small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li class='active'>Data Dokumen Persyaratan</li>
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
			<h2><b>$gs[kode_skema]</b>- $gs[judul]</h2>
			<table id='example' class='table table-bordered table-striped'>
				<thead><tr><th>No</th><th>Persyaratan</th></tr></thead>
				<tbody>";
				$no=1;
				$sqllsp="SELECT * FROM `skema_persyaratan` WHERE `id_skemakkni`='$gs[id]' ORDER BY `id` ASC";
				$lsp=$conn->query($sqllsp);
				while ($pm=$lsp->fetch_assoc()){
					echo "<tr class=gradeX><td>$no</td>";
					echo "</td><td>$pm[persyaratan]</td></tr>";
					/*echo "<td><form name='frm' method='POST' role='form' enctype='multipart/form-data'>
						<input type='hidden' name='iddelsy' value='$pm[id]'><input type='submit' name='hapussy' class='btn btn-danger btn-xs' title='Hapus' value='Hapus'></form></td></tr>"; */
					$no++;
				}
			echo "</tbody>
			</table>
		
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
				$sqlbiaya="SELECT * FROM `biaya_sertifikasi` WHERE `id_skemakkni`='$gs[id]'";
				$biayanya=$conn->query($sqlbiaya);
				$totbiaya=0;
				while ($bys=$biayanya->fetch_assoc()){
					$tampilbiaya="Rp. ".number_format($bys['nominal'],0,",",".");
					$sqljenisbi="SELECT * FROM `biaya_jenis` WHERE `id`='$bys[jenis_biaya]'";
					$jenisbi=$conn->query($sqljenisbi);
					$jnb=$jenisbi->fetch_assoc();
					echo "<tr><td>$jnb[jenis_biaya]</td><td>$tampilbiaya</td></tr>";
					$totbiaya=$totbiaya+$bys['nominal'];
				}
				$totbiayatampil="Rp. ".number_format($totbiaya,0,",",".");
				echo"</tbody>
				<tfoot><tr><th>Total Biaya</th><th>$totbiayatampil</th></tr></tfoot>
			</table>
			</div>
			<h4  class='box-title text-green'><b>Pembayaran telah dilakukan dan dikonfirmasi</b></h4>";
			$sqlgetdatakonfirm="SELECT * FROM `asesi_pembayaran` WHERE `id_asesi`='$as[no_pendaftaran]' AND `id_skemakkni`='$gs[id]'";
			$getdatakonfirm=$conn->query($sqlgetdatakonfirm);
			$dkf=$getdatakonfirm->fetch_assoc();
			$sqlgetrek="SELECT * FROM `rekeningbayar` WHERE `id`='$dkf[tujuan_rek]'";
			$getrek=$conn->query($sqlgetrek);
			$rek=$getrek->fetch_assoc();
			echo "<p><b>Pembayaran dilakukan tanggal ".$dkf['tgl_bayar']." pukul ".$dkf['jam_bayar']." dengan nominal Rp. ".number_format($dkf['nominal'],0,",",".")." ke Rekening ".$rek['bank']." No. ".$rek['norek']."</b></p>";
			if ($dkf['status']=='P'){
				echo "<p class='text-red'>Lakukan pengecekan rekening atas pembayaran tersebut, dan bila telah valid, klik tombol <b>Validasi Pembayaran</b></p><br>
				<form role='form' method='POST' enctype='multipart/form-data'>
					<input type='hidden' name='id_bayar' value='$dkf[id]'>
						<button type='submit' class='btn btn-success' name='validasipembayaran' title='Nyatakan pembayaran telah valid'>Validasi Pembayaran</button>
					</form>";
			}else{
				echo "<p><a class='btn btn-success btn-xs'><span class='glyphicon glyphicon-ok' aria-hidden='true' title='Pembayaran telah divalidasi'></span></a>  <b>Pembayaran telah divalidasi</b></p>";
			}
		echo"</div>
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
			function getAge($date) { // Y-m-d format
				$now = explode("-", date('Y-m-d'));
				$dob = explode("-", $date);
				$dif = $now[0] - $dob[0];
				if ($dob[1] > $now[1]) { // birthday month has not hit this year
					$dif -= 1;
				}
				elseif ($dob[1] == $now[1]) { // birthday month is this month, check day
					if ($dob[2] > $now[2]) {
						$dif -= 1;
					}
					elseif ($dob[2] == $now[2]) { // Happy Birthday!
						$dif = $dif." Happy Birthday!";
					};
				};
				return $dif;
			}

			$usia=getAge($as['tgl_lahir']);

			if ($usia<61){
				$syaratusia="<font color='green'><b>Calon Asesi telah memenuhi Persyaratan Usia</b></font>";
			}else{
				$syaratusia="<font color='red'><b>Maaf, Calon Asesi tidak memenuhi Persyaratan Usia</b></font>";

			}
			$sqlpendidikan="SELECT * FROM `pendidikan` WHERE `id`='$as[pendidikan]'";
			$pendidikan=$conn->query($sqlpendidikan);
			$pdas=$pendidikan->fetch_assoc();
			if ($as['pendidikan']>1){
				$syaratpend="<font color='green'><b>Calon Asesi telah memenuhi Persyaratan Pendidikan</b></font>";
			}else{
				$syaratpend="<font color='red'><b>Maaf, Calon Asesi tidak memenuhi Persyaratan Pendidikan</b></font>";

			}
			
			echo"<p>Asesi bernama <b>$as[nama]</b>, Nomor Pendaftaran <b>$as[no_pendaftaran]</b><br>
			Usia Calon Asesi adalah <b>$usia tahun</b>, $syaratusia<br>
			Pendidikan terakhir Calon Asesi adalah <b>$pdas[jenjang_pendidikan]</b>, $syaratpend<br></p>";

			$querycekasesmen = "SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$_GET[id]' AND `status`='R'";
			$cekasesmen=$conn->query($querycekasesmen);
			$asesmen=$cekasesmen->num_rows;

			if($asesmen<>0){

				echo "<div class='alert alert-danger alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-warning'></i> Permohonan Asesmen Ditolak</h4>
				Permohonan asesmen Asesi pada skema ini dinyatakan ditolak<b></b></div>";
			}
			$querycekasesmen1 = "SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$_GET[id]' AND `status`='A'";
			$cekasesmen1=$conn->query($querycekasesmen1);
			$asesmen1=$cekasesmen1->num_rows;

			if($asesmen1<>0){

				echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Permohonan Asesmen Disetujui</h4>
				Permohonan asesmen Asesi pada skema ini dinyatakan disetujui<b></b></div>";
			}



			if ($usia<61 && $as['pendidikan']>1){
			echo "<div class='row'>
		    	<div class='box-body'>

			<h3>Data Dokumen Persyaratan Asesi</h3>
			<div style='overflow-x:auto;'>
			<table id='table-example' class='table table-bordered table-striped'>
		
			<thead><tr><th>No.</th><th>Persyaratan<th>File Pendukung</th><th>Status</th></tr></thead>
			<tbody>";

			$no=1;
			$sqlasesidoc="SELECT * FROM `asesi_doc` WHERE `id_asesi`='$as[no_pendaftaran]' AND `id_skemakkni`='$_GET[id]' ORDER BY `id` DESC";
			$asesidoc=$conn->query($sqlasesidoc);
			while ($pm=$asesidoc->fetch_assoc()){
				switch ($pm['status']){
				default:
					$statusnya="<form role='form' method='POST' enctype='multipart/form-data'>
					<input type='hidden' name='id_doc' value='$pm[id]'>
						<button type='submit' class='btn btn-success btn-xs' name='setujuidoc'>Setujui</button>
						<button type='submit' class='btn btn-danger btn-xs' name='tolakdoc'>Tolak</button>
					</form>";
				break;
				case "A":
					$statusnya="<font color='green'><b>Disetujui</b></font>";
				break;
				case "R":
					$statusnya="<font color='red'><b>Ditolak</b></font>";
				break;
				}
			$portfolioskpi=$conn->query("SELECT * FROM `skema_persyaratan` WHERE `id`='$pm[skema_persyaratan]'");
			$prt=$portfolioskpi->fetch_assoc();
			echo "<tr class=gradeX><td>$no</td><td><b>$prt[persyaratan]</b><br>$pm[nama_doc]<br>No. Dokumen : <b><a href='#myModal".$pm['id']."' data-toggle='modal' data-id='".$pm['id']."'>$pm[nomor_doc]</a></b><br>Tanggal Dok.: <b>".tgl_indo($pm['tgl_doc'])."</b></td><td>"; 
			if (!empty($pm['file'])){
				echo"<a class='btn btn-success btn-xs'><span class='glyphicon glyphicon-ok' aria-hidden='true' title='Dokumen Berhasil Diunggah'></span></a>";
				echo"&nbsp;<a href='#myModal".$pm['id']."' class='btn btn-primary btn-xs' data-toggle='modal' data-id='".$pm['id']."'><span class='glyphicon glyphicon-zoom-in' aria-hidden='true' title='Lihat/Unduh Dokumen'></span></a>";
			}else{
				echo "<span class='text-red'>Tidak ada dokumen</span>";
			}

			echo"</td><td>$statusnya";
			
			echo "</td></tr>";
			$no++;
			echo "<script>
				$(function(){
							$(document).on('click','.edit-record',function(e){
								e.preventDefault();
								$('#myModal".$pm['id']."').modal('show');
							});
					});
			</script>
			<!-- Modal -->
				<div class='modal fade' id='myModal".$pm['id']."' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
					<div class='modal-dialog'>
						<div class='modal-content'>
							<div class='modal-header'>
								<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Tutup</span></button>
								<h4 class='modal-title' id='myModalLabel'>Dokumen Porfolio ".$pm['nama_doc']."</h4>
								<h5 class='modal-title' id='myModalLabel'>Dokumen Porfolio ".$as['nama']." No. Pendaftaran ".$as['no_pendaftaran']."</h5>
							</div>
							<div class='modal-body'><img src='../foto_asesi/$pm[file]' width='100%'/>
							</div>
							<div class='modal-footer'>";
								if ($pm['status']=='P'){
								echo "<form role='form' method='POST' enctype='multipart/form-data'>
									<input type='hidden' name='id_doc' value='$pm[id]'>
									<button type='submit' class='btn btn-success' name='setujuidoc'>Setujui</button>
									<button type='submit' class='btn btn-danger' name='tolakdoc'>Tolak</button>
								</form>";
								}
								echo"<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>
							</div>
						</div>
						</div>
				</div>";

			}

			echo "</tbody></table>
			</div>
			<br />
			<form role='form' method='POST' enctype='multipart/form-data'>
			<input type='hidden' name='biaya_asesmen' value='$totbiaya'>
			<div align='left' class='col-md-6 col-sm-6 col-xs-6'>
				<a class='btn btn-default' id=reset-validate-form href='?module=asesi'>Kembali</a>
			</div>
			<div align='right' class='col-md-6 col-sm-6 col-xs-6'>";
			$querycekasesmen2 = "SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$_GET[ida]' AND `id_skemakkni`='$_GET[id]' AND `status`='P'";
			$cekasesmen2=$conn->query($querycekasesmen2);
			$asesmen2=$cekasesmen2->num_rows;
				if($asesmen2<>0){
				echo "<a href='#myModalAs' data-toggle='modal' data-id='".$as['no_pendaftaran']."' class='btn btn-success' name='setujuiasesmen'>Setujui Pendaftaran</a>";
				echo "<button type='submit' class='btn btn-danger' name='tolakasesmen'>Tolak Pendaftaran</button>";
				}
			echo"</div>
			</form>";

// =============================================================================

echo "		<!-- modal -->
		<div class='modal fade' id='myModalAs' tabindex='-1' role='dialog'>
			<div class='modal-dialog'>
				<div class='modal-content'>
					<div class='modal-header'>
								<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Tutup</span></button>
								<h4 class='modal-title' id='myModalLabelAs'>Persetujuan Pendaftaran Asesmen</h4>
								<h5 class='modal-title' id='myModalLabelAs'>".$gs['kode_skema']." - ".$gs['judul']."</h5>
								<h5 class='modal-title' id='myModalLabelAs'>Asesi ".$as['nama']." No. Pendaftaran ".$as['no_pendaftaran']."</h5>					</div> 
					<div class='modal-body'>     
						<form role='form' method='POST' enctype='multipart/form-data'>";
									$sqlcekjadwalasesmen="SELECT * FROM `jadwal_asesmen` WHERE `id_skemakkni`='$gs[id]' AND `tgl_asesmen` >= '$today'";
									$cekjadwalasesmen=$conn->query($sqlcekjadwalasesmen);
									$jumjadwal=$cekjadwalasesmen->num_rows;
									if ($jumjadwal==0){
										echo "<label class='text-red'>Belum Ada Jadwal Asesmen untuk Skema ini/ Jadwal Belum Diinput</label>";
									}else{
										echo "<label>Pilih Jadwal Asesmen</label>
										<select name='jadwalasesmen' id='jadwalasesmen' class='form-control' required>
											<option>-- Pilih Jadwal Asesmen --</option>";
											$today=date("Y-m-d");
											$sqljadwalasesmen="SELECT * FROM `jadwal_asesmen` WHERE `id_skemakkni`='$gs[id]' AND `tgl_asesmen` >= '$today'";
											$jadwalasesmen=$conn->query($sqljadwalasesmen);
											while ($jdw=$jadwalasesmen->fetch_assoc()){
												$tanggalassesmen=tgl_indo($jdw['tgl_asesmen']);
												echo "<option value='$jdw[id]'>$jdw[tahun] $jdw[periode] Gelombang $jdw[gelombang] ($tanggalassesmen)</option>";
											}
										echo "</select>
										<label>Deskripsi Jadwal</label>
										<p class='text-red' id='deskripsijadwal'>Pilih jadwal terlebih dahulu</p>";
									}
					echo "</div>
					<div class='modal-footer'>";
						if ($jumjadwal!=0){
							echo "<button type='submit' class='btn btn-success' name='setujuiasesmen'>Setujui Pendaftaran</button>";
						}
						echo "</form>
						<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>
					</div> 
				</div>
			</div>
		</div>
		<!-- //modal --> ";

// =============================================================================
			
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

// Bagian Jadwal TUK ================================================================================================================
elseif ($_GET['module']=='jadwalasesmen'){
  if ($_SESSION['leveluser']=='pengusul'|| $_SESSION['leveluser']=='user'){
    echo "
    <!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
        Jadwal Uji Kompetensi (Jadwal TUK)
        <small>Input Data</small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li class='active'>Jadwal Uji Kompetensi Sertifikasi Profesi</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class='content'>
      <div class='row'>
        <div class='col-xs-12'>
          <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Data Jadwal Uji Kompetensi Lembaga Sertifikasi Profesi</h3>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>
			<div style='overflow-x:auto;'>
				<table id='example1' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Nama Kegiatan/ Periode, Waktu dan Tempat</th><th>Skema Sertifikasi, Kapasitas Peserta dan Asesor</th></tr></thead>
					<tbody>";
					$no=1;
					$sqljadwaltuk="SELECT * FROM `jadwal_asesmen`";
					$jadwaltuk=$conn->query($sqljadwaltuk);
					while ($pm=$jadwaltuk->fetch_assoc()){
						$masa_berlaku=tgl_indo($pm[masa_berlaku]);
						$sqltukjenis1="SELECT * FROM `tuk_jenis` WHERE `id`='$pm[jenis_tuk]'";
						$jenistuk=$conn->query($sqltukjenis1);
						$jt=$jenistuk->fetch_assoc();
						$sqllspinduk="SELECT * FROM `lsp` WHERE `id`='$pm[lsp_induk]'";
						$lspinduk=$conn->query($sqllspinduk);
						$li=$lspinduk->fetch_assoc();
						$sqltuk="SELECT * FROM `tuk` WHERE `id`='$pm[tempat_asesmen]'";
						$tuk=$conn->query($sqltuk);
						$tt=$tuk->fetch_assoc();
						$tglasesmen=tgl_indo($pm[tgl_asesmen]);
						if (empty($pm['nama_kegiatan'])){
							$namakegiatan=$pm['periode']." ".$pm['tahun']." Gelombang ".$pm['gelombang'];
						}else{
							$namakegiatan=$pm['nama_kegiatan']." (".$pm['periode']."-".$pm['gelombang'].")";
						}
						echo "<tr class=gradeX><td>$no</td><td><b>$namakegiatan</b><br>Tanggal : <b>$tglasesmen</b> Pukul : <b>$pm[jam_asesmen]</b></br>Tempat :<br><b>$tt[nama]</b><br>$tt[alamat] $tt[kelurahan]</td>";
						$sqlskemakkni="SELECT * FROM `skema_kkni` WHERE `id`='$pm[id_skemakkni]'";
						$skemakkni=$conn->query($sqlskemakkni);
						$skm=$skemakkni->fetch_assoc();
						echo "<td width='40%'><b>$skm[kode_skema]-$skm[judul]</b><br>";
						$namaskkni=$conn->query("SELECT * FROM `skkni` WHERE `id`='$skm[id_skkni]'");
						$nsk=$namaskkni->fetch_assoc();
						$pesertaasesmen=$conn->query("SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`='$pm[id]'");
						$jumps=$pesertaasesmen->num_rows;
						echo "$nsk[nama]<br>Maksimal Peserta : <b>$pm[kapasitas] Asesi</b><br>Peserta Terjadwal : <b>$jumps Asesi</b>";
						if ($jumps > 10){
							echo " <font color='red'><b>( OVERLOAD )</b></font>";
						}
						$sqlasesor="SELECT * FROM `asesor` WHERE `id`='$pm[id_asesor]'";
						$asesor=$conn->query($sqlasesor);
						$asr=$asesor->fetch_assoc();
						if (!empty($asr['gelar_depan'])){
							if (!empty($asr['gelar_blk'])){
								$namaasesor=$asr['gelar_depan']." ".$asr['nama'].", ".$asr['gelar_blk'];
							}else{
								$namaasesor=$asr['gelar_depan']." ".$asr['nama'];
							}
						}else{
							if (!empty($asr['gelar_blk'])){
								$namaasesor=$asr['nama'].", ".$asr['gelar_blk'];
							}else{
								$namaasesor=$asr['nama'];
							}
						}
						echo "<br>Asesor :<br><b>$namaasesor</b><br>No. Surat Tugas : $pm[no_surattugas]<b></b></td></tr>";
						$no++;
					}
				echo "</tbody></table>
			</div>
			</div>
		  </div>
          
		</div>
	  </div>
	</section>";
	}
}

// Bagian Peserta Asesmen ================================================================================================================
elseif ($_GET['module']=='pesertaasesmen'){
  if ($_SESSION['leveluser']=='pengusul'|| $_SESSION['leveluser']=='user'){
    echo "
    <!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
        Peserta (Asesi) Uji Kompetensi
        <small>Data Peserta Asesmen</small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li class='active'>Peserta/Calon Asesi Uji Kompetensi Sertifikasi Profesi</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class='content'>
      <div class='row'>
        <div class='col-xs-12'>
          <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Data Peserta Uji Kompetensi Lembaga Sertifikasi Profesi</h3>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>
			
				<table id='example1' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Identitas Peserta/Asesi</th><th>Aksi</th></tr></thead>
					<tbody>";
					$no=1;
					$sqljadwaltuk="SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`='$_GET[idj]'";
					$jadwaltuk=$conn->query($sqljadwaltuk);
					while ($pm=$jadwaltuk->fetch_assoc()){
						
						$sqlasesi="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$pm[id_asesi]'";
						$asesi=$conn->query($sqlasesi);
						$as=$asesi->fetch_assoc();
						echo "<tr class=gradeX><td>$no</td><td><b>$as[nama]</b><br>No. Pendaftaran : $pm[id_asesi]<br>No. HP : $as[nohp]</td>
						<td width='20%'><a href='form-apl-01.php?ida=$pm[id_asesi]&idj=$_GET[idj]' class='btn btn-primary btn-xs btn-block' title='FORMULIR PERMOHONAN SERTIFIKASI KOMPETENSI ($as[nama])'>Unduh FORM-APL-01</a><br>
						<a href='form-apl-02.php?ida=$pm[id_asesi]&idj=$_GET[idj]' class='btn btn-primary btn-xs btn-block' title='FORMULIR ASESMEN MANDIRI ($as[nama])'>Unduh FORM-APL-02</a><br>
						<a href='form-mak-01.php?ida=$pm[id_asesi]&idj=$_GET[idj]' class='btn btn-success btn-xs btn-block' title='FORMULIR PERSETUJUAN ASESMEN DAN KERAHASIAAN ($as[nama])'>Unduh FORM-MAK-01</a></td>";
						echo "</tr>";
						$no++;
					}
				echo "</tbody></table>
			
			</div>
		  </div>
		  <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Info Jadwal Asesmen</h3>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>";
			$sqljadwaltuk2="SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
			$jadwaltuk2=$conn->query($sqljadwaltuk2);
			$jdt=$jadwaltuk2->fetch_assoc();
			$sqltuk="SELECT * FROM `tuk` WHERE `id`='$jdt[tempat_asesmen]'";
			$tuk=$conn->query($sqltuk);
			$tt=$tuk->fetch_assoc();

			$masa_berlaku=tgl_indo($tt[masa_berlaku]);
			$sqltukjenis1="SELECT * FROM `tuk_jenis` WHERE `id`='$tt[jenis_tuk]'";
			$jenistuk=$conn->query($sqltukjenis1);
			$jt=$jenistuk->fetch_assoc();
			
			$tglasesmen=tgl_indo($jdt[tgl_asesmen]);
			$sqlskemakkni="SELECT * FROM `skema_kkni` WHERE `id`='$jdt[id_skemakkni]'";
			$skemakkni=$conn->query($sqlskemakkni);
			$skm=$skemakkni->fetch_assoc();
			
			echo "<table id='example1' class='table table-bordered table-striped'>
			<tbody><tr><td>Skema</td><td><b>$skm[kode_skema]-$skm[judul]</b><br>";

			$namaskkni=$conn->query("SELECT * FROM `skkni` WHERE `id`='$skm[id_skkni]'");
			$nsk=$namaskkni->fetch_assoc();
			$pesertaasesmen=$conn->query("SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`='$jdt[id]'");
			$jumps=$pesertaasesmen->num_rows;

			echo "$nsk[nama]</td></tr>
			<tr><td>Periode</td><td><b>$jdt[periode] $jdt[tahun]</td></tr>
			<tr><td>Gelombang</td><td><b>$jdt[gelombang]</b></td></tr>
			<tr><td>Tanggal</td><td><b>$tglasesmen</b></td></tr>
			<tr><td>Pukul</td><td><b>$jdt[jam_asesmen]</b></td></tr>
			<tr><td>Tempat</td><td><b>$tt[nama]</b><br>$tt[alamat] $tt[kelurahan]</td></tr>
			<tr><td>Maksimal Peserta</td><td><b>$jdt[kapasitas] Asesi</b></td></tr>
			<tr><td>Peserta Terjadwal</td><td><b>$jumps Asesi</b></td></tr>";
			
			$sqlasesor="SELECT * FROM `asesor` WHERE `id`='$jdt[id_asesor]'";
			$asesor=$conn->query($sqlasesor);
			$asr=$asesor->fetch_assoc();
			if (!empty($asr['gelar_depan'])){
				if (!empty($asr['gelar_blk'])){
					$namaasesor=$asr['gelar_depan']." ".$asr['nama'].", ".$asr['gelar_blk'];
				}else{
					$namaasesor=$asr['gelar_depan']." ".$asr['nama'];
				}
			}else{
				if (!empty($asr['gelar_blk'])){
					$namaasesor=$asr['nama'].", ".$asr['gelar_blk'];
				}else{
					$namaasesor=$asr['nama'];
				}
			}

			echo "<tr><td>Asesor</td><td><b>$namaasesor</b></td></tr></tbody></table><br>
				
			<div align='left' class='col-md-6 col-sm-6 col-xs-6'>
				<a href='daftarhadir.php?idj=$_GET[idj]' class='btn btn-primary'>Unduh Daftar Hadir</a>
			</div>
			<div align='right' class='col-md-6 col-sm-6 col-xs-6'>";
			
				echo "<a href='?module=jadwalasesmen' class='btn btn-success'>Lihat Jadwal Lainnya</a>";
			echo"</div>

			</div>
		  </div>
          
		</div>
	  </div>
	</section>";
	}
}

// Bagian Konten Frontpage LSP ==========================================================================================================
elseif ($_GET['module']=='konten'){
  if ($_SESSION['leveluser']=='pengusul'|| $_SESSION['leveluser']=='user'){
    if( isset( $_REQUEST['hapuskonten'] ))
	{
	$cekdu="SELECT * FROM `frontpage` WHERE `id`='$_POST[iddelkonten]'";
	$result = $conn->query($cekdu);
	if ($result->num_rows != 0){
		$conn->query("DELETE FROM `frontpage` WHERE `id`='$_POST[iddelkonten]'");
        echo "<div class='alert alert-danger alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Hapus Data Sukses</h4>
			Anda Telah Berhasil Menghapus Data <b>Konten</b></div>";

	}else{
		echo "<script>alert('Maaf Konten tersebut Tidak Ditemukan');</script>";
	}
    }
    echo "
    <!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
        Konten Web
        <small>Data Konten Web</small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li class='active'>Data Konten Web</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class='content'>
      <div class='row'>
        <div class='col-xs-12'>
          <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Data Konten Frontpage Website LSP</h3>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>
			<div class='alert alert-info alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-info'></i> PERHATIAN !</h4>
				Sebelum Anda Menambah/Mengubah Data <b>Konten</b>, pastikan Anda telah membaca <b>Panduan Pengaturan Konten</b> terlebih dahulu.</div>
			<div style='overflow-x:auto;'>
				<table id='example1' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Konten</th><th>Kategori</th><th>Aksi</th></tr></thead>
					<tbody>";
					$no=1;
					$sqllsp="SELECT * FROM `frontpage` ORDER BY `tanggal_terbit` DESC";
					$lsp=$conn->query($sqllsp);
					while ($pm=$lsp->fetch_assoc()){
						$tahunskr=date("Y");



						echo "<tr class=gradeX><td>$no</td><td>
						  <div class='box box-widget widget-user-2'>
							<!-- Add the bg color to the header using any of the bg-* classes -->";

							  echo "<div class='widget-user-image'>";
								if ($pm['konten_foto']==''){
									echo "<img class='img-responsive' src='../images/default.jpg' alt='User Avatar'>";
								}else{
									echo "<img class='img-responsive' src='../foto_konten/$pm[konten_foto]' alt='Foto Konten'>";
								}
								
							
							echo "</div>
							<!-- /.widget-user-image -->
							<h3 class='widget-user-username'><b>$pm[judul]</b></h3>
							<h5 class='widget-user-desc'>$pm[sub_judul]</h5>";
							$kontentampil=substr($pm['konten'],0,300);
							$tglterbit=tgl_indo($pm['tanggal_terbit']);
							echo "<h6 class='widget-user-desc'><b>Dipublikasikan $tglterbit Pukul $pm[waktu_terbit]</b></h6><p>$kontentampil</h6></div>
							
						  </div>
						  <!-- /.widget-user --></td>";
						
						$sqlgetkategori="SELECT * FROM `frontpage_kategori` WHERE `id`='$pm[kategori]'";
						$getkategori=$conn->query($sqlgetkategori);
						$gk=$getkategori->fetch_assoc();
						echo "</td><td>$gk[kategori]</td><td>";
					    	echo "<form name='frm' method='POST' role='form' enctype='multipart/form-data'>
							<input type='hidden' name='iddelkonten' value='$pm[id]'><input type='submit' name='hapuskonten' class='btn btn-danger btn-xs' title='Hapus' value='Hapus'></form>
							<a href='?module=updatekonten&id=$pm[id]' class='btn btn-primary btn-xs'>Perbarui</a></td>";
						echo "</tr>";
						$no++;
					}
				echo "</tbody></table></div><br><a href='?module=tambahkonten' class='btn btn-primary'>Tambah Konten Baru</a>			
			</div>
		  </div>
          
		</div>
	  </div>
	</section>";
  }
}

// Bagian Tambah Konten Frontpage Web LSP ==========================================================================================================
elseif ($_GET['module']=='tambahkonten'){
  if ($_SESSION['leveluser']=='pengusul'|| $_SESSION['leveluser']=='user'){




echo "<!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
	  Tambah Konten Web
        <small></small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li><a href='media.php?module=konten'>Konten Web</a></li>
        <li class='active'>Tambah</li>
      </ol>
    </section>";
	function uploadFoto($file){
		//$file_max_weight = 20097152; //Ukuran maksimal yg dibolehkan(2Mb)
		$ok_ext = array('jpg','png','gif','jpeg','JPG','PNG','GIF','JPEG'); // ekstensi yang diijinkan
		$destination = "../foto_konten/"; // tempat buat upload
		$filename = explode(".", $file['name']); 
		$file_name = $file['name'];
		$file_name_no_ext = isset($filename[0]) ? $filename[0] : null;
		$file_extension = $filename[count($filename)-1];
		$file_weight = $file['size'];
		$file_type = $file['type'];

		// Jika tidak ada error
		if( $file['error'] == 0 ){					
			$dateNow = date_create();
			$time_stamp = date_format($dateNow, 'U');
				if( in_array($file_extension, $ok_ext)):
					//if( $file_weight <= $file_max_weight ):
						$fileNewName = $time_stamp.md5( $file_name_no_ext[0].microtime() ).".".$file_extension;
						$alamatfile=$fileNewName;
						if( move_uploaded_file($file['tmp_name'], $destination.$fileNewName) ):
							//echo" File uploaded !";
						else:
							//echo "can't upload file.";
						endif;
					//else:
						//echo "File too heavy.";
					//endif;
				else:
					//echo "File type is not supported.";
				endif;
				}	
		return $alamatfile;
		}

	if( isset( $_REQUEST['simpan'] ))
	{				
		
		$file = $_FILES['file'];
				
		if(empty($_FILES['file']['tmp_name']) || !is_uploaded_file($_FILES['file']['tmp_name']))
		{
			$alamatfile=$rowAgen['foto'];
					
		}else {
			unlink('../foto_konten/'.$rowAgen['foto']);
			$alamatfile = uploadFoto($file);
		}
		$sqllogin="SELECT * FROM `frontpage` WHERE `judul`='$_POST[judul]' AND `sub_judul`='$_POST[sub_judul]' AND `konten`='$_POST[konten]' AND `kategori`='$_POST[kategori]'";
		$login=$conn->query($sqllogin);
		$ketemu=$login->num_rows;
		if ($ketemu==0){
			$query = "INSERT INTO `frontpage`(`judul`, `sub_judul`, `konten`, `kategori`, `konten_foto`, `tanggal_terbit`, `waktu_terbit`) VALUES ('$_POST[judul]','$_POST[sub_judul]','$_POST[konten]','$_POST[kategori]','$alamatfile','$_POST[tanggal_terbit]','$_POST[waktu_terbit]')";
			if ($conn->query($query) == TRUE) {					
				echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Tambah Data Sukses</h4>
				Anda Telah Berhasil Menambah Data <b>Konten</b></div>";
			//echo('<script>location.href = 'Location: editdata.php?type=$type&id=$id&edit=sukses';</script>');
				die("<script>location.href = 'media.php?module=konten'</script>");
			} else {
				echo "Error: " . $query . "<br>" . $conn->error;
			}
		}else{
			echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-times'></i> Tambah Data Gagal</h4>
			Data telah ditambahkan sebelumnya</div>";
			die("<script>location.href = 'media.php?module=tambahkonten'</script>");


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
		echo"<div class='row'>
		    <div class='col-md-12'>			  
				<div class='col-md-12'>			  
					
					<div class='form-group'>
						<label for='fileID'>
						Foto Konten
						</label>
						<input type='file' name='file' id='fileID' accept='image/*' onchange='readURL(this);'>
						<p class='help-block'>Ukuran foto maksimal 2 MB.</p>
					</div>
				</div>
				<div class='col-md-12'>  
					<div class='form-group'>
						<label>Judul</label>
						<input type='text'  name='judul' class='form-control'>
					</div>
				</div>
				<div class='col-md-12'>  
					<div class='form-group'>
						<label>Sub Judul</label>
						<input required type='text'  name='sub_judul' class='form-control'>
					</div>
				</div>
				<div class='col-md-3'>  
					<div class='form-group'>
						<label>Tanggal Terbit</label>
						<input type='date'  name='tanggal_terbit' class='form-control'>
					</div>
				</div>
				<div class='col-md-3'>  
					<div class='form-group'>
						<label>Jam Terbit</label>
						<input required type='time'  name='waktu_terbit' class='form-control'>
					</div>
				</div>
				<div class='col-md-4'>
					<div class='form-group'>
						<label>Kategori/Grup Konten</label>
						<select required class='form-control' name='kategori'>";		
							$sqlgetkategori="SELECT * FROM `frontpage_kategori`";
							$getkategori=$conn->query($sqlgetkategori);
							while ($fkat=$getkategori->fetch_assoc()){
								echo "<option value='$fkat[id]'>$fkat[kategori]</option>";
							}
						echo "</select>
					</div>
				</div>
				<div class='col-md-12'>
					<div class='form-group'>
						<label>Konten</label>
						<textarea id='editor1' name='konten' class='textarea' placeholder='Ketik konten di sini' style='width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;'></textarea>
					</div>
				</div>

              </div>
              <!-- /.box-body -->

	</div>
	<!-- /.row -->


              <div class='box-footer'>
			<div align='left' class='col-md-6 col-sm-6 col-xs-6'>
				<a class='btn btn-default' id=reset-validate-form href='?module=konten'>Kembali</a>
			</div>
			<div align='right' class='col-md-6 col-sm-6 col-xs-6'>
				<button type='submit' class='btn btn-success' name='simpan'>Simpan</button>
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
}

// Bagian Ubah Konten Frontpage Web LSP ==========================================================================================================
elseif ($_GET['module']=='updatekonten'){
  if ($_SESSION['leveluser']=='pengusul'|| $_SESSION['leveluser']=='user'){

	$sqlgetkonten="SELECT * FROM `frontpage` WHERE `id`='$_GET[id]'";
	$getkonten=$conn->query($sqlgetkonten);
	$kn=$getkonten->fetch_assoc();


echo "<!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
	  Ubah Konten Web
        <small></small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li><a href='media.php?module=konten'>Konten Web</a></li>
        <li class='active'>Edit</li>
      </ol>
    </section>";
	function uploadFoto($file){
		//$file_max_weight = 20097152; //Ukuran maksimal yg dibolehkan(2Mb)
		$ok_ext = array('jpg','png','gif','jpeg','JPG','PNG','GIF','JPEG'); // ekstensi yang diijinkan
		$destination = "../foto_konten/"; // tempat buat upload
		$filename = explode(".", $file['name']); 
		$file_name = $file['name'];
		$file_name_no_ext = isset($filename[0]) ? $filename[0] : null;
		$file_extension = $filename[count($filename)-1];
		$file_weight = $file['size'];
		$file_type = $file['type'];

		// Jika tidak ada error
		if( $file['error'] == 0 ){					
			$dateNow = date_create();
			$time_stamp = date_format($dateNow, 'U');
				if( in_array($file_extension, $ok_ext)):
					//if( $file_weight <= $file_max_weight ):
						$fileNewName = $time_stamp.md5( $file_name_no_ext[0].microtime() ).".".$file_extension;
						$alamatfile=$fileNewName;
						if( move_uploaded_file($file['tmp_name'], $destination.$fileNewName) ):
							//echo" File uploaded !";
						else:
							//echo "can't upload file.";
						endif;
					//else:
						//echo "File too heavy.";
					//endif;
				else:
					//echo "File type is not supported.";
				endif;
				}	
		return $alamatfile;
		}

	if( isset( $_REQUEST['simpan'] ))
	{				
		
		$file = $_FILES['file'];
				
		if(empty($_FILES['file']['tmp_name']) || !is_uploaded_file($_FILES['file']['tmp_name']))
		{
			$alamatfile=$kn['konten_foto'];
					
		}else {
			unlink('../foto_konten/'.$rowAgen['foto']);
			$alamatfile = uploadFoto($file);
		}
		$sqlcekkonten="SELECT * FROM `frontpage` WHERE `id`='$_GET[id]'";
		$cekkonten=$conn->query($sqlcekkonten);
		$ketemu=$cekkonten->num_rows;
		if ($ketemu!=0){
			$query = "UPDATE `frontpage` SET `judul`='$_POST[judul]',`sub_judul`='$_POST[sub_judul]',`konten`='$_POST[konten]',`kategori`='$_POST[kategori]',`konten_foto`='$alamatfile',`tanggal_terbit`='$_POST[tanggal_terbit]',`waktu_terbit`='$_POST[waktu_terbit]' WHERE `id`='$_POST[id]'";
			if ($conn->query($query) == TRUE) {					
				echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Tambah Data Sukses</h4>
				Anda Telah Berhasil Menambah Data <b>Konten</b></div>";
				die("<script>location.href = 'media.php?module=konten'</script>");
			} else {
				echo "Error: " . $query . "<br>" . $conn->error;
			}
		}else{
			echo "<div class='alert alert-danger alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-times'></i> Tambah Data Gagal</h4>
			Data tidak ditemukan</div>";
			die("<script>location.href = 'media.php?module=updatekonten&id=$_POST[id]'</script>");


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
		echo"<div class='row'>
		    <div class='col-md-12'>			  
				<div class='col-md-12'>			  
					
					<div class='form-group'>
						<label for='fileID'>";
						if (empty($kn['konten_foto'])){
				              		echo"<img class='img-responsive' src='images/default.jpg' alt='User profile picture' style='width:150px; height:150px;'>";
						}else{
				              		echo"<img class='img-responsive' src='../foto_konten/$kn[konten_foto]' alt='Foto' style='width:150px; height:150px;'>";
						}
						echo "</label>
						<input type='file' name='file' id='fileID' accept='image/*' onchange='readURL(this);'>
						<p class='help-block'>Ukuran foto maksimal 2 MB.</p>
						<input type='hidden' name='id' value='$kn[id]'>

					</div>
				</div>
				<div class='col-md-12'>  
					<div class='form-group'>
						<label>Judul</label>
						<input type='text'  name='judul' value='$kn[judul]' class='form-control'>
					</div>
				</div>
				<div class='col-md-12'>  
					<div class='form-group'>
						<label>Sub Judul</label>
						<input required type='text'  name='sub_judul' value='$kn[sub_judul]' class='form-control'>
					</div>
				</div>
				<div class='col-md-3'>  
					<div class='form-group'>
						<label>Tanggal Terbit</label>
						<input type='date'  name='tanggal_terbit' value='$kn[tanggal_terbit]' class='form-control'>
					</div>
				</div>
				<div class='col-md-3'>  
					<div class='form-group'>
						<label>Jam Terbit</label>
						<input required type='time'  name='waktu_terbit' value='$kn[waktu_terbit]' class='form-control'>
					</div>
				</div>
				<div class='col-md-4'>
					<div class='form-group'>
						<label>Kategori/Grup Konten</label>
						<select required class='form-control' name='kategori'>";		
							$sqlgetkategori="SELECT * FROM `frontpage_kategori`";
							$getkategori=$conn->query($sqlgetkategori);
							while ($fkat=$getkategori->fetch_assoc()){
								if ($kn['kategori']==$fkat['id']){
									echo "<option value='$fkat[id]' selected>$fkat[kategori]</option>";
								}else{
									echo "<option value='$fkat[id]'>$fkat[kategori]</option>";
								}
							}
						echo "</select>
					</div>
				</div>
				<div class='col-md-12'>
					<div class='form-group'>
						<label>Konten</label>
						<textarea id='editor1' name='konten' class='textarea' placeholder='Ketik konten di sini' style='width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;'>$kn[konten]</textarea>
					</div>
				</div>

              </div>
              <!-- /.box-body -->

	</div>
	<!-- /.row -->


              <div class='box-footer'>
			<div align='left' class='col-md-6 col-sm-6 col-xs-6'>
				<a class='btn btn-default' id=reset-validate-form href='?module=konten'>Kembali</a>
			</div>
			<div align='right' class='col-md-6 col-sm-6 col-xs-6'>
				<button type='submit' class='btn btn-success' name='simpan'>Simpan</button>
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
}

// Bagian SMS Notifikasi
elseif ($_GET['module']=='pesanmasuk'){
  if (!empty($_SESSION['namauser'])){

$sqlgetasesordata="SELECT * FROM `pengusul` WHERE `no_pendaftaran`='$_SESSION[namauser]'";
$getasesordata=$conn->query($sqlgetasesordata);
$asr=$getasesordata->fetch_assoc();
echo "<!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
	  SMS Notifikasi ke $asr[nama]
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
              <h3 class='box-title'>Notifikasi SMS yang dikirim Sistem SMS Gateway</h3>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>
			<div style='overflow-x:auto;'>
				<table id='example1' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Waktu Pengiriman</th><th>Isi Pesan</th><th>Status</th></tr></thead>
					<tbody>";
					$no=1;
					$sqlasesiikut="SELECT * FROM `sentitems` WHERE `DestinationNUmber`='$asr[nohp]' ORDER BY `SendingDateTime` DESC";
					$asesiikut=$conn->query($sqlasesiikut);
					while ($pm0=$asesiikut->fetch_assoc()){
						switch ($pm0['Status']){
						case 'SendingOK':
							$statuskirim="<span class='btn-success btn-flat btn-xs'>Terkirim</span>";
						break;
						case 'SendingOKNoReport':
							$statuskirim="<span class='btn-success btn-flat btn-xs'>Terkirim</span>";
						break;
						case 'SendingError':
							$statuskirim="<span class='btn-danger btn-flat btn-xs'>Gagal Terkirim</span>";
						break;
						case 'DeliveryOK':
							$statuskirim="<span class='btn-success btn-flat btn-xs'>Terkirim</span>";
						break;
						case 'DeliveryFailed':
							$statuskirim="<span class='btn-danger btn-flat btn-xs'>Gagal Terkirim</span>";
						break;
						case 'DeliveryPending':
							$statuskirim="<span class='btn-warning btn-flat btn-xs'>Tertahan</span>";
						break;
						case 'DeliveryUnknown':
							$statuskirim="<span class='btn-info btn-flat btn-xs'>Status tidak diketahui</span>";
						break;
						case 'Error':
							$statuskirim="<span class='btn-danger btn-flat btn-xs'>Terjadi kesalahan sistem</span>";
						break;
						}
						echo "<tr class=gradeX><td>$no</td><td><b>$pm0[SendingDateTime]</b></td><td>$pm0[TextDecoded]</td><td>$statuskirim</td></tr>";
						$no++;
					}
					$sqlasesiikut2="SELECT * FROM `outbox` WHERE `DestinationNUmber`='$asr[nohp]' ORDER BY `SendingDateTime` DESC";
					$asesiikut2=$conn->query($sqlasesiikut2);
					while ($pm02=$asesiikut2->fetch_assoc()){
						
						$statuskirim="<span class='btn-danger btn-flat btn-xs'>menunggu</span>";
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
}

// Bagian Profil Admin
elseif ($_GET['module']=='profil'){
$sqllogin="SELECT * FROM `pengusul` WHERE `no_pendaftaran`='$_SESSION[namauser]'";
$login=$conn->query($sqllogin);
$ketemu=$login->num_rows;
$rowAgen=$login->fetch_assoc();


echo "<!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
	  Profil Pengusul
        <small></small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li class='active'>Profil Pengusul</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class='content'>

      <div class='row'>
        <div class='col-md-3'>

          <!-- Profile Image -->
          <div class='box box-primary'>
            <div class='box-body box-profile'>";
		if (empty($rowAgen['foto'])){
              		echo"<img class='profile-user-img img-responsive img-circle' src='../images/default.jpg' alt='User profile picture' style='width:128px; height:128px;'>";
		}else{
              		echo"<img class='profile-user-img img-responsive img-circle' src='../foto_pengguna/$rowAgen[foto]' alt='User profile picture' style='width:128px; height:128px;'>";
		}

              echo "<h3 class='profile-username text-center'>$rowAgen[nama]</h3>

              <p class='text-muted text-center'>$rowAgen[no_pendaftaran]</p>
			  <br>
			  <!--<a href='?module=updateasesor' class='btn btn-block btn-primary'>Ubah Profil</a>
			  <br>
			  <a href='?module=unggahfile' class='btn btn-block btn-primary'>Unggah Dokumen</a>-->
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
              <strong><h3 class='box-title'>Detail Data Pengusul/h3></strong>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>
			<br>
              <strong class='col-md-3'>Nomor Induk Pegawai</strong>
               <span class='col-md-9 col-sm-12 col-xs-12'><b>$rowAgen[no_pendaftaran]</b></span>
			   <br><br>

              <strong class='col-md-3'>Nomor KTP</strong>
               <span class='col-md-9 col-sm-12 col-xs-12'>$rowAgen[no_ktp]</span>
			   <br><br>
              <strong class='col-md-3'>Nama Lengkap dan Gelar</strong>
               <span class='col-md-9 col-sm-12 col-xs-12'>$rowAgen[nama]</span>
			   <br><br>
              <strong class='col-md-3'>Tempat, Tanggal Lahir</strong>";
				$date = tgl_indo($rowAgen['tgl_lahir']);
				$tanggal_lahir = $date;
			  echo"<span class='col-md-9 col-sm-12 col-xs-12'>$rowAgen[tmp_lahir], $tanggal_lahir</span>
			   <br><br>

                           <strong class='col-md-3'>Nomor HP</strong>
               <span class='col-md-9 col-sm-12 col-xs-12'>$rowAgen[nohp]</span>
			   <br><br>
              <strong class='col-md-3'>Email</strong>
               <span class='col-md-9 col-sm-12 col-xs-12'><a href='mailto:$rowAgen[email]'>$rowAgen[email]</a></span>
			   <br><br>
              <strong class='col-md-3'>Nama Institusi Pengusul</strong>
               <span class='col-md-9 col-sm-12 col-xs-12'>$rowAgen[nama_kantor]</span>
			   <br><br>
              <strong class='col-md-3'>Alamat</strong>
               <span class='col-md-9 col-sm-12 col-xs-12'>";
			$sqlwil1="SELECT * FROM `data_wilayah` WHERE `id_wil`='$rowAgen[kecamatan_kantor]'";
			$wilayah1=$conn->query($sqlwil1);
			$wil1=$wilayah1->fetch_assoc();
			$sqlwil2="SELECT * FROM `data_wilayah` WHERE `id_wil`='$rowAgen[kota_kantor]'";
			$wilayah2=$conn->query($sqlwil2);
			$wil2=$wilayah2->fetch_assoc();
			$sqlwil3="SELECT * FROM `data_wilayah` WHERE `id_wil`='$rowAgen[propinsi_kantor]'";
			$wilayah3=$conn->query($sqlwil3);
			$wil3=$wilayah3->fetch_assoc();
			echo $rowAgen['alamat_kantor'].", ".$wil1['nm_wil'].", ".$wil2['nm_wil'].", ".$wil3['nm_wil'];
			   echo"</span><br><br>
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

// Bagian Asesi LSP ==========================================================================================================
elseif ($_GET['module']=='asesibypropinsi'){
  if ($_SESSION['leveluser']=='pengusul'|| $_SESSION['leveluser']=='user'){
    if( isset( $_REQUEST['blokir'] ))
	{
		$sqlblokir="UPDATE `asesi` SET `blokir`='Y' WHERE `id`='$_POST[idblokir]'";
		$conn->query($sqlblokir);
	}
    if( isset( $_REQUEST['bukablokir'] ))
	{
		$sqlblokir="UPDATE `asesi` SET `blokir`='N' WHERE `id`='$_POST[idblokir]'";
		$conn->query($sqlblokir);
	}
	$sqlgetpropinsi="SELECT * FROM `data_wilayah` WHERE `id_wil`='$_GET[wil]'";
	$getpropinsi=$conn->query($sqlgetpropinsi);
	$prop=$getpropinsi->fetch_assoc();
    echo "

   <div id='loading' class='col-xs-12 overlay'>
              <i class='fa fa-refresh fa-spin'></i>
   </div>

    <!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
        Asesi Sertifikasi Profesi
        <small>Data Asesi Berdasarkan Provinsi</small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li><a href='media.php?module=asesi'><i class='fa fa-users'></i> Asesi</a></li>
        <li class='active'>Data Asesi Berdasar Provinsi</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class='content'>
      <div class='row'>
        <div class='col-xs-12'>
          <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Data Asesi Sertifikasi Profesi Provinsi $prop[nm_wil]</h3>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>
		  <!-- Custom Tabs -->
          <div class='nav-tabs-custom'>
            <ul class='nav nav-tabs'>";
				$sqlgetkota="SELECT DISTINCT `kota` FROM `asesi` WHERE `propinsi`='$_GET[wil]' ORDER BY `kota` ASC limit 5";
				$getkota=$conn->query($sqlgetkota);
				$nokot=1;
				while ($kot=$getkota->fetch_assoc()){
					$sqlgetnamakotax="SELECT * FROM `data_wilayah` WHERE `id_wil`='$kot[kota]'";
					$getnamakotax=$conn->query($sqlgetnamakotax);
					$namkotx=$getnamakotax->fetch_assoc();
					$sqlwilasesi0x="SELECT * FROM `asesi` WHERE `kota`='$kot[kota]'";
					$wilasesi0x=$conn->query($sqlwilasesi0x);
					$jumaskotx=$wilasesi0x->num_rows;


					if ($nokot==1){
						echo "<li class='active'><a href='#".$kot['kota']."' data-toggle='tab'>".$namkotx['nm_wil']." (".$jumaskotx.")</a></li>";
					}else{
						echo "<li><a href='#".$kot['kota']."' data-toggle='tab'>".$namkotx['nm_wil']." (".$jumaskotx.")</a></li>";
					}
					$nokot++;
				}
		echo "<li class='dropdown'>
                	<a class='dropdown-toggle' data-toggle='dropdown' href='#'>
                	  Pilih Kota <span class='caret'></span>
                	</a>
                	<ul class='dropdown-menu'>";

				$sqlgetkotay="SELECT DISTINCT `kota` FROM `asesi` WHERE `propinsi`='$_GET[wil]' ORDER BY `kota` ASC";
				$getkotay=$conn->query($sqlgetkotay);
				$nokoty=1;

				while ($koty=$getkotay->fetch_assoc()){
					$sqlgetnamakotay="SELECT * FROM `data_wilayah` WHERE `id_wil`='$koty[kota]'";
					$getnamakotay=$conn->query($sqlgetnamakotay);
					$namkoty=$getnamakotay->fetch_assoc();
					$sqlwilasesi0y="SELECT * FROM `asesi` WHERE `kota`='$koty[kota]'";
					$wilasesi0y=$conn->query($sqlwilasesi0y);
					$jumaskoty=$wilasesi0y->num_rows;


					if ($nokoty>5){
                				echo "<li role='presentation'><a role='menuitem' data-toggle='tab' href='#".$koty['kota']."'>".$namkoty['nm_wil']." (".$jumaskoty.")</a></li>";

						//echo "<li><a href='#".$kot['kota']."' data-toggle='tab'>".$namkotx['nm_wil']." (".$jumaskotx.")</a></li>";
					}
					$nokoty++;
				}

                	echo"</ul>
            	</li>";

            echo"</ul>
            <div class='tab-content'>";
				$sqlgetkota2="SELECT DISTINCT `kota` FROM `asesi` WHERE `propinsi`='$_GET[wil]'";
				$getkota2=$conn->query($sqlgetkota2);
				$nokot2=1;
				while ($kot2=$getkota2->fetch_assoc()){
					if($nokot2==1){
						echo"<div class='tab-pane active' id='".$kot2['kota']."'>";
					}else{
						echo"<div class='tab-pane' id='".$kot2['kota']."'>";
					}
						echo"<div style='overflow-x:auto;'>

							<table id='example".$kot2['kota']."' class='table table-bordered table-striped'>
								<thead><tr><th>No</th><th>Nama Asesi</th><th>Nomor HP</th><th>Aksi</th></tr></thead>
								<tbody>";
								$no=1;
								$sqlwilasesi="SELECT * FROM `asesi` WHERE `kota`='$kot2[kota]' ORDER BY `nama` ASC";
								$wilasesi=$conn->query($sqlwilasesi);
								while ($was=$wilasesi->fetch_assoc()){
									echo "<tr class=gradeX><td>$no</td><td>$was[nama]</td><td>$was[nohp]</td><td></td></tr>";
									$no++;
								}
							echo "</tbody></table>
						</div>
					</div>
					<!-- /.tab-pane -->";
					$nokot2++;
				}
            echo"</div>
            <!-- /.tab-content -->
          </div>
          <!-- nav-tabs-custom -->			
			</div>
		  </div>
          
		</div>
	  </div>
	</section>";
  }
}

// Bagian Import Asesi LSP ==========================================================================================================
elseif ($_GET['module']=='importasesi'){
  if ($_SESSION['leveluser']=='pengusul'|| $_SESSION['leveluser']=='user'){
    echo "<style>
        #loading{
			background: whitesmoke;
			position: absolute;
			top: 140px;
			left: 82px;
			padding: 5px 10px;
			border: 1px solid #ccc;
		}
		</style>
		
		
		
		<script>
		$(document).ready(function(){
			// Sembunyikan alert validasi kosong
			$('#kosong').hide();
		});
	</script>
    <!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
        Asesi Sertifikasi Profesi
        <small>Impor Data Asesi (Pendaftar Baru)</small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li><a href='media.php?module=asesi'><i class='fa fa-users'></i> Asesi</a></li>
        <li class='active'>Impor Data Asesi</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class='content'>
      <div class='row'>
        <div class='col-xs-12'>
          <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Impor Data Asesi Sertifikasi Profesi</h3>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>";
		echo "<!-- Content -->
		<div style='padding: 0 15px;'>
			<!-- Buat sebuah tombol Cancel untuk kemabli ke halaman awal / view data -->
			<a href='media.php?module=asesibaru' class='btn btn-danger pull-right'>
				<span class='glyphicon glyphicon-remove'></span> Kembali
			</a>
			
			<h3>Form Import Data</h3>
			<hr>
			
			<!-- Buat sebuah tag form dan arahkan action nya ke file ini lagi -->
			<form method='post' action='' enctype='multipart/form-data'>
				<a href='Format.xlsx' class='btn btn-default'>
					<span class='glyphicon glyphicon-download'></span>
					Download Format
				</a><br><br>
				
				<!-- 
				-- Buat sebuah input type file
				-- class pull-left berfungsi agar file input berada di sebelah kiri
				-->
				<input type='file' name='file' class='pull-left'>
				
				<button type='submit' name='preview' class='btn btn-success btn-sm'>
					<span class='glyphicon glyphicon-eye-open'></span> Preview
				</button>
			</form>
			
			<hr>";


			if(isset($_POST['preview'])){
				//$ip = ; // Ambil IP Address dari User
				$nama_file_baru = 'data.xlsx';
				
				// Cek apakah terdapat file data.xlsx pada folder tmp
				if(is_file('tmp/'.$nama_file_baru)) // Jika file tersebut ada
					unlink('tmp/'.$nama_file_baru); // Hapus file tersebut
				
				$tipe_file = $_FILES['file']['type']; // Ambil tipe file yang akan diupload
				$tmp_file = $_FILES['file']['tmp_name'];
				
				// Cek apakah file yang diupload adalah file Excel 2007 (.xlsx)
				if($tipe_file == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"){
					// Upload file yang dipilih ke folder tmp
					// dan rename file tersebut menjadi data{ip_address}.xlsx
					// {ip_address} diganti jadi ip address user yang ada di variabel $ip
					// Contoh nama file setelah di rename : data127.0.0.1.xlsx
					move_uploaded_file($tmp_file, 'tmp/'.$nama_file_baru);
					
					// Load librari PHPExcel nya
					require_once 'PHPExcel/PHPExcel.php';
					
					$excelreader = new PHPExcel_Reader_Excel2007();
					$loadexcel = $excelreader->load('tmp/'.$nama_file_baru); // Load file yang tadi diupload ke folder tmp
					$sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);
					
					// Buat sebuah tag form untuk proses import data ke database
					echo "<form method='post' action='import.php'>";
					
					// Buat sebuah div untuk alert validasi kosong
					/* echo "<div class='alert alert-danger' id='kosong'>
					Semua data belum diisi, Ada <span id='jumlah_kosong'></span> data yang belum diisi.
					</div>"; */
					
					echo "<div style='overflow-x:auto;'><table class='table table-bordered'>
					<tr>
						<th colspan='15' class='text-center'>Preview Data</th>
					</tr>
					<tr>
						<th>No. KTP</th>
						<th>Nama</th>
						<th>Jenis Kelamin</th>
						<th>Tempat Lahir</th>
						<th>Tanggal Lahir</th>
						<th>Telepon/HP</th>
						<th>Email</th>
						<th>Alamat</th>
						<th>RT</th>
						<th>RW</th>
						<th>Kelurahan</th>
						<th>Kecamatan</th>
						<th>Kota</th>
						<th>Provinsi</th>
						<th>Kodepos</th>
					</tr>";
					
					$numrow = 1;
					$kosong = 0;
					foreach($sheet as $row){ // Lakukan perulangan dari data yang ada di excel
						// Ambil data pada excel sesuai Kolom
						$no_ktp = $row['A']; // Ambil data NIS
						$nama = $row['B']; // Ambil data nama
						$jenis_kelamin = $row['C']; // Ambil data jenis kelamin
						$tempat_lahir = $row['D']; // Ambil data
						$tanggal_lahir = $row['E']; // Ambil data
						$telepon = $row['F']; // Ambil data
						$email = $row['G']; // Ambil data
						$alamat = $row['H']; // Ambil data
						$rt = $row['I']; // Ambil data
						$rw = $row['J']; // Ambil data
						$kelurahan = $row['K']; // Ambil data
						$kecamatan = $row['L']; // Ambil data
						$kota = $row['M']; // Ambil data
						$propinsi = $row['N']; // Ambil data
						$kodepos = $row['O']; // Ambil data
						// Cek jika semua data tidak diisi
						if(empty($no_ktp) && empty($nama) && empty($jenis_kelamin) && empty($tempat_lahir) && empty($tanggal_lahir) && empty($telepon) && empty($email) && empty($alamat) && empty($rt) && empty($rw) && empty($kelurahan) && empty($kecamatan) && empty($kota) && empty($propinsi) && empty($kodepos))
							continue; // Lewat data pada baris ini (masuk ke looping selanjutnya / baris selanjutnya)
						
						// Cek $numrow apakah lebih dari 1
						// Artinya karena baris pertama adalah nama-nama kolom
						// Jadi dilewat saja, tidak usah diimport
						if($numrow > 1){
							// Validasi apakah semua data telah diisi
							$no_ktp_td = ( ! empty($no_ktp))? "" : " style='background: #E07171;'"; // Jika kosong, beri warna merah
							$nama_td = ( ! empty($nama))? "" : " style='background: #E07171;'"; // Jika Nama kosong, beri warna merah
							$jk_td = ( ! empty($jenis_kelamin))? "" : " style='background: #E07171;'"; // Jika Jenis Kelamin kosong, beri warna merah
							$tempat_lahir_td = ( ! empty($tempat_lahir))? "" : " style='background: #E07171;'"; // Jika kosong, beri warna merah
							$tanggal_lahir_td = ( ! empty($tanggal_lahir))? "" : " style='background: #E07171;'"; // Jika kosong, beri warna merah
							$telepon_td = ( ! empty($telepon))? "" : " style='background: #E07171;'"; // Jika kosong, beri warna merah
							$email_td = ( ! empty($email))? "" : " style='background: #E07171;'"; // Jika kosong, beri warna merah
							$alamat_td = ( ! empty($alamat))? "" : " style='background: #E07171;'"; // Jika kosong, beri warna merah
							$rt_td = ( ! empty($rt))? "" : " style='background: #E07171;'"; // Jika kosong, beri warna merah
							$rw_td = ( ! empty($rw))? "" : " style='background: #E07171;'"; // Jika kosong, beri warna merah
							$kelurahan_td = ( ! empty($kelurahan))? "" : " style='background: #E07171;'"; // Jika kosong, beri warna merah
							$kecamatan_td = ( ! empty($kecamatan))? "" : " style='background: #E07171;'"; // Jika kosong, beri warna merah
							$kota_td = ( ! empty($kota))? "" : " style='background: #E07171;'"; // Jika kosong, beri warna merah
							$propinsi_td = ( ! empty($propinsi))? "" : " style='background: #E07171;'"; // Jika kosong, beri warna merah
							$kodepos_td = ( ! empty($kodepos))? "" : " style='background: #E07171;'"; // Jika kosong, beri warna merah

							// Jika salah satu data ada yang kosong
							if(empty($no_ktp) or empty($nama) or empty($jenis_kelamin) or empty($tempat_lahir) or empty($tanggal_lahir)  or empty($telepon) or empty($email) or empty($alamat) or empty($rt) or empty($rw) or empty($kelurahan)){
								$kosong++; // Tambah 1 variabel $kosong
							}
							
							echo "<tr>";
							echo "<td".$no_ktp_td.">".$no_ktp."</td>";
							echo "<td".$nama_td.">".$nama."</td>";
							echo "<td".$jk_td.">".$jenis_kelamin."</td>";
							echo "<td".$tempat_lahir_td.">".$tempat_lahir."</td>";
							echo "<td".$tanggal_lahir_td.">".$tanggal_lahir."</td>";
							echo "<td".$telepon_td.">".$telepon."</td>";
							echo "<td".$email_td.">".$email."</td>";
							echo "<td".$alamat_td.">".$alamat."</td>";
							echo "<td".$rt_td.">".$rt."</td>";
							echo "<td".$rw_td.">".$rw."</td>";
							echo "<td".$kelurahan_td.">".$kelurahan."</td>";
							echo "<td".$kecamatan_td.">".$kecamatan."</td>";
							echo "<td".$kota_td.">".$kota."</td>";
							echo "<td".$propinsi_td.">".$propinsi."</td>";
							echo "<td".$kodepos_td.">".$kodepos."</td>";
							echo "</tr>";
						}
						
						$numrow++; // Tambah 1 setiap kali looping
					}
					
					echo "</table></div>";
					
					// Cek apakah variabel kosong lebih dari 1
					// Jika lebih dari 1, berarti ada data yang masih kosong
					if($kosong > 1){
					echo"	
						<script>
						$(document).ready(function(){
							// Ubah isi dari tag span dengan id jumlah_kosong dengan isi dari variabel kosong
							$('#jumlah_kosong').html('".$kosong."');
							
							$('#kosong').show(); // Munculkan alert validasi kosong
						});
						</script>";
					}else{ // Jika semua data sudah diisi
						echo "<hr>";
						
						// Buat sebuah tombol untuk mengimport data ke database
						echo "<button type='submit' name='import' class='btn btn-primary'><span class='glyphicon glyphicon-upload'></span> Import</button>";
					}
					
					echo "</form>";
				}else{ // Jika file yang diupload bukan File Excel 2007 (.xlsx)
					// Munculkan pesan validasi
					echo "<div class='alert alert-danger'>
					Hanya File Excel 2007 (.xlsx) yang diperbolehkan
					</div>";
				}
			}


		echo "</div>";
	    echo"</div>
	   </div>
        </div>
      </div>
    </section>";
  }
}

// Bagian Import Asesi Lama LSP ==========================================================================================================
elseif ($_GET['module']=='importasesilama'){
  if ($_SESSION['leveluser']=='pengusul'|| $_SESSION['leveluser']=='user'){
    echo "<style>
        #loading{
			background: whitesmoke;
			position: absolute;
			top: 140px;
			left: 82px;
			padding: 5px 10px;
			border: 1px solid #ccc;
		}
		</style>
		
		
		
		<script>
		$(document).ready(function(){
			// Sembunyikan alert validasi kosong
			$('#kosong').hide();
		});
	</script>
    <!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
        Asesi Sertifikasi Profesi
        <small>Impor Data Asesi Lama (Terdahulu)</small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li><a href='media.php?module=asesi'><i class='fa fa-users'></i> Asesi</a></li>
        <li class='active'>Impor Data Asesi</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class='content'>
      <div class='row'>
        <div class='col-xs-12'>
          <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Impor Data Asesi Sertifikasi Profesi</h3>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>";
		echo "<!-- Content -->
		<div style='padding: 0 15px;'>
			<!-- Buat sebuah tombol Cancel untuk kemabli ke halaman awal / view data -->
			<a href='media.php?module=asesi' class='btn btn-danger pull-right'>
				<span class='glyphicon glyphicon-remove'></span> Kembali
			</a>
			
			<h3>Form Import Data</h3>
			<hr>
			
			<!-- Buat sebuah tag form dan arahkan action nya ke file ini lagi -->
			<form method='post' action='' enctype='multipart/form-data'>
				<a href='Formatdata.xlsx' class='btn btn-default'>
					<span class='glyphicon glyphicon-download'></span>
					Download Format
				</a><br><br>
				
				<!-- 
				-- Buat sebuah input type file
				-- class pull-left berfungsi agar file input berada di sebelah kiri
				-->
				<input type='file' name='file' class='pull-left'>
				
				<button type='submit' name='preview' class='btn btn-success btn-sm'>
					<span class='glyphicon glyphicon-eye-open'></span> Preview
				</button>
			</form>
			
			<hr>";


			if(isset($_POST['preview'])){
				//$ip = ; // Ambil IP Address dari User
				$nama_file_baru = 'data.xlsx';
				
				// Cek apakah terdapat file data.xlsx pada folder tmp
				if(is_file('tmp/'.$nama_file_baru)) // Jika file tersebut ada
					unlink('tmp/'.$nama_file_baru); // Hapus file tersebut
				
				$tipe_file = $_FILES['file']['type']; // Ambil tipe file yang akan diupload
				$tmp_file = $_FILES['file']['tmp_name'];
				
				// Cek apakah file yang diupload adalah file Excel 2007 (.xlsx)
				if($tipe_file == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"){
					// Upload file yang dipilih ke folder tmp
					// dan rename file tersebut menjadi data{ip_address}.xlsx
					// {ip_address} diganti jadi ip address user yang ada di variabel $ip
					// Contoh nama file setelah di rename : data127.0.0.1.xlsx
					move_uploaded_file($tmp_file, 'tmp/'.$nama_file_baru);
					
					// Load librari PHPExcel nya
					require_once 'PHPExcel/PHPExcel.php';
					
					$excelreader = new PHPExcel_Reader_Excel2007();
					$loadexcel = $excelreader->load('tmp/'.$nama_file_baru); // Load file yang tadi diupload ke folder tmp
					$sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);
					
					// Buat sebuah tag form untuk proses import data ke database
					echo "<form method='post' action='importdatalama.php'>";
					
					// Buat sebuah div untuk alert validasi kosong
					/* echo "<div class='alert alert-danger' id='kosong'>
					Semua data belum diisi, Ada <span id='jumlah_kosong'></span> data yang belum diisi.
					</div>"; */
					
					echo "<div style='overflow-x:auto;'><table class='table table-bordered'>
					<tr>
						<th colspan='16' class='text-center'>Preview Data</th>
					</tr>
					<tr>
						<th>No. KTP</th>
						<th>Nama</th>
						<th>Jenis Kelamin</th>
						<th>Tempat Lahir</th>
						<th>Tanggal Lahir</th>
						<th>Telepon/HP</th>
						<th>Email</th>
						<th>Alamat</th>
						<th>RT</th>
						<th>RW</th>
						<th>Kelurahan</th>
						<th>Kecamatan</th>
						<th>Kota</th>
						<th>Provinsi</th>
						<th>Kodepos</th>
						<th>Tanggal Daftar</th>
					</tr>";
					
					$numrow = 1;
					$kosong = 0;
					foreach($sheet as $row){ // Lakukan perulangan dari data yang ada di excel
						// Ambil data pada excel sesuai Kolom
						$no_ktp = $row['A']; // Ambil data NIS
						$nama = $row['B']; // Ambil data nama
						$jenis_kelamin = $row['C']; // Ambil data jenis kelamin
						$tempat_lahir = $row['D']; // Ambil data
						$tanggal_lahir = $row['E']; // Ambil data
						$telepon = $row['F']; // Ambil data
						$email = $row['G']; // Ambil data
						$alamat = $row['H']; // Ambil data
						$rt = $row['I']; // Ambil data
						$rw = $row['J']; // Ambil data
						$kelurahan = $row['K']; // Ambil data
						$kecamatan = $row['L']; // Ambil data
						$kota = $row['M']; // Ambil data
						$propinsi = $row['N']; // Ambil data
						$kodepos = $row['O']; // Ambil data
						$tgldaftar = $row['P']; // Ambil data
						// Cek jika semua data tidak diisi
						if(empty($no_ktp) && empty($nama) && empty($jenis_kelamin) && empty($tempat_lahir) && empty($tanggal_lahir) && empty($telepon) && empty($email) && empty($alamat) && empty($rt) && empty($rw) && empty($kelurahan) && empty($kecamatan) && empty($kota) && empty($propinsi) && empty($kodepos))
							continue; // Lewat data pada baris ini (masuk ke looping selanjutnya / baris selanjutnya)
						
						// Cek $numrow apakah lebih dari 1
						// Artinya karena baris pertama adalah nama-nama kolom
						// Jadi dilewat saja, tidak usah diimport
						if($numrow > 1){
							// Validasi apakah semua data telah diisi
							$no_ktp_td = ( ! empty($no_ktp))? "" : " style='background: #E07171;'"; // Jika kosong, beri warna merah
							$nama_td = ( ! empty($nama))? "" : " style='background: #E07171;'"; // Jika Nama kosong, beri warna merah
							$jk_td = ( ! empty($jenis_kelamin))? "" : " style='background: #E07171;'"; // Jika Jenis Kelamin kosong, beri warna merah
							$tempat_lahir_td = ( ! empty($tempat_lahir))? "" : " style='background: #E07171;'"; // Jika kosong, beri warna merah
							$tanggal_lahir_td = ( ! empty($tanggal_lahir))? "" : " style='background: #E07171;'"; // Jika kosong, beri warna merah
							$telepon_td = ( ! empty($telepon))? "" : " style='background: #E07171;'"; // Jika kosong, beri warna merah
							$email_td = ( ! empty($email))? "" : " style='background: #E07171;'"; // Jika kosong, beri warna merah
							$alamat_td = ( ! empty($alamat))? "" : " style='background: #E07171;'"; // Jika kosong, beri warna merah
							$rt_td = ( ! empty($rt))? "" : " style='background: #E07171;'"; // Jika kosong, beri warna merah
							$rw_td = ( ! empty($rw))? "" : " style='background: #E07171;'"; // Jika kosong, beri warna merah
							$kelurahan_td = ( ! empty($kelurahan))? "" : " style='background: #E07171;'"; // Jika kosong, beri warna merah
							$kecamatan_td = ( ! empty($kecamatan))? "" : " style='background: #E07171;'"; // Jika kosong, beri warna merah
							$kota_td = ( ! empty($kota))? "" : " style='background: #E07171;'"; // Jika kosong, beri warna merah
							$propinsi_td = ( ! empty($propinsi))? "" : " style='background: #E07171;'"; // Jika kosong, beri warna merah
							$kodepos_td = ( ! empty($kodepos))? "" : " style='background: #E07171;'"; // Jika kosong, beri warna merah
							$tgldaftar_td = ( ! empty($tgldaftar))? "" : " style='background: #E07171;'"; // Jika kosong, beri warna merah
							// Jika salah satu data ada yang kosong
							if(empty($no_ktp) or empty($nama) or empty($jenis_kelamin) or empty($tempat_lahir) or empty($tanggal_lahir)  or empty($telepon) or empty($email) or empty($alamat) or empty($rt) or empty($rw) or empty($kelurahan)){
								$kosong++; // Tambah 1 variabel $kosong
							}
							
							echo "<tr>";
							echo "<td".$no_ktp_td.">".$no_ktp."</td>";
							echo "<td".$nama_td.">".$nama."</td>";
							echo "<td".$jk_td.">".$jenis_kelamin."</td>";
							echo "<td".$tempat_lahir_td.">".$tempat_lahir."</td>";
							echo "<td".$tanggal_lahir_td.">".$tanggal_lahir."</td>";
							echo "<td".$telepon_td.">".$telepon."</td>";
							echo "<td".$email_td.">".$email."</td>";
							echo "<td".$alamat_td.">".$alamat."</td>";
							echo "<td".$rt_td.">".$rt."</td>";
							echo "<td".$rw_td.">".$rw."</td>";
							echo "<td".$kelurahan_td.">".$kelurahan."</td>";
							echo "<td".$kecamatan_td.">".$kecamatan."</td>";
							echo "<td".$kota_td.">".$kota."</td>";
							echo "<td".$propinsi_td.">".$propinsi."</td>";
							echo "<td".$kodepos_td.">".$kodepos."</td>";
							echo "<td".$tgldaftar_td.">".$tgldaftar."</td>";
							echo "</tr>";
						}
						
						$numrow++; // Tambah 1 setiap kali looping
					}
					
					echo "</table></div>";
					
					// Cek apakah variabel kosong lebih dari 1
					// Jika lebih dari 1, berarti ada data yang masih kosong
					if($kosong > 1){
					echo"	
						<script>
						$(document).ready(function(){
							// Ubah isi dari tag span dengan id jumlah_kosong dengan isi dari variabel kosong
							$('#jumlah_kosong').html('".$kosong."');
							
							$('#kosong').show(); // Munculkan alert validasi kosong
						});
						</script>";
					}else{ // Jika semua data sudah diisi
						echo "<hr>";
						
						// Buat sebuah tombol untuk mengimport data ke database
						echo "<button type='submit' name='import' class='btn btn-primary'><span class='glyphicon glyphicon-upload'></span> Import</button>";
					}
					
					echo "</form>";
				}else{ // Jika file yang diupload bukan File Excel 2007 (.xlsx)
					// Munculkan pesan validasi
					echo "<div class='alert alert-danger'>
					Hanya File Excel 2007 (.xlsx) yang diperbolehkan
					</div>";
				}
			}


		echo "</div>";
	    echo"</div>
	   </div>
        </div>
      </div>
    </section>";
  }
}

// Bagian Setup SILSP ================================================================================================================
elseif ($_GET['module']=='setup'){
  if ($_SESSION['leveluser']=='pengusul'|| $_SESSION['leveluser']=='user'){
    if( isset( $_REQUEST['tambahkan'] ))
	{
	$cekdu="SELECT * FROM `identitas`";
	$result = $conn->query($cekdu);
	if ($result->num_rows == 0){
		$conn->query("INSERT INTO `identitas`(`nama_lsp`, `meta_judul`, `meta_keyword`, `url_domain`) VALUES ('$_POST[nama_lsp]', '$_POST[meta_judul]', '$_POST[meta_keyword]', '$_POST[url_domain]')");
		$conn->query("INSERT INTO `skkni`(`nama`, `legalitas`) VALUES ('$_POST[nama]','$_POST[legalitas]')");
        echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Input Data Sukses</h4>
			Anda Telah Berhasil Input Data <b>Indentitas LSP</b></div>";

	}else{
		echo "<script>alert('Identitas LSP telah tersedia');</script>";
	}
    }
    $cekdu0="SELECT * FROM `identitas`";
    $result0 = $conn->query($cekdu0);
    if ($result0->num_rows == 0){

    echo "
    <!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
        Identitas Lembaga Sertifikasi Profesi (LSP)
        <small>Setup Data SILSP</small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li class='active'>Setup SILSP</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class='content'>
      <div class='row'>
        <div class='col-xs-12'>
		  <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Pengaturan Awal Sistem Informasi Lembaga Sertifikasi Profesi (LSP)</h3>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>
			
				<form name='frm' method='POST' role='form' enctype='multipart/form-data'>
					<div class='col-md-6'>
						<div class='form-group'>
							<label>Nama LSP</label>
							<input type='text' name='nama_lsp' class='form-control' required>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
							<label>Judul (Meta)</label>
							<input type='text' name='meta_judul' class='form-control' required>
						</div>
					</div>
					<div class='col-md-12'>
						<div class='form-group'>
							<label>Kata Kunci (Meta)</label>
							<textarea name='meta_keyword' class='form-control' maxlenght='160' placeholder='isikan kata kunci dan pisahkan dengan tanda koma (,)' required></textarea>
						</div>
					</div>
					<div class='col-md-3'>
						<div class='form-group'>
							<label>URL (Domain)</label>
							<input type='text' name='url_domain' class='form-control' placeholder='alamat domain saja, tanpa http://' required>
						</div>
					</div>
					<div class='col-md-9'>
						<div class='form-group'>
								<label>Standar KKNI LSP</label>
								<input type='text' name='nama' class='form-control' required>
						</div>
					</div>
					<div class='col-md-12'>
						<div class='form-group'>
							<label>Legalitas</label>
							<input type='text' name='legalitas' placeholder='Contoh: Kepmenakrertrans RI Nomor XX Tahun XXXX' class='form-control'>
						</div>
					</div>
					<div class='col-md-12'>
						<div class='form-group'>
							<input type='submit'  name='tambahkan' class='btn btn-primary' value='Simpan Pengaturan'>
						</div>
					</div>
				</form>
			
			</div>
		  </div>
          
		</div>
	  </div>
	</section>";
    }else{
        echo "<div class='alert alert-warning alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Pengaturan telah dilakukan sebelumnya</h4>
			Pengaturan <b>Indentitas LSP</b> Telah Dilakukan Sebelumnya</div>";

    }
  }
}

// Bagian Input Asesmen Mandiri FORM-APL-02
elseif ($_GET['module']=='form-apl-02'){

$sqllogin="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_GET[ida]'";
$login=$conn->query($sqllogin);
$ketemu=$login->num_rows;
$rowAgen=$login->fetch_assoc();
$sqlgetjadwal="SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
$getjadwal=$conn->query($sqlgetjadwal);
$jd=$getjadwal->fetch_assoc();
$sqlgetskema="SELECT * FROM `skema_kkni` WHERE `id`='$jd[id_skemakkni]'";
$getskema=$conn->query($sqlgetskema);
$sk=$getskema->fetch_assoc();

	if( isset( $_REQUEST['simpandata'] ))
	{				
		$sqlgetunitkompetensi2="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
		$getunitkompetensi2=$conn->query($sqlgetunitkompetensi2);
		while ($unk2=$getunitkompetensi2->fetch_assoc()){
		$sqlcekukom="SELECT * FROM `asesmen_unitkompetensi` WHERE `id_skemakkni`='$sk[id]' AND `id_asesi`='$_GET[ida]' AND `id_unitkompetensi`='$unk2[id]'";
		$cekukom=$conn->query($sqlcekukom);
		$ukom=$cekukom->num_rows;
		  if ($ukom>0){

			$sqlgetelemen2="SELECT * FROM `elemen_kompetensi` WHERE `id_unitkompetensi`='$unk2[id]' ORDER BY `id` ASC";
			$getelemen2=$conn->query($sqlgetelemen2);
			while ($el2=$getelemen2->fetch_assoc()){
				$sqlgetkriteria2="SELECT * FROM `kriteria_unjukkerja` WHERE `id_elemenkompetensi`='$el2[id]' ORDER BY `id` ASC";
				$getkriteria2=$conn->query($sqlgetkriteria2);
				while($kr2=$getkriteria2->fetch_assoc()){
					$id_jawaban='optionsRadios'.$kr2['id'];
					$sqlcekjawaban="SELECT * FROM `asesi_apl02` WHERE `id_asesi`='$_GET[ida]' AND `id_kriteria`='$kr2[id]' AND `id_skemakkni`='$sk[id]'";
					$cekjawaban=$conn->query($sqlcekjawaban);
					$jjw=$cekjawaban->num_rows;
					if ($jjw==0){
						$sqlinputjawaban="INSERT INTO `asesi_apl02`(`id_asesi`, `id_kriteria`, `id_skemakkni`, `id_jadwal`, `jawaban`) VALUES ('$_GET[ida]','$kr2[id]','$sk[id]','$_GET[idj]','$_POST[$id_jawaban]')";
						$conn->query($sqlinputjawaban);
					}else{
						$sqlinputjawaban="UPDATE `asesi_apl02` SET `jawaban`='$_POST[$id_jawaban]' WHERE `id_asesi`='$_GET[ida]' AND `id_kriteria`='$kr2[id]' AND `id_skemakkni`='$sk[id]' AND `id_jadwal`='$_GET[idj]'";
						$conn->query($sqlinputjawaban);

					}
				}
			}
		  }
		}
		echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Jawaban berhasil disimpan</h4>
			Terimakasih, Anda telah melakukan <b>Asesmen Mandiri untuk Skema $sk[judul].</b></div>";

	}
if( isset( $_REQUEST['hapusdocasesi'] )){
	$cekdu="SELECT * FROM `asesi_apl02doc` WHERE `id`='$_POST[iddeldocasesi]'";
	$result = $conn->query($cekdu);
	$getr=$result->fetch_assoc();
	if ($result->num_rows != 0){
		$conn->query("DELETE FROM `asesi_apl02doc` WHERE `id`='$_POST[iddeldocasesi]'");
		unlink("../foto_apl02/".$getr['file']);
        echo "<div class='alert alert-danger alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Hapus Data Sukses</h4>
			Anda Telah Berhasil Menghapus Data <b>Bukti Kompetensi</b></div>";

	}else{
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
	function uploadBukti($file){
		$file_max_weight = 20097152; //Ukuran maksimal yg dibolehkan(2Mb)
		$ok_ext = array('jpg','png','gif','jpeg','JPG','PNG','GIF','JPEG','pdf','PDF'); // ekstensi yang diijinkan
		$destination = "../foto_apl02/"; // tempat buat upload
		$filename = explode(".", $file['name']); 
		$file_name = $file['name'];
		$file_name_no_ext = isset($filename[0]) ? $filename[0] : null;
		$file_extension = $filename[count($filename)-1];
		$file_weight = $file['size'];
		$file_type = $file['type'];

		// Jika tidak ada error
		if( $file['error'] == 0 ){					
			$dateNow = date_create();
			$time_stamp = date_format($dateNow, 'U');
				if( in_array($file_extension, $ok_ext)):
					if( $file_weight <= $file_max_weight ):
						$fileNewName = $time_stamp.md5( $file_name_no_ext[0].microtime() ).".".$file_extension;
						$alamatfile=$fileNewName;
						if( move_uploaded_file($file['tmp_name'], $destination.$fileNewName) ):
							//echo" File uploaded !";
						else:
							//echo "can't upload file.";
						endif;
					else:
						//echo "File too heavy.";
					endif;
				else:
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
		$sqlgetunitkompetensi="SELECT * FROM `unit_kompetensi` WHERE `id_skemakkni`='$sk[id]'";
		$getunitkompetensi=$conn->query($sqlgetunitkompetensi);
		$no=1;
		while ($unk=$getunitkompetensi->fetch_assoc()){
			$sqlcekukom="SELECT * FROM `asesmen_unitkompetensi` WHERE `id_skemakkni`='$sk[id]' AND `id_asesi`='$_GET[ida]' AND `id_unitkompetensi`='$unk[id]'";
			$cekukom=$conn->query($sqlcekukom);
			$ukom=$cekukom->num_rows;
			if ($ukom>0){

			echo"<div class='box box-solid'>";	  
				echo "<div class='box-header with-border'>
					<h3 class='box-title text-green'><b>Unit Kompetensi : $no. $unk[judul]</b></h3>
				</div>
				<div class='box-body'>";
						$no2=1;
						$sqlgetelemen="SELECT * FROM `elemen_kompetensi` WHERE `id_unitkompetensi`='$unk[id]' ORDER BY `id` ASC";
						$getelemen=$conn->query($sqlgetelemen);
						while ($el=$getelemen->fetch_assoc()){
							$no3=1;
							echo "<label>Elemen Kompetensi : $no.$no2. $el[elemen_kompetensi]</label>";
							echo "<div class='form-group'>
								<label>Kriteria Unjuk Kerja :</label>";
								$sqlgetkriteria="SELECT * FROM `kriteria_unjukkerja` WHERE `id_elemenkompetensi`='$el[id]' ORDER BY `id` ASC";
								$getkriteria=$conn->query($sqlgetkriteria);
								while($kr=$getkriteria->fetch_assoc()){
									$sqlcekjawaban0="SELECT * FROM `asesi_apl02` WHERE `id_asesi`='$_GET[ida]' AND `id_kriteria`='$kr[id]' AND `id_skemakkni`='$sk[id]'";
									$cekjawaban0=$conn->query($sqlcekjawaban0);
									$jjw0=$cekjawaban0->fetch_assoc();
									$numjjw0=$cekjawaban0->num_rows;
									if ($numjjw0==0){
										echo "<p>$no.$no2.$no3. $kr[kriteria]</p>
										<label>
											<input type='radio' name='optionsRadios$kr[id]' id='options1$kr[id]' value='1'>
											 Kompeten &nbsp;&nbsp;&nbsp;
										</label>
										<label>
											<input type='radio' name='optionsRadios$kr[id]' id='options2$kr[id]' value='0'>
											 Belum Kompeten
										</label>";
									}else{
										echo "<p>$no.$no2.$no3. $kr[kriteria]</p>";
										if ($jjw0['jawaban']==1){
											echo "<label>
												<input type='radio' name='optionsRadios$kr[id]' id='options1$kr[id]' value='1' checked>
												 Kompeten &nbsp;&nbsp;&nbsp;
											</label>";
										}else{
											echo "<label>
												<input type='radio' name='optionsRadios$kr[id]' id='options1$kr[id]' value='1'>
												 Kompeten &nbsp;&nbsp;&nbsp;
											</label>";

										}
										if ($jjw0['jawaban']==0){
											echo "<label>
												<input type='radio' name='optionsRadios$kr[id]' id='options2$kr[id]' value='0' checked>
												 Belum Kompeten
											</label>";
										}else{
											echo "<label>
												<input type='radio' name='optionsRadios$kr[id]' id='options2$kr[id]' value='0'>
												 Belum Kompeten
											</label>";

										}
									}
									//upload bukti
									$kritvar=$kr['id'];
									$id_kriteriax="id_kriteria".$kritvar;
									$id_elemenkompetensix="id_elemenkompetensi".$kritvar;
									$id_skemakknix="id_skemakkni".$kritvar;
									$unggahbuktix="unggahbukti".$kritvar;
									$filex="file".$kritvar;
									if( isset( $_REQUEST[$unggahbuktix] )){
										$file = $_FILES[$filex];

										
										// Apabila ada file yang diupload
										if (empty($_FILES[$filex]['tmp_name']) || !is_uploaded_file($_FILES[$filex]['tmp_name'])){
											$query = "INSERT INTO `asesi_apl02doc`(`id_asesi`, `id_kriteria`, `id_elemenkompetensi`, `id_skemakkni`, `id_jadwal`) VALUES ('$_GET[ida]','$_POST[$id_kriteriax]','$_POST[$id_elemenkompetensix]','$_POST[$id_skemakknix]','$_GET[idj]')";
										}else{
											$alamatfile = uploadBukti($file);
											$query = "INSERT INTO `asesi_apl02doc`(`id_asesi`, `id_kriteria`, `id_elemenkompetensi`, `id_skemakkni`, `id_jadwal`, `file`) VALUES ('$_GET[ida]','$_POST[$id_kriteriax]','$_POST[$id_elemenkompetensix]','$_POST[$id_skemakknix]','$_GET[idj]','$alamatfile')";
										}
										/*$querycek = "SELECT * FROM `asesi_apl02doc` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_skemakkni`='$_POST[$id_skemakknix]' AND `id_elemenkompetensi`='$_POST[$id_elemenkompetensix]'";
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
									$getfilebukti="SELECT * FROM `asesi_apl02doc` WHERE `id_asesi`='$_GET[ida]' AND `id_kriteria`='$kr[id]' AND `id_elemenkompetensi`='$el[id]' AND `id_skemakkni`='$sk[id]' AND `id_jadwal`='$_GET[idj]'";
									$filebukti=$conn->query($getfilebukti);
												$nodokumen=1;
												while ($fbk=$filebukti->fetch_assoc()){
													//$fbk[file];
													echo "Dokumen $no.$no2.$no3.$nodokumen&nbsp;<a href='#myModal".$fbk['id']."' class='btn btn-success btn-xs' data-toggle='modal' data-id='".$fbk['id']."'><span class='glyphicon glyphicon-zoom-in' aria-hidden='true' title='Lihat/Unduh Dokumen'></span></a>
				<input type='hidden' name='iddeldocasesi' value='$fbk[id]'>
				<input type='submit' name='hapusdocasesi' class='btn btn-danger btn-xs' title='Hapus' value='Hapus'><br>";
													echo "<script>
														$(function(){
																	$(document).on('click','.edit-record',function(e){
																		e.preventDefault();
																		$('#myModal".$fbk['id']."').modal('show');
																	});
															});
													</script>
													<!-- Modal -->
														<div class='modal fade' id='myModal".$fbk['id']."' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
															<div class='modal-dialog'>
																<div class='modal-content'>
																	<div class='modal-header'>
																		<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Tutup</span></button>
																		<h4 class='modal-title' id='myModalLabel'>Dokumen Bukti ".$kr['kriteria']."</h4>
																	</div>
																	<div class='modal-body'><embed src='../foto_apl02/$fbk[file]' width='100%' height='100%'/>
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
													echo "<input type='hidden' name='id_asesi$kr[id]' value='$_GET[ida]'>
													<input type='hidden' name='id_kriteria$kr[id]' value='$kr[id]'>
													<input type='hidden' name='id_skemakkni$kr[id]' value='$sk[id]'>
													<input type='hidden' name='id_elemenkompetensi$kr[id]' value='$el[id]'>
													<input type='hidden' name='id_jadwal$kr[id]' value='$_GET[idj]'>
													<input type='file' name='file$kr[id]' id='file$kr[id]' accept='.pdf, image/*' onchange='readURL(this);'>
													<button type='submit' class='btn btn-success btn-xs' name='unggahbukti$kr[id]'>Unggah/ Tambah Bukti</button>";
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
	      <div class='box-footer'>
                <div align='left' class='col-md-6 col-sm-6 col-xs-6'>
				<a class='btn btn-danger' id=reset-validate-form href='?module=jadwal'>Kembali</a>
		</div>
		<div align='right' class='col-md-6 col-sm-6 col-xs-6'>
				<!--<a href='admin/form-apl-02.php?ida=$_GET[ida]&idj=$_GET[idj]' class='btn btn-primary'>Unduh Form Jawaban</a>-->
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
elseif ($_GET['module']=='portfolio'){
$sqlasesi="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_GET[ida]'";
$getasesi=$conn->query($sqlasesi);
$as=$getasesi->fetch_assoc();

$max_upload = (int)(ini_get('upload_max_filesize'));
$max_post = (int)(ini_get('post_max_size'));
$memory_limit = (int)(ini_get('memory_limit'));
$upload_mb = min($max_upload, $max_post, $memory_limit);

function uploadDoc($file){
		//$file_max_weight = 20000000; //Ukuran maksimal yg dibolehkan( 20 Mb)
		$ok_ext = array('jpg','png','gif','jpeg','JPG','PNG','GIF','JPEG','pdf','PDF'); // ekstensi yang diijinkan
		$destination = "../foto_portfolio/"; // tempat buat upload
		$filename = explode(".", $file['name']); 
		$file_name = $file['name'];
		$file_name_no_ext = isset($filename[0]) ? $filename[0] : null;
		$file_extension = $filename[count($filename)-1];
		$file_weight = $file['size'];
		$file_type = $file['type'];

		// Jika tidak ada error
		if( $file['error'] == 0 ){					
			$dateNow = date_create();
			$time_stamp = date_format($dateNow, 'U');
				if( in_array($file_extension, $ok_ext)):
					//if( $file_weight <= $file_max_weight ):
						$fileNewName = $time_stamp.md5( $file_name_no_ext[0].microtime() ).".".$file_extension;
						$alamatfile=$fileNewName;
						if( move_uploaded_file($file['tmp_name'], $destination.$fileNewName) ):
							//echo" File uploaded !";
						else:
							//echo "can't upload file.";
						endif;
					//else:
						//echo "File too heavy.";
					//endif;
				else:
					//echo "File type is not supported.";
				endif;
				}	
		return $alamatfile;
		}

if( isset( $_REQUEST['tambahdocasesi'] )){
	$file = $_FILES['file'];

	
	// Apabila ada file yang diupload
	if (empty($_FILES['file']['tmp_name']) || !is_uploaded_file($_FILES['file']['tmp_name'])){
		$query = "INSERT INTO `asesi_portfolio`(`id_asesi`, `id_jadwal`, `id_skemakkni`, `jenis_portfolio`, `peran_portfolio`, `nama_doc`, `tahun_doc`, `nomor_doc`, `tgl_doc`) VALUES ('$_GET[ida]','$_GET[idj]','$_POST[id_skemakkni]','$_POST[jenis_portfolio]','$_POST[peran_portfolio]','$_POST[nama_dokumen]','$_POST[tahun_dokumen]','$_POST[nomor_dokumen]','$_POST[tgl_dokumen]')";
	}else{
		$alamatfile = uploadDoc($file);
		$query = "INSERT INTO `asesi_portfolio`(`id_asesi`, `id_jadwal`, `id_skemakkni`, `jenis_portfolio`, `peran_portfolio`,  `nama_doc`, `tahun_doc`, `nomor_doc`, `tgl_doc`, `file`) VALUES ('$_GET[ida]','$_GET[idj]','$_POST[id_skemakkni]','$_POST[jenis_portfolio]','$_POST[peran_portfolio]','$_POST[nama_dokumen]','$_POST[tahun_dokumen]','$_POST[nomor_dokumen]','$_POST[tgl_dokumen]','$alamatfile')";
		$query2 = "INSERT INTO `asesi_portfoliolib`(`id_asesi`, `id_jadwal`, `id_skemakkni`, `jenis_portfolio`, `peran_portfolio`,  `nama_doc`, `tahun_doc`, `nomor_doc`, `tgl_doc`, `file`) VALUES ('$_GET[ida]','$_GET[idj]','$_POST[id_skemakkni]','$_POST[jenis_portfolio]','$_POST[peran_portfolio]','$_POST[nama_dokumen]','$_POST[tahun_dokumen]','$_POST[nomor_dokumen]','$_POST[tgl_dokumen]','$alamatfile')";
	}
	$querycek = "SELECT * FROM `asesi_portfolio` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_skemakkni`='$_POST[id_skemakkni]' AND `nama_doc`='$_POST[nama_dokumen]' AND `tahun_doc`='$_POST[tahun_dokumen]' AND `nomor_doc`='$_POST[nomor_dokumen]' AND `tgl_doc`='$_POST[tgl_dokumen]'";
	$resultc=$conn->query($querycek);
	$row_cnt = $resultc->num_rows;
	if ($row_cnt==0){
		$conn->query($query);
		$conn->query($query2);
  		//header('location:../../media.php?module=syarat&id=$_GET[id]');
		echo "<div class='alert alert-success alert-dismissible'>
		<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
		<h4><i class='icon fa fa-check'></i> Tambah Data Sukses</h4>
		Anda Telah Berhasil Mengunggah Data <b>Portfolio</b></div>";
	}else{
		echo "<script>alert('Maaf Data Tersebut Sudah Ada'); window.location = '../../media.php?module=portfolio&ida=$_GET[ida]&idj=$_GET[idj]'</script>";
	}
}

if( isset( $_REQUEST['addfromlib'] )){
	$alamatfile = $_POST['file'];
	$query = "INSERT INTO `asesi_portfolio`(`id_asesi`, `id_jadwal`, `id_skemakkni`, `jenis_portfolio`, `peran_portfolio`,  `nama_doc`, `tahun_doc`, `nomor_doc`, `tgl_doc`, `file`) VALUES ('$_GET[ida]','$_GET[idj]','$_POST[id_skemakkni]','$_POST[jenis_portfolio]','$_POST[peran_portfolio]','$_POST[nama_dokumen]','$_POST[tahun_dokumen]','$_POST[nomor_dokumen]','$_POST[tgl_dokumen]','$alamatfile')";
	$querycek = "SELECT * FROM `asesi_portfolio` WHERE `id_asesi`='$_GET[ida]' AND `id_jadwal`='$_GET[idj]' AND `id_skemakkni`='$_POST[id_skemakkni]' AND `nama_doc`='$_POST[nama_dokumen]' AND `tahun_doc`='$_POST[tahun_dokumen]' AND `nomor_doc`='$_POST[nomor_dokumen]' AND `tgl_doc`='$_POST[tgl_dokumen]'";
	$resultc=$conn->query($querycek);
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

if( isset( $_REQUEST['hapusdocasesi'] )){
	$cekdu="SELECT * FROM `asesi_portfolio` WHERE `id`='$_POST[iddeldocasesi]'";
	$result = $conn->query($cekdu);
	$getr=$result->fetch_assoc();
	if ($result->num_rows != 0){
		$conn->query("DELETE FROM `asesi_portfolio` WHERE `id`='$_POST[iddeldocasesi]'");
		unlink("../foto_portfolio/".$getr['file']);
        echo "<div class='alert alert-danger alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Hapus Data Sukses</h4>
			Anda Telah Berhasil Menghapus Data <b>Portfolio</b></div>";

	}else{
		echo "<script>alert('Maaf Dokumen Portfolio tersebut Tidak Ditemukan');</script>";
	}
}
if( isset( $_REQUEST['hapuslibasesi'] )){
	$cekdu="SELECT * FROM `asesi_portfoliolib` WHERE `id`='$_POST[iddellibasesi]' OR `file`='$_POST[file]'";
	$result = $conn->query($cekdu);
	$getr=$result->fetch_assoc();
	if ($result->num_rows != 0){
		$conn->query("DELETE FROM `asesi_portfoliolib` WHERE `id`='$_POST[iddellibasesi]' OR `file`='$_POST[file]'");
		$conn->query("DELETE FROM `asesi_portfolio` WHERE `id`='$_POST[iddellibasesi]' OR `file`='$_POST[file]'");
		unlink("../foto_portfolio/".$getr['file']);
        echo "<div class='alert alert-danger alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Hapus Data Sukses</h4>
			Anda Telah Berhasil Menghapus Data <b>Portfolio</b></div>";

	}else{
		echo "<script>alert('Maaf Persyaratan Skema dengan tersebut Tidak Ditemukan');</script>";
	}
}

	$sqlgetjadwal="SELECT * FROM `jadwal_asesmen` WHERE `id`='$_GET[idj]'";
	$getjadwal=$conn->query($sqlgetjadwal);
	$jdw=$getjadwal->fetch_assoc();

	
	$sqlgetskema="SELECT * FROM `skema_kkni` WHERE `id`='$jdw[id_skemakkni]'";
	$getskema=$conn->query($sqlgetskema);
	$gs=$getskema->fetch_assoc();
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
			
			echo"<p>Untuk mengikuti uji kompetensi pada skema ini, silahkan lengkapi persyaratan dan dokumen portfolio berikut:</p>";
			
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
							<option value='Pelatihan'>Diklat/ Bimtek/ Seminar/ Workshop/ Lokakarya/ Kursus</option>";
						echo"</select>";
					echo"</div>
				  </div>
				  <div class='col-md-3'>
					<div class='form-group'>
						<label>Peran dalam Pengalaman</label>
						<select name='peran_portfolio' class='form-control' id='peran_portfolio'>
							<option value=''></option>";
						echo"</select>";
					echo"</div>
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
						$tahunskr=date("Y");
						$tahunnya=intval(trim(substr($as['tgl_lahir'],0,4)))+5;
						while ($tahunnya<=$tahunskr){
							echo "<option value='$tahunnya'>$tahunnya</option>";
							$tahunnya=$tahunnya+1;
						}
						echo"</select>
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
					<a href='#myModalLib".$as['no_pendaftaran']."' class='btn btn-primary' data-toggle='modal' data-id='".$as['id']."'>Tambahkan dokumen dari <em>Library</em> (pustaka anda)</a>
					</div>
				  </div>


			<script>
				$(function(){
							$(document).on('click','.edit-record',function(e){
								e.preventDefault();
								$('#myModal".$as['no_pendaftaran']."').modal('show');
							});
					});
			</script>
			<!-- Modal -->
				<div class='modal fade' id='myModalLib".$as['no_pendaftaran']."' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
					<div class='modal-dialog'>
						<div class='modal-content'>
							<div class='modal-header'>
								<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Tutup</span></button>
								<h4 class='modal-title' id='myModalLabel'><em>Library</em> (Pustaka) Dokumen ".$as['no_pendaftaran']."</h4>
							</div>
							<div class='modal-body'>
								<div style='overflow-x:auto;'>
								<table id='example1' class='table table-bordered table-striped'>
									<thead><tr><th>Dokumen</th><th>Aksi</th></tr></thead>
									<tbody>";
										$sqlasesidocx="SELECT DISTINCT `file` FROM `asesi_portfoliolib` WHERE `id_asesi`='$as[no_pendaftaran]'";
										$asesidocx=$conn->query($sqlasesidocx);
										while ($pmx0=$asesidocx->fetch_assoc()){
											$sqlasesidocxy="SELECT * FROM `asesi_portfoliolib` WHERE `file`='$pmx0[file]' ORDER BY `id` DESC";
											$asesidocxy=$conn->query($sqlasesidocxy);
											$pmx=$asesidocxy->fetch_assoc();
											echo "<tr><td width='50%'>Nama Dokumen : $pmx[nama_doc]<br>No. Dokumen : <b><a href='#myModalM".$pmx['id']."' data-toggle='modal' data-id='".$pmx['id']."'>$pmx[nomor_doc]</a></b><br>Tanggal Dok.: <b>".tgl_indo($pmx['tgl_doc'])."</b></td>
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
											<input type='submit' name='hapuslibasesi' class='btn btn-danger btn-xs btn-block' title='Hapus' value='Hapus'></form></div></td></tr>";
											echo "<script>
				$(function(){
							$(document).on('click','.edit-record',function(e){
								e.preventDefault();
								$('#myModalM".$pmx['id']."').modal('show');
							});
					});
			</script>
			<!-- ModalM -->
				<div class='modal fade' id='myModalM".$pmx['id']."' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
					<div class='modal-dialog'>
						<div class='modal-content'>
							<div class='modal-header'>
								<h4 class='modal-title' id='myModalLabel'>Dokumen Porfolio ".$pmx['nama_doc']."</h4>
							</div>
							<div class='modal-body'><embed src='../foto_portfolio/$pmx[file]' width='100%' height='100%'/>
							</div>
							<div class='modal-footer'>
								<em><font color='red'>klik di area gelap untuk menutup</font></em>
							</div>
						</div>
						</div>
				</div>
			<!-- ModalM End-->";
										}
									echo "</tbody>
								</table>
								</div><font color='red'><b>PERINGATAN :</b><br>Bila Anda menghapus data pada <em>library</em> akan menghapus data portfolio seluruh asesmen anda yang bersangkutan</font>
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
			<div class='row'>
		    <div class='box-body'>

			<h3>Data Dokumen Portfolio Persyaratan</h3>
			<div style='overflow-x:auto;'>
			<table id='table-example' class='table table-bordered table-striped'>
		
			<thead><tr><th>No.</th><th>Portfolio<th>File Pendukung</th><th>Status</th></tr></thead>
			<tbody>";

			$no=1;
			$sqlasesidoc="SELECT * FROM `asesi_portfolio` WHERE `id_asesi`='$as[no_pendaftaran]' AND `id_jadwal`='$_GET[idj]' ORDER BY `id` DESC";
			$asesidoc=$conn->query($sqlasesidoc);
			$jumpm=$asesidoc->num_rows;
			while ($pm=$asesidoc->fetch_assoc()){
				switch ($pm['status']){
				default:
					$statusnya="<font color='blue'><b>Menunggu<br />Persetujuan</b></font>";
				break;
				case "A":
					$statusnya="<font color='green'><b>Disetujui</b></font>";
				break;
				case "R":
					$statusnya="<font color='red'><b>Ditolak</b></font>";
				break;
				}
			echo "<tr class=gradeX><td>$no</td><td><b>$pm[nama_doc]</b><br>No. Dokumen : <b><a href='#myModal".$pm['id']."' data-toggle='modal' data-id='".$pm['id']."'>$pm[nomor_doc]</a></b><br>Tanggal Dok.: <b>".tgl_indo($pm['tgl_doc'])."</b></td><td>"; 
			if (!empty($pm['file'])){
				echo"<a class='btn btn-success btn-xs'><span class='glyphicon glyphicon-ok' aria-hidden='true' title='Dokumen Berhasil Diunggah'></span></a>";
				echo"&nbsp;<a href='#myModal".$pm['id']."' class='btn btn-primary btn-xs' data-toggle='modal' data-id='".$pm['id']."'><span class='glyphicon glyphicon-zoom-in' aria-hidden='true' title='Lihat/Unduh Dokumen'></span></a>";
			}else{
				echo "<span class='text-red'>Tidak ada dokumen</span>";
			}

			echo"</td><td>$statusnya";
			if ($pm['status']=='P'){
				echo "<br /><form role='form' method='POST' enctype='multipart/form-data'>
				<input type='hidden' name='iddeldocasesi' value='$pm[id]'>
				<input type='submit' name='hapusdocasesi' class='btn btn-danger btn-xs' title='Hapus' value='Hapus'></form>";
			}
			echo "</td></tr>";
			$no++;
			echo "<script>
				$(function(){
							$(document).on('click','.edit-record',function(e){
								e.preventDefault();
								$('#myModal".$pm['id']."').modal('show');
							});
					});
			</script>
			<!-- Modal -->
				<div class='modal fade' id='myModal".$pm['id']."' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
					<div class='modal-dialog'>
						<div class='modal-content'>
							<div class='modal-header'>
								<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Tutup</span></button>
								<h4 class='modal-title' id='myModalLabel'>Dokumen Porfolio ".$pm['nama_doc']."</h4>
							</div>
							<div class='modal-body'><embed src='../foto_portfolio/$pm[file]' width='100%' height='100%'/>
							</div>
							<div class='modal-footer'>
								<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>
							</div>
						</div>
						</div>
				</div>";

			}
			echo "</tbody></table></div>";
			if ($jumpm==0){
				echo "<br><p><font color='red'>Proses asesmen dapat dilakukan bila Anda telah mengunggah dokumen persyaratan portfolio.</font></p>";
			}
			echo "<form role='form' method='POST' enctype='multipart/form-data'>
			<div align='left' class='col-md-6 col-sm-6 col-xs-6'>
				<a class='btn btn-default' id=reset-validate-form href='?module=jadwal'>Kembali</a>
			</div>
			<div align='right' class='col-md-6 col-sm-6 col-xs-6'>";
			echo"</div>
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

// Bagian Unggah Dokumen Asesi
elseif ($_GET['module']=='unggahfile'){
	$sqlasesi="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_GET[ida]'";
	$getasesi=$conn->query($sqlasesi);
	$as=$getasesi->fetch_assoc();
	$sqlasesidoc="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$as[no_pendaftaran]'";
	$asesidoc=$conn->query($sqlasesidoc);
	$pm=$asesidoc->fetch_assoc();
	$sqlpendidikan="SELECT * FROM `pendidikan` WHERE `id`='$pm[pendidikan]'";
	$pendidikan=$conn->query($sqlpendidikan);
	$pdd=$pendidikan->fetch_assoc();

	$max_upload = (int)(ini_get('upload_max_filesize'));
	$max_post = (int)(ini_get('post_max_size'));
	$memory_limit = (int)(ini_get('memory_limit'));
	$upload_mb = min($max_upload, $max_post, $memory_limit);
	function uploadDoc2($file){
		//direktori file
		$vdir_upload = "../foto_asesi/";
		$vfile_upload = $vdir_upload . $fupload_name;
		$tipe_file   = $_FILES['file']['type'];

		//Simpan gambar dalam ukuran sebenarnya
		move_uploaded_file($_FILES["file"]["tmp_name"], $vfile_upload);
	}

	function uploadDoc($file){
			//$file_max_weight = 20000000; //Ukuran maksimal yg dibolehkan( 20 Mb)
			$ok_ext = array('jpg','png','gif','jpeg','JPG','PNG','GIF','JPEG','pdf','PDF'); // ekstensi yang diijinkan
			$destination = "../foto_asesi/"; // tempat buat upload
			$filename = explode(".", $file['name']); 
			$file_name = $file['name'];
			$file_name_no_ext = isset($filename[0]) ? $filename[0] : null;
			$file_extension = $filename[count($filename)-1];
			$file_weight = $file['size'];
			$file_type = $file['type'];

			// Jika tidak ada error
			if( $file['error'] == 0 ){					
				$dateNow = date_create();
				$time_stamp = date_format($dateNow, 'U');
					if( in_array($file_extension, $ok_ext)):
						//if( $file_weight <= $file_max_weight ):
							$fileNewName = $time_stamp.md5( $file_name_no_ext[0].microtime() ).".".$file_extension;
							$alamatfile=$fileNewName;
							if( move_uploaded_file($file['tmp_name'], $destination.$fileNewName) ):
								//echo" File uploaded !";
							else:
								//echo "can't upload file.";
							endif;
						//else:
						//	echo "File terlalu besar.";
						//endif;
					else:
						echo "Tipe file ini tidak diperbolehkan.";
					endif;
					}	
			return $alamatfile;
			}

	if( isset( $_REQUEST['tambahdocasesi'] )){
		$file = $_FILES['file'];
		switch ($_POST['kategori']){
			case "foto":
				$ketdata="Pas Foto";
				// Apabila ada file yang diupload
				if (!empty($_FILES['file']['tmp_name']) || !is_uploaded_file($_FILES['file']['tmp_name'])){
					$alamatfile = uploadDoc($file);
					$query = "UPDATE `asesi` SET `foto`='$alamatfile' WHERE `no_pendaftaran`='$as[no_pendaftaran]'";
				}
				$conn->query($query);
				echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Tambah Data Sukses</h4>
				Anda Telah Berhasil Mengunggah Dokumen <b>$ketdata</b></div>";
				die("<script>location.href = '?module=unggahfile&ida=$_GET[ida]'</script>");

			break;
			case "ktp":
				$ketdata="KTP No. $pm[no_ktp]";
				// Apabila ada file yang diupload
				if (!empty($_FILES['file']['tmp_name']) || !is_uploaded_file($_FILES['file']['tmp_name'])){
					$alamatfile = uploadDoc($file);
					$query = "UPDATE `asesi` SET `ktp`='$alamatfile' WHERE `no_pendaftaran`='$as[no_pendaftaran]'";
				}
				$conn->query($query);
				echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Tambah Data Sukses</h4>
				Anda Telah Berhasil Mengunggah Dokumen <b>$ketdata</b></div>";
				die("<script>location.href = '?module=unggahfile&ida=$_GET[ida]'</script>");

			break;
			case "ijazah":
				$ketdata="Ijazah ($pdd[jenjang_pendidikan] $pm[prodi])";
				// Apabila ada file yang diupload
				if (!empty($_FILES['file']['tmp_name']) || !is_uploaded_file($_FILES['file']['tmp_name'])){
					$alamatfile = uploadDoc($file);
					$query = "UPDATE `asesi` SET `ijazah`='$alamatfile' WHERE `no_pendaftaran`='$as[no_pendaftaran]'";
				}
				$conn->query($query);
				echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Tambah Data Sukses</h4>
				Anda Telah Berhasil Mengunggah Dokumen <b>$ketdata</b></div>";
				die("<script>location.href = '?module=unggahfile&ida=$_GET[ida]'</script>");

			break;
			case "transkrip":
				$ketdata="Transkrip Nilai/Daftar Nilai";
				// Apabila ada file yang diupload
				if (!empty($_FILES['file']['tmp_name']) || !is_uploaded_file($_FILES['file']['tmp_name'])){
					$alamatfile = uploadDoc($file);
					$query = "UPDATE `asesi` SET `transkrip`='$alamatfile' WHERE `no_pendaftaran`='$as[no_pendaftaran]'";
				}
				$conn->query($query);
				echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Tambah Data Sukses</h4>
				Anda Telah Berhasil Mengunggah Dokumen <b>$ketdata</b></div>";
				die("<script>location.href = '?module=unggahfile&ida=$_GET[ida]'</script>");

			break;
			case "kk":
				$ketdata="Kartu Keluarga";
				// Apabila ada file yang diupload
				if (!empty($_FILES['file']['tmp_name']) || !is_uploaded_file($_FILES['file']['tmp_name'])){
					$alamatfile = uploadDoc($file);
					$query = "UPDATE `asesi` SET `kk`='$alamatfile' WHERE `no_pendaftaran`='$as[no_pendaftaran]'";
				}
				$conn->query($query);
				echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Tambah Data Sukses</h4>
				Anda Telah Berhasil Mengunggah Dokumen <b>$ketdata</b></div>";
				die("<script>location.href = '?module=unggahfile&ida=$_GET[ida]'</script>");

			break;
			case "suket":
				$ketdata="Surat Keterangan Kerja/Magang dari $pm[nama_kantor]";
				// Apabila ada file yang diupload
				if (!empty($_FILES['file']['tmp_name']) || !is_uploaded_file($_FILES['file']['tmp_name'])){
					$alamatfile = uploadDoc($file);
					$query = "UPDATE `asesi` SET `suket`='$alamatfile' WHERE `no_pendaftaran`='$as[no_pendaftaran]'";
				}
				$conn->query($query);
				echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Tambah Data Sukses</h4>
				Anda Telah Berhasil Mengunggah Dokumen <b>$ketdata</b></div>";
				die("<script>location.href = '?module=unggahfile&ida=$_GET[ida]'</script>");

			break;
		}
	}

	if( isset( $_REQUEST['hapusdocasesi'] )){

		$cekdu="SELECT * FROM `asesi` WHERE `".$_POST['iddeldocasesi']."`='$_POST[iddeldocasesifile]' AND `no_pendaftaran`='$as[no_pendaftaran]'";
		$result = $conn->query($cekdu);
		if ($result->num_rows != 0){
			unlink("../foto_asesi/".$_POST['iddeldocasesifile']);
			$conn->query("UPDATE `asesi` SET `".$_POST['iddeldocasesi']."`='' WHERE `no_pendaftaran`='$as[no_pendaftaran]'");
			echo "<div class='alert alert-danger alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Hapus Data Sukses</h4>
				Anda Telah Berhasil Menghapus Data <b>Persyaratan</b></div>";
				die("<script>location.href = '?module=unggahfile&ida=$_GET[ida]'</script>");

		}else{
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
        <li><a href='media.php?module=asesibaru'><i class='fa fa-users'></i> Data Calon Asesi Sertifikasi Profesi</a></li>
        <li class='active'>Unggah Data Pokok Asesi</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class='content'>
      <div class='row'>
        <div class='col-xs-12'>
		  <div class='box-body'>
			<div class='box'>";
			
			echo "<div class='box-body'>
				<h3>Input Dokumen Pokok Asesi</h3>
				<span>Masukkan data pokok Anda, kemudian klik <b><a class='btn btn-primary btn-xs'>Tambahkan</a></b></span> 
				
				<form role='form' method='POST' enctype='multipart/form-data'>
				  <div class='col-md-6'>
					<div class='form-group'>
						<label>Jenis Dokumen Persyaratan</label>
						<select name='kategori' class='form-control' id='kategori'>
							<option value='foto'>Pas Foto</option>
							<option value='ktp'>KTP No. $pm[no_ktp]</option>
							<option value='ijazah'>Ijazah ($pdd[jenjang_pendidikan] $pm[prodi])</option>
							<option value='transkrip'>Transkrip Nilai/Daftar Nilai</option>
							<option value='kk'>Kartu Keluarga</option>
							<option value='suket'>Surat Keterangan Kerja/Magang dari $pm[nama_kantor]</option>
						</select>";
					echo"</div>
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
				</form>
				</div><!--body-->
				<div class='box-body'>
					<h3>Data Dokumen Persyaratan</h3>
					<div style='overflow-x:auto;'>
					<table id='table-example' class='table table-bordered table-striped'>
				
					<thead><tr><th>No.</th><th>Persyaratan<th>File Pendukung</th><th>Status</th></tr></thead>
					<tbody>";


					// -------------------------------------data dokumen foto -----------------------------------
					echo "<tr class=gradeX><td>1</td><td><b>Dokumen Foto</b><br>Dokumen : <b><a href='#myModalfoto' data-toggle='modal' data-id='foto'>$pm[foto]</a></b></td><td>"; 
					if (!empty($pm['foto'])){
						echo"<a class='btn btn-success btn-xs'><span class='glyphicon glyphicon-ok' aria-hidden='true' title='Dokumen Berhasil Diunggah'></span></a>";
						echo"&nbsp;<a href='#myModalfoto' class='btn btn-primary btn-xs' data-toggle='modal' data-id='foto'><span class='glyphicon glyphicon-zoom-in' aria-hidden='true' title='Lihat/Unduh Dokumen'></span></a>";
					}else{
						echo "<span class='text-red'>Tidak ada dokumen</span>";
					}

					echo"</td><td>";
					if (!empty($pm['foto'])){
						echo "<br /><form role='form' method='POST' enctype='multipart/form-data'>
						<input type='hidden' name='iddeldocasesi' value='foto'>
						<input type='hidden' name='iddeldocasesifile' value='$pm[foto]'>
						<input type='submit' name='hapusdocasesi' class='btn btn-danger btn-xs' title='Hapus' value='Hapus'></form>";
					
						echo "</td></tr>";
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
										<h4 class='modal-title' id='myModalLabel'>Dokumen Foto</h4>
									</div>
									<div class='modal-body'><embed src='../foto_asesi/$pm[foto]' width='100%' height='100%'/>
									</div>
									<div class='modal-footer'>
										<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>
									</div>
								</div>
								</div>
						</div>";

					}
					// -------------------------------------data dokumen KTP -----------------------------------
					echo "<tr class=gradeX><td>2</td><td><b>Dokumen KTP</b><br>Dokumen : <b><a href='#myModalktp' data-toggle='modal' data-id='ktp'>$pm[ktp]</a></b></td><td>"; 
					if (!empty($pm['ktp'])){
						echo"<a class='btn btn-success btn-xs'><span class='glyphicon glyphicon-ok' aria-hidden='true' title='Dokumen Berhasil Diunggah'></span></a>";
						echo"&nbsp;<a href='#myModalktp' class='btn btn-primary btn-xs' data-toggle='modal' data-id='ktp'><span class='glyphicon glyphicon-zoom-in' aria-hidden='true' title='Lihat/Unduh Dokumen'></span></a>";
					}else{
						echo "<span class='text-red'>Tidak ada dokumen</span>";
					}

					echo"</td><td>";
					if (!empty($pm['ktp'])){
						echo "<br /><form role='form' method='POST' enctype='multipart/form-data'>
						<input type='hidden' name='iddeldocasesi' value='ktp'>
						<input type='hidden' name='iddeldocasesifile' value='$pm[ktp]'>
						<input type='submit' name='hapusdocasesi' class='btn btn-danger btn-xs' title='Hapus' value='Hapus'></form>";
					
						echo "</td></tr>";
						echo "<script>
						$(function(){
									$(document).on('click','.edit-record',function(e){
										e.preventDefault();
										$('#myModalktp').modal('show');
									});
							});
						</script>
						<!-- Modal -->
						<div class='modal fade' id='myModalktp' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
							<div class='modal-dialog'>
								<div class='modal-content'>
									<div class='modal-header'>
										<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Tutup</span></button>
										<h4 class='modal-title' id='myModalLabel'>Dokumen KTP</h4>
									</div>
									<div class='modal-body'><embed src='../foto_asesi/$pm[ktp]' width='100%' height='100%'/>
									</div>
									<div class='modal-footer'>
										<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>
									</div>
								</div>
								</div>
						</div>";

					}
					// -------------------------------------data dokumen KK -----------------------------------
					echo "<tr class=gradeX><td>3</td><td><b>Dokumen Kartu Keluarga</b><br>Dokumen : <b><a href='#myModalkk' data-toggle='modal' data-id='kk'>$pm[kk]</a></b></td><td>"; 
					if (!empty($pm['kk'])){
						echo"<a class='btn btn-success btn-xs'><span class='glyphicon glyphicon-ok' aria-hidden='true' title='Dokumen Berhasil Diunggah'></span></a>";
						echo"&nbsp;<a href='#myModalkk' class='btn btn-primary btn-xs' data-toggle='modal' data-id='kk'><span class='glyphicon glyphicon-zoom-in' aria-hidden='true' title='Lihat/Unduh Dokumen'></span></a>";
					}else{
						echo "<span class='text-red'>Tidak ada dokumen</span>";
					}

					echo"</td><td>";
					if (!empty($pm['kk'])){
						echo "<br /><form role='form' method='POST' enctype='multipart/form-data'>
						<input type='hidden' name='iddeldocasesi' value='kk'>
						<input type='hidden' name='iddeldocasesifile' value='$pm[kk]'>
						<input type='submit' name='hapusdocasesi' class='btn btn-danger btn-xs' title='Hapus' value='Hapus'></form>";
					
						echo "</td></tr>";
						echo "<script>
						$(function(){
									$(document).on('click','.edit-record',function(e){
										e.preventDefault();
										$('#myModalkk').modal('show');
									});
							});
						</script>
						<!-- Modal -->
						<div class='modal fade' id='myModalkk' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
							<div class='modal-dialog'>
								<div class='modal-content'>
									<div class='modal-header'>
										<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Tutup</span></button>
										<h4 class='modal-title' id='myModalLabel'>Dokumen Kartu Keluarga</h4>
									</div>
									<div class='modal-body'><embed src='../foto_asesi/$pm[kk]' width='100%' height='100%'/>
									</div>
									<div class='modal-footer'>
										<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>
									</div>
								</div>
								</div>
						</div>";

					}
					// -------------------------------------data dokumen Ijazah -----------------------------------
					echo "<tr class=gradeX><td>4</td><td><b>Dokumen Ijazah Terakhir</b><br>Dokumen : <b><a href='#myModalijazah' data-toggle='modal' data-id='ijazah'>$pm[ijazah]</a></b></td><td>"; 
					if (!empty($pm['ijazah'])){
						echo"<a class='btn btn-success btn-xs'><span class='glyphicon glyphicon-ok' aria-hidden='true' title='Dokumen Berhasil Diunggah'></span></a>";
						echo"&nbsp;<a href='#myModalijazah' class='btn btn-primary btn-xs' data-toggle='modal' data-id='ijazah'><span class='glyphicon glyphicon-zoom-in' aria-hidden='true' title='Lihat/Unduh Dokumen'></span></a>";
					}else{
						echo "<span class='text-red'>Tidak ada dokumen</span>";
					}

					echo"</td><td>";
					if (!empty($pm['ijazah'])){
						echo "<br /><form role='form' method='POST' enctype='multipart/form-data'>
						<input type='hidden' name='iddeldocasesi' value='ijazah'>
						<input type='hidden' name='iddeldocasesifile' value='$pm[ijazah]'>
						<input type='submit' name='hapusdocasesi' class='btn btn-danger btn-xs' title='Hapus' value='Hapus'></form>";
					
						echo "</td></tr>";
						echo "<script>
						$(function(){
									$(document).on('click','.edit-record',function(e){
										e.preventDefault();
										$('#myModalijazah').modal('show');
									});
							});
						</script>
						<!-- Modal -->
						<div class='modal fade' id='myModalijazah' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
							<div class='modal-dialog'>
								<div class='modal-content'>
									<div class='modal-header'>
										<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Tutup</span></button>
										<h4 class='modal-title' id='myModalLabel'>Dokumen Ijazah Terakhir</h4>
									</div>
									<div class='modal-body'><embed src='../foto_asesi/$pm[ijazah]' width='100%' height='100%'/>
									</div>
									<div class='modal-footer'>
										<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>
									</div>
								</div>
								</div>
						</div>";

					}
					// -------------------------------------data dokumen Transkrip -----------------------------------
					echo "<tr class=gradeX><td>5</td><td><b>Dokumen Transkrip Nilai Terakhir</b><br>Dokumen : <b><a href='#myModaltranskrip' data-toggle='modal' data-id='transkrip'>$pm[transkrip]</a></b></td><td>"; 
					if (!empty($pm['transkrip'])){
						echo"<a class='btn btn-success btn-xs'><span class='glyphicon glyphicon-ok' aria-hidden='true' title='Dokumen Berhasil Diunggah'></span></a>";
						echo"&nbsp;<a href='#myModaltranskrip' class='btn btn-primary btn-xs' data-toggle='modal' data-id='transkrip'><span class='glyphicon glyphicon-zoom-in' aria-hidden='true' title='Lihat/Unduh Dokumen'></span></a>";
					}else{
						echo "<span class='text-red'>Tidak ada dokumen</span>";
					}

					echo"</td><td>";
					if (!empty($pm['transkrip'])){
						echo "<br /><form role='form' method='POST' enctype='multipart/form-data'>
						<input type='hidden' name='iddeldocasesi' value='transkrip'>
						<input type='hidden' name='iddeldocasesifile' value='$pm[transkrip]'>
						<input type='submit' name='hapusdocasesi' class='btn btn-danger btn-xs' title='Hapus' value='Hapus'></form>";
					
						echo "</td></tr>";
						echo "<script>
						$(function(){
									$(document).on('click','.edit-record',function(e){
										e.preventDefault();
										$('#myModaltranskrip').modal('show');
									});
							});
						</script>
						<!-- Modal -->
						<div class='modal fade' id='myModaltranskrip' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
							<div class='modal-dialog'>
								<div class='modal-content'>
									<div class='modal-header'>
										<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Tutup</span></button>
										<h4 class='modal-title' id='myModalLabel'>Dokumen Transkrip Nilai Terakhir</h4>
									</div>
									<div class='modal-body'><embed src='../foto_asesi/$pm[transkrip]' width='100%' height='400px'/>
									</div>
									<div class='modal-footer'>
										<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>
									</div>
								</div>
								</div>
						</div>";

					}
					// -------------------------------------data dokumen Suket Bekerja -----------------------------------
					echo "<tr class=gradeX><td>6</td><td><b>Dokumen Surat Keterangan Bekerja/ Pengalaman Magang</b><br>Dokumen : <b><a href='#myModalsuket' data-toggle='modal' data-id='transkrip'>$pm[suket]</a></b></td><td>"; 
					if (!empty($pm['suket'])){
						echo"<a class='btn btn-success btn-xs'><span class='glyphicon glyphicon-ok' aria-hidden='true' title='Dokumen Berhasil Diunggah'></span></a>";
						echo"&nbsp;<a href='#myModalsuket' class='btn btn-primary btn-xs' data-toggle='modal' data-id='suket'><span class='glyphicon glyphicon-zoom-in' aria-hidden='true' title='Lihat/Unduh Dokumen'></span></a>";
					}else{
						echo "<span class='text-red'>Tidak ada dokumen</span>";
					}

					echo"</td><td>";
					if (!empty($pm['suket'])){
						echo "<br /><form role='form' method='POST' enctype='multipart/form-data'>
						<input type='hidden' name='iddeldocasesi' value='suket'>
						<input type='hidden' name='iddeldocasesifile' value='$pm[suket]'>
						<input type='submit' name='hapusdocasesi' class='btn btn-danger btn-xs' title='Hapus' value='Hapus'></form>";
					
						echo "</td></tr>";
						echo "<script>
						$(function(){
									$(document).on('click','.edit-record',function(e){
										e.preventDefault();
										$('#myModalsuket').modal('show');
									});
							});
						</script>
						<!-- Modal -->
						<div class='modal fade' id='myModalsuket' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
							<div class='modal-dialog'>
								<div class='modal-content'>
									<div class='modal-header'>
										<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Tutup</span></button>
										<h4 class='modal-title' id='myModalLabel'>Dokumen Keterangan Kerja / Pengalaman Magang</h4>
									</div>
									<div class='modal-body'><embed src='../foto_asesi/$pm[suket]' width='100%' height='100%'/>
									</div>
									<div class='modal-footer'>
										<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>
									</div>
								</div>
								</div>
						</div>";

					}

					echo "</tbody></table>
				</div><!--body-->";
			echo "</div><!--box-->
		  </div><!--box body-->
		</div><!--col-->
	  </div><!--row-->
	</section>";


}


// Apabila modul tidak ditemukan =============================================================================================
else{
  echo "<p><b>MODUL BELUM ADA ATAU BELUM LENGKAP</b></p>";
}
?>
<!-- jQuery 2.2.3 -->
<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- ChartJS 1.0.1 -->
<script src="../../plugins/chartjs/Chart2.min.js"></script>
<!-- Page script -->
<script>
// ===================================================================================
// Perhatikan urutan sekuensial harus sesuai dengan bagian kode htmlnya
// dan jumlah chart yang ditampilkan harus sesuai dengan javascriptnya,
// untuk chart dengan data beda getELemenById juga harus beda
// kalau tidak sama chart tidak akan tampil
// ===================================================================================
new Chart(document.getElementById("line-chart"), {
  type: 'line',
  data: {
    labels: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul","Ags", "Sep", "Okt", "Nov", "Des"],
    datasets: [{ 
        data: [<?php echo "$kandidat01"; ?>, <?php echo "$kandidat02"; ?>,<?php echo "$kandidat03"; ?>,<?php echo "$kandidat04"; ?>,<?php echo "$kandidat05"; ?>,<?php echo "$kandidat06"; ?>,<?php echo "$kandidat07"; ?>,<?php echo "$kandidat08"; ?>,<?php echo "$kandidat09"; ?>,<?php echo "$kandidat10"; ?>,<?php echo "$kandidat11"; ?>,<?php echo "$kandidat12"; ?>],
        label: "Pendaftar",
        borderColor: "#3e95cd",
        fill: false
      }, { 
        data: [<?php echo "$kandidat01b"; ?>, <?php echo "$kandidat02b"; ?>,<?php echo "$kandidat03b"; ?>,<?php echo "$kandidat04b"; ?>,<?php echo "$kandidat05b"; ?>,<?php echo "$kandidat06b"; ?>,<?php echo "$kandidat07b"; ?>,<?php echo "$kandidat08b"; ?>,<?php echo "$kandidat09b"; ?>,<?php echo "$kandidat10b"; ?>,<?php echo "$kandidat11b"; ?>,<?php echo "$kandidat12b"; ?>],
        label: "Terverifikasi",
        borderColor: "#8e5ea2",
        fill: false
      }, { 
        data: [<?php echo "$terjadwal01b"; ?>, <?php echo "$terjadwal02b"; ?>,<?php echo "$terjadwal03b"; ?>,<?php echo "$terjadwal04b"; ?>,<?php echo "$terjadwal05b"; ?>,<?php echo "$terjadwal06b"; ?>,<?php echo "$terjadwal07b"; ?>,<?php echo "$terjadwal08b"; ?>,<?php echo "$terjadwal09b"; ?>,<?php echo "$terjadwal10b"; ?>,<?php echo "$terjadwal11b"; ?>,<?php echo "$terjadwal12b"; ?>],
        label: "Terjadwal",
        borderColor: "#3cba9f",
        fill: false
      }, { 
        data: [0,0,0,0,0,0,0,0,0,0,0,0],
        label: "Tersertifikasi",
        borderColor: "#e8c3b9",
        fill: false
      }
    ]
  },
  options: {
    title: {
      display: true,
      text: 'Data Asesi Tahun <?php echo date("Y");?>'
    }
  }
});

new Chart(document.getElementById("bar-chart"), {
    type: 'bar',
    data: {
      labels: ["16 - 21", "22 - 26", "27 - 32", "33 - 38", "39 - 44", "45 - 50", "51 - 56", "57 - 62", "63 - 68", "69 - 74", "> 74"],
      datasets: [
        {
          backgroundColor: ["#00D4E5", "#00E1AE","#00DD68","#00D925","#1AD500", "#59D200","#95CE00","#CAC500","#C68500", "#C24800","#BF0E00"],
          data: [<?php echo $jumusia1; ?>,<?php echo $jumusia2; ?>,<?php echo $jumusia3; ?>,<?php echo $jumusia4; ?>,<?php echo $jumusia5; ?>,<?php echo $jumusia6; ?>,<?php echo $jumusia7; ?>,<?php echo $jumusia8; ?>,<?php echo $jumusia9; ?>,<?php echo $jumusia10; ?>,<?php echo $jumusia11; ?>],
          label: "Jumlah Asesi"
        }
      ]
    },
    options: {
      legend: { display: false },
      title: {
        display: true,
        text: 'Jumlah Asesi Berdasarkan Kelompok Usia (Tahun)'
      }
    }
});

new Chart(document.getElementById("pie-chart"), {
    type: 'pie',
    data: {
      labels: ["Laki-Laki", "Perempuan", "Tidak Diketahui"],
      datasets: [{
        label: "Jumlah Asesi",
        backgroundColor: ["#0005D9", "#CA00A7","#00DD68"],
        data: [<?php echo $jumlaki; ?>,<?php echo $jumperempuan; ?>,<?php echo $jumnotlp; ?>]
      }]
    },
    options: {
      title: {
        display: true,
        text: 'Jumlah Asesi Berdasarkan Jenis Kelamin'
      }
    }
});


</script>
<!-- CK Editor -->
<script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<script>
  $(function () {
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
    CKEDITOR.replace('editor1');
    //bootstrap WYSIHTML5 - text editor
    $(".textarea").wysihtml5();
  });
</script>