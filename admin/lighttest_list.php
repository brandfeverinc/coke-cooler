<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");
include("../inc/classes/Light.php");
include("../inc/classes/LightTest.php");
include("../inc/classes/Helpers.php");

if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'sort') {
    $objLightTest = new LightTest();
    $objLightTest->SortValues(substr($_REQUEST['action'],1), substr($_REQUEST['action'],0,1));
    unset($objLightTest);
}
elseif (isset($_REQUEST['dragsort'])) {
    // handle drag sort ajax request:
    $objLightTest = new LightTest($_REQUEST['id']);
    $new_sort_order = ($_REQUEST['idx'] * 10) + 5;
    if ($new_sort_order > $objLightTest->SortOrder) {
        $new_sort_order += 10; // increasing sort order so must add another 10
    }
    $objLightTest->SortOrder = $new_sort_order;
    $objLightTest->update();
    echo "success";
    exit; // just exit since this is an ajax request
}

$objLight = new Light($_REQUEST['light']);

include("includes/pagetemplate.php");

function PageContent() {
    global $objLight;
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
                        url: 'lighttest_list.php',
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
                $aLabels[2] = 'Light';
                $aLinks[2] = 'light_list.php&cat=' . $_REQUEST['cat'];
                $aLabels[3] = 'Light Test List';
                $aList[3] = '';
                echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
                ?>

                <div class="bigbotspace flex-container space-between">
                    <p class="larger auto heading">Light Tests</p>
                    <a href="lighttest_admin.php?cat=<?php echo $_REQUEST['cat']; ?>&light=<?php echo $objLight->Id; ?>" class="button_link"><button class="">Add New Light Test</button></a>
                </div>
            </div>
    
            <div class="layout">
    
                <table class="tablestyle" id="list_table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <td>Test Image</th>
                            <th>Dark Image</th>
                            <th>Light Image</th>
                            <th class="mid">Order</th>
                            <th class="mid">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $objLightTest = new LightTest();
                        $oLightTest = $objLightTest->getAllLightTestByLightId($objLight->Id);
                        foreach ($oLightTest as $lighttest) {
                            echo '<tr id="img_' . $lighttest->Id . '">' . PHP_EOL;
                            echo '<td>' . $lighttest->Id . '</td>' . PHP_EOL;
                            echo '<td><img src="/' . $objLightTest->GetPath() . $lighttest->BackgroundImageFile . '" style="max-width: 100px;" /></td>' . PHP_EOL;
                            echo '<td><img src="/' . $objLightTest->GetPath() . $lighttest->ImageFileDark . '" style="max-width: 200px;" /></td>' . PHP_EOL;
                            echo '<td><img src="/' . $objLightTest->GetPath() . $lighttest->ImageFileLight . '" style="max-width: 200px;" /></td>' . PHP_EOL;
                            echo '<td class="mid"><img src="img/arrow-up-down.png" /></td>' . PHP_EOL;
                            echo '<td class="mid"><a href="lighttest_admin.php?cat=' . $_REQUEST['cat'] . '&light=' . $_REQUEST['light'] . '&id=' . $lighttest->Id . '"><img src="img/edit-icon.png" /></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="lighttest_delete.php?cat=' . $_REQUEST['cat'] . '&light=' . $_REQUEST['light'] . '&id=' . $lighttest->Id . '"><img src="img/delete-icon.png" /></a></td>' . PHP_EOL;
                            echo '</tr>' . PHP_EOL;
                        }
                        ?>
                    </tbody>
                </table>
    
            </div> <!-- layout -->
    
<?php 
}
?>