<?php
	$page_settings = [
        "header_visible" => true,
        "security_level" => 1
    ];
    include 'includes.php';

    $messages=getUserMessages($user_id, $amount = 25);

    // $sql = "UPDATE `message` SET `time_viewed` = CURRENT_TIMESTAMP WHERE `receiver` = :user_id AND `time_viewed` IS NULL";
    // $binds = [":user_id" => $user_id];
    // dbExecute($sql, $binds);

    echo "<h1>Messages</h1>";
   	if(empty($messages)){
   		echo "<h1 align=center>No messages</h1>";
   	}

   	echo '<br>
   			<form action=process_message.php>
   		  		<h3>Send a Message</h3>
				Name: <input type="text" name="reciever">
				Message: <input type="text" name="content" size="50">
				<input type="hidden" name="sender" value="'.$_COOKIE['user_id'].'">
				<input type="submit" value="Send">
			</form><br><br>';

   	foreach ($messages as $message){
      if(isMessageRead($message['id'])==false){
        markMessageRead($message['id']);
      }
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
      if($message['time_viewed']==NULL){
        echo '<p align=right>Viewed now</p>';
      }else{
            echo '<p align=right>Viewed '.formatRelativeDate(strtotime($message['time_viewed'])).' ago</p>';        
      }
      echo '</div>
        </div>
      </div>';
   	}

    include 'footer.php';
?>

