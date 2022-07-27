<?php
//This script will handle login
session_start();

// check if the user is already logged in
require_once "config.php";

$username = $password = "";
$err = "";

// if request method is post
if ($_SERVER['REQUEST_METHOD'] == "POST"){
    if(empty(trim($_POST['username'])) || empty(trim($_POST['password'])))
    {
        $err = "Please enter username + password";
    }
    else{
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
    }


if(empty($err))
{
    $sql = "SELECT id, username, password FROM user WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $param_username);
    $param_username = $username;
    
    
    // Try to execute this statement
    if(mysqli_stmt_execute($stmt)){
        mysqli_stmt_store_result($stmt);
        if(mysqli_stmt_num_rows($stmt) == 1)
                {
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt))
                    {
                        if(password_verify($password, $hashed_password))
                        {
                            // this means the password is corrct. Allow user to login
                            session_start();
                            $_SESSION["username"] = $username;
                            $_SESSION["id"] = $id;
                            $_SESSION["loggedin"] = true;

                            //Redirect user to welcome page
                            header("location: homepage.html");
                            
                        }
                    }

                }

    }
}    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Page</title>
    <link rel="stylesheet" href="login.css">

</head>
<body>
    <div class="signupFrm">
        <form action="" class="form"  method = "POST">
          <h1 class="title">Login Page</h1>
    
          <div class="inputContainer">
            <input type="text" class="input" name="username" placeholder="Username">
            <label for="" class="label">Username</label>
          
          </div>
    
          <div class="inputContainer">
            <input type="password" class="input" name ="password" placeholder="Password">
            <label for="" class="label">Password</label>
          </div>

          <div class="buttons">
          <input type="submit" class="submitBtn" value="Sign up"> 
          <input type="Forgotpassword" class=" ForgotpasswordBtn" value="Forgot password">
            </div>
          <div class="container signin">
            <p>if account doesn't exist? Create account <a href="signin.php">Sign up</a>.</p>
          </div>
        </form>
      </div>
    
</body>
</html>