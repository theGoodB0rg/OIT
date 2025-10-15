<?php
session_start();

// Check if admin is logged in
if (isset($_SESSION['admin_id'])) {
    // Clear admin session variables
    unset($_SESSION['admin_id']);
    unset($_SESSION['admin_username']);
    unset($_SESSION['admin_email']);
    unset($_SESSION['admin_login_time']);
    
    // Clear remember me cookie if it exists
    if (isset($_COOKIE['admin_remember'])) {
        setcookie('admin_remember', '', time() - 3600, '/');
    }
    
    // Optional: Log logout activity
    // You can implement logout logging here if needed
    
    $_SESSION['admin_success'] = "You have been successfully logged out.";
}

// Destroy session and redirect
session_destroy();
header("Location: login/index.php");
exit();
?>