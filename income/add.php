<?php
$pageTitle = "Add Income";
include '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = sanitize($_POST['amount']);
    $description = sanitize($_POST['description']);
    $date = sanitize($_POST['date']);
    $added_by = $_SESSION['user_id'];

    if (empty($amount) || empty($date)) {
        $_SESSION['error'] = "Amount and Date are required.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO income (amount, description, date, added_by) VALUES (?, ?, ?, ?)");
            $stmt->execute([$amount, $description, $date, $added_by]);
            redirect('index.php', "Income added successfully!");
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error: " . $e->getMessage();
        }
    }
}
?>

<div class="data-card" style="max-width: 500px; margin: 0 auto;">
    <div class="card-header">
        <h2>New Income Entry</h2>
    </div>
    <div style="padding: 24px;">
        <form action="add.php" method="POST">
            <div class="form-group">
                <label for="amount">Amount ($) *</label>
                <input type="number" id="amount" name="amount" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="date">Date *</label>
                <input type="date" id="date" name="date" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description / Source</label>
                <textarea id="description" name="description" rows="3" placeholder="e.g. Payment for Project X"></textarea>
            </div>
            <div style="display: flex; gap: 12px; margin-top: 32px;">
                <button type="submit" class="btn btn-primary">Save Income</button>
                <a href="index.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
