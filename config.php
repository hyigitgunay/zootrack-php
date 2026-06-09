<?php
// Session configuration - must be called before session_start()
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Database Configuration (Can be changed for production environment)
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'zootrack_db');

try {
    $db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    // If the database does not exist, try to connect without dbname to create it or show error
    try {
        $temp_db = new PDO("mysql:host=" . DB_HOST . ";charset=utf8mb4", DB_USER, DB_PASS);
        $temp_db->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        
        $db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
        
        // Auto-create tables if they don't exist
        $db->exec("CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB;");
        
        $db->exec("CREATE TABLE IF NOT EXISTS animals (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            name VARCHAR(100) NOT NULL,
            species VARCHAR(100) NOT NULL,
            age INT NOT NULL,
            gender ENUM('Male', 'Female', 'Unknown') DEFAULT 'Unknown',
            cage_number VARCHAR(50) NOT NULL,
            health_status ENUM('Healthy', 'Sick', 'Under Treatment', 'Quarantine') DEFAULT 'Healthy',
            feeding_schedule VARCHAR(255) NOT NULL,
            notes TEXT,
            image_url VARCHAR(255) DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB;");
        
    } catch (PDOException $ex) {
        die("Veritabanı bağlantı hatası: " . $ex->getMessage());
    }
}

// Helper functions for authentication and sanitization
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function checkLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit;
    }
}

function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}
?>
