<?php
// surveys.php

// Start output buffering
ob_start();

// Include the surveys content
include 'surveys_content.php';

// Get the buffered content
$content = ob_get_clean();

// Set the page title
$pageTitle = 'Surveys';

// Include the layout file
include 'layout.php';
?>