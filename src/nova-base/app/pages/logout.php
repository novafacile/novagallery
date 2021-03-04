<?php
session_start();
$_SESSION['visitorLoggedIn'] = false;
$_SESSION['adminLoggedIn'] = false;
session_destroy();
header('Location: '.Site::url().'/');

?>