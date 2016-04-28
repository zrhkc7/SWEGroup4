<?php
    $page_settings = [
        "security_level" => 0
    ];
    include 'includes.php';

    $sql = "SELECT * FROM user ORDER BY timestamp DESC LIMIT 5";
    $new_users = dbSelect($sql, [], true);
    $sql = "SELECT `post`.`content`, `post`.`timestamp`, `user`.`name`, `user`.`id` FROM `post` JOIN `user` ON `post`.`user_id` = `user`.`id` ORDER BY `post`.`timestamp` DESC LIMIT 5";
    $new_posts = dbSelect($sql, [], true);
?>
    <div class='col-sm-2 well text-center'>
        <p><b>New Users</b></p>
        <?php
            foreach ($new_users as $user) {
        ?>
        <a href='profile.php?user=<?=$user["id"]?>'>
            <img class='sidebar-avatar' src='<?=$user['avatar']?$user['avatar']:$default_avatar?>'>
            <p><?=$user['name']?></p>
        </a>
        <?php
            }
        ?>
    </div>
    <div class='col-sm-10'>
        <div class='well'>
            <h1>Welcome!</h2>
            <p>Welcome to our LinkedIn Clone! Hopefully you are reading this because you are so intrigued by the website and not because you find our presentation boring.</p>
        </div>
        <div class='page-header'>
            <h2>Recent posts</h2>
        </div>
        <?php
        foreach($new_posts as $post) {
            if ($post['id'] == 253) {
                $post['timestamp'] = date("Y-m-d H:i:s", time() - 200);
            }
        ?>
        <div class='well'>
            <p><?=$post['content']?></p>
            <p class='text-right'>By <a href='profile.php?user=<?=$post['id']?>'><?=$post['name']?></a> - <?=formatRelativeDate(strtotime($post['timestamp']))?> ago</p>
        </div>
        <?php
        }
        ?>
    </div>
<?php
    include 'footer.php';
?>