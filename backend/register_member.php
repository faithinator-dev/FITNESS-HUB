<?php
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    echo json_encode(["status" => "error", "message" => "Only POST requests are allowed."]);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

$name = trim((string)($input['name'] ?? ''));
$email = trim((string)($input['email'] ?? ''));
$phone = trim((string)($input['phone'] ?? ''));
$password = (string)($input['password'] ?? '');
$role = strtolower(trim((string)($input['role'] ?? 'member')));

// Allowed roles
if (!in_array($role, ['member', 'trainer', 'admin'])) {
    $role = 'member';
}

if (empty($name) || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Name, a valid email, and a password of at least 8 characters are required."]);
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
} catch (Throwable $exception) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    error_log('FitLife registration failed: ' . $exception->getMessage());
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Registration failed. Please try again."]);
}