<?php
session_start();

include('inc/classes/Category.php');
include('inc/classes/Item.php');
include('inc/classes/ItemImage.php');

$objCategory = new Category($_SESSION['sessCategory']);
$bgcolor = "#F40000"; // Coke red default
if ($objCategory->BackgroundColor != "") {
	$bgcolor = $objCategory->BackgroundColor;
}
$background = $bgcolor . " none repeat scroll 0% 0%"; // this is used in single-product-template.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Coke Cooler</title>
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/styles.css">
	<script type="text/javascript" src="assets/js/jquery.js"></script>
	<script type="text/javascript" src="assets/js/data.js"></script>
	<script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="assets/js/scripts.js"></script>
	<script type="text/javascript">
        $(document).ready(function() {
            // swipe left/right on slideshows: touch handling
            document.ontouchmove = function(e){
                if (e.target.id == "carousel-equipment" || $(e.target).parents("#carousel-equipment").size()) { 
                    event.preventDefault();
                }
            }

            document.addEventListener('touchstart', handleTouchStart, false);
            document.addEventListener('touchmove', handleTouchMove, false);

            var xDown = null;
            var yDown = null;
            var carousel_id = null;  // use to call carousel "next" or "prev" actions

            function handleTouchStart(e) {
                if (e.target.id == "carousel-equipment" || $(e.target).parents("#carousel-equipment").size()) { 
                    xDown = e.touches[0].clientX;
                    yDown = e.touches[0].clientY;
                    carousel_id = "#carousel-equipment"
                }
                else {
                    xDown = null;
                    yDown = null;
                    carousel_id = null
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
                        $(carousel_id).carousel("next");
                        e.stopPropagation();
                    }
                    else if (xDiff < 0 && xDiff < -10) {
                        // right swipe
                        $(carousel_id).carousel("prev");
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
            };
            
        }); // end document ready
	</script>
</head>
<body>
	<div class="background-gradient" style="background:<?php echo $background; ?>;"></div>
	<div class="wrap equipment-wrap">
		<header class="equipment">
			<div class="col-sm-6"><a class="main-logo" href="/">Coca-Cola</a></div>
			<div class="col-sm-6 header-tagline"><?php echo $_SESSION['sessCategoryName']; ?> Innovation</div>
		</header>
		<div id="carousel-equipment" class="equipment-menu-items carousel slide" data-ride="carousel">
			 <div class="carousel-inner" role="listbox">
				<?php 
			    $objItem = new Item();
			    $oItem = $objItem->getAllItemByCategoryId($_SESSION['sessCategory']);

				$iCnt = 6;  // Go ahead and set it to the max to kick of a new div
				$iTotalInView = 6;
				$cClass = ' active';
				foreach ($oItem as $item) {
				    $iCnt++;
				    if ($iCnt > $iTotalInView) {
				        if ($cClass == '') {
				            echo '</div>' . PHP_EOL;
				        }
				        echo '<div class="item' . $cClass . '">' . PHP_EOL;
				        $iCnt = 1;
				    }
        	        $objItemImage = new ItemImage();
        	        $oItemImage = $objItemImage->getAllItemImageByItemIdItemImageSide($item->Id, 'Front');
	        
			    	echo '<div class="menu-item menu-nav-item col-sm-4 css-mousedown">' . PHP_EOL;
					echo '	<div class="menu-nav-item-bg"></div>' . PHP_EOL;
					echo '	<a href="single-product.php?id=' . $item->Id . '"><img src="' . $objItemImage->getPath() . $oItemImage[0]->ItemImageUrl . '" alt="' . $item->ItemName . '"></a>' . PHP_EOL;
					echo '	<a class="equipment-title" href="single-product.php?id=' . $item->Id . '">' . $item->ItemName . '</a>' . PHP_EOL;
					echo '</div>' . PHP_EOL;

    				$cClass = ''; // Turn off active after first item
				}
				echo '</div>' . PHP_EOL;
				?>
				<!--
			    <div class="item active">
			    	<div class="menu-item menu-nav-item col-sm-4 css-mousedown">
						<div class="menu-nav-item-bg"></div>
						<a href="single-product.html"><img src="assets/img/eqp-1.png" alt="Equipment"></a>
						<a class="equipment-title" href="single-product.html">Off Grid</a>
					</div>
					<div class="menu-item menu-nav-item col-sm-4 css-mousedown">
						<div class="menu-nav-item-bg"></div>
						<a href="single-product.html"><img src="assets/img/eqp-1.png" alt="Equipment"></a>
						<a class="equipment-title" href="single-product.html">Off Grid</a>
					</div>
					<div class="menu-item menu-nav-item col-sm-4 css-mousedown">
						<div class="menu-nav-item-bg"></div>
						<a href="single-product.html"><img src="assets/img/eqp-1.png" alt="Equipment"></a>
						<a class="equipment-title" href="single-product.html">Off Grid</a>
					</div>
					<div class="menu-item menu-nav-item col-sm-4 css-mousedown">
						<div class="menu-nav-item-bg"></div>
						<a href="single-product.html"><img src="assets/img/eqp-1.png" alt="Equipment"></a>
						<a class="equipment-title" href="single-product.html">Off Grid</a>
					</div>
					<div class="menu-item menu-nav-item col-sm-4 css-mousedown">
						<div class="menu-nav-item-bg"></div>
						<a href="single-product.html"><img src="assets/img/eqp-1.png" alt="Equipment"></a>
						<a class="equipment-title" href="single-product.html">Off Grid</a>
					</div>
					<div class="menu-item menu-nav-item col-sm-4 css-mousedown">
						<div class="menu-nav-item-bg"></div>
						<a href="single-product.html"><img src="assets/img/eqp-1.png" alt="Equipment"></a>
						<a class="equipment-title" href="single-product.html">Off Grid</a>
					</div>
			    </div>
			    <div class="item">
			    	<div class="menu-item menu-nav-item col-sm-4 css-mousedown">
						<div class="menu-nav-item-bg"></div>
						<a href="single-product.html"><img src="assets/img/eqp-1.png" alt="Equipment"></a>
						<a class="equipment-title" href="single-product.html">Off Grid</a>
					</div>
					<div class="menu-item menu-nav-item col-sm-4 css-mousedown">
						<div class="menu-nav-item-bg"></div>
						<a href="single-product.html"><img src="assets/img/eqp-1.png" alt="Equipment"></a>
						<a class="equipment-title" href="single-product.html">Off Grid</a>
					</div>
					<div class="menu-item menu-nav-item col-sm-4 css-mousedown">
						<div class="menu-nav-item-bg"></div>
						<a href="single-product.html"><img src="assets/img/eqp-1.png" alt="Equipment"></a>
						<a class="equipment-title" href="single-product.html">Off Grid</a>
					</div>
					<div class="menu-item menu-nav-item col-sm-4 css-mousedown">
						<div class="menu-nav-item-bg"></div>
						<a href="single-product.html"><img src="assets/img/eqp-1.png" alt="Equipment"></a>
						<a class="equipment-title" href="single-product.html">Off Grid</a>
					</div>
					<div class="menu-item menu-nav-item col-sm-4 css-mousedown">
						<div class="menu-nav-item-bg"></div>
						<a href="single-product.html"><img src="assets/img/eqp-1.png" alt="Equipment"></a>
						<a class="equipment-title" href="single-product.html">Off Grid</a>
					</div>
					<div class="menu-item menu-nav-item col-sm-4 css-mousedown">
						<div class="menu-nav-item-bg"></div>
						<a href="single-product.html"><img src="assets/img/eqp-1.png" alt="Equipment"></a>
						<a class="equipment-title" href="single-product.html">Off Grid</a>
					</div>
			  	</div>
			  	-->
			</div>
		</div>
		<!-- Controls -->
		<div class="slider-controls">
		    <a class="slider-prev col-sm-6" href="#carousel-equipment" role="button" data-slide="prev">
		     	<img src="assets/img/arrow-toggle.png" alt="arrow">
		    </a>
		    <a class="slider-next col-sm-6" href="#carousel-equipment" role="button" data-slide="next">
		    	 <img src="assets/img/arrow-toggle.png" alt="arrow">
		    </a>
	    </div>
	</div>
	<?php 
	// Must set cat_id prior to flyout-menu
	$cat_id = $_SESSION['sessCategory'];
	include('flyout-menu.php');
	?>
	<div class="transparent-bg"></div>
</body>
</html>