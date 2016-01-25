<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

require("../inc/classes/Technology.php");
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
	header("Location:technology_list.php?cat=" . $_REQUEST['cat']);
	exit;
}

if ($_POST['commit'] == "Delete Technology") {

	$objTechnology = new Technology();
	$objTechnology->Delete($id);

	header("Location:technology_list.php?cat=" . $_REQUEST['cat']);
	exit;
}

$objTechnology = new Technology($id);

include("includes/pagetemplate.php");

function PageContent() {
	global $objTechnology;
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
                $aLabels[2] = 'Technology';
                $aLinks[2] = 'technology_list.php?cat=' . $_REQUEST['cat'];
                $aLabels[3] = 'Delete Technology';
                $aLinks[3] = '';
                echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
                ?>

                <p class="larger botspace heading">Technology Delete</p>

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
                    <form method="post" action="technology_delete.php" id="delete-form">
                        <p>Are you sure you want to delete this technology? This is irreversible.</p>
                        <p>&nbsp;</p>
                	    <table>
                	        <tr>
                	            <td><strong>Technology Name: </strong></td>
                	            <td style="width: 20px;">&nbsp;</td>
                	            <td><?php echo $objTechnology->TechnologyName; ?></td>
                	        </tr>
                	    </table>
                        <p>&nbsp;</p>
                      	<input type="hidden" name="id" value="<? echo $id; ?>" />
                       	<input type="hidden" name="cat" value="<? echo $_REQUEST['cat']; ?>" />

                        <p class="submit"><input type="submit" name="commit" id="submit" value="Delete Technology">&nbsp;&nbsp;<input type="submit" id="cancel" name="commit" value="Cancel"></p>

                    </form>
                </div>

            </div>

<?php
}
?>