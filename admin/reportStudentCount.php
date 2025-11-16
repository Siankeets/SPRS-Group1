<?php 
header('Content-Type: application/json; charset=utf-8');
include ('../dummy/connection_dummydb.php');

$sql = "SELECT COUNT(*) AS totalStudents FROM users WHERE role = 'student' ";
$result = $conn->query($sql);
$studCount = $result->fetch_assoc();

echo json_encode($studCount);
?>