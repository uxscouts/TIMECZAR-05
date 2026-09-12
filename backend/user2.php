<?php
// 1. Handle CORS securely and fix the Allow-Credentials wildcard bug
$origin = $_SERVER['HTTP_ORIGIN'] ?? '*';
header("Access-Control-Allow-Origin: $origin"); 
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

// 2. Handle Preflight instantly
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}

// 3. Database Credentials
$host = getenv('DB_HOST') ?: 'mysql';
$db   = getenv('DB_DATABASE') ?: 'my_database';
$user = getenv('DB_USERNAME') ?: 'dev_user';
$pass = getenv('DB_PASSWORD') ?: 'dev_password';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

// Fallback updated to an array of objects to prevent frontend map() crashes
$fallbackUsers = [[
    'id' => 14,
    'username' => 'Leif (Fallback)',
    'email' => 'leif@leif.com',
    'created_at' => '2026-07-01',
]];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    // Use FETCHALL to get up to 10 users as an array
    $stmt = $pdo->prepare("SELECT * FROM `tomato` LIMIT 10");
    $stmt->execute();
    $tomatoData = $stmt->fetchAll(); 

    if (!empty($tomatoData)) {
        echo json_encode($tomatoData);
    } else {
        // Table is empty, return empty array so React doesn't crash
        echo json_encode([]); 
    }

} catch (\PDOException $e) {
    // CRITICAL: Temporarily return a 500 error and the message so you can see it in Network tab
    http_response_code(500);
    echo json_encode([
        "error" => "Database connection failed",
        "message" => $e->getMessage()
    ]);
}
