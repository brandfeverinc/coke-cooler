<?php
include_once('../inc/classes/Technology.php');

$objTechnology = new Technology();

switch ($_REQUEST['item_id']) {
    case 'color-chk':
        $link_url = 'tech-color.php';
        break;
    case 'ibeacon-chk':
        $link_url = 'tech-ibeacon.php';
        break;
    case 'sound-chk':
        $link_url = 'tech-sound.php';
        break;
    case 'light-chk':
        $link_url = 'tech-light.php';
        break;
}

$oTechnology = $objTechnology->getAllTechnologyByCategoryIdLinkUrl($_REQUEST['category_id'], $link_url);

$objT = new Technology($oTechnology[0]->Id);
$objT->IsActive = $_REQUEST['item_value'];
$objT->Update();

echo 'success';

?>