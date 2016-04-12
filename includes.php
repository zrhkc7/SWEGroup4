<?php

	// Create database connection: $dbh
	include($_SERVER['DOCUMENT_ROOT']."/db.php");

	// Global Variables
	$debug = true;
	$site_name = 'LinkedIn Clone';
	$site_subtext = 'Software Engineering Project - Group 4';
	$message_types = ['success', 'info', 'warning', 'danger'];

	/** FUNCTIONS **/

	/* Begin Misc. Functions */
	
	function connectDB() {
		return null;
	}

	// Disconnect from the DB
	function disconnectDB() {
		$dbh = null;
	}
	
	functions isLoggedIn() {
		return null;
	}

	// Clamps a value between the min and max
	function clamp($value, $min, $max) {
		return max($min, min($max, $value));
	}

	// Get the user id of the user that is logged in
	// -1 = Not logged in
	function getLoggedInUserId() {
		if (isset($user_id)) {
			return $user_id;
		}
		else {
			return -1;
		}
	}

	// Create message cookie
	function createMessage($message, $type = 'info') {
		// Check if supplied type is valid
		if (!in_array($type, $message_types)) {
			$type = $message_types[1];
		}

		// Get current messages in cookie
		$currentCookie = getMessagesByType($type);
		$currentCookie[] = $message;

		// Set cookie
		setcookie('msg-' . $type, json_encode($currentCookie), time() + 12000);
	}

	// Get message array of specific type
	function getMessagesByType($type) {
		// Check if supplied type is valid
		if (!in_array($type, $message_types)) {
			return null;
		}

		// Return json decoding of cookie if not empty, otherwise return empty array
		return isset($_COOKIE['msg-' . $type]) ? json_decode($_COOKIE['msg-' . $type]) : [];
	}

	// Empty out all messages
	function clearMessages() {
		foreach ($type in $message_types) {
			setcookie('msg-' . $type, '', time() - 100);
		}
	}

	function startPage() {
		return null;
	}

	function endPage() {
		return null;
	}

	function login() {
		return null;
	}

	function logout() {
		return null;
	}

	function formatRelativeDate($time) {
		return null;
	}

	function formatDate($time, $format = null) {
		return null;
	}

	// Format user's name
	function formatName($first, $last, $short = false) {
		if ($short) {
			$last = $last[0] . '.';
		}
		return $first . ' ' . $last;
	}

	function calculateTimeDifference($time1, $time2) {
		return null;
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
	function createSession() {
		if (!($user_id = getLoggedInUserId())) {
			return false;	
		}
		
		// Variables
		$session_id = uniqid();
		$expiration_time = time() + 30 * 24 * 60 * 60;
		
		// Create session in DB
		$stmt = $dbh->prepare("INSERT INTO `session` (`user`, `session_id`, `expiration_time`) VALUES (:user_id, :session_id, :expiration_time)");
		$stmt->bindParam(":user_id", $user_id);
		$stmt->bindParam(":session_id", $session_id);
		$stmt->bindParam(":expiration_time", $expiration_time);
		$stmt->execute();
		
		// Create cookie
		setcookie("session_id", $session_id, $expiration_time);
		
		// Success
		return true;
	}
	
	function getCurrentSessionId() {
		if (isset($_COOKIE['session_id'])) {
			return $_COOKIE['session_id'];
		}
		else {
			return false;
		}
	}

	function validateSession() {
		if (!($user_id = getLoggedInUserId()) || !($session_id = getCurrentSessionId())) {
			return false;
		}
		
		$stmt = $dbh->prepare("SELECT COUNT(*) FROM `session` WHERE `user` = :user_id AND `session_id` = :session_id AND `expiration_time` > CURRENT_TIMESTAMP");
		$stmt->bindParam(":user_id", $user_id);
		$stmt->bindParam(":session_id", $session_id);
		$stmt->execute();
		
	    if ($stmt->fetchColumn() == 1) {
	        return true;
	    }
	    else {
	        return false;
	    }
	}

	function deleteSession($session_id) {
		return null;
	}

	function deleteAllSessions($user_id) {
		return null;
	}
	/* End Session Functions */

	/* Begin User Functions */
	function getUser($user_id) {
		return null;
	}

	function createUser($email, $password) {
		return null;
	}

	function updateUser($user_info) {
		return null;
	}

	function deleteUser($user_id) {
		return null;
	}

	function getNewestUsers($amount) {
		return null;
	}
	/* End User Functions */

	/* Begin Password Functions */
	function createPassword($user_id, $password) {
		return null;
	}

	function checkPassword($user_id, $password) {
		return null;
	}

	function updatePassword($user_id, $password) {
		return null;
	}

	function updatePasswordAttempts($user_id, $reset = false) {
		return null;
	}
	/* End Password Functions */

	/* Begin Friendship Functions */
	function createFriendship($sender, $receiver) {
		return null;
	}

	function deleteFriendship($friendship_id) {
		return null;
	}

	function getFriendship($friendship_id) {
		return null;
	}

	function getFriendshipId($friend1, $friend2) {
		return null;
	}

	function getNumberOfFriends($user_id) {
		return null;
	}
	/* End Friendship Functions */

	/* Begin View Functions */
	function createView($viewer, $viewed) {
		return null;
	}

	function getViewsTotal($user_id) {
		return null;
	}

	function getViewsBetweenDates($date1, $date2) {
		return null;
	}

	function getViewsSinceDate($date) {
		return getViewsBetweenDates($date, date());
	}

	function checkUserHasViewed($viewer, $viewed) {
		return null;
	}
	/* End View Functions */

	/* Begin Message Functions */
	function createMessage($sender, $receiver, $content) {
		return null;
	}

	function getMessage($message_id) {
		return null;
	}

	function getUserMessages($user_id, $amount = 25) {
		return null;
	}

	function getNumberOfMessages($user_id) {
		return null;
	}

	function markMessageRead($message_id) {
		return null;
	}

	function checkMessageRead($message_id) {
		return null;
	}

	function deleteMessage($message_id) {
		return null;
	}
	/* End Message Functions */

	/* Begin Skill Functions */
	function createSkill($user_id, $description) {
		return null;
	}

	function deleteSkill($id) {
		return null;
	}

	function getUserSkills($user_id) {
		return null;
	}

	function searchSkills($description) {
		return null;
	}
	/* End Skill Functions */

	/* Begin Endorsement Functions */
	function createEndorsement($user_id, $skill) {
		return null;
	}

	// Get number of people endorsing a person's skill
	function getNumberOfEndorsements($skill) {
		return null;
	}

	function createMultipleEndorsements($user_id, $skill_array) {
		return null;
	}

	function deleteEndorsement($user_id, $skill) {
		return null;
	}

	// Get number of unique people endorsing a specific user
	function getNumberOfEndorsees($user_id) {
		return null;
	}
	/* End Endorsement Functions */

	/* Begin Job History Functions */
	function createJob($user_id, $job_info) {
		return null;
	}

	function getJobHistory($user_id) {
		return null;
	}

	function updateJob($job_id, $job_info) {
		return null;
	}

	function deleteJob($job_id) {
		return null;
	}

	function getJob($job_id) {
		return null;
	}

	function getUsersJobAtSpecificTime($user_id, $time) {
		return null;
	}

	function getNumberOfJobs($user_id) {
		return null;
	}

	function getJobsByEmployer($employer) {
		return null;
	}

	function getJobsByTitle($title) {
		return null;
	}

	function getJobsByDescription($description) {
		return null;
	}
	/* End Job History Functions */

	/* Begin Photo Functions */
	function createPhoto($user_id, $url) {
		return null;
	}

	function deletePhoto($photo_id) {
		return null;
	}

	function getPhotoInfo($photo_id) {
		return null;
	}

	function getPhotos($user_id) {
		return null;
	}

	function getNumberOfPhotos($user_id) {
		return null;
	}

	function getLatestPhotos($amount = 25) {
		return null;
	}
	/* End Photo Functions */

	/* Begin Post Functions */
	function createPost($user_id, $content) {
		return null;
	}

	function getPost($post_id) {
		return null;
	}

	function getPosts($user_id, $amount = 10, $offset = 0) {
		return null;
	}

	function deletePost($post_id) {
		return null;
	}

	function getPostsBetweenTimes($user_id, $start_time, $end_time) {
		return null;
	}

	function searchPosts($search_phrase) {
		return null;
	}
	/* End Post Functions */
?>
