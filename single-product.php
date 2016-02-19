<?php
session_start();
if (!isset($_SESSION['sessCategory'])) {
    header('Location: index.php');
    exit;
}

include_once("inc/classes/Item.php");
include_once("inc/classes/ItemImage.php");
include_once("inc/classes/ItemInfo.php");
include_once("inc/classes/ItemInfoImage.php");
include_once("inc/classes/ItemInfoType.php");
include_once("inc/classes/ItemVideo.php");
include_once("inc/classes/ItemGalleryImage.php");
include_once("inc/classes/ItemPresentation.php");
include_once("inc/classes/ItemPresentationImage.php");
include_once("inc/classes/ItemImageHighlight.php");
include_once("inc/classes/Category.php");

$id = $_REQUEST['id'];
$item = new Item($id);
$bgcolor = "#F40000"; // Coke red default
if ($item->BackgroundColor != "") {
	$bgcolor = $item->BackgroundColor;
}
else {
	// otherwise use the category background color:
	$category = new Category($item->CategoryId);
	if ($category->BackgroundColor != "") {
		$bgcolor = $category->BackgroundColor;
	}
}
$background = $bgcolor . " none repeat scroll 0% 0%"; // this is used in single-product-template.php

include("inc/single-product_template.php");
    
function pageContent() {
    global $id, $item;
    $objItemInfo = new ItemInfo();
    $oItemInfo = $objItemInfo->getAllItemInfoByItemId($item->Id);
    $info_types_list = '<ol class="info-buttons">';
    $i = 0;
    foreach ($oItemInfo as $item_info) {
        $info_type = new ItemInfoType($item_info->ItemInfoTypeId);
        $class = '';
        if ($i == 0) {
            $class = ' class="active"';
        }
        $info_types_list .= '<li' . $class . ' data-id="' . $item_info->ItemInfoTypeId . '">' . $info_type->ItemInfoTypeName . '</li>';
        $i++;
    }
    //$info_types_list .= '<li data-id="2">Specs</li>';
    $info_types_list .= '</ol>';
    $info_types_content = '<div class="content">';
    $i = 0;
    foreach ($oItemInfo as $item_info) {
        $active_class = '';
        if ($i == 0) {
            $active_class = 'active ';
        }
        $info_types_content .= '<div class="' . $active_class . 'info-content-section info-content-section-' . $item_info->ItemInfoTypeId . '">' . preg_replace("/[\r\n]/", "", $item_info->ItemInfo) . '</div>';
        $i++;
    }
    $info_types_content .= '</div>';
    
    $objItemInfoImage = new ItemInfoImage();
    $oItemInfoImage = $objItemInfoImage->getAllItemInfoImageByItemId($id);
    $info_images = '';
    $i = 0;
    $info_image_count = 0;
    foreach ($oItemInfoImage as $image) {
        if ($i > 0) {
            $info_images .= ",";
        }
        $info_images .= "'/" . $image->GetPath() . $image->ItemInfoImageUrl . "'";
        $i++;
    }
    $info_image_count = $i;
    $info_images = "var info_images = [" . $info_images . "];\n";

    $frontimage = '';
    $rightimage = '';
    $leftimage = '';
    $backimage = '';
    $frontimage_id = 0;
    $rightimage_id = 0;
    $leftimage_id = 0;
    $backimage_id = 0;    
    $openimage = '';
    $rotate_sides = array();
    $objItemImage = new ItemImage();
    $obj = $objItemImage->getAllItemImageByItemIdItemImageSide($id, 'Open');
    if (count($obj) > 0) {
        $itemimage = $obj[0];
        $openimage = '/' . $itemimage->GetPath() . $itemimage->ItemImageUrl;
    }
    $obj = $objItemImage->getAllItemImageByItemIdItemImageSide($id, 'Front');
    if (count($obj) > 0) {
        $itemimage = $obj[0];
        $rotate_sides[] = 'Front';
        $frontimage = '/' . $itemimage->GetPath() . $itemimage->ItemImageUrl;
        $frontimage_id = $itemimage->Id;
    }
    $obj = $objItemImage->getAllItemImageByItemIdItemImageSide($id, 'Right');
    if (count($obj) > 0) {
        $itemimage = $obj[0];
        $rotate_sides[] = 'Right';
        $rightimage = '/' . $itemimage->GetPath() . $itemimage->ItemImageUrl;
        $rightimage_id = $itemimage->Id;
    }
    $obj = $objItemImage->getAllItemImageByItemIdItemImageSide($id, 'Back');
    if (count($obj) > 0) {
        $itemimage = $obj[0];
        $rotate_sides[] = 'Back';
        $backimage = '/' . $itemimage->GetPath() . $itemimage->ItemImageUrl;
        $backimage_id = $itemimage->Id;
    }
    $obj = $objItemImage->getAllItemImageByItemIdItemImageSide($id, 'Left');
    if (count($obj) > 0) {
        $itemimage = $obj[0];
        $rotate_sides[] = 'Left';
        $leftimage = '/' . $itemimage->GetPath() . $itemimage->ItemImageUrl;
        $leftimage_id = $itemimage->Id;
    }

    $objItemVideo = new ItemVideo();
    $oItemVideo = $objItemVideo->getAllItemVideoByItemId($id);
    $product_videos = '';
    $i = 0;
    $video_count = 0;
    foreach ($oItemVideo as $video) {
        if ($i > 0) {
            $product_videos .= ",";
        }
        $vpath = "/" . $video->GetPath();
        if (preg_match("/http/", $video->ItemVideoUrl)) {
            // a URL, don't get from local directory:
            $vpath = "";
        }
        $product_videos .= '{"video":"' . $vpath . $video->ItemVideoUrl . '", "title":"' . $video->ItemVideoTitle . '"}';
        $i++;
    }
    $video_count = $i;
    $product_videos = 'var product_videos = {"videos":[' . "\n" . $product_videos . "\n" . '    ]}' . "\n";

    $objItemGalleryImage = new ItemGalleryImage();
    $oItemGalleryImage = $objItemGalleryImage->getAllItemGalleryImageByItemId($id);
    $gallery_images = '';
    $i = 0;
    $gallery_image_count = 0;
    foreach ($oItemGalleryImage as $image) {
        if ($i > 0) {
            $gallery_images .= ",";
        }
        $gallery_images .= "'/" . $image->GetPath() . $image->ItemGalleryImageUrl . "'";
        $i++;
    }  
    $gallery_image_count = $i;  
    $gallery_images = "var gallery_images = [" . $gallery_images . "];\n";
    
    $gallery_caption = "Gallery";
    if ($item->GalleryDescription != "") {
    	$gallery_caption = $item->GalleryDescription;
    }
    
    $objItemPresentation = new ItemPresentation();
    $objItemPresentationImage = new ItemPresentationImage();
    $oItemPresentation = $objItemPresentation->getAllItemPresentationByItemId($id);
    $product_shows = "";
    $show_images = array();
    $product_show_images = '';
    $i = 0;
    $show_count = 0;
    foreach ($oItemPresentation as $show) {
        if ($i > 0) {
            $product_shows .= ",";
        }
        $product_shows .= '{"thumbnail":"/' . $show->GetPath() . $show->ItemPresentationThumbnailUrl . '", "title":"' . $show->ItemPresentationName . '"}';

        $oItemPresentationImage = $objItemPresentationImage->getAllItemPresentationImageByItemPresentationId($show->Id);
        $j = 0;
        foreach ($oItemPresentationImage as $image) {
            if ($j > 0) {
                $show_images[$i] .= ",";
            }
            $show_images[$i] .= "'/" . $image->GetPath() . $image->ItemPresentationImageUrl . "'";
            $j++;
        }
        $product_show_images .= "            show_images[" . $i . "] = [" . $show_images[$i] . "];\n";
        $i++;    
    }
    $show_count = $i;
    $product_shows = 'var product_shows = {"shows":[' . "\n" . $product_shows . "\n" . '    ]}' . "\n";
    
    $objItemImageHighlight = new ItemImageHighlight();

?>
        <script type="text/javascript">
			var current_show = 0;
			var show_needs_full_load = true;
            var rotate_sides = [];
<?php
            foreach ($rotate_sides as $side) {
                echo "            rotate_sides.push('" . $side . "');\n";
            }
?>
            var rotate_current_item = 0;
            <?php echo $info_images; ?>
            <?php echo $product_videos; ?>
            <?php echo $gallery_images; ?>
            var show_images = [];
<?php echo $product_show_images; ?>
            <?php echo $product_shows; ?>

            $(document).ready(function() {              
                $("#email_form_submit").on('click touch', function() {

                    if ($("#name").val() == "" || $("#email").val() == "" || $("#question").val() == "") {
                        alert("All fields are required.");
                        return false;
                    };
                    if (!validateEmail($("#email").val())) {
                        alert("Email address is invalid.")
                        return false;
                    }
                
                    $.ajax({
                    url: "ajax_send_email.php",
                        type: 'GET',
                        data: {
                            name: $("#name").val(),
                            email: $("#email").val(),
                            question: $("#question").val(),
                            item_id: "<?php echo $item->Id; ?>",
                            category_id: "<?php echo $item->CategoryId; ?>"
                        },
                        success: function(data){
                            if (data == '1') {
                                $("#thankDiv").show();
                                $("#form-div").hide();
                                $("#name").val("");
                                $("#email").val("");
                                $("#question").val("");
                            }
                            else {
                                alert("Error: Please try again.");
                            }
                        },
                        error: function(){
                            console.log('error');
                        }
                    });
                    return false;
                });

				$('.prod-icon').on('click',function(){
                	show_needs_full_load = true;
                	current_show = 0;
					$('.close-form').trigger('click');
					$('.rotate-carousel-indicators').fadeOut();
					$('.open-not-active').fadeOut();
					$('.carousel-inner').fadeIn();
					$('.top-section').css('border-bottom','none');
					$('.bottom-section').css('display','block');
					$('.rotate-menu-item').css('display', 'block');
					$('.rotate-menu-item-active').css('display', 'none');
					$('.open-door').attr('src','/assets/img/opendoor.png');
					$('.open-door').removeClass('open-door-active');
					var activeItem = $('.active-item').parent().parent().attr('data-id');
					$('.active-item').attr('src','/assets/img/'+activeItem+'.png');
					$('.active-item').removeClass('active-item')
					var newItem = $(this).parent().parent().attr('data-id');
					$(this).attr('src','/assets/img/'+newItem+'-active.png');
					$(this).addClass('active-item');
				});

				$('.rotate-icon').on('click',function(){
                	show_needs_full_load = true;
                	current_show = 0;
					$('.close-form').trigger('click');
					$('.open-not-active').fadeOut();
					$('.carousel-inner').fadeIn();
					$('.open-door').attr('src','/assets/img/opendoor.png');
					$('.open-door').removeClass('open-door-active');
					if( $(this).parent().parent().hasClass('rotate-menu-item-active') ){
						$(this).parent().parent().css('display','none');
						$('.rotate-menu-item').css('display', 'block');
					}else if( $(this).parent().parent().hasClass('rotate-menu-item') ){
						$(this).parent().parent().css('display','none');
						$('.rotate-menu-item-active').css('display', 'block');
					}
				});
				
				var nav_controls = ""; // used in lightbox add functions below

                // adds info data to the lightbox
                $('.info-menu-item').on('click touch',function () {
                	show_needs_full_load = true;
                    $("#name").val("");
                    $("#email").val("");
                    $("#question").val("");
                    $("#form-div").show();
                    $("#thankDiv").hide();
                	current_show = 0;
                    $('.product-lightbox-container .top-section').html('<div id="product-info-carousel" class="carousel slide" data-ride="carousel"><div class="carousel-inner" role="listbox"></div></div>');
                    nav_controls = "";
                    if (info_images.length > 1) {
                    	nav_controls = '<div class="prev"><a href="#product-info-carousel" role="button" data-slide="prev"><img src="assets/img/red-arrow.png" alt="arrow"></a></div><div class="next slider-next"><a href="#product-info-carousel" role="button" data-slide="next"><img src="assets/img/red-arrow.png" alt="arrow"></a></div>';
                    }
                    $('.product-lightbox-container .bottom-content').prepend('<div class="slider-container"><div class="slider-controls">'+nav_controls+'</div></div>');
                    $(info_images).each(function(i, data){
                        $('#product-info-carousel .carousel-inner').append('<div class="item"><img src="'+data+'" alt="Info Image"></div>');
                        $('.info-carousel-indicators').append('<li data-target="#product-info-carousel" data-slide-to="'+i+'"></li>');
                    });
                    $('#product-info-carousel .carousel-inner .item').first().addClass('active');
                    $('.info-carousel-indicators li').first().addClass('active');
                    $('.info-carousel-indicators li').on('click',function(){
                        $('.info-carousel-indicators .active').removeClass('active');
                        $(this).addClass('active');
                    });
                    $('.product-lightbox-container .bottom-content').append('<div class="show-form-container"><a href="" class="show-form">Request Info</a><a href="" class="show-form show-form-img"><img src="assets/img/mail_button.png"></a></div><div class="clear"></div><?php echo $info_types_list . $info_types_content; ?>');
                });
                
                $('.rotate-menu-item-active .next-view').on('click touch', function() {
                    rotate_current_item++;
                    if (rotate_current_item >= rotate_sides.length) {
                        rotate_current_item = 0;
                    }
                    $('#rotate-title').text(rotate_sides[rotate_current_item]);
                });

                $('.rotate-menu-item-active .prev-view').on('click touch', function() {
                    rotate_current_item--;
                    if (rotate_current_item < 0) {
                        rotate_current_item = (rotate_sides.length - 1);
                    }
                    $('#rotate-title').text(rotate_sides[rotate_current_item]);
                });

                $('.open-door').on('click',function(e){
                	show_needs_full_load = true;
                	current_show = 0;
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

                // adds video data to the lightbox
                $('.video-menu-item').on('click',function(){
                	show_needs_full_load = true;
                	current_show = 0;
                    $('.product-lightbox-container .bottom-section').css('display','none');
                    $('.product-lightbox-container .top-section').html('<div id="product-video-carousel" class="carousel slide" data-ride="carousel"><div class="carousel-inner" role="listbox"></div></div>').css('border-bottom','#FF1D25 solid 55px');
                    $(product_videos.videos).each(function(i, data){
                    	var mimetype = "video/mp4";
                    	var ext = data.video.split('.').pop();
                    	if (ext == "mov") {
                    		mimetype = "video/quicktime";
                    	}
                    	else if (ext == "wmv") {
                    		mimetype = "video/x-ms-wmv";
                    	}
                    	else if (data.video.indexOf("webm")) {
                    	    mimetype = "video/webm";
                    	}
                        $('#product-video-carousel .carousel-inner').append('<div class="item"><video style="width:560px; height:315px;"><source src="'+data.video+'" type="'+mimetype+'"></video><div class="video-info-container"><div class="carousel-caption">'+data.title+'</div><div class="fullscreen"></div><div class="videoplay" ><img src="assets/img/red_play.png" style="width:100%; height:100%;"></div><div class="videopause" style="display:none;"><img src="assets/img/red_pause.png" style="width:100%; height:100%;"></div><div class="video-time" style="float:right; margin-right:20px; color:red; display:none;">Length: <span id="video-length"></span></div></div>');
                    });
                    nav_controls = "";
                    if (product_videos.videos.length > 1) {
                    	nav_controls = '<div class="prev"><a href="#product-video-carousel" role="button" data-slide="prev"><img src="assets/img/red-arrow.png" alt="arrow"></a></div><div class="next slider-next"><a href="#product-video-carousel" role="button" data-slide="next"><img src="assets/img/red-arrow.png" alt="arrow"></a></div>';
                    }
                    $('.product-lightbox-container .top-section').append('<div class="video-info-container"><div class="video-slider-controls">'+nav_controls+'</div></div>');
                    $('#product-video-carousel .carousel-inner .item').first().addClass('active');
                });

                // adds gallery data to the lightbox
                $('.gallery-menu-item').on('click',function(){
                	show_needs_full_load = true;
                	current_show = 0;
                    $('.product-lightbox-container .bottom-section').css('display','none');
                    $('.product-lightbox-container .top-section').html('<div id="product-gallery-carousel" class="carousel slide" data-ride="carousel"><div class="carousel-inner" role="listbox"></div></div>').css('border-bottom','#FF1D25 solid 55px');
                    $(gallery_images).each(function(i, data){
                        $('#product-gallery-carousel .carousel-inner').append('<div class="item"><img src="'+data+'" alt="Gallery Image"></div>');
                        $('.info-carousel-indicators').append('<li data-target="#gallery-info-carousel" data-slide-to="'+i+'"></li>');
                    });
                    nav_controls = "";
                    if (gallery_images.length > 1) {
                    	nav_controls = '<div class="prev"><a href="#product-gallery-carousel" role="button" data-slide="prev"><img src="assets/img/red-arrow.png" alt="arrow"></a></div><div class="next slider-next"><a href="#product-gallery-carousel" role="button" data-slide="next"><img src="assets/img/red-arrow.png" alt="arrow"></a></div>';
                    }
                    $('.product-lightbox-container .top-section').append('<div class="gallery-info-container"><div class="carousel-caption"><?php echo $gallery_caption; ?></div><div class="fullscreen"></div><div class="gallery-slider-controls">'+nav_controls+'</div></div>');
                    $('#product-gallery-carousel .carousel-inner .item').first().addClass('active');
                });

                // adds show/presentation data to the lightbox
                $('.show-menu-item').on('click',function(){
                	$('#bottom-slides div').removeClass("selected");
                    $('.product-lightbox-container .top-section').html('<div id="product-show-carousel" class="carousel slide" data-ride="carousel"><div class="carousel-inner" role="listbox"></div></div>');
                    $(show_images[current_show]).each(function(i, data){
                        $('#product-show-carousel .carousel-inner').append('<div class="item"><img src="'+data+'" alt="Gallery Image"></div>');
                        $('.info-carousel-indicators').append('<li data-target="#show-info-carousel" data-slide-to="'+i+'"></li>');
                    });
                	if (show_needs_full_load) {
						nav_controls = "";
						if (show_images.length > 1) {
							nav_controls = '<div class="prev"><a href="#product-show-carousel" role="button" data-slide="prev"><img src="assets/img/red-arrow.png" alt="arrow"></a></div><div class="next slider-next"><a href="#product-show-carousel" role="button" data-slide="next"><img src="assets/img/red-arrow.png" alt="arrow"></a></div>';
						}
                    	$('.product-lightbox-container .bottom-content').html('<div class="bottom-top-container"><div class="col-sm-2"><div class="show-slider-controls">'+nav_controls+'</div></div><div class="col-sm-8"><h2>'+product_shows.shows[current_show].title+'</h2></div><div class="col-sm-2"><div class="fullscreen"></div></div></div>');
						// bottom slider to switch show/presentation:
						nav_controls = "";
						if (product_shows.shows.length > 3) {
							nav_controls = '<div class="prev"><a href="javascript:void(0);" id="bottom-show-prev"><img src="assets/img/red-arrow.png" alt="arrow"></a></div><div id="bottom-slides-outer"><div id="bottom-slides"></div></div><div class="next slider-next"><a href="javascript:void(0);" id="bottom-show-next"><img src="assets/img/red-arrow.png" alt="arrow"></a></div>';
						}
						$('.product-lightbox-container .bottom-content').append('<div class="bottom-bottom-container">'+nav_controls+'</div>');
						$(product_shows.shows).each(function(i, data){
							$('#bottom-slides').append('<div id="bottom_slide_'+i+'"><img src="'+data.thumbnail+'"><p>'+data.title+'</p></div>');
						});
					}
					else {
						// not first load, only load top of bottom container
                    	$('.product-lightbox-container .bottom-content .bottom-top-container').html('<div class="col-sm-2"><div class="show-slider-controls"><div class="prev"><a href="#product-show-carousel" role="button" data-slide="prev"><img src="assets/img/red-arrow.png" alt="arrow"></a></div><div class="next slider-next"><a href="#product-show-carousel" role="button" data-slide="next"><img src="assets/img/red-arrow.png" alt="arrow"></a></div></div></div><div class="col-sm-8"><h2>'+product_shows.shows[current_show].title+'</h2></div><div class="col-sm-2"><div class="fullscreen"></div></div>');
					}
					$('#product-show-carousel .carousel-inner .item').first().addClass('active');
                    $('#bottom_slide_' + current_show).addClass("selected");
                    show_needs_full_load = false;
                });
                
                $(".product-lightbox-container").on("click touch", "#bottom-show-next", function() {
                	var newPos = $("#bottom-slides-outer").scrollLeft() + ($("#bottom-slides-outer").width() / 3);
                	$("#bottom-slides-outer").animate({scrollLeft: newPos}, 800);
                });

                $(".product-lightbox-container").on("click touch", "#bottom-show-prev", function() {
                	var newPos = $("#bottom-slides-outer").scrollLeft() - ($("#bottom-slides-outer").width() / 3);
                	$("#bottom-slides-outer").animate({scrollLeft: newPos}, 800);
                });
                
                $(".product-lightbox-container").on("click touch", "#bottom-slides div", function() {
                	var id = $(this).attr("id").replace("bottom_slide_", "");
                	current_show = id;
                    $('.show-menu-item').click();
                });
                
                $(".product-lightbox-container").on("click touch", ".fullscreen", function() {
                 	$("#underlay").show();
                 	$(".product-lightbox-container").css("width", "100%");
                 	$(".product-lightbox-container").css("margin-left", "0");
                 	$(".product-lightbox-container .top-section").css("background-color", "black");
                 	$(".product-lightbox-container .top-section").css("border-bottom", "0");
                 	$(".product-lightbox-container .modalx-container").css("background-color", "transparent");
                 	$(".product-lightbox-container .modalx-container").css("margin-right", "2%");
                 	$(".product-lightbox-container .videoplay, .product-lightbox-container .videopause").css("margin-right", "0");
                 	$(".product-lightbox-container .video-slider-controls").css("margin-top", "20px");
                 	$(".product-lightbox-container .video-info-container").css("width", "94%");
                 	$(".fullscreen").hide();
                 	$(".bottom-section").css("background-color", "black");
                 	$(".bottom-section").css("border-bottom", "0");
                 	$(".bottom-top-container").css("border-bottom", "0");
                 	$(".bottom-bottom-container").hide();
                 	$("#product-video-carousel video").css("width", "94%");
                 	$(".gallery-info-container").css("width", "94%");
                 	$("#product-video-carousel video").css("height", "");
                 	$(".carousel-caption").hide();
                 	if ($(".item.active .video-info-container .carousel-caption").length) {
                 		$(".modaltitle-container p").text($(".item.active .video-info-container .carousel-caption").text());
                 	}
                 	else if ($(".gallery-info-container .carousel-caption").length) {
                 		$(".modaltitle-container p").text($(".gallery-info-container .carousel-caption").text());
                 	}
                 	else if ($(".bottom-top-container div h2").length) {
                 		$(".modaltitle-container p").text($(".bottom-top-container div h2").text());
                 		$(".bottom-top-container div h2").hide();
                 	}
                 	$(".modaltitle-container").show();
                });

				$(".product-lightbox-container").on("click touch", ".videoplay", function() {
					$("video").trigger('pause');
					$(".product-lightbox-container #product-video-carousel .item.active video").trigger('play');
					$(".videoplay").hide();
					$(".videopause").show();
					//var duration_secs = $(".product-lightbox-container #product-video-carousel .item.active video").get(0).duration;
					//var minutes = Math.round(duration_secs / 60);
					//var seconds = Math.round(duration_secs % 60);
					//$("#video-length").text(minutes + ":" + seconds);
				});
				
				$(".product-lightbox-container").on("click touch", ".videopause, .video-info-container .next, .video-info-container .prev", function() {
					$("video").trigger('pause');
					$(".videopause").hide();
					$(".videoplay").show();
				});

				$(".product-lightbox-container").on("slid.bs.carousel", "#product-video-carousel", function() {
                 	$(".modaltitle-container p").text($(".item.active .video-info-container .carousel-caption").text());
				});
				
				$(".highlight").on("click touch", function(e) {
					$(this).next("div.highlight-text").toggle();
					$(this).toggleClass("highlight-active");
				});
				
				// swipe left/right on slideshows: touch handling
				document.addEventListener('touchstart', handleTouchStart, false);
				document.addEventListener('touchmove', handleTouchMove, false);

				var xDown = null;
				var yDown = null;
				var carousel_id = null;  // use to call carousel "next" or "prev" actions (default slideHandler)
				var slideHandler = null; // determines how to do slide action (default or other)
				var section = null;      // some swipes require this also for alternate or additional function call
				var nextClass = null;    // some swipes require this also for alternate or additional function call
				var prevClass = null;    // some swipes require this also for alternate or additional function call

				function handleTouchStart(e) {
					if (e.target.id == "top-section" || $(e.target).parents("#top-section").size()) { 
						xDown = e.touches[0].clientX;
						yDown = e.touches[0].clientY;
						slideHandler = "default"; // use default carousel action
						if (e.target.id == "product-info-carousel" || $(e.target).parents("#product-info-carousel").size()) {
						    carousel_id = "#product-info-carousel";
						}
						else if (e.target.id == "product-video-carousel" || $(e.target).parents("#product-video-carousel").size()) {
						    carousel_id = "#product-video-carousel";
						}
						else if (e.target.id == "product-gallery-carousel" || $(e.target).parents("#product-gallery-carousel").size()) {
						    carousel_id = "#product-gallery-carousel";
						}
						else if (e.target.id == "product-show-carousel" || $(e.target).parents("#product-show-carousel").size()) {
						    carousel_id = "#product-show-carousel";
						}
					}
					else if (e.target.id == "bottom-section" || $(e.target).parents("#bottom-section").size()) { 
						xDown = e.touches[0].clientX;
						yDown = e.touches[0].clientY;
						slideHandler = "bottom-div"; // uses slider-specific action
						section = ".product-lightbox-container .bottom-bottom-container";
						nextClass = " .next a:first";
						prevClass = " .prev a:first";
					}
					else if (e.target.id == "rotate-product-carousel" || $(e.target).parents("#rotate-product-carousel").size()) { 
						xDown = e.touches[0].clientX;
						yDown = e.touches[0].clientY;
						slideHandler = "rotate-prod"; // uses default carousel action AND slider-specific action
						carousel_id = "#rotate-product-carousel";
						section = ".rotate-menu-item-active";
						nextClass = " .next-view";
						prevClass = " .prev-view";
					}
					else {
						xDown = null;
						yDown = null;
						carousel_id = null
						slideHandler = null;
						section = null;
						nextClass = null;
						prevClass = null;
					}
				};                                                

				function handleTouchMove(e) {
					if (!xDown || !yDown) {
						return;
					}

					var xUp = e.touches[0].clientX;
					var yUp = e.touches[0].clientY;

					var xDiff = xDown - xUp;
					var yDiff = yDown - yUp;

					// check for most significant touch movement direction
					if (Math.abs(xDiff) > Math.abs(yDiff)) {
						if ( xDiff > 0 && xDiff > 10) {
							// left swipe
							//$(section+nextClass).click();
							if (slideHandler == "default" || slideHandler == "rotate-prod") {
							    $(carousel_id).carousel("next");
							}
							if (slideHandler == "bottom-div" || slideHandler == "rotate-prod") {
							   $(section+nextClass).click(); 
							}
							e.stopPropagation();
						}
						else if (xDiff < 0 && xDiff < -10) {
							// right swipe
							//$(section+prevClass).click();
							if (slideHandler == "default" || slideHandler == "rotate-prod") {
							    $(carousel_id).carousel("prev");
							}
							if (slideHandler == "bottom-div" || slideHandler == "rotate-prod") {
							   $(section+prevClass).click();
							}
							e.stopPropagation();
						}
					} else {
						if ( yDiff > 0 && yDiff > 2) {
							// up swipe
						}
						else if (yDiff < 0 && yDiff < -2) {
							// down swipe
						}
					}
					/* reset values */
					xDown = null;
					yDown = null;
					carousel_id = null;
					slideHandler = null;
					section = null;
					nextClass = null;
					prevClass = null;
				};
				
            }); // end document.ready
        </script>
        <header class="product-header">
            <div class="col-sm-5"><a class="main-logo" href="/">Coca-Cola</a></div>
            <div class="col-sm-7 header-tagline">Explore <strong><?php echo $item->ItemName; ?></strong></div>
        </header>
        <div class="product-left-nav left-nav col-sm-3">
            <ul>
<?php if ($info_image_count > 0) { ?>
                <li data-id="info" class="info-menu-item"><a href="" class="activate-lightbox"><img class="prod-icon" src="assets/img/info.png" alt="Info"></a></li>
<?php } if (count($rotate_sides) > 1) { ?>
                <li data-id="rotate" class="rotate-menu-item-active" style="display:none">
                    <a data-id="rotate" href="" class="activate-menu-toggle"><img class="rotate-icon" src="assets/img/rotate-active.png" alt="Rotate"></a>
                    <div class="menu-activated-toggles">
                        <h6 id="rotate-title">Front</h6>
                        <div class="prev-view"><a href="#rotate-product-carousel" role="button" data-slide="prev"><img src="assets/img/arrow-toggle.png" alt="arrow"></a></div>
                        <div class="next-view"><a href="#rotate-product-carousel" role="button" data-slide="next"><img src="assets/img/arrow-toggle.png" alt="arrow"></a></div>
                    </div>
                </li>
                <li data-id="rotate" class="rotate-menu-item" style="display: block;">
                    <a data-id="rotate" href="" class="activate-menu-toggle"><img class="rotate-icon active-rotate" src="assets/img/rotate.png" alt="Rotate"></a>
                </li>
<?php } if ($openimage != "") { ?>
                <li data-id="opendoor" class="open-menu-item">
                    <a href=""><img class="open-door" src="assets/img/opendoor.png" alt="open-door"></a>
                </li>
<?php } if ($video_count > 0) { ?>
                <li data-id="video" class="video-menu-item"><a href="" class="activate-lightbox"><img class="prod-icon" src="assets/img/video.png" alt="Video"></a></li>
<?php } if ($gallery_image_count > 0) { ?>
                <li data-id="gallery" class="gallery-menu-item"><a href="" class="activate-lightbox"><img class="prod-icon" src="assets/img/gallery.png" alt="Gallery"></a></li>
<?php } if ($show_count > 0) { ?>
                <li data-id="show" class="show-menu-item"><a href="" class="activate-lightbox"><img class="prod-icon" src="assets/img/show.png" alt="Show"></a></li>
<?php } ?>
            </ul>
        </div>
        <div class="product-content col-sm-9">
            <div class="item open-not-active">
                <img src="<?php echo $openimage; ?>" alt="Product Front">
                <div class="carousel-caption"></div>
            </div>
            <div id="rotate-product-carousel" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->
                <ol class="carousel-indicators rotate-carousel-indicators">
                    <li data-target="#rotate-product-carousel" data-slide-to="0" class="active"></li>
                    <li data-target="#rotate-product-carousel" data-slide-to="1"></li>
                </ol>

                <!-- Wrapper for slides -->
                <div class="carousel-inner" role="listbox">
<?php
                if ($frontimage != '') {
?>
                    <div class="item active">
                        <img src="<?php echo $frontimage; ?>" alt="Product Front">
                        <div class="carousel-caption"></div>
<?php
				$button_width = 64;
				$full_div_width = 615;
				$bubble_LR_offset = 16;
				$bubble_top_offset = 8;
				$oItemImageHighlight = $objItemImageHighlight->getAllItemImageHighlightByItemImageId($frontimage_id);
				foreach ($oItemImageHighlight as $highlight) {
					echo '                        <div class="highlight" style="top:' . $highlight->HotspotTop . 'px; ';
					echo 'left:' . $highlight->HotspotLeft . 'px;"><img src="assets/img/plus_white.png"></div>' . "\n";
					$htext_position = 'left:' . ($highlight->HotspotLeft + ($button_width + $bubble_LR_offset)) . 'px;';
					$htext_class = "highlight-text-right";
					if ($highlight->HotspotLeft > ($full_div_width / 2)) {
						$htext_position = 'right:' . ($full_div_width - $highlight->HotspotLeft + $bubble_LR_offset) . 'px;';
						$htext_class = "highlight-text-left";
					}
					echo '						  <div class="highlight-text ' . $htext_class . '" style="top:' . ($highlight->HotspotTop - $bubble_top_offset) . 'px; ';
					echo $htext_position . '"><span class="red">Highlight</span><br />' . $highlight->ItemImageHighlightInfo . '</div>' . "\n";
					// note: 64 is width of highlight button
				}
?>
                    </div>
<?php
                }
                if ($rightimage != '') {
?>
                    <div class="item">
                        <img src="<?php echo $rightimage; ?>" alt="Product Right">
                        <div class="carousel-caption"></div>
                    </div>
<?php
                }
                if ($backimage != '') {
?>
                    <div class="item">
                        <img src="<?php echo $backimage; ?>" alt="Product Back">
                        <div class="carousel-caption"></div>
                    </div>
<?php
                }
                if ($leftimage != '') {
?>
                    <div class="item">
                        <img src="<?php echo $leftimage; ?>" alt="Product Left">
                        <div class="carousel-caption"></div>
                    </div>
<?php
                }
?>
                </div>
            </div>
        </div>
<?php
}
?>