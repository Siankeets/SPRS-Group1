<?php
session_start();
include('../db_connect.php');

// Student must be logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'student') {
    die("Access denied. Please log in as a student.");
}

$username = $_SESSION['username'];
$studentID = $_SESSION['userID'];

// Check QR parameter
if (!isset($_GET['qr'])) {
    die("Invalid QR code.");
}

$qrCode = trim($_GET['qr']);
$filePath = __DIR__ . '/qrcodes.txt';

if (!file_exists($filePath)) {
    die("QR system error: file not found.");
}

$lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$updatedLines = [];

$qrFound = false;
$qrUsed = false;
$pointsToAdd = 0;
$reason = "";

// 🔍 Check QR validity
foreach ($lines as $line) {
    list($code, $status, $points, $desc) = explode('|', $line);

    if ($code === $qrCode) {
        $qrFound = true;

        if ($status !== 'unused') {
            $qrUsed = true;
        } else {
            $pointsToAdd = intval($points);
            $reason = $desc;

            // Mark as used
            $updatedLines[] = "$code|used|$points|$desc";
            continue;
        }
    }

    $updatedLines[] = $line;
}

// ❌ QR not found
if (!$qrFound) {
    die("QR code does not exist or has been deleted.");
}

// ❌ QR already used
if ($qrUsed) {
    die("This QR code has already been redeemed.");
}

// 🔄 Save updated QR list (mark as used)
file_put_contents($filePath, implode("\n", $updatedLines) . "\n");

// ⭐ Add points to the student
$conn->select_db('sprs_dummydb');
$stmt = $conn->prepare("UPDATE users SET points = points + ? WHERE id = ?");
$stmt->bind_param("ii", $pointsToAdd, $studentID);
$stmt->execute();
$stmt->close();

// Update live session points
$_SESSION['points'] += $pointsToAdd;

// Redirect student back with a success message
header("Location: ../student/student_index.php?earned=$pointsToAdd&reason=" . urlencode($reason));
exit();
?>
