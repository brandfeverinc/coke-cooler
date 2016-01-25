<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
include("includes/checklogin.php");

require("../inc/classes/SystemUser.php");
require('../inc/classes/Helpers.php');

// page vars
$message = "";
$id = "";
$pwmsg = '';

if ($_REQUEST["id"] == "") {
    $id = 0;
    $_REQUEST['mode'] = 'a';
}
else {
    $id = $_REQUEST["id"];
    $_REQUEST['mode'] = 'e';
}

// if form was submitted:
if ($_POST['commit'] == "Cancel") {
	header("Location:user_list.php");
	exit;
}

if ($_POST['commit'] == "Save User") {
	
	if ($id == 0) {
		// add the listing

        $objSystemUser = new SystemUser();
		$objSystemUser->FirstName = $_REQUEST['first_name'];
		$objSystemUser->LastName = $_REQUEST['last_name'];
		$objSystemUser->EmailAddress = $_REQUEST['email_address'];
		$objSystemUser->Password = $_REQUEST['password'];
		$is_admin = 0;
		if (isset($_REQUEST['is_admin'])) {
		    $is_admin = 1;
		}
		$objSystemUser->IsAdmin = $is_admin;
        $objSystemUser->DateAdded = date('Y-d-m H:i:s');
        $objSystemUser->create();
        
		// redirect to listing list
		header("Location:user_list.php");
		exit;
	}
	else {

        $objSystemUser = new SystemUser($_REQUEST["id"]);
		$objSystemUser->FirstName = $_REQUEST['first_name'];
		$objSystemUser->LastName = $_REQUEST['last_name'];
		$objSystemUser->EmailAddress = $_REQUEST['email_address'];
		if (isset($_REQUEST['password'])) {
		    $objSystemUser->Password = $_REQUEST['password'];
		}
		$is_admin = 0;
		if (isset($_REQUEST['is_admin'])) {
		    $is_admin = 1;
		}
		$objSystemUser->IsAdmin = $is_admin;
        $objSystemUser->update();
		
		// redirect to listing list
		header("Location:user_list.php");
		exit;
	}
}
else {
	if ($_REQUEST['mode'] == 'e') {
		//listing
		$objSystemUser = new SystemUser($id);
		$first_name = $objSystemUser->FirstName;
		$last_name = $objSystemUser->LastName;
		$email_address = $objSystemUser->EmailAddress;
		$password = $objSystemUser->Password;
		$is_admin = $objSystemUser->IsAdmin;
	}
}

include("includes/pagetemplate.php");

function PageContent() {
	global $id;
	global $first_name;
	global $last_name;
	global $email_address;
	global $password;
	global $is_admin;
?>

	<div id="content">

		<div class="layout">
	        <?php
	        if ($id == 0) {
	            $bread_title = 'User Add';
	        }
	        else {
	            $bread_title = 'User Edit';
	        }

		    $aLabels = array();
		    $aLinks = array();
		    $aLabels[0] = 'Home';
		    $aLinks[0] = 'mainpage.php';
		    $aLabels[1] = 'Users';
		    $aLinks[1] = 'user_list.php';
		    $aLabels[2] = $bread_title;
		    $aLinks[2] = '';
		    echo Helpers::CreateBreadCrumbs($aLabels, $aLinks);
		    ?>

		    <p class="larger2 botspace heading"><?php echo $bread_title; ?></p>

			<div class="formstyle ">
				<form method="post" action="user_admin.php">
					<table class="form-table">
						<tr>
							<td class="table-label" align="right">First Name: </td>
							<td><input class="form midform" type="text" name="first_name" value="<?php echo $first_name ?>" placeholder="First Name" /></td>
						</tr>
						<tr>
							<td class="table-label" align="right">Last Name: </td>
							<td><input class="form midform" type="text" name="last_name" value="<?php echo $last_name; ?>" placeholder="Last Name" /></td>
						</tr>
						<tr>
							<td class="table-label" align="right">Email Address: </td>
							<td><input class="form midform" type="text" name="email_address" value="<?php echo $email_address; ?>" placeholder="Email Address" /></td>
						</tr>
						<tr>
							<td class="table-label" align="right">Admin: </td>
							<td><input type="checkbox" name="is_admin" value="1"<?php if ($is_admin) { echo ' checked'; } ?>>
							</td>
						</tr>
					</table>
				
					<p class="submit"><input type="submit" name="commit" value="Save User">&nbsp;&nbsp;<input type="submit" name="commit" value="Cancel"></p>
				
                   	<input type="hidden" name="id" value="<? echo $id; ?>" />

				</form>
		</div>

	</div>

<?php
}
?>