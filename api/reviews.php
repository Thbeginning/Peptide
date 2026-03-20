<?php
// === api/reviews.php ===
// Handles fetching approved site reviews for frontend display
header('Content-Type: application/json');
require_once 'db.php';

$action = $_GET['action'] ?? null;

switch ($action) {
    case 'fetch_approved':
        try {
            $stmt = $pdo->query("SELECT display_name as name, overall_rating as rating, review_text as comment FROM site_reviews WHERE status = 'approved' ORDER BY submitted_at DESC");
            $reviews = $stmt->fetchAll();
            echo json_encode(['status' => 'success', 'reviews' => $reviews]);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to fetch reviews.']);
        }
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action.']);
        break;
}
?>