<?php
/**
 * LOGOUT PAGE - logout.php
 * This page handles user logout and clears session data
 * Then redirects back to login page
 */

// Include database connection (starts session)
require_once 'db.php';

// Clear all session variables
// This "forgets" that the user was logged in
$_SESSION = array();

// Destroy the session cookie if it exists
// This removes the session cookie from user's browser
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session completely
// This removes the session file from server
session_destroy();

// Redirect to login page with logout success message
header("Location: index.php?logout=1");
exit();
?>