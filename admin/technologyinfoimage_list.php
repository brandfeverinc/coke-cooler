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
    $objTechnologyInfoImage = new TechnologyInfoImage();
    $objTechnologyInfoImage->SortValues(substr($_REQUEST['action'],1), substr($_REQUEST['action'],0,1));
    unset($objTechnologyInfoImage);
}
elseif (isset($_REQUEST['dragsort'])) {
    // handle drag sort ajax request:
    $objTechnologyInfoImage = new TechnologyInfoImage($_REQUEST['id']);
    $new_sort_order = ($_REQUEST['idx'] * 10) + 5;
    if ($new_sort_order > $TechnologyInfoImage->SortOrder) {
        $new_sort_order += 10; // increasing sort order so must add another 10
    }
    $TechnologyInfoImage->SortOrder = $new_sort_order;
    $TechnologyInfoImage->update();
    echo "success";
    exit; // just exit since this is an ajax request
}

$objTechnology = new Technology($_REQUEST['tech']);
$objTechnologyInfo = new TechnologyInfo($_REQUEST['techinfo']);

include("includes/pagetemplate.php");

function PageContent() {
    global $objTechnology;
    global $objTechnologyInfo;
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
                        url: 'technologyinfoimage_list.php',
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
                $aLinks[3] = 'technologyinfo_list.php?cat=' . $_REQUEST['cat'] . '&tech=' . $_REQUEST['tech'];
                $aLabels[4] = 'Technology Info Images';
                $aLinks[4] = '';
                echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
                ?>

                <div class="bigbotspace flex-container space-between">
                    <p class="larger auto heading"><?php echo $objTechnology->TechnologyName; ?></p>
                    <a href="technologyinfoimage_admin.php?cat=<?php echo $_REQUEST['cat']; ?>&tech=<?php echo $_REQUEST['tech']; ?>&techinfo=<?php echo $_REQUEST['techinfo']; ?>" class="button_link"><button class="">Add New Technology Info Image</button></a>
                </div>
            </div>
    
            <div class="layout">
    
                <table class="tablestyle" id="list_table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Ordering</th>
                            <th class="mid">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $objTechnologyInfoImage = new TechnologyInfoImage();
                        $oTechnologyInfoImage = $objTechnologyInfoImage->getAllTechnologyInfoImageByTechnologyInfoId($_REQUEST['techinfo']);
                        foreach ($oTechnologyInfoImage as $techinfoimage) {
                            echo '<tr id="img_' . $techinfoimage->Id . '">' . PHP_EOL;
                            echo '<td>' . $techinfoimage->Id . '</td>' . PHP_EOL;
                            echo '<td>';
                            if ($techinfoimage->TechnologyInfoImageUrl != '') {
                                echo substr($techinfoimage->TechnologyInfoImageUrl,0,200);
                            }
                            echo '</td>' . PHP_EOL;
                            echo '<td class="mid"><img src="img/arrow-up-down.png" /></td>' . PHP_EOL;
                            echo '<td class="mid"><a href="technologyinfoimage_admin.php?cat=' . $_REQUEST['cat'] . '&tech=' . $_REQUEST['tech'] . '&techinfo=' . $_REQUEST['techinfo'] . '&id=' . $techinfoimage->Id . '"><img src="img/edit-icon.png" /></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="technologyinfoimage_delete.php?cat=' . $_REQUEST['cat'] . '&tech=' . $_REQUEST['tech'] . '&techinfo=' . $_REQUEST['techinfo'] . '&id=' . $techinfoimage->Id . '"><img src="img/delete-icon.png" /></a></td>' . PHP_EOL;
                            echo '</tr>' . PHP_EOL;
                        }
                        ?>
                    </tbody>
                </table>
    
            </div> <!-- layout -->
    
<?php 
}
?>