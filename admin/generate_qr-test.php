<?php
header('Content-Type: application/json');

$file = __DIR__ . '/qrcodes.txt';
if (!file_exists($file)) file_put_contents($file, '');

// DELETE QR
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $deleteCode = trim($_POST['delete']);

    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $updated = [];

    foreach ($lines as $line) {
        list($code, $points, $reason, $status) = explode('|', $line);
        if ($code !== $deleteCode) {
            $updated[] = $line;
        }
    }

    file_put_contents($file, implode("\n", $updated) . (count($updated) ? "\n" : ""));
    echo json_encode(["success" => true, "message" => "QR deleted"]);
    exit;
}

// GENERATE QR
if (isset($_POST['points']) && isset($_POST['reason'])) {

    $points = intval($_POST['points']);
    $reason = trim($_POST['reason']);

    if ($points <= 0 || $reason === '') {
        echo json_encode(["success" => false, "message" => "Invalid inputs."]);
        exit;
    }

    // unique code only
    $code = bin2hex(random_bytes(8));

    // format: code|points|reason|unused
    file_put_contents($file, "$code|$points|$reason|unused\n", FILE_APPEND);

    // QR contains ONLY the code
    $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=" . urlencode($code);

    echo json_encode([
        "success" => true,
        "code" => $code,
        "qrPath" => $qrUrl,
        "points" => $points,
        "reason" => $reason
    ]);

    exit;
}

// fallback
echo json_encode(["success" => false, "message" => "Invalid request"]);
?>
