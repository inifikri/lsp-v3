<!DOCTYPE html>
<html>
	<?php
		include "config/koneksi.php";
		include "config/fungsi_indotgl.php";
		error_reporting(E_ALL);

	?>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>
		<?php 
			$sqlgetidentitas="SELECT * FROM `lsp` ORDER BY `id` DESC LIMIT 1";
			$getidentitas=$conn->query($sqlgetidentitas);
			$lsp=$getidentitas->fetch_assoc();
			echo"Tentang $lsp[nama]";
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
								<br /><br /><br /><h1>Tentang Kami</h1>
								<h3>Profil <?php echo "$lsp[nama]"?></h3>
								
								<div class="breadcrumbs">
									<a href="index.php">Beranda</a><i class="fa fa-long-arrow-right main-color"></i><span>Tentang Kami</span>
								</div>
								
							</div>
						</div>
					</div>
					<div class="section">
						<div class="container">
							<div class="row">
								<?php
								$nofq=1;
								$sqlgetkategoriid="SELECT * FROM `frontpage_kategori` WHERE `kategori`='profil-lsp'";
								$getkategoriid=$conn->query($sqlgetkategoriid);
								$katid=$getkategoriid->fetch_assoc();

								$sqlgetfaq="SELECT * FROM `frontpage` WHERE `kategori`='$katid[id]' ORDER BY `sub_judul` ASC";
								$getfaq=$conn->query($sqlgetfaq);
								while($fq=$getfaq->fetch_assoc()){
								   echo "<div class='col-md-6'>
									<img alt='' src='foto_konten/$fq[konten_foto]' class='fx' data-animate='fadeInLeft'>
								   </div>
								   <div class='col-md-6 vertical-icons'>
									<div class='heading sub-head'>
										<h3 class='head-4 uppercase font-30'><span class='main-color'>$fq[judul]</span></h3>
									</div>
									<p class='sub-heading'>$fq[konten]</p>
								   </div>";
								$nofq++;
								}
								?>

							</div>
						</div>
					</div>

					<div class="parallax" style="background:transparent url('assets/images/bgs/sec-bg-01.jpg')" data-stellar-background-ratio="0.6">
						<div class="container">
							<div class="row row-eq-height">
								
								<div class="col-md-3 padding-vertical-80">
									<div class="section-full-bg left black-bg"></div>
									<div class="heading sub-head">
										<h3 class="head-4 uppercase white">Kinerja <span class="main-color">Kami</span></h3>
									</div>
									<p class="white relative">Kami telah melaksanakan kegiatan asesmen uji kompetensi, continous improvement, dan pelayanan sertifikasi kepada masyarakat, dengan serangkaian pengalaman kami.</p>
								</div>
								<?php
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

								?>
								<div class="col-md-9 padding-vertical-80">
									<div class="row">
										<div class="col-md-3">
											<div class="c-chart bottom-txt" data-dimension="150" data-text="" data-icon="fa-users" data-iconsize="30" data-iconcolor="#0090ff" data-info="" data-width="5" data-fontsize="35" data-percent="<?php echo "$jumasesi" ?>" data-fgcolor="#0090ff" data-bgcolor="transparent" data-fill="transparent"></div>
											<h3 class="fun-number t-center odometer main-color" data-initial="0" data-value="<?php echo "$jumasesi" ?>" data-timer="500">0</h3>
											<div class="fun-info t-center">Asesi</div>
										</div>
										
										<div class="col-md-3">
											<div class="c-chart bottom-txt" data-dimension="150" data-text="" data-icon="fa-umbrella" data-iconsize="30" data-iconcolor="#f358db" data-info="" data-width="5" data-fontsize="35" data-percent="<?php echo "$jumskema" ?>" data-fgcolor="#f358db" data-bgcolor="transparent" data-fill="transparent"></div>
											<h3 class="fun-number t-center odometer main-color" data-initial="0" data-value="<?php echo "$jumskema" ?>" data-timer="500">0</h3>
											<div class="fun-info t-center">Skema</div>
										</div>
										
										<div class="col-md-3">
											<div class="c-chart bottom-txt" data-dimension="150" data-text="" data-icon="fa-clock-o" data-iconsize="30" data-iconcolor="#49d103" data-info="" data-width="5" data-fontsize="35" data-percent="<?php echo "$jumasesor" ?>" data-fgcolor="#49d103" data-bgcolor="transparent" data-fill="transparent"></div>
											<h3 class="fun-number t-center odometer main-color" data-initial="0" data-value="<?php echo "$jumasesor" ?>" data-timer="500">0</h3>
											<div class="fun-info t-center">Asesor</div>
										</div>
										
										<div class="col-md-3">
											<div class="c-chart bottom-txt" data-dimension="150" data-text="" data-icon="fa-coffee" data-iconsize="30" data-iconcolor="#ec8a35" data-info="" data-width="5" data-fontsize="35" data-percent="<?php echo "$jumevent" ?>" data-fgcolor="#ec8a35" data-bgcolor="transparent" data-fill="transparent"></div>
											<h3 class="fun-number t-center odometer main-color" data-initial="0" data-value="<?php echo "$jumevent" ?>" data-timer="500">0</h3>
											<div class="fun-info t-center">Uji Kompetensi</div>
										</div>
										
									</div>
								</div>
								
							</div>
						</div>
					</div>
					<div class="section">
						<div class="container">
							<div class="row">
								
								<div class="heading main-heading centered">
									<h3>Struktur Organisasi</h3>
									<h4 class="sub-title"><span class="main-color"><?php echo "$lsp[nama]" ?></span></h4>
									<div class="heading-separator"><span class="main-bg"></span><span class="dark-bg"></span></div>
								</div>
								<p class="heading-desc centered">Dalam rangka memberikan pelayanan yang prima kepada publik, kami didukung dengan tim profesional dalam struktur organisasi sebagai berikut.</p>
								
								
								<?php
								$nostr=1;
								$sqlgetstruktur="SELECT * FROM `frontpage_kategori` WHERE `kategori`='struktur-lsp'";
								$getstruktur=$conn->query($sqlgetstruktur);
								$strid=$getstruktur->fetch_assoc();

								$sqlgetstr="SELECT * FROM `frontpage` WHERE `kategori`='$strid[id]'";
								$getstr=$conn->query($sqlgetstr);

								while($struk=$getstr->fetch_assoc()){
								   $kontentampilstruktur=substr($struk['konten'],0,200);
								   echo "<div class='col-md-3'>
									<div class='team-box box-4 shape'>
										<div class='team-img'>
											<img alt='$struk[judul]' src='foto_konten/$struk[konten_foto]' width='300px' height='400px'/>
										</div>
										<div class='team-details main-bg'>
											<h3 class='team-name'>$struk[judul]</h3>
											<h5 class='team-pos white'>$struk[sub_judul]</h5>
											<p>$kontentampilstruktur... <br/><a class='more_btn' href='blog-single.php?pid=$struk[id]'>Selengkapnya >></a></p>
										</div>
									</div>
								   </div>";
								$nostr++;
								}

								?>
							</div>
						</div>
					</div>

					
					
					
			    </div>			    

					<!--<div class="section">
						<div class="container">
							<div class="row">
								<div class="col-md-12">
								    <div class="panel-group accordion style-4" id="accordion-5" role="tablist">
									<?php
									$nofq=1;
									$sqlgetkategoriid="SELECT * FROM `frontpage_kategori` WHERE `kategori`='profil-lsp'";
									$getkategoriid=$conn->query($sqlgetkategoriid);
									$katid=$getkategoriid->fetch_assoc();

									$sqlgetfaq="SELECT * FROM `frontpage` WHERE `kategori`='$katid[id]' ORDER BY `sub_judul` ASC";
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
					</div>-->					
					
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
								    	<li><i class="fa fa-whatsapp shape"></i><span><span class="heavy-font">WhatsApp: </span><?php echo "$lsp[wa]"; ?></span></li>
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
					    		<div class="copyrights col-md-5 first">© Copyrights <b class="main-color"><?php echo "$lsp[nama]";?></b> 2019. All rights reserved.</div>
					    						    		
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
				
		<!-- general script file -->
		<script type="text/javascript" src="assets/js/script.js"></script>
		
	</body>
</html>