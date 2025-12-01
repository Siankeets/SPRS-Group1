<?php
header('Content-Type: application/json; charset=utf-8');
session_start();

include('../db_connect.php'); // main DB

// Enable error logging but prevent it from breaking JSON
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

// Check eventID parameter
if (!isset($_GET['eventID'])) {
    echo json_encode(['success' => false, 'message' => 'Missing eventID']);
    exit;
}

$eventID = intval($_GET['eventID']);

/* ----------------------------------------
   1. MAIN DB — Get Event Info
---------------------------------------- */
$conn->select_db("if0_40284661_sprs_mainredo");

$eventSql = "SELECT eventName, eventDescription, eventRewards, eventDate
             FROM schoolevents
             WHERE eventID = ?";
$stmt = $conn->prepare($eventSql);
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'DB error: ' . $conn->error]);
    exit;
}
$stmt->bind_param("i", $eventID);
$stmt->execute();
$eventResult = $stmt->get_result();
$event = $eventResult->fetch_assoc();

if (!$event) {
    echo json_encode(['success' => false, 'message' => 'Event not found']);
    exit;
}

$eventName    = $event['eventName'];
$eventDesc    = $event['eventDescription'];
$rewardPoints = intval($event['eventRewards']);
$eventDate    = $event['eventDate'];

/* ----------------------------------------
   2. MAIN DB — Get Attending Students
---------------------------------------- */
$attendanceSql = "
    SELECT ep.studentID
    FROM eventparticipants ep
    WHERE ep.eventID = ? AND ep.attended = 1
";
$stmt2 = $conn->prepare($attendanceSql);
if (!$stmt2) {
    echo json_encode(['success' => false, 'message' => 'DB error: ' . $conn->error]);
    exit;
}
$stmt2->bind_param("i", $eventID);
$stmt2->execute();
$attendResult = $stmt2->get_result();

$studentIDs = [];
while ($row = $attendResult->fetch_assoc()) {
    $studentIDs[] = $row['studentID'];
}

/* ----------------------------------------
   3. DUMMY DB — Get Student Info
---------------------------------------- */
$participants = [];
$totalDistributedPoints = 0;

if (!empty($studentIDs)) {
    $conn->select_db("if0_40284661_sprs_dummydb"); // switch to dummy DB

    // Dynamic placeholders for IN clause
    $placeholders = implode(",", array_fill(0, count($studentIDs), "?"));
    $sql = "SELECT id, name, points FROM users WHERE id IN ($placeholders)";
    $stmt3 = $conn->prepare($sql);

    if ($stmt3) {
        $types = str_repeat("i", count($studentIDs));
        $stmt3->bind_param($types, ...$studentIDs);
        $stmt3->execute();
        $usersResult = $stmt3->get_result();

        while ($user = $usersResult->fetch_assoc()) {
            $participants[] = [
                "studentID"    => $user['id'],
                "studentName"  => $user['name'],
                "pointsGained" => $rewardPoints
            ];
            $totalDistributedPoints += $rewardPoints;
        }
    } else {
        // Log error but continue
        error_log("Prepare failed for dummy DB query: " . $conn->error);
    }
}

/* ----------------------------------------
   4. JSON Response — Always valid
---------------------------------------- */
echo json_encode([
    "success" => true,
    "eventID" => $eventID,
    "eventName" => $eventName,
    "eventDescription" => $eventDesc,
    "eventDate" => $eventDate,
    "rewardPoints" => $rewardPoints,
    "participantsCount" => count($participants),
    "totalDistributedPoints" => $totalDistributedPoints,
    "participants" => $participants
]);
