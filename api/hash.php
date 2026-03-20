<?php
// Password Hash Generator
// Run this to get the hash for "admin123"
$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Hash for '$password' is: <br>";
echo $hash;
?>
