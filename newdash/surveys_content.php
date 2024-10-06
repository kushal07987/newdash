<!-- Add survey management tools -->
<div class="flex justify-between items-center mb-6">
    <div></div> <!-- Empty div to maintain flex spacing -->
    <a href="create_survey.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Create New Survey
    </a>
</div>

<?php
require_once 'db_connect.php';  // Ensure the DB connection file is included

$conn = getDBConnection();
if (!$conn) {
    die("Database connection failed");
}

function columnExists($conn, $table, $column) {
    $sql = "SHOW COLUMNS FROM `$table` LIKE '$column'";
    $result = $conn->query($sql);
    return $result && $result->num_rows > 0;
}

// Fetch project statistics
function fetchProjectStatistics($conn) {
    $stats = [
        'total' => 0,
        'live' => 0,
        'closed' => 0,
        'on_hold' => 0,
        'yet_to_launch' => 0
    ];

    $sql = "SELECT COUNT(*) as total FROM projects";
    $result = $conn->query($sql);
    if ($result) {
        $stats['total'] = $result->fetch_assoc()['total'];
    }

    if (columnExists($conn, 'projects', 'status')) {
        $sql = "SELECT 
                SUM(CASE WHEN status = 'Live' THEN 1 ELSE 0 END) as live,
                SUM(CASE WHEN status = 'Closed' THEN 1 ELSE 0 END) as closed,
                SUM(CASE WHEN status = 'On Hold' THEN 1 ELSE 0 END) as on_hold,
                SUM(CASE WHEN status = 'Yet to Launch' THEN 1 ELSE 0 END) as yet_to_launch
                FROM projects";

        $result = $conn->query($sql);
        if ($result) {
            $row = $result->fetch_assoc();
            $stats['live'] = $row['live'];
            $stats['closed'] = $row['closed'];
            $stats['on_hold'] = $row['on_hold'];
            $stats['yet_to_launch'] = $row['yet_to_launch'];
        }
    }

    return $stats;
}

// Fetch recent activity statistics
function fetchRecentActivityStats($conn) {
    $stats = [
        'completes' => 0,
        'terminates' => 0,
        'quota_full' => 0,
        'ir' => 0
    ];

    $columns = ['completes', 'terminates', 'quota_full', 'ir'];
    $existingColumns = array_filter($columns, function($col) use ($conn) {
        return columnExists($conn, 'projects', $col);
    });

    if (!empty($existingColumns)) {
        $sql = "SELECT " . implode(', ', array_map(function($col) {
            return "SUM($col) as $col";
        }, $existingColumns)) . " FROM projects";

        if (in_array('ir', $existingColumns)) {
            $sql = str_replace("SUM(ir)", "AVG(ir)", $sql);
        }

        $result = $conn->query($sql);
        if ($result) {
            $stats = array_merge($stats, $result->fetch_assoc());
        }
    }

    return $stats;
}

// Fetch all projects
function fetchAllProjects($conn) {
    $columns = ['project_id', 'survey_id', 'project_name', 'client_name', 'geo', 'speciality', 'loi', 'ir', 'start_date', 'end_date'];
    $existingColumns = array_filter($columns, function($col) use ($conn) {
        return columnExists($conn, 'projects', $col);
    });

    $sql = "SELECT " . implode(', ', $existingColumns) . " FROM projects ORDER BY project_id DESC";

    $result = $conn->query($sql);
    $projects = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $projects[] = $row;
        }
    }

    return $projects;
}

$projectStats = fetchProjectStatistics($conn);
$recentActivityStats = fetchRecentActivityStats($conn);
$allProjects = fetchAllProjects($conn);

$conn->close();
?>

<!-- Section 1: Project Statistics Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold mb-2">Total Projects</h3>
        <p class="text-3xl font-bold"><?php echo $projectStats['total']; ?></p>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold mb-2">Live</h3>
        <p class="text-3xl font-bold"><?php echo $projectStats['live']; ?></p>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold mb-2">Closed</h3>
        <p class="text-3xl font-bold"><?php echo $projectStats['closed']; ?></p>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold mb-2">On Hold</h3>
        <p class="text-3xl font-bold"><?php echo $projectStats['on_hold']; ?></p>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold mb-2">Yet to Launch</h3>
        <p class="text-3xl font-bold"><?php echo $projectStats['yet_to_launch']; ?></p>
    </div>
</div>

<!-- Section 2: Recent Activity Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-blue-50 rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold mb-2">Completes</h3>
        <p class="text-3xl font-bold"><?php echo number_format($recentActivityStats['completes']); ?></p>
    </div>
    <div class="bg-blue-50 rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold mb-2">Terminates</h3>
        <p class="text-3xl font-bold"><?php echo number_format($recentActivityStats['terminates']); ?></p>
    </div>
    <div class="bg-blue-50 rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold mb-2">Quota Full</h3>
        <p class="text-3xl font-bold"><?php echo number_format($recentActivityStats['quota_full']); ?></p>
    </div>
    <div class="bg-blue-50 rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold mb-2">IR%</h3>
        <p class="text-3xl font-bold"><?php echo number_format($recentActivityStats['ir'], 2); ?>%</p>
    </div>
</div>

<!-- Section 3: Projects Table -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h3 class="text-lg font-semibold mb-4">Projects</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Project ID</th>
                    <th class="py-3 px-6 text-left">Survey ID</th>
                    <th class="py-3 px-6 text-left">Project Name</th>
                    <th class="py-3 px-6 text-left">Client Name</th>
                    <th class="py-3 px-6 text-left">Geo</th>
                    <th class="py-3 px-6 text-left">Specialty</th>
                    <th class="py-3 px-6 text-left">LOI</th>
                    <th class="py-3 px-6 text-left">IR%</th>
                    <th class="py-3 px-6 text-left">Start Date</th>
                    <th class="py-3 px-6 text-left">End Date</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                <?php foreach ($allProjects as $project): ?>
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6 text-left whitespace-nowrap"><?php echo htmlspecialchars($project['project_id'] ?? ''); ?></td>
                        <td class="py-3 px-6 text-left whitespace-nowrap"><?php echo htmlspecialchars($project['survey_id'] ?? ''); ?></td>
                        <td class="py-3 px-6 text-left whitespace-nowrap">
                            <a href="surveyproject_details.php?id=<?php echo urlencode($project['project_id'] ?? ''); ?>" class="text-blue-600 hover:text-blue-800">
                                <?php echo htmlspecialchars($project['project_name'] ?? ''); ?>
                            </a>
                        </td>
                        <td class="py-3 px-6 text-left whitespace-nowrap"><?php echo htmlspecialchars($project['client_name'] ?? ''); ?></td>
                        <td class="py-3 px-6 text-left whitespace-nowrap"><?php echo htmlspecialchars($project['geo'] ?? ''); ?></td>
                        <td class="py-3 px-6 text-left whitespace-nowrap"><?php echo htmlspecialchars($project['speciality'] ?? ''); ?></td>
                        <td class="py-3 px-6 text-left whitespace-nowrap"><?php echo htmlspecialchars($project['loi'] ?? ''); ?></td>
                        <td class="py-3 px-6 text-left whitespace-nowrap"><?php echo isset($project['ir']) ? number_format($project['ir'], 2) . '%' : ''; ?></td>
                        <td class="py-3 px-6 text-left whitespace-nowrap"><?php echo htmlspecialchars($project['start_date'] ?? ''); ?></td>
                        <td class="py-3 px-6 text-left whitespace-nowrap"><?php echo htmlspecialchars($project['end_date'] ?? ''); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
