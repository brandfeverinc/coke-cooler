<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

require("../inc/classes/Leadership.php");
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
	header("Location:leadership_list.php");
	exit;
}

if ($_POST['commit'] == "Delete Leadership") {

	$objLeadership = new Leadership();
	$objLeadership->Delete($id);

	header("Location:leadership_list.php");
	exit;
}

$objLeadership = new Leadership($id);

include("includes/pagetemplate.php");

function PageContent() {
	global $objLeadership;
	global $id;
?>

            <div class="layout laymidwidth">

                <?php
                $aLabels = array();
                $aLinks = array();
                $aLabels[0] = 'Home';
                $aLinks[0] = 'mainpage.php';
                $aLabels[1] = 'Leadership';
                $aLinks[1] = 'leadership_list.php';
                $aLabels[2] = 'Delete Leadership';
                $aLinks[2] = '';
                echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
                ?>

                <p class="larger botspace heading">Leadership Delete</p>

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
                    <form method="post" action="leadership_delete.php" id="delete-form">
                        <p>Are you sure you want to delete this leadership? This is irreversible.</p>
                        <p>&nbsp;</p>
                	    <table>
                	        <tr>
                	            <td><strong>Name: </strong></td>
                	            <td style="width: 20px;">&nbsp;</td>
                	            <td><? echo($objLeadership->LeaderName); ?></td>
                	        </tr>
                	    </table>
                        <p>&nbsp;</p>
                      	<input type="hidden" name="id" value="<? echo $id; ?>" />

                        <p class="submit"><input type="submit" name="commit" id="submit" value="Delete Leadership">&nbsp;&nbsp;<input type="submit" id="cancel" name="commit" value="Cancel"></p>

                    </form>
                </div>

            </div>

<?php
}
?>