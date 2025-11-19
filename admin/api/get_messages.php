<?php
session_start();
include('../../db_connect.php');

if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'admin' || !isset($_GET['id']) || !isset($_GET['studentID'])) {
    echo json_encode([]);
    exit();
}

$staffID = $_SESSION['userID'];
$conversationID = intval($_GET['id']);
$studentID = intval($_GET['studentID']); // <<< get studentID from GET

$conn->select_db('sprs_mainredo');

// Only get messages for conversations assigned to this staff and this student
$stmt = $conn->prepare("
    SELECT * FROM help_messages 
    WHERE conversation_id = ? 
      AND conversation_id IN (
          SELECT id FROM help_conversations 
          WHERE staffID = ? AND studentID = ?
      )
    ORDER BY created_at ASC
");
$stmt->bind_param("iii", $conversationID, $staffID, $studentID);

$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while($row = $result->fetch_assoc()){
    $messages[] = $row;
}

echo json_encode($messages);

?>
