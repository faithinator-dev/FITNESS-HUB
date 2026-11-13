<?php
// 1. Enable CORS headers for Web & Mobile clients
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

// 2. Handle HTTP OPTIONS preflight request from Web Browsers
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../config/database.php';

// 3. Parse requested URI path
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uriSegments = explode('/', trim($requestUri, '/'));

// Find route segment (e.g., /public/index.php/login)
$endpoint = end($uriSegments);

$database = new Database();
$db = $database->getConnection();

// 4. Basic Router Mapping
switch ($endpoint) {
    case 'login':
        require_once __DIR__ . '/../login.php';
        break;

    case 'members':
        require_once __DIR__ . '/../members.php';
        break;

    case 'register':
    case 'register_member':
        require_once __DIR__ . '/../register_member.php';
        break;

    case 'attendance':
        require_once __DIR__ . '/../attendance.php';
        break;

    case 'routines':
        require_once __DIR__ . '/../routines.php';
        break;

    case 'stats':
    case 'stat':
        require_once __DIR__ . '/../stat.php';
        break;

    default:
        $connectionInfo = $db->query(
            'SELECT DATABASE() AS database_name, VERSION() AS server_version'
        )->fetch();

        echo json_encode([
            "status" => "success",
            "message" => "FitLife Central API Router and database connection are running.",
            "connection" => [
                "status" => "connected",
                "host" => "sql7.freesqldatabase.com",
                "port" => 3306,
                "database" => $connectionInfo["database_name"],
                "user" => "sql7840675",
                "server_version" => $connectionInfo["server_version"],
                "charset" => "utf8mb4"
            ],
            "available_endpoints" => ["login", "members", "register", "attendance", "routines", "stats"]
        ]);
        break;
}
