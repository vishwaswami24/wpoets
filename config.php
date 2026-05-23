<?php
declare(strict_types=1);

// Update these for your environment.
// Example:
// $DB_HOST = '127.0.0.1';
// $DB_NAME = 'wpoets';
// $DB_USER = 'root';
// $DB_PASS = '';

$DB_HOST = '127.0.0.1';
$DB_PORT = '3306';
$DB_NAME = 'wpoets';
$DB_USER = 'root';
$DB_PASS = '';

$dsn = "mysql:host={$DB_HOST};port={$DB_PORT};dbname={$DB_NAME};charset=utf8mb4";

try {
  $pdo = new PDO(
    $dsn,
    $DB_USER,
    $DB_PASS,
    [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES => false,
    ]
  );
} catch (Throwable $e) {
  http_response_code(500);
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode(['error' => 'Database connection failed']);
  exit;
}

