<?php
$pageTitle = "Add Client";
include '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name']);
    $phone = sanitize($_POST['phone']);
    $address = sanitize($_POST['address']);
    $details = sanitize($_POST['project_details']);

    if (empty($name) || empty($phone)) {
        $_SESSION['error'] = "Name and Phone are required.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO clients (name, phone, address, project_details) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $phone, $address, $details]);
            redirect('index.php', "Client added successfully!");
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error: " . $e->getMessage();
        }
    }
}
?>

<div class="data-card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header">
        <h2>Enter Client Details</h2>
    </div>
    <div style="padding: 24px;">
        <form action="add.php" method="POST">
            <div class="form-group">
                <label for="name">Full Name *</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number *</label>
                <input type="text" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <textarea id="address" name="address" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label for="project_details">Project Details / Notes</label>
                <textarea id="project_details" name="project_details" rows="4"></textarea>
            </div>
            <div style="display: flex; gap: 12px; margin-top: 32px;">
                <button type="submit" class="btn btn-primary">Save Client</button>
                <a href="index.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
