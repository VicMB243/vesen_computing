<?php



//import database/connection page
include "database.php";

//import restricted.php page because this is a restricted area, only authenticated users can see it
//if current user's credentials(id or email) are not found in the db he/she is deconnected and redirected to login screen
include "restricted.php";


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .wrapper{
            width: 800px;
            margin: 0 auto;
        }
        table tr td:last-child{
            width: 120px;
        }
    </style>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>
<body>
<div style="padding-top: 30px; padding-right: 30px">
<a href="logoff.php" class="btn btn-primary pull-right"><i class="fa fa-user"></i> Logout</a></div>
    <div class="wrapper">
    
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">Users Details</h2>
                        <a href="add_admin.php" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Add New Admin</a>
                        
                    </div>


                    <?php
                    // Include config file
                    require_once "database.php";
                    
                    // Attempt select query execution
                    //display existing users in a tabled ordered in descending approach 
                    //so that add/edit/delete operations can be performed depending on assigned admin privileges

                    $sql = "SELECT * FROM admins ORDER BY user_id DESC";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo '<table class="table table-bordered table-striped">';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>#</th>";
                                        echo "<th>email</th>";
                                        echo "<th>rights</th>";
                                        echo "<th>date</th>";
                                        echo "<th>Action</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['user_id'] . "</td>";
                                        echo "<td>" . $row['email'] . "</td>";
                                        echo "<td>" . $row['rights'] . "</td>";
                                        echo "<td>" . $row['date'] . "</td>";

                                        echo "<td>";
                                        //pass the selected user'id to the add/edit or the delete page depending on the clicked link
                                        //that is where current admin's rights are checked before any operation on the selected user(record)
                                            echo '<a href="add_admin.php?id='. $row['user_id'] .'" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                            
                                            echo '<a href="delete_record.php?id='. $row['user_id'] .'" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                        }
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }
 
                    // Close connection
                    mysqli_close($link);
                    ?>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>