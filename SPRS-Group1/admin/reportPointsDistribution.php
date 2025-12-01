<?php
header('Content-Type: application/json; charset=utf-8');
include "../dummy/connection_dummydb.php";

// Fetch student names + points
$sql = "
    SELECT name, points 
    FROM users
    WHERE role = 'student'
    ORDER BY points DESC
";

$result = $conn->query($sql);

$labels = [];
$values = [];

while ($row = $result->fetch_assoc()) {
    $labels[] = $row['name'];
    $values[] = (int)$row['points'];
}

echo json_encode([
    "labels" => $labels,
    "values" => $values
]);
