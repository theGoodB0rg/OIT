<?php
/**
 * Database Configuration for OIT - Online Educational Tool
 * 
 * This file contains database connection settings and helper functions
 * for the OIT educational platform.
 */

// Database configuration constants
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'oit_db');

/**
 * Get database connection
 * 
 * @return mysqli|false Returns mysqli connection object or false on failure
 */
function getDBConnection() {
    $connection = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    
    if (!$connection) {
        error_log("Database connection failed: " . mysqli_connect_error());
        return false;
    }
    
    // Set charset to UTF-8 for proper character handling
    mysqli_set_charset($connection, "utf8mb4");
    
    return $connection;
}

/**
 * Execute a prepared statement query
 * 
 * @param string $query The SQL query with placeholders
 * @param string $types The types of parameters (s=string, i=integer, d=double, b=blob)
 * @param array $params Array of parameters to bind
 * @return mysqli_result|bool Returns result set or boolean
 */
function executeQuery($query, $types = '', $params = []) {
    $connection = getDBConnection();
    if (!$connection) {
        return false;
    }
    
    $stmt = mysqli_prepare($connection, $query);
    if (!$stmt) {
        error_log("Prepare failed: " . mysqli_error($connection));
        mysqli_close($connection);
        return false;
    }
    
    if (!empty($types) && !empty($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    
    $result = mysqli_stmt_execute($stmt);
    
    if (!$result) {
        error_log("Execute failed: " . mysqli_stmt_error($stmt));
        mysqli_stmt_close($stmt);
        mysqli_close($connection);
        return false;
    }
    
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($connection);
    
    return $result;
}

/**
 * Sanitize user input
 * 
 * @param string $input The input to sanitize
 * @return string Sanitized input
 */
function sanitizeInput($input) {
    $connection = getDBConnection();
    if (!$connection) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
    
    $sanitized = mysqli_real_escape_string($connection, trim($input));
    mysqli_close($connection);
    
    return htmlspecialchars($sanitized, ENT_QUOTES, 'UTF-8');
}

/**
 * Check if database exists and create it if it doesn't
 */
function initializeDatabase() {
    $connection = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD);
    
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    // Check if database exists
    $dbExists = mysqli_select_db($connection, DB_NAME);
    
    if (!$dbExists) {
        // Create database
        $createDB = "CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
        if (mysqli_query($connection, $createDB)) {
            echo "Database created successfully\n";
        } else {
            die("Error creating database: " . mysqli_error($connection));
        }
    }
    
    mysqli_close($connection);
}

// Initialize database if needed
// initializeDatabase();
?>