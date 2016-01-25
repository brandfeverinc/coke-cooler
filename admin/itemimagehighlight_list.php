<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");
include("../inc/classes/Item.php");
include("../inc/classes/ItemImage.php");
include("../inc/classes/ItemImageHighlight.php");
include("../inc/classes/Category.php");
include("../inc/classes/Helpers.php");

if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'sort') {
    $objItemImageHighlight = new ItemImageHighlight();
    $objItemImageHighlight->SortValues(substr($_REQUEST['action'],1), substr($_REQUEST['action'],0,1));
    unset($objItem);
}
elseif (isset($_REQUEST['dragsort'])) {
    // handle drag sort ajax request:
    $objItemImageHighlight = new ItemImageHighlight($_REQUEST['id']);
    $new_sort_order = ($_REQUEST['idx'] * 10) + 5;
    if ($new_sort_order > $objItemImageHighlight->SortOrder) {
        $new_sort_order += 10; // increasing sort order so must add another 10
    }
    $objItemImageHighlight->SortOrder = $new_sort_order;
    $objItemImageHighlight->update();
    echo "success";
    exit; // just exit since this is an ajax request
}

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
                $aLabels[3] = 'Item Images';
                $aLinks[3] = 'itemimage_list.php?cat=' . $_REQUEST['cat'] . '&item=' . $_REQUEST['item'];
                $aLabels[4] = 'Item Highlights';
                $aLinks[4] = '';
                echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
                ?>

                <div class="bigbotspace flex-container space-between">
                    <p class="larger auto heading"><?php echo $objCategory->CategoryName; ?></p>
                    <a href="itemimagehighlight_admin.php?cat=<?php echo $objCategory->Id; ?>&item=<?php echo $_REQUEST['item']; ?>&itemimage=<?php echo $_REQUEST['itemimage']; ?>" class="button_link"><button class="">Add New Item Image Highlight</button></a>
                </div>
            </div>
    
            <div class="layout">
    
                <table class="tablestyle" id="list_table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Left</th>
                            <th>Top</th>
                            <th class="mid">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $objItemImage = new ItemImage($_REQUEST['item']);
                        $objItemImageHighlight = new ItemImageHighlight();
                        $oItemImageHighlight = $objItemImageHighlight->getAllItemImageHighlightByItemImageId($_REQUEST['itemimage']);
                        foreach ($oItemImageHighlight as $itemimagehighlight) {
                            echo '<tr id="img_' . $itemimagehighlight->Id . '">' . PHP_EOL;
                            echo '<td>' . $itemimagehighlight->Id . '</td>' . PHP_EOL;
                            echo '<td>';
                            if ($objItemImage->ItemImageUrl != '') {
                                echo '<img src="/' . $objItemImage->GetPath() . $objItemImage->ItemImageUrl . '" style="width:80px;">';
                            }
                            echo '</td>' . PHP_EOL;
                            echo '<td>' . $itemimagehighlight->HotspotLeft . '</td>' . PHP_EOL;
                            echo '<td>' . $itemimagehighlight->HotspotTop . '</td>' . PHP_EOL;
                            echo '<td class="mid"><a href="itemimagehighlight_admin.php?cat=' . $_REQUEST['cat'] . '&item=' . $_REQUEST['item'] . '&itemimage=' . $_REQUEST['itemimage'] . '&id=' . $itemimagehighlight->Id . '"><img src="img/edit-icon.png" /></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="itemimagehighlight_delete.php?cat=' . $_REQUEST['cat'] . '&item=' . $_REQUEST['item'] . '&itemimage=' . $_REQUEST['itemimage'] . '&id=' . $itemimagehighlight->Id . '"><img src="img/delete-icon.png" /></a></td>' . PHP_EOL;
                            echo '</tr>' . PHP_EOL;
                        }
                        ?>
                    </tbody>
                </table>
    
            </div> <!-- layout -->
    
<?php 
}
?>