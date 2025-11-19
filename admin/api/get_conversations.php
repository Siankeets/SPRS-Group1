<?php
session_start();
include('../../db_connect.php');

if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'admin') {
    echo json_encode([]);
    exit();
}

$staffID = $_SESSION['userID'];

$conn->select_db('sprs_dummydb');

// Fetch all students with optional conversation assigned to this staff
$sql = "SELECT u.id AS studentID, u.name AS student_name, u.program AS student_program, u.department AS student_department,
               hc.id AS conversation_id, hc.status AS conversation_status
        FROM users u
        LEFT JOIN sprs_mainredo.help_conversations hc
            ON u.id = hc.studentID AND hc.staffID = ?
        WHERE u.role = 'student'
        ORDER BY u.name ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $staffID);
$stmt->execute();
$result = $stmt->get_result();

$students = [];
while($row = $result->fetch_assoc()){
    $last_sender = '';
    if($row['conversation_id']){
        $conn->select_db('sprs_mainredo');
        $stmt2 = $conn->prepare("SELECT sender FROM help_messages WHERE conversation_id = ? ORDER BY created_at DESC LIMIT 1");
        $stmt2->bind_param("i", $row['conversation_id']);
        $stmt2->execute();
        $res2 = $stmt2->get_result()->fetch_assoc();
        $last_sender = strtolower($res2['sender'] ?? '');
    }

    $students[] = [
        'studentID' => $row['studentID'],
        'student_name' => $row['student_name'],
        'student_program' => $row['student_program'],
        'student_department' => $row['student_department'],
        'conversation_id' => $row['conversation_id'] ?? null,
        'conversation_status' => $row['conversation_status'] ?? 'No conversation',
        'last_sender' => $last_sender
    ];
}

echo json_encode($students);
?>
