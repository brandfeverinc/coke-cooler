<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");
include("../inc/classes/Sound.php");
include("../inc/classes/SoundImage.php");
include("../inc/classes/SoundTest.php");
include("../inc/classes/Helpers.php");

include("includes/pagetemplate.php");

function PageContent() {
?>
    
            <div class="layout center-flex">

                <?php
                $aLabels = array();
                $aLinks = array();
                $aLabels[0] = 'Home';
                $aLinks[0] = 'mainpage.php';
                $aLabels[1] = 'Category List';
                $aLinks[1] = 'category_list.php';
                $aLabels[2] = 'Sound';
                $aLinks[2] = '';
                echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
                ?>

                <div class="bigbotspace flex-container space-between">
                    <p class="larger auto heading">Sound</p>
                    <!--
                    <a href="category_admin.php" class="button_link"><button class="">Add New Sound</button></a>
                    -->
                </div>
            </div>
    
            <div class="layout">
    
                <table class="tablestyle" id="list_table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Overview Text</th>
                            <th>Images</th>
                            <th>Tests</th>
                            <th class="mid">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $objSound = new Sound();
                        $oSound = $objSound->GetAllSoundByCategoryId($_REQUEST['cat']);
                        $objSoundImage = new SoundImage();
                        $objSoundTest = new SoundTest();
                        foreach ($oSound as $sound) {
                            echo '<tr id="img_' . $sound->Id . '">' . PHP_EOL;
                            echo '<td>' . $sound->Id . '</td>' . PHP_EOL;
                            echo '<td>' . substr($sound->OverviewText,0,200) . '</td>' . PHP_EOL;
                            echo '<td>(<a href="soundimage_list.php?cat=' . $_REQUEST['cat'] . '&sound=' . $sound->Id . '">' . $objSoundImage->getCountSoundImageBySoundId($sound->Id) . '</a>)</td>' . PHP_EOL;
                            echo '<td>(<a href="soundtest_list.php?cat=' . $_REQUEST['cat'] . '&sound=' . $sound->Id . '">' . $objSoundTest->getCountSoundTestBySoundId($sound->Id) . '</a>)</td>' . PHP_EOL;
                            echo '<td class="mid"><a href="sound_admin.php?cat=' . $_REQUEST['cat'] . '&id=' . $sound->Id . '"><img src="img/edit-icon.png" /></a></td>' . PHP_EOL;
                            echo '</tr>' . PHP_EOL;
                        }
                        ?>
                    </tbody>
                </table>
    
            </div> <!-- layout -->
    
<?php 
}
?>