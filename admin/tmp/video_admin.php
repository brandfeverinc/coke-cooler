<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

require("../inc/classes/Video.php");
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
	header("Location:video_list.php");
	exit;
}

if ($_POST['commit'] == "Save Video") {
	
	if ($id == 0) {
		// add the listing

        $objVideo = new Video();
        $objVideo->Title = $_REQUEST["title"];
        $objVideo->Summary = $_REQUEST["summary"];
        $objVideo->VideoEmbed = $_REQUEST["video_embed"];
        $objVideo->Create();
		
        $objVideo->HandleFileUploads();
        $objVideo->HandleDropFileUploads($aDropFields[0], 'ImageFile');
        
		// redirect to listing list
		header("Location:video_list.php");
		exit;
	}
	else {

        $objVideo = new Video($_REQUEST["id"]);
        $objVideo->Title = $_REQUEST["title"];
        $objVideo->Summary = $_REQUEST["summary"];
        $objVideo->VideoEmbed = $_REQUEST["video_embed"];
        $objVideo->Update();
		
        $objVideo->HandleFileUploads();
        $objVideo->HandleDropFileUploads($aDropFields[0], 'ImageFile');
        
		// redirect to listing list
		header("Location:video_list.php");
		exit;
	}
}
else {
	if ($_REQUEST['mode'] == 'e') {
		//listing
		$objVideo = new Video($id);
        $title = $objVideo->Title;
        $summary = $objVideo->Summary;
        $video_embed = $objVideo->VideoEmbed;
        $image_file = $objVideo->ImageFile;
	}
}

include("includes/pagetemplate.php");

function PageContent() {
	global $title;
	global $summary;
	global $image_file;
	global $video_embed;
	global $id;
    global $aDropFields;
    global $aDropFieldTypes;
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
	    document_base_url : "http://havells.boxkitemedia.com"
	});
	</script>

    <?php
    // Set up Drag And Drop styles for the drop area
    echo DragAndDrop::SetStyles();
    ?>

	<div id="content">

		<div class="layout">
	        <?php
	        if ($id == 0) {
	            $bread_title = 'Video Add';
	        }
	        else {
	            $bread_title = 'Video Edit';
	        }

		    $aLabels = array();
		    $aLinks = array();
		    $aLabels[0] = 'Home';
		    $aLinks[0] = 'mainpage.php';
		    $aLabels[1] = 'Videos';
		    $aLinks[1] = 'video_list.php';
		    $aLabels[2] = $bread_title;
		    $aLinks[2] = '';
		    echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
		    ?>

		    <p class="larger botspace heading"><?php echo $bread_title; ?></p>

			<div class="formstyle ">
				<form method="post" action="video_admin.php">
					<table class="form-table">
						<tr>
							<td class="table-label" align="right">Title: </td>
							<td><input class="form midform" type="text" name="title" value="<?php echo $title; ?>" placeholder="Title"></td>
						</tr>
						<tr>
							<td class="table-label" align="right">Summary: </td>
							<td><textarea class="form midform mceEditor" name="summary" placeholder="Summary"><?php echo $summary; ?></textarea></td>
						</tr>
                    	<tr>
                    		<td class="table-label" align="right" valign="top">Image File: </td>
                    		<td><input type="file" class="slick" size="50" maxlength="200" name="image_file" id="image_file" value="<?php echo(htmlspecialchars($image_file)); ?>"/>
                    		    <?php
                    		    if ($image_file != '') {
                    		        echo '<br ><strong>Current File:</strong> ' . $image_file;
                    		    }
                    		    
                    		    // Set up Drop Area
                    		    echo DragAndDrop::CreateArea($aDropFields[0]);
                    		    ?>
                    		</td>
                    	</tr>
						<tr>
							<td class="table-label" align="right" style="vertical-align: top;">Video Code: </td>
							<td><textarea class="form midform mceNoEditor" name="video_embed" style="height: 200px;" placeholder="Video Code"><?php echo $video_embed; ?></textarea></td>
						</tr>
					</table>
				
					<p class="submit"><input type="submit" name="commit" value="Save Video">&nbsp;&nbsp;<input type="submit" name="commit" value="Cancel"></p>
				
                   	<input type="hidden" name="id" value="<? echo $id; ?>" />

				</form>
		</div>

	</div>

<?php
// Set up Drag and Drop javascript controls
echo DragAndDrop::Control($aDropFields, $aDropFieldTypes);
}
?>