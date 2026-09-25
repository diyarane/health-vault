<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

$pageTitle = 'Medical Card';
$reference = trim($_GET['reference'] ?? '');
$card = null;
$errorMessage = null;
$searched = false;

if ($reference !== '') {
    $searched = true;

    // Basic input validation before touching the database.
    if (!preg_match('/^[A-Za-z0-9\-]{4,40}$/', $reference)) {
        $errorMessage = 'Please enter a valid reference number.';
    } else {
        $stmt = get_db()->prepare('SELECT * FROM medical_cards WHERE reference_number = :ref LIMIT 1');
        $stmt->execute(['ref' => $reference]);
        $card = $stmt->fetch();

        if (!$card) {
            // TC02 — invalid reference number must show a clear, friendly error.
            $errorMessage = 'No medical card was found for this reference number.';
        }
    }
} elseif (isset($_GET['reference'])) {
    $searched = true;
    $errorMessage = 'Please enter a reference number to search.';
}

include __DIR__ . '/../includes/header.php';
?>

<div class="panel">
  <h2>Find Your Medical Card</h2>
  <p class="text-muted">Enter the unique reference number provided to you to view your medical card.</p>
  <form method="get" action="" class="search-form" style="max-width:480px;">
    <div class="form-group" style="flex:1;">
      <label for="reference">Reference Number</label>
      <input type="text" id="reference" name="reference" placeholder="e.g. HV-2026-A1B2C3"
             value="<?= h($reference) ?>" required>
    </div>
    <div class="form-group" style="align-self:flex-end;">
      <button type="submit" class="btn">Search</button>
    </div>
  </form>

  <?php if ($errorMessage): ?>
    <div class="alert alert-error"><?= h($errorMessage) ?></div>
  <?php endif; ?>
</div>

<?php if ($card): ?>
  <div class="medical-card">
    <div class="medical-card-header">
      <h2>Health Vault Medical Card</h2>
      <span class="medical-card-ref"><?= h($card['reference_number']) ?></span>
    </div>
    <dl>
      <dt>Full Name</dt><dd><?= h($card['full_name']) ?></dd>
      <dt>Date of Birth</dt><dd><?= h(format_date($card['date_of_birth'])) ?></dd>
      <dt>Gender</dt><dd><?= h($card['gender'] ?: '—') ?></dd>
      <dt>Blood Group</dt><dd><?= h($card['blood_group'] ?: '—') ?></dd>
      <dt>Contact Number</dt><dd><?= h($card['contact_number']) ?></dd>
      <dt>Email</dt><dd><?= h($card['email']) ?></dd>
      <dt>Address</dt><dd><?= h($card['address'] ?: '—') ?></dd>
      <dt>Emergency Contact</dt>
      <dd><?= h($card['emergency_contact_name'] ?: '—') ?><?= $card['emergency_contact_number'] ? ' (' . h($card['emergency_contact_number']) . ')' : '' ?></dd>
      <dt>Known Allergies</dt><dd><?= h($card['known_allergies'] ?: 'None recorded') ?></dd>
      <dt>Issued On</dt><dd><?= h(format_date($card['created_at'])) ?></dd>
    </dl>
  </div>
  <p class="text-center" style="margin-top:20px;">
    <a class="btn btn-secondary" target="_blank"
       href="<?= h(site_base_url()) ?>/public/print-card.php?reference=<?= urlencode($card['reference_number']) ?>">
      🖨️ Print Medical Card
    </a>
  </p>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>
