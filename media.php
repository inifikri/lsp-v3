<?php



session_start();

include "config/koneksi.php";

ini_set('display_errors',1); 

error_reporting(E_ALL);

$timeout = 7 * 24 * 3600; // Number of seconds until it times out.



// Check if the timeout field exists.

if (isset($_SESSION['timeout'])) {

  // See if the number of seconds since the last

  // visit is larger than the timeout period.

  $duration = time() - (int)$_SESSION['timeout'];

  if ($duration > $timeout) {

    // Destroy the session and restart it.

    session_destroy();

    session_start();
  }
}



// Update the timout field with the current time.

$_SESSION['timeout'] = time();

//fungsi redirect



if (empty($_SESSION['username']) and empty($_SESSION['passuser'])) {



  echo "  <!-- Bootstrap 3.3.6 -->

  <link rel='stylesheet' href='bootstrap/css/bootstrap.min.css'>

 <center><br><br><br><br><br><br>Maaf, untuk masuk <b>Halaman Asesi</b><br>

  <center>anda harus <b>Login</b> dahulu!<br><br>";

  echo "<div> <a href='index.php#login'><img src='images/lock.png' width='10%'></a></div><br>";

  echo "<input type='button' class='btn default' value='LOGIN DI SINI' onclick=location.href='index.php'></a></center>";
} else {



?>



  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

  <html xmlns="http://www.w3.org/1999/xhtml">

  <head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <meta name="description" content="" />

    <meta name="keywords" content="" />

    <meta name="robots" content="ALL,FOLLOW" />

    <meta name="Author" content="Dhega Febiharsa" />

    <meta http-equiv="imagetoolbar" content="no" />





    <title>Laman Asesi Sistem Informasi LSP</title>

    <!-- Tell the browser to be responsive to screen width -->

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- Bootstrap 3.3.6 -->

    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">

    <!-- Font Awesome -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">

    <!-- Ionicons -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">

    <!-- daterange picker -->
    
    <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">

    <!-- bootstrap datepicker -->
    
    <link rel="stylesheet" href="plugins/datepicker/datepicker3.css">

    <!-- iCheck for checkboxes and radio inputs -->

    <link rel="stylesheet" href="plugins/iCheck/all.css">
    
    <!-- Bootstrap Color Picker -->
    
    <link rel="stylesheet" href="plugins/colorpicker/bootstrap-colorpicker.min.css">

    <!-- Bootstrap time Picker -->
    
    <link rel="stylesheet" href="plugins/timepicker/bootstrap-timepicker.min.css">


    <!-- Select2 -->
    
    <link rel="stylesheet" href="plugins/select2/select2.min.css">

    <!-- Theme style -->

    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    
    <!-- AdminLTE Skins. Choose a skin from the css/skins
    
    folder instead of downloading all of them to reduce the load. -->
    <!-- DataTables -->
    <link rel="stylesheet" href="<?= $base_url?>plugins/datatables/dataTables.bootstrap.css">

    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">



    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->

    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

    <!--[if lt IE 9]>

  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>

  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

  <![endif]-->



    <!-- Script untuk Digital Signature (Tandatangan Digital) -->



    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <link type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/south-street/jquery-ui.css" rel="stylesheet">

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>



    <script type="text/javascript" src="assets/js/jquery.signature.min.js"></script>

    <script type="text/javascript" src="assets/js/jquery.ui.touch-punch.min.js"></script>

    <link rel="stylesheet" type="text/css" href="assets/css/jquery.signature.css">



    <style>
      .kbw-signature {
        width: 100%;
        height: 400px;
      }

      #sig canvas {

        width: 100% !important;

        height: auto;

      }
    </style>

  </head>





  <body class="hold-transition skin-blue sidebar-mini">

    <div class="wrapper">



      <header class="main-header">

        <!-- Logo -->

        <a href="?module=home" class="logo">

          <!-- mini logo for sidebar mini 50x50 pixels -->

          <span class="logo-mini"><b>Si</b>LSP</span>

          <!-- logo for regular state and mobile devices -->

          <span class="logo-lg"><b>Asesi</b> LSP</span>

        </a>

        <!-- Header Navbar: style can be found in header.less -->

        <nav class="navbar navbar-static-top" role="navigation">

          <!-- Sidebar toggle button-->

          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">

            <span class="sr-only">Toggle navigation</span>

            <span class="icon-bar"></span>

            <span class="icon-bar"></span>

            <span class="icon-bar"></span>

          </a>



          <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">



              <!-- Tasks: style can be found in dropdown.less -->

              <li class="dropdown tasks-menu">

                <a href="?module=sms" class="dropdown-toggle" data-toggle="dropdown">

                  <i class="fa fa-flag-o"></i>

                  <span class="label label-danger"><?php

                                                    $sqlgetasesi = "SELECT * FROM `asesi` WHERE `no_pendaftaran`='$_SESSION[namauser]'";

                                                    $dt = $conn->query($sqlgetasesi);

                                                    $r = $dt->fetch_assoc();

                                                    $sqlgetsms = "SELECT * FROM `sentitems` WHERE `DestinationNumber` = '$r[nohp]'";

                                                    $getsms = $conn->query($sqlgetsms);

                                                    $jumlahpesan1 = $getsms->num_rows;

                                                    $sqlgetsms2 = "SELECT * FROM `outbox` WHERE `DestinationNumber` = '$r[nohp]'";

                                                    $getsms2 = $conn->query($sqlgetsms2);

                                                    $jumlahpesan2 = $getsms2->num_rows;

                                                    $jumlahpesan = $jumlahpesan1 + $jumlahpesan2;

                                                    echo "$jumlahpesan";

                                                    ?></span>

                </a>

                <ul class="dropdown-menu">

                  <li class="header">Anda memiliki <?php echo $jumlahpesan; ?> pemberitahuan</li>

                  <li>

                    <!-- inner menu: contains the actual data -->

                    <ul class="menu">

                      <?php

                      while ($note = $getsms->fetch_assoc()) {

                        echo "<li><!-- Task item -->

                      			<p><small class='pull-left'><b>$note[SendingDateTime]</b></small>

					<small class='pull-left'>

                      			  $note[TextDecoded]

                      			</small></p>

                  		</li>";
                      }

                      while ($note2 = $getsms2->fetch_assoc()) {

                        echo "<li><!-- Task item -->

                      			<p><small class='pull-left'><b>$note2[SendingDateTime]</b></small>

                      			<small class='pull-left'>

                      			  $note2[TextDecoded]

                      			</small></p>

                  		</li>";
                      }



                      ?>

                      <!-- end task item -->

                    </ul>

                  </li>

                  <li class="footer">

                    <a href="?module=sms">Lihat semua pemberitahuan</a>

                  </li>

                </ul>

              </li>

              <!-- User Account: style can be found in dropdown.less -->

              <li class="dropdown user user-menu">

                <a href="#" class="dropdown-toggle" data-toggle="dropdown">

                  <?php





                  if ($r['foto'] == '') {

                    echo "<img src='images/default.jpg' class='user-image' alt='User Image'/>";
                  } else {

                    if (substr($r['foto'], 0, 4) == "http") {

                      echo "<img src='$r[foto]'  class='user-image' alt='User Image'/>";
                    } else {

                      echo "<img src='foto_asesi/$r[foto]'  class='user-image' alt='User Image'/>";
                    }
                  }

                  ?>

                  <span class="hidden-xs"><?php echo $_SESSION['namalengkap']; ?></span>

                </a>

                <ul class="dropdown-menu">

                  <!-- User image -->

                  <li class="user-header">

                    <?php

                    if ($r['foto'] == '') {

                      echo "<img src='images/default.jpg' class='img-circle' alt='User Image'/>";
                    } else {

                      if (substr($r['foto'], 0, 4) == "http") {

                        echo "<img src='$r[foto]' class='img-circle' alt='User Image'/>";
                      } else {

                        echo "<img src='foto_asesi/$r[foto]' class='img-circle' alt='User Image'/>";
                      }
                    }

                    ?>



                    <p>

                      <small><?php echo $_SESSION['namalengkap']; ?>

                      </small>Asesi<br />ID: <?php echo $_SESSION['namauser']; ?>

                    </p>

                  </li>

                  <!-- Menu Footer-->

                  <li class="user-footer">

                    <div class="pull-left">

                      <a href="?module=profil" class="btn btn-default btn-flat">Profil</a>

                    </div>

                    <div class="pull-right">

                      <a href="logout.php" class="btn btn-default btn-flat">Keluar</a>

                    </div>

                  </li>

                </ul>

              </li>

            </ul>

          </div>

        </nav>

      </header>

      <!-- Left side column. contains the logo and sidebar -->

      <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->

        <section class="sidebar">

          <!-- Sidebar user panel -->

          <div class="user-panel">

            <div class="pull-left image">

              <?php

              if ($r['foto'] == '') {

                echo "<img src='images/default.jpg' class='img-circle' alt='User Image'/>";
              } else {

                if (substr($r['foto'], 0, 4) == "http") {

                  echo "<img src='$r[foto]' class='img-circle' alt='User Image'/>";
                } else {

                  echo "<img src='foto_asesi/$r[foto]' class='img-circle' alt='User Image'/>";
                }
              }

              ?>

            </div>

            <div class="pull-left info">

              <p><small><?php echo $_SESSION['namalengkap']; ?></small><br>ID: <?php echo $_SESSION['namauser']; ?>

              </p>

            </div>

          </div>

          <!-- sidebar menu: : style can be found in sidebar.less -->

          <ul class="sidebar-menu">

            <li class="header">MENU</li>

            <?php include "menu.php"; ?>



          </ul>

        </section>

        <!-- /.sidebar -->

      </aside>



      <!-- Content Wrapper. Contains page content -->

      <div class="content-wrapper">

        <!-- Content Header (Page header) -->

        <section class="content-header">

          <h1>

            <!--Buttons

        <small>Control panel</small> --->

          </h1>

          <!-- <ol class="breadcrumb">

        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>

        <li><a href="#">UI</a></li>

        <li class="active">Buttons</li>

      </ol> -->

        </section>



        <!-- Main content -->

        <section class="content">



          <div class="row">

            <div class="col-xs-12">

              <div class="box">

                <div class="box-body">

                  <?php include "content.php"; ?>

                </div>

              </div>

            </div>

            <!-- /.col -->

          </div>

          <!-- ./row -->

        </section>

        <!-- /.content -->

      </div>

      <!-- /.content-wrapper -->

      <?php

      echo "<footer class='main-footer'>

    <div class='pull-right hidden-xs'>

      <b>Version</b> 1.0.8

    </div>

    <strong>Copyright &copy; 2018-";

      $tahun = date("Y");

      echo "$tahun <a href='http://www.aplikasilsp.web.id'>SILSP</a>.</strong> All rights reserved.

    </footer>



    </div>";

      ?>

      <!-- ./wrapper -->





      <!-- jQuery 2.2.3 -->

      <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>

      <!-- Bootstrap 3.3.6 -->

      <script src="bootstrap/js/bootstrap.min.js"></script>

      <!-- Select2 -->

      <script src="plugins/select2/select2.full.min.js"></script>

      <!-- InputMask -->

      <script src="plugins/input-mask/jquery.inputmask.js"></script>

      <script src="plugins/input-mask/jquery.inputmask.date.extensions.js"></script>

      <script src="plugins/input-mask/jquery.inputmask.extensions.js"></script>

      <!-- date-range-picker -->

      <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>

      <script src="plugins/daterangepicker/daterangepicker.js"></script>

      <!-- bootstrap datepicker -->

      <script src="plugins/datepicker/bootstrap-datepicker.js"></script>

      <!-- bootstrap color picker -->

      <script src="plugins/colorpicker/bootstrap-colorpicker.min.js"></script>

      <!-- bootstrap time picker -->

      <script src="plugins/timepicker/bootstrap-timepicker.min.js"></script>

      <!-- SlimScroll 1.3.0 -->

      <script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>

      <!-- iCheck 1.0.1 -->

      <script src="plugins/iCheck/icheck.min.js"></script>

      <!-- FastClick -->

      <script src="plugins/fastclick/fastclick.js"></script>

      <!-- AdminLTE App -->

      <script src="dist/js/app.min.js"></script>

      <!-- AdminLTE for demo purposes -->

      <script src="dist/js/demo.js"></script>

      <!-- DataTables -->

      <script src='<?= $base_url; ?>plugins/datatables/jquery.dataTables.min.js'></script>

      <script src='<?= $base_url; ?>plugins/datatables/dataTables.bootstrap.min.js'></script>

      <!-- Page script -->

      <script type='text/javascript'>
        function confirmation() {

          return confirm('Apakah Anda yakin ingin menghapus?');

        }
        function confirmationsimpan() {

          return confirm('Apakah Anda yakin ingin menyimpan data?');

        }
        function confirmationupdate() {

          return confirm('Apakah Anda yakin ingin mengubah data?');

        }
      </script>


      <script>
        $(function() {

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
        $(function() {

          $('#example3').DataTable({

            'paging': true,

            'lengthChange': true,

            'searching': true,

            'ordering': true,

            'info': true,

            'autoWidth': true

          });

          $('#tablefrai05').DataTable({

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
        $(function() {

          //Initialize Select2 Elements

          $(".select2").select2();



          //Datemask dd/mm/yyyy

          $("#datemask").inputmask("dd/mm/yyyy", {
            "placeholder": "dd/mm/yyyy"
          });

          //Datemask2 mm/dd/yyyy

          $("#datemask2").inputmask("mm/dd/yyyy", {
            "placeholder": "mm/dd/yyyy"
          });

          //Money Euro

          $("[data-mask]").inputmask();



          //Date range picker

          $('#reservation').daterangepicker();

          //Date range picker with time picker

          $('#reservationtime').daterangepicker({
            timePicker: true,
            timePickerIncrement: 30,
            format: 'MM/DD/YYYY h:mm A'
          });

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

            function(start, end) {

              $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

            }

          );



          //Date picker

          $('#datepicker').datepicker({

            autoclose: true

          });



          //iCheck for checkbox and radio inputs

          $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({

            checkboxClass: 'icheckbox_minimal-blue',

            radioClass: 'iradio_minimal-blue'

          });

          //Red color scheme for iCheck

          $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({

            checkboxClass: 'icheckbox_minimal-red',

            radioClass: 'iradio_minimal-red'

          });

          //Flat red color scheme for iCheck

          $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({

            checkboxClass: 'icheckbox_flat-green',

            radioClass: 'iradio_flat-green'

          });



          //Colorpicker

          $(".my-colorpicker1").colorpicker();

          //color picker with addon

          $(".my-colorpicker2").colorpicker();



          //Timepicker

          $(".timepicker").timepicker({

            showInputs: false

          });

        });
      </script>





      <!-- Script untuk Mengontrol Dropdown List Bertingkat Propinsi, Kota dan Keamatam-->

      <script type="text/javascript">
        var htmlobjek;

        $(document).ready(function() {

          //apabila terjadi event onchange terhadap object <select id=propinsi>

          $("#propinsi").change(function() {

            var propinsi = $("#propinsi").val();

            $.ajax({

              url: "config/getkota.php",

              data: "propinsi=" + propinsi,

              cache: false,

              success: function(msg) {

                //jika data sukses diambil dari server kita tampilkan

                //di <select id=kota>

                $("#kota").html(msg);

              }

            });

          });





          $("#kota").change(function() {

            var kota = $("#kota").val();

            $.ajax({

              url: "config/getkecamatan.php",

              data: "kota=" + kota,

              cache: false,

              success: function(msg) {

                //jika data sukses diambil dari server kita tampilkan

                //di <select id=kecamatan>

                $("#kecamatan").html(msg);

              }

            });

          });



          $("#kecamatan").change(function() {

            var kecamatan = $("#kecamatan").val();

            $.ajax({

              url: "config/getkelurahan.php",

              data: "kecamatan=" + kecamatan,

              cache: false,

              success: function(msg) {

                //jika data sukses diambil dari server kita tampilkan

                //di <select id=kelurahan>

                $("#kelurahan").html(msg);

              }

            });

          });

        });







        var htmlobjek;

        $(document).ready(function() {

          //apabila terjadi event onchange terhadap object <select id=pembayaran>

          $("#pembayaran").change(function() {

            var pembayaran = $("#pembayaran").val();

            $.ajax({

              url: "config/jalurpembayaran.php",

              data: "pembayaran=" + pembayaran,

              cache: false,

              success: function(msg) {

                //jika data sukses diambil dari server kita tampilkan

                //di <select id=jalurpembayaran>

                $("#jalurpembayaran").html(msg);

              }

            });

          });





          $("#jalurpembayaran").change(function() {

            var jalurpembayaran = $("#jalurpembayaran").val();

            $.ajax({

              url: "config/rekpembayaran.php",

              data: "jalurpembayaran=" + jalurpembayaran,

              cache: false,

              success: function(msg) {

                //jika data sukses diambil dari server kita tampilkan

                //di <select id=rekening>

                $("#rekening").html(msg);

              }

            });

          });



          $("#skema").change(function() {

            var skema = $("#skema").val();

            $.ajax({

              url: "config/biayaasesmen.php",

              data: "skema=" + skema,

              cache: false,

              success: function(msg) {

                //jika data sukses diambil dari server kita tampilkan

                //di <select id=nominal>

                $("#nominal").html(msg);

              }

            });

          });

        });





        var htmlobjek;

        $(document).ready(function() {

          //apabila terjadi event onchange terhadap object <select id=jenis_portfolio>

          $("#jenis_portfolio").change(function() {

            var jenis_portfolio = $("#jenis_portfolio").val();

            $.ajax({

              url: "config/getportfolio.php",

              data: "jenis_portfolio=" + jenis_portfolio,

              cache: false,

              success: function(msg) {

                //jika data sukses diambil dari server kita tampilkan

                //di <select id=peran_portfolio>

                $("#peran_portfolio").html(msg);

              }

            });

          });

        });



        var htmlobjek;

        $(document).ready(function() {

          //apabila terjadi event onchange terhadap object <select id=jenis_portfoliom>

          $("#jenis_portfoliom").change(function() {

            var jenis_portfoliom = $("#jenis_portfoliom").val();

            $.ajax({

              url: "config/getportfoliom.php",

              data: "jenis_portfoliom=" + jenis_portfoliom,

              cache: false,

              success: function(msg) {

                //jika data sukses diambil dari server kita tampilkan

                //di <select id=peran_portfoliom>

                $("#peran_portfoliom").html(msg);

              }

            });

          });

        });
      </script>



  </body>

  <meta http-equiv="content-type" content="text/html;charset=UTF-8">

  </html>



<?php



}



?>