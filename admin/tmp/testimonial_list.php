<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");
include("../inc/classes/Testimonial.php");
include("../inc/classes/Helpers.php");

if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'sort') {
    $objTestimonial = new Testimonial();
    $objTestimonial->SortValues(substr($_REQUEST['action'],1), substr($_REQUEST['action'],0,1));
    unset($objTestimonial);
}
elseif (isset($_REQUEST['dragsort'])) {
    // handle drag sort ajax request:
    $objTestimonial = new Testimonial($_REQUEST['id']);
    $new_sort_order = ($_REQUEST['idx'] * 10) + 5;
    if ($new_sort_order > $objTestimonial->SortOrder) {
        $new_sort_order += 10; // increasing sort order so must add another 10
    }
    $objTestimonial->SortOrder = $new_sort_order;
    $objTestimonial->Update();
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
                        url: 'testimonial_list.php',
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
                $aLabels[1] = 'Testimonials';
                $aLinks[1] = '';
                echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
                ?>

                <div class="bigbotspace flex-container space-between">
                    <p class="larger auto heading">Testimonials</p>
                    <button class=" "><a href="testimonial_admin.php" class="button_link">Add New Testimonial</a></button>
                </div>
            </div>
    
            <div class="layout">
    
                <table class="tablestyle" id="list_table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Person</th>
                            <th>Title</th>
                            <th>Company</th>
                            <th class="mid">Order</th>
                            <th class="mid">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $objTestimonial = new Testimonial();
                        $oTestimonial = $objTestimonial->GetAllTestimonial('sort_order');
                        foreach ($oTestimonial as $testimonial) {
                            echo '<tr id="img_' . $testimonial->Id . '">' . PHP_EOL;
                            echo '<td>' . $testimonial->Id . '</td>' . PHP_EOL;
                            echo '<td>' . $testimonial->CustomerName . '</td>' . PHP_EOL;
                            echo '<td>' . $testimonial->CustomerTitle . '</td>' . PHP_EOL;
                            echo '<td>' . $testimonial->CustomerCompany . '</td>' . PHP_EOL;
                            echo '<td class="mid"><img src="img/arrow-up-down.png" /></td>' . PHP_EOL;
                            echo '<td class="mid"><a href="testimonial_admin.php?id=' . $testimonial->Id . '"><img src="img/edit-icon.png" /></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="testimonial_delete.php?id=' . $testimonial->Id . '"><img src="img/delete-icon.png" /></a></td>' . PHP_EOL;
                            echo '</tr>' . PHP_EOL;
                        }
                        ?>
                    </tbody>
                </table>
    
            </div> <!-- layout -->
    
<?php 
}
?>