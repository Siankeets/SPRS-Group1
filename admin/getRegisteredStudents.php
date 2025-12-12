<?php
header('Content-Type: application/json; charset=utf-8');
session_start();

include('../db_connect.php'); // Main DB
$conn->select_db("if0_40284661_sprs_mainredo");

// Validate eventID
if (!isset($_GET['eventID'])) {
    echo json_encode(["success" => false, "message" => "Missing eventID"]);
    exit;
}

$eventID = intval($_GET['eventID']);

/* ---------------------------------------------------
   1. Get registered studentIDs from event_registrations
----------------------------------------------------- */
$sql = "SELECT studentID FROM event_registrations WHERE eventID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $eventID);
$stmt->execute();
$res = $stmt->get_result();

$studentIDs = [];
while ($row = $res->fetch_assoc()) {
    $studentIDs[] = $row["studentID"];
}

/* ---------------------------------------------------
   2. Fetch student details from dummy DB
----------------------------------------------------- */
$participants = [];

if (!empty($studentIDs)) {
    $conn->select_db("if0_40284661_sprs_dummydb");

    $placeholders = implode(",", array_fill(0, count($studentIDs), "?"));
    $sql2 = "SELECT id, name, program, department FROM users WHERE id IN ($placeholders)";

    $stmt2 = $conn->prepare($sql2);
    $types = str_repeat("i", count($studentIDs));
    $stmt2->bind_param($types, ...$studentIDs);
    $stmt2->execute();

    $result2 = $stmt2->get_result();

    while ($user = $result2->fetch_assoc()) {
        $participants[] = [
            "id"     => $user["id"],
            "name"   => $user["name"],
            "program"=> $user["program"],
            "department"  => $user["department"]
        ];
    }
}

echo json_encode([
    "success" => true,
    "registeredCount" => count($participants),
    "registered" => $participants
]);
?>
