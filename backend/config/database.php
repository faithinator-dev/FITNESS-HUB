<?php
class Database
{
    public $conn;

    public function getConnection()
    {
        $this->conn = null;

        $host = 'sql7.freesqldatabase.com';
        $port = 3306;
        $databaseName = 'sql7840675';
        $username = 'sql7840675';
        $password = 'FjMVHZ7zls';

        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
            $host,
            $port,
            $databaseName
        );

        try {
            $this->conn = new PDO($dsn, $username, $password, [
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
