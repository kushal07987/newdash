<?php
require_once 'db_connect.php';

function fetchProjectData($columns, $limit = null) {
    $conn = getDBConnection();

    if (!$conn) {
        die("Database connection failed");
    }

    // Prepare the SQL query
    $selectedColumns = implode(', ', $columns);
    $sql = "SELECT $selectedColumns FROM projects ORDER BY project_id DESC";
    
    if ($limit !== null) {
        $sql .= " LIMIT $limit";
    }

    $result = $conn->query($sql);

    if (!$result) {
        die("Error in SQL query: " . $conn->error);
    }

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    $conn->close();

    return $data;
}
?>