<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

require("../inc/classes/ItemGalleryImage.php");
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
	header("Location:itemgalleryimage_list.php?cat=" . $_REQUEST['cat'] . "&item=" . $_REQUEST['item']);
	exit;
}

if ($_POST['commit'] == "Delete Item Gallery Image") {

    $objItemGalleryImage = new ItemGalleryImage($id);
	$objItemGalleryImage->Delete($id);

	header("Location:itemgalleryimage_list.php?cat=" . $_REQUEST['cat'] . "&item=" . $_REQUEST['item']);
	exit;
}

$objItemGalleryImage = new ItemGalleryImage($id);

include("includes/pagetemplate.php");

function PageContent() {
	global $objItemGalleryImage;
	global $id;
    $item = new Item($objItemGalleryImage->ItemId);
    $objCategory = new Category($item->CategoryId);
?>

            <div class="layout laymidwidth">

                <?php
                $aLabels = array();
                $aLinks = array();
                $aLabels[0] = 'Home';
                $aLinks[0] = 'mainpage.php';
                $aLabels[1] = $objCategory->CategoryName;
                $aLinks[1] = 'category_list.php';
                $aLabels[2] = 'Items';
                $aLinks[2] = 'item_list.php?cat=' . $_REQUEST['cat'];
                $aLabels[3] = 'Item Gallery Images';
                $aLinks[3] = 'itemgalleryimage_list.php?cat=' . $_REQUEST['cat'] . '&item=' . $_REQUEST['item'];
                $aLabels[4] = 'Item Gallery Image Delete';
                $aLinks[4] = '';
                echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
                ?>

                <p class="larger botspace heading">Item Gallery Image Delete</p>

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
                    <form method="post" action="itemgalleryimage_delete.php" id="delete-form">
                        <p>Are you sure you want to delete this item image? This is irreversible.</p>
                        <p>&nbsp;</p>
                	    <table>
                	        <tr>
                	            <td><strong>Item Gallery Image: </strong></td>
                	            <td style="width: 20px;">&nbsp;</td>
                	            <td><img src="/<?php echo $objItemGalleryImage->GetPath() . $objItemGalleryImage->ItemGalleryImageUrl; ?>" style="width:200px;"></td>
                	        </tr>
                	    </table>
                        <p>&nbsp;</p>
                      	<input type="hidden" name="id" value="<? echo $id; ?>" />
                      	<input type="hidden" name="cat" value="<? echo $_REQUEST['cat']; ?>" />
                      	<input type="hidden" name="item" value="<? echo $_REQUEST['item']; ?>" />

                        <p class="submit"><input type="submit" name="commit" id="submit" value="Delete Item Gallery Image">&nbsp;&nbsp;<input type="submit" id="cancel" name="commit" value="Cancel"></p>

                    </form>
                </div>

            </div>

<?php
}
?>