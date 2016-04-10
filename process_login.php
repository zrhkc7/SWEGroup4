<?php
	include 'includes.php';

	if (!isset($_POST['email']) || !isset($_POST['password'])) {
		die("Email or password not set");
	}
	else {
		$email = $_POST['email'];
		$password = $_POST['password'];
	}
	// Get password information
	$stmt = $dbh->prepare("SELECT * FROM `password` WHERE `user` = (SELECT `id` FROM `user` WHERE `email` = :email LIMIT 1)");
	$stmt->bindParam(':email', $email);
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC);

	// Set some variables
	$user_id = $result['user'];
	$attempts = $result['attempts'];

	// Brute Force Check
	if ($attempts > 5) {
		die("Too many incorrect guesses.");
	}

	// Check password
	if (password_verify($password, $result['hashed_pass'])) {
		$valid = true;
		$output = "Password is Valid";

	}
	else {
		$valid = false;
		$output = "Password is Invalid";
	}

	// Update attempts if need be
	if ($attempts > 0 || $attempts < 5) {
		// Reset when valid
		if ($valid) {
			$attempts = 0;
		}
		$stmt = $dbh->prepare("UPDATE `password` SET `attempts` = $attempts WHERE `user` = :user_id");
		$stmt->bindParam(':user_id', $user_id);
		$stmt->execute();
	}

	die($output);
?>