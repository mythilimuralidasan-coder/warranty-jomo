-- JOMO Warranty Claim System — MySQL schema
-- Run this once in phpMyAdmin (Hostinger hPanel → Databases → phpMyAdmin)
-- against the database you created for this site. Safe to re-run: every
-- statement uses IF NOT EXISTS.

CREATE TABLE IF NOT EXISTS warranty_claims (
  id CHAR(36) NOT NULL PRIMARY KEY,
  claim_number VARCHAR(32) NOT NULL,
  customer_name VARCHAR(191) NOT NULL,
  phone VARCHAR(32) NOT NULL,
  email VARCHAR(191) NULL,
  description TEXT NOT NULL,
  status ENUM(
    'NEW', 'UNDER_REVIEW', 'VISIT_SCHEDULED', 'ASSIGNED',
    'IN_PROGRESS', 'RESOLVED', 'CLOSED', 'REJECTED'
  ) NOT NULL DEFAULT 'NEW',
  assigned_to VARCHAR(191) NULL,
  resolution_notes TEXT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_claim_number (claim_number),
  KEY idx_status (status),
  KEY idx_created_at (created_at),
  KEY idx_phone (phone)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS warranty_claim_files (
  id CHAR(36) NOT NULL PRIMARY KEY,
  claim_id CHAR(36) NOT NULL,
  file_name VARCHAR(255) NOT NULL,
  storage_path VARCHAR(500) NOT NULL,
  file_type VARCHAR(100) NOT NULL,
  file_size BIGINT UNSIGNED NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_claim_id (claim_id),
  CONSTRAINT fk_warranty_claim_files_claim
    FOREIGN KEY (claim_id) REFERENCES warranty_claims (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Backs the atomic JOMO-WAR-<year>-<0001> claim-number generator.
CREATE TABLE IF NOT EXISTS warranty_counters (
  year INT NOT NULL PRIMARY KEY,
  counter INT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Admin/staff accounts for the warranty admin panel (admin/warranty/).
-- Create the first account once via admin/warranty/setup_admin.php, then
-- delete that file.
CREATE TABLE IF NOT EXISTS warranty_admins (
  id CHAR(36) NOT NULL PRIMARY KEY,
  username VARCHAR(100) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
