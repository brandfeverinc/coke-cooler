<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");
include("../inc/classes/Technology.php");
include("../inc/classes/TechnologyInfo.php");
include("../inc/classes/Helpers.php");

if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'sort') {
    $objTechnology = new Technology();
    $objTechnology->SortValues(substr($_REQUEST['action'],1), substr($_REQUEST['action'],0,1));
    unset($objTechnology);
}
elseif (isset($_REQUEST['dragsort'])) {
    // handle drag sort ajax request:
    $objTechnology = new Technology($_REQUEST['id']);
    $new_sort_order = ($_REQUEST['idx'] * 10) + 5;
    if ($new_sort_order > $objTechnology->SortOrder) {
        $new_sort_order += 10; // increasing sort order so must add another 10
    }
    $objTechnology->SortOrder = $new_sort_order;
    $objTechnology->Update();
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
                        url: 'technology_list.php',
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
                $aLinks[2] = '';
                echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
                ?>

                <div class="bigbotspace flex-container space-between">
                    <p class="larger auto heading">Technology</p>
                    <a href="technology_admin.php?cat=<?php echo $_REQUEST['cat']; ?>" class="button_link"><button class="">Add New Technology</button></a>
                </div>
            </div>
    
            <div class="layout">
    
                <table class="tablestyle" id="list_table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Technology Name</th>
                            <th>Info Items</th>
                            <th class="mid">Order</th>
                            <th class="mid">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $objTechnology = new Technology();
                        $oTechnology = $objTechnology->GetAllTechnologyBycategoryId($_REQUEST['cat']);
                        $objTechnologyInfo = new TechnologyInfo();
                        foreach ($oTechnology as $technology) {
                            echo '<tr id="img_' . $technology->Id . '">' . PHP_EOL;
                            echo '<td>' . $technology->Id . '</td>' . PHP_EOL;
                            echo '<td>' . $technology->TechnologyName . '</td>' . PHP_EOL;
                            switch ($technology->LinkUrl) {
                                case 'tech-sound.php':
                                    echo '<td class="mid"><a href="sound_list.php?cat=' . $_REQUEST['cat'] . '"><img src="img/edit-icon.png" /></a></td>' . PHP_EOL;
                                    break;
                                case 'tech-light.php':
                                    echo '<td class="mid"><a href="light_list.php?cat=' . $_REQUEST['cat'] . '"><img src="img/edit-icon.png" /></a></td>' . PHP_EOL;
                                    break;
                                case 'tech-standard.php':
                                    echo '<td class="mid">(<a href="technologyinfo_list.php?cat=' . $_REQUEST['cat'] . '&tech=' . $technology->Id . '">' . $objTechnologyInfo->getCountTechnologyInfoByTechnologyId($technology->Id) . '</a>)</td>' . PHP_EOL;
                                    break;
                            }
                            echo '<td class="mid"><img src="img/arrow-up-down.png" /></td>' . PHP_EOL;
                            echo '<td class="mid"><a href="technology_admin.php?cat=' . $_REQUEST['cat'] . '&id=' . $technology->Id . '"><img src="img/edit-icon.png" /></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="technology_delete.php?cat=' . $_REQUEST['cat'] . '&id=' . $technology->Id . '"><img src="img/delete-icon.png" /></a></td>' . PHP_EOL;
                            echo '</tr>' . PHP_EOL;
                        }
                        ?>
                    </tbody>
                </table>
    
            </div> <!-- layout -->
    
<?php 
}
?>