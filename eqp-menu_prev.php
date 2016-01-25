<?php
session_start();
if (!isset($_SESSION['sessCategory'])) {
    header('Location: index.php');
    exit;
}

include('inc/classes/Item.php');
include('inc/classes/ItemImage.php');
include('inc/classes/Category.php');
include('inc/classes/Technology.php');
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Coke Cooler</title>
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/styles.css">
	<script type="text/javascript" src="assets/js/jquery.js"></script>
	<script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="assets/js/scripts.js"></script>
</head>
<body>
	<div class="background-gradient"></div>
	<div class="menu-nav-container top-nav">
	    <?php 
	    $objItem = new Item();
	    $oItem = $objItem->GetAllItemByCategoryId($_REQUEST['id']);
	    
	    $nGetNum = 3;
	    $fGetSome = true;
	    $iCnt = 0;
	    $iIndex = 0;
	    
	    while ($fGetSome) {
	        $iCnt++;
	        
	        $objItemImage = new ItemImage();
	        $oItemImage = $objItemImage->getAllItemImageByItemIdItemImageSide($oItem[$iIndex]->Id, 'Front');
	        
    		echo '<div class="menu-item menu-nav-item col-sm-4">' . PHP_EOL;
    		echo '	<div class="menu-nav-item-bg"></div>' . PHP_EOL;
    		echo '	<a href="single-product.php?id=' . $oItem[$iIndex]->Id . '"><img src="' . $objItemImage->getPath() . $oItemImage[0]->ItemImageUrl . '" alt="' . $oItem[$iIndex]->ItemName . '"></a>' . PHP_EOL;
    		echo '</div>' . PHP_EOL;
    		
    		$iIndex++;
	        if ($iCnt == $nGetNum) {
	            $fGetSome = false;
	        }
	    }
	    ?>
	    <!--
		<div class="menu-item menu-nav-item col-sm-4">
			<div class="menu-nav-item-bg"></div>
			<a href="single-product.html"><img src="assets/img/eqp-1.png" alt="Equipment"></a>
		</div>
		<div class="menu-item menu-nav-item col-sm-4">
			<div class="menu-nav-item-bg"></div>
			<a href="single-product.html"><img src="assets/img/eqp-1.png" alt="Equipment"></a>
		</div>
		<div class="menu-item menu-nav-item col-sm-4">
			<div class="menu-nav-item-bg"></div>
			<a href="single-product.html"><img src="assets/img/eqp-1.png" alt="Equipment"></a>
		</div>
		-->
	</div>
	<div class="content product-menu-content">
		<h1 class="align-center">Explore</h1>
		<h3 class="align-center">Global Equipment Innovation</h3>
		<div class="menu-option">
			<div class="option col-sm-6">
				<a data-id="eqp-icon" href="" class="icon-mousedown">
					<img src="assets/img/eqp-icon.png" alt=""><br>
					<span>All Equipment</span>
				</a>
			</div>
			<div class="option col-sm-6">
				<a data-id="tech-icon" href="technology.html" class="icon-mousedown">
					<img src="assets/img/tech-icon.png" alt=""><br>
					<span>Technology</span>
				</a>
			</div>
		</div>
		<img class="company-logo" src="assets/img/coke-logo.png" alt="">
	</div>
	<div class="menu-nav-container bottom-nav">
	    <?php
	    $nGetNum = 3;
	    $fGetSome = true;
	    $iCnt = 0;
	    $iIndex = 4;
	    
	    while ($fGetSome) {
	        $iCnt++;

	        $objItemImage = new ItemImage();
	        $oItemImage = $objItemImage->getAllItemImageByItemIdItemImageSide($oItem[$iIndex]->Id, 'Front');
	        
    		echo '<div class="menu-item menu-nav-item col-sm-4">' . PHP_EOL;
    		echo '	<div class="menu-nav-item-bg"></div>' . PHP_EOL;
    		echo '	<a href="single-product.php?id=' . $oItem[$iIndex]->Id . '"><img src="' . $objItemImage->getPath() . $oItemImage[0]->ItemImageUrl . '" alt="' . $oItem[$iIndex]->ItemName . '"></a>' . PHP_EOL;
    		echo '</div>' . PHP_EOL;
    		
    		$iIndex++;
	        if ($iCnt == $nGetNum) {
	            $fGetSome = false;
	        }
	    }
	    ?>
	    <!--
		<div class="menu-item menu-nav-item col-sm-4">
			<div class="menu-nav-item-bg"></div>
			<a href="single-product.html"><img src="assets/img/eqp-1.png" alt="Equipment"></a>
		</div>
		<div class="menu-item menu-nav-item col-sm-4">
			<div class="menu-nav-item-bg"></div>
			<a href="single-product.html"><img src="assets/img/eqp-1.png" alt="Equipment"></a>
		</div>
		<div class="menu-item menu-nav-item col-sm-4">
			<div class="menu-nav-item-bg"></div>
			<a href="single-product.html"><img src="assets/img/eqp-1.png" alt="Equipment"></a>
		</div>
		-->
	</div>
	<div class="right-nav">
		<ul>
			<li><a class="back-btn" href="">Back</a></li>
			<li><a class="menu-toggle" href="">Menu</a></li>
		</ul>
	</div>
	<div class="menu-slideout-container">
		<a class="menu-close" href="">close</a>
		<div class="menu-slide-out">
			<div class="row row1">
				<div class="slideout-header">
					<h1 class="col-sm-9">Explore global innovation!</h1>
					<img src="assets/img/coke-logo.png" alt="Coke" class="col-sm-3">
				</div>
			    <?php 
			    $objCategory = new Category();
			    $oCategory = $objCategory->getAllCategoryByIsActive('1', 'sort_order ASC');
			    foreach ($oCategory as $category) {
			        if ($category->IsActive == '1') {
        				echo '<div class="menu-item col-sm-6">' . PHP_EOL;
        				echo '	<div class="menu-item-bg">	' . PHP_EOL;
        				echo '		<a href="' . $category->LinkUrl . '&id=' . $category->Id . '"><img src="' . $objCategory->GetPath() . $category->CategoryImageUrl . '" alt="' . $category->CategoryName . '"></a>' . PHP_EOL;
        				echo '	</div>' . PHP_EOL;
        				echo '	<h2><a href="' . $category->LinkUrl . '">' . $category->CategoryName . '</a></h2>' . PHP_EOL;
        				echo '	<a href="' . $category->LinkUrl . '">' . $category->CategoryDescription . '</a>' . PHP_EOL;
        				echo '</div>' . PHP_EOL;
			        }
			    }
			    ?>
			    <!--
				<div class="menu-item col-sm-4">
					<div class="menu-item-bg">	
						<a href="eqp-menu.html"><img src="assets/img/eqp-1.png" alt="Equipment"></a>
					</div>
					<h2><a href="eqp-menu.html">Equipment</a></h2>
					<a href="eqp-menu.html">Experience the way we serve Coca-Cola to the world</a>
				</div>
				<div class="menu-item col-sm-4">
					<div class="menu-item-bg">	
						<a href="eqp-menu.html"><img src="assets/img/pkg-surge.png" alt="packaging"></a>
					</div>
					<h2><a href="eqp-menu.html">Packaging</a></h2>
					<a href="eqp-menu.html">View innovative packaging solutions consumers love</a>
				</div>
				<div class="menu-item col-sm-4">
					<div class="menu-item-bg">	
						<a href="eqp-menu.html"><img style="visibility:hidden" src="assets/img/pkg-surge.png" alt="packaging"></a>
					</div>
					<h2><a href="eqp-menu.html">ETA</a></h2>
					<a href="eqp-menu.html">View innovative packaging solutions consumers love</a>
				</div>
				-->
			</div>
			<div class="row row2 white-border-top">
				<h2>Equipment</h2>
				<?php 
				foreach ($oItem as $item) {
        	        $objItemImage = new ItemImage();
        	        $oItemImage = $objItemImage->getAllItemImageByItemIdItemImageSide($item->Id, 'Front');
	        
    				echo '<div class="menu-item menu-nav-item col-sm-3">' . PHP_EOL;
    				echo '	<a href="single-product.php?id=' . $item->Id . '"><img src="' . $objItemImage->getPath() . $oItemImage[0]->ItemImageUrl . '" alt="' . $item->ItemName . '"></a>' . PHP_EOL;
    				echo '	<a href="single-product.php?id=' . $item->Id . '">' . $item->ItemName . '</a>' . PHP_EOL;
    				echo '</div>' . PHP_EOL;
				}
				?>
				<!--
				<div class="menu-item menu-nav-item col-sm-3">
					<a href="eqp-menu.html"><img src="assets/img/eqp-1.png" alt="Equipment"></a>
					<a href="eqp-menu.html">Title</a>
				</div>
				<div class="menu-item menu-nav-item col-sm-3">
					<a href="eqp-menu.html"><img src="assets/img/eqp-1.png" alt="Equipment"></a>
					<a href="eqp-menu.html">Title</a>
				</div>
				<div class="menu-item menu-nav-item col-sm-3">
					<a href="eqp-menu.html"><img src="assets/img/eqp-1.png" alt="Equipment"></a>
					<a href="eqp-menu.html">Title</a>
				</div>
				<div class="menu-item menu-nav-item col-sm-3">
					<a href="eqp-menu.html"><img src="assets/img/eqp-1.png" alt="Equipment"></a>
					<a href="eqp-menu.html">Title</a>
				</div>
				<div class="menu-item menu-nav-item col-sm-3">
					<a href="eqp-menu.html"><img src="assets/img/eqp-1.png" alt="Equipment"></a>
					<a href="eqp-menu.html">Title</a>
				</div>
				<div class="menu-item menu-nav-item col-sm-3">
					<a href="eqp-menu.html"><img src="assets/img/eqp-1.png" alt="Equipment"></a>
					<a href="eqp-menu.html">Title</a>
				</div>
				-->
			</div>
			<div class="row row3">
				<div class="section white-border-top">
					<h2>Equipment Technology</h2>
					<?php 
					$objTechnology = new Technology();
					$oTechnology = $objTechnology->GetAllTechnology('sort_order');
					foreach ($oTechnology as $technology) {
    					echo '<div class="menu-item col-sm-4">' . PHP_EOL;
    					echo '	<a data-id="/' . $objTechnology->getPath() . $technology->TechnologyButtonActiveImageUrl . '" href="' . $technology->LinkUrl . '" class="icon-mousedown"><img src="' . $objTechnology->getPath() . $technology->TechnologyButtonImageUrl . '" alt="' . $technology->TechnologyName . '"></a>' . PHP_EOL;
    					echo '	<a href="' . $technology->LinkUrl . '">' . $technology->TechnologyName . '</a>' . PHP_EOL;
    					echo '</div>' . PHP_EOL;
					}
					?>
					<!--
					<div class="menu-item col-sm-4">
						<a data-id="tech-sound" href="tech-sound.html" class="icon-mousedown"><img src="assets/img/tech-sound.png" alt="Audio"></a>
						<a href="tech-sound.html">Audio Reduction</a>
					</div>
					<div class="menu-item col-sm-4">
						<a data-id="tech-ibeacon" href="tech-ibeacon.html" class="icon-mousedown"><img src="assets/img/tech-ibeacon.png" alt="Ibeacon"></a>
						<a href="tech-ibeacon.html">iBeacon</a>
					</div>
					<div class="menu-item col-sm-4">
						<a data-id="tech-light" href="tech-light.html" class="icon-mousedown"><img src="assets/img/tech-light.png" alt="Light"></a>
						<a href="tech-light.html">Light</a>
					</div>
					-->
				</div>
				<div class="section align-center white-border-top">
					<h2>Intellectual Property</h2>
					<div class="menu-item">
						<a href="technology.html" data-id="ip" class="icon-mousedown"><img src="assets/img/ip.png" alt="Intellectual Property"></a>
						<a href="technology.html">See All</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="transparent-bg"></div>
</body>
</html>