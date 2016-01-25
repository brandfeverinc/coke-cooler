<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

require("../inc/classes/ExpertiseMarket.php");
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
	header("Location:expertisemarket_list.php");
	exit;
}

if ($_POST['commit'] == "Save Expertise Market") {
	
	if ($id == 0) {
		// add the listing

        $objExpertiseMarket = new ExpertiseMarket();
        $objExpertiseMarket->MarketName = $_REQUEST["market_name"];
        $objExpertiseMarket->MarketText = $_REQUEST["market_text"];
        $objExpertiseMarket->Create();
		
		// redirect to listing list
		header("Location:expertisemarket_list.php");
		exit;
	}
	else {

        $objExpertiseMarket = new ExpertiseMarket($_REQUEST["id"]);
        $objExpertiseMarket->MarketName = $_REQUEST["market_name"];
        $objExpertiseMarket->MarketText = $_REQUEST["market_text"];
        $objExpertiseMarket->Update();
		
		// redirect to listing list
		header("Location:expertisemarket_list.php");
		exit;
	}
}
else {
	if ($_REQUEST['mode'] == 'e') {
		//listing
		$objExpertiseMarket = new ExpertiseMarket($id);
        $market_name = $objExpertiseMarket->MarketName;
        $market_text = $objExpertiseMarket->MarketText;
	}
}

include("includes/pagetemplate.php");

function PageContent() {
	global $market_name;
	global $market_text;
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
	            $bread_title = 'Expertise Market Add';
	        }
	        else {
	            $bread_title = 'Expertise Market Edit';
	        }

		    $aLabels = array();
		    $aLinks = array();
		    $aLabels[0] = 'Home';
		    $aLinks[0] = 'mainpage.php';
		    $aLabels[1] = 'Expertise Markets';
		    $aLinks[1] = 'expertisemarket_list.php';
		    $aLabels[2] = $bread_title;
		    $aLinks[2] = '';
		    echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
		    ?>

		    <p class="larger botspace heading"><?php echo $bread_title; ?></p>

			<div class="formstyle ">
				<form method="post" action="expertisemarket_admin.php">
					<table class="form-table">
						<tr>
							<td class="table-label" align="right">Market Name: </td>
							<td><input class="form midform" type="text" name="market_name" value="<?php echo $market_name; ?>" placeholder="Market Name"></td>
						</tr>
						<tr>
							<td class="table-label" align="right">Market Text: </td>
							<td><textarea class="form midform mceEditor" name="market_text" placeholder="Market Text"><?php echo $market_text; ?></textarea></td>
						</tr>
					</table>
				
					<p class="submit"><input type="submit" name="commit" value="Save Expertise Market">&nbsp;&nbsp;<input type="submit" name="commit" value="Cancel"></p>
				
                   	<input type="hidden" name="id" value="<? echo $id; ?>" />

				</form>
		</div>

	</div>

<?php
}
?>