<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.dt.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "medivault");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$license = $_SESSION['doctor_license'];
$sql = "SELECT fullname, email, phone, license,specialization,qualification,experience 
        FROM signupdoc WHERE license = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $license);
$stmt->execute();
$result = $stmt->get_result();
$doctor = $result->fetch_assoc();
?>
