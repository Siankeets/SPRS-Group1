<?php
session_start();
include('../../db_connect.php');

if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success'=>false]);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);
$conversationID = intval($data['conversation_id']);
$message = trim($data['message']);
$staffID = $_SESSION['userID'];

if($message === ""){
    echo json_encode(['success'=>false]);
    exit();
}

$conn->select_db('sprs_mainredo');

// Ensure conversation belongs to this staff
$stmtCheck = $conn->prepare("SELECT id FROM help_conversations WHERE id = ? AND staffID = ?");
$stmtCheck->bind_param("ii", $conversationID, $staffID);
$stmtCheck->execute();
if(!$stmtCheck->get_result()->fetch_assoc()){
    echo json_encode(['success'=>false, 'error'=>'Conversation not assigned to you']);
    exit();
}

// Insert message
$stmt = $conn->prepare("INSERT INTO help_messages (conversation_id, sender, message, created_at) VALUES (?, 'staff', ?, NOW())");
$stmt->bind_param("is", $conversationID, $message);
$stmt->execute();

// Update last_updated
$conn->query("UPDATE help_conversations SET last_updated = NOW() WHERE id = $conversationID");

echo json_encode(['success'=>true]);
?>
