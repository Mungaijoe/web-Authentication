<?php
session_start();

// Enable error reporting for development (turn off in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database configuration constants
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'user');
define('DB_PASSWORD', 'Nzomo@bnb2');
define('DB_NAME', 'user_management');

// Attempt to connect to MySQL database using defined constants
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn === false) {
    // Log error for development (make sure to disable in production)
    error_log("Connection failed: " . mysqli_connect_error());

    // Display a user-friendly message
    die("Sorry, we are unable to connect to the database at this time.");
}

// Set session cookie parameters for security
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);

// Ensure you're using HTTPS before enabling cookie_secure
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    ini_set('session.cookie_secure', 1);
} else {
    ini_set('session.cookie_secure', 0);
}

// Security headers
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("Content-Security-Policy: default-src 'self'");

// Example of prepared statement (e.g., to prevent SQL injection)
$username = 'example_user'; // Sample input; in real use, get from form or input
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username); // "s" for string
$stmt->execute();
$result = $stmt->get_result();

// Fetch and process the result (example)
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "User ID: " . $row['id'] . " - Username: " . $row['username'] . "<br>";
    }
} else {
    echo "No user found.";
}

// Close the statement and connection when done
$stmt->close();
mysqli_close($conn);

?>
