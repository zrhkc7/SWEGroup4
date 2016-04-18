<?php
    // Create database connection: $dbh
    include $_SERVER['DOCUMENT_ROOT']."/db.php";

    // Global Variables
    $debug = true;
    $site_name = 'LinkedIn Clone';
    $site_subtext = 'Software Engineering Project - Group 4';
    $message_types = ['success', 'info', 'warning', 'danger'];

    // Page Settings
    $page_settings_defaults = [
        "is_script" => false,
        "security_level" => 1,
        "header_visible" => true
    ];

    // Set page settings to defaults if not overridden
    foreach($page_settings_defaults as $setting => $value) {
        if (!isset($page_settings[$setting])) {
            $page_settings[$setting] = $value;
        }
    }

    // Debug options
    if ($debug) {
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        error_reporting(E_ALL);
    }

    // Check if user is logged in
    if (isset($_COOKIE['user_id']) && isset($_COOKIE['session_id'])) {
        $user_id = $_COOKIE['user_id'];
        $session_id = $_COOKIE['session_id'];
        $logged_in = validateSession($user_id, $session_id);
        if (!$logged_in) {
            logout();
        }
    }
    else {
        $logged_in = false;
    }

    // Determine if header is visible
    if (!$page_settings["is_script"] || $page_settings["header_visible"]) {
        include 'header.php';
    }

    /** FUNCTIONS **/

    function dbExecute($sql, $binds, $check_success = false) {
        if (!($dbh = getDB()) || !$sql) {
            return false;
        }

        try {
            $stmt = $dbh->prepare($sql);
            foreach ($binds as $bind => &$value) {
                $stmt->bindParam($bind, $value);
            }
            $success = $stmt->execute();
            return $check_success ? $success : $stmt;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    // Returns an associative array of the results
    // $fetch_all forces the result to be indexed, even if there is only one row
    //     IE: return will be "array(0 => $row_info)" instead of just "$row_info"
    //     This keeps the result consistent if you are selecting something that
    //     may or may not have more than one item
    function dbSelect($sql, $binds, $fetch_all = false) {
        if ($stmt = dbExecute($sql, $binds)) {
            if (!$fetch_all && $stmt->rowCount() == 1) {
                return $stmt->fetch();
            }
            return $stmt->fetchAll();
        }
        else {
            return false;
        }
    }

    function dbInsert($sql, $binds) {
        if (dbExecute($sql, $binds)) {
            $dbh = getDB();
            return $dbh->lastInsertId();
        }
        else {
            return false;
        }
    }

    // Returns number of rows
    // Expects query to select COUNT(*)
    function dbSelectCount($sql, $binds) {
        if ($stmt = dbExecute($sql, $binds)) {
            return $stmt->fetchColumn();
        }
        else {
            return false;
        }
    }

    function checkPostVariables() {
        foreach (func_get_args() as $name) {
            if (!isset($_POST[$name])) {
                return false;
            }
        }
        return true;
    }

    function getUserId() {
        return getGlobal('user_id');
    }

    function getDB() {
        return getGlobal('dbh');
    }

    function getGlobal($variable) {
        if (isset($GLOBALS[$variable])) {
            return $GLOBALS[$variable];
        }
        else {
            return false;
        }
    }

    function connectDB() {
        return null;
    }

    // Disconnect from the DB
    function disconnectDB() {
        $dbh = null;
    }

    // Clamps a value between the min and max
    function clamp($value, $min, $max) {
        return max($min, min($max, $value));
    }

    // Create message cookie
    function createPageMessage($message, $type = 'info') {
        if (!($message_types = getGlobal('message_types'))) {
            return false;
        }

        // Check if supplied type is valid
        if (!in_array($type, $message_types)) {
            $type = $message_types[1];
        }

        // Get current messages in cookie
        $currentCookie = getMessages();
        $currentCookie[$type][] = $message;

        // Set cookie
        setcookie("messages", json_encode($currentCookie), time() + 12000);
    }

    function getMessages() {
        if (isset($_COOKIE['messages'])) {
            return json_decode($_COOKIE['messages'], true);
        }
        else {
            return [];
        }
    }

    // Get message array of specific type
    function getMessagesByType($type) {
        if (!($message_types = getGlobal('message_types'))) {
            return [];
        }

        // Check if supplied type is valid
        if (!in_array($type, $message_types)) {
            return [];
        }

        $messages = getMessages();
        return $messages[$type] ? $messages[$type] : [];
    }

    // Empty out all messages
    function clearMessages() {
        setcookie("messages", '', time() - 100);
        $_COOKIE["messages"] = null;
    }

    function startPage() {
        return null;
    }

    function endPage() {
        disconnectDB();
        return null;
    }

    function login($email, $password) {
        return null;
    }

    function logout() {
        setcookie("user_id", "", time() - 1);
        setcookie("session_id", "", time() - 1);
        return true;
    }

    // Adapted from http://snipplr.com/view/35635/
    // and http://xaviesteve.com/category/web-design/php/
    function formatRelativeDate($time) {
        $secs = time() - $time;
        $second = 1;
        $minute = 60;
        $hour = 60*60;
        $day = 60*60*24;
        $week = 60*60*24*7;
        $month = 60*60*24*7*30;
        $year = 60*60*24*7*30*365;

        if ($secs < 0) { return formatDate($secs);
        }elseif ($secs == 0) { return "now";
        }elseif ($secs > $second && $secs < $minute) { $output = round($secs/$second)." second";
        }elseif ($secs >= $minute && $secs < $hour) { $output = round($secs/$minute)." minute";
        }elseif ($secs >= $hour && $secs < $day) { $output = round($secs/$hour)." hour";
        }elseif ($secs >= $day && $secs < $week) { $output = round($secs/$day)." day";
        }elseif ($secs >= $week && $secs < $month) { $output = round($secs/$week)." week";
        }elseif ($secs >= $month && $secs < $year) { $output = round($secs/$month)." month";
        }elseif ($secs >= $year && $secs < $year*10) { $output = round($secs/$year)." year";
        }else{ $output = " more than a decade ago"; }

        if ($output <> "now") {
            $output = (substr($output,0,2)<>"1 ") ? $output."s" : $output;
        }
        return $output;
    }

    function formatDate($time, $format = null) {
        if ($format == null) {
            return date($time, "M j, Y");
        }
        else {
            return date($time, $format);
        }
    }

    // Format user's name
    function formatName($first, $last, $short = false) {
        if ($short) {
            $last = $last[0] . '.';
        }
        return $first . ' ' . $last;
    }

    // Not sure the point of this function
    function calculateTimeDifference($time1, $time2) {
        return $time2 - $time1;
    }

    function getCurrentURL($full = false) {
        if ($full) {
            return 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
        }
        else {
            return $_SERVER['PHP_SELF'];
        }
    }

    function redirect($location = 'index.php') {
        header("Location: " . $location);
        die();
    }
    /* End Misc. Functions */

    /* Begin Session Functions */
    function createSession($user_id) {

        // Variables
        $session_id = base64_encode(openssl_random_pseudo_bytes(30));
        $expiration_time = time() + 30 * 24 * 60 * 60;

        // Create session in DB
        $sql = "INSERT INTO `session` (`user`, `session_id`, `expiration_time`) VALUES (:user_id, :session_id, FROM_UNIXTIME(:expiration_time))";
        $binds = [
            ":user_id" => $user_id,
            ":session_id" => $session_id,
            ":expiration_time" => $expiration_time
        ];
        if (dbExecute($sql, $binds)) {
            // Create cookies
            setcookie("user_id", $user_id, $expiration_time);
            setcookie("session_id", $session_id, $expiration_time);
        }
        else {
            return false;
        }
    }

    function validateSession($user_id, $session_id) {
        $sql = "SELECT COUNT(*) FROM `session` WHERE `user` = :user_id AND `session_id` = :session_id AND `expiration_time` > CURRENT_TIMESTAMP";
        $binds = [
            ":user_id" => $user_id,
            ":session_id" => $session_id
        ];
        return dbSelectCount($sql, $binds);
    }

    function deleteCurrentSession($user_id, $session_id) {
        $sql = "DELETE FROM `session` WHERE `session_id` = :session_id AND `user` = :user_id";
        $binds = [
            ":user_id" => $user_id,
            ":session_id" => $session_id
        ];
        logout();

        return dbExecute($sql, $binds);
    }

    function deleteAllSessions($user_id) {
        $sql = "DELETE FROM `session` WHERE `user` = :user_id";
        $binds = [
            ":user_id" => $user_id,
            ":session_id" => $session_id
        ];
        logout();

        return dbExecute($sql, $binds);
    }
    /* End Session Functions */

    /* Begin User Functions */
    function getUser($user_id) {
        $sql = "SELECT * FROM `user` WHERE `id` = :user_id LIMIT 1";
        $binds = [":user_id" => $user_id];

        return dbSelect($sql, $binds);
    }

    function createUser($name, $email, $password) {
        // Create user
        $sql = "INSERT INTO `user` (`name`, `email`) VALUES (:name, :email)";
        $binds = [
            ":name" => $name,
            ":email" => $email
        ];
        $user_id = dbInsert($sql, $binds);

        // Create password
        if (createPassword($user_id, $password)) {
            return true;
        }
        else if ($user_id){
            deleteUser($user_id);
            return false;
        }
    }

    function updateUser($user_id, $user_info) {
        // Possible attributes
        $attributes = ["name", "current_employer", "avatar", "email", "phone", "summary"];
        // Build SQL string
        $sql  = "UPDATE `user` SET";
        foreach ($attributes as $attribute) {
            if (isset($user_info[$attribute])) {
                $sql .= " `$attribute` = :$attribute,";
                // Build binds array
                $binds[":$attribute"] = $user_info[$attribute];
            }
        }
        // Remove trailing comma
        $sql = trim($sql, ',');
        $sql .= " WHERE `id` = :user_id";
        $binds[":user_id"] = $user_id;

        // Execute
        return dbExecute($sql, $binds, true);
    }

    function deleteUser($user_id, $recent = false) {
        $sql = "DELETE FROM `user` WHERE `id` = :user_id ";
        $sql .= $recent ? "AND `timestamp` > FROM_UNIXTIME(:time) " : "";
        $sql .= "LIMIT 1";
        $binds = [":user_id" => $user_id];
        if ($recent) {
            $binds[":time"] = time() - 600;
        }
        return dbExecute($sql, $binds, true);
    }

    function getNewestUsers($amount = 10) {
        $sql = "SELECT `id`, `name`, `avatar` FROM `user` ORDER BY `id` DESC LIMIT :amount";
        $binds = [":amount" => $amount];

        return dbSelect($sql, $binds, $amount != 1);
    }
    /* End User Functions */

    /* Begin Password Functions */
    function getPasswordInfo($email) {
        $sql = "SELECT * FROM `password` WHERE `user` = (SELECT `id` FROM `user` WHERE `email` = :email LIMIT 1)";
        $binds = [":email" => $email];

        return dbSelect($sql, $binds);
    }

    function createPassword($user_id, $password) {
        $sql = "INSERT INTO `password` (`user`, `hashed_pass`) VALUES (:user_id, :hashed_pass)";
        $binds = [
            ":user_id" => $user_id,
            ":hashed_pass" => password_hash($password, PASSWORD_BCRYPT)
        ];
        return dbExecute($sql, $binds, true);
    }

    function checkPassword($user_id, $password) {
        $sql = "SELECT `hashed_pass` FROM `password` WHERE `user` = :user_id LIMIT 1";
        $binds = [":user_id" => $user_id];
        $password_info = dbSelect($sql, $binds);

        return password_verify($password, $password_info["hashed_pass"]);
    }

    function updatePassword($user_id, $password) {
        $sql = "UPDATE `password` SET `hashed_pass` = :hashed_pass WHERE `user` = :user_id LIMIT 1";
        $binds = [
            ":hashed_pass" => password_hash($password, PASSWORD_BCRYPT),
            ":user_id"     => $user_id
        ];
        return dbExecute($sql, $binds, true);
    }

    function updatePasswordAttempts($user_id, $correct_guess, $attempts) {
        // Reset when user guessed correctly and attempts is greater than 0
        // Increase when user guessed incorrectly and attempts is less than 5
        if (($correct_guess && $attempts != 0) && $attempts < 5) {
            if (!($dbh = getDB())) {
                return false;
            }

            // Reset when guessed correctly
            if ($correct_guess) {
                $attempts = -1;
            }
            $sql = "UPDATE `password` SET `attempts` = :attempts WHERE `user` = :user_id";
            $binds = [
                ":user_id" => $user_id,
                ":attempts" => $attempts++
            ];

            return dbExecute($sql, $binds, true);
        }
        else {
            return false;
        }
    }
    /* End Password Functions */

    /* Begin Friendship Functions */
    function createFriendship($sender, $receiver) {
        $sql = "INSERT INTO `friendship` (`friend1`, `friend2`) VALUES (:sender, :receiver)";
        $binds = [
            ":sender" => $sender,
            ":receiver" => $receiver
        ];
        return dbInsert($sql, $binds);
    }

    function acceptFriendship($user_id, $friendship_id) {
        $sql = "UPDATE `friendship` SET `time_accepted` = CURRENT_TIMESTAMP WHERE `id` = :friendship_id AND `friend2` = :user_id LIMIT 1";
        $binds = [
            ":friendship_id" => $friendship_id,
            ":user_id" => $user_id
        ];
        return dbExecute($sql, $binds, true);
    }

    function deleteFriendship($user_id, $friendship_id) {
        $sql = "DELETE FROM `friendship` WHERE `id` = :friendship_id AND (`friend1` = :user_id1 OR `friend2` = :user_id2) LIMIT 1";
        $binds = [
            ":friendship_id" => $friendship_id,
            ":user_id1" => $user_id,
            ":user_id2" => $user_id
        ];
        return dbExecute($sql, $binds, true);
    }

    function getFriendship($friendship_id) {
        $sql = "SELECT * FROM `friendship` WHERE `id` = :friendship_id LIMIT 1";
        $binds = [":friendship_id" => $friendship_id];
        return dbSelect($sql, $binds);
    }

    function getFriendshipId($friend1, $friend2) {
        $sql = "SELECT `id` FROM `friendship` WHERE (`friend1` = :user1 AND `friend2` = :user2) OR (`friend1` = :user22 AND `friend2` = :user11) LIMIT 1";
        $binds = [
            ":user1" => $friend1,
            ":user2" => $friend2,
            ":user11" => $friend1,
            ":user22" => $friend2
        ];
        $result = dbSelecT($sql, $binds);
        return $result["id"];
    }

    function getNumberOfFriends($user_id) {
        $sql = "SELECT COUNT(*) FROM `friendship` WHERE (`friend1` = :user1 OR `friend2` = :user11) AND `time_accepted` IS NOT NULL";
        $binds = [
            ":user1" => $user_id,
            ":user11" => $user_id
        ];
        return dbSelectCount($sql, $binds);
    }

    function checkFriendship($user1, $user2) {
        $sql = "SELECT COUNT(*) FROM `friendship` WHERE ((`friend1` = :user1 AND `friend2` = :user2) OR (`friend1` = :user22 AND `friend2` = :user11)) AND `time_accepted` IS NOT NULL";
        $binds = [
            ":user1" => $user1,
            ":user2" => $user2,
            ":user11" => $user1,
            ":user22" => $user2
        ];
        return dbSelectCount($sql, $binds);
    }

    /* End Friendship Functions */

    /* Begin View Functions */
    function createView($viewer, $viewed) {
        $sql = "INSERT INTO `view` (`viewer`, `viewed`) VALUES (:viewer, :viewed)";
        $binds = [
            ":viewer" => $viewer,
            ":viewed" => $viewed
        ];
        return dbExecute($sql, $binds, true);
    }

    function getViews($user_id, $date1 = null, $date2 = null) {
        $sql = "SELECT COUNT(*) FROM `view` WHERE `viewed` = :user_id";
        $binds = [":user_id" => $user_id];
        if ($date1) {
            $sql .= " AND `timestamp` > FROM_UNIXTIME(:date1)";
            $binds[":date1"] = $date1;
        }
        if ($date2) {
            $sql .= " AND `timestamp` < FROM_UNIXTIME(:date2)";
            $binds[":date2"] = $date2;
        }
        return dbSelectCount($sql, $binds);
    }

    function getViewsBetweenDates($user_id, $date1, $date2) {
        return getViews($user_id, $date1, $date2);
    }

    function getViewsSinceDate($user_id, $date) {
        return getViews($user_id, $date, date());
    }

    function checkUserHasViewed($viewer, $viewed) {
        $sql = "SELECT COUNT(*) FROM `view` WHERE `viewer` = :viewer AND `viewed` = :viewed";
        $binds = [
            ":viewer" => $viewer,
            ":viewed" => $viewed
        ];
        return dbSelectCount($sql, $binds);
    }
    /* End View Functions */

    /* Begin Message Functions */
    function createMessage($sender, $receiver, $content) {
        $sql = "INSERT INTO `message` (`sender`, `receiver`, `content`) VALUES (:sender, :receiver, :content)";
        $binds = [
            ":sender" => $sender,
            ":receiver" => $receiver,
            ":content" => $content
        ];
        return dbInsert($sql, $binds);
    }

    function getMessage($message_id) {
        $sql = "SELECT * FROM `message` WHERE `id` = :message_id LIMIT 1";
        $binds = [":message_id" => $message_id];
        return dbSelect($sql, $binds);
    }

    function getUserMessages($user_id, $amount = 25) {
        $sql = "SELECT * FROM `message` WHERE `receiver` = :user_id LIMIT :amount";
        $binds = [
            ":user_id" => $user_id,
            ":amount" => $amount
        ];
        return dbSelect($sql, $binds, $amount != 1);
    }

    function getNumberOfMessages($user_id, $all = false) {
        $sql = "SELECT COUNT(*) FROM `message` WHERE `receiver` = :user_id";
        $sql .= !$all ? " AND `time_viewed` IS NOT NULL" : "";
        $binds = [":user_id" => $user_id];
        return dbSelectCount($sql, $binds);
    }

    function markMessageRead($message_id) {
        $sql = "UPDATE `message` SET `time_viewed` = CURRENT_TIMESTAMP WHERE `id` = :message_id LIMIT 1";
        $binds = [":message_id" => $message_id];
        return dbExecute($sql, $binds, true);
    }

    function isMessageRead($message_id) {
        $sql = "SELECT COUNT(*) FROM `message` WHERE `id` = :message_id AND `time_viewed` IS NOT NULL";
        $binds = [":message_id" => $message_id];
        return dbSelectCount($sql, $binds);
    }

    function deleteMessage($user_id, $message_id) {
        $sql = "DELETE FROM `message` WHERE `id` = :message_id AND `receiver` = :receiver";
        $binds = [
            ":message_id" => $message_id,
            ":receiver" => $user_id
        ];
        return dbExecute($sql, $binds, true);
    }
    /* End Message Functions */

    /* Begin Skill Functions */
    function createSkill($user_id, $description) {
        $sql = "INSERT INTO `skill` (`user`, `description`) VALUES (:user_id, :description)";
        $binds = [
            ":user_id" => $user_id,
            ":description" => $description
        ];
        return dbExecute($sql, $binds, true);
    }

    function deleteSkill($user_id, $id) {
        $sql = "DELETE FROM `skill` WHERE `user` = :user_id AND `id` = :id";
        $binds = [
            ":user_id" => $user_id,
            ":id" => $id
        ];
        return dbExecute($sql, $binds, true);
    }

    function getUserSkills($user_id) {
        $sql = "SELECT `id`, `description` FROM `skill` WHERE `user` = :user_id";
        $binds = [":user_id" => $user_id];
        return dbSelect($sql, $binds, true);
    }

    function searchSkills($description) {
        $sql = "SELECT `id`, `description` FROM `skill` WHERE `description` LIKE :description LIMIT 15";
        $binds = [":description" => "%" . $description . "%"];
        return dbSelect($sql, $binds, true);
    }
    /* End Skill Functions */

    /* Begin Endorsement Functions */
    function createEndorsement($user_id, $skill) {
        $sql = "INSERT INTO `endorsement` (`endorsee`, `skill`) VALUES (:user_id, :skill)";
        $binds = [
            ":user_id" => $user_id,
            ":skill" => $skill
        ];
        return dbExecute($sql, $binds, true);
    }

    // Get number of people endorsing a person's skill
    function getNumberOfEndorsements($skill) {
        $sql = "SELECT COUNT(*) FROM `endorsement` WHERE `skill` = :skill";
        $binds = [":skill" => $skill];
        return dbSelectCount($sql, $binds);
    }

    function deleteEndorsement($skill) {
        $sql = "DELETE FROM `endorsement` WHERE `skill` = :skill";
        $binds = [":skill" => $skill];
        return dbExecute($sql, $binds, true);
    }

    // returns indexed array of arrays of id, description, num_endorsements
    function getEndorsements($user_id) {
        $sql = "SELECT `s`.`id`, `s`.`description`, COUNT(`e`.`endorsee`) AS `num_endorsements` FROM `skill` `s` LEFT JOIN `endorsement` `e` ON `s`.`id`=`e`.`skill` WHERE `s`.`user` = :user_id GROUP BY `s`.`id`";
        $binds = [":user_id" => $user_id];
        return dbSelect($sql, $binds, true);
    }

    // Get number of unique people endorsing a specific user
    function getNumberOfEndorsees($user_id) {
        $sql = "SELECT COUNT(DISTINCT `endorsee`) FROM `endorsement` WHERE `skill` IN (SELECT `id` FROM `skill` WHERE `user` = :user_id)";
        $binds = [":user_id" => $user_id];
        return dbSelectCount($sql, $binds);
    }
    /* End Endorsement Functions */

    /* Begin Job History Functions */
    function createJob($user_id, $job_info) {
        $attributes = ["employer", "title", "date_started", "date_finished", "description", "user"];
        $sql = "INSERT INTO `job_history` (`employer`, `title`, `date_started`, `date_finished`, `description`, `user`) VALUES (:employer, :title, :date_started, :date_finished, :description, :user)";
        foreach($attributes as $attribute) {
            $binds[":" . $attribute] = isset($job_info[$attribute]) ? $job_info[$attribute] : null;
        }
        return dbInsert($sql, $binds);
    }

    function getJobHistory($user_id) {
        $sql = "SELECT * FROM `job_history` WHERE `user` = :user_id";
        $binds = [":user_id" => $user_id];
        return dbSelect($sql, $binds, true);
    }

    function updateJob($user_id, $job_id, $job_info) {
        $attributes = ["employer", "title", "date_started", "date_finished", "description", "user"];
        $sql = "UPDATE `job_history` SET";
        foreach ($attributes as $attribute) {
            $sql .= isset($job_info[$attribute]) ? " `$attribute` = " . $job_info[$attribute] . " " : "";
            $binds[":" . $attribute] = $job_info[$attribute];
        }
        $sql .= "WHERE `user` = :user_id";
        $binds[":user_id"] = $user_id;
        return dbExecute($sql, $binds, true);
    }

    function deleteJob($job_id) {
        $sql = "DELETE FROM `job_history` WHERE `id` = :job_id";
        $binds = [":job_id" => $job_id];
        return dbExecute($sql, $binds, true);
    }

    function getJob($job_id) {
        $sql = "SELECT * FROM `job_history` WHERE `id` = :job_id LIMIT 1";
        $binds = [":job_id" => $job_id];
        return dbSelect($sql, $binds);
    }

    function getUsersJobsAtSpecificTime($user_id, $time) {
        $sql = "SELECT * FROM `job_history` WHERE `user` = :user_id AND `date_started` < FROM_UNIXTIME(:time1, 'YYYY-MM-DD') AND (`date_finished` > FROM_UNIXTIME(:time2, 'YYYY-MM-DD') OR `date_finished` IS NULL)";
        $binds = [
            ":user_id" => $user_id,
            ":time1" => $time,
            ":time2" => $time
        ];
        return dbSelect($sql, $binds, true);
    }

    function getNumberOfJobs($user_id) {
        $sql = "SELECT COUNT(*) FROM `job_history` WHERE `user` = :user_id";
        $binds = [":user_id" => $user_id];
        return dbSelectCount($sql, $binds);
    }

    // Highly doubt we will need these 3 functions in our scope
    function getJobsByEmployer($employer) {
        return null;
    }

    // Highly doubt we will need these 3 functions in our scope
    function getJobsByTitle($title) {
        return null;
    }

    // Highly doubt we will need these 3 functions in our scope
    function getJobsByDescription($description) {
        return null;
    }
    /* End Job History Functions */

    /* Begin Photo Functions */
    function createPhoto($user_id, $url) {
        $sql = "INSERT INTO `photo` (`user`, `url`) VALUES (:user_id, :url)";
        $binds = [
            ":user_id" => $user_id,
            ":url" => $url
        ];
        return dbInsert($sql, $binds);
    }

    function deletePhoto($photo_id) {
        $sql = "DELETE FROM `photo` WHERE `id` = :photo_id";
        $binds = [":photo_id" => $photo_id];
        return dbExecute($sql, $binds, true);
    }

    function getPhotoInfo($photo_id) {
        $sql = "SELECT * FROM `photo` WHERE `id` = :photo_id LIMIT 1";
        $binds = [":photo_id" => $photo_id];
        return dbSelect($sql, $binds);
    }

    function getPhotos($user_id, $amount = null) {
        $sql = "SELECT * FROM `photo` WHERE `user` = :user_id";
        $binds = [":user_id" => $user_id];
        if ($amount) {
            $sql .= "ORDER BY `id` DESC LIMIT :amount";
            $binds[":amount"] = $amount;
        }
        return dbSelect($sql, $binds, true);
    }

    function getNumberOfPhotos($user_id) {
        $sql = "SELECT COUNT(*) FROM `photo` WHERE `user` = :user_id";
        $binds = [":user_id" => $user_id];
        return dbSelectCount($sql, $binds);
    }

    function getLatestPhotos($user_id, $amount = 25) {
        return getPhotos($user_id, $amount);
    }
    /* End Photo Functions */

    /* Begin Post Functions */
    function createPost($user_id, $content) {
        $sql = "INSERT INTO `post` (`user_id`, `content`) VALUES (:user_id, :content)";
        $binds = [
            ":user_id" => $user_id,
            ":content" => $content
        ];
        return dbInsert($sql, $binds);
    }

    function getPost($post_id) {
        $sql = "SELECT * FROM `post` WHERE `id` = :post_id LIMIT 1";
        $binds = [":post_id" => $post_id];
        return dbSelect($sql, $binds);
    }

    function getPosts($user_id, $amount = 10, $offset = 0) {
        $sql = "SELECT * FROM `post` WHERE `user_id` = :user_id LIMIT :offset,:amount";
        $binds = [
            ":user_id" => $user_id,
            ":offset" => $offset,
            ":amount" => $amount
        ];
        return dbSelect($sql, $binds, true);
    }

    function deletePost($user_id, $post_id) {
        $sql = "DELETE FROM `post` WHERE `id` = :post_id AND `user_id` = :user_id";
        $binds = [
            ":post_id" => $post_id,
            ":user_id" => $user_id
        ];
    }

    // Could merge this into getPosts but I'm tired lol
    function getPostsBetweenTimes($user_id, $start_time, $end_time) {
        $sql = "SELECT * FROM `post` WHERE `user_id` = :user_id AND `timestamp` > FROM_UNIXTIME(:start_time) AND `timestamp` < FROM_UNIXTIME(:end_time)";
        $binds = [
            ":user_id" => $user_id,
            ":start_time" => $start_time,
            ":end_time" => $end_time
        ];
        return dbSelect($sql, $binds, true);
    }

    function searchPosts($search_phrase) {
        $sql = "SELECT * FROM `post` WHERE `content` LIKE :search LIMIT 10";
        $binds = [":search" => "%" . $search_phrase . "%"];
        return dbSelect($sql, $binds, true);
    }
    /* End Post Functions */
?>