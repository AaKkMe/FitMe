<?php
/**
 * Run after schema.sql - Updates admin/trainer with proper password hashes
 * Access once: http://yourserver/FitMe/database/seed_admin.php
 * Then DELETE this file for security
 */
require_once __DIR__ . '/../config/database.php';

$pdo = getDBConnection();

$passwords = [
    'admin@fitme.com' => password_hash('admin123', PASSWORD_DEFAULT),
    'trainer@fitme.com' => password_hash('trainer123', PASSWORD_DEFAULT)
];

foreach ($passwords as $email => $hash) {
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
    $stmt->execute([$hash, $email]);
}

echo "Passwords updated. DELETE this file now!";
