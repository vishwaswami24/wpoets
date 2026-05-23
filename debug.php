<?php
try {
  $pdo = new PDO('mysql:host=127.0.0.1;port=3306;charset=utf8mb4', 'root', '');
  echo "Connected to MySQL OK\n";

  $pdo->exec("CREATE DATABASE IF NOT EXISTS wpoets CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
  echo "Database 'wpoets' ready\n";

  $pdo->exec("USE wpoets");

  $pdo->exec("CREATE TABLE IF NOT EXISTS sliders (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    web_image_path VARCHAR(512) NOT NULL,
    mobile_bg_image_path VARCHAR(512) NULL,
    sort_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
  )");
  echo "Table 'sliders' ready\n";

  $count = $pdo->query("SELECT COUNT(*) FROM sliders")->fetchColumn();
  if ($count == 0) {
    $pdo->exec("INSERT INTO sliders (title, web_image_path, mobile_bg_image_path, sort_order) VALUES
      ('Communication', 'files/images/DL-Communication.jpg', 'files/images/DL-communication.svg', 1),
      ('Learning',      'files/images/DL-Learning-1.jpg',    'files/images/DL-learning.svg',      2),
      ('Technology',    'files/images/DL-Technology.jpg',    'files/images/DL-technology.svg',    3)
    ");
    echo "Seeded 3 sample rows\n";
  } else {
    echo "Table already has {$count} rows\n";
  }

  echo "Done! Delete this file after setup.\n";
} catch (Exception $e) {
  echo "ERROR: " . $e->getMessage() . "\n";
}
