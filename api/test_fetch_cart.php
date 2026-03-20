<?php
require_once 'db.php';
session_start();
$_SESSION['user_id'] = 5; // Justin Bieber

try {
    $stmt = $pdo->prepare("SELECT id, product_id, product_name, quantity, added_at FROM cart_items WHERE user_id = ? ORDER BY added_at DESC");
    $stmt->execute([5]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(['status' => 'success', 'cart' => $items]);
} catch(PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to fetch cart.']);
}
?>
