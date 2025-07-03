<?php
/**
 * DASHBOARD PAGE - dashboard.php
 * This is the main page after login showing system overview
 * Displays total students count and recent registrations
 */

// Include database connection
require_once 'db.php';

// Security check: Make sure user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit();
}

// Get total number of students in the system
try {
    $countStmt = $pdo->query("SELECT COUNT(*) as total FROM student");
    $totalStudents = $countStmt->fetch()['total'];
} catch (PDOException $e) {
    $totalStudents = 0; // If error, show 0
}

// Get recent students (last 5 registered)
// ORDER BY nic DESC means newest first
try {
    $recentStmt = $pdo->query("SELECT * FROM student ORDER BY nic DESC LIMIT 5");
    $recentStudents = $recentStmt->fetchAll();
} catch (PDOException $e) {
    $recentStudents = []; // If error, show empty array
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Beez Campus</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="admin-container">
        <nav class="sidebar">
            <div class="logo">
                <h2>🎓 Beez Campus</h2>
            </div>
            <ul class="nav-links">
                <li><a href="dashboard.php" class="active">📊 Dashboard</a></li>
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
                <h1>Dashboard</h1>
                <p>Student Management System Overview</p>
            </header>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">👥</div>
                    <div class="stat-info">
                        <h3><?php echo $totalStudents; ?></h3>
                        <p>Total Students</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">📚</div>
                    <div class="stat-info">
                        <h3>Active</h3>
                        <p>System Status</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">🎯</div>
                    <div class="stat-info">
                        <h3>Private</h3>
                        <p>Secure Access</p>
                    </div>
                </div>
            </div>

            <div class="recent-section">
                <h2>Recent Students</h2>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>NIC</th>
                                <th>Name</th>
                                <th>Course</th>
                                <th>Contact</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recentStudents)): ?>
                                <tr>
                                    <td colspan="5" class="no-data">No students registered yet</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recentStudents as $student): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($student['nic']); ?></td>
                                        <td><?php echo htmlspecialchars($student['name']); ?></td>
                                        <td><?php echo htmlspecialchars($student['course']); ?></td>
                                        <td><?php echo htmlspecialchars($student['contact']); ?></td>
                                        <td class="actions">
                                            <a href="update.php?nic=<?php echo urlencode($student['nic']); ?>" class="btn-edit">Edit</a>
                                            <a href="delete.php?nic=<?php echo urlencode($student['nic']); ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this student?')">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="action-buttons">
                    <a href="register.php" class="btn-primary">Add New Student</a>
                    <a href="search.php" class="btn-secondary">View All Students</a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>