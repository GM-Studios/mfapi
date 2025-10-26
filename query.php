<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Database configuration for DiscordBotHosting.com
$host = "database.discordbothosting.com";  // Update this to your actual DiscordBotHosting hostname
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
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['query'])) {
        echo json_encode(['success' => false, 'error' => 'No query provided']);
        exit;
    }
    
    $query = $input['query'];
    
    // Execute query
    $result = $conn->query($query);
    
    if ($result === false) {
        echo json_encode([
            'success' => false,
            'error' => $conn->error
        ]);
    } elseif ($result === true) {
        // For INSERT, UPDATE, DELETE queries
        echo json_encode([
            'success' => true,
            'affected_rows' => $conn->affected_rows
        ]);
    } else {
        // For SELECT queries
        $results = [];
        while ($row = $result->fetch_assoc()) {
            $results[] = $row;
        }
        echo json_encode([
            'success' => true,
            'results' => $results
        ]);
    }
    
    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>
