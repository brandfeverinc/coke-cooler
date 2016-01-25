<?php
session_start();
if (!isset($_SESSION['sessCategory'])) {
    header('Location: index.php');
    exit;
}

include_once('inc/classes/Technology.php');
include_once('inc/classes/Patent.php');
include_once('inc/classes/Category.php');

$cat_id = 1; // used here and in flyout-menu.php which is included below
if (isset($_REQUEST['cat'])) {
	$cat_id = $_REQUEST['cat']; // from menu, we can access a different category!
}
else if (isset($_SESSION['sessCategory'])) {
	$cat_id = $_SESSION['sessCategory'];
}
$objCategory = new Category($cat_id);
$_SESSION['sessCategory'] = $objCategory->Id;
$_SESSION['sessCategoryName'] = $objCategory->CategoryName;

// below settings emulate js code in scripts.js:
// (for page load in case we want to go directly to intellectual property tab)
$ip_tab = false;
$tab1_style = "display:block;";
$tab2_style = "display:none;";
$body_style = "background:#FF1D25;";
$background_gradient  = "display: block;";
if (isset($_REQUEST['ip_tab'])) {
	$ip_tab = true;
	$tab1_style = "display:none;";
	$tab2_style = "display:block;";
	$body_style = "background:#000;";
	$background_gradient = "display: none;";
}

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
</head>
<style>
	.background-gradient {
		<?php echo $background_gradient; ?>
	}
</style>
<body style="<?php echo $body_style; ?>">
	<div class="background-gradient" style="background:<?php echo $background; ?>;"></div>
	<div class="wrap tech-wrap">
		<header class="technology">
			<div class="col-sm-6"><a class="main-logo" href="index.php">Coca-Cola</a></div>
			<div class="col-sm-6 header-tagline"><?php echo $_SESSION['sessCategoryName']; ?> Technology</div>
		</header>
		<div class="technology-toggle">
			<div class="col-sm-6">
				<a class="<?php if (!$ip_tab) { echo 'active '; } ?>tab1" href="javascript:void(0);">Explore <br>Technology</a>
			</div>
			<div class="col-sm-6">
				<a href="javascript:void(0);" class="<?php if ($ip_tab) { echo 'active'; } ?>">Intellectual <br>Property</a>
			</div>
		</div>
		<div class="technology-menu-items tab-1" style="<?php echo $tab1_style; ?>">
			<div id="carousel-equipment" class="equipment-menu-items carousel slide" data-ride="carousel">
			 	<div class="carousel-inner" role="listbox">
					<?php 
					$objTechnology = new Technology();
					$oTechnology = $objTechnology->GetAllTechnologyByCategoryId($cat_id);
    				$iCnt = 6;  // Go ahead and set it to the max to kick of a new div
    				$iTotalInView = 6;
    				$cClass = ' active';
					foreach ($oTechnology as $technology) {
    				    $iCnt++;
    				    if ($iCnt > $iTotalInView) {
    				        if ($cClass == '') {
    				            echo '</div>' . PHP_EOL;
    				        }
    				        echo '<div class="item' . $cClass . '">' . PHP_EOL;
    				        $iCnt = 1;
    				    }
						echo '<div class="technology-menu-item col-sm-4">' . PHP_EOL;
						echo '	<a data-id="/' . $objTechnology->getPath() . $technology->TechnologyButtonActiveImageUrl . '" class="icon-mousedown" href="' . $technology->LinkUrl . '?id=' . $technology->Id . '"><img src="/' . $objTechnology->getPath() . $technology->TechnologyButtonImageUrl . '" alt="' . $technology->TechnologyName . '"></a>' . PHP_EOL;
						echo '	<a href="' . $technology->LinkUrl . '?id=' . $technology->Id . '">' . $technology->TechnologyName . '</a>' . PHP_EOL;
						echo '</div>' . PHP_EOL;

        				$cClass = ''; // Turn off active after first item
					}
    				echo '</div>' . PHP_EOL;
					?>
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
		<div class="tab-2" style="<?php echo $tab2_style; ?>">
			<div id="carousel-ip" class="equipment-menu-items carousel slide" data-ride="carousel">
			 	<div class="carousel-inner" role="listbox">
			 	    <?php 
			 	    $objPatent = new Patent();
			 	    $oPatent = $objPatent->GetAllPatentByCategoryId($cat_id);
			 	    $cClass = ' active';
			 	    foreach ($oPatent as $patent) {
			    	    echo '<div class="item' . $cClass . '">' . PHP_EOL;
					    echo '	<img src="/' . $objPatent->GetPath() . $patent->PatentImageUrl . '" alt="">' . PHP_EOL;
            			echo '<div style="text-align: left;">' . PHP_EOL;
                		echo '	<h6 class="tech-label">Title:</h6>' . PHP_EOL;
                		echo '	<h2 class="tech-name">' . $patent->PatentName . '</h2>' . PHP_EOL;
                		echo '	<h6 class="tech-label">Abstract:</h6>' . PHP_EOL;
                		echo '	<p class="tech-description">' . $patent->PatentAbstract . '</p>' . PHP_EOL;
                		echo '	<h6 class="tech-label">Probable Assignee:</h6>' . PHP_EOL;
                		echo '	<p class="tech-description">' . $patent->PatentProbableAssignee . '</p>' . PHP_EOL;
                		echo '	<h6 class="tech-label">Assignee(s):(std):</h6>' . PHP_EOL;
                		echo '	<p class="tech-description">' . $patent->PatentAssigneesStd . '</p>' . PHP_EOL;
                		echo '	<h6 class="tech-label">Assignee(s):</h6>' . PHP_EOL;
                		echo '	<p class="tech-description">' . $patent->PatentAssignees . '</p>' . PHP_EOL;
                		echo '</div>' . PHP_EOL;
					    echo '</div>' . PHP_EOL;
					    $cClass = '';
			 	    }
			 	    ?>
				</div>
				<div class="slider-controls">
				    <a class="slider-prev" href="#carousel-ip" role="button" data-slide="prev">
				     	<img src="assets/img/red-arrow.png" alt="arrow"></a>
				    </a>
				    <h5>See More</h5>
				    <a class="slider-next" href="#carousel-ip" role="button" data-slide="next">
				    	 <img src="assets/img/red-arrow.png" alt="arrow">
				    </a>
			    </div>
			</div>
			<!--
			<div class="patent-description" id="patent-description-1">
    			<h6 class="tech-label">Title:</h6>
    			<h2 class="tech-name">Beverage Dispenser</h2>
    			<h6 class="tech-label">Abstract:</h6>
    			<p class="tech-description">Source: USD731841S [Source: Claim 1] The ornamental design for a beverage dispenser, as shown and described. </p>
    			<h6 class="tech-label">Probable Assignee:</h6>
    			<p class="tech-description">COCA COLA CO </p>
    			<h6 class="tech-label">Assignee(s):(std):</h6>
    			<p class="tech-description">COCA COLA CO </p>
    			<h6 class="tech-label">Assignee(s):</h6>
    			<p class="tech-description">FORPEOPLE LTD </p>
    		</div>
    		-->
		</div>
	</div>
	<?php 
	// Must set $cat_id prior to flyout-menu (set above)
	include('flyout-menu.php');
	?>
	<div class="transparent-bg"></div>
</body>
</html>