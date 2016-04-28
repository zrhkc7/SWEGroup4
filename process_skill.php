<?php
    $page_settings = [
        "is_script" => true,
        "redirect_to" => "profile.php"
    ];
    include 'includes.php';
    

    if(!empty($_POST) && strcmp($_POST["operation"],"add_skill")==0){

        $skill = $_POST["skill"];
    
        if ($skill!="" && createSkill($user_id, $skill)) {
            createPageMessage("Successfully saved skill", "success");
            redirect();
        }
        else {
            createPageMessage("Failed to save skill", "warning");
            redirect("profile.php");
        }

    }
    else if(strcmp($_GET["operation"],"delete_skill")==0){
        $post = $_GET["id"];
    
        if (deleteSkill($user_id, $post)) {
            createPageMessage("Successfully deleted skill", "success");
            redirect();
        }
        else {
            createPageMessage("Failed to delete skill", "warning");
            redirect("profile.php");
        }
    }else{
        echo "Operation not found";
    }
?>