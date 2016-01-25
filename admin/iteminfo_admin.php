<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

require("../inc/classes/Category.php");
require("../inc/classes/ItemInfo.php");
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
	header("Location:iteminfo_list.php?cat=" . $_REQUEST['cat'] . "&item=" . $_REQUEST['item']);
	exit;
}

if ($_POST['commit'] == "Save Item Info") {
	
	if ($id == 0) {
		// insert
        $obj = new ItemInfo();
        $obj->ItemId = $_REQUEST["item"];
        $obj->ItemInfo = $_REQUEST["item_info"];
        $obj->ItemInfoTypeId = $_REQUEST["item_info_type_id"];
        $obj->create();
        
		// redirect to listing list
		header("Location:iteminfo_list.php?cat=" . $_REQUEST['cat'] . "&item=" . $_REQUEST['item']);
		exit;
	}
	else {
        // update
        $obj = new ItemInfo($_REQUEST["id"]);
        $obj->ItemInfo = $_REQUEST["item_info"];
        $obj->ItemInfoTypeId = $_REQUEST["item_info_type_id"];
        $obj->update();

		// redirect to listing list
		header("Location:iteminfo_list.php?cat=" . $_REQUEST['cat'] . "&item=" . $_REQUEST['item']);
		exit;
	}
}
else {
	if ($_REQUEST['mode'] == 'e') {
		//listing
		$objItemInfo = new ItemInfo($id);
        $item_info = $objItemInfo->ItemInfo;
        $type = $objItemInfo->ItemInfoTypeId;
	}
}

include("includes/pagetemplate.php");

function PageContent() {
	global $id;
	global $item_info;
	global $type;
	
	$objCategory = new Category($_REQUEST['cat']);
?>

	<script type="text/javascript" src="tinymce/js/tinymce/tinymce.min.js"></script>
	<script type="text/javascript">
	tinyMCE.init({
	    selector : "textarea.mceEditor",
	    plugins : "image code pagebreak table spellchecker preview searchreplace print contextmenu paste fullscreen nonbreaking",
	    image_advtab : true,
	    forced_root_block : "p",
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
	            $bread_image_name = 'Item Info Add';
	        }
	        else {
	            $bread_image_name = 'Item Info Edit';
	        }

		    $aLabels = array();
		    $aLinks = array();
		    $aLabels[0] = 'Home';
		    $aLinks[0] = 'mainpage.php';
            $aLabels[1] = $objCategory->CategoryName;
            $aLinks[1] = 'category_list.php?';
            $aLabels[2] = 'Items';
            $aLinks[2] = 'item_list.php?cat=' . $_REQUEST['cat'];
		    $aLabels[3] = $bread_image_name;
		    $aLinks[3] = '';
		    echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
		    ?>

		    <p class="larger2 botspace heading"><?php echo $bread_image_name; ?></p>

			<div class="formstyle ">
				<form method="post" name="admin" id="admin" action="iteminfo_admin.php" enctype='multipart/form-data'>
					<table class="form-table">
						<tr>
							<td class="table-label" align="right">Item Info Text: </td>
							<td><textarea class="form midform mceEditor" name="item_info" placeholder="Item Info"><?php echo $item_info; ?></textarea></td>
						</tr>
					</table>
				
					<p class="submit"><input type="submit" name="commit" value="Save Item Info">&nbsp;&nbsp;<input type="submit" name="commit" value="Cancel"></p>
				
                   	<input type="hidden" name="id" value="<? echo $id; ?>" />
                   	<input type="hidden" name="cat" value="<? echo $_REQUEST['cat']; ?>" />
                   	<input type="hidden" name="item" value="<? echo $_REQUEST['item']; ?>" />
                   	<input type="hidden" name="item_info_type_id" value="<? echo $_REQUEST['type']; ?>" />

				</form>
			</div>
		</div>

	</div>

<?php
}
?>