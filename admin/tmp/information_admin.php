<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

require("../inc/classes/Information.php");
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
array_push($aDropFields, 'pdf_file_drop');
array_push($aDropFieldTypes, 'pdf');

// if form was submitted:
if ($_POST['commit'] == "Cancel") {
	header("Location:information_list.php");
	exit;
}

if ($_POST['commit'] == "Save Information") {
	
	if ($id == 0) {
		// add the listing

        $objInformation = new Information();
		$objInformation->Title = $_REQUEST['title'];
        $objInformation->Create();
        
        $objInformation->HandleFileUploads();
        $objInformation->HandleDropFileUploads($aDropFields[0], 'PdfFile');
        
		// redirect to listing list
		header("Location:information_list.php");
		exit;
	}
	else {

        $objInformation = new Information($_REQUEST["id"]);
		$objInformation->Title = $_REQUEST['title'];
        $objInformation->Update();
		
        $objInformation->HandleFileUploads();
        $objInformation->HandleDropFileUploads($aDropFields[0], 'PdfFile');

		// redirect to listing list
		header("Location:information_list.php");
		exit;
	}
}
else {
	if ($_REQUEST['mode'] == 'e') {
		//listing
		$objInformation = new Information($id);
		$title = $objInformation->Title;
		$pdf_file = $objInformation->PdfFile;
	}
}

include("includes/pagetemplate.php");

function PageContent() {
	global $id;
	global $title;
	global $pdf_file;
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
	            $bread_title = 'Information Add';
	        }
	        else {
	            $bread_title = 'Information Edit';
	        }

		    $aLabels = array();
		    $aLinks = array();
		    $aLabels[0] = 'Home';
		    $aLinks[0] = 'mainpage.php';
		    $aLabels[1] = 'Information';
		    $aLinks[1] = 'information_list.php';
		    $aLabels[2] = $bread_title;
		    $aLinks[2] = '';
		    echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
		    ?>

		    <p class="larger2 botspace heading"><?php echo $bread_title; ?></p>

			<div class="formstyle ">
				<form method="post" name="admin" id="admin" action="information_admin.php" enctype='multipart/form-data'>
					<table class="form-table">
						<tr>
							<td class="table-label" align="right">Title: </td>
							<td><input class="form midform" type="text" name="title" value="<?php echo $title ?>" placeholder="Title" /></td>
						</tr>
                    	<tr>
                    		<td class="table-label" align="right" valign="top">PDF File: </td>
                    		<td><input type="file" class="slick" size="50" maxlength="200" name="pdf_file" id="pdf_file" value="<?php echo(htmlspecialchars($pdf_file)); ?>"/>
                    		    <?php
                    		    if ($pdf_file != '') {
                    		        echo '<br ><strong>Current File:</strong> ' . $pdf_file;
                    		    }
                    		    
                    		    // Set up Drop Area
                    		    echo DragAndDrop::CreateArea($aDropFields[0]);
                    		    ?>
                    		</td>
                    	</tr>
					</table>
				
					<p class="submit"><input type="submit" name="commit" value="Save Information">&nbsp;&nbsp;<input type="submit" name="commit" value="Cancel"></p>
				
                   	<input type="hidden" name="id" value="<? echo $id; ?>" />

				</form>
		</div>

	</div>

<?php
// Set up Drag and Drop javascript controls
echo DragAndDrop::Control($aDropFields, $aDropFieldTypes);
}
?>