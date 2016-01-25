<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

require("../inc/classes/ItemImage.php");
require("../inc/classes/ItemImageHighlight.php");
require("../inc/classes/Item.php");
require("../inc/classes/Category.php");
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

// if form was submitted:
if ($_POST['commit'] == "Cancel") {
	header("Location:itemimagehighlight_list.php?cat=" . $_REQUEST['cat'] . "&item=" . $_REQUEST['item'] . "&itemimage=" . $_REQUEST['itemimage']);
	exit;
}

if ($_POST['commit'] == "Save Item Image Highlight") {
	
	if ($id == 0) {
		// insert
        $obj = new ItemImageHighlight();
        $obj->ItemImageId = $_REQUEST["itemimage"];
        $obj->HotspotLeft = $_REQUEST["hotspot_left"];
        $obj->HotspotTop = $_REQUEST["hotspot_top"];
        $obj->ItemImageHighlightInfo = $_REQUEST["item_image_highlight_info"];
        $obj->create();
        
		// redirect to listing list
    	header("Location:itemimage_list.php?cat=" . $_REQUEST['cat'] . "&item=" . $_REQUEST['item'] . "&itemimage=" . $_REQUEST['itemimage']);
		exit;
	}
	else {
        // update
        $obj = new ItemImageHighlight($id);
        $obj->ItemImageId = $_REQUEST["itemimage"];
        $obj->HotspotLeft = $_REQUEST["hotspot_left"];
        $obj->HotspotTop = $_REQUEST["hotspot_top"];
        $obj->ItemImageHighlightInfo = $_REQUEST["item_image_highlight_info"];
        $obj->update();

		// redirect to listing list
    	header("Location:itemimagehighlight_list.php?cat=" . $_REQUEST['cat'] . "&item=" . $_REQUEST['item'] . "&itemimage=" . $_REQUEST['itemimage']);
		exit;
	}
}
else {
	if ($_REQUEST['mode'] == 'e') {
		//listing
        $objItemImageHighlight = new ItemImageHighlight($id);
        $item_image_id = $objItemImageHighlight->ItemImageId;
        $hotspot_left = $objItemImageHighlight->HotspotLeft;
        $hotspot_top = $objItemImageHighlight->HotspotTop;
        $item_image_highlight_info = $objItemImageHighlight->ItemImageHighlightInfo;
	}
	else if ($_REQUEST['mode'] == 'a') {
        $item_id = $_REQUEST["item"];
	}
}

include("includes/pagetemplate.php");

function PageContent() {
	global $id;
	global $item_image_id;
	global $hotspot_left;
	global $hotspot_top;
	global $item_image_highlight_info;
    $item = new Item($_REQUEST['item']);
    $category = new Category($item->CategoryId);
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
	            $bread_image_name = 'Item Image Highlight Add';
	        }
	        else {
	            $bread_image_name = 'Item Image Highlight Edit';
	        }

		    $aLabels = array();
		    $aLinks = array();
                $aLabels[0] = 'Home';
                $aLinks[0] = 'mainpage.php';
                $aLabels[1] = $objCategory->CategoryName;
                $aLinks[1] = 'category_list.php?';
                $aLabels[2] = 'Items';
                $aLinks[2] = 'item_list.php?cat=' . $_REQUEST['cat'];
                $aLabels[3] = 'Item Images';
                $aLinks[3] = 'itemimage_list.php?cat=' . $_REQUEST['cat'] . '&item=' . $_REQUEST['item'];
                $aLabels[4] = 'Item Highlight List';
                $aLinks[4] = 'itemimagehighlight_list.php?cat=' . $_REQUEST['cat'] . '&item=' . $_REQUEST['item'] . '&itemimage=' . $_REQUEST['itemimage'];
                $aLabels[5] = $bread_image_name;
                $aLinks[5] = '';
		    echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
		    ?>

		    <p class="larger2 botspace heading"><?php echo $bread_image_name; ?></p>

			<div class="formstyle ">
				<form method="post" name="admin" id="admin" action="itemimagehighlight_admin.php" enctype='multipart/form-data'>
					<table class="form-table">
						<tr>
							<td class="table-label" align="right">Left Offset (px): </td>
							<td><input class="form midform" type="text" name="hotspot_left" style="width: 120px;" value="<?php echo $hotspot_left; ?>" placeholder="Left Offset (px)" /></td>
						</tr>
						<tr>
							<td class="table-label" align="right">Top Offset (px): </td>
							<td><input class="form midform" type="text" name="hotspot_top" style="width: 120px;" value="<?php echo $hotspot_top; ?>" placeholder="Top Offset (px)" /></td>
						</tr>
						<tr>
							<td class="table-label" align="right">Highlight Info: </td>
							<td><textarea class="form midform mceEditor" name="item_image_highlight_info" placeholder="Highlight Information"><?php echo $item_image_highlight_info; ?></textarea></td>
						</tr>
					</table>
				
					<p class="submit"><input type="submit" name="commit" value="Save Item Image Highlight">&nbsp;&nbsp;<input type="submit" name="commit" value="Cancel"></p>
				
                   	<input type="hidden" name="id" value="<?php echo $id; ?>" />
                   	<input type="hidden" name="cat" value="<?php echo $_REQUEST['cat']; ?>" />
                   	<input type="hidden" name="item" value="<?php echo $_REQUEST['item']; ?>" />
                   	<input type="hidden" name="itemimage" value="<?php echo $_REQUEST['itemimage']; ?>" />

				</form>
			</div>
		</div>

	</div>

<?php
}
?>