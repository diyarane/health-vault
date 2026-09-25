-- ============================================================
-- Health Vault — Database Schema
-- Run this first, then database/seed.sql
-- ============================================================

CREATE DATABASE IF NOT EXISTS health_vault CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE health_vault;

-- ------------------------------------------------------------
-- admins
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS admins (
    id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name           VARCHAR(150) NOT NULL,
    email          VARCHAR(190) NOT NULL,
    mobile_number  VARCHAR(30)  DEFAULT NULL,
    password       VARCHAR(255) NOT NULL,
    created_at     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_admins_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- medical_cards
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS medical_cards (
    id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    reference_number VARCHAR(40)  NOT NULL,
    full_name        VARCHAR(150) NOT NULL,
    date_of_birth    DATE         DEFAULT NULL,
    gender           VARCHAR(20)  DEFAULT NULL,
    blood_group      VARCHAR(10)  DEFAULT NULL,
    contact_number   VARCHAR(30)  NOT NULL,
    email            VARCHAR(190) NOT NULL,
    address          VARCHAR(255) DEFAULT NULL,
    emergency_contact_name   VARCHAR(150) DEFAULT NULL,
    emergency_contact_number VARCHAR(30)  DEFAULT NULL,
    known_allergies  VARCHAR(255) DEFAULT NULL,
    notes            TEXT         DEFAULT NULL,
    created_at       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_medical_cards_reference (reference_number),
    KEY idx_medical_cards_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- inquiries
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS inquiries (
    id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name           VARCHAR(150) NOT NULL,
    email          VARCHAR(190) NOT NULL,
    subject        VARCHAR(190) DEFAULT NULL,
    message        TEXT NOT NULL,
    status         ENUM('unread','read') NOT NULL DEFAULT 'unread',
    admin_response TEXT DEFAULT NULL,
    responded_at   DATETIME DEFAULT NULL,
    created_at     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY idx_inquiries_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- pages  (editable content for About Us / Contact Us)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS pages (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug        VARCHAR(60) NOT NULL,
    title       VARCHAR(190) NOT NULL,
    content     TEXT NOT NULL,
    -- extra fields shown on the public Contact Us page
    contact_email    VARCHAR(190) DEFAULT NULL,
    contact_phone    VARCHAR(30)  DEFAULT NULL,
    contact_address  VARCHAR(255) DEFAULT NULL,
    updated_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_pages_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- password_resets
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS password_resets (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    admin_id    INT UNSIGNED NOT NULL,
    token_hash  VARCHAR(255) NOT NULL,
    expires_at  DATETIME NOT NULL,
    used_at     DATETIME DEFAULT NULL,
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY idx_password_resets_admin (admin_id),
    CONSTRAINT fk_password_resets_admin FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
