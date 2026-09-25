<?php
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';
require_admin_login();

$id = (int) ($_GET['id'] ?? 0);
$stmt = get_db()->prepare('SELECT * FROM medical_cards WHERE id = :id LIMIT 1');
$stmt->execute(['id' => $id]);
$card = $stmt->fetch();

if (!$card) {
    flash_set('error', 'Medical card not found.');
    redirect(admin_base_url() . '/medical-cards/index.php');
}

$adminBase = admin_base_url();
$pageTitle = 'Medical Card — ' . $card['reference_number'];
include __DIR__ . '/../../includes/admin-header.php';
?>

<div class="page-header">
  <h2 class="mt-0 mb-0"><?= h($card['full_name']) ?></h2>
  <div class="table-actions">
    <a class="btn btn-outline" href="edit.php?id=<?= (int) $card['id'] ?>">Edit</a>
    <a class="btn btn-outline" target="_blank"
       href="<?= h(site_base_url()) ?>/public/print-card.php?reference=<?= urlencode($card['reference_number']) ?>">🖨️ Print</a>
    <a class="btn btn-outline" href="index.php">← Back to list</a>
  </div>
</div>

<div class="medical-card">
  <div class="medical-card-header">
    <h2>Health Vault Medical Card</h2>
    <span class="medical-card-ref"><?= h($card['reference_number']) ?></span>
  </div>
  <dl>
    <dt>Full Name</dt><dd><?= h($card['full_name']) ?></dd>
    <dt>Date of Birth</dt><dd><?= h(format_date($card['date_of_birth'])) ?></dd>
    <dt>Gender</dt><dd><?= h($card['gender'] ?: '—') ?></dd>
    <dt>Blood Group</dt><dd><?= h($card['blood_group'] ?: '—') ?></dd>
    <dt>Contact Number</dt><dd><?= h($card['contact_number']) ?></dd>
    <dt>Email</dt><dd><?= h($card['email']) ?></dd>
    <dt>Address</dt><dd><?= h($card['address'] ?: '—') ?></dd>
    <dt>Emergency Contact</dt>
    <dd><?= h($card['emergency_contact_name'] ?: '—') ?><?= $card['emergency_contact_number'] ? ' (' . h($card['emergency_contact_number']) . ')' : '' ?></dd>
    <dt>Known Allergies</dt><dd><?= h($card['known_allergies'] ?: 'None recorded') ?></dd>
    <dt>Notes</dt><dd><?= nl2br(h($card['notes'] ?: '—')) ?></dd>
    <dt>Created</dt><dd><?= h(format_datetime($card['created_at'])) ?></dd>
    <dt>Last Updated</dt><dd><?= h(format_datetime($card['updated_at'])) ?></dd>
  </dl>
</div>

<?php include __DIR__ . '/../../includes/admin-footer.php'; ?>
