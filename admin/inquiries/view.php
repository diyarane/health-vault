<?php
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';
require_admin_login();

$db = get_db();
$id = (int) ($_GET['id'] ?? 0);

$stmt = $db->prepare('SELECT * FROM inquiries WHERE id = :id LIMIT 1');
$stmt->execute(['id' => $id]);
$inquiry = $stmt->fetch();

if (!$inquiry) {
    flash_set('error', 'Inquiry not found.');
    redirect(admin_base_url() . '/inquiries/index.php');
}

// Consistent behavior: opening an inquiry automatically marks it as read.
if ($inquiry['status'] === 'unread') {
    $upd = $db->prepare("UPDATE inquiries SET status = 'read', updated_at = NOW() WHERE id = :id");
    $upd->execute(['id' => $id]);
    $inquiry['status'] = 'read';
}

$adminBase = admin_base_url();
$pageTitle = 'Inquiry from ' . $inquiry['name'];
include __DIR__ . '/../../includes/admin-header.php';
?>

<div class="page-header">
  <h2 class="mt-0 mb-0">Inquiry from <?= h($inquiry['name']) ?></h2>
  <div class="table-actions">
    <?php if ($inquiry['status'] === 'read'): ?>
      <form method="post" action="respond.php">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= (int) $id ?>">
        <input type="hidden" name="action" value="mark_unread">
        <button type="submit" class="btn btn-sm btn-outline">Mark as Unread</button>
      </form>
    <?php else: ?>
      <form method="post" action="respond.php">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= (int) $id ?>">
        <input type="hidden" name="action" value="mark_read">
        <button type="submit" class="btn btn-sm btn-outline">Mark as Read</button>
      </form>
    <?php endif; ?>
    <a class="btn btn-sm btn-outline" href="index.php">← Back to list</a>
  </div>
</div>

<div class="panel" style="max-width:720px;">
  <dl style="display:grid;grid-template-columns:140px 1fr;row-gap:10px;">
    <dt style="font-weight:600;color:#6b7280;">Name</dt><dd><?= h($inquiry['name']) ?></dd>
    <dt style="font-weight:600;color:#6b7280;">Email</dt><dd><?= h($inquiry['email']) ?></dd>
    <dt style="font-weight:600;color:#6b7280;">Subject</dt><dd><?= h($inquiry['subject'] ?: '—') ?></dd>
    <dt style="font-weight:600;color:#6b7280;">Status</dt><dd><span class="badge badge-<?= h($inquiry['status']) ?>"><?= h(ucfirst($inquiry['status'])) ?></span></dd>
    <dt style="font-weight:600;color:#6b7280;">Received</dt><dd><?= h(format_datetime($inquiry['created_at'])) ?></dd>
  </dl>
  <hr style="margin:18px 0;border:none;border-top:1px solid #e5e7eb;">
  <h3>Message</h3>
  <p><?= nl2br(h($inquiry['message'])) ?></p>
</div>

<div class="panel" style="max-width:720px;">
  <h3>Admin Response</h3>
  <?php if (!empty($inquiry['admin_response'])): ?>
    <div class="alert alert-info">
      <strong>Sent <?= h(format_datetime($inquiry['responded_at'])) ?>:</strong><br>
      <?= nl2br(h($inquiry['admin_response'])) ?>
    </div>
    <p class="text-muted">No external email service is configured in this build — the response above is stored
      and visible here, but is not automatically emailed to the sender.</p>
  <?php endif; ?>

  <form method="post" action="respond.php">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= (int) $id ?>">
    <input type="hidden" name="action" value="respond">
    <div class="form-group">
      <label for="admin_response"><?= !empty($inquiry['admin_response']) ? 'Update Response' : 'Write a Response' ?></label>
      <textarea id="admin_response" name="admin_response"><?= h($inquiry['admin_response'] ?? '') ?></textarea>
    </div>
    <div class="form-actions">
      <button type="submit" class="btn">Save Response</button>
    </div>
  </form>
</div>

<?php include __DIR__ . '/../../includes/admin-footer.php'; ?>
