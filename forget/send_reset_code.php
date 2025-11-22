<?php
session_start();

// Connection to sprs_dummydb
$dummydb_conn = new mysqli("localhost", "root","" , "sprs_dummydb");
if ($dummydb_conn->connect_error) {
    die("Connection failed: " . $dummydb_conn->connect_error);
}

$rawPhone = $_POST['phone'] ?? '';

$cleanPhone = preg_replace('/\D/', '', $rawPhone);

if (!preg_match('/^09\d{9}$/', $cleanPhone)) {
    echo "<script>alert('❌ Invalid phone number format.'); window.location.href='forgot.html';</script>";
    exit;
}

$stmt = $dummydb_conn->prepare("SELECT id FROM users WHERE contact_number = ?");
$stmt->bind_param("s", $cleanPhone);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    echo "<script>alert('❌ This phone number is not linked to any account.'); window.location.href='forgot.html';</script>";
    exit;
}
$stmt->close();

$otp = random_int(100000, 999999);


$_SESSION['otp'] = $otp;
$_SESSION['otp_phone'] = $cleanPhone;
$_SESSION['otp_expires'] = time() + 300;


$api_token = "2ce1d87ab317b026eaf5a91f256d63e628e8306c";
$message = "Your Student Point Reward System reset code is: $otp (valid for 5 mins)";
$endpoint = "https://sms.iprogtech.com/api/v1/sms_messages";

$url = $endpoint . "?api_token=" . urlencode($api_token)
     . "&phone_number=" . urlencode($cleanPhone)
     . "&message=" . urlencode($message);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlerr = curl_error($ch);
curl_close($ch);

if ($response === false || $httpcode >= 400) {
    die("<p style='color:red; text-align:center;'>❌ Failed to send SMS. Please try again later.<br>HTTP $httpcode — $curlerr — Response: $response</p>");
}

echo "<script>
        alert('✅ Reset code sent to your phone number. Please check your SMS.');
        window.location.href='verify_code.php?phone=" . urlencode($cleanPhone) . "';
      </script>";
exit;
?>
