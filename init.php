<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Database configuration
$host = "127.0.0.1";
$port = "3306";
$dbname = "s3503_database";
$username = "u3503_Zc6D6LHtCz";
$password = "3hBp!YkAC0=z@nD+abJ4091d";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'error' => 'Database connection failed']));
}

// Handle CORS preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if users table exists
    $result = $conn->query("SHOW TABLES LIKE 'users'");
    
    if ($result && $result->num_rows > 0) {
        // Table exists
        echo json_encode(['tableExists' => true, 'message' => 'Users table already exists']);
    } else {
        // Create users table
        $createTable = "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            userDiscordId VARCHAR(255) NOT NULL,
            userKey VARCHAR(255) NOT NULL UNIQUE,
            validKey BOOLEAN DEFAULT TRUE,
            verificationKey VARCHAR(255) NOT NULL,
            createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        
        if ($conn->query($createTable)) {
            echo json_encode(['tableExists' => true, 'message' => 'Users table created successfully']);
        } else {
            echo json_encode(['tableExists' => false, 'error' => 'Failed to create table: ' . $conn->error]);
        }
    }
    
    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>
