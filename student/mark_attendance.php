<?php
session_start();
include('../db_connect.php'); 
header('Content-Type: application/json');


if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'student') {
    echo json_encode(['success' => false, 'message' => 'Not logged in.']);
    exit();
}
$studentID = (int)$_SESSION['userID'];


$eventID = null;
if (isset($_POST['eventID'])) $eventID = (int)$_POST['eventID'];
elseif (isset($_GET['eventID'])) $eventID = (int)$_GET['eventID'];

if (!$eventID) {
    echo json_encode(['success' => false, 'message' => 'Event ID missing.']);
    exit();
}

$stmtV = mysqli_prepare($conn, "SELECT eventID FROM schoolevents WHERE eventID = ?");
mysqli_stmt_bind_param($stmtV, "i", $eventID);
mysqli_stmt_execute($stmtV);
$resV = mysqli_stmt_get_result($stmtV);
if (!$resV || mysqli_num_rows($resV) === 0) {
    echo json_encode(['success' => false, 'message' => 'Event not found.']);
    exit();
}


$stmtReg = mysqli_prepare($conn, "SELECT id FROM event_registrations WHERE studentID = ? AND eventID = ?");
mysqli_stmt_bind_param($stmtReg, "ii", $studentID, $eventID);
mysqli_stmt_execute($stmtReg);
$resReg = mysqli_stmt_get_result($stmtReg);

if (mysqli_num_rows($resReg) === 0) {

    echo json_encode(['success' => false, 'message' => 'You are not registered for this event. Please register first.']);
    exit();
}


$stmtP = mysqli_prepare($conn, "SELECT id, attended FROM eventparticipants WHERE eventID = ? AND studentID = ?");
mysqli_stmt_bind_param($stmtP, "ii", $eventID, $studentID);
mysqli_stmt_execute($stmtP);
$resP = mysqli_stmt_get_result($stmtP);

if ($rowP = mysqli_fetch_assoc($resP)) {
    if ((int)$rowP['attended'] === 1) {
        echo json_encode(['success' => false, 'message' => 'Attendance already marked.']);
        exit();
    }

    $stmtUp = mysqli_prepare($conn, "UPDATE eventparticipants SET attended = 1 WHERE id = ?");
    mysqli_stmt_bind_param($stmtUp, "i", $rowP['id']);
    mysqli_stmt_execute($stmtUp);
    if (mysqli_stmt_affected_rows($stmtUp) >= 0) {
        echo json_encode(['success' => true, 'message' => 'Attendance marked successfully.']);
        exit();
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to mark attendance.']);
        exit();
    }
} else {

    $stmtIns = mysqli_prepare($conn, "INSERT INTO eventparticipants (eventID, studentID, attended) VALUES (?, ?, 1)");
    mysqli_stmt_bind_param($stmtIns, "ii", $eventID, $studentID);
    if (mysqli_stmt_execute($stmtIns)) {
        echo json_encode(['success' => true, 'message' => 'Attendance marked successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to mark attendance.']);
    }
    exit();
}
