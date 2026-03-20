<?php
require 'api/db.php';
$stmt = $pdo->query('SELECT name FROM products');
while ($row = $stmt->fetch()) {
    echo $row['name'] . "\n";
}
?>
