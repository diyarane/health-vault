<?php
/**
 * Health Vault - Application Configuration
 *
 * Copy/edit these values to match your local XAMPP/WAMP/MAMP MySQL setup.
 * Never commit real production credentials to version control.
 */

// ----- Database configuration -----
define('DB_HOST', getenv('HV_DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('HV_DB_NAME') ?: 'health_vault');
define('DB_USER', getenv('HV_DB_USER') ?: 'root');
define('DB_PASS', getenv('HV_DB_PASS') ?: '');
define('DB_CHARSET', 'utf8mb4');

// ----- Application configuration -----
define('APP_NAME', 'Health Vault');
// Base URL of the application (no trailing slash). Update if deployed in a
// subfolder, e.g. 'http://localhost/health-vault'
define('APP_URL', '');

// Password reset token lifetime, in minutes
define('RESET_TOKEN_TTL_MINUTES', 30);

// ----- Environment -----
// Set to false in a real production deployment.
define('APP_DEBUG', true);

// ----- Session -----
// How long (seconds) an admin session may sit idle before being logged out.
define('ADMIN_SESSION_TIMEOUT', 60 * 60 * 2); // 2 hours

date_default_timezone_set('UTC');
