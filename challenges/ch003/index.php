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

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<html>
<head>
<title>Challenge 003</title>
<center>
<body bgcolor="black">
<img src="xssme1.png">
<font color="green">
</head>
<body>
<h2>
<hr>
<?php
		$challenge_id = 3; // Make sure this matches the challenge ID in DB
		$user_id = $_SESSION['user_id'];

	if(isset($_POST['try_xss'])){
		$try_xss = $_POST['try_xss'];
	$try_xss= preg_replace('/\s+/', '', $try_xss);
	$try_xss= preg_replace('/type="text\/javascript"/', '', $try_xss);
	$try_xss= preg_replace("/type='text\/javascript'/", '', $try_xss);	
	if (  (preg_match("/<script>alert\(\'XSS!\'\);?<\/script>/",$try_xss)) or
               (preg_match('/<script>alert\(\"XSS!\"\);?<\/script>/',$try_xss)) ) {
       		 // ✅ Track SUCCESS in the database
				$stmt = $pdo->prepare("INSERT INTO user_challenges (user_id, challenge_id, status) VALUES (?, ?, 'success')");
				$stmt->execute([$user_id, $challenge_id]);
    		echo 'Thank you'.' '.($_POST['try_xss']).'!';
			echo "<H1>Congratulations!</H1>";
			echo '<a href="/" target="_top"> Go to start!</a>';

    }
	else {
		echo "Error!";
		        // ❌ Track FAILURE in the database
				$stmt = $pdo->prepare("INSERT INTO user_challenges (user_id, challenge_id, status) VALUES (?, ?, 'failed')");
				$stmt->execute([$user_id, $challenge_id]);
	
?>
	Try to XSS me using the straight forward way... <br />
	<form method="POST">
	<input type="text" name="try_xss" />
	<input type="submit" value="XSS Me!" />
	</form>
<?php
	}
	}else{
?>
Try to XSS me using the straight forward way... <br />
	<form method="POST">
	<input type="text" name="try_xss" />
	<input type="submit" value="XSS Me!" />
	</form>
<?php }?>
<hr>
</h2>
</body>
</html>

