<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

require("../inc/classes/Tool.php");
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
	header("Location:tool_list.php");
	exit;
}

if ($_POST['commit'] == "Save Tool") {
	
	if ($id == 0) {
		// add the listing

        $objTool = new Tool();
		$objTool->Title = $_REQUEST['title'];
        $objTool->Create();
        
        $objTool->HandleFileUploads();
        $objTool->HandleDropFileUploads($aDropFields[0], 'PdfFile');
        
		// redirect to listing list
		header("Location:tool_list.php");
		exit;
	}
	else {

        $objTool = new Tool($_REQUEST["id"]);
		$objTool->Title = $_REQUEST['title'];
        $objTool->Update();
		
        $objTool->HandleFileUploads();
        $objTool->HandleDropFileUploads($aDropFields[0], 'PdfFile');

		// redirect to listing list
		header("Location:tool_list.php");
		exit;
	}
}
else {
	if ($_REQUEST['mode'] == 'e') {
		//listing
		$objTool = new Tool($id);
		$title = $objTool->Title;
		$pdf_file = $objTool->PdfFile;
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
	            $bread_title = 'Tool Add';
	        }
	        else {
	            $bread_title = 'Tool Edit';
	        }

		    $aLabels = array();
		    $aLinks = array();
		    $aLabels[0] = 'Home';
		    $aLinks[0] = 'mainpage.php';
		    $aLabels[1] = 'Tools';
		    $aLinks[1] = 'tool_list.php';
		    $aLabels[2] = $bread_title;
		    $aLinks[2] = '';
		    echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
		    ?>

		    <p class="larger2 botspace heading"><?php echo $bread_title; ?></p>

			<div class="formstyle ">
				<form method="post" name="admin" id="admin" action="tool_admin.php" enctype='multipart/form-data'>
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
				
					<p class="submit"><input type="submit" name="commit" value="Save Tool">&nbsp;&nbsp;<input type="submit" name="commit" value="Cancel"></p>
				
                   	<input type="hidden" name="id" value="<? echo $id; ?>" />

				</form>
		</div>

	</div>

<?php
// Set up Drag and Drop javascript controls
echo DragAndDrop::Control($aDropFields, $aDropFieldTypes);
}
?>