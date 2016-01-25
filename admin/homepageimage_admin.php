<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

require("../inc/classes/HomepageImage.php");
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
array_push($aDropFields, 'homepage_image_url_drop');
array_push($aDropFieldTypes, 'image');

// if form was submitted:
if ($_POST['commit'] == "Cancel") {
	header("Location:homepageimage_list.php");
	exit;
}

if ($_POST['commit'] == "Save Home Page Image") {
	
	if ($id == 0) {
		// add the listing

        $objHomepageImage = new HomepageImage();
        if ($_REQUEST['is_active'] == '1') {
            $objHomepageImage->IsActive = '1';
        }
        else {
            $objHomepageImage->IsActive = '0';
        }
        $objHomepageImage->create();
        
        $objHomepageImage->handleFileUploads();
        $objHomepageImage->handleDropFileUploads($aDropFields[0], 'HomepageImageUrl');
        
		// redirect to listing list
		header("Location:homepageimage_list.php");
		exit;
	}
	else {

        $objHomepageImage = new HomepageImage($_REQUEST["id"]);
        if ($_REQUEST['is_active'] == '1') {
            $objHomepageImage->IsActive = '1';
        }
        else {
            $objHomepageImage->IsActive = '0';
        }
        $objHomepageImage->update();
		
        $objHomepageImage->handleFileUploads();
        $objHomepageImage->handleDropFileUploads($aDropFields[0], 'HomepageImageUrl');

		// redirect to listing list
		header("Location:homepageimage_list.php");
		exit;
	}
}
else {
	if ($_REQUEST['mode'] == 'e') {
		//listing
		$objHomepageImage = new HomepageImage($id);
		$is_active = $objHomepageImage->IsActive;
		$homepage_image_url = $objHomepageImage->HomepageImageUrl;
	}
}

include("includes/pagetemplate.php");

function PageContent() {
	global $id;
	global $is_active;
	global $homepage_image_url;
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
	            $bread_image_name = 'Home Page Image Add';
	        }
	        else {
	            $bread_image_name = 'Home Page Image Edit';
	        }

		    $aLabels = array();
		    $aLinks = array();
		    $aLabels[0] = 'Home';
		    $aLinks[0] = 'mainpage.php';
		    $aLabels[1] = 'Home Page Images';
		    $aLinks[1] = 'homepageimage_list.php';
		    $aLabels[2] = $bread_image_name;
		    $aLinks[2] = '';
		    echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
		    ?>

		    <p class="larger2 botspace heading"><?php echo $bread_image_name; ?></p>

			<div class="formstyle ">
				<form method="post" name="admin" id="admin" action="homepageimage_admin.php" enctype='multipart/form-data'>
					<table class="form-table">
                    	<tr>
                    		<td class="table-label" align="right" valign="top">Image File: </td>
                    		<td><input type="file" class="slick" size="50" maxlength="200" name="homepage_image_url" id="homepage_image_url" value="<?php echo(htmlspecialchars($homepage_image_url)); ?>"/>
                    		    <?php
                    		    if ($homepage_image_url != '') {
                    		        echo '<br ><strong>Current File:</strong> ' . $homepage_image_url;
                    		    }
                    		    
                    		    // Set up Drop Area
                    		    echo DragAndDrop::CreateArea($aDropFields[0]);
                    		    ?>
                    		</td>
                    	</tr>
                    	<tr>
                    		<td class="table-label" align="right" valign="top">Is Active?: </td>
                    		<td>
                    		    <input type="checkbox" <?php if ($is_active == '1') { echo 'checked'; } ?> name="is_active" value="1" />
                    		</td>
                    	</tr>
					</table>
				
					<p class="submit"><input type="submit" name="commit" value="Save Home Page Image">&nbsp;&nbsp;<input type="submit" name="commit" value="Cancel"></p>
				
                   	<input type="hidden" name="id" value="<? echo $id; ?>" />

				</form>
		</div>

	</div>

<?php
// Set up Drag and Drop javascript controls
echo DragAndDrop::Control($aDropFields, $aDropFieldTypes);
}
?>