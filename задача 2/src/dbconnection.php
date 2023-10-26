<?php

$dsn = 'pgsql:host=127.0.0.1;dbname=bills;port=5432"';
$username = 'postgres';
$password = 'root';
$options = [
PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
];

try {
$pdo = new PDO($dsn, $username, $password, $options);
} catch(PDOException $e) {
die('Connection failed: ' . $e->getMessage());
}
   