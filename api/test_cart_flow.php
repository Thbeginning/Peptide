<?php
require_once 'db.php';
session_start();
// Mock login as user 5
$_SESSION['user_id'] = 5;

// Insert a fake item to the cart
$pdo->exec("INSERT INTO cart_items (user_id, product_id, product_name, quantity) VALUES (5, 'bpc-tb-blend', 'BPC157 + TB500', 3)");

echo "Cart item inserted. Now simulate quote submission...\n";

// Fetch current cart items
$stmt = $pdo->prepare("SELECT product_id, product_name, quantity FROM cart_items WHERE user_id = ?");
$stmt->execute([5]);
$cart_items = $stmt->fetchAll();
print_r($cart_items);

// Create Quote Request
$insert_quote = $pdo->prepare("INSERT INTO quote_requests (user_id, shipping_city, shipping_country, contact_method, contact_detail, message, research_only_confirmed) VALUES (?, ?, ?, ?, ?, ?, ?)");
$insert_quote->execute([5, 'TestCity', 'TestCountry', 'email', 'test@example.com', 'Test msg', 1]);
$quote_id = $pdo->lastInsertId();

// Insert Items
$insert_item = $pdo->prepare("INSERT INTO quote_request_items (quote_id, product_id, product_name, quantity) VALUES (?, ?, ?, ?)");
foreach ($cart_items as $item) {
    $insert_item->execute([$quote_id, $item['product_id'], $item['product_name'], $item['quantity']]);
}

// Check quote items
$check = $pdo->query("SELECT * FROM quote_request_items WHERE quote_id = $quote_id")->fetchAll();
print_r($check);

?>
