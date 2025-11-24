<?php
error_reporting(E_ALL);         // for development, (E_ALL -> 0) disable for final product 
ini_set('display_errors', 1);   // ('display_errors', 1 -> 0)

$host = "sql213.infinityfree.com";
$user = "if0_40284661";
$pass = "UtozBUyverLcMai";
$dbname = "if0_40284661_sprs_mainredo";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}
// ✅ No echo here — this file just connects quietly
?>