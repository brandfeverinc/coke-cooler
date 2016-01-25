<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

require("../inc/classes/SoundTest.php");
require("../inc/classes/Sound.php");
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
	$objSoundTest = new SoundTest($id);
    $objSound = new Sound($objSoundTest->SoundId);

	header("Location:soundtest_list.php?cat=" . $_REQUEST['cat'] . "&sound=" . $objSound->Id);
	exit;
}

if ($_POST['commit'] == "Delete Sound Test") {

	$objSoundTest = new SoundTest($id);
    $objSound = new Sound($objSoundTest->SoundId);
	$objSoundTest->Delete($id);

	header("Location:soundtest_list.php?cat=" . $_REQUEST['cat'] . "&sound=" . $objSound->Id);
	exit;
}

$objSoundTest = new SoundTest($id);
$objSound = new Sound($objSoundTest->SoundId);

include("includes/pagetemplate.php");

function PageContent() {
	global $objSoundTest;
	global $objSound;
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
                $aLabels[2] = 'Sound List';
                $aLinks[2] = 'sound_list.php';
                $aLabels[3] = 'Sound Tests';
                $aLinks[3] = 'soundtest_list.php?cat=' . $_REQUEST['cat'] . '&sound=' . $_REQUEST['sound'];
                $aLabels[4] = 'Delete Sound Test';
                $aLinks[4] = '';
                echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
                ?>

                <p class="larger botspace heading">Sound Test Delete</p>

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
                    <form method="post" action="soundtest_delete.php" id="delete-form">
                        <p>Are you sure you want to delete this sound test? This is irreversible.</p>
                        <p>&nbsp;</p>
                	    <table>
                	        <tr>
                	            <td><strong>Image: </strong></td>
                	            <td style="width: 20px;">&nbsp;</td>
                	            <td><img src="/<?php echo $objSoundTest->GetPath() . $objSoundTest->ImageFile65db; ?>" style="max-width: 200px;" /></td>
                	        </tr>
                	    </table>
                        <p>&nbsp;</p>
                      	<input type="hidden" name="id" value="<? echo $id; ?>" />
                      	<input type="hidden" name="cat" value="<? echo $cat; ?>" />
                      	<input type="hidden" name="sound" value="<? echo $sound; ?>" />

                        <p class="submit"><input type="submit" name="commit" id="submit" value="Delete Sound Test">&nbsp;&nbsp;<input type="submit" id="cancel" name="commit" value="Cancel"></p>

                    </form>
                </div>

            </div>

<?php
}
?>