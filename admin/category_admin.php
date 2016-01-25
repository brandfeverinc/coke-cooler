<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

require("../inc/classes/Category.php");
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
	header("Location:category_list.php");
	exit;
}

if ($_POST['commit'] == "Save Category") {
	
	if ($id == 0) {
		// insert
        $obj = new Category();
        $obj->CategoryName = $_REQUEST["category_name"];
        $obj->CategoryDescription = $_REQUEST["category_description"];
        $obj->BackgroundColor = $_REQUEST["background_color"];
        $obj->CategoryImageUrl = $_REQUEST["category_image_url"];
        $obj->TitleHeading = $_REQUEST["title_heading"];
        $obj->SubtitleHeading = $_REQUEST["subtitle_heading"];
        $obj->ContactEmail = $_REQUEST["contact_email"];
        $is_active = false;
        if (isset($_REQUEST['is_active'])) {
            $is_active = true;
        }
        $obj->IsActive = $is_active;
        $obj->create();
        
        $obj->handleFileUploads();
        $obj->handleDropFileUploads($aDropFields[0], 'CategoryImageUrl');

		// redirect to listing list
		header("Location:category_list.php");
		exit;
	}
	else {
        // update
        $obj = new Category($_REQUEST["id"]);
        $obj->CategoryName = $_REQUEST["category_name"];
        $obj->CategoryDescription = $_REQUEST["category_description"];
        $obj->BackgroundColor = $_REQUEST["background_color"];
        $obj->CategoryImageUrl = $_REQUEST["category_image_url"];
        $obj->TitleHeading = $_REQUEST["title_heading"];
        $obj->SubtitleHeading = $_REQUEST["subtitle_heading"];
        $obj->ContactEmail = $_REQUEST["contact_email"];
        $is_active = false;
        if (isset($_REQUEST['is_active'])) {
            $is_active = true;
        }
        $obj->IsActive = $is_active;
        $obj->update();

        $obj->handleFileUploads();
        $obj->handleDropFileUploads($aDropFields[0], 'CategoryImageUrl');
		
		// redirect to listing list
		header("Location:category_list.php");
		exit;
	}
}
else {
	if ($_REQUEST['mode'] == 'e') {
		//listing
		$cat = new Category($id);
        $category_name = $cat->CategoryName;
        $category_description = $cat->CategoryDescription;
        $background_color = $cat->BackgroundColor;
        $category_image_url = $cat->CategoryImageUrl;
        $title_heading = $cat->TitleHeading;
        $subtitle_heading = $cat->SubtitleHeading;
        $contact_email = $cat->ContactEmail;
        $is_active = $cat->IsActive;
	}
}

include("includes/pagetemplate.php");

function PageContent() {
	global $id;
	global $category_name;
	global $category_description;
	global $background_color;
	global $category_image_url;
	global $title_heading;
	global $subtitle_heading;
	global $contact_email;
	global $is_active;
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
	            $bread_image_name = 'Category Add';
	        }
	        else {
	            $bread_image_name = 'Category Edit';
	        }

		    $aLabels = array();
		    $aLinks = array();
		    $aLabels[0] = 'Home';
		    $aLinks[0] = 'mainpage.php';
		    $aLabels[1] = 'Categories';
		    $aLinks[1] = 'category_list.php';
		    $aLabels[2] = $bread_image_name;
		    $aLinks[2] = '';
		    echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
		    ?>

		    <p class="larger2 botspace heading"><?php echo $bread_image_name; ?></p>

			<div class="formstyle ">
				<form method="post" name="admin" id="admin" action="category_admin.php" enctype='multipart/form-data'>
					<table class="form-table">
						<tr>
							<td class="table-label" align="right">Category Name: </td>
							<td><input class="form midform" type="text" name="category_name" value="<?php echo $category_name ?>" placeholder="Category Name" /></td>
						</tr>
						<tr>
							<td class="table-label" align="right">Title Heading: </td>
							<td><input class="form midform" type="text" name="title_heading" value="<?php echo $title_heading ?>" placeholder="Title Heading" /></td>
						</tr>
						<tr>
							<td class="table-label" align="right">Subtitle Heading: </td>
							<td><input class="form midform" type="text" name="subtitle_heading" value="<?php echo $subtitle_heading ?>" placeholder="Subtitle Heading" /></td>
						</tr>
						<tr>
							<td class="table-label" align="right">Contact Email: </td>
							<td><input class="form midform" type="text" name="contact_email" value="<?php echo $contact_email ?>" placeholder="Contact Email" /></td>
						</tr>
						<tr>
							<td class="table-label" align="right">Category Description: </td>
							<td><textarea class="form midform mceEditor" name="category_description" placeholder="Category Description"><?php echo $category_description; ?></textarea></td>
						</tr>
						<tr>
							<td class="table-label" align="right">Background Color: </td>
							<td><input class="form midform" type="text" name="background_color" value="<?php echo $background_color ?>" placeholder="Background Color" /> Examples: "#F40000", "rgb(255,255,0)"</td>
						</tr>
						<tr>
							<td class="table-label" align="right">Category Image: </td>
							<td><input class="form midform" type="text" name="category_image_url" value="<?php echo $category_image_url; ?>" placeholder="Image File" /></td>
						</tr>
                    	<tr>
                    		<td class="table-label" align="right" valign="top">Image File: </td>
                    		<td><input type="file" class="slick" size="50" maxlength="200" name="image_file" id="image_file" value="<?php echo(htmlspecialchars($category_image_url)); ?>"/>
                    		    <?php
                    		    if ($category_image_url != '') {
                    		        echo '<br ><strong>Current File:</strong> ' . $category_image_url;
                    		    }
                    		    
                    		    // Set up Drop Area
                    		    echo DragAndDrop::CreateArea($aDropFields[0]);
                    		    ?>
                    		</td>
                    	</tr>
						<tr>
							<td class="table-label" align="right">Is Active?: </td>
							<td><input type="checkbox" name="is_active" value="1"<?php if ($is_active) { echo ' checked'; } ?>>
							</td>
						</tr>
					</table>
				
					<p class="submit"><input type="submit" name="commit" value="Save Category">&nbsp;&nbsp;<input type="submit" name="commit" value="Cancel"></p>
				
                   	<input type="hidden" name="id" value="<? echo $id; ?>" />

				</form>
			</div>
		</div>

	</div>

<?php
// Set up Drag and Drop javascript controls
echo DragAndDrop::Control($aDropFields, $aDropFieldTypes);
}
?>