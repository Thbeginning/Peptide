<?php
// api/verify_rep.php

header('Content-Type: application/json');
require_once 'db.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $rep_id = isset($_GET['rep_id']) ? trim($_GET['rep_id']) : '';

    if (empty($rep_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Rep ID is required.']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM representatives WHERE rep_id = ? LIMIT 1");
        $stmt->execute([$rep_id]);
        $rep = $stmt->fetch();

        if ($rep) {
            echo json_encode([
                'status' => 'success',
                'rep' => [
                    'name' => $rep['name'],
                    'territory' => $rep['territory'],
                    'status' => $rep['status']
                ]
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ID not recognized.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
