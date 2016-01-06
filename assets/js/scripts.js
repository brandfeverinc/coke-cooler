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
	
	$('.prod-icon').on('click',function(){
		$('.close-form').trigger('click');
		$('.rotate-carousel-indicators').fadeOut();
		$('.open-not-active').fadeOut();
		$('.carousel-inner').fadeIn();
		$('.top-section').css('border-bottom','none');
		$('.bottom-section').css('display','block');
		$('.rotate-menu-item').css('display', 'block');
		$('.rotate-menu-item-active').css('display', 'none');
		$('.open-door').attr('src','assets/img/opendoor.png');
		$('.open-door').removeClass('open-door-active');
		var activeItem = $('.active-item').parent().parent().attr('data-id');
		$('.active-item').attr('src','assets/img/'+activeItem+'.png');
		$('.active-item').removeClass('active-item')
		var newItem = $(this).parent().parent().attr('data-id');
		$(this).attr('src','assets/img/'+newItem+'-active.png');
		$(this).addClass('active-item');
	});

	$('.rotate-icon').on('click',function(){
		$('.close-form').trigger('click');
		$('.open-not-active').fadeOut();
		$('.carousel-inner').fadeIn();
		$('.open-door').attr('src','assets/img/opendoor.png');
		$('.open-door').removeClass('open-door-active');
		if( $(this).parent().parent().hasClass('rotate-menu-item-active') ){
			$(this).parent().parent().css('display','none');
			$('.rotate-menu-item').css('display', 'block');
		}else if( $(this).parent().parent().hasClass('rotate-menu-item') ){
			$(this).parent().parent().css('display','none');
			$('.rotate-menu-item-active').css('display', 'block');
		}
	});

	$('.open-door').on('click',function(e){
		e.preventDefault();
		$('.close-form').trigger('click');
		$('.rotate-carousel-indicators').fadeOut();
		$('.modalx-container').trigger('click')
		$('.rotate-menu-item').css('display', 'block');
		$('.rotate-menu-item-active').css('display', 'none');
		if( $('.open-door').hasClass('open-door-active') ){
			$(this).attr('src','assets/img/opendoor.png');
			$(this).removeClass('open-door-active');
			$('.open-not-active').fadeOut( 300, function() {
			    $('.carousel-inner').fadeIn();
			});
		}else{
			$(this).attr('src','assets/img/opendoor-active.png');
			$(this).addClass('open-door-active');
			$('.carousel-inner').fadeOut( 300, function() {
			    $('.open-not-active').fadeIn();
			});
		}
	});

	// go to previous page
	$('.back-btn').on('click touch', function(){
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
		$('.active-item').attr('src','assets/img/'+activeItem+'.png');
		$('.active-item').removeClass('active-item')
		$('.active-menu-item').removeClass('active-menu-item');
		$( ".product-lightbox-container" ).animate({
		    top: "-110%",
		}, 400, function() {
			//clears lightbox content
			$('.product-lightbox-container .top-section, .product-lightbox-container .bottom-content').html('');
			$('.product-lightbox-container .top-section').css('border-bottom','none');
			$('.product-lightbox-container .bottom-section').css('display','block');
		});
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
	$('#rotate-product-carousel').carousel({
	  interval: false
	});

	// adds info data to the lightbox
	$('.info-menu-item').on('click',function(){
		$('.product-lightbox-container .top-section').html('<div id="product-info-carousel" class="carousel slide" data-ride="carousel"><div class="carousel-inner" role="listbox"></div></div>');
		$('.product-lightbox-container .bottom-content').prepend('<div class="slider-container"><div class="slider-controls"><div class="prev"><a href="product-slider-carousel" role="button" data-slide="prev"><img src="assets/img/red-arrow.png" alt="arrow"></a></div><div class="next"><a href="product-slider-carousel" role="button" data-slide="next"><img src="assets/img/red-arrow.png" alt="arrow"></a></div></div></div>');
		$(info_images).each(function(i, data){
			$('#product-info-carousel .carousel-inner').append('<div class="item"><img src="assets/img/'+data+'" alt="Info Image"></div>');
			$('.info-carousel-indicators').append('<li data-target="#product-info-carousel" data-slide-to="'+i+'"></li>');
		});
		$('#product-info-carousel .carousel-inner .item').first().addClass('active');
		$('.info-carousel-indicators li').first().addClass('active');
		$('.info-carousel-indicators li').on('click',function(){
			$('.info-carousel-indicators .active').removeClass('active');
			$(this).addClass('active');
		});
		$('.product-lightbox-container .bottom-content').append('<div class="show-form-container"><a href="" class="show-form">Request Info</a><a href="" class="show-form show-form-img"><img src="assets/img/mail_button.png"></a></div><div class="clear"></div><ol class="info-buttons"><li class="active" data-id="1">About</li><li data-id="2">Specs</li><li data-id="3">Technology</li><li data-id="4">Design</li></ol><div class="content"><div class="active info-content-section info-content-section-1">Section 1</div><div class="info-content-section info-content-section-2">Section 2</div><div class="info-content-section info-content-section-3">Section 3</div><div class="info-content-section info-content-section-4">Section 4</div></div>');
	});
	


	$(document).on('click', '.show-form', function(e){
		e.preventDefault();
		$('.form-section').animate({
			top: "0px"
		}, 300, function() {
		    // Animation complete.
		});
	});

	$(document).on('click', '.close-form', function(e){
		e.preventDefault();
		$('.form-section').animate({
			top: "400px"
		}, 300, function() {
		    // Animation complete.
		});
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

	// adds video data to the lightbox
 	$('.video-menu-item').on('click',function(){
 		$('.product-lightbox-container .bottom-section').css('display','none');
 		$('.product-lightbox-container .top-section').html('<div id="product-video-carousel" class="carousel slide" data-ride="carousel"><div class="carousel-inner" role="listbox"></div></div>').css('border-bottom','#FF1D25 solid 55px');
		$(product_videos.videos).each(function(i, data){
			$('#product-video-carousel .carousel-inner').append('<div class="item"><iframe width="560" height="315" src="'+data.video+'" frameborder="0"></iframe><div class="video-info-container"><div class="carousel-caption">'+data.title+'</div><div class="fullscreen"></div></div>');
		});
		$('.product-lightbox-container .top-section').append('<div class="video-info-container"><div class="video-slider-controls"><div class="prev"><a href="product-video-carousel" role="button" data-slide="prev"><img src="assets/img/red-arrow.png" alt="arrow"></a></div><div class="next"><a href="product-video-carousel" role="button" data-slide="next"><img src="assets/img/red-arrow.png" alt="arrow"></a></div></div></div>');
		$('#product-video-carousel .carousel-inner .item').first().addClass('active');
 	});
	
	$('.show-menu-item').on('click',function(){
		$('.product-lightbox-container .top-section').html('<div id="product-video-carousel" class="carousel slide" data-ride="carousel"><div class="carousel-inner" role="listbox"></div></div>');
		$('.product-lightbox-container .bottom-content').prepend('<div class=bottom-top-container><div class=col-sm-2><div class=video-slider-controls><div class=prev><a href=product-video-carousel role=button data-slide=prev><img src=assets/img/red-arrow.png alt=arrow></a></div><div class=next><a href=product-video-carousel role=button data-slide=next><img src=assets/img/red-arrow.png alt=arrow></a></div></div></div><div class=col-sm-8><h2>Title</h2></div><div class=col-sm-2><div class=fullscreen></div></div></div><div class=bottom-bottom-container><div class=prev><a href=product-video-carousel role=button data-slide=prev><img src=assets/img/red-arrow.png alt=arrow></a></div><div class=bottom-slides><div><img src=assets/img/show-thumb.png></div><div><img src=assets/img/show-thumb.png></div><div><img src=assets/img/show-thumb.png></div></div><div class=next><a href=product-video-carousel role=button data-slide=next><img src=assets/img/red-arrow.png alt=arrow></a></div></div>');
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
		$('.active').removeClass('active');
		$(this).parent().addClass('active');
		$('.technology-content .'+this_tab).addClass('active');
	});

	// toggles pod active state (and soon play sound)
	$('.pod').on('click',function(e){
		if( !$(this).hasClass('active') ){
			$('.pods .active').removeClass('active');
			$(this).addClass('active');
		}
	});
	$('.pod-x').on('click',function(e){
		setTimeout(function(){
			$('.pod.active').removeClass('active');
		}, 200);
	});


	//mouse down icon states
	$('.icon-mousedown').on('touchstart mousedown',function(){
		var img_src = $(this).attr('data-id');
		$(this).find('img').attr('src','assets/img/'+img_src+'-active.png');
	});


	
    
    

	
});

