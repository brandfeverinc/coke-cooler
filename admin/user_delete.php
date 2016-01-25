<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

require("../inc/classes/SystemUser.php");
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
	header("Location:user_list.php");
	exit;
}

if ($_POST['commit'] == "Delete User") {

	$objSystemUser = new SystemUser();
	$objSystemUser->delete($id);

	header("Location:user_list.php");
	exit;
}

$objSystemUser = new SystemUser($id);

include("includes/pagetemplate.php");

function PageContent() {
	global $objSystemUser;
	global $id;
?>

            <div class="layout laymidwidth">

                <?php
                $aLabels = array();
                $aLinks = array();
                $aLabels[0] = 'Home';
                $aLinks[0] = 'mainpage.php';
                $aLabels[1] = 'Users';
                $aLinks[1] = 'user_list.php';
                $aLabels[2] = 'Delete User';
                $aLinks[2] = '';
                echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
                ?>

                <p class="larger2 botspace heading">User Delete</p>

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
                    <form method="post" action="user_delete.php" id="delete-form">
                        <p>Are you sure you want to delete this user? This is irreversible.</p>
                        <p>&nbsp;</p>
                	    <table>
                	        <tr>
                	            <td><strong>Name: </strong></td>
                	            <td style="width: 20px;">&nbsp;</td>
                	            <td><? echo($objSystemUser->FirstName . ' ' . $objSystemUser->LastName); ?></td>
                	        </tr>
                	    </table>
                        <p>&nbsp;</p>
                      	<input type="hidden" name="id" value="<?php echo $id; ?>" />

                        <p class="submit"><input type="submit" name="commit" id="submit" value="Delete User">&nbsp;&nbsp;<input type="submit" id="cancel" name="commit" value="Cancel"></p>

                    </form>
                </div>

            </div>

<?php
}
?>