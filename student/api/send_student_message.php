<?php
session_start();
include('../../db_connect.php');

// -------------------------
// Check student session
// -------------------------
if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'student') {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit();
}

$studentID = $_SESSION['userID'];
$data = json_decode(file_get_contents("php://input"), true);

$staffID = isset($data['staffID']) ? intval($data['staffID']) : null;
$message = isset($data['message']) ? trim($data['message']) : "";
$conversationID = isset($data['conversation_id']) ? intval($data['conversation_id']) : null;

$conn->select_db('sprs_mainredo');

// -------------------------
// Step 1: Find or create conversation
// -------------------------
if (!$conversationID) {
    if (!$staffID) {
        echo json_encode(['success' => false, 'error' => 'No staff selected']);
        exit();
    }

    $stmt = $conn->prepare("SELECT id FROM help_conversations WHERE studentID = ? AND staffID = ?");
    $stmt->bind_param("ii", $studentID, $staffID);
    $stmt->execute();
    $conv = $stmt->get_result()->fetch_assoc();

    if ($conv) {
        $conversationID = $conv['id'];
    } else {
        $stmt2 = $conn->prepare("INSERT INTO help_conversations (studentID, staffID, status, last_updated) VALUES (?, ?, 'open', NOW())");
        $stmt2->bind_param("ii", $studentID, $staffID);
        $stmt2->execute();
        $conversationID = $stmt2->insert_id;
    }
}

// -------------------------
// Step 2: Insert student message
// -------------------------
if ($message !== "") {
    $stmt3 = $conn->prepare("INSERT INTO help_messages (conversation_id, sender, message, created_at) VALUES (?, 'student', ?, NOW())");
    $stmt3->bind_param("is", $conversationID, $message);
    $stmt3->execute();

    // -------------------------
    // SMS notification — only on first student message
    // -------------------------
    $stmtCheck = $conn->prepare("SELECT COUNT(*) FROM help_messages WHERE conversation_id = ? AND sender='student'");
    $stmtCheck->bind_param("i", $conversationID);
    $stmtCheck->execute();
    $stmtCheck->bind_result($studentMsgCount);
    $stmtCheck->fetch();
    $stmtCheck->close();

    if ($studentMsgCount === 1) {
        // Get assigned staffID
        $getStaff = $conn->prepare("SELECT staffID FROM help_conversations WHERE id = ?");
        $getStaff->bind_param("i", $conversationID);
        $getStaff->execute();
        $getStaff->bind_result($assignedStaffID);
        $getStaff->fetch();
        $getStaff->close();

        if ($assignedStaffID) {
            // Switch to dummydb to get phone
            $conn->select_db('sprs_dummydb');

            $getPhone = $conn->prepare("SELECT contact_number, name FROM users WHERE id = ? AND role='admin'");
            $getPhone->bind_param("i", $assignedStaffID);
            $getPhone->execute();
            $getPhone->bind_result($adminPhone, $adminName);
            $getPhone->fetch();
            $getPhone->close();

            if ($adminPhone) {
                // Switch back to main DB
                $conn->select_db('sprs_mainredo');

                // Prepare SMS
                $api_token = "2ce1d87ab317b026eaf5a91f256d63e628e8306c";
                $smsMessage = "New Helpdesk Message from Student $studentID: $message";

                $endpoint = "https://sms.iprogtech.com/api/v1/sms_messages";
                $postData = http_build_query([
                    "api_token" => $api_token,
                    "phone_number" => $adminPhone,
                    "message" => $smsMessage
                ]);

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $endpoint);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
                $response = curl_exec($ch);
                curl_close($ch);
            }
        }
    }
}

// -------------------------
// Step 3: Update conversation timestamp
// -------------------------
$stmt4 = $conn->prepare("UPDATE help_conversations SET last_updated = NOW() WHERE id = ?");
$stmt4->bind_param("i", $conversationID);
$stmt4->execute();

// -------------------------
// Return success
// -------------------------
echo json_encode(['success' => true, 'conversation_id' => $conversationID]);
?>
