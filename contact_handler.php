<?php
session_start();
require_once 'config/database.php';

if (isset($_POST['send_message'])) {
    $name = sanitizeInput($_POST['name']);
    $email = sanitizeInput($_POST['email']);
    $message = sanitizeInput($_POST['message']);
    
    // Validation
    if (empty($name) || empty($email) || empty($message)) {
        $_SESSION['contact_error'] = "All fields are required!";
        header("Location: index.php#contact");
        exit();
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['contact_error'] = "Please enter a valid email address!";
        header("Location: index.php#contact");
        exit();
    }
    
    // Generate unique ID for the message
    $uniqueId = rand(100000000, 999999999);
    
    // Insert into database
    $result = executeQuery(
        "INSERT INTO contact_us (unique_id, name, email, message) VALUES (?, ?, ?, ?)",
        "ssss",
        [$uniqueId, $name, $email, $message]
    );
    
    if ($result) {
        $_SESSION['contact_success'] = "Thank you! Your message has been sent successfully. We'll get back to you soon.";
    } else {
        $_SESSION['contact_error'] = "Sorry, there was an error sending your message. Please try again.";
    }
    
    header("Location: index.php#contact");
    exit();
} else {
    header("Location: index.php");
    exit();
}
?>