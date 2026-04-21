<?php
$pageTitle = "User Management";
include '../includes/header.php';
checkRole(['Admin']);

// Fetch users
try {
    $stmt = $pdo->query("SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC");
    $users = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<div class="top-bar" style="margin-bottom: 24px; justify-content: flex-end;">
    <a href="add.php" class="btn btn-primary">
        <i class="fas fa-user-plus"></i> Create New User
    </a>
</div>

<div class="data-card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td class="font-bold"><?php echo $user['name']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td>
                            <span class="status-badge status-completed" style="background: #e0f2fe; color: #0369a1;">
                                <?php echo $user['role']; ?>
                            </span>
                        </td>
                        <td><?php echo formatDate($user['created_at']); ?></td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <a href="edit.php?id=<?php echo $user['id']; ?>" class="btn btn-secondary" style="padding: 6px 10px;">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php if ($user['email'] !== $_SESSION['user_email']): // Prevent self-delete ?>
                                <a href="delete.php?id=<?php echo $user['id']; ?>" class="btn btn-danger" style="padding: 6px 10px;" onclick="return confirm('Are you sure you want to delete this user?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
