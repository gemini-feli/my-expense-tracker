<?php
require 'db.php';

$stmt = $pdo->query("SELECT DATABASE() AS db");
$row = $stmt->fetch();
echo "Connected to database: " . $row['db'];