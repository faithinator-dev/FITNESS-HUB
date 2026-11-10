<?php
require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

// Support GET: fetch attendance history for a member
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $memberId = $_GET['member_id'] ?? null;
    
    if (!$memberId) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Member ID is required."]);
        exit();
    }

    $stmt = $db->prepare("SELECT id, member_id, check_in_at FROM attendance WHERE member_id = :member_id ORDER BY check_in_at DESC LIMIT 50");
    $stmt->bindParam(':member_id', $memberId);
    $stmt->execute();
    $records = $stmt->fetchAll();

    echo json_encode([
        "status" => "success",
        "data" => $records
    ]);
    exit();
}

// Support POST: record check-in
$input = json_decode(file_get_contents('php://input'), true);
$memberId = $input['member_id'] ?? null;

if (!$memberId) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Member ID is required."]);
    exit();
}

$stmt = $db->prepare("INSERT INTO attendance (member_id, check_in_at) VALUES (:member_id, NOW())");
$stmt->bindParam(':member_id', $memberId);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Attendance logged successfully."]);
} else {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Failed to record attendance."]);
}
