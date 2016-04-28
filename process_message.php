<?php
    $page_settings = [
        "is_script" => true,
        "redirect_to" => "profile.php"
    ];
    include 'includes.php';

    $message = $_GET["content"];
    $sender = $_GET["sender"];
    $reciever=getUserByName($_GET['reciever']);
    
    if($reciever!=false){
        $reciever_id=$reciever['id'];
        if ($message!="" && createMessage($sender,$reciever_id,$message)) {
            createPageMessage("Successfully sent message", "success");
            redirect("messages.php");
        }
        else {
            createPageMessage("Failed to send message".$sender.$reciever_id.$message, "warning");
            redirect("messages.php");
        }
    }else{
        createPageMessage("Couldn't find user", "warning");
            redirect("messages.php");
    }

?>