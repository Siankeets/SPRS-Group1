<?php
session_start();
include('../../db_connect.php');

if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'admin') {
    echo json_encode([]);
    exit();
}

$staffID = $_SESSION['userID'];

$conn->select_db('sprs_dummydb');

$sql = "SELECT id AS studentID, name AS student_name, program AS student_program, department AS student_department
        FROM users
        WHERE role = 'student'
        ORDER BY name ASC";
$result = $conn->query($sql);

$students = [];

while ($row = $result->fetch_assoc()) {
    $conn->select_db('sprs_mainredo');

    $stmt = $conn->prepare("SELECT id, status FROM help_conversations WHERE studentID = ? AND staffID = ?");
    $stmt->bind_param("ii", $row['studentID'], $staffID);
    $stmt->execute();
    $conv = $stmt->get_result()->fetch_assoc();

    $last_sender = '';
    if ($conv) {
        $stmt2 = $conn->prepare("SELECT sender FROM help_messages WHERE conversation_id = ? ORDER BY created_at DESC LIMIT 1");
        $stmt2->bind_param("i", $conv['id']);
        $stmt2->execute();
        $res2 = $stmt2->get_result()->fetch_assoc();
       $last_sender = strtolower($res2['sender'] ?? '');

    }

    $conn->select_db('sprs_dummydb');

    $students[] = [
        'studentID' => $row['studentID'],
        'student_name' => $row['student_name'],
        'student_program' => $row['student_program'],
        'student_department' => $row['student_department'],
        'conversation_id' => $conv['id'] ?? null,
        'status' => $conv['status'] ?? 'No conversation',
        'last_sender' => $last_sender
    ];
}

echo json_encode($students);
?>