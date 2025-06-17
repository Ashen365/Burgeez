<?php
/**
 * Admin Authentication Check
 * 
 * Verifies that the user is logged in as an administrator.
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in as admin
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_name'])) {
    // Not logged in, redirect to login page
    header('Location: ../admin/admin_login.php');
    exit;
}

// Optional: Check session timeout (2 hours)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 7200)) {
    // Session expired
    session_unset();
    session_destroy();
    header('Location: ../admin/admin_login.php?session=expired');
    exit;
}

// Update last activity time
$_SESSION['last_activity'] = time();
?>