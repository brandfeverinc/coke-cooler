<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");
include("../inc/classes/Item.php");
include("../inc/classes/ItemInfo.php");
include("../inc/classes/ItemInfoType.php");
include("../inc/classes/Category.php");
include("../inc/classes/Helpers.php");

$objCategory = new Category($_REQUEST['cat']);

include("includes/pagetemplate.php");

function PageContent() {
    global $objCategory;
?>
    
            <div class="layout center-flex">

                <?php
                $aLabels = array();
                $aLinks = array();
                $aLabels[0] = 'Home';
                $aLinks[0] = 'mainpage.php';
                $aLabels[1] = $objCategory->CategoryName;
                $aLinks[1] = 'category_list.php?';
                $aLabels[2] = 'Items';
                $aLinks[2] = 'item_list.php?cat=' . $_REQUEST['cat'];
                $aLabels[3] = 'Item Info List';
                $aLinks[3] = '';
                echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
                ?>

                <div class="bigbotspace flex-container space-between">
                    <p class="larger auto heading"><?php echo $objCategory->CategoryName; ?></p>
                    <!-- a href="itemimage_admin.php?cat=<?php echo $objCategory->Id; ?>&item=<?php echo $_REQUEST['item']; ?>" class="button_link"><button class="">Add New Item Image</button></a -->
                </div>
            </div>
    
            <div class="layout">
    
                <table class="tablestyle" id="list_table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Text</th>
                            <th class="mid">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $objItemInfoType = new ItemInfoType();
                        $oItemInfoType = $objItemInfoType->getAllItemInfoType('item_info_type_name');
                        foreach ($oItemInfoType as $iteminfotype) {
                            $objItemInfo = new ItemInfo();
                            $oItemInfo = $objItemInfo->getAllItemInfoByItemIdItemInfoTypeId($_REQUEST['item'], $iteminfotype->Id);
                            
                            echo '<tr id="img_' . $oItemInfo[0]->Id . '">' . PHP_EOL;
                            echo '<td>' . $oItemInfo[0]->Id . '</td>' . PHP_EOL;
                            echo '<td>' . $iteminfotype->ItemInfoTypeName . '</td>' . PHP_EOL;
                            echo '<td>';
                            if ($oItemInfo[0]->ItemInfo != '') {
                                echo substr($oItemInfo[0]->ItemInfo,0,200);
                            }
                            echo '</td>' . PHP_EOL;
                            echo '<td class="mid"><a href="iteminfo_admin.php?cat=' . $_REQUEST['cat'] . '&item=' . $_REQUEST['item'] . '&infoid=' . $iteminfotype->Id . '&type=' . $iteminfotype->Id . '&id=' . $oItemInfo[0]->Id . '"><img src="img/edit-icon.png" /></a></td>' . PHP_EOL;
                            echo '</tr>' . PHP_EOL;
                        }
                        ?>
                    </tbody>
                </table>
    
            </div> <!-- layout -->
    
<?php 
}
?>