<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?php echo $appName; ?></title>
        <meta http-equiv="description" content="page description" />
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" href="assets/css/reset.css">
        <link href='http://fonts.googleapis.com/css?family=Lato:100,400,900,400italic' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    </head>
    
    <body>

        <?php include("includes/nav.php");?>


        <?php include("includes/header.php");?>
        <div id="content">

            <?php
            PageContent();
            ?>

        </div> <!-- content -->

        <!--Footer has all scripts-->
        <?php include("includes/footer.php");?>

    </body>
</html>