<?php
$db_host = "localhost";
$db_name = "SWEGroup4";
$db_user = "PUT USERNAME HERE";
$db_pass = "PUT PASSWORD HERE";

try {
  $dbh = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
} catch (PDOException $e) {
  print "Database Error!: " . $e->getMessage() . "<br>";
  die();
}

// http://stackoverflow.com/questions/15990857/reference-frequently-asked-questions-about-pdo/15991623#15991623
// Fixes some issues with PDO
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
// Default fetch method
$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
?>