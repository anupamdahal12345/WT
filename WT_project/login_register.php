<?php
require('connection.php');
session_start();

##for login

if(isset($_POST['login']))
{
    $query="SELECT * FROM `registered_users` WHERE `username`='$_POST[email_username]' OR `email`='$_POST[email_username]'";
    $result=mysqli_query($con,$query);

    if($result){
        if(mysqli_num_rows($result)==1){
            $result_fetch=mysqli_fetch_assoc($result);
            if(password_verify($_POST['password'],$result_fetch['password'])){
                #if password matched
                $_SESSION['logged_in']=true;
                $_SESSION['username']=$result_fetch['username'];
                header("location:index.php");

            }
            else{
                #if incorrect password
                echo"
        <script>
            alert('Incorrect password');
            window.location.href='index.php';
        </script>";
            }
        }
        else{
            echo"
        <script>
            alert('Email or username not registered');
            window.location.href='index.php';
        </script>";
        }
    }
    else{
        echo"
        <script>
            alert('Cannot Run Query');
            window.location.href='index.php';
        </script>";
    }
}

##for registration

if(isset($_POST['register'])){
    $username = $_POST['username'];
    $email = $_POST['email'];

    # check if the username or email already exists in the table
    $user_exist_query="SELECT * FROM `registered_users` WHERE `username`='$username' OR `email`='$email'";
    $result=mysqli_query($con,$user_exist_query);

    if($result){
        if(mysqli_num_rows($result)>0){
            #if Name or email is already taken.
            $result_fetch=mysqli_fetch_assoc($result);
            if($result_fetch['username']==$username){
                #error for username
                echo"
                <script>
                    alert('$username - Username already taken');
                    window.location.href='index.php';
                </script>";
            }
            else{
                #error for email
                echo"
                <script>
                    alert('$email - E-mail already taken');
                    window.location.href='index.php';
                </script>";
            }
        }
        else{
            #it will be executed if no one has taken username and email before.
            $password=password_hash($_POST['password'],PASSWORD_BCRYPT);
            $query="INSERT INTO `registered_users`(`full_name`, `username`, `email`, `password`) VALUES ('$_POST[fullname]','$username','$email','$password')";
            if(mysqli_query($con,$query)){
                #if data inserted successfully
                echo"
                <script>
                    alert('Registration Successful');
                    window.location.href='index.php';
                </script>";
            }
            else{
                #if data cannot be inserted
                echo"
                <script>
                    alert('Cannot Run Query');
                    window.location.href='index.php';
                </script>";
            }
        }
    }
    else{
        echo"
        <script>
            alert('Cannot Run Query');
            window.location.href='index.php';
        </script>";
    }
}
?>