<?php
declare(strict_types=1);
/*
 * Local XAMPP development settings.
 * For production, keep credentials in environment variables and outside the public web root.
 */
$dbHost = '127.0.0.1';
$dbName = 'opportunitedimigration';
$dbUser = 'root';
$dbPass = ''; // Default local XAMPP MySQL/MariaDB setup often has an empty password. Change if yours differs.

$dsn = "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4";
$pdo = new PDO($dsn, $dbUser, $dbPass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
]);
