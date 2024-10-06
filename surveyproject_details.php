<?php
require_once 'db_connect.php';

$conn = getDBConnection();
if (!$conn) {
    die("Database connection failed");
}

// Retrieve the project ID from the URL
$project_id = isset($_GET['id']) ? $_GET['id'] : '';

// Fetch project details
$project = array();
if (!empty($project_id)) {
    $sql = "SELECT * FROM projects WHERE project_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $project_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $project = $result->fetch_assoc();
    }
}

// Fetch vendor details
$vendors = array();
if (!empty($project_id)) {
    $sql = "SELECT * FROM vendors WHERE project_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $project_id);
    $stmt->execute();
    $vendorResult = $stmt->get_result();
    $vendors = $vendorResult->fetch_all(MYSQLI_ASSOC);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($project['project_name'] ?? 'Project Details'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <h1 class="text-3xl font-bold mb-8"><?php echo htmlspecialchars($project['project_name'] ?? 'Project Details'); ?></h1>

            <!-- Section 1: Project Details -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">Project Details</h2>
                <form id="projectDetailsForm">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p><strong>Project ID:</strong> <?php echo htmlspecialchars($project['project_id'] ?? ''); ?></p>
                            <p><strong>Survey ID:</strong> <?php echo htmlspecialchars($project['survey_id'] ?? ''); ?></p>
                            <div class="mb-2">
                                <label for="client_name" class="block text-sm font-medium text-gray-700">Client Name:</label>
                                <input type="text" id="client_name" name="client_name" value="<?php echo htmlspecialchars($project['client_name'] ?? ''); ?>" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" readonly>
                            </div>
                        </div>
                        <div>
                            <div class="mb-2">
                                <label for="client_address" class="block text-sm font-medium text-gray-700">Client Address:</label>
                                <input type="text" id="client_address" name="client_address" value="<?php echo htmlspecialchars($project['client_address'] ?? ''); ?>" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" readonly>
                            </div>
                            <div class="mb-2">
                                <label for="client_po_number" class="block text-sm font-medium text-gray-700">Client PO Number:</label>
                                <input type="text" id="client_po_number" name="client_po_number" value="<?php echo htmlspecialchars($project['client_po_number'] ?? ''); ?>" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" readonly>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="mt-4 flex space-x-2">
                    <button id="editProjectDetails" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Edit/Modify
                    </button>
                    <button id="saveProjectDetails" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded hidden">
                        Save
                    </button>
                </div>
            </div>

            <!-- Section 2: Project Specifications -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">Project Specifications</h2>
                <form id="projectSpecsForm">
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr class="bg-gray-200 text-gray-600 text-xs leading-normal">
                                    <?php
                                    $fields = ['Project Manager', 'Target Audience', 'Segment', 'Geo', 'Loi', 'Target', 'Start Date', 'End Date', 'Completes', 'Terminates', 'Quota Full', 'Total', 'Ir'];
                                    foreach ($fields as $field) {
                                        echo "<th class='py-3 px-2 text-left'>" . $field . "</th>";
                                    }
                                    ?>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 text-sm font-light" id="projectSpecsTable">
                                <tr class="border-b border-gray-200 hover:bg-gray-100">
                                    <?php
                                    $fields = ['project_manager', 'target_audience', 'segment', 'geo', 'loi', 'target', 'start_date', 'end_date', 'completes', 'terminates', 'quota_full', 'total', 'ir'];
                                    foreach ($fields as $field) {
                                        echo "<td class='py-3 px-2 text-left'>";
                                        echo "<input type='text' name='$field' value='" . htmlspecialchars($project[$field] ?? '') . "' class='w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-xs' readonly>";
                                        echo "</td>";
                                    }
                                    ?>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </form>
                <div class="mt-4 flex space-x-2">
                    <button id="editProjectSpecs" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Edit/Modify
                    </button>
                    <button id="saveProjectSpecs" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded hidden">
                        Save
                    </button>
                </div>
            </div>

            <!-- Section 3: Vendor Details -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">Vendor Details</h2>
                <form id="vendorDetailsForm">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left">Vendor Name</th>
                                <th class="py-3 px-6 text-left">Start Date</th>
                                <th class="py-3 px-6 text-left">End Date</th>
                                <th class="py-3 px-6 text-left">Completes</th>
                                <th class="py-3 px-6 text-left">Terminates</th>
                                <th class="py-3 px-6 text-left">Quota Full</th>
                                <th class="py-3 px-6 text-left">Total Responses</th>
                                <th class="py-3 px-6 text-left">IR%</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm font-light">
                            <?php if (empty($vendors)): ?>
                                <tr class="border-b border-gray-200">
                                    <td colspan="8" class="py-3 px-6 text-center">No vendor data available</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($vendors as $index => $vendor): ?>
                                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                                        <?php foreach ($vendor as $key => $value): ?>
                                            <td class="py-3 px-6 text-left whitespace-nowrap">
                                                <input type="text" name="vendors[<?php echo $index; ?>][<?php echo $key; ?>]" value="<?php echo htmlspecialchars($value ?? ''); ?>" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" readonly>
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </form>
                <div class="mt-4 flex space-x-2">
                    <button id="editVendorDetails" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Edit/Modify
                    </button>
                    <button id="saveVendorDetails" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded hidden">
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const sections = ['ProjectDetails', 'ProjectSpecs', 'VendorDetails'];
        
        sections.forEach(section => {
            const editButton = document.getElementById(`edit${section}`);
            const saveButton = document.getElementById(`save${section}`);
            const form = document.getElementById(`${section.toLowerCase()}Form`);
            
            editButton.addEventListener('click', function() {
                editButton.classList.add('hidden');
                saveButton.classList.remove('hidden');
                Array.from(form.elements).forEach(input => {
                    if (input.name !== 'project_id' && input.name !== 'survey_id') {
                        input.readOnly = false;
                    }
                });
            });
            
            saveButton.addEventListener('click', function() {
                saveButton.classList.add('hidden');
                editButton.classList.remove('hidden');
                Array.from(form.elements).forEach(input => {
                    input.readOnly = true;
                });
                // Add your save logic here
                console.log(`Saving ${section}`);
                // You would typically send an AJAX request here to save the data
            });
        });
    });
    </script>
</body>
</html>