<?php
class Database
{
    private $host = "localhost";
    private $db_name = "fitlife_db";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch (PDOException $exception) {
            echo json_encode([
                "status" => "error",
                "message" => "Connection failed: " . $exception->getMessage()
            ]);
            exit();
        }
        return $this->conn;
    }
}
