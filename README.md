# 🎓 Beez Campus - Student Management System

A **beginner-friendly** PHP-based Student Management System designed for learning web development concepts.

## 📋 What This Project Does

This system helps administrators manage student information with the following features:
- **👤 Admin Login**: Secure login system for administrators
- **➕ Add Students**: Register new students with their details
- **👀 View Students**: Browse and search through all students
- **✏️ Update Students**: Modify existing student information
- **🗑️ Delete Students**: Remove students from the system

## 🏗️ Project Structure

```
Beez-Campus/
├── 📄 index.php          # Login page (main entry point)
├── 📄 dashboard.php      # Admin dashboard with overview
├── 📄 register.php       # Add new students
├── 📄 search.php         # View and search students
├── 📄 update.php         # Edit student information
├── 📄 delete.php         # Delete students
├── 📄 logout.php         # Logout functionality
├── 📄 db.php             # Database connection
├── 📄 campus.sql         # Database structure and sample data
└── 📁 assets/
    ├── 🎨 style.css      # Styling for the website
    └── 📁 images/        # Image files
```

## 🛠️ Setup Instructions for WAMP Server

### Step 1: Install WAMP Server
1. Download WAMP Server from [wampserver.com](http://www.wampserver.com)
2. Install and start WAMP (icon should be green in system tray)

### Step 2: Copy Project Files
```powershell
# Copy project to WAMP directory
Copy-Item -Path "G:\Beez-Campus" -Destination "C:\wamp64\www\" -Recurse
```

### Step 3: Set Up Database
1. Open browser and go to: `http://localhost/phpmyadmin`
2. Click "Import" tab
3. Choose the `campus.sql` file
4. Click "Go" to create database and tables

### Step 4: Configure Database Password (if needed)
- Default WAMP has no MySQL password for root user
- If you want to use password "Ijse@123", set it in phpMyAdmin:
  - Go to "User accounts" → root → "Change password"

### Step 5: Access Your Application
- Open browser
- Go to: `http://localhost/Beez-Campus/`
- Login with:
  - **Username**: `admin`
  - **Password**: `1234`

## 💾 Database Structure

### Admin Table
- Stores administrator login credentials
- Default admin: username=`admin`, password=`1234`

### Student Table
- `nic`: National ID Card number (Primary Key)
- `name`: Student's full name
- `gender`: Male/Female/Other
- `address`: Home address
- `contact`: Phone number
- `email`: Email address
- `course`: Academic course

## 🔐 Security Features

### 1. **Session Management**
```php
// Check if user is logged in before accessing pages
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit();
}
```

### 2. **SQL Injection Prevention**
```php
// Using prepared statements
$stmt = $pdo->prepare("SELECT * FROM student WHERE nic = ?");
$stmt->execute([$nic]);
```

### 3. **Input Sanitization**
```php
// Clean user input
$name = trim($_POST['name'] ?? '');
echo htmlspecialchars($student['name']); // Prevent XSS
```

## 📚 Learning Objectives

This project teaches beginners:

### PHP Concepts:
- ✅ **Variables and Arrays**: `$message`, `$_POST`, `$_SESSION`
- ✅ **Conditional Statements**: `if/else`, `isset()`
- ✅ **Database Operations**: SELECT, INSERT, UPDATE, DELETE
- ✅ **Error Handling**: `try/catch` blocks
- ✅ **Sessions**: User login state management
- ✅ **Form Processing**: Handling user input
- ✅ **Security**: Prepared statements, input validation

### Web Development:
- ✅ **HTML Forms**: Creating user interfaces
- ✅ **CSS Styling**: Making attractive layouts
- ✅ **Navigation**: Moving between pages
- ✅ **CRUD Operations**: Create, Read, Update, Delete

### Database Design:
- ✅ **Table Relationships**: Primary keys, data types
- ✅ **Data Validation**: Required fields, unique constraints
- ✅ **Search Functionality**: LIKE queries, pattern matching

## 🐛 Common Issues & Solutions

### 1. **Database Connection Failed**
```
❌ Database Connection Failed: Access denied for user 'root'@'localhost'
```
**Solution**: Check password in `db.php` - WAMP default is empty password

### 2. **Page Not Loading**
**Solution**: Ensure WAMP is running (green icon) and files are in `C:\wamp64\www\Beez-Campus\`

### 3. **Session Issues**
**Solution**: Make sure `session_start()` is called in `db.php`



