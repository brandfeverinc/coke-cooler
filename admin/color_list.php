<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");
include("../inc/classes/Color.php");
include("../inc/classes/ColorImage.php");
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
                $aLabels[2] = 'Color';
                $aLinks[2] = '';
                echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
                ?>

                <div class="bigbotspace flex-container space-between">
                    <p class="larger auto heading">Color</p>
                    <!--
                    <a href="category_admin.php" class="button_link"><button class="">Add New Color</button></a>
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
                        $objColor = new Color();
                        $oColor = $objColor->GetAllColorByCategoryId($_REQUEST['cat']);
                        $objColorImage = new ColorImage();
                        foreach ($oColor as $color) {
                            echo '<tr id="img_' . $color->Id . '">' . PHP_EOL;
                            echo '<td>' . $color->Id . '</td>' . PHP_EOL;
                            echo '<td>' . substr($color->OverviewText,0,200) . '</td>' . PHP_EOL;
                            echo '<td>(<a href="colorimage_list.php?cat=' . $_REQUEST['cat'] . '&color=' . $color->Id . '">' . $objColorImage->getCountColorImageByColorId($color->Id) . '</a>)</td>' . PHP_EOL;
                            echo '<td class="mid"><a href="color_admin.php?cat=' . $_REQUEST['cat'] . '&id=' . $color->Id . '"><img src="img/edit-icon.png" /></a></td>' . PHP_EOL;
                            echo '</tr>' . PHP_EOL;
                        }
                        ?>
                    </tbody>
                </table>
    
            </div> <!-- layout -->
    
<?php 
}
?>