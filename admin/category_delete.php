<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

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
	header("Location:category_list.php");
	exit;
}

if ($_POST['commit'] == "Delete Category") {

	$objCategory = new Category();
	$objCategory->Delete($id);

	header("Location:category_list.php");
	exit;
}

$objCategory = new Category($id);

include("includes/pagetemplate.php");

function PageContent() {
	global $objCategory;
	global $id;
?>

            <div class="layout laymidwidth">

                <?php
                $aLabels = array();
                $aLinks = array();
                $aLabels[0] = 'Home';
                $aLinks[0] = 'mainpage.php';
                $aLabels[1] = 'Categories';
                $aLinks[1] = 'category_list.php';
                $aLabels[2] = 'Delete Category';
                $aLinks[2] = '';
                echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
                ?>

                <p class="larger botspace heading">Category Delete</p>

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
                    <form method="post" action="category_delete.php" id="delete-form">
                        <p>Are you sure you want to delete this category? This is irreversible.</p>
                        <p>&nbsp;</p>
                	    <table>
                	        <tr>
                	            <td><strong>Category Name: </strong></td>
                	            <td style="width: 20px;">&nbsp;</td>
                	            <td><?php echo $objCategory->CategoryName; ?></td>
                	        </tr>
                	    </table>
                        <p>&nbsp;</p>
                      	<input type="hidden" name="id" value="<? echo $id; ?>" />

                        <p class="submit"><input type="submit" name="commit" id="submit" value="Delete Category">&nbsp;&nbsp;<input type="submit" id="cancel" name="commit" value="Cancel"></p>

                    </form>
                </div>

            </div>

<?php
}
?>