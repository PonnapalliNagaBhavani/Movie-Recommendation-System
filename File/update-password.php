<?php

// Replace 'DB_HOST', 'DB_USERNAME', 'DB_PASSWORD', and 'DB_NAME' with your actual database credentials
$conn = new mysqli('localhost', 'root', '', 'Profiles');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $newPassword = $_POST["new_password"];

    if (empty($email) || empty($newPassword)) {
        echo "Please provide both email and new password.";
        exit;
    }

    // Hash the new password for security
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update the password in the database
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $hashedPassword, $email);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Password updated successfully.";
    } else {
        echo "Error occurred while updating the password. Please try again.";
    }

    $stmt->close();
}