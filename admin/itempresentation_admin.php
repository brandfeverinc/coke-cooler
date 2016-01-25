<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

require("../inc/classes/ItemPresentation.php");
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
array_push($aDropFields, 'item_presentation_thumbnail_url_drop');
array_push($aDropFieldTypes, 'image');

// if form was submitted:
if ($_POST['commit'] == "Cancel") {
	header("Location:itempresentation_list.php?cat=" . $_REQUEST['cat'] . "&item=" . $_REQUEST['item']);
	exit;
}

if ($_POST['commit'] == "Save Item Presentation") {
	
	if ($id == 0) {
		// insert
        $obj = new ItemPresentation();
        $obj->ItemId = $_REQUEST["item"];
        $obj->ItemPresentationName = $_REQUEST["item_presentation_name"];
        $obj->create();
        
        $obj->handleFileUploads();
        $obj->handleDropFileUploads($aDropFields[0], 'ItemPresentationThumbnailUrl');

		// redirect to listing list
    	header("Location:itempresentation_list.php?cat=" . $_REQUEST['cat'] . "&item=" . $_REQUEST['item']);
		exit;
	}
	else {
        // update
        $obj = new ItemPresentation($id);
        $obj->ItemPresentationName = $_REQUEST["item_presentation_name"];
        $obj->update();

        $obj->handleFileUploads();
        $obj->handleDropFileUploads($aDropFields[0], 'ItemPresentationThumbnailUrl');
		
		// redirect to listing list
    	header("Location:itempresentation_list.php?cat=" . $_REQUEST['cat'] . "&item=" . $_REQUEST['item']);
		exit;
	}
}
else {
	if ($_REQUEST['mode'] == 'e') {
		//listing
        $objItemPresentation = new ItemPresentation($id);
        $item_id = $objItemPresentation->ItemId;
        $item_presentation_name = $objItemPresentation->ItemPresentationName;
        $item_presentation_thumbnail_url = $objItemPresentation->ItemPresentationThumbnailUrl;
        $path = $objItemPresentation->GetPath();
	}
	else if ($_REQUEST['mode'] == 'a') {
        $item_id = $_REQUEST["item"];
	}
}

include("includes/pagetemplate.php");

function PageContent() {
	global $id;
	global $item_id;
	global $item_presentation_name;
	global $item_presentation_thumbnail_url;
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
	            $bread_image_name = 'Item Presentation Add';
	        }
	        else {
	            $bread_image_name = 'Item Presentation Edit';
	        }

		    $aLabels = array();
		    $aLinks = array();
                $aLabels[0] = 'Home';
                $aLinks[0] = 'mainpage.php';
                $aLabels[1] = $objCategory->CategoryName;
                $aLinks[1] = 'category_list.php';
                $aLabels[2] = 'Items';
                $aLinks[2] = 'item_list.php?cat=' . $_REQUEST['cat'];
                $aLabels[3] = 'Item Presentations';
                $aLinks[3] = 'itempresentation_list.php?cat=' . $_REQUEST['cat'] . '&item=' . $_REQUEST['item'];
                $aLabels[4] = $bread_image_name;
                $aLinks[4] = '';
		    echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
		    ?>

		    <p class="larger2 botspace heading"><?php echo $bread_image_name; ?></p>

			<div class="formstyle ">
				<form method="post" name="admin" id="admin" action="itempresentation_admin.php" enctype='multipart/form-data'>
					<table class="form-table">
						<tr>
							<td class="table-label" align="right">Presentation Name: </td>
							<td><input class="form midform" type="text" name="item_presentation_name" value="<?php echo $item_presentation_name ?>" placeholder="Presentation Name" /></td>
						</tr>
					    <?php 
					    if ($item_presentation_thumbnail_url != '') {
					        ?>
    						<tr>
    							<td>&nbsp;</td>
    							<td>
    							    <img src="/<?php echo $path . $item_presentation_thumbnail_url; ?>" style="width:120px;">
    							</td>
    						</tr>
    						<?php
    					}
    					?>
                    	<tr>
                    		<td class="table-label" align="right" valign="top">Image File: </td>
                    		<td><input type="file" class="slick" size="50" maxlength="200" name="item_presentation_thumbnail_url" id="item_presentation_thumbnail_url" value="<?php echo(htmlspecialchars($item_presentation_thumbnail_url)); ?>"/>
                    		    <?php
                    		    if ($item_presentation_thumbnail_url != '') {
                    		        echo '<br ><strong>Current File:</strong> ' . $item_presentation_thumbnail_url;
                    		    }
                    		    
                    		    // Set up Drop Area
                    		    echo DragAndDrop::CreateArea($aDropFields[0]);
                    		    ?>
                    		</td>
                    	</tr>
					</table>
				
					<p class="submit"><input type="submit" name="commit" value="Save Item Presentation">&nbsp;&nbsp;<input type="submit" name="commit" value="Cancel"></p>
				
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