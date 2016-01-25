<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");
include("../inc/classes/DemonstrationItemImage.php");
include("../inc/classes/DemonstrationItem.php");
include("../inc/classes/DemonstrationItemImageHighlight.php");
include("../inc/classes/DemonstrationCategory.php");
include("../inc/classes/Helpers.php");

if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'sort') {
    $objDemonstrationItemImage = new DemonstrationItemImage();
    $objDemonstrationItemImage->SortValues(substr($_REQUEST['action'],1), substr($_REQUEST['action'],0,1));
    unset($objDemonstrationItemImage);
}
elseif (isset($_REQUEST['dragsort'])) {
    // handle drag sort ajax request:
    $objDemonstrationItemImage = new DemonstrationItemImage($_REQUEST['id']);
    $new_sort_order = ($_REQUEST['idx'] * 10) + 5;
    if ($new_sort_order > $objDemonstrationItemImage->SortOrder) {
        $new_sort_order += 10; // increasing sort order so must add another 10
    }
    $objDemonstrationItemImage->SortOrder = $new_sort_order;
    $objDemonstrationItemImage->Update();
    echo "success";
    exit; // just exit since this is an ajax request
}

include("includes/pagetemplate.php");

function PageContent() {
    $demonstration_item = new DemonstrationItem($_REQUEST['demoitem']);
    $demonstration_category = new DemonstrationCategory($demonstration_item->DemonstrationCategoryId);
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
                        url: 'demoitemimage_list.php',
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
                $aLabels[1] = $demonstration_category->DemonstrationCategoryName;
                $aLinks[1] = 'demoitem_list.php?cat=' . $demonstration_category->Id;
                $aLabels[2] = $demonstration_item->DemonstrationItemName . ' Images';
                $aLinks[2] = '';
                echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
                ?>

                <div class="bigbotspace flex-container space-between">
                    <p class="larger auto heading"><?php echo $demonstration_item->DemonstrationItemName; ?> Images</p>
                    <a href="demoitemimage_admin.php?demoitem=<?php echo $_REQUEST['demoitem']; ?>" class="button_link"><button class="">Add New Item Image</button></a>
                </div>
            </div>
    
            <div class="layout">
    
                <table class="tablestyle" id="list_table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Side</th>
                            <th>Image</th>
                            <th>Hotspots</th>
                            <th class="mid">Order</th>
                            <th class="mid">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $objDemonstrationItemImage = new DemonstrationItemImage();
                        $objDemonstrationItemImageHighlight = new DemonstrationItemImageHighlight();
                        if (isset($_REQUEST['demoitem'])) {
                            $oDemonstrationItemImage = $objDemonstrationItemImage->getAllDemonstrationItemImageByDemonstrationItemId($_REQUEST['demoitem']);
                        }
                        else {
                            $oDemonstrationItemImage = $objDemonstrationItemImage->GetAllDemonstrationItemImage('sort_order');
                        }
                        foreach ($oDemonstrationItemImage as $demoitemimage) {
                            echo '<tr id="img_' . $demoitemimage->Id . '">' . PHP_EOL;
                            echo '<td>' . $demoitemimage->Id . '</td>' . PHP_EOL;
                            echo '<td>' . $demoitemimage->DemonstrationItemImageSide . '</td>' . PHP_EOL;
                            echo '<td><img src="/' . $demoitemimage->GetPath() . $demoitemimage->DemonstrationItemImageUrl . '" style="width:200px;"></td>' . PHP_EOL;
                            echo '<td>';
                            $hcount = $objDemonstrationItemImageHighlight->getCountDemonstrationItemImageHighlightByDemonstrationItemImageId($demoitemimage->Id);
                            //echo '<a href="demoitemimagehotspot.php?img=' . $demoitemimage->Id . '">' . $hcount . ' Hotspots</a>';
                            // note: above hotspot admin would be pretty complicated from a user perspective (x, y, dimensions, image).
                            //       therefore, not implementing now. - Jay
                            echo $hcount . ' Hotspots</a>';
                            echo '</td>' . PHP_EOL;
                            echo '<td class="mid"><img src="img/arrow-up-down.png" /></td>' . PHP_EOL;
                            echo '<td class="mid"><a href="demoitemimage_admin.php?id=' . $demoitemimage->Id . '" style="width:200px;"><img src="img/edit-icon.png" /></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="demoitemimage_delete.php?id=' . $demoitemimage->Id . '"><img src="img/delete-icon.png" /></a></td>' . PHP_EOL;
                            echo '</tr>' . PHP_EOL;
                        }
                        ?>
                    </tbody>
                </table>
    
            </div> <!-- layout -->
    
<?php 
}
?>