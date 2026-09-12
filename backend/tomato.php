<?php
// 1. Allow your React app to access this endpoint
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

// 2. Allow credentials to be sent securely along with the Authorization header
header("Access-Control-Allow-Credentials: true");

// 3. Instantly kill preflight OPTIONS requests so they don't run your main code
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204); 
    exit();
}

// 4. Connect to the database using your docker-compose environment variables
$host = getenv('DB_HOST') ?: 'mysql';
$db   = getenv('DB_DATABASE') ?: 'tomato220';
$user = getenv('DB_USERNAME') ?: 'dev_user';
$pass = getenv('DB_PASSWORD') ?: 'dev_password';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

$fallbackTomato = [
    'id' => 3692,
    'userid' => 1001,
    'title' => 'washzzz dishes',
    'tomdate' => '2025-06-09',
    'datestring' => '2025-06-09',
    'timestamp' => '1749441600',
    'weekdayno' => 1,
    'tomweek' => '2025-W24',
    'count' => 4,
    'category' => 6,
    'notes' => "wash dishes clean room\r\n        \r\n        ",
    'URL' => null,
    'nowstamp' => '2025-06-09 18:29:00',
    'fallback' => true,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    $stmt = $pdo->prepare("SELECT * FROM tomato ORDER BY id DESC LIMIT 10");
    $stmt->execute();
    
    // FORCE the engine to return associative arrays explicitly
    $tomatoData = $stmt->fetchAll(PDO::FETCH_ASSOC); 

    // Check if it is an array and actually has data in it
    if (!empty($tomatoData)) {
        echo json_encode($tomatoData); 
    } else {
        // If the database table is completely empty, send the fallback
        echo json_encode([$fallbackTomato]);
    }

} catch (\PDOException $e) {
    http_response_code(200);
    echo json_encode([$fallbackTomato]);
}

/*
} catch (\PDOException $e) {
    
    http_response_code(500);
    echo json_encode([
        'id' => 0,
        'title' => 'DATABASE ERROR DETECTED',
        'count' => 0,
        'category' => $e->getMessage() 
}
*/