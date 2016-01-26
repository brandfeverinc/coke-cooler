<?php
include_once('inc/classes/Item.php');
include_once('inc/classes/ItemImage.php');
include_once('inc/classes/Category.php');
include_once('inc/classes/Technology.php');
?>
	<!--
	/////////////////////////////////////////////////////
	//
	//  This area starts the flyout menu
	//
	/////////////////////////////////////////////////////
	-->
	<script type="text/javascript">
		$(document).ready(function() {
			var category_content = [];
			var technology_content = [];
			var category_names = [];
<?php
			$objItem = new Item();
			$objCategory = new Category();
			$oCategory = $objCategory->getAllCategoryByIsActive('1', 'sort_order ASC');
			$iCnt = 0;
			$cat_content = array();
			foreach ($oCategory as $cat) {
				$this_content = "";
				$oItem = $objItem->getAllItemByCategoryId($cat->Id);

				$iCnt = 8;  // Go ahead and set it to the max to kick off a new div
				$iTotalInView = 8;
				$cClass = ' active';
				foreach ($oItem as $item) {
					$iCnt++;
					if ($iCnt > $iTotalInView) {
						if ($cClass == '') {
							$this_content .= '</div>';
						}
						$this_content .= '<div class="item' . $cClass . '">';
						$iCnt = 1;
					}
					$objItemImage = new ItemImage();
					$oItemImage = $objItemImage->getAllItemImageByItemIdItemImageSide($item->Id, 'Front');
			
					$this_content .= '<div class="menu-item menu-nav-item col-sm-3">';
					$this_content .= '	<a href="single-product.php?id=' . $item->Id . '"><img src="' . $objItemImage->getPath() . $oItemImage[0]->ItemImageUrl . '" alt="' . $item->ItemName . '"></a>';
					$this_content .= '	<a href="single-product.php?id=' . $item->Id . '">' . $item->ItemName . '</a>';
					$this_content .= '</div>';
					
					$cClass = ''; // Turn off active after first item
				}
				echo "            category_names[" . $cat->Id . "] = '" . $cat->CategoryName . "';\n";
				$this_content .= "</div>";
				$cat_content[$cat->Id] = $this_content;
				echo "            category_content[" . $cat->Id . "] = '" . $this_content . "';\n";
			}
			
			$objTechnology = new Technology();
			$tech_content = array();
			foreach ($oCategory as $cat) {
				$this_content = "";
				$oTechnology = $objTechnology->GetAllTechnologyByCategoryId($cat->Id);
				
				$iCnt = 3;  // Go ahead and set it to the max to kick of a new div
				$iTotalInView = 3;
				$cClass = ' active';
				foreach ($oTechnology as $technology) {
					if ($technology->IsActive == '1') {
						$iCnt++;
						if ($iCnt > $iTotalInView) {
							if ($cClass == '') {
								$this_content .= '</div>';
							}
							$this_content .= '<div class="item' . $cClass . '">';
							$iCnt = 1;
						}
						$this_content .= '<div class="menu-item col-sm-4">';
						$this_content .= '	<a data-id="/' . $objTechnology->getPath() . $technology->TechnologyButtonActiveImageUrl . '" href="' . $technology->LinkUrl . '?id=' . $technology->Id . '" class="icon-mousedown"><img src="/' . $objTechnology->getPath() . $technology->TechnologyButtonImageUrl . '" alt="' . $technology->TechnologyName . '"></a>';
						$this_content .= '	<a href="' . $technology->LinkUrl . '?id=' . $technology->Id . '">' . $technology->TechnologyName . '</a>';
						$this_content .= '</div>';
				
						$cClass = ''; // Turn off active after first item
					}
				}
				$this_content .= '</div>';
				$tech_content[$cat->Id] = $this_content;
				echo "            technology_content[" . $cat->Id . "] = '" . $this_content . "';\n";
			}
?>
			var current_cat = <?php echo $_SESSION['sessCategory']; ?>;
			$(".topmenu").on("click touch", function(e) {
				var cat = $(this).attr("id").replace("topmenu_", "");
				if (cat == current_cat) {
					window.location.href = "/main-menu.php?id=" + cat;
					exit;
				}
				$("#carousel-slideout-products .carousel-inner").html(category_content[cat]);
				$("#carousel-slideout-tech .carousel-inner").html(technology_content[cat]);
				$("#tech_content_name").text(category_names[cat] + " Technology");
				$("#category_name_text").text(category_names[cat]);
				$("#intellectual_property_links a").attr("href", "technology.php?cat=" + cat + "&ip_tab");
				current_cat = cat;
			});
			
			$(".menu-item").on("click touch", function() {
				$(".menu-item").removeClass("active");
				$(this).addClass("active");
			});
		});
		// href="/main-menu.php?id=' . $category->Id . '"
	</script>
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
			    $iCnt = 0;
			    foreach ($oCategory as $category) {
			        if ($category->IsActive == '1') {
			            $iCnt++;
			            $cClass = '';
			            //if ($iCnt == 1) {
			            if ($category->Id == $_SESSION['sessCategory']) {
			                $cClass = ' active';
			            }
        				echo '<div class="menu-item col-sm-4' . $cClass . '">' . PHP_EOL;
        				echo '	<div class="menu-item-bg">	' . PHP_EOL;
        				echo '		<a href="javascript:void(0);" id="topmenu_' . $category->Id . '" class="topmenu"><img src="' . $objCategory->GetPath() . $category->CategoryImageUrl . '" alt="' . $category->CategoryName . '"></a>' . PHP_EOL;
        				echo '	</div>' . PHP_EOL;
        				echo '	<h2><a href="/main-menu.php?id=' . $category->Id . '">' . $category->CategoryName . '</a></h2>' . PHP_EOL;
        				echo '	<a href="/main-menu.php?id=' . $category->Id . '">' . $category->CategoryDescription . '</a>' . PHP_EOL;
        				echo '</div>' . PHP_EOL;
			        }
			    }
			    ?>
			</div>
			<!--
			/////////////////////////////////////////////////////
			//
			//  This area starts the Equipment section of flyout menu
			//
			/////////////////////////////////////////////////////
			-->
			<div class="row row2 white-border-top">
				<h2 class="col-sm-9" id="category_name_text"><?php echo $_SESSION['sessCategoryName']; ?></h2>
			    <div id="carousel-slideout-products" class="carousel slide" data-ride="carousel">
			    	<div class="slider-controls">
					    <a class="slider-prev col-sm-6" href="#carousel-slideout-products" role="button" data-slide="prev">
					     	<img src="assets/img/arrow-toggle.png" alt="arrow">
					    </a>
					    <a class="slider-next col-sm-6" href="#carousel-slideout-products" role="button" data-slide="next">
					    	 <img src="assets/img/arrow-toggle.png" alt="arrow">
					    </a>
				    </div>
					<div class="carousel-inner" role="listbox">
        				<?php
        					echo $cat_content[$_SESSION['sessCategory']];
        				?>
					</div>
				</div>
			</div>
			<!--
			/////////////////////////////////////////////////////
			//
			//  This ends the Equipment section of flyout menu
			//
			/////////////////////////////////////////////////////
			-->
			<!--
			/////////////////////////////////////////////////////
			//
			//  This area starts the Technology and IP section of flyout menu
			//
			/////////////////////////////////////////////////////
			-->
			<div class="row row3">
				<div class="section white-border-top">
					<h2 class="col-sm-9" id="tech_content_name"><?php echo $_SESSION['sessCategoryName']; ?> Technology</h2>
					<div id="carousel-slideout-tech" class="carousel slide" data-ride="carousel">
				    	<div class="slider-controls">
						    <a class="slider-prev col-sm-6" href="#carousel-slideout-tech" role="button" data-slide="prev">
						     	<img src="assets/img/arrow-toggle.png" alt="arrow">
						    </a>
						    <a class="slider-next col-sm-6" href="#carousel-slideout-tech" role="button" data-slide="next">
						    	 <img src="assets/img/arrow-toggle.png" alt="arrow">
						    </a>
				   		</div>
					  	<div class="carousel-inner" role="listbox">
							<?php
								echo $tech_content[$_SESSION['sessCategory']];
							?>
						</div>
					</div>
				</div>
				<div class="section align-center white-border-top">
					<h2>Intellectual Property</h2>
					<div class="menu-item" id="intellectual_property_links">
						<a href="technology.php?cat=<?php echo $cat_id; ?>&ip_tab" data-id="ip" class="icon-mousedown"><img src="assets/img/ip.png" alt="Intellectual Property"></a>
						<a href="technology.php?cat=<?php echo $cat_id; ?>&ip_tab">See All</a>
					</div>
				</div>
		    </div>
			<!--
			/////////////////////////////////////////////////////
			//
			//  This ends the Technology and IP section of flyout menu
			//
			/////////////////////////////////////////////////////
			-->
			
			<div style="position:absolute; bottom:20px; right:20px; width:60px; height:60px; overflow: auto;"><p style="text-align:center; margin:0; line-height:33px;"><a class="help" href="help.php"><img src="/assets/img/help_Icon.png" alt=""></a></p></div>
	   </div>
	</div>
	<!--
	/////////////////////////////////////////////////////
	//
	//  This ends the flyout menu
	//
	/////////////////////////////////////////////////////
	-->