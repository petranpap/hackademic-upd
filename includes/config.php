<?php
define("DB_DSN", "mysql:host=localhost;dbname=hackademic_db;charset=utf8mb4");
define("DB_USER", "root");  
define("DB_PASS", "");      

try {
    $pdo = new PDO(DB_DSN, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
