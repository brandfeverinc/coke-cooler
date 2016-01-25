<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");
include("../inc/classes/Ibeacon.php");
include("../inc/classes/IbeaconImage.php");
include("../inc/classes/Helpers.php");

if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'sort') {
    $objIbeaconImage = new IbeaconImage();
    $objIbeaconImage->SortValues(substr($_REQUEST['action'],1), substr($_REQUEST['action'],0,1));
    unset($objIbeaconImage);
}
elseif (isset($_REQUEST['dragsort'])) {
    // handle drag sort ajax request:
    $objIbeaconImage = new IbeaconImage($_REQUEST['id']);
    $new_sort_order = ($_REQUEST['idx'] * 10) + 5;
    if ($new_sort_order > $objIbeaconImage->SortOrder) {
        $new_sort_order += 10; // increasing sort order so must add another 10
    }
    $objIbeaconImage->SortOrder = $new_sort_order;
    $objIbeaconImage->update();
    echo "success";
    exit; // just exit since this is an ajax request
}

$objIbeacon = new Ibeacon($_REQUEST['ibeacon']);

include("includes/pagetemplate.php");

function PageContent() {
    global $objIbeacon;
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
                        url: 'ibeaconimage_list.php',
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
                $aLabels[1] = 'Category List';
                $aLinks[1] = 'category_list.php';
                $aLabels[2] = 'Ibeacon';
                $aLinks[2] = 'ibeacon_list.php?cat=' . $_REQUEST['cat'];
                $aLabels[3] = 'Ibeacon Image List';
                $aList[3] = '';
                echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
                ?>

                <div class="bigbotspace flex-container space-between">
                    <p class="larger auto heading">Ibeacon Images</p>
                    <a href="ibeaconimage_admin.php?cat=<?php echo $_REQUEST['cat']; ?>&ibeacon=<?php echo $objIbeacon->Id; ?>" class="button_link"><button class="">Add New iBeacon Image</button></a>
                </div>
            </div>
    
            <div class="layout">
    
                <table class="tablestyle" id="list_table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th class="mid">Order</th>
                            <th class="mid">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $objIbeaconImage = new IbeaconImage();
                        $oIbeaconImage = $objIbeaconImage->getAllIbeaconImageByIbeaconId($objIbeacon->Id);
                        foreach ($oIbeaconImage as $ibeaconimage) {
                            echo '<tr id="img_' . $ibeaconimage->Id . '">' . PHP_EOL;
                            echo '<td>' . $ibeaconimage->Id . '</td>' . PHP_EOL;
                            echo '<td><img src="/' . $objIbeaconImage->GetPath() . $ibeaconimage->ImageFile . '" style="max-width: 200px;" /></td>' . PHP_EOL;
                            echo '<td class="mid"><img src="img/arrow-up-down.png" /></td>' . PHP_EOL;
                            echo '<td class="mid"><a href="ibeaconimage_admin.php?cat=' . $_REQUEST['cat'] . '&ibeacon=' . $_REQUEST['ibeacon'] . '&id=' . $ibeaconimage->Id . '"><img src="img/edit-icon.png" /></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="ibeaconimage_delete.php?ibeacon=' . $_REQUEST['ibeacon'] . '&id=' . $ibeaconimage->Id . '"><img src="img/delete-icon.png" /></a></td>' . PHP_EOL;
                            echo '</tr>' . PHP_EOL;
                        }
                        ?>
                    </tbody>
                </table>
    
            </div> <!-- layout -->
    
<?php 
}
?>