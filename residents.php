<?php
require_once 'config.php';
requireLogin();

$conn = getConnection();
$message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            $stmt = $conn->prepare("INSERT INTO residents (first_name, last_name, age, gender, street_address, contact) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssisss", $_POST['first_name'], $_POST['last_name'], $_POST['age'], $_POST['gender'], $_POST['street_address'], $_POST['contact']);
            $stmt->execute();
            $message = 'Resident added successfully';
        } elseif ($_POST['action'] === 'delete') {
            $stmt = $conn->prepare("DELETE FROM residents WHERE id = ?");
            $stmt->bind_param("i", $_POST['id']);
            $stmt->execute();
            $message = 'Resident deleted successfully';
        }
    }
}

// Get all residents
$residents = $conn->query("SELECT * FROM residents ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Residents - Barangay Information System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container">
        <h2>RESIDENTS LIST</h2>
        
        <?php if ($message): ?>
            <div class="success-message"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <div class="form-card">
            <form method="POST" action="">
                <input type="hidden" name="action" value="add">
                
                <div class="form-row">
                    <input type="text" name="first_name" placeholder="FIRST NAME" required>
                    <select name="gender" required>
                        <option value="">GENDER</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                
                <div class="form-row">
                    <input type="text" name="last_name" placeholder="LAST NAME" required>
                    <input type="text" name="street_address" placeholder="STREET ADDRESS" required>
                </div>
                
                <div class="form-row">
                    <input type="number" name="age" placeholder="AGE" required>
                    <input type="text" name="contact" placeholder="CONTACT" required>
                </div>
                
                <button type="submit" class="btn-primary">ADD RESIDENT</button>
            </form>
        </div>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>FULL NAME</th>
                    <th>AGE</th>
                    <th>GENDER</th>
                    <th>ADDRESS</th>
                    <th>CONTACT</th>
                    <th>DATE</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($resident = $residents->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $resident['id']; ?></td>
                    <td><?php echo htmlspecialchars($resident['first_name'] . ' ' . $resident['last_name']); ?></td>
                    <td><?php echo $resident['age']; ?></td>
                    <td><?php echo $resident['gender']; ?></td>
                    <td><?php echo htmlspecialchars($resident['street_address']); ?></td>
                    <td><?php echo htmlspecialchars($resident['contact']); ?></td>
                    <td><?php echo date('Y-m-d H:i:s', strtotime($resident['created_at'])); ?></td>
                    <td>
                        <button class="btn-edit">EDIT</button>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $resident['id']; ?>">
                            <button type="submit" class="btn-delete" onclick="return confirm('Delete this resident?')">DELETE</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php $conn->close(); ?>
