<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

require("../inc/classes/ColorImage.php");
require("../inc/classes/Color.php");
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
	$objColorImage = new ColorImage($id);
    $objColor = new Color($objColorImage->ColorId);

	header("Location:colorimage_list.php?cat=" . $_REQUEST['cat'] . "&color=" . $objColor->Id);
	exit;
}

if ($_POST['commit'] == "Delete Color Image") {

	$objColorImage = new ColorImage($id);
    $objColor = new Color($objColorImage->ColorId);
	$objColorImage->Delete($id);

	header("Location:colorimage_list.php?cat=" . $_REQUEST['cat'] . "&color=" . $objColor->Id);
	exit;
}

$objColorImage = new ColorImage($id);
$objColor = new Color($objColorImage->ColorId);

include("includes/pagetemplate.php");

function PageContent() {
	global $objColorImage;
	global $objColor;
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
                $aLabels[2] = 'Color List';
                $aLinks[2] = 'color_list.php?cat=' . $_REQUEST['cat'];
                $aLabels[3] = 'Color Images';
                $aLinks[3] = 'colorimage_list.php?cat=' . $_REQUEST['cat'] . '&color=' . $_REQUEST['color'];
                $aLabels[4] = 'Delete Color Image';
                $aLinks[4] = '';
                echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
                ?>

                <p class="larger botspace heading">Color Image Delete</p>

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
                    <form method="post" action="colorimage_delete.php" id="delete-form">
                        <p>Are you sure you want to delete this color image? This is irreversible.</p>
                        <p>&nbsp;</p>
                	    <table>
                	        <tr>
                	            <td><strong>Image: </strong></td>
                	            <td style="width: 20px;">&nbsp;</td>
                	            <td><img src="/<?php echo $objColorImage->GetPath() . $objColorImage->ImageFile; ?>" style="max-width: 200px;" /></td>
                	        </tr>
                	    </table>
                        <p>&nbsp;</p>
                      	<input type="hidden" name="id" value="<? echo $id; ?>" />
                      	<input type="hidden" name="cat" value="<? echo $_REQUEST['cat']; ?>" />
                      	<input type="hidden" name="color" value="<? echo $_REQUEST['color']; ?>" />

                        <p class="submit"><input type="submit" name="commit" id="submit" value="Delete Color Image">&nbsp;&nbsp;<input type="submit" id="cancel" name="commit" value="Cancel"></p>

                    </form>
                </div>

            </div>

<?php
}
?>