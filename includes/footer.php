  </div>
</main>
<footer class="site-footer">
  <div class="container">
    <p>&copy; <?= date('Y') ?> <?= h(APP_NAME) ?>. All rights reserved.</p>
    <p class="footer-links">
      <a href="<?= h(site_base_url()) ?>/public/about.php">About Us</a> ·
      <a href="<?= h(site_base_url()) ?>/public/contact.php">Contact Us</a> ·
      <a href="<?= h(site_base_url()) ?>/admin/login.php">Admin Login</a>
    </p>
  </div>
</footer>
<script src="<?= h(site_base_url()) ?>/assets/js/main.js"></script>
</body>
</html>
