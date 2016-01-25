<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

require("../inc/classes/LightImage.php");
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
	header("Location:lightimage_list.php?cat=" . $_REQUEST['cat'] . "&light=" . $_REQUEST['light']);
	exit;
}

if ($_POST['commit'] == "Save Light Image") {
	
	if ($id == 0) {
		// insert
        $obj = new LightImage();
        $obj->LightId = $_REQUEST["light"];
        $obj->create();
        
        $obj->handleFileUploads();
        $obj->handleDropFileUploads($aDropFields[0], 'ImageFile');

		// redirect to listing list
    	header("Location:lightimage_list.php?cat=" . $_REQUEST['cat'] . "&light=" . $_REQUEST['light']);
		exit;
	}
	else {
        // update
        $obj = new LightImage($id);
        $obj->update();

        $obj->handleFileUploads();
        $obj->handleDropFileUploads($aDropFields[0], 'ImageFile');
		
		// redirect to listing list
    	header("Location:lightimage_list.php?cat=" . $_REQUEST['cat'] . "&light=" . $_REQUEST['light']);
		exit;
	}
}
else {
	if ($_REQUEST['mode'] == 'e') {
		//listing
        $objLightImage = new LightImage($id);
        $light_id = $objLightImage->LightId;
        $image_file = $objLightImage->ImageFile;
        $path = $objLightImage->GetPath();
	}
	else if ($_REQUEST['mode'] == 'a') {
        $light_id = $_REQUEST["light"];
	}
}

include("includes/pagetemplate.php");

function PageContent() {
	global $id;
	global $light_id;
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
	            $bread_image_name = 'Light Image Add';
	        }
	        else {
	            $bread_image_name = 'Light Image Edit';
	        }

		    $aLabels = array();
		    $aLinks = array();
                $aLabels[0] = 'Home';
                $aLinks[0] = 'mainpage.php';
                $aLabels[1] = 'Category List';
                $aLinks[1] = 'category_list.php';
                $aLabels[2] = 'Light List';
                $aLinks[2] = 'light_list.php';
                $aLabels[3] = 'Light Images';
                $aLinks[3] = 'lightimage_list.php?cat=' . $_REQUEST['cat'] . '&light=' . $_REQUEST['light'];
                $aLabels[4] = $bread_image_name;
                $aLinks[4] = '';
		    echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
		    ?>

		    <p class="larger2 botspace heading"><?php echo $bread_image_name; ?></p>

			<div class="formstyle ">
				<form method="post" name="admin" id="admin" action="lightimage_admin.php" enctype='multipart/form-data'>
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
				
					<p class="submit"><input type="submit" name="commit" value="Save Light Image">&nbsp;&nbsp;<input type="submit" name="commit" value="Cancel"></p>
				
                   	<input type="hidden" name="id" value="<?php echo $id; ?>" />
                   	<input type="hidden" name="cat" value="<? echo $cat; ?>" />
                   	<input type="hidden" name="light" value="<?php echo $_REQUEST['light']; ?>" />

				</form>
			</div>
		</div>

	</div>

<?php
// Set up Drag and Drop javascript controls
echo DragAndDrop::Control($aDropFields, $aDropFieldTypes);
}
?>