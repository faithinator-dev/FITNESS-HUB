<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$routineFile = __DIR__ . '/routines_data.json';

// Ensure data file exists
if (!file_exists($routineFile)) {
    file_put_contents($routineFile, json_encode([
        [
            "id" => 1,
            "member_id" => 1,
            "routine_name" => "Upper Body Hypertrophy",
            "exercise" => "Standard Push-Ups",
            "sets_reps" => "3 Sets x 12 Reps",
            "assigned_by" => "Head Coach",
            "date" => date("Y-m-d H:i:s")
        ],
        [
            "id" => 2,
            "member_id" => 1,
            "routine_name" => "Lower Body Strength",
            "exercise" => "Bodyweight Squats",
            "sets_reps" => "4 Sets x 15 Reps",
            "assigned_by" => "Head Coach",
            "date" => date("Y-m-d H:i:s")
        ]
    ]));
}

$routines = json_decode(file_get_contents($routineFile), true) ?: [];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $memberId = $_GET['member_id'] ?? null;
    if ($memberId) {
        $filtered = array_values(array_filter($routines, function($r) use ($memberId) {
            return (string)$r['member_id'] === (string)$memberId;
        }));
        echo json_encode(["status" => "success", "data" => $filtered]);
    } else {
        echo json_encode(["status" => "success", "data" => $routines]);
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $memberId = $input['member_id'] ?? null;
    $routineName = $input['routine_name'] ?? 'Custom Workout';
    $exercise = $input['exercise'] ?? '';
    $setsReps = $input['sets_reps'] ?? '';
    $assignedBy = $input['assigned_by'] ?? 'Trainer';

    if (!$memberId || empty($exercise)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Trainee member and exercise are required."]);
        exit();
    }

    $newRoutine = [
        "id" => time(),
        "member_id" => $memberId,
        "routine_name" => $routineName,
        "exercise" => $exercise,
        "sets_reps" => $setsReps,
        "assigned_by" => $assignedBy,
        "date" => date("Y-m-d H:i:s")
    ];

    $routines[] = $newRoutine;
    file_put_contents($routineFile, json_encode($routines, JSON_PRETTY_PRINT));

    echo json_encode([
        "status" => "success",
        "message" => "Routine assigned successfully!",
        "data" => $newRoutine
    ]);
    exit();
}

