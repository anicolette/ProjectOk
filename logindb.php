<?php
	try{
		#Variables may need to be modified for security/depending on server configuration
		$dbhost = "localhost";
		$dbuser = "root";
		$dbpassword = "projectok";
		$dbname = "LOGINDB";
	
		$db = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpassword);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	}
	catch (Exception $e){
		die($e);
	}
?>
