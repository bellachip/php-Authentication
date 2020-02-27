<?php
    session_start(); 
    require_once("config.php"); 
    //Get data off the web 
    $Email = $_SESSION["Email"]; 
    $Password = md5($_POST["Password"]); //ecnryption - one way encryption, cannot decrypt 
    print "Web data ($Email) ($Password) <br>"; 
    //Connect to DB 
    $con = mysqli_connect(SERVER, USER, PASSWORD, DATABASE); 

    if(!$con) {
       
        $_SESSION["RegState"] = -1; 
        $_SESSION["Message"] = "Database connection failed:" ;
            mysqli_error($con); 
        //header("location:../index.php"); 
        exit();
    }
    print "Database connected <br>"; 

    //Build query to update user with the password 

    $query = "Update Users set Password = '$Password' where Email = '$Email';";
    $result = mysqli_query($con, $query); 
    
    if (!$result) {
        $_SESSION["RegState"] = -6; 
        $_SESSION["Message"] = "Password update failed"; 
            mysqli_error($con); 
        // header("location:../index.php"); 
        exit();
    }
    //Password set successfully
    $_SESSION["RegState"] = 0; 
    $_SESSION["Message"] = "Passwrod set. Please login."; 
    header("location:../index.php");
    exit(); 
 ?>
