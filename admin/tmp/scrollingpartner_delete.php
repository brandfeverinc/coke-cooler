<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

require("../inc/classes/ScrollingPartner.php");
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
	header("Location:scrollingpartner_list.php");
	exit;
}

if ($_POST['commit'] == "Delete Scrolling Partner") {

	$objScrollingPartner = new ScrollingPartner();
	$objScrollingPartner->Delete($id);

	header("Location:scrollingpartner_list.php");
	exit;
}

$objScrollingPartner = new ScrollingPartner($id);

include("includes/pagetemplate.php");

function PageContent() {
	global $objScrollingPartner;
	global $id;
?>

            <div class="layout laymidwidth">

                <?php
                $aLabels = array();
                $aLinks = array();
                $aLabels[0] = 'Home';
                $aLinks[0] = 'mainpage.php';
                $aLabels[1] = 'Scrolling Partner';
                $aLinks[1] = 'scrollingpartner_list.php';
                $aLabels[2] = 'Delete Scrolling Partner';
                $aLinks[2] = '';
                echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
                ?>

                <p class="larger botspace heading">Scrolling Partner Delete</p>

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
                    <form method="post" action="scrollingpartner_delete.php" id="delete-form">
                        <p>Are you sure you want to delete this partner? This is irreversible.</p>
                        <p>&nbsp;</p>
                	    <table>
                	        <tr>
                	            <td><strong>Name: </strong></td>
                	            <td style="width: 20px;">&nbsp;</td>
                	            <td><? echo($objScrollingPartner->PartnerName); ?></td>
                	        </tr>
                	    </table>
                        <p>&nbsp;</p>
                      	<input type="hidden" name="id" value="<? echo $id; ?>" />

                        <p class="submit"><input type="submit" name="commit" id="submit" value="Delete Scrolling Partner">&nbsp;&nbsp;<input type="submit" id="cancel" name="commit" value="Cancel"></p>

                    </form>
                </div>

            </div>

<?php
}
?>