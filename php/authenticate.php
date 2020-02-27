<?php
    session_start(); 
    require_once("config.php"); 
    //Get data off the web 
    $Email = $_GET["Email"]; 
    $Acode = $_GET["Acode"]; 
    print "Web data ($Email) ($Acode) <br>"; 
    //Connect to DB 
    $con = mysqli_connect (SERVER, USER, PASSWORD, DATABASE); 
    if(!$con) {
       
        $_SESSION["RegState"] = -1; 
        $_SESSION["Message"] = "Database connection failed: ";
            mysqli_error($con); 
        header("location: ../index.php"); 
        exit();
    }
    print "Database connected <br>"; 

    //Build query to check if Email and Acode match? 

    $query = "Select * from Users where Email='$Email' and Acode = '$Acode';";
    $result = mysqli_query($con, $query);     
    if (!$result) {
        $_SESSION["RegState"] = -2; 
        $_SESSION["Message"] = "select Query Failed"; 
            mysqli_error($con); 
        header("location:../index.php"); 
        exit();
    }
    //Check if only one row matched
    if (mysqli_num_rows($result) != 1) {
        $_SESSION["RegState"] = -4; 
        $_SESSION["Message"] = "either email or activation code did not match. Register again"; 
            // mysqli_error($con); 
        header("location:../index.php"); 
        exit();

    }
	print "Authentication succeeded <br>";

    //Authentication succeeded 
    $Acode = rand(); //replacing the old Acode 
    $Adatetime = date("Y-m-d h:i:S"); 
    $query = "Update Users set Acode = '$Acode', Adatetime = '$Adatetime' where Email = '$Email';"; 
    $result = mysqli_query($con, $query); 
    if (!$result) {
        $_SESSION["RegState"] = -5; 
        $_SESSION["Message"] = "Acode update failed:" 
            .mysqli_error($con); 
        header("location:../index.php"); 
        exit();
    }
	print "User update completed <br>"; 
    //Save Email 
    $_SESSION["Email"] = $Email; 
    $_SESSION["RegState"] = 6 ;//To trigger the password form 
    header("location:../index.php");

    print "Query worked";
    exit();

?>