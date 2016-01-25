<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");
include("../inc/classes/Ibeacon.php");
include("../inc/classes/IbeaconImage.php");
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
                $aLabels[2] = 'iBeacon';
                $aLinks[2] = '';
                echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
                ?>

                <div class="bigbotspace flex-container space-between">
                    <p class="larger auto heading">iBeacon</p>
                    <!--
                    <a href="category_admin.php" class="button_link"><button class="">Add New Ibeacon</button></a>
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
                            <th class="mid">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $objIbeacon = new Ibeacon();
                        $oIbeacon = $objIbeacon->GetAllIbeaconByCategoryId($_REQUEST['cat']);
                        $objIbeaconImage = new IbeaconImage();
                        foreach ($oIbeacon as $ibeacon) {
                            echo '<tr id="img_' . $ibeacon->Id . '">' . PHP_EOL;
                            echo '<td>' . $ibeacon->Id . '</td>' . PHP_EOL;
                            echo '<td>' . substr($ibeacon->OverviewText,0,200) . '</td>' . PHP_EOL;
                            echo '<td>(<a href="ibeaconimage_list.php?cat=' . $_REQUEST['cat'] . '&ibeacon=' . $ibeacon->Id . '">' . $objIbeaconImage->getCountIbeaconImageByIbeaconId($ibeacon->Id) . '</a>)</td>' . PHP_EOL;
                            echo '<td class="mid"><a href="ibeacon_admin.php?cat=' . $_REQUEST['cat'] . '&id=' . $ibeacon->Id . '"><img src="img/edit-icon.png" /></a></td>' . PHP_EOL;
                            echo '</tr>' . PHP_EOL;
                        }
                        ?>
                    </tbody>
                </table>
    
            </div> <!-- layout -->
    
<?php 
}
?>