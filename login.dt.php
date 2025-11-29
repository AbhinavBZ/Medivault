<?php
session_start(); // start session early

// DB connection (adjust credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "medivault";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get input safely
$license = trim($_POST['license'] ?? '');
$password_input = $_POST['password'] ?? '';

// Basic validation
if (empty($license) || empty($password_input)) {
    // send back with error (or show message)
    die("Please provide both license and password.");
}

// Prepared statement to avoid SQL injection
$stmt = $conn->prepare("SELECT fullname, email, password FROM signupdoc WHERE license = ? LIMIT 1");
$stmt->bind_param("s", $license);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $stored_hash = $row['password']; // hashed password stored at signup

    if (password_verify($password_input, $stored_hash)) {
        // Authentication successful
        // Set session variables
        $_SESSION['doctor_name'] = $row['fullname'];
        $_SESSION['doctor_email'] = $row['email'];
        $_SESSION['logged_in'] = true;
        $_SESSION['last_activity'] = time();
        $_SESSION['doctor_license'] = $row['license'];

        // Redirect to dashboard
        header("Location: dashboard.dt.html");
        exit;
    } else {
        // wrong password
        echo "Invalid license or password.";
    }
} else {
    // no account with that license
    echo "Invalid license or password.";
}

$stmt->close();
$conn->close();
?>
