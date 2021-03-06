<?php
error_reporting(0);
//This script will handle login
session_start();

//check if the user is already logged in or not
if(isset($_SESSION['username'])){
    header("location: welcome.php");
    exit;
}
require_once "connect.php";

$username = $password ="";
$err ="";

//if request method is post 
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    if(empty(trim($_POST['username'])) || empty(trim($_POST['password']))){
        $err = "Please enter Username or Password";
    }
    else{
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
    }

    if(empty($err)){
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $param_username);
        $param_username = $username;

        //Try to execute this statement
        if(mysqli_stmt_execute($stmt)){
            mysqli_stmt_store_result($stmt);

            if(mysqli_stmt_num_rows($stmt) == 1){
                mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);

                if(mysqli_stmt_fetch($stmt)){

                    if(password_verify($password, $hashed_password)){

                        //This means username and password are correct. Allow user to access.
                        session_start();
                        $_SESSION["username"] = $username;
                        $_SESSION["id"] = $id;
                        $_SESSION["loggedin"] = true;

                        //Redirect user to Welcome page
                        header("loaction: welcome.php");
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kalam&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login/sign-up</title>
    <link rel="stylesheet" href="/css/login_style.css">
</head>
<body>
    <div class="center">
        <h1>Login</h1>
        <p>For Sellers Only</p>
        <form action="" method="post">
            <div class="txt_feild">
                <input type="text" name="username" required>
                <span></span>
                <label>Username</label>
            </div>
            <div class="txt_feild">
                <input type="password" name="password" required>
                <span></span>
                <label>Password</label>
            </div>
            <div class="pass">Forgot Password?</div>
            <input type="submit" value="Login">
            <div class="signup_link">
                Not a Member?<a href="register.php">Subscribe Us</a>
            </div>
        </form>
    </div>
</body>
</html>