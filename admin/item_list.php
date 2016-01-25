<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");
include("../inc/classes/Item.php");
include("../inc/classes/ItemImage.php");
include("../inc/classes/ItemInfoImage.php");
include("../inc/classes/ItemVideo.php");
include("../inc/classes/ItemGalleryImage.php");
include("../inc/classes/ItemPresentation.php");
include("../inc/classes/Category.php");
include("../inc/classes/Helpers.php");

if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'sort') {
    $objItem = new Item();
    $objItem->SortValues(substr($_REQUEST['action'],1), substr($_REQUEST['action'],0,1));
    unset($objItem);
}
elseif (isset($_REQUEST['dragsort'])) {
    // handle drag sort ajax request:
    $objItem = new Item($_REQUEST['id']);
    $new_sort_order = ($_REQUEST['idx'] * 10) + 5;
    if ($new_sort_order > $objItem->SortOrder) {
        $new_sort_order += 10; // increasing sort order so must add another 10
    }
    $objItem->SortOrder = $new_sort_order;
    $objItem->update();
    echo "success";
    exit; // just exit since this is an ajax request
}

$category = new Category($_REQUEST['cat']);

include("includes/pagetemplate.php");

function PageContent() {
    global $category;
?>
    
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                // fix to preserve width of cells
                var fixHelper = function(e, ui) {
                    ui.children().each(function() {
                        $(this).width($(this).width());
                    });
                    return ui;
                };
                var saveIndex = function(e, ui) {
                    //alert("New position: " + ui.item.index());
                    //alert("Image Id: " + ui.item.attr("id"));
                    id = ui.item.attr("id").replace("img_", "");
                    $.ajax({
                        url: 'item_list.php',
                        data: {
                            dragsort: 1,
                            idx: ui.item.index(),
                            id: id
                        },
                        type: 'POST',
                        dataType: 'html',
                        success: function (data) {
                            //alert("done");
                        },
                        error: function (xhr, status) {
                            alert('Sorry, there was a problem!');
                        }
                    });
                }; // end saveIndex
    
                $("#list_table tbody").sortable({
                    helper: fixHelper,
                    stop: saveIndex
                }).disableSelection();
    
            }); // end document.ready
        </script>
        <style>
            .icon-resize-vertical:hover {
                cursor:grab;
            }
        </style>        
    
            <div class="layout center-flex">

                <?php
                $aLabels = array();
                $aLinks = array();
                $aLabels[0] = 'Home';
                $aLinks[0] = 'mainpage.php';
                $aLabels[1] = $category->CategoryName;
                $aLinks[1] = '';
                echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
                ?>

                <div class="bigbotspace flex-container space-between">
                    <p class="larger auto heading"><?php echo $category->CategoryName; ?></p>
                    <a href="item_admin.php?cat=<?php echo $category->Id; ?>" class="button_link"><button class="">Add New Item</button></a>
                </div>
            </div>
    
            <div class="layout">
    
                <table class="tablestyle" id="list_table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Item<br />Images</th>
                            <th>Item<br />Info</th>
                            <th>Item<br />Info<br />Images</th>
                            <th>Videos</th>
                            <th>Gallery Images</th>
                            <th>Presentations</th>
                            <th class="mid">Order</th>
                            <th class="mid">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $objItem = new Item();
                        $oItem = $objItem->getAllItemByCategoryId($category->Id, 'sort_order');
                        foreach ($oItem as $item) {
                            echo '<tr id="img_' . $item->Id . '">' . PHP_EOL;
                            echo '<td>' . $item->Id . '</td>' . PHP_EOL;
                            echo '<td>' . $item->ItemName . '</td>' . PHP_EOL;
                            echo '<td style="text-align: center;">';
                                $objItemImage = new ItemImage();
                                echo '(<a href="itemimage_list.php?cat=' . $_REQUEST['cat'] . '&item=' . $item->Id . '">' . $objItemImage->GetItemImageCountByItemId($item->Id) . '</a>)';
                            echo '</td>' . PHP_EOL;
                            echo '<td style="text-align: center;">';
                            echo '<a href="iteminfo_list.php?cat=' . $_REQUEST['cat'] . '&item=' . $item->Id . '"><img src="img/edit-icon.png" /></a>';
                            echo '</td>' . PHP_EOL;
                            echo '<td style="text-align: center;">';
                                $objItemInfoImage = new ItemInfoImage();
                                echo '(<a href="iteminfoimage_list.php?cat=' . $_REQUEST['cat'] . '&item=' . $item->Id . '">' . $objItemInfoImage->GetItemInfoImageCountByItemId($item->Id) . '</a>)';
                            echo '</td>' . PHP_EOL;
                            echo '<td style="text-align: center;">';
                                $objItemVideo = new ItemVideo();
                                echo '(<a href="itemvideo_list.php?cat=' . $_REQUEST['cat'] . '&item=' . $item->Id . '">' . $objItemVideo->GetItemVideoCountByItemId($item->Id) . '</a>)';
                            echo '</td>' . PHP_EOL;
                            echo '<td style="text-align: center;">';
                                $objItemGalleryImage = new ItemGalleryImage();
                                echo '(<a href="itemgalleryimage_list.php?cat=' . $_REQUEST['cat'] . '&item=' . $item->Id . '">' . $objItemGalleryImage->GetItemGalleryImageCountByItemId($item->Id) . '</a>)';
                            echo '</td>' . PHP_EOL;
                            echo '<td style="text-align: center;">';
                                $objItemPresentation = new ItemPresentation();
                                echo '(<a href="itempresentation_list.php?cat=' . $_REQUEST['cat'] . '&item=' . $item->Id . '">' . $objItemPresentation->GetItemPresentationCountByItemId($item->Id) . '</a>)';
                            echo '</td>' . PHP_EOL;
                            echo '<td class="mid"><img src="img/arrow-up-down.png" /></td>' . PHP_EOL;
                            echo '<td class="mid"><a href="item_admin.php?id=' . $item->Id . '"><img src="img/edit-icon.png" /></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="item_delete.php?id=' . $item->Id . '"><img src="img/delete-icon.png" /></a></td>' . PHP_EOL;
                            echo '</tr>' . PHP_EOL;
                        }
                        ?>
                    </tbody>
                </table>
    
            </div> <!-- layout -->
    
<?php 
}
?>