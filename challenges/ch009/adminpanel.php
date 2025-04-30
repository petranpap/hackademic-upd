<?php
error_reporting(0);
session_start();
require_once "../../includes/config.php"; // Database connection
/** 
 *    ----------------------------------------------------------------
 *    OWASP Hackademic Challenges Project
 *    ----------------------------------------------------------------
 *    Copyright (C) 2010-2011 
 *   	  Andreas Venieris [venieris@owasp.gr]
 *   	  Anastasios Stasinopoulos [anast@owasp.gr]
 *    ----------------------------------------------------------------
 */
 	// Define user ID and challenge ID	
	 $user_id = $_SESSION['user_id'];
	 $challenge_id = 9; // Manually set this to match the challenge ID in DB
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php

$root=$_POST["username"];
$password=$_POST["password"];

echo '<html>'.
				  '<title>prwtoftyari.gr Administration Panel</title>'.
				  '<form method="POST" action="">'.
				  '<p><big><big>prwtoftyari.gr Administration Panel (TM)</font><hr>'.
				  '<input type="text" name="username"><br>'.
				  '<input type="password" name="password">'.
				  '<input type="submit" name="submit" value="Login">'.
				  '</form>'.
				  '</html>';

if(isset($root,$password))
{
	if (strtolower($root)=="admin" && $password=="teh_n1nj4_pwn3r")
	{
		echo "<hr><p><big><big><font color=Green>Access Granted!!! <p>Congratulations!</font>";		
								       		 // ✅ Track SUCCESS in the database
												$stmt = $pdo->prepare("INSERT INTO user_challenges (user_id, challenge_id, status) VALUES (?, ?, 'success')");
												$stmt->execute([$user_id, $challenge_id]);
	}
	else 
	{
		echo "<hr><p><big><big><font color=RED>Access Denied!</font>";
					        		  // ❌ Track FAILURE in the database
									  $stmt = $pdo->prepare("INSERT INTO user_challenges (user_id, challenge_id, status) VALUES (?, ?, 'failed')");
									  $stmt->execute([$user_id, $challenge_id]);
	}
}
else
{ 
	echo "<hr><p>Please login as Administrator";
}
?>
