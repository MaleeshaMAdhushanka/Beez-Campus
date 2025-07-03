<?php
/**
 * SEARCH STUDENTS PAGE - search.php
 * This page allows administrators to view and search all students
 * Students can be searched by NIC, name, course, or contact number
 */

// Include database connection
require_once 'db.php';

// Security check: Make sure user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit();
}

// Initialize variables
$students = [];        // Array to hold student data
$searchTerm = '';     // Search keyword entered by user
$totalStudents = 0;   // Count of students found
$message = '';        // Success message
$error = '';         // Error message

// Check if student was just deleted (coming from delete.php)
if (isset($_GET['deleted']) && $_GET['deleted'] == '1') {
    $message = "✅ Student deleted successfully!";
}

// Get search term from URL (if user used search box)
$searchTerm = trim($_GET['search'] ?? '');

try {
    if (!empty($searchTerm)) {
        // User entered a search term - search in multiple fields
        // LIKE operator allows partial matching
        // % symbols mean "any characters before/after"
        $searchStmt = $pdo->prepare("SELECT * FROM student WHERE nic LIKE ? OR name LIKE ? OR course LIKE ? OR contact LIKE ? ORDER BY name");
        $searchPattern = "%$searchTerm%";
        $searchStmt->execute([$searchPattern, $searchPattern, $searchPattern, $searchPattern]);
        $students = $searchStmt->fetchAll();
    } else {
        // No search term - show all students
        $allStmt = $pdo->query("SELECT * FROM student ORDER BY name");
        $students = $allStmt->fetchAll();
    }
    
    // Count how many students were found
    $totalStudents = count($students);
    
} catch (PDOException $e) {
    $error = "❌ Search Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Students - Beez Campus</title>
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
                <li><a href="search.php" class="active">🔍 View Students</a></li>
                <li><a href="logout.php">🚪 Logout</a></li>
            </ul>
            <div class="user-info">
                <p>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</p>
            </div>
        </nav>

        <main class="main-content">
            <header class="page-header">
                <h1>Student Records</h1>
                <p>Search and manage student information</p>
            </header>

            <?php if ($message): ?>
                <div class="success-message"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>

            <div class="search-section">
                <form method="GET" action="" class="search-form">
                    <div class="search-group">
                        <input type="text" name="search" placeholder="Search by NIC, Name, Course, or Contact..." value="<?php echo htmlspecialchars($searchTerm); ?>" class="search-input">
                        <button type="submit" class="btn-search">🔍 Search</button>
                        <?php if (!empty($searchTerm)): ?>
                            <a href="search.php" class="btn-clear">Clear</a>
                        <?php endif; ?>
                    </div>
                </form>
                
                <div class="results-info">
                    <p>
                        <?php if (!empty($searchTerm)): ?>
                            Found <strong><?php echo $totalStudents; ?></strong> student(s) for "<?php echo htmlspecialchars($searchTerm); ?>"
                        <?php else: ?>
                            Showing all <strong><?php echo $totalStudents; ?></strong> student(s)
                        <?php endif; ?>
                    </p>
                </div>
            </div>

            <div class="table-container">
                <?php if (empty($students)): ?>
                    <div class="no-results">
                        <h3>No students found</h3>
                        <p>
                            <?php if (!empty($searchTerm)): ?>
                                Try adjusting your search criteria or <a href="search.php">view all students</a>.
                            <?php else: ?>
                                <a href="register.php">Add the first student</a> to get started.
                            <?php endif; ?>
                        </p>
                    </div>
                <?php else: ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>NIC</th>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Contact</th>
                                <th>Email</th>
                                <th>Course</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $student): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($student['nic']); ?></td>
                                    <td><?php echo htmlspecialchars($student['name']); ?></td>
                                    <td><?php echo htmlspecialchars($student['gender']); ?></td>
                                    <td><?php echo htmlspecialchars($student['contact']); ?></td>
                                    <td><?php echo htmlspecialchars($student['email']); ?></td>
                                    <td><?php echo htmlspecialchars($student['course']); ?></td>
                                    <td class="actions">
                                        <a href="update.php?nic=<?php echo urlencode($student['nic']); ?>" class="btn-edit" title="Edit Student">✏️</a>
                                        <a href="delete.php?nic=<?php echo urlencode($student['nic']); ?>" class="btn-delete" title="Delete Student" onclick="return confirm('Are you sure you want to delete <?php echo htmlspecialchars($student['name']); ?>?')">🗑️</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

            <div class="action-buttons">
                <a href="register.php" class="btn-primary">Add New Student</a>
                <a href="dashboard.php" class="btn-secondary">Back to Dashboard</a>
            </div>
        </main>
    </div>
</body>
</html>