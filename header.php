<!DOCTYPE html>
<html>
    <head>
        <title>LinkedIn Clone</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <script src="https://code.jquery.com/jquery-2.2.3.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="https://d3js.org/d3.v3.min.js" charset="utf-8"></script>
        <script>var uid = <?=$user_id?>;</script>
        <link rel="stylesheet" href="CSS/main.css">
    </head>
    <body>

<?php
    if ($page_settings["header_visible"]) {
?>

        <nav class="navbar navbar-inverse">
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">LinkedIn Clone</a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="#">Home</a></li>
                        <li><a href="#">Friends</a></li>
                        <li><a href="#">Messages</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <?php
                            if ($logged_in) {
                        ?>
                        <li><a href="profile.php?user=<?=$user_info['id']?>"><img src='<?=$user_info['avatar']?>'></span> My profile</a></li>
                        <?php
                            }
                            else {
                        ?>
                        <li><a href='#'>Log In / Register</a></li>
                        <?php
                            }
                        ?>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>

<?php
    }
?>
        <div class='container' id='container'>
<?php
    foreach (getMessages() as $msg_type => $messages) {
        foreach ($messages as $msg) {
?>
            <div class="alert alert-<?= $msg_type ?> alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?= $msg ?>
            </div>
<?php
        }
    }
?>