CREATE DATABASE IF NOT EXISTS kamenriders;
USE kamenriders;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS leads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    design VARCHAR(50) NOT NULL, -- e.g., 'Ichigo', 'Black'
    size VARCHAR(10) NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    note TEXT,
    status ENUM('pending', 'contacted', 'paid', 'cancelled') DEFAULT 'pending',
    utm_source VARCHAR(50),
    utm_medium VARCHAR(50),
    utm_campaign VARCHAR(50),
    fbclid VARCHAR(255),
    gclid VARCHAR(255),
    referrer TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
