<?php
require_once '../config/db.php';
require_once '../includes/functions.php';
checkLogin();
checkRole(['Admin']);

$id = $_GET['id'] ?? null;

// Prevent self-deletion
if ($id && $id != $_SESSION['user_id']) {
    try {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['success'] = "User deleted successfully!";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error deleting user: " . $e->getMessage();
    }
} else {
    $_SESSION['error'] = "You cannot delete yourself or invalid ID provided.";
}

header("Location: index.php");
exit();
?>
