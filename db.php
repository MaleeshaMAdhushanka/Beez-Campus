<?php
/**
 * Database Connection File
 * This file handles the connection to MySQL database
 * and starts the session for user login management
 */

// Database connection settings
$servername = "localhost";    // Server name (usually localhost for WAMP)
$username = "root";          // MySQL username (default: root)
$password = "Ijse@123";      // MySQL password (set this in WAMP)
$dbname = "beez";           // Name of our database

try {
    // Create a new PDO connection to MySQL database
    // PDO is a secure way to connect to databases in PHP
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    
    // Set error mode to throw exceptions when errors occur
    // This helps us catch and handle database errors properly
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Optional: Set charset to UTF-8 for proper character encoding
    $pdo->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES utf8");
    
} catch(PDOException $e) {
    // If connection fails, show error message and stop execution
    die("❌ Database Connection Failed: " . $e->getMessage());
}

// Start PHP session for user login management
// Sessions allow us to store user information across different pages
session_start();

// Optional: Display success message (remove in production)
// echo "✅ Database connected successfully!";
?>