<?php
session_start();
include('../../db_connect.php');

$conn->select_db('sprs_dummydb');

$result = $conn->query("SELECT id, name, department, program FROM users WHERE role='admin' ORDER BY name ASC");

$staff = [];
while($row = $result->fetch_assoc()){
    $staff[] = $row;
}

echo json_encode($staff);
?>
