<?php
$pageTitle = "Projects";
include '../includes/header.php';

// Filter logic
$status_filter = isset($_GET['status']) ? sanitize($_GET['status']) : '';
$where = "";
$params = [];

if (!empty($status_filter)) {
    $where = "WHERE p.status = ?";
    $params = [$status_filter];
}

// Pagination logic
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Count total projects for pagination
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM projects p $where");
$countStmt->execute($params);
$total_projects = $countStmt->fetchColumn();
$total_pages = ceil($total_projects / $limit);

// Fetch projects with limit
try {
    $stmt = $pdo->prepare("SELECT p.*, c.name as client_name, u.name as user_name 
                           FROM projects p 
                           JOIN clients c ON p.client_id = c.id 
                           JOIN users u ON p.created_by = u.id 
                           $where 
                           ORDER BY p.created_at DESC
                           LIMIT $limit OFFSET $offset");
    $stmt->execute($params);
    $projects = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<div class="top-bar" style="margin-bottom: 24px;">
    <div class="filter-bar">
        <form action="index.php" method="GET" style="display: flex; gap: 10px;">
            <select name="status" style="width: 200px;" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                <option value="Pending" <?php echo $status_filter === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="Completed" <?php echo $status_filter === 'Completed' ? 'selected' : ''; ?>>Completed</option>
            </select>
            <button type="submit" class="btn btn-secondary">Filter</button>
        </form>
    </div>
    <a href="add.php" class="btn btn-primary">
        <i class="fas fa-plus"></i> Create Project
    </a>
</div>

<div class="data-card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Project Name</th>
                    <th>Client</th>
                    <th>Cost</th>
                    <th>Status</th>
                    <th>Created By</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($projects)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 32px;">No projects found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($projects as $project): ?>
                        <tr>
                            <td class="font-bold"><?php echo $project['project_name']; ?></td>
                            <td><?php echo $project['client_name']; ?></td>
                            <td><?php echo formatCurrency($project['cost']); ?></td>
                            <td>
                                <span class="status-badge <?php echo $project['status'] === 'Completed' ? 'status-completed' : 'status-pending'; ?>">
                                    <?php echo $project['status']; ?>
                                </span>
                            </td>
                            <td><?php echo $project['user_name']; ?></td>
                            <td><?php echo formatDate($project['created_at']); ?></td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    <a href="edit.php?id=<?php echo $project['id']; ?>" class="btn btn-secondary" style="padding: 6px 10px;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if ($_SESSION['user_role'] !== 'Staff'): ?>
                                    <a href="delete.php?id=<?php echo $project['id']; ?>" class="btn btn-danger" style="padding: 6px 10px;" onclick="return confirm('Are you sure you want to delete this project?')">
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
    
    <?php if ($total_pages > 1): ?>
    <div class="card-footer" style="padding: 16px 24px; border-top: 1px solid var(--border); display: flex; justify-content: center; gap: 8px;">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?php echo $i; ?>&status=<?php echo $status_filter; ?>" 
               class="btn <?php echo $page === $i ? 'btn-primary' : 'btn-secondary'; ?>" 
               style="padding: 4px 12px;">
               <?php echo $i; ?>
            </a>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
