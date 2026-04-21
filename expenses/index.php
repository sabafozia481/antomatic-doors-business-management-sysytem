<?php
$pageTitle = "Expense Management";
include '../includes/header.php';

// Filter logic
$start_date = isset($_GET['start_date']) ? sanitize($_GET['start_date']) : '';
$end_date = isset($_GET['end_date']) ? sanitize($_GET['end_date']) : '';
$where = "";
$params = [];

if (!empty($start_date) && !empty($end_date)) {
    $where = "WHERE date BETWEEN ? AND ?";
    $params = [$start_date, $end_date];
}

// Fetch expense records
try {
    $stmt = $pdo->prepare("SELECT e.*, u.name as user_name FROM expenses e JOIN users u ON e.added_by = u.id $where ORDER BY e.date DESC");
    $stmt->execute($params);
    $expenseRecords = $stmt->fetchAll();
    
    // Calculate total
    $total = 0;
    foreach($expenseRecords as $rec) $total += $rec['amount'];
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<div class="top-bar" style="margin-bottom: 24px;">
    <div class="filter-bar">
        <form action="index.php" method="GET" style="display: flex; gap: 10px; align-items: center;">
            <label class="text-sm">From:</label>
            <input type="date" name="start_date" value="<?php echo $start_date; ?>" style="width: 150px;">
            <label class="text-sm">To:</label>
            <input type="date" name="end_date" value="<?php echo $end_date; ?>" style="width: 150px;">
            <button type="submit" class="btn btn-secondary">Filter</button>
            <button type="button" id="exportCsv" class="btn btn-secondary">
                <i class="fas fa-file-csv"></i> Export
            </button>
        </form>
    </div>
    <a href="add.php" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Expense
    </a>
</div>

<div class="stats-grid" style="grid-template-columns: 1fr; margin-bottom: 24px;">
    <div class="stat-card" style="padding: 16px 24px;">
        <div class="stat-label">Total Expenses for Selection</div>
        <div class="stat-value" style="color: var(--danger);"><?php echo formatCurrency($total); ?></div>
    </div>
</div>

<div class="data-card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>Added By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($expenseRecords)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 32px;">No expense entries found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($expenseRecords as $expense): ?>
                        <tr>
                            <td><?php echo formatDate($expense['date']); ?></td>
                            <td><?php echo $expense['description']; ?></td>
                            <td class="font-bold text-danger" style="color: var(--danger);"><?php echo formatCurrency($expense['amount']); ?></td>
                            <td><?php echo $expense['user_name']; ?></td>
                            <td>
                                <?php if ($_SESSION['user_role'] !== 'Staff'): ?>
                                <a href="delete.php?id=<?php echo $expense['id']; ?>" class="btn btn-danger" style="padding: 6px 10px;" onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
