<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

require("../inc/classes/ItemPresentationImage.php");
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
	header("Location:itempresentationimage_list.php?cat=" . $_REQUEST['cat'] . "&item=" . $_REQUEST['item'] . "&itempres=" . $_REQUEST['itempres']);
	exit;
}

if ($_POST['commit'] == "Delete Item Presentation Image") {

    $objItemPresentationImage = new ItemPresentationImage($id);
	$objItemPresentationImage->Delete($id);

	header("Location:itempresentationimage_list.php?cat=" . $_REQUEST['cat'] . "&item=" . $_REQUEST['item'] . "&itempres=" . $_REQUEST['itempres']);
	exit;
}

$objItemPresentationImage = new ItemPresentationImage($id);

include("includes/pagetemplate.php");

function PageContent() {
	global $objItemPresentationImage;
	global $id;
    $item = new Item($objItemPresentation->ItemId);
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
                $aLabels[3] = 'Item Presentations';
                $aLinks[3] = 'itempresentation_list.php?cat=' . $_REQUEST['cat'] . '&item=' . $_REQUEST['item'];
                $aLabels[4] = 'Item Presentation Images';
                $aLinks[4] = 'itempresentationimage_list.php?cat=' . $_REQUEST['cat'] . '&item=' . $_REQUEST['item'] . '&itempres=' . $_REQUEST['itempres'];
                $aLabels[5] = 'Item Presentation Image Delete';
                $aLinks[5] = '';
                echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
                ?>

                <p class="larger botspace heading">Item Presentation Image Delete</p>

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
                    <form method="post" action="itempresentationimage_delete.php" id="delete-form">
                        <p>Are you sure you want to delete this presentation image? This is irreversible.</p>
                        <p>&nbsp;</p>
                	    <table>
                	        <tr>
                	            <td><strong>Presentation Image: </strong></td>
                	            <td style="width: 20px;">&nbsp;</td>
                	            <td><img src="/<?php echo $objItemPresentationImage->GetPath() . $objItemPresentationImage->ItemPresentationImageUrl; ?>" style="width:200px;"></td>
                	        </tr>
                	    </table>
                        <p>&nbsp;</p>
                      	<input type="hidden" name="id" value="<? echo $id; ?>" />
                      	<input type="hidden" name="cat" value="<? echo $_REQUEST['cat']; ?>" />
                      	<input type="hidden" name="item" value="<? echo $_REQUEST['item']; ?>" />
                      	<input type="hidden" name="itempres" value="<? echo $_REQUEST['itempres']; ?>" />

                        <p class="submit"><input type="submit" name="commit" id="submit" value="Delete Item Presentation Image">&nbsp;&nbsp;<input type="submit" id="cancel" name="commit" value="Cancel"></p>

                    </form>
                </div>

            </div>

<?php
}
?>