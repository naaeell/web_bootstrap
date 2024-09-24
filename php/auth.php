<?php
// auth.php

// Start the session
session_start();

// Database configuration
$host = 'localhost'; // Your database host
$db   = 'web';       // Your database name
$user = 'root';      // Your database username
$pass = '';          // Your database password

// Create a new PDO instance
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
