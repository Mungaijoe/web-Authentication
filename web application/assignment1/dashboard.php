<?php
// Include config file
require_once "includes/config.php";
require_once "includes/functions.php";
require_once "includes/auth.php";

// Check if the user is logged in, if not redirect to login page
if(!is_logged_in()) {
    redirect("index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to your dashboard.</h1>
        
        <div class="menu">
            <a href="submit_form.php" class="btn btn-primary">Submit Form</a>
            <a href="logout.php" class="btn btn-danger">Sign Out</a>
        </div>
        
        <div class="dashboard-content">
            <h2>Your Submissions</h2>
            
            <?php
            // Get user submissions
            $sql = "SELECT * FROM form_submissions WHERE user_id = ? ORDER BY created_at DESC";
            
            if($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "i", $param_user_id);
                $param_user_id = $_SESSION["user_id"];
                
                if(mysqli_stmt_execute($stmt)) {
                    $result = mysqli_stmt_get_result($stmt);
                    
                    if(mysqli_num_rows($result) > 0) {
                        echo '<table class="table">';
                        echo '<thead>';
                        echo '<tr>';
                        echo '<th>ID</th>';
                        echo '<th>Title</th>';
                        echo '<th>Description</th>';
                        echo '<th>Submitted</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';
                        
                        while($row = mysqli_fetch_array($result)) {
                            echo '<tr>';
                            echo '<td>' . $row['id'] . '</td>';
                            echo '<td>' . htmlspecialchars($row['title']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['description']) . '</td>';
                            echo '<td>' . $row['created_at'] . '</td>';
                            echo '</tr>';
                        }
                        
                        echo '</tbody>';
                        echo '</table>';
                    } else {
                        echo '<p>You have not submitted any forms yet.</p>';
                    }
                } else {
                    echo '<p>Error retrieving your submissions.</p>';
                }
                
                mysqli_stmt_close($stmt);
            }
            ?>
        </div>
    </div>
</body>
</html>

