<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'u599014834_auto_door_db');
define('DB_PASS', 'FOZIA@1997#####a');
define('DB_NAME', 'u599014834_auto_door_db'); // Prefixed for Hostinger compatibility


// Set your base URL (e.g., http://yourdomain.com/)
define('BASE_URL', '/'); // Use '/' for root as default


try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
