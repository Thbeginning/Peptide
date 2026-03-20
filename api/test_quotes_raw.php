<?php
require_once 'db.php';
$stmt = $pdo->query("SELECT * FROM quote_requests");
$quotes = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($quotes);
?>
