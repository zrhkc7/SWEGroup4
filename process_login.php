<?php
    $page_settings = [
        "is_script" => true,
        "security_level" => 0,
        "redirect_to" => "login.php"
    ];
    include 'includes.php';

    if (checkPostVariables('email', 'password')) {
        $email = $_POST['email'];
        $password = $_POST['password'];
    }
    else {
        createPageMessage("Email or password not set", "danger");
        redirect();
    }

    // Get password information
    if (!($password_info = getPasswordInfo($email))) {
        createPageMessage("Email was not found", "danger");
        redirect();
    }

    // Set some variables
    $user_id = $password_info['user'];
    $attempts = $password_info['attempts'];
    $hashed_pass = $password_info['hashed_pass'];

    // Brute Force Check
    if ($attempts > 5) {
        createPageMessage("Too many incorrect guesses.", "danger");
        redirect();
    }

    // Check password
    $valid = password_verify($password, $hashed_pass);

    // Update attempts
    updatePasswordAttempts($user_id, $valid, $attempts);

    // If password did not match
    if (!$valid) {
        createPageMessage("Password is invalid", "danger");
        redirect();
    }

    // Attempt to create session
    if (!createSession($user_id)) {
        createPageMessage("Unable to create session", "danger");
        redirect();
    }

    // Redirect on success
    createPageMessage("Successfully logged in", "success");
    redirect("index.php");
?>