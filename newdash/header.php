<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>


<header class="bg-white shadow-lg py-4 px-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800"><?php echo htmlspecialchars($pageTitle); ?></h1>
        <div class="flex items-center">
            <button class="mr-4 text-gray-600 hover:text-gray-800">
                <svg class="h-6 w-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
            </button>
            <button class="flex items-center text-gray-600 hover:text-gray-800">
                <img class="h-8 w-8 rounded-full object-cover" src="https://via.placeholder.com/150" alt="User avatar">
                <span class="ml-2">John Doe</span>
            </button>
        </div>
    </div>
</header>