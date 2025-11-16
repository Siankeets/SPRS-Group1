<?php
session_start();
include('../db_connect.php'); // DB connection
header('Content-Type: application/json');

// --- Ensure student is logged in ---
if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'student') {
    echo json_encode(['success' => false, 'message' => 'Not logged in.']);
    exit();
}

$studentID = (int)$_SESSION['userID'];

// --- Check POST data ---
if (!isset($_POST['eventID'])) {
    echo json_encode(['success' => false, 'message' => 'Event ID missing.']);
    exit();
}

$eventID = (int)$_POST['eventID'];

// Validate event exists
$stmtV = mysqli_prepare($conn, "SELECT eventID FROM schoolevents WHERE eventID = ?");
mysqli_stmt_bind_param($stmtV, "i", $eventID);
mysqli_stmt_execute($stmtV);
$resV = mysqli_stmt_get_result($stmtV);
if (!$resV || mysqli_num_rows($resV) === 0) {
    echo json_encode(['success' => false, 'message' => 'Event not found.']);
    exit();
}

// --- Check if already registered ---
$stmt = mysqli_prepare($conn, "SELECT id FROM event_registrations WHERE studentID = ? AND eventID = ?");
mysqli_stmt_bind_param($stmt, "ii", $studentID, $eventID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result) > 0){
    echo json_encode(['success' => false, 'message' => 'You are already registered for this event.']);
    exit();
}

// --- Insert registration ---
$stmtIns = mysqli_prepare($conn, "INSERT INTO event_registrations (studentID, eventID, registered_at) VALUES (?, ?, NOW())");
mysqli_stmt_bind_param($stmtIns, "ii", $studentID, $eventID);

if(mysqli_stmt_execute($stmtIns)){
    // --- Insert participant row if missing ---
    $stmt3 = mysqli_prepare($conn, "INSERT IGNORE INTO eventparticipants (eventID, studentID, attended) VALUES (?, ?, 0)");
    mysqli_stmt_bind_param($stmt3, "ii", $eventID, $studentID);
    mysqli_stmt_execute($stmt3);

    echo json_encode(['success' => true, 'message' => 'Registered successfully!']);
    exit(); // <--- important
} else {
    echo json_encode(['success' => false, 'message' => 'Registration failed.']);
    exit(); // <--- important
}
