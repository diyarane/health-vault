<?php
/**
 * Public site header. Expects $pageTitle to optionally be set before include.
 * Expects require_once of functions.php/auth.php to have already happened.
 */
$base = site_base_url();
$current = basename($_SERVER['SCRIPT_NAME']);
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= h(($pageTitle ?? 'Home') . ' — ' . APP_NAME) ?></title>
<link rel="stylesheet" href="<?= h($base) ?>/assets/css/style.css?v=<?= time() ?>">
</head>
<body class="<?= $current === 'index.php' ? 'page-home' : ($current === 'about.php' ? 'page-about' : '') ?>">
<header class="site-header">
  <div class="container header-inner">
    <a class="brand" href="<?= h($base) ?>/public/index.php">
      <span class="brand-mark">✚</span> <?= h(APP_NAME) ?>
    </a>
    <nav class="site-nav">
      <a href="<?= h($base) ?>/public/index.php" class="<?= $current === 'index.php' ? 'active' : '' ?>">Home</a>
      <a href="<?= h($base) ?>/public/medical-card.php" class="<?= $current === 'medical-card.php' ? 'active' : '' ?>">Medical Card</a>
      <a href="<?= h($base) ?>/public/about.php" class="<?= $current === 'about.php' ? 'active' : '' ?>">About Us</a>
      <a href="<?= h($base) ?>/public/contact.php" class="<?= $current === 'contact.php' ? 'active' : '' ?>">Contact Us</a>
      <a href="<?= h($base) ?>/admin/login.php" class="nav-admin-link">Admin</a>
    </nav>
  </div>
</header>
<main class="site-main">
  <div class="container">
    <?php foreach (flash_get_all() as $msg): ?>
      <div class="alert alert-<?= h($msg['type']) ?>"><?= h($msg['message']) ?></div>
    <?php endforeach; ?>
