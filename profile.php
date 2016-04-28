<?php
    $page_settings = [
        "security_level" => 0
    ];

    include 'includes.php';

    $is_my_profile = false;
    $is_friend = false;
    if (isset($_GET['user'])) {
        $profile_user = $_GET['user'];
    }
    elseif ($logged_in) {
        $is_my_profile = true;
        $profile_user = $user_id;
    }
    else {
        createPageMessage("Cannot view profile because you are not logged in", "warning");
        redirect("login.php");
    }

    $profile_user_info = getUser($profile_user);
    if (empty($profile_user_info)) {
        createPageMessage("User does not exist", "warning");
        redirect();
    }

    if ($logged_in && $user_id != $profile_user) {
        createView($user_id, $profile_user);
        $is_friend = checkFriendship($user_id, $profile_user);
    }

    $profile_user_info["num_friends"] = getNumberOfFriends($profile_user);
    $profile_user_info["recent_visits"] = getViewsSinceDate($profile_user, time() - 2592000);

    $sql = "SELECT * FROM `user` WHERE `id` IN (SELECT `friend1` FROM `friendship` WHERE `friend2` = :user_id1) OR `id` IN (SELECT `friend2` FROM `friendship` WHERE `friend1` = :user_id2) LIMIT 5";
    $binds = [
        ":user_id1" => $profile_user,
        ":user_id2" => $profile_user
    ];
    $friends = dbSelect($sql, $binds, true);

?>

<div class='page-header'>
    <h1><?=$profile_user_info["name"]?></h1>
</div>
<div class='row'>
    <div class='col-sm-3 well'>
        <div class='row text-center'>
            <img class='profile-avatar' src='<?=$profile_user_info['avatar']?$profile_user_info['avatar']:$default_avatar?>'>
        </div>
        <hr>
        <p><b>User Information</b></p>
        <p><label class='label label-default'>Employer</label> <?=$profile_user_info["current_employer"]?></p>
        <p><label class='label label-default'>Friends</label> <?=$profile_user_info["num_friends"]?></p>
        <p><label class='label label-default'>Account Age</label> <?=formatRelativeDate(strtotime($profile_user_info["timestamp"]))?></p>
        <p><label class='label label-default'>Visits in past month</label> <?=$profile_user_info["recent_visits"]?></p>
        <?php
        if ($is_friend || $profile_user == $user_id) {
        ?>
        <p><label class='label label-default'>Phone</label> <?=$profile_user_info["phone"]?$profile_user_info["phone"]:"N/A"?></p>
        <p><label class='label label-default'>Email</label> <?=$profile_user_info["email"]?$profile_user_info["email"]:"N/A"?></p>
        <?php
        }
        ?>
        <hr>
        <p><b>Skills</b></p>
        <?php
        foreach (getUserSkills($profile_user) as $skill) {
            if($is_my_profile){
        ?>
                <span  style="margin:2px" class='label label-info'><?=$skill["description"]?>   <a href='process_skill.php?id=<?=$skill['id']?>&operation=delete_skill'><span class='glyphicon glyphicon-remove'></span></a></span></span>
         <?php
            }else{
        ?> 
                <span class='label label-info'><?=$skill["description"]?></span>
        <?php
            }
        }   
        if ($is_my_profile) {
        ?>
        <hr>
        <form method='POST' action='process_skill.php'>
            <div class="form-group">
                <label>Add Skill</label>
                <input type="text" class="form-control" name="skill" placeholder='Skill Name'>
                <input type="hidden" name="operation" value="add_skill">
            </div>
            <button class="btn btn-block btn-primary" type="submit">Add Skill</button>
        </form>
        <hr>
        <p><a class='btn btn-block btn-default' href='update_profile.php'>Edit profile</a>
        <p><a class='btn btn-block btn-default btn-overflow' href='profile.php?user=<?=$user_id?>'>View profile as other user's see it</a>
        <?php
        }
        else if (!$is_friend) {
        ?>
        <hr>
        <a class='btn btn-block btn-primary' href='add_friend.php?user=<?=$profile_user?>'>Add Friend</a>
        <?php
        }
        ?>
    </div>
    <div class='col-sm-7'>
        <div class='well'>
            <p><b>Summary</b></p>
            <p><?=$profile_user_info["summary"]?></p>
        </div>
        <?php
        if ($is_my_profile) {
        ?>
        <form method='POST' action='process_post.php' class='add-post'>
            <div class='form-group'>
                <textarea name="content" class='form-control' placeholder='New post'></textarea>
            </div>
            <input type="hidden" name="operation" value="add_post">
            <button class='btn btn-block btn-primary'>Add Post</button>
        </form>
        <?php
        }
        foreach (getPosts($profile_user) as $post) {
        ?>
        <div class='well post-relative'>
            <?php
            if ($is_my_profile) {
            ?>
            <a href='process_post.php?id=<?=$post['id']?>&operation=delete_post'><span class='glyphicon glyphicon-remove post-delete'></span></a>
            <?php
            }
            ?>
            <p><?=$post["content"]?></p>
            <p class='text-right'>Posted <?=formatRelativeDate(strtotime($post["timestamp"]))?> ago</p>
        </div>
        <?php
        }
        ?>
    </div>
    <div class='col-sm-2 well'>
        <p><b>Friends (<?=$profile_user_info["num_friends"]?>)</b></p>
        <div class='text-center'>
    <?php
    foreach($friends as $friend) {
    ?>
            <a href='profile.php?user=<?=$friend['id']?>'>
                <img class='sidebar-avatar' src='<?=isset($friend['avatar'])?$friend['avatar']:$default_avatar?>'>
                <p><?=$friend['name']?></p>
            </a>
    <?php
    }
    ?>
        </div>
    </div>
</div>
<?php
if ($is_my_profile) {
?>
<div class='page-header'>
    <h2>Page Views</h2>
</div>
<div class='row'>
    <div id="views"></div>
</div>
<div class='page-header'>
    <h2>Friend Map</h2>
</div>
<div class='row'>
    <div id='map'></div>
</div>
<script src='js/friends.js'></script>
<script src='js/views.js'></script>
<?php
}
    include 'footer.php';
?>