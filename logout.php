<?php
    $page_settings = [
        "is_script" => true
    ];
    include 'includes.php';

    logout();
    createPageMessage("Successfully logged out!", "success");
    redirect("login.php");
?>