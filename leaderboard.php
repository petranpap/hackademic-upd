<?php
require_once "includes/config.php"; // Database connection

// Fetch leaderboard data (Top 10 users based on successful challenges)
$stmt = $pdo->query("
    SELECT u.username, COUNT(DISTINCT uc.challenge_id) AS completed_challenges
    FROM users u
    LEFT JOIN user_challenges uc ON u.id = uc.user_id AND uc.status = 'success'
    GROUP BY u.username
    ORDER BY completed_challenges DESC, u.username ASC
    LIMIT 10
");
$leaderboard = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Define badge system (every 3 successful challenges earns a new badge)
$badgeNames = ["Script Kiddie", "Novice Hacker", "Security Enthusiast", "Exploit Developer", "Pentester", "Cyber Warrior", "Elite Hacker", "Mastermind", "Legend", "God Mode"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Leaderboard - Hackademic Challenges</title>
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
            max-width: 90%;
            margin-top: 20px;
        }
        .table {
            --bs-table-bg: #111;
            border: 2px solid #33ff33;
        }
        .table th, .table td {
            color: #33ffcc;
            border: 1px solid #33ff33;
        }
        .badge-earned {
            font-weight: bold;
            color: #66ff66;
            animation: glow-badge 1.5s infinite alternate;
        }
        .badge {
            background-color: #222;
            color: #66ff66;
            border: 1px solid #33ff33;
            border-radius: 5px;
            padding: 2px 5px;
            margin-left: 5px;
        }
        /* Badge Animation */
        @keyframes glow-badge {
            from {
                text-shadow: 0 0 5px #33ff33, 0 0 10px #33ff33;
            }
            to {
                text-shadow: 0 0 10px #33ff33, 0 0 20px #33ff33;
            }
        }
        .search-bar {
            margin-bottom: 20px;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-dark">
    <img src="assets/images/logo.png" alt="Hackademic Logo">
    <div class="nav-buttons">
        <a href="/" class="btn btn-success">Home</a>
    </div>
</nav>

<div class="container">
    <h2>🏆 Leaderboard</h2>
    <form method="GET" class="search-bar">
        <input type="text" name="search" class="form-control" placeholder="Search by username..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
    </form>

    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th>#</th>
                <th>Username</th>
                <th>Challenges Completed</th>
                <th>Badges Earned</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($leaderboard) > 0): ?>
                <?php foreach ($leaderboard as $index => $user): ?>
                    <?php
                    $completed = (int)$user['completed_challenges'];
                    $badgeCount = floor($completed / 3);  // Badges earned every 3 challenges
                    ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td>
                            <?= htmlspecialchars($user['username']) ?>
                            <?php if ($badgeCount > 0): ?>
                                <?php for ($i = 0; $i < $badgeCount; $i++): ?>
                                    <span class="badge">🏆 <?= $badgeNames[$i] ?? "Hacker Legend" ?></span>
                                <?php endfor; ?>
                            <?php endif; ?>
                        </td>
                        <td><?= $completed ?></td>
                        <td>
                            <?php if ($badgeCount > 0): ?>
                                <?php for ($i = 0; $i < $badgeCount; $i++): ?>
                                    <span class="badge-earned">🏆 <?= $badgeNames[$i] ?? "Hacker Legend" ?></span>
                                <?php endfor; ?>
                            <?php else: ?>
                                <span class="badge-earned">No Badges Yet</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No data available</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <!-- Button to trigger the modal -->
    <div class="text-center my-3">
        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#badgeModal">
            What Do Badges Mean?
        </button>
    </div>
    <!-- Badge Explanation Modal -->
    <div class="modal fade" id="badgeModal" tabindex="-1" aria-labelledby="badgeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title" id="badgeModalLabel">Badge Explanations</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul>
                        <?php foreach ($badgeNames as $index => $badge): ?>
                            <li><strong>🏆 <?= $badge ?>:</strong> Earned for completing <?= ($index + 1) * 3 ?> challenges.</li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
