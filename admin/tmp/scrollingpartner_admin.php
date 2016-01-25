<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

require("../inc/classes/ScrollingPartner.php");
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
	header("Location:scrollingpartner_list.php");
	exit;
}

if ($_POST['commit'] == "Save Scrolling Partner") {
	
	if ($id == 0) {
		// add the listing

        $objScrollingPartner = new ScrollingPartner();
        $objScrollingPartner->PartnerName = $_REQUEST["partner_name"];
        $objScrollingPartner->LinkUrl = $_REQUEST["link_url"];
        $objScrollingPartner->Create();
		
        $objScrollingPartner->HandleFileUploads();
        $objScrollingPartner->HandleDropFileUploads($aDropFields[0], 'ImageFile');
		
		// redirect to listing list
		header("Location:scrollingpartner_list.php");
		exit;
	}
	else {

        $objScrollingPartner = new ScrollingPartner($_REQUEST["id"]);
        $objScrollingPartner->PartnerName = $_REQUEST["partner_name"];
        $objScrollingPartner->LinkUrl = $_REQUEST["link_url"];
        $objScrollingPartner->Update();
		
        $objScrollingPartner->HandleFileUploads();
        $objScrollingPartner->HandleDropFileUploads($aDropFields[0], 'ImageFile');
        
		// redirect to listing list
		header("Location:scrollingpartner_list.php");
		exit;
	}
}
else {
	if ($_REQUEST['mode'] == 'e') {
		//listing
		$objScrollingPartner = new ScrollingPartner($id);
        $partner_name = $objScrollingPartner->PartnerName;
        $link_url = $objScrollingPartner->LinkUrl;
        $image_file = $objScrollingPartner->ImageFile;
	}
}

include("includes/pagetemplate.php");

function PageContent() {
	global $partner_name;
	global $link_url;
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
	            $bread_title = 'Scrolling Partner Add';
	        }
	        else {
	            $bread_title = 'Scrolling Partner Edit';
	        }

		    $aLabels = array();
		    $aLinks = array();
		    $aLabels[0] = 'Home';
		    $aLinks[0] = 'mainpage.php';
		    $aLabels[1] = 'Scrolling Partner';
		    $aLinks[1] = 'scrollingpartner_list.php';
		    $aLabels[2] = $bread_title;
		    $aLinks[2] = '';
		    echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
		    ?>

		    <p class="larger botspace heading"><?php echo $bread_title; ?></p>

			<div class="formstyle ">
				<form method="post" action="scrollingpartner_admin.php" enctype='multipart/form-data'>
					<table class="form-table">
						<tr>
							<td class="table-label" align="right">Partner Name: </td>
							<td><input class="form midform" type="text" name="partner_name" value="<?php echo $partner_name; ?>" placeholder="Partner Name"></td>
						</tr>
						<tr>
							<td class="table-label" align="right">URL: </td>
							<td><input class="form midform" type="text" name="link_url" value="<?php echo $link_url; ?>" placeholder="URL"></td>
						</tr>
                    	<tr>
                    		<td class="table-label" valign="top" align="right"><strong>Image File: </strong></td>
                    		<td class="field"><input type="file" class="slick" size="50" maxlength="200" name="image_file" id="image_file" value="<?php echo(htmlspecialchars($image_file)); ?>"/> <em>(Should be 56 pixels high)</em>
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
				
					<p class="submit"><input type="submit" name="commit" value="Save Scrolling Partner">&nbsp;&nbsp;<input type="submit" name="commit" value="Cancel"></p>
				
                   	<input type="hidden" name="id" value="<? echo $id; ?>" />

				</form>
		</div>

	</div>

<?php
// Set up Drag and Drop javascript controls
echo DragAndDrop::Control($aDropFields, $aDropFieldTypes);
}
?>