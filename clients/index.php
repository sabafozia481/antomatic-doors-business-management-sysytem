<?php
$pageTitle = "Clients";
include '../includes/header.php';

// Search logic
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$where = "";
$params = [];

if (!empty($search)) {
    $where = "WHERE name LIKE ? OR phone LIKE ?";
    $params = ["%$search%", "%$search%"];
}

// Fetch clients
try {
    $stmt = $pdo->prepare("SELECT * FROM clients $where ORDER BY created_at DESC");
    $stmt->execute($params);
    $clients = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<div class="top-bar" style="margin-bottom: 24px;">
    <div class="search-bar">
        <form action="index.php" method="GET" style="display: flex; gap: 10px;">
            <input type="text" name="search" placeholder="Search by name or phone..." value="<?php echo $search; ?>" style="width: 300px;">
            <button type="submit" class="btn btn-secondary">Search</button>
        </form>
    </div>
    <a href="add.php" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add New Client
    </a>
</div>

<div class="data-card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Date Added</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($clients)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 32px;">No clients found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($clients as $client): ?>
                        <tr>
                            <td class="font-bold"><?php echo $client['name']; ?></td>
                            <td><?php echo $client['phone']; ?></td>
                            <td><?php echo $client['address']; ?></td>
                            <td><?php echo formatDate($client['created_at']); ?></td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    <a href="edit.php?id=<?php echo $client['id']; ?>" class="btn btn-secondary" style="padding: 6px 10px;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if ($_SESSION['user_role'] !== 'Staff'): ?>
                                    <a href="delete.php?id=<?php echo $client['id']; ?>" class="btn btn-danger" style="padding: 6px 10px;" onclick="return confirm('Are you sure you want to delete this client?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
