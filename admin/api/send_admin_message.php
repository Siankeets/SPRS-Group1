<?php
session_start();
include('../../db_connect.php');

if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false]);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);
$conversationID = intval($data['conversation_id']);
$message = trim($data['message']);
$staffID = $_SESSION['userID'];

if ($message === "") {
    echo json_encode(['success' => false]);
    exit();
}

$conn->select_db('if0_40284661_sprs_mainredo');

$stmt = $conn->prepare("
    INSERT INTO help_messages (conversation_id, sender, message, created_at)
    VALUES (?, 'staff', ?, NOW())
");
$stmt->bind_param("is", $conversationID, $message);
$stmt->execute();

$conn->query("UPDATE help_conversations SET last_updated = NOW() WHERE id = $conversationID");

echo json_encode(['success' => true]);
?>