<?php
// db.php

$host = '127.0.0.1';
$port = 3307;
$dbname = "my_expense_tracker";
$username = "root";
$password = "";
$charset = 'utf8mb4';

// MySQLi connection (used for CI/CD test)
$conn = mysqli_connect($host, $username, $password, $dbname, $port);
if (!$conn) {
    die("MySQLi Connection failed: " . mysqli_connect_error());
}

// PDO connection
$dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
    // echo "✅ Connected successfully"; // test line
} catch (\PDOException $e) {
    exit('PDO Connection failed: ' . $e->getMessage());
}
?>
