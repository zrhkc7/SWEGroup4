<?php
    $page_settings = ["is_script" => true];
    include 'includes.php';

    // Check if user is set
    if (isset($_GET['user'])) {
        $user = $_GET['user'];
    }
    else {
        echo [];
        die();
    }

    $tempViews = [];
    $returnViews = [];
    $sql = "SELECT `timestamp` FROM `view` WHERE `viewed` = :viewed AND `timestamp` > '0000-00-00 00:00:00' ORDER BY `timestamp` ASC";
    $binds = [":viewed" => $user];
    $views = dbSelect($sql, $binds, true);
    foreach ($views as $view) {
        $tempDate = date("M-y", strtotime($view["timestamp"]));
        $tempViews[$tempDate] = isset($tempViews[$tempDate]) ? $tempViews[$tempDate] + 1 : 1;
    }
    foreach ($tempViews as $date => $amount) {
        $returnViews[] = ["date" =>  $date, "views" => $amount];
    }
    echo json_encode($returnViews);
?>