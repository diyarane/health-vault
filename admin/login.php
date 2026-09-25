<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

if (is_admin_logged_in()) {
    redirect(admin_base_url() . '/dashboard.php');
}

$errors = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $email    = trim($_POST['email'] ?? '');
    $password = (string) ($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        $errors[] = 'Invalid email or password.';
    } else {
        $stmt = get_db()->prepare('SELECT * FROM admins WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $admin = $stmt->fetch();

        // Deliberately generic message — do not reveal which field was wrong.
        if (!$admin || !password_verify($password, $admin['password'])) {
            $errors[] = 'Invalid email or password.';
        } else {
            admin_login($admin);
            $intended = $_SESSION['intended_url'] ?? null;
            unset($_SESSION['intended_url']);
            redirect($intended ?: (admin_base_url() . '/dashboard.php'));
        }
    }
}

$pageTitle = 'Admin Login';
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
  <div class="container" style="max-width:420px;">
    <div class="panel" style="margin-top:60px;">
      <h2 class="text-center">✚ <?= h(APP_NAME) ?> Admin</h2>
      <p class="text-muted text-center">Sign in to manage medical cards, inquiries, and reports.</p>

      <?php foreach ($errors as $err): ?>
        <div class="alert alert-error"><?= h($err) ?></div>
      <?php endforeach; ?>
      <?php foreach (flash_get_all() as $msg): ?>
        <div class="alert alert-<?= h($msg['type']) ?>"><?= h($msg['message']) ?></div>
      <?php endforeach; ?>

      <form method="post" action="" novalidate>
        <?= csrf_field() ?>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" value="<?= h($email) ?>" required autofocus>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required>
        </div>
        <div class="form-actions">
          <button type="submit" class="btn btn-block">Login</button>
        </div>
      </form>
      <p class="text-center" style="margin-top:14px;">
        <a href="<?= h(admin_base_url()) ?>/forgot-password.php">Forgot your password?</a>
      </p>
      <p class="text-center text-muted" style="margin-top:18px;">
        <a href="<?= h($siteBase) ?>/public/index.php">← Back to Health Vault</a>
      </p>
    </div>
  </div>
</main>
</body>
</html>
