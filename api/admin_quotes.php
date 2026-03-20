<?php
// === api/admin_quotes.php ===
// Handles admin fetching and updating of quotes

session_start();
header('Content-Type: application/json');

require_once 'db.php';

// Check Authentication & Role
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized. Admin access only.']);
    exit;
}

$action = $_GET['action'] ?? '';
$input = json_decode(file_get_contents('php://input'), true);

switch ($action) {
    case 'fetch_all':
        try {
            // Join with users to get customer name
            $stmt = $pdo->query("
                SELECT q.*, u.name as customer_name, u.email as customer_email 
                FROM quote_requests q 
                JOIN users u ON q.user_id = u.id 
                ORDER BY q.created_at DESC
            ");
            $quotes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($quotes as &$quote) {
                $item_stmt = $pdo->prepare("SELECT product_name, quantity FROM quote_request_items WHERE quote_id = ?");
                $item_stmt->execute([$quote['id']]);
                $quote['items'] = $item_stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            echo json_encode(['status' => 'success', 'quotes' => $quotes]);
        } catch(PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to fetch quotes.']);
        }
        break;

    case 'update_status':
        $quote_id = $input['quote_id'] ?? null;
        $status = $input['status'] ?? null;

        if (!$quote_id || !$status) {
            echo json_encode(['status' => 'error', 'message' => 'Missing data.']);
            exit;
        }

        try {
            $stmt = $pdo->prepare("UPDATE quote_requests SET status = ? WHERE id = ?");
            $stmt->execute([$status, $quote_id]);
            echo json_encode(['status' => 'success', 'message' => 'Status updated.']);
        } catch(PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update status.']);
        }
        break;

    case 'update_notes':
        $quote_id = $input['quote_id'] ?? null;
        $notes = $input['notes'] ?? '';

        if (!$quote_id) {
            echo json_encode(['status' => 'error', 'message' => 'Quote ID missing.']);
            exit;
        }

        try {
            $stmt = $pdo->prepare("UPDATE quote_requests SET internal_notes = ? WHERE id = ?");
            $stmt->execute([$notes, $quote_id]);
            echo json_encode(['status' => 'success', 'message' => 'Notes saved.']);
        } catch(PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update notes.']);
        }
        break;

    case 'delete_quote':
        $quote_id = $input['quote_id'] ?? null;
        if (!$quote_id) {
            echo json_encode(['status' => 'error', 'message' => 'Quote ID missing.']);
            exit;
        }
        try {
            $pdo->beginTransaction();
            // Delete items first due to FK
            $stmt1 = $pdo->prepare("DELETE FROM quote_request_items WHERE quote_id = ?");
            $stmt1->execute([$quote_id]);
            
            $stmt2 = $pdo->prepare("DELETE FROM quote_requests WHERE id = ?");
            $stmt2->execute([$quote_id]);
            
            $pdo->commit();
            echo json_encode(['status' => 'success', 'message' => 'Quote deleted.']);
        } catch(PDOException $e) {
            $pdo->rollBack();
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete quote.']);
        }
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
}
?>
