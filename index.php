<!DOCTYPE html>

<html>

<?php

include "config/koneksi.php";

include "config/fungsi_indotgl.php";

ini_set('display_errors',1); 

error_reporting(E_ALL);
?>

<head>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

	<title>

		<?php

		$sqlgetidentitas = "SELECT * FROM `lsp` ORDER BY `id` DESC LIMIT 1";

		$getidentitas = $conn->query($sqlgetidentitas);

		$lsp = $getidentitas->fetch_assoc();

		echo "$lsp[nama]";

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
	<?php
	// UPDATE @FHM-PPM 28 JULY 2023 : PENAMBAHAN FUNGSI base_url()
	if (!function_exists('base_url')) {
		function base_url($atRoot = FALSE, $atCore = FALSE, $parse = FALSE)
		{
			return sprintf(
				"%s://%s%s",
				isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
				$_SERVER['SERVER_NAME'],
				$_SERVER['REQUEST_URI']
			  );
			return $base_url;
		}
	}
	$base_url = base_url();
	// END UPDATE

	?>




	<div class="pageWrapper animsition">



		<header class="top-head transparent transparent-3" data-sticky="true">

			<div class="container fluid-header">

				<!-- Logo start -->

				<div class="logo">

					<a href="index.php"><img alt="" src="/images/logolsp.png" /></a>

				</div>

				<!-- Logo end -->



				<div class="f-right responsive-nav">

					<!-- top navigation menu start -->

					<nav class="top-nav nav-animate to-bottom">

						<ul>

							<?php

							$sqlgetmenu = "SELECT * FROM `menu` WHERE `menu_induk`='NULL'";

							$getmenu = $conn->query($sqlgetmenu);

							$nomu = 1;

							while ($mu = $getmenu->fetch_assoc()) {

								echo "<li><a href='$mu[link]'>$mu[nama_menu]</a>";

								$sqlsubmenu0 = "SELECT * FROM `menu` WHERE `menu_induk`='$mu[id]'";

								$submenu0 = $conn->query($sqlsubmenu0);

								$jumsub = $submenu0->num_rows;

								if ($jumsub > 0) {

									echo "<ul>";

									$sqlsubmenu = "SELECT * FROM `menu` WHERE `menu_induk`='$mu[id]'";

									$submenu = $conn->query($sqlsubmenu);

									while ($smu = $submenu->fetch_assoc()) {

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



						<!-- cart start -->

						<!--<div class="top-cart">

					    		<a href="#" class="white"><span class="fa fa-shopping-cart"></span><i class="cart-num main-bg white">3</i></a>

						    	<div class="cart-box">

						    		<div class="empty hidden">Your shopping cart is empty!</div>

										<div class="mini-cart">

											<ul class="mini-cart-list">

												<li>

													<a class="cart-mini-lft" href="product-left-bar.html"><img src="assets/images/shop/pro-1.jpg" alt=""></a>

													<div class="cart-body">

														<a href="product-left-bar.html">Ultimate Fashion Wear White</a>

														<div class="price">$150</div>

													</div>

													<a class="remove" href="#"><i class="fa fa-times" title="Remove"></i></a>

												</li>

												<li>

													<a class="cart-mini-lft" href="product-left-bar.html"><img src="assets/images/shop/pro-2.jpg" alt=""></a>

													<div class="cart-body">

														<a href="product-left-bar.html">Fashion Wear White</a>

														<div class="price">$50</div>

													</div>

													<a class="remove" href="#"><i class="fa fa-times" title="Remove"></i></a>

												</li>

												<li>

													<a class="cart-mini-lft" href="product-left-bar.html"><img src="assets/images/shop/pro-3.jpg" alt=""></a>

													<div class="cart-body">

														<a href="product-left-bar.html">Ultimate Fashion</a>

														<div class="price">$220</div>

													</div>

													<a class="remove" href="#"><i class="fa fa-times" title="Remove"></i></a>

												</li>

											</ul>

											<div class="mini-cart-total">

												<div class="clearfix">

													<div class="f-left">Sub-Total:</div>

													<div class="f-right">$230.00</div>

												</div>

												<div class="clearfix">

													<div class="f-left">Tax (-10.00):</div>

													<div class="f-right">$12.05</div>

												</div>

												<div class="clearfix total">

													<div class="f-left"><strong>Total:</strong></div>

													<div class="f-right">$200.20</div>

												</div>

											</div>

											<div class="checkout">

												<a class="btn main-bg" href="cart.html">View Cart</a><a class="btn btn-default" href="checkout.html">Checkout</a>

											</div>

										</div>

						    	</div>

						    </div>-->

						<!-- cart end -->

					</div>



				</div>

			</div>

		</header>



		<div id="contentWrapper">



			<div id="slider_wrapper" class="rev_slider_wrapper fullscreen-container" data-alias="photography1" style="background-color:transparent;padding:0px;">

				<!-- START REVOLUTION SLIDER 5.0.7 fullscreen mode -->

				<div id="slider" class="rev_slider fullscreenbanner" style="display:none;" data-version="5.0.7">

					<ul> <!-- SLIDE  -->





						<?php

						$sqlgetkategoriid0s = "SELECT * FROM `frontpage_kategori` WHERE `kategori`='slidebanner'";

						$getkategoriid0s = $conn->query($sqlgetkategoriid0s);

						$katid0s = $getkategoriid0s->fetch_assoc();

						$sqlgetkontenberita0s = "SELECT * FROM `frontpage` WHERE `kategori`='$katid0s[id]' ORDER BY `tanggal_terbit` ASC LIMIT 3";

						$getkontenberita0s = $conn->query($sqlgetkontenberita0s);

						while ($bt0s = $getkontenberita0s->fetch_assoc()) {

							echo "<li data-index='rs-214' class='dark' data-transition='fade' data-slotamount='7'  data-easein='default' data-easeout='default' data-masterspeed='300'  data-rotate='0'  data-saveperformance='off'  data-title='Slide' data-description=''>

									<!-- MAIN IMAGE -->

									<img src='foto_konten/$bt0s[konten_foto]'  alt=''  data-bgposition='center center' data-bgfit='cover' data-bgrepeat='no-repeat' data-bgparallax='10' class='rev-slidebg' data-no-retina>

									<!-- LAYERS -->



									<!-- LAYER NR. 1 -->

									<!-- <div class='tp-caption Sports-Display uppercase tp-resizeme rs-parallaxlevel-0' 

										id='slide-214-layer-1' 

										data-x='['left','left','left','left']' data-hoffset='['30','30','30','30']' 

										data-y='['top','top','top','top']' data-voffset='['40','40','40','20']' 

										data-fontsize='['30','30','30','20']'

										data-lineheight='['30','30','30','20']'

										data-width='none'

										data-height='none'

										data-whitespace='nowrap'

										data-transform_idle='o:1;'

										data-transform_in='x:[-100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;s:1500;e:Power3.easeInOut;' 

										data-transform_out='s:1000;e:Power3.easeInOut;s:1000;e:Power3.easeInOut;' 

										data-mask_in='x:0px;y:0px;s:inherit;e:inherit;' 

										data-start='500' 

										data-splitin='none' 

										data-splitout='none' 

										data-responsive_offset='on' 									

										style='z-index: 5; white-space: nowrap;color:#fff;letter-spacing:3px'>Selamat Datang

									</div> -->



									<!-- LAYER NR. 2 -->

									<!-- <div class='tp-caption main-color uppercase heavy-font tp-resizeme rs-parallaxlevel-0' 

										id='slide-214-layer-2' 

										data-x='['left','left','left','left']' data-hoffset='['30','30','30','30']' 

										data-y='['top','top','top','top']' data-voffset='['70','70','70','40']' 

										data-fontsize='['80','80','80','60']'

										data-lineheight='['80','80','80','60']'

										data-width='none'

										data-height='none'

										data-whitespace='nowrap'

										data-transform_idle='o:1;'

										data-transform_in='x:[-105%];z:0;rX:45deg;rY:0deg;rZ:90deg;sX:1;sY:1;skX:0;skY:0;s:2000;e:Power4.easeInOut;' 

										data-transform_out='s:1000;e:Power2.easeInOut;s:1000;e:Power2.easeInOut;' 

										data-mask_in='x:0px;y:0px;s:inherit;e:inherit;' 

										data-start='750' 

										data-splitin='chars' 

										data-splitout='none' 

										data-elementdelay='0.05'

										data-responsive_offset='on' 									

										style='z-index: 6; font-family: Raleway;white-space: nowrap'> 

									</div> -->

									

									<!-- LAYER NR. 3 -->

									<!-- <div class='tp-caption sfb tp-resizeme gry-sep' data-x='20' data-y='160' data-start='1500'

										data-speed='600'

										data-easing='easeOutQuad'

										data-splitin='none'

										data-splitout='none'

										data-elementdelay='0.01'

										data-endelementdelay='0.1'

										data-endspeed='1000'

										data-endeasing='Power4.easeIn'>

									</div>



									<div class='tp-caption lg-list-item tp-resizeme white' data-x='70' data-y='180' data-start='2400'

										data-transform_idle='o:1;'

										data-transform_in='x:-50px;skX:100px;opacity:0;s:1000;e:Power4.easeInOut;' 

										data-transform_out='s:1000;e:Power2.easeInOut;s:1000;e:Power2.easeInOut;'

										data-splitin='none'

										data-splitout='none'

										data-elementdelay='0.01'

										data-endelementdelay='0.1'

										data-endspeed='1000'

										data-endeasing='Power4.easeIn'><i class='fa fa-bank main-color'></i>di $lsp[nama]

									</div>

									

									<div class='tp-caption ltl tp-resizeme gry-sep' data-x='70' data-y='250' data-start='2400'

										data-transform_in='x:-50px;skX:100px;opacity:0;s:1000;e:Power4.easeInOut;' 

										data-transform_out='s:1000;e:Power2.easeInOut;s:1000;e:Power2.easeInOut;'

										data-easing='easeOutQuad'

										data-splitin='none'

										data-splitout='none'

										data-elementdelay='0.01'

										data-endelementdelay='0.1'

										data-endspeed='1000'

										data-endeasing='Power4.easeIn'>

									</div>

									

									<div class='tp-caption customin ltl lg-list-item tp-resizeme white' data-x='120' data-y='270' data-start='2700'

										data-transform_in='x:-50px;skX:100px;opacity:0;s:1000;e:Power4.easeInOut;' 

										data-transform_out='s:1000;e:Power2.easeInOut;s:1000;e:Power2.easeInOut;'

										data-easing='Back.easeOut'

										data-splitin='none'

										data-splitout='none'

										data-elementdelay='0.01'

										data-endelementdelay='0.1'

										data-endspeed='1000'

										data-endeasing='Power4.easeIn'><i class='fa fa-check-square-o main-color'></i>Peserta Uji Kompetensi

									</div>

									

									<div class='tp-caption customin ltl tp-resizeme gry-sep' data-x='120' data-y='340' data-start='2700'

										data-transform_in='x:-50px;skX:100px;opacity:0;s:1000;e:Power4.easeInOut;' 

										data-transform_out='s:1000;e:Power2.easeInOut;s:1000;e:Power2.easeInOut;'

										data-speed='600'

										data-easing='easeOutQuad'

										data-splitin='none'

										data-splitout='none'

										data-elementdelay='0.01'

										data-endelementdelay='0.1'

										data-endspeed='1000'

										data-endeasing='Power4.easeIn'>

									</div>

									

									<div class='tp-caption customin ltl lg-list-item tp-resizeme white' data-x='170' data-y='360' data-start='3000'

										data-transform_in='x:-50px;skX:100px;opacity:0;s:1000;e:Power4.easeInOut;' 

										data-transform_out='s:1000;e:Power2.easeInOut;s:1000;e:Power2.easeInOut;'

										data-speed='600'

										data-easing='Back.easeOut'

										data-splitin='none'

										data-splitout='none'

										data-elementdelay='0.01'

										data-endelementdelay='0.1'

										data-endspeed='1000'

										data-endeasing='Power4.easeIn'><i class='fa fa-puzzle-piece main-color'></i>Asesor

									</div>

									

									<div class='tp-caption customin ltl tp-resizeme gry-sep' data-x='170' data-y='430' data-start='3000'

										data-transform_in='x:-50px;skX:100px;opacity:0;s:1000;e:Power4.easeInOut;' 

										data-transform_out='s:1000;e:Power2.easeInOut;s:1000;e:Power2.easeInOut;'

										data-speed='600'

										data-easing='easeOutQuad'

										data-splitin='none'

										data-splitout='none'

										data-elementdelay='0.01'

										data-endelementdelay='0.1'

										data-endspeed='1000'

										data-endeasing='Power4.easeIn'>

									</div>

									

									<div class='tp-caption customin ltl lg-list-item tp-resizeme white' data-x='220' data-y='450' data-start='3300'

										data-transform_in='x:-50px;skX:100px;opacity:0;s:1000;e:Power4.easeInOut;' 

										data-transform_out='s:1000;e:Power2.easeInOut;s:1000;e:Power2.easeInOut;'

										data-speed='600'

										data-easing='Back.easeOut'

										data-splitin='none'

										data-splitout='none'

										data-elementdelay='0.01'

										data-endelementdelay='0.1'

										data-endspeed='1000'

										data-endeasing='Power4.easeIn'><i class='fa fa-chevron-circle-right main-color'></i>Manajemen

									</div>

									

									<div class='tp-caption customin ltl tp-resizeme gry-sep' data-x='220' data-y='520' data-start='3300'

										data-transform_in='x:-50px;skX:100px;opacity:0;s:1000;e:Power4.easeInOut;' 

										data-transform_out='s:1000;e:Power2.easeInOut;s:1000;e:Power2.easeInOut;'

										data-speed='600'

										data-easing='easeOutQuad'

										data-splitin='none'

										data-splitout='none'

										data-elementdelay='0.01'

										data-endelementdelay='0.1'

										data-endspeed='1000'

										data-endeasing='Power4.easeIn'>

									</div> -->

									

									<!--<div class='tp-caption customin ltl tp-resizeme uppercase' data-x='340' data-y='570' data-start='3600'

										data-transform_in='x:-50px;skX:100px;opacity:0;s:1000;e:Power4.easeInOut;' 

										data-transform_out='s:1000;e:Power2.easeInOut;s:1000;e:Power2.easeInOut;'

										data-speed='600'

										data-easing='Back.easeOut'

										data-splitin='none'

										data-splitout='none'

										data-elementdelay='0.01'

										data-endelementdelay='0.1'

										data-endspeed='1000'

										data-endeasing='Power4.easeIn'><a class='btn btn-xl main-bg shape' style='white-space:nowrap' href='#'>Daftar Online</a>

									</div>-->



									

								</li>";
						}



						?>





					</ul>

					<div class="tp-bannertimer tp-bottom" style="visibility: hidden !important;"></div>

				</div>

			</div>



			<div class="padding-vertical-40 main-bg">

				<div class="container">

					<div class="cta_btn">



						<div class="cta-icon md-icon f-left">

							<i class="fa fa-check shape white-bg main-color"></i>

						</div>



						<div class="f-left">

							<h2 class="cta_heading uppercase bold white"><span class="black-color uppercase bolder">Sertifikasi Profesi</span> <?php echo "$lsp[nama]"; ?></h2>

							<h4 class="cta_heading white">Hubungi Kami di <span class="black-color"><?php echo "$lsp[telepon]"; ?></span> dan dapatkan layanan sertifikasi dari kami.</h4>

						</div>



						<a class="btn btn-xl btn-outlined btn-white f-right shape" href="tel:<?php echo "$lsp[telepon]"; ?>">Hubungi Kami</a>



					</div>

				</div>

			</div>



			<div class="section">

				<div class="container">

					<div class="row">

						<div class="col-md-6">

							<?php

							$sqlgetcontent = "SELECT * FROM `frontpage` WHERE `kategori`='12' AND `konten_foto`!='' ORDER BY RAND() LIMIT 1";

							$getcontent = $conn->query($sqlgetcontent);

							$cf = $getcontent->fetch_assoc();

							?>

							<img alt="" src="foto_konten/<?php echo $cf['konten_foto']; ?>" class="fx" data-animate="fadeInLeft">

						</div>

						<div class="col-md-6 vertical-icons">

							<div class="heading sub-head">

								<h3 class="uppercase head-4"><span class="main-color">Skema </span>Sertifikasi <?php echo $lsp['nama']; ?></h3>

								<p class="sub-heading">Skema kompetensi yang kami sediakan pada sertifikasi profesi di <?php echo $lsp['nama']; ?>.</p>

							</div>

							<ul class="list">

								<?php

								$sqlskkni = "SELECT * FROM `skema_kkni` WHERE `aktif`='Y' ORDER BY `judul` ASC";

								$skkni = $conn->query($sqlskkni);

								while ($sk = $skkni->fetch_assoc()) {

									echo "<li><a href='$base_url/detailskema.php?a=$sk[id]'><i class='fa fa-bell main-color'></i>$sk[judul]</a></li>";
								}





								?>

							</ul>

						</div>

					</div>

				</div>

			</div>



			<div class="padding-vertical-0">

				<div class="container">

					<div class="divider centered"><i class="fa fa-sun-o"></i></div>

				</div>

			</div>



			<div class="section">

				<div class="container">



					<?php

					$sqlgetkategoriidl = "SELECT * FROM `frontpage_kategori` WHERE `kategori`='layanan'";

					$getkategoriidl = $conn->query($sqlgetkategoriidl);

					$katidl = $getkategoriidl->fetch_assoc();

					$sqlgetkontenberital = "SELECT * FROM `frontpage` WHERE `kategori`='$katidl[id]'";

					$getkontenberital = $conn->query($sqlgetkontenberital);

					$nostr = 1;

					while ($struk = $getkontenberital->fetch_assoc()) {
						$kontentampilstruktur = substr($struk['konten'], 0, 200);
						echo "<div class='col-md-3'>
									<div class='team-box box-4 shape'>
										<div class='team-img'>
											<img alt='$struk[judul]' src='foto_konten/$struk[konten_foto]' width='300px' height='400px'/>
										</div>
										<div class='team-details main-bg'>
											<h3 class='team-name'>$struk[judul]</h3>
											<p>$kontentampilstruktur... <br/><a class='more_btn' href='blog-single.php?pid=$struk[id]'>Selengkapnya >></a></p>
										</div>
									</div>
								   </div>";
						$nostr++;
					}

					?>

					<!--<div class="col-md-4">

								<div class="icon-box-small">

									<i class="fa fa-leaf main-bg filled shape"></i>

									<div class="icon-sm-desc">

										<h3 class="bold uppercase">Clean, Valid HTML5 Code</h3>

										<p>Proin gravida nibh vel velit auctor enean sollicitud lorem quis bibendum auctor, nisi elit consequipsum.</p>

									</div>

								</div>

							</div>

							<div class="col-md-4">

								<div class="icon-box-small">

									<i class="fa fa-recycle main-bg filled shape"></i>

									<div class="icon-sm-desc">

										<h3 class="bold uppercase">Fully Customizable</h3>

										<p>Proin gravida nibh vel velit auctor enean sollicitud lorem quis bibendum auctor, nisi elit consequipsum.</p>

									</div>

								</div>

							</div>

							<div class="col-md-4">

								<div class="icon-box-small">

									<i class="fa fa-puzzle-piece main-bg filled shape"></i>

									<div class="icon-sm-desc">

										<h3 class="bold uppercase">High Quality Design</h3>

										<p>Proin gravida nibh vel velit auctor enean sollicitud lorem quis bibendum auctor, nisi elit consequipsum.</p>

									</div>

								</div>

							</div>

						</div>

						<div class="row margin-top-40">

							<div class="col-md-4">

								<div class="icon-box-small">

									<i class="fa fa-legal main-bg filled shape"></i>

									<div class="icon-sm-desc">

										<h3 class="bold uppercase">SEO Optimized Template</h3>

										<p>Proin gravida nibh vel velit auctor enean sollicitud lorem quis bibendum auctor, nisi elit consequipsum.</p>

									</div>

								</div>

							</div>

							<div class="col-md-4">

								<div class="icon-box-small">

									<i class="fa fa-legal main-bg filled shape"></i>

									<div class="icon-sm-desc">

										<h3 class="bold uppercase">SEO Optimized Template</h3>

										<p>Proin gravida nibh vel velit auctor enean sollicitud lorem quis bibendum auctor, nisi elit consequipsum.</p>

									</div>

								</div>

							</div>

							<div class="col-md-4">

								<div class="icon-box-small">

									<i class="fa fa-legal main-bg filled shape"></i>

									<div class="icon-sm-desc">

										<h3 class="bold uppercase">SEO Optimized Template</h3>

										<p>Proin gravida nibh vel velit auctor enean sollicitud lorem quis bibendum auctor, nisi elit consequipsum.</p>

									</div>

								</div>

							</div>

						</div>-->

				</div>

			</div>

			<?php

			$sqlgetcontent2 = "SELECT * FROM `frontpage` WHERE `konten_foto`!='' AND `kategori`='3' ORDER BY RAND() LIMIT 1";

			$getcontent2 = $conn->query($sqlgetcontent2);

			$cf2 = $getcontent2->fetch_assoc();

			?>



			<div class="section lg-padding padding-bottom-0" style="background-image:url('assets/images/bgs/background.jpg');background-size:cover">

				<div class="container">

					<div class="heading main-heading centered ">

						<h3 class="black-color">Infografis</h3>

						<h4 class="sub-title blue">Statistik <span class="main-color"><?php echo "$lsp[nama]"; ?></span></h4>

						<div class="heading-separator"><span class="main-bg"></span><span class="dark-bg"></span></div>

					</div>

					<p class="heading-desc centered white">Pencapaian <?php echo "$lsp[nama]"; ?> dalam angka.</p>



					<div class="row">

						<?php

						$sqlgetjumasesor = "SELECT * FROM `asesor`";

						$getjumasesor = $conn->query($sqlgetjumasesor);

						$jumasesor = $getjumasesor->num_rows;

						$sqlgetjumtuk = "SELECT * FROM `tuk`";

						$getjumtuk = $conn->query($sqlgetjumtuk);

						$jumtuk = $getjumtuk->num_rows;

						$sqlgetjumskema = "SELECT * FROM `skema_kkni`";

						$getjumskema = $conn->query($sqlgetjumskema);

						$jumskema = $getjumskema->num_rows;

						$sqlgetjumasesi = "SELECT * FROM `asesi_asesmen` WHERE status_asesmen='K'";
						// $sqlgetjumasesi="SELECT * FROM `asesi`";

						$getjumasesi = $conn->query($sqlgetjumasesi);

						$jumasesi = $getjumasesi->num_rows;



						?>

						<div class="col-md-3">

							<div class="c-chart" data-dimension="250" data-text="<?php echo "$jumasesor"; ?>" data-info="Asesor" data-width="20" data-fontsize="55" data-percent="<?php echo "$jumasesor"; ?>" data-fgcolor="#a9bf04" data-bgcolor="#989898" data-type="half" data-icon="fa-task" data-fill="#fff"></div>

						</div>



						<div class="col-md-3">

							<div class="c-chart" data-dimension="250" data-text="<?php echo "$jumtuk"; ?>" data-info="TUK" data-width="20" data-fontsize="55" data-percent="<?php echo "$jumtuk"; ?>" data-fgcolor="#a9bf04" data-bgcolor="#989898" data-type="half" data-icon="fa-task" data-fill="#fff"></div>

						</div>



						<div class="col-md-3">

							<div class="c-chart" data-dimension="250" data-text="<?php echo "$jumskema"; ?>" data-info="Skema" data-width="20" data-fontsize="55" data-percent="<?php echo "$jumskema"; ?>" data-fgcolor="#a9bf04" data-bgcolor="#989898" data-type="half" data-icon="fa-task" data-fill="#fff"></div>

						</div>



						<div class="col-md-3">

							<div class="c-chart" data-dimension="250" data-text="<?php echo "$jumasesi"; ?>" data-info="Pemegang Sertifikat" data-width="20" data-fontsize="55" data-percent="<?php echo "$jumasesi"; ?>" data-fgcolor="#a9bf04" data-bgcolor="#989898" data-type="half" data-icon="fa-task" data-fill="#fff"></div>

						</div>



					</div>

				</div>

			</div>



			<div class="section">

				<div class="container">



					<div class="heading main-heading centered ">

						<h3>Asesor Kami</h3>

						<h4 class="sub-title">Anggota Tim Asesor <span class="main-color"><?php echo "$lsp[nama]"; ?></span></h4>

						<div class="heading-separator"><span class="main-bg"></span><span class="dark-bg"></span></div>

					</div>

					<p class="heading-desc centered">Tim kami terdiri dari Asesor Profesional yang terdiri dari Profesor, Doktor dan Tenaga Ahli pada bidangnya.</p>

					<?php

					echo "<div class='row'>";

					$sqlgetasesor = "SELECT * FROM `asesor` ORDER BY RAND() LIMIT 4";

					$getasesor = $conn->query($sqlgetasesor);

					while ($asr = $getasesor->fetch_assoc()) {

						if (!empty($asr['gelar_depan'])) {

							if (!empty($asr['gelar_blk'])) {

								$namaasesor = $asr['gelar_depan'] . " " . $asr['nama'] . ", " . $asr['gelar_blk'];
							} else {

								$namaasesor = $asr['gelar_depan'] . " " . $asr['nama'];
							}
						} else {

							if (!empty($asr['gelar_blk'])) {

								$namaasesor = $asr['nama'] . ", " . $asr['gelar_blk'];
							} else {

								$namaasesor = $asr['nama'];
							}
						}



						echo "<div class='col-md-3'>

										<div class='team-box box-3 shape lg'>

											<div class='team-img'>

												<span></span>";

						if (empty($asr['foto'])) {

							echo "<img src='images/defaultasesor.jpg' width='300px' height='400px'>";
						} else {

							echo "<img alt='Foto $namaasesor' src='foto_asesor/$asr[foto]' width='300px' height='400px'>";
						}

						echo "</div>

											<div class='team-details main-bg'>

												<h3 class='team-name'>$namaasesor</h3>

												<h5 class='team-pos white'>Asesor</h5>

												<!--<ul class='social-list'>

													<li><a href='mailto:$asr[email]' class='fa fa-envelope shape sm'></a></li>

													<li><a href='https://www.facebook.com/$asr[facebook]' class='fa fa-facebook shape sm'></a></li>

													<li><a href='#' class='fa fa-linkedin shape sm'></a></li>

													<li><a href='#' class='fa fa-twitter shape sm'></a></li>

												</ul>-->

											</div>

										</div>

									</div>";
					}

					echo "</div>";

					?>

				</div>

			</div>



			<div class="section gry-bg">

				<div class="container">



					<div class="row">

						<div class="col-md-8">

							<div class="heading sub-head">

								<h3 class="bolder head-4 uppercase">Info <span class="main-color">Terkini</span></h3>

							</div>



							<div class="row">

								<div class="blog-posts show-arrows horizontal-slider" data-slides_count="2" data-scroll_amount="2" data-slider-speed="300" data-slider-infinite="1" data-slider-dots="0">

									<?php

									$sqlgetkategoriid = "SELECT * FROM `frontpage_kategori` WHERE `kategori`='berita'";

									$getkategoriid = $conn->query($sqlgetkategoriid);

									$katid = $getkategoriid->fetch_assoc();

									$sqlgetkontenberita = "SELECT * FROM `frontpage` WHERE `kategori`='$katid[id]'";

									$getkontenberita = $conn->query($sqlgetkontenberita);

									while ($bt = $getkontenberita->fetch_assoc()) {

										$kontentampil = substr($bt['konten'], 0, 150) . "...";

										$tanggalberita = tgl_indo($bt['tanggal_terbit']);

										echo "<div class='post-item col-md-4'>

												<div class='post-image'>

													<a href='blog-single.php?pid=$bt[id]'>";

										if (!empty($bt['konten_foto'])) {

											echo "<img src='foto_konten/$bt[konten_foto]' alt='$bt[judul]'>";
										} else {

											echo "<img src='images/default.jpg' alt='$bt[judul]'>";
										}

										echo "<i class='fa fa-book post-icon'></i>

													</a>

												</div>

												<article class='post-content main-border'>

													<div class='post-info-container'>

														<div class='post-info'>

															<h2><a href='blog-single.php?pid=$bt[id]'>$bt[judul]</a></h2>

															<ul class='post-meta'>

																<li class='meta-user'><i class='fa fa-user'></i>By: <a href='#'>Admin</a></li>

																<li class='meta_date'><i class='fa fa-clock-o'></i>$tanggalberita</li>

															</ul>

														</div>

													</div>

													<p>$kontentampil <br/><a class='more_btn' href='blog-single.php?pid=$bt[id]'>Selengkapnya >></a></p>

												</article>

											</div>";
									}

									?>





								</div>

							</div>



						</div>



						<div class="col-md-4">

							<div class="heading sub-head">

								<h3 class="bolder head-4 uppercase">Jadwal Asesmen Terkini</h3>

							</div>

							<p>Agenda/ jadwal penyelenggaraan uji kompetensi <?php echo $lsp['nama']; ?> yang dapat anda ikuti dapat dilihat pada data sebagai berikut.</p>

							<div class="testimonials testimonials-1 horizontal-slider t_slider-1" data-slides_count="1" data-scroll_amount="1" data-slider-speed="300" data-slider-infinite="1" data-slider-dots="1">

								<?php

								$sqlgetkategoriid2 = "SELECT * FROM `jadwal_asesmen` ORDER BY `tgl_asesmen` DESC LIMIT 10";

								$getkategoriid2 = $conn->query($sqlgetkategoriid2);

								$nobt = 1;

								while ($bt2 = $getkategoriid2->fetch_assoc()) {

									echo "<div class='testimonials-bg main-bg'>

						<div class='testimonials-img'>

							<img alt='' src='assets/images/testimonials/$nobt.jpg'>

						</div>

						<div class='testimonials-name white'>";

									echo "$bt2[nama_kegiatan]<br>";

									$sqlgetskema = "SELECT * FROM `skema_kkni` WHERE `id`='$bt2[id_skemakkni]'";

									$getskema = $conn->query($sqlgetskema);

									$skema = $getskema->fetch_assoc();

									echo "<strong>$skema[judul]</strong>";

									echo "<br>

					<span class='alter-bg testo-pos'>" . tgl_indo($bt2['tgl_asesmen']) . " sd. " . tgl_indo($bt2['tgl_asesmen_akhir']) . " Pukul $bt2[jam_asesmen]</span>

					</div>";

									$sqlgettuk = "SELECT * FROM `tuk` WHERE `id`='$bt2[tempat_asesmen]'";

									$gettuk = $conn->query($sqlgettuk);

									$tuk = $gettuk->fetch_assoc();

									echo "<p>$tuk[nama]<br>$tuk[alamat]</p>";



									echo "</div>";

									$nobt++;
								}



								?>

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

									$sqlgetmenub = "SELECT * FROM `menu`";

									$getmenub = $conn->query($sqlgetmenub);

									$nomub = 1;

									while ($mub = $getmenub->fetch_assoc()) {

										$sqlsubmenu0b = "SELECT * FROM `menu` WHERE `menu_induk`='$mub[id]'";

										$submenu0b = $conn->query($sqlsubmenu0b);

										$jumsubb = $submenu0b->num_rows;

										if ($jumsubb == 0) {

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

									$sqlgetkategoriid2 = "SELECT * FROM `jadwal_asesmen` ORDER BY `tgl_asesmen` DESC LIMIT 10";

									$getkategoriid2 = $conn->query($sqlgetkategoriid2);

									$nobt = 1;

									/*echo "<div style='overflow-x:auto;'>

				<table id='example2' class='table table-bordered table-striped'>

				<thead><tr><th>No.</th><th>Agenda Kegiatan Asesmen</th><th>Tempat (TUK)</th></tr></thead>

				<tbody>";*/

									while ($bt2 = $getkategoriid2->fetch_assoc()) {

										echo "<li class='shape sm'><a href='jadwal.php'>&nbsp;";

										//echo "$nobt $bt2[nama_kegiatan]<br>";

										$sqlgetskema = "SELECT * FROM `skema_kkni` WHERE `id`='$bt2[id_skemakkni]'";

										$getskema = $conn->query($sqlgetskema);

										$skema = $getskema->fetch_assoc();

										echo tgl_indo($bt2['tgl_asesmen']) . "&nbsp;|&nbsp;";

										//echo " Pukul $bt2[jam_asesmen]";

										$sqlgettuk = "SELECT * FROM `tuk` WHERE `id`='$bt2[tempat_asesmen]'";

										$gettuk = $conn->query($sqlgettuk);

										$tuk = $gettuk->fetch_assoc();
										echo "$skema[judul]";

										//echo "$tuk[nama]<br>$tuk[alamat]";



										echo "</a></li>";

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

											$sql1 = "SELECT * FROM  `data_wilayah` WHERE  `id_wil`='$lsp[id_wilayah]'";

											$tampil1 = $conn->query($sql1);

											$wil1 = $tampil1->fetch_assoc();

											$sql2 = "SELECT * FROM  `data_wilayah` WHERE  `id_wil`='$wil1[id_induk_wilayah]'";

											$tampil2 = $conn->query($sql2);

											$wil2 = $tampil2->fetch_assoc();

											$sql3 = "SELECT * FROM  `data_wilayah` WHERE  `id_wil`='$wil2[id_induk_wilayah]'";

											$tampil3 = $conn->query($sql3);

											$wil3 = $tampil3->fetch_assoc();

											echo "$wil1[nm_wil], $wil2[nm_wil], $wil3[nm_wil] 

							<span>Kode Pos: $lsp[kodepos]</span>

						</p>";

											?></span></li>

									<li><i class="fa fa-envelope shape"></i><span><span class="heavy-font">Email: </span><?php echo "<a href='mailto:$lsp[email]'>$lsp[email]</a>"; ?></span></li>

									<li><i class="fa fa-phone shape"></i><span><span class="heavy-font">Tel: </span><?php echo "<a href='tel:$lsp[telepon]'>$lsp[telepon]</a>"; ?></span></li>

									<li><i class="fa fa-whatsapp shape"></i><span><span class="heavy-font">WhatsApp: </span><?php echo "<a href='https://api.whatsapp.com/send?phone=$lsp[wa]&text=Salam, saya mau tanya tentang $lsp[nama]'>$lsp[wa]</a>"; ?></span></li>

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

							<div class="copyrights col-md-10 first">© Copyrights <b class="main-color"><?php echo "$lsp[nama]"; ?></b> 2019. All rights reserved.</div>



							<!-- footer social links right cell start -->

							<div class="col-md-2 last">

								<ul class="footer-menu f-right">



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
		var tpj = jQuery;

		var revapi4;

		tpj(window).load(function() {

			if (tpj("#slider").revolution == undefined) {

				revslider_showDoubleJqueryError("#rev_slider_4_1");

			} else {

				revapi4 = tpj("#slider").show().revolution({

					sliderType: "standard",

					jsFileLocation: "assets/revolution/js/",

					sliderLayout: "fullscreen",

					dottedOverlay: "none",

					delay: 9000,

					navigation: {

						keyboardNavigation: "off",

						keyboard_direction: "horizontal",

						mouseScrollNavigation: "off",

						onHoverStop: "off",

						touch: {

							touchenabled: "on",

							swipe_threshold: 75,

							swipe_min_touches: 1,

							swipe_direction: "horizontal",

							drag_block_vertical: false

						}

						,

						arrows: {

							style: "zeus",

							enable: true,

							hide_onmobile: true,

							hide_under: 600,

							hide_onleave: true,

							hide_delay: 200,

							hide_delay_mobile: 1200,

							tmp: '<div class="tp-title-wrap">  	<div class="tp-arr-imgholder"></div> </div>',

							left: {

								h_align: "left",

								v_align: "center",

								h_offset: 30,

								v_offset: 0

							},

							right: {

								h_align: "right",

								v_align: "center",

								h_offset: 30,

								v_offset: 0

							}

						}

					},

					viewPort: {

						enable: true,

						outof: "pause",

						visible_area: "80%"

					},

					responsiveLevels: [1240, 1024, 778, 480],

					gridwidth: [1240, 1024, 778, 480],

					gridheight: [600, 600, 500, 400],

					lazyType: "none",

					parallax: {

						type: "mouse",

						origo: "slidercenter",

						speed: 2000,

						levels: [2, 3, 4, 5, 6, 7, 12, 16, 10, 50],

					},

					shadow: 0,

					spinner: "off",

					stopLoop: "off",

					stopAfterLoops: -1,

					stopAtSlide: -1,

					shuffle: "off",

					autoHeight: "off",

					hideThumbsOnMobile: "off",

					hideSliderAtLimit: 0,

					hideCaptionAtLimit: 0,

					hideAllCaptionAtLilmit: 0,

					debugMode: false,

					fallbacks: {

						simplifyAll: "off",

						nextSlideOnWindowFocus: "off",

						disableFocusListener: false,

					}

				});



				revapi4.bind("revolution.slide.onchange", function(e, data) {

					if ($('.top-head').hasClass('transparent') || $('.top-head').hasClass('boxed-transparent')) {

						if ($('#slider ul > li').eq(data.slideIndex - 1).hasClass('dark')) {

							$('.top-head').removeClass('not-dark');

							$('.top-head').addClass('dark');

							var logo = $('.logo').find('img').attr('src').replace("logo.png", "logo-light.png");

							$('.logo').find('img').attr('src', logo);

						} else {

							$('.top-head').removeClass('dark');

							$('.top-head').addClass('not-dark');

							var logo = $('.logo').find('img').attr('src').replace("logo-light.png", "logo.png");

							$('.logo').find('img').attr('src', logo);

						}

						if ($('.top-head').hasClass('sticky-nav')) {

							var logo = $('.logo').find('img').attr('src').replace("logo-light.png", "logo.png");

							$('.logo').find('img').attr('src', logo);

						}

					}

				});

			}

		}); /*ready*/
	</script>



	<!-- general script file -->

	<script type="text/javascript" src="assets/js/script.js"></script>



</body>

</html>