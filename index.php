<?php
$pageTitle = "Dashboard";
include 'includes/header.php';

// Fetch summary data
try {
    // Total Income
    $stmtIncome = $pdo->query("SELECT SUM(amount) as total FROM income");
    $totalIncome = $stmtIncome->fetch()['total'] ?? 0;

    // Total Expenses
    $stmtExpenses = $pdo->query("SELECT SUM(amount) as total FROM expenses");
    $totalExpenses = $stmtExpenses->fetch()['total'] ?? 0;

    // Active Projects (Pending)
    $stmtActive = $pdo->query("SELECT COUNT(*) as count FROM projects WHERE status = 'Pending'");
    $activeProjects = $stmtActive->fetch()['count'];

    // Completed Projects
    $stmtCompleted = $pdo->query("SELECT COUNT(*) as count FROM projects WHERE status = 'Completed'");
    $completedProjects = $stmtCompleted->fetch()['count'];

    // Recent Activity (Projects)
    $stmtRecent = $pdo->query("SELECT p.*, c.name as client_name FROM projects p JOIN clients c ON p.client_id = c.id ORDER BY p.created_at DESC LIMIT 5");
    $recentProjects = $stmtRecent->fetchAll();

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-icon icon-green">
                <i class="fas fa-wallet"></i>
            </div>
        </div>
        <div class="stat-value"><?php echo formatCurrency($totalIncome); ?></div>
        <div class="stat-label">Total Income</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-icon icon-orange">
                <i class="fas fa-credit-card"></i>
            </div>
        </div>
        <div class="stat-value"><?php echo formatCurrency($totalExpenses); ?></div>
        <div class="stat-label">Total Expenses</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-icon icon-blue">
                <i class="fas fa-tasks"></i>
            </div>
        </div>
        <div class="stat-value"><?php echo $activeProjects; ?></div>
        <div class="stat-label">Active Projects</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-icon icon-purple">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
        <div class="stat-value"><?php echo $completedProjects; ?></div>
        <div class="stat-label">Completed Projects</div>
    </div>
</div>

<div class="data-card">
    <div class="card-header">
        <h2>Recent Projects</h2>
        <a href="<?php echo BASE_URL; ?>projects/index.php" class="btn btn-secondary">View All</a>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Project Name</th>
                    <th>Client</th>
                    <th>Cost</th>
                    <th>Status</th>
                    <th>Created Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($recentProjects)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 32px;">No projects found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($recentProjects as $project): ?>
                        <tr>
                            <td class="font-bold"><?php echo $project['project_name']; ?></td>
                            <td><?php echo $project['client_name']; ?></td>
                            <td><?php echo formatCurrency($project['cost']); ?></td>
                            <td>
                                <span class="status-badge <?php echo $project['status'] === 'Completed' ? 'status-completed' : 'status-pending'; ?>">
                                    <?php echo $project['status']; ?>
                                </span>
                            </td>
                            <td><?php echo formatDate($project['created_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
