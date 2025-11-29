<?php
$servername = "localhost";
$username = "root"; 
$password=""; 
$dbname = "medivault";

// connect to DB
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $fullname = $_POST['fullname'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $license = $_POST['license'];
  $specialization = $_POST['specialization'];
  $experience = $_POST['experience'];
  $qualification = $_POST['qualification'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $confirmpassword=$_POST['confirmpassword'];

  $sql = "INSERT INTO signupdoc (fullname, email, phone, license, specialization, experience, qualification, password, confirmpassword)
          VALUES ('$fullname', '$email', '$phone', '$license', '$specialization', '$experience', '$qualification', '$password','$confirmpassword')";

  if ($conn->query($sql) === TRUE) {
    header("Location: login.dt.html");
  } else {
    echo "Error: " . $conn->error;
  }
  if ($_POST['password'] !== $_POST['confirmpassword']) {
    die("Passwords do not match!");
}
}
$conn->close();
?>
