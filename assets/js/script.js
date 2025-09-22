(function($) {

	"use strict";
	/* ================ Choose your template theme color, skin and layout. ================ */
	
	var $theme				= 'light',		// available values: 'light','dark'.
		$skin				= 'default',	// available values: 'default','1','2','3','4','5','6','7','8','9','10','11','12'.
		$layout				= 'wide',		// available values: 'wide','boxed'.
		$shape				= 'new-angle',	// available values: 'new-angle','round','border5px','square','right-angle','left-angle','top-angle','bottom-angle'.
		$bodyclass			= 'bg1',		// for boxed layout only, available values: patterns : 'bg1','bg2','bg3','bg4','bg5','bg6','bg7','bg8','bg9','bg10' & background images: 'bg11','bg12','bg13'.
		$rtl				= '0',			// 0 is false and 1 is true.
		$page_transition	= '1';			// 0 to disable page transitions.
		
	var theme_css	= $('#theme_css').attr('href'),
		theme_name	= theme_css.substring(theme_css.lastIndexOf("/") + 1, theme_css.length),
		th_nam		= theme_name.replace('.css',''),
		skin_css	= $('#skin_css').attr('href'),
		skin_name	= skin_css.substring(skin_css.lastIndexOf("/") + 1, skin_css.length),
		sk_nam		= skin_name.replace('.css',''),
		theme_url 	= theme_css.replace(theme_name,''),
		skin_url 	= skin_css.replace(skin_name,'');
	
	if($theme == th_nam || th_nam == 'light'){
		$('#theme_css').attr('href',theme_url+$theme+'.css');
	}else{
		$('#theme_css').attr('href',theme_url+th_nam+'.css');
	}
		
	if($skin == sk_nam || sk_nam == 'default'){
		$('#skin_css').attr('href',skin_url+$skin+'.css');
	}else{
		$('#skin_css').attr('href',skin_url+sk_nam+'.css');
	}
	
	if($layout != 'wide'){
		$('.pageWrapper').addClass('boxed');
		$('body').addClass($bodyclass)
	}
	
	// add shapes styles.
	$('html,.shape').addClass($shape);
	
	if($rtl == '1'){
		$('head').append('<link rel="stylesheet" href="assets/css/rtl.css">');
		var $rt = true;
	}else{
		var $rt = false;
	}
		
	/* ================ Check for Mobile. ================ */
	if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
	 	$('html').addClass('touch');
	}else{
		$('html').addClass('no-touch');
	}
	$('.touch .fx').addClass('animated'); // disables mobile animations
		
	var ScrlTop = $(window).scrollTop();
	
	/* ================ Top Menu. ================ */
	var mnav = $('.nav-animate.top-nav > ul');
	var mainnav = $('.top-nav > ul');
	
	mnav.find(' > li').each(function(){
		var txtH = $(this).find('> a').text();
		$(this).find('> a').wrapInner('<span data-hover="'+txtH+'"/>');
	});
	mainnav.find('li').not('.mega-menu li').each(function(){
		var submenu = $(this).find('> ul');
		if (submenu.length){
			$(this).addClass('hasChildren');
		}
	});
	
	mainnav.find('li.mega-menu').each(function(){
		$(this).find('> a').wrap('<span class="inner-mega"></span>');
	});
	
	/* =================== Side Nav ===================== */
	if($('.header-left').length > 0){
		$('.pageWrapper').addClass('left-side-wrap');
	}
	if($('.header-right').length > 0){
		$('.pageWrapper').addClass('right-side-wrap');
	}
	if($('.side-nav').length > 0){
		var side_menu = $('.side-nav > ul'),
			items = side_menu.find('li');
		side_menu.find('ul').addClass('main-bg');
		items.each(function(){
			var ul = $(this).find('ul:first');
			if (ul.length){
				$(this).addClass('hasChildren');
			}
		});
	}
	
	/* ================ Show Hide Search box. ================ */
	$('.top-search .search-box').prepend('<a href="#" class="close-srch fa fa-times">X</a>');
	$('.top-search > a').on("click",function(){
		var sbox = $(this).parent().find('.search-box');
		if(sbox.is(':visible')){
			$(this).parent().find('.srch-overlay').remove();
			sbox.hide().removeClass('animated zoomInDown').addClass('fx');
			return false;
		}else{
			$(this).parent().append('<div class="srch-overlay"/>');
			sbox.show().delay(500).removeClass('fx').addClass('animated zoomInDown');
			return false;
		}
	});
	$('.close-srch').on("click",function(e){
		$(this).parent().parent().find('.srch-overlay').fadeOut(500).remove();
		$(this).parent().parent().find('.search-box').hide().removeClass('animated zoomInDown').addClass('fx');
		return false;
	});
	
	
	/* ================ Show Hide Cart box. ================ */
	$('.top-cart > a').on("click",function(){
		if($(this).parent().find('.cart-box').is(':visible')){
			$(this).parent().removeClass('selected').find('.cart-box').fadeOut(300);
			return false;
		}else{
			$(this).parent().addClass('selected').find('.cart-box').fadeIn(300);
			return false;
		}
	});
	
	$(document).on("mouseup",function(e){
		if($('.cart-box').is(':visible')){
			var targ = $(".cart-box");
			if (!targ.is(e.target) && targ.has(e.target).length === 0){
			$('.cart-box').fadeOut(300);
			$('.top-cart').removeClass('selected');
			}
		}
	});
	
	/* ================ Responsive Menu ================= */
	if($('.responsive-nav').length){
		
		var responsnav = $('.responsive-nav').find('nav').html();
		$('.pageWrapper').prepend('<a class="navbtn" href="#"><i class="fa fa-navicon"></i></a><div class="new-nav">'+responsnav+'</div>');
		$('.navbtn').on("click",function(e){
			e.preventDefault();
			if($('.pageWrapper').hasClass('responsive-body')){
				$(this).removeClass('active').find('i').removeClass('fa-times').addClass('fa-navicon');
				$('.new-nav').removeClass('active');
				$('.pageWrapper').removeClass('responsive-body');
				return false;
			}else{
				$(this).addClass('active').find('i').addClass('fa-times').removeClass('fa-navicon');
				$('.new-nav').addClass('active');
				$('.pageWrapper').addClass('responsive-body');
				return false;
			}
		});
		
		$('.new-nav li.hasChildren').find(' > a[href="#"]').on("click",function(e){
			e.preventDefault();
			$(this).parent().find('> ul').slideToggle();
			$(this).parent('li.hasChildren').toggleClass('active');
			return false;
		});
		
		$('.new-nav li.mega-menu.hasChildren').find('span.inner-mega > a[href="#"]').on("click",function(e){
			e.preventDefault();
			$(this).parent().parent().find('> ul').slideToggle();
			$(this).parent().parent('li.hasChildren').toggleClass('active');
			return false;
		});
		
		$('.new-nav li.hasChildren.mega-menu').find(' h4').on("click",function(e){
			e.preventDefault();
			$(this).parent().find('> ul').slideToggle();
			$(this).parent('li.hasChildren').toggleClass('active');
			return false;
		});
	}
	
	/* adjust submenu positions */
	var $sub = $('.top-nav ul ul');
	if($sub.length && $('.container').length){
		var $c = $('.container').width() + $('.container').offset().left + 30;
		$sub.each(function(){
			var $lft = $(this).offset().left + $(this).width() - 10;
			if( $lft > $c){
				$(this).addClass('rit-menu');
			}
		});
	}
	
	/* ================ boxed transparent header bottom line function =================== */
	if($('.top-head.boxed-transparent').length){
		var main_cont = $('.top-head.boxed-transparent > .container'),
			mW = main_cont.width(),			
			WW;
			
			if ($('.top-head.boxed-transparent .top-nav > ul > li.selected').length > 0){
				var selOff = $('.top-head.boxed-transparent .top-nav > ul > li.selected').offset().left,
					mOff = main_cont.offset().left,
					defW = selOff - mOff + ($('.top-head.boxed-transparent .top-nav > ul > li.selected').width() + 10);
					main_cont.append('<span class="bot-line"></span><span class="lft-line main-bg"></span>');
					$('.bot-line').css('width',defW + 'px');
			}else{
				var selOff = $('.top-head.boxed-transparent .logo').addClass('no-lines');
			}
		
		
		$('.top-head.boxed-transparent .top-nav > ul > li').each(function(){
			var thisOff = $(this).offset().left,
				thisW	= $(this).width() + 10;
			$(this).hover(function(){
				WW = thisOff - mOff + thisW;
				$('.bot-line').css('width',WW + 'px');
			},function(){
				$('.bot-line').css('width',defW + 'px');
			});
		});
	}
		
	/* ================ Back to top button. ================ */
	if (ScrlTop > 1) {
		$('#to-top').css({'transform':'translate3d(0,0,0)'});
	} else {
		$('#to-top').css({'transform':'translate3d(50px,50px,0)'});
	}
	$('#to-top,.divider .to-top').on("click",function(){
		$('html, body').animate({scrollTop: '0px'}, 800);
		return false;
	});
				
	/* ================ social links copy :after icon. ================ */
	$('.social-list li a').each(function(){
    	var contt = window.getComputedStyle(this,':before').content.replace(/\'/g, "").replace(/\"/g, "");
    	$(this).attr('data-hover',contt);
	});
	
	/* ================ Message boxes clode buttons. ================ */
	$('.msg-box').each(function(){
		$(this).prepend('<a class="close fa fa-times" href="#"></a>');
		$(this).find('a.close').on("click",function(e){
			e.preventDefault();
			$(this).parent().addClass('animated zoomOut').delay(500).queue(function(n) {
			  $(this).remove();
			});
			return false;
		});
	});
	
	/* ================ pager bar 3. ================ */
	$('.pagination.bar-3 ul').append('<div class="pager-slider"></div>');
	var page_n  = $('.pagination.bar-3 ul li.selected'),
		sel_pag = page_n.prevAll('li').length,
		sel_off = (sel_pag*40)+80;
	setTimeout(function(){
		$('.pager-slider').animate({width:sel_off+'px'},300);
	},2000);
	
	
	/* ================ Tabs. ================ */
	$('.nav-tabs a').on("click",function(e) {
		e.preventDefault();
		$(this).tab('show');
		return false;
	})
		
	$('.login-popup').on("click",function(e) {
		e.stopPropagation();
		return false;
	});
    
	$('.dropdown').on('show.bs.dropdown', function(e){
		var $dropdown = $(this).find('.dropdown-menu');
		var orig_margin_top = parseInt($dropdown.css('margin-top'),10);
		$dropdown.css({'margin-top': (orig_margin_top + 20) + 'px', opacity: 0}).animate({'margin-top': orig_margin_top + 'px', opacity: 1}, 300, function(){
			$(this).css({'margin-top':''});
		});
	});
   
	// Add slidedown & fadeout animation to dropdown
	$('.dropdown').on('hide.bs.dropdown', function(e){
		var $dropdown = $(this).find('.dropdown-menu');
		var orig_margin_top = parseInt($dropdown.css('margin-top'),10);
		$dropdown.css({'margin-top': orig_margin_top + 'px', opacity: 1, display: 'block'}).animate({'margin-top': (orig_margin_top + 20) + 'px', opacity: 0}, 300, function(){
			$(this).css({'margin-top':'', display:''});
		});
	});
	
	/* ================ Accordions. ================ */
	$('.accordion').collapse({
		toggle: false
	});
	$('.accordion.style-2 [data-toggle="collapse"]').each(function(){
		$(this).on("click",function(e) {
			e.preventDefault();
			var $par = $(this).parent().parent().parent();
			if(!$par.hasClass('main-bg')){
				$par.parent().find('.panel').removeClass('main-bg');
				$par.addClass('main-bg');
			}else{
				$par.removeClass('main-bg')
			}
		});
	});	
	
	/* ================ circliful charts. ================ */
	$('.c-chart').each(function(){
		$(this).waypoint(function() {
			$(this).circliful();
			var LH = $(this).find('.circle-text').css('line-height'),
				half = parseInt($(this).find('.circle-text-half').css('height'),10);
			if ($(this).hasClass('bottom-txt')){
				$(this).find('.fa').css('line-height',LH);
			}
			if ($(this).attr('data-type') == 'half'){
				$(this).css('height',half-48+'px');
			}
		},{offset: '90%',triggerOnce: true});
	});
	
	
	/* ================ Counter. ================ */
	$('.odometer').each(function(){
		$(this).waypoint(function() {
			var the = $(this),
			timerss = the.attr('data-timer');
			var timeout = setTimeout(function(){
				var initVal = the.attr('data-initial'),
				currVal = the.attr('data-value');
				the.html(currVal);
			},timerss);
		},{offset: '90%',triggerOnce: true});
	});	
	
	/* ================ flickr Photos. ================ */
	if ($('#flickrFeed').length > 0){    
		$('#flickrFeed').jflickrfeed({
			limit: 8,
			qstrings: {
			id: '45409431@N00'
		},
		itemTemplate: '<li><a href="{{image_b}}" class="zoom"><img src="{{image_s}}" alt="{{title}}" /><span class="img-overlay"></span></a></li>'
		});
	}
	
	/* flickr Photos many items. */
	if ($('#flickrFeed2').length > 0){    
		$('#flickrFeed2').jflickrfeed({
			limit: 16,
			qstrings: {
			id: '45409431@N00'
		},
		itemTemplate: '<li><a href="{{image_b}}" class="zoom"><img src="{{image_s}}" alt="{{title}}" /><span class="img-overlay"></span></a></li>'
		});
	}
	
	/* flickr Photos about me. */
	if ($('#flickrFeed3').length > 0){    
		$('#flickrFeed3').jflickrfeed({
			limit: 8,
			qstrings: {
			id: '45409431@N00'
		},
		itemTemplate: '<li><a href="{{image_b}}" class="zoom"><img src="{{image_s}}" alt="{{title}}" /><span class="img-overlay"></span></a></li>'
		});
	}
		
	/* ================ Coming Soon CountDown. ================ */
	if($(".digits").length > 0){
		$('.digits').countdown('2020/10/10').on('update.countdown', function(event) {
			var $this = $(this).html(event.strftime('<ul>'
			+ '<li><span>%-w</span><p> week%!w </p> </li>'
			+ '<li><span>%-d</span><p> day%!d </p></li>'
			+ '<li><span>%H</span><p>Hours </p></li>'
			+ '<li><span>%M</span><p> Minutes </p></li>'
			+ '<li><span>%S</span><p> Seconds </p></li>'
			+'</ul>'));
		});
	}
    
    /* ================ Portfolio Filterable IsoTope. ================ */
	if($('#container').length){
		var $container = $('#container').isotope({
			layoutMode: 'fitRows'
		});
				
		$container.imagesLoaded( function() {
			$container.isotope();
		});
		
		$('#filters').on( 'click', 'a.filter', function(e) {
			e.preventDefault();
			var filterValue = $(this).attr('data-filter');
			$container.isotope({ filter: filterValue });
			var $this = $(this);
			if ( $this.parent().hasClass('active') ) {
				return false;
			}
			var $optionSet = $this.parents('#filters');
			$optionSet.find('.active').removeClass('active');
			$this.parent().addClass('active');
		});
	}
	
	/* ================ Masonry IsoTope. ================ */
	if($('#masonry').length){
		var $masonry = $('#masonry').isotope({
			layoutMode: 'masonry',
			isFitWidth: true
		});
		
		$masonry.imagesLoaded( function() {
			$masonry.isotope();
		});
		
		$('#filters').on( 'click', 'a.filter', function(e) {
			e.preventDefault();
			var filterValue = $(this).attr('data-filter');
			$masonry.isotope({ filter: filterValue });
			var $this = $(this);
			if ( $this.parent().hasClass('active') ) {
				return false;
			}
			var $optionSet = $this.parents('#filters');
			$optionSet.find('.active').removeClass('active');
			$this.parent().addClass('active');
		});
	}
	
	/* ================ Grid IsoTope. ================ */
	if($('#grid').length){
		var $grid = $('#grid').isotope({
			layoutMode: 'fitRows'
		});
		$grid.imagesLoaded( function() {
			$grid.isotope();
		});
	}
	
	/* ================ Social share blog buttons plugin. ================ */
	if($('#shareme').length){
		$('#shareme').sharrre({
			share: {
				twitter: true,
				facebook: true,
				googlePlus: true,
				linkedin: true
			},
			template: '<ul class="social-list"><li class="md-icon"><a href="#" data-toggle="tooltip" data-placement="top" title="facebook" class="fa fa-facebook facebook"></a></li><li class="md-icon"><a href="#" data-toggle="tooltip" data-placement="top" title="twitter" class="fa fa-twitter twitter"></a></li><li class="md-icon"><a href="#" data-toggle="tooltip" data-placement="top" title="google plus" class="fa fa-google-plus googleplus"></a></li><li class="md-icon"><a href="#" data-toggle="tooltip" data-placement="top" title="linkedin" class="fa fa-linkedin linkedin"></a></li><li class="md-icon no-icon"><a class="dark-bg">{total}</a></li></ul>',
			enableHover: false,
			enableTracking: true,
			url: document.location.href,
			render: function(api, options){
				$(api.element).on('click', '.twitter', function(e) {
					e.preventDefault();
					api.openPopup('twitter');
				});
				$(api.element).on('click', '.facebook', function(e) {
					e.preventDefault();
					api.openPopup('facebook');
				});
				$(api.element).on('click', '.googleplus', function(e) {
					e.preventDefault();
					api.openPopup('googlePlus');
				});
				$(api.element).on('click', '.linkedin', function(e) {
					e.preventDefault();
					api.openPopup('linkedin');
				});
				
				$('.social-list li a').each(function(){
			    	var contt = window.getComputedStyle(this,':before').content.replace(/\'/g, "").replace(/\"/g, "");
			    	$(this).attr('data-hover',contt);
				});
			}
		});
	}
	
	/* ================ Slick Sliders. ================ */
	
	var runSlick = function() {
	
		/* Horizontal slider */
		if($('.horizontal-slider').length > 0){
			
			$('.horizontal-slider').each(function(){
				var slides_n 	= parseInt($(this).attr('data-slides_count'),10),
					sscrol 		= parseInt($(this).attr('data-scroll_amount'),10),
					t_infinite 	= $(this).attr('data-slider-infinite'),
					t_arr 		= $(this).attr('data-slider-arrows'),
					speed_n 	= $(this).attr('data-slider-speed'),
					t_fade 		= $(this).attr('data-slider-fade'),
					t_dots 		= $(this).attr('data-slider-dots'),
					t_auto 		= $(this).attr('data-slider-auto'),
					fd 			= false,
					tinfinite 	= false,
					aut 		= false,
					arr 		= true,
					tdots 		= true,
					resp_n 		= 1;
				
				
				if(t_infinite == '1'){
					tinfinite = true;
				}
				if(t_auto == '1'){
					aut = true;
				}
				if(t_fade == '1'){
					fd = true;
				}
				if(t_arr == '0'){
					arr = false;
				}
				if(t_dots == '0'){
					tdots = false;
				}				
				if(slides_n > 2){
					resp_n = 2;
				}
				
				$(this).slick({
					slidesToShow: slides_n,
					slidesToScroll: sscrol,
					dots: tdots,
					infinite: tinfinite,
					speed: speed_n,
					rtl: $rt,
					fade: fd,
					autoplay: aut,
					arrows: arr,
					responsive: [
					{
						breakpoint: 1024,
						settings: {
							slidesToShow: resp_n,
							slidesToScroll: resp_n
						}
					},
					{
						breakpoint: 640,
						settings: {
							slidesToShow: 1,
							slidesToScroll: 1
						}
				    }
				  ]
				});
			});
		}
		
		$('.slick-gal,.posts-gal').slick({
			dots: true,
			rtl: $rt,
			arrows: false,
		});		
				
		/* breaking news */
		$('.break-news-slider').slick({
			dots: false,
			arrows: true,
			vertical: true,
			rtl: $rt,
			slidesToShow: 1,
			touchMove: true,
			slidesToScroll: 1,
			autoplay:true
		});
		
		/* banner slick */
		$('.banner-slick').slick({
			dots: false,
			arrows: false,
			vertical: true,
			rtl: $rt,
			slidesToShow: 1,
			touchMove: true,
			slidesToScroll: 1,
			autoplay:true
		});
		
		$('.t_slider-1').each(function(){
			$(this).find('.slick-dots,.slick-prev,.slick-next').wrapAll('<div class="slider_controls" />');
		});
				
		/* shop slider */
		if($('.shop-ads-top').length){
			$('.shop-ads-top').slick({
				dots: true,
				rtl: $rt,
				infinite: true,
				autoplay: true,
				speed: 300,
				slidesToShow: 1,
				touchMove: true,
				arrows:false,
				slidesToScroll: 1
			});
		}
		
		/* ================= Product images zoom =============== */
		if($(".product-zoom").length){
			$('.product-zoom').slick({
				dots: true,
				rtl: $rt,
				arrows: false,
				autoplay: true,
				customPaging: function(slider, i) { 
				var sl = $(slider.$slides[i]).find('.zoom').attr('href');
				return '<img src=' + sl + ' />';
				},
			});
		}
	}
	
	runSlick();
	
	/* ================= Grid - List view =============== */
	$('.list-btn').on("click",function() {
		$('.grid-list').addClass('list');
		$('.grid-btn').removeClass('selected');
		$(this).addClass('selected');
		return false;
	});
	$('.grid-btn').on("click",function() {
		$('.grid-list').removeClass('list');
		$('.list-btn').removeClass('selected');
		$(this).addClass('selected');
		return false;
	});
	
	
	/* ================= increase decrease items textbox =============== */
	var num = $('#items-num').val();
	$('.add-items i.fa-plus').on("click",function(e){
		e.preventDefault();
		num ++;
		$('#items-num').attr('value',num);
		return false;
	});
	$('.add-items i.fa-minus').on("click",function(e){
		e.preventDefault();
		if (num > 1){
			num --;
			$('#items-num').attr('value',num);
		}
		return false;
	});
	
	
	/* ================= ajax Magnific popup =============== */
	
	$('.ajax-popup').magnificPopup({
		type: 'ajax',
		preloader: 'true',
		mainClass: '.mpf',
		closeOnContentClick: false,
		callbacks: {
			ajaxContentAdded: function() {
				this.content.addClass('newEff');
				this.content.find('.pro-img-slick').slick({
					dots: false,
					rtl: $rt,
					arrows: true,
				});
				var num = $('#items-num').val();
				$('.add-items i.fa-plus').on("click",function(e){
					e.preventDefault();
					num ++;
					$('#items-num').attr('value',num);
				});
				$('.add-items i.fa-minus').on("click",function(e){
					e.preventDefault();
					if (num > 1){
						num --;
						$('#items-num').attr('value',num);
					}
					return false;
				});
			}
		}
	});
	
	/* ================= Camera SlideShow =============== */	
	if($('.magazine-gallery').length){
		$('.magazine-gallery').camera({
			height: '400px',
			loader: 'none',
			pagination: false,
			thumbnails: true,
			fx: 'scrollTop'
		});
	}
	
	if($('.camera-slider').length){
		$('.camera-slider').camera({
			height: '600px',
			loader: 'none',
			pagination: true,
			thumbnails: true,
			fx: 'scrollTop'
		});
	}
	
	if($('.camera-slider-boxed').length){
		$('.camera-slider-boxed').camera({
			height: '600px',
			loader: 'none',
			pagination: false,
			thumbnails: true,
			fx: 'scrollTop'
		});
	}
		
	/* ================ Checkbox Styling. ================ */
	var checkBox = $('.checkbox');
	$(checkBox).each(function(){
		$(this).wrap( "<span class='custom-checkbox'></span>" );
		if($(this).attr('value') == '1'){
			$(this).parent().addClass("selected main-bg");
		}
	});
	$(checkBox).on("click",function(){
		$(this).parent().toggleClass("selected main-bg");
		if ($(this).attr('value') == '1'){
			$(this).attr('value','0');
		}else{
			$(this).attr('value','1');
		}
		return false;
	});
	$('.custom-checkbox').append('<div class="switcher"/>');
	
	$('.custom-radio').find('label').prepend('<span><span></span></span>');
	
	if($page_transition == '1'){
		if($('.animsition').length){
			$(".animsition").animsition({
				inClass					: 'fade-in',
				outClass				: 'fade-out',
				inDuration				: 2000,
				outDuration				: 800,
				linkElement				: '.top-nav ul li a:not([target="_blank"]):not([href^=#])',
				loading					: true,
				loadingParentElement	: 'body',
				loadingClass			: 'page-loader',
				loadingInner			: '<div class="inner-l"><span></span><span></span><span></span></div>',
				overlay					: false
			});
		}
	}else{
		$('.pageWrapper').removeClass('animsition');
	}
	
	/* =============== Window.load ================== */
	$(window).on("load",function(){
		
		/* =============== login box vertical alignment ================== */
		function loginpadding(){
			var winH 	= parseInt($(window).height(),10)/2,
				docH 	= parseInt($(window).height(),10),
				loginH 	= parseInt($('.login-box-lg').height(),10)/2,
				padd 	= winH - loginH;
			$('.login-page .pageWrapper').css('padding-top',padd+'px');
			$('.fullscreen').css('height',docH+'px');
		}
		
		loginpadding();
		
		$(window).resize(function(){
			loginpadding();
		});
		
		$('[data-toggle="tooltip"]').tooltip();
		$('[data-toggle="popover"]').popover();
		$('[data-toggle="dropdown"]').dropdown();
				
		/* ================ Progress Bars ================= */
		$('.progress-bar').each(function(){
			$(this).waypoint(function() {
				var num = $(this).attr('aria-valuenow'),
					percent = $.animateNumber.numberStepFactories.append('%');
				if($(this).parent().parent().parent().hasClass('tool-tip')){
					$(this).find('> span').animateNumber({number: num,numberStep: percent});
				}else{
					$(this).find('> span').animateNumber({number: num,numberStep: percent},num*20);
				}
				$(this).css('width',num+'%').animate({left:'0%',opacity:'1'},num*20);
				
				if(num <= 40){
					$(this).find('> span').addClass('sm-progress');
				}
			},{offset: '90%',triggerOnce: true});
		});
		
		/* ================= Magnific popup =============== */
		$('.zoom').magnificPopup({
			type:'image',
			gallery: {
				enabled: true
			}
		});
		
		/* ================ Waypoints: on scroll down animations. =============== */
		$('.no-touch .fx').waypoint(function() {
			var anim = $(this).attr('data-animate'),
				del = $(this).attr('data-animation-delay');
			$(this).addClass('animated '+anim).css({animationDelay: del + 'ms'});
		},{offset: '80%',triggerOnce: true});
		
		
		
		/* =============== fix parallax bg in safari. ================== */
		if (navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1){
			$('.tags').addClass('saf-tags');
		}else{			
			/* ============= Stick Header logo change ========================== */
			if (ScrlTop > 1) {
				if($('.top-head').attr('data-sticky') == "true"){
					$('.top-head').addClass('sticky-nav');
				}
			}else{
				$('.top-head').removeClass('sticky-nav');
			}
			
			$.stellar({
			horizontalScrolling: false,
			verticalScrolling: true,
			responsive: true,
			parallaxElements: true,
			verticalOffset:-4,
			hideDistantElements: false
		});
		
		if($('.parallax').length > 0){
			$('.parallax').each(function(){
				var pover = $(this).attr('data-overlay');
				if(pover != undefined){
					$(this).prepend('<div class="parallax-overlay" style="background-color:'+pover+'"></div>');
				}
			});
		}
			
		}
		
		if($('.top-head').hasClass('sticky-nav')){
			var logo = $('.logo').find('img').attr('src').replace("logo-light.png", "logo.png");
			$('.logo').find('img').attr('src',logo);
		}

		$('.modal[role="dialog"]').on('show.bs.modal', function (event) {
			$('.pageWrapper,#contentWrapper,.section,.section > .container').addClass('pos-static');
		});
		
		$('.modal[role="dialog"]').on('hide.bs.modal', function (event) {
			$('.pageWrapper,#contentWrapper,.section,.section > .container').removeClass('pos-static');
		});
		
		// youtube video in popup..
		var trigger = $("body").find('[data-toggle="modal"]');
		trigger.on("click",function () {
			var theModal = $(this).data("target"),
				videoSRC = $(this).attr("data-theVideo"),
				videoSRCauto = videoSRC + "?autoplay=1";
				$(theModal + ' iframe').attr('src', videoSRCauto);
			$(theModal + ' button.close').on("click",function () {
				$(theModal + ' iframe').attr('src', videoSRC);
			});
			$('.modal').on("click",function () {
				$(theModal + ' iframe').attr('src', videoSRC);
				return false;
			});
		});
		
		/* =============== One Page Navigation ================ */
		if($('.one-page').length){
			$('.one-page').onePageNav();
		}
		
		function one_nav(){
			if($('.one-2').length){
				var tp2 = $('#home').outerHeight();
				if(ScrlTop >= tp2 ){
					$('.one-2').removeClass('diamond-grid').find('li').removeClass('diamonds');
					$('.one-2').addClass('side-one');
				}else{
					$('.one-2').removeClass('side-one').addClass('diamond-grid').find('li').addClass('diamonds');
				}
			}
		}
		
		one_nav();
				
		$('.one-page a').on("click",function(){
			one_nav();
		});
				
		$(window).on("scroll",function(){
			var ScTop = $(window).scrollTop();
			
			/* ================ Sticky Nav. =============== */
			if($('.top-head').attr('data-sticky') == "true"){
				if (ScTop > 10) {
					$('.top-head').addClass('sticky-nav');
					if($('.top-head').hasClass('sticky-nav')){
						var logo = $('.logo').find('img').attr('src').replace("logo-light.png", "logo.png");
						$('.logo').find('img').attr('src',logo);
					}
				}else{
					$('.top-head').removeClass('sticky-nav');
					if($('.top-head').hasClass('dark') || $('.logo').hasClass('light')){
						var logo = $('.logo').find('img').attr('src').replace("logo.png", "logo-light.png");
						$('.logo').find('img').attr('src',logo);
					}
				}
			}
			if (ScTop > 1) {
				$('#to-top').css({'transform':'translate3d(0,0,0)'});
			} else {
				$('#to-top').css({'transform':'translate3d(50px,50px,0)'});
			}
			
			/* ================ One Page side menu. =============== */
			if($('#side_one').length > 0){
				var scrollHeight2 = $(document).scrollTop(),
					tp2 = $('#home').outerHeight();
				if($(this).scrollTop() > tp2 ){
					$('#side_one').css('left','0px');
				}else{
					$('#side_one').css('left','-100%');
				}
			}
			
			/* ================ One Page Function. =============== */
			one_nav();
			
		});
				
	});

})(jQuery);


// Latest tweets sliders.
var animatetweets = function (){
	
	$('.slick-s').slick({
		dots: false,
		arrows: true,
		vertical: true
	});
	$('.fade-slider').slick({
		dots: false,
		arrows: true,
		fade: true
	});
}
