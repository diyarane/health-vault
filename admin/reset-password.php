<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

if (is_admin_logged_in()) {
    redirect(admin_base_url() . '/dashboard.php');
}

$rawToken = trim($_GET['token'] ?? $_POST['token'] ?? '');
$errors = [];
$success = false;
$tokenRecord = null;

if ($rawToken === '') {
    $errors[] = 'Missing or invalid reset token.';
} else {
    $tokenHash = hash('sha256', $rawToken);
    $stmt = get_db()->prepare(
        'SELECT * FROM password_resets WHERE token_hash = :hash LIMIT 1'
    );
    $stmt->execute(['hash' => $tokenHash]);
    $tokenRecord = $stmt->fetch();

    if (!$tokenRecord) {
        $errors[] = 'This reset link is invalid.';
    } elseif ($tokenRecord['used_at'] !== null) {
        $errors[] = 'This reset link has already been used. Please request a new one.';
    } elseif (strtotime($tokenRecord['expires_at']) < time()) {
        $errors[] = 'This reset link has expired. Please request a new one.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($errors)) {
    verify_csrf();
    $password = (string) ($_POST['password'] ?? '');
    $confirm  = (string) ($_POST['confirm_password'] ?? '');

    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters long.';
    } elseif ($password !== $confirm) {
        $errors[] = 'Passwords do not match.';
    } else {
        $db = get_db();
        $db->beginTransaction();
        try {
            $upd = $db->prepare('UPDATE admins SET password = :password, updated_at = NOW() WHERE id = :id');
            $upd->execute([
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'id'       => $tokenRecord['admin_id'],
            ]);

            $markUsed = $db->prepare('UPDATE password_resets SET used_at = NOW() WHERE id = :id');
            $markUsed->execute(['id' => $tokenRecord['id']]);

            $db->commit();
            $success = true;
        } catch (Throwable $e) {
            $db->rollBack();
            error_log('Password reset failed: ' . $e->getMessage());
            $errors[] = 'Something went wrong while resetting your password. Please try again.';
        }
    }
}

$pageTitle = 'Reset Password';
$siteBase = site_base_url();
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= h($pageTitle) ?> — <?= h(APP_NAME) ?></title>
<link rel="stylesheet" href="<?= h($siteBase) ?>/assets/css/style.css">
</head>
<body>
<main class="site-main">
  <div class="container" style="max-width:460px;">
    <div class="panel" style="margin-top:60px;">
      <h2>Reset Password</h2>

      <?php foreach ($errors as $err): ?><div class="alert alert-error"><?= h($err) ?></div><?php endforeach; ?>

      <?php if ($success): ?>
        <div class="alert alert-success">Your password has been updated. You can now log in with your new password.</div>
        <p class="text-center"><a class="btn" href="<?= h(admin_base_url()) ?>/login.php">Go to Login</a></p>
      <?php elseif ($tokenRecord && $tokenRecord['used_at'] === null && strtotime($tokenRecord['expires_at']) >= time()): ?>
        <form method="post" action="?token=<?= h($rawToken) ?>" novalidate>
          <?= csrf_field() ?>
          <input type="hidden" name="token" value="<?= h($rawToken) ?>">
          <div class="form-group">
            <label for="password">New Password</label>
            <input type="password" id="password" name="password" required minlength="8">
            <div class="field-hint">At least 8 characters.</div>
          </div>
          <div class="form-group">
            <label for="confirm_password">Confirm New Password</label>
            <input type="password" id="confirm_password" name="confirm_password" required minlength="8">
          </div>
          <div class="form-actions">
            <button type="submit" class="btn btn-block">Update Password</button>
          </div>
        </form>
      <?php endif; ?>

      <p class="text-center" style="margin-top:14px;"><a href="<?= h(admin_base_url()) ?>/login.php">← Back to Login</a></p>
    </div>
  </div>
</main>
</body>
</html>
