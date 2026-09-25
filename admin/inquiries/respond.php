<?php
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';
require_admin_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(admin_base_url() . '/inquiries/index.php');
}

verify_csrf();
$db = get_db();
$id = (int) ($_POST['id'] ?? 0);
$action = $_POST['action'] ?? '';

$stmt = $db->prepare('SELECT * FROM inquiries WHERE id = :id LIMIT 1');
$stmt->execute(['id' => $id]);
$inquiry = $stmt->fetch();

if (!$inquiry) {
    flash_set('error', 'Inquiry not found.');
    redirect(admin_base_url() . '/inquiries/index.php');
}

switch ($action) {
    case 'mark_read':
        $db->prepare("UPDATE inquiries SET status = 'read', updated_at = NOW() WHERE id = :id")->execute(['id' => $id]);
        flash_set('success', 'Inquiry marked as read.');
        break;

    case 'mark_unread':
        $db->prepare("UPDATE inquiries SET status = 'unread', updated_at = NOW() WHERE id = :id")->execute(['id' => $id]);
        flash_set('success', 'Inquiry marked as unread.');
        break;

    case 'respond':
        $response = trim($_POST['admin_response'] ?? '');
        if ($response === '') {
            flash_set('error', 'Response cannot be empty.');
        } else {
            $upd = $db->prepare(
                "UPDATE inquiries SET admin_response = :response, responded_at = NOW(), status = 'read', updated_at = NOW() WHERE id = :id"
            );
            $upd->execute(['response' => $response, 'id' => $id]);
            flash_set('success', 'Response saved. (No external email service is configured — the response is stored and visible in the admin panel.)');
        }
        break;

    default:
        flash_set('error', 'Unknown action.');
}

redirect(admin_base_url() . '/inquiries/view.php?id=' . $id);
