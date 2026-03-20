<?php
// === api/admin_reviews.php ===
// Handles admin review management for site reviews
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

header('Content-Type: application/json');
require_once 'db.php';

$action = $_GET['action'] ?? null;
if (!$action && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $postData = json_decode(file_get_contents('php://input'), true);
    $action = $postData['action'] ?? null;
    $id = $postData['id'] ?? null;
} else {
    $id = $_GET['id'] ?? null;
}

switch ($action) {
    case 'fetch_all':
        try {
            $stmt = $pdo->query("SELECT * FROM site_reviews ORDER BY submitted_at DESC");
            $reviews = $stmt->fetchAll();
            echo json_encode(['status' => 'success', 'reviews' => $reviews]);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to fetch reviews.']);
        }
        break;

    case 'approve':
        if (!$id) {
            echo json_encode(['status' => 'error', 'message' => 'Review ID required.']);
            exit;
        }
        try {
            $stmt = $pdo->prepare("UPDATE site_reviews SET status = 'approved' WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['status' => 'success']);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to approve review: ' . $e->getMessage()]);
        }
        break;

    case 'reject':
        if (!$id) {
            echo json_encode(['status' => 'error', 'message' => 'Review ID required.']);
            exit;
        }
        try {
            $stmt = $pdo->prepare("UPDATE site_reviews SET status = 'rejected' WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['status' => 'success']);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to reject review: ' . $e->getMessage()]);
        }
        break;

    case 'delete':
        if (!$id) {
            echo json_encode(['status' => 'error', 'message' => 'Review ID required.']);
            exit;
        }
        try {
            $stmt = $pdo->prepare("DELETE FROM site_reviews WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['status' => 'success']);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete review: ' . $e->getMessage()]);
        }
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action.']);
        break;
}
?>