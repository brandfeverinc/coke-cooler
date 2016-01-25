<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

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
array_push($aDropFields, 'technology_button_image_url_drop');
array_push($aDropFieldTypes, 'image');
array_push($aDropFields, 'technology_button_active_image_url_drop');
array_push($aDropFieldTypes, 'image');

// if form was submitted:
if ($_POST['commit'] == "Cancel") {
	header("Location:technology_list.php?cat=" . $_REQUEST['cat']);
	exit;
}

if ($_POST['commit'] == "Save Technology") {
	
	if ($id == 0) {
		// insert
        $obj = new Technology();
        $obj->CategoryId = $_REQUEST["cat"];
        $obj->TechnologyName = $_REQUEST["technology_name"];
        $obj->TechnologyHeadline = $_REQUEST["technology_headline"];
        $obj->LinkUrl = 'tech-standard.php';
        $obj->create();
        
        $obj->handleFileUploads();
        $obj->handleDropFileUploads($aDropFields[0], 'TechnologyButtonImageUrl');
        $obj->handleDropFileUploads($aDropFields[1], 'TechnologyButtonActiveImageUrl');

		// redirect to listing list
		header("Location:technology_list.php?cat=" . $_REQUEST['cat']);
		exit;
	}
	else {
        // update
        $obj = new Technology($_REQUEST["id"]);
        $obj->TechnologyName = $_REQUEST["technology_name"];
        $obj->TechnologyHeadline = $_REQUEST["technology_headline"];
        $obj->update();

        $obj->handleFileUploads();
        $obj->handleDropFileUploads($aDropFields[0], 'TechnologyButtonImageUrl');
        $obj->handleDropFileUploads($aDropFields[1], 'TechnologyButtonActiveImageUrl');
		
		// redirect to listing list
		header("Location:technology_list.php?cat=" . $_REQUEST['cat']);
		exit;
	}
}
else {
	if ($_REQUEST['mode'] == 'e') {
		//listing
		$objTechnology = new Technology($id);
        $technology_name = $objTechnology->TechnologyName;
        $technology_headline = $objTechnology->TechnologyHeadline;
        $technology_button_image_url = $objTechnology->TechnologyButtonImageUrl;
        $technology_button_active_image_url = $objTechnology->TechnologyButtonActiveImageUrl;
	}
}

include("includes/pagetemplate.php");

function PageContent() {
	global $id;
	global $technology_name;
	global $technology_headline;
	global $technology_button_image_url;
	global $technology_button_active_image_url;
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
	            $bread_image_name = ' Technology Add';
	        }
	        else {
	            $bread_image_name = ' Technology Edit';
	        }

		    $aLabels = array();
		    $aLinks = array();
                $aLabels[0] = 'Home';
                $aLinks[0] = 'mainpage.php';
                $aLabels[1] = 'Category List';
                $aLinks[1] = 'category_list.php';
                $aLabels[2] = 'Technology';
                $aLinks[2] = 'technology_list.php?cat=' . $_REQUEST['cat'];
                $aLabels[3] = $bread_image_name;
                $aLinks[3] = '';
		    echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
		    ?>

		    <p class="larger2 botspace heading"><?php echo $bread_image_name; ?></p>

			<div class="formstyle ">
				<form method="post" name="admin" id="admin" action="technology_admin.php" enctype='multipart/form-data'>
					<table class="form-table">
						<tr>
							<td class="table-label" align="right">Technology Name: </td>
							<td><input class="form midform" type="text" name="technology_name" value="<?php echo $technology_name ?>" placeholder="Technology Name" /></td>
						</tr>
						<tr>
							<td class="table-label" align="right">Technology Headline: </td>
							<td><input class="form midform" type="text" name="technology_headline" value="<?php echo $technology_headline ?>" placeholder="Technology Headline" /></td>
						</tr>
                    	<tr>
                    		<td class="table-label" align="right" valign="top">Icon File: </td>
                    		<td><input type="file" class="slick" size="50" maxlength="200" name="technology_button_image_url" id="technology_button_image_url" value="<?php echo(htmlspecialchars($technology_button_image_url)); ?>"/>
                    		    <?php
                    		    if ($technology_button_image_url != '') {
                    		        echo '<br ><strong>Current File:</strong> ' . $technology_button_image_url;
                    		    }
                    		    
                    		    // Set up Drop Area
                    		    echo DragAndDrop::CreateArea($aDropFields[0]);
                    		    ?>
                    		</td>
                    	</tr>
                    	<tr>
                    		<td class="table-label" align="right" valign="top">Icon Active File: </td>
                    		<td><input type="file" class="slick" size="50" maxlength="200" name="technology_button_active_image_url" id="technology_button_active_image_url" value="<?php echo(htmlspecialchars($technology_button_active_image_url)); ?>"/>
                    		    <?php
                    		    if ($technology_button_active_image_url != '') {
                    		        echo '<br ><strong>Current File:</strong> ' . $technology_button_active_image_url;
                    		    }
                    		    
                    		    // Set up Drop Area
                    		    echo DragAndDrop::CreateArea($aDropFields[1]);
                    		    ?>
                    		</td>
                    	</tr>
					</table>
				
					<p class="submit"><input type="submit" name="commit" value="Save Technology">&nbsp;&nbsp;<input type="submit" name="commit" value="Cancel"></p>
				
                   	<input type="hidden" name="id" value="<? echo $id; ?>" />
                   	<input type="hidden" name="cat" value="<? echo $_REQUEST['cat']; ?>" />

				</form>
			</div>
		</div>

	</div>

<?php
// Set up Drag and Drop javascript controls
echo DragAndDrop::Control($aDropFields, $aDropFieldTypes);
}
?>