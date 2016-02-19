<?php
session_start();
if (!isset($_SESSION['sessCategory'])) {
    header('Location: index.php');
    exit;
}

include_once('inc/classes/Sound.php');
include_once('inc/classes/SoundImage.php');
include_once('inc/classes/SoundTest.php');
include_once('inc/classes/Technology.php');
include_once('inc/classes/Category.php');

$objSound = new Sound();
$oSound = $objSound->getAllSoundByCategoryId($_SESSION['sessCategory']);

$objTechnology = new Technology(1);

$objSoundImage = new SoundImage();
$oSoundImage = $objSoundImage->getAllSoundImageBySoundId($oSound[0]->Id);

$objSoundTest = new SoundTest();
$oSoundTest = $objSoundTest->getAllSoundTest($oSound[0]->Id);

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
			var playing1 = false;
			var playing2 = false;
			var playing3 = false;
			$("#audio_test1").on("click touch", function() {
				$("#audio_testfile3").trigger('pause');
				$("#audio_testfile2").trigger('pause');
				if (playing1) {
					$("#audio_testfile1").trigger('pause');
					playing1 = false;
				}
				else {
					$("#audio_testfile1").trigger('play');
					playing1 = true;
				}
				playing3 = false;
				playing2 = false;
			});
			$("#audio_test2").on("click touch", function() {
				$("#audio_testfile1").trigger('pause');
				$("#audio_testfile3").trigger('pause');
				if (playing2) {
					$("#audio_testfile2").trigger('pause');
					playing2 = false;
				}
				else {
					$("#audio_testfile2").trigger('play');
					playing2 = true;
				}
				playing1 = false;
				playing3 = false;
			});
			$("#audio_test3").on("click touch", function() {
				$("#audio_testfile1").trigger('pause');
				$("#audio_testfile2").trigger('pause');
				if (playing3) {
					$("#audio_testfile3").trigger('pause');
					playing3 = false;
				}
				else {
					$("#audio_testfile3").trigger('play');
					playing3 = true;
				}
				playing1 = false;
				playing2 = false;
			});
			
			// toggles pod active state
			$('.pod').on('click touch',function(e){
				var hasClass = $(this).hasClass('active');
				$('.pods .active').removeClass('active');
				if(!hasClass) {
					// need to turn it on:
					$(this).addClass('active');
				}
			});

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
            
		}); // end document.ready
	</script>
</head>
	<meta charset="UTF-8">
	<title>Document</title>
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
					<img src="assets/img/tech-sound.png" alt="Sound">
					<h5>Sound</h5>
				</li>
			</ul>
			<ul>
				<li class="active-item">
					<a class="overview" href="" class="activate-lightbox">Overview</a>
				</li>
				<?php 
				$iCnt = 0;
				foreach ($oSoundTest as $soundtest) {
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
				    foreach ($oSoundImage as $soundimage) {
				        echo '<div class="item' . $cClass . ' slider-container">' . PHP_EOL;
						echo '    <img class="featured-img" src="/' . $objSoundImage->GetPath() . $soundimage->ImageFile . '" />' . PHP_EOL;
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
				<p class="tech-description"><?php echo $oSound[0]->OverviewText; ?></p>
			</div>
			<?php
			$iCnt = 0;
			foreach ($oSoundTest as $soundtest) {
			    $iCnt++;
    			echo '<div class="test-' . $iCnt . '">' . PHP_EOL;
				echo '    <p class="tech-description">' . $soundtest->SoundTestDescription . '</p>' . PHP_EOL;
				echo '    <div class="pods">' . PHP_EOL;
				echo '    	<div class="pod" id="audio_test1">' . PHP_EOL;
				echo '    		<img class="pod-x" src="assets/img/modalx.png" alt="x">' . PHP_EOL;
				echo '    		<img src="/' . $objSoundTest->GetPath() . $soundtest->ImageUrl1 . '" alt="65db">' . PHP_EOL;
				echo '          <audio id="audio_testfile1"><source src="/' . $objSoundTest->GetPath() . $soundtest->SoundUrl1 . '" type="audio/wav"></audio>' . PHP_EOL;
				echo '    		<h4>' . $soundtest->Text1 . '</h4>' . PHP_EOL;
				echo '    	</div>' . PHP_EOL;
				echo '    	<div class="pod" id="audio_test2">' . PHP_EOL;
				echo '    		<img class="pod-x" src="assets/img/modalx.png" alt="x">' . PHP_EOL;
				echo '    		<img src="/' . $objSoundTest->GetPath() . $soundtest->ImageUrl2 . '" alt="55db">' . PHP_EOL;
				echo '    		<h4>' . $soundtest->Text2 . '</h4>' . PHP_EOL;
				echo '          <audio id="audio_testfile2"><source src="/' . $objSoundTest->GetPath() . $soundtest->SoundUrl2 . '" type="audio/wav"></audio>' . PHP_EOL;
				echo '    	</div>' . PHP_EOL;
				echo '    	<div class="pod" id="audio_test3">' . PHP_EOL;
				echo '    		<img class="pod-x" src="assets/img/modalx.png" alt="x">' . PHP_EOL;
				echo '    		<img src="/' . $objSoundTest->GetPath() . $soundtest->ImageUrl3 . '" alt="35db">' . PHP_EOL;
				echo '    		<h4>' . $soundtest->Text3 . '</h4>' . PHP_EOL;
				echo '          <audio id="audio_testfile3"><source src="/' . $objSoundTest->GetPath() . $soundtest->SoundUrl3 . '" type="audio/wav"></audio>' . PHP_EOL;
				echo '    	</div>' . PHP_EOL;
				echo '    </div>' . PHP_EOL;
    			echo '</div>' . PHP_EOL;
    		}
    		?>
    		<!--
			<div class="test-1">
				<p class="tech-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec ut felis non elit vehicula posuere. Integer pretium libero sit amet felis tincidunt, sit amet dictum nibh vulputate.</p>
				<div class="pods">
					<div class="pod">
						<img class="pod-x" src="assets/img/modalx.png" alt="x">
						<img src="assets/img/pod-img.png" alt="65db">
						<h4>65db</h4>
					</div>
					<div class="pod">
						<img class="pod-x" src="assets/img/modalx.png" alt="x">
						<img src="assets/img/pod-img.png" alt="55db">
						<h4>55db</h4>
					</div>
					<div class="pod">
						<img class="pod-x" src="assets/img/modalx.png" alt="x">
						<img src="assets/img/pod-img.png" alt="35db">
						<h4>35db</h4>
					</div>
				</div>
			</div>
			-->
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