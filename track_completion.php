<?php
require_once "includes/config.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in.");
}

// Extract challenge ID dynamically
$challenge_id = basename(dirname($_SERVER['SCRIPT_FILENAME']));
$challenge_id = filter_var(str_replace("ch", "", $challenge_id), FILTER_VALIDATE_INT);
$user_id = $_SESSION['user_id'];

if ($challenge_id) {
    // ✅ Check if user already completed it
    $check = $pdo->prepare("SELECT * FROM user_challenges WHERE user_id = ? AND challenge_id = ?");
    $check->execute([$user_id, $challenge_id]);
    $result = $check->fetch();

    if (!$result || $result['status'] === 'failed') {
        // ✅ Mark challenge as successful
        $stmt = $pdo->prepare("INSERT INTO user_challenges (user_id, challenge_id, status) 
                               VALUES (?, ?, 'success') 
                               ON DUPLICATE KEY UPDATE status = 'success'");
        $stmt->execute([$user_id, $challenge_id]);

        echo "<p style='color:green;'>✅ Challenge Completed & Saved!</p>";
    }
}
?>
