<?php
// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gaudicosddl";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$user = $_POST['username'];
$pass = $_POST['password'];

// Insert new user into the database
$sql = "INSERT INTO users (username, password) VALUES ('$user', '$pass')";
if ($conn->query($sql) === TRUE) {
    header('Location: login.php');  // Redirect to login after registration
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
