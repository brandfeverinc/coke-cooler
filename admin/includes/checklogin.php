<?php
@session_start();

isset($_SESSION['sessLoggedIn']) ? $loggedin = $_SESSION['sessLoggedIn'] : $loggedin = '0';

if ($loggedin != '1') {
 	header('Location: login.php');
   }

?>