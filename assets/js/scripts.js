$( document ).ready(function() {
	// Variables
	var menu_item;
	var thisInput;
	// Toggles menu slide in and out
	$( '.menu-toggle' ).on( 'click touch', function(e) {
		e.preventDefault();
	    $('.transparent-bg').fadeIn( "fast", function() {
		    $( ".menu-slideout-container" ).css('display','inline-block').animate({
			    left: '10%',
			}, 400, function() {
			});
		});
	});
	$( '.menu-close' ).on( 'click touch', function(e) {
		e.preventDefault();
		$( ".menu-slideout-container" ).animate({
		    left: "110%",
		}, 400, function() {
		    $('.transparent-bg').fadeOut( "fast", function() {});
		    $( ".menu-slideout-container" ).css('display','none');
		});
	});
	
	// go to previous page
	$('.back-btn').on('click touch', function(e){
		e.preventDefault();
		window.history.back();
	});

	//toggles light box in and out
	$('.activate-lightbox').on('click touch', function(e) {
		e.preventDefault();
		//activate the lightbox
		if( $('.left-menu-activated').hasClass('active-menu-item') ){
			$('.active-menu-item').removeClass('active-menu-item left-menu-activated');
		}
		$('.product-lightbox-container').addClass('active')
		$('.product-lightbox-container .top-section, .product-lightbox-container .bottom-content').html('');
		if( !$('.active-menu-item').hasClass('active-menu-item') ){
			$(this).addClass('active-menu-item');
			$('.modalx-container').attr('data-id', menu_item);
			$( ".product-lightbox-container" ).animate({
			    top: "20%",
			}, 400, function() {
				//ANIMATION COMPLETE
			});
		}
	});
	//closes lightbox
	$('.modalx-container').on('click touch', function(e) {
		e.preventDefault();
		var activeItem = $('.active-item').parent().parent().attr('data-id');
		$('.active-item').attr('src','/assets/img/'+activeItem+'.png');
		$('.active-item').removeClass('active-item');
		$('.active-menu-item').removeClass('active-menu-item');
		if ($("#underlay").css("display") == "block") {
			// fullscreen mode, undo it:
			// unset fullscreen mode settings:
			$("#underlay").hide();
			$(".modaltitle-container").hide();
			$(".product-lightbox-container").css("width", "");
			$(".product-lightbox-container").css("margin-left", "");
			$(".product-lightbox-container .top-section").css("background-color", "");
			$(".product-lightbox-container .modalx-container").css("background-color", "");
			$(".product-lightbox-container .modalx-container").css("margin-right", "");
			$(".product-lightbox-container .videoplay, .product-lightbox-container .videopause").css("margin-right", "");
            $(".product-lightbox-container .video-slider-controls").css("margin-top", "");
			$(".product-lightbox-container .fullscreen").show();
            $(".carousel-caption").show();
            $(".bottom-top-container div h2").show();
            $(".bottom-bottom-container").show();
			$(".bottom-section").css("background-color", "");
			$(".bottom-section").css("border-bottom", "");
		}
		else {
			// not fullscreen mode, close popup:
			$( ".product-lightbox-container" ).animate({
				top: "-110%",
			}, 400, function() {
				// clears lightbox content
				$('.product-lightbox-container .top-section, .product-lightbox-container .bottom-content').html('');
				$('.product-lightbox-container .top-section').css('border-bottom','none');
				$('.product-lightbox-container .bottom-section').css('display','block');
			
				// unset fullscreen mode settings:
				$("#underlay").hide();
				$(".modaltitle-container").hide();
				$(".product-lightbox-container").css("width", "");
				$(".product-lightbox-container").css("margin-left", "");
				$(".product-lightbox-container .top-section").css("background-color", "");
				$(".product-lightbox-container .modalx-container").css("background-color", "");
				$(".product-lightbox-container .modalx-container").css("margin-right", "");
				$(".product-lightbox-container .videoplay, .product-lightbox-container .videopause").css("margin-right", "");
            	$(".product-lightbox-container .video-slider-controls").css("margin-top", "");
				$(".bottom-section").css("background-color", "");
				$(".bottom-section").css("border-bottom", "");
			});
		}
	});
	
	function activateMenu(menuItem, e){
		e.preventDefault();
		// kills lightbox if its open
		if( $('.product-lightbox-container').hasClass('active') ){
			$('.modalx-container, .active-menu-item').trigger('click');
			$('.left-nav .active, .product-lightbox-container').removeClass('active');
		}		
		if ( !$('.active-menu-item').hasClass('active-menu-item') ) {
			menuItem.parent().addClass('active-menu-item left-menu-activated');
		}else if( menuItem.parent().hasClass('active-menu-item') ){
			$('.active-menu-item').removeClass('active-menu-item left-menu-activated');
		}else if($('.active-menu-item').hasClass('active-menu-item')){
			$('.active-menu-item').removeClass('active-menu-item left-menu-activated');
			menuItem.parent().addClass('active-menu-item left-menu-activated');
		}
	}
	// activate the menu toggles
	$('.activate-menu-toggle').on('click touch', function(e) {
		var thisMenuItem = $(this);
		activateMenu(thisMenuItem, e);
	});
	$('.active-menu-item').on('click touch', function(e) {
		var thisMenuItem = $(this);
		activateMenu(thisMenuItem, e);
	});

	// makes carousel dots appear when slides can be toggled through
	$('.rotate-menu-item').on('click',function(){
		if( !$(this).hasClass('left-menu-activated') ){
			$('.rotate-carousel-indicators').fadeOut();
		}else{
			$('.rotate-carousel-indicators').fadeIn();
		}
	});
	// turns off slide auto increment 
	$('#rotate-product-carousel, #carousel-equipment, #carousel-slideout-products, #carousel-slideout-tech, #tech-single, #carousel-ip, #product-video-carousel').carousel({
	  interval: false
	});

	$(document).on('click', '.show-form', function(e){
		e.preventDefault();
		$('.form-section').animate({
			top: "0px"
		}, 300, function() {
		    // Animation complete.
		});
		$(".product-lightbox-container #product-info-carousel").carousel('pause');
	});

	$(document).on('click', '.close-form', function(e){
		e.preventDefault();
		$('.form-section').animate({
			top: "500px"
		}, 300, function() {
		    // Animation complete.
            $("#name").val("");
            $("#email").val("");
            $("#question").val("");
		    $("#form-div").show();
		    $("#thankDiv").hide();
		});
		$(".product-lightbox-container #product-info-carousel").carousel('cycle');
	});
	
	//info lightbox tabs
	$(document).on('click','.info-buttons li',function(){
		var itemID = $(this).attr('data-id');
		if(!$(this).hasClass('active')){
			$('.info-buttons .active').removeClass('active');
			$(this).addClass('active');
			$('.bottom-section .content .active').fadeOut( "fast", function() {
			    $('.bottom-section .content .active').removeClass('active');
			    $('.bottom-section .content .info-content-section-'+itemID).fadeIn().addClass('active');
			});
		}
	});

	//technology menu tab toggle
	$('.technology-toggle a').on('click',function(){
		$('.technology-toggle .active').removeClass('active');
		$(this).addClass('active');
		if( $(this).hasClass('tab1') ){
			$('body').css('background','#FF1D25');
			$('.tab-2').css('display','none');
			$('.tab-1').css('display','block');
			$('.background-gradient').css('display','block');
		}else{
			$('body').css('background','#000');
			$('.tab-1').css('display','none');
			$('.tab-2').css('display','block');
			$('.background-gradient').css('display','none');
		}
	});

	// single technology page tabs
	$('.technology-menu a').on('click',function(e){
		e.preventDefault();
		var this_tab = $(this).attr('class');
		$('.active-item').removeClass('active-item');
		$(this).parent().addClass('active-item');
		$('.technology-content .'+this_tab).addClass('active-item');
	});

	//mouse down icon states
	$('.icon-mousedown').on('touchstart mousedown',function(){
		var img_src = $(this).attr('data-id');
		//$(this).find('img').attr('src','assets/img/'+img_src+'-active.png');
		$(this).find('img').attr('src',img_src);
	});

	$('.css-mousedown').on('touchstart mousedown',function(){
		$(this).find('.menu-nav-item-bg').addClass('active');
	});

    // disable normal touch handling when inside sliders (so swipe works without secondary screen slide):
    document.ontouchmove = function(e){
        if ((e.target.id == "top-section" || $(e.target).parents("#top-section").size()) || (e.target.id == "bottom-section" || $(e.target).parents("#bottom-section").size()) || (e.target.id == "rotate-product-carousel" || $(e.target).parents("#rotate-product-carousel").size()) || (e.target.id == "carousel-slideout-products" || $(e.target).parents("#carousel-slideout-products").size()) || (e.target.id == "carousel-slideout-tech" || $(e.target).parents("#carousel-slideout-tech").size()) || (e.target.id == "tech-single" || $(e.target).parents("#tech-single").size())) { 
            event.preventDefault();
        }
    }
	
}); // end document ready

function validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}