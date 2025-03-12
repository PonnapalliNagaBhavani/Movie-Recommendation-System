<?php
// Start the session
session_start();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Profiles";

try {
    // Create a connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Fetch form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Use a prepared statement to fetch user_id and hashed password
    $stmt = $conn->prepare("SELECT user_id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_id = $row['user_id'];
        $hashed_password = $row['password'];

        // Verify the entered password
        if (password_verify($password, $hashed_password)) {
            // Set session variable for the user
            $_SESSION['user_email'] = $email;
            
            // Set the user ID in a cookie
            setcookie("user_id", $user_id, time() + 3600, "/");

            
            header("Location: survey.html?user_id=" . urlencode($user_id));
            exit();
        } else {
            header("Location: login.html?error=" . urlencode("Invalid password!"));
            exit();
        }
    } else {
        header("Location: login.html?error=" . urlencode("No user found with this email!"));
        exit();
    }

} catch (mysqli_sql_exception $e) {
    error_log("Connection failed: " . $e->getMessage());
    echo "An error occurred. Please try again later.";
}

$conn->close();
?>

