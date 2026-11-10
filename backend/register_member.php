<?php
require_once 'config/database.php';

$input = json_decode(file_get_contents('php://input'), true);

$name = trim($input['name'] ?? '');
$email = trim($input['email'] ?? '');
$phone = trim($input['phone'] ?? '');
$password = trim($input['password'] ?? '');
$role = strtolower(trim($input['role'] ?? 'member'));

// Allowed roles
if (!in_array($role, ['member', 'trainer', 'admin'])) {
    $role = 'member';
}

if (empty($name) || empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Name, email, and password are required."]);
    exit();
}

$database = new Database();
$db = $database->getConnection();

try {
    // Check if email already registered
    $checkStmt = $db->prepare("SELECT id FROM users WHERE email = :email");
    $checkStmt->bindParam(':email', $email);
    $checkStmt->execute();
    if ($checkStmt->fetch()) {
        http_response_code(409);
        echo json_encode(["status" => "error", "message" => "An account with this email address already exists."]);
        exit();
    }

    $db->beginTransaction();

    // 1. Insert into users table
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $userQuery = "INSERT INTO users (name, email, password_hash, role) VALUES (:name, :email, :password, :role)";
    $userStmt = $db->prepare($userQuery);
    $userStmt->bindParam(':name', $name);
    $userStmt->bindParam(':email', $email);
    $userStmt->bindParam(':password', $hashedPassword);
    $userStmt->bindParam(':role', $role);
    $userStmt->execute();

    $userId = $db->lastInsertId();

    // 2. Insert into members table if registering as member or trainer
    $memberQuery = "INSERT INTO members (user_id, phone, joined_at, status) VALUES (:user_id, :phone, CURDATE(), 'active')";
    $memberStmt = $db->prepare($memberQuery);
    $memberStmt->bindParam(':user_id', $userId);
    $memberStmt->bindParam(':phone', $phone);
    $memberStmt->execute();

    $db->commit();

    $roleLabel = ($role === 'trainer') ? 'Trainer / Coach' : 'Member';
    echo json_encode([
        "status" => "success",
        "message" => "$roleLabel registered successfully!",
        "role" => $role
    ]);
} catch (Exception $e) {
    $db->rollBack();
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Registration failed: " . $e->getMessage()]);
}