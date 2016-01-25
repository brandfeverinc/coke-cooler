<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");
include("../inc/classes/Item.php");
include("../inc/classes/ItemPresentation.php");
include("../inc/classes/ItemPresentationImage.php");
include("../inc/classes/Category.php");
include("../inc/classes/Helpers.php");

if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'sort') {
    $objItemPresentation = new ItemPresentation();
    $objItemPresentation->SortValues(substr($_REQUEST['action'],1), substr($_REQUEST['action'],0,1));
    unset($objItem);
}
elseif (isset($_REQUEST['dragsort'])) {
    // handle drag sort ajax request:
    $objItemPresentation = new ItemPresentation($_REQUEST['id']);
    $new_sort_order = ($_REQUEST['idx'] * 10) + 5;
    if ($new_sort_order > $objItemPresentation->SortOrder) {
        $new_sort_order += 10; // increasing sort order so must add another 10
    }
    $objItemPresentation->SortOrder = $new_sort_order;
    $objItemPresentation->update();
    echo "success";
    exit; // just exit since this is an ajax request
}

$objCategory = new Category($_REQUEST['cat']);
$objItem = new Item($_REQUEST['item']);

include("includes/pagetemplate.php");

function PageContent() {
    global $objCategory;
    global $objItem;
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
                        url: 'itempresentation_list.php',
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
                $aLabels[1] = $objCategory->CategoryName;
                $aLinks[1] = 'category_list.php?';
                $aLabels[2] = $objItem->ItemName;
                $aLinks[2] = 'item_list.php?cat=' . $_REQUEST['cat'];
                $aLabels[3] = 'Item Presentations';
                $aLinks[3] = '';
                echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
                ?>

                <div class="bigbotspace flex-container space-between">
                    <p class="larger auto heading"><?php echo $objCategory->CategoryName; ?></p>
                    <a href="itempresentation_admin.php?cat=<?php echo $objCategory->Id; ?>&item=<?php echo $_REQUEST['item']; ?>" class="button_link"><button class="">Add New Item Presentation</button></a>
                </div>
            </div>
    
            <div class="layout">
    
                <table class="tablestyle" id="list_table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Thumbnail Image</th>
                            <th>Page Images</th>
                            <th class="mid">Order</th>
                            <th class="mid">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $objItemPresentation = new ItemPresentation();
                        $oItemPresentation = $objItemPresentation->getAllItemPresentationByItemId($_REQUEST['item']);
                        foreach ($oItemPresentation as $itempresentation) {
                            echo '<tr id="img_' . $itempresentation->Id . '">' . PHP_EOL;
                            echo '<td>' . $itempresentation->Id . '</td>' . PHP_EOL;
                            echo '<td>' . $itempresentation->ItemPresentationName . '</td>' . PHP_EOL;
                            echo '<td>';
                            if ($itempresentation->ItemPresentationThumbnailUrl != '') {
                                echo '<img src="/' . $itempresentation->GetPath() . $itempresentation->ItemPresentationThumbnailUrl . '" style="width:80px;">';
                            }
                            echo '</td>' . PHP_EOL;
                            echo '<td>';
                            $objItemPresentationImage = new ItemPresentationImage();
                            echo '(<a href="itempresentationimage_list.php?cat=' . $_REQUEST['cat'] . '&item=' . $_REQUEST['item'] . '&itempres=' . $itempresentation->Id . '">' . $objItemPresentationImage->GetItemPresentationImageCountByItemPresentationId($itempresentation->Id) . '</a>)';
                            echo '</td>' . PHP_EOL;
                            echo '<td class="mid"><img src="img/arrow-up-down.png" /></td>' . PHP_EOL;
                            echo '<td class="mid"><a href="itempresentation_admin.php?cat=' . $_REQUEST['cat'] . '&item=' . $_REQUEST['item'] . '&id=' . $itempresentation->Id . '"><img src="img/edit-icon.png" /></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="itempresentation_delete.php?cat=' . $_REQUEST['cat'] . '&item=' . $_REQUEST['item'] . '&id=' . $itempresentation->Id . '"><img src="img/delete-icon.png" /></a></td>' . PHP_EOL;
                            echo '</tr>' . PHP_EOL;
                        }
                        ?>
                    </tbody>
                </table>
    
            </div> <!-- layout -->
    
<?php 
}
?>