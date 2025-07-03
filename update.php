<?php
/**
 * STUDENT UPDATE PAGE - update.php
 * This page allows administrators to modify existing student information
 * Student NIC cannot be changed as it's the unique identifier
 */

// Include database connection
require_once 'db.php';

// Security check: Make sure user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit();
}

// Initialize variables
$message = '';  // Success message
$error = '';    // Error message
$student = null; // Will hold student data

// Get student NIC from URL (example: update.php?nic=123456789V)
$nic = $_GET['nic'] ?? '';

// If no NIC provided, redirect back to search page
if (empty($nic)) {
    header("Location: search.php");
    exit();
}

// Fetch student data from database
try {
    // Find student by NIC
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

// Handle form submission (when user clicks "Update Student" button)
if ($_POST && $student) {
    // Get updated information from form
    $name = trim($_POST['name'] ?? '');
    $gender = $_POST['gender'] ?? '';
    $address = trim($_POST['address'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $course = trim($_POST['course'] ?? '');
    
    // Validation: Check if all fields are filled
    if (empty($name) || empty($gender) || empty($address) || empty($contact) || empty($email) || empty($course)) {
        $error = "⚠️ All fields are required!";
    } else {
        try {
            // Update student information in database
            $updateStmt = $pdo->prepare("UPDATE student SET name = ?, gender = ?, address = ?, contact = ?, email = ?, course = ? WHERE nic = ?");
            $updateStmt->execute([$name, $gender, $address, $contact, $email, $course, $nic]);
            
            $message = "✅ Student updated successfully!";
            
            // Refresh student data to show updated information
            $refreshStmt = $pdo->prepare("SELECT * FROM student WHERE nic = ?");
            $refreshStmt->execute([$nic]);
            $student = $refreshStmt->fetch();
            
        } catch (PDOException $e) {
            $error = "❌ Update Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Student - Beez Campus</title>
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
                <h1>Update Student</h1>
                <p>Modify student information</p>
            </header>

            <?php if ($message): ?>
                <div class="success-message"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if ($student): ?>
                <div class="form-container">
                    <form method="POST" action="" class="student-form">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="nic">NIC Number:</label>
                                <input type="text" id="nic" name="nic" value="<?php echo htmlspecialchars($student['nic']); ?>" readonly class="readonly-field">
                                <small>NIC cannot be changed</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="name">Full Name:</label>
                                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($student['name']); ?>" required placeholder="e.g., John Doe">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="gender">Gender:</label>
                                <select id="gender" name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male" <?php echo ($student['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
                                    <option value="Female" <?php echo ($student['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
                                    <option value="Other" <?php echo ($student['gender'] === 'Other') ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="contact">Contact Number:</label>
                                <input type="tel" id="contact" name="contact" value="<?php echo htmlspecialchars($student['contact']); ?>" required placeholder="e.g., +94712345678">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="address">Address:</label>
                            <textarea id="address" name="address" required placeholder="Enter full address"><?php echo htmlspecialchars($student['address']); ?></textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" required placeholder="e.g., john@example.com">
                            </div>
                            
                            <div class="form-group">
                                <label for="course">Course:</label>
                                <select id="course" name="course" required>
                                    <option value="">Select Course</option>
                                    <option value="Computer Science" <?php echo ($student['course'] === 'Computer Science') ? 'selected' : ''; ?>>Computer Science</option>
                                    <option value="Information Technology" <?php echo ($student['course'] === 'Information Technology') ? 'selected' : ''; ?>>Information Technology</option>
                                    <option value="Software Engineering" <?php echo ($student['course'] === 'Software Engineering') ? 'selected' : ''; ?>>Software Engineering</option>
                                    <option value="Business Management" <?php echo ($student['course'] === 'Business Management') ? 'selected' : ''; ?>>Business Management</option>
                                    <option value="Accounting" <?php echo ($student['course'] === 'Accounting') ? 'selected' : ''; ?>>Accounting</option>
                                    <option value="Marketing" <?php echo ($student['course'] === 'Marketing') ? 'selected' : ''; ?>>Marketing</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-primary">Update Student</button>
                            <a href="search.php" class="btn-secondary">Cancel</a>
                            <a href="delete.php?nic=<?php echo urlencode($student['nic']); ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this student?')">Delete Student</a>
                        </div>
                    </form>
                </div>
            <?php else: ?>
                <div class="no-results">
                    <h3>Student Not Found</h3>
                    <p><a href="search.php">Return to student list</a></p>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>