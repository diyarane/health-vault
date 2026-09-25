<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

$pageTitle = 'Contact Us';
$page = get_page_content('contact');

$errors = [];
$old = ['name' => '', 'email' => '', 'subject' => '', 'message' => ''];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $old['name']    = trim($_POST['name'] ?? '');
    $old['email']   = trim($_POST['email'] ?? '');
    $old['subject'] = trim($_POST['subject'] ?? '');
    $old['message'] = trim($_POST['message'] ?? '');

    if ($old['name'] === '') {
        $errors['name'] = 'Name is required.';
    }
    if ($old['email'] === '') {
        $errors['email'] = 'Email is required.';
    } elseif (!is_valid_email($old['email'])) {
        $errors['email'] = 'Please enter a valid email address.';
    }
    if ($old['message'] === '') {
        $errors['message'] = 'Message is required.';
    }

    if (empty($errors)) {
        $stmt = get_db()->prepare(
            'INSERT INTO inquiries (name, email, subject, message, status, created_at, updated_at)
             VALUES (:name, :email, :subject, :message, "unread", NOW(), NOW())'
        );
        $stmt->execute([
            'name'    => $old['name'],
            'email'   => $old['email'],
            'subject' => $old['subject'] !== '' ? $old['subject'] : null,
            'message' => $old['message'],
        ]);
        $success = true;
        $old = ['name' => '', 'email' => '', 'subject' => '', 'message' => ''];
    }
}

include __DIR__ . '/../includes/header.php';
?>

<div class="panel">
  <h2><?= h($page['title'] ?? 'Contact Us') ?></h2>
  <?php if ($page && $page['content']): ?>
    <p><?= nl2br(h($page['content'])) ?></p>
  <?php endif; ?>

  <?php if ($page && ($page['contact_email'] || $page['contact_phone'] || $page['contact_address'])): ?>
    <ul style="list-style:none;padding:0;color:#475569;">
      <?php if ($page['contact_email']): ?><li>✉️ <?= h($page['contact_email']) ?></li><?php endif; ?>
      <?php if ($page['contact_phone']): ?><li>📞 <?= h($page['contact_phone']) ?></li><?php endif; ?>
      <?php if ($page['contact_address']): ?><li>📍 <?= h($page['contact_address']) ?></li><?php endif; ?>
    </ul>
  <?php endif; ?>
</div>

<div class="panel" style="max-width:640px;">
  <h2>Send Us a Message</h2>

  <?php if ($success): ?>
    <div class="alert alert-success">Thank you! Your message has been received. We'll get back to you soon.</div>
  <?php endif; ?>

  <form method="post" action="" novalidate>
    <?= csrf_field() ?>
    <div class="form-row">
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
    </div>
    <div class="form-group">
      <label for="subject">Subject <span class="text-muted">(optional)</span></label>
      <input type="text" id="subject" name="subject" value="<?= h($old['subject']) ?>">
    </div>
    <div class="form-group">
      <label for="message">Message</label>
      <textarea id="message" name="message"><?= h($old['message']) ?></textarea>
      <?php if (!empty($errors['message'])): ?><div class="field-error"><?= h($errors['message']) ?></div><?php endif; ?>
    </div>
    <div class="form-actions">
      <button type="submit" class="btn">Send Message</button>
    </div>
  </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
