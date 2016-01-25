<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

require("../inc/classes/ColorImage.php");
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
array_push($aDropFields, 'image_file_drop');
array_push($aDropFieldTypes, 'image');

// if form was submitted:
if ($_POST['commit'] == "Cancel") {
	header("Location:colorimage_list.php?cat=" . $_REQUEST['cat'] . "&color=" . $_REQUEST['color']);
	exit;
}

if ($_POST['commit'] == "Save Color Image") {
	
	if ($id == 0) {
		// insert
        $obj = new ColorImage();
        $obj->ColorId = $_REQUEST["color"];
        $obj->create();
        
        $obj->handleFileUploads();
        $obj->handleDropFileUploads($aDropFields[0], 'ImageFile');

		// redirect to listing list
    	header("Location:colorimage_list.php?cat=" . $_REQUEST['cat'] . "&color=" . $_REQUEST['color']);
		exit;
	}
	else {
        // update
        $obj = new ColorImage($id);
        $obj->update();

        $obj->handleFileUploads();
        $obj->handleDropFileUploads($aDropFields[0], 'ImageFile');
		
		// redirect to listing list
    	header("Location:colorimage_list.php?cat=" . $_REQUEST['cat'] . "&color=" . $_REQUEST['color']);
		exit;
	}
}
else {
	if ($_REQUEST['mode'] == 'e') {
		//listing
        $objColorImage = new ColorImage($id);
        $color_id = $objColorImage->ColorId;
        $image_file = $objColorImage->ImageFile;
        $path = $objColorImage->GetPath();
	}
	else if ($_REQUEST['mode'] == 'a') {
        $color_id = $_REQUEST["color"];
	}
}

include("includes/pagetemplate.php");

function PageContent() {
	global $id;
	global $color_id;
	global $image_file;
	global $path;
    global $aDropFields;
    global $aDropFieldTypes;
?>

    <?php
    // Set up Drag And Drop styles for the drop area
    echo DragAndDrop::SetStyles();
    ?>

	<div id="content">

		<div class="layout">
	        <?php
	        if ($id == 0) {
	            $bread_image_name = 'Color Image Add';
	        }
	        else {
	            $bread_image_name = 'Color Image Edit';
	        }

		    $aLabels = array();
		    $aLinks = array();
                $aLabels[0] = 'Home';
                $aLinks[0] = 'mainpage.php';
                $aLabels[1] = 'Category List';
                $aLinks[1] = 'category_list.php';
                $aLabels[2] = 'Color List';
                $aLinks[2] = 'color_list.php?cat=' . $_REQUEST['cat'];
                $aLabels[3] = 'Color Images';
                $aLinks[3] = 'colorimage_list.php?cat=' . $_REQUEST['cat'] . '&color=' . $_REQUEST['color'];
                $aLabels[4] = $bread_image_name;
                $aLinks[4] = '';
		    echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
		    ?>

		    <p class="larger2 botspace heading"><?php echo $bread_image_name; ?></p>

			<div class="formstyle ">
				<form method="post" name="admin" id="admin" action="colorimage_admin.php" enctype='multipart/form-data'>
					<table class="form-table">
					    <?php 
					    if ($image_file != '') {
					        ?>
    						<tr>
    							<td>&nbsp;</td>
    							<td>
    							    <img src="/<?php echo $path . $image_file; ?>" style="width:120px;">
    							</td>
    						</tr>
    						<?php
    					}
    					?>
                    	<tr>
                    		<td class="table-label" align="right" valign="top">Image File: </td>
                    		<td><input type="file" class="slick" size="50" maxlength="200" name="image_file" id="image_file" value="<?php echo(htmlspecialchars($image_file)); ?>"/>
                    		    <?php
                    		    if ($image_file != '') {
                    		        echo '<br ><strong>Current File:</strong> ' . $image_file;
                    		    }
                    		    
                    		    // Set up Drop Area
                    		    echo DragAndDrop::CreateArea($aDropFields[0]);
                    		    ?>
                    		</td>
                    	</tr>
					</table>
				
					<p class="submit"><input type="submit" name="commit" value="Save Color Image">&nbsp;&nbsp;<input type="submit" name="commit" value="Cancel"></p>
				
                   	<input type="hidden" name="id" value="<?php echo $id; ?>" />
                   	<input type="hidden" name="cat" value="<?php echo $_REQUEST['cat']; ?>" />
                   	<input type="hidden" name="color" value="<?php echo $_REQUEST['color']; ?>" />

				</form>
			</div>
		</div>

	</div>

<?php
// Set up Drag and Drop javascript controls
echo DragAndDrop::Control($aDropFields, $aDropFieldTypes);
}
?>