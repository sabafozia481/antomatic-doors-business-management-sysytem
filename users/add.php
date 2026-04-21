<?php
$pageTitle = "Create User";
include '../includes/header.php';
checkRole(['Admin']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = sanitize($_POST['role']);

    if (empty($name) || empty($email) || empty($_POST['password'])) {
        $_SESSION['error'] = "All fields are required.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $email, $password, $role]);
            redirect('index.php', "User created successfully!");
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error: " . $e->getMessage();
        }
    }
}
?>

<div class="data-card" style="max-width: 500px; margin: 0 auto;">
    <div class="card-header">
        <h2>Enter User Details</h2>
    </div>
    <div style="padding: 24px;">
        <form action="add.php" method="POST">
            <div class="form-group">
                <label for="name">Full Name *</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email Address *</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="role">User Role *</label>
                <select id="role" name="role" required>
                    <option value="Staff">Staff (Data Entry)</option>
                    <option value="Manager">Manager (Limited Control)</option>
                    <option value="Admin">Admin (Full Access)</option>
                </select>
            </div>
            <div class="form-group">
                <label for="password">Password *</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div style="display: flex; gap: 12px; margin-top: 32px;">
                <button type="submit" class="btn btn-primary">Create User</button>
                <a href="index.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
