<?php
/**
 * DELETE STUDENT PAGE - delete.php
 * This page allows administrators to delete a student from the system
 * Shows confirmation dialog before actually deleting
 */

// Include database connection
require_once 'db.php';

// Security check: Make sure user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit();
}

// Initialize variables
$message = '';   // Success message
$error = '';     // Error message
$student = null; // Will hold student data

// Get student NIC from URL (example: delete.php?nic=123456789V)
$nic = $_GET['nic'] ?? '';

// If no NIC provided, redirect back to search page
if (empty($nic)) {
    header("Location: search.php");
    exit();
}

// Fetch student data to show confirmation details
try {
    $stmt = $pdo->prepare("SELECT * FROM student WHERE nic = ?");
    $stmt->execute([$nic]);
    $student = $stmt->fetch();
    
    // If student not found
    if (!$student) {
        $error = "❌ Student not found!";
    }
} catch (PDOException $e) {
    $error = "❌ Database Error: " . $e->getMessage();
}

// Handle deletion confirmation (when user clicks "Yes, Delete" button)
if ($_POST && isset($_POST['confirm_delete']) && $student) {
    try {
        // Delete student from database
        $deleteStmt = $pdo->prepare("DELETE FROM student WHERE nic = ?");
        $deleteStmt->execute([$nic]);
        
        // Redirect to search page with success message
        header("Location: search.php?deleted=1");
        exit();
        
    } catch (PDOException $e) {
        $error = "❌ Error deleting student: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Student - Beez Campus</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="admin-container">
        <nav class="sidebar">
            <div class="logo">
                <h2>🎓 Beez Campus</h2>
            </div>
            <ul class="nav-links">
                <li><a href="dashboard.php">📊 Dashboard</a></li>
                <li><a href="register.php">➕ Add Student</a></li>
                <li><a href="search.php">🔍 View Students</a></li>
                <li><a href="logout.php">🚪 Logout</a></li>
            </ul>
            <div class="user-info">
                <p>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</p>
            </div>
        </nav>

        <main class="main-content">
            <header class="page-header">
                <h1>Delete Student</h1>
                <p>Confirm student deletion</p>
            </header>

            <?php if ($error): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
                <div class="action-buttons">
                    <a href="search.php" class="btn-secondary">Back to Student List</a>
                </div>
            <?php elseif ($student): ?>
                <div class="delete-confirmation">
                    <div class="warning-box">
                        <h2>⚠️ Warning</h2>
                        <p>You are about to permanently delete the following student record:</p>
                    </div>

                    <div class="student-details">
                        <table class="details-table">
                            <tr>
                                <th>NIC:</th>
                                <td><?php echo htmlspecialchars($student['nic']); ?></td>
                            </tr>
                            <tr>
                                <th>Name:</th>
                                <td><?php echo htmlspecialchars($student['name']); ?></td>
                            </tr>
                            <tr>
                                <th>Gender:</th>
                                <td><?php echo htmlspecialchars($student['gender']); ?></td>
                            </tr>
                            <tr>
                                <th>Contact:</th>
                                <td><?php echo htmlspecialchars($student['contact']); ?></td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td><?php echo htmlspecialchars($student['email']); ?></td>
                            </tr>
                            <tr>
                                <th>Course:</th>
                                <td><?php echo htmlspecialchars($student['course']); ?></td>
                            </tr>
                            <tr>
                                <th>Address:</th>
                                <td><?php echo htmlspecialchars($student['address']); ?></td>
                            </tr>
                        </table>
                    </div>

                    <div class="danger-notice">
                        <p><strong>This action cannot be undone!</strong></p>
                        <p>All student information will be permanently removed from the system.</p>
                    </div>

                    <form method="POST" action="" class="delete-form">
                        <div class="form-actions">
                            <button type="submit" name="confirm_delete" value="1" class="btn-danger" onclick="return confirm('Are you absolutely sure you want to delete this student? This action cannot be undone!')">
                                🗑️ Yes, Delete Student
                            </button>
                            <a href="search.php" class="btn-secondary">Cancel</a>
                            <a href="update.php?nic=<?php echo urlencode($student['nic']); ?>" class="btn-primary">Edit Instead</a>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
