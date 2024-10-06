<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Details</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <h1 class="text-3xl font-bold mb-8">Vendor Details</h1>

            <!-- Section 1: Vendor Information -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">Vendor Information</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p><strong>Vendor ID:</strong> V001</p>
                        <p><strong>Vendor Name:</strong> SurveyPro Solutions</p>
                    </div>
                    <div>
                        <p><strong>Vendor Address:</strong> 456 Oak St, Somewhere, USA</p>
                        <p><strong>Region:</strong> West Coast</p>
                    </div>
                </div>
            </div>

            <!-- Section 2: Project Details -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">Project Details</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr class="bg-gray-200 text-gray-600 text-xs leading-normal">
                                <th class="py-3 px-2 text-left">Project Manager</th>
                                <th class="py-3 px-2 text-left">Project Name</th>
                                <th class="py-3 px-2 text-left">Segment</th>
                                <th class="py-3 px-2 text-left">Target</th>
                                <th class="py-3 px-2 text-left">Achieved</th>
                                <th class="py-3 px-2 text-left">Conversion %</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm font-light">
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-3 px-2 text-left">Alice Johnson</td>
                                <td class="py-3 px-2 text-left">Customer Satisfaction Survey</td>
                                <td class="py-3 px-2 text-left">B2C</td>
                                <td class="py-3 px-2 text-left">500</td>
                                <td class="py-3 px-2 text-left">475</td>
                                <td class="py-3 px-2 text-left">95%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Section 3: Finance Contact -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Finance Contact</h2>
                <p><strong>Finance Contact:</strong> Bob Brown</p>
                <p><strong>Email:</strong> bob.brown@surveypro.com</p>
            </div>
        </div>
    </div>
</body>
</html>