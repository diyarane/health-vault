<?php
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';
require_admin_login();

$db = get_db();
$search = trim($_GET['reference'] ?? '');

if ($search !== '') {
    $stmt = $db->prepare(
        'SELECT * FROM medical_cards WHERE reference_number LIKE :ref OR full_name LIKE :ref2 ORDER BY created_at DESC'
    );
    $like = '%' . $search . '%';
    $stmt->execute(['ref' => $like, 'ref2' => $like]);
    $cards = $stmt->fetchAll();
} else {
    $cards = $db->query('SELECT * FROM medical_cards ORDER BY created_at DESC')->fetchAll();
}

$adminBase = admin_base_url();
$pageTitle = 'Medical Cards';
include __DIR__ . '/../../includes/admin-header.php';
?>

<div class="page-header">
  <form method="get" action="" class="search-form">
    <input type="text" name="reference" placeholder="Search by reference number or name" value="<?= h($search) ?>">
    <button type="submit" class="btn btn-outline">Search</button>
    <?php if ($search !== ''): ?>
      <a class="btn btn-outline" href="index.php">Clear</a>
    <?php endif; ?>
  </form>
  <a class="btn" href="add.php">+ Add Medical Card</a>
</div>

<div class="panel">
  <?php if (empty($cards)): ?>
    <p class="empty-state">
      <?= $search !== '' ? 'No medical cards match "' . h($search) . '".' : 'No medical cards yet. Add your first one.' ?>
    </p>
  <?php else: ?>
    <div class="table-responsive">
    <table class="table">
      <thead>
        <tr><th>Reference</th><th>Full Name</th><th>Contact</th><th>Email</th><th>Created</th><th>Actions</th></tr>
      </thead>
      <tbody>
        <?php foreach ($cards as $card): ?>
        <tr>
          <td><?= h($card['reference_number']) ?></td>
          <td><?= h($card['full_name']) ?></td>
          <td><?= h($card['contact_number']) ?></td>
          <td><?= h($card['email']) ?></td>
          <td><?= h(format_date($card['created_at'])) ?></td>
          <td class="table-actions">
            <a class="btn btn-sm btn-outline" href="view.php?id=<?= (int) $card['id'] ?>">View</a>
            <a class="btn btn-sm btn-outline" href="edit.php?id=<?= (int) $card['id'] ?>">Edit</a>
            <form method="post" action="delete.php" style="display:inline;">
              <?= csrf_field() ?>
              <input type="hidden" name="id" value="<?= (int) $card['id'] ?>">
              <button type="submit" class="btn btn-sm btn-danger" data-confirm="Delete medical card for <?= h($card['full_name']) ?>? This cannot be undone.">Delete</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    </div>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/../../includes/admin-footer.php'; ?>
