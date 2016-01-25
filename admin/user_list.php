<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");
include("../inc/classes/SystemUser.php");
include("../inc/classes/Helpers.php");

include("includes/pagetemplate.php");

function PageContent() {
?>
    
    <div class="layout center-flex">

        <?php
        $aLabels = array();
        $aLinks = array();
        $aLabels[0] = 'Home';
        $aLinks[0] = 'mainpage.php';
        $aLabels[1] = 'Users';
        $aLinks[1] = '';
        echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
        ?>

        <div class="bigbotspace flex-container space-between">
            <p class="larger2 auto heading">Users</p>
            <a href="user_admin.php" class="button_link"><button class=" ">Add New User</button></a>
        </div>
        <form name="search-form" method="post">
            <div class="bigbotspace flex-container">
                <input type="text" name="search_val" value="" />&nbsp;&nbsp;
                <button class=" ">Search Users</button>
            </div>
        </form>
    </div>

    <div class="layout">

        <table class="tablestyle" id="list_table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Last Name</th>
                    <th>First Name</th>
                    <th>Email</th>
                    <th>User Type</th>
                    <th class="mid">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $objSystemUser = new SystemUser();
                if ($_REQUEST['search_val'] == '') {
                    $oSystemUser = $objSystemUser->GetAllSystemUser('last_name ASC, first_name ASC');
                }
                else {
                    $oSystemUser = $objSystemUser->GetAllSystemUserBySearchVal($_REQUEST['search_val']);
                }
                foreach ($oSystemUser as $user) {
                    echo '<tr>' . PHP_EOL;
                    echo '<td>' . $user->Id . '</td>' . PHP_EOL;
                    echo '<td>' . $user->LastName . '</td>' . PHP_EOL;
                    echo '<td>' . $user->FirstName . '</td>' . PHP_EOL;
                    echo '<td>' . $user->EmailAddress . '</td>' . PHP_EOL;
                    echo '<td>';
                    if ($user->IsAdmin) {
                        echo 'Admin';
                    }
                    else {
                        echo '&nbsp;';
                    }
                    echo '</td>' . PHP_EOL;
                    echo '<td class="mid"><a href="user_admin.php?id=' . $user->Id . '"><img src="img/edit-icon.png" /></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="user_delete.php?id=' . $user->Id . '"><img src="img/delete-icon.png" /></a></td>' . PHP_EOL;
                    echo '</tr>' . PHP_EOL;
                }
                ?>
            </tbody>
        </table>

    </div> <!-- layout -->
    
<?php 
}
?>