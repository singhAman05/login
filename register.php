<?php
error_reporting(0);
require_once "connect.php";

$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    //Check if username is empty
    if(empty(trim($_POST['username']))){
        $username_err = "Username cannot be blank";
    }
    else{
        $sql = "SELECT id FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if($stmt){
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            //Set the value of param username
            $param_username = trim($_POST['username']);

            //Try to execute this statement
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken";
                }
                else{
                    $username = trim($_POST['username']);
                }
            }
            else{
                echo "Something went wrong :( ";
            }
        }
    }

    mysqli_stmt_close($stmt);
}

//Check for password
if(empty(trim($_POST['password']))){
    $password_err = "Password cannot be blank";
}
elseif(strlen(trim($_POST['password'])) < 5){
    $password_err  = "Password cannot be less than 5 characters";
}
else{
    $password = trim($_POST['password']);
}

//Check for confirm password feild
if(trim($_POST['password']) != trim($_POST['confirm_password']) ){
    $password_err = "Password should match";
}

//If there were no errors, then go ahead and insert into database
if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
    $sql = "INSERT INTO users (username, password) VALUES(?, ?)";
    $stmt = mysqli_prepare($conn, $sql); 
    if($stmt){
        mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);

        //Set these parameters
        $param_username = $username;
        $param_password = password_hash($password, PASSWORD_DEFAULT);

        //Try to execute the Query
        if(mysqli_stmt_execute($stmt)){
            header("location: login.php");
        }
        else{
            echo "Something went wrong :( .....cannot redirect !! ";
        }
    }

    mysqli_stmt_close($stmt);
}
mysqli_close($conn);

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
    <link rel="stylesheet" href="/css/signup_style.css">
</head>
<body>
    <div class="center">
        <h1>Sign-UP</h1>
        <p>Suscribe Us for full access to all lectures</p>
        <form action="" method="post">
            <div class="txt_feild">
                <input type="text" required>
                <span></span>
                <label>First-Name</label>
            </div>
            <div class="txt_feild">
                <input type="text" required>
                <span></span>
                <label>Last-Name</label>
            </div>
            <div class="txt_feild">
                <input type="text" name="username" required>
                <span></span>
                <label>Username</label>
            </div>
            <div class="txt_feild">
                <input type="password" name="password" required>
                <span></span>
                <label>Set Password</label>
            </div>
            <div class="txt_feild">
                <input type="password" name="confirm_password" required>
                <span></span>
                <label>Confirm Password</label>
            </div>
            <div class="txt_feild">
                <input type="email" required>
                <span></span>
                <label>Email-Id</label>
            </div>
            <!-- <div class="pass">Forgot Password?</div> -->
            <input type="submit" value="Sign Up">
            <div class="signup_link">
                <!-- Not a Member?<a href="Sign-up.html">Signup</a> -->
            </div>
        </form>
    </div>
</body>
</html>