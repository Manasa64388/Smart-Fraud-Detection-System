-- Create the fraud_detection database
CREATE DATABASE IF NOT EXISTS fraud_detection;
USE fraud_detection;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    account_no VARCHAR(20) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('customer', 'admin') DEFAULT 'customer',
    last_location VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create transactions table (Fixed: Changed INT to TINYINT for boolean)
CREATE TABLE IF NOT EXISTS transactions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    location VARCHAR(50) NOT NULL,
    is_fraud TINYINT(1) DEFAULT 0, 
    trans_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- FRAUD LOGS TABLE
CREATE TABLE IF NOT EXISTS fraud_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    transaction_id INT NOT NULL,
    fraud_reason VARCHAR(255) NOT NULL,
    detected_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (transaction_id) REFERENCES transactions(id) ON DELETE CASCADE
);

-- TRANSACTION HISTORY TABLE
CREATE TABLE IF NOT EXISTS transaction_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    transaction_id INT NOT NULL,
    user_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    location VARCHAR(100) NOT NULL,
    status VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (transaction_id) REFERENCES transactions(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ==========================================
--      DATABASE TRIGGER
-- ==========================================
-- This trigger automatically updates the user's last_location 
-- immediately after a new transaction is inserted.
DELIMITER //
CREATE TRIGGER update_location_after_transaction
AFTER INSERT ON transactions
FOR EACH ROW
BEGIN
    UPDATE users SET last_location = NEW.location WHERE id = NEW.user_id;
END;
//
DELIMITER ;

-- Insert sample admin user (password: admin123)
INSERT INTO users (name, account_no, password, role) 
VALUES ('Admin User', '999', '$2y$10$vIHl3dF5X0SHU5jPB7gUBeyVv7oL3M7HsYp1NR7hPwhfJ5oM0PNY2', 'admin');

-- Insert sample customer users
INSERT INTO users (name, account_no, password, role, last_location) 
VALUES ('John Doe', '1001', '$2y$10$vIHl3dF5X0SHU5jPB7gUBeyVv7oL3M7HsYp1NR7hPwhfJ5oM0PNY2', 'customer', 'Bengaluru');

INSERT INTO users (name, account_no, password, role, last_location) 
VALUES ('Jane Smith', '1002', '$2y$10$vIHl3dF5X0SHU5jPB7gUBeyVv7oL3M7HsYp1NR7hPwhfJ5oM0PNY2', 'customer', 'Mumbai');

-- Insert sample transactions
INSERT INTO transactions (user_id, amount, location, is_fraud) 
VALUES (2, 5000, 'Bengaluru', 0);

INSERT INTO transactions (user_id, amount, location, is_fraud) 
VALUES (2, 25000, 'Delhi', 1);

INSERT INTO transactions (user_id, amount, location, is_fraud) 
VALUES (3, 3000, 'Mumbai', 0);