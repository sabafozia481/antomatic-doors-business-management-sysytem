<?php
require_once '../config/db.php';
require_once '../includes/functions.php';
checkLogin();
checkRole(['Admin', 'Manager']);

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM expenses WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['success'] = "Expense entry deleted successfully!";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error deleting expense: " . $e->getMessage();
    }
}

header("Location: index.php");
exit();
?>
