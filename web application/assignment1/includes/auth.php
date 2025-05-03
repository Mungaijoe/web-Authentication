<?php
require_once 'config.php';
require_once 'functions.php';

/**
 * Register a new user
 */
function register_user($username, $email, $password) {
    global $conn;
    
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Prepare an insert statement
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    
    if($stmt = mysqli_prepare($conn, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_email, $param_password);
        
        // Set parameters
        $param_username = $username;
        $param_email = $email;
        $param_password = $hashed_password;
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)) {
            return true;
        } else {
            return false;
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    return false;
}

/**
 * Login a user
 */
function login_user($username, $password) {
    global $conn;
    
    // Prepare a select statement
    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    
    if($stmt = mysqli_prepare($conn, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_username);
        
        // Set parameters
        $param_username = $username;
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)) {
            // Store result
            mysqli_stmt_store_result($stmt);
            
            // Check if username exists, if yes then verify password
            if(mysqli_stmt_num_rows($stmt) == 1) {                    
                // Bind result variables
                mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                if(mysqli_stmt_fetch($stmt)) {
                    if(password_verify($password, $hashed_password)) {
                        // Password is correct, start a new session
                        
                        // Store data in session variables
                        $_SESSION["loggedin"] = true;
                        $_SESSION["user_id"] = $id;
                        $_SESSION["username"] = $username;
                        
                        // Regenerate session ID for security
                        session_regenerate_id(true);
                        
                        return true;
                    }
                }
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    return false;
}

/**
 * Logout a user
 */
function logout_user() {
    // Unset all session variables
    $_SESSION = array();
    
    // Destroy the session
    session_destroy();
    
    // Delete the session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
}
?>