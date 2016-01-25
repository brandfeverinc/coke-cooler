<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

require("../inc/classes/IbeaconImage.php");
require("../inc/classes/Ibeacon.php");
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
	$objIbeaconImage = new IbeaconImage($id);
    $objIbeacon = new Ibeacon($objIbeaconImage->IbeaconId);

	header("Location:ibeaconimage_list.php?cat=" . $_REQUEST['cat'] . "&ibeacon=" . $objIbeacon->Id);
	exit;
}

if ($_POST['commit'] == "Delete iBeacon Image") {

	$objIbeaconImage = new IbeaconImage($id);
    $objIbeacon = new Ibeacon($objIbeaconImage->IbeaconId);
	$objIbeaconImage->Delete($id);

	header("Location:ibeaconimage_list.php?cat=" . $_REQUEST['cat'] . "&ibeacon=" . $objIbeacon->Id);
	exit;
}

$objIbeaconImage = new IbeaconImage($id);
$objIbeacon = new Ibeacon($objIbeaconImage->IbeaconId);

include("includes/pagetemplate.php");

function PageContent() {
	global $objIbeaconImage;
	global $objIbeacon;
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
                $aLabels[2] = 'iBeacon List';
                $aLinks[2] = 'ibeacon_list.php';
                $aLabels[3] = 'iBeacon Images';
                $aLinks[3] = 'ibeaconimage_list.php?cat=' . $_REQUEST['cat'] . '&ibeacon=' . $_REQUEST['ibeacon'];
                $aLabels[4] = 'Delete iBeacon Image';
                $aLinks[4] = '';
                echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
                ?>

                <p class="larger botspace heading">iBeacon Image Delete</p>

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
                    <form method="post" action="ibeaconimage_delete.php" id="delete-form">
                        <p>Are you sure you want to delete this iBeacon image? This is irreversible.</p>
                        <p>&nbsp;</p>
                	    <table>
                	        <tr>
                	            <td><strong>Image: </strong></td>
                	            <td style="width: 20px;">&nbsp;</td>
                	            <td><img src="/<?php echo $objIbeaconImage->GetPath() . $objIbeaconImage->ImageFile; ?>" style="max-width: 200px;" /></td>
                	        </tr>
                	    </table>
                        <p>&nbsp;</p>
                      	<input type="hidden" name="id" value="<? echo $id; ?>" />
                      	<input type="hidden" name="ibeacon" value="<? echo $ibeacon; ?>" />

                        <p class="submit"><input type="submit" name="commit" id="submit" value="Delete iBeacon Image">&nbsp;&nbsp;<input type="submit" id="cancel" name="commit" value="Cancel"></p>

                    </form>
                </div>

            </div>

<?php
}
?>