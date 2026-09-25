<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_admin_login();

$db = get_db();
$admin = current_admin();
$errors = [];
$old = [
    'name' => $admin['name'],
    'email' => $admin['email'],
    'mobile_number' => $admin['mobile_number'] ?? '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $old['name']          = trim($_POST['name'] ?? '');
    $old['email']         = trim($_POST['email'] ?? '');
    $old['mobile_number'] = trim($_POST['mobile_number'] ?? '');

    if ($old['name'] === '') {
        $errors['name'] = 'Name is required.';
    }
    if ($old['email'] === '') {
        $errors['email'] = 'Email is required.';
    } elseif (!is_valid_email($old['email'])) {
        $errors['email'] = 'Please enter a valid email address.';
    }

    if (empty($errors['email'])) {
        $dup = $db->prepare('SELECT id FROM admins WHERE email = :email AND id != :id LIMIT 1');
        $dup->execute(['email' => $old['email'], 'id' => $admin['id']]);
        if ($dup->fetch()) {
            $errors['email'] = 'This email is already in use by another account.';
        }
    }

    if (empty($errors)) {
        $upd = $db->prepare(
            'UPDATE admins SET name = :name, email = :email, mobile_number = :mobile_number, updated_at = NOW() WHERE id = :id'
        );
        $upd->execute([
            'name' => $old['name'],
            'email' => $old['email'],
            'mobile_number' => $old['mobile_number'] !== '' ? $old['mobile_number'] : null,
            'id' => $admin['id'],
        ]);
        $_SESSION['admin_name']  = $old['name'];
        $_SESSION['admin_email'] = $old['email'];
        flash_set('success', 'Profile updated successfully.');
        redirect(admin_base_url() . '/profile.php');
    }
}

$adminBase = admin_base_url();
$pageTitle = 'Profile';
include __DIR__ . '/../includes/admin-header.php';
?>

<div class="panel" style="max-width:560px;">
  <form method="post" action="" novalidate>
    <?= csrf_field() ?>
    <div class="form-group">
      <label for="name">Name</label>
      <input type="text" id="name" name="name" value="<?= h($old['name']) ?>">
      <?php if (!empty($errors['name'])): ?><div class="field-error"><?= h($errors['name']) ?></div><?php endif; ?>
    </div>
    <div class="form-group">
      <label for="email">Email</label>
      <input type="email" id="email" name="email" value="<?= h($old['email']) ?>">
      <?php if (!empty($errors['email'])): ?><div class="field-error"><?= h($errors['email']) ?></div><?php endif; ?>
    </div>
    <div class="form-group">
      <label for="mobile_number">Mobile Number</label>
      <input type="tel" id="mobile_number" name="mobile_number" value="<?= h($old['mobile_number']) ?>">
    </div>
    <div class="form-actions">
      <button type="submit" class="btn">Save Changes</button>
    </div>
  </form>
</div>

<?php include __DIR__ . '/../includes/admin-footer.php'; ?>
