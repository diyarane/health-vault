<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

$pageTitle = 'About Us';
$page = get_page_content('about');
include __DIR__ . '/../includes/header.php';
?>

<!-- ROW 1: About Intro Card -->
<div class="abt-card abt-intro">
  <div class="abt-intro-top">
    <div>
      <span class="abt-badge">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/><path d="M3.22 12H9.5l.5-1 2 4.5 2-7 1.5 3.5h5.27"/></svg>
        Healthcare Card Platform
      </span>
      <h2 class="abt-heading">About Us</h2>
    </div>
    <?php if ($page): ?>
    <span class="abt-updated">
      <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
      Updated: <?= h(format_datetime($page['updated_at'])) ?>
    </span>
    <?php endif; ?>
  </div>
  <?php if ($page): ?>
  <p class="abt-desc"><?= nl2br(h($page['content'])) ?></p>
  <?php else: ?>
  <p class="abt-desc" style="color:#6b7280;">Content coming soon.</p>
  <?php endif; ?>
</div>

<!-- ROW 2: Feature Cards -->
<h3 class="abt-row-title">Built for Simpler Healthcare Access</h3>
<div class="abt-features">
  <div class="abt-card abt-feature">
    <div class="abt-icon-box">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
    </div>
    <h4>Instant Medical Access</h4>
    <p>Retrieve essential medical details using a unique reference number.</p>
  </div>
  <div class="abt-card abt-feature">
    <div class="abt-icon-box">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg>
    </div>
    <h4>Secure Record Management</h4>
    <p>Authenticated administration keeps medical records controlled and protected.</p>
  </div>
  <div class="abt-card abt-feature">
    <div class="abt-icon-box">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 2v2"/><path d="M7 22v-2a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2"/><path d="M8 2v2"/><circle cx="12" cy="11" r="3"/><rect x="3" y="4" width="18" height="18" rx="2"/></svg>
    </div>
    <h4>Digital Medical Cards</h4>
    <p>Create clean, printable medical cards for emergencies and routine visits.</p>
  </div>
  <div class="abt-card abt-feature">
    <div class="abt-icon-box">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
    </div>
    <h4>Simple Administration</h4>
    <p>Manage records and enquiries from one centralized interface.</p>
  </div>
</div>

<!-- ROW 3: Statistics -->
<div class="abt-card abt-stats-card">
  <div class="abt-stats">
    <div class="abt-stat">
      <div class="abt-stat-num">5+</div>
      <div class="abt-stat-lbl">Core Modules</div>
    </div>
    <div class="abt-stat-sep"></div>
    <div class="abt-stat">
      <div class="abt-stat-num">100%</div>
      <div class="abt-stat-lbl">Digital Workflow</div>
    </div>
    <div class="abt-stat-sep"></div>
    <div class="abt-stat">
      <div class="abt-stat-num">24/7</div>
      <div class="abt-stat-lbl">Record Availability</div>
    </div>
    <div class="abt-stat-sep"></div>
    <div class="abt-stat">
      <div class="abt-stat-num">1</div>
      <div class="abt-stat-lbl">Unique Reference per Card</div>
    </div>
  </div>
</div>

<!-- ROW 4: Bottom Two Cards -->
<div class="abt-bottom">
  <div class="abt-card abt-info">
    <div class="abt-info-head">
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0f766e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>
      <h4>Designed Around Real-World Simplicity</h4>
    </div>
    <p>Health Vault reduces paperwork and makes essential medical information easier to retrieve during routine visits and emergencies.</p>
  </div>
  <div class="abt-card abt-info abt-vision">
    <div class="abt-info-head">
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/></svg>
      <h4>Our Vision</h4>
    </div>
    <p>To make essential medical information easier to access, manage and carry — keeping the experience simple for individuals and administrators.</p>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
