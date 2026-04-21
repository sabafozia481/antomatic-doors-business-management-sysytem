<?php
$pageTitle = "Edit User";
include '../includes/header.php';
checkRole(['Admin']);

$id = $_GET['id'] ?? null;
if (!$id) redirect('index.php');

// Fetch user
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) redirect('index.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $role = sanitize($_POST['role']);
    
    // Only update password if provided
    $password_query = "";
    $params = [$name, $email, $role];
    
    if (!empty($_POST['password'])) {
        $password_query = ", password = ?";
        $params[] = password_hash($_POST['password'], PASSWORD_DEFAULT);
    }
    
    $params[] = $id;

    try {
        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, role = ? $password_query WHERE id = ?");
        $stmt->execute($params);
        redirect('index.php', "User updated successfully!");
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }
}
?>

<div class="data-card" style="max-width: 500px; margin: 0 auto;">
    <div class="card-header">
        <h2>Update User Details</h2>
    </div>
    <div style="padding: 24px;">
        <form action="edit.php?id=<?php echo $id; ?>" method="POST">
            <div class="form-group">
                <label for="name">Full Name *</label>
                <input type="text" id="name" name="name" value="<?php echo $user['name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email Address *</label>
                <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>
            </div>
            <div class="form-group">
                <label for="role">User Role *</label>
                <select id="role" name="role" required>
                    <option value="Staff" <?php echo $user['role'] === 'Staff' ? 'selected' : ''; ?>>Staff (Data Entry)</option>
                    <option value="Manager" <?php echo $user['role'] === 'Manager' ? 'selected' : ''; ?>>Manager (Limited Control)</option>
                    <option value="Admin" <?php echo $user['role'] === 'Admin' ? 'selected' : ''; ?>>Admin (Full Access)</option>
                </select>
            </div>
            <div class="form-group">
                <label for="password">Password (Leave blank to keep current)</label>
                <input type="password" id="password" name="password">
            </div>
            <div style="display: flex; gap: 12px; margin-top: 32px;">
                <button type="submit" class="btn btn-primary">Update User</button>
                <a href="index.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
