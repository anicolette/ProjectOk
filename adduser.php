<?php
	#Get form data from POST
	$username = $_POST["username"];
	$email = $_POST["email"];
	$password = $_POST["password"];
	$repeatPass = $_POST["repeatpass"];

	#Verify inputs were valid
	if(empty($username) || empty($password) || empty($repeatPass) || empty($email)){
		echo "<script>setTimeout(\"window.location='error.php'\", 100);</script>";
	}

	if($password != $repeatPass){
		echo "<script>setTimeout(\"window.location='error.php'\", 100);</script>";
	}

	require("logindb.php");

	try{
		#Check if username is taken
		$check_username_query = $db->prepare("SELECT * FROM USERS where Name= :username");
		$params = array(":username" => $username);
		$check_username_query->execute($params);
		$row = $check_username_query->fetch();
		if($row[0]){
			echo "Username is taken\n";
			echo "<script>setTimeout(\"window.location='newuser.php'\", 3000);</script>";
			exit();
		}

		#Check if email is taken
		$check_email_query = $db->prepare("SELECT * FROM USERS WHERE Email=:email");
		$emailParams = array(":email" => $email);
		$check_email_query->execute($emailParams);
		$emailRow = $check_email_query->fetch();
		if($emailRow[0]){
			echo "Email is taken\n";
			echo "<script>setTimeout(\"window.location='newuser.php'\", 3000);</script>";
			exit();
		}

	}
	catch(Exception $e){
		die($e);
	}

	#If we get this far, we're actually adding the new user

	$salt = mcrypt_create_iv(22, MCRYPT_DEV_RANDOM);

	$options = [
		'salt' => $salt,
	];

	$passHash = password_hash($password, PASSWORD_BCRYPT, $options);

	try{
		$insert_user_query = $db->prepare("INSERT INTO USERS (Name, Email, Hash, Salt) VALUES (?, ?, ?, ?)");
		$insertParams = array($username, $email, $passHash, $salt);
		$insert_user_query->execute($insertParams);
	}
	catch(Exception $e){
		die($e);
	}
	header("Location: usercreated.php");
	exit();
?>
