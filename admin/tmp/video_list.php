<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");
include("../inc/classes/Video.php");
include("../inc/classes/Helpers.php");

if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'sort') {
    $objVideo = new Video();
    $objVideo->SortValues(substr($_REQUEST['action'],1), substr($_REQUEST['action'],0,1));
    unset($objVideo);
}
elseif (isset($_REQUEST['dragsort'])) {
    // handle drag sort ajax request:
    $objVideo = new Video($_REQUEST['id']);
    $new_sort_order = ($_REQUEST['idx'] * 10) + 5;
    if ($new_sort_order > $objVideo->SortOrder) {
        $new_sort_order += 10; // increasing sort order so must add another 10
    }
    $objVideo->SortOrder = $new_sort_order;
    $objVideo->Update();
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
                        url: 'video_list.php',
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
                $aLabels[1] = 'Videos';
                $aLinks[1] = '';
                echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
                ?>

                <div class="bigbotspace flex-container space-between">
                    <p class="larger auto heading">Videos</p>
                    <button class=" "><a href="video_admin.php" class="button_link">Add New Video</a></button>
                </div>
            </div>
    
            <div class="layout">
    
                <table class="tablestyle" id="list_table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Image</th>
                            <th class="mid">Order</th>
                            <th class="mid">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $objVideo = new Video();
                        $oVideo = $objVideo->GetAllVideo('sort_order');
                        foreach ($oVideo as $video) {
                            echo '<tr id="img_' . $video->Id . '">' . PHP_EOL;
                            echo '<td style="vertical-align: top;">' . $video->Id . '</td>' . PHP_EOL;
                            echo '<td style="vertical-align: top;">' . $video->Title . '</td>' . PHP_EOL;
                            echo '<td><img src="/' . $objVideo->GetPath() . $video->ImageFile . '" width="200px;" /></td>' . PHP_EOL;
                            echo '<td class="mid" style="vertical-align: top;"><img src="img/arrow-up-down.png" /></td>' . PHP_EOL;
                            echo '<td class="mid" style="vertical-align: top;"><a href="video_admin.php?id=' . $video->Id . '"><img src="img/edit-icon.png" /></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="video_delete.php?id=' . $video->Id . '"><img src="img/delete-icon.png" /></a></td>' . PHP_EOL;
                            echo '</tr>' . PHP_EOL;
                        }
                        ?>
                    </tbody>
                </table>
    
            </div> <!-- layout -->
    
<?php 
}
?>