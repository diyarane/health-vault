<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

$pageTitle = 'About Us';
$page = get_page_content('about');
include __DIR__ . '/../includes/header.php';
?>

<div class="panel">
  <h2><?= h($page['title'] ?? 'About Us') ?></h2>
  <?php if ($page): ?>
    <p><?= nl2br(h($page['content'])) ?></p>
    <p class="text-muted" style="margin-top:20px;">Last updated: <?= h(format_datetime($page['updated_at'])) ?></p>
  <?php else: ?>
    <p class="text-muted">Content coming soon.</p>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
