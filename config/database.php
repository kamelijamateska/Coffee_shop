<?php
// config/database.php - Database connection configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');  // default XAMPP username
define('DB_PASS', '');      // default XAMPP password is blank
define('DB_NAME', 'my_database');

// Create database connection
function connectDB() {
    try {
        $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch(PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}
?>