<header class="main-header">
    <div class="header-left">
        <div class="logo">LOGO</div>
        <h1>INFORMATION BASED SYSTEM</h1>
    </div>
    
    <nav class="main-nav">
        <a href="dashboard.php" <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'class="active"' : ''; ?>>DASHBOARD</a>
        <a href="residents.php" <?php echo basename($_SERVER['PHP_SELF']) == 'residents.php' ? 'class="active"' : ''; ?>>RESIDENTS</a>
        <a href="permits.php" <?php echo basename($_SERVER['PHP_SELF']) == 'permits.php' ? 'class="active"' : ''; ?>>PERMITS</a>
        <a href="certificates.php" <?php echo basename($_SERVER['PHP_SELF']) == 'certificates.php' ? 'class="active"' : ''; ?>>CERTIFICATES</a>
        <a href="blotter.php" <?php echo basename($_SERVER['PHP_SELF']) == 'blotter.php' ? 'class="active"' : ''; ?>>BLOTTER</a>
        <a href="reports.php" <?php echo basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'class="active"' : ''; ?>>REPORTS</a>
    </nav>
    
    <a href="logout.php" class="btn-logout">LOG OUT</a>
</header>
