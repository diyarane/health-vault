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
    <div class="icon">
      <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-contact"><path d="M16 2v2"/><path d="M7 22v-2a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2"/><path d="M8 2v2"/><circle cx="12" cy="11" r="3"/><rect x="3" y="4" width="18" height="18" rx="2"/></svg>
    </div>
    <h3>Digital Medical Card</h3>
    <p class="text-muted">Every record gets a unique reference number that acts as your secure medical ID.</p>
  </div>
  <div class="feature-card">
    <div class="icon">
      <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
    </div>
    <h3>Instant Retrieval</h3>
    <p class="text-muted">Enter your reference number to pull up your details in seconds — anywhere, anytime.</p>
  </div>
  <div class="feature-card">
    <div class="icon">
      <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-printer"><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 9V3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6"/><rect x="6" y="14" width="12" height="8" rx="1"/></svg>
    </div>
    <h3>Print &amp; Carry</h3>
    <p class="text-muted">Print a clean, wallet-friendly copy of your medical card for offline emergencies.</p>
  </div>
  <div class="feature-card">
    <div class="icon">
      <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-check"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg>
    </div>
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
