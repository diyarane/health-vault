<?php
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';
require_admin_login();

$pages = get_db()->query('SELECT * FROM pages ORDER BY title')->fetchAll();

$adminBase = admin_base_url();
$pageTitle = 'Page Management';
include __DIR__ . '/../../includes/admin-header.php';
?>

<div class="panel">
  <p class="text-muted">Manage the content shown on the public About Us and Contact Us pages.</p>
  <div class="table-responsive">
  <table class="table">
    <thead><tr><th>Page</th><th>Last Updated</th><th>Actions</th></tr></thead>
    <tbody>
      <?php foreach ($pages as $p): ?>
      <tr>
        <td><?= h($p['title']) ?></td>
        <td><?= h(format_datetime($p['updated_at'])) ?></td>
        <td><a class="btn btn-sm btn-outline" href="<?= h($p['slug']) ?>.php">Edit</a></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  </div>
</div>

<?php include __DIR__ . '/../../includes/admin-footer.php'; ?>
