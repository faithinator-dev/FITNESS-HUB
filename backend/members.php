<?php
require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

$query = "SELECT m.id, u.name, u.email, m.phone, m.status, m.joined_at 
          FROM members m 
          INNER JOIN users u ON m.user_id = u.id 
          ORDER BY m.id DESC";

$stmt = $db->prepare($query);
$stmt->execute();
$members = $stmt->fetchAll();

echo json_encode([
    "status" => "success",
    "data" => $members
]);
