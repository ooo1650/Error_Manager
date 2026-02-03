<?php
// stop php from freaking out on database errors
mysqli_report(MYSQLI_REPORT_OFF);

$host = "localhost";
$user = "np03cy4a240059";
$password = "UPw2ywRgIm";
$database = "np03cy4a240059";

// standard connection setup
$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    // connection failed, just stop
    die("Database Connection Failed: " . mysqli_connect_error());
}

// Set Charset
mysqli_set_charset($conn, "utf8mb4");

// Start Session Safe Check
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// -----------------------------------------------------------
// auto-setup script (creates tables/data if missing)
// -----------------------------------------------------------
try {
    // check if we need to setup tables
    $result = mysqli_query($conn, "SHOW TABLES LIKE 'users'");
    
    if ($result && mysqli_num_rows($result) == 0) {
        // 1. Create Users
        $sql_users = "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        mysqli_query($conn, $sql_users);

        // 2. Create Errors
        $sql_errors = "CREATE TABLE IF NOT EXISTS errors (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            error_code VARCHAR(50),
            title VARCHAR(255) NOT NULL,
            description TEXT NOT NULL,
            solution TEXT,
            language VARCHAR(50),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )";
        mysqli_query($conn, $sql_errors);

        // 3. create the demo user (needed for errors to key off of)
        $pass = '$2y$12$KCVV9us8QzHWQ.KlRXutxu/5a6cdAEbBLi6YO39YYH37ELj/mERZW'; // user123
        // Check if demo_user exists before inserting
        $check_user = mysqli_query($conn, "SELECT id FROM users WHERE username = 'demo_user'");
        if ($check_user && mysqli_num_rows($check_user) == 0) {
             mysqli_query($conn, "INSERT INTO users (username, password) VALUES ('demo_user', '$pass')");
        }

        // Get User ID
        $uid_res = mysqli_query($conn, "SELECT id FROM users WHERE username = 'demo_user' LIMIT 1");
        $uid_row = mysqli_fetch_assoc($uid_res);
        $uid = $uid_row['id'] ?? 1;

        // 4. Seed Dummy Errors
        $check_errors = mysqli_query($conn, "SELECT id FROM errors LIMIT 1");
        if ($check_errors && mysqli_num_rows($check_errors) == 0) {
            $sql_seed = "INSERT INTO errors (user_id, title, error_code, language, description, solution) VALUES 
            ($uid, 'Database connection failed', 'ECONNREFUSED', 'PHP', 'Could not connect to MySQL server at localhost.', 'Check if MySQL service is running and credentials in db.php are correct.'),
            ($uid, 'Undefined index \"user_id\"', 'PHP Notice', 'PHP', 'Attempting to access session variable before session_start().', 'Added session_start() at the top of the file.'),
            ($uid, '404 Page Not Found', 'HTTP 404', 'Apache', 'The requested URL /dashboard was not found on this server.', 'Check if .htaccess is missing or RewriteEngine is off.'),
            ($uid, 'Cannot read property \"style\" of null', 'TypeError', 'JavaScript', 'Trying to manipulate DOM element before it is loaded.', 'Wrapped the code inside document.addEventListener(\"DOMContentLoaded\", ...).'),
            ($uid, 'Syntax error: unexpected token', 'SyntaxError', 'JavaScript', 'Missing closing brace in a function definition.', 'Added the missing } at line 45.')";
            mysqli_query($conn, $sql_seed);
        }
    }
} catch (Throwable $e) {
    // if setup fails, just ignore it. site might still work if tables exist.
}
?>
