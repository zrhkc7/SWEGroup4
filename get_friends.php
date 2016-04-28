<?php
    $page_settings = ["is_script" => true];
    include 'includes.php';

    $links = [];
    $my_friends = [];
    $all_links = [];
    $users = [$user_id];
    $temp_users = [];
    $nodes = [];

    $sql = "SELECT * FROM friendship";
    foreach (dbSelect($sql, [], true) as $friendship) {
        if ($friendship["friend1"] == $user_id) {
            if (!in_array($friendship["friend2"], $users)) {
                $users[] = $friendship["friend2"];
            }
            $my_friends[] = $friendship["friend2"];
            $links[] = [
                "source" => array_search($user_id, $users),
                "target" => array_search($friendship["friend2"], $users)
            ];
        }
        else if ($friendship["friend2"] == $user_id) {
            if (!in_array($friendship["friend1"], $users)) {
                $users[] = $friendship["friend1"];
            }
            $my_friends[] = $friendship["friend1"];
            $links[] = [
                "source" => array_search($user_id, $users),
                "target" => array_search($friendship["friend1"], $users)
            ];
        }
        else {
            $all_links[] = [$friendship["friend1"], $friendship["friend2"]];
        }
    }

    foreach ($all_links as $link) {
        if (in_array($link[0], $my_friends) || in_array($link[1], $my_friends)) {
            if (!in_array($link[0], $users)) {
                $users[] = $link[0];
            }
            if (!in_array($link[1], $users)) {
                $users[] = $link[1];
            }
            $links[] = [
                "source" => array_search($link[0], $users),
                "target" => array_search($link[1], $users)
            ];
        }
    }

    $sql = "SELECT * FROM user WHERE FIND_IN_SET(id, :array)";
    $binds = [":array" => implode(',', $users)];
    foreach (dbSelect($sql, $binds, true) as $info) {
        $temp_users[$info["id"]] = [
            "name" => $info["name"],
            "id" => $info["id"],
            "company" => $info["current_employer"],
            "avatar" => $info["avatar"]
        ];
    }

    foreach ($users as $key => $user) {
        $nodes[$key] = $temp_users[$user];
    }

    echo json_encode(["nodes" => $nodes, "links" => $links]);