<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection parameters
$servername = "localhost"; 
$username = "root";        
$password = "";            
$dbname = "Profiles";  

// Create a connection to the MySQL database
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch form data and sanitize it
$name = $conn->real_escape_string($_POST['name']);
$email = $conn->real_escape_string($_POST['email']);
$password = $conn->real_escape_string($_POST['password']);
$confirm_password = $conn->real_escape_string($_POST['confirm_password']);
$age = $conn->real_escape_string($_POST['age']);
$gender = $conn->real_escape_string($_POST['gender']);
$phone = $conn->real_escape_string($_POST['phone']);
$location = $conn->real_escape_string($_POST['location']);
$dob = $conn->real_escape_string($_POST['dob']);

// Password validation
if ($password !== $confirm_password) {
    die("Passwords do not match!");
}

// Encrypt the password before storing it
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$user_id = uniqid('user_'); // Generate a unique user ID
$sql = "INSERT INTO users (user_id, name, email, password, age, gender, phone, location, dob)
        VALUES ('$user_id', '$name', '$email', '$hashed_password', '$age', '$gender', '$phone', '$location', '$dob')";

// Execute the query and check if the data was inserted successfully
if ($conn->query($sql) === TRUE) {
    // Store the user ID in a cookie for 1 hour
    setcookie("user_id", $user_id, time() + 3600, "/");

    header("Location: Login.html?message=Registration successful!");
} else {
    header("Location: additional-details.html?error=Registration failed! Please try again.");
}



$conn->close();
?>
