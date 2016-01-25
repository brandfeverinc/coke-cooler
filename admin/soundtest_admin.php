<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

require("../inc/classes/SoundTest.php");
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
array_push($aDropFields, 'image_url1_drop');
array_push($aDropFieldTypes, 'image');
array_push($aDropFields, 'image_url2_drop');
array_push($aDropFieldTypes, 'image');
array_push($aDropFields, 'image_url3_drop');
array_push($aDropFieldTypes, 'image');

// if form was submitted:
if ($_POST['commit'] == "Cancel") {
	header("Location:soundtest_list.php?cat=" . $_REQUEST['cat'] . "&sound=" . $_REQUEST['sound']);
	exit;
}

if ($_POST['commit'] == "Save Sound Test") {
	
	if ($id == 0) {
		// insert
        $obj = new SoundTest();
        $obj->SoundId = $_REQUEST["sound"];
        $obj->SoundTestDescription = $_REQUEST["sound_test_description"];
        $obj->SoundUrl1 = $_REQUEST["sound_url1"];
        $obj->SoundUrl2 = $_REQUEST["sound_url2"];
        $obj->SoundUrl3 = $_REQUEST["sound_url3"];
        $obj->Text1 = $_REQUEST["text1"];
        $obj->Text2 = $_REQUEST["text2"];
        $obj->Text3 = $_REQUEST["text3"];
        $obj->create();
        
        $obj->handleFileUploads();
        $obj->handleDropFileUploads($aDropFields[0], 'ImageUrl1');
        $obj->handleDropFileUploads($aDropFields[1], 'ImageUrl2');
        $obj->handleDropFileUploads($aDropFields[2], 'ImageUrl3');

		// redirect to listing list
    	header("Location:soundtest_list.php?cat=" . $_REQUEST['cat'] . "&sound=" . $_REQUEST['sound']);
		exit;
	}
	else {
        // update
        $obj = new SoundTest($id);
        $obj->SoundTestDescription = $_REQUEST["sound_test_description"];
        $obj->SoundUrl1 = $_REQUEST["sound_url1"];
        $obj->SoundUrl2 = $_REQUEST["sound_url2"];
        $obj->SoundUrl3 = $_REQUEST["sound_url3"];
        $obj->Text1 = $_REQUEST["text1"];
        $obj->Text2 = $_REQUEST["text2"];
        $obj->Text3 = $_REQUEST["text3"];
        $obj->update();

        $obj->handleFileUploads();
        $obj->handleDropFileUploads($aDropFields[0], 'ImageUrl1');
        $obj->handleDropFileUploads($aDropFields[1], 'ImageUrl2');
        $obj->handleDropFileUploads($aDropFields[2], 'ImageUrl3');
		
		// redirect to listing list
    	header("Location:soundtest_list.php?cat=" . $_REQUEST['cat'] . "&sound=" . $_REQUEST['sound']);
		exit;
	}
}
else {
	if ($_REQUEST['mode'] == 'e') {
		//listing
        $objSoundTest = new SoundTest($id);
        $sound_id = $objSoundTest->SoundId;
        $sound_test_description = $objSoundTest->SoundTestDescription;
        $sound_url1 = $objSoundTest->SoundUrl1;
        $sound_url2 = $objSoundTest->SoundUrl2;
        $sound_url3 = $objSoundTest->SoundUrl3;
        $image_url1 = $objSoundTest->ImageUrl1;
        $image_url2 = $objSoundTest->ImageUrl2;
        $image_url3 = $objSoundTest->ImageUrl3;
        $text1 = $objSoundTest->Text1;
        $text2 = $objSoundTest->Text2;
        $text3 = $objSoundTest->Text3;        
        $path = $objSoundTest->GetPath();
	}
	else if ($_REQUEST['mode'] == 'a') {
        $sound_id = $_REQUEST["sound"];
	}
}

include("includes/pagetemplate.php");

function PageContent() {
	global $id;
	global $sound_id;
    global $sound_test_description;
    global $sound_url1;
    global $sound_url2;
    global $sound_url3;
    global $image_url1;
    global $image_url2;
    global $image_url3;
    global $text1;
    global $text2;
    global $text3;
	global $path;
    global $aDropFields;
    global $aDropFieldTypes;
?>

    <?php
    // Set up Drag And Drop styles for the drop area
    echo DragAndDrop::SetStyles();
    ?>

	<script type="text/javascript" src="tinymce/js/tinymce/tinymce.min.js"></script>
	<script type="text/javascript">
	tinyMCE.init({
	    selector : "textarea.mceEditor",
	    plugins : "image code pagebreak table spellchecker preview searchreplace print contextmenu paste fullscreen nonbreaking",
	    image_advtab : true,
	    forced_root_block : "",
	    setup: function (editor) {
	        editor.on('init', function(args) {
	            editor = args.target;
	
	            editor.on('NodeChange', function(e) {
	                if (e && e.element.nodeName.toLowerCase() == 'img') {
	//                     width = e.element.width;
	//                     height = e.element.height;
	                    tinyMCE.DOM.setAttribs(e.element, {'class':'img_responsive', 'height' : null, 'width' : null});
	                    tinyMCE.DOM.setStyle(e.element, 'max-width', '100%');
	                }
	            });
	        });
	    },
		content_css : ["../css/bootstrip.min.css"],
	    //document_base_url : "http://havells.boxkitemedia.com"
	});
	</script>

	<div id="content">

		<div class="layout">
	        <?php
	        if ($id == 0) {
	            $bread_image_name = 'Sound Test Add';
	        }
	        else {
	            $bread_image_name = 'Sound Test Edit';
	        }

		    $aLabels = array();
		    $aLinks = array();
                $aLabels[0] = 'Home';
                $aLinks[0] = 'mainpage.php';
                $aLabels[1] = 'Category List';
                $aLinks[1] = 'category_list.php';
                $aLabels[2] = 'Sound List';
                $aLinks[2] = 'sound_list.php';
                $aLabels[3] = 'Sound Tests';
                $aLinks[3] = 'soundtest_list.php?cat=' . $_REQUEST['cat'] . '&sound=' . $_REQUEST['sound'];
                $aLabels[4] = $bread_image_name;
                $aLinks[4] = '';
		    echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
		    ?>

		    <p class="larger2 botspace heading"><?php echo $bread_image_name; ?></p>

			<div class="formstyle ">
				<form method="post" name="admin" id="admin" action="soundtest_admin.php" enctype='multipart/form-data'>
					<table class="form-table">
						<tr>
							<td class="table-label" align="right">Sound Test Description: </td>
							<td><textarea class="form midform mceEditor" name="sound_test_description" placeholder="Sound Test Description"><?php echo $sound_test_description; ?></textarea></td>
						</tr>
						<tr>
							<td class="table-label" align="right">Sound 1 Text: </td>
							<td><input class="form midform" type="text" name="text1" style="width: 50%;" value="<?php echo $text1 ?>" placeholder="Sound 1 Text" /></td>
						</tr>
                    	<tr>
                    		<td class="table-label" align="right" valign="top">Image File 1: </td>
                    		<td><input type="file" class="slick" size="50" maxlength="200" name="image_url1" id="image_url1" value="<?php echo(htmlspecialchars($image_url1)); ?>"/>
                    		    <?php
                    		    if ($image_url1 != '') {
                    		        echo '<br ><strong>Current File:</strong> ' . $image_url1;
                    		    }
                    		    
                    		    // Set up Drop Area
                    		    echo DragAndDrop::CreateArea($aDropFields[0]);
                    		    ?>
                    		</td>
                    	</tr>
						<tr>
							<td class="table-label" align="right">Sound 1 Filename: </td>
							<td><input class="form midform" type="text" name="sound_url1" style="width: 90%;" value="<?php echo $sound_url1 ?>" placeholder="Sound 1 Filename" /></td>
						</tr>
						<tr>
							<td class="table-label" align="right">Sound 2 Text: </td>
							<td><input class="form midform" type="text" name="text2" style="width: 50%;" value="<?php echo $text2 ?>" placeholder="Sound 2 Text" /></td>
						</tr>
                    	<tr>
                    		<td class="table-label" align="right" valign="top">Image File 2: </td>
                    		<td><input type="file" class="slick" size="50" maxlength="200" name="image_url2" id="image_url2" value="<?php echo(htmlspecialchars($image_url2)); ?>"/>
                    		    <?php
                    		    if ($image_url2 != '') {
                    		        echo '<br ><strong>Current File:</strong> ' . $image_url2;
                    		    }
                    		    
                    		    // Set up Drop Area
                    		    echo DragAndDrop::CreateArea($aDropFields[1]);
                    		    ?>
                    		</td>
                    	</tr>
						<tr>
							<td class="table-label" align="right">Sound 2 Filename: </td>
							<td><input class="form midform" type="text" name="sound_url2" style="width: 90%;" value="<?php echo $sound_url2 ?>" placeholder="Sound 2 Filename" /></td>
						</tr>
						<tr>
							<td class="table-label" align="right">Sound 3 Text: </td>
							<td><input class="form midform" type="text" name="text3" style="width: 50%;" value="<?php echo $text3 ?>" placeholder="Sound 3 Text" /></td>
						</tr>
                    	<tr>
                    		<td class="table-label" align="right" valign="top">Image File 3: </td>
                    		<td><input type="file" class="slick" size="50" maxlength="200" name="image_url3" id="image_url3" value="<?php echo(htmlspecialchars($image_url3)); ?>"/>
                    		    <?php
                    		    if ($image_url3 != '') {
                    		        echo '<br ><strong>Current File:</strong> ' . $image_url3;
                    		    }
                    		    
                    		    // Set up Drop Area
                    		    echo DragAndDrop::CreateArea($aDropFields[2]);
                    		    ?>
                    		</td>
                    	</tr>
						<tr>
							<td class="table-label" align="right">Sound 3 Filename: </td>
							<td><input class="form midform" type="text" name="sound_url3" style="width: 90%;" value="<?php echo $sound_url3 ?>" placeholder="Sound 3 Filename" /></td>
						</tr>
					</table>
				
					<p class="submit"><input type="submit" name="commit" value="Save Sound Test">&nbsp;&nbsp;<input type="submit" name="commit" value="Cancel"></p>
				
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