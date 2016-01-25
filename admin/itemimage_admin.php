<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

require("../inc/classes/ItemImage.php");
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
array_push($aDropFields, 'item_image_url_drop');
array_push($aDropFieldTypes, 'image');

// if form was submitted:
if ($_POST['commit'] == "Cancel") {
	header("Location:itemimage_list.php?cat=" . $_REQUEST['cat'] . "&item=" . $_REQUEST['item']);
	exit;
}

if ($_POST['commit'] == "Save Item Image") {
	
	if ($id == 0) {
		// insert
        $obj = new ItemImage();
        $obj->ItemId = $_REQUEST["item"];
        $obj->ItemImageSide = $_REQUEST["item_image_side"];
        $obj->create();
        
        $obj->handleFileUploads();
        $obj->handleDropFileUploads($aDropFields[0], 'ItemImageUrl');

		// redirect to listing list
    	header("Location:itemimage_list.php?cat=" . $_REQUEST['cat'] . "&item=" . $_REQUEST['item']);
		exit;
	}
	else {
        // update
        $obj = new ItemImage($id);
        $obj->ItemId = $_REQUEST["item"];
        $obj->ItemImageSide = $_REQUEST["item_image_side"];
        $obj->update();

        $obj->handleFileUploads();
        $obj->handleDropFileUploads($aDropFields[0], 'ItemImageUrl');
		
		// redirect to listing list
    	header("Location:itemimage_list.php?cat=" . $_REQUEST['cat'] . "&item=" . $_REQUEST['item']);
		exit;
	}
}
else {
	if ($_REQUEST['mode'] == 'e') {
		//listing
        $objItemImage = new ItemImage($id);
        $item_id = $objItemImage->ItemId;
        $item_image_side = $objItemImage->ItemImageSide;
        $item_image_url = $objItemImage->ItemImageUrl;
        $path = $objItemImage->GetPath();
	}
	else if ($_REQUEST['mode'] == 'a') {
        $item_id = $_REQUEST["item"];
	}
}

include("includes/pagetemplate.php");

function PageContent() {
	global $id;
	global $item_id;
	global $item_image_side;
	global $item_image_url;
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
	            $bread_image_name = 'Item Image Add';
	        }
	        else {
	            $bread_image_name = 'Item Image Edit';
	        }

		    $aLabels = array();
		    $aLinks = array();
                $aLabels[0] = 'Home';
                $aLinks[0] = 'mainpage.php';
                $aLabels[1] = $objCategory->CategoryName;
                $aLinks[1] = 'category_list.php';
                $aLabels[2] = 'Items';
                $aLinks[2] = 'item_list.php?cat=' . $_REQUEST['cat'];
                $aLabels[3] = 'Item Images';
                $aLinks[3] = 'itemimage_list.php?cat=' . $_REQUEST['cat'] . '&item=' . $_REQUEST['item'];
                $aLabels[4] = $bread_image_name;
                $aLinks[4] = '';
		    echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
		    ?>

		    <p class="larger2 botspace heading"><?php echo $bread_image_name; ?></p>

			<div class="formstyle ">
				<form method="post" name="admin" id="admin" action="itemimage_admin.php" enctype='multipart/form-data'>
					<table class="form-table">
					    <?php 
					    if ($item_image_url != '') {
					        ?>
    						<tr>
    							<td>&nbsp;</td>
    							<td>
    							    <img src="/<?php echo $path . $item_image_url; ?>" style="width:120px;">
    							</td>
    						</tr>
    						<?php
    					}
    					?>
                    	<tr>
                    		<td class="table-label" align="right" valign="top">Image File: </td>
                    		<td><input type="file" class="slick" size="50" maxlength="200" name="image_file" id="image_file" value="<?php echo(htmlspecialchars($item_image_url)); ?>"/>
                    		    <?php
                    		    if ($item_image_url != '') {
                    		        echo '<br ><strong>Current File:</strong> ' . $item_image_url;
                    		    }
                    		    
                    		    // Set up Drop Area
                    		    echo DragAndDrop::CreateArea($aDropFields[0]);
                    		    ?>
                    		</td>
                    	</tr>
						<tr>
							<td class="table-label" align="right">Side: </td>
							<td>
							    <select name="item_image_side" class="form">
							        <option value=""></option>
							        <option value="Front"<?php if ($item_image_side == 'Front') { echo ' selected'; } ?>>Front</option>
							        <option value="Left"<?php if ($item_image_side == 'Left') { echo ' selected'; } ?>>Left</option>
							        <option value="Right"<?php if ($item_image_side == 'Right') { echo ' selected'; } ?>>Right</option>
							        <option value="Back"<?php if ($item_image_side == 'Back') { echo ' selected'; } ?>>Back</option>
							        <option value="Open"<?php if ($item_image_side == 'Open') { echo ' selected'; } ?>>Open</option>
							    </select>
							</td>
						</tr>
					</table>
				
					<p class="submit"><input type="submit" name="commit" value="Save Item Image">&nbsp;&nbsp;<input type="submit" name="commit" value="Cancel"></p>
				
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