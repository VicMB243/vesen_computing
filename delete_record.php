<?php 

 // Include config file
 require_once "database.php";

$uid =  $_COOKIE['auth_id'];
$email = $_COOKIE['auth_email'];

//get current admin's rights from db and perform delete operation if he/she is allowed to do so
//otherwise show the "Not Allowed" dialog and go back to dashboard screen

$sql = "SELECT rights FROM users WHERE user_id='$uid' AND email='$email'";
$result = mysqli_query($link,$sql);

if (mysqli_num_rows($result)==0)
{
    header("location: logoff.php");
    exit;
}
while($row = mysqli_fetch_array($result)){
    //verify if user has the right to perform delete operation
    if (strpos($row['rights'], 'Delete') !== false) {
        
     }else{
        header("location: not_allowed_dialog.php");
        exit;
     }
   
}



// Process delete operation after confirmation
if(isset($_POST["id"]) && !empty($_POST["id"])){
   
    
    // Prepare a delete statement
    $sql = "DELETE FROM users WHERE user_id = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Set parameters
        $param_id = trim($_POST["id"]);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            // Records deleted successfully. Redirect to landing page
            header("location: dashboard.php");
            exit();
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
     
    // Close statement
    mysqli_stmt_close($stmt);
    
    // Close connection
    mysqli_close($link);
} else{
    // Check existence of id parameter
    if(empty(trim($_GET["id"]))){
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5 mb-3">Delete Record</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger">
                            <input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>"/>
                            <p>Are you sure you want to delete this user record?</p>
                            <p>
                                <input type="submit" value="Yes" class="btn btn-danger">
                                <a href="dashboard.php" class="btn btn-secondary">No</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
