<?php
require_once 'config.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit();
}

$error = '';

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($email && $password) {
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($user = $result->fetch_assoc()) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $email;
                header('Location: dashboard.php');
                exit();
            }
        }
        
        $error = 'Invalid email or password';
        $stmt->close();
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Barangay Information System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="logo-section">
            <div class="logo-circle">LOGO</div>
        </div>
        
        <div class="form-section">
            <form method="POST" action="">
                <?php if ($error): ?>
                    <div class="error-message"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <div class="form-group">
                    <label>Name:</label>
                    <input type="email" name="email" placeholder="input email address..." required>
                </div>
                
                <div class="form-group">
                    <label>Password:</label>
                    <input type="password" name="password" placeholder="input password..." required>
                </div>
                
                <div class="forgot-password">
                    <a href="#">Forgot your password?</a>
                </div>
                
                <div class="button-group">
                    <button type="button" class="btn-secondary">Register</button>
                    <button type="submit" class="btn-primary">Log In</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
