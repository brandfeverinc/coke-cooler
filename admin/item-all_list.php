<?php
session_start();
header("Cache-control: private"); //IE 6 Fix

include_once("includes/config.php");
include_once("includes/checklogin.php");
include_once("../inc/classes/Item.php");
include_once("../inc/classes/ItemImage.php");
include_once("../inc/classes/ItemInfo.php");
include_once("../inc/classes/ItemInfoImage.php");
include_once("../inc/classes/ItemImageHighlight.php");
include_once("../inc/classes/ItemInfoType.php");
include_once("../inc/classes/ItemVideo.php");
include_once("../inc/classes/ItemGalleryImage.php");
include_once("../inc/classes/Category.php");
include_once("../inc/classes/Helpers.php");

include_once("includes/pagetemplate.php");

function PageContent() {
    $item = new Item($_REQUEST['item']);
    $category = new Category($item->CategoryId);
?>
    
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                // fix to preserve width of cells
                var fixHelper = function(e, ui) {
                    ui.children().each(function() {
                        $(this).width($(this).width());
                    });
                    return ui;
                };
                var saveIndex = function(e, ui) {
                    //alert("New position: " + ui.item.index());
                    //alert("Image Id: " + ui.item.attr("id"));
                    id = ui.item.attr("id").replace("img_", "");
                    $.ajax({
                        url: 'itemimage_list.php',
                        data: {
                            dragsort: 1,
                            idx: ui.item.index(),
                            id: id
                        },
                        type: 'POST',
                        dataType: 'html',
                        success: function (data) {
                            //alert("done");
                        },
                        error: function (xhr, status) {
                            alert('Sorry, there was a problem!');
                        }
                    });
                }; // end saveIndex
    
                $("#list_table tbody").sortable({
                    helper: fixHelper,
                    stop: saveIndex
                }).disableSelection();
    
            }); // end document.ready
        </script>
        <style>
            .icon-resize-vertical:hover {
                cursor:grab;
            }
        </style>        
    
            <div class="layout center-flex">

                <?php
                $aLabels = array();
                $aLinks = array();
                $aLabels[0] = 'Home';
                $aLinks[0] = 'mainpage.php';
                $aLabels[1] = $category->CategoryName;
                $aLinks[1] = 'item_list.php?cat=' . $category->Id;
                $aLabels[2] = $item->ItemName;
                $aLinks[2] = '';
                echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
                ?>

<!-- 
                <div class="bigbotspace flex-container space-between">
                    <p class="larger auto heading"><?php echo $item->ItemName; ?> Images</p>
                    <a href="itemimage_admin.php?item=<?php echo $_REQUEST['item']; ?>" class="button_link"><button class="">Add New Item Image</button></a>
                </div>
 -->
            </div>
    
            <div class="layout">
    
                <table class="tablestyle">
                    <thead>
                        <tr>
                            <th colspan="2">Item Settings&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="item_admin.php?id=<?php echo $item->Id; ?>"><img src="img/edit-icon.png" /></a></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Item Name:</td>
                            <td><?php echo $item->ItemName; ?></td>
                        </tr>
                        <tr>
                            <td>Background Color:</td>
                            <td><?php echo $item->BackgroundColor; ?></td>
                        </tr>
                        <tr>
                            <td>Contact Email:</td>
                            <td><?php echo $item->ContactEmail; ?></td>
                        </tr>
                    </tbody>
                </table>
                    
            </div> <!-- layout -->

			<div class="all-section-separator"></div>
			
            <div class="layout">
    
                <table class="tablestyle" id="list_table">
                    <thead>
                    	<tr>
                    		<th colspan="5">Item Images</th>
                    	</tr>
                    </thead>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Side</th>
                            <th>Image</th>
                            <th>Hotspots</th>
<!-- 
                            <th class="mid">Order</th>
 -->
                            <th class="mid">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $objItemImage = new ItemImage();
                        $objItemImageHighlight = new ItemImageHighlight();
                        $oItemImage = $objItemImage->getAllItemImageByItemId($_REQUEST['item']);
                        foreach ($oItemImage as $itemimage) {
                            echo '<tr id="img_' . $itemimage->Id . '">' . PHP_EOL;
                            echo '<td>' . $itemimage->Id . '</td>' . PHP_EOL;
                            echo '<td>' . $itemimage->ItemImageSide . '</td>' . PHP_EOL;
                            echo '<td><img src="/' . $itemimage->GetPath() . $itemimage->ItemImageUrl . '" style="width:80px;"></td>' . PHP_EOL;
                            echo '<td>';
                            $hcount = $objItemImageHighlight->getCountItemImageHighlightByItemImageId($itemimage->Id);
                            //echo '<a href="itemimagehotspot.php?img=' . $itemimage->Id . '">' . $hcount . ' Hotspots</a>';
                            // note: above hotspot admin would be pretty complicated from a user perspective (x, y, dimensions, image).
                            //       therefore, not implementing now. - Jay
                            echo $hcount . ' Hotspots</a>';
                            echo '</td>' . PHP_EOL;
//                             echo '<td class="mid"><img src="img/arrow-up-down.png" /></td>' . PHP_EOL;
                            echo '<td class="mid"><a href="itemimage_admin.php?id=' . $itemimage->Id . '" style="width:200px;"><img src="img/edit-icon.png" /></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="itemimage_delete.php?id=' . $itemimage->Id . '"><img src="img/delete-icon.png" /></a></td>' . PHP_EOL;
                            echo '</tr>' . PHP_EOL;
                        }
                        ?>
                    </tbody>
                </table>
    
            </div> <!-- layout -->

			<div class="all-section-separator"></div>

            <div class="layout">
    
                <table class="tablestyle" id="list_table">
                    <thead>
                    	<tr>
                    		<th colspan="4">Item Infos</th>
                    	</tr>
                    </thead>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Category</th>
                            <th>Text</th>
                            <th class="mid">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $objItemInfo = new ItemInfo();
                        $objItemInfoType = new ItemInfoType();
                        $item_info_types = $objItemInfoType->GetAllItemInfoType();
                        $objItemInfoImage = new ItemInfoImage();    
                        foreach ($item_info_types as $type) {
                        	$oItemInfo = $objItemInfo->GetAllItemInfoByItemIdItemInfoTypeId($_REQUEST['item'], $type->Id);
                        	$item_info = $oItemInfo[0];
                            echo '<tr>' . PHP_EOL;
                            echo '<td>' . $item_info->Id . '</td>' . PHP_EOL;
                            echo '<td>' . $type->ItemInfoTypeName . '</td>' . PHP_EOL;
                            echo '<td>' . strip_tags($item_info->ItemInfo) . '</td>' . PHP_EOL;
                            echo '<td class="mid"><a href="iteminfotype_admin.php?id=' . $item_info->Id . '" style="width:200px;"><img src="img/edit-icon.png" /></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="iteminfotype_delete.php?id=' . $item_info->Id . '"><img src="img/delete-icon.png" /></a> (not working)</td>' . PHP_EOL;
                            echo '</tr>' . PHP_EOL;
                        }                        
                        ?>
                    </tbody>
                </table>
    
            </div> <!-- layout -->

			<div class="all-section-separator"></div>

            <div class="layout">
    
                <table class="tablestyle" id="list_table">
                    <thead>
                    	<tr>
                    		<th colspan="4">Item Info Images</th>
                    	</tr>
                    </thead>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>&nbsp;</th>
                            <th class="mid">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $objItemInfoImage = new ItemInfoImage();
                        $oItemInfoImage = $objItemInfoImage->GetAllItemInfoImageByItemId($_REQUEST['item']);
                        foreach ($oItemInfoImage as $image) {
                            echo '<tr>' . PHP_EOL;
                            echo '<td>' . $image->Id . '</td>' . PHP_EOL;
                            echo '<td><img src="/' . $image->GetPath() . $image->ItemInfoImageUrl . '" style="height:60px;"></td>' . PHP_EOL;
                            echo '<td>' . '&nbsp;' . '</td>' . PHP_EOL;
                            echo '<td class="mid"><a href="iteminfoimage_admin.php?id=' . $image->Id . '" style="width:200px;"><img src="img/edit-icon.png" /></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="iteminfoimage_delete.php?id=' . $image->Id . '"><img src="img/delete-icon.png" /></a> (not working)</td>' . PHP_EOL;
                            echo '</tr>' . PHP_EOL;
                        }                        
                        ?>
                    </tbody>
                </table>
    
            </div> <!-- layout -->
            			
			<div class="all-section-separator"></div>

            <div class="layout">
    
                <table class="tablestyle" id="list_table">
                    <thead>
                    	<tr>
                    		<th colspan="5">Item Videos</th>
                    	</tr>
                    </thead>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>URL</th>
                            <th>Image</th>
                            <th class="mid">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $objItemVideo = new ItemVideo();
                        $oItemVideo = $objItemVideo->GetAllItemVideoByItemId($_REQUEST['item']);
                        foreach ($oItemVideo as $video) {
                            echo '<tr>' . PHP_EOL;
                            echo '<td>' . $video->Id . '</td>' . PHP_EOL;
                            echo '<td>' . $video->ItemVideoTitle . '</td>' . PHP_EOL;
                            echo '<td>' . $video->ItemVideoUrl . '</td>' . PHP_EOL;
                            echo '<td><img src="/' . $video->GetPath() . $video->ItemVideoPlaceholderImageUrl . '" style="height:60px;"></td>' . PHP_EOL;
                            echo '<td class="mid"><a href="itemvideo_admin.php?id=' . $video->Id . '" style="width:200px;"><img src="img/edit-icon.png" /></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="itemvideo_delete.php?id=' . $video->Id . '"><img src="img/delete-icon.png" /></a> (not working)</td>' . PHP_EOL;
                            echo '</tr>' . PHP_EOL;
                        }                        
                        ?>
                    </tbody>
                </table>
    
            </div> <!-- layout -->
						
			<div class="all-section-separator"></div>

<?php
	// this section disabled because added item_gallery table.
?>
<!-- 
            <div class="layout">
    
                <table class="tablestyle" id="list_table">
                    <thead>
                    	<tr>
                    		<th colspan="4">Item Gallery Images</th>
                    	</tr>
                    </thead>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Description</th>
                            <th>Image</th> 
                            <th class="mid">Order (comment out)</th>
                            <th class="mid">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $objItemGalleryImage = new ItemGalleryImage();
                        $oItemGalleryImage = $objItemGalleryImage->getAllItemGalleryImageByItemId($_REQUEST['item']);
                        foreach ($oItemGalleryImage as $image) {
                            echo '<tr id="img_' . $image->Id . '">' . PHP_EOL;
                            echo '<td>' . $image->ItemGalleryImageDescription . '</td>' . PHP_EOL;
                            echo '<td><img src="/' . $image->GetPath() . $image->ItemGalleryImageUrl . '" style="width:80px;"></td>' . PHP_EOL;
//                             echo '<td class="mid"><img src="img/arrow-up-down.png" /></td>' . PHP_EOL;
                            echo '<td class="mid"><a href="itemgalleryimage_admin.php?id=' . $image->Id . '" style="width:200px;"><img src="img/edit-icon.png" /></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="itemgalleryimage_delete.php?id=' . $image->Id . '"><img src="img/delete-icon.png" /></a> (not working)</td>' . PHP_EOL;
                            echo '</tr>' . PHP_EOL;
                        }
                        ?>
                    </tbody>
                </table>
    
            </div> <!~~ layout ~~>
 -->
			
			<p>Presentations:</p>

<?php 
}
?>