<!DOCTYPE html>
<html>
    <head>
        <title>LinkedIn Clone</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <script src="https://code.jquery.com/jquery-2.2.3.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="https://d3js.org/d3.v3.min.js" charset="utf-8"></script>
        <script>
            var uid = <?=$user_id?>;
            /*
            $(function() {
                $("nav [href='" + $(location).attr('href').split("/").pop() + "']").addClass("active");
                $(".dropdown-menu [href='" + $(location).attr('href').split("/").pop() + "']").addClass("active-drop").removeClass("active");
            });
            */
        </script>
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
                    <a class="navbar-brand" href="index.php">LinkedIn Clone</a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li><a href="#">About</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <?php
                            if ($logged_in) {
                        ?>
                        <form id='nav-search' class="navbar-form navbar-right" role="search" method='GET' action='search_friends.php'>
                            <div class="form-group input-group">
                                <input type="text" class="form-control" placeholder="Find Friends" name='name'>
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="submit">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </button>
                                </span>
                            </div>
                        </form>
                        <li class='dropdown'>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">
                                <img src='<?=$user_info['avatar']?>'> <?=$user_info['name']?> <span class="caret"></span>
                            </a>
                            <ul class='dropdown-menu'>
                                <li><a href='profile.php'>My Profile</a></li>
                                <li><a href='update_profile.php'>Edit Profile</a></li>
                                <li><a href='#'>Friends</a></li>
                                <li><a href='view_messages.php'>Send Message</a></li>
                                <li><a href='logout.php'>Logout</a></li>
                            </ul>
                        </li>
                        <li class='<?= $user_info["num_messages"] > 0 ? "havemail" : "" ?>'>
                            <a href='view_messages.php'>
                                <span class="glyphicon glyphicon-envelope"></span>
                            <?php
                                if ($user_info["num_messages"] > 0) {
                            ?>
                                <span class='inbox-amount'><?=$user_info["num_messages"]?></span>
                            <?php
                                }
                            ?>
                            </a>
                        </li>
                        <?php
                            }
                            else {
                        ?>
                        <li><a href='login.php'>Log In / Register</a></li>
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