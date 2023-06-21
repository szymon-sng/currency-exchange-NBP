<?php
$dsn = 'mysql:host=localhost;dbname=currency_exchange;charset=utf8';
$username = 'root';
$password = '';

try {
    $connection = new PDO($dsn, $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    exit('Connection failed: ' . $e->getMessage());
}
?>
