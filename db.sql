CREATE DATABASE IF NOT EXISTS zootrack_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE zootrack_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS animals (
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
) ENGINE=InnoDB;
