<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

require("../inc/classes/ItemVideo.php");
require("../inc/classes/Item.php");
require("../inc/classes/Category.php");
require('../inc/classes/DragAndDrop.php');
require('../inc/classes/Helpers.php');

// page vars
$message = "";
$id = "";
$pwmsg = '';

if ($_REQUEST["id"] == "") {
    $id = 0;
    $_REQUEST['mode'] = 'a';
}
else {
    $id = $_REQUEST["id"];
    $_REQUEST['mode'] = 'e';
}

// Setup Drag and Drop fields
$aDropFields = array();
$aDropFieldTypes = array();
array_push($aDropFields, 'item_video_url_drop');
array_push($aDropFieldTypes, 'video');
// array_push($aDropFields, 'item_video_placeholder_image_url_drop');
// array_push($aDropFieldTypes, 'image');

// if form was submitted:
if ($_POST['commit'] == "Cancel") {
	header("Location:itemvideo_list.php?cat=" . $_REQUEST['cat'] . "&item=" . $_REQUEST['item']);
	exit;
}

if ($_POST['commit'] == "Save Item Video") {
	
	if ($id == 0) {
		// insert
        $obj = new ItemVideo();
        $obj->ItemId = $_REQUEST["item"];
        $obj->ItemVideoTitle = $_REQUEST["item_video_title"];
        $obj->ItemVideoUrl = $_REQUEST["item_video_url"];
        $obj->create();
        
        $obj->handleFileUploads();
        $obj->handleDropFileUploads($aDropFields[0], 'ItemVideoUrl');
        //$obj->handleDropFileUploads($aDropFields[1], 'ItemVideoPlaceholderImageUrl');

		// redirect to listing list
    	header("Location:itemvideo_list.php?cat=" . $_REQUEST['cat'] . "&item=" . $_REQUEST['item']);
		exit;
	}
	else {
        // update
        $obj = new ItemVideo($id);
        $obj->ItemId = $_REQUEST["item"];
        $obj->ItemVideoTitle = $_REQUEST["item_video_title"];
        $obj->ItemVideoUrl = $_REQUEST["item_video_url"];
        $obj->update();

        $obj->handleFileUploads();
        $obj->handleDropFileUploads($aDropFields[0], 'ItemVideoUrl');
        //$obj->handleDropFileUploads($aDropFields[1], 'ItemVideoPlaceholderImageUrl');
		
		// redirect to listing list
    	header("Location:itemvideo_list.php?cat=" . $_REQUEST['cat'] . "&item=" . $_REQUEST['item']);
		exit;
	}
}
else {
	if ($_REQUEST['mode'] == 'e') {
		//listing
        $objItemVideo = new ItemVideo($id);
        $item_id = $objItemVideo->ItemId;
        $item_video_title = $objItemVideo->ItemVideoTitle;
        $item_video_url = $objItemVideo->ItemVideoUrl;
        $item_video_placeholder_image_url = $objItemVideo->ItemVideoPlaceholderImageUrl;
        $path = $objItemVideo->GetPath();
	}
	else if ($_REQUEST['mode'] == 'a') {
        $item_id = $_REQUEST["item"];
	}
}

include("includes/pagetemplate.php");

function PageContent() {
	global $id;
	global $item_id;
	global $item_video_title;
	global $item_video_url;
	global $item_video_placeholder_image_url;
	global $path;
    global $aDropFields;
    global $aDropFieldTypes;
    $item = new Item($item_id);
    $category = new Category($item->CategoryId);
?>

    <?php
    // Set up Drag And Drop styles for the drop area
    echo DragAndDrop::SetStyles();
    ?>

	<div id="content">

		<div class="layout">
	        <?php
	        if ($id == 0) {
	            $bread_image_name = 'Item Video Add';
	        }
	        else {
	            $bread_image_name = 'Item Video Edit';
	        }

		    $aLabels = array();
		    $aLinks = array();
                $aLabels[0] = 'Home';
                $aLinks[0] = 'mainpage.php';
                $aLabels[1] = $category->CategoryName;
                $aLinks[1] = 'category_list.php';
                $aLabels[2] = 'Items';
                $aLinks[2] = 'item_list.php?cat=' . $_REQUEST['cat'];
                $aLabels[3] = 'Item Videos';
                $aLinks[3] = 'itemvideo_list.php?cat=' . $_REQUEST['cat'] . '&item=' . $_REQUEST['item'];
                $aLabels[4] = $bread_image_name;
                $aLinks[4] = '';
		    echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
		    ?>

		    <p class="larger2 botspace heading"><?php echo $bread_image_name; ?></p>

			<div class="formstyle ">
				<form method="post" name="admin" id="admin" action="itemvideo_admin.php" enctype='multipart/form-data'>
					<table class="form-table">
						<tr>
							<td class="table-label" align="right">Video Title: </td>
							<td><input class="form midform" type="text" name="item_video_title" value="<?php echo $item_video_title; ?>" placeholder="Video Title" /></td>
						</tr>
						<tr>
							<td class="table-label" align="right">Video File: </td>
							<td><input class="form midform" type="text" name="item_video_url" value="<?php echo $item_video_url; ?>" placeholder="Video File Name" />&nbsp;&nbsp;MP4, WMV or MOV</td>
						</tr>
                    	<tr>
                    		<td class="table-label" align="right" valign="top">Upload Video: </td>
                    		<td><input type="file" class="slick" size="50" maxlength="200" name="video_file" id="video_file" value="<?php echo(htmlspecialchars($item_video_url)); ?>"/>
                    		    <?php
                    		    if ($item_video_url != '') {
                    		        echo '<br ><strong>Current File:</strong> ' . $item_video_url;
                    		    }
                    		    
                    		    // Set up Drop Area
                    		    echo DragAndDrop::CreateArea($aDropFields[0]);
                    		    ?>
                    		</td>
                    	</tr>
                    	<tr>
                    		<td class="table-label">&nbsp;</td>
                    		<td><p>Note: if you have trouble uploading a large file, use FTP to place the file into the folder 'files/itemvideos', then save the file name in 'Video File' above.</p></td>
                    	</tr>

					</table>
				
					<p class="submit"><input type="submit" name="commit" value="Save Item Video">&nbsp;&nbsp;<input type="submit" name="commit" value="Cancel"></p>
				
                   	<input type="hidden" name="id" value="<?php echo $id; ?>" />
                   	<input type="hidden" name="cat" value="<?php echo $_REQUEST['cat']; ?>" />
                   	<input type="hidden" name="item" value="<?php echo $_REQUEST['item']; ?>" />

				</form>
			</div>
		</div>

	</div>

<?php
// Set up Drag and Drop javascript controls
echo DragAndDrop::Control($aDropFields, $aDropFieldTypes);
}
?>