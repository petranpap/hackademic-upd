<?php
session_start();
require_once "../../includes/config.php"; // Database connection
/**
 *    ----------------------------------------------------------------
 *    OWASP Hackademic Challenges Project
 *    ----------------------------------------------------------------
 *    Copyright (C) 2010-2011
 *        Andreas Venieris [venieris@owasp.gr]
 *        Anastasios Stasinopoulos [anast@owasp.gr]
 *    ----------------------------------------------------------------
 *    PHP 8 Port & Maintenance (2025)
 *        Petros Papagiannis [peterpapagiannis@yahoo.com]
 *        Cyprus College, Limassol
 *    ----------------------------------------------------------------
 */
// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    die("<h2>Please log in to attempt this challenge.</h2>");
}
?>
<html>
<head>
<meta http-equiv="Content-Language" content="en-us">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1253">
<title>Challenge 005</title>
<style type="text/css">
.style2 {
	font-size: xx-large;
	color: #0000FF;
}
.style3 {
	color: #808000;
}
</style>
<center>
<body bgcolor="black">
<img src="p0wnb.png">
<font color="green">

</head>


<?php

// Define user ID and challenge ID
$user_id = $_SESSION['user_id'];
$challenge_id = 5; // Manually set this to match the challenge ID in DB

if (preg_match("/^p0wnBrowser/",$_SERVER['HTTP_USER_AGENT']))
{
			echo "<H1>Congratulations!</H1>";
       		 // ✅ Track SUCCESS in the database
				$stmt = $pdo->prepare("INSERT INTO user_challenges (user_id, challenge_id, status) VALUES (?, ?, 'success')");
				$stmt->execute([$user_id, $challenge_id]);
}
else
{
	echo "<h2><br><br>Unfortunately, you cannot access the contents of this site...<br>
In order to do this, you must buy p0wnBrowser. It only costs 3500 euros.";
        // ❌ Track FAILURE in the database
        $stmt = $pdo->prepare("INSERT INTO user_challenges (user_id, challenge_id, status) VALUES (?, ?, 'failed')");
        $stmt->execute([$user_id, $challenge_id]);
}
?>

</html>
