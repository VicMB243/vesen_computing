<?php


//if current user's credentials(id or email) are not found in the db,
// he/she is deconnected and redirected to login screen


$uid =  $_COOKIE['auth_id'];
$email = $_COOKIE['auth_email'];

$sql = "SELECT user_id FROM users WHERE user_id='$uid' AND email='$email'";
$result = mysqli_query($link,$sql);

if (mysqli_num_rows($result)==0)
{
    echo "Unauthorized access!";
    exit;
}


?>