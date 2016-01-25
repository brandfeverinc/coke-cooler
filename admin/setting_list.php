<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");
include("../inc/classes/GenericDb.php");
include("../inc/classes/Helpers.php");

include("includes/pagetemplate.php");

function PageContent() {
?>
    
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    
            <div class="layout center-flex">

                <?php
                $aLabels = array();
                $aLinks = array();
                $aLabels[0] = 'Home';
                $aLinks[0] = 'mainpage.php';
                $aLabels[1] = 'Settings';
                $aLinks[1] = '';
                echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
                ?>

                <div class="bigbotspace flex-container space-between">
                    <p class="larger auto heading">Settings</p>
                    <a href="setting_admin.php" class="button_link"><button class="">Add New Setting</button></a>
                </div>
            </div>
    
            <div class="layout">
    
                <table class="tablestyle" id="list_table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Setting Name</th>
                            <th>Setting Value</th>
                            <th class="mid">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $db = new GenericDb();
                        $rows = $db->run("select * from setting");
                        foreach ($rows as $setting) {
                            echo '<tr>' . PHP_EOL;
                            echo '<td>' . $setting['id'] . '</td>' . PHP_EOL;
                            echo '<td>' . $setting['setting_name'] . '</td>' . PHP_EOL;
                            echo '<td>' . substr(strip_tags($setting['setting_value']), 0, 50) . '...</td>' . PHP_EOL;
                            echo '<td class="mid"><a href="setting_admin.php?id=' . $setting['id'] . '"><img src="img/edit-icon.png" /></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="setting_delete.php?id=' . $setting['id'] . '"><img src="img/delete-icon.png" /></a></td>' . PHP_EOL;
                            echo '</tr>' . PHP_EOL;
                        }
                        ?>
                    </tbody>
                </table>
    
            </div> <!-- layout -->
    
<?php 
}
?>