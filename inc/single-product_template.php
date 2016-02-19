<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Coke Cooler</title>
    <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/styles.css">
    <script type="text/javascript" src="/assets/js/jquery.js"></script>
    <script type="text/javascript" src="/assets/js/data.js"></script>
    <script type="text/javascript" src="/assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/assets/js/jquery.virtualKeyboard.js"></script>
    <script type="text/javascript" src="/assets/js/scripts.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.virtualKeyboard').vkb();
        });
    </script>
</head>
<body>
    <div class="background-gradient" style="background:<?php echo $background; ?>;"></div>
    <div class="wrap">
<?php pageContent(); ?>
    </div>
	<?php 
	// Must set cat_id prior to flyout-menu
	// Since Sound is under Equipment, this is coded to 1
	$cat_id = 1;
	include('flyout-menu.php');
	?>
	<div id="underlay" style="height:100%; width:100%; position:absolute; top:0; left:0; background-color:black; display:none; opacity:0.9;"></div>
    <div class="product-lightbox-container">
        <div class="modaltitle-container"><p>Test</p></div>
        <div data-id="" class="modalx-container"><img class="modalx" src="assets/img/modalx.png" alt="x"></div>
        <div class="top-section" id="top-section"></div>
        <div class="bottom-section" id="bottom-section">
            <div class="bottom-content"></div>
            <div class="form-section">
                <h2>Request for information</h2>
                <a href="" class="close-form"><img src="assets/img/modalx.png"></a>
                <div id="form-div">
                    <form name="email-form">
                        <input type="text" name="name" id="name" class="virtualKeyboard vkb-css-parent-input" placeholder="Name">
                        <input type="text" name="email" id="email" class="virtualKeyboard vkb-css-parent-input" placeholder="Email">
                        <input type="text" name="question" id="question" placeholder="What would you like to know?" class="virtualKeyboard vkb-css-parent-input">
                        <input type="submit" value="Send" id="email_form_submit">
                    </form>
                </div>
                <div id="thankDiv" style="display:none;">
                    <h2>Thank you!</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="transparent-bg"></div>
</div>
</body>
</html>