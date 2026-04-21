<?php
$pageTitle = "Create Project";
include '../includes/header.php';

// Fetch clients for dropdown
$stmtClients = $pdo->query("SELECT id, name FROM clients ORDER BY name ASC");
$clients = $stmtClients->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_id = sanitize($_POST['client_id']);
    $project_name = sanitize($_POST['project_name']);
    $cost = sanitize($_POST['cost']);
    $status = sanitize($_POST['status']);
    $created_by = $_SESSION['user_id'];

    if (empty($client_id) || empty($project_name) || empty($cost)) {
        $_SESSION['error'] = "All mandatory fields are required.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO projects (client_id, project_name, cost, status, created_by) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$client_id, $project_name, $cost, $status, $created_by]);
            redirect('index.php', "Project created successfully!");
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error: " . $e->getMessage();
        }
    }
}
?>

<div class="data-card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header">
        <h2>Enter Project Details</h2>
    </div>
    <div style="padding: 24px;">
        <form action="add.php" method="POST">
            <div class="form-group">
                <label for="client_id">Select Client *</label>
                <select id="client_id" name="client_id" required>
                    <option value="">-- Select Client --</option>
                    <?php foreach ($clients as $client): ?>
                        <option value="<?php echo $client['id']; ?>"><?php echo $client['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="project_name">Project Name *</label>
                <input type="text" id="project_name" name="project_name" required placeholder="e.g. Office Glass Sliding Door">
            </div>
            <div class="form-group">
                <label for="cost">Estimated Cost ($) *</label>
                <input type="number" id="cost" name="cost" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="status">Project Status</label>
                <select id="status" name="status">
                    <option value="Pending">Pending</option>
                    <option value="Completed">Completed</option>
                </select>
            </div>
            <div style="display: flex; gap: 12px; margin-top: 32px;">
                <button type="submit" class="btn btn-primary">Create Project</button>
                <a href="index.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
