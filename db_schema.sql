-- MySQL schema for slider CRUD
-- Usage: execute this file in your MySQL database.

CREATE TABLE IF NOT EXISTS sliders (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  title VARCHAR(255) NOT NULL,
  web_image_path VARCHAR(512) NOT NULL,
  mobile_bg_image_path VARCHAR(512) NULL,
  sort_order INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE INDEX idx_sliders_sort_order ON sliders (sort_order, id);

