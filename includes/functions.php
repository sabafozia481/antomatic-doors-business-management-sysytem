<?php
// Function to sanitize input
function sanitize($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Check if user is logged in
function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: " . BASE_URL . "login.php");
        exit();
    }
}

// Check role access
function checkRole($roles) {
    if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], $roles)) {
        $_SESSION['error'] = "You do not have permission to access this page.";
        header("Location: ../index.php");
        exit();
    }
}

// Redirect with message
function redirect($path, $message = "", $type = "success") {
    if (!empty($message)) {
        $_SESSION[$type] = $message;
    }
    header("Location: $path");
    exit();
}

// Format currency
function formatCurrency($amount) {
    return "$" . number_format($amount, 2);
}

// Format date
function formatDate($date) {
    return date("d M Y", strtotime($date));
}

// Get user name by ID
function getUserName($pdo, $id) {
    $stmt = $pdo->prepare("SELECT name FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch();
    return $user ? $user['name'] : 'Unknown';
}
