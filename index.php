<?php
require_once "includes/session.php";
require_once "includes/config.php"; // Database connection

// Fetch challenges from the database
$stmt = $pdo->query("SELECT id, title, file_path FROM challenges ORDER BY id ASC");
$challenges = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Hackademic Challenges</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: black;
            color: #33ff33;
            font-family: "Courier New", Courier, monospace;
        }
        .navbar {
            background-color: #fff;
            border-bottom: 2px solid #33ff33;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px;
        }
        .navbar img {
            height: 50px;
        }
        .nav-buttons {
            position: absolute;
            right: 20px;
        }
        .container {
            max-width: 95%;
        }
        .sidebar {
            background-color: #111;
            padding: 20px;
            height: 100vh;
            overflow-y: auto;
            border-right: 2px solid #33ff33;
        }
        .sidebar a {
            color: #33ff33;
            display: block;
            padding: 10px;
            text-decoration: none;
            border-bottom: 1px solid #33ff33;
        }
        .sidebar a:hover {
            background-color: #222;
        }
        .main-content {
            padding: 20px;
        }
        .challenge-title {
            color: #ffcc00;
            font-size: 24px;
            border-bottom: 2px solid #33ff33;
            padding-bottom: 10px;
        }
        .challenge-desc {
            font-size: 16px;
            margin-top: 10px;
        }
        .home-container {
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-dark">
        <img src="assets/images/logo.png" alt="Hackademic Logo"> 
        <div class="nav-buttons">
            <a href="/" class="btn btn-success">Home</a>
            <a href="/profile.php" class="btn btn-primary">Profile</a>
            <a href="leaderboard.php" class="btn btn-info">Leaderboard</a>
            <a href="auth/logout.php" class="btn btn-danger">Logout</a>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 sidebar">
                <h4>Challenges</h4>
                <?php foreach ($challenges as $challenge): ?>
                    <a href="?challenge=<?= urlencode($challenge['file_path']) ?>">🔹 <?= htmlspecialchars($challenge['title']) ?></a>
                <?php endforeach; ?>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 main-content">
                <?php 
                if (isset($_GET['challenge'])) {
                    $challengePath = urldecode($_GET['challenge']);
                    
                    // Ensure the challenge path is safe
                    $challengePath = preg_replace('/[^a-zA-Z0-9_\/-]/', '', $challengePath);
                    $xmlFile = "challenges/{$challengePath}/" . basename($challengePath) . ".xml";

                    if (file_exists($xmlFile)) {
                        $xml = simplexml_load_file($xmlFile);
                        $title = (string) $xml->title ?? "Challenge";
                        $description = (string) $xml->description ?? "No description available.";
                    } else {
                        $title = "Challenge Not Found";
                        $description = "No description available.";
                    }
                ?>
                    <div class="challenge-title"><?= htmlspecialchars($title) ?></div>
                    <p class="challenge-desc"><?= nl2br(htmlspecialchars(strip_tags($description))) ?></p>
                    <a href="challenges/<?= htmlspecialchars($challengePath) ?>/index.php" class="btn btn-primary mt-3" target="_blank">Start Challenge</a>
                <?php } else { ?>
                    <div class="home-container">
                        <h2>Welcome to Hackademic Challenges</h2>
                        <h3 style="color: #ffcc00;">Hackademic Challenges Project</h3>
                    </div>
                    <p>Select a challenge from the sidebar to view details.</p>
                <?php } ?>
            </div>
        </div>
    </div>
</body>
</html>
