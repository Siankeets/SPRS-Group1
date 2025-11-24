<?php
session_start();
include('../db_connect.php');
header('Content-Type: application/json');

// Must be student
if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'student') {
    echo json_encode(["success" => false, "message" => "Access denied. Log in as student."]);
    exit;
}

$studentID = intval($_SESSION['userID']);

// Expecting POST with "qr"
if (!isset($_POST['qr'])) {
    echo json_encode(["success" => false, "message" => "No QR code provided."]);
    exit;
}

$qrCode = trim($_POST['qr']);
$qrCode = preg_replace('/[^A-Za-z0-9]/', '', $qrCode);

if ($qrCode === '') {
    echo json_encode(["success" => false, "message" => "Invalid QR format."]);
    exit;
}

$file = __DIR__ . '/qrcodes.txt';
if (!file_exists($file)) {
    echo json_encode(["success" => false, "message" => "QR system error."]);
    exit;
}

$lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$found = false;
$used = false;
$points = 0;
$reason = "";
$newLines = [];

foreach ($lines as $line) {
    list($code, $p, $desc, $status) = explode('|', $line);

    if ($code === $qrCode) {
        $found = true;

        if ($status !== "unused") {
            $used = true;
            $newLines[] = $line;
        } else {
            $points = intval($p);
            $reason = $desc;
            $newLines[] = "$code|$p|$desc|used";
        }
    } else {
        $newLines[] = $line;
    }
}

if (!$found) {
    echo json_encode(["success" => false, "message" => "QR code does not exist."]);
    exit;
}

if ($used) {
    echo json_encode(["success" => false, "message" => "This QR code was already redeemed."]);
    exit;
}

// Save updates
file_put_contents($file, implode("\n", $newLines) . "\n");

// Update database
$conn->select_db('if0_40284661_sprs_dummydb');

$stmt = $conn->prepare("UPDATE users SET points = points + ? WHERE id = ?");
$stmt->bind_param("ii", $points, $studentID);
$stmt->execute();

// fetch new total
$stmt2 = $conn->prepare("SELECT points FROM users WHERE id = ?");
$stmt2->bind_param("i", $studentID);
$stmt2->execute();
$stmt2->bind_result($newTotal);
$stmt2->fetch();
$stmt2->close();

$_SESSION['points'] = $newTotal;

echo json_encode([
    "success" => true,
    "message" => "Redeemed successfully!",
    "pointsAdded" => $points,
    "reason" => $reason,
    "newTotal" => intval($newTotal)
]);
?>
