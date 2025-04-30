<?php
session_start();
require_once "../../../includes/config.php"; // Database connection

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



<html>
<head>
<style type="text/css">
.style1 {
	font-family: "Times New Roman", Times, serif;
}
</style>
</head>

<body>
	<div id="result">
		<h1 id="success" style="color: green;"></h1>
		<h1 id="fail" style="color: red;"></h1>
		<a href="/" target="_top"> Go to start!</a> <!-- ✅ This replaces the whole page -->
	</div>
<div id="main_panel">
<font size="6">Central Communication Panel</font><b><font size="6">
		<img border="0" src="banner.gif" width="23" height="24">&nbsp;&nbsp;&nbsp; </font></b>
	<form method="POST" action="Diaxirisths.php" style="height: 250px">
		&nbsp;<p>
		  Type e-mail</span><span lang="en-us" class="style1"> </span>
		  <input type="text" name="name1" size="71"></p>
		  <p>Message</p>
		  <textarea rows="8" name="name2" cols="99" ></textarea><br>


		  <input type="submit" name="submit" value="Send">

		<p><a href="main.htm">Home</a></p>
	</form>
	</div>
</body>
<?php
    if(isset($_POST['submit'])) {
		$name1 = $_POST["name1"];
		$challenge_id = 1; // Make sure this matches the challenge ID in DB
		$user_id = $_SESSION['user_id'];
		if ($name1 === 'Friday13@JasonLives.com') {
			
			echo " <script>document.getElementById('success').innerText='Congratulations!'</script>";
       		 // ✅ Track SUCCESS in the database
        	$stmt = $pdo->prepare("INSERT INTO user_challenges (user_id, challenge_id, status) VALUES (?, ?, 'success')");
        	$stmt->execute([$user_id, $challenge_id]);
			echo "<style> #main_panel{display:none}</style>";
		} else {
			echo " <script>document.getElementById('fail').innerText='Wrong Email!!'</script>";
			  // ❌ Track FAILURE in database (Optional)
        // ❌ Track FAILURE in the database
        $stmt = $pdo->prepare("INSERT INTO user_challenges (user_id, challenge_id, status) VALUES (?, ?, 'failed')");
        $stmt->execute([$user_id, $challenge_id]);
		}
	}
?>
</html>
