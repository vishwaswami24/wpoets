<?php
declare(strict_types=1);

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';

try {
  $stmt = $pdo->query('SELECT id, title, badge, description, learn_more_url, web_image_path, mobile_bg_image_path, sort_order FROM sliders ORDER BY sort_order ASC, id ASC');
  $rows = $stmt->fetchAll();
  json_response(['items' => $rows]);
} catch (Throwable $e) {
  json_response(['error' => 'Failed to load items'], 500);
}

