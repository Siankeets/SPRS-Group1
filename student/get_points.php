<?php
session_start();
include('../db_connect.php');

// Ensure student is logged in gpt ver.
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'student') {
    http_response_code(403);
    echo json_encode(["error" => "Access denied"]);
    exit();
}

$conn->select_db('if0_40284661_sprs_dummydb'); // need to change this when hosting (infinityfree)

$username = $_SESSION['username'];

$stmt = $conn->prepare("SELECT points FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($points);
$stmt->fetch();

// Close statement and connection gpt.ver
$stmt->close();
// $conn->close(); // which of these? zzzz

// Return JSON with proper header gpt.ver
header('Content-Type: application/json');
echo json_encode(["points" => $points]);
exit();
?>