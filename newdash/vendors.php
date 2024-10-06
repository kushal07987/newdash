<?php
ob_start();
include 'vendors_content.php';
$content = ob_get_clean();
$pageTitle = 'Vendors';
include 'layout.php';
?>