<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

require("../inc/classes/CoreCapability.php");
require('../inc/classes/DragAndDrop.php');
require('../inc/classes/Helpers.php');

// page vars
$message = "";
$id = "";
$pwmsg = '';

$id = 1;
$_REQUEST['mode'] = 'e';

// Setup Drag and Drop fields
$aDropFields = array();
$aDropFieldTypes = array();
array_push($aDropFields, 'pdf_file_drop');
array_push($aDropFieldTypes, 'pdf');

// if form was submitted:
if ($_POST['commit'] == "Cancel") {
	header("Location:mainpage.php");
	exit;
}

if ($_POST['commit'] == "Save Core Capability") {
	
	if ($id == 0) {
		// add the listing

        $objCoreCapability = new CoreCapability();
        $objCoreCapability->Create();
		
        $objCoreCapability->HandleFileUploads();
        $objCoreCapability->HandleDropFileUploads($aDropFields[0], 'PdfFile');
		
		// redirect to listing list
		header("Location:mainpage.php");
		exit;
	}
	else {

        $objCoreCapability = new CoreCapability($_REQUEST["id"]);
        $objCoreCapability->Update();
		
        $objCoreCapability->HandleFileUploads();
        $objCoreCapability->HandleDropFileUploads($aDropFields[0], 'PdfFile');
        
		// redirect to listing list
		header("Location:mainpage.php");
		exit;
	}
}
else {
	if ($_REQUEST['mode'] == 'e') {
		//listing
		$objCoreCapability = new CoreCapability($id);
        $pdf_file = $objCoreCapability->PdfFile;
	}
}

include("includes/pagetemplate.php");

function PageContent() {
	global $pdf_file;
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
	            $bread_title = 'Core Capability Add';
	        }
	        else {
	            $bread_title = 'Core Capability Edit';
	        }

		    $aLabels = array();
		    $aLinks = array();
		    $aLabels[0] = 'Home';
		    $aLinks[0] = 'mainpage.php';
		    $aLabels[1] = $bread_title;
		    $aLinks[1] = '';
		    echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
		    ?>

		    <p class="larger botspace heading"><?php echo $bread_title; ?></p>

			<div class="formstyle ">
				<form method="post" action="corecapability_admin.php" enctype='multipart/form-data'>
					<table class="form-table">
                    	<tr>
                    		<td class="table-label" valign="top" align="right"><strong>PDF File: </strong></td>
                    		<td class="field"><input type="file" class="slick" size="50" maxlength="200" name="pdf_file" id="pdf_file" value="<?php echo(htmlspecialchars($pdf_file)); ?>"/>
                    		    <?php
                    		    if ($pdf_file != '') {
                    		        echo '<br /><br /><strong>Current File:</strong> ' . $pdf_file;
                    		    }
                    		    
                    		    // Set up Drop Area
                    		    echo DragAndDrop::CreateArea($aDropFields[0]);
                    		    ?>
                    		</td>
                    	</tr>
					</table>
				
					<p class="submit"><input type="submit" name="commit" value="Save Core Capability">&nbsp;&nbsp;<input type="submit" name="commit" value="Cancel"></p>
				
                   	<input type="hidden" name="id" value="<? echo $id; ?>" />

				</form>
		</div>

	</div>

<?php
// Set up Drag and Drop javascript controls
echo DragAndDrop::Control($aDropFields, $aDropFieldTypes);
}
?>