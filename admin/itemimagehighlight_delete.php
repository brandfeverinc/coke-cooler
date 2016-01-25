<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

require("../inc/classes/ItemImage.php");
require("../inc/classes/ItemImageHighlight.php");
require("../inc/classes/Item.php");
require("../inc/classes/Category.php");
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
	header("Location:itemimagehighlight_list.php?cat=" . $_REQUEST['cat'] . "&item=" . $_REQUEST['item'] . "&itemimage=" . $_REQUEST['itemimage']);
	exit;
}

if ($_POST['commit'] == "Delete Item Image Highlight") {

    $objItemImageHighlight = new ItemImageHighlight($id);
	$objItemImageHighlight->Delete($id);

	header("Location:itemimagehighlight_list.php?cat=" . $_REQUEST['cat'] . "&item=" . $_REQUEST['item'] . "&itemimage=" . $_REQUEST['itemimage']);
	exit;
}

$objItemImageHighlight = new ItemImageHighlight($id);

include("includes/pagetemplate.php");

function PageContent() {
	global $objItemImageHighlight;
	global $id;

    $itemimage = new ItemImage($_REQUEST['itemimage']);
    $item = new Item($objItemImage->ItemId);
    $objCategory = new Category($item->CategoryId);
?>

            <div class="layout laymidwidth">

                <?php
                $aLabels = array();
                $aLinks = array();
                $aLabels[0] = 'Home';
                $aLinks[0] = 'mainpage.php';
                $aLabels[1] = $objCategory->CategoryName;
                $aLinks[1] = 'category_list.php?';
                $aLabels[2] = 'Items';
                $aLinks[2] = 'item_list.php?cat=' . $_REQUEST['cat'];
                $aLabels[3] = 'Item Images';
                $aLinks[3] = 'itemimage_list.php?cat=' . $_REQUEST['cat'] . '&item=' . $_REQUEST['item'];
                $aLabels[4] = 'Item Highlight List';
                $aLinks[4] = 'itemimagehighlight_list.php?cat=' . $_REQUEST['cat'] . '&item=' . $_REQUEST['item'] . '&itemimage=' . $_REQUEST['itemimage'];
                $aLabels[5] = 'Item Highlight Delete';
                $aLinks[5] = '';
                echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
                ?>

                <p class="larger botspace heading">Item Image Highlight Delete</p>

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
                    <form method="post" action="itemimagehighlight_delete.php" id="delete-form">
                        <p>Are you sure you want to delete this item hotspot? This is irreversible.</p>
                        <p>&nbsp;</p>
                	    <table>
                	        <tr>
                	            <td><strong>Hot Spot Left/Top: </strong></td>
                	            <td style="width: 20px;">&nbsp;</td>
                	            <td><?php echo $objItemImageHighlight->HotspotLeft . ' / ' . $objItemImageHighlight->HotspotTop; ?></td>
                	        </tr>
                	    </table>
                        <p>&nbsp;</p>
                      	<input type="hidden" name="id" value="<? echo $id; ?>" />
                      	<input type="hidden" name="cat" value="<? echo $_REQUEST['cat']; ?>" />
                      	<input type="hidden" name="item" value="<? echo $_REQUEST['item']; ?>" />
                      	<input type="hidden" name="itemimage" value="<? echo $_REQUEST['itemimage']; ?>" />

                        <p class="submit"><input type="submit" name="commit" id="submit" value="Delete Item Image Highlight">&nbsp;&nbsp;<input type="submit" id="cancel" name="commit" value="Cancel"></p>

                    </form>
                </div>

            </div>

<?php
}
?>