<?php
declare(strict_types=1);

function json_response(array $payload, int $status = 200): void
{
  http_response_code($status);
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode($payload);
  exit;
}

function require_post_fields(array $fields): void
{
  foreach ($fields as $f) {
    if (!isset($_POST[$f])) {
      json_response(['error' => "Missing field: {$f}"], 400);
    }
  }
}

function sanitize_int($v, int $default = 0): int
{
  if ($v === null) return $default;
  $i = filter_var($v, FILTER_VALIDATE_INT);
  return $i === false ? $default : (int)$i;
}

