<?php
session_start();
include('../../db_connect.php');

if (!isset($_GET['id'])) {
    echo json_encode([]);
    exit();
}

$conversationID = intval($_GET['id']);

$conn->select_db('if0_40284661_sprs_mainredo');

$stmt = $conn->prepare("SELECT * FROM help_messages WHERE conversation_id = ? ORDER BY created_at ASC");
$stmt->bind_param("i", $conversationID);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode($messages);
?>