<?php
require_once 'db.php';
try {
    $stmt = $pdo->query("SELECT * FROM cart_items");
    $cart = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "CART ITEMS:\n";
    print_r($cart);

    $stmt = $pdo->query("SELECT * FROM quote_requests");
    $quotes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "\nQUOTE REQUESTS:\n";
    print_r($quotes);

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
