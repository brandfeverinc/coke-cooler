<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

require("../inc/classes/LightImage.php");
require("../inc/classes/Light.php");
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
	$objLightImage = new LightImage($id);
    $objLight = new Light($objLightImage->LightId);

	header("Location:lightimage_list.php?cat=" . $_REQUEST['cat'] . "&light=" . $objLight->Id);
	exit;
}

if ($_POST['commit'] == "Delete Light Image") {

	$objLightImage = new LightImage($id);
    $objLight = new Light($objLightImage->LightId);
	$objLightImage->Delete($id);

	header("Location:lightimage_list.php?cat=" . $_REQUEST['cat'] . "&light=" . $objLight->Id);
	exit;
}

$objLightImage = new LightImage($id);
$objLight = new Light($objLightImage->LightId);

include("includes/pagetemplate.php");

function PageContent() {
	global $objLightImage;
	global $objLight;
	global $id;
?>

            <div class="layout laymidwidth">

                <?php
                $aLabels = array();
                $aLinks = array();
                $aLabels[0] = 'Home';
                $aLinks[0] = 'mainpage.php';
                $aLabels[1] = 'Category List';
                $aLinks[1] = 'category_list.php';
                $aLabels[2] = 'Light List';
                $aLinks[2] = 'light_list.php';
                $aLabels[3] = 'Light Images';
                $aLinks[3] = 'lightimage_list.php?cat=' . $_REQUEST['cat'] . '&light=' . $_REQUEST['light'];
                $aLabels[4] = 'Delete Light Image';
                $aLinks[4] = '';
                echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
                ?>

                <p class="larger botspace heading">Light Image Delete</p>

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
                    <form method="post" action="lightimage_delete.php" id="delete-form">
                        <p>Are you sure you want to delete this light image? This is irreversible.</p>
                        <p>&nbsp;</p>
                	    <table>
                	        <tr>
                	            <td><strong>Image: </strong></td>
                	            <td style="width: 20px;">&nbsp;</td>
                	            <td><img src="/<?php echo $objLightImage->GetPath() . $objLightImage->ImageFile; ?>" style="max-width: 200px;" /></td>
                	        </tr>
                	    </table>
                        <p>&nbsp;</p>
                      	<input type="hidden" name="id" value="<? echo $id; ?>" />
                      	<input type="hidden" name="cat" value="<? echo $cat; ?>" />
                      	<input type="hidden" name="light" value="<? echo $light; ?>" />

                        <p class="submit"><input type="submit" name="commit" id="submit" value="Delete Light Image">&nbsp;&nbsp;<input type="submit" id="cancel" name="commit" value="Cancel"></p>

                    </form>
                </div>

            </div>

<?php
}
?>