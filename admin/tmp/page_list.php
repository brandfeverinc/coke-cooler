<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");
require_once("../inc/classes/Template.php");
require_once("../inc/classes/Page.php");
require_once("../inc/classes/PagePart.php");
require_once("../inc/classes/TemplatePartType.php");
require_once("../inc/classes/Helpers.php");

if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'sort') {
    $objPage = new Page();
    $objPage->SortValues(substr($_REQUEST['action'],1), substr($_REQUEST['action'],0,1));
    unset($objPage);
}
elseif (isset($_REQUEST['dragsort'])) {
    // handle drag sort ajax request:
    $objPage = new Page($_REQUEST['id']);
    $new_sort_order = ($_REQUEST['idx'] * 10) + 5;
    if ($new_sort_order > $objPage->SortOrder) {
        $new_sort_order += 10; // increasing sort order so must add another 10
    }
    $objPage->SortOrder = $new_sort_order;
    $objPage->Update();
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
                        url: 'page_list.php',
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
                $aLabels[1] = 'Site Pages';
                $aLinks[1] = '';
                echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
                ?>

                <div class="bigbotspace flex-container space-between">
                    <p class="larger auto heading">Pages</p>
                    <a href="page_admin.php" class="button_link"><button class=" ">Add New Page</button></a>
                </div>
            </div>
    
            <div class="layout">
    
                <table class="tablestyle" id="list_table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th colspan='2'>Page Title</th>
                            <th>Is Menu</th>
                            <th>Friendly URL</th>
                            <th>Template</th>
                            <th>Published</th>
                            <!-- th>Sorting</th -->
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $objPage = new Page();
                        $oPage = $objPage->GetAllPage('parent_page_id, sort_order');
                        foreach ($oPage as $page) {
                            echo '<tr id="img_' . $page->Id . '">' . PHP_EOL;
                            echo "<td>" . $page->Id . "</td>";
                            if ($page->ParentPageId == '' || $page->ParentPageId == $page->Id) {
                                echo "<td colspan='2'>" . $page->PageTitle . "</td>";
                            }
                            else {
                                echo "<td style='width:20px;'>&nbsp;</td>";
                                echo "<td>" . $page->PageTitle . "</td>";
                            }
                            echo "<td>";
                                if ($page->IsMenu == 1) {
                                    echo 'Yes';
                                }
                                else {
                                    echo 'No';
                                }
                            echo "</td>";
                            echo "<td>" . $page->FriendlyUrl . "</td>";
                            echo "<td>";
                                $objTemplate = new Template($page->TemplateId);
                                echo $objTemplate->Name;
                            echo "</td>";
                            echo "<td>";
                                if ($page->Published == 1) {
                                    echo 'Yes';
                                }
                                else {
                                    echo 'No';
                                }
                            echo "</td>";
                            //echo '<td class="mid"><img src="img/arrow-up-down.png" /></td>' . PHP_EOL;
                            echo '<td class="mid"><a href="page_admin.php?id=' . $page->Id . '"><img src="img/edit-icon.png" /></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="page_delete.php?id=' . $page->Id . '"><img src="img/delete-icon.png" /></a></td>' . PHP_EOL;
                            echo '</tr>' . PHP_EOL;
                        }
                        ?>
                    </tbody>
                </table>
    
            </div> <!-- layout -->
    
<?php 
}
?>