<?php
header('Content-Type: application/json');

// Path to store QR records
$file = __DIR__ . '/qrcodes.txt';
if (!file_exists($file)) file_put_contents($file, '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ✅ DELETE QR
    if (isset($_POST['delete'])) {
        $deleteCode = trim($_POST['delete']);
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $updated = [];

        foreach ($lines as $line) {
            if (!str_contains($line, $deleteCode)) {
                $updated[] = $line;
            }
        }
        file_put_contents($file, implode("\n", $updated) . "\n");
        echo json_encode(["success" => true, "message" => "QR deleted"]);
        exit;
    }

    // ✅ GENERATE NEW QR
    if (isset($_POST['points'])) {
        $uniqueCode = uniqid('qr_', true);
        $points = intval($_POST['points']);
        $reason = $_POST['reason'] ?? '';

        file_put_contents($file, "$uniqueCode|unused|$points|$reason\n", FILE_APPEND);

        $serverURL = "http://localhost/SPRS/SPRS-Group1/admin"; //babaguhin sa google cloud

        $qrData = "$serverURL/verify_qr-test.php?qr=$uniqueCode"; //sa admin pero papunta/directory sa student dashboard using unique student IT

        $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=" . urlencode($qrData);

        echo json_encode([
            "success" => true,
            "code" => $uniqueCode,
            "qrPath" => $qrUrl,
            "points" => $points,
            "reason" => $reason
        ]);
        exit;
    }
}

// Default fallback
echo json_encode(["success" => false, "message" => "Invalid request"]);
?>
