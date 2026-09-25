<?php
/**
 * Health Vault - Admin authentication helpers
 */

require_once __DIR__ . '/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/** True if an admin is currently logged in (and session not expired). */
function is_admin_logged_in(): bool
{
    if (empty($_SESSION['admin_id'])) {
        return false;
    }

    // Idle session timeout.
    if (!empty($_SESSION['admin_last_activity'])
        && (time() - $_SESSION['admin_last_activity']) > ADMIN_SESSION_TIMEOUT) {
        admin_logout();
        return false;
    }

    $_SESSION['admin_last_activity'] = time();
    return true;
}

/** Force the current visitor to be a logged-in admin, or redirect to login. */
function require_admin_login(): void
{
    if (!is_admin_logged_in()) {
        $_SESSION['intended_url'] = $_SERVER['REQUEST_URI'] ?? '';
        redirect(admin_base_url() . '/login.php');
    }
}

/** Log an admin in by starting a fresh session bound to their account. */
function admin_login(array $admin): void
{
    session_regenerate_id(true);
    $_SESSION['admin_id']            = $admin['id'];
    $_SESSION['admin_name']          = $admin['name'];
    $_SESSION['admin_email']         = $admin['email'];
    $_SESSION['admin_last_activity'] = time();
}

/** Destroy the admin session. */
function admin_logout(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
}

/** Get the currently logged-in admin's full DB record, or null. */
function current_admin(): ?array
{
    if (empty($_SESSION['admin_id'])) {
        return null;
    }
    $stmt = get_db()->prepare('SELECT id, name, email, mobile_number, created_at, updated_at FROM admins WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $_SESSION['admin_id']]);
    $admin = $stmt->fetch();
    return $admin ?: null;
}

