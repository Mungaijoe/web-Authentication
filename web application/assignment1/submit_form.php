<?php
// Include config file
require_once "includes/config.php";
require_once "includes/functions.php";
require_once "includes/auth.php";

// Check if the user is logged in, if not redirect to login page
if(!is_logged_in()) {
    redirect("index.php");
}

// Define variables and initialize with empty values
$title = $description = "";
$title_err = $description_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate CSRF token
    if (!verify_csrf_token($_POST['csrf_token'])) {
        die("CSRF token validation failed");
    }
    
    // Validate title
    if(empty(trim($_POST["title"]))) {
        $title_err = "Please enter a title.";
    } else {
        $title = clean_input($_POST["title"]);
    }
    
    // Validate description
    if(empty(trim($_POST["description"]))) {
        $description_err = "Please enter a description.";
    } else {
        $description = clean_input($_POST["description"]);
    }
    
    // Check input errors before inserting in database
    if(empty($title_err) && empty($description_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO form_submissions (user_id, title, description) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "iss", $param_user_id, $param_title, $param_description);
            
            // Set parameters
            $param_user_id = $_SESSION["user_id"];
            $param_title = $title;
            $param_description = $description;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)) {
                // Redirect to dashboard
                redirect("dashboard.php");
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Form</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Submit a New Form</h2>
        <p>Please fill in the fields below to submit a new form.</p>
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="submission-form">
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" id="title" class="form-control <?php echo (!empty($title_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $title; ?>">
                <span class="invalid-feedback"><?php echo $title_err; ?></span>
            </div>    
            
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" id="description" class="form-control <?php echo (!empty($description_err)) ? 'is-invalid' : ''; ?>"><?php echo $description; ?></textarea>
                <span class="invalid-feedback"><?php echo $description_err; ?></span>
            </div>
            
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <a href="dashboard.php" class="btn btn-secondary ml-2">Cancel</a>
            </div>
        </form>
    </div>
    
    <script src="js/validation.js"></script>
</body>
</html>