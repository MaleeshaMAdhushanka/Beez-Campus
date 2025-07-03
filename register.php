<?php
/**
 * STUDENT REGISTRATION PAGE - register.php
 * This page allows administrators to add new students to the system
 */

// Include database connection
require_once 'db.php';

// Security check: Make sure user is logged in
// If not logged in, redirect to login page
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit();
}

// Initialize variables for messages and form data
$message = '';  // Success message
$error = '';    // Error message

// Check if form was submitted (user clicked "Register Student" button)
if ($_POST) {
    // Get all form data and remove extra spaces with trim()
    $nic = trim($_POST['nic'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $gender = $_POST['gender'] ?? '';
    $address = trim($_POST['address'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $course = trim($_POST['course'] ?? '');
    
    // Validation: Check if all fields are filled
    if (empty($nic) || empty($name) || empty($gender) || empty($address) || empty($contact) || empty($email) || empty($course)) {
        $error = "⚠️ All fields are required!";
    } else {
        try {
            // Check if a student with this NIC already exists
            // NIC should be unique for each student
            $checkStmt = $pdo->prepare("SELECT nic FROM student WHERE nic = ?");
            $checkStmt->execute([$nic]);
            
            if ($checkStmt->fetch()) {
                // Student with this NIC already exists
                $error = "❌ Student with this NIC already exists!";
            } else {
                // Insert new student into database
                $insertStmt = $pdo->prepare("INSERT INTO student (nic, name, gender, address, contact, email, course) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $insertStmt->execute([$nic, $name, $gender, $address, $contact, $email, $course]);
                
                // Success! Show message and clear form
                $message = "✅ Student registered successfully!";
                // Clear all form variables
                $nic = $name = $gender = $address = $contact = $email = $course = '';
            }
        } catch (PDOException $e) {
            // Database error occurred
            $error = "❌ Database Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Student - Beez Campus</title>
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
                <li><a href="register.php" class="active">➕ Add Student</a></li>
                <li><a href="search.php">🔍 View Students</a></li>
                <li><a href="logout.php">🚪 Logout</a></li>
            </ul>
            <div class="user-info">
                <p>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</p>
            </div>
        </nav>

        <main class="main-content">
            <header class="page-header">
                <h1>Register New Student</h1>
                <p>Add a new student to the system</p>
            </header>

            <?php if ($message): ?>
                <div class="success-message"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <div class="form-container">
                <form method="POST" action="" class="student-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nic">NIC Number:</label>
                            <input type="text" id="nic" name="nic" value="<?php echo htmlspecialchars($nic ?? ''); ?>" required placeholder="e.g., 200012345678">
                        </div>
                        
                        <div class="form-group">
                            <label for="name">Full Name:</label>
                            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name ?? ''); ?>" required placeholder="e.g., John Doe">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="gender">Gender:</label>
                            <select id="gender" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="Male" <?php echo (isset($gender) && $gender === 'Male') ? 'selected' : ''; ?>>Male</option>
                                <option value="Female" <?php echo (isset($gender) && $gender === 'Female') ? 'selected' : ''; ?>>Female</option>
                                <option value="Other" <?php echo (isset($gender) && $gender === 'Other') ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="contact">Contact Number:</label>
                            <input type="tel" id="contact" name="contact" value="<?php echo htmlspecialchars($contact ?? ''); ?>" required placeholder="e.g., +94712345678">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address">Address:</label>
                        <textarea id="address" name="address" required placeholder="Enter full address"><?php echo htmlspecialchars($address ?? ''); ?></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required placeholder="e.g., john@example.com">
                        </div>
                        
                        <div class="form-group">
                            <label for="course">Course:</label>
                            <select id="course" name="course" required>
                                <option value="">Select Course</option>
                                <option value="Computer Science" <?php echo (isset($course) && $course === 'Computer Science') ? 'selected' : ''; ?>>Computer Science</option>
                                <option value="Information Technology" <?php echo (isset($course) && $course === 'Information Technology') ? 'selected' : ''; ?>>Information Technology</option>
                                <option value="Software Engineering" <?php echo (isset($course) && $course === 'Software Engineering') ? 'selected' : ''; ?>>Software Engineering</option>
                                <option value="Business Management" <?php echo (isset($course) && $course === 'Business Management') ? 'selected' : ''; ?>>Business Management</option>
                                <option value="Accounting" <?php echo (isset($course) && $course === 'Accounting') ? 'selected' : ''; ?>>Accounting</option>
                                <option value="Marketing" <?php echo (isset($course) && $course === 'Marketing') ? 'selected' : ''; ?>>Marketing</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary">Register Student</button>
                        <button type="reset" class="btn-secondary">Clear Form</button>
                        <a href="dashboard.php" class="btn-cancel">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>