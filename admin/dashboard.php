<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_admin_login();

$db = get_db();

$totalCards     = db_count('SELECT COUNT(*) FROM medical_cards');
$todayCards     = db_count('SELECT COUNT(*) FROM medical_cards WHERE DATE(created_at) = CURDATE()');
$yesterdayCards = db_count('SELECT COUNT(*) FROM medical_cards WHERE DATE(created_at) = CURDATE() - INTERVAL 1 DAY');
$last7Cards     = db_count('SELECT COUNT(*) FROM medical_cards WHERE created_at >= (NOW() - INTERVAL 7 DAY)');
$unreadCount    = db_count("SELECT COUNT(*) FROM inquiries WHERE status = 'unread'");
$readCount      = db_count("SELECT COUNT(*) FROM inquiries WHERE status = 'read'");

$recentCards = $db->query(
    'SELECT reference_number, full_name, email, created_at FROM medical_cards ORDER BY created_at DESC LIMIT 5'
)->fetchAll();

$recentInquiries = $db->query(
    'SELECT id, name, subject, status, created_at FROM inquiries ORDER BY created_at DESC LIMIT 5'
)->fetchAll();

$adminBase = admin_base_url();
$pageTitle = 'Dashboard';
include __DIR__ . '/../includes/admin-header.php';
?>

<div class="stat-grid">
  <a class="stat-card" href="<?= h($adminBase) ?>/medical-cards/index.php">
    <div class="stat-value"><?= (int) $totalCards ?></div>
    <div class="stat-label">Total Medical Cards</div>
  </a>
  <a class="stat-card" href="<?= h($adminBase) ?>/reports/index.php?from=<?= h(date('Y-m-d')) ?>&to=<?= h(date('Y-m-d')) ?>">
    <div class="stat-value"><?= (int) $todayCards ?></div>
    <div class="stat-label">Created Today</div>
  </a>
  <a class="stat-card" href="<?= h($adminBase) ?>/reports/index.php?from=<?= h(date('Y-m-d', strtotime('-1 day'))) ?>&to=<?= h(date('Y-m-d', strtotime('-1 day'))) ?>">
    <div class="stat-value"><?= (int) $yesterdayCards ?></div>
    <div class="stat-label">Created Yesterday</div>
  </a>
  <a class="stat-card" href="<?= h($adminBase) ?>/reports/index.php?from=<?= h(date('Y-m-d', strtotime('-7 days'))) ?>&to=<?= h(date('Y-m-d')) ?>">
    <div class="stat-value"><?= (int) $last7Cards ?></div>
    <div class="stat-label">Last 7 Days</div>
  </a>
  <a class="stat-card" href="<?= h($adminBase) ?>/inquiries/index.php?status=unread">
    <div class="stat-value"><?= (int) $unreadCount ?></div>
    <div class="stat-label">Unread Inquiries</div>
  </a>
  <a class="stat-card" href="<?= h($adminBase) ?>/inquiries/index.php?status=read">
    <div class="stat-value"><?= (int) $readCount ?></div>
    <div class="stat-label">Read Inquiries</div>
  </a>
</div>

<div class="form-row" style="align-items:start;">
  <div class="panel">
    <h2>Recent Medical Cards</h2>
    <?php if (empty($recentCards)): ?>
      <p class="empty-state">No medical cards yet. <a href="<?= h($adminBase) ?>/medical-cards/add.php">Add one now</a>.</p>
    <?php else: ?>
      <div class="table-responsive">
      <table class="table">
        <thead><tr><th>Reference</th><th>Name</th><th>Created</th></tr></thead>
        <tbody>
          <?php foreach ($recentCards as $c): ?>
          <tr>
            <td><?= h($c['reference_number']) ?></td>
            <td><?= h($c['full_name']) ?></td>
            <td><?= h(format_datetime($c['created_at'])) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      </div>
      <p style="margin-top:12px;"><a href="<?= h($adminBase) ?>/medical-cards/index.php">View all cards →</a></p>
    <?php endif; ?>
  </div>

  <div class="panel">
    <h2>Recent Inquiries</h2>
    <?php if (empty($recentInquiries)): ?>
      <p class="empty-state">No inquiries yet.</p>
    <?php else: ?>
      <div class="table-responsive">
      <table class="table">
        <thead><tr><th>Name</th><th>Subject</th><th>Status</th><th>Received</th></tr></thead>
        <tbody>
          <?php foreach ($recentInquiries as $i): ?>
          <tr>
            <td><a href="<?= h($adminBase) ?>/inquiries/view.php?id=<?= (int) $i['id'] ?>"><?= h($i['name']) ?></a></td>
            <td><?= h($i['subject'] ?: '—') ?></td>
            <td><span class="badge badge-<?= h($i['status']) ?>"><?= h(ucfirst($i['status'])) ?></span></td>
            <td><?= h(format_datetime($i['created_at'])) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      </div>
      <p style="margin-top:12px;"><a href="<?= h($adminBase) ?>/inquiries/index.php">View all inquiries →</a></p>
    <?php endif; ?>
  </div>
</div>

<?php include __DIR__ . '/../includes/admin-footer.php'; ?>
