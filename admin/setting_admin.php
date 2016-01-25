<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

require("../inc/classes/GenericDb.php");
require('../inc/classes/Helpers.php');

// page vars
$message = "";
$id = "";
$pwmsg = '';
$setting = "";

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
	header("Location:setting_list.php");
	exit;
}

if ($_POST['commit'] == "Save Setting") {
	echo("he");
	echo "<br>" . $id;
	if ($id == 0) {
		// insert
        $db = new GenericDb();
        $db->arrayInsert("setting", $_REQUEST);

		// redirect to listing list
		header("Location:setting_list.php");
		exit;
	}
	else {
        // update
        $db = new GenericDb();
        $db->arrayUpdate("setting", $_REQUEST, "id = :id", array(":id"=>$id));

		// redirect to listing list
		header("Location:setting_list.php");
		exit;
	}
}
else {
	if ($_REQUEST['mode'] == 'e') {
		//listing
		$db = new GenericDb();
		$rows = $db->run("select * from setting where id = ?", $id);
		$setting = $rows[0];
	}
}

include("includes/pagetemplate.php");

function PageContent() {
	global $id;
	global $setting;
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
	            $bread_image_name = 'Setting Add';
	        }
	        else {
	            $bread_image_name = 'Setting Edit';
	        }

		    $aLabels = array();
		    $aLinks = array();
		    $aLabels[0] = 'Home';
		    $aLinks[0] = 'mainpage.php';
		    $aLabels[1] = 'Settings';
		    $aLinks[1] = 'setting_list.php';
		    $aLabels[2] = $bread_image_name;
		    $aLinks[2] = '';
		    echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
		    ?>

		    <p class="larger2 botspace heading"><?php echo $bread_image_name; ?></p>

			<div class="formstyle">
				<form method="post" name="admin" id="admin" action="setting_admin.php" enctype='multipart/form-data'>
					<table class="form-table">
						<tr>
							<td class="table-label" align="right">Setting Name: </td>
							<td><input class="form midform" type="text" name="setting_name" value="<?php echo $setting['setting_name']; ?>" placeholder="Setting Name" /></td>
						</tr>
						<tr>
							<td class="table-label" align="right">Setting Value: </td>
							<td><textarea class="form midform mceEditor" name="setting_value" placeholder="Setting Value"><?php echo $setting['setting_value']; ?></textarea></td>
						</tr>
					</table>
				
					<p class="submit"><input type="submit" name="commit" value="Save Setting">&nbsp;&nbsp;<input type="submit" name="commit" value="Cancel"></p>
				
                   	<input type="hidden" name="id" value="<?php echo $id; ?>" />

				</form>
			</div>
		</div>

	</div>
<?php
}
?>