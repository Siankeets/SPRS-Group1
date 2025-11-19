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
$message = isset($data['message']) ? trim($data['message']) : "";
$conversationID = isset($data['conversation_id']) ? intval($data['conversation_id']) : null;

$conn->select_db('sprs_mainredo');

// Step 1: Find or create conversation if conversation_id not given
if (!$conversationID) {
    if (!$staffID) {
        echo json_encode(['success' => false, 'error' => 'No staff selected']);
        exit();
    }

    $stmt = $conn->prepare("SELECT id FROM help_conversations WHERE studentID = ? AND staffID = ?");
    $stmt->bind_param("ii", $studentID, $staffID);
    $stmt->execute();
    $conv = $stmt->get_result()->fetch_assoc();

    if ($conv) {
        $conversationID = $conv['id'];
    } else {
        $stmt2 = $conn->prepare("INSERT INTO help_conversations (studentID, staffID, status, last_updated) VALUES (?, ?, 'open', NOW())");
        $stmt2->bind_param("ii", $studentID, $staffID);
        $stmt2->execute();
        $conversationID = $stmt2->insert_id;
    }
}

// Step 2: Insert message if not empty
if ($message !== "") {
    $stmt3 = $conn->prepare("INSERT INTO help_messages (conversation_id, sender, message, created_at) VALUES (?, 'student', ?, NOW())");
    $stmt3->bind_param("is", $conversationID, $message);
    $stmt3->execute();
}

// Step 3: Update conversation timestamp
$stmt4 = $conn->prepare("UPDATE help_conversations SET last_updated = NOW() WHERE id = ?");
$stmt4->bind_param("i", $conversationID);
$stmt4->execute();

echo json_encode(['success' => true, 'conversation_id' => $conversationID]);
?>