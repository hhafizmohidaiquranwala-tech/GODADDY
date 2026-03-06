-- Create Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create Domains Table
CREATE TABLE IF NOT EXISTS domains (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    domain_name VARCHAR(255) NOT NULL,
    status VARCHAR(50) DEFAULT 'Active',
    registration_date DATE NOT NULL,
    expiry_date DATE NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
