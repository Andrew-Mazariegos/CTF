<?php

if (!isset($_COOKIE["cookie"]) || empty($_COOKIE["cookie"])) {
	echo "Login in to access your account.";
	exit();
}
	
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
	$balanceStatement = $mysqli->prepare("SELECT balance FROM balances WHERE username=?;");

	$balanceStatement->bind_param("s", $user);

	$balanceStatement->execute();

	$results = $balanceStatement->get_result();
	if(!$results) {
		echo $mysqli->error;
		exit();
	}

	$balance = null;
	if ($results->num_rows > 0) {
		$balance = $results->fetch_assoc();
	}
	else {
		echo "Unable to successfully deposit amount, please try again.";
		exit();
	}

	//add to balance
	$balance += $amount;
	//update balance with new amount
	$updateStatement = $mysqli->prepare("UPDATE balances SET balance=? WHERE username=?;");
	$updateStatement->bind_param("is", $balance, $user);
	$updateStatement->execute();

	$results = $updateStatement->get_result();
	if(!$results) {
		echo $mysqli->error;
		exit();
	}

	if ($mysqli->affected_rows > 0) {
		echo "Successfully deposited amount, your balance is currently: " + $balance;
	}
	else {
		echo "Unable to successfully deposit amount, please try again.";
		exit();
	}
}
else if($action == "withdraw"){
	//get current balance
	$balanceStatement = $mysqli->prepare("SELECT balance FROM balances WHERE username=?;");

	$balanceStatement->bind_param("s", $user);

	$balanceStatement->execute();

	$results = $balanceStatement->get_result();
	if(!$results) {
		echo $mysqli->error;
		exit();
	}

	$balance = null;
	if ($results->num_rows > 0) {
		$balance = $results->fetch_assoc();
	}
	else {
		echo "Unable to successfully withdraw amount, please try again.";
		exit();
	}
	//check if sufficient funds and subtract from balance
	if ($balance < $amount) {
		//error
		echo "Not enough money in account to make this withdrawal.";
		exit();
	}
	else{
		$balance -= $amount;
		//update balance with new amount
		$updateStatement = $mysqli->prepare("UPDATE balances SET balance=? WHERE username=?;");
		$updateStatement->bind_param("is", $balance, $user);
		$updateStatement->execute();

		$results = $updateStatement->get_result();
		if(!$results) {
			echo $mysqli->error;
			exit();
		}

		if ($mysqli->affected_rows > 0) {
			echo "Successfully withdraw amount, your balance is currently: " + $balance;
		}
		else {
			echo "Unable to successfully withdraw amount, please try again.";
			exit();
		}
	}
}
else if($action == "balance"){
	//get current balance
	$balanceStatement = $mysqli->prepare("SELECT balance FROM balances WHERE username=?;");

	$balanceStatement->bind_param("s", $user);

	$balanceStatement->execute();

	$results = $balanceStatement->get_result();
	if(!$results) {
		echo $mysqli->error;
		exit();
	}

	if ($results->num_rows > 0) {
		$balance = $results->fetch_assoc();
		echo "Your current balance is: " + $balance;
	}
	else {
		echo "Unable to successfully retrieve balance, please try again.";
		exit();
	}
}
else if($action == "close"){
	exit();
}

$mysqli->close();


?>