<?php
require_once 'config/database.php';

// Read JSON payload from request body
$input = json_decode(file_get_contents('php://input'), true);

$email = $input['email'] ?? '';
$password = $input['password'] ?? '';

if (empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Email and password are required."]);
    exit();
}

$database = new Database();
$db = $database->getConnection();

// Fetch user by email
$query = "SELECT u.id, u.name, u.email, u.password_hash, u.role, m.id AS member_id 
          FROM users u 
          LEFT JOIN members m ON u.id = m.user_id 
          WHERE u.email = :email";

$stmt = $db->prepare($query);
$stmt->bindParam(':email', $email);
$stmt->execute();

$user = $stmt->fetch();

if ($user && password_verify($password, $user['password_hash'])) {
    // Generate stateless API token
    $apiToken = bin2hex(random_bytes(32));

    // Store token in database
    $updateStmt = $db->prepare("UPDATE users SET api_token = :token WHERE id = :id");
    $updateStmt->bindParam(':token', $apiToken);
    $updateStmt->bindParam(':id', $user['id']);
    $updateStmt->execute();

    echo json_encode([
        "status" => "success",
        "token" => $apiToken,
        "user" => [
            "id" => $user['id'],
            "member_id" => $user['member_id'],
            "name" => $user['name'],
            "email" => $user['email'],
            "role" => $user['role']
        ]
    ]);
} else {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Invalid email or password."]);
}
