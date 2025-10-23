<?php
require_once 'config.php';
requireLogin();

$conn = getConnection();
$message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $permit_number = 'P' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
    $stmt = $conn->prepare("INSERT INTO permits (business_name, owner_name, business_address, business_type, permit_number) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $_POST['business_name'], $_POST['owner_name'], $_POST['business_address'], $_POST['business_type'], $permit_number);
    $stmt->execute();
    $message = 'Permit generated successfully';
}

// Get all permits
$permits = $conn->query("SELECT * FROM permits ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permits - Barangay Information System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container">
        <h2>BARANGAY BUSINESS PERMIT</h2>
        
        <?php if ($message): ?>
            <div class="success-message"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <div class="form-card">
            <form method="POST" action="">
                <input type="hidden" name="action" value="add">
                
                <div class="form-row">
                    <input type="text" name="business_name" placeholder="BUSINESS NAME" required>
                    <input type="text" name="owner_name" placeholder="OWNER NAME" required>
                    <input type="text" name="business_address" placeholder="BUSINESS ADDRESS" required>
                </div>
                
                <div class="form-row">
                    <input type="text" name="business_type" placeholder="TYPE OF BUSINESS" required>
                    <button type="submit" class="btn-primary">GENERATE PERMIT</button>
                </div>
            </form>
        </div>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>BUSINESS NAME</th>
                    <th>OWNER</th>
                    <th>ADDRESS</th>
                    <th>TYPE</th>
                    <th>PERMIT #</th>
                    <th>ISSUED</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($permit = $permits->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $permit['id']; ?></td>
                    <td><?php echo htmlspecialchars($permit['business_name']); ?></td>
                    <td><?php echo htmlspecialchars($permit['owner_name']); ?></td>
                    <td><?php echo htmlspecialchars($permit['business_address']); ?></td>
                    <td><?php echo htmlspecialchars($permit['business_type']); ?></td>
                    <td><?php echo $permit['permit_number']; ?></td>
                    <td><?php echo date('Y-m-d H:i:s', strtotime($permit['issued_date'])); ?></td>
                    <td>
                        <button class="btn-print" onclick="window.print()">PRINT</button>
                        <button class="btn-edit">EDIT</button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php $conn->close(); ?>
