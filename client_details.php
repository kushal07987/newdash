<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Details</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <h1 class="text-3xl font-bold mb-8">Client Details</h1>

            <!-- Section 1: Client Information -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">Client Information</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p><strong>Client ID:</strong> CL001</p>
                        <p><strong>Client Name:</strong> Acme Corporation</p>
                    </div>
                    <div>
                        <p><strong>Client Address:</strong> 123 Main St, Anytown, USA</p>
                        <p><strong>Region:</strong> North America</p>
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
                                <td class="py-3 px-2 text-left">John Doe</td>
                                <td class="py-3 px-2 text-left">Market Research 2023</td>
                                <td class="py-3 px-2 text-left">B2B</td>
                                <td class="py-3 px-2 text-left">1000</td>
                                <td class="py-3 px-2 text-left">850</td>
                                <td class="py-3 px-2 text-left">85%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Section 3: Finance Contact -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Finance Contact</h2>
                <p><strong>Finance Contact:</strong> Jane Smith</p>
                <p><strong>Email:</strong> jane.smith@acmecorp.com</p>
            </div>
        </div>
    </div>
</body>
</html>