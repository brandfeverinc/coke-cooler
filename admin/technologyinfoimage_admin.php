<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

require("../inc/classes/TechnologyInfoImage.php");
require("../inc/classes/TechnologyInfo.php");
require("../inc/classes/Technology.php");
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
array_push($aDropFields, 'technology_info_image_url_drop');
array_push($aDropFieldTypes, 'image');

// if form was submitted:
if ($_POST['commit'] == "Cancel") {
	header("Location:technologyinfoimage_list.php?cat=" . $_REQUEST['cat'] . "&tech=" . $_REQUEST['tech'] . '&techinfo=' . $_REQUEST['techinfo']);
	exit;
}

if ($_POST['commit'] == "Save Technology Info Image") {
	
	if ($id == 0) {
		// insert
        $obj = new TechnologyInfoImage();
        $obj->TechnologyInfoId = $_REQUEST["techinfo"];
        $obj->create();
        
        $obj->handleFileUploads();
        $obj->handleDropFileUploads($aDropFields[0], 'TechnologyInfoImageUrl');

		// redirect to listing list
		header("Location:technologyinfoimage_list.php?cat=" . $_REQUEST['cat'] . "&tech=" . $_REQUEST['tech'] . '&techinfo=' . $_REQUEST['techinfo']);
		exit;
	}
	else {
        // update
        $obj = new TechnologyInfoImage($id);
        $obj->update();

        $obj->handleFileUploads();
        $obj->handleDropFileUploads($aDropFields[0], 'TechnologyInfoImageUrl');
		
		// redirect to listing list
		header("Location:technologyinfoimage_list.php?cat=" . $_REQUEST['cat'] . "&tech=" . $_REQUEST['tech'] . '&techinfo=' . $_REQUEST['techinfo']);
		exit;
	}
}
else {
	if ($_REQUEST['mode'] == 'e') {
		//listing
        $objTechnologyInfoImage = new TechnologyInfoImage($id);
        $technology_info_image_url = $objTechnologyInfoImage->TechnologyInfoImageUrl;
	}
}

include("includes/pagetemplate.php");

function PageContent() {
	global $id;
	global $item_id;
	global $technology_info_image_url;
    global $aDropFields;
    global $aDropFieldTypes;
    $objTechnology = new Technology($_REQUEST['tech']);
?>

    <?php
    // Set up Drag And Drop styles for the drop area
    echo DragAndDrop::SetStyles();
    ?>

	<div id="content">

		<div class="layout">
	        <?php
	        if ($id == 0) {
	            $bread_image_name = 'Technology Info Image Add';
	        }
	        else {
	            $bread_image_name = 'Technology Info Image Edit';
	        }

		    $aLabels = array();
		    $aLinks = array();
                $aLabels[0] = 'Home';
                $aLinks[0] = 'mainpage.php';
                $aLabels[1] = 'Category List';
                $aLinks[1] = 'category_list.php';
                $aLabels[2] = 'Technology';
                $aLinks[2] = 'technology_list.php?cat=' . $_REQUEST['cat'];
                $aLabels[3] = $objTechnology->TechnologyName;
                $aLinks[3] = 'technologyinfo_list.php?cat=' . $_REQUEST['cat'] . '&tech=' . $_REQUEST['tech'];
                $aLabels[4] = 'Technology Info Images';
                $aLinks[4] = 'technologyinfoimage_list.php?cat=' . $_REQUEST['cat'] . '&tech=' . $_REQUEST['tech'] . '&techimage=' . $_REQUEST['techimage'];
                $aLabels[5] = $bread_image_name;
                $aLinks[5] = '';
		    echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
		    ?>

		    <p class="larger2 botspace heading"><?php echo $bread_image_name; ?></p>

			<div class="formstyle ">
				<form method="post" name="admin" id="admin" action="technologyinfoimage_admin.php" enctype='multipart/form-data'>
					<table class="form-table">
                    	<tr>
                    		<td class="table-label" align="right" valign="top">Image File: </td>
                    		<td><input type="file" class="slick" size="50" maxlength="200" name="technology_info_image_url" id="technology_info_image_url" value="<?php echo(htmlspecialchars($technology_info_image_url)); ?>"/>
                    		    <?php
                    		    if ($technology_info_image_url != '') {
                    		        echo '<br ><strong>Current File:</strong> ' . $technology_info_image_url;
                    		    }
                    		    
                    		    // Set up Drop Area
                    		    echo DragAndDrop::CreateArea($aDropFields[0]);
                    		    ?>
                    		</td>
                    	</tr>
					</table>
				
					<p class="submit"><input type="submit" name="commit" value="Save Technology Info Image">&nbsp;&nbsp;<input type="submit" name="commit" value="Cancel"></p>
				
                   	<input type="hidden" name="id" value="<?php echo $id; ?>" />
                  	<input type="hidden" name="cat" value="<? echo $_REQUEST['cat']; ?>" />
                   	<input type="hidden" name="tech" value="<?php echo $_REQUEST['tech']; ?>" />
                   	<input type="hidden" name="techinfo" value="<?php echo $_REQUEST['techinfo']; ?>" />

				</form>
			</div>
		</div>

	</div>

<?php
// Set up Drag and Drop javascript controls
echo DragAndDrop::Control($aDropFields, $aDropFieldTypes);
}
?>