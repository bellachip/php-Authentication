<?php
    session_start();

    // if (isset($_COOKIE["email"]) and isset($_COOKIE["password"])) {
    //     setcookie("email","");
    //     setcookie("password","");
    //     unset($_COOKIE['email']);
    //     unset($_COOKIE['password']);
    //     header("location:../service.php");
    // }
    session_destroy(); //because session is destroyed all data is lost and cookies are reseted

    //$_SESSION["RegState"] = -17; //means resets the cookies as well. 
    header('Location:../index.php');
  
    exit;
?>