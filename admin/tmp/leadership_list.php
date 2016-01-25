<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");
include("../inc/classes/Leadership.php");
include("../inc/classes/Helpers.php");

if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'sort') {
    $objLeadership = new Leadership();
    $objLeadership->SortValues(substr($_REQUEST['action'],1), substr($_REQUEST['action'],0,1));
    unset($objLeadership);
}
elseif (isset($_REQUEST['dragsort'])) {
    // handle drag sort ajax request:
    $objLeadership = new Leadership($_REQUEST['id']);
    $new_sort_order = ($_REQUEST['idx'] * 10) + 5;
    if ($new_sort_order > $objLeadership->SortOrder) {
        $new_sort_order += 10; // increasing sort order so must add another 10
    }
    $objLeadership->SortOrder = $new_sort_order;
    $objLeadership->Update();
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
                        url: 'leadership_list.php',
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
                $aLabels[1] = 'Leadership';
                $aLinks[1] = '';
                echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
                ?>

                <div class="bigbotspace flex-container space-between">
                    <p class="larger auto heading">Leadership</p>
                    <button class=" "><a href="leadership_admin.php" class="button_link">Add New Leadership</a></button>
                </div>
            </div>
    
            <div class="layout">
    
                <table class="tablestyle" id="list_table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Title</th>
                            <th>Image</th>
                            <th class="mid">Order</th>
                            <th class="mid">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $objLeadership = new Leadership();
                        $oLeadership = $objLeadership->GetAllLeadership('sort_order');
                        foreach ($oLeadership as $leadership) {
                            echo '<tr id="img_' . $leadership->Id . '">' . PHP_EOL;
                            echo '<td style="vertical-align: top;">' . $leadership->Id . '</td>' . PHP_EOL;
                            echo '<td style="vertical-align: top;">' . $leadership->LeaderName . '</td>' . PHP_EOL;
                            echo '<td style="vertical-align: top;">' . $leadership->LeaderTitle . '</td>' . PHP_EOL;
                            echo '<td>';
                            if ($leadership->ImageFile != '') {
                            	echo '<img src="/' . $objLeadership->GetPath() . $leadership->ImageFile . '" width="100" />';
                            }
                            else {
                            	echo '&nbsp;';
                            }
                            echo '</td>' . PHP_EOL;
                            echo '<td class="mid" style="vertical-align: top;"><img src="img/arrow-up-down.png" /></td>' . PHP_EOL;
                            echo '<td class="mid" style="vertical-align: top;"><a href="leadership_admin.php?id=' . $leadership->Id . '"><img src="img/edit-icon.png" /></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="leadership_delete.php?id=' . $leadership->Id . '"><img src="img/delete-icon.png" /></a></td>' . PHP_EOL;
                            echo '</tr>' . PHP_EOL;
                        }
                        ?>
                    </tbody>
                </table>
    
            </div> <!-- layout -->
    
<?php 
}
?>