<!DOCTYPE HTML>

<html>

<head>
  <title>Edit admin</title>


      <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <!-- Include Date Range Picker -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>



    <
</script>
</head>

<body>



<?php

//import db config file

   include "database.php"; 



    $uid =  $_COOKIE['auth_id'];
    $uEmail = $_COOKIE['auth_email'];
    


    
    $op = filter_input(INPUT_POST, 'op');
    
    
    
    $name = filter_input(INPUT_POST, 'name');
    $email = filter_input(INPUT_POST, 'email');
    
    $date = date("Y-m-d H:i:s");
     
     $createNewAccount = "false";
     $id = filter_input(INPUT_POST, 'id');;
   
     $adminRights = implode('|',["Add"]);
    
    

    


        if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){ 

            // Get URL parameter
            $id =  trim($_GET["id"]);









            //See if current admin's a genuine account and get the assigned rights/privileges
            // or deconnect him if account does not exist

            $sql = "SELECT rights FROM admins WHERE email='$uEmail'";
            $result = mysqli_query($link,$sql);
            
            if (mysqli_num_rows($result)==0)
            {
                header("location: not_allowed_dialog.php");
                exit;
            }
            while($row = mysqli_fetch_array($result)){
                //verify if user has the right to perform edit operation and go to " Not Allowed" dialog if not
                //or if the seleted user/record is the current admin's account, edit operation can be performed
                // as it is his own account. For more details on this, refer to the readme.md file last paragraph
                if (strpos($row['rights'], 'Edit') !== false || $uid === $id) {
                   
                 }else{
                    
                    header("location: not_allowed_dialog.php");
                    exit;
                 }
               
            }









           

            //If url parameter has a valid id, get that user's values from db and set them as default in the html form
            //then edit them if necessary or it will be a new account to be created
            
            // Prepare a select statement
            $sql = "SELECT * FROM admins WHERE user_id = ?";
            if($stmt = mysqli_prepare($link, $sql)){

                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "i", $param_id);
                
                // Set parameters
                $param_id = $id;
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    $result = mysqli_stmt_get_result($stmt);
        
                    if(mysqli_num_rows($result) == 1){
                    
                        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                        
                        // Retrieve individual field values and assign them to variables
                        $id = $row["user_id"];
                        $name = $row["username"];
                        $email = $row["email"];
                        $adminRights = $row["rights"];


                    
                    } 
                    
            } else{
                header("location: error.php");
            }
        }else{
            header("location: error.php");
        }
        
            // Close statement
            mysqli_stmt_close($stmt);
            
            // Close connection
            mysqli_close($link);
     } 

    
    //to be executed on "save" operation (when the save btn is pressed)
    if ($op=="save")
    {
        

       
        
        //get the admin rights selected from checkboxes and assign them to the selected user/record 
        // or set default value which is the right to "Add" new

        if(!empty($_POST['adminRights'])){

            $adminRights = $_POST['adminRights'];
            // Loop to store and display values of individual checked checkbox.
            
            }else{$adminRights = ["Add"];}
            
            
        //verify if account exists or not and perform either update operation or create new account accordingly
        $sql = "SELECT user_id FROM admins WHERE user_id='$id' AND email='$email'";
        $result = mysqli_query($link,$sql);

        if (mysqli_num_rows($result)==0)
        {
           
            // Prepare an insert statement
        $sql = "INSERT INTO admins (username, email, rights, date) VALUES (?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssss", $param_name, $param_email, $param_rights, $param_date);
            
            // Set parameters
            $param_name = $name;
            $param_email = $email;
            $param_rights = implode('|',$adminRights);
            $param_date = $date;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                 // Records updated successfully. Redirect to landing page
                 ?>
                    <div class="container p-3">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">Success!</div>
                                    <div class="panel-body">Admin registration was successful.</div>
                                </div>
                    </div>
                 <?php
                    header("location: dashboard.php");
                    exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
            // Close statement
            mysqli_stmt_close($stmt);


       }
         else{

           
            
                    // Prepare an update statement
                $sql = "UPDATE admins SET username=?, email=?, rights=?, date=? WHERE user_id=?";
                
                if($stmt = mysqli_prepare($link, $sql)){
                    // Bind variables to the prepared statement as parameters
                    $stmt->bind_param("ssssi", $param_name, $param_email, $param_rights, $param_date, $param_id);
                    
                    // Set parameters
                    $param_name = $name;
                    $param_email = $email;
                    $param_rights = implode('|',$adminRights);
                    $param_date = $date;
                    $param_id = $id;

                   
                   
                    // Attempt to execute the prepared statement
                    if(mysqli_stmt_execute($stmt)){
                        // Records updated successfully. Redirect to landing page
                       
                        ?>
                        <div class="container p-3">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">Success!</div>
                                        <div class="panel-body">Admin registration was successful.</div>
                                    </div>
                        </div>
        
                       
                     <?php
                      
            

                        header("location: dashboard.php");
                        
                    } else{
                        
                        echo "MySQL Error: " . mysqli_error($link);
                    }
                     // Close statement
                    mysqli_stmt_close($stmt);
                     // Close statement
                   
     
                }
                
        
         }


        


    }
    

?>

<div class="container p-3 col-md-4 col-sm-4 col-md-offset-4" style="padding-top: 30px;">

    <div class="panel panel-default">
    <div class="panel-heading"><h3><b>Add Admin</b></h3></div>
    <div class="panel-body">

        <form method=POST action=add_admin.php>

            

            <div class="form-group">
                <label>Name:</label>
                <input type="text" class="form-control" name="name" value="<?php echo $name; ?>" required/>
            </div>

            <div class="form-group">
                <label>Email:</label>
                <input type="email" class="form-control"  name="email" value="<?php echo $email; ?>" required/>
            </div>

            <div class="form-group">
                <label>Admin Rights: </label>






<?php 



//by default the corresponding checkboxes to users already assigned rights are checked

        if (strpos($adminRights, 'Add') !== false) {
            echo '<div class="checkbox"><label><input type="checkbox" name="adminRights[]" value="Add" checked >Add</label></div>';
                
        }else{
            echo '<div class="checkbox"><label><input type="checkbox" name="adminRights[]" value="Add" >Add</label></div>';
             
        }
        if (strpos($adminRights, 'Edit') !== false) {
            echo '<div class="checkbox"><label><input type="checkbox" name="adminRights[]" value="Edit" checked >Edit</label></div>';
                
        }else{
            echo '<div class="checkbox"><label><input type="checkbox" name="adminRights[]" value="Edit" >Edit</label></div>';
             
        }
        if (strpos($adminRights, 'Delete') !== false) {
            echo '<div class="checkbox"><label><input type="checkbox" name="adminRights[]" value="Delete" checked >Delete</label></div>';
                
        }else{
            echo '<div class="checkbox"><label><input type="checkbox" name="adminRights[]" value="Delete" >Delete</label></div>';
             
        }



?>
            <div class="form-group">
                <label></label>
                <input type="submit" class="btn btn-primary" value="Save data" />
            
            <input type="hidden" name="op" value="save" />
            <input type="hidden" name="id" value="<?php echo $id; ?>"/>


            
            <a href="dashboard.php" class="btn btn-secondary ml-2">Cancel</a>
            </div>

            
            
        </form>
     </div>
     </div>

</div>
  
</body>

</html>