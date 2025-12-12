<?php
$servername = "sql213.infinityfree.com";
$username = "if0_40284661";         
$password = "UtozBUyverLcMai";              
$database = "if0_40284661_sprs_dummydb"; 

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- Set MySQL session timezone to Philippines (UTC+8) ---
$conn->query("SET time_zone = '+08:00'");

// echo "Connected successfully"; commented out for reports testing, echo interferes with my json
?>
