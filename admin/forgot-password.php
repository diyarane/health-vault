<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

if (is_admin_logged_in()) {
    redirect(admin_base_url() . '/dashboard.php');
}

$errors = [];
$email = '';
$devResetUrl = null; // Only ever shown in APP_DEBUG / local dev mode — see README.

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $email = trim($_POST['email'] ?? '');

    if ($email === '' || !is_valid_email($email)) {
        $errors[] = 'Please enter a valid email address.';
    } else {
        $stmt = get_db()->prepare('SELECT id FROM admins WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $admin = $stmt->fetch();

        // Always show the same confirmation regardless of whether the email
        // exists, so we don't leak which emails are registered admins.
        if ($admin) {
            $rawToken  = bin2hex(random_bytes(32));
            $tokenHash = hash('sha256', $rawToken);
            $expiresAt = date('Y-m-d H:i:s', time() + (RESET_TOKEN_TTL_MINUTES * 60));

            $ins = get_db()->prepare(
                'INSERT INTO password_resets (admin_id, token_hash, expires_at, created_at)
                 VALUES (:admin_id, :token_hash, :expires_at, NOW())'
            );
            $ins->execute([
                'admin_id'   => $admin['id'],
                'token_hash' => $tokenHash,
                'expires_at' => $expiresAt,
            ]);

            $resetLink = admin_base_url() . '/reset-password.php?token=' . $rawToken;

            // No email service is configured for this local/dev build.
            // We do NOT pretend an email was sent — instead we surface a
            // development reset link so the flow can be completed locally.
            if (APP_DEBUG) {
                $devResetUrl = $resetLink;
            }
        }

        flash_set('info', 'If that email is registered, a password reset link has been generated.');
    }
}

$pageTitle = 'Forgot Password';
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
      <h2>Forgot Password</h2>
      <p class="text-muted">Enter your admin email address and we'll generate a password reset link.</p>

      <?php foreach ($errors as $err): ?><div class="alert alert-error"><?= h($err) ?></div><?php endforeach; ?>
      <?php foreach (flash_get_all() as $msg): ?>
        <div class="alert alert-<?= h($msg['type']) ?>"><?= h($msg['message']) ?></div>
      <?php endforeach; ?>

      <?php if ($devResetUrl): ?>
        <div class="alert alert-warning">
          <strong>Development mode:</strong> no email service is configured, so here is your reset link
          (this box is only shown because APP_DEBUG is enabled):<br>
          <a href="<?= h($devResetUrl) ?>"><?= h($devResetUrl) ?></a>
        </div>
      <?php endif; ?>

      <form method="post" action="" novalidate>
        <?= csrf_field() ?>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" value="<?= h($email) ?>" required autofocus>
        </div>
        <div class="form-actions">
          <button type="submit" class="btn btn-block">Send Reset Link</button>
        </div>
      </form>
      <p class="text-center" style="margin-top:14px;"><a href="<?= h(admin_base_url()) ?>/login.php">← Back to Login</a></p>
    </div>
  </div>
</main>
</body>
</html>
