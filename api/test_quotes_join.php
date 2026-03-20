<?php
require_once 'db.php';
try {
    $stmt = $pdo->query("
        SELECT q.id as q_id, u.id as u_id, u.name as customer_name, q.* 
        FROM quote_requests q 
        LEFT JOIN users u ON q.user_id = u.id 
    ");
    $quotes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "LEFT JOIN RESULT:\n";
    print_r($quotes);
    
    $stmt = $pdo->query("
        SELECT q.id as q_id, u.id as u_id, u.name as customer_name, q.* 
        FROM quote_requests q 
        JOIN users u ON q.user_id = u.id 
    ");
    $quotes_inner = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "\nINNER JOIN RESULT:\n";
    print_r($quotes_inner);

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
