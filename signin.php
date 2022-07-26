<?php
require_once "config.php";

$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
$email = $email_err = $email_err ="";
if ($_SERVER['REQUEST_METHOD'] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Username cannot be blank";
    }
    else{
        $sql = "SELECT id FROM user WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if($stmt)
        {
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set the value of param username
            $param_username = trim($_POST['username']);

            // Try to execute this statement
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1)
                {
                    $username_err = "This username is already taken"; 
                }
                else{
                    $username = trim($_POST['username']);
                }
            }
            else{
                echo "Something went wrong";
            }
        }
    }

    mysqli_stmt_close($stmt);


// Check for password
if(empty(trim($_POST['password']))){
    $password_err = "Password cannot be blank";
}
elseif(strlen(trim($_POST['password'])) < 5){
    $password_err = "Password cannot be less than 5 characters";
}
else{
    $password = trim($_POST['password']);
}

// Check for confirm password field
if(trim($_POST['password']) !=  trim($_POST['confirm_password'])){
    $password_err = "Passwords should match";
}


// If there were no errors, go ahead and insert into the database
if(empty($username_err) && empty($password_err) && empty($confirm_password_err))
{
    $sql = "INSERT INTO user (username, password, email) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt)
    {
        mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_password,$param_email);

        // Set these parameters
        $param_username = $username;
        $param_email = $email;
        $param_password = password_hash($password, PASSWORD_DEFAULT);

        // Try to execute the query
        if (mysqli_stmt_execute($stmt))
        {
            header("location: login.php");
        }
        else{
            echo "Something went wrong... cannot redirect!";
        }
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($conn);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Page</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>
    <div class="signupFrm">
        <form action="" class="form" method = "POST">
          <h1 class="title">Sign up</h1>
    
          <div class="inputContainer">
            <input type="text" class="input" name="email"placeholder="Email">
            <label for="" class="label">Email</label>    
          </div>
    
          <div class="inputContainer">
            <input type="text" class="input" name="username" placeholder="Username">
            <label for="" class="label">Username</label>
          
          </div>
    
          <div class="inputContainer">
            <input type="password" class="input" name ="password" placeholder="Password">
            <label for="" class="label">Password</label>
          </div>
    
          <div class="inputContainer">
            <input type="password" class="input" name="confirm_password" placeholder="Password">
            <label for="" class="label">Confirm Password</label>
          </div>
    
          <input type="submit" class="submitBtn" value="Sign up">
          <div class="container signin">
            <p>Already have an account? <a href="sign.php">Sign in</a>.</p>
          </div>
        </form>
      </div>
    
</body>
</html>