<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

require("../inc/classes/Leadership.php");
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
	header("Location:leadership_list.php");
	exit;
}

if ($_POST['commit'] == "Save Leadership") {
	
	if ($id == 0) {
		// add the listing

        $objLeadership = new Leadership();
        $objLeadership->LeaderName = $_REQUEST["leader_name"];
        $objLeadership->LeaderTitle = $_REQUEST["leader_title"];
        $objLeadership->LinkedIn = $_REQUEST["linked_in"];
        $objLeadership->Create();
		
        $objLeadership->HandleFileUploads();
        $objLeadership->HandleDropFileUploads($aDropFields[0], 'ImageFile');
		
		// redirect to listing list
		header("Location:leadership_list.php");
		exit;
	}
	else {

        $objLeadership = new Leadership($_REQUEST["id"]);
        $objLeadership->LeaderName = $_REQUEST["leader_name"];
        $objLeadership->LeaderTitle = $_REQUEST["leader_title"];
        $objLeadership->LinkedIn = $_REQUEST["linked_in"];
        $objLeadership->Update();
		
        $objLeadership->HandleFileUploads();
        $objLeadership->HandleDropFileUploads($aDropFields[0], 'ImageFile');
        
		// redirect to listing list
		header("Location:leadership_list.php");
		exit;
	}
}
else {
	if ($_REQUEST['mode'] == 'e') {
		//listing
		$objLeadership = new Leadership($id);
        $leader_name = $objLeadership->LeaderName;
        $leader_title = $objLeadership->LeaderTitle;
        $linked_in = $objLeadership->LinkedIn;
        $image_file = $objLeadership->ImageFile;
	}
}

include("includes/pagetemplate.php");

function PageContent() {
	global $leader_name;
	global $leader_title;
	global $linked_in;
	global $image_file;
	global $id;
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
	            $bread_title = 'Leadership Add';
	        }
	        else {
	            $bread_title = 'Leadership Edit';
	        }

		    $aLabels = array();
		    $aLinks = array();
		    $aLabels[0] = 'Home';
		    $aLinks[0] = 'mainpage.php';
		    $aLabels[1] = 'Leadership';
		    $aLinks[1] = 'leadership_list.php';
		    $aLabels[2] = $bread_title;
		    $aLinks[2] = '';
		    echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
		    ?>

		    <p class="larger botspace heading"><?php echo $bread_title; ?></p>

			<div class="formstyle ">
				<form method="post" action="leadership_admin.php" enctype='multipart/form-data'>
					<table class="form-table">
						<tr>
							<td class="table-label" align="right">Leader Name: </td>
							<td><input class="form midform" type="text" name="leader_name" value="<?php echo $leader_name; ?>" placeholder="Leader Name"></td>
						</tr>
						<tr>
							<td class="table-label" align="right">Leader Title: </td>
							<td><input class="form midform" type="text" name="leader_title" value="<?php echo $leader_title; ?>" placeholder="Leader Title"></td>
						</tr>
						<tr>
							<td class="table-label" align="right">Linked In URL: </td>
							<td><input class="form midform" type="text" name="linked_in" value="<?php echo $linked_in; ?>" placeholder="Linked In URL"></td>
						</tr>
                    	<tr>
                    		<td class="table-label" valign="top" align="right"><strong>Image File: </strong></td>
                    		<td class="field"><input type="file" class="slick" size="50" maxlength="200" name="image_file" id="image_file" value="<?php echo(htmlspecialchars($image_file)); ?>"/>
                    		    <?php
                    		    if ($image_file != '') {
                    		        echo '<br /><br /><strong>Current File:</strong> ' . $image_file;
                    		    }
                    		    
                    		    // Set up Drop Area
                    		    echo DragAndDrop::CreateArea($aDropFields[0]);
                    		    ?>
                    		</td>
                    	</tr>
					</table>
				
					<p class="submit"><input type="submit" name="commit" value="Save Leadership">&nbsp;&nbsp;<input type="submit" name="commit" value="Cancel"></p>
				
                   	<input type="hidden" name="id" value="<? echo $id; ?>" />

				</form>
		</div>

	</div>

<?php
// Set up Drag and Drop javascript controls
echo DragAndDrop::Control($aDropFields, $aDropFieldTypes);
}
?>