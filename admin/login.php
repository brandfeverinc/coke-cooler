<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
require_once("../inc/classes/SystemUser.php");
$login_msg = '';

if ($_POST['commit'] == "Sign In") {

	$acct = new SystemUser();
	$acct = $acct->CheckLogin($_POST['login'],$_POST['password'] );

	//if($acct->AdminId >= 0 && $acct->IsActive) 
	if($acct->IsActive) {
		$_SESSION['sessLoggedIn'] = '1';
		$_SESSION['sessUser'] = $acct->FirstName . " " . $acct->LastName;
		$_SESSION['sessIsAdmin'] = $acct->IsAdmin;
		$_SESSION['sessAccessLevel'] = $acct->AccessLevel;
	}
	else {
		if ($acct->IsActive) {
			$login_msg = 'Invalid User Name or Password';
		}
		else {
			$login_msg = 'Account is not active';
		}
	}

	if ($login_msg == '') {
		header("Location: mainpage.php");
		exit;
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $appName; ?></title>
    <meta http-equiv="description" content="page description" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="assets/css/reset.css">
    <link rel="stylesheet" href="assets/css/fonts/regular.css">
    <link rel="stylesheet" href="assets/css/fonts/light.css">
    <link rel="stylesheet" href="assets/css/fonts/black.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<?php include("includes/logheader.php");?>
<div id="content">
<div class="layout">
    <p></p>

    <div class="formstyle center">
        <form method="post" action="">
            <?php
            if ($login_msg != '') {
                echo '<p>' . $login_msg . '</p>';
            }
            ?>
          <p><input class="form logform" type="text" name="login" value="" placeholder="User Name"></p>
          <p><input class="form logform" type="password" name="password" value="" placeholder="Password"></p>
          <!--
          <p class="remember_me">
            <label>
             <label class="checktxt">
              <input type="checkbox" name="remember_me" id="remember_me">
              <span class="icon"><i class="fa fa-check"></i></span>
              <span>Remember me on this computer</span>
            </label>
            </label>
          </p>
          -->
          <p class="submit"><input type="submit" name="commit" value="Sign In"></p>
        </form>
    </div>



<!--    <button></button>-->
</div>

</div> <!-- content -->



<!--Footer has all scripts-->
<?php include("includes/footer.php");?>

  </body>


</html>
