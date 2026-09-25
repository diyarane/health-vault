<?php
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';
require_admin_login();

$db = get_db();
$page = get_page_content('contact');
$errors = [];
$old = [
    'title' => $page['title'] ?? 'Contact Us',
    'content' => $page['content'] ?? '',
    'contact_email' => $page['contact_email'] ?? '',
    'contact_phone' => $page['contact_phone'] ?? '',
    'contact_address' => $page['contact_address'] ?? '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    foreach ($old as $key => $_) {
        $old[$key] = trim($_POST[$key] ?? '');
    }

    if ($old['title'] === '') {
        $errors['title'] = 'Title is required.';
    }
    if ($old['content'] === '') {
        $errors['content'] = 'Content is required.';
    }
    if ($old['contact_email'] !== '' && !is_valid_email($old['contact_email'])) {
        $errors['contact_email'] = 'Please enter a valid email address.';
    }

    if (empty($errors)) {
        $params = [
            'title' => $old['title'], 'content' => $old['content'],
            'contact_email' => $old['contact_email'] !== '' ? $old['contact_email'] : null,
            'contact_phone' => $old['contact_phone'] !== '' ? $old['contact_phone'] : null,
            'contact_address' => $old['contact_address'] !== '' ? $old['contact_address'] : null,
        ];
        if ($page) {
            $params['slug'] = 'contact';
            $upd = $db->prepare(
                'UPDATE pages SET title = :title, content = :content, contact_email = :contact_email,
                 contact_phone = :contact_phone, contact_address = :contact_address, updated_at = NOW() WHERE slug = :slug'
            );
            $upd->execute($params);
        } else {
            $params['slug'] = 'contact';
            $ins = $db->prepare(
                'INSERT INTO pages (slug, title, content, contact_email, contact_phone, contact_address, updated_at)
                 VALUES (:slug, :title, :content, :contact_email, :contact_phone, :contact_address, NOW())'
            );
            $ins->execute($params);
        }
        flash_set('success', 'Contact Us page updated successfully.');
        redirect(admin_base_url() . '/pages/contact.php');
    }
}

$adminBase = admin_base_url();
$pageTitle = 'Edit Contact Us';
include __DIR__ . '/../../includes/admin-header.php';
?>

<div class="panel" style="max-width:720px;">
  <form method="post" action="" novalidate>
    <?= csrf_field() ?>
    <div class="form-group">
      <label for="title">Title</label>
      <input type="text" id="title" name="title" value="<?= h($old['title']) ?>">
      <?php if (!empty($errors['title'])): ?><div class="field-error"><?= h($errors['title']) ?></div><?php endif; ?>
    </div>
    <div class="form-group">
      <label for="content">Intro Content</label>
      <textarea id="content" name="content" style="min-height:140px;"><?= h($old['content']) ?></textarea>
      <?php if (!empty($errors['content'])): ?><div class="field-error"><?= h($errors['content']) ?></div><?php endif; ?>
    </div>
    <div class="form-row">
      <div class="form-group">
        <label for="contact_email">Contact Email</label>
        <input type="email" id="contact_email" name="contact_email" value="<?= h($old['contact_email']) ?>">
        <?php if (!empty($errors['contact_email'])): ?><div class="field-error"><?= h($errors['contact_email']) ?></div><?php endif; ?>
      </div>
      <div class="form-group">
        <label for="contact_phone">Contact Phone</label>
        <input type="text" id="contact_phone" name="contact_phone" value="<?= h($old['contact_phone']) ?>">
      </div>
    </div>
    <div class="form-group">
      <label for="contact_address">Contact Address</label>
      <input type="text" id="contact_address" name="contact_address" value="<?= h($old['contact_address']) ?>">
    </div>
    <div class="form-actions">
      <button type="submit" class="btn">Save Changes</button>
      <a class="btn btn-outline" href="<?= h(site_base_url()) ?>/public/contact.php" target="_blank">Preview Public Page</a>
    </div>
  </form>
</div>

<?php include __DIR__ . '/../../includes/admin-footer.php'; ?>
