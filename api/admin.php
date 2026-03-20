<?php
// === api/admin.php ===
// Admin Dashboard API endpoints

session_start();
header('Content-Type: application/json');

require_once 'db.php';

// Check Authentication and Admin Role
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized. Admin access required.']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? null;

switch ($action) {
    case 'stats':
        try {
            // Get total users (excluding admin itself)
            $user_count_stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role != 'admin'");
            $total_users = $user_count_stmt->fetchColumn();

            // Get total items sitting in all carts
            $cart_count_stmt = $pdo->query("SELECT SUM(quantity) FROM cart_items");
            $total_cart_items = $cart_count_stmt->fetchColumn() ?: 0;
            
            // Get recent complete users
            $recent_stmt = $pdo->query("SELECT id, name, email, created_at, last_login FROM users WHERE role != 'admin' ORDER BY created_at DESC LIMIT 5");
            $recent_users = $recent_stmt->fetchAll();

            echo json_encode([
                'status' => 'success',
                'stats' => [
                    'total_users' => $total_users,
                    'total_cart_items' => $total_cart_items,
                    'recent_users' => $recent_users
                ]
            ]);
        } catch(PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to fetch stats.']);
        }
        break;

    case 'users':
        try {
            // Fetch all users
            $stmt = $pdo->query("
                SELECT u.id, u.name, u.email, u.created_at, u.last_login, COUNT(c.id) as cart_item_count 
                FROM users u 
                LEFT JOIN cart_items c ON u.id = c.user_id 
                WHERE u.role != 'admin' 
                GROUP BY u.id 
                ORDER BY u.created_at DESC
            ");
            $users = $stmt->fetchAll();
            echo json_encode(['status' => 'success', 'users' => $users]);
        } catch(PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to fetch users.']);
        }
        break;

    case 'user_cart':
        $target_user_id = $_GET['user_id'] ?? null;
        if (!$target_user_id) {
            echo json_encode(['status' => 'error', 'message' => 'User ID is required']);
            exit;
        }

        try {
            // Get user details
            $user_stmt = $pdo->prepare("SELECT name, email FROM users WHERE id = ?");
            $user_stmt->execute([$target_user_id]);
            $user = $user_stmt->fetch();

            if (!$user) {
                echo json_encode(['status' => 'error', 'message' => 'User not found']);
                exit;
            }

            // Get their cart items
            $cart_stmt = $pdo->prepare("SELECT product_id, product_name, quantity, added_at FROM cart_items WHERE user_id = ? ORDER BY added_at DESC");
            $cart_stmt->execute([$target_user_id]);
            $cart = $cart_stmt->fetchAll();

            echo json_encode([
                'status' => 'success', 
                'user' => $user,
                'cart' => $cart
            ]);
        } catch(PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to fetch user cart.']);
        }
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
}
?>
