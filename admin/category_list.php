<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");
include("../inc/classes/Category.php");
include("../inc/classes/Item.php");
include("../inc/classes/Technology.php");
include("../inc/classes/Helpers.php");

if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'sort') {
    $objCategory = new Category();
    $objCategory->SortValues(substr($_REQUEST['action'],1), substr($_REQUEST['action'],0,1));
    unset($objCategory);
}
elseif (isset($_REQUEST['dragsort'])) {
    // handle drag sort ajax request:
    $objCategory = new Category($_REQUEST['id']);
    $new_sort_order = ($_REQUEST['idx'] * 10) + 5;
    if ($new_sort_order > $objCategory->SortOrder) {
        $new_sort_order += 10; // increasing sort order so must add another 10
    }
    $objCategory->SortOrder = $new_sort_order;
    $objCategory->Update();
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
                        url: 'category_list.php',
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
                
                $('.chk-active').click(function() {
                    var item_id = $(this).attr('id');
                    var category_id = $(this).attr('data-id');
                    var item_value = 0;
                    if ($(this).prop("checked")) {
                        item_value = 1;
                    }
                    $.ajax({
                        url: 'technology_activate.php',
                        data: {
                            category_id: category_id,
                            item_value: item_value,
                            item_id: item_id
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
                });
    
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
                $aLabels[1] = 'Categories';
                $aLinks[1] = '';
                echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
                ?>

                <div class="bigbotspace flex-container space-between">
                    <p class="larger auto heading">Categories</p>
                    <a href="category_admin.php" class="button_link"><button class="">Add New Category</button></a>
                </div>
            </div>
    
            <div class="layout">
                
                <p>Use checkboxes to mark sections active or inactive</p>
    
                <table class="tablestyle" id="list_table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Category Name</th>
                            <th>Items</th>
                            <!--
                            <th>Color</th>
                            <th>iBeacon</th>
                            <th>Light</th>
                            <th>Sound</th>
                            -->
                            <th>Patents</th>
                            <th>Technology</th>
                            <th>Active</th>
                            <th class="mid">Order</th>
                            <th class="mid">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $objTechnology = new Technology();
                        $objCategory = new Category();
                        $oCategory = $objCategory->GetAllCategory('sort_order');
                        $objItem = new Item();
                        foreach ($oCategory as $cat) {
                            echo '<tr id="img_' . $cat->Id . '">' . PHP_EOL;
                            echo '<td>' . $cat->Id . '</td>' . PHP_EOL;
                            echo '<td>' . $cat->CategoryName . '</td>' . PHP_EOL;
                            echo '<td>(<a href="item_list.php?cat=' . $cat->Id . '">' . $objItem->getCountItemByCategoryId($cat->Id) . '</a>)</td>' . PHP_EOL;

                            //$cChecked = '';
                            //if ($objTechnology->getAllTechnologyByCategoryIdLinkUrlIsActive($cat->Id, 'tech-color.php')) {
                            //    $cChecked = 'checked';
                            //}
                            //echo '<td class="mid"><input type="checkbox" ' . $cChecked . ' data-id="' . $cat->Id . '" class="chk-active" name="color-chk" id="color-chk" value="1" />&nbsp;<a href="color_list.php?cat=' . $cat->Id . '"><img src="img/edit-icon.png" /></a></td>' . PHP_EOL;
                            //$cChecked = '';
                            //if ($objTechnology->getAllTechnologyByCategoryIdLinkUrlIsActive($cat->Id, 'tech-ibeacon.php')) {
                            //    $cChecked = 'checked';
                            //}
                            //echo '<td class="mid"><input type="checkbox" ' . $cChecked . ' data-id="' . $cat->Id . '" class="chk-active" name="ibeacon-chk" id="ibeacon-chk" value="1" />&nbsp;<a href="ibeacon_list.php?cat=' . $cat->Id . '"><img src="img/edit-icon.png" /></a></td>' . PHP_EOL;
                            //$cChecked = '';
                            //if ($objTechnology->getAllTechnologyByCategoryIdLinkUrlIsActive($cat->Id, 'tech-light.php')) {
                            //    $cChecked = 'checked';
                            //}
                            //echo '<td class="mid"><input type="checkbox" ' . $cChecked . ' data-id="' . $cat->Id . '" class="chk-active" name="light-chk" id="light-chk" value="1" />&nbsp;<a href="light_list.php?cat=' . $cat->Id . '"><img src="img/edit-icon.png" /></a></td>' . PHP_EOL;
                            //$cChecked = '';
                            //if ($objTechnology->getAllTechnologyByCategoryIdLinkUrlIsActive($cat->Id, 'tech-sound.php')) {
                            //    $cChecked = 'checked';
                            //}
                            //echo '<td class="mid"><input type="checkbox" ' . $cChecked . ' data-id="' . $cat->Id . '" class="chk-active" name="sound-chk" id="sound-chk" value="1" />&nbsp;<a href="sound_list.php?cat=' . $cat->Id . '"><img src="img/edit-icon.png" /></a></td>' . PHP_EOL;
                            
                            echo '<td class="mid"><a href="patent_list.php?cat=' . $cat->Id . '"><img src="img/edit-icon.png" /></a></td>' . PHP_EOL;
                            echo '<td class="mid"><a href="technology_list.php?cat=' . $cat->Id . '"><img src="img/edit-icon.png" /></a></td>' . PHP_EOL;
                            echo '<td>';
                            if ($cat->IsActive) {
                                echo 'Yes';
                            }
                            else {
                                echo 'No';
                            }
                            echo '</td>' . PHP_EOL;
                            echo '<td class="mid"><img src="img/arrow-up-down.png" /></td>' . PHP_EOL;
                            echo '<td class="mid"><a href="category_admin.php?id=' . $cat->Id . '"><img src="img/edit-icon.png" /></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="category_delete.php?id=' . $cat->Id . '"><img src="img/delete-icon.png" /></a></td>' . PHP_EOL;
                            echo '</tr>' . PHP_EOL;
                        }
                        ?>
                    </tbody>
                </table>
    
            </div> <!-- layout -->
    
<?php 
}
?>