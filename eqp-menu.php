<?php
include('inc/classes/Item.php');
include('inc/classes/ItemImage.php');
include('inc/classes/Category.php');
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
	<!--
	//
	// Top row displaying 3 products
	//
	-->
	<div class="menu-nav-container top-nav">
	    <?php 
	    $objItem = new Item();
	    $oItem = $objItem->getAllItemByCategoryId($_REQUEST['id']);
	    
	    $nGetNum = 3;
	    $fGetSome = true;
	    $iCnt = 0;
	    $iIndex = 0;
	    
	    while ($fGetSome) {
	        $iCnt++;
	        
	        $objItemImage = new ItemImage();
	        $oItemImage = $objItemImage->getAllItemImageByItemIdItemImageSide($oItem[$iIndex]->Id, 'Front');
	        
    		echo '<div class="menu-item menu-nav-item col-sm-4 css-mousedown">' . PHP_EOL;
    		echo '	<div class="menu-nav-item-bg"></div>' . PHP_EOL;
    		echo '	<a href="single-product.php?id=' . $oItem[$iIndex]->Id . '"><img src="' . $objItemImage->getPath() . $oItemImage[0]->ItemImageUrl . '" alt="' . $oItem[$iIndex]->ItemName . '"></a>' . PHP_EOL;
    		echo '</div>' . PHP_EOL;
    		
    		$iIndex++;
	        if ($iCnt == $nGetNum) {
	            $fGetSome = false;
	        }
	    }
	    ?>
	</div>
	<!--
	//
	// Middle row
	//
	-->
	<div class="content product-menu-content">
		<h1 class="align-center">Explore</h1>
		<h3 class="align-center">Global Equipment Innovation</h3>
		<div class="menu-option">
			<div class="option col-sm-6">
				<a data-id="eqp-icon" href="equipment.html" class="icon-mousedown">
					<img src="assets/img/eqp-icon.png" alt=""><br>
					<span>All Equipment</span>
				</a>
			</div>
			<div class="option col-sm-6">
				<a data-id="tech-icon" href="technology.php" class="icon-mousedown">
					<img src="assets/img/tech-icon.png" alt=""><br>
					<span>Technology</span>
				</a>
			</div>
		</div>
		<img class="company-logo" src="assets/img/coke-logo.png" alt="">
	</div>
	<!--
	//
	// Bottom row displaying 3 products
	//
	-->
	<div class="menu-nav-container bottom-nav">
	    <?php
	    $nGetNum = 3;
	    $fGetSome = true;
	    $iCnt = 0;
	    $iIndex = 3;  // 4th item but array is zero-based
	    
	    while ($fGetSome) {
	        $iCnt++;

	        $objItemImage = new ItemImage();
	        $oItemImage = $objItemImage->getAllItemImageByItemIdItemImageSide($oItem[$iIndex]->Id, 'Front');
	        
    		echo '<div class="menu-item menu-nav-item col-sm-4 css-mousedown">' . PHP_EOL;
    		echo '	<div class="menu-nav-item-bg"></div>' . PHP_EOL;
    		echo '	<a href="single-product.php?id=' . $oItem[$iIndex]->Id . '"><img src="' . $objItemImage->getPath() . $oItemImage[0]->ItemImageUrl . '" alt="' . $oItem[$iIndex]->ItemName . '"></a>' . PHP_EOL;
    		echo '</div>' . PHP_EOL;
    		
    		$iIndex++;
	        if ($iCnt == $nGetNum) {
	            $fGetSome = false;
	        }
	    }
	    ?>
	</div>
	<?php 
	// Must set cat_id prior to flyout-menu
	$cat_id = $_REQUEST['id'];
	include('flyout-menu.php');
	?>
	<div class="transparent-bg"></div>
</body>
</html>