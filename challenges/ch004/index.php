<?php
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

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<html>
<head>
<title>Challenge 004</title>
<center>
<body bgcolor="black">
<img src="xssme2.png">
<font color="green">
</head>
<body>
<h2>
<hr>
<?php
// Define user ID and challenge ID
$user_id = $_SESSION['user_id'];
$challenge_id = 4; // Manually set this to match the challenge ID in DB

        // <script>alert(String.fromCharCode(88,88,83,33))</script>
	if(isset($_POST['try_xss'])){
	$try_xss = $_POST['try_xss'];
	if($try_xss == '<script>alert(String.fromCharCode(88,83,83,33))</script>') {
    	echo 'Thank you '.$try_xss.'';
		echo "<H1>Congratulations!</H1>";
       		 // ✅ Track SUCCESS in the database
				$stmt = $pdo->prepare("INSERT INTO user_challenges (user_id, challenge_id, status) VALUES (?, ?, 'success')");
				$stmt->execute([$user_id, $challenge_id]);

    }
	else {
		echo "Error!";
		        // ❌ Track FAILURE in the database
				$stmt = $pdo->prepare("INSERT INTO user_challenges (user_id, challenge_id, status) VALUES (?, ?, 'failed')");
				$stmt->execute([$user_id, $challenge_id]);
		
?>
	Try to XSS me...Again! <br />
	<form method="POST">
	<input type="text" name="try_xss" />
	<input type="submit" value="XSS Me!" />
	</form>
<?php
	}
	}else{
?>
	Try to XSS me...Again! <br />
	<form method="POST">
	<input type="text" name="try_xss" />
	<input type="submit" value="XSS Me!" />
	</form>
<?php }

?>
<hr>
</h2>
</body>
</html>

