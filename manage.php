<?php

if (!isset($_COOKIE["cookie"]) || empty($_COOKIE["cookie"])) {
	echo "Login in to access your account.";
	exit();
}
	
var_dump($_GET);

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

if (!isset($_GET["action"]) || empty($_GET["action"]) || !isset($_GET["amount"]) || empty($_GET["amount"])) {
	echo "Action and/or amount not entered";
	exit();
}

//get user and password and insert into database
$action = $_GET["action"];
$amount = $_GET["amount"];

$user = $_COOKIE["cookie"]; 

if($action == "deposit"){
	//get current balance
	$userStatement = $mysqli->prepare("SELECT balances.balance FROM users WHERE username=?;");

	$userStatement->bind_param("s", $user);

	$userStatement->execute();

	$results = $userStatement->get_result();
	if(!$results) {
		echo $mysqli->error;
		exit();
	}
	$balance = $results->fetch_assoc();

	//add to balance
	$balance += $amount;
	//update balance with new amount
	$sqladd = "UPDATE balances SET balance = $balance WHERE username=$user;";
	$results2 = $mysqli->query($sqladd);
	if( !$results2) {
		echo $mysqli->error;
		exit();
	}
}
else if($action == "withdraw"){
	//get current balance
	$sql = "SELECT balances.balance WHERE username=$user;";
	$results = $mysqli->query($sql);
	if( !$results) {
		echo $mysqli->error;
		exit();
	}
	//check if sufficient funds and subtract from balance
	if($results < $amount){
		//error
		echo "not enough money in account to make this withdrawal";
		exit();
	}
	else{
		$results -= $amount;
		//update balance with new amount
		$sqladd = "UPDATE balances SET balance = $results WHERE username=$user;";
		$results2 = $mysqli->query($sqladd);
		if( !$results2) {
			echo $mysqli->error;
			exit();
		}
	}
}
else if($action == "balance"){
	//get current balance
	$sql = "SELECT balances.balance WHERE username=$user;";
	$results = $mysqli->query($sql);
	if( !$results) {
		echo $mysqli->error;
		exit();
	}
	echo "balance=" . $results;
}
else if($action == "close"){
	exit();
}

$mysqli->close();


?>