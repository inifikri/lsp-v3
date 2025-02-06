<?php

session_start();
ini_set('display_errors', 0);
error_reporting(E_ALL);
include "../config/koneksi.php";

//fungsi cek akses user
function user_akses($mod,$id){
	$link = "?module=".$mod;
	$sqlcekmodul="SELECT * FROM modul,users_modul WHERE modul.id_modul=users_modul.id_modul AND users_modul.id_session='$id' AND modul.link='$link'";
	$cekmodul=$conn->query($sqlcekmodul);
	$cek = $cekmodul->num_rows;
	
	return $cek;
}

//fungsi cek akses menu

function umenu_akses($link,$id){
	$sqlcekakses="SELECT * FROM modul,users_modul WHERE modul.id_modul=users_modul.id_modul AND users_modul.id_session='$id' AND modul.link='$link'";
	$cekakses=$conn->query($sqlcekakses);
	$cek = $cekakses->num_rows;
	return $cek;
}

//fungsi redirect

function akses_salah(){
	$pesan = "<link href='style.css' rel='stylesheet' type='text/css'>
 <center>Maaf Anda tidak berhak mengakses halaman ini</center>";
 	$pesan.= "<meta http-equiv='refresh' content='2;url=media.php?module=home'>";
	return $pesan;
}

if (empty($_SESSION['username']) AND empty($_SESSION['passuser'])){
  echo "<link href='../css/screen.css' rel='stylesheet' type='text/css'><link href='../css/reset.css' rel='stylesheet' type='text/css'>
 <center><br><br><br><br><br><br>Maaf, untuk masuk <b>Halaman Administrator</b><br>
  <center>anda harus <b>Login</b> dahulu!<br><br>";
 echo "<div> <a href='index.php'><img src='../images/lock.png'  height=176 width=143></a>
             </div>";
  echo "<input type=button class=simplebtn value='LOGIN DI SINI' onclick=location.href='index.php'></a></center>";
}
else{
echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>
<meta name='description'  content='Sistem Informasi Lembaga Sertifikasi Profesi'/>
<meta name='keywords' content='Sistem Informasi LSP, LSP, Lembaga Sertifikasi Profesi, SILSP'/>
<meta name='robots' content='ALL,FOLLOW'/>
<meta name='Author' content='Dhega Febiharsa'/>
<meta http-equiv='imagetoolbar' content='no'/>


<title>Dahsboard Administrator | Sistem Informasi Lembaga Sertifikasi Profesi</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
  <!-- Bootstrap 3.3.6 -->
  <link rel='stylesheet' href='../bootstrap/css/bootstrap.min.css'>
  <!-- Font Awesome -->
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css'>
  <!-- Ionicons -->
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css'>
  <!-- daterange picker -->
  <link rel='stylesheet' href='../plugins/daterangepicker/daterangepicker.css'>
  <!-- bootstrap datepicker -->
  <link rel='stylesheet' href='../plugins/datepicker/datepicker3.css'>
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel='stylesheet' href='../plugins/iCheck/all.css'>
  <!-- Bootstrap Color Picker -->
  <link rel='stylesheet' href='../plugins/colorpicker/bootstrap-colorpicker.min.css'>
  <!-- Bootstrap time Picker -->
  <link rel='stylesheet' href='../plugins/timepicker/bootstrap-timepicker.min.css'>
  <!-- Select2 -->
  <link rel='stylesheet' href='../plugins/select2/select2.min.css'>
  <!-- CKEditor -->
  <link rel='stylesheet' href='https://cdn.ckeditor.com/ckeditor5/43.3.1/ckeditor5.css' />
  <!-- Theme style -->
  <link rel='stylesheet' href='../dist/css/AdminLTE.min.css'>
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel='stylesheet' href='../dist/css/skins/_all-skins.min.css'>
  <!-- Script untuk Digital Signature (Tandatangan Digital) -->
   
    <script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js'></script> 
    <link type='text/css' href='https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/south-street/jquery-ui.css' rel='stylesheet'> 
    <script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js'></script>
   
    <script type='text/javascript' src='../assets/js/jquery.signature.min.js'></script>
    <script type='text/javascript' src='../assets/js/jquery.ui.touch-punch.min.js'></script>
    <link rel='stylesheet' type='text/css' href='../assets/css/jquery.signature.css'>
   
    <style>
        .kbw-signature { width: 100%; height: 400px;}
        #sig canvas{
            width: 100% !important;
            height: auto;
        }
    </style>


</head>


<body class='hold-transition skin-blue sidebar-mini'>

<script type='text/javascript'>
	function confirmation() {
	  return confirm('Apakah Anda yakin ingin menghapus?');
	}
</script>
<script type='text/javascript'>
	function confirmationtolak() {
	  return confirm('Apakah Anda yakin ingin menolak?');
	}
</script>


<div class='wrapper'>

  <header class='main-header'>
    <!-- Logo -->
    <a href='?module=home' class='logo'>
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class='logo-mini'><b>A</b>dmin</span>
      <!-- logo for regular state and mobile devices -->
      <span class='logo-lg'>S.I.<b>LSP</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class='navbar navbar-static-top' role='navigation'>
      <!-- Sidebar toggle button-->
      <a href='#' class='sidebar-toggle' data-toggle='offcanvas' role='button'>
        <span class='sr-only'>Toggle navigation</span>
        <span class='icon-bar'></span>
        <span class='icon-bar'></span>
        <span class='icon-bar'></span>
      </a>

      <div class='navbar-custom-menu'>
        <ul class='nav navbar-nav'>
            
          <!-- Tasks: style can be found in dropdown.less -->
          <li class='dropdown tasks-menu'>
            <a href='?module=inbox' class='dropdown-toggle' data-toggle='dropdown'>
              <i class='fa fa-flag-o'></i>"; 
		$sqlgethpasr="SELECT * FROM `users` WHERE `username`='$_SESSION[namauser]'";
		$dta=$conn->query($sqlgethpasr);
		$r=$dta->fetch_assoc();
		$sqlgetsms="SELECT * FROM `inbox` WHERE `Processed`='false'";
		$getsms=$conn->query($sqlgetsms);
		$jumlahpesan=$getsms->num_rows;
		if ($jumlahpesan>0){
			echo "<span class='label label-danger'>$jumlahpesan</span>";
		}
            echo"</a> 
	    <ul class='dropdown-menu'>
              <li class='header'>Anda memiliki $jumlahpesan pemberitahuan</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class='menu'>";
			while ($note=$getsms->fetch_assoc()){
				echo "<li><!-- Task item -->
                      			<p><small class='pull-left'><b>$note[ReceivedDateTime]</b></small>
					<small class='pull-left'>
                      			  $note[TextDecoded]
                      			</small></p>
                  		</li>";
			}

		  echo "<!-- end task item -->
                </ul>
              </li>
              <li class='footer'>
                <a href='?module=inbox'>Lihat semua pemberitahuan</a>
              </li>
            </ul>           
          </li>
          <!-- User Account: style can be found in dropdown.less -->
          <li class='dropdown user user-menu'>
            <a href='#' class='dropdown-toggle' data-toggle='dropdown'>";
		$sqlgetuser="SELECT * FROM `users` WHERE `username`='$_SESSION[namauser]'";
		$getuser=$conn->query($sqlgetuser);
		$dt=$getuser->fetch_assoc();
		
		if ($_SESSION['foto']==''){
			echo"<img src='../images/default.jpg' class='user-image' alt='User Image'/>";
		}else{
			$new_file_directory = '../foto_pengguna/'.$_SESSION['foto'];
			echo "<img src='$new_file_directory'  class='user-image' alt='User Image'/>";
		}
		
              echo "<span class='hidden-xs'>";
			  echo $_SESSION['namalengkap'];
			  echo "</span>
            </a>
            <ul class='dropdown-menu'>
              <!-- User image -->
              <li class='user-header'>";
		if ($_SESSION['foto']==''){
			echo"<img src='../images/default.jpg' class='user-image' alt='User Image'/>";
		}else{
			$new_file_directory = '../foto_pengguna/'.$_SESSION['foto'];
			$new_file_directory2 = '../foto_pengguna/'.$_SESSION['foto'];
			echo "<img src='$new_file_directory'  class='user-image' alt='User Image'/>";
		}
        echo "<p>
                  <small>";
		echo $_SESSION['namalengkap'];
		echo "</small><br />";
		echo $_SESSION['namauser'];
                echo "</p>
              </li>
              <!-- Menu Footer-->
              <li class='user-footer'>
                <div class='pull-left'>
                  <a href='?module=profil' class='btn btn-default btn-flat'>Profil</a>
                </div>
                <div class='pull-right'>
                  <a href='logout.php' class='btn btn-default btn-flat'>Keluar</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class='main-sidebar'>
    <!-- sidebar: style can be found in sidebar.less -->
    <section class='sidebar'>
      <!-- Sidebar user panel -->
      <div class='user-panel'>
        <div class='pull-left image'>";
          if ($_SESSION['foto']==''){
		echo"<img src='../images/default.jpg' class='img-circle' alt='User Image'/>";
	}else{
		$new_file_directory = '../foto_pengguna/'.$_SESSION['foto'];
		$new_file_directory2 = '../foto_pengguna/'.$_SESSION['foto'];
		$fileada=file_exists($new_file_directory);
		//if($fileada=='1'){
			echo "<img src='$new_file_directory'  class='img-circle' alt='User Image'/>";
		//}else{
		//	echo "<img src='$new_file_directory2'  class='img-circle' alt='User Image'/>";
		//}
	}

        echo "</div>
        <div class='pull-left info'>
          <p><small>";
		  echo $_SESSION['namalengkap'];
		  echo "</small><br />"; 
		  echo $_SESSION['namauser'];
echo "</p>
        </div>
      </div>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class='sidebar-menu'>
        <li class='header'>MAIN NAVIGATION</li>";       
      include "menu.php";
echo "</ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class='content-wrapper'>
    <!-- Content Header (Page header) -->
    <section class='content-header'>
      <h1>
        <!--Buttons
        <small>Control panel</small> --->
      </h1>
      <!-- <ol class='breadcrumb'>
        <li><a href='#'><i class='fa fa-dashboard'></i> Home</a></li>
        <li><a href='#'>UI</a></li>
        <li class='active'>Buttons</li>
      </ol> -->
    </section>

    <!-- Main content -->";

include "content.php"; 
	echo "<!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class='main-footer'>
    <div class='pull-right hidden-xs'>
      <b>Version</b>  1.0.7
    </div>
    <strong>Copyright &copy; 2017-"; 
	$tahun=date("Y"); 
	echo "$tahun <a href='http://www.aplikasilsp.web.id'>SILSP</a>.</strong> All rights
    reserved.
  </footer>

</div>
<!-- ./wrapper -->


<!-- jQuery 2.2.3 -->
<script src='../plugins/jQuery/jquery-2.2.3.min.js'></script>
<!-- Bootstrap 3.3.6 -->
<script src='../bootstrap/js/bootstrap.min.js'></script>
<!-- Select2 -->
<script src='../plugins/select2/select2.full.min.js'></script>
<!-- InputMask -->
<script src='../plugins/input-mask/jquery.inputmask.js'></script>
<script src='../plugins/input-mask/jquery.inputmask.date.extensions.js'></script>
<script src='../plugins/input-mask/jquery.inputmask.extensions.js'></script>
<!-- date-range-picker -->
<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js'></script>
<script src='../plugins/daterangepicker/daterangepicker.js'></script>
<!-- bootstrap datepicker -->
<script src='../plugins/datepicker/bootstrap-datepicker.js'></script>
<!-- bootstrap color picker -->
<script src='../plugins/colorpicker/bootstrap-colorpicker.min.js'></script>
<!-- bootstrap time picker -->
<script src='../plugins/timepicker/bootstrap-timepicker.min.js'></script>
<!-- SlimScroll 1.3.0 -->
<script src='../plugins/slimScroll/jquery.slimscroll.min.js'></script>
<!-- iCheck 1.0.1 -->
<script src='../plugins/iCheck/icheck.min.js'></script>
<!-- FastClick -->
<script src='../plugins/fastclick/fastclick.js'></script>
<!-- AdminLTE App -->
<script src='../dist/js/app.min.js'></script>
<!-- AdminLTE for demo purposes -->
<script src='../dist/js/demo.js'></script> 
<!-- DataTables -->
<script src='../plugins/datatables/jquery.dataTables.min.js'></script>
<script src='../plugins/datatables/dataTables.bootstrap.min.js'></script>
<!-- Page script -->";
?>
<script type="importmap">
            {
                "imports": {
                    "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/43.3.1/ckeditor5.js",
                    "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/43.3.1/"
                }
            }
        </script>

        <script type="module">
            import {
                ClassicEditor,
                Essentials,
                Bold,
                Italic,
                Font,
                Paragraph
            } from 'ckeditor5';

            ClassicEditor
                .create( document.querySelector( '#editor1' ), {
                    plugins: [ Essentials, Bold, Italic, Font, Paragraph ],
                    toolbar: [
                        'undo', 'redo', '|', 'bold', 'italic', '|',
                        'fontSize'
                    ],
                } );
            ClassicEditor
                .create( document.querySelector( '#editor2' ), {
                    plugins: [ Essentials, Bold, Italic, Font, Paragraph ],
                    toolbar: [
                        'undo', 'redo', '|', 'bold', 'italic', '|',
                        'fontSize'
                    ],
                } );
            ClassicEditor
                .create( document.querySelector( '#editor3' ), {
                    plugins: [ Essentials, Bold, Italic, Font, Paragraph ],
                    toolbar: [
                        'undo', 'redo', '|', 'bold', 'italic', '|',
                        'fontSize'
                    ],
                } );
            ClassicEditor
                .create( document.querySelector( '#editor4' ), {
                    plugins: [ Essentials, Bold, Italic, Font, Paragraph ],
                    toolbar: [
                        'undo', 'redo', '|', 'bold', 'italic', '|',
                        'fontSize'
                    ],
                } );
            ClassicEditor
                .create( document.querySelector( '#editor5' ), {
                    plugins: [ Essentials, Bold, Italic, Font, Paragraph ],
                    toolbar: [
                        'undo', 'redo', '|', 'bold', 'italic', '|',
                        'fontSize'
                    ],
                } );
        </script>
<script>
  $(function () {
    $('#example1').DataTable();
    $('#example2').DataTable({
      'paging': true,
      'lengthChange': false,
      'searching': false,
      'ordering': true,
      'info': true,
      'autoWidth': false
    });
  });
</script>
<script>
  $(function () {
    $('#example3').DataTable({
      'paging': true,
      'lengthChange': true,
      'searching': true,
      'ordering': true,
      'info': true,
      'autoWidth': true
    });
    $('#example4').DataTable({
      'paging': true,
      'lengthChange': true,
      'searching': true,
      'ordering': true,
      'info': true,
      'autoWidth': true
    });
    $('#example5').DataTable({
      'paging': true,
      'lengthChange': true,
      'searching': true,
      'ordering': true,
      'info': true,
      'autoWidth': true
    });
    $('#example6').DataTable({
      'paging': true,
      'lengthChange': true,
      'searching': true,
      'ordering': true,
      'info': true,
      'autoWidth': true
    });
    $('#example7').DataTable({
      'paging': true,
      'lengthChange': true,
      'searching': true,
      'ordering': true,
      'info': true,
      'autoWidth': true
    });
    $('#example8').DataTable({
      'paging': true,
      'lengthChange': true,
      'searching': true,
      'ordering': true,
      'info': true,
      'autoWidth': true
    });

  });
</script>
<script>
  $(function () {
  // Initialize Select2 Elements
    $('.select2').select2();

    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', {'placeholder': 'dd/mm/yyyy'});
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', {'placeholder': 'mm/dd/yyyy'});
    //Money Euro
    $('[data-mask]').inputmask();

    //Date range picker
    $('#reservation').daterangepicker();
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
    //Date range as a button
    $('#daterange-btn').daterangepicker(
        {
          ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          startDate: moment().subtract(29, 'days'),
          endDate: moment()
        },
        function (start, end) {
          $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
    );

    //Date picker
    $('#datepicker').datepicker({
      autoclose: true
    });

    //iCheck for checkbox and radio inputs
    //  $('input[type='checkbox'].minimal, input[type='radio'].minimal').iCheck({
    //    checkboxClass: 'icheckbox_minimal-blue',
    //    radioClass: 'iradio_minimal-blue'
    //  });
    //  //Red color scheme for iCheck
    //  $('input[type='checkbox'].minimal-red, input[type='radio'].minimal-red').iCheck({
    //    checkboxClass: 'icheckbox_minimal-red',
    //    radioClass: 'iradio_minimal-red'
    //  });
    //  //Flat red color scheme for iCheck
    //  $('input[type='checkbox'].flat-red, input[type='radio'].flat-red').iCheck({
    //    checkboxClass: 'icheckbox_flat-green',
    //    radioClass: 'iradio_flat-green'
    //  });

    //Colorpicker
    $('.my-colorpicker1').colorpicker();
    //color picker with addon
    $('.my-colorpicker2').colorpicker();

    //Timepicker
    $('.timepicker').timepicker({
      showInputs: false
    });
  });
</script>

<script type='text/javascript'>
function deleteLSP(id)
{
     if(confirm('Apakah anda yakin untuk menghapus data ini?'))
     {
        $.ajax({
        type: 'POST',
        url: 'deletelsp.php',
        data: {
            'id' : id

        },
        success: function (r) {
			window.location.reload();
        }
		});
     }
}

</script>
<!-- Script untuk Mengontrol Dropdown List Bertingkat Propinsi, Kota dan Keamatam-->
<script type='text/javascript'>

var htmlobjek;
$(document).ready(function(){
//apabila terjadi event onchange terhadap object <select id=propinsi>
$('#propinsi').change(function(){
var propinsi = $('#propinsi').val();
$.ajax({
url: '../config/getkota.php',
data: 'propinsi='+propinsi,
cache: false,
success: function(msg){
//jika data sukses diambil dari server kita tampilkan
//di <select id=kota>
$('#kota').html(msg);
}
});
});


$('#kota').change(function(){
var kota = $('#kota').val();
$.ajax({
url: '../config/getkecamatan.php',
data: 'kota='+kota,
cache: false,
success: function(msg){
//jika data sukses diambil dari server kita tampilkan
//di <select id=kecamatan>
$('#kecamatan').html(msg);
}
});
});
});

</script>

<!-- Script untuk Mengontrol Dropdown List LSP-->
<script type='text/javascript'>
var htmlobjek;
$(document).ready(function(){
//apabila terjadi event onchange terhadap object <select id=lsp>
$('#lsp').change(function(){
var lsp = $('#lsp').val();
$.ajax({
url: '../config/getlsp.php',
data: 'lsp='+lsp,
cache: false,
success: function(msg){
//jika data sukses diambil dari server kita tampilkan
//di <select id=skkni>
$('#skkni').html(msg);
}
});
});


$('#skkni').change(function(){
var skkni = $('#skkni').val();
$.ajax({
url: '../config/getskkni.php',
data: 'skkni='+skkni,
cache: false,
success: function(msg){
//jika data sukses diambil dari server kita tampilkan
//di <select id=skemakkni>
$('#skemakkni').html(msg);
}
});
});


$('#skemakkni').change(function(){
var skemakkni = $('#skemakkni').val();
$.ajax({
url: '../config/getbiaya.php',
data: 'skemakkni='+skemakkni,
cache: false,
success: function(msg){
//jika data sukses diambil dari server kita tampilkan
//di <select id=jenis_biaya>
$('#jenis_biaya').html(msg);
}
});
});
});


var htmlobjek;
$(document).ready(function(){
//apabila terjadi event onchange terhadap object <select id=id_jadwal>
$('#jadwalasesmen').change(function(){
var jadwalasesmen = $('#jadwalasesmen').val();
$.ajax({
url: '../config/getdeskripsijadwal.php',
data: 'jadwalasesmen='+jadwalasesmen,
cache: false,
success: function(msg){
//jika data sukses diambil dari server kita tampilkan
//di <select id=deskripsijadwal>
$('#deskripsijadwal').html(msg);
}
});
});

//apabila terjadi event onchange terhadap object <select id=jadwalasesmenubah>
$('#jadwalasesmenubah').change(function(){
var jadwalasesmenubah = $('#jadwalasesmenubah').val();
$.ajax({
url: '../config/getdeskripsijadwalubah.php',
data: 'jadwalasesmenubah='+jadwalasesmenubah,
cache: false,
success: function(msg){
//jika data sukses diambil dari server kita tampilkan
//di <select id=deskripsijadwalubah>
$('#deskripsijadwalubah').html(msg);
}
});
});

//apabila terjadi event onchange terhadap object <select id=jadwalasesmenubah>
$('#jadwalasesmenujiulang').change(function(){
var jadwalasesmenujiulang = $('#jadwalasesmenujiulang').val();
$.ajax({
url: '../config/getdeskripsijadwalujiulang.php',
data: 'jadwalasesmenujiulang='+jadwalasesmenujiulang,
cache: false,
success: function(msg){
//jika data sukses diambil dari server kita tampilkan
//di <select id=deskripsijadwalujiulang>
$('#deskripsijadwalujiulang').html(msg);
}
});
});

$('#subjadwal').change(function(){
var subjadwal = $('#subjadwal').val();
$.ajax({
url: '../config/getsubjadwal.php',
data: 'subjadwal='+subjadwal,
cache: false,
success: function(msg){
//jika data sukses diambil dari server kita tampilkan
//di <select id=subjadwal>
$('#subjadwal').html(msg);
}
});
});
});

</script>

<!-- Script untuk Mengontrol Dropdown List Asesor-->
<script type='text/javascript'>

var htmlobjek;
$(document).ready(function(){
//apabila terjadi event onchange terhadap object <select id=tuk>
$('#tuk').change(function(){
var tuk = $('#tuk').val();
$.ajax({
url: '../config/getwilasesor.php',
data: 'tuk='+tuk,
cache: false,
success: function(msg){
//jika data sukses diambil dari server kita tampilkan
//di <select id=asesorwil>
$('#asesorwil').html(msg);
}
});
});


$('#asesorwil').change(function(){
var ASESORWIL = $('#asesorwil').val();
var TUK = $('#tuk').val();
$.ajax({
url: '../config/getrekomasesor.php',
data: {asesorwil:ASESORWIL,tuk:TUK},
//data: 'asesorwil='+ASESORWIL,
cache: false,
success: function(msg){
//jika data sukses diambil dari server kita tampilkan
//di <select id=asesor>
$('#asesor').html(msg);
}
});
});
});

</script>


<script language='javascript' type='text/javascript'>
     $(window).load(function() {
     $('#loading').hide();
  });
</script>



  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src='https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js'></script>
  <script src='https://oss.maxcdn.com/respond/1.4.2/respond.min.js'></script>
  <![endif]-->




</body>
<meta http-equiv='content-type' content='text/html;charset=UTF-8'>
</html>
<?php
}

?>