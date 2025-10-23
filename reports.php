<?php
require_once 'config.php';
requireLogin();

$conn = getConnection();
$search_results = [];
$search_query = '';

// Handle search
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_query = $_GET['search'];
    $search_term = "%$search_query%";
    
    // Search in residents
    $stmt = $conn->prepare("SELECT 'Resident' as type, CONCAT(first_name, ' ', last_name) as name, contact as info, created_at as date FROM residents WHERE first_name LIKE ? OR last_name LIKE ? OR contact LIKE ?");
    $stmt->bind_param("sss", $search_term, $search_term, $search_term);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $search_results[] = $row;
    }
    
    // Search in certificates
    $stmt = $conn->prepare("SELECT 'Certificate' as type, certificate_type as name, purpose as info, issued_date as date FROM certificates WHERE certificate_type LIKE ? OR purpose LIKE ?");
    $stmt->bind_param("ss", $search_term, $search_term);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $search_results[] = $row;
    }
    
    // Search in blotter
    $stmt = $conn->prepare("SELECT 'Blotter' as type, incident_type as name, CONCAT(complainant, ' vs ', respondent) as info, created_at as date FROM blotter WHERE incident_type LIKE ? OR complainant LIKE ? OR respondent LIKE ?");
    $stmt->bind_param("sss", $search_term, $search_term, $search_term);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $search_results[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Barangay Information System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container">
        <h2>SEARCH REPORTS</h2>
        
        <div class="search-card">
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Search Resident, Certificate, or Blotter..." value="<?php echo htmlspecialchars($search_query); ?>" required>
                <button type="submit" class="btn-primary">SEARCH</button>
            </form>
        </div>
        
        <?php if (!empty($search_results)): ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>TYPE</th>
                        <th>NAME/TITLE</th>
                        <th>INFORMATION</th>
                        <th>DATE</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($search_results as $result): ?>
                    <tr>
                        <td><?php echo $result['type']; ?></td>
                        <td><?php echo htmlspecialchars($result['name']); ?></td>
                        <td><?php echo htmlspecialchars($result['info']); ?></td>
                        <td><?php echo date('Y-m-d H:i:s', strtotime($result['date'])); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif ($search_query): ?>
            <p class="no-results">No results found for "<?php echo htmlspecialchars($search_query); ?>"</p>
        <?php endif; ?>
    </div>
</body>
</html>
<?php $conn->close(); ?>
