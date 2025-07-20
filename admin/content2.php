<?php
include "../config/koneksi.php";
include "../config/library.php";
include "../config/fungsi_indotgl.php";
include "../config/fungsi_combobox.php";
include "../config/class_paging.php";
include "../config/fungsi_rupiah.php";

$sqlidentitas="SELECT * FROM `identitas`";
$identitas=$conn->query($sqlidentitas);
$iden=$identitas->fetch_assoc();
$urldomain=$iden['url_domain'];

// Bagian Home
if ($_GET['module']=='home'){
	if ($_SESSION['leveluser']=='admin'|| $_SESSION['leveluser']=='user'){
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
	$sqljumasesi="SELECT * FROM `asesi`";
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
        <div class='col-lg-2 col-xs-6'>
          <!-- small box -->
          <div class='small-box bg-aqua'>
            <div class='inner'>
              <h3>$jumtuk</h3>

              <p>T.U.K.</p>
            </div>
            <div class='icon'>
              <i class='fa fa-building'></i>
            </div>
            <a href='?module=tuk' class='small-box-footer'>Lihat data <i class='fa fa-arrow-circle-right'></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class='col-lg-2 col-xs-6'>
          <!-- small box -->
          <div class='small-box bg-blue'>
            <div class='inner'>
              <h3>$jumskema</h3>

              <p>Skema Sertifikasi</p>
            </div>
            <div class='icon'>
              <i class='fa fa-users'></i>
            </div>
            <a href='?module=skema' class='small-box-footer'>Lihat data <i class='fa fa-arrow-circle-right'></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class='col-lg-2 col-xs-6'>
          <!-- small box -->
          <div class='small-box bg-green'>
            <div class='inner'>
              <h3>$jumasesor</h3>

              <p>Asesor</p>
            </div>
            <div class='icon'>
              <i class='fa fa-user'></i>
            </div>
            <a href='?module=asesor' class='small-box-footer'>Lihat data <i class='fa fa-arrow-circle-right'></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class='col-lg-2 col-xs-6'>
          <!-- small box -->
          <div class='small-box bg-yellow'>
            <div class='inner'>
              <h3>$jumasesi</h3>

              <p>Asesi/ Calon Asesi</p>
            </div>
            <div class='icon'>
              <i class='fa fa-user-plus'></i>
            </div>
            <a href='?module=asesi' class='small-box-footer'>Lihat data <i class='fa fa-arrow-circle-right'></i></a>
          </div>
        </div>
        <!-- ./col -->
	<div class='col-lg-2 col-xs-6'>
          <!-- small box -->
          <div class='small-box bg-purple'>
            <div class='inner'>
              <h3>$jumevent</h3>

              <p>Uji Kompetensi</p>
            </div>
            <div class='icon'>
              <i class='fa fa-check-square-o'></i>
            </div>
            <a href='?module=event' class='small-box-footer'>Lihat data <i class='fa fa-arrow-circle-right'></i></a>
          </div>
        </div>
        <!-- ./col -->
	<div class='col-lg-2 col-xs-6'>
          <!-- small box -->
          <div class='small-box bg-maroon'>
            <div class='inner'>
              <h3>$jumjadwal</h3>

              <p>Jadwal Asesmen</p>
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
              <h3 class='box-title'>Pendaftar dan Kandidat Tahun $tahun</h3>

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
              <h3 class='box-title'>Jenis Kelamin Asesi</h3>

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

	  <!-- DONUT CHART -->
          <div class='box box-default'>
            <div class='box-header with-border'>
              <h3 class='box-title'>Jenis Kelamin Asesor</h3>

              <div class='box-tools pull-right'>
                <button type='button' class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i>
                </button>
                <button type='button' class='btn btn-box-tool' data-widget='remove'><i class='fa fa-times'></i></button>
              </div>
            </div>
            <div class='box-body'>
              <div class='chart'>
<canvas id='pie-chart2' width='800' height='450'></canvas>
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
		  	$sqlnumkona="SELECT DISTINCT `id_asesi` FROM `asesi_doc`";
			$numkona0=$conn->query($sqlnumkona);
			$numkona=$numkona0->num_rows;
			$sqlnumkon2a="SELECT * FROM `asesi`";
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
		  	$sqlnumkon="SELECT * FROM `asesi_asesmen` WHERE `tgl_asesmen`!='0000-00-00'";
			$numkon0=$conn->query($sqlnumkon);
			$numkon=$numkon0->num_rows;
			$sqlnumkon2="SELECT * FROM `asesi_asesmen`";
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
			//if($_SESSION['leveluser']=='admin'){
				$sqlgetkompeten2="SELECT * FROM `asesi_asesmen` WHERE `status_asesmen`='K'";
				$getkompeten2=$conn->query($sqlgetkompeten2);
				$numkonb=$getkompeten2->num_rows;

				$sqlgetkompeten="SELECT * FROM `asesi_asesmen`";
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
			//if($_SESSION['leveluser']=='admin'){
				$sqlgetkompeten3="SELECT * FROM `asesi_asesmen` WHERE `status_asesmen`='BK'";
				$getkompeten3=$conn->query($sqlgetkompeten3);
				$numkonc=$getkompeten3->num_rows;

				$sqlgetkompeten4="SELECT * FROM `asesi_asesmen`";
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
              <h3 class='box-title'>Kelompok Usia Asesi</h3>

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

          <!-- BAR CHART -->
          <div class='box box-danger'>
            <div class='box-header with-border'>
              <h3 class='box-title'>Kelompok Usia Asesor</h3>

              <div class='box-tools pull-right'>
                <button type='button' class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i>
                </button>
                <button type='button' class='btn btn-box-tool' data-widget='remove'><i class='fa fa-times'></i></button>
              </div>
            </div>
            <div class='box-body'>
              <div class='chart'>
<canvas id='bar-chart2' width='800' height='450'></canvas>
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


	$sqljumkandidat01="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode01%'";
	$sqljumkandidat02="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode02%'";
	$sqljumkandidat03="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode03%'";
	$sqljumkandidat04="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode04%'";
	$sqljumkandidat05="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode05%'";
	$sqljumkandidat06="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode06%'";
	$sqljumkandidat07="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode07%'";
	$sqljumkandidat08="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode08%'";
	$sqljumkandidat09="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode09%'";
	$sqljumkandidat10="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode10%'";
	$sqljumkandidat11="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode11%'";
	$sqljumkandidat12="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode12%'";

	// data terverifikasi

	$sqljumkandidat01b="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode01%' AND `verifikasi`='V'";
	$sqljumkandidat02b="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode02%' AND `verifikasi`='V'";
	$sqljumkandidat03b="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode03%' AND `verifikasi`='V'";
	$sqljumkandidat04b="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode04%' AND `verifikasi`='V'";
	$sqljumkandidat05b="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode05%' AND `verifikasi`='V'";
	$sqljumkandidat06b="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode06%' AND `verifikasi`='V'";
	$sqljumkandidat07b="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode07%' AND `verifikasi`='V'";
	$sqljumkandidat08b="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode08%' AND `verifikasi`='V'";
	$sqljumkandidat09b="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode09%' AND `verifikasi`='V'";
	$sqljumkandidat10b="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode10%' AND `verifikasi`='V'";
	$sqljumkandidat11b="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode11%' AND `verifikasi`='V'";
	$sqljumkandidat12b="SELECT * FROM `asesi` WHERE `no_pendaftaran` LIKE '$periode12%' AND `verifikasi`='V'";

	// data terjadwal

	$sqljumterjadwal01b="SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`!='' AND `tgl_asesmen` LIKE '$periode01b%'";
	$sqljumterjadwal02b="SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`!='' AND `tgl_asesmen` LIKE '$periode02b%'";
	$sqljumterjadwal03b="SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`!='' AND `tgl_asesmen` LIKE '$periode03b%'";
	$sqljumterjadwal04b="SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`!='' AND `tgl_asesmen` LIKE '$periode04b%'";
	$sqljumterjadwal05b="SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`!='' AND `tgl_asesmen` LIKE '$periode05b%'";
	$sqljumterjadwal06b="SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`!='' AND `tgl_asesmen` LIKE '$periode06b%'";
	$sqljumterjadwal07b="SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`!='' AND `tgl_asesmen` LIKE '$periode07b%'";
	$sqljumterjadwal08b="SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`!='' AND `tgl_asesmen` LIKE '$periode08b%'";
	$sqljumterjadwal09b="SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`!='' AND `tgl_asesmen` LIKE '$periode09b%'";
	$sqljumterjadwal10b="SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`!='' AND `tgl_asesmen` LIKE '$periode10b%'";
	$sqljumterjadwal11b="SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`!='' AND `tgl_asesmen` LIKE '$periode11b%'";
	$sqljumterjadwal12b="SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`!='' AND `tgl_asesmen` LIKE '$periode12b%'";



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

$sqljeniskelaminl="SELECT * FROM `asesi` WHERE `jenis_kelamin`='L'";
$jeniskelaminl=$conn->query($sqljeniskelaminl);
$jumlaki=$jeniskelaminl->num_rows;
$sqljeniskelaminp="SELECT * FROM `asesi` WHERE `jenis_kelamin`='P'";
$jeniskelaminp=$conn->query($sqljeniskelaminp);
$jumperempuan=$jeniskelaminp->num_rows;
$sqljeniskelaminu="SELECT * FROM `asesi` WHERE `jenis_kelamin`=''";
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
$sqlgetusia1="SELECT * FROM `asesi` WHERE `usia` BETWEEN 15.5 AND 21.5";
$getusia1=$conn->query($sqlgetusia1);
$jumusia1=$getusia1->num_rows;
$sqlgetusia2="SELECT * FROM `asesi` WHERE `usia` BETWEEN 21.5 AND 26.5";
$getusia2=$conn->query($sqlgetusia2);
$jumusia2=$getusia2->num_rows;
$sqlgetusia3="SELECT * FROM `asesi` WHERE `usia` BETWEEN 26.5 AND 32.5";
$getusia3=$conn->query($sqlgetusia3);
$jumusia3=$getusia3->num_rows;
$sqlgetusia4="SELECT * FROM `asesi` WHERE `usia` BETWEEN 32.5 AND 38.5";
$getusia4=$conn->query($sqlgetusia4);
$jumusia4=$getusia4->num_rows;
$sqlgetusia5="SELECT * FROM `asesi` WHERE `usia` BETWEEN 38.5 AND 44.5";
$getusia5=$conn->query($sqlgetusia5);
$jumusia5=$getusia5->num_rows;
$sqlgetusia6="SELECT * FROM `asesi` WHERE `usia` BETWEEN 44.5 AND 50.5";
$getusia6=$conn->query($sqlgetusia6);
$jumusia6=$getusia6->num_rows;
$sqlgetusia7="SELECT * FROM `asesi` WHERE `usia` BETWEEN 50.5 AND 56.5";
$getusia7=$conn->query($sqlgetusia7);
$jumusia7=$getusia7->num_rows;
$sqlgetusia8="SELECT * FROM `asesi` WHERE `usia` BETWEEN 56.5 AND 62.5";
$getusia8=$conn->query($sqlgetusia8);
$jumusia8=$getusia8->num_rows;
$sqlgetusia9="SELECT * FROM `asesi` WHERE `usia` BETWEEN 62.5 AND 68.5";
$getusia9=$conn->query($sqlgetusia9);
$jumusia9=$getusia9->num_rows;
$sqlgetusia10="SELECT * FROM `asesi` WHERE `usia` BETWEEN 68.5 AND 74.5";
$getusia10=$conn->query($sqlgetusia10);
$jumusia10=$getusia10->num_rows;
$sqlgetusia11="SELECT * FROM `asesi` WHERE `usia` > 74.5";
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
  if ($_SESSION['leveluser']=='admin' OR $_SESSION[leveluser]=='user'){
    include "modul/mod_users/users.php";
  }
}

// Bagian Password User ======================================================================================================
elseif ($_GET['module']=='password'){
  if ($_SESSION['leveluser']=='admin' OR $_SESSION[leveluser]=='user'){
    include "modul/mod_password/password.php";
  }
}
// Bagian Modul ==============================================================================================================
elseif ($_GET['module']=='modul'){
   if ($_SESSION['leveluser']=='admin' OR $_SESSION['leveluser']=='user'){
    include "modul/mod_modul/modul.php";
  }
}

// Bagian LSP ================================================================================================================
elseif ($_GET['module']=='lsp'){
  if ($_SESSION['leveluser']=='admin'|| $_SESSION['leveluser']=='user'){
if( isset( $_REQUEST['tambahkan'] ))
	{
	$kode_lsp=$_POST['kodelsp'];
	$nama=$_POST['namalsp'];
	$direktur=$_POST['direktur'];
	$penanggungjawab=$_POST['penjablsp'];
	$jenis_lsp=$_POST['jenislsp'];
	$institusi_induk=$_POST['institusi_induk'];
	$alamat=$_POST['alamatlsp'];
	$kelurahan=$_POST['kelurahan'];
	$id_wilayah=$_POST['kecamatan'];
	$kodepos=$_POST['kodepos'];
	$telepon=$_POST['telepon'];
	$email=$_POST['email'];
	$fax=$_POST['fax'];
	$wa=$_POST['wa'];
	$website=$_POST['website'];
	$tgl_pendirian=$_POST['tgl_pendirian'];
	$no_lisensi=$_POST['no_lisensi'];
	$masa_berlaku=$_POST['masa_berlaku'];
	$id_skkni=$_POST['skknilsp'];
	$cekdu="SELECT * FROM `lsp` WHERE `kode_lsp`='$kodelsp'";
	$result = $conn->query($cekdu);
	if ($result->num_rows == 0){
		$conn->query("INSERT INTO `lsp`(`kode_lsp`, `nama`, `direktur`, `penanggungjawab`, `jenis_lsp`, `institusi_induk`, `alamat`, `kelurahan`, `id_wilayah`, `kodepos`, `telepon`, `email`, `fax`, `wa`, `website`, `tgl_pendirian`, `no_lisensi`, `masa_berlaku`, `id_skkni`) VALUES ('$kode_lsp', '$nama', '$direktur', '$penanggungjawab', '$jenis_lsp', '$institusi_induk', '$alamat', '$kelurahan', '$id_wilayah', '$kodepos', '$telepon', '$email', '$fax', '$wa', '$website', '$tgl_pendirian', '$no_lisensi', '$masa_berlaku', '$id_skkni')");
        echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Input Data Sukses</h4>
			Anda Telah Berhasil Input Data <b>LSP $nama</b></div>";

	}else{
		echo "<script>alert('Maaf LSP dengan Kode Tersebut Sudah Ada');</script>";
	}
}
if( isset( $_REQUEST['hapuslsp'] ))
	{
	$cekdu="SELECT * FROM `lsp` WHERE `id`='$_POST[iddellsp]'";
	$result = $conn->query($cekdu);
	if ($result->num_rows != 0){
		$conn->query("DELETE FROM `lsp` WHERE `id`='$_POST[iddellsp]'");
        echo "<div class='alert alert-danger alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Hapus Data Sukses</h4>
			Anda Telah Berhasil Menghapus Data <b>LSP</b></div>";

	}else{
		echo "<script>alert('Maaf LSP dengan ID Tersebut Tidak Ditemukan');</script>";
	}
}
    echo "
    <!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
        Lembaga Sertifikasi Profesi (LSP)
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
              <h3 class='box-title'>Data Lembaga Sertifikasi Profesi</h3>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>
			<div style='overflow-x:auto;'>
				<table id='example1' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Identitas dan Data LSP</th><th>Penanggungjawab</th><th>Aksi</th></tr></thead>
					<tbody>";
					$no=1;
					$sqllsp="SELECT * FROM `lsp` ORDER BY `id` ASC";
					$lsp=$conn->query($sqllsp);
					while ($pm=$lsp->fetch_assoc()){
						$masa_berlaku=tgl_indo($pm['masa_berlaku']);
						$sqljenislsp="SELECT * FROM `lsp_jenis` WHERE `kode`='$pm[jenis_lsp]'";
						$jenislsp=$conn->query($sqljenislsp);
						$jnl=$jenislsp->fetch_assoc();
						echo "<tr class=gradeX><td>$no</td><td><b>$pm[nama]</b><br>Jenis : <b>$pm[jenis_lsp] ($jnl[nama_kategori])</b><br>";
						$tglsekarang=date("Y-m-d");
						if ($pm['masa_berlaku']>=$tglsekarang){
							echo "Lisensi : <font color='green'><b>$pm[no_lisensi]</b> (Berlaku sd. <b>$masa_berlaku</b>)</font><br />";
						}else{
							echo "Lisensi : <font color='red'><b>$pm[no_lisensi]</b> (Telah Berakhir pada <b>$masa_berlaku</b>)</font><br />";
						}
						$namaskkni=$conn->query("SELECT * FROM `skkni` WHERE `id`='$pm[id_skkni]'");
						$nsk=$namaskkni->fetch_assoc();
						echo "SKKNI : <b>$nsk[nama]</b>";
						echo "</td><td>Ketua :<br><b>$pm[direktur]</b><br>Dewan Pengarah :<br><b>$pm[penanggungjawab]</b></td>";
					    echo "<td><form name='frm' method='POST' role='form' enctype='multipart/form-data'>
							<input type='hidden' name='iddellsp' value='$pm[id]'><input type='submit' name='hapuslsp' class='btn btn-danger btn-xs' title='Hapus' value='Hapus'></form>
							<a href='?module=ubahlsp&idl=$pm[id]' class='btn btn-primary btn-xs'>Ubah</a>
						</td></tr>";
						$no++;
					}
				echo "</tbody></table>
			</div>
			</div>
		  </div>
		  <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Input Data Lembaga Sertifikasi Profesi (LSP)</h3>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>
			
				<form name='frm' method='POST' role='form' enctype='multipart/form-data'>
					<div class='col-md-6'>
						<div class='form-group'>
							<label>Kode LSP</label>
							<input type='text' name='kodelsp' class='form-control'>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
							<label>Nama LSP</label>
							<input type='text' name='namalsp' class='form-control' required>
						</div>
					</div>
					<div class='col-md-3'>
						<div class='form-group'>
							<label>Ketua LSP</label>
							<input type='text' name='direktur' class='form-control' required>
						</div>
					</div>
					<div class='col-md-3'>
						<div class='form-group'>
							<label>Dewan Pengarah LSP</label>
							<input type='text' name='penjablsp' class='form-control' required>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
								<label>Jenis LSP</label>
								<select name='jenislsp' class='form-control'>";
								$sqllspjenis="SELECT * FROM `lsp_jenis` ORDER BY `kode` ASC";
								$indukkat=$conn->query($sqllspjenis);
								while ($ik=$indukkat->fetch_assoc()){
									echo"<option value='$ik[kode]'>$ik[nama_kategori] ($ik[kode])</option>";
								}
								echo"</select>
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
					<div class='col-md-2'>
						<div class='form-group'>
							<label>Telepon</label>
							<input type='text' name='telepon' class='form-control'>
						</div>
					</div>
					<div class='col-md-2'>
						<div class='form-group'>
							<label>Faximile</label>
							<input type='text' name='fax' class='form-control'>
						</div>
					</div>
					<div class='col-md-2'>
						<div class='form-group'>
							<label>WhatsApp</label>
							<input type='text' name='wa' class='form-control'>
						</div>
					</div>
					<div class='col-md-8'>
						<div class='form-group'>
							<label>Alamat LSP</label>
							<input type='text' name='alamatlsp' class='form-control'>
						</div>
					</div>
					<div class='col-md-4'>
						<div class='form-group'>
							<label>Desa/Kelurahan</label>
							<input type='text' name='kelurahan' class='form-control'>
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
					<div class='col-md-2'>
						<div class='form-group'>
							<label>Kode Pos</label>
							<input type='text' name='kodepos' class='form-control'>
						</div>
					</div>
					<div class='col-md-4'>
						<div class='form-group'>
							<label>Website</label>
							<input type='text' name='website' class='form-control' placeholder='http://'>
						</div>
					</div>

					<div class='col-md-3'>
						<div class='form-group'>
							<label>Nomor Lisensi</label>
							<input type='text' name='no_lisensi' class='form-control'>
						</div>
					</div>
					<div class='col-md-3'>
						<div class='form-group'>
							<label>Masa Berlaku Lisensi</label>
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
	}
}

// Bagian Ubah LSP ================================================================================================================
elseif ($_GET['module']=='ubahlsp'){
  if ($_SESSION['leveluser']=='admin'|| $_SESSION['leveluser']=='user'){
if( isset( $_REQUEST['ubahlsp'] ))
	{
	$kode_lsp=$_POST['kodelsp'];
	$nama=$_POST['namalsp'];
	$direktur=$_POST['direktur'];
	$penanggungjawab=$_POST['penjablsp'];
	$jenis_lsp=$_POST['jenislsp'];
	$institusi_induk=$_POST['institusi_induk'];
	$alamat=$_POST['alamatlsp'];
	$kelurahan=$_POST['kelurahan'];
	$id_wilayah=$_POST['kecamatan'];
	$kodepos=$_POST['kodepos'];
	$telepon=$_POST['telepon'];
	$email=$_POST['email'];
	$fax=$_POST['fax'];
	$wa=$_POST['wa'];
	$website=$_POST['website'];
	$tgl_pendirian=$_POST['tgl_pendirian'];
	$no_lisensi=$_POST['no_lisensi'];
	$masa_berlaku=$_POST['masa_berlaku'];
	$id_skkni=$_POST['skknilsp'];
	$cekdu="SELECT * FROM `lsp` WHERE `id`='$_POST[idlsp]' AND `kode_lsp`='$kodelsp'";
	$result = $conn->query($cekdu);
	if ($result->num_rows == 0){
		$conn->query("UPDATE `lsp` SET `kode_lsp`='$kode_lsp', `nama`='$nama', `direktur`='$direktur', `penanggungjawab`='$penanggungjawab', `jenis_lsp`='$jenis_lsp', `institusi_induk`='$institusi_induk', `alamat`='$alamat', `kelurahan`='$kelurahan', `id_wilayah`='$id_wilayah', `kodepos`='$kodepos', `telepon`='$telepon', `email`='$email', `fax`='$fax', `wa`='$wa', `website`='$website', `tgl_pendirian`='$tgl_pendirian', `no_lisensi`='$no_lisensi', `masa_berlaku`='$masa_berlaku', `id_skkni`='$id_skkni', `googlemapcode`='$_POST[googlemapcode]' WHERE `id`='$_POST[idlsp]'");
        echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Update Data Sukses</h4>
			Anda Telah Berhasil Ubah Data <b>LSP $nama</b></div>";

	}else{
		echo "<script>alert('Maaf LSP dengan Kode Tersebut Tidak Ada');</script>";
	}
}
    echo "
    <!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
        Lembaga Sertifikasi Profesi (LSP)
        <small>Ubah Data</small>
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
              <h3 class='box-title'>Ubah Data Lembaga Sertifikasi Profesi (LSP)</h3>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>";
		$sqlgetlsp="SELECT * FROM `lsp` WHERE `id`='$_GET[idl]'";
		$getlsp=$conn->query($sqlgetlsp);
		$gl=$getlsp->fetch_assoc();
				echo "<form name='frm' method='POST' role='form' enctype='multipart/form-data'>
					<input type='hidden' name='idlsp' class='form-control' value='$gl[id]'>
					<div class='col-md-3'>
						<div class='form-group'>
							<label>Kode LSP</label>
							<input type='text' name='kodelsp' class='form-control' value='$gl[kode_lsp]'>
						</div>
					</div>
					<div class='col-md-3'>
						<div class='form-group'>
							<label>Alamat Email</label>
							<input type='text' name='email' class='form-control' value='$gl[email]'>
						</div>
					</div>

					<div class='col-md-6'>
						<div class='form-group'>
							<label>Nama LSP</label>
							<input type='text' name='namalsp' class='form-control' value='$gl[nama]' required>
						</div>
					</div>
					<div class='col-md-3'>
						<div class='form-group'>
							<label>Ketua LSP</label>
							<input type='text' name='direktur' class='form-control' value='$gl[direktur]' required>
						</div>
					</div>
					<div class='col-md-3'>
						<div class='form-group'>
							<label>Dewan Pengarah LSP</label>
							<input type='text' name='penjablsp' class='form-control' value='$gl[penanggungjawab]' required>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
								<label>Jenis LSP</label>
								<select name='jenislsp' class='form-control'>";
								$sqllspjenis="SELECT * FROM `lsp_jenis` ORDER BY `kode` ASC";
								$indukkat=$conn->query($sqllspjenis);
								while ($ik=$indukkat->fetch_assoc()){
									if ($gl['jenis_lsp']==$ik['kode']){
										echo"<option value='$ik[kode]' selected>$ik[nama_kategori] ($ik[kode])</option>";
									}else{
										echo"<option value='$ik[kode]'>$ik[nama_kategori] ($ik[kode])</option>";
									}
								}
								echo"</select>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
								<label>SKKNI LSP</label>
								<select name='skknilsp' class='form-control'>";
								$sqlskkni="SELECT * FROM `skkni` ORDER BY `nama` ASC";
								$skkni=$conn->query($sqlskkni);
								while ($sk=$skkni->fetch_assoc()){
									if ($gl['id_skkni']==$sk['id']){
										echo"<option value='$sk[id]' selected>$sk[nama]</option>";
									}else{
										echo"<option value='$sk[id]'>$sk[nama]</option>";
									}
								}
								echo"</select>
						</div>
					</div>
					<div class='col-md-2'>
						<div class='form-group'>
							<label>Telepon</label>
							<input type='text' name='telepon' class='form-control' value='$gl[telepon]'>
						</div>
					</div>
					<div class='col-md-2'>
						<div class='form-group'>
							<label>Faximile</label>
							<input type='text' name='fax' class='form-control' value='$gl[fax]'>
						</div>
					</div>
					<div class='col-md-2'>
						<div class='form-group'>
							<label>WhatsApp</label>
							<input type='text' name='wa' class='form-control' value='$gl[wa]'>
						</div>
					</div>
					<div class='col-md-8'>
						<div class='form-group'>
							<label>Alamat LSP</label>
							<input type='text' name='alamatlsp' class='form-control' value='$gl[alamat]'>
						</div>
					</div>
					<div class='col-md-4'>
						<div class='form-group'>
							<label>Desa/Kelurahan</label>
							<input type='text' name='kelurahan' class='form-control' value='$gl[kelurahan]'>
						</div>
					</div>

					<div class='col-md-6'>
						<div class='form-group'>
							<label>Provinsi</label>
							<select name='propinsi' class='form-control' id='propinsi'>";
							$sql1="SELECT * FROM  `data_wilayah` WHERE  `id_wil`='$gl[id_wilayah]'";
							$tampil1=$conn->query($sql1);
							$wil1=$tampil1->fetch_assoc();
							$sql2="SELECT * FROM  `data_wilayah` WHERE  `id_wil`='$wil1[id_induk_wilayah]'";
							$tampil2=$conn->query($sql2);
							$wil2=$tampil2->fetch_assoc();
							$sql3="SELECT * FROM  `data_wilayah` WHERE  `id_wil`='$wil2[id_induk_wilayah]'";
							$tampil3=$conn->query($sql3);
							$wil3=$tampil3->fetch_assoc();
							$sql="SELECT * FROM  `data_wilayah` WHERE  id_level_wil='1' AND id_induk_wilayah!='NULL' ORDER BY id_wil ASC";
							$tampil=$conn->query($sql);

							while($rr=$tampil->fetch_assoc()){
								if($wil3['id_wil']==$rr['id_wil']){
									echo "<option value='$rr[id_wil]' selected>$rr[nm_wil]</option>";
								}else{
									echo "<option value='$rr[id_wil]'>$rr[nm_wil]</option>";
								}
							}
							echo"</select>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
							<label>Kota/Kabupaten</label>
							<select name='kota' class='form-control' id='kota'>";
							$sql2b="SELECT * FROM  `data_wilayah` WHERE `id_induk_wilayah`='$wil2[id_induk_wilayah]'";
							$tampil2b=$conn->query($sql2b);
							while ($rr2=$tampil2b->fetch_assoc()){
								if($wil2['id_wil']==$rr2['id_wil']){
									echo"<option value='$rr2[id_wil]' selected>$rr2[nm_wil]</option>";
								}else{
									echo"<option value='$rr2[id_wil]'>$rr2[nm_wil]</option>";
								}
							}
							echo"</select>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
							<label>Kecamatan</label>
							<select name='kecamatan' class='form-control' id='kecamatan'>";
							$sql1b="SELECT * FROM  `data_wilayah` WHERE `id_induk_wilayah`='$wil1[id_induk_wilayah]'";
							$tampil1b=$conn->query($sql1b);
							while ($rr1=$tampil1b->fetch_assoc()){
								if($wil1['id_wil']==$rr1['id_wil']){
									echo"<option value='$rr1[id_wil]' selected>$rr1[nm_wil]</option>";
								}else{
									echo"<option value='$rr1[id_wil]'>$rr1[nm_wil]</option>";
								}
							}
							echo"</select>
						</div>
					</div>
					<div class='col-md-2'>
						<div class='form-group'>
							<label>Kode Pos</label>
							<input type='text' name='kodepos' class='form-control' value='$gl[kodepos]'>
						</div>
					</div>
					<div class='col-md-4'>
						<div class='form-group'>
							<label>Website</label>
							<input type='text' name='website' class='form-control' value='$gl[website]' placeholder='http://'>
						</div>
					</div>

					<div class='col-md-3'>
						<div class='form-group'>
							<label>Nomor Lisensi</label>
							<input type='text' name='no_lisensi' class='form-control' value='$gl[no_lisensi]'>
						</div>
					</div>
					<div class='col-md-3'>
						<div class='form-group'>
							<label>Masa Berlaku Lisensi</label>
							<input type='date' name='masa_berlaku' class='form-control' value='$gl[masa_berlaku]'>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
							<label>Institusi Induk</label>
							<input type='text' name='institusi_induk' class='form-control' value='$gl[institusi_induk]'>
						</div>
					</div>
					<div class='col-md-12'>
						<div class='form-group'>
							<label>Kode Google Maps</label>
							<input type='text' name='googlemapcode' class='form-control' value='$gl[googlemapcode]' placeholder='Kode Embed Google Maps Custom Size 600 x 450 pixel'>
						</div>
					</div>

					<div class='col-md-12'>
						<div class='form-group'>
							<input type='submit'  name='ubahlsp' class='btn btn-primary' value='Perbarui Data'>
						</div>
					</div>
				</form>
			
			</div>
		  </div>
          
		</div>
	  </div>
	</section>";
	}
}

// Bagian TUK ================================================================================================================
elseif ($_GET['module']=='tuk'){
  if ($_SESSION['leveluser']=='admin'|| $_SESSION['leveluser']=='user'){
if( isset( $_REQUEST['tambahkan'] ))
	{
	$kode_tuk=$_POST['kodetuk'];
	$nama=$_POST['namatuk'];
	$penanggungjawab=$_POST['penjabtuk'];
	$jenis_tuk=$_POST['jenistuk'];
	$induklsp=$_POST['induktuk'];
	$institusi_induk=$_POST['institusi_induk'];
	$alamat=$_POST['alamattuk'];
	$kelurahan=$_POST['kelurahan'];
	$id_wilayah=$_POST['kecamatan'];
	$kodepos=$_POST['kodepos'];
	$telepon=$_POST['telepon'];
	$email=$_POST['email'];
	$fax=$_POST['fax'];
	$tgl_pendirian=$_POST['tgl_pendirian'];
	$no_lisensi=$_POST['no_lisensi'];
	$masa_berlaku=$_POST['masa_berlaku'];
	$id_skkni=$_POST['skknituk'];
	$cekdu="SELECT * FROM `tuk` WHERE `kode_tuk`='$kode_tuk'";
	$result = $conn->query($cekdu);
	if ($result->num_rows == 0){
		$conn->query("INSERT INTO `tuk`(`kode_tuk`, `nama`, `penanggungjawab`, `jenis_tuk`, `lsp_induk`, `institusi_induk`, `alamat`, `kelurahan`, `id_wilayah`, `kodepos`, `telepon`, `email`, `fax`, `tgl_pendirian`, `no_lisensi`, `masa_berlaku`, `id_skkni`) VALUES ('$kode_tuk', '$nama', '$penanggungjawab', '$jenis_tuk', '$induklsp', '$institusi_induk', '$alamat', '$kelurahan', '$id_wilayah', '$kodepos', '$telepon', '$email', '$fax', '$tgl_pendirian', '$no_lisensi', '$masa_berlaku', '$id_skkni')");
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
					<thead><tr><th>No</th><th>Nama TUK</th><th>LSP Induk</th><th>No. Lisensi dan SKKNI</th><th>Penanggungjawab</th><th>Aksi</th></tr></thead>
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
					    	echo "<td>";
						if ($jumjadwal==0){
							echo "<form name='frm' method='POST' role='form' enctype='multipart/form-data'>
							<input type='hidden' name='iddeltuk' value='$pm[id]'><input type='submit' name='hapustuk' class='btn btn-danger btn-xs' title='Hapus' value='Hapus'></form>";
						}
						echo "<a href='?module=updatetuk&idt=$pm[id]' class='btn btn-primary btn-xs'>Ubah</a></td></tr>";
						$no++;
					}
				echo "</tbody></table>
			</div>
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
					<div class='col-md-3'>
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
					<div class='col-md-3'>
						<div class='form-group'>
								<label>Jenis TUK</label>
								<select name='jenistuk' class='form-control'>";
								$sqltukjenis2="SELECT * FROM `tuk_jenis` ORDER BY `jenis_tuk` ASC";
								$indukkat2=$conn->query($sqltukjenis2);
								while ($ik2=$indukkat2->fetch_assoc()){
									echo"<option value='$ik2[id]'>$ik2[jenis_tuk]</option>";
								}
								echo"</select>
						</div>
					</div>

					<div class='col-md-6'>
						<div class='form-group'>
								<label>SKK/SKKNI TUK</label>
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
							<textarea name='alamattuk' class='form-control'></textarea>
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
					<div class='col-md-4'>
						<div class='form-group'>
							<label>Desa/Kelurahan</label>
							<input type='text' name='kelurahan' class='form-control'>
						</div>
					</div>
					<div class='col-md-2'>
						<div class='form-group'>
							<label>Kode Pos</label>
							<input type='text' name='kodepos' class='form-control'>
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
	}
}

// Bagian Update TUK ================================================================================================================
elseif ($_GET['module']=='updatetuk'){
  if ($_SESSION['leveluser']=='admin'|| $_SESSION['leveluser']=='user'){
if( isset( $_REQUEST['ubahtuk'] ))
	{
	$kode_tuk=$_POST['kodetuk'];
	$nama=$_POST['namatuk'];
	$penanggungjawab=$_POST['penjabtuk'];
	$jenis_tuk=$_POST['jenistuk'];
	$induklsp=$_POST['induktuk'];
	$institusi_induk=$_POST['institusi_induk'];
	$alamat=$_POST['alamattuk'];
	$kelurahan=$_POST['kelurahan'];
	$id_wilayah=$_POST['kecamatan'];
	$kodepos=$_POST['kodepos'];
	$telepon=$_POST['telepon'];
	$email=$_POST['email'];
	$fax=$_POST['fax'];
	$tgl_pendirian=$_POST['tgl_pendirian'];
	$no_lisensi=$_POST['no_lisensi'];
	$masa_berlaku=$_POST['masa_berlaku'];
	$id_skkni=$_POST['skknituk'];
	$cekdu="SELECT * FROM `tuk` WHERE `id`='$_GET[idt]'";
	$result = $conn->query($cekdu);
	if ($result->num_rows != 0){
		$conn->query("UPDATE `tuk` SET `kode_tuk`='$kode_tuk', `nama`='$nama', `penanggungjawab`='$penanggungjawab', `jenis_tuk`='$jenis_tuk', `lsp_induk`='$induklsp', `institusi_induk`='$institusi_induk', `alamat`='$alamat', `kelurahan`='$kelurahan', `id_wilayah`='$id_wilayah', `kodepos`='$kodepos', `telepon`='$telepon', `email`='$email', `fax`='$fax', `tgl_pendirian`='$tgl_pendirian', `no_lisensi`='$no_lisensi', `masa_berlaku`='$masa_berlaku', `id_skkni`='$id_skkni' WHERE `id`='$_GET[idt]'");
        	echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Ubah Data Sukses</h4>
			Anda Telah Berhasil Ubah Data <b>TUK $nama</b></div>";

	}else{
		echo "<script>alert('Maaf TUK dengan Kode Tersebut Tidak Ada');</script>";
	}
}

    	$sqlgettuk="SELECT * FROM `tuk` WHERE `id`='$_GET[idt]'";
	$gettuk=$conn->query($sqlgettuk);
	$tu=$gettuk->fetch_assoc();
    echo "
    <!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
        Tempat Uji Kompetensi (TUK)
        <small>Ubah Data</small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li><a href='media.php?module=tuk'><i class='fa fa-bank'></i> Tempat Uji Kompetensi (TUK)</a></li>
        <li class='active'>Ubah Data</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class='content'>
      <div class='row'>
        <div class='col-xs-12'>
          <div class='box'>

            <div class='box-header'>
              <h3 class='box-title'>Ubah Data Tempat Uji Kompetensi (TUK)</h3>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>
			
				<form name='frm' method='POST' role='form' enctype='multipart/form-data'>
					<div class='col-md-6'>
						<div class='form-group'>
							<label>Kode TUK</label>
							<input type='text' name='kodetuk' class='form-control' value='$tu[kode_tuk]'>
							<input type='hidden' name='id_tuk' class='form-control' value='$tu[id]'>

						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
							<label>Nama TUK</label>
							<input type='text' name='namatuk' class='form-control' value='$tu[nama]' required>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
							<label>Penanggungjawab TUK</label>
							<input type='text' name='penjabtuk' class='form-control' value='$tu[penanggungjawab]' required>
						</div>
					</div>
					<div class='col-md-3'>
						<div class='form-group'>
								<label>LSP Induk TUK</label>
								<select name='induktuk' class='form-control'>";
								$sqltukjenis="SELECT * FROM `lsp` ORDER BY `nama` ASC";
								$indukkat=$conn->query($sqltukjenis);
								while ($ik=$indukkat->fetch_assoc()){
									echo"<option value='$ik[id]'"; if ($tu['lsp_induk']==$ik['id']){ echo "selected"; } echo">$ik[nama] ($ik[jenis_lsp])</option>";
								}
								echo"</select>
						</div>
					</div>
					<div class='col-md-3'>
						<div class='form-group'>
								<label>Jenis TUK</label>
								<select name='jenistuk' class='form-control'>";
								$sqltukjenis2="SELECT * FROM `tuk_jenis` ORDER BY `jenis_tuk` ASC";
								$indukkat2=$conn->query($sqltukjenis2);
								while ($ik2=$indukkat2->fetch_assoc()){
									echo"<option value='$ik2[id]'"; if ($tu['jenis_tuk']==$ik2['id']){ echo "selected"; } echo">$ik2[jenis_tuk]</option>";
								}
								echo"</select>
						</div>
					</div>

					<div class='col-md-6'>
						<div class='form-group'>
								<label>SKK/SKKNI TUK</label>
								<select name='skknituk' class='form-control'>";
								$sqlskkni="SELECT * FROM `skkni` ORDER BY `nama` ASC";
								$skkni=$conn->query($sqlskkni);
								while ($sk=$skkni->fetch_assoc()){
									echo"<option value='$sk[id]'"; if ($tu['id_skkni']==$sk['id']){ echo "selected"; } echo">$sk[nama]</option>";
								}
								echo"</select>
						</div>
					</div>
					<div class='col-md-3'>
						<div class='form-group'>
							<label>Telepon</label>
							<input type='text' name='telepon' class='form-control' value='$tu[telepon]'>
						</div>
					</div>
					<div class='col-md-3'>
						<div class='form-group'>
							<label>Faximile</label>
							<input type='text' name='fax' class='form-control' value='$tu[fax]'>
						</div>
					</div>
					<div class='col-md-12'>
						<div class='form-group'>
							<label>Alamat TUK</label>
							<textarea name='alamattuk' class='form-control'>$tu[alamat]</textarea>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
							<label>Provinsi</label>
							<select name='propinsi' class='form-control' id='propinsi'>";

							$sqlkecamatan="SELECT * FROM  `data_wilayah` WHERE  `id_wil`='$tu[id_wilayah]' ORDER BY `id_wil` ASC";
							$tampilkec=$conn->query($sqlkecamatan);
							$kec=$tampilkec->fetch_assoc();

							$sqlkota="SELECT * FROM  `data_wilayah` WHERE  `id_wil`='$kec[id_induk_wilayah]' ORDER BY `id_wil` ASC";
							$tampilkota=$conn->query($sqlkota);
							$kota=$tampilkota->fetch_assoc();

							$sqlpropinsi="SELECT * FROM  `data_wilayah` WHERE `id_wil`='$kota[id_induk_wilayah]' ORDER BY `id_wil` ASC";
							$tampilpropinsi=$conn->query($sqlpropinsi);
							$prop=$tampilpropinsi->fetch_assoc();

							$sql="SELECT * FROM  `data_wilayah` WHERE  `id_level_wil`='1' AND `id_induk_wilayah`!='NULL' ORDER BY `id_wil` ASC";
							$tampil=$conn->query($sql);
							while($rr=$tampil->fetch_assoc()){
								echo "<option value='$rr[id_wil]'"; if ($prop['id_wil']==$rr['id_wil']){ echo "selected"; } echo">$rr[nm_wil]</option>";
							}
							echo"</select>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
							<label>Kota/Kabupaten</label>
							<select name='kota' class='form-control' id='kota'>";
							echo"<option value='$kota[id_wil]'>$kota[nm_wil]</option>";
							echo"</select>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
							<label>Kecamatan</label>
							<select name='kecamatan' class='form-control' id='kecamatan'>";

							echo"<option value='$kec[id_wil]'>$kec[nm_wil]</option>";
							echo"</select>
						</div>
					</div>
					<div class='col-md-4'>
						<div class='form-group'>
							<label>Desa/Kelurahan</label>
							<input type='text' name='kelurahan' class='form-control' value='$tu[kelurahan]'>
						</div>
					</div>
					<div class='col-md-2'>
						<div class='form-group'>
							<label>Kode Pos</label>
							<input type='text' name='kodepos' class='form-control' value='$tu[kodepos]'>
						</div>
					</div>

					<div class='col-md-3'>
						<div class='form-group'>
							<label>Nomor Lisensi/Ijin/SK</label>
							<input type='text' name='no_lisensi' class='form-control' value='$tu[no_lisensi]'>
						</div>
					</div>
					<div class='col-md-3'>
						<div class='form-group'>
							<label>Masa Berlaku Lisensi/Ijin/SK</label>
							<input type='date' name='masa_berlaku' class='form-control' value='$tu[masa_berlaku]'>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
							<label>Institusi Induk</label>
							<input type='text' name='institusi_induk' class='form-control' value='$tu[institusi_induk]'>
						</div>
					</div>
					<div class='col-md-12'>
						<div class='form-group'>
							<input type='submit'  name='ubahtuk' class='btn btn-primary' value='Simpan Perubahan'>
						</div>
					</div>
				</form>
			
			</div>
		  </div>
          
		</div>
	  </div>
	</section>";
	}
}

// Bagian Skema LSP ==========================================================================================================
elseif ($_GET['module']=='skema'){
  if ($_SESSION['leveluser']=='admin'|| $_SESSION['leveluser']=='user'){
    if( isset( $_REQUEST['tambahkan'] ))
	{
	$kode_skema=$_POST['kodeskema'];
	$judul=$_POST['namaskema'];
	$id_skkni=$_POST['skknilsp'];
	$keterangan_bukti=$_POST['keterangan_bukti'];
	$cekdu="SELECT * FROM `skema_kkni` WHERE `kode_skema`='$kode_skema' AND `id_skkni`='$id_skkni'";
	$result = $conn->query($cekdu);
	if ($result->num_rows == 0){
		$conn->query("INSERT INTO `skema_kkni`(`kode_skema`, `judul`, `id_skkni`,`keterangan_bukti`) VALUES ('$kode_skema', '$judul', '$id_skkni', '$keterangan_bukti')");
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
if( isset( $_REQUEST['nonaktifkan'] ))
	{
	$conn->query("UPDATE `skema_kkni` SET `aktif`='N' WHERE `id`='$_POST[idskema]'");
        echo "<div class='alert alert-danger alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Penonaktifan Skema Sukses</h4>
			Anda Telah Berhasil Menonaktifkan Data <b>Skema Sertifikasi</b></div>";
}
if( isset( $_REQUEST['aktifkan'] ))
	{
	$conn->query("UPDATE `skema_kkni` SET `aktif`='Y' WHERE `id`='$_POST[idskema]'");
        echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Penonaktifan Skema Sukses</h4>
			Anda Telah Berhasil Menonaktifkan Data <b>Skema Sertifikasi</b></div>";
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
							echo "<td><a href='?module=insyarat&id=$pm[id]' class='btn btn-primary btn-xs'>Input Persyaratan</a></td>";
						}else{
							echo "<td><b>( $numsyarat ) Persyaratan</b><br><a href='?module=insyarat&id=$pm[id]' class='btn btn-success btn-xs'>Ubah Persyaratan</a></td>";
						}
						$sqlcekdiasesmen="SELECT * FROM `asesi_asesmen` WHERE `id_skemakkni`='$pm[id]'";
						$cekdiasesmen=$conn->query($sqlcekdiasesmen);
						$jumdiasesmen=$cekdiasesmen->num_rows;
						echo "<td>";
						if ($jumdiasesmen==0 && $numsyarat==0){
					    		echo "<form name='frm' method='POST' role='form' enctype='multipart/form-data'>
							<input type='hidden' name='iddellsp' value='$pm[id]'><input type='submit' name='hapuslsp' class='btn btn-danger btn-xs btn-block' title='Hapus' value='Hapus'></form>";
						}
						if ($pm['aktif']=='Y'){
					    		echo "<form name='frm' method='POST' role='form' enctype='multipart/form-data'>
							<input type='hidden' name='idskema' value='$pm[id]'><input type='submit' name='nonaktifkan' class='btn btn-danger btn-xs btn-block' title='Nonaktifkan' value='Nonaktifkan'></form>";
						}else{
					    		echo "<form name='frm' method='POST' role='form' enctype='multipart/form-data'>
							<input type='hidden' name='idskema' value='$pm[id]'><input type='submit' name='aktifkan' class='btn btn-success btn-xs btn-block' title='Aktifkan' value='Aktifkan'></form>";
						}
					    	echo "<a class='btn btn-primary btn-xs btn-block' href='media.php?module=ubahskema&id=$pm[id]'>Ubah/Perbarui</a>";

						echo "</td></tr>";
						$no++;
					}
				echo "</tbody></table>
			</div>
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
								<label>SKK/SKKNI LSP</label>
								<select name='skknilsp' class='form-control'>";
								$sqlskkni="SELECT * FROM `skkni` ORDER BY `nama` ASC";
								$skkni=$conn->query($sqlskkni);
								while ($sk=$skkni->fetch_assoc()){
									echo"<option value='$sk[id]'>$sk[nama]</option>";
								}
								echo"</select>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
							<label>Keterangan Bukti yang Akan Diperoleh</label>
							<input type='text' name='keterangan_bukti' class='form-control' required>
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
  }
}

// Bagian Skema LSP ==========================================================================================================
elseif ($_GET['module']=='ubahskema'){
  if ($_SESSION['leveluser']=='admin'|| $_SESSION['leveluser']=='user'){
if( isset( $_REQUEST['updateskema'] ))
	{
	$conn->query("UPDATE `skema_kkni` SET `kode_skema`='$_POST[kodeskema]',`judul`='$_POST[namaskema]',`id_skkni`='$_POST[skknilsp]',`keterangan_bukti`='$_POST[keterangan_bukti]' WHERE `id`='$_POST[idskema]'");
        echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Ubah Skema Sukses</h4>
			Anda Telah Berhasil Ubah Data <b>Skema Sertifikasi</b></div>";
}

	$sqlgetskema="SELECT * FROM `skema_kkni` WHERE `id`='$_GET[id]'";
	$getskema=$conn->query($sqlgetskema);
	$getsk=$getskema->fetch_assoc();
    echo "
    <!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
        Skema Sertifikasi Profesi
        <small>Ubah Data</small>
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
		  <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Ubah Data Skema Sertifikasi Profesi (LSP)</h3>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>
			
				<form name='frm' method='POST' role='form' enctype='multipart/form-data'>
					<div class='col-md-6'>
						<div class='form-group'>
							<label>Kode Skema</label>
							<input type='hidden' name='idskema' value='$getsk[id]' class='form-control' required>
							<input type='text' name='kodeskema' value='$getsk[kode_skema]' class='form-control' required>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
							<label>Nama Skema Profesi</label>
							<input type='text' name='namaskema' value='$getsk[judul]' class='form-control' required>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
								<label>SKK/SKKNI LSP</label>
								<select name='skknilsp' class='form-control'>";
								$sqlskkni="SELECT * FROM `skkni` ORDER BY `nama` ASC";
								$skkni=$conn->query($sqlskkni);
								while ($sk=$skkni->fetch_assoc()){
									echo"<option value='$sk[id]'"; if ($sk['id']==$getsk['id_skkni'] ){echo "selected";}echo ">$sk[nama]</option>";
								}
								echo"</select>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
							<label>Keterangan Bukti yang Akan Diperoleh</label>
							<input type='text' name='keterangan_bukti' value='$getsk[keterangan_bukti]' class='form-control' required>
						</div>
					</div>

					<div class='col-md-12'>
						<div class='form-group'>
							<input type='submit'  name='updateskema' class='btn btn-primary pull-right' value='Perbarui'>
						</div>
						<div class='form-group'>
							<a class='btn btn-default' href='media.php?module=skema'>Kembali</a>
						</div>

					</div>
				</form>
			
			</div>
		  </div>
          
		</div>
	  </div>
	</section>";
  }
}


// Bagian Unit Kompetensi LSP ==========================================================================================================
elseif ($_GET['module']=='unitkompetensi'){
  if ($_SESSION['leveluser']=='admin'|| $_SESSION['leveluser']=='user'){
    if( isset( $_REQUEST['tambahkanunit'] ))
	{
	$kode_skema=$_POST['kodeunit'];
	$judul=$_POST['namaunit'];
	$id_skkni=$_POST['skemakknilsp'];
	$cekdu="SELECT * FROM `unit_kompetensi` WHERE `kode_unit`='$kode_skema' AND `id_skemakkni`='$id_skkni'";
	$result = $conn->query($cekdu);
	if ($result->num_rows == 0){
		$conn->query("INSERT INTO `unit_kompetensi`(`kode_unit`, `judul`, `id_skemakkni`) VALUES ('$kode_skema', '$judul', '$id_skkni')");
        echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Input Data Sukses</h4>
			Anda Telah Berhasil Input Data <b>Unit Kompetensi $judul ($kode_skema)</b></div>";

	}else{
		echo "<script>alert('Maaf Unit Kompetensi dengan Kode tersebut Sudah Ada');</script>";
	}
}
if( isset( $_REQUEST['hapusunit'] ))
	{
	$cekdu="SELECT * FROM `unit_kompetensi` WHERE `id`='$_POST[iddelunit]'";
	$result = $conn->query($cekdu);
	if ($result->num_rows != 0){
		$conn->query("DELETE FROM `unit_kompetensi` WHERE `id`='$_POST[iddelunit]'");
        echo "<div class='alert alert-danger alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Hapus Data Sukses</h4>
			Anda Telah Berhasil Menghapus Data <b>Unit Kompetensi Sertifikasi</b></div>";

	}else{
		echo "<script>alert('Maaf Unit Kompetensi dengan Kode tersebut Tidak Ditemukan');</script>";
	}
}
    echo "
    <!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
        Unit Kompetensi Sertifikasi Profesi
        <small>Input Data</small>
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
					<thead><tr><th>No</th><th>Unit Kompetensi</th><th>Skema KKNI Sertifikasi</th><th>Standar</th><th>Aksi</th></tr></thead>
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

					    	echo "<td>$ns2[nama]<br>($pm[jenis])</td><td>";
						if ($jumelemen==0){
							echo "<form name='frm' method='POST' role='form' enctype='multipart/form-data'>
							<input type='hidden' name='iddelunit' value='$pm[id]'><input type='submit' name='hapusunit' class='btn btn-danger btn-xs' title='Hapus' value='Hapus'></form>";
						}
						echo "</td></tr>";
						$no++;
					}
				echo "</tbody></table>
			</div>
			</div>
		  </div>
		  <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Input Data Unit Kompetensi Sertifikasi Profesi (LSP)</h3>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>
			
				<form name='frm' method='POST' role='form' enctype='multipart/form-data'>
					<div class='col-md-6'>
						<div class='form-group'>
							<label>Kode Unit</label>
							<input type='text' name='kodeunit' class='form-control' required>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
							<label>Nama Unit Kompetensi Profesi</label>
							<input type='text' name='namaunit' class='form-control' required>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
								<label>Skema KKNI LSP</label>
								<select name='skemakknilsp' class='form-control'>";
								$sqlskkni="SELECT * FROM `skema_kkni` WHERE `aktif`='Y' ORDER BY `judul` ASC";
								$skkni=$conn->query($sqlskkni);
								while ($sk=$skkni->fetch_assoc()){
									echo"<option value='$sk[id]'>$sk[judul]</option>";
								}
								echo"</select>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
								<label>Jenis Standar</label>
								<select name='jenis' class='form-control'>
									<option value='SKKNI'>SKKNI</option>
									<option value='Standar Khusus'>Standar Kompetensi Khusus</option>
									<option value='Standar Internasional'>Standar Internasional</option>
								</select>
						</div>
					</div>
					<div class='col-md-12'>
						<div class='form-group'>
							<input type='submit'  name='tambahkanunit' class='btn btn-primary' value='Tambahkan'>
						</div>
					</div>
				</form>
			
			</div>
		  </div>
          
		</div>
	  </div>
	</section>";
  }
}

// Bagian Elemen Kompetensi LSP ==========================================================================================================
elseif ($_GET['module']=='elemenkompetensi'){
  if ($_SESSION['leveluser']=='admin'|| $_SESSION['leveluser']=='user'){
    if( isset( $_REQUEST['tambahelemen'] ))
	{
	$id_unitkompetensi=$_POST['iduk'];
	$elemen=$_POST['elemen'];
	$cekdu="SELECT * FROM `elemen_kompetensi` WHERE `elemen_kompetensi`='$_POST[elemen]' AND `id_unitkompetensi`='$_POST[iduk]'";
	$result = $conn->query($cekdu);
	if ($result->num_rows == 0){
		$conn->query("INSERT INTO `elemen_kompetensi`(`id_unitkompetensi`, `elemen_kompetensi`) VALUES ('$id_unitkompetensi', '$elemen')");
        echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Input Data Sukses</h4>
			Anda Telah Berhasil Input Data <b>Elemen Unit Kompetensi : $elemen</b></div>";

	}else{
		echo "<script>alert('Maaf Elemen Unit Kompetensi dengan Kode tersebut Sudah Ada');</script>";
	}
}
if( isset( $_REQUEST['hapuselemen'] ))
	{
	$cekdu="SELECT * FROM `elemen_kompetensi` WHERE `id`='$_POST[iddelelemen]'";
	$result = $conn->query($cekdu);
	if ($result->num_rows != 0){
		$conn->query("DELETE FROM `elemen_kompetensi` WHERE `id`='$_POST[iddelelemen]'");
        echo "<div class='alert alert-danger alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Hapus Data Sukses</h4>
			Anda Telah Berhasil Menghapus Data <b>Elemen Unit Kompetensi Sertifikasi</b></div>";

	}else{
		echo "<script>alert('Maaf Elemen Unit Kompetensi dengan tersebut Tidak Ditemukan');</script>";
	}
}

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
        <small>Input Data</small>
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
					<thead><tr><th>No</th><th>Nama Elemen</th><th>Aksi</th></tr></thead>
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

					    	echo "<td>";
						if ($jumelemen==0){
							echo "<form name='frm' method='POST' role='form' enctype='multipart/form-data'>
							<input type='hidden' name='iddelelemen' value='$pm[id]'><input type='submit' name='hapuselemen' class='btn btn-danger btn-xs' title='Hapus' value='Hapus'></form>";
						}
						echo "</td></tr>";
						$no++;
					}
				echo "</tbody></table>
			
			</div>
		  </div>
		  <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Input Data Elemen Kompetensi Unit Kompetensi $unk[judul]</h3>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>
			
				<form name='frm' method='POST' role='form' enctype='multipart/form-data'>
					<input type='hidden' name='iduk' class='form-control' value='$_GET[iduk]'>
					<div class='col-md-12'>
						<div class='form-group'>
							<label>Nama Elemen Kompetensi Profesi</label>
							<input type='text' name='elemen' class='form-control' required>
						</div>
					</div>
					<div class='col-md-12'>
						<div class='form-group'>
							<input type='submit'  name='tambahelemen' class='btn btn-primary' value='Tambahkan'>
							<a href='?module=unitkompetensi' class='btn btn-default'>Kembali</a>
						</div>
					</div>
				</form>
			
			</div>
		  </div>
          
		</div>
	  </div>
	</section>";
  }
}

// Bagian Kriteria Elemen Kompetensi LSP ==========================================================================================================
elseif ($_GET['module']=='kriteriaelemen'){
  if ($_SESSION['leveluser']=='admin'|| $_SESSION['leveluser']=='user'){
    if( isset( $_REQUEST['tambahkriteria'] ))
	{
	$id_elemenkompetensi=$_POST['ide'];
	$kriteria=$_POST['kriteria'];
	$cekdu="SELECT * FROM `kriteria_unjukkerja` WHERE `kriteria`='$_POST[kriteria]' AND `id_elemenkompetensi`='$_POST[ide]'";
	$result = $conn->query($cekdu);
	if ($result->num_rows == 0){
		$conn->query("INSERT INTO `kriteria_unjukkerja`(`id_elemenkompetensi`, `kriteria`) VALUES ('$id_elemenkompetensi', '$kriteria')");
        echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Input Data Sukses</h4>
			Anda Telah Berhasil Input Data <b>Kriteria Unjuk Kerja Elemen Unit Kompetensi : $elemen</b></div>";

	}else{
		echo "<script>alert('Maaf Kriteria Unjuk Kerja Elemen Unit Kompetensi dengan tersebut Sudah Ada');</script>";
	}
}
if( isset( $_REQUEST['hapuskriteria'] ))
	{
	$cekdu="SELECT * FROM `kriteria_unjukkerja` WHERE `id`='$_POST[iddelkriteria]'";
	$result = $conn->query($cekdu);
	if ($result->num_rows != 0){
		$conn->query("DELETE FROM `kriteria_unjukkerja` WHERE `id`='$_POST[iddelkriteria]'");
        echo "<div class='alert alert-danger alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Hapus Data Sukses</h4>
			Anda Telah Berhasil Menghapus Data <b>Kriteria Unjuk Kerja Elemen Unit Kompetensi Sertifikasi</b></div>";

	}else{
		echo "<script>alert('Maaf Kriteria Unjuk Kerja Elemen Unit Kompetensi dengan tersebut Tidak Ditemukan');</script>";
	}
}
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
					<thead><tr><th>No</th><th>Kriteria Unjuk Kerja</th><th>Aksi</th></tr></thead>
					<tbody>";
					$no=1;
					$sqllsp="SELECT * FROM `kriteria_unjukkerja` WHERE `id_elemenkompetensi`='$_GET[ide]' ORDER BY `id` ASC";
					$lsp=$conn->query($sqllsp);
					while ($pm=$lsp->fetch_assoc()){
						echo "<tr class=gradeX><td>$no</td><td><b>$pm[kriteria]</b></td>";
						echo "<td><form name='frm' method='POST' role='form' enctype='multipart/form-data'>
							<input type='hidden' name='iddelkriteria' value='$pm[id]'><input type='submit' name='hapuskriteria' class='btn btn-danger btn-xs' title='Hapus' value='Hapus'></form></td></tr>";
						$no++;
					}
				echo "</tbody></table>
			
			</div>
		  </div>
		  <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Input Data Kriteria Unjuk Kerja Elemen Kompetensi Unit Kompetensi $elm[elemen_kompetensi]</h3>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>
			
				<form name='frm' method='POST' role='form' enctype='multipart/form-data'>
					<input type='hidden' name='ide' class='form-control' value='$_GET[ide]'>
					<div class='col-md-12'>
						<div class='form-group'>
							<label>Kriteria Unjuk Kerja Elemen Kompetensi Profesi</label>
							<input type='text' name='kriteria' class='form-control' required>
						</div>
					</div>
					<div class='col-md-12'>
						<div class='form-group'>
							<input type='submit'  name='tambahkriteria' class='btn btn-primary' value='Tambahkan'>
							<a href='?module=elemenkompetensi&iduk=$elm[id_unitkompetensi]' class='btn btn-default'>Kembali</a>
						</div>
					</div>
				</form>
			
			</div>
		  </div>
          
		</div>
	  </div>
	</section>";
  }
}

// Bagian Rekening Bank LSP ==========================================================================================================
elseif ($_GET['module']=='rekening'){
  if ($_SESSION['leveluser']=='admin'|| $_SESSION['leveluser']=='user'){
    if( isset( $_REQUEST['tambahkanrek'] ))
	{
	$lsp=$_POST['lsp'];
	$bank=$_POST['bank'];
	$norek=$_POST['norek'];
	$atasnama=$_POST['atasnama'];
	$cekdu="SELECT * FROM `rekeningbayar` WHERE `kode_lsp`='$lsp' AND `bank`='$bank' AND `norek`='$norek' AND `atasnama`='$atasnama'";
	$result = $conn->query($cekdu);
	if ($result->num_rows == 0){

	    switch ($bank){
		default:
			$logo='';
		break;
		case "BRI":
			$logo='bri.png';
		break;
		case "BNI":
			$logo='bni.png';
		break;
		case "Mandiri":
			$logo='mandiri.png';
		break;
		case "BTN":
			$logo='btn.png';
		break;
		case "Bank Jateng":
			$logo='bankjateng.png';
		break;
		case "BCA":
			$logo='bca.png';
		break;
		case "CIMB NIAGA":
			$logo='cimbniaga.png';
		break;

	     }
		$conn->query("INSERT INTO `rekeningbayar`(`kode_lsp`, `bank`, `norek`, `atasnama`, `logo`, `metode`, `aktif`) VALUES ('$lsp', '$bank', '$norek', '$atasnama', '$logo', 'Transfer', 'Y')");
        echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Input Data Sukses</h4>
			Anda Telah Berhasil Input Data <b>Rekening Bank Pembayaran</b></div>";

	}else{
		echo "<script>alert('Maaf Rekening Pembayaran tersebut tersebut Sudah Ada');</script>";
	}
}
if( isset( $_REQUEST['hapusrek'] ))
	{
	$cekdu="SELECT * FROM `rekeningbayar` WHERE `id`='$_POST[iddelbiaya]'";
	$result = $conn->query($cekdu);
	if ($result->num_rows != 0){
		$conn->query("DELETE FROM `rekeningbayar` WHERE `id`='$_POST[iddelbiaya]'");
        echo "<div class='alert alert-danger alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Hapus Data Sukses</h4>
			Anda Telah Berhasil Menghapus Data <b>Rekening Pembayaran</b></div>";

	}else{
		echo "<script>alert('Maaf Rekening Pembayaran tersebut Tidak Ditemukan');</script>";
	}
}
    echo "
    <!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
        Rekening Bank Pembayaran Sertifikasi Profesi
        <small>Input Data</small>
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
					<thead><tr><th>No</th><th>Rekening Bank</th><th>Nama LSP</th><th>Aksi</th></tr></thead>
					<tbody>";
					$no=1;
					$sqlrekbayar="SELECT * FROM `rekeningbayar` ORDER BY `bank` ASC, `norek` ASC";
					$rek=$conn->query($sqlrekbayar);
					while ($pm=$rek->fetch_assoc()){
						$sqllsp="SELECT * FROM `lsp` WHERE `id`='$pm[kode_lsp]'";
						$lsp=$conn->query($sqllsp);
						$ls=$lsp->fetch_assoc();
					    echo "<tr class=gradeX><td>$no</td><td><b>$pm[bank]</b><br>$pm[norek]<br>$pm[atasnama]</td><td>$ls[nama]</td><td><form name='frm' method='POST' role='form' enctype='multipart/form-data'>
							<input type='hidden' name='iddelbiaya' value='$pm[id]'><input type='submit' name='hapusrek' class='btn btn-danger btn-xs' title='Hapus' value='Hapus'></form></td></tr>";
						$no++;
					}
				echo "</tbody></table>
			
			</div>
		  </div>
		  <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Input Rekening Bank Pembayaran Uji Kompetensi Sertifikasi Profesi (LSP)</h3>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>
			
				<form name='frm' method='POST' role='form' enctype='multipart/form-data'>
					<div class='col-md-6'>
						<div class='form-group'>
								<label>Lembaga Sertifikasi Profesi (LSP)</label>
								<select name='lsp' id='lsp' class='form-control' required>";
								echo"<option>Pilih LSP</option>";

								$sqlskkni="SELECT * FROM `lsp` ORDER BY `nama` ASC";
								$skkni=$conn->query($sqlskkni);
								while ($sk=$skkni->fetch_assoc()){
									echo"<option value='$sk[id]'>$sk[nama]</option>";
								}
								echo"</select>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
								<label>Nama Bank</label>
								<select name='bank' id='bank' class='form-control' required>
									<option value='Tunai'>Loket Pembayaran Tunai</option>
									<option value='BRI'>Bank Rakyat Indonesia</option>
									<option value='BNI'>Bank Negara Indonesia</option>
									<option value='Mandiri'>Bank Mandiri</option>
									<option value='BTN'>Bank BTN</option>
									<option value='Bank Jateng'>Bank Jateng</option>
									<option value='BCA'>Bank Central Asia</option>
									<option value='CIMB NIAGA'>Bank CIMB Niaga</option>
								</select>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
								<label>Nomor Rekening</label>
								<input type='text' name='norek' id='norek' class='form-control' required>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
								<label>Nama Pemilik Rekening (Atas Nama)</label>
								<input type='text' name='atasnama' id='atasnama' class='form-control' required>
						</div>
					</div>

					<div class='col-md-12'>
						<div class='form-group'>
							<input type='submit'  name='tambahkanrek' class='btn btn-primary' value='Tambahkan'>
						</div>
					</div>
				</form>
			
			</div>
		  </div>
          
		</div>
	  </div>
	</section>";
  }
}

// Bagian Biaya Uji Kompetensi LSP ==========================================================================================================
elseif ($_GET['module']=='biayauji'){
  if ($_SESSION['leveluser']=='admin'|| $_SESSION['leveluser']=='user'){
    if( isset( $_REQUEST['tambahkanbiaya'] ))
	{
	$lsp=$_POST['lsp'];
	$skkni=$_POST['skkni'];
	$skemakkni=$_POST['skemakkni'];
	$jenisbiaya=$_POST['jenis_biaya'];
	$nominal=$_POST['biaya'];
	$cekdu="SELECT * FROM `biaya_sertifikasi` WHERE `id_lsp`='$lsp' AND `id_skkni`='$skkni' AND `id_skemakkni`='$skemakkni' AND `jenis_biaya`='$jenisbiaya'";
	$result = $conn->query($cekdu);
	if ($result->num_rows == 0){
		$conn->query("INSERT INTO `biaya_sertifikasi`(`id_lsp`, `id_skkni`, `id_skemakkni`, `jenis_biaya`, `nominal`) VALUES ('$lsp', '$skkni', '$skemakkni', '$jenisbiaya', '$nominal')");
        echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Input Data Sukses</h4>
			Anda Telah Berhasil Input Data <b>Biaya untuk Skema Kompetensi $judul ($kode_skema)</b></div>";

	}else{
		echo "<script>alert('Maaf Biaya untuk Skema Kompetensi tersebut Sudah Ada');</script>";
	}
}
if( isset( $_REQUEST['hapusbiaya'] ))
	{
	$cekdu="SELECT * FROM `biaya_sertifikasi` WHERE `id`='$_POST[iddelbiaya]'";
	$result = $conn->query($cekdu);
	if ($result->num_rows != 0){
		$conn->query("DELETE FROM `biaya_sertifikasi` WHERE `id`='$_POST[iddelbiaya]'");
        echo "<div class='alert alert-danger alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Hapus Data Sukses</h4>
			Anda Telah Berhasil Menghapus Data <b>Biaya untuk Skema Kompetensi Sertifikasi</b></div>";

	}else{
		echo "<script>alert('Maaf Biaya untuk Skema Kompetensi tersebut Tidak Ditemukan');</script>";
	}
}
    echo "
    <!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
        Biaya Uji Kompetensi Sertifikasi Profesi
        <small>Input Data</small>
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
					<thead><tr><th>No</th><th>Skema KKNI Sertifikasi</th><th>Jenis Biaya</th><th>Nominal Biaya</th><th>Aksi</th></tr></thead>
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
					    echo "<tr class=gradeX><td>$no</td><td><b>$bs1[nama]</b><h6 class='text-blue'>$bs2[nama]</h6>$ns[judul] ($ns[kode_skema])</td><td>$ns2[jenis_biaya]</td><td>$nominaltampil</td><td><form name='frm' method='POST' role='form' enctype='multipart/form-data'>
							<input type='hidden' name='iddelbiaya' value='$pm[id]'><input type='submit' name='hapusbiaya' class='btn btn-danger btn-xs' title='Hapus' value='Hapus'></form></td></tr>";
						$no++;
					}
				echo "</tbody></table>
			
			</div>
		  </div>
		  <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Input Biaya Uji Kompetensi Sertifikasi Profesi (LSP)</h3>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>
			
				<form name='frm' method='POST' role='form' enctype='multipart/form-data'>
					<div class='col-md-6'>
						<div class='form-group'>
								<label>Lembaga Sertifikasi Profesi (LSP)</label>
								<select name='lsp' id='lsp' class='form-control'>";
								echo"<option>Pilih LSP</option>";

								$sqlskkni="SELECT * FROM `lsp` ORDER BY `nama` ASC";
								$skkni=$conn->query($sqlskkni);
								while ($sk=$skkni->fetch_assoc()){
									echo"<option value='$sk[id]'>$sk[nama]</option>";
								}
								echo"</select>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
								<label>SKK/SKKNI LSP</label>
								<select name='skkni' id='skkni' class='form-control'>
									<option></option>
								</select>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
								<label>Skema KKNI LSP</label>
								<select name='skemakkni' id='skemakkni' class='form-control'>
									<option></option>
								</select>
						</div>
					</div>
					<div class='col-md-3'>
						<div class='form-group'>
								<label>Jenis Biaya</label>
								<select name='jenis_biaya' id='jenis_biaya' class='form-control'>
									<option></option>
								</select>
						</div>
					</div>
					<div class='col-md-3'>
						<div class='form-group'>
							<label>Nominal Biaya</label>
							<div class='input-group'>
								<span class='input-group-addon'>Rp.</span><input type='text' name='biaya' class='form-control' required>
							</div>
							<span class='help-block'>Masukkan hanya angka, contoh: 123000</span>
						</div>
					</div>

					<div class='col-md-12'>
						<div class='form-group'>
							<input type='submit'  name='tambahkanbiaya' class='btn btn-primary' value='Tambahkan'>
						</div>
					</div>
				</form>
			
			</div>
		  </div>
          
		</div>
	  </div>
	</section>";
  }
}
// Bagian Input Syarat Skema LSP ==========================================================================================================
elseif ($_GET['module']=='insyarat'){
  if ($_SESSION['leveluser']=='admin'|| $_SESSION['leveluser']=='user'){
    if( isset( $_REQUEST['tambahkansy'] ))
	{
	$id_skema=$_POST['id_skema'];
	$syarat=$_POST['persyaratan'];
	$cekdu="SELECT * FROM `skema_persyaratan` WHERE `persyaratan`='$syarat' AND `id_skemakkni`='$id_skema'";
	$result = $conn->query($cekdu);
	if ($result->num_rows == 0){
		$conn->query("INSERT INTO `skema_persyaratan`(`persyaratan`, `id_skemakkni`) VALUES ('$syarat', '$id_skema')");
        echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Input Data Sukses</h4>
			Anda Telah Berhasil Input Data <b>Persyaratan Skema $syarat</b></div>";

	}else{
		echo "<script>alert('Maaf Persyaratan Skema tersebut Sudah Ada');</script>";
	}
}
if( isset( $_REQUEST['hapussy'] ))
	{
	$cekdu="SELECT * FROM `skema_persyaratan` WHERE `id`='$_POST[iddelsy]'";
	$result = $conn->query($cekdu);
	if ($result->num_rows != 0){
		$conn->query("DELETE FROM `skema_persyaratan` WHERE `id`='$_POST[iddelsy]'");
        echo "<div class='alert alert-danger alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Hapus Data Sukses</h4>
			Anda Telah Berhasil Menghapus Data <b>Persyaratan Skema Sertifikasi</b></div>";

	}else{
		echo "<script>alert('Maaf Persyaratan Skema dengan Kode tersebut Tidak Ditemukan');</script>";
	}
}
	$sqlgetskema="SELECT * FROM `skema_kkni` WHERE `id`='$_GET[id]'";
	$getskema=$conn->query($sqlgetskema);
	$gs=$getskema->fetch_assoc();
	echo "
    <!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
        Persyaratan Uji Kompetensi
        <small>Input Data</small>
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
					<thead><tr><th>No</th><th>Persyaratan</th><th>Aksi</th></tr></thead>
					<tbody>";
					$no=1;
					$sqllsp="SELECT * FROM `skema_persyaratan` WHERE `id_skemakkni`='$gs[id]' ORDER BY `id` ASC";
					$lsp=$conn->query($sqllsp);
					while ($pm=$lsp->fetch_assoc()){
						echo "<tr class=gradeX><td>$no</td>";
						echo "</td><td>$pm[persyaratan]</td>";
					    echo "<td><form name='frm' method='POST' role='form' enctype='multipart/form-data'>
							<input type='hidden' name='iddelsy' value='$pm[id]'><input type='submit' name='hapussy' class='btn btn-danger btn-xs' title='Hapus' value='Hapus'></form></td></tr>";
						$no++;
					}
				echo "</tbody></table>
			
			</div>
		  </div>
		  <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Input Data Persyaratan Skema Sertifikasi Profesi: $gs[judul]</h3>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>
			
				<form name='frm' method='POST' role='form' enctype='multipart/form-data'>
					
					<div class='col-md-12'>
						<div class='form-group'>
							<label>Nama Skema Profesi</label>
							<input type='text' name='judul_skema' class='form-control' value='$gs[judul]' disabled>
							<input type='hidden' name='id_skema' class='form-control' value='$_GET[id]'>
						</div>
					</div>
					<div class='col-md-12'>
						<div class='form-group'>
							<label>Persyaratan</label>
							<textarea name='persyaratan' class='form-control' required></textarea>
						</div>
					</div>
					<div class='col-md-12'>
						<div class='form-group'>
							<input type='submit'  name='tambahkansy' class='btn btn-primary' value='Tambahkan'>
						</div>
					</div>
				</form>
			
			</div>
		  </div>
          
		</div>
	  </div>
	</section>";
  }
}

// Bagian Asesi LSP ==========================================================================================================
elseif ($_GET['module']=='asesi'){
  if ($_SESSION['leveluser']=='admin'|| $_SESSION['leveluser']=='user'){
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
			<a href='media.php?module=importasesilama' class='btn btn-success pull-right'>
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
					<thead><tr><th>No</th><th>Tahun Angkatan</th><th>Jumlah Asesi</th><th>Aksi</th></tr></thead>
					<tbody>";
					$no=1;
					$sqlwilasesi0="SELECT * FROM `asesi` ORDER BY `tgl_daftar` ASC";
					$wilasesi0=$conn->query($sqlwilasesi0);
					while ($was0=$wilasesi0->fetch_assoc()){
						if ($was0['tgl_daftar']=='0000-00-00' OR $was0['tgl_daftar']=='NULL'){
							$angkatan=substr($was0['no_pendaftaran'],0,4);
							$thndaftarnya=substr($was0['no_pendaftaran'],0,4);
							$blndaftarnya=substr($was0['no_pendaftaran'],4,2);
							$tgldaftarnya=substr($was0['no_pendaftaran'],6,2);
							$tgl_daftarx=$thndaftarnya."-".$blndaftarnya."-".$tgldaftarnya;
							$conn->query("UPDATE `asesi` SET `tgl_daftar`='$tgl_daftarx',`angkatan`='$angkatan' WHERE `id`='$was0[id]'");

						}else{
							$angkatan=substr($was0['tgl_daftar'],0,4);
							$thndaftarnya=substr($was0['tgl_daftar'],0,4);
							$blndaftarnya=substr($was0['tgl_daftar'],4,2);
							$tgldaftarnya=substr($was0['tgl_daftar'],6,2);
							$tgl_daftarx=$thndaftarnya."-".$blndaftarnya."-".$tgldaftarnya;
							$conn->query("UPDATE `asesi` SET `angkatan`='$angkatan' WHERE `id`='$was0[id]'");

						}
					}
					$sqlwilasesi="SELECT DISTINCT `angkatan` FROM `asesi` ORDER BY `angkatan` DESC";
					$wilasesi=$conn->query($sqlwilasesi);
					while ($was=$wilasesi->fetch_assoc()){
						$sqljumaswil="SELECT * FROM `asesi` WHERE `angkatan`='$was[angkatan]'";
						$jumaswil=$conn->query($sqljumaswil);
						$jumas=$jumaswil->num_rows;
						echo "<tr class=gradeX><td>$no</td><td>$was[angkatan]</td><td>$jumas</td><td><a href='?module=asesibytahun&tahun=$was[angkatan]' class='btn btn-primary btn-xs'>Detail</a></td></tr>";
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
					$sqlwilasesi="SELECT DISTINCT `propinsi` FROM `asesi` ORDER BY `propinsi` ASC";
					$wilasesi=$conn->query($sqlwilasesi);
					while ($was=$wilasesi->fetch_assoc()){
						$sqljumaswil="SELECT * FROM `asesi` WHERE `propinsi`='$was[propinsi]'";
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
					$sqlwilasesi2="SELECT DISTINCT `kota` FROM `asesi` ORDER BY `kota` ASC";
					$wilasesi2=$conn->query($sqlwilasesi2);
					while ($was2=$wilasesi2->fetch_assoc()){
						$sqljumaswil2="SELECT * FROM `asesi` WHERE `kota`='$was2[kota]'";
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
						$sqlgetasesi="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$pm[id_asesi]'";
						$getasesi=$conn->query($sqlgetasesi);
						$pma=$getasesi->fetch_assoc();
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
							if ($ns['no_lisensi']=='' && $ns['no_serisertifikat']==''){
								echo "Sertifikat : <span class='text-red'><b>Belum Dicetak</b></span>&nbsp;";
								echo "<a href='#myModalK".$ns['id']."' class='btn btn-primary btn-xs' data-toggle='modal' data-id='".$ns['id']."' title='Perbarui dan unggah scan dokumen sertifikat Asesi'>Perbarui dan Unggah Sertifikat</a><br>";

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
						$sqlgetasesi="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$pm[id_asesi]'";
						$getasesi=$conn->query($sqlgetasesi);
						$pma=$getasesi->fetch_assoc();
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
				echo "</tbody></table>
			</div>
              </div>
              <!-- /.tab-pane -->
              <div class='tab-pane' id='BELUM-VERIFIKASI'>
			<div style='overflow-x:auto;'>
				<table id='example1' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Identitas Asesi</th><th>Asesmen Skema Sertifikasi</th><th>Aksi</th></tr></thead>
					<tbody>";
					$no=1;
					$sqllsp="SELECT * FROM `asesi` WHERE `verifikasi`='P' AND `blokir`='N' ORDER BY `nama` ASC";
					$lsp=$conn->query($sqllsp);
					while ($pm=$lsp->fetch_assoc()){
						$sqlgetskkni=$conn->query("SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$pm[no_pendaftaran]' ORDER BY `id` DESC");
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
					$sqllsp="SELECT * FROM `asesi` WHERE `verifikasi`='V' AND `blokir`='N' ORDER BY `nama` ASC";
					$lsp=$conn->query($sqllsp);
					while ($pm=$lsp->fetch_assoc()){
						$sqlgetskkni=$conn->query("SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$pm[no_pendaftaran]' ORDER BY `id` DESC");
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
					$sqllsp="SELECT * FROM `asesi` WHERE `blokir`='Y' ORDER BY `nama` ASC";
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
  if ($_SESSION['leveluser']=='admin'|| $_SESSION['leveluser']=='user'){
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
			<a href='media.php?module=importasesi' class='btn btn-success pull-right'>
				<span class='fa fa-upload'></span> Import Data Asesi
			</a>

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
					$sqllsp="SELECT * FROM `asesi` WHERE `verifikasi`='P' AND `blokir`='N' ORDER BY `nama` ASC";
					$lsp=$conn->query($sqllsp);
					while ($pm=$lsp->fetch_assoc()){
						$sqlgetskkni=$conn->query("SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$pm[no_pendaftaran]' ORDER BY `id` DESC");
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
					    echo "<td>
							<form name='frm' method='POST' role='form' enctype='multipart/form-data'>
							<input type='hidden' name='idblokir' value='$pm[id]'><input type='submit' name='blokir' class='btn btn-warning btn-xs btn-block' title='Blokir akses $pm[nama]' value='Blokir'></form>
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
			</div>
		  </div>
          
		</div>
	  </div>
	</section>";
  }
}

// Bagian Asesi LSP ==========================================================================================================
elseif ($_GET['module']=='asesithak'){
  if ($_SESSION['leveluser']=='admin'|| $_SESSION['leveluser']=='user'){
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
					<thead><tr><th>No</th><th>Tahun Angkatan</th><th>Daftar</th><th>Asesmen</th><th>Kompeten</th><th>Belum Kompeten</th><th>Sedang Proses</th></tr></thead>
					<tbody>";
					$no=1;
					$sqlwilasesi0="SELECT * FROM `asesi` ORDER BY `tgl_daftar` ASC";
					$wilasesi0=$conn->query($sqlwilasesi0);
					while ($was0=$wilasesi0->fetch_assoc()){
						if ($was0['tgl_daftar']=='0000-00-00'){
							$angkatan=substr($was0['no_pendaftaran'],0,4);
							$thndaftarnya=substr($was0['no_pendaftaran'],0,4);
							$blndaftarnya=substr($was0['no_pendaftaran'],4,2);
							$tgldaftarnya=substr($was0['no_pendaftaran'],6,2);
							$tgl_daftarx=$thndaftarnya."-".$blndaftarnya."-".$tgldaftarnya;
						}else{
							$angkatan=substr($was0['tgl_daftar'],0,4);
							$thndaftarnya=substr($was0['tgl_daftar'],0,4);
							$blndaftarnya=substr($was0['tgl_daftar'],4,2);
							$tgldaftarnya=substr($was0['tgl_daftar'],6,2);
							$tgl_daftarx=$thndaftarnya."-".$blndaftarnya."-".$tgldaftarnya;
						}
						$conn->query("UPDATE `asesi` SET `tgl_daftar`='$tgl_daftarx',`angkatan`='$angkatan' WHERE `id`='$was0[id]'");
					}
					$sqlwilasesi="SELECT DISTINCT `angkatan` FROM `asesi` ORDER BY `angkatan` DESC";
					$wilasesi=$conn->query($sqlwilasesi);
					while ($was=$wilasesi->fetch_assoc()){
						$sqljumaswil="SELECT DISTINCT `no_pendaftaran` FROM `asesi` WHERE `angkatan`='$was[angkatan]'";
						$jumaswil=$conn->query($sqljumaswil);
						$jumas=$jumaswil->num_rows;

						$sqlasesmen="SELECT DISTINCT `id_asesi` FROM `asesi_asesmen` WHERE `tgl_asesmen` LIKE '$was[angkatan]%'";
						$asesmen=$conn->query($sqlasesmen);
						$jumasesmen=$asesmen->num_rows;

						$sqlasesmen2="SELECT DISTINCT `id_asesi` FROM `asesi_asesmen` WHERE `tgl_asesmen` LIKE '$was[angkatan]%' AND `status_asesmen`='K'";
						$asesmen2=$conn->query($sqlasesmen2);
						$jumasesmen2=$asesmen2->num_rows;

						$sqlasesmen3="SELECT DISTINCT `id_asesi` FROM `asesi_asesmen` WHERE `tgl_asesmen` LIKE '$was[angkatan]%' AND `status_asesmen`='BK'";
						$asesmen3=$conn->query($sqlasesmen3);
						$jumasesmen3=$asesmen3->num_rows;

						$sqlasesmen4="SELECT DISTINCT `id_asesi` FROM `asesi_asesmen` WHERE `tgl_asesmen` LIKE '$was[angkatan]%' AND `status_asesmen`='P'";
						$asesmen4=$conn->query($sqlasesmen4);
						$jumasesmen4=$asesmen4->num_rows;

						if ($jumasesmen>$jumas){
							$ketjumas="<font color='red'>".$jumasesmen." (tidak sinkron)</font> <a href='media.php?module=asesmen' class='btn btn-primary btn-xs'><span class='glyphicon glyphicon-zoom-in' aria-hidden='true' title='Lihat Data'></span></a>";
						}else{
							$ketjumas=$jumasesmen;
						}
						echo "<tr class=gradeX><td>$no</td><td>$was[angkatan]</td><td>$jumas</td><td>$ketjumas</td><td>$jumasesmen2</td><td>$jumasesmen3</td><td>$jumasesmen4</td></tr>";
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
  if ($_SESSION['leveluser']=='admin'|| $_SESSION['leveluser']=='user'){
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
					$sqlwilasesi="SELECT DISTINCT `propinsi` FROM `asesi` ORDER BY `propinsi` ASC";
					$wilasesi=$conn->query($sqlwilasesi);
					while ($was=$wilasesi->fetch_assoc()){
						$sqljumaswil="SELECT * FROM `asesi` WHERE `propinsi`='$was[propinsi]'";
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
  if ($_SESSION['leveluser']=='admin'|| $_SESSION['leveluser']=='user'){
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
					$sqlwilasesi2="SELECT DISTINCT `kota` FROM `asesi` ORDER BY `kota` ASC";
					$wilasesi2=$conn->query($sqlwilasesi2);
					while ($was2=$wilasesi2->fetch_assoc()){
						$sqljumaswil2="SELECT * FROM `asesi` WHERE `kota`='$was2[kota]'";
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
  if ($_SESSION['leveluser']=='admin'|| $_SESSION['leveluser']=='user'){
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
						$sqlgetasesi="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$pm[id_asesi]'";
						$getasesi=$conn->query($sqlgetasesi);
						$pma=$getasesi->fetch_assoc();
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
							if ($ns['no_lisensi']=='' && $ns['no_serisertifikat']==''){
								echo "Sertifikat : <span class='text-red'><b>Belum Dicetak</b></span>&nbsp;";
								echo "<a href='#myModalK".$ns['id']."' class='btn btn-primary btn-xs' data-toggle='modal' data-id='".$ns['id']."' title='Perbarui dan unggah scan dokumen sertifikat Asesi'>Perbarui dan Unggah Sertifikat</a><br>";

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
  if ($_SESSION['leveluser']=='admin'|| $_SESSION['leveluser']=='user'){
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
						$sqlgetasesi="SELECT * FROM `asesi` WHERE `no_pendaftaran`='$pm[id_asesi]'";
						$getasesi=$conn->query($sqlgetasesi);
						$pma=$getasesi->fetch_assoc();
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
  if ($_SESSION['leveluser']=='admin'|| $_SESSION['leveluser']=='user'){
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
				<table id='example1' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Identitas Asesi</th><th>Asesmen Skema Sertifikasi</th><th>Aksi</th></tr></thead>
					<tbody>";
					$no=1;
					$sqllsp="SELECT * FROM `asesi` WHERE `verifikasi`='P' AND `blokir`='N' ORDER BY `nama` ASC";
					$lsp=$conn->query($sqllsp);
					while ($pm=$lsp->fetch_assoc()){
						$sqlgetskkni=$conn->query("SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$pm[no_pendaftaran]' ORDER BY `id` DESC");
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
  if ($_SESSION['leveluser']=='admin'|| $_SESSION['leveluser']=='user'){
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
					$sqllsp="SELECT * FROM `asesi` WHERE `verifikasi`='V' AND `blokir`='N' ORDER BY `nama` ASC";
					$lsp=$conn->query($sqllsp);
					while ($pm=$lsp->fetch_assoc()){
						$sqlgetskkni=$conn->query("SELECT * FROM `asesi_asesmen` WHERE `id_asesi`='$pm[no_pendaftaran]' ORDER BY `id` DESC");
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
  if ($_SESSION['leveluser']=='admin'|| $_SESSION['leveluser']=='user'){
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
					$sqllsp="SELECT * FROM `asesi` WHERE `blokir`='Y' ORDER BY `nama` ASC";
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
		  </div>
          
		</div>
	  </div>
	</section>";
  }
}

// Bagian Asesmen LSP Basis Data ==========================================================================================================
elseif ($_GET['module']=='asesmen'){
  if ($_SESSION['leveluser']=='admin'|| $_SESSION['leveluser']=='user'){
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
  if ($_SESSION['leveluser']=='admin'|| $_SESSION['leveluser']=='user'){
    if( isset( $_REQUEST['hapusasesor'] ))
	{
	$cekdu="SELECT * FROM `asesor` WHERE `id`='$_POST[iddelasesor]'";
	$result = $conn->query($cekdu);
	if ($result->num_rows != 0){
		$conn->query("DELETE FROM `asesor` WHERE `id`='$_POST[iddelasesor]'");
        echo "<div class='alert alert-danger alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Hapus Data Sukses</h4>
			Anda Telah Berhasil Menghapus Data <b>Asesor Kompetensi Sertifikasi</b></div>";

	}else{
		echo "<script>alert('Maaf Asesor tersebut Tidak Ditemukan');</script>";
	}
    }
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
		  <!-- Custom Tabs -->
          <div class='nav-tabs-custom'>
            <ul class='nav nav-tabs'>
              <li class='active'><a href='#tab_1' data-toggle='tab'>Semua</a></li>
              <li><a href='#tab_2' data-toggle='tab'>Lisensi Aktif</a></li>
              <li><a href='#tab_3' data-toggle='tab'>Lisensi Segera Kadaluarsa</a></li>
              <li><a href='#tab_4' data-toggle='tab'>Lisensi Telah Kadaluarsa</a></li>
              <li><a href='#tab_5' data-toggle='tab'>Calon Asesor</a></li>
            </ul>
            <div class='tab-content'>
              <div class='tab-pane active' id='tab_1'>
			<div style='overflow-x:auto;'>
				<table id='example1' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Identitas Asesor</th><th>Portofolio Asesmen Skema Sertifikasi</th><th>Aksi</th></tr></thead>
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

						echo "<tr class=gradeX><td>$no</td><td>
						  <div class='box box-widget widget-user-2'>
							<!-- Add the bg color to the header using any of the bg-* classes -->";
							if ($days_between<180){
								if ($days_between<0){
									echo "<div class='widget-user-header bg-red'>";
								}else{
									echo "<div class='widget-user-header bg-yellow'>";
								}
							}else{
								echo "<div class='widget-user-header bg-green'>";
							}

							  echo "<div class='widget-user-image'>";
								if ($pm['foto']==''){
									echo "<img class='profile-user-img img-responsive img-circle' src='../images/default.jpg' alt='User Avatar'>";
								}else{
									echo "<img class='profile-user-img img-responsive img-circle' src='../foto_asesor/$pm[foto]' alt='User Avatar'>";
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
							$sqlpendidikan="SELECT * FROM `pendidikan` WHERE `id`='$pm[pendidikan_terakhir]'";
							$pendidikan=$conn->query($sqlpendidikan);
							$pe=$pendidikan->fetch_assoc();
							
							echo "</div>
							<!-- /.widget-user-image -->
							<h3 class='widget-user-username'>$namaasesor</h3>
							<h5 class='widget-user-desc'>No. Register : $pm[no_induk]</h5>";
							echo "</div>
							<div class='box-footer'>
							  <ul class='nav nav-stacked'>";
								if ($days_between<180){
									if ($days_between<0){
										echo "<li>Masa Berlaku Lisensi<span class='pull-right badge'>$masaberlakuasesor (Kadaluarsa $days_between2 hari)</span></li>";
									}else{
										echo "<li>Masa Berlaku Lisensi<span class='pull-right badge'>$masaberlakuasesor (Tersisa $days_between hari)</span></li>";
									}
								}else{
									echo "<li>Masa Berlaku Lisensi<span class='pull-right badge'>$masaberlakuasesor (Tersisa $days_between hari)</span></li>";
								}
								echo "<li>Total Portfolio Asesmen<span class='pull-right badge'>$ikutasesmen Skema</span></li>
								<li>Jumlah Portfolio Asesmen Tahun $tahunskr<span class='pull-right badge'>$ikutasesmen2 Skema</span></li>
								<li>Dokumen Pokok <span class='pull-right badge'>$kelengkapan</span></li>

							  </ul>
							</div>
						  </div>
						  <!-- /.widget-user --></td>";
						
			
						echo "</td><td>";
						$sqlgetskkniasesor=$conn->query("SELECT DISTINCT `id_skemakkni` FROM `jadwal_asesmen` WHERE `id_asesor`='$pm[id]'");
						while ($ns=$sqlgetskkniasesor->fetch_assoc()){
							$sqlgetskkni2=$conn->query("SELECT * FROM `skema_kkni` WHERE `id`='$ns[id_skemakkni]'");
							$ns2=$sqlgetskkni2->fetch_assoc();
							echo "<b>$ns2[judul]</b> ($ns2[kode_skema])<br>";
						}
						echo "</td><td>";
						if ($ikutasesmen==0){
					    	echo "<form name='frm' method='POST' role='form' enctype='multipart/form-data'>
							<input type='hidden' name='iddelasesor' value='$pm[id]'><input type='submit' name='hapusasesor' class='btn btn-danger btn-xs' title='Hapus' value='Hapus'></form>
							<a href='?module=updateasesor&id=$pm[id]' class='btn btn-primary btn-xs'>Perbarui</a></td>";

						}else{
							echo"<a href='?module=updateasesor&id=$pm[id]' class='btn btn-primary btn-xs'>Perbarui</a>";
						}
						echo "</tr>";
						$no++;
					}
				echo "</tbody></table>
			</div>
              </div>
              <!-- /.tab-pane -->
              <div class='tab-pane' id='tab_2'>
			<div style='overflow-x:auto;'>
				<table id='example3' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Identitas Asesor</th><th>Portofolio Asesmen Skema Sertifikasi</th><th>Aksi</th></tr></thead>
					<tbody>";
					$no=1;
					$sqllsp="SELECT * FROM `asesor` WHERE `masaberlaku_lisensi` > '$tanggalini' ORDER BY `nama` ASC";
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

						echo "<tr class=gradeX><td>$no</td><td>
						  <div class='box box-widget widget-user-2'>
							<!-- Add the bg color to the header using any of the bg-* classes -->";
							if ($days_between<180){
								if ($days_between<0){
									echo "<div class='widget-user-header bg-red'>";
								}else{
									echo "<div class='widget-user-header bg-yellow'>";
								}
							}else{
								echo "<div class='widget-user-header bg-green'>";
							}

							  echo "<div class='widget-user-image'>";
								if ($pm['foto']==''){
									echo "<img class='profile-user-img img-responsive img-circle' src='../images/default.jpg' alt='User Avatar'>";
								}else{
									echo "<img class='profile-user-img img-responsive img-circle' src='../foto_asesor/$pm[foto]' alt='User Avatar'>";
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
							$sqlpendidikan="SELECT * FROM `pendidikan` WHERE `id`='$pm[pendidikan_terakhir]'";
							$pendidikan=$conn->query($sqlpendidikan);
							$pe=$pendidikan->fetch_assoc();
							
							echo "</div>
							<!-- /.widget-user-image -->
							<h3 class='widget-user-username'>$namaasesor</h3>
							<h5 class='widget-user-desc'>No. Register : $pm[no_induk]</h5>";
							echo "</div>
							<div class='box-footer'>
							  <ul class='nav nav-stacked'>";
								if ($days_between<180){
									if ($days_between<0){
										echo "<li>Masa Berlaku Lisensi<span class='pull-right badge'>$masaberlakuasesor (Kadaluarsa $days_between2 hari)</span></li>";
									}else{
										echo "<li>Masa Berlaku Lisensi<span class='pull-right badge'>$masaberlakuasesor (Tersisa $days_between hari)</span></li>";
									}
								}else{
									echo "<li>Masa Berlaku Lisensi<span class='pull-right badge'>$masaberlakuasesor (Tersisa $days_between hari)</span></li>";
								}
								echo "<li>Total Portfolio Asesmen<span class='pull-right badge'>$ikutasesmen Skema</span></li>
								<li>Jumlah Portfolio Asesmen Tahun $tahunskr<span class='pull-right badge'>$ikutasesmen2 Skema</span></li>
								<li>Dokumen Pokok <span class='pull-right badge'>$kelengkapan</span></li>

							  </ul>
							</div>
						  </div>
						  <!-- /.widget-user --></td>";
						
			
						echo "</td><td>";
						$sqlgetskkniasesor=$conn->query("SELECT DISTINCT `id_skemakkni` FROM `jadwal_asesmen` WHERE `id_asesor`='$pm[id]'");
						while ($ns=$sqlgetskkniasesor->fetch_assoc()){
							$sqlgetskkni2=$conn->query("SELECT * FROM `skema_kkni` WHERE `id`='$ns[id_skemakkni]'");
							$ns2=$sqlgetskkni2->fetch_assoc();
							echo "<b>$ns2[judul]</b> ($ns2[kode_skema])<br>";
						}
						echo "</td><td>";
						if ($ikutasesmen==0){
					    	echo "<form name='frm' method='POST' role='form' enctype='multipart/form-data'>
							<input type='hidden' name='iddelasesor' value='$pm[id]'><input type='submit' name='hapusasesor' class='btn btn-danger btn-xs' title='Hapus' value='Hapus'></form>
							</td>";
						}else{
							echo"<a href='?module=updateasesor&id=$pm[id]' class='btn btn-primary btn-xs'>Perbarui</a>";
						}
						echo "</tr>";
						$no++;
					}
				echo "</tbody></table>
			</div>
              </div>
              <!-- /.tab-pane -->
              <div class='tab-pane' id='tab_3'>
			<div style='overflow-x:auto;'>
				<table id='example4' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Identitas Asesor</th><th>Portofolio Asesmen Skema Sertifikasi</th><th>Aksi</th></tr></thead>
					<tbody>";
					$no=1;
					$sqllsp="SELECT * FROM `asesor` WHERE `masaberlaku_lisensi` < '$tanggalkadaluarsa' OR `masaberlaku_lisensi` = '$tanggalkadaluarsa' ORDER BY `nama` ASC";
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

						echo "<tr class=gradeX><td>$no</td><td>
						  <div class='box box-widget widget-user-2'>
							<!-- Add the bg color to the header using any of the bg-* classes -->";
							if ($days_between<180){
								if ($days_between<0){
									echo "<div class='widget-user-header bg-red'>";
								}else{
									echo "<div class='widget-user-header bg-yellow'>";
								}
							}else{
								echo "<div class='widget-user-header bg-green'>";
							}

							  echo "<div class='widget-user-image'>";
								if ($pm['foto']==''){
									echo "<img class='profile-user-img img-responsive img-circle' src='../images/default.jpg' alt='User Avatar'>";
								}else{
									echo "<img class='profile-user-img img-responsive img-circle' src='../foto_asesor/$pm[foto]' alt='User Avatar'>";
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
							$sqlpendidikan="SELECT * FROM `pendidikan` WHERE `id`='$pm[pendidikan_terakhir]'";
							$pendidikan=$conn->query($sqlpendidikan);
							$pe=$pendidikan->fetch_assoc();
							
							echo "</div>
							<!-- /.widget-user-image -->
							<h3 class='widget-user-username'>$namaasesor</h3>
							<h5 class='widget-user-desc'>No. Register : $pm[no_induk]</h5>";
							echo "</div>
							<div class='box-footer'>
							  <ul class='nav nav-stacked'>";
								if ($days_between<180){
									if ($days_between<0){
										echo "<li>Masa Berlaku Lisensi<span class='pull-right badge'>$masaberlakuasesor (Kadaluarsa $days_between2 hari)</span></li>";
									}else{
										echo "<li>Masa Berlaku Lisensi<span class='pull-right badge'>$masaberlakuasesor (Tersisa $days_between hari)</span></li>";
									}
								}else{
									echo "<li>Masa Berlaku Lisensi<span class='pull-right badge'>$masaberlakuasesor (Tersisa $days_between hari)</span></li>";
								}
								echo "<li>Total Portfolio Asesmen<span class='pull-right badge'>$ikutasesmen Skema</span></li>
								<li>Jumlah Portfolio Asesmen Tahun $tahunskr<span class='pull-right badge'>$ikutasesmen2 Skema</span></li>
								<li>Dokumen Pokok <span class='pull-right badge'>$kelengkapan</span></li>

							  </ul>
							</div>
						  </div>
						  <!-- /.widget-user --></td>";
						
			
						echo "</td><td>";
						$sqlgetskkniasesor=$conn->query("SELECT DISTINCT `id_skemakkni` FROM `jadwal_asesmen` WHERE `id_asesor`='$pm[id]'");
						while ($ns=$sqlgetskkniasesor->fetch_assoc()){
							$sqlgetskkni2=$conn->query("SELECT * FROM `skema_kkni` WHERE `id`='$ns[id_skemakkni]'");
							$ns2=$sqlgetskkni2->fetch_assoc();
							echo "<b>$ns2[judul]</b> ($ns2[kode_skema])<br>";
						}
						echo "</td><td>";
						if ($ikutasesmen==0){
					    	echo "<form name='frm' method='POST' role='form' enctype='multipart/form-data'>
							<input type='hidden' name='iddelasesor' value='$pm[id]'><input type='submit' name='hapusasesor' class='btn btn-danger btn-xs' title='Hapus' value='Hapus'></form>
							</td>";
						}else{
							echo"<a href='?module=updateasesor&id=$pm[id]' class='btn btn-primary btn-xs'>Perbarui</a>";
						}
						echo "</tr>";
						$no++;
					}
				echo "</tbody></table>
			</div>
              </div>
              <!-- /.tab-pane -->
              <div class='tab-pane' id='tab_4'>
			<div style='overflow-x:auto;'>
				<table id='example5' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Identitas Asesor</th><th>Portofolio Asesmen Skema Sertifikasi</th><th>Aksi</th></tr></thead>
					<tbody>";
					$no=1;
					$sqllsp="SELECT * FROM `asesor` WHERE `masaberlaku_lisensi` < '$tanggalini' OR `masaberlaku_lisensi` = '$tanggalini' ORDER BY `nama` ASC";
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

						echo "<tr class=gradeX><td>$no</td><td>
						  <div class='box box-widget widget-user-2'>
							<!-- Add the bg color to the header using any of the bg-* classes -->";
							if ($days_between<180){
								if ($days_between<0){
									echo "<div class='widget-user-header bg-red'>";
								}else{
									echo "<div class='widget-user-header bg-yellow'>";
								}
							}else{
								echo "<div class='widget-user-header bg-green'>";
							}

							  echo "<div class='widget-user-image'>";
								if ($pm['foto']==''){
									echo "<img class='profile-user-img img-responsive img-circle' src='../images/default.jpg' alt='User Avatar'>";
								}else{
									echo "<img class='profile-user-img img-responsive img-circle' src='../foto_asesor/$pm[foto]' alt='User Avatar'>";
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
							$sqlpendidikan="SELECT * FROM `pendidikan` WHERE `id`='$pm[pendidikan_terakhir]'";
							$pendidikan=$conn->query($sqlpendidikan);
							$pe=$pendidikan->fetch_assoc();
							
							echo "</div>
							<!-- /.widget-user-image -->
							<h3 class='widget-user-username'>$namaasesor</h3>
							<h5 class='widget-user-desc'>No. Register : $pm[no_induk]</h5>";
							echo "</div>
							<div class='box-footer'>
							  <ul class='nav nav-stacked'>";
								if ($days_between<180){
									if ($days_between<0){
										echo "<li>Masa Berlaku Lisensi<span class='pull-right badge'>$masaberlakuasesor (Kadaluarsa $days_between2 hari)</span></li>";
									}else{
										echo "<li>Masa Berlaku Lisensi<span class='pull-right badge'>$masaberlakuasesor (Tersisa $days_between hari)</span></li>";
									}
								}else{
									echo "<li>Masa Berlaku Lisensi<span class='pull-right badge'>$masaberlakuasesor (Tersisa $days_between hari)</span></li>";
								}
								echo "<li>Total Portfolio Asesmen<span class='pull-right badge'>$ikutasesmen Skema</span></li>
								<li>Jumlah Portfolio Asesmen Tahun $tahunskr<span class='pull-right badge'>$ikutasesmen2 Skema</span></li>
								<li>Dokumen Pokok <span class='pull-right badge'>$kelengkapan</span></li>

							  </ul>
							</div>
						  </div>
						  <!-- /.widget-user --></td>";
						
			
						echo "</td><td>";
						$sqlgetskkniasesor=$conn->query("SELECT DISTINCT `id_skemakkni` FROM `jadwal_asesmen` WHERE `id_asesor`='$pm[id]'");
						while ($ns=$sqlgetskkniasesor->fetch_assoc()){
							$sqlgetskkni2=$conn->query("SELECT * FROM `skema_kkni` WHERE `id`='$ns[id_skemakkni]'");
							$ns2=$sqlgetskkni2->fetch_assoc();
							echo "<b>$ns2[judul]</b> ($ns2[kode_skema])<br>";
						}
						echo "</td><td>";
						if ($ikutasesmen==0){
					    	echo "<form name='frm' method='POST' role='form' enctype='multipart/form-data'>
							<input type='hidden' name='iddelasesor' value='$pm[id]'><input type='submit' name='hapusasesor' class='btn btn-danger btn-xs' title='Hapus' value='Hapus'></form>
							</td>";
						}else{
							echo"<a href='?module=updateasesor&id=$pm[id]' class='btn btn-primary btn-xs'>Perbarui</a>";
						}
						echo "</tr>";
						$no++;
					}
				echo "</tbody></table>
			</div>
              </div>
              <!-- /.tab-pane -->
              <div class='tab-pane' id='tab_5'>
			<div style='overflow-x:auto;'>
				<table id='example6' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Identitas Asesor</th><th>Portofolio Asesmen Skema Sertifikasi</th><th>Aksi</th></tr></thead>
					<tbody>";
					$no=1;
					$sqllsp="SELECT * FROM `asesor` WHERE `no_lisensi` = '' ORDER BY `nama` ASC";
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

						echo "<tr class=gradeX><td>$no</td><td>
						  <div class='box box-widget widget-user-2'>
							<!-- Add the bg color to the header using any of the bg-* classes -->";
							if ($days_between<180){
								if ($days_between<0){
									echo "<div class='widget-user-header bg-red'>";
								}else{
									echo "<div class='widget-user-header bg-yellow'>";
								}
							}else{
								echo "<div class='widget-user-header bg-green'>";
							}

							  echo "<div class='widget-user-image'>";
								if ($pm['foto']==''){
									echo "<img class='profile-user-img img-responsive img-circle' src='../images/default.jpg' alt='User Avatar'>";
								}else{
									echo "<img class='profile-user-img img-responsive img-circle' src='../foto_asesor/$pm[foto]' alt='User Avatar'>";
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
							$sqlpendidikan="SELECT * FROM `pendidikan` WHERE `id`='$pm[pendidikan_terakhir]'";
							$pendidikan=$conn->query($sqlpendidikan);
							$pe=$pendidikan->fetch_assoc();
							
							echo "</div>
							<!-- /.widget-user-image -->
							<h3 class='widget-user-username'>$namaasesor</h3>
							<h5 class='widget-user-desc'>No. Register : $pm[no_induk]</h5>";
							echo "</div>
							<div class='box-footer'>
							  <ul class='nav nav-stacked'>";
								if ($days_between<180){
									if ($days_between<0){
										echo "<li>Masa Berlaku Lisensi<span class='pull-right badge'>$masaberlakuasesor (Kadaluarsa $days_between2 hari)</span></li>";
									}else{
										echo "<li>Masa Berlaku Lisensi<span class='pull-right badge'>$masaberlakuasesor (Tersisa $days_between hari)</span></li>";
									}
								}else{
									echo "<li>Masa Berlaku Lisensi<span class='pull-right badge'>$masaberlakuasesor (Tersisa $days_between hari)</span></li>";
								}
								echo "<li>Total Portfolio Asesmen<span class='pull-right badge'>$ikutasesmen Skema</span></li>
								<li>Jumlah Portfolio Asesmen Tahun $tahunskr<span class='pull-right badge'>$ikutasesmen2 Skema</span></li>
								<li>Dokumen Pokok <span class='pull-right badge'>$kelengkapan</span></li>

							  </ul>
							</div>
						  </div>
						  <!-- /.widget-user --></td>";
						
			
						echo "</td><td>";
						$sqlgetskkniasesor=$conn->query("SELECT DISTINCT `id_skemakkni` FROM `jadwal_asesmen` WHERE `id_asesor`='$pm[id]'");
						while ($ns=$sqlgetskkniasesor->fetch_assoc()){
							$sqlgetskkni2=$conn->query("SELECT * FROM `skema_kkni` WHERE `id`='$ns[id_skemakkni]'");
							$ns2=$sqlgetskkni2->fetch_assoc();
							echo "<b>$ns2[judul]</b> ($ns2[kode_skema])<br>";
						}
						echo "</td><td>";
						if ($ikutasesmen==0){
					    	echo "<form name='frm' method='POST' role='form' enctype='multipart/form-data'>
							<input type='hidden' name='iddelasesor' value='$pm[id]'><input type='submit' name='hapusasesor' class='btn btn-danger btn-xs' title='Hapus' value='Hapus'></form>
							</td>";
						}else{
							echo"<a href='?module=updateasesor&id=$pm[id]' class='btn btn-primary btn-xs'>Perbarui</a>";
						}
						echo "</tr>";
						$no++;
					}
				echo "</tbody></table>
			</div>
              </div>
              <!-- /.tab-pane -->
	</div>
            <div class='box-footer'>

				<a href='?module=tambahasesor' class='btn btn-primary'>Tambah Asesor Baru</a>&nbsp;<a href='exportasesor.php' class='btn btn-primary'>Ekspor Data Asesor</a>		
			</div>
		  </div>
          
		</div>
	  </div>
	</section>";
  }
}

// Bagian Update Asesor LSP ==========================================================================================================
elseif ($_GET['module']=='updateasesor'){
  if ($_SESSION['leveluser']=='admin'|| $_SESSION['leveluser']=='user'){


$sqllogin="SELECT * FROM `asesor` WHERE `id`='$_GET[id]'";
$login=$conn->query($sqllogin);
$ketemu=$login->num_rows;
$rowAgen=$login->fetch_assoc();

echo "<!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
	  Edit Profile
        <small></small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li><a href='media.php?module=asesor'>Profil Asesor</a></li>
        <li class='active'>Edit</li>
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

	function uploadFotoCert($filecert){
		//$file_max_weight = 20097152; //Ukuran maksimal yg dibolehkan(2Mb)
		$ok_ext = array('jpg','png','gif','jpeg','JPG','PNG','GIF','JPEG','pdf','PDF'); // ekstensi yang diijinkan
		$destination = "../foto_asesor/"; // tempat buat upload
		$filename = explode(".", $filecert['name']); 
		$file_name = $filecert['name'];
		$file_name_no_ext = isset($filename[0]) ? $filename[0] : null;
		$file_extension = $filename[count($filename)-1];
		$file_weight = $filecert['size'];
		$file_type = $filecert['type'];

		// Jika tidak ada error
		if( $filecert['error'] == 0 ){					
			$dateNow = date_create();
			$time_stamp = date_format($dateNow, 'U');
				if( in_array($file_extension, $ok_ext)):
					//if( $file_weight <= $file_max_weight ):
						$fileNewName = $time_stamp.md5( $file_name_no_ext[0].microtime() ).".".$file_extension;
						$alamatfilecert=$fileNewName;
						if( move_uploaded_file($filecert['tmp_name'], $destination.$fileNewName) ):
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
		return $alamatfilecert;
		}


	if( isset( $_REQUEST['simpan'] ))
	{				
		
		$file = $_FILES['file'];
		$filecert = $_FILES['filecert'];
		if(empty($_FILES['file']['tmp_name']) || !is_uploaded_file($_FILES['file']['tmp_name']))
		{
			$alamatfile=$rowAgen['foto'];
					
		}else {
			unlink('../foto_asesor/'.$rowAgen['foto']);
			$alamatfile = uploadFoto($file);
		}
		if(empty($_FILES['filecert']['tmp_name']) || !is_uploaded_file($_FILES['filecert']['tmp_name']))
		{
			$alamatfilecert=$rowAgen['foto_sertifikat'];
					
		}else {
			unlink('../foto_asesor/'.$rowAgen['foto_sertifikat']);
			$alamatfilecert = uploadFotoCert($filecert);
		}
		$query = "UPDATE `asesor` SET `nama`='$_POST[nama]',`gelar_depan`='$_POST[gelar_depan]',`gelar_blk`='$_POST[gelar_blk]',`jenis_kelamin`='$_POST[jenis_kelamin]',`tmp_lahir`='$_POST[tmp_lahir]',`tgl_lahir`='$_POST[tgl_lahir]',`foto`='$alamatfile',`email`='$_POST[email]',`no_hp`='$_POST[no_hp]',`no_induk`='$_POST[no_induk]',`no_ktp`='$_POST[no_ktp]',`pendidikan_terakhir`='$_POST[pendidikan_terakhir]',`tahun_lulus`='$_POST[tahun_lulus]',`bid_keahlian`='$_POST[bid_keahlian]',`pekerjaan`='$_POST[pekerjaan]',`kebangsaan`='$_POST[kebangsaan]',`alamat`='$_POST[alamat]',`RT`='$_POST[RT]',`RW`='$_POST[RW]',`kelurahan`='$_POST[kelurahan]',`kecamatan`='$_POST[kecamatan]',`kota`='$_POST[kota]',`propinsi`='$_POST[propinsi]',`kodepos`='$_POST[kodepos]',`institusi_asal`='$_POST[institusi_asal]',`telp_kantor`='$_POST[telp_kantor]',`fax_kantor`='$_POST[fax_kantor]',`email_kantor`='$_POST[email_kantor]',`no_lisensi`='$_POST[no_lisensi]',`masaberlaku_lisensi`='$_POST[masaberlaku_lisensi]',`foto_sertifikat`='$alamatfilecert',`aktif`='$_POST[aktif]' WHERE `id`='$_POST[id_asesor]'";
		if ($conn->query($query) == TRUE) {					
			echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Ubah Data Sukses</h4>
			Anda Telah Berhasil Mengubah Data <b>Profil Asesor</b></div>";
		//echo('<script>location.href = 'Location: media.php?module=updateasesor&id=$_POST[id_asesor]';</script>');
			die("<script>location.href = 'media.php?module=updateasesor&id=$_POST[id_asesor]'</script>");
		} else {
			echo "Error: " . $query . "<br>" . $conn->error;
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
			Silahkan lengkapi data <b>Profil Asesor</b> untuk dapat melanjutkan</div>";

		}
		echo"<div class='row'>
		    <div class='col-md-6'>			  
				<div class='col-md-12'>			  
					
					<div class='form-group'>
						<label for='fileID'>";
						if (empty($rowAgen['foto'])){
				              		echo"<img class='profile-user-img img-responsive img-circle' src='../images/default.jpg' alt='User profile picture' style='width:150px; height:150px;'>";
						}else{
				              		echo"<img class='profile-user-img img-responsive img-circle' src='../foto_asesor/$rowAgen[foto]' alt='User profile picture' style='width:150px; height:150px;'>";
						}
						echo "</label>
						<input type='file' name='file' id='fileID' accept='image/*' onchange='readURL(this);'>
						<p class='help-block'>Ukuran foto maksimal 2 MB.</p>
						<input type='hidden'  name='id_asesor' value='$rowAgen[id]' class='form-control'>
					</div>
				</div>

				<div class='col-md-3'>  
					<div class='form-group'>
						<label>Gelar depan</label>
						<input type='text'  name='gelar_depan' value='$rowAgen[gelar_depan]' class='form-control'>
					</div>
				</div>
				<div class='col-md-6'>  
					<div class='form-group'>
						<label>Nama Lengkap</label>
						<input required type='text'  name='nama' value='$rowAgen[nama]' class='form-control'>
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
						<label>Bidang Keahlian/ Jurusan/ Prodi</label>
						<input required type='text'  name='bid_keahlian' value='$rowAgen[bid_keahlian]' class='form-control'>
					</div>
				</div>
				<div class='col-md-12'>  
					<div class='form-group'>
						<label>Pekerjaan</label>
						<select  name='pekerjaan' class='form-control'>";
							$sqlgetpekerjaan="SELECT * FROM `pekerjaan` ORDER BY `id` ASC";
							$getpekerjaan=$conn->query($sqlgetpekerjaan);
							while ($krj=$getpekerjaan->fetch_assoc()){
								echo "<option value='$krj[id]' ";if ($krj['id']==$rowAgen['pekerjaan']){echo "selected";}echo ">$krj[pekerjaan]</option>";
							}
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
						<label>Nomor Lisensi Asesor/ No. Sertifikat BNSP</label>
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

              </div>";
	if (!empty($rowAgen['foto_sertifikat'])){

	      echo "<div class='col-md-12'>			  
				<div class='col-md-12'>			
					<div class='form-group'>
						<label>Unggah/Perbarui Sertifikat</label>
						<input type='file' name='filecert' id='fileIDCert' accept='image/*, .pdf' onchange='readURL(this);'>
						<p class='help-block'>Berkas *.jpg/*.bmp/*.png/*.gif ukuran maks. 2 MB.</p>
					</div>
				</div>
				<div class='col-md-12'>			
					<div class='form-group'>
						<label for='fileIDCert'>Sertifikat Asesor";
				              	echo"<embed class='img-responsive' src='../foto_asesor/$rowAgen[foto_sertifikat]' alt='Sertifikat Asesor'>";
						echo "</label>
					</div>
				</div>

	      </div>";
	}else{
	      echo "<div class='col-md-12'>			  
				<div class='col-md-12'>			
					<div class='form-group'>
						<label>Unggah Sertifikat</label>
						<input type='file' name='filecert' id='fileIDCert' accept='image/*, .pdf' onchange='readURL(this);'>
						<p class='help-block'>Berkas *.jpg/*.bmp/*.png/*.gif ukuran maks. 2 MB.</p>
					</div>
				</div>

	      </div>";

	}
              echo"<!-- /.box-body -->

	</div>
	<!-- /.row -->


              <div class='box-footer'>
			<div align='left' class='col-md-6 col-sm-6 col-xs-6'>
				<a class='btn btn-default' id=reset-validate-form href='?module=asesor'>Kembali</a>
			</div>
			<div align='right' class='col-md-6 col-sm-6 col-xs-6'>
				<button type='submit' class='btn btn-primary' name='simpan'>Simpan</button>
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

// Bagian Tambah Asesi LSP ==========================================================================================================
elseif ($_GET['module']=='tambahasesi'){
  if ($_SESSION['leveluser']=='admin'|| $_SESSION['leveluser']=='user'){




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
		$sqllogin="SELECT * FROM `asesi` WHERE `nama`='$_POST[nama]' AND `jenis_kelamin`='$_POST[jenis_kelamin]' AND `tmp_lahir`='$_POST[tmp_lahir]' AND `tgl_lahir`='$_POST[tgl_lahir]'";
		$login=$conn->query($sqllogin);
		$ketemu=$login->num_rows;
		$password=md5($_POST['password']);
		if ($ketemu==0){
			$query = "INSERT INTO `asesi`(`no_pendaftaran`, `password`, `nama`, `tmp_lahir`, `tgl_lahir`, `email`, `nohp`, `no_ktp`, `alamat`, `RT`, `RW`, `kelurahan`, `kecamatan`, `kota`, `propinsi`, `kodepos`, `pendidikan`, `prodi`, `tahun_lulus`, `kebangsaan`, `jenis_kelamin`, `foto`)
					 VALUES ('$_POST[no_pendaftaran]','$password','$_POST[nama]','$_POST[tmp_lahir]','$_POST[tgl_lahir]','$_POST[email]','$_POST[no_hp]','$_POST[no_ktp]','$_POST[alamat]','$_POST[RT]','$_POST[RW]','$_POST[kelurahan]','$_POST[kecamatan]','$_POST[kota]','$_POST[propinsi]','$_POST[kodepos]','$_POST[pendidikan_terakhir]','$_POST[bid_keahlian]','$_POST[tahun_lulus]','$_POST[kebangsaan]','$_POST[jenis_kelamin]','$alamatfile')";
			if ($conn->query($query) == TRUE) {					
				echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				<h4><i class='icon fa fa-check'></i> Tambah Data Sukses</h4>
				Anda Telah Berhasil Menambah Data <b>Asesor</b></div>";
			//echo('<script>location.href = 'Location: editdata.php?type=$type&id=$id&edit=sukses';</script>');
				die("<script>location.href = 'media.php?module=asesmen'</script>");
			} else {
				echo "Error: " . $query . "<br>" . $conn->error;
			}
		}else{
			echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-times'></i> Tambah Data Gagal</h4>
			Data telah ditambahkan sebelumnya</div>";
			die("<script>location.href = 'media.php?module=asesmen'</script>");


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
				if (empty($_GET['idp'])){
					echo "<div class='col-md-6'>  
						<div class='form-group'>
							<label>No. Pendaftaran</label>
							<input required type='text'  name='no_pendaftaran' class='form-control'>
						</div>
					</div>";
				}else{
					echo "<div class='col-md-6'>  
						<div class='form-group'>
							<label>No. Pendaftaran</label>
							<input required type='text'  name='no_pendaftaran' value='$_GET[idp]' class='form-control'>
						</div>
					</div>";

				}

				echo "<div class='col-md-6'>  
					<div class='form-group'>
						<label>Password</label>
						<input required type='text'  name='password' class='form-control'>
					</div>
				</div>
				<div class='col-md-12'>  
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
						<label>Program Studi/Jurusan</label>
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

		</div>
		<div class='col-md-6'>			  
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
  if ($_SESSION['leveluser']=='admin'|| $_SESSION['leveluser']=='user'){




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
    			mail($email,$subjek,$pesan,$dari);
    
    			//SMS Pendaftar
    
    			$isisms="Yth. $_POST[nama] Anda didaftarkan sebagai asesor LSP, dengan No. Register : $_POST[no_induk] Password : $pass2 Silahkan masuk/Login ke http://".$urldomain."/asesor";
    			if (strlen($_POST['no_hp'])>8){
    			    $sqloutbox="INSERT INTO `outbox` (`DestinationNumber`, `TextDecoded`, `Coding`,`SenderID`,`CreatorID`) VALUES ('$_POST[no_hp]','$isisms','Default_No_Compression','MyPhone1','MyPhone1')";
			    $outbox=$conn->query($sqloutbox);
    			}


			$query = "INSERT INTO `asesor`(`password`, `nama`, `gelar_depan`, `gelar_blk`, `jenis_kelamin`, `tmp_lahir`, `tgl_lahir`, `foto`, `email`, `no_hp`, `no_induk`, `no_ktp`, `pendidikan_terakhir`, `tahun_lulus`, `bid_keahlian`, `pekerjaan`, `kebangsaan`, `alamat`, `RT`, `RW`, `kelurahan`, `kecamatan`, `kota`, `propinsi`, `kodepos`, `institusi_asal`, `telp_kantor`, `fax_kantor`, `email_kantor`, `no_lisensi`, `masaberlaku_lisensi`, `aktif`) VALUES ('$password', '$_POST[nama]','$_POST[gelar_depan]','$_POST[gelar_blk]','$_POST[jenis_kelamin]','$_POST[tmp_lahir]','$_POST[tgl_lahir]','$alamatfile','$_POST[email]','$_POST[no_hp]','$_POST[no_induk]','$_POST[no_ktp]','$_POST[pendidikan_terakhir]','$_POST[tahun_lulus]','$_POST[bid_keahlian]','$_POST[pekerjaan]','$_POST[kebangsaan]','$_POST[alamat]','$_POST[RT]','$_POST[RW]','$_POST[kelurahan]','$_POST[kecamatan]','$_POST[kota]','$_POST[propinsi]','$_POST[kodepos]','$_POST[institusi_asal]','$_POST[telp_kantor]','$_POST[fax_kantor]','$_POST[email_kantor]','$_POST[no_lisensi]','$_POST[masaberlaku_lisensi]','$_POST[aktif]')";
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
						<label>Bidang Keahlian/ Jurusan/ Prodi</label>
						<input required type='text'  name='bid_keahlian' value='$rowAgen[bid_keahlian]' class='form-control'>
					</div>
				</div>
				<div class='col-md-12'>  
					<div class='form-group'>
						<label>Pekerjaan</label>
						<select  name='pekerjaan' class='form-control'>";
							$sqlgetpekerjaan="SELECT * FROM `pekerjaan` ORDER BY `id` ASC";
							$getpekerjaan=$conn->query($sqlgetpekerjaan);
							while ($krj=$getpekerjaan->fetch_assoc()){
								echo "<option value='$krj[id]'>$krj[pekerjaan]</option>";
							}
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
			    mail($email,$subjek,$pesan,$dari);
    
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
			    mail($email,$subjek,$pesan,$dari);
    
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
			    mail($email,$subjek,$pesan,$dari);
    
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
			    mail($email,$subjek,$pesan,$dari);
    
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
			    mail($email,$subjek,$pesan,$dari);
    
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
  if ($_SESSION['leveluser']=='admin'|| $_SESSION['leveluser']=='user'){
if( isset( $_REQUEST['tambahjadwal'] ))
	{
	$cekdu="SELECT * FROM `jadwal_asesmen` WHERE `nama_kegiatan`='$_POST[nama_kegiatan]' AND `tahun`='$_POST[tahun]' AND `periode`='$_POST[periode]' AND `gelombang`='$_POST[gelombang]'";
	$result = $conn->query($cekdu);
	if ($result->num_rows == 0){
		$conn->query("INSERT INTO `jadwal_asesmen`(`nama_kegiatan`,`tahun`, `periode`, `gelombang`, `tgl_asesmen`, `jam_asesmen`, `tempat_asesmen`, `id_asesor`, `no_surattugas`, `kapasitas`, `id_skemakkni`) VALUES ('$_POST[nama_kegiatan]', '$_POST[tahun]','$_POST[periode]','$_POST[gelombang]','$_POST[tgl_asesmen]','$_POST[jam_asesmen]','$_POST[tuk]','$_POST[asesor]','$_POST[no_surattugas]','$_POST[kapasitas]','$_POST[skemakkni]')");
        echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Input Data Jadwal Sukses</h4>
			Anda Telah Berhasil Input Data <b>Jadwal Asesmen TUK</b></div>";
		// SMS Asesor
		$sqlgethpasesor="SELECT * FROM `asesor` WHERE `id`='$_POST[asesor]'";
		$gethpasesor=$conn->query($sqlgethpasesor);
		$hpasr=$gethpasesor->fetch_assoc();
		$email=$hpasr['email'];
		$namanya=$hpasr['nama'];
		$no_hp=$hpasr['nohp'];

		$isismsasr="Yth. ".$hpasr['nama'].", Anda dijawal untuk Tugas Asesor pada ".$_POST['tgl_asesmen'].", info lengkap lihat di http://".$urldomain;
		if (strlen($hpasr['no_hp'])>8){
			$sqloutbox="INSERT INTO `outbox` (`DestinationNumber`, `TextDecoded`, `Coding`,`SenderID`,`CreatorID`) VALUES ('$hpasr[no_hp]','$isismsasr','Default_No_Compression','MyPhone1','MyPhone1')";
			$outbox=$conn->query($sqloutbox);
		}
		// Email Asesor
		$sqlgetskema="SELECT * FROM `skema_kkni` WHERE `id`='$_POST[skemakkni]'";
		$getskema=$conn->query($sqlgetskema);
		$gs=$getskema->fetch_assoc();

		$sqltuk2="SELECT * FROM `tuk` WHERE `id`='$_POST[tuk]'";
		$tuk2=$conn->query($sqltuk2);
		$tt2=$tuk2->fetch_assoc();
		$tempatasesmen=$tt2['nama']." ".$tt2['alamat'];
		$tglasesmen=tgl_indo($_POST['tgl_asesmen']);
		$pukulasesmen=$_POST['jam_asesmen'];
		// Kirim email dalam format HTML ke Pendaftar
		    $pesan ="Yth. $namanya, Anda dijadwalkan sebagai Asesor pada Asesmen Skema $gs[kode_skema] - $gs[judul]<br /><br />  
		            Tanggal: $tglasesmen <br />
		            Waktu: Pukul $pukulasesmen <br />
		            Tempat: $tempatasesmen <br />
			    Skema: $gs[kode_skema] - $gs[judul]<br />
		    		<br /><br />Untuk info selengkapnya silahkan lihat jadwal asesmen di laman Dashboard Anda di http://$urldomain.<br /><br />";
		    
			    $subjek="Anda dijadwal sebagai Asesor pada Asesmen pada $tglasesmen";
			    $dari = "From: noreply@".$urldomain."\r\n";
			    $dari .= "Content-type: text/html\r\n";
			    // Kirim email ke member
			    mail($email,$subjek,$pesan,$dari);

	}else{
		echo "<script>alert('Maaf Jadwal TUK Tersebut Sudah Ada');</script>";
	}
}
if( isset( $_REQUEST['hapusjadwal'] ))
	{
	$cekdu="SELECT * FROM `jadwal_asesmen` WHERE `id`='$_POST[iddeljadwal]'";
	$result = $conn->query($cekdu);
	if ($result->num_rows != 0){
		$conn->query("DELETE FROM `jadwal_asesmen` WHERE `id`='$_POST[iddeljadwal]'");
        echo "<div class='alert alert-danger alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
			<h4><i class='icon fa fa-check'></i> Hapus Data Sukses</h4>
			Anda Telah Berhasil Menghapus Data <b>Jadwal Asesmen TUK</b></div>";

	}else{
		echo "<script>alert('Maaf Jadwal Asesmen TUK Tersebut Tidak Ditemukan');</script>";
	}
}
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
					<thead><tr><th>No</th><th>Nama Kegiatan/ Periode, Waktu dan Tempat</th><th>Skema Sertifikasi, Kapasitas Peserta dan Asesor</th><th>Aksi</th></tr></thead>
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
						echo "<br>Asesor :<br><b>$namaasesor</b><br>No. Surat Tugas : $pm[no_surattugas]<b></b></td>";
						if ($jumps==0){
					    		echo "<td width='20%'>";
							if (empty($pm['dok_standarkompetensi'])){							
								echo "<a href='#myModalSKKNI".$pm['id']."' class='btn btn-default btn-xs btn-block' data-toggle='modal' data-id='".$pm['id']."' title='Unggah Dokumen Standar Kompetensi'>Unggah Dokumen Standar Kompetensi</a>";
							}else{
								echo "<a href='#myModalShowDoc".$pm['id']."' class='btn btn-success btn-xs btn-block' data-toggle='modal' data-id='".$pm['id']."' title='Lihat Dokumen Standar Kompetensi'>Lihat Dokumen Standar Kompetensi</a>";
							}
							echo "<br><a href='?module=pesertaasesmen&idj=$pm[id]' class='btn btn-default btn-xs btn-block' title='Belum Ada Peserta Asesmen'>Belum Ada Peserta</a><br><form name='frm' method='POST' role='form' enctype='multipart/form-data'>
							<input type='hidden' name='iddeljadwal' value='$pm[id]'><input type='submit' name='hapusjadwal' class='btn btn-danger btn-xs btn-block' title='Hapus' value='Hapus'></form>
							</td></tr>";
						}else{
							echo "<td width='20%'>";
							if (empty($pm['dok_standarkompetensi'])){							
								echo "<a href='#myModalSKKNI".$pm['id']."' class='btn btn-default btn-xs btn-block' data-toggle='modal' data-id='".$pm['id']."' title='Unggah Dokumen Standar Kompetensi'>Unggah Dokumen Standar Kompetensi</a>";
							}else{
								echo "<a href='#myModalShowDoc".$pm['id']."' class='btn btn-success btn-xs btn-block' data-toggle='modal' data-id='".$pm['id']."' title='Lihat Dokumen Standar Kompetensi'>Lihat Dokumen Standar Kompetensi</a>";
							}
							echo "<br><a href='?module=pesertaasesmen&idj=$pm[id]' class='btn btn-info btn-xs btn-block' title='Lihat Peserta Asesmen'>Lihat Peserta</a>
								<br><a href='daftarhadir.php?idj=$pm[id]' class='btn btn-primary btn-xs btn-block' title='Unduh Daftar Hadir Peserta'>Unduh Presensi</a>
								<br><a href='exporthasilasesmen.php?idj=$pm[id]' class='btn btn-success btn-xs btn-block' title='Unduh hasil asesmen dalam excel untuk usulan blangko sertifikat dan diunggah ke sistem BNSP'>Unduh Hasil Asesmen (Lap. ke BNSP)</a></td></tr>";
						}
						$no++;
						// modal upload dokumen SKKNI Jadwal yang bersangkutan
						echo "<script>
							$(function(){
										$(document).on('click','.edit-record',function(e){
											e.preventDefault();
											$('#myModalSKKNI".$pm['id']."').modal('show');
										});
								});
						</script>
						<!-- Modal -->
							<div class='modal fade' id='myModalSKKNI".$pm['id']."' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
								<div class='modal-dialog'>
									<div class='modal-content'>
										<div class='modal-header'>
											<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Tutup</span></button>
											<h4 class='modal-title' id='myModalLabel'>Dokumen Standar Kompetensi</h4>
										</div>
										<form role='form' method='POST' action='uploadskknijadwal.php' enctype='multipart/form-data'>
										<div class='modal-body'>
												<div class='col-md-12'>
													<div class='form-group'>
														<input type='hidden' name='idpost' value='".$pm['id']."'/>
														<label>Unggah Sertifikat</label>
														<input type='file' name='file".$pm['id']."'/><br>Dokumen : *.doc, *.docx, *.pdf
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
											$('#myModalShowDoc".$pm['id']."').modal('show');
										});
								});
						</script>
						<!-- Modal -->
							<div class='modal fade' id='myModalShowDoc".$pm['id']."' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
								<div class='modal-dialog'>
									<div class='modal-content'>
										<form role='form' method='POST' action='hapusuploadskknijadwal.php' enctype='multipart/form-data'>
										<div class='modal-header'>
											<button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Tutup</span></button>
											<h4 class='modal-title' id='myModalLabel'>Dokumen Standar Kompetensi</h4>
										</div>
										<div class='modal-body'>
											<input type='hidden' name='idpost' value='".$pm['id']."'/>
											<input type='hidden' name='namadok' value='".$pm['dok_standarkompetensi']."'/>

											<embed src='../foto_dokskkni/$pm[dok_standarkompetensi]' width='100%' height='400px'/>
										</div>
										<div class='modal-footer'>
											<div align='left' class='col-md-6 col-sm-6 col-xs-6'>
												<button type='submit' class='btn btn-danger' name='simpancert'>Hapus</button>
											</div>
											<div align='right' class='col-md-6 col-sm-6 col-xs-6'>
												<button type='button' class='btn btn-default' data-dismiss='modal'>Tutup</button>
											</div>
										</div>
										</form>

									</div>
									</div>
							</div>
						<!-- Modal -->";

					}

				echo "</tbody></table>
			</div>
			</div>
		  </div>
		  <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Input Data Jadwal Uji Kompetensi (Jadwal TUK)</h3>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>
			
				<form name='frm' method='POST' role='form' enctype='multipart/form-data'>
					<div class='col-md-12'>
						<div class='form-group'>
							<label>Nama/ Judul Kegiatan</label>
							<textarea name='nama_kegiatan' class='form-control'></textarea>
						</div>
					</div>
					<div class='col-md-2'>
						<div class='form-group'>
							<label>Tahun</label>
							<input type='text' name='tahun' class='form-control'>
						</div>
					</div>
					<div class='col-md-2'>
						<div class='form-group'>
							<label>Periode</label>
							<select name='periode' class='form-control' required>
								<option value='Januari'>Januari</option>
								<option value='Februari'>Februari</option>
								<option value='Maret'>Maret</option>
								<option value='April'>April</option>
								<option value='Mei'>Mei</option>
								<option value='Juni'>Juni</option>
								<option value='Juli'>Juli</option>
								<option value='Agustus'>Agustus</option>
								<option value='September'>September</option>
								<option value='Oktober'>Oktober</option>
								<option value='November'>November</option>
								<option value='Desember'>Desember</option>
							</select>
						</div>
					</div>
					<div class='col-md-2'>
						<div class='form-group'>
							<label>Gelombang/ Grup</label>
							<select name='gelombang' class='form-control' required>
								<option value='1'>1</option>
								<option value='2'>2</option>
								<option value='3'>3</option>
								<option value='4'>4</option>
								<option value='5'>5</option>
								<option value='6'>6</option>
								<option value='7'>7</option>
								<option value='8'>8</option>
								<option value='9'>9</option>
								<option value='10'>10</option>
							</select>
						</div>
					</div>
					<div class='col-md-2'>
						<div class='form-group'>
							<label>Tanggal Pelaksanaan</label>
							<input type='date' name='tgl_asesmen' class='form-control'>
						</div>
					</div>
					<div class='col-md-2'>
						<div class='form-group'>
							<label>Jam Pelaksanaan</label>
							<select name='jam_asesmen' class='form-control' required>
								<option value='07.00'>07.00</option>
								<option value='07.30'>07.30</option>
								<option value='08.00'>08.00</option>
								<option value='08.30'>08.30</option>
								<option value='09.00'>09.00</option>
								<option value='09.30'>09.30</option>
								<option value='10.00'>10.00</option>
								<option value='10.30'>10.30</option>
								<option value='11.00'>11.00</option>
								<option value='11.30'>11.30</option>
								<option value='12.00'>12.00</option>
								<option value='12.30'>12.30</option>
								<option value='13.00'>13.00</option>
								<option value='13.30'>13.30</option>
								<option value='14.00'>14.00</option>
								<option value='14.30'>14.30</option>
								<option value='15.00'>15.00</option>
								<option value='15.30'>15.30</option>
								<option value='16.00'>16.00</option>
								<option value='16.30'>16.30</option>
								<option value='17.00'>17.00</option>
								<option value='17.30'>17.30</option>
								<option value='18.00'>18.00</option>
								<option value='18.30'>18.30</option>
								<option value='19.00'>19.00</option>
								<option value='19.30'>19.30</option>
								<option value='20.00'>20.00</option>
								<option value='20.30'>20.30</option>
								<option value='21.00'>21.00</option>
								<option value='21.30'>21.30</option>
								<option value='22.00'>22.00</option>
								<option value='22.30'>22.30</option>
							</select>
						</div>
					</div>
					<div class='col-md-2'>
						<div class='form-group'>
							<label>Kuota Peserta</label>
							<input type='text' name='kapasitas' class='form-control' required>
						</div>
					</div>

					<div class='col-md-6'>
						<div class='form-group'>
								<label>SKK/SKKNI</label>
								<select name='skkni' id='skkni' class='form-control'>
									<option>--Pilih SKKNI--</option>";
									$sqlskkni="SELECT * FROM `skkni` ORDER BY `nama` ASC";
									$skkni=$conn->query($sqlskkni);
									while ($sk=$skkni->fetch_assoc()){
										echo"<option value='$sk[id]'>$sk[nama]</option>";
									}
								echo"</select>
						</div>
					</div>
					<div class='col-md-6'>
						<div class='form-group'>
								<label>Skema KKNI LSP</label>
								<select name='skemakkni' id='skemakkni' class='form-control'>
									<option></option>
								</select>
						</div>
					</div>
					<div class='col-md-4'>
						<div class='form-group'>
								<label>TUK</label>
								<select id='tuk' name='tuk' class='form-control'><option>-- Pilih TUK --</option>";
								$sqltuk="SELECT * FROM `tuk` ORDER BY `nama` ASC";
								$tuk=$conn->query($sqltuk);
								while ($tu=$tuk->fetch_assoc()){
									echo"<option value='$tu[id]'>$tu[nama]</option>";
								}
								echo"</select>
						</div>
					</div>
					<div class='col-md-2'>
						<div class='form-group'>
								<label>Lingkup Asesor</label>
								<select id='asesorwil' name='asesorwil' class='form-control'>";
									/*echo"<option value='wilayah'>Wilayah</option>";
									echo"<option value='nonwilayah'>Non Wilayah</option>";*/

								echo"</select>
						</div>
					</div>

					<div class='col-md-3'>
						<div class='form-group'>
								<label>Asesor</label>
								<select id='asesor' name='asesor' class='form-control'>";
								/*$sqlasesor="SELECT * FROM `asesor` ORDER BY `nama` ASC";
								$asesor=$conn->query($sqlasesor);
								while ($asr=$asesor->fetch_assoc()){
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

									echo"<option value='$asr[id]'>$namaasesor</option>";
								}*/
								echo"</select>
						</div>
					</div>
					<div class='col-md-3'>
						<div class='form-group'>
							<label>Nomor Surat Tugas</label>
							<input type='text' name='no_surattugas' class='form-control'>
						</div>
					</div>

					<div class='col-md-12'>
						<div class='form-group'>
							<input type='submit'  name='tambahjadwal' class='btn btn-primary' value='Tambahkan'>
						</div>
					</div>
				</form>
			
			</div>
		  </div>
          
		</div>
	  </div>
	</section>";
	}
}

// Bagian Peserta Asesmen ================================================================================================================
elseif ($_GET['module']=='pesertaasesmen'){
  if ($_SESSION['leveluser']=='admin'|| $_SESSION['leveluser']=='user'){
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
  if ($_SESSION['leveluser']=='admin'|| $_SESSION['leveluser']=='user'){
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
  if ($_SESSION['leveluser']=='admin'|| $_SESSION['leveluser']=='user'){




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
  if ($_SESSION['leveluser']=='admin'|| $_SESSION['leveluser']=='user'){

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
elseif ($_GET['module']=='sent'){
  if ($_SESSION['leveluser']=='admin'|| $_SESSION['leveluser']=='user'){


echo "<!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
	  SMS Notifikasi
        <small></small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li class='active'>SMS</li>
      </ol>
    </section>";

if( isset( $_REQUEST['kirimulang'] ))
	{
		// SMS Ulang
		$nohpnya="nomor".$_POST['idresend'];
		$pesannya="pesan".$_POST['idresend'];
		$no_hp=$_POST[$nohpnya];
		$isismsasr=$_POST[$pesannya];
		if (strlen($no_hp)>8){
			$sqloutbox="INSERT INTO `outbox` (`DestinationNumber`, `TextDecoded`, `Coding`,`SenderID`,`CreatorID`) VALUES ('$no_hp','$isismsasr','Default_No_Compression','MyPhone1','MyPhone1')";
			$outbox=$conn->query($sqloutbox);
			$sqldelsms="DELETE FROM `sentitems` WHERE `ID`='$_POST[idresend]'";
			$conn->query($sqldelsms);
		}

}

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
				<table id='example1' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Waktu Pengiriman</th><th>Isi Pesan</th><th>Status</th></tr></thead>
					<tbody>";
					$no=1;
					$sqlasesiikut="SELECT * FROM `sentitems` ORDER BY `SendingDateTime` DESC";
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
							$statuskirim="<span class='btn-danger btn-flat btn-xs'>Gagal Terkirim</span><br><form role='form' method='POST' enctype='multipart/form-data'><input type='hidden' name='idresend' value='$pm0[ID]'><input type='hidden' name='pesan$pm0[ID]' value='$pm0[TextDecoded]'><input type='hidden' name='nomor$pm0[ID]' value='$pm0[DestinationNumber]'><input type='submit' name='kirimulang' class='btn-primary btn-flat btn-xs' value='Kirim Ulang'></form>";
						break;
						case 'DeliveryOK':
							$statuskirim="<span class='btn-success btn-flat btn-xs'>Terkirim</span>";
						break;
						case 'DeliveryFailed':
							$statuskirim="<span class='btn-danger btn-flat btn-xs'>Gagal Terkirim</span><br><form role='form' method='POST' enctype='multipart/form-data'><input type='hidden' name='idresend' value='$pm0[ID]'><input type='hidden' name='pesan$pm0[ID]' value='$pm0[TextDecoded]'><input type='hidden' name='nomor$pm0[ID]' value='$pm0[DestinationNumber]'><input type='submit' name='kirimulang' class='btn-primary btn-flat btn-xs' value='Kirim Ulang'></form>";
						break;
						case 'DeliveryPending':
							$statuskirim="<span class='btn-warning btn-flat btn-xs'>Tertahan</span>";
						break;
						case 'DeliveryUnknown':
							$statuskirim="<span class='btn-info btn-flat btn-xs'>Status tidak diketahui</span><br><form role='form' method='POST' enctype='multipart/form-data'><input type='hidden' name='idresend' value='$pm0[ID]'><input type='hidden' name='pesan$pm0[ID]' value='$pm0[TextDecoded]'><input type='hidden' name='nomor$pm0[ID]' value='$pm0[DestinationNumber]'><input type='submit' name='kirimulang' class='btn-primary btn-flat btn-xs' value='Kirim Ulang'></form>";
						break;
						case 'Error':
							$statuskirim="<span class='btn-danger btn-flat btn-xs'>Terjadi kesalahan sistem</span><br><form role='form' method='POST' enctype='multipart/form-data'><input type='hidden' name='idresend' value='$pm0[ID]'><input type='hidden' name='pesan$pm0[ID]' value='$pm0[TextDecoded]'><input type='hidden' name='nomor$pm0[ID]' value='$pm0[DestinationNumber]'><input type='submit' name='kirimulang' class='btn-primary btn-flat btn-xs' value='Kirim Ulang'></form>";
						break;
						}
						echo "<tr class=gradeX><td>$no</td><td><b>$pm0[SendingDateTime]</b></td><td><b>$pm0[DestinationNumber]</b><br>$pm0[TextDecoded]</td><td>$statuskirim</td></tr>";
						$no++;
					}
					$sqlasesiikut2="SELECT * FROM `outbox` ORDER BY `SendingDateTime` DESC";
					$asesiikut2=$conn->query($sqlasesiikut2);
					while ($pm02=$asesiikut2->fetch_assoc()){
						
						$statuskirim="<span class='btn-danger btn-flat btn-xs'>menunggu</span>";
						echo "<tr class=gradeX><td>$no</td><td><b>$pm02[SendingDateTime]</b></td><td>$pm02[TextDecoded]</td><td>$statuskirim</td></tr>";
						$no++;
					}
				echo "</tbody></table>
			
			</div>
		  </div>
	
            
	</section>
    <!-- /.content -->";
  }
}

// Bagian SMS Notifikasi
elseif ($_GET['module']=='inbox'){
  if ($_SESSION['leveluser']=='admin'|| $_SESSION['leveluser']=='user'){


echo "<!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
	  SMS Notifikasi
        <small></small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li class='active'>SMS Inbox</li>
      </ol>
    </section>";


echo "<!-- Main content -->
    <section class='content'>
      <div class='row'>
        <div class='col-xs-12'>
          <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Notifikasi SMS yang diterima Sistem SMS Gateway</h3>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>
				<table id='example1' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Waktu Penerimaan</th><th>Isi Pesan</th><th>Status</th></tr></thead>
					<tbody>";
					$no=1;
					$sqlasesiikut="SELECT * FROM `inbox` ORDER BY `Processed` ASC, `ReceivingDateTime` DESC";
					$asesiikut=$conn->query($sqlasesiikut);
					while ($pm0=$asesiikut->fetch_assoc()){
						switch ($pm0['Processed']){
						case 'true':
							$statuskirim="<a class='btn btn-success btn-flat btn-xs btn-block'>Terbaca</a>";
						break;
						case 'false':
							$statuskirim="<a class='btn btn-primary btn-flat btn-xs btn-block'>Masuk</a><a href='#myModalInbox".$pm0['ID']."' data-toggle='modal' data-id='".$pm0['ID']."' class='btn btn-xs btn-flat btn-success btn-block' title='Baca Pesan'>Baca</a>";
						break;
						
						}
						echo "<tr class=gradeX><td>$no</td><td><b>$pm0[ReceivingDateTime]</b></td><td>$pm0[TextDecoded]</td><td>$statuskirim</td></tr>";
						$no++;
							echo "<script>
								$(function(){
											$(document).on('click','.edit-record',function(e){
												e.preventDefault();
												$('#myModalInbox".$pm0['ID']."').modal('show');
											});
									});
							</script>
							<!-- Modal -->
								<div class='modal fade' id='myModalInbox".$pm0['ID']."' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
									<div class='modal-dialog'>
										<div class='modal-content'>
											<form role='form' action='bacainbox.php' method='POST' enctype='multipart/form-data'>
											<div class='modal-header'>
												<button type='submit' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Tutup</span></button>
												<h4 class='modal-title' id='myModalLabel'>Pesan SMS dari $pm0[SenderNumber] tanggal $pm0[ReceivingDateTime]</h4>
											</div>
											<div class='modal-body'>
												<input type='hidden' name='idpostinbox' value='$pm0[ID]'/>
												<p>Pesan $pm0[ID] :<br>$pm0[TextDecoded]</p>
											</div>
											<div class='modal-footer'>
												<div align='left' class='col-md-6 col-sm-6 col-xs-6'>
												</div>
												<div align='right' class='col-md-6 col-sm-6 col-xs-6'>
													<button type='submit' name='baca' class='btn btn-default'>Tutup</button>
												</div>
											</div>
											</form>

										</div>
									</div>
								</div>

							<!-- Modal -->";

					}
					
				echo "</tbody></table>
			
			</div>
		  </div>
	
            
	</section>
    <!-- /.content -->";
  }
}

// Bagian Event LSP ================================================================================================================
elseif ($_GET['module']=='event'){
  if ($_SESSION['leveluser']=='admin'|| $_SESSION['leveluser']=='user'){

    echo "
    <!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
        Penyelenggaraan Uji Kompetensi (Event Asesmen)
        <small>Input Data</small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li class='active'>Event Uji Kompetensi Sertifikasi Profesi</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class='content'>
      <div class='row'>
        <div class='col-xs-12'>
          <div class='box'>
            <div class='box-header'>
              <h3 class='box-title'>Data Penyelenggaraan Uji Kompetensi Lembaga Sertifikasi Profesi</h3>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>
			
				<table id='example1' class='table table-bordered table-striped'>
					<thead><tr><th>No</th><th>Nama Kegiatan/ Periode, Waktu dan Tempat</th></tr></thead>
					<tbody>";
					$no=1;
					$sqljadwaltuk="SELECT DISTINCT `id_event` FROM `jadwal_asesmen`";
					$jadwaltuk=$conn->query($sqljadwaltuk);
					while ($pm=$jadwaltuk->fetch_assoc()){
						echo "<tr class=gradeX><td>$no</td><td>";
						$sqljadwaltuk2="SELECT * FROM `jadwal_asesmen` WHERE `id_event`='$pm[id_event]'";
						$jadwaltuk2=$conn->query($sqljadwaltuk2);
						$pm2=$jadwaltuk2->fetch_assoc();
						$jumasesinya=0;
							$sqltukjenis1="SELECT * FROM `tuk_jenis` WHERE `id`='$pm2[jenis_tuk]'";
							$jenistuk=$conn->query($sqltukjenis1);
							$jt=$jenistuk->fetch_assoc();
							$sqllspinduk="SELECT * FROM `lsp` WHERE `id`='$pm2[lsp_induk]'";
							$lspinduk=$conn->query($sqllspinduk);
							$li=$lspinduk->fetch_assoc();
							$sqltuk="SELECT * FROM `tuk` WHERE `id`='$pm2[tempat_asesmen]'";
							$tuk=$conn->query($sqltuk);
							$tt=$tuk->fetch_assoc();

							$tglasesmen=tgl_indo($pm2['tgl_asesmen']);
							if (empty($pm2['nama_kegiatan'])){
								$namakegiatan=$pm2['periode']." ".$pm2['tahun']." Gelombang ".$pm2['gelombang'];
							}else{
								$namakegiatan=$pm2['nama_kegiatan'];
							}
							echo "<b>$namakegiatan</b><br>Tanggal : <b>$tglasesmen</b></br>Tempat :<br><b>$tt[nama]</b><br>$tt[alamat] $tt[kelurahan]<br>";
						$sqljadwaltuk2a="SELECT * FROM `jadwal_asesmen` WHERE `id_event`='$pm[id_event]'";
						$jadwaltuk2a=$conn->query($sqljadwaltuk2a);
						while ($pm3=$jadwaltuk2a->fetch_assoc()){
							/* $masa_berlaku=tgl_indo($pm3['masa_berlaku']);
							$sqltukjenis1="SELECT * FROM `tuk_jenis` WHERE `id`='$pm3[jenis_tuk]'";
							$jenistuk=$conn->query($sqltukjenis1);
							$jt=$jenistuk->fetch_assoc();
							$sqllspinduk="SELECT * FROM `lsp` WHERE `id`='$pm3[lsp_induk]'";
							$lspinduk=$conn->query($sqllspinduk);
							$li=$lspinduk->fetch_assoc();
							$sqltuk="SELECT * FROM `tuk` WHERE `id`='$pm3[tempat_asesmen]'";
							$tuk=$conn->query($sqltuk);
							$tt=$tuk->fetch_assoc();

							$tglasesmen=tgl_indo($pm3['tgl_asesmen']);
							if (empty($pm3['nama_kegiatan'])){
								$namakegiatan=$pm3['periode']." ".$pm2['tahun']." Gelombang ".$pm2['gelombang'];
							}else{
								$namakegiatan=$pm3['nama_kegiatan'];
							}
							echo "<b>$namakegiatan</b><br>Tanggal : <b>$tglasesmen</b></br>Tempat :<br><b>$tt[nama]</b><br>$tt[alamat] $tt[kelurahan]<br>"; */


							$sqljumpeserta="SELECT * FROM `asesi_asesmen` WHERE `id_jadwal`='$pm3[id]'";
							$jumpesertax=$conn->query($sqljumpeserta);
							$jumasesi=$jumpesertax->num_rows;
							$jumasesinya=$jumasesinya+$jumasesi;
							//echo "$pm3[id] Jumlah Peserta : <b>$jumasesi</b><br>";
						}
						echo "<h4>JUMLAH TOTAL PESERTA : <b>$jumasesinya PESERTA</b></h4></td></tr>";
						$no++;
					}
				echo "</tbody></table>
			
			</div>
		  </div>
	  </div>
	</section>";
	}
}
// Bagian Profil Admin
elseif ($_GET['module']=='profil'){
$sqllogin="SELECT * FROM `users` WHERE `username`='$_SESSION[namauser]'";
$login=$conn->query($sqllogin);
$ketemu=$login->num_rows;
$rowAgen=$login->fetch_assoc();
						if (!empty($rowAgen['gelar_depan'])){
							if (!empty($rowAgen['gelar_blk'])){
								$namaasesor=$rowAgen['gelar_depan']." ".$rowAgen['nama_lengkap'].", ".$rowAgen['gelar_blk'];
							}else{
								$namaasesor=$rowAgen['gelar_depan']." ".$rowAgen['nama_lengkap'];
							}
						}else{
							if (!empty($rowAgen['gelar_blk'])){
								$namaasesor=$rowAgen['nama_lengkap'].", ".$rowAgen['gelar_blk'];
							}else{
								$namaasesor=$rowAgen['nama_lengkap'];
							}
						}


echo "<!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
	  Profil Administrator
        <small></small>
      </h1>
      <ol class='breadcrumb'>
        <li><a href='media.php?module=home'><i class='fa fa-dashboard'></i> Home</a></li>
        <li class='active'>Profil Administrator</li>
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
              		echo"<img class='profile-user-img img-responsive img-circle' src='images/default.jpg' alt='User profile picture' style='width:128px; height:128px;'>";
		}else{
              		echo"<img class='profile-user-img img-responsive img-circle' src='../foto_pengguna/$rowAgen[foto]' alt='User profile picture' style='width:128px; height:128px;'>";
		}

              echo "<h3 class='profile-username text-center'>$namaasesor</h3>

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
              <strong><h3 class='box-title'>Detail Data Administrator</h3></strong>
            </div>
            <!-- /.box-header -->
            <div class='box-body'>
			<br>
              <strong class='col-md-3'>Nomor Induk Pegawai</strong>
               <span class='col-md-9 col-sm-12 col-xs-12'><b>$rowAgen[no_induk]</b></span>
			   <br><br>
              <strong class='col-md-3'>Nomor Lisensi</strong>
               <span class='col-md-9 col-sm-12 col-xs-12'><b>$rowAgen[no_lisensi]</b></span>
			   <br><br>
              <strong class='col-md-3'>Masa Berlaku Lisensi</strong>
               <span class='col-md-9 col-sm-12 col-xs-12'><b>";
				echo tgl_indo($rowAgen['masaberlaku_lisensi']);
				echo "</b></span>
			   <br><br>

              <strong class='col-md-3'>Nomor KTP</strong>
               <span class='col-md-9 col-sm-12 col-xs-12'>$rowAgen[no_ktp]</span>
			   <br><br>
              <strong class='col-md-3'>Nama Lengkap dan Gelar</strong>
               <span class='col-md-9 col-sm-12 col-xs-12'>$namaasesor</span>
			   <br><br>
              <strong class='col-md-3'>Tempat, Tanggal Lahir</strong>";
				$date = tgl_indo($rowAgen['tgl_lahir']);
				$tanggal_lahir = $date;
			  echo"<span class='col-md-9 col-sm-12 col-xs-12'>$rowAgen[tmp_lahir], $tanggal_lahir</span>
			   <br><br>
              
              <strong class='col-md-3'>Alamat</strong>
               <span class='col-md-9 col-sm-12 col-xs-12'>";
			$sqlwil1="SELECT * FROM `data_wilayah` WHERE `id_wil`='$rowAgen[kecamatan]'";
			$wilayah1=$conn->query($sqlwil1);
			$wil1=$wilayah1->fetch_assoc();
			$sqlwil2="SELECT * FROM `data_wilayah` WHERE `id_wil`='$rowAgen[kota]'";
			$wilayah2=$conn->query($sqlwil2);
			$wil2=$wilayah2->fetch_assoc();
			$sqlwil3="SELECT * FROM `data_wilayah` WHERE `id_wil`='$rowAgen[propinsi]'";
			$wilayah3=$conn->query($sqlwil3);
			$wil3=$wilayah3->fetch_assoc();
			echo $rowAgen['alamat']." RT ".$rowAgen['RT'].", RW ".$rowAgen['RW'].", ".$wil1['nm_wil'].", ".$wil2['nm_wil'].", ".$wil3['nm_wil']." ".$rowAgen['kodepos'];
			   echo"</span><br><br>
                           <strong class='col-md-3'>Nomor HP</strong>
               <span class='col-md-9 col-sm-12 col-xs-12'>$rowAgen[no_telp]</span>
			   <br><br>
              <strong class='col-md-3'>Email</strong>
               <span class='col-md-9 col-sm-12 col-xs-12'><a href='mailto:$rowAgen[email]'>$rowAgen[email]</a></span>
			   <br><br>
              <strong class='col-md-3'>Pendidikan Terakhir</strong>
               <span class='col-md-9 col-sm-12 col-xs-12'>";
			$sqlpendidikan="SELECT * FROM `pendidikan` WHERE `id`='$rowAgen[pendidikan_terakhir]'";
			$pendidikan=$conn->query($sqlpendidikan);
			$jpen=$pendidikan->fetch_assoc();
				echo"$jpen[jenjang_pendidikan]</span>
			   <br><br>
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
  if ($_SESSION['leveluser']=='admin'|| $_SESSION['leveluser']=='user'){
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
  if ($_SESSION['leveluser']=='admin'|| $_SESSION['leveluser']=='user'){
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
  if ($_SESSION['leveluser']=='admin'|| $_SESSION['leveluser']=='user'){
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
  if ($_SESSION['leveluser']=='admin'|| $_SESSION['leveluser']=='user'){
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

		// Register Instalasi Baru
		$email="febiharsa@gmail.com";
		// Kirim email dalam format HTML
		$pesan ="Instalasi Baru SILSP telah dilakukan di $_POST[url_domain]<br /><br />  
	            Nama LSP : $_POST[nama_lsp] <br />
	            URL Domain: ".$_SERVER['HTTP_HOST']." <br />
	            Host: $_POST[url_domain] <br />
		    <br /><br />Untuk info selengkapnya silahkan lihat di http://$_POST[url_domain].<br /><br />";
		    
		$subjek="Instalasi Baru SILSP telah dilakukan di $_POST[url_domain]";
		$dari = "From: noreply@".$_POST['url_domain']."\r\n";
		$dari .= "Content-type: text/html\r\n";
		// Kirim email ke member
		mail($email,$subjek,$pesan,$dari);


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

new Chart(document.getElementById("bar-chart2"), {
    type: 'bar',
    data: {
      labels: ["23 - 29", "30 - 36", "37 - 43", "44 - 50", "51 - 57", "> 57"],
      datasets: [
        {
          backgroundColor: ["#00D4E5", "#00E1AE","#00DD68","#00D925","#1AD500", "#59D200"],
          data: [<?php echo $jumusia1asr; ?>,<?php echo $jumusia2asr; ?>,<?php echo $jumusia3asr; ?>,<?php echo $jumusia4asr; ?>,<?php echo $jumusia5asr; ?>,<?php echo $jumusia6asr; ?>],
          label: "Jumlah Asesor"
        }
      ]
    },
    options: {
      legend: { display: false },
      title: {
        display: true,
        text: 'Jumlah Asesor Berdasarkan Kelompok Usia (Tahun)'
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

new Chart(document.getElementById("pie-chart2"), {
    type: 'pie',
    data: {
      labels: ["Laki-Laki", "Perempuan", "Tidak Diketahui"],
      datasets: [{
        label: "Jumlah Asesor",
        backgroundColor: ["#30D500", "#CAB000","#6300D2"],
        data: [<?php echo $jumlakiasr; ?>,<?php echo $jumperempuanasr; ?>,<?php echo $jumnotlpasr; ?>]
      }]
    },
    options: {
      title: {
        display: true,
        text: 'Jumlah Asesor Berdasarkan Jenis Kelamin'
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