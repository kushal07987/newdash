<?php
ob_start();
include 'clients_content.php';
$content = ob_get_clean();
$pageTitle = 'Clients';
include 'layout.php';
?>