<?php
declare(strict_types=1);

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  json_response(['error' => 'Method not allowed'], 405);
}

require_post_fields(['id']);

$id = sanitize_int($_POST['id'], 0);
if ($id <= 0) {
  json_response(['error' => 'Invalid id'], 400);
}

try {
  $stmt = $pdo->prepare('DELETE FROM sliders WHERE id = :id');
  $stmt->execute([':id' => $id]);
  json_response(['ok' => true]);
} catch (Throwable $e) {
  json_response(['error' => 'Failed to delete'], 500);
}

