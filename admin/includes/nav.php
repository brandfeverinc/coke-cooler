
<div class ="navborder">
</div>

  <div class="hambackground">
    <div id="hamburger">
      <span></span>
      <span></span>
      <span></span>
      <span></span>
    </div>
</div>
    <nav>
      <ul class="menu">

        <div class="navheader">Administration</div>
        <li><a href="homepageimage_list.php">Homepage Images</a></li>
        <li><a href="category_list.php"> Categories</a></li>
<?php
    include_once("../inc/classes/Category.php");
    $objCats = new Category();
    $oCats = $objCats->GetAllCategory();
    foreach ($oCats as $cat) {
        echo '        <li style="margin-left:20px;"><a href="item_list.php?cat=' . $cat->Id . '">&ndash; ' . $cat->CategoryName . '</a></li>' . PHP_EOL;;
    }
?>
<!--
        <li><a href="patent_list.php">Patents</a></li>
        <li><a href="sound_list.php">Sound</a></li>
        <li><a href="color_list.php">Color</a></li>
        <li><a href="ibeacon_list.php">iBeacon</a></li>
        <li><a href="light_list.php">Light</a></li>
        <li><a href="technology_list.php">Technology</a></li>
-->
        <li><a href="setting_list.php">Settings</a></li>
        <li><a href="user_list.php">System Users</a></li>

        <div class="navheader">Account</div>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </nav>
