<?php
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';
require_admin_login();

$db = get_db();
$from = trim($_GET['from'] ?? '');
$to   = trim($_GET['to'] ?? '');
$errors = [];
$results = null;

$generated = ($from !== '' || $to !== '');

if ($generated) {
    if ($from === '' || !strtotime($from)) {
        $errors[] = 'Please provide a valid "From" date.';
    }
    if ($to === '' || !strtotime($to)) {
        $errors[] = 'Please provide a valid "To" date.';
    }
    if (empty($errors) && strtotime($from) > strtotime($to)) {
        $errors[] = 'The "From" date must not be later than the "To" date.';
    }

    if (empty($errors)) {
        $stmt = $db->prepare(
            'SELECT * FROM medical_cards
             WHERE DATE(created_at) BETWEEN :from AND :to
             ORDER BY created_at ASC'
        );
        $stmt->execute(['from' => $from, 'to' => $to]);
        $results = $stmt->fetchAll();
    }
}

$adminBase = admin_base_url();
$pageTitle = 'Reports';
include __DIR__ . '/../../includes/admin-header.php';
?>

<div class="panel no-print">
  <h2>Medical Card Activity Report</h2>
  <form method="get" action="">
    <div class="form-row">
      <div class="form-group">
        <label for="from">From Date</label>
        <input type="date" id="from" name="from" value="<?= h($from) ?>">
      </div>
      <div class="form-group">
        <label for="to">To Date</label>
        <input type="date" id="to" name="to" value="<?= h($to) ?>">
      </div>
    </div>
    <div class="form-actions">
      <button type="submit" class="btn">Generate Report</button>
      <?php if ($generated): ?><a class="btn btn-outline" href="index.php">Clear</a><?php endif; ?>
    </div>
  </form>
  <?php foreach ($errors as $err): ?><div class="alert alert-error"><?= h($err) ?></div><?php endforeach; ?>
</div>

<?php if ($generated && empty($errors)): ?>
  <div class="panel">
    <div class="page-header no-print">
      <h2 class="mt-0 mb-0">Results: <?= h(format_date($from)) ?> — <?= h(format_date($to)) ?></h2>
      <button class="btn btn-outline" onclick="window.print()">🖨️ Print Report</button>
    </div>

    <?php if (empty($results)): ?>
      <p class="empty-state">No medical cards were found for the selected date range.</p>
    <?php else: ?>
      <p><strong>Total records:</strong> <?= count($results) ?></p>
      <div class="table-responsive">
      <table class="table">
        <thead>
          <tr><th>Reference Number</th><th>Full Name</th><th>Contact Number</th><th>Email</th><th>Creation Date</th></tr>
        </thead>
        <tbody>
          <?php foreach ($results as $r): ?>
          <tr>
            <td><?= h($r['reference_number']) ?></td>
            <td><?= h($r['full_name']) ?></td>
            <td><?= h($r['contact_number']) ?></td>
            <td><?= h($r['email']) ?></td>
            <td><?= h(format_datetime($r['created_at'])) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      </div>
    <?php endif; ?>
  </div>
<?php endif; ?>

<?php include __DIR__ . '/../../includes/admin-footer.php'; ?>
