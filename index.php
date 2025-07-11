<?php
/**
 * LOGIN PAGE - index.php
 * This is the main login page for administrators
 * Only authorized users can access the student management system
 */

// Include database connection
require_once 'db.php';

// Check if user is already logged in
// If yes, redirect them to dashboard (no need to login again)
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: dashboard.php");
    exit();
}

// Initialize variables for error messages and logout notification
$error = '';
$logoutMessage = '';

// Check if user just logged out
// URL parameter ?logout=1 means user clicked logout
if (isset($_GET['logout']) && $_GET['logout'] == '1') {
    $logoutMessage = "✅ You have been logged out successfully!";
}

// Check if form was submitted (user clicked login button)
if ($_POST) {
    // Get username and password from form
    // ?? '' means "if not set, use empty string"
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Basic validation - check if fields are not empty
    if (!empty($username) && !empty($password)) {
        
        // Prepare SQL query to find admin with matching username and password
        // Using prepared statements to prevent SQL injection attacks
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE admin = ? AND 1234 = ?");
        $stmt->execute([$username, $password]);
        $admin = $stmt->fetch();
        
        // If admin found (correct credentials)
        if ($admin) {
            // Set session variables to remember user is logged in
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['admin'];
            
            // Redirect to dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            // Wrong username or password
            $error = "❌ Invalid username or password!";
        }
    } else {
        // Empty fields
        $error = "⚠️ Please fill in all fields!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beez Campus - Student Management System</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1>🎓 Beez Campus</h1>
            <h2>Student Management System</h2>
            
            <?php if ($logoutMessage): ?>
                <div class="success-message"><?php echo htmlspecialchars($logoutMessage); ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="login-btn">Login</button>
            </form>
            
            <div class="login-info">
                <p><strong>Default Login:</strong></p>
                <p>Username: admin</p>
                <p>Password: 1234</p>
            </div>
        </div>
    </div>
</body>
</html>