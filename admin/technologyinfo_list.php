<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");
include("../inc/classes/Technology.php");
include("../inc/classes/TechnologyInfo.php");
include("../inc/classes/TechnologyInfoImage.php");
include("../inc/classes/Helpers.php");

if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'sort') {
    $objTechnologyInfo = new TechnologyInfo();
    $objTechnologyInfo->SortValues(substr($_REQUEST['action'],1), substr($_REQUEST['action'],0,1));
    unset($objItem);
}
elseif (isset($_REQUEST['dragsort'])) {
    // handle drag sort ajax request:
    $objTechnologyInfo = new TechnologyInfo($_REQUEST['id']);
    $new_sort_order = ($_REQUEST['idx'] * 10) + 5;
    if ($new_sort_order > $objTechnologyInfo->SortOrder) {
        $new_sort_order += 10; // increasing sort order so must add another 10
    }
    $objTechnologyInfo->SortOrder = $new_sort_order;
    $objTechnologyInfo->update();
    echo "success";
    exit; // just exit since this is an ajax request
}

$objTechnology = new Technology($_REQUEST['tech']);

include("includes/pagetemplate.php");

function PageContent() {
    global $objTechnology;
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
                        url: 'technologyinfo_list.php',
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
                $aLabels[2] = 'Technology';
                $aLinks[2] = 'technology_list.php?cat=' . $_REQUEST['cat'];
                $aLabels[3] = $objTechnology->TechnologyName;
                $aLinks[3] = '';
                echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
                ?>

                <div class="bigbotspace flex-container space-between">
                    <p class="larger auto heading"><?php echo $objTechnology->TechnologyName; ?></p>
                    <a href="technologyinfo_admin.php?cat=<?php echo $_REQUEST['cat']; ?>&tech=<?php echo $_REQUEST['tech']; ?>" class="button_link"><button class="">Add New Technology Info Item</button></a>
                </div>
            </div>
    
            <div class="layout">
    
                <table class="tablestyle" id="list_table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Template</th>
                            <th>Text</th>
                            <th>Images</th>
                            <th>Ordering</th>
                            <th class="mid">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $objTechnologyInfo = new TechnologyInfo();
                        $oTechnologyInfo = $objTechnologyInfo->getAllTechnologyInfoByTechnologyId($_REQUEST['tech']);
                        foreach ($oTechnologyInfo as $techinfo) {
                            echo '<tr id="img_' . $techinfo->Id . '">' . PHP_EOL;
                            echo '<td>' . $techinfo->Id . '</td>' . PHP_EOL;
                            echo '<td>' . $techinfo->TechnologyInfoName . '</td>' . PHP_EOL;
                            echo '<td>' . $techinfo->TechnologyInfoTemplate . '</td>' . PHP_EOL;
                            echo '<td>';
                            if ($techinfo->TechnologyInfoDescription != '') {
                                echo substr($techinfo->TechnologyInfoDescription,0,200);
                            }
                            echo '</td>' . PHP_EOL;
                            echo '<td style="text-align: center;">';
                            $objTechnologyInfoImage = new TechnologyInfoImage();
                            //if ($techinfo->TechnologyInfoTemplate == 'sound') {
                                echo '(<a href="technologyinfoimage_list.php?cat=' . $_REQUEST['cat'] . '&tech=' . $_REQUEST['tech'] . '&techinfo=' . $techinfo->Id . '">' . $objTechnologyInfoImage->getCountTechnologyInfoImageByTechnologyInfoId($techinfo->Id) . '</a>)';
                            //}
                            //else {
                            //	echo '&nbsp;';
                            //}
                            echo '</td>' . PHP_EOL;
                            echo '<td class="mid"><img src="img/arrow-up-down.png" /></td>' . PHP_EOL;
                            echo '<td class="mid"><a href="technologyinfo_admin.php?cat=' . $_REQUEST['cat'] . '&tech=' . $_REQUEST['tech'] . '&id=' . $techinfo->Id . '"><img src="img/edit-icon.png" /></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="technologyinfo_delete.php?cat=' . $_REQUEST['cat'] . '&tech=' . $_REQUEST['tech'] . '&id=' . $techinfo->Id . '"><img src="img/delete-icon.png" /></a></td>' . PHP_EOL;
                            echo '</tr>' . PHP_EOL;
                        }
                        ?>
                    </tbody>
                </table>
    
            </div> <!-- layout -->
    
<?php 
}
?>