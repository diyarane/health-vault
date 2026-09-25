<?php
/**
 * Health Vault - Shared helper functions
 */

require_once __DIR__ . '/db.php';

/**
 * Compute the base URL path of the application (e.g. '/health-vault' or '').
 * Has no trailing slash.
 */
function site_base_url(): string
{
    if (defined('APP_URL') && APP_URL !== '') {
        $path = parse_url(APP_URL, PHP_URL_PATH);
        if ($path !== null && $path !== '') {
            return rtrim($path, '/');
        }
    }

    $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');

    if (($pos = strpos($script, '/public/')) !== false) {
        return substr($script, 0, $pos);
    }
    if (($pos = strpos($script, '/admin/')) !== false) {
        return substr($script, 0, $pos);
    }
    if (substr($script, -7) === '/public') {
        return substr($script, 0, -7);
    }
    if (substr($script, -6) === '/admin') {
        return substr($script, 0, -6);
    }

    $dir = dirname($script);
    return ($dir === '/' || $dir === '\\') ? '' : rtrim($dir, '/');
}

/**
 * Base URL path for the admin area without trailing slash (e.g. '/health-vault/admin').
 */
function admin_base_url(): string
{
    return site_base_url() . '/admin';
}

/** Escape a string for safe HTML output. */
function h(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/** Redirect to a given path relative to the site root and stop execution. */
function redirect(string $path): void
{
    if (!preg_match('#^https?://#i', $path) && strpos($path, '/') !== 0) {
        $path = site_base_url() . '/' . ltrim($path, '/');
    }
    header('Location: ' . $path);
    exit;
}

/** Generate (and store in session) a CSRF token, or return the existing one. */
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/** Render a hidden CSRF input field. */
function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . h(csrf_token()) . '">';
}

/** Validate a submitted CSRF token; halts the request with 400 on failure. */
function verify_csrf(): void
{
    $submitted = $_POST['csrf_token'] ?? '';
    if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $submitted)) {
        http_response_code(400);
        die('Invalid or expired form submission. Please go back, refresh the page, and try again.');
    }
}

/** Flash message helpers (one-time messages shown after redirect). */
function flash_set(string $type, string $message): void
{
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function flash_get_all(): array
{
    $messages = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $messages;
}

/** Generate a unique medical card reference number, e.g. HV-2026-8F3K9Q. */
function generate_reference_number(): string
{
    $year = date('Y');
    $random = strtoupper(substr(bin2hex(random_bytes(4)), 0, 6));
    return "HV-{$year}-{$random}";
}

/** Basic email format check. */
function is_valid_email(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/** Format a datetime string for display. */
function format_datetime(?string $datetime, string $format = 'M j, Y g:i A'): string
{
    if (empty($datetime)) {
        return '—';
    }
    $ts = strtotime($datetime);
    return $ts ? date($format, $ts) : '—';
}

function format_date(?string $date, string $format = 'M j, Y'): string
{
    return format_datetime($date, $format);
}

/** Fetch a single "page" record (About Us / Contact Us content) by slug. */
function get_page_content(string $slug): ?array
{
    $stmt = get_db()->prepare('SELECT * FROM pages WHERE slug = :slug LIMIT 1');
    $stmt->execute(['slug' => $slug]);
    $row = $stmt->fetch();
    return $row ?: null;
}

/** Simple wrapper to count rows from a query with parameters. */
function db_count(string $sql, array $params = []): int
{
    $stmt = get_db()->prepare($sql);
    $stmt->execute($params);
    return (int) $stmt->fetchColumn();
}
