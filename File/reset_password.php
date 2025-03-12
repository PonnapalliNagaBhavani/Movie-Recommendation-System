<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Profiles";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $new_password = $conn->real_escape_string($_POST['password']);

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

    // Update the password in the database
    $sql = "UPDATE users SET password = ? WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $hashed_password, $email);

    if ($stmt->execute()) {
        echo "<script>alert('Password has been successfully reset!'); window.location.href='login.html';</script>";
    } else {
        echo "<script>alert('Error updating password: " . $conn->error . "'); window.location.href='reset-password.html?email=" . urlencode($email) . "';</script>";
    }

    $stmt->close();
}

$conn->close();
?>

