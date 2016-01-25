<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

require("../inc/classes/IbeaconImage.php");
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
	header("Location:ibeaconimage_list.php?cat=" . $_REQUEST['cat'] . "&ibeacon=" . $_REQUEST['ibeacon']);
	exit;
}

if ($_POST['commit'] == "Save iBeacon Image") {
	
	if ($id == 0) {
		// insert
        $obj = new IbeaconImage();
        $obj->IbeaconId = $_REQUEST["ibeacon"];
        $obj->create();
        
        $obj->handleFileUploads();
        $obj->handleDropFileUploads($aDropFields[0], 'ImageFile');

		// redirect to listing list
    	header("Location:ibeaconimage_list.php?cat=" . $_REQUEST['cat'] . "&ibeacon=" . $_REQUEST['ibeacon']);
		exit;
	}
	else {
        // update
        $obj = new IbeaconImage($id);
        $obj->update();

        $obj->handleFileUploads();
        $obj->handleDropFileUploads($aDropFields[0], 'ImageFile');
		
		// redirect to listing list
    	header("Location:ibeaconimage_list.php?cat=" . $_REQUEST['cat'] . "&ibeacon=" . $_REQUEST['ibeacon']);
		exit;
	}
}
else {
	if ($_REQUEST['mode'] == 'e') {
		//listing
        $objIbeaconImage = new IbeaconImage($id);
        $ibeacon_id = $objIbeaconImage->IbeaconId;
        $image_file = $objIbeaconImage->ImageFile;
        $path = $objIbeaconImage->GetPath();
	}
	else if ($_REQUEST['mode'] == 'a') {
        $ibeacon_id = $_REQUEST["ibeacon"];
	}
}

include("includes/pagetemplate.php");

function PageContent() {
	global $id;
	global $ibeacon_id;
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
	            $bread_image_name = 'iBeacon Image Add';
	        }
	        else {
	            $bread_image_name = 'iBeacon Image Edit';
	        }

		    $aLabels = array();
		    $aLinks = array();
                $aLabels[0] = 'Home';
                $aLinks[0] = 'mainpage.php';
                $aLabels[1] = 'Category List';
                $aLinks[1] = 'category_list.php';
                $aLabels[2] = 'iBeacon List';
                $aLinks[2] = 'ibeacon_list.php';
                $aLabels[3] = 'iBeacon Images';
                $aLinks[3] = 'ibeaconimage_list.php?cat=' . $_REQUEST['cat'] . '&ibeacon=' . $_REQUEST['ibeacon'];
                $aLabels[4] = $bread_image_name;
                $aLinks[4] = '';
		    echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
		    ?>

		    <p class="larger2 botspace heading"><?php echo $bread_image_name; ?></p>

			<div class="formstyle ">
				<form method="post" name="admin" id="admin" action="ibeaconimage_admin.php" enctype='multipart/form-data'>
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
				
					<p class="submit"><input type="submit" name="commit" value="Save iBeacon Image">&nbsp;&nbsp;<input type="submit" name="commit" value="Cancel"></p>
				
                   	<input type="hidden" name="id" value="<?php echo $id; ?>" />
                   	<input type="hidden" name="cat" value="<?php echo $_REQUEST['cat']; ?>" />
                   	<input type="hidden" name="ibeacon" value="<?php echo $_REQUEST['ibeacon']; ?>" />

				</form>
			</div>
		</div>

	</div>

<?php
// Set up Drag and Drop javascript controls
echo DragAndDrop::Control($aDropFields, $aDropFieldTypes);
}
?>