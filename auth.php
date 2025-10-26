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
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['userKey']) || !isset($input['verificationKey'])) {
        echo json_encode(['success' => false, 'error' => 'Missing credentials']);
        exit;
    }
    
    $userKey = $conn->real_escape_string($input['userKey']);
    $verificationKey = $conn->real_escape_string($input['verificationKey']);
    
    // Query database
    $query = "SELECT userDiscordId, validKey FROM users WHERE userKey = '$userKey' AND verificationKey = '$verificationKey'";
    $result = $conn->query($query);
    
    if ($result && $row = $result->fetch_assoc()) {
        if ($row['validKey'] == 1) {
            echo json_encode([
                'success' => true,
                'discordId' => $row['userDiscordId']
            ]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Key is not valid']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid credentials']);
    }
    
    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>
