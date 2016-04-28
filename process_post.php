<?php
    $page_settings = [
        "is_script" => true,
        "redirect_to" => "profile.php"
    ];
    include 'includes.php';
    

    if(!empty($_POST) && strcmp($_POST["operation"],"add_post")==0){

        $post = $_POST["content"];
    
        if (createPost($user_id, $post)) {
            createPageMessage("Successfully posted", "success");
            redirect();
        }
        else {
            createPageMessage("Failed to post", "warning");
            redirect("profile.php");
        }

    }
    else if(strcmp($_GET["operation"],"delete_post")==0){
        $post = $_GET["id"];
    
        if (deletePost($user_id, $post)) {
            createPageMessage("Successfully deleted", "success");
            redirect();
        }
        else {
            createPageMessage("Failed to delete", "warning");
            redirect("profile.php");
        }
    }else{
        echo "Operation not found";
    }
?>