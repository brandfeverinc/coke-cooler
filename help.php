<?php
session_start();

include_once("inc/classes/Setting.php");
$objSetting = new Setting();

$oSetting = $objSetting->GetAllSettingBySettingName("How to Screen Share");
$screen_share_text = $oSetting[0]->SettingValue;
$oSetting = $objSetting->GetAllSettingBySettingName("Back End Login");
$back_end_login_text = $oSetting[0]->SettingValue;
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Coke Cooler Help</title>
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/styles.css">
	<script type="text/javascript" src="assets/js/jquery.js"></script>
	<script type="text/javascript" src="assets/js/data.js"></script>
	<script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="assets/js/scripts.js"></script>
	<style>
		h2 {
/* 
			background: rgba(0,0,0,0.4);
 */
			background: #f40000; /* Old browsers */
			background: -moz-linear-gradient(right,  transparent 0%, #cd1d20 20%, #a00305 100%); /* FF3.6-15 */
			background: -webkit-linear-gradient(right,  transparent 0%,#cd1d20 20%,#a00305 100%); /* Chrome10-25,Safari5.1-6 */
			background: linear-gradient(to left,  transparent 0%,#cd1d20 20%,#a00305 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
			padding: 10px;
		}
		p {
			padding: 10px;
			font-size: 18px;
		}
	</style>
</head>
<body>
	<div class="background-gradient" style="background:<?php echo $background; ?>;"></div>
	<div class="wrap equipment-wrap">
		<header class="equipment">
			<div class="col-sm-6"><a class="main-logo" href="/">Coca-Cola</a></div>
			<div class="col-sm-6 header-tagline">Help</div>
		</header>
		<div style="padding-left:10px;">
			<h2>How to Screen Share</h2>
			<p><?php echo $screen_share_text; ?></p>
			<p>&nbsp;</p>

			<h2>Back End Login</h2>
			<p><?php echo $back_end_login_text; ?></p>
		</div>
	</div>
	<?php 
	// Must set cat_id prior to flyout-menu
	$cat_id = $_SESSION['sessCategory'];
	include('flyout-menu.php');
	?>
	<div class="transparent-bg"></div>
</body>
</html>