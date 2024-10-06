<?php
ob_start();
include 'participants_content.php';
$content = ob_get_clean();
$pageTitle = 'Participants';
include 'layout.php';
?>