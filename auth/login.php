<?php
require_once "../includes/config.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $stmt = $pdo->prepare("SELECT id, password_hash FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user["password_hash"])) {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $username;
        header("Location: ../main.php");
        exit;
    } else {
        $error = "Invalid login credentials.";
    }
}

// Fetch top 5 hackers
$stmt = $pdo->query("
    SELECT u.username, COUNT(DISTINCT uc.challenge_id) AS completed_challenges
    FROM users u
    LEFT JOIN user_challenges uc ON u.id = uc.user_id AND uc.status = 'success'
    GROUP BY u.username
    ORDER BY completed_challenges DESC, u.username ASC
    LIMIT 5
");
$topHackers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Define badge system (every 4 successful challenges earns a new badge)
$badgeNames = ["Script Kiddie", "Novice Hacker", "Security Enthusiast", "Exploit Developer", "Pentester", "Cyber Warrior", "Elite Hacker", "Mastermind", "Legend", "God Mode"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: black;
            color: #33ff33;
            font-family: "Courier New", Courier, monospace;
        }
        .logo-container {
            text-align: center;
            margin-top: 1px;
            background-color: #fff;
            border-bottom: 2px solid #33ff33;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px;
        }
        .logo-container img {
            height: 80px;
        }
        .container {
            max-width: 80%;
            margin: 30px auto;
            display: flex;
            justify-content: space-between;
        }
        .login-container, .top-hackers-container {
            background-color: #111;
            border: 2px solid #33ff33;
            border-radius: 10px;
            padding: 20px;
            width: 45%;
        }
        .login-container h2, .top-hackers-container h2 {
            color: #ffcc00;
        }
        .login-container input {
            background-color: #222;
            color: #33ff33;
            border: 1px solid #33ff33;
            padding: 10px;
            margin-bottom: 10px;
            width: 100%;
        }
        .login-container button {
            background-color: #33ff33;
            color: black;
            font-weight: bold;
            width: 100%;
            padding: 10px;
        }
        .login-container a {
            color: #33ff33;
            text-decoration: none;
        }
        .login-container a:hover {
            text-decoration: underline;
        }
        .top-hackers-container ul {
            list-style-type: none;
            padding: 0;
        }
        .top-hackers-container li {
            padding: 5px 0;
            border-bottom: 1px solid #33ff33;
            animation: glow 1.5s infinite alternate;
        }
        .top-hackers-container li span {
            font-weight: bold;
            color: #ffcc00;
        }
        .badge-earned {
            font-weight: bold;
            color: #ffcc00;
            animation: glow-badge 1.5s infinite alternate;
        }
        /* Badge Animation */
        @keyframes glow {
            from {
                text-shadow: 0 0 5px #ffcc00, 0 0 10px #ffcc00;
            }
            to {
                text-shadow: 0 0 10px #ffcc00, 0 0 20px #ffcc00;
            }
        }
        @keyframes glow-badge {
            from {
                text-shadow: 0 0 5px #33ff33, 0 0 10px #33ff33;
            }
            to {
                text-shadow: 0 0 10px #33ff33, 0 0 20px #33ff33;
            }
        }
    </style>
</head>
<body>

<!-- Logo at the top -->
<div class="logo-container">
    <img src="../assets/images/logo.png" alt="Hackademic Logo">
</div>

<div class="container">
    <!-- Login Box (Left) -->
    <div class="login-container">
        <h2>Login</h2>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Login</button>
        </form>
        <p class="mt-3">New user? <a href="register.php">Register</a></p>
    </div>

    <!-- Top 5 Hackers Box (Right) -->
    <div class="top-hackers-container">
        <h2>🏆 Top 5 Hackers</h2>
        <ul>
            <?php if (count($topHackers) > 0): ?>
                <?php foreach ($topHackers as $index => $hacker): ?>
                    <?php
                    $completed = (int)$hacker['completed_challenges'];
                    $badgeCount = floor($completed / 3);
                    ?>
                    <li>
                        <span>#<?= $index + 1 ?>:</span> <?= htmlspecialchars($hacker['username']) ?>
                        <br><small>Challenges Completed: <?= $completed ?></small><br>
                        <?php for ($i = 0; $i < $badgeCount; $i++): ?>
                            <span class="badge-earned">🏆 <?= $badgeNames[$i] ?? "Hacker Legend" ?></span>
                            <?php endfor; ?>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>No hackers found</li>
            <?php endif; ?>
        </ul>
        <a href="/leaderboard.php" class="btn btn-info">Leaderboard</a>
    </div>
</div>

</body>
</html>
