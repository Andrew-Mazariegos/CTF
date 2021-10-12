<?php
	
var_dump($_GET);
echo $_GET["user"];
echo $_GET["pass"];


// ---- STEP 1: Establish DB connection
$host = "303.itpwebdev.com";
$user = "nayeon_db_user";
$password = "uscItp2020!";
$db = "nayeon_song_db";

$mysqli = new mysqli($host, $user, $password, $db);

// Check for errors
// connect_errno will return an error code if there is an error when attempting to connect to the db.
if( $mysqli->connect_errno){
	// Display the exact error message
	echo $mysqli->connect_error;
	// exit() terminates the program. Subsequent code will not run.
	exit();
}

// Set character set
$mysqli->set_charset("utf8");


$user = $_GET["user"];
$pass = $_GET["pass"];


$sql = "SELECT 1 FROM users 
		WHERE username = $user AND password = $pass;";

$results = $mysqli->query($sql);
if( !$results) {
	echo "Invalid login";
	exit();
}
else{
	//SERVE THEM A COOKIE
}


$mysqli->close();


?>