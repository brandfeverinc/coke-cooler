<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");
include("../inc/classes/Information.php");
include("../inc/classes/Helpers.php");

if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'sort') {
    $objInformation = new Information();
    $objInformation->SortValues(substr($_REQUEST['action'],1), substr($_REQUEST['action'],0,1));
    unset($objInformation);
}
elseif (isset($_REQUEST['dragsort'])) {
    // handle drag sort ajax request:
    $objInformation = new Information($_REQUEST['id']);
    $new_sort_order = ($_REQUEST['idx'] * 10) + 5;
    if ($new_sort_order > $objInformation->SortOrder) {
        $new_sort_order += 10; // increasing sort order so must add another 10
    }
    $objInformation->SortOrder = $new_sort_order;
    $objInformation->Update();
    echo "success";
    exit; // just exit since this is an ajax request
}

include("includes/pagetemplate.php");

function PageContent() {
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
                        url: 'information_list.php',
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
                $aLabels[1] = 'Information';
                $aLinks[1] = '';
                echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
                ?>

                <div class="bigbotspace flex-container space-between">
                    <p class="larger auto heading">Information</p>
                    <a href="information_admin.php" class="button_link"><button class=" ">Add New Information</button></a>
                </div>
            </div>
    
            <div class="layout">
    
                <table class="tablestyle" id="list_table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>File</th>
                            <th class="mid">Order</th>
                            <th class="mid">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $objInformation = new Information();
                        $oInformation = $objInformation->GetAllInformation('sort_order');
                        foreach ($oInformation as $info) {
                            echo '<tr id="img_' . $info->Id . '">' . PHP_EOL;
                            echo '<td>' . $info->Id . '</td>' . PHP_EOL;
                            echo '<td>' . $info->Title . '</td>' . PHP_EOL;
                            echo '<td>/' . $objInformation->GetPath() . $info->PdfFile . '</td>' . PHP_EOL;
                            echo '<td class="mid"><img src="img/arrow-up-down.png" /></td>' . PHP_EOL;
                            echo '<td class="mid"><a href="information_admin.php?id=' . $info->Id . '"><img src="img/edit-icon.png" /></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="information_delete.php?id=' . $info->Id . '"><img src="img/delete-icon.png" /></a></td>' . PHP_EOL;
                            echo '</tr>' . PHP_EOL;
                        }
                        ?>
                    </tbody>
                </table>
    
            </div> <!-- layout -->
    
<?php 
}
?>