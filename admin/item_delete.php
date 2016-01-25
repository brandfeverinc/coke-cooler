<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

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
	$objItem = new Item($id);
    $category = new Category($objItem->CategoryId);

	header("Location:item_list.php?cat=" . $category->Id);
	exit;
}

if ($_POST['commit'] == "Delete Item") {

	$objItem = new Item($id);
    $category = new Category($objItem->CategoryId);
	$objItem->Delete($id);

	header("Location:item_list.php?cat=" . $category->Id);
	exit;
}

$objItem = new Item($id);
$category = new Category($objItem->CategoryId);

include("includes/pagetemplate.php");

function PageContent() {
	global $objItem;
	global $category;
	global $id;
?>

            <div class="layout laymidwidth">

                <?php
                $aLabels = array();
                $aLinks = array();
                $aLabels[0] = 'Home';
                $aLinks[0] = 'mainpage.php';
                $aLabels[1] = $category->CategoryName;
                $aLinks[1] = 'item_list.php?cat=' . $category->Id;
                $aLabels[2] = 'Delete Item';
                $aLinks[2] = '';
                echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
                ?>

                <p class="larger botspace heading">Item Delete</p>

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
                    <form method="post" action="item_delete.php" id="delete-form">
                        <p>Are you sure you want to delete this item? This is irreversible.</p>
                        <p>&nbsp;</p>
                	    <table>
                	        <tr>
                	            <td><strong>Item Name: </strong></td>
                	            <td style="width: 20px;">&nbsp;</td>
                	            <td><?php echo $objItem->ItemName; ?></td>
                	        </tr>
                	    </table>
                        <p>&nbsp;</p>
                      	<input type="hidden" name="id" value="<? echo $id; ?>" />

                        <p class="submit"><input type="submit" name="commit" id="submit" value="Delete Item">&nbsp;&nbsp;<input type="submit" id="cancel" name="commit" value="Cancel"></p>

                    </form>
                </div>

            </div>

<?php
}
?>