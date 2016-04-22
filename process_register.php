<?php
    $page_settings = [
        "is_script" => true,
        "redirect_to" => "register.php",
        "security_level" => 0
    ];
    include 'includes.php';

    // Check if variables are set
    if (!checkPostVariables('name', 'email', 'password')) {
        createPageMessage("Email or Password missing", "danger");
        redirect();
    }
    else {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
    }

    // Check if email is 'valid'
    if (!validateEmail($email)) {
        createPageMessage("Email is invalid", "danger");
        redirect();
    }

    // Create User entry
    if ($user_id = createUser($name, $email, $password)) {
        if (!createSession($user_id)) {
            $redirect_to = "index.php";
        }
        else {
            $redirect_to = "login.php";
        }
        createPageMessage("Successfully registered!", "success");
        redirect($redirect_to);
    }
    else {
        createPageMessage("Unable to create user: Email most likely in use", "danger");
        redirect();
    }
?>