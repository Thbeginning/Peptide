-- === database.sql ===
-- Import this file into phpMyAdmin to set up the environment.

CREATE DATABASE IF NOT EXISTS qingli_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE qingli_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('customer', 'admin') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS cart_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id VARCHAR(100) NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    quantity INT DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS quote_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    shipping_city VARCHAR(100) NOT NULL,
    shipping_country VARCHAR(100) NOT NULL,
    contact_method ENUM('email', 'whatsapp') DEFAULT 'email',
    contact_detail VARCHAR(255) NOT NULL,
    message TEXT,
    research_only_confirmed BOOLEAN DEFAULT TRUE,
    status ENUM('Pending', 'Under Review', 'Paid', 'Shipped', 'Cancelled') DEFAULT 'Pending',
    internal_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS quote_request_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quote_id INT NOT NULL,
    product_id VARCHAR(100) NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    quantity INT DEFAULT 1,
    FOREIGN KEY (quote_id) REFERENCES quote_requests(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS site_reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    display_name VARCHAR(255) NOT NULL,
    overall_rating INT NOT NULL CHECK (overall_rating >= 1 AND overall_rating <= 5),
    communication_professional BOOLEAN DEFAULT FALSE,
    shipping_discreet_timely BOOLEAN DEFAULT FALSE,
    product_lab_standards BOOLEAN DEFAULT FALSE,
    review_text TEXT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert a default admin for testing purposes
-- Password is 'admin123'
INSERT INTO users (name, email, password_hash, role) VALUES (
    'Admin User',
    'admin@qinglipeptide.com',
    '$2y$10$w1v/R99Z7Z9Z7Z9Z7Z9Z7e.a1v/R99Z7Z9Z7Z9Z7Z9Z7Z9Z7Z9Z7.', -- dummy hash, you can recreate by registering with this email
    'admin'
) ON DUPLICATE KEY UPDATE id=id;
