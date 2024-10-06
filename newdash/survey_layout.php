<?php
// survey_layout.php

function survey_startLayout($title) {
    global $pageTitle, $content;
    $pageTitle = $title;
    ob_start();
}

function survey_endLayout() {
    global $content;
    $content = ob_get_clean();
    include 'layout.php';
}
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $title; ?> - Survey Admin</title>
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    <body class="bg-gray-100">
        <div class="flex h-screen bg-gray-200">
            <!-- Sidebar -->
            <div class="bg-indigo-800 text-indigo-100 w-64 space-y-6 py-7 px-2 absolute inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 transition duration-200 ease-in-out">
                <a href="#" class="text-white flex items-center space-x-2 px-4">
                    <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    <span class="text-2xl font-extrabold">Survey Admin</span>
                </a>
                <nav>
                    <a href="survey_dashboard.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-indigo-700 hover:text-white">
                        Dashboard
                    </a>
                    <a href="survey_list.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-indigo-700 hover:text-white">
                        Surveys
                    </a>
                    <a href="survey_create.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-indigo-700 hover:text-white">
                        Create Survey
                    </a>
                    <a href="survey_reports.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-indigo-700 hover:text-white">
                        Reports
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Top bar -->
                <header class="bg-white shadow-lg">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                        <h1 class="text-3xl font-bold text-gray-900"><?php echo $title; ?></h1>
                        <div class="flex items-center">
                            <button class="text-gray-500 focus:outline-none focus:text-gray-700">
                                <svg class="h-6 w-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                            </button>
                            <button class="ml-6 text-gray-500 focus:outline-none focus:text-gray-700">
                                <svg class="h-6 w-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
                    <div class="container mx-auto px-6 py-8">
    <?php
