<?php
session_start();
include('../../db_connect.php');

if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success'=>false]);
    exit();
}

$staffID = $_SESSION['userID'];
$data = json_decode(file_get_contents("php://input"), true);
$studentID = intval($data['studentID']);

$conn->select_db('sprs_mainredo');

// Check if conversation already exists
$stmt = $conn->prepare("SELECT id FROM help_conversations WHERE studentID = ? AND staffID = ?");
$stmt->bind_param("ii", $studentID, $staffID);
$stmt->execute();
$conv = $stmt->get_result()->fetch_assoc();

if($conv){
    $conversation_id = $conv['id'];
} else {
    // Create a new conversation
    $stmt2 = $conn->prepare("INSERT INTO help_conversations (studentID, staffID, status, last_updated) VALUES (?, ?, 'taken', NOW())");
    $stmt2->bind_param("ii", $studentID, $staffID);
    $stmt2->execute();
    $conversation_id = $stmt2->insert_id;
}

echo json_encode(['success'=>true, 'conversation_id'=>$conversation_id]);
?>
