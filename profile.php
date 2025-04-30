<?php
require_once "includes/session.php";

// Fetch total number of challenges from the database
$stmt = $pdo->query("SELECT COUNT(*) AS total_challenges FROM challenges");
$totalChallenges = $stmt->fetchColumn() ?? 10; // Default to 10 if query fails

// Fetch completed challenges (only unique successful ones count for progress)
$stmt = $pdo->prepare("
    SELECT COUNT(DISTINCT challenge_id) AS completed
    FROM user_challenges
    WHERE user_id = ? AND status = 'success'
");
$stmt->execute([$_SESSION["user_id"]]);
$result = $stmt->fetch();
$completedCount = $result["completed"] ?? 0;

// Calculate progress percentage (only successful challenges count)
$progress = ($completedCount / $totalChallenges) * 100;

// Define badge system (every 4 successful challenges earns a new badge)
$badgeCount = floor($completedCount / 4);
$nextBadgeProgress = ($completedCount % 4) / 4 * 100;

// Cybersecurity-themed badge names
$badgeNames = ["Script Kiddie", "Novice Hacker", "Security Enthusiast", "Exploit Developer", "Pentester", "Cyber Warrior", "Elite Hacker", "Mastermind", "Legend", "God Mode"];

// Check if a new badge was just earned
$newBadgeUnlocked = ($completedCount % 4 == 0 && $completedCount > 0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: black;
            color: #33ff33;
            font-family: "Courier New", Courier, monospace;
        }
        .navbar {
            background-color: #111;
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
            max-width: 80%;
            margin-top: 20px;
        }
        .progress-bar {
            background-color: #33ff33;
        }
        .badge-list {
            border-left: 2px solid #33ff33;
            padding-left: 20px;
        }
        .challenge-success {
            color: #33ff33;
        }
        .challenge-fail {
            color: red;
        }
        .card {
            background-color: #111;
            border: 2px solid #33ff33;
            color: white;
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 10px;
        }
        .badge-earned {
            font-weight: bold;
            color: #ffcc00;
        }
        /* Badge Animation */
        .new-badge {
            animation: glow 1.5s infinite alternate;
        }
        @keyframes glow {
            from {
                text-shadow: 0 0 5px #ffcc00, 0 0 10px #ffcc00;
            }
            to {
                text-shadow: 0 0 10px #ffcc00, 0 0 20px #ffcc00;
            }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-dark">
        <img src="assets/images/logo.png" alt="Hackademic Logo">
        <div class="nav-buttons">
            <a href="/" class="btn btn-success">Home</a>
            <a href="leaderboard.php" class="btn btn-primary">Leaderboard</a>
            <a href="auth/logout.php" class="btn btn-danger">Logout</a>
        </div>
    </nav>

    <div class="container">
        <h2>Profile - <?= htmlspecialchars($_SESSION["username"]) ?></h2>

        <div class="row">
            <!-- Progress Section -->
            <div class="col-md-8">
                <div class="card">
                    <h3>Progress</h3>
                    <div class="progress mt-2">
                        <div class="progress-bar" role="progressbar" style="width: <?= $progress ?>%;" aria-valuenow="<?= $progress ?>" aria-valuemin="0" aria-valuemax="100">
                            <?= round($progress, 1) ?>%
                        </div>
                    </div>
                </div>

                <!-- Challenge List -->
                <div class="card">
                    <h3>My Challenge Progress</h3>
                    <ul class="list-group badge-list">
                        <?php 
                        $stmt = $pdo->prepare("
                            SELECT c.id, c.title, 
                                   (SELECT COUNT(*) FROM user_challenges uc WHERE uc.user_id = ? AND uc.challenge_id = c.id AND uc.status = 'success') AS success_count,
                                   (SELECT COUNT(*) FROM user_challenges uc WHERE uc.user_id = ? AND uc.challenge_id = c.id AND uc.status = 'failed') AS fail_count
                            FROM challenges c
                        ");
                        $stmt->execute([$_SESSION["user_id"], $_SESSION["user_id"]]);
                        $challenges = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($challenges as $challenge): ?>
                            <li class="list-group-item">
                                <strong><?= htmlspecialchars($challenge['title']) ?></strong> - 
                                <?php if ($challenge['success_count'] > 0): ?>
                                    <span class="challenge-success">✅ Completed (<?= $challenge['success_count'] ?> times)</span>
                                <?php elseif ($challenge['fail_count'] > 0): ?>
                                    <span class="challenge-fail">❌ Failed (<?= $challenge['fail_count'] ?> times)</span>
                                <?php else: ?>
                                    ⏳ Not Attempted
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- Badges Section -->
            <div class="col-md-4">
                <div class="card">
                    <h3>Badges Earned</h3>
                    <ul class="list-group">
                        <?php 
                        for ($i = 0; $i < $badgeCount; $i++): ?>
                            <li class="list-group-item badge-earned <?= $newBadgeUnlocked && $i == ($badgeCount - 1) ? 'new-badge' : '' ?>">
                                🏆 <?= $badgeNames[$i] ?? "Hacker Legend" ?>
                            </li>
                        <?php endfor; ?>

                        <?php if ($badgeCount < count($badgeNames)): ?>
                            <li class="list-group-item">
                                🔜 Next: <strong><?= $badgeNames[$badgeCount] ?? "Hacker Legend" ?></strong> (<?= round($nextBadgeProgress, 1) ?>% earned)
                                <div class="progress mt-2">
                                    <div class="progress-bar" role="progressbar" style="width: <?= $nextBadgeProgress ?>%;" aria-valuenow="<?= $nextBadgeProgress ?>" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
