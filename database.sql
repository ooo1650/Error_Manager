CREATE DATABASE IF NOT EXISTS error_db;
USE error_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS errors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    error_code VARCHAR(50),
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    solution TEXT,
    language VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert dummy users (admin / user1)
INSERT INTO users (username, password, role) VALUES 
('admin', '$2y$12$zsGSxiM3euYASFyp9lBYFuMbevOtptRJ7YYF6tMKFIPW8jnSr0gIG', 'admin'),
('user1', '$2y$12$KCVV9us8QzHWQ.KlRXutxu/5a6cdAEbBLi6YO39YYH37ELj/mERZW', 'user')
ON DUPLICATE KEY UPDATE id=id;

-- Insert 5 dummy errors
INSERT INTO errors (user_id, title, error_code, language, description, solution) VALUES 
(1, 'Database connection failed', 'ECONNREFUSED', 'PHP', 'Could not connect to MySQL server at localhost.', 'Check if MySQL service is running and credentials in db.php are correct.'),
(2, 'Undefined index "user_id"', 'PHP Notice', 'PHP', 'Attempting to access session variable before session_start().', 'Added session_start() at the top of the file.'),
(1, '404 Page Not Found', 'HTTP 404', 'Apache', 'The requested URL /dashboard was not found on this server.', 'Check if .htaccess is missing or RewriteEngine is off.'),
(2, 'Cannot read property "style" of null', 'TypeError', 'JavaScript', 'Trying to manipulate DOM element before it is loaded.', 'Wrapped the code inside document.addEventListener("DOMContentLoaded", ...).'),
(1, 'Syntax error: unexpected token', 'SyntaxError', 'JavaScript', 'Missing closing brace in a function definition.', 'Added the missing } at line 45.');

