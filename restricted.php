<?php


//if current user's credentials(id or email) are not found in the db,
// he/she is deconnected and redirected to login screen

if(isset($_COOKIE['auth_id']) && isset($_COOKIE['auth_email'])){
    $uid =  $_COOKIE['auth_id'];
    $email = $_COOKIE['auth_email'];


    $sql = "SELECT user_id FROM users WHERE user_id='$uid' AND email='$email'";
    $result = mysqli_query($link,$sql);

    if (mysqli_num_rows($result)==0)
    {
        echo '<!DOCTYPE html>
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
                            <h2 class="mt-5 mb-3">Error</h2>
                               <div class="alert alert-danger">
                                    
                                    <p>Unauthorized access!</br>
                                     Please try again later.</p>
                                    <p>
                                       
                                        <a href="logoff.php" class="btn btn-secondary">OK</a>
                                    </p>
                                </div>
                           
                        </div>
                    </div>        
                </div>
            </div>
        </body>
        </html>';
        exit;
    }

}else{
    echo '<!DOCTYPE html>
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
                            <h2 class="mt-5 mb-3">Error</h2>
                            
                                <div class="alert alert-danger">
                                    
                                    <p>Unauthorized access!</br>
                                     Please try again later.</p>
                                    <p>
                                       
                                        <a href="logoff.php" class="btn btn-secondary">OK</a>
                                    </p>
                                </div>
                            
                        </div>
                    </div>        
                </div>
            </div>
        </body>
        </html>';
    exit;

}



?>