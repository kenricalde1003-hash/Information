<?php
require_once 'config.php';
requireLogin();

$conn = getConnection();
$message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $stmt = $conn->prepare("INSERT INTO blotter (complainant, respondent, incident_type, incident_date, description, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $_POST['complainant'], $_POST['respondent'], $_POST['incident_type'], $_POST['incident_date'], $_POST['description'], $_POST['status']);
    $stmt->execute();
    $message = 'Blotter record added successfully';
}

// Get all blotter records
$blotters = $conn->query("SELECT * FROM blotter ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blotter - Barangay Information System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container">
        <h2>BLOTTER RECORDS</h2>
        
        <?php if ($message): ?>
            <div class="success-message"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <div class="form-card">
            <form method="POST" action="">
                <input type="hidden" name="action" value="add">
                
                <div class="form-row">
                    <input type="text" name="complainant" placeholder="COMPLAINANT" required>
                    <input type="text" name="respondent" placeholder="RESPONDENT" required>
                </div>
                
                <div class="form-row">
                    <input type="text" name="incident_type" placeholder="INCIDENT TYPE" required>
                    <input type="date" name="incident_date" required>
                </div>
                
                <div class="form-row">
                    <textarea name="description" placeholder="DESCRIPTION" rows="3" required></textarea>
                </div>
                
                <div class="form-row">
                    <select name="status" required>
                        <option value="Pending">Pending</option>
                        <option value="Ongoing">Ongoing</option>
                        <option value="Resolved">Resolved</option>
                    </select>
                    <button type="submit" class="btn-primary">ADD RECORD</button>
                </div>
            </form>
        </div>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>COMPLAINANT</th>
                    <th>RESPONDENT</th>
                    <th>TYPE</th>
                    <th>DATE</th>
                    <th>STATUS</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($blotter = $blotters->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $blotter['id']; ?></td>
                    <td><?php echo htmlspecialchars($blotter['complainant']); ?></td>
                    <td><?php echo htmlspecialchars($blotter['respondent']); ?></td>
                    <td><?php echo htmlspecialchars($blotter['incident_type']); ?></td>
                    <td><?php echo $blotter['incident_date']; ?></td>
                    <td><span class="status-<?php echo strtolower($blotter['status']); ?>"><?php echo $blotter['status']; ?></span></td>
                    <td>
                        <button class="btn-edit">EDIT</button>
                        <button class="btn-print" onclick="window.print()">PRINT</button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php $conn->close(); ?>
