<?php
try {
  $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=wpoets;charset=utf8mb4', 'root', '');
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Add missing columns if not exist
  $cols = $pdo->query("SHOW COLUMNS FROM sliders")->fetchAll(PDO::FETCH_COLUMN);
  if (!in_array('badge', $cols))
    $pdo->exec("ALTER TABLE sliders ADD COLUMN badge VARCHAR(255) NULL AFTER title");
  if (!in_array('description', $cols))
    $pdo->exec("ALTER TABLE sliders ADD COLUMN description VARCHAR(512) NULL AFTER badge");
  if (!in_array('learn_more_url', $cols))
    $pdo->exec("ALTER TABLE sliders ADD COLUMN learn_more_url VARCHAR(512) NULL AFTER description");

  echo "Schema updated\n";

  // Seed
  $pdo->exec("DELETE FROM sliders");
  $pdo->exec("INSERT INTO sliders (title, badge, description, web_image_path, mobile_bg_image_path, sort_order, learn_more_url) VALUES
    ('Learning',      'Digital Learning Infrastructure', 'Usability enhancement and Training for Transaction Portal for Customers', 'files/images/DL-Learning-1.jpg',    'files/images/DL-learning.svg',      1, '#'),
    ('Technology',    'Digital Learning Infrastructure', 'Usability enhancement and Training for Transaction Portal for Customers', 'files/images/DL-Technology.jpg',    'files/images/DL-technology.svg',    2, '#'),
    ('Communication', 'Digital Learning Infrastructure', 'Usability enhancement and Training for Transaction Portal for Customers', 'files/images/DL-Communication.jpg', 'files/images/DL-communication.svg', 3, '#')
  ");
  echo "Seeded OK\n";
} catch (Exception $e) {
  echo "ERROR: " . $e->getMessage() . "\n";
}
