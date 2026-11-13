<?php
function fitlife_load_environment(): void
{
    static $loaded = false;

    if ($loaded) {
        return;
    }

    $environmentFile = __DIR__ . '/../.env';
    if (is_file($environmentFile)) {
        $variables = parse_ini_file($environmentFile, false, INI_SCANNER_RAW);
        if (is_array($variables)) {
            foreach ($variables as $name => $value) {
                if (getenv($name) === false) {
                    putenv($name . '=' . $value);
                }
            }
        }
    }

    $loaded = true;
}

class Database
{
    public $conn;

    public static function getConfig(): array
    {
        fitlife_load_environment();

        return [
            'host' => getenv('DB_HOST') ?: getenv('host') ?: 'localhost',
            'port' => (int)(getenv('DB_PORT') ?: getenv('port') ?: 3306),
            'database' => getenv('DB_DATABASE') ?: getenv('databaseName') ?: 'fitlife_db',
            'username' => getenv('DB_USERNAME') ?: getenv('username') ?: 'root',
            'password' => getenv('DB_PASSWORD') ?: getenv('password') ?: '',
        ];
    }

    public function getConnection(): PDO
    {
        $this->conn = null;

        $config = self::getConfig();

        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
            $config['host'],
            $config['port'],
            $config['database']
        );

        try {
            $this->conn = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $exception) {
            error_log('FitLife database connection failed: ' . $exception->getMessage());
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Database connection failed. Check the MySQL settings and PDO MySQL extension.'
            ]);
            exit();
        }

        return $this->conn;
    }
}
