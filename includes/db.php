<?php
/**
 * Health Vault - Database Connection
 *
 * Provides a single shared PDO instance via get_db().
 * Uses PDO exceptions so calling code can catch failures explicitly;
 * uncaught PDO exceptions are handled by the global handler below so
 * raw SQL errors are never shown to end users.
 */

require_once __DIR__ . '/../config/config.php';

function get_db(): PDO
{
    static $pdo = null;

    if ($pdo !== null) {
        return $pdo;
    }

    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;

    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    } catch (PDOException $e) {
        // Never leak connection strings / credentials / raw DB errors to the browser.
        error_log('Health Vault DB connection failed: ' . $e->getMessage());
        http_response_code(500);
        if (APP_DEBUG) {
            die('Database connection failed: ' . htmlspecialchars($e->getMessage()));
        }
        die('A system error occurred. Please try again later.');
    }

    return $pdo;
}

/**
 * Global fallback so that any uncaught PDOException anywhere in the app
 * is converted into a friendly message instead of a raw stack trace.
 */
set_exception_handler(function (Throwable $e) {
    error_log('Uncaught exception: ' . $e->getMessage());
    http_response_code(500);
    if (defined('APP_DEBUG') && APP_DEBUG) {
        echo '<pre>Unexpected error: ' . htmlspecialchars($e->getMessage()) . '</pre>';
    } else {
        echo 'An unexpected error occurred. Please try again later.';
    }
});
