<?php
require_once 'db_connect.php';

$conn = getDBConnection();
if (!$conn) {
    die("Database connection failed");
}

function columnExists($conn, $table, $column) {
    $sql = "SHOW COLUMNS FROM `$table` LIKE '$column'";
    $result = $conn->query($sql);
    return $result->num_rows > 0;
}

function fetchRecentProjects($conn, $limit = 5) {
    $columns = [
        'client_name' => 'Client',
        'project_name' => 'Project Name',
        'start_date' => 'Start Date',
        'completes' => 'Completes',
        'end_date' => 'End Date'
    ];

    $selectColumns = [];
    foreach ($columns as $dbColumn => $alias) {
        if (columnExists($conn, 'projects', $dbColumn)) {
            $selectColumns[] = "`$dbColumn` AS `$alias`";
        }
    }

    if (empty($selectColumns)) {
        return [];
    }

    $selectClause = implode(', ', $selectColumns);

    $sql = "SELECT $selectClause,
            CASE 
                WHEN `end_date` < CURDATE() THEN 'Completed'
                WHEN `start_date` > CURDATE() THEN 'Upcoming'
                ELSE 'In Progress'
            END AS Status
            FROM projects 
            ORDER BY " . (columnExists($conn, 'projects', 'start_date') ? 'start_date' : 'project_name') . " DESC 
            LIMIT ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    $projects = [];
    while ($row = $result->fetch_assoc()) {
        $projects[] = $row;
    }

    $stmt->close();

    return $projects;
}

function fetchProjectStatistics($conn) {
    $stats = [
        'total_projects' => 0,
        'active_projects' => 0,
        'completed_projects' => 0
    ];

    // Total projects
    $result = $conn->query("SELECT COUNT(*) AS count FROM projects");
    if ($result) {
        $stats['total_projects'] = $result->fetch_assoc()['count'];
    }

    // Active and completed projects
    if (columnExists($conn, 'projects', 'start_date') && columnExists($conn, 'projects', 'end_date')) {
        $sql = "SELECT 
                SUM(CASE WHEN start_date <= CURDATE() AND (end_date >= CURDATE() OR end_date IS NULL) THEN 1 ELSE 0 END) AS active_projects,
                SUM(CASE WHEN end_date < CURDATE() THEN 1 ELSE 0 END) AS completed_projects
                FROM projects";
        $result = $conn->query($sql);
        if ($result) {
            $row = $result->fetch_assoc();
            $stats['active_projects'] = $row['active_projects'];
            $stats['completed_projects'] = $row['completed_projects'];
        }
    }

    return $stats;
}

function fetchRecentActivity($conn, $limit = 5) {
    if (!columnExists($conn, 'projects', 'created_at')) {
        return [];
    }

    $sql = "SELECT 'New project created' AS activity, project_name, created_at 
            FROM projects 
            ORDER BY created_at DESC 
            LIMIT ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    $activities = [];
    while ($row = $result->fetch_assoc()) {
        $activities[] = $row;
    }

    $stmt->close();

    return $activities;
}

$recentProjects = fetchRecentProjects($conn);
$projectStats = fetchProjectStatistics($conn);
$recentActivities = fetchRecentActivity($conn);

// Close the database connection after all queries
$conn->close();
?>

<h2 class="text-2xl font-semibold mb-4">Dashboard</h2>

<!-- Cards section -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold mb-2">Total Projects</h3>
        <p class="text-3xl font-bold"><?php echo $projectStats['total_projects']; ?></p>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold mb-2">Active Projects</h3>
        <p class="text-3xl font-bold"><?php echo $projectStats['active_projects']; ?></p>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold mb-2">Completed Projects</h3>
        <p class="text-3xl font-bold"><?php echo $projectStats['completed_projects']; ?></p>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold mb-2">Total Responses</h3>
        <p class="text-3xl font-bold">--</p>
    </div>
</div>

<!-- Project Statistics and Recent Activity section -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold mb-4">Project Statistics</h3>
        <ul>
            <li class="mb-2">Total Projects: <?php echo $projectStats['total_projects']; ?></li>
            <li class="mb-2">Active Projects: <?php echo $projectStats['active_projects']; ?></li>
            <li class="mb-2">Completed Projects: <?php echo $projectStats['completed_projects']; ?></li>
        </ul>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold mb-4">Recent Activity</h3>
        <?php if (!empty($recentActivities)): ?>
            <ul>
                <?php foreach ($recentActivities as $activity): ?>
                    <li class="mb-2">
                        <?php echo htmlspecialchars($activity['activity'] . ': ' . $activity['project_name']); ?>
                        <span class="text-sm text-gray-500">
                            (<?php echo date('M j, Y', strtotime($activity['created_at'])); ?>)
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No recent activity to display.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Recent Projects Table -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h3 class="text-lg font-semibold mb-4">Recent Projects</h3>
    <?php if (!empty($recentProjects)): ?>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                        <?php foreach (array_keys($recentProjects[0]) as $header): ?>
                            <th class="py-3 px-6 text-left"><?php echo htmlspecialchars($header); ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    <?php foreach ($recentProjects as $project): ?>
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <?php foreach ($project as $key => $value): ?>
                                <td class="py-3 px-6 text-left whitespace-nowrap">
                                    <?php if ($key === 'Status'): ?>
                                        <?php
                                        $statusClass = '';
                                        switch ($value) {
                                            case 'Completed':
                                                $statusClass = 'bg-green-200 text-green-600';
                                                break;
                                            case 'In Progress':
                                                $statusClass = 'bg-blue-200 text-blue-600';
                                                break;
                                            case 'Upcoming':
                                                $statusClass = 'bg-yellow-200 text-yellow-600';
                                                break;
                                        }
                                        ?>
                                        <span class="py-1 px-3 rounded-full text-xs <?php echo $statusClass; ?>">
                                            <?php echo htmlspecialchars($value); ?>
                                        </span>
                                    <?php else: ?>
                                        <?php echo htmlspecialchars($value); ?>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p>No recent projects to display.</p>
    <?php endif; ?>
</div>