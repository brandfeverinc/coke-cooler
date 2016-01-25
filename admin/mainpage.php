<?php
session_start();
header("Cache-control: private"); //IE 6 Fix
include("includes/config.php");
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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
</head>

<body>
<?php include("includes/nav.php");?>

<?php include("includes/header.php");?>

<div id="content">
</div>



<!--Footer has all scripts-->
<?php include("includes/footer.php");?>

  </body>


</html>