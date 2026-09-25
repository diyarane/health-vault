<?php
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';
require_admin_login();

$db = get_db();
$statusFilter = $_GET['status'] ?? '';

if (in_array($statusFilter, ['unread', 'read'], true)) {
    $stmt = $db->prepare('SELECT * FROM inquiries WHERE status = :status ORDER BY created_at DESC');
    $stmt->execute(['status' => $statusFilter]);
    $inquiries = $stmt->fetchAll();
} else {
    $inquiries = $db->query('SELECT * FROM inquiries ORDER BY created_at DESC')->fetchAll();
}

$adminBase = admin_base_url();
$pageTitle = 'Inquiries';
include __DIR__ . '/../../includes/admin-header.php';
?>

<div class="page-header">
  <div class="table-actions">
    <a class="btn btn-sm <?= $statusFilter === '' ? 'btn' : 'btn-outline' ?>" href="index.php">All</a>
    <a class="btn btn-sm <?= $statusFilter === 'unread' ? 'btn' : 'btn-outline' ?>" href="index.php?status=unread">Unread</a>
    <a class="btn btn-sm <?= $statusFilter === 'read' ? 'btn' : 'btn-outline' ?>" href="index.php?status=read">Read</a>
  </div>
</div>

<div class="panel">
  <?php if (empty($inquiries)): ?>
    <p class="empty-state">No inquiries found.</p>
  <?php else: ?>
    <div class="table-responsive">
    <table class="table">
      <thead><tr><th>Name</th><th>Email</th><th>Subject</th><th>Status</th><th>Received</th><th>Actions</th></tr></thead>
      <tbody>
        <?php foreach ($inquiries as $inq): ?>
        <tr>
          <td><?= h($inq['name']) ?></td>
          <td><?= h($inq['email']) ?></td>
          <td><?= h($inq['subject'] ?: '—') ?></td>
          <td><span class="badge badge-<?= h($inq['status']) ?>"><?= h(ucfirst($inq['status'])) ?></span></td>
          <td><?= h(format_datetime($inq['created_at'])) ?></td>
          <td class="table-actions">
            <a class="btn btn-sm btn-outline" href="view.php?id=<?= (int) $inq['id'] ?>">View</a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    </div>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/../../includes/admin-footer.php'; ?>
