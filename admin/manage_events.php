<?php
session_start();
include ('../db_connect.php');

header('Content-Type: application/json');
$act = $_GET['action'] ?? ($_POST['action'] ?? '');

// ----------------------------------------------------------------------
// LIST EVENTS
// ----------------------------------------------------------------------
if ($act === 'list') {
    $query = "SELECT * FROM schoolevents ORDER BY eventID DESC";
    $result = mysqli_query($conn, $query);

    $events = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $events[] = $row;
    }
    echo json_encode($events);
    exit;
}

// ----------------------------------------------------------------------
// GET SINGLE EVENT
// ----------------------------------------------------------------------
elseif ($act === 'get') {
    $id = intval($_GET['id']);

    $stmt = mysqli_prepare($conn, "SELECT * FROM schoolevents WHERE eventID = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $event = mysqli_fetch_assoc($result);
    echo json_encode($event ?: ['error' => 'Event not found']);
    exit;
}

// ----------------------------------------------------------------------
// DELETE EVENT
// ----------------------------------------------------------------------
elseif ($act === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = mysqli_prepare($conn, "DELETE FROM schoolevents WHERE eventID = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    echo json_encode([
        'message' => mysqli_stmt_affected_rows($stmt) > 0
            ? 'Event deleted successfully!'
            : 'No event found'
    ]);
    exit;
}

// ----------------------------------------------------------------------
// SAVE (ADD/UPDATE) EVENT
// ----------------------------------------------------------------------
elseif ($act === 'save') {

    $id = intval($_POST['eventID'] ?? 0);
    $title = $_POST['eventName'] ?? '';
    $description = $_POST['eventDescription'] ?? '';
    $rewards = $_POST['eventRewards'] ?? '';
    $type = $_POST['rewardType'] ?? 'Points'; // default type if not set

    if (!$title || !$description || !$rewards || !$type) {
        echo json_encode(['message' => 'All fields are required.']);
        exit;
    }

    if ($id > 0) {
        // UPDATE
        $stmt = mysqli_prepare($conn,
            "UPDATE schoolevents
             SET eventName = ?, eventDescription = ?, eventRewards = ?, rewardType = ?
             WHERE eventID = ?"
        );
        mysqli_stmt_bind_param($stmt, "ssssi", $title, $description, $rewards, $type, $id);
        mysqli_stmt_execute($stmt);
        $message = "Event updated successfully!";
    } else {
        // INSERT
        $stmt = mysqli_prepare($conn,
            "INSERT INTO schoolevents (eventName, eventDescription, eventRewards, rewardType)
             VALUES (?, ?, ?, ?)"
        );
        mysqli_stmt_bind_param($stmt, "ssss", $title, $description, $rewards, $type);
        mysqli_stmt_execute($stmt);
        $message = "Event added successfully!";
    }

    echo json_encode(['message' => $message]);
    exit;
}


// ----------------------------------------------------------------------
// REWARD LIST
// ----------------------------------------------------------------------
elseif ($act === 'listRewards') {
    $query = "SELECT * FROM rewards";
    $result = mysqli_query($conn, $query);

    $rewards = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rewards[] = $row;
    }

    echo json_encode($rewards);
    exit;
}


// ----------------------------------------------------------------------
// GET REWARD
// ----------------------------------------------------------------------
elseif ($act === 'getReward') {
    $id = $_GET['id'];

    $stmt = mysqli_prepare($conn, "SELECT * FROM rewards WHERE rewardID = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $reward = mysqli_fetch_assoc($result);

    echo json_encode($reward ?: ['error' => 'Reward not found']);
    exit;
}


// ----------------------------------------------------------------------
// DELETE REWARD
// ----------------------------------------------------------------------
elseif ($act === 'delReward' && isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = mysqli_prepare($conn, "DELETE FROM rewards WHERE rewardID = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    echo json_encode([
        'message' => mysqli_stmt_affected_rows($stmt) > 0
            ? 'Reward deleted successfully!'
            : 'No reward found'
    ]);
    exit;
}


// ----------------------------------------------------------------------
// SAVE REWARD
// ----------------------------------------------------------------------
elseif ($act === 'saveReward') {

    $id = $_POST['rewardID'];
    $title = $_POST['rewardName'];
    $description = $_POST['rewardDescription'];
    $points = $_POST['rewardPointsRequired'];
    $type = $_POST['rewardType'];

    if (empty($id)) {
        $stmt = mysqli_prepare($conn,
            "INSERT INTO rewards (rewardName, rewardDescription, rewardPointsRequired, rewardType)
             VALUES (?, ?, ?, ?)"
        );
        mysqli_stmt_bind_param($stmt, "ssis", $title, $description, $points, $type);
        mysqli_stmt_execute($stmt);
        $message = "Reward added successfully!";
    }

    else {
        $stmt = mysqli_prepare($conn,
            "UPDATE rewards
             SET rewardName = ?, rewardDescription = ?, rewardPointsRequired = ?, rewardType = ?
             WHERE rewardID = ?"
        );
        mysqli_stmt_bind_param($stmt, "ssisi", $title, $description, $points, $type, $id);
        mysqli_stmt_execute($stmt);
        $message = "Reward updated successfully!";
    }

    echo json_encode(['message' => $message]);
    exit;
}

?>