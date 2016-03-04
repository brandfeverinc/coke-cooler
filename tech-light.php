<?php
session_start();
if (!isset($_SESSION['sessCategory'])) {
    header('Location: index.php');
    exit;
}

include_once('inc/classes/Light.php');
include_once('inc/classes/LightImage.php');
include_once('inc/classes/LightTest.php');
include_once('inc/classes/Technology.php');
include_once('inc/classes/Category.php');

$objLight = new Light();
$oLight = $objLight->getAllLightByCategoryId($_SESSION['sessCategory']);

$objTechnology = new Technology(3);

$objLightImage = new LightImage();
$oLightImage = $objLightImage->getAllLightImageByLightId($oLight[0]->Id);

$objLightTest = new LightTest();
$oLightTest = $objLightTest->getAllLightTestByLightId($oLight[0]->Id);

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
	<script src="assets/js/jquery-ui.js"></script>
	<script type="text/javascript" src="assets/js/data.js"></script>
	<script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="assets/js/scripts.js"></script>
	<script  src="assets/js/touch.js"></script>
	<script type="text/javascript">
		$( document ).ready(function() {
			//$( "#draggable" ).draggable({ axis: "x", scroll: false, containment:[-150, 0, 250, 0],handle: ".handle"});
			$( ".ui-draggable" ).draggable({ axis: "x", scroll: false, containment:[70, 0, 520, 0],handle: ".handle"});

            // swipe left/right on slideshows: touch handling
            document.addEventListener('touchstart', handleTouchStart, false);
            document.addEventListener('touchmove', handleTouchMove, false);

            var xDown = null;
            var yDown = null;
            var carousel_id = null;  // use to call carousel "next" or "prev" actions

            function handleTouchStart(e) {
                if (e.target.id == "tech-single" || $(e.target).parents("#tech-single").size()) { 
                    xDown = e.touches[0].clientX;
                    yDown = e.touches[0].clientY;
                    carousel_id = "#tech-single";
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
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
    <style>
        /*
.light-test-left {
  background-image: url("/files/lightimages/bottle.png");
}
.dragger {
  background-color: rgba(235, 247, 254, 0.7);
}
*/
    </style>
	<div class="background-gradient" style="background:<?php echo $background; ?>;"></div>
	<div class="wrap technology-single">
		<header class="technology">
			<div class="col-sm-6"><a class="main-logo" href="index.php">Coca-Cola</a></div>
			<div class="col-sm-6 header-tagline"><?php echo $_SESSION['sessCategoryName']; ?> Technology</div>
		</header>
		<div class="technology-menu left-nav col-sm-3">
			<ul>
				<li>
					<img src="assets/img/tech-light.png" alt="light">
					<h5>Light</h5>
				</li>
			</ul>
			<ul>
				<li class="active-item">
					<a class="overview" href="#" class="activate-lightbox">Overview</a>
				</li>
				<?php 
				$iCnt = 0;
				foreach ($oLightTest as $lighttest) {
				    $iCnt++;
				    echo '<li>' . PHP_EOL;
				    echo '	<a class="test-' . $iCnt . '" href="#" class="activate-lightbox">Test ' . $iCnt . '</a>' . PHP_EOL;
				    echo '</li>' . PHP_EOL;
				}
				?>
			</ul>
		</div>
		<div class="col-sm-9 technology-content">
			<h1><?php echo $objTechnology->TechnologyHeadline; ?></h1>
			<div class="overview active-item">
				<div id="tech-single" class="carousel slide" data-ride="carousel">
				  <!-- Wrapper for slides -->
				  <div class="carousel-inner" role="listbox">
				    <?php 
				    $cClass = ' active';
				    foreach ($oLightImage as $lightimage) {
				        echo '<div class="item' . $cClass . ' slider-container">' . PHP_EOL;
						echo '    <img class="featured-img" src="/' . $objLightImage->GetPath() . $lightimage->ImageFile . '" />' . PHP_EOL;
					    echo '</div>' . PHP_EOL;
					    $cClass = '';
					}
					?>
				  </div>
				</div>
				<div class="slider-controls">
				    <a class="slider-prev col-sm-5" href="#tech-single" role="button" data-slide="prev">
				     	<img src="assets/img/arrow-toggle.png" alt="arrow">
				    </a>
				    <a class="slider-next col-sm-5" href="#tech-single" role="button" data-slide="next">
				    	 <img src="assets/img/arrow-toggle.png" alt="arrow">
				    </a>
		   		 </div>
				<p class="tech-description"><?php echo $oLight[0]->OverviewText; ?></p>
			</div>
			<?php
			$iCnt = 0;
			foreach ($oLightTest as $lighttest) {
			    $iCnt++;
    			echo '<div class="test-' . $iCnt . '">' . PHP_EOL;
    			echo '	<div class="light-test-container">' . PHP_EOL;
    			echo '		<div class="light-test-left col-sm-12" style="background-image: url(' . $objLightTest->GetPath() . $lighttest->BackgroundImageFile . ');">' . PHP_EOL;
    			//echo '			<div id="draggable" class="dragger" style="background-color: rgba(' . $lighttest->RgbaValue . ');"><img class="handle" src="assets/img/handle.png" alt="handle"></div>' . PHP_EOL;
    			echo '          <div id="draggable' . $iCnt . '" class="ui-draggable" style="position: relative; left: 0px; top: 0px;">';
    			echo '              <div class="dragger-left" style="background-color: rgba(' . $lighttest->RgbaValue . ');"></div>';
    			echo '              <img class="handle" src="assets/img/handle.png" alt="handle">';
    			echo '              <div class="dragger-right" style="background-color: rgba(' . $lighttest->RgbaValueRight . ');"></div>' . PHP_EOL;
    			echo '		    </div>' . PHP_EOL;
    			echo '		</div>' . PHP_EOL;
    			echo '		<div class="befor-after-container">' . PHP_EOL;
    			echo '			<div class="col-sm-6">' . PHP_EOL;
    			echo '				<img src="/' . $objLightTest->GetPath() . $lighttest->ImageFileDark . '" alt="Dark">' . PHP_EOL;
    			echo '				<p>' . $lighttest->DarkText . '</p>' . PHP_EOL;
    			echo '			</div>	' . PHP_EOL;
    			echo '			<div class="col-sm-6">' . PHP_EOL;
    			echo '				<img src="' . $objLightTest->GetPath() . $lighttest->ImageFileLight . '" alt="Light">' . PHP_EOL;
    			echo '				<p>' . $lighttest->LightText . '</p>' . PHP_EOL;
    			echo '			</div>' . PHP_EOL;
    			echo '		</div>' . PHP_EOL;
    			echo '	</div>' . PHP_EOL;
    			echo '</div>' . PHP_EOL;
    		}
    		?>
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