<?php
session_start();
include('../db_connect.php');
header('Content-Type: application/json');

date_default_timezone_set('Asia/Manila');

function todayDate() { return date('Y-m-d'); }
function nowDateTime() { return date('Y-m-d H:i:s'); }

if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'student') {
    echo json_encode(['success' => false, 'message' => 'Not logged in.']);
    exit();
}

$studentID = (int)$_SESSION['userID'];

if (!isset($_POST['eventID'])) {
    echo json_encode(['success' => false, 'message' => 'Event ID missing.']);
    exit();
}

$eventID = (int)$_POST['eventID'];

// Fetch event + date
$stmtV = mysqli_prepare($conn, "SELECT eventID, eventDate FROM schoolevents WHERE eventID = ?");
mysqli_stmt_bind_param($stmtV, "i", $eventID);
mysqli_stmt_execute($stmtV);
$resV = mysqli_stmt_get_result($stmtV);

if (!$resV || mysqli_num_rows($resV) === 0) {
    echo json_encode(['success' => false, 'message' => 'Event not found.']);
    exit();
}

$eventData = mysqli_fetch_assoc($resV);
$eventDate = $eventData['eventDate'];
$today = todayDate();

// Prevent registration for past events
if ($eventDate < $today) {
    echo json_encode([
        'success' => false,
        'message' => 'This event is already finished. You cannot register.'
    ]);
    exit();
}

// Already registered?
$stmt = mysqli_prepare($conn, "SELECT id FROM event_registrations WHERE studentID = ? AND eventID = ?");
mysqli_stmt_bind_param($stmt, "ii", $studentID, $eventID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    echo json_encode(['success' => false, 'message' => 'You are already registered for this event.']);
    exit();
}

// Insert registration
$registerTime = nowDateTime();
$stmtIns = mysqli_prepare($conn, 
    "INSERT INTO event_registrations (studentID, eventID, registered_at) VALUES (?, ?, ?)"
);
mysqli_stmt_bind_param($stmtIns, "iis", $studentID, $eventID, $registerTime);

if (mysqli_stmt_execute($stmtIns)) {

    // Ensure participant is created
    $stmt3 = mysqli_prepare($conn, 
        "INSERT IGNORE INTO eventparticipants (eventID, studentID, id, attended) VALUES (?, ?, ?, 0)"
    );
    mysqli_stmt_bind_param($stmt3, "iii", $eventID, $studentID, $studentID);
    mysqli_stmt_execute($stmt3);

    echo json_encode(['success' => true, 'message' => 'Registered successfully!']);
    exit();
}

echo json_encode(['success' => false, 'message' => 'Registration failed.']);
exit();
?>
