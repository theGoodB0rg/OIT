<?php
session_start();
require_once '../../../config/database.php';

if (isset($_POST['admin_login'])) {
    $username = sanitizeInput($_POST['username']);
    $password = sanitizeInput($_POST['password']);
    $remember = isset($_POST['remember']);
    
    // Validation
    if (empty($username) || empty($password)) {
        $_SESSION['admin_error'] = "Username and password are required!";
        header("Location: ../index.php");
        exit();
    }
    
    // Hash the password for comparison (assuming stored passwords are MD5 hashed)
    $hashedPassword = md5($password);
    
    // Check admin credentials
    $result = executeQuery(
        "SELECT * FROM admin WHERE username = ? AND pass = ?",
        "ss",
        [$username, $hashedPassword]
    );
    
    if ($result && mysqli_num_rows($result) > 0) {
        $adminData = mysqli_fetch_assoc($result);
        
        // Set session variables
        $_SESSION['admin_id'] = $adminData['user_id'];
        $_SESSION['admin_username'] = $adminData['username'];
        $_SESSION['admin_email'] = $adminData['email'];
        $_SESSION['admin_login_time'] = time();
        
        // Set remember me cookie if requested
        if ($remember) {
            $cookieValue = base64_encode($adminData['user_id'] . ':' . $adminData['username']);
            setcookie('admin_remember', $cookieValue, time() + (30 * 24 * 60 * 60), '/'); // 30 days
        }
        
        // Log successful login (you can expand this to include IP, user agent, etc.)
        $loginLog = executeQuery(
            "INSERT INTO admin_login_logs (admin_id, login_time, ip_address) VALUES (?, NOW(), ?)",
            "ss",
            [$adminData['user_id'], $_SERVER['REMOTE_ADDR'] ?? 'Unknown']
        );
        
        $_SESSION['admin_success'] = "Welcome back, " . $adminData['username'] . "!";
        header("Location: ../../dashboard.php");
        exit();
        
    } else {
        // Invalid credentials
        $_SESSION['admin_error'] = "Invalid username or password!";
        
        // Log failed login attempt
        $failedLog = executeQuery(
            "INSERT INTO admin_login_attempts (username, ip_address, attempt_time, success) VALUES (?, ?, NOW(), 0)",
            "ss",
            [$username, $_SERVER['REMOTE_ADDR'] ?? 'Unknown']
        );
        
        header("Location: ../index.php");
        exit();
    }
    
} else {
    // Direct access not allowed
    header("Location: ../index.php");
    exit();
}
?>