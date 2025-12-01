<?php
session_start();
include('../../db_connect.php');

if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'student') {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit();
}

$studentID = $_SESSION['userID'];
$data = json_decode(file_get_contents("php://input"), true);
$staffID = isset($data['staffID']) ? intval($data['staffID']) : null;

if (!$staffID) {
    echo json_encode(['success' => false, 'error' => 'No staff selected']);
    exit();
}

$conn->select_db('if0_40284661_sprs_mainredo');

// Check for existing conversation
$stmt = $conn->prepare("SELECT id FROM help_conversations WHERE studentID = ? AND staffID = ?");
$stmt->bind_param("ii", $studentID, $staffID);
$stmt->execute();
$conv = $stmt->get_result()->fetch_assoc();

if ($conv) {
    $conversationID = $conv['id'];
} else {
    // Create new conversation
    $stmt2 = $conn->prepare("INSERT INTO help_conversations (studentID, staffID, status, last_updated) VALUES (?, ?, 'open', NOW())");
    $stmt2->bind_param("ii", $studentID, $staffID);
    $stmt2->execute();
    $conversationID = $stmt2->insert_id;
}

echo json_encode(['success' => true, 'conversation_id' => $conversationID]);