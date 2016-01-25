<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

require("../inc/classes/Light.php");
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
	header("Location:light_list.php?cat=" . $_REQUEST['cat']);
	exit;
}

if ($_POST['commit'] == "Save Light") {
	
	if ($id == 0) {
		// insert
        $obj = new Light();
        $obj->OverviewText = $_REQUEST["overview_text"];
        $obj->create();
        
		// redirect to listing list
		header("Location:light_list.php?cat=" . $_REQUEST['cat']);
		exit;
	}
	else {
        // update
        $obj = new Light($_REQUEST["id"]);
        $obj->OverviewText = $_REQUEST["overview_text"];
        $obj->update();

		// redirect to listing list
		header("Location:light_list.php?cat=" . $_REQUEST['cat']);
		exit;
	}
}
else {
	if ($_REQUEST['mode'] == 'e') {
		//listing
		$objLight = new Light($id);
        $overview_text = $objLight->OverviewText;
	}
}

include("includes/pagetemplate.php");

function PageContent() {
	global $id;
	global $overview_text;
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
	            $bread_image_name = 'Light Add';
	        }
	        else {
	            $bread_image_name = 'Light Edit';
	        }

		    $aLabels = array();
		    $aLinks = array();
		    $aLabels[0] = 'Home';
		    $aLinks[0] = 'mainpage.php';
            $aLabels[1] = 'Category List';
            $aLinks[1] = 'category_list.php';
		    $aLabels[2] = 'Light';
		    $aLinks[2] = 'light_list.php&cat=' . $_REQUEST['cat'];
		    $aLabels[3] = $bread_image_name;
		    $aLinks[3] = '';
		    echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
		    ?>

		    <p class="larger2 botspace heading"><?php echo $bread_image_name; ?></p>

			<div class="formstyle ">
				<form method="post" name="admin" id="admin" action="light_admin.php" enctype='multipart/form-data'>
					<table class="form-table">
						<tr>
							<td class="table-label" align="right">Overview Text: </td>
							<td><textarea class="form midform mceEditor" name="overview_text" placeholder="Overview Text"><?php echo $overview_text; ?></textarea></td>
						</tr>
					</table>
				
					<p class="submit"><input type="submit" name="commit" value="Save Light">&nbsp;&nbsp;<input type="submit" name="commit" value="Cancel"></p>
				
                   	<input type="hidden" name="id" value="<? echo $id; ?>" />
                   	<input type="hidden" name="cat" value="<? echo $_REQUEST['cat']; ?>" />

				</form>
			</div>
		</div>

	</div>

<?php
}
?>