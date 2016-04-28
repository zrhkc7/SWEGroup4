<?php
    $page_settings = [
        "is_script" => true,
        "redirect_to" => "profile.php"
    ];
    include 'includes.php';

    $update_user_info = [];
    $attributes = ["name", "current_employer", "avatar", "email", "phone", "summary"];
    foreach ($attributes as $attr) {
        if (isset($_POST[$attr]) && strcmp($_POST[$attr], $user_info[$attr])) {
            $update_user_info[$attr] = $_POST[$attr];
        }
    }

    if (updateUser($user_id, $update_user_info)) {
        createPageMessage("Successfully updated user info", "success");
        redirect();
    }
    else {
        createPageMessage("Failed to update user info", "warning");
        redirect("update_profile.php");
    }
?>