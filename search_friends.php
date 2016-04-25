<?php
    include 'includes.php';
?>
<div class='page-header'>
    <h1>Find Users</h1>
</div>
<form method='GET' action=''>
    <div class="input-group">
        <input type="text" class="form-control" placeholder="Name" name="name">
        <div class="input-group-btn">
            <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
        </div>
    </div>
</form>
<?php

    if (!isset($_GET['name'])) {
        include 'footer.php';
        die();
    }
    else {
        $name = $_GET['name'];
    }
?>
<div class='page-header'>
    <h1>Results for: "<?=$name?>"</h1>
</div>
<?php
    $sql = "SELECT * FROM `user` WHERE `name` like :name LIMIT 25";
    $binds = [":name" => "%$name%"];
    foreach(dbSelect($sql, $binds, true) as $user) {
        ?>
        <a href='profile.php?user=<?=$user['id']?>'>
        <div class='panel panel-default profile-box'>
            <div class='panel-body'>
                <img src='<?=$user['avatar']?>'><br>
                <span class='profile-box-name'><?=$user['name']?></span><br>
                <?=$user['current_employer'] ? $user['current_employer'] : "N/A"?>
            </div>
        </div></a>
        <?php
    }
    include 'footer.php';
?>