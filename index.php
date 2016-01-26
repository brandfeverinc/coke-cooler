<?php
	include_once('inc/classes/Category.php');
	include_once('inc/classes/HomepageImage.php');
	$objHomepageImage = new HomepageImage();
	$oHomepageImage = $objHomepageImage->getAllHomepageImageByIsActive();
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
	<script type="text/javascript">
		var rotation = setInterval(rotateImages, 8000);
		var current_img = 0;
		var prior_img = 9999;
		function rotateImages() {
			prior_img = current_img;
			current_img++;
			if (current_img >= <?php echo count($oHomepageImage) ?>) {
				current_img = 0;
			}
			$("#bg_" + prior_img).fadeOut(800);
			$("#bg_" + current_img).fadeIn(800);
		}
	</script>
</head>
<body>
	<div class="background">
	<?php
		$i = 0;
		foreach ($oHomepageImage as $image) {
			echo '		<img src="/' . $objHomepageImage->getPath() . $image->HomepageImageUrl . '"';
			if ($i == 0) {
				echo ' style="display:block;"';
			}
			echo ' id="bg_' . $i . '">' . PHP_EOL;
			$i++;
		}
	?>
	</div>
		<div class="overlay">
			<img src="assets/img/project-logo.png" alt="logo">
			<h1>Get inspired by global innovation!</h1>
			<div class="home-menu-container">
			    <?php 
			    $objCategory = new Category();
			    $oCategory = $objCategory->getAllCategoryByIsActive('1', 'sort_order ASC');
			    foreach ($oCategory as $category) {
			        if ($category->IsActive == '1') {
        				echo '<div class="menu-item col-xs-4">' . PHP_EOL;
        				echo '	<div class="menu-item-bg">' . PHP_EOL;
        				echo '		<a href="main-menu.php?id=' . $category->Id . '"><img src="/' . $objCategory->GetPath() . $category->CategoryImageUrl . '" alt="' . $category->CategoryName . '"></a>' . PHP_EOL;
        				echo '	</div>' . PHP_EOL;
        				echo '	<a href="main-menu.php?id=' . $category->Id . '">' . $category->CategoryName . '</a>' . PHP_EOL;
        				echo '	<a class="hm-item-description" href="main-menu.php?id=' . $category->Id . '">' . $category->CategoryDescription . '</a>' . PHP_EOL;
        				echo '</div>' . PHP_EOL;
        			}
			    }
			    ?>
			</div>
			<div style="position:absolute; bottom:20px; right:20px; width:60px; height:50px; overflow: auto;"><p style="text-align:center; margin:0; line-height:33px;"><a class="help" href="help.php"><img src="/assets/img/help_Icon.png" alt=""></a></p></div>
		</div>
</body>
</html>