<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

require("../inc/classes/HomepageImage.php");
require('../inc/classes/Helpers.php');

// page vars
$page_title = "";
$id = $_REQUEST['id'];

// id required
if ($id == "") {
	header("Location:mainpage.php");
	exit;
}

// if form was submitted
if ($_POST['commit'] == "Cancel") {
	header("Location:homepageimage_list.php");
	exit;
}

if ($_POST['commit'] == "Delete Home Page Image") {

	$objHomepageImage = new HomepageImage();
	$objHomepageImage->Delete($id);

	header("Location:homepageimage_list.php");
	exit;
}

$objHomepageImage = new HomepageImage($id);

include("includes/pagetemplate.php");

function PageContent() {
	global $objHomepageImage;
	global $id;
?>

            <div class="layout laymidwidth">

                <?php
                $aLabels = array();
                $aLinks = array();
                $aLabels[0] = 'Home';
                $aLinks[0] = 'mainpage.php';
                $aLabels[1] = 'Home Page Images';
                $aLinks[1] = 'homepageimage_list.php';
                $aLabels[2] = 'Delete Home Page Image';
                $aLinks[2] = '';
                echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
                ?>

                <p class="larger botspace heading">Home Page Image Delete</p>

                <script type="text/javascript">
                    $(document).ready(function() {
                        //$('#delete-form').submit(function() {
                        $('#submit').click(function() {
                            if (confirm("Are you sure?")) {
                                return true;
                            }
                            else {
                                return false;
                            }
                        });
                        $('#cancel').click(function() {
                            return true;
                        });
                    });
                </script>
                
                <div class="formstyle">
                    <form method="post" action="homepageimage_delete.php" id="delete-form">
                        <p>Are you sure you want to delete this image? This is irreversible.</p>
                        <p>&nbsp;</p>
                	    <table>
                	        <tr>
                	            <td><strong>Image: </strong></td>
                	            <td style="width: 20px;">&nbsp;</td>
                	            <td><img src="/<?php echo $objHomepageImage->GetPath() . $objHomepageImage->HomepageImage; ?>" style="width:200px;"></td>
                	        </tr>
                	    </table>
                        <p>&nbsp;</p>
                      	<input type="hidden" name="id" value="<? echo $id; ?>" />

                        <p class="submit"><input type="submit" name="commit" id="submit" value="Delete Home Page Image">&nbsp;&nbsp;<input type="submit" id="cancel" name="commit" value="Cancel"></p>

                    </form>
                </div>

            </div>

<?php
}
?>