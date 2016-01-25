<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

require("../inc/classes/LightTest.php");
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
array_push($aDropFields, 'image_file_dark_drop');
array_push($aDropFieldTypes, 'image');
array_push($aDropFields, 'image_file_light_drop');
array_push($aDropFieldTypes, 'image');
array_push($aDropFields, 'background_image_file_drop');
array_push($aDropFieldTypes, 'image');

// if form was submitted:
if ($_POST['commit'] == "Cancel") {
	header("Location:lighttest_list.php?cat=" . $_REQUEST['cat'] . "&light=" . $_REQUEST['light']);
	exit;
}

if ($_POST['commit'] == "Save Light Test") {
	
	if ($id == 0) {
		// insert
        $obj = new LightTest();
        $obj->LightId = $_REQUEST["light"];
        $obj->DarkText = $_REQUEST["dark_text"];
        $obj->LightText = $_REQUEST["light_text"];
        $obj->RgbaValue = $_REQUEST["rgba_value"];
        $obj->RgbaValueRight = $_REQUEST["rgba_value_right"];
        $obj->create();
        
        $obj->handleFileUploads();
        $obj->handleDropFileUploads($aDropFields[0], 'ImageFileDark');
        $obj->handleDropFileUploads($aDropFields[1], 'ImageFileLight');
        $obj->handleDropFileUploads($aDropFields[2], 'BackgroundImageFile');

		// redirect to listing list
    	header("Location:lighttest_list.php?cat=" . $_REQUEST['cat'] . "&light=" . $_REQUEST['light']);
		exit;
	}
	else {
        // update
        $obj = new LightTest($id);
        $obj->DarkText = $_REQUEST["dark_text"];
        $obj->LightText = $_REQUEST["light_text"];
        $obj->RgbaValue = $_REQUEST["rgba_value"];
        $obj->RgbaValueRight = $_REQUEST["rgba_value_right"];
        $obj->update();

        $obj->handleFileUploads();
        $obj->handleDropFileUploads($aDropFields[0], 'ImageFileDark');
        $obj->handleDropFileUploads($aDropFields[1], 'ImageFileLight');
        $obj->handleDropFileUploads($aDropFields[2], 'BackgroundImageFile');
		
		// redirect to listing list
    	header("Location:lighttest_list.php?cat=" . $_REQUEST['cat'] . "&light=" . $_REQUEST['light']);
		exit;
	}
}
else {
	if ($_REQUEST['mode'] == 'e') {
		//listing
        $objLightTest = new LightTest($id);
        $light_id = $objLightTest->LightId;
        $dark_text = $objLightTest->DarkText;
        $light_text = $objLightTest->LightText;
        $rgba_value = $objLightTest->RgbaValue;
        $rgba_value_right = $objLightTest->RgbaValueRight;
        $image_file_dark = $objLightTest->ImageFileDark;
        $image_file_light = $objLightTest->ImageFileLight;
        $background_image_file = $objLightTest->BackgroundImageFile;
        $path = $objLightTest->GetPath();
	}
	else if ($_REQUEST['mode'] == 'a') {
        $light_id = $_REQUEST["light"];
	}
}

include("includes/pagetemplate.php");

function PageContent() {
	global $id;
	global $light_id;
	global $dark_text;
	global $light_text;
	global $rgba_value;
	global $rgba_value_right;
	global $image_file_dark;
	global $image_file_light;
	global $background_image_file;
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
	            $bread_image_name = 'Light Test Add';
	        }
	        else {
	            $bread_image_name = 'Light Test Edit';
	        }

		    $aLabels = array();
		    $aLinks = array();
                $aLabels[0] = 'Home';
                $aLinks[0] = 'mainpage.php';
                $aLabels[1] = 'Category List';
                $aLinks[1] = 'category_list.php';
                $aLabels[2] = 'Light List';
                $aLinks[2] = 'light_list.php';
                $aLabels[3] = 'Light Tests';
                $aLinks[3] = 'lighttest_list.php?cat=' . $_REQUEST['cat'] . '&light=' . $_REQUEST['light'];
                $aLabels[4] = $bread_image_name;
                $aLinks[4] = '';
		    echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
		    ?>

		    <p class="larger2 botspace heading"><?php echo $bread_image_name; ?></p>

			<div class="formstyle ">
				<form method="post" name="admin" id="admin" action="lighttest_admin.php" enctype='multipart/form-data'>
					<table class="form-table">
                    	<tr>
                    		<td class="table-label" align="right" valign="top">Dark Image File: </td>
                    		<td><input type="file" class="slick" size="50" maxlength="200" name="image_file_dark" id="image_file_dark" value="<?php echo(htmlspecialchars($image_file_dark)); ?>"/>
                    		    <?php
                    		    if ($image_file_dark != '') {
                    		        echo '<br ><strong>Current File:</strong> ' . $image_file_dark;
                    		    }
                    		    
                    		    // Set up Drop Area
                    		    echo DragAndDrop::CreateArea($aDropFields[0]);
                    		    ?>
                    		</td>
                    	</tr>
						<tr>
							<td class="table-label" align="right">Dark Image Text: </td>
							<td><input class="form midform" type="text" name="dark_text" style="width: 90%;" value="<?php echo $dark_text ?>" placeholder="Dark Image Text" /></td>
						</tr>
                    	<tr>
                    		<td class="table-label" align="right" valign="top">Light Image File: </td>
                    		<td><input type="file" class="slick" size="50" maxlength="200" name="image_file_light" id="image_file_light" value="<?php echo(htmlspecialchars($image_file_light)); ?>"/>
                    		    <?php
                    		    if ($image_file_light != '') {
                    		        echo '<br ><strong>Current File:</strong> ' . $image_file_light;
                    		    }
                    		    
                    		    // Set up Drop Area
                    		    echo DragAndDrop::CreateArea($aDropFields[1]);
                    		    ?>
                    		</td>
                    	</tr>
						<tr>
							<td class="table-label" align="right">Light Image Text: </td>
							<td><input class="form midform" type="text" name="light_text" style="width: 90%;" value="<?php echo $light_text ?>" placeholder="Light Image Text" /></td>
						</tr>
                    	<tr>
                    		<td class="table-label" align="right" valign="top">Test Image: </td>
                    		<td><input type="file" class="slick" size="50" maxlength="200" name="background_image_file" id="background_image_file" value="<?php echo(htmlspecialchars($background_image_file)); ?>"/>
                    		    <?php
                    		    if ($background_image_file != '') {
                    		        echo '<br ><strong>Current File:</strong> ' . $background_image_file;
                    		    }
                    		    
                    		    // Set up Drop Area
                    		    echo DragAndDrop::CreateArea($aDropFields[2]);
                    		    ?>
                    		</td>
                    	</tr>
						<tr>
							<td class="table-label" align="right">RGBA Value (Left): </td>
							<td><input class="form midform" type="text" name="rgba_value" style="width: 90%;" value="<?php echo $rgba_value ?>" placeholder="RGBA Value (Left)" /></td>
						</tr>
						<tr>
							<td class="table-label" align="right">RGBA Value (Right): </td>
							<td><input class="form midform" type="text" name="rgba_value_right" style="width: 90%;" value="<?php echo $rgba_value_right ?>" placeholder="RGBA Value (Right)" /></td>
						</tr>
					</table>
				
					<p class="submit"><input type="submit" name="commit" value="Save Light Test">&nbsp;&nbsp;<input type="submit" name="commit" value="Cancel"></p>
				
                   	<input type="hidden" name="id" value="<?php echo $id; ?>" />
                  	<input type="hidden" name="cat" value="<? echo $cat; ?>" />
                   	<input type="hidden" name="light" value="<?php echo $_REQUEST['light']; ?>" />

				</form>
			</div>
		</div>

	</div>

<?php
// Set up Drag and Drop javascript controls
echo DragAndDrop::Control($aDropFields, $aDropFieldTypes);
}
?>