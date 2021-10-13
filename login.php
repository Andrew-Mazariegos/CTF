<?php
	
var_dump($_GET);


// ---- STEP 1: Establish DB connection
$host = "303.itpwebdev.com";
$mysqlUser = "nayeon_db_user";
$mysqlPassword = "uscItp2020!";
$db = "nayeon_song_db";

$mysqli = new mysqli($host, $mysqlUser, $mysqlPassword, $db);

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

if (!isset($_GET["user"]) || empty($_GET["user"]) || !isset($_GET["pass"]) || empty($_GET["pass"])) {
	echo "Username and/or password not entered";
	exit();
}

$user = $_GET["user"];
$pass = $_GET["pass"];

$hashedPassword = hash("sha256", $pass);

$statement = $mysqli->prepare("SELECT 1 FROM users WHERE username=? AND password=?;");

$statement->bind_param("ss", $user, $hashedPassword);

$statement->execute();

$results = $statement->get_result();
if( !$results) {
	echo $mysqli->error;
	exit();
}

if($results->num_rows > 0) {
	setcookie("cookie", $user, time() + 600);
	header("Location: manage.php");
}
else {
	echo "Invalid username and password combination.";
	exit();
}

$mysqli->close();

?>