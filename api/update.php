<?php
declare(strict_types=1);

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  json_response(['error' => 'Method not allowed'], 405);
}

require_post_fields(['id', 'title', 'web_image_path', 'mobile_bg_image_path', 'sort_order']);

$id = sanitize_int($_POST['id'], 0);
$title = trim((string)$_POST['title']);
$web_image_path = trim((string)$_POST['web_image_path']);
$mobile_bg_image_path = trim((string)$_POST['mobile_bg_image_path']);
$sort_order = sanitize_int($_POST['sort_order'], 0);

if ($id <= 0 || $title === '' || $web_image_path === '') {
  json_response(['error' => 'Invalid input'], 400);
}

try {
  $stmt = $pdo->prepare(
    'UPDATE sliders SET title = :title, web_image_path = :web, mobile_bg_image_path = :mobile, sort_order = :sort WHERE id = :id'
  );
  $stmt->execute([
    ':title' => $title,
    ':web' => $web_image_path,
    ':mobile' => $mobile_bg_image_path !== '' ? $mobile_bg_image_path : null,
    ':sort' => $sort_order,
    ':id' => $id,
  ]);

  json_response(['ok' => true]);
} catch (Throwable $e) {
  json_response(['error' => 'Failed to update'], 500);
}

