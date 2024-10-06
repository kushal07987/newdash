<?php
ob_start();
include 'settings.php';
$content = ob_get_clean();
$pageTitle = 'Settings';
include 'layout.php';
?>