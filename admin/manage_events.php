<?php
session_start();
include('../db_connect.php');

header('Content-Type: application/json');

// Ensure admin is logged in
if (!isset($_SESSION['username'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$act = $_GET['action'] ?? ($_POST['action'] ?? '');

// ----------------------------------------------------------------------
// LIST EVENTS WITH REGISTRATION & ATTENDANCE COUNTS
// ----------------------------------------------------------------------
if ($act === 'list') {
    $query = "
        SELECT 
            e.eventID, e.eventName, e.eventDescription, e.eventRewards, e.rewardType, e.eventDate,
            COUNT(DISTINCT r.id) AS registeredCount,
            COUNT(DISTINCT p.id) AS attendedCount
        FROM schoolevents e
        LEFT JOIN event_registrations r ON e.eventID = r.eventID
        LEFT JOIN eventparticipants p ON e.eventID = p.eventID AND p.attended = 1
        GROUP BY e.eventID
        ORDER BY e.eventDate DESC
    ";

    $result = mysqli_query($conn, $query);
    $events = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $row['registeredCount'] = (int)$row['registeredCount'];
        $row['attendedCount'] = (int)$row['attendedCount'];
        $events[] = $row;
    }

    echo json_encode($events);
    exit;
}

// ----------------------------------------------------------------------
// GET SINGLE EVENT
// ----------------------------------------------------------------------
if ($act === 'get') {
    $id = intval($_GET['id'] ?? 0);
    $stmt = mysqli_prepare($conn, "SELECT * FROM schoolevents WHERE eventID = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $event = mysqli_fetch_assoc($res);
    echo json_encode($event ?: ['error' => 'Event not found']);
    exit;
}

// ----------------------------------------------------------------------
// SAVE (ADD / UPDATE) EVENT
// ----------------------------------------------------------------------
if ($act === 'save') {
    $id = intval($_POST['eventID'] ?? 0);
    $title = $_POST['eventName'] ?? '';
    $description = $_POST['eventDescription'] ?? '';
    $rewards = $_POST['eventRewards'] ?? '';
    $type = $_POST['rewardType'] ?? '';
    $eventDate = $_POST['eventDate'] ?? ''; // new field

    if (!$title || !$description || !$rewards || !$type || !$eventDate) {
        echo json_encode(['message' => 'All fields are required.']);
        exit;
    }

    if ($id > 0) {
        // UPDATE
        $stmt = mysqli_prepare($conn, "UPDATE schoolevents SET eventName=?, eventDescription=?, eventRewards=?, rewardType=?, eventDate=? WHERE eventID=?");
        mysqli_stmt_bind_param($stmt, "sssssi", $title, $description, $rewards, $type, $eventDate, $id);
        mysqli_stmt_execute($stmt);
        $message = "Event updated successfully!";
    } else {
        // INSERT
        $stmt = mysqli_prepare($conn, "INSERT INTO schoolevents (eventName, eventDescription, eventRewards, rewardType, eventDate) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssss", $title, $description, $rewards, $type, $eventDate);
        mysqli_stmt_execute($stmt);
        $message = "Event added successfully!";
    }

    echo json_encode(['message' => $message]);
    exit;
}

// ----------------------------------------------------------------------
// DELETE EVENT
// ----------------------------------------------------------------------
if ($act === 'delete') {
    $id = intval($_GET['id'] ?? 0);
    $stmt = mysqli_prepare($conn, "DELETE FROM schoolevents WHERE eventID=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    echo json_encode(['message' => mysqli_stmt_affected_rows($stmt) > 0 ? 'Event deleted successfully!' : 'No event found']);
    exit;
}

// ----------------------------------------------------------------------
// REWARDS MANAGEMENT (unchanged)
// ----------------------------------------------------------------------
if ($act === 'listRewards') {
    $result = mysqli_query($conn, "SELECT * FROM rewards");
    $rewards = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($rewards);
    exit;
}

if ($act === 'getReward') {
    $id = intval($_GET['id'] ?? 0);
    $stmt = mysqli_prepare($conn, "SELECT * FROM rewards WHERE rewardID=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $reward = mysqli_fetch_assoc($res);
    echo json_encode($reward ?: ['error' => 'Reward not found']);
    exit;
}

if ($act === 'delReward') {
    $id = intval($_GET['id'] ?? 0);
    $stmt = mysqli_prepare($conn, "DELETE FROM rewards WHERE rewardID=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    echo json_encode(['message' => mysqli_stmt_affected_rows($stmt) > 0 ? 'Reward deleted successfully!' : 'No reward found']);
    exit;
}

if ($act === 'saveReward') {
    $id = intval($_POST['rewardID'] ?? 0);
    $title = $_POST['rewardName'] ?? '';
    $desc = $_POST['rewardDescription'] ?? '';
    $points = intval($_POST['rewardPointsRequired'] ?? 0);
    $type = $_POST['rewardType'] ?? '';

    if (!$title || !$desc || !$points || !$type) {
        echo json_encode(['message' => 'All fields are required.']);
        exit;
    }

    if ($id > 0) {
        $stmt = mysqli_prepare($conn, "UPDATE rewards SET rewardName=?, rewardDescription=?, rewardPointsRequired=?, rewardType=? WHERE rewardID=?");
        mysqli_stmt_bind_param($stmt, "ssisi", $title, $desc, $points, $type, $id);
        mysqli_stmt_execute($stmt);
        $message = "Reward updated successfully!";
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO rewards (rewardName, rewardDescription, rewardPointsRequired, rewardType) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssis", $title, $desc, $points, $type);
        mysqli_stmt_execute($stmt);
        $message = "Reward added successfully!";
    }

    echo json_encode(['message' => $message]);
    exit;
}

echo json_encode(['error' => 'Invalid action']);
exit;
?>
