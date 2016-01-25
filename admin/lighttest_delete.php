<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

require("../inc/classes/LightTest.php");
require("../inc/classes/Light.php");
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
	$objLightTest = new LightTest($id);
    $objLight = new Light($objLightTest->LightId);

	header("Location:lighttest_list.php?cat=" . $_REQUEST['cat'] . "&light=" . $objLight->Id);
	exit;
}

if ($_POST['commit'] == "Delete Light Test") {

	$objLightTest = new LightTest($id);
    $objLight = new Light($objLightTest->LightId);
	$objLightTest->Delete($id);

	header("Location:lighttest_list.php?cat=" . $_REQUEST['cat'] . "&light=" . $objLight->Id);
	exit;
}

$objLightTest = new LightTest($id);
$objLight = new Light($objLightTest->LightId);

include("includes/pagetemplate.php");

function PageContent() {
	global $objLightTest;
	global $objLight;
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
                $aLabels[2] = 'Light List';
                $aLinks[2] = 'light_list.php';
                $aLabels[3] = 'Light Tests';
                $aLinks[3] = 'lighttest_list.php?cat=' . $_REQUEST['cat'] . '&light=' . $_REQUEST['light'];
                $aLabels[4] = 'Delete Light Test';
                $aLinks[4] = '';
                echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
                ?>

                <p class="larger botspace heading">Light Test Delete</p>

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
                    <form method="post" action="lighttest_delete.php" id="delete-form">
                        <p>Are you sure you want to delete this light test? This is irreversible.</p>
                        <p>&nbsp;</p>
                	    <table>
                	        <tr>
                	            <td><strong>Image: </strong></td>
                	            <td style="width: 20px;">&nbsp;</td>
                	            <td><img src="/<?php echo $objLightTest->GetPath() . $objLightTest->ImageFileDark; ?>" style="max-width: 200px;" /></td>
                	        </tr>
                	    </table>
                        <p>&nbsp;</p>
                      	<input type="hidden" name="id" value="<? echo $id; ?>" />
                      	<input type="hidden" name="cat" value="<? echo $cat; ?>" />
                      	<input type="hidden" name="light" value="<? echo $light; ?>" />

                        <p class="submit"><input type="submit" name="commit" id="submit" value="Delete Light Test">&nbsp;&nbsp;<input type="submit" id="cancel" name="commit" value="Cancel"></p>

                    </form>
                </div>

            </div>

<?php
}
?>