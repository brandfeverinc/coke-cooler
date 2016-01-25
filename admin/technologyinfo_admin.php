<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

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
array_push($aDropFields, 'technology_info_button_image_url_drop');
array_push($aDropFieldTypes, 'image');

// if form was submitted:
if ($_POST['commit'] == "Cancel") {
	header("Location:technologyinfo_list.php?cat=" . $_REQUEST['cat'] . "&tech=" . $_REQUEST['tech']);
	exit;
}

if ($_POST['commit'] == "Save Technology Info") {
	
	if ($id == 0) {
		// insert
        $obj = new TechnologyInfo();
        $obj->TechnologyId = $_REQUEST["tech"];
        $obj->TechnologyInfoName = $_REQUEST["technology_info_name"];
        $obj->TechnologyInfoDescription = $_REQUEST["technology_info_description"];
        $obj->TechnologyInfoTemplate = $_REQUEST["technology_info_template"];
        $obj->create();
        
        $obj->handleFileUploads();
        $obj->handleDropFileUploads($aDropFields[0], 'TechnologyInfoButtonImageUrl');

		// redirect to listing list
		header("Location:technologyinfo_list.php?cat=" . $_REQUEST['cat'] . "&tech=" . $_REQUEST['tech']);
		exit;
	}
	else {
        // update
        $obj = new TechnologyInfo($id);
        $obj->TechnologyInfoName = $_REQUEST["technology_info_name"];
        $obj->TechnologyInfoDescription = $_REQUEST["technology_info_description"];
        $obj->TechnologyInfoTemplate = $_REQUEST["technology_info_template"];
        $obj->update();

        $obj->handleFileUploads();
        $obj->handleDropFileUploads($aDropFields[0], 'TechnologyInfoButtonImageUrl');
		
		// redirect to listing list
		header("Location:technologyinfo_list.php?cat=" . $_REQUEST['cat'] . "&tech=" . $_REQUEST['tech']);
		exit;
	}
}
else {
	if ($_REQUEST['mode'] == 'e') {
		//listing
        $objTechnologyInfo = new TechnologyInfo($id);
        $technology_info_name = $objTechnologyInfo->TechnologyInfoName;
        $technology_info_description = $objTechnologyInfo->TechnologyInfoDescription;
        $technology_info_template = $objTechnologyInfo->TechnologyInfoTemplate;
        $technology_info_button_image_url = $objTechnologyInfo->TechnologyInfoButtonImageUrl;
	}
}

include("includes/pagetemplate.php");

function PageContent() {
	global $id;
	global $item_id;
	global $technology_info_name;
	global $technology_info_description;
	global $technology_info_template;
	global $technology_info_button_image_url;
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
	            $bread_image_name = 'Technology Info Add';
	        }
	        else {
	            $bread_image_name = 'Technology Info Edit';
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
                $aLabels[4] = $bread_image_name;
                $aLinks[4] = '';
		    echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
		    ?>

		    <p class="larger2 botspace heading"><?php echo $bread_image_name; ?></p>

			<div class="formstyle ">
				<form method="post" name="admin" id="admin" action="technologyinfo_admin.php" enctype='multipart/form-data'>
					<table class="form-table">
						<tr>
							<td class="table-label" align="right">Technology Info Name: </td>
							<td><input class="form midform" type="text" name="technology_info_name" value="<?php echo $technology_info_name ?>" placeholder="Technology Info Name" /></td>
						</tr>
						<tr>
							<td class="table-label" align="right">Technology Info Description: </td>
							<td><textarea class="form midform mceEditor" name="technology_info_description" placeholder="Technology Info Description"><?php echo $technology_info_description; ?></textarea></td>
						</tr>
						<!--
                    	<tr>
                    		<td class="table-label" align="right" valign="top">Button Image: </td>
                    		<td><input type="file" class="slick" size="50" maxlength="200" name="technology_info_button_image_url" id="technology_info_button_image_url" value="<?php echo(htmlspecialchars($technology_info_button_image_url)); ?>"/>
                    		    <?php
                    		    if ($technology_info_button_image_url != '') {
                    		        echo '<br ><strong>Current File:</strong> ' . $technology_info_button_image_url;
                    		    }
                    		    
                    		    // Set up Drop Area
                    		    echo DragAndDrop::CreateArea($aDropFields[0]);
                    		    ?>
                    		</td>
                    	</tr>
                    	-->
						<tr>
							<td class="table-label" align="right">Template: </td>
							<td>
							    <select name="technology_info_template" class="form">
							        <option value=""></option>
							        <option value="standard"<?php if ($technology_info_template == 'standard') { echo ' selected'; } ?>>Standard</option>
							        <!--
							        <option value="sound"<?php if ($technology_info_template == 'sound') { echo ' selected'; } ?>>Sound</option>
							        <option value="lighting"<?php if ($technology_info_template == 'lighting') { echo ' selected'; } ?>>Lighting</option>
							        -->
							    </select>
							</td>
						</tr>
					</table>
				
					<p class="submit"><input type="submit" name="commit" value="Save Technology Info">&nbsp;&nbsp;<input type="submit" name="commit" value="Cancel"></p>
				
                   	<input type="hidden" name="id" value="<?php echo $id; ?>" />
                   	<input type="hidden" name="cat" value="<? echo $_REQUEST['cat']; ?>" />
                   	<input type="hidden" name="tech" value="<?php echo $_REQUEST['tech']; ?>" />

				</form>
			</div>
		</div>

	</div>

<?php
// Set up Drag and Drop javascript controls
echo DragAndDrop::Control($aDropFields, $aDropFieldTypes);
}
?>