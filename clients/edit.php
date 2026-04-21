<?php
$pageTitle = "Edit Client";
include '../includes/header.php';

$id = $_GET['id'] ?? null;
if (!$id) redirect('index.php');

// Fetch client
$stmt = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
$stmt->execute([$id]);
$client = $stmt->fetch();

if (!$client) redirect('index.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name']);
    $phone = sanitize($_POST['phone']);
    $address = sanitize($_POST['address']);
    $details = sanitize($_POST['project_details']);

    if (empty($name) || empty($phone)) {
        $_SESSION['error'] = "Name and Phone are required.";
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE clients SET name = ?, phone = ?, address = ?, project_details = ? WHERE id = ?");
            $stmt->execute([$name, $phone, $address, $details, $id]);
            redirect('index.php', "Client updated successfully!");
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error: " . $e->getMessage();
        }
    }
}
?>

<div class="data-card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header">
        <h2>Update Client Details</h2>
    </div>
    <div style="padding: 24px;">
        <form action="edit.php?id=<?php echo $id; ?>" method="POST">
            <div class="form-group">
                <label for="name">Full Name *</label>
                <input type="text" id="name" name="name" value="<?php echo $client['name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number *</label>
                <input type="text" id="phone" name="phone" value="<?php echo $client['phone']; ?>" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <textarea id="address" name="address" rows="3"><?php echo $client['address']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="project_details">Project Details / Notes</label>
                <textarea id="project_details" name="project_details" rows="4"><?php echo $client['project_details']; ?></textarea>
            </div>
            <div style="display: flex; gap: 12px; margin-top: 32px;">
                <button type="submit" class="btn btn-primary">Update Client</button>
                <a href="index.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
