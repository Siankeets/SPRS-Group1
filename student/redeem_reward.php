<?php
session_start();
header('Content-Type: application/json');
error_reporting(0);

include('../db_connect.php');

if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'student') {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$studentID = $_SESSION['userID'];
$data = json_decode(file_get_contents('php://input'), true);
$rewardID = intval($data['rewardID'] ?? 0);
$action = $data['action'] ?? 'redeem'; // "redeem" or "use"

if (!$rewardID) {
    echo json_encode(['success'=>false,'message'=>'Invalid reward']);
    exit;
}

// --- Fetch reward info ---
$conn->select_db('if0_40284661_sprs_mainredo');
$stmt = $conn->prepare("SELECT rewardName, rewardType FROM rewards WHERE rewardID = ?");
$stmt->bind_param("i", $rewardID);
$stmt->execute();
$stmt->bind_result($rewardName, $rewardType);
if (!$stmt->fetch()) {
    echo json_encode(['success'=>false,'message'=>'Reward not found']);
    exit;
}
$stmt->close();

// --- Function to log activity ---
function logActivity($conn, $studentID, $type, $desc) {
    $stmt = $conn->prepare("INSERT INTO student_activity_log (studentID, type, description, logDate) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iss", $studentID, $type, $desc);
    $stmt->execute();
    $stmt->close();
}

if($action === 'redeem'){
    // --- Redeem: deduct points and add to inventory ---
    $pointsRequired = intval($data['pointsRequired'] ?? 0);
    if (!$pointsRequired) {
        echo json_encode(['success'=>false,'message'=>'Invalid points']);
        exit;
    }

    $conn->select_db('if0_40284661_sprs_dummydb');
    $stmt = $conn->prepare("SELECT points FROM users WHERE id=?");
    $stmt->bind_param("i", $studentID);
    $stmt->execute();
    $stmt->bind_result($currentPoints);
    $stmt->fetch();
    $stmt->close();

    if ($currentPoints < $pointsRequired) {
        echo json_encode(['success'=>false,'message'=>'Not enough points']);
        exit;
    }

    $newPoints = $currentPoints - $pointsRequired;
    $stmt = $conn->prepare("UPDATE users SET points=? WHERE id=?");
    $stmt->bind_param("ii", $newPoints, $studentID);
    $stmt->execute();
    $stmt->close();

    $conn->select_db('if0_40284661_sprs_mainredo');
    $stmt = $conn->prepare("INSERT INTO student_inventory (studentID, rewardID, dateAdded) VALUES (?, ?, NOW())");
    $stmt->bind_param("ii", $studentID, $rewardID);
    $stmt->execute();
    $stmt->close();

    // --- Log redeem activity ---
    logActivity($conn, $studentID, "Reward Redeemed", "Redeemed '$rewardName'");

    echo json_encode([
        'success'=>true,
        'newPoints'=>$newPoints,
        'rewardName'=>$rewardName,
        'rewardType'=>$rewardType,
        'date'=>date("M d, Y"),
        'message'=>"You have successfully redeemed '$rewardName'."
    ]);
    exit;
}

elseif($action === 'use'){
    // --- Use: remove from inventory and log activity ---
    $conn->select_db('if0_40284661_sprs_mainredo');

    $stmt = $conn->prepare("DELETE FROM student_inventory WHERE studentID=? AND rewardID=? LIMIT 1");
    $stmt->bind_param("ii", $studentID, $rewardID);
    $stmt->execute();
    $stmt->close();

    // --- Log use activity ---
    logActivity($conn, $studentID, "Reward Used", "Used '$rewardName'");

    echo json_encode([
        'success'=>true,
        'message'=>"You have successfully used '$rewardName'.",
        'date'=>date("M d, Y")
    ]);
    exit;
}

else{
    echo json_encode(['success'=>false,'message'=>'Unknown action']);
    exit;
}
