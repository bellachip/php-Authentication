<?php
    session_start();
    require_once("config.php"); 
    
    use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	use PHPMailer\PHPMailer\SMTP;

	require '../PHPMailer-master/src/Exception.php';
	require '../PHPMailer-master/src/PHPMailer.php';
	require '../PHPMailer-master/src/SMTP.php';

	//get the data off web 
 	$Email = $_GET["Email"]; 
	// $FirstName = $_GET["FirstName"];
	// $LastName = $_GET["LastName"]; 
    print "Web data ($Email) <br>";
    
    $con = mysqli_connect(SERVER, USER, PASSWORD, DATABASE);

    if(!$con) {
       
        $_SESSION["RegState"] = -1; 
        $_SESSION["Message"] = "Database connection failed:";
            mysqli_error($con); 
        // header("location: ../index.php"); 
        exit();
    }

    $Counter = -1;
    print "Database connected <br>"; 
    $Acode = rand(); 
    $Rdatetime = date("Y-m-d h:i:s"); 
    $query = "Update Users set Acode = '$Acode', Rdatetime = '$Rdatetime', Status = '$Counter' where Email= '$Email';";
    $result = mysqli_query($con, $query); 

    if (!$result) {
        $_SESSION["RegState"] = -2; 
        $_SESSION["Message"] = "Query Failed"; 
            mysqli_error($con); 
        // header("location:../index.php"); 
        exit();
    }
    print "Query worked";
    //Registeration Success. Build authentication email
    // Build the PHPMailer object:
	$mail= new PHPMailer(true);
	try { 
		$mail->SMTPDebug = 2; // Wants to see all errors
		$mail->IsSMTP();
		$mail->Host="smtp.gmail.com";
		$mail->SMTPAuth=true;
		$mail->Username="cis105223053238@gmail.com";
		$mail->Password = 'g+N3NmtkZWe]m8"M';
		$mail->SMTPSecure = "ssl";
		$mail->Port=465;
		$mail->SMTPKeepAlive = true;
		$mail->Mailer = "smtp";
		$mail->setFrom("tug67998@temple.edu", "Bella Yang");
		$mail->addReplyTo("tug67998@temple.edu","Bella Yang");
        $msg = "Please Click the link to complete registeration process:" 
        ."http://cis-linux2.temple.edu/~tug67998/4398/BCLab/php/authenticate.php?Acode=$Acode&Email=$Email";
		$mail->addAddress($Email, "");
		$mail->Subject = "Welcome to (my project)";
		$mail->Body = $msg;
		$mail->send();
		print "Email sent ... <br>";
		$_SESSION["RegState"] = 3;
		$_SESSION["Message"] = "Email sent.";
		header("location:../index.php");
		exit();
	} catch (phpmailerException $e) {
		$_SESSION["Message"] = "Mailer error: ".$e->errorMessage();
		$_SESSION["RegState"] = -4;
        print "Mail send failed: ".$e->errorMessage;
        header("location:../index.php")	; 
        exit();	
    }


?>