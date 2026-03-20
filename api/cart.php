<?php
// === api/cart.php ===
// Handles adding, fetching, and removing items from a user's cart

session_start();
header('Content-Type: application/json');

require_once 'db.php';

// Check Authentication
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized. Please log in first.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

$action = $_GET['action'] ?? ($input['action'] ?? null);

switch ($action) {
    case 'add':
        $product_id = $input['product_id'] ?? '';
        $product_name = $input['product_name'] ?? '';
        $quantity = (int)($input['quantity'] ?? 1);

        if (empty($product_id) || empty($product_name)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid product data.']);
            exit;
        }

        try {
            // Check if item already exists in cart, if so, increment quantity
            $stmt = $pdo->prepare("SELECT id, quantity FROM cart_items WHERE user_id = ? AND product_id = ?");
            $stmt->execute([$user_id, $product_id]);
            $existing = $stmt->fetch();

            if ($existing) {
                // Update
                $new_qty = $existing['quantity'] + $quantity;
                $update = $pdo->prepare("UPDATE cart_items SET quantity = ?, added_at = NOW() WHERE id = ?");
                $update->execute([$new_qty, $existing['id']]);
            } else {
                // Insert
                $insert = $pdo->prepare("INSERT INTO cart_items (user_id, product_id, product_name, quantity) VALUES (?, ?, ?, ?)");
                $insert->execute([$user_id, $product_id, $product_name, $quantity]);
            }
            
            echo json_encode(['status' => 'success', 'message' => 'Added to cart']);
        } catch(PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add to cart.', 'error' => $e->getMessage()]);
        }
        break;

    case 'fetch':
        try {
            $stmt = $pdo->prepare("SELECT id, product_id, product_name, quantity, added_at FROM cart_items WHERE user_id = ? ORDER BY added_at DESC");
            $stmt->execute([$user_id]);
            $items = $stmt->fetchAll();
            
            echo json_encode(['status' => 'success', 'cart' => $items]);
        } catch(PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to fetch cart.']);
        }
        break;

    case 'remove':
        $item_id = $input['item_id'] ?? null;
        if (!$item_id) {
            echo json_encode(['status' => 'error', 'message' => 'Item ID required.']);
            exit;
        }

        try {
            // Ensure the item belongs to the user
            $stmt = $pdo->prepare("DELETE FROM cart_items WHERE id = ? AND user_id = ?");
            $stmt->execute([$item_id, $user_id]);
            echo json_encode(['status' => 'success', 'message' => 'Item removed']);
        } catch(PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to remove item.']);
        }
        break;

    case 'update':
        $item_id = $input['item_id'] ?? null;
        $qty = (int)($input['quantity'] ?? 1);
        
        if (!$item_id || $qty < 1) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid update payload.']);
            exit;
        }

        try {
            $stmt = $pdo->prepare("UPDATE cart_items SET quantity = ? WHERE id = ? AND user_id = ?");
            $stmt->execute([$qty, $item_id, $user_id]);
            echo json_encode(['status' => 'success', 'message' => 'Quantity updated']);
        } catch(PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update quantity.']);
        }
        break;

    case 'submit_quote':
        // Handle Quote Submission
        $shipping_city = $input['shipping_city'] ?? '';
        $shipping_country = $input['shipping_country'] ?? '';
        $contact_method = $input['contact_method'] ?? 'email';
        $contact_detail = $input['contact_detail'] ?? '';
        $message = $input['message'] ?? '';
        $research_only_confirmed = isset($input['research_only_confirmed']) && $input['research_only_confirmed'] ? 1 : 0;

        if (empty($shipping_city) || empty($shipping_country) || empty($contact_detail) || !$research_only_confirmed) {
            echo json_encode(['status' => 'error', 'message' => 'Mandatory fields are missing or research policy not accepted.']);
            exit;
        }

        try {
            $pdo->beginTransaction();

            // Fetch current cart items
            $stmt = $pdo->prepare("SELECT product_id, product_name, quantity FROM cart_items WHERE user_id = ?");
            $stmt->execute([$user_id]);
            $cart_items = $stmt->fetchAll();

            if (empty($cart_items)) {
                $pdo->rollBack();
                echo json_encode(['status' => 'error', 'message' => 'Your quote list is empty.']);
                exit;
            }

            // Create Quote Request
            $insert_quote = $pdo->prepare("INSERT INTO quote_requests (user_id, shipping_city, shipping_country, contact_method, contact_detail, message, research_only_confirmed) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $insert_quote->execute([$user_id, $shipping_city, $shipping_country, $contact_method, $contact_detail, $message, $research_only_confirmed]);
            $quote_id = $pdo->lastInsertId();

            // Insert Items
            $insert_item = $pdo->prepare("INSERT INTO quote_request_items (quote_id, product_id, product_name, quantity) VALUES (?, ?, ?, ?)");
            foreach ($cart_items as $item) {
                $insert_item->execute([$quote_id, $item['product_id'], $item['product_name'], $item['quantity']]);
            }

            // Empty standard Cart
            $clear_cart = $pdo->prepare("DELETE FROM cart_items WHERE user_id = ?");
            $clear_cart->execute([$user_id]);

            $pdo->commit();
            
            // Basic Notification email to admin
            $admin_email = "donaldfaith193@gmail.com";
            $subject = "New Wholesale Quote Request #$quote_id";
            $email_body = "You have received a new quote request from User ID $user_id.\n\n";
            $email_body .= "City: $shipping_city, Country: $shipping_country\n";
            $email_body .= "Preferred Contact: $contact_method ($contact_detail)\n";
            $email_body .= "Message: $message\n\n";
            $email_body .= "Items Requested:\n";
            foreach ($cart_items as $item) {
                 $email_body .= "- {$item['product_name']} (Qty: {$item['quantity']})\n";
            }
            
            // Fetch User Name for personalized response
            $stmt_user = $pdo->prepare("SELECT name FROM users WHERE id = ?");
            $stmt_user->execute([$user_id]);
            $user_data = $stmt_user->fetch();
            $user_name = $user_data ? $user_data['name'] : 'CEO';

            // In a real server environment, uncomment to send:
            // @mail($admin_email, $subject, $email_body, "From: no-reply@qinglipeptide.com");

            echo json_encode(['status' => 'success', 'message' => "Your request has been sent, $user_name. Our team will review your quote and contact you within 12 hours with a final invoice including shipping.", 'quote_id' => $quote_id]);

        } catch (PDOException $e) {
            $pdo->rollBack();
            echo json_encode(['status' => 'error', 'message' => 'Failed to submit quote.', 'error' => $e->getMessage()]);
        }
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
}
