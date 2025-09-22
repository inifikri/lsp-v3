<!DOCTYPE html>
<html>
	<?php
		include "config/koneksi.php";
		include "config/fungsi_indotgl.php";
	?>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>
		<?php 
			$sqlgetidentitas="SELECT * FROM `lsp` ORDER BY `id` DESC LIMIT 1";
			$getidentitas=$conn->query($sqlgetidentitas);
			$lsp=$getidentitas->fetch_assoc();
			echo"$lsp[nama]";
		?>
		</title>
		<meta name="description" content="<?php echo "$lsp[nama]"; ?>">
		<meta name="author" content="IT-RAYS">
		
		<!-- Mobile Meta -->
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		
		<!-- Put favicon.ico and apple-touch-icon(s).png in the images folder -->
	    <link rel="shortcut icon" href="images/favicon.ico">
		    	
		<!-- CSS StyleSheets -->
		<link href='https://fonts.googleapis.com/css?family=Raleway:400,100,200,300,500,600,700,800,900' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="assets/css/assets.css">
		
		<link rel="stylesheet" href="assets/css/style.css">
		<link id="theme_css" rel="stylesheet" href="assets/css/light.css">
		
		<!-- REVOLUTION SLIDER STYLES -->
		<link rel="stylesheet" href="assets/revolution/fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css" type="text/css">
		<link rel="stylesheet" href="assets/revolution/css/settings.css" type="text/css">
		<link rel="stylesheet" href="assets/revolution/css/layers.css" type="text/css">
		<link rel="stylesheet" href="assets/revolution/css/navigation.css" type="text/css">
		<!--[if lt IE 9]>
	    	<script type="text/javascript" src="assets/js/html5.js"></script>
	    <![endif]-->
		
		<!-- Skin CSS file -->
		<link id="skin_css" rel="stylesheet" href="assets/css/skins/default.css">
		

	</head>
	<body>

		
		<div class="pageWrapper animsition">
			
			<header class="top-head transparent transparent-3" data-sticky="true">
		    	<div class="container fluid-header">
			    	<!-- Logo start -->
			    	<div class="logo">
				    	<a href="index.php"><img alt="" src="images/logolsp.png" /></a>
				    </div>
				    <!-- Logo end -->
				    
					<div class="f-right responsive-nav">
						<!-- top navigation menu start -->
						<nav class="top-nav nav-animate to-bottom">
							<ul>
							<?php
							$sqlgetmenu="SELECT * FROM `menu` WHERE `menu_induk`='NULL'";
							$getmenu=$conn->query($sqlgetmenu);
							$nomu=1;
							while ($mu=$getmenu->fetch_assoc()){
								echo "<li><a href='$mu[link]'>$mu[nama_menu]</a>";
								$sqlsubmenu0="SELECT * FROM `menu` WHERE `menu_induk`='$mu[id]'";
								$submenu0=$conn->query($sqlsubmenu0);
								$jumsub=$submenu0->num_rows;
								if ($jumsub>0){
									echo "<ul>";
									$sqlsubmenu="SELECT * FROM `menu` WHERE `menu_induk`='$mu[id]'";
									$submenu=$conn->query($sqlsubmenu);
									while ($smu=$submenu->fetch_assoc()){
										echo "<li><a href='$smu[link]'>$smu[nama_menu]</a></li>";

									}
									echo "</ul>";
								}
								echo "</li>";
								$nomu++;
							}
							?>

							</ul>
						</nav>
						<!-- top navigation menu end -->
						    
					    <div class="f-right">
					    	<!-- top search start -->
						    <div class="top-search">
					    		<a href="#" class="main-color"><span class="fa fa-search"></span></a>
						    	<div class="search-box">
					    			<input type="text" name="t" placeHolder="Ketik dan tekan Enter..." />
						    	</div>
						    </div>
						    <!-- top search end -->

						    
					    </div>
					    
					</div>
		    	</div>
		    </header>
		    		    
		    <div id="contentWrapper">
		    
		    	<div class="pageContent">
		    		
		    		<div class="page-title title-1">
						<div class="container">
							<div class="row">
								<br /><br /><br />
								<h1>Pendaftaran Peserta (Asesi)</h1>
								<h3>Formulir pendaftaran akun peserta (asesi) uji kompetensi sertifikasi profesi</h3>
								
								<div class="breadcrumbs">
									<a href="index.php">Beranda</a><i class="fa fa-long-arrow-right main-color"></i><span>Pendaftaran Pengusul</span>
								</div>
								
							</div>
						</div>
					</div>
					
					<div class="section">
						<div class="container">
							<div class="row">
								<div class="col-md-7 contact-form">
				    				<div class="heading">
										<h3 class="uppercase head-6"><span class="main-color">Pendaftaran</span> Akun Asesi</h3>
									</div>
									<?php
		                		$sql="SELECT * FROM `data_wilayah`";
						echo "<form action='pendaftaran.php' method='post'>
							<div class='form-input'>
				    						<label>Nama Lengkap<span class='red'>*</span></label><input type='text' name='nama' placeholder='Nama Lengkap' class='form-control shape' required>
							</div>
							<div class='form-input'>
				    						<label>Alamat Email<span class='red'></span></label><input type='email' name='email' placeholder='Alamat Email' class='form-control shape' >
							</div>
							<div class='form-input'>
				    						<label>Nomor HP<span class='red'>*</span></label><input type='text' name='no_hp' placeholder='Nomor HP' maxlength='14' class='form-control shape' required>
							</div>
							<div class='form-input'>
				    						<label>Nomor KTP/NIK<span class='red'>*</span></label><input type='text' name='no_ktp' placeholder='Nomor KTP/NIK' class='form-control shape' maxlength='16' required>
							</div>
							<div class='form-input'>
				    						<label>Alamat<span class='red'>*</span></label><input type='text' name='alamat' placeholder='Alamat' class='form-control shape' required>
							
							<select name='propinsi' class='form-control shape'  id='propinsi'>
								<option>Pilih Provinsi</option>";
							$sql="SELECT * FROM  `data_wilayah` WHERE  id_level_wil='1' AND id_induk_wilayah!='NULL' ORDER BY id_wil ASC";
							$tampil=$conn->query($sql);
							while($rr=$tampil->fetch_assoc()){
								echo "<option value='$rr[id_wil]'>$rr[nm_wil]</option>";
							}
							echo"</select>
							<select name='kota' class='form-control shape' id='kota'>";
							echo"<option>Pilih Kota/Kabupaten</option>";
							echo"</select>
							<select name='kecamatan' class='form-control shape' id='kecamatan'>";
							echo"<option>Pilih Kecamatan</option>";
							echo"</select>
							<select name='kelurahan' class='form-control shape' id='kelurahan'>";
							echo"<option>Pilih Kelurahan</option>";
							echo"</select>";
							// <div class='form-input'>
				    		// 		<label>Di wilayah mana Anda menginginkan Uji Kompetensi?<span class='red'>*</span></label>
							// 	<select name='wil_ujikom' class='form-control shape'  id='wil_ujikom'>
							// 	<option>Pilih Wilayah</option>";
							// 	$sqlwilukom="SELECT DISTINCT `id_wilayah` FROM  `tuk` ORDER BY `id_wilayah` ASC";
							// 	$tampilukom=$conn->query($sqlwilukom);
							// 	while($rwu=$tampilukom->fetch_assoc()){
							// 		$sqlgetkotwil="SELECT * FROM `data_wilayah` WHERE `id_wil`='$rwu[id_wilayah]'";
							// 		$getkotwil=$conn->query($sqlgetkotwil);
							// 		$kotwil=$getkotwil->fetch_assoc();
							// 		$sqlgetkotwil2="SELECT * FROM `data_wilayah` WHERE `id_wil`='$kotwil[id_induk_wilayah]'";
							// 		$getkotwil2=$conn->query($sqlgetkotwil2);
							// 		$kotwil2=$getkotwil2->fetch_assoc();
							// 		echo "<option value='$kotwil2[id_wil]'>$kotwil2[nm_wil]</option>";
							// 	}
							// 	echo"</select>
							// </div>
							echo "
							<img src='captcha.php' width='100%' height='70' border='1' alt='CAPTCHA'>
							<input required type='text' name='kode' class='form-control shape' placeholder='Kode Keamanan'>
							</div>

							<input type='submit' value='DAFTAR' class='btn btn-md main-bg shape'>
						</form>";
									?>

				    			</div>
				    			
					    		<div class="col-md-5">
									
									<div class="msg-box warning-box shape">
										<h4>Anda Perlu Tahu:</h4>
										<p>Peserta uji kompetensi dapat secara mandiri melakukan pendaftaran melalui laman ini. Setelah melakukan pendaftaran, periksa inbox (kotak masuk) email anda, bila tidak terdapat pada inbox email anda silahkan periksa di SPAM email anda</p>
									</div>
									
									<p>Akun peserta hanya satu untuk satu Nomor KTP/NIK. Akun dapat digunakan untuk mendaftar pada satu atau lebih skema uji kompetensi</p>
					    			
					    			<div class="panel-group accordion style-3 shape" id="accordion-5" role="tablist">

									<?php
									$nofq=1;
									$sqlgetkategoriid="SELECT * FROM `frontpage_kategori` WHERE `kategori`='faq'";
									$getkategoriid=$conn->query($sqlgetkategoriid);
									$katid=$getkategoriid->fetch_assoc();

									$sqlgetfaq="SELECT * FROM `frontpage` WHERE `kategori`='$katid[id]'";
									$getfaq=$conn->query($sqlgetfaq);
									while($fq=$getfaq->fetch_assoc()){
										echo "<div class='panel'>
											<div class='panel-heading' role='tab' id='heading-$nofq'>
												<h4 class='panel-title'>
													<a role='button' class='shape' data-toggle='collapse' data-parent='#accordion-5' href='#acc-$nofq' aria-expanded='true' aria-controls='acc-$nofq'><i class='fa fa-desktop main-color'></i>$fq[judul]</a>
												</h4>
											</div>
											<div id='acc-1' class='panel-collapse collapse in' role='tabpanel' aria-labelledby='heading-$nofq'>
												<div class='panel-body'>
													<p>$fq[konten]</p>
												</div>
											</div>
										</div>";
									$nofq++;
									}
									?>

									</div>
									
				    			</div>
			    			
							</div>
						</div>
					</div>
					
					
					
			    </div>			    
		    	
		    	<!-- Footer start -->
			    <footer id="footWrapper">
			    	<div class="footer-top main-bg">
			    		<div class="container">
							<div id="twitter-feed" class="slick-s shape"></div>
			    		</div>
			    	</div>
			    	<div class="footer-middle">
					    <div class="container">
						    <div class="row">
						    	
						    	<!-- main menu footer cell start -->
							    <div class="col-md-4 first">
								    <h3>Menu</h3>
								    <ul class="menu-widget">
							<?php
							$sqlgetmenub="SELECT * FROM `menu`";
							$getmenub=$conn->query($sqlgetmenub);
							$nomub=1;
							while ($mub=$getmenub->fetch_assoc()){
								$sqlsubmenu0b="SELECT * FROM `menu` WHERE `menu_induk`='$mub[id]'";
								$submenu0b=$conn->query($sqlsubmenu0b);
								$jumsubb=$submenu0b->num_rows;
								if ($jumsubb==0){
									//while ($smub=$submenu0b->fetch_assoc()){
										echo "<li><a href='$mub[link]'>$mub[nama_menu]</a></li>";

									//}
								}
								echo "</li>";
								$nomub++;
							}
							?>
								    </ul>
							    </div>
							    <!-- main menu footer cell start -->
							    
							    <!-- Our Friends footer cell start -->
							    <!--<div class="col-md-3">
							    	<h3>Flickr Photos</h3>
							    	<p>You can checkout some of our amazing photos on flickr. </p>
							    	<ul id="flickrFeed" class="flickr-widget"></ul>
							    </div>-->
							    <!-- Our Friends footer cell start -->
							    
							    <!-- Useful Links footer cell start -->
							    <div class="col-md-4">
							    	<h3>Jadwal Asesmen Terkini</h3>
							    	<ul class="tags hover-effect">
									<?php
				$sqlgetkategoriid2="SELECT * FROM `jadwal_asesmen` ORDER BY `tgl_asesmen` DESC LIMIT 10";
				$getkategoriid2=$conn->query($sqlgetkategoriid2);
				$nobt=1;
				/*echo "<div style='overflow-x:auto;'>
				<table id='example2' class='table table-bordered table-striped'>
				<thead><tr><th>No.</th><th>Agenda Kegiatan Asesmen</th><th>Tempat (TUK)</th></tr></thead>
				<tbody>";*/
				while ($bt2=$getkategoriid2->fetch_assoc()){
					echo "<li class='shape sm'>&nbsp;";
					//echo "$nobt $bt2[nama_kegiatan]<br>";
					$sqlgetskema="SELECT * FROM `skema_kkni` WHERE `id`='$bt2[id_skemakkni]'";
					$getskema=$conn->query($sqlgetskema);
					$skema=$getskema->fetch_assoc();
					//echo "$skema[judul]";
					echo tgl_indo($bt2['tgl_asesmen'])."&nbsp;";
					//echo " Pukul $bt2[jam_asesmen]";
					$sqlgettuk="SELECT * FROM `tuk` WHERE `id`='$bt2[tempat_asesmen]'";
					$gettuk=$conn->query($sqlgettuk);
					$tuk=$gettuk->fetch_assoc();
					//echo "$tuk[nama]<br>$tuk[alamat]";
					
					echo "</li>";
					$nobt++;
				}

									?>
								    </ul>
							    </div>
							    <!-- Useful Links footer cell start -->
							    
							    <!-- Tags Cloud footer cell start -->
							    <div class="col-md-4 last contact-widget">
								    <h3>Hubungi Kami</h3>
								    <ul class="details">
								    	<li><i class="fa fa-map-marker shape"></i><span><span class="heavy-font">Kantor Pusat: </span>
						<?php echo "<h4>$lsp[nama]</h4>
						<p>$lsp[alamat]<br>";
							$sql1="SELECT * FROM  `data_wilayah` WHERE  `id_wil`='$lsp[id_wilayah]'";
							$tampil1=$conn->query($sql1);
							$wil1=$tampil1->fetch_assoc();
							$sql2="SELECT * FROM  `data_wilayah` WHERE  `id_wil`='$wil1[id_induk_wilayah]'";
							$tampil2=$conn->query($sql2);
							$wil2=$tampil2->fetch_assoc();
							$sql3="SELECT * FROM  `data_wilayah` WHERE  `id_wil`='$wil2[id_induk_wilayah]'";
							$tampil3=$conn->query($sql3);
							$wil3=$tampil3->fetch_assoc();
							echo "$wil1[nm_wil], $wil2[nm_wil], $wil3[nm_wil] 
							<span>Kode Pos: $lsp[kodepos]</span>
						</p>";
						?></span></li>
								    	<li><i class="fa fa-envelope shape"></i><span><span class="heavy-font">Email: </span><?php echo "$lsp[email]"; ?></span></li>
								    	<li><i class="fa fa-phone shape"></i><span><span class="heavy-font">Tel: </span><?php echo "$lsp[telepon]"; ?></span></li>
								    	<li><i class="fa fa-whatsapp shape"></i><span><span class="heavy-font">WhatsApp: </span><?php echo "$lsp[whatsapp]"; ?></span></li>
								    </ul>
							    </div>
							    <!-- Tags Cloud footer cell start -->
							    
							    <div class="clearfix margin-bottom-30"></div>
							    							    							    
						    </div>
						    
						    <!--<div class="bottom-md-footer">
							    
							    <div class="col-md-7">
								    <label>Follow Us On:</label>
								    <ul class="social-list">
									    <li><a data-toggle="tooltip" data-placement="top" title="Dribbble" href="#" class="fa fa-dribbble shape sm">dribbble</a></li>
									    <li><a data-toggle="tooltip" data-placement="top" title="Facebook" href="#" class="fa fa-facebook shape sm">facebook</a></li>
									    <li><a data-toggle="tooltip" data-placement="top" title="Linkedin" href="#" class="fa fa-linkedin shape sm">linkedin</a></li>
									    <li><a data-toggle="tooltip" data-placement="top" title="Skype" href="#" class="fa fa-skype shape sm">skype</a></li>
									    <li><a data-toggle="tooltip" data-placement="top" title="Twitter" href="#" class="fa fa-twitter shape sm">twitter</a></li>
									    <li><a data-toggle="tooltip" data-placement="top" title="Behance" href="#" class="fa fa-behance shape sm">twitter</a></li>
								    </ul>
							    </div>
						    	
							    
							    <div class="f-right col-md-5 nl">
							    	<form action="link-to-your-site" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" target="_blank" novalidate class="NL">
							    		
							    		<label>Subscribe to our NewsLetters:</label>
							    		
							    		<input class="form-control shape" type="email" value="" name="EMAIL" id="mce-EMAIL" placeholder="Enter your email here" required />
							    		
							    		<input type="submit" name="subscribe" id="mc-embedded-subscribe" class="btn main-bg shape" value="Subscribe" />
							    		
							    		<div class="hidden"><input type="text" name="name-of-the-hidden-field" value=""></div>
							    		
		   								 <div class="nl-note"><span></span></div>
		   								 
							    	</form>
							    </div>
							    
						    </div>-->
						    
					    </div>	
				    </div>
				    			    
				    <!-- footer bottom bar start -->
				    <div class="footer-bottom">
					    <div class="container">
				    		<div class="row">
					    		<!-- footer copyrights left cell -->
					    		<div class="copyrights col-md-5 first">� Copyrights <b class="main-color"><?php echo "$lsp[nama]";?></b> 2023. All rights reserved.</div>
					    						    		
					    		<!-- footer social links right cell start -->
							    <div class="col-md-7 last">
							    	<ul class="footer-menu f-right">
							<?php
							$sqlgetmenub2="SELECT * FROM `menu`";
							$getmenub2=$conn->query($sqlgetmenub2);
							$nomub2=1;
							while ($mub2=$getmenub2->fetch_assoc()){
								$sqlsubmenu0b2="SELECT * FROM `menu` WHERE `menu_induk`='$mub2[id]'";
								$submenu0b2=$conn->query($sqlsubmenu0b2);
								$jumsubb2=$submenu0b2->num_rows;
								if ($jumsubb2==0){
									//while ($smub2=$submenu0b2->fetch_assoc()){
										echo "<li><a href='$mub2[link]'>$mub2[nama_menu]</a></li>";

									//}
								}
								echo "</li>";
								$nomub2++;
							}
							?>
								    </ul>
							    </div>
							    <!-- footer social links right cell end -->
							    
				    		</div>
					    </div>
				    </div>
				    <!-- footer bottom bar end -->
				</footer>
		    	<!-- Footer end -->
		    </div>
		</div>
		
		    	
		<!-- Back to top Link -->
	    <a id="to-top"><span class="fa fa-chevron-up shape main-bg"></span></a>


	    <!-- Load JS plugins file -->
 		<script type="text/javascript" src="assets/js/assets.min.js"></script>
 		
 		<!-- SLIDER REVOLUTION  -->
		<script type="text/javascript" src="assets/revolution/js/jquery.themepunch.tools.min.js"></script>
		<script type="text/javascript" src="assets/revolution/js/jquery.themepunch.revolution.min.js"></script>
		
		<!-- SLIDER REVOLUTION 5.0 EXTENSIONS  
			(Load Extensions only on Local File Systems !  +
			The following part can be removed on Server for On Demand Loading) -->
		<script type="text/javascript" src="assets/revolution/js/extensions/revolution.extension.actions.min.js"></script>
		<script type="text/javascript" src="assets/revolution/js/extensions/revolution.extension.carousel.min.js"></script>
		<script type="text/javascript" src="assets/revolution/js/extensions/revolution.extension.kenburn.min.js"></script>
		<script type="text/javascript" src="assets/revolution/js/extensions/revolution.extension.layeranimation.min.js"></script>
		<script type="text/javascript" src="assets/revolution/js/extensions/revolution.extension.migration.min.js"></script>
		<script type="text/javascript" src="assets/revolution/js/extensions/revolution.extension.navigation.min.js"></script>
		<script type="text/javascript" src="assets/revolution/js/extensions/revolution.extension.parallax.min.js"></script>
		<script type="text/javascript" src="assets/revolution/js/extensions/revolution.extension.slideanims.min.js"></script>
		<script type="text/javascript" src="assets/revolution/js/extensions/revolution.extension.video.min.js"></script>
		<!-- END SLIDER REVOLUTION 5.0 EXTENSIONS -->
		<script type="text/javascript">
			var tpj=jQuery;			
			var revapi4;
			tpj(window).load(function() {
				if(tpj("#slider").revolution == undefined){
					revslider_showDoubleJqueryError("#rev_slider_4_1");
				}else{
					revapi4 = tpj("#slider").show().revolution({
						sliderType:"standard",
						jsFileLocation:"assets/revolution/js/",
						sliderLayout:"fullscreen",
						dottedOverlay:"none",
						delay:9000,
						navigation: {
							keyboardNavigation:"off",
							keyboard_direction: "horizontal",
							mouseScrollNavigation:"off",
							onHoverStop:"off",
							touch:{
								touchenabled:"on",
								swipe_threshold: 75,
								swipe_min_touches: 1,
								swipe_direction: "horizontal",
								drag_block_vertical: false
							}
							,
							arrows: {
								style:"zeus",
								enable:true,
								hide_onmobile:true,
								hide_under:600,
								hide_onleave:true,
								hide_delay:200,
								hide_delay_mobile:1200,
								tmp:'<div class="tp-title-wrap">  	<div class="tp-arr-imgholder"></div> </div>',
								left: {
									h_align:"left",
									v_align:"center",
									h_offset:30,
									v_offset:0
								},
								right: {
									h_align:"right",
									v_align:"center",
									h_offset:30,
									v_offset:0
								}
							}
						},
						viewPort: {
							enable:true,
							outof:"pause",
							visible_area:"80%"
						},
						responsiveLevels:[1240,1024,778,480],
						gridwidth:[1240,1024,778,480],
						gridheight:[600,600,500,400],
						lazyType:"none",
						parallax: {
							type:"mouse",
							origo:"slidercenter",
							speed:2000,
							levels:[2,3,4,5,6,7,12,16,10,50],
						},
						shadow:0,
						spinner:"off",
						stopLoop:"off",
						stopAfterLoops:-1,
						stopAtSlide:-1,
						shuffle:"off",
						autoHeight:"off",
						hideThumbsOnMobile:"off",
						hideSliderAtLimit:0,
						hideCaptionAtLimit:0,
						hideAllCaptionAtLilmit:0,
						debugMode:false,
						fallbacks: {
							simplifyAll:"off",
							nextSlideOnWindowFocus:"off",
							disableFocusListener:false,
						}
					});
					
					revapi4.bind("revolution.slide.onchange",function (e,data) {
					if($('.top-head').hasClass('transparent') || $('.top-head').hasClass('boxed-transparent')){
						if($('#slider ul > li').eq(data.slideIndex-1).hasClass('dark') ){
							$('.top-head').removeClass('not-dark');
							$('.top-head').addClass('dark');
							var logo = $('.logo').find('img').attr('src').replace("logo.png", "logo-light.png");
							$('.logo').find('img').attr('src',logo);
						} else {
							$('.top-head').removeClass('dark');
							$('.top-head').addClass('not-dark');
							var logo = $('.logo').find('img').attr('src').replace("logo-light.png", "logo.png");
							$('.logo').find('img').attr('src',logo);
						}
						if($('.top-head').hasClass('sticky-nav')){
							var logo = $('.logo').find('img').attr('src').replace("logo-light.png", "logo.png");
							$('.logo').find('img').attr('src',logo);
						}
					}
	            });
				}
			});	/*ready*/
		</script>

<!-- Script untuk Mengontrol Dropdown List Kode Perguruan Tinggi-->
<script type="text/javascript">
var htmlobjek;
$(document).ready(function(){
//apabila terjadi event onchange terhadap object <select id=kodept>
$("#kodept").change(function(){
var kodept = $("#kodept").val();
$.ajax({
url: "config/getkodept.php",
data: "kodept="+kodept,
cache: false,
success: function(msg){
//jika data sukses diambil dari server kita tampilkan
//di <select id=prodipt>
$("#prodipt").html(msg);
}
});
});


$("#prodipt").change(function(){
var prodipt = $("#prodipt").val();
$.ajax({
url: "config/getsubprodipt.php",
data: "prodipt="+prodipt,
cache: false,
success: function(msg){
//jika data sukses diambil dari server kita tampilkan
//di <select id=subprodipt>
$("#subprodipt").html(msg);
}
});
});
});


var htmlobjek;
$(document).ready(function(){
//apabila terjadi event onchange terhadap object <select id=propinsi>
	$("#propinsi").change(function(){
	  var propinsi = $("#propinsi").val();
	  $.ajax({
		url: "config/getkota.php",
		data: "propinsi="+propinsi,
		cache: false,
		success: function(msg){
			//jika data sukses diambil dari server kita tampilkan
			//di <select id=kota>
			$("#kota").html(msg);
		}
	  });
	});

	$("#kota").change(function(){
	  var kota = $("#kota").val();
	  $.ajax({
		url: "config/getkecamatan.php",
		data: "kota="+kota,
		cache: false,
		success: function(msg){
			//jika data sukses diambil dari server kita tampilkan
			//di <select id=kecamatan>
			$("#kecamatan").html(msg);
		}
	  });
	});

	$("#kecamatan").change(function(){
	  var kecamatan = $("#kecamatan").val();
	  $.ajax({
		url: "config/getkelurahan.php",
		data: "kecamatan="+kecamatan,
		cache: false,
		success: function(msg){
			//jika data sukses diambil dari server kita tampilkan
			//di <select id=kelurahan>
			$("#kelurahan").html(msg);
		}
	  });
	});
});


var htmlobjek;
$(document).ready(function(){
//apabila terjadi event onchange terhadap object <select id=skkni>
$("#skkni").change(function(){
var skkni = $("#skkni").val();
$.ajax({
url: "config/getunitkompetensi.php",
data: "skkni="+skkni,
cache: false,
success: function(msg){
//jika data sukses diambil dari server kita tampilkan
//di <select id=unitkompetensi>
$("#unitkompetensi").html(msg);
}
});
});


$("#unitkompetensi").change(function(){
var unitkompetensi = $("#unitkompetensi").val();
$.ajax({
url: "config/getelemenkompetensi.php",
data: "unitkompetensi="+unitkompetensi,
cache: false,
success: function(msg){
//jika data sukses diambil dari server kita tampilkan
//di <select id=elemenkompetensi>
$("#elemenkompetensi").html(msg);
}
});
});
});

</script>
<!-- //js-scripts -->
				
		<!-- general script file -->
		<script type="text/javascript" src="assets/js/script.js"></script>
		
	</body>
</html>