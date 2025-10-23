<?php
require_once 'config.php';
requireLogin();

$conn = getConnection();
$message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'generate') {
    $stmt = $conn->prepare("INSERT INTO certificates (resident_id, certificate_type, purpose) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $_POST['resident_id'], $_POST['certificate_type'], $_POST['purpose']);
    $stmt->execute();
    $message = 'Certificate generated successfully';
}

// Get all residents for dropdown
$residents = $conn->query("SELECT id, CONCAT(first_name, ' ', last_name) as fullname FROM residents ORDER BY first_name");

// Get all certificates with resident names
$certificates = $conn->query("
    SELECT c.*, CONCAT(r.first_name, ' ', r.last_name) as resident_name 
    FROM certificates c 
    JOIN residents r ON c.resident_id = r.id 
    ORDER BY c.id DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificates - Barangay Information System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container">
        <h2>CERTIFICATE GENERATION</h2>
        
        <?php if ($message): ?>
            <div class="success-message"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <div class="form-card">
            <form method="POST" action="">
                <input type="hidden" name="action" value="generate">
                
                <div class="form-row">
                    <select name="resident_id" required>
                        <option value="">SELECT RESIDENT</option>
                        <?php 
                        $residents->data_seek(0);
                        while ($resident = $residents->fetch_assoc()): 
                        ?>
                            <option value="<?php echo $resident['id']; ?>"><?php echo htmlspecialchars($resident['fullname']); ?></option>
                        <?php endwhile; ?>
                    </select>
                    
                    <select name="certificate_type" required>
                        <option value="">SELECT CERTIFICATE TYPE</option>
                        <option value="Barangay Indigency">Barangay Indigency</option>
                        <option value="Barangay Clearance">Barangay Clearance</option>
                        <option value="Certificate of Residency">Certificate of Residency</option>
                        <option value="Business Permit">Business Permit</option>
                    </select>
                    
                    <input type="text" name="purpose" placeholder="PURPOSE" required>
                </div>
                
                <button type="submit" class="btn-primary">GENERATE PERMIT</button>
            </form>
        </div>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>RESIDENT</th>
                    <th>TYPE</th>
                    <th>PURPOSE</th>
                    <th>ISSUED DATE</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($cert = $certificates->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $cert['id']; ?></td>
                    <td><?php echo htmlspecialchars($cert['resident_name']); ?></td>
                    <td><?php echo htmlspecialchars($cert['certificate_type']); ?></td>
                    <td><?php echo htmlspecialchars($cert['purpose']); ?></td>
                    <td><?php echo date('Y-m-d H:i:s', strtotime($cert['issued_date'])); ?></td>
                    <td>
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
