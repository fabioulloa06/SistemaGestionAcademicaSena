<?php
$host = '127.0.0.1';
$user = 'root';
$pass = '';
$dbname = 'sena_db'; // Default fallback, will be replaced if tinker output differs

try {
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");
    echo "Database `$dbname` created successfully or already exists.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit(1);
}
