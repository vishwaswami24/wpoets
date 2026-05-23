<?php
declare(strict_types=1);

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  json_response(['error' => 'Method not allowed'], 405);
}

require_post_fields(['title', 'web_image_path', 'mobile_bg_image_path', 'sort_order']);

$title = trim((string)$_POST['title']);
$web_image_path = trim((string)$_POST['web_image_path']);
$mobile_bg_image_path = trim((string)$_POST['mobile_bg_image_path']);
$sort_order = sanitize_int($_POST['sort_order'], 0);

if ($title === '' || $web_image_path === '') {
  json_response(['error' => 'title and web_image_path are required'], 400);
}

try {
  $stmt = $pdo->prepare(
    'INSERT INTO sliders (title, web_image_path, mobile_bg_image_path, sort_order) VALUES (:title, :web, :mobile, :sort)'
  );
  $stmt->execute([
    ':title' => $title,
    ':web' => $web_image_path,
    ':mobile' => $mobile_bg_image_path !== '' ? $mobile_bg_image_path : null,
    ':sort' => $sort_order,
  ]);

  json_response(['ok' => true, 'id' => (int)$pdo->lastInsertId()]);
} catch (Throwable $e) {
  json_response(['error' => 'Failed to create'], 500);
}

