<?php
require_once '../config/db.php';
require_once '../includes/functions.php';
checkLogin();
checkRole(['Admin', 'Manager']);

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['success'] = "Project deleted successfully!";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error deleting project: " . $e->getMessage();
    }
}

header("Location: index.php");
exit();
?>
