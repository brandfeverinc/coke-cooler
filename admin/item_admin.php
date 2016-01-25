<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

require("../inc/classes/Item.php");
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
	header("Location:item_list.php?cat=" . $_REQUEST['category_id']);
	exit;
}

$category = new Category($_REQUEST['cat']);

if ($_POST['commit'] == "Save Item") {
	
	if ($id == 0) {
		// insert
        $obj = new Item();
        $obj->CategoryId = $_REQUEST["category_id"];
        $obj->ItemName = $_REQUEST["item_name"];
        $obj->BackgroundColor = $_REQUEST["background_color"];
        $obj->ContactEmail = $_REQUEST["contact_email"];
        $obj->GalleryDescription = $_REQUEST["gallery_description"];
        $obj->create();
        
		// redirect to listing list
		header("Location:item_list.php?cat=" . $_REQUEST['category_id']);
		exit;
	}
	else {
        // update
        $obj = new Item($_REQUEST["id"]);
        $obj->CategoryId = $_REQUEST["category_id"];
        $obj->ItemName = $_REQUEST["item_name"];
        $obj->BackgroundColor = $_REQUEST["background_color"];
        $obj->ContactEmail = $_REQUEST["contact_email"];
        $obj->GalleryDescription = $_REQUEST["gallery_description"];
        $obj->update();

		// redirect to listing list
		header("Location:item_list.php?cat=" . $_REQUEST['category_id']);
		exit;
	}
}
else {
	if ($_REQUEST['mode'] == 'e') {
		//listing
		$obj = new Item($id);
        $id = $obj->Id;
        $category_id = $obj->CategoryId;
        $item_name = $obj->ItemName;
        $background_color = $obj->BackgroundColor;
        $contact_email = $obj->ContactEmail;
        $gallery_description = $obj->GalleryDescription;
        $category = new Category($category_id);
	}
	else if ($_REQUEST['mode'] == 'a') {
        $category_id = $category->Id;
	}
}

include("includes/pagetemplate.php");

function PageContent() {
    global $category;
	global $id;
	global $category_id;
	global $item_name;
	global $background_color;
	global $contact_email;
	global $gallery_description;
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
	            $bread_image_name = $category->CategoryName . ' Add';
	        }
	        else {
	            $bread_image_name = $category->CategoryName . ' Edit';
	        }

		    $aLabels = array();
		    $aLinks = array();
		    $aLabels[0] = 'Home';
		    $aLinks[0] = 'mainpage.php';
		    $aLabels[1] = $category->CategoryName;
		    $aLinks[1] = 'item_list.php?cat=' . $category->Id;
		    $aLabels[2] = $bread_image_name;
		    $aLinks[2] = '';
		    echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
		    ?>

		    <p class="larger2 botspace heading"><?php echo $bread_image_name; ?></p>

			<div class="formstyle">
				<form method="post" name="admin" id="admin" action="item_admin.php" enctype='multipart/form-data'>
					<table class="form-table">
						<tr>
							<td class="table-label" align="right">Item Name: </td>
							<td><input class="form midform" type="text" name="item_name" value="<?php echo $item_name; ?>" placeholder="Item Name" /></td>
						</tr>
						<tr>
							<td class="table-label" align="right">Background Color: </td>
							<td><input class="form midform" type="text" name="background_color" value="<?php echo $background_color; ?>" placeholder="Background Color (html code)" /></td>
						</tr>
						<tr>
							<td class="table-label" align="right">Contact Email: </td>
							<td><input class="form midform" type="text" name="contact_email" value="<?php echo $contact_email; ?>" placeholder="Contact Email" /></td>
						</tr>
						<tr>
							<td class="table-label" align="right">Gallery Description: </td>
							<td><input class="form midform" type="text" name="gallery_description" value="<?php echo $gallery_description; ?>" placeholder="Gallery Description" /></td>
						</tr>
					</table>
				
					<p class="submit"><input type="submit" name="commit" value="Save Item">&nbsp;&nbsp;<input type="submit" name="commit" value="Cancel"></p>
				
                   	<input type="hidden" name="id" value="<?php echo $id; ?>" />
                    <input type="hidden" name="category_id" value="<?php echo $category->Id; ?>">
                    
				</form>
			</div>
		</div>

	</div>
<?php
}
?>