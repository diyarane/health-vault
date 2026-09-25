<?php
$adminBase = admin_base_url();
$script = $_SERVER['SCRIPT_NAME'] ?? '';
function nav_active(string $needle, string $script): string
{
    return strpos($script, $needle) !== false ? 'active' : '';
}
?>
<aside class="admin-sidebar">
  <div class="admin-brand">✚ <?= h(APP_NAME) ?></div>
  <nav class="admin-nav">
    <a class="<?= nav_active('/admin/dashboard.php', $script) ?>" href="<?= h($adminBase) ?>/dashboard.php">Dashboard</a>
    <a class="<?= nav_active('/admin/medical-cards/', $script) ?>" href="<?= h($adminBase) ?>/medical-cards/index.php">Medical Cards</a>
    <a class="<?= nav_active('/admin/inquiries/', $script) ?>" href="<?= h($adminBase) ?>/inquiries/index.php">Inquiries</a>
    <a class="<?= nav_active('/admin/pages/', $script) ?>" href="<?= h($adminBase) ?>/pages/index.php">Pages</a>
    <a class="<?= nav_active('/admin/reports/', $script) ?>" href="<?= h($adminBase) ?>/reports/index.php">Reports</a>
    <a class="<?= nav_active('/admin/profile.php', $script) ?>" href="<?= h($adminBase) ?>/profile.php">Profile</a>
    <a class="<?= nav_active('/admin/change-password.php', $script) ?>" href="<?= h($adminBase) ?>/change-password.php">Change Password</a>
  </nav>
</aside>
