<?php
ob_start();
include 'create_survey_content.php';
$content = ob_get_clean();
$pageTitle = 'Create Survey';
include 'layout.php';
?>