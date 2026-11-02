CREATE DATABASE IF NOT EXISTS opportunitedimigration
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE opportunitedimigration;

CREATE TABLE IF NOT EXISTS enquiries (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  full_name VARCHAR(120) NOT NULL,
  email VARCHAR(254) NOT NULL,
  phone VARCHAR(40) NOT NULL,
  destination VARCHAR(80) NOT NULL,
  project_type ENUM('study','work','travel','immigration','other') NOT NULL,
  message TEXT NULL,
  consent_given TINYINT(1) NOT NULL DEFAULT 0,
  preferred_language CHAR(2) NOT NULL DEFAULT 'fr',
  status ENUM('new','in_progress','answered','closed') NOT NULL DEFAULT 'new',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  INDEX idx_enquiries_created_at (created_at),
  INDEX idx_enquiries_status (status),
  INDEX idx_enquiries_email (email)
) ENGINE=InnoDB;
