<?php
// === api/user_quotes.php ===
// Handles fetching quotes for the customer dashboard

session_start();
header('Content-Type: application/json');

require_once 'db.php';

// Check Authentication
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized. Please log in first.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'fetch_history':
        try {
            // Fetch all quotes for the user
            $stmt = $pdo->prepare("SELECT * FROM quote_requests WHERE user_id = ? ORDER BY created_at DESC");
            $stmt->execute([$user_id]);
            $quotes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Fetch items for each quote
            foreach ($quotes as &$quote) {
                $item_stmt = $pdo->prepare("SELECT * FROM quote_request_items WHERE quote_id = ?");
                $item_stmt->execute([$quote['id']]);
                $quote['items'] = $item_stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            echo json_encode(['status' => 'success', 'quotes' => $quotes]);
        } catch(PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to fetch quote history.']);
        }
        break;

    case 'duplicate':
        $input = json_decode(file_get_contents('php://input'), true);
        $quote_id = $input['quote_id'] ?? null;

        if (!$quote_id) {
            echo json_encode(['status' => 'error', 'message' => 'Quote ID required.']);
            exit;
        }

        try {
            $pdo->beginTransaction();

            // Verify quote belongs to user
            $stmt = $pdo->prepare("SELECT id FROM quote_requests WHERE id = ? AND user_id = ?");
            $stmt->execute([$quote_id, $user_id]);
            if (!$stmt->fetch()) {
                throw new Exception("Unauthorized or quote not found.");
            }

            // Get items
            $item_stmt = $pdo->prepare("SELECT product_id, product_name, quantity FROM quote_request_items WHERE quote_id = ?");
            $item_stmt->execute([$quote_id]);
            $items = $item_stmt->fetchAll(PDO::FETCH_ASSOC);

            // Insert into active cart (quote list)
            $insert = $pdo->prepare("INSERT INTO cart_items (user_id, product_id, product_name, quantity) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)");
            
            foreach ($items as $item) {
                // Using a separate check to increment properly if it exists, since we didn't set a unique key constraint on user_id + product_id in the db setup
                $check = $pdo->prepare("SELECT id, quantity FROM cart_items WHERE user_id = ? AND product_id = ?");
                $check->execute([$user_id, $item['product_id']]);
                $existing = $check->fetch();

                if ($existing) {
                     $update = $pdo->prepare("UPDATE cart_items SET quantity = quantity + ? WHERE id = ?");
                     $update->execute([$item['quantity'], $existing['id']]);
                } else {
                     $insert = $pdo->prepare("INSERT INTO cart_items (user_id, product_id, product_name, quantity) VALUES (?, ?, ?, ?)");
                     $insert->execute([$user_id, $item['product_id'], $item['product_name'], $item['quantity']]);
                }
            }

            $pdo->commit();
            echo json_encode(['status' => 'success', 'message' => 'Items added to your active Quote List.']);

        } catch(Exception $e) {
            $pdo->rollBack();
            echo json_encode(['status' => 'error', 'message' => 'Failed to duplicate quote.']);
        }
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
}
