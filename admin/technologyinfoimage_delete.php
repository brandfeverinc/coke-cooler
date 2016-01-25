<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

require("../inc/classes/TechnologyInfoImage.php");
require("../inc/classes/TechnologyInfo.php");
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
	header("Location:technologyinfoimage_list.php?cat=" . $_REQUEST['cat'] . "&tech=" . $_REQUEST['tech'] . '&techinfo=' . $_REQUEST['techinfo']);
	exit;
}

if ($_POST['commit'] == "Delete Technology Info Image") {

    $objTechnologyInfoImage = new TechnologyInfoImage($id);
	$objTechnologyInfoImage->Delete($id);

	header("Location:technologyinfoimage_list.php?cat=" . $_REQUEST['cat'] . "&tech=" . $_REQUEST['tech'] . '&techinfo=' . $_REQUEST['techinfo']);
	exit;
}

$objTechnologyInfoImage = new TechnologyInfoImage($id);

include("includes/pagetemplate.php");

function PageContent() {
	global $objTechnologyInfoImage;
	global $id;
    $objTechnology = new Technology($_REQUEST['tech']);
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
                $aLabels[3] = $objTechnology->TechnologyName;
                $aLinks[3] = 'technologyinfo_list.php?cat=' . $_REQUEST['cat'] . '&tech=' . $_REQUEST['tech'];
                $aLabels[4] = 'Technology Info Images';
                $aLinks[4] = 'technologyinfoimage_list.php?cat=' . $_REQUEST['cat'] . '&tech=' . $_REQUEST['tech'] . '&techimage=' . $_REQUEST['techimage'];
                $aLabels[5] = 'Technology Info Image Delete';
                $aLinks[5] = '';
                echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
                ?>

                <p class="larger botspace heading">Technology Image Delete</p>

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
                    <form method="post" action="technologyinfoimage_delete.php" id="delete-form">
                        <p>Are you sure you want to delete this technology info image? This is irreversible.</p>
                        <p>&nbsp;</p>
                	    <table>
							<tr>
								<td class="table-label" align="right">Technology Info Image:&nbsp;</td>
								<td><?php echo $objTechnologyInfoImage->TechnologyInfoImageUrl ?></td>
							</tr>
                	    </table>
                        <p>&nbsp;</p>
                      	<input type="hidden" name="id" value="<? echo $id; ?>" />
                      	<input type="hidden" name="cat" value="<? echo $_REQUEST['cat']; ?>" />
                      	<input type="hidden" name="tech" value="<? echo $_REQUEST['tech']; ?>" />
                      	<input type="hidden" name="techinfo" value="<? echo $_REQUEST['techinfo']; ?>" />

                        <p class="submit"><input type="submit" name="commit" id="submit" value="Delete Technology Info Image">&nbsp;&nbsp;<input type="submit" id="cancel" name="commit" value="Cancel"></p>

                    </form>
                </div>

            </div>

<?php
}
?>