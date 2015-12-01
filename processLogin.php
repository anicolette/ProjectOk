<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	require_once("session.php");
	
	#Get credentials from POST and check that the specified user exists
	$username = $_POST["username"];
	$password = $_POST["password"];

	if(empty($username) || empty($password)){
		echo "<script>setTimeout(\"window.location='error.php'\", 100);</script>";
		#echo "Must fill all fields\n";
		#echo "<script>setTimeout(\"window.location='login.php'\", 3000);</script>";
		exit();
	}

	require("logindb.php");

	try{
		$checkUser = $db->prepare("SELECT Name, Hash, Salt FROM USERS WHERE Name=:username");
		$param = array(":username" => $username);
		$checkUser->execute($param);
		$row = $checkUser->fetch();
	
		if(!$row[0]){ #Specified user does not exist
			echo "<script>setTimeout(\"window.location='error.php'\", 100);</script>";
			#echo "$username does not exist!\n";
			#echo "<script>setTimeout(\"window.location='login.php'\", 3000);</script>";
			exit();
		}

		#Generate hash using given password and stored salt, and check against stored hash
		$salt = $row["Salt"];

        	$options = [
        		'salt' => $salt,
        	];

        	$passHash = password_hash($password, PASSWORD_BCRYPT, $options);

        	$storedHash = $row["Hash"];
        	if($passHash == $storedHash){#The given password is correct
        	        $_SESSION["username"] = $username;
			header("Location: profile.php");
        	} else{
			echo "<script>setTimeout(\"window.location='error.php'\", 100);</script>";
			#echo "Incorrect password for user $username!\n";
			#echo "<script>setTimeout(\"window.location='login.php'\", 3000);</script>";
			exit();
        	}
	} catch (Exception $e){
		die($e);
	} 
