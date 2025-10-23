<?php
require_once 'config.php';
requireLogin();

$conn = getConnection();

// Get total population
$population_result = $conn->query("SELECT COUNT(*) as total FROM residents");
$population = $population_result->fetch_assoc()['total'];

// Get officials
$officials_result = $conn->query("SELECT * FROM officials ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Barangay Information System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container">
        <div class="welcome-banner">
            <h1>WELCOME TO THE BARANGAY INFORMATION AND MANAGEMENT SYSTEM</h1>
            <p>Manage resident and official records efficiently.</p>
        </div>
        
        <div class="dashboard-grid">
            <div class="officials-section">
                <h2>CURRENT BARANGAY OFFICIALS</h2>
                <table>
                    <thead>
                        <tr>
                            <th>FULLNAME</th>
                            <th>CHAIRMANSHIP</th>
                            <th>POSITION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($official = $officials_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($official['fullname']); ?></td>
                            <td><?php echo htmlspecialchars($official['chairmanship']); ?></td>
                            <td><?php echo htmlspecialchars($official['position']); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="population-card">
                <div class="population-icon">
                    <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
                <div class="population-count"><?php echo $population; ?></div>
                <div class="population-label">TOTAL POPULATION</div>
            </div>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>
