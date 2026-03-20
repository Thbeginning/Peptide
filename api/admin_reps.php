<?php
// api/admin_reps.php

session_start();
header('Content-Type: application/json');
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

// Fetch user data to check role
$stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user || $user['role'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized: Admins only.']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Fetch all reps
    try {
        $stmt = $pdo->query("SELECT * FROM representatives ORDER BY created_at DESC");
        $reps = $stmt->fetchAll();
        echo json_encode(['status' => 'success', 'reps' => $reps]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error.']);
    }
} elseif ($method === 'POST') {
    // Read JSON payload
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['action'])) {
        echo json_encode(['status' => 'error', 'message' => 'Action required.']);
        exit;
    }

    $action = $data['action'];

    if ($action === 'add') {
        $rep_id = trim($data['rep_id'] ?? '');
        $name = trim($data['name'] ?? '');
        $territory = trim($data['territory'] ?? '');
        $status = trim($data['status'] ?? 'Active');

        if (empty($rep_id) || empty($name)) {
            echo json_encode(['status' => 'error', 'message' => 'ID and Name are required.']);
            exit;
        }

        try {
            // Check if rep_id already exists
            $stmt = $pdo->prepare("SELECT id FROM representatives WHERE rep_id = ?");
            $stmt->execute([$rep_id]);
            if ($stmt->fetch()) {
                echo json_encode(['status' => 'error', 'message' => 'Rep ID already exists.']);
                exit;
            }

            $stmt = $pdo->prepare("INSERT INTO representatives (rep_id, name, territory, status) VALUES (?, ?, ?, ?)");
            $stmt->execute([$rep_id, $name, $territory, $status]);
            echo json_encode(['status' => 'success', 'message' => 'Representative added successfully.']);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Database error.']);
        }
    } elseif ($action === 'update') {
        $id = $data['id'] ?? 0;
        $name = trim($data['name'] ?? '');
        $territory = trim($data['territory'] ?? '');
        $status = trim($data['status'] ?? 'Active');

        if (empty($id) || empty($name)) {
            echo json_encode(['status' => 'error', 'message' => 'ID and Name are required.']);
            exit;
        }

        try {
            $stmt = $pdo->prepare("UPDATE representatives SET name = ?, territory = ?, status = ? WHERE id = ?");
            $stmt->execute([$name, $territory, $status, $id]);
            echo json_encode(['status' => 'success', 'message' => 'Representative updated successfully.']);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Database error.']);
        }
    } elseif ($action === 'delete') {
        $id = $data['id'] ?? 0;

        if (empty($id)) {
            echo json_encode(['status' => 'error', 'message' => 'ID is required.']);
            exit;
        }

        try {
            $stmt = $pdo->prepare("DELETE FROM representatives WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['status' => 'success', 'message' => 'Representative deleted successfully.']);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Database error.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Unknown action.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
