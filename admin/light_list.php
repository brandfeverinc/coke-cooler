<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");
include("../inc/classes/Light.php");
include("../inc/classes/LightImage.php");
include("../inc/classes/LightTest.php");
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
                $aLabels[2] = 'Light';
                $aLinks[2] = '';
                echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
                ?>

                <div class="bigbotspace flex-container space-between">
                    <p class="larger auto heading">Light</p>
                    <!--
                    <a href="category_admin.php" class="button_link"><button class="">Add New Light</button></a>
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
                        $objLight = new Light();
                        $oLight = $objLight->GetAllLightByCategoryId($_REQUEST['cat']);
                        $objLightImage = new LightImage();
                        $objLightTest = new LightTest();
                        foreach ($oLight as $light) {
                            echo '<tr id="img_' . $light->Id . '">' . PHP_EOL;
                            echo '<td>' . $light->Id . '</td>' . PHP_EOL;
                            echo '<td>' . substr($light->OverviewText,0,200) . '</td>' . PHP_EOL;
                            echo '<td>(<a href="lightimage_list.php?cat=' . $_REQUEST['cat'] . '&light=' . $light->Id . '">' . $objLightImage->getCountLightImageByLightId($light->Id) . '</a>)</td>' . PHP_EOL;
                            echo '<td>(<a href="lighttest_list.php?cat=' . $_REQUEST['cat'] . '&light=' . $light->Id . '">' . $objLightTest->getCountLightTestByLightId($light->Id) . '</a>)</td>' . PHP_EOL;
                            echo '<td class="mid"><a href="light_admin.php?cat=' . $_REQUEST['cat'] . '&id=' . $light->Id . '"><img src="img/edit-icon.png" /></a></td>' . PHP_EOL;
                            echo '</tr>' . PHP_EOL;
                        }
                        ?>
                    </tbody>
                </table>
    
            </div> <!-- layout -->
    
<?php 
}
?>