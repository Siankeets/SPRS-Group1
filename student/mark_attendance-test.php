<?php
session_start();
include('../db_connect.php'); // Main DB connection

// USERS database (dummy) for points
//include('../connection_dummydb.php');   // commented out so $conn only refers to db_connect

header('Content-Type: application/json');

// COPY OF WORKING MARK ATTENDANCE w/ FULL SYSTEM  // THIS TEST VERSION IS FOR ADDING POINTS DISTRIBUTION THE SAME TIME AS ATTENDANCE IS CONFIRMED.

// Must be logged in student to mark attendance 
if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'student') {
    echo json_encode(['success' => false, 'message' => 'Not logged in.']);
    exit();
}
$studentID = (int)$_SESSION['userID'];

// Accept via POST (preferred). Also allow GET when scanned via browser if needed.
$eventID = null;
if (isset($_POST['eventID'])) $eventID = (int)$_POST['eventID'];
elseif (isset($_GET['eventID'])) $eventID = (int)$_GET['eventID'];

if (!$eventID) {
    echo json_encode(['success' => false, 'message' => 'Event ID missing.']);
    exit();
}

// Validate event exists
$stmtV = mysqli_prepare($conn, "SELECT eventID FROM schoolevents WHERE eventID = ?");
mysqli_stmt_bind_param($stmtV, "i", $eventID);
mysqli_stmt_execute($stmtV);
$resV = mysqli_stmt_get_result($stmtV);
if (!$resV || mysqli_num_rows($resV) === 0) {
    echo json_encode(['success' => false, 'message' => 'Event not found.']);
    exit();
}

// Check registration; if not registered, return friendly error
$stmtReg = mysqli_prepare($conn, "SELECT id FROM event_registrations WHERE studentID = ? AND eventID = ?");
mysqli_stmt_bind_param($stmtReg, "ii", $studentID, $eventID);
mysqli_stmt_execute($stmtReg);
$resReg = mysqli_stmt_get_result($stmtReg);

if (mysqli_num_rows($resReg) === 0) {
    // not registered -> cannot mark attendance
    echo json_encode(['success' => false, 'message' => 'You are not registered for this event. Please register first.']);
    exit();
}

// Ensure eventparticipants row exists; if not, insert one
$stmtP = mysqli_prepare($conn, "SELECT id, attended FROM eventparticipants WHERE eventID = ? AND studentID = ?");
mysqli_stmt_bind_param($stmtP, "ii", $eventID, $studentID);
mysqli_stmt_execute($stmtP);
$resP = mysqli_stmt_get_result($stmtP);

if ($rowP = mysqli_fetch_assoc($resP)) {
    if ((int)$rowP['attended'] === 1) {
        echo json_encode(['success' => false, 'message' => 'Attendance already marked.']);
        exit();
    }
    // update attended = 1
    $stmtUp = mysqli_prepare($conn, "UPDATE eventparticipants SET attended = 1 WHERE id = ?");
    mysqli_stmt_bind_param($stmtUp, "i", $rowP['id']);
    mysqli_stmt_execute($stmtUp);
    if (mysqli_stmt_affected_rows($stmtUp) >= 0) { //slot in here the points distribution.
		//get event
		mysqli_select_db($conn, "if0_40284661_sprs_mainredo"); //connect to main db and get eventReward value first.

		$stmtReward = mysqli_prepare($conn, "SELECT eventRewards FROM schoolevents WHERE eventID = ?");
		mysqli_stmt_bind_param($stmtReward, "i", $eventID);
		mysqli_stmt_execute($stmtReward);
		$resReward = mysqli_stmt_get_result($stmtReward);
		$rowReward = mysqli_fetch_assoc($resReward);
		
		$pointsToAdd = (int)$rowReward['eventRewards']; //only reads the int e.g 500 points, reads 500 to set as pointsToAdd value.
		
		
		mysqli_select_db($conn, "if0_40284661_sprs_dummydb"); //switch to dummy db to find the user's points column to update.
		
		// get current points
		$stmtBefore = mysqli_prepare($conn, "SELECT points FROM users WHERE id = ?");
		mysqli_stmt_bind_param($stmtBefore, "i", $studentID);
		mysqli_stmt_execute($stmtBefore);
		$resBefore = mysqli_stmt_get_result($stmtBefore);
		$rowBefore = mysqli_fetch_assoc($resBefore);
		$pointsBefore = (int)$rowBefore['points'];

		//add the points // this makes sure that the points to be added is correct / untampered by getting the before value and the reward value in the databases.
		$pointsAfter = $pointsBefore + $pointsToAdd;
        
        $stmtUpdatePoints = mysqli_prepare($conn, "UPDATE users SET points = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmtUpdatePoints, "ii", $pointsAfter, $studentID);
        mysqli_stmt_execute($stmtUpdatePoints);
		
		//update session points value
		//$_SESSION['points'] = $pointsAfter; // commented out for now because i think get_points.php or something handles the updates for the front-end.
		
		// switch back to main db
		//mysqli_select_db($conn, "if0_40284661_sprs_mainredo"); this isnt needed since i made the connection switch in the else statement.

        echo json_encode(['success' => true, 'message' => 'Attendance marked successfully.']);
        exit();
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to mark attendance.']);
        exit();
    }
} else {
    // insert row with attended=1
    mysqli_select_db($conn, "if0_40284661_sprs_mainredo"); // added after select_db modifications for points distribution.

    $stmtIns = mysqli_prepare($conn, "INSERT INTO eventparticipants (eventID, studentID, attended) VALUES (?, ?, 1)");
    mysqli_stmt_bind_param($stmtIns, "ii", $eventID, $studentID);
    if (mysqli_stmt_execute($stmtIns)) {
        echo json_encode(['success' => true, 'message' => 'Attendance marked successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to mark attendance.']);
    }
    exit();
}
