<?php
	$page_settings = [
        "header_visible" => true,
        "security_level" => 1
    ];
    include 'includes.php';

    $messages=getUserMessages(getUserID(), $amount = 25);

    echo "<h1>Messages</h1>";
   	if(empty($messages)){
   		echo "<h1 align=center>No messages</h1>";
   	}

   	echo '<br>
   			<form action=process_create_message.php>
   		  		<h3>Send a Message</h3>
				Name: <input type="text" name="reciever">
				Message: <input type="text" name="content" size="50">
				<input type="hidden" name="sender" value="'.$_COOKIE['user_id'].'">
				<input type="submit" value="Send">
			</form><br><br>';

   	foreach ($messages as $message){

   		$sender=getUser($message['sender']);
   		echo '<div class="row">
          <div class="panel panel-default text-left">
            <div class="panel-body">
            	<img src="'.$sender['avatar'].'" class="img-circle" alt="Avatar" height="55" width="55" align=right>
                <h3>'.$message['content'].'</h3>
                <p align=right>'.$sender['name'];
      if($message['time_sent']=='0000-00-00 00:00:00'){
        echo '<p align=right>Sent time not found</p>';
      }else{
            echo '<p align=right>Sent '.formatRelativeDate(strtotime($message['time_sent'])).' ago</p>';        
      }
      echo '</div>
        </div>
      </div>';
   	}

    include 'footer.php';
?>

