<?php
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';
require_admin_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(admin_base_url() . '/medical-cards/index.php');
}

verify_csrf();
$id = (int) ($_POST['id'] ?? 0);

$stmt = get_db()->prepare('SELECT reference_number FROM medical_cards WHERE id = :id LIMIT 1');
$stmt->execute(['id' => $id]);
$card = $stmt->fetch();

if (!$card) {
    flash_set('error', 'Medical card not found.');
} else {
    $del = get_db()->prepare('DELETE FROM medical_cards WHERE id = :id');
    $del->execute(['id' => $id]);
    flash_set('success', 'Medical card "' . $card['reference_number'] . '" was deleted.');
}

redirect(admin_base_url() . '/medical-cards/index.php');
