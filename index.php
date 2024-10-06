<?php
// index.php

// Start output buffering
ob_start();

// Include the dashboard content
include 'dashboard_content.php';

// Get the buffered content
$content = ob_get_clean();

// Set the page title
$pageTitle = 'Dashboard';

// Include the layout file
include 'layout.php';
?>