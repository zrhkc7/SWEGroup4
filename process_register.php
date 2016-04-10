<?php
	include 'includes.php';

	// Check if variables are set	
	if (!isset($_POST['email']) || !isset($_POST['password'])) {
		die("Email or Password missing");
	}
	else {
		$email = $_POST['email'];
		$password = $_POST['password'];
	}

	// Check if email is 'valid'
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		die("Email is invalid");
	}

	// Create User entry
	$stmt = $dbh->prepare("INSERT INTO `user` (`email`) VALUES (:email)");
	$stmt->bindParam(':email', $email);
	$stmt->execute();
	$user_id = $dbh->lastInsertId();

	// Create password entry
	$stmt = $dbh->prepare("INSERT INTO `password` (`user`, `hashed_pass`) VALUES (:user, :hashed_pass)");
	$stmt->bindParam(':user', $user_id);
	$stmt->bindParam(':hashed_pass', password_hash($password, PASSWORD_BCRYPT));
	$stmt->execute();

	header("Location: index.php");
	die();
?>