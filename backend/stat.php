<?php
require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

// Count active members
$memberStmt = $db->query("SELECT COUNT(*) AS total FROM members WHERE status = 'active'");
$activeMembers = $memberStmt->fetch()['total'];

// Count today's check-ins
$checkinStmt = $db->query("SELECT COUNT(*) AS total FROM attendance WHERE DATE(check_in_at) = CURRENT_DATE()");
$todayCheckins = $checkinStmt->fetch()['total'];

echo json_encode([
    "status" => "success",
    "data" => [
        "active_members" => (int)$activeMembers,
        "today_checkins" => (int)$todayCheckins
    ]
]);
