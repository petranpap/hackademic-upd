<?php
require_once "../includes/config.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);

    // Check if the username or email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    $existingUser = $stmt->fetch();

    if ($existingUser) {
        $error = "Username or email already in use.";
    } else {
        // Insert new user
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
        if ($stmt->execute([$username, $email, $password])) {
            $_SESSION["user_id"] = $pdo->lastInsertId();
            $_SESSION["username"] = $username;
            header("Location: ../index.php");
            exit;
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: black;
            color: #33ff33;
            font-family: "Courier New", Courier, monospace;
        }
        .register-container {
            max-width: 400px;
            margin: auto;
            margin-top: 10%;
            padding: 20px;
            background-color: #111;
            border: 2px solid #33ff33;
            border-radius: 10px;
            text-align: center;
        }
        .register-container h2 {
            color: #ffcc00;
        }
        .register-container input {
            background-color: #222;
            color: #33ff33;
            border: 1px solid #33ff33;
            padding: 10px;
            margin-bottom: 10px;
            width: 100%;
        }
        .register-container button {
            background-color: #33ff33;
            color: black;
            font-weight: bold;
            width: 100%;
            padding: 10px;
        }
        .register-container a {
            color: #33ff33;
            text-decoration: none;
        }
        .register-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="register-container">
        <h2>Register</h2>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Register</button>
        </form>
        <p class="mt-3">Already have an account? <a href="login.php">Login</a></p>
    </div>

</body>
</html>
