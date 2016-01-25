<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

require("../inc/classes/Testimonial.php");
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
	header("Location:testimonial_list.php");
	exit;
}

if ($_POST['commit'] == "Save Testimonial") {
	
	if ($id == 0) {
		// add the listing

        $objTestimonial = new Testimonial();
        $objTestimonial->CustomerName = $_REQUEST["customer_name"];
        $objTestimonial->CustomerTitle = $_REQUEST["customer_title"];
        $objTestimonial->CustomerCompany = $_REQUEST["customer_company"];
        $objTestimonial->TestimonialText = $_REQUEST["testimonial_text"];
        $objTestimonial->Create();
		
		// redirect to listing list
		header("Location:testimonial_list.php");
		exit;
	}
	else {

        $objTestimonial = new Testimonial($_REQUEST["id"]);
        $objTestimonial->CustomerName = $_REQUEST["customer_name"];
        $objTestimonial->CustomerTitle = $_REQUEST["customer_title"];
        $objTestimonial->CustomerCompany = $_REQUEST["customer_company"];
        $objTestimonial->TestimonialText = $_REQUEST["testimonial_text"];
        $objTestimonial->Update();
		
		// redirect to listing list
		header("Location:testimonial_list.php");
		exit;
	}
}
else {
	if ($_REQUEST['mode'] == 'e') {
		//listing
		$objTestimonial = new Testimonial($id);
        $customer_name = $objTestimonial->CustomerName;
        $customer_title = $objTestimonial->CustomerTitle;
        $customer_company = $objTestimonial->CustomerCompany;
        $testimonial_text = $objTestimonial->TestimonialText;
	}
}

include("includes/pagetemplate.php");

function PageContent() {
	global $customer_name;
	global $customer_title;
	global $customer_company;
	global $testimonial_text;
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
	            $bread_title = 'Testimonial Add';
	        }
	        else {
	            $bread_title = 'Testimonial Edit';
	        }

		    $aLabels = array();
		    $aLinks = array();
		    $aLabels[0] = 'Home';
		    $aLinks[0] = 'mainpage.php';
		    $aLabels[1] = 'Testimonials';
		    $aLinks[1] = 'testimonial_list.php';
		    $aLabels[2] = $bread_title;
		    $aLinks[2] = '';
		    echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
		    ?>

		    <p class="larger botspace heading"><?php echo $bread_title; ?></p>

			<div class="formstyle ">
				<form method="post" action="testimonial_admin.php">
					<table class="form-table">
						<tr>
							<td class="table-label" align="right">Customer Name: </td>
							<td><input class="form midform" type="text" name="customer_name" value="<?php echo $customer_name; ?>" placeholder="Customer Name"></td>
						</tr>
						<tr>
							<td class="table-label" align="right">Customer Title: </td>
							<td><input class="form midform" type="text" name="customer_title" value="<?php echo $customer_title; ?>" placeholder="Customer Title"></td>
						</tr>
						<tr>
							<td class="table-label" align="right">Customer Company: </td>
							<td><input class="form midform" type="text" name="customer_company" value="<?php echo $customer_company; ?>" placeholder="Customer Company"></td>
						</tr>
						<tr>
							<td class="table-label" align="right">Testimonial: </td>
							<td><textarea class="form midform mceEditor" name="testimonial_text" placeholder="Testimonial"><?php echo $testimonial_text; ?></textarea></td>
						</tr>
					</table>
				
					<p class="submit"><input type="submit" name="commit" value="Save Testimonial">&nbsp;&nbsp;<input type="submit" name="commit" value="Cancel"></p>
				
                   	<input type="hidden" name="id" value="<? echo $id; ?>" />

				</form>
		</div>

	</div>

<?php
}
?>