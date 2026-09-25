<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_admin_login();

$db = get_db();
$admin = current_admin();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $current = (string) ($_POST['current_password'] ?? '');
    $newPass = (string) ($_POST['new_password'] ?? '');
    $confirm = (string) ($_POST['confirm_password'] ?? '');

    $stmt = $db->prepare('SELECT password FROM admins WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $admin['id']]);
    $row = $stmt->fetch();

    if (!password_verify($current, $row['password'])) {
        $errors[] = 'Current password is incorrect.';
    }
    if (strlen($newPass) < 8) {
        $errors[] = 'New password must be at least 8 characters long.';
    }
    if ($newPass !== $confirm) {
        $errors[] = 'New password and confirmation do not match.';
    }

    if (empty($errors)) {
        $upd = $db->prepare('UPDATE admins SET password = :password, updated_at = NOW() WHERE id = :id');
        $upd->execute(['password' => password_hash($newPass, PASSWORD_DEFAULT), 'id' => $admin['id']]);
        flash_set('success', 'Password changed successfully.');
        redirect(admin_base_url() . '/change-password.php');
    }
}

$adminBase = admin_base_url();
$pageTitle = 'Change Password';
include __DIR__ . '/../includes/admin-header.php';
?>

<div class="panel" style="max-width:480px;">
  <?php foreach ($errors as $err): ?><div class="alert alert-error"><?= h($err) ?></div><?php endforeach; ?>

  <form method="post" action="" novalidate>
    <?= csrf_field() ?>
    <div class="form-group">
      <label for="current_password">Current Password</label>
      <input type="password" id="current_password" name="current_password" required>
    </div>
    <div class="form-group">
      <label for="new_password">New Password</label>
      <input type="password" id="new_password" name="new_password" required minlength="8">
      <div class="field-hint">At least 8 characters.</div>
    </div>
    <div class="form-group">
      <label for="confirm_password">Confirm New Password</label>
      <input type="password" id="confirm_password" name="confirm_password" required minlength="8">
    </div>
    <div class="form-actions">
      <button type="submit" class="btn">Update Password</button>
    </div>
  </form>
</div>

<?php include __DIR__ . '/../includes/admin-footer.php'; ?>
