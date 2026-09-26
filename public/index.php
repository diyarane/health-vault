<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

$pageTitle = 'Home';
include __DIR__ . '/../includes/header.php';
?>

<section class="hero">
  <div class="hero-content">
    <h1>Your medical information, instantly accessible.</h1>
    <p>
      Health Vault stores your essential medical details securely and lets you retrieve
      them anytime with a single reference number — no paperwork, no delays, ready for
      emergencies, hospital visits, and routine checkups.
    </p>
    <a class="btn" href="<?= h(site_base_url()) ?>/public/medical-card.php">Access My Medical Card</a>
  </div>
  <div class="hero-visual"></div>
</section>

<section class="feature-grid">
  <div class="feature-card">
    <div class="icon">🪪</div>
    <h3>Digital Medical Card</h3>
    <p class="text-muted">Every record gets a unique reference number that acts as your secure medical ID.</p>
  </div>
  <div class="feature-card">
    <div class="icon">🔍</div>
    <h3>Instant Retrieval</h3>
    <p class="text-muted">Enter your reference number to pull up your details in seconds — anywhere, anytime.</p>
  </div>
  <div class="feature-card">
    <div class="icon">🖨️</div>
    <h3>Print &amp; Carry</h3>
    <p class="text-muted">Print a clean, wallet-friendly copy of your medical card for offline emergencies.</p>
  </div>
  <div class="feature-card">
    <div class="icon">🔒</div>
    <h3>Managed Securely</h3>
    <p class="text-muted">Records are managed by verified administrators using secure, authenticated tools.</p>
  </div>
</section>

<section class="panel">
  <h2>How it works</h2>
  <ol>
    <li>An administrator creates your medical card record and gives you a unique reference number.</li>
    <li>Visit the <a href="<?= h(site_base_url()) ?>/public/medical-card.php">Medical Card</a> page and enter your reference number.</li>
    <li>View your details instantly, and print a copy to keep with you.</li>
  </ol>
  <p>
    Have a question? Visit our <a href="<?= h(site_base_url()) ?>/public/contact.php">Contact Us</a> page
    or learn more <a href="<?= h(site_base_url()) ?>/public/about.php">About Us</a>.
  </p>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
