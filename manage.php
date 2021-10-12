<?php
	
var_dump($_GET);
echo $_GET["action"];
echo $_GET["amount"];


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

//get user and password and insert into database
$action = $_GET["action"];
$amount = $_GET["amount"];

$user; //HOW TO GET THEIR USERNAME? 

if($action == "deposit"){
	//get current balance
	$sql = "SELECT balances.balance WHERE username=$user;";
	$results = $mysqli->query($sql);
	if( !$results) {
		echo $mysqli->error;
		exit();
	}
	//add to balance
	$results += $amount;
	//update balance with new amount
	$sqladd = "UPDATE balances SET balance = $results WHERE username=$user;";
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