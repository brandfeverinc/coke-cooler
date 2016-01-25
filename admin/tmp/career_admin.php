<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

require("../inc/classes/Career.php");
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
	header("Location:career_list.php");
	exit;
}

if ($_POST['commit'] == "Save Career") {
	
	if ($id == 0) {
		// add the listing

        $objCareer = new Career();
        $objCareer->CareerTitle = $_REQUEST["career_title"];
        $objCareer->JobDescription = $_REQUEST["job_description"];
        if ($_REQUEST["is_active"] == '1') {
            $objCareer->IsActive = '1';
        }
        else {
            $objCareer->IsActive = '0';
        }
        $objCareer->Create();
		
		// redirect to listing list
		header("Location:career_list.php");
		exit;
	}
	else {

        $objCareer = new Career($_REQUEST["id"]);
        $objCareer->CareerTitle = $_REQUEST["career_title"];
        $objCareer->JobDescription = $_REQUEST["job_description"];
        if ($_REQUEST["is_active"] == '1') {
            $objCareer->IsActive = '1';
        }
        else {
            $objCareer->IsActive = '0';
        }
        $objCareer->Update();
		
		// redirect to listing list
		header("Location:career_list.php");
		exit;
	}
}
else {
	if ($_REQUEST['mode'] == 'e') {
		//listing
		$objCareer = new Career($id);
        $career_title = $objCareer->CareerTitle;
        $job_description = $objCareer->JobDescription;
        $is_active = $objCareer->IsActive;
	}
}

include("includes/pagetemplate.php");

function PageContent() {
	global $career_title;
	global $job_description;
	global $is_active;
	global $id;
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

	<div id="content">

		<div class="layout">
	        <?php
	        if ($id == 0) {
	            $bread_title = 'Career Add';
	        }
	        else {
	            $bread_title = 'Career Edit';
	        }

		    $aLabels = array();
		    $aLinks = array();
		    $aLabels[0] = 'Home';
		    $aLinks[0] = 'mainpage.php';
		    $aLabels[1] = 'Careers';
		    $aLinks[1] = 'career_list.php';
		    $aLabels[2] = $bread_title;
		    $aLinks[2] = '';
		    echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
		    ?>

		    <p class="larger botspace heading"><?php echo $bread_title; ?></p>

			<div class="formstyle ">
				<form method="post" action="career_admin.php">
					<table class="form-table">
						<tr>
							<td class="table-label" align="right">Job Title: </td>
							<td><input class="form midform" type="text" name="career_title" value="<?php echo $career_title; ?>" placeholder="Job Title"></td>
						</tr>
						<tr>
							<td class="table-label" align="right">Description: </td>
							<td><textarea class="form midform mceEditor" name="job_description" placeholder="Career" style="height: 300px;"><?php echo $job_description; ?></textarea></td>
						</tr>
						<tr>
							<td class="table-label" align="right">Is Active?: </td>
							<td><input <?php if ($is_active != '0') { echo 'checked'; } ?> type="radio" name="is_active" value="1">Yes&nbsp;&nbsp;&nbsp;<input <?php if ($is_active == '0') { echo 'checked'; } ?> type="radio" name="is_active" value="0">No</td>
						</tr>
					</table>
				
					<p class="submit"><input type="submit" name="commit" value="Save Career">&nbsp;&nbsp;<input type="submit" name="commit" value="Cancel"></p>
				
                   	<input type="hidden" name="id" value="<? echo $id; ?>" />

				</form>
		</div>

	</div>

<?php
}
?>