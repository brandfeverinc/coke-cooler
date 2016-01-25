<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

require("../inc/classes/Patent.php");
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
array_push($aDropFields, 'patent_image_url_drop');
array_push($aDropFieldTypes, 'image');

// if form was submitted:
if ($_POST['commit'] == "Cancel") {
	header("Location:patent_list.php?cat=" . $_REQUEST['cat']);
	exit;
}

if ($_POST['commit'] == "Save Patent") {
	
	if ($id == 0) {
		// insert
        $obj = new Patent();
        $obj->CategoryId = $_REQUEST["cat"];
        $obj->PatentName = $_REQUEST["patent_name"];
        $obj->PatentAbstract = $_REQUEST["patent_abstract"];
        $obj->PatentProbableAssignee = $_REQUEST["patent_probable_assignee"];
        $obj->PatentAssigneesStd = $_REQUEST["patent_assignees_std"];
        $obj->PatentAssignees = $_REQUEST["patent_assignees"];
        $obj->create();
        
        $obj->handleFileUploads();
        $obj->handleDropFileUploads($aDropFields[0], 'PatentImageUrl');

		// redirect to listing list
		header("Location:patent_list.php?cat=" . $_REQUEST['cat']);
		exit;
	}
	else {
        // update
        $obj = new Patent($_REQUEST["id"]);
        $obj->PatentName = $_REQUEST["patent_name"];
        $obj->PatentAbstract = $_REQUEST["patent_abstract"];
        $obj->PatentProbableAssignee = $_REQUEST["patent_probable_assignee"];
        $obj->PatentAssigneesStd = $_REQUEST["patent_assignees_std"];
        $obj->PatentAssignees = $_REQUEST["patent_assignees"];
        $obj->update();

        $obj->handleFileUploads();
        $obj->handleDropFileUploads($aDropFields[0], 'PatentImageUrl');
		
		// redirect to listing list
		header("Location:patent_list.php?cat=" . $_REQUEST['cat']);
		exit;
	}
}
else {
	if ($_REQUEST['mode'] == 'e') {
		//listing
		$cat = new Patent($id);
        $patent_name = $cat->PatentName;
        $patent_abstract = $cat->PatentAbstract;
        $patent_probable_assignee = $cat->PatentProbableAssignee;
        $patent_assignees_std = $cat->PatentAssigneesStd;
        $patent_assignees = $cat->PatentAssignees;
        $patent_image_url = $cat->PatentImageUrl;
	}
}

include("includes/pagetemplate.php");

function PageContent() {
	global $id;
	global $patent_name;
    global $patent_abstract;
    global $patent_probable_assignee;
    global $patent_assignees_std;
    global $patent_assignees;
	global $patent_image_url;
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
	            $bread_image_name = 'Patent Add';
	        }
	        else {
	            $bread_image_name = 'Patent Edit';
	        }

		    $aLabels = array();
		    $aLinks = array();
		    $aLabels[0] = 'Home';
		    $aLinks[0] = 'mainpage.php';
            $aLabels[1] = 'Category List';
            $aLinks[1] = 'category_list.php';
            $aLabels[2] = 'Patents';
            $aLinks[2] = 'patent_list.php&cat=' . $_REQUEST['cat'];
		    $aLabels[3] = $bread_image_name;
		    $aLinks[3] = '';
		    echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
		    ?>

		    <p class="larger2 botspace heading"><?php echo $bread_image_name; ?></p>

			<div class="formstyle ">
				<form method="post" name="admin" id="admin" action="patent_admin.php" enctype='multipart/form-data'>
					<table class="form-table">
						<tr>
							<td class="table-label" align="right">Patent Name: </td>
							<td><input class="form midform" type="text" name="patent_name" value="<?php echo $patent_name ?>" placeholder="Patent Name" /></td>
						</tr>
						<tr>
							<td class="table-label" align="right">Abstract: </td>
							<td><textarea class="form midform mceNoEditor" name="patent_abstract" placeholder="Patent Abstract"><?php echo $patent_abstract; ?></textarea></td>
						</tr>
						<tr>
							<td class="table-label" align="right">Probable Assignee: </td>
							<td><textarea class="form midform mceNoEditor" name="patent_probable_assignee" placeholder="Probable Assignee"><?php echo $patent_probable_assignee; ?></textarea></td>
						</tr>
						<tr>
							<td class="table-label" align="right">Assignee(s):(std): </td>
							<td><textarea class="form midform mceNoEditor" name="patent_assignees_std" placeholder="Assignee(s):(std)"><?php echo $patent_assignees_std; ?></textarea></td>
						</tr>
						<tr>
							<td class="table-label" align="right">Assignee(s): </td>
							<td><textarea class="form midform mceNoEditor" name="patent_assignees" placeholder="Assignee(s)"><?php echo $patent_assignees; ?></textarea></td>
						</tr>
                    	<tr>
                    		<td class="table-label" align="right" valign="top">Image File: </td>
                    		<td><input type="file" class="slick" size="50" maxlength="200" name="image_file" id="image_file" value="<?php echo(htmlspecialchars($patent_image_url)); ?>"/>
                    		    <?php
                    		    if ($patent_image_url != '') {
                    		        echo '<br ><strong>Current File:</strong> ' . $patent_image_url;
                    		    }
                    		    
                    		    // Set up Drop Area
                    		    echo DragAndDrop::CreateArea($aDropFields[0]);
                    		    ?>
                    		</td>
                    	</tr>
					</table>
				
					<p class="submit"><input type="submit" name="commit" value="Save Patent">&nbsp;&nbsp;<input type="submit" name="commit" value="Cancel"></p>
				
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