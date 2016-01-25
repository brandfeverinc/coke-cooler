<?php
session_start();

unset($_SESSION['sessLoggedIn']);

header("Location: login.php");
exit;

?>