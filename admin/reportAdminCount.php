<?php 
header('Content-Type: application/json; charset=utf-8');
include ('../dummy/connection_dummydb.php');

$sql = "SELECT COUNT(*) AS totalAdmins FROM users WHERE role = 'admin' ";
$result = $conn->query($sql);
$adminCount = $result->fetch_assoc();

echo json_encode($adminCount);
?>