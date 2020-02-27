<?php
    session_start(); 
    require_once("config.php");

    //Get data off the web; 
    $Email = $_POST["Email"]; 
    $Password = md5($_POST["Password"]); 
    $rememberMe = $_POST["rememberMe"]; // you ahve to figure how to handle cookies 
    $Counter = -1; //for status
    $con = mysqli_connect(SERVER, USER, PASSWORD, DATABASE);  //Connect to DB 
    
    if(!$con) {
        $_SESSION["RegState"] = -1; 
        $_SESSION["Message"] = "Database connection failed:";
            mysqli_error($con); 
        header("location: ../index.php"); 
        exit();
    }

    print "Database connected <br>"; 

    // if ($rememberMe != null) {
    //     if ($_COOKIE['userEmail'] == NULL || $_COOKIE['userValidator'] == NULL) {
    //         //create cookies for user 
    //         setcookie("userEmail", $Email, time()+ (10 * 365 * 24 * 60), "/");
    //         $cookieQuery = "Update Users set CookieSelector = '$Email' where Email = '$Email';";
    //         $cookieQueryResult = mysqli_query($con, $cookieQuery); 
    //         if (!$cookieQueryResult) {
    //             $_SESSION["RegState"] = -7;
    //             $_SESSION["Message"] = "CookieSelector query failed: ". 
    //                 mysqli_error($con); 
    //             header("location:../index.php"); 
    //             exit ();
    //         }

    //         //Generate cookieValidator and set cookie for that validator 
    //         $cookieValidator = rand(); 
    //         setcookie("userValidator", $cookieValidator, time() + (10 * 365 * 24 * 60 * 60), "/");

    //         // Store cookie Validator 
    //         $cookieValidatorQuery = "Update Users set CookieValidator = '$cookieValidator' where Email = '$Email';";
    //         $cookieValidatorResult = mysqli_query($con, $cookieValidatorQuery);
    //         if (!$cookieValidatorResult) {
    //             $_SESSION["RegState"] = -7; 
    //             $_SESSION["Message"] = "cookieValidator query failed: ". 
    //                 mysqli_error($con); 
    //             header("location:../index.php"); 
    //             exit(); 
    //         }
    //     } else {
    //         //check if useres stored validator matches cookie userValidator, if so log them in 
    //         $userValidator  = $_COOKIE['userValidator']; 
    //         $checkUserQueryResult = mysqli_query ($con, $checkUserQuery);
    //         if (!$checkUserQueryResult) {
    //             $_SESSION["RegState"] = -7; 
    //             $_SESSION[ "Message"] = "User's cookie validator does Not match the validator stored for the current user". 
    //                 mysqli_error($con); 
    //             header("location:../index.php"); 
    //             exit();

    //         }
    //         if (mysqli_num_rows($checkUserQueryResult) !=1) {
    //             $_SESSION["RegState"] = -9;
    //             $_SESSION["Message"] = "User's cookie validator does NOT match the validator stored for the current user"; 
    //             header("location:../index.php"); 
    //             exit(); 


    //         }

    //         //login sucess - set the regstate and reirect to service.php - notice entering a password is not reuired to log in this case 
    //         $_SESSION["RegState"] = 4; 
    //         header("location:../serfvice.php"); 
    //         exit();

    //     }
    // } else {
    //     setcookie("userEmail", NULL, 0, "/"); 
    //     setcookie("userValidator", NULL, 0, "/"); 
    // }

    if(!empty($rememberMe)) {
        setcookie ('userEmail', $Email, time()+ 3600);
        // setcookie ('password', $Password, time()+ 3600);
        $email = $_COOKIE['email'];
        print "($email)";
        print "Cookies Set Successfuly";
        $_SESSION["RegState"] = 4;
        header("location:../index.php");
        $Counter = 1;
    } else {
        $Counter =0;
        // setcookie("email","");
        // setcookie("password","");
        unset($_COOKIE['userEmail']);
        // unset($_COOKIE['password']);
        // $email = $_COOKIE['email'];
        // print "($email)";
       
        print "Cookies Not Set";
        header("location:../service.php");
        // if(isset($_COOKIE[$Email])) {
        //     setcookie ($Email,"");
        // }
    }



    //Build query to update user with the password 
    $query = "Select * from Users where Password='$Password' and Email = '$Email';";
    $result = mysqli_query ($con, $query); 
    if (!$result) {
        $_SESSION["RegState"] = -7; 
        $_SESSION["Message"] = "Login query Failed ";
            mysqli_error($con);
        header("Location:../index.php");
        exit();
    }

    //Check if only one row matched
    if (mysqli_num_rows($result) != 1) {
        $_SESSION["RegState"] = -8; 
        $_SESSION["Message"] = "Either email or password did not match. Please try again.";  
        header("location:../index.php"); 
        exit();
    }
    print "Authentication succeeded <br>";

   
    $query = "Update Users set Status = '$Counter' where Email = '$Email';";
    $result = mysqli_query($con, $query); 
    
    if (!$result) {
        $_SESSION["RegState"] = -10; 
        $_SESSION["Message"] = "Status update query failed"; 
            mysqli_error($con); 
        // header("location:../index.php"); 
        exit();
    }
    $_SESSION["RegState"] = 4; //login success
    header("location:../index.php");
    exit(); 

?> 