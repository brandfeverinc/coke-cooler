<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

require("../inc/classes/SoundImage.php");
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
	header("Location:soundimage_list.php?cat=" . $_REQUEST['cat'] . "&sound=" . $_REQUEST['sound']);
	exit;
}

if ($_POST['commit'] == "Save Sound Image") {
	
	if ($id == 0) {
		// insert
        $obj = new SoundImage();
        $obj->SoundId = $_REQUEST["sound"];
        $obj->create();
        
        $obj->handleFileUploads();
        $obj->handleDropFileUploads($aDropFields[0], 'ImageFile');

		// redirect to listing list
    	header("Location:soundimage_list.php?cat=" . $_REQUEST['cat'] . "&sound=" . $_REQUEST['sound']);
		exit;
	}
	else {
        // update
        $obj = new SoundImage($id);
        $obj->update();

        $obj->handleFileUploads();
        $obj->handleDropFileUploads($aDropFields[0], 'ImageFile');
		
		// redirect to listing list
    	header("Location:soundimage_list.php?cat=" . $_REQUEST['cat'] . "&sound=" . $_REQUEST['sound']);
		exit;
	}
}
else {
	if ($_REQUEST['mode'] == 'e') {
		//listing
        $objSoundImage = new SoundImage($id);
        $sound_id = $objSoundImage->SoundId;
        $image_file = $objSoundImage->ImageFile;
        $path = $objSoundImage->GetPath();
	}
	else if ($_REQUEST['mode'] == 'a') {
        $sound_id = $_REQUEST["sound"];
	}
}

include("includes/pagetemplate.php");

function PageContent() {
	global $id;
	global $sound_id;
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
	            $bread_image_name = 'Sound Image Add';
	        }
	        else {
	            $bread_image_name = 'Sound Image Edit';
	        }

		    $aLabels = array();
		    $aLinks = array();
                $aLabels[0] = 'Home';
                $aLinks[0] = 'mainpage.php';
                $aLabels[1] = 'Category List';
                $aLinks[1] = 'category_list.php';
                $aLabels[2] = 'Sound List';
                $aLinks[2] = 'sound_list.php';
                $aLabels[3] = 'Sound Images';
                $aLinks[3] = 'soundimage_list.php?cat=' . $_REQUEST['cat'] . '&sound=' . $_REQUEST['sound'];
                $aLabels[4] = $bread_image_name;
                $aLinks[4] = '';
		    echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
		    ?>

		    <p class="larger2 botspace heading"><?php echo $bread_image_name; ?></p>

			<div class="formstyle ">
				<form method="post" name="admin" id="admin" action="soundimage_admin.php" enctype='multipart/form-data'>
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
				
					<p class="submit"><input type="submit" name="commit" value="Save Sound Image">&nbsp;&nbsp;<input type="submit" name="commit" value="Cancel"></p>
				
                   	<input type="hidden" name="id" value="<?php echo $id; ?>" />
                   	<input type="hidden" name="cat" value="<? echo $cat; ?>" />
                   	<input type="hidden" name="sound" value="<?php echo $_REQUEST['sound']; ?>" />

				</form>
			</div>
		</div>

	</div>

<?php
// Set up Drag and Drop javascript controls
echo DragAndDrop::Control($aDropFields, $aDropFieldTypes);
}
?>