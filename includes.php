<?php

	// Create database connection: $dbh
	include($_SERVER['DOCUMENT_ROOT']."/db.php");

	// Global Variables
	$debug = true;
	$site_name = 'LinkedIn Clone';
	$site_subtext = 'Software Engineering Project - Group 4';

	/** FUNCTIONS **/

	/* Begin Misc. Functions */
	function connectDB() {
		return null;
	}

	function disconnectDB() {
		return null;
	}

	function getLoggedInUserId() {
		return null;
	}

	function createErrorMessage($message, $type = 'warn', $expire = 12000) {
		return null;
	}

	function createErrors($error_array) {
		return null;
	}

	function getErrors() {
		return null;
	}

	function clearErrors() {
		return null;
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

	function formatTime($time, $format = null) {
		return null;
	}

	function formatName($name, $short = false) {
		return null;
	}

	function calculateTimeDifference($time1, $time2) {
		return null;
	}

	function getCurrentURL($full = false) {
		return null;
	}

	function redirect($location = 'index.php') {
		return null;
	}
	/* End Misc. Functions */

	/* Begin Session Functions */
	function createSession($user_id) {
		return null;
	}

	function validateSession($user_id, $session_id) {
		return null;
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