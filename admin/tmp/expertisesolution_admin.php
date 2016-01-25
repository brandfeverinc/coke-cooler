<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

require("../inc/classes/ExpertiseSolution.php");
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
	header("Location:expertisesolution_list.php");
	exit;
}

if ($_POST['commit'] == "Save Expertise Solution") {
	
	if ($id == 0) {
		// add the listing

        $objExpertiseSolution = new ExpertiseSolution();
        $objExpertiseSolution->SolutionName = $_REQUEST["solution_name"];
        $objExpertiseSolution->SolutionSubheading = $_REQUEST["solution_subheading"];
        $objExpertiseSolution->SolutionText = $_REQUEST["solution_text"];
        $objExpertiseSolution->Create();
		
		// redirect to listing list
		header("Location:expertisesolution_list.php");
		exit;
	}
	else {

        $objExpertiseSolution = new ExpertiseSolution($_REQUEST["id"]);
        $objExpertiseSolution->SolutionName = $_REQUEST["solution_name"];
        $objExpertiseSolution->SolutionSubheading = $_REQUEST["solution_subheading"];
        $objExpertiseSolution->SolutionText = $_REQUEST["solution_text"];
        $objExpertiseSolution->Update();
		
		// redirect to listing list
		header("Location:expertisesolution_list.php");
		exit;
	}
}
else {
	if ($_REQUEST['mode'] == 'e') {
		//listing
		$objExpertiseSolution = new ExpertiseSolution($id);
        $solution_name = $objExpertiseSolution->SolutionName;
        $solution_subheading = $objExpertiseSolution->SolutionSubheading;
        $solution_text = $objExpertiseSolution->SolutionText;
	}
}

include("includes/pagetemplate.php");

function PageContent() {
	global $solution_name;
	global $solution_subheading;
	global $solution_text;
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
	            $bread_title = 'Expertise Solution Add';
	        }
	        else {
	            $bread_title = 'Expertise Solution Edit';
	        }

		    $aLabels = array();
		    $aLinks = array();
		    $aLabels[0] = 'Home';
		    $aLinks[0] = 'mainpage.php';
		    $aLabels[1] = 'Expertise Solutions';
		    $aLinks[1] = 'expertisesolution_list.php';
		    $aLabels[2] = $bread_title;
		    $aLinks[2] = '';
		    echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
		    ?>

		    <p class="larger botspace heading"><?php echo $bread_title; ?></p>

			<div class="formstyle ">
				<form method="post" action="expertisesolution_admin.php">
					<table class="form-table">
						<tr>
							<td class="table-label" align="right">Solution Name: </td>
							<td><input class="form midform" type="text" name="solution_name" value="<?php echo $solution_name; ?>" placeholder="Solution Name"></td>
						</tr>
						<tr>
							<td class="table-label" align="right">Solution Subheading: </td>
							<td><input class="form midform" type="text" name="solution_subheading" value="<?php echo $solution_subheading; ?>" placeholder="Solution Subheading"></td>
						</tr>
						<tr>
							<td class="table-label" align="right">Solution Text: </td>
							<td><textarea class="form midform mceEditor" name="solution_text" placeholder="Solution Text"><?php echo $solution_text; ?></textarea></td>
						</tr>
					</table>
				
					<p class="submit"><input type="submit" name="commit" value="Save Expertise Solution">&nbsp;&nbsp;<input type="submit" name="commit" value="Cancel"></p>
				
                   	<input type="hidden" name="id" value="<? echo $id; ?>" />

				</form>
		</div>

	</div>

<?php
}
?>