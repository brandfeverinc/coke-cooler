<?php
session_start();
if (!isset($_SESSION['sessCategory'])) {
    header('Location: index.php');
    exit;
}

include_once('inc/classes/Technology.php');
include_once('inc/classes/TechnologyInfo.php');
include_once('inc/classes/TechnologyInfoImage.php');
include_once('inc/classes/Category.php');

$objTechnology = new Technology($_REQUEST['id']);

$objTechnologyInfo = new TechnologyInfo();
$oTechnologyInfo = $objTechnologyInfo->getAllTechnologyInfoByTechnologyId($_REQUEST['id']);

$objTechnologyInfoImage = new TechnologyInfoImage();
$oTechnologyInfoImage = $objTechnologyInfoImage->GetAllTechnologyInfoImageByTechnologyInfoId($oTechnologyInfo[0]->Id);

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
<body>
	<div class="background-gradient" style="background:<?php echo $background; ?>;"></div>
	<div class="wrap technology-single">
		<header class="technology">
			<div class="col-sm-6"><a class="main-logo" href="index.php">Coca-Cola</a></div>
			<div class="col-sm-6 header-tagline"><?php echo $_SESSION['sessCategoryName']; ?> Technology</div>
		</header>
		<div class="technology-menu left-nav col-sm-3">
			<ul>
				<li>
					<img src="<?php echo $objTechnology->GetPath() . $objTechnology->TechnologyButtonImageUrl; ?>" alt="<?php echo $objTechnology->TechnologyName; ?>">
					<h5><?php echo $objTechnology->TechnologyName; ?></h5>
				</li>
			</ul>
			<ul>
			    <?php
			    foreach ($oTechnologyInfo as $technologyinfo) {
				    echo '<li class="active-item">' . PHP_EOL;
					echo '<a class="overview" href="tech-standard.php?id=' . $_REQUEST['id'] . '&sub_id=' . $technologyinfo->Id . '" class="activate-lightbox">' . $technologyinfo->TechnologyInfoName . '</a>' . PHP_EOL;
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
				    foreach ($oTechnologyInfoImage as $techinfoimage) {
				        echo '<div class="item' . $cClass . ' slider-container">' . PHP_EOL;
						echo '    <img class="featured-img" src="/' . $objTechnologyInfoImage->GetPath() . $techinfoimage->TechnologyInfoImageUrl . '" />' . PHP_EOL;
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
				<p class="tech-description"><?php echo $oTechnologyInfo[0]->TechnologyInfoDescription; ?></p>
			</div>
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