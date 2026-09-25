<?php
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';
require_admin_login();

$db = get_db();
$errors = [];
$old = [
    'reference_number' => generate_reference_number(),
    'full_name' => '', 'date_of_birth' => '', 'gender' => '', 'blood_group' => '',
    'contact_number' => '', 'email' => '', 'address' => '',
    'emergency_contact_name' => '', 'emergency_contact_number' => '',
    'known_allergies' => '', 'notes' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    foreach ($old as $key => $_) {
        $old[$key] = trim($_POST[$key] ?? '');
    }
    if ($old['reference_number'] === '') {
        $old['reference_number'] = generate_reference_number();
    }

    // ----- Server-side validation -----
    if ($old['reference_number'] === '') {
        $errors['reference_number'] = 'Reference number is required.';
    } elseif (!preg_match('/^[A-Za-z0-9\-]{4,40}$/', $old['reference_number'])) {
        $errors['reference_number'] = 'Reference number may only contain letters, numbers, and dashes.';
    }
    if ($old['full_name'] === '') {
        $errors['full_name'] = 'Full name is required.';
    }
    if ($old['contact_number'] === '') {
        $errors['contact_number'] = 'Contact number is required.';
    } elseif (!preg_match('/^[0-9+\-\s()]{7,20}$/', $old['contact_number'])) {
        $errors['contact_number'] = 'Please enter a valid contact number.';
    }
    if ($old['email'] === '') {
        $errors['email'] = 'Email is required.';
    } elseif (!is_valid_email($old['email'])) {
        $errors['email'] = 'Please enter a valid email address.';
    }
    if ($old['date_of_birth'] !== '' && !strtotime($old['date_of_birth'])) {
        $errors['date_of_birth'] = 'Please enter a valid date.';
    }

    // Duplicate reference number check.
    if (empty($errors['reference_number'])) {
        $dup = $db->prepare('SELECT id FROM medical_cards WHERE reference_number = :ref LIMIT 1');
        $dup->execute(['ref' => $old['reference_number']]);
        if ($dup->fetch()) {
            $errors['reference_number'] = 'This reference number already exists.';
        }
    }

    if (empty($errors)) {
        $stmt = $db->prepare(
            'INSERT INTO medical_cards
                (reference_number, full_name, date_of_birth, gender, blood_group, contact_number, email, address,
                 emergency_contact_name, emergency_contact_number, known_allergies, notes, created_at, updated_at)
             VALUES
                (:reference_number, :full_name, :date_of_birth, :gender, :blood_group, :contact_number, :email, :address,
                 :emergency_contact_name, :emergency_contact_number, :known_allergies, :notes, NOW(), NOW())'
        );
        try {
            $stmt->execute([
                'reference_number' => $old['reference_number'],
                'full_name' => $old['full_name'],
                'date_of_birth' => $old['date_of_birth'] !== '' ? date('Y-m-d', strtotime($old['date_of_birth'])) : null,
                'gender' => $old['gender'] !== '' ? $old['gender'] : null,
                'blood_group' => $old['blood_group'] !== '' ? $old['blood_group'] : null,
                'contact_number' => $old['contact_number'],
                'email' => $old['email'],
                'address' => $old['address'] !== '' ? $old['address'] : null,
                'emergency_contact_name' => $old['emergency_contact_name'] !== '' ? $old['emergency_contact_name'] : null,
                'emergency_contact_number' => $old['emergency_contact_number'] !== '' ? $old['emergency_contact_number'] : null,
                'known_allergies' => $old['known_allergies'] !== '' ? $old['known_allergies'] : null,
                'notes' => $old['notes'] !== '' ? $old['notes'] : null,
            ]);
            flash_set('success', 'Medical card "' . $old['reference_number'] . '" was created successfully.');
            redirect(admin_base_url() . '/medical-cards/index.php');
        } catch (PDOException $e) {
            // Catch a race-condition duplicate (unique constraint) gracefully.
            if ($e->getCode() === '23000') {
                $errors['reference_number'] = 'This reference number already exists.';
            } else {
                error_log('Add medical card failed: ' . $e->getMessage());
                $errors['general'] = 'Something went wrong while saving. Please try again.';
            }
        }
    }
}

$adminBase = admin_base_url();
$pageTitle = 'Add Medical Card';
include __DIR__ . '/../../includes/admin-header.php';
?>

<div class="panel" style="max-width:760px;">
  <?php if (!empty($errors['general'])): ?>
    <div class="alert alert-error"><?= h($errors['general']) ?></div>
  <?php endif; ?>

  <form method="post" action="" novalidate>
    <?= csrf_field() ?>
    <div class="form-row">
      <div class="form-group">
        <label for="reference_number">Reference Number</label>
        <input type="text" id="reference_number" name="reference_number" value="<?= h($old['reference_number']) ?>">
        <?php if (!empty($errors['reference_number'])): ?><div class="field-error"><?= h($errors['reference_number']) ?></div><?php endif; ?>
        <div class="field-hint">Auto-generated; you may edit it if you have your own numbering scheme.</div>
      </div>
      <div class="form-group">
        <label for="full_name">Full Name</label>
        <input type="text" id="full_name" name="full_name" value="<?= h($old['full_name']) ?>">
        <?php if (!empty($errors['full_name'])): ?><div class="field-error"><?= h($errors['full_name']) ?></div><?php endif; ?>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label for="date_of_birth">Date of Birth</label>
        <input type="date" id="date_of_birth" name="date_of_birth" value="<?= h($old['date_of_birth']) ?>">
        <?php if (!empty($errors['date_of_birth'])): ?><div class="field-error"><?= h($errors['date_of_birth']) ?></div><?php endif; ?>
      </div>
      <div class="form-group">
        <label for="gender">Gender</label>
        <select id="gender" name="gender">
          <option value="">-- Select --</option>
          <?php foreach (['Male', 'Female', 'Other'] as $g): ?>
            <option value="<?= h($g) ?>" <?= $old['gender'] === $g ? 'selected' : '' ?>><?= h($g) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label for="blood_group">Blood Group</label>
        <select id="blood_group" name="blood_group">
          <option value="">-- Select --</option>
          <?php foreach (['O+','O-','A+','A-','B+','B-','AB+','AB-'] as $bg): ?>
            <option value="<?= h($bg) ?>" <?= $old['blood_group'] === $bg ? 'selected' : '' ?>><?= h($bg) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label for="contact_number">Contact Number</label>
        <input type="tel" id="contact_number" name="contact_number" value="<?= h($old['contact_number']) ?>">
        <?php if (!empty($errors['contact_number'])): ?><div class="field-error"><?= h($errors['contact_number']) ?></div><?php endif; ?>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= h($old['email']) ?>">
        <?php if (!empty($errors['email'])): ?><div class="field-error"><?= h($errors['email']) ?></div><?php endif; ?>
      </div>
      <div class="form-group">
        <label for="address">Address</label>
        <input type="text" id="address" name="address" value="<?= h($old['address']) ?>">
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label for="emergency_contact_name">Emergency Contact Name</label>
        <input type="text" id="emergency_contact_name" name="emergency_contact_name" value="<?= h($old['emergency_contact_name']) ?>">
      </div>
      <div class="form-group">
        <label for="emergency_contact_number">Emergency Contact Number</label>
        <input type="tel" id="emergency_contact_number" name="emergency_contact_number" value="<?= h($old['emergency_contact_number']) ?>">
      </div>
    </div>

    <div class="form-group">
      <label for="known_allergies">Known Allergies</label>
      <input type="text" id="known_allergies" name="known_allergies" value="<?= h($old['known_allergies']) ?>">
    </div>

    <div class="form-group">
      <label for="notes">Notes</label>
      <textarea id="notes" name="notes"><?= h($old['notes']) ?></textarea>
    </div>

    <div class="form-actions">
      <button type="submit" class="btn">Save Medical Card</button>
      <a class="btn btn-outline" href="index.php">Cancel</a>
    </div>
  </form>
</div>

<?php include __DIR__ . '/../../includes/admin-footer.php'; ?>
