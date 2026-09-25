<?php
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';
require_admin_login();

$db = get_db();
$page = get_page_content('about');
$errors = [];
$old = ['title' => $page['title'] ?? 'About Us', 'content' => $page['content'] ?? ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $old['title']   = trim($_POST['title'] ?? '');
    $old['content'] = trim($_POST['content'] ?? '');

    if ($old['title'] === '') {
        $errors['title'] = 'Title is required.';
    }
    if ($old['content'] === '') {
        $errors['content'] = 'Content is required.';
    }

    if (empty($errors)) {
        if ($page) {
            $upd = $db->prepare('UPDATE pages SET title = :title, content = :content, updated_at = NOW() WHERE slug = :slug');
            $upd->execute(['title' => $old['title'], 'content' => $old['content'], 'slug' => 'about']);
        } else {
            $ins = $db->prepare('INSERT INTO pages (slug, title, content, updated_at) VALUES (:slug, :title, :content, NOW())');
            $ins->execute(['slug' => 'about', 'title' => $old['title'], 'content' => $old['content']]);
        }
        flash_set('success', 'About Us page updated successfully.');
        redirect(admin_base_url() . '/pages/about.php');
    }
}

$adminBase = admin_base_url();
$pageTitle = 'Edit About Us';
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
      <label for="content">Content</label>
      <textarea id="content" name="content" style="min-height:220px;"><?= h($old['content']) ?></textarea>
      <?php if (!empty($errors['content'])): ?><div class="field-error"><?= h($errors['content']) ?></div><?php endif; ?>
    </div>
    <div class="form-actions">
      <button type="submit" class="btn">Save Changes</button>
      <a class="btn btn-outline" href="<?= h(site_base_url()) ?>/public/about.php" target="_blank">Preview Public Page</a>
    </div>
  </form>
</div>

<?php include __DIR__ . '/../../includes/admin-footer.php'; ?>
