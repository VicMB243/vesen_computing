<?php

//import database config file
    include "database.php";

    
    $op = filter_input(INPUT_POST, 'op');
    $email = filter_input(INPUT_POST, 'email');
    $pass = filter_input(INPUT_POST, 'pass');
    $failed = null;

    if ($op=="login")
    {
        //if email and password exists in db, echo "Success!" and login user then go to dashboard screen;
        $sql = "SELECT user_id,username FROM users WHERE email='$email' AND PASSWORD('$pass') = password";
        $result = mysqli_query($link,$sql);
        // FAILED LOGIN
        if (!$result || mysqli_num_rows($result)==0)
        {
            //echo "Nothing found 
            $failed=1;
        }

        
        else
        {
            //echo "Found! Login OK!";
            //Let's save the user's ID to cookie and direct him to landing page(dashboard)
            //we could also receive a token from the backend and save it to persistent local storage
            // in order to remember the user in future and redeirect him to home page directly if toke is still valid
            $row = $result->fetch_row();

            $db_id = $row[0];

        

            setcookie("auth_id","");
            setcookie("auth_email","");
            
            setcookie("auth_id","$db_id");
            setcookie("auth_email","$email");

            
            header("Location:dashboard.php");

            exit;
        }



    }

?>

<!DOCTYPE HTML>

<html>

<head>
    <title>Login</title>

            <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

</head>

<body>



<div class="container p-3 col-md-4 col-sm-4 col-md-offset-4" style="padding-top: 100px;">

    <?php
    if ($failed==1)
    {
    ?>
              <div class="panel panel-danger">
                <div class="panel-heading">Login error</div>
                <div class="panel-body">Wrong login or password</div>
              </div>

    <?php
    }
    ?>

    <div class="panel panel-default">
    <div class="panel-heading"><h3><b>Login page</b></h3></div>
    <div class="panel-body">


        <form method=POST action=login.php>

            <div class="form-group">
                <label>Email:</label>
                <input type="text" class="form-control" autocomplete=off required name="email"/>
            </div>

            <div class="form-group">
                <label>Password:</label>
                <input type="password" class="form-control"  required name="pass"/>
            </div>


            <div class="form-group">
                <label></label>
                <input type="submit" class="form-control btn btn-primary" value="Login"/>
            </div>
            

            <input type="hidden" name="op" value="login" />
            <div class="form-group">
            
            <a href="registration.php">Register</a>
            </div>

        </form>
    </div>
    </div>
</div>

</body>

</html>